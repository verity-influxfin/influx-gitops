<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Subloan_lib{
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/transaction_model');
		$this->CI->load->model('loan/subloan_model');
		$this->CI->load->library('credit_lib');
		$this->CI->load->library('target_lib');
    }

	public function get_info($target=array()){
		if($target->status == 5 && $target->delay == 1 && $target->delay_days > GRACE_PERIOD){
			$where 			= array(
				"target_id" => $target->id,
				"user_from" => $target->user_id,
				"status"	=> 1
			);
			$transaction 	= $this->CI->transaction_model->order_by("limit_date","asc")->get_many_by($where);
			if($transaction){
				$entering_date		= get_entering_date();
				$settlement_date 	= date("Y-m-d",strtotime($entering_date.' +'.SUBLOAN_RANGE_DAYS.' days'));

				$data = array(
					"remaining_principal"		=> 0,//剩餘本金
					"remaining_instalment"		=> 0,//剩餘期數
					"settlement_date"			=> $settlement_date,//結帳日
					"liquidated_damages"		=> 0,//違約金
					"delay_interest_payable"	=> 0,//延滯息
					"interest_payable"			=> 0,//應付利息
					"total"						=> 0,
				);
				$instalment 			= 0;
				$remaining_principal	= array();
				$interest_payable		= array();
				foreach($transaction as $key => $value){
					$remaining_principal[$value->user_to] 	= 0;
					$interest_payable[$value->user_to] 		= 0;
				}
				foreach($transaction as $key => $value){
					switch($value->source){
						case SOURCE_AR_PRINCIPAL: 
							$instalment 							= $value->instalment_no;
							$remaining_principal[$value->user_to]	+= $value->amount;
							break;
						case SOURCE_AR_INTEREST: 
							$interest_payable[$value->user_to]		+= $value->amount;			
							break;
						case SOURCE_AR_DAMAGE: 
							$data["liquidated_damages"] 			+= $value->amount;
							break;
						default:
							break;
					}
				} 

				$data["remaining_instalment"] 	= $target->instalment - $instalment;
				
				if($remaining_principal){
					foreach($remaining_principal as $k => $v){
						$data["remaining_principal"] 	+= $v;
						$data["delay_interest_payable"] += $this->CI->financial_lib->get_delay_interest( $v , $target->delay_days + SUBLOAN_RANGE_DAYS);
					}
					
					foreach($interest_payable as $k => $v){
						$data["interest_payable"] 	 += $v;
					}
				}
				$total 	= 	$data["remaining_principal"] + 
							$data["interest_payable"] + 
							$data["liquidated_damages"] + 
							$data["delay_interest_payable"];
				$data["sub_loan_fees"] 	= intval(round( $data["remaining_principal"] * SUB_LOAN_FEES / 100 ,0));
				$total += $data["sub_loan_fees"];
				$data["platform_fees"] 	= intval(round( $total * PLATFORM_FEES / (100-PLATFORM_FEES) ,0));
				$data["platform_fees"] 	= $data["platform_fees"] > PLATFORM_FEES_MIN?$data["platform_fees"]:PLATFORM_FEES_MIN;
				$total 					+= $data["platform_fees"];
				$data["total"]			= $total;
				return $data;
			}
		}
		return false;
	}

	public function apply_subloan($target,$data){
		if($target && $target->status==5){
			$info  = $this->get_info($target);
			if($info){
				$param = array(
					"target_id"			=> $target->id,
					"settlement_date"	=> $info["settlement_date"],
					"amount"			=> $info["total"],
					"platform_fee"		=> $info["platform_fees"],
					"subloan_fee"		=> $info["sub_loan_fees"],
					"instalment"		=> $data["instalment"],
					"repayment"			=> $data["repayment"]
				);
				$new_target_id = $this->add_subloan_target($target,$param);
				if($new_target_id){
					$param["new_target_id"] = $new_target_id;
					$rs = $this->CI->subloan_model->insert($param);
					if($rs){
						$this->CI->target_lib->insert_change_log($target->id,array("sub_status"=>1),$target->user_id);
						$this->CI->target_model->update($target->id,array("sub_status"=>1));
						return $rs;
					}
				}
			}
		}
		return false;
	}

	public function add_subloan_target($target,$subloan){
			$user_id 		= $target->user_id;
			$product_id 	= $target->product_id;
			$credit 		= $this->CI->credit_lib->get_credit($user_id,$product_id);
			if(!$credit){
				$rs 		= $this->CI->credit_lib->approve_credit($user_id,$product_id);
				if($rs){
					$credit = $this->CI->credit_lib->get_credit($user_id,$product_id);
				}
			}
			if($credit){
				$interest_rate	= $this->CI->credit_lib->get_rate($credit["level"],$subloan["instalment"]);
				if($interest_rate){
					$this->CI->load->library('contract_lib');
					$contract_id	= $this->CI->contract_lib->sign_contract("lend",["",$user_id,$subloan["amount"],$interest_rate,""]);
					if($contract_id){
						$target_no 		= $this->get_target_no();
						$param = array(
							"product_id"		=> $product_id,
							"user_id"			=> $user_id,
							"target_no"			=> $target_no,
							"amount"			=> $subloan["amount"],
							"loan_amount"		=> $subloan["amount"],
							"instalment"		=> $subloan["instalment"],
							"repayment"			=> $subloan["repayment"],
							"credit_level"		=> $credit["level"],
							"platform_fee"		=> $subloan["platform_fee"],
							"interest_rate"		=> $interest_rate,
							"contract_id"		=> $contract_id,
							"status"			=> "1",
							"sub_status"		=> "8",
							"remark"			=> $target->target_no.",轉換產品",
							"expire_time"		=> strtotime($subloan["settlement_date"].' '.CLOSING_TIME),
						);

						$rs = $this->CI->target_model->insert($param);
						return $rs;
					}
				}
			}

		return false;
	}
	
	public function signing_subloan($subloan,$data){

		if($subloan && $subloan->status==0){
			$target = $this->CI->target_model->get($subloan->new_target_id);
			if($target && $target->status==1){
				$param = array(
					"person_image"	=> $data["person_image"],
					"status"		=> 2,
				);
				$rs = $this->CI->target_model->update($target->id,$param);
				if($rs){
					$this->CI->target_lib->insert_change_log($target->id,$param,$target->user_id);
					$this->CI->subloan_model->update($subloan->id,array("status"=>1));
					return $rs;
				}
			}
		}
		return false;
	}
	
	public function subloan_verify_success($target = array(),$admin_id=0){
		if(!empty($target) && $target->status==2){
			$subloan	= $this->CI->subloan_model->get_by(array(
				"status"		=> 1,
				"new_target_id"	=> $target->id
			));
			
			if($subloan){
				$param = array(
					"status" 		=> 3 , 
					"launch_times"	=> 1
				);
				$this->CI->target_model->update($target->id, $param);
				$this->CI->target_lib->insert_change_log($target->id,$param,0,$admin_id);
				$this->CI->subloan_model->update($subloan->id,array("status"=>2));
				$this->CI->notification_lib->target_verify_success($target);
			}
		}
		return false;
	}
	
	public function subloan_verify_failed($new_target = array(),$admin_id=0,$remark="審批不通過"){
		if(!empty($new_target) && in_array($new_target->status,[1,2])){
			$subloan	= $this->CI->subloan_model->get_by(array(
				"status"		=> [0,1],
				"new_target_id"	=> $new_target->id
			));
			if($subloan){
				$rs = $this->CI->subloan_model->update($subloan->id,array("status"=>9));
				if($rs){
					$param = array(
						"loan_amount"		=> 0,
						"status"			=> "9",
						"remark"			=> $new_target->remark.",".$remark,
					);
					
					$this->CI->target_lib->insert_change_log($subloan->target_id,array("sub_status"=>0),0,$admin_id);
					$this->CI->target_lib->insert_change_log($subloan->new_target_id,$param,0,$admin_id);
					$this->CI->target_model->update($subloan->target_id,array("sub_status"=>0));
					$this->CI->target_model->update($subloan->new_target_id,$param);
					$this->CI->notification_lib->subloan_verify_failed($new_target->user_id,date("Y-m-d",$new_target->created_at),$new_target->amount,$new_target->target_no,$remark);
					return $rs;
				}
			}
		}
		return false;
	}
	
	//流標
	public function auction_ended($target){
		if($target->status == 3 && $target->expire_time < time()){
			$subloan	= $this->CI->subloan_model->get_by(array(
				"status"		=> 2,
				"new_target_id"	=> $target->id
			));
			if($subloan){
				$old_target = $this->CI->target_model->get($subloan->target_id);
				$info  		= $this->get_info($old_target);
				if($info){
					$subloan_param = array(
						"settlement_date"	=> $info["settlement_date"],
						"amount"			=> $info["total"],
						"platform_fee"		=> $info["platform_fees"],
						"subloan_fee"		=> $info["sub_loan_fees"],
					);
				
					$target_param = array(
						"amount"			=> $info["total"],
						"loan_amount"		=> $info["total"],
						"platform_fee"		=> $info["platform_fees"],
						"launch_times"		=> $target->launch_times + 1,
						"expire_time"		=> strtotime($info["settlement_date"].' '.CLOSING_TIME),
						"invested"			=> 0,
					);
					$contract = $this->CI->contract_lib->update_contract($target->contract_id,["",$target->user_id,$info["total"],$target->interest_rate,""]);
					if($contract){
						$rs = $this->CI->subloan_model->update($subloan->id,$subloan_param);
						if($rs){
							$this->CI->target_model->update($target->id,$target_param);
							return $rs;
						}
					}
				}
			}
		}
	}

	public function get_subloan($target){
		if($target){
			$where 		= array("status not"=>array(8,9),"target_id"=>$target->id);
			$subloan	= $this->CI->subloan_model->order_by("created_at","desc")->get_by($where);
			if($subloan){
				return $subloan;
			}
		}
		return false;
	}
	
	public function subloan_success_return($new_target = array(),$admin_id=0){
		if(!empty($new_target) && $new_target->status==4){
			$subloan	= $this->CI->subloan_model->get_by(array(
				"status"		=> 2,
				"new_target_id"	=> $new_target->id
			));
			if($subloan){
				$rs = $this->CI->subloan_model->update($subloan->id,array("status"=>9));
				if($rs){
					$this->CI->target_lib->insert_change_log($subloan->target_id,array("sub_status"=>0),0,$admin_id);
					$this->CI->target_model->update($subloan->target_id,array("sub_status"=>0));
					return $rs;
				}
			}
		}
		return false;
	}
	
	public function cancel_subloan($subloan,$user_id=0,$admin_id=0){
		if($subloan && in_array($subloan->status,array(0,1,2))){
			$rs = $this->CI->subloan_model->update($subloan->id,array("status"=>8));
			if($rs){
				$this->CI->target_lib->insert_change_log($subloan->target_id,array("sub_status"=>0),$user_id,$admin_id);
				$this->CI->target_lib->insert_change_log($subloan->new_target_id,array("status"=>8),$user_id,$admin_id);
				$this->CI->target_model->update($subloan->target_id,array("sub_status"=>0));
				$this->CI->target_model->update($subloan->new_target_id,array("status"=>8));
				return $rs;
			}
		}
		return false;
	}
	
	private function get_target_no(){
		$code = "STS".date("Ymd").rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(1, 9);
		$result = $this->CI->target_model->get_by('target_no',$code);
		if ($result) {
			return $this->get_target_no();
		}else{
			return $code;
		}
	}
	
}

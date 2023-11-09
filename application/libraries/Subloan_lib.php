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
				'target_id' => $target->id,
				'user_from' => $target->user_id,
				'status'	=> 1
			);
			$transaction 	= $this->CI->transaction_model->order_by('limit_date','asc')->get_many_by($where);
			if($transaction){
				$settlement_date 	= entering_date_range(get_entering_date())['edatetime'];

				$data = array(
					'remaining_principal'		=> 0,//剩餘本金
					'remaining_instalment'		=> 0,//剩餘期數
					'settlement_date'			=> $settlement_date,//結帳日
					'liquidated_damages'		=> 0,//違約金
					'delay_interest_payable'	=> 0,//延滯息
					'interest_payable'			=> 0,//應付利息
					'total'						=> 0,
				);
				$instalment 			= 0;
				$remaining_principal	= [];
				$interest_payable		= [];
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
							$data['liquidated_damages'] 			+= $value->amount;
							break;
                        case SOURCE_AR_DELAYINTEREST:
                            $data['delay_interest_payable'] 		+= $value->amount;
                            break;
						default:
							break;
					}
                    $limit_date = $value->limit_date;
				} 

				$data['remaining_instalment'] 	= $target->instalment - $instalment;
				
				if($remaining_principal){
                    $this->CI->load->model('loan/contract_model');
                    $contract = $this->CI->contract_model->get($target->contract_id);
					foreach($remaining_principal as $k => $v){
						$data['remaining_principal'] 	+= $v;
					}
					
					foreach($interest_payable as $k => $v){
						$data['interest_payable'] 	 += $v;
					}
				}
				$total 	= 	$data['remaining_principal'] + 
							$data['interest_payable'] + 
							$data['liquidated_damages'] + 
							$data['delay_interest_payable'];
				$data['sub_loan_fees'] 	= intval(round( $data['remaining_principal'] * SUB_LOAN_FEES / 100 ,0));
				$total += $data['sub_loan_fees'];
                $product_list = $this->CI->config->item('product_list');
				$data['platform_fees'] 	= $this->CI->financial_lib->get_platform_fee2($total, $product_list[$target->product_id]['charge_platform']);
				$total 					+= $data['platform_fees'];
				$data['total']			= $total;
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
					'target_id'			=> $target->id,
					'settlement_date'	=> $info['settlement_date'],
					'amount'			=> $info['total'],
					'platform_fee'		=> $info['platform_fees'],
					'subloan_fee'		=> $info['sub_loan_fees'],
					'instalment'		=> $data['instalment'],
					'repayment'			=> $data['repayment'],
                    'product_id'		=> $data['product_id']
				);
                $new_target_id = $this->add_subloan_target($target,$param);
                unset($param['product_id']);
                if($new_target_id){
                    $param['new_target_id'] = $new_target_id;
					$rs = $this->CI->subloan_model->insert($param);
					if($rs){
						$this->CI->target_lib->insert_change_log($target->id,array('sub_status'=>1),$target->user_id);
						$this->CI->target_model->update($target->id,array('sub_status'=>1));
						return $new_target_id;
					}
				}
			}
		}
		return false;
	}

	public function add_subloan_target($target,$subloan){
		$user_id 		= $target->user_id;
		$product_id 	= $target->product_id;
		isset($subloan['product_id'])?$product_id=$subloan['product_id']:'';
		$credit 		= $this->CI->credit_lib->get_credit($user_id,$product_id);
		if(!$credit){
			$rs 		= $this->CI->credit_lib->approve_credit($user_id,$product_id,0,null,false,false,false,$target->instalment);
			if($rs){
				$credit = $this->CI->credit_lib->get_credit($user_id,$product_id);
			}
		}
		if($credit){
			$interest_rate	= $this->CI->credit_lib->get_rate($credit['level'],$subloan['instalment'],$product_id);
			if($interest_rate){
			    $subloan_remark = $this->subloan_history($target->id);
				$this->CI->load->library('contract_lib');
				$contract_id	= $this->CI->contract_lib->sign_contract('lend',['',$user_id,$subloan['amount'],$interest_rate,'']);
				if($contract_id){
					$target_no 		= $this->get_target_no($product_id);
					$param = array(
						'product_id'		=> $product_id,
						'user_id'			=> $user_id,
						'target_no'			=> $target_no,
						'amount'			=> $subloan['amount'],
						'loan_amount'		=> $subloan['amount'],
						'instalment'		=> $subloan['instalment'],
						'repayment'			=> $subloan['repayment'],
                        'reason'            => $target->reason,
						'credit_level'		=> $credit['level'],
						'platform_fee'		=> $subloan['platform_fee'],
						'interest_rate'		=> $interest_rate,
						'contract_id'		=> $contract_id,
						'status'			=> '0',
						'sub_status'		=> '8',
						'remark'			=> $subloan_remark,
						'expire_time'		=> strtotime($subloan['settlement_date']),
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
					'person_image'	=> $data['person_image'],
					'status'		=> 2,
				);
				$rs = $this->CI->target_model->update($target->id,$param);
				if($rs){
					$this->CI->target_lib->insert_change_log($target->id,$param,$target->user_id);
					$this->CI->subloan_model->update($subloan->id,array('status'=>1));
					return $rs;
				}
			}
		}
		return false;
	}
	
	public function subloan_verify_success($target = array(),$admin_id=0){
		if(!empty($target) && $target->status==2){
			$subloan	= $this->CI->subloan_model->get_by(array(
				'status'		=> 1,
				'new_target_id'	=> $target->id
			));
			
			if($subloan){
			    if($target->expire_time < time()){
                    $this->renew_subloan($target,false);
                }
				$param = array(
					'status' 		=> 3 , 
					'launch_times'	=> 1
				);
				$this->CI->target_model->update($target->id, $param);
				$this->CI->target_lib->insert_change_log($target->id,$param,0,$admin_id);
				$this->CI->subloan_model->update($subloan->id,array('status'=>2));
				$this->CI->notification_lib->target_verify_success($target);
                $this->CI->target_lib->aiBiddingTarget($target);
            }
		}
		return false;
	}
	
	public function subloan_verify_failed($new_target = array(),$admin_id=0,$remark='審批不通過'){
		if(!empty($new_target) && in_array($new_target->status,[1,2])){
			$subloan	= $this->CI->subloan_model->get_by(array(
				'status'		=> [0,1],
				'new_target_id'	=> $new_target->id
			));
			if($subloan){
				$rs = $this->CI->subloan_model->update($subloan->id,array('status'=>9));
				if($rs){
					$param = array(
						'loan_amount' 	=> 0,
						'status'	 	=> '9',
						'remark'		=> $new_target->remark.','.$remark,
					);
					
					$this->CI->target_lib->insert_change_log($subloan->target_id,array('sub_status'=>0),0,$admin_id);
					$this->CI->target_lib->insert_change_log($subloan->new_target_id,$param,0,$admin_id);
					$this->CI->target_model->update($subloan->target_id,array('sub_status'=>0));
					$this->CI->target_model->update($subloan->new_target_id,$param);
					$this->CI->notification_lib->subloan_verify_failed($new_target->user_id,date('Y-m-d',$new_target->created_at),$new_target->amount,$new_target->target_no,$remark);
					return $rs;
				}
			}
		}
		return false;
	}

    public function subloan_cancel_bidding($target = array(),$admin_id=0,$remark="請連繫客服協助處理"){
        if(!empty($target) && $target->status==3){
            $subloan	= $this->CI->subloan_model->get_by(array(
                "status"		=> 2,
                "new_target_id"	=> $target->id
            ));

            if($subloan){
                $param = array(
                    "status" 		=> 2 ,
                    "launch_times"	=> 0
                );
                $this->CI->target_model->update($target->id, $param);
                $this->CI->target_lib->insert_change_log($target->id,$param,0,$admin_id);
                $this->CI->subloan_model->update($subloan->id,array("status"=>1));
                $this->CI->notification_lib->target_cancel_bidding($target->user_id,0,$remark);
            }
        }
        return false;
    }

    //更新借款金額
	public function renew_subloan($target,$msg=true){
		if(in_array($target->status,[2,3]) && $target->expire_time < time()){
			$subloan	= $this->CI->subloan_model->get_by(array(
				'status'		=> [1,2],
				'new_target_id'	=> $target->id
			));
			if($subloan){
				$old_target = $this->CI->target_model->get($subloan->target_id);
				$info  		= $this->get_info($old_target);
				if($info){
					$subloan_param = array(
						'settlement_date'	=> $info['settlement_date'],
						'amount'			=> $info['total'],
						'platform_fee'		=> $info['platform_fees'],
						'subloan_fee'		=> $info['sub_loan_fees'],
					);
				
					$target_param = array(
						'amount'			=> $info['total'],
						'loan_amount'		=> $info['total'],
						'platform_fee'		=> $info['platform_fees'],
						'launch_times'		=> $target->launch_times + 1,
						'expire_time'		=> strtotime($info['settlement_date']),
						'invested'			=> 0,
					);

					// 案件處理時間 <= 過期時間，代表延滯息尚未計算，不更新 expire_time，以利下次跑批可觸發判斷
					if(isset($old_target->handle_date) && $old_target->handle_date <= date("Y-m-d", $target->expire_time)) {
                        return FALSE;
                    }

                    $this->CI->load->library('contract_lib');
					$contract = $this->CI->contract_lib->update_contract($target->contract_id,['',$target->user_id,$info['total'],$target->interest_rate,'']);
					if($contract){
						$rs = $this->CI->subloan_model->update($subloan->id,$subloan_param);
						if($rs){
							$this->CI->target_model->update($target->id,$target_param);
                            if($msg){
                                $this->CI->load->model('user/user_model');
                                $user_info = $this->CI->user_model->get($target->user_id);
                                $this->CI->notification_lib->subloan_auction_failed($target->user_id,$old_target->handle_date,$info['total'],$target->target_no,$user_info->name);
                            }
                            return $rs;
                        }
                    }
				}
			}
		}
	}

    //流標並重新上架
    public function rollback_success_target($target,$admin_id=0){
        if($target && $target->status==4 && $target->script_status==0 ){
            $param = [
                'status'	=> 2,
            ];
            $rs = $this->CI->target_model->update($target->id,$param);
            $this->CI->target_lib->insert_change_log($target->id,$param,0,$admin_id);
            $target->status = 2;
            if($rs){
                $this->CI->load->model('loan/investment_model');
                $this->CI->load->model('transaction/frozen_amount_model');
                $investments = $this->CI->investment_model->get_many_by([
                    'target_id'	=> $target->id,
                    'status'	=> [0,1,2]
                ]);
                if($investments){
                    foreach($investments as $key => $value){
                        $this->CI->target_lib->insert_investment_change_log($value->id,['status'=>9],0,$admin_id);
                        $this->CI->investment_model->update($value->id,['status'=>9]);
                        if($value->frozen_status==1 && $value->frozen_id){
                            $this->CI->frozen_amount_model->update($value->frozen_id,['status'=>0]);
                        }
                    }
                }

                $subloan	= $this->CI->subloan_model->get_by(array(
                    'status'		=> 2,
                    'new_target_id'	=> $target->id
                ));
                if($subloan){
                    $this->renew_subloan($target,false);
                    $param = array(
                        'status' 		=> 3 ,
                    );
                    $this->CI->target_model->update($target->id, $param);
                    $this->CI->target_lib->insert_change_log($target->id,$param,0,$admin_id);
                }
            }
            return true;
        }
        return false;
    }

	public function get_subloan($target,$new_target=false){
		if($target||$new_target){
			$where 		= array(
				'status not' => array(8,9),
			);
			$new_target?$where['new_target_id']=$new_target->id:$where['target_id']=$target->id;
			$subloan	= $this->CI->subloan_model->order_by('created_at','desc')->get_by($where);
			if($subloan){
				return $subloan;
			}
		}
		return false;
	}
	
	public function subloan_success_return($new_target = array(),$admin_id=0){
		if(!empty($new_target) && $new_target->status==4){
			$subloan	= $this->CI->subloan_model->get_by(array(
				'status'		=> 2,
				'new_target_id'	=> $new_target->id
			));
			if($subloan){
				$rs = $this->CI->subloan_model->update($subloan->id,array('status'=>9));
				if($rs){
					$this->CI->target_lib->insert_change_log($subloan->target_id,array('sub_status'=>0),0,$admin_id);
					$this->CI->target_model->update($subloan->target_id,array('sub_status'=>0));
					return $rs;
				}
			}
		}
		return false;
	}
	
	public function cancel_subloan($subloan,$user_id=0,$admin_id=0){
		if($subloan && in_array($subloan->status,array(0,1,2))){
			$rs = $this->CI->subloan_model->update($subloan->id,array('status'=>8));
			if($rs){
                $info = $this->CI->target_model->get($subloan->new_target_id);
                if(isset($info)) {
                    if($info->status==4) {
                        // 滿標待放款也需要退
                        $this->CI->target_lib->cancel_success_target($info, $admin_id);
                    }else{
                        // 如果新案件是上架後，需退掉投資人的錢
                        if($info->status == 3) {
                            $this->CI->load->model('loan/investment_model');
                            $investments = $this->CI->investment_model->get_many_by([
                                'target_id' => $subloan->new_target_id,
                                'status' => [0, 1]
                            ]);
                            foreach ($investments as $inv_val) {
                                $this->CI->target_lib->cancel_investment($info, $inv_val, $user_id);
                            }
                        }
                        $this->CI->target_model->update($subloan->new_target_id,array('status'=>8));
                        $this->CI->target_lib->insert_change_log($subloan->new_target_id,array('status'=>8),$user_id,$admin_id);
                    }
                }
				$this->CI->target_lib->insert_change_log($subloan->target_id,array('sub_status'=>0),$user_id,$admin_id);
				$this->CI->target_model->update($subloan->target_id,array('sub_status'=>0));

				return $rs;
			}
		}
		return false;
	}
	
	private function get_target_no($product_id=0){
		$product_list 	= $this->CI->config->item('product_list');
		$alias 			= $product_list[$product_id]['alias'];
		$code 			= $alias.'S'.date('Ymd').rand(0, 9).rand(0, 9).rand(0, 9).rand(1, 9);
		$result = $this->CI->target_model->get_by('target_no',$code);
		if ($result) {
			return $this->get_target_no();
		}else{
			return $code;
		}
	}

    public function subloan_history($current_target=false){
	    $subloan_history_target[] = $current_target;
        $old_target = $current_target;
        $descri     = '此案件為產品轉換標的:
';
        while ($current_target) {
            $subloan_target = $this->CI->subloan_model->order_by('created_at','desc')->get_by([
                'new_target_id' => $old_target,
                'status'        => 10
            ]);
            if($subloan_target){
                $old_target = $subloan_target->target_id;
                $subloan_history_target[] = $old_target;
            }
            else{break;}
        }
        $targets = $this->CI->target_model->get_many_by('id',$subloan_history_target);
        if($targets){
            $count = count($subloan_history_target);
            foreach ($targets as $key => $value){
                $targets_status = $value->target_no.' 逾期'.($count==1||$count==($key-1)&&$count!=1?$value->delay_days:get_range_days($value->loan_date,$value->handle_date)).'天';
                if($key==0){
                    $descri .= '首次申請：'.$targets_status;
                }
                else{
                    $count = $key==1?'首':$key;
                    $descri .= '
'.$count.'次轉換成功：'.$targets_status;
                }
            }
            return $descri;
        }
        return false;
    }
}

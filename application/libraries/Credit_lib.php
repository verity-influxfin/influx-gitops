<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Credit_lib{
	
	private $user_id = '';
	private $product_id = '';
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('loan/credit_model');
		$this->CI->load->model('user/user_meta_model');
		$this->CI->config->load('school_points',TRUE);
		$this->CI->config->load('credit',TRUE);
    }
	
	//信用評比
	public function approve_credit($user_id,$product_id){
		if($user_id && $product_id){
			$method		= 'approve_'.$product_id;
			if(method_exists($this, $method)){
				$rs = $this->$method($user_id,$product_id);
				return $rs;
			}
		}
		return false;
	}
	
	private function approve_1($user_id,$product_id){
		$rs 	= $this->CI->credit_model->order_by('level','desc')->get_by(array(
			'product_id' 	=> $product_id,
			'user_id'		=> $user_id,
			'status'		=> 1,
			'points <'		=> 0,
		));
		if($rs){
			$param		= array(
				'product_id' 	=> $product_id,
				'user_id'		=> $user_id,
				'points'		=> $rs->points,
				'level'			=> $rs->level,
				'amount'		=> $rs->amount,
			);
			return $this->CI->credit_model->insert($param);
		}
		
		$info 		= $this->CI->user_meta_model->get_many_by(array('user_id'=>$user_id));
		$user_info 	= $this->CI->user_model->get($user_id);
		$data 		= [];
		$total 		= 0;
		$param		= array('product_id'=>$product_id,'user_id'=>$user_id);
		foreach($info as $key => $value){
			$data[$value->meta_key] = $value->meta_value;
		}
		
		//學校
		if(isset($data['school_name']) && !empty($data['school_name'])){
			$total += $this->get_school_point($data['school_name'],$data['school_system'],$data['school_major']);
		}

		//財務證明
		if(isset($data['financial_status']) && !empty($data['financial_status'])){
			$total += 50;
			if(!empty($data['financial_creditcard']) || !empty($data['financial_passbook'])){
				$total += 50;
				if(!empty($data['financial_creditcard'])){
					$total += 50;
				}
				if(!empty($data['financial_passbook'])){
					$total += 50;
				}
			}
		}
		
		if(isset($data['social_status']) && !empty($data['social_status'])){
			$total += 50;
		}
		
		//SIP
		if(!empty($data['student_sip_account']) && !empty($data['student_sip_password'])){
			$total += 150;
		}
		//成績單
		if(isset($data['transcript_front']) && !empty($data['transcript_front'])){
			$total += 100;
		}
		//緊急聯絡人
		if(isset($data['emergency_relationship']) && $data['emergency_relationship']=='監護人'){
			$total = $total - 400;//mantis 0000003
		}
		
		$total = $user_info->sex=='M'?round($total*0.9):$total;
		$param['points'] 	= $total;
		$param['level'] 	= $this->get_credit_level($total,$product_id);
		$param['amount'] 	= $this->get_credit_amount($total,$product_id);
		$rs 		= $this->CI->credit_model->insert($param);
		return $rs;
	}
	
	public function get_school_point($school_name='',$school_system=0,$school_major=''){
		$point = 0;
		if(!empty($school_name)){
			$school_list = $this->CI->config->item('school_points');
			$school_info = [];
			foreach($school_list['school_points'] as $key => $value){
				if(trim($school_name)==$value['name']){
					$school_info = $value;
					break;
				}
			}

			if(!empty($school_info)){
				$point = $school_info['points'];
				if($school_system==1){
					$point += $school_info['national']==1?300:200;
				}else if($school_system==2){
					$point += 400;
				}
			}

			if(!empty($school_major)){
				$point += isset($school_list['school_major_point'][$school_major])?$school_list['school_major_point'][$school_major]:100;
			}else{
				$point += 100;
			}
		}
		return $point;
	}
	
	//取得信用評分
	public function get_credit($user_id,$product_id){
		if($user_id && $product_id){
			$param = array(
				'user_id'			=> $user_id,
				'product_id'		=> $product_id,
				'status'			=> 1,
				'expire_time >='	=> time(),
			);
			$data 	= array();
			$rs 	= $this->CI->credit_model->order_by('created_at','desc')->get_by($param);
			if($rs){
				$data = array(
					'level'		 => $rs->level,
					'points'	 => $rs->points,
					'amount'	 => $rs->amount,
					'created_at' => $rs->created_at,
				);
				return $data;
			}
		}
		return false;
	}
	
	public function get_credit_level($points=0,$product_id=0){
		if(intval($points)>0 && $product_id){
			$credit = $this->CI->config->item('credit');
			if(isset($credit['credit_level_'.$product_id])){
				foreach($credit['credit_level_'.$product_id] as $level => $value){
					if($points >= $value['start'] && $points <= $value['end']){
						return $level;
						break;
					}
				}
			}
			
		}
		return false;
	}
	
	public function get_credit_amount($points=0,$product_id=0){
		if(intval($points)>0 && $product_id){
			$credit = $this->CI->config->item('credit');
			if(isset($credit['credit_amount_'.$product_id])){
				foreach($credit['credit_amount_'.$product_id] as $key => $value){
					if($points>=$value['start'] && $points<=$value['end']){
						return $value['amount'];
						break;
					}
				}
			}
		}
		return false;
	}
	
	public function get_rate($level,$instalment,$product_id){
		$credit = $this->CI->config->item('credit');
		if(isset($credit['credit_level_'.$product_id][$level])){
			if(isset($credit['credit_level_'.$product_id][$level]['rate'][$instalment])){
				return $credit['credit_level_'.$product_id][$level]['rate'][$instalment];
			}
		}
		return false;
	}
	
	public function delay_credit($user_id,$delay_days=0){
		if($user_id && $delay_days > GRACE_PERIOD){
			$param = array(
				'user_id'			=> $user_id,
				'status'			=> 1,
			);
			
			$amount 		= 0;
			$points 		= -1;
			$level 			= 11;
			
			if($delay_days>30){
				$points 	= -501;
				$level 		= 12;
			}
			
			if($delay_days>60){
				$points 	= -1501;
				$level 		= 13;
			}
			
			$product_id = array();
			$rs 		= $this->CI->credit_model->order_by('created_at','desc')->get_many_by($param);
			if($rs){
				foreach($rs as $key => $value){
					if($value->level != $level){
						$this->CI->credit_model->update($value->id,array('status'=>0));
						$product_id[$value->product_id] = $value->product_id;
					}
				}
				
				if($product_id){
					foreach($product_id as $key => $value){
						$param = array(
							'user_id'		=> $user_id,
							'product_id'	=> $value,
							'points'		=> $points,
							'amount'		=> $amount,
							'level'			=> $level,
							
						);
						$rs = $this->CI->credit_model->insert($param);
					}
				}
				
				return $level;
			}
		}
		return false;
	}
}

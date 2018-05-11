<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Credit_lib{
	
	private $user_id = "";
	private $product_id = "";
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/credit_model');
		$this->CI->load->model('user/user_meta_model');
		$this->CI->load->model('user/user_model');
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
		$info 		= $this->CI->user_meta_model->get_many_by(array("user_id"=>$user_id));
		$user_info 	= $this->CI->user_model->get($user_id);
		$data 		= array();
		$total 		= 0;
		$param		= array("product_id"=>$product_id,"user_id"=>$user_id);
		foreach($info as $key => $value){
			$data[$value->meta_key] = $value->meta_value;
		}
		
		//學校
		if(isset($data['school_name']) && isset($data['school_system']) && !empty($data['school_name'])){
			$school_list = file_get_contents("https://s3-ap-northeast-1.amazonaws.com/influxp2p/school_point_1.json");
			$school_list = json_decode($school_list,true);
			$school_info = array();
			foreach($school_list as $key => $value){
				if(trim($data['school_name'])==$value['name']){
					$school_info = $value;
					break;
				}
			}
			if($data['school_system']==1){
				if($school_info['national']==1){
					$total += 400;
				}else{
					$total += 200;
				}
			}else if($data['school_system']==2){
				$total += 1200;
			}else{
				$total += $school_info['points'];
			}
		}
		
		//財務證明
		if(isset($data['financial_status']) && !empty($data['financial_status'])){
			$total += 100;
			if(!empty($data['financial_creditcard']) || !empty($data['financial_passbook'])){
				$total += 100;
			}
		}
		
		if(isset($data['social_status']) && !empty($data['social_status'])){
			$total += 300;
		}
		$total = $user_info->sex=="M"?$total:round($total*0.95);
		$param['points'] = $total;
		$credit_level 	= $this->CI->config->item('credit_level');
		foreach($credit_level as $level => $value){
			if($total>=$value['start'] && $total<=$value['end']){
				$param['level'] = $level;
				break;
			}
		}
		$credit_amount 	= $this->CI->config->item('credit_amount');
		foreach($credit_amount as $key => $value){
			if($total>=$value['start'] && $total<=$value['end']){
				$param['amount'] = $value['amount'];
				break;
			}
		}
		$rs = $this->CI->credit_model->insert($param);
		return $rs;
	}
	
	//取得信用評分
	public function get_credit($user_id,$product_id){
		if($user_id && $product_id){
			$param = array(
				"user_id"			=> $user_id,
				"product_id"		=> $product_id,
				"expire_time >="	=> time(),
			);
			$data 	= array();
			$rs 	= $this->CI->credit_model->order_by("created_at","desc")->get_by($param);
			if($rs){
				$data = array(
					"level"		=> $rs->level,
					"points"	=> $rs->points,
					"amount"	=> $rs->amount,
					"created_at"=> $rs->created_at,
				);
				return $data;
			}
		}
		return false;
	}
	
	public function get_rate($level,$instalment){
		$credit_level 	= $this->CI->config->item('credit_level');
		if(isset($credit_level[$level])){
			if(isset($credit_level[$level]['rate'][$instalment])){
				return $credit_level[$level]['rate'][$instalment];
			}
		}
		return false;
		
	}
}

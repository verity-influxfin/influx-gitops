<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Certification_lib{
	
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('platform/certification_model');
		$this->CI->load->model('user/user_certification_model');
		$this->CI->load->model('user/user_meta_model');
		$this->CI->load->model('user/user_model');
    }
	
	public function get_certification_info($user_id,$certification_id){
		if($user_id && $certification_id){
			$param = array(
				"user_id"			=> $user_id,
				"certification_id"	=> $certification_id
			);
			$certification = $this->CI->user_certification_model->order_by("created_at","desc")->get_by($param);
			
			if(!empty($certification)){
				$certification->content = json_decode($certification->content,true);
				return $certification;
			}
		}
		return false;
	}
	
	public function set_success($id){
		if($id){
			$info = $this->CI->user_certification_model->get($id);
			if($info && $info->status != 1){
				$info->content 	= json_decode($info->content,true);
				$certification 	= $this->CI->certification_model->get($info->certification_id);
				$method			= $certification->alias.'_success';
				if(method_exists($this, $method)){
					$rs = $this->$method($info);
					return $rs;
				}
			}
		}
		return false;
	}
	
	private function health_card_success($info){
		if($info){
			$content 	= $info->content;
			$data 		= array(
				"health_card_status"	=> 1,
				"health_card_front"		=> $content["front_image"]
			);
			
			$exist 		= $this->CI->user_meta_model->get_by(array("user_id"=>$info->user_id , "meta_key" => "health_card_status"));
			if($exist){
				foreach($data as $key => $value){
					$param = array(
						"user_id"		=> $info->user_id,
						"meta_key" 		=> $key,
					);
					$rs  = $this->CI->user_meta_model->update_by($param,array("meta_value"	=> $value));
				}
			}else{
				foreach($data as $key => $value){
					$param[] = array(
						"user_id"		=> $info->user_id,
						"meta_key" 		=> $key,
						"meta_value"	=> $value
					);
				}
				$rs  = $this->CI->user_meta_model->insert_many($param);
			}
			if($rs){
				$this->CI->user_certification_model->update_by(array("user_id"=> $info->user_id,"certification_id"=>$info->certification_id,"status"=>0),array("status"=>2));
				$this->CI->user_certification_model->update($info->id,array("status"=>1));
				return true;
			}
		}
		return false;
	}
	
	private function id_card_success($info){
		if($info){
			$content 	= $info->content;
			$data 		= array(
				"id_card_status"	=> 1,
				"id_card_front"		=> $content["front_image"],
				"id_card_back"		=> $content["back_image"],
				"id_card_person"	=> $content["person_image"],
			);
			
			$exist 		= $this->CI->user_meta_model->get_by(array("user_id"=>$info->user_id , "meta_key" => "id_card_status"));
			if($exist){
				foreach($data as $key => $value){
					$param = array(
						"user_id"		=> $info->user_id,
						"meta_key" 		=> $key,
					);
					$rs  = $this->CI->user_meta_model->update_by($param,array("meta_value"	=> $value));
				}
			}else{
				foreach($data as $key => $value){
					$param[] = array(
						"user_id"		=> $info->user_id,
						"meta_key" 		=> $key,
						"meta_value"	=> $value
					);
				}
				$rs  = $this->CI->user_meta_model->insert_many($param);
			}

			if($rs){
				$birthday 	= trim($content["birthday"]);
				$sex		= substr($content["id_number"],1,1)==1?"M":"F";
				if(strlen($birthday)==7 || strlen($birthday)==6){
					$birthday = $birthday + 19110000;
					$birthday = date("Y-m-d",strtotime($birthday));
					
				}
				
				$user_info = array(
					"name"			=> $content["name"],
					"sex"			=> $sex,
					"id_number"		=> $content["id_number"],
					"id_card_date"	=> $content["id_card_date"],
					"id_card_place"	=> $content["id_card_place"],
					"address"		=> $content["address"],
					"birthday"		=> $birthday,
				);
				
				$this->CI->user_model->update_many($info->user_id,$user_info);
				$this->CI->user_certification_model->update($info->id,array("status"=>1));
				$this->CI->user_certification_model->update_by(array("user_id"=> $info->user_id,"certification_id"=>$info->certification_id,"status"=>0),array("status"=>2));
				return true;
			}
		}
		return false;
	}
	
	private function student_success($info){
		if($info){
			$content 	= $info->content;
			$data 		= array(
				"student_status"		=> 1,
				"school_name"			=> $content["school"],
				"school_system"			=> $content["system"],
				"school_department"		=> $content["department"],
				"school_email"			=> $content["email"],
				"student_id"			=> $content["student_id"],
				"student_card_front"	=> $content["front_image"],
				"student_card_back"		=> $content["back_image"],
			);
			
			$exist 		= $this->CI->user_meta_model->get_by(array("user_id"=>$info->user_id , "meta_key" => "student_status"));
			if($exist){
				foreach($data as $key => $value){
					$param = array(
						"user_id"		=> $info->user_id,
						"meta_key" 		=> $key,
					);
					$rs  = $this->CI->user_meta_model->update_by($param,array("meta_value"	=> $value));
				}
			}else{
				foreach($data as $key => $value){
					$param[] = array(
						"user_id"		=> $info->user_id,
						"meta_key" 		=> $key,
						"meta_value"	=> $value
					);
				}
				$rs  = $this->CI->user_meta_model->insert_many($param);
			}
			if($rs){
				$this->CI->user_certification_model->update_by(array("user_id"=> $info->user_id,"certification_id"=>$info->certification_id,"status"=>0),array("status"=>2));
				$this->CI->user_certification_model->update($info->id,array("status"=>1));
				return true;
			}
		}
		return false;
	}
	
	private function debit_card_success($info){
		if($info){
			$this->CI->load->model('user/user_bankaccount_model');
			$content 	= $info->content;
			$exist 		= $this->CI->user_meta_model->get_by(array("user_id"=>$info->user_id , "meta_key" => "debit_card_status"));
			if(!$exist){
				$param = array(
					"user_id"		=> $info->user_id,
					"meta_key" 		=> 'debit_card_status',
					"meta_value"	=> 1
				);
				$rs  = $this->CI->user_meta_model->insert($param);
			}
		
			$user_info = array(
				"user_id"		=> $info->user_id,
				"bank_code"		=> $content["bank_code"],
				"branch_code"	=> $content["branch_code"],
				"bank_account"	=> $content["bank_account"],
				"front_image"	=> $content["front_image"],
				"back_image"	=> $content["back_image"],
			);
			
			$rs = $this->CI->user_bankaccount_model->insert($user_info);
			if($rs){
				$this->CI->user_certification_model->update($info->id,array("status"=>1));
				return true;
			}else{
				$this->CI->user_certification_model->update($info->id,array("status"=>2,"remark"=>"exist"));
			}
		}
		return false;
	}
	
	public function get_status($user_id){
		if($user_id){
			$certification_list = $this->CI->certification_model->get_many_by(array("status"=>1));
			foreach($certification_list as $key => $value){
				$param = array(
					"user_id"			=> $user_id,
					"certification_id"	=> $value->id
				);
				$user_certification = $this->CI->user_certification_model->order_by("created_at","desc")->get_by($param);
				if($user_certification){
					$value->user_status = $user_certification->status;
				}else{
					$value->user_status = null;
				}
				$certification_list[$key] = $value;
			}
			return $certification_list;
		}
		return false;
	}
}

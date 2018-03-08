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
				$birthday = trim($content["birthday"]);
				if(strlen($birthday)==7 || strlen($birthday)==6){
					$birthday = $birthday + 19110000;
					$birthday = date("Y-m-d",strtotime($birthday));
					
				}
				
				$user_info = array(
					"name"			=> $content["name"],
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
				"student_status"			=> 1,
				"school_name"			=> $content["school"],
				"school_department"		=> $content["department"],
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
	
	public function get_status($user_id){
		if($user_id){
			$certification_list = $this->CI->certification_model->get_many_by(array("status"=>1));
			foreach($certification_list as $key => $value){
				$alias[] = $value->alias."_status";
			}
			$data  = $this->CI->user_meta_model->get_many_by(array("user_id"=>$user_id,"meta_key"=>$alias));
			foreach($certification_list as $key => $value){
				$alias  = $value->alias."_status";
				$status = 0;
				if(!empty($data)){
					foreach($data as $k => $v){
						if($alias == $v->meta_key && $v->meta_value){
							$status = 1;
						}
					}
				}
				$value->user_status = $status;
				$certification_list[$key] = $value;
			}
			return $certification_list;
		}
		return false;
	}
}

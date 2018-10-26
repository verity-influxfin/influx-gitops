<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Certification_lib{
	
	public $certification;
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('user/user_certification_model');
		$this->CI->load->model('user/user_meta_model');
		$this->CI->load->library('Notification_lib');
		$this->certification 	= $this->CI->config->item('certifications');
    }
	
	public function get_certification_info($user_id,$certification_id,$investor=0){
		if($user_id && $certification_id){
			$param = array(
				"user_id"			=> $user_id,
				"certification_id"	=> $certification_id,
				"investor"			=> $investor,
				"status !="			=> 2,// 8/10 by toy
				"expire_time >="	=> time(),
			);
			$certification = $this->CI->user_certification_model->order_by("created_at","desc")->get_by($param);
			if(!empty($certification)){
				$certification->content = json_decode($certification->content,true);
				return $certification;
			}
		}
		return false;
	}
	
	public function get_last_certification_info($user_id,$certification_id,$investor=0){
		if($user_id && $certification_id){
			$param = array(
				"user_id"			=> $user_id,
				"certification_id"	=> $certification_id,
				"investor"			=> $investor,
				"expire_time >="	=> time(),
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
				$certification 	= $this->certification[$info->certification_id];
				$method			= $certification["alias"].'_success';
				if(method_exists($this, $method)){
					$rs = $this->$method($info);
					if($rs){
						$this->CI->notification_lib->certification($info->user_id,$info->investor,$certification['name'],1);
					}
					return $rs;
				}
			}
		}
		return false;
	}
	
	public function set_failed($id){
		if($id){
			$info = $this->CI->user_certification_model->get($id);
			if($info && $info->status != 2){
				$info->content 	= json_decode($info->content,true);
				$certification 	= $this->certification[$info->certification_id];
				$rs = $this->CI->user_certification_model->update($id,array("status"=>2));
				if($rs){
					$this->CI->notification_lib->certification($info->user_id,$info->investor,$certification['name'],2);
				}
				return $rs;
			}
		}
		return false;
	}
	
	public function idcard_verify($id){
		if($id){
			$this->CI->load->library('Faceplusplus_lib');
			$info = $this->CI->user_certification_model->get($id);
			if($info && $info->status ==0 ){
				$info->content 	= json_decode($info->content,true);
				$content		= $info->content;
				$ocr 			= array();
				$person_token 	= $this->CI->faceplusplus_lib->get_face_token($content['person_image']);
				$front_token 	= $this->CI->faceplusplus_lib->get_face_token($content['front_image']);
				$person_count 	= $person_token&&is_array($person_token)?count($person_token):0;
				$front_count 	= $front_token&&is_array($front_token)?count($front_token):0;
				$answer			= array();
				if($person_count==2 && $front_count==1){
					foreach($person_token as $token){
						$answer[] = $this->CI->faceplusplus_lib->token_compare($token,$front_token[0]);
					}
					if(count($answer)==2){
						if($answer[0]>$answer[1]){
							$tmp 		= $answer[0];
							$answer[0] 	= $answer[1];
							$answer[1] 	= $tmp;
						}
						if($answer[0]>=60 && $answer[1]>=90){
							$error = array("error"=>"","OCR"=>$ocr,"face"=>$answer,"face_count"=>array("person_count"=>$person_count,"front_count"=>$front_count));
							$this->CI->user_certification_model->update($id,array("remark"=>json_encode($error)));
						}else{
							$error = array("error"=>"人臉比對分數不足","OCR"=>$ocr,"face"=>$answer,"face_count"=>array("person_count"=>$person_count,"front_count"=>$front_count));
							$this->CI->user_certification_model->update($id,array("remark"=>json_encode($error)));
						}
					}
				}else{
					if($person_count==1 && $front_count==1){
						$answer[] = $this->CI->faceplusplus_lib->token_compare($person_token[0],$front_token[0]);
					}
					$error = array("error"=>"人臉數量錯誤","OCR"=>$ocr,"face"=>$answer,"face_count"=>array("person_count"=>$person_count,"front_count"=>$front_count));
					$this->CI->user_certification_model->update($id,array("remark"=>json_encode($error)));
				}
			}
		}
		return false;
	}
	
	private function id_card_success($info){
		if($info){
			$content 	= $info->content;
			//檢查身分證字號
			$id_number_used = $this->CI->user_model->get_by(array( "id_number" => $content['id_number'] ));
			if($id_number_used && $id_number_used->id != $info->user_id){
				return false;
			}
			
			$data 		= array(
				"id_card_status"	=> 1,
				"id_card_front"		=> $content["front_image"],
				"id_card_back"		=> $content["back_image"],
				"id_card_person"	=> $content["person_image"],
				"health_card_front"	=> $content["healthcard_image"],
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
				if($exist){
					$user_info = array(
						"name"				=> $content["name"],
						"id_card_date"		=> $content["id_card_date"],
						"id_card_place"		=> $content["id_card_place"],
						"address"			=> $content["address"],
					);
				}else{
					$birthday 	= trim($content["birthday"]);
					$sex		= substr($content["id_number"],1,1)==1?"M":"F";
					if(strlen($birthday)==7 || strlen($birthday)==6){
						$birthday = $birthday + 19110000;
						$birthday = date("Y-m-d",strtotime($birthday));
						
					}
					$user_info = array(
						"name"				=> $content["name"],
						"sex"				=> $sex,
						"id_number"			=> $content["id_number"],
						"id_card_date"		=> $content["id_card_date"],
						"id_card_place"		=> $content["id_card_place"],
						"address"			=> $content["address"],
						"birthday"			=> $birthday,
					);
					
					$virtual_data[] = array(
						"investor"			=> 1,
						"user_id"			=> $info->user_id,				
						"virtual_account"	=> CATHAY_VIRTUAL_CODE.INVESTOR_VIRTUAL_CODE.substr($content["id_number"],1,9),
					);
					
					$virtual_data[] = array(
						"investor"			=> 0,
						"user_id"			=> $info->user_id,				
						"virtual_account"	=> CATHAY_VIRTUAL_CODE.BORROWER_VIRTUAL_CODE.substr($content["id_number"],1,9),
					);
					$this->CI->load->model('user/virtual_account_model');
					$this->CI->virtual_account_model->insert_many($virtual_data);
				}

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
				"school_major"			=> $content["major"],
				"school_email"			=> $content["email"],
				"school_grade"			=> $content["grade"],
				"student_id"			=> $content["student_id"],
				"student_card_front"	=> $content["front_image"],
				"student_card_back"		=> $content["back_image"],
				"student_sip_account"	=> $content["sip_account"],
				"student_sip_password"	=> $content["sip_password"],
				"transcript_front"		=> $content["transcript_image"],
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
			$content 	= $info->content;
			$exist 		= $this->CI->user_meta_model->get_by(array("user_id"=>$info->user_id , "meta_key" => "debit_card_status"));
			if($exist){
				$param = array(
					"user_id"		=> $info->user_id,
					"meta_key" 		=> "debit_card_status",
				);
				$rs  = $this->CI->user_meta_model->update_by($param,array("meta_value"	=> 1));
			}else{
				$param = array(
					"user_id"		=> $info->user_id,
					"meta_key" 		=> 'debit_card_status',
					"meta_value"	=> 1
				);
				$rs  = $this->CI->user_meta_model->insert($param);
			}

			if($rs){
				$this->CI->user_certification_model->update($info->id,array("status"=>1));
				return true;
			}
		}
		return false;
	}
	
	private function emergency_success($info){
		if($info){
			$content 	= $info->content;
			$data 		= array(
				"emergency_status"			=> 1,
				"emergency_name"			=> $content["name"],
				"emergency_phone"			=> $content["phone"],
				"emergency_relationship"	=> $content["relationship"],
			);
			
			$exist 		= $this->CI->user_meta_model->get_by(array("user_id"=>$info->user_id , "meta_key" => "emergency_status"));
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
	
	private function email_success($info){
		if($info){
			$content 	= $info->content;
			$data 		= array(
				"email_status"	=> 1,
			);
			
			$exist 		= $this->CI->user_meta_model->get_by(array("user_id"=>$info->user_id , "meta_key" => "email_status"));
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
				$this->CI->user_model->update($info->user_id,array("email"=> $content["email"]));
				$this->CI->user_certification_model->update($info->id,array("status"=>1));
				$this->CI->user_certification_model->update_by(array("user_id"=> $info->user_id,"certification_id"=>$info->certification_id,"status"=>0),array("status"=>2));
				return true;
			}
		}
		return false;
	}
	
	private function financial_success($info){
		if($info){
			$content 	= $info->content;
			$data 		= array(
				"financial_status"		=> 1,
				"financial_income"		=> $content["parttime"]+$content["allowance"]+$content["scholarship"]+$content["other_income"],
				"financial_expense"		=> $content["restaurant"]+$content["transportation"]+$content["entertainment"]+$content["other_expense"],
				"financial_creditcard"	=> $content["creditcard_image"],
				"financial_passbook"	=> $content["passbook_image"],
			);

			$exist 		= $this->CI->user_meta_model->get_by(array("user_id"=>$info->user_id , "meta_key" => "financial_status"));
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
	
	private function social_success($info){
		if($info){
			$content 	= $info->content;
			$data 		= array(
				"social_status"		=> 1,
			);

			$exist 		= $this->CI->user_meta_model->get_by(array("user_id"=>$info->user_id , "meta_key" => "social_status"));
			if($exist){
				foreach($data as $key => $value){
					$param = array(
						"user_id"		=> $info->user_id,
						"meta_key" 		=> $key,
					);
					$rs  = $this->CI->user_meta_model->update_by($param,array("meta_value"	=> $value));
				}
			}else{
				if($content["type"]=="facebook"){
					$this->CI->load->library('facebook_lib'); 
					$meta  = $this->CI->facebook_lib->get_user_meta($info->user_id);
					if(!$meta){
						$debug_token = $this->CI->facebook_lib->debug_token($content["access_token"]);
						if($debug_token){
							$facebook_info 		= $this->CI->facebook_lib->get_info($content["access_token"]);
							if($facebook_info){
								$user_id 		= $this->CI->facebook_lib->login($facebook_info);
								if(!$user_id){
									$this->CI->facebook_lib->bind_user($info->user_id,$facebook_info);
									$this->CI->user_model->update($info->user_id,array("nickname"=>$facebook_info['name'],"picture"=>$facebook_info['picture']));
								}
							}
						}
					}
				}else if($content["type"]=="instagram"){
					$this->CI->load->library('instagram_lib'); 
					$meta  = $this->CI->instagram_lib->get_user_meta($info->user_id);
					if(!$meta){
						$ig_info 		= $this->CI->instagram_lib->get_info($content["access_token"]);
						if($ig_info){
							$user_id 	= $this->CI->instagram_lib->login($ig_info);
							if(!$user_id){
								$this->CI->instagram_lib->bind_user($info->user_id,$ig_info);
								$this->CI->user_model->update($info->user_id,array("nickname"=>$ig_info['name'],"picture"=>$ig_info['picture']));
							}
						}
					}
				}
			
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
	
	public function get_status($user_id,$investor=0){
		if($user_id){
			$certification = array();
			if($investor){
				foreach($this->certification as $key => $value){
					if(in_array($value["alias"],array("id_card","debit_card","email","emergency"))){
						$certification[$key] = $value;
					}
				}
			}else{
				$certification = $this->certification;
			}

			$certification_list = array();
			foreach($certification as $key => $value){
				$user_certification = $this->get_certification_info($user_id,$key,$investor);
				if($user_certification){
					$value["user_status"] 		= $user_certification->status;
					$value["certification_id"] 	= $user_certification->id;
				}else{
					$value["user_status"] 		= null;
					$value["certification_id"] 	= null;
				}
				
				$certification_list[$key] = $value;
			}
			return $certification_list;
		}
		return false;
	}
	
	public function get_last_status($user_id,$investor=0){
		if($user_id){
			$certification = array();
			if($investor){
				foreach($this->certification as $key => $value){
					if(in_array($value["alias"],array("id_card","debit_card","email","emergency"))){
						$certification[$key] = $value;
					}
				}
			}else{
				$certification = $this->certification;
			}

			$certification_list = array();
			foreach($certification as $key => $value){
				$user_certification = $this->get_last_certification_info($user_id,$key,$investor);
				if($user_certification){
					$value["user_status"] 		= $user_certification->status;
					$value["certification_id"] 	= $user_certification->id;
				}else{
					$value["user_status"] 		= null;
					$value["certification_id"] 	= null;
				}
				
				$certification_list[$key] = $value;
			}
			return $certification_list;
		}
		return false;
	}
		
	public function script_check_certification(){
		$script  		= 8;
		$count 			= 0;
		$date			= get_entering_date();
		$ids			= array();
		$certification	= array();
		foreach($this->certification as $key => $value){
			if(in_array($value["alias"],array("email","student","emergency"))){
				$certification[$value["alias"]] = $key;
			}
		}

		$user_certifications 	= $this->CI->user_certification_model->get_many_by(array(
			"status"			=> 0,
			"certification_id"	=> array_values($certification),
		));
		if($user_certifications){
			foreach($user_certifications as $key => $value){
				if(in_array($value->certification_id,array($certification["email"],$certification["student"]))){
					if(time()> ($value->created_at + 3600)){
						$this->set_failed($value->id);
						$count++; 
					}
				}
			}
		}
		return $count;
	}
}

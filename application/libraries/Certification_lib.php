<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Certification_lib{
	
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('platform/certification_model');
		$this->CI->load->model('user/user_certification_model');
		$this->CI->load->model('user/virtual_account_model');
		$this->CI->load->model('user/user_meta_model');
		$this->CI->load->model('user/user_model');
		$this->CI->load->library('Notification_lib');
    }
	
	public function get_certification_info($user_id,$certification_id,$investor=0){
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
				$certification 	= $this->CI->certification_model->get($info->certification_id);
				$method			= $certification->alias.'_success';
				if(method_exists($this, $method)){
					$rs = $this->$method($info);
					if($rs){
						$this->CI->notification_lib->certification($info->user_id,$info->investor,$certification->name,1);
					}else{
						$this->CI->notification_lib->certification($info->user_id,$info->investor,$certification->name,2);
					}
					
					return $rs;
				}
			}
		}
		return false;
	}
	
	public function idcard_verify($id){
		if($id){
			//$this->CI->load->library('Ocr_lib');
			$this->CI->load->library('Faceplusplus_lib');
			$info = $this->CI->user_certification_model->get($id);
			if($info && $info->status ==0 ){
				$info->content 		= json_decode($info->content,true);
				$content			= $info->content;
				$ocr 				= array();
				//$ocr = $this->CI->ocr_lib->identify($content['front_image'],1031);
				//if($ocr && $content['name']==$ocr['name'] && $content['id_number']==$ocr['id_number']){
				
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
								$error = array("error"=>"","OCR"=>$ocr,"face"=>$answer);
								$this->CI->user_certification_model->update($id,array("remark"=>json_encode($error)));
								$this->set_success($id);
							}else{
								$error = array("error"=>"face point","OCR"=>$ocr,"face"=>$answer);
								$this->CI->user_certification_model->update($id,array("remark"=>json_encode($error),"status"=>3));
							}
						}
					}else{
						$error = array("error"=>"face count","OCR"=>$ocr);
						$this->CI->user_certification_model->update($id,array("remark"=>json_encode($error),"status"=>3));
					}
				/*}else{
					$error = array("error"=>"OCR","OCR"=>$ocr);
					$this->CI->user_certification_model->update($id,array("remark"=>json_encode($error),"status"=>2));
				}*/
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
			if($investor){
				$where = array("status"=>1,"alias"=>array("id_card","debit_card","email","emergency"));
			}else{
				$where = array("status"=>1);
			}

			$certification_list = $this->CI->certification_model->get_many_by($where);
			foreach($certification_list as $key => $value){
				
				$user_certification = $this->get_certification_info($user_id,$value->id,$investor);
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

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
		$this->certification = $this->CI->config->item('certifications');
    }
	
	public function get_certification_info($user_id,$certification_id,$investor=0){
		if($user_id && $certification_id){
			$param = array(
				'user_id'			=> $user_id,
				'certification_id'	=> $certification_id,
				'investor'			=> $investor,
				'status !='			=> 2,
				'expire_time >='	=> time(),
			);
			$certification = $this->CI->user_certification_model->order_by('created_at','desc')->get_by($param);
			if(!empty($certification)){
				$certification->id 					= intval($certification->id);
				$certification->user_id 			= intval($certification->user_id);
				$certification->investor 			= intval($certification->investor);
				$certification->status 				= intval($certification->status);
				$certification->certification_id 	= intval($certification->certification_id);
				$certification->created_at 			= intval($certification->created_at);
				$certification->updated_ip 			= intval($certification->updated_ip);
				$certification->content = json_decode($certification->content,true);
				return $certification;
			}
		}
		return false;
	}
	
	public function get_last_certification_info($user_id,$certification_id,$investor=0){
		if($user_id && $certification_id){
			$param = array(
				'user_id'			=> $user_id,
				'certification_id'	=> $certification_id,
				'investor'			=> $investor,
				'expire_time >='	=> time(),
			);
			$certification = $this->CI->user_certification_model->order_by('created_at','desc')->get_by($param);
			if(!empty($certification)){
				$certification->id 					= intval($certification->id);
				$certification->user_id 			= intval($certification->user_id);
				$certification->investor 			= intval($certification->investor);
				$certification->status 				= intval($certification->status);
				$certification->certification_id 	= intval($certification->certification_id);
				$certification->created_at 			= intval($certification->created_at);
				$certification->updated_ip 			= intval($certification->updated_ip);
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
				$method			= $certification['alias'].'_success';
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
	
	public function verify($info){
		if($info && $info->status != 1){
			$certification 	= $this->certification[$info->certification_id];
			$method			= $certification['alias'].'_verify';
			if(method_exists($this, $method)){
				$rs = $this->$method($info);
			}else{
				$rs = $this->CI->user_certification_model->update($info->id,array(
					'status'	=> 3,
				));
			}
			return $rs;
		}
		return false;
	}
	
	public function set_failed($id,$fail=''){
		if($id){
			$info = $this->CI->user_certification_model->get($id);
			if($info && $info->status != 2){
				$info->content 			= json_decode($info->content,true);
				$info->remark 			= json_decode($info->remark,true);
				$info->remark['fail'] 	= $fail;
				$certification 	= $this->certification[$info->certification_id];
				$rs = $this->CI->user_certification_model->update($id,array('status'=>2,'remark'=>json_encode($info->remark)));
				if($rs){
					$this->CI->notification_lib->certification($info->user_id,$info->investor,$certification['name'],2,$fail);
				}
				return $rs;
			}
		}
		return false;
	}
	
	public function idcard_verify($info = []){
		if($info && $info->status ==0 && $info->certification_id==1){
			$this->CI->load->library('Faceplusplus_lib');
			$this->CI->load->library('Ocr_lib');
			$content				 = json_decode($info->content,true);
			$person_token 			 = $this->CI->faceplusplus_lib->get_face_token($content['person_image'],$info->user_id);
			$front_token 			 = $this->CI->faceplusplus_lib->get_face_token($content['front_image'],$info->user_id);
			$ocr 					 = [];
			$ocr['front_image'] 	 = $this->CI->ocr_lib->identify($content['front_image']		,1031);
			$ocr['back_image'] 		 = $this->CI->ocr_lib->identify($content['back_image']		,1032);
			$ocr['healthcard_image'] = $this->CI->ocr_lib->identify($content['healthcard_image'],1030);
			$error 		= '';
			$check_list = ['name','id_number','id_card_date','birthday','address'];
			$check_name = ['姓名','身分證字號','發證日','生日','地址'];
			foreach($check_list as $k => $v){
				if(in_array($v,['birthday','id_card_date'])){
					$content[$v] = r_to_ad($content[$v]);
					if(isset($ocr['front_image'][$v])){
						$ocr['front_image'][$v] 	= r_to_ad($ocr['front_image'][$v]);
					}
					if(isset($ocr['healthcard_image'][$v])){
						$ocr['healthcard_image'][$v] = r_to_ad($ocr['healthcard_image'][$v]);
					}
				}
				if(isset($ocr['front_image'][$v]) && trim($content[$v]) != trim($ocr['front_image'][$v]) ){
					$error .= '身分證'.$check_name[$k].'錯誤<br>';
				}
				if(isset($ocr['back_image'][$v]) && trim($content[$v]) != trim($ocr['back_image'][$v]) ){
					$error .= '身分證'.$check_name[$k].'錯誤<br>';
				}
				if(isset($ocr['healthcard_image'][$v]) && trim($content[$v]) != trim($ocr['healthcard_image'][$v]) ){
					$error .= '健保卡'.$check_name[$k].'錯誤<br>';
				}
			}

			$person_count 	= $person_token&&is_array($person_token)?count($person_token):0;
			$front_count 	= $front_token&&is_array($front_token)?count($front_token):0;
			$answer			= array();
			$remark			= array(
				'error'			=> $error,
				'OCR'			=> $ocr,
				'face'			=> array(),
				'face_count'	=> array(
					'person_count'	=> $person_count,
					'front_count'	=> $front_count
				),
			);

			if($person_count > 0 && $front_count > 0 ){
				foreach($person_token as $token){
					$answer[] = $this->CI->faceplusplus_lib->token_compare($token,$front_token[0],$info->user_id);
				}
				sort($answer);
				$remark['face'] = $answer;
				if(count($answer)==2){
					if($answer[0]<60 || $answer[1]<90){
						$remark['error'] .= '人臉比對分數不足';
					}
				}else{
					$remark['error'] .= '人臉數量錯誤';
				}
			}else{
				$remark['error'] .= '人臉數量錯誤';
			}

			if($remark['error']==''){
				$this->CI->user_certification_model->update($info->id,array(
					'remark'	=> json_encode($remark),
					'content'	=> json_encode($content),
				));
				$this->set_success($info->id);
			}else{
				$this->CI->user_certification_model->update($info->id,array(
					'status'	=> 3,
					'remark'	=> json_encode($remark),
					'content'	=> json_encode($content),
				));
			}

			return true;
		}
		return false;
	}
	
	public function emergency_verify($info = array()){
		if($info && $info->status ==0 && $info->certification_id==5){
			$content	= json_decode($info->content,true);
			$name 		= $content['name'];
			$idcard		= $this->get_certification_info($info->user_id,1,0);
			if($idcard && $idcard->status==1){
				$status 		= 3;
				$id_card_remark = json_decode($idcard->remark,true);
				if($id_card_remark && isset($id_card_remark['OCR']['back_image'])){
					$father = $id_card_remark['OCR']['back_image']['father'];
					$mother = $id_card_remark['OCR']['back_image']['mother'];
					if(in_array($name,array($father,$mother))){
						$this->set_success($info->id);
						return true;
					}
				}

				$this->CI->user_certification_model->update($info->id,array(
					'status'	=> $status,
				));
				return true;
			}
		}
		return false;
	}
	
	/*public function face_rotate($url='',$user_id=0){
		$this->CI->load->library('faceplusplus_lib');
		$this->CI->load->library('s3_upload');
		$image 	= file_get_contents($url);
		if($image){
			for($i=1;$i<=3;$i++){
				$src  	= imagecreatefromstring($image);
				switch ($i) {
					case 1:
						$src = imagerotate($src, 90, 0);
						break;
					case 2:
						$src = imagerotate($src, -90, 0);
						break;
					case 3:
						$src = imagerotate($src, 180, 0);
						break;
				}
				$output_w = $src_w = imagesx($src);
				$output_h = $src_h = imagesy($src);
				if($src_w > $src_h && $src_w > IMAGE_MAX_WIDTH){
					$output_w = IMAGE_MAX_WIDTH;
					$output_h = intval($src_h / $src_w * IMAGE_MAX_WIDTH);
				}else if($src_h > $src_w && $src_h > IMAGE_MAX_WIDTH){
					$output_h = IMAGE_MAX_WIDTH;
					$output_w = intval($src_w / $src_h * IMAGE_MAX_WIDTH);
				}else if($src_h == $src_w && $src_h > IMAGE_MAX_WIDTH){
					$output_h = IMAGE_MAX_WIDTH;
					$output_w = IMAGE_MAX_WIDTH;
				}
				
				$output = imagecreatetruecolor($output_w, $output_h);
				imagecopyresampled($output, $src, 0, 0, 0, 0, $output_w, $output_h, $src_w, $src_h);
				
				ob_start();
				imagejpeg($output, NULL, 90);
				$image_data = ob_get_contents();
				ob_end_clean();
				$base64 = base64_encode($image_data);
				$count = $this->CI->faceplusplus_lib->get_face_token_by_base64($base64,1);
				if($count){
					$url = $this->CI->s3_upload->image_by_data($image_data,basename($url),$user_id,'id_card');
					return array('count' => $count,'url' => $url);
				}
			}
		}
		return false;
	}*/
	
	private function idcard_success($info){
		if($info){
			$content 	= $info->content;
			//檢查身分證字號
			$id_number_used = $this->CI->user_model->get_by(array( 'id_number' => $content['id_number'] ));
			if($id_number_used && $id_number_used->id != $info->user_id){
				return false;
			}
			
			$data 		= array(
				'id_card_status'	=> 1,
				'id_card_front'		=> $content['front_image'],
				'id_card_back'		=> $content['back_image'],
				'id_card_person'	=> $content['person_image'],
				'health_card_front'	=> $content['healthcard_image'],
			);
			
			$exist 		= $this->CI->user_meta_model->get_by(array('user_id'=>$info->user_id , 'meta_key' => 'id_card_status'));
			if($exist){
				foreach($data as $key => $value){
					$param = array(
						'user_id'		=> $info->user_id,
						'meta_key' 		=> $key,
					);
					$rs  = $this->CI->user_meta_model->update_by($param,array('meta_value'	=> $value));
				}
			}else{
				foreach($data as $key => $value){
					$param[] = array(
						'user_id'		=> $info->user_id,
						'meta_key' 		=> $key,
						'meta_value'	=> $value
					);
				}
				$rs  = $this->CI->user_meta_model->insert_many($param);
			}

			if($rs){
				if($exist){
					$user_info = array(
						'name'				=> $content['name'],
						'id_card_date'		=> $content['id_card_date'],
						'id_card_place'		=> $content['id_card_place'],
						'address'			=> $content['address'],
					);
				}else{
					$sex		= substr($content['id_number'],1,1)==1?'M':'F';
					$user_info = array(
						'name'				=> $content['name'],
						'sex'				=> $sex,
						'id_number'			=> $content['id_number'],
						'id_card_date'		=> $content['id_card_date'],
						'id_card_place'		=> $content['id_card_place'],
						'address'			=> $content['address'],
						'birthday'			=> $content['birthday'],
					);
					
					$virtual_data[] = array(
						'investor'			=> 1,
						'user_id'			=> $info->user_id,				
						'virtual_account'	=> CATHAY_VIRTUAL_CODE.INVESTOR_VIRTUAL_CODE.substr($content['id_number'],1,9),
					);
					
					$virtual_data[] = array(
						'investor'			=> 0,
						'user_id'			=> $info->user_id,				
						'virtual_account'	=> CATHAY_VIRTUAL_CODE.BORROWER_VIRTUAL_CODE.substr($content['id_number'],1,9),
					);
					$this->CI->load->model('user/virtual_account_model');
					$this->CI->virtual_account_model->insert_many($virtual_data);
				}

				$this->CI->user_model->update_many($info->user_id,$user_info);
				$this->CI->user_certification_model->update($info->id,array('status'=>1));
				$this->CI->user_certification_model->update_by(array('user_id'=> $info->user_id,'certification_id'=>$info->certification_id,'status'=>0),array('status'=>2));
				return true;
			}
		}
		return false;
	}
	
	private function student_success($info){
		if($info){
			$content 	= $info->content;
			$data 		= array(
				'student_status'		=> 1,
				'school_name'			=> $content['school'],
				'school_system'			=> $content['system'],
				'school_department'		=> $content['department'],
				'school_major'			=> $content['major'],
				'school_email'			=> $content['email'],
				'school_grade'			=> $content['grade'],
				'student_id'			=> $content['student_id'],
				'student_card_front'	=> $content['front_image'],
				'student_card_back'		=> $content['back_image'],
				'student_sip_account'	=> $content['sip_account'],
				'student_sip_password'	=> $content['sip_password'],
				'transcript_front'		=> $content['transcript_image'],
			);
			
			$exist 		= $this->CI->user_meta_model->get_by(array('user_id'=>$info->user_id , 'meta_key' => 'student_status'));
			if($exist){
				foreach($data as $key => $value){
					$param = array(
						'user_id'		=> $info->user_id,
						'meta_key' 		=> $key,
					);
					$rs  = $this->CI->user_meta_model->update_by($param,array('meta_value'	=> $value));
				}
			}else{
				foreach($data as $key => $value){
					$param[] = array(
						'user_id'		=> $info->user_id,
						'meta_key' 		=> $key,
						'meta_value'	=> $value
					);
				}
				$rs  = $this->CI->user_meta_model->insert_many($param);
			}
			if($rs){
				$this->CI->user_certification_model->update_by(array('user_id'=> $info->user_id,'certification_id'=>$info->certification_id,'status'=>0),array('status'=>2));
				$this->CI->user_certification_model->update($info->id,array('status'=>1));
				return true;
			}
		}
		return false;
	}
	
	private function debitcard_success($info){
		if($info){
			$content 	= $info->content;
			$exist 		= $this->CI->user_meta_model->get_by(array(
				'user_id'	=> $info->user_id ,
				'meta_key' 	=> 'debit_card_status'
			));
			if($exist){
				$param = array(
					'user_id'		=> $info->user_id,
					'meta_key' 		=> 'debit_card_status',
				);
				$rs  = $this->CI->user_meta_model->update_by($param,array('meta_value'	=> 1));
			}else{
				$param = array(
					'user_id'		=> $info->user_id,
					'meta_key' 		=> 'debit_card_status',
					'meta_value'	=> 1
				);
				$rs  = $this->CI->user_meta_model->insert($param);
			}

			if($rs){
				$this->CI->user_certification_model->update($info->id,array('status'=>1));
				return true;
			}
		}
		return false;
	}
	
	private function emergency_success($info){
		if($info){
			$content 	= $info->content;
			$data 		= array(
				'emergency_status'			=> 1,
				'emergency_name'			=> $content['name'],
				'emergency_phone'			=> $content['phone'],
				'emergency_relationship'	=> $content['relationship'],
			);
			
			$exist 		= $this->CI->user_meta_model->get_by(array('user_id'=>$info->user_id , 'meta_key' => 'emergency_status'));
			if($exist){
				foreach($data as $key => $value){
					$param = array(
						'user_id'		=> $info->user_id,
						'meta_key' 		=> $key,
					);
					$rs  = $this->CI->user_meta_model->update_by($param,array('meta_value'	=> $value));
				}
			}else{
				foreach($data as $key => $value){
					$param[] = array(
						'user_id'		=> $info->user_id,
						'meta_key' 		=> $key,
						'meta_value'	=> $value
					);
				}
				$rs  = $this->CI->user_meta_model->insert_many($param);
			}
			if($rs){
				$this->CI->user_certification_model->update_by(array('user_id'=> $info->user_id,'certification_id'=>$info->certification_id,'status'=>0),array('status'=>2));
				$this->CI->user_certification_model->update($info->id,array('status'=>1));
				return true;
			}
		}
		return false;
	}
	
	private function email_success($info){
		if($info){
			$content 	= $info->content;
			$data 		= array(
				'email_status'	=> 1,
			);
			
			$exist 		= $this->CI->user_meta_model->get_by(array('user_id'=>$info->user_id , 'meta_key' => 'email_status'));
			if($exist){
				foreach($data as $key => $value){
					$param = array(
						'user_id'		=> $info->user_id,
						'meta_key' 		=> $key,
					);
					$rs  = $this->CI->user_meta_model->update_by($param,array('meta_value'	=> $value));
				}
			}else{
				foreach($data as $key => $value){
					$param[] = array(
						'user_id'		=> $info->user_id,
						'meta_key' 		=> $key,
						'meta_value'	=> $value
					);
				}
				$rs  = $this->CI->user_meta_model->insert_many($param); 
			}

			if($rs){
				$this->CI->user_model->update($info->user_id,array('email'=> $content['email']));
				$this->CI->user_certification_model->update($info->id,array('status'=>1));
				$this->CI->user_certification_model->update_by(array(
					'user_id'			=> $info->user_id,
					'certification_id'	=> $info->certification_id,
					'status'			=> 0
				),array('status'=>2));
				return true;
			}
		}
		return false;
	}
	
	private function financial_success($info){
		if($info){
			$content 	= $info->content;
			$data 		= array(
				'financial_status'		=> 1,
				'financial_income'		=> $content['parttime']+$content['allowance']+$content['scholarship']+$content['other_income'],
				'financial_expense'		=> $content['restaurant']+$content['transportation']+$content['entertainment']+$content['other_expense'],
				'financial_creditcard'	=> $content['creditcard_image'],
				'financial_passbook'	=> $content['passbook_image'],
			);

			$exist 		= $this->CI->user_meta_model->get_by(array('user_id'=>$info->user_id , 'meta_key' => 'financial_status'));
			if($exist){
				foreach($data as $key => $value){
					$param = array(
						'user_id'		=> $info->user_id,
						'meta_key' 		=> $key,
					);
					$rs  = $this->CI->user_meta_model->update_by($param,array('meta_value'	=> $value));
				}
			}else{
				foreach($data as $key => $value){
					$param[] = array(
						'user_id'		=> $info->user_id,
						'meta_key' 		=> $key,
						'meta_value'	=> $value
					);
				}
				$rs  = $this->CI->user_meta_model->insert_many($param);
			}
			if($rs){
				$this->CI->user_certification_model->update_by(array('user_id'=> $info->user_id,'certification_id'=>$info->certification_id,'status'=>0),array('status'=>2));
				$this->CI->user_certification_model->update($info->id,array('status'=>1));
				return true;
			}
		}
		return false;
	}
	
	private function social_success($info){
		if($info){
			$content 	= $info->content;
			$data 		= array(
				'social_status'		=> 1,
			);

			$exist 		= $this->CI->user_meta_model->get_by(array('user_id'=>$info->user_id , 'meta_key' => 'social_status'));
			if($exist){
				foreach($data as $key => $value){
					$param = array(
						'user_id'		=> $info->user_id,
						'meta_key' 		=> $key,
					);
					$rs  = $this->CI->user_meta_model->update_by($param,array('meta_value'	=> $value));
				}
			}else{
				foreach($data as $key => $value){
					$param[] = array(
						'user_id'		=> $info->user_id,
						'meta_key' 		=> $key,
						'meta_value'	=> $value
					);
				}
				$rs  = $this->CI->user_meta_model->insert_many($param);
			}
			if($rs){
				$this->CI->user_certification_model->update_by(array('user_id'=> $info->user_id,'certification_id'=>$info->certification_id,'status'=>0),array('status'=>2));
				$this->CI->user_certification_model->update($info->id,array('status'=>1));
				return true;
			}
		}
		return false;
	}
	
	private function diploma_success($info){
		if($info){
			$content 	= $info->content;
			$data 		= array(
				'diploma_status'	=> 1,
				'diploma_name'		=> $content['school'],
				'diploma_system'	=> $content['system'],
				'diploma_image'		=> $content['diploma_image'],
			);

			$exist 		= $this->CI->user_meta_model->get_by(array('user_id'=>$info->user_id , 'meta_key' => 'diploma_status'));
			if($exist){
				foreach($data as $key => $value){
					$param = array(
						'user_id'		=> $info->user_id,
						'meta_key' 		=> $key,
					);
					$rs  = $this->CI->user_meta_model->update_by($param,array('meta_value'	=> $value));
				}
			}else{
				foreach($data as $key => $value){
					$param[] = array(
						'user_id'		=> $info->user_id,
						'meta_key' 		=> $key,
						'meta_value'	=> $value
					);
				}
				$rs  = $this->CI->user_meta_model->insert_many($param);
			}
			if($rs){
				$this->CI->user_certification_model->update_by(array('user_id'=> $info->user_id,'certification_id'=>$info->certification_id,'status'=>0),array('status'=>2));
				$this->CI->user_certification_model->update($info->id,array('status'=>1));
				return true;
			}
		}
		return false;
	}
	
	private function investigation_success($info){
		if($info){
			$content 	= $info->content;
			$data 		= array(
				'investigation_status'	=> 1,
			);

			$exist 		= $this->CI->user_meta_model->get_by(array('user_id'=>$info->user_id , 'meta_key' => 'investigation_status'));
			if($exist){
				foreach($data as $key => $value){
					$param = array(
						'user_id'		=> $info->user_id,
						'meta_key' 		=> $key,
					);
					$rs  = $this->CI->user_meta_model->update_by($param,array('meta_value'	=> $value));
				}
			}else{
				foreach($data as $key => $value){
					$param[] = array(
						'user_id'		=> $info->user_id,
						'meta_key' 		=> $key,
						'meta_value'	=> $value
					);
				}
				$rs  = $this->CI->user_meta_model->insert_many($param);
			}
			if($rs){
				$this->CI->user_certification_model->update_by(array('user_id'=> $info->user_id,'certification_id'=>$info->certification_id,'status'=>0),array('status'=>2));
				$this->CI->user_certification_model->update($info->id,array('status'=>1));
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
					if(in_array($value['alias'],array('idcard','debitcard','email','emergency'))){
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
					$value['user_status'] 		= $user_certification->status;
					$value['certification_id'] 	= $user_certification->id;
				}else{
					$value['user_status'] 		= null;
					$value['certification_id'] 	= null;
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
					if(in_array($value['alias'],array('idcard','debitcard','email','emergency'))){
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
					$value['user_status'] 		= $user_certification->status;
					$value['certification_id'] 	= $user_certification->id;
				}else{
					$value['user_status'] 		= null;
					$value['certification_id'] 	= null;
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
		$user_certifications 	= $this->CI->user_certification_model->get_many_by(array(
			'status'				=> 0,
			'certification_id !='	=> 3,
		));
		if($user_certifications){
			foreach($user_certifications as $key => $value){
				switch($value->certification_id){
					case 2: 
					case 6:
						if(time() > ($value->created_at + 3600)){
							$this->set_failed($value->id,'未在有效時間內完成認證');
						}
						break;
					default:
						$this->verify($value);
						break;
				}
				$count++; 
			}
		}
		return $count;
	}
}

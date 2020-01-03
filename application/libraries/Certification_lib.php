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

	public function get_certification_info($user_id,$certification_id,$investor=0,$set_fail=false){
		if($user_id && $certification_id){
			$param = array(
				'user_id'			=> $user_id,
				'certification_id'	=> $certification_id,
				'investor'			=> $investor,
				'status !='			=> 2,
			);
			$certification = $this->CI->user_certification_model->order_by('created_at','desc')->get_by($param);
			if(!empty($certification)){
			    if($certification->expire_time <= time()&&$investor==0&&!in_array($certification_id,[IDCARD,DEBITCARD,EMERGENCY,EMAIL])){
                    return false;
                }
			    else{
                    $certification->id 					= intval($certification->id);
                    $certification->user_id 			= intval($certification->user_id);
                    $certification->investor 			= intval($certification->investor);
                    $certification->status 				= intval($certification->status);
                    $certification->certification_id 	= intval($certification->certification_id);
                    $certification->created_at 			= intval($certification->created_at);
                    $certification->updated_at 			= intval($certification->updated_at);
                    $certification->content = json_decode($certification->content,true);
                    return $certification;
                }
			}
		}
		return false;
	}

	public function get_last_certification_info($user_id,$certification_id,$investor=0){
		if($user_id && $certification_id){
			$certification = $this->CI->user_certification_model->order_by('created_at','desc')->get_by([
				'user_id'			=> $user_id,
				'certification_id'	=> $certification_id,
				'investor'			=> $investor,
			]);
			if(!empty($certification)){
				$certification->id 					= intval($certification->id);
				$certification->user_id 			= intval($certification->user_id);
				$certification->investor 			= intval($certification->investor);
				$certification->status 				= intval($certification->status);
				$certification->certification_id 	= intval($certification->certification_id);
				$certification->expire_time 		= intval($certification->expire_time);
				$certification->created_at 			= intval($certification->created_at);
				$certification->updated_at 			= intval($certification->updated_at);
				$certification->content = json_decode($certification->content,true);
				return $certification;
			}
		}
		return false;
	}

	public function set_success($id,$sys_check=false){
		if($id){
			$info = $this->CI->user_certification_model->get($id);
			if($info && $info->status != 1){
				$info->content 	= json_decode($info->content,true);
				$certification 	= $this->certification[$info->certification_id];
				$method			= $certification['alias'].'_success';
				if (in_array($id, [9, 10])) { 
					$this->CI->user_certification_model->update($info->id,array(
						'expire_time'	=> strtotime("+1 months", time()),
					));
				}
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

	public function set_failed($id,$fail='',$sys_check=false){
		if($id){
			$info = $this->CI->user_certification_model->get($id);
			if($info && $info->status != 2){
				$info->content 			= json_decode($info->content,true);
                $info->remark           = $info->remark!=''?json_decode($info->remark,true):[];
				$info->remark['fail'] 	= $fail;
				$certification 	= $this->certification[$info->certification_id];
				$rs = $this->CI->user_certification_model->update($id,array(
				    'status'    => 2,
                    'sys_check' => ($sys_check==true?1:0),
                    'remark'    => json_encode($info->remark)
                ));
				if($rs){
                    $this->CI->load->library('target_lib');
                    $targets = $this->CI->target_model->get_many_by(array(
                        'user_id'   => $info->user_id,
                        'status'	=> array(1,23)
                    ));
                    if($targets){
                        foreach($targets as $key => $value){
                            $this->CI->target_model->update_by(
                                ['id'  => $value->id],
                                ['status'	=> $value->status==1?0:22]
                            );
                        }
                    }
                    $this->CI->load->model('loan/credit_model');
                    $credit_list = $this->CI->credit_model->get_many_by(array(
                        'user_id'=>$info->user_id,
                        'status'=> 1
                    ));
                    foreach($credit_list as $ckey => $cvalue){
                        if(!in_array($cvalue->level,[11,12,13])){
                            $this->CI->credit_model->update_by(
                                ['id'    => $cvalue->id],
                                ['status'=> 0]
                            );
                        }
                    }

					$this->CI->notification_lib->certification($info->user_id,$info->investor,$certification['name'],2,$fail);
				}
				return $rs;
			}
		}
		return false;
	}

    public function idcard_verify($info = []){
        if($info && $info->status ==0 && $info->certification_id==1){
            $user_id        = $info->user_id;
            $cer_id         = $info->id;
            $msg            = '';
            $ocr            = [];
            $socr           = [];
            $answer         = [];
            $rawData        = [];
            $srawData       = false;
            $person_compare = [];
            $done           = false;

            $content = json_decode($info->content,true);
            $this->CI->load->library('Scan_lib');
            $this->CI->load->library('Compare_lib');
            $this->CI->load->library('Azure_lib');
            $this->CI->load->library('Faceplusplus_lib');

            $person_face       = $this->CI->azure_lib->detect($content['person_image'],$user_id,$cer_id);
            $front_face        = $this->CI->azure_lib->detect($content['front_image'],$user_id,$cer_id);
            //$healthcard_face   = $this->CI->azure_lib->detect($content['healthcard_image'],$user_id);

            $person_count = count($person_face);
            $front_count  = count($front_face);

            //嘗試轉向找人臉
            if($person_count==0){
                $rotate = $this->face_rotate($content['person_image'],$user_id);
                if($rotate){
                    $content['person_image'] 	= $rotate['url'];
                    $person_count				= $rotate['count'];
                }
            }
            if($front_count==0){
                $rotate = $this->face_rotate($content['front_image'],$user_id);
                if($rotate){
                    $content['front_image'] 	= $rotate['url'];
                    $front_count				= $rotate['count'];
                }
            }

            $remark = array(
                'error'			    => '',
                'OCR'			    => '',
                'face'			    => [],
                'face_flag'		    => [],
                'faceplus'		    => [],
                'face_count'	        => array(
                    'person_count'	=> $person_count,
                    'front_count'	=> $front_count
                ),
            );

            if($person_count>=2 && $person_count<=3 && $front_count==1){
                foreach($person_face as $token){
                    $person_compare[] = $this->CI->azure_lib->verify($token['faceId'],$front_face[0]['faceId'],$user_id,$cer_id);
                }
                $rawData['front_image']      = $this->CI->scan_lib->scanData($content['front_image'],$user_id,$cer_id);
                $rawData['back_image']       = $this->CI->scan_lib->detectText($content['back_image'],$user_id,$cer_id,'[a-zA-Z]');
                $rawData['healthcard_image'] = $this->CI->scan_lib->scanDataArr($content['healthcard_image'],$user_id,$cer_id);

                //身分證正面
                $ocr['name']            = $this->CI->compare_lib->contentCheck($content['name'],$rawData['front_image'],1);
                $ocr['id_number']       = $this->CI->compare_lib->contentCheck($content['id_number'],$rawData['front_image']);
                $ocr['birthday']        = $this->CI->compare_lib->dateContentCheck($content['birthday'],$rawData['front_image']);
                $ocr['id_card_date']    = $this->CI->compare_lib->dateContentCheck($content['id_card_date'],$rawData['front_image']);
                $ocr['id_card_place']   = $this->CI->compare_lib->dataExtraction('\(\p{Han}{2,3}\)\p{Han}{2}','',$rawData['front_image'],1);
                $check_name = ['姓名','身分證字號','發證日','生日'];
                $check_item = ['name','id_number','id_card_date','birthday'];
                foreach($check_item as $k => $v){
                    if($ocr[$v] == false){
                        if(!isset($srawData['front_image'])){
                            $srawData['front_image'] = $this->CI->scan_lib->azureScanData($content['front_image'],$user_id,$cer_id);

                            $socr['name']            = $this->CI->compare_lib->contentCheck($content['name'],$srawData['front_image'],1);
                            $socr['id_number']       = $this->CI->compare_lib->contentCheck($content['id_number'],$srawData['front_image']);
                            $socr['birthday']        = $this->CI->compare_lib->dateContentCheck($content['birthday'],$srawData['front_image']);
                            $socr['id_card_date']    = $this->CI->compare_lib->dateContentCheck($content['id_card_date'],$srawData['front_image']);
                            $socr['id_card_place']   = $this->CI->compare_lib->dataExtraction('\(\p{Han}{2,3}\)\p{Han}{2}','',$srawData['front_image'],1);
                        }
                        if($socr[$v]!=false){
                            $ocr[$v]=$socr[$v];
                        }else{
                            $msg.='身分證'.$check_name[$k].'無法辨識<br />';
                        }
                    }
                }

                //身分證背面
                $ocr['father']           = $this->CI->compare_lib->dataExtraction('父\\n{0,1}\p{Han}{1,6}\\n{0,1}役別|父\p{Han}{1,6}母','父|母|役別|\\n',$rawData['back_image'],1);
                mb_strlen($ocr['father'])==6?$ocr['father']=mb_substr($ocr['father'],0,3):null;
                $ocr['mother']           = $this->CI->compare_lib->dataExtraction('母\\n{0,1}\p{Han}{1,5}\\n{0,1}父|'.$ocr['father'].'\p{Han}{1,4}役別','父|母|役別|\\n|'.$ocr['father'],$rawData['back_image'],1);
                $ocr['spouse']           = $this->CI->compare_lib->dataExtraction('配偶\\n{0,1}\p{Han}{1,5}\\n{0,1}出生','配偶|出生|\\n',$rawData['back_image'],1);
                $ocr['military_service'] = $this->CI->compare_lib->dataExtraction('役別\\n{0,1}\p{Han}{1,5}\\n{0,1}配偶','役別|配偶|\\n',$rawData['back_image'],1);
                $ocr['born']             = $this->CI->compare_lib->dataExtraction('生地\\n{0,1}\s{0,2}\p{Han}{1,3}\\n{0,1}\p{Han}{1,3}\\n{0,1}','生地|住址|\\n',$rawData['back_image'],1);
                $ocr['gnumber']          = $this->CI->compare_lib->dataExtraction('\d{10}','',$rawData['back_image']);
                $ocr['film_number']      = $this->CI->compare_lib->dataExtraction('\d{6,10}','',preg_replace('/'.$ocr['gnumber'].'/','',$rawData['back_image']));
                $ocr['address']          = $this->CI->compare_lib->dataExtraction('址(.*?'.$ocr['gnumber'].')','址|\\n|'.$ocr['gnumber'],$rawData['back_image'],1);
                $check_item = ['father','mother','born','gnumber','address'];
                foreach($check_item as $k => $v){
                    if($ocr[$v] == false){
                        if(!isset($srawData['back_image'])){
                            //$srawData['back_image'] = $this->CI->scan_lib->azureScanData($content['back_image'],$user_id,$cer_id);
                            $srawData['back_image']  = $this->CI->scan_lib->scanDataArr($content['back_image'],$user_id,$cer_id);

                            $socr['father']           = $this->CI->compare_lib->dataExtraction('父\p{Han}{1,3}母|'.mb_substr($ocr['name'],0,1,"utf-8").'\p{Han}{1,3}','父|母',$srawData['back_image'],1);
                            $socr['mother']           = $this->CI->compare_lib->dataExtraction('母\p{Han}{1,5}\|{0,1}配偶','母|配偶|\|',$srawData['back_image'],1);
                            $socr['spouse']           = $this->CI->compare_lib->dataExtraction('配偶\p{Han}{1,5}\|{0,1}役別','配偶|役別|\|',$srawData['back_image'],1);
                            $socr['military_service'] = $this->CI->compare_lib->dataExtraction('役別\p{Han}{1,5}\|{0,1}出生','役別|出生|\|',$srawData['back_image'],1);
                            $socr['born']             = $this->CI->compare_lib->dataExtraction('生地\p{Han}{1,6}\|{0,1}','生地|\|',$srawData['back_image'],1);
                            $socr['gnumber']          = $this->CI->compare_lib->dataExtraction('\d{10}','',$srawData['back_image']);
                            $socr['address']          = $this->CI->compare_lib->dataExtraction('址(.*?\|)|'.$socr['born'].'\|(.*?\|)','住|址|\||'.$socr['born'],$srawData['back_image'],1);
                        }
                        if($socr[$v]!=false){
                            $ocr[$v]=$socr[$v];
                        }
                    }
                }


                //健保卡
                $ocr['healthcard_name']      = $this->CI->compare_lib->contentCheck($content['name'],$rawData['healthcard_image'],1);
                $ocr['healthcard_id_number'] = $this->CI->compare_lib->contentCheck($content['id_number'],$rawData['healthcard_image']);
                $ocr['healthcard_birthday']  = $this->CI->compare_lib->contentCheck($content['birthday'],$rawData['healthcard_image']);
                $ocr['healthcard_number']    = $this->CI->compare_lib->dataExtraction('\|\d{11,12}','\|',preg_replace('/'.$ocr['healthcard_id_number'].'/','',$rawData['healthcard_image']));
                $check_name = ['姓名','身分證字號','生日'];//,'發證區域'
                $check_item = ['healthcard_name','healthcard_id_number','healthcard_birthday'];
                foreach($check_item as $k => $v){
                    $ocr[$v] == false?$msg.='健保卡'.$check_name[$k].'無法辨識<br />':null;
                }

                $remark['face']       = [$person_compare[0]['confidence']*100,$person_compare[1]['confidence']*100];
                $remark['face_flag']  = [$person_compare[0]['isIdentical'],$person_compare[1]['isIdentical']];
                if($remark['face'][0]  < 65 || $remark['face'][1]  < 80){
                    $person_token = $this->CI->faceplusplus_lib->get_face_token($content['person_image'],$info->user_id,$cer_id);
                    $front_token  = $this->CI->faceplusplus_lib->get_face_token($content['front_image'],$info->user_id,$cer_id);
                    $fperson_count 	= $person_token&&is_array($person_token)?count($person_token):0;
                    $ffront_count 	= $front_token&&is_array($front_token)?count($front_token):0;
                    //嘗試轉向找人臉
                    if($fperson_count==0){
                        $rotate = $this->face_rotate($content['person_image'],$user_id,$cer_id,'faceplusplus');
                        if($rotate){
                            $content['person_image'] 	= $rotate['url'];
                            $fperson_count				= $rotate['count'];
                            $person_token               = $fperson_count;
                        }
                    }
                    if($ffront_count==0){
                        $rotate = $this->face_rotate($content['front_image'],$user_id,$cer_id,'faceplusplus');
                        if($rotate){
                            $content['front_image'] 	= $rotate['url'];
                            $ffront_count				= $rotate['count'];
                            $front_token                = $ffront_count;
                        }
                    }
                    if($fperson_count ==2 && $ffront_count == 1 ){
                        foreach($person_token as $token){
                            $answer[] = $this->CI->faceplusplus_lib->token_compare($token,$front_token[0],$info->user_id,$cer_id);
                        }
                        sort($answer);
                        $remark['faceplus'] = $answer;
                        if($answer[0]<65 || $answer[1]<80){
                            $msg .= 'Sys2人臉比對分數不足';
                        }
                    }
                    else{
                        $msg .= 'Sys2人臉數量不足';
                    }
                }
                $done = true;
            }
            else{
                $msg .= '系統判定人臉數量不正確，可能有陰影或其他因素';
            }

            $remark['error'] = $msg;
            $remark['OCR']   = $ocr;

            if($remark['error']==''&&$done){
                $this->CI->user_certification_model->update($info->id,array(
                    'remark'	    => json_encode($remark),
                    'content'	    => json_encode($content),
                    'sys_check'     => 1,
                ));
                $this->set_success($info->id);
            }else{
                $this->CI->user_certification_model->update($info->id,array(
                    'status'	    => 3,
                    'remark'	    => json_encode($remark),
                    'content'	    => json_encode($content),
                    'sys_check'     => 1,
                ));
            }
            return true;
        }
        return false;
    }

    //public function student_verify($info = array()){
    //    if($info && $info->status ==0 && $info->certification_id==2) {
    //        $status 	 = 3;
    //        $content     = json_decode($info->content);
    //        $user_id        = $info->user_id;
    //        $cer_id         = $info->id;
    //        $school       = $content->info->counts->school;
    //        $student_id   = $content->info->counts->student_id;

    //        $rawData['front_image']      = $this->CI->scan_lib->scanData($content['front_image'],$user_id,$cer_id);
    //        $rawData['back_image']       = $this->CI->scan_lib->detectText($content['back_image'],$user_id,$cer_id,'[a-zA-Z]');

    //        $this->CI->user_certification_model->update($info->id,array(
    //            'status'	=> $status,
    //            'sys_check'	=> 1,
    //        ));
    //        return true;
    //    }
    //    return false;
    //}

    public function social_verify($info = array()){
        if($info && $info->status ==0 && $info->certification_id==4) {
            $status 	 = 3;
            $content     = json_decode($info->content);
            $media       = $content->instagram->counts->media;
            $followed_by = $content->instagram->counts->followed_by;
            $is_fb_email = isset($content->facebook->email);
            $is_fb_name = isset($content->facebook->name);
            if($media >= 10 && $followed_by >= 10 && $is_fb_email && $is_fb_name){
                $status = 1;
                $this->set_success($info->id);
            }
            $this->CI->user_certification_model->update($info->id,array(
                'status'	=> $status,
                'sys_check'	=> 1,
            ));
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
				if($id_card_remark && isset($id_card_remark['OCR'])){
					$father = $id_card_remark['OCR']['father'];
					$mother = $id_card_remark['OCR']['mother'];
					if(in_array($name,array($father,$mother))){
                        $phone_used = $this->CI->user_model->get_by(array(
                            'id'    => $info->user_id,
                            'phone' => $content['phone'],
                        ));
					    if($phone_used){
                            $this->set_failed($info->id,'與註冊電話相同',true);
                        }
					    else{
                            $this->set_success($info->id);
                        }
					}
                    $this->CI->user_certification_model->update($info->id,array(
                        'status'	=> $status,
                        'sys_check'	=> 1,
                    ));
                    return true;
				}
			}
		}
		return false;
	}


	public function investigation_verify($info = array(), $url=null)
	{
		$user_certification	= $this->get_certification_info($info->user_id,1,$info->investor);
		if($user_certification==false || $user_certification->status!=1){
			return false;
		}
		$url = isset(json_decode($info->content)->pdf_file) ?
			json_decode($info->content)->pdf_file
			: $url;
		if ($info && $info->certification_id == 9 && !empty($url) && $info->status == 0) {
			$this->CI->load->library('Joint_credit_lib');
			$return_type=json_decode($info->content)->return_type;
			$result = [
				'status' => null,
				'messages' => []
			];
			$parser = new \Smalot\PdfParser\Parser();
			$pdf    = $parser->parseFile($url);
			$text = $pdf->getText();
			$res=$this->CI->joint_credit_lib->check_join_credits($info->user_id,$text, $result);
			switch ($res['status']) {
				case 'pending': //轉人工
					$status = 3;
					$this->CI->user_certification_model->update($info->id, array(
						'status' => $status, 
						'sys_check' => 1,
						'content' => json_encode(array('return_type'=>$return_type,'pdf_file' => $url, 'result' => $res))
					));
					break;
				case 'success':
					$status = 1;
					$get_time=$res['messages'][10]['message'];
					$get_months=$res['messages'][8]['message'][0];
					$get_credit_rate=$res['messages'][8]['message'][2];
					$times=preg_replace('/[^\d]/','',$get_time);
					$credit_rate=(preg_replace('/[^\d*\.\d]/','',$get_credit_rate));
					$months=preg_replace('/[^\d]/','',$get_months);
					$this->CI->user_certification_model->update($info->id, array(
						'sys_check' => 1,
						'content' => json_encode(array('return_type'=>$return_type,'pdf_file' => $url, 'result' => $res,'times'=>$times,'credit_rate'=>$credit_rate,'months'=>$months))
					));					
					$this->set_success($info->id,1);
					$this->CI->user_certification_model->update($info->id, array(
						'status' => $status
					));	
					break;
				case 'failure':
					$status = 2;
					$this->CI->user_certification_model->update($info->id, array(
						'status' => $status, 'sys_check' => 1,
						'content' => json_encode(array('return_type'=>$return_type,'pdf_file' => $url, 'result' => $res))
					));					
					$this->set_failed($info->id,'經本平台綜合評估暫時無法核准您的聯徵認證，感謝您的支持與愛護，希望下次還有機會為您服務。',true);
					break;
			}
			return true;
		}
		return false;
	}

	public function save_mail_url($info = array(),$url) {
		$content=json_decode($info->content,true);
		$content['pdf_file']=$url;
		$this->CI->user_certification_model->update($info->id, array(
			'content'=>json_encode($content) 
		));
		return true;
	}
	
	public function job_verify($info = array(),$url=null) {
		if ($info && $info->status == 0 && $info->certification_id == 10) {
			$status = 3;
			$content=json_decode($info->content,true);
			$content['pdf_file']=$url;
			$this->CI->user_certification_model->update($info->id, array(
				'status' => $status, 'sys_check' => 1,
				'content'=>json_encode($content) 
			));
			return true;	
		}
		return false;
	}
    public function face_rotate($url='',$user_id=0,$cer_id=0,$system='azure'){
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

				if($system=='azure'){
				    $this->CI->load->library('Azure_lib');
                    $count  = count($this->CI->azure_lib->detect($url,$user_id));
                }
				else{
                    $base64 = base64_encode($image_data);
                    $this->CI->load->library('faceplusplus_lib');
                    $count = $this->CI->faceplusplus_lib->get_face_token_by_base64($base64,$user_id,$cer_id);
                }
                if($count){
                    $this->CI->load->library('s3_upload');
                    $url = $this->CI->s3_upload->image_by_data($image_data, basename($url), $user_id, 'id_card', 'rotate');
                    return array('count' => $count,'url' => $url);
                }
            }
		}
		return false;
	}


    private function idcard_success($info){
		if($info){
			$content 	= $info->content;
			//檢查身分證字號
            $exist = $this->CI->user_model->get_by(array( 'id_number' => $content['id_number'] ));
			if($exist && $exist->id != $info->user_id){
				return false;
			}

			$data 		= array(
				'id_card_status'	=> 1,
				'id_card_front'		=> $content['front_image'],
				'id_card_back'		=> $content['back_image'],
				'id_card_person'	=> $content['person_image'],
				'health_card_front'	=> $content['healthcard_image'],
			);

            $rs = $this->user_meta_progress($data,$info);
			if($rs){
                $birthday 	= trim($content["birthday"]);
                if(strlen($birthday)==7 || strlen($birthday)==6){
                    $birthday = $birthday + 19110000;
                    $birthday = date("Y-m-d",strtotime($birthday));
                }
                $sex		= substr($content['id_number'],1,1)==1?'M':'F';
                $user_info = array(
                    'name'				=> $content['name'],
                    'sex'				=> $sex,
                    'id_number'			=> $content['id_number'],
                    'id_card_date'		=> $content['id_card_date'],
                    'id_card_place'		=> $content['id_card_place'],
                    'address'			=> $content['address'],
                    "birthday"			=> $birthday,
                );
				if($exist){
                    unset($user_info['sex']);
				}else{
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

                return $this->fail_other_cer($info);
			}
		}
		return false;
	}

	private function student_success($info){
		if($info){
			$content 	= $info->content;
			$data 		= array(
				'student_status'		 => 1,
				'school_name'			 => $content['school'],
				'school_system'			 => $content['system'],
				'school_department'		 => $content['department'],
				'school_major'			 => $content['major'],
				'school_email'			 => $content['email'],
				'school_grade'			 => $content['grade'],
				'student_id'			 => $content['student_id'],
				'student_card_front'	 => $content['front_image'],
				'student_card_back'		 => $content['back_image'],
				'student_sip_account'	 => $content['sip_account'],
                'student_sip_password'	 => $content['sip_password'],
                'student_license_level'	 => $content['license_level'],
                'student_game_work_level'=> $content['game_work_level'],
                'student_pro_level'      => $content['pro_level'],
            );
            isset($content['graduate_date'])?$data['graduate_date']=$content['graduate_date']:'';
            isset($content['programming_language'])?$data['student_programming_language']=$content['programming_language']:'';

            $rs = $this->user_meta_progress($data,$info);
            if($rs){
                return $this->fail_other_cer($info);
            }
		}
		return false;
	}

	private function debitcard_success($info){
		if($info){
            $data 		= array(
                'debit_card_status'			=> 1,
            );

            $rs = $this->user_meta_progress($data,$info);
			if($rs){
                return $this->fail_other_cer($info);
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

            $rs = $this->user_meta_progress($data,$info);
			if($rs){
                return $this->fail_other_cer($info);
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

            $rs = $this->user_meta_progress($data,$info);
			if($rs){
                $this->CI->load->model('user/judicial_person_model');
                $judicial_person = $this->CI->judicial_person_model->get_by([
                    'user_id'=> $info->user_id
                ]);
                if($judicial_person){
                    $this->CI->user_model->update($judicial_person->company_user_id,array('email'=> $content['email']));
                }

				$this->CI->user_model->update($info->user_id,array('email'=> $content['email']));
                return $this->fail_other_cer($info);
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
			);
            if(isset($content['creditcard_image'])){
                $data['financial_creditcard'] = $content['creditcard_image'];
            }
            if(isset($content['passbook_image'])){
                $data['financial_passbook'] = $content['passbook_image'];
            }
            $rs = $this->user_meta_progress($data,$info);
			if($rs){
                return $this->fail_other_cer($info);
			}
		}
		return false;
	}

	private function social_success($info){
		if($info){
			$content 	= $info->content;
			$data 		= array(
				'social_status'		=> 1,
				'fb_id'		=> isset($content['facebook']['id'])?$content['facebook']['id']:NULL,
				'fb_name'		=> isset($content['facebook']['name'])?$content['facebook']['name']:NULL,
				'fb_email'		=> isset($content['facebook']['email'])?$content['facebook']['email']:NULL,
				'fb_access_token'		=> isset($content['facebook']['access_token'])?$content['facebook']['access_token']:NULL,
				'ig_id'		=> isset($content['instagram']['id'])?$content['instagram']['id']:NULL,
				'ig_username'		=> isset($content['instagram']['username'])?$content['instagram']['username']:NULL,
				'ig_name'		=> isset($content['instagram']['name'])?$content['instagram']['name']:NULL,
				'ig_access_token'		=>  isset($content['instagram']['access_token'])?$content['instagram']['access_token']:NULL,
			);

            $rs = $this->user_meta_progress($data,$info);
			if($rs){
                return $this->fail_other_cer($info);
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
                'diploma_major'		=> $content['major'],
                'diploma_department'=> $content['department'],
                'diploma_system'	=> $content['system'],
				'diploma_image'		=> $content['diploma_image'],
			);

            $rs = $this->user_meta_progress($data,$info);
			if($rs){
                return $this->fail_other_cer($info);
			}
		}
		return false;
	}

	private function investigation_success($info){
		if($info){
			$content 	= $info->content;
			$data 		= [
				'investigation_status'		=> 1,
				'investigation_times'		=> $content['times'],
				'investigation_credit_rate'	=> $content['credit_rate'],
				'investigation_months'		=> $content['months']
			];

            $rs = $this->user_meta_progress($data,$info);
			if($rs){
                return $this->fail_other_cer($info);
			}
		}
		return false;
	}

	private function job_success($info){
		if($info){
			$content 	= $info->content;
			$data 		= [
				'job_status'			=> 1,
				'job_tax_id'			=> $content['tax_id'],
				'job_company'			=> $content['company'],
				'job_industry'			=> $content['industry'],
				'job_employee'			=> $content['employee'],
				'job_position'			=> $content['position'],
				'job_type'				=> $content['type'],
				'job_seniority'			=> $content['seniority'],
				'job_company_seniority'	=> $content['job_seniority'],
				'job_salary'			=> $content['salary'],
				'job_license'			=> $content['license_status'],
				'job_pro_level'			=> $content['pro_level'],
			];
            isset($content['programming_language'])?$data['job_programming_language']=$content['programming_language']:'';
            isset($content['job_title'])?$data['job_title']=$content['job_title']:'';

            $rs = $this->user_meta_progress($data,$info);
			if($rs){
                return $this->fail_other_cer($info);
			}
		}
		return false;
	}

    public function get_status($user_id,$investor=0,$company=0,$set_fail=false){
		if($user_id){
			$certification = array();
			if($company){
				foreach($this->certification as $key => $value){
					if($value['alias']=='debitcard'){
						$certification[$key] = $value;
					}
				}
			}else if($investor){
				foreach($this->certification as $key => $value){
					if(in_array($value['alias'],['idcard','debitcard','email','emergency'])){
						$certification[$key] = $value;
					}
				}
			}else{
				$certification = $this->certification;
			}

			$certification_list = [];
			foreach($certification as $key => $value){
				$user_certification = $this->get_certification_info($user_id,$key,$investor,$set_fail);
				if($user_certification){
					$value['user_status'] 		   = intval($user_certification->status);
					$value['certification_id'] 	   = intval($user_certification->id);
                    $value['updated_at'] 		   = intval($user_certification->updated_at);
                    $dipoma                        = isset($user_certification->content['diploma_date'])?$user_certification->content['diploma_date']:null;
                    $key==8?$value['diploma_date']=$dipoma:null;
				}else{
					$value['user_status'] 		 = null;
					$value['certification_id'] 	 = null;
					$value['updated_at'] 		 = null;
				}

				$certification_list[$key] = $value;
			}
			return $certification_list;
		}
		return false;
	}

    public function option_investigation($product_id,$value,$diploma){
        if($value['id']==9 && in_array($product_id,$value['optional'])){
            if(isset($diploma['diploma_date']) && is_numeric($diploma['diploma_date'])){
                return get_range_days(intval($diploma['diploma_date'])+19110000,date('Ymd',strtotime(get_entering_date())))>=DIPLOMA_RANGE_DAYS?false:true;
            }
        }
        return false;
    }

    public function get_last_status($user_id,$investor=0,$company=0){
		if($user_id){
			$certification = [];
			if($company){
				foreach($this->certification as $key => $value){
					if($value['alias']=='debitcard'){
						$certification[$key] = $value;
					}
				}
			}else if($investor){
				foreach($this->certification as $key => $value){
					if(in_array($value['alias'],['idcard','debitcard','email','emergency'])){
						$certification[$key] = $value;
					}
				}
			}else{
				$certification = $this->certification;
			}

			$certification_list = [];
			foreach($certification as $key => $value){
				$user_certification = $this->get_last_certification_info($user_id,$key,$investor);
				if($user_certification){
					$value['user_status'] 		= intval($user_certification->status);
                    $value['certification_id'] 	= intval($user_certification->id);
                    $value['updated_at'] 		= intval($user_certification->updated_at);
                    $value['expire_time'] 		= $user_certification->expire_time;
                    $value['sys_check'] 		= intval($user_certification->sys_check);
                }else{
					$value['user_status'] 		= null;
					$value['certification_id'] 	= null;
					$value['updated_at'] 		= null;
					$value['expire_time'] 		= 0;
					$value['sys_check'] 		= 0;
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

    //寫入或更新muser_meta
	private function user_meta_progress($data,$info){
        foreach($data as $key => $value) {
            $exist = $this->CI->user_meta_model->get_by(array('user_id' => $info->user_id, 'meta_key' => $key));
            if ($exist) {
                $param = array(
                    'user_id' => $info->user_id,
                    'meta_key' => $key,
                );
                $this->CI->user_meta_model->update_by($param, array('meta_value' => $value));
            }else{
                $param = array(
                    'user_id'		=> $info->user_id,
                    'meta_key' 		=> $key,
                    'meta_value'	=> $value
                );
                $this->CI->user_meta_model->insert($param);
            }
        }
        return true;
    }

    //失效其他認證
	private function fail_other_cer($info){
        $this->CI->user_certification_model->update(
            $info->id,
            ['status'=>1]
        );
        $rs = $this->CI->user_certification_model->update_by([
            'id !='             => $info->id,
            'user_id'			=> $info->user_id,
            'certification_id'	=> $info->certification_id,
            'status'			=> [0,1,2,3]
        ], ['status'=> 2]);
        return $rs;
    }

    public function expire_certification($user_id,$investor=0){
        if($user_id) {
            $certification = $this->CI->user_certification_model->order_by('created_at', 'desc')->get_many_by([
                'user_id' => $user_id,
                'investor' => $investor,
                'status !=' => 2,
            ]);
            if($certification) {
                foreach ($certification as $key => $value) {
                    if ($value->expire_time <= time() && $investor == 0 && !in_array($value->certification_id, [IDCARD, DEBITCARD, EMERGENCY, EMAIL])) {
                        $this->set_failed($value->id, '認證已逾期。', true);
                    }
                }
            }
        }
        return false;
    }
}

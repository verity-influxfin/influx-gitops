<?php

use Smalot\PdfParser\Parser;

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

	public function get_certification_info($user_id,$certification_id,$investor=0,$get_fail=false){
		if($user_id && $certification_id){
			$param = array(
				'user_id'			=> $user_id,
				'certification_id'	=> $certification_id,
				'investor'			=> $investor,
			);
            !$get_fail ? $param['status'] = [0,1,3,4] : '';
			$certification = $this->CI->user_certification_model->order_by('created_at','desc')->get_by($param);
			if(!empty($certification)){
			    if($certification->expire_time <= time()&&$investor==0&&!in_array($certification_id,[CERTIFICATION_IDCARD,CERTIFICATION_DEBITCARD,CERTIFICATION_EMERGENCY,CERTIFICATION_EMAIL])){
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

    public function set_success($id, $sys_check = false, $expire_timestamp = false)
    {
		if($id){
			$info = $this->CI->user_certification_model->get($id);
			if($info && $info->status != 1){
				$info->content 	= json_decode($info->content,true);
				$certification 	= $this->certification[$info->certification_id];
				$method			= $certification['alias'].'_success';
				$param = [
                    'sys_check' => ($sys_check==true?1:0),
                ];
				if ($expire_timestamp) {
				    $param['expire_time'] = $expire_timestamp;
				}
                $this->CI->user_certification_model->update($info->id,$param);
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

	public function set_failed($id,$fail='',$sys_check=false, $expire_timestamp = false){
		if($id){
			$info = $this->CI->user_certification_model->get($id);
			if($info && $info->status != 2){
				$info->content 			= json_decode($info->content,true);
                $info->remark           = $info->remark!=''?json_decode($info->remark,true):[];
				$info->remark['fail'] 	= $fail;
				$certification 	= $this->certification[$info->certification_id];
				$param = [
                    'status'    => 2,
                    'sys_check' => ($sys_check==true?1:0),
                    'remark'    => json_encode($info->remark)
                ];
                if ($expire_timestamp) {
                    $param['expire_time'] = $expire_timestamp;
                }
				$rs = $this->CI->user_certification_model->update($id,$param);
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
                'faceplus_data' => [],
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
                $ocr['mother']           = $this->CI->compare_lib->dataExtraction('母\\n{0,1}\p{Han}{1,5}\\n{0,1}父|'.$ocr['father'].'\p{Han}{1,4}役別|母\\n{0,1}\p{Han}{1,5}\\n{0,1}配偶','父|母|役別|配偶|\\n|'.$ocr['father'],$rawData['back_image'],1);
                $ocr['mother'] = $this->CI->compare_lib->repeatCheck($ocr['mother']);

                $ocr['spouse']           = $this->CI->compare_lib->dataExtraction('配偶\\n{0,1}\p{Han}{1,5}\\n{0,1}出生|配偶\\n{0,1}\p{Han}{1,5}\\n{0,1}役別','配偶|出生|役別|\\n|\\s',$rawData['back_image'],1);
                $ocr['military_service'] = $this->CI->compare_lib->dataExtraction('役別\\n{0,1}\p{Han}{1,5}\\n{0,1}配偶|役別\\n{0,1}\p{Han}{1,5}\\n{0,1}出生地','役別|配偶|出生地|\\n',$rawData['back_image'],1);
                $ocr['born']             = $this->CI->compare_lib->dataExtraction('生地\\n{0,1}\s{0,2}\p{Han}{1,3}\\n{0,1}\p{Han}{1,3}\\n{0,1}','生地|住址|\\n',$rawData['back_image'],1);
                $ocr['born'] = $this->CI->compare_lib->repeatCheck($ocr['born']);
                $ocr['gnumber']          = $this->CI->compare_lib->dataExtraction('\d{10}','',$rawData['back_image']);
                $ocr['film_number']      = $this->CI->compare_lib->dataExtraction('\d{6,10}','',preg_replace('/'.$ocr['gnumber'].'/','',$rawData['back_image']));
                $ocr['address']          = $this->CI->compare_lib->dataExtraction('('.$ocr['born'].')(.*?'.$ocr['gnumber'].')','住|址|生地|住址|\\n|'.$ocr['gnumber'].'|'.$ocr['born'],$rawData['back_image'],1);
                $check_item = ['father','mother','born','gnumber','address'];
                foreach($check_item as $k => $v){
                    if($ocr[$v] == false){
                        if(!isset($srawData['back_image'])){
                            //$srawData['back_image'] = $this->CI->scan_lib->azureScanData($content['back_image'],$user_id,$cer_id);
                            $srawData['back_image']  = $this->CI->scan_lib->scanDataArr($content['back_image'],$user_id,$cer_id);

                            $socr['father']           = $this->CI->compare_lib->dataExtraction('父\p{Han}{1,3}母|'.mb_substr($ocr['name'],0,1,"utf-8").'\p{Han}{1,3}','父|母',$srawData['back_image'],1);
                            if($socr['mother'] = ''){
                                $socr['mother'] = $this->CI->compare_lib->dataExtraction('母\p{Han}{1,5}\|{0,1}配偶','母|配偶|\|',$srawData['back_image'],1);
                            }
                            if($socr['spouse'] = ''){
                                $socr['spouse']           = $this->CI->compare_lib->dataExtraction('配偶\p{Han}{1,5}\|{0,1}役別','配偶|役別|\|',$srawData['back_image'],1);
                            }
                            if($socr['military_service'] = ''){
                                $socr['military_service'] = $this->CI->compare_lib->dataExtraction('役別\p{Han}{1,5}\|{0,1}出生','役別|出生|\|',$srawData['back_image'],1);
                            }
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
//                if($remark['face'][0]  < 65 || $remark['face'][1]  < 80){
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
                            $answer[] = $this->CI->faceplusplus_lib->token_compare($token[0],$front_token[0][0],$info->user_id,$cer_id);
                            $faceplus_data[] = [
                                'gender' => $token[1],
                                'age' => $token[2],
                            ];
                        }
                        sort($answer);
                        $remark['faceplus'] = $answer;
                        $remark['faceplus_data'] = $faceplus_data;
                        if($answer[0]<65 || $answer[1]<80){
                            $msg .= 'Face++人臉比對分數不足';
                        }
                    }
                    else{
                        $msg .= 'Face++人臉數量不足';
                    }
//                }
                $done = true;
            }
            else{
                $msg .= '系統判定人臉數量不正確，可能有陰影或其他因素';
            }

            $this->CI->load->library('Papago_lib');
            $face8_person_face = $this->CI->papago_lib->detect($content['person_image'], $user_id, $cer_id);
            $face8_front_face = $this->CI->papago_lib->detect($content['front_image'], $user_id, $cer_id);
            $face8_person_count = count($face8_person_face['faces']);
            $face8_front_count = count($face8_front_face['faces']);
            foreach ($face8_person_face['faces'] as $tkey =>$token) {
                if (isset($token['face_token']) && count($face8_front_face['faces']) > 0) {
                    $face8_compare_res = $this->CI->papago_lib->compare([$token['face_token'], $face8_front_face['faces'][0]['face_token']], $user_id, $cer_id);
                    $compares[] = $face8_compare_res['confidence'];
                }
            }
            $face8_face1 = isset($compares[0]) ? $compares[0] : 'n/a';
            $face8_face2 = isset($compares[1]) ? $compares[1] : 'n/a';
            $remark['face8'] = [
                'count' => [$face8_person_count, $face8_front_count],
                'score' => [$face8_face1, $face8_face2],
                'liveness' => [
                    [
                        ($face8_person_count > 0 ? $face8_person_face['faces'][0]['attributes']['liveness']['value'] : 'n/a'),
                        ($face8_person_count > 1 ? $face8_person_face['faces'][1]['attributes']['liveness']['value'] : 'n/a')
                    ],
                    ($face8_front_count > 0 ? $face8_front_face['faces'][0]['attributes']['liveness']['value'] : 'n/a')
                ],
            ];

			// 戶役政 api
			// if(isset($content['id_number']) && isset($ocr['id_number']) && isset($content['name']) && isset($ocr['name']) && isset($content['birthday']) && isset($ocr['birthday'])){
			// 	if($content['id_number'] == $ocr['id_number'] && $content['name'] == $ocr['name'] && $content['birthday'] == $ocr['birthday']){
			// 		$this->CI->load->library('id_card_lib');
			// 		$requestPersonId = isset($content['id_number']) ? $content['id_number'] : '';
			// 		preg_match('/(初|補|換)發$/',$content['id_card_place'],$requestApplyCode);
			// 		$requestApplyCode = isset($requestApplyCode[0]) ? $requestApplyCode[0] : '';
			// 		$reqestApplyYyymmdd = $content['id_card_date'];
			// 		preg_match('/(*UTF8)((\W{1}|新北)市|\W{1}縣)|(連江|金門)/',$content['id_card_place'],$requestIssueSiteId);
			// 		$requestIssueSiteId = isset($requestIssueSiteId[0]) ? $requestIssueSiteId[0] : '';
			// 		$result = $this->CI->id_card_lib->send_request($requestPersonId, $requestApplyCode, $reqestApplyYyymmdd, $requestIssueSiteId);
			// 		if($result){
			// 			// $result = json_decode($result,true);
			// 			if($result['status'] != '200'){
			// 				$done = false;
			// 			}
			// 			if($result['response']['response']['rowData']['responseData']['checkIdCardApply'] == 1){
			// 				$done = true;
			// 			}
			// 			$content['id_card_api'] = $result['response'];
			// 		}else{
			// 			$content['id_card_api'] = 'no response';
			// 			$done = false;
			// 		}
			// 	}
			// }

            $remark['error'] = $msg;
            $remark['OCR']   = $ocr;
            $param = [
                'status'	    => 3,
                'remark'	    => json_encode($remark),
                'content'	    => json_encode($content),
                'sys_check'     => 1,
            ];
            if($remark['error']==''&&$done){
                $param = [
                    'remark'	    => json_encode($remark),
                    'content'	    => json_encode($content),
                ];
                $this->set_success($info->id ,true);
            }
            $this->CI->user_certification_model->update($info->id,$param);
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

    public function social_verify($info = array())
    {
        if ($info && $info->status == 0 && $info->certification_id == 4) {
			$param['status'] = 3;
            $content = json_decode($info->content);

            if (isset($content->instagram->username) && isset($info->user_id)) {
                $this->CI->load->library('scraper/instagram_lib');
                $user_followed_info = $this->CI->instagram_lib->getUserFollow($info->user_id, $content->instagram->username);

                if ($user_followed_info && $user_followed_info->status == 204) {
                    $this->CI->instagram_lib->autoFollow($info->user_id, $content->instagram->username);
                    $this->CI->instagram_lib->updateUserFollow($info->user_id, $content->instagram->username);
                    return false;
                }
                $param['sys_check'] = 1;
                if ($user_followed_info && $user_followed_info->status == 200 && !empty($user_followed_info->response->result)) {
                    $content->instagram->status = $user_followed_info->response->result->info->followStatus;
                    $content->instagram->link = 'https://www.instagram.com/' . $content->instagram->username;
                    $content->instagram->counts->media = $user_followed_info->response->result->info->allPostCount;
                    $content->instagram->counts->follows = $user_followed_info->response->result->info->allFollowingCount;
                    $content->instagram->counts->followed_by = $user_followed_info->response->result->info->allFollowerCount;
                    $update_time = $user_followed_info->response->result->updatedAt;
                    if ($content->instagram->status == 'unfollowed' && $update_time && time() >= ($update_time + rand(5,30)*137)) {
                        $this->CI->instagram_lib->updateUserFollow($info->user_id, $content->instagram->username);
                    }else {
                        if($content->instagram->counts->media != ''){
                            if ($update_time && time() >= ($update_time + 86400)) {
                                $this->CI->instagram_lib->updateUserFollow($info->user_id, $content->instagram->username);
                            }
                        }
                    }
                }
                $media = $content->instagram->counts->media;
                $followed_by = $content->instagram->counts->followed_by;
                $is_fb_email = isset($content->facebook->email);
                $is_fb_name = isset($content->facebook->name);
                $this->CI->load->model('user/user_meta_model');
                $line = $this->CI->user_meta_model->get_by(array(
                    'user_id' => $info->user_id,
                    'meta_key' => 'line_access_token'
                ));
                if ((is_numeric($media) && is_numeric($followed_by) || $is_fb_email && $is_fb_name) && isset($line)) {
                    if ($media >= 10 && $followed_by >= 10 || is_numeric($media) && is_numeric($followed_by) ) {
                        $param['status'] = 1;
                        $this->set_success($info->id, true);
                    }
                }
                $param['content'] = json_encode($content);
                $this->CI->user_certification_model->update($info->id, $param);
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

				$phone_used = $this->CI->user_model->get_by(array(
					'id'    => $info->user_id,
					'phone' => $content['phone'],
				));
				if($phone_used){
					$this->set_failed($info->id,'與註冊電話相同',true);
				}
				else{
					$this->set_success($info->id, true);
					$status = 1;
				}

                $this->CI->user_certification_model->update($info->id,array(
                    'status'	=> $status,
                    'sys_check'	=> 1,
                ));
                return true;
            }
		}
		return false;
	}


	public function investigation_verify($info = array(), $url=null)
	{
		$user_certification	= $this->get_certification_info($info->user_id,1,$info->investor);
		$job_certification = $this->get_certification_info($info->user_id,10,$info->investor);
		if($user_certification==false || $user_certification->status!=1 ||$job_certification ==false || $job_certification->status!=1){
			return false;
		}
		$certification_content = isset($info->content) ? json_decode($info->content,true): [];
		$url = isset($certification_content['pdf_file']) ? $certification_content['pdf_file']: null;
		$result = [];
		$verifiedResult = new InvestigationCertificationResult(3);
		$time = time();
		$printDatetime = '';

		if ($info && $info->certification_id == 9 && $url && $info->status == 0) {
			$remark = isset($info->remark) ? json_decode($info->remark, true) : NULL;
			$remark['verify_result'] = [];
			$this->CI->load->library('Joint_credit_lib');
			$parser = new \Smalot\PdfParser\Parser();
			$pdf    = $parser->parseFile($url);
			$text = $pdf->getText();
			$response = $this->CI->joint_credit_lib->transfrom_pdf_data($text);
			$data = [
				'id' => isset($response['applierInfo']['basicInfo']['personId']) ? $response['applierInfo']['basicInfo']['personId']: '',
			];

			// 資料轉 result
			$this->CI->load->library('mapping/user/Certification_data');
			$result = $this->CI->certification_data->transformJointCreditToResult($response);

			// 印表日期
			$this->CI->load->library('mapping/time');
			$printTimestamp = preg_replace('/\s[0-9]{2}\:[0-9]{2}\:[0-9]{2}/','',$result['printDatetime']);
			$printTimestamp = $this->CI->time->ROCDateToUnixTimestamp($printTimestamp);
			$printDatetime = date('Y-m-d',$printTimestamp);

			$group_id = isset(json_decode($info->content)->group_id) ? json_decode($info->content)->group_id : time();
			$certification_content['group_id'] = $group_id;
			$certification_content['result']["$group_id"] = $result;
			$certification_content['times'] = isset($result['S1Count']) ? $result['S1Count'] : 0;
			$certification_content['credit_rate'] = isset($result['creditCardUseRate']) ? $result['creditCardUseRate'] : 0;
			$certification_content['months'] = isset($result['creditLogCount']) ? $result['creditLogCount'] : 0;
			$certification_content['printDatetime'] = $time;
			$certification_content['printDate'] = $printDatetime;

			// 還款力計算-22倍薪資
			// 薪資22倍
			$certification_content['total_repayment'] = '';
			// 投保金額
			$certification_content['monthly_repayment'] = '';
			// 借款總額是否小於薪資22倍
			$certification_content['total_repayment_enough'] = '';
			// 每月還款是否小於投保金額
			$certification_content['monthly_repayment_enough'] = '';
			// 負債比
			$certification_content['debt_to_equity_ratio'] = 0;

			if(isset($job_certification->content)){
				if(! is_array($job_certification->content)){
					$job_certification_content = json_decode($job_certification->content,true);
				}else{
					$job_certification_content = $job_certification->content;
				}
				$certification_content['monthly_repayment'] = isset($job_certification_content['salary']) && is_numeric($job_certification_content['salary']) ? $job_certification_content['salary']/1000 : '';
				$certification_content['total_repayment'] = isset($job_certification_content['salary']) && is_numeric($job_certification_content['salary']) ? $job_certification_content['salary']*22/1000 : '';
			}

			if(isset($result['totalMonthlyPayment'])  && $certification_content['monthly_repayment']){
				// 每月還款是否小於投保金額
				if($result['totalMonthlyPayment'] < $certification_content['monthly_repayment']){
					$certification_content['monthly_repayment_enough'] = '是';
				}else{
					$certification_content['monthly_repayment_enough'] = '否';
				}
			}else{
				$certification_content['monthly_repayment_enough'] = '資料不齊無法比對';
			}

			if(isset($result['totalAmountQuota']) && $certification_content['total_repayment']){
				// 借款總額是否小於薪資22倍
				if($result['totalAmountQuota'] < $certification_content['total_repayment']){
					$certification_content['total_repayment_enough'] = '是';
				}else{
					$certification_content['total_repayment_enough'] = '否';
				}
			}else{
				$certification_content['total_repayment_enough'] = '資料不齊無法比對';
			}

			if(isset($approve_status['status_code']) && $approve_status['status_code'] == 2){
				// to do : 鎖三十天
				// $certification_content['mail_file_status'] = 2;
			}

			// 負債比計算，投保薪資不能為0
			if(intval($certification_content['monthly_repayment']))
				$certification_content['debt_to_equity_ratio'] = round($result['totalMonthlyPayment'] / $certification_content['monthly_repayment'] * 100, 2);

			// 自然人聯徵正確性驗證
			$this->CI->load->library('verify/data_legalize_lib');
			$verifiedResult = $this->CI->data_legalize_lib->legalize_investigation($verifiedResult,$info->user_id,$result,$info->created_at);

			// 過件邏輯
			$this->CI->load->library('verify/data_verify_lib');
			$verifiedResult = $this->CI->data_verify_lib->check_investigation($verifiedResult, $result, $certification_content);

			$remark['verify_result'] = array_merge($remark['verify_result'],$verifiedResult->getAllMessage(MassageDisplay::Backend));
			$status = $verifiedResult->getStatus();

			$expire_time = new DateTime;
			$expire_time->setTimestamp($printTimestamp);
			$expire_time->modify( '+ 1 month' );
			$expire_timestamp = $expire_time->getTimestamp();

			$this->CI->user_certification_model->update($info->id, array(
				'status' => $status,
				'sys_check' => 1,
				'content' => json_encode($certification_content),
				'remark' => json_encode($remark),
				'expire_time' => $expire_timestamp
        	));

			if($status == 1) {
				$this->investigation_success($info);
			}else if($status == 2) {
				$canResubmitDate = $verifiedResult->getCanResubmitDate($info->created_at);
				$notificationContent = $verifiedResult->getAPPMessage(2);
				$this->certi_failed($info,$notificationContent,$canResubmitDate,1);

			}

			return true;
		}
		return false;
	}

	// 寫入信箱夾帶檔案位置
	public function save_mail_url($info = array(),$url) {
		$content=json_decode($info->content,true);
		$content['pdf_file']=$url;
		if($url){
			$content['mail_file_status'] = 1;
		}else{
			$content['mail_file_status'] = 0;
		}

		$this->CI->user_certification_model->update($info->id, array(
			'content'=>json_encode($content)
		));
		return true;
	}

	public function job_verify($info = array(),$url=null) {

		$realname_certification	= $this->get_certification_info($info->user_id,1,$info->investor);

		if($realname_certification==false || $realname_certification->status != 1){
			return false;
		}

		$certification_content = isset($info->content) ? json_decode($info->content,true) : [];

		$verifiedResult = new JobCertificationResult(3);
		$res = [];
		$gcis_res = [];
		$remark = isset($info->remark) ? json_decode($info->remark,true) : NULL;
		$remark['verify_result'] = [];

		// 勞保異動明細 pdf
		$pdf_url = isset($certification_content['pdf_file']) ? $certification_content['pdf_file'] : '';

		if($info && $info->certification_id == 10 && $info->status == 0 && $pdf_url) {
			// 勞保 pdf 解析
			if($pdf_url){
				$this->CI->load->library('Labor_insurance_lib');
				$parser = new \Smalot\PdfParser\Parser();
				$pdf    = $parser->parseFile($pdf_url);
				$text = $pdf->getText();
				$res = $this->CI->labor_insurance_lib->transfrom_pdf_data($text);
			}

			if(isset($certification_content['tax_id']) && $certification_content['tax_id']) {
				$this->CI->load->library('gcis_lib');
				$gcis_res = $this->CI->gcis_lib->account_info($certification_content['tax_id']);
				$certification_content['gcis_info'] = $gcis_res;
				$res['gcis_info'] = $gcis_res;
			}

			if($res) {
				$this->CI->load->library('mapping/user/Certification_data');
				$result = $this->CI->certification_data->transformJobToResult($res);
				$certification_content['pdf_info'] = $result;
			}else{
				$verifiedResult->addMessage('勞保pdf解析失敗',3, MassageDisplay::Backend);
			}

			//勞保 pdf 驗證
			if(isset($result) && isset($certification_content['tax_id'])) {

				$this->CI->load->library('verify/data_legalize_lib');
				$verifiedResult = $this->CI->data_legalize_lib->legalize_job($verifiedResult,$info->user_id,$result,$certification_content,$info->created_at);

				$this->CI->load->library('verify/data_verify_lib');
				$verifiedResult = $this->CI->data_verify_lib->check_job($verifiedResult,$info->user_id,$result,$certification_content);

				// to do : 是否千大企業員工
				// $this->CI->config->load('top_enterprise');
				// $top_enterprise = $this->CI->config->item("top_enterprise");

			}

			$remark['verify_result'] = array_merge($remark['verify_result'],$verifiedResult->getAllMessage(MassageDisplay::Backend));
			$status = $verifiedResult->getStatus();

			$expire_timestamp = 0;
			preg_match('/^(?<year>(1[0-9]{2}|[0-9]{2}))(?<month>(0?[1-9]|1[012]))(?<day>(0?[1-9]|[12][0-9]|3[01]))$/', $result['report_date'], $regexResult);
			if(!empty($regexResult)) {
				$date = sprintf("%d-%'.02d-%'.02d", intval($regexResult['year']) + 1911,
					intval($regexResult['month']), intval($regexResult['day']));
				$expire_time = DateTime::createFromFormat('Y-m-d', $date);
				$expire_time->modify( '+ 1 month' );
				$expire_timestamp = $expire_time->getTimestamp();
			}

			$this->CI->user_certification_model->update($info->id, array(
				'status' => $status,
				'sys_check' => 1,
				'remark' => json_encode($remark),
				'content' => json_encode($certification_content),
				'expire_time' => $expire_timestamp
			));

			if($status == 1) {
				$this->job_success($info);
			}else if($status == 2) {
				$canResubmitDate = $verifiedResult->getCanResubmitDate($info->created_at);
				$notificationContent = $verifiedResult->getAPPMessage(2);
				$this->certi_failed($info,$notificationContent,$canResubmitDate,1);
			}

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
			isset($content['graduate_date']) ? $data['graduate_date'] = $content['graduate_date'] : '';
            isset($content['programming_language']) ? $data['student_programming_language'] = count($content['programming_language']) : '';
            isset($content['transcript_image']) ? $data['transcript_front'] = $content['transcript_image'][0] : '';

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
                $this->CI->load->library('brookesia/brookesia_lib');
                $this->CI->brookesia_lib->userCheckAllRules($info->user_id);
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
                $data['financial_passbook'] = $content['passbook_image'][0];
            }

            if(isset($content['bill_phone_image'])){
                $data['financial_bill_phone'] = $content['bill_phone_image'][0];
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
			);
			if (isset($content['facebook'])) {
				$data['fb_name'] = $content['facebook']['name'];
				$data['fb_id'] = $content['facebook']['id'];
				$data['fb_email'] = $content['facebook']['email'];
				$data['fb_access_token'] = $content['facebook']['access_token'];
			}
			if (isset($content['instagram'])) {
				isset($content['instagram']['id']) ? $data['ig_id'] = $content['instagram']['id'] : '';
				isset($content['instagram']['username']) ? $data['ig_username'] = $content['instagram']['username'] : '';
				isset($content['instagram']['name']) ? $data['ig_name'] = $content['instagram']['name'] : '';
				isset($content['instagram']['access_token']) ? $data['ig_access_token'] = $content['instagram']['access_token'] : '';
			}


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
				'diploma_image'		=> $content['diploma_image'][0],
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
				'investigation_months'		=> $content['months'],
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
			$certification 	= $this->certification[$info->certification_id];
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
                'game_work_level'=> $content['game_work_level'],
			];

            isset($content['programming_language'])?$data['job_programming_language']=$content['programming_language']:'';
            isset($content['job_title'])?$data['job_title']=$content['job_title']:'';

            $rs = $this->user_meta_progress($data,$info);
			if($rs){
				$this->CI->notification_lib->certification($info->user_id,$info->investor,$certification['name'],1);
                return $this->fail_other_cer($info);
			}
		}
		return false;
	}

	private function certi_failed($info,$msg='',$resubmitDate='',$sys_check=true){
		if($info){
			$certification 	= $this->certification[$info->certification_id];
			$param = [
				'status'    => 2,
				'sys_check' => ($sys_check==true?1:0),
				'can_resubmit_at'=>$resubmitDate
			];

			$rs = $this->CI->user_certification_model->update($info->id, $param);
			if($rs){
				$this->CI->notification_lib->certification($info->user_id,$info->investor,$certification['name'],2,$msg);
			}
		}
		return false;
	}

    private function businesstax_success($info){
        if($info){
            $data 		= array(
                //'debit_card_status'			=> 1,
            );

            $rs = $this->user_meta_progress($data,$info);
            if($rs){
                return $this->fail_other_cer($info);
            }
        }
        return false;
    }

    private function balancesheet_success($info){
        if($info){
            $data 		= array(
                //'debit_card_status'			=> 1,
            );

            $rs = $this->user_meta_progress($data,$info);
            if($rs){
                return $this->fail_other_cer($info);
            }
        }
        return false;
    }

    private function incomestatement_success($info){
        if($info){
            $data 		= array(
                //'debit_card_status'			=> 1,
            );

            $rs = $this->user_meta_progress($data,$info);
            if($rs){
                return $this->fail_other_cer($info);
            }
        }
        return false;
    }

    private function investigationjudicial_success($info){
        if($info){
            $data 		= array(
                //'debit_card_status'			=> 1,
            );

            $rs = $this->user_meta_progress($data,$info);
            if($rs){
                return $this->fail_other_cer($info);
            }
        }
        return false;
    }

    private function passbookcashflow_success($info){
        if($info){
            $data 		= array(
                //'debit_card_status'			=> 1,
            );

            $rs = $this->user_meta_progress($data,$info);
            if($rs){
                return $this->fail_other_cer($info);
            }
        }
        return false;
    }

    private function governmentauthorities_success($info){
        if($info){
            $data 		= array(
            );

            $rs = $this->user_meta_progress($data,$info);
            if($rs){
                return $this->fail_other_cer($info);
            }
        }
        return false;
    }

    private function interview_success($info){
        if($info){
            $data 		= array(
                //'debit_card_status'			=> 1,
            );

            $rs = $this->user_meta_progress($data,$info);
            if($rs){
                return $this->fail_other_cer($info);
            }
        }
        return false;
    }

    public function cerCreditJudicial_success($info){
        if($info){
            $content 	= $info->content;
            $data 		= array(
                'creditJudicial_status' => 1,
                'companyHistoyAndCompanyDevelopment' => $content['companyHistoyAndCompanyDevelopment'],
                'companyCatureAndRegualations-1' => $content['companyCatureAndRegualations-1'],
                'companyCatureAndRegualations-2' => $content['companyCatureAndRegualations-2'],
                'companyCatureAndRegualations-3' => $content['companyCatureAndRegualations-3'],
                'companyCatureAndRegualations-4' => $content['companyCatureAndRegualations-4'],
                'companyCatureAndRegualations-5' => $content['companyCatureAndRegualations-5'],
                'opratorsAndTeamBackgroundMeans_1' => $content['opratorsAndTeamBackgroundMeans_1'],
                'opratorsAndTeamBackgroundMeans_2' => $content['opratorsAndTeamBackgroundMeans_2'],
                'opratorsAndTeamBackgroundMeans_3' => $content['opratorsAndTeamBackgroundMeans_3'],
                'revenue' => $content['revenue'],
                'policyImpact' => $content['policyImpact'],
                'internationlEconomicalImpact' => $content['internationlEconomicalImpact'],
                'industryProspect' => $content['industryProspect'],
                'companyOperateProspect_1' => $content['companyOperateProspect_1'],
                'companyOperateProspect_2' => $content['companyOperateProspect_2'],
                'companyOperateProspect_2-1' => $content['companyOperateProspect_2-1'],
                'companyOperateProspect_2-2' => $content['companyOperateProspect_2-2'],
                'companyOperateProspect_3-1' => $content['companyOperateProspect_3-1'],
                'companyOperateProspect_3-2' => $content['companyOperateProspect_3-2'],
                'companyOperateProspect_3-3' => $content['companyOperateProspect_3-3'],
                'companyOperateProspect_3-4' => $content['companyOperateProspect_3-4'],
                'sameIndustryAndCustomerEvaluation-1' => $content['sameIndustryAndCustomerEvaluation-1'],
                'sameIndustryAndCustomerEvaluation-2' => $content['sameIndustryAndCustomerEvaluation-2'],
                'sameIndustryAndCustomerEvaluation-3' => $content['sameIndustryAndCustomerEvaluation-3'],
                'sameIndustryAndCustomerEvaluation-4' => $content['sameIndustryAndCustomerEvaluation-4'],
                'sameIndustryAndCustomerEvaluation-5' => $content['sameIndustryAndCustomerEvaluation-5'],
            );

            $rs = $this->user_meta_progress($data,$info);
            if($rs){
                return $this->fail_other_cer($info);
            }
        }
        return false;
    }

    private function salesdetail_success($info){
        if($info){
            $data 		= array(
                //'debit_card_status'			=> 1,
            );

            $rs = $this->user_meta_progress($data,$info);
            if($rs){
                return $this->fail_other_cer($info);
            }
        }
        return false;
    }



    public function get_status($user_id,$investor=0,$company=0,$get_fail=false,$target=false){
		if($user_id){
			$certification = array();
			if($company){
                $total = 0;
                $allows = ['businesstax','governmentauthorities'];
                $company = $this->get_company_type($user_id);
                //FEV
                $company->selling_type == 2 ? $allows = array_merge($allows,['salesdetail','cercreditjudicial']) : '';

                $this->CI->load->model('transaction/order_model');
                $orders = $this->CI->order_model->get_many_by([
                    'company_user_id' => $user_id,
                    'status' => 10,
                ]);
                if($orders){
                    $this->CI->load->library('target_lib');
                    foreach($orders as $key => $value){
                        $targets = $this->CI->target_model->get_by([
                            'status' => 5,
                            'order_id' => $value->id,
                        ]);
                        if($targets) {
                            $total += $this->CI->target_lib->get_amortization_table($targets)['remaining_principal'];
                        }
                    }
                }
                $self_targets = $this->CI->target_model->get_many_by([
                    'status' => 5,
                    'user_id' => $user_id,
                ]);
                if($self_targets) {
                    foreach($self_targets as $key => $value) {
                        //$total += $this->CI->target_lib->get_amortization_table($value)['remaining_principal'];
                    }
                }
                $total >= 500000 || $company->selling_type == 2?$allows = array_merge($allows,['balancesheet','incomestatement','investigationjudicial','passbookcashflow'] ):'';
                if($total >= 1000000 && $company->selling_type != 2){
                    $allows[] = 'interview';
                }

                foreach($this->certification as $key => $value){
					if(in_array($value['alias'],$allows)){
						$certification[$key] = $value;
					}
				}
			}else if($investor){
				foreach($this->certification as $key => $value){
					if(in_array($value['alias'],['idcard','debitcard','email','emergency'])){
						$certification[$key] = $value;
					}
				}
			}else if($target){
                foreach($this->certification as $key => $value) {
                    if ($target->product_id >= 1000 && ($key > 10 || $key == 9) || $target->product_id < 1000 && $key <= 10) {
                        $certification[$key] = $value;
                    }
                    else{
                        unset($certification[$key]);
                    }
                }
            }else{
                foreach($this->certification as $key => $value) {
                    if ($key < 1000) {
                        $certification[$key] = $value;
                    }
                    else{
                        unset($certification[$key]);
                    }
                }
            }

			$certification_list = [];
			foreach($certification as $key => $value){
                $user_certification = $this->get_certification_info($user_id,$key,$investor,$get_fail);
                if($user_certification){
					$value['user_status'] 		   = intval($user_certification->status);
					$value['certification_id'] 	   = intval($user_certification->id);
                    $value['updated_at'] 		   = intval($user_certification->updated_at);
					// 回傳認證資料
					$value['content']		   = $user_certification->content;
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

    public function get_last_status($user_id,$investor=0,$company=0,$target=false){
		if($user_id){
			$certification = [];
            $company_source_user_id = false;
            if($target){
                if($target->product_id >= 1000){
                    $company = $this->get_company_type($user_id);
                    $company_source_user_id = $company->user_id;
                }
                foreach($this->certification as $key => $value) {
                    if ($target->product_id >= 1000 && ($key > 10 || $key == 9) || $target->product_id < 1000 && $key <= 10) {
                        $certification[$key] = $value;
                    }
                    else{
                        unset($certification[$key]);
                    }
                }
            }else if($investor){
				foreach($this->certification as $key => $value){
					if(in_array($value['alias'],['idcard','debitcard','email','emergency'])){
						$certification[$key] = $value;
					}
				}
            }elseif($company){
                foreach($this->certification as $key => $value){
                    if(in_array($value['alias'],['businesstax','balancesheet','incomestatement','investigationjudicial','passbookcashflow','governmentauthorities','salesdetail'])){
                        $certification[$key] = $value;
                    }
                }
            }else{
                foreach($this->certification as $key => $value) {
                    if ($key < 1000) {
                        $certification[$key] = $value;
                    }
                    else{
                        unset($certification[$key]);
                    }
                }
            }

			$certification_list = [];
			foreach($certification as $key => $value){
                $ruser_id = $key == 9 && $company_source_user_id?$company_source_user_id:$user_id;
                $user_certification = $this->get_certification_info($ruser_id,$key,$investor);
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
		$user_certifications 	= $this->CI->user_certification_model->order_by('certification_id','ASC')->get_many_by(array(
			'status'				=> 0,
			'certification_id !='	=> 3,
		));
		if($user_certifications){
			foreach($user_certifications as $key => $value){
				switch($value->certification_id){
					case 2:
						if(time() > ($value->created_at + 3600)){
							$this->set_failed($value->id,'未在有效時間內完成認證');
						}
						break;
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
            'investor'			=> $info->investor,
            'certification_id'	=> $info->certification_id,
            'status'			=> [0,1,2,3]
        ], ['status'=> 2]);
        return $rs;
    }

    public function expire_certification($user_id,$investor=0){
        if($user_id && $investor == 0) {
            $certification = $this->CI->user_certification_model->order_by('created_at', 'desc')->get_many_by([
                'user_id' => $user_id,
                'investor' => $investor,
                'status !=' => 2,
            ]);
            if ($certification) {
                foreach ($certification as $key => $value) {
                    $expireGraduateDate = false;
                    if($value->certification_id == CERTIFICATION_STUDENT && $value->status == 1){
                        $content = json_decode($value->content);
                        if(isset($content->graduate_date) && !empty($content->graduate_date)){
                            preg_match_all('/\d+/', $content->graduate_date, $matches);
                            $rocYear = date('Y') - 1911;
                            if($rocYear >= $matches[0][0]){
                                $expireGraduateDate = true;
                                $rocYear == $matches[0][0] && date('m') <= $matches[0][1] ? $expireGraduateDate = false : '';
                            }
                        }
                    }

                    if (!in_array($value->certification_id, [CERTIFICATION_IDCARD, CERTIFICATION_DEBITCARD, CERTIFICATION_EMERGENCY, CERTIFICATION_EMAIL, CERTIFICATION_DIPLOMA])
                                && $value->expire_time <= time()
                            || in_array($value->certification_id, [CERTIFICATION_INVESTIGATION, CERTIFICATION_JOB])
                                && $value->status == 1 && time() > strtotime('+2 months', $value->updated_at)
                            || $expireGraduateDate
                    ) {
                        $this->set_failed($value->id, '認證已逾期。', true);
                    }
                }
            }
        }
        return false;
    }

    private function get_company_type($user_id){
        $this->CI->load->model('user/judicial_person_model');
        $company = $this->CI->judicial_person_model->get_by(array(
            'company_user_id' 	=> $user_id,
        ));
        return $company;
    }

    public function papago_facedetact_report($limit = 10)
    {
        $certification_list = $this->CI->user_certification_model->limit($limit)->order_by('updated_at', 'desc')->get_many_by([
            'certification_id' => 1,
            'remark like' => '%face":[%',
            'remark not like' => '%face":[]%',
            'remark like' => '%faceplus":[%',
            'remark not like' => '%faceplus":[]%',
        ]);
        $list = [];
        foreach ($certification_list as $key => $value) {
            $person_compare = [];
            $content = json_decode($value->content, true);
            $remark = json_decode($value->remark, true);
            $user_id = $value->user_id;
            $cer_id = $value->id;
            $this->CI->load->library('Papago_lib');
            $person_face = $this->CI->papago_lib->detect($content['person_image'], $user_id, $cer_id);
            $front_face = $this->CI->papago_lib->detect($content['front_image'], $user_id, $cer_id);
            $person_count = count($person_face['faces']);
            $front_count = count($front_face['faces']);
            foreach ($person_face['faces'] as $token) {
                if(isset($token['face_token'])){
                    $compare_res = $this->CI->papago_lib->compare([$token['face_token'], $front_face['faces'][0]['face_token']], $user_id, $cer_id);
                    $person_compare[] = isset($compare_res['confidence']) ? $compare_res['confidence'] * 100 : '0';
                }
            }
            $ocount_person_count = $remark['face_count']['person_count'];
            $ocount_front_count = $remark['face_count']['front_count'];
            $azure_face1 = isset($remark['face'][0]) ? $remark['face'][0] : 'n/a';
            $azure_face2 = isset($remark['face'][1]) ? $remark['face'][1] : 'n/a';
            $faceplus_face1 = isset($remark['faceplus'][0]) ? $remark['faceplus'][0] : 'n/a';
            $faceplus_face2 = isset($remark['faceplus'][1]) ? $remark['faceplus'][1] : 'n/a';
            $face8_face1 = isset($person_compare[0]) ? $person_compare[0] : 'n/a';
            $face8_face2 = isset($person_compare[1]) ? $person_compare[1] : 'n/a';
            $list[] = [
                $user_id,
                $content['id_card_date'],
                $ocount_person_count . '/' . $ocount_front_count,
                $azure_face1,
                $azure_face2,
                $ocount_person_count . '/' . $ocount_front_count,
                $faceplus_face1,
                $faceplus_face2,
                $person_count . '/' . $front_count,
                $face8_face1,
                $face8_face2,
            ];
        }
        return $list;
    }

    public function veify_signing_face($user_id, $url = false)
    {
        if ($url) {
            $msg = '';
            $remark = [];
            $idcard_cer = $this->get_certification_info($user_id, 1, 0);
            $student_cer = $this->get_certification_info($user_id, 2, 0);
            $diploma_cer = $this->get_certification_info($user_id, 8, 0);
            if ($idcard_cer && $idcard_cer->status == 1
                && (isset($student_cer->content['school']) && !preg_match('/\(自填\)/', $student_cer->content['school']))
                && (isset($diploma_cer->content['school']) && !preg_match('/\(自填\)/', $diploma_cer->content['school']))
            ) {
                $cer_id = $idcard_cer->id;
                $this->CI->load->library('Azure_lib');
                $idcard_cer_face = $this->CI->azure_lib->detect($idcard_cer->content['person_image'], $user_id, $cer_id);
                $signing_face = $this->CI->azure_lib->detect($url, $user_id, $cer_id);
                $signing_face_count = count($signing_face);
                if ($signing_face_count == 0) {
                    $rotate = $this->face_rotate($url, $user_id);
                    if ($rotate) {
                        $idcard_cer->content['person_image'] = $rotate['url'];
                        $signing_face_count = $rotate['count'];
                    }
                }
                if ($signing_face_count >= 2 && $signing_face_count <= 3) {
                    $person_compare[] = $this->CI->azure_lib->verify($idcard_cer_face[0]['faceId'], $signing_face[0]['faceId'], $user_id, $cer_id);
                    $person_compare[] = $this->CI->azure_lib->verify($idcard_cer_face[1]['faceId'], $signing_face[1]['faceId'], $user_id, $cer_id);
                    $remark['face'] = [$person_compare[0]['confidence'] * 100, $person_compare[1]['confidence'] * 100];
                    $remark['face_flag'] = [$person_compare[0]['isIdentical'], $person_compare[1]['isIdentical']];
                    if ($remark['face'][0] < 90 || $remark['face'][1] < 90) {
                        $this->CI->load->library('Faceplusplus_lib');
                        $idcard_cer_token = $this->CI->faceplusplus_lib->get_face_token($idcard_cer->content['person_image'], $user_id, $cer_id);
                        $signing_face_token = $this->CI->faceplusplus_lib->get_face_token($idcard_cer->content['person_image'], $user_id, $cer_id);
                        $signing_face_count = $signing_face_token && is_array($signing_face_token) ? count($signing_face_token) : 0;
                        if ($signing_face_count == 0) {
                            $rotate = $this->face_rotate($idcard_cer->content['person_image'], $user_id, $cer_id, 'faceplusplus');
                            if ($rotate) {
                                $idcard_cer->content['person_image'] = $rotate['url'];
                                $signing_face_count = $rotate['count'];
                                $signing_face_token = $signing_face_count;
                            }
                        }
                        if ($signing_face_count == 2) {
                            $answer[] = $this->CI->faceplusplus_lib->token_compare($idcard_cer_token[0], $signing_face_token[0], $user_id, $cer_id);
                            $answer[] = $this->CI->faceplusplus_lib->token_compare($idcard_cer_token[1], $signing_face_token[1], $user_id, $cer_id);
                            sort($answer);
                            $remark['faceplus'] = $answer;
                            if ($answer[0] < 90 || $answer[1] < 90) {
                                $msg .= 'Sys2人臉比對分數不足';
                            }
                        } else {
                            $msg .= 'Sys2人臉數量不足';
                        }
                    }elseif ($remark['face'][0] == 100){
                        $msg .= '分數過高，可能為同一張照片';
                    }
                } else {
                    $msg .= '系統判定人臉數量不正確，可能有陰影或其他因素';
                }
            }
            $remark['error'] = $msg;
            return $remark;
        }
        return false;
    }

    public function get_social_report($limit = 10){
        $certification_list = $this->CI->user_certification_model->order_by('user_id', 'desc')->get_many_by([
            'certification_id' => 4,
            'status' => 1,
            'content not like' => '%instagram\":\"\"}%',
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ]);
        $list = [];
        $users = [];
        $this->CI->load->model('user/user_model');
        $this->CI->load->library('Transaction_lib');
        $delayUserList = $this->CI->transaction_lib->getDelayUserList();
        foreach ($certification_list as $key => $value) {
            if(!in_array($value->user_id, $users)){
                $users[] = $value->user_id;
                $content = json_decode($value->content);
                $ig = isset($content->instagram->counts) ? 'instagram' : 'info';
                if(isset($content->$ig->counts) && $content->$ig->counts->media >= 10){
                    $user_info 	= $this->CI->user_model->get($value->user_id);
                    if($user_info && count($content->$ig->meta) >= 10){
                        $metas = [];
                        foreach ($content->$ig->meta as $contentKey => $contentValue) {
                            array_push($metas, date("Y/m/d H:i:s",$contentValue->created_time));
                            array_push($metas, $contentValue->text);
                            array_push($metas, $contentValue->likes);
                        }
                        $list[] = array_merge([
                            $value->user_id,
                            ($user_info->sex == 'M' ? '男' : '女'),
                            (in_array($value->user_id, $delayUserList) ? 'Y' : 'N'),
                            $content->$ig->counts->media,
                            $content->$ig->counts->followed_by,
                            $content->$ig->counts->follows,
                        ],$metas);
                    }
                }
            }
            if(count($list) >= $limit){
                break;
            }
        }
        return $list;
    }
}

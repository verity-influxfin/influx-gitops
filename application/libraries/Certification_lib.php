<?php defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;
use Smalot\PdfParser\Parser;
use CertificationResult\CertificationResultFactory;
use CertificationResult\InvestigationCertificationResult;
use CertificationResult\MessageDisplay;
use Certification\Certification_factory;

class Certification_lib{

	public $certification;
    private $notification_list;

	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->library('ocr/report_scan_lib');
		$this->CI->load->model('user/user_certification_model');
		$this->CI->load->model('user/user_meta_model');
		$this->CI->load->model('log/log_image_model');
		$this->CI->load->library('Notification_lib');
		$this->certification = $this->CI->config->item('certifications');
        $this->notification_list = [];
    }

    public function justLegalizeCertification($status) {
        return $status == CERTIFICATION_STATUS_PENDING_TO_AUTHENTICATION;
    }

    public function canVerify($status) {
        return in_array($status, [CERTIFICATION_STATUS_PENDING_TO_VALIDATE, CERTIFICATION_STATUS_AUTHENTICATED]);
    }

    /**
     * 篩選特定狀態的認證項目
     * @param $userCertifications
     * @param int[] $findStatusList
     * @return int[]|string[]
     */
    public function filterCertIdsInStatusList($userCertifications, array $findStatusList=[CERTIFICATION_STATUS_SUCCEED]) {
        return array_unique(array_keys(array_filter($userCertifications,
            function ($x) use ($findStatusList) { return (is_array($x) && in_array($x['status'], $findStatusList))
                || (is_object($x) && in_array($x->status, $findStatusList)); })));
    }

    /**
     * 取得產品的各階段徵信檢核項目列表
     * @param $product_id
     * @return mixed|null
     */
    public function getCertificationsStageList($product_id) {
        $productList = $this->CI->config->item('product_list');
        return isset($productList[$product_id]) &&
            isset($productList[$product_id]['certifications_stage']) ?
            $productList[$product_id]['certifications_stage'] : null;
    }

    /**
     * 確認特定驗證階段是否都有紀錄
     * @param $product_id
     * @param $certificationList
     * @param $stage
     * @return bool
     */
    public function checkVerifiedStage($product_id, $certificationList, $stage) {
        $certificationsStageList = $this->getCertificationsStageList($product_id);

        return isset($certificationsStageList) &&
            (count(array_intersect($certificationsStageList[$stage], $certificationList))
            == count($certificationsStageList[$stage]));
    }

	public function get_certification_info($user_id,$certification_id,$investor=0,$get_fail=false, $get_expired = FALSE){
		if($user_id && $certification_id){
			$param = array(
				'user_id'			=> $user_id,
				'certification_id'	=> $certification_id,
				'investor'			=> $investor,
			);
            !$get_fail ? $param['status'] = [CERTIFICATION_STATUS_PENDING_TO_VALIDATE,
                CERTIFICATION_STATUS_SUCCEED, CERTIFICATION_STATUS_PENDING_TO_REVIEW,
                CERTIFICATION_STATUS_NOT_COMPLETED, CERTIFICATION_STATUS_AUTHENTICATED]
                : $param['status NOT'] = [CERTIFICATION_STATUS_PENDING_SPOUSE_ASSOCIATE];
			$certification = $this->CI->user_certification_model->order_by('created_at','desc')->get_by($param);
			if(!empty($certification)){
                if ($get_expired == FALSE && $certification->expire_time <= time()&&$investor==0&&!in_array($certification_id,[CERTIFICATION_IDENTITY,CERTIFICATION_DEBITCARD,CERTIFICATION_EMERGENCY,CERTIFICATION_EMAIL])){
                    return false;
                }
			    else{
                    $certification->id 					= intval($certification->id);
                    $certification->user_id 			= intval($certification->user_id);
                    $certification->investor 			= intval($certification->investor);
                    $certification->status 				= intval($certification->status);
                    $certification->certification_id 	= intval($certification->certification_id);
                    $certification->certificate_status = (int) $certification->certificate_status;
                    $certification->created_at 			= intval($certification->created_at);
                    $certification->updated_at 			= intval($certification->updated_at);
                    $certification->content = json_decode($certification->content,true);
                    $certification->remark              = isJson($certification->remark) ? json_decode($certification->remark,true) : $certification->remark;
                    $certification->expire_time = (int) $certification->expire_time;
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
                'status NOT' => [CERTIFICATION_STATUS_PENDING_SPOUSE_ASSOCIATE]
			]);
			if(!empty($certification)){
				$certification->id 					= intval($certification->id);
				$certification->user_id 			= intval($certification->user_id);
				$certification->investor 			= intval($certification->investor);
				$certification->status 				= intval($certification->status);
				$certification->certification_id 	= intval($certification->certification_id);
                $certification->certificate_status = (int) $certification->certificate_status;
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
                    if ($rs && $info->certification_id != CERTIFICATION_REPAYMENT_CAPACITY)
                    {
						$this->CI->notification_lib->certification($info->user_id,$info->investor,$certification['name'],1);
					}

					return $rs;
				}
			}
		}
		return false;
	}

    /**
     * 驗證推薦碼申請
     * @param $info: 徵信項目
     * @param $failed: 徵信項目是否失敗
     * @return false
     */
    public function verify_promote_code($info, $failed): bool
    {
        $this->CI->load->model('user/user_qrcode_model');
        $this->CI->load->model('user/qrcode_setting_model');
        $this->CI->load->library('qrcode_lib');
        $promoteCode = $this->CI->user_qrcode_model->get_by(['user_id' => $info->user_id, 'status' => [
            PROMOTE_STATUS_PENDING_TO_VERIFY,
            PROMOTE_STATUS_CAN_SIGN_CONTRACT
        ]]);
        if ( ! isset($promoteCode))
        {
            return FALSE;
        }

        $company = $this->CI->qrcode_lib->is_company($promoteCode->alias);
        if ($company)
        {
            $promote_cert_list = $this->CI->config->item('promote_code_certs_company');
        }
        else
        {
            $promote_cert_list = $this->CI->config->item('promote_code_certs');
        }

        if (in_array($info->certification_id, $promote_cert_list) || empty($promote_cert_list))
        {
            $this->CI->load->model('user/user_certification_model');
            $this->CI->load->library('Notification_lib');
            if ($failed)
            {
                $user_qrcode_update_param = [
                    'status' => PROMOTE_STATUS_PENDING_TO_SENT,
                    'sub_status' => PROMOTE_SUB_STATUS_DEFAULT,
                ];
                $this->CI->user_qrcode_model->update_by(['id' => $promoteCode->id], $user_qrcode_update_param);
                $this->CI->notification_lib->certification($info->user_id, $info->investor, "推薦有賞", CERTIFICATION_STATUS_FAILED);
                // 寫 log
                $this->CI->load->model('log/log_user_qrcode_model');
                $user_qrcode_update_param['user_qrcode_id'] = $promoteCode->id;
                $this->CI->log_user_qrcode_model->insert_log($user_qrcode_update_param);
                $this->CI->load->model('user/user_qrcode_apply_model');
                $apply_info = $this->CI->user_qrcode_apply_model->get_by(['user_qrcode_id' => $promoteCode->id, 'status != ' => PROMOTE_REVIEW_STATUS_WITHDRAW]);
                if ($apply_info){
                    $this->CI->user_qrcode_apply_model->update_by(['id' => $apply_info->id], ['status' => PROMOTE_REVIEW_STATUS_WITHDRAW]);
                }
            }
            else
            {
                $param = array(
                    'user_id' => $info->user_id,
                    'certification_id' => $promote_cert_list,
                    'investor' => $info->investor,
                    'status' => [CERTIFICATION_STATUS_SUCCEED]
                );
                $certList = $this->CI->user_certification_model->order_by('created_at', 'desc')->get_many_by($param);
                $certifications = array_reduce($certList, function ($list, $item) {
                    if ( ! isset($list[$item->certification_id]))
                        $list[$item->certification_id] = $item;
                    return $list;
                }, []);

                if (count($certifications) != count($promote_cert_list))
                {
                    return FALSE;
                }

                $this->CI->load->library('qrcode_lib');
                if ($this->CI->qrcode_lib->is_appointed_type($promoteCode->alias))
                {
                    // 特約通路商需等合約審核過才可以通過
                    $this->CI->load->model('user/user_qrcode_apply_model');
                    $apply_info = $this->CI->user_qrcode_apply_model->get_by(['user_qrcode_id' => $promoteCode->id, 'status != ' => PROMOTE_REVIEW_STATUS_WITHDRAW]);
                    if ( ! isset($apply_info) || $apply_info->status != PROMOTE_REVIEW_STATUS_SUCCESS)
                    {
                        return FALSE;
                    }
                }

                $settings = json_decode($promoteCode->settings, TRUE);
                $settings['certification_id'] = array_column($certifications, 'id');
                $user_qrcode_update_param = [
                    'settings' => json_encode($settings),
                    'status' => PROMOTE_STATUS_AVAILABLE,
                ];
                $this->CI->user_qrcode_model->update_by(['id' => $promoteCode->id], $user_qrcode_update_param);
                // 寫 log
                $this->CI->load->model('log/log_user_qrcode_model');
                $user_qrcode_update_param['user_qrcode_id'] = $promoteCode->id;
                $this->CI->log_user_qrcode_model->insert_log($user_qrcode_update_param);

                $this->CI->load->library('contract_lib');
                $raw_contract = $this->CI->contract_lib->raw_contract($promoteCode->contract_id);
                if (isset($raw_contract))
                {
                    $this->CI->load->library('qrcode_lib');

                    // 取得原合約的簽約時間
                    $raw_contract_content = json_decode($raw_contract->content, TRUE);
                    $origin_contract_date = '';
                    if (isset($raw_contract_content[1]) && isset($raw_contract_content[2]) && isset($raw_contract_content[3]))
                    {
                        $origin_contract_date = $raw_contract_content[1] . '-' . $raw_contract_content[2] . '-' . $raw_contract_content[3];
                    }

                    if ( ! $company)
                    {
                        $content = json_decode($certifications[CERTIFICATION_IDENTITY]->content, TRUE);
                        $name = $content['name'] ?? '';
                        $id_number = $content['id_number'] ?? '';
                        $address = $content['address'] ?? '';
                    }
                    else
                    {
                        // TODO: 應該要撈取當初變卡的公司名稱，因為法人名字有可能更改
                        $this->CI->load->model('user/judicial_person_model');
                        $judicial_person_info = $this->CI->judicial_person_model->get_by(['company_user_id' => $info->user_id]);
                        $name = $judicial_person_info->company ?? '';
                        $address = $judicial_person_info->cooperation_address ?? '';
                        $id_number = $judicial_person_info->tax_id ?? '';
                    }
                    $contract_type = $this->CI->qrcode_lib->get_contract_type_by_alias($promoteCode->alias);
                    $contract = $this->CI->qrcode_lib->get_contract_format_content($contract_type,
                        $name, $id_number, $address, $settings, $origin_contract_date);

                    if ( ! empty($contract))
                    {
                        $this->CI->contract_lib->update_contract($promoteCode->contract_id, $contract);
                    }
                }

                // 將銀行帳號改為待驗證
                $this->CI->load->model('user/user_bankaccount_model');
                $bank_account = $this->CI->user_bankaccount_model->get_by([
                    'status' => 1,
                    'investor' => $info->investor,
                    'verify' => 0,
                    'user_id' => $info->user_id
                ]);
                if ($bank_account)
                {
                    $bankaccount_info = ['verify' => 2];
                    $this->CI->user_bankaccount_model->update($bank_account->id, $bankaccount_info);

                    // 寫 Log
                    $this->CI->load->library('user_bankaccount_lib');
                    $this->CI->user_bankaccount_lib->insert_change_log($bank_account->id, $bankaccount_info);
                }

                $this->CI->notification_lib->certification($info->user_id, $info->investor, "推薦有賞", CERTIFICATION_STATUS_SUCCEED);
            }
            return TRUE;

        }
        return FALSE;
    }

	public function verify($info){
        if ($info &&
            $info->status != CERTIFICATION_STATUS_SUCCEED &&
            array_key_exists($info->certification_id, $this->certification))
        {
			$certification 	= $this->certification[$info->certification_id];
			$method			= $certification['alias'].'_verify';

            $cert = Certification_factory::get_instance_by_model_resource($info);
            if (isset($cert))
            { // 新的認證項驗證架構，非所有認證項都有實例化
                return $cert->verify();
            }
            else
            { // 舊的認證項驗證架構
                if (method_exists($this, $method))
                {
                    $rs = $this->$method($info);
                }
                else
                {
                    $rs = $this->CI->user_certification_model->update($info->id, array(
                        'status' => CERTIFICATION_STATUS_PENDING_TO_REVIEW,
                    ));
                }
            }
			return $rs;
		}
		return false;
	}

	public function set_failed($id,$fail='',$sys_check=false, $expire_timestamp = false){
		if($id){
			$info = $this->CI->user_certification_model->get($id);
            if ($info && $info->status != CERTIFICATION_STATUS_FAILED)
            {
                $info->content = isJson($info->content) ? json_decode($info->content, TRUE) : [];
                $info->remark = json_decode($info->remark, TRUE);
                $info->remark = is_array($info->remark) ? $info->remark : [];
                $info->remark['fail'] = $fail;
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
                        foreach ($targets as $value)
                        {
                            $this->CI->target_lib->withdraw_target_to_unapproved($value, 0, 0, $sys_check);
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

                    if (isset($certification['show']) && $certification['show'] != FALSE)
                    {
                        $this->CI->notification_lib->certification($info->user_id, $info->investor, $certification['name'], CERTIFICATION_STATUS_FAILED, $fail);
                    }

                    // 驗證推薦碼失敗
                    $this->verify_promote_code($info, TRUE);
				}
				return $rs;
			}
		}
		return false;
	}

	public function set_failed_for_recheck($id,$fail='',$sys_check=false, $expire_timestamp = false){
		if($id){
			$info = $this->CI->user_certification_model->get($id);
			if($info && $info->status != 2){
				$info->content 			= json_decode($info->content,true);
				$info->remark           = $info->remark!=''?json_decode($info->remark,true):[];
				$info->remark['fail'] 	= $fail;
				$certification 	= $this->certification[$info->certification_id];
				$param = [
					'status'    => 2,
					'sys_check' => ($sys_check==true?1:0)
					,
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
                        foreach ($targets as $value)
                        {
                            $this->CI->target_lib->withdraw_target_to_unapproved($value, 0, 0, $sys_check);
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

					$this->CI->notification_lib->recheck_certification($info->user_id,$info->investor,$fail);
				}
				return $rs;
			}
		}
		return false;
	}

    public function realname_verify($info = [])
    {
        if (is_array($info))
        {
            $info = json_decode(json_encode($info));
        }

        $user_id = $info->user_id;
        $cer_id = $info->id;

        $msg = ''; // 最後放入 $remark['error']
        $ocr = []; // 最後放入 $remark['OCR']

        $remark = [
            'error' => '', // 放 $msg
            'OCR' => [], // 放 $ocr

            // Azure 新增項目
            'face' => [], // [float,float]
            'face_flag' => [], // [bool, bool]
            'person_count' => 0,
            'front_count' => 0,

            // face++ 新增項目
            'faceplus' => [], // [float,float]
            'faceplus_data' => [], // [array, array]
            'face_count' => [
                'person_count' => 0,
                'front_count' => 0,
            ],

            // face8 新增項目
            'face8' => [], // ['count'=>[int, int], 'score'=>[float, float], 'liveness'=>[array, float]]
        ];

        // default return data
        $return_data = [
            'remark' => $remark,
            'content' => $info->content,
            'risVerified' => FALSE, // 勾稽戶役政 API
            'risVerificationFailed' => TRUE, // 勾稽戶役政 API
            'ocrCheckFailed' => FALSE,
        ];

        $content = json_decode($info->content, TRUE);
        if ( ! is_array($content) || empty($content))
        {
            $return_data['remark']['error'] = '使用者資料解析發生錯誤.<br/>';
            return $return_data;
        }

        $return_data['content'] = $content;
        $image_types = ['front_image', 'back_image', 'healthcard_image', 'person_image'];
        $images = [];

        $client = new GuzzleHttp\Client();
        try
        {
            foreach ($image_types as $key => $type)
            {
                if (empty($content[$type]))
                {
                    continue;
                }

                $res = $client->request('GET', $content[$type]);
                $body = $res->getBody()->getContents();
                $images[] = base64_encode($body);
            }
        }
        catch (Exception $e)
        {
            $return_data['remark']['error'] = '使用者的圖片無法取得' . (count($image_types) - count($images)) . '筆，無法進行實名驗證.<br/>';
            return $return_data;
        }

        if (count($images) !== count($image_types))
        {
            $return_data['remark']['error'] = '使用者的圖片資料不足' . count($image_types) . '筆，無法進行實名驗證.<br/>';
            return $return_data;
        }

        // 成功產出 4 張圖片資料
        $send_data['img_base64_list'] = $images;

        $this->CI->load->library('Ocr2_lib', [
            'user_id' => $user_id,
            'cer_id' => $cer_id,
        ]);

        // 進行 新版 ocr 身份辨識
        $ocr_result = $this->CI->ocr2_lib->identity_verification($send_data);
        if (empty($ocr_result))
        {
            $return_data['remark']['error'] = 'OCR 沒有在正常時間內回應，無法進行實名驗證.<br/>';
            return $return_data;
        }

        // 補上 OCR 辨識結果的錯誤訊息
        if ( ! $ocr_result['infoValidation']['id_card']['is_valid'])
        {
            $msg .= $ocr_result['infoValidation']['id_card']['msg'] . '<br/>';
        }

        if ( ! $ocr_result['infoValidation']['is_match'])
        {
            $msg .= $ocr_result['infoValidation']['msg'] . '<br/>';
        }

        if (empty($content['name']) || empty($ocr_result['ocr']['id_card']['name'])
            || $ocr_result['ocr']['id_card']['name'] != $content['name'])
        {
            $msg .= '[姓名比對] 自填姓名與身分證OCR姓名不符<br/>';
        }

        // 身份證＆健保卡的辨識資料重組
        $id_card_days = $this->CI->ocr2_lib->combine_ymd($ocr_result['ocr']['id_card']);
        $health_card_days = $this->CI->ocr2_lib->combine_ymd($ocr_result['ocr']['health_card']);
        $issue_site = $this->CI->ocr2_lib->transfer_issue_site($ocr_result['ocr']['id_card']['issue_site_id']);
        $apply_code = $this->CI->ocr2_lib->transfer_apply_code($ocr_result['ocr']['id_card']['apply_code_int']);
        $gender = $this->CI->ocr2_lib->get_gender_from_id_number($ocr_result['ocr']['id_card']['person_id']);

        $ocr = [
            'name' => $ocr_result['ocr']['id_card']['name'],
            'birthday' => $id_card_days['birth'],
            'id_card_date' => $id_card_days['apply'],
            'id_card_place' => $issue_site,
            'issueType' => $apply_code,
            'id_number' => $ocr_result['ocr']['id_card']['person_id'],
            'gender' => $gender,
            'father' => $ocr_result['ocr']['id_card_back']['father_name'],
            'mother' => $ocr_result['ocr']['id_card_back']['mother_name'],
            'spouse' => $ocr_result['ocr']['id_card_back']['spouse_name'],
            'military_service' => $ocr_result['ocr']['id_card_back']['military'],
            'born' => $ocr_result['ocr']['id_card_back']['birth_address'],
            'address' => $ocr_result['ocr']['id_card_back']['residence_address'],
            'gnumber' => $ocr_result['ocr']['id_card_back']['serial_code'],
            'healthcard_name' => $ocr_result['ocr']['health_card']['name'],
            'healthcard_birthday' => $health_card_days['birth'],
            'healthcard_id_number' => $ocr_result['ocr']['health_card']['person_id'],
            'healthcard_number' => $ocr_result['ocr']['health_card']['code_str'],
        ];

        // 圖片人臉數量判斷
        $face_analysis_system = ['azure', 'face8', 'faceplusplus'];
        foreach ($face_analysis_system as $key => $company)
        {
            if ( ! $ocr_result['faceValidation'][$company]['id_card']['is_face_count_valid'])
            {
                $msg .= '[' . $company . '] 身份證人臉數量不足.<br/>';
            }

            if ( ! $ocr_result['faceValidation'][$company]['hold_card_selfie']['is_face_count_valid'])
            {
                $msg .= '[' . $company . '] 持證自拍人臉數量不足.<br/>';
            }
        }

        // 身份證&持證自拍的臉部比對結果
        // face++ 有 OCR 的資料
        $compare_id_card_faces = $ocr_result['faceComparison']['faceplusplus']['id_card_faces_compare'];
        if ( ! $compare_id_card_faces['is_valid'])
        {
            $msg .= '[face++] ' . $compare_id_card_faces['msg'] . '<br/>';
        }
        $compare_id_card_vs_person = $ocr_result['faceComparison']['faceplusplus']['id_card_vs_person_faces_compare'];
        if ( ! $compare_id_card_vs_person['is_valid'])
        {
            $msg .= '[face++] ' . $compare_id_card_vs_person['msg'] . '<br/>';
        }

        // Azure 人臉比對，以後等 OCR 做完再直接串
        $azure_compare = [
            'face' => [], // [0, 0],
            'face_flag' => [], // [FALSE, FALSE],
        ];
        if (isset($ocr_result['faceComparison']['azure']['id_card_faces_compare']['score']) &&
            isset($ocr_result['faceComparison']['azure']['id_card_faces_compare']['is_valid'])
        )
        {
            $azure_compare['face'][] = $ocr_result['faceComparison']['azure']['id_card_faces_compare']['score'];
            $azure_compare['face_flag'][] = $ocr_result['faceComparison']['azure']['id_card_faces_compare']['is_valid'];
            if ($ocr_result['faceComparison']['azure']['id_card_faces_compare']['is_valid'] === FALSE)
            {
                $msg .= '[Azure]' . $ocr_result['faceComparison']['azure']['id_card_faces_compare']['msg'] . '<br/>';
            }
        }
        if (isset($ocr_result['faceComparison']['azure']['id_card_vs_person_faces_compare']['score']) &&
            isset($ocr_result['faceComparison']['azure']['id_card_vs_person_faces_compare']['is_valid'])
        )
        {
            $azure_compare['face'][] = $ocr_result['faceComparison']['azure']['id_card_vs_person_faces_compare']['score'];
            $azure_compare['face_flag'][] = $ocr_result['faceComparison']['azure']['id_card_vs_person_faces_compare']['is_valid'];
            if ($ocr_result['faceComparison']['azure']['id_card_vs_person_faces_compare']['is_valid'] === FALSE)
            {
                $msg .= '[Azure]' . $ocr_result['faceComparison']['azure']['id_card_vs_person_faces_compare']['msg'] . '<br/>';
            }
        }

        // face8 人臉比對，以後等 OCR 做完再直接串
        $face8_compare = [
            'score' => ['n/a', 'n/a'],
        ];

        // 取回資料後解析回傳內容，將資料填入回傳格式中
        $remark = [
            'error' => '',

            // Azure 新增項目
            'face' => $azure_compare['face'],
            'face_flag' => $azure_compare['face_flag'],
            'person_count' => $ocr_result['faceValidation']['azure']['hold_card_selfie']['face_count'],
            'front_count' => $ocr_result['faceValidation']['azure']['id_card']['face_count'],

            // face++ 新增項目
            'faceplus' => [
                $compare_id_card_vs_person['score'],
                $compare_id_card_faces['score'],
            ],
            'faceplus_data' => [
                [
                    'gender' => [
                        'value' => $ocr_result['faceValidation']['faceplusplus']['hold_card_selfie']['face_list'][0]['gender'] ?? '',
                    ],
                    'age' => [
                        'value' => $ocr_result['faceValidation']['faceplusplus']['hold_card_selfie']['face_list'][0]['age'] ?? '',
                    ],
                ],
                [
                    'gender' => [
                        'value' => $ocr_result['faceValidation']['faceplusplus']['hold_card_selfie']['face_list'][1]['gender'] ?? '',
                    ],
                    'age' => [
                        'value' => $ocr_result['faceValidation']['faceplusplus']['hold_card_selfie']['face_list'][1]['age'] ?? '',
                    ],
                ],
            ],
            'face_count' => [
                'person_count' => $ocr_result['faceValidation']['faceplusplus']['hold_card_selfie']['face_count'],
                'front_count' => $ocr_result['faceValidation']['faceplusplus']['id_card']['face_count'],
            ],

            // face8 新增項目
            'face8' => [
                'count' => [
                    $ocr_result['faceValidation']['face8']['hold_card_selfie']['face_count'],
                    $ocr_result['faceValidation']['face8']['id_card']['face_count'],
                ],
                'score' => $face8_compare['score'],
                'liveness' => [
                    [
                        $ocr_result['faceValidation']['face8']['hold_card_selfie']['face_list'][0]['liveness'] ?? 'n/a',
                        $ocr_result['faceValidation']['face8']['hold_card_selfie']['face_list'][1]['liveness'] ?? 'n/a',
                    ],
                    $ocr_result['faceValidation']['face8']['id_card']['face_list'][0]['liveness'] ?? 'n/a',
                ],
            ]
        ];

        // 僅保留 勾稽戶役政 API
        $verify_result = $this->verify_id_card_info($info->id, $content, $msg, $ocr);

        // 確認有無配偶
        $content['hasSpouse'] = ! empty($ocr['spouse']);

        $remark['error'] = $msg;
        $remark['OCR']   = $ocr;
        $return_data['remark'] = $remark;
        $return_data['content'] = $content;
        $return_data['risVerified'] = $verify_result[0];
        $return_data['risVerificationFailed'] = $verify_result[1];
        return $return_data;
	}

    /**
     * Use 戶役政 API to verify ID card info.
     * @param $user_certification_id : id of user_certification with certification_id == CERTIFICATION_IDENTITY
     * @param $identity_content : array version of the corresponding user_certification.content
     * @return [$risVerified, $risVerificationFailed, $param['checkIdCardApplyFormat']] : [TRUE, TRUE, Message] means [呼叫戶役政 API 成功, 身分證資料有誤, 戶役政回應的內容]
     */
    public function verify_id_card_info($user_certification_id, array &$identity_content, &$error_message, $ocr_info=[]): array
    {
        $risVerified = false;
        $risVerificationFailed = true;
        if ( ! isset($identity_content['id_number']) || ! isset($identity_content['name']) || ! isset($identity_content['birthday']))
        {
            return [$risVerified, $risVerificationFailed];
        }
        $this->CI->load->model('log/log_integration_model');
        $logRs = $this->CI->log_integration_model->order_by('id', 'DESC')->limit(1)->get_all();
        if(!empty($logRs)) {
            $logRs = $logRs[0];
            $resultUserId = substr($logRs->api_user_id, 0, -3) .
                str_pad(strval((intval(substr($logRs->api_user_id, -3)) + 1) % 1000), 3, 0, STR_PAD_LEFT);
        }else
            $resultUserId = 'realname_001';

        $this->CI->load->library('id_card_lib');
        $requestPersonId = isset($identity_content['id_number']) ? $identity_content['id_number'] : '';
        preg_match('/(初|補|換)發$/', $identity_content['id_card_place'], $requestApplyCode);
        if ($ocr_info && empty($requestApplyCode))
        {
            preg_match('/(初|補|換)發$/', $ocr_info['issueType'], $requestApplyCode);
        }
        else if (empty($requestApplyCode))
        {
            return [$risVerified, $risVerificationFailed];
        }
        $requestApplyCode = isset($requestApplyCode[0]) ? $requestApplyCode[0] : '';
        $reqestApplyYyymmdd = $identity_content['id_card_date'];
        if ($ocr_info)
        {
            preg_match('/(*UTF8)((\W{1}|新北)市|\W{1}縣)|(連江|金門)/', $ocr_info['id_card_place'], $requestIssueSiteId);
        }
        if (empty($requestIssueSiteId))
        {
            preg_match('/(*UTF8)(([^\(\)]{1,2}|新北)市|[^\(\)]{1,2}縣)|(連江|金門)/', $identity_content['id_card_place'], $requestIssueSiteId);
        }
        $requestIssueSiteId = isset($requestIssueSiteId[0]) ? $requestIssueSiteId[0] : '';
        $result = $this->CI->id_card_lib->send_request($requestPersonId, $requestApplyCode, $reqestApplyYyymmdd, $requestIssueSiteId, $resultUserId);

        $current_time = (new DateTime())->format('Y-m-d H:i:s.u');
        $re_verify = isset($identity_content['id_card_api']);
        if ($re_verify)
        {
            // Put current data into history log.
            if ( ! isset($identity_content['id_card_api_history']))
            {
                // The value will be associative array with key: replaced time, value: copy of the current $identity_content['id_card_api'].
                $identity_content['id_card_api_history'] = [];
            }
            $identity_content['id_card_api_history'][$current_time] = $identity_content['id_card_api'];
        }

        if ( ! $result)
        {
            $identity_content['id_card_api'] = 'no response';
            return [$risVerified, $risVerificationFailed];
        }
        $param = [
            'user_certification_id' => $user_certification_id,
            'api_user_id' => $resultUserId,
            'httpCode' => $result['status'],
            'rdCode' => '',
            'rdMessage' => '',
            'checkIdCardApply' => 0,
            'checkIdCardApplyFormat' => '',
        ];

        $msg_prefix = $re_verify ? "[{$current_time} 重新執行爬蟲]" : '';
        if ($result['status'] != 200)
        {
            $identity_content['id_card_api'] = [
                'status' => $result['status'],
                'error' => $result['response']['response']['checkIdCardApplyFormat']
            ];
            $param['checkIdCardApplyFormat'] = $result['response']['response']['checkIdCardApplyFormat'];
            $error_message .= "{$msg_prefix}[戶役政]".$param['checkIdCardApplyFormat']."<br/>";
        }
        else
        {
            $param['rdCode'] = $result['response']['response']['rowData']['rdCode'];
            $param['rdMessage'] = $result['response']['response']['rowData']['rdMessage'];
            if (isset($result['response']['response']['rowData']['responseData']['checkIdCardApply'])) {
                $param['checkIdCardApply'] = $result['response']['response']['rowData']['responseData']['checkIdCardApply'];
                $param['checkIdCardApplyFormat'] = $result['response']['response']['checkIdCardApplyFormat'];

                $risVerified = TRUE;
                if ($result['response']['response']['rowData']['responseData']['checkIdCardApply'] != 1) {
                    $error_message .= "{$msg_prefix}[戶役政]".$param['checkIdCardApplyFormat']."<br/>";
                    $risVerificationFailed = TRUE;
                } else {
                    $risVerificationFailed = FALSE;
                }
            }
            $identity_content['id_card_api'] = $result['response'];
        }
        $this->CI->log_integration_model->insert($param);
        return [$risVerified, $risVerificationFailed, $param['checkIdCardApplyFormat']];
    }

    // 實名認證
    public function identity_verify($info = [])
    {
        $cert = Certification_factory::get_instance_by_model_resource($info);
        return $cert->verify();
    }

    public function student_verify($info = array())
    {
        $user_certification = $this->get_certification_info($info->user_id, CERTIFICATION_IDENTITY, $info->investor);
        if ($user_certification == FALSE || $user_certification->status != CERTIFICATION_STATUS_SUCCEED)
        {
            return FALSE;
        }

        if ($info && $info->status == CERTIFICATION_STATUS_PENDING_TO_VALIDATE && $info->certification_id == CERTIFICATION_STUDENT)
        {
            $content = json_decode($info->content, TRUE);
            $verifiedResult = new StudentCertificationResult(CERTIFICATION_STATUS_SUCCEED);
            $sys_check = SYSTEM_CHECK;
            $content['meta'] ?? [];

            $this->CI->load->library('scraper/sip_lib');
            if ( ! empty($content['school']) && ! empty($content['sip_account']) && ! empty($content['sip_password']))
            {
                $sip_log = $this->CI->sip_lib->getLoginLog($content['school'], $content['sip_account']);
                // 判斷login_log是否有回應
                if ($sip_log && isset($sip_log['status']))
                {
                    if ($sip_log['status'] == SCRAPER_STATUS_SUCCESS)
                    {
                        // login執行完成
                        if ($sip_log['response']['status'] == 'finished')
                        {
                            // 判斷 SIP 帳號密碼是否正確
                            if (isset($sip_log['response']['isRight']) && $sip_log['response']['isRight'] == TRUE)
                            {
                                // 判斷 SIP 是否成功登入
                                if (isset($sip_log['response']['isLogin']) && $sip_log['response']['isLogin'] == TRUE)
                                {
                                    // 判斷deep_log是否有回應
                                    $deep_log = $this->CI->sip_lib->getDeepLog($content['school'], $content['sip_account']);
                                    if ($deep_log && isset($deep_log['status']) && isset($deep_log['response']['status']))
                                    {
                                        if ($deep_log['status'] == SCRAPER_STATUS_SUCCESS)
                                        {
                                            // 深度爬蟲任務完成
                                            if ($deep_log['response']['status'] == 'finished')
                                            {
                                                $sip_data                      = $this->CI->sip_lib->getDeepData($content['school'], $content['sip_account']);
                                                $content['sip_data']           = $sip_data['response'] ?? [];
                                                $content['meta']['last_grade'] = $sip_data['response']['result']['latestGrades'] ?? '';
                                                $user_info = ! empty($user_certification->content) ? $user_certification->content : [];
                                                // 判斷是否有資料
                                                if ($sip_data && isset($sip_data['response']['result']))
                                                {
                                                    $name = $user_info['name']  ?? '';
                                                    $id_number = $user_info['id_number'] ?? '';
                                                    $sip_name = $sip_data['response']['result']['name'] ?? '';
                                                    $sip_id_number = $sip_data['response']['result']['idNumber'] ?? '';
                                                    if ($name != $sip_name)
                                                    {
                                                        $verifiedResult->addMessage("SIP姓名與實名認證資訊不同:1.實名認證姓名=\"{$name}\"2.SIP姓名=\"{$sip_name}\"", CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                                                    }
                                                    if ($id_number != $sip_id_number)
                                                    {
                                                        $verifiedResult->addMessage("SIP身分證與實名認證資訊不同1.實名認證身分證=\"{$id_number}\"2.SIP身分證=\"{$sip_id_number}\"", CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                                                    }
                                                }
                                                else
                                                {
                                                    $verifiedResult->addMessage('SIP爬蟲DeepScraper沒有資料，請人工進行驗證', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                                                }
                                            }
                                            else if ($deep_log['response']['status'] == 'failure')
                                            {
                                                $verifiedResult->addMessage('SIP爬蟲DeepScraper失敗，請人工進行驗證', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                                            }
                                            else if ($deep_log['response']['status'] == 'deep scraping' || $deep_log['response']['status'] == 'logging in')
                                            {
                                                return FALSE;
                                            }
                                            else
                                            {
                                                $verifiedResult->addMessage('SIP爬蟲DeepLog status回應: ' . $sip_log['response']['status'] . '，請洽工程師', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                                            }
                                        }
                                        else if ($deep_log['status'] == SCRAPER_STATUS_NO_CONTENT)
                                        {
                                            $this->CI->sip_lib->requestDeep($content['school'], $content['sip_account'], $content['sip_password']);
                                            return FALSE;
                                        }
                                    }
                                }
                                else
                                {
                                    // SIP 帳號密碼判定正確，但登入爬取過程中出現異常
                                    $verifiedResult->addMessage('SIP帳號密碼正確，爬蟲執行失敗，請確認此學校狀態、以及是否為在學中帳號，請人工進行驗證', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                                }
                            }
                            else
                            {
                                $university_status_response = $this->CI->sip_lib->getUniversityModel($content['school']);
                                $university_status = isset($university_status_response['response']) ? $university_status_response['response']['status'] : '';
                                
                                $status_mapping = [
                                    SCRAPER_SIP_RECAPTCHA => '驗證碼問題',
                                    SCRAPER_SIP_NORMALLY => '正常狀態',
                                    SCRAPER_SIP_BLOCK => '黑名單學校',
                                    SCRAPER_SIP_SERVER_ERROR => 'server問題',
                                    SCRAPER_SIP_VPN => 'VPN相關問題',
                                    SCRAPER_SIP_CHANGE_PWD => '要求改密碼',
                                    SCRAPER_SIP_FILL_QUEST => '問卷問題',
                                    SCRAPER_SIP_UNSTABLE => '不穩定 有時有未知異常',
                                ];

                                $verifiedResult->addMessage('SIP登入失敗，學校狀態: ' .
                                    $status_mapping[$university_status] .
                                    '，請人工進行驗證', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                            }
                        }
                        else if ($sip_log['response']['status'] == 'failure')
                        {
                            $verifiedResult->addMessage('SIP登入執行失敗，請人工進行驗證', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                        }
                        else if ($sip_log['response']['status'] == 'university_not_found')
                        {
                            $verifiedResult->addMessage('SIP學校不在清單內，請人工進行驗證', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                        }
                        else if ($sip_log['response']['status'] == 'university_not_enabled')
                        {
                            $verifiedResult->addMessage('SIP學校為黑名單，請人工進行驗證', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                        }
                        else if ($sip_log['response']['status'] == 'university_not_crawlable')
                        {
                            $verifiedResult->addMessage('SIP學校無法爬取，請人工進行驗證', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                        }
                        // 爬蟲未跑完
                        else if ($sip_log['response']['status'] == 'started' || $sip_log['response']['status'] == 'retry' || $sip_log['response']['status'] == 'requested')
                        {
                            return FALSE;
                        }
                        else
                        {
                            $verifiedResult->addMessage('SIP爬蟲LoginLog status回應: ' . $sip_log['response']['status'] . '，請洽工程師', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                        }
                    }
                    else if ($sip_log['status'] == SCRAPER_STATUS_NO_CONTENT)
                    {
                        $this->CI->sip_lib->requestDeep($content['school'], $content['sip_account'], $content['sip_password']);
                        return FALSE;
                    }
                    else if ($sip_log['status'] == SCRAPER_STATUS_CREATED)
                    {
                        return FALSE;
                    }
                    else
                    {
                        $verifiedResult->addMessage('SIP爬蟲LoginLog http回應: ' . $sip_log['status'] . '，請洽工程師', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                    }
                }
                else
                {
                    $verifiedResult->addMessage('SIP爬蟲LoginLog無回應，請洽工程師', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                }
            }
            else
            {
                $verifiedResult->addMessage('SIP填入資訊為空', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
            }

            // 預計畢業時間
            if (isset($content['graduate_date']) && ! empty($content['graduate_date']))
            {
                if (preg_match('/^民國[0-9]{2,3}(年|-|\/)(0?[1-9]|1[012])(月|-|\/)(0?[1-9]|[12][0-9]|3[01])(日?)$/u', $content['graduate_date']))
                {
                    $graduate_date = preg_replace('/民國/', '', $content['graduate_date']);
                    $this->CI->load->library('mapping/time');
                    $graduate_date = $this->CI->time->ROCDateToUnixTimestamp($graduate_date);
                    // 是否畢業
                    if (is_numeric($graduate_date) && $graduate_date <= strtotime(date('Y-m-d', $info->created_at)))
                    {
                        // 是否超過六年
                        if ($graduate_date <= strtotime(date('Y-m-d', $info->created_at) . '-6 years'))
                        {
                            $verifiedResult->addMessage('已畢業，請申請上班族貸', CERTIFICATION_STATUS_FAILED, MessageDisplay::Client);
                        }
                    }
                }
                else
                {
                    $verifiedResult->addMessage('預計畢業時間格式錯誤', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                }
            }
            else
            {
                $verifiedResult->addMessage('預計畢業時間格式錯誤', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
            }

            $status                  = $verifiedResult->getStatus();
            $remark                  = is_array(json_decode($info->remark, TRUE)) ? json_decode($info->remark, TRUE) : [];
            $remark['verify_result'] = isset($remark['verify_result']) ? $remark['verify_result'] : [];
            $remark['verify_result'] = array_merge($remark['verify_result'], $verifiedResult->getAllMessage(MessageDisplay::Backend));

            $this->CI->user_certification_model->update($info->id, array(
                'status'    => $status != CERTIFICATION_STATUS_PENDING_TO_REVIEW ? CERTIFICATION_STATUS_PENDING_TO_VALIDATE : $status,
                'sys_check' => $sys_check,
                'content'   => json_encode($content, JSON_INVALID_UTF8_IGNORE),
                'remark'    => json_encode($remark, JSON_INVALID_UTF8_IGNORE),
            ));

            if ($status == CERTIFICATION_STATUS_SUCCEED)
            {
                $this->set_success($info->id, TRUE);
            }
            else if ($status == CERTIFICATION_STATUS_FAILED)
            {
                $notificationContent = $verifiedResult->getAPPMessage(CERTIFICATION_STATUS_FAILED);
                $this->set_failed($info->id, $notificationContent, $sys_check);
            }

            return TRUE;
        }
        return FALSE;
    }

    // 金融帳號認證
    public function debitcard_verify($info = [])
    {
        $cert = Certification_factory::get_instance_by_model_resource($info);
        return $cert->verify();
    }

    // 社交帳號
    public function social_verify($info = array())
    {
        $cert = Certification_factory::get_instance_by_model_resource($info);
        return $cert->verify();
    }

    // 名校貸社交帳號
    public function social_intelligent_verify($info = array())
    {
        $cert = Certification_factory::get_instance_by_model_resource($info);
        return $cert->verify();
    }

    // 緊急聯絡人
    public function emergency_verify($info = array())
    {
        $cert = Certification_factory::get_instance_by_model_resource($info);
        return $cert->verify();
    }

    // 常用電子信箱
    public function email_verify($info = array())
    {
        $cert = Certification_factory::get_instance_by_model_resource($info);
        return $cert->verify();
    }

    // 收支資訊
    public function financial_verify($info = array())
    {
        $cert = Certification_factory::get_instance_by_model_resource($info);
        return $cert->verify();
    }

    // 最高學歷證明
    public function diploma_verify($info = array())
    {
        $cert = Certification_factory::get_instance_by_model_resource($info);
        return $cert->verify();
    }

    // 個人基本資料
    public function profile_verify($info = array())
    {
        $cert = Certification_factory::get_instance_by_model_resource($info);
        return $cert->verify();
    }

    // 財務訊息資訊
    public function financialWorker_verify($info = array())
    {
        $cert = Certification_factory::get_instance_by_model_resource($info);
        return $cert->verify();
    }

    // 良民證
    public function criminalrecord_verify($info = array())
    {
        $cert = Certification_factory::get_instance_by_model_resource($info);
        return $cert->verify();
    }

	public function governmentauthorities_verify($info = array(), $url=null){
		$info->content = isset($info->content) ? json_decode($info->content,true) : '';
		if($info && $info->certification_id == 1007 && $info->status == 0){
		  $status = 3;
		  $data = [];
		  $group_id = isset($info->content['group_id']) ? $info->content['group_id'] : '';
		  $imageIds[] = $group_id;
		  $count_array =[
		    '1' => 'A',
		    '2' => 'B',
		    '3' => 'C',
		    '4' => 'D',
		    '5' => 'E',
		    '6' => 'F',
		    '7' => 'G',
		  ];

		  // 找不到資料來源，找 ocr 結果
		  if(isset($group_id) && !isset($info->content['result'][$group_id]['origin_type'])){
		    $this->CI->load->library('ocr/report_scan_lib');
		    $batchType = 'amendment_of_registers';
		    $response = $this->CI->report_scan_lib->requestForResult($batchType, $imageIds);

		    if ($response && $response->status == 200) {
		      $response = isset($response->response->amendment_of_register_logs->items[0]) ? $response->response->amendment_of_register_logs->items[0] : '';
		      if($response && $response->status=='finished'){
		        // 變卡ocr資料
		        $data[$group_id]['company_owner'] = isset($response->amendment_of_register->companyInfo->owner) ? $response->amendment_of_register->companyInfo->owner : '';
		        $data[$group_id]['tax_id'] = isset($response->amendment_of_register->companyInfo->taxId) ? $response->amendment_of_register->companyInfo->taxId : '';
		        $data[$group_id]['company_address'] = isset($response->amendment_of_register->companyInfo->address) ? $response->amendment_of_register->companyInfo->address : '';
		        $data[$group_id]['company_name'] = isset($response->amendment_of_register->companyInfo->name) ? $response->amendment_of_register->companyInfo->name : '';
		        $data[$group_id]['capital_amount'] = isset($response->amendment_of_register->companyInfo->amountOfCapital) ? $response->amendment_of_register->companyInfo->amountOfCapital : '';
		        $data[$group_id]['paid_in_capital_amount'] = isset($response->amendment_of_register->companyInfo->paidInCapital) ? $response->amendment_of_register->companyInfo->paidInCapital : '';
		        // to do : 董監事資料待補上?
		      }else{
		      $info->remark = ['找不到變卡ocr資料'];
		    }
		  }}
		  // 使用者校正資料
		  if(isset($info->content['result'][$group_id]['origin_type']) && $info->content['result'][$group_id]['origin_type']=='user_confirm'){
		    $data[$group_id]['company_owner'] = isset($info->content['result'][$group_id]['owner']) ? $info->content['result'][$group_id]['owner'] : '';
			$data[$group_id]['owner_id'] = isset($info->content['result'][$group_id]['owner_id']) ? $info->content['result'][$group_id]['owner_id'] : '';
		    $data[$group_id]['tax_id'] = isset($info->content['result'][$group_id]['tax_id']) ? $info->content['result'][$group_id]['tax_id'] : '';
		    $data[$group_id]['company_address'] = isset($info->content['result'][$group_id]['address']) ? $info->content['result'][$group_id]['address'] : '';
		    $data[$group_id]['company_name'] = isset($info->content['result'][$group_id]['name']) ? $info->content['result'][$group_id]['name'] : '';
		    $data[$group_id]['capital_amount'] = isset($info->content['result'][$group_id]['capital']) ? $info->content['result'][$group_id]['capital'] : '';
		    $data[$group_id]['paid_in_capital_amount'] = isset($info->content['result'][$group_id]['capital']) ? $info->content['result'][$group_id]['capital'] : '';
		    // 董監事
		    for($i=1;$i<=7;$i++){
		      $data[$group_id]["Director{$count_array[$i]}Id"] = isset($info->content['result'][$group_id]["Director{$count_array[$i]}Id"]) ? $info->content['result'][$group_id]["Director{$count_array[$i]}Id"] : '';
		      $data[$group_id]["Director{$count_array[$i]}Name"] = isset($info->content['result'][$group_id]["Director{$count_array[$i]}Name"]) ? $info->content['result'][$group_id]["Director{$count_array[$i]}Name"] : '';
		    }
		  }
		  if($data){
		    // 變卡正確性驗證
		    $this->CI->load->library('verify/data_legalize_lib');
		    $res = $this->CI->data_legalize_lib->legalize_governmentauthorities($info->user_id,$data);
		    // 寫入結果(不論對錯都寫入，方便查驗)
		    $info->remark = $res['error_message'];
				if(empty($res['error_message'])){
					$status = 1;
				}
		    $info->content['error_location'] = $res['error_location'];
		    $info->content['result'][$imageIds[0]] = [
		      'action_user' => 'system',
		      'send_time' => time(),
		      'status' => 1,
		      'tax_id' => isset($data[$group_id]['tax_id']) ? $data[$group_id]['tax_id'] : '',
		      'name' => isset($data[$group_id]['company_name']) ? $data[$group_id]['company_name'] : '',
		      'capital' => isset($data[$group_id]['capital_amount']) ? $data[$group_id]['capital_amount'] : '',
		      'address' => isset($data[$group_id]['company_address']) ? $data[$group_id]['company_address'] : '',
		      'owner' => isset($data[$group_id]['company_owner']) ? $data[$group_id]['company_owner'] : '',
			  'owner_id' => isset($data[$group_id]['owner_id']) ? $data[$group_id]['owner_id'] : '',
		      'company_type' => isset($res['result']['company_type']) ? $res['result']['company_type'] : '',
		    ];
		    for($i=1;$i<=7;$i++){
		      $info->content['result'][$imageIds[0]]["Director{$count_array[$i]}Id"] = isset($data["Director{$count_array[$i]}Id"]) ? $data["Director{$count_array[$i]}Id"] : '';
		      $info->content['result'][$imageIds[0]]["Director{$count_array[$i]}Name"] = isset($data["Director{$count_array[$i]}Name"]) ? $data["Director{$count_array[$i]}Name"] : '';
		    }
		  }
          // 爬蟲資料結果
          $user_info = $this->CI->user_model->get_by(array( 'id' => $info->user_id ));
          if($user_info && !empty($user_info->id_number)){
              $this->CI->load->library('scraper/Findbiz_lib');
              // 確認爬蟲狀態
              $scraper_status = $this->CI->findbiz_lib->getFindBizStatus($user_info->id_number);
              if(! $scraper_status || ! isset($scraper_status->response->result->status) || ($scraper_status->response->result->status != 'failure' && $scraper_status->response->result->status != 'finished') ){
                  // 爬蟲沒打過重打一次
                  if($scraper_status && isset($scraper_status->status) && $scraper_status->status == 204){
                      $this->CI->findbiz_lib->requestFindBizData($user_info->id_number);
                  }
                  return false;
              }
              // 商業司截圖(for新光普匯微企e秒貸)
              $company_image_url = $this->CI->findbiz_lib->getFindBizImage($user_info->id_number, $user_info->id);
              if($company_image_url){
                  $info->content['governmentauthorities_image'][] = $company_image_url;
              }
              // 商業司歷任負責人
              $company_scraper_info = $this->CI->findbiz_lib->getResultByBusinessId($user_info->id_number);
              if($company_scraper_info){
                  $company_user_info = $this->CI->findbiz_lib->searchEachTermOwner($company_scraper_info);
                  if($company_user_info){
                      krsort($company_user_info);
                      $num = 0;
                      foreach($company_user_info as $k=>$v){
                          if($num==0){
                            $info->content['PrOnboardDay'] = $k;
                            $info->content['PrOnboardName'] = $v;
                          }
                          if($num==1){
                            $info->content['ExPrOnboardDay'] = $k;
                        	$info->content['ExPrOnboardName'] = $v;
                          }
                          if($num==2){
                        	$info->content['ExPrOnboardDay2'] = $k;
                        	$info->content['ExPrOnboardName2'] = $v;
                          }
                          if($num==3){
                        	break;
                          }
                        $num++;
                      }
                  }
              }
          }

          $this->CI->user_certification_model->update($info->id, array(
            'status' => 3,
            'sys_check' => 1,
            'content' => json_encode($info->content),
            'remark' => json_encode($info->remark)
          ));
		    if($status == 1){
		      $this->set_success($info->id ,true);
		    }
		    return true;
		  }
		return false;
	}

	public function balancesheet_verify($info = array(), $url=null){

		$info->content = isset($info->content) ? json_decode($info->content,true) : '';
		if($info && $info->certification_id == 1001 && $info->status == 0 && !empty($info->content['balance_sheet_image'])){

			// 資產負債暫時性
			$status = 3;


			$this->CI->user_certification_model->update($info->id, array(
				'status' => $status,
				'sys_check' => 1,
				'content' => json_encode($info->content)
			));
			return true;
		}
		return false;
	}

	public function employeeinsurancelist_verify($info = array(), $url=null){
		// $user_certification	= $this->get_certification_info($info->user_id,1007,$info->investor);
		// if($user_certification==false || $user_certification->status!=1){
		// 	return false;
		// }
		$info->content = isset($info->content) ? json_decode($info->content,true) : [];
		if($info && $info->certification_id == 1017 && $info->status == 0){
			$status = 3;
			$data = [];

            if ( ! empty($info->content['employeeinsurancelist_image']) && isset($info->content['result']) && ! empty($info->content['result']))
            {
                foreach($info->content['result'] as $k=>$v){
    				if(isset($v['origin_type']) && $v['origin_type'] == 'user_confirm'){
    					$imageIds[] = $k;
    					foreach($v as $k1=>$v1){
    						if(preg_match('/NumOfInsuredYM|NumOfInsured/',$k1)){
    							if(preg_match('/NumOfInsuredYM/',$k1)){
    								$k1 = preg_replace('/NumOfInsuredYM|NumOfInsured/','',$k1);
    								$data[$k]['table'][$k1]['yearMonth'] = $v1;
    							}
    							if(preg_match('/NumOfInsured/',$k1)){
    								$k1 = preg_replace('/NumOfInsuredYM|NumOfInsured/','',$k1);
    								$data[$k]['table'][$k1]['insuredCount'] = $v1;
    							}
    						}
    						if($k1 =='company_name'){
    							$data[$k]['company_name'] = isset($v1) ? $v1 : '';
    						}
    						if($k1 =='range'){
    							$data[$k]['range'] = isset($v1) ? $v1 : '';
    						}
    						if($k1 =='report_time'){
    							$data[$k]['report_time'] = isset($v1) ? $v1 : '';
    						}
    					}
    					if(array_key_exists('table',$data[$k])){
    						$data[$k]['table'] = array_values($data[$k]['table']);
    					}
    				}
    				if(! isset($v['origin_type'])){
    					// 找所有圖片ID
    					$this->CI->load->model('log/log_image_model');
    					if(is_array($info->content['employeeinsurancelist_image'])){
    						$imgurl = $info->content['employeeinsurancelist_image'];
    					}else{
    						$imgurl = [$info->content['employeeinsurancelist_image']];
    					}
    					$image_info = $this->CI->log_image_model->get_many_by([
    							'url' => $imgurl,
    					]);
    					if($image_info){
    						foreach($image_info as $v){
    							$imageIds[] = $v->id;
    						}
    					}

    					// 找所有ocr資料
    					$this->CI->load->library('ocr/report_scan_lib');
    					$response = $this->CI->report_scan_lib->requestForResult('insurance_tables', $imageIds);
    					if ($response && $response->status == 200) {
    						$response = isset($response->response->insurance_table_logs->items[0]) ? $response->response->insurance_table_logs->items[0] : [];
    						if($response && $response->status=='finished'){
    							$data[$imageIds[0]]['company_name'] = isset($response->insurance_table->companyInfo) ? $response->insurance_table->companyInfo : '';
    							$data[$imageIds[0]]['range'] = isset($response->insurance_table->insurancePeriod) ? $response->insurance_table->insurancePeriod: '';
    							$data[$imageIds[0]]['report_time'] = isset($response->insurance_table->reportTime) ? $response->insurance_table->reportTime: '';
    							$data[$imageIds[0]]['table'] = isset($response->insurance_table->insuredList) ? $response->insurance_table->insuredList: [];
    						}
    					}
    				}
    			}
            }

			if($data){
				$this->CI->load->library('verify/data_legalize_lib');
				$res = [];//$this->CI->data_legalize_lib->legalize_employeeinsurancelist($info->user_id,$data);

				$info->remark = $res['error_message'];
				$info->content['error_location'][$imageIds[0]] = $res['error_location'];
				$info->content['result'][$imageIds[0]] = [
					'action_user' => 'system',
					'send_time' => time(),
					'status' => 0,
					'company_name' => $res['result']['company_name'],
					'report_time' => $res['result']['report_time'],
					'range' => $res['result']['range'],
					'average' => $res['result']['average'],
					'list' => $res['result']['list'],
				];
				// if(empty($res['error_message'])){
				//   $status = 1;
				// }
			}

			$this->CI->user_certification_model->update($info->id, array(
		    'status' => 3,
		    'sys_check' => 1,
		    'content' => json_encode($info->content),
		    'remark' => json_encode($info->remark)
		  ));
		  if($status == 1){
		    $this->set_success($info->id ,true);
		  }
			return true;
		}
		return false;
	}

    public function judicialguarantee_verify($info = array(), $url=null){
        $cert = Certification_factory::get_instance_by_id($info->id);
        if (isset($cert))
        {
            return $cert->verify();
        }
        if($info && $info->certification_id == CERTIFICATION_JUDICIALGUARANTEE && $info->status == 0){
            $info->content = isset($info->content) ? json_decode($info->content,true) : [];
            $this->CI->load->library('Judicialperson_lib');
            $res = $this->CI->judicialperson_lib->script_check_judicial_person_face($info);
            if($res){
                $info->content['judicialPersonId'] = $res['judicialPersonId'];
                $info->content['compareResult'] = $res['compareResult'];
                $this->CI->user_certification_model->update($info->id, array(
                    'status' => $res['status'],
                    'sys_check' => 1,
                    'content' => json_encode($info->content),
                ));
            }

            return true;
        }
        return false;
    }

    public function profilejudicial_verify($info = array(), $url=null){
        if($info && $info->certification_id == CERTIFICATION_PROFILEJUDICIAL && $info->status == 0){
            // $this->CI->user_certification_model->update($info->id, array(
            //     'status' => 1,
            //     'sys_check' => 1,
            // ));

			// 產生公司資料表 pdf

			// if(){
			// 	$status = 1;
			// }else{
			// 	$status = 3;
			// }
			$this->CI->user_certification_model->update($info->id, array(
			    'status' => 3,
			    'sys_check' => 1,
			  ));

            // $this->set_success($info->id, true);
            return true;
        }
        return false;
    }

	// to do : 待加入並合併普匯微企e秒貸
	public function investigation_verify($info = array(), $url=null): bool
    {
        $cert = Certification_factory::get_instance_by_model_resource($info);
        return $cert->verify();
	}

	// 聯徵+A11
	public function investigationa11_verify($info = array(), $url=null)
	{
		$user_certification	= $this->get_certification_info($info->user_id,1,$info->investor);
		if($user_certification==false || $user_certification->status!=1){
			return false;
		}
		$info->content = json_decode($info->content,true) ? json_decode($info->content,true) : [];

		if ($info && $info->certification_id == 12 && $info->status == 0 && !empty($info->content)) {
			// pdf 連結
			$url = isset($info->content['pdf_file']) ? $info->content['pdf_file']: '';
            $verifiedResult = CertificationResultFactory::getInstance(CERTIFICATION_INVESTIGATION, CERTIFICATION_STATUS_SUCCEED);
			$status = 3;
			$data = [];
			$response = [];
			$group_id = isset($info->content['group_id']) ? $info->content['group_id'] : '';
			// pdf 解析
			// if(! is_null($url) && $info->content['return_type'] == 1){
			// 	$this->CI->load->library('Joint_credit_lib');
			// 	$parser = new Parser();
			// 	$pdf    = $parser->parseFile($url);
			// 	$text = $pdf->getText();
			// 	$response = $this->CI->joint_credit_lib->transfrom_pdf_data($text);
			// 	$data['id'] = isset($response['applierInfo']['basicInfo']['personId']) ? $response['applierInfo']['basicInfo']['personId'] : '';
			// 	$group_id = time();
			// }

			// ocr 解析
			if(!isset($info->content['credit_investigation'][$group_id]) && $group_id && $info->content['return_type'] == 0){
				$imageIds[] = $group_id;
				$this->CI->load->library('ocr/report_scan_lib');
				$response = $this->CI->report_scan_lib->requestForResult('credit_investigations', $imageIds);
				if($response && $response->status == 200) {
					$response = isset($response->response->credit_investigation_logs->items[0]) ? $response->response->credit_investigation_logs->items[0] : [];
					if($response && $response->status=='finished'){
						$data['id'] = isset($response->credit_investigation->applierInfo->basicInfo->personId) ? $response->credit_investigation->applierInfo->basicInfo->personId : '';
						$data['A11_id'] = isset($response->credit_investigation->extraA11->personId1) ? $response->credit_investigation->extraA11->personId1 : '';
						$response = json_decode(json_encode($response->credit_investigation),true);
					}
				}
			}

			// 人工編輯
			if(isset($info->content['credit_investigation'][$group_id]) && !empty($info->content['credit_investigation'][$group_id]) && $info->content['return_type'] == 0){
				$data['id'] = isset($info->content['credit_investigation'][$group_id]['data']['applierInfo']['basicInfo']['personId']) ? $info->content['credit_investigation'][$group_id]['data']['applierInfo']['basicInfo']['personId'] : '';
				$data['A11_id'] = isset($info->content['credit_investigation'][$group_id]['data']['extraA11']['personId1']) ? $info->content['credit_investigation'][$group_id]['data']['extraA11']['personId1'] : '';
				$response = $info->content['credit_investigation'][$group_id]['data'];
			}

			if($response && $data){
				// 自然人聯徵正確性驗證
				$this->CI->load->library('verify/data_legalize_lib');
                // $verifiedResult = $this->CI->data_legalize_lib->legalize_investigation($verifiedResult, $info->user_id, $result, $info->created_at);
				// $res = $this->CI->data_legalize_lib->legalize_investigation($info->user_id,$data,1);

				// 資料轉 result
				$this->CI->load->library('mapping/user/Certification_data');
				// $result = $this->CI->certification_data->transformJointCreditToResult($response);

				// 寫入資料
				$info->content['result'][$group_id] = $result;
				$info->remark = $res['error_message'];
				$info->content['error_location'][$group_id] = $res['error_location'];
				if(empty($res['error_message'])){
					$status = 1;
				}
			}

			$this->CI->user_certification_model->update($info->id, array(
				'status' => 3,
				'sys_check' => 1,
				'content' => json_encode($info->content),
				'remark' => json_encode($info->remark)
			));

			if($status == 1){
				$this->set_success($info->id ,true);
			}
			return true;
		}
		return false;
	}

	// 法人聯徵
	public function investigationjudicial_verify($info = array(), $url=null){
		// $user_certification	= $this->get_certification_info($info->user_id,1007,$info->investor);
		// if($user_certification==false || $user_certification->status!=1){
		// 	return false;
		// }
		$info->content = isset($info->content) ? json_decode($info->content,true) : [];
		if($info && $info->certification_id == 1003 && $info->status == 0){
			$status = 3;
			$data = [];
			$group_id = isset($info->content['group_id']) ? $info->content['group_id'] : '';
			$imageIds[] = $group_id;

			// 找所有ocr資料
			if(!empty($imageIds) && !isset($info->content['credit_investigation'][$group_id])){
				// 找所有圖片ID
				$this->CI->load->library('ocr/report_scan_lib');
				$response = $this->CI->report_scan_lib->requestForResult('credit_investigations', $imageIds);

				if ($response && $response->status == 200) {
					$response = isset($response->response->credit_investigation_logs->items[0]) ? $response->response->credit_investigation_logs->items[0] : [];
					if($response && $response->status=='finished'){
						$data['id'] = isset($response->credit_investigation->applierInfo->basicInfo->taxId) ? $response->credit_investigation->applierInfo->basicInfo->taxId : '';
						$response = json_decode(json_encode($response->credit_investigation),true);
					}
				}
			}

			// 人工編輯
			if(isset($info->content['credit_investigation'][$group_id]) && !empty($info->content['credit_investigation'][$group_id])){
				$data['id'] = isset($info->content['credit_investigation'][$group_id]['data']['applierInfo']['basicInfo']['taxId']) ? $info->content['credit_investigation'][$group_id]['data']['applierInfo']['basicInfo']['taxId'] : '';
				$response = $info->content['credit_investigation'][$group_id]['data'];
			}
			if($data){
				// 法人聯徵正確性驗證
				$this->CI->load->library('verify/data_legalize_lib');
				// $res = $this->CI->data_legalize_lib->legalize_investigation($info->user_id,$data);

				// 資料轉 result
				$this->CI->load->library('mapping/user/Certification_data');
				// $result = $this->CI->certification_data->transformJointCreditToResult($response);

				// 寫入資料
				$info->content['result'][$info->content['group_id']] = $result;
				$info->remark = $res['error_message'];
				$info->content['error_location'][$info->content['group_id']] = $res['error_location'];
				if(empty($res['error_message'])){
					$status = 1;
				}
			}

				$this->CI->user_certification_model->update($info->id, array(
					'status' => 3,
					'sys_check' => 1,
					'content' => json_encode($info->content),
					'remark' => json_encode($info->remark)
				));
			if($status == 1){
		      $this->set_success($info->id ,true);
		    }
				return true;
			}
			return false;
	}

	public function simplificationjob_verify($info = array(), $url=null){
		// $user_certification	= $this->get_certification_info($info->user_id,1007,$info->investor);
		// if($user_certification==false || $user_certification->status!=1){
		// 	return false;
		// }

		$info->content = json_decode($info->content,true) ? json_decode($info->content,true) : [];
		if($info && $info->certification_id == CERTIFICATION_SIMPLIFICATIONJOB && $info->status == 0){
			$status = 3;
			// pdf 連結
			$pdf_url = isset($info->content['pdf_file']) ? $info->content['pdf_file'] : '';
			$data = [];
			$group_id = isset($info->content['group_id']) ? $info->content['group_id'] : '';
			$imageIds[] = $group_id;
			$response = [];
			// pdf 解析
			if(! is_null($pdf_url) && isset($info->content['return_type']) && $info->content['return_type'] == 1){
				$this->CI->load->library('Labor_insurance_lib');
				$parser = new Parser();
				$pdf    = $parser->parseFile($pdf_url);
				$text = $pdf->getText();
				$res = $this->CI->labor_insurance_lib->transfrom_pdf_data($text);
				$data['id_card'] = isset($res['pageList'][0]['personId']) ? $res['pageList'][0]['personId'] : '';
				$data['name'] = isset($res['pageList'][0]['name']) ? $res['pageList'][0]['name'] : '';
				$data['birthday'] = isset($res['pageList'][0]['birthday']) ? $res['pageList'][0]['birthday'] : '';
				$data['search_day'] = isset($res['pageList'][0]['reportDate']) ? $res['pageList'][0]['reportDate']: '';
				if(isset($res['pageList']) &&! empty($res['pageList'])){
					$list = end($res['pageList']);
					$last = end($list['insuranceList']);
					$last = end($last['detailList']);
					$data['salary'] = isset($last['insuranceSalary']) ? $last['insuranceSalary']: '';
				}
			}

			//  ocr解析
			if(!isset($info->content['insurance'][$group_id]) && $group_id && $info->content['return_type'] == 0){

				$this->CI->load->library('ocr/report_scan_lib');
				$res = $this->CI->report_scan_lib->requestForResult('insurance_tables', $imageIds);

				if ($res && $res->status == 200) {
					$res = isset($res->response->insurance_table_logs->items[0]) ? $res->response->insurance_table_logs->items[0] : [];
					if($res && $res->status=='finished'){
						$res = isset($res->insurance_table) ? $res->insurance_table : [];
						if($res){
							$res = json_decode(json_encode($res),true);
							$data['search_day'] = isset($res['pageList'][0]['reportDate']) ? $res['pageList'][0]['reportDate'] : '';
							$data['id_card'] = isset($res['pageList'][0]['personId']) ? $res['pageList'][0]['personId'] : '';
							$data['name'] = isset($res['pageList'][0]['name']) ? $res['pageList'][0]['name'] : '';
							$data['birthday'] = isset($res['pageList'][0]['birthday']) ? $res['pageList'][0]['birthday'] : '';
							if(isset($res['pageList']) &&! empty($res['pageList'])){
								$list = end($res['pageList']);
								$last = end($list['insuranceList']);
								$last = end($last['detailList']);
								$data['salary'] = isset($last['insuranceSalary']) ? $last['insuranceSalary']: '';
							}
						}
					}
				}
			}

			//  人工編輯
			if(isset($info->content['insurance_table'][$group_id]) && $group_id ){
				$content_data = $info->content['insurance_table'][$group_id]['data'];
				// summary_reportDate
				$data['id_card'] = isset($content_data['summary_personId']) ? $content_data['summary_personId'] : '';
				$data['name'] = isset($content_data['summary_name']) ? $content_data['summary_name'] : '';
				$data['birthday'] = isset($content_data['summary_birthday']) ? $content_data['summary_birthday'] : '';
				$data['search_day'] = isset($content_data['summary_reportDate']) ? $content_data['summary_reportDate']: '';
				if(isset($content_data['table_list']['nl-table'])){
					$last = end($content_data['table_list']['nl-table']);
					$data['salary'] = isset($last['salary']) ? $last['salary']: '';
				}
			}

			if($data){
				// 勞保異動明細
				$this->CI->load->library('verify/data_legalize_lib');
				$response = $this->CI->data_legalize_lib->legalize_simplificationjob($info->user_id,$data);

				// 寫入資料
				$info->content['result'][$group_id] = $response['result'];
				$info->remark = $response['error_message'];
				$info->content['error_location'][$group_id] = $response['error_location'];
				if(empty($response['error_message'])){
					$status = 1;
				}
			}

			$this->CI->user_certification_model->update($info->id, array(
				'status' => 3,
				'sys_check' => 1,
				'content' => json_encode($info->content),
				'remark' => json_encode($info->remark)
			));

			if($status == 1){
			  $this->set_success($info->id ,true);
			}
			return true;
		}

		return false;
	}

    public function save_mail_url($info = array(), $url, $is_valid_pdf): bool
    {
        $content = json_decode($info->content, TRUE);
        $content['pdf_file'] = $url;
        $content['is_valid_pdf'] = $is_valid_pdf;
        $mail_file_status = $url ? 1 : 0;
        $content['mail_file_status'] = $mail_file_status;

        $this->CI->user_certification_model->update($info->id, array(
            'content' => json_encode($content)
        ));
        return (bool)$mail_file_status;
    }

	// to do : 待加入並合併普匯微企e秒貸
	public function job_verify($info = array(),$url=null): bool
    {
        $cert = Certification_factory::get_instance_by_model_resource($info);
        return $cert->verify();
	}

    public function face_rotate($url='',$user_id=0,$cer_id=0,$system='azure'){
		if(empty($url))
			return false;

		$image 	= @file_get_contents($url);
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

                // Todo: 2023-10-11 azure 暫時改回 face++
                // if($system=='azure'){
                //     $this->CI->load->library('Azure_lib');
                //     $count  = count($this->CI->azure_lib->detect($url,$user_id));
                // }
                // else{
                //     $base64 = base64_encode($image_data);
                //     $this->CI->load->library('faceplusplus_lib');
                //     $token = $this->CI->faceplusplus_lib->get_face_token_by_base64($base64,$user_id,$cer_id);
                //     $count = is_array($token) ? count($token) : 0;
                // }

                $base64 = base64_encode($image_data);
                $this->CI->load->library('faceplusplus_lib');
                $token = $this->CI->faceplusplus_lib->get_face_token_by_base64($base64, $user_id, $cer_id);
                $count = is_array($token) ? count($token) : 0;
                
                if($count){
                    $this->CI->load->library('s3_upload');
                    $url = $this->CI->s3_upload->image_by_data($image_data, basename($url), $user_id, 'id_card', 'rotate');
                    return array('count' => $count, 'url' => $url, 'system' => $system);
                }
            }
		}
		return false;
	}


    private function identity_success($info){
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

			// 通過實名時更新 subcode 綁定之 user
            $this->CI->load->model('user/user_subcode_model');
            $this->CI->load->model('user/user_qrcode_model');
            $user = $this->CI->user_model->get_by(['id' => $info->user_id]);
            if (isset($user))
            {
                $subcode = $this->CI->user_subcode_model->get_by(['registered_id' => $content['id_number']]);
                if (isset($subcode))
                {
                    $user_qrcode_update_param = ['user_id' => $user->id];
                    $this->CI->user_qrcode_model->update_by(['id' => $subcode->user_qrcode_id], $user_qrcode_update_param);
                    // 寫 log
                    $this->CI->load->model('log/log_user_qrcode_model');
                    $user_qrcode_update_param['user_qrcode_id'] = $subcode->user_qrcode_id;
                    $this->CI->log_user_qrcode_model->insert_log($user_qrcode_update_param);
                }
            }

            // 認證頁「手動」通過實名認證時觸發本人、父、母、配偶，google、司法院爬蟲
            $this->CI->load->library('scraper/judicial_yuan_lib.php');
            $this->CI->load->library('scraper/google_lib.php');
            $remark = json_decode($info->remark, TRUE);

            $names = [
                $content['name'],
                $remark['OCR']['father'] ?? '',
                $remark['OCR']['mother'] ?? '',
                $remark['OCR']['spouse'] ?? ''
            ];

            // 取得地址
            $address = isset($user->address) ? $user->address : '';
            preg_match('/([\x{4e00}-\x{9fa5}]{2})(縣|市)/u', str_replace('台', '臺', $address), $matches);
            $domicile = ! empty($matches) ? $matches[1] : '';

            foreach ($names as $name)
            {
                if (!$name)
                {
                    continue;
                }

                $verdicts_statuses = $this->CI->judicial_yuan_lib->requestJudicialYuanVerdictsStatuses($name, $domicile);
                if(isset($verdicts_statuses['status']))
                {
                    if (($verdicts_statuses['status'] == 200 && $verdicts_statuses['response']['updatedAt'] < strtotime('- 1 week'))
                        || $verdicts_statuses['status'] == 204)
                    {
                        $this->CI->judicial_yuan_lib->requestJudicialYuanVerdicts($name, $domicile);
                    }
                }

                $google_statuses = $this->CI->google_lib->get_google_status($name);
                if (isset($google_statuses['status']))
                {
                    if (($google_statuses['status'] == 200 && $google_statuses['response']['updatedAt'] < strtotime('- 1 week'))
                        || $google_statuses['status'] == 204)
                    {
                        $this->CI->google_lib->request_google($name);
                    }
                }
            }


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

					array_map(function ($viracc) {
						$data = $this->CI->virtual_account_model->get_by($viracc);
						if(!isset($data))
							$this->CI->virtual_account_model->insert($viracc);
					}, $virtual_data);
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
            if (isset($content['meta']))
            {
                isset($content['meta']['last_grade']) ? $data['last_grade'] = $content['meta']['last_grade'] : '';
            }

            $rs = $this->user_meta_progress($data,$info);
            if($rs){
                return $this->fail_other_cer($info);
            }
		}
		return false;
	}

    private function passbook_success($info)
    {
        if ( ! empty($info))
        {
            $data = array(
                'passbook_status' => 1,
            );

            $rs = $this->user_meta_progress($data, $info);
            if ($rs)
            {
                $this->fail_other_cer($info);
                $user = $this->CI->user_model->get_by(['id' => $info->user_id]);
                if ( ! isset($user))
                {
                    return FALSE;
                }
                $virtual_data[] = [
                    'investor' => USER_INVESTOR,
                    'user_id' => $info->user_id,
                    'virtual_account' => CATHAY_VIRTUAL_CODE . INVESTOR_VIRTUAL_CODE . '0' . substr($user->id_number, 0, 8),
                ];

                $virtual_data[] = [
                    'investor' => USER_BORROWER,
                    'user_id' => $info->user_id,
                    'virtual_account' => CATHAY_VIRTUAL_CODE . BORROWER_VIRTUAL_CODE . '0' . substr($user->id_number, 0, 8),
                ];
                $this->CI->load->model('user/virtual_account_model');

                array_map(function ($vir_acc) {
                    $data = $this->CI->virtual_account_model->get_by($vir_acc);
                    if ( ! isset($data))
                        $this->CI->virtual_account_model->insert($vir_acc);
                }, $virtual_data);
                return TRUE;
            }
        }
        return FALSE;
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
            if (empty($info->content))
            {
                log_message('error', json_encode(['function_name' => 'email_success', 'message' => "Content of use_certification which id is {$info->id} is empty."]));
            }

            if (empty($content['email']))
            {
                return FALSE;
            }

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
                $meta_key = $info->investor == INVESTOR ? 'email_investor' : 'email_borrower';
                $param = ['user_id' => $info->user_id, 'meta_key' => $meta_key];
                if ($this->CI->user_meta_model->get_by($param))
                {
                    $this->CI->user_meta_model->update_by($param, ['meta_value' => $content['email']]);
                }
                else
                {
                    $this->CI->user_meta_model->insert(array_merge($param, ['meta_value' => $content['email']]));
                }
                return $this->fail_other_cer($info);
			}
		}
		return false;
	}

	private function companyemail_success($info)
    {
        if ( ! empty($info))
        {
            $content = $info->content;
            $email = $content['email'] ?? '';
            $data = [
                'company_email' => $email
            ];
            $rs = $this->user_meta_progress($data, $info);
            if ($rs)
            {
                $this->CI->user_model->update($info->user_id, ['email' => $email]);
                return $this->fail_other_cer($info);
            }
        }
        return FALSE;
    }

    private function financial_success($info){
		if($info){
			$content 	= $info->content;

            $financial_income = 0;
            $financial_expense = 0;
            $income 	= [
                // 薪資/打工收入
				'income',
                // 零用錢收入
				'incomeStudent',
                // 獎學金收入
                'scholarship',
				'other_income',
			];
            $expense = [
                'restaurant',
				'transportation',
				// 網路電信支出
				'telegraph_expense',
				'entertainment',
				'other_expense',
				// 租金
				'rent_expenses',
				// 教育
				'educational_expenses',
				// 保險
				'insurance_expenses',
				// 社交
				'social_expenses',
				// 房貸
				'long_assure_monthly_payment',
				// 車貸
				'mid_assure_monthly_payment',
				// 信貸
				'credit_monthly_payment',
				// 學貸
				'student_loans_monthly_payment',
				// 信用卡
				'credit_card_monthly_payment',
				// 其他民間借款
				'other_private_borrowing'
            ];

            // 收入計算
            foreach($income as $income_fields){
                if(isset($content[$income_fields]) && is_numeric($content[$income_fields])){
                    $financial_income += $content[$income_fields];
                }
            }

            // 支出計算
            foreach($expense as $expense_fields){
                if(isset($content[$expense_fields]) && is_numeric($content[$expense_fields])){
                    $financial_expense += $content[$income_fields];
                }
            }

			$data 		= array(
				'financial_status'		=> 1,
				'financial_income'		=> $financial_income,
				'financial_expense'		=> $financial_expense,
			);

            if(isset($content['creditcard_image']) && !empty($content['creditcard_image'])){
                $data['financial_creditcard'] = $content['creditcard_image'];
            }
            if(isset($content['passbook_image'][0]) && !empty($content['passbook_image'][0])){
                $data['financial_passbook'] = $content['passbook_image'][0];
            }

            if(isset($content['bill_phone_image'][0]) && !empty($content['bill_phone_image'][0])){
                $data['financial_bill_phone'] = $content['bill_phone_image'][0];
            }
            $rs = $this->user_meta_progress($data,$info);
			if($rs){
                return $this->fail_other_cer($info);
			}
		}
		return false;
	}

    private function financialWorker_success($info){
		if($info){
			$content 	= $info->content;
            $financial_income = 0;
            $financial_expense = 0;
            $income 	= [
                // 薪資/兼職收入
				'income',
                // 投資理財收入
				'pocketMoney',
				'other_income',
			];
            $expense = [
                'restaurant',
				'transportation',
				// 網路電信支出
				'telegraph_expense',
				'entertainment',
				'other_expense',
				// 租金
				'rent_expenses',
				// 教育
				'educational_expenses',
				// 保險
				'insurance_expenses',
				// 社交
				'social_expenses',
				// 房貸
				'long_assure_monthly_payment',
				// 車貸
				'mid_assure_monthly_payment',
				// 信貸
				'credit_monthly_payment',
				// 學貸
				'student_loans_monthly_payment',
				// 信用卡
				'credit_card_monthly_payment',
				// 其他民間借款
				'other_private_borrowing'
            ];

            // 收入計算
            foreach($income as $income_fields){
                if(isset($content[$income_fields]) && is_numeric($content[$income_fields])){
                    $financial_income += $content[$income_fields];
                }
            }

            // 支出計算
            foreach($expense as $expense_fields){
                if(isset($content[$expense_fields]) && is_numeric($content[$expense_fields])){
                    $financial_expense += $content[$income_fields];
                }
            }

			$data 		= array(
				'financial_status'		=> 1,
				'financial_income'		=> $financial_income,
				'financial_expense'		=> $financial_expense,
			);
            if(isset($content['creditcard_image']) && !empty($content['creditcard_image'])){
                $data['financial_creditcard'] = $content['creditcard_image'];
            }
            if(isset($content['passbook_image'][0]) && !empty($content['passbook_image'][0])){
                $data['financial_passbook'] = $content['passbook_image'][0];
            }

            if(isset($content['bill_phone_image'][0]) && !empty($content['bill_phone_image'][0])){
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
            if (isset($content['instagram']))
            {
                isset($content['instagram']['username']) ? $data['ig_username'] = $content['instagram']['username'] : '';
                isset($content['instagram']['link']) ? $data['ig_link'] = $content['instagram']['link'] : '';
                isset($content['instagram']['usernameExist']) ? $data['ig_usernameExist'] = $content['instagram']['usernameExist'] : '';
                isset($content['instagram']['info']['isPrivate']) ? $data['ig_isPrivate'] = $content['instagram']['info']['isPrivate'] : '';
                isset($content['instagram']['info']['followStatus']) ? $data['ig_followStatus'] = $content['instagram']['info']['followStatus'] : '';
                isset($content['instagram']['info']['isFollower']) ? $data['ig_isFollower'] = $content['instagram']['info']['isFollower'] : '';
                isset($content['instagram']['info']['allPostCount']) ? $data['ig_allPostCount'] = $content['instagram']['info']['allPostCount'] : '';
                isset($content['instagram']['info']['allFollowerCount']) ? $data['ig_allFollowerCount'] = $content['instagram']['info']['allFollowerCount'] : '';
                isset($content['instagram']['info']['allFollowingCount']) ? $data['ig_allFollowingCount'] = $content['instagram']['info']['allFollowingCount'] : '';
            }
            if (isset($content['meta']))
            {
                isset($content['meta']['follow_count']) ? $data['follow_count'] = $content['meta']['follow_count'] : '';
                isset($content['meta']['posts_in_3months']) ? $data['posts_in_3months'] = $content['meta']['posts_in_3months'] : '';
                isset($content['meta']['key_word']) ? $data['key_word'] = $content['meta']['key_word'] : '';
            }

            // 社交認證完成，觸發 ig 司法院爬蟲
            $verdicts_statuses = $this->CI->judicial_yuan_lib->requestJudicialYuanVerdictsStatuses($data['ig_username']);
            if(isset($verdicts_statuses['status']))
            {
                if (($verdicts_statuses['status'] == 200 && $verdicts_statuses['response']['updatedAt'] < strtotime('- 1 week'))
                    || $verdicts_statuses['status'] == 204)
                {
                    $this->CI->judicial_yuan_lib->requestJudicialYuanAllCityVerdicts($data['ig_username']);
                }
            }

            $rs = $this->user_meta_progress($data,$info);
			if($rs){
                return $this->fail_other_cer($info);
			}
		}
		return false;
	}

    private function social_intelligent_success($info)
    {
        if ($info)
        {
            $content = $info->content;
            $data = array(
                'social_status' => 1,
            );

            if (isset($content['instagram']))
            {
                isset($content['instagram']['username']) ? $data['ig_username'] = $content['instagram']['username'] : '';
                isset($content['instagram']['link']) ? $data['ig_link'] = $content['instagram']['link'] : '';
                isset($content['instagram']['usernameExist']) ? $data['ig_usernameExist'] = $content['instagram']['usernameExist'] : '';
                isset($content['instagram']['info']['isPrivate']) ? $data['ig_isPrivate'] = $content['instagram']['info']['isPrivate'] : '';
                isset($content['instagram']['info']['followStatus']) ? $data['ig_followStatus'] = $content['instagram']['info']['followStatus'] : '';
                isset($content['instagram']['info']['isFollower']) ? $data['ig_isFollower'] = $content['instagram']['info']['isFollower'] : '';
                isset($content['instagram']['info']['allPostCount']) ? $data['ig_allPostCount'] = $content['instagram']['info']['allPostCount'] : '';
                isset($content['instagram']['info']['allFollowerCount']) ? $data['ig_allFollowerCount'] = $content['instagram']['info']['allFollowerCount'] : '';
                isset($content['instagram']['info']['allFollowingCount']) ? $data['ig_allFollowingCount'] = $content['instagram']['info']['allFollowingCount'] : '';
            }
            if (isset($content['meta']))
            {
                isset($content['meta']['follow_count']) ? $data['follow_count'] = $content['meta']['follow_count'] : '';
                isset($content['meta']['posts_in_3months']) ? $data['posts_in_3months'] = $content['meta']['posts_in_3months'] : '';
                isset($content['meta']['key_word']) ? $data['key_word'] = $content['meta']['key_word'] : '';
            }

            // 社交認證完成，觸發 ig 司法院爬蟲
            $verdicts_statuses = $this->CI->judicial_yuan_lib->requestJudicialYuanVerdictsStatuses($data['ig_username']);
            if(isset($verdicts_statuses['status']))
            {
                if (($verdicts_statuses['status'] == 200 && $verdicts_statuses['response']['updatedAt'] < strtotime('- 1 week'))
                    || $verdicts_statuses['status'] == 204)
                {
                    $this->CI->judicial_yuan_lib->requestJudicialYuanAllCityVerdicts($data['ig_username']);
                }
            }

            $rs = $this->user_meta_progress($data, $info);
            if ($rs)
            {
                return $this->fail_other_cer($info);
            }
        }
        return FALSE;
    }

	private function diploma_success($info){
		if($info){
			$content 	= $info->content;
            if ( ! empty($content) && ! empty($content['school']))
            {
                $data = array(
                    'diploma_status' => 1,
                    'diploma_name' => $content['school'],
                    'diploma_major' => $content['major'],
                    'diploma_department' => $content['department'],
                    'diploma_system' => $content['system'],
                    'diploma_image' => $content['diploma_image'][0],
                );

                $this->user_meta_progress($data, $info);
            }
			return $this->fail_other_cer($info);
		}
		return false;
	}

	private function investigation_success($info){
        if($info){
			$content 	= $info->content;
			$data 		= [
				'investigation_status'		=> 1,
				'investigation_times'		=> isset($content['times']) ? $content['times'] : '',
				'investigation_credit_rate'	=> isset($content['credit_rate']) ? $content['credit_rate'] : '',
				'investigation_months'		=> isset($content['months']) ? $content['months'] : '',
			];

            $rs = $this->user_meta_progress($data,$info);
			if($rs){
                return $this->fail_other_cer($info);
			}
		}
		return false;
	}

	private function investigationa11_success($info){
		if($info){
			// $content = $info->content;
			// $this->CI->load->library('mapping/user/Certification_data');
            // TODO:暫時不寫入 meta
			// $result = ! empty($content['result']) ? $content['result'] : [];
			$meta = [];//$this->CI->certification_data->transformJointCreditToMeta($result);

			$rs = [];//$this->user_meta_progress($data,$info);
			if(1){
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
            if($rs && in_array($info->certification_id, $this->notification_list)) {
				$this->CI->notification_lib->certification($info->user_id,$info->investor,$certification['name'],1);
            }
            return $this->fail_other_cer($info);
        }
		return false;
	}

	private function certi_failed($id,$msg='',$resubmitDate='',$sys_check=true){
		$info = $this->CI->user_certification_model->get($id);
		if($info && $info->status != CERTIFICATION_STATUS_FAILED){
            $certification 	= $this->certification[$info->certification_id];

			$param = [
				'status'    => CERTIFICATION_STATUS_FAILED,
				'sys_check' => ($sys_check==true?1:0),
				'can_resubmit_at'=>$resubmitDate,
			];

			$rs = $this->CI->user_certification_model->update($info->id, $param);
			if($rs) {
                if(in_array($info->certification_id, $this->notification_list))
 				    $this->CI->notification_lib->certification($info->user_id,$info->investor,$certification['name'],2,$msg);
 				return true;
			}
		}
		return false;
	}

    private function businesstax_success($info){
        if($info){
            $data 		= [];

            $rs = $this->user_meta_progress($data,$info);
            if($rs){
                return $this->fail_other_cer($info);
            }
        }
        return false;
    }

    private function balancesheet_success($info){
        if($info){
            $data 		= [];

            $rs = $this->user_meta_progress($data,$info);
            if($rs){
                return $this->fail_other_cer($info);
            }
        }
        return false;
    }

    private function incomestatement_success($info){
			if($info){
					$content = $info->content;
					$this->CI->load->library('mapping/user/Certification_data');
					$result = ! empty($content['result']) ? $content['result'] : [];
                    // TODO:暫時不寫入 meta
					$meta = [];//$this->CI->certification_data->transformIncomestatementToMeta($result);

					$rs = [];//$this->user_meta_progress($data,$info);
					if(1){
							return $this->fail_other_cer($info);
					}
			}
        return false;
    }

    private function investigationjudicial_success($info){
        if($info){
    		$content = $info->content;
    		$this->CI->load->library('mapping/user/Certification_data');
    		// $result = ! empty($content['result']) ? $content['result'] : [];
            // TODO:暫時不寫入 meta
    		$meta = [];//$this->CI->certification_data->transformJointCreditToMeta($result);

            $rs = [];//$this->user_meta_progress($data,$info);
            if(1){
                return $this->fail_other_cer($info);
            }
        }
        return false;
    }

    private function passbookcashflow_success($info){
        if($info){
            $data 		= [];

            $rs = $this->user_meta_progress($data,$info);
            if($rs){
                return $this->fail_other_cer($info);
            }
        }
        return false;
    }

    private function passbookcashflow2_success($info)
    {
        return $this->passbookcashflow_success($info);
    }

		private function governmentauthorities_success($info){
		    if($info){
                $content = $info->content;
                $this->CI->load->library('mapping/user/Certification_data');
                $result = !empty($content['result']) ? $content['result'] : [];
                // TODO:暫時不寫入 meta
                $meta = [];//$this->CI->certification_data->transformGovernmentauthoritiesToMeta($result);
                // 寫入法人基本資料
                $this->CI->load->model('user/user_model');
                $this->CI->user_model->update($info->user_id, array(
                    'name' => $content['compName'] ?? '',
                    // 地址暫時不寫入
                    'address' => $content['address'] ?? '',
                ));
                // 找自然人資料
                $this->CI->load->model('user/user_model');
                $company = $this->CI->user_model->get_by(['id' => $info->user_id]);
                $user = $this->CI->user_model->get_by(['phone' => $company->phone, 'company_status' => 0]);
                if ($user) {
                    $status = 1;
                    // 新建法人歸戶資料
                    $param = [
                        'user_id' => $user->id,
                        'company_type' => $content['compType'] ?? '',
                        'company' => $content['compName'] ?? '',
                        'company_user_id' => $info->user_id,
                        'tax_id' => $content['compId'] ?? '',
                        'status' => 3,
                        'enterprise_registration' => json_encode(['enterprise_registration_image' => $info->content['governmentauthorities_image']])
                    ];
                    $this->CI->load->model('user/judicial_person_model');
					$judical_person_info = $this->CI->judicial_person_model->get_by(['company_user_id'=>$info->user_id]);
					if($judical_person_info){
						$rs = $this->CI->judicial_person_model->update_by(['company_user_id'=>$info->user_id],$param);
					}else{
						$rs = $this->CI->judicial_person_model->insert($param);
					}
                }
                // $rs = $this->user_meta_progress($data,$info);
                if(1){
                    return $this->fail_other_cer($info);
                }
            }
            return false;
		}

    private function interview_success($info){
        if($info){
            $data 		= [];

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
            $data 		= [];

            $rs = $this->user_meta_progress($data,$info);
            if($rs){
                return $this->fail_other_cer($info);
            }
        }
        return false;
    }

    private function charter_success($info)
    {
        if ($info) {
            $data = [];

            $rs = $this->user_meta_progress($data, $info);
            if ($rs) {
                return $this->fail_other_cer($info);
            }
        }
        return false;
    }

    private function registerofmembers_success($info)
    {
        if ($info) {
            $data = [];

            $rs = $this->user_meta_progress($data, $info);
            if ($rs) {
                return $this->fail_other_cer($info);
            }
        }
        return false;
    }

    private function mainproductstatus_success($info)
    {
        if ($info) {
            $data = [];

            $rs = $this->user_meta_progress($data, $info);
            if ($rs) {
                return $this->fail_other_cer($info);
            }
        }
        return false;
    }

    private function startupfunds_success($info)
    {
        if ($info) {
            $data = [];

            $rs = $this->user_meta_progress($data, $info);
            if ($rs) {
                return $this->fail_other_cer($info);
            }
        }
        return false;
    }

    private function business_plan_success($info)
    {
        if ($info) {
            $data = [];

            $rs = $this->user_meta_progress($data, $info);
            if ($rs) {
                return $this->fail_other_cer($info);
            }
        }
        return false;
    }

    private function verification_success($info)
    {
        if ($info) {
            $data = [];

            $rs = $this->user_meta_progress($data, $info);
            if ($rs) {
                return $this->fail_other_cer($info);
            }
        }
        return false;
    }

    private function condensedbalancesheet_success($info)
    {
        if ($info) {
            $data = [];

            $rs = $this->user_meta_progress($data, $info);
            if ($rs) {
                return $this->fail_other_cer($info);
            }
        }
        return false;
    }

    private function condensedincomestatement_success($info)
    {
        if ($info) {
            $data = [];

            $rs = $this->user_meta_progress($data, $info);
            if ($rs) {
                return $this->fail_other_cer($info);
            }
        }
        return false;
    }

    private function purchasesalesvendorlist_success($info)
    {
        if ($info) {
            $data = [];

            $rs = $this->user_meta_progress($data, $info);
            if ($rs) {
                return $this->fail_other_cer($info);
            }
        }
        return false;
    }

    private function employeeinsurancelist_success($info)
    {
			if($info){
					$content = $info->content;
					$this->CI->load->library('mapping/user/Certification_data');
					// $result = ! empty($content['result']) ? $content['result'] : [];
                    // TODO:暫時不寫入 meta
					$meta = [];//$this->CI->certification_data->transformEmployeeinsurancelistToMeta($result);
					$rs = [];//$this->user_meta_progress($data,$info);
					if(1){
							return $this->fail_other_cer($info);
					}
			}
			return false;
    }

    private function simplificationfinancial_success($info)
    {
        if ($info) {
            $data = [];

            $rs = $this->user_meta_progress($data, $info);
            if ($rs) {
                return $this->fail_other_cer($info);
            }
        }
        return false;
    }

    private function criminalrecord_success($info) {
        return $this->fail_other_cer($info);
    }

    private function simplificationjob_success($info)
    {
        if ($info) {
			$content = $info->content;
            $this->CI->load->library('mapping/user/Certification_data');
            // $result = ! empty($content['result']) ? $content['result'] : [];
            // TODO:暫時不寫入 meta
            $meta = [];//$this->CI->certification_data->transformSimplificationjobToMeta($result);

            $rs = [];//$this->user_meta_progress($data, $info);
            if (1) {
                return $this->fail_other_cer($info);
            }
        }
        return false;
    }

    private function profile_success($info){
        if($info){
            $content = $info->content;
            $this->CI->load->library('mapping/user/Certification_data');
            // $result = ! empty($content['result']) ? $content['result'] : [];
            // TODO:暫時不寫入 meta
            $meta = [];//$this->CI->certification_data->transformProfileToMeta($result);
            $rs = [];//$this->user_meta_progress($data,$info);
            if(1){
                return $this->fail_other_cer($info);
            }
        }
        return false;
    }

    private function profilejudicial_success($info){
        if($info){
            $content = $info->content;
            $this->CI->load->library('mapping/user/Certification_data');
            // $result = ! empty($content['result']) ? $content['result'] : [];
            // TODO:暫時不寫入 meta
            $meta = [];//$this->CI->certification_data->transformProfilejudicialToMeta($result);

            $rs = [];//$this->user_meta_progress($data,$info);
            if(1){
                return $this->fail_other_cer($info);
            }
        }
        return false;
    }

    public function judicialguarantee_success($info)
    {
        if( $info )
        {
            $this->CI->load->library('Judicialperson_lib');
            $apply_response = $this->CI->judicialperson_lib->succeed_in_company_guaranty($info->user_id);
            if ( $apply_response === TRUE )
            {
                return $this->fail_other_cer($info);
            }
        }
        return FALSE;
    }

    public function get_status($user_id,$investor=0,$company=0,$get_fail=false,$target=false,$product=false, $get_expired = FALSE){
		if($user_id){
			$certification = array();
            $naturalPerson = false;
            $certification_list = [];
            if($company){
                $this->CI->load->library('Judicialperson_lib');
                $naturalPerson = $this->CI->judicialperson_lib->getNaturalPerson($user_id);
            }

            if($product){
                $this->CI->load->library('loanmanager/product_lib');
                $product_certs = $this->CI->product_lib->get_product_certs_by_product($product, [ASSOCIATES_CHARACTER_REGISTER_OWNER]);
                foreach($product_certs as $key => $value) {
                    $data = $this->certification[$value];
                    if($company){
                        if(in_array($value, [
                            CERTIFICATION_IDENTITY,
                            CERTIFICATION_DEBITCARD,
                            CERTIFICATION_EMERGENCY,
                            CERTIFICATION_EMAIL,
                            CERTIFICATION_FINANCIALWORKER,
                            CERTIFICATION_INVESTIGATION,
                            CERTIFICATION_JOB,
                            CERTIFICATION_PROFILE,
                            CERTIFICATION_PASSBOOKCASHFLOW_2
                        ]))
                        {
                            $user_certification = $this->get_certification_info($naturalPerson->id, $value, 0, $get_fail, $get_expired);
                            if ($user_certification) {
                                $data['user_status'] = intval($user_certification->status);
                                $data['certification_id'] = intval($user_certification->id);
                                $data['updated_at'] = intval($user_certification->updated_at);
                            } else {
                                $data['user_status'] = null;
                                $data['certification_id'] = null;
                                $data['updated_at'] = null;
                            }
                            $certification_list[$value] = $data;
                        }else{
                            $certification[$value] = $data;
                        }
                    }else{
                        if($value < CERTIFICATION_FOR_JUDICIAL){
                            $certification[$value] = $data;
                        }
                    }
                }
            }
			elseif($company){
                $this->CI->load->library('target_lib');
                $this->CI->load->model('transaction/order_model');
                $total = 0;
                $notAllows = ['student','social','emergency','diploma'];
                $companyType = $this->get_company_type($user_id);
                //FEV
                if ($companyType) {
                    $companyType->selling_type != 1 ? $notAllows = array_merge($notAllows,['cercreditjudicial']) : '';
                    $companyType->selling_type == 2 ? $notAllows = array_merge($notAllows,['salesdetail']) : '';
                }

                $orders = $this->CI->order_model->get_many_by([
                    'company_user_id' => $user_id,
                    'status' => 10,
                ]);
                if($orders){
                    foreach($orders as $key => $value){
                        $targets = $this->CI->target_model->get_by([
                            'status' => TARGET_REPAYMENTING,
                            'order_id' => $value->id,
                        ]);
                        if($targets) {
                            $total += $this->CI->target_lib->get_amortization_table($targets)['remaining_principal'];
                        }
                    }
                }
                $self_targets = $this->CI->target_model->get_many_by([
                    'status' => TARGET_REPAYMENTING,
                    'user_id' => $user_id,
                ]);
                if($self_targets) {
                    foreach($self_targets as $key => $value) {
                        $total += $this->CI->target_lib->get_amortization_table($value)['remaining_principal'];
                    }
                }
                if ($companyType) {
//                    $total >= 500000 || $companyType->selling_type != 1?$notAllows = array_merge($notAllows,['balancesheet','incomestatement','investigationjudicial'] ):'';
                    if($total >= 1000000 && $companyType->selling_type == 1){
                        $notAllows[] = 'interview';
                    }
                }

                foreach($this->certification as $key => $value){
					in_array($value['alias'],$notAllows) ? '' : $certification[$key] = $value;
				}
			}else if($investor){
				foreach($this->certification as $key => $value){
					if(in_array($value['alias'],['identity','debitcard','email','emergency'])){
						$certification[$key] = $value;
					}
				}
			}else if($target){
                foreach($this->certification as $key => $value) {
                    if ($target->product_id >= PRODUCT_FOR_JUDICIAL && $key >= CERTIFICATION_FOR_JUDICIAL || $target->product_id < PRODUCT_FOR_JUDICIAL && $key < CERTIFICATION_FOR_JUDICIAL) {
                        $certification[$key] = $value;
                    }
                    else{
                        unset($certification[$key]);
                    }
                }
            }else{
                foreach($this->certification as $key => $value) {
                    if ($key < CERTIFICATION_FOR_JUDICIAL) {
                        $certification[$key] = $value;
                    }
                    else{
                        unset($certification[$key]);
                    }
                }
            }

            $skip_certification_ids = $this->get_skip_certification_ids($target);

			foreach($certification as $key => $value){
				$userId = $user_id;
				if($company){
                    $userId = $key < CERTIFICATION_FOR_JUDICIAL ? $naturalPerson->id : $user_id;
                }
                $user_certification = $this->get_certification_info($userId,$key,$investor,$get_fail, $get_expired);
                if($user_certification){
					$value['user_status'] 		   = intval($user_certification->status);
					$value['certification_id'] 	   = intval($user_certification->id);
                    $value['updated_at'] 		   = intval($user_certification->updated_at);
					// 回傳認證資料
					$value['content'] 		       = $user_certification->content;
                    $value['remark']		       = $user_certification->remark;
                    $value['certificate_status'] = $user_certification->certificate_status;
                    $value['expire_time'] = $user_certification->expire_time;
                    $dipoma                        = isset($user_certification->content['diploma_date'])?$user_certification->content['diploma_date']:null;
                    $key==8?$value['diploma_date']=$dipoma:null;
				}
                else
                {
                    $value['user_status'] = NULL;
                    $value['certification_id'] = NULL;
                    $value['updated_at'] = NULL;
                    $value['expire_time'] = NULL;
                }

                if (in_array($key, $skip_certification_ids))
                {
                    $value['user_status'] = CERTIFICATION_STATUS_SUCCEED;
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

    public function get_last_status($user_id, $investor = 0, $company = 0, $target = false, $product_info = false, $target_get_failed = FALSE, $target_get_expired = FALSE)
    {
        if (is_array($target))
        {
            $target = json_decode(json_encode($target));
        }
		if($user_id){
            $this->CI->load->library('loanmanager/product_lib');
			$certification = [];
            $company_source_user_id = false;
            if($target){
                $product_certs = $this->CI->product_lib->get_product_certs_by_product_id($target->product_id, $target->sub_product_id, []);
                if($target->product_id >= PRODUCT_FOR_JUDICIAL){
//                    $company = $this->get_company_type($user_id);
//                    $company_source_user_id = $company->user_id;
                }
                foreach($this->certification as $key => $value) {
                    if (in_array($key, $product_certs)) {
                        $certification[$key] = $value;
                    }
                    else{
                        unset($certification[$key]);
                    }
                }
            }else if($investor){
				foreach($this->certification as $key => $value){
					if(in_array($value['alias'],['identity','debitcard','email','emergency'])){
						$certification[$key] = $value;
					}
				}
            }elseif($company){
                foreach($this->certification as $key => $value){
                    if(in_array($value['alias'],['businesstax','balancesheet','incomestatement','investigationjudicial','passbookcashflow','governmentauthorities','salesdetail','charter','registerofmembers','mainproductstatus','startupfunds','business_plan','verification','condensedbalancesheet','condensedincomestatement','purchasesalesvendorlist','employeeinsurancelist'])){
                        $certification[$key] = $value;
                    }
                }
            }else{
                foreach($this->certification as $key => $value) {
                    if ($key < CERTIFICATION_FOR_JUDICIAL) {
                        $certification[$key] = $value;
                    }
                    else{
                        unset($certification[$key]);
                    }
                }
            }

			$certification_list = [];
			foreach($certification as $key => $value){
                $ruser_id = $key == CERTIFICATION_INVESTIGATION && $company_source_user_id?$company_source_user_id:$user_id;
                // 歸戶顯示資料不進行是否過期判斷
                if($target){
                    // 有過期判斷
                    if ($key == CERTIFICATION_REPAYMENT_CAPACITY)
                    {
                        $get_failed = TRUE;
                    }
                    else
                    {
                        $get_failed = FALSE;
                    }
                    $user_certification = $this->get_certification_info($ruser_id, $key, $investor, $get_failed || $target_get_failed, $target_get_expired);
                }else {
                    // 沒有過期判斷
                    $user_certification = $this->get_last_certification_info($ruser_id,$key,$investor);
                }
				if($user_certification){
				    $key == CERTIFICATION_JUDICIALGUARANTEE ? $value['judicialPersonId'] = isset($user_certification->content['judicialPersonId']) ? $user_certification->content['judicialPersonId'] : '' : '';
					$value['user_status'] 		= intval($user_certification->status);
                    $value['user_sub_status'] = (int) $user_certification->sub_status;
                    $value['certification_id'] 	= intval($user_certification->id);
                    $value['updated_at'] 		= intval($user_certification->updated_at);
                    $value['expire_time'] 		= $user_certification->expire_time;
                    $value['sys_check'] 		= intval($user_certification->sys_check);
                }else{
					$value['user_status'] 		= null;
                    $value['user_sub_status'] = NULL;
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

    public function verify_certifications($target, $stage): bool
    {
        $productList = $this->CI->config->item('product_list');

        $certificationsStageList = isset($target->product_id) &&
        isset($productList[$target->product_id]['certifications_stage']) ?
            $productList[$target->product_id]['certifications_stage'] : null;

        $option_cert = $productList[$target->product_id]['option_certifications'] ?? [];
        if ($target->sub_product_id != 0)
        {
            $this->CI->load->library('loanmanager/product_lib.php');
            $sub_product_list = $this->CI->product_lib->getProductInfo($target->product_id, $target->sub_product_id);
            $certificationsStageList = ! empty($sub_product_list['certifications_stage'])
                ? $sub_product_list['certifications_stage']
                : $certificationsStageList;
            $option_cert = $sub_product_list['option_certifications'];
        }

        if(!isset($target) || !isset($certificationsStageList[$stage]))
            return FALSE;

        $this->CI->load->helper('product');
        if (isset($target->certificate_status) && $target->certificate_status != TARGET_CERTIFICATE_SUBMITTED && ! is_judicial_product($target->product_id))
        { // todo: 目前僅適用於個金產品
            $userCertifications = $this->CI->user_certification_model->order_by('certification_id', 'ASC')
                ->order_by('created_at', 'DESC')->get_many_by([
                    'status' => [
                        CERTIFICATION_STATUS_PENDING_TO_VALIDATE,
                        CERTIFICATION_STATUS_SUCCEED,
                        CERTIFICATION_STATUS_PENDING_TO_REVIEW,
                        CERTIFICATION_STATUS_AUTHENTICATED,
                        CERTIFICATION_STATUS_FAILED
                    ],
                    'user_id' => $target->user_id,
                ]);
            $cert_can_pending_status = [CERTIFICATION_STATUS_SUCCEED, CERTIFICATION_STATUS_PENDING_TO_REVIEW, CERTIFICATION_STATUS_FAILED];
        }
        else
        {
            $userCertifications = $this->CI->user_certification_model->order_by('certification_id', 'ASC')
                ->order_by('created_at', 'DESC')->get_many_by([
                    'status' => [
                        CERTIFICATION_STATUS_PENDING_TO_VALIDATE,
                        CERTIFICATION_STATUS_SUCCEED,
                        CERTIFICATION_STATUS_PENDING_TO_REVIEW,
                        CERTIFICATION_STATUS_AUTHENTICATED
                    ],
                    'user_id' => $target->user_id,
                ]);
            $cert_can_pending_status = [CERTIFICATION_STATUS_SUCCEED, CERTIFICATION_STATUS_PENDING_TO_REVIEW];
        }

        // 空的認證徵信列表
        if (empty($userCertifications))
        {
            return FALSE;
        }

        $validateCertificationList = call_user_func_array('array_merge', $certificationsStageList);
        $validateCertificationList = array_diff($validateCertificationList, $option_cert);
        $doneCertifications = array_reduce($userCertifications, function ($list, $item) {
            if ( ! isset($list[$item->certification_id]))
                $list[$item->certification_id] = $item;
            return $list;
        }, []);

        // 待驗證或是已通過的認證徵信項目數量需一致
        $pendingCertifications = array_filter($doneCertifications,
            function ($x) use ($validateCertificationList, $cert_can_pending_status) {
                return in_array($x->certification_id, $validateCertificationList) &&
                    ($this->canVerify($x->status) || in_array($x->status, $cert_can_pending_status));
            });

        if (count(array_intersect_key(array_flip($validateCertificationList), $pendingCertifications))
            != count($validateCertificationList))
        {
            return FALSE;
        }

        // 必填徵信項
        $targetData = json_decode($target->target_data, TRUE);
        $verify_cert_ids = array_column($pendingCertifications, 'id');
        $targetData['verify_cetification_list'] = json_encode($verify_cert_ids);

        $this->CI->target_model->update_by([
            'id' => $target->id
        ], ['target_data' => json_encode($targetData, JSON_INVALID_UTF8_IGNORE)]);

        // 選填徵信項
        $optional_cert_ids = [];
        if ( ! empty($productList[$target->product_id]['certifications']))
        { // 取得各個徵信項最新的一筆
            $newest_user_cert = [];
            $user_cert_ary = json_decode(json_encode($userCertifications), TRUE);
            usort($user_cert_ary, function ($a, $b) {
                if ($a['created_at'] == $b['created_at']) {
                    return 0;
                }
                return ($a['created_at'] > $b['created_at']) ? -1 : 1;
            });
            foreach ($user_cert_ary as $value)
            {
                if (empty($value['certification_id']) || empty($value['id']) || isset($newest_user_cert[$value['certification_id']]))
                {
                    continue;
                }
                $newest_user_cert[$value['certification_id']] = $value['id'];
            }
            if ( ! empty($newest_user_cert))
            { // 篩選出選填的徵信項
                $option_cert_list = array_diff($productList[$target->product_id]['certifications'], $validateCertificationList);
                $optional_cert_ids = array_filter(
                    $newest_user_cert,
                    function ($key) use ($option_cert_list) {
                        return in_array($key, $option_cert_list);
                    }, ARRAY_FILTER_USE_KEY
                );
            }
        }

        $this->CI->user_certification_model->update_by(['id' => array_merge($verify_cert_ids, $optional_cert_ids)], [
            'certificate_status' => 1
        ]);
        return TRUE;
    }

	public function script_check_certification(){
		$script  		= 8;
		$count 			= 0;
		$date			= get_entering_date();
		$ids			= array();
		$user_certifications 	= $this->CI->user_certification_model->order_by('certification_id','ASC')->get_many_by(array(
			'status'				=> 0,
			'certification_id NOT'	=> [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
		));
		if($user_certifications){
			foreach($user_certifications as $key => $value){
				switch($value->certification_id){
                    case CERTIFICATION_REPAYMENT_CAPACITY:
                    case CERTIFICATION_LAND_AND_BUILDING_TRANSACTIONS:
                        break;
					default:
						$this->verify($value);
						break;
				}
				$count++;
			}
		}

        // $this->repayment_capacity_verify();
        $this->land_and_building_transactions_verify();
        $this->site_surve_video_verify();

		return $count;
	}

    // 寫入或更新muser_meta
    public function user_meta_progress($data, $info)
    {
        if (is_array($info))
        {
            $info = json_decode(json_encode($info));
        }

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

    // 失效其他認證
    public function fail_other_cer($info)
    {
        if (is_array($info))
        {
            $info = json_decode(json_encode($info));
        }

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
        if($user_id && $investor == BORROWER) {
            $certification = $this->CI->user_certification_model->order_by('created_at', 'desc')->get_many_by([
                'user_id' => $user_id,
                'investor' => $investor,
                'status !=' => CERTIFICATION_STATUS_FAILED,
            ]);
            if ($certification) {
                foreach ($certification as $value) {
                    $expireGraduateDate = false;
                    if($value->certification_id == CERTIFICATION_STUDENT && $value->status == CERTIFICATION_STATUS_SUCCEED){
                        $content = json_decode($value->content);
                        if(isset($content->graduate_date) && !empty($content->graduate_date)){
                            preg_match_all('/\d+/', $content->graduate_date, $matches);
                            $rocYear = date('Y') - 1911;
                            if($rocYear >= $matches[0][0]){
                                $expireGraduateDate = ! ($rocYear == $matches[0][0] && date('m') <= $matches[0][1]);
                            }
                        }
                    }

                    if ( ! in_array($value->certification_id, [CERTIFICATION_IDENTITY, CERTIFICATION_DEBITCARD, CERTIFICATION_EMERGENCY, CERTIFICATION_EMAIL, CERTIFICATION_DIPLOMA])
                        && $value->expire_time <= time()
                        || in_array($value->certification_id, [CERTIFICATION_INVESTIGATION, CERTIFICATION_JOB])
                        && $value->status == CERTIFICATION_STATUS_SUCCEED && time() > strtotime('+2 months', $value->updated_at)
                        || $expireGraduateDate
                    )
                    {
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
            $remark = ['memo' => []];
            $identity_cer = $this->get_certification_info($user_id, CERTIFICATION_IDENTITY, BORROWER);
            // $student_cer = $this->get_certification_info($user_id, CERTIFICATION_STUDENT, BORROWER);
            // $diploma_cer = $this->get_certification_info($user_id, CERTIFICATION_DIPLOMA, BORROWER);
            if ($identity_cer && $identity_cer->status == CERTIFICATION_STATUS_SUCCEED
                // && (isset($student_cer->content['school']) && !preg_match('/\(自填\)/', $student_cer->content['school']))
                // && (isset($diploma_cer->content['school']) && !preg_match('/\(自填\)/', $diploma_cer->content['school']))
            ) {
                $cer_id = $identity_cer->id;
                // Todo: 2023-10-11 azure 暫時改回 face++
                // Azure
                // $this->CI->load->library('Azure_lib');
                // $identity_cer_face = $this->CI->azure_lib->detect($identity_cer->content['person_image'], $user_id, $cer_id);
                // $signing_face = $this->CI->azure_lib->detect($url, $user_id, $cer_id);
                // $signing_face_count = count($signing_face);

                // face++
                $this->CI->load->library('faceplusplus_lib');

                $identity_cer_face = $this->CI->faceplusplus_lib->get_face_token($identity_cer->content['person_image']);

                $signing_face = $this->CI->faceplusplus_lib->get_face_token($url);
                $signing_face_count = is_array($signing_face) ? count($signing_face) : 0;

                $remark['memo']['first_count'] = $signing_face_count;
                if ($signing_face_count == 0) {
                    // Todo: 2023-10-11 azure 暫時改回 face++
                    // $rotate = $this->face_rotate($url, $user_id);
                    $rotate = $this->face_rotate($url, $user_id, 'faceplusplus');
                    $remark['memo']['first_rotate'] = $rotate;
                    if ($rotate) {
                        $identity_cer->content['person_image'] = $rotate['url'];
                        $signing_face_count = $rotate['count'];
                    }
                }
                if ($signing_face_count >= 2 && $signing_face_count <= 3) {

                    // Todo: 2023-10-11 azure 暫時改回 face++
                    // Azure
                    // $person_compare[] = $this->CI->azure_lib->verify($identity_cer_face[0]['faceId'], $signing_face[0]['faceId'], $user_id, $cer_id);
                    // $person_compare[] = $this->CI->azure_lib->verify($identity_cer_face[1]['faceId'], $signing_face[1]['faceId'], $user_id, $cer_id);
                    // confidence 0.0~1.0
                    // $remark['face'] = [$person_compare[0]['confidence'] * 100, $person_compare[1]['confidence'] * 100];
                    // isIdentical true/false, https://learn.microsoft.com/en-us/rest/api/faceapi/face/verify-face-to-face?tabs=HTTP
                    // $remark['face_flag'] = [$person_compare[0]['isIdentical'], $person_compare[1]['isIdentical']];

                    // face++
                    $person_compare[] = $this->CI->faceplusplus_lib->token_compare($identity_cer_face[0][0], $signing_face[0][0], $user_id, $cer_id);
                    $person_compare[] = $this->CI->faceplusplus_lib->token_compare($identity_cer_face[1][0], $signing_face[1][0], $user_id, $cer_id);
                    //confidence 0.0~100.0
                    $remark['face'] = [$person_compare[0], $person_compare[1]];
                    // 根據azure的定義，confidence >= 50 face_flag為true
                    $remark['face_flag'] = [$person_compare[0] >= 50, $person_compare[1] >= 50];

                    if ($remark['face'][0] < 90 || $remark['face'][1] < 90) {
                        $this->CI->load->library('Faceplusplus_lib');
                        $identity_cer_token = $this->CI->faceplusplus_lib->get_face_token($identity_cer->content['person_image'], $user_id, $cer_id);
                        $signing_face_token = $this->CI->faceplusplus_lib->get_face_token($identity_cer->content['person_image'], $user_id, $cer_id);
                        $signing_face_count = $signing_face_token && is_array($signing_face_token) ? count($signing_face_token) : 0;
                        $remark['memo']['second_count'] = $signing_face_count;
                        if ($signing_face_count == 0) {
                            $rotate = $this->face_rotate($identity_cer->content['person_image'], $user_id, $cer_id, 'faceplusplus');
                            $remark['memo']['second_rotate'] = $rotate;
                            if ($rotate) {
                                $identity_cer->content['person_image'] = $rotate['url'];
                                $signing_face_count = $rotate['count'];
                                $signing_face_token = $signing_face_count;
                            }
                        }
                        if ($signing_face_count == 2) {
                            $answer[] = $this->CI->faceplusplus_lib->token_compare($identity_cer_token[0][0], $signing_face_token[0][0], $user_id, $cer_id);
                            $answer[] = $this->CI->faceplusplus_lib->token_compare($identity_cer_token[1][0], $signing_face_token[1][0], $user_id, $cer_id);
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

    public function check_associates($target_id){
        $this->CI->load->model('loan/target_associate_model');
        $params = [
            "target_id" => $target_id,
            "status" => 0,
        ];
        $associate_list = $this->CI->target_associate_model->get_many_by($params);
        if($associate_list){
            $this->CI->load->model("user/user_model");
            foreach($associate_list as $key => $value){
                if($value->user_id == null && $value->content !=null){
                    $data = json_decode($value->content);
                    $user = $this->CI->user_model->get_by([
//                        'name' => $data->name,
                        'phone' => $data->phone,
                        // 'id_number' => $data->id_number,
                        'company_status' => 0

                    ]);
                    if($user){
                        $update_info = [
                            'user_id' => $user->id
                        ];
                        // 負責人加入配偶的話配偶自動同意
                        if($value->character == 3){
                            $update_info['status'] = 1;
                        }
                        // 負責人加入之實際負責人為配偶自動同意
                        if($value->character == 2 && $value->relationship == 1){
                            $update_info['status'] = 1;
                        }
                        $this->CI->target_associate_model->update_by([
                            'id' => $value->id,
                        ], $update_info);
                    }
                }
            }
        }
    }

    public function withdraw_investigation($user_id, $investor) {
        $investigation_cert = $this->CI->user_certification_model->get_by(
            [
                'certification_id' => CERTIFICATION_INVESTIGATION,
                'user_id' => $user_id,
                'investor' => $investor,
                'status' => [0, 1, 3]
            ]);
        if(isset($investigation_cert)) {
            $temp_remark						= json_decode($investigation_cert->remark, TRUE);
            if(!is_array($temp_remark))
                $temp_remark = [isset($temp_remark) ? strval($temp_remark) : ''];

            $temp_remark['verify_result'] 	= [InvestigationCertificationResult::$FAILED_MESSAGE];
            $temp_remark['client_verify_result'] = [InvestigationCertificationResult::$FAILED_MESSAGE];
            $this->CI->user_certification_model->update($investigation_cert->id,[
                'remark' => json_encode($temp_remark),
            ]);
            $this->set_failed($investigation_cert->id, InvestigationCertificationResult::$FAILED_MESSAGE);
        }
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

    public function isRejectedResult(string $msg): bool
    {
        return strpos($msg, "經AI系統綜合評估後") !== FALSE;
    }

    // 還款力計算驗證
    public function repayment_capacity_verify()
    {
        // 取得有案件「待核可」的使用者ID
        $user_lists = $this->CI->target_model->get_distinct_user_by_status([TARGET_WAITING_APPROVE]);
        foreach ($user_lists as $user)
        {
            $info = $this->get_certification_info($user['user_id'], CERTIFICATION_REPAYMENT_CAPACITY, USER_BORROWER, FALSE, TRUE);
            if (empty($info))
            {
                $id = $this->CI->user_certification_model->insert([
                    'user_id' => $user['user_id'],
                    'certification_id' => CERTIFICATION_REPAYMENT_CAPACITY,
                    'investor' => USER_BORROWER,
                    'content' => json_encode([]),
                    'status' => CERTIFICATION_STATUS_PENDING_TO_VALIDATE
                ]);
            }
            else
            {
                $id = $info->id;
            }

            $cert = Certification_factory::get_instance_by_id($id);
            $cert->verify();
        }

        return TRUE;
    }

    // 土地建物謄本
    public function land_and_building_transactions_verify()
    {
        // 取得有案件「待核可」的使用者ID
        $user_lists = $this->CI->target_model->get_distinct_user_by_status([TARGET_WAITING_APPROVE], 0, [
            'product_id' => PRODUCT_ID_HOME_LOAN
        ]);
        if (empty($user_lists))
        {
            return TRUE;
        }

        foreach ($user_lists as $user)
        {
            $info = $this->get_certification_info($user['user_id'], CERTIFICATION_LAND_AND_BUILDING_TRANSACTIONS, USER_BORROWER, FALSE, TRUE);
            if ( ! empty($info))
            {
                $cert = Certification_factory::get_instance_by_id($info->id);
                $cert->verify();
                continue;
            }

            $this->CI->user_certification_model->insert([
                'user_id' => $user['user_id'],
                'certification_id' => CERTIFICATION_LAND_AND_BUILDING_TRANSACTIONS,
                'investor' => USER_BORROWER,
                'content' => json_encode([]),
                'status' => CERTIFICATION_STATUS_PENDING_TO_REVIEW
            ]);
        }
        return TRUE;
    }

    // 入屋現勘/遠端視訊影片
    public function site_surve_video_verify()
    {
        // 取得有案件「待核可」的使用者ID
        $user_lists = $this->CI->target_model->get_distinct_user_by_status([TARGET_WAITING_APPROVE], 0, [
            'product_id' => PRODUCT_ID_HOME_LOAN
        ]);
        if (empty($user_lists))
        {
            return TRUE;
        }

        foreach ($user_lists as $user)
        {
            $info = $this->get_certification_info($user['user_id'], CERTIFICATION_SITE_SURVEY_VIDEO, USER_BORROWER, FALSE, TRUE);
            if ( ! empty($info))
            {
                $cert = Certification_factory::get_instance_by_id($info->id);
                $cert->verify();
                continue;
            }

            $this->CI->user_certification_model->insert([
                'user_id' => $user['user_id'],
                'certification_id' => CERTIFICATION_SITE_SURVEY_VIDEO,
                'investor' => USER_BORROWER,
                'content' => json_encode([]),
                'status' => CERTIFICATION_STATUS_PENDING_TO_REVIEW
            ]);
        }
        return TRUE;
    }

    /**
     * @param $id : user_certification.id
     * @param $print_timestamp : 印表日期
     * @param $verified_result : 資料驗證結果
     * @param $certification_content : user_certification.content
     * @param $remark : user_certification.remark
     * @param $created_at : user_certification.created_at
     * @return void
     */
    public function update_repayment_certification($id, $print_timestamp, $verified_result, $certification_content, $remark, $created_at)
    {
        $remark['verify_result'] = array_merge($remark['verify_result'] ?? [], $verified_result->getAllMessage(MessageDisplay::Backend));
        $status = $verified_result->getStatus();

        // Frank言: 還款力計算，若跑批結果為失敗(CERTIFICATION_STATUS_FAILED)，則自動轉為待人工(CERTIFICATION_STATUS_PENDING_TO_REVIEW)
        if ($status == CERTIFICATION_STATUS_FAILED)
        {
            $status = CERTIFICATION_STATUS_PENDING_TO_REVIEW;
        }

        $this->CI->user_certification_model->update($id, array(
            'status' => in_array($status, [CERTIFICATION_STATUS_SUCCEED, CERTIFICATION_STATUS_FAILED]) ? CERTIFICATION_STATUS_PENDING_TO_VALIDATE : $status,
            'sys_check' => 1,
            'content' => json_encode($certification_content, JSON_INVALID_UTF8_IGNORE),
            'remark' => json_encode($remark, JSON_INVALID_UTF8_IGNORE),
        ));

        if ($status == CERTIFICATION_STATUS_SUCCEED)
        {
            if ( ! $print_timestamp)
            {
                $expire_time = new DateTime;
                $expire_time->setTimestamp($created_at);
                $expire_time->modify('+ 3 month');
                $expire_timestamp = $expire_time->getTimestamp();
                $this->set_success($id, TRUE, $expire_timestamp);
            }
            else
            {
                $expire_time = new DateTime;
                $expire_time->setTimestamp($print_timestamp);
                $expire_time->modify('+ 1 month');
                $expire_timestamp = $expire_time->getTimestamp();
                $this->set_success($id, TRUE, $expire_timestamp);
            }
        }
        elseif ($status == CERTIFICATION_STATUS_FAILED)
        {
            $this->set_failed($id);
        }
    }

    private function repayment_capacity_success($info)
    {
        if ($info)
        {
            return $this->fail_other_cer($info);
        }
        return FALSE;
    }

    // 計算長期擔保月繳
    public function get_long_assure_monthly_payment($long_assure = 0)
    {
        return number_format(
            $long_assure * (pow(1.0020833, 300) * 0.0020833) / (pow(1.0020833, 300) - 1),
            2
        );
    }

    // 計算中期擔保月繳
    public function get_mid_assure_monthly_payment($mid_assure = 0)
    {
        return number_format(
            $mid_assure * (pow(1.0041666, 84) * 0.0041666) / (pow(1.0041666, 84) - 1),
            2
        );
    }

    // 計算長期放款月繳
    public function get_long_monthly_payment($long = 0)
    {
        return number_format(
            $long * (pow(1.0020833, 300) * 0.0020833) / (pow(1.0020833, 300) - 1),
            2
        );
    }

    // 計算中期放款月繳
    public function get_mid_monthly_payment($mid = 0)
    {
        return number_format(
            $mid * (pow(1.0083333, 60) * 0.0083333) / (pow(1.0083333, 60) - 1),
            2
        );
    }

    // 計算短期放款月繳
    public function get_short_monthly_payment($short = 0)
    {
        return number_format($short * 0.0083333, 2);
    }

    // 計算學生貸款月繳
    public function get_student_loans_monthly_payment($student_loans = 0, int $student_loans_count = 1)
    {
        if ($student_loans_count === 0)
        {
            return 0;
        }

        return number_format(
            $student_loans / ($student_loans_count * 12),
            2
        );
    }

    // 計算信用卡帳款月繳
    public function get_credit_card_monthly_payment($credit_card = 0)
    {
        return round(
            $credit_card * 0.1 / 1000,
            2
        );
    }

    public function set_fail_repayment_capacity(int $user_id, string $msg_backend = '', string $msg_client = ''): bool
    {
        $repayment_capacity_list = $this->CI->user_certification_model->get_many_by([
            'certification_id' => CERTIFICATION_REPAYMENT_CAPACITY,
            'user_id' => $user_id,
            'status !=' => CERTIFICATION_STATUS_FAILED
        ]);

        if (empty($repayment_capacity_list))
        {
            return TRUE;
        }

        $rs = TRUE;
        foreach ($repayment_capacity_list as $info)
        {
            $cert = Certification_factory::get_instance_by_model_resource($info);
            if ( ! empty($msg_backend))
                $cert->result->addMessage($msg_backend, CERTIFICATION_STATUS_FAILED, MessageDisplay::Backend);
            if ( ! empty($msg_client))
                $cert->result->addMessage($msg_client, CERTIFICATION_STATUS_FAILED, MessageDisplay::Client);

            $remark = json_decode($info->remark, TRUE);
            $remark['fail'] = implode("、", $cert->result->getAPPMessage(CERTIFICATION_STATUS_FAILED));
            $remark['verify_result'] = $cert->result->getAllMessage(MessageDisplay::Backend);
            $remark['verify_result_json'] = $cert->result->jsonDump();

            $this->CI->user_certification_model->update($info->id, [
                'remark' => json_encode($remark, JSON_INVALID_UTF8_IGNORE),
            ]);
            $rs = $rs && $cert->set_failure(TRUE);
        }
        return $rs;
    }

    public function get_content($user_id, $investor, $certification_id_list) {
        $certs_content = [];
        $this->CI->load->model('user/user_certification_model');
        $certs_rs = $this->CI->user_certification_model->as_array()->get_many_by(['user_id' => $user_id, 'investor' => $investor, 'status' => CERTIFICATION_STATUS_SUCCEED,
            'certification_id' => $certification_id_list]);
        foreach ($certs_rs as $v) {
            $v['content'] = json_decode($v['content'], TRUE);
            $certs_content[$v['certification_id']] = $v['content'];
        }
        return $certs_content;
    }

    public function get_skip_certification_ids($target, $user_id = 0)
    {
        $skip_certification_ids = [];
        if ( ! empty($target))
        {
            if (is_array($target))
            {
                $target = json_decode(json_encode($target));
            }
            $this->CI->load->model('loan/target_meta_model');
            $target_meta = $this->CI->target_meta_model->as_array()->get_by([
                'target_id' => $target->id,
                'meta_key' => 'skip_certification_ids',
                'user_id' => $user_id
            ]);
            $skip_certification_ids = json_decode($target_meta['meta_value'] ?? '[]', TRUE);
            $skip_certification_ids = is_array($skip_certification_ids) ? $skip_certification_ids : [];
        }
        return $skip_certification_ids;
    }

    /**
     * 確認案件關係人是否都通過徵信項
     * @param $target
     * @throws Exception
     * @return Boolean
     */
    public function associate_certs_are_succeed($target): bool
    {
        if (is_array($target))
        {
            $target = json_decode(json_encode($target));
        }
        $this->CI->load->model('loan/target_associate_model');
        // 歸案之自然人資料
        $associates_list = $this->CI->target_associate_model->get_many_by([
            'status' => ASSOCIATES_STATUS_APPROVED,
            'target_id' => $target->id
        ]);
        if ( ! empty($associates_list))
        {
            $user_id_list = array_column($associates_list, 'user_id', 'character');

            // 有未註冊之自然人 (id 為 NULL)
            if (count(array_filter($user_id_list)) != count($user_id_list))
            {
                return FALSE;
            }

            $associates_certifications_config = $this->CI->config->item('associates_certifications');
            if ( ! isset($associates_certifications_config[$target->product_id]))
            {
                throw new Exception("Not supported for this product (product_id:{$target->product_id})");
            }

            $this->CI->load->model('user/user_certification_model');
            $associates_certifications = $associates_certifications_config[$target->product_id];
            foreach ($associates_list as $associates_info)
            {
                if (isset($associates_certifications[$associates_info->character]))
                {
                    $associates_certifications_list = $this->CI->user_certification_model->get_many_by([
                        'investor' => BORROWER,
                        'status' => CERTIFICATION_STATUS_SUCCEED,
                        'user_id' => $associates_info->user_id,
                        'certification_id' => $associates_certifications[$associates_info->character]
                    ]);
                    // 確認認證徵信是否完成
                    if (count($associates_certifications[$associates_info->character])
                        == count(json_decode(json_encode($associates_certifications_list), TRUE)))
                    {
                        return TRUE;
                    }
                }
            }

        }

        return TRUE;
    }

    /**
     * 確認徵信項是否審核失敗
     * @param $exist_target_submitted : 是否已有送出案件
     * @param $certification_id : 徵信項id (user_certification.id)
     * @param int $investor : 借款端/投資端
     * @param bool $is_judicial_product
     * @return bool
     */
    public function certification_truly_failed($exist_target_submitted, $certification_id, int $investor = USER_BORROWER, bool $is_judicial_product = FALSE): bool
    {
        $cert = Certification_factory::get_instance_by_id($certification_id);
        if ( ! isset($cert))
        {
            return FALSE;
        }

        if ($investor == USER_INVESTOR ||
            $is_judicial_product === TRUE ||
            ($exist_target_submitted === TRUE || ($exist_target_submitted === FALSE && $cert->is_submit_to_review()))
        )
        {
            if ($cert->is_failed())
            {
                return TRUE;
            }

            if ($cert->is_expired())
            {
                return TRUE;
            }
        }

        return FALSE;
    }
}

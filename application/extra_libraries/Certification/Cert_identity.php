<?php

namespace Certification;
defined('BASEPATH') or exit('No direct script access allowed');

use CertificationResult\IdentityCertificationResult;
use CertificationResult\MessageDisplay;

/**
 * 實名認證徵信項
 * Class Identity
 * @package Certification
 */
class Cert_identity extends Certification_base
{
    /**
     * @var int 該徵信項之代表編號
     */
    protected $certification_id = CERTIFICATION_IDENTITY;

    /**
     * @var array 所需依賴徵信項之編號
     */
    protected $dependency_cert_id = [];

    /**
     * @var array 依賴該徵信項相關之徵信項編號
     */
    protected $relations = [];

    /**
     * @var int 認證持續有效月數
     */
    protected $valid_month = 6;

    /**
     * @var array 轉換後的資料
     */
    private $transform_data = [];

    /**
     * 解析輸入資料
     * @return array|mixed
     */
    public function parse()
    {
        $this->CI->load->library('certification_lib');
        $this->transform_data = $this->CI->certification_lib->realname_verify($this->certification);

        $this->content = $this->transform_data['content'];
        $this->remark = $this->transform_data['remark'];

        return $this->content;
    }

    /**
     * 驗證之前的前置確認作業
     * @return bool
     */
    public function check_before_verify(): bool
    {
        return TRUE;
    }

    /**
     * 驗證格式是否正確
     * @param $content : 徵信內容
     * @return bool
     */
    public function verify_format($content): bool
    {
        return TRUE;
    }

    /**
     * 核實資料是否屬實
     * @param $content : 徵信內容
     * @return bool
     */
    public function verify_data($content): bool
    {
        if ($this->transform_data['risVerified'])
        {
            if ($this->remark['error'] == '' && ! $this->transform_data['risVerificationFailed'] && ! $this->transform_data['ocrCheckFailed'])
            {
                $this->result->setStatus(CERTIFICATION_STATUS_SUCCEED);
            }
            elseif ($this->transform_data['risVerificationFailed'])
            {
                $this->remark['failed_type_list'] = [REALNAME_IMAGE_TYPE_FRONT, REALNAME_IMAGE_TYPE_BACK, REALNAME_IMAGE_TYPE_PERSON];
                $this->result->addMessage(
                    '親愛的會員您好，為確保資料真實性，請您提重新提供實名認證資料，更新您的訊息，謝謝。',
                    CERTIFICATION_STATUS_FAILED,
                    MessageDisplay::Client
                );

                $this->result->setSubStatus(CERTIFICATION_SUBSTATUS_VERIFY_FAILED);

                return FALSE;
            }
            else
            {
                $this->result->setStatus(CERTIFICATION_STATUS_PENDING_TO_REVIEW);
            }
        }
        else
        {
            $this->result->addMessage(IdentityCertificationResult::$RIS_NO_RESPONSE_MESSAGE . '，需人工驗證', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
        }

        return TRUE;
    }

    /**
     * 依照授信規則審查資料
     * @param $content : 徵信內容
     * @return bool
     */
    public function review_data($content): bool
    {
        if (empty($this->remark['OCR']['birthday']) || empty($this->content['birthday']) || $this->remark['OCR']['birthday'] != $this->content['birthday'])
        {
            $this->result->addMessage('OCR資訊與使用者資訊不符（生日）需人工驗證', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 審核成功前處理函數
     * @param $sys_check
     * @return bool
     */
    public function pre_success($sys_check): bool
    {
        $data = array(
            'id_card_status' => 1,
            'id_card_front' => $this->content['front_image'],
            'id_card_back' => $this->content['back_image'],
            'id_card_person' => $this->content['person_image'],
            'health_card_front' => $this->content['healthcard_image'],
        );

        if ( ! $this->CI->certification_lib->user_meta_progress($data, $this->certification))
        {
            return FALSE;
        }

        // 依照建立時間設定過期時間
        $expire_time = new \DateTime;
        $expire_time->setTimestamp($this->certification['created_at']);
        $expire_time->modify("+ {$this->valid_month} month");
        $this->expired_timestamp = $expire_time->getTimestamp();

        return TRUE;
    }

    /**
     * 審核成功後處理函數
     * @param $sys_check
     * @return bool
     */
    public function post_success($sys_check): bool
    {
        $content = $this->content;
        //檢查身分證字號
        $exist = $this->CI->user_model->get_by(array('id_number' => $content['id_number']));
        if (isset($exist) && $exist->id != $this->certification['user_id'])
        {
            return FALSE;
        }

        // 系統「自動」過實名認證時觸發本人、父、母、配偶，google、司法院爬蟲
        $this->CI->load->library('scraper/judicial_yuan_lib.php');
        $this->CI->load->library('scraper/google_lib.php');
        $this->CI->load->model('user/user_model');
        $remark = $this->remark;

        $names = [
            $content['name'],
            $remark['OCR']['father'] ?? '',
            $remark['OCR']['mother'] ?? '',
            $remark['OCR']['spouse'] ?? ''
        ];

        // 取得地址
        $address = $content['address'] ?? '';
        preg_match('/([\x{4e00}-\x{9fa5}]{2})(縣|市)/u', str_replace('台', '臺', $address), $matches);
        $domicile = ! empty($matches) ? $matches[1] : '';

        foreach ($names as $name)
        {
            if (!$name)
            {
                continue;
            }

            $verdicts_statuses = $this->CI->judicial_yuan_lib->requestJudicialYuanVerdictsStatuses($name, $domicile);
            log_msg('debug', "user_id={$this->certification['user_id']}; verdicts_status=" . $verdicts_statuses['status'] ?? '');
            if(isset($verdicts_statuses['status']))
            {
                if (($verdicts_statuses['status'] == 200 && $verdicts_statuses['response']['updatedAt'] < strtotime('- 1 week'))
                    || $verdicts_statuses['status'] == 204)
                {
                    $this->CI->judicial_yuan_lib->requestJudicialYuanVerdicts($name, $domicile, $this->certification['user_id']);
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

        $data = array(
            'id_card_status' => 1,
            'id_card_front' => $content['front_image'],
            'id_card_back' => $content['back_image'],
            'id_card_person' => $content['person_image'],
            'health_card_front' => $content['healthcard_image'],
        );

        $rs = $this->CI->certification_lib->user_meta_progress($data, $this->certification);
        if ($rs)
        {
            $birthday = trim($content['birthday']);
            if (strlen($birthday) == 7 || strlen($birthday) == 6)
            {
                $birthday = $birthday + 19110000;
                $birthday = date('Y-m-d', strtotime($birthday));
            }
            $sex = substr($content['id_number'], 1, 1) == 1 ? 'M' : 'F';
            $user_info = array(
                'name' => $content['name'],
                'sex' => $sex,
                'id_number' => $content['id_number'],
                'id_card_date' => $content['id_card_date'],
                'id_card_place' => $content['id_card_place'],
                'address' => $content['address'],
                'birthday' => $birthday,
            );
            if ($exist)
            {
                unset($user_info['sex']);
            }
            else
            {
                $virtual_data[] = array(
                    'investor' => 1,
                    'user_id' => $this->certification['user_id'],
                    'virtual_account' => CATHAY_VIRTUAL_CODE . INVESTOR_VIRTUAL_CODE . substr($content['id_number'], 1, 9),
                );

                $virtual_data[] = array(
                    'investor' => 0,
                    'user_id' => $this->certification['user_id'],
                    'virtual_account' => CATHAY_VIRTUAL_CODE . BORROWER_VIRTUAL_CODE . substr($content['id_number'], 1, 9),
                );
                $this->CI->load->model('user/virtual_account_model');

                array_map(function ($viracc) {
                    $data = $this->CI->virtual_account_model->get_by($viracc);
                    if ( ! isset($data))
                        $this->CI->virtual_account_model->insert($viracc);
                }, $virtual_data);
            }

            $this->CI->user_model->update_many($this->certification['user_id'], $user_info);

            return $this->CI->certification_lib->fail_other_cer($this->certification);
        }

        return FALSE;
    }

    /**
    * 起案前戶役政比對資訊有誤退掉實名認證項
    * @param $identity_cert 認證項
    * @param string $msg
    * @return bool
    */
    public static function set_failed_for_apply($identity_cert, string $msg = ''): bool
    {
        $CI = &get_instance();
        $CI->load->model('user/user_certification_model');
        $certification = json_decode(json_encode($identity_cert), TRUE);
        $remark = empty($certification['remark'])
            ? []
            : (is_string($certification['remark'])
                ? json_decode($certification['remark'], TRUE)
                : $certification['remark']);

        $param = [
            'status' => CERTIFICATION_STATUS_FAILED,
            'sys_check' => SYSTEM_ADMIN_ID,
        ];
        if ( ! empty($msg))
        {
            $remark['fail'] = $msg;
            $remark['failed_type_list'] = [1, 2, 3, 4];
            $param['remark'] = json_encode($remark);
        }
        $rs = $CI->user_certification_model->update($identity_cert->id, $param);
        if ($rs)
        {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 審核失敗前處理函數
     * @param $sys_check
     * @return bool
     */
    public function pre_failure($sys_check): bool
    {
        return TRUE;
    }

    /**
     * 審核失敗後處理函數
     * @param $sys_check
     * @return bool
     */
    public function post_failure($sys_check): bool
    {
        return TRUE;
    }

    /**
     * 轉人工前處理函數
     * @param $sys_check
     * @return bool
     */
    public function pre_review($sys_check): bool
    {
        return TRUE;
    }

    /**
     * 轉人工後處理函數
     * @param $sys_check
     * @return bool
     */
    public function post_review($sys_check): bool
    {
        return TRUE;
    }

    /**
     * 是否已過期
     * @return bool
     */
    public function is_expired(): bool
    {
        return FALSE;
    }

    public function is_submit_to_review(): bool
    {
        // 實名認證不管案件「送出狀態(user_certification.certificate_status)」為何，永遠視為「已送件」
        // 意即，此徵信項不受「一件送出」邏輯影響，失敗及失敗、成功即成功...
        return TRUE;
    }
}

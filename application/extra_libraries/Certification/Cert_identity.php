<?php

namespace Certification;
defined('BASEPATH') or exit('No direct script access allowed');

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
                    '親愛的會員您好，為確保資料真實性，請至我的>資料中心>實名認證，更新您的訊息，謝謝。',
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

        return TRUE;
    }

    /**
     * 依照授信規則審查資料
     * @param $content : 徵信內容
     * @return bool
     */
    public function review_data($content): bool
    {
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
}
<?php

namespace Certification;
defined('BASEPATH') or exit('No direct script access allowed');

use CertificationResult\MessageDisplay;

/**
 * 學生身份證徵信項
 * Class Student
 * @package Certification
 */
class Cert_student extends Certification_base
{
    /**
     * @var int 該徵信項之代表編號
     */
    protected $certification_id = CERTIFICATION_STUDENT;

    /**
     * @var array 所需依賴徵信項之編號
     */
    protected $dependency_cert_id = [CERTIFICATION_IDENTITY];

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
     * 所有項目是否已提交
     * @override
     * @return bool
     */
    public function is_submitted(): bool
    {
        return TRUE;
    }

    /**
     * 解析輸入資料
     * @return array|mixed
     */
    public function parse()
    {
        $this->CI->load->library('scraper/sip_lib');

        if (empty($this->content['school']) || empty($this->content['sip_account']) || empty($this->content['sip_password']))
        {
            $this->result->addMessage('SIP填入資訊為空', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
            return $this->content;
        }

        $sip_log = $this->CI->sip_lib->getLoginLog($this->content['school'], $this->content['sip_account']);

        // 判斷 login_log 是否有回應
        if ( ! isset($sip_log['status']))
        {
            $this->result->addMessage('SIP爬蟲LoginLog無回應，請洽工程師', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
            return $this->content;
        }

        // 判斷 login 是否執行完成
        if ($sip_log['status'] != SCRAPER_STATUS_SUCCESS)
        {
            switch ($sip_log['status'])
            {
                case SCRAPER_STATUS_NO_CONTENT:
                    $this->CI->sip_lib->requestDeep($this->content['school'], $this->content['sip_account'], $this->content['sip_password']);
                    $this->result->setStatus(CERTIFICATION_STATUS_PENDING_TO_VALIDATE);
                    break;
                case SCRAPER_STATUS_CREATED:
                    $this->result->setStatus(CERTIFICATION_STATUS_PENDING_TO_VALIDATE);
                    break;
                default:
                    $this->result->addMessage('SIP爬蟲LoginLog http回應: ' . $sip_log['status'] . '，請洽工程師', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
            }
            return $this->content;
        }

        // 判斷 login 執行完成後的結果
        if ( ! isset($sip_log['response']['status']))
        {
            $this->result->addMessage('無對應的SIP爬蟲LoginLog status，請洽工程師', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
            return $this->content;
        }
        if ($sip_log['response']['status'] != 'finished')
        {
            switch ($sip_log['response']['status'])
            {
                case 'failure':
                    $this->result->addMessage('SIP登入執行失敗，請人工進行驗證', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                    break;
                case 'university_not_found':
                    $this->result->addMessage('SIP學校不在清單內，請人工進行驗證', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                    break;
                case 'university_not_enabled':
                    $this->result->addMessage('SIP學校為黑名單，請人工進行驗證', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                    break;
                case 'university_not_crawlable':
                    $this->result->addMessage('SIP學校無法爬取，請人工進行驗證', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                    break;
                case 'started':
                case 'retry':
                case 'requested':
                    // 爬蟲未跑完
                    $this->result->setStatus(CERTIFICATION_STATUS_PENDING_TO_VALIDATE);
                    break;
                default:
                    $this->result->addMessage('SIP爬蟲LoginLog status回應: ' . $sip_log['response']['status'] . '，請洽工程師', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
            }
            return $this->content;
        }

        // 判斷 SIP 帳號密碼是否正確
        if ( ! isset($sip_log['response']['isRight']) || ! $sip_log['response']['isRight'])
        {
            $university_status_response = $this->CI->sip_lib->getUniversityModel($this->content['school']);
            $university_status = $university_status_response['response']['status'] ?? '';

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

            $this->result->addMessage(
                'SIP登入失敗，學校狀態: ' . ($status_mapping[$university_status] ?? '') . '，請人工進行驗證',
                CERTIFICATION_STATUS_PENDING_TO_REVIEW,
                MessageDisplay::Backend
            );

            return $this->content;
        }

        // 判斷 SIP 是否成功登入
        if ( ! isset($sip_log['response']['isLogin']) || ! $sip_log['response']['isLogin'])
        {
            // SIP 帳號密碼判定正確，但登入爬取過程中出現異常
            $this->result->addMessage(
                'SIP帳號密碼正確，爬蟲執行失敗，請確認此學校狀態、以及是否為在學中帳號，請人工進行驗證',
                CERTIFICATION_STATUS_PENDING_TO_REVIEW,
                MessageDisplay::Backend
            );
            return $this->content;
        }

        // 判斷 deep_log 是否有回應
        $deep_log = $this->CI->sip_lib->getDeepLog($this->content['school'], $this->content['sip_account']);
        if ( ! isset($deep_log['status']) || ! isset($deep_log['response']['status']))
        {
            return $this->content;
        }

        // 判斷 deep_log 是否執行完成
        if ($deep_log['status'] != SCRAPER_STATUS_SUCCESS)
        {
            switch ($deep_log['status'])
            {
                case SCRAPER_STATUS_NO_CONTENT:
                    $this->CI->sip_lib->requestDeep($this->content['school'], $this->content['sip_account'], $this->content['sip_password']);
                    $this->result->setStatus(CERTIFICATION_STATUS_PENDING_TO_VALIDATE);
                    break;
                default:
                    // 沒動作
            }
            return $this->content;
        }

        // 判斷 deep_log 執行完成後的結果
        if ($deep_log['response']['status'] != 'finished')
        {
            switch ($deep_log['response']['status'])
            {
                case 'failure':
                    $this->result->addMessage('SIP爬蟲DeepScraper失敗，請人工進行驗證', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                    break;
                case 'deep scraping':
                case 'logging in':
                    $this->result->setStatus(CERTIFICATION_STATUS_PENDING_TO_VALIDATE);
                    break;
                default:
                    $this->result->addMessage('SIP爬蟲DeepLog status回應: ' . $sip_log['response']['status'] . '，請洽工程師', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
            }
            return $this->content;
        }

        $sip_data = $this->CI->sip_lib->getDeepData($this->content['school'], $this->content['sip_account']);

        // 判斷是否有資料
        if ( ! isset($sip_data['response']['result']))
        {
            $this->result->addMessage('SIP爬蟲DeepScraper沒有資料，請人工進行驗證', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
        }

        // 判斷實名認證資料與 SIP 資料是否一致
        $this->content['sip_data'] = $sip_data['response'] ?? [];
        $this->content['meta']['last_grade'] = $sip_data['response']['result']['latestGrades'] ?? '';
        $user_info = $this->dependency_cert_list[CERTIFICATION_IDENTITY]->content ?? []; // 取得實名認證的資料
        $name = $user_info['name'] ?? '';
        $id_number = $user_info['id_number'] ?? '';
        $sip_name = $sip_data['response']['result']['name'] ?? '';
        $sip_id_number = $sip_data['response']['result']['idNumber'] ?? '';

        if ($name != $sip_name)
        {
            $this->result->addMessage("SIP姓名與實名認證資訊不同:1.實名認證姓名=\"{$name}\"2.SIP姓名=\"{$sip_name}\"", CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
        }

        if ($id_number != $sip_id_number)
        {
            $this->result->addMessage("SIP身分證與實名認證資訊不同1.實名認證身分證=\"{$id_number}\"2.SIP身分證=\"{$sip_id_number}\"", CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
        }

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
        if (empty($content['graduate_date']))
        {
            $this->result->addMessage('預計畢業時間格式錯誤', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
            return FALSE;
        }

        if ( ! preg_match('/^民國\d{2,3}(年|-|\/)(0?[1-9]|1[012])(月|-|\/)(0?[1-9]|[12]\d|3[01])(日?)$/u', $content['graduate_date']))
        {
            $this->result->addMessage('預計畢業時間格式錯誤', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
            return FALSE;
        }

        $graduate_date = preg_replace('/民國/', '', $content['graduate_date']);
        $this->CI->load->library('mapping/time');
        $graduate_date = $this->CI->time->ROCDateToUnixTimestamp($graduate_date);

        // 是否畢業
        if (is_numeric($graduate_date) && $graduate_date <= strtotime(date('Y-m-d', $this->certification['created_at'])))
        {
            // 是否超過六年
            if ($graduate_date <= strtotime(date('Y-m-d', $this->certification['created_at']) . '-6 years'))
            {
                $this->result->addMessage('已畢業，請申請上班族貸', CERTIFICATION_STATUS_FAILED, MessageDisplay::Client);
                return FALSE;
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
        $data = array(
            'student_status' => 1,
            'school_name' => $content['school'],
            'school_system' => $content['system'],
            'school_department' => $content['department'],
            'school_major' => $content['major'],
            'school_email' => $content['email'],
            'school_grade' => $content['grade'],
            'student_id' => $content['student_id'],
            'student_card_front' => $content['front_image'],
            'student_card_back' => $content['back_image'],
            'student_sip_account' => $content['sip_account'],
            'student_sip_password' => $content['sip_password'],
            'student_license_level' => $content['license_level'] ?? '',
            'student_game_work_level' => $content['game_work_level'] ?? '',
            'student_pro_level' => $content['pro_level'] ?? '',
        );
        ! isset($content['graduate_date']) ?: $data['graduate_date'] = $content['graduate_date'];
        ! isset($content['programming_language']) ?: $data['student_programming_language'] = count(is_array($content['programming_language']) ?: []);
        ! isset($content['transcript_image'][0]) ?: $data['transcript_front'] = $content['transcript_image'][0];

        $rs = $this->CI->certification_lib->user_meta_progress($data, $this->certification);
        if ($rs)
        {
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
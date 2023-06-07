<?php

namespace Certification;
defined('BASEPATH') or exit('No direct script access allowed');

use Certification_ocr\Parser\Ocr_parser_factory;
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
        return array_merge($this->content, $this->_get_ocr_info());
    }

    /**
     * 驗證之前的前置確認作業
     * @return bool
     */
    public function check_before_verify(): bool
    {
        if (empty($this->content['school']) || empty($this->content['sip_account']) || empty($this->content['sip_password']))
        {
            // SIP填入資訊為空
            $this->result->addMessage('SIP填入資訊為空', CERTIFICATION_STATUS_PENDING_TO_VALIDATE, MessageDisplay::Backend);
            return TRUE;
        }

        $this->CI->load->library('scraper/sip_lib');
        $sip_log = $this->CI->sip_lib->getLoginLog($this->content['school'], $this->content['sip_account']);

        // 判斷 login_log 是否有回應
        if ( ! isset($sip_log['status']))
        {
            // SIP爬蟲LoginLog無回應
            $this->result->addMessage('SIP爬蟲LoginLog無回應，請洽工程師', CERTIFICATION_STATUS_PENDING_TO_VALIDATE, MessageDisplay::Backend);
            return TRUE;
        }

        // 判斷 login 是否執行完成
        if ($sip_log['status'] != SCRAPER_STATUS_SUCCESS)
        {
            switch ($sip_log['status'])
            {
                case SCRAPER_STATUS_NO_CONTENT:
                    $this->CI->sip_lib->requestDeep($this->content['school'], $this->content['sip_account'], $this->content['sip_password']);
                    $this->result->setStatus(CERTIFICATION_STATUS_PENDING_TO_VALIDATE);
                    return FALSE;
                case SCRAPER_STATUS_CREATED:
                    $this->result->setStatus(CERTIFICATION_STATUS_PENDING_TO_VALIDATE);
                    return FALSE;
                default:
                    // SIP爬蟲LoginLog http回應：非成功 http response status code
                    $this->result->addMessage('SIP爬蟲LoginLog http回應: ' . $sip_log['status'] . '，請洽工程師', CERTIFICATION_STATUS_PENDING_TO_VALIDATE, MessageDisplay::Backend);
                    return TRUE;
            }
        }

        // 判斷 login 執行完成後的結果
        if ( ! isset($sip_log['response']['status']))
        {
            // 無對應的SIP爬蟲LoginLog status
            $this->result->addMessage('無對應的SIP爬蟲LoginLog status，請洽工程師', CERTIFICATION_STATUS_PENDING_TO_VALIDATE, MessageDisplay::Backend);
            return TRUE;
        }
        if ($sip_log['response']['status'] != 'finished')
        {
            switch ($sip_log['response']['status'])
            {
                case 'failure':
                    // SIP登入執行失敗
                    $this->result->addMessage('SIP登入執行失敗', CERTIFICATION_STATUS_PENDING_TO_VALIDATE, MessageDisplay::Backend);
                    return TRUE;
                case 'university_not_found':
                    // SIP學校不在清單內
                    $this->result->addMessage('SIP學校不在清單內', CERTIFICATION_STATUS_PENDING_TO_VALIDATE, MessageDisplay::Backend);
                    return TRUE;
                case 'university_not_enabled':
                    // SIP學校為黑名單
                    $this->result->addMessage('SIP學校為黑名單', CERTIFICATION_STATUS_PENDING_TO_VALIDATE, MessageDisplay::Backend);
                    return TRUE;
                case 'university_not_crawlable':
                    // SIP學校無法爬取
                    $this->result->addMessage('SIP學校無法爬取', CERTIFICATION_STATUS_PENDING_TO_VALIDATE, MessageDisplay::Backend);
                    return TRUE;
                case 'started':
                case 'retry':
                case 'requested':
                    // 爬蟲未跑完
                    $this->result->setStatus(CERTIFICATION_STATUS_PENDING_TO_VALIDATE);
                    return FALSE;
                default:
                    $this->result->addMessage('SIP爬蟲LoginLog status回應: ' . $sip_log['response']['status'], CERTIFICATION_STATUS_PENDING_TO_VALIDATE, MessageDisplay::Backend);
                    return TRUE;
            }
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
            // SIP登入失敗
            $this->result->addMessage(
                'SIP登入失敗，學校狀態: ' . ($status_mapping[$university_status] ?? ''),
                CERTIFICATION_STATUS_PENDING_TO_VALIDATE,
                MessageDisplay::Backend
            );
            return TRUE;
        }

        // 判斷 SIP 是否成功登入
        if ( ! isset($sip_log['response']['isLogin']) || ! $sip_log['response']['isLogin'])
        {
            // SIP 帳號密碼判定正確，但登入爬取過程中出現異常
            // 可能為 1.學校狀態異常 2.帳號非在學中
            $this->result->addMessage(
                'SIP帳號密碼正確，爬蟲執行失敗，請確認此學校狀態、以及是否為在學中帳號',
                CERTIFICATION_STATUS_PENDING_TO_VALIDATE,
                MessageDisplay::Backend
            );
            return TRUE;
        }

        // 判斷 deep_log 是否有回應
        $deep_log = $this->CI->sip_lib->getDeepLog($this->content['school'], $this->content['sip_account']);
        if ( ! isset($deep_log['status']) || ! isset($deep_log['response']['status']))
        {
            return TRUE;
        }

        // 判斷 deep_log 是否執行完成
        if ($deep_log['status'] != SCRAPER_STATUS_SUCCESS)
        {
            switch ($deep_log['status'])
            {
                case SCRAPER_STATUS_NO_CONTENT:
                    $this->CI->sip_lib->requestDeep($this->content['school'], $this->content['sip_account'], $this->content['sip_password']);
                    $this->result->setStatus(CERTIFICATION_STATUS_PENDING_TO_VALIDATE);
                    return FALSE;
                default:
                    return TRUE;
            }
        }

        // 判斷 deep_log 執行完成後的結果
        if ($deep_log['response']['status'] != 'finished')
        {
            switch ($deep_log['response']['status'])
            {
                case 'failure':
                    // SIP爬蟲DeepScraper失敗
                    $this->result->addMessage('SIP爬蟲DeepScraper失敗', CERTIFICATION_STATUS_PENDING_TO_VALIDATE, MessageDisplay::Backend);
                    return TRUE;
                case 'deep scraping':
                case 'logging in':
                    $this->result->setStatus(CERTIFICATION_STATUS_PENDING_TO_VALIDATE);
                    return FALSE;
                default:
                    $this->result->addMessage('SIP爬蟲DeepLog status回應: ' . $sip_log['response']['status'] . '，請洽工程師', CERTIFICATION_STATUS_PENDING_TO_VALIDATE, MessageDisplay::Backend);
                    return TRUE;
            }
        }

        $sip_data = $this->CI->sip_lib->getDeepData($this->content['school'], $this->content['sip_account']);
        $this->content['sip_data'] = $sip_data['response'] ?? [];
        $this->content['meta']['last_grade'] = $sip_data['response']['result']['latestGrades'] ?? '';

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
        if ($this->_chk_ocr_status($content) === FALSE)
        {
            return FALSE;
        }

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

        return TRUE;
    }

    /**
     * 依照授信規則審查資料
     * @param $content : 徵信內容
     * @return bool
     */
    public function review_data($content): bool
    {
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
                $this->result->addMessage('畢業時間超過六年：自動退件', CERTIFICATION_STATUS_FAILED, MessageDisplay::Backend);
                $this->result->setSubStatus(CERTIFICATION_SUBSTATUS_REVIEW_FAILED);
                return FALSE;
            }
        }

        // 預計畢業時間
        if ($graduate_date > strtotime(date('Y-m-d', $this->certification['created_at']) . '+6 years'))
        {
            $this->result->addMessage('預計畢業時間超過六年：轉人工', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
            return FALSE;
        }

        $config_school_system_list = $this->CI->config->item('school_system');

        // 判斷實名認證、自填資料與 SIP 資料是否一致
        // 若不一致，改以 OCR 結果比對
        $user_info = $this->dependency_cert_list[CERTIFICATION_IDENTITY]->content ?? []; // 取得實名認證的資料
        $name = $user_info['name'] ?? '';
        $school = $content['school'] ?? '';
        $department = $content['department'] ?? '';
        $system = $config_school_system_list[$content['system'] ?? ''] ?? '';
        $sip_name = $content['sip_data']['result']['name'] ?? '';
        $sip_school = $content['sip_data']['university'] ?? '';
        $sip_department = $content['sip_data']['result']['department'] ?? '';
        $sip_flag = TRUE;
        $sip_not_match_column = [];
        if ( ! empty($content['sip_data']))
        {
            if ($name != $sip_name)
            {
                $sip_not_match_column[] = "1.實名認證姓名=\"{$name}\"2.SIP姓名=\"{$sip_name}\"";
                $sip_flag = FALSE;
            }
            if ($school != $sip_school)
            {
                $sip_not_match_column[] = "1.實名認證學校=\"{$school}\"2.SIP學校=\"{$sip_school}\"";
                $sip_flag = FALSE;
            }
            if ($department != $sip_department)
            {
                $sip_not_match_column[] = "1.實名認證科系=\"{$department}\"2.SIP科系=\"{$sip_department}\"";
                $sip_flag = FALSE;
            }
        }
        if ($sip_flag === FALSE)
        {
            $this->result->addMessage('SIP資訊與使用者資訊不符' . (
                empty($sip_not_match_column)
                    ? ''
                    : ('（' . implode('、', $sip_not_match_column) . '）')
                ), CERTIFICATION_STATUS_PENDING_TO_VALIDATE, MessageDisplay::Backend);
        }
        if ($name == $sip_name && $school == $sip_school && $department == $sip_department)
        {
            return TRUE;
        }
        $ocr_name = $content['ocr_parser']['content']['student']['name'] ?? '';
        $ocr_school = $content['ocr_parser']['content']['university']['name'] ?? '';
        $ocr_department = $content['ocr_parser']['content']['student']['department'] ?? '';
        $ocr_system = $content['ocr_parser']['content']['student']['academic_degree'] ?? '';
        // 子系統提供之OCR學制辨識結果若為「四技」或「二技」
        // 後台須將OCR辨識結果當作「大學」（*非將辨識結果改為「大學」*）
        if ($ocr_system == '四技' || $ocr_system == '二技')
        {
            $ocr_system = $config_school_system_list[0] ?? '';
        }
        $ocr_flag = TRUE;
        $ocr_not_match_column = [];
        if ($name != $ocr_name)
        {
            $ocr_not_match_column[] = '姓名';
            $ocr_flag = FALSE;
        }
        if ($school != $ocr_school)
        {
            $ocr_not_match_column[] = '學校';
            $ocr_flag = FALSE;
        }
        if ($department != $ocr_department)
        {
            $ocr_not_match_column[] = '科系';
            $ocr_flag = FALSE;
        }
        if ($system != $ocr_system)
        {
            $ocr_not_match_column[] = '學制';
            $ocr_flag = FALSE;
        }
        if ($ocr_flag === FALSE)
        {
            $this->result->addMessage('OCR資訊與使用者資訊不符' . (
                empty($ocr_not_match_column)
                    ? ''
                    : ('（' . implode('、', $ocr_not_match_column) . '）')
                ) . '：轉人工', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
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
            'school_name' => $content['admin_edit']['school'] ?? $content['school'],
            'school_system' => $content['admin_edit']['system'] ?? $content['system'],
            'school_department' => $content['admin_edit']['department'] ?? $content['department'],
            'school_major' => $content['major'] ?? '',
            'school_email' => $content['email'] ?? '',
            'school_grade' => $content['grade'] ?? '',
            'student_id' => $content['student_id'] ?? '',
            'student_card_front' => $content['front_image'] ?? '',
            'student_card_back' => $content['back_image'] ?? '',
            'student_sip_account' => $content['sip_account'] ?? '',
            'student_sip_password' => $content['sip_password'] ?? '',
            'student_license_level' => $content['license_level'] ?? '',
            'student_game_work_level' => $content['game_work_level'] ?? '',
            'student_pro_level' => $content['pro_level'] ?? '',
        );
        ! isset($content['graduate_date']) ?: $data['graduate_date'] = $content['graduate_date'] ?? '';
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

    // 要跑的 OCR 辨識
    private function _get_ocr_info(): array
    {
        $result = [];
        if ( ! isset($this->content['ocr_parser']['res']))
        {
            $cert_ocr_parser = Ocr_parser_factory::get_instance($this->certification);
            $ocr_parser_result = $cert_ocr_parser->get_result();
            if ($ocr_parser_result['success'] === TRUE)
            {
                if ($ocr_parser_result['code'] == 201 || $ocr_parser_result['code'] == 202)
                { // OCR 任務剛建立，或是 OCR 任務尚未辨識完成
                    return $result;
                }
                $result['ocr_parser']['res'] = TRUE;
                $result['ocr_parser']['content'] = $ocr_parser_result['data'];
            }
            else
            {
                $result['ocr_parser']['res'] = FALSE;
                $result['ocr_parser']['msg'] = $ocr_parser_result['msg'];
            }
        }

        return $result;
    }

    // OCR 辨識後的檢查
    private function _chk_ocr_status($content): bool
    {
        if ( ! isset($content['ocr_parser']['res']))
        {
            $this->result->setStatus(CERTIFICATION_STATUS_PENDING_TO_VALIDATE);
            return FALSE;
        }
        return TRUE;
    }
}
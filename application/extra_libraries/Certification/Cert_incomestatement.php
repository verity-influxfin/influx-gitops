<?php

namespace Certification;

use Certification_ocr\Certification_ocr_factory;
use CertificationResult\MessageDisplay;

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 近三年所得稅結算申報書
 * Class Incomestatement
 * @package Certification
 */
class Cert_incomestatement extends Certification_base
{
    /**
     * @var int 該徵信項之代表編號
     */
    protected $certification_id = CERTIFICATION_INCOMESTATEMENT;

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
        $cert_ocr = Certification_ocr_factory::get_instance($this->certification);
        $ocr_result = $cert_ocr->get_result();
        if ($ocr_result['success'] === TRUE)
        { // 把 OCR 解析到的內容補到 content 的空格裡
            $this->content = array_replace_recursive($ocr_result['data'], $this->content);
            $this->content['ocr_result'] = TRUE;
            $this->content['ocr_result_content'] = $ocr_result['data'];
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
        if ( ! isset($this->content['ocr_result']) || $this->content['ocr_result'] === FALSE)
        {
            $this->result->setStatus(CERTIFICATION_STATUS_PENDING_TO_VALIDATE);
            return TRUE;
        }

        $data = [];
        $this->result->setStatus(CERTIFICATION_STATUS_PENDING_TO_REVIEW);

        if ( ! empty($this->content['result']))
        {
            foreach ($this->content['result'] as $k => $v)
            {
                // 使用者校驗資料
                if (isset($v['origin_type']))
                {
                    if ($v['origin_type'] == 'user_confirm')
                    {
                        $data[$k]['report_time'] = $v['report_time'] ?? '';
                        $data[$k]['company_name'] = $v['company_name'] ?? '';
                        $data[$k]['company_tax_id'] = $v['company_tax_id'] ?? '';
                        $data[$k]['input_89'] = $v['input_89'] ?? '';
                        $data[$k]['input_90'] = $v['input_90'] ?? '';
                        $data[$k]['id'] = $k;
                    }
                }
                else
                {
                    // 找所有ocr資料
                    $this->CI->load->library('ocr/report_scan_lib');
                    $response = $this->CI->report_scan_lib->requestForResult('income_statements', $k);
                    if ($response && $response->status == 200)
                    {
                        foreach ($response as $v1)
                        {
                            $data[$k]['report_time'] = $v1->income_statement->report_time_range->end_at ?? '';
                            $data[$k]['company_name'] = $v1->income_statement->company->companyName ?? '';
                            $data[$k]['company_tax_id'] = $v1->income_statement->company->taxId ?? '';
                            $data[$k]['input_89'] = $v1->income_statement->operationIncome->{'89'} ?? '';
                            $data[$k]['input_90'] = $v1->income_statement->operationIncome->{'90'} ?? '';
                            $data[$k]['id'] = $v1->id ?? '';
                            foreach ($v1->income_statement->netIncomeTable as $v2)
                            {
                                if ($v2->key == '04')
                                {
                                    $data[$k]['input_4_1'] = $v2->value->left;
                                    $data[$k]['input_4_2'] = $v2->value->right;
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($data)
        {
            $this->CI->load->library('verify/data_legalize_lib');
            $res = $this->CI->data_legalize_lib->legalize_incomestatement($this->certification['user_id'], $data);

            if (empty($res['error_message']))
            {
                $this->result->setStatus(CERTIFICATION_STATUS_SUCCEED);
            }
            else
            {
                $this->result->addMessage(
                    $res['error_message'],
                    CERTIFICATION_STATUS_PENDING_TO_REVIEW,
                    MessageDisplay::Backend
                );
                $this->additional_data['error_location'] = $res['error_location'];
                // 寫入資料
                foreach ($res['result'] as $k => $v)
                {
                    $this->additional_data['result'][$data[$k]['id']] = [
                        'action_user' => '系統',
                        'send_time' => time(),
                        'status' => 1,
                        'company_name' => $res[$k]['company_name'] ?? '',
                        'report_time' => $res[$k]['report_time'] ?? '',
                        'company_tax_id' => $res[$k]['company_tax_id'] ?? '',
                        'input_89' => $res[$k]['input_89'] ?? '',
                        'input_90' => $res[$k]['input_90'] ?? '',
                        'input_4_1' => $data[$k]['input_4_1'] ?? '',
                        'input_4_2' => $data[$k]['input_4_2'] ?? '',
                    ];
                }
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
        return $this->CI->certification_lib->fail_other_cer($this->certification);
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
}
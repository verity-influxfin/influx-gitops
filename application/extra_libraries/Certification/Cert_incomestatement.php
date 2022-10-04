<?php

namespace Certification;

use Certification_ocr\Parser\Ocr_parser_factory;
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
     * @var array 驗證後的額外資料
     */
    private $additional_data = [];

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
        /**
         * Certification::incomestatement_post() 原新增時 status=1
         * 但為了可以跑 OCR 辨識，改成新增時 status=0
         * 辨識完成改回 status=1
         */

        $data = [];
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
        else
        {
            $this->result->setStatus(CERTIFICATION_STATUS_SUCCEED);
        }
        return TRUE;
    }

    /**
     * 驗證結束後處理函數
     * @return bool
     */
    public function post_verify(): bool
    {
        if (empty($this->additional_data))
        {
            return TRUE;
        }

        $certification_info = $this->CI->user_certification_model->get($this->certification['id']);
        $content = json_decode($certification_info->content ?? [], TRUE);
        $result = $this->CI->user_certification_model->update($this->certification['id'], [
            'content' => json_encode(array_replace_recursive($content, $this->additional_data), JSON_INVALID_UTF8_IGNORE)
        ]);

        if ($result)
        {
            return TRUE;
        }

        return FALSE;
    }
}
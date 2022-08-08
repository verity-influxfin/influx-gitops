<?php

namespace Certification_ocr\Parser;

use Certification\Certification_factory;

/**
 * OCR 辨識
 * Type : 一般的資料解析
 * Cert : 聯合徵信報告
 */
class Cert_investigation extends Ocr_parser_base
{
    protected $task_path = '/ocr/joint_credit_pdf_v1v2_report';
    protected $task_type = self::TYPE_PARSER;
    private $content;

    public function __construct($certification)
    {
        parent::__construct($certification);
        if ( ! empty($this->certification['content']))
        {
            $this->content = json_decode($this->certification['content'], TRUE);
        }
        else
        {
            $this->content = [];
        }
    }

    /**
     * 把任務的回傳值填到對應的 content key
     * @param $task_res_data : 任務解析完的 response_body
     * @return array
     */
    public function data_mapping($task_res_data): array
    {
        if (empty($task_res_data))
        {
            return [];
        }
        return [
            'is_valid' => isset($task_res_data['is_valid_bool']) && $task_res_data['is_valid_bool'] === TRUE,
            'applierInfo' => [
                'basicInfo' => [
                    'personId' => $task_res_data['person_basic_info_table']['person_id'] ?? '',
                ],
                'creditInfo' => [
                    'printDatetime' => $task_res_data['print_time_str'] ?? '',
                    'liabilities' => [
                        'description' => '一. 借款資訊B',
                        'totalAmount' => [
                            'description' => '1. 借款總餘額資訊',
                            'existCreditInfo' => $task_res_data['credit_info_table']['liabilities']['total_amount']['has_credit_info'] ?? '有/無信用資訊',
                            'creditDetail' => '參閱信用明細'
                        ],
                        'metaInfo' => [
                            'description' => '2. 共同債務/從債務/其他債務資訊',
                            'existCreditInfo' => $task_res_data['credit_info_table']['liabilities']['meta_info']['has_credit_info'] ?? '有/無信用資訊',
                            'creditDetail' => '參閱信用明細'
                        ],
                        'badDebtInfo' => [
                            'description' => '3. 借款逾期、催收或呆帳記錄',
                            'existCreditInfo' => $task_res_data['credit_info_table']['liabilities']['bad_debt_info']['has_credit_info'] ?? '有/無信用資訊',
                            'creditDetail' => '參閱信用明細'
                        ],
                    ],
                    'creditCard' => [
                        'description' => '二. 信用卡資訊K',
                        'cardInfo' => [
                            'description' => '1. 信用卡持卡紀錄',
                            'existCreditInfo' => $task_res_data['credit_info_table']['credit_card']['card_info']['has_credit_info'] ?? '有/無信用資訊',
                            'creditDetail' => '參閱信用明細'
                        ],
                        'totalAmount' => [
                            'description' => '2. 信用卡帳款總餘額資訊',
                            'existCreditInfo' => $task_res_data['credit_info_table']['credit_card']['total_amount']['has_credit_info'] ?? '有/無信用資訊',
                            'creditDetail' => '參閱信用明細'
                        ],
                    ],
                    'checkingAccount' => [
                        'description' => '三. 票信資訊C',
                        'largeAmount' => [
                            'description' => '1. 大額存款不足退票資訊',
                            'existCreditInfo' => $task_res_data['credit_info_table']['checking_account']['large_amount']['has_credit_info'] ?? '有/無信用資訊',
                            'creditDetail' => '參閱信用明細'
                        ],
                        'rejectInfo' => [
                            'description' => '2. 票據拒絕往來資訊',
                            'existCreditInfo' => $task_res_data['credit_info_table']['checking_account']['reject_info']['has_credit_info'] ?? '有/無信用資訊',
                            'creditDetail' => '參閱信用明細'
                        ]
                    ],
                    'queryLog' => [
                        'description' => '四. 查詢記錄S',
                        'queriedLog' => [
                            'description' => '1. 被查詢記錄',
                            'existCreditInfo' => $task_res_data['credit_info_table']['query_log']['queried_log']['has_credit_info'] ?? '有/無信用資訊',
                            'creditDetail' => '參閱信用明細'
                        ],
                        'applierSelfQueriedLog' => [
                            'description' => '2. 當事人查詢信用報告記錄',
                            'existCreditInfo' => $task_res_data['credit_info_table']['query_log']['applier_self_queried_log']['has_credit_info'] ?? '有/無信用資訊',
                            'creditDetail' => '參閱信用明細'
                        ]
                    ],
                    'other' => [
                        'description' => '五. 其他O',
                        'extraInfo' => [
                            'description' => '1. 附加訊息資訊',
                            'existCreditInfo' => $task_res_data['credit_info_table']['other']['extra_info']['has_credit_info'] ?? '有/無信用資訊',
                            'creditDetail' => '參閱信用明細'
                        ],
                        'mainInfo' => [
                            'description' => '2. 主債務債權轉讓及清償資訊',
                            'existCreditInfo' => $task_res_data['credit_info_table']['other']['main_info']['has_credit_info'] ?? '有/無信用資訊',
                            'creditDetail' => '參閱信用明細'
                        ],
                        'metaInfo' => [
                            'description' => '3. 共同債務/從債務/其他債務轉讓資訊',
                            'existCreditInfo' => $task_res_data['credit_info_table']['other']['meta_info']['has_credit_info'] ?? '有/無信用資訊',
                            'creditDetail' => '參閱信用明細'
                        ],
                        'creditCardTransferInfo' => [
                            'description' => '4. 信用卡債權轉讓及清償資訊',
                            'existCreditInfo' => $task_res_data['credit_info_table']['other']['credit_card_transfer_info']['has_credit_info'] ?? '有/無信用資訊',
                            'creditDetail' => '參閱信用明細'
                        ]
                    ]
                ]
            ],
            'B1' => [
                'dataList' => $this->_get_b1_data_list($task_res_data['b1_p1_table'] ?? []),
            ],
            'B1-extra' => [
                'dataList' => $this->_get_b1_extra_data_list($task_res_data['b1_p2_table'] ?? []),
            ],
            'B2' => [
                'part1' => [
                    'dataList' => $this->_get_b2_data_list($task_res_data['b2_p1_table'] ?? [], 1),
                    'description' => '共同債務資訊'
                ],
                'part2' => [
                    'dataList' => $this->_get_b2_data_list($task_res_data['b2_p2_table'] ?? [], 2),
                    'description' => '從債務資訊'
                ],
                'part3' => [
                    'dataList' => $this->_get_b2_data_list($task_res_data['b2_p3_table'] ?? [], 3),
                    'description' => '其他債務資訊'
                ]
            ],
            'B3' => [
                'dataList' => $this->_get_b3_data_list($task_res_data['b3_table'] ?? []),
            ],
            'K1' => [
                'dataList' => $this->_get_k1_data_list($task_res_data['k1_table'] ?? []),
            ],
            'K2' => [
                'totalAmount' => $task_res_data['k2_sum_table']['total_balance'] ?? '',
                'dataList' => $this->_get_k2_data_list($task_res_data['k2_table'] ?? []),
            ],
            'C1' => [
                'dataList' => [],
            ],
            'C2' => [
                'dataList' => [],
            ],
            'S1' => [
                'dataList' => $this->_get_s1_data_list($task_res_data['s1_table'] ?? []),
            ],
            'S2' => [
                'dataList' => $this->_get_s2_data_list($task_res_data['s2_table'] ?? []),
            ],
            '01' => [
                'dataList' => [],
            ],
            '02' => [
                'dataList' => [],
            ],
            '03' => [
                'dataList' => [],
            ],
            '04' => [
                'dataList' => [],
            ],
            'companyCreditScore' => [
                'scoreComment' => $this->_get_score_cmment($task_res_data['credit_score'] ?? ''),
                'noCommentReason' => $this->get_no_comment_reason($task_res_data['credit_score_reason'] ?? []),
            ]
        ];
    }

    /**
     * 取得借款餘額明細資訊
     * @param array $b1_raw_data
     * @return array
     */
    private function _get_b1_data_list(array $b1_raw_data): array
    {
        $result = [];
        if (empty($b1_raw_data))
        {
            return $result;
        }
        foreach ($b1_raw_data as $value)
        {
            $result[] = [
                'bankName' => $value['bank_name'] ?? '', // 金融機構名稱
                'yearMonth' => $value['data_yyy_mm'] ?? '', // 資料年月
                'totalAmount' => $value['contract_amount'] ?? '', // 訂約金額
                'noDelayAmount' => $value['unpaid_amount'] ?? '', // 未逾期餘額
                'delayAmount' => $value['overdue_amount'] ?? '', // 逾期金額
                'accountDescription' => $value['subject'] ?? '', // 科目
                'purpose' => $value['purpose'] ?? '', // 用途
                'pastOneYearDelayRepayment' => $value['last_12_month_delay_record'] ?? '', // 最近十二個月遲延還款紀錄
            ];
        }
        return $result;
    }

    /**
     * 取得借款餘額明細資訊下方的餘額增減等明細
     * @param array $b1_extra_raw_data
     * @return array
     */
    private function _get_b1_extra_data_list(array $b1_extra_raw_data): array
    {
        $result = [];
        if (empty($b1_extra_raw_data))
        {
            return $result;
        }
        foreach ($b1_extra_raw_data as $value)
        {
            $result[] = [
                'bankName' => $value['bank_name'] ?? '', // 金融機構名稱
                'yearMonth' => $value['data_date'] ?? '', // 資料日期
                'appropriationAmount' => $value['balance_increase'] ?? '', // 餘額增加
                'repaymentAmount' => $value['balance_decrease'] ?? '', // 餘額減少
                'accountDescription' => $value['subject'] ?? '', // 科目
                'purpose' => $value['purpose'] ?? '', // 用途
                'pastOneYearDelayRepayment' => $value['delay_record'] ?? '', // 遲延還款紀錄
            ];
        }
        return $result;
    }

    /**
     * 取得共同債務/從債務/其他債務資訊
     * @param $b2_partly_raw_data
     * @param int $part_no
     * @return array
     */
    private function _get_b2_data_list($b2_partly_raw_data, int $part_no): array
    {
        $result = [];
        if (empty($b2_partly_raw_data))
        {
            return $result;
        }
        $field_list = [
            1 => ['co_borrower' => '主借款戶', 'loan_bank' => '承貸金融機構', 'unpaid_amount' => '未逾期金額', 'overdue_amount' => '逾期金額', 'subject' => '科目'],
            2 => ['main_borrower' => '主借款戶', 'loan_bank' => '承貸金融機構', 'unpaid_amount' => '未逾期金額', 'overdue_amount' => '逾期金額', 'subject' => '科目'],
            3 => ['main_borrower' => '主借款戶', 'loan_bank' => '承貸金融機構', 'unpaid_amount' => '未逾期金額', 'overdue_amount' => '逾期金額', 'subject' => '科目'],
        ];
        if ( ! isset($field_list[$part_no]))
        {
            return $result;
        }
        foreach ($b2_partly_raw_data as $data_key => $data_value)
        {
            foreach ($field_list[$part_no] as $field_key => $field_value)
            {
                $result[$data_key][$field_value] = $data_value[$field_key] ?? '';
            }
        }
        return $result;
    }

    /**
     * 取得借款逾期、催收或呆帳紀錄
     * @param $b3_raw_data
     * @return array
     */
    private function _get_b3_data_list($b3_raw_data): array
    {
        $result = [];
        if (empty($b3_raw_data))
        {
            return $result;
        }
        foreach ($b3_raw_data as $value)
        {
            $result[] = [
                '金融機構名稱' => $value['bank_name'] ?? '',
                '資料日期' => $value['data_date'] ?? '',
                '金額' => $value['amount'] ?? '',
                '科目' => $value['subject'] ?? '',
                '結案日期' => $value['close_date'] ?? '',
            ];
        }
        return $result;
    }

    /**
     * 取得信用卡持卡紀錄
     * @param $k1_raw_data
     * @return array
     */
    private function _get_k1_data_list($k1_raw_data): array
    {
        $result = [];
        if (empty($k1_raw_data))
        {
            return $result;
        }
        foreach ($k1_raw_data as $value)
        {
            $result[] = [
                'authority' => $value['bank'] ?? '', // 發卡機構
                'cardName' => [ // 卡名
                    'type' => $value['card_name_1'] ?? '',
                    'level' => $value['card_name_2'] ?? '',
                    'primaryType' => $value['card_name_3'] ?? ''
                ],
                'authorizedDate' => $value['date'] ?? '', // 發卡日期
                'deauthorizedDate' => $value['stop_date'] ?? '', // 停卡日期
                'status' => $value['status'] ?? '', // 使用狀態
            ];
        }
        return $result;
    }

    /**
     * 取得信用卡戶帳款資訊
     * @param $k2_raw_data
     * @return array
     */
    private function _get_k2_data_list($k2_raw_data): array
    {
        $result = [];
        if (empty($k2_raw_data))
        {
            return $result;
        }
        foreach ($k2_raw_data as $value)
        {
            $result[] = [
                'date' => $value['date'] ?? '', // 結帳日
                'bank' => $value['bank'] ?? '', // 發卡機構
                'cardType' => $value['card_name'] ?? '', // 卡名
                'quotaAmount' => $value['quota'] ?? '', // 額度
                'currentAmount' => $value['current_payable_account'] ?? '', // 本期應付帳款
                'nonExpiredAmount' => $value['unexpired_payable_account'] ?? '', // 未到期待付款
                'previousPaymentStatus' => $value['last_pay_status'] ?? '', // 上期繳款狀況
                'cashAdvanced' => $value['is_prepay_cash'] ?? '', // 是否預借現金
                'claimsStatus' => $value['debt_status'] ?? '', // 債權狀態
                'claimsClosed' => $value['debt_close'] ?? '', // 債權結案
            ];
        }
        return $result;
    }

    /**
     * 取得被查詢紀錄
     * @param $s1_raw_data
     * @return array
     */
    private function _get_s1_data_list($s1_raw_data): array
    {
        $result = [];
        if (empty($s1_raw_data))
        {
            return $result;
        }
        foreach ($s1_raw_data as $value)
        {
            $result[] = [
                'date' => $value['query_date'] ?? '', // 查詢日期
                'institution' => $value['query_bank'] ?? '', // 查詢機構
                'reason' => $value['query_reason'] ?? '', // 查詢理由
            ];
        }
        return $result;
    }

    /**
     * 取得當事人查詢信用報告紀錄
     * @param $s2_raw_data
     * @return array
     */
    private function _get_s2_data_list($s2_raw_data): array
    {
        $result = [];
        if (empty($s2_raw_data))
        {
            return $result;
        }
        foreach ($s2_raw_data as $value)
        {
            $result[] = [
                'date' => $value['query_date'] ?? '', // 查詢日期
                'applyType' => $value['apply_method'] ?? '', // 申請方式
                'creditReportType' => $value['credit_report_type'] ?? '', // 信用報告種類
                'revealToBank' => $value['is_disclose_to_bank'] ?? '', // 是否揭露予金融機構參考
            ];
        }
        return $result;
    }

    /**
     * 取得信用評分
     * @param $score_comment
     * @return array|string|string[]|null
     */
    private function _get_score_cmment($score_comment)
    {
        if (empty($score_comment))
        {
            return '';
        }
        return preg_replace('/^(\d{3})分$/i', '${1}', $score_comment);
    }

    /**
     * 取得信用評分說明
     * @param $no_comment_reason
     * @return string
     */
    private function get_no_comment_reason(array $no_comment_reason): string
    {
        if (empty($no_comment_reason))
        {
            return '';
        }
        return '＊' . implode(' ＊', $no_comment_reason);
    }

    /**
     * 取得 OCR 欲解析的圖片及其相關資訊
     * @return array
     */
    public function get_request_body(): array
    {
        $get_file = $this->get_pdf_url();
        if ($get_file['success'] === FALSE)
        {
            return $this->return_failure($get_file['msg']);
        }

        return $this->return_success([
            'pdf_url' => $get_file['data']['pdf_url'],
        ]);
    }

    /**
     * 取得欲解析的 pdf 檔案 URL
     * @return array
     */
    public function get_pdf_url(): array
    {
        $cert = Certification_factory::get_instance_by_model_resource($this->certification);
        if (empty($cert))
        {
            return $this->return_failure('Cannot find pdf file.');
        }
        if ($cert->is_submitted() === FALSE)
        {
            return $this->return_failure('Pdf file not submitted yet!');
        }
        if (empty($this->content['pdf_file']))
        {
            return $this->return_failure('Empty pdf file!');
        }
        return $this->return_success(['pdf_url' => $this->content['pdf_file']]);
    }
}
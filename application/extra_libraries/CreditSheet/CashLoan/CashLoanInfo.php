<?php
namespace CreditSheet\CashLoan;
use CreditSheet\CashLoan\CashLoanBase;
use CreditSheet\CreditSheetTrait;

defined('BASEPATH') OR exit('No direct script access allowed');

class CashLoanInfo implements CashLoanBase {
    use CreditSheetTrait;

    protected $CI;
    protected $endDate;

    function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('loan/credit_model');
        $this->CI->load->model('loan/credit_sheet_model');
        $this->CI->load->model('user/user_meta_model');
        $this->CI->load->library('Certification_lib');
        $this->CI->load->library('Target_lib');

        $this->endDate = date('Y-m-d H:i:s');
    }

    /**
     * 取得現存明細區塊
     * @return array
     */
    public function getCashLoanInfo(): array
    {
        $response = [];
        if(isset($this->creditSheet->target)) {

            $response['approvedDate'] = $this->getApprovedDate();
            $response['lastYearDate'] = $this->getLastYearDate();

            $lastYear =  date('Y-m-d', strtotime("-12 months",
                strtotime($this->endDate)));
            $response['approvedCreditList'] = [];
            $totalLoanAmount = 0;
            $approvedCreditList = $this->getApprovedCreditList();
            foreach ($approvedCreditList as $creditRecord) {
                $credit = [];
                $credit['bookkeeping'] = $this->getBookkeepingType($creditRecord->instalment);
                $credit['unusedCreditLine'] = $this->creditSheet->viewConverter->thousandUnit($creditRecord->unused_credit_line);
                $credit['loanLine'] = $this->creditSheet->viewConverter->thousandUnit($creditRecord->loan_amount);
                $credit['interestRate'] = $this->creditSheet->viewConverter->percentSymbol($creditRecord->interest_rate);
                $credit['lineExpiredDate'] = $this->creditSheet->viewConverter->dateFormatToChinese(
                    $creditRecord->line_expired_at ?? '');
                $credit['lastPaymentDate'] = $this->creditSheet->viewConverter->dateFormatToChinese(
                    $this->CI->target_lib->getLastPaymentDate($creditRecord->loan_date, $creditRecord->format_id, $creditRecord->instalment));
                $response['approvedCreditList'][] = $credit;

                if($creditRecord->loan_date >= $lastYear)
                    $totalLoanAmount += $creditRecord->loan_amount;
            }
            $response['lastYearTotalLine'] = $this->creditSheet->viewConverter->thousandUnit($this->getLatestLine());
            $response['lastYearTotalLoanAmount'] = $this->creditSheet->viewConverter->thousandUnit($totalLoanAmount);
            $response['lastYearTotalLoanAmount2'] = $this->creditSheet->viewConverter->thousandUnit($totalLoanAmount);
            $response['note'] = $this->getNote();
        }
        return $response;
    }

    /**
     * 取得本次核准日期
     * @return string
     */
    protected function getApprovedDate(): string
    {
        return '';
    }

    /**
     * 取得最近一年時間
     * @return string
     */
    protected function getLastYearDate(): string
    {
        return '';
    }

    /**
     * 取得核准紀錄列表
     * @return array
     */
    protected function getApprovedCreditList(): array
    {
        return $this->_getApprovedCreditList($this->endDate);
    }

    /**
     * 取得其他說明
     * @return string
     */
    protected function getNote(): string
    {
        return '';
    }

    /**
     * 取得最近一年總額度
     * @return string
     */
    protected function getLatestLine(): string
    {
        $credit = $this->CI->credit_model->order_by('created_at', 'DESC')->get_by(
            ['user_id' => $this->creditSheet->user->id, 'status' => 1]);
        return isset($credit) ? $credit->amount : '';
    }

}

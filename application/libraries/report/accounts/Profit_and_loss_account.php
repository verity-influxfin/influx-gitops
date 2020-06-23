<?php

class Profit_and_loss_account
{
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('investment_model');
        $this->CI->load->model('target_model');
        $this->CI->load->library('target_lib');
        $this->CI->load->model('transaction/transaction_model');
        $this->CI->load->library('utility/payment_time_utility');
        $this->investment_model = $this->CI->investment_model;
        $this->target_model = $this->CI->target_model;
        $this->target_lib = $this->CI->target_lib;
        $this->transaction_model = $this->CI->transaction_model;
        $this->payment_time_utility = $this->CI->payment_time_utility;
    }

    public function getInitialInvestmentAt($investmentIds)
    {
        $investment = $this->transaction_model->get_by(['investment_id' => $investmentIds, 'source' => 11, 'status' => [1,2]]);
        if ($investment) {
            return date('Y-m-d', $investment->created_at);
        }
        return '';
    }

    public function getLastRepayment($investmentIds)
    {
        $lastTransaction = $this->transaction_model->order_by('limit_date', 'DESC')->limit(1)->get_by(['investment_id' => $investmentIds, 'status' => [1, 2]]);

        $lastRepaymentAt = $lastTransaction->entering_date > $lastTransaction->limit_date
                         ? $lastTransaction->entering_date
                         : $lastTransaction->limit_date;
        return $this->payment_time_utility->goToNext($lastRepaymentAt);
    }

    public function generateTotalReport($investmentIds)
    {
        $investments = $this->investment_model->order_by('target_id', 'ASC')->get_many($investmentIds);

        $rows = ['normal' => [], 'overdue' => [], 'prepayment' => []];
        if (!$investments) {
            return [];
        }

        $initialInvestmentAt = $this->getInitialInvestmentAt($investmentIds);
        $lastRepaymentAt = $this->getLastRepayment($investmentIds);

        $nextMonthPayDate = date('Y-m-', strtotime(date('Y-m', time()) . ' + 1 month')) . REPAYMENT_DAY;
        foreach ($investments as $key => $value) {
            $target = $this->target_model->get($value->target_id);
            $amortizationTables = $this->target_lib->get_investment_amortization_table_v2($target, $value, $lastRepaymentAt);
            if (
                !$amortizationTables
                || !isset($amortizationTables['normal'])
                || !isset($amortizationTables['overdue'])
            ) {
                continue;
            }
            $isPrepayment = false;
            if (in_array($target->sub_status, [4])) {
                $isPrepayment = true;
            }
            $set = false;
            $delay_occurred_used = false;

            foreach ($amortizationTables as $key => $value) {
                $currentRows = $value['rows'];

                foreach ($currentRows as $k => $v) {
                    if ($v['instalment'] == 0) continue;

                    if (!$v['repayment_date']) {
                        continue;
                    }

                    if (
                        $isPrepayment
                        && $key == 'normal'
                        && (
                            $v["interest"] > $v["r_interest"]
                            || $v["prepayment_allowance"] > 0
                            || $v["prepayment_damage"] > 0
                        )
                    ) {
                        if ($v["remaining_principal"] == 0) {
                            $key = 'prepayment';
                        } else {
                            //the prepayment is not associated with current investor as the loan was transfered to other
                            $v["prepayment_damage"] = 0;
                        }
                    }
                    if (!$rows[$key] && $key != "prepayment") {
                        $rows[$key][$initialInvestmentAt] = $this->initRow();
                        $currentMonth = $initialInvestmentAt;
                        while ($currentMonth < $v['repayment_date']) {
                            $nextMonth = $this->payment_time_utility->goToNext($currentMonth, true);
                            $rows[$key][$nextMonth] = $this->initRow();
                            $currentMonth = $nextMonth;
                        }
                    }

                    if( !$set && $key == 'normal' && isset($amortizationTables['normal']['date'])
                        && date('d', strtotime($amortizationTables['normal']['date'])) != 10)
                    {
                        $odate = $ndate = $amortizationTables['normal']['date'];
                        $ym = date('Y-m', strtotime($odate));
                        $pay_date = date('Y-m-', strtotime($ym )) . REPAYMENT_DAY;
                        $ndate = $odate > $pay_date ? date('Y-m-', strtotime($ym . ' + 1 month')) . REPAYMENT_DAY : $pay_date;
                        if(!isset($rows[$key][$ndate])){
                            $rows[$key][$ndate] = $this->initRow();
                        }
                        $rows[$key][$ndate]['remaining_principal'] += $amortizationTables['normal']['amount'];
                        $set = true;
                    }

//                    $today = date('Y-m-d', time());
//                    if(date('Y-m-', time()) . REPAYMENT_DAY == $v['repayment_date']){
//                        $temp = $v;
//                        $temp['principal'] = $temp['principal'] - $temp['r_principal'];
//                        $temp['interest'] = $temp['interest'] - $temp['r_interest'];
//                        $temp['delay_interest'] = $temp['delay_interest'] - $temp['r_delayinterest'];
//                        $rows[$key][$today] = $this->sumColumn($temp ,$rows[$key][$today]);
//                    }

                    if($key == 'overdue'){
                        if ($v['repayment_date'] > $nextMonthPayDate) {
                            continue;
                        }

                        if(!$delay_occurred_used && $v['delay_occurred_at'] != 0){
                            $temp = $v;
                            $pay_dates = date('Y-m-',strtotime( $v['delay_occurred_at'])) . REPAYMENT_DAY;
                            if(isset($rows[$key][$v['delay_occurred_at']])) {//$rows[$key][$pay_dates]['principal']
                                $temp['principal'] += $rows[$key][$v['delay_occurred_at']]['principal'];
                                $temp['interest'] += $rows[$key][$v['delay_occurred_at']]['interest'];
                                $temp['damage'] += $rows[$key][$v['delay_occurred_at']]['damage'];
                                $temp['delay_interest'] += $rows[$key][$v['delay_occurred_at']]['delay_interest'];
                                $temp['r_principal'] += $rows[$key][$v['delay_occurred_at']]['r_principal'];
                                $temp['r_interest'] += $rows[$key][$v['delay_occurred_at']]['r_interest'];
                                $temp['r_fees'] += $rows[$key][$v['delay_occurred_at']]['r_fees'];
                                $temp['r_delayinterest'] += $rows[$key][$v['delay_occurred_at']]['r_delayinterest'];
                                $temp['repayment'] += $rows[$key][$v['delay_occurred_at']]['repayment'];
//                                $rows[$key][$v['delay_occurred_at']] = $this->sumColumn($temp ,$rows[$key][$v['delay_occurred_at']]);;
                            }

                            if($v['delay_occurred_at'] != $v['delay_principal_return_at']){
                                $temp['r_principal'] -= $temp['r_principal'];
                                $temp['r_interest'] -= $temp['r_interest'];
                                $temp['r_fees'] -= $temp['r_fees'];
                                $temp['r_delayinterest'] -= $temp['r_delayinterest'];
                                $temp['repayment'] -= $temp['repayment'];
                            }
                            $rows[$key][$v['delay_occurred_at']] = $temp;
                            $delay_occurred_used = true;
                        }

                        if($v['repayment'] != 0 && $v['repayment_principal'] == 0 && $v['delay_principal_return_at'] != 0){
                            $temp = $v;
                            if(!$delay_occurred_used && isset($rows[$key][$v['delay_principal_return_at']])) {
                                $temp['principal'] += $rows[$key][$v['delay_principal_return_at']]['principal'];
                                $temp['interest'] += $rows[$key][$v['delay_principal_return_at']]['interest'];
                                $temp['damage'] += $rows[$key][$v['delay_principal_return_at']]['damage'];
                                $temp['delay_interest'] += $rows[$key][$v['delay_principal_return_at']]['delay_interest'];
                                $temp['r_principal'] += $rows[$key][$v['delay_principal_return_at']]['r_principal'] - $temp['r_principal'];
                                $temp['r_interest'] += $rows[$key][$v['delay_principal_return_at']]['r_interest'];
                                $temp['r_fees'] += $rows[$key][$v['delay_principal_return_at']]['r_fees'];
                                $temp['r_delayinterest'] += $rows[$key][$v['delay_principal_return_at']]['r_delayinterest'];
                                $temp['repayment'] += $rows[$key][$v['delay_principal_return_at']]['repayment'];
//                              $rows[$key][$v['delay_principal_return_at']] = $this->sumColumn($temp ,$rows[$key][$v['delay_principal_return_at']]);;
                            }
                            $rows[$key][$v['delay_principal_return_at']] = $temp;
                            $v['principal'] -= $v['r_principal'];
                            $v['interest'] -= $v['r_interest'];
                            $v['delay_interest'] -= $v['r_delayinterest'];
                            $v['damage'] -= $v['damage'];
//                          $rows[$key][$v['delay_principal_return_at']]['principal'] = $v['interest'] - $v['r_interest'];
                        }
                    }

                    if (!isset($rows[$key][$v['repayment_date']])) {
                        $rows[$key][$v['repayment_date']] = $this->initRow();
                    }

                    $rows[$key][$v['repayment_date']]['remaining_principal'] += $v['remaining_principal'];
                    $rows[$key][$v['repayment_date']]['r_principal'] += $v['r_principal'];
                    $rows[$key][$v['repayment_date']]['principal'] += $v['principal'];
                    $rows[$key][$v['repayment_date']]['r_interest'] += $v['r_interest'];
                    $rows[$key][$v['repayment_date']]['interest'] += $v['interest'];
                    $rows[$key][$v['repayment_date']]['damage'] += $v['damage'];
                    $rows[$key][$v['repayment_date']]['prepayment_allowance'] += $v['prepayment_allowance'];
                    $rows[$key][$v['repayment_date']]['prepayment_damage'] += $v['prepayment_damage'];
                    $rows[$key][$v['repayment_date']]['delay_interest'] += $v['delay_interest'];
                    $rows[$key][$v['repayment_date']]['ar_fees'] += $v['ar_fees'];
                    $rows[$key][$v['repayment_date']]['r_fees'] += $v['r_fees'];
                    $rows[$key][$v['repayment_date']]['r_delayinterest'] += $v['r_delayinterest'];
                    $rows[$key][$v['repayment_date']]['repayment'] += $v['repayment'];
                }
            }
        }

        return $rows;
    }

    private function initRow()
    {
        return [
            'remaining_principal' => 0,
            'principal' => 0,
            'r_principal' => 0,
            'interest' => 0,
            'r_interest' => 0,
            'damage' => 0,
            'prepayment_allowance' => 0,
            'prepayment_damage' => 0,
            'delay_interest' => 0,
            'ar_fees' => 0,
            'r_fees' => 0,
            'r_delayinterest' => 0,
            'repayment' => 0,
        ];
    }

    public function getTableHeader($tableName)
    {
        if ($tableName == '逾期案') {
            return "<table>{$tableName}<thead><tr><th>日期</th><th>尚欠本金</th><th>尚欠利息</th><th>尚欠本息</th><th>違約金</th><th>延滯息</th><th>當期償還本金</th><th>當期償還利息</th><th>當期償還本息</th><th>當期償還延滯息</th><th>回款手續費</th><th>補貼</th><th>投資回款淨額</th></tr></thead><tbody>";
        }
        return "<table>{$tableName}<thead><tr><th>日期</th><th>當期本金</th><th>當期利息</th><th>本息合計</th><th>本金餘額</th><th>違約金</th><th>延滯息</th><th>當期償還本金</th><th>當期償還利息</th><th>當期償還本息</th><th>當期償還延滯息</th><th>回款手續費</th><th>補貼</th><th>投資回款淨額</th></tr></thead><tbody>";
    }

    public function getEndingTable()
    {
        return '</tbody></table>';
    }

    public function getSupportedTables()
    {
        return [
            'normal' => '正常案',
            'overdue' => '逾期案',
            'prepayment' => '提前清償',
        ];
    }

    public function toExcel($rows)
    {
        header('Content-type:application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=repayment_schedule_' . date('Ymd') . '.xls');
        $tables = $this->getSupportedTables();
        foreach ($tables as $type => $tableName) {
            $html = $this->getTableHeader($tableName);

            if (!isset($rows[$type]) || !$rows[$type]) {
                $html .= $this->getEndingTable();
                echo $html;
                echo "<br><br>";
                continue;
            }

            ksort($rows[$type]);
            $reportType = $type;
            if ($type == 'prepayment') {
                $reportType = 'normal';
            }
            $reportType = ucfirst($reportType);
            $functionName = "to{$reportType}";
            $html = $this->$functionName($html, $rows, $type);

            $html .= $this->getEndingTable();
            echo $html;
            echo "<br><br>";
        }
    }

    private function toNormal($html, $rows, $type)
    {
        $isFirst = true;
        foreach ($rows[$type] as $key => $value) {
            if (
                $type != 'prepayment' && substr($key, -2) == '10'
                || $type == 'prepayment'
                || $isFirst
            ) {
                $total = $value['r_principal'] + $value['r_interest'];
                $r_fee = $value['r_fees'];
                $profit = $value['repayment'] - $r_fee;
                $html .= '<tr>';
                $html .= '<td>' . $key . '</td>';
                $html .= '<td>' . $value['principal'] . '</td>';
                $html .= '<td>' . $value['interest'] . '</td>';
                $html .= '<td>' . ($value['principal'] + $value['interest']) . '</td>';
                $html .= '<td>' . $value['remaining_principal'] . '</td>';
                $html .= '<td>' . ($value['damage'] + $value['prepayment_damage']) . '</td>';
                $html .= '<td>' . $value['delay_interest'] . '</td>';
                $html .= '<td>' . $value['r_principal'] . '</td>';
                $html .= '<td>' . $value['r_interest'] . '</td>';
                $html .= '<td>' . $total . '</td>';
                $html .= '<td>' . $value['r_delayinterest'] . '</td>';
                $html .= '<td>' . $value['r_fees'] . '</td>';
                if ($type == 'prepayment') {
                    $html .= '<td>' . $value['prepayment_allowance'] . '</td>';
                } else {
                    $html .= '<td>0</td>';
                }
                $html .= '<td>' . $profit . '</td>';
                $html .= '</tr>';
            }
            $isFirst = false;
        }
        return $html;
    }

    private function toOverdue($html, $rows, $type)
    {
        $isFirst = true;
        foreach ($rows[$type] as $key => $value) {
            if (
                $type != 'prepayment' && substr($key, -2) == '10'
                || $type == 'prepayment'
                || $type == 'overdue'
                || $key == date('Y-m-d', time())
                || $isFirst
            ) {
                if($value['principal'] == 0 && $value['r_principal'] == 0 && substr($key, -2) != '10'){
                    continue;
                }
                $total = $value['r_principal'] + $value['r_interest'];
                $r_fee = $value['r_fees'];
                $profit = $value['repayment'] - $r_fee;
                $html .= '<tr>';
                $html .= '<td>' . $key . '</td>';
                $html .= '<td>' . $value['principal'] . '</td>';
                $html .= '<td>' . $value['interest'] . '</td>';
                $html .= '<td>' . ($value['principal'] + $value['interest']) . '</td>';
                $html .= '<td>' . ($value['damage'] + $value['prepayment_damage']) . '</td>';
                $html .= '<td>' . $value['delay_interest'] . '</td>';
                $html .= '<td>' . $value['r_principal'] . '</td>';
                $html .= '<td>' . $value['r_interest'] . '</td>';
                $html .= '<td>' . $total . '</td>';
                $html .= '<td>' . $value['r_delayinterest'] . '</td>';
                $html .= '<td>' . $value['r_fees'] . '</td>';
                if ($type == 'prepayment') {
                    $html .= '<td>' . $value['prepayment_allowance'] . '</td>';
                } else {
                    $html .= '<td>0</td>';
                }
                $html .= '<td>' . $profit . '</td>';
                $html .= '</tr>';
                $isFirst = false;
            }
        }
        return $html;
    }
    private function sumColumn($a, $b){
        $result = [];
        foreach($a as $key => $value){
            $result[$key] = array_sum(array_column([$a ,$b],$key));
        }
        return $result;
    }
}
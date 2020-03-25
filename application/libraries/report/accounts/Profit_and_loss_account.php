<?php

class Profit_and_loss_account
{
    public function generateTotalReport($investementIds)
    {
        $investments = $this->investment_model->order_by('target_id', 'ASC')->get_many($ids);

        $rows = ['normal' => [], 'overdue' => []];
        if (!$investments) {
            return [];
        }

        foreach ($investments as $key => $value) {
            $target = $this->target_model->get($value->target_id);
            $amortizationTables = $this->target_lib->get_investment_amortization_table_v2($target, $value);
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
            foreach ($amortizationTables as $key => $value) {
                $currentRows = $rvalue['rows'];
                if ($isPrepayment && $key == 'normal') {
                    $key = 'prepayment';
                }

                foreach ($currentRows as $k => $v) {
                    if (!$v['repayment_date']) {
                        continue;
                    }
                    if (!isset($rows[$key][$v['repayment_date']])) {
                        $rows[$key][$v['repayment_date']] = array(
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
                        );
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

    public function getTableHeader($tableName)
    {
        return "<table>{$tableName}<thead><tr><th>還款日</th><th>本金金額</th><th>本金餘額</th><th>當期利息</th><th>本息合計</th><th>違約金</th><th>延滯息</th><th>當期償還本息</th><th>回款手續費</th><th>補貼</th><th>投資回款淨額</th></tr></thead><tbody>";
    }

    public function getEndingTable()
    {
        return '</tbody></table>';
    }

    public function toExcel()
    {
        header('Content-type:application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=repayment_schedule_' . date('Ymd') . '.xls');
        foreach (['normal', 'overdue', 'prepayment'] as $type) {
            if ($type == 'normal') {
                $tableName = '正常案';
            } elseif ($type == 'overdue') {
                $tableName = '逾期案';
            } else {
                $tableName = '提前清償';
            }
            $html = $this->getTableHeader();
            ksort($rows[$type]);
            foreach ($rows[$type] as $key => $value) {
                if ($type != 'prepayment' && substr($key, -2) == '10' || $type == 'prepayment') {
                    $total = $value['r_principal'] + $value['r_interest'];
                    $r_fee = $value['r_fees'];
                    $profit = $value['repayment'] - $r_fee;
                    $html .= '<tr>';
                    $html .= '<td>' . $key . '</td>';
                    $html .= '<td>' . $value['principal'] . '</td>';
                    $html .= '<td>' . $value['remaining_principal'] . '</td>';
                    $html .= '<td>' . $value['interest'] . '</td>';
                    $html .= '<td>' . ($value['principal'] + $value['interest']) . '</td>';
                    $html .= '<td>' . ($value['damage'] + $value['prepayment_damage']) . '</td>';
                    $html .= '<td>' . $value['r_delayinterest'] . '</td>';
                    $html .= '<td>' . $total . '</td>';
                    $html .= '<td>' . $value['r_fees'] . '</td>';
                    if ($type == 'prepayment') {
                        $html .= '<td>' . $value['prepayment_allowance'] . '</td>';
                    } else {
                        $html .= '<td>0</td>';
                    }
                    $html .= '<td>' . $profit . '</td>';
                    $html .= '</tr>';
                }
            }
            $html .= $this->getEndingTable();
            echo $html;
        }
    }
}
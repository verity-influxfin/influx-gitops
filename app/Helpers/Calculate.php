<?php

namespace App\Helpers;

class Calculate
{
	/**
     * [PMT_calculate 本息均攤公式]
     * @param integer $rate [年利率]
     * @param integer $nper [總期數]
     * @param integer $pv   [金額]
     */
	public function PMT_calculate(int $rate = 0,int $nper = 0,int $pv = 0){
		if($nper == 0 || $pv == 0){
			return '期數與貸款金額不得為0';
		}
		$month_rate = $rate / 100 / 12;
		$months = $nper * 12;
		$response = pow((1 + $month_rate),$months) * $month_rate / (pow((1 + $month_rate),$months) -1) * $pv;
		return $response;
	}
}

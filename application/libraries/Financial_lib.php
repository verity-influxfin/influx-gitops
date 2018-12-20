<?php

defined('BASEPATH') OR exit('No direct script access allowed');

define('FINANCIAL_ACCURACY', 1.0e-6);
define('FINANCIAL_MAX_ITERATIONS', 100);
	
class Financial_lib{
	
	public function get_amortization_schedule($amount=0,$instalment=0,$rate=0,$date="",$repayment_type=1){
		if($amount && $instalment && $rate && $repayment_type){
			$date 	= empty($date)?date("Y-m-d"):$date;
			$method	= 'amortization_schedule_'.$repayment_type;
			if(method_exists($this, $method)){
				$rs = $this->$method($amount,$instalment,$rate,$date);
				return $rs;
			}
		}
		return false;
	}

	//取得攤還表 - 等額本息
	private function amortization_schedule_1($amount,$instalment,$rate,$date){

		$total_payment 		= $this->PMT($rate,$instalment,$amount);
		if($total_payment){
			$xirr_dates		= array($date);
			$xirr_value		= array($amount*(-1));
			//驗證閏年
			$leap_year	= $this->leap_year($date,$instalment);
			$year_days = $leap_year?366:365;//今年日數
			$schedule	= array(
				"amount"		=> $amount,
				"instalment"	=> $instalment,
				"rate"			=> $rate,
				"date"			=> $date,
				"total_payment"	=> $total_payment,
				"leap_year"		=> $leap_year,
				"year_days"		=> $year_days
			);
			
			$list 		= array();
			$t_amount 	= $t_interest = $t_min = 0;
			for( $i=1; $i <= $instalment; $i++ ){
				$odate 		= $date;
				//還款日
				$ym 		= date("Y-m",strtotime($date));
				$date 		= date("Y-m-",strtotime($ym." + 1 month")).REPAYMENT_DAY;
				if($i==1 && $odate > date("Y-m-",strtotime($odate)).REPAYMENT_DAY){
					$date 		= date("Y-m-",strtotime($date." + 1 month")).REPAYMENT_DAY;
				}
				//本期日數
				$days  		= get_range_days($odate,$date);
				//本期利息 = 年利率/年日數*本期日數=本期利率
				$interest 	= round( $amount * $rate / 100 * $days / $year_days ,0);
				//本期本金
				$principal	= $total_payment - $interest;
				
				//最後一期本金
				if($i==$instalment){
					$principal = $schedule['amount'] - $t_amount;
				}
				
				$total_payment = $interest + $principal;
				$t_interest	+= $interest;
				$t_amount	+= $principal;
				$t_min		+= $interest + $principal;
				
				$list[$i] = array(	
					"instalment"			=> $i,
					"repayment_date"		=> $date,
					"days"					=> $days,
					"remaining_principal"	=> $amount,
					"principal"				=> $principal,
					"interest"				=> $interest,
					"total_payment"			=> $total_payment,
				);	
				$xirr_dates[] = $date;
				$xirr_value[] = $total_payment;
				$amount = $amount - $principal;
			}

			$schedule['XIRR']		= $this->XIRR($xirr_value,$xirr_dates);
			$schedule['schedule'] 	= $list;
			$schedule['total'] 		= array(
				"principal"		=> $t_amount,
				"interest"		=> $t_interest,
				"total_payment"	=> $t_min,
			);
			return $schedule;
		}
		return false;
	}
	
	//取得攤還表 - 先息後本
	private function amortization_schedule_2($amount,$instalment,$rate,$date){

		$xirr_dates		= array($date);
		$xirr_value		= array($amount*(-1));
		//驗證閏年
		$leap_year	= $this->leap_year($date,$instalment);
		$year_days = $leap_year?366:365;//今年日數
		$schedule	= array(
			"amount"		=> $amount,
			"instalment"	=> $instalment,
			"rate"			=> $rate,
			"date"			=> $date,
			"total_payment"	=> "",
			"leap_year"		=> $leap_year,
			"year_days"		=> $year_days
		);
		
		$list 		= array();
		$t_amount 	= $t_interest = $t_min = 0;
		for( $i=1; $i <= $instalment; $i++ ){
			$odate 		= $date;
			//還款日
			$ym 		= date("Y-m",strtotime($date));
			$date 		= date("Y-m-",strtotime($ym." + 1 month")).REPAYMENT_DAY;
			if($i==1 && $odate > date("Y-m-",strtotime($odate)).REPAYMENT_DAY){
				$date 		= date("Y-m-",strtotime($date." + 1 month")).REPAYMENT_DAY;
			}

			//本期日數
			$days  		= get_range_days($odate,$date);
			//本期利息 = 年利率/年日數*本期日數=本期利率
			$interest 	= round( $amount * $rate / 100 * $days / $year_days ,0);
			//本期本金
			$principal	= 0;
			
			//最後一期本金
			if($i==$instalment){
				$principal = $schedule['amount'] - $t_amount;
			}
			
			$total_payment = $interest + $principal;
			$t_interest	+= $interest;
			$t_amount	+= $principal;
			$t_min		+= $interest + $principal;
			
			$list[$i] = array(	
				"instalment"			=> $i,
				"repayment_date"		=> $date,
				"days"					=> $days,
				"remaining_principal"	=> $amount,
				"principal"				=> $principal,
				"interest"				=> $interest,
				"total_payment"			=> $total_payment,
			);	
			$xirr_dates[] = $date;
			$xirr_value[] = $total_payment;
			$amount = $amount - $principal;
		}
		
		$schedule['XIRR']		= $this->XIRR($xirr_value,$xirr_dates);
		$schedule['schedule'] 	= $list;
		$schedule['total'] 		= array(
			"principal"		=> $t_amount,
			"interest"		=> $t_interest,
			"total_payment"	=> $t_min,
		);
		return $schedule;
	}
	
	public function leap_year($date="",$instalment=0){
		if($date && $instalment){
			//驗證閏年
			$leap_year	= FALSE;
			for($i=1;$i<=$instalment;$i++){
				$sdate 	= date("Y-m-d",strtotime($date));
				$date 	= date("Y-m-d",strtotime($date." + 1 month"));
				if(date("L",strtotime($sdate))=="1" || date("L",strtotime($date))=="1"){
					$cdate = date("Y",strtotime($date)).'-02-29';
					if( $sdate <= $cdate && $date >= $cdate ){
						$leap_year = TRUE;
					}
				}
			}
			return $leap_year;
		}
		return false;
	}

	public function get_liquidated_damages($remaining_principal=0,$damage_rate=0){
		if($remaining_principal){
			$damage_rate = $damage_rate?$damage_rate:LIQUIDATED_DAMAGES;
			return intval(round($remaining_principal*$damage_rate/100,0));
		}
		return 0;
	}
	
	public function get_delay_interest($remaining_principal=0,$delay_days=0){
		if($remaining_principal && $delay_days > GRACE_PERIOD){
			return intval(round($remaining_principal*DELAY_INTEREST*$delay_days/100,0));
		}
		return 0;
	}
	
	public function get_interest_by_days($days=0,$principal=0,$instalment=0,$rate=0,$date=""){
		$interest = 0;
		if($days && $principal && $instalment && $rate){
			$leap_year	= $this->leap_year($date,$instalment);
			$year_days 	= $leap_year?366:365;//今年日數
			$interest 	= round( $principal * $rate / 100 * $days / $year_days ,0);
		}
		return $interest;
	}
	
	private function PMT($rate=0,$instalment=0,$amount=0)
	{
		if($amount && $instalment && $rate){
			$mrate 	 		= $rate/1200;//月利率
			$mtotal			= 1+$mrate;// 1+月利率
			$total_payment 	= ceil($amount*$mrate*pow($mtotal,$instalment)/(pow($mtotal,$instalment)-1));
			return intval($total_payment);
		}
		
		return false;
	}
	
	private function XNPV($rate, $values, $dates)
	{
		if ((!is_array($values)) || (!is_array($dates))) return null;
		if (count($values) != count($dates)) return null;
		
		$xnpv = 0.0;
		for ($i = 0; $i < count($values); $i++){
			$days = floor((strtotime($dates[$i]) - strtotime($dates[0]))/(60*60*24));
			$xnpv += $values[$i] / pow(1 + $rate, $days / 365);
		}
		return is_finite($xnpv) ? $xnpv: false ;
	}

	public function XIRR($values, $dates, $guess = 0.1)
	{
		if ((!is_array($values)) && (!is_array($dates))) return false;
		if (count($values) != count($dates)) return false;
		
		// create an initial bracket, with a root somewhere between bot and top
		$x1 = 0.0;
		$x2 = $guess;
		$f1 = $this->XNPV($x1, $values, $dates);
		$f2 = $this->XNPV($x2, $values, $dates);
		
		for ($i = 0; $i < FINANCIAL_MAX_ITERATIONS; $i++){
			
			if (($f1 * $f2) < 0.0) break;
			if (abs($f1) < abs($f2)) {
				$f1 = $this->XNPV($x1 += 1.6 * ($x1 - $x2), $values, $dates);
			} else {
				$f2 = $this->XNPV($x2 += 1.6 * ($x2 - $x1), $values, $dates);
			}
		}
		if (($f1 * $f2) > 0.0) return false;
		
		$f = $this->XNPV($x1, $values, $dates);
		if ($f < 0.0) {
			$rtb 	= $x1;
			$dx 	= $x2 - $x1;
		} else {
			$rtb 	= $x2;
			$dx 	= $x1 - $x2;
		}
		
		for ($i = 0;  $i < FINANCIAL_MAX_ITERATIONS; $i++){
			$dx *= 0.5;
			$x_mid = $rtb + $dx;
			$f_mid = $this->XNPV($x_mid, $values, $dates);
			if ($f_mid <= 0.0) $rtb = $x_mid;
			if ((abs($f_mid) < FINANCIAL_ACCURACY) || (abs($dx) < FINANCIAL_ACCURACY)) return round($x_mid ,4)*100;
		}
		return false;
	}
	
	public function get_tax_amount($total=0){
		if($total > 10){
			return intval($total - round($total*100/(100+TAX_RATE),0));
		}
		return 0;
	}

}

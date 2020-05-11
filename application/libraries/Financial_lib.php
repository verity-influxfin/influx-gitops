<?php

defined('BASEPATH') OR exit('No direct script access allowed');

define('FINANCIAL_ACCURACY', 1.0e-6);
define('FINANCIAL_MAX_ITERATIONS', 100);
	
class Financial_lib{

    public function __construct()
    {
        $this->CI = &get_instance();
    }

	public function get_amortization_schedule($amount=0,$target=[],$date='',$prepayment=false){
        $instalment = $target->instalment;
        $rate = $target->interest_rate;
        $repayment_type = $target->repayment;

        $product_list = $this->CI->config->item('product_list');
        $product = $product_list[$target->product_id];
        $sub_product_id = $target->sub_product_id;
        if($this->is_sub_product($product,$sub_product_id)){
            $product = $this->trans_sub_product($product,$sub_product_id);
        }

        isset($target->user_id)?$product['user_id']=$target->user_id:'';

		if($amount && $instalment && $rate && $repayment_type){
			$date 	= empty($date)?get_entering_date():$date;
			$method	= 'amortization_schedule_'.$repayment_type;
			if(method_exists($this, $method)){
				$rs = $this->$method($amount,$instalment,$rate,$date,$product,$prepayment);
				return $rs;
			}
		}
		return false;
	}

	//取得攤還表 - 等額本息
	private function amortization_schedule_1($amount,$instalment,$rate,$date,$product,$prepayment=false){
        $product_type = $product['type'];
		$amount 	= intval($amount);
		$instalment = intval($instalment);
		$rate 		= floatval($rate);
		$first_total_payment = 0;
		$total_payment 		 = $this->PMT($rate,$instalment,$amount);
		if($total_payment){
			$xirr_dates		= array($date);
			$xirr_value		= array($amount*(-1));
			//驗證閏年
			$leap_year	= $this->leap_year($date,$instalment);
			$year_days = $leap_year?366:365;//今年日數
			$schedule	= [
				'amount'		=> $amount,
				'instalment'	=> $instalment,
				'rate'			=> $rate,
				'date'			=> $date,
				'total_payment'	=> $total_payment,
				'leap_year'		=> $leap_year,
				'year_days'		=> $year_days
			];
			
			$list 		= array();
			$t_amount 	= $t_interest = $t_min = 0;
			for( $i=1; $i <= $instalment; $i++ ){
				$odate 		= $date;
				//還款日
				$ym 		= date('Y-m',strtotime($date));
				$date 		= date('Y-m-',strtotime($ym.' + 1 month')).REPAYMENT_DAY;
				if($i==1 && $odate > date('Y-m-',strtotime($odate)).REPAYMENT_DAY){
					$date 		= date('Y-m-',strtotime($date.' + 1 month')).REPAYMENT_DAY;
				}
				//本期日數
				$days  		= $product_type == 1?get_range_days($odate,$date):30;
				//本期利息 = 年利率/年日數*本期日數=本期利率
				$interest 	= round( $amount * $rate / 100 * $days / $year_days ,0);
				//本期本金
				$principal	= $total_payment - $interest;
				
				//最後一期本金
                if($i==$instalment){
					$principal = $schedule['amount'] - $t_amount;
				}

                //消費貸
                $i==1?$first_total_payment=$total_payment:0;
                if($i==$instalment && $product_type == 2){
                    $interest += $first_total_payment - ($interest + $principal);
                }

                $t_interest	+= $interest;
                $t_amount	+= $principal;
                $t_min		+= $interest + $principal;
                $total_payment = $interest + $principal;

                $list[$i] = array(
					'instalment'			=> $i,
					'repayment_date'		=> $date,
					'days'					=> $days,
					'remaining_principal'	=> $amount,
					'principal'				=> $principal,
					'interest'				=> $interest,
					'total_payment'			=> $total_payment,
				);
				$xirr_dates[] = $date;
				$xirr_value[] = $total_payment;
				$amount = $amount - $principal;
			}

			$schedule['XIRR']		= $this->XIRR($xirr_value,$xirr_dates);
			$schedule['schedule'] 	= $list;
			$schedule['total'] 		= array(
				'principal'		=> $t_amount,
				'interest'		=> $t_interest,
				'total_payment'	=> $t_min,
			);
			return $schedule;
		}
		return false;
	}
	
	//取得攤還表 - 先息後本
	private function amortization_schedule_2($amount,$instalment,$rate,$date,$product,$prepayment=false){
		$xirr_dates		= array($date);
		$xirr_value		= array($amount*(-1));
		//驗證閏年
		$leap_year	= $this->leap_year($date,$instalment);
		$year_days = $leap_year?366:365;//今年日數
		$schedule	= array(
			'amount'		=> $amount,
			'instalment'	=> $instalment,
			'rate'			=> $rate,
			'date'			=> $date,
			'total_payment'	=> '',
			'leap_year'		=> $leap_year,
			'year_days'		=> $year_days
		);
		
		$list 		= array();
		$t_amount 	= $t_interest = $t_min = 0;
		for( $i=1; $i <= $instalment; $i++ ){
			$odate 		= $date;
			//還款日
			$ym 		= date('Y-m',strtotime($date));
			$date 		= date('Y-m-',strtotime($ym.' + 1 month')).REPAYMENT_DAY;
			if($i==1 && $odate > date('Y-m-',strtotime($odate)).REPAYMENT_DAY){
				$date 		= date('Y-m-',strtotime($date.' + 1 month')).REPAYMENT_DAY;
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
				'instalment'			=> $i,
				'repayment_date'		=> $date,
				'days'					=> $days,
				'remaining_principal'	=> $amount,
				'principal'				=> $principal,
				'interest'				=> $interest,
				'total_payment'			=> $total_payment,
			);	
			$xirr_dates[] = $date;
			$xirr_value[] = $total_payment;
			$amount = $amount - $principal;
		}
		
		$schedule['XIRR']		= $this->XIRR($xirr_value,$xirr_dates);
		$schedule['schedule'] 	= $list;
		$schedule['total'] 		= array(
			'principal'		=> $t_amount,
			'interest'		=> $t_interest,
			'total_payment'	=> $t_min,
		);
		return $schedule;
	}

    private function amortization_schedule_3($amount,$instalment,$rate,$date,$product,$prepayment=false)
    {
        $user_info = isset($this->CI->user_info->id)?$this->CI->user_info->id:0;
        $visul_id = $product['visul_id'];
        //驗證閏年
        $leap_year = $this->leap_year($date, $instalment);
        $year_days = $leap_year ? 366 : 365;//今年日數
        $schedule = [
            'amount' => $amount,
            'instalment' => $instalment,
            'rate' => $rate,
            'date' => $date,
            'total_payment' => 0,
            'leap_year' => $leap_year,
            'year_days' => $year_days
        ];

        $list = array();
        $total_payment = 0;

        $amount = intval($amount);
        $instalment = intval($instalment);
        $rate = floatval($rate);
        $platformFee = 0;
        $shareRate = 0;
        $startAt = 1;

        $this->CI->load->library('entity/amortization/Foreign_exchange_car_amortization_schedule_setting', [], 'amortization_setting');
        $inputSetting = $this->CI->amortization_setting;
        $inputSetting->setUseGenerate(false);
        $inputSetting->setLength($instalment);
        $inputSetting->setInterests($rate * 0.01);
        $inputSetting->setPlatformProportion($platformFee * 0.01);
        $inputSetting->setShareRate($shareRate * 0.01);
        $inputSetting->setYearDays($year_days);

        if (in_array($visul_id, ['DS1P1', 'DS2P1'])) {
            $shareRate = FEV_SHARE_RATE;
            if ($visul_id == 'DS1P1') {
                $inputSetting->setUseGenerate(true);
            }elseif ($visul_id == 'DS2P1') {
                $inputSetting->setShareRate($prepayment['sold'] * 0.01);
            }
            $prepayment ? $shareRate = FEV_PREPAYMENT_SHARE_RATE : '';
        }

        $this->CI->load->library('entity/amortization/Foreign_exchange_car_amortization_schedule_loan', [], "loan1");
        $loanStage1 = $this->CI->loan1;
        $loanStage1->setStartAt($startAt);
        $loanStage1->setAmount($amount);

        $this->CI->load->library('Foreign_exchange_car_lib');
        $day_amortization_schedule = $this->CI->foreign_exchange_car_lib->amortization_schedule([$loanStage1], $inputSetting);

        if ($visul_id == 'DS2P1') {
            $max_instalment = $instalment;
            $odate = $date;
            //還款日
            $ym = date('Y-m', strtotime($date));
            $d = date('Y-m-d', strtotime($date));
            $date = date('Y-m-', strtotime($ym)) . REPAYMENT_DAY;
            $last_day = date('Y-m-d', strtotime($d . ' + ' . $max_instalment . ' day'));
            if ($d >= $date) {
                $date = date('Y-m-', strtotime($date . ' + 1 month')) . REPAYMENT_DAY;
            }
            $pay_day[get_range_days($odate, $date)] = $date;
            for ($i = 1; $i <= $max_instalment; $i++) {
                $date = date('Y-m-', strtotime($date . ' + 1 month')) . REPAYMENT_DAY;
                $rangeDays = get_range_days($odate, $date);
                $max_instalment > $rangeDays ? $pay_day[$rangeDays] = $date : false;
                $i = $rangeDays;
            }
            $date != $last_day ? $pay_day[$max_instalment] = $last_day : '';
        }
        $total_interest = 0;
        $all_total_payment = 0;
        $past = 0;
        if($prepayment){
            $days = $prepayment['days'];
            if($days>0){
                $share = $day_amortization_schedule->getRows()[$days - 1]->getShare();
                $total_interest = ($day_amortization_schedule->getRows()[$days - 1]->getAnnualReturns()[0]->getFee());
            }
            else{
                $share = $day_amortization_schedule->getRows()[$days]->getShare();
                $total_interest = 0;
            }
            $all_total_payment = $total_interest + $share + $amount;
        }
        else{
            foreach ($pay_day as $pdKey => $pdValue) {
                $interest = ($day_amortization_schedule->getRows()[$pdKey - 1]->getAnnualReturns()[0]->getFee())-$total_interest;
                $total_interest += $interest;
                $share = $day_amortization_schedule->getRows()[$pdKey - 1]->getShare();
                $total_payment = ($pdKey == $max_instalment
                    ? $product['user_id'] == $user_info ? $interest + $share + $amount : $interest + $amount
                    : $interest);
                $all_total_payment += $total_payment;
                $list[$pdKey] = array(
                    'instalment' => $pdKey,
                    'repayment_date' => $pdValue,
                    'days' => $pdKey-$past,
                    'remaining_principal' => $amount,
                    'principal' => ($pdKey==$max_instalment?$amount:0),
                    'interest' => $interest,
                    'total_payment' => $total_payment,
                );
                $product['user_id']==$user_info && $pdKey == $max_instalment?$list[$pdKey]['share'] = $share:'';
                $past = $pdKey;
            }
        }

        $schedule['schedule'] = $list;
        $schedule['total'] = array(
            'principal' => $amount,
            'interest' => $total_interest,
            'total_payment' => $all_total_payment,
        );
        $product['user_id']==$user_info?$schedule['total']['share'] = $share:'';
        return $schedule;
    }

	public function leap_year($date='',$instalment=0){
        if (!$date || !$instalment) {
            return false;
        }

        if($instalment > 96)return true;

        $current = explode("-",$date);

        $cy = $current[0];
        $cm = $current[1];
        $cd = $current[2];

        $isLeapDay = $cm == 2 && $cd >= 29;
        $thisY = $cy;

        if($thisY%4 == 0){
            if($thisY%100 == 0){
                if($thisY%400 == 0){
                    if($this->checkCurrentLeap($instalment,$cm,$cd))return true;
                }
            }else{
                if($this->checkCurrentLeap($instalment, $cm, $cd))return true;
            }
        }

        $nextLeapInc = $thisY%4 == 0 ? 4 : (4-$thisY%4);
        $_nextLeaY = $nextLeapInc + $cy;
        if($_nextLeaY%400 != 0 && $_nextLeaY%100 == 0)$nextLeapInc = $nextLeapInc*1 + 4;
        $checkMLess = $cm%12;
        $nextLMs = ($checkMLess == 0 ? 0 : (12-$checkMLess)) + ($nextLeapInc == 0 ? 0 : $nextLeapInc - 1)*12 + 2;
        if($nextLMs > $instalment)return false;
        if($nextLMs < $instalment)return true;
        if($instalment>48){
            if($nextLeapInc>4 || ($cy%400 != 0 && $cy%100 == 0)){
                $_nextLeaY = $nextLeapInc + $cy;
                $_y = $cy + floor(($cm+$instalment)/12);
                $_m = ($cm+$instalment)%12 == 0 ? 1 : ($cm+$instalment)%12;
                if($_y>$_nextLeaY)return true;
                if($_y<$_nextLeaY)return false;
                if($_m==1)return false;
                if($_m>2)return true;
                return $cd >= 29;
            }else{
                return true;
            }
        }

        $nextLeapY = $thisY + $nextLeapInc;
        if($nextLeapY%4 == 0){
            if($nextLeapY%100 == 0){
                if($nextLeapY%400 == 0){
                    return $this->checkNextMD($thisY, $instalment,$nextLeapY,$cm, $cd);
                }else{
                    if($isLeapDay)return false;
                }
            }else{
                return $this->checkNextMD($thisY, $instalment,$nextLeapY, $cm, $cd);
            }
        }else if($isLeapDay){
            return false;
        }
        return false;
	}

    public function checkNextMD($thisY, $instalment,$nextLeapY, $m , $d ){
        $nextY = $thisY;
        if($m+$instalment>12){
            $_m = $instalment-($m == 12 ? 0 : (12-$m%12));
            $nextY = $thisY + floor($_m/12) + ($_m%12 > 0 ? 1 : 0);
        }

        if($nextY>$nextLeapY){
            return true;
        }else if($nextY<$nextLeapY){
            return false;
        }else{
            $_m2 = ($m+$instalment)%12;
            if($_m2==1){
                return false;
            }else if($_m2>2){
                return true;
            }else{
                return $d>=29;
            }
        }
    }

    public function checkCurrentLeap($instalment , $m , $d ){
        if($m>2){
            return false;
        }else{
            $_m = $m+$instalment;
            if($_m>2){
                return true;
            }else if($_m==2){
                return $d>=29;
            }else{
                return false;
            }
        }
    }

	public function get_liquidated_damages($remaining_principal=0,$damage_rate=0){
		if($remaining_principal){
			$damage_rate = $damage_rate?$damage_rate:LIQUIDATED_DAMAGES;
			return intval(round($remaining_principal*$damage_rate/100,0));
		}
		return 0;
	}

    public function get_platform_fee($price = 0, $platform_fees = PLATFORM_FEES)
    {
        if ($price) {
            $platform_fee = intval(round($price / 100 * $platform_fees, 0));
            return $platform_fee > PLATFORM_FEES_MIN ? $platform_fee : PLATFORM_FEES_MIN;
        }
        return 0;
    }

    public function get_platform_fee2($price = 0, $platform_fees = PLATFORM_FEES)
    {
        if ($price) {
            $platform_fee = intval(round($price * $platform_fees / (100 - PLATFORM_FEES), 0));
            return $platform_fee > PLATFORM_FEES_MIN ? $platform_fee : PLATFORM_FEES_MIN;
        }
        return 0;
    }

    public function get_transfer_fee($price=0){
        if($price){
            return intval(round($price *DEBT_TRANSFER_FEES/100,0));
        }
        return 0;
    }

    public function get_ar_fee($price=0){
        if($price){
            return intval(round($price/100*REPAYMENT_PLATFORM_FEES,0));
        }
        return 0;
    }
    public function get_delay_interest($remaining_principal=0,$delay_days=0){
		if($remaining_principal && $delay_days > GRACE_PERIOD){
			return intval(round($remaining_principal*DELAY_INTEREST*$delay_days/100,0));
		}
		return 0;
	}
	
	public function get_interest_by_days($days=0,$principal=0,$instalment=0,$rate=0,$date=''){
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

	private function get_AnnualReturns($PDkey, $PDvalue, $amount){
        $interest = $PDvalue->getAnnualReturns()[0]->getFee();
        $platform = $PDvalue->getAnnualReturns()[0]->getPlatform();
        $share = $PDvalue->getShare();
        $total_payment = $interest + $platform + $share + $amount;

        $list[$PDkey] = array(
            'instalment'			=> $PDkey+1,
            'repayment_date'		=> [],
            'days'					=> $PDkey+1,
            'remaining_principal'	=> $amount,
            'principal'				=> $amount,
            'interest'				=> $interest,
            'total_payment'			=> $total_payment,
        );
    }

    private function is_sub_product($product,$sub_product_id){
        $sub_product_list = $this->CI->config->item('sub_product_list');
        return isset($sub_product_list[$sub_product_id]['identity'][$product['identity']]) && in_array($sub_product_id,$product['sub_product']);
    }

    private function trans_sub_product($product,$sub_product_id){
        $sub_product_list = $this->CI->config->item('sub_product_list');
        $sub_product_data = $sub_product_list[$sub_product_id]['identity'][$product['identity']];
        $product = $this->sub_product_profile($product,$sub_product_data);
        return $product;
    }

    private function sub_product_profile($product,$sub_product){
        return array(
            'id' => $product['id'],
            'visul_id' => $sub_product['visul_id'],
            'type' => $product['type'],
            'identity' => $product['identity'],
            'name' => $sub_product['name'],
            'description' => $sub_product['description'],
            'loan_range_s' => $sub_product['loan_range_s'],
            'loan_range_e' => $sub_product['loan_range_e'],
            'interest_rate_s' => $sub_product['interest_rate_s'],
            'interest_rate_e' => $sub_product['interest_rate_e'],
            'charge_platform' => $sub_product['charge_platform'],
            'charge_platform_min' => $sub_product['charge_platform_min'],
            'certifications' => $sub_product['certifications'],
            'instalment' => $sub_product['instalment'],
            'repayment' => $sub_product['repayment'],
            'targetData' => $sub_product['targetData'],
            'dealer' => $sub_product['dealer'],
            'multi_target' => $sub_product['multi_target'],
            'status' => $sub_product['status'],
        );
    }
}

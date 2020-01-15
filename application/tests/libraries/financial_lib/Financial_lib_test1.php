<?php

class Financial_lib_test1 extends TestCase
{
	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->library('Financial_lib');
		$this->financial_lib = $this->CI->financial_lib;
	}

	//original function
	public function leap_year($date='',$instalment=0)
	{
		if($date && $instalment){
			//驗證閏年
			$leap_year	= FALSE;
			for($i=1;$i<=$instalment;$i++){
				$sdate 	= date('Y-m-d',strtotime($date));
				$date 	= date('Y-m-d',strtotime($date.' + 1 month'));
				if(date('L',strtotime($sdate))=='1' || date('L',strtotime($date))=='1'){
					$cdate = date('Y',strtotime($date)).'-02-29';
					if( $sdate <= $cdate && $date >= $cdate ){
						$leap_year = TRUE;
					}
				}
			}
			return $leap_year;
		}
		return false;
	}

	public function testLeapYear()
	{
		$year = 2016;
		$month = 1;
		$day = 1;
		for ($y = 0; $y < 10; $y++) {
			for ($m = 0; $m < 12; $m++) {
				for ($d = 0; $d < 30; $d++) {
					for ($i = 1; $i < 60; $i++) {
						$curYear = $year + $y;
						$curMonth = $month + $m;
						$curDay = $day + $d;
						$inputDate = "{$curYear}-{$curMonth}-{$curDay}";
						if ($m == 2) {
							if (date("L", strtotime($inputDate))) {
								if ($day > 29) {
									continue;
								}
							} else {
								if ($day > 28) {
									continue;
								}
							}
						}
						$original = $this->leap_year($inputDate, $i);
						$new = $this->financial_lib->leap_year($inputDate, $i);
						$this->assertEquals($original, $new);
					}
				}
			}
		}
	}
}

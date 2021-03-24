<?php
class Time
{
	public function __construct()
	{
		$this->CI = &get_instance();
	}

	/**
	 * [ROCDateToUnixTimestamp 民國時間轉時間戳記]
	 * @param  string $ROC_date [民國時間]
	 * {year_number}年{month_number}月{day_number}日 | {year_number}/{month_number}/{day_number} | {year_number}-{month_number}-{day_number}
	 * @return string $unix_time_stamp [時間戳記]
	 */
	public function ROCDateToUnixTimestamp($ROC_date=''){
		$unix_time_stamp = '';
		if(!preg_match("/^[0-9]{3}(年|-|\/)(0?[1-9]|1[012])(月|-|\/)(0?[1-9]|[12][0-9]|3[01])(日?)$/u", $ROC_date)){
			return false;
		}

		$ROC_date = preg_replace('/日/','',$ROC_date);
		$ROC_array = explode('/',preg_replace('/年|月|-/',"/",$ROC_date));
		if(count($ROC_array)==3){
			$year = $ROC_array[0] + 1911;
			$month = $ROC_array[1];
			$day = $ROC_array[2];
			$unix_time_stamp = strtotime("{$year}/{$month}/{$day}");
		}else{
			return false;
		}

		return $unix_time_stamp;
	}
}

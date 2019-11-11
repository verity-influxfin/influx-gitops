<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once (APPPATH . 'libraries/utility/Regular_expression.php');

class Joint_credit_regex extends Regular_expression
{
	const EQUAL_BREAKER = "================================================================================";
	const BREAKER = "--------------------------------------------------------------------------------";

	public function isDateTimeFormat($text)
	{
		preg_match('/\d+\/\d+\/\d+/', $text, $matches);
		return !empty($matches);
	}

	public function isHoursMinutesSecondsFormat($text)
	{
		return preg_match('/\d+:\d+:\d+/', $text, $match) == 1;
	}

	public function getDelayByMonth($text)
	{
		$text = mb_convert_kana($text, 'n');
		preg_match('/(?<=遲延未滿)\d+(?=個月)/', $text, $matches);
		if ($matches) {
			return $matches[0];
		}
		if (preg_match('/遲延6個月以上/', $text)) {
			return 7;
		}
		return 0;
	}

	public function isOverdueOrBadDebits($text)
	{
		return preg_match('/呆帳/', $text) == 1
			   || preg_match('/催收/', $text) == 1;
	}

	public function needFurtherInvestigationForFinishedCase($text)
	{
		$completeStatuses = ['債權已轉讓', '正常結案', '業務移轉', '被併購', '全額清償', '協議清償', '債權拋棄', '併入其他帳單別'];
		$result = false;
		foreach ($completeStatuses as $status) {
			$result = $result || preg_match("/{$status}/", $text) == 1;
		}
		return $result;
	}

	public function isNoDataFound($text)
	{
		return preg_match("/查資料庫中無/", $text, $matches) == 1
			   || preg_match("/無電子支付
機構及電子票證發行機構依法令規定向本中心查詢/", $text) == 1;


	}

	public function replaceEqualBreaker($text)
	{
		return preg_replace("/" . self::EQUAL_BREAKER . "/", "", $text);
	}

	public function removeBreaker($text)
	{
		return preg_replace("/" . self::BREAKER . "/", "", $text);
	}

	public function removeExtraDebtsStopWords($text)
	{
		$text = preg_replace("/^：*\s*/", "", $text);
		return $this->removeBreaker($text);
	}

	public function getZeroOverdueAmount($text)
	{
		return preg_match("/\*\*\*\*\*\*\*0千元/", $text) == 1;
	}

	public function isGuarantor($text)
	{
		return preg_match('/(?<=台端擔任).*(?=之保證人)/', $text) == 1;
	}
}

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once (APPPATH . 'libraries/utility/Regular_expression.php');

class Joint_credit_regex extends Regular_expression
{
	const EQUAL_BREAKER = "================================================================================";
	public function isDateTimeFormat($text)
	{
		preg_match('/\d+\/\d+\/\d+/', $text, $matches);
		return !empty($matches);
	}

	public function isHoursMinutesSecondsFormat($text)
	{
		return preg_match('/\d+:\d+:\d+/', $text, $match) == 1;
	}

	public function isDelayInOneMonth($text)
	{

	}

	public function isNoDataFound($text)
	{
		return preg_match("/查資料庫中無/", $text) == 1;
	}

	public function replaceEqualBreaker($text)
	{
		return preg_replace("/" . self::EQUAL_BREAKER . "/", "", $text);
	}
}

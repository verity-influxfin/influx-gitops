<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once (APPPATH . 'libraries/utility/Regular_expression.php');

class Joint_credit_regex extends Regular_expression
{
	public function isDateTimeFormat($text)
	{
		preg_match('/\d+\/\d+\/\d+/', $text, $matches);
		return !empty($matches);
	}

	public function isDelayInOneMonth($text)
	{

	}
}

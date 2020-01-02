<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once (APPPATH . 'libraries/utility/Regular_expression.php');

class Labor_insurance_regex extends Regular_expression
{
    const APPLICATION_TITLE = '勞工保險異動查詢';

    public function isLaborInsuranceApplication($text)
    {
        return preg_match('/' . self::APPLICATION_TITLE . '/', $text, $match) == 1;
    }

    public function extractDownloadTime($text)
    {
        preg_match_all('/[0-9]+/', $text, $matches);
        return $matches;
    }

    public function extractIdNumber($text)
    {
        preg_match('/[A-Z][1-2][0-9]{8}/', $text, $match);
        return $match;
    }
}
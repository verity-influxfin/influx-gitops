<?php
namespace Utility;

abstract class ViewCoverterBase
{

    /**
     * 把數字轉換為仟元
     * @param $number
     * @return string
     */
    public function thousandUnit($number) : string { return $number; }

    /**
     * 把數字加上 % 符號
     * @param $number
     * @return string
     */
    public function percentSymbol($number) : string { return $number; }

    /**
     * 把日期的斜線轉為年月日
     * @param string
     * @return string
     */
    public function dateFormatToChinese(string $date) : string { return $date; }

    /**
     * 把月加上中文月份單位
     * @param $month
     * @return string
     */
    public function chineseMonthUnit($month) : string { return $month; }
}
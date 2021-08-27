<?php
namespace Utility;

class ViewCoverter extends ViewCoverterBase
{
    public function chineseThousandUnit($number) : string {
        return intval($number)/1000 . "仟";
    }

    public function percentSymbol($number) : string {
        return $number . "%";
    }

    public function dateSlashToChinese(string $date) : string {
        $convertedDate = $date;
        if(preg_match("/^([0-9]+)\/(0[1-9]|1[012])\/(0[1-9]|[12][0-9]|3[01])$/u",
            $date, $matches)) {
            $convertedDate = "$matches[1]年$matches[2]月$matches[3]日";
        }
        return $convertedDate;
    }

    public function chineseMonthUnit($month) :string {
        return $month . "個月";
    }
}
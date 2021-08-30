<?php
namespace Utility;

class ViewCoverter extends ViewCoverterBase
{
    public function thousandUnit($number) : string {
        return is_numeric($number) ? intval($number)/1000 : $number;
    }

    public function percentSymbol($number) : string {
        return $number . "%";
    }

    public function dateFormatToChinese(string $date) : string {
        $originDate = strtotime($date);
        if($originDate !== FALSE) {
            return date('Y年m月d日', strtotime($date));
        }else{
            return $date;
        }
    }

    public function chineseMonthUnit($month) :string {
        return $month . "個月";
    }
}
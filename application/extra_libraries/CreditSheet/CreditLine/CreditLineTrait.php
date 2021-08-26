<?php


namespace CreditSheet\CreditLine;


Trait CreditLineTrait
{
    /**
     * 將期數轉為會計科目的種類名稱
     * @param $instalment
     * @return string
     */
    protected function getBookkeepingType($instalment): string
    {
        if($instalment <= 12)
            return "短放";
        else if($instalment <= 24)
            return "長放";
    }
}
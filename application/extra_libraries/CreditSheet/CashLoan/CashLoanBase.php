<?php
namespace CreditSheet\CashLoan;

interface CashLoanBase
{
    // 取得現存明細資料
    public function getCashLoanInfo() : array;

}
<?php
namespace CreditSheet;

Trait CreditSheetTrait
{
    public $creditSheet;

    public function setCreditSheet($creditSheet) {
        $this->creditSheet = $creditSheet;
    }
}
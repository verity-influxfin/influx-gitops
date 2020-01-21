<?php

class Foreign_exchange_car_lib
{
    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function amortization_schedule($inputs, $setting, $table = null)
    {
        if (!$table) {
            $this->CI->load->library('entity/amortization/foreign_exchange_car_amortization_table', [], 'foreignExchangeTable');
            $table = $this->CI->foreignExchangeTable;
        }

        $this->CI->load->library('entity/amortization/foreign_exchange_car_share_rate_generator', [], 'shareRateGenerator');
        $shareRateGenerator = $this->CI->shareRateGenerator;

        $currentShareRate = 0;
        $numInputs = count($inputs);
        $daysOfTheYear = $setting->getYearDays();
        $instalment = $setting->getLength();
        for ($i = 1; $i <= $instalment; $i++) {
            $shareBase = 0;
            $this->CI->load->library('entity/amortization/foreign_exchange_car_row', [], "row{$i}");
            $rowObject = "row{$i}";
            $row = $this->CI->$rowObject;
            for ($j = 0; $j < $numInputs; $j++) {
                $input = $inputs[$j];
                if ($input->getStartAt() <= $i) {
                    $this->CI->load->library('entity/amortization/annual_return', [], "annual_return{$i}_{$j}");
                    $annualReturnObject = "annual_return{$i}_{$j}";
                    $annualReturn = $this->CI->$annualReturnObject;
                    $fee = round($input->getAmount() * $setting->getInterests() * ($i - $input->getStartAt() + 1) / $daysOfTheYear);
                    $annualReturn->setFee($fee);
                    $annualReturn->setRate($setting->getInterests());

                    $platformFee = $input->getAmount() * $setting->getPlatformProportion();
                    $annualReturn->setPlatform($platformFee);
                    $annualReturn->setPlatformRate($setting->getPlatformProportion());

                    $row->addAnnualReturns($annualReturn);
                    $shareBase += $input->getAmount();
                }
            }

            if ($currentShareRate == 0) {
                $currentShareRate = $setting->getShareRate();
            }

            $setting->getUseGenerate() ? $currentShareRate = $shareRateGenerator->generateRate($i, $currentShareRate):'';

            $share = round($shareBase * $currentShareRate);
            $row->setShareRate($currentShareRate);
            $row->setShare($share);
            $table->addRow($row);
        }

        return $table;
    }
}

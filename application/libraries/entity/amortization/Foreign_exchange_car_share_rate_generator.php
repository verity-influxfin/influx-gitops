<?php

class Foreign_exchange_car_share_rate_generator
{
	public function generateRate($i, $currentShareRate)
	{
		if ($i > 75 && $i < 151) {
			if ($i > 75 && $i < 91) {
				$currentShareRate *= 0.99;
			} elseif ($i >= 91 && $i < 106) {
				$currentShareRate *= 0.98;
			} elseif ($i >= 106 && $i < 121) {
				$currentShareRate *= 0.97;
			} elseif ($i >= 121 && $i < 131) {
				$currentShareRate *= 0.95;
			} elseif ($i >= 131 && $i < 141) {
				$currentShareRate *= 0.92;
			} elseif ($i >= 141 && $i < 151) {
				$currentShareRate *= 0.82;
			}
		}
		return $currentShareRate;
	}
}

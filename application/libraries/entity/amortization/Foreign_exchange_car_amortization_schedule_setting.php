<?php

class Foreign_exchange_car_amortization_schedule_setting
{
	/**
	* @var int
	*/
	protected $length;

	/**
	* @var float
	*/
	protected $interests;

	/**
	* @var float
	*/
	protected $platformProportion;

	/**
	* @var float
	*/
	protected $shareRate;

	/**
	* @return int
	*/
	public function getLength(): int
	{
	return $this->length;
	}

	/**
	* @param int $length
	*/
	public function setLength(int $length): void
	{
		$this->length = $length;
	}

	/**
	* @return float
	*/
	public function getInterests(): float
	{
		return $this->interests;
	}

	/**
	* @param float $interests
	*/
	public function setInterests(float $interests): void
	{
		$this->interests = $interests;
	}

	/**
	* @return float
	*/
	public function getPlatformProportion(): float
	{
		return $this->platformProportion;
	}

	/**
	* @param float $platformProportion
	*/
	public function setPlatformProportion(float $platformProportion): void
	{
		$this->platformProportion = $platformProportion;
	}

	/**
	* @return float
	*/
	public function getShareRate(): float
	{
		return $this->shareRate;
	}

	/**
	* @param float $shareRate
	*/
	public function setShareRate(float $shareRate): void
	{
		$this->shareRate = $shareRate;
	}
}

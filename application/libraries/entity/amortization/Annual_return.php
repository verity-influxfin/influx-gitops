<?php

class Annual_return
{
	/**
	 * @var date
	 */
	protected $startAt;

	/**
	 * @var float
	 */
	protected $rate;

	/**
	 * @var float
	 */
	protected $fee;

	/**
	 * @var int
	 */
	protected $platform;

	/**
	 * @var float
	 */
	protected $platformRate;

	/**
	 * @return date
	 */
	public function getStartAt(): date
	{
		return $this->startAt;
	}

	/**
	 * @param date $startAt
	 */
	public function setStartAt(date $startAt): void
	{
		$this->startAt = $startAt;
	}

	/**
	 * @return float
	 */
	public function getRate(): float
	{
		return $this->rate;
	}

	/**
	 * @param float $rate
	 */
	public function setRate(float $rate): void
	{
		$this->rate = $rate;
	}

	/**
	 * @return float
	 */
	public function getFee(): float
	{
		return $this->fee;
	}

	/**
	 * @param float $fee
	 */
	public function setFee(float $fee): void
	{
		$this->fee = $fee;
	}

	/**
	 * @return int
	 */
	public function getPlatform(): int
	{
		return $this->platform;
	}

	/**
	 * @param int $platform
	 */
	public function setPlatform(int $platform): void
	{
		$this->platform = $platform;
	}

	/**
	 * @return float
	 */
	public function getPlatformRate(): float
	{
		return $this->platformRate;
	}

	/**
	 * @param float $platformRate
	 */
	public function setPlatformRate(float $platformRate): void
	{
		$this->platformRate = $platformRate;
	}
}

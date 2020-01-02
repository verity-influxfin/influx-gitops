<?php

class Foreign_exchange_car_row
{
	/**
	 * @var AnnualReturn
	 */
	protected $annualReturns;

	/**
	 * @var int
	 */
	protected $share;

	/**
	 * @var float
	 */
	protected $shareRate;

	/**
	 * @return int
	 */
	public function getAnnualReturns(): array
	{
		return $this->annualReturns;
	}

	/**
	 * @param int $annualReturns
	 */
	public function addAnnualReturns(Annual_return $annualReturns): void
	{
		$this->annualReturns[] = $annualReturns;
	}

	/**
	 * @return int
	 */
	public function getShare(): int
	{
		return $this->share;
	}

	/**
	 * @param int $share
	 */
	public function setShare(int $share): void
	{
		$this->share = $share;
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

    /**
     * @return int
     */
    public function getYearDays(): int
    {
        return $this->yearDays;
    }

    /**
     * @param int $yearDays
     */
    public function setYearDays(int $yearDays): void
    {
        $this->yearDays = $yearDays;
    }

    /**
     * @return boolean
     */
    public function getUseGenerate(): bool
    {
        return $this->useGenerate;
    }
    /**
     * @param boolean $useGenerate
     */
    public function setUseGenerate(bool $useGenerate): void
    {
        $this->useGenerate = $useGenerate;
    }
}

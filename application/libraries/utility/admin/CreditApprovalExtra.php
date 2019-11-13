<?php

class CreditApprovalExtra
{
	/**
	 * @var integer
	 */
	protected $extraPoints;

	/**
	 * @var bool
	 */
	protected $skipInsertion;

	/**
	 * @return int
	 */
	public function getExtraPoints(): int
	{
		return $this->extraPoints;
	}

	/**
	 * @param int $extraPoints
	 */
	public function setExtraPoints(int $extraPoints): void
	{
		$this->extraPoints = $extraPoints;
	}

	/**
	 * @return bool
	 */
	public function shouldSkipInsertion(): bool
	{
		return $this->skipInsertion;
	}

	/**
	 * @param bool $skipInsertion
	 */
	public function setSkipInsertion(bool $skipInsertion): void
	{
		$this->skipInsertion = $skipInsertion;
	}
}

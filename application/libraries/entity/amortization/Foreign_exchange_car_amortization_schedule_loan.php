<?php

class Foreign_exchange_car_amortization_schedule_loan
{
	/**
	* @var int
	*/
	protected $startAt;

	/**
	* @var int
	*/
	protected $amount;

	/**
	* @return int
	*/
	public function getStartAt(): int
	{
	       return $this->startAt;
	}

	/**
	* @param int $startAt
	*/
	public function setStartAt(int $startAt): void
	{
	       $this->startAt = $startAt;
	}

	/**
	* @return int
	*/
	public function getAmount(): int
	{
	       return $this->amount;
	}

	/**
	* @param int $amount
	*/
	public function setAmount(int $amount): void
	{
	       $this->amount = $amount;
	}
}

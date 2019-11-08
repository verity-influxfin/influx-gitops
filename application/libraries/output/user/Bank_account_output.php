<?php

class Bank_account_output
{
	const VERIFIED = "verified";
	const FAILURE = "failure";
	const PENDING = "pending";
	const SENT = "sent";

	protected $bankAccount;
	protected $bankAccounts;
	protected $statusMapping;

	public function __construct($params)
	{
		if (!isset($params["data"])) {
			throw new OutOfBoundsException("Data to construct bank account output is not found");
		}

		if (is_array($params["data"])) {
			$this->bankAccounts = $params["data"];
		} else {
			$this->bankAccount = $params["data"];
		}

		$this->loadVerificationStatusMapping();
	}

	public function toOne()
	{
		if (!$this->bankAccount) {
			return [];
		}
		return $this->map($this->bankAccount);
	}

	public function toMany()
	{
		if (!$this->bankAccounts) {
			return [];
		}

		$bankAccounts = [];
		foreach ($this->bankAccounts as $bankAccount) {
			$bankAccountOutput = $this->map($bankAccount);
			if ($bankAccount->investor) {
				$bankAccounts["investor"] = $bankAccountOutput;
			} else {
				$bankAccounts["borrower"] = $bankAccountOutput;
			}
		}
		return $bankAccounts;
	}

	public function map($bankAccount, $withSensitiveInfo = false)
	{
		$output = [
			"id" => $bankAccount->id,
			"bank_code" => $bankAccount->bank_code,
			"branch_code" => $bankAccount->branch_code,
			"account" => $bankAccount->bank_account,
			"verification" => [
				"status" => isset($this->statusMapping[$bankAccount->verify]) ? $this->statusMapping[$bankAccount->verify] : 'error',
				"verified_at" => $bankAccount->verify_at,
			],
			"is_investor" => $bankAccount->investor == 1,
			"created_at" => $bankAccount->created_at,
		];

		return $output;
	}

	public function loadVerificationStatusMapping()
	{
		$this->statusMapping = [
			1 => self::VERIFIED,
			2 => self::PENDING,
			3 => self::SENT,
			4 => self::FAILURE,
		];
	}
}

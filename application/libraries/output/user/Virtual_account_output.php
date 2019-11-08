<?php

class Virtual_account_output
{
	const FROZEN = "frozen";
	const IN_USE = "in_use";
	const NORMAL = "normal";

	protected $virtualAccounts;
	protected $virtualAccount;
	protected $statusMapping;

	public function __construct($params)
	{
		if (!isset($params["data"])) {
			throw new OutOfBoundsException("Data to construct bank account output is not found");
		}

		if (is_array($params["data"])) {
			$this->virtualAccounts = $params["data"];
		} else {
			$this->virtualAccount = $params["data"];
		}

		$this->loadVirtualAccountStatusMapping();
	}

	public function toOne()
	{
		if (!$this->virtualAccount) {
			return [];
		}
		return $this->map($this->virtualAccount);
	}

	public function toMany()
	{
		if (!$this->virtualAccounts) {
			return [];
		}

		$virtualAccounts = [];
		foreach ($this->virtualAccounts as $virtualAccount) {
			$virtualAccountOutput = $this->map($virtualAccount);
			if ($virtualAccount->investor) {
				$virtualAccounts["investor"] = $virtualAccountOutput;
			} else {
				$virtualAccounts["borrower"] = $virtualAccountOutput;
			}
		}
		return $virtualAccounts;
	}

	public function map($virtualAccount, $withSensitiveInfo = false)
	{
		$output = [
			"id" => $virtualAccount->id,
			"is_investor" => $virtualAccount->investor == 1,
			"account" => $virtualAccount->virtual_account,
			"created_at" => $virtualAccount->created_at,
		];

		if ($virtualAccount->funds) {
			$output["funds"] = $virtualAccount->funds;
		}

		return $output;
	}

	public function loadVirtualAccountStatusMapping()
	{
		$this->statusMapping = [
			0 => self::FROZEN,
			1 => self::NORMAL,
			2 => self::IN_USE,
		];
	}
}

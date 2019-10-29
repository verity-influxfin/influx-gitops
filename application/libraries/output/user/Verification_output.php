<?php

class Verification_output
{
	const PENDING = "pending";
	const VERIFIED = "verified";
	const FAILURE = "failure";
	const HUMAN_REVIEW_REQUIRED = "human_review_required";
	const NOT_FOUND = "not_found";

	protected $verification;
	protected $verifications;
	protected $statusMapping;

	public function __construct($params)
	{
		if (!isset($params["data"])) {
			throw new OutOfBoundsException("Data to construct verification output is not found");
		}

		if (is_array($params["data"])) {
			$this->verifications = $params["data"];
		} else {
			$this->verification = $params["data"];
		}

		$this->loadVerificationStatusMapping();
	}

	public function toOne()
	{
		if (!$this->verification) {
			return [];
		}
		return $this->map($this->verification);
	}

	public function toMany()
	{
		if (!$this->verifications) {
			return [];
		}

		$verifications = [];
		foreach ($this->verifications as $verification) {
			$verifications[] = $this->map($verification);
		}
		return $verifications;
	}

	public function map($verification, $withSensitiveInfo = false)
	{
		$output = [
			"id" => $verification["certification_id"],
			"name" => $verification["name"],
			"description" => $verification["description"],
			"status" => isset($this->statusMapping[$verification["user_status"]]) ? $this->statusMapping[$verification["user_status"]] : self::NOT_FOUND,
		];

		return $output;
	}

	public function loadVerificationStatusMapping()
	{
		$this->statusMapping = [
			0 => self::PENDING,
			1 => self::VERIFIED,
			2 => self::FAILURE,
			3 => self::HUMAN_REVIEW_REQUIRED,
		];
	}
}

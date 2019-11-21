<?php

class facebook_output
{
	protected $facebook;

	public function __construct($params)
	{
		if (!isset($params["data"])) {
			throw new OutOfBoundsException("Data to construct facebook output is not found");
		}

		$this->facebook = $this->convertToFacebookObject($params["data"]);
	}

	public function toOne()
	{
		if (!$this->facebook) {
			return [];
		}
		return $this->map($this->facebook);
	}

	public function map($facebook, $withSensitiveInfo = false)
	{
		$output = [
			"id" => $facebook->id,
			"username" => $facebook->username,
		];

		return $output;
	}

	public function convertToFacebookObject($userMetaInputs)
	{
		$facebook = new stdClass();
		$facebook->id = $userMetaInputs->fb_id;
		$facebook->username = $userMetaInputs->fb_name;
		return $facebook;
	}
}

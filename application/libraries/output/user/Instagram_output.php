<?php

class Instagram_output
{
	protected $instagram;

	public function __construct($params)
	{
		if (!isset($params["data"])) {
			throw new OutOfBoundsException("Data to construct instagram output is not found");
		}

		$this->instagram = $this->convertToInstagramObject($params["data"]);
	}

	public function toOne()
	{
		if (!$this->instagram) {
			return [];
		}
		return $this->map($this->instagram);
	}

	public function map($instagram, $withSensitiveInfo = false)
	{
		$output = [
			"id" => $instagram->id,
			"username" => $instagram->username,
		];

		return $output;
	}

	public function convertToInstagramObject($userMetaInputs)
	{
		$instagram = new stdClass();
		foreach ($userMetaInputs as $userMetaInput) {
			switch ($userMetaInput->meta_key) {
				case "ig_id":
					$instagram->id = $userMetaInput->meta_value;
					break;
				case "ig_username":
					$instagram->username = $userMetaInput->meta_value;
					break;
			}
		}
		return $instagram;
	}
}

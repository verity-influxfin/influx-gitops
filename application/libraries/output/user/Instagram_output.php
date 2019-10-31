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

		if ($instagram->image) {
			$output["profile_image"] = $instagram->image;
		}

		return $output;
	}

	public function convertToInstagramObject($userMetaInputs)
	{
		$instagram = new stdClass();
		$instagram->id = $userMetaInputs->ig_id;
		$instagram->username = $userMetaInputs->ig_username;
		if (isset($userMetaInputs->ig_image)) {
			$instagram->image = $userMetaInputs->ig_image;
		}
		return $instagram;
	}
}

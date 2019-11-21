<?php

class Usermeta
{
	protected $data;

	public function __construct($params)
	{
		$userMeta = $params["data"];

		$data = new stdClass();
		foreach ($userMeta as $each) {
			$name = $each->meta_key;
			$data->$name = $each->meta_value;
		}
		$this->data = $data;
	}

	public function setInstagramPicture($picture)
	{
		$this->data->ig_image = $picture;
	}

	public function values()
	{
		return $this->data;
	}
}

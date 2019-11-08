<?php

class Credit_output
{
	protected $credit;
	protected $productMapping;

	public function __construct($params)
	{
		if (!isset($params["data"])) {
			throw new OutOfBoundsException("Data to construct credit output is not found");
		}

		$this->credit = $params["data"];
		$this->loadProductMapping();
	}

	public function toOne()
	{
		if (!$this->credit) {
			return [];
		}
		return $this->map($this->credit);
	}

	public function map($credit, $withSensitiveInfo = false)
	{
		$output = [
			'id' => $credit->id,
			'product' => [
				'id' => $credit->product_id,
				'name' => isset($this->productMapping[$credit->product_id]["name"]) ? $this->productMapping[$credit->product_id]["name"] : '',
			],
			'level' => $credit->level,
			'points' => $credit->points,
			'amount' => $credit->amount,
			'expired_at' => $credit->expire_time,
			'created_at' => $credit->created_at
		];

		return $output;
	}

	public function loadProductMapping()
	{
		$ci =& get_instance();
		$this->productMapping = $ci->config->item('product_list');
	}
}

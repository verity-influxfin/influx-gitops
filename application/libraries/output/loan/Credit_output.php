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

		if (is_array($params["data"])) {
			$this->credit = $this->mapToObject($params["data"]);
		} else {
			$this->credit = $params["data"];
		}

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
				'sub_product_id' => isset($credit->sub_product_id)?$credit->sub_product_id:'',
				'name' => $this->productMapping[$credit->product_id]["name"] . (isset($credit->sub_product_name) ? ' / ' . $credit->sub_product_name : ''),
			],
			'level' => $credit->level,
			'points' => $credit->points,
			'amount' => $credit->amount,
			'expired_at' => $credit->expire_time,
			'created_at' => $credit->created_at
		];

		return $output;
	}

	public function mapToObject($creditInput)
	{
		$credit = new stdClass();
		$credit->id = $creditInput["id"];
		$credit->product_id = $creditInput["product_id"];
		if(isset($creditInput["sub_product_id"])){
            $credit->sub_product_id = $creditInput["sub_product_id"];
            $credit->sub_product_name = $creditInput["sub_product_name"];
        }
        $credit->level = $creditInput["level"];
        $credit->points = $creditInput["points"];
        $credit->amount = $creditInput["amount"];
        $credit->expire_time = $creditInput["expire_time"];
        $credit->created_at = $creditInput["created_at"];
		return $credit;
	}

	public function loadProductMapping()
	{
		$ci =& get_instance();
		$this->productMapping = $ci->config->item('product_list');
	}
}

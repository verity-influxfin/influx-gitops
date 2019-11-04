<?php

class Target_output
{
	protected $target;
	protected $targets;

	protected $productMapping;
	protected $statusMapping;

	public function __construct($params)
	{
		if (!isset($params["data"])) {
			throw new OutOfBoundsException("Data to construct target output is not found");
		}

		if (is_array($params["data"])) {
			$this->targets = $params["data"];
		} else {
			$this->target = $params["data"];
		}

		$this->loadProductMapping();
		$this->loadTargetStatusMapping();
	}

	public function toOne()
	{
		if (!$this->target) {
			return [];
		}
		return $this->map($this->target);
	}

	public function toMany()
	{
		if (!$this->targets) {
			return [];
		}

		$targets = [];
		foreach ($this->targets as $target) {
			$targetOutput = $this->map($target);
			$targets[] = $targetOutput;
		}
		return $targets;
	}

	public function map($target, $withSensitiveInfo = false)
	{
		$output = [
			'id' => $target->id,
			'number' => $target->target_no,
			'product' => [
				'id' => $target->product_id,
				'name' => isset($this->productMapping[$target->product_id]["name"]) ? $this->productMapping[$target->product_id]["name"] : '',
			],
			'requested_amount' => $target->amount,
			'approved_amount' => isset($target->credit) ? $target->credit->amount : null,
			'available_amount' => $target->loan_amount,
			'status' => [
				'id' => $target->status,
				'text' => isset($this->statusMapping[$target->status]) ? $this->statusMapping[$target->status] : '',
			],
			'reason' => $target->reason,
			'image' => $target->person_image,
			'expire_at' => $target->expire_time,
		];

		if (isset($target->amortization)) {
			$output["remaining"] = $target->amortization["remaining_principal"];
			$output["principal"] = 0;
			foreach ($target->amortization["list"] as $returning) {
				$output["principal"] += $returning["principal"];
			}
		}

		return $output;
	}

	public function loadTargetStatusMapping()
	{
		$ci =& get_instance();
		$ci->load->model('loan/target_model');

		$this->statusMapping = $ci->target_model->status_list;
	}

	public function loadProductMapping()
	{
		$ci =& get_instance();
		$this->productMapping = $ci->config->item('product_list');
	}
}

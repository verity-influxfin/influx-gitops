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
			'status' => isset($this->statusMapping[$target->status]) ? $this->statusMapping[$target->status] : '',
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
		$this->statusMapping = [
			0 => 'waiting_approval',
			1 => 'waiting_signing',
			2 => 'waiting_verify',
			3 => 'waiting_loan',
			4 => 'waiting_release',
			5 => 'returning',
			8 => 'cancelled',
			9 => 'failure',
			10 => 'finished',
			21 => 'waiting_quotes',
		];
	}

	public function loadProductMapping()
	{
		$ci =& get_instance();
		$this->productMapping = $ci->config->item('product_list');
	}
}

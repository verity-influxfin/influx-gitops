<?php

class Target_output
{
	protected $target;
	protected $targets;

	protected $repaymentMapping;
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
		$this->loadSubProductMapping();
		$this->loadTargetStatusMapping();
		$this->loadRepaymentMapping();
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
        $reason = $target->reason;
        $json_reason = json_decode($reason);
        if(isset($json_reason->reason)){
            $reason = $json_reason->reason.' - '.$json_reason->reason_description;
        }
		$output = [
			'id' => $target->id,
			'number' => $target->target_no,
			'product' => [
				'id' => $target->product_id,
				'name' => isset($this->productMapping[$target->product_id]["name"])
                          ? $this->productMapping[$target->product_id]["name"]
                              . (
                                  $target->sub_product_id!=0
                                  ?' / '.$this->subProductMapping[$target->sub_product_id]['identity'][$this->productMapping[$target->product_id]['identity']]['name']
                                  :''
                              )
                          : '',
			],
			'requested_amount' => $target->amount,
			'approved_amount' => isset($target->credit) ? isset($target->credit->amount) ? $target->credit->amount : $target->credit["amount"] : null,
			'available_amount' => $target->loan_amount,
			'credit' => $target->credit_level,
			'interests' => $target->interest_rate,
			'instalment' => $target->instalment,
			'repayment' => [
				'id' => $target->repayment,
				'text' => isset($this->repaymentMapping[$target->repayment]) ? $this->repaymentMapping[$target->repayment] : '',
			],
			'status' => [
				'id' => $target->status,
				'text' => isset($this->statusMapping[$target->status]) ? $this->statusMapping[$target->status] : '',
			],
			'is_delay' => $target->delay == 1,
			'reason' => $reason,
			'image' => $target->person_image,
			'expire_at' => $target->expire_time,
			'loan_at' => $target->loan_date,
		];

		if(!empty($target->productTargetData)){
            $output['targetData'] = $target->target_data;
			$output['productTargetData'] = $target->productTargetData['targetData'];
			$output['creditTargetData'] = $target->creditTargetData;
        }

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

	public function loadSubProductMapping()
	{
		$ci =& get_instance();
		$this->subProductMapping = $ci->config->item('sub_product_list');
	}

	public function loadRepaymentMapping()
	{
		$ci =& get_instance();
		$this->repaymentMapping = $ci->config->item('repayment_type');
	}
}

<?php

class Verifications_output
{
	protected $borrower;
	protected $investor;

	public function __construct($params)
	{
		if (isset($params["borrower"]) && $params["borrower"]) {
			$this->borrower = $params["borrower"];
		}
		if (isset($params["investor"]) && $params["investor"]) {
			$this->investor = $params["investor"];
		}
	}

	public function toMany()
	{
		$ci =& get_instance();

		$output = [];
		if ($this->borrower) {
			$ci->load->library('output/user/verification_output', ["data" => $this->borrower], 'borrower_verification_output');
			$output["borrower"] = $ci->borrower_verification_output->toMany();
		}
		if ($this->investor) {
			$investor = $ci->load->library('output/user/verification_output', ["data" => $this->investor], 'investor_verification_output');
			$output["investor"] = $ci->investor_verification_output->toMany();
		}

		return $output;
	}
}

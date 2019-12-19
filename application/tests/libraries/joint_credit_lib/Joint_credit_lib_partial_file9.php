<?php

class Joint_credit_lib_partial_file9 extends TestCase
{
	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->library('Joint_credit_lib');
		$this->joint_credit = $this->CI->joint_credit_lib;
		$this->readInputFile();
	}

	private function readInputFile(){
		$outfile = dirname(__FILE__, 3) .  "/files/libraries/joint_credit_lib/39-decoded.pdf";
		$parser = new \Smalot\PdfParser\Parser();
		$pdf = $parser->parseFile($outfile);
		$this->text = $pdf->getText();
	}

    public function test_check_bank_loan()
	{
		$result = ["status" => "failure", "messages" => []];
		$this->joint_credit->check_bank_loan($this->text, $result);

		$expected = [
			"stage" => "bank_loan",
			"status" => "success",
			"message" => [
				'有無延遲還款 : 無',
				"銀行借款家數 : 0",
				"長期放款家數 : 0",
				"長期放款借款餘額比例 : 0%",
				"長期放款借款餘額比例 : 0%",
				"長期放款借款餘額比例 : 0%",
			]
		];

		$this->assertEquals($expected, $result["messages"][0]);
	}
}

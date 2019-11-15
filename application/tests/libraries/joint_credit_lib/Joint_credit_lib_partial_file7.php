<?php

class Joint_credit_lib_partial_file6 extends TestCase
{
	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->library('Joint_credit_lib');
		$this->joint_credit = $this->CI->joint_credit_lib;
		$this->readInputFile();
	}

	private function readInputFile(){
		$outfile = dirname(__FILE__, 3) .  "/files/libraries/joint_credit_lib/21-decoded.pdf";
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
			"status" => "failure",
			"message" => [
				"有無延遲還款 : 有",
				"銀行借款家數 : 1",
			],
			"rejected_message" => [
				"最近十二個月有無延遲還款 : 有"
			]
		];
		$this->assertEquals($expected, $result["messages"][0]);
	}
}

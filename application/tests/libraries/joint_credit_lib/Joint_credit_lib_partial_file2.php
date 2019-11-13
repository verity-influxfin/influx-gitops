<?php

class Joint_credit_lib_file1 extends TestCase
{
	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->library('Joint_credit_lib');
		$this->joint_credit = $this->CI->joint_credit_lib;
		$this->readInputFile();
	}

	private function readInputFile(){
		$outfile = dirname(__FILE__, 3) .  "/files/libraries/joint_credit_lib/28-decoded.pdf";
		$parser = new \Smalot\PdfParser\Parser();
		$pdf = $parser->parseFile($outfile);
		$this->text = $pdf->getText();
	}

	public function test_check_credit_card_accounts()
	{
		$result = ["stage" => "credit_card_accounts", "status" => "failure", "message" => []];
		$input = ["appliedTime" => '108/09/06', "allowedAmount" => 20];
		$this->joint_credit->check_credit_card_accounts($this->text, $input, $result);

		$expected = [
			"stage" => "credit_card_accounts",
			"status" => "success",
			"message" => [
				"當月信用卡使用率：65.83%",
				"近一月信用卡使用率：75.29%",
				"近二月信用卡使用率：46.85%",
				"是否有預借現金 : 無",
				"延遲未滿一個月次數：1"
			],
		];
		$this->assertEquals($expected, $result["messages"][0]);
	}
}

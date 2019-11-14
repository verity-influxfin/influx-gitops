<?php

class Joint_credit_lib_partial_file1 extends TestCase
{
	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->library('Joint_credit_lib');
		$this->joint_credit = $this->CI->joint_credit_lib;
		$this->readInputFile();
	}

	private function readInputFile(){
		$outfile = dirname(__FILE__, 3) .  "/files/libraries/joint_credit_lib/37-decoded.pdf";
		$parser = new \Smalot\PdfParser\Parser();
		$pdf = $parser->parseFile($outfile);
		$this->text = $pdf->getText();
	}

	public function test_check_credit_card_accounts()
	{
		$result = ["stage" => "credit_card_accounts", "status" => "failure", "message" => []];
		$input = ["appliedTime" => '108/09/02', "allowedAmount" => 400];
		$this->joint_credit->check_credit_card_accounts($this->text, $input, $result);

		$expected = [
			"stage" => "credit_card_accounts",
			"status" => "failure",
			"message" => [
				"當月信用卡使用率：0%",
				"近一月信用卡使用率：138.46%",
				"近二月信用卡使用率：136.93%",
				"是否有預借現金 : 有",
				"延遲未滿一個月次數：4"
			],
			"rejected_message" => [
				"延遲紀錄超過3次",
				"預借現金該月有延遲紀錄"
			]
		];
		$this->assertEquals($expected, $result["messages"][0]);
	}
}

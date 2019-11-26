<?php

class Joint_credit_lib_partial_file8 extends TestCase
{
	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->library('Joint_credit_lib');
		$this->joint_credit = $this->CI->joint_credit_lib;
		$this->readInputFile();
	}

	private function readInputFile(){
		$outfile = dirname(__FILE__, 3) .  "/files/libraries/joint_credit_lib/7-decoded.pdf";
		$parser = new \Smalot\PdfParser\Parser();
		$pdf = $parser->parseFile($outfile);
		$this->text = $pdf->getText();
	}

	public function test_check_credit_card_accounts()
	{
		$result = ["stage" => "credit_card_accounts", "status" => "failure", "message" => []];
		$input = ["appliedTime" => '108/07/22', "allowedAmount" => 140];
		$this->joint_credit->check_credit_card_accounts($this->text, $input, $result);

		$expected = [
			"stage" => "credit_card_accounts",
			"status" => "pending",
			"message" => [
				'信用紀錄幾個月：7',
				"當月信用卡使用率：73.86%",
				"近一月信用卡使用率：78.83%",
				"近二月信用卡使用率：67.17%",
				"是否有預借現金 : 有",
				"延遲未滿一個月次數：0",
			],
		];
		$this->assertEquals($expected, $result["messages"][0]);
	}
}

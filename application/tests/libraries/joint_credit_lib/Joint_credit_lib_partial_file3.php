<?php

class Joint_credit_lib_partial_file3 extends TestCase
{
	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->library('Joint_credit_lib');
		$this->joint_credit = $this->CI->joint_credit_lib;
		$this->readInputFile();
	}

	private function readInputFile(){
		$outfile = dirname(__FILE__, 3) .  "/files/libraries/joint_credit_lib/20-decoded.pdf";
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

	public function test_check_credit_card_accounts()
	{
		$result = ["stage" => "credit_card_accounts", "status" => "failure", "message" => []];
		$input = ["appliedTime" => '108/08/26', "allowedAmount" => 40];
		$this->joint_credit->check_credit_card_accounts($this->text, $input, $result);

		$expected = [
			"stage" => "credit_card_accounts",
			"status" => "success",
			"message" => [
				'信用紀錄幾個月：9',
				"當月信用卡使用率：0%",
				"近一月信用卡使用率：0%",
				"近二月信用卡使用率：0%",
				"是否有預借現金 : 無",
				"延遲未滿一個月次數：1"
			],
		];
		$this->assertEquals($expected, $result["messages"][0]);
	}

	public function test_check_browsed_hits_by_electrical_pay()
	{
		$result = ["status" => "failure", "messages" => []];
		$this->joint_credit->check_browsed_hits_by_electrical_pay($this->text, $result);

		$expected = [
			"stage" => "browsed_hits_by_electrical_pay",
			"status" => "success",
			"message" => "被電子支付或電子票證發行機構查詢紀錄：1"
		];
		$this->assertEquals($expected, $result["messages"][0]);
	}
}

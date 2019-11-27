<?php

class Joint_credit_lib_partial_file5 extends TestCase
{
	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->library('Joint_credit_lib');
		$this->joint_credit = $this->CI->joint_credit_lib;
		$this->readInputFile();
	}

	private function readInputFile(){
		$outfile = dirname(__FILE__, 3) .  "/files/libraries/joint_credit_lib/13-decoded.pdf";
		$parser = new \Smalot\PdfParser\Parser();
		$pdf = $parser->parseFile($outfile);
		$this->text = $pdf->getText();
	}

	public function test_check_credit_card_accounts()
	{
		$result = ["stage" => "credit_card_accounts", "status" => "failure", "message" => []];
		$input = ["appliedTime" => '108/08/26', "allowedAmount" => 0];
		$this->joint_credit->check_credit_card_accounts($this->text, $input, $result);

		$expected = [
			"stage" => "credit_card_accounts",
			"status" => "failure",
			"message" => [
				'信用紀錄幾個月：0',
				"當月信用卡使用率：0%",
				"近一月信用卡使用率：0%",
				"近二月信用卡使用率：0%",
				"超過一個月延遲繳款：26",
				"是否有預借現金 : 無",
				"延遲未滿一個月次數：5",
			],
			"rejected_message" => [
				"延遲紀錄超過3次",
				"債權狀態：呆帳或催收",
			]
		];
		$this->assertEquals($expected, $result["messages"][0]);
	}

	public function test_check_credit_scores()
	{
		$result = ["status" => "failure", "messages" => []];
		$this->joint_credit->check_credit_scores($this->text, $result);

		$expected = [
			"stage" => "credit_scores",
			"status" => "pending",
			"message" => "信用評分 : 此次暫時無法評分"
		];
		$this->assertEquals($expected, $result["messages"][0]);
	}
}

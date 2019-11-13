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
		$outfile = dirname(__FILE__, 3) .  "/files/libraries/joint_credit_lib/38-decoded.pdf";
		$parser = new \Smalot\PdfParser\Parser();
		$pdf = $parser->parseFile($outfile);
		$this->text = $pdf->getText();
	}

	public function test_get_credit_date()
	{
		$expected = "108/10/29";

		$method = ReflectionHelper::getPrivateMethodInvoker($this->joint_credit, "get_credit_date");
		$result = $method($this->text);
		$this->assertEquals($expected, $result);
	}

	public function test_check_report_expirations(){
		$currentTime = 1573462800; // 2019/11/11
		$this->joint_credit->setCurrentTime($currentTime);
		$result = ["status" => "failure", "messages" => []];
		$this->joint_credit->check_report_expirations($this->text, $result);

		$expected = ["stage" => "report_expirations", "status" => "success", "message" => ''];
		$this->assertEquals($expected, $result["messages"][0]);
	}

	public function test_check_overdue_and_bad_debts()
	{
		$result = ["status" => "failure", "messages" => []];
		$this->joint_credit->check_overdue_and_bad_debts($this->text, $result);

		$expected = ["stage" => "bad_debts", "status" => "success", "message" => '逾期、催收或呆帳資訊：無'];
		$this->assertEquals($expected, $result["messages"][0]);
	}

	public function test_check_main_debts()
	{
		$result = ["status" => "failure", "messages" => []];
		$this->joint_credit->check_main_debts($this->text, $result);

		$expected = ["stage" => "main_debts", "status" => "success", "message" => "主債務債權再轉讓及清償資訊：無"];
		$this->assertEquals($expected, $result["messages"][0]);
	}

	public function test_check_extra_debts()
	{
		$result = ["status" => "failure", "messages" => []];
		$this->joint_credit->check_extra_debts($this->text, $result);

		$expected = ["stage" => "extra_debts", "status" => "success", "message" => "共同債務/從債務/其他債務資訊 : 無"];
		$this->assertEquals($expected, $result["messages"][0]);
	}

	public function testReadExtraDebtRow()
	{
		$result = ["status" => "failure", "messages" => []];
		$matches = $this->CI->regex->findPatternInBetween($this->text, '【共同債務\/從債務\/其他債務資訊】', '【共同債務\/從債務\/其他債務轉讓資訊】');

		$method = ReflectionHelper::getPrivateMethodInvoker($this->joint_credit, "readExtraDebtRow");
		$rows = $method($matches[0]);

		//empty result but the data contains keyword "台端", thus the array may contain some data
		$expected = [
			"台端" => "台端108年09月底在國內各金融機構",
			"科目" => "",
			"承貸行" => "",
			"未逾期" => "",
			"逾期未還金額" => "",
		];

		$this->assertEquals($expected, $rows[0]);
	}

	public function test_check_extra_transfer_debts()
	{
		$result = ["status" => "failure", "messages" => []];
		$this->joint_credit->check_extra_transfer_debts($this->text, $result);

		$expected = ["stage" => "transfer_debts", "status" => "success", "message" => "共同債務/從債務/其他債務轉讓資訊：無"];
		$this->assertEquals($expected, $result["messages"][0]);
	}

	public function test_check_bounced_checks(){
		$result = ["status" => "failure", "messages" => []];
		$this->joint_credit->check_bounced_checks($this->text, $result);

		$expected = ["stage" => "bounced_checks", "status" => "success", "message" => "退票資訊：無"];
		$this->assertEquals($expected, $result["messages"][0]);
	}

	public function test_check_lost_contacts(){
		$result = ["status" => "failure", "messages" => []];
		$this->joint_credit->check_lost_contacts($this->text, $result);

		$expected = ["stage" => "lost_contacts", "status" => "success", "message" => "拒絕往來資訊：無"];
		$this->assertEquals($expected, $result["messages"][0]);
	}

	public function test_check_credit_cards(){
		$result = ["status" => "status", "messages" => []];
		$this->joint_credit->check_credit_cards($this->text, $result);

		$expected = [
			"stage" => "credit_card_debts",
			"status" => "pending",
			"message" => [
				"信用卡資訊：有",
				"信用卡使用中張數：3",
				"信用卡總額度（元）：290"
			]
		];
		$this->assertEquals($expected, $result["messages"][0]);
	}

	public function test_check_credit_card_accounts()
	{
		$result = ["stage" => "credit_card_accounts", "status" => "failure", "message" => []];
		$input = ["appliedTime" => '108/10/29', "allowedAmount" => 290];
		$this->joint_credit->check_credit_card_accounts($this->text, $input, $result);

		$expected = [
			"stage" => "credit_card_accounts",
			"status" => "failure",
			"message" => [
				"當月信用卡使用率：79.87%",
				"近一月信用卡使用率：74.59%",
				"近二月信用卡使用率：81.05%",
				"是否有預借現金 : 無",
				"延遲未滿一個月次數：6"
			],
			"rejected_message" => [
				"延遲紀錄超過3次"
			]
		];
		$this->assertEquals($expected, $result["messages"][0]);
	}
}

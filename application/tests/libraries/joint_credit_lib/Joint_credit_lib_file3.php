<?php

class Joint_credit_lib_file3 extends TestCase
{
	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->library('Joint_credit_lib');
		$this->joint_credit = $this->CI->joint_credit_lib;
		$this->readInputFile();
	}

	private function readInputFile(){
		$outfile = dirname(__FILE__, 3) .  "/files/libraries/joint_credit_lib/15-decoded.pdf";
		$parser = new \Smalot\PdfParser\Parser();
		$pdf = $parser->parseFile($outfile);
		$this->text = $pdf->getText();
	}

	public function test_get_credit_date()
	{
		$expected = "108/08/19";

		$method = ReflectionHelper::getPrivateMethodInvoker($this->joint_credit, "get_credit_date");
		$result = $method($this->text);
		$this->assertEquals($expected, $result);
	}

	public function test_check_report_expirations(){
		$currentTime = 1573462800; // 2019/11/11
		$this->joint_credit->setCurrentTime($currentTime);
		$result = ["status" => "failure", "messages" => []];
		$this->joint_credit->check_report_expirations($this->text, $result);

		$expected = ["stage" => "report_expirations", "status" => "failure", "message" => ''];
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

		$expected = [
			"stage" => "extra_debts",
			"status" => "success",
			"message" => [
				"台端擔任耿德實業有限公司之保證人",
				"科目：中期擔保放款",
				"未逾期餘額:********659千元",
			]
		];
		$this->assertEquals($expected, $result["messages"][0]);
	}

	public function testReadExtraDebtRow()
	{
		$result = ["status" => "failure", "messages" => []];
		$matches = $this->CI->regex->findPatternInBetween($this->text, '【共同債務\/從債務\/其他債務資訊】', '【共同債務\/從債務\/其他債務轉讓資訊】');

		$method = ReflectionHelper::getPrivateMethodInvoker($this->joint_credit, "readExtraDebtRow");
		$secondaryLiability = $this->CI->regex->findPatternInBetween($matches[0], '從債務', '');

		$rows = $method($secondaryLiability[0]);

		$expected = [
			'台端' => '台端擔任耿德實業有限公司之保證人',
			'科目' => '中期擔保放款',
			'承貸行' => '承貸行:台北富邦銀行營業部',
			'未逾期' => '未逾期餘額:********659千元',
			'逾期未還金額' => '逾期未還金額:**********0千元',
			'未逾期餘額' => '未逾期餘額:********659千元',
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
}

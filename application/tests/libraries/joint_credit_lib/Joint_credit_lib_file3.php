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
			"status" => "success",
			"message" => [
				"信用卡資訊：有",
				"信用卡使用中張數：2",
				"信用卡總額度（元）：50"
			]
		];
		$this->assertEquals($expected, $result["messages"][0]);
	}

	public function test_check_credit_card_accounts()
	{
		$result = ["stage" => "credit_card_accounts", "status" => "failure", "message" => []];
		$input = ["appliedTime" => '108/08/19', "allowedAmount" => 50];
		$this->joint_credit->check_credit_card_accounts($this->text, $input, $result);

		$expected = [
			"stage" => "credit_card_accounts",
			"status" => "success",
			"message" => [
				"當月信用卡使用率：0%",
				"近一月信用卡使用率：-35.81%",
				"近二月信用卡使用率：13.36%",
				"是否有預借現金 : 無",
				"延遲未滿一個月次數：0"
			],
		];
		$this->assertEquals($expected, $result["messages"][0]);
	}

	public function test_check_credit_card_debts()
	{
		$result = ["status" => "failure", "messages" => []];
		$this->joint_credit->check_credit_card_debts($this->text, $result);

		$expected = [
			"stage" => "credit_card_debts",
			"status" => "success",
			"message" => "信用卡債權再轉讓及清償資訊：無"
		];
		$this->assertEquals($expected, $result["messages"][0]);
	}

	public function test_check_browsed_hits()
	{
		$result = ["status" => "failure", "messages" => []];
		$this->joint_credit->check_browsed_hits($this->text, $result);

		$expected = [
			"stage" => "browsed_hits",
			"status" => "pending",
			"message" => "被查詢次數：6"
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
			"message" => "被電子支付或電子票證發行機構查詢紀錄：0"
		];
		$this->assertEquals($expected, $result["messages"][0]);
	}

	public function test_check_browsed_hits_by_itself()
	{
		$result = ["status" => "failure", "messages" => []];
		$this->joint_credit->check_browsed_hits_by_itself($this->text, $result);

		$expected = [
			"stage" => "browsed_hits_by_itself",
			"status" => "success",
			"message" => "當事人查詢紀錄：0"
		];
		$this->assertEquals($expected, $result["messages"][0]);
	}

	public function test_check_extra_messages()
	{
		$result = ["status" => "failure", "messages" => []];
		$this->joint_credit->check_extra_messages($this->text, $result);

		$expected = [
			"stage" => "extra_messages",
			"status" => "success",
			"message" => "附加訊息：無"
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
			"message" => "信用評分 : 496"
		];
		$this->assertEquals($expected, $result["messages"][0]);
	}

	public function test_is_id_match()
	{
		$userModel = $this->getMockBuilder('user_model')
						  ->disableOriginalConstructor()
						  ->getMock();
		$user = new stdClass();
		$user->id_number = 'A129360915';
		$userModel->expects($this->any())
				  ->method('get')
				  ->will($this->returnValue($user));

		$this->joint_credit->CI->user_model = $userModel;
		$result = $this->joint_credit->is_id_match(1, $this->text);
		$this->assertTrue($result);

		$user->id_number = 'B123456789';
		$result = $this->joint_credit->is_id_match(1, $this->text);
		$this->assertFalse($result);
	}
}

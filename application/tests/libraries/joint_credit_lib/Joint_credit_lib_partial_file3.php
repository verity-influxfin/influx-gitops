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

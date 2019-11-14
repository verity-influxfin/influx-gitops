<?php

class Joint_credit_lib_partial_file4 extends TestCase
{
	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->library('Joint_credit_lib');
		$this->joint_credit = $this->CI->joint_credit_lib;
		$this->readInputFile();
	}

	private function readInputFile(){
		$outfile = dirname(__FILE__, 3) .  "/files/libraries/joint_credit_lib/4-decoded.pdf";
		$parser = new \Smalot\PdfParser\Parser();
		$pdf = $parser->parseFile($outfile);
		$this->text = $pdf->getText();
	}

	public function test_check_credit_scores()
	{
		$result = ["status" => "failure", "messages" => []];
		$this->joint_credit->check_credit_scores($this->text, $result);

		$expected = [
			"stage" => "credit_scores",
			"status" => "pending",
			"message" => "信用評分 : 無"
		];
		$this->assertEquals($expected, $result["messages"][0]);
	}
}

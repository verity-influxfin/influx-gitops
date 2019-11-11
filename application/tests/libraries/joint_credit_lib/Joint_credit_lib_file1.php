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
		$outputFile = "joint-credits/38-decoded.pdf";
		$parser = new \Smalot\PdfParser\Parser();
		$url = "http://127.0.0.1/{$outputFile}";
		$pdf = $parser->parseFile($url);
		$this->text = $pdf->getText();
	}

	public function test_get_credit_date()
	{
		$expected = "108/10/29";

		$method = ReflectionHelper::getPrivateMethodInvoker($this->joint_credit, "get_credit_date");
		$result = $method($this->text);
		$this->assertEquals($expected, $result);
	}
}

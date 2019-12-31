<?php

class Labor_insurance_lib_file5 extends TestCase
{
	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->library('Labor_insurance_lib');
		$this->labor_insurance_lib = $this->CI->labor_insurance_lib;
		$this->readInputFile();
	}

	private function readInputFile(){
		$outfile = dirname(__FILE__, 3) .  "/files/libraries/labor_insurance_lib/5-decoded.pdf";
		$parser = new \Smalot\PdfParser\Parser();
		$pdf = $parser->parseFile($outfile);
		$this->text = $pdf->getText();
	}

    public function testProcessDocumentCorrectness()
    {
        $expectedResult = [
			"status" => "pending",
			"messages" => [
				[
					"stage" => "correctness",
		            "status" => "success",
		            "message" => ""
				]
			]
		];
		$result = ["status" => "pending", "messages" => []];
		$this->labor_insurance_lib->processDocumentCorrectness($this->text, $result);

		$this->assertEquals($expectedResult, $result);
    }

	public function testProcessDocumentIsValid()
	{
		$expectedResult = [
			"status" => "pending",
			"messages" => [
				[
					"stage" => "download_time",
					"status" => "success",
					"message" => ""
				]
			]
		];
		//2019/12/27
		$time = 1577427644;
		$this->labor_insurance_lib->setCurrentTime($time);
		$result = ["status" => "pending", "messages" => []];
		$this->labor_insurance_lib->processDocumentIsValid($this->text, $result);

		$this->assertEquals($expectedResult, $result);
	}
}

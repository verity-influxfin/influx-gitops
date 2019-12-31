<?php

class Labor_insurance_lib_file2 extends TestCase
{
	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->library('Labor_insurance_lib');
		$this->labor_insurance_lib = $this->CI->labor_insurance_lib;
		$this->readInputFile();
	}

	private function readInputFile(){
		$outfile = dirname(__FILE__, 3) .  "/files/libraries/labor_insurance_lib/2.pdf";
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
		            "status" => "failure",
		            "message" => "上傳文件錯誤"
				]
			]
		];
		$result = ["status" => "pending", "messages" => []];
		$this->labor_insurance_lib->processDocumentCorrectness($this->text, $result);

		$this->assertEquals($expectedResult, $result);
    }
}

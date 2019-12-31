<?php

class Labor_insurance_lib_file3 extends TestCase
{
	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->library('Labor_insurance_lib');
		$this->labor_insurance_lib = $this->CI->labor_insurance_lib;
		$this->readInputFile();
	}

	private function readInputFile(){
		$outfile = dirname(__FILE__, 3) .  "/files/libraries/labor_insurance_lib/3.pdf";
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
}

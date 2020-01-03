<?php

class Labor_insurance_lib_partial_file3 extends TestCase
{
	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->library('Labor_insurance_lib');
		$this->labor_insurance_lib = $this->CI->labor_insurance_lib;
		$this->readInputFile();
		$this->rows = $this->labor_insurance_lib->readRows($this->text);
	}

	private function readInputFile(){
		$outfile = dirname(__FILE__, 3) .  "/files/libraries/labor_insurance_lib/8-decoded.pdf";
		$parser = new \Smalot\PdfParser\Parser();
		$pdf = $parser->parseFile($outfile);
		$this->text = $pdf->getText();
	}

    public function testProcessMostRecentCompanyName()
    {
        $expectedResult = [
            "status" => "pending",
            "messages" => [
                [
                    "stage" => "company",
                    "status" => "success",
                    "message" => "公司 : 通合系統科技有限公司",
                ]
            ]
        ];
        $result = ["status" => "pending", "messages" => []];

        $this->labor_insurance_lib->processMostRecentCompanyName($this->rows, $result);

        $this->assertEquals($expectedResult, $result);
    }
}
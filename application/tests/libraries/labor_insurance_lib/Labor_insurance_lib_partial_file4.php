<?php

class Labor_insurance_lib_partial_file4 extends TestCase
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
		$outfile = dirname(__FILE__, 3) .  "/files/libraries/labor_insurance_lib/9-decoded.pdf";
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
                    "status" => "failure",
                    "message" => "不符合平台規範",
                    "rejected_message" => $this->labor_insurance_lib::REJECT_DUR_TO_CONSTRAINT_NOT_PASSED
                ]
            ]
        ];
        $result = ["status" => "pending", "messages" => []];

        $this->labor_insurance_lib->processMostRecentCompanyName(42775, $this->rows, $result);
        $this->assertEquals($expectedResult, $result);
    }

    public function testProcessCurrentSalary()
    {
        $expectedResult = [
            "status" => "pending",
            "messages" => [
                [
                    "stage" => "salary",
                    "status" => "success",
                    "message" => "投保月薪 : 46000"
                ]
            ]
        ];

        $result = ["status" => "pending", "messages" => []];

        $this->labor_insurance_lib->processCurrentSalary($this->rows, $result);

        $this->assertEquals($expectedResult, $result);
    }
}
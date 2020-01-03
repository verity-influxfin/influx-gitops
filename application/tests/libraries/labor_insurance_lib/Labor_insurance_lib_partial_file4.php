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
                    "rejected_message" => "經平台綜合評估暫時無法核准您的工作認證，感謝您的支持與愛護，希望下次還有機會為您服務。"
                ]
            ]
        ];
        $result = ["status" => "pending", "messages" => []];

        $this->labor_insurance_lib->processMostRecentCompanyName($this->rows, $result);

        $this->assertEquals($expectedResult, $result);
    }
}
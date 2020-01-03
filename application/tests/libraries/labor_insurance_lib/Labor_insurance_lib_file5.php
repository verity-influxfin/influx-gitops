<?php

class Labor_insurance_lib_file5 extends TestCase
{
	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->library('Labor_insurance_lib');
		$this->labor_insurance_lib = $this->CI->labor_insurance_lib;
		$this->readInputFile();
		$this->replaceSensitiveData();
		$this->rows = $this->labor_insurance_lib->readRows($this->text);
	}

	private function readInputFile(){
		$outfile = dirname(__FILE__, 3) .  "/files/libraries/labor_insurance_lib/5-decoded.pdf";
		$parser = new \Smalot\PdfParser\Parser();
		$pdf = $parser->parseFile($outfile);
		$this->text = $pdf->getText();
	}

	private function replaceSensitiveData()
	{
		$extraFile = dirname(__FILE__, 3) .  "/files/libraries/labor_insurance_lib/5-extra.txt";
		$text = file_get_contents($extraFile);
		$json = json_decode($text, true);
		$this->text = str_replace($json["originalName"], $json["replacedName"], $this->text);
		$this->text = str_replace($json["originalId"], $json["replacedId"], $this->text);
		$this->text = str_replace($json["originalDOB"], $json["replacedDOB"], $this->text);
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

	public function testProcessDownloadTimeMatchSearchTime()
	{
		$expectedResult = [
			"status" => "pending",
			"messages" => [
				[
					"stage" => "time_matches",
					"status" => "success",
					"message" => ""
				]
			]
		];

		$downloadTime = 1574899200;
		$result = ["status" => "pending", "messages" => []];
		$this->labor_insurance_lib->processDownloadTimeMatchSearchTime($downloadTime, $this->text, $result);

		$this->assertEquals($expectedResult, $result);
	}

	public function testProcessApplicantDetail()
	{
		$expectedResult = [
			"status" => "pending",
			"messages" => [
				[
					"stage" => "applicant_detail",
					"status" => "success",
					"message" => ""
				]
			]
		];

		$userModel = $this->getMockBuilder('user_model')
						  ->disableOriginalConstructor()
						  ->getMock();

		$user = new stdClass();
		$user->id_number = 'E125941355';
		$user->name = '陳阿達';
		$user->birthday = '1992-12-31';
		$userModel->expects($this->any())
				  ->method('get')
				  ->will($this->returnValue($user));

		$this->labor_insurance_lib->CI->user_model = $userModel;

		$result = ["status" => "pending", "messages" => []];
		$this->labor_insurance_lib->processApplicantDetail(42775, $this->text, $result);

		$this->assertEquals($expectedResult, $result);
	}

	public function testProcessApplicantHavingLaborInsurance()
	{
		$expectedResult = [
			"status" => "pending",
			"messages" => [
				[
					"stage" => "insurance_enrollment",
					"status" => "success",
					"message" => ""
				]
			]
		];

		$result = ["status" => "pending", "messages" => []];

		$this->labor_insurance_lib->processApplicantHavingLaborInsurance($this->rows, $result);

		$this->assertEquals($expectedResult, $result);
	}

	public function testProcessMostRecentCompanyName()
	{
		$expectedResult = [
			"status" => "pending",
			"messages" => [
				[
					"stage" => "company",
					"status" => "pending",
					"message" => "多家投保"
				]
			]
		];
		$result = ["status" => "pending", "messages" => []];

		$this->labor_insurance_lib->processMostRecentCompanyName($this->rows, $result);

		$this->assertEquals($expectedResult, $result);
	}
}

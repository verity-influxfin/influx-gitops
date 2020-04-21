<?php

class Labor_insurance_lib_partial_file1 extends TestCase
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
		$outfile = dirname(__FILE__, 3) .  "/files/libraries/labor_insurance_lib/6-decoded.pdf";
		$parser = new \Smalot\PdfParser\Parser();
		$pdf = $parser->parseFile($outfile);
		$this->text = $pdf->getText();
	}

	private function replaceSensitiveData()
	{
		$extraFile = dirname(__FILE__, 3) .  "/files/libraries/labor_insurance_lib/6-extra.txt";
		$text = file_get_contents($extraFile);
		$json = json_decode($text, true);
		$this->text = str_replace($json["originalName"], $json["replacedName"], $this->text);
		$this->text = str_replace($json["originalId"], $json["replacedId"], $this->text);
		$this->text = str_replace($json["originalDOB"], $json["replacedDOB"], $this->text);
	}

	public function testProcessDownloadTimeMatchSearchTime()
	{
		$expectedResult = [
			"status" => "pending",
			"messages" => [
				[
					"stage" => "time_matches",
					"status" => "failure",
					"message" => ["起始日非空白", "勞保異動明細非歷年"],
					"rejected_message" => $this->labor_insurance_lib::REJECT_DUE_TO_REPORT_NOT_COMPLETE
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

	public function testProcessApplicantDetailWithIdNotBeingIdentified()
	{
		$expectedResult = [
			"status" => "pending",
			"messages" => [
				[
					"stage" => "applicant_detail",
					"status" => "pending",
					"message" => "身分證字號無法判讀"
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

		$text = str_replace($user->id_number, "", $this->text);

		$result = ["status" => "pending", "messages" => []];
		$this->labor_insurance_lib->processApplicantDetail(42775, $text, $result);

		$this->assertEquals($expectedResult, $result);
	}

	public function testProcessApplicantDetailWithNameNotBeingIdentified()
	{
		$expectedResult = [
			"status" => "pending",
			"messages" => [
				[
					"stage" => "applicant_detail",
					"status" => "pending",
					"message" => "姓名無法判讀"
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

		$text = str_replace($user->name, "", $this->text);

		$result = ["status" => "pending", "messages" => []];
		$this->labor_insurance_lib->processApplicantDetail(42775, $text, $result);

		$this->assertEquals($expectedResult, $result);
	}

	public function testProcessApplicantDetailWithDoBNotBeingIdentified()
	{
		$expectedResult = [
			"status" => "pending",
			"messages" => [
				[
					"stage" => "applicant_detail",
					"status" => "pending",
					"message" => "出生日期無法判讀"
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

		$text = str_replace('0811231', "", $this->text);

		$result = ["status" => "pending", "messages" => []];
		$this->labor_insurance_lib->processApplicantDetail(42775, $text, $result);

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
					"status" => "failure",
					"message" => "不符合平台規範",
					"rejected_message" => $this->labor_insurance_lib::REJECT_DUR_TO_CONSTRAINT_NOT_PASSED,
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
					"status" => "pending",
					"message" => ""
				]
			]
		];

		$result = ["status" => "pending", "messages" => []];

		$this->labor_insurance_lib->processCurrentSalary($this->rows, $result);

		$this->assertEquals($expectedResult, $result);
	}

	public function testProcessApplicantHavingGreatSalary()
	{
		$expectedResult = [
			"status" => "pending",
			"messages" => [
				[
					"stage" => "high_salary",
					"status" => "success",
					"message" => "是否為高收入族群 : 是"
				]
			]
		];

		$result = ["status" => "pending", "messages" => []];

		$this->labor_insurance_lib->processApplicantHavingGreatSalary(50001, $result);

		$this->assertEquals($expectedResult, $result);
	}

	public function testProcessCurrentJobExperience()
	{
		$expectedResult = [
			"status" => "pending",
			"messages" => [
				[
					"stage" => "current_job",
					"status" => "success",
					"message" => "現職工作年資 : 2年1月",
					"data" => [
						"year" => 2,
						"month" => 1,
					]
				]
			]
		];

		$result = ["status" => "pending", "messages" => []];
		//2020/1/5
		$this->labor_insurance_lib->setCurrentTime(1578182400);
		$this->labor_insurance_lib->processCurrentJobExperience($this->rows, $result);

		$this->assertEquals($expectedResult, $result);
	}

    public function testProcessApplicantHavingGreatJob()
    {
        $expectedResult = [
            "stage" => "great_job",
            "status" => "success",
            "message" => "是否符合優良職業認定 : 是"
        ];

        $isTopCompany = true;
        $salary = 40001;
        $result = ["status" => "pending", "messages" => []];
        $this->labor_insurance_lib->processApplicantHavingGreatJob($isTopCompany, $salary, $result);

        $this->assertEquals($expectedResult, $result["messages"][0]);
    }
}

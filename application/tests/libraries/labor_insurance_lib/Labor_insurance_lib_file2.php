<?php

class Labor_insurance_lib_file2 extends TestCase
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
		$outfile = dirname(__FILE__, 3) .  "/files/libraries/labor_insurance_lib/2-decoded.pdf";
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
					"message" => "上傳文件錯誤",
					"rejected_message" => $this->labor_insurance_lib::REJECT_DUE_TO_OUTDATED_REPORT
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
		            "status" => "pending",
		            "message" => "無法辨識日期"
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
		            "status" => "pending",
		            "message" => ["無法辨識日期"]
				]
			]
		];

		$downloadTime = 1574208000;
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
					"status" => "failure",
					"message" => "未加保勞保",
					"rejected_message" => $this->labor_insurance_lib::REJECT_DUE_TO_UNEMPLOYMENT,
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
					"message" => "未發現任何仍在加保中的公司名稱",
                    "rejected_message" => $this->labor_insurance_lib::REJECT_DUE_TO_UNEMPLOYMENT
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
					"message" => "是否為高收入族群 : 否"
				]
			]
		];

		$result = ["status" => "pending", "messages" => []];

		$this->labor_insurance_lib->processApplicantHavingGreatSalary(0, $result);

		$this->assertEquals($expectedResult, $result);
	}

	public function testProcessCurrentJobExperience()
	{
		$expectedResult = [
			"status" => "pending",
			"messages" => [
				[
					"stage" => "current_job",
					"status" => "failure",
					"message" => "無",
                    "rejected_message" => $this->labor_insurance_lib::REJECT_DUR_TO_CONSTRAINT_NOT_PASSED
				]
			]
		];

		$result = ["status" => "pending", "messages" => []];
		//2020/1/5
		$this->labor_insurance_lib->setCurrentTime(1578182400);
		$this->labor_insurance_lib->processCurrentJobExperience($this->rows, $result);

		$this->assertEquals($expectedResult, $result);
	}

	public function testProcessTotalJobExperience()
	{
		$expectedResult = [
			"status" => "pending",
			"messages" => [
				[
					"stage" => "total_job",
					"status" => "failure",
					"message" => "無",
                    "rejected_message" => $this->labor_insurance_lib::REJECT_DUR_TO_CONSTRAINT_NOT_PASSED
				]
			]
		];

		$result = ["status" => "pending", "messages" => []];
		//2020/1/5
		$this->labor_insurance_lib->setCurrentTime(1578182400);
		$this->labor_insurance_lib->processTotalJobExperience($this->rows, $result);

		$this->assertEquals($expectedResult, $result);
	}

	public function testProcessJobExperiences()
	{
		$expectedResult = [
			'stage' => 'job',
			'status' => 'failure',
			'message' => '投保年資不足',
			'rejected_message' => $this->labor_insurance_lib::REJECT_DUR_TO_CONSTRAINT_NOT_PASSED
		];
		$certificationModel = $this->getMockBuilder('user_model')
								   ->disableOriginalConstructor()
								   ->getMock();

		$certification = new stdClass();
		$certification->content = "";

		$certificationModel->expects($this->any())
						   ->method('get_by')
						   ->will($this->returnValue($certification));

		$this->labor_insurance_lib->CI->user_certification_model = $certificationModel;

		$result = ["status" => "pending", "messages" => []];
		$this->labor_insurance_lib->setCurrentTime(1578182400);
		$this->labor_insurance_lib->processCurrentJobExperience($this->rows, $result);
		$this->labor_insurance_lib->processTotalJobExperience($this->rows, $result);
		$this->labor_insurance_lib->processJobExperiences($this->rows, $result);

		$this->assertEquals($expectedResult, end($result['messages']));
	}

    public function testProcessApplicantServingWithTopCompany()
    {
        $expectedResult = [
            "stage" => "top_company",
            "status" => "pending",
            "message" => "無法辨識，未成功讀取公司名稱"
        ];

        $this->labor_insurance_lib->topEnterprises = [
            "玉盛國際企業有限公司"
        ];
        $result = ["status" => "pending", "messages" => []];
        $companyName = $this->labor_insurance_lib->processMostRecentCompanyName(42775, $this->rows, $result);
        $this->labor_insurance_lib->processApplicantServingWithTopCompany($companyName, $result);

        $this->assertEquals($expectedResult, $result["messages"][1]);
    }

    public function testProcessApplicantHavingGreatJob()
    {
        $expectedResult = [
            "stage" => "great_job",
            "status" => "success",
            "message" => "是否符合優良職業認定 : 否"
        ];

        $isTopCompany = false;
        $salary = 0;
        $result = ["status" => "pending", "messages" => []];
        $this->labor_insurance_lib->processApplicantHavingGreatJob($isTopCompany, $salary, $result);

        $this->assertEquals($expectedResult, $result["messages"][0]);
    }
}
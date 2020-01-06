<?php

class Labor_insurance_lib_file3 extends TestCase
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
		$outfile = dirname(__FILE__, 3) .  "/files/libraries/labor_insurance_lib/3-decoded.pdf";
		$parser = new \Smalot\PdfParser\Parser();
		$pdf = $parser->parseFile($outfile);
		$this->text = $pdf->getText();
	}

	private function replaceSensitiveData()
	{
		$extraFile = dirname(__FILE__, 3) .  "/files/libraries/labor_insurance_lib/3-extra.txt";
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
		            "status" => "success",
		            "message" => ""
				]
			]
		];

		$downloadTime = 1575331200;
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
					"status" => "success",
					"message" => "公司 : 慈香庭有限公司"
				]
			]
		];
		$result = ["status" => "pending", "messages" => []];

		MonkeyPatch::patchMethod(
			'Labor_insurance_lib',
			['fetchCompanyNameFilledByUser' => "慈香庭有限公司"]
		);

		$this->labor_insurance_lib->processMostRecentCompanyName(42775, $this->rows, $result);

		$this->assertEquals($expectedResult, $result);
	}

	public function testProcessMostRecentCompanyNameWithWrongName()
	{
		$expectedResult = [
			"status" => "pending",
			"messages" => [
				[
					"stage" => "company",
					"status" => "pending",
					"message" => "與user自填公司名稱不一致"
				]
			]
		];
		$result = ["status" => "pending", "messages" => []];

		MonkeyPatch::patchMethod(
			'Labor_insurance_lib',
			['fetchCompanyNameFilledByUser' => ""]
		);

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
					"message" => "投保月薪 : 24000"
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

		$this->labor_insurance_lib->processApplicantHavingGreatSalary(24000, $result);

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
					"message" => "現職工作年資 : 1年2月",
					"data" => [
						"year" => 1,
						"month" => 2
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

	public function testProcessTotalJobExperience()
	{
		$expectedResult = [
			"status" => "pending",
			"messages" => [
				[
					"stage" => "total_job",
					"status" => "success",
					"message" => "總工作年資 : 5年9月",
					"data" => [
						"year" => 5,
						"month" => 9
					]
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
			'status' => 'success',
			'message' => '總工作年資一年(含)以上且現職工作年資四個月(含)以上',
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
            "status" => "success",
            "message" => "是否為千大企業之員工 : 否"
        ];

        MonkeyPatch::patchMethod(
            'Labor_insurance_lib',
            ['fetchCompanyNameFilledByUser' => "慈香庭有限公司"]
        );

        $this->labor_insurance_lib->topEnterprises = [
            "玉盛國際企業有限公司"
        ];
        $result = ["status" => "pending", "messages" => []];
        $companyName = $this->labor_insurance_lib->processMostRecentCompanyName(42775, $this->rows, $result);
        $this->labor_insurance_lib->processApplicantServingWithTopCompany($companyName, $result);

        $this->assertEquals($expectedResult, $result["messages"][1]);
    }
}

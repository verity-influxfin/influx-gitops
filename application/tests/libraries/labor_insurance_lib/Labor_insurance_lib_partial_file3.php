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

        MonkeyPatch::patchMethod(
            'Labor_insurance_lib',
            ['fetchCompanyNameFilledByUser' => "通合系統科技有限公司"]
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
					"message" => "與user自填公司名稱不一致",
				]
			]
		];
		$result = ["status" => "pending", "messages" => []];

		MonkeyPatch::patchMethod(
			'Labor_insurance_lib',
			['fetchCompanyNameFilledByUser' => null]
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
					"message" => "投保月薪 : 26000"
				]
			]
		];

		$result = ["status" => "pending", "messages" => []];

		$this->labor_insurance_lib->processCurrentSalary($this->rows, $result);

		$this->assertEquals($expectedResult, $result);
	}

	public function testProcessJobExperiences()
	{
		$expectedResult = [
			'stage' => 'job',
			'status' => 'pending',
			'message' => '畢業一年內之上班族總工作年資不足一年，且現職工作年資不足四個月'
		];
		$certificationModel = $this->getMockBuilder('user_model')
								   ->disableOriginalConstructor()
								   ->getMock();

		$certification = new stdClass();
		$certification->content = [
			'graduate_date' => '民國108年6月30日'
		];
		$certification->content = json_encode($certification->content);
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

	public function testProcessJobExperiencesWithMoreThanFourMonth()
	{
		$expectedResult = [
			'stage' => 'job',
			'status' => 'success',
			'message' => '畢業一年內之上班族總工作年資不足一年但現職工作年資四個月(含)以上'
		];
		$certificationModel = $this->getMockBuilder('user_model')
								   ->disableOriginalConstructor()
								   ->getMock();

		$certification = new stdClass();
		$certification->content = [
			'graduate_date' => '民國108年6月30日'
		];
		$certification->content = json_encode($certification->content);
		$certificationModel->expects($this->any())
						   ->method('get_by')
						   ->will($this->returnValue($certification));

		$this->labor_insurance_lib->CI->user_certification_model = $certificationModel;

		$result = ["status" => "pending", "messages" => []];
		$this->labor_insurance_lib->setCurrentTime(1580860800);
		$this->labor_insurance_lib->processCurrentJobExperience($this->rows, $result);
		$this->labor_insurance_lib->processTotalJobExperience($this->rows, $result);
		$this->labor_insurance_lib->processJobExperiences($this->rows, $result);

		$this->assertEquals($expectedResult, end($result['messages']));
	}
}
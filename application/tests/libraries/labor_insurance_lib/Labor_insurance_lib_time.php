<?php

class Labor_insurance_lib_time extends TestCase
{
	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->library('Labor_insurance_lib');
		$this->labor_insurance_lib = $this->CI->labor_insurance_lib;
	}

	private function readInputFile(){
		$outfile = dirname(__FILE__, 3) .  "/files/libraries/labor_insurance_lib/9-decoded.pdf";
		$parser = new \Smalot\PdfParser\Parser();
		$pdf = $parser->parseFile($outfile);
		$this->text = $pdf->getText();
	}

    public function testTime1()
    {
        $expectedResult = [
            'year' => 4,
            'month' => 11
        ];
        $start = '1040129';
        $end = '1090105';
        $result = $this->labor_insurance_lib->compareTaiwanTime($start, $end);

        $this->assertEquals($expectedResult, $result);
    }

    public function testTime2()
    {
        $expectedResult = [
            'year' => 5,
            'month' => 9
        ];
        $start = '1030330';
        $end = '1090105';
        $result = $this->labor_insurance_lib->compareTaiwanTime($start, $end);

        $this->assertEquals($expectedResult, $result);
    }

    public function testTime3()
    {
        $expectedResult = [
            'year' => 3,
            'month' => 9
        ];
        $start = '1050308';
        $end = '1090105';
        $result = $this->labor_insurance_lib->compareTaiwanTime($start, $end);

        $this->assertEquals($expectedResult, $result);
    }

    public function testTime4()
    {
        $expectedResult = [
            'year' => 7,
            'month' => 0
        ];
        $start = '1020102';
        $end = '1090105';
        $result = $this->labor_insurance_lib->compareTaiwanTime($start, $end);

        $this->assertEquals($expectedResult, $result);
    }

    public function testTime5()
    {
        $expectedResult = [
            'year' => 9,
            'month' => 3
        ];
        $start = '0990929';
        $end = '1090105';
        $result = $this->labor_insurance_lib->compareTaiwanTime($start, $end);

        $this->assertEquals($expectedResult, $result);
    }

    public function testTime6()
    {
        $expectedResult = [
            'year' => 9,
            'month' => 11
        ];
        $start = '0990126';
        $end = '1090105';
        $result = $this->labor_insurance_lib->compareTaiwanTime($start, $end);

        $this->assertEquals($expectedResult, $result);
    }

    public function testTime7()
    {
        $expectedResult = [
            'year' => 0,
            'month' => 3
        ];
        $start = '1081004';
        $end = '1090105';
        $result = $this->labor_insurance_lib->compareTaiwanTime($start, $end);

        $this->assertEquals($expectedResult, $result);
    }

    public function testTime8()
    {
        $expectedResult = [
            'year' => 2,
            'month' => 4
        ];
        $start = '1060821';
        $end = '1090105';
        $result = $this->labor_insurance_lib->compareTaiwanTime($start, $end);

        $this->assertEquals($expectedResult, $result);
    }

    public function testTime9()
    {
        $expectedResult = [
            'year' => 2,
            'month' => 4
        ];
        $start = '1060904';
        $end = '1090105';
        $result = $this->labor_insurance_lib->compareTaiwanTime($start, $end);

        $this->assertEquals($expectedResult, $result);
    }

    public function testTime10()
    {
        $expectedResult = [
            'year' => 6,
            'month' => 5
        ];
        $start = '1020706';
        $end = '1090105';
        $result = $this->labor_insurance_lib->compareTaiwanTime($start, $end);

        $this->assertEquals($expectedResult, $result);
    }
}
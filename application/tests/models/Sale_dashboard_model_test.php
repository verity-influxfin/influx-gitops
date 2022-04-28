<?php

class Sale_dashboard_model_test extends TestCase
{
    public function setUp(): void
    {
        $this->resetInstance();
        $this->CI->load->model('user/Sale_dashboard_model');
        $this->obj = $this->CI->Sale_dashboard_model;
    }

    public function test_get_amounts_at()
    {
        $expected = 16;

        $input_types = [$this->obj::TARGET_WEB_TRAFFIC];

        $date = new DateTimeImmutable(date('Y-m-d'));
        $amounts1 = $this->obj->get_amounts_at($date);

        $this->assertEquals($expected, count($amounts1));

        $amounts2 = $this->obj->get_amounts_at($date, $input_types);
        $this->assertEquals($expected, count($amounts2));
    }

    public function test_get_loan_mapping_eboard_key_to_type()
    {
        $expected = [
            'SMART_STUDENT' => 6,
            'STUDENT' => 5,
            'SALARY_MAN' => 4,
            'SK_MILLION' => 14,
        ];
        $mapping = $this->obj->get_loan_mapping_eboard_key_to_type();

        $this->assertEquals($expected, $mapping);
    }

    public function test_get_deal_mapping_eboard_key_to_type()
    {
        $expected = [
            'SALARY_MAN' => 7,
            'STUDENT' => 8,
            'SMART_STUDENT' => 9,
            'SK_MILLION' => 15,
        ];
        $mapping = $this->obj->get_deal_mapping_eboard_key_to_type();

        $this->assertEquals($expected, $mapping);
    }

    public function test_get_amounts_by_month()
    {
        $expected = 16;

        $no_data_month = '202002';
        $has_data_month = '202202';

        $no_data = $this->obj->get_amounts_by_month($no_data_month);
        $this->assertEquals($expected, count($no_data));

        $data = $this->obj->get_amounts_by_month($has_data_month);
        $this->assertEquals($expected, count($data));
    }

}

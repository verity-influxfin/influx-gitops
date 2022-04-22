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
        // 這功能原本只有紀錄 官網流量、android下載數、ios下載數
        $expected_input_no_types = 3;
        $expected_input_1_type = 1;

        $input_types = [$this->obj::TARGET_WEB_TRAFFIC];

        // 因為 2022-02-14 在 stage-5 裡面有資料，如果其他沒資料的日子會爆炸
        $date = new DateTimeImmutable(date('2022-02-14'));
        $amounts1 = $this->obj->get_amounts_at($date);

        $this->assertEquals($expected_input_no_types, count($amounts1));

        $amounts2 = $this->obj->get_amounts_at($date, $input_types);
        $this->assertEquals($expected_input_1_type, count($amounts2));
    }

}

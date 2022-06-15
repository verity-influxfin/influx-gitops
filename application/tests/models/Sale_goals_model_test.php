<?php

class Sale_goals_model_test extends TestCase
{
    public function setUp(): void
    {
        $this->resetInstance();
        $this->CI->load->model('user/Sale_goals_model');
        $this->obj = $this->CI->Sale_goals_model;
    }

    public function test_type_name_mapping()
    {
        $expected = [
            0 => '官網流量',
            1 => 'APP下載',
            2 => '申貸總計',
            3 => '會員註冊',
            4 => '上班族貸申貸',
            5 => '學生貸申貸',
            6 => '3S名校貸申貸',
            7 => '上班族貸成交',
            8 => '學生貸成交',
            9 => '3S名校貸成交',
            10 => '信保專案申貸',
            11 => '中小企業申貸',
            12 => '信保專案成交',
            13 => '中小企業成交',
        ];
        $goal_list = $this->obj->type_name_mapping();
        foreach ($goal_list as $key => $goal)
        {
            $this->assertEquals($expected[$key], $goal);
        }
    }

    public function test_get_goals_number_at_this_month()
    {
        $expected_type = 14;
        $expected_keys = ['id', 'type', 'number'];

        $goals_number = $this->obj->get_goals_number_at_this_month();
        $this->assertEquals($expected_type, count($goals_number));

        foreach ($goals_number as $key => $value)
        {
            $this->assertArrayHasKey($expected_keys[0], $value);
            $this->assertArrayHasKey($expected_keys[1], $value);
            $this->assertArrayHasKey($expected_keys[2], $value);
        }
    }
}

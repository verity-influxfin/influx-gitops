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
        $expected_keys = ['id', 'number'];

        $goals_number = $this->obj->get_goals_number_at_this_month();
        $this->assertEquals($expected_type, count($goals_number));

        foreach ($goals_number as $key => $value)
        {
            $this->assertArrayHasKey($expected_keys[0], $value);
            $this->assertArrayHasKey($expected_keys[1], $value);
        }
    }

    public function test_add_year_to_export_month()
    {
        $expected1 = '202201'; // 用戶選取過去的月份
        $expected2 = '202112'; // 用戶選取未來的月份

        $at_month1 = $this->obj->add_year_to_export_month('01');
        $this->assertEquals($expected1, $at_month1);

        $at_month2 = $this->obj->add_year_to_export_month('12');
        $this->assertEquals($expected2, $at_month2);
    }


    public function test_get_goals_at_month()
    {
        $expected_type_count = 14;
        
        $goals = $this->obj->get_goals_at_month('202204');
        $this->assertEquals($expected_type_count, count($goals));
    }
}

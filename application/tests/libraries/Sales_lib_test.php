<?php
/**
 * Part of ci-phpunit-test
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2015 Kenji Suzuki
 * @link       https://github.com/kenjis/ci-phpunit-test
 */

class Sales_lib_test extends TestCase
{
    public function setUp(): void
    {
        $this->resetInstance();
        $at_month = date('Ym');
        $this->CI->load->library('sales_lib', ['at_month' => $at_month]);
        $this->obj = $this->CI->sales_lib;
    }

    public function test_get_goals()
    {
        $expected_keys = ['goal_a_month', 'goal_per_day'];
        $goals = $this->obj->get_goals();

        foreach ($expected_keys as $value)
        {
            $this->assertArrayHasKey($value, $goals);
        }
    }

    public function test_get_goals_id()
    {
        $this->CI->load->model('user/sale_goals_model');
        $types = $this->CI->sale_goals_model->type_name_mapping();
        $expected = count($types);

        $goals = $this->obj->get_goals_id();
        $this->assertEquals($expected, count($goals));
    }

    public function test_get_days()
    {
        $expected_keys = ['date', 'week', 'int_month'];

        $days_info = $this->obj->get_days();
        foreach ($expected_keys as $value)
        {
            $this->assertArrayHasKey($value, $days_info);
        }

        $this->assertEquals(date('n'), $days_info['int_month']);
    }
}

<?php

class User_model_test extends TestCase
{
    public function setUp(): void
    {
        $this->resetInstance();
        $this->CI->load->model('user/User_model');
        $this->obj = $this->CI->User_model;
    }

    public function test_get_new_members_at_day()
    {
        $date = new DateTimeImmutable(date('Y-m-d'));
        $members = $this->obj->get_new_members_at_day($date->modify('+1 day'));

        $this->assertEquals(0, $members);
    }
}

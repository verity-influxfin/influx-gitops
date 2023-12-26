<?php

namespace Approve_target;

use Approve_target\Credit\Approve_target_credit_base;
use TestCase;

class Approve_factory_test extends TestCase
{
    public function setUp(): void
    {
        $this->resetInstance();
        $this->CI->load->model('target_model');
        $this->obj = $this->CI->target_model;
    }

    /**
     * @dataProvider provider_exist
     */
    public function test_get_instance_by_model_data_exist(int $product_id, int $sub_product_id)
    {
        $target = $this->get_target($product_id, $sub_product_id);
        $approve_instance = (new Approve_factory())->get_instance_by_model_data($target);
        $this->assertTrue(method_exists($approve_instance, 'approve'));
    }

    /**
     * @dataProvider provider_not_exist
     */
    public function test_get_instance_by_model_data_not_exist(int $product_id, int $sub_product_id)
    {
        $target = $this->get_target($product_id, $sub_product_id);
        $approve_instance = (new Approve_factory())->get_instance_by_model_data($target);
        $this->assertFalse(method_exists($approve_instance, 'approve'));
    }

    public function provider_exist(): array
    {
        return [
            [PRODUCT_ID_STUDENT, 0]
        ];
    }

    public function provider_not_exist(): array
    {
        return [
            [PRODUCT_ID_STUDENT, 1],
            [PRODUCT_ID_SALARY_MAN, 0],
            [PRODUCT_ID_SALARY_MAN, 1],
        ];
    }


    private function get_target(int $product_id, int $sub_product_id)
    {
        return $this->obj->get_by([
            'status' => [TARGET_WAITING_APPROVE, TARGET_ORDER_WAITING_VERIFY],
            'script_status' => 0,
            'product_id' => $product_id,
            'sub_product_id' => $sub_product_id,
        ]);
    }
}

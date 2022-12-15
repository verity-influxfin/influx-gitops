<?php

namespace Approve_target\Order;

use Approve_target\Approve_base;
use Approve_target\Approve_target_result;

/**
 * 核可 消費貸款
 */
class Approve_target_order_base extends Approve_base
{
    /**
     * 是否為待核可狀態
     * @return bool
     */
    protected function is_waiting_approve_status(): bool
    {
        return $this->target['status'] == TARGET_ORDER_WAITING_VERIFY;
    }

    /**
     * 取得 property $result 的初始值
     * @return Approve_target_result
     */
    public function get_initial_result(): Approve_target_result
    {
        // status=待審批 (分期)
        return new Approve_target_result(TARGET_ORDER_WAITING_SHIP, TARGET_SUBSTATUS_NORNAL);
    }

    /**
     * 檢查使用者提交的徵信項
     * @param $user_certs
     * @return bool
     */
    protected function check_cert($user_certs): bool
    {
        // TODO: Implement check_cert() method.
        // 到各產品實作
    }

    /**
     * 檢查是否符合產品設定
     * @return bool
     */
    protected function check_product(): bool
    {
        // TODO: Implement check_product() method.
        // 到各產品實作
    }

    /**
     * 是否已提交審核 (部分產品需檢查 targets.certificate_status )
     * @return bool
     */
    protected function is_submitted(): bool
    {
        return TRUE;
    }

    /**
     * 依不同產品檢查是否需進二審
     * @return bool
     */
    protected function check_need_second_instance_by_product(): bool
    {
        // TODO: Implement check_need_second_instance_by_product() method.
    }
}
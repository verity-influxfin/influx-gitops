<?php

namespace Approve_target\Credit;

use Approve_target\Approve_base;
use Approve_target\Approve_target_result;

/**
 * 核可 信用貸款
 */
abstract class Approve_target_credit_base extends Approve_base
{
    /**
     * 是否為待核可狀態
     * @return bool
     */
    protected function is_waiting_approve_status(): bool
    {
        return $this->target['status'] == TARGET_WAITING_APPROVE;
    }
}
<?php

namespace Approve_target;

interface Approve_interface
{
    /**
     * 案件核可主行為
     * @param bool $renew : 是否為人工同意通過
     * @return mixed
     */
    public function approve(bool $renew);

    /**
     * 案件審核成功
     * @param bool $renew : 是否為人工同意通過
     * @return mixed
     */
    public function set_target_success(bool $renew);

    /**
     * 案件審核失敗
     * @return bool
     */
    public function set_target_failure(): bool;

    /**
     * 審核成功的通知
     * @param bool $subloan_status : 是否為產轉案件
     * @return bool
     */
    public function success_notify(bool $subloan_status): bool;

    /**
     * 審核失敗的通知
     * @param bool $subloan_status : 是否為產轉案件
     * @return bool
     */
    public function failure_notify(bool $subloan_status): bool;
}
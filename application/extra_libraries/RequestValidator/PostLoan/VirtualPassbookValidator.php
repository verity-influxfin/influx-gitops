<?php
namespace RequestValidator\PostLoan;
use RequestValidator\ValidatorBase;

class VirtualPassbookValidator extends ValidatorBase
{
    function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->CI->load->model('user/virtual_account_model');
    }

    /**
     * 驗證是否有權限
     * @param array $input: 輸入的參數
     * @param array $allowedParameters: 要驗證的參數
     * @return bool
     */
    public function checkPermission(array $input, array $allowedParameters): bool {
        $permissionDenied = false;

        if(isset($input['virtual_account'])) {
            // 需要有逾期的用戶才可以查看
            $virtualAccount = $this->CI->virtual_account_model->get_by(['virtual_account'=>$input['virtual_account']]);
            if(isset($virtualAccount)) {
                $delayedTarget = $this->CI->target_model->get_many_by(['user_id' => $virtualAccount->user_id, 'delay_days >' => GRACE_PERIOD]);
                if(empty($delayedTarget)) {
                    $delayedTarget = $this->CI->user_model->getDelayedTargetByInvestor($virtualAccount->user_id);
                    if(empty($delayedTarget)) {
                        $permissionDenied = true;
                    }
                }
            }else{
                $permissionDenied = true;
            }
        }
        return !$permissionDenied && parent::checkPermission($input, $allowedParameters);
    }
}
<?php
namespace RequestValidator\PostLoan;
use RequestValidator\ValidatorBase;

class UserValidator extends ValidatorBase
{
    function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->CI->load->model('loan/target_model');
        $this->CI->load->model('user/user_model');
    }

    /**
     * 驗證是否有權限
     * @param array $input: 輸入的參數
     * @param array $allowedParameters: 要驗證的參數
     * @return bool
     */
    public function checkPermission(array $input, array $allowedParameters): bool {
        $permissionDenied = false;

        if(isset($input['id'])) {
            // 需要有逾期的用戶才可以查看
            $delayedTarget = $this->CI->target_model->get_many_by(['user_id' => $input['id'], 'delay_days >' => GRACE_PERIOD]);
            if(empty($delayedTarget)) {
                // 投資人需要持有逾期案才可以看
                $delayedTarget = $this->CI->user_model->getDelayedTargetByInvestor($input['id']);
                if(empty($delayedTarget)) {
                    $permissionDenied = true;
                }
            }
        }
        return !$permissionDenied && parent::checkPermission($input, $allowedParameters);
    }
}
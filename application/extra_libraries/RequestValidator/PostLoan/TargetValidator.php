<?php
namespace RequestValidator\PostLoan;
use RequestValidator\ValidatorBase;

class TargetValidator extends ValidatorBase
{
    function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->CI->load->model('loan/target_model');
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
            // 需要有逾期是逾期案才可以查看
            $target = $this->CI->target_model->get($input['id']);
            if($target->delay_days <= GRACE_PERIOD) {
                $permissionDenied = true;
            }
        }
        return !$permissionDenied && parent::checkPermission($input, $allowedParameters);
    }
}
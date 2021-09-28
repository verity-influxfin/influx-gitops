<?php
namespace RequestValidator\PostLoan;
use RequestValidator\ValidatorBase;

class CertificationValidator extends ValidatorBase
{
    private $allowCertificationIdList = [
        CERTIFICATION_IDCARD,
        CERTIFICATION_STUDENT,
        CERTIFICATION_DEBITCARD
    ];

    function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->CI->load->model('user/user_certification_model');
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
            $certInfo = $this->CI->user_certification_model->get($input['id']);
            if(!in_array($certInfo->certification_id, $this->allowCertificationIdList)) {
                $permissionDenied = true;
            }
        }
        return !$permissionDenied && parent::checkPermission($input, $allowedParameters);
    }
}
<?php
namespace CreditSheet;
defined('BASEPATH') OR exit('No direct script access allowed');

class ArchivingPersonalCreditSheet extends PersonalCreditSheet {

    function __construct($target)
    {
        parent::__construct($target);
        $this->CI->load->model('user/user_certification_model');
    }

    /**
     * 取得已封存的認證項目之對應數值
     * @return array
     */
    protected function getAllCertification() {
        $targetData = json_decode($this->target->target_data,true);
        $response = [];

        if(isset($targetData['certification_id']) && is_array($targetData['certification_id'])) {
            $certs = $this->CI->user_certification_model->get_many_by(['id' => $targetData['certification_id']]);
            if(!empty($certs)) {
                $certList = array_reduce($certs, function ($list, $item) {
                    $list[$item->certification_id] = $item;
                    return $list;
                }, []);
            }
        }

        foreach ($this->certificationProcessList as $certId => $columnList) {
            $response = array_merge($response, $this->getArchivingCertInfo(isset($certList[$certId]) ? $certList[$certId] : False, $columnList));
        }
        return $response;
    }

    /**
     * 取得指定徵信項目的資料
     * @param stdclass $info: 認證項目
     * @param array $selectColumnList: 需求欄位名稱列表
     * @return array
     */
    protected function getArchivingCertInfo($info, $selectColumnList) {
        $result = [];
        if($info) {
            $info->content = json_decode($info->content,true);
            foreach ($selectColumnList as $columnName) {
                $result[$columnName] = isset($info->content[$columnName]) ? $info->content[$columnName] : '';
                if(is_numeric($result[$columnName]))
                    $result[$columnName] = intval($result[$columnName]);
            }
        }else{
            $result = array_fill_keys($selectColumnList, '');
        }
        return $result;
    }

    /**
     * 取得授信核貸層次(已審核過)
     * @return int
     */
    protected function getReviewedLevel() {
        if ($this->target->sub_status == TARGET_SUBSTATUS_SECOND_INSTANCE_TARGET) {
            // 二審過件
            return self::CREDIT_REVIEW_LEVEL_MANUAL;
        }else{
            // 一審自動過
            return self::CREDIT_REVIEW_LEVEL_SYSTEM;
        }
        return 0;
    }
}

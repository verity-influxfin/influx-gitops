<?php
namespace CreditSheet\BasicInfo;
defined('BASEPATH') OR exit('No direct script access allowed');

class ArchivingPersonalBasicInfo extends PersonalBasicInfo {

    function __construct()
    {
        parent::__construct();
        $this->CI->load->model('user/user_certification_model');
    }

    /**
     * 取得已封存的認證項目之對應數值
     * @return array
     */
    protected function getAllCertification(): array
    {
        $response = [];

        if(isset($this->creditSheet->creditSheetRecord->certification_list))
            $certificationIdList = json_decode($this->creditSheet->creditSheetRecord->certification_list,true);

        if(isset($certificationIdList) && is_array($certificationIdList)) {
            $certs = $this->CI->user_certification_model->get_many_by(['id' => $certificationIdList]);
            if(!empty($certs)) {
                $certList = array_reduce($certs, function ($list, $item) {
                    $list[$item->certification_id] = $item;
                    return $list;
                }, []);
            }
        }

        foreach ($this->certificationProcessList as $certId => $columnList) {
            $response = array_merge($response, $this->getArchivingCertInfo($certList[$certId] ?? False, $columnList));
        }
        return $response;
    }

    /**
     * 取得指定徵信項目的資料
     * @param stdclass $info: 認證項目
     * @param array $selectColumnList: 需求欄位名稱列表
     * @return array
     */
    protected function getArchivingCertInfo($info, $selectColumnList): array
    {
        $result = [];
        if($info) {
            $info->content = json_decode($info->content,true);
            foreach ($selectColumnList as $columnName) {
                $result[$columnName] = $info->content[$columnName] ?? '';
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
    protected function getReviewedLevel(): int
    {
        if ($this->creditSheet->target->sub_status == TARGET_SUBSTATUS_SECOND_INSTANCE_TARGET) {
            // 二審過件
            return self::CREDIT_REVIEW_LEVEL_MANUAL;
        }else{
            // 一審自動過
            return self::CREDIT_REVIEW_LEVEL_SYSTEM;
        }
    }

    /**
     * 取得製表日期
     * @return string
     */
    protected function getReportDate(): string
    {
        if(isset($this->creditSheet->creditSheetRecord->created_at))
            return date("Y/m/d", strtotime($this->creditSheet->creditSheetRecord->created_at));
        else
            return '';
    }

    /**
     * 取得信評產生日期
     * @return string
     */
    protected function getCreditDate(): string
    {
        if(isset($this->creditSheet->creditRecord->created_at)) {
            return date("Y/m/d", $this->creditSheet->creditRecord->created_at);
        }else
            return '';
    }

    /**
     * 取得信評等級
     * @return string
     */
    protected function getCreditRank(): string
    {
        return $this->creditSheet->creditRecord->level ?? '';
    }
}

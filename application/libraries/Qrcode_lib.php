<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use EdmEvent\EdmEventFactory;

class Qrcode_lib {

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('log/log_userlogin_model');
        $this->CI->load->model('user/user_model');
    }

    /**
     * 取得 qrcode 的合約格式對應內容
     * @param string $type_name
     * @param string $name
     * @param string $address
     * @param array $settings
     * @param string $contract_date
     * @return array
     */
    public function get_contract_format_content(string $type_name, string $name='', string $address='', array $settings=[], string $contract_date=''): array
    {
        $time = empty($contract_date) ? time() : strtotime($contract_date);
        $contract_year = date('Y', $time) - 1911;
        $contract_month = date('m', $time);
        $contract_day = date('d', $time);
        switch ($type_name) {
            case PROMOTE_GENERAL_CONTRACT_TYPE_NAME:
                return [$name, $contract_year, $contract_month, $contract_day,
                $settings['reward']['product']['student']['amount'] ?? 0, $settings['reward']['product']['salary_man']['amount'] ?? 0,
                    $settings['reward']['product']['small_enterprise']['amount'] ?? 0,
                    $name, $name, $address,
                $contract_year, $contract_month, $contract_day];
                break;
            case PROMOTE_APPOINTED_CONTRACT_TYPE_NAME:
                return [$name, $contract_year, $contract_month, $contract_day,
                    $settings['reward']['product']['student']['percent'] ?? 0, $settings['reward']['product']['salary_man']['percent'] ?? 0,
                    $settings['reward']['collaboration_person']['amount'] ?? 0, $settings['reward']['collaboration_enterprise']['amount'] ?? 0,
                    $name, $name, $address,
                    $contract_year, $contract_month, $contract_day];
                break;
        }
        return [];
    }

    public function get_contract_type_by_alias($alias): string
    {
        $this->CI->load->model('user/qrcode_setting_model');
        $contract_type_name = '';
        if($alias == $this->CI->qrcode_setting_model->generalCaseAliasName) {
            $contract_type_name = PROMOTE_GENERAL_CONTRACT_TYPE_NAME;
        }else if($alias == $this->CI->qrcode_setting_model->appointedCaseAliasName) {
            $contract_type_name = PROMOTE_APPOINTED_CONTRACT_TYPE_NAME;
        }
        return $contract_type_name;
    }

    private function filter_delayed_target($data, array $targetIds, $needle) {
        if(!empty($targetIds)) {
            $delayedTargets = $this->CI->target_model->getDelayedTarget($targetIds);
            $delayedTargets = array_column($delayedTargets, NULL, $needle);
            foreach ($data as $key => $info) {
                if(array_key_exists($info[$needle], $delayedTargets))
                    unset($data[$key]);
            }
            $data = array_values($data);
        }
        return $data;
    }

    public function get_product_reward_list(array $qrcode_where, array $product_id_list, array $status_list, string $start_time='', string $end_time='', bool $filter_delayed=FALSE): array
    {
        $this->CI->load->model('user/user_qrcode_model');
        $rewardList = $this->CI->user_qrcode_model->getLoanedCount($qrcode_where, $product_id_list, $status_list, $start_time, $end_time);
        if($filter_delayed) {
            $rewardList = $this->filter_delayed_target($rewardList, array_column($rewardList, 'id'), 'id');
        }
        return $rewardList;
    }

    public function get_borrower_platform_fee_list(array $qrcode_where, array $product_id_list, array $status_list, string $start_time='', string $end_time='', bool $filter_delayed=FALSE) {
        $this->CI->load->model('user/user_qrcode_model');
        $rewardList = $this->CI->user_qrcode_model->getBorrowerPlatformFeeList($qrcode_where, $product_id_list, $status_list, $start_time, $end_time);
        if($filter_delayed) {
            $rewardList = $this->filter_delayed_target($rewardList, array_column($rewardList, 'id'), 'id');
        }
        return $rewardList;
    }

    public function get_investor_platform_fee_list(array $qrcode_where, array $product_id_list, array $status_list, string $start_time='', string $end_time='', bool $filter_delayed=FALSE) {
        $this->CI->load->model('user/user_qrcode_model');
        $rewardList = $this->CI->user_qrcode_model->getInvestorPlatformFeeList($qrcode_where, $product_id_list, $status_list, $start_time, $end_time);
        if($filter_delayed) {
            $rewardList = $this->filter_delayed_target($rewardList, array_column($rewardList, 'id'), 'id');
        }
        return $rewardList;
    }

}
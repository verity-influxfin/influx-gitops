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
    }

    public function get_contract_type_by_alias($alias): string
    {
        $this->CI->load->model('user/qrcode_setting_model');
        $contract_type_name = '';
        switch ($alias) {
            case $this->CI->qrcode_setting_model->generalCaseAliasName:
                $contract_type_name = PROMOTE_GENERAL_CONTRACT_TYPE_NAME;
                break;
            case $this->CI->qrcode_setting_model->appointedCaseAliasName:
                $contract_type_name = PROMOTE_APPOINTED_CONTRACT_TYPE_NAME;
                break;
        }
        return $contract_type_name;
    }
}
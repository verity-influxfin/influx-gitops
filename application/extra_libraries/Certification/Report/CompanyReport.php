<?php
namespace Certification\Report;

defined('BASEPATH') OR exit('No direct script access allowed');

class CompanyReport extends CertificationBase {

    // 徵信報告類型
    protected $type = self::TYPE_COMPANY;

    // 視圖讀取路徑
    protected $view_path = 'admin/certification/company_report';

    /**
     * CorporateReport constructor.
     * @param $target
     * @param $user
     */
    function __construct($target, $user)
    {
        $this->CI = &get_instance();

        $this->target = $target;
        $this->user = $user;
    }

    /**
     * 取得視圖讀取路徑
     * @return string
     */
    public function get_view_path() : string
    {
        return $this->view_path;
    }

    /**
     * 取得資料
     * @return array
     */
    public function get_data(): array
    {
        $response = [];
        return $response;
    }
    /**
     * 送出資料
     * @return array
     */
    public function send_data(): array
    {
        $response = [];
        return $response;
    }
}

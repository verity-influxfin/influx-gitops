<?php defined('BASEPATH') or exit('No direct script access allowed');

class Enterprise extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function credit_sheet($target_id)
    {
        if ( ! is_numeric($target_id))
        {
            echo 'no target.';
            exit;
        }

        // TODO 以下 page_data 的參數都是先塞示意圖的資料，需要改從 model 抓之類
        $page_data['name'] = 'xxx股份有限公司';
        $page_data['type'] = '製造業';
        $page_data['total'] = $target_id;

        $page_data['details'] = [
            [
                'item' => '產業要素',
                'subitem' => '產業生命週期',
                'options' => '成長期',
                'score' => '10',
            ],
            [
                'item' => '借戶要素',
                'subitem' => '企業經營資歷 經濟部公司設立年資',
                'options' => '2年',
                'score' => '6',
            ],
            [
                'item' => '',
                'subitem' => '實收資本額(經濟部登記資訊)',
                'options' => '7,500,000',
                'score' => '4',
            ],
            [
                'item' => '經營效能',
                'subitem' => '...',
                'options' => '',
                'score' => '',
            ],
            [
                'item' => '',
                'subitem' => '...',
                'options' => '',
                'score' => '',
            ],
            [
                'item' => '',
                'subitem' => '...',
                'options' => '',
                'score' => '',
            ],
            [
                'item' => '',
                'subitem' => '...',
                'options' => '',
                'score' => '',
            ],
            [
                'item' => '財務要素',
                'subitem' => '...',
                'options' => '',
                'score' => '',
            ],
            [
                'item' => '保證要素',
                'subitem' => '...',
                'options' => '',
                'score' => '',
            ],
            [
                'item' => 'DD查核',
                'subitem' => '...',
                'options' => '',
                'score' => '',
            ],
        ];

        $this->load->view('admin/_header');
        // $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/enterprise_credit_sheet', $page_data);
        $this->load->view('admin/_footer');
    }
}

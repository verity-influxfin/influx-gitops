<?php

defined('BASEPATH') or exit('No direct script access allowed');
require(APPPATH . '/libraries/MY_Admin_Controller.php');

class Ntu extends MY_Admin_Controller
{
    public $status_list = [];
    public $source_list = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user/ntu_model');

        $this->status_list = [
            0 => '未顯示',
            1 => '已顯示',
        ];

        $this->source_list = [
            0 => 'APP新增',
            1 => '人工新增',
        ];
    }

    public function index()
    {
        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/ntu.php');
        $this->load->view('admin/_footer');
    }

    // 取得列表
    public function get_list()
    {
        // 取得搜尋條件
        $get = $this->input->get(NULL, TRUE);
        $data = $this->ntu_model->get_list(
            $get['user_id'] ?? 0       // 投資人ID
        );

        array_walk($data, function (&$value, $key) use (&$data) {
            $data[$key]['status_title'] = $this->status_list[$data[$key]['status']] ?? '';
            $data[$key]['source_title'] = $this->source_list[$data[$key]['data_source']] ?? '';
        }, $data);

        echo json_encode($data);
    }

    // 取得單筆資料
    public function get_info()
    {
        $id = $this->input->get('id');
        $data = [];
        if ( ! empty($id))
        {
            $data = $this->ntu_model->get_info($id);
            $data['status_title'] = $this->status_list[$data['status']] ?? '';
            $data['source_title'] = $this->source_list[$data['data_source']] ?? '';
        }

        echo json_encode($data);
    }

    // 更新
    public function update_info()
    {
        try
        {
            $post = json_decode($this->input->raw_input_stream, TRUE);

            if (empty($post['id']))
            {
                throw new Exception('刪除失敗，查無此ID');
            }

            $this->ntu_model->delete($post['id']);

            echo json_encode([
                'result' => 'SUCCESS'
            ]);
        }
        catch (Exception $e)
        {
            echo json_encode([
                'result' => 'ERROR',
                'msg' => $e->getMessage()
            ]);
        }
    }

    // 新增
    public function add_info()
    {
        try
        {
            $this->load->model('user/user_model');
            $post = json_decode($this->input->raw_input_stream, TRUE);

            if (empty($post['user_id']) || empty($this->user_model->get_by(['id' => $post['user_id']])))
            {
                throw new Exception('新增失敗，查無此投資人ID');
            }

            if (empty($post['weight']) || $post['weight'] < 0 || $post['weight'] > 10)
            {
                throw new Exception('新增失敗，權重必須為0-10');
            }

            $this->ntu_model->insert([
                'user_id' => $post['user_id'],
                'amount' => $post['amount'],
                'status' => 0,
                'weight' => $post['weight'],
                'updated_admin_id' => $this->login_info->id,
                'data_source' => 1,
                'type' => isset($post['type']) && $post['type'] ? 0 : 1
            ]);

            echo json_encode([
                'result' => 'SUCCESS'
            ]);
        }
        catch (Exception $e)
        {
            echo json_encode([
                'result' => 'ERROR',
                'msg' => $e->getMessage()
            ]);
        }
    }
}

?>

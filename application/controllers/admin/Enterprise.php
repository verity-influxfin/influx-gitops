<?php defined('BASEPATH') or exit('No direct script access allowed');

class Enterprise extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function credit_sheet($target_id)
    {
        $this->load->library('target_lib');
        $this->load->library('credit_lib');
        $this->load->model('user/user_model');

        $target = $this->target_model->get($target_id);
        if (empty($target) || $target->product_id != 1002)
        {
            echo 'no target.';
            exit;
        }

        $user_id = $target->user_id;
        $user = $this->user_model->get($user_id);

        // TODO 畫面上輸入分數的地方被 disable 沒辦法加分，
        // 另外就是評分表的項目都是有所依據，人為的加分是否要出現在這？
        $points = 0;

        $this->load->library('utility/admin/creditapprovalextra', [], 'approvalextra');
        $this->approvalextra->setSkipInsertion(true);
        $this->approvalextra->setExtraPoints($points);

        $credit_data = $this->credit_lib->approve_credit(
            $user_id,
            $target->product_id,
            $target->sub_product_id,
            $this->approvalextra,
            false,
            false,
            false,
            $target->instalment,
            $target
        );

        switch ($credit_data['type_code'])
        {
        case '1':
            $page_data['type'] = '製造業';
            break;
        case '2':
            $page_data['type'] = '買賣業';
            break;
        case '3':
            $page_data['type'] = '服務業';
            break;
        default:
            $page_data['type'] = '沒有 businessTypeCode = ' . $credit_data['type_code'] . ' 的類別';
            break;
        }

        $page_data['name'] = $user->name;
        $page_data['total'] = $credit_data['points'];
        $page_data['details'] = json_decode($credit_data['score_list'], TRUE);

        $this->load->view('admin/_header');
        $this->load->view('admin/enterprise_credit_sheet', $page_data);
        $this->load->view('admin/_footer');
    }
}

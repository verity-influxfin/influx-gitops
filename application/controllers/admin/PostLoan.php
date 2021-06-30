<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class PostLoan extends MY_Admin_Controller {

	protected $edit_method = array('legal_doc');

	public function __construct() {
		parent::__construct();
		$this->load->model('user/virtual_account_model');
		$this->load->model('transaction/frozen_amount_model');
		$this->load->model('transaction/withdraw_model');
		$this->load->library('passbook_lib');

	}

	public function index(){
		$page_data 	= array('type'=>'list');
		$input 		= $this->input->get(NULL, TRUE);
		$where		= [];
		$list		= [];

		if(isset($input['virtual_account']) && $input['virtual_account']!='' ) {
			$where['virtual_account like'] = '%'.$input['virtual_account'].'%';
		}

		if(isset($input['user_id']) && $input['user_id']!='' ) {
			$where['user_id'] = $input['user_id'];
		}

		if(!empty($where)){
			$list = $this->virtual_account_model->order_by('user_id','ASC')->get_many_by($where);
		}

		$page_data['list'] 				= $list;
		$page_data['status_list'] 		= $this->virtual_account_model->status_list;
		$page_data['investor_list'] 	= $this->virtual_account_model->investor_list;
		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/passbook_list',$page_data);
		$this->load->view('admin/_footer');
	}

	public function legal_doc()
	{
		$page_data = ['type' => 'list'];
		$dateOfToday = get_entering_date();
		$input = $this->input->get(NULL, TRUE);
		$post = $this->input->post(NULL, TRUE);
		$where = ['status' => '1', 'limit_date <=' => $dateOfToday];
		$target_where = [];
		$list = [];

		if (empty($post)) {
			if (isset($input['tsearch']) && $input['tsearch'] != '') {
				$tsearch = $input['tsearch'];
				if (preg_match("/^[\x{4e00}-\x{9fa5}]+$/u", $tsearch)) {
					$name = $this->user_model->get_many_by(array(
						'name like ' => '%' . $tsearch . '%',
						'status' => 1
					));
					if ($name) {
						foreach ($name as $k => $v) {
							$where['user_id'][] = $v->id;
						}
					}
				} else {
					if (preg_match_all('/[A-Za-z]/', $tsearch) == 1) {
						$id_number = $this->user_model->get_many_by(array(
							'id_number  like' => '%' . $tsearch . '%',
							'status' => 1
						));
						if ($id_number) {
							foreach ($id_number as $k => $v) {
								$where['user_id'][] = $v->id;
							}
						}
					} elseif (preg_match_all('/\D/', $tsearch) == 0) {
						$where['user_id'] = $tsearch;
					} else {
						$where['target_no like'] = '%' . $tsearch . '%';
					}
				}
			}

			if (isset($input['inquiry'])) {
				$start = isset($input['sdate']) && $input['sdate'] != '' ? $input['sdate'] : false;
				$end = isset($input['edate']) && $input['edate'] != '' ? $input['edate']+30 : ($start !== false ? $start+30: 0);

				if($end) {
					$target_where['delay_days > '] = $start;
					$target_where['delay_days <= '] = $end;
				}
				$result = $this->transaction_model->getDelayTargetInfoList($where, $target_where);

				foreach ($result as $v) {
					if (!isset($list[$v->target_id])) {
						$list[$v->target_id] = $v;
					}
					if (!isset($list[$v->target_id]->investor_list))
						$list[$v->target_id]->investor_list = [];
					$list[$v->target_id]->investor_list[] = $v->investor_userid;
				}
			}

			$product_list = $this->config->item('product_list');
			$sub_product_list = $this->config->item('sub_product_list');
			$status_list = $this->target_model->status_list;
			$delay_list = $this->target_model->delay_list;

			$page_data['product_list'] = $product_list;
			$page_data['sub_product_list'] = $sub_product_list;
			$page_data['list'] = $list;
			$page_data['status_list'] = $status_list;
			$page_data['delay_list'] = $delay_list;
			$page_data['name_list'] = $this->admin_model->get_name_list();

			$this->load->view('admin/_header');
			$this->load->view('admin/_title', $this->menu);
			$this->load->view('admin/legal_doc', $page_data);
			$this->load->view('admin/_footer');

		}else{

		}
	}
}

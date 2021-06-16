<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class AdminDashboard extends MY_Admin_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('user/user_contact_model');
		$this->load->model('loan/transfer_model');
		$this->load->model('user/user_bankaccount_model');
		$this->load->model('transaction/withdraw_model');
	}

	public function index()
	{
		$get = $this->input->get(NULL, TRUE);
		$data 			= array();
		$transfer_list = false;
		$bank_account_user_ids = [];
		$type = isset($get['type']) ? $get['type'] : '';
		$target_count 	= array(
			"evaluation"					 => 0,
			"approve"						 => 0,
			"bidding"						 => 0,
			"success"						 => 0,
			"delay"							 => 0,
			"prepayment"					 => 0,
			"transfer_bidding"			 	 => 0,
			"transfer_success"			 	 => 0,
			"withdraw"						 => 0,
			"waiting_approve_order_transfer" => 0,
			"manual_handling" => 0,
		);
		$contact_list 	= $this->user_contact_model->order_by("created_at","desc")->limit(5)->get_many_by(array("status" => 0));

		$chart_date = array();
		$chart_list = array();
		for($i=0;$i<30;$i++){
			$chart_date[] = date("Y-m-d",strtotime("-".$i."days"));
		}
		sort($chart_date);
		$sdatetime = current($chart_date).' 00:00:00';
		$edatetime = end($chart_date).' 23:59:59';


		foreach($chart_date as $key => $date){
			$chart_list[$date] = array("register"=>0,"loan"=>0);
		}

		if($type == 'judicial'){
			$targetsParm = [
				'product_id >=' => PRODUCT_FOR_JUDICIAL,
				"status" => [
					TARGET_WAITING_VERIFY,
					TARGET_BANK_VERIFY,
					TARGET_BANK_GUARANTEE,
					TARGET_BANK_LOAN,
					TARGET_BANK_REPAYMENTING,
					TARGET_BANK_FAIL
				]
			];
			$registerParm = [
				"company_status" => 1,
				"created_at <=" =>strtotime($edatetime),
				"created_at >="	=>strtotime($sdatetime),
			];
			$targetschangeParm = [
				"status"		=> [TARGET_BANK_VERIFY],
				"created_at <=" => strtotime($edatetime),
				"created_at >="	=> strtotime($sdatetime),
			];
		}
		else{
			$transfer_list 	= $this->transfer_model->get_many_by(array("status" => array(0,1)));
			$targetsParm = [
				'product_id <' => PRODUCT_FOR_JUDICIAL,
				"status" => [
					TARGET_WAITING_APPROVE,
					TARGET_WAITING_VERIFY,
					TARGET_WAITING_BIDDING,
					TARGET_WAITING_LOAN,
					TARGET_REPAYMENTING,
					TARGET_ORDER_WAITING_SHIP,
					TARGET_ORDER_WAITING_VERIFY_TRANSFER
				]
			];
			$registerParm = [
				"status"		=>1,
				"created_at <=" =>strtotime($edatetime),
				"created_at >="	=>strtotime($sdatetime),
			];
			$targetschangeParm = [
				"status"		=> TARGET_WAITING_BIDDING,
				"created_at <=" => strtotime($edatetime),
				"created_at >="	=> strtotime($sdatetime),
			];
		}

		if($transfer_list){
			foreach($transfer_list as $key => $value){
				if($value->status==0){
					$target_count["transfer_bidding"] += 1;
				}
				if($value->status==1){
					$target_count["transfer_success"] += 1;
				}
			}
		}

		$target_list 	= $this->target_model->get_many_by($targetsParm);
		if($target_list){
			foreach($target_list as $targekey => $targevalue){
				$target_user_ids[] = $targevalue->user_id;
			}
			$bank_account 	= $this->user_bankaccount_model->get_many_by(array(
				"user_id"	=> $target_user_ids,
				"investor"	=> 0,
				"status"	=> 1,
				"verify"	=> 1,
			));
			foreach($bank_account as $bankkey => $bankvalue){
				$bank_account_user_ids[] = $bankvalue->user_id;
			}
			foreach($target_list as $key => $value){
				if($value->status==0 && $value->sub_status==TARGET_SUBSTATUS_SECOND_INSTANCE){
					$target_count["evaluation"] += 1;
				}
				if($value->delay==1 && $value->status==TARGET_REPAYMENTING
					|| $value->delay == 1 && $value->status == TARGET_BANK_REPAYMENTING) {
					$target_count["delay"] += 1;
				}
				if (in_array($value->user_id, $bank_account_user_ids) || in_array($value->product_id, $this->config->item('externalCooperation'))) {
					if ($value->status == TARGET_WAITING_VERIFY && $value->sub_status != TARGET_SUBSTATUS_WAITING_TRANSFER_INTERNAL
						|| $value->status == TARGET_ORDER_WAITING_SHIP
						&& ($value->sub_status == TARGET_SUBSTATUS_NORNAL
							|| $value->sub_status == TARGET_SUBSTATUS_SHIPING
							|| $value->sub_status == TARGET_SUBSTATUS_SECOND_INSTANCE)) {
						$target_count["approve"] += 1;
					}

					if (in_array($value->status, [
						TARGET_WAITING_BIDDING,
						TARGET_BANK_VERIFY,
						TARGET_BANK_GUARANTEE,
						TARGET_BANK_LOAN
					])) {
						$target_count["bidding"] += 1;
					}

					if ($value->status == TARGET_WAITING_LOAN) {
						$target_count["success"] += 1;
					}

					if ($value->sub_status == TARGET_SUBSTATUS_PREPAYMENT
						&& $value->status == TARGET_REPAYMENTING) {
						$target_count["prepayment"] += 1;
					}
					if ($value->status == TARGET_ORDER_WAITING_VERIFY_TRANSFER) {
						$target_count["waiting_approve_order_transfer"] += 1;
					}

					if ($value->status == TARGET_WAITING_VERIFY
						&& $value->sub_status == TARGET_SUBSTATUS_WAITING_TRANSFER_INTERNAL
						|| $value->status == TARGET_BANK_FAIL
						&& $value->sub_status == TARGET_SUBSTATUS_WAITING_TRANSFER_INTERNAL) {
						$target_count["manual_handling"]++;
					}
				}
			}
		}

		$user_list	= $this->user_model->get_many_by($registerParm);
		foreach($user_list as $k => $v){
			$chart_list[date("Y-m-d",$v->created_at)]['register']++;
		}

		$this->load->model('log/Log_targetschange_model');
		$target_list	= $this->Log_targetschange_model->get_many_by($targetschangeParm);

		foreach($target_list as $k => $v){
			$chart_list[date("Y-m-d",$v->created_at)]['loan']++;
		}

		$list = $this->withdraw_model->get_many_by(array(
			"status" 		=> array(0,2),
			"frozen_id >" 	=> 0
		));
		if(!empty($list)){
			$target_count["withdraw"] = count($list);
		}

		// 取得 0: 待付款 1: 待結標(款項已移至待交易) 2: 待放款(已結標) 的所有資料
		$this->load->model('loan/investment_model');
		$result = $this->investment_model->get_bidding_investment(["status" => [0,1,2]]);

		$data["chart_list"] 	= $chart_list;
		$data["target_count"] 	= $target_count;
		$data["bidding_count"] = isset($result) ? count($result) : 0;
		$data["contact_list"] 	= $contact_list;
		$data["type"] = $type;
		if($type == 'judicial'){
			$this->load->view('admin/judicialdashboard',$data);
		}else{
			$this->load->view('admin/_header');
			$this->load->view('admin/_footer');
			$this->load->view('admin/_title',$this->menu);
			$this->load->view('admin/index',$data);
		}
	}

	public function personal(){
		$role_name 	= $this->role_model->get_name_list();
		$page_data 	= array("type"=>"edit","role_name"=>$role_name);
		$post 		= $this->input->post(NULL, TRUE);
		$get 		= $this->input->get(NULL, TRUE);
		$id 		= $this->login_info->id;
		if(empty($post)){
			$admin_info = $this->admin_model->get_by('id', $id);
			if($admin_info){
				$url 				= BORROW_URL.'?promote_code='.$admin_info->my_promote_code;
				$admin_info->qrcode	= get_qrcode($url);
				$page_data['data'] 	= $admin_info;
				$this->load->view('admin/_header');
				$this->load->view('admin/_title',$this->menu);
				$this->load->view('admin/personal',$page_data);
				$this->load->view('admin/_footer');
			}else{
				alert("ERROR , id isn't exist",admin_url('AdminDashboard'));
			}
		}else{
			if(!empty($post['name'])){
				$fields = ['name', 'phone', 'birthday', 'password'];
				foreach ($fields as $field) {
					if (isset($post[$field])) {
						$data[$field] = $post[$field];
					}
				}
				if(isset($data['password']) && empty($data['password'])){
					unset($data['password']);
				}

				$rs = $this->admin_model->update($id,$data);
				if($rs===true){
					alert("更新成功",admin_url('AdminDashboard'));
				}else{
					alert("更新失敗，請洽工程師",admin_url('AdminDashboard'));
				}
			}else{
				alert("ERROR , 姓名不得空白",admin_url('AdminDashboard'));
			}
		}
	}
}
?>

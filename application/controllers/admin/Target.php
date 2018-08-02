<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Target extends MY_Admin_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('loan/investment_model');
		$this->load->model('user/user_meta_model');
		$this->load->model('user/user_bankaccount_model');
		$this->load->model('user/virtual_account_model');
		$this->load->model('platform/certification_model');
		$this->load->model('loan/product_model');
		$this->load->model('loan/credit_model');
		$this->load->library('target_lib');
		$this->load->library('financial_lib');
 	}
	
	public function index(){
		$page_data 					= array("type"=>"list");
		$input 						= $this->input->get(NULL, TRUE);
		$where						= array();
		$page_data["product_name"]	= $this->product_model->get_name_list();
		$fields 					= ['status','target_no','user_id','delay'];
		
		foreach ($fields as $field) {
			if (isset($input[$field])&&$input[$field]!="") {
				$where[$field] = $input[$field];
			}
		}
		$list 						= $this->target_model->get_many_by($where);
		if($list){
			foreach($list as $key => $value){
				if($value->status==2){
					$bank_account 		= $this->user_bankaccount_model->get_by(array(
						"user_id"	=> $value->user_id,
						"investor"	=> 0,
						"status"	=> 1,
						"verify"	=> 1,
					));
					$list[$key]->bank_account_verify = $bank_account?1:0;
				}
			}
		}
		$page_data['instalment_list']	= $this->config->item('instalment');
		$page_data['repayment_type']	= $this->config->item('repayment_type');
		$page_data['list'] 				= $list;
		$page_data['status_list'] 		= $this->target_model->status_list;
		$page_data['delay_list'] 		= $this->target_model->delay_list;
		$page_data['name_list'] 		= $this->admin_model->get_name_list();


		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/targets_list',$page_data);
		$this->load->view('admin/_footer');
	}

	public function edit(){
		$page_data 	= array("type"=>"edit");
		$post 		= $this->input->post(NULL, TRUE);
		$get 		= $this->input->get(NULL, TRUE);
		if(empty($post)){
			$id = isset($get["id"])?intval($get["id"]):0;
			if($id){
				$info = $this->target_model->get($id);
				if($info){
					$amortization_table 				= array();
					$investments 						= array();
					$investments 						= array();
					$investments_amortization_table 	= array();
					$investments_amortization_schedule 	= array();
					if($info->status==5 || $info->status==10){
						$amortization_table = $this->target_lib->get_amortization_table($info);
						$investments = $this->investment_model->get_many_by(array("target_id"=>$info->id,"status"=>array(3,10)));
						if($investments){
							foreach($investments as $key =>$value){
								$investments[$key]->user_info 		= $this->user_model->get($value->user_id);
								$investments[$key]->virtual_account = $this->virtual_account_model->get_by(array(
									"user_id"	=> $value->user_id,
									"investor"	=> 1,
									"status"	=> 1,
								));
								$investments_amortization_table[$value->id] = $this->target_lib->get_investment_amortization_table($info,$value);
							}
						}
					}else if($info->status==4){
						$investments = $this->investment_model->get_many_by(array("target_id"=>$info->id,"status"=>2));
						if($investments){
							foreach($investments as $key =>$value){
								$investments[$key]->user_info 		= $this->user_model->get($value->user_id);
								$investments[$key]->virtual_account = $this->virtual_account_model->get_by(array(
									"user_id"	=> $value->user_id,
									"investor"	=> 1,
									"status"	=> 1,
								));
								$investments_amortization_schedule[$value->id] = $this->financial_lib->get_amortization_schedule(
									$value->loan_amount,
									$info->instalment,
									$info->interest_rate,
									date("Y-m-d"),
									$info->repayment
								);
							}
						}
					}

					$user_id 			= $info->user_id;
					$bank_account 		= $this->user_bankaccount_model->get_many_by(array(
						"user_id"	=> $user_id,
						"investor"	=> 0,
						"status"	=> 1,
						"verify"	=> 1,
					));
					$virtual_account 	= $this->virtual_account_model->get_by(array(
						"user_id"	=> $user_id,
						"investor"	=> 0,
						"status"	=> 1,
					));
					$bank_account_verify 	= $bank_account?1:0;
					$credit_list			= $this->credit_model->get_many_by(array("user_id"=>$user_id));
					$user_info 				= $this->user_model->get($user_id);
					$page_data['data'] 					= $info;
					$page_data['user_info'] 			= $user_info;
					$page_data['amortization_table'] 	= $amortization_table;
					$page_data['investments'] 			= $investments;
					$page_data['investments_amortization_table'] 	= $investments_amortization_table;
					$page_data['investments_amortization_schedule'] = $investments_amortization_schedule;
					$page_data['credit_list'] 			= $credit_list;
					$page_data['product_list'] 			= $this->product_model->get_name_list();
					$page_data['bank_account_verify'] 	= $bank_account_verify;
					$page_data['virtual_account'] 		= $virtual_account;
					$page_data['instalment_list']		= $this->config->item('instalment');
					$page_data['repayment_type']		= $this->config->item('repayment_type');
					$page_data['status_list'] 			= $this->target_model->status_list;
					$page_data['loan_list'] 			= $this->target_model->loan_list;
					
					$this->load->view('admin/_header');
					$this->load->view('admin/_title',$this->menu);
					$this->load->view('admin/targets_edit',$page_data);
					$this->load->view('admin/_footer');
				}else{
					alert("ERROR , id isn't exist",admin_url('target/index'));
				}
			}else{
				alert("ERROR , id isn't exist",admin_url('target/index'));
			}
		}else{
			if(!empty($post['id'])){
				$fields = ['status'];
				foreach ($fields as $field) {
					if (isset($post[$field])) {
						$data[$field] = $post[$field];
					}
				}
				$rs = $this->product_model->update($post['id'],$data);
				if($rs===true){
					alert("更新成功",admin_url('target/index'));
				}else{
					alert("更新失敗，請洽工程師",admin_url('target/index'));
				}
			}else{
				alert("ERROR , id isn't exist",admin_url('target/index'));
			}
		}
	}

	function verify_success(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get["id"])?intval($get["id"]):0;
		if($id){
			$info = $this->target_model->get($id);
			if($info && $info->status==2){
				$this->target_lib->target_verify_success($info);
				echo "更新成功";die();
			}else{
				echo "查無此ID";die();
			}
		}else{
			echo "查無此ID";die();
		}
	}
	
	function verify_failed(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get["id"])?intval($get["id"]):0;
		if($id){
			$info = $this->target_model->get($id);
			if($info && $info->status==2){
				$this->target_lib->target_verify_failed($info);
				echo "更新成功";die();
			}else{
				echo "查無此ID";die();
			}
		}else{
			echo "查無此ID";die();
		}
	}
	
	public function waiting_verify(){
		$page_data 					= array("type"=>"list");
		$input 						= $this->input->get(NULL, TRUE);
		$where						= array("status"=>2);
		$page_data["product_name"]	= $this->product_model->get_name_list();
		$fields 					= ['target_no','user_id','delay'];
		
		foreach ($fields as $field) {
			if (isset($input[$field])&&$input[$field]!="") {
				$where[$field] = $input[$field];
			}
		}
		$waiting_list 				= array();
		$list 						= $this->target_model->get_many_by($where);
		if($list){
			foreach($list as $key => $value){
				if($value->status==2){
					$bank_account 	= $this->user_bankaccount_model->get_by(array(
						"user_id"	=> $value->user_id,
						"investor"	=> 0,
						"status"	=> 1,
						"verify"	=> 1,
					));
					if($bank_account){
						$waiting_list[] = $value;
					}
				}
			}
		}
		$page_data['instalment_list']	= $this->config->item('instalment');
		$page_data['repayment_type']	= $this->config->item('repayment_type');
		$page_data['list'] 				= $waiting_list;
		$page_data['status_list'] 		= $this->target_model->status_list;
		$page_data['delay_list'] 		= $this->target_model->delay_list;
		$page_data['name_list'] 		= $this->admin_model->get_name_list();


		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/waiting_verify_target',$page_data);
		$this->load->view('admin/_footer');
	}
	
	public function waiting_loan(){
		$page_data 					= array("type"=>"list");
		$input 						= $this->input->get(NULL, TRUE);
		$where						= array("status"=>4);
		$page_data["product_name"]	= $this->product_model->get_name_list();
		$fields 					= ['target_no','user_id','delay'];
		
		foreach ($fields as $field) {
			if (isset($input[$field])&&$input[$field]!="") {
				$where[$field] = $input[$field];
			}
		}
		$waiting_list 				= array();
		$list 						= $this->target_model->get_many_by($where);
		if($list){
			foreach($list as $key => $value){
				if($value->status==4 && $value->sub_status==0){
					$bank_account 	= $this->user_bankaccount_model->get_by(array(
						"user_id"	=> $value->user_id,
						"investor"	=> 0,
						"status"	=> 1,
						"verify"	=> 1,
					));
					if($bank_account){
						$waiting_list[] = $value;
					}
				}
			}
		}
		$page_data['instalment_list']	= $this->config->item('instalment');
		$page_data['repayment_type']	= $this->config->item('repayment_type');
		$page_data['list'] 				= $waiting_list;
		$page_data['status_list'] 		= $this->target_model->status_list;
		$page_data['loan_list'] 		= $this->target_model->loan_list;
		$page_data['name_list'] 		= $this->admin_model->get_name_list();


		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/waiting_loan_target',$page_data);
		$this->load->view('admin/_footer');
	}
	
	function target_loan(){
		$get 		= $this->input->get(NULL, TRUE);
		$ids		= isset($get["ids"])&&$get["ids"]?explode(",",$get["ids"]):"";
		if($ids && is_array($ids)){
			$this->load->library('payment_lib');
			$rs = $this->payment_lib->loan_txt($ids,$this->login_info->id);
			if($rs && $rs !=""){
				$rs = iconv('UTF-8', 'BIG-5', $rs);
				header("Content-type: application/text");
				header("Content-Disposition: attachment; filename=loan_".date("YmdHis").".txt");
				echo $rs;
			}else{
				alert("無可放款之案件",admin_url('Target/waiting_loan'));
			}
		}else{
			alert("請選擇待放款的案件",admin_url('Target/waiting_loan'));
		}
	}
	
	function loan_success(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get["id"])?intval($get["id"]):0;
		if($id){
			$info = $this->target_model->get($id);
			if($info && $info->status==4 && $info->loan_status==3){
				$this->target_model->update($id,array("loan_status"=>1));
				$this->load->library('Transaction_lib');
				$rs = $this->transaction_lib->lending_success($id);
				if($rs){
					echo "更新成功";die();
				}else{
					$this->target_model->update($id,array("loan_status"=>3));
					echo "更新失敗";die();
				}
			}else{
				echo "查無此ID";die();
			}
		}else{
			echo "查無此ID";die();
		}
	}
	
	function loan_failed(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get["id"])?intval($get["id"]):0;
		if($id){
			$info = $this->target_model->get($id);
			if($info && $info->status==4 && $info->loan_status==3){
				$this->target_model->update($id,array("loan_status"=>2));
				echo "更新成功";die();
			}else{
				echo "查無此ID";die();
			}
		}else{
			echo "查無此ID";die();
		}
	}
	
	public function transaction_display(){
		$page_data 	= array("type"=>"edit");
		$get 		= $this->input->get(NULL, TRUE);
		$id = isset($get["id"])?intval($get["id"]):0;
		if($id){
			$info = $this->target_model->get($id);
			if($info && in_array($info->status,array(5,10))){
				$list = array();
				$this->load->model('transaction/transaction_model');
				$transaction_list = $this->transaction_model->order_by("id","asc")->get_many_by(array("target_id"=>$info->id));
				if($transaction_list){
					foreach($transaction_list as $key =>$value){
						$list[$value->investment_id][$value->instalment_no][] = $value;
					}
				}

				$page_data['info'] 					= $info;
				$page_data['list'] 					= $list;
				$page_data['transaction_source'] 	= $this->config->item('transaction_source');
				$page_data['status_list'] 			= $this->transaction_model->status_list;
				$page_data['passbook_status_list'] 	= $this->transaction_model->passbook_status_list;
				$this->load->view('admin/_header');
				$this->load->view('admin/transaction_edit',$page_data);
				$this->load->view('admin/_footer');
				
			}else{
				die("ERROR , id isn't exist");
			}
		}else{
			die("ERROR , id isn't exist");
		}
		
	}
}
?>
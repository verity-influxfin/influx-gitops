<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Passbook extends MY_Admin_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('user/virtual_account_model');
		$this->load->model('transaction/frozen_amount_model');
		$this->load->library('passbook_lib');

	}
	
	public function index(){
		$page_data 	= array("type"=>"list");
		$list 		= $this->virtual_account_model->get_all();
		if(!empty($list)){
			$page_data['list'] 				= $list;
			$page_data['status_list'] 		= $this->virtual_account_model->status_list;
			$page_data['investor_list'] 	= $this->virtual_account_model->investor_list;
		}

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/passbook_list',$page_data);
		$this->load->view('admin/_footer');
	}
	
	public function edit(){
		$get 				= $this->input->get(NULL, TRUE);
		$id 				= isset($get["id"])?$get["id"]:"";
		$virtual_account 	= $this->virtual_account_model->get($id);
		if($virtual_account){
			$list 				= $this->passbook_lib->get_passbook_list($virtual_account->virtual_account);
			$frozen_list 		= $this->frozen_amount_model->order_by("tx_datetime","ASC")->get_many_by(array("virtual_account"=>$virtual_account->virtual_account));
			$frozen_type 		= $this->frozen_amount_model->type_list;
			$page_data['list'] 					= $list;
			$page_data['frozen_list'] 			= $frozen_list;
			$page_data['frozen_status'] 		= $this->frozen_amount_model->status_list;
			$page_data['frozen_type'] 			= $this->frozen_amount_model->type_list;
			$page_data['transaction_source'] 	= $this->config->item('transaction_source');
			$this->load->view('admin/_header');
			$this->load->view('admin/_title',$this->menu);
			$this->load->view('admin/passbook_edit',$page_data);
			$this->load->view('admin/_footer');
		}else{
			alert("ERROR , id isn't exist",admin_url('passbook/index'));
		}
	}
	
	public function display(){
		$get 				=	 $this->input->get(NULL, TRUE);
		$account 			= isset($get["virtual_account"])?$get["virtual_account"]:"";
		$virtual_account 	= $this->virtual_account_model->get_by(array("virtual_account"=>$account));
		if($virtual_account || $account ==PLATFORM_VIRTUAL_ACCOUNT){
			$list 				= $this->passbook_lib->get_passbook_list($account);
			$frozen_list 		= $this->frozen_amount_model->order_by("tx_datetime","ASC")->get_many_by(array("virtual_account"=>$account));
			$frozen_type 		= $this->frozen_amount_model->type_list;
			$page_data['list'] 					= $list;
			$page_data['frozen_list'] 			= $frozen_list;
			$page_data['frozen_status'] 		= $this->frozen_amount_model->status_list;
			$page_data['frozen_type'] 			= $this->frozen_amount_model->type_list;
			$page_data['transaction_source'] 	= $this->config->item('transaction_source');
			$this->load->view('admin/_header');
			$this->load->view('admin/passbook_edit',$page_data);
			$this->load->view('admin/_footer');
		}else{
			echo "ERROR , Account isn't exist";
		}
	}
}
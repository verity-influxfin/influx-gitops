<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Sales extends MY_Admin_Controller {
	
	protected $edit_method = array();
	
	public function __construct() {
		parent::__construct();
		$this->load->model('user/user_meta_model');
		$this->load->model('admin/partner_model');
		$this->load->model('admin/partner_type_model');
 	}
	
	public function index(){
		$page_data 		= array();	
		$list			= array();
		$partner_list 	= $this->partner_model->order_by("parent_id","ASC")->get_all();
		$admins_list 	= $this->admin_model->order_by("id","ASC")->get_all();
		$partner_type 	= $this->partner_type_model->get_name_list();
		$target_list	= $this->target_model->get_many_by(array("promote_code <>"=>""));
		if(!empty($target_list)){
			foreach($target_list as $key => $value){
				$list[] = array(
					"id"			=> $value->id,
					"amount"		=> $value->amount,
					"loan_amount"	=> $value->loan_amount,
					"loan_date"		=> $value->loan_date,
					"status"		=> $value->status,
					"promote_code"	=> $value->promote_code,
					"created_date"	=> date("Y-m-d H:i:s",$value->created_at),
				);
			}
		}
		$page_data["list"] 			= $list;
		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
	//	$this->load->view('admin/sales_loan',$page_data);
		$this->load->view('admin/_footer');
	}

	public function register_report(){

	}
}
?>
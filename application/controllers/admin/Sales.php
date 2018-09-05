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
		$max_date 		= "";
		$min_date 		= "";
		$admins_qrcode 	= $this->admin_model->get_qrcode_list();
		$admins_name 	= $this->admin_model->get_name_list();
		$partner_type 	= $this->partner_type_model->get_name_list();
		$partner_list 	= $this->partner_model->get_many_by(array("status"=>1));
		$partner_list_byid = array();
		if($partner_list){
			$partner_qrcode = array();
			foreach($partner_list as $key => $value){
				$partner_qrcode[$value->my_promote_code] = $value->id;
				$partner_list_byid[$value->id] = $value;
			}
		}
		
		$target_list	= $this->target_model->get_all();
		if(!empty($target_list)){
			foreach($target_list as $key => $value){
				if(isset($partner_qrcode[$value->promote_code]) && $partner_qrcode[$value->promote_code]){
					$list["partner"][$partner_qrcode[$value->promote_code]][$value->status][] = array(
						"id"			=> $value->id,
						"amount"		=> $value->amount,
						"loan_amount"	=> $value->loan_amount,
						"loan_date"		=> $value->loan_date,
						"status"		=> $value->status,
						"promote_code"	=> $value->promote_code,
						"created_date"	=> date("Y-m-d",$value->created_at),
					);
				}
				
				if(isset($admins_qrcode[$value->promote_code]) && $admins_qrcode[$value->promote_code]){
					$list["sales"][$admins_qrcode[$value->promote_code]][$value->status][] = array(
						"id"			=> $value->id,
						"amount"		=> $value->amount,
						"loan_amount"	=> $value->loan_amount,
						"loan_date"		=> $value->loan_date,
						"status"		=> $value->status,
						"promote_code"	=> $value->promote_code,
						"created_date"	=> date("Y-m-d",$value->created_at),
					);
				}
				
				if($value->promote_code=="" || (!isset($admins_qrcode[$value->promote_code]) && !isset($partner_qrcode[$value->promote_code]))){
					$list["platform"][$value->status][] = array(
						"id"			=> $value->id,
						"amount"		=> $value->amount,
						"loan_amount"	=> $value->loan_amount,
						"loan_date"		=> $value->loan_date,
						"status"		=> $value->status,
						"promote_code"	=> $value->promote_code,
						"created_date"	=> date("Y-m-d",$value->created_at),
					);
				}
				
				if($max_date=="" || $max_date<$value->created_at)
					$max_date = $value->created_at;
				if($min_date=="" || $min_date>$value->created_at)
					$min_date = $value->created_at;
			}
		}
		$page_data["list"] 			= $list;
		$page_data["partner_list"] 	= $partner_list_byid;
		$page_data["admins_name"] 	= $admins_name;
		$page_data["partner_type"] 	= $partner_type;
		$page_data["target_status"] = $this->target_model->status_list;
		$page_data["max_date"] 		= $max_date?date("Y-m-d",$max_date):"";
		$page_data["min_date"] 		= $min_date?date("Y-m-d",$min_date):"";

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/sales_loan',$page_data);
		$this->load->view('admin/_footer');
	}

	public function register_report(){
		$page_data 		= array();	
		$list			= array();
		$max_date 		= "";
		$min_date 		= "";
		$admins_qrcode 	= $this->admin_model->get_qrcode_list();
		$admins_name 	= $this->admin_model->get_name_list();
		$partner_type 	= $this->partner_type_model->get_name_list();
		$partner_list 	= $this->partner_model->get_many_by(array("status"=>1));
		$partner_list_byid = array();
		if($partner_list){
			$partner_qrcode = array();
			foreach($partner_list as $key => $value){
				$partner_qrcode[$value->my_promote_code] = $value->id;
				$partner_list_byid[$value->id] = $value;
			}
		}
		
		$user_list		= $this->user_model->get_many_by(array("status"=>1));
		$school_list 	= $this->user_meta_model->get_many_by(array("meta_key"=>"student_status"));
		if(!empty($user_list)){
			
			if(!empty($school_list)){
				$school_status = array();
				foreach($school_list as $key => $value){
					
					$school_status[$value->user_id] = 1;
				}
			}
			
			foreach($user_list as $key => $value){
				$user_list[$key]->school 	= isset($school_status[$value->id])&&$school_status[$value->id]?1:0;
				$user_list[$key]->fb 		= $value->nickname?1:0;
			}
			
			$list = array("platform"=>array("count"=>0,"school"=>0,"fb"=>0));
			foreach($user_list as $key => $value){
				if(isset($partner_qrcode[$value->promote_code]) && $partner_qrcode[$value->promote_code]){
					$list["partner"][$partner_qrcode[$value->promote_code]]["count"] ++;
					if($value->school)
						$list["partner"][$partner_qrcode[$value->promote_code]]["school"] ++;
					if($value->fb)
						$list["partner"][$partner_qrcode[$value->promote_code]]["fb"] ++;
				}

				if(isset($admins_qrcode[$value->promote_code]) && $admins_qrcode[$value->promote_code]){
					@$list["sales"][$admins_qrcode[$value->promote_code]]["count"] ++;
					if($value->school)
						$list["sales"][$admins_qrcode[$value->promote_code]]["school"] ++;
					if($value->fb)
						$list["sales"][$admins_qrcode[$value->promote_code]]["fb"] ++;
				}
				
				if($value->promote_code=="" || (!isset($admins_qrcode[$value->promote_code]) && !isset($partner_qrcode[$value->promote_code]))){
					$list["platform"]["count"] ++;
					if($value->school)
						$list["platform"]["school"] ++;
					if($value->fb)
						$list["platform"]["fb"] ++;
				}
				
				if($max_date=="" || $max_date<$value->created_at)
					$max_date = $value->created_at;
				if($min_date=="" || $min_date>$value->created_at)
					$min_date = $value->created_at;
			}
		}
		$page_data["list"] 			= $list;
		$page_data["partner_list"] 	= $partner_list_byid;
		$page_data["admins_name"] 	= $admins_name;
		$page_data["partner_type"] 	= $partner_type;
		$page_data["max_date"] 		= $max_date?date("Y-m-d",$max_date):"";
		$page_data["min_date"] 		= $min_date?date("Y-m-d",$min_date):"";

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/sales_register',$page_data);
		$this->load->view('admin/_footer');
	}
}
?>
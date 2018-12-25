<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Risk extends MY_Admin_Controller {
	
	protected $edit_method = array("add","edit","partner_type_add");
	
	public function __construct() {
		parent::__construct();
		$this->load->model('loan/investment_model');
		$this->load->model('user/user_meta_model');
		$this->load->model('user/user_bankaccount_model');
		$this->load->model('user/user_certification_model');
		$this->load->model('user/virtual_account_model');
		$this->load->model('loan/product_model');
		$this->load->model('loan/credit_model');
		$this->load->library('target_lib');
		$this->load->library('certification_lib');
 	}
	
	public function index(){
		$page_data 					= array("type"=>"list");
		$input 						= $this->input->get(NULL, TRUE);
		$list 						= array();
		$user_list 					= array();
		$user_investor_list 		= array();
		$certification_investor_list = array();
		$user_certification_list	= $this->user_certification_model->get_many_by(array(
			"status"	=> array(0,3),
		));
		if($user_certification_list){
			foreach($user_certification_list as $key => $value){
				if($value->investor){
					$user_investor_list[$value->user_id] = $value->user_id;
				}else{
					$user_list[$value->user_id] = $value->user_id;
				}
			}
		}

		if($user_investor_list){
			ksort($user_investor_list);
			foreach($user_investor_list as $key => $value){
				$certification_investor_list[$value] = $this->certification_lib->get_last_status($value,1);
				if(isset($certification_investor_list[$value][3]['certification_id'])){
					$bank_account 	= $this->user_bankaccount_model->get_by(array(
						"user_certification_id"	=> $certification_investor_list[$value][3]['certification_id'],
					));
					$certification_investor_list[$value]["bank_account"]  = $bank_account;
				}
			}
		}
		
		if($user_list){
			$targets = $this->target_model->get_many_by(array(
				"user_id"	=> $user_list,
				"status"	=> 0
			));
			if($targets){
				foreach($targets as $key => $value){
					$list[$value->id] = $value;
				}
			}
		}
		
		$targets = $this->target_model->get_many_by(array(
			"status"	=> array(1,2)
		));
		if($targets){
			foreach($targets as $key => $value){
				$list[$value->id] = $value;
			}
		}
		
		if($list){
			ksort($list);
			foreach($list as $key => $value){
				$list[$key]->certification = $this->certification_lib->get_last_status($value->user_id,0);
				if(isset($list[$key]->certification[3]['certification_id'])){
					$bank_account 	= $this->user_bankaccount_model->get_by(array(
						"user_certification_id"	=> $list[$key]->certification[3]['certification_id'],
					));
					$list[$key]->bank_account 	 	 = $bank_account;
					$list[$key]->bank_account_verify = $bank_account->verify==1?1:0;
				}
			}
		}

		$page_data['list'] 					= $list;
		$page_data['certification_investor_list'] 	= $certification_investor_list;
		$page_data['certification'] 		= $this->config->item('certifications');
		$page_data['status_list'] 			= $this->target_model->status_list;
		$page_data['product_name']			= $this->product_model->get_name_list();
		
		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/risk_target',$page_data);
		$this->load->view('admin/_footer');
	}
	
	
	function credit(){
		$page_data 	= array('type'=>'list','list'=>array());
		$input 		= $this->input->get(NULL, TRUE);
		$list		= array();
		$where		= array();
		$fields 	= ['user_id'];

		foreach ($fields as $field) {
			if (isset($input[$field])&&$input[$field]!="") {
				$where[$field] = $input[$field];
			}
		}
		
		if(!empty($where)){
			$list = $this->credit_model->order_by('expire_time','desc')->get_many_by($where);
			if($list){
				foreach($list as $key => $value){
					$user = $this->user_model->get($value->user_id);
					$list[$key]->user_name 	= $user->name;
				}
			}
		}

		$page_data['list'] 			= $list;
		$page_data['status_list']	= $this->credit_model->status_list;
		$page_data['product_name']	= $this->product_model->get_name_list();

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/credit_list',$page_data);
		$this->load->view('admin/_footer');
	}
	
	
	public function loaned_wait_push(){
		$this->load->model('admin/debt_processing_model');
		$this->load->model('admin/debt_audit_model');
		$page_data 					= array("type"=>"list");
		$input 						= $this->input->get(NULL, TRUE);
		$where						= array("status"=>5,"delay"=>1);
		$list 						= array();
		$page_data["product_name"]	= $this->product_model->get_name_list();
		$fields = ['status','target_no','user_id','all'];
		$push_data=array();
		$result_data=array();
		if(isset($input["slist"])&&$input["slist"]!=null){
			$page_data['slist']=$input["slist"];
			$input["status"]=1;
		}
		
		if(!empty($input)){
			foreach ($fields as $field) {
				if (isset($input[$field])&&$input[$field]!="") {
					if($field!='status'){
						$where[$field] = $input[$field];
					}
				}
			}
		}
		
		$list = $this->target_model->get_many_by($where);
		if($list){
			foreach($list as $key => $value){
				$temp=$this->debt_audit_model->get_by(array("target_id"=> $value->id));
				$list[$key]->push_status = $temp?1:0;
				if(isset($input["status"])&&$input["status"]!=null){
					$list[$key]->push_status==$input["status"]?$result_data[$key]=$list[$key]:null;
				}
				else{
					$result_data[$key]=$list[$key];
				}
			}
		}
		$page_data['instalment_list']	= $this->config->item('instalment');
		$page_data['repayment_type']	= $this->config->item('repayment_type');
		$page_data['list'] 				= $result_data;
		$page_data['push_status_list'] 		= $this->debt_processing_model->push_status_list;
		$page_data['status_list'] 		= $this->target_model->status_list;
		$page_data['delay_list'] 		= $this->target_model->delay_list;
		$page_data['name_list'] 		= $this->admin_model->get_name_list();

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/risk/risk_loaned',$page_data);
		$this->load->view('admin/_footer');
	}
	
	public function push_target(){
		$page_data 	= array("type"=>"edit");
		$get 		= $this->input->get(NULL, TRUE);
		$input		= $this->input->get(NULL, TRUE);
		$target_id 	= isset($get["id"])?intval($get["id"]):0;
		$display 	= isset($get["display"])?intval($get["display"]):0;
		if($target_id){
			$info = $this->target_model->get($target_id);
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
				if(isset($input["slist"])&&$input["slist"]!=null){$page_data['slist']=$input["slist"];}
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
				


				$user_list 					= array();
				$user_investor_list 		= array();
				$certification_investor_list = array();

				$targets = $this->target_model->get_many_by(array(
					"user_id"	=> $user_id,
					"id"=>$info->id
				));
				if($targets){
					foreach($targets as $key => $value){
						$list[$value->id] = $value;
					}
				}

				if($list){
					ksort($list);
					foreach($list as $key => $value){
						$list[$key]->certification = $this->certification_lib->get_last_status($value->user_id,0);
						if(isset($list[$key]->certification[3]['certification_id'])){
							$bank_account 	= $this->user_bankaccount_model->get_by(array(
								"user_certification_id"	=> $list[$key]->certification[3]['certification_id'],
							));
							$list[$key]->bank_account 	 	 = $bank_account;
							$list[$key]->bank_account_verify = $bank_account->verify==1?1:0;
						}
					}
				}

				$page_data['list'] 					= $list;
				$page_data['certification_investor_list'] 	= $certification_investor_list;
				$page_data['certification'] 		= $this->config->item('certifications');
				$page_data['status_list'] 			= $this->target_model->status_list;
				$page_data['product_name']			= $this->product_model->get_name_list();

				
				$this->load->view('admin/_header');
				$this->load->view('admin/risk/risk_targets_edit',$page_data);
				$this->load->view('admin/_footer');

				
			}else{
				alert("ERROR , id isn't exist",admin_url('admin/risk/loaned_wait_push'));
			}
		}else{
			alert("ERROR , id isn't exist",admin_url('admin/risk/loaned_wait_push'));
		}
	}
	public function push_info(){
		$this->load->model('admin/debt_processing_model');
		$this->load->model('user/user_meta_model');
		$page_data 					= array("type"=>"list");
		$input 						= $this->input->get(NULL, TRUE);
		$target_id					= isset($input["id"])?intval($input["id"]):0;
		$list 						= array();
		$list=$this->debt_processing_model->get_many_by(array("target_id"=> $target_id));
		$user_id=$list[0]->user_id;
		$meta_data 			= array();
		$meta 				= $this->user_meta_model->get_many_by(array("user_id"=>$user_id));
		$info = $this->user_model->get($user_id);		
		
		if($list){
			foreach($list as $key => $value){
				$list[$key]->admin_name = $this->admin_model->get($value->admin_id)->name;
			}
		}
		if($meta){
			foreach($meta as $key => $value){
				$meta_data[$value->meta_key] = $value->meta_value;
			}
		} 
		if(isset($input["slist"])&&$input["slist"]!=null){$page_data['slist']=$input["slist"];}
		$page_data['list']	=$list;
		$page_data['data']	= $info;
		$page_data['meta']	= $meta_data;
		$page_data['push_by_status_list'] 		= $this->debt_processing_model->push_by_status_list;
		$page_data['result_status_list'] 		= $this->debt_processing_model->result_status_list;

		$this->load->view('admin/_header');
		$this->load->view('admin/risk/risk_push_info',$page_data);
		$this->load->view('admin/_footer');
	}
	public function push_info_add(){
		$this->load->model('admin/debt_processing_model');
		$input = $this->input->get(NULL, TRUE);
		$info = $this->target_model->get($input["id"]);	
		$rs = $this->debt_processing_model->insert(array("admin_id"=>$this->login_info->id,"user_id"=> $info->user_id,"target_id"=> $input["id"],"contact_person"=> $input["a"],"contact_phone"=> $input["b"],"result"=> $input["c"],"push_by"=> $input["d"],"remark"=> $input["e"],"start_time"=> $input["f"],"end_time"=> $input["g"]));
	}
	public function push_info_remove(){
		$this->load->model('admin/debt_processing_model');
		$input = $this->input->get(NULL, TRUE);	
		$rs = $this->debt_processing_model->delete($input["id"]);
	}	
	public function push_info_update(){
		$this->load->model('admin/debt_processing_model');
		$input = $this->input->get(NULL, TRUE);
		$rs = $this->debt_processing_model->update($input["id"],array("admin_id"=>$this->login_info->id,"contact_person"=> $input["a"],"contact_phone"=> $input["b"],"result"=> $input["c"],"push_by"=> $input["d"],"start_time"=> $input["e"],"end_time"=> $input["f"],"remark"=> $input["g"]));
	}
	public function push_audit(){
		$this->load->model('admin/debt_audit_model');
		$role_name 	= $this->role_model->get_name_list();
		$page_data 					= array("type"=>"list");
		$input 						= $this->input->get(NULL, TRUE);
		$target_id					= isset($input["id"])?intval($input["id"]):0;
		$list 						= array();
		$list=$this->debt_audit_model->get_many_by(array("target_id"=> $target_id));
		
		if($list){
			foreach($list as $key => $value){
				$temp=$this->admin_model->get($value->admin_id);
				$list[$key]->admin_name = $temp->name;
				$list[$key]->role_id = $temp->role_id;
			}
		}
		if(isset($input["slist"])&&$input["slist"]!=null){$page_data['slist']=$input["slist"];}			
		$page_data['id']	=$target_id;
		$page_data['list']	=$list;		
		$page_data['role_name']	=$role_name;
		$page_data['status_list'] 		= $this->debt_audit_model->status_list;

		$this->load->view('admin/_header');
		$this->load->view('admin/risk/risk_push_audit',$page_data);
		$this->load->view('admin/_footer');
	}
	public function push_audit_add(){
		$this->load->model('admin/debt_audit_model');
		$input = $this->input->get(NULL, TRUE);
		$info = $this->target_model->get($input["id"]);	
		$rs = $this->debt_audit_model->insert(array("admin_id"=>$this->login_info->id,"user_id"=> $info->user_id,"target_id"=> $input["id"],"remark"=> $input["a"],"product_level"=> $input["b"],"next_push"=> $input["c"],"result"=> $input["d"],"end_time"=> $input["e"]));
	}
}
?>
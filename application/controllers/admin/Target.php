<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Target extends MY_Admin_Controller {
	
	protected $edit_method = array('edit','verify_success','verify_failed','loan_success','loan_failed');
	
	public function __construct() {
		parent::__construct();
		$this->load->model('loan/investment_model');
		$this->load->model('loan/transfer_model');
		$this->load->model('user/user_meta_model');
		$this->load->model('user/user_bankaccount_model');
		$this->load->model('user/virtual_account_model');
		$this->load->model('loan/credit_model');
		$this->load->library('target_lib');
		$this->load->library('financial_lib');
 	}
	
	public function index(){
		
		$page_data 	= ['type'=>'list'];
		$input 		= $this->input->get(NULL, TRUE);
		$where		= [];
		$list		= [];
		$fields 	= ['status','target_no','user_id','delay','all'];
		foreach ($fields as $field) {
			if (isset($input[$field])&&$input[$field]!='') {
				if($field=='target_no'){
					$where[$field.' like'] = '%'.$input[$field].'%';
				}else{
					$where[$field] = $input[$field];
				}
			}
		}
		if(!empty($where)){
			$list 						= $this->target_model->get_many_by($where);
			if($list){
				foreach($list as $key => $value){
					if($value->status==2){
						$bank_account 		= $this->user_bankaccount_model->get_by(array(
							'user_id'	=> $value->user_id,
							'investor'	=> 0,
							'status'	=> 1,
							'verify'	=> 1,
						));
						$list[$key]->bank_account_verify = $bank_account?1:0;
					}
				}
			}
		}
		
		$page_data['product_list']		= $this->config->item('product_list');
		$page_data['instalment_list']	= $this->config->item('instalment');
		$page_data['repayment_type']	= $this->config->item('repayment_type');
		$page_data['list'] 				= $list;
		$page_data['status_list'] 		= $this->target_model->status_list;
		$page_data['delay_list'] 		= $this->target_model->delay_list;
		$page_data['name_list'] 		= $this->admin_model->get_name_list();


		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/target/targets_list',$page_data);
		$this->load->view('admin/_footer');
	}

	public function edit(){
		$page_data 	= array('type'=>'edit');
		$get 		= $this->input->get(NULL, TRUE);
		$id 		= isset($get['id'])?intval($get['id']):0;
		$display 	= isset($get['display'])?intval($get['display']):0;
		if($id){
			$info = $this->target_model->get($id);
			if($info){
				$amortization_table 				= [];
				$investments 						= [];
				$investments 						= [];
				$investments_amortization_table 	= [];
				$investments_amortization_schedule 	= [];
				if($info->status==5 || $info->status==10){
					$amortization_table = $this->target_lib->get_amortization_table($info);
					$investments = $this->investment_model->get_many_by(array('target_id'=>$info->id,'status'=>array(3,10)));
					if($investments){
						foreach($investments as $key =>$value){
							$investments[$key]->user_info 		= $this->user_model->get($value->user_id);
							$investments[$key]->virtual_account = $this->virtual_account_model->get_by(array(
								'user_id'	=> $value->user_id,
								'investor'	=> 1,
								'status'	=> 1,
							));
							$investments_amortization_table[$value->id] = $this->target_lib->get_investment_amortization_table($info,$value);
						}
					}
				}else if($info->status==4){
					$investments = $this->investment_model->get_many_by(array('target_id'=>$info->id,'status'=>2));
					if($investments){
						foreach($investments as $key =>$value){
							$investments[$key]->user_info 		= $this->user_model->get($value->user_id);
							$investments[$key]->virtual_account = $this->virtual_account_model->get_by(array(
								'user_id'	=> $value->user_id,
								'investor'	=> 1,
								'status'	=> 1,
							));
							$investments_amortization_schedule[$value->id] = $this->financial_lib->get_amortization_schedule(
								$value->loan_amount,
								$info->instalment,
								$info->interest_rate,
								date('Y-m-d'),
								$info->repayment
							);
						}
					}
				}

				$user_id 			= $info->user_id;
				$bank_account 		= $this->user_bankaccount_model->get_many_by(array(
					'user_id'	=> $user_id,
					'investor'	=> 0,
					'status'	=> 1,
					'verify'	=> 1,
				));
				$virtual_account 	= $this->virtual_account_model->get_by(array(
					'user_id'	=> $user_id,
					'investor'	=> 0,
					'status'	=> 1,
				));

				$bank_account_verify 				= $bank_account?1:0;
				$credit_list						= $this->credit_model->get_many_by(array('user_id'=>$user_id));
				$user_info 							= $this->user_model->get($user_id);
				$page_data['data'] 					= $info;
				$page_data['user_info'] 			= $user_info;
				$page_data['amortization_table'] 	= $amortization_table;
				$page_data['investments'] 			= $investments;
				$page_data['investments_amortization_table'] 	= $investments_amortization_table;
				$page_data['investments_amortization_schedule'] = $investments_amortization_schedule;
				$page_data['credit_list'] 			= $credit_list;
				$page_data['product_list']			= $this->config->item('product_list');
				$page_data['bank_account_verify'] 	= $bank_account_verify;
				$page_data['virtual_account'] 		= $virtual_account;
				$page_data['instalment_list']		= $this->config->item('instalment');
				$page_data['repayment_type']		= $this->config->item('repayment_type');
				$page_data['status_list'] 			= $this->target_model->status_list;
				$page_data['loan_list'] 			= $this->target_model->loan_list;

				if(isset($get['risk'])&&$get['risk']!=null){
					$this->load->library('certification_lib');
					if(isset($get['slist'])&&$get['slist']!=null){$page_data['slist']=$get['slist'];}

					$user_list 						= [];
					$user_investor_list 			= [];
					$certification_investor_list 	= [];

					$targets = $this->target_model->get_many_by(array(
						'user_id'	=> $user_id,
						'id'=>$info->id
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
									'user_certification_id'	=> $list[$key]->certification[3]['certification_id'],
								));
								$list[$key]->bank_account 	 	 = $bank_account;
								$list[$key]->bank_account_verify = $bank_account->verify==1?1:0;
							}
						}
					}

					$page_data['list'] 					= $list;
					$page_data['certification_investor_list'] 	= $certification_investor_list;
					$page_data['certification'] 		= $this->config->item('certifications');
					$this->load->view('admin/risk/risk_targets_edit',$page_data);
				}
				else{
					if(!$display){
						$this->load->view('admin/_title',$this->menu);
					}
					$this->load->view('admin/target/targets_edit',$page_data);
				}
				
				$this->load->view('admin/_header');
				$this->load->view('admin/_footer');
				
			}else{
				alert('ERROR , id is not exist',admin_url('target/index'));
			}
		}else{
			alert('ERROR , id is not exist',admin_url('target/index'));
		}
	}

	function verify_success(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		if($id){
			$info = $this->target_model->get($id);
			if($info && $info->status==2){
				if($info->sub_status==8){
					$this->load->library('subloan_lib');
					$this->subloan_lib->subloan_verify_success($info,$this->login_info->id);
				}else{
					$this->target_lib->target_verify_success($info,$this->login_info->id);
				}
				echo '更新成功';die();
			}else{
				echo '查無此ID';die();
			}
		}else{
			echo '查無此ID';die();
		}
	}
	
	function verify_failed(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		$remark = isset($get['remark'])?$get['remark']:'';
		if($id){
			$info = $this->target_model->get($id);
			if($info && in_array($info->status,array(0,1,2))){
				if($info->sub_status==8){
					$this->load->library('subloan_lib');
					$this->subloan_lib->subloan_verify_failed($info,$this->login_info->id,$remark);
				}else{
					$this->target_lib->target_verify_failed($info,$this->login_info->id,$remark);
				}
				echo '更新成功';die();
			}else{
				echo '查無此ID';die();
			}
		}else{
			echo '查無此ID';die();
		}
	}
	
	public function waiting_verify(){
		$page_data 					= array('type'=>'list');
		$input 						= $this->input->get(NULL, TRUE);
		$where						= array('status'=>2);
		$fields 					= ['target_no','user_id','delay'];
		
		foreach ($fields as $field) {
			if (isset($input[$field])&&$input[$field]!='') {
				$where[$field] = $input[$field];
			}
		}
		$waiting_list 				= array();
		$list 						= $this->target_model->get_many_by($where);
		if($list){
			foreach($list as $key => $value){
				if($value->status==2){
					$bank_account 	= $this->user_bankaccount_model->get_by(array(
						'user_id'	=> $value->user_id,
						'investor'	=> 0,
						'status'	=> 1,
						'verify'	=> 1,
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
		$page_data['product_list']		= $this->config->item('product_list');
		$page_data['status_list'] 		= $this->target_model->status_list;
		$page_data['delay_list'] 		= $this->target_model->delay_list;
		$page_data['name_list'] 		= $this->admin_model->get_name_list();


		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/target/waiting_verify_target',$page_data);
		$this->load->view('admin/_footer');
	}
	
	public function waiting_loan(){
		$page_data 					= array('type'=>'list');
		$input 						= $this->input->get(NULL, TRUE);
		$where						= array('status'=>4);
		$fields 					= ['target_no','user_id','delay'];
		
		foreach ($fields as $field) {
			if (isset($input[$field])&&$input[$field]!='') {
				$where[$field] = $input[$field];
			}
		}
		$waiting_list 				= array();
		$list 						= $this->target_model->get_many_by($where);
		if($list){
			foreach($list as $key => $value){
				if($value->status==4){
					$bank_account 	= $this->user_bankaccount_model->get_by(array(
						'user_id'	=> $value->user_id,
						'investor'	=> 0,
						'status'	=> 1,
						'verify'	=> 1,
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
		$page_data['product_list']		= $this->config->item('product_list');
		$page_data['status_list'] 		= $this->target_model->status_list;
		$page_data['loan_list'] 		= $this->target_model->loan_list;
		$page_data['name_list'] 		= $this->admin_model->get_name_list();


		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/target/waiting_loan_target',$page_data);
		$this->load->view('admin/_footer');
	}
	
	function target_loan(){
		$get 		= $this->input->get(NULL, TRUE);
		$ids		= isset($get['ids'])&&$get['ids']?explode(',',$get['ids']):'';
		if($ids && is_array($ids)){
			$this->load->library('payment_lib');
			$rs = $this->payment_lib->loan_txt($ids,$this->login_info->id);
			if($rs && $rs !=''){
				$rs = iconv('UTF-8', 'BIG-5//IGNORE', $rs);
				header('Content-type: application/text');
				header('Content-Disposition: attachment; filename=loan_'.date('YmdHis').'.txt');
				echo $rs;
			}else{
				alert('無可放款之案件',admin_url('Target/waiting_loan'));
			}
		}else{
			alert('請選擇待放款的案件',admin_url('Target/waiting_loan'));
		}
	}
	
	function subloan_success(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		if($id){
			$info = $this->target_model->get($id);
			if($info && $info->status==4 && $info->loan_status==2 && $info->sub_status==8){
				$this->load->library('Transaction_lib');
				$rs = $this->transaction_lib->subloan_success($id,$this->login_info->id);
				if($rs){
					echo '更新成功';die();
				}else{
					echo '更新失敗';die();
				}
			}else{
				echo '查無此ID';die();
			}
		}else{
			echo '查無此ID';die();
		}
	}
	
	function loan_return(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		if($id){
			$info = $this->target_model->get($id);
			if($info && $info->status==4){
				$rs = $this->target_lib->cancel_success_target($info,$this->login_info->id);
				if($rs){
					echo '更新成功';die();
				}else{
					echo '更新失敗';die();
				}
			}else{
				echo '查無此ID';die();
			}
		}else{
			echo '查無此ID';die();
		}
	}
	
	function loan_success(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		if($id){
			$info = $this->target_model->get($id);
			if($info && $info->status==4 && $info->loan_status==3 && $info->sub_status==0){
				$this->load->library('Transaction_lib');
				$rs = $this->transaction_lib->lending_success($id,$this->login_info->id);
				if($rs){
					echo '更新成功';die();
				}else{
					echo '更新失敗';die();
				}
			}else{
				echo '查無此ID';die();
			}
		}else{
			echo '查無此ID';die();
		}
	}
	
	function loan_failed(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		if($id){
			$info = $this->target_model->get($id);
			if($info && $info->status==4 && $info->loan_status==3 && $info->sub_status==0){
				$this->load->library('Transaction_lib');
				$rs = $this->transaction_lib->lending_failed($id,$this->login_info->id);
				echo '更新成功';die();
			}else{
				echo '查無此ID';die();
			}
		}else{
			echo '查無此ID';die();
		}
	}
	
	public function transaction_display(){
		$page_data 	= array('type'=>'edit');
		$get 		= $this->input->get(NULL, TRUE);
		$id = isset($get['id'])?intval($get['id']):0;
		if($id){
			$info = $this->target_model->get($id);
			if($info && in_array($info->status,array(5,10))){
				$list = array();
				$this->load->model('transaction/transaction_model');
				$transaction_list = $this->transaction_model->order_by('id','asc')->get_many_by(array('target_id'=>$info->id));
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
				die('ERROR , id is not exist');
			}
		}else{
			die('ERROR , id is not exist');
		}
		
	}
	
	public function repayment(){
		$page_data 					= ['type'=>'list'];
		$input 						= $this->input->get(NULL, TRUE);
		$list 						= $this->target_model->get_many_by(['status'=>5]);
		$school_list 				= [];
		$user_list 					= [];
		$amortization_table 		= [];
		if($list){
			foreach($list as $key => $value){
				$user_list[] = $value->user_id;
				$limit_date  = $value->created_at + (TARGET_APPROVE_LIMIT*86400);
				$credit		 = $this->credit_model->order_by('created_at','desc')->get_by([
					'product_id' 	=> $value->product_id,
					'user_id' 		=> $value->user_id,
					'created_at <=' => $limit_date,
				]);
				if($credit){
					$list[$key]->credit = $credit;
				}
				$amortization_table = $this->target_lib->get_amortization_table($value);
				$list[$key]->amortization_table = [
					'total_payment_m'		=> $amortization_table['list'][1]['total_payment'],
					'total_payment'			=> $amortization_table['total_payment'],
					'remaining_principal'	=> $amortization_table['remaining_principal'],
				];
			}
			
			$this->load->model('user/user_meta_model');
			$users_school 	= $this->user_meta_model->get_many_by([
				'meta_key' 	=> ['school_name','school_department'],
				'user_id' 	=> $user_list,
			]);
			if($users_school){
				foreach($users_school as $key => $value){
					$school_list[$value->user_id][$value->meta_key] = $value->meta_value;
				}
			}
		}
		$page_data['instalment_list']	= $this->config->item('instalment');
		$page_data['repayment_type']	= $this->config->item('repayment_type');
		$page_data['product_name']		= $this->product_model->get_name_list();
		$page_data['list'] 				= $list;
		$page_data['delay_list'] 		= $this->target_model->delay_list;
		$page_data['status_list'] 		= $this->target_model->status_list;
		$page_data['school_list'] 		= $school_list;
		$page_data['product_list']		= $this->config->item('product_list');

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/target/targets_repayment',$page_data);
		$this->load->view('admin/_footer');
	}
	
	public function target_export(){
		$get 	= $this->input->get(NULL, TRUE);
		$ids 	= isset($get['ids'])&&$get['ids']?explode(',',$get['ids']):'';
		$status = isset($get['status'])&&$get['status']?$get['status']:5;
		if($ids && is_array($ids)){
			$where = ['id'=>$ids];
		}else{
			$where = ['status'=>$status];
		}
		
		$product_list				= $this->config->item('product_list');
		$list 						= $this->target_model->get_many_by($where);
		$school_list 				= [];
		$user_list 					= [];
		$amortization_table 		= [];
		if($list){
			foreach($list as $key => $value){
				$user_list[] = $value->user_id;
				$limit_date  = $value->created_at + (TARGET_APPROVE_LIMIT*86400);
				$credit		 = $this->credit_model->order_by('created_at','desc')->get_by([
					'product_id' 	=> $value->product_id,
					'user_id' 		=> $value->user_id,
					'created_at <=' => $limit_date,
				]);
				if($credit){
					$list[$key]->credit = $credit;
				}
				
				if(in_array($value->status,[5,10])){
					$amortization_table = $this->target_lib->get_amortization_table($value);
					$list[$key]->amortization_table = [
						'total_payment_m'		=> $amortization_table['list'][1]['total_payment'],
						'total_payment'			=> $amortization_table['total_payment'],
						'remaining_principal'	=> $amortization_table['remaining_principal'],
					];
				}else{
					$amortization_table = $this->financial_lib->get_amortization_schedule($value->loan_amount,$value->instalment,$value->interest_rate,$value->loan_date,$value->repayment);
					$list[$key]->amortization_table = [
						'total_payment_m'		=> $amortization_table['total_payment'],
						'total_payment'			=> $amortization_table['total']['total_payment'],
						'remaining_principal'	=> $value->loan_amount,
					];
				}
				
			}
			
			$this->load->model('user/user_meta_model');
			$users_school 	= $this->user_meta_model->get_many_by(array(
				'meta_key' 	=> ['school_name','school_department'],
				'user_id' 	=> $user_list,
			));
			if($users_school){
				foreach($users_school as $key => $value){
					$school_list[$value->user_id][$value->meta_key] = $value->meta_value;
				}
			}
		}
		$instalment_list	= $this->config->item('instalment');
		$repayment_type		= $this->config->item('repayment_type');
		$delay_list 		= $this->target_model->delay_list;
		$status_list 		= $this->target_model->status_list;
		$sub_list 			= $this->target_model->sub_list;

		header('Content-type:application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename=targets_'.date('Ymd').'.xls');
		$html = '<table><thead><tr><th>案號</th><th>產品</th><th>會員 ID</th><th>信用等級</th><th>學校名稱</th><th>學校科系</th>
                <th>申請金額</th><th>核准金額</th><th>剩餘本金</th><th>年化利率</th><th>期數</th>
                <th>還款方式</th><th>每月回款</th><th>回款本息總額</th><th>放款日期</th>
                <th>逾期狀況</th><th>逾期天數</th><th>狀態</th><th>申請日期</th><th>信評核准日期</th></tr></thead><tbody>';

		if(isset($list) && !empty($list)){
			
			foreach($list as $key => $value){
				$sub_status = $value->status==10&&$value->sub_status!= 0 ?'('.$sub_list[$value->sub_status].')':'';
				$credit = isset($value->credit)?date("Y-m-d H:i:s",$value->credit->created_at):'';
				$html .= '<tr>';
				$html .= '<td>'.$value->target_no.'</td>';
				$html .= '<td>'.$product_list[$value->product_id]['name'].'</td>';
				$html .= '<td>'.$value->user_id.'</td>';
				$html .= '<td>'.$value->credit_level.'</td>';
				$html .= '<td>'.$school_list[$value->user_id]['school_name'].'</td>';
				$html .= '<td>'.$school_list[$value->user_id]['school_department'].'</td>';
				$html .= '<td>'.$value->amount.'</td>';
				$html .= '<td>'.$value->loan_amount.'</td>';
				$html .= '<td>'.$value->amortization_table['remaining_principal'].'</td>';
				$html .= '<td>'.$value->interest_rate.'</td>';
				$html .= '<td>'.$value->instalment.'</td>';
				$html .= '<td>'.$repayment_type[$value->repayment].'</td>';
				$html .= '<td>'.$value->amortization_table['total_payment_m'].'</td>';
				$html .= '<td>'.$value->amortization_table['total_payment'].'</td>';
				$html .= '<td>'.$value->loan_date.'</td>';
				$html .= '<td>'.$delay_list[$value->delay].'</td>';
				$html .= '<td>'.$value->delay_days.'</td>';
				$html .= '<td>'.$status_list[$value->status].$sub_status.'</td>';
				$html .= '<td>'.date('Y-m-d H:i:s',$value->created_at).'</td>';
				$html .= '<td>'.$credit.'</td>';
				$html .= '</tr>';
			}
		}
        $html .= '</tbody></table>';
		echo $html;
	}
	
	public function amortization_export(){
		$get = $this->input->get(NULL, TRUE);
		$ids = isset($get['ids'])&&$get['ids']?explode(',',$get['ids']):'';
		if($ids && is_array($ids)){
			$where = ['id'=>$ids,'status'=>5];
		}else{
			$where = ['status'=>5];
		}
		
		$data 		= [];
		$first_data = [];
		$list 	= $this->target_model->get_many_by($where);
		if($list){
			foreach($list as $key => $value){
				$amortization_table = $this->target_lib->get_amortization_table($value);
				if($amortization_table){
					@$first_data[$amortization_table['date']] -= $amortization_table['amount'];
					foreach($amortization_table['list'] as $instalment => $value){
						@$data[$value['repayment_date']]['total_payment'] 	+= $value['total_payment'];
						@$data[$value['repayment_date']]['repayment'] 		+= $value['repayment'];
						@$data[$value['repayment_date']]['interest'] 		+= $value['interest'];
						@$data[$value['repayment_date']]['principal'] 		+= $value['principal'];
					}
					
				}
			}
		}
		
		header('Content-type:application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename=amortization_'.date('Ymd').'.xls');
		$html = '<table><thead><tr><th>日期</th><th>合計</th><th>本金</th><th>利息</th><th>已回款</th></tr></thead><tbody>';
		if(isset($first_data) && !empty($first_data)){
			foreach($first_data as $key => $value){
				$html .= '<tr>';
				$html .= '<td>'.$key.'</td>';
				$html .= '<td>'.$value.'</td>';
				$html .= '<td></td><td></td><td></td>';
				$html .= '</tr>';
			}
		}
		if(isset($data) && !empty($data)){
			foreach($data as $key => $value){
				$html .= '<tr>';
				$html .= '<td>'.$key.'</td>';
				$html .= '<td>'.$value['total_payment'].'</td>';
				$html .= '<td>'.$value['principal'].'</td>';
				$html .= '<td>'.$value['interest'].'</td>';
				$html .= '<td>'.$value['repayment'].'</td>';
				$html .= '</tr>';
			}
		}
        $html .= '</tbody></table>';
		echo $html;
	}
	
	public function prepayment(){
		$page_data 	= array('type'=>'list');
		$input 		= $this->input->get(NULL, TRUE);
		$where		= array(
			'status'	=> array(5,10),
			'sub_status'=> array(3,4),
		);
		$list		= array();
		$fields 	= ['target_no','user_id'];
		
		foreach ($fields as $field) {
			if (isset($input[$field])&&$input[$field]!='') {
				if($field=='target_no'){
					$where[$field.' like'] = '%'.$input[$field].'%';
				}else{
					$where[$field] = $input[$field];
				}
			}
		}
		if(!empty($where)){
			$this->load->model('loan/prepayment_model');
			$list 	= $this->target_model->order_by('sub_status','ASC')->get_many_by($where);
			if($list){
				foreach($list as $key => $value){
					$list[$key]->prepayment = $this->prepayment_model->order_by('settlement_date','DESC')->get_by(array('target_id'=>$value->id));
				}
			}
		}
		$page_data['instalment_list']	= $this->config->item('instalment');
		$page_data['repayment_type']	= $this->config->item('repayment_type');
		$page_data['list'] 				= $list;
		$page_data['status_list'] 		= $this->target_model->status_list;
		$page_data['sub_list'] 			= $this->target_model->sub_list;
		$page_data['name_list'] 		= $this->admin_model->get_name_list();


		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/target/prepayment_list',$page_data);
		$this->load->view('admin/_footer');
	}

	public function waiting_bidding(){
		$page_data 					= array('type'=>'list');
		$input 						= $this->input->get(NULL, TRUE);
		$list 						= $this->target_model->get_many_by(['status'=>3]);
		$school_list 				= [];
		$user_list 					= [];
		$amortization_table 		= [];
		if($list){
			$this->load->model('log/Log_targetschange_model');
			foreach($list as $key => $value){
				$user_list[] 	= $value->user_id;
				$target_change	= $this->Log_targetschange_model->get_by(array(
					'target_id'		=> $value->id,
					'status'		=> 3,
				));
				if($target_change){
					$list[$key]->bidding_date = $target_change->created_at;
				}
			}
			
			$this->load->model('user/user_meta_model');
			$users_school 	= $this->user_meta_model->get_many_by([
				'meta_key' 	=> ['school_name','school_department'],
				'user_id' 	=> $user_list,
			]);
			if($users_school){
				foreach($users_school as $key => $value){
					$school_list[$value->user_id][$value->meta_key] = $value->meta_value;
				}
			}
		}
		$page_data['instalment_list']	= $this->config->item('instalment');
		$page_data['repayment_type']	= $this->config->item('repayment_type');
		$page_data['product_list']		= $this->config->item('product_list');
		$page_data['list'] 				= $list;
		$page_data['status_list'] 		= $this->target_model->status_list;
		$page_data['school_list'] 		= $school_list;

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/target/waiting_bidding_target',$page_data);
		$this->load->view('admin/_footer');
	}
	
	public function finished(){
		$page_data 					= ['type'=>'list'];
		$input 						= $this->input->get(NULL, TRUE);
		$list 						= $this->target_model->get_many_by(['status'=>10]);
		$school_list 				= [];
		$user_list 					= [];
		$amortization_table 		= [];
		if($list){
			foreach($list as $key => $value){
				$user_list[] = $value->user_id;
				$limit_date  = $value->created_at + (TARGET_APPROVE_LIMIT*86400);
				$credit		 = $this->credit_model->order_by('created_at','desc')->get_by([
					'product_id' 	=> $value->product_id,
					'user_id' 		=> $value->user_id,
					'created_at <=' => $limit_date,
				]);
				if($credit){
					$list[$key]->credit = $credit;
				}
				$amortization_table = $this->target_lib->get_amortization_table($value);
				$list[$key]->amortization_table = [
					'total_payment_m'		=> $amortization_table['list'][1]['total_payment'],
					'total_payment'			=> $amortization_table['total_payment'],
					'remaining_principal'	=> $amortization_table['remaining_principal'],
				];
			}
			
			$this->load->model('user/user_meta_model');
			$users_school 	= $this->user_meta_model->get_many_by([
				'meta_key' 	=> ['school_name','school_department'],
				'user_id' 	=> $user_list,
			]);
			if($users_school){
				foreach($users_school as $key => $value){
					$school_list[$value->user_id][$value->meta_key] = $value->meta_value;
				}
			}
		}
		$page_data['instalment_list']	= $this->config->item('instalment');
		$page_data['repayment_type']	= $this->config->item('repayment_type');
		$page_data['product_name']		= $this->product_model->get_name_list();
		$page_data['list'] 				= $list;
		$page_data['delay_list'] 		= $this->target_model->delay_list;
		$page_data['status_list'] 		= $this->target_model->status_list;
		$page_data['sub_list'] 			= $this->target_model->sub_list;
		$page_data['school_list'] 		= $school_list;

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/target/targets_finished',$page_data);
		$this->load->view('admin/_footer');
	}
	
	public function waiting_signing(){
		$page_data 					= ['type'=>'list'];
		$input 						= $this->input->get(NULL, TRUE);
		$list 						= $this->target_model->get_many_by(['status'=>1]);
		$school_list 				= [];
		$user_list 					= [];
		$amortization_table 		= [];
		if($list){
			foreach($list as $key => $value){
				$user_list[] = $value->user_id;
				$limit_date  = $value->created_at + (TARGET_APPROVE_LIMIT*86400);
				$credit		 = $this->credit_model->order_by('created_at','desc')->get_by([
					'product_id' 	=> $value->product_id,
					'user_id' 		=> $value->user_id,
					'created_at <=' => $limit_date,
				]);
				if($credit){
					$list[$key]->credit = $credit;
				}
				$amortization_table = $this->financial_lib->get_amortization_schedule($value->loan_amount,$value->instalment,$value->interest_rate,$value->loan_date,$value->repayment);
				$list[$key]->amortization_table = [
					'total_payment_m'		=> $amortization_table['total_payment'],
					'total_payment'			=> $amortization_table['total']['total_payment'],
					'remaining_principal'	=> $value->loan_amount,
				];
			}
			
			$this->load->model('user/user_meta_model');
			$users_school 	= $this->user_meta_model->get_many_by([
				'meta_key' 	=> ['school_name','school_department'],
				'user_id' 	=> $user_list,
			]);
			if($users_school){
				foreach($users_school as $key => $value){
					$school_list[$value->user_id][$value->meta_key] = $value->meta_value;
				}
			}
		}
		$page_data['instalment_list']	= $this->config->item('instalment');
		$page_data['repayment_type']	= $this->config->item('repayment_type');
		$page_data['product_name']		= $this->product_model->get_name_list();
		$page_data['list'] 				= $list;
		$page_data['delay_list'] 		= $this->target_model->delay_list;
		$page_data['status_list'] 		= $this->target_model->status_list;
		$page_data['sub_list'] 			= $this->target_model->sub_list;
		$page_data['school_list'] 		= $school_list;

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/target/waiting_signing',$page_data);
		$this->load->view('admin/_footer');
	}
}
?>
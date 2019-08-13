<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Passbook extends MY_Admin_Controller {
	
	protected $edit_method = array('withdraw_loan','loan_success','loan_failed','unknown_refund','withdraw_by_admin','withdraw_deny');
	
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
	
	public function edit(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?$get['id']:'';
		if($id==PLATFORM_VIRTUAL_ACCOUNT){
			$virtual_account = new stdClass();
			$virtual_account->id = PLATFORM_VIRTUAL_ACCOUNT;
			$virtual_account->virtual_account = PLATFORM_VIRTUAL_ACCOUNT;
			$virtual_account->user_id 		= 0;
			$virtual_account->investor 		= 0;
		}else{
			$virtual_account 	= $this->virtual_account_model->get($id);
		}
		
		if($virtual_account){
			$list 				= $this->passbook_lib->get_passbook_list($virtual_account->virtual_account);
			$frozen_list 		= $this->frozen_amount_model->order_by('tx_datetime','ASC')->get_many_by(array('virtual_account'=>$virtual_account->virtual_account));
			$frozen_type 		= $this->frozen_amount_model->type_list;
			$page_data['list'] 					= $list;
			$page_data['virtual_account'] 		= $virtual_account;
			$page_data['user_info'] 			= $this->user_model->get($virtual_account->user_id);
			$page_data['frozen_list'] 			= $frozen_list;
			$page_data['frozen_status'] 		= $this->frozen_amount_model->status_list;
			$page_data['frozen_type'] 			= $this->frozen_amount_model->type_list;
			$page_data['transaction_source'] 	= $this->config->item('transaction_source');
			$page_data['investor_list'] 		= $this->virtual_account_model->investor_list;
			$this->load->view('admin/_header');
			$this->load->view('admin/_title',$this->menu);
			$this->load->view('admin/passbook_edit',$page_data);
			$this->load->view('admin/_footer');
		}else{
			alert('ERROR , id is not exist',admin_url('passbook/index'));
		}
	}
	
	public function display(){
		$get 				=	 $this->input->get(NULL, TRUE);
		$account 			= isset($get['virtual_account'])?$get['virtual_account']:'';
		$virtual_account 	= $this->virtual_account_model->get_by(array('virtual_account'=>$account));
		if($account==PLATFORM_VIRTUAL_ACCOUNT){
			$virtual_account = new stdClass();
			$virtual_account->id = PLATFORM_VIRTUAL_ACCOUNT;
			$virtual_account->virtual_account = PLATFORM_VIRTUAL_ACCOUNT;
			$virtual_account->user_id 		= 0;
			$virtual_account->investor 		= 0;
		}
		if($virtual_account){
			$list 				= $this->passbook_lib->get_passbook_list($account);
			$frozen_list 		= $this->frozen_amount_model->order_by('tx_datetime','ASC')->get_many_by(array('virtual_account'=>$account));
			$frozen_type 		= $this->frozen_amount_model->type_list;
			$page_data['list'] 					= $list;
			$page_data['virtual_account'] 		= $virtual_account;
			$page_data['user_info'] 			= $this->user_model->get($virtual_account->user_id);
			$page_data['frozen_list'] 			= $frozen_list;
			$page_data['frozen_status'] 		= $this->frozen_amount_model->status_list;
			$page_data['frozen_type'] 			= $this->frozen_amount_model->type_list;
			$page_data['transaction_source'] 	= $this->config->item('transaction_source');
			$page_data['investor_list'] 		= $this->virtual_account_model->investor_list;
			$this->load->view('admin/_header');
			$this->load->view('admin/passbook_edit',$page_data);
			$this->load->view('admin/_footer');
		}else{
			echo 'ERROR , Account is not exist';
		}
	}
	
	public function withdraw_list(){
		$page_data 	= array('type'=>'list');
		$list 		= $this->withdraw_model->get_all();
		if(!empty($list)){
			$page_data['list'] 				= $list;
			$page_data['status_list'] 		= $this->withdraw_model->status_list;
			$page_data['investor_list'] 	= $this->withdraw_model->investor_list;
		}

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/withdraw_list',$page_data);
		$this->load->view('admin/_footer');
	}
	
	public function withdraw_waiting(){
		$page_data 	= array('type'=>'list');
		$where		= array(
			'status' 		=> array(0,2),
			'frozen_id >' 	=> 0
		);
		$list = $this->withdraw_model->get_many_by($where);
		if(!empty($list)){
			$page_data['list'] 				= $list;
			$page_data['status_list'] 		= $this->withdraw_model->status_list;
			$page_data['investor_list'] 	= $this->withdraw_model->investor_list;
			$page_data['sys_check_list'] 	= $this->withdraw_model->sys_check_list;
		}

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/withdraw_waiting',$page_data);
		$this->load->view('admin/_footer');
	}
	
	function withdraw_loan(){
		$get 		= $this->input->get(NULL, TRUE);
		$ids		= isset($get['ids'])&&$get['ids']?explode(',',$get['ids']):'';
		if($ids && is_array($ids)){
			$this->load->library('payment_lib');
			$rs = $this->payment_lib->withdraw_txt($ids,$this->login_info->id);
			if($rs && $rs !=''){
				$rs = iconv('UTF-8', 'BIG-5//IGNORE', $rs);
				header('Content-type: application/text');
				header('Content-Disposition: attachment; filename=withdraw_'.date('YmdHis').'.txt');
				echo $rs;
			}else{
				alert('無可放款之案件',admin_url('passbook/withdraw_waiting'));
			}
		}else{
			alert('請選擇待放款的案件',admin_url('passbook/withdraw_waiting'));
		}
	}
	
	function unknown_funds(){
		$this->load->model('transaction/payment_model');
		$page_data 	= array('type'=>'list');
		$where		= array(
			'status' 		=> array(4,5),
		);
		$list = $this->payment_model->get_many_by($where);
		if(!empty($list)){
			$page_data['list'] 				= $list;
			$page_data['status_list'] 		= $this->payment_model->status_list;
		}

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/unknown_funds',$page_data);
		$this->load->view('admin/_footer');
	}
	
	function unknown_refund(){
		$get 		= $this->input->get(NULL, TRUE);
		$ids		= isset($get['ids'])&&$get['ids']?explode(',',$get['ids']):'';
		if($ids && is_array($ids)){
			$this->load->library('payment_lib');
			$rs = $this->payment_lib->unknown_txt($ids,$this->login_info->id);
			if($rs && $rs !=''){
				$rs = iconv('UTF-8', 'BIG-5//IGNORE', $rs);
				header('Content-type: application/text');
				header('Content-Disposition: attachment; filename=unknown_'.date('YmdHis').'.txt');
				echo $rs;
			}else{
				alert('無退款的選擇',admin_url('Passbook/unknown_funds'));
			}
		}else{
			alert('請選擇退款',admin_url('Passbook/unknown_funds'));
		}
	}
	
	function loan_success(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		if($id){
			$info = $this->withdraw_model->get($id);
			if($info && $info->status==2 && $info->frozen_id>0){
				$this->load->library('Transaction_lib');
				$rs = $this->transaction_lib->withdraw_success($id);
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
			$info = $this->withdraw_model->get($id);
			if($info && $info->status==2 && $info->frozen_id>0){
				$this->withdraw_model->update($id,array('status'=>0));
				echo '更新成功';die();
			}else{
				echo '查無此ID';die();
			}
		}else{
			echo '查無此ID';die();
		}
	}
	
	function withdraw_by_admin(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		$amount = isset($get['amount'])?intval($get['amount']):0;
		if($amount > 31){
			if( $id==PLATFORM_VIRTUAL_ACCOUNT ){
				$this->load->library('Transaction_lib');
				$rs = $this->transaction_lib->platform_withdraw($amount);
				if($rs){
					echo '更新成功，請至提領放款';die();
				}else{
					echo '更新失敗';die();
				}
			}else{
				$virtual_account 	= $this->virtual_account_model->get($id);
				if($virtual_account){
					$this->load->library('Transaction_lib');
					$rs = $this->transaction_lib->withdraw($virtual_account->user_id,$amount,$virtual_account->investor);
					if($rs){
						echo '更新成功，請至提領放款';die();
					}else{
						echo '更新失敗';die();
					}
				}else{
					echo '查無此ID';die();
				}
			}
		}else{
			echo '金額過小';die();
		}
	}

    function withdraw_deny(){
        $get 	= $this->input->get(NULL, TRUE);
        $id 	= isset($get['id'])?intval($get['id']):0;
        if($id){
            $info = $this->withdraw_model->get($id);
            if($info && $info->status==0 && $info->frozen_id>0){
                $this->withdraw_model->update($id,array('status'=>3));
                $this->frozen_amount_model->update($info->frozen_id,array('status'=>0));
                echo '提領駁回成功，已取消凍結';die();
            }else{
                echo '查無此ID';die();
            }
        }else{
            echo '查無此ID';die();
        }
    }


}
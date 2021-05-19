<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Risk extends MY_Admin_Controller {

	protected $edit_method = array('add','edit','partner_type_add');

	public function __construct() {
		parent::__construct();
		$this->load->model('loan/investment_model');
		$this->load->model('user/user_meta_model');
		$this->load->model('user/user_bankaccount_model');
		$this->load->model('user/user_certification_model');
		$this->load->model('user/virtual_account_model');
		$this->load->model('loan/credit_model');
		$this->load->model('loan/target_model');
		$this->load->library('target_lib');
		$this->load->library('certification_lib');
 	}

	public function index(){
		$page_data 					= array('type'=>'list');
		$input 						= $this->input->get(NULL, TRUE);
		$list 						= array();
		$plist 						= array();
		$cer_list 					= array();
		$certification_investor_list = array();
		$cer = $this->config->item('certifications');
		$product_list = $this->config->item('product_list');

		$target_status = [
			TARGET_WAITING_APPROVE,
			TARGET_WAITING_SIGNING, TARGET_WAITING_VERIFY,
			TARGET_ORDER_WAITING_SIGNING,
			TARGET_ORDER_WAITING_VERIFY,
			30,
			31,
			32,
			TARGET_BANK_VERIFY,
			TARGET_BANK_GUARANTEE,
			TARGET_BANK_LOAN
		];

		//分類身分供風控頁面使用
		$character = [
			0 => 'owner',
			1 => 'owner',
			2 => 'guarantor',
			3 => 'spouse',
			4 => 'guarantor',
			5 => 'guarantor',
		];

        $cer_parm['investor'] = isset($input['investor']) ? $input['investor'] : 0;
		if($cer_parm['investor'] == 1){
			$cer_parm['certification_id'] = [1, 3, 5, 6];
			$cer_parm['status'] = [0, 3];
			$user_certification_list	= $this->user_certification_model->get_many_by($cer_parm);
			if($user_certification_list){
				foreach($user_certification_list as $key => $value){
					$user_investor_list[$value->user_id] = $value->user_id;
				}
			}
			$investor_cer = [CERTIFICATION_IDCARD, CERTIFICATION_DEBITCARD, CERTIFICATION_EMERGENCY, CERTIFICATION_EMAIL];
			foreach($investor_cer as $ckey => $cvalue){
				$useCer[$cvalue] = $cer[$cvalue];
			}
			ksort($user_investor_list);
			foreach($user_investor_list as $key => $value){
				$certification_investor_list[$value] = $this->certification_lib->get_last_status($value,INVESTOR);
				if(isset($certification_investor_list[$value][3]['certification_id'])){
					$bank_account 	= $this->user_bankaccount_model->get_by(array(
						'user_certification_id'	=> $certification_investor_list[$value][3]['certification_id'],
					));
					$certification_investor_list[$value]['bank_account']  = $bank_account;
				}
			}
		}else{
			$useCer = [];
			$target_parm = [
				'status'	=> $target_status
			];
			isset($input['company'])
				? $input['company'] == 1
				?$target_parm['product_id >='] = PRODUCT_FOR_JUDICIAL
				:$target_parm['product_id <'] = PRODUCT_FOR_JUDICIAL
				: '';

			isset($input['target_id']) ? $target_parm['id'] = $input['target_id'] : '';
			$targets = $this->target_model->order_by('user_id','desc')->get_many_by($target_parm);
			if($targets){
				foreach($targets as $key => $value) $list[$value->id] = $value;
				ksort($list);
				foreach($list as $key => $value){
					$product = $product_list[$value->product_id];
					$sub_product_id = $value->sub_product_id;
					if($this->is_sub_product($product,$sub_product_id)){
						$product = $this->trans_sub_product($product,$sub_product_id);
					}

					foreach($product['certifications'] as $ckey => $cvalue){
						if(!isset($useCer[$value->product_id][$cvalue])){
							$useCer[$value->product_id][$cvalue] = $cer[$cvalue];
						}
					}

					//產品是否需要保證人
					if($product['checkOwner']){
						$associates_list = $this->target_lib->get_associates_user_data($value->id, 'all', [0, 1], false);
						foreach($associates_list as $associate){
							$setCharacter = $character[$associate->character];
							$status = 1;
							if($associate->user_id != null){
								if($cer_status = $this->certification_lib->get_last_status($associate->user_id, BORROWER)){
									foreach($product['certifications'] as $cer_statusKey => $cer_statusValue) {
										if($cer_statusValue < PRODUCT_FOR_JUDICIAL && isset($cer_status[$cer_statusValue])){
											$cer_status[$cer_statusValue]['user_status'] != 1 ? $status = 0 : '';
										}
									}
								}
							}
							else{
								$status = 0;
							}
							$list[$key]->associate[$setCharacter] = $status;
						}
					}

					!isset($cer_list[$value->user_id]) ? $cer_list[$value->user_id] = $this->certification_lib->get_last_status($value->user_id, BORROWER, 1, $value) : '';
					$list[$key]->certification = $cer_list[$value->user_id];
					if(isset($list[$key]->certification[3]['certification_id'])){
						$bank_account 	= $this->user_bankaccount_model->get_by(array(
							'user_certification_id'	=> $list[$key]->certification[3]['certification_id'],
						));
						$list[$key]->bank_account 	 	 = $bank_account;
						$list[$key]->bank_account_verify = $bank_account->verify==1?1:0;
					}
					elseif($value->product_id >= PRODUCT_FOR_JUDICIAL){
						$list[$key]->bank_account 	 	 = '';
						$list[$key]->bank_account_verify = 1;
					}
					// 所有認證最後更新時間
					$lastUpdate = max(array_column($cer_list[$value->user_id], 'updated_at'));
					$list[$key]->lastUpdate = ! empty($lastUpdate) ? $lastUpdate : $list[$key]->updated_at;
					$plist[$value->product_id][$key] = $list[$key];
				}
			}
		}

		$page_data['list'] 					= $plist;
		$page_data['certification_investor_list'] 	= $certification_investor_list;
		$page_data['certification'] 		= $useCer;
		$page_data['status_list'] 			= $this->target_model->status_list;
		$page_data['product_list']			= $product_list;
		$page_data['sub_product_list'] = $this->config->item('sub_product_list');
		$page_data['externalCooperation'] = $this->config->item('externalCooperation');
		$page_data['input'] = $input;


		$this->load->view('admin/_header');
		if(!isset($input['target_id'])){
			$this->load->view('admin/_title',$this->menu);
		}
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
			if (isset($input[$field])&&$input[$field]!='') {
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
		$page_data['product_list']	= $this->config->item('product_list');
        $page_data['sub_product_list'] = $this->config->item('sub_product_list');

        $this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/credit_list',$page_data);
		$this->load->view('admin/_footer');
	}

	public function loaned_wait_push(){
		$this->load->model('admin/debt_processing_model');
		$this->load->model('admin/debt_audit_model');
		$page_data 		= array('type'=>'list');
		$input 			= $this->input->get(NULL, TRUE);
		$where			= ['status'=>5,'delay'=>1];
		$list 			= [];
		$fields 		= ['status','target_no','user_id','all'];
		$push_data		= [];
		$result_data	= [];
		if(isset($input['slist'])&&$input['slist']!=null){
			$page_data['slist']=$input['slist'];
			$input['status']=1;
		}

		if(!empty($input)){
			foreach ($fields as $field) {
				if (isset($input[$field])&&$input[$field]!='') {
					if($field!='status'){
						$where[$field] = $input[$field];
					}
				}
			}
		}

		$list = $this->target_model->get_many_by($where);
		if($list){
			foreach($list as $key => $value){
				$temp=$this->debt_audit_model->get_by(array('target_id'=> $value->id));
				$list[$key]->push_status = $temp?1:0;
				if(isset($input['status'])&&$input['status']!=null){
					$list[$key]->push_status==$input['status']?$result_data[$key]=$list[$key]:null;
				}
				else{
					$result_data[$key]=$list[$key];
				}
			}
		}
		$page_data['instalment_list']	= $this->config->item('instalment');
		$page_data['repayment_type']	= $this->config->item('repayment_type');
		$page_data['list'] 				= $result_data;
		$page_data['push_status_list'] 	= $this->debt_processing_model->push_status_list;
		$page_data['status_list'] 		= $this->target_model->status_list;
		$page_data['delay_list'] 		= $this->target_model->delay_list;
		$page_data['name_list'] 		= $this->admin_model->get_name_list();

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/risk/risk_loaned',$page_data);
		$this->load->view('admin/_footer');
	}

	public function push_info(){
		$this->load->model('admin/debt_processing_model');
		$this->load->model('user/user_meta_model');
		$page_data 	= array('type'=>'list');
		$input 		= $this->input->get(NULL, TRUE);
		$target_id	= isset($input['id'])?intval($input['id']):0;
		$list		= $this->debt_processing_model->order_by('created_at','desc')->get_many_by(array('target_id'=> $target_id));
		$user_id	= $this->target_model->get($target_id)->user_id;
		$meta_data 	= [];
		$meta 		= $this->user_meta_model->get_many_by(array('user_id'=>$user_id));
		$info 		= $this->user_model->get($user_id);

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

		if(isset($input['slist']) && $input['slist']!=null){
			$page_data['slist']=$input['slist'];
		}

		$page_data['id']	= $target_id;
		$page_data['list']	= $list;
		$page_data['data']	= $info;
		$page_data['meta']	= $meta_data;
		$page_data['push_by_status_list'] 	= $this->debt_processing_model->push_by_status_list;
		$page_data['result_status_list'] 	= $this->debt_processing_model->result_status_list;

		$this->load->view('admin/_header');
		$this->load->view('admin/risk/risk_push_info',$page_data);
		$this->load->view('admin/_footer');
	}

	public function push_info_add(){
		$this->load->model('admin/debt_processing_model');
		$input 	= $this->input->post(NULL, TRUE);
		$get 	= $this->input->get(NULL, TRUE);
		$info 	= $this->target_model->get($get['id']);
		$rs 	= $this->debt_processing_model->insert(array(
			'admin_id'		=> $this->login_info->id,
			'user_id'		=> $info->user_id,
			'target_id'		=> $get['id'],
			'contact_person'=> $input['contact_person'],
			'contact_phone'	=> $input['contact_phone'],
			'result'		=> $input['result'],
			'push_by'		=> $input['push_by'],
			'remark'		=> $input['remark'],
			'start_time'	=> $input['start_time'],
			'end_time'		=> $input['end_time']
		));
	}

	public function push_info_remove(){
		$this->load->model('admin/debt_processing_model');
		$input 	= $this->input->post(NULL, TRUE);
		$rs 	= $this->debt_processing_model->delete($input['id']);
	}

	public function push_info_update(){
		$this->load->model('admin/debt_processing_model');
		$input 	= $this->input->post(NULL, TRUE);
		$rs 	= $this->debt_processing_model->update($input['id'],array(
			'admin_id'		=> $this->login_info->id,
			'contact_person'=> $input['contact_person'],
			'contact_phone'	=> $input['contact_phone'],
			'result'		=> $input['result'],
			'push_by'		=> $input['push_by'],
			'remark'		=> $input['remark'],
			'start_time'	=> $input['start_time'],
			'end_time'		=> $input['end_time']
		));
	}

	public function push_audit(){
		$this->load->model('admin/debt_audit_model');
		$role_name 	= $this->role_model->get_name_list();
		$page_data 	= array('type'=>'list');
		$input 		= $this->input->get(NULL, TRUE);
		$target_id	= isset($input['id'])?intval($input['id']):0;
		$list 		= array();
		$list		= $this->debt_audit_model->order_by('created_at','desc')->get_many_by(array('target_id'=> $target_id));

		if($list){
			foreach($list as $key => $value){
				$temp=$this->admin_model->get($value->admin_id);
				$list[$key]->admin_name = $temp->name;
				$list[$key]->role_id = $temp->role_id;
			}
		}

		if(isset($input['slist']) && $input['slist']!=null){
			$page_data['slist']=$input['slist'];
		}
		$page_data['id']	=$target_id;
		$page_data['list']	=$list;
		$page_data['role_name']	=$role_name;
		$page_data['product_level'] 		= $this->debt_audit_model->product_level;

		$this->load->view('admin/_header');
		$this->load->view('admin/risk/risk_push_audit',$page_data);
		$this->load->view('admin/_footer');
	}

	public function push_audit_add(){
		$this->load->model('admin/debt_audit_model');
		$input 		= $this->input->post(NULL, TRUE);
		$get 		= $this->input->get(NULL, TRUE);
		$delay_days	= $this->target_model->get($get['id'])->delay_days;
		$last_push	= $this->debt_audit_model->order_by('created_at','desc')->get_by(array('target_id'=> $get['id']))->next_push;
		$info 		= $this->target_model->get($get['id']);
		if(isset($last_push)){
			$start_time=$last_push;
		}else{
			$start_time=time()-$delay_days*86400;
		}
		$rs = $this->debt_audit_model->insert(array(
			'admin_id'		=> $this->login_info->id,
			'user_id'		=> $info->user_id,
			'target_id'		=> $get['id'],
			'remark'		=> $input['remark'],
			'product_level'	=> $input['product_level'],
			'next_push'		=> $input['next_push'],
			'result'		=> $input['result'],
			'end_time'		=> $input['end_time'],
			'start_time'	=> $start_time
		));
	}

	function judicial_associates(){
		$page_data 	= array('type'=>'list','list'=>array());
		$input 		= $this->input->get(NULL, TRUE);
		$list		= array();

		if(isset($input['target_id'])) {
			//target_no
			$target_parm['id'] = $input['target_id'];
			$target = $this->target_model->get_by($target_parm);

			$product_list = $this->config->item('product_list');
			$certification_list = $this->config->item('certifications');
			$product = $product_list[$target->product_id];
			$sub_product_id = $target->sub_product_id;
			if ($this->is_sub_product($product, $sub_product_id)) {
				$product = $this->trans_sub_product($product, $sub_product_id);
			}

			foreach ($product['certifications'] as $ckey => $cvalue) {
				if (!isset($useCer[$cvalue]) && $cvalue < CERTIFICATION_FOR_JUDICIAL) {
					$useCer[$cvalue] = $certification_list[$cvalue];
				}
			}
			//									$associate_certification_ids = [1,3,6,11,501,9,7];
			$associates_list = $this->target_lib->get_associates_user_data($target->id, 'all', [0, 1], false);
			foreach ($associates_list as $associate) {
				$status = 1;
				$lastUpdate = 0;
				if ($associate->user_id != null) {
					if ($cer_status = $this->certification_lib->get_last_status($associate->user_id, BORROWER)) {
						foreach($product['certifications'] as $cer_statusKey => $cer_statusValue) {
							if($cer_statusValue < CERTIFICATION_FOR_JUDICIAL){
								if(isset($cer_status[$cer_statusValue])){
									$cer_status[$cer_statusValue]['user_status'] != 1 ? $status = 0 : '';
									$associate->certification[$cer_statusValue] = $cer_status[$cer_statusValue];
									$cer_status[$cer_statusValue]['updated_at'] > $lastUpdate ? $lastUpdate = $cer_status[$cer_statusValue]['updated_at'] : '';

									//驗證銀行帳號
//									if($cer_statusValue == 3){
//										$associate->bank_account_verify = 0;
//										$bank_account 	= $this->user_bankaccount_model->get_by(array(
//											'user_certification_id'	=> $cer_status[$cer_statusValue]['certification_id'],
//										));
//										if($bank_account){
//											$associate->bank_account = $bank_account;
//											$associate->bank_account_verify = $bank_account->verify==1?1:0;
//										}
//									}
								}
								else{
									$status = 0;
								}
							}
						}
					}
//					$associate->bank_account_verify == 1 && $status == 1 ? $status = 2 : '';
				}else{
					$status=0;
				}
				$associate->lastUpdate = $lastUpdate;
				$associate->cerStatus = $status;
				$list[] = $associate;
			}
		}

		$character_list = $this->config->item('character');
		$page_data['character_list'] = $character_list;
		$page_data['list'] 	= $list;
		$page_data['input'] = $input;
		$page_data['target_no'] = $target->target_no;
		$page_data['certification_list'] = $useCer;

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/judicial_person/judicial_associates_certification_list',$page_data);
		$this->load->view('admin/_footer');

	}


    private function sub_product_profile($product,$sub_product){
        return array(
            'id' => $product['id'],
            'visul_id' => $sub_product['visul_id'],
            'type' => $product['type'],
            'identity' => $product['identity'],
            'name' => $sub_product['name'],
            'description' => $sub_product['description'],
            'loan_range_s' => $sub_product['loan_range_s'],
            'loan_range_e' => $sub_product['loan_range_e'],
            'interest_rate_s' => $sub_product['interest_rate_s'],
            'interest_rate_e' => $sub_product['interest_rate_e'],
            'charge_platform' => $sub_product['charge_platform'],
            'charge_platform_min' => $sub_product['charge_platform_min'],
            'certifications' => $sub_product['certifications'],
            'instalment' => $sub_product['instalment'],
            'repayment' => $sub_product['repayment'],
            'targetData' => $sub_product['targetData'],
            'dealer' => $sub_product['dealer'],
            'multi_target' => $sub_product['multi_target'],
            'checkOwner' => $product['checkOwner'],
            'status' => $sub_product['status'],
        );
    }
    private function is_sub_product($product,$sub_product_id){
        $sub_product_list = $this->config->item('sub_product_list');
        return isset($sub_product_list[$sub_product_id]['identity'][$product['identity']]) && in_array($sub_product_id,$product['sub_product']);
    }

    private function trans_sub_product($product,$sub_product_id){
        $sub_product_list = $this->config->item('sub_product_list');
        $sub_product_data = $sub_product_list[$sub_product_id]['identity'][$product['identity']];
        $product = $this->sub_product_profile($product,$sub_product_data);
        return $product;
    }

	// 授信審核表
	// public function credit_management(){
	//
	// 	return
	// }
}
?>

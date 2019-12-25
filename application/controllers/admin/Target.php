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
		$fields 	= ['status','delay'];
		foreach ($fields as $field) {
			if (isset($input[$field])&&$input[$field]!='') {
			    $where[$field] = $input[$field];
            }
        }
        if(isset($input[$field])&&$input['tsearch']!=''){
            $tsearch = $input['tsearch'];
            if(preg_match("/^[\x{4e00}-\x{9fa5}]+$/u", $tsearch))
            {
                $name = $this->user_model->get_many_by(array(
                    'name like '    => '%'.$tsearch.'%',
                    'status'	    => 1
                ));
                if($name){
                    foreach($name as $k => $v){
                        $where['user_id'][] = $v->id;
                    }
                }
            }else{
                if(preg_match_all('/[A-Za-z]/', $tsearch)==1){
                    $id_number	= $this->user_model->get_many_by(array(
                        'id_number  like'	=> '%'.$tsearch.'%',
                        'status'	        => 1
                    ));
                    if($id_number){
                        foreach($id_number as $k => $v){
                            $where['user_id'][] = $v->id;
                        }
                    }
                }
                elseif(preg_match_all('/\D/', $tsearch)==0){
                    $where['user_id'] = $tsearch;
                }
                else{
                    $where['target_no like'] = '%'.$tsearch.'%';
                }
            }
        }

        !isset($where['status'])&&count($where)!=0||isset($where['status'])&&$where['status']=='510'?$where['status'] = [5,10]:'';
        if(isset($where['status'])&&$where['status']==99){
            unset($where['status']);
        }

		if(!empty($where)||isset($input['status'])&&$input['status']==99){
            isset($input['sdate'])&&$input['sdate']!=''?$where['created_at >=']=strtotime($input['sdate']):'';
            isset($input['edate'])&&$input['edate']!=''?$where['created_at <=']=strtotime($input['edate']):'';
			$list = $this->target_model->get_many_by($where);
			$tmp  = [];
			if($list){
				foreach($list as $key => $value){
					if($value->status==2 || $value->status==23 && $value->sub_status==0 ){
					    if(!isset($tmp[$value->user_id]['bank_account_verify'])){
                            $bank_account 		= $this->user_bankaccount_model->get_by(array(
                                'user_id'	=> $value->user_id,
                                'investor'	=> 0,
                                'status'	=> 1,
                                'verify'	=> 1,
                            ));
                            $tmp[$value->user_id]['bank_account_verify'] = $bank_account?1:0;
                        }
						$list[$key]->bank_account_verify = $tmp[$value->user_id]['bank_account_verify'];
                    }

                    if(!isset($tmp[$value->user_id]['school'])||!isset($tmp[$value->user_id]['company'])) {
                        $this->load->model('user/user_meta_model');
                        $get_meta = $this->user_meta_model->get_many_by([
                            'meta_key' => ['school_name', 'school_department','job_company'],
                            'user_id' => $value->user_id,
                        ]);
                        if ($get_meta) {
                            foreach ($get_meta as $skey => $svalue) {
                                $svalue->meta_key == 'school_name' ? $tmp[$svalue->user_id]['school']['school_name'] = $svalue->meta_value : '';
                                $svalue->meta_key == 'school_department' ? $tmp[$svalue->user_id]['school']['school_department'] = $svalue->meta_value : '';
                                $svalue->meta_key == 'job_company' ? $tmp[$svalue->user_id]['company'] = $svalue->meta_value : '';
                            }
                        }
                    }
                    if(isset($tmp[$value->user_id]['school']['school_name'])){
                        $list[$key]->school_name       = $tmp[$value->user_id]['school']['school_name'];
                        $list[$key]->school_department = $tmp[$value->user_id]['school']['school_department'];
                    }

                    isset($tmp[$value->user_id]['company'])?$list[$key]->company=$tmp[$value->user_id]['company']:'';

                    $amortization_table = $this->target_lib->get_amortization_table($value);
                    $list[$key]->remaining_principal = $amortization_table['remaining_principal'];

                    $limit_date  = $value->created_at + (TARGET_APPROVE_LIMIT*86400);
                    $credit		 = $this->credit_model->order_by('created_at','desc')->get_by([
                        'product_id' 	=> $value->product_id,
                        'user_id' 		=> $value->user_id,
                        'created_at <=' => $limit_date,
                    ]);
                    if($credit){
                        $list[$key]->credit = $credit;
                    }
                }
			}
		}
        $product_list    = $this->config->item('product_list');
        $sub_product_list = $this->config->item('sub_product_list');
        $instalment_list = $this->config->item('instalment');
        $repayment_type  = $this->config->item('repayment_type');
        $status_list     = $this->target_model->status_list;
        $delay_list      = $this->target_model->delay_list;
		if(isset($input['export'])&&$input['export']==1){
            header('Content-type:application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename=All_targets_'.date('Ymd').'.xls');
            $html = '<table><thead><tr><th>案號</th><th>產品</th><th>會員ID</th><th>信評</th><th>公司/學校</th><th>科系</th><th>申請金額</th><th>核准金額</th><th>動用金額</th><th>本金餘額</th><th>年化利率</th><th>期數</th><th>還款方式</th><th>放款日期</th><th>逾期狀況</th><th>逾期天數</th><th>狀態</th><th>申請日期</th><th>核准日期</th><th>邀請碼</th><th>備註</th></tr></thead><tbody>';

            if(isset($list) && !empty($list)){
                $subloan_list = $this->config->item('subloan_list');
                foreach($list as $key => $value){
                    $html .= '<tr>';
                    $html .= '<td>'.$value->target_no.'</td>';
                    $html .= '<td>'.$product_list[$value->product_id]['name'].($value->sub_product_id!=0?'/'.$sub_product_list[$value->sub_product_id]['identity'][$product_list[$value->product_id]['identity']]['name']:'').(preg_match('/'.$subloan_list.'/',$value->target_no)?'(產品轉換)':'').'</td>';
                    $html .= '<td>'.$value->user_id.'</td>';
                    $html .= '<td>'.$value->credit_level.'</td>';
                    $html .= '<td>'.(isset($value->company)?$value->company:'').(isset($value->company)&&isset($value->school_name)?' / ':'').(isset($value->school_name)?$value->school_name:'').'</td>';
                    $html .= '<td>'.(isset($value->school_department)?$value->school_department:'').'</td>';
                    $html .= '<td>'.$value->amount.'</td>';
                    $html .= '<td>'.(isset($value->credit->amount)?$value->credit->amount:'').'</td>';
                    $html .= '<td>'.$value->loan_amount.'</td>';
                    $html .= '<td>'.$value->remaining_principal.'</td>';
                    $html .= '<td>'.floatval($value->interest_rate).'</td>';
                    $html .= '<td>'.$instalment_list[$value->instalment].'</td>';
                    $html .= '<td>'.$repayment_type[$value->repayment].'</td>';
                    $html .= '<td>'.$value->loan_date.'</td>';
                    $html .= '<td>'.$delay_list[$value->delay].'</td>';
                    $html .= '<td>'.intval($value->delay_days).'</td>';
                    $html .= '<td>'.$status_list[$value->status].'</td>';
                    $html .= '<td>'.date("Y-m-d H:i:s",$value->created_at).'</td>';
                    $html .= '<td>'.(isset($value->credit->created_at)?date("Y-m-d H:i:s",$value->credit->created_at):'').'</td>';
                    $html .= '<td>'.$value->promote_code.'</td>';
                    $html .= '<td>'.$value->remark.'</td>';
                    $html .= '</tr>';
                }
            }
            $html .= '</tbody></table>';
            echo $html;
        }
		else{
            $page_data['product_list']		= $product_list;
            $page_data['sub_product_list'] = $sub_product_list;
            $page_data['instalment_list']	= $instalment_list;
            $page_data['repayment_type']	= $repayment_type;
            $page_data['list'] 				= $list;
            $page_data['status_list'] 		= $status_list;
            $page_data['delay_list'] 		= $delay_list;
            $page_data['name_list'] 		= $this->admin_model->get_name_list();


            $this->load->view('admin/_header');
            $this->load->view('admin/_title',$this->menu);
            $this->load->view('admin/target/targets_list',$page_data);
            $this->load->view('admin/_footer');
        }
    }

	public function edit(){
		$page_data 	= array('type'=>'edit');
		$get 		= $this->input->get(NULL, TRUE);
        $post 		= $this->input->post(NULL, TRUE);
        $sub_product_list = $this->config->item('sub_product_list');


		$id 		= isset($get['id'])?intval($get['id']):0;
		$display 	= isset($get['display'])?intval($get['display']):0;
        if(empty($post)) {
            if ($id) {
                $info = $this->target_model->get($id);
                if ($info) {
                    $this->load->library('Contract_lib');
                    $amortization_table = [];
                    $investments = [];
                    $investments_amortization_table = [];
                    $investments_amortization_schedule = [];
                    $order = [];
                    if ($info->status == 5 || $info->status == 10) {
                        $amortization_table = $this->target_lib->get_amortization_table($info);
                        $investments = $this->investment_model->get_many_by(array('target_id' => $info->id, 'status' => array(3, 10)));
                        if ($investments) {
                            foreach ($investments as $key => $value) {
                                $investments[$key]->contract = $this->contract_lib->get_contract($value->contract_id)['content'];
                                $investments[$key]->user_info = $this->user_model->get($value->user_id);
                                $investments[$key]->virtual_account = $this->virtual_account_model->get_by(array(
                                    'user_id' => $value->user_id,
                                    'investor' => 1,
                                    'status' => 1,
                                ));
                                $investments_amortization_table[$value->id] = $this->target_lib->get_investment_amortization_table($info, $value);
                            }
                        }
                    } else if ($info->status == 4) {
                        $investments = $this->investment_model->get_many_by(array('target_id' => $info->id, 'status' => 2));
                        if ($investments) {
                            foreach ($investments as $key => $value) {
                                $investments[$key]->contract = $this->contract_lib->get_contract($value->contract_id)['content'];
                                $investments[$key]->user_info = $this->user_model->get($value->user_id);
                                $investments[$key]->virtual_account = $this->virtual_account_model->get_by(array(
                                    'user_id' => $value->user_id,
                                    'investor' => 1,
                                    'status' => 1,
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
                    $judicial_person = '';
                    if ($info->order_id != 0) {
                        $this->load->model('transaction/order_model');
                        $order = $this->order_model->get($info->order_id);
                        $store_id = explode('-', $order->order_no)[0];
                        $this->load->model('user/cooperation_model');
                        $cooperation = $this->cooperation_model->get($store_id);
                        $this->load->model('user/judicial_person_model');
                        $judicial_person = $this->judicial_person_model->get_by([
                            'company_user_id' => $cooperation->company_user_id
                        ]);
                    }

                    $user_id = $info->user_id;
                    $bank_account = $this->user_bankaccount_model->get_many_by(array(
                        'user_id' => $user_id,
                        'investor' => 0,
                        'status' => 1,
                        'verify' => 1,
                    ));
                    $virtual_account = $this->virtual_account_model->get_by(array(
                        'user_id' => $user_id,
                        'investor' => 0,
                        'status' => 1,
                    ));

                    $bank_account_verify = $bank_account ? 1 : 0;
                    $credit_list = $this->credit_model->get_many_by(array('user_id' => $user_id));
                    $user_info = $this->user_model->get($user_id);
                    $page_data['sub_product_list'] = $sub_product_list;
                    $page_data['data'] = $info;
                    $page_data['order'] = $order;
                    $page_data['user_info'] = $user_info;
                    $page_data['judicial_person'] = $judicial_person;
                    $page_data['amortization_table'] = $amortization_table;
                    $page_data['investments'] = $investments;
                    $page_data['delivery_list'] = $this->target_model->delivery_list;
                    $page_data['investments_amortization_table'] = $investments_amortization_table;
                    $page_data['investments_amortization_schedule'] = $investments_amortization_schedule;
                    $page_data['credit_list'] = $credit_list;
                    $page_data['product_list'] = $this->config->item('product_list');
                    $page_data['sub_product_list'] = $this->config->item('sub_product_list');
                    $page_data['bank_account_verify'] = $bank_account_verify;
                    $page_data['virtual_account'] = $virtual_account;
                    $page_data['instalment_list'] = $this->config->item('instalment');
                    $page_data['repayment_type'] = $this->config->item('repayment_type');
                    $page_data['status_list'] = $this->target_model->status_list;
                    $page_data['loan_list'] = $this->target_model->loan_list;

                    if (isset($get['risk']) && $get['risk'] != null) {
                        $this->load->library('certification_lib');
                        if (isset($get['slist']) && $get['slist'] != null) {
                            $page_data['slist'] = $get['slist'];
                        }

                        $user_list = [];
                        $user_investor_list = [];
                        $certification_investor_list = [];

                        $targets = $this->target_model->get_many_by(array(
                            'user_id' => $user_id,
                            'id' => $info->id
                        ));
                        if ($targets) {
                            foreach ($targets as $key => $value) {
                                $list[$value->id] = $value;
                            }
                        }

                        if ($list) {
                            ksort($list);
                            foreach ($list as $key => $value) {
                                $list[$key]->certification = $this->certification_lib->get_last_status($value->user_id, 0);
                                if (isset($list[$key]->certification[3]['certification_id'])) {
                                    $bank_account = $this->user_bankaccount_model->get_by(array(
                                        'user_certification_id' => $list[$key]->certification[3]['certification_id'],
                                    ));
                                    $list[$key]->bank_account = $bank_account;
                                    $list[$key]->bank_account_verify = $bank_account->verify == 1 ? 1 : 0;
                                }
                            }
                        }

                        $page_data['list'] = $list;
                        $page_data['certification_investor_list'] = $certification_investor_list;
                        $page_data['certification'] = $this->config->item('certifications');
                        $this->load->view('admin/risk/risk_targets_edit', $page_data);
                    } else {
                        if (!$display) {
                            $this->load->view('admin/_title', $this->menu);
                        }
                        $this->load->view('admin/target/targets_edit', $page_data);
                    }

                    $this->load->view('admin/_header');
                    $this->load->view('admin/_footer');

                } else {
                    alert('ERROR , id is not exist', admin_url('target/index'));
                }
            } else {
                alert('ERROR , id is not exist', admin_url('target/index'));
            }
        }
        else{
            if(!empty($post['id'])) {
                $id = $post['id'];
                $targets = $this->target_model->get($id);
                $param = [
                    'status'      => $targets->status,
                    'loan_amount' => $targets->amount,
                    'sub_status'  => 0,
                    'remark'      => '核可消費額度',
                ];
                $this->target_model->update($id,$param);
                $this->target_lib->insert_change_log($id,$param);
                alert('額度已提升',admin_url('Target/waiting_verify'));
            }
        }
	}

	function verify_success(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		if($id){
			$info = $this->target_model->get($id);
			if($info && in_array($info->status,array(2,23))){
				if($info->sub_status==8){
					$this->load->library('subloan_lib');
					$this->subloan_lib->subloan_verify_success($info,$this->login_info->id);
				}if($info->status == 23 && $info->sub_status == 0){
                    $this->target_lib->order_verify_success($info,$this->login_info->id);
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
			if($info && in_array($info->status,array(0,1,2,22,23))){
				if($info->sub_status==8){
					$this->load->library('subloan_lib');
					$this->subloan_lib->subloan_verify_failed($info,$this->login_info->id,$remark);
				}else{
					$this->target_lib->target_verify_failed($info,$this->login_info->id,$remark);
				}
				echo '更新成功';die();
			}else{
				echo '更新失敗';die();
			}
		}else{
			echo '查無此ID';die();
		}
	}

	public function waiting_verify(){
		$page_data 					= array('type'=>'list');
		$input 						= $this->input->get(NULL, TRUE);
		$where						= array('status'=>[2,23]);
		$fields 					= ['target_no','user_id','delay'];
		$subloan_keyword			= $this->config->item('action_Keyword')[0];

		foreach ($fields as $field) {
			if (isset($input[$field])&&$input[$field]!='') {
				$where[$field] = $input[$field];
			}
		}
		$waiting_list 				= array();
		$list 						= $this->target_model->get_many_by($where);
		if($list){
			foreach($list as $key => $value){
				if($value->status==2 || $value->status==23 && ($value->sub_status==0 || $value->sub_status==9) ){
					$bank_account 	= $this->user_bankaccount_model->get_by(array(
						'user_id'	=> $value->user_id,
						'investor'	=> 0,
						'status'	=> 1,
						'verify'	=> 1,
					));


					$value -> subloan_count = count($this->target_model->get_many_by(
						array(
							'user_id'     => $value->user_id,
							'status !='   => "9",
							'remark like' => '%'.$subloan_keyword.'%'
						)
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
        $page_data['sub_product_list'] = $this->config->item('sub_product_list');
		$page_data['status_list'] 		= $this->target_model->status_list;
		$page_data['sub_list'] 		    = $this->target_model->sub_list;;
		$page_data['delay_list'] 		= $this->target_model->delay_list;
		$page_data['name_list'] 		= $this->admin_model->get_name_list();


		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/target/waiting_verify_target',$page_data);
		$this->load->view('admin/_footer');
	}

	public function credits()
	{
		$get = $this->input->get(NULL, TRUE);
		if (!$this->input->is_ajax_request()) {
			alert('ERROR, 只接受Ajax', admin_url('user/blocked_users'));
		}

		$targetId = isset($get["id"]) ? intval($get["id"]) : 0;
		$points = isset($get["points"]) ? intval($get["points"]) : 0;
		if($points>400){$points=400;}
		if($points<-400){$points=-400;}

		$this->load->library('output/json_output');
		$target = $this->target_model->get($targetId);

		if (!$target) {
			$this->json_output->setStatusCode(404)->send();
		}

		$this->load->library('credit_lib');

		$userId = $target->user_id;
		$credit = $this->credit_lib->get_credit($target->user_id, $target->product_id, $target->sub_product_id);
		$credit["product_id"] = $target->product_id;

		$this->load->library('utility/admin/creditapprovalextra', [], 'approvalextra');
		$this->approvalextra->setSkipInsertion(true);
		$this->approvalextra->setExtraPoints($points);

		$newCredits = $this->credit_lib->approve_credit($userId,$target->product_id,$target->sub_product_id, $this->approvalextra);
		$credit["amount"] = $newCredits["amount"];
		$credit["points"] = $newCredits["points"];
		$credit["level"] = $newCredits["level"];
		$credit["expire_time"] = $newCredits["expire_time"];
		$this->load->library('output/loan/credit_output', ["data" => $credit]);

		$response = [
			"credits" => $this->credit_output->toOne(),
		];
		$this->json_output->setStatusCode(200)->setResponse($response)->send();
	}

	public function evaluation_approval()
	{
		$post = $this->input->post(NULL, TRUE);

		$targetId = isset($post["id"]) ? intval($post["id"]) : 0;
		$points = isset($post["points"]) ? intval($post["points"]) : 0;
		$remark = isset($post["reason"]) ? strval($post["reason"]) : false;

        if ($points > 400) $points = 400;
        if ($points < -400) $points = -400;

		$this->load->library('output/json_output');

		$target = $this->target_model->get($targetId);
		if (!$target) {
			$this->json_output->setStatusCode(404)->send();
		}

		if ($target->status !=0 && $target->sub_status != 9) {
			$this->json_output->setStatusCode(404)->send();
		}

		$userId = $target->user_id;
		$credit = $this->credit_model->get_by([
            'user_id' => $userId,
            'product_id' => $target->product_id,
            'sub_product_id'=> $target->sub_product_id,
            'status' => 1
        ]);

		$this->load->library('utility/admin/creditapprovalextra', [], 'approvalextra');
		$this->approvalextra->setSkipInsertion(true);
		$this->approvalextra->setExtraPoints($points);

		$this->load->library('credit_lib');
		$newCredits = $this->credit_lib->approve_credit($userId,$target->product_id,$target->sub_product_id, $this->approvalextra);

        if ($remark) {
            if ($target->remark) {
                $update["remark"] = $target->remark . "," . $remark;
            } else {
                $update["remark"] = $remark;
            }
        }

		if (
			$newCredits["amount"] != $credit->amount
			|| $newCredits["points"] != $credit->points
			|| $newCredits["level"] != $credit->level
		) {
            $this->credit_model->update_by(
                [
                    'user_id' => $userId,
                    'product_id' => $target->product_id,
                    'sub_product_id'=> $target->sub_product_id,
                    'status' => 1
                ],
                ['status'=> 0]
            );
			$this->credit_model->insert($newCredits);
		}
        $this->target_lib->approve_target($target,$remark,true);
        $this->json_output->setStatusCode(200)->send();
    }

	public function final_validations()
	{
		$get = $this->input->get(NULL, TRUE);

		$targetId = isset($get["id"]) ? intval($get["id"]) : 0;

		if ($this->input->is_ajax_request()) {
			$this->load->library('output/json_output');

			$target = $this->target_model->get($targetId);
			if (!$target || $target->id <= 0) {
				$this->json_output->setStatusCode(404)->send();
			}
			if ($target->status != 0 && $target->sub_status != 9) {
				$this->json_output->setStatusCode(404)->send();
			}
			$this->load->library('output/loan/target_output', ['data' => $target], 'current_target_output');

			$userId = $target->user_id;
			$user = $this->user_model->get($userId);

			$userMeta = $this->user_meta_model->get_many_by(['user_id' 	=> $userId,]);
			$this->load->library('credit_lib');
			$credits = $this->credit_lib->get_credit($userId, $target->product_id, $target->sub_product_id);
			$credits["product_id"] = $target->product_id;

			$this->load->model('user/user_certification_model');
			$schoolCertificationDetail = $this->user_certification_model->get_by([
				'user_id' => $userId,
				'certification_id' => 2,
				'status' => 1,
			]);
			$schoolCertificationDetailArray = json_decode($schoolCertificationDetail->content, true);
			if (isset($schoolCertificationDetailArray["graduate_date"])) {
				 $graduateDate = new stdClass();
				 $graduateDate->meta_key = "school_graduate_date";
				 $graduateDate->meta_value = $schoolCertificationDetailArray["graduate_date"];
				 $userMeta[] = $graduateDate;
			}

			$this->load->library('mapping/user/usermeta', ["data" => $userMeta]);

			$instagramCertificationDetail = $this->user_certification_model->get_by([
				'user_id' => $userId,
				'certification_id' => 4,
				'status' => 1,
			]);
			$instagramCertificationDetailArray = json_decode($instagramCertificationDetail->content, true);
			if ($instagramCertificationDetailArray["type"] == "instagram") {
				$picture = $instagramCertificationDetailArray["info"]["picture"];
				$this->usermeta->setInstagramPicture($picture);
			}

			$user->profile = $this->usermeta->values();
			$user->school = $this->usermeta->values();
			$user->instagram = $this->usermeta->values();
			$user->facebook = $this->usermeta->values();

			$this->load->library('output/user/user_output', ["data" => $user]);
			$this->load->library('output/loan/credit_output', ["data" => $credits]);
			$this->load->library('certification_lib');
			$borrowerVerifications = $this->certification_lib->get_last_status($userId, 0, $user->company_status);
			$investorVerifications = $this->certification_lib->get_last_status($userId, 1, $user->company_status);
			$verificationInput = ["borrower" => $borrowerVerifications, "investor" => $investorVerifications];
			$this->load->library('output/user/verifications_output', $verificationInput);

			$bankAccount = $this->user_bankaccount_model->get_many_by([
				'user_id'	=> $userId,
				'status'	=> 1,
			]);

			$this->load->library('output/user/Bank_account_output', ['data' => $bankAccount]);

			$virtualAccounts = $this->virtual_account_model->get_many_by([
				'user_id' => $userId,
				'status' => 1,
			]);

			$this->load->library('Transaction_lib');
			foreach ($virtualAccounts as $virtualAccount) {
				$virtualAccount->funds = $this->transaction_lib->get_virtual_funds($virtualAccount->virtual_account);
			}

			$this->load->library('output/user/Virtual_account_output', ['data' => $virtualAccounts]);

			$targets = $this->target_model->get_many_by([
				"user_id" => $userId,
				"status NOT" => [8,9]
			]);

			foreach ($targets as $otherTarget) {
				$amortization = $this->target_lib->get_amortization_table($otherTarget);
				$otherTarget->amortization = $amortization;

				$validBefore  = $otherTarget->created_at + (TARGET_APPROVE_LIMIT*86400);
				$credit		 = $this->credit_model->order_by('created_at','desc')->get_by([
					'product_id' => $otherTarget->product_id,
					'user_id' => $userId,
					'created_at <=' => $validBefore,
				]);
				$otherTarget->credit = $credit;
			}

			$this->load->library('output/loan/target_output', ['data' => $targets]);

			$response = [
				"target" => $this->current_target_output->toOne(),
				"user" => $this->user_output->toOne(true),
				"credits" => $this->credit_output->toOne(),
				"verifications" => $this->verifications_output->toMany(),
				"bank_accounts" => $this->bank_account_output->toMany(),
				"virtual_accounts" => $this->virtual_account_output->toMany(),
				"targets" => $this->target_output->toMany(),
			];

			$this->json_output->setStatusCode(200)->setResponse($response)->send();
		}

		$this->load->view('admin/_header');
		$this->load->view('admin/_title', $this->menu);
		$this->load->view('admin/target/final_validations');
		$this->load->view('admin/_footer');
	}

	public function waiting_evaluation()
	{
		if ($this->input->is_ajax_request()) {
			$this->load->library('output/json_output');

			$where = ["status" => 0, "sub_status" => 9];
			$targets = $this->target_model->get_many_by($where);
			if (!$targets) {
				$this->json_output->setStatusCode(204)->send();
			}

			$userIds = [];
			$userIndexes = [];
			$index = 0;
			foreach ($targets as $target) {
				$userIds[] = $target->user_id;
				if (!isset($userIndexes[$target->user_id])) {
					$userIndexes[$target->user_id] = [];
				}
				$userIndexes[$target->user_id][] = $index++;
			}

			$users = $this->user_model->get_many_by(['id' => $userIds]);

			$numTargets = count($targets);
			$userList = array_fill(0, $numTargets, null);
			foreach ($users as $user) {
				$indexes = $userIndexes[$user->id];
				foreach ($indexes as $index) {
					$userList[$index] = $user;
				}
			}

			$this->load->library('output/loan/target_output', ['data' => $targets]);
			$this->load->library('output/user/user_output', ["data" => $userList]);

			$response = [
				"users" => $this->user_output->toMany(false),
				"targets" => $this->target_output->toMany(),
			];
			$this->json_output->setStatusCode(200)->setResponse($response)->send();
		}

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/target/waiting_evaluation');
		$this->load->view('admin/_footer');
	}
	
	public function waiting_loan(){
		$page_data 					= array('type'=>'list');
		$input 						= $this->input->get(NULL, TRUE);
		$where						= array('status'=>[4]);
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
				if(in_array($value->status,array(4))){
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
        $page_data['sub_product_list'] = $this->config->item('sub_product_list');
        $page_data['status_list'] 		= $this->target_model->status_list;
		$page_data['loan_list'] 		= $this->target_model->loan_list;
		$page_data['name_list'] 		= $this->admin_model->get_name_list();
		$page_data['sub_status_list'] 		= $this->target_model->sub_list;

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
					echo '產轉放款成功';die();
				}else{
					echo '產轉放款失敗';die();
				}
			}else{
				echo '查無此ID';die();
			}
		}else{
			echo '查無此ID';die();
		}
	}

	function re_subloan(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		if($id){
			$info = $this->target_model->get($id);
			if($info && $info->status==4 && $info->loan_status==2 && $info->sub_status==8){
                $this->load->library('subloan_lib');
				$rs = $this->subloan_lib->rollback_success_target($info,$this->login_info->id);
				if($rs){
					echo '重新上架成功';die();
				}else{
					echo '操作失敗';die();
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
			if($info && $info->status==4 && $info->loan_status==3 && ($info->sub_status==0||$info->sub_status==20||$info->sub_status==21)){
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
					'total_payment_m'		=> isset($amortization_table['list'][1]['total_payment']),
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
		$page_data['list'] 				= $list;
		$page_data['delay_list'] 		= $this->target_model->delay_list;
		$page_data['status_list'] 		= $this->target_model->status_list;
		$page_data['school_list'] 		= $school_list;
		$page_data['product_list']		= $this->config->item('product_list');
        $page_data['sub_product_list'] = $this->config->item('sub_product_list');

        $this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/target/targets_repayment',$page_data);
		$this->load->view('admin/_footer');
	}
	
	public function target_export(){
		$post 	= $this->input->post(NULL, TRUE);
		$ids 	= isset($post['ids'])&&$post['ids']?explode(',',$post['ids']):'';
		$status = isset($post['status'])&&$post['status']?$post['status']:5;
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
				$html .= '<td>'.(isset($school_list[$value->user_id]['school_name'])?$school_list[$value->user_id]['school_name']:'').'</td>';
				$html .= '<td>'.(isset($school_list[$value->user_id]['school_department'])?$school_list[$value->user_id]['school_department']:'').'</td>';
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
		$post = $this->input->post(NULL, TRUE);
		$ids = isset($post['ids'])&&$post['ids']?explode(',',$post['ids']):'';
		if($ids && is_array($ids)){
			$where = [
			    'id'     =>$ids,
                'status' =>5
            ];
		}else{
			$where = [
			    'status' =>5,
            ];
		}

		$data 		= [];
		$first_data = [];
		$list 	= $this->target_model->get_many_by($where);
		if($list){
            $total_payment       = 0;
            $principal           = 0;
            $interest            = 0;
            $repayment           = 0;
            $r_principal         = 0;
            $r_interest          = 0;

			foreach($list as $key => $value){
				$amortization_table = $this->target_lib->get_amortization_table($value);
				if($amortization_table){
					@$first_data[$amortization_table['date']] -= $amortization_table['amount'];
					foreach($amortization_table['list'] as $instalment => $value){
						@$data[$value['repayment_date']]['total_payment'] 	+= $value['total_payment'];
						@$data[$value['repayment_date']]['repayment'] 		+= $value['repayment'];
                        @$data[$value['repayment_date']]['interest'] 		+= $value['interest'];
                        @$data[$value['repayment_date']]['principal'] 		+= $value['principal'];
                        @$data[$value['repayment_date']]['r_principal'] 	+= $value['r_principal'];
                        @$data[$value['repayment_date']]['r_interest'] 		+= $value['repayment']-$value['r_principal'];
                        @$total_payment         		                    += $value['total_payment'];
                        @$principal         		                        += $value['principal'];
                        @$interest         		                            += $value['interest'];
                        @$repayment         		                        += $value['repayment'];
                        @$r_principal         		                        += $value['r_principal'];
                        @$r_interest         		                        += $value['repayment']-$value['r_principal'];
					}
				}
			}
		}

		header('Content-type:application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename=amortization_'.date('Ymd').'.xls');
		$html = '<table>';
		if(isset($first_data) && !empty($first_data)){
		    $sumvalue = 0;
			foreach($first_data as $key => $value){
				$html .= '<tr>';
				$html .= '<td>'.$key.'</td>';
				$html .= '<td>'.$value.'</td>';
				$html .= '<td></td><td></td><td></td><td></td>';
				$html .= '</tr>';
                $sumvalue -= $value;
			}
		}
        $html .= '<tr><td></td><td>'.$sumvalue.'</td><td></td><td></td><td></td></tr>';
        $html .= '<tr><td></td><td></td><td></td><td></td><td></td></tr>';
        $html .= '<tr><th>日期</th><th>應收本金</th><th>應收利息</th><th>合計</th><th>當期本金餘額</th><th>已實現本金</th><th>已實現利息</th><th>已回款</th></tr>';
		if(isset($data) && !empty($data)){
		    $total_unrepayment = 0;
			foreach($data as $key => $value){
			    $unrepayment = $value['principal']-$value['r_principal'];
				$html .= '<tr>';
				$html .= '<td>'.$key.'</td>';
                $html .= '<td>'.$value['principal'].'</td>';
                $html .= '<td>'.$value['interest'].'</td>';
                $html .= '<td>'.$value['total_payment'].'</td>';
                $html .= '<td>'.$unrepayment.'</td>';
                $html .= '<td>'.$value['r_principal'].'</td>';
                $html .= '<td>'.$value['r_interest'].'</td>';
                $html .= '<td>'.$value['repayment'].'</td>';
				$html .= '</tr>';
                $total_unrepayment +=$unrepayment;
			}
		}
        $html .= '<tr><td></td><td>'.$principal.'</td><td>'.$interest.'</td><td>'.$total_payment.'</td><td>'.$total_unrepayment.'</td><td>'.$r_principal.'</td><td>'.$r_interest.'</td><td>'.$repayment.'</td></tr>';
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
        $page_data['sub_product_list'] = $this->config->item('sub_product_list');
        $page_data['list'] 				= $list;
		$page_data['status_list'] 		= $this->target_model->status_list;
		$page_data['school_list'] 		= $school_list;

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/target/waiting_bidding_target',$page_data);
		$this->load->view('admin/_footer');
	}

    function cancel_bidding(){
        $get 	= $this->input->get(NULL, TRUE);
        $id 	= isset($get['id'])?intval($get['id']):0;
        $remark = isset($get['remark'])?$get['remark']:'';
        if($id){
            $info = $this->target_model->get($id);
            if($info && in_array($info->status,array(3))){
                if($info->sub_status==8){
                    $this->load->library('subloan_lib');
                    $this->subloan_lib->subloan_cancel_bidding($info,$this->login_info->id,$remark);
                }else{
                    $this->target_lib->target_cancel_bidding($info,$this->login_info->id,$remark);
                }
                echo '更新成功';die();
            }else{
                echo '查無此ID';die();
            }
        }else{
            echo '查無此ID';die();
        }
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
					'total_payment_m'		=> isset($amortization_table['list'][1]['total_payment']),
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
		$page_data['product_list']		= $this->config->item('product_list');
        $page_data['sub_product_list'] = $this->config->item('sub_product_list');
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
		$list 						= $this->target_model->get_many_by(['status'=>[1,21]]);
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
		$page_data['product_list']		= $this->config->item('product_list');
        $page_data['sub_product_list'] = $this->config->item('sub_product_list');
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

    public function waiting_approve_order_transfer(){
        $page_data 	  = array('type'=>'list');
        $waiting_list = array();
        $list 		  = $this->target_model->get_many_by(['status'=>[24]]);
        if($list){
            foreach($list as $key => $value){
                if($value->status==24){
                    $bank_account 	= $this->user_bankaccount_model->get_by(array(
                        'user_id'	=> $value->user_id,
                        'investor'	=> 0,
                        'status'	=> 1,
                        'verify'	=> 1,
                    ));
                }
                if($bank_account){
                    $waiting_list[] = $value;
                }
            }
        }
        $page_data['instalment_list']	= $this->config->item('instalment');
        $page_data['repayment_type']	= $this->config->item('repayment_type');
        $page_data['list'] 				= $waiting_list;
        $page_data['product_list']		= $this->config->item('product_list');
        $page_data['status_list'] 		= $this->target_model->status_list;
        $page_data['name_list'] 		= $this->admin_model->get_name_list();


        $this->load->view('admin/_header');
        $this->load->view('admin/_title',$this->menu);
        $this->load->view('admin/target/approve_order_transfer',$page_data);
        $this->load->view('admin/_footer');
    }

public function order_target(){
        $page_data 	  = array('type'=>'list');
        $list 		  = $this->target_model->get_many_by(['status'=>[20,21,22,23,24]]);

        $page_data['instalment_list']	= $this->config->item('instalment');
        $page_data['repayment_type']	= $this->config->item('repayment_type');
        $page_data['list'] 				= $list;
        $page_data['product_list']		= $this->config->item('product_list');
        $page_data['status_list'] 		= $this->target_model->status_list;
        $page_data['name_list'] 		= $this->admin_model->get_name_list();


        $this->load->view('admin/_header');
        $this->load->view('admin/_title',$this->menu);
        $this->load->view('admin/target/order_target',$page_data);
        $this->load->view('admin/_footer');
    }

    function approve_order_transfer(){
        $get 	= $this->input->get(NULL, TRUE);
        $id 	= isset($get['id'])?intval($get['id']):0;
        if($id){
            $info = $this->target_model->get($id);
            if($info && in_array($info->status,array(24))){
                $this->load->library('Transaction_lib');
                $rs = $this->transaction_lib->order_success($id);
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
}
?>

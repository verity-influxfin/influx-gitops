<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Target extends MY_Admin_Controller {

	protected $edit_method = array('edit','verify_success','verify_failed','order_fail','waiting_verify','evaluation_approval','final_validations','waiting_evaluation','waiting_loan','target_loan','subloan_success','re_subloan','loan_return','loan_success','loan_failed','target_export','amortization_export','prepayment','cancel_bidding','approve_order_transfer','legalAffairs','waiting_reinspection');

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
		$this->load->library('Spreadsheet_lib');
 	}

    public function isJson($inputString) {
        json_decode($inputString);
        return (json_last_error() == JSON_ERROR_NONE);
    }

	public function index(){

		$page_data 	= ['type'=>'list'];
		$input 		= $this->input->get(NULL, TRUE);
		$where		= [];
		$list		= [];
		$fields 	= ['status','delay'];

		if (isset($input['export'])) {
			switch ($input['export']) {
				case 2:
					$title_rows = [
						'user_id' => ['name' => '借款人ID'],
						'product_name' => ['name' => '產品名稱'],
						'target_no' => ['name' => '案號', 'width' => 20],
						'credit_level' => ['name' => '核准信評'],
						'user_meta_1' => ['name' => '學校/公司', 'width' => 25],
						'user_meta_2' => ['name' => '科系/職位', 'width' => 25],
						'invest_amount' => ['name' => '債權總額'],
						'lender' => ['name' => '投資人ID'],
						'unpaid_principal' => ['name' => '逾期本金'],
						'loan_date' => ['name' => '放款日期', 'width' => 12],
                        'limit_date' => ['name' => '首逾日期', 'width' => 12],
						'delayed_days' => ['name' => '逾期天數'],
						'unpaid_interest' => ['name' => '尚欠利息'],
						'delay_interest' => ['name' => '延滯息'],
						'damage' => ['name' => '違約金']
					];
					$data_rows = $this->target_model->getDelayedReport($input);
					$this->spreadsheet_lib->save($title_rows, $data_rows);
					return;
			}
		}

		foreach ($fields as $field) {
			if (isset($input[$field])&&$input[$field]!='') {
			    $where[$field] = $input[$field];
            }
        }
        if(isset($input[$field])&&isset($input['tsearch'])&&$input['tsearch']!=''){
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

        !isset($where['status'])&&count($where)!=0||isset($where['status'])&&$where['status']=='510'?$where['status'] = [TARGET_REPAYMENTING, TARGET_REPAYMENTED]:'';
        if(isset($where['status'])&&$where['status']==99){
            unset($where['status']);
        }

		if(!empty($where)||isset($input['status'])&&$input['status']==99){
            isset($input['sdate'])&&$input['sdate']!=''?$where['created_at >=']=strtotime($input['sdate']):'';
            isset($input['edate'])&&$input['edate']!=''?$where['created_at <=']=strtotime($input['edate']):'';
			$list = $this->target_model->get_many_by($where);
			$tmp  = [];
			if($list){
                $this->load->model('user/user_meta_model');
                foreach($list as $key => $value){
                    if ($this->isJson($value->reason)) {
                        $reasonJson = json_decode($value->reason, true);
                        $value->reason = sprintf("原因: %s, 敘述: %s", $reasonJson["reason"], $reasonJson["reason_description"]);
                    }

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
            $html = '<table><thead>
                    <tr><th>案號</th><th>產品</th><th>會員ID</th><th>新舊戶</th><th>信評</th><th>公司/學校</th>
                    <th>科系</th><th>是否完成實名驗證</th><th>申請金額</th><th>核准金額</th><th>動用金額</th><th>本金餘額</th>
                    <th>年化利率</th><th>期數</th><th>還款方式</th><th>放款日期</th><th>逾期狀況</th><th>逾期天數</th>
                    <th>狀態</th><th>借款原因</th><th>申請日期</th><th>申請時間</th><th>核准日期</th><th>核准時間</th>
                    <th>邀請碼</th><th>備註</th></tr>
                    </thead><tbody>';

            if(isset($list) && !empty($list)){
                $this->load->model('user/user_certification_model');
                $targetIds = array_column($list, 'id');
                $userLoanedCountList = $this->target_model->getUserStatusByTargetId($targetIds);
                $userLoanedCountList = array_column($userLoanedCountList, 'total_count', 'user_id');

                $where = ['investor' => USER_BORROWER, 'status' => 1];
                if(isset($input['edate']) && !empty($input['edate']) && strtotime($input['edate']))
                    $where['updated_at <= '] = strtotime($input['edate']);
                $userCertList = $this->user_certification_model->getCertificationsByTargetId($targetIds, $where);

                $subloan_list = $this->config->item('subloan_list');
                foreach($list as $key => $value){
                    $html .= '<tr>';
                    $html .= '<td>'.$value->target_no.'</td>';
                    $html .= '<td>'.$product_list[$value->product_id]['name'].($value->sub_product_id!=0?'/'.$sub_product_list[$value->sub_product_id]['identity'][$product_list[$value->product_id]['identity']]['name']:'').(preg_match('/'.$subloan_list.'/',$value->target_no)?'(產品轉換)':'').'</td>';
                    $html .= '<td>'.$value->user_id.'</td>';
                    $html .= '<td>'.(($userLoanedCountList[$value->user_id] ?? 0) > 0 ? '舊戶':'新戶').'</td>';
                    $html .= '<td>'.$value->credit_level.'</td>';
                    $html .= '<td>'.(isset($value->company)?$value->company:'').(isset($value->company)&&isset($value->school_name)?' / ':'').(isset($value->school_name)?$value->school_name:'').'</td>';
                    $html .= '<td>'.(isset($value->school_department)?$value->school_department:'').'</td>';
                    $html .= '<td>'.(isset($userCertList[$value->user_id]) && isset($userCertList[$value->user_id][CERTIFICATION_IDCARD]) ? "是" : "否").'</td>';
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
                    $html .= '<td>'.$value->reason.'</td>';
                    $html .= '<td>'.date("Y-m-d",$value->created_at).'</td>';
                    $html .= '<td>'.date("H:i:s",$value->created_at).'</td>';
                    $html .= '<td>'.(isset($value->credit->created_at)?date("Y-m-d",$value->credit->created_at):'').'</td>';
                    $html .= '<td>'.(isset($value->credit->created_at)?date("H:i:s",$value->credit->created_at):'').'</td>';
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
                $delay_list      = $this->target_model->delay_list;
                $info = $this->target_model->get($id);
                if ($info) {
                    $this->load->library('Contract_lib');
                    $amortization_table = [];
                    $investments = [];
                    $investments_amortization_table = [];
                    $investments_amortization_schedule = [];
                    $order = [];
                    if ($info->status == TARGET_REPAYMENTING || $info->status == TARGET_REPAYMENTED) {
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
                    } else if ($info->status == TARGET_WAITING_LOAN) {
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
                                    $info,
                                    date('Y-m-d')
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

                    $reason = $info->reason;
                    $json_reason = json_decode($reason);
                    if(isset($json_reason->reason)){
                        $reason = $json_reason->reason.' - '.$json_reason->reason_description;
                    }

                    $target_data = $info->target_data;
                    $json_target_data = json_decode($target_data);
                    if (isset($json_target_data->autoVerifyLog)) {
                        $page_data['autoVerifyLog'] = $json_target_data->autoVerifyLog;
                    }

                    $bank_account_verify = $bank_account ? 1 : 0;
                    $credit_list = $this->credit_model->get_many_by(array('user_id' => $user_id));
                    $user_info = $this->user_model->get($user_id);

                    $virtual_accounts = $this->virtual_account_model->get_many_by([
                        'user_id'	=> $user_id,
                        'investor'	=> 0,
                    ]);

                    if($info->sub_status == TARGET_SUBSTATUS_LAW_DEBT_COLLECTION){
                        $lawAccount = CATHAY_VIRTUAL_CODE . LAW_VIRTUAL_CODE . substr($user_info->id_number, 1, 9);
                        $page_data['lawAccount'] = $lawAccount;
                         $targetData = json_decode($info->target_data);
                        if (isset($targetData->legalAffairs)) {
                            $page_data['legalAffairs'] = $targetData->legalAffairs;
                        }
                    }

                    $page_data['sub_product_list'] = $sub_product_list;
                    $page_data['data'] = $info;
                    $page_data['reason'] = $reason;
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
                    $page_data['virtual_accounts'] = $virtual_accounts;
                    $page_data['instalment_list'] = $this->config->item('instalment');
                    $page_data['repayment_type'] = $this->config->item('repayment_type');
                    $page_data['delay_list'] 		= $delay_list;
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
                                $list[$key]->certification = $this->certification_lib->get_last_status($value->user_id, BORROWER);
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
                $this->load->library('Target_lib');
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
			if($info && in_array($info->status,array(TARGET_WAITING_VERIFY,TARGET_ORDER_WAITING_SHIP))){
				if($info->sub_status==TARGET_SUBSTATUS_SUBLOAN_TARGET){
					$this->load->library('subloan_lib');
					$this->subloan_lib->subloan_verify_success($info,$this->login_info->id);
				}if($info->status == TARGET_ORDER_WAITING_SHIP && $info->sub_status == TARGET_SUBSTATUS_NORNAL){
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
			if($info && in_array($info->status,array(
			        TARGET_WAITING_APPROVE,
                    TARGET_WAITING_SIGNING,
                    TARGET_WAITING_VERIFY,
                    TARGET_ORDER_WAITING_VERIFY,
                    TARGET_ORDER_WAITING_SHIP,
                    TARGET_BANK_FAIL,
                ))){
				if($info->sub_status==TARGET_SUBSTATUS_SUBLOAN_TARGET){
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

    function order_fail(){
        $get 	= $this->input->get(NULL, TRUE);
        $id 	= isset($get['id'])?intval($get['id']):0;
        $remark = isset($get['remark'])?$get['remark']:'';
        if($id){
            $info = $this->target_model->get($id);
            if($info && $info->status == TARGET_ORDER_WAITING_SIGNING){
                $this->load->library('subloan_lib');
                $this->target_lib->order_fail($info,$this->login_info->id,$remark);
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
		$where						= array('status'=>[TARGET_WAITING_VERIFY, TARGET_ORDER_WAITING_SHIP]);
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
                if ($value->status == TARGET_WAITING_VERIFY && $value->sub_status != TARGET_SUBSTATUS_WAITING_TRANSFER_INTERNAL
                    || $value->status == TARGET_WAITING_VERIFY && $value->sub_status == TARGET_SUBSTATUS_SECOND_INSTANCE
                    || $value->status == TARGET_ORDER_WAITING_SHIP &&  ($value->sub_status==TARGET_SUBSTATUS_NORNAL || $value->sub_status==TARGET_SUBSTATUS_SECOND_INSTANCE)
                ) {
					$bank_account 	= $this->user_bankaccount_model->get_by(array(
						'user_id'	=> $value->user_id,
						'investor'	=> 0,
						'status'	=> 1,
						'verify'	=> 1,
					));


					$value -> subloan_count = count($this->target_model->get_many_by(
						array(
							'user_id'     => $value->user_id,
							'status !='   => TARGET_FAIL,
							'remark like' => '%'.$subloan_keyword.'%'
						)
					));

					$value->bankaccount_verify = $bank_account?1:0;

                    $waiting_list[] = $value;
                }
			}
		}
		$page_data['instalment_list']	= $this->config->item('instalment');
		$page_data['repayment_type']	= $this->config->item('repayment_type');
		$page_data['list'] 				= $waiting_list;
		$page_data['product_list']		= $this->config->item('product_list');
        $page_data['sub_product_list'] = $this->config->item('sub_product_list');
		$page_data['status_list'] 		= $this->target_model->status_list;
		$page_data['sub_list'] 		    = $this->target_model->sub_list;
        $page_data['delay_list'] 		= $this->target_model->delay_list;
		$page_data['name_list'] 		= $this->admin_model->get_name_list();
        $page_data['externalCooperation'] = $this->config->item('externalCooperation');


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

        $points = min($points, 500);
        $points = max($points, 0);

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

        $level = false;
        if($target->product_id == 3 && $target->sub_product_id == STAGE_CER_TARGET){
            $this->load->library('Certification_lib');
            $certification = $this->certification_lib->get_certification_info($userId, 8, 0);
            $certificationStatus = isset($certification) && $certification
                ? ($certification->status == 1 ? true : false)
                : false;
            $level = $certificationStatus ? 3 : 4 ;
        }
        $newCredits = $this->credit_lib->approve_credit($userId,$target->product_id,$target->sub_product_id, $this->approvalextra, $level, false, false, $target->instalment);
        $credit["amount"] = $newCredits["amount"];
        $credit["points"] = $newCredits["points"];
        $credit["level"] = $newCredits["level"];
        $credit["expire_time"] = $newCredits["expire_time"];

        $product_list = $this->config->item('product_list');
        $product = $product_list[$target->product_id];
        if($this->is_sub_product($product,$target->sub_product_id)){
            $credit['sub_product_id'] = $target->sub_product_id;
            $credit['sub_product_name'] = $this->trans_sub_product($product,$target->sub_product_id)['name'];
        }
        $this->load->library('output/loan/credit_output', ["data" => $credit]);

        $response = [
			"credits" => $this->credit_output->toOne(),
		];
		$this->json_output->setStatusCode(200)->setResponse($response)->send();
	}

	public function evaluation_approval()
	{
		$post = $this->input->post(NULL, TRUE);
        $newCredits = false;

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

		if($target->sub_product_id != STAGE_CER_TARGET || $target->product_id == 3){
            $this->load->library('utility/admin/creditapprovalextra', [], 'approvalextra');
            $this->approvalextra->setSkipInsertion(true);
            $this->approvalextra->setExtraPoints($points);

            $level = false;
            if($target->product_id == 3 && $target->sub_product_id == STAGE_CER_TARGET){
                $this->load->library('Certification_lib');
                $certification = $this->certification_lib->get_certification_info($userId, 8, 0);
                $certificationStatus = isset($certification) && $certification
                    ? ($certification->status == 1 ? true : false)
                    : false;
                $level = $certificationStatus ? 3 : 4 ;
            }
            $this->load->library('credit_lib');
            $newCredits = $this->credit_lib->approve_credit($userId,$target->product_id,$target->sub_product_id, $this->approvalextra, $level, false, false, $target->instalment);
        }

        $remark = (empty($target->remark) ? $remark : $target->remark . ', '.$remark);

		if ($newCredits &&
            ($newCredits["amount"] != $credit->amount
			|| $newCredits["points"] != $credit->points
			|| $newCredits["level"] != $credit->level)
		) {
            $this->credit_model->update_by(
                [
                    'user_id' => $userId,
                    'product_id' => $target->product_id,
                    'sub_product_id'=> $target->sub_product_id,
                    'status' => 1,
                ],
                ['status'=> 0]
            );
			$this->credit_model->insert($newCredits);
		}

		if($target->sub_product_id == STAGE_CER_TARGET && $target->product_id == 1){
            $param['status'] = 1;
            $param['sub_status'] = 10;
            $param['remark'] = $remark;
            $this->target_model->update($target->id,$param);
        }
        else{
            $this->target_lib->approve_target($target,$remark,true);
        }
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

            $product_list = $this->config->item('product_list');
            $product = $product_list[$target->product_id];
            $sub_product_id = $target->sub_product_id;
            $target->productTargetData = $product;

            $this->config->load('credit',TRUE);
            $get_creditTargetData = $this->config->item('credit')['creditTargetData'];
            $target->creditTargetData = isset($get_creditTargetData[$target->product_id][$sub_product_id])?$get_creditTargetData[$target->product_id][$sub_product_id]:false;

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

            if($this->is_sub_product($product,$sub_product_id)){
                $getSubProduct = $this->trans_sub_product($product,$sub_product_id);
                $target->productTargetData = $getSubProduct;
                $credits['sub_product_id'] = $sub_product_id;
                $credits['sub_product_name'] = $getSubProduct['name'];
            }

            if($user->company_status == 0){
                $schoolCertificationDetailArray = $schoolCertificationDetail?json_decode($schoolCertificationDetail->content, true):false;
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

                $instagramCertificationDetailArray = isset($instagramCertificationDetail->content) &&
                 is_array(json_decode($instagramCertificationDetail->content, true))
                 ? json_decode($instagramCertificationDetail->content, true) : [];
                if (isset($instagramCertificationDetailArray["type"]) && $instagramCertificationDetailArray["type"] == "instagram") {
                    $picture = $instagramCertificationDetailArray["info"]["picture"];
                    $this->usermeta->setInstagramPicture($picture);
                }else if(isset($instagramCertificationDetailArray['instagram']['picture'])){
                    $picture =$instagramCertificationDetailArray['instagram']['picture'];
                    $this->usermeta->setInstagramPicture($picture);
                }

                $user->profile = $this->usermeta->values();
                $user->school = $this->usermeta->values();
                $user->instagram = $this->usermeta->values();
                $user->facebook = $this->usermeta->values();
            }elseif ($user->company_status == 1){
                $this->load->model('user/judicial_person_model');
                $judicial_person = $this->judicial_person_model->get_by([
                    'company_user_id' => $user->id,
                ]);
                $user->judicial_id = $judicial_person?$judicial_person->id:false;
            }

			$this->load->library('output/user/user_output', ["data" => $user]);
			$this->load->library('output/loan/credit_output', ["data" => $credits]);
			$this->load->library('certification_lib');

			$borrowerVerifications = $this->certification_lib->get_last_status($userId, BORROWER, $user->company_status,$target,$target->productTargetData);
			$investorVerifications = $this->certification_lib->get_last_status($userId, INVESTOR, $user->company_status);
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

			$where = ["status" => TARGET_WAITING_APPROVE, "sub_status" => TARGET_SUBSTATUS_SECOND_INSTANCE];
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
				"users" => $this->user_output->toMany(),
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
		$where						= array('status'=>[TARGET_WAITING_LOAN]);
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

	function subloan_success()
    {
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		if ($id) {
            // 啟用SQL事務
            $this->db->trans_start();
            $this->db->query('SET SESSION TRANSACTION ISOLATION LEVEL SERIALIZABLE;');

			// $info = $this->target_model->get($id);

            # issue_898
            $targetSql = sprintf("SELECT * FROM `%s`.`%s` WHERE `id` = '%s' FOR UPDATE", P2P_LOAN_DB, P2P_LOAN_TARGET_TABLE, $id);
            $sqlResult = $this->db->query($targetSql);
            $info = $sqlResult->row();

			if ($info && $info->status == 4 && $info->loan_status == 2 && $info->sub_status == 8) {
				$this->load->library('Transaction_lib');
				$rs = $this->transaction_lib->subloan_success($id, $this->login_info->id);
				if ($rs) {
					echo '產轉放款成功';
				} else {
					echo '產轉放款失敗';
				}
			} else {
				echo '案件不存在或已處理';
			}

            // 事務交易完成，提交結果
            $this->db->trans_complete();
		} else {
			echo '請輸入ID';
		}
	}

	function re_subloan(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		if($id){
			$info = $this->target_model->get($id);
			if($info && $info->status==TARGET_WAITING_LOAN && $info->loan_status==2 && $info->sub_status==TARGET_SUBSTATUS_SUBLOAN_TARGET){
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

	function loan_success()
    {
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		if ($id) {

            // 啟用SQL事務
            $this->db->trans_start();
            $this->db->query('SET SESSION TRANSACTION ISOLATION LEVEL SERIALIZABLE;');

            // $info = $this->target_model->get($id);

            # issue_898
            $targetSql = sprintf("SELECT * FROM `%s`.`%s` WHERE `id` = '%s' FOR UPDATE", P2P_LOAN_DB, P2P_LOAN_TARGET_TABLE, $id);
            $sqlResult = $this->db->query($targetSql);
            $info = $sqlResult->row();

			if ($info && $info->status == 4 && $info->loan_status == 3 && in_array($info->sys_check, [20, 21])) {
				$this->load->library('Transaction_lib');
				$rs = $this->transaction_lib->lending_success($id, $this->login_info->id);
				if ($rs) {
					echo '更新成功';
				} else {
					echo '更新失敗';
				}
			} else {
				echo '案件不存在或已處理';
			}

            // 事務交易完成，提交結果
            $this->db->trans_complete();
		} else {
			echo '請輸入ID';
		}
	}

	function loan_failed(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		if($id){
			$info = $this->target_model->get($id);
			if($info && $info->status==TARGET_WAITING_LOAN && $info->loan_status==3){
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
			if($info && in_array($info->status,array(TARGET_REPAYMENTING,TARGET_REPAYMENTED))){
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
		$list 						= $this->target_model->get_many_by(['status'=>TARGET_REPAYMENTING]);
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
		$status = isset($post['status'])&&$post['status']?$post['status']:TARGET_REPAYMENTING;
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

				if(in_array($value->status,[TARGET_REPAYMENTING,TARGET_REPAYMENTED])){
					$amortization_table = $this->target_lib->get_amortization_table($value);
					$list[$key]->amortization_table = [
						'total_payment_m'		=> $amortization_table['list'][1]['total_payment'],
						'total_payment'			=> $amortization_table['total_payment'],
						'remaining_principal'	=> $amortization_table['remaining_principal'],
					];
				}else{
					$amortization_table = $this->financial_lib->get_amortization_schedule($value->loan_amount,$value,$value->loan_date);
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
                <th>申請金額</th><th>核准金額</th><th>剩餘本金</th><th>年化利率</th><th>貸放期間</th>
                <th>計息方式</th><th>每月回款</th><th>回款本息總額</th><th>放款日期</th>
                <th>逾期狀況</th><th>逾期天數</th><th>狀態</th><th>申請日期</th><th>信評核准日期</th></tr></thead><tbody>';

		if(isset($list) && !empty($list)){

			foreach($list as $key => $value){
				$sub_status = $value->status==TARGET_REPAYMENTED&&$value->sub_status!= TARGET_SUBSTATUS_NORNAL ?'('.$sub_list[$value->sub_status].')':'';
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
                'status' =>TARGET_REPAYMENTING
            ];
		}else{
			$where = [
			    'status' =>TARGET_REPAYMENTING,
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
			'status'	=> array(TARGET_REPAYMENTING,TARGET_REPAYMENTED),
			'sub_status'=> array(TARGET_SUBSTATUS_PREPAYMENT,TARGET_SUBSTATUS_PREPAYMENTED),
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
		$list 						= $this->target_model->get_many_by(['status'=>[TARGET_WAITING_BIDDING, TARGET_BANK_VERIFY, TARGET_BANK_GUARANTEE, TARGET_BANK_LOAN]]);
        $tmp  = [];
        $personal = [];
        $judicialPerson = [];
        $judicialPersonFormBank = [];
        $externalCooperation = $this->config->item('externalCooperation');
		if($list){
			$this->load->model('log/Log_targetschange_model');
            $this->load->model('user/user_meta_model');
            foreach($list as $key => $value){
                $target_change	= $this->Log_targetschange_model->get_by(array(
					'target_id'		=> $value->id,
					'status'		=> [TARGET_WAITING_BIDDING, TARGET_BANK_VERIFY],
				));
                if($target_change){
                    $list[$key]->bidding_date = $target_change->created_at;
                }
                if (!isset($tmp[$value->user_id]['school']) || !isset($tmp[$value->user_id]['company'])) {
                    if($value->product_id >= PRODUCT_FOR_JUDICIAL){
                        $this->load->model("user/user_model");
                        $userData = $this->user_model->get_by(["id" => $value->user_id]);
                        $tmp[$value->user_id]['company'] = $userData->name;
                    }else{
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
                }
                if(isset($tmp[$value->user_id]['school']['school_name'])){
                    $list[$key]->school_name       = $tmp[$value->user_id]['school']['school_name'];
                    $list[$key]->school_department = $tmp[$value->user_id]['school']['school_department'];
                }

                isset($tmp[$value->user_id]['company'])?$list[$key]->company=$tmp[$value->user_id]['company']:'';

                if($value->product_id >= PRODUCT_FOR_JUDICIAL){
                    !in_array($value->product_id, $externalCooperation) ? $judicialPerson[] = $list[$key] : $judicialPersonFormBank[] = $list[$key];
                }
                else{
                    $personal[] = $list[$key];
                }
            }
		}
		$list = [
		    'personal' => $personal,
		    'judicialPerson' => $judicialPerson,
		    'judicialPersonFormBank' => $judicialPersonFormBank,
        ];

		$page_data['instalment_list']	= $this->config->item('instalment');
		$page_data['repayment_type']	= $this->config->item('repayment_type');
		$page_data['product_list']		= $this->config->item('product_list');
        $page_data['sub_product_list'] = $this->config->item('sub_product_list');
        $page_data['list'] 				= $list;
		$page_data['status_list'] 		= $this->target_model->status_list;
        $page_data['sub_list'] 		    = $this->target_model->sub_list;
        $page_data['externalCooperation'] = $externalCooperation;

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
            if($info && in_array($info->status,array(TARGET_WAITING_BIDDING))){
                if($info->sub_status==TARGET_SUBSTATUS_SUBLOAN_TARGET){
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
		$list 						= $this->target_model->get_many_by(['status'=>TARGET_REPAYMENTED]);
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
        $page_data = ['type' => 'list'];
        $input = $this->input->get(NULL, TRUE);
        $list = $this->target_model->get_many_by(['status' => [TARGET_WAITING_SIGNING, TARGET_ORDER_WAITING_SIGNING]]);
        $product_list = $this->config->item('product_list');
        $sub_product_list = $this->config->item('sub_product_list');
        $school_list = [];
        $user_list = [];
        $amortization_table = [];
        if ($list) {
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
				$amortization_table = $this->financial_lib->get_amortization_schedule($value->loan_amount,$value,$value->loan_date);
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
		$page_data['product_list']		= $product_list;
        $page_data['sub_product_list'] = $sub_product_list;
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
        $list 		  = $this->target_model->get_many_by(['status'=>[TARGET_ORDER_WAITING_VERIFY_TRANSFER]]);
        if($list){
            foreach($list as $key => $value){
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
        $list 		  = $this->target_model->get_many_by(['status'=>[TARGET_ORDER_WAITING_QUOTE,TARGET_ORDER_WAITING_SIGNING,TARGET_ORDER_WAITING_VERIFY,TARGET_ORDER_WAITING_SHIP,TARGET_ORDER_WAITING_VERIFY_TRANSFER]]);

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
            if($info && in_array($info->status,array(TARGET_ORDER_WAITING_VERIFY_TRANSFER))){
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

    public function get_test_data()
    {
        $input = $this->input->get();
        $productId = isset($input['product_id']) ? $input['product_id'] : 0;
        $order = isset($input['order']) ? $input['order'] : 'asc';
        $skip = isset($input['skip']) ? $input['skip'] : 0;
        $limit = isset($input['limit']) ? $input['limit'] : 500;
        $this->load->model("loan/target_model");
        $targets = $this->target_model->limit($limit, $skip)->order_by('id', $order)->get_many_by([
            "product_id" => $productId,
            "status" => [TARGET_WAITING_LOAN, TARGET_REPAYMENTING, TARGET_REPAYMENTED]
        ]);

        $users = [];
        $userIds = [];
        foreach ($targets as $target) {
            $userIds[] = $target->user_id;
        }

        $batch = 1000;
        $iters = intval(count($userIds) / $batch);
        if ($userIds && $iters == 0) $iters = 1;
        $this->load->model("user/user_model");
        $this->load->model("user/user_meta_model");
        $this->load->model("user/user_certification_model");
        for ($i = 0; $i < $iters; $i++) {
            $start = $i * $batch;
            $currentUserIds = array_slice($userIds, $start, $batch);
            $metaKeys = ["school_name", "school_major", "school_system", "school_grade", "financial_income", "job_seniority", "investigation_times", "investigation_credit_rate"];
            $metas = $this->user_meta_model->get_many_by(["user_id" => $currentUserIds, "meta_key" => $metaKeys]);
            foreach ($metas as $meta) {
                if (!isset($users[$meta->user_id])) {
                    $users[$meta->user_id] = [];
                }
                $users[$meta->user_id][$meta->meta_key] = $meta->meta_value;
            }

            $certificationDetails = $this->user_certification_model->get_many_by([
                'user_id' => $currentUserIds,
                'certification_id' => 4,
                'status' => 1,
            ]);

            $certificationKeys = ["media", "follows", "followed_by"];
            foreach ($certificationDetails as $certificationDetail) {
                if (!isset($users[$certificationDetail->user_id])) {
                    $users[$certificationDetail->user_id] = [];
                }
                $certificationContent = json_decode($certificationDetail->content);

                foreach ($certificationKeys as $key) {
                    if (isset($certificationContent->info->counts->$key))
                        $users[$certificationDetail->user_id][$key] = $certificationContent->info->counts->$key;
                }
            }

            $incomeExpenditureKeys = ['parttime', 'allowance', 'scholarship', 'other_income', 'restaurant', 'transportation', 'entertainment', 'other_expense'];
            $ieDetails = $this->user_certification_model->get_many_by([
                'user_id' => $currentUserIds,
                'certification_id' => 7,
            ]);
            foreach ($ieDetails as $ie) {
                if (!isset($users[$ie->user_id])) {
                    $users[$ie->user_id] = [];
                }
                $certificationContent = json_decode($ie->content);

                foreach ($incomeExpenditureKeys as $key) {
                    $users[$ie->user_id][$key] = $certificationContent->$key;
                }
            }

            $userKeys = ["id_number", "sex", "birthday", "id_card_place", "created_at"];
            $userInfoArray = $this->user_model->get_many_by(["id" => $currentUserIds]);
            foreach ($userInfoArray as $userInfo) {
                if (!isset($users[$userInfo->id])) {
                    $users[$userInfo->id] = [];
                }

                foreach ($userKeys as $key) {
                    $users[$userInfo->id][$key] = $userInfo->$key;
                }
            }
        }

        $result = [];
        $mapping = [];
        $metaMapping = [
            'parttime' => 'part_time',
            'allowance' => 'allowance',
            'scholarship' => 'scholarship',
            'other_income' => 'other_income',
            'restaurant' => 'food_expenditure',
            'transportation' => 'transportation_expenses',
            'entertainment' => 'entertainment_expenses',
            'other_expense' => 'other_expenses',

            "sex" => "gender",
            "id_card_place" => "location",
            "created_at" => "register_at",
            "birthday" => "dob",
            "school_name" => "uni",
            "school_major" => "department",
            "school_system" => "degree",
            "school_grade" => "grade",
            "media" => "posts",
            "follows" => "num_follow",
            "followed_by" => "num_followed_by",
            "financial_income" => "annual_inc",
            "job_seniority" => "emp_length",
            "investigation_times" => "inq_last_6mths",
            "investigation_credit_rate" => "bc_util",
        ];
        foreach ($targets as $target) {
            $output = [
                "id" => (int) $target->id,
                "product_id" => (int) $target->product_id,
                "target_no" => $target->target_no,
                "user_id" => $target->user_id,
                "loan_amnt" => (int) $target->amount,
                "funded_amnt" => (int) $target->loan_amount,
                "term" => (int) $target->instalment,
                "overdue" => $target->delay_days > 7,
                "purpose" => "",
                "verification_status" => false,
                "pymnt_plan" => true,
                "initial_list_status" => true,
                "home_ownership" => null,
            ];
            if ($target->reason) {
                $output["purpose"] = $target->reason;
                $reason = json_decode($target->reason);
                if (isset($reason->reason)) {
                    $output["purpose"] = $reason->reason;
                }
                if (isset($reason->reason_description)) {
                    $output["purpose"] .= "-" . $reason->reason_description;
                }
            }
            if (!isset($users[$target->user_id])) {
                foreach ($metaMapping as $key => $value) {
                    $output[$value] = null;
                }
                $result[] = $output;
                continue;
            }
            $user = $users[$target->user_id];
            foreach ($metaMapping as $key => $value) {
                if (!isset($user[$key])) {
                    $output[$value] = null;
                } else {
                    $output[$value] = $user[$key];
                    if ($key == "investigation_credit_rate") {
                        $output[$value] .= "%";
                    }
                    if ($key == "job_seniority") {
                        $output["verification_status"] = true;
                    }
                    if ($key == "id_card_place") {
                        $output["location"] = substr($user["id_number"], 0, 1);
                    }
                    if ($key == "birthday") {
                        $output["dob"] = strtotime($user["birthday"]);
                    }
                }
            }
            $result[] = $output;
        }
        if (!$result) {
            return;
        }
        $i = 0;
        $numCols = 33;
        foreach ($result[0] as $key => $value) {
            echo $key;
            $i++;
            if ($i <= $numCols) {
                echo ",";
            }
        }
        echo "<br>";
        foreach ($result as $each) {
            $i = 0;
            foreach ($each as $key => $value) {
                echo $value;
                $i++;
                if ($i <= $numCols) {
                    echo ",";
                }
            }
            echo "<br>";
        }
    }

    public function legalAffairs()
    {
        $get = $this->input->get(NULL, TRUE);
        $post = $this->input->post(NULL, TRUE);
        if(!empty($get['id'])) {
            $id = $get['id'];
            $targets = $this->target_model->get($id);
            $userInfo = $this->user_model->get($targets->user_id);
            if (!$userInfo) {
                return false;
            }

            $rs = $this->virtual_account_model->insert([
                'investor' => 0,
                'user_id' => $userInfo->id,
                'virtual_account' => CATHAY_VIRTUAL_CODE . LAW_VIRTUAL_CODE . substr($userInfo->id_number, 1, 9),
            ]);
            if ($rs) {
                $param = [
                    'sub_status' => TARGET_SUBSTATUS_LAW_DEBT_COLLECTION,
                ];
                $this->target_model->update($id, $param);
                $this->load->library('Target_lib');
                $this->target_lib->insert_change_log($id, $param);
                alert('已建立法催帳戶', admin_url('target/edit?id=' . $id));
            } else {
                alert('法催帳戶建立失敗', admin_url('target/edit?id=' . $id));
            }
        } elseif (!empty($post['id'])) {
            if (!empty($post['type'])) {
                if ($post['type'] == 'set') {
                    $id = $post['id'];
                    $targets = $this->target_model->get($id);
                    $targetData = json_decode($targets->target_data);
                    if (!isset($targetData->legalAffairs)) {
                        $list = [];
                        $fields = ['platformfee','fee','liquidateddamages','liquidateddamagesinterest','delayinterest'];
                        foreach ($fields as $field) {
                            if (isset($post[$field]) && is_numeric($post[$field])) {
                                $list[$field] = $post[$field];
                            }
                            else{
                                alert('輸入不完整', admin_url('target/edit?id=' . $id));
                            }
                        }
                        !isset($targetData->legalAffairs) ? $targetData = new stdClass() : '';
                        $targetData->legalAffairs = [
                            'platformfee' => $list['platformfee'],
                            'fee' => $list['fee'],
                            'liquidateddamages' => $list['liquidateddamages'],
                            'liquidateddamagesinterest' => $list['liquidateddamagesinterest'],
                            'delayinterest' => $list['delayinterest'],
                        ];
                        $param = [
                            'target_data' => json_encode($targetData),
                        ];
                        $this->target_model->update($id, $param);

                        $this->transaction_model->update_by([
                            'target_id' => $post['id'],
                            'source' => SOURCE_AR_DELAYINTEREST,
                            'status' => 1
                        ],[
                            'amount' => $list['delayinterest']
                        ]);

                        $this->transaction_model->update_by([
                            'target_id' => $post['id'],
                            'source' => SOURCE_AR_DAMAGE,
                            'status' => 1
                        ],[
                            'amount' => ($list['liquidateddamages'] + $list['liquidateddamagesinterest'])
                        ]);

                        $transaction = $this->transaction_model->get_by([
                            'target_id' => $post['id'],
                            'source' => SOURCE_AR_DAMAGE,
                            'status' => 1
                        ]);
                        $this->transaction_model->insert([
                            'source' => SOURCE_AR_FEES,
                            'entering_date' => get_entering_date(),
                            'user_from' => $transaction->user_from,
                            'bank_account_from' => $transaction->bank_account_from,
                            'amount' => ($list['platformfee'] + $list['fee']),
                            'target_id' => $post['id'],
                            'instalment_no' => $transaction->instalment_no,
                            'user_to' => $transaction->user_to,
                            'bank_account_to' => $transaction->bank_account_to,
                            'limit_date' => $transaction->limit_date,
                            'status' => 1,
                        ]);

                        alert('金額已寫入', admin_url('target/edit?id=' . $id));
                    }
                }
            }
            admin_url('target/edit?id=' . $id);
        }
        return false;
    }

    public function waiting_reinspection()
    {
        $get = $this->input->get(NULL, TRUE);
        $post = $this->input->post(NULL, TRUE);
        $target_id = isset($get['target_id']) ? $get['target_id'] : (isset($post['target_id']) ? $post['target_id'] : '');
        $target_info = $this->target_model->get_by(['id'=>$target_id]);
        if($target_info && in_array($target_info->product_id, $this->config->item('externalCooperation'))){
            if(count($post) > 0){
                if(isset($post['send_bank'])){
                    if($target_info->status == TARGET_WAITING_VERIFY
                        && $target_info->sub_status ==TARGET_SUBSTATUS_SECOND_INSTANCE){
                        $param = [
                            'status' => TARGET_BANK_VERIFY,
                            'sub_status' => TARGET_SUBSTATUS_NORNAL,
                        ];
                        $this->target_model->update($target_info->id,$param);
                        $this->load->library('Target_lib');
                        $this->target_lib->insert_change_log($target_info->id, $param);
                    }
                }
                elseif(isset($post['manual_handling'])){
                    if($target_info->status == TARGET_WAITING_VERIFY
                        && $target_info->sub_status ==TARGET_SUBSTATUS_SECOND_INSTANCE
                        || $target_info->status == TARGET_BANK_FAIL
                    ) {
                        $param = [
                            'status' => TARGET_WAITING_VERIFY,
                            'sub_status' => TARGET_SUBSTATUS_WAITING_TRANSFER_INTERNAL,
                        ];
                        $this->target_model->update($target_info->id, $param);
                        $this->load->library('Target_lib');
                        $this->target_lib->insert_change_log($target_info->id, $param);
                    }
                }
                elseif(isset($post['type']) && isset($post['for']) && isset($post['val'])){
                    $target_data = json_decode($target_info->target_data);
                    $typeList = ['reinspection_opinion','CRO_opinion','general_manager_opinion'];
                    $forList = ['comment','score'];
                    if (in_array($post['type'],$typeList) && in_array($post['for'],$forList)) {
                        //if(isset($target_data['$field']['comment'])){}
                        $type = $post['type'];
                        $for = $post['for'];
                        $now = time();
                        $newVal = new stdClass();
                        foreach ($target_data->reinspection->$type->$for as $key =>$value) {
                            $newVal->$key = $value;
                        }
                        $newVal->$now = $post['val'];
                        (object)$target_data->reinspection->$type->$for = $newVal;
                        $param = [
                            'target_data' => json_encode($target_data),
                        ];
                        $this->target_model->update($target_info->id,$param);
                    }
                }
            }
            else{
                $page_data['get'] = $get;
                $page_data['targetInfo'] = $target_info;
                $page_data['productList'] = $this->config->item('product_list');

                $this->load->view('admin/_header');
                $this->load->view('admin/_title',$this->menu);
                $this->load->view('admin/target/waiting_reinspection.php', $page_data);
                $this->load->view('admin/_footer');
            }
            $return['result'] = 'fail';
        }
        else{
            alert('不支援此產品', admin_url('target/waiting_verify'));
        }
        return true;
    }

    // 新光收件檢核表送件紀錄 api
    public function skbank_text_get(){
        $get = $this->input->get(NULL, TRUE);
        $this->load->library('output/json_output');

        if(!$this->input->is_ajax_request() || !isset($get['target_id']) || empty($get) || !is_numeric($get['target_id'])){
            $this->json_output->setStatusCode(400)->setErrorCode(RequiredArguments)->send();
        }
        $response = [];
        $this->load->model('skbank/LoanTargetMappingMsgNo_model');
        $this->LoanTargetMappingMsgNo_model->limit(1)->order_by("id", "desc");
        $mapping_info = $this->LoanTargetMappingMsgNo_model->get_by(['target_id'=>$get['target_id'],'type'=>'text','content !='=>'']);

        if(empty($mapping_info)){
            $this->json_output->setStatusCode(200)->setResponse($response)->send();
        }

        $this->load->model('skbank/LoanSendRequestLog_model');
        $msg_no_info = $this->LoanSendRequestLog_model->get_by(['msg_no'=>$mapping_info->msg_no, 'send_success ='=>1, 'case_no !='=>0 ]);
        if(empty($msg_no_info)){
            $this->json_output->setStatusCode(200)->setResponse($response)->send();
        }

        $response['skbankMsgNo'] = isset($msg_no_info->msg_no) ? $msg_no_info->msg_no : '';
        $response['skbankCaseNo'] = isset($msg_no_info->case_no) ? $msg_no_info->case_no: '';

        if(!empty($msg_no_info->request_content)){
            $request_content = json_decode($msg_no_info->request_content,true);
            $return_msg = json_decode($msg_no_info->response_content,true);
            $response['skbankCompId'] = isset($request_content['unencrypted']['CompId']) ? $request_content['unencrypted']['CompId'] : '';
            $response['skbankMetaInfo'] = isset($return_msg['ReturnMsg']) ? $return_msg['ReturnMsg'] : '';
            $response['skbankReturn'] = '成功';
        }
        $this->json_output->setStatusCode(200)->setResponse($response)->send();
    }

    // 新光收件檢核表送件 API
    public function skbank_text_send(){
        $get = $this->input->get(NULL, TRUE);
        $this->load->library('output/json_output');

        if(!$this->input->is_ajax_request() || !isset($get['target_id']) || empty($get) || !is_numeric($get['target_id'])){
            $this->json_output->setStatusCode(400)->setErrorCode(RequiredArguments)->send();
        }

        $this->load->model('skbank/LoanTargetMappingMsgNo_model');
        $this->LoanTargetMappingMsgNo_model->limit(1)->order_by("id", "desc");
        $skbank_save_info = $this->LoanTargetMappingMsgNo_model->get_by(['target_id'=>$get['target_id'],'type'=>'text','content !='=>'']);

        if(!$skbank_save_info || !isset($skbank_save_info->content) || empty($skbank_save_info->content)){
            $this->json_output->setStatusCode(400)->setErrorCode(ItemNotFound)->send();
        }

        $this->json_output->setStatusCode(200)->setResponse(json_decode($skbank_save_info->content,true))->send();

    }

    // 新光取得圖片
    public function skbank_image_get(){
        $get = $this->input->get(NULL, TRUE);
        $this->load->library('output/json_output');
        $response = [];

        $target_info 	= $this->target_model->get_by(['id'=>$get['target_id']]);
        if(!$target_info){
            $this->json_output->setStatusCode(400)->setErrorCode(RequiredArguments)->send();
        }
        $this->load->library('mapping/sk_bank/check_list');
        $image_url = $this->check_list->get_raw_data($target_info);

        $this->load->library('S3_lib');
        foreach($image_url as $image_type => $images){
            $response[$image_type] = [];
            if (!empty($image_url[$image_type])) {
                $response[$image_type] = $this->s3_lib->imagesToPdf($images,$target_info->user_id,$image_type,'skbank_raw_data');
            }
        }

        $this->json_output->setStatusCode(200)->setResponse($response)->send();
    }
}
?>

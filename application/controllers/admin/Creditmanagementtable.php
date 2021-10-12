<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Creditmanagementtable extends MY_Admin_Controller
{

	protected $edit_method = array();
	public function __construct()
    {
		parent::__construct();
		$this->load->model('loan/target_model');
	}

    public function index()
    {
        $this->load->view('admin/_header');
        $this->load->view('admin/_title',$this->menu);
        $this->load->view('admin/ocr/list');
        $this->load->view('admin/_footer');
    }

	// 授信審核表資料
	public function getCreditManagementInfo($target_info){
		$response = [];
		$user_id = $target_info->user_id;
		$this->load->model('user/user_meta_model');
		// print_r($user_id);exit;
		$meta_data = $this->user_meta_model->get_meta_value_by_user_id_and_meta_key($user_id,['1007_governmentauthorities']);
		if($meta_data){
			$company_info = isset($meta_data[0]->{'meta_value'}) ? json_decode($meta_data[0]->{'meta_value'},true) : '';
			// $company_info = json_decode($company_json,true);
			if($company_info){
				$response['company_name'] = isset($company_info['name']) ? $company_info['name'] : '';
				$response['tax_id'] = isset($company_info['tax_id']) ? $company_info['tax_id'] : '';
				$response['address'] = isset($company_info['address']) ? $company_info['address'] : '';
				$response['owner'] = isset($company_info['owner']) ? $company_info['owner'] : '';
				$response['director_id'] = isset($company_info['director_id']) ? $company_info['director_id'] : '';
				$response['create_date'] = isset($company_info['create_date']) ? $company_info['create_date'] : '';
				$response['company_type'] = isset($company_info['company_type']) ? $company_info['company_type'] : '';
			}
			// print_r($company_info);exit;
		}

		$meta_data = $this->user_meta_model->get_meta_value_by_user_id_and_meta_key($user_id,['1002_incomestatement']);
		if($meta_data){
			$company_info = isset($meta_data[0]->{'meta_value'}) ? json_decode($meta_data[0]->{'meta_value'},true) : '';
			$response['industryName'] = $company_info['input_89'];
		}
		$first_target_info = $this->target_model->get_by(['user_id'=>$user_id]);
		if($first_target_info){
			$response['firstApplyDate'] = isset($first_target_info->created_at) ? date('Y-m-d',$first_target_info->created_at) : '';
		}
		return $response;
	}

	// 授信審核表當前案件
	public function getCreditManagementThisCase($user_id,$time){
		$response = [];
		$response['loan_list_total'] = 0;
        $response['loan_list'] = [];
		// $this->load->model('loan/target_model');
		$target_infos = $this->target_model->get_many_by(['user_id'=>$user_id,'status'=>['2','3','4','5','10'],'created_at <'=>$time]);
		// print_r($target_infos);exit;
		foreach($target_infos as $key => $value){
			if($key == 0){
				$response['total_amount'] = isset($value->loan_amount) ? $value->loan_amount : 0;
				$response['total_amount'] = isset($value->loan_amount) ? $value->loan_amount : '';
			}
			$credit_type = '';
			if(isset($value->instalment) && is_numeric($value->instalment)){
				if($value->instalment<=12){
					$credit_type = '短放-';
				}
				if(84>=$value->instalment&&$value->instalment>=12){
					$credit_type = '中放-';
				}
				if(84<=$value->instalment){
					$credit_type = '長放-';
				}
				if($value->product_id == 1002){
					$credit_type .= '信保';
				}else{
					$credit_type .= '純保';
				}
			}
			$response['loan_list'][] = [
				// 授信種類
				'credit_type' => $credit_type,
				// 核可額度
				'approved_amount' => isset($value->loan_amount) ? $value->loan_amount : '',
				// 申請額度
				'applied_amount' => isset($value->amount) ? $value->amount : '',
				// 期數
				'instalment' => isset($value->instalment) ? $value->instalment : '',
				// 利率
				'interest_rate' => isset($value->interest_rate) ? $value->interest_rate : '',
				'interest_calculation_method' => '本息均攤',
				'dialing_method' => '一次動撥',
			];
			$response['loan_list_total'] += isset($value->loan_amount) ? $value->loan_amount : 0;
		}
		return $response;
	}

	//借款人資料
	public function getUserInfo(){
		$response = [];
		return $response;
	}
	// 核准額度資訊
	public function getHistoryCaseInfo(){
		$response = [];
		return $response;
	}
	// 二審
	public function getApproveInfo(){
		$response = [];
		return $response;
	}
	// 連保人資料
	public function getGuarantorInfo($target_id=''){
		$response = [];

		$this->load->library('Target_lib');
		if(method_exists($this->target_lib,'get_associates_user_data') && $target_id){
			// 找案件的相關人員(負責人、實際負責人、配偶、保證人) User ID
			$guarantors = $this->target_lib->get_associates_user_data($target_id, 'all', [0 ,1], false);
			// print_r($guarantors);exit;
			if($guarantors && is_array($guarantors)){
				foreach($guarantors as $k=>$v){
					$data = [];
					if($v->status == 2 || $v->status == 1){
						$user_id = isset($v->user_id) ? $v->user_id : '';
						if($user_id){
							// 身份證資料
							$this->load->model('user/user_model');
							$identity_info = $this->user_model->get_by(['id'=>$user_id]);
							// print_r($identity_info);exit;
							if($identity_info){
								$data = [
									'name' => isset($identity_info->name) ? $identity_info->name: '',
									'birthday' => isset($identity_info->birthday) ? $identity_info->birthday: '',
									'id' => isset($identity_info->id_number) ? $identity_info->id_number: '',
								];
							}
							// 負責人
							if($v->character == 0 || $v->character == 1){
								$data['relationship'] = '負責人';
							}
							// 實際負責人
							if($v->character == 2){
								$data['relationship'] = '實際負責人';
							}
							// 配偶
							if($v->character == 3){
								$data['relationship'] = '負責人配偶';
							}
							// 甲保證人
							if($v->character == 4){
								$data['relationship'] = '保證人';
							}
							// 乙保證人
							if($v->character == 5){
								$data['relationship'] = '保證人';
							}
							$data['netAssets'] = '';
							$data['otherAuthority'] = '';
						}
					}
					if($data){
						$response['relationship'][] =  $data;
					}
				}

			}
		}

		return $response;
	}
	// 擔保品
	public function getCollateralInfo(){
		$response = [];
		return $response;
	}
	// 現放明細
	public function getLoanInfo($user_id,$time){
		$response = [];
        $data = [];

		$info = $this->getCreditManagementThisCase($user_id,$time);
		// print_r($info);exit;
		if($info['loan_list']){
			foreach($info['loan_list'] as $k=>$v){
				if(!isset($data[$v['credit_type']])){
					$data['loan_total'][$v['credit_type']] = [
						// 授信種類
						'credit_type' => isset($v['credit_type']) ? $v['credit_type'] : '',
						'approved_amount' => isset($v['approved_amount']) ? $v['approved_amount'] : '',
						'amount' => isset($v['approved_amount']) ? $v['approved_amount'] : '',
						'interest_rate' => isset($v['interest_rate']) ? $v['interest_rate'] : '',
						'amount_dead_line' => '',
						'loan_dead_line' => '',
					];
				}else{
					$data['loan_total'][$v['credit_type']]['approved_amount'] += isset($v['approved_amount']) ? $v['approved_amount'] : 0;
					$data['loan_total'][$v['credit_type']]['approved_amount'] += isset($v['approved_amount']) ? $v['approved_amount'] : 0;
				}
			}
		}
        if($data && isset ($data['loan_total'])){
            $data['loan_total'] = array_values($data['loan_total']);
        }
		return $data;
	}
	// 受審表資料
    public function report()
    {
        $input = $this->input->get(NULL, TRUE);
        $target_id = isset($input['target_id']) ? $input['target_id'] : '';

		if(!$target_id){
			echo '缺少參數'.'參數：target_id = '.$target_id;exit;
		}

		// 進入受審表頁面
        if (!$this->input->is_ajax_request()) {
			$this->load->view('admin/target/management_list');
			return;
        }
		$response = [];
		$this->load->library('output/json_output');

		$target_info = $this->target_model->get_by(['id'=>$target_id]);
		if($target_info){
			$target_data = isset($target_info->target_data) ? json_decode($target_info->target_data,true): '';
			// print_r($target_data);exit;
				$merge_data = $this->getCreditManagementInfo($target_info);
				$response = array_merge($response,$merge_data);

				if(isset($target_info->user_id)){
					$merge_data = $this->getCreditManagementThisCase($target_info->user_id,$target_info->created_at);
					$response = array_merge($response,$merge_data);
					$merge_data = $this->getLoanInfo($target_info->user_id,$target_info->created_at);
					$response = array_merge($response,$merge_data);
				}
				if(isset($target_info->id)){
					$merge_data = $this->getGuarantorInfo($target_info->id);
					$response = array_merge($response,$merge_data);
				}
				// print_r($response);exit;
		}else{
			$this->json_output->setStatusCode(404)->setResponse(['找不到該案件資料'])->send();
		}
		// print_r($response);exit;
        $this->json_output->setStatusCode(200)->setResponse((array)$response)->send();
    }

}

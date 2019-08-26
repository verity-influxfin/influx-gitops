<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Transfer extends MY_Admin_Controller {
	
	protected $edit_method = array('assets_export','amortization_export','transfer_success','transfer_cancel','combination_transfer_cancel');
	
	public function __construct() {
		parent::__construct();
		$this->load->model('loan/investment_model');
		$this->load->model('loan/transfer_model');
		$this->load->model('loan/transfer_investment_model');
		$this->load->library('target_lib');
		$this->load->library('transfer_lib');
		$this->load->library('financial_lib');
 	}
	
	public function index(){
		$page_data 		= array('type'=>'list');
		$list 			= array();
		$transfers 		= array();
		$targets 		= array();
		$school_list 	= array();
		$input 			= $this->input->get(NULL, TRUE);
		$show_status 	= array(3,10);
		$where			= array();
		$target_no		= '';
		$fields 		= ['status','target_no','user_id'];
		
		foreach ($fields as $field) {
			if (isset($input[$field])&&$input[$field]!='') {
				if($field=='target_no'){
					$target_no = '%'.$input[$field].'%';
				}else{
					$where[$field] = $input[$field];
				}
			}
		}

        if(isset($input['status'])&&$input['status']==''&&isset($input['target_no'])&&$input['target_no']==''&&isset($input['user_id'])&&$input['user_id']==''){
            $where = [3,10];
        }
		if($target_no!='' || !empty($where)){
			$where['status'] = isset($where['status'])?$where['status']:$show_status;
			$query = $target_no!=''?['target_no like' => $target_no]:($where['status']==3?['status' => [5]]:['status' => [5,10]]);
			if(!empty($target_no)||$query){
				$target_ids 	= array();
				$target_list 	= $this->target_model->get_many_by(
                    $query
				);
				if($target_list){
					foreach($target_list as $key => $value){
						$target_ids[] = $value->id;
					}
					$where['target_id'] = $target_ids;
				}
			}
			
			if(isset($where['target_id']) || isset($where['user_id'])){
				$list 	= $this->investment_model->order_by('target_id','ASC')->get_many_by($where);
			}

			if($list){
				$target_ids 	= array();
				$ids 			= array();
				$user_list 		= array();

				foreach($list as $key => $value){
					$target_ids[] 	= $value->target_id;
					$ids[] 			= $value->id;
				}

				//$target_list 	= $this->target_model->get_many($target_ids);
				if($target_list){
					foreach($target_list as $key => $value){
						$user_list[] 		 = $value->user_id;
						$targets[$value->id] = $value;
					}
				}
				
				foreach($list as $key => $value){
					$list[$key]->amortization_table = $this->target_lib->get_investment_amortization_table($targets[$value->target_id],$value);
				}
				
				$this->load->model('user/user_meta_model');
				$users_school 	= $this->user_meta_model->get_many_by(array(
					'meta_key' 	=> array('school_name','school_department'),
					'user_id' 	=> $user_list,
				));
				if($users_school){
					foreach($users_school as $key => $value){
						$school_list[$value->user_id][$value->meta_key] = $value->meta_value;
					}
				}
			
				$transfer_list 	= $this->transfer_model->get_many_by(array('investment_id'=>$ids));
				if($transfer_list){
					foreach($transfer_list as $key => $value){
						$transfers[$value->investment_id] = $value;
					}
				}
			}
		}

		$page_data['repayment_type']			= $this->config->item('repayment_type');
		$page_data['list'] 						= $list;
		$page_data['delay_list'] 				= $this->target_model->delay_list;
		$page_data['status_list'] 				= $this->target_model->status_list;
		$page_data['show_status'] 				= $show_status;
		$page_data['investment_status_list'] 	= $this->investment_model->status_list;
		$page_data['transfer_status_list'] 		= $this->investment_model->transfer_status_list;
		$page_data['transfers'] 				= $transfers;
		$page_data['targets'] 					= $targets;
		$page_data['school_list'] 				= $school_list;

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/target/targets_assets',$page_data);
		$this->load->view('admin/_footer');
	}

	public function assets_export(){
		$get 			= $this->input->get(NULL, TRUE);
		$html 			= '';
		$ids 			= isset($get['ids'])&&$get['ids']?explode(',',$get['ids']):'';
		$list 			= array();
		$targets 		= array();
		$school_list 	= array();
		if($ids && is_array($ids)){
			$product_list		= $this->config->item('product_list');
			$list 				= $this->investment_model->order_by('target_id','ASC')->get_many($ids);
			$user_list 			= [];
			$amortization_table = [];
			if($list){
				$target_ids 	= array();
				$user_list 		= array();
				
				foreach($list as $key => $value){
					$target_ids[] 	= $value->target_id;
				}
				
				$target_list 	= $this->target_model->get_many($target_ids);
				if($target_list){
					foreach($target_list as $key => $value){
						$user_list[] 		 = $value->user_id;
						$targets[$value->id] = $value;
					}
				}
				
				foreach($list as $key => $value){
					$amortization_table = $this->target_lib->get_investment_amortization_table($targets[$value->target_id],$value);
					$amortization_list 	= array_values($amortization_table['list']);
					$list[$key]->amortization_table = array(
						'total_payment_m'		=> isset($amortization_list[0])?$amortization_list[0]['total_payment']:0,
						'total_payment'			=> $amortization_table['total_payment'],
						'remaining_principal' 	=> $amortization_table['remaining_principal'],
					);
				}
				
				$this->load->model('user/user_meta_model');
				$users_school 	= $this->user_meta_model->get_many_by(array(
					'meta_key' 	=> array('school_name','school_department'),
					'user_id' 	=> $user_list,
				));
				if($users_school){
					foreach($users_school as $key => $value){
						$school_list[$value->user_id][$value->meta_key] = $value->meta_value;
					}
				}
			}
			
			$repayment_type				= $this->config->item('repayment_type');
			$delay_list 				= $this->target_model->delay_list;
			$status_list 				= $this->target_model->status_list;
            $transfer_list 	= $this->transfer_model->get_many_by(array('investment_id'=>$ids));
            if($transfer_list){
                foreach($transfer_list as $key => $value){
                    $transfers[$value->investment_id] = $value;
                }
            }

			header('Content-type:application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename=assets_'.date('Ymd').'.xls');
			$html = '<table><thead><tr><th>案號</th><th>投資人 ID</th><th>借款人 ID</th><th>債權金額</th><th>案件總額</th><th>剩餘本金</th>
					<th>信用等級</th><th>學校名稱</th><th>學校科系</th><th>年化利率</th><th>期數</th>
					<th>還款方式</th><th>放款日期</th><th>逾期狀況</th><th>債權狀態</th>
					<th>債轉時間</th><th>案件狀態</th></tr></thead><tbody>';

			if(isset($list) && !empty($list)){
				
				foreach($list as $key => $value){
					$target = $targets[$value->target_id];
					$html .= '<tr>';
					$html .= '<td>'.$target->target_no.'</td>';
					$html .= '<td>'.$value->user_id.'</td>';
					$html .= '<td>'.$target->user_id.'</td>';
					$html .= '<td>'.$value->loan_amount.'</td>';
					$html .= '<td>'.$target->loan_amount.'</td>';
					$html .= '<td>'.$value->amortization_table["remaining_principal"].'</td>';
					$html .= '<td>'.$target->credit_level.'</td>';
					$html .= '<td>'.$school_list[$target->user_id]["school_name"].'</td>';
					$html .= '<td>'.$school_list[$target->user_id]["school_department"].'</td>';
					$html .= '<td>'.$target->interest_rate.'</td>';
					$html .= '<td>'.$target->instalment.'</td>';
					$html .= '<td>'.$repayment_type[$target->repayment].'</td>';
					$html .= '<td>'.$target->loan_date.'</td>';
					$html .= '<td>'.$delay_list[$target->delay].'</td>';
					$html .= '<td>'.($value->transfer_status==2?$this->investment_model->transfer_status_list[$value->transfer_status]:$this->investment_model->status_list[$value->status]).'</td>';
					$html .= '<td>'.($value->transfer_status==2&&isset($transfers[$value->id]->transfer_date)?$transfers[$value->id]->transfer_date:"").'</td>';
					$html .= '<td>'.(isset($status_list[$target->status])?$status_list[$target->status]:"").'</td>';
					$html .= '</tr>';
				}
			}
			$html .= '</tbody></table>';
		}
		echo $html;
	}
	
	public function amortization_export(){
		$get 			= $this->input->get(NULL, TRUE);
		$html 			= '';
		$ids 			= isset($get['ids'])&&$get['ids']?explode(',',$get['ids']):'';
		$list 			= array();
		if($ids && is_array($ids)){
			$investments 			= $this->investment_model->order_by('target_id','ASC')->get_many($ids);
			$amortization_table 	= array();
			if($investments){
				foreach($investments as $key => $value){
					$target = $this->target_model->get($value->target_id);
					$amortization_table = $this->target_lib->get_investment_amortization_table($target,$value);
					if($amortization_table && !empty($amortization_table['list'])){
						foreach($amortization_table['list'] as $k => $v){
							if(!isset($list[$v['repayment_date']])){
								$list[$v['repayment_date']] = array(
									'principal'	=> 0,
									'interest'	=> 0,
									'ar_fees'	=> 0,
									'repayment'	=> 0,
								);
							}
							$list[$v['repayment_date']]['principal'] 	+= $v['principal'];
							$list[$v['repayment_date']]['interest'] 	+= $v['interest'];
							$list[$v['repayment_date']]['ar_fees'] 		+= $v['ar_fees'];
							$list[$v['repayment_date']]['repayment'] 	+= $v['repayment'];
						}
					}
				}

			}

			header('Content-type:application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename=repayment_schedule_'.date('Ymd').'.xls');
			$html = '<table><thead><tr><th>還款日</th><th>還款本金</th><th>還款利息</th><th>還款合計</th><th>已還款</th><th>每期回款金額</th></tr></thead><tbody>';

			if(isset($list) && !empty($list)){
				ksort($list);
				foreach($list as $key => $value){
				    if(substr($key, -2)=='10'){
                        $total 	= $value['principal']+$value['interest'];
                        $profit = $value['principal']+$value['interest']-$value['ar_fees'];
                        $html .= '<tr>';
                        $html .= '<td>'.$key.'</td>';
                        $html .= '<td>'.$value['principal'].'</td>';
                        $html .= '<td>'.$value['interest'].'</td>';
                        $html .= '<td>'.$total.'</td>';
                        $html .= '<td>'.$value['repayment'].'</td>';
                        $html .= '<td>'.$profit.'</td>';
                        $html .= '</tr>';
                    }
				}
			}
			$html .= '</tbody></table>';
		}
		echo $html;
	}
	
	public function waiting_transfer(){
		$page_data 		= array('type'=>'list');
        $this->load->model('loan/transfer_combination_model');
		$transfers        = [];
        $combinations    = [];
		$where			= array(
			'status'	=> 0
		);
		$list 	= $this->transfer_model->get_many_by($where);
		if($list){
            $combination_ids = [];
            foreach($list as $key => $value){
                if(!in_array($value->combination,$combination_ids)) {
                    if ($value->combination != 0) {
                        array_push($combination_ids, $value->combination);
                        $investment = $this->investment_model->get($value->investment_id);
                        $combination_info = $this->transfer_combination_model->get($value->combination);
                        $combination_info->user_id = $investment->user_id;
                        $combination_info->expire_time = $value->expire_time;
                        $combinations[] = $combination_info;
                        array_splice($list, $key, 1);
                    } else {
                        $transfer_info = $value;
                        $transfer_info->target = $this->target_model->get($value->target_id);
                        $transfer_info->investment = $this->investment_model->get($value->investment_id);
                        $transfers[] = $transfer_info;
                    }
                }
			}
		}

		$page_data['instalment_list']			= $this->config->item('instalment');
		$page_data['repayment_type']			= $this->config->item('repayment_type');
		$page_data['list'] 						= $transfers;
		$page_data['combinations'] 				= $combinations;
		$page_data['transfer_status_list'] 		= $this->investment_model->transfer_status_list;

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/transfer/waiting_transfer',$page_data);
		$this->load->view('admin/_footer');
	}
	
    public function transfer_combination(){
		$page_data 		= array('type'=>'list');
        $input 			= $this->input->get(NULL, TRUE);
		$where			= array(
			'combination'	=> $input['id']
		);
        $list 	= $this->transfer_model->get_many_by($where);
        if($list){
            foreach($list as $key => $value){
                $list[$key]->target 	= $this->target_model->get($value->target_id);
                $list[$key]->investment = $this->investment_model->get($value->investment_id);
            }
        }

		$page_data['instalment_list']			= $this->config->item('instalment');
		$page_data['repayment_type']			= $this->config->item('repayment_type');
		$page_data['list'] 						= $list;
		$page_data['no'] 						= $input['no'];
		$page_data['transfer_status_list'] 		= $this->investment_model->transfer_status_list;

		$this->load->view('admin/_header');
		$this->load->view('admin/transfer/transfer_combination',$page_data);
		$this->load->view('admin/_footer');
	}

    public function transfer_combination_success(){
		$page_data 		= array('type'=>'list');
        $input 			= $this->input->get(NULL, TRUE);
		$where			= array(
			'combination'	=> $input['id']
		);
        $list 	= $this->transfer_model->get_many_by($where);
        if($list){
            foreach($list as $key => $value){
                $list[$key]->target 	= $this->target_model->get($value->target_id);
                $list[$key]->investment = $this->investment_model->get($value->investment_id);
                $list[$key]->transfer_investments = $transfer_investments = $this->transfer_investment_model->get_by(array('transfer_id' => $value->id,));
            }
        }

		$page_data['instalment_list']			= $this->config->item('instalment');
		$page_data['repayment_type']			= $this->config->item('repayment_type');
		$page_data['list'] 						= $list;
		$page_data['no'] 						= $input['no'];
		$page_data['transfer_status_list'] 		= $this->investment_model->transfer_status_list;

		$this->load->view('admin/_header');
		$this->load->view('admin/transfer/transfer_combination_success',$page_data);
		$this->load->view('admin/_footer');
	}

	public function waiting_transfer_success(){
		$page_data 		= array('type'=>'list');
        $this->load->model('loan/transfer_combination_model');
        $this->load->model('loan/transfer_investment_model');
        $transfers        = [];
        $combinations    = [];
		$where			= array(
			'status'	=> 1
		);
        $list 	= $this->transfer_model->get_many_by($where);
        if($list){
            $combination_ids = [];
            foreach($list as $key => $value){
                if(!in_array($value->combination,$combination_ids)) {
                    if ($value->combination != 0) {
                        array_push($combination_ids, $value->combination);
                        $investment = $this->investment_model->get($value->investment_id);
                        $combination_info = $this->transfer_combination_model->get($value->combination);
                        $combination_info->transfer = $value->id;
                        $combination_info->user_id = $investment->user_id;
                        $combination_info->expire_time = $value->expire_time;
                        $combinations[] = $combination_info;
                        array_splice($list, $key, 1);
                    } else {
                        $transfer_info = $value;
                        $transfer_info->target = $this->target_model->get($value->target_id);
                        $transfer_info->investment = $this->investment_model->get($value->investment_id);
                        $transfer_info->transfer_investments = $transfer_investments = $this->transfer_investment_model->get_by(array('transfer_id' => $value->id,));
                        $transfers[] = $transfer_info;
                    }
                }
            }
        }

        $page_data['instalment_list']			= $this->config->item('instalment');
        $page_data['repayment_type']			= $this->config->item('repayment_type');
        $page_data['list'] 						= $transfers;
        $page_data['combinations'] 				= $combinations;
		$page_data['transfer_status_list'] 		= $this->investment_model->transfer_status_list;

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/transfer/waiting_transfer_success',$page_data);
		$this->load->view('admin/_footer');
	}
	
	function transfer_success(){
		$get 	= $this->input->get(NULL, TRUE);
		$ids 	= isset($get['ids'])&&$get['ids']?explode(',',$get['ids']):'';
		if($ids && is_array($ids)){
			$this->load->library('Transaction_lib');
			foreach($ids as $key => $id){
				$rs = $this->transaction_lib->transfer_success($id,$this->login_info->id);
			}
            if($rs){
                echo '更新成功';die();
            }else{
                echo '更新失敗';die();
            }
		}else{
			echo '查無此ID';die();
		}
	}
	
	function transfer_cancel(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		if($id){
			$info = $this->transfer_model->get($id);
			if($info && $info->status==1){
				$this->load->library('transfer_lib');
				$rs = $this->transfer_lib->cancel_transfer($info,$this->login_info->id);
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

    function c_transfer_cancel(){
        $get 	= $this->input->get(NULL, TRUE);
        $id 	= isset($get['id'])?intval($get['id']):0;
        if($id){
            $this->load->model('loan/transfer_combination_model');
            $combination = $this->transfer_combination_model->get([ 'combination' =>$id]);
            $transfer    = $this->transfer_model->get_many_by([ 'combination' =>$id]);
            if($combination->count && count($transfer)){
                $this->load->library('transfer_lib');
                $rs = $this->transfer_lib->cancel_combination_transfer($transfer);
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
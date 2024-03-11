<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Prepayment_lib Class
 * @property CI_Controller $CI
 */
class Prepayment_lib{
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/transaction_model');
		$this->CI->load->model('loan/prepayment_model');
		$this->CI->load->library('Transaction_lib');
    }
 
	public function get_prepayment_info($target=[]){
		if($target->status == 5 && $target->delay_days==0){

			$transaction 	= $this->CI->transaction_model->order_by('limit_date','asc')->get_many_by([
				'target_id' => $target->id,
				'user_from' => $target->user_id,
				'status'	=> [1,2]
			]);
			if($transaction){
				$entering_date	= get_entering_date();
				$data = [
					'settlement_date'		=> $entering_date,//剩餘本金
					'remaining_instalment'	=> 0,//剩餘期數
					'remaining_principal'	=> 0,//剩餘本金
					'interest_payable'		=> 0,//應付利息
					'liquidated_damages'	=> 0,//違約金
					'total'					=> 0,
				];
				$last_settlement_date 	= $target->loan_date;
				$instalment_paid 		= 0;
				$remaining_principal	= [];
				$interest_payable		= [];
				foreach($transaction as $key => $value){
					if(!isset($remaining_principal[$value->investment_id]) || !isset($interest_payable[$value->investment_id])){
						$remaining_principal[$value->investment_id] 	= 0;
						$interest_payable[$value->investment_id] 		= 0;
					}

                    if($value->status==2 && $value->source==SOURCE_PRINCIPAL){
						$instalment_paid 		= $value->instalment_no;
                        //$last_settlement_date 	= $value->limit_date;
					}

                    if($value->status==2 && $value->source==SOURCE_AR_PRINCIPAL){
                        $last_settlement_date 	= $value->limit_date;
                    }

                    if($value->status==1){
						switch($value->source){
							case SOURCE_AR_PRINCIPAL:
								$remaining_principal[$value->investment_id]	+= $value->amount;
								break;
							case SOURCE_AR_INTEREST:
								if($value->limit_date <= $entering_date){
									$last_settlement_date = $value->limit_date;
									$interest_payable[$value->investment_id] += $value->amount;
								}
								break;
							default:
								break;
						}
					}
				}

                $data['remaining_instalment'] 	= $target->instalment - $instalment_paid;
                if($remaining_principal){
                    $days  = get_range_days($last_settlement_date,$entering_date) + 1; // 2024/01/22發現提前還款計息日期有誤，缺少一天
                    
                    $product_list = $this->CI->config->item('product_list');
                    $product = $product_list[$target->product_id];
                    $sub_product_id = $target->sub_product_id;
                    if($this->is_sub_product($product,$sub_product_id)){
                        $product = $this->trans_sub_product($product,$sub_product_id);
                    }

                    // 2024/01/22 修改了days的計算，因無法重現DS2P1的案件，所以先忽略DS2P1提前還款的影響
                    if($product['visul_id'] == 'DS2P1'){
                        $input = $this->CI->input->get(NULL, TRUE);
                        $this->CI->load->model('log/log_image_model');
                        $targetData = json_decode($target->target_data);
                        $certified_documents = false;
                        if(isset($input['certified_documents'])){
                            if(!empty($input['certified_documents'])){
                                $imgage = $this->CI->log_image_model->get_by([
                                    'id'		=> $input['certified_documents'],
                                    'user_id'	=> $target->user_id,
                                ]);
                                $targetData->certified_documents = '';
                                if($imgage){
                                    $targetData->certified_documents = $imgage->url;
                                }
                            }
                            else{
                                $targetData->certified_documents = '';
                            }
                            $this->CI->target_model->update($target->id,[
                                'target_data' => json_encode($targetData)
                            ]);
                        }
                        !empty($targetData->certified_documents)?$certified_documents = true:'';

                        $amortization_schedule = $this->CI->financial_lib->get_amortization_schedule($target->loan_amount, $target, $last_settlement_date, [
                            'days' => $days,
                            'sold' => $certified_documents ? 2 : 5,
                        ])['total'];
                        $remaining_principal = $amortization_schedule['principal'];
                        $interest_payable = $amortization_schedule['interest'];
                        $liquidated_damages = $amortization_schedule['share'];
                    }
					else{
					    if($days){
                            foreach($remaining_principal as $k => $v){
                                $interest_payable[$k] 	= $this->CI->financial_lib->get_interest_by_days($days,$v,$target->instalment,$target->interest_rate,$target->loan_date);
                            }
                        }
                        $remaining_principal = array_sum($remaining_principal);
                        $interest_payable = array_sum($interest_payable);
                        $liquidated_damages = $this->CI->financial_lib->get_liquidated_damages($remaining_principal,$target->damage_rate);
                    }

					$data['remaining_principal'] 	= $remaining_principal;
					$data['interest_payable'] 		= $interest_payable;
					$data['liquidated_damages'] 	= $liquidated_damages;
				}

                // 名校貸，滿三個月後提前清償者，不收取違約金
                if ($target->product_id == 1 && $target->sub_product_id == 6)
                {
                    $this->CI->load->model('transaction/transaction_model');
                    $transaction_status = $this->CI->transaction_model->get_repayment_status_by_target_id(
                        $target->id,
                        SOURCE_INTEREST,
                        3
                    );

                    if (isset($transaction_status['status']) && $transaction_status['status'] == TRANSACTION_STATUS_PAID_OFF)
                    {
                        $data['liquidated_damages'] = 0;
                    }
                }
				
				$data['total'] = $data['remaining_principal'] + $data['interest_payable'] + $data['liquidated_damages'];
				return $data;
			}
		}
		return false;
	}
	
	public function apply_prepayment($target){
		if($target && $target->status==TARGET_REPAYMENTING && $target->delay_days==0){
			$info  = $this->get_prepayment_info($target);
            $product_list = $this->CI->config->item('product_list');
            $product = $product_list[$target->product_id];
            $sub_product_id = $target->sub_product_id;
            if($this->is_sub_product($product,$sub_product_id)){
                $product = $this->trans_sub_product($product,$sub_product_id);
            }
            $virtualAccountParm = [
                'status'		=> 1,
                'investor'		=> 0,
                'user_id'		=> $target->user_id
            ];
            if($product['visul_id'] == 'DS2P1'){
                $virtualAccountParm['virtual_account like'] = TAISHIN_VIRTUAL_CODE.'%';
            }

            $this->CI->load->model('user/virtual_account_model');
			$virtual_account = $this->CI->virtual_account_model->get_by($virtualAccountParm);
			if($info && $virtual_account){
				$this->CI->load->library('Transaction_lib');
				$funds = $this->CI->transaction_lib->get_virtual_funds($virtual_account->virtual_account);
				$total = $funds['total'] - $funds['frozen'];
				if(intval($total)-intval($info['total'])>=0){
					$rs = $this->CI->prepayment_model->insert([
						'target_id'			=> $target->id,
						'settlement_date'	=> $info['settlement_date'],
						'amount'			=> $info['total'],
						'principal'			=> $info['remaining_principal'],
						'interest'			=> $info['interest_payable'],
						'damage'			=> $info['liquidated_damages'],
					]);
					if($rs){
						$this->CI->load->library('Target_lib');
						$this->CI->target_lib->insert_change_log($target->id,['sub_status'=>TARGET_SUBSTATUS_PREPAYMENT],0,0);
						$this->CI->target_model->update($target->id,['sub_status'=>TARGET_SUBSTATUS_PREPAYMENT]);
						$this->CI->load->library('Charge_lib');
						
						$target 	= $this->CI->target_model->get($target->id);
						$prepayment = $this->CI->prepayment_model->get($rs);
						$this->CI->charge_lib->charge_prepayment_target($target,$prepayment);
						return $rs;
					}
				}
			}
		}
		return false;
	}

	public function get_prepayment($target){
		if($target){
			$prepayment	= $this->CI->prepayment_model->order_by('settlement_date','desc')->get_by(['target_id'=>$target->id]);
			if($prepayment){
				return $prepayment;
			}
		}
		return false;
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
        $sub_product_list = $this->CI->config->item('sub_product_list');
        return isset($sub_product_list[$sub_product_id]['identity'][$product['identity']]) && in_array($sub_product_id,$product['sub_product']);
    }

    private function trans_sub_product($product,$sub_product_id){
        $sub_product_list = $this->CI->config->item('sub_product_list');
        $sub_product_data = $sub_product_list[$sub_product_id]['identity'][$product['identity']];
        $product = $this->sub_product_profile($product,$sub_product_data);
        return $product;
    }
}



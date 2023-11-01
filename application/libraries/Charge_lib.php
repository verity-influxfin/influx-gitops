<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Charge_lib
{

	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/transaction_model');
		$this->CI->load->model('user/virtual_account_model');
        $this->CI->load->library('Financial_lib');
		$this->CI->load->library('Passbook_lib');
		$this->CI->load->library('Transaction_lib');
    }


    /**
     * 針對應收款項進行清償，餘額不足額時可部分清償
     * @param int $balance 帳戶餘額
     * @param array $transactions transaction list
     * @return int 剩餘餘額
     */
    public function charge_partial_fee($balance, array $transactions) {
	    // 沒有餘額可還款時直接返回
	    if(!$balance)
	        return $balance;

        $date = get_entering_date();
        $origin_balance = $balance;

        $transaction_param = [];

        foreach ($transactions as $transaction) {
            // 沒有餘額可扣款時跳離
            if($balance <= 0)
                break;

            $paid_transaction = clone $transaction;
            if(($difference = $transaction->amount - $balance) > 0) {
                // 該筆交易紀錄部分金額沖償
                $rs = $this->CI->transaction_model->update_by(['id' =>$transaction->id, 'status' => 1], ['status' => 0]);
                if ($rs) {
                    // 剩餘應付帳款
                    $transaction_param[] = [
                        'source' => $transaction->source,
                        'entering_date' => $date,
                        'user_from' => $transaction->user_from,
                        'bank_account_from' => $transaction->bank_account_from,
                        'amount' => intval($difference),
                        'target_id' => $transaction->target_id,
                        'investment_id' => $transaction->investment_id,
                        'instalment_no' => $transaction->instalment_no,
                        'user_to' => $transaction->user_to,
                        'limit_date' => $transaction->limit_date,
                        'bank_account_to' => $transaction->bank_account_to,
                        'status' => TRANSACTION_STATUS_TO_BE_PAID
                    ];
                    // 結清應付帳款
                    $paid_transaction->amount = $balance;
                    $transaction_param[] = [
                        'source' => $transaction->source,
                        'entering_date' => $date,
                        'user_from' => $transaction->user_from,
                        'bank_account_from' => $transaction->bank_account_from,
                        'amount' => intval($balance),
                        'target_id' => $transaction->target_id,
                        'investment_id' => $transaction->investment_id,
                        'instalment_no' => $transaction->instalment_no,
                        'user_to' => $transaction->user_to,
                        'bank_account_to' => $transaction->bank_account_to,
                        'limit_date' => $transaction->limit_date,
                        'status' => TRANSACTION_STATUS_PAID_OFF
                    ];
                }
            }else{
                // 該筆交易紀錄全額沖償
                $rs = $this->CI->transaction_model->update_by(['id' =>$transaction->id, 'status' => 1], ['status' => 2]);
            }

            if ($rs) {
                $charge_source = convertARSourceToChargeSource($paid_transaction->source);
                $transaction_param[] = [
                    'source' => $charge_source,
                    'entering_date' => $date,
                    'user_from' => $paid_transaction->user_from,
                    'bank_account_from' => $paid_transaction->bank_account_from,
                    'amount' => intval($paid_transaction->amount),
                    'target_id' => $paid_transaction->target_id,
                    'investment_id' => $paid_transaction->investment_id,
                    'instalment_no' => $paid_transaction->instalment_no,
                    'user_to' => $paid_transaction->user_to,
                    'bank_account_to' => $paid_transaction->bank_account_to,
                    'status' => TRANSACTION_STATUS_PAID_OFF
                ];
                $balance -= $paid_transaction->amount;

            }else
                return $origin_balance;
        }

        // 將交易轉為入帳
        if ($transaction_param) {
            $rs = $this->CI->transaction_model->insert_many($transaction_param);
            if ($rs) {
                foreach ($rs as $key => $value) {
                    $this->CI->passbook_lib->enter_account($value);
                }
            }else
                return $origin_balance;
        }
        return $balance;
    }


    /**
     * 使用餘額沖償逾期戶的所有逾期案，可部分金額清償
     * @param int $user_id 使用者編號
     * @return bool
     */
    public function charge_delayed_target($user_id=0) {
        $this->CI->load->library('financial_lib');
        $this->CI->load->library('subloan_lib');
        $this->CI->load->library('Notification_lib');
        $this->CI->load->model('transaction/virtual_passbook_model');
		$date               = get_entering_date();
        $transaction_param = [];
        $target_key_list = [];
        $target_id_list = [];
        $legal_collection_target_id_list = [];

        // 償還交易科目 (需按照合約順序)
        $sorting_charge_ar_sources = getSortingARSourceList();
        // 需計算回款平台服務費的交易科目
        $platform_fee_ar_sources = getPlatformFeeRelatedARSourceList();

        // TODO: 借款端虛擬帳號入金時，需判斷有無法催帳號，有的話需先轉移差額至法催帳號，避免入金後被部分沖償而沒清還法催帳號 (寫在入金函數)

		$this->CI->transaction_model->trans_begin();
        $this->CI->virtual_passbook_model->trans_begin();

        $account_payable_list = $this->CI->transaction_model->
            getDelayedAccountPayable("tra.*", 0, $sorting_charge_ar_sources, $user_id, $date, '');
        if(!empty($account_payable_list)) {
            $target_id_list = array_unique(array_column($account_payable_list, 'target_id'));

            // 欲處理之案件列表，目前只處理學生貸和上班貸,房貸 (不處理有法催帳戶的案件 sub_status = 13)
            $targets = $this->CI->target_model->get_many_by(['id' => $target_id_list, 'product_id' => [1, 3, 5],
                'delay_days > ' => GRACE_PERIOD, 'status' => 5, 'sub_status != ' => 13]);
            $target_id_list = array_column($targets, 'id');

            // 撈取法催中的案件編號
            if(!empty($target_id_list)) {
                $legal_collection_investment = $this->CI->investment_model->get_many_by(['target_id' => $target_id_list,
                    'legal_collection_at >' => '1911-01-01 00:00:00',
                    'status' => INVESTMENT_STATUS_REPAYING]);
                $legal_collection_target_id_list = array_unique(array_column($legal_collection_investment, 'target_id'));
            }

            if(!empty($targets)) {
                $target_key_list = array_reduce($targets, function ($list, $item) {
                    $list[$item->id] = json_decode(json_encode($item), true);
                    return $list;
                }, []);
            }
        }

        $trans_rollback = function ($disableScriptStatus = TRUE) use ($target_id_list) {
            $this->CI->transaction_model->trans_rollback();
            $this->CI->virtual_passbook_model->trans_rollback();
            if($disableScriptStatus)
                $this->CI->target_model->setScriptStatus($target_id_list, 16, 0);
        };

        // 將欲處理之案件鎖 script 狀態
        $result = $this->CI->target_model->setScriptStatus($target_id_list, 0, 16);
        if(count($result) != count($target_id_list)) {
            $trans_rollback(FALSE);
            return FALSE;
        }

        // 轉換所有交易紀錄成對應的結構
        // array[target_id][investment_id]['transactions'][<source>] = [{stdclass of transaction}, {}, ..]
        // array[target_id][investment_id]['total_amount'][<source>] = Total account payable of source
        $account_payable_map = [];
		foreach ($account_payable_list as $value) {
		    // 交易科目可能會與欲處理案件列表有差異，不處理案件列表會沒有element
		    if( !isset($target_key_list[$value->target_id]) ||
                // 法催中/寬限期內不處理
                (in_array($value->target_id, $legal_collection_target_id_list)
                || !isDelayed($target_key_list[$value->target_id]['delay_days'] ?? 0))
            )
		        continue;

			if (!isset($account_payable_map[$value->target_id])) {
                $account_payable_map[$value->target_id] = [];
            }
			// Initialize
            if (!isset($account_payable_map[$value->target_id][$value->investment_id])) {
                $account_payable_map[$value->target_id][$value->investment_id] = [
                    'transactions' => [],
                    'total_amount' => array_fill_keys($sorting_charge_ar_sources, 0)
                ];
            }
            if (!isset($account_payable_map[$value->target_id][$value->investment_id]['transactions'][$value->source])) {
                $account_payable_map[$value->target_id][$value->investment_id]['transactions'][$value->source] = [];
            }
            $account_payable_map[$value->target_id][$value->investment_id]['transactions'][$value->source][] = $value;
            $account_payable_map[$value->target_id][$value->investment_id]['total_amount'][$value->source] += $value->amount;
		}
        array_walk($account_payable_map, function (&$item, $key) use ($sorting_charge_ar_sources) {
            foreach($sorting_charge_ar_sources as $ar_source) {
                $item['total_amount'][$ar_source] = array_sum(array_column(array_column($item, "total_amount"), $ar_source));
            }
        });

		// 計算借款人的所有交易的個別總金額
        $total_accounts_payable_list = [];
        foreach($sorting_charge_ar_sources as $ar_source)
            $total_accounts_payable_list[$ar_source] = array_sum(array_column(array_column($account_payable_map, "total_amount"), $ar_source));
        $total_accounts_payable = array_sum($total_accounts_payable_list);
        if ($total_accounts_payable <= 0) {
            $trans_rollback();
            return FALSE;
        }

        // 取得借款人的可使用帳戶 (目前只處理非外匯車之項目)
        // $virtual = $target->product_id != PRODUCT_FOREX_CAR_VEHICLE ? CATHAY_VIRTUAL_CODE.BORROWER_VIRTUAL_CODE : TAISHIN_VIRTUAL_CODE;
        $virtual = CATHAY_VIRTUAL_CODE.BORROWER_VIRTUAL_CODE;
        $virtual_account = $this->CI->virtual_account_model->setVirtualAccount($user_id, USER_BORROWER,
            VIRTUAL_ACCOUNT_STATUS_AVAILABLE, VIRTUAL_ACCOUNT_STATUS_USING, $virtual);
        if (empty($virtual_account)) {
            $trans_rollback();
            return FALSE;
        }

        // 取得借款人虛擬帳戶餘額
        $funds = $this->CI->transaction_lib->get_virtual_funds($virtual_account->virtual_account);
        $balance = $funds['total'] - $funds['frozen'];
        $available_balance = $balance;

        // 當餘額滿足所有逾期案之總和時，將交給全額清償的流程去處理，這邊不做清償
        $total_recovery_list = [];
        $target_paid_amount_list = [];
        $repay_notification_list = [];

        if ($available_balance < $total_accounts_payable) {
            foreach($sorting_charge_ar_sources as $ar_source) {
                $origin_balance = $available_balance;
                if($available_balance <= 0)
                    break;

                foreach ($account_payable_map as $target_id => $target_list) {
                    if (!is_numeric($target_id) || $total_accounts_payable_list[$ar_source] == 0)
                        continue;

                    $target_repayment_amount = min($target_list['total_amount'][$ar_source] / $total_accounts_payable_list[$ar_source] * $origin_balance, $target_list['total_amount'][$ar_source]);
                    if ($target_repayment_amount <= 0)
                        continue;

                    foreach ($target_list as $investment_id => $investment_list) {
                        if (!is_numeric($investment_id) || $investment_list['total_amount'][$ar_source] <= 0)
                            continue;

                        $investment_repayment_amount = intval(round(($investment_list['total_amount'][$ar_source] / $target_list['total_amount'][$ar_source] * $target_repayment_amount), 0));
                        if($investment_repayment_amount == 0)
                            continue;

                        // 處理金額平均分配後因為誤差而殘留的餘額
                        $remaining_balance = $available_balance - $investment_repayment_amount;
                        if($remaining_balance > 0 && $remaining_balance < 3) {
                            $investment_repayment_amount += $remaining_balance;
                            $investment_repayment_amount = min($investment_list['total_amount'][$ar_source], $investment_repayment_amount);
                        }

                        // 總還款金額不能大於餘額
                        $investment_repayment_amount = min($investment_repayment_amount, $available_balance);

                        $paid_balance = $this->charge_partial_fee($investment_repayment_amount, $investment_list['transactions'][$ar_source]);
                        $paid_amount = ($investment_repayment_amount - $paid_balance);
                        $available_balance -= $paid_amount;

                        // 案件累加還款金額
                        $target_paid_amount_list[$target_id] = ($target_paid_amount_list[$target_id] ?? 0) + $paid_amount;

                        // 累加需回款平台手續費的交易金額
                        if(in_array($ar_source, $platform_fee_ar_sources)) {
                            $total_recovery_list[$target_id][$investment_id] = (isset($total_recovery_list[$target_id][$investment_id]) ? $total_recovery_list[$target_id][$investment_id] : 0) + intval($paid_amount);
                        }
                    }
                }
            }

            foreach ($total_recovery_list as $target_id => $target_list) {
                $repay_notification_list[$target_id] = [];

                foreach ($target_list as $investment_id => $total_recovery) {
                    // 找到最近一期的本金交易紀錄
                    $principle_transaction = new stdClass();
                    array_reduce($account_payable_map[$target_id][$investment_id]['transactions'][SOURCE_AR_PRINCIPAL], function($min, $details) use (&$principle_transaction) {
                        if($details->instalment_no < $min) {
                            $principle_transaction = $details;
                            return $details->instalment_no;
                        }else
                            return $min;
                    }, PHP_INT_MAX);
                    $repay_notification_list[$target_id][$investment_id] = [
                        'user_from' => $principle_transaction->user_from,
                        'user_to' => $principle_transaction->user_to,
                    ];

                    $ar_fee = $this->CI->financial_lib->get_ar_fee($total_recovery);
                    if ($ar_fee > 0) {
                        // 處理已付平台服務費交易紀錄
                        $paid_fee_transaction = [
                            'source' => SOURCE_FEES,
                            'entering_date' => $date,
                            'user_from' => $principle_transaction->user_to,
                            'bank_account_from' => $principle_transaction->bank_account_to,
                            'amount' => $ar_fee,
                            'target_id' => $target_id,
                            'investment_id' => $investment_id,
                            'instalment_no' => $principle_transaction->instalment_no,
                            'user_to' => 0,
                            'bank_account_to' => PLATFORM_VIRTUAL_ACCOUNT,
                            'status' => TRANSACTION_STATUS_PAID_OFF
                        ];
                        $fee_transaction = $paid_fee_transaction;
                        $fee_transaction['source'] = SOURCE_AR_FEES;
                        $transaction_param[] = $paid_fee_transaction;
                        $transaction_param[] = $fee_transaction;

                        // 更新應收平台服務費交易紀錄
                        $ar_fee_transaction = $this->CI->transaction_model->get_by([
                            'investment_id' => $investment_id,
                            'source' => SOURCE_AR_FEES,
                            'status' => TRANSACTION_STATUS_TO_BE_PAID
                        ]);
                        if (isset($ar_fee_transaction)) {
                            $remaining_fee = max(0, $ar_fee_transaction->amount - $ar_fee);
                            $this->CI->transaction_model->update_by(['id' => $ar_fee_transaction->id, 'status' => TRANSACTION_STATUS_TO_BE_PAID],
                                [
                                    'amount' => $remaining_fee,
                                ]);

                        }
                    }
                }
            }
        }

        $rs = $this->CI->transaction_model->insert_many($transaction_param);
        if ($rs) {
            foreach ($rs as $value) {
                $this->CI->passbook_lib->enter_account($value);
            }
        }

        if ($this->CI->transaction_model->trans_status() === TRUE &&
            $this->CI->virtual_passbook_model->trans_status() === TRUE ) {
            $this->CI->transaction_model->trans_commit();
            $this->CI->virtual_passbook_model->trans_commit();
            $this->CI->target_model->setScriptStatus($target_id_list, 16, 0);

            // 通知投資人已部分還款
            foreach ($total_recovery_list as $target_id => $target_list) {
                foreach ($target_list as $investment_id => $total_recovery) {
                    $this->CI->notification_lib->repay_partial_success($repay_notification_list[$target_id][$investment_id]['user_to'],
                        1, $target_key_list[$target_id]['target_no'], $total_recovery, 0);
                }
            }

            // 通知借款人已部分還款
            foreach ($target_paid_amount_list as $target_id => $paid_amount) {
                $total_target_accounts_payable = array_sum($account_payable_map[$target_id]['total_amount']);
                $this->CI->notification_lib->repay_partial_success($user_id, 0,
                    $target_key_list[$target_id]['target_no'], $paid_amount,
                    $total_target_accounts_payable - $paid_amount);
            }

            // 針對有回款的投資人取消申請中之債轉
            if(!empty($total_recovery_list)) {
                $investment_id_list = array_map(function ($x) {
                    return key($x);
                }, array_values($total_recovery_list));
                $this->CI->load->library('transfer_lib');
                $this->CI->load->model('loan/transfer_model');
                $investment = $this->CI->investment_model->get_many_by([
                    'id' => $investment_id_list,
                    'transfer_status' => 1,
                ]);
                if (isset($investment) && count($investment)) {
                    $investmentList = array_reduce(json_decode(json_encode($investment), true), function ($list, $item) {
                        $list[$item['id']] = $item;
                        return $list;
                    }, []);

                    // 0:待出借 1:待放款
                    $transfer_list = $this->CI->transfer_model->get_many_by([
                        'investment_id' => array_keys($investmentList),
                        'status' => [0, 1]
                    ]);
                    foreach ($transfer_list as $value) {
                        if ($value->combination != 0) {
                            $transfer = $this->CI->transfer_model->get_many_by(['combination' => $value->combination]);
                            $result = $this->CI->transfer_lib->cancel_combination_transfer($transfer);
                        } else {
                            $result = $this->CI->transfer_lib->cancel_transfer($value);
                        }
                        if($result) {
                            $this->CI->notification_lib->cancel_transfer_bidding($investmentList[$value->investment_id]['user_id'],
                                $target_key_list[$investmentList[$value->investment_id]['target_id']]['target_no']);
                        }
                    }
                }

                // 取消案件產轉申請
                foreach ($targets as $target) {
                    $subloan = $this->CI->subloan_lib->get_subloan($target);
                    if (isset($subloan) && $subloan) {
                        if (in_array($subloan->status, array(0, 1, 2))) {
                            if($this->CI->subloan_lib->cancel_subloan($subloan)) {
                                $this->CI->notification_lib->cancel_subloan_bidding($target);
                            }
                        }
                    }
                }
            }
        }else{
            $trans_rollback();
        }

        $virtual_account = $this->CI->virtual_account_model->setVirtualAccount($user_id, USER_BORROWER,
            VIRTUAL_ACCOUNT_STATUS_USING, VIRTUAL_ACCOUNT_STATUS_AVAILABLE, $virtual);
        if (empty($virtual_account)) {
            error_log("Changing status of virtual account was failed.");
        }

        // 餘額超過逾期總額，採用正常還款方式清償
        if ($available_balance >= $total_accounts_payable) {
            foreach ($target_key_list as $value) {
                // convert to stdClass
                $target = json_decode(json_encode($value));
                $this->charge_normal_target($target);
            }
        }

        return TRUE;
	}

	public function charge_normal_target($target=[]){
		$date			= get_entering_date();
        $amount			= 0;
        $limit_date		= '';
        $user_to		= [];
        $source_list 	= [
            SOURCE_AR_PRINCIPAL,
            SOURCE_AR_INTEREST,
            SOURCE_AR_DAMAGE,
            SOURCE_AR_DELAYINTEREST
        ];
        $charge_source_list = [
            SOURCE_AR_PRINCIPAL		=> SOURCE_PRINCIPAL,
            SOURCE_AR_INTEREST		=> SOURCE_INTEREST,
            SOURCE_AR_DAMAGE		=> SOURCE_DAMAGE,
            SOURCE_AR_DELAYINTEREST	=> SOURCE_DELAYINTEREST,
            SOURCE_AR_FEES			=> SOURCE_FEES,
        ];
        if($target->sub_status == 13){
            $transaction 	= $this->CI->transaction_model->get_many_by([
                'target_id'		=> $target->id,
                'user_from'		=> $target->user_id,
                'limit_date <=' => $date,
                'status'		=> 1,
            ]);
            if($transaction) {
                $userInfo = $this->CI->user_model->get($target->user_id);
                $targetData = json_decode($target->target_data);
                $lawAccount = CATHAY_VIRTUAL_CODE . LAW_VIRTUAL_CODE . substr($userInfo->id_number, 1, 9);
                $virtual_account = $this->CI->virtual_account_model->get_by([
                    'status' => 1,
                    'investor' => 0,
                    'user_id' => $target->user_id,
                    'virtual_account' => $lawAccount
                ]);
                if ($virtual_account && isset($targetData->legalAffairs)) {
                    $this->CI->virtual_account_model->update($virtual_account->id, ['status' => 2]);
                    $funds = $this->CI->transaction_lib->get_virtual_funds($virtual_account->virtual_account);
                    $total = $funds['total'] - $funds['frozen'];
                    $transaction_param = [];
                    $pass_book = [];
                    $sourceList = [];
                    $sort = [SOURCE_AR_DAMAGE, SOURCE_AR_FEES, SOURCE_AR_INTEREST, SOURCE_AR_DELAYINTEREST, SOURCE_AR_PRINCIPAL];
                    $fee = 0;
                    $balance = false;
                    foreach ($transaction as $key => $value) {
                        $sourceList[$value->source] = $value;
                    }
                    foreach ($sort as $skey => $svalue) {
                        $source = $sourceList[$svalue];
                        if ($total > 0) {
                            $rs = $this->CI->transaction_model->update($source->id, [
                                'status' => 2,
                                'bank_account_from' => $lawAccount
                            ]);
                            if ($rs) {
                                $amount = intval($source->amount);
                                if ($svalue == SOURCE_AR_PRINCIPAL && $amount > $total) {
                                    $balance = $amount - $total;
                                    $amount = $total;
                                    $this->CI->transaction_model->update($source->id, [
                                        'status' => 2,
                                        'amount' => $amount
                                    ]);
                                }
                                $charge_source = $charge_source_list[$source->source];
                                $pass_book[] = $source->id;
                                $transaction_param[] = [
                                    'source' => $charge_source,
                                    'entering_date' => $date,
                                    'user_from' => $source->user_from,
                                    'bank_account_from' => $lawAccount,
                                    'amount' => $amount,
                                    'target_id' => $source->target_id,
                                    'investment_id' => $source->investment_id,
                                    'instalment_no' => $source->instalment_no,
                                    'user_to' => $source->user_to,
                                    'bank_account_to' => $source->bank_account_to,
                                    'status' => 2
                                ];
                                $total -= $amount;
                                if (!in_array($svalue, [SOURCE_AR_DAMAGE, SOURCE_AR_FEES])) {
                                    $fee += $amount;
                                    if ($source->user_from == $target->user_id && $source->investment_id != 0) {
                                        if (!isset($user_to[$source->investment_id])) {
                                            $user_to[$source->investment_id] = [
                                                'amount' => 0,
                                                'user_id' => $source->user_to,
                                            ];
                                        }
                                        $user_to[$source->investment_id]['amount'] += $amount;
                                    }
                                }

                                if ($balance) {
                                    $transaction_param[] = [
                                        'source' => SOURCE_AR_PRINCIPAL,
                                        'entering_date' => $date,
                                        'user_from' => $source->user_from,
                                        'bank_account_from' => $lawAccount,
                                        'amount' => $balance,
                                        'target_id' => $source->target_id,
                                        'investment_id' => $source->investment_id,
                                        'instalment_no' => $source->instalment_no,
                                        'user_to' => $source->user_to,
                                        'limit_date' => $source->limit_date,
                                        'bank_account_to' => $source->bank_account_to,
                                        'status' => 1
                                    ];

                                    $ar_fee = $this->CI->financial_lib->get_ar_fee($balance);
                                    $transaction_param[] = [
                                        'source' => SOURCE_AR_FEES,
                                        'entering_date' => $date,
                                        'user_from' => $source->user_from,
                                        'bank_account_from' => $source->bank_account_from,
                                        'amount' => $ar_fee,
                                        'target_id' => $source->target_id,
                                        'investment_id' => $source->investment_id,
                                        'instalment_no' => $source->instalment_no,
                                        'user_to' => $source->user_to,
                                        'limit_date' => $source->limit_date,
                                        'bank_account_to' => $source->bank_account_to,
                                        'status' => 1
                                    ];
                                }

                            }
                        }
                    }
                    if ($fee > 0) {
                        $transaction = $this->CI->transaction_model->get_by([
                            'source' => SOURCE_AR_FEES,
                            'target_id' => $target->id,
                            'user_from !=' => $target->user_id,
                            'limit_date <=' => $date,
                            'status' => 1,
                        ]);

                        $ar_fee = $this->CI->financial_lib->get_ar_fee($fee);
                        $source = $transaction;
                        $this->CI->transaction_model->update($source->id, [
                            'status' => 2,
                            'amount' => $ar_fee
                        ]);
                        $pass_book[] = $source->id;
                        $transaction_param[] = [
                            'source' => $charge_source_list[SOURCE_AR_FEES],
                            'entering_date' => $date,
                            'user_from' => $source->user_from,
                            'bank_account_from' => $source->bank_account_from,
                            'amount' => $ar_fee,
                            'target_id' => $source->target_id,
                            'investment_id' => $source->investment_id,
                            'instalment_no' => $source->instalment_no,
                            'user_to' => $source->user_to,
                            'limit_date' => $source->limit_date,
                            'bank_account_to' => $source->bank_account_to,
                            'status' => 2
                        ];
                    }
                    if ($transaction_param) {
                        $rs = $this->CI->transaction_model->insert_many($transaction_param);
                        if ($rs) {
                            foreach ($rs as $key => $value) {
                                $this->CI->passbook_lib->enter_account($value);
                            }

                            foreach ($pass_book as $key => $value) {
                                $this->CI->passbook_lib->enter_account($value);
                            }

                            $this->CI->load->library('Notification_lib');
                            $this->CI->notification_lib->repay_success($target->user_id, 0, $target->target_no, $amount);
                            foreach ($user_to as $investment => $user_to_info) {
                                $user_to_info['user_id'] != 0 ? $this->CI->notification_lib->repay_success($user_to_info['user_id'], 1, $target->target_no, $user_to_info['amount']) : '';
                            }

                            if ($target->delay && $balance == 0) {
                                $update_data = [
                                    'delay' => 0,
                                    'delay_days' => 0
                                ];

                                $this->CI->load->library('Target_lib');
                                $this->CI->target_lib->insert_change_log($target->id, $update_data, 0, 0);
                                $this->CI->target_model->update($target->id, $update_data);
                                $this->check_finish($target);
                            }
                        }
                    }
                    $this->CI->virtual_account_model->update($virtual_account->id, array('status' => 1));
                    return true;
                }
            }
            return false;
        }
        else{
            $transaction 	= $this->CI->transaction_model->get_many_by([
                'target_id'		=> $target->id,
                'limit_date <=' => $date,
                'status'		=> 1,
            ]);
            if($transaction) {
                foreach ($transaction as $key => $value) {
                    if (in_array($value->source, $source_list) && $value->user_from == $target->user_id) {
                        $amount += $value->amount;
                        if (!isset($user_to[$value->investment_id])) {
                            $user_to[$value->investment_id] = [
                                'amount' => 0,
                                'recovery_amount' => 0,
                                'user_id' => $value->user_to,
                            ];
                        }
                        $user_to[$value->investment_id]['amount'] += $value->amount;

                        // 投資人回款總金額
                        if(in_array($value->source, getPlatformFeeRelatedARSourceList())) {
                            $user_to[$value->investment_id]['recovery_amount'] += $value->amount;
                        }
                    }
                }
                if ($amount > 0) {
                    $virtual = $target->product_id != PRODUCT_FOREX_CAR_VEHICLE ? CATHAY_VIRTUAL_CODE : TAISHIN_VIRTUAL_CODE;
//                    $virtual_account = $this->CI->virtual_account_model->setVirtualAccount($target->user_id, USER_BORROWER,
//                        VIRTUAL_ACCOUNT_STATUS_AVAILABLE, VIRTUAL_ACCOUNT_STATUS_USING, $virtual);
                    $virtual_account = $this->CI->virtual_account_model->get_by([
                        'status' => VIRTUAL_ACCOUNT_STATUS_AVAILABLE,
                        'investor' => USER_BORROWER,
                        'user_id' => $target->user_id,
                        'virtual_account like ' => $virtual . "%",
                    ]);

                    if (!empty($virtual_account)) {
                        $this->CI->virtual_account_model->update($virtual_account->id, ['status' => VIRTUAL_ACCOUNT_STATUS_USING]);
                        $funds = $this->CI->transaction_lib->get_virtual_funds($virtual_account->virtual_account);
                        $total = $funds['total'] - $funds['frozen'];
                        if ($total >= $amount) {
                            $transaction_param = [];
                            $pass_book = [];
                            foreach ($transaction as $key => $value) {
                                if($value->source == SOURCE_AR_FEES) {
                                    // 由於增加餘額部分清償會有精度問題，需重新計算回款手續費
                                    $ar_fee = $this->CI->financial_lib->get_ar_fee($user_to[$value->investment_id]['recovery_amount']);
                                    $rs = $this->CI->transaction_model->update($value->id,
                                        ['amount' => $ar_fee,
                                            'status' => TRANSACTION_STATUS_PAID_OFF]);
                                    $value->amount = $ar_fee;
                                }else {
                                    $rs = $this->CI->transaction_model->update($value->id, ['status' => TRANSACTION_STATUS_PAID_OFF]);
                                }

                                if ($rs) {
                                    $charge_source = $charge_source_list[$value->source];
                                    $pass_book[] = $value->id;
                                    $transaction_param[] = [
                                        'source' => $charge_source,
                                        'entering_date' => $date,
                                        'user_from' => $value->user_from,
                                        'bank_account_from' => $value->bank_account_from,
                                        'amount' => intval($value->amount),
                                        'target_id' => $value->target_id,
                                        'investment_id' => $value->investment_id,
                                        'instalment_no' => $value->instalment_no,
                                        'user_to' => $value->user_to,
                                        'bank_account_to' => $value->bank_account_to,
                                        'status' => 2
                                    ];
                                }
                            }
                            if ($transaction_param) {
                                $rs = $this->CI->transaction_model->insert_many($transaction_param);
                                if ($rs) {
                                    foreach ($rs as $key => $value) {
                                        $this->CI->passbook_lib->enter_account($value);
                                    }

                                    foreach ($pass_book as $key => $value) {
                                        $this->CI->passbook_lib->enter_account($value);
                                    }

                                    if ($target->delay) {
                                        $update_data = [
                                            'delay' => 0,
                                            'delay_days' => 0
                                        ];

                                        $this->CI->load->library('Target_lib');
                                        $this->CI->target_lib->insert_change_log($target->id, $update_data, 0, 0);
                                        $this->CI->target_model->update($target->id, $update_data);
                                    }

                                    $this->CI->load->library('Notification_lib');
                                    $this->CI->notification_lib->repay_success($target->user_id, 0, $target->target_no, $amount);
                                    foreach ($user_to as $investment => $user_to_info) {
                                        $user_to_info['user_id'] != 0 ? $this->CI->notification_lib->repay_success($user_to_info['user_id'], 1, $target->target_no, $user_to_info['amount']) : '';
                                    }
                                }
                            }
                            $this->check_finish($target);
                        } else {
                            $this->notice_delay_target($target);
                        }

                        $this->CI->virtual_account_model->update($virtual_account->id, array('status' => 1));
                        return true;
                    }
                }
            }
            return false;
        }
	}

	public function charge_prepayment_target($target,$prepayment){
		if($target->status == 5 && $prepayment){
			$settlement_date = $prepayment->settlement_date;
			$date 			 = get_entering_date();
            $get_data 	= $this->CI->transaction_model->order_by('limit_date','asc')->get_by([
                'target_id' => $target->id,
                'source' => SOURCE_AR_PRINCIPAL,
                'status'	=> 1
            ]);
			$virtual_account = $this->CI->virtual_account_model->get_by([
				'status'	=> 1,
				'investor'	=> 0,
				'user_id'	=> $target->user_id,
				'virtual_account'	=> $get_data->bank_account_from
			]);
			if($virtual_account){
				$this->CI->virtual_account_model->update($virtual_account->id,['status'=>2]);
				$funds = $this->CI->transaction_lib->get_virtual_funds($virtual_account->virtual_account);
				$total = $funds['total'] - $funds['frozen'];
				if($total >= $prepayment->amount){
					$transaction 	= $this->CI->transaction_model->order_by('limit_date','asc')->get_many_by([
						'target_id' => $target->id,
						'status'	=> [1,2]
					]);
					if($transaction){
                        $this->CI->load->library('Notification_lib');
						$last_settlement_date 	= $target->loan_date;
						$user_to_info 			= [];
						$transaction_param 		= [];
						$instalment_paid		= 0;
						$liquidated_damages		= 0;
						$total_remaining_principal	= 0;
						$instalment				= 1;
						$next_instalment 		= true;//下期
						foreach($transaction as $key => $value){
							if($value->source==SOURCE_AR_PRINCIPAL){
								$user_to_info[$value->investment_id] 	= [
									'user_to'					=> $value->user_to,
									'bank_account_to'			=> $value->bank_account_to,
									'investment_id'				=> $value->investment_id,
									'total_amount'				=> 0,
									'remaining_principal'		=> 0,
									'interest_payable'			=> 0,
									'platform_fee'				=> 0,
								];
							}
                            if($value->status==2 && $value->source==SOURCE_PRINCIPAL){
                                $instalment_paid 		= $value->instalment_no;
                                //$last_settlement_date 	= $value->limit_date;
                            }

                            if($value->status==2 && $value->source==SOURCE_AR_PRINCIPAL){
                                $last_settlement_date 	= $value->limit_date;
                            }
						}

						$instalment = $instalment_paid + 1;
						foreach($transaction as $key => $value){
							if($value->status==1){
								switch($value->source){
									case SOURCE_AR_PRINCIPAL:
										$user_to_info[$value->investment_id]['remaining_principal']	+= $value->amount;
										$total_remaining_principal += $value->amount;
										break;
									case SOURCE_AR_FEES:
										if($value->limit_date <= $settlement_date){
											$user_to_info[$value->investment_id]['platform_fee']	+= $value->amount;
										}else if($value->limit_date > $settlement_date && $value->instalment_no==$instalment){
											$user_to_info[$value->investment_id]['platform_fee']	+= $value->amount;
										}
										break;
									case SOURCE_AR_INTEREST:
										if($value->limit_date <= $settlement_date){
											$last_settlement_date = $value->limit_date;
											$user_to_info[$value->investment_id]['interest_payable'] += $value->amount;
										}
										break;
									default:
										break;
								}
								$this->CI->transaction_model->update($value->id,['status'=>0]);
							}
						}

						if($user_to_info){
							$days  		= get_range_days($last_settlement_date,$settlement_date);
							$leap_year 	= $this->CI->financial_lib->leap_year($target->loan_date,$target->instalment);
							$year_days 	= $leap_year?366:365;//今年日數
                            $msg = [];
							if($days){
								foreach($user_to_info as $investment_id => $value){
									$user_to_info[$investment_id]['interest_payable'] = $this->CI->financial_lib->get_interest_by_days($days,$value['remaining_principal'],$target->instalment,$target->interest_rate,$target->loan_date);
								}
							}
							$liquidated_damages = $prepayment->damage;

							$project_source = [
								'interest_payable'			=> [SOURCE_AR_INTEREST,SOURCE_INTEREST],
								'remaining_principal'		=> [SOURCE_AR_PRINCIPAL,SOURCE_PRINCIPAL],
							];
							foreach($user_to_info as $investment_id => $value){

								foreach($project_source as $k => $v){
									$amount = $value[$k];
									if(intval($amount)>0){
										$transaction_param[] = [
											'source'			=> $v[0],
											'entering_date'		=> $date,
											'user_from'			=> $target->user_id,
											'bank_account_from'	=> $virtual_account->virtual_account,
											'amount'			=> $amount,
											'target_id'			=> $target->id,
											'investment_id'		=> $user_to_info[$investment_id]['investment_id'],
											'instalment_no'		=> $instalment,
											'user_to'			=> $user_to_info[$investment_id]['user_to'],
											'limit_date'		=> $settlement_date,
											'bank_account_to'	=> $user_to_info[$investment_id]['bank_account_to'],
											'status'			=> 2
										];
										$transaction_param[] = [
											'source'			=> $v[1],
											'entering_date'		=> $date,
											'user_from'			=> $target->user_id,
											'bank_account_from'	=> $virtual_account->virtual_account,
											'amount'			=> $amount,
											'target_id'			=> $target->id,
											'investment_id'		=> $user_to_info[$investment_id]['investment_id'],
											'instalment_no'		=> $instalment,
											'user_to'			=> $user_to_info[$investment_id]['user_to'],
											'bank_account_to'	=> $user_to_info[$investment_id]['bank_account_to'],
											'status'			=> 2
										];
										$value['total_amount'] += $amount;
									}
								}

								$prepayment_allowance = 0;
								if($value['total_amount']>0){
									//回款手續費
									$transaction_param[] = [
										'source'			=> SOURCE_FEES,
										'entering_date'		=> $date,
										'user_from'			=> $user_to_info[$investment_id]['user_to'],
										'bank_account_from'	=> $value['bank_account_to'],
										'amount'			=> $user_to_info[$investment_id]['platform_fee'],
										'target_id'			=> $target->id,
										'investment_id'		=> $value['investment_id'],
										'instalment_no'		=> $instalment,
										'user_to'			=> 0,
										'bank_account_to'	=> PLATFORM_VIRTUAL_ACCOUNT,
										'status'			=> 2
									];

                                    $has_prepayment_allowance = $this->CI->config->item('has_prepayment_allowance');
                                    if (in_array($target->product_id, $has_prepayment_allowance))
                                    {
                                        $prepayment_allowance	= intval(round($value['remaining_principal']/100*PREPAYMENT_ALLOWANCE_FEES,0));//提還補貼金
                                        $transaction_param[] = [
                                            'source'			=> SOURCE_PREPAYMENT_ALLOWANCE,
                                            'entering_date'		=> $date,
                                            'user_from'			=> 0,
                                            'bank_account_from'	=> PLATFORM_VIRTUAL_ACCOUNT,
                                            'amount'			=> $prepayment_allowance,
                                            'target_id'			=> $target->id,
                                            'investment_id'		=> $value['investment_id'],
                                            'instalment_no'		=> $instalment,
                                            'user_to'			=> $user_to_info[$investment_id]['user_to'],
                                            'bank_account_to'	=> $value['bank_account_to'],
                                            'status'			=> 2
                                        ];
                                    }
								}
                                $msg[$user_to_info[$investment_id]['user_to']] = $value['total_amount'] + (isset($prepayment_allowance) ? $prepayment_allowance : 0);
							}

							if(intval($liquidated_damages)>0){
								$transaction_param[] = [
									'source'			=> SOURCE_PREPAYMENT_DAMAGE,
									'entering_date'		=> $date,
									'user_from'			=> $target->user_id,
									'bank_account_from'	=> $virtual_account->virtual_account,
									'amount'			=> $liquidated_damages,
									'target_id'			=> $target->id,
									'investment_id'		=> 0,
									'instalment_no'		=> $instalment,
									'user_to'			=> 0,
									'limit_date'		=> $settlement_date,
									'bank_account_to'	=> PLATFORM_VIRTUAL_ACCOUNT,
									'status'			=> 2
								];
							}

							if($transaction_param){
								$rs  = $this->CI->transaction_model->insert_many($transaction_param);
								if($rs){
									foreach($rs as $key => $value){
										$this->CI->passbook_lib->enter_account($value);
									}
									foreach ($msg as $item_arr =>$item) {
										// 由於提前清償會把已結清的紀錄計算進來，實際剩餘本金!=0的才是真正債權擁有者
										if($item != 0)
											$this->CI->notification_lib->prepay_success($item_arr,1,$target->target_no,$item);
									}
								}
							}
						}
					}
                    $this->CI->notification_lib->prepay_success($target->user_id,0,$target->target_no,$total_remaining_principal);
				}else{
					$this->notice_normal_target($target);
				}

				$this->CI->virtual_account_model->update($virtual_account->id,['status'=>1]);
				$this->check_finish($target);
				return true;
			}
		}
		return false;
	}

	public function check_finish($target){
		if($target->status == 5){
			$transaction 	= $this->CI->transaction_model->get_by([
				'target_id' => $target->id,
				'status'	=> 1
			]);
			if(!$transaction){
				$this->CI->load->model('loan/investment_model');
				$this->CI->load->library('Target_lib');
				if($target->sub_status==3){
					$param = ['status'=>10,'sub_status'=>4];
				}else if($target->sub_status==1){
					$param = ['status'=>10,'sub_status'=>2];
				}else{
					$param = ['status'=>10];
				}

                $this->CI->load->library('Transfer_lib');
                $this->CI->transfer_lib->break_transfer($target->id);

				$this->CI->target_model->update($target->id,$param);
				$this->CI->target_lib->insert_change_log($target->id,$param,0,0);

				$investment 	= $this->CI->investment_model->get_many_by([
					'target_id' => $target->id,
					'status'	=> 3
				]);
				if($investment){
					foreach($investment as $key => $value){
						$this->CI->investment_model->update($value->id,['status'=>10]);
						$this->CI->target_lib->insert_investment_change_log($value->id,['status'=>10],0,0);
					}
				}
				return true;
			}
		}
		return false;
	}

	public function script_charge_targets(){
		$script  	= 6;
		$count 		= 0;
		$date		= get_entering_date();
		$ids		= array();
		$targets 	= $this->CI->target_model->get_many_by(array(
			'status'			=> 5,//還款中
			'script_status'		=> 0,
		));
		if($targets && !empty($targets)){
            foreach($targets as $key => $value){
                $ids[] = $value->id;
            }
            $update_rs 	= $this->CI->target_model->update_many($ids,array('script_status'=>$script));
            if($update_rs) {
                foreach ($targets as $key => $value) {
                    if ($value->sub_status != 3) {
                        $transaction = $this->CI->transaction_model->order_by('limit_date', 'ASC')->get_by(array(
                            'target_id' => $value->id,
                            'limit_date <=' => $date,
                            'status' => 1,
                            'user_from' => $value->user_id
                        ));
                        if ($transaction) {
                            $check = $this->charge_normal_target($value);
                            if ($check) {
                                $count++;
                            }
                        } else {
                            $this->notice_normal_target($value);
                        }
                    }
                    $this->CI->target_model->update($value->id, array('script_status' => 0));
                }
            }
		}
		return $count;
	}

	public function script_prepayment_targets(){
		$script  	= 7;
		$count 		= 0;
		$date		= get_entering_date();
		$ids		= [];
		$targets 	= $this->CI->target_model->get_many_by([
			'status'			=> 5,//還款中
			'sub_status'		=> 3,
			'script_status'		=> 0
		]);
		if($targets){
			foreach($targets as $key => $value){
				$ids[] = $value->id;
			}
			$update_rs 	= $this->CI->target_model->update_many($ids,['script_status'=>$script]);
			if($update_rs){
				$this->CI->load->library('Target_lib');
				$this->CI->load->library('Prepayment_lib');
				foreach($targets as $key => $value){
					$prepayment = $this->CI->prepayment_lib->get_prepayment($value);
					if($prepayment){
						if($date > $prepayment->settlement_date){
							$update_data = [
								'script_status'	=> 0,
								'sub_status'	=> 0
							];
							$this->CI->target_lib->insert_change_log($value->id,$update_data,0,0);
							$this->CI->target_model->update($value->id,$update_data);
							$this->CI->load->library('Notification_lib');
							$this->CI->notification_lib->prepay_failed($value->user_id,$value->target_no);
						}else{
							$this->charge_prepayment_target($value,$prepayment);
							$this->CI->target_model->update($value->id,['script_status'=>0]);
						}
						$count++;
					}
				}
			}
		}
		return $count;
	}

	public function notice_normal_target($target){
		$date			= get_entering_date();
		$next_date		= '';
		$range_days		= 0;
		if($target->handle_date < $date){
			$transaction = $this->CI->transaction_model->order_by('limit_date','ASC')->get_by(array(
				'target_id'		=> $target->id,
				'status'		=> 1,
				'user_from'		=> $target->user_id
			));
			if($transaction){
				$next_date 	= $transaction->limit_date;
				$range_days	= get_range_days($date,$next_date);
				$amount		= 0;
				if(in_array($range_days,[1,3,7])){
					$transaction = $this->CI->transaction_model->get_many_by(array(
						'target_id'		=> $target->id,
						'status'		=> 1,
						'user_from'		=> $target->user_id,
						'limit_date'	=> $next_date,
					));
					foreach($transaction as $key => $value){
						$amount += $value->amount;
					}
					if($amount){
						$this->CI->load->library('Notification_lib');
						$this->CI->load->model('user/virtual_account_model');
						$virtualAcount = $this->CI->virtual_account_model->get_by([
							'investor'	=> 0,
							'user_id'	=> $target->user_id,
						]);
						if(isset($virtualAcount)) {
							$virtualAccountBalance = $this->CI->virtual_passbook_model->get_virtual_acc_balance($virtualAcount->virtual_account);
							$difference = $virtualAccountBalance - $amount;
							if($difference < 0) {
								$this->CI->notification_lib->notice_normal_target($target->user_id, $amount, $difference, $target->target_no, $next_date);
							}
						}
						if($range_days==1){
							$this->CI->load->library('sms_lib');
							$this->CI->sms_lib->notice_normal_target($target->user_id,$amount,$target->target_no,$next_date);
						}
					}
				}
			}else{
				$this->check_finish($target);
			}
			$this->CI->target_model->update($target->id,array('handle_date'=>$date));
		}
		return false;
	}

	public function notice_delay_target($target=[]){
		$date		= get_entering_date();
		$delay_days	= 0;
		$update_data= ['handle_date' => $date];
		if($target->handle_date < $date){
			$transaction = $this->CI->transaction_model->order_by('limit_date','ASC')->get_many_by([
				'target_id'		=> $target->id,
				'status'		=> 1,
				'limit_date <=' => $date,
				'user_from'		=> $target->user_id
			]);
			if($transaction){
				$amount		= 0;
				$last_date	= '';
				foreach($transaction as $key => $value){
					$amount += $value->amount;
					if($last_date == '' || $last_date > $value->limit_date){
						$last_date = $value->limit_date;
					}
				}

				$delay_days	= get_range_days($last_date,$date);
                if( $delay_days > 0 && $amount > 0){
                    $total = 0;
                    $remaining_principal = 0;
                    $delay_interest = 0;
                    $transaction = $this->CI->transaction_model->order_by('limit_date', 'asc')->get_many_by([
                        'target_id' => $target->id,
                        'source' => 11,
                        'status' => 1
                    ]);
                    if ($transaction) {
                        foreach ($transaction as $key => $value) {
                            $remaining_principal += $value->amount;
                        }
                        $delay_interest = $this->CI->financial_lib->get_delay_interest($remaining_principal, 8);
                        $liquidated_damages = $this->CI->financial_lib->get_liquidated_damages($remaining_principal,$target->damage_rate);
                        $total = $remaining_principal + $delay_interest + $liquidated_damages;
                    }
                    $this->CI->load->library('Notification_lib');
                    $this->CI->load->library('sms_lib');
                    if (in_array($delay_days, [1, 2, 3, 4, 5])) {
                        $this->CI->notification_lib->notice_delay_target($target->user_id, $amount, $target->target_no);
                    } else if (in_array($delay_days, [6, 7])) {
                        $this->CI->notification_lib->notice_delay_target_lv2($target->user_id, $amount, $target->target_no, $total, $delay_interest);
                    }

					$update_data = [
						'delay'		  => 1,
						'delay_days'  => $delay_days,
						'handle_date' => $date
					];
					if($target->delay==0){
						$this->CI->load->library('Target_lib');
						$this->CI->target_lib->insert_change_log($target->id,$update_data,0,0);
					}
				}
			}
			$this->CI->target_model->update($target->id,$update_data);
            $gracePeriod = $target->product_id == PRODUCT_FOREX_CAR_VEHICLE ? 0 : GRACE_PERIOD;
			if ($delay_days > $gracePeriod) {
                // revert to 7df5a422de2af7fdb1e4a7063df1907c7ceacd5b, issue#800
                $this->handle_delay_target($target, $delay_days, $gracePeriod);
			}
			return true;
		}
		return false;
	}

	public function handle_delay_target($target=[],$delay_days=0,$gracePeriod){
		if($target->status == 5 && $delay_days > $gracePeriod){
			$this->CI->load->model('loan/investment_model');
			if(in_array($delay_days,[8,31,61])){
				$this->CI->load->library('credit_lib');
				$level = $this->CI->credit_lib->delay_credit($target->user_id,$delay_days);
				if($level){
					$this->CI->target_model->update($target->id,['credit_level'=>$level]);
				}
			}

			$date			= get_entering_date();
			$transaction 	= $this->CI->transaction_model->order_by('limit_date','asc')->get_many_by([
				'target_id' => $target->id,
				'status'	=> TRANSACTION_STATUS_TO_BE_PAID
			]);
			if($transaction){
                $this->CI->load->model('loan/contract_model');
                $contract = $this->CI->contract_model->get($target->contract_id);
				$settlement 			= true;
				$user_to_info 			= [];
				$transaction_param 		= [];
				$bank_account_from		= '';
				$liquidated_damages		= 0;
				$instalment				= 1;
				$limit_date				= '';
				foreach($transaction as $key => $value){
                    if(!isset($user_to_info[$value->investment_id])) {
                        $user_to_info[$value->investment_id] 	= [
                            'user_to'				=> 0,
                            'bank_account_to'		=> 0,
                            'investment_id'			=> 0,
                            'remaining_principal'	=> 0,
                            'delay_interest'		=> 0,
                            'ar_delay_interest_existed'		=> false,
                            'ar_interest'		    => 0,
                            'platform_fee'          => 0,
                            'ar_platform_fee_existed'		=> false,
                        ];
                    }
                    if($value->source==SOURCE_AR_PRINCIPAL){
                        $user_to_info[$value->investment_id]['user_to'] = $value->user_to;
                        $user_to_info[$value->investment_id]['bank_account_to'] = $value->bank_account_to;
                        $user_to_info[$value->investment_id]['investment_id'] = $value->investment_id;

						$bank_account_from 	= $value->bank_account_from;
						$user_to_info[$value->investment_id]['remaining_principal']	+= $value->amount;
						if($value->limit_date < $date){
							$instalment = $value->instalment_no;
							$limit_date = $value->limit_date;
						}
					}else if($value->limit_date < $date && $value->source==SOURCE_AR_INTEREST){
                        $user_to_info[$value->investment_id]['ar_interest']	= $value->amount;
					}else if($value->source==SOURCE_AR_DELAYINTEREST){
                        $user_to_info[$value->investment_id]['delay_interest'] += $value->amount;
                        $user_to_info[$value->investment_id]['ar_delay_interest_existed'] = true;
					}else if($value->source==SOURCE_AR_FEES){
                        $user_to_info[$value->investment_id]['platform_fee'] += $value->amount;
                        $user_to_info[$value->investment_id]['ar_platform_fee_existed'] = true;
                    }
				}

				// 第一次逾期時需結算並產生違約金紀錄，故若有違約金紀錄，不應再結算一次
                $damage_transaction 	= $this->CI->transaction_model->order_by('limit_date','asc')->get_many_by([
                    'source'    => SOURCE_AR_DAMAGE,
                    'target_id' => $target->id,
                    'status'	=> [TRANSACTION_STATUS_TO_BE_PAID, TRANSACTION_STATUS_PAID_OFF]
                ]);
				if(!empty($damage_transaction))
                    $settlement = false;

				// 第一次逾期時，需統整本息等項目成每一科目一筆金額及增加違約金等
				if($settlement) {
					foreach($transaction as $key => $value){
						if($value->limit_date > $date || $value->source==SOURCE_AR_PRINCIPAL){
							$this->CI->transaction_model->update($value->id,array('status'=>0));
						}
					}

					if($user_to_info){
						$total_remaining_principal = 0;
                        foreach($user_to_info as $investment_id => $value){
                            if ($contract->format_id == 3) {
                                $user_to_info[$investment_id]['delay_interest'] = $this->CI->financial_lib->get_interest_by_days($delay_days, $value['remaining_principal'], $instalment, 20, $limit_date);
                            } else {
                                $user_to_info[$investment_id]['delay_interest'] = $this->CI->financial_lib->get_delay_interest($value['remaining_principal'], $delay_days);
                            }
							$total_remaining_principal 	+= $value['remaining_principal'];
						}

						$liquidated_damages = $this->CI->financial_lib->get_liquidated_damages($total_remaining_principal,$target->damage_rate);

						foreach($user_to_info as $investment_id => $value){
							$transaction_param[] = [
								'source'			=> SOURCE_AR_PRINCIPAL,
								'entering_date'		=> $date,
								'user_from'			=> $target->user_id,
								'bank_account_from'	=> $bank_account_from,
								'amount'			=> $value['remaining_principal'],
								'target_id'			=> $target->id,
								'investment_id'		=> $value['investment_id'],
								'instalment_no'		=> $instalment,
								'user_to'			=> $value['user_to'],
								'limit_date'		=> $limit_date,
								'bank_account_to'	=> $value['bank_account_to'],
								'status'			=> TRANSACTION_STATUS_TO_BE_PAID
							];

							$transaction_param[] = [
								'source'			=> SOURCE_AR_DELAYINTEREST,
								'entering_date'		=> $date,
								'user_from'			=> $target->user_id,
								'bank_account_from'	=> $bank_account_from,
								'amount'			=> $value['delay_interest'],
								'target_id'			=> $target->id,
								'investment_id'		=> $value['investment_id'],
								'instalment_no'		=> $instalment,
								'user_to'			=> $value['user_to'],
								'limit_date'		=> $limit_date,
								'bank_account_to'	=> $value['bank_account_to'],
								'status'			=> TRANSACTION_STATUS_TO_BE_PAID
							];

                            $total = $value['remaining_principal'] + $value['ar_interest'] + $value['delay_interest'];
                            $ar_fee = $this->CI->financial_lib->get_ar_fee($total);
                            $this->CI->transaction_model->update_by(
                                [
                                    'source' 		=> SOURCE_AR_FEES,
                                    'investment_id' => $value['investment_id'],
                                    'status'        => TRANSACTION_STATUS_TO_BE_PAID
                                ],
                                [
                                    'amount' => $ar_fee
                                ]
                            );
						}

						if(intval($liquidated_damages)>0){
							$transaction_param[] = [
								'source'			=> SOURCE_AR_DAMAGE,
								'entering_date'		=> $date,
								'user_from'			=> $target->user_id,
								'bank_account_from'	=> $bank_account_from,
								'amount'			=> $liquidated_damages,
								'target_id'			=> $target->id,
								'investment_id'		=> 0,
								'instalment_no'		=> $instalment,
								'user_to'			=> 0,
								'limit_date'		=> $limit_date,
								'bank_account_to'	=> PLATFORM_VIRTUAL_ACCOUNT,
								'status'			=> TRANSACTION_STATUS_TO_BE_PAID
							];
						}

						if($transaction_param){
							$rs  = $this->CI->transaction_model->insert_many($transaction_param);
						}
					}
				}else{
					if($user_to_info){
						foreach($user_to_info as $investment_id => $value) {
                            $delay_interest = 0;

                            // 對象是平台不處理延滯息及投資人的回款平台服務費
                            if($investment_id == 0)
                                continue;

                            if ($contract->format_id == 3) {
                                // 消費貸不能做部分清償，所以依舊使用原先方式計算
                                $delay_interest = $this->CI->financial_lib->get_interest_by_days($delay_days, $value['remaining_principal'], $instalment, 20, $limit_date);
                            } else {
								$delay_days	= get_range_days($target->handle_date, $date);

								// 若有未正常結算延滯息的情況，依照每日撈出已結清本金做該天延滯息的計算
								$paid_principal_transaction_list 	= $this->CI->transaction_model->order_by('entering_date','desc')->get_many_by([
									'source'	=> SOURCE_PRINCIPAL,
									'target_id' => $target->id,
									'investment_id' => $investment_id,
									'entering_date >' => $target->handle_date,
									'status'	=> 2
								]);
								$unsettlement_delay_interest = 0;
								for($i=1; $i<=$delay_days; $i++) {
									$over_handle_date_paid_amount = 0;
									foreach ($paid_principal_transaction_list as $paid_principal_transaction) {
										if(date("Y-m-d",strtotime($paid_principal_transaction->entering_date)) >= date("Y-m-d",strtotime($target->handle_date.' +'.$i.' day'))) {
											$over_handle_date_paid_amount += $paid_principal_transaction->amount;
										}else{
											// 交易依照建立時間遞減排序，低於處理日後就可以直接跳離
											break;
										}
									}
									// 未結算的延滯息
                                    $unsettlement_delay_interest += intval(round(($value['remaining_principal']+$over_handle_date_paid_amount)*DELAY_INTEREST*1/100,0));
								}

								$delay_interest = $value['delay_interest'] + $unsettlement_delay_interest;
                            }

                            $ar_fee = $value['platform_fee'] + $this->CI->financial_lib->get_ar_fee($delay_interest-$value['delay_interest']);

                            if($value['ar_delay_interest_existed']) {
                                // 已有延滯息科目，對現有科目更新
                                $this->CI->transaction_model->update_by(
                                    [
                                        'source' 		=> SOURCE_AR_DELAYINTEREST,
                                        'investment_id' => $value['investment_id'],
                                        'status' => TRANSACTION_STATUS_TO_BE_PAID,
                                    ],
                                    [
                                        'amount' => $delay_interest
                                    ]
                                );
                            } else {
                                // 無延滯息科目，新增
                                $this->CI->transaction_model->insert([
                                    'source' => SOURCE_AR_DELAYINTEREST,
                                    'entering_date' => $date,
                                    'user_from' => $target->user_id,
                                    'bank_account_from' => $bank_account_from,
                                    'amount' => $delay_interest,
                                    'target_id' => $target->id,
                                    'investment_id' => $value['investment_id'],
                                    'instalment_no' => $instalment,
                                    'user_to' => $value['user_to'],
                                    'limit_date' => $limit_date,
                                    'bank_account_to' => $value['bank_account_to'],
                                    'status' => TRANSACTION_STATUS_TO_BE_PAID
                                ]);
                            }

                            if($value['ar_platform_fee_existed']) {
                                // 已有平台應收服務費科目，對現有交易更新
                                $this->CI->transaction_model->update_by(
                                    [
                                        'source' 		=> SOURCE_AR_FEES,
                                        'investment_id' => $value['investment_id'],
                                        'status' => TRANSACTION_STATUS_TO_BE_PAID,
                                    ],
                                    [
                                        'amount' => $ar_fee
                                    ]
                                );
                            }else {
                                // 無平台應收服務費科目，新增交易
                                $this->CI->transaction_model->insert([
                                    'source' => SOURCE_AR_FEES,
                                    'entering_date' => $date,
                                    'user_from' => $value['user_to'],
                                    'bank_account_from' => $value['bank_account_to'],
                                    'amount' => $ar_fee,
                                    'target_id' => $target->id,
                                    'investment_id' => $value['investment_id'],
                                    'instalment_no' => $instalment,
                                    'user_to' => 0,
                                    'limit_date' => $limit_date,
                                    'bank_account_to' => PLATFORM_VIRTUAL_ACCOUNT,
                                    'status' => TRANSACTION_STATUS_TO_BE_PAID
                                ]);
                            }
						}
					}
				}
				return true;
			}
		}
		return false;
	}

    public function script_charge_delayed_targets_partial_fee() {
        $script  	= 16;
        $count 		= 0;

        $result =$this->CI->virtual_account_model->getDelayedVirtualAccountList(USER_BORROWER);
        $virtualAccountList = array_reduce($result, function ($list, $item) {
            $list[$item['virtual_account']] = $item;
            return $list;
        }, []);

        if(!empty($virtualAccountList)) {
            $funds = $this->CI->virtual_passbook_model->getVirtualAccFunds(array_keys($virtualAccountList));

            foreach ($funds as $fund) {
                if($fund['balance'] <= 0)
                    continue;

                if($this->charge_delayed_target($virtualAccountList[$fund['virtual_account']]['user_id']))
                    $count++;
            }
        }
        return $count;
    }

    // // 判斷當前時刻，是否為每月17號晚上23點50分到18號0點10分之間
    // public function checkExcludePeriodTime($deafult_date = null)
    // {
    //     $isExcludeTime = false;

    //     // UTC+8
    //     $datetime_string = date("Y-m-d H:i:s", time() + 8 * 60 * 60);
    //     if ($deafult_date) {
    //         $datetime_string = $deafult_date;
    //     }

    //     $spilt_array = explode(" ", $datetime_string);
    //     $date_string = $spilt_array[0];
    //     $time_string = $spilt_array[1];

    //     $date_array = explode("-", $date_string);
    //     $day = $date_array[2];
    //     $time_array = explode(":", $time_string);
    //     $hour = $time_array[0];
    //     $minute = $time_array[1];

    //     // yyyy-dd-17 23:50:00 ~ yyyy-dd-17 23:59:59
    //     $repqyment_date = sprintf("%s-%s-%02d", $date_array[0], $date_array[1], REPAYMENT_DAY);
    //     $grace_date_string = sprintf("%s + %d days", $repqyment_date, GRACE_PERIOD);
    //     $last_grace_date = date('Y-m-d', strtotime($grace_date_string));
    //     $last_grace_date_array = explode("-", $last_grace_date);
    //     $last_day_1 = $last_grace_date_array[2];
    //     if ($day == $last_day_1 && $hour == 23 && $minute >= 50) {
    //         $isExcludeTime = true;
    //     }

    //     $last_day_1_date = sprintf("%s-%s-%02d", $date_array[0], $date_array[1], $last_day_1);
    //     $new_last_day_2_string = date('Y-m-d', strtotime($last_day_1_date . ' + 1 days'));
    //     $new_last_day_2_array = explode("-", $new_last_day_2_string);
    //     $last_day_2 = $new_last_day_2_array[2];
    //     // yyyy-dd-18 00:00:00 ~ yyyy-dd-18 00:09:59
    //     if ($day == $last_day_2 && $hour == 0 && $minute <= 9) {
    //         $isExcludeTime = true;
    //     }

    //     return $isExcludeTime;
    // }
}

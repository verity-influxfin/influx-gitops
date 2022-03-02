<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use EdmEvent\EdmEventFactory;

class User_lib {

    /**
     * @var int
     */
    private $totalCount;
    public $rewardCategories = [
        'student' => [1, 2],
        'salary_man' => [3, 4]
    ];
    public $logRewardColumns = [
        'student', 'salary_man', 'fullMember'
    ];

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('log/log_userlogin_model');
        $this->CI->load->model('user/user_model');
    }

    //登入失敗鎖定帳號
    public function auto_block_user($account,$investor,$user_id,$device_id)
    {
        $temp_lock = '000';
        $system_lock = '0000000000';
        $check_log = '';
        $check_logs = $this->CI->log_userlogin_model->order_by('created_at', 'desc')->limit(10)->get_many_by(array(
                'user_id'	  => $user_id,
                'status <'	  => 2,
                'created_at >'=> strtotime('-30 minutes')
        ));
        foreach ($check_logs as $field) {
            $check_log .= $field->status;
        }

        if (mb_substr($check_log, 0, 3) === $temp_lock) {
            if ($check_log != $system_lock) {
                $block_status = 2;
            } else {
                $block_status = 3;
            }
            $this->CI->user_model->update($user_id, array(
                'block_status' => $block_status)
            );
            $this->CI->agent->device_id=$device_id;
            $this->CI->log_userlogin_model->insert(array(
                'account'	=> $account,
                'investor'	=> $investor,
                'user_id'	=> $user_id,
                'status'	=> $block_status
            ));
            $remind_count = 0;
        }
        else{
            $remind_count = mb_substr($check_log, 0, 2) === '00'?1:(mb_substr($check_log, 0, 1) === '0'?2:3);
        }

        return $remind_count;
    }

    public function unblock_user($user_id)
    {
        $this->CI->load->model('log/log_userlogin_model');
        $check_logs = $this->CI->log_userlogin_model->order_by('created_at', 'desc')->limit(10)->get_many_by(array(
            'user_id' => $user_id,
            'status <' => 2,
            'created_at >' => strtotime('-30 minutes')
        ));
        if (!$check_logs) {
            $this->CI->user_model->update_by(array(
                'id' => $user_id,
                'block_status' => 2,
            ), array(
                'block_status' => 0
            ));
            return true;
        }
    }

    public function script_trigger_edm_event() {
        $this->CI->load->model('user/edm_event_model');
        $this->CI->load->model('user/edm_event_log_model');
        $events = $this->CI->edm_event_model->getCanTriggerEvent();
        $this->totalCount = 0;

        foreach ($events as $event) {
            $edmEvent = EdmEventFactory::getInstance($event);
            if(isset($edmEvent)) {
                $this->CI->edm_event_model->update_by(['id' => $event['id']], ['triggered_at' => date('Y-m-d H:i:s')]);
                $this->totalCount += $edmEvent->send();
            }
        }

        return $this->totalCount;
    }

    /**
     * 取得推薦碼
     * @param int $length       : 推薦碼長度
     * @param string $prefix    : 前綴詞
     * @return string
     */
    public function get_promote_code(int $length, string $prefix = ''): string
    {
        $code = $prefix.make_promote_code($length);
        $result = $this->CI->user_model->get_by('my_promote_code',$code);
        if ($result) {
            return $this->get_promote_code($length, $prefix);
        }else{
            return $code;
        }
    }

    /**
     * 取得產品對應之推薦金額
     * @param $productSettings
     * @param $productIdList
     * @return int|mixed
     */
    public function getRewardAmountByProduct($productSettings, $productIdList) {
        foreach ($productSettings as $setting) {
            if(isset($setting['product_id']) && $setting['product_id'] == $productIdList) {
                return $setting['amount'];
            }
        }
        return 0;
    }

    /**
     * 取得推薦碼獎勵及相關資訊
     * @param array $where
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getPromotedRewardInfo(array $where, string $startDate='', string $endDate=''): array
    {
        $this->CI->load->model('user/user_qrcode_model');
        $categoryInitList = array_combine(array_keys($this->rewardCategories), array_fill(0,count($this->rewardCategories),[]));
        $list = [];
        $promoteCodeList = [];

        if(!isset($where['status']))
            $where['status'] = [PROMOTE_STATUS_AVAILABLE];

        // 取得推薦碼資料
        $promoteCodesRs = $this->CI->user_qrcode_model->getUserQrcodeInfo([], $where);
        foreach ($promoteCodesRs as $promoteCodeRs) {
            if(!isset($list[$promoteCodeRs['promote_code']])) {
                $code = $promoteCodeRs['promote_code'];
                $list[$code] = $categoryInitList;
                $list[$code]['fullMemberCount'] = 0;
                $list[$code]['fullMember'] = [];
                $list[$code]['registeredCount'] = 0;
                $list[$code]['registered'] = [];
                $promoteCodeList[$code] = $promoteCodeRs;
            }
        }

        // 取得推薦碼下載數
        $this->CI->load->model('behavion/user_behavior_model');
        $firstOpenRs =$this->CI->user_behavior_model->getFirstOpenCountByPromoteCode(array_keys($promoteCodeList), $startDate, $endDate);
        foreach ($firstOpenRs as $rs) {
            $list[$rs['promote_code']]['downloadedCount'] = $rs['count'];
        }

        // 取得推薦之註冊會員數
        $registeredRs = $this->CI->user_qrcode_model->getRegisteredUserByPromoteCode($where, $startDate, $endDate);
        foreach ($registeredRs as $rs) {
            if($rs['app_status'] == 1) {
                $list[$rs['promote_code']]['fullMemberCount'] += 1;
                $list[$rs['promote_code']]['fullMember'][] = $rs;
            }
            $list[$rs['promote_code']]['registeredCount'] += 1;
            $list[$rs['promote_code']]['registered'][] = $rs;
        }

        // 取得成功推薦申貸的數量
        foreach ($this->rewardCategories as $category => $productIdList) {
            $rs = $this->CI->user_qrcode_model->getLoanedCount($where, $productIdList, $startDate, $endDate);
            foreach ($rs as $promotedTarget) {
                $list[$promotedTarget['promote_code']][$category][] = $promotedTarget;
            }
        }

        foreach ($promoteCodeList as $value) {
            $promoteCode = $value['promote_code'];

            $list[$promoteCode]['info'] = json_decode(json_encode($value), true);
            $list[$promoteCode]['totalRewardAmount'] = 0;
            $list[$promoteCode]['totalLoanedAmount'] = 0;

            $list[$promoteCode]['info']['settings'] = json_decode($list[$promoteCode]['info']['settings'], true);
            $settings = $list[$promoteCode]['info']['settings'];
            foreach ($this->rewardCategories as $category => $productIdList) {
                $rewardAmount = 0;
                if(isset($settings['reward']) && isset($settings['reward']['product']))
                    $rewardAmount = $this->getRewardAmountByProduct($settings['reward']['product'], $productIdList);
                $list[$promoteCode]['loanedCount'][$category] = count($list[$promoteCode][$category]);
                $list[$promoteCode]['loanedBalance'][$category] = array_sum(array_column($list[$promoteCode][$category], 'loan_amount'));
                $list[$promoteCode]['rewardAmount'][$category] = $list[$promoteCode]['loanedCount'][$category] * intval($rewardAmount);
                $list[$promoteCode]['totalRewardAmount'] += $list[$promoteCode]['rewardAmount'][$category];
                $list[$promoteCode]['totalLoanedAmount'] += $list[$promoteCode]['loanedBalance'][$category];
            }

            if(!isset($list[$promoteCode]['fullMemberCount']))
                $list[$promoteCode]['fullMemberCount'] = 0;

            if(!isset($list[$promoteCode]['downloadedCount']))
                $list[$promoteCode]['downloadedCount'] = 0;


            if(isset($settings['reward']) && isset($settings['reward']['full_member'])) {
                $list[$promoteCode]['fullMemberRewardAmount'] = $list[$promoteCode]['fullMemberCount'] * intval($settings['reward']['full_member']['amount']);
                $list[$promoteCode]['totalRewardAmount'] += $list[$promoteCode]['fullMemberRewardAmount'];
            }
        }

        return $list;
    }

    /**
     * 取得虛擬帳號前綴帳號
     * @param int $investor
     * @param int $product_id
     * @return string
     */
    public function getVirtualAccountPrefix(int $investor, int $product_id = 0): string
    {
        switch ($investor)
        {
            case USER_BORROWER:
                return CATHAY_VIRTUAL_CODE . BORROWER_VIRTUAL_CODE;
                break;
            case USER_INVESTOR:
                return CATHAY_VIRTUAL_CODE . INVESTOR_VIRTUAL_CODE;
                break;
        }
    }

    /**
     * 結算所有推薦碼的獎勵
     * @return int
     */
    public function scriptHandlePromoteReward(): int
    {
        $count = 0;
        $this->CI->load->model('user/user_qrcode_model');
        $startTime = date('Y-m-01 00:00:00', strtotime("-1 month"));
        $endTime = date('Y-m-01 00:00:00');
        $userQrcodes = $this->CI->user_qrcode_model->getQrcodeRewardInfo(['status' => [PROMOTE_STATUS_AVAILABLE],
            'settlementing' => 0]);
        foreach ($userQrcodes as $qrcode) {
            if(isset($qrcode['end_time']))
                $_startTime = max($startTime, $qrcode['end_time']);
            else
                $_startTime = $startTime;

            if($_startTime < $endTime && (!isset($qrcode['handle_time']) || $qrcode['handle_time'] < $endTime)) {
                if($this->handlePromoteReward($qrcode, $_startTime, $endTime))
                    $count++;
            }
        }
        return $count;
    }

    /**
     * 結算推薦碼獎勵
     * @param $qrcode
     * @param string $startTime
     * @param string $endTime
     * @return bool
     */
    public function handlePromoteReward($qrcode, string $startTime='', string $endTime=''): bool
    {
        $promoteCode = $this->CI->user_qrcode_model->setUserPromoteLock($qrcode['promote_code'], 0, 1);
        if (!empty($promoteCode) &&
            (!isset($promoteCode->handle_time) || $promoteCode->handle_time < $endTime)) {
            $this->CI->load->model('transaction/qrcode_reward_model');

            $today = date("Y-m-d H:i:s");
            $rollback = function () {
                $this->CI->user_qrcode_model->trans_rollback();
                $this->CI->qrcode_reward_model->trans_rollback();
            };

            $this->CI->user_qrcode_model->trans_begin();
            $this->CI->qrcode_reward_model->trans_begin();
            $info = $this->getPromotedRewardInfo(['id' => $qrcode['id']], $startTime, $endTime);

            try {
                if(empty($info))
                    throw New Exception("The promote code ".$qrcode['promote_code'] ." is not found.");
                $info = reset($info);
                $descRewardList = $this->CI->qrcode_reward_model->order_by('end_time', 'DESC')->get_many_by(['user_qrcode_id' => $info['info']['id'], 'status' => [
                    PROMOTE_REWARD_STATUS_TO_BE_PAID, PROMOTE_REWARD_STATUS_PAID_OFF
                ]]);

                // 整理所有案件資訊
                $rewardList = array_intersect_key($info, $this->rewardCategories);
                $closedDelayedTargetList = [];
                $currentDelayedTargets = [];
                $dockAmountList = [];
                $remainingDockAmount = 0;

                // 最後一期的剩餘扣除金額需加回
                $tmpReward = reset($descRewardList);
                if($tmpReward) {
                    $data = json_decode($tmpReward->json_data, TRUE);
                    $remainingDockAmount += $data['remainingDockAmount'] ?? 0;
                }

                // 將之前的獎勵案列表 及 逾期案列表 合併
                foreach ($descRewardList as $value) {
                    $data = json_decode($value->json_data, TRUE);
                    if($data) {
                        $rewardList = array_merge_recursive($rewardList, array_intersect_key($data, $this->rewardCategories));
                        $closedDelayedTargetList = array_merge_recursive($closedDelayedTargetList, array_intersect_key($data['delayed_targets'] ?? [], $this->rewardCategories));
                    }
                }

                // 處理需扣回獎金之逾期案
                foreach ($this->rewardCategories as $category => $productIdList) {
                    if(empty($rewardList[$category]))
                        continue;

                    $rewardList[$category] = array_column($rewardList[$category], NULL, 'id');

                    // 移除已算過逾期案
                    if(isset($closedDelayedTargetList[$category])) {
                        $closedDelayedTargetList[$category] = array_column($closedDelayedTargetList[$category], NULL, 'id');
                        $rewardList[$category] = array_diff_key($rewardList[$category], $closedDelayedTargetList[$category]);
                    }

                    $currentDelayedTargets[$category] = array_column($this->CI->target_model->getDelayedTarget(array_keys($rewardList[$category])), NULL, "id");

                    if(isset($info['info']) && isset($info['info']['settings'])) {
                        $settings = $info['info']['settings'];
                        if(isset($settings['reward']) && isset($settings['reward']['product'])) {
                            $rewardAmount = $this->getRewardAmountByProduct($settings['reward']['product'], $productIdList);
                            $dockAmountList[$category] = $rewardAmount * count($currentDelayedTargets[$category]);
                            $diff = $info['totalRewardAmount'] - $dockAmountList[$category];
                            if($diff < 0) {
                                $info['totalRewardAmount'] = 0;
                                $remainingDockAmount += abs($diff);
                            }else{
                                $info['totalRewardAmount'] -= $dockAmountList[$category];
                            }
                        }
                    }

                }

                // 篩選需要儲存的欄位
                $data = array_intersect_key($info, array_flip($this->logRewardColumns));
                $selectColumns = array_flip(['id', 'user_id', 'product_id', 'status', 'loan_amount', 'loan_date', 'created_at', 'app_status']);
                foreach ($data as $key => $list) {
                    foreach ($list as $idx => $value) {
                        $data[$key][$idx] = array_intersect_key($value, $selectColumns);
                    }
                }
                $data['delayed_targets'] = $currentDelayedTargets;
                $data['dockList'] = $dockAmountList;

                // 處理需倒扣之金額
                $diff = $info['totalRewardAmount'] - $remainingDockAmount;
                if($diff < 0) {
                    $info['totalRewardAmount'] = 0;
                    $remainingDockAmount = abs($diff);
                }else{
                    $info['totalRewardAmount'] -= $remainingDockAmount;
                    $remainingDockAmount = 0;
                }
                $data['remainingDockAmount'] = $remainingDockAmount;

                $income_tax = $info['totalRewardAmount'] > 20000 ? intval(round($info['totalRewardAmount'] * 0.1,0)) : 0;
                $health_premium = $info['totalRewardAmount'] >= 20000 ? intval(round($info['totalRewardAmount'] * 0.0211,0)) : 0;
                $data['incomeTax'] = $income_tax;
                $data['healthPremium'] = $health_premium;
                $data['originRewardAmount'] = $info['totalRewardAmount'];

                $info['totalRewardAmount'] -= ($income_tax + $health_premium);

                $this->CI->qrcode_reward_model->insert([
                    'user_qrcode_id' => $qrcode['id'],
                    'status' => PROMOTE_REWARD_STATUS_TO_BE_PAID,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'amount' => $info['totalRewardAmount'] ?? 0,
                    'settlement_time' => $today,
                    'json_data' => json_encode($data),
                ]);

                $this->CI->user_qrcode_model->update_by([
                    'id' => $qrcode['id']], ['handle_time' => $today]);

                if ($this->CI->user_qrcode_model->trans_status() === TRUE && $this->CI->qrcode_reward_model->trans_status() === TRUE) {
                    $this->CI->user_qrcode_model->trans_commit();
                    $this->CI->qrcode_reward_model->trans_commit();
                }else{
                    throw new Exception("transaction_status is invalid.");
                }

            } catch (Exception $e) {
                $rollback();
                return FALSE;
            }

            $this->CI->user_qrcode_model->setUserPromoteLock($qrcode['promote_code'], 1, 0);
        }
        return TRUE;
    }

    /**
     * 寄送推薦碼勞務報酬單
     * @return int
     */
    public function send_promote_receipt(): int
    {
        $this->CI->load->model('transaction/qrcode_reward_model');
        $this->CI->load->model('user/virtual_account_model');
        $this->CI->load->library('sendemail');
        $count = 0;

        $list = $this->CI->qrcode_reward_model->getUninformedRewardList();
        if(empty($list))
            return $count;

        $bankAccountList = [];
        $bankAccountRs 	= $this->CI->virtual_account_model->get_many_by([
            'user_id'	=> array_column($list, 'user_id'),
            'status'	=> 1,
            'virtual_account like ' => CATHAY_VIRTUAL_CODE . "%",
        ]);
        foreach ($bankAccountRs as $bankAccount) {
            $bankAccountList[$bankAccount->user_id][$bankAccount->investor] = $bankAccount;
        }

        foreach ($list as $value) {
            $settings = json_decode($value['settings'], TRUE);
            if($settings === FALSE || !isset($bankAccountList[$value['user_id']]) ||
                !isset($settings['investor']) || !isset($bankAccountList[$value['user_id']][$settings['investor']])
            ) {
                continue;
            }
            $bankAccount = $bankAccountList[$value['user_id']][$settings['investor']];
            $rewardData = json_decode($value['reward_data'], TRUE);

            $rs = $this->CI->sendemail->send_promote_receipt($value['email'], $value['name'], $value['id_number'], $value['phone'],
                $value['address'], $value['updated_at'], $bankAccount->virtual_account, $rewardData['originRewardAmount'],
                $rewardData['incomeTax'], $rewardData['healthPremium'], $value['amount']);
            // 也要寄一份給財務人員
            // TODO: 先暫時 hardcode，等有流程出現再依照流程修正
            $this->CI->sendemail->send_promote_receipt('katia@influxfin.com', $value['name'], $value['id_number'], $value['phone'],
                $value['address'], $value['updated_at'], $bankAccount->virtual_account, $rewardData['originRewardAmount'],
                $rewardData['incomeTax'], $rewardData['healthPremium'], $value['amount']);
            if($rs) {
                $this->CI->qrcode_reward_model->update_by(['id' => $value['id']], ['notified_at' => date('Y-m-d H:i:s')]);
                $count++;
            }
        }
        return $count;
    }

    /**
     * 取得首次投資資訊
     * @param $user_id
     * @return array
     */
    public function get_first_investment_info($user_id): array
    {
        $this->CI->load->model('loan/investment_model');
        $this->CI->load->model('loan/transfer_model');

        $result = [
            'tx_date' => '',
            'amount' => 0
        ];

        // TODO: 不能group
        $rs = $this->CI->investment_model->get_principle_list($user_id, [], FALSE);
        if ( ! empty($rs))
        {
            $investment = reset($rs);
        }

        // TODO: 不能group
        $rs = $this->CI->transfer_investment_model->get_principle_list([], $user_id, [], FALSE);
        if ( ! empty($rs))
        {
            $transfer_investment = reset($rs);
        }

        if (!empty($investment) && ! isset($transfer_investment))
        {
            $result['tx_date'] = $investment['tx_date'];
            $result['amount'] = $investment['amount'];
        }
        else if ( empty($investment) && isset($transfer_investment))
        {
            $result['tx_date'] = $transfer_investment['tx_date'];
            $result['amount'] = $transfer_investment['amount'];
        }
        else if (!empty($investment) && isset($transfer_investment))
        {
            if ($transfer_investment['tx_date'] < $investment['tx_date'])
            {
                $result['tx_date'] = $transfer_investment['tx_date'];
                $result['amount'] = $transfer_investment['amount'];
            }
            else
            {
                $result['tx_date'] = $investment['tx_date'];
                $result['amount'] = $investment['amount'];
            }
        }
        return $result;
    }

    public function get_principle_list($user_id, $product_id_list, DateTimeImmutable $start_date, DateTimeImmutable $end_date): array
    {
        $principle_list[$start_date->format('Y-m-d')] = [
            'principle_balance' => 0
        ];

        try
        {
            $this->CI->load->model('loan/transfer_investment_model');
            $this->CI->load->model('loan/investment_model');
            $this->CI->load->model('transaction/transaction_model');
            // 出讓
            $rs = $this->CI->transfer_investment_model->get_principle_list($user_id, [], $product_id_list);
            $sell_principle_list = array_column($rs, NULL, 'tx_date');

            // 債權投資
            $rs = $this->CI->investment_model->get_principle_list($user_id, $product_id_list);
            $investment_principle_list = array_column($rs, NULL, 'tx_date');

            // 本金結清
            $rs = $this->CI->transaction_model->get_paid_off_list(SOURCE_PRINCIPAL, $from=[], $to=$user_id, $product_id_list, $is_group=TRUE);
            $paid_off_principle_list = array_column($rs, NULL, 'tx_date');

            $interval_date = $start_date->diff($end_date);
            $days = $interval_date->days;

            $prev_date = clone $start_date;
            $cur_date = clone $start_date;

            for($i=0; $i<=$days; $i++) {
                $prev_date_str = $prev_date->format('Y-m-d');
                $cur_date_str = $cur_date->format('Y-m-d');

                $principle_list[$cur_date_str] = [
                    'principle_balance' => $principle_list[$prev_date_str]['principle_balance']
                        + ($investment_principle_list[$cur_date_str]['amount'] ?? 0)
                        - ($sell_principle_list[$cur_date_str]['amount'] ?? 0)
                        - ($paid_off_principle_list[$cur_date_str]['amount'] ?? 0)
                ];

                $prev_date = clone $cur_date;
                $cur_date = $cur_date->add(DateInterval::createfromdatestring("+1 day"));
            }
        }
        catch (Exception $e)
        {
            log_message('error', $e->getMessage());
        }
        return $principle_list;
    }

    public function get_investor_report($user_id, $product_id_list, $export_date)
    {
        // 產品列表名稱
        $product_list = $this->CI->config->item('product_list');
        $display_product_ids = !empty($product_id_list) ? $product_id_list : [PRODUCT_ID_STUDENT, PRODUCT_ID_SALARY_MAN];

        $data = [
            "basicInfo" => [
                "id" => "{$user_id}",
                "firstInvestDate" => "-",
                "investAmount" => "0",
                'exportDate' => "{$export_date}"
            ],
            "assetsDescription" => [
                $product_list[PRODUCT_ID_STUDENT]['alias'] => [
                    "name" => "學生貸",
                    "amountNotDelay" => "126436",
                    "totalAmount" => "137379",
                    "amountDelay" => "57340587234"
                ],
                $product_list[PRODUCT_ID_SALARY_MAN]['alias'] => [
                    "name" => "上班族貸",
                    "amountNotDelay" => "126436",
                    "totalAmount" => "137379",
                    "amountDelay" => "57340587234"
                ],
                'total' => [
                    "name" => "本金餘額",
                    "amountNotDelay" => "126436",
                    "totalAmount" => "137379",
                    "amountDelay" => "57340587234"
                ]
            ],
            "investPerformance" => [
                'years' => [
                    "name" => "投資年資",
                    "description" => "0"
                ],
                'firstHalf' =>[
                    "name" => "2021上半年",
                    "description" => "7.1"
                ],
                'averagePrincipalBalance' => [
                    "name" => "平均本金餘額",
                    "description" => "2.9"
                ],
                'discountedCashFlowOfReturnNotDelay' => [
                    "name" => "扣除逾期之折現收益",
                    "description" => "167893934"
                ],
                'discountedCashIRR' => [
                    "name" => "折現年化報酬率",
                    "description" => "12.00"
                ],
            ],
            "realizedRateOfReturn" => [
                [
                    "rangeOfYear" => "2018 01-12",
                    "principalBalance" => "266734",
                    "interest" => "3271",
                    "withdrawInterest" => "15",
                    "repayDelayInterest" => "546",
                    "delayInterest" => "13424",
                    "subsidyInterest" => "90",
                    "handlingFee" => "141241",
                    "totalIncome" => "99573552",
                    "rateOfReturn" => "1"
                ],
                [
                    "rangeOfYear" => "2018 01-12",
                    "principalBalance" => "266734",
                    "interest" => "3271",
                    "withdrawInterest" => "15",
                    "repayDelayInterest" => "546",
                    "delayInterest" => "13424",
                    "subsidyInterest" => "90",
                    "handlingFee" => "141241",
                    "totalIncome" => "99573552",
                    "rateOfReturn" => "1"
                ],
                [
                    "rangeOfYear" => "累績收益率",
                    "principalBalance" => "266734",
                    "interest" => "3271",
                    "withdrawInterest" => "15",
                    "repayDelayInterest" => "546",
                    "delayInterest" => "13424",
                    "subsidyInterest" => "90",
                    "handlingFee" => "141241",
                    "totalIncome" => "99573552",
                    "rateOfReturn" => "1"
                ]
            ],
            "waitedRateOfReturn" => [
                "statisticsData" => [
                    [
                        "rangeOfMonth" => "2021 06-12",
                        "amount" => "62041",
                        "discount" => "41243"
                    ],
                    [
                        "rangeOfMonth" => "2021 06-12",
                        "amount" => "62041",
                        "discount" => "41243"
                    ],
                    [
                        "rangeOfMonth" => "合計",
                        "amount" => "62041",
                        "discount" => "41243"
                    ]
                ],
                "predictRateOfReturn" => "16.14"
            ],
            "delayNotReturn" => [
                'principalAndInterest' => [
                    "name" => "逾期-尚欠本息",
                    "amount" => "58296"
                ],
                'delayInterest' => [
                    "name" => "逾期-尚欠延滯息",
                    "amount" => "58296"
                ],
                'total' => [
                    "name" => "合計",
                    "amount" => "58296"
                ]
            ]
        ];

        // -- 投資人資訊
        $this->CI->load->model('loan/investment_model');
        $this->CI->load->model('loan/transfer_investment_model');
        $first_investment = $this->get_first_investment_info($user_id);
        if(!empty($first_investment))
        {
            $data['basicInfo']['firstInvestDate'] = date('Y/m/d', strtotime($first_investment['tx_date']));
            $data['basicInfo']['investAmount'] = $first_investment['amount'];
        }

        // -- 資產概況
        // 正常還款本金餘額
        $PrincipalBalance = $this->CI->target_model->getTransactionSourceByInvestor($user_id, FALSE, [SOURCE_AR_PRINCIPAL], $product_id_list, TRUE);
        if ( ! empty($PrincipalBalance))
        {
            $PrincipalBalance = array_column($PrincipalBalance, 'amount', 'product_id');
        }
        // 逾期中本金餘額
        $PrincipalBalanceDelay = $this->CI->target_model->getTransactionSourceByInvestor($user_id, TRUE, [SOURCE_AR_PRINCIPAL], $product_id_list, TRUE);
        if ( ! empty($PrincipalBalanceDelay))
        {
            $PrincipalBalanceDelay = array_column($PrincipalBalanceDelay, 'amount', 'product_id');
        }

        $amountNotDelayAll = 0;
        $amountDelayAll = 0;
        $totalAmountAll = 0;
        foreach ($display_product_ids as $product_id)
        {
            $amountNotDelay = isset($PrincipalBalance[$product_id]) && is_numeric($PrincipalBalance[$product_id]) ? $PrincipalBalance[$product_id] : 0;
            $amountDelay = isset($PrincipalBalanceDelay[$product_id]) && is_numeric($PrincipalBalanceDelay[$product_id]) ? $PrincipalBalanceDelay[$product_id] : 0;
            $totalAmount = $amountNotDelay + $amountDelay;
            $data['assetsDescription'][$product_list[$product_id]['alias']] = [
                'name' => $product_list[$product_id]['name'],
                'amountNotDelay' => $amountNotDelay,
                'amountDelay' => $amountDelay,
                'totalAmount' => $totalAmount,
            ];
            // 全部總和
            $amountNotDelayAll += $amountNotDelay;
            $amountDelayAll += $amountDelay;
            $totalAmountAll += $totalAmount;
        }
        $data['assetsDescription']['total'] = [
            'name' => '本金餘額',
            'amountNotDelay' => $amountNotDelayAll,
            'amountDelay' => $amountDelayAll,
            'totalAmount' => $totalAmountAll,
        ];

        // -- 投資績效
        if ( ! empty($first_investment))
        {
            try
            {
                $d1 = new DateTime($first_investment['tx_date']);
                $d2 = new DateTime($export_date);
                $data['investPerformance']['years'] = round($d1->diff($d2)->days / 365.0, 1);
            }
            catch (Exception $e)
            {
                log_message('error', $e->getMessage());
            }
        }

        // -- 已實現收益率
        $generate_RoR_init_list = function(DateTimeImmutable $start_date, DateTimeImmutable $end_date) {
            return [
                'principle_list' => [],
                'interest' => 0,
                'prepaid_interest' => 0,
                'delayed_paid_interest' => 0,
                'delayed_interest' => 0,
                'allowance' => 0,
                'platform_fee' => 0,
                'total_income' => 0,
                'rate_of_return' => 0,
                'average_principle' => 0,
                'start_date' => $start_date->format('Y-m'),
                'end_date' => $end_date->format('Y-m'),
                'days' => $start_date->diff($end_date)->days + 1
            ];
        };

        if(!empty($first_investment))
        {
            try
            {
                $start_date = new \DateTimeImmutable(date('Y-01-01', strtotime($first_investment['tx_date'])));
                $end_date = new \DateTimeImmutable(date('Y-m-t', strtotime("-1 month")));
                $RoRList = [];

                // 建立表格結構
                $diff = $start_date->diff($end_date);
                $RoRList['total'] = $generate_RoR_init_list($start_date, $end_date);
                for ($i = 0; $i <= $diff->y; $i++)
                {
                    $year = $start_date->add(DateInterval::createfromdatestring("+{$i} year"));
                    $year_str = $year->format('Y');
                    $RoRList[$year_str] = $generate_RoR_init_list(
                        $year->setDate($year_str, 1, 1), $year->setDate($year_str, 12, 31));
                    if ($end_date->format('Y') == $year_str)
                    {
                        $RoRList[$year_str]['end_date'] = $end_date->format('Y-m');
                        $RoRList[$year_str]['days'] = $year->diff($end_date)->days + 1;
                    }
                }

                // 取得每天之本金餘額
                $principle_list = $this->get_principle_list($user_id, $product_id_list, $start_date, $end_date);
                foreach ($principle_list as $date => $info)
                {
                    $ym_date = new \DateTimeImmutable($date);
                    $ym_date_str = $ym_date->format('Y');
                    $RoRList[$ym_date_str]['principle_list'][] = $info['principle_balance'];
                }

                // 計算本金均額
                for ($i = 0; $i <= $diff->y; $i++)
                {
                    $year = $start_date->add(DateInterval::createfromdatestring("+{$i} year"));
                    $year_str = $year->format('Y');
                    $RoRList[$year_str]['average_principle'] = round(array_sum($RoRList[$year_str]['principle_list']) / $RoRList[$year_str]['days']);
                }

                // 計算收入數據
                $income_list = $this->CI->transaction_model->get_paid_off_list([SOURCE_INTEREST, SOURCE_DELAYINTEREST, SOURCE_PREPAYMENT_ALLOWANCE], $from = [], $to = $user_id, $product_id_list, $is_group = TRUE);
                foreach ($income_list as $info)
                {
                    $ym_date = new \DateTimeImmutable($info['tx_date']);
                    $year_str = $ym_date->format('Y');
                    switch ($info['source'])
                    {
                        case SOURCE_INTEREST:
                            $RoRList[$year_str]['interest'] += $info['amount'];
                            break;
                        case SOURCE_DELAYINTEREST:
                            $RoRList[$year_str]['delayed_interest'] += $info['amount'];
                            break;
                        case SOURCE_PREPAYMENT_ALLOWANCE:
                            $RoRList[$year_str]['allowance'] += $info['amount'];
                            break;
                    }
                }

                // 計算平台服務費支出
                $expense_list = $this->CI->transaction_model->get_paid_off_list([SOURCE_FEES], $from = $user_id, $to = [], $product_id_list, $is_group = TRUE);
                foreach ($expense_list as $info)
                {
                    $ym_date = new \DateTimeImmutable($info['tx_date']);
                    $year_str = $ym_date->format('Y');
                    $RoRList[$year_str]['platform_fee'] += $info['amount'];
                }

                // 計算提還利息
                $prepaid_interest_list = $this->CI->transaction_model->get_prepaid_transactions(SOURCE_INTEREST, $user_id, $product_id_list, $is_group = TRUE);
                foreach ($prepaid_interest_list as $info)
                {
                    $ym_date = new \DateTimeImmutable($info['tx_date']);
                    $year_str = $ym_date->format('Y');
                    $RoRList[$year_str]['prepaid_interest'] += $info['amount'];
                }

                // 逾期償還利息
                $delayed_interest_list = $this->CI->transaction_model->get_delayed_paid_transaction(SOURCE_INTEREST, $user_id, $product_id_list, $is_group = TRUE);
                foreach ($delayed_interest_list as $info)
                {
                    $ym_date = new \DateTimeImmutable($info['tx_date']);
                    $year_str = $ym_date->format('Y');
                    $RoRList[$year_str]['delayed_paid_interest'] += $info['amount'];
                }

                // 轉換為每年區間的統計數據
                for ($i = 0; $i <= $diff->y; $i++)
                {
                    $year = $start_date->add(DateInterval::createfromdatestring("+{$i} year"));
                    $year_str = $year->format('Y');
                    $RoRList[$year_str]['total_income'] = $RoRList[$year_str]['interest'] + $RoRList[$year_str]['delayed_interest'] +
                        $RoRList[$year_str]['prepaid_interest'] + $RoRList[$year_str]['delayed_paid_interest'] +
                        $RoRList[$year_str]['allowance'] - $RoRList[$year_str]['platform_fee'];
                    $RoRList[$year_str]['rate_of_return'] = round($RoRList[$year_str]['total_income'] / $RoRList[$year_str]['average_principle'] * 100, 1);

                    $RoRList['total']['average_principle'] += round($RoRList[$year_str]['average_principle'] * ($RoRList[$year_str]['days'] / $RoRList['total']['days']));
                    $RoRList['total']['interest'] += $RoRList[$year_str]['interest'];
                    $RoRList['total']['delayed_interest'] += $RoRList[$year_str]['delayed_interest'];
                    $RoRList['total']['prepaid_interest'] += $RoRList[$year_str]['prepaid_interest'];
                    $RoRList['total']['delayed_paid_interest'] += $RoRList[$year_str]['delayed_paid_interest'];
                    $RoRList['total']['allowance'] += $RoRList[$year_str]['allowance'];
                    $RoRList['total']['platform_fee'] += $RoRList[$year_str]['platform_fee'];
                    $RoRList['total']['total_income'] += $RoRList[$year_str]['total_income'];
                }
                $RoRList['total']['rate_of_return'] = round($RoRList['total']['total_income'] / $RoRList['total']['average_principle'] * 100, 1);

                // 計算待實現應收利息
                $ar_interest_list = [];
                $ar_interest = $this->CI->transaction_model->get_account_payable_list(SOURCE_AR_INTEREST, $from = [], $to = $user_id,
                    $product_id_list, $is_group = TRUE, $end_date->format('Y-m-d'));
                foreach ($ar_interest as $info)
                {
                    $ym_date = new \DateTimeImmutable($info['tx_date']);
                    $year_str = $ym_date->format('Y');
                    if ( ! array_key_exists($year_str, $ar_interest_list))
                    {
                        $ar_interest_list[$year_str] = [
                            'amount' => $info['amount'],
                            'discount_amount' => 0,
                            'start_date' => $ym_date->format('Y-m'),
                            'end_date' => $ym_date->format('Y-m'),
                        ];
                    }
                    else
                    {
                        $ar_interest_list[$year_str]['amount'] += $info['amount'];
                        if (($tmp_date = $ym_date->format('Y-m')) > $ar_interest_list[$year_str]['end_date'])
                        {
                            $ar_interest_list[$year_str]['end_date'] = $tmp_date;
                        }
                    }
                }

                $estimate_IRR = 16.1;
                foreach ($ar_interest_list as $year_str => $info)
                {
                    $end = new \DateTimeImmutable($info['end_date']);
                    $diff = $end_date->setDate($end_date->format('Y'),$end_date->format('m'),1)->diff($end);
                    $ar_interest_list[$year_str]['discount_amount'] = $info['amount'] / pow(($estimate_IRR+1),$diff->m/12);
                }

                // 逾期未收
                $delayed_ar_list_rs = $this->CI->transaction_model->get_delayed_ar_transaction([SOURCE_AR_PRINCIPAL, SOURCE_AR_INTEREST, SOURCE_AR_DELAYINTEREST], $user_id, $product_id_list, $is_group = TRUE);
                $delayed_ar_list = [];
                array_walk($delayed_ar_list_rs, function($item, $key) use (&$delayed_ar_list){
                    $source = $item['source'];
                    $delayed_ar_list[$source] = isset($delayed_ar_list[$source]) ?  $item['amount'] + $delayed_ar_list[$source] : $item['amount'];
                });

            }
            catch (Exception $e)
            {
                log_message('error', $e->getMessage());
            }
        }
    }

}

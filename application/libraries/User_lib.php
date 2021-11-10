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
            'virtual_account like ' => TAISHIN_VIRTUAL_CODE . "%",
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
            if($rs) {
                $this->CI->qrcode_reward_model->update_by(['id' => $value['id']], ['notified_at' => date('Y-m-d H:i:s')]);
                $count++;
            }
        }
        return $count;
    }
}

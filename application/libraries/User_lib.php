<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use EdmEvent\EdmEventFactory;

class User_lib {

    /**
     * @var int
     */
    private $totalCount;

    // 特約方案可以改申貸服務費/利息百分比的產品類別
    public $appointedRewardCategories = ['student', 'salary_man'];

    public $rewardCategories = [
        'student' => [1, 2],
        'salary_man' => [3, 4],
        'small_enterprise' => [PRODUCT_SK_MILLION_SMEG],
    ];
    public $categoriesName = [
        'student' => '學生貸',
        'salary_man' => '上班族貸',
        'small_enterprise' => '微企貸',
    ];

    public $rewardedTargetStatus = [
        'student' => [TARGET_REPAYMENTING, TARGET_REPAYMENTED],
        'salary_man' => [TARGET_REPAYMENTING, TARGET_REPAYMENTED],
        'small_enterprise' => [TARGET_BANK_REPAYMENTING, TARGET_BANK_REPAYMENTED],
    ];
    public $logRewardColumns = [
        'student', 'salary_man', 'small_enterprise', 'fullMember'
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
     * @param int $length : 推薦碼長度
     * @param string $prefix : 前綴詞
     * @param int $retries : 重試次數
     * @return string
     * @throws Exception
     */
    public function get_promote_code(int $length, string $prefix = '', int $retries=0): string
    {
        if($retries >= 1000) {
            throw new \Exception('超過1000次的重複命中，請檢查資料庫是否已經使用滿組合數');
        }
        $this->CI->load->model('user/user_qrcode_model');
        $code = $prefix . make_promote_code($length);
        $user = $this->CI->user_model->get_by(['my_promote_code' => $code]);
        $user_qrcode = $this->CI->user_qrcode_model->get_by(['promote_code' => $code]);
        if (isset($user) || isset($user_qrcode))
        {
            return $this->get_promote_code($length, $prefix, $retries + 1);
        }
        else
        {
            return $code;
        }
    }

    /**
     * 取得產品對應之推薦金額
     * @param $productSettings
     * @param $productIdList
     * @return int
     */
    public function getRewardAmountByProduct($productSettings, $productIdList): int
    {
        foreach ($productSettings as $setting)
        {
            if (isset($setting['product_id']) && $setting['product_id'] == $productIdList)
            {
                return (int) ($setting['amount'] ?? 0);
            }
        }
        return 0;
    }

    /**
     * 取得產品對應之借款人服務費獎勵百分比
     * @param $productSettings
     * @param $productIdList
     * @return float
     */
    public function getRewardBorrowerPercentByProduct($productSettings, $productIdList): float
    {
        foreach ($productSettings as $setting)
        {
            if (isset($setting['product_id']) && $setting['product_id'] == $productIdList)
            {
                return max(0, min(100, (float) ($setting['borrower_percent'] ?? 0)));
            }
        }
        return 0;
    }

    /**
     * 取得產品對應之投資人回款服務費獎勵百分比
     * @param $productSettings
     * @param $productIdList
     * @return float
     */
    public function getRewardInvestorPercentByProduct($productSettings, $productIdList): float
    {
        foreach ($productSettings as $setting)
        {
            if (isset($setting['product_id']) && $setting['product_id'] == $productIdList)
            {
                return max(0, min(100, (float) ($setting['investor_percent'] ?? 0)));
            }
        }
        return 0;
    }

    public function getCollaborationRewardAmount($settings, $collaboration_type)
    {
        switch ($collaboration_type)
        {
            case 1:
                $type = 'collaboration_person';
                break;
            case 2:
                $type = 'collaboration_enterprise';
                break;
            default:
                return 0;
        }
        return $settings[$type]['amount'] ?? 0;
    }

    /**
     * 取得推薦碼獎勵及相關資訊
     * @param array $where
     * @param string $startDate
     * @param string $endDate
     * @param int $limit
     * @param int $offset
     * @param bool $filterDelayed 是否要過濾逾期案
     * @return array
     */
    public function getPromotedRewardInfo(array $where, string $startDate = '', string $endDate = '', int $limit = 0, int $offset = 0, bool $filterDelayed = FALSE): array
    {
        $this->CI->load->model('user/user_qrcode_model');
        $this->CI->load->library('qrcode_lib');
        $categoryInitList = array_combine(array_keys($this->rewardCategories), array_fill(0, count($this->rewardCategories), []));
        $categoryInitNum = array_combine(array_keys($this->rewardCategories), array_fill(0, count($this->rewardCategories), 0));
        $list = [];
        $promoteCodeList = [];

        if ( ! isset($where['status']))
            $where['status'] = [PROMOTE_STATUS_DISABLED, PROMOTE_STATUS_AVAILABLE];

        // 取得推薦碼資料
        $promoteCodesRs = $this->CI->qrcode_lib->get_user_qrcode_info([], $where, $limit, $offset);
        foreach ($promoteCodesRs as $promoteCodeRs)
        {
            if ( ! isset($list[$promoteCodeRs['id']]))
            {
                $userQrcodeId = $promoteCodeRs['id'];
                $list[$userQrcodeId] = $categoryInitList;
                $list[$userQrcodeId]['monthly'] = [];
                $list[$userQrcodeId]['borrowerPlatformFee'] = $categoryInitNum;
                $list[$userQrcodeId]['investorPlatformFee'] = $categoryInitNum;
                $list[$userQrcodeId]['collaboration'] = [];
                $list[$userQrcodeId]['collaborationCount'] = [];
                $list[$userQrcodeId]['collaborationRewardAmount'] = [];
                $list[$userQrcodeId]['totalCollaborationRewardAmount'] = 0;
                $list[$userQrcodeId]['fullMemberCount'] = 0;
                $list[$userQrcodeId]['fullMember'] = [];
                $list[$userQrcodeId]['registeredCount'] = 0;
                $list[$userQrcodeId]['registered'] = [];
                $list[$userQrcodeId]['rewardAmount'] = [];
                $promoteCodeList[$userQrcodeId] = $promoteCodeRs;
            }
        }

        $promoteCodes = array_column($promoteCodeList, 'promote_code');
        $userQrcodeIds = array_column($promoteCodeList, 'id');
        if (empty($promoteCodes))
        {
            return $list;
        }

        // 取得推薦碼下載數
        $this->CI->load->model('behavion/user_behavior_model');
        $firstOpenRs = $this->CI->user_behavior_model->getFirstOpenCountByPromoteCode($promoteCodes, $startDate, $endDate);
        foreach ($firstOpenRs as $rs)
        {
            $list[$rs['user_qrcode_id']]['downloadedCount'] = $rs['count'];
        }

        // 取得推薦之註冊會員數
        if ( ! empty($promoteCodeList))
            $where['id'] = $userQrcodeIds;
        $registeredRs = $this->CI->user_qrcode_model->getRegisteredUserByPromoteCode($where, $startDate, $endDate);
        foreach ($registeredRs as $rs)
        {
            if ($rs['app_status'] == 1)
            {
                $list[$rs['user_qrcode_id']]['fullMemberCount'] += 1;
                $list[$rs['user_qrcode_id']]['fullMember'][] = $rs;
            }
            $list[$rs['user_qrcode_id']]['registeredCount'] += 1;
            $list[$rs['user_qrcode_id']]['registered'][] = $rs;
        }

        // 取得成功推薦申貸的數量
        foreach ($this->rewardCategories as $category => $productIdList)
        {
            $rs = $this->CI->qrcode_lib->get_product_reward_list($where, $productIdList, $this->rewardedTargetStatus[$category], $startDate, $endDate, $filterDelayed);
            foreach ($rs as $promotedTarget)
            {
                $list[$promotedTarget['user_qrcode_id']][$category][] = $promotedTarget;
            }
        }

        // 計算推薦碼各產品初貸數量/下載數/註冊數的相關獎金
        foreach ($promoteCodeList as $value)
        {
            $userQrcodeId = $value['id'];

            $list[$userQrcodeId]['info'] = json_decode(json_encode($value), TRUE) ?? [];
            $list[$userQrcodeId]['totalRewardAmount'] = 0;
            $list[$userQrcodeId]['totalLoanedAmount'] = 0;

            $list[$userQrcodeId]['info']['settings'] = json_decode($list[$userQrcodeId]['info']['settings'], TRUE) ?? [];
            $settings = $list[$userQrcodeId]['info']['settings'];
            foreach ($this->rewardCategories as $category => $productIdList)
            {
                $list[$userQrcodeId]['loanedCount'][$category] = count($list[$userQrcodeId][$category]);
                $list[$userQrcodeId]['loanedBalance'][$category] = array_sum(array_column($list[$userQrcodeId][$category], 'loan_amount'));
                $list[$userQrcodeId]['rewardAmount'][$category] = 0;

                if (isset($settings['reward']) && isset($settings['reward']['product']))
                {
                    $rewardAmount = $this->getRewardAmountByProduct($settings['reward']['product'], $productIdList);
                    $rewardBorrowerPercent = $this->getRewardBorrowerPercentByProduct($settings['reward']['product'], $productIdList);
                    $rewardInvestorPercent = $this->getRewardInvestorPercentByProduct($settings['reward']['product'], $productIdList);

                    $list[$userQrcodeId]['rewardAmount'][$category] = $list[$userQrcodeId]['loanedCount'][$category] * $rewardAmount;

                    if ($rewardAmount > 0)
                    {
                        foreach ($list[$userQrcodeId][$category] as $value)
                        {
                            $formattedMonth = date("Y-m", strtotime($value['loan_date']));
                            if ( ! isset($list[$userQrcodeId]['monthly'][$formattedMonth]))
                            {
                                $list[$userQrcodeId]['monthly'][$formattedMonth] = $categoryInitList;
                            }
                            if ( ! isset($list[$userQrcodeId]['monthly'][$formattedMonth][$category]['targets']))
                            {
                                $list[$userQrcodeId]['monthly'][$formattedMonth][$category]['targets'] = [];
                            }
                            // 每個案件的明細
                            $list[$userQrcodeId]['monthly'][$formattedMonth][$category]['targets'][$value['id']][] = [
                                'enteringDate' => $value['loan_date'],
                                'rewardAmount' => $rewardAmount,
                            ];
                            $list[$userQrcodeId]['monthly'][$formattedMonth][$category]['targets'][$value['id']]['rewardAmount'] =
                                ($list[$userQrcodeId]['monthly'][$formattedMonth][$category]['targets'][$value['id']]['rewardAmount'] ?? 0) + $rewardAmount;
                        }
                    }

                    // 計算服務費的函數
                    $computePlatformFee = function ($rs, $keyword, $rewardPercent) use (&$list, $category, $userQrcodeId, $categoryInitList) {
                        foreach ($rs as $value)
                        {
                            $formattedMonth = date("Y-m", strtotime($value['entering_date']));
                            if ( ! isset($list[$userQrcodeId]['monthly'][$formattedMonth]))
                            {
                                $list[$userQrcodeId]['monthly'][$formattedMonth] = $categoryInitList;
                            }
                            if ( ! isset($list[$userQrcodeId]['monthly'][$formattedMonth][$category]['targets']))
                            {
                                $list[$userQrcodeId]['monthly'][$formattedMonth][$category]['targets'] = [];
                            }

                            $rewardAmount = (int) round($value['platform_fee'] * $rewardPercent / 100.0, 0);
                            // 每個案件的明細
                            $list[$userQrcodeId]['monthly'][$formattedMonth][$category]['targets'][$value['id']][] = [
                                'enteringDate' => $value['entering_date'],
                                $keyword => $value['platform_fee'],
                                'rewardAmount' => $rewardAmount,
                            ];

                            // 每月結算
                            $list[$userQrcodeId]['monthly'][$formattedMonth][$category][$keyword] =
                                ($list[$userQrcodeId][$formattedMonth][$category][$keyword] ?? 0) + $value['platform_fee'];
                            $list[$userQrcodeId]['monthly'][$formattedMonth][$category]['targets'][$value['id']][$keyword] =
                                ($list[$userQrcodeId]['monthly'][$formattedMonth][$category]['targets'][$value['id']][$keyword] ?? 0) + $value['platform_fee'];
                            $list[$userQrcodeId][$keyword][$category] += $value['platform_fee'];

                            if ($rewardAmount)
                            {
                                $list[$userQrcodeId]['monthly'][$formattedMonth][$category]['rewardAmount'] = ($list[$userQrcodeId]['monthly'][$formattedMonth][$category]['rewardAmount'] ?? 0) + $rewardAmount;
                                $list[$userQrcodeId]['rewardAmount'][$category] = ($list[$userQrcodeId]['rewardAmount'][$category] ?? 0) + $rewardAmount;
                            }
                        }
                    };

                    // 處理申貸借款的服務費
                    $rs = $this->CI->qrcode_lib->get_borrower_platform_fee_list(['id' => $userQrcodeId], $productIdList, $this->rewardedTargetStatus[$category],
                        $startDate, $endDate, $filterDelayed);
                    $computePlatformFee($rs, 'borrowerPlatformFee', $rewardBorrowerPercent);

                    // 處理投資人回款手續費
                    $rs = $this->CI->qrcode_lib->get_investor_platform_fee_list(['id' => $userQrcodeId], $productIdList, $this->rewardedTargetStatus[$category],
                        $startDate, $endDate, $filterDelayed);
                    $computePlatformFee($rs, 'investorPlatformFee', $rewardInvestorPercent);
                }

                $list[$userQrcodeId]['totalRewardAmount'] += $list[$userQrcodeId]['rewardAmount'][$category] ?? 0;
                $list[$userQrcodeId]['totalLoanedAmount'] += $list[$userQrcodeId]['loanedBalance'][$category];
            }

            if ( ! isset($list[$userQrcodeId]['fullMemberCount']))
                $list[$userQrcodeId]['fullMemberCount'] = 0;

            if ( ! isset($list[$userQrcodeId]['downloadedCount']))
                $list[$userQrcodeId]['downloadedCount'] = 0;

            if (isset($settings['reward']) && isset($settings['reward']['full_member']))
            {
                $list[$userQrcodeId]['fullMemberRewardAmount'] = $list[$userQrcodeId]['fullMemberCount'] * intval($settings['reward']['full_member']['amount']);
                $list[$userQrcodeId]['totalRewardAmount'] += $list[$userQrcodeId]['fullMemberRewardAmount'];
            }
        }

        // 計算合作對象推薦獎金
        $this->CI->load->model('user/user_qrcode_collaboration_model');
        $collaborationRs = $this->CI->user_qrcode_collaboration_model->getCollaborationList($userQrcodeIds, $startDate, $endDate);
        foreach ($collaborationRs as $rs)
        {
            $userQrcodeId = $rs['id'];
            $collaboratorId = $rs['qrcode_collaborator_id'];
            $settings = $list[$userQrcodeId]['info']['settings'];
            $collaborationRewardAmount = 0;
            if (isset($settings['reward']) && isset($rs['type']))
            {
                $collaborationRewardAmount = $this->getCollaborationRewardAmount($settings['reward'], $rs['type']);
            }
            $rs['rewardAmount'] = $collaborationRewardAmount;

            $list[$userQrcodeId]['collaboration'][$collaboratorId][] = $rs;

            if ( ! isset($list[$userQrcodeId]['collaborationCount'][$collaboratorId]) &&
                ! isset($list[$userQrcodeId]['collaborationRewardAmount'][$collaboratorId]))
            {
                $list[$userQrcodeId]['collaborationCount'][$collaboratorId] = 0;
                $list[$userQrcodeId]['collaborationRewardAmount'][$collaboratorId] = 0;
            }
            $list[$userQrcodeId]['collaborationCount'][$collaboratorId] += 1;
            $list[$userQrcodeId]['collaborationRewardAmount'][$collaboratorId] += $collaborationRewardAmount;
            $list[$userQrcodeId]['totalCollaborationRewardAmount'] += $collaborationRewardAmount;

            // 累加合作對象獎金至總獎金
            $list[$userQrcodeId]['totalRewardAmount'] += $collaborationRewardAmount;
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
            'settlementing' => 0, 'subcode_flag' => 0]);
        foreach ($userQrcodes as $qrcode)
        {
            if (isset($qrcode['end_time']))
                $_startTime = max($startTime, $qrcode['end_time']);
            else
                $_startTime = $startTime;

            if ($_startTime < $endTime && ( ! isset($qrcode['handle_time']) || $qrcode['handle_time'] < $endTime))
            {
                if ($this->handlePromoteReward($qrcode, $_startTime, $endTime))
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
    public function handlePromoteReward($qrcode, string $startTime = '', string $endTime = ''): bool
    {
        $promoteCode = $this->CI->user_qrcode_model->setUserPromoteLock($qrcode['promote_code'], 0, 1);
        if ( ! empty($promoteCode) &&
            ( ! isset($promoteCode->handle_time) || $promoteCode->handle_time < $endTime))
        {
            $this->CI->load->model('transaction/qrcode_reward_model');
            $this->CI->load->library('qrcode_lib');

            $today = date("Y-m-d H:i:s");
            $rollback = function () {
                $this->CI->user_qrcode_model->trans_rollback();
                $this->CI->qrcode_reward_model->trans_rollback();
            };

            $this->CI->user_qrcode_model->trans_begin();
            $this->CI->qrcode_reward_model->trans_begin();

            $info = $this->CI->qrcode_lib->get_promoted_reward_info(['id' => $qrcode['id']], $startTime, $endTime, 0, 0, TRUE);

            try
            {
                if (empty($info))
                    throw new Exception("The promote code " . $qrcode['promote_code'] . " is not found.");
                $info = reset($info);
                $descRewardList = $this->CI->qrcode_reward_model->order_by('end_time', 'DESC')->get_many_by(['user_qrcode_id' => $info['info']['id'], 'status' => [
                    PROMOTE_REWARD_STATUS_TO_BE_PAID, PROMOTE_REWARD_STATUS_PAID_OFF
                ]]);

                // 之前的每月獎勵資訊 (For 逾期追回用)
                $monthlyRewardList = $info['monthly'] ?? [];
                // 整理所有案件資訊
                $rewardList = array_intersect_key($info, $this->rewardCategories);
                $closedDelayedTargetList = [];
                $currentDelayedTargets = [];
                $dockAmountList = array_combine(array_keys($this->rewardCategories), array_fill(0, count($this->rewardCategories), 0));
                $remainingDockAmount = 0;

                // 最後一期的剩餘扣除金額需加回
                $tmpReward = reset($descRewardList);
                if ($tmpReward)
                {
                    $data = json_decode($tmpReward->json_data, TRUE);
                    $remainingDockAmount += $data['remainingDockAmount'] ?? 0;
                }

                foreach ($descRewardList as $value)
                {
                    // 將之前的獎勵案列表 及 逾期案列表 合併
                    $data = json_decode($value->json_data, TRUE);
                    if ($data)
                    {
                        $rewardList = array_merge_recursive($rewardList, array_intersect_key($data, $this->rewardCategories));
                        $closedDelayedTargetList = array_merge_recursive($closedDelayedTargetList, array_intersect_key($data['delayed_targets'] ?? [], $this->rewardCategories));
                    }

                    if ( ! isset($data['monthly_rewards']) || empty($data['monthly_rewards']))
                        continue;

                    // 依照月份和案號 去合併之前的每月獎勵金額
                    foreach ($data['monthly_rewards'] as $date => $categoryRewardList)
                    {
                        foreach ($categoryRewardList as $category => $infoList)
                        {
                            if ( ! isset($infoList['targets']))
                                continue;
                            foreach ($infoList['targets'] as $target_id => $targetInfo)
                            {
                                if (isset($targetInfo['rewardAmount']))
                                {
                                    $monthlyRewardList[$date][$category]['targets'][$target_id]['rewardAmount'] =
                                        ($monthlyRewardList[$date][$category]['targets'][$target_id]['rewardAmount'] ?? 0) + $targetInfo['rewardAmount'];
                                }
                            }
                        }
                    }

                }

                // 處理需扣回獎金之逾期案
                foreach ($this->rewardCategories as $category => $productIdList)
                {
                    if (empty($rewardList[$category]))
                        continue;

                    $rewardList[$category] = array_column($rewardList[$category], NULL, 'id');

                    // 移除已算過逾期案
                    if (isset($closedDelayedTargetList[$category]))
                    {
                        $closedDelayedTargetList[$category] = array_column($closedDelayedTargetList[$category], NULL, 'id');
                        $rewardList[$category] = array_diff_key($rewardList[$category], $closedDelayedTargetList[$category]);
                    }

                    $currentDelayedTargets[$category] = array_column($this->CI->target_model->getDelayedTarget(array_keys($rewardList[$category]), $endTime), NULL, "id");

                    // 針對逾期案件的獎金做追回
                    foreach ($monthlyRewardList as $date => $categoryRewardList)
                    {
                        if ( ! isset($categoryRewardList[$category]['targets']) || $date > $startTime)
                            continue;

                        foreach ($currentDelayedTargets[$category] as $targetId => $delayedTarget)
                        {
                            $dockAmountList[$category] += $categoryRewardList[$category]['targets'][$targetId]['rewardAmount'] ?? 0;
                        }
                    }

                    $diff = $info['totalRewardAmount'] - $dockAmountList[$category];
                    if ($diff < 0)
                    {
                        $info['totalRewardAmount'] = 0;
                        $remainingDockAmount += abs($diff);
                    }
                    else
                    {
                        $info['totalRewardAmount'] -= $dockAmountList[$category];
                    }
                }


                // 篩選需要儲存的欄位
                $data = array_intersect_key($info, array_flip($this->logRewardColumns));
                $selectColumns = array_flip(['id', 'user_id', 'product_id', 'status', 'loan_amount', 'loan_date', 'created_at', 'app_status']);
                foreach ($data as $key => $list)
                {
                    foreach ($list as $idx => $value)
                    {
                        $data[$key][$idx] = array_intersect_key($value, $selectColumns);
                    }
                }
                $data['delayed_targets'] = $currentDelayedTargets;
                $data['dockList'] = $dockAmountList;
                $data['monthly_rewards'] = $info['monthly'];

                // 儲存第三方合作產品紀錄
                $data = array_replace_recursive($data, array_intersect_key($info, array_flip(['collaboration', 'collaborationRewardAmount'])));

                // 處理需倒扣之金額
                $diff = $info['totalRewardAmount'] - $remainingDockAmount;
                if ($diff < 0)
                {
                    $info['totalRewardAmount'] = 0;
                    $remainingDockAmount = abs($diff);
                }
                else
                {
                    $info['totalRewardAmount'] -= $remainingDockAmount;
                    $remainingDockAmount = 0;
                }
                $data['remainingDockAmount'] = $remainingDockAmount;

                $income_tax = $info['totalRewardAmount'] > 20000 ? intval(round($info['totalRewardAmount'] * 0.1, 0)) : 0;
                $health_premium = $info['totalRewardAmount'] >= 20000 ? intval(round($info['totalRewardAmount'] * 0.0211, 0)) : 0;
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

                if ($this->CI->user_qrcode_model->trans_status() === TRUE && $this->CI->qrcode_reward_model->trans_status() === TRUE)
                {
                    $this->CI->user_qrcode_model->trans_commit();
                    $this->CI->qrcode_reward_model->trans_commit();
                }
                else
                {
                    throw new Exception("transaction_status is invalid.");
                }

            }
            catch (Exception $e)
            {
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
        $this->CI->load->model('user/qrcode_setting_model');
        $this->CI->load->library('sendemail');
        $count = 0;

        $list = $this->CI->qrcode_reward_model->getUninformedRewardList([PROMOTE_REWARD_STATUS_TO_BE_PAID, PROMOTE_REWARD_STATUS_PAID_OFF]);
        if (empty($list))
            return $count;

        $bankAccountList = [];
        $bankAccountRs = $this->CI->virtual_account_model->get_many_by([
            'user_id' => array_column($list, 'user_id'),
            'status' => VIRTUAL_ACCOUNT_STATUS_AVAILABLE,
            'virtual_account like ' => CATHAY_VIRTUAL_CODE . "%",
        ]);
        foreach ($bankAccountRs as $bankAccount)
        {
            $bankAccountList[$bankAccount->user_id][$bankAccount->investor] = $bankAccount;
        }

        foreach ($list as $value)
        {
            // 沒有獎金的不需寄送明細表
            if ( ! $value['amount'])
            {
                continue;
            }

            $rs = FALSE;
            if ($value['alias'] == $this->CI->qrcode_setting_model->appointedCaseAliasName)
            {
                // 特約方案
                $this->CI->load->library('qrcode_lib');
                $rs = $this->CI->qrcode_lib->insert_statement_pdf($value['id']);
            }
            else if ($value['status'] == PROMOTE_REWARD_STATUS_PAID_OFF)
            {
                // 其他方案
                $settings = json_decode($value['settings'], TRUE);
                if ($settings === NULL || ! isset($bankAccountList[$value['user_id']]) ||
                    ! isset($settings['investor']) || ! isset($bankAccountList[$value['user_id']][$settings['investor']])
                )
                {
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
            }

            if ($rs)
            {
                $this->CI->qrcode_reward_model->update_by(['id' => $value['id']], ['notified_at' => date('Y-m-d H:i:s')]);
                $count++;
            }
        }
        return $count;
    }

    /**
     * 解析 JWT token 並取得使用者
     * @param $token : JWT token
     * @param $request_method : HTTP 請求方法
     * @return mixed
     * @throws Exception
     */
    public function parse_token($token, $request_method, $url)
    {
        $tokenData = AUTHORIZATION::getUserInfoByToken($token);
        if (empty($tokenData->id) || empty($tokenData->phone) || $tokenData->expiry_time < time())
        {
            throw new Exception('TOKEN_NOT_CORRECT', TOKEN_NOT_CORRECT);
        }

        $this->CI->load->model('user/user_model');
        $user_info = $this->CI->user_model->get($tokenData->id);
        if ($tokenData->auth_otp != $user_info->auth_otp)
        {
            throw new Exception('TOKEN_NOT_CORRECT', TOKEN_NOT_CORRECT);
        }

        if ($user_info->block_status != 0)
        {
            throw new Exception('BLOCK_USER', BLOCK_USER);
        }

        if ($request_method != 'get')
        {
            $this->CI->load->model('log/log_request_model');
            $this->CI->log_request_model->insert([
                'method' => $request_method,
                'url' => $url,
                'investor' => $tokenData->investor,
                'user_id' => $tokenData->id,
                'agent' => $tokenData->agent,
            ]);
        }

        $user_info->investor = $tokenData->investor;
        $user_info->company = $tokenData->company;
        $user_info->incharge = $tokenData->incharge;
        $user_info->agent = $tokenData->agent;
        $user_info->expiry_time = $tokenData->expiry_time;
        return $user_info;
    }

    /**
     * 確認負責人實名是否通過
     * @param $company_user_id : 法人使用者編號
     * @param $investor : 借款人/投資人
     * @return int 自然人使用者編號
     * @throws Exception
     */
    public function get_identified_responsible_user($company_user_id, $investor): int
    {
        // 確認負責人需通過實名認證
        $this->CI->load->model('user/user_meta_model');
        $rs = $this->CI->user_meta_model->get_by(['user_id' => $company_user_id, 'meta_key' => 'company_responsible_user_id']);
        if ( ! isset($rs))
        {
            throw new \Exception('法人沒有綁定負責人', NO_RESPONSIBLE_USER_BIND);
        }
        $responsible_user_id = $rs->meta_value;

        $this->CI->load->library('Certification_lib');
        $user_certification = $this->CI->certification_lib->get_certification_info($responsible_user_id, CERTIFICATION_IDCARD,
            $investor);
        if ( ! $user_certification || $user_certification->status != CERTIFICATION_STATUS_SUCCEED)
        {
            throw new \Exception('法人沒有通過負責人實名', NO_RESPONSIBLE_IDENTITY);
        }
        return (int)$responsible_user_id;
    }

}

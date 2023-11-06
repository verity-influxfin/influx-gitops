<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use EdmEvent\EdmEventFactory;

class User_lib {

    /**
     * @var int
     */
    private $totalCount;

    // 特約方案可以改申貸服務費/利息百分比的產品類別
    public $appointed_individual_categories = ['student', 'salary_man'];
    public $appointed_enterprise_categories = ['small_enterprise'];

    public $rewardCategories = [
        'student' => [PRODUCT_ID_STUDENT, PRODUCT_ID_STUDENT_ORDER],
        'salary_man' => [PRODUCT_ID_SALARY_MAN, PRODUCT_ID_SALARY_MAN_ORDER],
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
        'student', 'salary_man', 'small_enterprise', 'fullMember', 'registered'
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
     * @param bool $initialized 是否只獲得初始化資料結構
     * @return array
     */
    public function getPromotedRewardInfo(array $where, string $startDate = '', string $endDate = '', int $limit = 0, int $offset = 0, bool $filterDelayed = FALSE, bool $initialized = FALSE): array
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
                $list[$userQrcodeId]['downloadedCount'] = 0;
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

        if ( ! $initialized)
        {
            // 取得推薦碼下載數
            $this->CI->load->model('behavion/user_behavior_model');
            $firstOpenRs = $this->CI->user_behavior_model->getFirstOpenCountByPromoteCode($promoteCodes, $startDate, $endDate, FALSE);
            $no_duplicate_device_list = [];
            foreach ($firstOpenRs as $rs)
            {
                if ( ! isset($promoteCodeList[$rs['user_qrcode_id']]))
                {
                    continue;
                }
                $settings = json_decode($promoteCodeList[$rs['user_qrcode_id']]['settings'], TRUE) ?? [];
                if (isset($settings['reward']['download']['amount']) && $settings['reward']['download']['amount'] > 0)
                {
                    if ( ! empty($rs['device_id']) && in_array($rs['device_id'], $no_duplicate_device_list[$rs['user_qrcode_id']]))
                    {
                        continue;
                    }
                }
                $no_duplicate_device_list[$rs['user_qrcode_id']][] = $rs['device_id'];
            }
            foreach ($no_duplicate_device_list as $user_qrcode_id => $device_list)
            {
                $list[$user_qrcode_id]['downloadedCount'] = count($device_list);
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

            if ( ! isset($list[$userQrcodeId]['registeredCount']))
            {
                $list[$userQrcodeId]['registeredCount'] = 0;
            }

            if ( ! isset($list[$userQrcodeId]['downloadedCount']))
                $list[$userQrcodeId]['downloadedCount'] = 0;

            if (isset($settings['reward']))
            {
                if(isset($settings['reward']['full_member']))
                {
                    $list[$userQrcodeId]['fullMemberRewardAmount'] = $list[$userQrcodeId]['fullMemberCount'] * intval($settings['reward']['full_member']['amount']);
                    $list[$userQrcodeId]['totalRewardAmount'] += $list[$userQrcodeId]['fullMemberRewardAmount'];
                }

                if (isset($settings['reward']['download']))
                {
                    $list[$userQrcodeId]['downloadRewardAmount'] = $list[$userQrcodeId]['downloadedCount'] * intval($settings['reward']['download']['amount']);
                    $list[$userQrcodeId]['totalRewardAmount'] += $list[$userQrcodeId]['downloadRewardAmount'];
                }
            }
            if (isset($settings['reward']['registered']['amount']))
            {
                $list[$userQrcodeId]['registeredRewardAmount'] = $list[$userQrcodeId]['registeredCount'] * intval($settings['reward']['registered']['amount']);
                $list[$userQrcodeId]['totalRewardAmount'] += $list[$userQrcodeId]['registeredRewardAmount'];
            }
            else
            {
                $list[$userQrcodeId]['registeredRewardAmount'] = 0;
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
     * @param $year : 年
     * @param $month : 月
     * @return int
     */
    public function scriptHandlePromoteReward($year = NULL, $month = NULL): int
    {
        $count = 0;
        $this->CI->load->model('user/user_qrcode_model');

        $tmp_timestamp = time();
        if (empty($year))
        {
            $year = date('Y', $tmp_timestamp);
        }
        if (empty($month))
        {
            $month = date('m', $tmp_timestamp);
        }
        if ( ! ($now = strtotime("{$year}-{$month}")))
        {
            $now = $tmp_timestamp;
        }

        $startTime = date('Y-m-01 00:00:00', strtotime("-1 month", $now));
        $endTime = date('Y-m-01 00:00:00', $now);
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

                $user_qrcode_update_param = ['handle_time' => $today];
                $this->CI->user_qrcode_model->update_by([
                    'id' => $qrcode['id']], $user_qrcode_update_param);
                // 寫 log
                $this->CI->load->model('log/log_user_qrcode_model');
                $user_qrcode_update_param['user_qrcode_id'] = $qrcode['id'];
                $this->CI->log_user_qrcode_model->insert_log($user_qrcode_update_param);

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
        // 負責人實名定義：
        // 1. 負責人的個人實名認證 (CERTIFICATION_IDENTITY)
        // 2. 公司的設立(變更)事項登記表 (CERTIFICATION_GOVERNMENTAUTHORITIES)

        $this->CI->load->model('user/user_meta_model');
        $rs = $this->CI->user_meta_model->get_by(['user_id' => $company_user_id, 'meta_key' => 'company_responsible_user_id']);
        if ( ! isset($rs))
        {
            throw new \Exception('法人沒有綁定負責人', NO_RESPONSIBLE_USER_BIND);
        }
        $responsible_user_id = $rs->meta_value;

        $this->CI->load->library('Certification_lib');
        $user_certification = $this->CI->certification_lib->get_certification_info($responsible_user_id, CERTIFICATION_IDENTITY,
            $investor);
        if ( ! $user_certification || $user_certification->status != CERTIFICATION_STATUS_SUCCEED)
        {
            throw new \Exception('法人沒有通過負責人實名', NO_RESPONSIBLE_IDENTITY);
        }

        $user_certification = $this->CI->certification_lib->get_certification_info($company_user_id, CERTIFICATION_GOVERNMENTAUTHORITIES,
            $investor);
        if ( ! $user_certification || $user_certification->status != CERTIFICATION_STATUS_SUCCEED)
        {
            throw new \Exception('法人沒有通過負責人實名', NO_RESPONSIBLE_IDENTITY);
        }

        return (int)$responsible_user_id;
    }

    /*
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

        $rs = $this->CI->investment_model->get_principle_list($user_id, [], FALSE);
        if ( ! empty($rs))
        {
            $investment = reset($rs);
        }

        $rs = $this->CI->transfer_investment_model->get_principle_list([], $user_id, [], FALSE);
        if ( ! empty($rs))
        {
            $transfer_investment = reset($rs);
        }

        if ( ! empty($investment) && ! isset($transfer_investment))
        {
            $result['tx_date'] = $investment['tx_date'];
            $result['amount'] = $investment['amount'];
        }
        else if (empty($investment) && isset($transfer_investment))
        {
            $result['tx_date'] = $transfer_investment['tx_date'];
            $result['amount'] = $transfer_investment['amount'];
        }
        else if ( ! empty($investment) && isset($transfer_investment))
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
            $rs = $this->CI->transaction_model->get_paid_off_list(SOURCE_PRINCIPAL, $from = [], $to = $user_id, $product_id_list, $is_group = TRUE);
            $paid_off_principle_list = array_column($rs, NULL, 'tx_date');

            $interval_date = $start_date->diff($end_date);
            $days = $interval_date->days;

            $prev_date = clone $start_date;
            $cur_date = clone $start_date;

            for ($i = 0; $i <= $days; $i++)
            {
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
        $display_product_ids = ! empty($product_id_list) ? $product_id_list : [PRODUCT_ID_STUDENT, PRODUCT_ID_STUDENT_ORDER, PRODUCT_ID_SALARY_MAN, PRODUCT_ID_SALARY_MAN_ORDER];
        $combine_product_ids = [PRODUCT_ID_STUDENT_ORDER => PRODUCT_ID_STUDENT, PRODUCT_ID_SALARY_MAN_ORDER => PRODUCT_ID_SALARY_MAN];

        $data = [
            "basic_info" => [
                "id" => "{$user_id}",
                "first_invest_date" => "-",
                "invest_amount" => "0",
                'export_date' => "{$export_date}"
            ],
            "assets_description" => [
                $product_list[PRODUCT_ID_STUDENT]['alias'] => [
                    "name" => "學生貸",
                    "amount_not_delay" => "0",
                    "total_amount" => "0",
                    "amount_delay" => "0"
                ],
                $product_list[PRODUCT_ID_SALARY_MAN]['alias'] => [
                    "name" => "上班族貸",
                    "amount_not_delay" => "0",
                    "total_amount" => "0",
                    "amount_delay" => "0"
                ],
                'total' => [
                    "name" => "本金餘額",
                    "amount_not_delay" => "0",
                    "total_amount" => "0",
                    "amount_delay" => "0"
                ]
            ],
            "invest_performance" => [
                'years' => 0,
            ],
            "realized_rate_of_return" => [
            ],
            "account_payable_interest" => [
            ],
            "delay_not_return" => [
                'principal_and_interest' => 0,
                'delay_interest' => 0,
                'total' => 0
            ],
            'estimate_IRR' => 0.161
        ];

        // -- 投資人資訊
        $this->CI->load->model('loan/investment_model');
        $this->CI->load->model('loan/transfer_investment_model');
        $first_investment = $this->get_first_investment_info($user_id);
        if ( ! empty($first_investment) && $first_investment['amount'])
        {
            $data['basic_info']['first_invest_date'] = date('Y/m/d', strtotime($first_investment['tx_date']));
            $data['basic_info']['invest_amount'] = $first_investment['amount'];
        }

        // -- 資產概況
        // 正常還款本金餘額
        $principal_balance = $this->CI->target_model->getTransactionSourceByInvestor($user_id, FALSE, [SOURCE_AR_PRINCIPAL], $product_id_list, TRUE);
        if ( ! empty($principal_balance))
        {
            $principal_balance = array_column($principal_balance, 'amount', 'product_id');
        }
        // 逾期中本金餘額
        $principal_balance_delay = $this->CI->target_model->getTransactionSourceByInvestor($user_id, TRUE, [SOURCE_AR_PRINCIPAL], $product_id_list, TRUE);
        if ( ! empty($principal_balance_delay))
        {
            $principal_balance_delay = array_column($principal_balance_delay, 'amount', 'product_id');
        }

        $amount_not_delay_all = 0;
        $amount_delay_all = 0;
        $total_amount_all = 0;
        foreach ($display_product_ids as $product_id)
        {
            $amount_not_delay = isset($principal_balance[$product_id]) && is_numeric($principal_balance[$product_id]) ? $principal_balance[$product_id] : 0;
            $amount_delay = isset($principal_balance_delay[$product_id]) && is_numeric($principal_balance_delay[$product_id]) ? $principal_balance_delay[$product_id] : 0;
            $total_amount = $amount_not_delay + $amount_delay;

            if (array_key_exists($product_id, $combine_product_ids))
            {
                $product_id = $combine_product_ids[$product_id];
            }

            $alias_name = $product_list[$product_id]['alias'];
            if (isset($data['assets_description'][$alias_name]))
            {
                $data['assets_description'][$alias_name]['amount_not_delay'] += $amount_not_delay;
                $data['assets_description'][$alias_name]['amount_delay'] += $amount_delay;
                $data['assets_description'][$alias_name]['total_amount'] += $total_amount;
            }
            else
            {
                $data['assets_description'][$alias_name] = [
                    'name' => $product_list[$product_id]['name'],
                    'amount_not_delay' => $amount_not_delay,
                    'amount_delay' => $amount_delay,
                    'total_amount' => $total_amount,
                ];
            }

            // 全部總和
            $amount_not_delay_all += $amount_not_delay;
            $amount_delay_all += $amount_delay;
            $total_amount_all += $total_amount;
        }
        $data['assets_description']['total'] = [
            'name' => '本金餘額',
            'amount_not_delay' => $amount_not_delay_all,
            'amount_delay' => $amount_delay_all,
            'total_amount' => $total_amount_all,
        ];

        // -- 已實現收益率
        $generate_RoR_init_list = function (DateTimeImmutable $start_date, DateTimeImmutable $end_date) {
            $diff = $start_date->diff($end_date);
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
                'days' => $diff->days + 1,
                'diff_month' => $diff->y * 12 + $diff->m + 1
            ];
        };

        if ( ! empty($first_investment) && $first_investment['amount'])
        {
            try
            {
                // -- 已實現收益率
                $first_invest_date = new \DateTimeImmutable(date('Y-m-d', strtotime($first_investment['tx_date'])));
                $start_date = new \DateTimeImmutable($first_invest_date->format('Y-01-01'));
                $end_date = new \DateTimeImmutable(date('Y-m-t', strtotime("-1 month")));
                $RoR_List = [];

                // 建立表格結構
                $diff = $start_date->diff($end_date);
                for ($i = 0; $i <= $diff->y; $i++)
                {
                    $next_year_date = $start_date->add(DateInterval::createfromdatestring("+{$i} year"));
                    $year_str = $next_year_date->format('Y');
                    $start_year_date = $next_year_date->setDate($year_str, 1, 1);
                    $end_year_date = $next_year_date->setDate($year_str, 12, 31);
                    if ($first_invest_date->diff($start_year_date)->invert)
                    {
                        $start_year_date = $first_invest_date;
                    }
                    else if ($end_date->format('Y') == $year_str)
                    {
                        $end_year_date = $end_date;
                    }

                    $RoR_List[$year_str] = $generate_RoR_init_list($start_year_date, $end_year_date);
                }
                $RoR_List['total'] = $generate_RoR_init_list($start_date, $end_date);

                // 取得每天之本金餘額
                $principle_list = $this->get_principle_list($user_id, $product_id_list, $start_date, $end_date);
                foreach ($principle_list as $date => $info)
                {
                    $ym_date = new \DateTimeImmutable($date);
                    $ym_date_str = $ym_date->format('Y');
                    $RoR_List[$ym_date_str]['principle_list'][] = $info['principle_balance'];
                }

                // 計算本金均額
                for ($i = 0; $i <= $diff->y; $i++)
                {
                    $year = $start_date->add(DateInterval::createfromdatestring("+{$i} year"));
                    $year_str = $year->format('Y');
                    $RoR_List[$year_str]['average_principle'] = round(array_sum($RoR_List[$year_str]['principle_list']) / $RoR_List[$year_str]['days']);
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
                            $RoR_List[$year_str]['interest'] += $info['amount'];
                            break;
                        case SOURCE_DELAYINTEREST:
                            $RoR_List[$year_str]['delayed_interest'] += $info['amount'];
                            break;
                        case SOURCE_PREPAYMENT_ALLOWANCE:
                            $RoR_List[$year_str]['allowance'] += $info['amount'];
                            break;
                    }
                }

                // 提還利息
                $prepaid_interest_list = $this->CI->transaction_model->get_prepaid_transactions(SOURCE_INTEREST, $user_id, $product_id_list, $is_group = TRUE);
                foreach ($prepaid_interest_list as $info)
                {
                    $ym_date = new \DateTimeImmutable($info['tx_date']);
                    $year_str = $ym_date->format('Y');
                    $RoR_List[$year_str]['prepaid_interest'] += $info['amount'];
                }

                // 逾期後償還利息
                $delayed_interest_list = $this->CI->transaction_model->get_delayed_paid_transaction(SOURCE_INTEREST, $user_id, $product_id_list, $is_group = TRUE);
                foreach ($delayed_interest_list as $info)
                {
                    $ym_date = new \DateTimeImmutable($info['tx_date']);
                    $year_str = $ym_date->format('Y');
                    $RoR_List[$year_str]['delayed_paid_interest'] += $info['amount'];
                }

                // 平台服務費支出
                $expense_list = $this->CI->transaction_model->get_paid_off_list([SOURCE_FEES], $from = $user_id, $to = [], $product_id_list, $is_group = TRUE);
                foreach ($expense_list as $info)
                {
                    $ym_date = new \DateTimeImmutable($info['tx_date']);
                    $year_str = $ym_date->format('Y');
                    $RoR_List[$year_str]['platform_fee'] += $info['amount'];
                }

                // 轉換為每年區間的統計數據
                for ($i = 0; $i <= $diff->y; $i++)
                {
                    $year = $start_date->add(DateInterval::createfromdatestring("+{$i} year"));
                    $year_str = $year->format('Y');
                    $RoR_List[$year_str]['interest'] -= $RoR_List[$year_str]['prepaid_interest'] + $RoR_List[$year_str]['delayed_paid_interest'];
                    $RoR_List[$year_str]['total_income'] = $RoR_List[$year_str]['interest'] + $RoR_List[$year_str]['delayed_interest'] +
                        $RoR_List[$year_str]['prepaid_interest'] + $RoR_List[$year_str]['delayed_paid_interest'] +
                        $RoR_List[$year_str]['allowance'] - $RoR_List[$year_str]['platform_fee'];
                    $RoR_List[$year_str]['rate_of_return'] = $RoR_List[$year_str]['average_principle'] != 0 ? round($RoR_List[$year_str]['total_income'] / $RoR_List[$year_str]['average_principle'] / $RoR_List[$year_str]['diff_month'] * 12 * 100, 2) : 0;
                    $RoR_List[$year_str]['range_title'] = date('Ym', strtotime($RoR_List[$year_str]['start_date'])) . '-' . date('Ym', strtotime($RoR_List[$year_str]['end_date']));

                    $RoR_List['total']['average_principle'] += round($RoR_List[$year_str]['average_principle'] * ($RoR_List[$year_str]['days'] / $RoR_List['total']['days']));
                    $RoR_List['total']['interest'] += $RoR_List[$year_str]['interest'];
                    $RoR_List['total']['delayed_interest'] += $RoR_List[$year_str]['delayed_interest'];
                    $RoR_List['total']['prepaid_interest'] += $RoR_List[$year_str]['prepaid_interest'];
                    $RoR_List['total']['delayed_paid_interest'] += $RoR_List[$year_str]['delayed_paid_interest'];
                    $RoR_List['total']['allowance'] += $RoR_List[$year_str]['allowance'];
                    $RoR_List['total']['platform_fee'] += $RoR_List[$year_str]['platform_fee'];
                    $RoR_List['total']['total_income'] += $RoR_List[$year_str]['total_income'];
                }
                $RoR_List['total']['rate_of_return'] = $RoR_List['total']['average_principle'] != 0 ? round($RoR_List['total']['total_income'] / $RoR_List['total']['average_principle'] / $RoR_List['total']['diff_month'] * 12 * 100, 2) : 0;
                $RoR_List['total']['range_title'] = '累計收益率';
                $data['realized_rate_of_return'] = array_values($RoR_List);

                // -- 待實現應收利息
                $ar_interest_list = [];
                $ar_interest = $this->CI->transaction_model->get_account_payable_list(SOURCE_AR_INTEREST, $from = [], $to = $user_id,
                    $product_id_list, $is_group = TRUE);
                foreach ($ar_interest as $info)
                {
                    $ym_date = new \DateTimeImmutable($info['tx_date']);
                    $year_str = $ym_date->format('Y');
                    if ( ! array_key_exists($year_str, $ar_interest_list))
                    {
                        $ar_interest_list[$year_str] = [
                            'amount' => $info['amount'],
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

                $data['estimate_IRR'] = 0.161;
                foreach ($ar_interest_list as $year_str => $info)
                {
                    $end = new \DateTimeImmutable($info['end_date']);
                    $diff = $end_date->setDate($end_date->format('Y'), $end_date->format('m'), 1)->diff($end);
                    $ar_interest_list[$year_str]['range_title'] = date('Ym', strtotime($info['start_date'])) . '-' . date('Ym', strtotime($info['end_date']));
                }
                $ar_interest_list['total']['range_title'] = '合計';
                $ar_interest_list['total']['amount'] = array_sum(array_column($ar_interest_list, 'amount'));
                $data['account_payable_interest'] = array_values($ar_interest_list);

                // -- 逾期未收
                $delayed_ar_list_rs = $this->CI->transaction_model->get_delayed_ar_transaction([SOURCE_AR_PRINCIPAL, SOURCE_AR_INTEREST, SOURCE_AR_DELAYINTEREST], $user_id, $product_id_list, $is_group = TRUE);
                $delayed_ar_list = [];
                array_walk($delayed_ar_list_rs, function ($item, $key) use (&$delayed_ar_list) {
                    $source = $item['source'];
                    $delayed_ar_list[$source] = isset($delayed_ar_list[$source]) ? $item['amount'] + $delayed_ar_list[$source] : $item['amount'];
                });

                $data['delay_not_return']['principal_and_interest'] = ($delayed_ar_list[SOURCE_AR_PRINCIPAL] ?? 0) + ($delayed_ar_list[SOURCE_AR_INTEREST] ?? 0);
                $data['delay_not_return']['delay_interest'] = ($delayed_ar_list[SOURCE_AR_DELAYINTEREST] ?? 0);
                $data['delay_not_return']['total'] = $data['delay_not_return']['principal_and_interest'] + $data['delay_not_return']['delay_interest'];

                // -- 投資績效
                try
                {
                    $d1 = new DateTime($first_investment['tx_date']);
                    $d2 = new DateTime($export_date);
                    $data['invest_performance']['years'] = round($d1->diff($d2)->days / 365.0, 1);
                }
                catch (Exception $e)
                {
                    log_message('error', $e->getMessage());
                }

            }
            catch (Exception $e)
            {
                log_message('error', $e->getMessage());
            }
        }

        // -- start row of every part for the layout
        $data['start_row']['realized_rate_of_return'] = 12;
        $data['start_row']['account_payable_interest'] = $data['start_row']['realized_rate_of_return'] + count($data['realized_rate_of_return'] ?? []) + 6;;
        $account_payable_cnt = count($data['account_payable_interest'] ?? []);
        $data['start_row']['delay_not_return'] = $data['start_row']['account_payable_interest'] + ($account_payable_cnt != 0 ? $account_payable_cnt : 1) + 3;

        return $data;
    }

    /**
     * 檢查是否有相同統編之使用者存在
     * @param $tax_id : 統編 (users.id_number)
     * @return array
     */
    public function get_exist_company_user_id($tax_id): array
    {
        $this->CI->load->model('user/user_model');
        $result = $this->CI->user_model->get_exit_judicial_person($tax_id);
        return ['id' => $result['user_id']];
    }

    /**
     * 以手機號碼取得相同負責人之公司資訊
     * @param $phone : 手機號碼 (users.phone)
     * @return array
     */
    public function get_all_certificated_companies_by_phone($phone): array
    {
        $companies_info = $this->CI->user_model->as_array()->get_many_by([
            'phone' => $phone,
            'company_status' => USER_IS_COMPANY,
            'status' => 1
        ]);

        if (empty($companies_info))
        {
            return [];
        }

        $result = [];
        foreach ($companies_info as $single_company)
        {
            $result[] = [
                'id' => $single_company['id'],
                'name' => $single_company['name'], // 公司名稱
                'tax' => $single_company['id_number'], // 統一編號
            ];
        }
        return $result;
    }

    /**
     * 檢查使用者帳號的有效性
     * @param $user_id : 使用者帳號
     * @param $tax_id : 統一編號
     * @throws Exception
     */
    public function check_user_id_validation($user_id, $tax_id)
    {
        // 檢查帳號格式
        $this->check_user_id_format($user_id);
        // 檢查帳號是否存在
        $this->check_distinct_user_id($user_id, $tax_id);
    }

    /**
     * 檢查使用者帳號格式
     * @param $user_id : 使用者帳號
     * @return void
     * @throws Exception
     */
    public function check_user_id_format($user_id)
    {
        if ( ! preg_match("/(?=.{9})(?=.*[A-Z])(?=.*[a-z])(?=.*\d)/", $user_id))
        {
            throw new Exception('帳號格式有誤', USER_ID_FORMAT_ERROR);
        }
    }

    /**
     * 檢查使用者帳號是否存在
     * @param $user_id : 使用者帳號
     * @param $tax_id : 統一編號
     * @return void
     * @throws Exception
     */
    public function check_distinct_user_id($user_id, $tax_id)
    {
        $this->CI->load->model('user/user_model');
        $company_user_id_exist = $this->CI->user_model->check_user_id_exist($user_id, $tax_id);
        if ( ! empty($company_user_id_exist))
        {
            throw new Exception('公司帳號已存在，請使用其他公司帳號', USER_ID_EXIST);
        }
    }

    /**
     * 檢查密碼
     * @param $password : 密碼
     * @return void
     * @throws Exception
     */
    public function check_password($password)
    {
        if (strlen($password) < PASSWORD_LENGTH || strlen($password) > PASSWORD_LENGTH_MAX)
        {
            throw new Exception('密碼長度有誤', PASSWORD_LENGTH_ERROR);
        }
    }

    /**
     * 檢查統一編號
     * @param $tax_id : 統一編號
     * @return void
     * @throws Exception
     */
    public function check_tax_id($tax_id)
    {
        // 檢查統編
        if (strlen($tax_id) != 8)
        {
            throw new Exception('統編長度有誤', TAX_ID_LENGTH_ERROR);
        }
    }

    // 取得相同負責人的公司列表，及其負責人實名的情況
    // 0:未提交 1:已通過 2:審核中
    public function get_company_list_with_identity_status($phone)
    {
        $this->CI->load->model('user/user_model');
        $result = $this->CI->user_model->get_company_list_with_identity_status($phone);
        array_walk($result, function (&$element) {
            switch ($element['status'])
            {
                case NULL:
                    $element['status'] = 0;
                    break;
                case CERTIFICATION_STATUS_SUCCEED:
                    $element['status'] = 1;
                    break;
                default:
                    $element['status'] = 2;
            }
        });
        return $result;
    }
}

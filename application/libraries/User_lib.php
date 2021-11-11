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
    public function getVirtualAccountPrefix(int $investor, int $product_id=0): string
    {
        switch ($investor) {
            case USER_BORROWER:
                return CATHAY_VIRTUAL_CODE.BORROWER_VIRTUAL_CODE;
                break;
            case USER_INVESTOR:
                return CATHAY_VIRTUAL_CODE.INVESTOR_VIRTUAL_CODE;
                break;
        }
    }

}

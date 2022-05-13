<?php

defined('BASEPATH') or exit('No direct script access allowed');

use EdmEvent\EdmEventFactory;

class Qrcode_lib
{

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('log/log_userlogin_model');
        $this->CI->load->model('user/user_model');
    }

    /**
     * 取得 promote_code 的合約格式對應內容
     * @param string $type_name
     * @param string $name
     * @param string $address
     * @param array $settings
     * @param string $contract_date
     * @return array
     */
    public function get_contract_format_content(string $type_name, string $name = '', string $address = '', array $settings = [], string $contract_date = ''): array
    {
        if ( ! empty($contract_date))
        {
            preg_match('/(\d+)\-(\d+)\-(\d+)/', $contract_date, $regexResult);
            if ( ! empty($regexResult))
            {
                $contract_year = $regexResult[1];
                $contract_month = $regexResult[2];
                $contract_day = $regexResult[3];
            }
        }

        if (empty($contract_date))
        {
            $time = time();
            $contract_year = date('Y', $time) - 1911;
            $contract_month = date('m', $time);
            $contract_day = date('d', $time);
        }

        switch ($type_name)
        {
            case PROMOTE_GENERAL_CONTRACT_TYPE_NAME:
                return [$name, $contract_year, $contract_month, $contract_day,
                    $settings['reward']['product']['student']['amount'] ?? 0, $settings['reward']['product']['salary_man']['amount'] ?? 0,
                    $settings['reward']['product']['small_enterprise']['amount'] ?? 0,
                    $name, $name, $address,
                    $contract_year, $contract_month, $contract_day];
                break;
            case PROMOTE_APPOINTED_CONTRACT_TYPE_NAME:
                return [$name, $contract_year, $contract_month, $contract_day,
                    $settings['reward']['product']['student']['borrower_percent'] ?? 0, $settings['reward']['product']['salary_man']['investor_percent'] ?? 0,
                    $settings['reward']['collaboration_person']['amount'] ?? 0, $settings['reward']['collaboration_enterprise']['amount'] ?? 0,
                    $name, $name, $address,
                    $contract_year, $contract_month, $contract_day];
                break;
        }
        return [];
    }

    public function get_contract_type_by_alias($alias): string
    {
        $this->CI->load->model('user/qrcode_setting_model');
        $contract_type_name = '';
        if ($alias == $this->CI->qrcode_setting_model->generalCaseAliasName)
        {
            $contract_type_name = PROMOTE_GENERAL_CONTRACT_TYPE_NAME;
        }
        else if ($alias == $this->CI->qrcode_setting_model->appointedCaseAliasName)
        {
            $contract_type_name = PROMOTE_APPOINTED_CONTRACT_TYPE_NAME;
        }
        return $contract_type_name;
    }

    private function filter_delayed_target($data, array $targetIds, $needle)
    {
        if ( ! empty($targetIds))
        {
            $delayedTargets = $this->CI->target_model->getDelayedTarget($targetIds);
            $delayedTargets = array_column($delayedTargets, NULL, $needle);
            foreach ($data as $key => $info)
            {
                if (array_key_exists($info[$needle], $delayedTargets))
                    unset($data[$key]);
            }
            $data = array_values($data);
        }
        return $data;
    }

    public function get_product_reward_list(array $qrcode_where, array $product_id_list, array $status_list, string $start_time = '', string $end_time = '', bool $filter_delayed = FALSE): array
    {
        $this->CI->load->model('user/user_qrcode_model');
        $rewardList = $this->CI->user_qrcode_model->getLoanedCount($qrcode_where, $product_id_list, $status_list, $start_time, $end_time);
        if ($filter_delayed)
        {
            $rewardList = $this->filter_delayed_target($rewardList, array_column($rewardList, 'id'), 'id');
        }
        return $rewardList;
    }

    public function get_borrower_platform_fee_list(array $qrcode_where, array $product_id_list, array $status_list, string $start_time = '', string $end_time = '', bool $filter_delayed = FALSE)
    {
        $this->CI->load->model('user/user_qrcode_model');
        $rewardList = $this->CI->user_qrcode_model->getBorrowerPlatformFeeList($qrcode_where, $product_id_list, $status_list, $start_time, $end_time);
        if ($filter_delayed)
        {
            $rewardList = $this->filter_delayed_target($rewardList, array_column($rewardList, 'id'), 'id');
        }
        return $rewardList;
    }

    public function get_investor_platform_fee_list(array $qrcode_where, array $product_id_list, array $status_list, string $start_time = '', string $end_time = '', bool $filter_delayed = FALSE)
    {
        $this->CI->load->model('user/user_qrcode_model');
        $rewardList = $this->CI->user_qrcode_model->getInvestorPlatformFeeList($qrcode_where, $product_id_list, $status_list, $start_time, $end_time);
        if ($filter_delayed)
        {
            $rewardList = $this->filter_delayed_target($rewardList, array_column($rewardList, 'id'), 'id');
        }
        return $rewardList;
    }

    public function get_company_basic_info(int $user_qrcode_id)
    {
        $data = [
            "company_user_id" => 0,
            "company_name" => '',
            "responsible_name" => '',
            "tax" => 0,
            "phone" => '',
            "bank_name" => '國泰世華銀行',
            "bank_account_name" => '',
            'virtual_account' => '',
            'investor' => '',
        ];

        $this->CI->load->model('user/user_qrcode_model');
        $this->CI->load->model('user/judicial_person_model');
        $this->CI->load->model('user/user_certification_model');
        $this->CI->load->model('user/virtual_account_model');
        $user_qrcode = $this->CI->user_qrcode_model->get($user_qrcode_id);
        if (isset($user_qrcode))
        {
            $judicial_person = $this->CI->judicial_person_model->get_by(['company_user_id' => $user_qrcode->user_id]);
            if ( ! isset($judicial_person))
            {
                return $data;
            }
            $user = $this->CI->user_model->get($judicial_person->user_id);
            $settings = json_decode($user_qrcode->settings, TRUE);
            $promote_cert_list = $settings['certification_id'];

            $param = array(
                'user_id' => $judicial_person->company_user_id,
                'id' => $promote_cert_list,
                'status' => [CERTIFICATION_STATUS_SUCCEED],
            );
            $cert_list = $this->CI->user_certification_model->order_by('created_at', 'desc')->get_many_by($param);
            if ( ! empty($cert_list))
            {
                $cert = reset($cert_list);
                $investor = [$cert->investor];
            }
            else
            {
                $investor = [USER_BORROWER, USER_INVESTOR];
            }
            $virtual_account = $this->CI->virtual_account_model->get_by(array("status" => [VIRTUAL_ACCOUNT_STATUS_AVAILABLE,
                VIRTUAL_ACCOUNT_STATUS_USING], "investor" => $investor, "user_id" => $judicial_person->company_user_id));

            $data = [
                "company_user_id" => $judicial_person->company_user_id,
                "company_name" => $judicial_person->company,
                "responsible_name" => $user->name ?? '',
                "tax" => $judicial_person->tax_id,
                "phone" => $judicial_person->cooperation_phone,
                "bank_name" => '國泰世華銀行',
                "bank_account_name" => $judicial_person->company,
                "virtual_account" => $virtual_account->virtual_account,
                "investor" => $virtual_account->investor,
            ];
        }
        return $data;
    }

    public function insert_statement_pdf($qrcode_reward_id)
    {
        $this->CI->load->model('transaction/qrcode_reward_model');
        $this->CI->load->model('user/qrcode_collaborator_model');
        $this->CI->load->model('user/user_estatement_model');
        $this->CI->load->library('user_lib');
        $categoryInitNum = array_combine(array_keys($this->CI->user_lib->rewardCategories), array_fill(0, count($this->CI->user_lib->rewardCategories), 0));

        $qrcode_reward = $this->CI->qrcode_reward_model->get($qrcode_reward_id);
        if ( ! isset($qrcode_reward) || isset($qrcode_reward->notified_at))
        {
            return FALSE;
        }

        $start_time = $qrcode_reward->start_time;
        $end_time = $qrcode_reward->end_time;
        $data = [
            "send_time" => date('Y-m-d'),
            "start_time" => date('Y-m-d', strtotime($start_time)),
            "end_time" => date('Y-m-d', strtotime($end_time)),
            "reward_list" => [],
            "list" => [],
        ];
        $data = array_replace_recursive($data, $this->get_company_basic_info($qrcode_reward->user_qrcode_id));
        $reward_data = json_decode($qrcode_reward->json_data, TRUE);

        // 實領金額
        $data['reward_amount'] = number_format($reward_data['originRewardAmount']);

        // 初始化結構
        try
        {
            $d1 = new DateTime($start_time);
            $d2 = new DateTime($end_time);
            $start = date_create($d1->format('Y-m-d'));
            $end = date_create($d2->format('Y-m-t'));
            $diffMonths = $start->diff($end)->m + ($start->diff($end)->y * 12) + ($start->diff($end)->d > 0 ? 1 : 0);
        }
        catch (Exception $e)
        {
            $diffMonths = 0;
            error_log($e->getMessage());
        }

        // 計算產品的獎勵明細
        for ($i = 0; $i <= $diffMonths; $i++)
        {
            $date = date("Y-m", strtotime(date("Y-m", strtotime($start_time)) . '+' . $i . ' MONTH'));
            if (isset($reward_data['monthly_rewards'][$date]))
            {
                $monthly_data = $reward_data['monthly_rewards'];

                foreach (array_keys($categoryInitNum) as $category)
                {
                    if ( ! isset($monthly_data[$date][$category]['targets']) || empty($monthly_data[$date][$category]['targets']))
                    {
                        continue;
                    }

                    // 將每筆獎勵轉為日結算
                    foreach ($monthly_data[$date][$category]['targets'] as $detail_list)
                    {
                        foreach ($detail_list as $value)
                        {
                            if ( ! isset($value['enteringDate']))
                                continue;

                            $formattedMonth = date("Y-m-d", strtotime($value['enteringDate']));
                            if ( ! isset($data['reward_list'][$formattedMonth]))
                            {
                                $data['reward_list'][$formattedMonth] = $categoryInitNum;
                            }
                            $data['reward_list'][$formattedMonth][$category] +=
                                ($value['rewardAmount'] ?? 0);
                        }
                    }
                }
            }
        }

        $collaboratorList = json_decode(json_encode($this->CI->qrcode_collaborator_model->get_all(['status' => 1])), TRUE) ?? [];
        $collaboratorList = array_column($collaboratorList, NULL, 'id');
        $data['collaboratorList'] = $collaboratorList;

        if (isset($reward_data['collaboration']))
        {
            foreach ($reward_data['collaboration'] as $collaborator_id => $collaboration_list)
            {
                foreach ($collaboration_list as $value)
                {
                    if (empty($value['rewardAmount']))
                        continue;

                    $formattedMonth = date("Y-m-d", strtotime($value['loan_time']));
                    if ( ! isset($data['reward_list'][$formattedMonth]))
                    {
                        $data['reward_list'][$formattedMonth] = ['collaboration' => []];
                    }
                    $data['reward_list'][$formattedMonth]['collaboration'][$collaborator_id] =
                        ($data['reward_list'][$formattedMonth]['collaboration'][$collaborator_id] ?? 0) + ($value['rewardAmount'] ?? 0);
                }
            }
        }

        $data['list'] = [];
        foreach ($data['reward_list'] as $date => $value)
        {
            foreach (array_keys($categoryInitNum) as $category)
            {
                if ( ! isset($value[$category]) || ! $value[$category])
                    continue;
                $data['list'][] = [
                    'date' => $date,
                    'summary' => '成功推薦' . $this->CI->user_lib->categoriesName[$category] . '獎金',
                    'amount' => number_format($value[$category])
                ];
            }
            if (isset($value['collaboration']))
            {
                foreach ($value['collaboration'] as $collaborator_id => $amount)
                {
                    $data['list'][] = [
                        'date' => $date,
                        'summary' => '成功推薦普匯合作產品' . $collaboratorList[$collaborator_id]['collaborator'] . '獎金',
                        'amount' => number_format($amount)
                    ];
                }
            }
        }

        // 逾期追回處理
        $dockAmount = 0;
        foreach (array_keys($categoryInitNum) as $category)
        {
            if ( ! isset($reward_data['dockList'][$category]) || ! $reward_data['dockList'][$category])
                continue;
            $dockAmount += $reward_data['dockList'][$category];
        }
        if ($dockAmount)
        {
            $data['list'][] = [
                'date' => $end_time,
                'summary' => '逾期案件追回獎金',
                'amount' => number_format(-$dockAmount)
            ];
        }

        $this->CI->load->library('parser');
        $html = $this->CI->parser->parse('admin/promote_code/appointed_statement', $data, TRUE);

        $param = [];

        $this->CI->load->library('judicialperson_lib');
        $emails = $this->CI->judicialperson_lib->get_company_email_list($data['company_user_id']);
        foreach ($emails as $user_id => $email)
        {
            $param[] = [
                "user_id" => $user_id,
                "type" => "promote_code",
                "investor" => $data['investor'],
                "sdate" => $start_time,
                "edate" => $end_time,
                "content" => $html,
                "url" => "",
            ];
        }

        return $this->CI->user_estatement_model->insert_many($param);
    }

    public function is_company(string $alias): bool
    {
        $this->CI->load->model('user/qrcode_setting_model');
        return $alias == $this->CI->qrcode_setting_model->appointedCaseAliasName;
    }

    /**
     * 取得使用者實名認證徵信項
     * @param $registered_id
     * @return false
     */
    public function get_user_identity($registered_id)
    {
        $this->CI->load->model('user/user_model');
        $this->CI->load->library('certification_lib');

        $identity = FALSE;
        $subcode_user = $this->CI->user_model->get_by(['id_number' => $registered_id, 'block_status != ' => 1, 'company_status' => USER_NOT_COMPANY]);
        if (isset($subcode_user))
        {
            $borrower = $this->CI->certification_lib->get_certification_info($subcode_user->id, CERTIFICATION_IDENTITY, USER_BORROWER);
            if ($borrower !== FALSE)
            {
                $identity = $borrower;
            }
            else
            {
                $investor = $this->CI->certification_lib->get_certification_info($subcode_user->id, CERTIFICATION_IDENTITY, USER_INVESTOR);
                $identity = $investor;
            }
        }

        return $identity;
    }

    /**
     * 取得 subcode 列表
     * @param $company_user_id
     * @param $conditions
     * @return array
     */
    public function get_subcode_list($company_user_id, $conditions, $subcode_conditions=[]): array
    {
        if (empty($company_user_id))
        {
            return [];
        }
        $this->CI->load->model('user/user_qrcode_model');
        $this->CI->load->model('user/user_subcode_model');
        $master_user_qrcode = $this->CI->user_qrcode_model->get_by(['user_id' => $company_user_id, 'status' => PROMOTE_STATUS_AVAILABLE]);
        if ( ! isset($master_user_qrcode))
        {
            return [];
        }

        return $this->CI->user_subcode_model->get_subcode_list($master_user_qrcode->id, $conditions, $subcode_conditions);
    }

    /**
     * 更新 subcode 資訊
     * @param $subcode_id
     * @param $subcode_param
     * @param $qrcode_param
     * @return bool
     */
    public function update_subcode_info($subcode_id, $subcode_param, $qrcode_param): bool
    {
        $this->CI->load->model('user/user_qrcode_model');
        $this->CI->load->model('user/user_subcode_model');
        $this->CI->user_qrcode_model->trans_begin();
        $this->CI->user_subcode_model->trans_begin();
        $rollback = function () {
            $this->CI->user_qrcode_model->trans_rollback();
            $this->CI->user_subcode_model->trans_rollback();
        };

        try {
            $subcode = $this->CI->user_subcode_model->get($subcode_id);
            if(!isset($subcode))
            {
                throw new \Exception('找不到 subcode 紀錄');
            }

            $subcode_rs = $this->CI->user_subcode_model->update_by(['id' => $subcode->id], $subcode_param);
            $qrcode_rs = $this->CI->user_qrcode_model->update_by(['id' => $subcode->user_qrcode_id], $qrcode_param);
            if ( ! $subcode_rs || ! $qrcode_rs ||
                $this->CI->user_qrcode_model->trans_status() === FALSE ||
                $this->CI->user_subcode_model->trans_status() === FALSE)
            {
                throw new \Exception('更新 qrcode 失敗');
            }
            $this->CI->user_qrcode_model->trans_commit();
            $this->CI->user_subcode_model->trans_commit();
            return TRUE;
        }
        catch (Exception $e)
        {
            $rollback();
            return FALSE;
        }
    }

    /**
     * 取得用戶資料與推薦碼相關資訊
     * @param array $user_where
     * @param array $qrcode_where
     * @param int $limit
     * @param int $offset
     * @return mixed
     */
    public function get_user_qrcode_info(array $user_where = [], array $qrcode_where = [], int $limit = 0, int $offset = 0)
    {
        $this->CI->load->model('user/user_qrcode_model');
        $promote_codes_list = $this->CI->user_qrcode_model->getUserQrcodeInfo($user_where, $qrcode_where, $limit, $offset);

        // subcode 的 settings 需換成 main code 的內容
        $promote_codes_list = array_column($promote_codes_list, NULL, 'id');
        $sub_user_qrcode_ids = array_keys(array_filter($promote_codes_list, function ($item) {
            return $item['subcode_flag'] == IS_PROMOTE_SUBCODE;
        }));
        if(!empty($sub_user_qrcode_ids))
        {
            $this->CI->load->model('user/user_subcode_model');
            $subcode_list = $this->CI->user_subcode_model->get_subcode_by_id($sub_user_qrcode_ids);
            $subcode_list = array_column($subcode_list, NULL, 'user_qrcode_id');
            $master_qrcode_ids = array_unique(array_column($subcode_list, 'master_user_qrcode_id'));
            $master_qrcode_list = $this->CI->user_qrcode_model->getUserQrcodeInfo([], ['id' => $master_qrcode_ids], 0, 0);
            $master_qrcode_list = array_column($master_qrcode_list, NULL, 'id');
            foreach ($sub_user_qrcode_ids as $sub_qrcode_id)
            {
                $master_qrcode_id = $subcode_list[$sub_qrcode_id]['master_user_qrcode_id'] ?? 0;
                if (array_key_exists($sub_qrcode_id, $promote_codes_list) &&
                    array_key_exists($master_qrcode_id, $master_qrcode_list))
                {
                    $promote_codes_list[$sub_qrcode_id]['settings'] = $master_qrcode_list[$master_qrcode_id]['settings'];
                }
            }
        }
        return $promote_codes_list;
    }

    /**
     * 取得推薦碼獎勵及相關資訊
     * @param array $where
     * @param string $start_date
     * @param string $end_date
     * @param int $limit
     * @param int $offset
     * @param bool $filter_delayed: 是否要過濾逾期案
     * @param bool $merge_subcode: 是否合併 subcode 資訊至主推薦碼
     * @return array
     */
    public function get_promoted_reward_info(array $where, string $start_date = '', string $end_date = '', int $limit = 0, int $offset = 0, bool $filter_delayed = FALSE, bool $merge_subcode = TRUE): array
    {
        $this->CI->load->library('user_lib');

        $main_qrcode_reward_list = $this->CI->user_lib->getPromotedRewardInfo($where, $start_date, $end_date, $limit, $offset, $filter_delayed);
        if (empty($main_qrcode_reward_list))
        {
            return [];
        }
        $main_qrcode_ids = [];
        $subcode_reward_list = [];
        foreach ($main_qrcode_reward_list as $main_qrcode) {
            $main_qrcode_ids[] = $main_qrcode['info']['id'];
        }
        $this->CI->load->model('user/user_subcode_model');
        $subcode_list = $this->CI->user_subcode_model->get_subcode_list($main_qrcode_ids);
        $subcode_list = array_column($subcode_list, NULL, 'user_qrcode_id');

        $this->CI->load->library('qrcode_lib');
        $user_qrcode_id_list = array_keys( $subcode_list);

        if ( ! empty($subcode_list))
        {
            $where = ['id' => array_values($user_qrcode_id_list)];
            $subcode_reward_list = $this->CI->user_lib->getPromotedRewardInfo($where,
                $start_date, $end_date, $limit, $offset, $filter_delayed);
        }

        if($merge_subcode)
        {
            foreach ($subcode_reward_list as $subcode_reward)
            {
                $user_qrcode_id = $subcode_reward['info']['id'];
                $main_qrcode_id = $subcode_list[$user_qrcode_id]['master_user_qrcode_id'];
                $main_qrcode_reward_list[$main_qrcode_id] = $this->merge_reward_info($main_qrcode_reward_list[$main_qrcode_id], $subcode_reward);
            }
        }
        else
        {
            $main_qrcode_reward_list = array_merge($main_qrcode_reward_list, $subcode_reward_list);
        }
        return $main_qrcode_reward_list;
    }

    /**
     * 合併兩個 qrcode 的獎勵資訊
     * @param array $main_info: 合併目標
     * @param array $info: 合併來源
     * @return array
     */
    public function merge_reward_info(array $main_info, array $info): array
    {
        $this->CI->load->library('user_lib');
        $categoryInitList = array_combine(array_keys($this->CI->user_lib->rewardCategories), array_fill(0, count($this->CI->user_lib->rewardCategories), []));

        // 合併產品相關數據
        foreach (array_keys($this->CI->user_lib->rewardCategories) as $category)
        {
            $main_info[$category] = array_merge($main_info[$category], $info[$category]);
            $main_info['borrowerPlatformFee'][$category] = ($main_info['borrowerPlatformFee'][$category] ?? 0) + $info['borrowerPlatformFee'][$category];
            $main_info['investorPlatformFee'][$category] = ($main_info['investorPlatformFee'][$category] ?? 0) + $info['investorPlatformFee'][$category];
            $main_info['rewardAmount'][$category] = ($main_info['rewardAmount'][$category] ?? 0) + $info['rewardAmount'][$category];
            $main_info['loanedCount'][$category] = ($main_info['loanedCount'][$category] ?? 0) + $info['loanedCount'][$category];
            $main_info['loanedBalance'][$category] = ($main_info['loanedBalance'][$category] ?? 0) + $info['loanedBalance'][$category];
        }

        // 合併月結算結果
        foreach ($info['monthly'] as $month => $item)
        {
            if ( ! isset($main_info['monthly'][$month]))
            {
                $main_info['monthly'][$month] = $categoryInitList;
            }

            foreach (array_keys($this->CI->user_lib->rewardCategories) as $category)
            {
                if (empty($info['monthly'][$month][$category]))
                {
                    continue;
                }
                foreach ($info['monthly'][$month][$category]['targets'] as $target_id => $target_list)
                {
                    // 沒有該類別就直接複製
                    if ( ! isset($main_info['monthly'][$month][$category]))
                    {
                        $main_info['monthly'][$month][$category] = $info['monthly'][$month][$category];
                        continue;
                    }

                    // 合併案件明細
                    foreach ($target_list as $i => $target)
                    {
                        if (is_numeric($i))
                        {
                            $main_info['monthly'][$month][$category]['targets'][$target_id][] = $target;
                        }
                    }

                    // 合併案件列表內的金額
                    if (isset($target_list['borrowerPlatformFee']))
                    {
                        $main_info['monthly'][$month][$category]['targets'][$target_id]['borrowerPlatformFee'] = ($main_info['monthly'][$month][$category]['targets'][$target_id]['borrowerPlatformFee'] ?? 0) + $target_list['borrowerPlatformFee'];
                    }
                    if (isset($target_list['investorPlatformFee']))
                    {
                        $main_info['monthly'][$month][$category]['targets'][$target_id]['investorPlatformFee'] = ($main_info['monthly'][$month][$category]['targets'][$target_id]['investorPlatformFee'] ?? 0) + $target_list['investorPlatformFee'];
                    }
                }

                // 合併案件列表內的金額
                if (isset($info['monthly'][$month][$category]['borrowerPlatformFee']))
                {
                    $main_info['monthly'][$month][$category]['borrowerPlatformFee'] = ($main_info['monthly'][$month][$category]['borrowerPlatformFee'] ?? 0) + $info['monthly'][$month][$category]['borrowerPlatformFee'];
                }
                if (isset($info['monthly'][$month][$category]['investorPlatformFee']))
                {
                    $main_info['monthly'][$month][$category]['investorPlatformFee'] = ($main_info['monthly'][$month][$category]['investorPlatformFee'] ?? 0) + $info['monthly'][$month][$category]['investorPlatformFee'];
                }
                if (isset($info['monthly'][$month][$category]['rewardAmount']))
                {
                    $main_info['monthly'][$month][$category]['rewardAmount'] = ($main_info['monthly'][$month][$category]['rewardAmount'] ?? 0) + $info['monthly'][$month][$category]['rewardAmount'];
                }
            }
        }

        // 第三方合作金額合併
        $this->CI->load->model('user/qrcode_collaborator_model');
        $collaborator_list = $this->CI->qrcode_collaborator_model->db->where(['status' => 1])->get('p2p_user.qrcode_collaborator')->result_array();
        $collaborator_ids = array_column($collaborator_list, 'id');
        foreach ($collaborator_ids as $id)
        {
            if (isset($info['collaboration'][$id]) && ! empty($info['collaboration'][$id]))
            {
                if ( ! isset($main_info['collaboration'][$id]))
                {
                    $main_info['collaboration'][$id] = $info['collaboration'][$id];
                }
                else
                {
                    $main_info['collaboration'][$id] = array_merge($main_info['collaboration'][$id], $info['collaboration'][$id]);
                }
            }
            if (isset($info['collaborationCount'][$id]))
            {
                $main_info['collaborationCount'][$id] = ($main_info['collaborationCount'][$id] ?? 0) + $info['collaborationCount'][$id];
            }
            if (isset($info['collaborationRewardAmount'][$id]))
            {
                $main_info['collaborationRewardAmount'][$id] = ($main_info['collaborationRewardAmount'][$id] ?? 0) + $info['collaborationRewardAmount'][$id];
            }
        }

        // 其他數據合併
        $main_info['totalCollaborationRewardAmount'] = ($main_info['totalCollaborationRewardAmount'] ?? 0) + $info['totalCollaborationRewardAmount'];
        $main_info['fullMemberCount'] = ($main_info['fullMemberCount'] ?? 0) + $info['fullMemberCount'];
        $main_info['fullMember'] = array_merge($main_info['fullMember'], $info['fullMember']);
        $main_info['registeredCount'] = ($main_info['registeredCount'] ?? 0) + $info['registeredCount'];
        $main_info['registered'] = array_merge($main_info['registered'], $info['registered']);
        $main_info['totalRewardAmount'] = ($main_info['totalRewardAmount'] ?? 0) + $info['totalRewardAmount'];
        $main_info['totalLoanedAmount'] = ($main_info['totalLoanedAmount'] ?? 0) + $info['totalLoanedAmount'];
        $main_info['downloadedCount'] = ($main_info['downloadedCount'] ?? 0) + $info['downloadedCount'];
        $main_info['fullMemberRewardAmount'] = ($main_info['fullMemberRewardAmount'] ?? 0) + $info['fullMemberRewardAmount'];
        return $main_info;
    }

    /**
     * 取得 subcode 列表
     * @param $master_user_id
     * @param $investor
     * @param null $start_time
     * @param null $end_time
     * @throws Exception
     * @return array
     */
    public function get_subcode_detail_list($master_user_id, $investor, $start_time=NULL, $end_time=NULL): array
    {
        $this->CI->load->model('user/user_qrcode_model');
        $this->CI->load->model('user/qrcode_setting_model');
        $this->CI->load->model('admin/contract_format_model');
        $this->CI->load->model('user/qrcode_collaborator_model');
        $this->CI->load->library('contract_lib');
        $this->CI->load->library('user_lib');
        $this->CI->load->library('qrcode_lib');
        $list = [];

        $where = ['user_id' => $master_user_id, 'status' => [PROMOTE_STATUS_AVAILABLE],
            'subcode_flag' => IS_NOT_PROMOTE_SUBCODE];
        $user_qrcode = $this->CI->qrcode_lib->get_promoted_reward_info($where);
        if (!isset($user_qrcode) || empty($user_qrcode))
        {
            throw new \Exception('該推薦碼不存在', PROMOTE_CODE_NOT_EXIST);
        }

        $user_qrcode = reset($user_qrcode);
        if($this->is_company($user_qrcode['info']['alias'])) {
            /* @throws Exception */
            $responsible_user = $this->CI->user_lib->get_identified_responsible_user($master_user_id, $investor);
        }

        // 建立合作方案的初始化資料結構
        $collaboratorList = json_decode(json_encode($this->CI->qrcode_collaborator_model->get_many_by(['status' => PROMOTE_COLLABORATOR_AVAILABLE])), TRUE) ?? [];
        $collaboratorList = array_column($collaboratorList, NULL, 'id');
        $collaboratorInitList = array_combine(array_keys($collaboratorList), array_fill(0, count($collaboratorList), ['count' => 0]));
        foreach ($collaboratorInitList as $collaboratorIdx => $value)
        {
            $collaboratorInitList[$collaboratorIdx]['collaborator'] = $collaboratorList[$collaboratorIdx]['collaborator'];
        }

        // 建立各產品的初始化資料結構
        $categoryInitList = array_combine(array_keys($this->CI->user_lib->rewardCategories), array_fill(0, count($this->CI->user_lib->rewardCategories), ['count' => 0]));

        $start_time = $start_time ?? date('Y-m-01 00:00:00');
        $end_time = $end_time ?? date('Y-m-d H:i:s');
        $end_time = max(min($end_time, date('Y-m-d H:i:s')), $start_time);

        try
        {
            $d1 = new DateTime($start_time);
            $d2 = new DateTime($end_time);
            $start = date_create($d1->format('Y-m-d'));
            $end = date_create($d2->format('Y-m-d'));
            $diffMonths = ($start->format('m') !== $end->format('m') ? $start->diff($end)->m : 0) + ($start->diff($end)->y * 12) +
                ($start->format('d') > $end->format('d') ? 1 : 0);
        }
        catch (Exception $e)
        {
            $diffMonths = 0;
            error_log($e->getMessage());
        }
        for ($i = 0; $i <= $diffMonths; $i++)
        {
            $date = date("Y-m", strtotime(date("Y-m", strtotime($start_time)) . '+' . $i . ' MONTH'));
            $list[$date] = [];
        }

        $where = [];
        $where['user_id'] = $master_user_id;
        $where['status'] = [PROMOTE_STATUS_AVAILABLE];
        $where['subcode_flag'] = IS_NOT_PROMOTE_SUBCODE;

        $user_qrcode_list = $this->CI->qrcode_lib->get_promoted_reward_info($where, $start_time ?? '', $end_time ?? '', 0, 0, FALSE, FALSE);
        $user_subcode_list = $this->CI->qrcode_lib->get_subcode_list($master_user_id, [], ['status' => PROMOTE_STATUS_AVAILABLE]);
        $user_subcode_list = array_column($user_subcode_list, NULL, 'user_qrcode_id');

        foreach ($user_qrcode_list as $user_qrcode)
        {
            if ( ! isset($user_qrcode) || empty($user_qrcode) ||
                ! isset($user_qrcode['info']['subcode_flag']) || $user_qrcode['info']['subcode_flag'] == IS_NOT_PROMOTE_SUBCODE)
            {
                continue;
            }

            $user_qrcode_info = $user_qrcode['info'];
            $user_qrcode_id = $user_qrcode_info['id'];
            if ( ! isset($user_subcode_list[$user_qrcode_id]))
            {
                continue;
            }

            // 初始化結構
            for ($i = 0; $i <= $diffMonths; $i++)
            {
                $date = date("Y-m", strtotime(date("Y-m", strtotime($start_time)) . '+' . $i . ' MONTH'));
                $list[$date][$user_qrcode_id] = $categoryInitList;
                $list[$date][$user_qrcode_id]['collaboration'] = $collaboratorInitList;
                $list[$date][$user_qrcode_id]['full_member_count'] = 0;
                $list[$date][$user_qrcode_id]['subcode_id'] = (int) $user_subcode_list[$user_qrcode_id]['id'];
                $list[$date][$user_qrcode_id]['alias'] = $user_subcode_list[$user_qrcode_id]['alias'];
                $list[$date][$user_qrcode_id]['registered_id'] = $user_subcode_list[$user_qrcode_id]['registered_id'];
            }

            // 處理各個產品
            $categories_name = $this->CI->user_lib->categoriesName;
            $categories_reward = [
                'student' => $user_qrcode['info']['settings']['reward']['collaboration_person']['amount'],
                'salary_man' => $user_qrcode['info']['settings']['reward']['collaboration_person']['amount'],
                'small_enterprise' => $user_qrcode['info']['settings']['reward']['collaboration_enterprise']['amount'],
            ];
            foreach (array_keys($this->CI->user_lib->rewardCategories) as $category)
            {
                if ( ! isset($user_qrcode[$category]) || empty($user_qrcode[$category]))
                {
                    continue;
                }

                foreach ($user_qrcode[$category] as $value)
                {
                    $formattedMonth = date("Y-m", strtotime($value['loan_date']));
                    $list[$formattedMonth][$user_qrcode_id][$category]['count'] += 1;
                    $list[$formattedMonth][$user_qrcode_id]['list'][] = [
                        'loan_date' => $value['loan_date'],
                        'alias' => $user_subcode_list[$user_qrcode_id]['alias'],
                        'category' => $categories_name[$category] ?? '',
                        'reward' => $categories_reward[$category]
                    ];
                }
            }

            // 處理合作資料的計算
            foreach ($user_qrcode['collaboration'] as $collaborator_id => $collaboration_list)
            {
                foreach ($collaboration_list as $value)
                {
                    $formattedMonth = date("Y-m", strtotime($value['loan_time']));
                    $list[$formattedMonth][$user_qrcode_id]['collaboration'][$collaborator_id]['count'] += 1;
                }
            }

            // 下載+註冊
            foreach ($user_qrcode['fullMember'] as $value)
            {
                $formattedMonth = date("Y-m", strtotime($value['created_at']));
                $list[$formattedMonth][$user_qrcode_id]['full_member_count'] += 1;
            }
        }

        return $list;
    }
}
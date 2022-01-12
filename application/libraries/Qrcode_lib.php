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
     * @param $registered_phone
     * @return false
     */
    public function get_user_identity($registered_phone) {
        $this->CI->load->model('user/user_model');
        $this->CI->load->library('certification_lib');

        $identity = FALSE;
        $subcode_user = $this->CI->user_model->get_by(['phone' => $registered_phone, 'block_status != ' => 1, 'company_status' => USER_NOT_COMPANY]);
        if (isset($subcode_user))
        {
            $borrower = $this->CI->certification_lib->get_certification_info($subcode_user->id, CERTIFICATION_IDCARD, USER_BORROWER);
            if($borrower !== FALSE)
            {
                $identity = $borrower;
            }
            else
            {
                $investor = $this->CI->certification_lib->get_certification_info($subcode_user->id, CERTIFICATION_IDCARD, USER_INVESTOR);
                $identity = $investor;
            }
        }

        return $identity;
    }

}
<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use Credit\Credit_industry_life_cycle;
use Credit\Credit_year_in_business;
use Credit\Credit_registered_capital;
use Credit\Credit_per_captial_output;
use Credit\Credit_average_collection_period;
use Credit\Credit_days_payable_outstanding;
use Credit\Credit_days_sales_of_inventory;
use Credit\Credit_gross_margin;
use Credit\Credit_business_finance_ratio;
use Credit\Credit_debit_ratio;
use Credit\Credit_break_even_point;
use Credit\Credit_revenue_stability;
use Credit\Credit_smec_qualification;
use Credit\due_diligence\Credit_asset;
use Credit\due_diligence\Credit_background;
use Credit\due_diligence\Credit_guarantor;
use Credit\due_diligence\Credit_human_resource;
use Credit\due_diligence\Credit_job_seniority;
use Credit\due_diligence\Credit_team_seniority;

/**
 * @property  CI_Controller $CI
 */
class Credit_lib{

	private $credit = [];

	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('loan/credit_model');
		$this->CI->load->model('user/user_meta_model');
		$this->CI->config->load('school_points',TRUE);
		$this->CI->config->load('credit',TRUE);
		$this->credit = $this->CI->config->item('credit');
		$this->product_list = $this->CI->config->item('product_list');
		$this->scoreHistory = [];
    }

	//信用評比
    public function approve_credit($user_id, $product_id, $sub_product_id = 0, $approvalExtra = NULL, $stage_cer = FALSE, $credit = FALSE, $mix_credit = FALSE, $instalment = 0, $target = NULL)
    {
		if($user_id && $product_id){

            //信用低落
            $low = $this->CI->credit_model->order_by('level','desc')->get_by([
                'user_id'		=> $user_id,
                'status'		=> 1,
                'points <'		=> 0,
            ]);
            $expire_time     = $max_expire_time = strtotime("+2 months", time());

            if($low){
                if($mix_credit){
                    return $low->points;
                }
                $param = [
					'product_id' 	=> $product_id,
					'sub_product_id'=> $sub_product_id,
					'user_id'		=> $user_id,
					'points'		=> $low->points,
					'level'			=> $low->level,
					'amount'		=> $low->amount,
                    'instalment'    => $instalment,
					'expire_time'   => $expire_time,
				];
				if ($approvalExtra && $approvalExtra->shouldSkipInsertion()) {
					return $param;
				}
                return $this->CI->credit_model->insert($param);
            }

            if(!$mix_credit){
                //few target
                $target  = $this->CI->target_model->order_by('loan_date','asc')->get_by([
                    'user_id'     => $user_id,
                    'status'      => 5,
                    'loan_date >' => date('Y-m-d',strtotime("-2 months", time())),
                ]);
                if($target){
                    $expire_time = strtotime("+2 months", strtotime($target->loan_date));
                }
            }

            $this->scoreHistory = [];
			$method		= 'approve_'.$product_id;
			if(method_exists($this, $method)){
				$rs = $this->$method($user_id,$product_id,$sub_product_id,$expire_time, $approvalExtra, $stage_cer, $credit, $mix_credit, $instalment, $target);
				return $rs;
			}
		}
		return false;
	}

    public function approve_associates_credit($target, $total_point)
    {
        $product_id = $target->product_id;
        $sub_product_id = $target->sub_product_id;
        $user_id = $target->user_id;
        $low = $this->CI->credit_model->order_by('level', 'desc')->get_by([
            'user_id' => $user_id,
            'status' => 1,
            'points <' => 0,
        ]);
        $expire_time = $max_expire_time = strtotime("+6 months", time());

        if ($low) {
            $param = [
                'product_id' => $product_id,
                'sub_product_id' => $sub_product_id,
                'user_id' => $user_id,
                'points' => $low->points,
                'level' => $low->level,
                'amount' => $low->amount,
                'expire_time' => $expire_time,
            ];
            return $this->CI->credit_model->insert($param);
        }

        $target = $this->CI->target_model->order_by('loan_date', 'asc')->get_by([
            'user_id' => $user_id,
            'status' => 5,
            'loan_date >' => date('Y-m-d', strtotime("-2 months", time())),
        ]);
        if ($target) {
            $expire_time = strtotime("+2 months", strtotime($target->loan_date));
        }

        $param = [
            'product_id' => $product_id,
            'sub_product_id' => $sub_product_id,
            'user_id' => $user_id,
            'points' => $total_point,
            'level' => 0,
            'amount' => 0,
            'expire_time' => $expire_time,
        ];
        $param['level'] = $this->get_credit_level($total_point, $product_id, $sub_product_id);
        if (isset($this->credit['credit_amount_' . $product_id])) {
            foreach ($this->credit['credit_amount_' . $product_id] as $key => $value) {
                if ($param['points'] >= $value['start'] && $param['points'] <= $value['end']) {
                    $param['amount'] = $value['amount'];
                    break;
                }
            }
        }
        return $this->CI->credit_model->insert($param);
    }

	private function approve_1($user_id,$product_id,$sub_product_id,$expire_time, $approvalExtra, $stage_cer, $credit, $mix_credit, $instalment, $target){

        $total = 0;
        $school_point_is_lower_than_150_and_point_larger_than_870 = false;
        $param = [
            'product_id' => $product_id,
            'sub_product_id' => $sub_product_id,
            'user_id' => $user_id,
            'amount' => 0,
            'instalment' => $instalment
        ];

        $this->CI->config->load('credit', TRUE);
        $instalment_modifier_list = $this->CI->config->item('credit')['credit_instalment_modifier_' . $product_id];
        $user_info = $this->CI->user_model->get($user_id);
	    if($stage_cer == 0) {
            NORMAL_CREDIT:
            $info = $this->CI->user_meta_model->get_many_by(['user_id' => $user_id]);

            $this->CI->load->model('user/user_certification_model');

            $data = [];
            foreach ($info as $key => $value) {
                $data[$value->meta_key] = $value->meta_value;
            }

            // 學校
            if (isset($data['school_name']) && ! empty($data['school_name']))
            {
                $get_school_point = $this->get_school_point(
                    $data['school_name'],
                    $data['school_system'],
                    $data['school_major'],
                    $data['school_department'],
                    $sub_product_id,
                    $product_id
                );
                $school_point = (int) ($get_school_point['school_point'] ?? 0);
                $total += (int) ($get_school_point['point'] ?? 0);
                if ( ! empty($get_school_point['score_history']))
                {
                    foreach ($get_school_point['score_history'] as $history_val)
                    {
                        $this->scoreHistory[] = $history_val;
                    }
                }
            }

            // 近一學期成績
            if (isset($data['last_grade']) && is_numeric($data['last_grade']))
            {
                if ($data['last_grade'] > 90)
                {
                    $total += 200;
                    $this->scoreHistory[] = '提供學校成績單(>90分) = 200\n';
                }
                elseif ($data['last_grade'] > 85)
                {
                    $total += 150;
                    $this->scoreHistory[] = '提供學校成績單(85-90分) = 150\n';
                }
                elseif ($data['last_grade'] > 80)
                {
                    $total += 100;
                    $this->scoreHistory[] = '提供學校成績單(80-85分) = 100\n';
                }
                else
                {
                    $total += 50;
                    $this->scoreHistory[] = '提供學校成績單(<80分) = 50\n';
                }
            }

            // 財務證明
            if (isset($data['financial_status']) && ! empty($data['financial_status']))
            {
                $total += 50;
                $this->scoreHistory[] = '借款人提供個人財務數據表(自填) = 50\n';
            }

            // IG好友數
            // 1. 10-50人得200分
            // 2. 51-100人得300分
            // 3. 超過101人(含)起，好友數每增加10人再得10分(個位數無條件捨去)
            // 最高上限350分
            if (isset($data['follow_count']) && ! empty($data['follow_count']))
            {
                if ($data['follow_count'] >= 101)
                { // 超過101人(含)起，好友數每增加10人再得10分(個位數無條件捨去)
                    $calculate_points = 300 + floor(($data['follow_count'] - 100) / 10) * 10;
                }
                elseif ($data['follow_count'] >= 51)
                { // 51-100人得300分
                    $calculate_points = 300;
                }
                elseif ($data['follow_count'] >= 10)
                { // 10-50人得200分
                    $calculate_points = 200;
                }
                else
                {
                    $calculate_points = 0;
                }

                // 最高上限350分
                $calculate_points = min($calculate_points, 350);

                $total += $calculate_points;
                $this->scoreHistory[] = "IG好友數 = {$calculate_points}\n";
            }

            // IG近3個月內每發文1次得10分
            // 最高得分100
            if (isset($data['posts_in_3months']) && ! empty($data['posts_in_3months']))
            {
                $calculate_points = min($data['posts_in_3months'] * 10, 100);
                $total += $calculate_points;
                $this->scoreHistory[] = "IG近3個月內發文 = {$calculate_points}\n";
            }

            // IG發文關鍵字每1個得10分/全球、財經、數位、兩岸
            // 最高得分100
            if (isset($data['key_word']) && ! empty($data['key_word']))
            {
                $calculate_points = min($data['key_word'] * 10, 100);
                $total += $calculate_points;
                $this->scoreHistory[] = "IG發文關鍵字 = {$calculate_points}\n";
            }

            if (isset($data['line_access_token']) && ! empty($data['line_access_token']))
            {
                $total += 100;
                $this->scoreHistory[] = '提供社交帳戶認證LINE = 100\n';
            }

            // 聯徵
            if (isset($data['investigation_status']) && ! empty($data['investigation_status']))
            {
                $investigationStatus = 150;
                $total += $investigationStatus;
                $this->scoreHistory[] = '提供聯徵 = ' . $investigationStatus;
            }

            //SIP
            //if(!empty($data['student_sip_account']) && !empty($data['student_sip_password'])){
            //$total += 150;
            //}
            if (isset($data['transcript_front']) && !empty($data['transcript_front'])) {
                $total += 50;
                $this->scoreHistory[] = '提供成績單 = 50';
            }
            //緊急聯絡人
            // if (isset($data['emergency_relationship']) && $data['emergency_relationship'] == '監護人') {
            //     $total = $total - 400;//mantis 0000003
            //     $this->scoreHistory[] = '緊急聯絡人為監護人 = 400';
            // }

            if ($approvalExtra && $approvalExtra->getExtraPoints()) {
                $total += $approvalExtra->getExtraPoints();
                $this->scoreHistory[] = '分數調整 = ' . $approvalExtra->getExtraPoints();
            }
            if ($approvalExtra && $approvalExtra->get_fixed_amount()) {
                $total = $this->get_credit_score_with_credit_amount($approvalExtra->get_fixed_amount(), $product_id,
                    $sub_product_id, $stage_cer);
            }
            // 學校分數小於等於150分者，其credits.points不得高於870，若高於則以870計
            if (( ! isset($school_point) || $school_point <= 150) && $total > 870)
            {
                $total = 870;
                $this->scoreHistory[] = '學校信評分在150（含）以下，信評分數不能超過870（含）分';
                $school_point_is_lower_than_150_and_point_larger_than_870 = true;
            }
            $param['points'] = intval($total);
            goto SKIP_STAGE_CREDIT;
        }
        if(in_array($stage_cer,[1,2])){
            // todo: 暫時繞過階段上架的評分方式
            goto NORMAL_CREDIT;
            $param['points'] = $total = 100;
        }

        SKIP_STAGE_CREDIT:

        $param['points'] = intval($total);

        if($mix_credit){
            return $param['points'];
        }

        $param['level'] 	= $this->get_credit_level($total,$product_id,$sub_product_id);

        // 取得額度對照表
        $credit_amount_list = $this->get_credit_amount_list($product_id, $sub_product_id);

        // 取得區間額度
        $param['amount'] = $this->get_amount_from_credit_amount_list($credit_amount_list, $param['amount'], $param['points']);

        // 額度調整 = 額度 * 性別對應的系數
        if ($user_info->sex == 'M')
        {
            // 男
            $param['amount'] *= 0.95;
            $this->scoreHistory[] = '性別男: 額度 * 0.95';
        }
        else
        {
            $this->scoreHistory[] = '性別女: 額度 * 1';
        }

        // 調整額度 = 額度 * 分期期數對應的系數
        $param['amount'] = round($param['amount'] * ($instalment_modifier_list[$instalment] ?? 1));
        $this->scoreHistory[] = '借款期數' . $instalment . '期: 額度 * ' . ($instalment_modifier_list[$instalment] ?? 1);

        $param['expire_time'] = $expire_time;

        // 額度不能「小」於產品的最「小」允許額度
        $param['amount'] = $param['amount'] < (int) $this->product_list[$product_id]['loan_range_s'] ? 0 : $param['amount'];

        // 額度不能「大」於產品的最「大」允許額度
        $param['amount'] = min($this->get_credit_max_amount($param['points'], $product_id, $sub_product_id), $param['amount']);

        $param['remark'] = json_encode(['scoreHistory' => $this->scoreHistory]);
//        學校分數小於等於150分者，且原本分數高於870，且二審有調整額度，更新調整的額度為870分對應的額度
        if ($school_point_is_lower_than_150_and_point_larger_than_870 &&
            $approvalExtra && $approvalExtra->get_fixed_amount()) {
            // 取得區間額度
            $_amount = $this->get_amount_from_credit_amount_list($credit_amount_list, $param['amount'], $param['points']);
            $approvalExtra->set_fixed_amount($_amount);
        }
        $param = $this->set_fixed_amount_into_param($param, $product_id, $sub_product_id, $approvalExtra, $stage_cer);
        if ($approvalExtra && $approvalExtra->shouldSkipInsertion()
            || (!empty($credit['level']) && $credit['level'] == 10)) {
            return $param;
        }
        $this->CI->credit_model->update_by(
            [
                'product_id' => $product_id,
                'sub_product_id' => $sub_product_id,
                'user_id' => $user_id,
                'status' => 1
            ],
            ['status' => 0]
        );
        return  $this->CI->credit_model->insert($param);
	}

	private function approve_2($user_id,$product_id,$sub_product_id,$expire_time, $approvalExtra, $stage_cer, $credit, $mix_credit, $instalment, $target){
		return $this->approve_1($user_id,$product_id,$sub_product_id,$expire_time, $approvalExtra, $stage_cer, $credit, $mix_credit, $instalment, $target);
	}

	private function approve_3($user_id,$product_id,$sub_product_id,$expire_time, $approvalExtra, $stage_cer, $credit, $mix_credit, $instalment, $target){
        $total = 0;

        $param = [
            'user_id' => $user_id,
            'product_id' => $product_id,
            'sub_product_id' => $sub_product_id,
            'amount' => 0,
            'instalment' => $instalment
        ];

        $info = $this->CI->user_meta_model->get_many_by(['user_id' => $user_id]);
        $user_info = $this->CI->user_model->get($user_id);
        $data = [];
        foreach ($info as $key => $value) {
            $data[$value->meta_key] = $value->meta_value;
        }

        if ($sub_product_id) {
            //techie
            if ($sub_product_id == 1) {
                $job_license_point =  isset($data['job_license']) ? (int) $data['job_license'] * 50 : 0;
                $total += $job_license_point;
                $this->scoreHistory[] = '工程師貸提供專業證書: ' . $job_license_point . ' * 50';

                $job_pro_level_point = isset($data['job_pro_level']) ? (int) $data['job_pro_level'] * 100 : 0;
                $total += $job_pro_level_point;
                $this->scoreHistory[] = '工程師貸專家調整: ' . $job_pro_level_point . ' * 100';
            }
        }

        // 畢業學校
        if (isset($data['diploma_name']) && isset($data['diploma_system']))
        {
            $get_school_point = $this->get_school_point_product_salary_man($data['diploma_name'], $data['diploma_system']);
            $school_point_m = min($get_school_point['point'], 390);
            $total += $school_point_m;
            $this->scoreHistory[] = "原始學歷評分: {$get_school_point['point']}; 調整為: {$school_point_m}; 明細: ";
            foreach ($get_school_point['score_history'] as $key => $value)
            {
                $this->scoreHistory[] = "{$key}. {$value}";
            }
        }
        else
        {
            $school_list = $this->CI->config->item('school_points');
            $school_points_list = array_column($school_list['school_points'], 'points', 'points');
            $min_points = min($school_points_list);
            $normal_coef = 0.3;
            $total += $min_points * $normal_coef;
            $this->scoreHistory[] = "無提交最高學歷得分: {$min_points} * {$normal_coef}";
        }

        // 財務評分
        if ($approvalExtra && ! empty($approvalExtra->getSpecialInfo()))
        {
            $special_info = $approvalExtra->getSpecialInfo();
            $special_info = array_filter($special_info, function ($value) {
                return is_numeric($value);
            });
            $data = array_replace($data, $special_info);
        }
        $get_financial_point = $this->get_financial_point_product_salary_man($data);
        $financial_point_m = min($get_financial_point['point'], 2250);
        $total += $financial_point_m;
        $this->scoreHistory[] = "原始財務評分: {$get_financial_point['point']}; 調整為: {$financial_point_m}; 明細: ";
        foreach ($get_financial_point['score_history'] as $key => $value)
        {
            $this->scoreHistory[] = "{$key}. {$value}";
        }

        //聯徵
        if (isset($data['investigation_status']) && !empty($data['investigation_status'])) {
            if (isset($data['investigation_times'])) {
                $investigation_times_point = $this->get_investigation_times_point(intval($data['investigation_times']));
                $total += $investigation_times_point;
                $this->scoreHistory[] = '聯徵查詢次數: ' . $investigation_times_point;
            }

            if (isset($data['investigation_credit_rate'])) {
                $investigation_credit_rate_point = $this->get_investigation_rate_point(intval($data['investigation_credit_rate']), $data['investigation_has_using_credit_card'] ?? 0);
                $total += $investigation_credit_rate_point;
                $this->scoreHistory[] = '聯徵信用卡使用率: ' . $investigation_credit_rate_point;
            }

            if (isset($data['investigation_months'])) {
                $data['investigation_months'] = (int) $data['investigation_months'];
                $investigation_months_point = $this->get_investigation_months_point($data['investigation_months']);
                $total += $investigation_months_point;
                $this->scoreHistory[] = '聯徵信用記錄' . $data['investigation_months'] . '個月: ' . $investigation_months_point;
            }
        }

        // 社交評分
        $social_total_score = 0;
        $social_score_history = [];
        // IG近1個月內發文次數>10
        if ( ! empty($data['posts_in_1months']) && $data['posts_in_1months'] > 10)
        {
            $social_total_score += (300 * 0.5);
            $social_score_history[] = 'IG近1個月內發文次數>10: 300 * 0.5';
        }
        // 好友數>100且較3個月前增加10%以上
        $data_follow_count = (int) ($data['follow_count'] ?? 0);
        $data_followers_grow_rate_in_3month = (double) ($data['followers_grow_rate_in_3month'] ?? 0);
        if ($this->CI->target_model->chk_exist_by_status(['user_id' => $user_id, 'product_id' => [PRODUCT_ID_STUDENT, PRODUCT_ID_SALARY_MAN], 'status' => [TARGET_REPAYMENTING, TARGET_REPAYMENTED]]) &&
            $data_follow_count > 100 && $data_followers_grow_rate_in_3month >= 0.1 )
        {
            $social_total_score += (300 * 0.5);
            $social_score_history[] = '好友數>100且較3個月前增加10%以上: 300 * 0.5';
        }
        $social_total_score = min($social_total_score, 300);
        $total += $social_total_score;
        $this->scoreHistory = array_merge($this->scoreHistory, $social_score_history);
        // 提供社交帳戶認證ID
        $this->CI->load->library('certification_lib');
        $cert_social = $this->CI->certification_lib->get_certification_info($user_id, CERTIFICATION_SOCIAL);
        $cert_social_score = 0;
        if ( ! empty($cert_social->content['facebook']))
        {
            $cert_social_score += 100;
        }
        if ( ! empty($cert_social->content['instagram']))
        {
            $cert_social_score += 100;
        }
        // 每增加一個社交帳戶ID+100、調整係數0.5
        // 至多150分
        $cert_social_score = min(($cert_social_score * 0.5), 150);
        $total += $cert_social_score;
        $this->scoreHistory[] = '提供社交帳戶認證ID: ' . $cert_social_score;

        if ($stage_cer)
        {
            goto SKIP_STAGE_CREDIT;
            $tmp_msg = '--- 原始風控計算: ' . $total;
            $total = min($total, $this->credit['credit_level_3'][10]['end']);
            $this->scoreHistory[] = "{$tmp_msg}; 因階段上架調整為: {$total} ---";
        }

        SKIP_STAGE_CREDIT:
        $salary = isset($data['job_salary']) ? intval($data['job_salary']) : 0;

        if ($approvalExtra) {
            if($approvalExtra->getExtraPoints())
            {
                $extra_point = $approvalExtra->getExtraPoints();
                $total += $extra_point;
                $this->scoreHistory[] = '二審專家調整: ' . $extra_point;
            }
        }

        // 總分調整 = 總分 * 性別對應的系數
        if ($user_info->sex == 'M')
        {
            // 男
            $total *= 0.9;
            $this->scoreHistory[] = '性別男: 總分 * 0.9';
        }
        else
        {
            $this->scoreHistory[] = '性別女: 總分 * 1';
        }

        $param['points'] = (int) $total;

        if($mix_credit){
            return $param['points'];
        }

        $param['level'] = $this->get_credit_level($total, $product_id, $sub_product_id, $stage_cer);
        $credit_amount_list = $this->get_credit_amount_list($product_id, $sub_product_id);
        if ( ! empty($credit_amount_list))
        {
            foreach ($credit_amount_list as $value) {
                if ($param['points'] >= $value['start'] && $param['points'] <= $value['end']) {
                    $param['amount'] = $salary * $value['rate'];
                    break;
                }
            }
        }
        else
        {
            return FALSE;
        }

        $param['expire_time'] = $expire_time;

        // 月薪低於特定值，不能超過特定倍數的額度
        if (!$stage_cer && intval($data['job_salary']) <= $this->product_list[$product_id]['condition_rate']['salary_below']) {
			$job_salary = intval($data['job_salary']) * $this->product_list[$product_id]['condition_rate']['rate'];
            $param['amount'] = intval(min($param['amount'], $job_salary));
        }

        // 額度調整 = 額度 * 分期期數對應的系數
        $this->CI->config->load('credit', TRUE);
        $instalment_modifier_list = $this->CI->config->item('credit')['credit_instalment_modifier_' . $product_id];
        $param['amount'] = round($param['amount'] * ($instalment_modifier_list[$instalment] ?? 1));
        $this->scoreHistory[] = '借款期數' . $instalment . '期: 額度 * ' . ($instalment_modifier_list[$instalment] ?? 1);

        // 舊客戶加碼
        $this->CI->load->model('loan/target_model');
        $has_delayed = $this->CI->target_model->count_by([
            'user_id' => $param['user_id'],
            'delay_days>' => 0,
        ]);
        if ($has_delayed === 0)
        {
            $markup_amount = $this->get_markup_amount($param['user_id']);
            if ( ! empty($markup_amount))
            {
                $max_key = max(array_keys($markup_amount));
                $max_times = $max_key / 100;
                $param['amount'] = $param['amount'] * $max_times;
                $this->scoreHistory[] = "舊客戶加碼：<br/>額度 * {$max_times} 倍，因符合其中一條件：" . implode('、', $markup_amount[$max_key]);
            }
        }

        // 額度不能「小」於產品的最「小」允許額度
        $param['amount'] = $param['amount'] < (int) $this->product_list[$product_id]['loan_range_s'] ? 0 : $param['amount'];

        // 額度不能「大」於產品的最「大」允許額度
        $param['amount'] = min($this->get_credit_max_amount($param['points'], $product_id, $sub_product_id), $param['amount']);

        $param['remark'] = json_encode(['scoreHistory' => $this->scoreHistory]);

        // 檢查二審額度調整
        $param = $this->set_fixed_amount_into_param($param,
            $product_id, $sub_product_id, $approvalExtra, $stage_cer);

        if ($approvalExtra && $approvalExtra->shouldSkipInsertion()) {
			return $param;
		}

        $this->CI->credit_model->update_by(
            [
                'product_id' => $product_id,
                'sub_product_id' => $sub_product_id,
                'user_id' => $user_id,
                'status' => 1,
            ],
            ['status'=> 0]
        );
        $rs 		= $this->CI->credit_model->insert($param);
		return $rs;
	}

	private function approve_4($user_id,$product_id,$sub_product_id,$expire_time,$approvalExtra, $stage_cer, $credit, $mix_credit, $instalment, $target){
		return $this->approve_3($user_id,$product_id,$sub_product_id,$expire_time,$approvalExtra, $stage_cer, $credit, $mix_credit, $instalment, $target);
	}

    // 房產消費貸
    private function approve_5($user_id, $product_id, $sub_product_id, $expire_time, $approvalExtra, $stage_cer, $credit, $mix_credit, $instalment, $target)
    {
        $this->CI->load->model('user/user_certification_model');
        $update_certification_status_remark = function ($certification, $message = '') {
            $certification_id = $certification->id;
            $certification_remark = json_decode($certification->remark, true);
            $certification_remark['credit_lib_approve_5'] = $message;
            $this->CI->user_certification_model->update($certification_id, ['status' => 3, 'remark' => json_encode($certification_remark, JSON_UNESCAPED_UNICODE)]);
        };
        // todo: 上班族貸信評會修改，到時候要記得也改這邊
        $total = 0;
        $param = [
            'user_id' => $user_id,
            'product_id' => $product_id,
            'sub_product_id' => $sub_product_id,
            'amount' => 0,
            'instalment' => $instalment
        ];

        $user_meta_info = $this->CI->user_meta_model->get_many_by(['user_id' => $user_id]);
        $user_info = $this->CI->user_model->get($user_id);
        $data = [];
        foreach ($user_meta_info as $value)
        {
            $data[$value->meta_key] = $value->meta_value;
        }

        //畢業學校
        if ( ! empty($data['diploma_name']))
        {
            $get_school_point = $this->get_school_point_product_salary_man($data['diploma_name'], $data['diploma_system']);
            $school_point_m = min($get_school_point['point'], 390);
            $total += $school_point_m;
            $this->scoreHistory[] = "原始學歷評分: {$get_school_point['point']}; 調整為: {$school_point_m}; 明細: ";
            foreach ($get_school_point['score_history'] as $key => $value)
            {
                $this->scoreHistory[] = "{$key}. {$value}";
            }
        }
        else
        {
            $school_list = $this->CI->config->item('school_points');
            $school_points_list = array_column($school_list['school_points'], 'points', 'points');
            $min_points = min($school_points_list);
            $normal_coef = 0.3;
            $total += $min_points * $normal_coef;
            $this->scoreHistory[] = "無提交最高學歷得分: {$min_points} * {$normal_coef}";
        }

        // 財務評分
        if ($approvalExtra && ! empty($approvalExtra->getSpecialInfo()))
        {
            $special_info = $approvalExtra->getSpecialInfo();
            $special_info = array_filter($special_info, function ($value) {
                return is_numeric($value);
            });
            $data = array_replace($data, $special_info);
        }
        $get_financial_point = $this->get_financial_point_product_salary_man($data);
        $financial_point_m = min($get_financial_point['point'], 2250);
        $total += $financial_point_m;
        $this->scoreHistory[] = "原始財務評分: {$get_financial_point['point']}; 調整為: {$financial_point_m}; 明細: ";
        foreach ($get_financial_point['score_history'] as $key => $value)
        {
            $this->scoreHistory[] = "{$key}. {$value}";
        }

        //聯徵
        if (isset($data['investigation_status']) && !empty($data['investigation_status'])) {
            if (isset($data['investigation_times'])) {
                $investigation_times_point = $this->get_investigation_times_point(intval($data['investigation_times']));
                $total += $investigation_times_point;
                $this->scoreHistory[] = '聯徵查詢次數: ' . $investigation_times_point;
            }

            if (isset($data['investigation_credit_rate'])) {
                $investigation_credit_rate_point = $this->get_investigation_rate_point(intval($data['investigation_credit_rate']), $data['investigation_has_using_credit_card'] ?? 0);
                $total += $investigation_credit_rate_point;
                $this->scoreHistory[] = '聯徵信用卡使用率: ' . $investigation_credit_rate_point;
            }

            if (isset($data['investigation_months'])) {
                $data['investigation_months'] = (int) $data['investigation_months'];
                $investigation_months_point = $this->get_investigation_months_point($data['investigation_months']);
                $total += $investigation_months_point;
                $this->scoreHistory[] = '聯徵信用記錄' . $data['investigation_months'] . '個月: ' . $investigation_months_point;
            }
        }

        // 社交評分
        $social_total_score = 0;
        $social_score_history = [];
        // IG近1個月內發文次數>10
        if ( ! empty($data['posts_in_1months']) && $data['posts_in_1months'] > 10)
        {
            $social_total_score += (300 * 0.5);
            $social_score_history[] = 'IG近1個月內發文次數>10: 300 * 0.5';
        }
        // 好友數>100且較3個月前增加10%以上
        $data_follow_count = (int) ($data['follow_count'] ?? 0);
        $data_followers_grow_rate_in_3month = (double) ($data['followers_grow_rate_in_3month'] ?? 0);
        if ($this->CI->target_model->chk_exist_by_status(['user_id' => $user_id, 'product_id' => [PRODUCT_ID_HOME_LOAN], 'status' => [TARGET_REPAYMENTING, TARGET_REPAYMENTED]]) && $data_follow_count > 100 && $data_followers_grow_rate_in_3month >= 0.1)
        {
            $social_total_score += (300 * 0.5);
            $social_score_history[] = '好友數>100且較3個月前增加10%以上: 300 * 0.5';
        }
        $social_total_score = min($social_total_score, 300);
        $total += $social_total_score;
        $this->scoreHistory = array_merge($this->scoreHistory, $social_score_history);
        // 提供社交帳戶認證ID
        $this->CI->load->library('certification_lib');
        $cert_social = $this->CI->certification_lib->get_certification_info($user_id, CERTIFICATION_SOCIAL);
        $cert_social_score = 0;
        if ( ! empty($cert_social->content['facebook']))
        {
            $cert_social_score += 100;
        }
        if ( ! empty($cert_social->content['instagram']))
        {
            $cert_social_score += 100;
        }
        // 每增加一個社交帳戶ID+100、調整係數0.5
        // 至多150分
        $cert_social_score = min(($cert_social_score * 0.5), 150);
        $total += $cert_social_score;
        $this->scoreHistory[] = '提供社交帳戶認證ID: ' . $cert_social_score;

        $salary = isset($data['job_salary']) ? intval($data['job_salary']) : 0;
        if ($approvalExtra && $approvalExtra->getExtraPoints()) {
            $extra_point = $approvalExtra->getExtraPoints();
            $total += $extra_point;
            $this->scoreHistory[] = '二審專家調整: ' . $extra_point;
        }

        // 總分調整 = 總分 * 性別對應的系數
        if ($user_info->sex == 'M')
        {
            // 男
            $total *= 0.9;
            $this->scoreHistory[] = '性別男: 總分 * 0.9';
        }
        else
        {
            $this->scoreHistory[] = '性別女: 總分 * 1';
        }

        $param['points'] = (int) $total;

        if($mix_credit){
            return $param['points'];
        }

        $param['level'] = $this->get_credit_level($total, $product_id, $sub_product_id, $stage_cer);
        $credit_amount_list = $this->get_credit_amount_list($product_id, $sub_product_id);
        if ( ! empty($credit_amount_list))
        {
            foreach ($credit_amount_list as $value) {
                if ($param['points'] >= $value['start'] && $param['points'] <= $value['end']) {
                    $param['amount'] = $salary * $value['rate'];
                    break;
                }
            }
        }
        else
        {
            return FALSE;
        }

        $param['expire_time'] = $expire_time;

        // 月薪低於特定值，不能超過特定倍數的額度
        if (!$stage_cer && intval($data['job_salary']) <= $this->product_list[$product_id]['condition_rate']['salary_below']) {
			$job_salary = intval($data['job_salary']) * $this->product_list[$product_id]['condition_rate']['rate'];
            $param['amount'] = intval(min($param['amount'], $job_salary));
        }

        // 額度調整 = 額度 * 分期期數對應的系數
        $this->CI->config->load('credit', TRUE);
        $instalment_modifier_list = $this->CI->config->item('credit')['credit_instalment_modifier_' . $product_id];
        $param['amount'] = round($param['amount'] * ($instalment_modifier_list[$instalment] ?? 1));
        $this->scoreHistory[] = '借款期數' . $instalment . '期: 額度 * ' . ($instalment_modifier_list[$instalment] ?? 1);

        // 舊客戶加碼
        $this->CI->load->model('loan/target_model');
        $has_delayed = $this->CI->target_model->count_by([
            'user_id' => $param['user_id'],
            'delay_days>' => 0,
        ]);
        if ($has_delayed === 0) {
            $markup_amount = $this->get_markup_amount($param['user_id']);
            if (!empty($markup_amount)) {
                $max_key = max(array_keys($markup_amount));
                $max_times = $max_key / 100;
                $param['amount'] = $param['amount'] * $max_times;
                $this->scoreHistory[] = "舊客戶加碼：<br/>額度 * {$max_times} 倍，因符合其中一條件：" . implode('、', $markup_amount[$max_key]);
            }
        }
        // 依各子產品調整最高額度
        $this->CI->load->model('user/user_certification_model');
        switch ($sub_product_id)
        {
            case SUB_PRODUCT_ID_HOME_LOAN_SHORT:
                // 取得徵信項，購屋合約
                $user_cert_info_contract = $this->CI->user_certification_model->get_content($user_id, CERTIFICATION_HOUSE_CONTRACT);
                $certification = $this->CI->user_certification_model->get_by(
                    [
                        'user_id' => $user_id,
                        'investor' => 0,
                        'status' => 1,
                        'certification_id' => CERTIFICATION_HOUSE_CONTRACT,
                        'investor' => USER_BORROWER
                    ]
                );
                if (empty($user_cert_info_contract[0]->content)) {

                    $message = '缺少 content';
                    $update_certification_status_remark($certification, $message);
                    return false;
                }
                $user_cert_content_contract = json_decode($user_cert_info_contract[0]->content, TRUE);
                if (empty($user_cert_content_contract['admin_edit']['down_payment'])) {
                    $message =  '缺少 審核人員確認 頭款/訂金金額';
                    $update_certification_status_remark($certification, $message);
                    return false;
                }

                // 最高借款金額不得超過該筆買賣合約頭期款之9成
                $param['amount'] = min($param['amount'], ($user_cert_content_contract['admin_edit']['down_payment'] * 0.9));
                break;
            case SUB_PRODUCT_ID_HOME_LOAN_RENOVATION:
                // 取得徵信項
                // 24 裝修合約
                $user_cert_info_contract = $this->CI->user_certification_model->get_content($user_id, CERTIFICATION_RENOVATION_CONTRACT);
                $certification = $this->CI->user_certification_model->get_by(
                    [
                        'user_id' => $user_id,
                        'investor' => 0,
                        'status' => 1,
                        'certification_id' => CERTIFICATION_RENOVATION_CONTRACT,
                        'investor' => USER_BORROWER
                    ]
                );
                if (empty($user_cert_info_contract[0]->content)) {
                    $message = '缺少 content';
                    $update_certification_status_remark($certification, $message);
                    return false;
                }
                $user_cert_content_contract = json_decode($user_cert_info_contract[0]->content, true);
                if (empty($user_cert_content_contract['admin_edit']['contract_amount'])) {
                    $message = '缺少 審核人員確認 合約金額';
                    $update_certification_status_remark($certification, $message);
                    return false;
                }

                // 25 裝修發票，選填，所以不一定會有，但有的話，就要檢查
                $user_cert_info_receipt = $this->CI->user_certification_model->get_content($user_id, CERTIFICATION_RENOVATION_RECEIPT);
                if (!empty($user_cert_info_receipt[0]->content)) {
                    $certification_receipt = $this->CI->user_certification_model->get_by(
                        [
                            'user_id' => $user_id,
                            'investor' => 0,
                            'status' => 1,
                            'certification_id' => CERTIFICATION_RENOVATION_RECEIPT,
                            'investor' => USER_BORROWER
                        ]
                    );

                    $user_cert_content_receipt = json_decode($user_cert_info_receipt[0]->content, true);
                    if (empty($user_cert_content_receipt['admin_edit']['receipt_amount'])) {
                        $message = '缺少 審核人員確認 發票金額';
                        $update_certification_status_remark($certification_receipt, $message);
                        return false;
                    }
                    // 如果後台審核送出時，沒有填寫合約金額、合約簽訂日，content不會有admin_edit這個key
                    // 如果最終amount為0，這裡是其中原因
                    $tmp_amount = min(
                        intval($user_cert_content_contract['admin_edit']['contract_amount']),
                        intval($user_cert_content_receipt['admin_edit']['receipt_amount'])
                    );
                }
                // 最高借款金額不得超過該筆買賣合約或發票金額之8成
                $param['amount'] = min($param['amount'], ($tmp_amount * 0.8));
                break;
            case SUB_PRODUCT_ID_HOME_LOAN_APPLIANCES:
                // 取得徵信項，傢俱家電合約或發票收據
                $user_cert_info_contract = $this->CI->user_certification_model->get_content($user_id, CERTIFICATION_APPLIANCE_CONTRACT_RECEIPT);
                $certification = $this->CI->user_certification_model->get_by(
                    [
                        'user_id' => $user_id,
                        'investor' => 0,
                        'status' => 1,
                        'certification_id' => CERTIFICATION_APPLIANCE_CONTRACT_RECEIPT,
                        'investor' => USER_BORROWER
                    ]
                );
                if (empty($user_cert_info_contract[0]->content)) {
                    $message = '缺少 content';
                    $update_certification_status_remark($certification, $message);
                    return false;
                }
                $user_cert_content_contract = json_decode($user_cert_info_contract[0]->content, TRUE);
                // 傢俱家電合約或發票收據 是取amount
                // 請參考 appliance_contract_receipt.php，admin_edit的結構有amount、contract_amount 和 receipt_amount
                // ，但後台人員調整完後，只有表單只有改amount的value，因此以amount為主
                if(empty($user_cert_content_contract['admin_edit']['amount']) ){
                    $message = '缺少 審核人員確認 金額' ;
                    $update_certification_status_remark($certification, $message);
                    return false;
                }
                $tmp_amount = (int)($user_cert_content_contract['admin_edit']['amount']);

                // 最高借款金額不得超過該筆買賣合約或發票金額之8成
                $param['amount'] = min($param['amount'], ($tmp_amount * 0.8));
                break;
        }

        // 土地建物謄本
        $user_cert_info = $this->CI->user_certification_model->get_content($user_id, CERTIFICATION_LAND_AND_BUILDING_TRANSACTIONS);
        if (empty($user_cert_info[0]->content))
        {
            goto SKIP_TRANSACTION_VALUE;
        }
        $user_cert_content = json_decode($user_cert_info[0]->content, TRUE);
        // 市價估值
        if (empty($user_cert_content['admin_edit']['market_value']))
        {
            goto SKIP_TRANSACTION_VALUE;
        }
        // 最高借款額度為「市價估值扣除前順位抵押設定後之餘額」且「不得超過市價估值2成」
        $param['amount'] = min($param['amount'], ($user_cert_content['admin_edit']['market_value'] * 0.2));
        SKIP_TRANSACTION_VALUE:

        // 額度不能「小」於產品的最「小」允許額度
        $param['amount'] = $param['amount'] < (int) $this->product_list[$product_id]['loan_range_s'] ? 0 : $param['amount'];

        // 額度不能「大」於產品的最「大」允許額度
        $param['amount'] = min($this->get_credit_max_amount($param['points'], $product_id, $sub_product_id), $param['amount']);

        $param['remark'] = json_encode(['scoreHistory' => $this->scoreHistory]);

        // 檢查二審額度調整
        $param = $this->set_fixed_amount_into_param($param,
            $product_id, $sub_product_id, $approvalExtra, $stage_cer);

        if ($approvalExtra && $approvalExtra->shouldSkipInsertion())
        {
            return $param;
        }

        $this->CI->credit_model->update_by(
            [
                'product_id' => $product_id,
                'sub_product_id' => $sub_product_id,
                'user_id' => $user_id,
                'status' => 1,
            ],
            ['status' => 0]
        );
        $rs = $this->CI->credit_model->insert($param);
        return $rs;
    }

    private function approve_7($user_id,$product_id,$sub_product_id,$expire_time, $approvalExtra, $stage_cer, $credit, $mix_credit, $instalment, $target){
        $rs = $this->approve_1($user_id,$product_id,$sub_product_id,$expire_time,$approvalExtra, $stage_cer, $credit, $mix_credit, $instalment, $target);
        return $rs;
    }

    private function approve_8($user_id,$product_id,$sub_product_id,$expire_time,$approvalExtra, $stage_cer, $credit, $mix_credit, $instalment, $target){
        $rs = $this->approve_3($user_id,$product_id,$sub_product_id,$expire_time,$approvalExtra, $stage_cer, $credit, $mix_credit, $instalment, $target);
	    return $rs;
    }

    private function approve_9($user_id,$product_id,$sub_product_id,$expire_time, $approvalExtra, $stage_cer, $credit, $mix_credit, $instalment, $target){
        $rs = $this->approve_1($user_id,$product_id,$sub_product_id,$expire_time,$approvalExtra, $stage_cer, $credit, $mix_credit, $instalment, $target);
        return $rs;
    }

    private function approve_10($user_id,$product_id,$sub_product_id,$expire_time,$approvalExtra, $stage_cer, $credit, $mix_credit, $instalment, $target){
        $rs = $this->approve_3($user_id,$product_id,$sub_product_id,$expire_time,$approvalExtra, $stage_cer, $credit, $mix_credit, $instalment, $target);
        return $rs;
    }

    private function approve_1000($user_id,$product_id,$sub_product_id,$expire_time, $approvalExtra, $stage_cer, $credit, $mix_credit, $instalment, $target){

        $info 		= $this->CI->user_meta_model->get_many_by(['user_id'=>$user_id]);
        $user_info 	= $this->CI->user_model->get($user_id);
        $data 		= [];
        $total 		= 0;
        $param		= [
            'product_id'    => $product_id,
            'sub_product_id'=> $sub_product_id,
            'user_id'       => $user_id,
            'amount'        => 0,
            'instalment'    => $instalment,
        ];
        foreach($info as $key => $value){
            $data[$value->meta_key] = $value->meta_value;
        }

        $this->CI->config->load('credit',TRUE);
        $creditJudicial = $this->CI->config->item('credit')['creditJudicial'];
        if(isset($creditJudicial[2]) && $data['creditJudicial_status'] == 1){
            foreach ($creditJudicial[2] as $key => $value) {
                $bonus = 0;
                $score = 0;
                if($value['selctType'] == 'select' && isset($data[$key])){
                    $total += $value['score'][$data[$key]];
                }elseif ($value['selctType'] == 'radio'){
                    foreach ($value['descrtion'] as $descrtionKey => $descrtionValue) {
                        $score = $data[$descrtionValue[0]] == 1?$value['score'][$descrtionKey]:0;
                        $total += $score;
                        $score > 0 ? $bonus++ : '';
                    }
                    $value['bonus'] > 0 && $bonus == count($value['score']) ? $total+=$value['bonus'] : '';
                }
            }
        }

        if ($approvalExtra && $approvalExtra->getExtraPoints()) {
            $total += $approvalExtra->getExtraPoints();
        }

        $param['points'] 	= intval($total);
        $param['level'] 	= $this->get_credit_level($total,$product_id,$sub_product_id);
        if(isset($this->credit['credit_amount_'.$product_id])){
            foreach($this->credit['credit_amount_'.$product_id] as $key => $value){
                if($param['points']>=$value['start'] && $param['points']<=$value['end']){
                    $param['amount'] = $value['amount'];
                    break;
                }
            }
        }
        $param['expire_time'] = $expire_time;

        if ($approvalExtra && $approvalExtra->shouldSkipInsertion()) {
            return $param;
        }

        $rs 		= $this->CI->credit_model->insert($param);
        return $rs;
    }

    private function approve_1002($user_id, $product_id, $sub_product_id, $expire_time, $approvalExtra, $stage_cer, $credit, $mix_credit, $instalment, $target)
    {

        $total = 0;
        $param = [
            'product_id' => $product_id,
            'sub_product_id' => $sub_product_id,
            'user_id' => $user_id,
            'amount' => 0,
            'instalment' => $instalment,
            'type_code' => 0
        ];

        $this->CI->config->load('credit', TRUE);
        $info = $this->CI->user_meta_model->get_many_by(['user_id' => $user_id]);
        $this->CI->load->model('user/user_certification_model');
        $this->CI->load->library('target_lib');
        $this->CI->load->library('certification_lib');


        $register_certs_content = [];
        $spouse_certs_content = [];
        $certs_content = $this->CI->certification_lib->get_content($user_id, USER_BORROWER, [CERTIFICATION_GOVERNMENTAUTHORITIES, CERTIFICATION_INVESTIGATIONJUDICIAL, CERTIFICATION_INCOMESTATEMENT, CERTIFICATION_EMPLOYEEINSURANCELIST, CERTIFICATION_PROFILEJUDICIAL]);
        $associate_list = $this->CI->target_lib->get_associates_data($target->id, 'all');
        if (isset($associate_list[ASSOCIATES_CHARACTER_OWNER]['user_id']))
        {
            $register_certs_content = $this->CI->certification_lib->get_content($associate_list[ASSOCIATES_CHARACTER_OWNER]['user_id'], USER_BORROWER, [CERTIFICATION_INVESTIGATIONA11]);
        }
        if (isset($associate_list[ASSOCIATES_CHARACTER_SPOUSE]['user_id']))
        {
            $spouse_certs_content = $this->CI->certification_lib->get_content($associate_list[ASSOCIATES_CHARACTER_SPOUSE]['user_id'], USER_BORROWER, [CERTIFICATION_INVESTIGATIONA11]);
        }

        $this->CI->load->model('loan/target_meta_model');
        $meta_info = $this->CI->target_meta_model->as_array()->get_many_by([
            'target_id' => $target->id
        ]);
        $target_meta_list = array_column($meta_info, 'meta_value', 'meta_key');

        $credit_list = [];
        if (isset($certs_content[CERTIFICATION_GOVERNMENTAUTHORITIES]['businessTypeCode']))
        {
            $param['type_code'] = $certs_content[CERTIFICATION_GOVERNMENTAUTHORITIES]['businessTypeCode'];
            switch ($certs_content[CERTIFICATION_GOVERNMENTAUTHORITIES]['businessTypeCode'])
            {
                case INDUSTRY_CODE_MANUFACTURING:
                    $credit_list = [
                        new Credit_industry_life_cycle($certs_content[CERTIFICATION_GOVERNMENTAUTHORITIES]),
                        new Credit_year_in_business($certs_content[CERTIFICATION_GOVERNMENTAUTHORITIES]),
                        new Credit_registered_capital($certs_content[CERTIFICATION_GOVERNMENTAUTHORITIES]),
                        new Credit_per_captial_output(array_merge($certs_content[CERTIFICATION_INCOMESTATEMENT], $certs_content[CERTIFICATION_EMPLOYEEINSURANCELIST] ?? [], $certs_content[CERTIFICATION_PROFILEJUDICIAL])),
                        new Credit_average_collection_period($certs_content[CERTIFICATION_INCOMESTATEMENT]),
                        new Credit_days_payable_outstanding($certs_content[CERTIFICATION_INCOMESTATEMENT]),
                        new Credit_gross_margin($certs_content[CERTIFICATION_INCOMESTATEMENT]),
                        new Credit_business_finance_ratio(array_merge($certs_content[CERTIFICATION_INCOMESTATEMENT], $certs_content[CERTIFICATION_INVESTIGATIONJUDICIAL] ?? [], $register_certs_content[CERTIFICATION_INVESTIGATIONA11] ?? [], $spouse_certs_content[CERTIFICATION_INVESTIGATIONA11] ?? [])),
                        new Credit_debit_ratio(array_merge($certs_content[CERTIFICATION_GOVERNMENTAUTHORITIES], $certs_content[CERTIFICATION_INVESTIGATIONJUDICIAL] ?? [], $register_certs_content[CERTIFICATION_INVESTIGATIONA11] ?? [], $spouse_certs_content[CERTIFICATION_INVESTIGATIONA11] ?? [])),
                        new Credit_break_even_point($certs_content[CERTIFICATION_INCOMESTATEMENT]),
                        new Credit_revenue_stability($certs_content[CERTIFICATION_INCOMESTATEMENT]),
                        new Credit_smec_qualification(json_decode(json_encode($target), TRUE)),
                        new Credit_asset($target_meta_list),
                        new Credit_background($target_meta_list),
                        new Credit_guarantor($target_meta_list),
                        new Credit_human_resource($target_meta_list),
                        new Credit_job_seniority($target_meta_list),
                        new Credit_team_seniority($target_meta_list),
                    ];
                    break;
                case INDUSTRY_CODE_MERCHANDISING_SECTOR:
                case INDUSTRY_CODE_SERVICE:
                    $credit_list = [
                        new Credit_industry_life_cycle($certs_content[CERTIFICATION_GOVERNMENTAUTHORITIES]),
                        new Credit_year_in_business($certs_content[CERTIFICATION_GOVERNMENTAUTHORITIES]),
                        new Credit_registered_capital($certs_content[CERTIFICATION_GOVERNMENTAUTHORITIES]),
                        new Credit_per_captial_output(array_merge($certs_content[CERTIFICATION_INCOMESTATEMENT] ?? [],
                            $certs_content[CERTIFICATION_EMPLOYEEINSURANCELIST] ?? [], $certs_content[CERTIFICATION_PROFILEJUDICIAL] ?? [])),
                        new Credit_average_collection_period($certs_content[CERTIFICATION_INCOMESTATEMENT]),
                        new Credit_days_payable_outstanding($certs_content[CERTIFICATION_INCOMESTATEMENT]),
                        new Credit_days_sales_of_inventory($certs_content[CERTIFICATION_INCOMESTATEMENT]),
                        new Credit_gross_margin($certs_content[CERTIFICATION_INCOMESTATEMENT]),
                        new Credit_business_finance_ratio(array_merge($certs_content[CERTIFICATION_INCOMESTATEMENT] ?? [],
                            $certs_content[CERTIFICATION_INVESTIGATIONJUDICIAL] ?? [], $register_certs_content[CERTIFICATION_INVESTIGATIONA11] ?? [],
                            $spouse_certs_content[CERTIFICATION_INVESTIGATIONA11] ?? [])),
                        new Credit_debit_ratio(array_merge($certs_content[CERTIFICATION_GOVERNMENTAUTHORITIES] ?? [],
                            $certs_content[CERTIFICATION_INVESTIGATIONJUDICIAL] ?? [], $register_certs_content[CERTIFICATION_INVESTIGATIONA11] ?? [],
                            $spouse_certs_content[CERTIFICATION_INVESTIGATIONA11] ?? [])),
                        new Credit_break_even_point($certs_content[CERTIFICATION_INCOMESTATEMENT]),
                        new Credit_revenue_stability($certs_content[CERTIFICATION_INCOMESTATEMENT]),
                        new Credit_smec_qualification(json_decode(json_encode($target), TRUE)),
                        new Credit_asset($target_meta_list),
                        new Credit_background($target_meta_list),
                        new Credit_guarantor($target_meta_list),
                        new Credit_human_resource($target_meta_list),
                        new Credit_job_seniority($target_meta_list),
                        new Credit_team_seniority($target_meta_list),
                    ];
                    break;
            }
        }

        $score_list = [];

        foreach ($credit_list as $credit_entity)
        {
            $credit_entity->scoring();
            $score_list[] = [
                'item' => $credit_entity->get_item(),
                'subitem' => $credit_entity->get_subitem(),
                'option' => $credit_entity->get_option(),
                'score' => $credit_entity->get_score(),
            ];
            $total += $credit_entity->get_score();
        }

        if ($approvalExtra && $approvalExtra->getExtraPoints())
        {
            $total += $approvalExtra->getExtraPoints();
        }
        $param['points'] = intval($total);

        if ($mix_credit)
        {
            return $param['points'];
        }

        $param['level'] = $this->get_credit_level($total, $product_id, $sub_product_id);

        // 取得額度對照表
        if (isset($this->credit['credit_amount_' . $product_id . '_' . $sub_product_id]))
        {
            $credit_amount = $this->credit['credit_amount_' . $product_id . '_' . $sub_product_id];
        }
        else
        {
            $credit_amount = $this->credit['credit_amount_' . $product_id];
        }

        if ( ! empty($credit_amount))
        {
            foreach ($credit_amount as $key => $value)
            {
                if ($param['points'] >= $value['start'] && $param['points'] <= $value['end'])
                {
                    $param['amount'] = $value['amount'];
                    break;
                }
            }
        }

        $param['expire_time'] = $expire_time;

        // 額度不能「小」於產品的最「小」允許額度
        $param['amount'] = $param['amount'] < (int) $this->product_list[$product_id]['loan_range_s'] ? 0 : $param['amount'];

        // 額度不能「大」於產品的最「大」允許額度
        $param['amount'] = min($this->product_list[$product_id]['loan_range_e'], $param['amount']);

        if ($approvalExtra && $approvalExtra->shouldSkipInsertion() || $credit['level'] == 10)
        {
            $param['score_list'] = json_encode($score_list);
            return $param;
        }
        $this->CI->credit_model->update_by(
            [
                'product_id' => $product_id,
                'sub_product_id' => $sub_product_id,
                'user_id' => $user_id,
                'status' => 1
            ],
            ['status' => 0]
        );
        $param['remark'] = json_encode(['score_list' => $score_list]);
        return $this->CI->credit_model->insert($param);
    }

    private function set_fixed_amount_into_param($param, $product_id, $sub_product_id, $approvalExtra, $stage_cer)
    {
        if(!isset($approvalExtra)){
            return $param;
        }

        $fixed_amount = $approvalExtra->get_fixed_amount();
        $loan_range_s = $this->product_list[$product_id]['loan_range_s'];
        $loan_range_e = $this->product_list[$product_id]['loan_range_e'];
        if ($this->is_valid_fixed_amount($fixed_amount, $loan_range_s,
                $loan_range_e) === FALSE) {
            return $param;
        }
        // old：由二審人員key額度，則該戶信評等級則為上班族貸最低之可授信信評（目前為9），分數要給「671」
        // new：比對fixed_amount是否與舊額度相同，若相同則不做調整，若小於舊額度則調整為9等級，若大於舊額度則取新計算額度
        // 2024-01-25: 用fixed_amount去找對應的score，再找到對應的level
        $param['amount'] = $fixed_amount;
        $param['points'] = $this->get_credit_score_with_credit_amount($fixed_amount, $product_id,
            $sub_product_id, $stage_cer);
        $param['level']  = $this->get_credit_level($param['points'], $product_id, $sub_product_id,
            $stage_cer);

        $tmp_remark = json_decode($param['remark'], TRUE);
        if (isset($tmp_remark['scoreHistory'])) {
            $tmp_remark['scoreHistory'][] = '--- 由二審人員調整額度 ---';
            $tmp_remark['scoreHistory'][] = "等級: {$param['level']}";
            $tmp_remark['scoreHistory'][] = "額度: {$param['amount']}";
        }
        $param['remark'] = json_encode($tmp_remark);
        return $param;
    }
    public function get_business_type_code($business_type)
    {
        $business_type_list = $this->CI->config->item('business_type_list');
        $business_prefix_type = substr($business_type ?? '', 0, 2);
        foreach ($business_type_list as $business_type_info)
        {
            if ($business_prefix_type >= $business_type_info['range'][0] && $business_prefix_type <= $business_type_info['range'][1])
            {
                return $business_type_info['code'];
            }
        }
        return '';
    }

    public function get_school_point($school_name = '', $school_system = 0, $school_major = '', $school_department = FALSE, $sub_product_id = 0, $product_id = 0)
    {
		$point = 0;
        $score_history = [];
		if(!empty($school_name)){
			$school_list = $this->CI->config->item('school_points');
			$school_info = [];
			foreach($school_list['school_points'] as $key => $value){
				if(trim($school_name)==$value['name']){
					$school_info = $value;
					break;
				}
			}

            if(!empty($school_info)) {

                // 取得學校得分
                if ($product_id == PRODUCT_ID_STUDENT && $sub_product_id == SUBPRODUCT_INTELLIGENT_STUDENT)
                {
                    // 名校貸
                    if (empty($school_info['intelligent_points']))
                    {
                        $schoolPoing = 0;
                        $score_history[] = '此申貸案為名校貸，但系統無設定對應的名校分數';
                    }
                    else
                    {
                        $schoolPoing = $school_info['intelligent_points'];
                    }
                }
                else
                {
                    // 一般學生貸
                    $schoolPoing = $school_info['points'];
                }

                $point = $schoolPoing;
                $score_history[] = '學校得分: '.$school_name.' = '.$schoolPoing;
                if($school_system == 0){
                    $point += 100;
                    $score_history[] = '學制: 學士 = 100';
                }else if($school_system==1){
                    $point += 400;
                    $score_history[] = '學制: 碩士 = 400';
                }else if($school_system==2){
                    $point += 500;
                    $score_history[] = '學制: 博士 = 500';
                }

				if($school_department) {
					$school_data = $school_list['department_points'];
					if(!empty($school_data)) {
						$schoolDepartmentPoint = 0;
						if (isset($school_data[$school_name]['score'][$school_department])) {
							$schoolDepartmentPoint = $school_data[$school_name]['score'][$school_department];
							$point += $schoolDepartmentPoint;
							$score_history[] = '大學科系加分: ' . $school_department . ' = ' . $schoolDepartmentPoint;
						} else {
							asort($school_data[$school_name]['score']);
							foreach ($school_data[$school_name]['score'] as $s) {
								$point += $s;
								$score_history[] = '大學科系加分: ' . $school_department . '(不在列表取該校科系最低加分) = ' . $s;
								break;
							}
						}
					}
				}
            }

            if (in_array($school_name, $school_list['lock_school']))
            {
                $score_history[] = "(WARNING: {$school_name}為黑名單學校)";
            }
		}
		return ['score_history' => $score_history, 'point' => $point, 'school_point' => $schoolPoing ?? 0];
	}

    /**
     * 取得畢業學校評分
     * @param string $school_name : 學校名稱
     * @param int $school_system : 學制
     * @return array
     */
    public function get_school_point_product_salary_man(string $school_name = '', int $school_system = 0): array
    {
        $score_history = [];
        $school_list = $this->CI->config->item('school_points');
        $school_points_list = array_column($school_list['school_points'], 'points', 'points');
        $min_points = min($school_points_list);
        $normal_coef = 0.3;
        $school_info = [];

        if (empty($school_name))
        {
            $point = $min_points * $normal_coef;
            $score_history[] = "未命中學校清單得分: {$min_points} * {$normal_coef}";
            return ['score_history' => $score_history, 'point' => $point];
        }

        foreach ($school_list['school_points'] as $value)
        {
            if (trim($school_name) == $value['name'])
            {
                $school_info = $value;
                break;
            }
        }

        if (empty($school_info))
        {
            $point = $min_points * $normal_coef;
            $score_history[] = "未命中學校清單得分: {$school_name} = {$min_points} * {$normal_coef}";
            return ['score_history' => $score_history, 'point' => $point];
        }

        // 取得學校得分
        $schoolPoing = $school_info['points'];

        $point = $schoolPoing * $normal_coef;
        $score_history[] = "學校得分: {$school_name} = {$schoolPoing} * {$normal_coef}";

        if ($school_system == 1)
        {
            $master_points = $school_info['master_points'] ?? 0;
            if ($master_points <= 0)
            {
                if ($school_info['national'] == 1)
                {
                    $point += (300 * 0.6);
                    $score_history[] = '學制: 碩士(國立/公立) = 300 * 0.6';
                }
                else
                {
                    $point += (200 * 0.6);
                    $score_history[] = '學制: 碩士(私立) = 200 * 0.6';
                }
            }
            else
            {
                $point += ($master_points * 0.6);
                $score_history[] = "學制: 碩士({$school_name}) = {$master_points} * 0.6";
            }
        }
        else if ($school_system == 2)
        {
            $point += (400 * 0.6);
            $score_history[] = '學制: 博士 = 400 * 0.6';
        }
        return ['score_history' => $score_history, 'point' => $point];
    }

    /**
     * 取得財務評分
     * @param $data : raw data of p2p_user.user_meta
     * @return array
     */
    private function get_financial_point_product_salary_man($data): array
    {
        $point = 0;
        $score_history = [];
        // 1.服務機構名稱
        // 世界500大企業: 300
        // 公家機關: 250
        // 台灣1000大企業: 前100大得300分、101-500得200分、501-1000得100分
        // 台灣公私立醫院: 醫學中心300分、區域醫院200分、其他100分
        // 其他機構: 50

        $job_company_point_max = max([
            $data['job_company_world_500_point'] ?? 0,
            $data['job_company_public_agency_point'] ?? 0,
            $data['job_company_taiwan_1000_point'] ?? 0,
            $data['job_company_medical_institute_point'] ?? 0,
            50
        ]);
        $point += $job_company_point_max;
        $score_history[] = '服務機構: ' . $job_company_point_max;

        // 2.職業情況
        if (isset($data['job_type'])) {
            $job_type_point =  $data['job_type'] ? 100 : 50;
            $point += $job_type_point;
            $score_history[] = '職務性質(內/外勤): ' . $job_type_point;
        }
        $data_job_salary = (int) $data['job_salary'] ?? 0;
        if (isset($data['job_salary'])) {
            $job_salary_point = $this->get_job_salary_point($data_job_salary);
            $point += $job_salary_point;
            $score_history[] = '薪資: ' . $job_salary_point;
        }
        if ( ! empty($data['job_has_license']) && $data['job_has_license'] === '1')
        {
            $job_license_point = 100;
            $point += $job_license_point;
            $score_history[] = '提供專業證書: ' . $job_license_point;
        }
        if (isset($data['job_employee'])) {
            $job_employee_point = $this->get_job_employee_point((int) $data['job_employee']);
            $point += $job_employee_point;
            $score_history[] = '任職公司規模: ' . $job_employee_point;
        }
        if (isset($data['job_position'])) {
            $job_position_point = $this->get_job_position_point(intval($data['job_position']), $data_job_salary);
            $point += $job_position_point;
            $score_history[] = '職位: ' . $job_position_point;
        }
        if (isset($data['job_seniority'])) {
            $job_seniority_point = $this->get_job_seniority_point((int) $data['job_seniority'], $data_job_salary);
            $point += $job_seniority_point;
            $score_history[] = '畢業以來的工作期間: ' . $job_seniority_point;
        }
        if (isset($data['job_company_seniority'])) {
            $job_company_seniority_point = $this->get_job_company_seniority_point((int) $data['job_company_seniority'], $data_job_salary);
            $point += $job_company_seniority_point;
            $score_history[] = '此公司工作期間: ' . $job_company_seniority_point;
        }
        if (isset($data['job_industry'])) {
            $job_industry_point = $this->get_job_industry_point($data['job_industry']);
            $point += $job_industry_point;
            $score_history[] = '公司類型: ' . $job_industry_point;
        }

        // 3.財務自評
        if (isset($data['financial_income']))
        {
            // 收入證明之收入數據需在自填收入數據的正負15%內，否則不給分
            $data_financial_income = (int) $data['financial_income'];
            if ($data_financial_income < $data_job_salary * (1 + 0.15) &&
                $data_financial_income > $data_job_salary * (1 - 0.15))
            {
                $point += 200;
                $score_history[] = '財務自評: 已提交且自填收入介於薪資正負15%內 = 200';
            }
        }

        return ['score_history' => $score_history, 'point' => $point];
    }

    /**
     * 取得世界 500 大企業的分數
     * @param $job_company : 任職公司名稱
     */
    public function get_job_company_in_world_500($job_company)
    {
        $this->CI->load->config('world_500');
        $world_500_list = $this->CI->config->item('world_500');
        return $world_500_list[$job_company] ?? '';
    }

    /**
     * 取得公家機關的分數
     * @param $job_company : 任職公司名稱
     */
    public function get_job_company_in_public_agency($job_company)
    {
        $this->CI->load->config('public_agency');
        $public_agency_list = $this->CI->config->item('public_agency');
        return $public_agency_list[$job_company] ?? '';
    }

    /**
     * 取得台灣 1000 大企業的分數
     * @param $job_company : 任職公司名稱
     */
    public function get_job_company_in_taiwan_1000($job_company)
    {
        $this->CI->load->config('taiwan_1000');
        $taiwan_1000_list = $this->CI->config->item('taiwan_1000');
        return $taiwan_1000_list[$job_company] ?? '';
    }

    /**
     * 取得醫療院所的分數
     * @param $job_company : 任職公司名稱
     */
    public function get_job_company_in_medical_institute($job_company)
    {
        $this->CI->load->config('medical_institute');
        $medical_institute_list = $this->CI->config->item('medical_institute');
        return $medical_institute_list[$job_company] ?? '';
    }

    /**
     * 取得薪資分數
     * @param $job_salary : 薪資
     * @return int
     */
	public function get_job_salary_point($job_salary = 0){
		$point 	= 0;
		if($job_salary >= 23000 && $job_salary <= 30000){
			$point = 50;
		}else if($job_salary > 30000 && $job_salary <= 35000){
			$point = 100;
		}else if($job_salary > 35000 && $job_salary <= 40000){
			$point = 150;
		}else if($job_salary > 40000 && $job_salary <= 45000){
			$point = 200;
		}else if($job_salary > 45000 && $job_salary <= 50000){
			$point = 250;
		}else if($job_salary > 50000){
			$point = 500;
		}
		return $point;
	}

	public function get_job_employee_point($employee = 0){
		switch ($employee) {
			case 1:
				return 50;
				break;
			case 2:
				return 100;
				break;
			case 3:
				return 150;
				break;
			case 4:
				return 200;
				break;
			case 5:
				return 250;
				break;
			case 6:
				return 300;
				break;
		}
		return 0;
	}

    /**
     * 取得職位分數
     * @param $position : 職位
     * @param $job_salary : 薪資
     * @return int
     */
	public function get_job_position_point($position = 0,$job_salary = 0){
		switch ($position) {
            case 1: // 初級管理
                return 100;
            case 2: // 中級管理
                return ($job_salary < 40000) ? 100 : 150;
            case 3: // 高級管理
                return ($job_salary < 50000) ? 100 : 200;
        }
        // 一般員工
        return 50;
	}

    /**
     * 取得總工作期間的分數
     * @param $seniority
     * @param $job_salary
     * @return int
     */
	public function get_job_seniority_point($seniority = 0,$job_salary = 0){
		switch ($seniority) {
            case 1: // 三個月至半年（含）
                return 50;
            case 2: // 半年至一年（含）
                return 100;
            case 3: // 一年至三年（含）
                return ($job_salary < 40000) ? 100 : 150;
            case 4: // 三年以上
                return ($job_salary < 50000) ? 100 : 200;
		}
		return 0;
	}

    /**
     * 取得現任職公司工作期間的分數
     * @param $seniority
     * @param $job_salary
     * @return int
     */
    public function get_job_company_seniority_point($seniority = 0, $job_salary = 0)
    {
        switch ($seniority)
        {
            case 1: // 三個月至半年（含）
                return 50;
            case 2: // 半年至一年（含）
                return 75;
            case 3: // 一年至三年（含）
                return 100;
            case 4: // 三年以上
                return ($job_salary < 50000) ? 100 : 150;
        }
        return 0;
    }

	public function get_job_industry_point($industry = ''){
        $point_mapping = [
            'A' => 100, 'B' => 100, 'C' => 100, 'D' => 150, 'E' => 100, 'F' => 100, 'G' => 100, 'H' => 100, 'I' => 100,
            'J' => 150, 'K' => 200, 'L' => 100, 'M' => 150, 'N' => 100, 'O' => 200, 'P' => 200, 'Q' => 200, 'R' => 100,
            'S' => 100, 'T' => 0, 'U' => 0, 'V' => 0, 'W' => 0, 'X' => 0, 'Y' => 0, 'Z' => 0,
        ];

        return $point_mapping[$industry] ?? 0;
	}

	public function get_investigation_times_point($times = 0){
		$point 	= 0;
		if($times >= 0 && $times <= 3){
			$point = 300;
		}else if($times > 3 && $times <= 6){
			$point = 200;
		}else if($times > 6 && $times <= 9){
			$point = 100;
		}
		return $point;
	}

	public function get_investigation_rate_point($rate = 0, $has_using_credit_card = 0){
        if ( ! $has_using_credit_card)
        {
            return 0;
        }
		$point 	= 0;
        if ($rate >= 0 && $rate <= 30)
        {
			$point = 300;
		}else if($rate > 30 && $rate <= 50){
			$point = 200;
		}else if($rate > 50 && $rate <= 70){
			$point = 100;
		}
		return $point;
	}

	public function get_investigation_months_point($months = 0){
		$point 	= 0;
		if($months >= 12){
			$point = 300;
		}else if($months >= 6 && $months < 12){
			$point = 200;
		}else if($months >= 3 && $months < 6){
			$point = 100;
		}
		return $point;
	}

	//取得信用評分
	public function get_credit($user_id,$product_id,$sub_product_id=0,$target=false){
        if ($target && is_array($target))
        {
            $target = json_decode(json_encode($target));
        }
		if($user_id && $product_id){
			$param = array(
				'user_id'			=> $user_id,
				'product_id'		=> $product_id,
                'sub_product_id' => $sub_product_id,
				'status'			=> 1,
				'expire_time >='	=> time(),
			);

            // 申貸額度若要共用，需要為同產品(product_id)、同期間(instalment)
            if (isset($target->instalment) && is_numeric($target->instalment))
            {
                $param['instalment'] = $target->instalment;
            }

			$rs 	= $this->CI->credit_model->order_by('created_at','desc')->get_by($param);
			if($rs){
                $data = [
				    'id'         => intval($rs->id),
					'level'		 => intval($rs->level),
					'points'	 => intval($rs->points),
					'amount'	 => intval($rs->amount),
					'instalment' => intval($rs->instalment),
					'created_at' => intval($rs->created_at),
                    'remark'     => $rs->remark,
					'expire_time'=> intval($rs->expire_time),
                    'sub_product_id' => (int) $rs->sub_product_id,
				];
                if($target){
                    $data['rate'] = $this->get_rate($rs->level,$target->instalment,$product_id,$sub_product_id,$target);
                    // 期數不同的評分要重新跑
                    if($target->instalment != $rs->instalment)
                        return FALSE;

                    $this->CI->load->library('target_lib');
                    if ($this->CI->target_lib->is_sub_loan($target->target_no) === TRUE &&
                        $target->product_id === PRODUCT_ID_STUDENT)
                    {
                        return $data;
                    }
                }

                $info = $this->CI->user_meta_model->get_by(['user_id' => $user_id, 'meta_key' => 'school_name']);
                if (isset($info->meta_value) && in_array($product_id, [PRODUCT_ID_STUDENT, PRODUCT_ID_STUDENT_ORDER]))
                {
                    $school_points_data = $this->get_school_point($info->meta_value);
                    $school_config = $this->CI->config->item('school_points');
                    // #2779: 命中黑名單學校給予固定信評為10、固定額度3,000元
                    if (in_array($info->meta_value, $school_config['lock_school']))
                    {
                        $this->get_lock_school_amount($product_id, $sub_product_id, $data, $target);
                    }
                    elseif (empty($school_points_data['point']))
                    {
                        $data['amount'] = 0;
                    }
				}
                return $data;
			}
		}
		return false;
	}
    //取得信用評分(從credit_sheet對應的credit取得)
    public function get_target_credit($user_id,$product_id,$sub_product_id=0,$target_id = 0,$target=false ){
        $target_info = $this->CI->target_model->get_by(['id' => $target_id]);
        if(!$target_info){
            return false;
        }
        $this->CI->load->model('loan/credit_sheet_model');
        $credit_sheet = $this->CI->credit_sheet_model->get_by(['target_id' => $target_id, 'credit_id !=' => 0, 'status !=' => 0,]);
        if(!$credit_sheet){
            return false;
        }

        if ($target && is_array($target))
        {
            $target = json_decode(json_encode($target));
        }
        if($user_id && $product_id){
            $param = ['id' => $credit_sheet->credit_id];

            // 申貸額度若要共用，需要為同產品(product_id)、同期間(instalment)
            if (isset($target->instalment) && is_numeric($target->instalment))
            {
                $param['instalment'] = $target->instalment;
            }

            $rs 	= $this->CI->credit_model->order_by('created_at','desc')->get_by($param);
            if($rs){
                $data = [
                    'id'         => intval($rs->id),
                    'level'		 => intval($rs->level),
                    'points'	 => intval($rs->points),
                    'amount'	 => intval($rs->amount),
                    'instalment' => intval($rs->instalment),
                    'created_at' => intval($rs->created_at),
                    'remark'     => $rs->remark,
                    'expire_time'=> intval($rs->expire_time),
                    'sub_product_id' => (int) $rs->sub_product_id,
                ];
                if($target){
                    $data['rate'] = $this->get_rate($rs->level,$target->instalment,$product_id,$sub_product_id,$target);
                    // 期數不同的評分要重新跑
                    if($target->instalment != $rs->instalment)
                        return FALSE;

                    $this->CI->load->library('target_lib');
                    if ($this->CI->target_lib->is_sub_loan($target->target_no) === TRUE &&
                        $target->product_id === PRODUCT_ID_STUDENT)
                    {
                        return $data;
                    }
                }

                $info = $this->CI->user_meta_model->get_by(['user_id' => $user_id, 'meta_key' => 'school_name']);
                if (isset($info->meta_value) && in_array($product_id, [PRODUCT_ID_STUDENT, PRODUCT_ID_STUDENT_ORDER]))
                {
                    $school_points_data = $this->get_school_point($info->meta_value);
                    $school_config = $this->CI->config->item('school_points');
                    // #2779: 命中黑名單學校給予固定信評為10、固定額度3,000元
                    if (in_array($info->meta_value, $school_config['lock_school']))
                    {
                        $this->get_lock_school_amount($product_id, $sub_product_id, $data, $target);
                    }
                    elseif (empty($school_points_data['point']))
                    {
                        $data['amount'] = 0;
                    }
                }
                return $data;
            }
        }
        return false;
    }

    public function get_lock_school_amount($product_id, $sub_product_id, &$data, $target = FALSE)
    {
        $this->CI->config->load('credit', TRUE);
        $credit_level_config = $this->CI->config->item('credit')['credit_level_' . $product_id];
        $credit_amount_config = $this->CI->config->item('credit')['credit_amount_' . $product_id];

        $data['level'] = 10;
        $level_max_points = $credit_level_config[$data['level']]['end'];

        $left = 0;
        $right = count($credit_amount_config);

        $amount = $points = 0;
        while ($left < $right)
        {
            $tmp = (int) (($left + $right) / 2);
            if ($credit_amount_config[$tmp]['start'] > $level_max_points)
            {
                $left = $tmp;
                continue;
            }

            if ($credit_amount_config[$tmp]['end'] < $level_max_points)
            {
                $right = $tmp;
                continue;
            }

            $amount = $credit_amount_config[$tmp]['amount'];
            $points = min($credit_amount_config[$tmp]['end'], $data['points']);
            break;
        }

        $data['amount'] = $amount;
        $data['points'] = $points;
        if ($target)
        {
            $data['rate'] = $this->get_rate($data['level'], $target->instalment, $product_id, $sub_product_id, $target);
        }
    }

    public function get_credit_level($points = 0, $product_id = 0, $sub_product_id = 0, $stage_cer = FALSE)
    {
        if (!$product_id) {
            return False;
        }
        if (intval($points) < 0 && !$stage_cer) {
            return False;
        }

        $credit_level_list = $this->credit['credit_level_' . $product_id . '_' . $sub_product_id] ??
            $this->credit['credit_level_' . $product_id] ??
            [];
        if(empty($credit_level_list)){
            return FALSE;
        }

        foreach ($credit_level_list as $level => $value) {
            if ($points >= $value['start'] && $points <= $value['end']) {
                return $level;
            }
        }
        return FALSE;
    }

    /**
     * @param int $credit_amount
     * @param int $product_id
     * @param int $sub_product_id
     * @param bool $stage_cer
     * @return int
     */
    public function get_credit_score_with_credit_amount(
        int  $credit_amount = 0,
        int  $product_id = 0,
        int  $sub_product_id = 0,
        bool $stage_cer = FALSE): int
    {
        if (!$product_id) {
            return 0;
        }
        if ($credit_amount <= 0 && !$stage_cer) {
            return 0;
        }

        $credit_amount_list = $this->get_credit_amount_list($product_id, $sub_product_id);
        if (empty($credit_amount_list)) {
            return 0;
        }

        // 用金額反推最低分數區間的最低分數
        // e.g. product_id 1, 150000 -> point 1471
        $min_score_end = 0;
        foreach ($credit_amount_list as $index => $range) {
            $range_amount = $range['amount'] ?? $range['max_amount'] ?? 0;
            if ($index == 0 && $credit_amount > $range_amount) {
                //                不能超過最大值
                return $min_score_end;
            }

            if ($credit_amount > $range_amount) {
                break;
            }
            $min_score_end = $range['start'];
        }
        return $min_score_end;
    }

    public function get_rate($level, $instalment, $product_id, $sub_product_id = 0, $target = []){
        $credit            = $this->CI->config->item('credit');
        $credit_level_list = $credit['credit_level_' . $product_id];
        if (isset($credit['credit_level_' . $product_id . '_' . $sub_product_id]))
        {
            $credit_level_list = $credit['credit_level_' . $product_id . '_' . $sub_product_id];
        }
        if (isset($credit_level_list[$level]))
        {
            if (isset($credit_level_list[$level]['rate'][$instalment]))
            {
                $rate = $credit_level_list[$level]['rate'][$instalment];
                //副產品減免
                if($sub_product_id){
                    $info        = $this->CI->user_meta_model->get_many_by(['user_id'=>$target->user_id]);
                    $data = [];
                    foreach ($info as $key => $value)
                    {
                        $data[$value->meta_key] = $value->meta_value;
                    }
                    $sub_product = $this->get_sub_product_data($sub_product_id);
                    //techie
                    if ($sub_product && $sub_product_id == 1)
                    {
                        if ( ! empty($data['school_department']))
                        {
                            $rate -= in_array($data['school_department'], $sub_product->majorList) ? 1 : 0;
                        }
                        if ($product_id == 1)
                        {
                            $rate -= ! empty($data['student_license_level']) && is_numeric($data['student_license_level']) ? $data['student_license_level'] * 0.5 : 0;
                            $rate -= ! empty($data['student_game_work_level']) && is_numeric($data['student_game_work_level']) ? $data['student_game_work_level'] * 0.5 : 0;
                        }
                        elseif ($product_id == 3)
                        {
                            $rate -= ! empty($data['job_license']) ? (int) $data['job_license'] * 0.5 : 0;
                            //工作認證減免%
                            if (isset($sub_product->titleList->{$data['job_title']}))
                            {
                                $rate -= ! empty($data['job_title']) ? $sub_product->titleList->{$data['job_title']}->level : 0;
                            }
                        }
                    }
                    $product_info 	= $this->CI->config->item('product_list')[$target->product_id];
                    $rate<$product_info['interest_rate_s']?$rate=$product_info['interest_rate_s']:'';
                }
				return $rate;
			}
		}
		return false;
	}

    /**
     * @param int $product_id
     * @param int $sub_product_id
     * @return array
     */
    public function get_credit_amount_list(int $product_id = 0, int $sub_product_id = 0): array
    {
        if (!$product_id) {
            return [];
        }
        return $this->credit['credit_amount_' . $product_id . '_' . $sub_product_id] ??
            $this->credit['credit_amount_' . $product_id] ??
            [];
    }

    /**
     * @param array $credit_amount_list
     * @param int $param_amount
     * @param int $param_point
     * @return int
     */
    public function get_amount_from_credit_amount_list(array $credit_amount_list, int $param_amount, int $param_point):
    int
    {
        if (empty($credit_amount_list)) {
            return $param_amount;
        }

        foreach ($credit_amount_list as $range) {
            if ($param_point >= $range['start'] && $param_point <= $range['end']) {
                return $range['amount'];
            }
        }
        return $param_amount;
    }

	public function delay_credit($user_id,$delay_days=0){
		if($user_id && $delay_days > GRACE_PERIOD){
			$param = array(
				'user_id'			=> $user_id,
			);

			$amount 		= 0;
			$points 		= -1;
			$level 			= 11;

			if($delay_days>30){
				$points 	= -501;
				$level 		= 12;
			}

			if($delay_days>60){
				$points 	= -1501;
				$level 		= 13;
			}

			$product_id = [];
			$rs 		= $this->CI->credit_model->order_by('created_at','desc')->get_many_by($param);
			if($rs){
				foreach($rs as $key => $value){
					if($value->level != $level){
						$this->CI->credit_model->update($value->id,['status'=>0]);
						$product_id[$value->product_id] = $value->product_id;
					}
				}

				if($product_id){
					foreach($product_id as $key => $value){
						$param = array(
							'user_id'		=> $user_id,
							'product_id'	=> $value,
							'points'		=> $points,
							'amount'		=> $amount,
							'level'			=> $level,

						);
						$rs = $this->CI->credit_model->insert($param);
					}
				}

				return $level;
			}
		}
		return false;
	}

    //取得最高歸戶額度
    public function get_user_max_credit_amount($user_id){
        if($user_id){
            $param = array(
                'user_id'			=> $user_id,
                'status'			=> 1,
                'expire_time >='	=> time(),
            );
            $rs 	= $this->CI->credit_model->order_by('amount','desc')->get_by($param);
            if($rs){
                return $rs->amount;
            }
        }
        return false;
    }

    private function get_sub_product_data($sub_product_id){
	    if(isset($this->CI->config->item('sub_product_mapping')[$sub_product_id])){
            $sub_product_mapping = $this->CI->config->item('sub_product_mapping')[$sub_product_id];
            $this->CI->config->load('sub_product',TRUE);
            $get_list = $this->CI->config->item('sub_product');
            return $get_list[$sub_product_mapping];
        }
	    return false;
    }

    /**
     * 取得使用者申請同產品的剩餘額度
     * @param int $user_id
     * @param int $product_id
     * @param int $sub_product_id
     * @param int $except_target_id
     * @return array
     */
    public function get_remain_amount(int $user_id, int $product_id, int $sub_product_id, int $except_target_id = 0)
    {
        $result = [
            'credit_amount' => 0, // 核可額度
            'target_amount' => 0, // 佔用中的額度
            'remain_amount' => 0, // 剩餘可用額度
            'user_available_amount' => 0, // 使用者可動用的額度(符合產品設定、千元表達)
            'instalment' => 0,
            'credit_level' => 0,
        ];

        // 撈取同產品的最新一筆核可資訊
        $credit = $this->get_credit($user_id, $product_id,
            $sub_product_id == STAGE_CER_TARGET ? 0 : $sub_product_id);
        if ($credit && isset($credit['sub_product_id']) && $credit['sub_product_id'] == $sub_product_id)
        {
            $used_amount = 0;
            $other_used_amount = 0;

            //取得所有產品申請或進行中的案件
            $target_list = $this->CI->target_model->get_many_by([
                'id !=' => $except_target_id,
                'user_id' => $user_id,
                'status NOT' => [TARGET_CANCEL, TARGET_FAIL, TARGET_REPAYMENTED]
            ]);
            if ($target_list)
            {
                foreach ($target_list as $value)
                {
                    if ($product_id == $value->product_id)
                    {
                        $used_amount = $used_amount + intval($value->loan_amount);
                    }
                    else
                    {
                        $other_used_amount = $other_used_amount + intval($value->loan_amount);
                    }
                    //取得案件已還款金額
                    $pay_back_transactions = $this->CI->transaction_model->get_many_by(array(
                        'source' => SOURCE_PRINCIPAL,
                        'user_from' => $user_id,
                        'target_id' => $value->id,
                        'status' => TRANSACTION_STATUS_PAID_OFF
                    ));
                    //扣除已還款金額
                    foreach ($pay_back_transactions as $value2)
                    {
                        if ($product_id == $value->product_id)
                        {
                            $used_amount = $used_amount - intval($value2->amount);
                        }
                        else
                        {
                            $other_used_amount = $other_used_amount - intval($value2->amount);
                        }
                    }
                }
                //無條件進位使用額度(千元) ex: 1001 ->1100
                $used_amount = $used_amount % 1000 != 0 ? ceil($used_amount * 0.001) * 1000 : $used_amount;
                $other_used_amount = $other_used_amount % 1000 != 0 ? ceil($other_used_amount * 0.001) * 1000 : $other_used_amount;
            }

            $all_used_amount = $used_amount + $other_used_amount;
            if ($credit['amount'] > $all_used_amount)
            {
                $remain_amount = $credit['amount'] - $all_used_amount;

                // 額度需符合產品設定的上下限
                $this->CI->load->library('loanmanager/product_lib');
                $product_info = $this->CI->product_lib->getProductInfo($product_id, $sub_product_id);
                if ($remain_amount < $product_info['loan_range_s'])
                {
                    $user_available_amount = 0;
                }
                else
                {
                    $user_available_amount = min($product_info['loan_range_e'], $remain_amount);
                    $user_available_amount = (int) (floor($user_available_amount / 1000) * 1000);
                }
            }
            else
            {
                $remain_amount = 0;
                $user_available_amount = 0;
            }

            $result = [
                'credit_amount' => $credit['amount'], // 核可額度
                'target_amount' => $all_used_amount, // 佔用中的額度
                'remain_amount' => $remain_amount, // 剩餘可用額度
                'user_available_amount' => $user_available_amount,
                'instalment' => $credit['instalment'],
                'credit_level' => $credit['level'] ?? 0
            ];
        }

        return $result;
    }

    public function get_credit_max_amount($points, $product_id, $sub_product_id = 0)
    {
        $loan_range_end = $this->product_list[$product_id]['loan_range_e'];
        $credit_amount_list = $this->credit['credit_amount_' . $product_id];
        if (isset($this->credit['credit_amount_' . $product_id . '_' . $sub_product_id]))
        {
            $credit_amount_list = $this->credit['credit_amount_' . $product_id . '_' . $sub_product_id];
        }

        if (isset($credit_amount_list))
        {
            foreach ($credit_amount_list as $value)
            {
                if ($points >= $value['start'] && $points <= $value['end'] && isset($value['max_amount']))
                {
                    $loan_range_end = $value['max_amount'];
                    break;
                }
            }
        }

        return $loan_range_end;
    }

    public function get_markup_amount($user_id)
    {
        $result = [
            // key: 原核定額度可以乘以的倍率 (單位 %)
            // value: 達成該條件的敘述
        ];
        $this->CI->load->model('transaction/transaction_model');

        // 歷史還款紀錄
        $repayment_date_list = $this->CI->transaction_model->get_repayment_date($user_id);
        $flag_on_time = 0;
        foreach ($repayment_date_list as $value)
        {
            if (empty($value['entering_date']) || empty($value['limit_date']))
            {
                continue;
            }
            $entering_date = new DateTimeImmutable($value['entering_date']);
            $limit_date = new DateTimeImmutable($value['limit_date']);
            if ($entering_date === $limit_date)
            {
                $flag_on_time++;
            }
        }
        if ($flag_on_time > 10)
        {
            $result[200][] = '非寬限期還款>10次';
        }
        elseif ($flag_on_time > 5)
        {
            $result[150][] = '非寬限期還款>5次';
        }
        elseif ($flag_on_time > 3)
        {
            $result[120][] = '非寬限期還款>3次';
        }

        // 正常結案
        $normal_repayment_list = $this->CI->transaction_model->get_normal_repayment($user_id);
        switch (count($normal_repayment_list))
        {
            case 0:
                break;
            case 1:
                $result[110][] = '正常結案1次';
                break;
            case 2:
                $result[130][] = '正常結案2次';
                break;
            default:
                $result[150][] = '正常結案3次(含)以上';
        }

        // 提前還款
        $prepayment_target_list = $this->CI->transaction_model->get_prepayment_target($user_id);
        switch (count($prepayment_target_list))
        {
            case 0:
                break;
            case 1:
                $result[120][] = '提前還款1次';
                break;
            case 2:
                $result[150][] = '提前還款2次';
                break;
            default:
                $result[200][] = '提前還款3次(含)以上';
        }

        // 累計清償總金額
        $repayment_amount_list = $this->CI->transaction_model->get_repayment_amount($user_id);
        $repayment_amount_list = array_column($repayment_amount_list, 'total_amount', 'product_id');
        $amount_1 = $repayment_amount_list[PRODUCT_ID_STUDENT] ?? 0;
        $amount_3 = $repayment_amount_list[PRODUCT_ID_SALARY_MAN] ?? 0;
        $amount_1_and_3 = $amount_1 + $amount_3;
        if ($amount_1 > 150000)
        {
            $result[200][] = '累計清償總金額，學生貸大於15萬';
        }
        elseif ($amount_1 > 100000)
        {
            $result[150][] = '累計清償總金額，學生貸大於10萬';
        }
        elseif ($amount_1 > 50000)
        {
            $result[120][] = '累計清償總金額，學生貸大於5萬';
        }
        if ($amount_1_and_3 > 500000)
        {
            $result[200][] = '累計清償總金額，(學生貸+上班族貸)大於50萬';
        }
        elseif ($amount_1_and_3 > 300000)
        {
            $result[150][] = '累計清償總金額，(學生貸+上班族貸)大於30萬';
        }
        elseif ($amount_1_and_3 > 150000)
        {
            $result[120][] = '累計清償總金額，(學生貸+上班族貸)大於15萬';
        }

        return $result;
    }

    public function is_valid_fixed_amount(int $fixed_amount, int $product_loan_range_s, int $product_loan_range_e): bool
    {
        if ($fixed_amount == 0 || $fixed_amount < $product_loan_range_s || $fixed_amount > $product_loan_range_e)
        {
            return FALSE;
        }
        return TRUE;
    }
}

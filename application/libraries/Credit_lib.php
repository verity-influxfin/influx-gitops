<?php

defined('BASEPATH') OR exit('No direct script access allowed');

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
	public function approve_credit($user_id,$product_id,$sub_product_id=0, $approvalExtra = null, $stage_cer = false, $credit = false, $mix_credit = false, $instalment = 0){
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
				$rs = $this->$method($user_id,$product_id,$sub_product_id,$expire_time, $approvalExtra, $stage_cer, $credit, $mix_credit, $instalment);
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

	private function approve_1($user_id,$product_id,$sub_product_id,$expire_time, $approvalExtra, $stage_cer, $credit, $mix_credit, $instalment){

        $total = 0;
        $param = [
            'product_id' => $product_id,
            'sub_product_id' => $sub_product_id,
            'user_id' => $user_id,
            'amount' => 0,
            'instalment' => $instalment
        ];

        $this->CI->config->load('credit', TRUE);
        $instalment_modifier_list = $this->CI->config->item('credit')['credit_instalment_modifier_' . $product_id];

	    if($stage_cer == 0) {
            $info = $this->CI->user_meta_model->get_many_by(['user_id' => $user_id]);
            $user_info = $this->CI->user_model->get($user_id);
            $this->CI->load->model('user/user_certification_model');

            $data = [];
            foreach ($info as $key => $value) {
                $data[$value->meta_key] = $value->meta_value;
            }

            // 學校
            if (isset($data['school_name']) && ! empty($data['school_name']))
            {
                $total += $this->get_school_point(
                    $data['school_name'],
                    $data['school_system'],
                    $data['school_major'],
                    $data['school_department'],
                    $sub_product_id,
                    $product_id
                );
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
            }

            $param['points'] = intval($total);

        }
        if(in_array($stage_cer,[1,2])){
            $param['points'] = $total = 100;
        }

        if($mix_credit){
            return $param['points'];
        }

        $param['level'] 	= $this->get_credit_level($total,$product_id,$sub_product_id);

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
                if($param['points']>=$value['start'] && $param['points']<=$value['end']){
                    $param['amount'] = $value['amount'];
                    break;
                }
            }
        }

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
		$param['amount'] = min($this->product_list[$product_id]['loan_range_e'], $param['amount']);

		if ($approvalExtra && $approvalExtra->shouldSkipInsertion() || $credit['level'] == 10) {
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
        $param['remark'] = json_encode(['scoreHistory' => $this->scoreHistory]);
        $rs 		= $this->CI->credit_model->insert($param);
		return $rs;
	}

	private function approve_2($user_id,$product_id,$sub_product_id,$expire_time, $approvalExtra, $stage_cer, $credit, $mix_credit, $instalment){
		return $this->approve_1($user_id,$product_id,$sub_product_id,$expire_time, $approvalExtra, $stage_cer, $credit, $mix_credit, $instalment);
	}

	private function approve_3($user_id,$product_id,$sub_product_id,$expire_time, $approvalExtra, $stage_cer, $credit, $mix_credit, $instalment){
        $total = 0;
        $time = time();
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

        //畢業學校
        if (isset($data['diploma_name']) && !empty($data['diploma_name'])) {
            $total += intval($this->get_school_point($data['diploma_name'], $data['diploma_system'], '', $data['diploma_department'])) * 0.6;
        }

        if (isset($data['job_type'])) {
            $job_type_point =  $data['job_type'] ? 50 : 100;
            $total += $job_type_point;
            $this->scoreHistory[] = '職務性質(內/外勤): ' . $job_type_point;
        }

        if (isset($data['job_salary'])) {
            $job_salary_point = $this->get_job_salary_point(intval($data['job_salary']));
            $total += $job_salary_point;
            $this->scoreHistory[] = '薪資: ' . $job_salary_point;
        }

        if (isset($data['job_license']) && $data['job_license']) {
            $job_license_point = 100;
            $total += $job_license_point;
            $this->scoreHistory[] = '提供專業證書: ' . $job_license_point;
        }

        if (isset($data['job_employee'])) {
            $job_employee_point =  $this->get_job_employee_point(intval($data['job_employee']));
            $total += $job_employee_point;
            $this->scoreHistory[] = '任職公司規模: ' . $job_employee_point;
        }

        if (isset($data['job_position'])) {
            $job_position_point = $this->get_job_position_point(intval($data['job_position']));
            $total += $job_position_point;
            $this->scoreHistory[] = '職位: ' . $job_position_point;
        }

        if (isset($data['job_seniority'])) {
            $job_seniority_point = $this->get_job_seniority_point(intval($data['job_seniority']), intval($data['job_salary']));
            $total += $job_seniority_point;
            $this->scoreHistory[] = '畢業以來的工作期間: ' . $job_seniority_point;
        }

        if (isset($data['job_company_seniority'])) {
            $job_company_seniority_point = $this->get_job_seniority_point(intval($data['job_company_seniority']), intval($data['job_salary']));
            $total += $job_company_seniority_point;
            $this->scoreHistory[] = '此公司工作期間: ' . $job_company_seniority_point;
        }

        if (isset($data['job_industry'])) {
            $job_industry_point = $this->get_job_industry_point($data['job_industry']);
            $total += $job_industry_point;
            $this->scoreHistory[] = '公司類型: ' . $job_industry_point;
        }

        //聯徵
        if (isset($data['investigation_status']) && !empty($data['investigation_status'])) {
            if (isset($data['investigation_times'])) {
                $investigation_times_point = $this->get_investigation_times_point(intval($data['investigation_times']));
                $total += $investigation_times_point;
                $this->scoreHistory[] = '聯徵查詢次數: ' . $investigation_times_point;
            }

            if (isset($data['investigation_credit_rate'])) {
                $investigation_credit_rate_point = $this->get_investigation_rate_point(intval($data['investigation_credit_rate']));
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

        if ($approvalExtra && $approvalExtra->getExtraPoints()) {
            $extra_point = $approvalExtra->getExtraPoints();
            $total += $extra_point;
            $this->scoreHistory[] = '二審專家調整: ' . $extra_point;
        }

        if ($stage_cer)
        {
            $total = 100;
            $this->scoreHistory = [
                '階段上架: 100'
            ];
        }

        $param['points'] = (int) $total;

        if($mix_credit){
            return $param['points'];
        }

        $param['level'] = $this->get_credit_level($total, $product_id, $sub_product_id, $stage_cer);
        if (isset($this->credit['credit_amount_' . $product_id])) {
            foreach ($this->credit['credit_amount_' . $product_id] as $key => $value) {
                if ($param['points'] >= $value['start'] && $param['points'] <= $value['end']) {
                    $salary = isset($data['job_salary']) ? intval($data['job_salary']) : 0;
                    $param['amount'] = $salary * $value['rate'];
                    break;
                }
            }
        }

        if($stage_cer != 0) {
            $expire_time = strtotime('+1 days', $time);
        }
        $param['expire_time'] = $expire_time;

        // 月薪低於特定值，不能超過特定倍數的額度
        if (!$stage_cer && intval($data['job_salary']) <= $this->product_list[$product_id]['condition_rate']['salary_below']) {
			$job_salary = intval($data['job_salary']) * $this->product_list[$product_id]['condition_rate']['rate'];
            $param['amount'] = intval(min($param['amount'], $job_salary));
        }

        // 額度調整 = 額度 * 性別對應的系數
        if ($user_info->sex == 'M')
        {
            // 男
            $param['amount'] *= 0.9;
            $this->scoreHistory[] = '性別男: 額度 * 0.9';
        }
        else
        {
            $this->scoreHistory[] = '性別女: 額度 * 1';
        }

        // 額度調整 = 額度 * 分期期數對應的系數
        $this->CI->config->load('credit', TRUE);
        $instalment_modifier_list = $this->CI->config->item('credit')['credit_instalment_modifier_' . $product_id];
        $param['amount'] = round($param['amount'] * ($instalment_modifier_list[$instalment] ?? 1));
        $this->scoreHistory[] = '借款期數' . $instalment . '期: 額度 * ' . ($instalment_modifier_list[$instalment] ?? 1);

        // 額度不能「小」於產品的最「小」允許額度
        $param['amount'] = $param['amount'] < (int) $this->product_list[$product_id]['loan_range_s'] ? 0 : $param['amount'];

        // 額度不能「大」於產品的最「大」允許額度
        $param['amount'] = min($this->product_list[$product_id]['loan_range_e'], $param['amount']);

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
        if($sub_product_id == STAGE_CER_TARGET && $time < $credit['expire_time']){
            $rs 		= $this->CI->credit_model->update($credit['id'],$param);
            return $rs;
        }
        $param['remark'] = json_encode(['scoreHistory' => $this->scoreHistory]);
        $rs 		= $this->CI->credit_model->insert($param);
		return $rs;
	}

	private function approve_4($user_id,$product_id,$sub_product_id,$expire_time,$approvalExtra, $stage_cer, $credit, $mix_credit, $instalment){
		return $this->approve_3($user_id,$product_id,$sub_product_id,$expire_time,$approvalExtra, $stage_cer, $credit, $mix_credit, $instalment);
	}

    private function approve_7($user_id,$product_id,$sub_product_id,$expire_time, $approvalExtra, $stage_cer, $credit, $mix_credit, $instalment){
        $rs = $this->approve_1($user_id,$product_id,$sub_product_id,$expire_time,$approvalExtra, $stage_cer, $credit, $mix_credit, $instalment);
        return $rs;
    }

    private function approve_8($user_id,$product_id,$sub_product_id,$expire_time,$approvalExtra, $stage_cer, $credit, $mix_credit, $instalment){
        $rs = $this->approve_3($user_id,$product_id,$sub_product_id,$expire_time,$approvalExtra, $stage_cer, $credit, $mix_credit, $instalment);
	    return $rs;
    }

    private function approve_9($user_id,$product_id,$sub_product_id,$expire_time, $approvalExtra, $stage_cer, $credit, $mix_credit, $instalment){
        $rs = $this->approve_1($user_id,$product_id,$sub_product_id,$expire_time,$approvalExtra, $stage_cer, $credit, $mix_credit, $instalment);
        return $rs;
    }

    private function approve_10($user_id,$product_id,$sub_product_id,$expire_time,$approvalExtra, $stage_cer, $credit, $mix_credit, $instalment){
        $rs = $this->approve_3($user_id,$product_id,$sub_product_id,$expire_time,$approvalExtra, $stage_cer, $credit, $mix_credit, $instalment);
        return $rs;
    }

    private function approve_1000($user_id,$product_id,$sub_product_id,$expire_time, $approvalExtra, $stage_cer, $credit, $mix_credit, $instalment){

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

    public function get_school_point($school_name = '', $school_system = 0, $school_major = '', $school_department = FALSE, $sub_product_id = 0, $product_id = 0)
    {
		$point = 0;
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
                        $this->scoreHistory[] = '此申貸案為名校貸，但系統無設定對應的名校分數';
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
                $this->scoreHistory[] = '學校得分:'.$school_name.' = '.$schoolPoing;
                if($school_system == 0){
                    $point += 100;
                    $this->scoreHistory[] = '學制:學士 = 100';
                }else if($school_system==1){
                    $point += 400;
                    $this->scoreHistory[] = '學制:碩士 = 400';
                }else if($school_system==2){
                    $point += 500;
                    $this->scoreHistory[] = '學制:博士 = 500';
                }

				if($school_department) {
					$school_data = $school_list['department_points'];
					if(!empty($school_data)) {
						$schoolDepartmentPoint = 0;
						if (isset($school_data[$school_name]['score'][$school_department])) {
							$schoolDepartmentPoint = $school_data[$school_name]['score'][$school_department];
							$point += $schoolDepartmentPoint;
							$this->scoreHistory[] = '大學科系加分:' . $school_department . ' = ' . $schoolDepartmentPoint;
						} else {
							asort($school_data[$school_name]['score']);
							foreach ($school_data[$school_name]['score'] as $s) {
								$point += $s;
								$this->scoreHistory[] = '大學科系加分:' . $school_department . '(不在列表取該校科系最低加分) = ' . $schoolDepartmentPoint;
								break;
							}
						}
					}
				}
            }
		}
		return $point;
	}

	public function get_job_salary_point($job_salary = 0){
		$point 	= 0;
		if($job_salary >= 23000 && $job_salary < 30000){
			$point = 50;
		}else if($job_salary >= 30000 && $job_salary < 35000){
			$point = 100;
		}else if($job_salary >= 35000 && $job_salary < 40000){
			$point = 150;
		}else if($job_salary >= 40000 && $job_salary < 45000){
			$point = 200;
		}else if($job_salary >= 45000 && $job_salary < 50000){
			$point = 250;
		}else if($job_salary >= 50000){
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

	public function get_job_position_point($position = 0,$job_salary = 0){
		switch ($position) {
			case 1:
                if($job_salary < 35000){
                    return 100;
                }else{
                    return 150;
                }
				break;
			case 2:
				return 200;
				break;
			case 3:
				return 300;
				break;
		}
		return 100;
	}

	public function get_job_seniority_point($seniority = 0,$job_salary = 0){
		switch ($seniority) {
            case 1: // 三個月至半年（含）
				return 100;
				break;
            case 2: // 半年至一年（含）
				return 150;
				break;
            case 3: // 一年至三年（含）
				if($job_salary < 40000){
					return 100;
				}else{
					return 200;
				}
				break;
            case 4: // 三年以上
				if($job_salary < 50000){
					return 100;
				}else{
					return 300;
				}
				break;
		}
		return 0;
	}

	public function get_job_industry_point($industry = ''){
		$point300 = ['K','O','Q','P'];
		$point200 = ['M','D','J'];

		if(in_array($industry,$point300)){
			return 300;
		}else if(in_array($industry,$point200)){
			return 200;
		}else{
			return 100;
		}
	}

	public function get_investigation_times_point($times = 0){
		$point 	= 0;
		if($times > 0 && $times <= 3){
			$point = 300;
		}else if($times > 3 && $times <= 6){
			$point = 200;
		}else if($times > 6 && $times <= 9){
			$point = 100;
		}
		return $point;
	}

	public function get_investigation_rate_point($rate = 0){
		$point 	= 0;
		if($rate > 0 && $rate <= 30){
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
		if($user_id && $product_id){
			$param = array(
				'user_id'			=> $user_id,
				'product_id'		=> $product_id,
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
					'expire_time'=> intval($rs->expire_time),
				];
                if($target){
                    $data['rate'] = $this->get_rate($rs->level,$target->instalment,$product_id,$sub_product_id,$target);
                    // 期數不同的評分要重新跑
                    if($target->instalment != $rs->instalment)
                        return FALSE;
                }

				$info = $this->CI->user_meta_model->get_by(['user_id' => $user_id, 'meta_key' => 'school_name']);
				if(isset($info->meta_value) && in_array($product_id, [1, 2])) {
                    $school_points_data = $this->get_school_point($info->meta_value);
                    $school_config = $this->CI->config->item('school_points');
                    // 黑名單的學校額度是0
                    if(in_array($info->meta_value,$school_config['lock_school']) || !$school_points_data){
                        $data['amount'] = 0;
                    }
				}
                return $data;
			}
		}
		return false;
	}

    public function get_credit_level($points = 0, $product_id = 0, $sub_product_id = 0, $stage_cer = FALSE)
    {
        if ((intval($points) > 0 || $stage_cer) && $product_id)
        {
            $credit_level_list = $this->credit['credit_level_' . $product_id];
            if (isset($this->credit['credit_level_' . $product_id . '_' . $sub_product_id]))
            {
                $credit_level_list = $this->credit['credit_level_' . $product_id . '_' . $sub_product_id];
            }
            if (isset($credit_level_list))
            {
                foreach ($credit_level_list as $level => $value)
                {
                    if ($points >= $value['start'] && $points <= $value['end'])
                    {
                        return $level;
                        break;
                    }
                }
            }

        }
        return FALSE;
    }

	public function get_rate($level,$instalment,$product_id,$sub_product_id=0,$target=[]){
		$credit = $this->CI->config->item('credit');
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
                    foreach($info as $key => $value){
                        $data[$value->meta_key] = $value->meta_value;
                    }
                    $sub_product = $this->get_sub_product_data($sub_product_id);
                    //techie
                    if ($sub_product && $sub_product_id == 1){
                        if(isset($data['school_department'])){
                            $rate -= in_array($data['school_department'],$sub_product->majorList)?1:0;
                        }
                        if ($product_id == 1){
                            $rate -= isset($data['student_license_level'])?$data['student_license_level']*0.5:0;
                            $rate -= isset($data['student_game_work_level'])?$data['student_game_work_level']*0.5:0;
                        }elseif ($product_id == 3){
                            $rate -= isset($data['job_license']) ? (int) $data['job_license'] * 0.5 : 0;
                            //工作認證減免%
                            if (isset($sub_product->titleList->{$data['job_title']}))
                            {
                                $rate -= isset($data['job_title']) ? $sub_product->titleList->{$data['job_title']}->level : 0;
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
            'instalment' => 0
        ];

        // 撈取同產品的最新一筆核可資訊
        $credit = $this->get_credit($user_id, $product_id,
            $sub_product_id == STAGE_CER_TARGET ? 0 : $sub_product_id);
        if ($credit)
        {
            $used_amount = 0;
            $other_used_amount = 0;
            $user_max_credit_amount = $this->get_user_max_credit_amount($user_id);
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

            $result = [
                'credit_amount' => $user_max_credit_amount, // 核可額度
                'target_amount' => $all_used_amount, // 佔用中的額度
                'remain_amount' => $user_max_credit_amount - $all_used_amount, // 剩餘可用額度
                'instalment' => $credit['instalment']
            ];
        }

        return $result;
    }
}

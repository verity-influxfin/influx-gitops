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
	public function approve_credit($user_id,$product_id,$sub_product_id=0, $approvalExtra = null, $stage_cer = false, $credit = false){
		if($user_id && $product_id){

            //信用低落
            $low = $this->CI->credit_model->order_by('level','desc')->get_by([
                'user_id'		=> $user_id,
                'status'		=> 1,
                'points <'		=> 0,
            ]);
            $expire_time     = $max_expire_time = strtotime("+6 months", time());

            if($low){
				$param = [
					'product_id' 	=> $product_id,
					'sub_product_id'=> $sub_product_id,
					'user_id'		=> $user_id,
					'points'		=> $low->points,
					'level'			=> $low->level,
					'amount'		=> $low->amount,
					'expire_time'   => $expire_time,
				];
				if ($approvalExtra && $approvalExtra->shouldSkipInsertion()) {
					return $param;
				}
                return $this->CI->credit_model->insert($param);
            }


            //few target
            $target  = $this->CI->target_model->order_by('loan_date','asc')->get_by([
                'user_id'     => $user_id,
                'status'      => 5,
                'loan_date >' => date('Y-m-d',strtotime("-2 months", time())),
            ]);
            if($target){
                $expire_time = strtotime("+2 months", strtotime($target->loan_date));
            }

			$method		= 'approve_'.$product_id;
			if(method_exists($this, $method)){
				$rs = $this->$method($user_id,$product_id,$sub_product_id,$expire_time, $approvalExtra, $stage_cer, $credit);
				return $rs;
			}
		}
		return false;
	}

	private function approve_1($user_id,$product_id,$sub_product_id,$expire_time, $approvalExtra, $stage_cer, $credit){

        $total = 0;
        $param = [
            'product_id' => $product_id,
            'sub_product_id' => $sub_product_id,
            'user_id' => $user_id,
            'amount' => 0
        ];
	    if($stage_cer == 0) {
            $info = $this->CI->user_meta_model->get_many_by(['user_id' => $user_id]);
            $user_info = $this->CI->user_model->get($user_id);
            $this->CI->load->model('user/user_certification_model');
            $user_certification_list = $this->CI->user_certification_model->get_many_by([
                'user_id' => $user_id,
                'status' => 1,
            ]);

            $transcript = false;
            if ($user_certification_list) {
                foreach ($user_certification_list as $key => $value) {
                    $data = json_decode($value->content);
                    if($value->certification_id == 2){
                        if(isset($data->transcript_image)){
                            !empty($data->transcript_image) ? $transcript = true : '';
                        }
                    }elseif($value->certification_id == 4){
                        if (isset($data->instagram->meta)) {
                            $three_month_ago = strtotime("-3 months", time());
                            if (count($data->instagram->meta) >= 10) {
                                $three_month_ago < $data->instagram->meta[9]->created_time ? $total += 100 : '';
                                $this->scoreHistory[] = '3個月內發文次數大於10次 = 100\n';
                            }
                            foreach ($data->instagram->meta as $igKey => $igValue) {
                                if (preg_match_all('/' . $this->CI->config->item('social_patten') . '/', $igValue->text)) {
                                    $total += 200;
                                    $this->scoreHistory[] = '發文關鍵字/全球、財經、數位、兩岸 = 200\n';
                                    break;
                                }
                            }
                            $last_social_cer_list = $this->CI->user_certification_model->order_by('created_at', 'desc')->get_many_by([
                                'user_id' => $user_id,
                                'certification_id' => 4,
                            ]);
                            if (is_array($last_social_cer_list) && count($last_social_cer_list) >= 2) {
                                foreach ($last_social_cer_list as $lastIgKey => $lastIgValue) {
                                    if ($three_month_ago >= $lastIgValue->created_at && $lastIgKey > 0) {
                                        $all_contents = json_decode($lastIgValue->content);
                                        $last_ig_follows = isset($all_contents->instagram) ? $all_contents->instagram->counts->follows : $all_contents->info->counts->follows;
                                        $data->instagram->counts->follows - $last_ig_follows > $last_ig_follows * 0.1 ? $total += 100 : '';
                                        $this->scoreHistory[] = '好友數>100且較3個月前增加10%以上 = 100\n';
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $data = [];
            foreach ($info as $key => $value) {
                $data[$value->meta_key] = $value->meta_value;
            }

            if ($sub_product_id) {
                $sub_product = $this->get_sub_product_data($sub_product_id);
                //techie
                if ($sub_product && $sub_product_id == 1) {
                    //系所加分
                    $total += in_array($data['school_department'], $sub_product->majorList) ? 200 : 0;
                    $this->scoreHistory[] = ' = 200\n';
                    $total += isset($data['student_game_work_level']) ? $data['student_game_work_level'] * 50 : 0;
                    $this->scoreHistory[] = ' = 50\n';
                    $total += isset($data['student_license_level']) ? $data['student_license_level'] * 50 : 0;
                    $this->scoreHistory[] = ' = 50\n';
                    $total += isset($data['student_pro_level']) ? $data['student_pro_level'] * 100 : 0;
                    $this->scoreHistory[] = ' = 100\n';
                }
            }

            //學校
            if (isset($data['school_name']) && !empty($data['school_name'])) {
                $total += $this->get_school_point($data['school_name'], $data['school_system'], $data['school_major'], $data['school_department']) ;
            }

            //財務證明
            if (isset($data['financial_status']) && !empty($data['financial_status'])) {
                $total += 50;
                $this->scoreHistory[] = '借款人提供個人財務數據表(自填) = 50\n';
                if (isset($data['financial_passbook']) && !empty($data['financial_passbook'])) {
                    $total += 100;
                    $this->scoreHistory[] = '近3個月存摺內頁/收入證明 = 100\n';
                }
                if (isset($data['financial_bill_phone']) && !empty($data['financial_bill_phone'])) {
                    $total += 100;
                    $this->scoreHistory[] = '提供電話費帳單 = 100\n';
                }
                if (isset($data['financial_creditcard']) && !empty($data['financial_creditcard'])) {
                    $total += 100;
                    $this->scoreHistory[] = '近期信用卡帳單 = 100\n';
                }
            }

            if (isset($data['line_access_token']) && !empty($data['line_access_token'])) {
                $total += 50;
                $this->scoreHistory[] = '提供社交帳戶認證LINE = 50\n';
            }

            //聯徵
            if (isset($data['investigation_status']) && !empty($data['investigation_status'])) {
                if (isset($data['investigation_times'])) {
                    $investigationTimes = round($this->get_investigation_times_point(intval($data['investigation_times']))/3);
                    $total += $investigationTimes;
                    $this->scoreHistory[] = '提供聯徵(使用次數) = '.$investigationTimes;
                }

                if (isset($data['investigation_credit_rate'])) {
                    $investigationCreditRate = round($this->get_investigation_rate_point(intval($data['investigation_credit_rate']))/3);
                    $total += $investigationCreditRate;
                    $this->scoreHistory[] = '提供聯徵(使用額度) = '.$investigationCreditRate;
                }

                if (isset($data['investigation_months'])) {
                    $investigationMonths = round($this->get_investigation_months_point(intval($data['investigation_months']))/3);
                    $total += $investigationMonths;
                    $this->scoreHistory[] = '提供聯徵(使用紀錄月份) = '.$investigationMonths;
                }
            }

            //SIP
            //if(!empty($data['student_sip_account']) && !empty($data['student_sip_password'])){
            //$total += 150;
            //}
            if (isset($data['transcript_front']) && !empty($data['transcript_front']) || $transcript) {
                $total += 50;
                $this->scoreHistory[] = '提供成績單 = 50';
            }
            //緊急聯絡人
            if (isset($data['emergency_relationship']) && $data['emergency_relationship'] == '監護人') {
                $total = $total - 400;//mantis 0000003
                $this->scoreHistory[] = '緊急聯絡人為監護人 = 400';
            }

            if ($approvalExtra && $approvalExtra->getExtraPoints()) {
                $total += $approvalExtra->getExtraPoints();
            }

            $total = $user_info->sex == 'M' ? round($total * 0.95) : $total;
            $this->scoreHistory[] = '性別:'.($user_info->sex == 'M' ? '男 * 0.95' : '女 * 1');
            $param['points'] = intval($total);

        }
        if(in_array($stage_cer,[1,2])){
            $param['points'] = $total = 100;
        }
        $param['level'] 	= $this->get_credit_level($total,$product_id);
        if(isset($this->credit['credit_amount_'.$product_id])){
            foreach($this->credit['credit_amount_'.$product_id] as $key => $value){
                if($param['points']>=$value['start'] && $param['points']<=$value['end']){
                    $param['amount'] = $value['amount'];
                    break;
                }
            }
        }
        $param['expire_time'] = $expire_time;
//      $param['scoreHistory'] = $this->scoreHistory;
		$param['amount'] = min($this->product_list[$product_id]['loan_range_e'], $param['amount']);

		if ($approvalExtra && $approvalExtra->shouldSkipInsertion() || $credit['level'] == 10) {
            return $param;
        }

        $rs 		= $this->CI->credit_model->insert($param);
		return $rs;
	}

	private function approve_2($user_id,$product_id,$sub_product_id,$expire_time, $approvalExtra, $stage_cer, $credit){
		return $this->approve_1($user_id,$product_id,$sub_product_id,$expire_time, $approvalExtra, $stage_cer, $credit);
	}

	private function approve_3($user_id,$product_id,$sub_product_id,$expire_time, $approvalExtra, $stage_cer, $credit){
        $total = 0;
        $time = time();
        $param = [
            'user_id' => $user_id,
            'product_id' => $product_id,
            'sub_product_id' => $sub_product_id,
            'amount' => 0,
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
                //系所加分
                $total += isset($data['job_license']) ? $data['job_license'] * 50 : 0;
                $total += isset($data['job_pro_level']) ? $data['job_pro_level'] * 100 : 0;
            }
        }

        //畢業學校
        if (isset($data['diploma_name']) && !empty($data['diploma_name'])) {
            $total += intval($this->get_school_point($data['diploma_name'], $data['diploma_system'], '', $data['diploma_department'])) * 0.6;
        }

        if (isset($data['job_type'])) {
            $total += $data['job_type'] ? 50 : 100;
        }

        if (isset($data['job_salary'])) {
            $total += $this->get_job_salary_point(intval($data['job_salary']));
        }

        if (isset($data['job_license']) && $data['job_license']) {
            $total += 100;
        }

        if (isset($data['job_employee'])) {
            $total += $this->get_job_employee_point(intval($data['job_employee']));
        }

        if (isset($data['job_position'])) {
            $total += $this->get_job_position_point(intval($data['job_position']));
        }

        if (isset($data['job_seniority'])) {
            $total += $this->get_job_seniority_point(intval($data['job_seniority']), intval($data['job_salary']));
        }

        if (isset($data['job_company_seniority'])) {
            $total += $this->get_job_seniority_point(intval($data['job_company_seniority']), intval($data['job_salary']));
        }

        if (isset($data['job_industry'])) {
            $total += $this->get_job_industry_point($data['job_industry']);
        }

        //聯徵
        if (isset($data['investigation_status']) && !empty($data['investigation_status'])) {
            if (isset($data['investigation_times'])) {
                $total += $this->get_investigation_times_point(intval($data['investigation_times']));
            }

            if (isset($data['investigation_credit_rate'])) {
                $total += $this->get_investigation_rate_point(intval($data['investigation_credit_rate']));
            }

            if (isset($data['investigation_months'])) {
                $total += $this->get_investigation_months_point(intval($data['investigation_months']));
            }
        }

        if ($approvalExtra && $approvalExtra->getExtraPoints()) {
            $total += $approvalExtra->getExtraPoints();
        }

        $total = $user_info->sex == 'M' ? round($total * 0.9) : $total;
        $param['points'] = intval($total);

        $stage_cer ? $total = 100 : '';
        $param['level'] = $this->get_credit_level($total, $product_id, $stage_cer);
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
        }else{
        	// 低於最小放款額度時則不予授信
            $param['amount'] = $param['amount'] < intval($this->product_list[$product_id]['loan_range_s']) ? 0 : $param['amount'];
        }
        $param['expire_time'] = $expire_time;

        // 月薪低於特定值，不能超過特定倍數的額度
        if (!$stage_cer && intval($data['job_salary']) <= $this->product_list[$product_id]['condition_rate']['salary_below']) {
			$job_salary = intval($data['job_salary']) * $this->product_list[$product_id]['condition_rate']['rate'];
            $param['amount'] = intval(min($param['amount'], $job_salary));
        }
		// 額度不能大於最大允許額度
		$param['amount'] = min($this->product_list[$product_id]['loan_range_e'], $param['amount']);

        if ($approvalExtra && $approvalExtra->shouldSkipInsertion()) {
			return $param;
		}

        if($sub_product_id == STAGE_CER_TARGET && $time < $credit['expire_time']){
            $rs 		= $this->CI->credit_model->update($credit['id'],$param);
            return $rs;
        }
        $rs 		= $this->CI->credit_model->insert($param);
		return $rs;
	}

	private function approve_4($user_id,$product_id,$sub_product_id,$expire_time,$approvalExtra, $stage_cer, $credit){
		return $this->approve_3($user_id,$product_id,$sub_product_id,$expire_time,$approvalExtra, $stage_cer, $credit);
	}

    private function approve_1000($user_id,$product_id,$sub_product_id,$expire_time, $approvalExtra, $stage_cer, $credit){

        $info 		= $this->CI->user_meta_model->get_many_by(['user_id'=>$user_id]);
        $user_info 	= $this->CI->user_model->get($user_id);
        $data 		= [];
        $total 		= 0;
        $param		= [
            'product_id'    => $product_id,
            'sub_product_id'=> $sub_product_id,
            'user_id'       => $user_id,
            'amount'        => 0
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
        $param['level'] 	= $this->get_credit_level($total,$product_id);
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

	public function get_school_point($school_name='',$school_system=0,$school_major='',$school_department = false ){
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
                $schoolPoing = $school_info['points'];
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

				if(!empty($school_major)){
					$schoolMajorPoint = isset($school_list['school_major_point'][$school_major])?$school_list['school_major_point'][$school_major]:100;
					$point += $schoolMajorPoint;
					$this->scoreHistory[] = '大學學門分類:'.$school_major.' = '.$schoolMajorPoint;
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
			case 1:
				return 100;
				break;
			case 2:
				return 150;
				break;
			case 3:
				if($job_salary < 40000){
					return 100;
				}else{
					return 200;
				}
				break;
			case 3:
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
				'sub_product_id'	=> $sub_product_id,
				'status'			=> 1,
				'expire_time >='	=> time(),
			);
			$rs 	= $this->CI->credit_model->order_by('created_at','desc')->get_by($param);
			if($rs){
                $data = [
				    'id'         => intval($rs->id),
					'level'		 => intval($rs->level),
					'points'	 => intval($rs->points),
					'amount'	 => intval($rs->amount),
					'created_at' => intval($rs->created_at),
					'expire_time'=> intval($rs->expire_time),
				];
                if($target){
                    $data['rate'] = $this->get_rate($rs->level,$target->instalment,$product_id,$sub_product_id,$target);
                }

				$info = $this->CI->user_meta_model->get_by(['user_id' => $user_id, 'meta_key' => 'school_name']);
				if(isset($info->meta_value)) {
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

	public function  get_credit_level($points=0,$product_id=0, $stage_cer = false){
		if((intval($points)>0 || $stage_cer) && $product_id ){
			if(isset($this->credit['credit_level_'.$product_id])){
				foreach($this->credit['credit_level_'.$product_id] as $level => $value){
					if($points >= $value['start'] && $points <= $value['end']){
						return $level;
						break;
					}
				}
			}

		}
		return false;
	}

	public function get_rate($level,$instalment,$product_id,$sub_product_id=0,$target=[]){
		$credit = $this->CI->config->item('credit');
		if(isset($this->credit['credit_level_'.$product_id][$level])){
			if(isset($this->credit['credit_level_'.$product_id][$level]['rate'][$instalment])){
                $rate = $this->credit['credit_level_'.$product_id][$level]['rate'][$instalment];
                //副產品減免
                if($sub_product_id){
                    $info        = $this->CI->user_meta_model->get_many_by(['user_id'=>$target->user_id]);
                    foreach($info as $key => $value){
                        $data[$value->meta_key] = $value->meta_value;
                    }
                    $sub_product = $this->get_sub_product_data($sub_product_id);
                    //techie
                    if ($sub_product && $sub_product_id == 1){
                        $rate -= in_array($data['school_department'],$sub_product->majorList)?1:0;
                        if ($product_id == 1){
                            $rate -= isset($data['student_license_level'])?$data['student_license_level']*0.5:0;
                            $rate -= isset($data['student_game_work_level'])?$data['student_game_work_level']*0.5:0;
                        }elseif ($product_id == 3){
                            $rate -= isset($data['job_license'])?$data['job_license']*0.5:0;
                            //工作認證減免%
                            $rate -= isset($data['job_title'])?$sub_product->titleList->{$data['job_title']}->level:0;
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
}

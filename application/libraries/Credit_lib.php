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
    }
	
	//信用評比
	public function approve_credit($user_id,$product_id,$sub_product_id=0){
		if($user_id && $product_id){

            //信用低落
            $low = $this->CI->credit_model->order_by('level','desc')->get_by([
                'user_id'		=> $user_id,
                'status'		=> 1,
                'points <'		=> 0,
            ]);
            $expire_time     = $max_expire_time = strtotime("+6 months", time());

            if($low){
                return $this->CI->credit_model->insert([
                    'product_id' 	=> $product_id,
                    'sub_product_id'=> $sub_product_id,
                    'user_id'		=> $user_id,
                    'points'		=> $low->points,
                    'level'			=> $low->level,
                    'amount'		=> $low->amount,
                    'expire_time'   => $expire_time,
                ]);
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
				$rs = $this->$method($user_id,$product_id,$sub_product_id,$expire_time);
				return $rs;
			}
		}
		return false;
	}
	
	private function approve_1($user_id,$product_id,$sub_product_id,$expire_time){

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

        if($sub_product_id){
            $sub_product = $this->get_sub_product_data($sub_product_id);
            //techie
            if($sub_product_id == 1){
                //系所加分
                $total += in_array($data['school_department'],$sub_product->majorList)?200:0;
                $total += isset($data['student_game_work_level'])?$data['student_game_work_level']*100:0;
                $total += isset($data['student_license_level'])?$data['student_license_level']*100:0;
                $total += isset($data['student_pro_level'])?$data['student_pro_level']*100:0;
            }
        }

		//學校
		if(isset($data['school_name']) && !empty($data['school_name'])){
			$total += $this->get_school_point($data['school_name'],$data['school_system'],$data['school_major']);
		}

		//財務證明
		if(isset($data['financial_status']) && !empty($data['financial_status'])){
			$total += 50;
			if(!empty($data['financial_creditcard']) || !empty($data['financial_passbook'])){
				$total += 50;
				if(!empty($data['financial_creditcard'])){
					$total += 50;
				}
				if(!empty($data['financial_passbook'])){
					$total += 50;
				}
			}
		}
		
		if(isset($data['social_status']) && !empty($data['social_status'])){
			$total += 50;
		}
		
		//SIP
		//if(!empty($data['student_sip_account']) && !empty($data['student_sip_password'])){
			//$total += 150;
		//}
		//成績單
		if(isset($data['transcript_front']) && !empty($data['transcript_front'])){
			$total += 100;
		}
		//緊急聯絡人
		if(isset($data['emergency_relationship']) && $data['emergency_relationship']=='監護人'){
			$total = $total - 400;//mantis 0000003
		}
		
		$total = $user_info->sex=='M'?round($total*0.9):$total;
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

        $rs 		= $this->CI->credit_model->insert($param);
		return $rs;
	}
	
	private function approve_2($user_id,$product_id,$sub_product_id,$expire_time){
		return $this->approve_1($user_id,$product_id,$sub_product_id,$expire_time);
	}

	private function approve_3($user_id,$product_id,$sub_product_id,$expire_time){

		$info 		= $this->CI->user_meta_model->get_many_by(['user_id'=>$user_id]);
		$user_info 	= $this->CI->user_model->get($user_id);
		$data 		= [];
		$total 		= 0;
		$param		= ['product_id'=>$product_id,'user_id'=> $user_id,'amount'=>0];
		foreach($info as $key => $value){
			$data[$value->meta_key] = $value->meta_value;
		}

        if($sub_product_id){
            //techie
            if($sub_product_id == 1){
                //系所加分
                $total += isset($data['job_license'])?$data['job_license']*100:0;
                $total += isset($data['job_pro_level'])?$data['job_pro_level']*100:0;
            }
        }

		//畢業學校
		if(isset($data['diploma_name']) && !empty($data['diploma_name'])){
			$total += intval($this->get_school_point($data['diploma_name'],$data['diploma_system'],''))*0.6;
		}

		if(isset($data['job_type'])){
			$total += $data['job_type']?50:100;
		}

		if(isset($data['job_salary'])){
			$total += $this->get_job_salary_point(intval($data['job_salary']));
		}

		if(isset($data['job_license']) && $data['job_license']){
			$total += 100;
		}

		if(isset($data['job_employee'])){
			$total += $this->get_job_employee_point(intval($data['job_employee']));
		}

		if(isset($data['job_position'])){
			$total += $this->get_job_position_point(intval($data['job_position']));
		}

		if(isset($data['job_seniority'])){
			$total += $this->get_job_seniority_point(intval($data['job_seniority']),intval($data['job_salary']));
		}

		if(isset($data['job_company_seniority'])){
			$total += $this->get_job_seniority_point(intval($data['job_company_seniority']),intval($data['job_salary']));
		}

		if(isset($data['job_industry'])){
			$total += $this->get_job_industry_point($data['job_industry']);
		}
		
		//聯徵
		if(isset($data['investigation_status']) && !empty($data['investigation_status'])){
			if(isset($data['investigation_times'])){
				$total += $this->get_investigation_times_point(intval($data['investigation_times']));
			}

			if(isset($data['investigation_credit_rate'])){
				$total += $this->get_investigation_rate_point(intval($data['investigation_credit_rate']));
			}

			if(isset($data['investigation_months'])){
				$total += $this->get_investigation_months_point(intval($data['investigation_months']));
			}
		}

		$total = $user_info->sex=='M'?round($total*0.9):$total;
		$param['points'] 	= intval($total);
		$param['level'] 	= $this->get_credit_level($total,$product_id);
		if(isset($this->credit['credit_amount_'.$product_id])){
			foreach($this->credit['credit_amount_'.$product_id] as $key => $value){
				if($param['points']>=$value['start'] && $param['points']<=$value['end']){
					$param['amount'] = intval($data['job_salary'])*$value['rate'];
					break;
				}
			}
		}
		$param['amount']      = $param['amount']>200000?200000:$param['amount'];
		$param['amount']      = $param['amount']<20000?0:$param['amount'];
        $param['expire_time'] = $expire_time;
		if(intval($data['job_salary'])<=35000){
			$job_salary = intval($data['job_salary'])*2;
			$param['amount'] = $param['amount']>$job_salary?$job_salary:$param['amount'];
		}

		$rs 		= $this->CI->credit_model->insert($param);
		return $rs;
	}
	
	private function approve_4($user_id,$product_id,$sub_product_id,$expire_time){
		return $this->approve_3($user_id,$product_id,$sub_product_id,$expire_time);
	}
	
	public function get_school_point($school_name='',$school_system=0,$school_major=''){
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

			if(!empty($school_info)){
				$point = $school_info['points'];
				if($school_system==1){
					$point += $school_info['national']==1?300:200;
				}else if($school_system==2){
					$point += 400;
				}
			}

			if(!empty($school_major)){
				$point += isset($school_list['school_major_point'][$school_major])?$school_list['school_major_point'][$school_major]:100;
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
	public function get_credit($user_id,$product_id,$sub_product_id=0){
		if($user_id && $product_id){
			$param = array(
				'user_id'			=> $user_id,
				'product_id'		=> $product_id,
				'status'			=> 1,
				'expire_time >='	=> time(),
			);
			$data 	= array();
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
				return $data;
			}
		}
		return false;
	}
	
	public function get_credit_level($points=0,$product_id=0){
		if(intval($points)>0 && $product_id){
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
	
	public function get_rate($level,$instalment,$product_id){
		$credit = $this->CI->config->item('credit');
		if(isset($this->credit['credit_level_'.$product_id][$level])){
			if(isset($this->credit['credit_level_'.$product_id][$level]['rate'][$instalment])){
				return $this->credit['credit_level_'.$product_id][$level]['rate'][$instalment];
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
        $sub_product_mapping = $this->CI->config->item('sub_product_mapping')[$sub_product_id];
        $this->CI->config->load('sub_product',TRUE);
        $get_list = $this->CI->config->item('sub_product');
        return $get_list[$sub_product_mapping];
    }
}

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Judicialperson_lib{

	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('user/judicial_person_model');
		$this->CI->load->model('user/judicial_agent_model');
		$this->CI->load->model('user/cooperation_model');
        $this->CI->load->model('user/user_bankaccount_model');
        $this->CI->load->model('user/user_certification_model');
        $this->CI->load->model('user/virtual_account_model');
    }
	
	//審核成功
	function apply_success($person_id,$admin_id=0){
		if($person_id){
			$judicial_person = $this->CI->judicial_person_model->get($person_id);
			if( $judicial_person && $judicial_person->status == 0){
				$exist = $this->CI->user_model->get_by([
					'phone' => $judicial_person->tax_id
				]);
				if(!$exist){
					$judicial_person_data = explode(',',$judicial_person->sign_video);
                    $transaction_password = $judicial_person_data[0];
                    $bank_code            = $judicial_person_data[1];
                    $branch_code          = $judicial_person_data[2];
                    $bank_account         = $judicial_person_data[3];
                    $email                = $judicial_person_data[4];
					$bankbook_images      = urldecode($judicial_person_data[5]);
					unset( $judicial_person_data[0], $judicial_person_data[1], $judicial_person_data[2], $judicial_person_data[3], $judicial_person_data[4], $judicial_person_data[5]);
					if(empty($judicial_person_data))
					{
						echo '請先上傳法人對保影片';die();
					}
					$media=$judicial_person_data;
					$media=implode(",",$media);
					$user_param = [
						'name'				   => $judicial_person->company,
						'nickname'			   => $judicial_person->company,
						'password'			   => md5($judicial_person->user_id),
						'phone'				   => $judicial_person->tax_id,
						'email'                => $email,
						'id_number'			   => $judicial_person->tax_id,
						'company_status'	   => 1,
						'transaction_password' => $transaction_password,
					];
					$user_id = $this->CI->user_model->insert($user_param);
					if($user_id){
						$agent_param = [
							'company_user_id'	=> $user_id,
							'incharge'			=> 1,
							'user_id'			=> $judicial_person->user_id,
						];
						$this->CI->judicial_agent_model->insert($agent_param);
                        $param		= [
                            'user_id'			=> $user_id,
                            'certification_id'	=> 3,
                            'investor'			=> 1,
                            'expire_time'		=> strtotime('+20 years'),
                            'content'			=> $bankbook_images,
                            'status'            => 1,
                        ];
                        $insert = $this->CI->user_certification_model->insert($param);
						//建立金融帳號
                        $bankaccount_info = [
                            'user_id'               => $user_id,
                            'investor'              => 1,
                            'user_certification_id' => $insert,
                            'bank_code'             => $bank_code,
                            'branch_code'           => $branch_code,
                            'bank_account'          => $bank_account,
                            'front_image'	        => $bankbook_images,
                            'verify'                => 1,
                        ];
                        $this->CI->user_bankaccount_model->insert($bankaccount_info);

                        $virtual_data 	= [];
                        $virtual_data[] = [
                            'investor'			=> 1,
                            'user_id'			=> $user_id,
                            'virtual_account'	=> CATHAY_VIRTUAL_CODE.INVESTOR_VIRTUAL_CODE.'0'.substr($judicial_person->tax_id,0,8),
                        ];

                        $virtual_data[] = [
                            'investor'			=> 0,
                            'user_id'			=> $user_id,
                            'virtual_account'	=> CATHAY_VIRTUAL_CODE.BORROWER_VIRTUAL_CODE.'0'.substr($judicial_person->tax_id,0,8),
                        ];
						$this->CI->virtual_account_model->insert_many($virtual_data);
						$this->CI->judicial_person_model->update($person_id, [
							'status' 			=> 1,
							'company_user_id'	=> $user_id,
							'sign_video'        => $media,
						]);
						return true;
					}
				}
			}
		}
		return false;
	}

	//審核失敗
	function apply_failed($person_id,$admin_id=0){
		if($person_id){
			$judicial_person = $this->CI->judicial_person_model->get($person_id);
			if( $judicial_person && $judicial_person->status == 0){
				$param = array(
					'status'	    => 2,
					'cooperation'	=> 0,
				);
				return $this->CI->judicial_person_model->update($person_id,$param);
			}
		}
		return false;
	}
	
	//經銷商審核成功
	function cooperation_success($person_id,$admin_id=0){
		if($person_id){
			$judicial_person = $this->CI->judicial_person_model->get($person_id);
			if( $judicial_person && $judicial_person->status == 1 && $judicial_person->cooperation == 2){
				$this->CI->judicial_person_model->update($person_id,['cooperation'=>1]);
				$param	= array(
					'company_user_id'	=> $judicial_person->company_user_id,
                    'cooperation_id'	=> 'CO'.$judicial_person->tax_id,
                    'cooperation_key'	=> SHA1(COOPER_KEY.$judicial_person->tax_id.time()),
					//'server_ip'		=> $judicial_person->cooperation_server_ip,
					'type'		        => $judicial_person->selling_type,
                    'status'			=> 1,
				);
				$rs = $this->CI->cooperation_model->insert($param);
				return $rs;
			}
		}
		return false;
	}

	//經銷商審核失敗
	function cooperation_failed($person_id,$admin_id=0){
		if($person_id){
			$judicial_person = $this->CI->judicial_person_model->get($person_id);
			if( $judicial_person && $judicial_person->status == 1 && $judicial_person->cooperation == 2){
				$param = array(
					'cooperation'	=> 0,
				);
				return $this->CI->judicial_person_model->update($person_id,$param);
			}
		}
		return false;
	}
}

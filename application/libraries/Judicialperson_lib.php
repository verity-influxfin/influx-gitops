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
                    $judicial_person_data = json_decode($judicial_person->sign_video,true);
                    $transaction_password = $judicial_person_data['transaction_password'];
                    $bank_code            = $judicial_person_data['bank_code'];
                    $branch_code          = $judicial_person_data['branch_code'];
                    $bank_account         = $judicial_person_data['bank_account'];
                    $email                = $judicial_person_data['email'];
					$bankbook_images      = urldecode($judicial_person_data['bankbook_images']);
                    $businesstax = false;
					if(empty($judicial_person_data['judi_admin_video'])|| empty($judicial_person_data['judi_user_video']))
					{
						echo '請先上傳法人或請負責人上傳對保影片';die();
					}

					if(isset($judicial_person_data['businesstax'])){
                        $businesstax = $judicial_person_data['businesstax'];
                        unset($judicial_person_data['businesstax']);
                    }

                    unset(
                        $judicial_person_data['transaction_password'],
                        $judicial_person_data['bank_code'],
                        $judicial_person_data['branch_code'],
                        $judicial_person_data['bank_account'],
                        $judicial_person_data['email'],
                        $judicial_person_data['bankbook_images']
                    );
					$media = json_encode($judicial_person_data);

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
                    if($user_id) {
                        $agent_param = [
                            'company_user_id' => $user_id,
                            'incharge' => 1,
                            'user_id' => $judicial_person->user_id,
                        ];
                        $virtual_data = [];
                        $virtual_data[] = [
                            'investor' => 1,
                            'user_id' => $user_id,
                            'virtual_account' => CATHAY_VIRTUAL_CODE . INVESTOR_VIRTUAL_CODE . '0' . substr($judicial_person->tax_id, 0, 8),
                        ];

                        $virtual_data[] = [
                            'investor' => 0,
                            'user_id' => $user_id,
                            'virtual_account' => CATHAY_VIRTUAL_CODE . BORROWER_VIRTUAL_CODE . '0' . substr($judicial_person->tax_id, 0, 8),
                        ];

                        if (in_array($judicial_person->selling_type, $this->CI->config->item('use_taishin_selling_type'))) {
                            $virtual_data[] = [
                                'investor' => 0,
                                'user_id' => $user_id,
                                'virtual_account' => TAISHIN_VIRTUAL_CODE . '0' . substr($judicial_person->tax_id, 0, 8),
                            ];
                        }
                    }
                    $v_rs = $this->CI->virtual_account_model->insert_many($virtual_data);
                    if($v_rs){
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

                        $params = [];
                        if($businesstax){
                            $params[] = [
                                'user_id' => $user_id,
                                'certification_id' => 1000,
                                'investor' => 1,
                                'expire_time' => strtotime('+2 months'),
                                'content' => $businesstax,
                                'status' => 0,
                            ];
                        }

                        $enterprise_registration['governmentauthorities_image'] = json_decode($judicial_person->enterprise_registration ,true)['enterprise_registration_image'];
                        $params[] = [
                            'user_id' => $user_id,
                            'certification_id' => 1007,
                            'investor' => 1,
                            'expire_time' => strtotime('+1 years'),
                            'content' => json_encode($enterprise_registration),
                            'status' => 0,
                        ];
                        $this->CI->user_certification_model->insert_many($params);

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

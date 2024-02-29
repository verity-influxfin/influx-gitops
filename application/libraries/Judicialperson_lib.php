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
                $company_user_id = $judicial_person->company_user_id;
//                     if($company_user_id) {
//                        $agent_param = [
//                            'company_user_id' => $company_user_id,
//                            'incharge' => 1,
//                            'user_id' => $judicial_person->user_id,
//                        ];
//                        $virtual_data = [];
//                        $virtual_data[] = [
//                            'investor' => 1,
//                            'user_id' => $company_user_id,
//                            'virtual_account' => CATHAY_VIRTUAL_CODE . INVESTOR_VIRTUAL_CODE . '0' . substr($judicial_person->tax_id, 0, 8),
//                        ];
//
//                        $virtual_data[] = [
//                            'investor' => 0,
//                            'user_id' => $company_user_id,
//                            'virtual_account' => CATHAY_VIRTUAL_CODE . BORROWER_VIRTUAL_CODE . '0' . substr($judicial_person->tax_id, 0, 8),
//                        ];
//
//                        if (in_array($judicial_person->selling_type, $this->CI->config->item('use_taishin_selling_type'))) {
//                            $virtual_data[] = [
//                                'investor' => 0,
//                                'user_id' => $company_user_id,
//                                'virtual_account' => TAISHIN_VIRTUAL_CODE . '0' . substr($judicial_person->tax_id, 0, 8),
//                            ];
//                        }
//                    }
//                    $v_rs = $this->CI->virtual_account_model->insert_many($virtual_data);
//                    if($v_rs){
//                        $this->CI->judicial_agent_model->insert($agent_param);
                $this->CI->judicial_person_model->update($person_id, [
                    'status' 			=> 1,
                    'company_user_id'	=> $company_user_id,
                ]);
                $certification_info = $this->CI->user_certification_model->get_by(['user_id' => $company_user_id, 'certification_id' => CERTIFICATION_JUDICIALGUARANTEE, 'investor' => [0, 1], 'status' => [3]]);
                if($certification_info){
                    $this->CI->user_certification_model->update($certification_info->id,array(
                        'status' => 1
                    ));
                }
                return true;

//                    }
			}
		}
		return false;
	}

	//審核失敗
	function apply_failed($person_id,$admin_id=0){
		if($person_id){
			$judicial_person = $this->CI->judicial_person_model->get($person_id);
 			if( $judicial_person && in_array($judicial_person->status, [0])){
                $certification_info = $this->CI->user_certification_model->get_by(['user_id' => $judicial_person->company_user_id, 'certification_id' => CERTIFICATION_JUDICIALGUARANTEE, 'investor' => [0, 1], 'status' => [0, 1, 3]]);
                if($certification_info) {
                    $this->CI->user_certification_model->update($certification_info->id, array(
                        'status' => 2
                    ));
                }
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

	// 法人人臉辨識排程
	function script_check_judicial_person_face($info){
        if (is_array($info))
        {
            $info = json_decode(json_encode($info));
        }
		$this->CI->load->model('user/judicial_person_model');
        $judicial_person_info = $this->CI->judicial_person_model->get_by([
            'company_user_id' => $info->user_id ?? $info['user_id'] ?? 0,
            'status' => 0,
        ]);

        if (empty($judicial_person_info))
        {
            return [];
        }

        $image_info = isset($judicial_person_info->sign_video) && json_decode($judicial_person_info->sign_video,true) ? json_decode($judicial_person_info->sign_video,true) : [];
        $governmentauthorities_image = isset($image_info['image_url']) ? $image_info['image_url'] : '';
        $person_image = '';
        $user_id = isset($judicial_person_info->user_id) ? $judicial_person_info->user_id : '';

        // 找持證自拍
        if($user_id){
            $certification_info = $this->CI->user_certification_model->get_by(['user_id' => $user_id,'certification_id' => 1,'investor' => 0, 'status' => 1]);
            if($certification_info){
                $content = isset($certification_info->content) ? json_decode($certification_info->content,true) : [];
                $person_image = isset($content['person_image']) ? $content['person_image'] : '';
            }
        }
        // 人臉辨識
        if($person_image && $governmentauthorities_image){
             $image_info['person_image_url']= $person_image;

            // Todo: 2023-10-11 azure 暫時改回 face++
            // 微軟
            // $this->CI->load->library('azure_lib');
            // $person_image_info = $this->CI->azure_lib->detect($person_image);
            // $governmentauthorities_image_info = $this->CI->azure_lib->detect($governmentauthorities_image);
            // if(!empty($person_image_info) && !empty($governmentauthorities_image_info)){
            //     $image_info['azure']['person'] = ! empty($person_image_info) ? $person_image_info : [];
            //     $image_info['azure']['governmentauthorities'] = ! empty($person_image_info) ? $person_image_info : [];
            //     foreach ($person_image_info as $value) {
            //         $face_id = isset($value['faceId']) ? $value['faceId'] : '';
            //         if($face_id){
            //             foreach ($governmentauthorities_image_info as $value1) {
            //                 $face_id1 = isset($value1['faceId']) ? $value1['faceId'] : '';
            //                 if($face_id1){
            //                     $image_info['azure']['compare'][] = $this->CI->azure_lib->verify($face_id,$face_id1);
            //                 }
            //             }
            //         }
            //     }
            // }else{
            //     $image_info['azure']['person'] = ! empty($person_image_info) ? $person_image_info : [];
            //     $image_info['azure']['governmentauthorities'] = ! empty($person_image_info) ? $person_image_info : [];
            //     $image_info['azure']['compare'] = [];
            // }

            // 曠世
            $this->CI->load->library('faceplusplus_lib');
            $person_image_info = $this->CI->faceplusplus_lib->get_face_token($person_image);
            $governmentauthorities_image_info = $this->CI->faceplusplus_lib->get_face_token($governmentauthorities_image);
            if(!empty($person_image_info) && !empty($governmentauthorities_image_info)){
                $image_info['faceplusplus']['person'] = $person_image_info;
                $image_info['faceplusplus']['governmentauthorities'] = $person_image_info;
                foreach ($person_image_info as $value) {
                    $face_id = isset($value[0]) ? $value[0] : '';
                    if($face_id){
                        foreach ($governmentauthorities_image_info as $value1) {
                            $face_id1 = isset($value1[0]) ? $value1[0] : '';
                            if($face_id1){
                                $image_info['faceplusplus']['compare'][] = $this->CI->faceplusplus_lib->url_compare($face_id,$face_id1);
                            }
                        }
                    }
                }
            }else{
                $image_info['faceplusplus']['person'] = ! empty($person_image_info) ? $person_image_info : [];
                $image_info['faceplusplus']['governmentauthorities'] = ! empty($person_image_info) ? $person_image_info : [];
                $image_info['faceplusplus']['compare'] = [];
            }

            // papago
            $this->CI->load->library('papago_lib');
            $person_image_info = $this->CI->papago_lib->detect($person_image);
            $governmentauthorities_image_info = $this->CI->papago_lib->detect($governmentauthorities_image);
            if(!empty($person_image_info) && !empty($governmentauthorities_image_info)){
                $image_info['papago']['person'] = $person_image_info;
                $image_info['papago']['governmentauthorities'] = $person_image_info;
                foreach ($person_image_info['faces'] as $value) {
                    $face_id = isset($value['face_token']) ? $value['face_token'] : '';
                    if($face_id){
                        foreach ($governmentauthorities_image_info['faces'] as $value1) {
                            $face_id1 = isset($value1['face_token']) ? $value1['face_token'] : '';
                            if($face_id1){
                                $image_info['papago']['compare'][] = $this->CI->papago_lib->compare([$face_id,$face_id1]);
                            }
                        }
                    }
                }
            }else{
                $image_info['papago']['person'] = ! empty($person_image_info) ? $person_image_info : [];
                $image_info['papago']['governmentauthorities'] = ! empty($person_image_info) ? $person_image_info : [];
                $image_info['papago']['compare'] = [];
            }

            // to do : 自動過件邏輯
            if(true){
                //閥值通過的過件邏輯
                $status = 0;
                $this->CI->judicial_agent_model->insert([
                    'incharge'			=> 1,
                    'company_user_id'	=> $judicial_person_info->company_user_id,
                    'user_id'			=> $judicial_person_info->user_id,
                ]);
            }
            else{
                //閥值不足處置
                $status = 0;//停留在待人工審核
            }
        }else{
            $status = 3;
        }
        $this->CI->judicial_person_model->update_by(['id' => $judicial_person_info->id],['sign_video' => json_encode($image_info), 'status' => $status]);
        return [
            'status' => $status,
            'judicialPersonId' => $judicial_person_info->id,
            'compareResult' => $image_info,
        ];
	}
	public function getNaturalPerson($userId){
        $this->CI->load->model('user/user_model');
        $info = $this->CI->user_model->get($userId);
        return $this->CI->user_model->get_by([
            "phone" => $info->phone,
            "company_status" => 0,
        ]);
    }

    public function get_company_email_list($company_user_id): array
    {
        $this->CI->load->model('user/judicial_person_model');
        $judical_person_info = $this->CI->judicial_person_model->get_by(['company_user_id' => $company_user_id]);
        $email_list = [];

        if (isset($judical_person_info))
        {
            $company_user = $this->CI->user_model->get($judical_person_info->company_user_id);
            if (isset($company_user) && isset($company_user->email))
            {
                $email_list[$company_user->id] = $company_user->email;
            }
            $user = $this->CI->user_model->get($judical_person_info->user_id);
            if (isset($user) && isset($user->email))
            {
                $email_list[$user->id] = $user->email;
            }
        }
        return $email_list;
    }

    /**
     * 法人對保通過
     * @param  string  $company_id 法人使用者編號
     * @return boolean
     */
    public function succeed_in_company_guaranty($company_id = '')
    {
        if ( ! empty($company_id) )
        {
            $this->CI->load->model('user/User_model');
            $company_user_info = $this->CI->user_model->get($company_id);
            if ( ! empty($company_user_info)
                && $company_user_info->company_status == USER_IS_COMPANY
                && ! empty($company_user_info->phone) )
            {
                $user_info = $this->CI->user_model->get_by([
                    'phone' => $company_user_info->phone,
                    'company_status' => USER_NOT_COMPANY,
                ]);
                if ( ! empty($user_info) )
                {
                    $update_by_tax_id = FALSE;
                    if ( ! empty($company_user_info->id_number) )
                    {
                        $info_by_tax_id = $this->CI->judicial_person_model->get_by([
                            'tax_id' => $company_user_info->id_number,
                            'status' => JUDICIAL_PERSON_STATUS_SUCCESS
                        ]);
                        // 有已經開通的法人就覆蓋
                        if ( ! empty($info_by_tax_id) )
                        {
                            $this->CI->judicial_person_model->update_by([
                                'id' => $info_by_tax_id->id
                                ],['status' => JUDICIAL_PERSON_STATUS_SUCCESS,
                                    'company_user_id' => $company_user_info->id,
                                    'user_id' => $user_info->id
                            ]);
                            $update_by_tax_id = TRUE;
                        }
                    }
                    if ( $update_by_tax_id === FALSE )
                    {
                        $info_by_company_id = $this->CI->judicial_person_model->get_by([
                            'company_user_id' => $company_user_info->id,
                            'user_id' => $user_info->id
                        ]);
                        if ( !empty($info_by_company_id) )
                        {
                            $this->CI->judicial_person_model->update_by([
                                'id' => $info_by_company_id->id
                                ],['status' => JUDICIAL_PERSON_STATUS_SUCCESS,
                                    'tax_id' => $company_user_info->id_number
                            ]);
                        }
                    }
                    return TRUE;
                }
            }
        }
        return FALSE;
    }
}

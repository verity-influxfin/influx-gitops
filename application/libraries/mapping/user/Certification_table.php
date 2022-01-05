<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Certification_table
{
    public $ocr_url = [
        '1007' => [
			'type' => 'amendment_of_register',
            'image_name' => ['governmentauthorities_image'],
		],
        '1017' => [
            'type' => 'insurance_table_company',
            'image_name' => ['employeeinsurancelist_image'],
        ],
        '1002' => [
            'type' => 'income_statement',
            'image_name' => ['income_statement_image'],
        ],
        '1003' => [
            'type' => 'credit_investigation',
            'image_name' => ['legal_person_mq_image','postal_image'],
        ],
        '12' => [
            'type' => 'credit_investigation',
            'image_name' => ['person_mq_image'],
        ],
    ];
	public function __construct()
	{
		$this->CI = &get_instance();
	}

	/**
	 * [getOcrUrl 給 ocr人工編輯連結]
	 * @param  string $user_certification_id [認證ID]
	 * @param  string $certification_id      [認證類型ID]
	 * @param  array  $certification_content [認證資料]
	 * @return array  $data                  [ocr人工編輯連結]
	 */
	public function getOcrUrl($user_certification_id='',$certification_id='',$certification_content=[]){
		$data = [];
		$this->CI->load->model('log/log_image_model');
		$ocr_type = $this->ocr_url[$certification_id]['type'];
		$img = $this->getUserPostFilesKey($certification_id);

		$ocr_img_id = [];
		if(isset($certification_content['group_id'])){
			$ocr_img_id[] = $certification_content['group_id'];
			$data[] = base_url("admin/ocr/report?id={$ocr_img_id[0]}&type={$ocr_type}&certification={$user_certification_id}");
		}else{
            if(!empty($img)){
                foreach($img as $name){
                    if(isset($certification_content[$name])){
                        $img_url_list = [];
                        if(is_array($certification_content[$name])){
                            foreach($certification_content[$name] as $image_url){
                                $img_url_list[] = $image_url;
                            }
                        }
                        if(is_string($certification_content[$name])){
                            $img_url_list[] = $certification_content[$name];
                        }
                        if(!empty($img_url_list)){
                            $ocr_img_info = $this->CI->log_image_model->getIDByUrl($img_url_list);
                            $ocr_img_id = [];
                            if($ocr_img_info){
                                foreach($ocr_img_info as $v){
                                    if(! in_array($v->id,$ocr_img_id)){
                                        $ocr_img_id[] = $v->id;
                                        $data[] = base_url("admin/ocr/report?id={$v->id}&type={$ocr_type}&certification={$user_certification_id}");
                                    }
                                }
                            }
                        }
        			}
                }
            }
		}
		return $data;
	}

	/**
	 * [getUserPostFilesKey 取得使用者上傳檔案連結的 Key值]
	 * @param  string $certification_id [認證類型ID]
	 * @return string $key_name         [連結的 Key值]
	 */
	public function getUserPostFilesKey($certification_id=''){
		$key_name = [];
		if(isset($this->ocr_url[$certification_id])){
            foreach($this->ocr_url[$certification_id]['image_name'] as $image_name){
                $key_name[] = $image_name;
            }

		}
		return $key_name;
	}
}

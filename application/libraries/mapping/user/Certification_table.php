<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Certification_table
{
	public $certification_mapping =[
		// '11' => [
		// 	'type' => 'profile',
		// 	'file_location' => 'profile',
        //     'image_location' => ['profile'],
		// 	'total_table' => [
		// 		'action_user' => '編輯人',
		// 		'send_time' => '編輯時間',
		// 		'status' => '編輯狀態',
		// 		'PrCurAddrZip' => '負責人現居地址-郵遞區號',
		// 		'PrCurAddrZipName' => '負責人現居地址-名稱',
		// 		'PrCurlAddress' => '負責人現居地址-地址',
		// 		'PrTelAreaCode' => '負責人聯絡電話-區碼',
		// 		'PrTelNo' => '負責人聯絡電話-電話號碼',
		// 		'PrTelExt' => '負責人聯絡電話-分機碼',
		// 		'PrMobileNo' => '負責人連絡行動電話',
		// 		'RealPr' => '本公司實際負責人',
		// 		'IsPrSpouseGu' => '配偶是否擔任本案保證人',
		// 		'PrStartYear' => '負責人從事本行業年度',
		// 		'PrEduLevel' => '負責人學歷',
		// 		'OthRealPrRelWithPr' => '實際負責(經營)人_其他實際負責經營人_ 與借戶負責人之關係',
		// 		'OthRealPrName' => '實際負責(經營)人_其他實際負責經營人_ 姓名',
		// 		'OthRealPrId' => '實際負責(經營)人_其他實際負責經營人_ 身分證字號',
		// 		'OthRealPrBirth' => '實際負責(經營)人_其他實際負責經營人_ 出生日期',
		// 		'OthRealPrStartYear' => '實際負責(經營)人_其他實際負責經營人_ 從事本行業年度',
		// 		'OthRealPrTitle' => '實際負責(經營)人_其他實際負責經營人_擔任本公司職務',
		// 		'OthRealPrSHRatio' => '實際負責(經營)人_其他實際負責經營人_持股比率%',
		// 		'GuOneRelWithPr' => '保證人甲_與借戶負責人之關係',
		// 		'GuOneCompany' => '保證人甲_任職公司',
		// 		'GuTwoRelWithPr' => '保證人乙_與借戶負責人之關係',
		// 		'GuTwoCompany' => '保證人乙_任職公司',
		// 		'SpouseCurAddrZip' => '配偶現居地址-郵遞區號',
		// 		'SpouseCurAddrZipName' => '配偶現居地址-郵遞區號名稱',
		// 		'SpouseCurlAddress' => '配偶現居地址-地址',
		// 		'SpouseMobileNo' => '配偶連絡行動電話',
		// 		'SpouseTelAreaCode' => '配偶連絡電話-區碼',
		// 		'SpouseTelNo' => '配偶連絡電話-電話號碼',
		// 		'SpouseTelExt' => '配偶連絡電話-分機碼',
		// 		'GuOneCurAddrZip' => '甲保證人現居地址-郵遞區號',
		// 		'GuOneCurAddrZipName' => '甲保證人現居地址-名稱',
		// 		'GuOneCurlAddress' => '甲保證人現居地址-地址',
		// 		'GuOneTelAreaCode' => '甲保證人連絡電話-區碼',
		// 		'GuOneTelNo' => '甲保證人連絡電話-電話號碼',
		// 		'GuOneTelExt' => '甲保證人連絡電話-分機碼',
		// 		'GuOneMobileNo' => '甲保證人聯絡行動電話',
		// 		'GuTwoCurAddrZip' => '乙保證人現居地址-郵遞區號',
		// 		'GuTwoCurAddrZipName' => '乙保證人現居地址-名稱',
		// 		'GuTwoCurlAddress' => '乙保證人現居地址-地址',
		// 		'GuTwoTelAreaCode' => '乙保證人連絡電話-區碼',
		// 		'GuTwoTelNo' => '乙保證人連絡電話-電話號碼',
		// 		'GuTwoTelExt' => '乙保證人連絡電話-分機碼',
		// 		'GuTwoMobileNo' => '乙保證人聯絡行動電話',
		// 	]
		// ],
		'1000' => [
			'type' => 'business_tax_return',
			'file_location' => 'business_tax_image',
            'image_location' => ['business_tax_image'],
		],
		// '12' => [
		// 	'type' => 'credit_investigation',
		// 	'file_location' => 'person_mq_image',
        //     'image_location' => ['person_mq_image'],
		// 	'total_table' => [
		// 		'action_user' => '編輯人',
		// 		'send_time' => '編輯時間',
		// 		'status' => '編輯狀態',
		// 		'personName' => '戶名',
		// 		'printDatetime' => '印表時間'
		// 	]
		// ],
		'1001' => [
			'type' => 'balance_sheet',
			'file_location' => 'balance_sheet_image',
            'image_location' => ['balance_sheet_image'],
		],
		// '1002' => [
		// 	'type' => 'income_statement',
		// 	'file_location' => 'income_statement_image',
        //     'image_location' => ['income_statement_image'],
		// 	'total_table' =>[
		// 		'action_user' => '編輯人',
		// 		'send_time' => '編輯時間',
		// 		'status' => '編輯狀態',
		// 		'report_time' => '所得期間',
		// 		'company_tax_id' => '統一編號',
		// 		'company_name' => '公司名稱',
		// 		'input_89' => '標準代號(89)',
		// 		'input_90' => '營業收入淨額(90)',
		// 		'input_4_1' => '結算申報書營業收入(年度、營收)(04)-帳載結算金額',
		// 		'input_4_2' => '結算申報書營業收入(年度、營收)(04)-自行依法調整後金額',
		// 	],
		// ],
		// '1003' => [
		// 	'type' => 'credit_investigation',
		// 	'file_location' => 'legal_person_mq_image',
        //     'image_location' => ['legal_person_mq_image'],
		// 	'total_table' =>[
		// 		'action_user' => '編輯人',
		// 		'send_time' => '編輯時間',
		// 		'status' => '編輯狀態',
		// 		'id-card' => '統一編號',
		// 		'name' => '戶名'
		// 	],
		// ],
		// '1007' => [
		// 	'type' => 'amendment_of_register',
		// 	'file_location' => 'governmentauthorities_image',
        //     'image_location' => ['governmentauthorities_image'],
		// 	'total_table' => [
		// 		'action_user' => '編輯人',
		// 		'send_time' => '編輯時間',
		// 		'status' => '編輯狀態',
		// 		'tax_id' => '統一編號',
		// 		'name' => '公司名稱',
		// 		'capital' => '資本總額/實收資本總額',
		// 		'address' => '公司所在地',
		// 		'owner' => '公司負責人',
		// 		'owner_id' => '公司負責人統一編號',
		// 		'director_title' => '董事/監察人名單首欄(職稱)',
		// 		'director_name' => '董事/監察人名單首欄(姓名)',
		// 		'director_id' => '董事/監察人名單首欄(身分證號或法人統一編號)',
		// 	],
		// ],
		// '1017' => [
		// 	'type' => 'insurance_table',
		// 	'file_location' => 'employeeinsurancelist_image',
        //     'image_location' => ['employeeinsurancelist_image'],
		// 	'total_table' => [
		// 		'action_user' => '編輯人',
		// 		'send_time' => '編輯時間',
		// 		'status' => '編輯狀態',
		// 		'company_name' => '公司名稱',
		// 		'report_time' => '印表日期',
		// 		'range' => '計費年月',
		// 		'average' => '月底生效人數近一年平均',
		// 	],
		// ],
		// '1018' => [
		// 	'type' => 'profilejudicial',
		// 	'file_location' => '',
        //     'image_location' => ['BizLandOwnership', 'BizHouseOwnership', 'RealLandOwnership', 'RealHouseOwnership'],
		// 	'total_table' => [
		// 		'action_user' => '編輯人',
		// 		'send_time' => '編輯時間',
		// 		'status' => '編輯狀態',
        //         'CompMajorAddrZip' => '公司主要營業場所-郵遞區號' ,
        //         'CompMajorAddrZipName' => '公司主要營業場所-名稱' ,
        //         'CompMajorAddress' => '公司主要營業場所-地址' ,
        //         'CompMajorCityName' => '主要營業場所建號-縣市名' ,
        //         'CompMajorAreaName' => '主要營業場所建號-地區' ,
        //         'CompMajorSecName' => '主要營業場所建號-段名' ,
        //         'CompMajorSecNo' => '主要營業場所建號-段號' ,
        //         'CompMajorOwnership' => '主要營業場所所有權' ,
        //         'CompMajorSetting' => '營業場所設定' ,
        //         'CompTelAreaCode' => '公司聯絡電話-區碼' ,
        //         'CompTelNo' => '公司聯絡電話-電話號碼' ,
        //         'CompTelExt' => '公司聯絡電話-分機碼' ,
        //         'BusinessType' => '營業種類' ,
        //         'Comptype' => '公司產業別' ,
        //         'IsBizRegAddrSelfOwn' => '營業登記地址' ,
        //         'BizRegAddrOwner' => '營業登記地址_自有' ,
        //         'IsBizAddrEqToBizRegAddr' => '實際營業地址_是否同營業登記地址' ,
        //         'RealBizAddrCityName' => '實際營業地址_選擇縣市' ,
        //         'RealBizAddrAreaName' => '實際營業地址_選擇鄉鎮市區' ,
        //         'RealBizAddrRoadName' => '實際營業地址_路街名稱(不含路、街)' ,
        //         'RealBizAddrRoadType' => '實際營業地址_路 OR 街' ,
        //         'RealBizAddrSec' => '實際營業地址_段' ,
        //         'RealBizAddrLn' => '實際營業地址_巷' ,
        //         'RealBizAddrAly' => '實際營業地址_弄' ,
        //         'RealBizAddrNo' => '實際營業地址_號(不含之號)' ,
        //         'RealBizAddrNoExt' => '實際營業地址_之號' ,
        //         'RealBizAddrFloor' => '實際營業地址_樓(不含之樓、室)' ,
        //         'RealBizAddrFloorExt' => '實際營業地址_之樓' ,
        //         'RealBizAddrRoom' => '實際營業地址_室' ,
        //         'RealBizAddrOtherMemo' => '實際營業地址_其他備註' ,
        //         'IsRealBizAddrSelfOwn' => '實際營業地址' ,
        //         'RealBizAddrOwner' => '實際營業地址_自有' ,
        //         'BizTaxFileWay' => '營業稅申報方式' ,
        //         'DirectorAName' => '公司董監事 A 姓名' ,
        //         'DirectorAId' => '公司董監事 A 統編' ,
        //         'DirectorBName' => '公司董監事 B 姓名' ,
        //         'DirectorBId' => '公司董監事 B 統編' ,
        //         'DirectorCName' => '公司董監事 C 姓名' ,
        //         'DirectorCId' => '公司董監事 C 統編' ,
        //         'DirectorDName' => '公司董監事 D 姓名' ,
        //         'DirectorDId' => '公司董監事 D 統編' ,
        //         'DirectorEName' => '公司董監事 E 姓名' ,
        //         'DirectorEId' => '公司董監事 E 統編' ,
        //         'DirectorFName' => '公司董監事 F 姓名' ,
        //         'DirectorFId' => '公司董監事 F 統編' ,
        //         'DirectorGName' => '公司董監事 G 姓名' ,
        //         'DirectorGId' => '公司董監事 G 統編' ,
        //         'main_business' => '主要業務範疇' ,
        //         'main_product' => '主要產品' ,
        //         'history' => '組織沿革' ,
		// 	],
		// ],
        // '1019' => [
        //     'type' => 'companyemail',
        //     'file_location' => 'companyemail',
        //     'image_location' => ['companyemail'],
        //     'total_table' => [
        //         'action_user' => '編輯人',
        //         'send_time' => '編輯時間',
        //         'status' => '編輯狀態',
        //         'email' => '公司電子信箱',
        //     ],
        // ],
        '1020' => [
            'type' => 'judicialguarantee',
            'file_location' => 'judicialguarantee',
            'image_location' => ['judicialguarantee'],
            'total_table' => [
                'action_user' => '編輯人',
                'send_time' => '編輯時間',
                'status' => '編輯狀態',
                'image_url' => '對保照片',
            ],
        ],
		// '501' => [
		// 	'type' => 'simplificationjob',
		// 	'file_location' => 'labor_image',
        //     'image_location' => ['labor_image'],
        //     'total_table' => [
        //         'action_user' => '編輯人',
        //         'send_time' => '編輯時間',
        //         'status' => '編輯狀態',
        //         'name' => '姓名',
		// 		'birthday' => '出生日期',
		// 		'id_card' => '身份證字號',
		// 	 	'search_day' => '查詢日期',
		// 		'salary' => '投保薪資',
        //     ],
		// ],
	];

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
            'image_name' => ['postal_image'],
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
	 * [isInTemplate 是否使用認證模板]
	 * @param  string $certification_id [認證類型ID]
	 * @return bool                     [true|false]
	 */
	public function isInTemplate($certification_id=''){
		if(isset($this->certification_mapping[$certification_id])){
			return true;
		}else{
			return false;
		}
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

    public function getUserPostImagesKey($certification_id=''){
        $key_name = [];
        if(isset($this->certification_mapping[$certification_id]['image_location'])){
            $key_name = $this->certification_mapping[$certification_id]['image_location'];
        }
        return $key_name;
    }

	/**
	 * [getTotalTableDataArray 組裝資料表格資料]
	 * @param  string $certification_id [認證類型ID]
	 * @param  array  $data_infos       [上傳檔案中有用的資料]
	 * @param  array  $error_location   [資料有誤位置 Key值]
	 * @return array  $data             [表格資料]
	 */
	public function getTotalTableDataArray($certification_id='',$data_infos=[],$error_location=[]){
		$data = [];
		foreach($data_infos as $k=>$v){
			foreach($v as $k1=>$v1){
				if($k1 == 'action_user'){
					$this->CI->load->model('admin/admin_model');
					$admin_info	= $this->CI->admin_model->get_by(['id'=>$v1]);
					if($admin_info){
						$v1 = isset($admin_info->name) ? $admin_info->name : '-----';
					}
				}

				if($k1 == 'send_time'){
					$v1 = isset($v1) ? date('Y-m-d H:i:s',$v1) : '-----';
				}

				if(isset($error_location[$k])){
					if(in_array($k1,$error_location[$k])){
						$v1 = '<span style="color:red;">'.$v1.'</span>';
					}
				}
				$value[$k1][] = $v1;
			}
		}

		foreach($value as $k=>$v){
			if(isset($this->certification_mapping[$certification_id]['total_table'][$k])){
				$data[] = [
					'title' => $this->certification_mapping[$certification_id]['total_table'][$k],
					'value' => $v,
				];
			}
		}

		return $data;
	}
}

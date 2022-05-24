<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use Adapter\Adapter_factory;

class Check_list
{

	public function __construct()
	{
		$this->CI = &get_instance();
	}

	// 收件檢核表變卡相關資料
	public function get_1007_governmentauthorities_data($meta_data){
		$response = [];
		$meta_data = json_decode($meta_data,true) ? json_decode($meta_data,true) : [];
		if($meta_data){
			foreach($meta_data as $k => $v){
				// if($k == ''){
				// 	$k = '';
				// }
				// if($k == ''){
				// 	$k = '';
				// }
				$response[$k.'_content'] = $v;
			}
			// $this->CI->load->library('gcis_lib');
			// if(isset($meta_data['tax_id'])){
			// 	// 商業司(公司)
			// 	$gcis_info = $this->CI->gcis_lib->account_info($meta_data['tax_id']);
			// 	if(! $gcis_info){
			// 		// 商業司(行號)
			// 		$gcis_info = $this->CI->gcis_lib->account_info_businesss($meta_data['tax_id']);
			// 	}
			//
			// 	$response['CompId_content'] = $meta_data['tax_id'];
			// 	// 財政部 api
			// 	$meragre_array = $this->get_mof_api($meta_data['tax_id']);
			// 	$response = array_merge($response,$meragre_array);
			//
			// 	// 商業司爬蟲
			// 	$this->CI->load->library('scraper/findbiz_lib');
	      	// 	$res = $this->CI->findbiz_lib->getResultByBusinessId($meta_data['tax_id']);
			// 	if($res){
			// 		$findbiz_info = $this->CI->findbiz_lib->searchEachTermOwner($res);
			// 		krsort($findbiz_info);
			// 		$num = 0;
			// 		foreach($findbiz_info as $k=>$v){
			// 			if($num==0){
			// 				$response['PrOnboardDay_content'] = $k;
			// 				$response['PrOnboardName_content'] = $v;
			// 			}
			// 			if($num==1){
			// 				$response['ExPrOnboardDay_content'] = $k;
			// 				$response['ExPrOnboardName_content'] = $v;
			// 			}
			// 			if($num==2){
			// 				$response['ExPrOnboardDay2_content'] = $k;
			// 				$response['ExPrOnboardName2_content'] = $v;
			// 			}
			// 			if($num==3){
			// 				break;
			// 			}
			// 			$num++;
			// 		}
			// 	}
			//
			// 	if($gcis_info){
			// 		$response['CompType_content'] = isset($gcis_info['Business_Organization_Type_Desc']) && $gcis_info['Business_Organization_Type_Desc'] == '獨資' ? '獨資': '中小企業';
			// 		$response['CompName_content'] = $gcis_info['Company_Name'];
			// 		$response['CompName_content'] = $gcis_info['Company_Name'];
			// 		$response['CompSetDate_content'] = $gcis_info['Company_Setup_Date'];
			// 		$response['CompCapital_content'] = $gcis_info['Paid_In_Capital_Amount'] ? $gcis_info['Paid_In_Capital_Amount'] : $gcis_info['Capital_Stock_Amount'];
			// 		$response['CompRegAddress_content'] = $gcis_info['Company_Location'];
			// 		$response['PrName_content'] = $gcis_info['Responsible_Name'];
			// 		// 郵遞區號
			// 		if($gcis_info['Company_Location']){
			// 			$this->CI->load->library('mapping/address');
		    //     $zip_code = $this->CI->address->getZipAdrNumber($gcis_info['Company_Location']);
			// 			$zip_name = '';
			// 			if($zip_code){
			// 				$zip_name = $this->CI->address->getZipAdrName($zip_code,2);
			// 			}
			// 			// 地址拆分
			// 			$split_address = $this->CI->address->splitAddress($gcis_info['Company_Location']);
			// 			$response['BizRegAddrCityName_content'] = $split_address['city'];
			// 			$response['BizRegAddrAreaName_content'] = $split_address['area'];
			// 			$response['BizRegAddrRoadName_content'] = $split_address['road'];
			// 			$response['BizRegAddrSec_content'] = $split_address['part'];
			// 			$response['BizRegAddrLn_content'] = $split_address['lane'];
			// 			$response['BizRegAddrAly_content'] = $split_address['alley'];
			// 			$response['BizRegAddrNo_content'] = $split_address['number'];
			// 			$response['BizRegAddrNoExt_content'] = $split_address['sub_number'];
			// 			$response['BizRegAddrFloor_content'] = $split_address['floor'];
			// 			$response['BizRegAddrFloorExt_content'] = $split_address['sub_floor'];
			// 		}
			//
			// 		$response['CompRegAddrZip_content'] = $zip_code;
			// 		// 郵遞區號名稱前面需加入市或縣
			// 		$response['CompRegAddrZipName_content'] = $zip_name;
			// 	}
			// }
		}
		return $response;
	}

	// 財政部 api (行業別)
	public function get_mof_api($tax_id){
		$response = [];
		$this->CI->load->library('scraper/business_registration_lib');
		$res = $this->CI->business_registration_lib->getResultByBusinessId($tax_id);
		if(isset($res->response->industryName)){
			$response['CompIdustry_content'] = $res->response->industryName;
		}
		return $response;
	}

	// 收件檢核表法人聯徵相關資料
	public function get_1003_credit_investigation_data($meta_data){
		$response = [];
		$meta_data = json_decode($meta_data,true) ? json_decode($meta_data,true) : '';
		if($meta_data){
			$response['CompJCICQueryDate_content'] = isset($meta_data['printDatetime']) ? $meta_data['printDatetime'] : '';
			$response['MidTermLnYM_content'] = isset($meta_data['totalAmountMidMonth']) ? $meta_data['totalAmountMidMonth'] : '';
			$response['MidTermLnBal_content'] = isset($meta_data['totalAmountMid']) ? $meta_data['totalAmountMid'] : '';
			$response['ShortTermLnYM_content'] = isset($meta_data['totalAmountShortMonth']) ? $meta_data['totalAmountShortMonth'] : '';
			$response['ShortTermLnBal_content'] = isset($meta_data['totalAmountShort']) ? $meta_data['totalAmountShort'] : '';
			$response['CompCreditScore_content'] = isset($meta_data['scoreComment']) ? $meta_data['scoreComment'] : '';
		}
		return $response;
	}
	// 收件檢核表自然人聯徵相關資料
	public function get_9_credit_investigation_data($meta_data,$roles){
		$response = [];
		$meta_data = json_decode($meta_data,true) ? json_decode($meta_data,true) : '';
		if($meta_data){
			$response["{$roles}JCICQueryDate"] = isset($meta_data['printDatetime']) ? $meta_data['printDatetime'] : '';
			$response["{$roles}CreditScore"] = isset($meta_data['scoreComment']) ? $meta_data['scoreComment'] : '';
			$response["{$roles}Bal_CashCard"] = isset($meta_data['totalAmountCash']) ? $meta_data['totalAmountCash'] : '';
			$response["{$roles}Bal_CreditCard"] = isset($meta_data['totalAmountCreditCard']) ? $meta_data['totalAmountCreditCard'] : '';
			$response["{$roles}Bal_ShortTermLn"] = isset($meta_data['totalAmountShort']) ? $meta_data['totalAmountShort'] : '';
			$response["{$roles}Bal_MidTermLn"] = isset($meta_data['totalAmountMid']) ? $meta_data['totalAmountMid'] : '';
			$response["{$roles}Bal_LongTermLn"] = isset($meta_data['totalAmountLong']) ? $meta_data['totalAmountLong'] : '';
			$response["{$roles}Bal_ShortTermGuar"] = isset($meta_data['totalAmountShortAssure']) ? $meta_data['totalAmountShortAssure'] : '';
			$response["{$roles}Bal_MidTermLnGuar"] = isset($meta_data['totalAmountMidAssure']) ? $meta_data['totalAmountMidAssure'] : '';
			$response["{$roles}Bal_LongTermLnGuar"] = isset($meta_data['totalAmountLongAssure']) ? $meta_data['totalAmountLongAssure'] : '';
		}
		return $response;
	}

	// 收件檢核表公司資料表
	public function get_1018_profilejudicial_data($meta_data){
		$response = [];
		$meta_data = json_decode($meta_data,true) ? json_decode($meta_data,true) : '';
		if($meta_data){
			foreach($meta_data as $k=>$v){
				$response[$k] = isset($v) ? $v : '';
			}
		}
		return $response;
	}

	// 收件檢核表個人資料表
	public function get_11_profile_data($meta_data){
		$response = [];
		// $meta_data = json_decode($meta_data,true) ? json_decode($meta_data,true) : '';
		if($meta_data){
			foreach($meta_data as $k=>$v){
				$response[$k] = isset($v) ? $v : '';
			}
		}
		return $response;
	}

	// 收件檢核表月末投保人數相關資料
	public function get_1017_employeeinsurancelist_data($meta_data){
		$response = [];
		// $not_count_array = ['company_name','report_time','average','range'];
		$meta_data = json_decode($meta_data,true) ? json_decode($meta_data,true) : '';
		if($meta_data){
			krsort($meta_data);
			$num = 1;
			if(isset($meta_data['list'])){
				foreach($meta_data['list'] as $k=>$v){
					$response['NumOfInsuredYM'.$num.'_content'] = $k;
					$response['NumOfInsured'.$num.'_content'] = $v;
					$num += 1;
				}
			}else{
				foreach($meta_data as $k=>$v){
					if(preg_match('/NumOfInsuredYM|NumOfInsured/',$k)){
						$response[$k.'_content'] = $v;
					}
				}
			}

		}
		return $response;
	}

	// 收件檢核表損益表相關欄位
	public function get_1002_incomestatement_data($meta_data){
		$response = [];
		$meta_data = json_decode($meta_data,true) ? json_decode($meta_data,true) : '';
		if($meta_data){
			krsort($meta_data);
			$num = 1;
			foreach($meta_data as $k=>$v){
				if($k!='input_89'){
					$response['AnnualIncomeYear'.$num.'_content'] = $k;
					$response['AnnualIncome'.$num.'_content'] = $v;
					$num += 1;
				}
			}
		}
		return $response;
	}

	// 收件檢核表負責人身分證相關欄位
	public function get_company_user_data($certification_info,$roles){
		$response = [];
		$address_info =[];
		$zip_code = '';
		$zip_name = '';

		// 郵遞區號
		if(isset($certification_info['address'])){
			$this->CI->load->library('mapping/address');
			$zip_code = $this->CI->address->getZipAdrNumber($certification_info['address']);
			if($zip_code){
				$zip_name = $this->CI->address->getZipAdrName($zip_code,2);
			}
		}

		if($roles == 'Pr'){
			$id_card = 'Principal';
		}else{
			$id_card = $roles;
		}

		$response[$roles.'Name_content'] = isset($certification_info['name']) ? $certification_info['name'] : '';
		$response[$id_card.'Id_content'] = isset($certification_info['id_number']) ? $certification_info['id_number'] : '';
		$response[$roles.'Birth_content'] = isset($certification_info['birthday']) ? $certification_info['birthday'] : '';
		$response[$roles.'ResAddrZip_content'] = $zip_code;
		// 郵遞區號名稱前面需加入市或縣
		$response[$roles.'ResAddrZipName_content'] = $zip_name;
		$response[$roles.'ReslAddress_content'] = isset($certification_info['address']) ? $certification_info['address'] : '';
		return $response;
	}

	// 勞保異動明細
	public function get_insurance_table_user_data($meta_data,$roles){
		$response = [];
		// meta_key
		$response[$roles.'LaborQryDate_content'] = isset($meta_data['meta_key']['search_day']) ? $meta_data['meta_key']['search_day'] : '';
		$response[$roles.'LaborInsSalary_content'] = isset($meta_data['meta_key']['salary']) ? $meta_data['meta_key']['salary'] : '';
		return $response;
	}

	// 附件檢核表圖片資料
    public function get_raw_data($target_info=[], $bank=MAPPING_MSG_NO_BANK_NUM_SKBANK, $get_api_attach_no=FALSE): array
    {
	    $data = $this->_get_raw_data($target_info);

        $adapter = Adapter_factory::getInstance($bank);
        return $adapter->convert_attach($data, $get_api_attach_no);
    }

    private function _get_raw_data($target_info = []): array
    {
        $response = [
            'A01' => [], // 公司變更事項登記卡及工商登記查詢
            'A02' => [], // 負責人及保證人身分證影本及第二證件、戶役政查詢
            'A03' => [], // 營業據點建物登記謄本(公司或負責人或保證人自有才須提供)
            'A04' => [], // 近三年公司所得稅申報書
            'A05' => [], // 近12月勞保局投保資料
            'A06' => [], // 公司、負責人、配偶及保證人的聯徵資料 J01、J02、J10、J20、A13、A11
            'A07' => [], // 負責人及保證人之被保險人勞保異動查詢
            'A08' => [], // 公司、負責人及保證人近六個月存摺餘額明細及存摺封面
            'B02' => [], // 負責人身分證 + 健保卡
            'B03' => [], // 負責人配偶身分證 + 健保卡
            'B04' => [], // 保證人身分證 + 健保卡
            'B08' => [], // 公司聯徵資料
            'B09' => [], // 負責人聯徵資料
            'B10' => [], // 負責人配偶聯徵資料
            'B11' => [], // 保證人聯徵資料
            'B13' => [], // 公司近六個月往來存摺影本+內頁
            'B14' => [], // 負責人近六個月往來存摺影本+內頁
            'B15' => [], // 保證人近六個月往來存摺影本+內頁
            'B16' => [], // 近三年 401/403/405
        ];

        $mapping_config = [
            // 變卡
            CERTIFICATION_GOVERNMENTAUTHORITIES => [
                [
                    'location' => 'A01',
                    'raw_data_name' => ['governmentauthorities_image']
                ]
            ],
            // 實名
            CERTIFICATION_IDENTITY => [
                [
                    'location' => 'A02',
                    'raw_data_name' => ['front_image', 'back_image', 'healthcard_image']
                ],
                [
                    'location' => 'B02',
                    'character' => ASSOCIATES_CHARACTER_OWNER,
                    'raw_data_name' => ['front_image', 'back_image', 'healthcard_image']
                ],
                [
                    'location' => 'B03',
                    'character' => ASSOCIATES_CHARACTER_SPOUSE,
                    'raw_data_name' => ['front_image', 'back_image', 'healthcard_image']
                ],
                [
                    'location' => 'B04',
                    'character' => ASSOCIATES_CHARACTER_GUARANTOR_A,
                    'raw_data_name' => ['front_image', 'back_image', 'healthcard_image']
                ],
            ],
            // 公司資料表
            CERTIFICATION_PROFILEJUDICIAL => [
                [
                    'location' => 'A03',
                    'raw_data_name' => ['BizLandOwnership', 'BizHouseOwnership', 'RealLandOwnership', 'RealHouseOwnership', 'DocTypeA03']
                ]
            ],
            // 自然人聯合徵信報告+A11
            CERTIFICATION_INVESTIGATIONA11 => [
                [
                    'location' => 'A06',
                    'raw_data_name' => ['person_mq_image']
                ],
                [
                    'location' => 'B09',
                    'character' => ASSOCIATES_CHARACTER_OWNER,
                    'raw_data_name' => ['person_mq_image']
                ],
                [
                    'location' => 'B10',
                    'character' => ASSOCIATES_CHARACTER_SPOUSE,
                    'raw_data_name' => ['person_mq_image']
                ],
                [
                    'location' => 'B11',
                    'character' => ASSOCIATES_CHARACTER_GUARANTOR_A,
                    'raw_data_name' => ['person_mq_image']
                ]
            ],
            // 損益表
            CERTIFICATION_INCOMESTATEMENT => [
                [
                    'location' => 'A04',
                    'raw_data_name' => ['income_statement_image']
                ]
            ],
            // 法人聯合徵信報告
            CERTIFICATION_INVESTIGATIONJUDICIAL => [
                [
                    'location' => 'A06',
                    'raw_data_name' => ['legal_person_mq_image', 'postal_image']
                ],
                [
                    'location' => 'B08',
                    'raw_data_name' => ['legal_person_mq_image', 'postal_image']
                ]
            ],
            // 月末投保人數
            CERTIFICATION_EMPLOYEEINSURANCELIST => [
                [
                    'location' => 'A05',
                    'raw_data_name' => ['employeeinsurancelist_image']
                ]
            ],
            // 負責人及保證人之被保險人勞保異動查詢
            CERTIFICATION_SIMPLIFICATIONJOB => [
                [
                    'location' => 'A07',
                    'raw_data_name' => ['labor_image']
                ]
            ],
            // 個人近六個月往來存摺影本+內頁
            CERTIFICATION_SIMPLIFICATIONFINANCIAL => [
                [
                    'location' => 'A08',
                    'raw_data_name' => ['passbook_image']
                ],
                [
                    'location' => 'B14',
                    'character' => ASSOCIATES_CHARACTER_OWNER,
                    'raw_data_name' => ['passbook_image']
                ],
                [
                    'location' => 'B15',
                    'character' => ASSOCIATES_CHARACTER_GUARANTOR_A,
                    'raw_data_name' => ['passbook_image']
                ]
            ],
            // 公司近六個月往來存摺影本+內頁
            CERTIFICATION_PASSBOOKCASHFLOW => [
                [
                    'location' => 'A08',
                    'raw_data_name' => ['passbook_image']
                ],
                [
                    'location' => 'B13',
                    'raw_data_name' => ['passbook_image']
                ]
            ],
            // 近三年 401/403/405
            CERTIFICATION_BUSINESSTAX => [
                [
                    'location' => 'B16',
                    'raw_data_name' => ['business_tax_image']
                ]
            ],
        ];

        $user_list = [];
        // TODO: 改撈法人關係再撈全徵提資料圖片
        if ( ! $target_info)
        {
            return [];
        }
        $user_list[] = $target_info->user_id;

        // 找案件關係人
        $this->CI->load->library('Target_lib');
        $guarantors = $this->CI->target_lib->get_associates_user_data($target_info->id, 'all', [0, 1], FALSE);
        if ($guarantors && is_array($guarantors))
        {
            foreach ($guarantors as $k => $v)
            {
                $user_list[] = $v->user_id ?? '';
            }
        }

        // 找案件關聯人，取得 [{character} => data]
        $associate_list = $this->CI->target_lib->get_associates_data($target_info->id, 'all');

        // 找全部認證
        $this->CI->load->model('user/user_certification_model');
        $certification_info = $this->CI->user_certification_model->get_skbank_check_list($user_list);
        if ( ! empty($certification_info))
        {
            foreach ($certification_info as $info_value)
            {
                $content = json_decode($info_value->content, TRUE);
                if (isset($mapping_config[$info_value->certification_id]) && is_array($content))
                {
                    // 找認證資料內有無相關圖片連結名稱
                    foreach ($mapping_config[$info_value->certification_id] as $mapping_info)
                    {
                        if (isset($mapping_info['character']) &&
                            ( ! array_key_exists($mapping_info['character'], $associate_list) || $associate_list[$mapping_info['character']]['user_id'] != $info_value->user_id))
                        {
                            continue;
                        }

                        foreach ($mapping_info['raw_data_name'] as $name)
                        {
                            if (isset($content[$name]))
                            {
                                $response_location = $mapping_info['location'];
                                if (is_array($content[$name]))
                                {
                                    $response[$response_location] = array_merge($response[$response_location], $content[$name]);
                                }
                                else
                                {
                                    $response[$response_location][] = $content[$name];
                                }
                            }
                        }
                    }
                }
            }
        }
        return $response;
    }
}

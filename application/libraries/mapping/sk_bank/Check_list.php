<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Check_list
{

	public function __construct()
	{
		$this->CI = &get_instance();
	}

	// 收件檢核表變卡相關資料
	public function get_1007_governmentauthorities_data($meta_data){
		$response = [];
		$meta_data = json_decode($meta_data,true) ? json_decode($meta_data,true) : '';
		if($meta_data){
			$this->CI->load->library('gcis_lib');
			if(isset($meta_data['tax_id'])){
				// 商業司(公司)
				$gcis_info = $this->CI->gcis_lib->account_info($meta_data['tax_id']);
				if(! $gcis_info){
					// 商業司(行號)
					$gcis_info = $this->CI->gcis_lib->account_info_businesss($meta_data['tax_id']);
				}

				$response['CompId_content'] = $meta_data['tax_id'];
				// 財政部 api
				$meragre_array = $this->get_mof_api($meta_data['tax_id']);
				$response = array_merge($response,$meragre_array);

				// 商業司爬蟲
				$this->CI->load->library('scraper/findbiz_lib');
	      $res = $this->CI->findbiz_lib->getResultByBusinessId($meta_data['tax_id']);
				// print_r($res);exit;
				if($res){
					$findbiz_info = $this->CI->findbiz_lib->searchEachTermOwner($res);
					krsort($findbiz_info);
					$num = 0;
					foreach($findbiz_info as $k=>$v){
						if($num==0){
							$response['PrOnboardDay_content'] = $k;
							$response['PrOnboardName_content'] = $v;
						}
						if($num==1){
							$response['ExPrOnboardDay_content'] = $k;
							$response['ExPrOnboardName_content'] = $v;
						}
						if($num==2){
							$response['ExPrOnboardDay2_content'] = $k;
							$response['ExPrOnboardName2_content'] = $v;
						}
						if($num==3){
							break;
						}
						$num++;
					}
				}

				if($gcis_info){
					$response['CompType_content'] = isset($gcis_info['Business_Organization_Type_Desc']) && $gcis_info['Business_Organization_Type_Desc'] == '獨資' ? '獨資': '中小企業';
					$response['CompName_content'] = $gcis_info['Company_Name'];
					$response['CompName_content'] = $gcis_info['Company_Name'];
					$response['CompSetDate_content'] = $gcis_info['Company_Setup_Date'];
					$response['CompCapital_content'] = $gcis_info['Paid_In_Capital_Amount'] ? $gcis_info['Paid_In_Capital_Amount'] : $gcis_info['Capital_Stock_Amount'];
					$response['CompRegAddress_content'] = $gcis_info['Company_Location'];
					$response['PrName_content'] = $gcis_info['Responsible_Name'];
					// 郵遞區號
					if($gcis_info['Company_Location']){
						$this->CI->load->library('mapping/address');
		        $zip_code = $this->CI->address->getZipAdrNumber($gcis_info['Company_Location']);
						$zip_name = '';
						if($zip_code){
							$zip_name = $this->CI->address->getZipAdrName($zip_code,2);
						}
						// 地址拆分
						$split_address = $this->CI->address->splitAddress($gcis_info['Company_Location']);
						$response['BizRegAddrCityName_content'] = $split_address['city'];
						$response['BizRegAddrAreaName_content'] = $split_address['area'];
						$response['BizRegAddrRoadName_content'] = $split_address['road'];
						$response['BizRegAddrSec_content'] = $split_address['part'];
						$response['BizRegAddrLn_content'] = $split_address['lane'];
						$response['BizRegAddrAly_content'] = $split_address['alley'];
						$response['BizRegAddrNo_content'] = $split_address['number'];
						$response['BizRegAddrNoExt_content'] = $split_address['sub_number'];
						$response['BizRegAddrFloor_content'] = $split_address['floor'];
						$response['BizRegAddrFloorExt_content'] = $split_address['sub_floor'];
					}

					$response['CompRegAddrZip_content'] = $zip_code;
					// 郵遞區號名稱前面需加入市或縣
					$response['CompRegAddrZipName_content'] = $zip_name;
				}
			}
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
	public function get_raw_data($target_info=[]){
		$response = [
			'A01' => [],
			'A02' => [],
			'A03' => [],
			'A04' => [],
			'A05' => [],
			'A06' => [],
			'A07' => [],
			'A08' => [],
		];

		$mapping_config = [
			// 變卡
			'1007' => [
				'location' => 'A01',
				'raw_data_name' => ['governmentauthorities_image'],
			],
			// 實名
			'1' => [
				'location' => 'A02',
				'raw_data_name' => ['front_image','back_image','healthcard_image']
			],
			// 自然人連徵
			'12' => [
				'location' => 'A06',
				'raw_data_name' =>['person_mq_image']
			],
			// 損益表
			'1002' => [
				'location' => 'A04',
				'raw_data_name' => ['income_statement_image']
			],
			// 法人連徵
			'1003' => [
				'location' => 'A06',
				'raw_data_name' => ['legal_person_mq_image']
			],
			// 月末投保人數
			'1017' => [
				'location' => 'A05',
				'raw_data_name' => ['employeeinsurancelist_image']
			],
			'501' => [
				'location' => 'A07',
				'raw_data_name' => ['labor_image']
			],
			'500' => [
				'location' => 'A08',
				'raw_data_name' => ['passbook_image']
			],
			// '' => [
			// 	'location' => '',
			// 	'raw_data_name' => []
			// ]
		];

		if($target_info && isset($target_info->target_data)){
			$this->CI->load->model('user/user_certification_model');
			$target_data = json_decode($target_info->target_data,true);

			if(isset($target_data['certification_id'])){
				// 找全部認證
				$certification_info = $this->CI->user_certification_model->get_many_by(['id'=>$target_data['certification_id']]);
				if($certification_info){
					foreach($certification_info as $info){
						if(isset($mapping_config[$info->certification_id]) && $info->content){
							$content = json_decode($info->content,true);
							// 找認證資料內有無相關圖片連結名稱
							foreach($mapping_config[$info->certification_id]['raw_data_name'] as $name){
								if(isset($content[$name])){
									$response_location = $mapping_config[$info->certification_id]['location'];
									if(is_array($content[$name])){
										$response[$response_location] = array_merge($response[$response_location],$content[$name]);
									}else{
										$response[$response_location][] = $content[$name];
									}
								}
							}
						}
					}
				}
			}
			// print_r($response);exit;

		}
		return $response;
	}
}

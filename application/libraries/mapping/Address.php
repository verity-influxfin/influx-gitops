<?php
class Address
{

	public $address_delete_string = [
		'市', '鄉', '鎮', '區', '路', '街', '道', '段', '巷', '弄', '號', '樓'
	];

	public function __construct()
	{
		$this->CI = &get_instance();
	}

	/**
	 * [getZipAdrNumber 獲取郵遞區號]
	 * @param  string $address 		[地址]
	 * @return string $zip_number [郵遞區號]
	 */
	public function getZipAdrNumber($address='')
	{
		$zip_number = '';

		if(!$address){
			return false;
		}
		$this->CI->load->library('utility/http_utility');
		$url = 'http://zip5.5432.tw/zip5json.py?adrs='.urlencode($address);
		$this->CI->http_utility->setUrl($url);
		$this->CI->http_utility->setWaitingTime(300);
		$result = $this->CI->http_utility->get();
		$address_info = json_decode($result,true);
		if($address_info){
			$zip_number = isset($address_info['zipcode']) ? $address_info['zipcode'] : '';
		}

		return $zip_number;
	}

	/**
	 * [getZipAdrName 獲取郵遞區號名稱]
	 * @param  string  $zip_number [郵遞區號]
	 * @param  integer $last_place [郵遞區號末?碼]
	 * @return string  $zip_name   [郵遞區號名稱]
	 */
	public function getZipAdrName($zip_number='',$last_place=0)
	{
		$zip_name = '';

		if(!$zip_number){
			return false;
		}
		if($last_place){
			$zip_code = substr($zip_number, 0, -$last_place);
		}else{
			$zip_code = $zip_number;
		}
		$zip_code = substr($zip_number, 0, -$last_place);
		$countries = file_get_contents("./assets/admin/js/mapping/address/taiwan_districts.json");
		if($countries && is_array(json_decode($countries,true))){
			$zipcode_info = json_decode($countries,true);
			foreach($zipcode_info as $key=>$area){
				if(isset($area['districts'])){
					$search_key = array_search($zip_code, array_column($area['districts'], 'zip'));
					if($search_key){
						$zip_name = $zipcode_info[$key]['districts'][$search_key]['name'];
						break;
					}
				}
			}
			// 郵遞區號名稱前面需加入市或縣
			// $address_info['zipcode_name'] = $zip_code;

		return $zip_name;
		}
	}

	/**
	 * [splitAddress 地址切割]
	 * @param  string $address       [地址]
	 * @return array $split_address [地址]
	 * (
	 *  [city] => 縣市
	 * 	[area] => 鄉鎮市區
	 * 	[road] => 路
	 * 	[part] => 段,
	 *	[lane] => 巷,
	 *	[alley] => 弄,
	 *	[number] => 號,
	 *	[sub_number] => 之號,
	 *	[floor] => 樓,
	 *	[sub_floor] => 之樓,
	 * )
	 */
	public function splitAddress($address='')
	{
		$split_address = [
			'city' => '',
			'area' => '',
			'road' => '',
			'part' => '',
			'lane' => '',
			'alley' => '',
			'number' => '',
			'sub_number' => '',
			'floor' => '',
			'sub_floor' => '',
		];

		if(!$address){
			return false;
		}

		preg_match('/(\D+?[縣市])(\D+?(市區|鎮區|鎮市|[鄉鎮市區]))?(\D+?[村里])?(\d+[鄰])?(\D+[鄰])?(\D+?(村路|[路街道段]))?(\d+?(村路|[路街道段]))?(\d?段)?(\D?段)?(\d+巷)?(\D+巷)?(\d+弄)?(\D+弄)?(\d+之?\d號)?(\D+之?\D號)?(\d.*號)?(\d+樓\d之?.*)?(\D+樓\D之?.*)?(.+)?/u',$address,$split_array,PREG_OFFSET_CAPTURE);
		if($split_array){
			foreach($split_array as $k=>$v){
				if(in_array($v[0],$this->address_delete_string) || $v[0] == '' || $k == 0){
					unset($split_array[$k]);
				}
			}
			foreach($split_array as $k=>$v){
					if(preg_match('/[縣市]/u',$v[0]) && $v[1] == 0){
						$split_address['city'] = $v[0];
					}
					if(preg_match('/((市區|鎮區|鎮市|[鄉鎮市區]))/u',$v[0]) && $v[1] != 0){
						$split_address['area'] = $v[0];
					}
					if(preg_match('/((村路|[路街道]))/u',$v[0])){
						$split_address['road'] = $v[0];
					}
					if(preg_match('/(段)/u',$v[0])){
						$split_address['part'] = $v[0];
					}
					if(preg_match('/(巷)/u',$v[0])){
						$split_address['lane'] = $v[0];
					}
					if(preg_match('/(弄)/u',$v[0])){
						$split_address['alley'] = $v[0];
					}
					if(preg_match('/(號)/u',$v[0])){
						$address_number = preg_replace('/號/u','',$v[0]);
						if($address_number && preg_match('/之/u',$address_number)){
							$address_number = preg_split('/之/u',$address_number);
							$split_address['number'] = isset($address_number[0]) ? $address_number[0] : '';
							$split_address['sub_number'] = isset($address_number[1]) ? $address_number[1] : '';
						}else{
							if(preg_match('/-/u',$v[0])){
								$address_number = preg_split('/-/u',$address_number);
								$split_address['number'] = isset($address_number[0]) ? $address_number[0] : '';
								$split_address['sub_number'] = isset($address_number[1]) ? $address_number[1] : '';
							}else{
								$split_address['number'] = $address_number;
							}
						}
					}
					if(preg_match('/(樓)/u',$v[0])){
						$address_floor = preg_replace('/樓/u','',$v[0]);
						if($address_floor && preg_match('/之/u',$address_floor)){
							$address_floor = preg_split('/之/u',$address_floor);
							$split_address['floor'] = isset($address_floor[0]) ? $address_floor[0] : '';
							$split_address['sub_floor'] = isset($address_floor[1]) ? $address_floor[1] : '';
						}else{
							if(preg_match('/-/u',$v[0])){
								$address_floor = preg_split('/-/u',$address_floor);
								$split_address['floor'] = isset($address_floor[0]) ? $address_floor[0] : '';
								$split_address['sub_floor'] = isset($address_floor[1]) ? $address_floor[1] : '';
							}else{
								$split_address['floor'] = $address_floor;
							}
						}
					}
			}
		}

		return $split_address;
	}
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gcis_lib
{
	
	private $url = 'http://data.gcis.nat.gov.tw/od/data/api/';
	
    public function __construct()
    {
        $this->CI = &get_instance();
    }

    //公司統編查詢
    public function account_info($account_no=''){
        if(strlen($account_no)==8){
            $url 	= $this->url.'5F64D864-61CB-4D0D-8AD9-492047CC1EA6?$format=json&$filter=Business_Accounting_NO%20eq%20'.$account_no.'&$skip=0';
            $rs  	= curl_get($url);
            $rs 	= json_decode($rs,TRUE);
            if($rs && isset($rs[0])){
                $data = $rs[0];
                //換成西元年
                $data['Company_Setup_Date'] 		= $data['Company_Setup_Date']?date('Y-m-d',strtotime(intval($data['Company_Setup_Date'])+19110000)):'';
                $data['Change_Of_Approval_Data'] 	= $data['Change_Of_Approval_Data']?date('Y-m-d',strtotime(intval($data['Change_Of_Approval_Data'])+19110000)):'';
                return $data;
            }
        }
        return false;
    }

    //商業統編查詢
    public function account_info_businesss($account_no=''){
        $Agency = [
            '376410000A' => '新北市政府經濟發展局',
            '379100000G' => '臺北市商業處',
            '383100000G' => '高雄市政府',
            '376570000A' => '基隆市政府',
            '376580000A' => '新竹市政府',
            '376590000A' => '臺中市政府',
            '376610000A' => '臺南市政府',
            '376600000A' => '嘉義市政府',
            '376430000A' => '桃園市政府',
            '376440000A' => '新竹縣政府',
            '376420000A' => '宜蘭縣政府',
            '376450000A' => '苗栗縣政府',
            '376470000A' => '彰化縣政府',
            '376480000A' => '南投縣政府',
            '376490000A' => '雲林縣政府',
            '376500000A' => '嘉義縣政府',
            '376530000A' => '屏東縣政府',
            '376560000A' => '澎湖縣政府',
            '376550000A' => '花蓮縣政府',
            '376540000A' => '臺東縣政府',
            '371010000A' => '福建省金門縣政府',
            '371030000A' => '福建省連江縣政府',
        ];
        if(strlen($account_no)==8){
            foreach($Agency as $key => $value){
                $url 	= $this->url.'7E6AFA72-AD6A-46D3-8681-ED77951D912D?$format=json&$filter=President_No%20eq%20'.$account_no.'%20and%20Agency%20eq%20'.$value.'&$skip=0&$top=50';
                $rs  	= curl_get($url);
                $rs 	= json_decode($rs,TRUE);
                if($rs && isset($rs[0])){
                    $data = $rs[0];
                    //換成西元年
                    $data['Company_Setup_Date'] 		= $data['Company_Setup_Date']?date('Y-m-d',strtotime(intval($data['Company_Setup_Date'])+19110000)):'';
                    $data['Change_Of_Approval_Data'] 	= $data['Change_Of_Approval_Data']?date('Y-m-d',strtotime(intval($data['Change_Of_Approval_Data'])+19110000)):'';
                    return $data;
                }
            }
        }
        return false;
    }

    //股東查詢
	public function get_shareholders($account_no=''){
		if(strlen($account_no)==8){
			$url 	= $this->url.'4E5F7653-1B91-4DDC-99D5-468530FAE396?$format=json&$filter=Business_Accounting_NO%20eq%20'.$account_no.'&$skip=0&$top=50';
			$rs  	= curl_get($url);
			$data 	= json_decode($rs,TRUE);
			if($data){
				return $data;
			}
		}
		return false;
	}
	
	//公司負責人查詢
	public function check_responsible($account_no='',$name=''){
		if(!empty($name) && strlen($account_no)==8){
			$url 	= $this->url.'4B61A0F1-458C-43F9-93F3-9FD6DA5E1B08?$format=json&$filter=Responsible_Name%20eq%20'.$name.'&$skip=0&$top=50';
			$rs  	= curl_get($url);
			$data 	= json_decode($rs,TRUE);
			if($data){
				foreach($data as $key => $value){
					if($value['Business_Accounting_NO']==$account_no){
						return $value;
					}
				}
			}
		}
		return false;
	}

    //商業負責人查詢
    public function check_responsible_businesss($account_no='',$name=''){
        if(!empty($name) && strlen($account_no)==8){
            $url 	= $this->url.'35BCAB6C-9876-4356-B674-538E4F1DED5E?$format=json&$filter=Business_Current_Status%20eq%2001%20and%20Responsible_Name%20eq%20'.$name.'&$skip=0&$top=50';
            $rs  	= curl_get($url);
            $data 	= json_decode($rs,TRUE);
            if($data){
                foreach($data as $key => $value){
                    if($value['President_No']==$account_no){
                        return $value;
                    }
                }
            }
        }
        return false;
    }
}
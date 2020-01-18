<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gcis_lib
{
	
	private $url = 'https://data.gcis.nat.gov.tw/od/data/api/';
	
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
        $Agency = $this->CI->config->item('Agency');
        if(strlen($account_no)==8){
            foreach($Agency as $key=>$value){
                $url 	= $this->url.'7E6AFA72-AD6A-46D3-8681-ED77951D912D?$format=json&$filter=President_No%20eq%20'.$account_no.'%20and%20Agency%20eq%20'.$value.'&$skip=0&$top=50';
                $rs  	= curl_get($url);
				$rs 	= json_decode($rs,TRUE);
				
                if(isset($rs[0])){
                    $data = $rs[0];
                    //換成西元年
                    $data['Business_Setup_Approve_Date'] 		= $data['Business_Setup_Approve_Date']?date('Y-m-d',strtotime(intval($data['Business_Setup_Approve_Date'])+19110000)):'';
                    $data['Business_Last_Change_Date'] 	= $data['Business_Last_Change_Date']?date('Y-m-d',strtotime(intval($data['Business_Last_Change_Date'])+19110000)):'';
                     return   $data;
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

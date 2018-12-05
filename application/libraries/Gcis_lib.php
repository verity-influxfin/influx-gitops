<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gcis_lib
{
	
	private $url = "http://data.gcis.nat.gov.tw/od/data/api/";
	
    public function __construct()
    {
        $this->CI = &get_instance();
    }

	//統編查詢
	public function account_info($account_no=''){
		if(strlen($account_no)==8){
			$url 	= $this->url.'5F64D864-61CB-4D0D-8AD9-492047CC1EA6?$format=json&$filter=Business_Accounting_NO%20eq%20'.$account_no.'&$skip=0';
			$rs  	= curl_get($url);
			$rs 	= json_decode($rs,TRUE);
			if($rs && isset($rs[0])){
				$data = $rs[0];
				//換成西元年
				$data['Company_Setup_Date'] 		= $data['Company_Setup_Date']?date("Y-m-d",strtotime(intval($data['Company_Setup_Date'])+19110000)):'';
				$data['Change_Of_Approval_Data'] 	= $data['Change_Of_Approval_Data']?date("Y-m-d",strtotime(intval($data['Change_Of_Approval_Data'])+19110000)):'';
				return $data;
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
	
	//負責人查詢
	public function check_responsible($account_no='',$name=""){
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
}
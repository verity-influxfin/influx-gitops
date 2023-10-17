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

    /**
     * 取得商業司的 API 資訊
     * @param $url : 商業司 API 網址
     * @return array|mixed
     * @throws Exception
     */
    public function get_business_info($url) {
        $rs  	= curl_get($url);
        $result = json_decode($rs,TRUE);
        if($rs === FALSE)
            throw new Exception("商業司連線失敗",CONNECTION_ERROR);
        if(empty($rs))
            throw new Exception("此公司或商行不存在",COMPANY_NOT_EXIST);
        if(empty($result) || !is_array($result))
            throw new Exception("商業司API回應異常",RESPONSE_ERROR);

        return $result;
    }

    /**
     * 取得商業司的公司基本資訊
     * @param $account_no : 商業統一編號
     * @return array|mixed
     * @throws Exception
     */
    public function get_company_info($account_no) {
        $url 	= $this->url.'5F64D864-61CB-4D0D-8AD9-492047CC1EA6?$format=json&$filter=Business_Accounting_NO%20eq%20'.$account_no.'&$skip=0&$top=50';
        return $this->get_business_info($url);
    }

    /**
     * 取得商業司的商行基本資訊
     * @param $account_no : 商業統一編號
     * @return array|mixed
     * @throws Exception
     */
    public function get_president_info($account_no) {
        $url 	= $this->url.'426D5542-5F05-43EB-83F9-F1300F14E1F1?$format=json&$filter=President_No%20eq%20'.$account_no.'&$skip=0&$top=50';
        return $this->get_business_info($url);
    }

    /**
     * 取得商業司的商行基本資訊
     * @param $account_no : 商業統一編號
     * @param $agency_no : 商業申登機關代碼
     * @return array|mixed
     * @throws Exception
     */
    public function get_president_with_agency_info($account_no, $agency_no) {
        $url 	= $this->url.'7E6AFA72-AD6A-46D3-8681-ED77951D912D?$format=json&$filter=President_No%20eq%20'.$account_no.'%20and%20Agency%20eq%20'.$agency_no.'&$skip=0&$top=50';
        return $this->get_business_info($url);
    }

    /**
     * 確認公司負責人是否符合
     * @param $account_no : 商業統一編號
     * @param $name : 負責人姓名
     * @return bool
     * @throws Exception
     */
    public function is_company_responsible($account_no, $name): bool
    {
        $rs = $this->get_company_info($account_no);
        $rs = reset($rs);
        if(!isset($rs['Business_Accounting_NO']) || !isset($rs['Responsible_Name']) || !isset($rs['Company_Status_Desc']))
            throw new Exception("商業司API回應異常",RESPONSE_ERROR);
        if(!$this->business_is_incorporation($rs['Company_Status_Desc']))
            throw new Exception("公司不是正常設立狀態",NOT_INCORPORATION);
        
        // 因爲部分公司負責人名稱會包含空白，故做相應處理
        $rs['Responsible_Name'] = str_replace(' ', '', $rs['Responsible_Name']);
        $rs['Responsible_Name'] = str_replace('　', '', $rs['Responsible_Name']);

        $name = str_replace(' ', '', $name);
        $name = str_replace('　', '', $name);

        // 因為部分公司負責人名稱會包含英文，故改用 str_contains
        return str_contains($rs['Responsible_Name'], $name);
    }

    /**
     * 確認商行負責人是否符合
     * @param $account_no : 商業統一編號
     * @param $name : 負責人姓名
     * @return bool
     * @throws Exception
     */
    public function is_president_responsible($account_no, $name): bool
    {
        $president_info = $this->get_president_info($account_no);
        $president_info = reset($president_info);
        if(!isset($president_info['President_No']) || !isset($president_info['Agency']))
            throw new Exception("商業司API回應異常",RESPONSE_ERROR);

        $president_info = $this->get_president_with_agency_info($account_no, $president_info['Agency']);
        $president_info = reset($president_info);
        if(!isset($president_info['President_No']) || !isset($president_info['Responsible_Name']) ||
            !isset($president_info['Business_Current_Status_Desc']) || !isset($president_info['Agency']))
            throw new Exception("商業司API回應異常",RESPONSE_ERROR);

        if(!$this->business_is_incorporation($president_info['Business_Current_Status_Desc']))
            throw new Exception("公司不是正常設立狀態",NOT_INCORPORATION);
        return $name === $president_info['Responsible_Name'];
    }

    /**
     * 確認統一編號的負責人姓名
     * @throws Exception
     */
    public function is_business_responsible($account_no, $name) : bool {
        $is_responsible = FALSE;

        if(strlen($account_no)==8) {
            $is_company = TRUE;

            try{
                $is_responsible = $this->is_company_responsible($account_no, $name);
            }catch (Exception $e){
                // 由於統一編號可能不是公司而是商行，故查無公司資料時不回傳 Exception
                if(!in_array($e->getCode(), [RESPONSE_ERROR, COMPANY_NOT_EXIST]))
                    throw $e;
                $is_company = FALSE;
            }

            if(!$is_company) {
                try{
                    $is_responsible = $this->is_president_responsible($account_no, $name);
                }catch (Exception $e){
                    throw $e;
                }
            }
        }else{
            throw new Exception("統一編號長度非8碼",TAX_ID_LENGTH_ERROR);
        }
        return $is_responsible;
    }

    public function business_is_incorporation($status_name): bool
    {
        switch ($status_name) {
            case '核准設立':
                return TRUE;
            case '核准登記':
                return TRUE;
            default:
                return FALSE;
        }
    }

    /**
     * 取得法人基本資料 (會同時檢查公司與商行的資料)
     * @param $account_no
     * @return array|string[]
     */
    public function get_company_president_info($account_no)
    {
        $result = [
            'company_name' => '', // 公司名稱
            'responsible_name' => '', // 負責人名稱
            'company_last_change_date' => '', // 戳章日期 (最後核准變更日期)
            'company_capital' => '', // 實收資本額
            'company_address' => '', // 公司所在地
        ];

        // 公司資料
        $company_info = $this->account_info($account_no);
        if ( ! empty($company_info))
        {
            empty($company_info['Company_Name']) ?: $result['company_name'] = $company_info['Company_Name'];
            empty($company_info['Responsible_Name']) ?: $result['responsible_name'] = $company_info['Responsible_Name'];
            empty($company_info['Change_Of_Approval_Data']) ?: $result['company_last_change_date'] = $company_info['Change_Of_Approval_Data'];
            empty($company_info['Paid_In_Capital_Amount']) ?: $result['company_capital'] = $company_info['Paid_In_Capital_Amount'];
            empty($company_info['Company_Location']) ?: $result['company_address'] = $company_info['Company_Location'];
            goto END;
        }

        // 商行資料
        $president_info = $this->account_info_businesss($account_no);
        if ( ! empty($president_info))
        {
            empty($president_info['Business_Name']) ?: $result['company_name'] = $president_info['Business_Name'];
            empty($president_info['Responsible_Name']) ?: $result['responsible_name'] = $president_info['Responsible_Name'];
            empty($president_info['Business_Last_Change_Date']) ?: $result['company_last_change_date'] = $president_info['Business_Last_Change_Date'];
            empty($president_info['Business_Register_Funds']) ?: $result['company_capital'] = $president_info['Business_Register_Funds'];
            empty($president_info['Business_Address']) ?: $result['company_address'] = $president_info['Business_Address'];
        }

        END:
        return $result;
    }
}

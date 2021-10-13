<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Findbiz_lib
{
    function __construct()
    {
        $this->CI = &get_instance();
        $sipServerPort = '9998';
        $this->scraperUrl = "http://" . getenv('GRACULA_IP') . ":{$sipServerPort}/scraper/api/v1.0/";
    }

    /**
     * [requestFindBizData 商業司爬蟲]
     * @param  string $businessid [統一編號]
     * @return object $response   [發送結果]
     *(
     * [status] => 200|204|400|201(SUCCESS|NO_CONTENT|BAD_REQUEST|TASK_REPEAT)
     *)
     *
     */
    public function requestFindBizData($businessid)
    {
        if(!$businessid) {
            return false;
        }

        $url = $this->scraperUrl  . "findbiz/{$businessid}/data";
        $result = curl_get($url,['']);
        $response = json_decode($result);

        if (!$result || !isset($response->status) || $response->status != 200) {
            return;
        }

        return $response;
    }

    /**
     * [getResultByBusinessId 統一編號爬蟲結果查詢]
     * @param  string $businessid [統一編號]
     * @return object $response   [爬蟲結果]
     * (
     *  [status] => 200|204|400|201(SUCCESS|NO_CONTENT|BAD_REQUEST|TASK_REPEAT)
     *  [response] =>
     *    (
     *     [result] =>
     *      (
     *       [reference] => 統一編號
     *       [firstPage] =>
     *         (
     *          [firstPageCompanyInfo] =>
     *            (
     *             公司基本資料
     *            )
     *          [firstPageDirectorInfo] =>
     *            (
     *             董監事資料
     *            )
     *         )
     *       [history] =>
     *         (
     *          歷史資料
     *         )
     *       [createdAt] => 創建時間
     *       [updatedAt] => 更新時間
     *      )
     *    )
     * )
     */
    public function getResultByBusinessId($businessid)
    {
      $url = $this->scraperUrl  . "findbiz/{$businessid}/data";
  		$result = curl_get($url);
  		$response = json_decode($result);

  		if (!$result || !isset($response->status) || $response->status != 200) {
  			return;
  		}

  		return $response;
    }

    /**
     * [getFindBizStatus 商業司爬蟲進度]
     * @param  string $businessid [統一編號]
     * @return object $response   [發送結果]
     *(
     * [status] => 200|204|400|201(SUCCESS|NO_CONTENT|BAD_REQUEST|TASK_REPEAT)
     * [result] =>
     *   (
     *    [reference] =>  統一編號
     *    [status] => not_found|failure|finished|requested|started(沒有資料|失敗|完成|已發送|正在執行)
     *    [createdAt] => 創建時間
     *    [updatedAt] => 更新時間
     *   )
     *)
     *
     */
    public function getFindBizStatus($businessid)
    {
      $url = $this->scraperUrl  . "findbiz/{$businessid}/status";
  		$result = curl_get($url);
  		$response = json_decode($result);

  		if (!$result || !isset($response->status) || $response->status != 200) {
  			return;
  		}

  		return $response;
    }

    /**
     * [searchEachTermOwner 尋找歷任登記負責人]
     * @param  array  $company_info [爬蟲結果]
     * @return array  $response     [歷任登記負責人]
     * (
     *  [擔任起日] => 姓名
     *  ...
     * )
     */
    public function searchEachTermOwner($company_info=[]){
      $response = [];
      $allow_date = '';
      $owner_name = '';

      $company_info = ! empty($company_info->response->result) ? $company_info->response->result : [];
      if(!$company_info){
        return false;
      }

      $owner_name = isset($company_info->firstPage->firstPageCompanyInfo->代表人姓名) ? $company_info->firstPage->firstPageCompanyInfo->代表人姓名 : '';
      $allow_date = isset($company_info->firstPage->firstPageCompanyInfo->最後核准變更日期) ? $company_info->firstPage->firstPageCompanyInfo->最後核准變更日期 : '';

      $history_info = ! empty($company_info->history) ? $company_info->history : [];
      if($history_info){
        foreach ($history_info as $value) {
          $last_allow_date = isset($value->核准設立日期) ? $value->核准設立日期 : '';
          $last_owner_name = isset($value->代表人姓名) ? $value->代表人姓名 : '';
          if($last_owner_name != $owner_name){
            $response = array_merge($response,[$allow_date => $owner_name]);
          }
        }
        $response = array_merge($response,[$last_allow_date => $last_owner_name]);
      }else{
        $response = array_merge($response,[$allow_date => $owner_name]);
      }

      return $response;
    }

    /**
     * [getFindBizImage description]
     * @param  string $businessid [公司統一編號]
     * @param  string $user_id [公司使用者編號]
     * @return string $url   [商業司截圖 S3連結]
     */
    public function getFindBizImage($businessid = '',$user_id = ''){
        if(empty($businessid) || empty($user_id)){
            return false;
        }

        $response = '';
        $url = $this->scraperUrl  . "findbiz/{$businessid}/screenshot";
    	$result = curl_get($url,'');

        if(!$result){
            return false;
        }
        $result_array =json_decode($result,true);

        if(!is_array($result_array) || ! isset($result_array['response']['result'])){
            return false;
        }
        $image_info = base64_decode($result_array['response']['result']);
        $this->CI->load->library('S3_upload');
        $url = $this->CI->s3_upload->image_by_data($image_info,'',$user_id,'BizImage');

        return $url;
    }
}

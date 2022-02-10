<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Black_list extends Admin_rest_api_controller
{

    public $user_info;
    public $brookesia_url;

    public function __construct()
    {
        parent::__construct();

        $brookesiaPort = '9453';
        $this->brookesia_url = "http://" . getenv('GRACULA_IP') . ":{$brookesiaPort}/brookesia/api/v1.0/";

        $CI =& get_instance();
        $CI->load->library('session');
        $admin_info = AUTHORIZATION::getAdminInfoByToken(
            $CI->session->userdata(SESSION_APP_ADMIN_INFO)
        );
        $this->login_info = empty($admin_info->id) ? false : $admin_info;
    }

	public function get_anti_list_get(){
		$input = $this->input->get(NULL, TRUE);
		$page = isset($input['page']) ? $input['page'] : 1;
		$this->response(json_decode('{"results":[{"id":"61f359dba6b8800620e2bc04","userId":47160,"typeId":"020101","ruleId":"5fb487f93d9e10b523f2fb48","createdAt":1611629063,"updatedAt":1614154554,"isResultOfRelatedUser":false,"risk":"中","block":"封鎖三個月","mainDescription":"【緊急聯絡人手機】重複出現數次","description":"緊急聯絡人手機號碼重複出現3次以上","emergencyPhone":"0988255555","repeatedCount":8,"relatedUserId":[23004,42829,47103,47105,47110,47111,47112,47118],"index":["手機"]},{"id":"61f359dba6b8800620e2bc04","userId":47160,"typeId":"020201","ruleId":"5fb487f93d9e10b523f2fb40","createdAt":1611629063,"updatedAt":1614154554,"isResultOfRelatedUser":true,"risk":"中","block":"封鎖三個月","mainDescription":"【緊急聯絡人手機】與其他用戶【緊急連絡人手機】相同","description":"緊急聯絡人手機與其他用戶緊急聯絡人手機相同，且關係同為父/母","emergencyPhone":"0988255555","relatedUsers":[{"relatedUserId":23004,"relatedKey":"關係","relatedValue":"父母"},{"relatedUserId":42829,"relatedKey":"關係","relatedValue":"父母"},{"relatedUserId":47103,"relatedKey":"關係","relatedValue":"父母"},{"relatedUserId":47105,"relatedKey":"關係","relatedValue":"父母"},{"relatedUserId":47110,"relatedKey":"關係","relatedValue":"父母"},{"relatedUserId":47111,"relatedKey":"關係","relatedValue":"父母"},{"relatedUserId":47112,"relatedKey":"關係","relatedValue":"父母"},{"relatedUserId":47118,"relatedKey":"關係","relatedValue":"父母"}],"index":["手機"]},{"id":"61f359dba6b8800620e2bc04","userId":47160,"typeId":"020301","ruleId":"5fb487f93d9e10b523f2fb4c","createdAt":1611629063,"updatedAt":1614154554,"isResultOfRelatedUser":false,"risk":"中","block":"封鎖三個月","mainDescription":"【緊急聯絡人手機】重複出現，且【緊急聯絡人姓名】不相符","description":"【緊急聯絡人手機】重複出現，且【緊急聯絡人姓名】不相符","emergencyPhone":"0988255555","relatedUserInfo":[{"relatedUserId":23004,"emergencyName":"許雲府"},{"relatedUserId":42829,"emergencyName":"我想"},{"relatedUserId":47103,"emergencyName":"熊熊"},{"relatedUserId":47105,"emergencyName":"許雲熊"},{"relatedUserId":47110,"emergencyName":"雄熊"},{"relatedUserId":47111,"emergencyName":"熊雲許"},{"relatedUserId":47112,"emergencyName":"許熊熊"},{"relatedUserId":47118,"emergencyName":"許阿熊"}],"index":["手機"]},{"id":"61f359dba6b8800620e2bc04","userId":47105,"typeId":"020101","ruleId":"5fb487f93d9e10b523f2fb48","createdAt":1611629063,"updatedAt":1614154552,"isResultOfRelatedUser":false,"risk":"中","block":"封鎖三個月","mainDescription":"【緊急聯絡人手機】重複出現數次","description":"緊急聯絡人手機號碼重複出現3次以上","emergencyPhone":"0988255555","repeatedCount":8,"relatedUserId":[23004,42829,47103,47110,47111,47112,47118,47160],"index":["手機"]},{"id":"61f359dba6b8800620e2bc04","userId":47105,"typeId":"020201","ruleId":"5fb487f93d9e10b523f2fb40","createdAt":1611629063,"updatedAt":1614154552,"isResultOfRelatedUser":true,"risk":"中","block":"封鎖三個月","mainDescription":"【緊急聯絡人手機】與其他用戶【緊急連絡人手機】相同","description":"緊急聯絡人手機與其他用戶緊急聯絡人手機相同，且關係同為父/母","emergencyPhone":"0988255555","relatedUsers":[{"relatedUserId":23004,"relatedKey":"關係","relatedValue":"父母"},{"relatedUserId":42829,"relatedKey":"關係","relatedValue":"父母"},{"relatedUserId":47103,"relatedKey":"關係","relatedValue":"父母"},{"relatedUserId":47110,"relatedKey":"關係","relatedValue":"父母"},{"relatedUserId":47111,"relatedKey":"關係","relatedValue":"父母"},{"relatedUserId":47112,"relatedKey":"關係","relatedValue":"父母"},{"relatedUserId":47118,"relatedKey":"關係","relatedValue":"父母"},{"relatedUserId":47160,"relatedKey":"關係","relatedValue":"父母"}],"index":["手機"]},{"id":"61f359dba6b8800620e2bc04","userId":47105,"typeId":"020301","ruleId":"5fb487f93d9e10b523f2fb4c","createdAt":1611629063,"updatedAt":1614154553,"isResultOfRelatedUser":false,"risk":"中","block":"封鎖三個月","mainDescription":"【緊急聯絡人手機】重複出現，且【緊急聯絡人姓名】不相符","description":"【緊急聯絡人手機】重複出現，且【緊急聯絡人姓名】不相符","emergencyPhone":"0988255555","relatedUserInfo":[{"relatedUserId":23004,"emergencyName":"許雲府"},{"relatedUserId":42829,"emergencyName":"我想"},{"relatedUserId":47103,"emergencyName":"熊熊"},{"relatedUserId":47110,"emergencyName":"雄熊"},{"relatedUserId":47111,"emergencyName":"熊雲許"},{"relatedUserId":47112,"emergencyName":"許熊熊"},{"relatedUserId":47118,"emergencyName":"許阿熊"},{"relatedUserId":47160,"emergencyName":"俎法強"}],"index":["手機"]},{"id":"61f359dba6b8800620e2bc04","userId":47110,"typeId":"020101","ruleId":"5fb487f93d9e10b523f2fb48","createdAt":1611629063,"updatedAt":1614154553,"isResultOfRelatedUser":false,"risk":"中","block":"封鎖三個月","mainDescription":"【緊急聯絡人手機】重複出現數次","description":"緊急聯絡人手機號碼重複出現3次以上","emergencyPhone":"0988255555","repeatedCount":8,"relatedUserId":[23004,42829,47103,47105,47111,47112,47118,47160],"index":["手機"]},{"id":"61f359dba6b8800620e2bc04","userId":47110,"typeId":"020201","ruleId":"5fb487f93d9e10b523f2fb40","createdAt":1611629063,"updatedAt":1614154553,"isResultOfRelatedUser":true,"risk":"中","block":"封鎖三個月","mainDescription":"【緊急聯絡人手機】與其他用戶【緊急連絡人手機】相同","description":"緊急聯絡人手機與其他用戶緊急聯絡人手機相同，且關係同為父/母","emergencyPhone":"0988255555","relatedUsers":[{"relatedUserId":23004,"relatedKey":"關係","relatedValue":"父母"},{"relatedUserId":42829,"relatedKey":"關係","relatedValue":"父母"},{"relatedUserId":47103,"relatedKey":"關係","relatedValue":"父母"},{"relatedUserId":47105,"relatedKey":"關係","relatedValue":"父母"},{"relatedUserId":47111,"relatedKey":"關係","relatedValue":"父母"},{"relatedUserId":47112,"relatedKey":"關係","relatedValue":"父母"},{"relatedUserId":47118,"relatedKey":"關係","relatedValue":"父母"},{"relatedUserId":47160,"relatedKey":"關係","relatedValue":"父母"}],"index":["手機"]},{"id":"61f359dba6b8800620e2bc04","userId":47110,"typeId":"020301","ruleId":"5fb487f93d9e10b523f2fb4c","createdAt":1611629063,"updatedAt":1614154553,"isResultOfRelatedUser":false,"risk":"中","block":"封鎖三個月","mainDescription":"【緊急聯絡人手機】重複出現，且【緊急聯絡人姓名】不相符","description":"【緊急聯絡人手機】重複出現，且【緊急聯絡人姓名】不相符","emergencyPhone":"0988255555","relatedUserInfo":[{"relatedUserId":23004,"emergencyName":"許雲府"},{"relatedUserId":42829,"emergencyName":"我想"},{"relatedUserId":47103,"emergencyName":"熊熊"},{"relatedUserId":47105,"emergencyName":"許雲熊"},{"relatedUserId":47111,"emergencyName":"熊雲許"},{"relatedUserId":47112,"emergencyName":"許熊熊"},{"relatedUserId":47118,"emergencyName":"許阿熊"},{"relatedUserId":47160,"emergencyName":"俎法強"}],"index":["手機"]},{"id":"61f359dba6b8800620e2bc04","userId":47111,"typeId":"020101","ruleId":"5fb487f93d9e10b523f2fb48","createdAt":1611629064,"updatedAt":1614154553,"isResultOfRelatedUser":false,"risk":"中","block":"封鎖三個月","mainDescription":"【緊急聯絡人手機】重複出現數次","description":"緊急聯絡人手機號碼重複出現3次以上","emergencyPhone":"0988255555","repeatedCount":8,"relatedUserId":[23004,42829,47103,47105,47110,47112,47118,47160],"index":["手機"]}],"pagination":{"page":' . $page . ',"last_page":28,"count":10}}'));
	}

	public function get_new_tree_get(){
		$result = curl_get('http://35.77.82.16:9453/brookesia/api/v1.0/rule/indexMap');
		$json = json_decode($result, TRUE);
		$this->response($json['response']);
	}

	public function new_risk_post(){
		$this->response(json_decode('{"status":200,"message":"ok"}'));
	}

	public function get_option_get()
	{
		$this->response( json_decode('{"results":{"risk":["追蹤分析","低","中","高","拒絕"],"index":["實名認證","學生","社群","金融","手機","普匯","工作","資料庫","手動新增"],"block_text":["轉二審","封鎖一個月","封鎖三個月","封鎖六個月","永久封鎖"],"block_rule":["反詐欺規則","其他(人為加入)","授信政策"]}}'));
	}

    /**
     * 取得黑名單資料
     * 
     * @created_at          2022-01-13
     * @created_by          Frankie
     */
    public function get_all_block_users_get()
    {
        $input = $this->input->get(NULL, TRUE);

        $userId = isset($input['userId']) ? urlencode($input['userId']) : '';
        $blockRule = isset($input['blockRule']) ? urlencode($input['blockRule']) : '';
        $blockTimeText = isset($input['blockTimeText']) ? urlencode($input['blockTimeText']) : '';

        $url = $this->brookesia_url . 'blockUser/getAllBlockUsers'
        . '?userId=' . $userId
        . '&blockRule=' . $blockRule
        . '&blockTimeText=' . $blockTimeText
        ;

        $result = curl_get($url);
        $json = json_decode($result, TRUE);

        if ( ! $result)
        {
            $error = [
                'message' => '黑名單系統無回應，請洽工程師。'
            ];
            $this->response($error);
        }

        if ($json['status'] == 204)
        {
            $this->response(['results' => [] ]);
        }

        $this->response($json['response']);
    }

    /**
     * 新增黑名單資料
     *
     * @created_at          2022-01-13
     * @created_by          Frankie
     */
    public function block_add_post()
    {
        $input = json_decode($this->security->xss_clean($this->input->raw_input_stream), TRUE);
        $updatedBy = $this->login_info->id;

        $url = $this->brookesia_url . 'blockUser/add';
        $payload = [
            'userId'           => $input['userId'],
            'updatedBy'        => $updatedBy,
            'blockRule'        => $input['blockRule'],
            'blockDescription' => $input['blockDescription'],
            'blockRemark'      => $input['blockRemark'],
            'blockRisk'        => $input['blockRisk'],
            'blockTimeText'    => $input['blockTimeText'],
        ];
        $result = curl_get($url, $payload);

        if ( ! $result)
        {
            $error = [
                'response' => [
                    'message' => '黑名單系統無回應，請洽工程師。'
                ]
            ];
            $this->response($error);
        }

        $json = json_decode($result, TRUE);
        $this->response($json);

    }

    /**
     * 更新黑名單資料
     *
     * @created_at          2022-01-13
     * @created_by          Frankie
     */
    public function block_update_post()
    {
        $input = json_decode($this->security->xss_clean($this->input->raw_input_stream), TRUE);
        $updatedBy = $this->login_info->id;

        $url = $this->brookesia_url . 'blockUser/update';
        $payload = [
            'userId'           => $input['userId'],
            'updatedBy'        => $updatedBy,
            'blockRemark'        => $input['blockRemark'],
            'blockTimeText' => $input['blockTimeText']
        ];
        $result = curl_get($url, $payload);

        if ( ! $result)
        {
            $error = [
                'message' => '黑名單系統無回應，請洽工程師。'
            ];
            $this->response($error);
        }

        $json = json_decode($result, TRUE);
        $this->response($json);

    }

    /**
     * 移除黑名單(使失效)
     *
     * @created_at          2022-01-13
     * @created_by          Frankie
     */
    public function block_disable_post()
    {
        $input = json_decode($this->security->xss_clean($this->input->raw_input_stream), TRUE);
        $updatedBy = $this->login_info->id;

        $url = $this->brookesia_url . 'blockUser/disable';
        $payload = [
            'userId'           => $input['userId'],
            'updatedBy'        => $updatedBy,
            'blockRemark'        => $input['blockRemark']
        ];
        $result = curl_get($url, $payload);

        if ( ! $result)
        {
            $error = [
                'message' => '黑名單系統無回應，請洽工程師。'
            ];
            $this->response($error);
        }

        $json = json_decode($result, TRUE);
        $this->response($json);

    }

    /**
     * 重新加入黑名單(使生效)
     *
     * @created_at          2022-01-13
     * @created_by          Frankie
     */
    public function block_enable_post()
    {
        $input = json_decode($this->security->xss_clean($this->input->raw_input_stream), TRUE);
        $updatedBy = $this->login_info->id;

        $url = $this->brookesia_url . 'blockUser/enable';
        $payload = [
            'userId'           => $input['userId'],
            'updatedBy'        => $updatedBy,
            'blockRemark'        => $input['blockRemark']
        ];
        $result = curl_get($url, $payload);

        if ( ! $result)
        {
            $error = [
                'message' => '黑名單系統無回應，請洽工程師。'
            ];
            $this->response($error);
        }

        $json = json_decode($result, TRUE);
        $this->response($json);

    }

}

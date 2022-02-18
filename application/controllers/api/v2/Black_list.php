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

	public function block_history_get()
    {
		$this->response(json_decode('{"results":[{"userId":"123","blockRule":"aaaa","blockDescription":"bbb","blockRemark":"yrdy","blockRisk":"sdf","blockLogAction":"trd","history":[{"userId":"123","blockRule":"aaaa","blockDescription":"bbb","blockRemark":"yrdy","blockRisk":"sdf","blockLogAction":"trd","updatedAt":"1645154829"}],"updatedAt":"1632514699"},{"userId":"123","blockRule":"aaaa","blockDescription":"bbb","blockRemark":"yrdy","blockRisk":"sdf","blockLogAction":"trd","history":[],"updatedAt":"16451548819"}]}'));
	}

}

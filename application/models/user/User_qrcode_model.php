<?php

class user_qrcode_model extends MY_Model
{
	public $_table = 'user_qrcode';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );
	public $status_list   = array(
		0 =>	'停用',
		1 =>	'有效'
	);
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('default',TRUE);
 	}
	
	protected function before_data_c($data)
    {
        $data['created_at'] = $data['updated_at'] = date('Y-m-d H:i:s');
        $data['created_ip'] = $data['updated_ip'] = get_ip();
        return $data;
    } 	
	
	protected function before_data_u($data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_ip'] = get_ip();
        return $data;
    }

    /**
     * 取得被推薦之使用者與推薦碼相關資訊
     * @param array $qrcode_where
     * @param false $returnSQL
     * @return mixed
     */
    public function getRegisteredUserByPromoteCode(array $qrcode_where, string $startTime, string $endTime, bool $returnSQL=FALSE) {
        $this->_database->select('*')
            ->from("`p2p_user`.`user_qrcode`");
        if(!empty($qrcode_where))
            $this->_set_where([$qrcode_where]);
        $subQuery = $this->_database->get_compiled_select('', TRUE);

        $this->_database
            ->select("u.id AS user_id, u.app_status, u.app_investor_status, uq.promote_code, uq.settings, uq.start_time, uq.end_time, DATE_ADD(DATE_FORMAT(FROM_UNIXTIME(u.created_at),'%Y-%m-%d %H:%i:%s'), INTERVAL 8 HOUR) as created_at")
            ->from('`p2p_user`.`users` AS `u`')
            ->where("DATE_ADD(DATE_FORMAT(FROM_UNIXTIME(u.created_at), '%Y-%m-%d %H:%i:%s'), INTERVAL 8 HOUR) >= uq.start_time")
            ->where("DATE_ADD(DATE_FORMAT(FROM_UNIXTIME(u.created_at), '%Y-%m-%d %H:%i:%s'), INTERVAL 8 HOUR) <= uq.end_time")
            ->join("($subQuery) as `uq`", "`u`.`promote_code` = `uq`.`promote_code`");

        $fullQuery = $this->_database->get_compiled_select('', TRUE);
        $this->_database
            ->select('r.*')
            ->from("($fullQuery) AS `r`");
        if($startTime!='')
            $this->_database->where("r.created_at >=",  $startTime);
        if($endTime!='')
            $this->_database->where("r.created_at <=",  $endTime);

        if($returnSQL)
            return $this->_database->get_compiled_select('', TRUE);
        return $this->_database->get()->result_array();
    }

    /**
     * 取得使用推薦碼初貸成功的數量
     * @param array $qrcode_where 推薦碼篩選條件
     * @param array $productIdList 產品編號列表
     * @param string $startTime 篩選起始時間
     * @param string $endTime 篩選結束時間
     * @return mixed
     */
    public function getLoanedCount(array $qrcode_where, array $productIdList, string $startTime='', string $endTime='') {
        $subQuery = $this->getRegisteredUserByPromoteCode($qrcode_where, '','', TRUE);

        $this->_database
            ->select('r.promote_code, r.settings, t.id, t.user_id, t.product_id, t.status, t.loan_amount, t.loan_date')
            ->from('`p2p_loan`.`targets` AS `t`')
            ->join("($subQuery) as `r`", "`t`.`user_id` = `r`.`user_id`")
            ->where_in("t.status", [TARGET_REPAYMENTING, TARGET_REPAYMENTED])
            ->where_in("t.product_id", $productIdList)
            ->where("t.loan_date >= DATE_FORMAT(`r`.`start_time`, '%Y-%m-%d')")
            ->where("t.loan_date <= DATE_FORMAT(`r`.`end_time`, '%Y-%m-%d')")
            ->group_by('t.user_id');
        $fullQuery = $this->_database->get_compiled_select('', TRUE);

        $this->_database
            ->select('r.*')
            ->from("($fullQuery) AS `r`");
        if($startTime!='')
            $this->_database->where("r.loan_date >=",  $startTime);
        if($endTime!='')
            $this->_database->where("r.loan_date <=",  $endTime);

        return $this->_database->get()->result_array();
    }

    /**
     * 取得用戶資料與推薦碼相關資訊
     * @param array $user_where
     * @param array $qrcode_where
     * @return mixed
     */
    public function getUserQrcodeInfo(array $user_where=[], array $qrcode_where=[]) {
        $this->_database->select('*')
            ->from("`p2p_user`.`users`");
        if(!empty($user_where))
            $this->_set_where([$user_where]);
        $subQuery = $this->_database->get_compiled_select('', TRUE);

        $this->_database
            ->select('u.name, uq.*')
            ->from('`p2p_user`.`user_qrcode` AS `uq`')
            ->join("($subQuery) as `u`", "`u`.`id` = `uq`.`user_id`");
        if(!empty($qrcode_where)) {
            $qrcode_where = array_combine(addPrefixToArray(array_keys($qrcode_where), "uq."), array_values($qrcode_where));
            $this->_set_where([$qrcode_where]);
        }

        return $this->_database->get()->result_array();
    }

    /**
     * 取得推薦碼結帳的相關資訊
     * @param array $qrcode_where
     * @return mixed
     */
    public function getQrcodeRewardInfo(array $qrcode_where=[]) {
        $this->_database->select('*')
            ->from("`p2p_user`.`user_qrcode`");
        if(!empty($qrcode_where))
            $this->_set_where([$qrcode_where]);
        $subQuery = $this->_database->get_compiled_select('', TRUE);

        $this->_database
            ->select('u.id, u.promote_code, u.handle_time, u.settlementing, ur.user_qrcode_id, ur.start_time, ur.end_time, ur.amount, ur.json_data')
            ->from('`p2p_transaction`.`qrcode_reward` AS `ur`')
            ->join("($subQuery) as `u`", "`u`.`id` = `ur`.`user_qrcode_id`", 'right')
            ->order_by("ur.end_time", "DESC");
        $subQuery2 = $this->_database->get_compiled_select('', TRUE);

        $this->_database
            ->select('r.*')
            ->from("($subQuery2) as `r`")
            ->group_by("r.promote_code");

        return $this->_database->get()->result_array();
    }

    /**
     * 設定推薦碼的使用狀態 (成功:回傳推薦碼的物件/不成功:回傳空陣列)
     * @param $promoteCode
     * @param $old_status
     * @param $new_status
     * @return array|stdClass
     */
    public function setUserPromoteLock($promoteCode, $old_status, $new_status) {
        $conditions = [
            'promote_code'  => $promoteCode,
            'settlementing' => $old_status,
            'status'        => 1,
        ];

        $this->db->trans_start();
        $this->db->select_for_update('*')->where($conditions);
        $userPromoteCode = $this->db->get($this->_table)->result();

        if(is_array($userPromoteCode) && !empty($userPromoteCode)) {
            $userPromoteCode = $userPromoteCode[0];
            $result = $this->db->where(['id' => $userPromoteCode->id])
                ->set(['settlementing' => $new_status])
                ->update($this->_table);

            // 更新失敗需回傳 empty array
            if($this->db->affected_rows() > 0) {
                $userPromoteCode->settlementing = $new_status;
            }else{
                $userPromoteCode = [];
            }
        }
        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE) {

            $userPromoteCode = [];
        }

        return $userPromoteCode;
    }
}

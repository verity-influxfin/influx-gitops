<?php

class user_qrcode_model extends MY_Model
{
    public $_table = 'user_qrcode';
    public $before_create = array('before_data_c');
    public $before_update = array('before_data_u');
    public $status_list = array(
        PROMOTE_STATUS_DISABLED => '停用',
        PROMOTE_STATUS_AVAILABLE => '啟用'
    );

    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->load->database('default', TRUE);
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
     * @param string $startTime
     * @param string $endTime
     * @param false $returnSQL
     * @return mixed
     */
    public function getRegisteredUserByPromoteCode(array $qrcode_where, string $startTime, string $endTime, bool $returnSQL = FALSE)
    {
        $this->_database->select('*')
            ->from("`p2p_user`.`user_qrcode`");
        if ( ! empty($qrcode_where))
            $this->_set_where([$qrcode_where]);
        $subQuery = $this->_database->get_compiled_select('', TRUE);

        $this->_database
            ->select("uq.id AS user_qrcode_id, uq.alias AS alias, u.id AS user_id, u.app_status, u.app_investor_status, uq.promote_code, uq.settings, uq.start_time, uq.end_time, DATE_ADD(DATE_FORMAT(FROM_UNIXTIME(u.created_at),'%Y-%m-%d %H:%i:%s'), INTERVAL 8 HOUR) as created_at")
            ->from('`p2p_user`.`users` AS `u`')
            ->where("DATE_ADD(DATE_FORMAT(FROM_UNIXTIME(u.created_at), '%Y-%m-%d %H:%i:%s'), INTERVAL 8 HOUR) >= uq.start_time")
            ->where("DATE_ADD(DATE_FORMAT(FROM_UNIXTIME(u.created_at), '%Y-%m-%d %H:%i:%s'), INTERVAL 8 HOUR) <= uq.end_time")
            ->join("({$subQuery}) as `uq`", "`u`.`promote_code` = `uq`.`promote_code`");

        $fullQuery = $this->_database->get_compiled_select('', TRUE);
        $this->_database
            ->select('r.*')
            ->from("({$fullQuery}) AS `r`");
        if ($startTime != '')
            $this->_database->where("r.created_at >=", $startTime);
        if ($endTime != '')
            $this->_database->where("r.created_at <", $endTime);

        if ($returnSQL)
            return $this->_database->get_compiled_select('', TRUE);
        return $this->_database->get()->result_array();
    }

    /**
     * 取得使用推薦碼初貸成功的數量
     * @param array $qrcodeWhere 推薦碼篩選條件
     * @param array $productIdList 產品編號列表
     * @param string $startTime 篩選起始時間
     * @param string $endTime 篩選結束時間
     * @param bool $returnSQL 是否回傳SQL語句
     * @return mixed
     */
    public function getLoanedCount(array $qrcodeWhere, array $productIdList, array $statusList, string $startTime = '', string $endTime = '', bool $returnSQL = FALSE)
    {
        $this->load->model('user/qrcode_setting_model');
        $subQuery = $this->getRegisteredUserByPromoteCode($qrcodeWhere, '', '', TRUE);

        $this->_database
            ->select('r.user_qrcode_id, r.promote_code, r.settings, t.id, t.user_id, t.product_id, t.status, t.loan_amount, t.loan_date')
            ->from('`p2p_loan`.`targets` AS `t`')
            ->join("({$subQuery}) as `r`", "`t`.`user_id` = `r`.`user_id`")
            ->where_in("t.status", $statusList)
            ->where_in("t.product_id", $productIdList)
            ->where("t.loan_date >= DATE_FORMAT(`r`.`start_time`, '%Y-%m-%d')")
            ->where("t.loan_date <= DATE_FORMAT(`r`.`end_time`, '%Y-%m-%d')")
            ->group_by('t.user_id');

        // 特約商合約之新戶定義：透過QR code完成會員註冊時起算半年內之第三人
        $this->_database
            ->group_start()
            ->where('r.alias != ', $this->qrcode_setting_model->appointedCaseAliasName)
            ->or_group_start()
            ->where('r.alias', $this->qrcode_setting_model->appointedCaseAliasName)
            ->where("t.loan_date <= DATE_ADD(DATE_FORMAT(r.created_at, '%Y-%m-%d'), INTERVAL 6 MONTH)")
            ->group_end()
            ->group_end();

        $fullQuery = $this->_database->get_compiled_select('', TRUE);

        $this->_database
            ->select('r.*')
            ->from("({$fullQuery}) AS `r`");
        if ($startTime != '')
            $this->_database->where("r.loan_date >=", $startTime);
        if ($endTime != '')
            $this->_database->where("r.loan_date <", $endTime);

        if ($returnSQL)
            return $this->_database->get_compiled_select('', TRUE);
        return $this->_database->get()->result_array();
    }

    /**
     * 取得申貸案件數量/借款人服務費/投資人回款手續費
     * @param array $qrcodeWhere 推薦碼篩選條件
     * @param array $productIdList 產品編號列表
     * @param string $startTime 篩選起始時間
     * @param string $endTime 篩選結束時間
     * @return mixed
     */
    public function getProductRewardList(array $qrcodeWhere, array $productIdList, array $statusList, string $startTime = '', string $endTime = '')
    {
        $subQuery = $this->getLoanedCount($qrcodeWhere, $productIdList, $statusList, '', '', TRUE);

        $this->db
            ->select('r.*, SUM(tra.amount) as borrower_platform_fee')
            ->from('`p2p_transaction`.`transactions` AS `tra`')
            ->join("({$subQuery}) as `r`", "`r`.`id` = `tra`.`target_id` AND `r`.`user_id` = `tra`.`user_from`")
            ->where('tra.source', SOURCE_FEES)
            ->where('tra.status', TRANSACTION_STATUS_PAID_OFF)
            ->group_by('tra.target_id');
        if ($startTime != '')
            $this->db->where("entering_date >=", $startTime);
        if ($endTime != '')
            $this->db->where("entering_date <", $endTime);

        $borrowerQuery = $this->db->get_compiled_select('', TRUE);

        $this->db
            ->select('r.*, SUM(tra.amount) as investor_platform_fee')
            ->from('`p2p_transaction`.`transactions` AS `tra`')
            ->join("({$subQuery}) as `r`", "`r`.`id` = `tra`.`target_id` AND `r`.`user_id` != `tra`.`user_from`")
            ->where('tra.source', SOURCE_FEES)
            ->where('tra.status', TRANSACTION_STATUS_PAID_OFF)
            ->group_by('tra.target_id');
        if ($startTime != '')
            $this->db->where("entering_date >=", $startTime);
        if ($endTime != '')
            $this->db->where("entering_date <", $endTime);

        $investorQuery = $this->db->get_compiled_select('', TRUE);
        $this->db
            ->select('r.*, br.borrower_platform_fee, ir.investor_platform_fee')
            ->from("({$subQuery}) as `r`")
            ->join("({$borrowerQuery}) as `br`", "`r`.`id` = `br`.`id`", 'left')
            ->join("({$investorQuery}) as `ir`", "`r`.`id` = `ir`.`id`", 'left');

        return $this->db->get()->result_array();
    }

    /**
     * 取得申貸借款人服務費手續費的交易記錄
     * @param array $qrcodeWhere 推薦碼篩選條件
     * @param array $productIdList 產品編號列表
     * @param string $startTime 篩選起始時間
     * @param string $endTime 篩選結束時間
     * @return mixed
     */
    public function getBorrowerPlatformFeeList(array $qrcodeWhere, array $productIdList, array $statusList, string $startTime = '', string $endTime = '')
    {
        $subQuery = $this->getLoanedCount($qrcodeWhere, $productIdList, $statusList, '', '', TRUE);

        $this->db
            ->select('r.*, tra.amount as platform_fee, tra.entering_date')
            ->from('`p2p_transaction`.`transactions` AS `tra`')
            ->join("({$subQuery}) as `r`", "`r`.`id` = `tra`.`target_id` AND `r`.`user_id` = `tra`.`user_from`")
            ->where('tra.source', SOURCE_FEES)
            ->where('tra.status', TRANSACTION_STATUS_PAID_OFF);
        if ($startTime != '')
            $this->db->where("entering_date >=", $startTime);
        if ($endTime != '')
            $this->db->where("entering_date <", $endTime);

        return $this->db->get()->result_array();
    }

    /**
     * 取得投資人回款手續費的交易記錄
     * @param array $qrcodeWhere 推薦碼篩選條件
     * @param array $productIdList 產品編號列表
     * @param string $startTime 篩選起始時間
     * @param string $endTime 篩選結束時間
     * @return mixed
     */
    public function getInvestorPlatformFeeList(array $qrcodeWhere, array $productIdList, array $statusList, string $startTime = '', string $endTime = '')
    {
        $subQuery = $this->getLoanedCount($qrcodeWhere, $productIdList, $statusList, '', '', TRUE);

        $this->db
            ->select('r.*, tra.amount as platform_fee, tra.entering_date')
            ->from('`p2p_transaction`.`transactions` AS `tra`')
            ->join("({$subQuery}) as `r`", "`r`.`id` = `tra`.`target_id` AND `r`.`user_id` != `tra`.`user_from`")
            ->where('tra.source', SOURCE_FEES)
            ->where('tra.status', TRANSACTION_STATUS_PAID_OFF);
        if ($startTime != '')
            $this->db->where("entering_date >=", $startTime);
        if ($endTime != '')
            $this->db->where("entering_date <", $endTime);

        return $this->db->get()->result_array();
    }

    /**
     * 取得用戶資料與推薦碼相關資訊
     * @param array $user_where
     * @param array $qrcode_where
     * @param int $limit
     * @param int $offset
     * @return mixed
     */
    public function getUserQrcodeInfo(array $user_where = [], array $qrcode_where = [], int $limit = 0, int $offset = 0)
    {
        $this->_database->select('*')
            ->from("`p2p_user`.`users`");
        if ( ! empty($user_where))
            $this->_set_where([$user_where]);
        $subQuery = $this->_database->get_compiled_select('', TRUE);

        $this->_database
            ->select('u.name, uq.*')
            ->from('`p2p_user`.`user_qrcode` AS `uq`')
            ->join("({$subQuery}) as `u`", "`u`.`id` = `uq`.`user_id`", 'left');
        if ($limit)
        {
            $offset = max(0, $offset);
            $limit = max(0, $limit);
            $this->_database->limit($limit, $offset);
        }
        if ( ! empty($qrcode_where))
        {
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
    public function getQrcodeRewardInfo(array $qrcode_where = [])
    {
        $this->_database->select('*')
            ->from("`p2p_user`.`user_qrcode`");
        if ( ! empty($qrcode_where))
            $this->_set_where([$qrcode_where]);
        $subQuery = $this->_database->get_compiled_select('', TRUE);

        $this->_database
            ->select('u.id, u.promote_code, u.handle_time, u.settlementing, ur.user_qrcode_id, ur.start_time, ur.end_time, ur.amount, ur.json_data')
            ->from('`p2p_transaction`.`qrcode_reward` AS `ur`')
            ->join("({$subQuery}) as `u`", "`u`.`id` = `ur`.`user_qrcode_id`", 'right')
            ->order_by("ur.end_time", "DESC");
        $subQuery2 = $this->_database->get_compiled_select('', TRUE);

        $this->_database
            ->select('r.*')
            ->from("({$subQuery2}) as `r`")
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
    public function setUserPromoteLock($promoteCode, $old_status, $new_status)
    {
        $conditions = [
            'promote_code' => $promoteCode,
            'settlementing' => $old_status,
            'status' => 1,
        ];

        $this->db->trans_start();
        $this->db->select_for_update('*')->where($conditions);
        $userPromoteCode = $this->db->get($this->_table)->result();

        if (is_array($userPromoteCode) && ! empty($userPromoteCode))
        {
            $userPromoteCode = $userPromoteCode[0];
            $result = $this->db->where(['id' => $userPromoteCode->id])
                ->set(['settlementing' => $new_status])
                ->update($this->_table);

            // 更新失敗需回傳 empty array
            if ($this->db->affected_rows() > 0)
            {
                $userPromoteCode->settlementing = $new_status;
            }
            else
            {
                $userPromoteCode = [];
            }
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {

            $userPromoteCode = [];
        }

        return $userPromoteCode;
    }

    public function autoRenewTime($alias)
    {
        $this->_database
            ->from('`p2p_user`.`user_qrcode`')
            ->where('status', PROMOTE_STATUS_AVAILABLE)
            ->where('DATE_SUB(end_time, INTERVAL 1 DAY) <=', "'" . date('Y-m-d H:i:s') . "'", FALSE)
            ->where('alias', $alias)
            ->set('end_time', 'DATE_ADD(end_time, INTERVAL 1 YEAR)', FALSE)
            ->update();

    }

    /**
     * 取得使用推薦碼申貸的列表
     * @param array $qrcodeWhere 推薦碼篩選條件
     * @param array $productIdList 產品編號列表
     * @param string $startTime 篩選起始時間
     * @param string $endTime 篩選結束時間
     * @param bool $returnSQL 是否回傳SQL語句
     * @return mixed
     */
    public function get_target_apply_list(array $qrcodeWhere, array $productIdList, array $statusList, string $startTime = '', string $endTime = '', bool $returnSQL = FALSE)
    {
        $this->load->model('user/qrcode_setting_model');
        $subQuery = $this->getRegisteredUserByPromoteCode($qrcodeWhere, '', '', TRUE);

        $this->_database
            ->select("r.user_qrcode_id, r.promote_code, r.settings, t.id, t.user_id, t.product_id, t.status, DATE_ADD(DATE_FORMAT(FROM_UNIXTIME(t.created_at), '%Y-%m-%d %H:%i:%s'), INTERVAL 8 HOUR) AS created_at")
            ->from('`p2p_loan`.`targets` AS `t`')
            ->join("({$subQuery}) as `r`", "`t`.`user_id` = `r`.`user_id`")
            ->where_in("t.status", $statusList)
            ->where_in("t.product_id", $productIdList)
            ->where("DATE_ADD(DATE_FORMAT(FROM_UNIXTIME(t.created_at), '%Y-%m-%d %H:%i:%s'), INTERVAL 8 HOUR) BETWEEN `r`.`start_time` AND `r`.`end_time`");

        $fullQuery = $this->_database->get_compiled_select('', TRUE);

        $this->_database
            ->select('r.*')
            ->from("({$fullQuery}) AS `r`");
        if ($startTime != '')
            $this->_database->where("r.created_at >=", $startTime);
        if ($endTime != '')
            $this->_database->where("r.created_at <", $endTime);

        if ($returnSQL)
            return $this->_database->get_compiled_select('', TRUE);
        return $this->_database->get()->result_array();
    }

    public function get_user_name_by_id($id)
    {
        return $this->db
            ->select('u.name')
            ->from('p2p_user.user_qrcode uq')
            ->join('p2p_user.users u', 'u.id=uq.user_id')
            ->where('uq.id', $id)
            ->get()
            ->first_row('array');
    }
}

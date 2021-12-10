<?php

class Target_model extends MY_Model
{
    public $_table = 'targets';
    public $before_create = array('before_data_c');
    public $before_update = array('before_data_u');
    public $status_list = array(
        0 => "待核可",
        1 => "待簽約",
        2 => "待驗證",
        3 => "待出借",
        4 => "待放款（結標）",
        5 => "還款中",
        8 => "已取消",
        9 => "申請失敗",
        10 => "已結案",

        20 => "待報價 (分期)",
        21 => "待簽約 (分期)",
        22 => "待驗證 (分期)",
        23 => "待審批 (分期)",
        24 => "待債轉上架 (分期)",

        30 => "待核可 (外匯車)",
        31 => "待簽約 (外匯車)",

        500 => "銀行審核中",
        501 => "銀行對保中",
        504 => "銀行待放款",
        505 => "銀行還款中",
        509 => "銀行退件",
        510 => "銀行已結案",
    );
    public $sub_list = array(
        0 => "無",
        1 => "轉貸中",
        2 => "轉貸成功",
        3 => "申請提還",
        4 => "提還成功",
        5 => "已通知出貨",
        6 => "出貨鑑賞期",
        7 => "退貨中",
        8 => "產轉案件",
        9 => "二審",
        10 => "二審過件",
        11 => "檢驗保證人",
        12 => "轉人工(內部審核)",
        13 => "法催",

        20 => "交易成功(系統自動)",
        21 => "需轉人工",

    );

    public $loan_list = array(
        0 => "無",
        1 => "已出款",
        2 => "待出款",
        3 => "出款中",
    );

    public $delay_list = array(
        0 => "未逾期",
        1 => "逾期中",
    );

    public $delivery_list = array(
        0 => "線下",
        1 => "線上",
    );

    public $simple_fields = array(
        "id",
        "target_no",
        "product_id",
        "user_id",
        "amount",
        "loan_amount",
        "platform_fee",
        "interest_rate",
        "instalment",
        "repayment",
        "delay",
        "delay_days",
        "status",
        "sub_status",
        "created_at",
    );

    public $detail_fields = array(
        "id",
        "target_no",
        "product_id",
        "user_id",
        "amount",
        "loan_amount",
        "platform_fee",
        "interest_rate",
        "instalment",
        "repayment",
        "reason",
        "remark",
        "delay",
        "delay_days",
        "status",
        "sub_status",
        "created_at",
        "updated_at"
    );

    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->load->database('loan', TRUE);
    }

    protected function before_data_c($data)
    {
        $data['created_at'] = $data['updated_at'] = time();
        $data['created_ip'] = $data['updated_ip'] = get_ip();
        return $data;
    }

    protected function before_data_u($data)
    {
        $data['updated_at'] = time();
        $data['updated_ip'] = get_ip();
        return $data;
    }

    public function getUniqueApplicantCountByStatus($status, $isNewApplicant, $createdRange = [], $convertedRange = [])
    {
        if ($isNewApplicant) {
            $this->db->select("MIN(id) AS target_id")
                ->from('p2p_loan.targets')
                ->where_in('status', $status)
                ->group_by('user_id');
            $fromTable = $this->db->get_compiled_select();
        } else {
            $this->db->select('user_id AS existing_user_id');
            $this->db->from('p2p_loan.targets');
            $this->db->where_in('status', [5, 9, 10]);
            $this->db->where('created_at <=', $createdRange['start']);
            $this->db->where('loan_date <=', date('Y-m-d', $createdRange['end']));
            $this->db->group_by('user_id');
            $fromTable = $this->db->get_compiled_select();
        }

        $this->db->select('
                    COUNT(user_id) AS count,
                    status,
                    product_id,
                    sub_product_id,
                    SUM(least(loan_amount, amount)) AS sumAmount
                 ')
            ->from("({$fromTable}) AS uniqueTargets");

        if ($isNewApplicant) {
            $this->db->join('p2p_loan.targets', 'uniqueTargets.target_id = targets.id');
        } else {
            $this->db->join('p2p_loan.targets', 'uniqueTargets.existing_user_id = targets.user_id');
        }

        if ($status) {
            $this->db->where_in('status', $status);
        }

        if (isset($createdRange['start'])) {
            $this->db->where(['created_at >=' => $createdRange['start']]);
        }
        if (isset($createdRange['end'])) {
            $this->db->where(['created_at <=' => $createdRange['end']]);
        }
        if (isset($convertedRange['start'])) {
            $this->db->where(['updated_at >=' => $convertedRange['start']]);
        }
        if (isset($convertedRange['end'])) {
            $this->db->where(['updated_at <=' => $convertedRange['end']]);
        }

        $this->db->group_by('status, product_id, sub_product_id');
        $query = $this->db->get();

        return $query->result();
    }

    public function getApplicationCountByStatus($status = [], $createdRange = [], $convertedRange = [])
    {
        $this->db->select('
                    COUNT(*) AS count,
                    status,
                    product_id,
                    sub_product_id
                 ')
            ->from("p2p_loan.targets");

        if ($status) {
            $this->db->where_in('status', $status);
        }

        if (isset($createdRange['start'])) {
            $this->db->where(['created_at >=' => $createdRange['start']]);
        }
        if (isset($createdRange['end'])) {
            $this->db->where(['created_at <=' => $createdRange['end']]);
        }
        if (isset($convertedRange['start'])) {
            $this->db->where(['updated_at >=' => $convertedRange['start']]);
        }
        if (isset($convertedRange['end'])) {
            $this->db->where(['updated_at <=' => $convertedRange['end']]);
        }

        $this->db->group_by('status, product_id, sub_product_id');
        $query = $this->db->get();

        return $query->result();
    }

    public function getApplicationAmountByStatus($status = [], $createdRange = [], $convertedRange = [])
    {
        $this->db->select('
                    status,
                    product_id,
                    sub_product_id,
                    SUM(least(loan_amount, amount)) AS sumAmount
                 ')
            ->from("p2p_loan.targets");

        if ($status) {
            $this->db->where_in('status', $status);
        }

        if (isset($createdRange['start'])) {
            $this->db->where(['created_at >=' => $createdRange['start']]);
        }
        if (isset($createdRange['end'])) {
            $this->db->where(['created_at <=' => $createdRange['end']]);
        }
        if (isset($convertedRange['start'])) {
            $this->db->where(['updated_at >=' => $convertedRange['start']]);
        }
        if (isset($convertedRange['end'])) {
            $this->db->where(['updated_at <=' => $convertedRange['end']]);
        }

        $this->db->group_by('status, product_id, sub_product_id');
        $query = $this->db->get();

        return $query->result();
    }

    public function getUserStatusByTargetId($targetIds) {
        $this->db->select('*')
            ->from("`p2p_loan`.`targets`")
            ->where_in('id', $targetIds);
        $subquery = $this->db->get_compiled_select('', TRUE);
        $this->db
            ->select('t.user_id, COUNT(*) as total_count')
            ->from('`p2p_loan`.`targets` AS `t`')
            ->join("($subquery) as `r`", "`t`.`user_id` = `r`.`user_id`")
            ->where('t.created_at < r.created_at')
            ->where("FROM_UNIXTIME(`t`.`created_at`, '%Y-%m-%d %H:%i:%s') >= DATE_SUB(FROM_UNIXTIME(`r`.`created_at`, '%Y-%m-%d %H:%i:%s'), INTERVAL 6 MONTH)")
            ->group_by('t.user_id');

        return $this->db->get()->result();
    }
    
    /**
     * 設定案件的的腳本使用狀態 (成功:回傳案件的物件/不成功:回傳空陣列)
     * @param $target_ids
     * @param $old_status
     * @param $new_status
     * @return array|stdClass
     */
    public function setScriptStatus($target_ids, $old_status, $new_status) {
        if(!is_array($target_ids) || empty($target_ids))
            return [];

        $this->db->trans_begin();
        $targets = $this->db->select_for_update('*')->where_in('id', $target_ids)
            ->where('script_status', $old_status)
            ->from('`p2p_loan`.`targets`')
            ->get()->result();

        if(is_array($targets) && !empty($targets)) {
            $this->db->where_in('id', $target_ids)
                ->where('script_status', $old_status)
                ->set(['script_status' => $new_status])
                ->update('`p2p_loan`.`targets`');

            // 更新失敗需回傳 empty array
            if($this->db->affected_rows() != count($target_ids)) {
                $this->db->trans_rollback();
                return [];
            }
        }
        $this->db->trans_commit();

        foreach ($targets as $target) {
            $target->script_status = $new_status;
        }
        return $targets;
    }

    /**
     * 取得逾期案件之相關資訊（含該案的平台手續費）
     * @param $targetIds
     * @return mixed
     */
    public function getDelayedTarget($targetIds) {
        $this->db->select('target_id, entering_date, user_from, user_to')
            ->from("`p2p_transaction`.`transactions`")
            ->where_in('target_id', $targetIds)
            ->where('source', SOURCE_AR_DELAYINTEREST)
            ->group_by('target_id');
        $subquery = $this->db->get_compiled_select('', TRUE);

        $this->db
            ->select('ta.user_id, ta.loan_date, ta.product_id, ta.sub_product_id, ta.id, t.entering_date as delay_date')
            ->from('`p2p_loan`.`targets` AS `ta`')
            ->join("($subquery) as `t`", "`ta`.`id` = `t`.`target_id`");
        $subQuery2 = $this->db->get_compiled_select('', TRUE);

        $this->db
            ->select('r.*, SUM(tra.amount) as borrower_platform_fee')
            ->from('`p2p_transaction`.`transactions` AS `tra`')
            ->join("($subQuery2) as `r`", "`r`.`id` = `tra`.`target_id` AND `r`.`user_id` = `tra`.`user_from`")
            ->where('tra.source', SOURCE_FEES)
            ->where('tra.status', TRANSACTION_STATUS_PAID_OFF)
            ->group_by('tra.target_id');
        $borrowerQuery = $this->db->get_compiled_select('', TRUE);

        $this->db
            ->select('r.*, SUM(tra.amount) as investor_platform_fee')
            ->from('`p2p_transaction`.`transactions` AS `tra`')
            ->join("($subQuery2) as `r`", "`r`.`id` = `tra`.`target_id` AND `r`.`user_id` != `tra`.`user_from`")
            ->where('tra.source', SOURCE_FEES)
            ->where('tra.status', TRANSACTION_STATUS_PAID_OFF)
            ->group_by('tra.target_id');
        $investorQuery = $this->db->get_compiled_select('', TRUE);

        $this->db
            ->select('r.*, br.borrower_platform_fee, ir.investor_platform_fee')
            ->from("($subQuery2) as `r`")
            ->join("($borrowerQuery) as `br`", "`r`.`id` = `br`.`id`", 'left')
            ->join("($investorQuery) as `ir`", "`r`.`id` = `ir`.`id`", 'left');

        return $this->db->get()->result_array();
    }
}

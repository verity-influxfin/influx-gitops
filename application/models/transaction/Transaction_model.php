<?php

class Transaction_model extends MY_Model
{
	public $_table = 'transactions';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );
	public $status_list   = array(
		0 =>	"已刪除",
		1 =>	"正常",
		2 =>	"已結清",
	);
	
	public $passbook_status_list = array(
		0 =>	"未處理",
		1 =>	"入帳",
		2 =>	"處理中",
	);
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('transaction',TRUE);
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

    public function getDelayUserList(){
	    $list = [];
        $this->db->select('
                    user_from
                 ')
            ->from("p2p_transaction.transactions")
            ->where('source =', 93);
        $this->db->group_by('user_from');
        $query = $this->db->get()->result();
        foreach ($query as $k => $v){
            $list[] = $v->user_from;
        }
        return $list;
    }

    public function getDelayedAccountPayableByTarget($source_list=[], $user_from=0, $before_date="") {
	    return $this->_getDelayedAccountPayable('tra.target_id, tra.user_to, SUM(tra.amount) AS amount',
            0,$source_list,$user_from,$before_date,'target_id');
    }

    public function getDelayedAccountPayable($select="tra.target_id, tra.source, tra.investment_id, tra.user_to, SUM(tra.amount) AS amount",
                                             $target_id=0, $source_list=[], $user_from=0,
                                              $before_date='', $group_by='investment_id, source')
    {
        return $this->_getDelayedAccountPayable($select, $target_id, $source_list, $user_from,
            $before_date, $group_by);
    }

    /**
     * 取得案件的所有逾期應付款項，並依照 investment_id 進行分群
     * @param int $target_id
     * @param array $source_list: 欲撈取交易科目列表
     * @param string $before_date: 篩選該時間以前的資料之日期
     * @return mixed
     */
    private function _getDelayedAccountPayable($select="tra.*", $target_id=0, $source_list=[], $user_from=0,
                                             $before_date='', $group_by='investment_id, source') {
        if($before_date == "")
            $before_date = get_entering_date();

        $condition = [
            'source' => SOURCE_AR_PRINCIPAL,
            'status' => 1,
            'limit_date < ' => $before_date
        ];
        if($target_id)
            $condition['target_id'] = $target_id;
        if($user_from)
            $condition['user_from'] = $user_from;
        if(empty($source_list))
            $source_list = [SOURCE_AR_DAMAGE,SOURCE_AR_DELAYINTEREST,SOURCE_AR_PRINCIPAL,SOURCE_AR_INTEREST,SOURCE_AR_LAW_FEE];

        $this->db
            ->select('target_id, limit_date')
            ->from('`p2p_transaction`.`transactions`')
            ->where($condition)
            ->group_by('target_id')
            ->having('MIN(`limit_date`) = `limit_date`');
        $subquery = $this->db->get_compiled_select('', TRUE);
        $this->db
            ->select($select)
            ->from('`p2p_transaction`.`transactions` AS `tra`')
            ->join("($subquery) as `r_tra`", "`tra`.`target_id` = `r_tra`.`target_id`")
            ->where_in('source', $source_list)
            ->where('status', 1)
            ->where('`tra`.`limit_date` >= `r_tra`.`limit_date`')
            ->order_by('investment_id');
        if($group_by != "")
            $this->db->group_by($group_by);

        return $this->db->get()->result();
    }

    public function getDelayedTargetInfoList($transaction_where, $target_where)
    {
        $this->_database->select('MIN(`limit_date`) as `min_limit_date`, `target_id`')
            ->from("`p2p_transaction`.`transactions`")
            ->where($transaction_where)
            ->group_by('target_id');
        $subquery = $this->_database->get_compiled_select('', TRUE);

        $this->_database
            ->select('ta.user_id, ta.loan_date, ta.product_id, ta.sub_product_id, ta.target_no, ta.delay_days, ta.script_status, t.*')
            ->from('`p2p_loan`.`targets` AS `ta`')
            ->join("($subquery) as `t`", "`ta`.`id` = `t`.`target_id`");
        $this->_set_where([0 => $target_where]);
        $subquery2 = $this->_database->get_compiled_select('', TRUE);

        $this->_database
            ->select('i.target_id, i.user_id as investor_userid, r.user_id, r.loan_date, r.product_id, r.sub_product_id, r.target_no, r.min_limit_date, r.delay_days, r.script_status')
            ->from('`p2p_loan`.`investments` AS `i`')
            ->where('status', 3)
            ->where('transfer_status <', '2')
            ->join("($subquery2) as `r`", "`i`.`target_id` = `r`.`target_id`")
            ->order_by('i.target_id', 'ASC');

        $query = $this->_database->get();
        return $query->result();
    }

    // 新增內帳交易紀錄，並回傳ID
    public function insert_get_id($data)
    {
        $this->db->set($data)->insert("`p2p_transaction`.`{$this->_table}`");
        return $this->db->insert_id();
    }

    /**
     * 依target ID及科目名稱找第N期的還款狀態
     * @param $target_id
     * @param $source : 科目名稱
     * @param $instalment_no
     * @return mixed
     */
    public function get_repayment_status_by_target_id($target_id, $source, $instalment_no)
    {
        $this->db
            ->select('status')
            ->from('p2p_transaction.' . $this->_table)
            ->where('target_id', $target_id)
            ->where('source', $source)
            ->where('instalment_no', $instalment_no);

        return $this->db->get()->first_row('array');
    }

    public function get_delayed_principle($target_ids, $end_date, $group_by) {
        $this->db
            ->select('id, user_id')
            ->from('`p2p_loan`.`targets`')
            ->where('loan_date <= ', $end_date)
            ->where_in('status', [TARGET_REPAYMENTING, TARGET_REPAYMENTED]);
        if ( ! empty($target_ids))
        {
            $this->db->where_in('id', $target_ids);
        }
        $subquery = $this->db->get_compiled_select('', TRUE);
        $this->db
            ->select('t.user_id, SUM(tra.amount) as total_amount')
            ->from('`p2p_transaction`.`transactions` AS `tra`')
            ->join("($subquery) as `t`", "`tra`.`target_id` = `t`.`id`")
            ->where('source', SOURCE_AR_PRINCIPAL)
            ->where_in('status', [TRANSACTION_STATUS_TO_BE_PAID, TRANSACTION_STATUS_PAID_OFF])
            ->where('`tra`.`limit_date` > ', $end_date);
        if ( ! empty($group_by))
        {
            $this->db->group_by($group_by);
        }
        return $this->db->get()->result_array();
    }

    /**
     * 取得應付的應付日期和金額
     * @param $source_list
     * @param mixed $user_from_list 金額付款來源user
     * @param mixed $user_to_list 金額付款目標user
     * @param mixed $product_id_list 欲篩選產品編號
     * @param bool $is_group 依照科目,交易日群組計算
     * @return mixed
     */
    public function get_account_payable_list($source_list, $user_from_list = [], $user_to_list = [], $product_id_list = [], bool $is_group = TRUE, string $start_date='')
    {
        $this->db
            ->select('tra.source')
            ->select('tra.limit_date AS tx_date')
            ->from('`p2p_transaction`.`transactions` AS `tra`')
            ->join('`p2p_loan`.`targets` AS t', 't.id = tra.target_id')
            ->where_in('tra.source', $source_list)
            ->where_in('tra.status', [TRANSACTION_STATUS_TO_BE_PAID]);
        if ( ! empty($start_date))
        {
            $this->db->where('tra.limit_date >= ', $start_date);
        }
        if ($is_group)
        {
            $this->db->select('SUM(tra.amount) AS amount')
                ->group_by('tra.source, tra.limit_date');
        }
        else
        {
            $this->db->select('tra.amount');
        }
        if ( ! empty($user_from_list))
        {
            $this->db->where_in('tra.user_from', $user_from_list);
        }
        if ( ! empty($user_to_list))
        {
            $this->db->where_in('tra.user_to', $user_to_list);
        }
        if ( ! empty($product_id_list))
        {
            $this->db->where_in('t.product_id', $product_id_list);
        }

        return $this->db->get()->result_array();
    }

    /**
     * 取得已結清的交易日期和金額
     * @param $source_list
     * @param mixed $user_from_list 金額付款來源user
     * @param mixed $user_to_list 金額付款目標user
     * @param mixed $product_id_list 欲篩選產品編號
     * @param bool $is_group 依照科目,交易日群組計算
     * @return mixed
     */
    public function get_paid_off_list($source_list, $user_from_list = [], $user_to_list = [], $product_id_list = [], $is_group = TRUE)
    {
        $this->db
            ->select('tra.source')
            ->select('tra.entering_date AS tx_date')
            ->from('`p2p_transaction`.`transactions` AS `tra`')
            ->join('`p2p_loan`.`targets` AS t', 't.id = tra.target_id')
            ->where_in('tra.source', $source_list)
            ->where('tra.status', TRANSACTION_STATUS_PAID_OFF)
            // 防止撈到借款人的出借手續費等科目
            ->where('tra.investment_id !=', 0);

        if ($is_group)
        {
            $this->db->select('SUM(tra.amount) AS amount')
                ->group_by('tra.source, tra.entering_date');
        }
        else
        {
            $this->db->select('tra.amount');
        }
        if ( ! empty($user_from_list))
        {
            $this->db->where_in('tra.user_from', $user_from_list);
        }
        if ( ! empty($user_to_list))
        {
            $this->db->where_in('tra.user_to', $user_to_list);
        }
        if ( ! empty($product_id_list))
        {
            $this->db->where_in('t.product_id', $product_id_list);
        }

        return $this->db->get()->result_array();
    }

    /**
     * 取得提前清償(含)後的結清交易之交易日與金額
     * @param $source_list
     * @param mixed $user_to_list 金額付款目標user
     * @param mixed $product_id_list 欲篩選產品編號
     * @param bool $is_group 依照科目,交易日群組計算
     * @return mixed
     */
    public function get_prepaid_transactions($source_list, $user_to_list = [], $product_id_list = [], bool $is_group = TRUE)
    {
        $this->db
            ->select('tra.investment_id, tra.entering_date')
            ->from('`p2p_transaction`.`transactions` AS `tra`')
            ->join('`p2p_loan`.`targets` AS `t`', 't.id = tra.target_id')
            ->where_in('tra.source', SOURCE_PREPAYMENT_ALLOWANCE)
            ->where('tra.status', TRANSACTION_STATUS_PAID_OFF);
        if ( ! empty($user_to_list))
        {
            $this->db->where_in('tra.user_to', $user_to_list);
        }
        if ( ! empty($product_id_list))
        {
            $this->db->where_in('t.product_id', $product_id_list);
        }
        $paid_off_prepayment_query = $this->db->get_compiled_select('', TRUE);

        $this->db
            ->select('tra.source')
            ->select('tra.entering_date AS tx_date')
            ->from('`p2p_transaction`.`transactions` AS `tra`')
            ->join("({$paid_off_prepayment_query}) AS `r`", 'r.investment_id = tra.investment_id')
            ->where_in('tra.source', $source_list)
            ->where('tra.status', TRANSACTION_STATUS_PAID_OFF)
            ->where('tra.entering_date >= r.entering_date', NULL, TRUE);

        if ($is_group)
        {
            $this->db->select('SUM(tra.amount) AS amount')
                ->group_by('tra.source, tra.entering_date');
        }
        else
        {
            $this->db->select('tra.amount');
        }

        return $this->db->get()->result_array();
    }

    public function get_delayed_paid_transaction($source_list, $user_to_list = [], $product_id_list = [], bool $is_group = TRUE): array
    {
        return $this->_get_delayed_transaction($source_list, $user_to_list, $product_id_list, $is_group, $status=TRANSACTION_STATUS_PAID_OFF);
    }

    public function get_delayed_ar_transaction($source_list, $user_to_list = [], $product_id_list = [], bool $is_group = TRUE): array
    {
        return $this->_get_delayed_transaction($source_list, $user_to_list, $product_id_list, $is_group, $status=TRANSACTION_STATUS_TO_BE_PAID, $only_over_delayed_date=FALSE);
    }

    /**
     * 取得逾期(含)後的結清交易之交易日與金額
     * @param $source_list
     * @param mixed $user_to_list
     * @param mixed $product_id_list
     * @param bool $is_group
     * @param mixed $status 交易紀錄的狀態
     * @param bool $only_over_delayed_date 只撈取超過逾期日的交易紀錄
     * @return array
     */
    public function _get_delayed_transaction($source_list, $user_to_list = [], $product_id_list = [], bool $is_group = TRUE, $status = TRANSACTION_STATUS_PAID_OFF, bool $only_over_delayed_date = TRUE): array
    {
        $this->db
            ->select('tra.investment_id, MIN(tra.entering_date) AS entering_date')
            ->from('`p2p_transaction`.`transactions` AS `tra`')
            ->join('`p2p_loan`.`targets` AS `t`', 't.id = tra.target_id')
            ->where('tra.source', SOURCE_AR_DELAYINTEREST)
            ->where_in('tra.status', [TRANSACTION_STATUS_TO_BE_PAID, TRANSACTION_STATUS_PAID_OFF])
            ->group_by('investment_id');
        if ( ! empty($user_to_list))
        {
            $this->db->where_in('tra.user_to', $user_to_list);
        }
        if ( ! empty($product_id_list))
        {
            $this->db->where_in('t.product_id', $product_id_list);
        }
        $delayed_query = $this->db->get_compiled_select('', TRUE);

        $this->db
            ->select('tra.source')
            ->select('tra.entering_date AS tx_date')
            ->from('`p2p_transaction`.`transactions` AS `tra`')
            ->join("({$delayed_query}) AS `r`", 'r.investment_id = tra.investment_id')
            ->where_in('tra.source', $source_list)
            ->where_in('tra.status', $status);

        if($only_over_delayed_date)
        {
            $this->db->where('tra.entering_date >= r.entering_date', NULL, TRUE);
        }

        if ($is_group)
        {
            $this->db->select('SUM(tra.amount) AS amount')
                ->group_by('tra.source, tra.entering_date');
        }
        else
        {
            $this->db->select('tra.amount');
        }

        return $this->db->get()->result_array();
    }

    public function get_targets_account_payable_amount($target_ids, $sources, $group_by_target = TRUE, $group_by_investment = FALSE)
    {
        $this->db
            ->select('id, user_id')
            ->from('`p2p_loan`.`targets`')
            ->where_in('status', [TARGET_REPAYMENTING]);
        if ( ! empty($target_ids))
        {
            $this->db->where_in('id', $target_ids);
        }
        $subquery = $this->db->get_compiled_select('', TRUE);
        $this->db
            ->select('`t`.`user_id`, SUM(tra.amount) as total_amount')
            ->from('`p2p_transaction`.`transactions` AS `tra`')
            ->join("($subquery) as `t`", '`tra`.`target_id` = `t`.`id`')
            ->where_in('source', $sources)
            ->where_in('status', [TRANSACTION_STATUS_TO_BE_PAID]);
        if ($group_by_target)
        {
            $this->db->select('`tra`.`target_id`')->group_by('`tra`.`target_id`');
        }
        else if ($group_by_investment)
        {
            $this->db->select('`tra`.`investment_id`')->group_by('`tra`.`investment_id`');
        }
        return $this->db->get()->result_array();
    }

    /**
     * 取得貸款人每期還款總額與期間
     * @param $target_id
     * @return mixed
     */
    public function get_repayment_schedule($target_id)
    {
        $this->db->select('id')
            ->from('`p2p_transaction`.`transactions`')
            ->where('status', TRANSACTION_STATUS_TO_BE_PAID)
            ->where('target_id', $target_id)
            ->where_in('source', [SOURCE_AR_PRINCIPAL, SOURCE_AR_INTEREST,
                SOURCE_AR_DAMAGE, SOURCE_AR_DELAYINTEREST]);
        $sub_query = $this->db->get_compiled_select('', TRUE);
        $this->db
            ->select('instalment_no, limit_date, SUM(amount) as amount')
            ->from('`p2p_transaction`.`transactions` AS `tra`')
            ->join("({$sub_query}) as `r`", "`tra`.`id` = `r`.`id`")
            ->order_by('limit_date', 'ASC')
            ->group_by('limit_date');
        return $this->db->get()->result_array();
    }

    /**
     * 取得每個月的風險指標
     * @param $year : 年份
     * @param $month : 月份
     * @return array
     *
     * delay_users_count 當月逾期人數
     * delay_loans_count 當月逾期筆數
     * apply_loans_amount 當月申貸金額
     * apply_student_loans_count 當月學生貸申貸案件
     * apply_work_loans_count 當月上班族貸申貸案件
     */
    public function get_risk_report_info_by_month($year, $month)
    {
        $date = new DateTime();
        $date->setDate($year, $month, 1);
        $start = date('Y-m-d 00:00:00', $date->getTimestamp());
        $end = date('Y-m-t 23:59:59', $date->getTimestamp());

        if (empty($start) || empty($end))
        {
            return [];
        }

        $sql = '    
        SELECT (`r1`.`delay_users_count` - `r4`.`debt_settlement_users_count`) AS `delay_users_count`,
               (`r2`.`delay_loans_count` - `r4`.`debt_settlement_loans_count`) AS `delay_loans_count`,
               `r5`.*, `r6`.*, `r7`.*
          FROM (SELECT COUNT(1) AS delay_users_count
                  FROM (SELECT DISTINCT `user_from`
                          FROM `p2p_transaction`.`transactions`
                         WHERE `source` = ' . SOURCE_AR_DELAYINTEREST . '
                           AND `status` IN (' . TRANSACTION_STATUS_TO_BE_PAID . ', ' . TRANSACTION_STATUS_PAID_OFF . ') 
                           AND DATE_FORMAT(`limit_date`, "%Y-%m-%d %H:%i:%s") BETWEEN "' . $start . '" AND "' . $end . '") AS `tra`) AS `r1`,
               (SELECT COUNT(1) AS delay_loans_count
                  FROM (SELECT DISTINCT `target_id`
                          FROM `p2p_transaction`.`transactions`
                         WHERE `source` = ' . SOURCE_AR_DELAYINTEREST . '
                           AND `status` IN (' . TRANSACTION_STATUS_TO_BE_PAID . ', ' . TRANSACTION_STATUS_PAID_OFF . ')
                           AND DATE_FORMAT(`limit_date`, "%Y-%m-%d %H:%i:%s") BETWEEN "' . $start . '" AND "' . $end . '") AS `tra`) AS `r2`,
               (SELECT COUNT(DISTINCT (`targets`.`user_id`)) AS debt_settlement_users_count, COUNT(DISTINCT (`targets`.`id`)) AS debt_settlement_loans_count
                  FROM (SELECT DISTINCT `target_id`, `limit_date`
                          FROM `p2p_transaction`.`transactions`
                         WHERE `source` = ' . SOURCE_AR_DELAYINTEREST . '
                           AND `status` IN (' . TRANSACTION_STATUS_TO_BE_PAID . ', ' . TRANSACTION_STATUS_PAID_OFF . ')
                           AND DATE_FORMAT(`limit_date`, "%Y-%m-%d %H:%i:%s") BETWEEN "' . $start . '" AND "' . $end . '") AS `tra`
                  JOIN (SELECT * FROM `p2p_loan`.`targets` WHERE `delay` = 0) AS `targets` ON `tra`.`target_id` = `targets`.`id`) AS `r4`,
               (SELECT SUM(loan_amount) AS apply_loans_amount FROM `p2p_loan`.`targets`
                 WHERE DATE_FORMAT(FROM_UNIXTIME(`created_at`), "%Y-%m-%d %H:%i:%s") BETWEEN "' . $start . '" AND "' . $end . '") AS r5,
               (SELECT COUNT(1) AS apply_student_loans_count FROM `p2p_loan`.`targets`
                 WHERE `product_id` = ' . PRODUCT_ID_STUDENT . '
                   AND DATE_FORMAT(FROM_UNIXTIME(`created_at`), "%Y-%m-%d %H:%i:%s") BETWEEN "' . $start . '" AND "' . $end . '") AS r6,
               (SELECT COUNT(1) AS apply_work_loans_count FROM `p2p_loan`.`targets`
                 WHERE `product_id` = ' . PRODUCT_ID_SALARY_MAN . '
                   AND DATE_FORMAT(FROM_UNIXTIME(`created_at`), "%Y-%m-%d %H:%i:%s") BETWEEN "' . $start . '" AND "' . $end . '") AS r7';

        return $this->db->query($sql)->first_row('array');
    }

    /**
     * 取得開台至今的風險指標
     * @param $year : 年份
     * @param $month : 月份
     * @return array
     *
     * total_apply_success 累計媒合成功件數
     * total_apply_count 累積筆數
     * total_apply_amount 累積金額
     * avg_invest_student 學生貸媒合成功均額
     * avg_invest_work 上班族貸媒合成功均額
     * avg_invest 平均每筆投資金額
     * delayed_return_amount 已累計回收逾期金額
     * yearly_rate_of_return 平均年化報酬率(%)
     */
    public function get_risk_report_info_from_beginning($year, $month)
    {
        $date = new DateTime();
        $date->setDate($year, $month, 1);
        $end = date('Y-m-t 23:59:59', $date->getTimestamp());

        if (empty($end))
        {
            return [];
        }

        $sql = '
        SELECT `r1`.*, `r2`.*, `r4`.*, `r5`.*, `r6`.*, `r7`.*,
               `r7`.`total_apply_amount` / `total_invest_number`.`count` AS `avg_invest`,
               `total_interest_rate`.`interest_rate` / `total_invest_number`.`count` AS `yearly_rate_of_return`
          FROM (SELECT COUNT(1) AS `total_apply_success`
                  FROM `p2p_loan`.`targets`
                 WHERE DATE_FORMAT(`loan_date`, "%Y-%m-%d %H:%i:%s") BETWEEN "2000-01-01 00:00:00" AND "' . $end . '"
                   AND `status` IN (' . TARGET_REPAYMENTING . ', ' . TARGET_REPAYMENTED . ')
               ) AS `r1`,
               (SELECT ((((`r1`.`c` + (`r2`.`c` * 2)) + `r3`.`c`) + `r4`.`c`) + (`r5`.`c` * 2)) AS `total_apply_count`
                  FROM (SELECT COUNT(1) AS c FROM `p2p_loan`.`investments`
                         WHERE `status` = ' . INVESTMENT_STATUS_REPAYING . '
                           AND DATE_FORMAT(FROM_UNIXTIME(`created_at`), "%Y-%m-%d %H:%i:%s") BETWEEN "2000-01-01 00:00:00" AND "' . $end . '"
                       ) `r1`,
                       (SELECT COUNT(1) AS `c` FROM `p2p_loan`.`investments`
                         WHERE `status` = ' . INVESTMENT_STATUS_PAID_OFF . '
                           AND DATE_FORMAT(FROM_UNIXTIME(`created_at`), "%Y-%m-%d %H:%i:%s") BETWEEN "2000-01-01 00:00:00" AND "' . $end . '"
                       ) `r2`,
                       (SELECT COUNT(1) AS `c` FROM `p2p_loan`.`transfers`
                         WHERE `status` = ' . TRANSFER_STATUS_FINISHED . '
                           AND transfer_date BETWEEN "2000-01-01 00:00:00" AND "' . $end . '"
                       ) `r3`,
                       (SELECT COUNT(1) AS c FROM p2p_loan.targets
                         WHERE `status` = ' . TARGET_REPAYMENTING . '
                           AND DATE_FORMAT(loan_date, "%Y-%m-%d %H:%i:%s") BETWEEN "2000-01-01 00:00:00" AND "' . $end . '"
                       ) `r4`,
                       (SELECT COUNT(1) AS c FROM p2p_loan.targets
                         WHERE `status` = ' . TARGET_REPAYMENTED . '
                           AND DATE_FORMAT(`loan_date`, "%Y-%m-%d %H:%i:%s") BETWEEN "2000-01-01 00:00:00" AND "' . $end . '"
                       ) `r5`
               ) AS `r2`,
               (SELECT `student`.`total_amount` / `student`.`total_count` AS `avg_invest_student`
                  FROM (SELECT SUM(`loan_amount`) AS `total_amount`, COUNT(1) AS `total_count`
                          FROM `p2p_loan`.`targets`
                         WHERE DATE_FORMAT(`loan_date`, "%Y-%m-%d %H:%i:%s") BETWEEN "2000-01-01 00:00:00" AND "' . $end . '"
                           AND `product_id` IN (' . PRODUCT_ID_STUDENT . ', ' . PRODUCT_ID_STUDENT_ORDER . ')
                           AND `status` IN (' . TARGET_REPAYMENTING . ', ' . TARGET_REPAYMENTED . ')
                       ) AS `student`
               ) AS `r4`,
               (SELECT `worker`.`total_amount` / `worker`.`total_count` AS `avg_invest_work`
                  FROM (SELECT SUM(`loan_amount`) AS `total_amount`, COUNT(1) AS `total_count`
                          FROM `p2p_loan`.`targets`
                         WHERE DATE_FORMAT(`loan_date`, "%Y-%m-%d %H:%i:%s") BETWEEN "2000-01-01 00:00:00" AND "' . $end . '"
                           AND `product_id` IN (' . PRODUCT_ID_SALARY_MAN . ', ' . PRODUCT_ID_SALARY_MAN_ORDER . ')
                           AND `status` IN (' . TARGET_REPAYMENTING . ', ' . TARGET_REPAYMENTED . ')
                       ) worker
               ) AS `r5`,
               (SELECT SUM(`tra`.`amount`) AS delay_return_amount
                  FROM `p2p_transaction`.`transactions` AS `tra`
                  JOIN (SELECT DISTINCT `target_id`, `created_at` FROM `p2p_transaction`.`transactions`
                         WHERE `source` = ' . SOURCE_AR_DELAYINTEREST . '
                           AND `status` IN (' . TRANSACTION_STATUS_TO_BE_PAID . ', ' . TRANSACTION_STATUS_PAID_OFF . ')
                           AND DATE_FORMAT(FROM_UNIXTIME(`created_at`), "%Y-%m-%d %H:%i:%s") BETWEEN "2000-01-01 00:00:00" AND "' . $end . '"
                         GROUP BY `target_id`
                       ) AS `r` ON `tra`.`target_id` = `r`.`target_id`
                 WHERE `source` IN (' . SOURCE_PRINCIPAL . ', ' . SOURCE_INTEREST . ', ' . SOURCE_DAMAGE . ', ' . SOURCE_DELAYINTEREST . ')
                   AND `status` = ' . TRANSACTION_STATUS_PAID_OFF . '
                   AND `tra`.`entering_date` BETWEEN DATE_FORMAT(FROM_UNIXTIME(`r`.`created_at`), "%Y-%m-%d") AND "' . $end . '"
               ) AS `r6`,
               (SELECT (`r1`.`c` + `r2`.`c` + `r3`.`c`) AS `total_apply_amount`
                  FROM (SELECT SUM(`loan_amount`) AS `c` FROM `p2p_loan`.`investments` WHERE `status` = ' . INVESTMENT_STATUS_REPAYING . '
                           AND DATE_FORMAT(FROM_UNIXTIME(`created_at`), "%Y-%m-%d %H:%i:%s") BETWEEN "2000-01-01 00:00:00" AND "' . $end . '"
                       ) `r1`,
                       (SELECT SUM(`loan_amount`) AS `c` FROM `p2p_loan`.`investments` WHERE `status` = ' . INVESTMENT_STATUS_PAID_OFF . '
                           AND DATE_FORMAT(FROM_UNIXTIME(`created_at`), "%Y-%m-%d %H:%i:%s") BETWEEN "2000-01-01 00:00:00" AND "' . $end . '"
                       ) `r2`,
                       (SELECT SUM(`amount`) AS `c` FROM `p2p_loan`.`transfers` WHERE `status` = ' . TRANSFER_STATUS_FINISHED . '
                           AND `transfer_date` BETWEEN "2000-01-01 00:00:00" AND "' . $end . '"
                       ) `r3`
               ) AS `r7`,
               (SELECT (`r1`.`c` + `r2`.`c` + `r3`.`c`) AS `count`
                  FROM (SELECT COUNT(1) AS `c` FROM `p2p_loan`.`investments`
                         WHERE `status` = ' . INVESTMENT_STATUS_REPAYING . '
                           AND DATE_FORMAT(FROM_UNIXTIME(`created_at`), "%Y-%m-%d %H:%i:%s") BETWEEN "2000-01-01 00:00:00" AND "' . $end . '"
                       ) `r1`,
                       (SELECT COUNT(1) AS `c` FROM `p2p_loan`.`investments`
                         WHERE `status` = ' . INVESTMENT_STATUS_PAID_OFF . '
                           AND DATE_FORMAT(FROM_UNIXTIME(`created_at`), "%Y-%m-%d %H:%i:%s") BETWEEN "2000-01-01 00:00:00" AND "' . $end . '"
                       ) `r2`,
                       (SELECT COUNT(1) AS `c` FROM `p2p_loan`.`transfers`
                         WHERE `status` = ' . TRANSFER_STATUS_FINISHED . '
                           AND `transfer_date` BETWEEN "2000-01-01 00:00:00" AND "' . $end . '"
                       ) `r3`
               ) AS `total_invest_number`,
               (SELECT (`r1`.`interest_rate` + `r2`.`interest_rate` + `r3`.`interest_rate`) AS `interest_rate`
                  FROM (SELECT SUM(`t`.`interest_rate`) AS `interest_rate`
                          FROM `p2p_loan`.`investments` `i`
                          JOIN `p2p_loan`.`targets` `t` ON `i`.`target_id` = `t`.`id`
                         WHERE `i`.`status` = ' . INVESTMENT_STATUS_REPAYING . '
                           AND DATE_FORMAT(FROM_UNIXTIME(`i`.`created_at`), "%Y-%m-%d %H:%i:%s") BETWEEN "2000-01-01 00:00:00" AND "' . $end . '"
                       ) `r1`,
                       (SELECT SUM(`t`.`interest_rate`) AS `interest_rate`
                          FROM `p2p_loan`.`investments` `i`
                          JOIN `p2p_loan`.`targets` `t` ON `i`.`target_id` = `t`.`id`
                         WHERE `i`.`status` = ' . INVESTMENT_STATUS_PAID_OFF . '
                           AND DATE_FORMAT(FROM_UNIXTIME(`i`.`created_at`), "%Y-%m-%d %H:%i:%s") BETWEEN "2000-01-01 00:00:00" AND "' . $end . '"
                       ) `r2`,
                       (SELECT SUM(`t`.`interest_rate`) AS `interest_rate`
                          FROM `p2p_loan`.`transfers` `tf`
                          JOIN `p2p_loan`.`targets` `t` ON `tf`.`target_id` = `t`.`id`
                         WHERE `tf`.`status` = ' . TRANSFER_STATUS_FINISHED . '
                           AND `tf`.`transfer_date` BETWEEN "2000-01-01 00:00:00" AND "' . $end . '"
                       ) `r3`
               ) AS `total_interest_rate`;';

        return $this->db->query($sql)->first_row('array');
    }

    /**
     * 取得各信評等級的逾期率
     * @return array
     *
     * level 信評等級
     * rate 逾期率(%)
     */
    public function get_delay_rate_by_level($year, $month)
    {
        $date = new DateTime();
        $date->setDate($year, $month, 1);
        $end = date('Y-m-t 23:59:59', $date->getTimestamp());

        if (empty($end))
        {
            return [];
        }

        $sql = '
        SELECT `r`.`level`, (SUM(`r`.`amount`) / `loan`.`total_amount`) AS `rate`
        FROM (SELECT `max_credit_result`.`max_credit_time`, `tttt`.*
                FROM (SELECT `ttt`.`target_id`, MAX(`credit_time`) AS `max_credit_time`
                        FROM
                        (SELECT `c`.`level`, `c`.`created_at` AS `credit_time`, `trr`.`user_id`, `trr`.`target_id`, `trr`.`amount`, `trr`.`created_at`
                           FROM `p2p_loan`.`credits` `c`
                           JOIN (SELECT `t`.`user_id`, `t`.`product_id`, `tr`.*
                                   FROM `p2p_loan`.`targets` `t`
                                   JOIN (SELECT `tc`.`id`, `tc`.`created_at`, `r`.*
                                           FROM `p2p_log`.`targets_change_log` `tc`
                                           JOIN (SELECT `tra`.`target_id`, SUM(`tra`.`amount`) AS `amount`
                                                   FROM `p2p_transaction`.`transactions` AS `tra`
                                                   JOIN (SELECT `target_id`, `limit_date` FROM `p2p_transaction`.`transactions`
                                                          WHERE `source` = ' . SOURCE_AR_DELAYINTEREST . '
                                                            AND `status` IN (' . TRANSACTION_STATUS_TO_BE_PAID . ', ' . TRANSACTION_STATUS_PAID_OFF . ')
                                                          GROUP BY `target_id`) AS `r_tra` ON `tra`.`target_id` = `r_tra`.`target_id`
                                                  WHERE `source` = ' . SOURCE_AR_PRINCIPAL . ' AND `status` IN (' . TRANSACTION_STATUS_TO_BE_PAID . ', ' . TRANSACTION_STATUS_PAID_OFF . ')
                                                    AND `tra`.`limit_date` >= `r_tra`.`limit_date`
                                                  GROUP BY `target_id`) AS `r` ON `tc`.`target_id` = `r`.`target_id`
                                          WHERE `tc`.`status` = 1
                                          GROUP BY `r`.`target_id`
                                         HAVING MAX(`tc`.`created_at`) = `tc`.`created_at`) AS `tr` ON `t`.`id` = `tr`.`target_id`
                                ) AS `trr` ON `c`.`user_id` = `trr`.`user_id` AND `c`.`product_id` = `trr`.`product_id`
                          WHERE `c`.`created_at` <= `trr`.`created_at`) AS `ttt`
                          GROUP BY `target_id`
                     ) AS `max_credit_result`
                JOIN (SELECT `ttt`.*
                        FROM (SELECT `c`.`level`, `c`.`created_at` AS `credit_time`, `trr`.`user_id`, `trr`.`target_id`, `trr`.`amount`, `trr`.`created_at`
                                FROM `p2p_loan`.`credits` `c`
                                JOIN (SELECT `t`.`user_id`, `t`.`product_id`, `tr`.*
                                        FROM `p2p_loan`.`targets` `t`
                                        JOIN (SELECT `tc`.`id`, `tc`.`created_at`, `r`.*
                                                FROM `p2p_log`.`targets_change_log` `tc`
                                                JOIN (SELECT `tra`.`target_id`, SUM(`tra`.`amount`) AS `amount`
                                                        FROM `p2p_transaction`.`transactions` AS `tra`
                                                        JOIN (SELECT `target_id`, `limit_date`
                                                                FROM `p2p_transaction`.`transactions`
                                                               WHERE `source` = ' . SOURCE_AR_DELAYINTEREST . '
                                                                 AND `status` IN (' . TRANSACTION_STATUS_TO_BE_PAID . ', ' . TRANSACTION_STATUS_PAID_OFF . ')
                                                                 AND `entering_date` <= "' . $end . '"
                                                               GROUP BY `target_id`) AS `r_tra` ON `tra`.`target_id` = `r_tra`.`target_id`
                                                       WHERE `source` = ' . SOURCE_AR_PRINCIPAL . '
                                                         AND `status` IN (' . TRANSACTION_STATUS_TO_BE_PAID . ', ' . TRANSACTION_STATUS_PAID_OFF . ')
                                                         AND `tra`.`limit_date` >= `r_tra`.`limit_date`
                                                       GROUP BY `target_id`) AS `r` ON `tc`.`target_id` = `r`.`target_id`
                                               WHERE `tc`.`status` = 1
                                               GROUP BY `r`.`target_id`
                                              HAVING MAX(`tc`.`created_at`) = `tc`.`created_at`) AS `tr` ON `t`.`id` = `tr`.`target_id`
                                     ) AS `trr` ON `c`.`user_id` = `trr`.`user_id` AND `c`.`product_id` = `trr`.`product_id`
                               WHERE `c`.`created_at` <= `trr`.`created_at`) AS `ttt`
                     ) AS `tttt` ON `max_credit_result`.`target_id` = `tttt`.`target_id`
               WHERE `max_credit_result`.`max_credit_time` = `tttt`.`credit_time`
             ) AS `r`,
             (SELECT SUM(`loan_amount`) AS `total_amount`
                FROM `p2p_loan`.`targets` 
               WHERE `status` IN (' . TARGET_REPAYMENTING . ', ' . TARGET_REPAYMENTED . ')
             ) AS `loan`
       GROUP BY `r`.`level`;';

        return $this->db->query($sql)->result_array();
    }

    /**
     * 取得案件每一期的「入帳日(entering_date)」與「還款期限(limit_date)」
     * @param $user_id
     * @return mixed
     */
    public function get_repayment_date($user_id)
    {
        $sub_query1 = $this->db
            ->select('id')
            ->from('p2p_loan.targets')
            ->where('user_id', $user_id)
            ->where_in('status', [TARGET_REPAYMENTED, TARGET_REPAYMENTING])
            ->get_compiled_select();

        $sub_query2 = $this->db->distinct()
            ->select('target_id')
            ->select('instalment_no')
            ->select('limit_date')
            ->from('p2p_transaction.transactions')
            ->where('source', SOURCE_AR_PRINCIPAL)
            ->get_compiled_select();

        return $this->db->distinct()
            ->select('t.entering_date')
            ->select('t.target_id')
            ->select('a.limit_date')
            ->from('p2p_transaction.transactions t')
            ->join("({$sub_query2}) a", 'a.target_id=t.target_id AND a.instalment_no=t.instalment_no')
            ->where("t.target_id IN ({$sub_query1})")
            ->where('t.source', SOURCE_PRINCIPAL)
            ->where('t.status', TRANSACTION_STATUS_PAID_OFF)
            ->get()
            ->result_array();
    }

    /**
     * 取得提前還款的案件清單
     * @param $user_id
     * @return mixed
     */
    public function get_prepayment_target($user_id)
    {
        $sub_query1 = $this->db
            ->select('id')
            ->from('p2p_loan.targets')
            ->where('user_id', $user_id)
            ->where_in('status', [TARGET_REPAYMENTED])
            ->get_compiled_select();

        return $this->db->distinct()
            ->select('target_id')
            ->from('p2p_loan.prepayment')
            ->where("target_id IN ({$sub_query1})")
            ->get()
            ->result_array();
    }

    /**
     * 取得正常結案的案件列表
     * @param $user_id
     * @return mixed
     */
    public function get_normal_repayment($user_id)
    {
        $sub_query1 = $this->db
            ->select('t.id')
            ->from('p2p_loan.targets t')
            ->join('p2p_transaction.transactions tr', 'tr.target_id=t.id AND tr.source=' . SOURCE_DELAYINTEREST)
            ->where('t.user_id', $user_id)
            ->where('t.status', TARGET_REPAYMENTED)
            ->get_compiled_select();

        return $this->db
            ->select('t2.id')
            ->from('p2p_loan.targets t2')
            ->where('t2.user_id', $user_id)
            ->where('t2.status', TARGET_REPAYMENTED)
            ->where("t2.id NOT IN ({$sub_query1})")
            ->get()
            ->result_array();
    }

    /**
     * 取得不同產品的總還款本金與利息
     * @param $user_id
     * @return mixed
     */
    public function get_repayment_amount($user_id)
    {
        return $this->db
            ->select('SUM(tr.amount) AS total_amount')
            ->select('t.product_id')
            ->from('p2p_transaction.transactions tr')
            ->join('p2p_loan.targets t', 't.id=tr.target_id')
            ->where('tr.user_from', $user_id)
            ->where_in('tr.source', [SOURCE_PRINCIPAL, SOURCE_INTEREST])
            ->group_by('t.product_id')
            ->get()
            ->result_array('array');
    }
}

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

    public $integer_fields = [
        'product_id',
        'amount',
        'loan_amount',
        'platform_fee',
        'instalment',
    ];

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
     * 依照放款時間篩選還款案件
     * @param int $userId
     * @param array $productIdList
     * @param int $filterTime
     * @return mixed
     */
    public function getRepaymentingTargets(int $userId, array $productIdList, int $filterTime=0) {
        $this->db->select('*')
            ->from("`p2p_loan`.`targets`")
            ->where('status', TARGET_REPAYMENTING)
            ->where('user_id', $userId)
            ->where_in('product_id', $productIdList);
        $subquery = $this->db->get_compiled_select('', TRUE);
        $this->db
            ->select('t.*, tra.created_at as loan_time')
            ->from('`p2p_transaction`.`transactions` AS `tra`')
            ->where('tra.source', SOURCE_LENDING)
            ->where('tra.user_to', 't.user_id', FALSE)
            ->join("($subquery) as `t`", "`t`.`id` = `tra`.`target_id`")
            ->group_by('t.id');
        if($filterTime)
            $this->db->where('tra.created_at < ', $filterTime);
        return $this->db->get()->result();
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

        return $this->db->get()->result_array();
    }

	public function get_delayed_report_by_investor($input)
	{
		//投資人ID/債權總額
		$subquery_investment = $this->db
			->select('target_id,user_id,amount,id')
			->where('status', 3)
			->get_compiled_select('p2p_loan.investments', TRUE);

		//學校/公司
		$subquery_user_meta_1 = $this->db
			->select("
				user_id,
				GROUP_CONCAT(meta_value SEPARATOR '/') AS user_meta_1
			")
			->where_in('meta_key', ['school_name', 'job_company'])
			->group_by('user_id')
			->get_compiled_select('p2p_user.user_meta');

		//科系
		$subquery_user_meta_2 = $this->db
			->select('user_id,meta_value')
			->where('meta_key', 'school_department')
			->group_by('user_id')
			->get_compiled_select('p2p_user.user_meta');

		//職位
		$subquery_user_meta_3 = $this->db
			->select('user_id,meta_value')
			->where('meta_key', 'job_position')
			->group_by('user_id')
			->get_compiled_select('p2p_user.user_meta');

		//逾期本金
		$subquery_unpaid_principal = $this->db
			->select('target_id')
            ->select('user_to')
			->select_sum('amount')
			->where(['status' => 1, 'source' => 11])
			->group_by('target_id,user_to')
			->get_compiled_select('p2p_transaction.transactions', TRUE);

		//逾期利息
		$subquery_unpaid_interest = $this->db
			->select('target_id')
            ->select('user_to')
			->select_sum('amount')
			->where(['status' => 1, 'source' => 13])
			->group_by('target_id,user_to')
			->get_compiled_select('p2p_transaction.transactions', TRUE);

		//延滯息
		$subquery_delay_interest = $this->db
			->select('target_id')
            ->select('user_to')
			->select_sum('amount')
			->where(['status' => 1, 'source' => 93])
			->group_by('target_id,user_to')
			->get_compiled_select('p2p_transaction.transactions', TRUE);

		$this->db
			->select('
				t.id,
				t.user_id,
				t.product_id,
				t.target_no,
				t.credit_level,
				t.loan_date,
				a1.amount AS invest_amount,
				a1.user_id AS lender,
				CONCAT(a1.target_id,"-",a1.id) AS ary_key,
				tr.entering_date,
				t.delay_days AS delayed_days,
				a6.user_meta_1,
				a7.meta_value AS school_department,
				a8.meta_value AS job_position
			')
            ->select('`tr`.`limit_date`')
			->from('p2p_loan.targets t')
			->join("($subquery_investment) a1", 'a1.target_id=t.id', 'left')
            ->join(
                'p2p_transaction.transactions tr',
                'tr.target_id=t.id AND tr.id=(
                    SELECT `id`
                      FROM `p2p_transaction`.`transactions` 
                     WHERE `status` IN ('.TRANSACTION_STATUS_DELETED.','.TRANSACTION_STATUS_TO_BE_PAID.','.TRANSACTION_STATUS_PAID_OFF.')
                       AND `source`='.SOURCE_AR_DELAYINTEREST.'
                       AND `target_id`=`tr`.`target_id`
                     ORDER BY `id` LIMIT 1)',
                'left',
                FALSE)
			->join("($subquery_user_meta_1) a6", 'a6.user_id=t.user_id', 'left')
			->join("($subquery_user_meta_2) a7", 'a7.user_id=t.user_id', 'left')
			->join("($subquery_user_meta_3) a8", 'a8.user_id=t.user_id', 'left')
			->where(['t.delay' => 1, 't.status' => 5])
			->order_by('t.target_no');

		foreach ($input as $key => $value) {
			switch ($key) {
				case 'tsearch': //搜尋(使用者代號(UserID)/姓名/身份證字號/案號)
					if (!empty($input['tsearch'])) {
						$this->db
							->join('p2p_user.users u', 'u.id=t.user_id')
							->group_start()
							->like('u.id', $input['tsearch'])
							->or_like('u.name', $input['tsearch'])
							->or_like('u.id_number', $input['tsearch'])
							->or_like('t.target_no', $input['tsearch'])
							->group_end();
					}
					break;
				case 'sdate': //從
					if (!empty($input['sdate'])) {
						$this->db->where('t.created_at >=', strtotime($input['sdate']));
					}
					break;
				case 'edate': //到
					if (!empty($input['edate'])) {
						$this->db->where('t.created_at <=', strtotime($input['edate']));
					}
					break;
			}
		}

		$target_rows = array_column($this->db->get()->result_array(), NULL, 'ary_key');
		$target_ids = array_column($target_rows, 'id');

        $this->db
            ->select('
                CONCAT(i.target_id,"-",i.id) AS ary_key,
                a3.amount AS unpaid_principal_by_investor,
                a4.amount AS unpaid_interest_by_investor,
                a5.amount AS delay_interest_by_investor
            ')
            ->from('p2p_loan.investments i')
            ->join("($subquery_unpaid_principal) a3", 'a3.target_id=i.target_id AND a3.user_to=i.user_id', 'left')
            ->join("($subquery_unpaid_interest) a4", 'a4.target_id=i.target_id AND a4.user_to=i.user_id', 'left')
            ->join("($subquery_delay_interest) a5", 'a5.target_id=i.target_id AND a5.user_to=i.user_id', 'left')
            ->where_in('i.target_id', $target_ids)
            ->where('i.status', INVESTMENT_STATUS_REPAYING);
		$transaction_rows = array_column($this->db->get()->result_array(), NULL, 'ary_key');
        $product_list = $this->config->item('product_list');
		$target_rows = array_map(function ($value) use ($product_list) {
			if (isset($value['school_department']) && !empty($value['school_department'])) {
				$value['user_meta_2'][] = $value['school_department'];
			}
			if (isset($value['job_position']) && empty($value['job_position']) && $value['job_position'] !== 0) {
				$position_name = $this->config->item('position_name');
				$value['user_meta_2'][] = $position_name[$value['job_position']] ?? '';
			}

			$value['user_meta_2'] = implode('/', $value['user_meta_2'] ?? []);
            $value['product_name'] = $product_list[$value['product_id']]['name'] ?? '';
			return $value;
		}, $target_rows);

		return array_replace_recursive($target_rows, $transaction_rows);
	}

    public function get_delayed_report_by_target($input)
    {
        // 學校/公司
        $subquery_user_meta_1 = $this->db
            ->select('user_id')
            ->select('GROUP_CONCAT(meta_value SEPARATOR "/") AS user_meta_1')
            ->where_in('meta_key', ['school_name', 'job_company'])
            ->group_by('user_id')
            ->get_compiled_select('p2p_user.user_meta');

        // 科系
        $subquery_user_meta_2 = $this->db
            ->select('user_id')
            ->select('meta_value')
            ->where('meta_key', 'school_department')
            ->group_by('user_id')
            ->get_compiled_select('p2p_user.user_meta');

        // 信評
        $subquery_credit = $this->db
            ->select('MAX(c.id) AS id')
            ->select('c.user_id')
            ->select('c.product_id')
            ->join('p2p_loan.targets t',
                'c.product_id=t.product_id AND
                 c.user_id=t.user_id AND
                 c.created_at<=(t.created_at+' . (TARGET_APPROVE_LIMIT * 86400) . ')')
            ->group_by('user_id,product_id')
            ->get_compiled_select('p2p_loan.credits c');
        $subquery_credit = $this->db
            ->select('c.amount')
            ->select('c.created_at')
            ->select('c.product_id')
            ->select('c.user_id')
            ->join("({$subquery_credit}) a", 'a.id=c.id AND a.user_id=c.user_id AND a.product_id=c.product_id')
            ->get_compiled_select('p2p_loan.credits c');

        // 尚欠違約金
        $subquery_unpaid_damage = $this->db
            ->select('target_id')
            ->select('user_from')
            ->select_sum('amount')
            ->where(['status' => 1, 'source' => 91])
            ->group_by('target_id,user_from')
            ->get_compiled_select('p2p_transaction.transactions', TRUE);

        // 尚欠利息
        $subquery_unpaid_interest = $this->db
            ->select('target_id')
            ->select('user_from')
            ->select_sum('amount')
            ->where(['status' => 1, 'source' => 13])
            ->group_by('target_id,user_from')
            ->get_compiled_select('p2p_transaction.transactions', TRUE);

        // 尚欠延滯息
        $subquery_unpaid_delayinterest = $this->db
            ->select('target_id')
            ->select('user_from')
            ->select_sum('amount')
            ->where(['status' => 1, 'source' => 93])
            ->group_by('target_id,user_from')
            ->get_compiled_select('p2p_transaction.transactions', TRUE);

        $this->db
            ->select('t.*')
            ->select('a6.user_meta_1')
            ->select('a7.meta_value AS school_department')
            ->select('a8.amount AS credit_amount')
            ->select('a8.created_at AS credit_created_at')
            ->select('a3.amount AS unpaid_damage')
            ->select('a4.amount AS unpaid_interest')
            ->select('a5.amount AS unpaid_delayinterest')
            ->from('p2p_loan.targets t')
            ->join("({$subquery_unpaid_damage}) a3", 'a3.user_from=t.user_id AND a3.target_id=t.id', 'left')
            ->join("({$subquery_unpaid_interest}) a4", 'a4.user_from=t.user_id AND a4.target_id=t.id', 'left')
            ->join("({$subquery_unpaid_delayinterest}) a5", 'a5.user_from=t.user_id AND a5.target_id=t.id', 'left')
            ->join("({$subquery_user_meta_1}) a6", 'a6.user_id=t.user_id', 'left')
            ->join("({$subquery_user_meta_2}) a7", 'a7.user_id=t.user_id', 'left')
            ->join("({$subquery_credit}) a8", 'a8.user_id=t.user_id AND a8.product_id=t.product_id', 'left')
            ->where('t.status', TARGET_REPAYMENTING)
            ->where('t.delay', 1);

        foreach ($input as $key => $value) {
            switch ($key) {
                case 'tsearch': //搜尋(使用者代號(UserID)/姓名/身份證字號/案號)
                    if (!empty($input['tsearch'])) {
                        $this->db
                            ->join('p2p_user.users u', 'u.id=t.user_id')
                            ->group_start()
                            ->like('u.id', $input['tsearch'])
                            ->or_like('u.name', $input['tsearch'])
                            ->or_like('u.id_number', $input['tsearch'])
                            ->or_like('t.target_no', $input['tsearch'])
                            ->group_end();
                    }
                    break;
                case 'sdate': //從
                    if (!empty($input['sdate'])) {
                        $this->db->where('t.created_at >=', strtotime($input['sdate']));
                    }
                    break;
                case 'edate': //到
                    if (!empty($input['edate'])) {
                        $this->db->where('t.created_at <=', strtotime($input['edate']));
                    }
                    break;
            }
        }

        return $this->db->get()->result_array();
    }

    /**
     * 撈取累積投資總金額
     * @return int
     */
    public function get_total_loan_amount()
    {
        $subquery_investment = $this->db
            ->select_sum('loan_amount')
            ->where_in('status', [
                INVESTMENT_STATUS_REPAYING,
                INVESTMENT_STATUS_PAID_OFF
            ])
            ->get_compiled_select('`p2p_loan`.`investments`', TRUE);

        $subquery_transfer = $this->db
            ->select_sum('amount')
            ->where('status', 10)
            ->get_compiled_select('`p2p_loan`.`transfers`', TRUE);

        $result = $this->db
            ->select('(`r1`.`loan_amount` + `r2`.`amount`) AS total_loan_amount')
            ->from("({$subquery_investment}) `r1`")
            ->from("({$subquery_transfer}) `r2`")
            ->get()
            ->first_row('array');

        return (int) ($result['total_loan_amount'] ?? 0);
    }

    /**
     * 撈取總交易(成交)筆數
     * @return int
     */
    public function get_transaction_count()
    {
        $result = $this->db
            ->select('((((`r1`.`c` + (`r2`.`c` * 2)) + `r3`.`c`) + `r4`.`c`) + (`r5`.`c` * 2)) AS transaction_count')
            ->from('(SELECT COUNT(1) as c FROM p2p_loan.investments WHERE `status` = ' . INVESTMENT_STATUS_REPAYING . ' ) `r1`')
            ->from('(SELECT COUNT(1) as c FROM p2p_loan.investments WHERE `status` = ' . INVESTMENT_STATUS_PAID_OFF . ' ) `r2`')
            ->from('(SELECT COUNT(1) as c FROM p2p_loan.transfers WHERE `status` = 10 ) `r3`')
            ->from('(SELECT COUNT(1) as c FROM p2p_loan.targets WHERE `status` = ' . TARGET_REPAYMENTING . ' ) `r4`')
            ->from('(SELECT COUNT(1) as c FROM p2p_loan.targets WHERE `status` = ' . TARGET_REPAYMENTED . ' ) `r5`')
            ->get()
            ->first_row('array');

        return (int) ($result['transaction_count'] ?? 0);
    }

    /** 依targets.status取得不重複的使用者ID
     * @param array $status
     * @param int $company_Status : 是否為法人 (p2p_user.users.company_status)
     * @return mixed
     */
    public function get_distinct_user_by_status(array $status, int $company_Status = 0)
    {
        $this->db
            ->select('DISTINCT(targets.user_id) AS user_id')
            ->from('p2p_loan.targets')
            ->join('p2p_user.users', 'users.id=targets.user_id AND users.company_status=' . $company_Status)
            ->where_in('targets.status', $status);

        return $this->db->get()->result_array();
    }

    public function get_apply_target_count($where)
    {
        $this->_database->select('user_id, COUNT(*) as total_count')
            ->from("`p2p_loan`.`targets`")
            ->group_by('user_id');
        if ( ! empty($where))
        {
            $this->_set_where([0 => $where]);
        }

        return $this->_database->get()->result_array();
    }

    public function get_apply_frequent($where)
    {
        $this->_database->select('user_id, MIN(id) as first_id, MAX(id) as last_id')
            ->from("`p2p_loan`.`targets`")
            ->group_by('user_id');
        if ( ! empty($where))
        {
            $this->_set_where([0 => $where]);
        }

        return $this->_database->get()->result_array();
    }

    public function get_banned_list($where)
    {
        $this->_database->select('user_id, COUNT(*) as total_count')
            ->from("`p2p_loan`.`targets`")
            ->like('remark', '經AI')
            ->group_by('user_id');
        if ( ! empty($where))
        {
            $this->_set_where([0 => $where]);
        }

        return $this->_database->get()->result_array();
    }

    public function get_failed_target(int $user_id)
    {
        $subqery = $this->db
            ->select('MAX(id)')
            ->from('p2p_loan.targets')
            ->where('user_id', $user_id)
            ->group_start()
                ->where('status', TARGET_FAIL)
                ->or_group_start()
                    ->where('status', TARGET_WAITING_APPROVE)
                    ->where('certificate_status', TARGET_CERTIFICATE_SUBMITTED)
                ->group_end()
            ->group_end()
            ->group_by('product_id')
            ->group_by('sub_product_id')
            ->get_compiled_select(NULL, TRUE);

        $this->db
            ->select('t.id')
            ->select('t.target_no')
            ->select('t.product_id')
            ->select('t.sub_product_id')
            ->select('t.user_id')
            ->select('t.remark')
            ->select('t.created_at')
            ->select('t.status')
            ->select('t.certificate_status')
            ->from('p2p_loan.targets t')
            ->where("t.id IN ($subqery)");

        return $this->db->get()->result_array();
    }

    /**
     * @param int $investor : 投資人/借款人
     * @param array $cert_status : 徵信項狀態 (參考constant(CERTIFICATION_STATUS_*))
     * @param int $product_id : 產品ID
     * @param $has_stage_target : 是否撈取階段上架的相關申貸案 (TRUE=只撈階段上架 FALSE=不撈取階段上架 NULL=不管是不是階段上架都撈)
     * @return mixed
     */
    public function get_risk_person_list(int $investor, array $cert_status, int $product_id, $has_stage_target = NULL)
    {
        $subquery = $this->db
            ->select('DISTINCT(user_id) AS user_id')
            ->from('p2p_user.user_certification uc')
            ->where_in('uc.status', $cert_status)
            ->where('uc.investor', $investor)
            ->get_compiled_select(NULL, TRUE);

        $this->db
            ->select('t.*')
            ->from('p2p_loan.targets t')
            ->join("({$subquery}) AS a", 'a.user_id=t.user_id')
            ->where('t.product_id<', PRODUCT_FOREX_CAR_VEHICLE)
            ->where_in('t.status', [
                TARGET_WAITING_APPROVE,
                TARGET_WAITING_SIGNING,
                TARGET_WAITING_VERIFY,
                TARGET_ORDER_WAITING_SIGNING,
                TARGET_ORDER_WAITING_VERIFY
            ])
            ->where('t.product_id', $product_id)
            ->order_by('t.id', 'ASC');

        if ($has_stage_target === FALSE)
        {
            $this->db->where('t.sub_product_id !=', STAGE_CER_TARGET);
        }
        elseif ($has_stage_target === TRUE)
        {
            $this->db->where('t.sub_product_id', STAGE_CER_TARGET);
        }

        return $this->db->get()->result();
    }

    /**
     * @param int $user_id
     * @param array $target_status
     * @param array $prod_subprod_id : 產品ID
     * [
     *     product_id => [sub_product_id],
     *     1 => [0, 6, 9999],
     *     3 => [0, 9999]
     * ]
     * @return mixed
     */
    public function get_by_multi_product(int $user_id, array $target_status, array $prod_subprod_id)
    {
        $this->db
            ->select('id, status, sub_status')
            ->from('p2p_loan.targets')
            ->where('user_id', $user_id)
            ->where_in('status', $target_status);

        if ($prod_subprod_id)
        {
            $this->db->group_start();
            foreach ($prod_subprod_id as $key => $value)
            {
                $this->db
                    ->or_group_start()
                    ->where('product_id', $key)
                    ->where_in('sub_product_id', $value)
                    ->group_end();

            }
            $this->db->group_end();
        }

        return $this->db->get()->result_array();
    }

    /**
     * 撈取案件未結算交易科目
     * @param integer $userId 投資人使用者編號
     * @param boolean $isDelay 是否逾期
     * @param array $sourceList 科目代號
     * @param array $product_id_list 產品編號列表
     * @param boolean $isGroup 是否
     * @return array
     */
    public function getTransactionSourceByInvestor(int $userId = 0, bool $isDelay = FALSE, array $sourceList = [], array $product_id_list = [], bool $isGroup = FALSE): array
    {
        $this->db->select('source, status, target_id, user_to, amount')
            ->from("`p2p_transaction`.`transactions`")
            ->where('status', TRANSACTION_STATUS_TO_BE_PAID)
            ->where('user_to', $userId);
        if ( ! empty($sourceList))
        {
            $this->db->where_in('source', $sourceList);
        }

        $sub_query = $this->db->get_compiled_select('', TRUE);
        $logic = $isDelay ? '>' : '<=';
        $this->db->select('t.product_id, SUM(tra.amount) as amount')
            ->from('`p2p_loan`.`targets` AS `t`')
            ->join("({$sub_query}) as `tra`", "`t`.`id` = `tra`.`target_id`")
            ->where("t.delay_days {$logic}", GRACE_PERIOD);
        if ( ! empty($product_id_list))
        {
            $this->db->where_in('t.product_id', $product_id_list);
        }
        if ($isGroup)
        {
            $this->db->group_by('t.product_id');
        }

        return $this->db->get()->result_array();
    }

    // 取得指定日子申貸的案件數量
    public function get_loan_targets_at_day(DateTimeInterface $date)
    {
        $month_ini = $date->modify('first day of this month');
        $month_end = $date->modify('first day of next month');
        $month_ini = $month_ini->setTime(0, 0, 0)->getTimestamp();
        $month_end = $month_end->setTime(0, 0, 0)->getTimestamp();

        $sub_query = "SELECT
            t.`id`,
            t.`user_id`,
            t.`product_id`,
            t.`sub_product_id`,
            min(t.`created_at`) as `first_target_at`
            FROM `p2p_loan`.`targets` t LEFT JOIN `p2p_loan`.`subloan` s ON s.`new_target_id` = t.`id`
            WHERE s.id is NULL
            AND t.`created_at` >= {$month_ini}
            AND t.`created_at` < {$month_end}
            GROUP BY t.`user_id`";

        $this->load->model('loan/target_model');
        $loan_targets = $this->target_model->db->select([
            'id',
            'user_id',
            'product_id',
            'sub_product_id',
            'first_target_at',
        ])->from("({$sub_query}) as r")
            ->where([
                'first_target_at >=' => $date->getTimestamp(),
                'first_target_at <' => $date->modify('+1 day')->getTimestamp(),
            ])
            ->get()
            ->result_array();

        return $this->_list_products_at_targets($loan_targets);
    }

    // 取得指定日子成交的案件數量
    public function get_deal_targets_at_day(DateTimeInterface $date)
    {
        $this->db->select('id, product_id, sub_product_id');
        $this->db->from('p2p_loan.targets');
        $this->db->where_in('status', [TARGET_REPAYMENTING, TARGET_REPAYMENTED, TARGET_BANK_REPAYMENTING, TARGET_BANK_REPAYMENTED]);
        $this->db->where([
            'loan_status' => 1,
            'loan_date' => $date->format('Y-m-d'),
        ]);

        $deal_targets = $this->db->get()->result_array();

        return $this->_list_products_at_targets($deal_targets);
    }

    // 統計各種案件數量，針對申貸&成交都可以用
    private function _list_products_at_targets($targets)
    {
        $subloan_target_ids = [];
        if ( ! empty($targets))
        {
            $this->db->select('new_target_id')
                ->from('p2p_loan.subloan')
                ->where_in('new_target_id', array_column($targets, 'id'));
            $rs = $this->db->get()->result_array();
            if ( ! empty($rs))
            {
                $subloan_target_ids = array_column($rs, 'new_target_id');
            }
        }

        $result = [
            'SMART_STUDENT' => 0,
            'STUDENT' => 0,
            'SALARY_MAN' => 0,
            'SK_MILLION' => 0, // 微企貸沒有出現在 匯出的 excel 裡面
            'CREDIT_INSURANCE' => 0, // TODO 當信保專案上線後，需要在下方新增他的 case
            'SME' => 0, // TODO 當中小企業上線後，需要在下方新增他的 case
        ];

        foreach ($targets as $target)
        {
            // 產轉的案件不能計算進來
            if ( ! empty($subloan_target_ids) && in_array($target['id'], $subloan_target_ids))
            {
                continue;
            }

            switch (TRUE)
            {
            case $target['product_id'] == PRODUCT_ID_STUDENT && $target['sub_product_id'] == SUBPRODUCT_INTELLIGENT_STUDENT:
                $result['SMART_STUDENT'] += 1;
                break;

            case $target['product_id'] == PRODUCT_ID_STUDENT:
                $result['STUDENT'] += 1;
                break;

            case $target['product_id'] == PRODUCT_ID_SALARY_MAN:
                $result['SALARY_MAN'] += 1;
                break;

            case $target['product_id'] == PRODUCT_SK_MILLION_SMEG:
                $result['SK_MILLION'] += 1;
                break;
            }
        }

        return $result;
    }

    public function chk_exist_by_status($condition): bool
    {
        $this->_database
            ->from('`p2p_loan`.`targets`');
        if ( ! empty($condition))
        {
            $this->_set_where([0 => $condition]);
        }
        if ( ! empty($this->_database->get()->first_row()))
        {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 取得案件詳細相關資訊
     * @param $conditions
     * @return array
     */
    public function get_targets_detail($conditions): array
    {
        if ( ! empty($conditions))
        {
            $this->_set_where([$conditions]);
        }

        $this->_database->select('*')
            ->from('`p2p_loan`.`targets`');
        $target_query = $this->_database->get_compiled_select('', TRUE);

        $rs = $this->_database
            ->select('`r`.*, `c`.`level`, `c`.`points`, `c`.`amount` AS `credit_amount`, `c`.`status` AS `credit_status`')
            ->select('`c`.`expire_time` AS `credit_expire_time`, `c`.`created_at` AS `credit_created_at`')
            ->select('`cs`.`unused_credit_line`')
            ->from('`p2p_loan`.`credit_sheet` AS `cs`')
            ->join("($target_query) as `r`", '`cs`.`target_id` = `r`.`id`', 'right')
            ->join('`p2p_loan`.`credits` AS `c`', '`c`.`id` = `cs`.`credit_id`', 'left');

        return $rs->get()->result_array();
    }

    /**
     * 依使用者 id 取得二審案件資料
     * @param $user_id : 使用者id
     * @return mixed
     */
    public function get_second_instance_targets_by_user($user_id)
    {
        return $this->db
            ->select(['id', 'target_data'])
            ->from('p2p_loan.targets')
            ->where('user_id', $user_id)
            ->where('status', TARGET_WAITING_APPROVE)
            ->where('sub_status', TARGET_SUBSTATUS_SECOND_INSTANCE)
            ->where('product_id <', PRODUCT_FOR_JUDICIAL)
            ->where('script_status', 0)
            ->get()
            ->result_array();
    }
}

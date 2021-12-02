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

	public function getDelayedReport($input)
	{
		//投資人ID/債權總額
		$subquery_investment = $this->db
			->select('target_id,user_id,amount')
			->where('status', 3)
			->get_compiled_select('p2p_loan.investments', TRUE);

		//首逾日期
		$subquery_transaction = $this->db
			->select('target_id,entering_date')
			->where(['status' => 1, 'source' => 93])
			->group_by('target_id')
			->get_compiled_select('p2p_transaction.transactions', TRUE);

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
			->select_sum('amount')
			->where(['status' => 1, 'source' => 11])
			->group_by('target_id')
			->get_compiled_select('p2p_transaction.transactions', TRUE);

		//逾期利息
		$subquery_unpaid_interest = $this->db
			->select('target_id')
			->select_sum('amount')
			->where(['status' => 1, 'source' => 13])
			->group_by('target_id')
			->get_compiled_select('p2p_transaction.transactions', TRUE);

		//延滯息
		$subquery_delay_interest = $this->db
			->select('target_id')
			->select_sum('amount')
			->where(['status' => 1, 'source' => 93])
			->group_by('target_id')
			->get_compiled_select('p2p_transaction.transactions', TRUE);

		$this->db
			->select('
				t.id,
				t.user_id,
				t.product_id,
				p.name AS product_name,
				t.target_no,
				t.credit_level,
				t.loan_date,
				a1.amount AS invest_amount,
				a1.user_id AS lender,
				a2.entering_date,
				DATEDIFF(NOW(), a2.entering_date) AS delayed_days,
				a6.user_meta_1,
				a7.meta_value AS school_department,
				a8.meta_value AS job_position
			')
			->from('p2p_loan.targets t')
			->join('p2p_loan.products p', 'p.id=t.product_id')
			->join("($subquery_investment) a1", 'a1.target_id=t.id', 'left')
			->join("($subquery_transaction) a2", 'a2.target_id=t.id', 'left')
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

		$target_rows = array_column($this->db->get()->result_array(), NULL, 'id');
		$target_ids = array_column($target_rows, 'id');

		$this->db
			->select('
				t.id,
				a3.amount AS unpaid_principal,
				a4.amount AS unpaid_interest,
				a5.amount AS delay_interest
			')
			->from('p2p_loan.targets t')
			->join("($subquery_unpaid_principal) a3", 'a3.target_id=t.id', 'left')
			->join("($subquery_unpaid_interest) a4", 'a4.target_id=t.id', 'left')
			->join("($subquery_delay_interest) a5", 'a5.target_id=t.id', 'left')
			->where_in('t.id', $target_ids);
		$transaction_rows = array_column($this->db->get()->result_array(), NULL, 'id');

		$target_rows = array_map(function ($value) {
			if (isset($value['school_department']) && !empty($value['school_department'])) {
				$value['user_meta_2'][] = $value['school_department'];
			}
			if (isset($value['job_position']) && empty($value['job_position']) && $value['job_position'] !== 0) {
				$position_name = $this->config->item('position_name');
				$value['user_meta_2'][] = $position_name[$value['job_position']] ?? '';
			}

			$value['user_meta_2'] = implode('/', $value['user_meta_2']);
			return $value;
		}, $target_rows);

		return array_replace_recursive($target_rows, $transaction_rows);
	}
}

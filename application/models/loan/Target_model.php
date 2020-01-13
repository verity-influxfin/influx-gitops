<?php

class Target_model extends MY_Model
{
	public $_table = 'targets';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );
	public $status_list   = array(
		0 =>	"待核可",
		1 =>	"待簽約",
		2 =>	"待驗證",
		3 =>	"待出借",
		4 =>	"待放款（結標）",
		5 =>	"還款中",
		8 =>	"已取消",
		9 =>	"申請失敗",
		10 =>	"已結案",
        20 =>	"待報價 (分期)",
        21 =>	"待簽約 (分期)",
        22 =>	"待驗證 (分期)",
        23 =>	"待審批 (分期)",
        24 =>	"待債轉上架 (分期)",
	);
	public $sub_list   = array(
		0 =>	"無",
		1 =>	"轉貸中",
		2 =>	"轉貸成功",
		3 =>	"申請提還",
		4 =>	"提還成功",
        5 =>	"已通知出貨",
        6 =>	"出貨鑑賞期",
 
        8 =>	"產轉案件",
        9 =>	"待二審",
 
		
		20 =>	"交易成功(系統自動)",
        21 =>    "需轉人工",
  
	);

	public $loan_list   = array(
		0 =>	"無",
		1 =>	"已出款",
		2 =>	"待出款",
		3 =>	"出款中",
	);
	
	public $delay_list   = array(
		0 =>	"無",
		1 =>	"逾期中",
	);

    public $delivery_list   = array(
        0 =>	"線下",
        1 =>	"線上",
    );
	
	public $simple_fields  = array(
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
	
	public $detail_fields  = array(
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
		$this->_database = $this->load->database('loan',TRUE);
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

    public function getApplicationCountByStatus($status = [], $createdRange = [])
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

        $this->db->group_by('status, product_id, sub_product_id');
        $query = $this->db->get();

        return $query->result();
    }

    public function getApplicationAmountByStatus($status = [], $createdRange = [])
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

        $this->db->group_by('status, product_id, sub_product_id');
        $query = $this->db->get();

        return $query->result();
    }
}

<?php
// 法催扣繳

class Deduct_model extends MY_Model
{
    public $_table = 'deduct';
    public $before_create = ['before_data_c'];
    public $before_update = ['before_data_u'];

    // 扣繳狀態
    public $status_list = [
        DEDUCT_STATUS_DEFAULT => '應付',
        DEDUCT_STATUS_CONFIRM => '已付',
        DEDUCT_STATUS_CANCEL => '註銷'
    ];

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
     * 依「投資人ID」、「代支日期」取得法催扣繳列表資料
     * @param int $user_id : 投資人ID
     * @param $created_at_s : 代支日期開始 (格式: Y-m-d, e.g. 2021-12-01)
     * @param $created_at_e : 代支日期結束 (格式: Y-m-d, e.g. 2021-12-31)
     * @return mixed
     */
    public function get_deduct_list(int $user_id, $created_at_s, $created_at_e)
    {
        $this->db
            ->select('d.id')
            ->select('d.user_id')
            ->select('d.amount')
            ->select('d.reason')
            ->select('d.created_at')
            ->select('d.status')
            ->select('d.updated_at')
            ->select('d.cancel_reason')
            ->select('a.name AS admin')
            ->select('b.name AS updated_admin')
            ->from('deduct d')
            ->join('p2p_admin.admins a', 'a.id=d.created_admin_id')
            ->join('p2p_admin.admins b', 'b.id=d.updated_admin_id');

        // 投資人ID
        if ( ! empty($user_id))
        {
            $this->db->where('d.user_id', $user_id);
        }

        // 代支日期 開始
        if ( ! empty($created_at_s))
        {
            $this->db->where('d.created_at >=', $created_at_s);
        }

        // 代支日期 結束
        if ( ! empty($created_at_e))
        {
            $this->db->where('d.created_at <=', $created_at_e);
        }

        return $this->db->get()->result_array();
    }

    /**
     * 依「投資人ID」取得投資人姓名、虛擬帳戶餘額
     * @param int $user_id : 投資人ID
     * @return mixed
     */
    public function get_deduct_user_info(int $user_id)
    {
        $subquery_virtual_account = $this->db
            ->select('IFNULL(SUM(vp.amount),0) AS account_amount')
            ->from('p2p_transaction.virtual_passbook vp')
            ->where('EXISTS ( SELECT 1 
                                FROM users u, virtual_account va 
                               WHERE u.id=va.user_id 
                                 AND va.virtual_account=vp.virtual_account
                                 AND va.virtual_account LIKE \''.CATHAY_VIRTUAL_CODE.INVESTOR_VIRTUAL_CODE.'%\'
                                 AND va.status='.VIRTUAL_ACCOUNT_STATUS_AVAILABLE.'
                                 AND va.user_id='.$user_id.'
                                 AND va.investor='.INVESTOR.' )')
            ->group_by('vp.virtual_account')
            ->get_compiled_select('', TRUE);

        $subquery_frozen_amount = $this->db
            ->select('(IFNULL(SUM(fa.amount),0)*-1) AS account_amount')
            ->from('p2p_transaction.frozen_amount fa')
            ->where('EXISTS ( SELECT 1
                                FROM users u, virtual_account va
                               WHERE u.id=va.user_id
                                 AND va.virtual_account=fa.virtual_account
                                 AND va.virtual_account LIKE \''.CATHAY_VIRTUAL_CODE.INVESTOR_VIRTUAL_CODE.'%\'
                                 AND va.status='.VIRTUAL_ACCOUNT_STATUS_AVAILABLE.'
                                 AND va.user_id='.$user_id.'
                                 AND va.investor='.INVESTOR.' )')
            ->where('fa.status', 1)
            ->group_by('fa.virtual_account')
            ->get_compiled_select('', TRUE);

        $this->db
            ->select('u.name AS user_name')
            ->select_sum('a.account_amount')
            ->from("({$subquery_virtual_account} UNION {$subquery_frozen_amount}) a")
            ->from('users u')
            ->where('u.id', $user_id);

        return $this->db->get()->first_row('array');
    }

    /**
     * 依「法催扣繳ID」取得該筆資料
     * @param int $id : 法催扣繳ID
     * @return mixed
     */
    public function get_deduct_info(int $id)
    {
        $subquery_virtual_account = $this->db
            ->select('IFNULL(SUM(vp.amount),0) AS account_amount')
            ->from('p2p_transaction.virtual_passbook vp')
            ->where('EXISTS ( SELECT 1 FROM deduct d, users u, virtual_account va
                               WHERE d.user_id=u.id
                                 AND u.id=va.user_id
                                 AND va.virtual_account=vp.virtual_account
                                 AND va.virtual_account LIKE \''.CATHAY_VIRTUAL_CODE.INVESTOR_VIRTUAL_CODE.'%\'
                                 AND va.status='.VIRTUAL_ACCOUNT_STATUS_AVAILABLE.'
                                 AND va.investor='.INVESTOR.'
                                 AND d.id='.$id.' )')
            ->group_by('vp.virtual_account')
            ->get_compiled_select('', TRUE);

        $subquery_frozen_amount = $this->db
            ->select('(IFNULL(SUM(fa.amount),0)*-1) AS account_amount')
            ->from('p2p_transaction.frozen_amount fa')
            ->where('EXISTS ( SELECT 1 FROM deduct d, users u, virtual_account va
                               WHERE d.user_id=u.id
                                 AND u.id=va.user_id
                                 AND va.virtual_account=fa.virtual_account
                                 AND va.virtual_account LIKE \''.CATHAY_VIRTUAL_CODE.INVESTOR_VIRTUAL_CODE.'%\'
                                 AND va.status='.VIRTUAL_ACCOUNT_STATUS_AVAILABLE.'
                                 AND va.investor='.INVESTOR.'
                                 AND d.id='.$id.' )')
            ->where('fa.status', 1)
            ->group_by('fa.virtual_account')
            ->get_compiled_select('', TRUE);

        $this->db
            ->select('d.id')
            ->select('d.user_id')
            ->select('u.name AS user_name')
            ->select_sum('a.account_amount')
            ->select('d.reason AS deduct_reason')
            ->select('d.amount AS deduct_amount')
            ->from("({$subquery_virtual_account} UNION {$subquery_frozen_amount}) a")
            ->from('deduct d')
            ->join('users u', 'u.id=d.user_id')
            ->where('d.id', $id);

        return $this->db->get()->first_row('array');
    }

    /**
     * 依「虛擬帳戶」取得「扣繳金額」、「虛擬帳戶餘額」
     * @param $id
     * @param $virtual_account : 虛擬帳戶
     * @return mixed
     */
    public function get_deduct_and_virtual_amount($id, $virtual_account)
    {
        $subquery_virtual_account = $this->db
            ->select('IFNULL(SUM(vp.amount),0) AS account_amount')
            ->from('p2p_transaction.virtual_passbook vp')
            ->where('vp.virtual_account', $virtual_account)
            ->get_compiled_select('', TRUE);

        $subquery_frozen_amount = $this->db
            ->select('(IFNULL(SUM(fa.amount),0)*-1) AS account_amount')
            ->from('p2p_transaction.frozen_amount fa')
            ->where('fa.virtual_account', $virtual_account)
            ->where('fa.status', 1)
            ->get_compiled_select('', TRUE);

        $this->db
            ->select('d.id')
            ->select('d.user_id')
            ->select('d.transaction_id')
            ->select('u.name AS user_name')
            ->select_sum('a.account_amount')
            ->select('d.reason AS deduct_reason')
            ->select('d.amount AS deduct_amount')
            ->from("({$subquery_virtual_account} UNION {$subquery_frozen_amount}) a")
            ->from('deduct d')
            ->join('users u', 'u.id=d.user_id')
            ->join('virtual_account va', 'va.user_id=u.id')
            ->where('va.virtual_account', $virtual_account)
            ->where('d.id', $id);

        return $this->db->get()->first_row('array');
    }

    /**
     * 依「法催扣繳ID」取得「內帳交易ID」
     * @param int $id : 法催扣繳ID
     * @return mixed
     */
    public function get_transaction_id_by_deduct_id(int $id)
    {
        $result = $this->db
            ->select('transaction_id')
            ->from($this->_table)
            ->where('id', $id)
            ->get()
            ->first_row('array');

        return $result['transaction_id'] ?? 0;
    }

    /**
     * 依「法催扣繳ID」取得「使用者ID」
     * @param int $id : 法催扣繳ID
     * @return int|mixed
     */
    public function get_user_id_by_deduct_id(int $id)
    {
        $result = $this->db
            ->select('user_id')
            ->from($this->_table)
            ->where('id', $id)
            ->get()
            ->first_row('array');

        return $result['user_id'] ?? 0;
    }
}
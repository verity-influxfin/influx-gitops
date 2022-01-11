<?php


class Deduct_model extends MY_Model
{
    public $_table = 'deduct';
    public $before_create = ['before_data_c'];
    public $before_update = ['before_data_u'];
    public $status_lsit = [
        1 => '應付',
        2 => '已付',
        3 => '註銷'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->load->database('default', TRUE);
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

    /**
     * 依「投資人ID」、「代支日期」取得法催扣繳列表資料
     * @param int $user_id : 投資人ID
     * @param $created_at_s : 代支日期開始
     * @param $created_at_e : 代支日期結束
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
            ->from('deduct d')
            ->join('p2p_admin.admins a', 'a.id=d.created_admin_id');

        // 投資人ID
        if ( ! empty($user_id))
        {
            $this->db->where('d.user_id LIKE', "%{$user_id}%");
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
        $subquery = $this->db
            ->select_sum('vp.amount')
            ->select('vp.virtual_account')
            ->from('p2p_transaction.virtual_passbook vp')
            ->group_by('vp.virtual_account')
            ->get_compiled_select('', TRUE);

        $this->db
            ->select('u.name AS user_name')
            ->select('a.amount as account_amount')
            ->from('users u')
            ->join('virtual_account va', 'va.user_id=u.id')
            ->join("({$subquery}) a", 'a.virtual_account=va.virtual_account', 'left')
            ->where('u.id', $user_id)
            ->where('va.investor', 1); //------------------

        return $this->db->get()->first_row('array');
    }

    /**
     * 取得法催扣繳資料
     * @param int $id : 法催扣繳ID
     * @return mixed
     */
    public function get_deduct_info(int $id)
    {
        $subquery = $this->db
            ->select_sum('vp.amount')
            ->select('vp.virtual_account')
            ->from('p2p_transaction.virtual_passbook vp')
            ->group_by('vp.virtual_account')
            ->get_compiled_select('', TRUE);

        $this->db
            ->select('d.id')
            ->select('d.user_id')
            ->select('u.name AS user_name')
            ->select('IFNULL(a.amount,0) AS account_amount')
            ->select('d.reason AS deduct_reason')
            ->select('d.amount AS deduct_amount')
            ->from('deduct d')
            ->join('users u', 'u.id=d.user_id')
            ->join('virtual_account va', 'va.user_id=u.id')
            ->join("({$subquery}) a", 'a.virtual_account=va.virtual_account', 'left');

        return $this->db->get()->first_row('array');
    }
}
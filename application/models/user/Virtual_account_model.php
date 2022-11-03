<?php

class Virtual_account_model extends MY_Model
{
    public $_table = 'virtual_account';
    public $before_create = array( 'before_data_c' );
//	public $before_update = array( 'before_data_u' );
    public $investor_list  	= array(
        0 =>	"借款端",
        1 =>	"出借端",
    );

    public $status_list  	= array(
        0 =>	"凍結中",
        1 =>	"正常",
        2 =>	"使用中",
    );

    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->load->database('default',TRUE);
    }

    protected function before_data_c($data)
    {
        $data['created_at'] = time();
        $data['created_ip'] = get_ip();
        return $data;
    }

    /**
     * 設定虛擬帳號的使用狀態 (成功:回傳虛擬帳號的物件/不成功:回傳空陣列)
     * @param $user_id
     * @param $investor
     * @param $old_status
     * @param $new_status
     * @param $virtual_prefix: 虛擬帳號前綴數字
     * @return array|stdClass
     */
    public function setVirtualAccount($user_id, $investor, $old_status, $new_status, $virtual_prefix) {
        $conditions = [
            'user_id' => $user_id,
            'investor' => $investor,
            'status' => $old_status,
            'virtual_account like ' => $virtual_prefix.'%'
        ];

        $this->db->trans_start();
        $this->db->select_for_update('*')->where($conditions);
        $virtual_account = $this->db->get($this->_table)->result();

        if(is_array($virtual_account) && !empty($virtual_account)) {
            $virtual_account = $virtual_account[0];
            $result = $this->db->where(['id' => $virtual_account->id])
                ->set(['status' => $new_status])
                ->update($this->_table);

            // 更新失敗需回傳 empty array
            if($this->db->affected_rows() > 0) {
                $virtual_account->status = $new_status;
            }else{
                $virtual_account = [];
            }
        }
        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE) {

            $virtual_account = [];
        }

        return $virtual_account;
    }

    public function getDelayedVirtualAccountList($investor = 0, $delayedDay = GRACE_PERIOD){
        $this->db
            ->distinct()
            ->select('user_id')
            ->from("`p2p_loan`.`targets`")
            ->where(['delay_days >' => $delayedDay, 'status' => 5])
            ->group_by('user_id');
        $subquery = $this->db->get_compiled_select('', TRUE);
        $this->db
            ->select('va.user_id, va.virtual_account')
            ->from('`p2p_user`.`virtual_account` AS `va`')
            ->where('investor', $investor)
            ->where('status', 1)
            ->join("($subquery) as `r`", "`va`.`user_id` = `r`.`user_id`");

        return $this->db->get()->result_array();
    }

    /**
     * 依使用者ID取得「投資人身份」的虛擬帳戶號碼
     * @param int $user_id : 使用者ID
     * @return int|mixed
     */
    public function get_investor_account_by_user_id(int $user_id)
    {
        $result = $this->db
            ->select('va.virtual_account')
            ->from('virtual_account va')
            ->where('va.investor', INVESTOR)
            ->where('va.user_id', $user_id)
            ->get()
            ->first_row('array');

        return $result['virtual_account'] ?? 0;
    }

    /**
     * 取得用戶虛擬帳戶
     *
     * @param      int     $user_id   用戶 ID
     * @param      int     $investor  投資人 (0:借款|1:投資)
     * @return     string             虛擬帳戶
     *
     * @created_at                    2022-03-04
     * @created_by                    Jack
     */
    public function get_virtual_account_by_user(int $user_id, int $investor)
    {
        $result = $this->db
            ->select('va.virtual_account')
            ->from('virtual_account va')
            ->where([
                'va.user_id'  => $user_id,
                'va.investor' => $investor,
                'va.status'   => VIRTUAL_ACCOUNT_STATUS_AVAILABLE
            ])
            ->get()
            ->first_row('array');
        return $result['virtual_account'] ?? 0;
    }

    /**
     * Get the latest available investor virtual account.
     * @param int $user_id
     * @return string NULL if there is no matching data
     */
    public function get_valid_investor_account(int $user_id): string
    {
        $result = $this->db
            ->select('va.virtual_account')
            ->from('virtual_account va')
            ->where('va.investor', INVESTOR)
            ->where('va.user_id', $user_id)
            ->where('va.status', VIRTUAL_ACCOUNT_STATUS_AVAILABLE)
            ->order_by('va.created_at', 'DESC')
            ->get()
            ->first_row('array');

        return $result['virtual_account'];
    }
}

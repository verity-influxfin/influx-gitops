<?php

class Loan_manager_target_model extends MY_Model
{
    public $_table = 'roles';
    public $before_create = array('before_data_c');
    public $before_update = array('before_data_u');
    private $status_list = array(
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
        9 => "待二審",
        10 => "二審過件",

        20 => "交易成功(系統自動)",
        21 => "需轉人工",

    );

    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->load->database('loan_manager', TRUE);
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

    public function get_target_list($select, $where = [], $orWhere = [],  $offset = 0, $limit = false, $order_by = false)
    {
        is_array($select) ? $select = implode(',', $select) : '*';

        $this->db->select($select,false)
            ->from('p2p_user.users as user');
        $this->db->join(
            'p2p_loan.targets as target',
            'target.user_id = user.id',
            'left'
        );
        $this->db->join(
            'loan_manager.debt_processing as processing',
            'processing.user_id = user.id',
            'left'
        );
        $this->db->join(
            'loan_manager.push_data as push',
            'push.user_id = user.id',
            'left'
        );
        $this->db->join(
            'loan_manager.users as admin',
            'processing.admin_id = admin.id',
            'left'
        );

        if(count($where) > 0){
            $this->db->where($where);
        }

        if(count($orWhere) > 0){
            $this->db->or_where($orWhere);
        }

//        $this->db->order_by("processing.updated_at",'desc');
        $limit ? $this->db->limit($limit, $offset) : '';
        $this->db->group_by('target.id');
        $order_by ? $this->db->order_by($order_by,'desc') : '';
        $query = $this->db->get();
        return $query->result();
    }

    public function get_delayUser_list($select,  $offset = 0, $limit = false)
    {
        $this->db->select($select)->from('p2p_user.users as user');
        $this->db->join(
            'p2p_loan.targets as target',
            'target.user_id = user.id'
        );
        $this->db->where('target.delay', 1);
        $this->db->group_by('user.id');
        $limit ? $this->db->limit($limit, $offset) : '';
        $query = $this->db->get();
        return $query->result();
    }

    public function get_userinfo($userId)
    {
        $select = [
            'user.id as userId',
            'user.name',
            'user.picture as userPicture',
            'processing.id as processingId',
            'processing.admin_id',
            'processing.push_by',
            'processing.push_type',
            'processing.result',
            'processing.remark',
            'processing.updated_at',
            'admin.name as adminName',
            'push.push_identity as pushIdentity',
            'push.user_status as pushUserStatus',
            'push.status as pushStatus',
        ];

        $this->db->select($select, false)
            ->from('p2p_user.users as user');
        $this->db->join(
            'loan_manager.debt_processing as processing',
            'processing.user_id = user.id',
            'left'
        );
        $this->db->join(
            'loan_manager.push_data as push',
            'push.user_id = user.id',
            'left'
        );
        $this->db->join(
            'loan_manager.users as admin',
            'processing.admin_id = admin.id',
            'left'
        );

        $this->db->where([
            'user.id' => $userId
        ]);
        $this->db->order_by('processing.id','desc');
        $this->db->limit(1,0 );
        $query = $this->db->get();
        return $query->result();
    }

    public function getPassbookBalance($userId){
        $select = [
            'user.id as userId',
            'virtualaccounts.virtual_account as virtualAccounts',
            'sum(virtualpassbooks.amount) as virtualPassbooks',
        ];

        $this->db->select($select, false)
            ->from('p2p_transaction.virtual_passbook as virtualpassbooks');
        $this->db->join(
            'p2p_user.virtual_account as virtualaccounts',
            'virtualaccounts.virtual_account = virtualpassbooks.virtual_account'
        );
        $this->db->join(
            'p2p_user.users as user',
            'virtualaccounts.user_id = user.id'
        );
        $this->db->where([
            'user.id' => $userId,
            'virtualaccounts.investor' => 0,
        ]);
        $this->db->order_by('virtualpassbooks.id','desc');
        $query = $this->db->get();
        return $query->result();
    }


    public function getPassbookList($account){
        $select = [
            'virtualpassbooks.amount',
            'virtualpassbooks.remark',
            'virtualpassbooks.tx_datetime',
            'virtualpassbooks.created_at',
            'virtualpassbooks.amount',
        ];

        $this->db->select($select, false)
            ->from('p2p_transaction.virtual_passbook as virtualpassbooks');
        $this->db->where([
            'virtualpassbooks.virtual_account' => $account,
        ]);
        $this->db->order_by('tx_datetime,created_at','asc');
        $query = $this->db->get();
        $res = $query->result();

        $total 	= 0;
        foreach($res as $key => $value){
            $total	+= $value->amount;
            $list[] = array(
                'amount' 		=> intval($value->amount),
                'bank_amount'	=> $total,
                'remark'		=> $value->remark,
                'tx_datetime'	=> $value->tx_datetime,
                'created_at'	=> intval($value->created_at),
                'action'		=> intval($value->amount)>0?'debit':'credit',
            );
        }

        return array_reverse($list);
    }

    public function getUserCerList($userId){
        $select = [
            'certification.certification_id',
            'certification.status',
            'certification.created_at',
            'certification.expire_time',
        ];

        $this->db->select($select, false)
            ->from('p2p_user.user_certification as certification');
        $this->db->where([
            'user_id' => $userId,
        ]);
        $query = $this->db->get();
        return $query->result();
    }

    public function getUserLoginLog($userId){
        $select = [
            'userLoginLog.status',
            'userLoginLog.created_at',
        ];

        $this->db->select($select, false)
            ->from('p2p_log.user_login_log as userLoginLog');
        $this->db->where([
            'user_id' => $userId,
        ]);
        $query = $this->db->get();
        return $query->result();
    }

    public function getUserServiceLog($userId, $type = false){
        $select = [
            'debtProcessing.admin_id',
            'debtProcessing.contact_id',
            'debtProcessing.result',
            'debtProcessing.push_by',
            'debtProcessing.push_type',
            'debtProcessing.message',
            'debtProcessing.remark',
            'debtProcessing.start_time',
            'debtProcessing.end_time',
            'debtProcessing.created_at',
        ];

        $this->db->select($select, false)
            ->from('loan_manager.debt_processing as debtProcessing');
        $param = [
            'user_id' => $userId,
        ];
        $type ? $param['push_by'] = 4 : $param['push_by !='] = 4;
        $this->db->where($param);
        $query = $this->db->get();
        return $query->result();
    }
}
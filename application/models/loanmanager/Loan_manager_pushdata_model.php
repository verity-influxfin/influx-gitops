<?php

class Loan_manager_pushdata_model extends MY_Model
{
    public $_table = 'push_data';
    public $before_create = array('before_data_c');
    public $before_update = array('before_data_u');

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

    public function getAccountingRecord($userId){
        $select = [
            'virtualpassbooks.amount as amount',
            'virtualpassbooks.tx_datetime as txdatetime',
            'virtualpassbooks.created_at as createdAt',
        ];

        $this->db->select($select, false)
            ->from('p2p_user.users as user');
        $this->db->join(
            'p2p_user.virtual_account as virtualaccounts',
            'virtualaccounts.user_id = user.id'
        );
        $this->db->join(
            'p2p_transaction.virtual_passbook as virtualpassbooks',
            'virtualaccounts.virtual_account = virtualpassbooks.virtual_account'
        );
        $this->db->where([
            'user.id' => $userId,
            'virtualaccounts.investor' => 0,
            'virtualpassbooks.remark' => '{"source":"1","target_id":"0"}',
        ]);
        $query = $this->db->get();
        return $query->result();
    }

//    public function getAccountingRecord($account){
//        $select = [
//            'amount as AccountingRecord',
//        ];
//
//        $this->db->select($select, false)
//            ->from('p2p_user.users as user');
//        $this->db->where([
//            'virtualpassbooks.virtual_passbook' => $account,
//            'virtualpassbooks.remark' => '{"source":"1","target_id":"0"}',
//        ]);
//        $this->db->order_by('virtualpassbooks.id','desc');
//        $query = $this->db->get();
//        return $query->result();
//    }

}
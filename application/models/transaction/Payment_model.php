<?php

class Payment_model extends MY_Model
{
    public $_table = 'payments';
    public $before_create = ['before_data_c'];
    public $before_update = ['before_data_u'];
    public $status_list = [
        0 => "未處理",
        1 => "已處理",
        2 => "處理中",
        3 => "需人工",
        4 => "已退款",
        5 => "不明資金",
        6 => "已處理",
        # iseeus 861
        7 => "已退款",
    ];

    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->load->database('transaction', TRUE);
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

    public function get_userid_by_payment($payment_id)
    {
        if(empty($payment_id))
        {
            return [];
        }

        $this->db
            ->select('*')
            ->from('`p2p_transaction`.`payments`')
            ->where_in('id', $payment_id);
        $sub_query = $this->db->get_compiled_select('', TRUE);
        $this->db
            ->select('`va`.`user_id` as `user_id`')
            ->from('`p2p_user`.`virtual_account` AS `va`')
            ->join("($sub_query) as `p`", "`p`.`virtual_account` = `va`.`virtual_account`");
        return $this->db->get()->result();
    }

    public function get_chraity_payment_info($user_bank_acc, $sdate = '', $edate = '')
    {
        $this->_database
            ->select('id, tx_datetime, tx_seq_no, amount , tx_spec')
            ->from('payments')
            ->where('bank_acc', $user_bank_acc);

        if ( ! empty($sdate))
        {
            $this->_database->where('tx_datetime >= ', "{$sdate} 00:00:00");
        }
        if ( ! empty($edate))
        {
            $this->_database->where('tx_datetime <= ', "{$edate} 23:59:59");
        }
        $payments = $this->_database->get()->result_array();

        return $this->_get_fee_from_same_spec($payments);
    }

    private function _get_fee_from_same_spec($payments)
    {
        $return_data = [];
        if ( ! empty($payments))
        {
            foreach ($payments as $key => $payment)
            {
                $next_id = $payment['id'] + 1;
                $next_payment = $this->_database
                    ->select('id, tx_datetime, tx_seq_no, amount , tx_spec')
                    ->from('payments')
                    ->where('id', $next_id)
                    ->get()->row_array();
                if ($next_payment['tx_seq_no'] == $payment['tx_seq_no']
                    && $next_payment['tx_spec'] == '跨行費用')
                {
                    $return_data[] = $this->_parser_fees($next_payment);
                }
            }
        }

        return $return_data;
    }

    private function _parser_fees($payment)
    {
        return [
            date('Ymd', strtotime($payment['tx_datetime'])),
            '跨行轉帳手續費',
            abs($payment['amount']),
        ];
    }
}

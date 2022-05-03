<?php

class Charity_anonymous_model extends MY_Model
{
    public $_table = 'charity_anonymous';
    public $before_create = ['before_data_c'];

    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->load->database('default', TRUE);
    }

    protected function before_data_c($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s', time());
        $data['created_ip'] = get_ip();
        return $data;
    }

    public function anonymous_insert($data)
    {
        $insert_data = [
            'amount' => $data['amount'],
            'name' => $data['name'] ?? '',
            'number' => $data['number'] ?? '',
            'phone' => $data['phone'] ?? '',
            'email' => $data['email'] ?? '',
            'upload' => $data['upload'] ?? 0,
            'receipt' => $data['receipt'] ?? 0,
            'address' => $data['address'] ?? '',
            'source' => $data['source'] ?? 0,
        ];

        return $this->insert($insert_data);
    }

    /**
     * 捐款查詢有輸入姓名或身份證
     **/
    public function get_anonymous($data)
    {
        $user_name = $data['name'] ?? '';
        $user_number = $data['number'] ?? '';

        $anonymous_list = $this->db
            ->select('id, amount')
            ->from('`p2p_user`.`charity_anonymous`')
            ->where([
                'name' => $user_name,
                'number' => $user_number,
            ])->get()->result_array();

        if ($anonymous_list)
        {
            foreach ($anonymous_list as $key => $list)
            {
                if ($list['amount'] == $data['amount'])
                {
                    return $list['id'];
                }
            }
        }

        return 0;
    }
}

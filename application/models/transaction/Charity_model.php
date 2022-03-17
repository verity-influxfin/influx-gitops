<?php

class Charity_model extends MY_Model
{
    public $_table = 'charity';
    public $before_create = ['before_data_c'];
    public $before_update = ['before_data_u'];
    public $status_list = [
        0 => '停用',
        1 => '有效',
    ];

    public $recipient_type = [
        0 => '單筆收據',
        1 => '不寄收據',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->load->database('transaction', TRUE);
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

    public function getDonatedList($alias = '', $where = [])
    {
        $this->_database->select('*')
            ->from("`p2p_transaction`.`charity`");
        if ( ! empty($where))
        {
            $this->_set_where([$where]);
        }

        $subquery = $this->_database->get_compiled_select('', TRUE);
        $this->_database
            ->select('ch.*, chi.alias, chi.name, chi.CoA_content')
            ->from('`p2p_user`.`charity_institution` AS `chi`')
            ->join("($subquery) as `ch`", "`ch`.`institution_id` = `chi`.`id`");
        if ( ! empty($alias))
        {
            $this->_database->where('chi.alias', $alias);
        }

        return $this->_database->get()->result_array();
    }

    public function get_ntu_donation_list(int $max_id)
    {
        $this->_database
            ->select('id')
            ->select('amount')
            ->select('created_at')
            ->select('updated_at')
            ->select('data')
            ->from('p2p_transaction.' . $this->_table)
            ->where('id>', $max_id)
            ->order_by('id');

        return $this->_database->get()->result_array();
    }

    public function get_donor_list($sdate, $edate)
    {
        $this->_database->select('ch.tx_datetime, ch.user_id, us.id_number AS id_number, us.name AS name, ch.receipt_type, ch.amount, ch.data')
            ->from("`p2p_transaction`.`charity` AS ch")
            ->join("`p2p_user`.`users` AS us", "ch.user_id=us.id");
        if ( ! empty($sdate))
        {
            $this->_database->where("ch.created_at >= ", "{$sdate} 00:00:00");
        }
        if ( ! empty($edate))
        {
            $this->_database->where("ch.created_at <= ", "{$edate} 23:59:59");
        }
        $donate_list = $this->_database->get()->result_array();

        foreach ($donate_list as $key => $donate)
        {
            $address_info = json_decode($donate['data'], TRUE)['receipt_address'] ?? '';
            if ($address_info === '由基金會代上傳國稅局' || empty($address_info))
            {
                $donate_list[$key]['receipt_type'] = $this->recipient_type[1];
            }
            else
            {
                $donate_list[$key]['receipt_type'] = $this->recipient_type[0];
            }
        }

        return $donate_list;
    }

    public function get_download_info($sdate, $edate)
    {
        $this->_database->select('us.name AS name, us.id_number AS id_number, us.phone AS phone, ch.tx_datetime, ch.amount, ch.user_id, ch.receipt_type, ch.data')
            ->from("`p2p_transaction`.`charity` AS ch")
            ->join("`p2p_user`.`users` AS us", "ch.user_id=us.id");
        if ( ! empty($sdate))
        {
            $this->_database->where("ch.created_at >= ", "{$sdate} 00:00:00");
        }
        if ( ! empty($edate))
        {
            $this->_database->where("ch.created_at <= ", "{$edate} 23:59:59");
        }
        $donate_list = $this->_database->get()->result_array();

        foreach ($donate_list as $key => $donate)
        {
            // 用戶自填的資料
            $donor_info = json_decode($donate['data'], TRUE);
            $donate_list[$key]['email'] = $donor_info['email'] ?? '';
            $address_info = $donor_info['receipt_address'] ?? '';

            if ($address_info === '由基金會代上傳國稅局')
            {
                $donate_list[$key]['upload'] = '是';
                $donate_list[$key]['receipt_type'] = $this->recipient_type[1];
            }
            else
            {
                $donate_list[$key]['upload'] = '否';
                if (empty($address_info))
                {
                    $donate_list[$key]['receipt_type'] = $this->recipient_type[1];
                }
                else
                {
                    $donate_list[$key]['receipt_type'] = $this->recipient_type[0];
                    $donate_list[$key]['address_data'] = $this->parser_address($address_info);
                }
            }

            // 整理一下捐款日
            $donate_list[$key]['donate_day'] = str_replace('-', '', explode(' ', $donate['tx_datetime'])[0]);
        }

        return $donate_list;
    }

    // TODO 處理地址相關參數，待完成
    public function parser_address($address)
    {
        $address_data = [
            'code' => '',
            'city' => '',
            'country' => '',
            'address' => '',
        ];

        if (empty($address))
        {
            return $address_data;
        }

        return $address_data;
    }
}

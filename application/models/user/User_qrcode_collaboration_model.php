<?php

class user_qrcode_collaboration_model extends MY_Model
{
    public $_table = 'user_qrcode_collaboration';
    public $before_create = array('before_data_c');
    public $before_update = array('before_data_u');
    public $status_list = array(
        0 => '停用',
        1 => '有效'
    );

    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->load->database('default', TRUE);
    }

    protected function before_data_c($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_ip'] = get_ip();
        return $data;
    }

    protected function before_data_u($data)
    {
        return $data;
    }

    /**
     * 取得推薦碼的已匯入合作紀錄列表
     * @param mixed $promoteCode
     * @param string $startTime
     * @param string $endTime
     * @return array
     */
    public function getCollaborationList($userQrcodeId, string $startTime = '', string $endTime = ''): array
    {
        $this->db->select('user_qrcode_id, qrcode_collaborator_id, loan_time')
            ->from('p2p_user.user_qrcode_collaboration');
        if ($startTime != '')
            $this->db->where("`loan_time` >=", $startTime);
        if ($endTime != '')
            $this->db->where("`loan_time` <", $endTime);

        $subQuery = $this->db->get_compiled_select('', TRUE);
        $this->db
            ->select('uq.id, uq.promote_code, uqc.qrcode_collaborator_id, uqc.loan_time')
            ->from('`p2p_user`.`user_qrcode` AS `uq`')
            ->join("({$subQuery}) as `uqc`", "`uqc`.`user_qrcode_id` = `uq`.`id`");
        if (is_array($userQrcodeId))
            $this->db->where_in('uq.id', $userQrcodeId);
        else
            $this->db->where('uq.id', $userQrcodeId);

        $subQuery2 = $this->db->get_compiled_select('', TRUE);
        $this->db
            ->select('r.*, uc.type')
            ->from('`p2p_user`.`qrcode_collaborator` AS `uc`')
            ->join("({$subQuery2}) as `r`", "`r`.`qrcode_collaborator_id` = `uc`.`id`");

        return $this->db->get()->result_array();
    }

}

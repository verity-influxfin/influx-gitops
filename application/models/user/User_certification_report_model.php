<?php

class User_certification_report_model extends MY_Model
{
    public $_table = 'user_certification_report';
    public $before_create 	= array( 'before_data_c' );
    public $before_update 	= array( 'before_data_u' );

    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->load->database('default', TRUE);
    }

    protected function before_data_c($data)
    {
        $data['created_at'] = $data['updated_at'] = time();
        return $data;
    }

    protected function before_data_u($data)
    {
        $data['updated_at'] = time();
        return $data;
    }

    /**
     * 寫入或更新徵信報告資料
     * @param  integer $user_id   使用者會員編號
     * @param  integer $target_id 案件編號
     * @param  array   $content   徵信報告資料
     * @param  integer $admin_id  後台編輯人員編號
     * @param  string  $name      後台編輯人員名稱
     * @return boolean TRUE/FALSE
     */
    public function insert_or_update( int $user_id = 0, int $target_id = 0, array $content = [], int $admin_id = 0, string $name = '')
    {
        $content = json_encode($content,JSON_UNESCAPED_UNICODE);
        // TODO: waitting to add insert_or_update function in DB_active_rec.php
        $sql = 'INSERT INTO user_certification_report (user_id,target_id, content, admin_id, name)
                VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE user_id=?, target_id=?, content=?, admin_id=?, name=?';
        $result = $this->db->query($sql, [$user_id, $target_id, $content, $admin_id, $name, $user_id, $target_id, $content, $admin_id, $name]);
        if ($result)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
}

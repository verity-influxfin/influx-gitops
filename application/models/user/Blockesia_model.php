<?php

class Blockesia_model extends MY_Model
{
    protected $_table = 'p2p_user.blockesia';

    /**
     * 新增資料
     *
     * @param      array  $data      資料陣列
     * @param      int    $admin_id  管理人員 ID
     * @return     mixed             FALSE|新增資料 ID
     * 
     * @created_at                   2021-12-10
     * @created_by                   Jack
     */
    public function create_data(array $data, int $admin_id=0)
    {
        return $this->db->insert($this->_table, [
            'user_id'     => $data['user_id'] ?? null,
            'item'        => $data['item'] ?? null,
            'data_source' => $data['data_source'] ?? null,
            'category'    => $data['category'] ?? null,
            'content'     => $data['content'] ?? null,
            'risk'        => $data['risk'] ?? null,
            'resolution'  => $data['resolution'] ?? null,
            'created_by'  => $admin_id,
            'created_ip'  => $this->input->ip_address()
        ]);
    }

    /**
     * 更新資料
     *
     * @param      int    $user_id   用戶 ID
     * @param      array  $data      更新資料鄭烈
     * @param      int    $admin_id  管理人員 ID
     * @return     bool              更新結果
     * 
     * @created_at                   2021-12-10
     * @created_by                   Jack
     */
    public function update_data(int $user_id, array $data=[], int $admin_id=0)
    {
        $this->db->where([
            'user_id' => $user_id,
            'status'  => STATUS_INACTIVE
        ]);
        switch (TRUE)
        {
            case ! empty($data['item']):
                $this->db->set('item', $data['item']);

            case ! empty($data['data_source']):
                $this->db->set('data_source', $data['data_source']);

            case ! empty($data['category']):
                $this->db->set('category', $data['category']);

            case ! empty($data['content']):
                $this->db->set('content', $data['content']);

            case ! empty($data['risk']):
                $this->db->set('risk', $data['risk']);

            case ! empty($data['resolution']):
                $this->db->set('resolution', $data['resolution']);
        }
        $this->db->set([
            'updated_by' => $admin_id,
            'updated_ip' => $this->input->ip_address()
        ]);
        return $this->db->update($this->_table, $data);
    }

    /**
     * 取得資料
     *
     * @param      int               $page   目前頁數
     * @param      int               $limit  每頁筆數
     * @return     Model_Pagination          Model 分頁物件
     * 
     * @created_at                           2021-12-10
     * @created_by                           Jack
     */
    public function get_data(int $page=1, int $limit=20)
    {
        $query = $this->db->from($this->_table)
                        ->where('status', STATUS_ACTIVE);

        return $this->set_page($page, $limit);
    }
}
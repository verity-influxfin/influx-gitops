<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 供子系統取得主站設定檔資訊
 */
class Config extends Admin_rest_api_controller
{
    /**
     * 取得設定檔資料
     * @param string $config : 要載入的設定文件
     * @param string $item : 要讀取的設定項
     * @return void
     */
    public function index_get(string $config, string $item = '')
    {
        if ($config == 'platform' && empty($item))
        {
            $result = [];
        }
        else
        {
            $this->load->config($config, TRUE);
            if (empty($item))
            {
                $result = $this->config->item($config) ?? [];
            }
            else
            {
                $result = [
                    $item => $this->config->item($config)[$item] ?? []
                ];
            }
        }
        $this->response(['result' => 'SUCCESS', 'data' => ['config_list' => $result]]);
    }
}
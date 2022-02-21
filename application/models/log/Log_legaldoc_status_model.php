<?php

class Log_legaldoc_status_model extends MY_Model
{
	public $_table = 'legaldoc_status_log';
	public $before_create = array( 'before_data_c' );

    public $process_status = array(
        0 => "系統通知/APP、Mail",
        1 => "LINE客服",
        2 => "人工電話",
        3 => "還款計畫訪談表",
        4 => "產品轉換",
        5 => "緊急聯絡人",
        6 => "存證信函-電子",
        7 => "存證信函-紙本",
        8 => "投資人-委任處理",
        9 => "投資人-自行處理",
        10 => "支付命令-聲請",
        11 => "支付命令-回函",
        12 => "支付命令-確認書",
        13 => "查調債務人課稅資料",
        14 => "強制執行-聲請",
        15 => "強制執行-查扣薪資財產",
        16 => "債權分配",
        17 => "債權憑證",
        18 => "結案",
        19 => "其他說明",
    );

	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('log',TRUE);
 	}
	
	protected function before_data_c($data)
    {
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['created_ip'] = get_ip();
        return $data;
    } 	
	
}

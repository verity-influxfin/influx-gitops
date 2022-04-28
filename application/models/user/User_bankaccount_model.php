<?php

class User_bankaccount_model extends MY_Model
{
	public $_table = 'user_bankaccount';
	public $before_create 	= array( 'before_data_c' );
	public $before_update 	= array( 'before_data_u' );
	public $fields			= array("user_id","bank_code","bank_account","front_image","back_image");
	public $sys_check_list = array(
		0 =>	"未驗證",
		20 =>	"交易成功(系統自動)",
		21 =>	"需轉人工",
	);

	public $investor_list  	= array(
		0 =>	"借款端",
		1 =>	"投資端",
        2 =>	"法人借款端",
        3 =>	"法人投資端",
	);
	
	public $verify_list  	= array(
		0 =>	"未驗證",
		1 =>	"驗證成功",
		2 =>	"待驗證",
		3 =>	"已發送",
		4 =>	"驗證失敗",
	);

	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('default',TRUE);
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

    /**
     * 取得用戶實體銀行帳戶
     *
     * @param      int     $user_id   用戶 ID
     * @param      int     $investor  投資人 (0:借款|1:投資)
     * @return     array              實體帳戶資訊
     *
     * @created_at                    2022-03-04
     * @created_by                    Jack
     */
    public function get_bank_account_by_user(int $user_id, int $investor)
    {
        return $this->db
            ->select([
                'ub.bank_code',
                'ub.bank_account',
            ])
            ->from('user_bankaccount AS ub')
            ->where([
                'ub.user_id'  => $user_id,
                'ub.investor' => $investor,
                'ub.status'   => 1
            ])
            ->get()
            ->first_row('array');
    }
}

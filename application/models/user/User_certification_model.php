<?php

class User_certification_model extends MY_Model
{
	public $_table = 'user_certification';
	public $before_create 	= array( 'before_data_c' );
	public $before_update 	= array( 'before_data_u' );
	public $status_list   	= array(
		0 => "待驗證" ,
		1 => "驗證成功" ,
		2 => "驗證失敗" ,
		3 => "待人工審核" ,
		4 => "未完成填寫" ,
	);
	public $investor_list  	= array(
		0 =>	"借款端",
		1 =>	"出借端",
	);
	public $certificationContentKeyMapping = [
		'id_number' => 1,
		'bank_account' => 3,
		'address' => 1,
	];

	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('default',TRUE);
 	}

	protected function before_data_c($data)
    {
        $data['created_at'] 	= $data['updated_at'] = time();
		if(!isset($data['expire_time'])){
			$data['expire_time'] 	= strtotime("+6 months", $data['created_at']);
		}
        $data['created_ip'] 	= $data['updated_ip'] = get_ip();
        return $data;
    }

	protected function before_data_u($data)
    {
        $data['updated_at'] = time();
        $data['updated_ip'] = get_ip();
        return $data;
    }

	public function get_users_with_same_value($userId, $key, $values)
	{
		if (
			!$values
			|| !isset($this->certificationContentKeyMapping[$key])
		) {
			return [];
		}

		$this->db->select('users.*')
			->from('p2p_user.user_certification as cert')
			->join('p2p_user.users as users', 'users.id = cert.user_id')
			->where('user_id !=', $userId);

		$certificationId = $this->certificationContentKeyMapping[$key];
		$this->db->where('cert.certification_id', $certificationId);

		$numbersLike = count($values);
		$this->db->group_start();
		for ($i = 0; $i < $numbersLike; $i++) {
			$value = $values[$i];
			if (preg_match('/[^A-Za-z0-9]/', $value)) {
				$value = json_encode($value);
				$value = rtrim($value, '"');
				$value = ltrim($value, '"');
			}

			$value = "{$key}\":\"{$value}\"";

			if ($i == 0) $this->db->like("content", $value);
			else $this->db->or_like("content", $value);
		}
		$this->db->group_end();

		$this->db->group_by('cert.user_id');

		$query = $this->db->get();

		return $query->result();
	}

    public function get_skbank_check_list($userIdList=[]){

        if(empty($userIdList) || !is_array($userIdList)){
            return [];
        }

        $query = $this->db->select_max('id')
                    ->select(['user_id','certification_id','content'])
        			->from('p2p_user.user_certification')
        			->where_in('user_id', $userIdList)
                    ->where_in('certification_id',['11','12','500','501','1002','1003','1007','1017','1018'])
                    ->where_not_in('status', ['2'])
                    ->where('content !=', '')
                    ->group_by(['user_id','certification_id'])->get();

        return $query->result();
    }

    public function getCertificationsByTargetId($targetIds, $cer_condition=[]) {
        $this->db->select('*')
            ->from("`p2p_user`.`user_certification`")
            ->where($cer_condition);
        $certification_subquery = $this->db->get_compiled_select('', TRUE);
        $this->db->select('id, user_id')
            ->from("`p2p_loan`.`targets`")
            ->where_in('id', $targetIds);
        $target_subquery = $this->db->get_compiled_select('', TRUE);
        $this->db
            ->select('MAX(`uc`.`updated_at`) as `updated_at`, uc.certification_id, uc.user_id, uc.investor')
            ->from("($certification_subquery) as `uc`")
            ->join("($target_subquery) as `t`", "`t`.`user_id` = `uc`.`user_id`")
            ->group_by('uc.user_id, uc.investor, uc.certification_id');
        $subquery = $this->db->get_compiled_select('', TRUE);
        $this->db
            ->select('*')
            ->from('`p2p_user`.`user_certification` as `uc`')
            ->join("($subquery) as `r`", "`r`.`certification_id` = `uc`.`certification_id` and `r`.`user_id` = `uc`.`user_id` and `r`.`investor` = `uc`.`investor` and `r`.`updated_at` = `uc`.`updated_at`")
            ->group_by('uc.user_id, uc.certification_id');
        $result = $this->db->get()->result_array();
        $list = [];
        foreach ($result as $cer) {
            $list[$cer['user_id']][$cer['certification_id']] = $cer;
        }
        return $list;
    }
}

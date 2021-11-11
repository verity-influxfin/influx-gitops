<?php

class Charity_model extends MY_Model
{
	public $_table = 'charity';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );
	public $status_list   = array(
		0 =>	'停用',
		1 =>	'有效'
	);

	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('transaction',TRUE);
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

    public function getDonatedList($alias='', $where=[]) {
        $this->_database->select('*')
            ->from("`p2p_transaction`.`charity`");
        if(!empty($where))
            $this->_set_where([$where]);

        $subquery = $this->_database->get_compiled_select('', TRUE);
        $this->_database
            ->select('ch.*, chi.alias, chi.name, chi.CoA_content')
            ->from('`p2p_user`.`charity_institution` AS `chi`')
            ->join("($subquery) as `ch`", "`ch`.`institution_id` = `chi`.`id`");
        if(!empty($alias)) {
            $this->_database->where('chi.alias', $alias);
        }

        return $this->_database->get()->result_array();
    }
}

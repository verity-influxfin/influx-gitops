<?php

class Difficult_word_model extends MY_Model
{
	public $_table = 'difficult_words';
	public $before_create = array( 'before_data_c' );

	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('admin',TRUE);
 	}
	
	protected function before_data_c($data)
    {
        $data['created_at'] = time();
        $data['created_ip'] = get_ip();
        return $data;
    } 	

	public function get_name_list(){
		$data 	= array();
		$list 	= $this->get_all();
		if($list){
			foreach($list as $key => $value){
				$data[$value->word] = $value->spelling;
			}
		}
		return $data;
	}
}
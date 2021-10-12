<?php

class Log_image_model extends MY_Model
{
	public $_table = 'image_log';
	public $before_create = array( 'before_data_c' );

	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('log',TRUE);
 	}

	protected function before_data_c($data)
    {
        $data['created_at'] = time();
        $data['created_ip'] = get_ip();
        return $data;
    }

		public function getUrlByID($imgID=[]){
			if($imgID){
				$this->db->select('image_log.id,image_log.url')
 	 			->from('p2p_log.image_log')
 	 			->where_in('id', $imgID);
 				$query = $this->db->get();

 				return $query->result();
			}
			 return [];
		}

		public function getUrlByGroupID($imgID=[]){
			if($imgID){
				$this->db->select('image_log.id,image_log.url,image_log.group_info')
 	 			->from('p2p_log.image_log')
 	 			->where_in('group_info', $imgID);
 				$query = $this->db->get();

 				return $query->result();
			}
			 return [];
		}

		public function insertGroupById($imgID=[],$data=[]){
			if($imgID){
				$query = $this->db->where_in('id', $imgID)
				->update('p2p_log.image_log', $data);

			}
			 return [];
		}

		public function getIDByUrl($Url=[]){
			if($Url){
				$this->db->select('image_log.id,image_log.url')
 	 			->from('p2p_log.image_log')
 	 			->where_in('url', $Url);
 				$query = $this->db->get();

 				return $query->result();
			}
			 return [];
		}
}

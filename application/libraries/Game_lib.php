<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Game_lib{


	public function __construct()
    {
        $this->CI = &get_instance();
    }


	 //line_活動_送line points ++
	//game lib
	public function check_five_points($user_id,$my_line_id){
		
		$this->CI->load->model('log/log_game_model');
		$this->CI->load->library('line_lib');
		$param = array(
			"user_id"=>$user_id , "content" => $my_line_id, "memo"=>'check_five_points');
			$this->CI->log_game_model->insert($param);
		$check = $this->CI->log_game_model->get_by(array("user_id"=>$user_id,"content"=>$my_line_id,"memo"=>'send_five_points'));
        if(empty($check)){
			//送出linebot 
			$this->CI->line_lib->send_five_points($my_line_id);
			$param = array(
				"user_id"=>$user_id , "content" => $my_line_id, "memo"=>'send_five_points');
				$this->CI->log_game_model->insert($param);
		}
		
	  }

	  public function check_fifty_points($user_id,$my_line_id,$collect_count){
		$this->CI->load->model('log/log_game_model');
		$this->CI->load->library('line_lib');
		$param = array(
			"user_id"=>$user_id , "content" => $my_line_id, "memo"=>'check_fifty_points');
			$this->CI->log_game_model->insert($param);
			$done_collect_count = $this->CI->log_game_model->get_many_by(array("user_id"=>$user_id,"content"=>$my_line_id,"memo"=>'send_fifty_points'));
			$size=count($done_collect_count); 

			if($size==0&&$collect_count>=0){
			//送出第一次linebot 
			   for ( $i=1 ; $i<=$collect_count ; $i++ ) {
					$param = array(
						"user_id"=>$user_id , "content" => $my_line_id, "memo"=>'send_fifty_points');
						$this->CI->log_game_model->insert($param);
					    $this->CI->line_lib->send_fifty_points($my_line_id);
					}
				}
				if(	$size>0&&($collect_count>$size)){
					$res=$collect_count-$size;
					for ( $i=1 ; $i<=$res; $i++ ) {
						$param = array(
							"user_id"=>$user_id , "content" => $my_line_id, "memo"=>'send_fifty_points');
							$this->CI->log_game_model->insert($param);
						$this->CI->line_lib->send_fifty_points($my_line_id);
				}
				}
		
	  }
 
 
	
 
}
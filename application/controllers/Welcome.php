<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		echo $this->config->item('jwt_key');
		//$this->load->view('welcome_message');
		
		$question = array(
			1	=> array(
				"id"		=> "1",
				"type"		=> "single",
				"question" 	=> "是否為在校學生？",
				"select"	=> array(
					"1"	=> array("option"=>"是","rating"=>"1","next" => "2"),
					"2"	=> array("option"=>"否","rating"=>"0","next" => "3"), 
				)
			),
			2	=> array(
				"id"		=> "2",
				"type"		=> "single",
				"question" 	=> "請點擊選擇您的學歷",
				"select"	=> array(
					"1"	=> array("option"=>"大學在讀","rating"=>"1","next" => "3"),
					"2"	=> array("option"=>"研究所在讀","rating"=>"2","next" => "3"), 
					"3"	=> array("option"=>"博士在讀","rating"=>"3","next" => "3"), 
					"4"	=> array("option"=>"專科在讀","rating"=>"4","next" => "3"), 
				)
			),
			3	=> array(
				"id"		=> "3",
				"type"		=> "multiple",
				"question" 	=> "目前收入來源",
				"select"	=> array(
					"1"	=> array("option"=>"打工","rating"=>"1","next" => "end"),
					"2"	=> array("option"=>"投資","rating"=>"1","next" => "end"),
					"3"	=> array("option"=>"家中資助","rating"=>"1","next" => "end"),
					"4"	=> array("option"=>"專業撿到錢","rating"=>"1","next" => "end")
				)
			)
		);
		
		dump(json_encode($question)); 
	}
}

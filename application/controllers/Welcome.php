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
		/*$amount 	= 10000;//額度
		$rate		= 4.4;
		$instalment = 12;
		$mrate 	 	= $rate/1200;
		$mtotal		= 1+$mrate;
		$minterest 	= $amount*$mrate*pow($mtotal,$instalment)/(pow($mtotal,$instalment)-1);
		$minterest	= round($minterest,2);
		echo '<span>本金：'.$amount.'</span><br>';
		echo '<span>年利率：'.$rate.'%</span><br>';
		echo '<span>每期應繳：'.$minterest.'</span>';
		echo '<table style="width:50%;text-align: center;"><tr><th>期數</th><th>本金</th><th>利息</th><th>本息合計</th></tr>';
		$t_amount = $t_interest = $t_min = 0;
		for($i=1;$i<=$instalment;$i++){
			
			$mamount 	= $amount*$mrate*pow($mtotal,$i-1)/(pow($mtotal,$instalment)-1);
			$interest	= $amount*$mrate*pow($mtotal,$instalment)/(pow($mtotal,$instalment)-1) - $amount*$mrate*pow($mtotal,$i-1)/(pow($mtotal,$instalment)-1);
			$interest 	= round($interest,0);
			$mamount	= ceil($minterest)-$interest;
			if($i==$instalment){
				$mamount = $amount-$t_amount;
			}
			
			$min		= $mamount+$interest;
			$t_interest	= $t_interest+$interest;
			$t_amount	= $t_amount+$mamount;
			$t_min		= $t_min+$min;
			
			echo "<tr>";
			echo "<td>".$i."</td>";
			echo "<td>".$mamount."</td>";
			echo "<td>".$interest."</td>";
			echo "<td>".$min."</td>";
			echo "</tr>";
		}
			echo "<td>合計</td>";
			echo "<td>".$t_amount."</td>";
			echo "<td>".$t_interest."</td>";
			echo "<td>".$t_min."</td>";
			echo "</tr>";
		echo '</table>'; */
		//$this->load->library('Sms_lib');
		//$this->sms_lib->test('0977249516');
		$this->load->library('Payment_lib');
		$this->payment_lib->insert_cathay_info();
		/*
		$this->load->library('Target_lib');
		$rs = $this->target_lib->approve_target(1);
		dump($rs);*/
		
		
		/*$this->load->library('S3_upload');
		$this->load->library('Faceplusplus_lib');
		$data = array();

		
		$card_token = "654f6e80fc834acb57036f388bfece55";
		$face_token = "2e3b21a904be20ceba99c473f4d1e9a8";
		$rs = $this->faceplusplus_lib->token_compare($card_token,$face_token);
		dump($rs);
		
		$face_token = "e1bd223b8b84503805b3ef1bca7f76e0";
		$rs = $this->faceplusplus_lib->token_compare($card_token,$face_token);
		dump($rs);
		
		if(isset($_FILES["image"]) && !empty($_FILES["image"])){
			$url = $this->s3_upload->image($_FILES,"image2",0,"test");
			dump($url);
			$url = $this->s3_upload->image($_FILES,"image",0,"test");
			dump($url);		
$this->load->view('welcome_message');			
		}else{
			$this->load->view('welcome_message');
		}*/
		
		//echo $this->config->item('jwt_key');
		//$this->load->view('welcome_message');
		

	}
}

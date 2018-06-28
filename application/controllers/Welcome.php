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
		$this->load->library('S3_upload');
		$this->load->library('Faceplusplus_lib');
		$data = array();
		if(isset($_FILES["image"]) && !empty($_FILES["image"])){
			$image 	= $this->s3_upload->image($_FILES,"image",0,"test");
			$image2 = $this->s3_upload->image($_FILES,"image2",0,"test");
			if($image && $image2){
				$image_token 	= $this->faceplusplus_lib->get_face_token($image);
				$image2_token 	= $this->faceplusplus_lib->get_face_token($image2);
				$image_count 	= $image_token&&is_array($image_token)?count($image_token):0;
				$image2_count 	= $image2_token&&is_array($image2_token)?count($image2_token):0;
				$image_answer	= 0;
				$image2_answer	= 0;
				$answer			= array();
				if($image_count>0 && $image2_count>0){
					foreach($image_token as $token){
						foreach($image2_token as $token2){
							$answer[] = $this->faceplusplus_lib->token_compare($token,$token2);
						}
					}
				}
				
				if($image_count>1){
					$image_answer = $this->faceplusplus_lib->token_compare($image_token[0],$image_token[1]);
				}
				
				if($image2_count>1){
					$image2_answer = $this->faceplusplus_lib->token_compare($image2_token[0],$image2_token[1]);
				}
				
				//$this->load->library('Ocr_lib');
				//$ocr = $this->ocr_lib->identify($image,1031);
				$ocr = array();
				$data = array(
					"image"			=> 	$image,
					"id_card"		=>	$ocr,
					"image2"		=> 	$image2,
					"image_count"	=> 	$image_count,
					"image2_count"	=> 	$image2_count,
					"image_answer"	=> 	$image_answer,
					"image2_answer"	=> 	$image2_answer,
					"answer"		=> 	$answer,
				);
			}else{
				alert("上傳失敗");
			}
		}
		$this->load->view('admin/forms',$data);
	}
	
	
	function test(){
		$this->load->library('Financial_lib');
		$amount 	= intval($_GET['amount']);//額度
		$rate		= $_GET['rate'];
		$instalment = intval($_GET['instalment']);//期數
		$date 		= $_GET['date'];//起始日
		$repayment	= isset($_GET['repayment'])?intval($_GET['repayment']):1;//起始日
		$schedule 	= $this->financial_lib->get_amortization_schedule($amount,$instalment,$rate,$date,$repayment); 
		echo '<span>本金：'		.$schedule['amount']		.'</span><br>';
		echo '<span>年利率：'		.$schedule['rate']		.'%</span><br>';
		echo '<span>每期應繳：'	.$schedule['total_payment']	.'</span><br>';
		echo '<span>Day0：'		.$schedule['date']			.'</span><br>';
		echo '<span>有無需考慮閏年：'.$schedule['leap_year']	.'</span><br>';
		echo '<span>期數：'.$schedule['instalment']			.'</span><br>';
		echo '<span>XIRR：'.($schedule['XIRR'])			.'%</span><br>';
		echo '<table style="width:50%;text-align:center;"><tr><th>期數</th><th>還款日</th><th>日數</th><th>期初本金餘額</th><th>還款本金</th><th>還款利息</th><th>還款合計</th></tr>';

		foreach($schedule['schedule'] as $key =>$value){
			echo "<tr>";
			echo "<td>".$key."</td>";
			echo "<td>".$value['repayment_date']."</td>";
			echo "<td>".$value['days']."</td>";
			echo "<td>".$value['remaining_principal']."</td>";
			echo "<td>".$value['principal']."</td>";
			echo "<td>".$value['interest']."</td>";
			echo "<td>".$value['total_payment']."</td>";
			echo "</tr>";
		}
		
		echo "<td>合計</td><td></td><td></td><td></td>";
		echo "<td>".$schedule['total']['principal']."</td>";
		echo "<td>".$schedule['total']['interest']."</td>";
		echo "<td>".$schedule['total']['total_payment']."</td>";
		echo "</tr>";
		echo "</table>";
		
	}
	
	function allimage(){
		$this->load->library('S3_upload');
		$this->s3_upload->image_list();
	}
	
	function recharge(){

	}
}
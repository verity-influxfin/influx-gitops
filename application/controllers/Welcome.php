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
				
				$data = array(
					"image"			=> 	$image,
					"id_card"		=>	$this->get_id_card_info($image),
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
		$amount 	= 3000;//額度
		$rate		= 11.5;
		$instalment = 15;
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
		echo '</table>';
		
	}

	function get_id_card_info($url){
		$data	= array();
		$client = new SoapClient("http://13.230.227.104:8888/cxfServerX/ImgReconCard?wsdl");
		//台湾身份证正面1031
		//台湾身份证背面1032
		$file_content = base64_encode( file_get_contents($url) );
		$file_content = $file_content."==##" . '1031' . "==##" . '' . "==##" . 'null';
		$param = array(
				'arg0' => 'test',
				'arg1' => $file_content,
				'arg2' => null,
				'arg3' => 'jpg'
			);
		$rs 	= $client->__soapCall('doAllCardRecon', array('parameters' => $param));
		$return = str_replace("==@@","",$rs->return);
		$xml 	= simplexml_load_string($return,null,LIBXML_NOCDATA);
		$xml 	= json_decode(json_encode($xml),TRUE);
		if($xml['message']['value']=="识别完成"){
			
			$item = $xml['cardsinfo']['card']['item'];
			foreach($item as $key => $value){
				if(is_array($value)){
					$item[$key] = "";
				}
			}
			
			$data = array(
				"name"			=> $item[1],
				"sex"			=> $item[2],
				"birthday"		=> $item[3],
				"id_card_date"	=> $item[4],
				"id_number"		=> $item[5],
				"face"			=> $item[6],
			);
			return $data;
		}
		return false;
	}

}
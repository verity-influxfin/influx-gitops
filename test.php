<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header ('Content-type:text/html; charset=utf-8');

$db = array(
	"p2p_loan"			=> array("batch","contracts","credits","investments","prepayment","subloan","targets","transfers","transfer_investment"),
	"p2p_log"			=> array("admin_login_log","faceplusplus_log","image_log","script_log","sns_log","user_login_log"),
	"p2p_partner"		=> array("partners"),
	"p2p_transaction"	=> array("frozen_amount","payments","transactions","virtual_passbook","withdraw"),
	"p2p_user"			=> array("email_verify_code","sms_verify_code","users","user_bankaccount","user_certification","user_contact","user_meta","	user_notification","virtual_account"),
	
);

$servername = "localhost";
$username 	= "root";
$password 	= "169euAO4nBxO5CvR";


foreach($db as $dbname => $tables){
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	mysqli_set_charset($conn,'utf8');
	foreach($tables as $k => $table){
		$sql 		= "TRUNCATE TABLE ".$table;
		echo $sql;
		//$result 	= mysqli_query($conn,$sql);
		var_dump($result);
		echo "</br>";
	}
	mysqli_close($conn);
}


?>



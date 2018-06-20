<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config['user_fields']= array(
	"id"				=> "User ID",
	"name"				=> "姓名",
	"sex"				=> "性別",
	"id_number"			=> "身分證字號",
	"id_card_date"		=> "發證日期",
	"id_card_place"		=> "發證地點",
	"phone"				=> "電話",
	"address"			=> "地址",
	"email"				=> "Email",
	"birthday"			=> "生日",
	"status"			=> "借款端帳號",
	"investor_status"	=> "出借端帳號",
	"block_status"		=> "性別",
	"promote_code"		=> "性別",
	"created_at"		=> "註冊日期",
	"created_ip"		=> "註冊IP",
);

$config['target_fields']= array(
	"id"				=> "Target ID",
	"target_no"			=> "案號",
	"product_id"		=> "Product ID",
	"user_id"			=> "User ID",
	"amount"			=> "申請金額",
	"loan_amount"		=> "核准金額",
	"interest_rate"		=> "核可利率",
	"instalment"		=> "期數",
	"repayment"			=> "還款方式",
	"bank_code"			=> "借款人收款銀行代碼",
	"branch_code"		=> "借款人收款分行代碼",
	"bank_account"		=> "借款人收款帳號",
	"virtual_account"	=> "還款虛擬帳號",
	"remark"			=> "備註",
	"contract"			=> "合約內容",
	"person_image"		=> "證件自拍照",
	"delay"				=> "是否逾期",
	"status"			=> "狀態",
	"loan_status"		=> "放款狀態",
	"created_at"		=> "申請時間",
);

$config['user_meta_fields']= array(
	"id_card" 	=> array(
		"id_card_status"		=> "身分證認證",
		"id_card_front"			=> "身分證正面",
		"id_card_back"			=> "身分證反面",
		"id_card_person"		=> "證件自拍照",
		"health_card_front"		=> "健保卡正面",
	),
	"student" 	=> array(
		"student_status"		=> "學生證認證",
		"school_name"			=> "學校名稱",
		"school_system"			=> "學制",
		"school_department"		=> "系所",
		"school_email"			=> "校內電子信箱",
		"school_grade"			=> "年級",
		"student_id"			=> "學號",
		"student_card_front"	=> "學生證正面照",
		"student_card_back"		=> "學生證背面照",
		"student_sip_account"	=> "SIP帳號",
		"student_sip_password"	=> "SIP密碼",
		"transcript_front"		=> "成績單",
	),
	"debit_card" => array(
		"debit_card_status"		=> "金融卡認證",
	),
	"emergency" => array(
		"emergency_status"		=> "緊急聯絡人",
		"emergency_name"		=> "緊急聯絡人姓名",
		"emergency_phone"		=> "緊急聯絡人電話",
		"emergency_relationship"=> "緊急聯絡人關係",
	),
	"email" 	=> array(
		"email_status"		=> "常用郵箱認證",
	),
	"financial" => array(
		"financial_status"		=> "財務訊息認證",
		"financial_income"		=> "財務收入",
		"financial_expense"		=> "財務支出",
		"financial_creditcard"	=> "信用卡帳單照",
		"financial_passbook"	=> "存摺內頁照",
	),
	"social" 	=> array(
		"social_status"			=> "社交認證",
	),
);

$config['user_meta_images']= array(
	"health_card_front",
	"id_card_front",
	"id_card_back",
	"id_card_person",
	"student_card_front",
	"student_card_back",
	"transcript_front",
	"financial_creditcard",
	"financial_passbook",
);


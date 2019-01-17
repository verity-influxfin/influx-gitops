<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
		"school_major"			=> "學門",
		"school_email"			=> "校內電子信箱",
		"school_grade"			=> "年級",
		"student_id"			=> "學號",
		"student_card_front"	=> "學生證正面照",
		"student_card_back"		=> "學生證背面照",
		"student_sip_account"	=> "SIP帳號",
		"student_sip_password"	=> "SIP密碼",
		"transcript_front"		=> "成績單",
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


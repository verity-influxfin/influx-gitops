<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//後台menu
$config['admin_menu'] = array(
	'Product' 			=> array('name'=>'產品管理','icon'=>'fa-briefcase'),
	'Target' 			=> array(
		'parent_name'				=> '借款管理',
		'index'						=> '全部列表',
		'waiting_verify'			=> '待審批',
		'waiting_bidding'			=> '已上架',
		'waiting_loan'				=> '待放款',
		'repayment'					=> '還款中',
		'prepayment'				=> '提前還款',
	),
	'Transfer' 						=> array(
		'parent_name'				=> '債權管理',
		'index'						=> '全部列表',
		'waiting_transfer'			=> '債轉待收購',
		'waiting_transfer_success'	=> '債轉待放款',
	),
	'Risk' 	=> array(
		'parent_name'				=> '風控專區',
		'index'						=> '風控審核',
		'credit'					=> '信評管理',
		'loaned_wait_push' 			=> '貸後催收',
		'loaned_wait_push?slist=1' 	=> '貸後已催收列表',
	),
	'Passbook' 	=> array(
		'parent_name'				=> '虛擬帳號管理',
		'index'						=> '虛擬帳號列表',
		'withdraw_list'				=> '提領紀錄',
		'withdraw_waiting'			=> '提領待放款',
		'unknown_funds'				=> '不明來源退款',
	),
	'Judicialperson' 	=> array(
		'parent_name'				=> '法人帳號管理',
		'index?status=0'			=> '申請列表',
		'cooperation?cooperation=2'	=> '經銷商申請',
	),
	'Certification' => array(
		'parent_name'				=> '認證管理',
		'index'						=> '認證方式列表',
		'user_certification_list'			=> '會員認證審核',
		'user_bankaccount_list?verify=2'	=> '金融帳號認證',
		'difficult_word_list'				=> '銀行困難字管理',
	),
	'Partner' 				=> array(
		'parent_name'		=> '合作夥伴管理',
		'partner_type'		=> '合作商類別',
		'index'				=> '合作商列表',
	),
	'Contact' 				=> array(
		'parent_name'		=> '客服管理',
		'index'				=> '投訴與建議',
		'send_email'		=> '通知工具',
	),
	'User' 					=> array('name'=>'會員管理'),
	'Admin' 				=> array(
		'parent_name'		=> '後台人員管理',
		'role_list'			=> '權限管理',
		'index'				=> '人員列表',
	),
	'Sales' 				=> array(
		'parent_name'		=> '業務報表',
		'index'				=> '借款報表',
		'register_report'	=> '註冊報表',
		'bonus_report'		=> '獎金報表',
	),
	'Account' 				=> array(
		'parent_name'		=> '財務作業',
		'daily_report'		=> '交易日報表',
		'passbook_report'	=> '虛擬帳號餘額明細表',
		'estatement'		=> '個人對帳單',
		'index'				=> '收支統計表',
	),
	'Agreement' 			=> array('name'=>'協議書','icon'=>'fa-star'),
);

//內部通知Email
if(ENVIRONMENT=='development'){
	$config['admin_email'] = array('news@influxfin.com');
}else{
	$config['admin_email'] = array('yaomu@influxfin.com','rogerkuo@influxfin.com','mori2.tw@gmail.com','tim@influxfin.com');
} 

//期數
$config['instalment']= array(
	0=> '其他',
	3=> '3期',
	6=> '6期',
	12=> '12期',
	18=> '18期',
	24=> '24期',
);

//公司型態
$config['company_type']= array(
	1=> '獨資',
	2=> '合夥',
	3=> '有限公司',
	4=> '股份有限公司',
);

//產品列表
$config['product_list']= [
	1 => [
		'id'				=> 1,
		'type'				=> 1,
		'identity'			=> 1,
		'name'				=> '學生貸',
		'loan_range_s'		=> 5000,
		'loan_range_e'		=> 120000,
		'interest_rate_s'	=> 5,
		'interest_rate_e'	=> 20,
		'certifications'	=> [1,2,3,4,5,6,7],
		'instalment'		=> [3,6,12,18,24],
		'repayment'			=> [1],
		'description'		=> '
普匯學生貸
計畫留學、創業或者實現更多理想嗎？
需要資金卻無法向銀行聲請借款嗎？
普匯陪你一起實現夢想'
	],
	2 => [
		'id'				=> 2,
		'type'				=> 2,
		'identity'			=> 1,
		'name'				=> '學生分期',
		'loan_range_s'		=> 5000,
		'loan_range_e'		=> 120000,
		'interest_rate_s'	=> 5,
		'interest_rate_e'	=> 20,
		'certifications'	=> [1,2,3,4,5,6,7],
		'instalment'		=> [3,6,12,18,24],
		'repayment'			=> [1],
		'description'		=> '
普匯學生貸
計畫留學、創業或者實現更多理想嗎？
需要資金卻無法向銀行聲請借款嗎？
普匯陪你一起實現夢想'
	],
	3 => [
		'id'				=> 3,
		'type'				=> 1,
		'identity'			=> 2,
		'name'				=> '新鮮人貸',
		'loan_range_s'		=> 10000,
		'loan_range_e'		=> 200000,
		'interest_rate_s'	=> 5,
		'interest_rate_e'	=> 20,
		'certifications'	=> [1,2,3,4,5,6,7],
		'instalment'		=> [3,6,12,18,24],
		'repayment'			=> [1],
		'description'		=> '
普匯學生貸
計畫留學、創業或者實現更多理想嗎？
需要資金卻無法向銀行聲請借款嗎？
普匯陪你一起實現夢想'
	],
	4 => [
		'id'				=> 4,
		'type'				=> 2,
		'identity'			=> 2,
		'name'				=> '新鮮人分期',
		'loan_range_s'		=> 10000,
		'loan_range_e'		=> 200000,
		'interest_rate_s'	=> 5,
		'interest_rate_e'	=> 20,
		'certifications'	=> [1,2,3,4,5,6,7],
		'instalment'		=> [3,6,12,18,24],
		'repayment'			=> [1],
		'description'		=> '
普匯學生貸
計畫留學、創業或者實現更多理想嗎？
需要資金卻無法向銀行聲請借款嗎？
普匯陪你一起實現夢想'
	],
];

//產品型態
$config['product_type']= array(
	1=> '信用貸款',
	2=> '分期付款',
//	3=> '抵押貸款',
);

//產品型態
$config['product_identity']= array(
	1=> '學生',
	2=> '社會新鮮人',
);

//還款方式
$config['repayment_type']= array(
	1=> '等額本息',
//	2=> '先息後本',
);

//學制
$config['school_system']= array(
	0=> '大學',
	1=> '碩士',
	2=> '博士',
);

//科目名稱
$config['transaction_source']= array(
	1	=> '代收',
	2	=> '提領',
	3	=> '出借款',
	4	=> '平台服務費',
	5	=> '轉換產品服務費',
	6	=> '債權轉讓服務費',
	7	=> '提還補償金',
	8	=> '提還違約金',
	9	=> '應付平台服務費',
	10	=> '債權轉讓金',

	11	=> '應付借款本金',
	12	=> '還款本金',
	13	=> '應付借款利息',
	14	=> '還款利息',

	81	=> '平台驗證費',
	82	=> '平台驗證費退回',
	83	=> '跨行轉帳費',
	84	=> '跨行轉帳費退回',

	91	=> '應付違約金',
	92	=> '已還違約金',
	93	=> '應付延滯息',
	94	=> '已還延滯息',
);

$config['transaction_type_name']= array(
	'recharge'		=> '代收',
	'withdraw'		=> '提領',
	'lending'		=> '放款',
	'subloan'		=> '產品轉換',
	'transfer'		=> '債權轉讓',
	'prepayment'	=> '提前還款',
	'charge_delay'	=> '逾期清償',
	'charge_normal'	=> '還款',
);

$config['certifications']= array(
	1 	=> array('id'=>1,'alias'=>'idcard'			,'name'=>'實名認證'		,'status'=>1,'description'=>'驗證個人身份資訊'),
	2 	=> array('id'=>2,'alias'=>'student'			,'name'=>'學生身份認證'	,'status'=>1,'description'=>'驗證學生身份'),
	3 	=> array('id'=>3,'alias'=>'debitcard'		,'name'=>'金融帳號認證'	,'status'=>1,'description'=>'驗證個人金融帳號'),
	4 	=> array('id'=>4,'alias'=>'social'			,'name'=>'社交認證'		,'status'=>1,'description'=>'個人社交帳號認證'),
	5 	=> array('id'=>5,'alias'=>'emergency'		,'name'=>'緊急聯絡人'		,'status'=>1,'description'=>'設定緊急連絡人資訊'),
	6 	=> array('id'=>6,'alias'=>'email'			,'name'=>'常用電子信箱'	,'status'=>1,'description'=>'驗證常用E-Mail位址'),
	7 	=> array('id'=>7,'alias'=>'financial'		,'name'=>'財務訊息認證'	,'status'=>1,'description'=>'提供財務訊息資訊'	),
	8 	=> array('id'=>8,'alias'=>'diploma'			,'name'=>'最高學歷認證'	,'status'=>1,'description'=>'提供最高學歷畢業資訊'	),
	9 	=> array('id'=>9,'alias'=>'investigation'	,'name'=>'聯合徵信認證'	,'status'=>1,'description'=>'提供聯合徵信資訊'	),
	10 	=> array('id'=>10,'alias'=>'job'			,'name'=>'工作認證'		,'status'=>1,'description'=>'提供工作訊息資訊'	),
);

//支援XML銀行列表
$config['xml_bank_list']= array(
'004','005','006','007','008','009','011','012','013','016','017','021',
'039','050','052','053','054','081','101','102','103','108','118','147',
'803','805','806','807','808','809','810','812','815','816','822',
);












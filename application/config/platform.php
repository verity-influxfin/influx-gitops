<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//後台menu
$config['admin_menu'] = [
	'Product' 			=> ['name'=>'產品管理','icon'=>'fa-briefcase'],
	'Target' 			=> [
		'parent_name'				      => '借款管理',
		'index'						      => '全部列表',
		'waiting_signing'			      => '待簽約',
		'waiting_verify'			      => '待審批',
		'waiting_bidding'			      => '已上架',
		'waiting_loan'				      => '待放款',
		'repayment'					      => '還款中',
		'finished'					      => '已結案',
		'prepayment'				      => '提前還款',
        'waiting_approve_order_transfer'  => '消費貸債轉待批覆',
	],
	'Transfer' 						=> [
		'parent_name'				=> '債權管理',
		'index'						=> '全部列表',
		'waiting_transfer'			=> '債轉待收購',
		'waiting_transfer_success'	=> '債轉待放款',
	],
	'Risk' 	=> [
		'parent_name'				=> '風控專區',
		'index'						=> '風控審核',
		'credit'					=> '信評管理',
		'loaned_wait_push' 			=> '貸後催收',
		'loaned_wait_push?slist=1' 	=> '貸後已催收列表',
	],
	'Passbook' 	=> [
        '../Certification/user_bankaccount_list?verify=2'	=> '金融帳號認證',
		'parent_name'				=> '虛擬帳號管理',
		'index'						=> '虛擬帳號列表',
		'withdraw_list'				=> '提領紀錄',
		'withdraw_waiting'			=> '提領待放款',
		'unknown_funds'				=> '不明來源退款',
	],
	'Judicialperson' 	=> [
		'parent_name'				=> '法人帳號管理',
		'index?status=0'			=> '法人管理列表',
		'cooperation?cooperation=2'	=> '經銷商管理列表',
	],
	'Certification' => [
		'parent_name'				=> '認證管理',
		'index'						=> '認證方式列表',
		'user_certification_list'			=> '會員認證審核',
		'difficult_word_list'				=> '銀行困難字管理',
	],
	'Partner' 				=> [
		'parent_name'		=> '合作夥伴管理',
		'partner_type'		=> '合作商類別',
		'index'				=> '合作商列表',
	],
	'Contact' 				=> [
		'parent_name'		=> '客服管理',
		'index'				=> '投訴與建議',
		'send_email'		=> '通知工具',
	],
	'User' 					=> [
	    'parent_name'       => '會員管理',
	    'index'             => '會員列表',
        'block_user'        => '鎖定帳號管理',
    ],
	'Admin' 				=> [
		'parent_name'		=> '後台人員管理',
		'role_list'			=> '權限管理',
		'index'				=> '人員列表',
	],
	'Sales' 				=> [
		'parent_name'		=> '業務報表',
		'index'				=> '借款報表',
		'register_report'	=> '註冊報表',
		'bonus_report'		=> '獎金報表',
	],
	'Account' 				=> [
		'parent_name'		=> '財務作業',
		'daily_report'		=> '交易日報表',
		'passbook_report'	=> '虛擬帳號餘額明細表',
		'estatement'		=> '個人對帳單',
		'index'				=> '收支統計表',
	],
	'Article' 				=> [
		'parent_name'		=> '活動及最新消息',
		'index'				=> '最新活動',
		'index?type=2'		=> '最新消息',
	],
	'Agreement' 			=> ['name'=>'協議書'],
    'Contract' 			    => ['name'=>'合約書'],
];

//內部通知Email
if(ENVIRONMENT=='development'){
	$config['admin_email'] = ['news@influxfin.com'];
}else{
	$config['admin_email'] = ['yaomu@influxfin.com','rogerkuo@influxfin.com','lc@influxfin.com'];//,'mori2.tw@gmail.com'
} 

//期數
$config['instalment']= [
	0=> '其他',
	3=> '3期',
	6=> '6期',
	12=> '12期',
	18=> '18期',
	24=> '24期',
];

//公司型態
$config['company_type']= [
	1=> '獨資',
	2=> '合夥',
	3=> '有限公司',
	4=> '股份有限公司',
];

//產品列表
$config['product_list']= [
	1 => [
		'id'				=> 1,
		'type'				=> 1,
		'identity'			=> 1,
		'alias'				=> 'STN',
		'name'				=> '學生貸',
		'loan_range_s'		=> 5000,
		'loan_range_e'		=> 120000,
		'interest_rate_s'	=> 5,
		'interest_rate_e'	=> 20,
		'certifications'	=> [1,2,3,4,5,6,7],
		'instalment'		=> [3,6,12,18,24],
		'repayment'			=> [1],
		'description'		=> '學生貸
計畫留學、創業或者實現更多理想嗎？
需要資金卻無法向銀行聲請借款嗎？
普匯陪你一起實現夢想'
	],
	2 => [
		'id'				=> 2,
		'type'				=> 2,
		'identity'			=> 1,
		'alias'				=> 'STI',
		'name'				=> '學生手機貸',
		'loan_range_s'		=> 5000,
		'loan_range_e'		=> 120000,
		'interest_rate_s'	=> 18,
		'interest_rate_e'	=> 18,
		'certifications'	=> [1,2,3,4,5,6,7],
		'instalment'		=> [3,6,12,18,24],
		'repayment'			=> [1],
		'description'		=> '學生手機貸
計畫留學、創業或者實現更多理想嗎？
需要資金卻無法向銀行聲請借款嗎？
普匯陪你一起實現夢想'
	],
	3 => [
		'id'				=> 3,
		'type'				=> 1,
		'identity'			=> 2,
		'alias'				=> 'FGN',
		'name'				=> '上班族貸',
		'loan_range_s'		=> 10000,
		'loan_range_e'		=> 200000,
		'interest_rate_s'	=> 5,
		'interest_rate_e'	=> 20,
		'certifications'	=> [1,3,4,5,6,7,8,9,10],
		'instalment'		=> [3,6,12,18,24],
		'repayment'			=> [1],
		'description'		=> '上班族貸
計畫留學、創業或者實現更多理想嗎？
需要資金卻無法向銀行聲請借款嗎？
普匯陪你一起實現夢想'
	],
	4 => [
		'id'				=> 4,
		'type'				=> 2,
		'identity'			=> 2,
		'alias'				=> 'FGI',
		'name'				=> '上班族手機貸',
		'loan_range_s'		=> 10000,
		'loan_range_e'		=> 200000,
		'interest_rate_s'	=> 18,
		'interest_rate_e'	=> 18,
		'certifications'	=> [1,3,4,5,6,7,8,9,10],
		'instalment'		=> [3,6,12,18,24],
		'repayment'			=> [1],
		'description'		=> '上班族手機貸
計畫留學、創業或者實現更多理想嗎？
需要資金卻無法向銀行聲請借款嗎？
普匯陪你一起實現夢想'
	],
/*	5 => [
		'id'				=> 5,
		'type'				=> 1,
		'identity'			=> 2,
		'alias'				=> 'FGD',
		'name'				=> 'Pay Day Loan',
		'loan_range_s'		=> 10000,
		'loan_range_e'		=> 200000,
		'interest_rate_s'	=> 5,
		'interest_rate_e'	=> 20,
		'certifications'	=> [],
		'instalment'		=> [0],
		'repayment'			=> [3],
		'description'		=> '普匯學生貸
計畫留學、創業或者實現更多理想嗎？
需要資金卻無法向銀行聲請借款嗎？
普匯陪你一起實現夢想'
	],*/
];

//產品型態
$config['product_type']= [
	1=> '信用貸款',
	2=> '分期付款',
//	3=> '抵押貸款',
];

//產品型態
$config['product_identity']= [
	1=> '學生',
	2=> '社會新鮮人',
];

//還款方式
$config['repayment_type']= [
	1=> '等額本息',
	2=> '繳息不還本',
	3=> '以日計息',
];

//學制
$config['school_system']= [
	0=> '大學',
	1=> '碩士',
	2=> '博士',
];

//科目名稱
$config['transaction_source']= [
	1	=> '平台代收',
	2	=> '提領',
	3	=> '出借款',
	4	=> '平台服務費',
	5	=> '轉換產品服務費',
	6	=> '債權轉讓服務費',
	7	=> '提前還款補償金',
	8	=> '提前還款違約金',
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
];

$config['transaction_type_name']= [
	'recharge'		=> '代收',
	'withdraw'		=> '提領',
	'lending'		=> '放款',
	'subloan'		=> '產品轉換',
	'transfer'		=> '債權轉讓',
	'prepayment'	=> '提前還款',
	'charge_delay'	=> '逾期清償',
	'charge_normal'	=> '還款',
];

$config['certifications']= [
	1 	=> ['id'=>1,'alias'=>'idcard'			,'name'=>'實名認證'		,'status'=>1,'description'=>'驗證個人身份資訊'],
	2 	=> ['id'=>2,'alias'=>'student'			,'name'=>'學生身份認證'	,'status'=>1,'description'=>'驗證學生身份'],
	3 	=> ['id'=>3,'alias'=>'debitcard'		,'name'=>'金融帳號認證'	,'status'=>1,'description'=>'驗證個人金融帳號'],
	4 	=> ['id'=>4,'alias'=>'social'			,'name'=>'社交認證'		,'status'=>1,'description'=>'個人社交帳號認證'],
	5 	=> ['id'=>5,'alias'=>'emergency'		,'name'=>'緊急聯絡人'	,'status'=>1,'description'=>'設定緊急連絡人資訊'],
	6 	=> ['id'=>6,'alias'=>'email'			,'name'=>'常用電子信箱'	,'status'=>1,'description'=>'驗證常用E-Mail位址'],
	7 	=> ['id'=>7,'alias'=>'financial'		,'name'=>'財務訊息認證'	,'status'=>1,'description'=>'提供財務訊息資訊'	],
	8 	=> ['id'=>8,'alias'=>'diploma'			,'name'=>'最高學歷認證'	,'status'=>1,'description'=>'提供最高學歷畢業資訊'	],
	9 	=> ['id'=>9,'alias'=>'investigation'	,'name'=>'聯合徵信認證'	,'status'=>1,'description'=>'提供聯合徵信資訊'	],
	10 	=> ['id'=>10,'alias'=>'job'			    ,'name'=>'工作認證'		,'status'=>1,'description'=>'提供工作訊息資訊'	],
];

//支援XML銀行列表
$config['xml_bank_list']= [
'004','005','006','007','008','009','011','012','013','016','017','021',
'039','050','052','053','054','081','101','102','103','108','118','147',
'803','805','806','807','808','809','810','812','815','816','822',
];

$config['credit_level']= [1,2,3,4,5,6,7,8,9,10,11,12,13];


$config['job_type_name']= [
	0	=> '外勤',
	1	=> '内勤',
];

$config['seniority_range']= [
	0	=> '三個月以内（含）',
	1	=> '三個月至半年（含）',
	2	=> '半年至一年（含）',
	3	=> '一年至三年（含）',
	4	=> '三年以上',
];

$config['employee_range']= [
	0	=> '1~20（含）',
	1	=> '20~50（含）',
	2	=> '50~100（含）',
	3	=> '100~500（含）',
	4	=> '500~1000（含）',
	5	=> '1000~5000（含）',
	6	=> '5000以上',
];

$config['position_name']= [
	0	=> '一般員工',
	1	=> '初級管理',
	2	=> '中級管理',
	3	=> '高級管理',
];

$config['industry_name']= [
	'A'	=>	'農、林、漁、牧業',
	'B'	=>	'礦業及土石採取業',
	'C'	=>	'製造業',
	'D'	=>	'電力及燃氣供應業',
	'E'	=>	'用水供應及污染整治業',
	'F'	=>	'營建工程業',
	'G'	=>	'批發及零售業',
	'H'	=>	'運輸及倉儲業',
	'I'	=>	'住宿及餐飲業',
	'J'	=>	'出版、影音製作、傳播及資通訊服務業',
	'K'	=>	'金融及保險業',
	'L'	=>	'不動產業',
	'M'	=>	'專業、科學及技術服務業',
	'N'	=>	'支援服務業',
	'O'	=>	'公共行政及國防；強制性社會安全',
	'P'	=>	'教育業',
	'Q'	=>	'醫療保健及社會工作服務業',
	'R'	=>	'藝術、娛樂及休閒服務業',
	'S'	=>	'其他服務業',
];


$config['action_Keyword']= [
	0   => '轉換產品',
];

$config['selling_type']= [
    0   => '手機',
    1   => '遊學',
    2   => '外匯車',
    999 => '其它',
];
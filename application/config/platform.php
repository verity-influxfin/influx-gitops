<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//後台menu
$config['admin_menu'] = array(
	"AdminDashboard" 	=> array("name"=>"Dashboard","icon"=>"fa-dashboard"),
	"Product" 			=> array("name"=>"產品管理","icon"=>"fa-briefcase"),
	"Target" 			=> array(
		"parent_name"	=> "借款管理",
		"parent_icon"	=> "fa-gavel",
		"index"			=> "全部列表",
		"waiting_verify"=> "待審批",
		"waiting_loan"	=> "待放款",
		"repayment"		=> "還款中",
		"prepayment"	=> "提前還款",
	),
	"Transfer" 						=> array(
		"parent_name"				=> "債權管理",
		"parent_icon"				=> "fa-gavel",
		"index"						=> "全部列表",
		"waiting_transfer"			=> "待收購",
		"waiting_transfer_success"	=> "待放款",
	),
	"Risk" 				=> array("name"=>"風控專區","icon"=>"fa-briefcase"),
	"Passbook" 	=> array(
		"parent_name"		=> "虛擬帳號管理",
		"parent_icon"		=> "fa-star",
		"index"				=> "虛擬帳號列表",
		"withdraw_list"		=> "提領紀錄",
		"withdraw_waiting"	=> "提領待放款",
		"unknown_funds"		=> "不明來源退款",
	),
	"Certification" 	=> array(
		"parent_name"	=> "認證管理",
		"parent_icon"	=> "fa-question-circle",
		"index"			=> "認證方式列表",
		"user_certification_list?status=0"		=> "會員認證審核",
		"user_bankaccount_list?verify=2"=> "金融帳號認證",
		"difficult_word_list"			=> "銀行困難字管理",
	),
	"Partner" 			=> array(
		"parent_name"	=> "合作夥伴管理",
		"parent_icon"	=> "fa-group",
		"partner_type"	=> "合作商類別",
		"index"			=> "合作商列表",
	),
	"Contact" 			=> array(
		"parent_name"	=> "客服管理",
		"parent_icon"	=> "fa-star",
		"index"			=> "投訴與建議",
		"send_email"	=> "通知工具",
	),
	"User" 				=> array("name"=>"會員管理","icon"=>"fa-user"),
	"Admin" 			=> array(
		"parent_name"	=> "後台人員管理",
		"parent_icon"	=> "fa-user",
		"role_list"		=> "權限管理",
		"index"			=> "人員列表",
	),
	"Sales" 				=> array(
		"parent_name"		=> "業務報表",
		"parent_icon"		=> "fa-thumbs-up",
		"index"				=> "借款報表",
		"register_report"	=> "註冊報表",
	),
	"Account" 				=> array(
		"parent_name"		=> "財務作業",
		"parent_icon"		=> "fa-thumbs-up",
		"daily_report"		=> "交易日報表",
		"passbook_report"	=> "虛擬帳號餘額明細表",
		"estatement"		=> "個人對帳單",
		"index"				=> "收支統計表",
	),
	"Agreement" 		=> array("name"=>"協議書","icon"=>"fa-star"),
);

//內部通知Email
if(ENVIRONMENT=="development"){
	$config['admin_email'] = array('news@influxfin.com');
}else{
	$config['admin_email'] = array('yaomu@influxfin.com','rogerkuo@influxfin.com','mori2.tw@gmail.com','tim@influxfin.com','jenny@influxfin.com');
} 

//期數
$config['instalment']= array(
	0=> "其他",
	3=> "3期",
	6=> "6期",
	12=> "12期",
	18=> "18期",
	24=> "24期",
);

//還款方式
$config['repayment_type']= array(
	1=> "等額本息",
	2=> "先息後本",
);

//學制
$config['school_system']= array(
	0=> "大學",
	1=> "碩士",
	2=> "博士",
);

//科目名稱
$config['transaction_source']= array(
1	=> "儲值",
2	=> "提領",
3	=> "出借款",
4	=> "平台服務費",
5	=> "轉換產品服務費",
6	=> "債權轉讓服務費",
7	=> "提還補償金",
8	=> "提還違約金",
9	=> "應付平台服務費",
10	=> "債權轉讓金",

11	=> "應付借款本金",
12	=> "還款本金",
13	=> "應付借款利息",
14	=> "還款利息",

81	=> "平台驗證費",
82	=> "平台驗證費退回",
83	=> "跨行轉帳費",
84	=> "跨行轉帳費退回",

91	=> "應付違約金",
92	=> "已還違約金",
93	=> "應付延滯息",
94	=> "已還延滯息",
);

$config['transaction_type_name']= array(
	'recharge'		=> "儲值",
	'withdraw'		=> "提領",
	'lending'		=> "放款",
	'subloan'		=> "產品轉換",
	'transfer'		=> "債權轉讓",
	'prepayment'	=> "提前還款",
	'charge_delay'	=> "逾期清償",
	'charge_normal'	=> "還款",
);

$config['credit_level']= array(
	1 	=> array("start"=>2301	,"end"=>9999,"rate"	=>array("3"=>5	,"6"=>5.5	,"12"=>6,	"18"=>6.5,	"24"=>7)),
	2 	=> array("start"=>2101	,"end"=>2300,"rate"	=>array("3"=>6	,"6"=>6.5	,"12"=>7,	"18"=>7.5,	"24"=>8)),
	3 	=> array("start"=>1801	,"end"=>2100,"rate"	=>array("3"=>7	,"6"=>7.5	,"12"=>8,	"18"=>8.5,	"24"=>9)),
	4 	=> array("start"=>1501	,"end"=>1800,"rate"	=>array("3"=>8	,"6"=>8.5	,"12"=>9,	"18"=>9.5,	"24"=>10)),
	5 	=> array("start"=>1201	,"end"=>1500,"rate"	=>array("3"=>9	,"6"=>10	,"12"=>11,	"18"=>12,	"24"=>14)),
	6 	=> array("start"=>901	,"end"=>1200,"rate"	=>array("3"=>10	,"6"=>11	,"12"=>12,	"18"=>13,	"24"=>15)),
	7 	=> array("start"=>601	,"end"=>900	,"rate"	=>array("3"=>11	,"6"=>12	,"12"=>13,	"18"=>14,	"24"=>16)),
	8 	=> array("start"=>301	,"end"=>600	,"rate"	=>array("3"=>12	,"6"=>13	,"12"=>14,	"18"=>16,	"24"=>18)),
	9 	=> array("start"=>101	,"end"=>300	,"rate"	=>array("3"=>13	,"6"=>14	,"12"=>16,	"18"=>18,	"24"=>20)),
	10 	=> array("start"=>0		,"end"=>100	,"rate"	=>array()),
	11 	=> array("start"=>-500	,"end"=>-1	,"rate"	=>array("3"=>16	,"6"=>17	,"12"=>18,	"18"=>19,	"24"=>20)),
	12 	=> array("start"=>-1500	,"end"=>-501,"rate"	=>array("3"=>18	,"6"=>18	,"12"=>20,	"18"=>20,	"24"=>20)),
	13 	=> array("start"=>-9999	,"end"=>-1501,"rate"=>array())
);

$config['certifications']= array(
	1 => array("id"=>1,"alias"=>"id_card"	,"name"=>"實名認證"		,"status"=>1,"description"=>"驗證個人身份資訊"),
	2 => array("id"=>2,"alias"=>"student"	,"name"=>"學生身份認證"	,"status"=>1,"description"=>"驗證學生身份"),
	3 => array("id"=>3,"alias"=>"debit_card","name"=>"金融帳號認證"	,"status"=>1,"description"=>"驗證個人金融帳號"),
	4 => array("id"=>4,"alias"=>"social"	,"name"=>"社交認證"		,"status"=>1,"description"=>"個人社交帳號認證"),
	5 => array("id"=>5,"alias"=>"emergency"	,"name"=>"緊急聯絡人"		,"status"=>1,"description"=>"設定緊急連絡人資訊"),
	6 => array("id"=>6,"alias"=>"email"		,"name"=>"常用電子信箱"	,"status"=>1,"description"=>"驗證常用E-Mail位址"),
	7 => array("id"=>7,"alias"=>"financial"	,"name"=>"財務訊息認證"	,"status"=>1,"description"=>"提供財務訊息資訊"	),
);

$config['school_major_point']= array(
	'醫藥衛生學門'			=> 400,
	'教育學門'				=> 400,
	'運輸服務學門'			=> 400,
	'資訊通訊科技學門'			=> 400,
	'工程及工程業學門'			=> 400,
	'建築及營建工程學門'		=> 300,
	'商業及管理學門'			=> 300,
	'物理、化學及地球科學學門'	=> 300,
	'社會及行為科學學門'		=> 300,
	'獸醫學門'				=> 300,
	'數學及統計學門'			=> 300,
	'法律學門'				=> 300,
	'安全服務學門'			=> 300,
	'製造及加工學門'			=> 300,
	'衛生及職業衛生服務學門'	=> 300,
	'農業學門'				=> 200,
	'餐旅及民生服務學門'		=> 200,
	'環境學門'				=> 200,
	'人文學門'				=> 200,
	'語文學門'				=> 200,
	'社會福利學門'			=> 200,
	'新聞學及圖書資訊學門'		=> 200,
);

$config['credit_amount']= array(
	array("start"=>2481,"end"=>9999,"amount"=>120000),
	array("start"=>2461,"end"=>2480,"amount"=>119000), 
	array("start"=>2441,"end"=>2460,"amount"=>118000),
	array("start"=>2421,"end"=>2440,"amount"=>117000),
	array("start"=>2401,"end"=>2420,"amount"=>116000),
	array("start"=>2381,"end"=>2400,"amount"=>115000),
	array("start"=>2361,"end"=>2380,"amount"=>114000),
	array("start"=>2341,"end"=>2360,"amount"=>113000),
	array("start"=>2321,"end"=>2340,"amount"=>112000),
	array("start"=>2301,"end"=>2320,"amount"=>111000),
	array("start"=>2281,"end"=>2300,"amount"=>110000),
	array("start"=>2261,"end"=>2280,"amount"=>109000),
	array("start"=>2241,"end"=>2260,"amount"=>108000),
	array("start"=>2221,"end"=>2240,"amount"=>107000),
	array("start"=>2201,"end"=>2220,"amount"=>106000),
	array("start"=>2181,"end"=>2200,"amount"=>105000),
	array("start"=>2161,"end"=>2180,"amount"=>104000),
	array("start"=>2141,"end"=>2160,"amount"=>103000),
	array("start"=>2121,"end"=>2140,"amount"=>102000),
	array("start"=>2101,"end"=>2120,"amount"=>101000),
	array("start"=>2081,"end"=>2100,"amount"=>100000),
	array("start"=>2061,"end"=>2080,"amount"=>99000),
	array("start"=>2041,"end"=>2060,"amount"=>98000),
	array("start"=>2021,"end"=>2040,"amount"=>97000),
	array("start"=>2001,"end"=>2020,"amount"=>96000),
	array("start"=>1981,"end"=>2000,"amount"=>95000),
	array("start"=>1961,"end"=>1980,"amount"=>94000),
	array("start"=>1941,"end"=>1960,"amount"=>93000),
	array("start"=>1921,"end"=>1940,"amount"=>92000),
	array("start"=>1901,"end"=>1920,"amount"=>91000),
	array("start"=>1881,"end"=>1900,"amount"=>90000),
	array("start"=>1861,"end"=>1880,"amount"=>89000),
	array("start"=>1841,"end"=>1860,"amount"=>88000),
	array("start"=>1821,"end"=>1840,"amount"=>87000),
	array("start"=>1801,"end"=>1820,"amount"=>86000),
	array("start"=>1781,"end"=>1800,"amount"=>85000),
	array("start"=>1761,"end"=>1780,"amount"=>84000),
	array("start"=>1741,"end"=>1760,"amount"=>83000),
	array("start"=>1721,"end"=>1740,"amount"=>82000),
	array("start"=>1701,"end"=>1720,"amount"=>81000),
	array("start"=>1681,"end"=>1700,"amount"=>80000),
	array("start"=>1661,"end"=>1680,"amount"=>79000),
	array("start"=>1641,"end"=>1660,"amount"=>78000),
	array("start"=>1621,"end"=>1640,"amount"=>77000),
	array("start"=>1601,"end"=>1620,"amount"=>76000),
	array("start"=>1581,"end"=>1600,"amount"=>75000),
	array("start"=>1561,"end"=>1580,"amount"=>74000),
	array("start"=>1541,"end"=>1560,"amount"=>73000),
	array("start"=>1521,"end"=>1540,"amount"=>72000),
	array("start"=>1501,"end"=>1520,"amount"=>71000),
	array("start"=>1481,"end"=>1500,"amount"=>70000),
	array("start"=>1461,"end"=>1480,"amount"=>69000),
	array("start"=>1441,"end"=>1460,"amount"=>68000),
	array("start"=>1421,"end"=>1440,"amount"=>67000),
	array("start"=>1401,"end"=>1420,"amount"=>66000),
	array("start"=>1381,"end"=>1400,"amount"=>65000),
	array("start"=>1361,"end"=>1380,"amount"=>64000),
	array("start"=>1341,"end"=>1360,"amount"=>63000),
	array("start"=>1321,"end"=>1340,"amount"=>62000),
	array("start"=>1301,"end"=>1320,"amount"=>61000),
	array("start"=>1281,"end"=>1300,"amount"=>60000),
	array("start"=>1261,"end"=>1280,"amount"=>59000),
	array("start"=>1241,"end"=>1260,"amount"=>58000),
	array("start"=>1221,"end"=>1240,"amount"=>57000),
	array("start"=>1201,"end"=>1220,"amount"=>56000),
	array("start"=>1181,"end"=>1200,"amount"=>55000),
	array("start"=>1161,"end"=>1180,"amount"=>54000),
	array("start"=>1141,"end"=>1160,"amount"=>53000),
	array("start"=>1121,"end"=>1140,"amount"=>52000),
	array("start"=>1101,"end"=>1120,"amount"=>51000),
	array("start"=>1081,"end"=>1100,"amount"=>50000),
	array("start"=>1061,"end"=>1080,"amount"=>49000),
	array("start"=>1041,"end"=>1060,"amount"=>48000),
	array("start"=>1021,"end"=>1040,"amount"=>47000),
	array("start"=>1001,"end"=>1020,"amount"=>46000),
	array("start"=>981,"end"=>1000,"amount"=>45000),
	array("start"=>961,"end"=>980,"amount"=>44000),
	array("start"=>941,"end"=>960,"amount"=>43000),
	array("start"=>921,"end"=>940,"amount"=>42000),
	array("start"=>901,"end"=>920,"amount"=>41000),
	array("start"=>881,"end"=>900,"amount"=>40000),
	array("start"=>861,"end"=>880,"amount"=>39000),
	array("start"=>841,"end"=>860,"amount"=>38000),
	array("start"=>821,"end"=>840,"amount"=>37000),
	array("start"=>801,"end"=>820,"amount"=>36000),
	array("start"=>781,"end"=>800,"amount"=>35000),
	array("start"=>761,"end"=>780,"amount"=>34000),
	array("start"=>741,"end"=>760,"amount"=>33000),
	array("start"=>721,"end"=>740,"amount"=>32000),
	array("start"=>701,"end"=>720,"amount"=>31000),
	array("start"=>681,"end"=>700,"amount"=>30000),
	array("start"=>661,"end"=>680,"amount"=>29000),
	array("start"=>641,"end"=>660,"amount"=>28000),
	array("start"=>621,"end"=>640,"amount"=>27000),
	array("start"=>601,"end"=>620,"amount"=>26000),
	array("start"=>581,"end"=>600,"amount"=>25000),
	array("start"=>561,"end"=>580,"amount"=>24000),
	array("start"=>541,"end"=>560,"amount"=>23000),
	array("start"=>521,"end"=>540,"amount"=>22000),
	array("start"=>501,"end"=>520,"amount"=>21000),
	array("start"=>481,"end"=>500,"amount"=>20000),
	array("start"=>461,"end"=>480,"amount"=>19000),
	array("start"=>441,"end"=>460,"amount"=>18000),
	array("start"=>421,"end"=>440,"amount"=>17000),
	array("start"=>401,"end"=>420,"amount"=>16000),
	array("start"=>381,"end"=>400,"amount"=>15000),
	array("start"=>361,"end"=>380,"amount"=>14000),
	array("start"=>341,"end"=>360,"amount"=>13000),
	array("start"=>321,"end"=>340,"amount"=>12000),
	array("start"=>301,"end"=>320,"amount"=>11000),
	array("start"=>281,"end"=>300,"amount"=>10000),
	array("start"=>261,"end"=>280,"amount"=>9000),
	array("start"=>241,"end"=>260,"amount"=>8000),
	array("start"=>221,"end"=>240,"amount"=>7000),
	array("start"=>201,"end"=>220,"amount"=>6000),
	array("start"=>100,"end"=>200,"amount"=>5000)
);

//支援XML銀行列表
$config['xml_bank_list']= array(
'004','005','006','007','008','009','011','012','013','016','017','021',
'039','050','052','053','054','081','101','102','103','108','118','147',
'803','805','806','807','808','809','810','812','815','816','822',
);












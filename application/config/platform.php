<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config['admin_menu'] = array(
	"AdminDashboard" 	=> array("name"=>"Dashboard","icon"=>"fa-dashboard"),
	/*"Product" 			=> array(
		"parent_name"	=> "產品管理",
		"parent_icon"	=> "fa-briefcase",
		"index"			=> "產品列表",
	),*/
	"Target" 			=> array(
		"parent_name"	=> "借款管理",
		"parent_icon"	=> "fa-gavel",
		"index"			=> "借款列表",
	),
	"Passbook" 			=> array("name"=>"虛擬帳號管理","icon"=>"fa-star"),
	"Certification" 	=> array(
		"parent_name"	=> "認證管理",
		"parent_icon"	=> "fa-question-circle",
		//"index"			=> "認證方式列表",
		"user_certification_list"		=> "認證審核",
		"user_bankaccount_list"			=> "金融帳號",
	),
	"Partner" 			=> array(
		"parent_name"	=> "合作夥伴",
		"parent_icon"	=> "fa-group",
		"index"			=> "合作商列表",
	),
	"Contact" 			=> array("name"=>"投訴與建議","icon"=>"fa-star"),
	"User" 				=> array("name"=>"會員管理","icon"=>"fa-user"),
	"Admin" 			=> array("name"=>"後台人員管理","icon"=>"fa-user"),
	
	//"Agreement" 		=> array("name"=>"協議書","icon"=>"fa-star"),
	//"Contract" 		=> array("name"=>"合約書","icon"=>"fa-star"),
);

$config['instalment']= array(
0=> "其他",
3=> "3期",
6=> "6期",
12=> "12期",
18=> "18期",
24=> "24期",
);

$config['repayment_type']= array(
1=> "等額本息",
2=> "先息後本",
);

$config['transaction_source']= array(
1=> "儲值",
2=> "提領",
3=> "出借款",
4=> "平台服務費",
5=> "轉換產品手續費",
6=> "債權轉讓手續費",
7=> "提還補償金",
8=> "提還違約金",
9=> "應付平台服務費",
10=> "債權轉讓金",

11=> "應付借款本金",
12=> "還款本金",
13=> "應付借款利息",
14=> "還款利息",

91=> "應付違約金",
92=> "已還違約金",
93=> "應付延滯息",
94=> "已還延滯息",
);


$config['credit_level']= array(
1 => array("start"=>2001,"end"=>2500,"rate"=>array("3"=>5,"6"=>6,"12"=>7,"18"=>8,"24"=>9)),
2 => array("start"=>1501,"end"=>2000,"rate"=>array("3"=>6,"6"=>7,"12"=>8,"18"=>9,"24"=>10)),
3 => array("start"=>1001,"end"=>1500,"rate"=>array("3"=>7,"6"=>8,"12"=>9,"18"=>10,"24"=>12)),
4 => array("start"=>501,"end"=>1000,"rate"=>array("3"=>8,"6"=>9,"12"=>10,"18"=>12,"24"=>15)),
5 => array("start"=>101,"end"=>500,"rate"=>array("3"=>9,"6"=>10,"12"=>12,"18"=>15,"24"=>20)),
6 => array("start"=>-501,"end"=>100,"rate"=>array("3"=>11,"6"=>12,"12"=>15,"18"=>18,"24"=>20)),
7 => array("start"=>-1500,"end"=>-500,"rate"=>array("3"=>16,"6"=>17,"12"=>18,"18"=>19,"24"=>20)),
8 => array()
);

$config['credit_amount']= array(
array("start"=>2481,"end"=>2500,"amount"=>120000),
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
















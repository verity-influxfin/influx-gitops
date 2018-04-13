<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
1=> "儲值",//出借->平台
2=> "提領(出借)",//出借<-平台
3=> "出借款(出借)",//出借->借款
4=> "平台服務費",

11=> "應付借款本金(借款)",//借款->出借
12=> "還款本金(借款)",//借款->出借
13=> "應付借款利息(借款)",
14=> "還款利息(借款)",

21=> "應付轉貸手續費(借款)",
22=> "轉貸手續費(借款)",
23=> "應付提還手續費(出借)",
24=> "提還手續費(出借)",
25=> "應付提領手續費(出借)",
26=> "提領手續費(出借)",

96=> "應付違約金(借款)",
97=> "違約金(借款)",
98=> "應付延滯息(借款)",
99=> "延滯息(借款)",
);

/*
應付提還補貼金(平台)
應付滯納補貼金(平台)
提還補貼金(平台)
滯納補貼金(平台)
*/

$config['credit_level']= array(
1 => array("start"=>1901,"end"=>2600,"rate"=>array("3"=>5,"6"=>6,"12"=>7,"18"=>8,"24"=>9)),
2 => array("start"=>1501,"end"=>1900,"rate"=>array("3"=>6,"6"=>7,"12"=>8,"18"=>9,"24"=>10)),
3 => array("start"=>1001,"end"=>1500,"rate"=>array("3"=>7,"6"=>8,"12"=>9,"18"=>10,"24"=>12)),
4 => array("start"=>501,"end"=>1000,"rate"=>array("3"=>8,"6"=>9,"12"=>10,"18"=>12,"24"=>15)),
5 => array("start"=>101,"end"=>500,"rate"=>array("3"=>9,"6"=>10,"12"=>12,"18"=>15,"24"=>20)),
6 => array("start"=>-501,"end"=>100,"rate"=>array("3"=>11,"6"=>12,"12"=>15,"18"=>18,"24"=>20)),
7 => array("start"=>-1500,"end"=>-500,"rate"=>array("3"=>16,"6"=>17,"12"=>18,"18"=>19,"24"=>20)),
8 => array(),
9 => array(),
);

$config['credit_amount']= array(
array("start"=>2561,"end"=>2600,"amount"=>120,000),
array("start"=>2521,"end"=>2560,"amount"=>119,000),
array("start"=>2481,"end"=>2520,"amount"=>118,000),
array("start"=>2441,"end"=>2480,"amount"=>117,000),
array("start"=>2401,"end"=>2440,"amount"=>116,000),
array("start"=>2361,"end"=>2400,"amount"=>115,000),
array("start"=>2321,"end"=>2360,"amount"=>114,000),
array("start"=>2281,"end"=>2320,"amount"=>113,000),
array("start"=>2241,"end"=>2280,"amount"=>112,000),
array("start"=>2201,"end"=>2240,"amount"=>111,000),
array("start"=>2161,"end"=>2200,"amount"=>110,000),
array("start"=>2121,"end"=>2160,"amount"=>109,000),
array("start"=>2081,"end"=>2120,"amount"=>108,000),
array("start"=>2041,"end"=>2080,"amount"=>107,000),
array("start"=>2001,"end"=>2040,"amount"=>106,000),
array("start"=>1981,"end"=>2000,"amount"=>105,000),
array("start"=>1961,"end"=>1980,"amount"=>104,000),
array("start"=>1941,"end"=>1960,"amount"=>103,000),
array("start"=>1921,"end"=>1940,"amount"=>102,000),
array("start"=>1901,"end"=>1920,"amount"=>101,000),
array("start"=>1881,"end"=>1900,"amount"=>100,000),
array("start"=>1861,"end"=>1880,"amount"=>99,000),
array("start"=>1841,"end"=>1860,"amount"=>98,000),
array("start"=>1821,"end"=>1840,"amount"=>97,000),
array("start"=>1801,"end"=>1820,"amount"=>96,000),
array("start"=>1781,"end"=>1800,"amount"=>95,000),
array("start"=>1761,"end"=>1780,"amount"=>94,000),
array("start"=>1741,"end"=>1760,"amount"=>93,000),
array("start"=>1721,"end"=>1740,"amount"=>92,000),
array("start"=>1701,"end"=>1720,"amount"=>91,000),
array("start"=>1681,"end"=>1700,"amount"=>90,000),
array("start"=>1661,"end"=>1680,"amount"=>89,000),
array("start"=>1641,"end"=>1660,"amount"=>88,000),
array("start"=>1621,"end"=>1640,"amount"=>87,000),
array("start"=>1601,"end"=>1620,"amount"=>86,000),
array("start"=>1581,"end"=>1600,"amount"=>85,000),
array("start"=>1561,"end"=>1580,"amount"=>84,000),
array("start"=>1541,"end"=>1560,"amount"=>83,000),
array("start"=>1521,"end"=>1540,"amount"=>82,000),
array("start"=>1501,"end"=>1520,"amount"=>81,000),
array("start"=>1481,"end"=>1500,"amount"=>80,000),
array("start"=>1461,"end"=>1480,"amount"=>79,000),
array("start"=>1441,"end"=>1460,"amount"=>78,000),
array("start"=>1421,"end"=>1440,"amount"=>77,000),
array("start"=>1401,"end"=>1420,"amount"=>76,000),
array("start"=>1381,"end"=>1400,"amount"=>75,000),
array("start"=>1361,"end"=>1380,"amount"=>74,000),
array("start"=>1341,"end"=>1360,"amount"=>73,000),
array("start"=>1321,"end"=>1340,"amount"=>72,000),
array("start"=>1301,"end"=>1320,"amount"=>71,000),
array("start"=>1281,"end"=>1300,"amount"=>70,000),
array("start"=>1261,"end"=>1280,"amount"=>69,000),
array("start"=>1241,"end"=>1260,"amount"=>68,000),
array("start"=>1221,"end"=>1240,"amount"=>67,000),
array("start"=>1201,"end"=>1220,"amount"=>66,000),
array("start"=>1181,"end"=>1200,"amount"=>65,000),
array("start"=>1161,"end"=>1180,"amount"=>64,000),
array("start"=>1141,"end"=>1160,"amount"=>63,000),
array("start"=>1121,"end"=>1140,"amount"=>62,000),
array("start"=>1101,"end"=>1120,"amount"=>61,000),
array("start"=>1081,"end"=>1100,"amount"=>60,000),
array("start"=>1061,"end"=>1080,"amount"=>59,000),
array("start"=>1041,"end"=>1060,"amount"=>58,000),
array("start"=>1021,"end"=>1040,"amount"=>57,000),
array("start"=>1001,"end"=>1020,"amount"=>56,000),
array("start"=>981,"end"=>1000,"amount"=>55,000),
array("start"=>961,"end"=>980,"amount"=>54,000),
array("start"=>941,"end"=>960,"amount"=>53,000),
array("start"=>921,"end"=>940,"amount"=>52,000),
array("start"=>901,"end"=>920,"amount"=>51,000),
array("start"=>881,"end"=>900,"amount"=>50,000),
array("start"=>861,"end"=>880,"amount"=>49,000),
array("start"=>841,"end"=>860,"amount"=>48,000),
array("start"=>821,"end"=>840,"amount"=>47,000),
array("start"=>801,"end"=>820,"amount"=>46,000),
array("start"=>781,"end"=>800,"amount"=>45,000),
array("start"=>761,"end"=>780,"amount"=>44,000),
array("start"=>741,"end"=>760,"amount"=>43,000),
array("start"=>721,"end"=>740,"amount"=>42,000),
array("start"=>701,"end"=>720,"amount"=>41,000),
array("start"=>681,"end"=>700,"amount"=>40,000),
array("start"=>661,"end"=>680,"amount"=>39,000),
array("start"=>641,"end"=>660,"amount"=>38,000),
array("start"=>621,"end"=>640,"amount"=>37,000),
array("start"=>601,"end"=>620,"amount"=>36,000),
array("start"=>581,"end"=>600,"amount"=>35,000),
array("start"=>561,"end"=>580,"amount"=>34,000),
array("start"=>541,"end"=>560,"amount"=>33,000),
array("start"=>521,"end"=>540,"amount"=>32,000),
array("start"=>501,"end"=>520,"amount"=>31,000),
array("start"=>481,"end"=>500,"amount"=>30,000),
array("start"=>461,"end"=>480,"amount"=>28,000),
array("start"=>441,"end"=>460,"amount"=>26,000),
array("start"=>421,"end"=>440,"amount"=>24,000),
array("start"=>401,"end"=>420,"amount"=>22,000),
array("start"=>381,"end"=>400,"amount"=>20,000),
array("start"=>361,"end"=>380,"amount"=>18,000),
array("start"=>341,"end"=>360,"amount"=>16,000),
array("start"=>321,"end"=>340,"amount"=>14,000),
array("start"=>301,"end"=>320,"amount"=>12,000),
array("start"=>281,"end"=>300,"amount"=>10,000),
array("start"=>261,"end"=>280,"amount"=>9,000),
array("start"=>241,"end"=>260,"amount"=>8,000),
array("start"=>221,"end"=>240,"amount"=>7,000),
array("start"=>201,"end"=>220,"amount"=>6,000),
array("start"=>100,"end"=>200,"amount"=>5,000)
);
















<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['apiList'] = [
    'U1' => 'userLogin',
    'U2' => 'userInfo',
    'U3' => 'userEditpw',
    'U4' => 'userAdd',
    'U5' => 'userList',
];

$config['nonAuthMethods'] = [
    'login'
];

$config['noLog'] = [
    'list',
    'info'
];

$config['targetDelayRange'] = [
    0 => '觀察資產(D7)',
    1 => '關注資產(M1)',
    2 => '次級資產(M2)',
    3 => '可疑資產(M3)',
    4 => '不良資產(>M3)',
];

$config['delayStatus'] = [
    0 => '正常',
    1 => '寬限',
    2 => '逾期',
];

$config['pushDataStatus'] = [
    0 => "催收列表",
    1 => "產轉協商",
    2 => "法催執行",
];

$config['pushIdentity'] = [
    0 => "身分待確認",
    1 => "已畢業",
    2 => "失聯",
    3 => "工作中",
];

$config['pushDataUserStatus'] = [
    0 => "待確認",
    1 => "有望催回",
    2 => "不穩定",
    3 => "無望催回",
];

$config['pushTool'] = [
    0 => "其它",
    1 => "LINE",
    2 => "Facebook",
    3 => "緊急聯絡人",
    4 => "系統訊息",
    5 => "instgram",
    6 => "電話",
    7 => "簡訊",
    8 => "上門",
    9 => "信件",
    10 => "律師函",
    11 => "面談",
];
defined('PUSH_BY_LINE') or define('PUSH_BY_LINE', '1');
defined('PUSH_BY_FACEBOOK') or define('PUSH_BY_FACEBOOK', '2');
defined('PUSH_BY_EMERGENCY_PHONE') or define('PUSH_BY_EMERGENCY_PHONE', '3');
defined('PUSH_BY_INSTGRAM') or define('PUSH_BY_INSTGRAM', '5');
defined('PUSH_BY_USER_PHONE') or define('PUSH_BY_USER_PHONE', '6');
defined('PUSH_BY_SMS') or define('PUSH_BY_SMS', '7');

$config['pushType'] = [
    0 => "提醒",
    1 => "協商",
    2 => "催收",
];

$config['pushResultStatus'] = [
    0 => "未處理",
    1 => "已發送",
    2 => "發送失敗",
    3 => "接通",
    4 => "未接通",
    5 => "已面談",
];

$config['contactRelationship'] = [
    0 => "本人",
    1 => "親人",
    2 => "朋友",
];
$config['certifications'] = [
    1 => ['id' => 1, 'alias' => 'idcard', 'name' => '實名認證', 'status' => 1],
    2 => ['id' => 2, 'alias' => 'student', 'name' => '學生身份認證', 'status' => 1],
    3 => ['id' => 3, 'alias' => 'debitcard', 'name' => '金融帳號認證', 'status' => 1],
    4 => ['id' => 4, 'alias' => 'social', 'name' => '社交認證', 'status' => 1],
    5 => ['id' => 5, 'alias' => 'emergency', 'name' => '緊急聯絡人', 'status' => 1],
    6 => ['id' => 6, 'alias' => 'email', 'name' => '常用電子信箱', 'status' => 1],
    7 => ['id' => 7, 'alias' => 'financial', 'name' => '財務訊息認證', 'status' => 1],
    8 => ['id' => 8, 'alias' => 'diploma', 'name' => '最高學歷認證', 'status' => 1],
    9 => ['id' => 9, 'alias' => 'investigation', 'name' => '聯合徵信認證', 'status' => 1],
    10 => ['id' => 10, 'alias' => 'job', 'name' => '工作認證', 'status' => 1],
];

$config['cer_status'] = [
    0 => "待驗證" ,
    1 => "" ,
    2 => "無效/過期" ,
    3 => "待人工審核" ,
    4 => "未完成填寫" ,
];
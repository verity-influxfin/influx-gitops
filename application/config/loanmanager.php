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
    0 => "未執行",
    1 => "催收列表",
    2 => "產轉協商",
    3 => "法催執行",
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
    8 => "上門催收",
    9 => "信件催收",
    10 => "律師函",
];

$config['pushResultStatus'] = [
    0 => "未處理",
    1 => "已發送",
    2 => "可連絡",
    3 => "聯絡失敗",
    4 => "失聯",
];

$config['contactRelationship'] = [
    0 => "本人",
    1 => "親人",
    2 => "朋友",
];

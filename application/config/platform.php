<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//後台menu
$config['admin_menu'] = [
    'Product' => ['name' => '產品管理', 'icon' => 'fa-briefcase'],
	'AntiFraud' => [
		'name' => '反詐欺管理指標'
	],
    'Target' => [
        'parent_name' => '借款管理',
        'index' => '全部列表',
        'waiting_evaluation' => '二審',
        'waiting_signing' => '待簽約',
        'waiting_verify' => '待上架',
        'waiting_bidding' => '已上架',
        'waiting_loan' => '待放款',
        'repayment' => '還款中',
        'finished' => '已結案',
        'prepayment' => '提前還款',
        'order_target' => '消費貸 - 案件列表',
        'waiting_approve_order_transfer' => '消費貸 - 債轉待批覆',
    ],
    'Transfer' => [
        'parent_name' => '債權管理',
        'index' => '全部列表',
        //'assets_list' => '資產列表(全部列表v2)',
        'obligations' => '全部列表(New)',
        'waiting_transfer' => '債轉待收購',
        'waiting_transfer_success' => '債轉待放款',
    ],
    'Risk' => [
        'parent_name' => '風控專區',
        //'index' => '風控審核(全)',
        'index?investor=0&company=0' => '自然人借款端審核',
        'index?investor=0&company=1' => '法人借款端審核',
        'index?investor=1' => '投資端審核',
        'credit' => '信評管理',
		'credit_management' => '授信審核表'
        //'loaned_wait_push' => '貸後催收',
        //'loaned_wait_push?slist=1' => '貸後已催收列表',
    ],
    'Passbook' => [
        '../Certification/user_bankaccount_list?verify=2' => '金融帳號認證',
        'parent_name' => '虛擬帳號管理',
        'index' => '虛擬帳號列表',
        'withdraw_list' => '提領紀錄',
        'withdraw_waiting' => '提領待放款',
        'unknown_funds' => '不明來源退款',
    ],
    'Judicialperson' => [
        'parent_name' => '法人管理',
        'index?status=0' => '法人申請列表',
        'index?status=1' => '法人管理列表',
        'cooperation?cooperation=2' => '經銷商申請列表',
        'cooperation?cooperation=1' => '經銷商管理列表',
    ],
    'Certification' => [
        'parent_name' => '認證管理',
        'index' => '認證方式列表',
        'user_certification_list' => '會員認證審核',
        'difficult_word_list' => '銀行困難字管理',
    ],
    'Partner' => [
        'parent_name' => '合作夥伴管理',
        'partner_type' => '合作商類別',
        'index' => '合作商列表',
    ],
    'Contact' => [
        'parent_name' => '客服管理',
        'index' => '投訴與建議',
        'send_email' => '通知工具',
        'certifications' => '會員認證審核列表',
    ],
    'User' => [
        'parent_name' => '會員管理',
        'index' => '會員列表',
        'blocked_users' => '鎖定帳號管理',
    ],
    'Admin' => [
        'parent_name' => '後台人員管理',
        'role_list' => '權限管理',
        'index' => '人員列表',
    ],
    'Sales' => [
        'parent_name' => '業務報表',
        'index' => '借款報表',
        'register_report' => '註冊報表',
        'bonus_report' => '獎金報表',
        'loan_overview' => '申貸總覽'
    ],
    'Account' => [
        'parent_name' => '財務作業',
        'daily_report' => '虛擬帳戶交易明細表',
        'passbook_report' => '虛擬帳號餘額明細表',
        'estatement' => '個人對帳單',
        'index' => '收支統計表',
    ],
    'Ocr' => [
        'parent_name' => 'OCR 結果',
        'index' => '報表',
    ],
    'Article' => [
        'parent_name' => '活動及最新消息',
        'index' => '最新活動',
        'index?type=2' => '最新消息',
    ],
    // 'Creditmanagementtable' => [
    //     'parent_name' => '授信審核表',
    //     'index' => '報表',
    // ],
    'Agreement' => ['name' => '協議書'],
    'Contract' => ['name' => '合約書'],
];

//內部通知Email
if (ENVIRONMENT == 'development') {
    $config['admin_email'] = ['news@influxfin.com', 'brian@influxfin.com'];
} else {
    $config['admin_email'] = ['yaomu@influxfin.com'];
}

//期數
$config['instalment'] = [
    0 => '其他',
    3 => '3期',
    6 => '6期',
    12 => '12期',
    18 => '18期',
    24 => '24期',
    36 => '36期',
    90 => '最高90期',
    180 => '最高180期',
];

//公司型態
$config['company_type'] = [
    1 => '獨資',
    2 => '合夥',
    3 => '有限公司',
    4 => '股份有限公司',
];


// 'id' => 1,
// app端顯示資訊
// 'visul_id' => 'N1',
// 類型, 信用貸款=1, 消費貸款=2
// 'type' => 1,
// 身份, 學生=1, 上班族=2, 法人=3
// 'identity' => 1,
// 'alias' => 'STN',
// 'name' => '學生貸',
// 最低借款金額
// 'loan_range_s' => 5000,
// 最高借款金額
// 'loan_range_e' => 120000,
// 最低利率
// 'interest_rate_s' => 5,
// 最高利率
// 'interest_rate_e' => 20,
// 平台手續費
// 'charge_platform' => PLATFORM_FEES,
// 最低平台手續費
// 'charge_platform_min' => PLATFORM_FEES_MIN,
// 'sub_product' => [5000, STAGE_CER_TARGET, 1],
// 需要認證或徵信之項目列表
// 'certifications' => [
//     CERTIFICATION_IDCARD,
//     CERTIFICATION_STUDENT,
//     CERTIFICATION_DEBITCARD,
//     CERTIFICATION_SOCIAL,
//     CERTIFICATION_EMERGENCY,
//     CERTIFICATION_EMAIL,
//     CERTIFICATION_FINANCIAL
// ],
// 分期期數
// 'instalment' => [3, 6, 12, 18, 24],
// 計息方式, 本息均攤=1, 繳息不還本/按月付息=2, 以日計息=3,
// 'repayment' => [1],
// 產品的檢附資料, key: [certification_id=該案認證的資訊, credit_level=信用評級, original_interest_rate=利息
// 'targetData' => [],
// 是否強制二審
// 'secondInstance' => false,
// 'weight' => [],
// 該產品狀態, 關閉=0, 開啟=1
// 'status' => 1,
// 經銷商類別
// 'dealer' => [],
// 是否允許多案, 0=不允許, 1= 允許
// 'multi_target' => 0,
// 'hiddenMainProduct' => false,
// 'description' => '須提供有效學生證<br>可申請額度<br>5,000-120,000',
// 法人負責人判斷
// 'checkOwner' => false,

//產品列表
$config['product_list'] = [
    1 => [
        'id' => 1,
        'visul_id' => 'N1',
        'type' => 1,
        'identity' => 1,
        'alias' => 'STN',
        'name' => '學生貸',
        'loan_range_s' => 5000,
        'loan_range_e' => 120000,
        'interest_rate_s' => 5,
        'interest_rate_e' => 20,
        'charge_platform' => PLATFORM_FEES,
        'charge_platform_min' => PLATFORM_FEES_MIN,
        'sub_product' => [5000, STAGE_CER_TARGET, 1],
        'certifications' => [
            CERTIFICATION_IDCARD,
            CERTIFICATION_STUDENT,
            CERTIFICATION_DEBITCARD,
            CERTIFICATION_SOCIAL,
            CERTIFICATION_EMERGENCY,
            CERTIFICATION_EMAIL,
            CERTIFICATION_FINANCIAL
        ],
        'instalment' => [3, 6, 12, 18, 24],
        'repayment' => [1],
        'targetData' => [],
        'secondInstance' => false,
        'weight' => [],
        'status' => 1,
        'dealer' => [],
        'multi_target' => 0,
        'hiddenMainProduct' => false,
        'hiddenSubProduct' => false,
        'description' => '須提供有效學生證<br>可申請額度<br>5,000-120,000',
        'checkOwner' => false,
    ],
    2 => [
        'id' => 2,
        'visul_id' => 'N2',
        'type' => 2,
        'identity' => 1,
        'alias' => 'STI',
        'name' => '學生手機貸',
        'loan_range_s' => 5000,
        'loan_range_e' => 120000,
        'interest_rate_s' => ORDER_INTEREST_RATE,
        'interest_rate_e' => ORDER_INTEREST_RATE,
        'charge_platform' => PLATFORM_FEES,
        'charge_platform_min' => PLATFORM_FEES_MIN,
        'sub_product' => [],
        'certifications' => [
            CERTIFICATION_IDCARD,
            CERTIFICATION_STUDENT,
            CERTIFICATION_DEBITCARD,
            CERTIFICATION_SOCIAL,
            CERTIFICATION_EMERGENCY,
            CERTIFICATION_EMAIL,
            CERTIFICATION_FINANCIAL
        ],
        'instalment' => [3, 6, 12, 18, 24],
        'repayment' => [1],
        'targetData' => [],
        'secondInstance' => false,
        'weight' => [],
        'status' => 1,
        'dealer' => [],
        'multi_target' => 0,
        'hiddenMainProduct' => false,
        'hiddenSubProduct' => false,
        'description' => '須提供有效學生證<br>可申請額度<br>5,000-120,000',
        'checkOwner' => false,
    ],
    3 => [
        'id' => 3,
        'visul_id' => 'N1',
        'type' => 1,
        'identity' => 2,
        'alias' => 'FGN',
        'name' => '上班族貸',
        'loan_range_s' => 10000,
        'loan_range_e' => 200000,
        'interest_rate_s' => 5,
        'interest_rate_e' => 20,
        'charge_platform' => 4,
        'charge_platform_min' => PLATFORM_FEES_MIN,
        'sub_product' => [5001, STAGE_CER_TARGET, 1],
        'certifications' => [
            CERTIFICATION_IDCARD,
            CERTIFICATION_DEBITCARD,
            CERTIFICATION_SOCIAL,
            CERTIFICATION_EMERGENCY,
            CERTIFICATION_EMAIL,
            CERTIFICATION_FINANCIAL,
            CERTIFICATION_DIPLOMA,
            CERTIFICATION_INVESTIGATION,
            CERTIFICATION_JOB
        ],
        'instalment' => [3, 6, 12, 18, 24],
        'repayment' => [1],
        'targetData' => [],
        'secondInstance' => false,
        'weight' => [],
        'status' => 1,
        'dealer' => [],
        'multi_target' => 0,
        'hiddenMainProduct' => false,
        'hiddenSubProduct' => false,
        'description' => '須提供工作證明<br>可申請額度<br>10,000-200,000',
        'checkOwner' => false,
    ],
    4 => [
        'id' => 4,
        'visul_id' => 'N2',
        'type' => 2,
        'identity' => 2,
        'alias' => 'FGI',
        'name' => '上班族手機貸',
        'loan_range_s' => 10000,
        'loan_range_e' => 200000,
        'interest_rate_s' => ORDER_INTEREST_RATE,
        'interest_rate_e' => ORDER_INTEREST_RATE,
        'charge_platform' => 4,
        'charge_platform_min' => PLATFORM_FEES_MIN,
        'sub_product' => [],
        'certifications' => [
            CERTIFICATION_IDCARD,
            CERTIFICATION_DEBITCARD,
            CERTIFICATION_SOCIAL,
            CERTIFICATION_EMERGENCY,
            CERTIFICATION_EMAIL,
            CERTIFICATION_FINANCIAL,
            CERTIFICATION_DIPLOMA,
            CERTIFICATION_INVESTIGATION,
            CERTIFICATION_JOB
        ],
        'instalment' => [3, 6, 12, 18, 24],
        'repayment' => [1],
        'targetData' => [],
        'secondInstance' => false,
        'weight' => [],
        'status' => 1,
        'dealer' => [],
        'multi_target' => 0,
        'hiddenMainProduct' => false,
        'hiddenSubProduct' => false,
        'description' => '須提供工作證明<br>可申請額度<br>10,000-200,000',
        'checkOwner' => false,
    ],
    //5 => [
    //    'id' => 5,
    //    'visul_id' => 'N3',
    //    'type' => 2,
    //    'identity' => 1,
    //    'alias' => 'SFV',
    //    'name' => '學生外匯車貸',
    //    'loan_range_s' => 10000,
    //    'loan_range_e' => 2000000,
    //    'interest_rate_s' => FEV_INTEREST_RATE,
    //    'interest_rate_e' => FEV_INTEREST_RATE,
    //    'charge_platform' => PLATFORM_FEES,
    //    'charge_platform_min' => 10000,
    //    'sub_product' => [],
    //    'certifications' => [
    //            CERTIFICATION_IDCARD,
    //            CERTIFICATION_STUDENT,
    //            CERTIFICATION_DEBITCARD,
    //            CERTIFICATION_SOCIAL,
    //            CERTIFICATION_EMERGENCY,
    //            CERTIFICATION_EMAIL,
    //            CERTIFICATION_FINANCIAL
    //        ],
    //    'instalment' => [180],
    //    'repayment' => [3],
    //    'targetData' => [],
    //    'secondInstance' => false,
    //    'weight' => [],
    //    'status' => 1,
    //    'dealer' => [],
    //    'multi_target' => 0,
    //    'hiddenMainProduct' => false,
//        'hiddenSubProduct' => false,
    //    'description' => ''
//    'checkOwner' => false,
    //],
    //6 => [
    //    'id' => 6,
    //    'visul_id' => 'N3',
    //    'type' => 2,
    //    'identity' => 2,
    //    'alias' => 'FFV',
    //    'name' => '上班族外匯車貸',
    //    'loan_range_s' => 10000,
    //    'loan_range_e' => 2000000,
    //    'interest_rate_s' => FEV_INTEREST_RATE,
    //    'interest_rate_e' => FEV_INTEREST_RATE,
    //    'charge_platform' => 4,
    //    'charge_platform_min' => 10000,
    //    'sub_product' => [],
    //    'certifications' => [
    //            CERTIFICATION_IDCARD,
    //            CERTIFICATION_DEBITCARD,
    //            CERTIFICATION_SOCIAL,
    //            CERTIFICATION_EMERGENCY,
    //            CERTIFICATION_EMAIL,
    //            CERTIFICATION_FINANCIAL,
    //            CERTIFICATION_DIPLOMA,
    //            CERTIFICATION_INVESTIGATION,
    //            CERTIFICATION_JOB
    //        ],
    //    'instalment' => [180],
    //    'repayment' => [3],
    //    'targetData' => [],
    //    'secondInstance' => false,
    //    'weight' => [],
    //    'status' => 1,
    //    'dealer' => [],
    //    'multi_target' => 0,
    //    'hiddenMainProduct' => false,
//        'hiddenSubProduct' => false,
    //    'description' => ''
//    'checkOwner' => false,
    //],
//    7 => [
//        'id' => 7,
//        'visul_id' => 'N3',
//        'type' => 1,
//        'identity' => 1,
//        'alias' => 'SUL',
//        'name' => '新創企業貸',
//        'loan_range_s' => 200000,
//        'loan_range_e' => 1500000,
//        'interest_rate_s' => SUL_INTEREST_STARTING_RATE,
//        'interest_rate_e' => SUL_INTEREST_ENDING_RATE,
//        'charge_platform' => PLATFORM_FEES,
//        'charge_platform_min' => 500,
//        'sub_product' => [4],
//        'certifications' => [
//            CERTIFICATION_IDCARD,
//            CERTIFICATION_STUDENT,
//            CERTIFICATION_DEBITCARD,
//            CERTIFICATION_SOCIAL,
//            CERTIFICATION_EMERGENCY,
//            CERTIFICATION_EMAIL,
//            CERTIFICATION_FINANCIAL
//        ],
//        'instalment' => [3, 6, 12, 18, 24],
//        'repayment' => [1],
//        'targetData' => [],
//        'secondInstance' => false,
//        'weight' => [],
//        'status' => 1,
//        'dealer' => [],
//        'multi_target' => 0,
//        'hiddenMainProduct' => true,
//        'hiddenSubProduct' => false,
//        'description' => '',
//        'checkOwner' => true,
//    ],
//    8 => [
//        'id' => 8,
//        'visul_id' => 'N3',
//        'type' => 1,
//        'identity' => 2,
//        'alias' => 'SUL',
//        'name' => '新創企業貸',
//        'loan_range_s' => 200000,
//        'loan_range_e' => 1500000,
//        'interest_rate_s' => SUL_INTEREST_STARTING_RATE,
//        'interest_rate_e' => SUL_INTEREST_ENDING_RATE,
//        'charge_platform' => 4,
//        'charge_platform_min' => 500,
//        'sub_product' => [4],
//        'certifications' => [
//            CERTIFICATION_IDCARD,
//            CERTIFICATION_DEBITCARD,
//            CERTIFICATION_SOCIAL,
//            CERTIFICATION_EMERGENCY,
//            CERTIFICATION_EMAIL,
//            CERTIFICATION_FINANCIAL,
//            CERTIFICATION_DIPLOMA,
//            CERTIFICATION_INVESTIGATION,
//            CERTIFICATION_JOB
//        ],
//        'instalment' => [3, 6, 12, 18, 24],
//        'repayment' => [1],
//        'targetData' => [],
//        'secondInstance' => false,
//    //    'weight' => [],
//        'status' => 1,
//        'dealer' => [],
//        'multi_target' => 0,
//        'hiddenMainProduct' => true,
//        'hiddenSubProduct' => false,
//        'description' => '',
//        'checkOwner' => true,
//    ],
//    9 => [
//        'id' => 9,
//        'visul_id' => 'N3',
//        'type' => 1,
//        'identity' => 1,
//        'alias' => 'CCL',
//        'name' => '新創企業貸',
//        'loan_range_s' => 500000,
//        'loan_range_e' => 1500000,
//        'interest_rate_s' => SUL_INTEREST_STARTING_RATE,
//        'interest_rate_e' => SUL_INTEREST_ENDING_RATE,
//        'charge_platform' => PLATFORM_FEES,
//        'charge_platform_min' => 500,
//        'sub_product' => [5],
//        'certifications' => [
//            CERTIFICATION_IDCARD,
//            CERTIFICATION_STUDENT,
//            CERTIFICATION_DEBITCARD,
//            CERTIFICATION_SOCIAL,
//            CERTIFICATION_EMERGENCY,
//            CERTIFICATION_EMAIL,
//            CERTIFICATION_FINANCIAL
//        ],
//        'instalment' => [3, 6, 12, 18, 24],
//        'repayment' => [1],
//        'targetData' => [],
//        'secondInstance' => false,
//        'weight' => [],
//        'status' => 1,
//        'dealer' => [],
//        'multi_target' => 0,
//        'hiddenMainProduct' => true,
//        'hiddenSubProduct' => false,
//        'description' => '',
//        'checkOwner' => true,
//    ],
//    10 => [
//        'id' => 10,
//        'visul_id' => 'N3',
//        'type' => 1,
//        'identity' => 2,
//        'alias' => 'CCL',
//        'name' => '新創企業貸',
//        'loan_range_s' => 500000,
//        'loan_range_e' => 1500000,
//        'interest_rate_s' => SUL_INTEREST_STARTING_RATE,
//        'interest_rate_e' => SUL_INTEREST_ENDING_RATE,
//        'charge_platform' => 4,
//        'charge_platform_min' => 500,
//        'sub_product' => [5],
//        'certifications' => [
//            CERTIFICATION_IDCARD,
//            CERTIFICATION_DEBITCARD,
//            CERTIFICATION_SOCIAL,
//            CERTIFICATION_EMERGENCY,
//            CERTIFICATION_EMAIL,
//            CERTIFICATION_FINANCIAL,
//            CERTIFICATION_DIPLOMA,
//            CERTIFICATION_INVESTIGATION,
//            CERTIFICATION_JOB
//        ],
//        'instalment' => [3, 6, 12, 18, 24],
//        'repayment' => [1],
//        'targetData' => [],
//        'weight' => [],
//        'status' => 1,
//        'dealer' => [],
//        'multi_target' => 0,
//        'hiddenMainProduct' => true,
//        'hiddenSubProduct' => false,
//        'description' => '',
//        'checkOwner' => true,
//    ],
//    11 => [
//        'id' => 11,
//        'visul_id' => 'J2',
//        'type' => 1,
//        'identity' => 2,
//        'alias' => 'SSM',
//        'name' => '百萬信保微企貸',
//        'loan_range_s' => 500000,
//        'loan_range_e' => 1000000,
//        'interest_rate_s' => SUL_INTEREST_STARTING_RATE,
//        'interest_rate_e' => SUL_INTEREST_ENDING_RATE,
//        'charge_platform' => 4,
//        'charge_platform_min' => 500,
//        'sub_product' => [],
//        'certifications' => [
//            CERTIFICATION_IDCARD,
//            CERTIFICATION_DEBITCARD,
//            CERTIFICATION_SOCIAL,
//            CERTIFICATION_EMERGENCY,
//            CERTIFICATION_EMAIL,
//            CERTIFICATION_FINANCIAL,
//            CERTIFICATION_DIPLOMA,
//            CERTIFICATION_INVESTIGATION,
//            CERTIFICATION_JOB
//        ],
//        'instalment' => [36],
//        'repayment' => [1],
//        'targetData' => [],
//        'weight' => [],
//        'status' => 1,
//        'dealer' => [],
//        'multi_target' => 0,
//        'hiddenMainProduct' => true,
//        'hiddenSubProduct' => false,
//        'description' => '',
//        'checkOwner' => true,
//    ],
    1000 => [
        'id' => 1000,
        'visul_id' => 'D1',
        'type' => 1,
        'identity' => 3,
        'alias' => 'FEV',
        'name' => '外匯車貸',
        'loan_range_s' => 10000,
        'loan_range_e' => 2000000,
        'interest_rate_s' => FEV_INTEREST_RATE,
        'interest_rate_e' => FEV_INTEREST_RATE,
        'charge_platform' => PLATFORM_FEES,
        'charge_platform_min' => 10000,
        'sub_product' => [3],//2,
        'certifications' => [
            CERTIFICATION_IDCARD,
            CERTIFICATION_DEBITCARD,
            CERTIFICATION_SOCIAL,
            CERTIFICATION_EMERGENCY,
            CERTIFICATION_EMAIL,
            CERTIFICATION_FINANCIAL,
            CERTIFICATION_DIPLOMA,
            CERTIFICATION_INVESTIGATION,
            CERTIFICATION_JOB
        ],
        'instalment' => [180],
        'repayment' => [3],
        'targetData' => [],
        'secondInstance' => false,
        'weight' => [],
        'status' => 1,
        'dealer' => [2],
        'multi_target' => 1,
        'hiddenMainProduct' => true,
        'hiddenSubProduct' => false,
        'description' => '',
        'checkOwner' => false,
    ],
//    1001 => [
//        'id' => 1001,
//        'visul_id' => 'J1',
//        'type' => 1,
//        'identity' => 3,
//        'alias' => 'CCL',
//        'name' => '新創企業貸',
//        'loan_range_s' => 500000,
//        'loan_range_e' => 1500000,
//        'interest_rate_s' => SUL_INTEREST_STARTING_RATE,
//        'interest_rate_e' => SUL_INTEREST_ENDING_RATE,
//        'charge_platform' => PLATFORM_FEES,
//        'charge_platform_min' => 500,
//        'sub_product' => [],
//        'certifications' => [
//            CERTIFICATION_BUSINESSTAX,
//            CERTIFICATION_BALANCESHEET,
//            CERTIFICATION_INCOMESTATEMENT,
//            CERTIFICATION_INVESTIGATIONJUDICIAL,
//            CERTIFICATION_PASSBOOKCASHFLOW,
//            CERTIFICATION_GOVERNMENTAUTHORITIES,
//            CERTIFICATION_CHARTER,
//            CERTIFICATION_REGISTEROFMEMBERS,
//            CERTIFICATION_MAINPRODUCTSTATUS,
//            CERTIFICATION_STARTUPFUNDS,
//            CERTIFICATION_BUSINESS_PLAN,
//            CERTIFICATION_VERIFICATION,
//            CERTIFICATION_CONDENSEDBALANCESHEET,
//            CERTIFICATION_CONDENSEDINCOMESTATEMENT,
//            CERTIFICATION_PURCHASESALESVENDORLIST,
//            CERTIFICATION_EMPLOYEEINSURANCELIST
//        ],
//        'instalment' => [3, 6, 12, 18, 24],
//        'repayment' => [1],
//        'targetData' => [],
//        'weight' => [],
//        'status' => 1,
//        'dealer' => 0,
//        'multi_target' => 1,
//        'hiddenMainProduct' => false,
//        'hiddenSubProduct' => false,
//        'description' => '',
//        'checkOwner' => true,
//    ],
    1002 => [
        'id' => 1002,
        'visul_id' => 'J2',
        'type' => 1,
        'identity' => 3,
        'alias' => 'SSM',
        'name' => '百萬信保微企貸',
        'loan_range_s' => 500000,
        'loan_range_e' => 6000000,
        'interest_rate_s' => 5,
        'interest_rate_e' => 20,
        'charge_platform' => PLATFORM_FEES,
        'charge_platform_min' => 500,
        'sub_product' => [5002],
        'certifications' => [
            CERTIFICATION_IDCARD,
            CERTIFICATION_EMAIL,
            CERTIFICATION_PROFILE,
            CERTIFICATION_SIMPLIFICATIONJOB,
            // CERTIFICATION_INVESTIGATION,
            CERTIFICATION_SIMPLIFICATIONFINANCIAL,
            CERTIFICATION_GOVERNMENTAUTHORITIES,
            CERTIFICATION_JUDICIALGUARANTEE,
            CERTIFICATION_PROFILEJUDICIAL,
            CERTIFICATION_PASSBOOKCASHFLOW,
            CERTIFICATION_EMPLOYEEINSURANCELIST,
            CERTIFICATION_INCOMESTATEMENT,
            CERTIFICATION_INVESTIGATIONJUDICIAL,
			CERTIFICATION_INVESTIGATIONA11,
            CERTIFICATION_COMPANYEMAIL,
        ],
        'instalment' => [36],
        'repayment' => [1],
        'targetData' => [],
        'secondInstance' => false,
        'weight' => [],
        'status' => 1,
        'dealer' => [],
        'multi_target' => 0,
        'hiddenMainProduct' => false,
        'hiddenSubProduct' => true,
        'description' => '',
        'checkOwner' => true,
    ],
];

$config['visul_id_des'] = [
    'N1' => [
        'name' => '個人信貸',
        'description' => '<span style=\'font-size:16px;color:white;font-weight: 900;\'>全線上申請，無人打擾</span><br><span style=\'font-size:14px;color:white\'>最高額度12-20萬元<br>3-24期，償還期限選擇多元<br>最低利率5%</span>',
        'icon' => FRONT_CDN_URL . 'app_asset/marketing_res/marketing_app_loan.jpg',
        'banner' => FRONT_CDN_URL . 'app_asset/image_loan_03.jpg',
        'url' => '',
        'status' => 1
    ],
    'N2' => [
        'name' => '手機無卡分期專案',
        'description' => '最新熱門手機選擇最多元',
        'icon' => FRONT_CDN_URL . 'app_asset/marketing_res/marketing_app_phoneloan.jpg',
        'banner' => FRONT_CDN_URL . 'app_asset/image_loan_03.jpg',
        'url' => '',
        'status' => 1
    ],
    'N3' => [
        'name' => '新創團隊',
        'description' => '好的idea缺乏啟動資金',
        'icon' => FRONT_CDN_URL . 'app_asset/image_loan_03.jpg',
        'banner' => FRONT_CDN_URL . 'app_asset/image_loan_03.jpg',
        'url' => '',
        'status' => 1
    ],
    'N4' => [
        'name' => '百萬信保微企貸',
        'description' => '企業融資 專案啟動',
        'icon' => FRONT_CDN_URL . 'app_asset/image_loan_03.jpg',
        'banner' => FRONT_CDN_URL . 'app_asset/image_loan_03.jpg',
        'url' => '',
        'status' => 1
    ],
    'D1' => [
        'name' => '外匯車專案',
        'description' => '幫您貸車進來',
        'icon' => FRONT_CDN_URL . 'app_asset/foreign_vehicle/image_banner_.jpg',
        'banner' => FRONT_CDN_URL . 'app_asset/foreign_vehicle/image_banner_.jpg',
        'url' => '',
        'status' => 1
    ],
    'J1' => [
        'name' => '微型企業',
        'description' => '企業融資 專案啟動',
        'icon' => FRONT_CDN_URL . 'app_asset/image_loan_03.jpg',
        'banner' => FRONT_CDN_URL . 'app_asset/image_loan_03.jpg',
        'url' => '',
        'status' => 1
    ],
    'J2' => [
        'name' => '百萬信保微企貸',
        'description' => '企業融資 專案啟動',
        'icon' => FRONT_CDN_URL . 'app_asset/image_loan_03.jpg',
        'banner' => FRONT_CDN_URL . 'app_asset/image_loan_03.jpg',
        'url' => '',
        'status' => 1
    ],
    'LJ2' => [
        'name' => '百萬信保微企貸',
        'description' => '企業融資 專案啟動',
        'icon' => FRONT_CDN_URL . 'app_asset/image_loan_03.jpg',
        'banner' => FRONT_CDN_URL . 'app_asset/image_loan_03.jpg',
        'url' => '',
        'status' => 1
    ],
    'TOLJ2' => [
        'name' => '百萬信保微企貸',
        'description' => '企業融資 專案啟動',
        'icon' => FRONT_CDN_URL . 'app_asset/image_loan_03.jpg',
        'banner' => FRONT_CDN_URL . 'app_asset/image_loan_03.jpg',
        'url' => '',
        'status' => 1
    ],
    'LS1' => [
        'name' => '學生貸',
        'description' => '<span style=\'font-size:16px;color:black;font-weight: 900;\'>資金小幫手生活超easy</span><br><span style=\'font-size:14px;color:#4a4a4a\'>全台大學生與碩博士均可申請</span>',
        'icon' => FRONT_CDN_URL . 'app_asset/marketing_res/marketing_app_product_student.jpg',
        'banner' => FRONT_CDN_URL . 'app_asset/marketing_res/marketing_app_product_student.jpg',
        'url' => '',
        'status' => 1
    ],
    'TOLS1' => [
        'name' => '<span style=\'font-size:18px;color:#1f232c\'>學生貸</span>',
        'description' => '<span style=\'font-size:14px;color:#4a4a4a\'>不論是夢想實現，還是生活急需，<br/>我們集結了各大學校友、老師，<br/>專門投資借貸同學在學期間的資金需求。</span>',
        'icon' => FRONT_CDN_URL . 'app_asset/marketing_res/marketing_app_product_student.jpg',
        'banner' => FRONT_CDN_URL . 'app_asset/marketing_res/marketing_app_product_student.jpg',
        'url' => '',
        'status' => 1
    ],
    'LF1' => [
        'name' => '上班族貸',
        'description' => '<span style=\'font-size:16px;color:black;font-weight: 900;\'>快速滿足您的資金需求</span><br><span style=\'font-size:14px;color:#4a4a4a\'>年滿20歲的工作人士均可申請</span>',
        'icon' => FRONT_CDN_URL . 'app_asset/marketing_res/marketing_app_product_worker.jpg',
        'banner' => FRONT_CDN_URL . 'app_asset/marketing_res/marketing_app_product_worker.jpg',
        'url' => '',
        'status' => 1
    ],
    'TOLF1' => [
        'name' => '<span style=\'font-size:18px;color:white\'>上班族貸</span>',
        'description' => '<span style=\'font-size:14px;color:white\'>進入社會工作了，臨時有急缺？<br/>沒有煩人的「專員」打擾，<br/>只有AI 24小時online滿足您的資金需求！</span>',
        'icon' => FRONT_CDN_URL . 'app_asset/marketing_res/marketing_app_product_worker.jpg',
        'banner' => FRONT_CDN_URL . 'app_asset/marketing_res/marketing_app_product_worker.jpg',
        'url' => '',
        'status' => 1
    ],
    'NS1' => [
        'name' => '資訊工程師專案',
        'description' => '<span style=\'font-size:16px;color:black;font-weight: 900;\'>為您的金錢問題debug</span><br><span style=\'font-size:14px;color:#4a4a4a\'>資訊相關學生或職場專業人員均可申請</span>',
        'icon' => FRONT_CDN_URL . 'app_asset/marketing_res/marketing_app_product_programer.jpg',
        'banner' => FRONT_CDN_URL . 'app_asset/marketing_res/marketing_app_product_programer.jpg',
        'url' => '',
        'status' => 1
    ],
    'TONS1' => [
        'name' => '<span style=\'font-size:18px;color:white\'>工程師貸</span>',
        'description' => '<span style=\'font-size:14px;color:white\'>不論是學生/上班族，<br/>只要是資訊/資工/資管相關科系，<br/>我們特別提供給您優惠利率，<br/>隨時隨地，只要打開APP，資金到手。</span>',
        'icon' => FRONT_CDN_URL . 'app_asset/marketing_res/marketing_app_product_programer.jpg',
        'banner' => FRONT_CDN_URL . 'app_asset/marketing_res/marketing_app_product_programer.jpg',
        'url' => '',
        'status' => 1
    ],
    'NS2' => [
        'name' => '新創團隊',
        'description' => '<span style=\'font-size:16px;color:black;font-weight: 900;\'>好的idea缺乏啟動資金</span><br><span style=\'font-size:14px;color:#4a4a4a\'>學生或職場專業人員均可申請</span>',
        'icon' => FRONT_CDN_URL . 'app_asset/image_loan_03.jpg',
        'banner' => FRONT_CDN_URL . 'app_asset/image_loan_03.jpg',
        'url' => '',
        'status' => 1
    ],
    'NS3' => [
        'name' => '微型企業',
        'description' => '<span style=\'font-size:16px;color:black;font-weight: 900;\'>企業融資 專案啟動</span><br><span style=\'font-size:14px;color:#4a4a4a\'>學生或職場專業人員均可申請</span>',
        'icon' => FRONT_CDN_URL . 'app_asset/image_loan_03.jpg',
        'banner' => FRONT_CDN_URL . 'app_asset/image_loan_03.jpg',
        'url' => '',
        'status' => 1
    ],
    'NS4' => [
        'name' => '百萬微型企業',
        'description' => '<span style=\'font-size:16px;color:black;font-weight: 900;\'>企業融資 專案啟動</span><br><span style=\'font-size:14px;color:#4a4a4a\'>企業可申請</span>',
        'icon' => FRONT_CDN_URL . 'app_asset/image_loan_03.jpg',
        'banner' => FRONT_CDN_URL . 'app_asset/image_loan_03.jpg',
        'url' => '',
        'status' => 1
    ],
    'DS1' => [
        'name' => '代購代付融資',
        'description' => '<span style=\'font-size:16px;color:black;font-weight: 900;\'>幫您貸車進來</span><br><span style=\'font-size:14px;color:#4a4a4a\'>外匯車商可申請</span>',
        'icon' => FRONT_CDN_URL . 'app_asset/foreign_vehicle/image_sub_1.jpg',
        'banner' => FRONT_CDN_URL . 'app_asset/foreign_vehicle/image_sub_1.jpg',
        'url' => '',
        'status' => 1
    ],
    'DS2' => [
        'name' => '車輛融資專案',
        'description' => '<span style=\'font-size:14px;color:#4a4a4a\'>提供便利資金融通</span>',
        'icon' => FRONT_CDN_URL . 'app_asset/foreign_vehicle/image_sub_2.jpg',
        'banner' => FRONT_CDN_URL . 'app_asset/foreign_vehicle/image_sub_2.jpg',
        'url' => '',
        'status' => 1
    ],
    'TODS2' => [
        'name' => '<span style=\'font-size:18px;color:white\'>外匯車貸</span>',
        'description' => '<span style=\'font-size:14px;color:white\'>車輛融資專案<br/>提供便利資金融通</span>',
        'icon' => FRONT_CDN_URL . 'app_asset/foreign_vehicle/image_sub_2.jpg',
        'banner' => FRONT_CDN_URL . 'app_asset/foreign_vehicle/image_sub_2.jpg',
        'url' => '',
        'status' => 1
    ],
    'NS1P1' => [
        'icon' => FRONT_CDN_URL . 'app_asset/image_loan_03.jpg',
        'banner' => FRONT_CDN_URL . 'app_asset/image_loan_03.jpg',
    ],
    'NS1P2' => [
        'icon' => FRONT_CDN_URL . 'app_asset/image_loan_03.jpg',
        'banner' => FRONT_CDN_URL . 'app_asset/image_loan_03.jpg',
    ],
    'LS1P1' => [
        'icon' => FRONT_CDN_URL . 'app_asset/image_loan_03.jpg',
        'banner' => FRONT_CDN_URL . 'app_asset/image_loan_03.jpg',
    ],
];
$config['sub_product_mapping'] = [
    1 => 'config_techi',
];

$config['map_sub_product'] = [
    '1:1' => '[1]->'
];
$config['sub_product_list'] = [
    1 => [
        'visul_id' => 'NS1',
        'identity' => [
            1 => [
                'visul_id' => 'NS1P1',
                'name' => '學生工程師貸',
                'product_id' => '1:1',
                'loan_range_s' => 5000,
                'loan_range_e' => 120000,
                'interest_rate_s' => 5,
                'interest_rate_e' => 20,
                'charge_platform' => PLATFORM_FEES,
                'charge_platform_min' => PLATFORM_FEES_MIN,
                'certifications' => [
                    CERTIFICATION_IDCARD,
                    CERTIFICATION_STUDENT,
                    CERTIFICATION_DEBITCARD,
                    CERTIFICATION_SOCIAL,
                    CERTIFICATION_EMERGENCY,
                    CERTIFICATION_EMAIL,
                    CERTIFICATION_FINANCIAL
                ],
                'instalment' => [3, 6, 12, 18, 24],
                'repayment' => [1],
                'targetData' => [],
                'secondInstance' => false,
                'weight' => [],
                'status' => 1,
                'dealer' => [],
                'multi_target' => 0,
                'description' => '須提供有效學生證<br>可申請額度<br>5,000-120,000',
                'checkOwner' => false,
            ],
            2 => [
                'visul_id' => 'NS1P2',
                'name' => '上班族工程師貸',
                'product_id' => '3:1',
                'loan_range_s' => 10000,
                'loan_range_e' => 200000,
                'interest_rate_s' => 5,
                'interest_rate_e' => 20,
                'charge_platform' => 4,
                'charge_platform_min' => PLATFORM_FEES_MIN,
                'certifications' => [
                    CERTIFICATION_IDCARD,
                    CERTIFICATION_DEBITCARD,
                    CERTIFICATION_SOCIAL,
                    CERTIFICATION_EMERGENCY,
                    CERTIFICATION_EMAIL,
                    CERTIFICATION_FINANCIAL,
                    CERTIFICATION_DIPLOMA,
                    CERTIFICATION_INVESTIGATION,
                    CERTIFICATION_JOB
                ],
                'instalment' => [3, 6, 12, 18, 24],
                'repayment' => [1],
                'targetData' => [],
                'secondInstance' => false,
                'weight' => [],
                'status' => 1,
                'dealer' => [],
                'multi_target' => 0,
                'description' => '須提供工作證明<br>可申請額度<br>10,000-200,000',
                'checkOwner' => false,
            ]
        ],
        'status' => 1
    ],
    2 => [
        'visul_id' => 'DS1',
        'identity' => [
            3 => [
                'visul_id' => 'DS1P1',
                'name' => '代購代付融資',
                'product_id' => '1000:2',
                'loan_range_s' => 10000,
                'loan_range_e' => 2000000,
                'interest_rate_s' => FEV_INTEREST_RATE,
                'interest_rate_e' => FEV_INTEREST_RATE,
                'charge_platform' => PLATFORM_FEES,
                'charge_platform_min' => 10000,
                'certifications' => [1000, 1001, 1002, 1003, 1004, 1006, 1007, 2000],
                'instalment' => [180],
                'repayment' => [3],
                'targetData' => [
                    'vin' => ['String', '車身號碼'],
                    'factory_time' => ['Timestamp', '出廠時間'],
                    'product_description' => ['String', '產品備註)'],
                    'car_history_image' => ['Picture', '車輛歷史報告 ( Carfax / Autocheck )', '6'],
                ],
                'secondInstance' => false,
                'weight' => [],
                'status' => 1,
                'dealer' => [2],
                'multi_target' => 1,
                'description' => '可申請額度<br>10,000-2,000,000',
                'checkOwner' => false,
            ]
        ],
        'status' => 1
    ],
    3 => [
        'visul_id' => 'DS2',
        'identity' => [
            3 => [
                'visul_id' => 'DS2P1',
                'name' => '在庫車融資',
                'product_id' => '1000:3',
                'loan_range_s' => 10000,
                'loan_range_e' => 2000000,
                'interest_rate_s' => FEV_INTEREST_RATE,
                'interest_rate_e' => FEV_INTEREST_RATE,
                'charge_platform' => PLATFORM_FEES,
                'charge_platform_min' => 10000,
                'certifications' => [
                    CERTIFICATION_BUSINESSTAX,
                    CERTIFICATION_BALANCESHEET,
                    CERTIFICATION_INCOMESTATEMENT,
                    CERTIFICATION_INVESTIGATIONJUDICIAL,
                    CERTIFICATION_CERCREDITJUDICIAL,
                    CERTIFICATION_GOVERNMENTAUTHORITIES,
                    CERTIFICATION_SALESDETAIL,
                ],
                'instalment' => [90],
                'repayment' => [3],
                'targetData' => [
                    'purchase_time' => ['Timestamp', '購車時間'],
                    'vin' => ['String', '車身號碼'],
                    'factory_time' => ['Timestamp', '出廠時間'],
                    'product_description' => ['String', '產品備註'],
                    'car_history_image' => ['Picture', '車輛歷史報告 ( Carfax / Autocheck )', '6'],
                    'car_title_image' => ['Picture', '車輛所有權狀 ( title )', '6'],
                    'car_import_proof_image' => ['Picture', '海關進口證明/進口報單', '6'],
                    'car_artc_image' => ['Picture', '交通部核發合格證明、環保驗車證明(ARTC)', '6'],
                    'car_others_image' => ['Picture', '協力廠商鑑定報告', '6', true],//index3 true = option
                    'car_photo_front_image' => ['Picture', '車輛外觀照片-前側', '6'],
                    'car_photo_mileage_image' => ['Picture', '車輛外觀照片-里程', '6'],
                    'car_photo_date_image' => ['Picture', '車輛外觀照片-出廠日期', '6'],
                    'car_photo_all_image' => ['Picture', '車輛外觀照片-全車', '6'],
                    'car_photo_back_image' => ['Picture', '車輛外觀照片-後側', '6'],
                ],
                'secondInstance' => false,
                'weight' => ['car_others_image'],
                'status' => 1,
                'dealer' => [2],
                'multi_target' => 1,
                'description' => '可申請額度<br>10,000-2,000,000',
                'checkOwner' => false,
            ]
        ],
        'status' => 1
    ],
    4 => [
        'visul_id' => 'NS2',
        'identity' => [
            1 => [
                'visul_id' => 'NS2P1',
                'name' => '新創團隊',
                'product_id' => '7:4',
                'loan_range_s' => 200000,
                'loan_range_e' => 1500000,
                'interest_rate_s' => SUL_INTEREST_STARTING_RATE,
                'interest_rate_e' => SUL_INTEREST_ENDING_RATE,
                'charge_platform' => PLATFORM_FEES,
                'charge_platform_min' => 500,
                'certifications' => [
                    CERTIFICATION_IDCARD,
                    CERTIFICATION_STUDENT,
                    CERTIFICATION_DEBITCARD,
                    CERTIFICATION_SOCIAL,
                    CERTIFICATION_EMERGENCY,
                    CERTIFICATION_EMAIL,
                    CERTIFICATION_FINANCIAL
                ],
                'instalment' => [3, 6, 12, 18, 24],
                'repayment' => [1],
                'targetData' => [
                    'business_plan' => ['Picture', '商業企劃書', '6'],
                    'verification' => ['Picture', '驗資基金證明', '6', true],
                ],
                'secondInstance' => false,
                'weight' => [],
                'status' => 1,
                'dealer' => [],
                'multi_target' => 1,
                'description' => '可申請額度<br>200,000-1,500,000',
                'checkOwner' => true,
            ],
            2 => [
                'visul_id' => 'NS2P2',
                'name' => '新創團隊',
                'product_id' => '8:4',
                'loan_range_s' => 200000,
                'loan_range_e' => 1500000,
                'interest_rate_s' => SUL_INTEREST_STARTING_RATE,
                'interest_rate_e' => SUL_INTEREST_ENDING_RATE,
                'charge_platform' => 4,
                'charge_platform_min' => 500,
                'certifications' => [1, 3, 4, 5, 6, 7, 8, 9, 10],
                'instalment' => [3, 6, 12, 18, 24],
                'repayment' => [1],
                'targetData' => [
                    'business_plan' => ['Picture', '商業企劃書', '6'],
                    'verification' => ['Picture', '驗資基金證明', '6', true],
                ],
                'secondInstance' => false,
                'weight' => [],
                'status' => 1,
                'dealer' => [],
                'multi_target' => 0,
                'description' => '可申請額度<br>10,000-1,500,000',
                'checkOwner' => true,
            ],
        ],
        'status' => 1
    ],
    5 => [
        'visul_id' => 'NS3',
        'identity' => [
            1 => [
                'visul_id' => 'NS3P1',
                'name' => '微型企業',
                'product_id' => '9:5',
                'loan_range_s' => 500000,
                'loan_range_e' => 1500000,
                'interest_rate_s' => SUL_INTEREST_STARTING_RATE,
                'interest_rate_e' => SUL_INTEREST_ENDING_RATE,
                'charge_platform' => PLATFORM_FEES,
                'charge_platform_min' => PLATFORM_FEES_MIN,
                'certifications' => [
                    CERTIFICATION_IDCARD,
                    CERTIFICATION_STUDENT,
                    CERTIFICATION_DEBITCARD,
                    CERTIFICATION_SOCIAL,
                    CERTIFICATION_EMERGENCY,
                    CERTIFICATION_EMAIL,
                    CERTIFICATION_FINANCIAL
                ],
                'instalment' => [3, 6, 12, 18, 24],
                'repayment' => [1],
                'targetData' => [
                    'business_plan' => ['Picture', '商業企劃書', '6'],
                ],
                'secondInstance' => false,
                'weight' => [],
                'status' => 1,
                'dealer' => [],
                'multi_target' => 0,
                'description' => '可申請額度<br>5,000-1,500,000',
                'checkOwner' => true,
            ],
            2 => [
                'visul_id' => 'NS3P2',
                'name' => '微型企業',
                'product_id' => '10:5',
                'loan_range_s' => 500000,
                'loan_range_e' => 1500000,
                'interest_rate_s' => SUL_INTEREST_STARTING_RATE,
                'interest_rate_e' => SUL_INTEREST_ENDING_RATE,
                'charge_platform' => 4,
                'charge_platform_min' => PLATFORM_FEES_MIN,
                'certifications' => [
                    CERTIFICATION_IDCARD,
                    CERTIFICATION_DEBITCARD,
                    CERTIFICATION_SOCIAL,
                    CERTIFICATION_EMERGENCY,
                    CERTIFICATION_EMAIL,
                    CERTIFICATION_FINANCIAL,
                    CERTIFICATION_DIPLOMA,
                    // CERTIFICATION_INVESTIGATION,
                    CERTIFICATION_JOB,
					CERTIFICATION_INVESTIGATIONA11,
                ],
                'instalment' => [3, 6, 12, 18, 24],
                'repayment' => [1],
                'targetData' => [
                    'business_plan' => ['Picture', '商業企劃書', '6'],
                ],
                'weight' => [],
                'status' => 1,
                'dealer' => [],
                'multi_target' => 0,
                'description' => '可申請額度<br>10,000-1,500,000',
                'checkOwner' => true,
            ],
        ],
        'status' => 1
    ],
    5000 => [
        'visul_id' => 'LS1',
        'identity' => [
            1 => [
                'visul_id' => 'LS1P1',
                'name' => '學生貸',
                'product_id' => '1:0',
                'loan_range_s' => 5000,
                'loan_range_e' => 120000,
                'interest_rate_s' => 5,
                'interest_rate_e' => 20,
                'charge_platform' => PLATFORM_FEES,
                'charge_platform_min' => PLATFORM_FEES_MIN,
                'certifications' => [
                    CERTIFICATION_IDCARD,
                    CERTIFICATION_STUDENT,
                    CERTIFICATION_DEBITCARD,
                    CERTIFICATION_SOCIAL,
                    CERTIFICATION_EMERGENCY,
                    CERTIFICATION_EMAIL,
                    CERTIFICATION_FINANCIAL
                ],
                'instalment' => [3, 6, 12, 18, 24],
                'repayment' => [1],
                'targetData' => [],
                'weight' => [],
                'status' => 1,
                'dealer' => [],
                'multi_target' => 0,
                'description' => '須提供有效學生證<br>可申請額度<br>5,000-120,000',
                'checkOwner' => false,
            ]
        ],
        'status' => 1
    ],
    5001 => [
        'visul_id' => 'LF1',
        'identity' => [
            2 => [
                'visul_id' => 'LF1P1',
                'name' => '上班族貸',
                'product_id' => '3:0',
                'loan_range_s' => 10000,
                'loan_range_e' => 200000,
                'interest_rate_s' => 5,
                'interest_rate_e' => 20,
                'charge_platform' => 4,
                'charge_platform_min' => PLATFORM_FEES_MIN,
                'certifications' => [
                    CERTIFICATION_IDCARD,
                    CERTIFICATION_DEBITCARD,
                    CERTIFICATION_SOCIAL,
                    CERTIFICATION_EMERGENCY,
                    CERTIFICATION_EMAIL,
                    CERTIFICATION_FINANCIAL,
                    CERTIFICATION_DIPLOMA,
                    CERTIFICATION_INVESTIGATION,
                    CERTIFICATION_JOB
                ],
                'instalment' => [3, 6, 12, 18, 24],
                'repayment' => [1],
                'targetData' => [],
                'weight' => [],
                'status' => 1,
                'dealer' => [],
                'multi_target' => 0,
                'description' => '須提供工作證明<br>可申請額度<br>10,000-200,000',
                'checkOwner' => false,
            ]
        ],
        'status' => 1
    ],
    5002 => [
        'visul_id' => 'LJ2',
        'identity' => [
            3 => [
                'visul_id' => 'LJ2P1',
                'name' => '百萬信保微企貸',
                'product_id' => '1002:0',
                'loan_range_s' => 500000,
                'loan_range_e' => 6000000,
                'interest_rate_s' => 5,
                'interest_rate_e' => 20,
                'charge_platform' => PLATFORM_FEES,
                'charge_platform_min' => 500,
                'certifications' => [
                    CERTIFICATION_IDCARD,
                    CERTIFICATION_DEBITCARD,
                    CERTIFICATION_EMAIL,
                    // CERTIFICATION_INVESTIGATION,
                    CERTIFICATION_PROFILE,
                    CERTIFICATION_SIMPLIFICATIONJOB,
                    CERTIFICATION_SIMPLIFICATIONFINANCIAL,
                    CERTIFICATION_INCOMESTATEMENT,
                    CERTIFICATION_INVESTIGATIONJUDICIAL,
					CERTIFICATION_INVESTIGATIONA11,
                    CERTIFICATION_PASSBOOKCASHFLOW,
                    CERTIFICATION_GOVERNMENTAUTHORITIES,
                    CERTIFICATION_EMPLOYEEINSURANCELIST,
                    CERTIFICATION_PROFILEJUDICIAL,
                    CERTIFICATION_COMPANYEMAIL,
                    CERTIFICATION_JUDICIALGUARANTEE,
                ],
                'instalment' => [36],
                'repayment' => [1],
                'targetData' => [],
                'weight' => [],
                'status' => 1,
                'dealer' => [],
                'multi_target' => 0,
                'hiddenMainProduct' => false,
                'description' => '',
                'checkOwner' => true,
            ]
        ],
        'status' => 1
    ],
    9999 => [
        'visul_id' => 'OS1',
        'identity' => [
            1 => [
                'visul_id' => 'OSP1',
                'name' => '學生階段上架',
                'product_id' => '1:9999',
                'loan_range_s' => 3000,
                'loan_range_e' => 120000,
                'interest_rate_s' => 5,
                'interest_rate_e' => 20,
                'charge_platform' => 5,
                'charge_platform_min' => PLATFORM_FEES_MIN,
                'certifications' => [
                    CERTIFICATION_IDCARD,
                    CERTIFICATION_STUDENT,
                    CERTIFICATION_DEBITCARD,
                    CERTIFICATION_SOCIAL,
                    CERTIFICATION_EMERGENCY,
                    CERTIFICATION_EMAIL,
                    CERTIFICATION_FINANCIAL
                ],
                'instalment' => [3, 6, 12, 18, 24],
                'repayment' => [1],
                'targetData' => [],
                'secondInstance' => false,
                'weight' => [],
                'status' => 1,
                'dealer' => [],
                'multi_target' => 1,
                'description' => '可申請額度<br>3000-12,000',
                'checkOwner' => false,
            ],
            2 => [
                'visul_id' => 'OSP2',
                'name' => '上班族階段上架',
                'product_id' => '2:9999',
                'loan_range_s' => 3000,
                'loan_range_e' => 200000,
                'interest_rate_s' => 5,
                'interest_rate_e' => 20,
                'charge_platform' => 5,
                'charge_platform_min' => PLATFORM_FEES_MIN,
                'certifications' => [
                    CERTIFICATION_IDCARD,
                    CERTIFICATION_DEBITCARD,
                    CERTIFICATION_SOCIAL,
                    CERTIFICATION_EMERGENCY,
                    CERTIFICATION_EMAIL,
                    CERTIFICATION_FINANCIAL,
                    // CERTIFICATION_DIPLOMA,
                    CERTIFICATION_INVESTIGATION,
                    CERTIFICATION_JOB
                ],
                'instalment' => [3, 6, 12, 18, 24],
                'repayment' => [1],
                'targetData' => [],
                'secondInstance' => false,
                'weight' => [],
                'status' => 1,
                'dealer' => [],
                'multi_target' => 1,
                'description' => '可申請額度<br>3000-20,000',
                'checkOwner' => false,
            ],
        ],
        'status' => 1
    ],
];

$config['app_product_totallist'] = [
    'nature' => ['LS1', 'NS1', 'LF1', 'DS2'],
    'company' => ['DS2','LJ2'],
];

$config['stage_option_cer'] = [2, 8, 9, 10];

$config['target_tips'] = '此標的使用者未提供完整資訊，下標前請審慎評估';

//產品轉換代碼
$config['subloan_list'] = 'STS|STNS|STIS|FGNS|FGIS';

//產品型態
$config['product_type'] = [
    1 => '信用貸款',
    2 => '分期付款',
//	3=> '抵押貸款',
];

//產品型態
$config['product_identity'] = [
    1 => '學生',
    2 => '社會新鮮人',
];

//計息方式
$config['repayment_type'] = [
    1 => '本息均攤',
    2 => '繳息不還本/按月付息',
    3 => '以日計息',
];

//學制
$config['school_system'] = [
    0 => '大學',
    1 => '碩士',
    2 => '博士',
    3 => '五專',
];

//科目名稱
$config['transaction_source'] = [
    1 => '平台代收',
    2 => '提領',
    3 => '出借款',
    4 => '平台服務費',
    5 => '轉換產品服務費',
    6 => '債權轉讓服務費',
    7 => '提前還款補償金',
    8 => '提前還款違約金',
    9 => '應付平台服務費',
    10 => '債權轉讓金',

    11 => '應付借款本金',
    12 => '還款本金',
    13 => '應付借款利息',
    14 => '還款利息',

    31 => '應收法催執行費',
    32 => '法催執行費',

    50 => '平台服務費沖正',
    51 => '債權轉讓服務費沖正',
    52 => '債權轉讓金沖正',
    53 => '銀行錯帳撥還',

    81 => '平台驗證費',
    82 => '平台驗證費退回',
    83 => '跨行轉帳費',
    84 => '跨行轉帳費退回',
    85 => '退款-不明原因',

    91 => '應付違約金',
    92 => '已還違約金',
    93 => '應付延滯息',
    94 => '已還延滯息',
];

$config['internal_transaction_source'] = [
    4 => '平台服務費',
    5 => '轉換產品服務費',
    6 => '債權轉讓服務費',
    7 => '提前還款補償金',
    8 => '違約金 - 提還 (提前還款手續費)',

    31 => '應收法催執行費',
    32 => '法催執行費',

    50 => '平台服務費沖正',
    51 => '債權轉讓服務費沖正',
    52 => '債權轉讓金沖正',
    53 => '錯帳匯費支出',

    81 => '平台驗證費',
    82 => '平台驗證費沖正',
    83 => '跨行轉帳費',
    84 => '跨行轉帳費沖正',
    85 => '退款-不明原因',

    92 => '違約金 - 逾期 (已還手續費)',
];
$config['transaction_type_name'] = [
    'recharge' => '代收',
    'withdraw' => '提領',
    'lending' => '放款',
    'subloan' => '產品轉換',
    'transfer' => '債權轉讓',
    'transfer_b' => '債權轉讓費沖正',
    'bank_wrong_tx' => '銀行錯帳撥還',
    'platform_wrong_tx' => '錯帳退款',
    'prepayment' => '提前還款',
    'charge_delay' => '逾期清償',
    'charge_normal' => '還款',
    'unknown_refund' => '退款-不明原因',
    'platform_law_fee' => '法催執行費',
];

$config['certifications'] = [
    1 => ['id' => CERTIFICATION_IDCARD, 'alias' => 'idcard', 'name' => '實名認證', 'status' => 1, 'description' => '驗證個人身份資訊', 'optional' => []],
    2 => ['id' => CERTIFICATION_STUDENT, 'alias' => 'student', 'name' => '學生身份認證', 'status' => 1, 'description' => '驗證學生身份', 'optional' => []],
    3 => ['id' => CERTIFICATION_DEBITCARD, 'alias' => 'debitcard', 'name' => '金融帳號認證', 'status' => 1, 'description' => '驗證個人金融帳號', 'optional' => []],
    4 => ['id' => CERTIFICATION_SOCIAL, 'alias' => 'social', 'name' => '社交認證', 'status' => 1, 'description' => '個人社交帳號認證', 'optional' => []],
    5 => ['id' => CERTIFICATION_EMERGENCY, 'alias' => 'emergency', 'name' => '緊急聯絡人', 'status' => 1, 'description' => '設定緊急連絡人資訊', 'optional' => []],
    6 => ['id' => CERTIFICATION_EMAIL, 'alias' => 'email', 'name' => '常用電子信箱', 'status' => 1, 'description' => '驗證常用E-Mail位址', 'optional' => []],
    7 => ['id' => CERTIFICATION_FINANCIAL, 'alias' => 'financial', 'name' => '財務訊息認證', 'status' => 1, 'description' => '提供財務訊息資訊', 'optional' => []],
    8 => ['id' => CERTIFICATION_DIPLOMA, 'alias' => 'diploma', 'name' => '最高學歷認證', 'status' => 1, 'description' => '提供最高學歷畢業資訊', 'optional' => []],
    9 => ['id' => CERTIFICATION_INVESTIGATION, 'alias' => 'investigation', 'name' => '聯合徵信認證', 'status' => 1, 'description' => '提供聯合徵信資訊', 'optional' => [3, 4]],
    10 => ['id' => CERTIFICATION_JOB, 'alias' => 'job', 'name' => '工作認證', 'status' => 1, 'description' => '提供工作訊息資訊', 'optional' => [3, 4]],
    11 => ['id' => CERTIFICATION_PROFILE, 'alias' => 'profile', 'name' => '個人資料表', 'status' => 1, 'description' => '提供個人資料', 'optional' => []],
	12 => ['id' => CERTIFICATION_INVESTIGATIONA11, 'alias' => 'investigationa11', 'name' => '聯合徵信認證+A11', 'status' => 1, 'description' => '提供負責人聯合徵信資訊', 'optional' => []],

    500 => ['id' => CERTIFICATION_SIMPLIFICATIONFINANCIAL, 'alias' => 'simplificationfinancial', 'name' => '財務訊息', 'status' => 1, 'description' => '提供個人簡易財務資料', 'optional' => []],
    501 => ['id' => CERTIFICATION_SIMPLIFICATIONJOB, 'alias' => 'simplificationjob', 'name' => '工作資料', 'status' => 1, 'description' => '提供簡易工作資料', 'optional' => []],

    1000 => ['id' => CERTIFICATION_BUSINESSTAX, 'alias' => 'businesstax', 'name' => '銷售額與稅額申報書(401表)', 'status' => 1, 'description' => '提供銷售額與稅額申報書(401表)', 'optional' => []],
    1001 => ['id' => CERTIFICATION_BALANCESHEET, 'alias' => 'balancesheet', 'name' => '資產負債表', 'status' => 1, 'description' => '提供資產負債表', 'optional' => []],
    1002 => ['id' => CERTIFICATION_INCOMESTATEMENT, 'alias' => 'incomestatement', 'name' => '損益表', 'status' => 1, 'description' => '提供損益表', 'optional' => []],
    1003 => ['id' => CERTIFICATION_INVESTIGATIONJUDICIAL, 'alias' => 'investigationjudicial', 'name' => '法人聯合徵信', 'status' => 1, 'description' => '提供法人聯合徵信', 'optional' => []],
    1004 => ['id' => CERTIFICATION_PASSBOOKCASHFLOW, 'alias' => 'passbookcashflow', 'name' => '金流證明', 'status' => 1, 'description' => '提供金流證明', 'optional' => []],
    1005 => ['id' => CERTIFICATION_INTERVIEW, 'alias' => 'interview', 'name' => '親訪報告', 'status' => 1, 'description' => '提供親訪報告', 'optional' => []],
    1006 => ['id' => CERTIFICATION_CERCREDITJUDICIAL, 'alias' => 'cercreditjudicial', 'name' => '信用評估表', 'status' => 1, 'description' => '提供信用評估表', 'optional' => []],
    1007 => ['id' => CERTIFICATION_GOVERNMENTAUTHORITIES, 'alias' => 'governmentauthorities', 'name' => '公司變更事項登記表', 'status' => 1, 'description' => '提供公司變更事項登記表', 'optional' => []],
    1008 => ['id' => CERTIFICATION_CHARTER, 'alias' => 'charter', 'name' => '公司章程', 'status' => 1, 'description' => '提供公司章程', 'optional' => []],
    1009 => ['id' => CERTIFICATION_REGISTEROFMEMBERS, 'alias' => 'registerofmembers', 'name' => '股東名簿', 'status' => 1, 'description' => '提供股東名簿', 'optional' => []],
    1010 => ['id' => CERTIFICATION_MAINPRODUCTSTATUS, 'alias' => 'mainproductstatus', 'name' => '主要商品銷售情況表', 'status' => 1, 'description' => '提供主要商品銷售情況表', 'optional' => []],
    1011 => ['id' => CERTIFICATION_STARTUPFUNDS, 'alias' => 'startupfunds', 'name' => '創業啟動金', 'status' => 1, 'description' => '提供創業啟動金', 'optional' => []],
    1012 => ['id' => CERTIFICATION_BUSINESS_PLAN, 'alias' => 'business_plan', 'name' => '商業企劃書', 'status' => 1, 'description' => '提供商業企劃書', 'optional' => []],
    1013 => ['id' => CERTIFICATION_VERIFICATION, 'alias' => 'verification', 'name' => '驗資基金證明', 'status' => 1, 'description' => '提供驗資基金證明', 'optional' => []],
    1014 => ['id' => CERTIFICATION_CONDENSEDBALANCESHEET, 'alias' => 'condensedbalancesheet', 'name' => '簡明資產負債表', 'status' => 1, 'description' => '提供簡明資產負債表', 'optional' => []],
    1015 => ['id' => CERTIFICATION_CONDENSEDINCOMESTATEMENT, 'alias' => 'condensedincomestatement', 'name' => '簡明損益表', 'status' => 1, 'description' => '提供簡明損益表', 'optional' => []],
    1016 => ['id' => CERTIFICATION_PURCHASESALESVENDORLIST, 'alias' => 'purchasesalesvendorlist', 'name' => '進銷貨廠商明細表', 'status' => 1, 'description' => '提供進銷貨廠商明細表', 'optional' => []],
    1017 => ['id' => CERTIFICATION_EMPLOYEEINSURANCELIST, 'alias' => 'employeeinsurancelist', 'name' => '員工投保人數資料', 'status' => 1, 'description' => '提供員工投保人數資料', 'optional' => []],
    1018 => ['id' => CERTIFICATION_PROFILEJUDICIAL, 'alias' => 'profilejudicial', 'name' => '公司資料表', 'status' => 1, 'description' => '提供公司資料表', 'optional' => []],
    1019 => ['id' => CERTIFICATION_COMPANYEMAIL, 'alias' => 'companyemail', 'name' => '公司電子信箱', 'status' => 1, 'description' => '驗證公司E-Mail位址', 'optional' => []],
    1020 => ['id' => CERTIFICATION_JUDICIALGUARANTEE, 'alias' => 'judicialguarantee', 'name' => '法人對保', 'status' => 1, 'description' => '法人線上對保', 'optional' => []],

    2000 => ['id' => CERTIFICATION_SALESDETAIL, 'alias' => 'salesdetail', 'name' => '庫存車銷售檔', 'status' => 1, 'description' => '', 'optional' => []],
];
$config['certifications_sort'] = [
    CERTIFICATION_IDCARD,
    CERTIFICATION_STUDENT,
    CERTIFICATION_DEBITCARD,
    CERTIFICATION_SOCIAL,
    CERTIFICATION_EMERGENCY,
    CERTIFICATION_EMAIL,
    CERTIFICATION_FINANCIAL,
    CERTIFICATION_DIPLOMA,
    CERTIFICATION_INVESTIGATION,
    CERTIFICATION_JOB,
    CERTIFICATION_PROFILE,
	CERTIFICATION_INVESTIGATIONA11,
    CERTIFICATION_SIMPLIFICATIONFINANCIAL,
    CERTIFICATION_SIMPLIFICATIONJOB,

    CERTIFICATION_GOVERNMENTAUTHORITIES,
    CERTIFICATION_INVESTIGATIONJUDICIAL,
    CERTIFICATION_BALANCESHEET,
    CERTIFICATION_INCOMESTATEMENT,
    CERTIFICATION_CONDENSEDBALANCESHEET,
    CERTIFICATION_CONDENSEDINCOMESTATEMENT,
    CERTIFICATION_PURCHASESALESVENDORLIST,
    CERTIFICATION_MAINPRODUCTSTATUS,
    CERTIFICATION_EMPLOYEEINSURANCELIST,
    CERTIFICATION_CHARTER,
    CERTIFICATION_REGISTEROFMEMBERS,
    CERTIFICATION_BUSINESS_PLAN,
    CERTIFICATION_PASSBOOKCASHFLOW,
    CERTIFICATION_BUSINESSTAX,
    CERTIFICATION_INTERVIEW,
//    CERTIFICATION_CERCREDITJUDICIAL,
    CERTIFICATION_STARTUPFUNDS,
    CERTIFICATION_VERIFICATION,
    CERTIFICATION_SALESDETAIL,
    CERTIFICATION_PROFILEJUDICIAL,
    CERTIFICATION_COMPANYEMAIL,
    CERTIFICATION_JUDICIALGUARANTEE
];

//支援XML銀行列表
$config['xml_bank_list'] = [
    '004', '005', '006', '007', '008', '009', '011', '012', '013', '016', '017', '021',
    '039', '050', '052', '053', '054', '081', '101', '102', '103', '108', '118', '147',
    '803', '805', '806', '807', '808', '809', '810', '812', '815', '816', '822',
];

$config['credit_level'] = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13];


$config['job_type_name'] = [
    0 => '外勤',
    1 => '内勤',
];

$config['seniority_range'] = [
    0 => '三個月以内（含）',
    1 => '三個月至半年（含）',
    2 => '半年至一年（含）',
    3 => '一年至三年（含）',
    4 => '三年以上',
];

$config['employee_range'] = [
    0 => '1~20（含）',
    1 => '20~50（含）',
    2 => '50~100（含）',
    3 => '100~500（含）',
    4 => '500~1000（含）',
    5 => '1000~5000（含）',
    6 => '5000以上',
];

$config['position_name'] = [
    0 => '一般員工',
    1 => '初級管理',
    2 => '中級管理',
    3 => '高級管理',
];

$config['industry_name'] = [
    'A' => '農、林、漁、牧業',
    'B' => '礦業及土石採取業',
    'C' => '製造業',
    'D' => '電力及燃氣供應業',
    'E' => '用水供應及污染整治業',
    'F' => '營建工程業',
    'G' => '批發及零售業',
    'H' => '運輸及倉儲業',
    'I' => '住宿及餐飲業',
    'J' => '出版、影音製作、傳播及資通訊服務業',
    'K' => '金融及保險業',
    'L' => '不動產業',
    'M' => '專業、科學及技術服務業',
    'N' => '支援服務業',
    'O' => '公共行政及國防；強制性社會安全',
    'P' => '教育業',
    'Q' => '醫療保健及社會工作服務業',
    'R' => '藝術、娛樂及休閒服務業',
    'S' => '其他服務業',
];


$config['action_Keyword'] = [
    0 => '轉換產品',
];

$config['selling_type'] = [
    0 => '手機',
    1 => '遊學',
    2 => '外匯車',
    127 => 'null',
    999 => '其它',
];

$config['character'] = [
	0 => '登記負責人',
    1 => '負責人',
    2 => '實際負責人',
    3 => '配偶',
    4 => '保證人甲',
    5 => '保證人乙',
];

//登記機關,用於商業統編查詢++
$config['Agency'] = array(
    '376410000A', //'新北市政府經濟發展局'
    '379100000G',//'臺北市商業處',
    '383100000G',//'高雄市政府',
    '376570000A',//'基隆市政府',
    '376580000A',// '新竹市政府',
    '376590000A',//'臺中市政府',
    '376610000A',//'臺南市政府',
    '376600000A',//'嘉義市政府',
    '376430000A',//'桃園市政府',
    '376440000A',//'新竹縣政府',
    '376420000A',//'宜蘭縣政府',
    '376450000A',//'苗栗縣政府',
    '376470000A',// '彰化縣政府',
    '376480000A',//'南投縣政府',
    '376490000A',//'雲林縣政府',
    '376500000A',//'嘉義縣政府',
    '376530000A',//'屏東縣政府',
    '376560000A',//'澎湖縣政府',
    '376550000A',//'花蓮縣政府',
    '376540000A',//'臺東縣政府',
    '371010000A',//'福建省金門縣政府',
    '371030000A' // '福建省連江縣政府',
);
//C登記機關,用於商業統編查詢--


$config['certifications_msg'] = [
    CERTIFICATION_IDCARD => [
        '身份非平台服務範圍，我們無法提供服務給您，造成不便，敬請見諒！',
        '照片顛倒，煩請您重新拍攝',
        '照片不清晰，煩請您重新拍攝',
        '持證自拍不清晰，煩請您重新拍攝',
        '持證自拍證件遮擋到臉部，請依照虛線範圍拍攝',
        '持證自拍手指遮住證件資訊，煩請您重新拍攝',
        '持證自拍未持身分證',
        '上傳證件有誤',
    ],
    CERTIFICATION_STUDENT => [
        '學制與證件不符',
        '學生證錯誤',
        'SIP資訊有誤',
        '身份非平台服務範圍，我們無法提供服務給您，造成不便，敬請見諒！',
    ],
    CERTIFICATION_DEBITCARD => [],
    CERTIFICATION_SOCIAL => [
        '您認證的IG非常用帳號，系統無法驗證',
        '您認證的IG非常用帳號，請洽 LINE@influxfin 客服，提供使用者編號協助進行FB驗證',
    ],
    CERTIFICATION_EMERGENCY => [
        '緊急連絡人資訊有誤',
        '請提供監護人之佐證資料，如：戶口名簿等政府單位核發文件',
    ],
    CERTIFICATION_EMAIL => [],
    CERTIFICATION_FINANCIAL> [],
    CERTIFICATION_DIPLOMA => [
        '畢業證書錯誤',
        '您的身份非平台服務範圍，我們無法提供服務給您，造成不便，敬請見諒！',
        '填寫的學制與證書不符',
    ],
    CERTIFICATION_INVESTIGATION => [
        '請寄送完整版電子聯徵資料至credit@influxfin.com，感謝您的配合！',
        '聯徵資料與平台規範不符',
    ],
    CERTIFICATION_JOB => [
        '勞保資料有誤，請您查閱最新的“勞保投保異動明細表”後上傳，感謝您的配合！',
        '勞保非近一個月申請',
        '未上傳勞保資訊',
        '投保年資不符，我們無法提供服務給您，造成不便，敬請見諒！',
        '缺少薪資轉入之存摺封面',
        '您的薪資轉入資料不足三個月',
        '未提供存摺相關資料',
    ],
    CERTIFICATION_PROFILE => [
        '系統無法判讀為本人，煩請您重新拍攝',
        '光線不足無法判讀，煩請您重新拍攝',
    ],

    1000 => [],
    1001 => [],
    1002 => [],
    1003 => [],
    1004 => [],
    1005 => [],
    1006 => [],

    2000 => []
];

$config['mail_event'] = '2020_spring';//current_event //19christmas //20_01
$config['use_taishin_selling_type'] = [2];
$config['use_borrow_account_selling_type'] = [2];

$config['target_status'] = [
    0 => '正常案-正常還款中',
    1 => '正常案-已結案',
    2 => '正常案-提前清償',
    3 => '逾期案-還款中',
    4 => '逾期案-清償',
];

$config['target_delay_range'] = [
    0 => '觀察資產(D7)',
    1 => '關注資產(M1)',
    2 => '次級資產(M2)',
    3 => '可疑資產(M3)',
    4 => '不良資產(>M3)',
];

$config['allow_fast_verify_product'] = [1, 3];

$config['allow_changeRate_product'] = [1, 3];

$config['social_patten'] = '全球|財經|數位|兩岸';

$config['no_prepayment_allowance'] = [1000];

$config['allow_aiBidding_product'] = [1, 2, 3, 4];

# 推播的相關設定
abstract class NotificationTargetCategory
{
	const Investment = 1;
	const Loan = 2;
	const All = 3;
}
abstract class NotificationType
{
	const Manual = 1;
	const RoutineReminder = 2;
}
abstract class NotificationStatus
{
	const Pending = 0;
	const Accepted = 1;
	const Rejected = 2;
	const Sent = 3;
	const Canceled = 4;
}

$config['notification'] = [
	'target_category_name' => [
		NotificationTargetCategory::Investment => '投資',
		NotificationTargetCategory::Loan => '借款',
		NotificationTargetCategory::All => '投資&借款'],
	'status' => [
		NotificationStatus::Pending => '待核可',
		NotificationStatus::Accepted => '待發送',
		NotificationStatus::Rejected => '已拒絕',
		NotificationStatus::Sent => '已發送',
		NotificationStatus::Canceled => '已取消'
	]
];

$config['no_prepayment_allowance'] = [PRODUCT_FOREX_CAR_VEHICLE];

$config['externalCooperation'] = [PRODUCT_SK_MILLION_SMEG];

//個人資料表
$config['cer_profile'] = [
    'RealPr' => ['登記負責人','配偶','甲保證人','乙保證人'],
    'IsPrSpouseGu' => ['是','否'],
    'PrEduLevel' => ['A'=>'國小','B'=>'國中','C'=>'高中職','D'=>'專科','E'=>'大學','F'=>'碩士','G'=>'博士','H'=>'無'],
    'OthRealPrRelWithPr' => ['A'=>'配偶','B'=>'血親','C'=>'姻親','D'=>'股東','E'=>'朋友','F'=>'本人','G'=>'其他','H'=>'與經營有關之借戶職員'],
    'GuOneRelWithPr' => ['A'=>'配偶','B'=>'血親','C'=>'姻親','D'=>'股東','E'=>'朋友','F'=>'本人','G'=>'其他','H'=>'與經營有關之借戶職員'],
    'GuOneCompany' => ['A'=>'公家機關','B'=>'上市櫃公司','C'=>'專業人士','D'=>'借戶','E'=>'其他民營企業','F'=>'無'],
    'GuTwoRelWithPr' => ['A'=>'配偶','B'=>'血親','C'=>'姻親','D'=>'股東','E'=>'朋友','F'=>'本人','G'=>'其他','H'=>'與經營有關之借戶職員'],
    'GuTwoCompany' => ['A'=>'公家機關','B'=>'上市櫃公司','C'=>'專業人士','D'=>'借戶','E'=>'其他民營企業','F'=>'無'],
];
//公司資料表
$config['cer_profilejudicial'] = [
    'IsBizRegAddrSelfOwn' => ['非自有','自有'],
    'BizRegAddrOwner' => ['A'=>'企業','B'=>'負責人','C'=>'負責人配偶'],
    'IsBizAddrEqToBizRegAddr' => ['不同於營業登記地址','同營業登記地址'],
];

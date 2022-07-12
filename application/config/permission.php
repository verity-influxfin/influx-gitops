<?php
$config['permission'] = [
    'Charity' => [
        'name' => '慈善專區',
        'menu' => [
            'index' => '慈善專區',
        ],
        'permission' => [
            'index' => ['model' => 'Charity', 'submodel' => 'index', 'action' => 'read'],
            'export' => ['model' => 'Charity', 'submodel' => 'index', 'action' => 'read'],
        ],
    ],
    'Product' => [
        'name' => '產品管理',
        'menu' => [
            'index' => '產品管理',
        ],
        'permission' => [
            'index' => ['model' => 'Product', 'submodel' => 'index', 'action' => 'read'],
        ],
    ],
    'AntiFraud' => [
        'name' => '反詐欺系統',
        'menu' => [
            'index' => '反詐欺管理指標',
            'list' => '反詐欺規則總覽',
        ],
        'permission' => [
            'index' => ['model' => 'AntiFraud', 'submodel' => 'index', 'action' => 'read'],
            'list' => ['model' => 'AntiFraud', 'submodel' => 'list', 'action' => 'read'],
        ],
    ],
    'Target' => [
        'name' => '借款管理',
        'menu' => [
            'index' => '全部列表',
            'waiting_evaluation' => '二審',
            'waiting_signing' => '待簽約',
            'waiting_verify' => '待上架',
            'waiting_bidding' => '已上架',
            'waiting_loan' => '待放款',
            'repayment' => '還款中',
            'finished' => '已結案',
            'repayment_delayed' => '逾期中',
            'prepayment' => '提前還款',
            'order_target' => '消費貸 - 案件列表',
            'waiting_approve_order_transfer' => '消費貸 - 債轉待批覆',
        ],
        'detail' => [
            'edit' => '借款詳細內容'
        ],
        'permission' => [
            'index' => ['model' => 'Target', 'submodel' => 'index', 'action' => 'read'],
            'detail' => ['model' => 'Target', 'submodel' => 'index', 'action' => 'read'],
            'waiting_evaluation' => ['model' => 'Target', 'submodel' => 'waiting_evaluation', 'action' => 'read'],
            'waiting_signing' => ['model' => 'Target', 'submodel' => 'waiting_signing', 'action' => 'read'],
            'waiting_verify' => ['model' => 'Target', 'submodel' => 'waiting_verify', 'action' => 'read'],
            'waiting_bidding' => ['model' => 'Target', 'submodel' => 'waiting_bidding', 'action' => 'read'],
            'waiting_loan' => ['model' => 'Target', 'submodel' => 'waiting_loan', 'action' => 'read'],
            'repayment' => ['model' => 'Target', 'submodel' => 'repayment', 'action' => 'read'],
            'finished' => ['model' => 'Target', 'submodel' => 'finished', 'action' => 'read'],
            'repayment_delayed' => ['model' => 'Target', 'submodel' => 'repayment_delayed', 'action' => 'read'],
            'prepayment' => ['model' => 'Target', 'submodel' => 'prepayment', 'action' => 'read'],
            'target_prepayment_detail' => ['model' => 'Target', 'submodel' => 'prepayment', 'action' => 'read'],
            'order_target' => ['model' => 'Target', 'submodel' => 'order_target', 'action' => 'read'],
            'waiting_approve_order_transfer' => ['model' => 'Target', 'submodel' => 'waiting_approve_order_transfer', 'action' => 'read'],
            'edit' => ['model' => 'Target', 'submodel' => 'index', 'action' => 'update'],
            'verify_success' => ['model' => 'Target', 'submodel' => 'waiting_verify', 'action' => 'update'],
            'verify_failed' => ['model' => 'Target', 'submodel' => 'waiting_verify', 'action' => 'update'],
            'order_fail' => ['model' => 'Target', 'submodel' => 'waiting_signing', 'action' => 'update'],
            'credits' => ['model' => 'Target', 'submodel' => 'waiting_evaluation', 'action' => 'update'],
            'evaluation_approval' => ['model' => 'Target', 'submodel' => 'waiting_evaluation', 'action' => 'update'],
            'final_validations' => ['model' => 'Target', 'submodel' => 'waiting_evaluation', 'action' => 'update'],
            'final_validations_detail' => ['model' => 'Target', 'submodel' => 'waiting_evaluation', 'action' => 'read'],
            'target_loan' => ['model' => 'Target', 'submodel' => 'waiting_loan', 'action' => 'update'],
            'target_loan_detail' => ['model' => 'Target', 'submodel' => 'waiting_loan', 'action' => 'read'],
            'subloan_success' => ['model' => 'Target', 'submodel' => 'waiting_loan', 'action' => 'update'],
            're_subloan' => ['model' => 'Target', 'submodel' => 'waiting_loan', 'action' => 'update'],
            'loan_return' => ['model' => 'Target', 'submodel' => 'waiting_loan', 'action' => 'update'],
            'loan_success' => ['model' => 'Target', 'submodel' => 'waiting_loan', 'action' => 'update'],
            'loan_failed' => ['model' => 'Target', 'submodel' => 'waiting_loan', 'action' => 'update'],
            'transaction_display' => ['model' => 'Target', 'submodel' => 'index', 'action' => 'update'],
            'target_repayment_export' => ['model' => 'Target', 'submodel' => 'repayment', 'action' => 'read'],
            'target_repayment_detail' => ['model' => 'Target', 'submodel' => 'repayment', 'action' => 'read'],
            'target_finished_export' => ['model' => 'Target', 'submodel' => 'finished', 'action' => 'read'],
            'target_finished_detail' => ['model' => 'Target', 'submodel' => 'finished', 'action' => 'read'],
            'target_waiting_signing_export' => ['model' => 'Target', 'submodel' => 'waiting_signing', 'action' => 'read'],
            'target_waiting_signing_detail' => ['model' => 'Target', 'submodel' => 'waiting_signing', 'action' => 'read'],
            'target_waiting_verify_detail' => ['model' => 'Target', 'submodel' => 'waiting_verify', 'action' => 'read'],
            'legal_doc_detail' => ['model' => 'PostLoan', 'submodel' => 'legal_doc', 'action' => 'read'],
            'amortization_export' => ['model' => 'Target', 'submodel' => 'repayment', 'action' => 'read'],
            'cancel_bidding' => ['model' => 'Target', 'submodel' => 'waiting_bidding', 'action' => 'update'],
            'approve_order_transfer' => ['model' => 'Target', 'submodel' => 'waiting_approve_order_transfer', 'action' => 'update'],
            'legalaffairs' => ['model' => 'Target', 'submodel' => 'index', 'action' => 'update'],
            'waiting_reinspection' => ['model' => 'Target', 'submodel' => 'waiting_verify', 'action' => 'update'],
            'skbank_text_get' => ['model' => 'Target', 'submodel' => 'waiting_verify', 'action' => 'update'],
            'skbank_text_send' => ['model' => 'Target', 'submodel' => 'waiting_verify', 'action' => 'update'],
            'skbank_image_get' => ['model' => 'Target', 'submodel' => 'waiting_verify', 'action' => 'update'],
            'transfer_detail' => ['model' => 'Transfer', 'submodel' => 'index', 'action' => 'read'],
            'obligations_detail' => ['model' => 'Transfer', 'submodel' => 'obligations', 'action' => 'read'],
            'waiting_transfer_detail' => ['model' => 'Transfer', 'submodel' => 'waiting_transfer', 'action' => 'read'],
            'waiting_transfer_success_detail' => ['model' => 'Transfer', 'submodel' => 'waiting_transfer_success', 'action' => 'read'],
        ],
    ],
    'Transfer' => [
        'name' => '債權管理',
        'menu' => [
            'index' => '全部列表',
            'obligations' => '全部列表(New)',
            'waiting_transfer' => '債轉待收購',
            'waiting_transfer_success' => '債轉待放款',
            'bidding' => '已投標',
        ],
        'detail' => [
            'edit' => '債權詳細內容'
        ],
        'permission' => [
            'index' => ['model' => 'Transfer', 'submodel' => 'index', 'action' => 'read'],
            'obligations' => ['model' => 'Transfer', 'submodel' => 'obligations', 'action' => 'read'],
            'waiting_transfer' => ['model' => 'Transfer', 'submodel' => 'waiting_transfer', 'action' => 'read'],
            'waiting_transfer_success' => ['model' => 'Transfer', 'submodel' => 'waiting_transfer_success', 'action' => 'read'],
            'bidding' => ['model' => 'Transfer', 'submodel' => 'bidding', 'action' => 'read'],
            'assets_export_new' => ['model' => 'Transfer', 'submodel' => 'obligations', 'action' => 'read'],
            'transfer_assets_export' => ['model' => 'Transfer', 'submodel' => 'index', 'action' => 'read'],
            'obligation_assets_export' => ['model' => 'Transfer', 'submodel' => 'obligations', 'action' => 'read'],
            'amortization_schedule' => ['model' => 'Transfer', 'submodel' => 'obligations', 'action' => 'read'],
            'amortization_export' => ['model' => 'Transfer', 'submodel' => 'index', 'action' => 'read'],
            'transfer_combination' => ['model' => 'Transfer', 'submodel' => 'waiting_transfer', 'action' => 'read'],
            'transfer_combination_success' => ['model' => 'Transfer', 'submodel' => 'waiting_transfer_success', 'action' => 'read'],
            'transfer_success' => ['model' => 'Transfer', 'submodel' => 'waiting_transfer_success', 'action' => 'update'],
            'transfer_cancel' => ['model' => 'Transfer', 'submodel' => 'waiting_transfer_success', 'action' => 'update'],
            'c_transfer_cancel' => ['model' => 'Transfer', 'submodel' => 'waiting_transfer_success', 'action' => 'update'],
        ],
    ],
    'Risk' => [
        'name' => '風控專區',
        'menu' => [
            'natural_person' => '自然人借款端審核',
            'juridical_person' => '法人借款端審核',
            'investor' => '投資端審核',
            'credit' => '信評管理',
            'credit_management' => '授信審核表',
            'black_list' => '黑名單列表',
        ],
        'permission' => [
            'natural_person' => ['model' => 'Risk', 'submodel' => 'natural_person', 'action' => 'read'],
            'get_natural_person_list' => ['model' => 'Risk', 'submodel' => 'natural_person', 'action' => 'read'],
            'juridical_person' => ['model' => 'Risk', 'submodel' => 'juridical_person', 'action' => 'read'],
            'investor' => ['model' => 'Risk', 'submodel' => 'investor', 'action' => 'read'],
            'credit' => ['model' => 'Risk', 'submodel' => 'credit', 'action' => 'read'],
            'credit_management' => ['model' => 'Risk', 'submodel' => 'credit_management', 'action' => 'read'],
            'black_list' => ['model' => 'Risk', 'submodel' => 'black_list', 'action' => 'read'],
            'push_info' => ['model' => 'Target', 'submodel' => 'index', 'action' => 'update'],
            'push_info_add' => ['model' => 'Target', 'submodel' => 'index', 'action' => 'update'],
            'push_info_remove' => ['model' => 'Target', 'submodel' => 'index', 'action' => 'update'],
            'push_info_update' => ['model' => 'Target', 'submodel' => 'index', 'action' => 'update'],
            'push_audit' => ['model' => 'Target', 'submodel' => 'index', 'action' => 'update'],
            'push_audit_add' => ['model' => 'Target', 'submodel' => 'index', 'action' => 'update'],
            'judicial_associates' => ['model' => 'Risk', 'submodel' => 'juridical_person', 'action' => 'read'],
        ],
    ],
    'Passbook' => [
        'name' => '虛擬帳號管理',
        'menu' => [
            'user_bankaccount_list' => '金融帳號認證',
            'index' => '虛擬帳號列表',
            'withdraw_list' => '提領紀錄',
            'withdraw_waiting' => '提領待放款',
            'unknown_funds' => '不明來源退款',
        ],
        'permission' => [
            'user_bankaccount_list' => ['model' => 'Passbook', 'submodel' => 'user_bankaccount_list', 'action' => 'read'],
            'index' => ['model' => 'Passbook', 'submodel' => 'index', 'action' => 'read'],
            'withdraw_list' => ['model' => 'Passbook', 'submodel' => 'withdraw_list', 'action' => 'read'],
            'withdraw_waiting' => ['model' => 'Passbook', 'submodel' => 'withdraw_waiting', 'action' => 'read'],
            'unknown_funds' => ['model' => 'Passbook', 'submodel' => 'unknown_funds', 'action' => 'read'],
            'edit' => ['model' => 'Passbook', 'submodel' => 'index', 'action' => 'update'],
            'display' => ['model' => 'Passbook', 'submodel' => 'index', 'action' => 'read'],
            'withdraw_loan' => ['model' => 'Passbook', 'submodel' => 'withdraw_waiting', 'action' => 'read'],
            'unknown_refund' => ['model' => 'Passbook', 'submodel' => 'unknown_funds', 'action' => 'update'],
            'loan_success' => ['model' => 'Passbook', 'submodel' => 'withdraw_waiting', 'action' => 'update'],
            'loan_failed' => ['model' => 'Passbook', 'submodel' => 'withdraw_waiting', 'action' => 'update'],
            'withdraw_by_admin' => ['model' => 'Passbook', 'submodel' => 'index', 'action' => 'update'],
            'withdraw_deny' => ['model' => 'Passbook', 'submodel' => 'withdraw_waiting', 'action' => 'update'],
            'user_bankaccount_edit' => ['model' => 'Passbook', 'submodel' => 'user_bankaccount_list', 'action' => 'update'],
            'user_bankaccount_success' => ['model' => 'Passbook', 'submodel' => 'user_bankaccount_list', 'action' => 'update'],
            'user_bankaccount_failed' => ['model' => 'Passbook', 'submodel' => 'user_bankaccount_list', 'action' => 'update'],
            'user_bankaccount_resend' => ['model' => 'Passbook', 'submodel' => 'user_bankaccount_list', 'action' => 'update'],
            'user_bankaccount_verify' => ['model' => 'Passbook', 'submodel' => 'user_bankaccount_list', 'action' => 'read'],
        ],
    ],
    'Judicialperson' => [
        'name' => '法人管理',
        'menu' => [
            'juridical_apply' => '法人申請列表',
            'juridical_management' => '法人管理列表',
            'cooperation_apply' => '經銷商申請列表',
            'cooperation_management' => '經銷商管理列表',
        ],
        'permission' => [
            'juridical_apply' => ['model' => 'Judicialperson', 'submodel' => 'juridical_apply', 'action' => 'read'],
            'juridical_management' => ['model' => 'Judicialperson', 'submodel' => 'juridical_management', 'action' => 'read'],
            'cooperation_apply' => ['model' => 'Judicialperson', 'submodel' => 'cooperation_apply', 'action' => 'read'],
            'cooperation_management' => ['model' => 'Judicialperson', 'submodel' => 'cooperation_management', 'action' => 'read'],
            'juridical_apply_edit' => ['model' => 'Judicialperson', 'submodel' => 'juridical_apply', 'action' => 'update'],
            'juridical_management_edit' => ['model' => 'Judicialperson', 'submodel' => 'juridical_management', 'action' => 'update'],
            'cooperation_apply_edit' => ['model' => 'Judicialperson', 'submodel' => 'cooperation_apply', 'action' => 'update'],
            'cooperation_management_edit' => ['model' => 'Judicialperson', 'submodel' => 'cooperation_management', 'action' => 'update'],
            'cooperation_apply_success' => ['model' => 'Judicialperson', 'submodel' => 'cooperation_apply', 'action' => 'update'],
            'cooperation_management_success' => ['model' => 'Judicialperson', 'submodel' => 'cooperation_management', 'action' => 'update'],
            'cooperation_apply_failed' => ['model' => 'Judicialperson', 'submodel' => 'cooperation_apply', 'action' => 'update'],
            'cooperation_management_failed' => ['model' => 'Judicialperson', 'submodel' => 'cooperation_management', 'action' => 'update',],
        ],
    ],
    'Creditmanagement' => [
        'name' => '授審表',
        'menu' => [
            'index' => '列表(還沒做)',
        ],
        'permission' => [
            'index' => ['model' => 'Creditmanagement', 'submodel' => 'index', 'action' => 'read'],
            'report_final_validations' => ['model' => 'Target', 'submodel' => 'waiting_evaluation', 'action' => 'read'],
            'report_natural_person' => ['model' => 'Risk', 'submodel' => 'natural_person', 'action' => 'read'],
            'report_targets_edit' => ['model' => 'Target', 'submodel' => 'waiting_signing', 'action' => 'read'],
            'final_validations_get_structural_data' => ['model' => 'Target', 'submodel' => 'waiting_evaluation', 'action' => 'read'],
            'natural_person_get_structural_data' => ['model' => 'Risk', 'submodel' => 'natural_person', 'action' => 'read'],
            'get_reviewed_list' => ['model' => 'Target', 'submodel' => 'waiting_evaluation', 'action' => 'read'],
            'get_data' => ['model' => 'Risk', 'submodel' => 'natural_person', 'action' => 'read'],
            'approve' => ['model' => 'Target', 'submodel' => 'waiting_evaluation', 'action' => 'update'],
            'waiting_bidding_report' => ['model' => 'Target', 'submodel' => 'waiting_bidding', 'action' => 'read'],
        ],
    ],
    'Certification' => [
        'name' => '認證管理',
        'menu' => [
            'index' => '認證方式列表',
            'user_certification_list' => '會員認證審核',
            'difficult_word_list' => '銀行困難字管理',
        ],
        'permission' => [
            'index' => ['model' => 'Certification', 'submodel' => 'index', 'action' => 'read'],
            'user_certification_list' => ['model' => 'Certification', 'submodel' => 'user_certification_list', 'action' => 'read'],
            'difficult_word_list' => ['model' => 'Certification', 'submodel' => 'difficult_word_list', 'action' => 'read'],
            'user_certification_edit' => ['model' => 'Certification', 'submodel' => 'user_certification_list', 'action' => 'update'],
            'user_bankaccount_edit' => ['model' => 'Passbook', 'submodel' => 'user_bankaccount_list', 'action' => 'update'],
            'user_bankaccount_success' => ['model' => 'Passbook', 'submodel' => 'user_bankaccount_list', 'action' => 'update'],
            'user_bankaccount_failed' => ['model' => 'Passbook', 'submodel' => 'user_bankaccount_list', 'action' => 'update'],
            'user_bankaccount_resend' => ['model' => 'Passbook', 'submodel' => 'user_bankaccount_list', 'action' => 'update'],
            'user_bankaccount_verify' => ['model' => 'Passbook', 'submodel' => 'user_bankaccount_list', 'action' => 'read'],
            'difficult_word_add' => ['model' => 'Certification', 'submodel' => 'difficult_word_list', 'action' => 'create'],
            'difficult_word_edit' => ['model' => 'Certification', 'submodel' => 'difficult_word_list', 'action' => 'update'],
            'verdict_statuses' => ['model' => 'Certification', 'submodel' => 'user_certification_list', 'action' => 'read'],
            'verdict_count' => ['model' => 'Certification', 'submodel' => 'user_certification_list', 'action' => 'read'],
            'verdict' => ['model' => 'Certification', 'submodel' => 'user_certification_list', 'action' => 'read'],
            'judicial_yuan_case' => ['model' => 'Certification', 'submodel' => 'user_certification_list', 'action' => 'read'],
            'media_upload' => ['model' => 'Certification', 'submodel' => 'index', 'action' => 'update'],
            'hasspouse' => ['model' => 'Certification', 'submodel' => 'user_certification_list', 'action' => 'update'],
            'sendskbank' => ['model' => 'Certification', 'submodel' => 'user_certification_list', 'action' => 'update'],
            'getskbank' => ['model' => 'Certification', 'submodel' => 'user_certification_list', 'action' => 'update'],
            'save_meta' => ['model' => 'Certification', 'submodel' => 'user_certification_list', 'action' => 'update'],
            'getmeta' => ['model' => 'Certification', 'submodel' => 'user_certification_list', 'action' => 'update'],
            'job_credits' => ['model' => 'Certification', 'submodel' => 'user_certification_list', 'action' => 'update'],
            'joint_credits' => ['model' => 'Certification', 'submodel' => 'user_certification_list', 'action' => 'update'],
            'sip' => ['model' => 'Certification', 'submodel' => 'user_certification_list', 'action' => 'update'],
            'sip_login' => ['model' => 'Certification', 'submodel' => 'user_certification_list', 'action' => 'update'],
        ],
    ],
    'Scraper' => [
        'name' => '爬蟲系統',
        'menu' => [
            'index' => '會員爬蟲列表',
        ],
        'permission' => [
            'index' => ['model' => 'Scraper', 'submodel' => 'index', 'action' => 'read'],
            'scraper_status' => ['model' => 'Scraper', 'submodel' => 'index', 'action' => 'read'],
            'judicial_yuan_info' => ['model' => 'Scraper', 'submodel' => 'index', 'action' => 'read'],
            'judicial_yuan_verdict' => ['model' => 'Scraper', 'submodel' => 'index', 'action' => 'read'],
            'judicial_yuan_case' => ['model' => 'Scraper', 'submodel' => 'index', 'action' => 'read'],
            'sip_info' => ['model' => 'Scraper', 'submodel' => 'index', 'action' => 'read'],
            'sip' => ['model' => 'Scraper', 'submodel' => 'index', 'action' => 'read'],
            'bizandbr_info' => ['model' => 'Scraper', 'submodel' => 'index', 'action' => 'read'],
            'biz' => ['model' => 'Scraper', 'submodel' => 'index', 'action' => 'read'],
            'businessregistration' => ['model' => 'Scraper', 'submodel' => 'index', 'action' => 'read'],
            'google_info' => ['model' => 'Scraper', 'submodel' => 'index', 'action' => 'read'],
            'google' => ['model' => 'Scraper', 'submodel' => 'index', 'action' => 'read'],
            'ptt_info' => ['model' => 'Scraper', 'submodel' => 'index', 'action' => 'read'],
            'ptt' => ['model' => 'Scraper', 'submodel' => 'index', 'action' => 'read'],
            'instagram_info' => ['model' => 'Scraper', 'submodel' => 'index', 'action' => 'read'],
            'instagram' => ['model' => 'Scraper', 'submodel' => 'index', 'action' => 'read'],
        ],
    ],
    'Partner' => [
        'name' => '合作夥伴管理',
        'menu' => [
            'partner_type' => '合作商類別',
            'index' => '合作商列表',
        ],
        'permission' => [
            'partner_type' => ['model' => 'Partner', 'submodel' => 'partner_type', 'action' => 'read'],
            'index' => ['model' => 'Partner', 'submodel' => 'index', 'action' => 'read'],
            'add' => ['model' => 'Partner', 'submodel' => 'index', 'action' => 'create'],
            'edit' => ['model' => 'Partner', 'submodel' => 'index', 'action' => 'update'],
            'partner_type_add' => ['model' => 'Partner', 'submodel' => 'partner_type', 'action' => 'create'],
        ],
    ],
    'Contact' => [
        'name' => '客服管理',
        'menu' => [
            'index' => '投訴與建議',
            'send_email' => '通知工具',
            'certifications' => '會員認證審核列表',
        ],
        'permission' => [
            'index' => ['model' => 'Contact', 'submodel' => 'index', 'action' => 'read'],
            'send_email' => ['model' => 'Contact', 'submodel' => 'send_email', 'action' => 'read'],
            'certifications' => ['model' => 'Contact', 'submodel' => 'certifications', 'action' => 'read'],
            'edit' => ['model' => 'Contact', 'submodel' => 'index', 'action' => 'update'],
            'update_notificaion' => ['model' => 'Contact', 'submodel' => 'send_email', 'action' => 'update'],
        ],
    ],
    'User' => [
        'name' => '會員管理',
        'menu' => [
            'index' => '會員列表',
            'blocked_users' => '鎖定帳號管理',
        ],
        'detail' => [
            'edit' => '會員詳細內容'
        ],
        'permission' => [
            'index' => ['model' => 'User', 'submodel' => 'index', 'action' => 'read'],
            'detail' => ['model' => 'User', 'submodel' => 'index', 'action' => 'read'],
            'blocked_users' => ['model' => 'User', 'submodel' => 'blocked_users', 'action' => 'read'],
            'edit' => ['model' => 'User', 'submodel' => 'index', 'action' => 'update'],
            'display' => ['model' => 'User', 'submodel' => 'index', 'action' => 'read'],
            'block_users' => ['model' => 'User', 'submodel' => 'blocked_users', 'action' => 'update'],
            'get_user_notification' => ['model' => 'User', 'submodel' => 'index', 'action' => 'read'],
            'judicialyuan' => ['model' => 'User', 'submodel' => 'index', 'action' => 'update'],
        ],
    ],
    'Admin' => [
        'name' => '後台人員管理',
        'menu' => [
            'index' => '人員列表',
            'group_permission_list' => '部門權限設定',
            'admin_permission_list' => '人員權限設定',
            'permission_grant_list' => '權限審核',
            'permission_search' => '權限查詢',
        ],
        'permission' => [
            'index' => ['model' => 'Admin', 'submodel' => 'index', 'action' => 'read'],
            'group_permission_list' => ['model' => 'Admin', 'submodel' => 'group_permission_list', 'action' => 'read'],
            'get_group_permission_list' => ['model' => 'Admin', 'submodel' => 'group_permission_list', 'action' => 'read'],
            'admin_permission_list' => ['model' => 'Admin', 'submodel' => 'admin_permission_list', 'action' => 'read'],
            'permission_grant_list' => ['model' => 'Admin', 'submodel' => 'permission_grant_list', 'action' => 'read'],
            'permission_search' => ['model' => 'Admin', 'submodel' => 'permission_search', 'action' => 'read'],
            'add' => ['model' => 'Admin', 'submodel' => 'index', 'action' => 'create'],
            'edit' => ['model' => 'Admin', 'submodel' => 'index', 'action' => 'update'],
            'get_promote_code' => ['model' => 'Admin', 'submodel' => 'index', 'action' => 'update'],
            'role_add' => ['model' => 'Admin', 'submodel' => 'role_list', 'action' => 'create'],
            'role_edit' => ['model' => 'Admin', 'submodel' => 'role_list', 'action' => 'update'],
            'group_permission_list_get' => ['model' => 'Admin', 'submodel' => 'group_permission_list', 'action' => 'read'],
            'group_permission_edit' => ['model' => 'Admin', 'submodel' => 'group_permission_list', 'action' => 'update'],
            'group_permission_add' => ['model' => 'Admin', 'submodel' => 'group_permission_list', 'action' => 'create'],
            'get_admin_permission_list' => ['model' => 'Admin', 'submodel' => 'admin_permission_list', 'action' => 'read'],
            'admin_permission_edit' => ['model' => 'Admin', 'submodel' => 'admin_permission_list', 'action' => 'update'],
            'admin_permission_add' => ['model' => 'Admin', 'submodel' => 'admin_permission_list', 'action' => 'create'],
            'get_permission_list' => ['model' => 'Admin', 'submodel' => 'permission_grant_list', 'action' => 'read'],
            'permission_detail' => ['model' => 'Admin', 'submodel' => 'permission_search', 'action' => 'read'],
            'update_permission_status' => ['model' => 'Admin', 'submodel' => 'permission_grant_list', 'action' => 'update'],
            'get_group_list' => ['model' => 'Admin', 'submodel' => 'admin_permission_list', 'action' => 'update'],
        ],
    ],
    'Sales' => [
        'name' => '業務報表',
        'menu' => [
            'index' => '借款報表',
            'register_report' => '註冊報表',
            'bonus_report' => '獎金報表',
            'loan_overview' => '申貸總覽',
            'valuable_report' => '高價值用戶報表',
            'promote_list' => '推薦有賞',
            'promote_reward_list' => '推薦有賞放款',
            'qrcode_projects' => 'QRcode方案設定',
            'qrcode_contracts' => 'QRcode合約審核',
            'sales_report' => '績效統計表',
        ],
        'detail' => [
            'promote_edit' => '推薦有賞詳細內容'
        ],
        'permission' => [
            'index' => ['model' => 'Sales', 'submodel' => 'index', 'action' => 'read'],
            'register_report' => ['model' => 'Sales', 'submodel' => 'register_report', 'action' => 'read'],
            'accounts' => ['model' => 'Sales', 'submodel' => 'register_report', 'action' => 'read'],
            'bonus_report' => ['model' => 'Sales', 'submodel' => 'bonus_report', 'action' => 'read'],
            'loan_overview' => ['model' => 'Sales', 'submodel' => 'loan_overview', 'action' => 'read'],
            'valuable_report' => ['model' => 'Sales', 'submodel' => 'valuable_report', 'action' => 'read'],
            'promote_list' => ['model' => 'Sales', 'submodel' => 'promote_list', 'action' => 'read'],
            'promote_reward_list' => ['model' => 'Sales', 'submodel' => 'promote_reward_list', 'action' => 'read'],
            'qrcode_projects' => ['model' => 'Sales', 'submodel' => 'qrcode_projects', 'action' => 'read'],
            'promote_contract_submit' => ['model' => 'Sales', 'submodel' => 'qrcode_projects', 'action' => 'update'],
            'promote_apply_list' => ['model' => 'Sales', 'submodel' => 'qrcode_projects', 'action' => 'read'],
            'promote_review_contract' => ['model' => 'Sales', 'submodel' => 'qrcode_projects', 'action' => 'update'],
            'promote_modify_contract' => ['model' => 'Sales', 'submodel' => 'qrcode_projects', 'action' => 'update'],
            'qrcode_contracts' => ['model' => 'Sales', 'submodel' => 'qrcode_contracts', 'action' => 'read'],
            'promote_review_list' => ['model' => 'Sales', 'submodel' => 'qrcode_contracts', 'action' => 'read'],
            'sales_report' => ['model' => 'Sales', 'submodel' => 'sales_report', 'action' => 'read'],
            'set_goal' => ['model' => 'Sales', 'submodel' => 'sales_report', 'action' => 'update'],
            'goals_export' => ['model' => 'Sales', 'submodel' => 'sales_report', 'action' => 'read'],
            'set_monthly_goals' => ['model' => 'Sales', 'submodel' => 'sales_report', 'action' => 'update'],
            'monthly_goals_edit' => ['model' => 'Sales', 'submodel' => 'sales_report', 'action' => 'update'],
            'goal_edit' => ['model' => 'Sales', 'submodel' => 'sales_report', 'action' => 'update'],
            'bonus_report_detail' => ['model' => 'Sales', 'submodel' => 'bonus_report', 'action' => 'read'],
            'promote_edit' => ['model' => 'Sales', 'submodel' => 'promote_list', 'action' => 'update'],
            'promote_set_status' => ['model' => 'Sales', 'submodel' => 'promote_list', 'action' => 'update'],
            'promote_detail' => ['model' => 'Sales', 'submodel' => 'promote_list', 'action' => 'read'],
            'promote_reward_loan' => ['model' => 'Sales', 'submodel' => 'promote_reward_list', 'action' => 'update'],
        ],
    ],
    'Account' => [
        'name' => '財務作業',
        'menu' => [
            'daily_report' => '虛擬帳戶交易明細表',
            'passbook_report' => '虛擬帳號餘額明細表',
            'estatement' => '個人對帳單',
            'index' => '收支統計表',
        ],
        'permission' => [
            'daily_report' => ['model' => 'Account', 'submodel' => 'daily_report', 'action' => 'read'],
            'passbook_report' => ['model' => 'Account', 'submodel' => 'passbook_report', 'action' => 'read'],
            'estatement' => ['model' => 'Account', 'submodel' => 'estatement', 'action' => 'read'],
            'index' => ['model' => 'Account', 'submodel' => 'index', 'action' => 'read'],
            'estatement_excel' => ['model' => 'Account', 'submodel' => 'estatement', 'action' => 'read'],
            'estatement_s_excel' => ['model' => 'Account', 'submodel' => 'estatement', 'action' => 'read'],
        ],
    ],
    'Ocr' => [
        'name' => 'OCR 結果',
        'menu' => [
            'index' => '報表',
        ],
        'permission' => [
            'index' => ['model' => 'Ocr', 'submodel' => 'index', 'action' => 'read'],
            'reports' => ['model' => 'Ocr', 'submodel' => 'index', 'action' => 'read'],
            'report' => ['model' => 'Ocr', 'submodel' => 'index', 'action' => 'read'],
            'save' => ['model' => 'Ocr', 'submodel' => 'index', 'action' => 'update'],
            'send' => ['model' => 'Ocr', 'submodel' => 'index', 'action' => 'update'],
        ],
    ],
    'PostLoan' => [
        'name' => '貸後管理',
        'menu' => [
            'legal_doc' => '法訴文件管理',
            'deduct' => '法催扣款',
        ],
        'permission' => [
            'legal_doc' => ['model' => 'PostLoan', 'submodel' => 'legal_doc', 'action' => 'read'],
            'deduct' => ['model' => 'PostLoan', 'submodel' => 'deduct', 'action' => 'read'],
            'get_deduct_list' => ['model' => 'PostLoan', 'submodel' => 'deduct', 'action' => 'read'],
            'add_deduct_info' => ['model' => 'PostLoan', 'submodel' => 'deduct', 'action' => 'create'],
            'update_deduct_info' => ['model' => 'PostLoan', 'submodel' => 'deduct', 'action' => 'update'],
            'save_status' => ['model' => 'PostLoan', 'submodel' => 'legal_doc', 'action' => 'update'],
            'legal_doc_status' => ['model' => 'PostLoan', 'submodel' => 'legal_doc', 'action' => 'read'],
        ],
    ],
    'Article' => [
        'name' => '活動及最新消息',
        'menu' => [
            'index' => '最新活動',
            'news' => '最新消息',
        ],
        'permission' => [
            'index' => ['model' => 'Article', 'submodel' => 'index', 'action' => 'read'],
            'news' => ['model' => 'Article', 'submodel' => 'news', 'action' => 'read'],
            'article_add' => ['model' => 'Article', 'submodel' => 'index', 'action' => 'create'],
            'article_edit' => ['model' => 'Article', 'submodel' => 'index', 'action' => 'update'],
            'article_success' => ['model' => 'Article', 'submodel' => 'index', 'action' => 'update'],
            'article_failed' => ['model' => 'Article', 'submodel' => 'index', 'action' => 'update'],
            'article_del' => ['model' => 'Article', 'submodel' => 'index', 'action' => 'delete'],
            'news_add' => ['model' => 'Article', 'submodel' => 'news', 'action' => 'create'],
            'news_edit' => ['model' => 'Article', 'submodel' => 'news', 'action' => 'update'],
            'news_success' => ['model' => 'Article', 'submodel' => 'news', 'action' => 'update'],
            'news_failed' => ['model' => 'Article', 'submodel' => 'news', 'action' => 'update'],
            'news_del' => ['model' => 'Article', 'submodel' => 'news', 'action' => 'delete'],
        ],
    ],
    'Agreement' => [
        'name' => '協議書',
        'menu' => [
            'index' => '協議書',
        ],
        'permission' => [
            'index' => ['model' => 'Agreement', 'submodel' => 'index', 'action' => 'read'],
            'editagreement' => ['model' => 'Agreement', 'submodel' => 'index', 'action' => 'update'],
            'insertagreement' => ['model' => 'Agreement', 'submodel' => 'index', 'action' => 'create'],
            'updateagreement' => ['model' => 'Agreement', 'submodel' => 'index', 'action' => 'update'],
            'deleteagreement' => ['model' => 'Agreement', 'submodel' => 'index', 'action' => 'delete'],
            'aliasunique' => ['model' => 'Agreement', 'submodel' => 'index', 'action' => 'update'],
        ],
    ],
    'Contract' => [
        'name' => '合約書',
        'menu' => [
            'index' => '合約書',
        ],
        'permission' => [
            'index' => ['model' => 'Contract', 'submodel' => 'index', 'action' => 'read'],
            'editcontract' => ['model' => 'Contract', 'submodel' => 'index', 'action' => 'update'],
            'updatecontract' => ['model' => 'Contract', 'submodel' => 'index', 'action' => 'update'],
            'validateinput' => ['model' => 'Contract', 'submodel' => 'index', 'action' => 'update'],
            'typeunique' => ['model' => 'Contract', 'submodel' => 'index', 'action' => 'update'],
        ],
    ],
    'Creditmanagementtable' => [
        'permission' => [
            'index' => ['model' => 'Ocr', 'submodel' => 'index', 'action' => 'read'],
            'waiting_reinspection_report' => ['model' => 'Target', 'submodel' => 'waiting_verify', 'action' => 'read'],
            'juridical_person_report' => ['model' => 'Risk', 'submodel' => 'juridical_person', 'action' => 'read'],
            'report' => ['model' => 'Risk', 'submodel' => 'juridical_person', 'action' => 'read'],
        ]
    ],
    'Bankdata' => [
        'permission' => [
            'index' => ['model' => 'Ocr', 'submodel' => 'index', 'action' => 'read'],
            'juridical_person_report' => ['model' => 'Risk', 'submodel' => 'juridical_person', 'action' => 'read'],
            'getmappingmsgno' => ['model' => 'Target', 'submodel' => 'waiting_evaluation', 'action' => 'update'],
            'savechecklistdata' => ['model' => 'Risk', 'submodel' => 'juridical_person', 'action' => 'update'],
            'report' => ['model' => 'Risk', 'submodel' => 'juridical_person', 'action' => 'read'],
        ]
    ],
    'Brookesia' => [
        'permission' => [
            'user_rule_hit' => ['model' => 'Target', 'submodel' => 'waiting_verify', 'action' => 'update'],
            'final_valid_user_rule_hit' => ['model' => 'Target', 'submodel' => 'waiting_evaluation', 'action' => 'update'],
            'user_related_user' => ['model' => 'Target', 'submodel' => 'waiting_verify', 'action' => 'update'],
            'final_valid_user_related_user' => ['model' => 'Target', 'submodel' => 'waiting_evaluation', 'action' => 'update'],
        ],
    ],
    'Certificationreport' => [
        'permission' => [
            'index' => ['model' => 'Target', 'submodel' => 'waiting_evaluation', 'action' => 'read'],
            'report' => ['model' => 'Target', 'submodel' => 'waiting_evaluation', 'action' => 'read'],
            'get_data' => ['model' => 'Target', 'submodel' => 'waiting_evaluation', 'action' => 'read'],
            'send_data' => ['model' => 'Target', 'submodel' => 'waiting_evaluation', 'action' => 'update'],
        ],
    ],
];
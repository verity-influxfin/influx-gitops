<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('EXT', '.php');
/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS'			, 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR'			, 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG'			, 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE'	, 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS'	, 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD'	, 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT'		, 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE'		, 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN'		, 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX'		, 125); // highest automatically-assigned error code


//Error Code
defined('TOKEN_NOT_CORRECT')   			OR define('TOKEN_NOT_CORRECT'			, 100); // token not exit
defined('BLOCK_USER')   				OR define('BLOCK_USER'					, 101); // block status
defined('KEY_FAIL')   			        OR define('KEY_FAIL'		            , 102); // key not exit
defined('SYSTEM_BLOCK_USER')			OR define('SYSTEM_BLOCK_USER'	    	, 120); // SYSTEM_BLOCK_USER
defined('TEMP_BLOCK_USER')			    OR define('TEMP_BLOCK_USER'			    , 121); // TEMP_BLOCK_USER
defined('PERMISSION_DENY')              or define('PERMISSION_DENY'             , 130);//權限不足
defined('FILE_IS_EMPTY')                OR define('FILE_IS_EMPTY'               , 199); // file size is zero
defined('INPUT_NOT_CORRECT')   			OR define('INPUT_NOT_CORRECT'			, 200); // input not correct.

defined('INSERT_ERROR')  				OR define('INSERT_ERROR'				, 201);
defined('NOT_VERIFIED')  				OR define('NOT_VERIFIED'				, 202); //沒通過認證
defined('NO_BANK_ACCOUNT')  			OR define('NO_BANK_ACCOUNT'				, 203); //沒綁定金融卡
defined('INVALID_EMAIL_FORMAT')  		OR define('INVALID_EMAIL_FORMAT'		, 204); //Email
defined('NOT_INVERTOR')  				OR define('NOT_INVERTOR'				, 205); //請登入出借端
defined('FACE_ERROR')  					OR define('FACE_ERROR'					, 206); //人臉辨識錯誤
defined('IS_INVERTOR')  				OR define('IS_INVERTOR'					, 207); //請登入借款端
defined('UNDER_AGE')  					OR define('UNDER_AGE'					, 208); //未滿18歲
defined('NO_TRANSACTION_PASSWORD')  	OR define('NO_TRANSACTION_PASSWORD'		, 209); //未設置交易密碼
defined('TRANSACTION_PASSWORD_ERROR')  	OR define('TRANSACTION_PASSWORD_ERROR'	, 210); //交易密碼錯誤
defined('NOT_ENOUGH_FUNDS')  			OR define('NOT_ENOUGH_FUNDS'			, 211); //可用餘額不足
defined('NOT_VERIFIED_EMAIL')  			OR define('NOT_VERIFIED_EMAIL'			, 212); //沒通過認證Email
defined('NOT_IN_CHARGE')  				OR define('NOT_IN_CHARGE'				, 213); //非公司負責人
defined('COMPANY_EXIST')  				OR define('COMPANY_EXIST'				, 214); //此公司已存在
defined('COMPANY_NOT_EXIST')  			OR define('COMPANY_NOT_EXIST'			, 215); //此公司不存在
defined('IS_COMPANY')  					OR define('IS_COMPANY'					, 216); //不支援公司帳號
defined('NOT_COMPANY')  				OR define('NOT_COMPANY'					, 217); //請登入公司帳號
defined('NOT_DEALER')                   OR define('NOT_DEALER'                  , 218); //未有該商品類型的經銷商資格

defined('TAX_ID_LENGTH_ERROR')          OR define('TAX_ID_LENGTH_ERROR'         , 219); //統一編號長度非8碼
defined('NO_CER_IDENTITY') or define('NO_CER_IDENTITY', 220); //未通過實名認證
defined('NO_CER_GOVERNMENTAUTHORITIES') or define('NO_CER_GOVERNMENTAUTHORITIES', 221); //未通過法人實名認證
defined('NO_ALLOW_CHARGE') or define('NO_ALLOW_CHARGE', 222); //不可加入負責人
defined('NOT_INCORPORATION') or define('NOT_INCORPORATION', 223); //公司不是正常設立狀態
defined('NO_RESPONSIBLE_USER_BIND') or define('NO_RESPONSIBLE_USER_BIND', 224); //法人帳號沒有綁定自然人帳號
defined('NO_RESPONSIBLE_IDENTITY') or define('NO_RESPONSIBLE_IDENTITY', 225); // 法人沒有通過負責人實名 by news
defined('PROMOTE_CODE_NOT_EXIST') or define('PROMOTE_CODE_NOT_EXIST', 226); // 該推薦碼不存在
defined('LOW_WITHDRAW_AMOUNT') or define('LOW_WITHDRAW_AMOUNT', 227); // 提領金額過低
defined('GO_GET_EMAIL_VERIFICATION') or define('GO_GET_EMAIL_VERIFICATION', 227); // 請至信箱收信驗證
defined('PROMOTE_CODE_NOT_APPLY') or define('PROMOTE_CODE_NOT_APPLY', 228); // 推薦碼尚未按下「立即申請」
defined('PROMOTE_CODE_NOT_GENERAL') or define('PROMOTE_CODE_NOT_GENERAL', 229); // 非一般經銷商
defined('PROMOTE_CODE_NOT_APPOINTED') or define('PROMOTE_CODE_NOT_APPOINTED', 230); // 非特約通路商
defined('PROMOTE_SUBCODE_NOT_EXIST') or define('PROMOTE_SUBCODE_NOT_EXIST', 231); // 該推薦碼 subcode 不存在
defined('PROMOTE_DUPLICATE_INVITE') or define('PROMOTE_DUPLICATE_INVITE', 232); // 該二級經銷商邀請中

//User Error Code
defined('USER_EXIST')  					OR define('USER_EXIST'					, 301);
defined('USER_NOT_EXIST')  				OR define('USER_NOT_EXIST'				, 302);
defined('VERIFY_CODE_ERROR')  			OR define('VERIFY_CODE_ERROR'			, 303);
defined('PASSWORD_ERROR')  				OR define('PASSWORD_ERROR'				, 304);
defined('ACCESS_TOKEN_ERROR')  			OR define('ACCESS_TOKEN_ERROR'			, 305);
defined('TYPE_WAS_BINDED')  			OR define('TYPE_WAS_BINDED'				, 306);
defined('VERIFY_CODE_BUSY')  			OR define('VERIFY_CODE_BUSY'			, 307); //SMS太頻繁
defined('FBID_EXIST')  					OR define('FBID_EXIST'					, 308);
defined('IGID_EXIST')  					OR define('IGID_EXIST'					, 309);
defined('LINEID_EXIST')  				OR define('LINEID_EXIST'				, 310);
defined('TRANSACTIONPW_LEN_ERROR')  	OR define('TRANSACTIONPW_LEN_ERROR'		, 311);
defined('PASSWORD_LENGTH_ERROR')  		OR define('PASSWORD_LENGTH_ERROR'		, 312);
defined('AGENT_EXIST')  				OR define('AGENT_EXIST'					, 313); //代理人已存在
defined('COOPERATION_EXIST')  			OR define('COOPERATION_EXIST'			, 314); //已申請過經銷商
defined('COOPERATION_NOT_EXIST')  		OR define('COOPERATION_NOT_EXIST'		, 315); //沒申請過經銷商
defined('COOPERATION_TYPE_ERROR')  		OR define('COOPERATION_TYPE_ERROR'		, 316); //經銷商類別錯誤
defined('SMS_SEND_FAIL')                OR define('SMS_SEND_FAIL'               , 317); //簡訊驗證碼SMS發送失敗
defined('RESPONSE_ERROR')                OR define('RESPONSE_ERROR'               , 319);   //第三方回應內容有誤
defined('CONNECTION_ERROR')                OR define('CONNECTION_ERROR'               , 320); //第三方連線錯誤
defined('CHARITY_INVALID_AMOUNT')       OR define('CHARITY_INVALID_AMOUNT'      , 318); //無效的慈善捐款金額
defined('CHARITY_ILLEGAL_AMOUNT')       OR define('CHARITY_ILLEGAL_AMOUNT'      , 321); // 因AMC防制法規定：捐款金額超過500,000元請洽客服。
defined('CHARITY_RECORD_NOT_FOUND')     OR define('CHARITY_RECORD_NOT_FOUND'    , 322); // 捐款紀錄不存在
defined('USER_TAX_ID_PHONE_UNMATCHED')  OR define('USER_TAX_ID_PHONE_UNMATCHED' , 323); // (法人忘記密碼)統編與電話不同
defined('USER_ID_FORMAT_ERROR')         OR define('USER_ID_FORMAT_ERROR'        , 324); // 帳號格式有誤
defined('USER_ID_EXIST')                OR define('USER_ID_EXIST'               , 325); // 帳號重複

//Product Error Code
defined('PRODUCT_NOT_EXIST')  			OR define('PRODUCT_NOT_EXIST'			, 401);
defined('PRODUCT_AMOUNT_RANGE')  		OR define('PRODUCT_AMOUNT_RANGE'		, 402);
defined('PRODUCT_INSTALMENT_ERROR')  	OR define('PRODUCT_INSTALMENT_ERROR'	, 403);
defined('APPLY_NOT_EXIST')  			OR define('APPLY_NOT_EXIST'				, 404);
defined('APPLY_NO_PERMISSION')  		OR define('APPLY_NO_PERMISSION'			, 405);
defined('APPLY_ACTION_ERROR')  			OR define('APPLY_ACTION_ERROR'			, 406);
defined('APPLY_STATUS_ERROR')  			OR define('APPLY_STATUS_ERROR'			, 407);
defined('APPLY_EXIST')  				OR define('APPLY_EXIST'					, 408);
defined('PRODUCT_REPAYMENT_ERROR')  	OR define('PRODUCT_REPAYMENT_ERROR'		, 409);
defined('PRODUCT_TYPE_ERROR')  			OR define('PRODUCT_TYPE_ERROR'			, 410);
defined('ORDER_NOT_EXIST')  			    OR define('ORDER_NOT_EXIST'				, 411);
defined('ORDER_STATUS_ERROR')  			OR define('ORDER_STATUS_ERROR'			, 412);
defined('ORDER_NO_PERMISSION')  			OR define('ORDER_NO_PERMISSION'			, 413);
defined('PRODUCT_CLOSE') OR define('PRODUCT_CLOSE' , 414);
defined('PRODUCT_RATE_ERROR') OR define('PRODUCT_RATE_ERROR' , 414);
defined('PRODUCT_STUDENT_NOT_INTELLIGENT') or define('PRODUCT_STUDENT_NOT_INTELLIGENT', 416); // 申請名校貸但身份非名校
defined('M_ORDER_NOT_EXIST')  			OR define('M_ORDER_NOT_EXIST'			, 420);
defined('M_ORDER_STATUS_ERROR')  		OR define('M_ORDER_STATUS_ERROR'		, 421);
defined('M_ORDER_ACTION_ERROR')  		OR define('M_ORDER_ACTION_ERROR'		, 422);
defined('PICTURE_NOT_EXIST')  		OR define('PICTURE_NOT_EXIST'		, 423);
defined('PRODUCT_HAS_NO_CREDIT') or define('PRODUCT_HAS_NO_CREDIT', 424); // 該產品已無額度，不起新案
defined('BLACK_LIST_APPLY_PRODUCT') OR define('BLACK_LIST_APPLY_PRODUCT', 426);
defined('PRODUCT_CANNOT_BOOK_TIME') or define('PRODUCT_CANNOT_BOOK_TIME', 427); // 無法預約時段

//Certification Error Code
defined('CERTIFICATION_NOT_ACTIVE') 	OR define('CERTIFICATION_NOT_ACTIVE'	, 501);
defined('CERTIFICATION_WAS_VERIFY') 	OR define('CERTIFICATION_WAS_VERIFY'	, 502);
defined('CERTIFICATION_NEVER_VERIFY') 	OR define('CERTIFICATION_NEVER_VERIFY'	, 503);
defined('CERTIFICATION_IDNUMBER_ERROR') OR define('CERTIFICATION_IDNUMBER_ERROR', 504);
defined('CERTIFICATION_IDNUMBER_EXIST') OR define('CERTIFICATION_IDNUMBER_EXIST', 505);
defined('CERTIFICATION_BANK_CODE_ERROR') 	OR define('CERTIFICATION_BANK_CODE_ERROR'	, 506);
defined('CERTIFICATION_BRANCH_CODE_ERROR') 	OR define('CERTIFICATION_BRANCH_CODE_ERROR'	, 507);
defined('CERTIFICATION_BANK_ACCOUNT_ERROR') OR define('CERTIFICATION_BANK_ACCOUNT_ERROR', 508);
defined('CERTIFICATION_BANK_ACCOUNT_EXIST') OR define('CERTIFICATION_BANK_ACCOUNT_EXIST', 509);
defined('CERTIFICATION_STUDENTID_EXIST') 	OR define('CERTIFICATION_STUDENTID_EXIST'	, 510);
defined('CERTIFICATION_STUDENTEMAIL_EXIST') OR define('CERTIFICATION_STUDENTEMAIL_EXIST', 511);
defined('MAIL_EXIST') OR define('MAIL_EXIST', 512);

defined('CERTIFICATION_NOT_EXIST') 	OR define('CERTIFICATION_NOT_EXIST'	, 513); // 該認證資料不存在
defined('CERTIFICATION_NO_IG_AUTH_CODE') 	OR define('CERTIFICATION_NO_IG_AUTH_CODE'	, 514); // IG 沒有給 Authorization Code
defined('CERTIFICATION_NO_IG_ACCESS_TOKEN') 	OR define('CERTIFICATION_NO_IG_ACCESS_TOKEN'	, 515); // IG 沒有給 Access Token

//Certification
defined('CERTIFICATION_IDENTITY')        OR define('CERTIFICATION_IDENTITY'            , 1);
defined('CERTIFICATION_STUDENT')       OR define('CERTIFICATION_STUDENT'           , 2);
defined('CERTIFICATION_DEBITCARD')     OR define('CERTIFICATION_DEBITCARD'         , 3);
defined('CERTIFICATION_SOCIAL')        OR define('CERTIFICATION_SOCIAL'            , 4);
defined('CERTIFICATION_EMERGENCY')     OR define('CERTIFICATION_EMERGENCY'         , 5);
defined('CERTIFICATION_EMAIL')         OR define('CERTIFICATION_EMAIL'             , 6);
defined('CERTIFICATION_FINANCIAL')     OR define('CERTIFICATION_FINANCIAL'         , 7);
defined('CERTIFICATION_DIPLOMA')       OR define('CERTIFICATION_DIPLOMA'           , 8);
defined('CERTIFICATION_INVESTIGATION') OR define('CERTIFICATION_INVESTIGATION'     , 9);
defined('CERTIFICATION_JOB')           OR define('CERTIFICATION_JOB'               , 10);
defined('CERTIFICATION_PROFILE') or define('CERTIFICATION_PROFILE', 11);
defined('CERTIFICATION_INVESTIGATIONA11') OR define('CERTIFICATION_INVESTIGATIONA11'     , 12);
defined('CERTIFICATION_FINANCIALWORKER')   OR define('CERTIFICATION_FINANCIALWORKER'       , 14);
defined('CERTIFICATION_REPAYMENT_CAPACITY') or define('CERTIFICATION_REPAYMENT_CAPACITY', 15); // 還款力
defined('CERTIFICATION_CRIMINALRECORD') OR define('CERTIFICATION_CRIMINALRECORD'     , 20);
defined('CERTIFICATION_SOCIAL_INTELLIGENT') or define('CERTIFICATION_SOCIAL_INTELLIGENT', 21); // 名校貸社交帳號
defined('CERTIFICATION_HOUSE_CONTRACT') or define('CERTIFICATION_HOUSE_CONTRACT', 22); // 購屋合約
defined('CERTIFICATION_HOUSE_RECEIPT') or define('CERTIFICATION_HOUSE_RECEIPT', 23); // 購屋發票
defined('CERTIFICATION_RENOVATION_CONTRACT') or define('CERTIFICATION_RENOVATION_CONTRACT', 24); // 裝修合約
defined('CERTIFICATION_RENOVATION_RECEIPT') or define('CERTIFICATION_RENOVATION_RECEIPT', 25); // 裝修發票
defined('CERTIFICATION_APPLIANCE_CONTRACT_RECEIPT') or define('CERTIFICATION_APPLIANCE_CONTRACT_RECEIPT', 26); // 傢俱家電合約或發票收據
defined('CERTIFICATION_HOUSE_DEED') or define('CERTIFICATION_HOUSE_DEED', 27); // 房屋所有權狀
defined('CERTIFICATION_LAND_AND_BUILDING_TRANSACTIONS') or define('CERTIFICATION_LAND_AND_BUILDING_TRANSACTIONS', 28); // 土地建物謄本
defined('CERTIFICATION_SITE_SURVEY_VIDEO') or define('CERTIFICATION_SITE_SURVEY_VIDEO', 29); // 入屋現勘/遠端視訊影片
defined('CERTIFICATION_SITE_SURVEY_BOOKING') or define('CERTIFICATION_SITE_SURVEY_BOOKING', 30); // 入屋現勘/遠端視訊預約時間

defined('CERTIFICATION_SIMPLIFICATIONFINANCIAL') or define('CERTIFICATION_SIMPLIFICATIONFINANCIAL', 500);
defined('CERTIFICATION_SIMPLIFICATIONJOB') or define('CERTIFICATION_SIMPLIFICATIONJOB', 501);
defined('CERTIFICATION_PASSBOOKCASHFLOW_2') or define('CERTIFICATION_PASSBOOKCASHFLOW_2', 502);

defined('CERTIFICATION_BUSINESSTAX') or define('CERTIFICATION_BUSINESSTAX', 1000);
defined('CERTIFICATION_BALANCESHEET') or define('CERTIFICATION_BALANCESHEET', 1001);
defined('CERTIFICATION_INCOMESTATEMENT') or define('CERTIFICATION_INCOMESTATEMENT', 1002);
defined('CERTIFICATION_INVESTIGATIONJUDICIAL') or define('CERTIFICATION_INVESTIGATIONJUDICIAL', 1003);
defined('CERTIFICATION_PASSBOOKCASHFLOW') or define('CERTIFICATION_PASSBOOKCASHFLOW', 1004);
defined('CERTIFICATION_INTERVIEW') or define('CERTIFICATION_INTERVIEW', 1005);
defined('CERTIFICATION_CERCREDITJUDICIAL') or define('CERTIFICATION_CERCREDITJUDICIAL', 1006);
defined('CERTIFICATION_GOVERNMENTAUTHORITIES') or define('CERTIFICATION_GOVERNMENTAUTHORITIES', 1007);
defined('CERTIFICATION_CHARTER') or define('CERTIFICATION_CHARTER', 1008);
defined('CERTIFICATION_REGISTEROFMEMBERS') or define('CERTIFICATION_REGISTEROFMEMBERS', 1009);
defined('CERTIFICATION_MAINPRODUCTSTATUS') or define('CERTIFICATION_MAINPRODUCTSTATUS', 1010);
defined('CERTIFICATION_STARTUPFUNDS') or define('CERTIFICATION_STARTUPFUNDS', 1011);
defined('CERTIFICATION_BUSINESS_PLAN') or define('CERTIFICATION_BUSINESS_PLAN', 1012);
defined('CERTIFICATION_VERIFICATION') or define('CERTIFICATION_VERIFICATION', 1013);
defined('CERTIFICATION_CONDENSEDBALANCESHEET') or define('CERTIFICATION_CONDENSEDBALANCESHEET', 1014);
defined('CERTIFICATION_CONDENSEDINCOMESTATEMENT') or define('CERTIFICATION_CONDENSEDINCOMESTATEMENT', 1015);
defined('CERTIFICATION_PURCHASESALESVENDORLIST') or define('CERTIFICATION_PURCHASESALESVENDORLIST', 1016);
defined('CERTIFICATION_EMPLOYEEINSURANCELIST') or define('CERTIFICATION_EMPLOYEEINSURANCELIST', 1017);
defined('CERTIFICATION_PROFILEJUDICIAL') or define('CERTIFICATION_PROFILEJUDICIAL', 1018);
defined('CERTIFICATION_COMPANYEMAIL') or define('CERTIFICATION_COMPANYEMAIL', 1019);
defined('CERTIFICATION_JUDICIALGUARANTEE') or define('CERTIFICATION_JUDICIALGUARANTEE', 1020);
defined('CERTIFICATION_PASSBOOK') or define('CERTIFICATION_PASSBOOK', 1021);
defined('CERTIFICATION_TARGET_APPLY') or define('CERTIFICATION_TARGET_APPLY', 1022); // 法人可否認購債權（僅人工）

defined('CERTIFICATION_SALESDETAIL') or define('CERTIFICATION_SALESDETAIL', 2000);

defined('CERTIFICATION_FOR_JUDICIAL') or define('CERTIFICATION_FOR_JUDICIAL', 1000); //法人認證/徵信ID範圍

// Certification Status

// 待驗證
defined('CERTIFICATION_STATUS_PENDING_TO_VALIDATE') OR define('CERTIFICATION_STATUS_PENDING_TO_VALIDATE', 0);
// 驗證成功
defined('CERTIFICATION_STATUS_SUCCEED') OR define('CERTIFICATION_STATUS_SUCCEED', 1);
// 驗證失敗
defined('CERTIFICATION_STATUS_FAILED') OR define('CERTIFICATION_STATUS_FAILED', 2);
// 待人工審核
defined('CERTIFICATION_STATUS_PENDING_TO_REVIEW') OR define('CERTIFICATION_STATUS_PENDING_TO_REVIEW', 3);
// 資料尚未填寫完成
defined('CERTIFICATION_STATUS_NOT_COMPLETED') OR define('CERTIFICATION_STATUS_NOT_COMPLETED', 4);
// 等待進行資料真實性驗證
defined('CERTIFICATION_STATUS_PENDING_TO_AUTHENTICATION') OR define('CERTIFICATION_STATUS_PENDING_TO_AUTHENTICATION', 5);
// 已驗證資料真實性待使用者送出審核 -> 送出審核後會變為待驗證
defined('CERTIFICATION_STATUS_AUTHENTICATED') OR define('CERTIFICATION_STATUS_AUTHENTICATED', 6);
// 等待配偶歸戶
defined('CERTIFICATION_STATUS_PENDING_SPOUSE_ASSOCIATE') OR define('CERTIFICATION_STATUS_PENDING_SPOUSE_ASSOCIATE', 7);

// 資料格式有誤
defined('CERTIFICATION_SUBSTATUS_WRONG_FORMAT') OR define('CERTIFICATION_SUBSTATUS_WRONG_FORMAT', 1);
// 資料核實失敗
defined('CERTIFICATION_SUBSTATUS_VERIFY_FAILED') OR define('CERTIFICATION_SUBSTATUS_VERIFY_FAILED', 2);
// 未符合授信標準
defined('CERTIFICATION_SUBSTATUS_REVIEW_FAILED') OR define('CERTIFICATION_SUBSTATUS_REVIEW_FAILED', 3);
// 資料非近一個月申請
defined('CERTIFICATION_SUBSTATUS_NOT_ONE_MONTH') OR define('CERTIFICATION_SUBSTATUS_NOT_ONE_MONTH', 4);

// 已送出審核
defined('CERTIFICATION_CERTIFICATE_STATUS_SENT') OR define('CERTIFICATION_CERTIFICATE_STATUS_SENT', 1);


// HTTP status code

// Success
defined('HTTP_STATUS_OK') 	OR define('HTTP_STATUS_OK'	, 200);
// Created
defined('HTTP_STATUS_CREATED') 	OR define('HTTP_STATUS_CREATED'	, 201);
// For custom error
defined('HTTP_STATUS_CUSTOM_ERROR') 	OR define('HTTP_STATUS_CUSTOM_ERROR'	, 587);


// 通知類型
defined('NOTIFICATION_TYPE_NONE') OR define('NOTIFICATION_TYPE_NONE', 0);
defined('NOTIFICATION_TYPE_DIALOG') OR define('NOTIFICATION_TYPE_DIALOG', 1);
defined('NOTIFICATION_TYPE_OPENURL') OR define('NOTIFICATION_TYPE_OPENURL', 2);
defined('NOTIFICATION_TYPE_GOTO_TARGET') OR define('NOTIFICATION_TYPE_GOTO_TARGET', 3);
defined('NOTIFICATION_TYPE_GOTO_CERT') OR define('NOTIFICATION_TYPE_GOTO_CERT', 4);

//Target
defined('TARGET_WAITING_APPROVE') OR define('TARGET_WAITING_APPROVE', 0);//待核可
defined('TARGET_WAITING_SIGNING') OR define('TARGET_WAITING_SIGNING', 1);//待簽約
defined('TARGET_WAITING_VERIFY') OR define('TARGET_WAITING_VERIFY', 2);//待驗證
defined('TARGET_WAITING_BIDDING') OR define('TARGET_WAITING_BIDDING', 3);//待出借
defined('TARGET_WAITING_LOAN') OR define('TARGET_WAITING_LOAN', 4);//待放款(結標)
defined('TARGET_REPAYMENTING') OR define('TARGET_REPAYMENTING', 5);//還款中
defined('TARGET_CANCEL') OR define('TARGET_CANCEL', 8);//已取消
defined('TARGET_FAIL') OR define('TARGET_FAIL', 9);//申請失敗
defined('TARGET_REPAYMENTED') OR define('TARGET_REPAYMENTED', 10);//已結案

defined('TARGET_ORDER_WAITING_QUOTE') OR define('TARGET_ORDER_WAITING_QUOTE', 20);//待報價 (分期)
defined('TARGET_ORDER_WAITING_SIGNING') OR define('TARGET_ORDER_WAITING_SIGNING', 21);//待簽約 (分期)
defined('TARGET_ORDER_WAITING_VERIFY') OR define('TARGET_ORDER_WAITING_VERIFY', 22);//待驗證 (分期)
defined('TARGET_ORDER_WAITING_SHIP') OR define('TARGET_ORDER_WAITING_SHIP', 23);//待審批 (分期)
defined('TARGET_ORDER_WAITING_VERIFY_TRANSFER') OR define('TARGET_ORDER_WAITING_VERIFY_TRANSFER', 24);//待債轉上架 (分期)

defined('TARGET_BANK_VERIFY') OR define('TARGET_BANK_VERIFY', 500);//審核中(銀行)
defined('TARGET_BANK_GUARANTEE') OR define('TARGET_BANK_GUARANTEE', 501);//對保(銀行)
defined('TARGET_BANK_LOAN') OR define('TARGET_BANK_LOAN', 504);//放款(銀行)
defined('TARGET_BANK_REPAYMENTING') OR define('TARGET_BANK_REPAYMENTING', 505);//還款中(銀行)
defined('TARGET_BANK_FAIL') OR define('TARGET_BANK_FAIL', 509);//申請失敗(銀行)
defined('TARGET_BANK_REPAYMENTED') OR define('TARGET_BANK_REPAYMENTED', 510);//已結案(銀行)

defined('TARGET_SUBSTATUS_NORNAL') OR define('TARGET_SUBSTATUS_NORNAL', 0);
defined('TARGET_SUBSTATUS_WAITING_SUBLOAN') OR define('TARGET_SUBSTATUS_WAITING_SUBLOAN', 1);
defined('TARGET_SUBSTATUS_SUBLOANED') OR define('TARGET_SUBSTATUS_SUBLOANED', 2);
defined('TARGET_SUBSTATUS_PREPAYMENT') OR define('TARGET_SUBSTATUS_PREPAYMENT', 3);
defined('TARGET_SUBSTATUS_PREPAYMENTED') OR define('TARGET_SUBSTATUS_PREPAYMENTED', 4);
defined('TARGET_SUBSTATUS_SHIPING') OR define('TARGET_SUBSTATUS_SHIPING', 5);
defined('TARGET_SUBSTATUS_FREE_RETURN') OR define('TARGET_SUBSTATUS_FREE_RETURN', 6);
defined('TARGET_SUBSTATUS_FREE_RETURNS') OR define('TARGET_SUBSTATUS_FREE_RETURNS', 7);
defined('TARGET_SUBSTATUS_SUBLOAN_TARGET') OR define('TARGET_SUBSTATUS_SUBLOAN_TARGET', 8);
defined('TARGET_SUBSTATUS_SECOND_INSTANCE') OR define('TARGET_SUBSTATUS_SECOND_INSTANCE', 9);
defined('TARGET_SUBSTATUS_SECOND_INSTANCE_TARGET') OR define('TARGET_SUBSTATUS_SECOND_INSTANCE_TARGET', 10);
defined('TARGET_SUBSTATUS_WAITING_ASSOCIATES') OR define('TARGET_SUBSTATUS_WAITING_ASSOCIATES', 11);//檢核夥伴/保證人中
defined('TARGET_SUBSTATUS_WAITING_TRANSFER_INTERNAL') OR define('TARGET_SUBSTATUS_WAITING_TRANSFER_INTERNAL', 12);//案件轉內部核可
defined('TARGET_SUBSTATUS_LAW_DEBT_COLLECTION') OR define('TARGET_SUBSTATUS_LAW_DEBT_COLLECTION', 13);//

defined('TARGET_SCRIPT_STATUS_NOT_IN_USE') or define('TARGET_SCRIPT_STATUS_NOT_IN_USE', 0);  // Not in use by any script(跑批)
defined('TARGET_SCRIPT_STATUS_APPROVE_TARGET') or define('TARGET_SCRIPT_STATUS_APPROVE_TARGET', 4);  // 正在跑批 approve_target

defined('TARGET_MSG_NOT_CREDIT_STANDARD') OR define('TARGET_MSG_NOT_CREDIT_STANDARD', '未符合信保標準');
defined('TARGET_MSG_BANK_NOT_APPROVED') OR define('TARGET_MSG_BANK_NOT_APPROVED', '銀行未核准');

// Target certificate_status
defined('TARGET_CERTIFICATE_DEFAULT') OR define('TARGET_CERTIFICATE_DEFAULT', 0); // 預設，尚未一鍵送出
defined('TARGET_CERTIFICATE_SUBMITTED') OR define('TARGET_CERTIFICATE_SUBMITTED', 1); // 已一鍵送出，信用評估中
defined('TARGET_CERTIFICATE_RE_SUBMITTING') OR define('TARGET_CERTIFICATE_RE_SUBMITTING', 2); // 已一鍵送出，徵信項審核失敗，重新提交徵信項

// Sub-loan status
defined('SUBLOAN_STATUS_CANCELED') or define('SUBLOAN_STATUS_CANCELED', 8); // 已取消
defined('SUBLOAN_STATUS_FAILED') or define('SUBLOAN_STATUS_FAILED', 9); // 申請失敗

//Investment
defined('INVESTMENT_WAITING_IMPORT') OR define('INVESTMENT_WAITING_IMPORT', 0);
defined('INVESTMENT_WAITING_BIDDING') OR define('INVESTMENT_WAITING_BIDDING', 1);
defined('INVESTMENT_WAITING_LOAN') OR define('INVESTMENT_WAITING_LOAN', 2);

//Notification Error Code
defined('NOTIFICATION_NOT_EXIST') 		OR define('NOTIFICATION_NOT_EXIST'		, 601);
//Agreement Error Code
defined('AGREEMENT_NOT_EXIST') 			OR define('AGREEMENT_NOT_EXIST'			, 701);
//Target Error Code
defined('TARGET_NOT_EXIST') 			OR define('TARGET_NOT_EXIST'			, 801);
defined('TARGET_AMOUNT_RANGE') 			OR define('TARGET_AMOUNT_RANGE'			, 802);
defined('TARGET_APPLY_EXIST')  			OR define('TARGET_APPLY_EXIST'			, 803);
defined('TARGET_SAME_USER')  			OR define('TARGET_SAME_USER'			, 804);
defined('TARGET_APPLY_NO_PERMISSION')  	OR define('TARGET_APPLY_NO_PERMISSION'	, 805);
defined('TARGET_APPLY_NOT_EXIST')  		OR define('TARGET_APPLY_NOT_EXIST'		, 806);
defined('TARGET_APPLY_STATUS_ERROR')  	OR define('TARGET_APPLY_STATUS_ERROR'	, 807);
defined('TRANSFER_EXIST') 				OR define('TRANSFER_EXIST'				, 808);
defined('TRANSFER_NOT_EXIST') 			OR define('TRANSFER_NOT_EXIST'			, 809);
defined('TRANSFER_APPLY_EXIST')  		OR define('TRANSFER_APPLY_EXIST'		, 810);
defined('BATCH_NOT_EXIST') 				OR define('BATCH_NOT_EXIST'				, 811);
defined('BATCH_NO_PERMISSION') 			OR define('BATCH_NO_PERMISSION'			, 812);
defined('TRANSFER_AMOUNT_ERROR') 		OR define('TRANSFER_AMOUNT_ERROR'		, 813);
defined('TRANSFER_COMBINE_STATUS') 		OR define('TRANSFER_COMBINE_STATUS'		, 814);
defined('TRANSFER_PASSWORD_ERROR') 		OR define('TRANSFER_PASSWORD_ERROR'		, 815);
defined('COMBINATION_NOT_EXIST') 		OR define('COMBINATION_NOT_EXIST'		, 816);
defined('TARGET_IS_BUSY') 				OR define('TARGET_IS_BUSY'				, 817);
defined('TARGET_OWNER_EXIST') or define('TARGET_OWNER_EXIST', 818);
defined('TARGET_AGITATE_EXIST') or define('TARGET_AGITATE_EXIST', 819);
//Repayment Error Code
//defined('TARGET_NOT_DELAY') 			OR define('TARGET_NOT_DELAY'			, 901);
//defined('TARGET_WAS_DELAY') 			OR define('TARGET_WAS_DELAY'			, 902);
defined('TARGET_HAD_SUBSTATUS') 		OR define('TARGET_HAD_SUBSTATUS'		, 903);
defined('TARGET_SUBLOAN_NOT_EXIST') 	OR define('TARGET_SUBLOAN_NOT_EXIST'	, 904);
defined('TARGET_IN_LEGAL_COLLECTION')  		OR define('TARGET_IN_LEGAL_COLLECTION'		, 905);

//Admin
defined('SESSION_APP_ADMIN_INFO')   OR define('SESSION_APP_ADMIN_INFO'	, getenv('ENV_SESSION_APP_ADMIN_INFO')); //Use in Admin Login
defined('URL_ADMIN')             	OR define('URL_ADMIN'				, '/admin/');
defined('COOKIES_LOGIN_ADMIN') 		OR define('COOKIES_LOGIN_ADMIN'		, getenv('ENV_COOKIES_LOGIN_ADMIN')); //Use in Admin Login Cookies
defined('COOKIE_EXPIRE')       		OR define('COOKIE_EXPIRE'			, 1800); //Use in Login Cookies
defined('SYSTEM_ADMIN_ID') or define('SYSTEM_ADMIN_ID', 0);

defined('MOBILE_PHONE_VENDOR') or define('MOBILE_PHONE_VENDOR', 0); //手機商
defined('FOREX_CAR_DEALER') or define('FOREX_CAR_DEALER', 2); //外匯車商

defined('BORROWER') or define('BORROWER', '0');
defined('INVESTOR') or define('INVESTOR', '1');

// product['type'] : 類型
defined('PRODUCT_TYPE_NORMAL') or define('PRODUCT_TYPE_NORMAL', 1);                 // 一般貸類型
defined('PRODUCT_TYPE_ORDER') or define('PRODUCT_TYPE_ORDER', 2);                   // 消費貸類型

// product['identity'] : 身份
defined('PRODUCT_IDENTITY_STUDENT') or define('PRODUCT_IDENTITY_STUDENT', 1);         // 學生
defined('PRODUCT_IDENTITY_WORKER') or define('PRODUCT_IDENTITY_WORKER', 2);           // 上班族
defined('PRODUCT_IDENTITY_CORPORATION') or define('PRODUCT_IDENTITY_CORPORATION', 3); // 法人

// product['id']
defined('PRODUCT_ID_STUDENT') or define('PRODUCT_ID_STUDENT', 1);                   // 學生貸
defined('PRODUCT_ID_STUDENT_ORDER') or define('PRODUCT_ID_STUDENT_ORDER', 2);       // 學生手機貸款
defined('PRODUCT_ID_SALARY_MAN') or define('PRODUCT_ID_SALARY_MAN', 3);             // 上班族貸
defined('PRODUCT_ID_SALARY_MAN_ORDER') or define('PRODUCT_ID_SALARY_MAN_ORDER', 4); // 上班族消費貸
defined('PRODUCT_ID_HOME_LOAN') or define('PRODUCT_ID_HOME_LOAN', 5);               // 房貸
defined('PRODUCT_FOREX_CAR_VEHICLE') or define('PRODUCT_FOREX_CAR_VEHICLE', 1000);  // 外匯車商
defined('PRODUCT_SK_MILLION_SMEG') or define('PRODUCT_SK_MILLION_SMEG', 1002);      // 普匯信保專案融資

defined('PRODUCT_FOR_JUDICIAL') or define('PRODUCT_FOR_JUDICIAL', 1000); //法人產品ID範圍
defined('PRODUCT_TAB_INDIVIDUAL') or define('PRODUCT_TAB_INDIVIDUAL', 'individual'); // 個金產品類型名稱 (除房產消費貸)
defined('PRODUCT_TAB_ENTERPRISE') or define('PRODUCT_TAB_ENTERPRISE', 'enterprise'); // 企金產品類型名稱
defined('PRODUCT_TAB_HOME_LOAN') or define('PRODUCT_TAB_HOME_LOAN', 'home_loan'); // 房產消費貸產品類型名稱

// sub-product
defined('SUB_PRODUCT_GENERAL') or define('SUB_PRODUCT_GENERAL', 0); // 一般貸款
defined('SUBPRODUCT_INTELLIGENT_STUDENT') or define('SUBPRODUCT_INTELLIGENT_STUDENT', 6); // 3S名校貸
defined('SUB_PRODUCT_ID_SALARY_MAN_CAR') or define('SUB_PRODUCT_ID_SALARY_MAN_CAR', 7); // 上班族貸(購車)
defined('SUB_PRODUCT_ID_SALARY_MAN_HOUSE') or define('SUB_PRODUCT_ID_SALARY_MAN_HOUSE', 8); // 上班族貸(購房)
defined('SUB_PRODUCT_ID_SALARY_MAN_RENOVATION') or define('SUB_PRODUCT_ID_SALARY_MAN_RENOVATION', 9); // 上班族貸(裝修)
defined('SUB_PRODUCT_ID_HOME_LOAN_SHORT') or define('SUB_PRODUCT_ID_HOME_LOAN_SHORT', 10); // 房貸(不足額)
defined('SUB_PRODUCT_ID_HOME_LOAN_RENOVATION') or define('SUB_PRODUCT_ID_HOME_LOAN_RENOVATION', 11); // 房貸(裝修)
defined('SUB_PRODUCT_ID_HOME_LOAN_APPLIANCES') or define('SUB_PRODUCT_ID_HOME_LOAN_APPLIANCES', 12); // 房貸(家電)
defined('SUB_PRODUCT_ID_SK_MILLION') or define('SUB_PRODUCT_ID_SK_MILLION', 5002); // 普匯信保專案融資(微企貸)
defined('SUB_PRODUCT_ID_CREDIT_INSURANCE') or define('SUB_PRODUCT_ID_CREDIT_INSURANCE', 5003); // 普匯信保專案融資(擴大信保)

defined('TARGET_AMOUNT_MIN')      	OR define('TARGET_AMOUNT_MIN'		, 1000); //最小投資額
defined('TARGET_AMOUNT_MAX_COMPANY') or define('TARGET_AMOUNT_MAX_COMPANY', 500000); // 法人最大投資額
defined('TARGET_AMOUNT_MAX_COMPANY_DAILY') or define('TARGET_AMOUNT_MAX_COMPANY_DAILY', 3000000); // 法人單日最大投資額
defined('INVESTOR_VIRTUAL_CODE')    OR define('INVESTOR_VIRTUAL_CODE'	, '9'); //virtual_account
defined('BORROWER_VIRTUAL_CODE')    OR define('BORROWER_VIRTUAL_CODE'	, '1'); //virtual_account
defined('LAW_VIRTUAL_CODE')    OR define('LAW_VIRTUAL_CODE'	, '8'); //virtual_account
defined('REQUEST_TOKEN_EXPIRY')    	OR define('REQUEST_TOKEN_EXPIRY'	, 21600); //request_token時效
defined('REQUEST_RETOKEN_EXPIRY')   OR define('REQUEST_RETOKEN_EXPIRY'	, 10800); //換request_token時效
defined('REPAYMENT_DAY')   			OR define('REPAYMENT_DAY'			, 10); //固定還款日，不能大於28號
defined('DEBT_TRANSFER_FEES')   	OR define('DEBT_TRANSFER_FEES'		, 0.5); //債轉手續費%
defined('SUB_LOAN_FEES')   			OR define('SUB_LOAN_FEES'			, 1); //轉貸手續費%
defined('LIQUIDATED_DAMAGES')   	OR define('LIQUIDATED_DAMAGES'		, 5); //違約金%
defined('DELAY_INTEREST')   		OR define('DELAY_INTEREST'			, 0.1); //延滯息%
defined('PLATFORM_FEES')   			OR define('PLATFORM_FEES'			, 3); //平台手續費%
defined('PLATFORM_FEES_MIN')   		OR define('PLATFORM_FEES_MIN'		, 500); //最低平台手續費
defined('REPAYMENT_PLATFORM_FEES') 	OR define('REPAYMENT_PLATFORM_FEES'	, 1); //平台手續費%
defined('ORDER_INTEREST_RATE') 		OR define('ORDER_INTEREST_RATE'		, 18); //分期利率
defined('FEV_INTEREST_RATE') 		OR define('FEV_INTEREST_RATE'		, 15); //分期利率
defined('FEV_SHARE_RATE')           OR define('FEV_SHARE_RATE' , 2); //平台分潤
defined('FEV_PREPAYMENT_SHARE_RATE') OR define('FEV_PREPAYMENT_SHARE_RATE' , 5); //提早清算平台分潤
defined('STAGE_CER_TARGET')         OR define('STAGE_CER_TARGET' , 9999);
defined('STAGE_CER_MIN_AMOUNT')     OR define('STAGE_CER_MIN_AMOUNT' , 3000);
defined('SUL_INTEREST_STARTING_RATE') 		OR define('SUL_INTEREST_STARTING_RATE' , 5); //分期利率
defined('SUL_INTEREST_ENDING_RATE') 		OR define('SUL_INTEREST_ENDING_RATE' , 20); //分期利率
defined('PREPAYMENT_ALLOWANCE_FEES') or define('PREPAYMENT_ALLOWANCE_FEES', 0); //提還補貼%
defined('GRACE_PERIOD') 					OR define('GRACE_PERIOD'					, 7); //寬限期
defined('TRANSACTION_PASSWORD_LENGTH') 		OR define('TRANSACTION_PASSWORD_LENGTH'		, 6); //交易密碼長度
defined('TRANSACTION_PASSWORD_LENGTH_MAX') 	OR define('TRANSACTION_PASSWORD_LENGTH_MAX'	, 50); //交易密碼長度
defined('PASSWORD_LENGTH') 			OR define('PASSWORD_LENGTH'					, 6); //登入密碼長度
defined('PASSWORD_LENGTH_MAX') 		OR define('PASSWORD_LENGTH_MAX'		, 50); //登入密碼長度
defined('TARGET_APPROVE_LIMIT')   	OR define('TARGET_APPROVE_LIMIT'	, 15); //申請期限
defined('TARGET_PROMOTE_LIMIT')   	OR define('TARGET_PROMOTE_LIMIT'	, 2); //邀請碼保留月數
defined('BALLOON_MORTGAGE_RULE')   	OR define('BALLOON_MORTGAGE_RULE'   , 3); //轉換產品達到次數開啟繳息不還本
defined('PREPAYMENT_RANGE_DAYS')   	OR define('PREPAYMENT_RANGE_DAYS'	, 2); //提前還款結息天數
defined('SUBLOAN_RANGE_DAYS')   	OR define('SUBLOAN_RANGE_DAYS'		, 3); //轉換產品結息天數
defined('TRANSFER_RANGE_DAYS')   	OR define('TRANSFER_RANGE_DAYS'		, 1); //債權轉讓結息天數
defined('ORDER_TRANSFER_RANGE_DAYS')OR define('ORDER_TRANSFER_RANGE_DAYS', 30); //消費貸債權轉讓期限
defined('DIPLOMA_RANGE_DAYS')   	OR define('DIPLOMA_RANGE_DAYS'		, 395); //最高學歷允許免年徵畢業時間
defined('TAX_RATE')   				OR define('TAX_RATE'				, 5); //稅率
defined('PLATFORM_VIRTUAL_ACCOUNT') OR define('PLATFORM_VIRTUAL_ACCOUNT', '56630000000000'); //
defined('PLATFORM_TAISHIN_VIRTUAL_ACCOUNT') OR define('PLATFORM_TAISHIN_VIRTUAL_ACCOUNT', '97213000000000'); //
defined('BANK_COST_ACCOUNT') 		OR define('BANK_COST_ACCOUNT'		, '00000000000000');
defined('LENDING_URL') 				OR define('LENDING_URL'				, getenv('ENV_LENDING_URL'));
defined('BORROW_URL') 				OR define('BORROW_URL'				, getenv('ENV_BORROW_URL'));
defined('CREDIT_EMAIL') 			OR define('CREDIT_EMAIL'		, getenv('ENV_CREDIT_EMAIL'));
defined('MINIMUM_WITHDRAW_AMOUNT') or define('MINIMUM_WITHDRAW_AMOUNT', 32); // 最低提領金額

//SOURCE
defined('SOURCE_RECHARGE')       	OR define('SOURCE_RECHARGE'			, '1');//代收
defined('SOURCE_WITHDRAW')       	OR define('SOURCE_WITHDRAW'			, '2');//提領
defined('SOURCE_LENDING')       	OR define('SOURCE_LENDING'			, '3');//出借款
defined('SOURCE_FEES')       		OR define('SOURCE_FEES'				, '4');//平台服務費
defined('SOURCE_SUBLOAN_FEE')      	OR define('SOURCE_SUBLOAN_FEE'		, '5');//轉換產品手續費
defined('SOURCE_TRANSFER_FEE')    	OR define('SOURCE_TRANSFER_FEE'		, '6');//債權轉讓手續費
defined('SOURCE_PREPAYMENT_ALLOWANCE') OR define('SOURCE_PREPAYMENT_ALLOWANCE'	, '7');//提還補償金
defined('SOURCE_PREPAYMENT_DAMAGE') OR define('SOURCE_PREPAYMENT_DAMAGE', '8');//提還違約金
defined('SOURCE_AR_FEES') 			OR define('SOURCE_AR_FEES'			, '9');//應收平台服務費
defined('SOURCE_TRANSFER') 			OR define('SOURCE_TRANSFER'			, '10');//債權轉讓金
defined('SOURCE_AR_PRINCIPAL')      OR define('SOURCE_AR_PRINCIPAL'		, '11');//應收借款本金
defined('SOURCE_PRINCIPAL')       	OR define('SOURCE_PRINCIPAL'		, '12');//還款本金
defined('SOURCE_AR_INTEREST')       OR define('SOURCE_AR_INTEREST'		, '13');//應收借款利息
defined('SOURCE_INTEREST')       	OR define('SOURCE_INTEREST'			, '14');//還款利息

defined('SOURCE_AR_LAW_FEE')        OR define('SOURCE_AR_LAW_FEE'       , '31');//應收法催執行費
defined('SOURCE_LAW_FEE')           OR define('SOURCE_LAW_FEE'          , '32');//法催執行費

defined('SOURCE_PROMOTE_REWARD')           OR define('SOURCE_PROMOTE_REWARD'          , '40');//推薦獎金
defined('SOURCE_CHARITY')           OR define('SOURCE_CHARITY'          , '41');//慈善捐款

defined('SOURCE_FEES_B')        	OR define('SOURCE_FEES_B'			, '50');//平台服務費沖正
defined('SOURCE_TRANSFER_FEES_B')   OR define('SOURCE_TRANSFER_FEES_B'	, '51');//債權轉讓服務費沖正
defined('SOURCE_TRANSFER_B')       	OR define('SOURCE_TRANSFER_B'		, '52');//債權轉讓費沖正
defined('SOURCE_BANK_WRONG_TX_B')   OR define('SOURCE_BANK_WRONG_TX_B'	, '53');//銀行錯帳撥還

defined('SOURCE_VERIFY_FEE')     	OR define('SOURCE_VERIFY_FEE'		, '81');//平台驗證費
defined('SOURCE_VERIFY_FEE_R')     	OR define('SOURCE_VERIFY_FEE_R'		, '82');//平台驗證費退回
defined('SOURCE_REMITTANCE_FEE')    OR define('SOURCE_REMITTANCE_FEE'	, '83');//跨行轉帳費
defined('SOURCE_REMITTANCE_FEE_R')  OR define('SOURCE_REMITTANCE_FEE_R'	, '84');//跨行轉帳費退回
defined('SOURCE_UNKNOWN_R')         OR define('SOURCE_UNKNOWN_R'        , '85');//不明原因退回

defined('SOURCE_AR_DAMAGE')       	OR define('SOURCE_AR_DAMAGE'		, '91');//應收違約金
defined('SOURCE_DAMAGE')       		OR define('SOURCE_DAMAGE'			, '92');//違約金
defined('SOURCE_AR_DELAYINTEREST')  OR define('SOURCE_AR_DELAYINTEREST'	, '93');//應收延滯息
defined('SOURCE_DELAYINTEREST')     OR define('SOURCE_DELAYINTEREST'	, '94');//延滯息


//Facebook
defined('FACEBOOK_APP_ID')       	OR define('FACEBOOK_APP_ID'			, getenv('ENV_FB_CLIENT_ID'));
defined('FACEBOOK_APP_SECRET')      OR define('FACEBOOK_APP_SECRET'		, getenv('ENV_FB_CLIENT_SECRET'));

//Instagram
defined('INSTAGRAM_CLIENT_ID')      OR define('INSTAGRAM_CLIENT_ID'		, getenv('ENV_IG_CLIENT_ID'));
defined('INSTAGRAM_CLIENT_SECRET')  OR define('INSTAGRAM_CLIENT_SECRET'	, getenv('ENV_IG_CLIENT_SECRET'));

//Line
defined('LINE_CHANNEL_ID')     	 	OR define('LINE_CHANNEL_ID'			, '1508139296');
defined('LINE_CHANNEL_SECRET')  	OR define('LINE_CHANNEL_SECRET'		, '7f57ae86e8ff067d9e11248b2a75973e');

//LineBotURL
defined('LINEBOT_URL')           	OR define('LINEBOT_URL'			    , getenv('ENV_LINEBOT_URL'));


//曠視
defined('FACEPLUSPLUS_KEY')      	OR define('FACEPLUSPLUS_KEY'		, getenv('ENV_FACEPLUSPLUS_KEY'));
defined('FACEPLUSPLUS_SECRET')      OR define('FACEPLUSPLUS_SECRET'		, getenv('ENV_FACEPLUSPLUS_SECRET'));

//新光
defined('VESTA_ENDPOINT')           OR define('VESTA_ENDPOINT'          , getenv('ENV_VESTA_ENDPOINT'));
defined('SKBANK_API_SOURCE')        OR define('SKBANK_API_SOURCE'       , getenv('ENV_SKBANK_API_SOURCE'));
defined('SKBANK_LOAN_ENDPOINT')     OR define('SKBANK_LOAN_ENDPOINT'    , getenv('ENV_SKBANK_LOAN_ENDPOINT'));

//凱基
defined('KGIBANK_LOAN_ENDPOINT')     OR define('KGIBANK_LOAN_ENDPOINT'    , getenv('ENV_KGIBANK_LOAN_ENDPOINT'));
defined('KGIBANK_LOAN_KEYID')     OR define('KGIBANK_LOAN_KEYID'    , getenv('ENV_KGIBANK_LOAN_KEYID'));

//台新
defined('TAISHIN_VIRTUAL_CODE')     OR define('TAISHIN_VIRTUAL_CODE'	, getenv('ENV_TAISHIN_VIRTUAL_CODE'));
defined('TAISHIN_CUST_ACCNO')     OR define('TAISHIN_CUST_ACCNO'	, getenv('ENV_TAISHIN_CUST_ACCNO'));

//Cathay 國泰世華
defined('CATHAY_API_URL')      		OR define('CATHAY_API_URL'			, 'https://www.globalmyb2b.com/securities/tx10d0_txt.aspx');
defined('CATHAY_AP2AP_API_URL')     OR define('CATHAY_AP2AP_API_URL'	, 'https://www.globalmyb2b.com/GEBANK/AP2AP/MyB2B_AP2AP_Rev.aspx');
defined('CATHAY_AP2APINFO_API_URL') OR define('CATHAY_AP2APINFO_API_URL', 'https://www.globalmyb2b.com/GEBANK/AP2AP/MyB2B_AP2AP_QueryRMT.aspx');
defined('CATHAY_CUST_ID')     		OR define('CATHAY_CUST_ID'			, getenv('ENV_CATHAY_CUST_ID'));
defined('CATHAY_CUST_NICKNAME')     OR define('CATHAY_CUST_NICKNAME'	, getenv('ENV_CATHAY_CUST_NICKNAME'));
defined('CATHAY_CUST_PASSWORD')    	OR define('CATHAY_CUST_PASSWORD'	, getenv('ENV_CATHAY_CUST_PASSWORD'));
defined('CATHAY_CUST_ACCNO')     	OR define('CATHAY_CUST_ACCNO'		, getenv('ENV_CATHAY_CUST_ACCNO'));
defined('CATHAY_VIRTUAL_CODE')     	OR define('CATHAY_VIRTUAL_CODE'		, getenv('ENV_CATHAY_VIRTUAL_CODE'));
defined('CATHAY_AES_KEY')     		OR define('CATHAY_AES_KEY'			, getenv('ENV_CATHAY_AES_KEY'));
defined('CATHAY_BANK_CODE')     	OR define('CATHAY_BANK_CODE'		, '013');
defined('CATHAY_BRANCH_CODE')     	OR define('CATHAY_BRANCH_CODE'		, '0154');
defined('CATHAY_BANK_NAME')     	OR define('CATHAY_BANK_NAME'		, '國泰世華商業銀行');
defined('CATHAY_BRANCH_NAME')     	OR define('CATHAY_BRANCH_NAME'		, '信義分行');
defined('CATHAY_COMPANY_NAME')     	OR define('CATHAY_COMPANY_NAME'		, '普匯金融科技股份有限公司');
defined('CATHAY_COMPANY_ACCOUNT')   OR define('CATHAY_COMPANY_ACCOUNT'	, '015035006602');

defined('TAISHIN_VIRTUAL_CODE') OR define('TAISHIN_VIRTUAL_CODE', getenv('ENV_TAISHIN_VIRTUAL_CODE'));
defined('TAISHIN_CUST_ACCNO') OR define('TAISHIN_CUST_ACCNO', getenv('ENV_TAISHIN_CUST_ACCNO'));
defined('TAISHIN_BANK_CODE') OR define('TAISHIN_BANK_CODE', '812');
defined('TAISHIN_BRANCH_CODE') OR define('TAISHIN_BRANCH_CODE', ' - ');
defined('TAISHIN_BANK_NAME') OR define('TAISHIN_BANK_NAME', '台新國際商業銀行');
defined('TAISHIN_BRANCH_NAME') OR define('TAISHIN_BRANCH_NAME', ' - ');
defined('TAISHIN_COMPANY_NAME') OR define('TAISHIN_COMPANY_NAME', '普匯金融科技股份有限公司');
defined('TAISHIN_COMPANY_ACCOUNT') OR define('TAISHIN_COMPANY_ACCOUNT', '20680100217837');


//Ezpay
defined('EZPAY_ID')     			OR define('EZPAY_ID'				, getenv('ENV_EZPAY_ID'));
defined('EZPAY_KEY')     			OR define('EZPAY_KEY'				, getenv('ENV_EZPAY_KEY'));
defined('EZPAY_IV')     			OR define('EZPAY_IV'				, getenv('ENV_EZPAY_IV'));

// Ezpay（普匯租賃）
defined('EZPAY_ID_LEASING') or define('EZPAY_ID_LEASING', getenv('ENV_EZPAY_ID_LEASING'));
defined('EZPAY_KEY_LEASING') or define('EZPAY_KEY_LEASING', getenv('ENV_EZPAY_KEY_LEASING'));
defined('EZPAY_IV_LEASING') or define('EZPAY_IV_LEASING', getenv('ENV_EZPAY_IV_LEASING'));

//OCR
defined('OCR_API_URL')      		OR define('OCR_API_URL'				,'http://52.194.4.73:8888/cxfServerX/ImgReconCard?wsdl');


//Azure
defined('AZURE_API_URL')      		OR define('AZURE_API_URL'		    ,getenv('ENV_AZURE_API_URL'));
defined('AZURE_API_KEY')      		OR define('AZURE_API_KEY'		    ,getenv('ENV_AZURE_API_KEY'));



//SMS settings (seconds)
defined('SMS_EXPIRE_TIME')      	OR define('SMS_EXPIRE_TIME'			, 1800);
defined('SMS_LIMIT_TIME')      		OR define('SMS_LIMIT_TIME'			, 180);

//EVER8D
defined('EVER8D_UID')      			OR define('EVER8D_UID'				, '0908903885');
defined('EVER8D_PWD')     			OR define('EVER8D_PWD'				, 'ATx#25B6');

//S3
defined('AWS_ACCESS_TOKEN')         	OR define('AWS_ACCESS_TOKEN'		, getenv('ENV_AWS_ACCESS_TOKEN'));
defined('AWS_SECRET_TOKEN')      	OR define('AWS_SECRET_TOKEN'		, getenv('ENV_AWS_SECRET_TOKEN'));
defined('S3_BUCKET')     			OR define('S3_BUCKET'				, getenv('ENV_S3_BUCKET'));
defined('FRONT_S3_BUCKET')     		OR define('FRONT_S3_BUCKET'			, getenv('ENV_FRONT_S3_BUCKET'));
defined('S3_SELLER_PUBLIC_BUCKET')    OR define('S3_SELLER_PUBLIC_BUCKET' , getenv('ENV_S3_SELLER_PUBLIC_BUCKET'));
defined('AZURE_S3_BUCKET')          OR define('AZURE_S3_BUCKET'         , getenv('ENV_AZURE_S3_BUCKET'));
defined('S3_BUCKET_MAILBOX')          OR define('S3_BUCKET_MAILBOX'         , getenv('ENV_S3_BUCKET_MAILBOX'));

defined('FRONT_CDN_URL')     		OR define('FRONT_CDN_URL'			, 'https://d3imllwf4as09k.cloudfront.net/');
defined('INFLUX_S3_URL')     		OR define('INFLUX_S3_URL'			, 'https://influxp2p-front-assets.s3.ap-northeast-1.amazonaws.com/');
defined('IMAGE_MAX_WIDTH')     		OR define('IMAGE_MAX_WIDTH'			, 3000);

//SMTP GMAIL
defined('GMAIL_SMTP_ACCOUNT')   	OR define('GMAIL_SMTP_ACCOUNT'		, getenv('ENV_GMAIL_SMTP_ACCOUNT'));
defined('GMAIL_SMTP_PASSWORD')   	OR define('GMAIL_SMTP_PASSWORD'		, getenv('ENV_GMAIL_SMTP_PASSWORD'));
defined('GMAIL_SMTP_NAME')   		OR define('GMAIL_SMTP_NAME'			, getenv('ENV_GMAIL_SMTP_NAME'));

//SMTP SES
defined('SES_SMTP_ACCOUNT')   		OR define('SES_SMTP_ACCOUNT'		, getenv('ENV_SES_SMTP_ACCOUNT'));
defined('SES_SMTP_PASSWORD')   		OR define('SES_SMTP_PASSWORD'		, getenv('ENV_SES_SMTP_PASSWORD'));

defined('PDF_OWNER_PASSWORD')   	OR define('PDF_OWNER_PASSWORD'		, getenv('ENV_PDF_OWNER_PASSWORD'));
//200227_[6p8u5h66e88y1]
//COOP
defined('LINK_FAIL')  	            	OR define('LINK_FAIL'		            , 500);//與主系統連線失敗

defined('ArgumentError')  	            OR define('ArgumentError'		        , 351);//參數錯誤
defined('RequiredArguments')  	        OR define('RequiredArguments'		    , 352);//參數不足
defined('AuthorizationRequired')  	    OR define('AuthorizationRequired'		, 353);//法人認證失敗
defined('IllegalIP')  	            	OR define('IllegalIP'		            , 354);//禁止訪問的IP位置
defined('TimeOut')  	            	    OR define('TimeOut'		        		, 355);//逾時
defined('OrderNotFound')  	            OR define('OrderNotFound'		        , 356);//找不到此訂單
defined('CooperationNotFound')			OR define('CooperationNotFound'		    , 357);//法人代碼不存在
defined('InsertError')  	                OR define('InsertError'		            , 358);//輸入錯誤
defined('OrderExists')  	                OR define('OrderExists'		            , 359);//重複的單號
defined('UnknownMethod')  	            OR define('UnknownMethod'		        , 360);//API ERROR
defined('ItemNotFound')  	            OR define('ItemNotFound'		        , 361);//商品不存在
defined('ApplyFail')  	                OR define('ApplyFail'		            , 362);//訂單建立失敗
defined('CooperationAccountNotFound') OR define('CooperationAccountNotFound' , 363);//法人帳號不存在
defined('SUB_SYSTEM_REQUEST_ERROR') OR define('SUB_SYSTEM_REQUEST_ERROR' , 364); // 無法訪問子系統

defined('COOPER_ID')   			OR define('COOPER_ID'				, getenv('ENV_COOPER_ID'));
defined('COOPER_KEY')   			OR define('COOPER_KEY'				, getenv('ENV_COOPER_KEY'));
defined('COOPER_TIMEOUT')   	    OR define('COOPER_TIMEOUT'			, 3600);
defined('COOPER_API_URL')        OR define('COOPER_API_URL'			, getenv('ENV_COOP_API_URL'));

//Google GCP
defined('GOOGLE_APPLICATION_CREDENTIALS')  OR define('GOOGLE_APPLICATION_CREDENTIALS', getenv('GOOGLE_APPLICATION_CREDENTIALS'));

//Redis
defined('REDIS_AGREEMENT_LIST')   	OR define('REDIS_AGREEMENT_LIST'	, 'agreement_list');
defined('REDIS_EVENT_LIST')   		OR define('REDIS_EVENT_LIST'		, 'event_list');
defined('REDIS_NEWS_LIST')   		OR define('REDIS_NEWS_LIST'			, 'news_list');

// use sql instead of CI orm ,issue#898
defined('P2P_LOAN_DB')              OR define('P2P_LOAN_DB'             , 'p2p_loan');
defined('P2P_LOAN_TARGET_TABLE')    OR define('P2P_LOAN_TARGET_TABLE'   , 'targets');

// notification
defined('ENV_NOTIFICATION_INVEST_API_KEY')  OR define('ENV_NOTIFICATION_INVEST_API_KEY' , getenv('ENV_NOTIFICATION_INVEST_API_KEY'));
defined('ENV_NOTIFICATION_ENDPOINT')  OR define('ENV_NOTIFICATION_ENDPOINT' , getenv('ENV_NOTIFICATION_ENDPOINT'));
defined('ENV_NOTIFICATION_REQUEST_URL')  OR define('ENV_NOTIFICATION_REQUEST_URL' , getenv('ENV_NOTIFICATION_ENDPOINT').'notification');

// 普匯OCR子系統
defined('INFLUX_OCR_ENDPOINT')      OR define('INFLUX_OCR_ENDPOINT'     , getenv('ENV_INFLUX_OCR_ENDPOINT'));
defined('CERT_OCR_HOME_LOAN_BOOKING_PORT')      OR define('CERT_OCR_HOME_LOAN_BOOKING_PORT'     , getenv('CERT_OCR_HOME_LOAN_BOOKING_PORT'));
defined('CERT_OCR_HOME_LOAN_PORT')      OR define('CERT_OCR_HOME_LOAN_PORT'     , getenv('CERT_OCR_HOME_LOAN_PORT'));

// legal document automatically
defined('ENV_ANUBIS_ENDPOINT')  OR define('ENV_ANUBIS_ENDPOINT' , getenv('ENV_ANUBIS_ENDPOINT'));
defined('ENV_ANUBIS_REQUEST_URL')  OR define('ENV_ANUBIS_REQUEST_URL' , getenv('ENV_ANUBIS_ENDPOINT').'/anubis/api/v1.0/');

// status of the investment
defined('INVESTMENT_STATUS_TO_BE_PAYMENT') OR define('INVESTMENT_STATUS_TO_BE_PAYMENT', 0);
defined('INVESTMENT_STATUS_TO_BE_END_OF_BIDDING') OR define('INVESTMENT_STATUS_TO_BE_END_OF_BIDDING', 1);
defined('INVESTMENT_STATUS_TO_BE_LOANED') OR define('INVESTMENT_STATUS_TO_BE_LOANED', 2);
defined('INVESTMENT_STATUS_REPAYING') OR define('INVESTMENT_STATUS_REPAYING', 3);
defined('INVESTMENT_STATUS_CANCELED') OR define('INVESTMENT_STATUS_CANCELED', 8);
defined('INVESTMENT_STATUS_FAILED') OR define('INVESTMENT_STATUS_FAILED', 9);
defined('INVESTMENT_STATUS_PAID_OFF') OR define('INVESTMENT_STATUS_PAID_OFF', 10);

// status of the transaction
defined('TRANSACTION_STATUS_DELETED') OR define('TRANSACTION_STATUS_DELETED', 0);
defined('TRANSACTION_STATUS_TO_BE_PAID') OR define('TRANSACTION_STATUS_TO_BE_PAID', 1);
defined('TRANSACTION_STATUS_PAID_OFF') OR define('TRANSACTION_STATUS_PAID_OFF', 2);

// status of the transfer
defined('TRANSFER_STATUS_WAITING_BIDDING') OR define('TRANSFER_STATUS_WAITING_BIDDING', 0);
defined('TRANSFER_STATUS_WAITING_LOAN') OR define('TRANSFER_STATUS_WAITING_LOAN', 1);
defined('TRANSFER_STATUS_CANCELED') OR define('TRANSFER_STATUS_CANCELED', 8);
defined('TRANSFER_STATUS_FINISHED') OR define('TRANSFER_STATUS_FINISHED', 10);

// status of the virtual account
defined('VIRTUAL_ACCOUNT_STATUS_BLOCKED') OR define('VIRTUAL_ACCOUNT_STATUS_BLOCKED', 0);
defined('VIRTUAL_ACCOUNT_STATUS_AVAILABLE') OR define('VIRTUAL_ACCOUNT_STATUS_AVAILABLE', 1);
defined('VIRTUAL_ACCOUNT_STATUS_USING') OR define('VIRTUAL_ACCOUNT_STATUS_USING', 2);

// status of the withdraw
defined('WITHDRAW_STATUS_WAITING') or define('WITHDRAW_STATUS_WAITING', 0); // 待出款
defined('WITHDRAW_STATUS_FINISHED') or define('WITHDRAW_STATUS_FINISHED', 1); // 提領成功
defined('WITHDRAW_STATUS_PROCESSING') or define('WITHDRAW_STATUS_PROCESSING', 2); // 出款中
defined('WITHDRAW_STATUS_CANCELED') or define('WITHDRAW_STATUS_CANCELED', 3); // 取消

// borrower/investor
defined('USER_BORROWER') OR define('USER_BORROWER', 0);
defined('USER_INVESTOR') OR define('USER_INVESTOR', 1);

// promote code
// status
defined('PROMOTE_STATUS_DISABLED') OR define('PROMOTE_STATUS_DISABLED', 0); // 失效
defined('PROMOTE_STATUS_AVAILABLE') OR define('PROMOTE_STATUS_AVAILABLE', 1); // 啟用 (可提領)
defined('PROMOTE_STATUS_PENDING_TO_SENT') OR define('PROMOTE_STATUS_PENDING_TO_SENT', 2); // 待送出審核
defined('PROMOTE_STATUS_PENDING_TO_VERIFY') OR define('PROMOTE_STATUS_PENDING_TO_VERIFY', 3); // 審核中
defined('PROMOTE_STATUS_CAN_SIGN_CONTRACT') OR define('PROMOTE_STATUS_CAN_SIGN_CONTRACT', 4);

// sub_status
defined('PROMOTE_SUB_STATUS_DEFAULT') OR define('PROMOTE_SUB_STATUS_DEFAULT', 0); // 預設狀態
defined('PROMOTE_SUB_STATUS_EMAIL_SUCCESS') OR define('PROMOTE_SUB_STATUS_EMAIL_SUCCESS', 1); // 申請成功
defined('PROMOTE_SUB_STATUS_PENDING_TO_VERIFY_EMAIL') OR define('PROMOTE_SUB_STATUS_PENDING_TO_VERIFY_EMAIL', 2); // 送出申請，待驗證

defined('PROMOTE_COLLABORATOR_DISABLED') OR define('PROMOTE_COLLABORATOR_DISABLED', 0);
defined('PROMOTE_COLLABORATOR_AVAILABLE') OR define('PROMOTE_COLLABORATOR_AVAILABLE', 1);

defined('PROMOTE_IS_NOT_SETTLEMENT') OR define('PROMOTE_IS_NOT_SETTLEMENT', 0);
defined('PROMOTE_IS_SETTLEMENT') OR define('PROMOTE_IS_SETTLEMENT', 1);

defined('PROMOTE_REWARD_STATUS_DELETED') OR define('PROMOTE_REWARD_STATUS_DELETED', 0);
defined('PROMOTE_REWARD_STATUS_TO_BE_PAID') OR define('PROMOTE_REWARD_STATUS_TO_BE_PAID', 1);
defined('PROMOTE_REWARD_STATUS_PAID_OFF') OR define('PROMOTE_REWARD_STATUS_PAID_OFF', 2);

defined('PROMOTE_GENERAL_CONTRACT_TYPE_NAME') OR define('PROMOTE_GENERAL_CONTRACT_TYPE_NAME', "qrcode_general");
defined('PROMOTE_GENERAL_V2_CONTRACT_TYPE_NAME') OR define('PROMOTE_GENERAL_V2_CONTRACT_TYPE_NAME', "qrcode_general_v2");
defined('PROMOTE_GENERAL_V2_CONTRACT_TYPE_NAME_NATURAL') OR define('PROMOTE_GENERAL_V2_CONTRACT_TYPE_NAME_NATURAL', "qrcode_general_v2_natural"); // 一般經銷商 (自然人)
defined('PROMOTE_GENERAL_V2_CONTRACT_TYPE_NAME_JUDICIAL') OR define('PROMOTE_GENERAL_V2_CONTRACT_TYPE_NAME_JUDICIAL', "qrcode_general_v2_judicial"); // 一般經銷商 (法人)
defined('PROMOTE_APPOINTED_CONTRACT_TYPE_NAME') OR define('PROMOTE_APPOINTED_CONTRACT_TYPE_NAME', "qrcode_appointed");
defined('PROMOTE_APPOINTED_V2_CONTRACT_TYPE_NAME_NATURAL') OR define('PROMOTE_APPOINTED_V2_CONTRACT_TYPE_NAME_NATURAL', "qrcode_appointed_v2_natural"); // 特約通路商 (自然人)
defined('PROMOTE_APPOINTED_V2_CONTRACT_TYPE_NAME_JUDICIAL') OR define('PROMOTE_APPOINTED_V2_CONTRACT_TYPE_NAME_JUDICIAL', "qrcode_appointed_v2_judicial"); // 特約通路商 (法人)

defined('PROMOTE_GENERAL_FULL_V1_CONTRACT_TYPE_NAME') OR define('PROMOTE_GENERAL_FULL_V1_CONTRACT_TYPE_NAME', "qrcode_general_full");
defined('PROMOTE_GENERAL_AMT_V1_CONTRACT_TYPE_NAME') OR define('PROMOTE_GENERAL_AMT_V1_CONTRACT_TYPE_NAME', "qrcode_general_amount");
defined('PROMOTE_GENERAL_PERCT_V1_CONTRACT_TYPE_NAME') OR define('PROMOTE_GENERAL_PERCT_V1_CONTRACT_TYPE_NAME', "qrcode_general_percent");
defined('PROMOTE_GENERAL_FULL_AMT_V1_CONTRACT_TYPE_NAME') OR define('PROMOTE_GENERAL_FULL_AMT_V1_CONTRACT_TYPE_NAME', "qrcode_general_full_amount");
defined('PROMOTE_GENERAL_FULL_PERCT_V1_CONTRACT_TYPE_NAME') OR define('PROMOTE_GENERAL_FULL_PERCT_V1_CONTRACT_TYPE_NAME', "qrcode_general_full_percent");
defined('PROMOTE_APPOINTED_AMT_CONTRACT_TYPE_NAME') OR define('PROMOTE_APPOINTED_AMT_CONTRACT_TYPE_NAME', "qrcode_appointed_amount");
defined('PROMOTE_APPOINTED_PERCT_CONTRACT_TYPE_NAME') OR define('PROMOTE_APPOINTED_PERCT_CONTRACT_TYPE_NAME', "qrcode_appointed_percent");
defined('PROMOTE_APPOINTED_FULL_AMT_CONTRACT_TYPE_NAME') OR define('PROMOTE_APPOINTED_FULL_AMT_CONTRACT_TYPE_NAME', "qrcode_appointed_full_amount");
defined('PROMOTE_APPOINTED_FULL_PERCT_CONTRACT_TYPE_NAME') OR define('PROMOTE_APPOINTED_FULL_PERCT_CONTRACT_TYPE_NAME', "qrcode_appointed_full_percent");

// subcode
// status
defined('PROMOTE_SUBCODE_STATUS_DISABLED') OR define('PROMOTE_SUBCODE_STATUS_DISABLED', 0); // 失效
defined('PROMOTE_SUBCODE_STATUS_AVAILABLE') OR define('PROMOTE_SUBCODE_STATUS_AVAILABLE', 1); // 啟用
// sub_status
defined('PROMOTE_SUBCODE_SUB_STATUS_DEFAULT') OR define('PROMOTE_SUBCODE_SUB_STATUS_DEFAULT', 0); // 預設狀態
defined('PROMOTE_SUBCODE_SUB_STATUS_TEND_TO_LEAVE') OR define('PROMOTE_SUBCODE_SUB_STATUS_TEND_TO_LEAVE', 1); // 二級經銷商申請退出 (待特約通路商同意)
defined('PROMOTE_SUBCODE_SUB_STATUS_TEND_TO_ADD') OR define('PROMOTE_SUBCODE_SUB_STATUS_TEND_TO_ADD', 2); // 特約通路商加入二級經銷商 (待其同意成為二級經銷商)
defined('PROMOTE_SUBCODE_SUB_STATUS_TEND_TO_READ') OR define('PROMOTE_SUBCODE_SUB_STATUS_TEND_TO_READ', 3); // 特約通路商刪除二級經銷商，待二級經銷商閱讀 (即便二級經銷商未閱讀，刪除關係依然生效)
defined('PROMOTE_SUBCODE_SUB_STATUS_TEND_TO_REJECT') OR define('PROMOTE_SUBCODE_SUB_STATUS_TEND_TO_REJECT', 4); // 受邀方拒絕特約通路商成為二級經銷商，待特約經銷商閱讀 (即便特約經銷商未閱讀，拒絕關係依然生效)')

defined('CHARITY_RECEIPT_TYPE_SINGLE_PAPER') OR define('CHARITY_RECEIPT_TYPE_SINGLE_PAPER', 0);

defined('REALNAME_IMAGE_TYPE_FRONT') OR define('REALNAME_IMAGE_TYPE_FRONT', 1);
defined('REALNAME_IMAGE_TYPE_BACK') OR define('REALNAME_IMAGE_TYPE_BACK', 2);
defined('REALNAME_IMAGE_TYPE_PERSON') OR define('REALNAME_IMAGE_TYPE_PERSON', 3);
defined('REALNAME_IMAGE_TYPE_HEALTH') OR define('REALNAME_IMAGE_TYPE_HEALTH', 4);

// users table company status
defined('USER_NOT_COMPANY') OR define('USER_NOT_COMPANY', 0);
defined('USER_IS_COMPANY') OR define('USER_IS_COMPANY', 1);

// judicial_person table status
defined('JUDICIAL_PERSON_STATUS_REVIEW') OR define('JUDICIAL_PERSON_STATUS_REVIEW', 0);
defined('JUDICIAL_PERSON_STATUS_SUCCESS') OR define('JUDICIAL_PERSON_STATUS_SUCCESS', 1);
defined('JUDICIAL_PERSON_STATUS_FAIL') OR define('JUDICIAL_PERSON_STATUS_FAIL', 2);
defined('JUDICIAL_PERSON_STATUS_PENDING') OR define('JUDICIAL_PERSON_STATUS_PENDING', 3);

// system check
defined('SYSTEM_CHECK') OR define('SYSTEM_CHECK', 1);

// 子系統回應
defined('SCRAPER_STATUS_SUCCESS') OR define('SCRAPER_STATUS_SUCCESS', 200);
defined('SCRAPER_STATUS_CREATED') OR define('SCRAPER_STATUS_CREATED', 201);
defined('SCRAPER_STATUS_NO_CONTENT') OR define('SCRAPER_STATUS_NO_CONTENT', 204);

// SIP爬蟲學校狀態
defined('SCRAPER_SIP_RECAPTCHA') OR define('SCRAPER_SIP_RECAPTCHA', 0);
defined('SCRAPER_SIP_NORMALLY') OR define('SCRAPER_SIP_NORMALLY', 1);
defined('SCRAPER_SIP_BLOCK') OR define('SCRAPER_SIP_BLOCK', 2);
defined('SCRAPER_SIP_SERVER_ERROR') OR define('SCRAPER_SIP_SERVER_ERROR', 3);
defined('SCRAPER_SIP_VPN') OR define('SCRAPER_SIP_VPN', 4);
defined('SCRAPER_SIP_CHANGE_PWD') OR define('SCRAPER_SIP_CHANGE_PWD', 5);
defined('SCRAPER_SIP_FILL_QUEST') OR define('SCRAPER_SIP_FILL_QUEST', 6);
defined('SCRAPER_SIP_UNSTABLE') OR define('SCRAPER_SIP_UNSTABLE', 7);

// ig非活躍帳號判斷數字
defined('FOLLOWER_ACTIVATE') OR define('FOLLOWER_ACTIVATE', 30);
defined('FOLLOWING_ACTIVATE') OR define('FOLLOWING_ACTIVATE', 50);

// deduct table status
defined('DEDUCT_STATUS_DEFAULT') or define('DEDUCT_STATUS_DEFAULT', 1); // 應付
defined('DEDUCT_STATUS_CONFIRM') or define('DEDUCT_STATUS_CONFIRM', 2); // 已付
defined('DEDUCT_STATUS_CANCEL') or define('DEDUCT_STATUS_CANCEL', 3);   // 註銷

// 0: 待制定合約, 1:審核成功, 2:審核失敗, 3: 已送出審核
defined('PROMOTE_REVIEW_STATUS_PENDING_TO_DRAW_UP') OR define('PROMOTE_REVIEW_STATUS_PENDING_TO_DRAW_UP', 0);
defined('PROMOTE_REVIEW_STATUS_SUCCESS') OR define('PROMOTE_REVIEW_STATUS_SUCCESS', 1);
defined('PROMOTE_REVIEW_STATUS_WITHDRAW') OR define('PROMOTE_REVIEW_STATUS_WITHDRAW', 2);
defined('PROMOTE_REVIEW_STATUS_PENDING_TO_REVIEW') OR define('PROMOTE_REVIEW_STATUS_PENDING_TO_REVIEW', 3);
// subcode_flag
defined('IS_NOT_PROMOTE_SUBCODE') OR define('IS_NOT_PROMOTE_SUBCODE', 0);
defined('IS_PROMOTE_SUBCODE') OR define('IS_PROMOTE_SUBCODE', 1);
// target_associates table status
defined('ASSOCIATES_STATUS_WAITTING_APPROVE') or define('ASSOCIATES_STATUS_WAITTING_APPROVE', 0); // 自然人歸案狀態待同意
defined('ASSOCIATES_STATUS_APPROVED') or define('ASSOCIATES_STATUS_APPROVED', 1); // 自然人歸案狀態已同意
defined('ASSOCIATES_STATUS_CERTIFICATION_CHECKED') or define('ASSOCIATES_STATUS_CERTIFICATION_CHECKED', 2); // 自然人歸案狀態已驗證

// target_associates table character
defined('ASSOCIATES_CHARACTER_REGISTER_OWNER') or define('ASSOCIATES_CHARACTER_REGISTER_OWNER', 0); // 自然人歸案角色登記負責人
defined('ASSOCIATES_CHARACTER_OWNER') or define('ASSOCIATES_CHARACTER_OWNER', 1); // 自然人歸案角色負責人
defined('ASSOCIATES_CHARACTER_REAL_OWNER') or define('ASSOCIATES_CHARACTER_REAL_OWNER', 2); // 自然人歸案角色實際負責人
defined('ASSOCIATES_CHARACTER_SPOUSE') or define('ASSOCIATES_CHARACTER_SPOUSE', 3); // 自然人歸案角色配偶
defined('ASSOCIATES_CHARACTER_GUARANTOR_A') or define('ASSOCIATES_CHARACTER_GUARANTOR_A', 4); // 自然人歸案角色保證人甲
defined('ASSOCIATES_CHARACTER_GUARANTOR_B') or define('ASSOCIATES_CHARACTER_GUARANTOR_B', 5); // 自然人歸案角色保證人乙

// charity_institution_model table status
defined('CHARITY_INSTITUTION_STATUS_BLOCK') or define('CHARITY_INSTITUTION_STATUS_BLOCK', 0); // 停用
defined('CHARITY_INSTITUTION_STATUS_AVAILABLE') or define('CHARITY_INSTITUTION_STATUS_AVAILABLE', 1); // 啟用
// 預設狀態代碼 (0: 停用 1: 啟用)
defined('STATUS_INACTIVE') OR define('STATUS_INACTIVE', 0);
defined('STATUS_ACTIVE')   OR define('STATUS_ACTIVE', 1);

// 黑名單檢查類型代碼 (0: 禁止申貸 1: 轉二審)
defined('CHECK_APPLY_PRODUCT') OR define('CHECK_APPLY_PRODUCT', 0);
defined('CHECK_SECOND_INSTANCE')   OR define('CHECK_SECOND_INSTANCE', 1);

// montage user status
defined('MONTAGE_USER_STATUS_EXISTS') or define('MONTAGE_USER_STATUS_EXISTS', 1); // 已上傳過
defined('MONTAGE_USER_STATUS_NO_REFERENCE') or define('MONTAGE_USER_STATUS_NO_REFERENCE', 2); // 找不到reference的圖
defined('MONTAGE_USER_STATUS_NO_USER') or define('MONTAGE_USER_STATUS_NO_USER', 3); // 找不到user的圖

// 普匯租賃 user id
defined('LEASING_USERID') or define('LEASING_USERID', 73628);

defined('COMPANY_NAME') or define('COMPANY_NAME', '普匯金融科技股份有限公司');
defined('COMPANY_ID_NUMBER') or define('COMPANY_ID_NUMBER', '68566881');
defined('COMPANY_SERVICE_EMAIL') or define('COMPANY_SERVICE_EMAIL', 'service@influxfin.com');

// 就職公司代號
defined('COMPANY_CATEGORY_NORMAL') or define('COMPANY_CATEGORY_NORMAL', 1);
defined('COMPANY_CATEGORY_FINANCIAL') or define('COMPANY_CATEGORY_FINANCIAL', 2);
defined('COMPANY_CATEGORY_GOVERNMENT') or define('COMPANY_CATEGORY_GOVERNMENT', 3);
defined('COMPANY_CATEGORY_LISTED') or define('COMPANY_CATEGORY_LISTED', 4);
defined('COMPANY_CATEGORY_NAME_NORMAL') or define('COMPANY_CATEGORY_NAME_NORMAL', '一般上班族');
defined('COMPANY_CATEGORY_NAME_FINANCIAL') or define('COMPANY_CATEGORY_NAME_FINANCIAL', '金融機構員工');
defined('COMPANY_CATEGORY_NAME_GOVERNMENT') or define('COMPANY_CATEGORY_NAME_GOVERNMENT', '公家機關員工');
defined('COMPANY_CATEGORY_NAME_LISTED') or define('COMPANY_CATEGORY_NAME_LISTED', '上市櫃企業員工');

// mata name
defined('TARGET_META_COMPANY_CATEGORY_NUMBER') or define('TARGET_META_COMPANY_CATEGORY_NUMBER', 'company_category_number'); // 就職公司代號 meta name

// target loan mapping msg_no bank
defined('MAPPING_MSG_NO_NO_BANK') or define('MAPPING_MSG_NO_NO_BANK', 0);
defined('MAPPING_MSG_NO_BANK_NUM_SKBANK') or define('MAPPING_MSG_NO_BANK_NUM_SKBANK', 1);
defined('MAPPING_MSG_NO_BANK_NUM_KGIBANK') or define('MAPPING_MSG_NO_BANK_NUM_KGIBANK', 2);

// 企業營業登記項目「前兩碼」識別之產業別
defined('INDUSTRY_CODE_MANUFACTURING') or define('INDUSTRY_CODE_MANUFACTURING', 1);
defined('INDUSTRY_CODE_MERCHANDISING_SECTOR') or define('INDUSTRY_CODE_MERCHANDISING_SECTOR', 2);
defined('INDUSTRY_CODE_SERVICE') or define('INDUSTRY_CODE_SERVICE', 3);

// 企金產品對應的送件檢核表目錄
defined('VIEW_SUB_PRODUCT_ID_SK_MILLION') or define('VIEW_SUB_PRODUCT_ID_SK_MILLION', 'sk_million');
defined('VIEW_SUB_PRODUCT_ID_CREDIT_INSURANCE') or define('VIEW_SUB_PRODUCT_ID_CREDIT_INSURANCE', 'credit_insurance');
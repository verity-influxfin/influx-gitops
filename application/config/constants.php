<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
defined('BLOCK_USER')   				    OR define('BLOCK_USER'					, 101); // block status
defined('KEY_FAIL')   			        OR define('KEY_FAIL'		        , 102); // key not exit
defined('SYSTEM_BLOCK_USER')			    OR define('SYSTEM_BLOCK_USER'	    	, 120); // SYSTEM_BLOCK_USER
defined('TEMP_BLOCK_USER')			    OR define('TEMP_BLOCK_USER'			    , 121); // TEMP_BLOCK_USER
defined('INPUT_NOT_CORRECT')   			OR define('INPUT_NOT_CORRECT'			, 200); // input not correct.
defined('INSERT_ERROR')  				OR define('INSERT_ERROR'				, 201); 
defined('NOT_VERIFIED')  				OR define('NOT_VERIFIED'				, 202); //沒通過認證
defined('NO_BANK_ACCOUNT')  			OR define('NO_BANK_ACCOUNT'				, 203); //沒綁定金融卡
defined('INVALID_EMAIL_FORMAT')  		OR define('INVALID_EMAIL_FORMAT'		, 204); //Email
defined('NOT_INVERTOR')  				OR define('NOT_INVERTOR'				, 205); //請登入出借端
defined('FACE_ERROR')  					OR define('FACE_ERROR'					, 206); //人臉辨識錯誤
defined('IS_INVERTOR')  				OR define('IS_INVERTOR'					, 207); //請登入借款端
defined('UNDER_AGE')  					OR define('UNDER_AGE'					, 208); //未滿20歲
defined('NO_TRANSACTION_PASSWORD')  	OR define('NO_TRANSACTION_PASSWORD'		, 209); //未設置交易密碼
defined('TRANSACTION_PASSWORD_ERROR')  	OR define('TRANSACTION_PASSWORD_ERROR'	, 210); //交易密碼錯誤
defined('NOT_ENOUGH_FUNDS')  			OR define('NOT_ENOUGH_FUNDS'			, 211); //可用餘額不足
defined('NOT_VERIFIED_EMAIL')  			OR define('NOT_VERIFIED_EMAIL'			, 212); //沒通過認證Email
defined('NOT_IN_CHARGE')  				OR define('NOT_IN_CHARGE'				, 213); //非公司負責人
defined('COMPANY_EXIST')  				OR define('COMPANY_EXIST'				, 214); //此公司已存在
defined('COMPANY_NOT_EXIST')  			OR define('COMPANY_NOT_EXIST'			, 215); //此公司不存在
defined('IS_COMPANY')  					OR define('IS_COMPANY'					, 216); //不支援公司帳號
defined('NOT_COMPANY')  				OR define('NOT_COMPANY'					, 217); //請登入公司帳號
defined('NOT_DEALER') OR define('NOT_DEALER' , 218); //未有該商品類型的經銷商資格
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
defined('AGENT_EXIST')  				OR define('AGENT_EXIST'					, 313);//代理人已存在
defined('COOPERATION_EXIST')  			OR define('COOPERATION_EXIST'			, 314);//已申請過經銷商
defined('COOPERATION_NOT_EXIST')  		OR define('COOPERATION_NOT_EXIST'		, 315);//沒申請過經銷商
defined('COOPERATION_TYPE_ERROR')  		OR define(''		, 316);//經銷商類別錯誤

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
defined('M_ORDER_NOT_EXIST')  			OR define('M_ORDER_NOT_EXIST'			, 420);
defined('M_ORDER_STATUS_ERROR')  		OR define('M_ORDER_STATUS_ERROR'		, 421);
defined('M_ORDER_ACTION_ERROR')  		OR define('M_ORDER_ACTION_ERROR'		, 422);

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

//Certification
defined('IDCARD')        OR define('IDCARD'            , 1);
defined('STUDENT')       OR define('STUDENT'           , 2);
defined('DEBITCARD')     OR define('DEBITCARD'         , 3);
defined('SOCIAL')        OR define('SOCIAL'            , 4);
defined('EMERGENCY')     OR define('EMERGENCY'         , 5);
defined('EMAIL')         OR define('EMAIL'             , 6);
defined('FINANCIAL')     OR define('FINANCIAL'         , 7);
defined('DIPLOMA')       OR define('DIPLOMA'           , 8);
defined('INVESTIGATION') OR define('INVESTIGATION'     , 9);
defined('JOB')           OR define('JOB'               , 10);

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
defined('TARGET_OWNER_EXIST') 			OR define('TARGET_OWNER_EXIST'			, 818);
//Repayment Error Code
//defined('TARGET_NOT_DELAY') 			OR define('TARGET_NOT_DELAY'			, 901); 
//defined('TARGET_WAS_DELAY') 			OR define('TARGET_WAS_DELAY'			, 902); 
defined('TARGET_HAD_SUBSTATUS') 		OR define('TARGET_HAD_SUBSTATUS'		, 903); 
defined('TARGET_SUBLOAN_NOT_EXIST') 	OR define('TARGET_SUBLOAN_NOT_EXIST'	, 904); 


//Admin
defined('SESSION_APP_ADMIN_INFO')   OR define('SESSION_APP_ADMIN_INFO'	, getenv('ENV_SESSION_APP_ADMIN_INFO')); //Use in Admin Login
defined('URL_ADMIN')             	OR define('URL_ADMIN'				, '/admin/'); 
defined('COOKIES_LOGIN_ADMIN') 		OR define('COOKIES_LOGIN_ADMIN'		, getenv('ENV_COOKIES_LOGIN_ADMIN')); //Use in Admin Login Cookies
defined('COOKIE_EXPIRE')       		OR define('COOKIE_EXPIRE'			, 1800); //Use in Login Cookies


defined('TARGET_AMOUNT_MIN')      	OR define('TARGET_AMOUNT_MIN'		, 1000); //最小投資額 
defined('INVESTOR_VIRTUAL_CODE')    OR define('INVESTOR_VIRTUAL_CODE'	, '9'); //virtual_account
defined('BORROWER_VIRTUAL_CODE')    OR define('BORROWER_VIRTUAL_CODE'	, '1'); //virtual_account
defined('REQUEST_TOKEN_EXPIRY')    	OR define('REQUEST_TOKEN_EXPIRY'	, 21600); //request_token時效
defined('REQUEST_RETOKEN_EXPIRY')   OR define('REQUEST_RETOKEN_EXPIRY'	, 10800); //換request_token時效
defined('REPAYMENT_DAY')   			OR define('REPAYMENT_DAY'			, 10); //固定還款日
defined('DEBT_TRANSFER_FEES')   	OR define('DEBT_TRANSFER_FEES'		, 0.5); //債轉手續費%
defined('SUB_LOAN_FEES')   			OR define('SUB_LOAN_FEES'			, 1); //轉貸手續費%
defined('LIQUIDATED_DAMAGES')   	OR define('LIQUIDATED_DAMAGES'		, 5); //違約金%
defined('DELAY_INTEREST')   		OR define('DELAY_INTEREST'			, 0.1); //延滯息%
defined('PLATFORM_FEES')   			OR define('PLATFORM_FEES'			, 3); //平台手續費%
defined('PLATFORM_FEES_MIN')   		OR define('PLATFORM_FEES_MIN'		, 500); //最低平台手續費
defined('REPAYMENT_PLATFORM_FEES') 	OR define('REPAYMENT_PLATFORM_FEES'	, 1); //平台手續費%
defined('ORDER_INTEREST_RATE') 		OR define('ORDER_INTEREST_RATE'		, 18); //分期利率
defined('FEV_INTEREST_RATE') 		OR define('FEV_INTEREST_RATE'		, 15); //分期利率
defined('FEV_SHARE_RATE') OR define('FEV_SHARE_RATE' , 2); //平台分潤
defined('FEV_PREPAYMENT_SHARE_RATE') OR define('FEV_PREPAYMENT_SHARE_RATE' , 5); //提早清算平台分潤
defined('SUL_INTEREST_STARTING_RATE') 		OR define('SUL_INTEREST_STARTING_RATE' , 5); //分期利率
defined('SUL_INTEREST_ENDING_RATE') 		OR define('SUL_INTEREST_ENDING_RATE' , 20); //分期利率
defined('PREPAYMENT_ALLOWANCE_FEES') 		OR define('PREPAYMENT_ALLOWANCE_FEES'		, 1); //提還補貼%
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
defined('BANK_COST_ACCOUNT') 		OR define('BANK_COST_ACCOUNT'		, '00000000000000');
defined('LENDING_URL') 				OR define('LENDING_URL'				, getenv('ENV_LENDING_URL'));
defined('BORROW_URL') 				OR define('BORROW_URL'				, getenv('ENV_BORROW_URL'));
defined('CREDIT_EMAIL') 			OR define('CREDIT_EMAIL'		, getenv('ENV_CREDIT_EMAIL'));

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

defined('SOURCE_FEES_B')        	OR define('SOURCE_FEES_B'			, '50');//平台服務費沖正
defined('SOURCE_TRANSFER_FEES_B')   OR define('SOURCE_TRANSFER_FEES_B'	, '51');//債權轉讓服務費沖正
defined('SOURCE_TRANSFER_B')       	OR define('SOURCE_TRANSFER_B'		, '52');//債權轉讓費沖正
defined('SOURCE_BANK_WRONG_TX_B')   OR define('SOURCE_BANK_WRONG_TX_B'	, '53');//銀行錯帳撥還

defined('SOURCE_VERIFY_FEE')     	OR define('SOURCE_VERIFY_FEE'		, '81');//平台驗證費
defined('SOURCE_VERIFY_FEE_R')     	OR define('SOURCE_VERIFY_FEE_R'		, '82');//平台驗證費退回
defined('SOURCE_REMITTANCE_FEE')    OR define('SOURCE_REMITTANCE_FEE'	, '83');//跨行轉帳費
defined('SOURCE_REMITTANCE_FEE_R')  OR define('SOURCE_REMITTANCE_FEE_R'	, '84');//跨行轉帳費退回

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
defined('FACEPLUSPLUS_KEY')      	OR define('FACEPLUSPLUS_KEY'		, 'WW6FytNauCGA4U4UZuVbT4bqYq2tNtJo');
defined('FACEPLUSPLUS_SECRET')      OR define('FACEPLUSPLUS_SECRET'		, 'dYyem8PPlwd8Ue3EdErHHdBTNFSZ2-Yh');

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

defined('TAISHIN_VIRTUAL_CODE')     	OR define('TAISHIN_VIRTUAL_CODE'		, getenv('ENV_TAISHIN_VIRTUAL_CODE'));
defined('TAISHIN_CUST_ACCNO')     	OR define('TAISHIN_CUST_ACCNO'		, getenv('ENV_TAISHIN_CUST_ACCNO'));

//Ezpay
defined('EZPAY_ID')     			OR define('EZPAY_ID'				, getenv('ENV_EZPAY_ID'));
defined('EZPAY_KEY')     			OR define('EZPAY_KEY'				, getenv('ENV_EZPAY_KEY'));
defined('EZPAY_IV')     			OR define('EZPAY_IV'				, getenv('ENV_EZPAY_IV'));

//OCR 
defined('OCR_API_URL')      		OR define('OCR_API_URL'				,'http://52.194.4.73:8888/cxfServerX/ImgReconCard?wsdl'); 


//Azure
defined('AZURE_API_URL')      		OR define('AZURE_API_URL'		    ,getenv('ENV_AZURE_API_URL'));
defined('AZURE_API_KEY')      		OR define('AZURE_API_KEY'		    ,getenv('ENV_AZURE_API_KEY'));



//SMS
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
defined('IMAGE_MAX_WIDTH')     		OR define('IMAGE_MAX_WIDTH'			, 3000);

//SMTP GMAIL
defined('GMAIL_SMTP_ACCOUNT')   	OR define('GMAIL_SMTP_ACCOUNT'		, getenv('ENV_GMAIL_SMTP_ACCOUNT'));
defined('GMAIL_SMTP_PASSWORD')   	OR define('GMAIL_SMTP_PASSWORD'		, getenv('ENV_GMAIL_SMTP_PASSWORD'));
defined('GMAIL_SMTP_NAME')   		OR define('GMAIL_SMTP_NAME'			, getenv('ENV_GMAIL_SMTP_NAME'));

//SMTP SES
defined('SES_SMTP_ACCOUNT')   		OR define('SES_SMTP_ACCOUNT'		, getenv('ENV_SES_SMTP_ACCOUNT'));
defined('SES_SMTP_PASSWORD')   		OR define('SES_SMTP_PASSWORD'		, getenv('ENV_SES_SMTP_PASSWORD'));

defined('PDF_OWNER_PASSWORD')   	OR define('PDF_OWNER_PASSWORD'		, getenv('ENV_PDF_OWNER_PASSWORD'));

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


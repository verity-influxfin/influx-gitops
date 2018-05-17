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
//Product Error Code
defined('PRODUCT_NOT_EXIST')  			OR define('PRODUCT_NOT_EXIST'			, 401);
defined('PRODUCT_AMOUNT_RANGE')  		OR define('PRODUCT_AMOUNT_RANGE'		, 402); 
defined('PRODUCT_INSTALMENT_ERROR')  	OR define('PRODUCT_INSTALMENT_ERROR'	, 403);
defined('APPLY_NOT_EXIST')  			OR define('APPLY_NOT_EXIST'				, 404);
defined('APPLY_NO_PERMISSION')  		OR define('APPLY_NO_PERMISSION'			, 405);
defined('APPLY_ACTION_ERROR')  			OR define('APPLY_ACTION_ERROR'			, 406);
defined('APPLY_STATUS_ERROR')  			OR define('APPLY_STATUS_ERROR'			, 407);
defined('APPLY_EXIST')  				OR define('APPLY_EXIST'					, 408);
//Certification Error Code
defined('CERTIFICATION_NOT_ACTIVE') 	OR define('CERTIFICATION_NOT_ACTIVE'	, 501); 
defined('CERTIFICATION_WAS_VERIFY') 	OR define('CERTIFICATION_WAS_VERIFY'	, 502); 
defined('CERTIFICATION_NEVER_VERIFY') 	OR define('CERTIFICATION_NEVER_VERIFY'	, 503); 
defined('CERTIFICATION_IDNUMBER_ERROR') OR define('CERTIFICATION_IDNUMBER_ERROR', 504); 
defined('CERTIFICATION_IDNUMBER_EXIST') OR define('CERTIFICATION_IDNUMBER_EXIST', 505); 
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

//Admin
defined('SESSION_APP_ADMIN_INFO')   OR define('SESSION_APP_ADMIN_INFO'	, 'app_admin_info'); //Use in Admin Login
defined('URL_ADMIN')             	OR define('URL_ADMIN'				, '/admin/'); 
defined('COOKIES_LOGIN_ADMIN') 		OR define('COOKIES_LOGIN_ADMIN'		, 'admin_cookie'); //Use in Admin Login Cookies
defined('COOKIE_EXPIRE')       		OR define('COOKIE_EXPIRE'			, 1800); //Use in Login Cookies


defined('TARGET_AMOUNT_MIN')      	OR define('TARGET_AMOUNT_MIN'		, 1000); //最小投資額 
defined('INVESTOR_VIRTUAL_CODE')    OR define('INVESTOR_VIRTUAL_CODE'	, '9'); //最小投資額 virtual_account
defined('REQUEST_TOKEN_EXPIRY')    	OR define('REQUEST_TOKEN_EXPIRY'	, 21600); //request_token時效
defined('REQUEST_RETOKEN_EXPIRY')   OR define('REQUEST_RETOKEN_EXPIRY'	, 10800); //換request_token時效
defined('REPAYMENT_DAY')   			OR define('REPAYMENT_DAY'			, 10); //固定還款日
defined('CLOSING_TIME')   			OR define('CLOSING_TIME'			, '12:00:00'); //關帳時間
defined('DEBT_TRANSFER_FEES')   	OR define('DEBT_TRANSFER_FEES'		, 0.5); //債轉手續費%
defined('SUB_LOAN_FEES')   			OR define('SUB_LOAN_FEES'			, 1); //轉貸手續費%
defined('LIQUIDATED_DAMAGES')   	OR define('LIQUIDATED_DAMAGES'		, 2); //違約金%
defined('PLATFORM_FEES')   			OR define('PLATFORM_FEES'			, 3); //平台手續費%
defined('PLATFORM_FEES_MIN')   		OR define('PLATFORM_FEES_MIN'		, 500); //最低平台手續費
defined('PLATFORM_VIRTUAL_ACCOUNT') OR define('PLATFORM_VIRTUAL_ACCOUNT', '56630000000000'); //
defined('LENDING_URL') 				OR define('LENDING_URL'				, 'http://dev-lend.influxfin.com'); //
defined('BORROW_URL') 				OR define('BORROW_URL'				, 'http://dev.influxfin.com'); //

//SOURCE
defined('SOURCE_RECHARGE')       	OR define('SOURCE_RECHARGE'			, '1');//儲值
defined('SOURCE_WITHDRAW')       	OR define('SOURCE_WITHDRAW'			, '2');//提領
defined('SOURCE_LENDING')       	OR define('SOURCE_LENDING'			, '3');//出借款
defined('SOURCE_FEES')       		OR define('SOURCE_FEES'				, '4');//平台服務費
defined('SOURCE_SUBLOAN_FEE')      	OR define('SOURCE_SUBLOAN_FEE'		, '5');//轉換產品手續費
defined('SOURCE_TRANSFER_FEE')    	OR define('SOURCE_TRANSFER_FEE'		, '6');//債權轉讓手續費
defined('SOURCE_PREPAYMENT_ALLOWANCE') OR define('SOURCE_PREPAYMENT_ALLOWANCE'	, '7');//提還補貼金
defined('SOURCE_AR_PRINCIPAL')      OR define('SOURCE_AR_PRINCIPAL'		, '11');//應收借款本金
defined('SOURCE_PRINCIPAL')       	OR define('SOURCE_PRINCIPAL'		, '12');//還款本金
defined('SOURCE_AR_INTEREST')       OR define('SOURCE_AR_INTEREST'		, '13');//應收借款利息
defined('SOURCE_INTEREST')       	OR define('SOURCE_INTEREST'			, '14');//還款利息
defined('SOURCE_AR_DAMAGE')       	OR define('SOURCE_AR_DAMAGE'		, '91');//應收違約金
defined('SOURCE_DAMAGE')       		OR define('SOURCE_DAMAGE'			, '92');//違約金
defined('SOURCE_AR_DELAYINTEREST')  OR define('SOURCE_AR_DELAYINTEREST'	, '93');//應收延滯息
defined('SOURCE_DELAYINTEREST')     OR define('SOURCE_DELAYINTEREST'	, '94');//延滯息

//Facebook
defined('FACEBOOK_APP_ID')       	OR define('FACEBOOK_APP_ID'			, '2066969360226590');
defined('FACEBOOK_APP_SECRET')      OR define('FACEBOOK_APP_SECRET'		, 'd8ed8469f718c53e9e93cdb6a99e1e0b');

//Instagram
defined('INSTAGRAM_CLIENT_ID')      OR define('INSTAGRAM_CLIENT_ID'		, '622ba30fa4524019a3b36fccd862b764');
defined('INSTAGRAM_CLIENT_SECRET')  OR define('INSTAGRAM_CLIENT_SECRET'	, '851c4c9b2622438cad669b0d12ce4709');

//Line
defined('LINE_CHANNEL_ID')     	 	OR define('LINE_CHANNEL_ID'			, '1508139296');
defined('LINE_CHANNEL_SECRET')  	OR define('LINE_CHANNEL_SECRET'		, '7f57ae86e8ff067d9e11248b2a75973e');

//曠視
defined('FACEPLUSPLUS_KEY')      	OR define('FACEPLUSPLUS_KEY'		, 'FOlzTTV1goCuQsaiNrRcjUONWZrSLEsf');
defined('FACEPLUSPLUS_SECRET')      OR define('FACEPLUSPLUS_SECRET'		, 'UCEPzxznd-0fRz4CQBmre0ZVTm3_Cff4');

//Cathay 國泰世華
defined('CATHAY_API_URL')      		OR define('CATHAY_API_URL'			, 'https://www.globalmyb2b.com/securities/tx10d0_txt.aspx');
defined('CATHAY_CUST_ID')     		OR define('CATHAY_CUST_ID'			, '68566881');
defined('CATHAY_CUST_NICKNAME')     OR define('CATHAY_CUST_NICKNAME'	, 'toychen');
defined('CATHAY_CUST_PASSWORD')    	OR define('CATHAY_CUST_PASSWORD'	, 'fable1234');
defined('CATHAY_CUST_ACCNO')     	OR define('CATHAY_CUST_ACCNO'		, '015035006475');
defined('CATHAY_VIRTUAL_CODE')     	OR define('CATHAY_VIRTUAL_CODE'		, '5663');
defined('CATHAY_BANK_CODE')     	OR define('CATHAY_BANK_CODE'		, '013');
defined('CATHAY_BRANCH_CODE')     	OR define('CATHAY_BRANCH_CODE'		, '0154');
defined('CATHAY_BANK_NAME')     	OR define('CATHAY_BANK_NAME'		, '國泰世華商業銀行');
defined('CATHAY_BRANCH_NAME')     	OR define('CATHAY_BRANCH_NAME'		, '信義分行');

//OCR 
defined('OCR_API_URL')      		OR define('OCR_API_URL'				, 'http://13.230.227.104:8888/cxfServerX/ImgReconCard?wsdl'); 

//SMS
defined('SMS_EXPIRE_TIME')      	OR define('SMS_EXPIRE_TIME'			, 1800); 
defined('SMS_LIMIT_TIME')      		OR define('SMS_LIMIT_TIME'			, 180);

//EVER8D
defined('EVER8D_UID')      			OR define('EVER8D_UID'				, '0977249516');
defined('EVER8D_PWD')     			OR define('EVER8D_PWD'				, 'n7xg');

//S3
defined('AWS_ACCESS_TOKEN')     	OR define('AWS_ACCESS_TOKEN'		, 'AKIAJE5RGXS7FXHKSVBA');
defined('AWS_SECRET_TOKEN')      	OR define('AWS_SECRET_TOKEN'		, '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');
defined('S3_BUCKET')     			OR define('S3_BUCKET'				, 'influxp2p-personal');
defined('IMAGE_MAX_WIDTH')     		OR define('IMAGE_MAX_WIDTH'			, 3000);

//SMTP
defined('GMAIL_SMTP_ACCOUNT')   	OR define('GMAIL_SMTP_ACCOUNT'		, 'service@influxfin.com');
defined('GMAIL_SMTP_PASSWORD')   	OR define('GMAIL_SMTP_PASSWORD'		, 'fable1234');
defined('GMAIL_SMTP_NAME')   		OR define('GMAIL_SMTP_NAME'			, '普匯金融科技');

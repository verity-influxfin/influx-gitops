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
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

//Error Code
defined('TOKEN_NOT_CORRECT')   			OR define('TOKEN_NOT_CORRECT'			, 100); // token not exit
defined('INPUT_NOT_CORRECT')   			OR define('INPUT_NOT_CORRECT'			, 200); // input not correct.
defined('INSERT_ERROR')  				OR define('INSERT_ERROR'				, 201); 
defined('NOT_VERIFIED')  				OR define('NOT_VERIFIED'				, 202); //沒通過認證
defined('NO_BANK_ACCOUNT')  			OR define('NO_BANK_ACCOUNT'				, 203); //沒綁定金融卡
defined('INVALID_EMAIL_FORMAT')  		OR define('INVALID_EMAIL_FORMAT'		, 204); //Email
defined('NOT_INVERTOR')  				OR define('NOT_INVERTOR'				, 205); //請登入投資端
defined('FACE_ERROR')  					OR define('FACE_ERROR'					, 206); //人臉辨識錯誤
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


//Admin
defined('SESSION_APP_ADMIN_INFO')   OR define('SESSION_APP_ADMIN_INFO', 'app_admin_info'); //Use in Admin Login
defined('URL_ADMIN')             	OR define('URL_ADMIN', '/admin/'); 
defined('COOKIES_LOGIN_ADMIN') 		OR define('COOKIES_LOGIN_ADMIN', 'admin_cookie'); //Use in Admin Login Cookies
defined('COOKIE_EXPIRE')       		OR define('COOKIE_EXPIRE', 1800); //Use in Login Cookies


defined('TARGET_AMOUNT_MIN')      	OR define('TARGET_AMOUNT_MIN', 1000); //最小投資額 
defined('INVESTOR_VIRTUAL_CODE')    OR define('INVESTOR_VIRTUAL_CODE', '9'); //最小投資額 virtual_account
defined('REQUEST_TOKEN_EXPIRY')    	OR define('REQUEST_TOKEN_EXPIRY', 21600); //request_token時效
defined('REQUEST_RETOKEN_EXPIRY')   OR define('REQUEST_RETOKEN_EXPIRY', 10800); //換request_token時效
defined('REPAYMENT_DAY')   			OR define('REPAYMENT_DAY', 10); //換request_token時效
defined('PLATFORM_FEES')   			OR define('PLATFORM_FEES', 3); //平台手續費%
defined('PLATFORM_FEES_MIN')   		OR define('PLATFORM_FEES_MIN', 500); //最低平台手續費


//SOURCE
defined('SOURCE_RECHARGE')       	OR define('SOURCE_RECHARGE'			, '1');//儲值
defined('SOURCE_WITHDRAW')       	OR define('SOURCE_WITHDRAW'			, '2');//提領
defined('SOURCE_LENDING')       	OR define('SOURCE_LENDING'			, '3');//出借款
defined('SOURCE_FEES')       		OR define('SOURCE_FEES'				, '4');//平台服務費
defined('SOURCE_AR_PRINCIPAL')      OR define('SOURCE_AR_PRINCIPAL'		, '11');//應付借款本金
defined('SOURCE_PRINCIPAL')       	OR define('SOURCE_PRINCIPAL'		, '12');//還款本金
defined('SOURCE_AR_INTEREST')       OR define('SOURCE_AR_INTEREST'		, '13');//應付借款利息
defined('SOURCE_INTEREST')       	OR define('SOURCE_INTEREST'			, '14');//還款利息
defined('SOURCE_AR_TRANLOAN_FEE')   OR define('SOURCE_AR_TRANLOAN_FEE'	, '21');//應付轉貸手續費
defined('SOURCE_TRANLOAN_FEE')      OR define('SOURCE_TRANLOAN_FEE'		, '22');//轉貸手續費
defined('SOURCE_AR_PREPAYMENT_FEE') OR define('SOURCE_AR_PREPAYMENT_FEE', '23');//應付提還手續費
defined('SOURCE_PREPAYMENT_FEE')    OR define('SOURCE_PREPAYMENT_FEE'	, '24');//提還手續費
defined('SOURCE_AR_WITHDRAW_FEE')   OR define('SOURCE_AR_WITHDRAW_FEE'	, '25');//應付提領手續費
defined('SOURCE_WITHDRAW_FEE')      OR define('SOURCE_WITHDRAW_FEE'		, '26');//提領手續費
defined('SOURCE_AR_DAMAGE')       	OR define('SOURCE_AR_DAMAGE'		, '96');//應付違約金
defined('SOURCE_DAMAGE')       		OR define('SOURCE_DAMAGE'			, '97');//違約金
defined('SOURCE_AR_DELAYINTEREST')  OR define('SOURCE_AR_DELAYINTEREST'	, '98');//應付延滯息
defined('SOURCE_DELAYINTEREST')     OR define('SOURCE_DELAYINTEREST'	, '99');//延滯息


//Facebook
defined('FACEBOOK_APP_ID')       	OR define('FACEBOOK_APP_ID'			, '2023445087921828');
defined('FACEBOOK_APP_SECRET')      OR define('FACEBOOK_APP_SECRET'		, '0b494064ae77ae04d9fcb343f294b98c');

//Instagram
defined('INSTAGRAM_CLIENT_ID')      OR define('INSTAGRAM_CLIENT_ID'		, '622ba30fa4524019a3b36fccd862b764');
defined('INSTAGRAM_CLIENT_SECRET')  OR define('INSTAGRAM_CLIENT_SECRET'	, '851c4c9b2622438cad669b0d12ce4709');

//Line
defined('LINE_CHANNEL_ID')     	 	OR define('LINE_CHANNEL_ID'			, '1508139296');
defined('LINE_CHANNEL_SECRET')  	OR define('LINE_CHANNEL_SECRET'		, '7f57ae86e8ff067d9e11248b2a75973e');

//曠視
defined('FACEPLUSPLUS_KEY')      	OR define('FACEPLUSPLUS_KEY', 'FOlzTTV1goCuQsaiNrRcjUONWZrSLEsf');
defined('FACEPLUSPLUS_SECRET')      OR define('FACEPLUSPLUS_SECRET', 'UCEPzxznd-0fRz4CQBmre0ZVTm3_Cff4');

//Cathay 國泰世華
defined('CATHAY_API_URL')      		OR define('CATHAY_API_URL', 'https://www.globalmyb2b.com/securities/tx10d0_txt.aspx');
defined('CATHAY_CUST_ID')     		OR define('CATHAY_CUST_ID', '68566881');
defined('CATHAY_CUST_NICKNAME')     OR define('CATHAY_CUST_NICKNAME', 'toychen');
defined('CATHAY_CUST_PASSWORD')    	OR define('CATHAY_CUST_PASSWORD', 'fable1234');
defined('CATHAY_CUST_ACCNO')     	OR define('CATHAY_CUST_ACCNO', '015035006475');
defined('CATHAY_VIRTUAL_CODE')     	OR define('CATHAY_VIRTUAL_CODE', '5663');
defined('CATHAY_BANK_CODE')     	OR define('CATHAY_BANK_CODE', '013');
defined('CATHAY_BRANCH_CODE')     	OR define('CATHAY_BRANCH_CODE', '0154');

//OCR 
defined('OCR_API_URL')      		OR define('OCR_API_URL', 'http://13.230.227.104:8888/cxfServerX/ImgReconCard?wsdl'); 

//SMS
defined('SMS_EXPIRE_TIME')      	OR define('SMS_EXPIRE_TIME', 1800); 
defined('SMS_LIMIT_TIME')      		OR define('SMS_LIMIT_TIME', 180);

//EVER8D
defined('EVER8D_UID')      			OR define('EVER8D_UID', '0977249516');
defined('EVER8D_PWD')     			OR define('EVER8D_PWD', 'n7xg');

//S3
defined('AWS_ACCESS_TOKEN')     	OR define('AWS_ACCESS_TOKEN', 'AKIAJE5RGXS7FXHKSVBA');
defined('AWS_SECRET_TOKEN')      	OR define('AWS_SECRET_TOKEN', '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');
defined('S3_BUCKET')     			OR define('S3_BUCKET', 'influxp2p-personal');
defined('IMAGE_MAX_WIDTH')     		OR define('IMAGE_MAX_WIDTH', 3000);

//SMTP
defined('GMAIL_SMTP_ACCOUNT')   	OR define('GMAIL_SMTP_ACCOUNT', 'service@influxfin.com');
defined('GMAIL_SMTP_PASSWORD')   	OR define('GMAIL_SMTP_PASSWORD', 'fable1234');
defined('GMAIL_SMTP_NAME')   		OR define('GMAIL_SMTP_NAME', '普匯金融科技');
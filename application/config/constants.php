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
//User Error Code
defined('USER_EXIST')  					OR define('USER_EXIST'					, 301); 
defined('USER_NOT_EXIST')  				OR define('USER_NOT_EXIST'				, 302); 
defined('VERIFY_CODE_ERROR')  			OR define('VERIFY_CODE_ERROR'			, 303); 
defined('PASSWORD_ERROR')  				OR define('PASSWORD_ERROR'				, 304); 
defined('ACCESS_TOKEN_ERROR')  			OR define('ACCESS_TOKEN_ERROR'			, 305); 
defined('TYPE_WAS_BINDED')  			OR define('TYPE_WAS_BINDED'				, 306); 
//Product Error Code
defined('PRODUCT_NOT_EXIST')  			OR define('PRODUCT_NOT_EXIST'			, 401);
defined('PRODUCT_AMOUNT_RANGE')  		OR define('PRODUCT_AMOUNT_RANGE'		, 402); 
defined('PRODUCT_INSTALMENT_ERROR')  	OR define('PRODUCT_INSTALMENT_ERROR'	, 403);
defined('APPLY_NOT_EXIST')  			OR define('APPLY_NOT_EXIST'				, 404);
defined('APPLY_NO_PERMISSION')  		OR define('APPLY_NO_PERMISSION'			, 405);
defined('APPLY_ACTION_ERROR')  			OR define('APPLY_ACTION_ERROR'			, 406);
defined('APPLY_STATUS_ERROR')  			OR define('APPLY_STATUS_ERROR'			, 407);
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




defined('SESSION_APP_ADMIN_INFO')   OR define('SESSION_APP_ADMIN_INFO', 'app_admin_info'); //Use in Admin Login
defined('URL_ADMIN')             	OR define('URL_ADMIN', '/admin/'); 
defined('COOKIES_LOGIN_ADMIN') 		OR define('COOKIES_LOGIN_ADMIN', 'admin_cookie'); //Use in Admin Login Cookies
defined('COOKIE_EXPIRE')       		OR define('COOKIE_EXPIRE', 1800); //Use in Login Cookies
defined('FACEBOOK_APP_ID')       	OR define('FACEBOOK_APP_ID', '999193623552932'); //Use in Login Cookies
defined('FACEBOOK_APP_SECRET')      OR define('FACEBOOK_APP_SECRET', 'ee8fef831790d13600db848492bac332'); //Use in Login Cookies
defined('FACEPLUSPLUS_KEY')      	OR define('FACEPLUSPLUS_KEY', 'UJdqWlA-ZeSGkpk6lvvTZhu2r05oFeiI'); //Face++ 
defined('FACEPLUSPLUS_SECRET')      OR define('FACEPLUSPLUS_SECRET', 'YapyNRr644ivmNi1iEba5tt9TuuMv0XJ'); //Face++ 
defined('FACEPLUSPLUS_POINTS')      OR define('FACEPLUSPLUS_POINTS', 70); //Face++ 


//Cathay 國泰世華
defined('CATHAY_API_URL')      		OR define('CATHAY_API_URL', 'https://www.globalmyb2b.com/securities/tx10d0_txt.aspx');
defined('CATHAY_CUST_ID')     		OR define('CATHAY_CUST_ID', '68566881');
defined('CATHAY_CUST_NICKNAME')     OR define('CATHAY_CUST_NICKNAME', 'toychen');
defined('CATHAY_CUST_PASSWORD')    	OR define('CATHAY_CUST_PASSWORD', 'fable1234');
defined('CATHAY_CUST_ACCNO')     	OR define('CATHAY_CUST_ACCNO', '015035006475');
defined('CATHAY_VIRTUAL_CODE')     	OR define('CATHAY_VIRTUAL_CODE', '5663');

//SMS
defined('SMS_EXPIRE_TIME')      	OR define('SMS_EXPIRE_TIME', 1800);
defined('SMS_TYPE_REGISTER')     	OR define('SMS_TYPE_REGISTER', 1);
	
//S3
defined('AWS_ACCESS_TOKEN')     	OR define('AWS_ACCESS_TOKEN', 'AKIAJE5RGXS7FXHKSVBA');
defined('AWS_SECRET_TOKEN')      	OR define('AWS_SECRET_TOKEN', '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');
defined('S3_BUCKET')     			OR define('S3_BUCKET', 'influxp2p');
defined('IMAGE_MAX_WIDTH')     		OR define('IMAGE_MAX_WIDTH', 3000);
LAMP開發環境
	php 7.2
	phpmyadmin
	mysql
	php-mbstring
 
版本控制
	git
	composer
	
Cache使用
	phpredis
	phpredisadmin
	predis

文檔使用
	node
	npm
	apidoc

OCR使用：
	Soap
	bcmath
	
php.ini
	short_open_tag = On
	extension = redis.so
	
Env:
	SetEnv CI_ENV development
	#銀行設定
    SetEnv ENV_CATHAY_CUST_ID ""
    SetEnv ENV_CATHAY_CUST_NICKNAME ""
    SetEnv ENV_CATHAY_CUST_PASSWORD ""
    SetEnv ENV_CATHAY_CUST_ACCNO ""
    SetEnv ENV_CATHAY_VIRTUAL_CODE ""
    SetEnv ENV_CATHAY_AES_KEY ""
	#AWS
    SetEnv ENV_AWS_ACCESS_TOKEN ""
    SetEnv ENV_AWS_SECRET_TOKEN ""
	SetEnv ENV_S3_BUCKET ""
	#SMTP
    SetEnv ENV_GMAIL_SMTP_ACCOUNT ""
    SetEnv ENV_GMAIL_SMTP_PASSWORD ""
	SetEnv ENV_GMAIL_SMTP_NAME "P2p"
	SetEnv ENV_SES_SMTP_ACCOUNT ""
	SetEnv ENV_SES_SMTP_PASSWORD ""
	#Database
    SetEnv ENV_DBHOST ""
    SetEnv ENV_DBUSER ""
    SetEnv ENV_DBPASS ""
	#Facebook App
    SetEnv ENV_FB_CLIENT_ID ""
    SetEnv ENV_FB_CLIENT_SECRET ""
	#instagram
    SetEnv ENV_IG_CLIENT_ID ""
    SetEnv ENV_IG_CLIENT_SECRET ""
	#Google
    SetEnv GOOGLE_APPLICATION_CREDENTIALS ""
	#URL
    SetEnv ENV_LENDING_URL ""
    SetEnv ENV_BORROW_URL ""
	SetEnv ENV_BASE_URL ""
	#Key
	SetEnv ENV_COOKIES_LOGIN_ADMIN ""
	SetEnv ENV_SESSION_APP_ADMIN_INFO ""
	SetEnv ENV_JWT_KEY ""
	SetEnv ENV_JWT_ADMIN_KEY ""
	SetEnv ENV_JWT_ADMIN_COOKIE_KEY ""
	#ezPay
	SetEnv ENV_EZPAY_ID ""
	SetEnv ENV_EZPAY_KEY ""
	SetEnv ENV_EZPAY_IV ""
	#PDF password
	SetEnv ENV_PDF_OWNER_PASSWORD ""
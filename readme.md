內容更新：

2023-07-10 api文件存取方式改成帳密需要執行的指令

# Tech Stack

- php: 7.2.24
- codeigniter: 3.1.7
- mysql: 5.7.42

## php extension

command: `php -m`

正式站，許多不符當前(2024-01)架構的設定
```
[PHP Modules]
bcmath
calendar
Core
ctype
curl
date
dom
exif
fileinfo
filter
ftp
gd
gettext
hash
iconv
imagick
imap
intl
json
libxml
mailparse
mbstring
memcache
mysqli
mysqlnd
openssl
pcntl
pcre
PDO
pdo_mysql
Phar
posix
readline
Reflection
session
shmop
SimpleXML
soap
sockets
sodium
SPL
standard
sysvmsg
sysvsem
sysvshm
tokenizer
wddx
xml
xmlreader
xmlwriter
xsl
Zend OPcache
zip
zlib

[Zend Modules]
Zend OPcache
```

database driver: mysqli

cache driver: apc, fallback to file, once used in `Page.php`

## version control

- git
- composer

## Cache使用

- predis

停用，可參考 `Predis.php`

## 文檔使用

- apidoc

url: `<base_url>/doc`

非本地環境的帳密可跟同事索取

### 添加方法

主站的API文件在 `doc/api_data.js`，php裡的comment已被棄用

## App JSON檔

- 學校科系學門列表：

    http://d3imllwf4as09k.cloudfront.net/json/school_department.json

    https://s3-ap-northeast-1.amazonaws.com/influxp2p-front-assets/json/school_department.json

- 銀行分行列表：

    http://d3imllwf4as09k.cloudfront.net/json/banks.json

    https://s3-ap-northeast-1.amazonaws.com/influxp2p-front-assets/json/banks.json

- 學校列表：

    http://d3imllwf4as09k.cloudfront.net/json/school.json
    
    https://s3-ap-northeast-1.amazonaws.com/influxp2p-front-assets/json/school.json

- 台灣地區列表：

    http://d3imllwf4as09k.cloudfront.net/json/get-citys.json

    https://s3-ap-northeast-1.amazonaws.com/influxp2p-front-assets/json/get-citys.json

- 行業列表：

    http://d3imllwf4as09k.cloudfront.net/json/industry.json

    https://s3-ap-northeast-1.amazonaws.com/influxp2p-front-assets/json/industry.json

## php.ini 設定

相容性上，有些php檔案沒有尾巴 `?>`
```
short_open_tag = On
```

apache2吃的插件
```
extension=mongodb.so
```

production機器有載入的插件
```
extension=mysqlnd.so
zend_extension=opcache.so
extension=pdo.so
extension=xml.so
extension=bcmath.so
extension=calendar.so
extension=ctype.so
extension=curl.so
extension=dom.so
extension=exif.so
extension=fileinfo.so
extension=ftp.so
extension=gd.so
extension=gettext.so
extension=iconv.so
extension=imagick.so
extension=imap.so
extension=intl.so
extension=json.so
extension=mbstring.so
extension=memcache.so
extension=mysqli.so
extension=pdo_mysql.so
extension=phar.so
extension=posix.so
extension=readline.so
extension=shmop.so
extension=simplexml.so
extension=soap.so
extension=sockets.so
extension=sysvmsg.so
extension=sysvsem.so
extension=sysvshm.so
extension=tokenizer.so
extension=wddx.so
extension=xmlreader.so
extension=xmlwriter.so
extension=xsl.so
extension=zip.so
extension=mailparse.so
```

## 環境變數：

在 `.htaccess` ，可跟同事索取


## api文件存取方式改成帳密:

1. 執行以下指令

    `bash setPwAccess.sh`

2. 輸入機器環境變數

    ```
    Which env? [d]:dev, [p]:prod
    d:測試環境，p:正式環境
    ```

# notion

- [CodeIgniter 學習資源(For Deus)](https://www.notion.so/edc16965affe42cc97ebda9ed8242932?v=c91c57e6eddf4c6e880160b3e59e6fc0&p=e23ff2555c624993b1e52b360dc480bd&pm=s)

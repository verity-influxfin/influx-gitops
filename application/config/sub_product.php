<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['config_techi']= json_decode(trim(file_get_contents('https://influxp2p-front-assets.s3-ap-northeast-1.amazonaws.com/json/config_techi.json'), "\xEF\xBB\xBF"));
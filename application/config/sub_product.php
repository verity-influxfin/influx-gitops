<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['config_techi']= json_decode(trim(file_get_contents(FRONT_CDN_URL.'json/config_techi.json'), "\xEF\xBB\xBF"));
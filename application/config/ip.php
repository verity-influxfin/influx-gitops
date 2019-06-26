<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(ENVIRONMENT=="development"){
	$config['access_ip_list']	= array(
		'114.34.172.44',
		'54.64.205.49',
	);
}else{
	$config['access_ip_list']	= array(
		'114.34.172.44',
		'13.112.224.83',
	);
} 



<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(ENVIRONMENT=="development"){
	$config['access_ip_list']	= array(
		'114.34.172.44',
		'54.64.205.49',
	);

    $config['bank_adapter_ip'] = '54.64.205.49';
}else{
	$config['access_ip_list']	= array(
		'114.34.172.44',
		'13.112.224.83',
        '52.194.4.73',
        '18.179.183.180',
	);
    // TODO: 待確認是否為私網IP, ex: 172.xx.xx.xx
    // octopoda ip
    $config['bank_adapter_ip'] = '13.112.225.36';

}

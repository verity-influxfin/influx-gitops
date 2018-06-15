<?php defined('BASEPATH') OR exit('No direct script access allowed');

//後台管理員驗證
if (!function_exists('check_admin')) {
    function check_admin() {
    	$CI =& get_instance();
        $CI->load->library('session');
    	$info 			= $CI->session->userdata(SESSION_APP_ADMIN_INFO);
		$admin_info		= AUTHORIZATION::getAdminInfoByToken($info);
    	if (empty($admin_info->id)) 
			return false;
		return $admin_info;
    }
}

//後台連結
if (!function_exists('admin_url')) {
	function admin_url($url='index') {
		return base_url(URL_ADMIN.$url);
	}
}


if (!function_exists('admin_login')) {
	function admin_login($token = "") {
		$CI =& get_instance();
        $CI->load->library('session');
    	$CI->session->set_userdata(SESSION_APP_ADMIN_INFO,$token);
		return $token;
	}
}


if (!function_exists('admin_logout')) {
	function admin_logout() {
		$CI =& get_instance();
        $CI->load->library('session');
    	$CI->session->unset_userdata(SESSION_APP_ADMIN_INFO);
	}
}
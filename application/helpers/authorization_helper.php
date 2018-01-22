<?php

class AUTHORIZATION
{
    public static function validateUserToken($token ="") {
        $CI =& get_instance();
        return JWT::decode($token, $CI->config->item('jwt_key'));
    }
	
    public static function generateUserToken($data = array()) {
        $CI =& get_instance();
        return JWT::encode($data, $CI->config->item('jwt_key'));
    }
	
    public static function getUserInfoByToken($token="") {
        $CI 	=& get_instance();
		$data 	= new stdClass;
		if(!empty($token)){
			$data 		= AUTHORIZATION::validateUserToken($token);
		}

        return $data;
    }    
	
	public static function validateAdminToken($token ="") {
        $CI =& get_instance();
        return JWT::decode($token, $CI->config->item('jwt_admin_key'));
    }
	
    public static function generateAdminToken($data = array()) {
        $CI =& get_instance();
        return JWT::encode($data, $CI->config->item('jwt_admin_key'));
    }
	
    public static function getAdminInfoByToken($token="") {
        $CI 	=& get_instance();
		$data 	= new stdClass;
		if(!empty($token)){
			$data 		= AUTHORIZATION::validateAdminToken($token);
		}
        return $data;
    }	
	
	public static function validateAdminCookieToken($token ="") {
        $CI =& get_instance();
        return JWT::decode($token, $CI->config->item('jwt_admin_cookie_key'));
    }
	
    public static function generateAdminCookieToken($data = array()) {
        $CI =& get_instance();
        return JWT::encode($data, $CI->config->item('jwt_admin_cookie_key'));
    }
	
    public static function getAdminCookieInfoByToken($token="") {
        $CI 	=& get_instance();
		$data 	= new stdClass;
		if(!empty($token)){
			$data 		= AUTHORIZATION::validateAdminCookieToken($token);
		}
        return $data;
    }
	
}
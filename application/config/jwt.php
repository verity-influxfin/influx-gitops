<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['jwt_key']				= getenv('ENV_JWT_KEY');
$config['jwt_admin_key']		= getenv('ENV_JWT_ADMIN_KEY');
$config['jwt_admin_cookie_key']	= getenv('ENV_JWT_ADMIN_COOKIE_KEY');
$config['jwt_lonemanager_key']	= getenv('ENV_JWT_LONEMANAGER_KEY');
/* End of file jwt.php */
/* Location: ./application/config/jwt.php */

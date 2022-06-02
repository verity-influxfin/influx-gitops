<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 後台管理員驗證
 *
 * @return     mixed    false|JWT object
 * 
 * @updated_at          2021-07-29
 * @updated_by          Jack
 */
if ( ! function_exists('check_admin'))
{
    function check_admin()
    {
        $CI =& get_instance();
        $CI->load->library('session');
        $admin_info = AUTHORIZATION::getAdminInfoByToken(
            $CI->session->userdata(SESSION_APP_ADMIN_INFO)
        );
        return empty($admin_info->id) ? FALSE : $admin_info;
    }
}

/**
 * 後台連結
 *
 * @return     string    後台連結網址
 * 
 * @updated_at          2021-07-29
 * @updated_by          Jack
 */
if ( ! function_exists('admin_url'))
{
    function admin_url($url = 'index')
    {
        return URL_ADMIN . $url;
    }
}

/**
 * 設置管理者 token session
 *
 * @param      string    $token    管理者 token
 * 
 * @return     string    token
 * 
 * @updated_at          2021-07-29
 * @updated_by          Jack
 */
if ( ! function_exists('admin_login'))
{
    function admin_login(string $token = '')
    {
        $CI =& get_instance();
        $CI->load->library('session');
        $CI->session->set_userdata(SESSION_APP_ADMIN_INFO, $token);
        return $token;
    }
}

/**
 * 登出管理者 (移除 session)
 *
 * @return     string    token
 * 
 * @updated_at          2021-07-29
 * @updated_by          Jack
 */
if ( ! function_exists('admin_logout'))
{
    function admin_logout()
    {
        $CI =& get_instance();
        $CI->load->library('session');
        $CI->session->unset_userdata(SESSION_APP_ADMIN_INFO);
    }
}
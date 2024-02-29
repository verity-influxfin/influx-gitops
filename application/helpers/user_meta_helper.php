<?php
function get_email_to($user_info, $investor)
{
    $CI =& get_instance();
    $CI->load->model('user/user_meta_model');
    $user_meta_info = $CI->user_meta_model->order_by('created_at', 'DESC')->get_by([
        'user_id' => $user_info->id,
        'meta_key' => ((int) $investor) === USER_INVESTOR ? 'email_investor' : 'email_borrower'
    ]);
    if ( ! empty($user_meta_info->meta_value))
    {
        return $user_meta_info->meta_value;
    }
    else
    {
        return $user_info->email;
    }
}
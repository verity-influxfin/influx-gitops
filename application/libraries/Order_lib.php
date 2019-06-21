<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Order_lib{
	
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/order_model');
		//$this->CI->load->library('Notification_lib');
    }


    //訂單
    public function order_change( $order_id, $status, $param, $user_id, $admin_id=0 ){
        if($order_id){
            $rs = $this->CI->order_model->update_by(
                [
                    'id'            => $order_id,
                    'status'        => $status,
                ],$param
            );
            $this->insert_change_log($order_id,$param['status'],$user_id,$admin_id);
            return $rs;
        }
        return false;
    }

    public function insert_change_log($order_id,$status,$user_id=0,$admin_id=0){
        if($order_id){
            $this->CI->load->model('log/Log_orderchange_model');
            $param		= [
                'order_id'		=> $order_id,
                'status'        => $status,
                'change_user'	=> $user_id,
                'change_admin'	=> $admin_id
            ];
            $rs = $this->CI->Log_orderchange_model->insert($param);
            return $rs;
        }
        return false;
    }
}

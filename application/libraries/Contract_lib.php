<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contract_lib{
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('loan/contract_model');
		$this->CI->load->model('admin/contract_format_model');
    }

	public function get_contract($contract_id){
		if($contract_id){
			$contract = $this->CI->contract_model->get($contract_id);
			if($contract){
				$format = $this->CI->contract_format_model->get($contract->format_id);
				if($format){
					$content 	= json_decode($contract->content,TRUE);
					$content 	= vsprintf($format->content,$content);
					$data = array(
						'title'		=> $format->title,
						'content' 	=> $content,
						'created_at'=> $contract->created_at,
					);
					return $data;
				}
			}
		}
		return false;
	}
	
	public function sign_contract( $type='' , $data = array() ){
		if($type && $data){
			$format = $this->CI->contract_format_model->order_by('created_at','desc')->get_by(['type'=>$type]);
			if($format){
				$param = array(
					'type'		=> $type,
					'format_id'	=> $format->id,
					'content' 	=> json_encode($data) 
				);
				return $this->CI->contract_model->insert($param);
			}
		}
		return false;
	}

	public function update_contract( $id=0 , $data = array() ){
		if($id && $data){
			$param = array(
				'content' 	=> json_encode($data) 
			);
			return $this->CI->contract_model->update($id,$param);
		}
		return false;
	}
	
	public function pretransfer_contract($content = array() ){
		if($content){
			$format = $this->CI->contract_format_model->order_by('created_at','desc')->get_by(['type'=>'transfer']);
			if($format){
				$contract 	= vsprintf($format->content,$content);
				return $contract;
			}
		}
		return false;
	}
	
}

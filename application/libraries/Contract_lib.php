<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contract_lib{
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('loan/contract_model');
    }

	public function get_contract($contract_id){
		if($contract_id){
			$contract = $this->CI->contract_model->get($contract_id);
			if($contract){
				$file 	= base_url().'assets/contracts/'.$contract->type.'.json';
				$rs 	= file_get_contents($file);
				if($rs){
					$info 		= json_decode($rs,TRUE);
					$content 	= json_decode($contract->content,TRUE);
					$content 	= vsprintf($info["content"],$content);
					$data = array(
						"title"		=> $info["title"],
						"content" 	=> $content,
						"created_at"=> $contract->created_at,
					);
					return $data;
				}
			}
		}
		return false;
	}
	
	public function sign_contract( $type="" , $data = array() ){
		if($type && $data){
			$param = array(
				"type"		=> $type,
				"content" 	=> json_encode($data) 
			);
			$id = $this->CI->contract_model->insert($param);
			if($id){
				return $id;
			}
		}
		return false;
	}
	
	public function pretransfer_contract($content = array() ){
		if($content){
			$file 	= base_url().'assets/contracts/transfer.json';
			$rs 	= file_get_contents($file);
			if($rs){
				$rs 		= json_decode($rs,TRUE);
				$contract 	= vsprintf($rs["content"],$content);
				return $contract;
			}
		}
		return false;
	}
	
}

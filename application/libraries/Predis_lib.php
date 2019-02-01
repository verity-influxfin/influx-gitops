<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Predis_lib {
	
	private $_redis;
	private $isconnect = false;
	
	function __construct()
    {
        $this->CI = &get_instance();
		$server  =  [
			'scheme' 	=> 'tcp',
			'host'      =>  'dev-redis.zxe7r8.ng.0001.apne1.cache.amazonaws.com' ,
			'port'      =>  6379
		];
		
		try{
			$this->_redis  =  new Predis\Client($server);
			$this->_redis->connect();
			$this->isconnect = $this->_redis->isConnected();
		} catch (Exception $e) {
			error_log('Redis connect error');
		}
    }
	
	public function get_agreement_list($reset = false){
		$list = [];
		if($this->isconnect && !$reset){
			$list = $this->_redis->hgetall(REDIS_AGREEMENT_LIST);
		}
		
		if($reset || empty($list)){
			$this->delect_key(REDIS_AGREEMENT_LIST);
			$this->CI->load->model('admin/agreement_model');
			$agreement_list = $this->CI->agreement_model->get_many_by(['status'=>1]);
			if(!empty($agreement_list)){
				foreach($agreement_list as $key => $value){
					$list[$value->alias] = json_encode($value);
				}
				if($this->isconnect){
					$this->_redis->hmset(REDIS_AGREEMENT_LIST,$list);
				}
			}
		}
		
		if($list){
			foreach($list as $key => $value){
				$list[$key] = json_decode($value);
			}
			return $list;
		}
		return false;
	}
	
	public function delect_key($key=''){
		if($this->isconnect && $key){
			return $this->_redis->del($key) ; 
		}
		return false;
	}
	
	function __destruct(){
		if($this->isconnect){
			$this->_redis->disconnect();
			$this->connect = false;
		}
	}
}

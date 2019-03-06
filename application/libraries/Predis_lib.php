<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Predis_lib {
	
	private $_redis;
	private $isconnect = false;
	
	function __construct()
    {
		return false;//停用
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

	public function get_event_list($reset = false){
		$list = [];
		if($this->isconnect && !$reset){
			$list = $this->_redis->hgetall(REDIS_EVENT_LIST);
		}
		
		if($reset || empty($list)){
			$this->delect_key(REDIS_EVENT_LIST);
			$this->CI->load->model('admin/article_model');
			$article_list = $this->CI->article_model->order_by('rank','desc')->get_many_by(['type'=>1,'status'=>1]);
			if(!empty($article_list)){
				foreach($article_list as $key => $value){
					$list[$key] = json_encode($value);
				}
				if($this->isconnect){
					$this->_redis->hmset(REDIS_EVENT_LIST,$list);
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
	
	public function get_news_list($reset = false){
		$list = [];
		if($this->isconnect && !$reset){
			$list = $this->_redis->hgetall(REDIS_NEWS_LIST);
		}
		
		if($reset || empty($list)){
			$this->delect_key(REDIS_NEWS_LIST);
			$this->CI->load->model('admin/article_model');
			$article_list = $this->CI->article_model->order_by('rank','desc')->get_many_by(['type'=>2,'status'=>1]);
			if(!empty($article_list)){
				foreach($article_list as $key => $value){
					$list[$key] = json_encode($value);
				}
				if($this->isconnect){
					$this->_redis->hmset(REDIS_NEWS_LIST,$list);
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

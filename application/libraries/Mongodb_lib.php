<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mongodb_lib {

	private $conn;

	function __construct() {
		$this->ci =& get_instance();
		$this->ci->load->config('mongodb');

		$host = $this->ci->config->item('host');
		$port = $this->ci->config->item('port');
		$username = $this->ci->config->item('username');
		$password = $this->ci->config->item('password');
		$authenticate = $this->ci->config->item('authenticate');

		try {
			if($authenticate) {
				$this->ci->conn = new MongoDB\Driver\Manager('mongodb://' . $username . ':' . $password . '@' . $host. ':' . $port);
			} else {
				$this->ci->conn = new MongoDB\Driver\Manager('mongodb://' . $host. ':' . $port);
			}
		} catch(MongoDB\Driver\Exception\MongoConnectionException $ex) {

		}
	}

	function getConn() {
		return $this->ci->conn;
	}

}

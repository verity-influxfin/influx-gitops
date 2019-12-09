<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mongodb_lib {

	private $conn;
	private $bulk;
	private $writeConcern;
	private $session;

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

	public function initBulkWrite()
	{
		$this->bulk = new \MongoDB\Driver\BulkWrite();
	}

	public function initWriteConcern($voteMajority = \MongoDB\Driver\WriteConcern::MAJORITY, $timeout = 100)
	{
		$this->writeConcern = new \MongoDB\Driver\WriteConcern($voteMajority, $timeout, true);
	}

	public function addToBulk($document)
	{
		return $this->bulk->insert($document);
	}

	public function bulkWrite($database, $collection)
	{
		$options = ["writeConcern" => $this->writeConcern];
		if ($this->session) {
			$options["session"] = $this->session;
		}

		try {
			$this->ci->conn->executeBulkWrite($database . '.' . $collection, $this->bulk, $options);
		} catch (Exception $e) {
			$this->abort();
		}
	}

	public function startSession()
	{
		$this->session = $this->conn->startSession();
		$this->session->startTransaction();
	}

	public function save()
	{
		try {
			$this->session->commitTransaction();
		} catch (MongoDB\Driver\Exception\RuntimeException $e) {
			$this->abort();
		} catch (MongoDB\Driver\Exception\CommandException $e) {
			$this->abort();
		}

	}

	public function abort()
	{
		if (!$this->session) {
			return;
		}
		$this->session->abortTransaction();
	}

	function getConn() {
		return $this->ci->conn;
	}

}

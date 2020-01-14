<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Author: https://www.roytuts.com
*/

class Ocr_model extends CI_model {

	private $database = 'influx';
	private $collection = 'ocr';
	private $conn;
	private $manager;

	public function __construct() {
		parent::__construct();
		$this->load->library('mongodb_lib');
		$this->conn = $this->mongodb_lib->getConn();
		$this->manager = $this->mongodb_lib;
	}

	public function save($log)
	{
		$this->manager->initBulkWrite();
		$this->manager->initWriteConcern();
		$docId = $this->manager->addToBulk($log);
		$this->manager->bulkWrite($this->database, $this->collection);
	}
}

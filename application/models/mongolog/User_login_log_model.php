<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Author: https://www.roytuts.com
*/

class User_login_log_model extends CI_model {

	private $database = 'influx_logs';
	private $collection = 'user-login-logs';
	private $conn;

	public function __construct() {
		parent::__construct();
		$this->load->library('mongodb_lib');
		$this->conn = $this->mongodb_lib->getConn();
	}

    public function findUserIdsHavingSameDeviceIdsWith($userId)
    {
        $pipeline = [
            ['$match' => ['user_id' => $userId, "client.device_id" => ['$ne' => null]]],
            [
                '$lookup' => [
                    "from" => "user-login-logs",
                    "let" => [
                        "my_device_id" => '$client.device_id',
                        "my_id" => '$user_id'
                    ],
                    "pipeline" => [
                        [
                            '$match' => [
                                '$expr' => [
                                    '$and' => [
                                        ['$eq' => ['$client.device_id', '$$my_device_id']],
                                        ['$ne' => ['$user_id', '$$my_id']],
                                    ]
                                ]
                            ]
                        ]
                    ],
                    "as" => "related-users"
                ]
            ],
            [
                '$group' => [
                    '_id' => '$user_id',
                    'users' => [
                        '$addToSet' => '$related-users.user_id'
                    ]
                ]
            ],
            [
                '$project' => [
                    '_id' => '$user_id',
                    'users' => [
                        '$reduce' => [
                            'input' => '$users',
                            'initialValue' => [],
                            'in' => ['$setUnion' => ['$$value', '$$this']]
                        ]
                    ]
                ]
            ]
        ];

        try {
            $commandInput = [
                'aggregate' => $this->collection,
                'pipeline' => $pipeline,
                'cursor' => ["batchSize" => 20]
            ];
            $command = new \MongoDB\Driver\Command($commandInput);
            $result = $this->conn->executeCommand($this->database, $command);
        } catch(MongoDB\Driver\Exception\RuntimeException $ex) {
            show_error('Error while fetching users: ' . $ex->getMessage(), 500);
        }
        return $result->toArray();
    }

    public function findUserLoginIps($userId, $timeBefore = 0)
    {
		$matchStage = ['$match' => ['user_id' => $userId]];
		if ($timeBefore > 0) {
			$matchStage['$match']['created_at'] = ['$gt' => $timeBefore];
		}
        $pipeline = [
            $matchStage,
            [
                '$group' => [
                    '_id' => '$user_id',
                    'created_ips' => [
                        '$addToSet' => '$created_ip'
                    ]
                ]
            ]
        ];

        try {
            $commandInput = [
                'aggregate' => $this->collection,
                'pipeline' => $pipeline,
                'cursor' => ["batchSize" => 20]
            ];
            $command = new \MongoDB\Driver\Command($commandInput);
            $result = $this->conn->executeCommand($this->database, $command);
        } catch(MongoDB\Driver\Exception\RuntimeException $ex) {
            show_error('Error while fetching users: ' . $ex->getMessage(), 500);
        }

        $users = $result->toArray();
        if ($users) {
            return $users[0];
        }
        return [];
    }

    public function findUserIdsByIps($ips, $timeBefore = 0)
    {
        if (!$ips) {
            return [];
        }

		$matchStage = ['$match' => ['created_ip' => ['$in' => $ips]]];
		if ($timeBefore > 0) {
			$matchStage['$match']['created_at'] = ['$gt' => $timeBefore];
		}

        $pipeline = [
            $matchStage,
            [
                '$group' => [
                    '_id' => null,
                    'users' => [
                        '$addToSet' => '$user_id'
                    ]
                ]
            ]
        ];

        try {
            $commandInput = [
                'aggregate' => $this->collection,
                'pipeline' => $pipeline,
                'cursor' => ["batchSize" => 20]
            ];
            $command = new \MongoDB\Driver\Command($commandInput);
            $result = $this->conn->executeCommand($this->database, $command);
        } catch(MongoDB\Driver\Exception\RuntimeException $ex) {
            show_error('Error while fetching users: ' . $ex->getMessage(), 500);
        }

        $users = $result->toArray();
        if ($users) {
            return $users[0];
        }
        return [];
    }
}

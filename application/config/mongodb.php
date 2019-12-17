<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['host'] = getenv('ENV_MONGO_HOST');
$config['port'] = getenv('ENV_MONGO_PORT');
$config['username'] = getenv('ENV_MONGO_USERNAME');
$config['password'] = getenv('ENV_MONGO_PASSWORD');
$config['authenticate'] = TRUE;

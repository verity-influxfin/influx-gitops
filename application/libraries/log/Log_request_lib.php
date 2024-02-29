<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Log_request_lib
{

    private $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('log/log_request_model');
    }

    /**
     * 寫log到table
     * @param Log_request_data $data
     * @return Log_request_result
     */
    public function insert(Log_request_data $data): Log_request_result
    {
        $result = $this->CI->log_request_model->insert($data->to_array());
        return new Log_request_result($result);
    }
}

/**
 * This class represents the log request information.
 * @property string $method
 * @property string $url
 */
class Log_request_info
{
    /**
     * @var string
     */
    private $method = '';
    /**
     * @var string
     */
    private $url = '';

    public function __construct(string $method, string $url)
    {
        $this->method = $method;
        $this->url = $url;
    }

    public function get_method()
    {
        if (empty($this->method)) {
            throw new Exception('Method is empty');
        }
        return $this->method;
    }

    public function get_url()
    {
        if (empty($this->url)) {
            throw new Exception('Url is empty');
        }
        return $this->url;
    }
}


/**
 * This class is responsible for logging requests.
 * @property Log_request_info $log_request_info
 * @property int $investor
 * @property int $user_id
 * @property int $agent
 * @property string $payload
 * @property string $ip
 * @property string $user_agent
 */
class Log_request_data
{
    /**
     * @var Log_request_info $log_request_info
     */
    private $log_request_info = null;
    /**
     * @var int $investor
     */
    private $investor = 0;
    /**
     * @var int $user_id
     */
    private $user_id = 0;
    /**
     * @var int $agent
     */
    private $agent = 0;
    /**
     * @var string $payload
     */
    private $payload = '';

    public function __construct(Log_request_info $log_request_info, array $payload = array())
    {
        $this->log_request_info = $log_request_info;
        if (!empty($payload)) {
            $this->set_payload($payload);
        }
    }

    public function set_investor(int $investor): void
    {
        $this->investor = $investor;
    }

    public function set_user_id(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function set_agent(int $agent): void
    {
        $this->agent = $agent;
    }

    private function set_payload(array $payload): void
    {
        $this->payload = json_encode($payload, JSON_UNESCAPED_UNICODE);
    }

    public function to_array(): array
    {
        return array(
            'method' => $this->log_request_info->get_method(),
            'url' => $this->log_request_info->get_url(),
            'investor' => $this->investor,
            'user_id' => $this->user_id,
            'agent' => $this->agent,
            'payload' => $this->payload
        );
    }
}

/**
 * This class represents the log request result.
 * @property int $id
 */
class Log_request_result
{
    /**
     * @var int $id
     */
    public $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}

<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cli_command extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ( ! is_cli())
        {
            show_404();
        }
        if ( ! app_access())
        {
            show_404();
        }
    }

    public function is_ocr_connected_success()
    {
        $this->load->library('Ocr2_lib', [
            'user_id' => 0,
            'cer_id' => 0,
        ]);

        $connection_info = $this->ocr2_lib->check_ocr_connection();
        echo $connection_info['msg'] . PHP_EOL;
    }
}
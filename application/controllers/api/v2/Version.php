<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Version extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('log/log_version_model');
    }

    public function ver_get()
    {
        $data = [];
        $get = $this->input->get(NULL, TRUE);
        if(!isset($get['app'])||!isset($get['platform'])){
            $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
        }
        $version = $this->log_version_model->get_by([
            'app_name' => $get['app'],
            'platform' => $get['platform']
        ]);
        if (!empty($version)) {
            $data = array(
                'version'     => $version -> version,
                'description' => $version -> description,
                'events'      => "linePointBox,stepProduct,oppo,skipFB,jv2"
            );
        }
        $this->response(array('result' => 'SUCCESS','data' => $data ));
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'/libraries/REST_Controller.php');

class Version extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('log/log_version_model');
        $this->load->model('user/user_version_update_model');
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
        if (isset($get['user_id'])){
            $version_for_user = $this->user_version_update_model->get_by([
                'user_id'  => $get['user_id'],
                'app' => $get['app'],
                'platform' => $get['platform']
            ]);
        }
        if (!empty($version)) {
            $data = array(
                'version'     => !empty($version_for_user) ? $version_for_user -> allow_version : (ENVIRONMENT == 'production' ? $version -> version : 'all'),
                'description' => $version -> description,
                'events'      => "linePointBox,stepProduct,oppo,skipFB,jv2,livingvoicecheck,childDonate"
            );
        }
        $this->response(array('result' => 'SUCCESS','data' => $data ));
    }
}

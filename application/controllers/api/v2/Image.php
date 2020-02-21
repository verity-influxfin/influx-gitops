<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Image extends REST_Controller {

	public $user_info;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('S3_upload');
        $this->load->library('S3_lib');
        $this->load->library('log/log_image_model');
    }

    public function info_delete()
    {
        $input = $this->input->get(NULL, TRUE);
        $url = isset($input["url"]) ? $input["url"] : 0;
        $ownerId = isset($input["owner_id"]) ? intval($input["owner_id"]) : 0;

        if (!$url || $ownerId <= 0) {
            $this->response(['result' => 'ERROR','error' => INPUT_NOT_CORRECT]);
        }

        $url = str_replace(FRONT_CDN_URL, INFLUX_S3_URL, $url);

        $log = $this->log_image_model->get_by([
            "user_id" => $ownerId,
            "url" => $url
        ]);
        if (!$log) {
            $this->response(['result' => 'ERROR','error' => INPUT_NOT_CORRECT]);
        }

        $allowedToDeleteTypes = ["temp_image"];
        $isAllowedToDelete = in_array($log->type, $allowedToDeleteTypes);
        if (!$isAllowedToDelete) {
            $this->response(['result' => 'ERROR','error' => INPUT_NOT_CORRECT]);
        }

        $this->s3_lib->public_delete_image($log->url, FRONT_S3_BUCKET, ["name" => 'student_card_image']);
        $this->response(['result' => 'SUCCESS']);
    }
}

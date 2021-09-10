<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');
use CreditSheet\CreditSheetFactory;
use CreditSheet\CreditSheetBase;

class Creditmanagement extends MY_Admin_Controller
{

	protected $edit_method = [];
	protected $api_method = ['get_structural_data', 'get_data', 'approve'];

    private $type;
    private $target_id;
    private $creditSheet;
    private $inputData;

    public function __construct()
    {
		parent::__construct();
		$this->load->model('loan/target_model');
        $this->load->model('user/user_certification_model');

        $httpMethod = $this->input->method(TRUE);
        if ($httpMethod == "POST") {
            $this->inputData = $this->input->post(NULL, TRUE);
        }else if ($httpMethod == "GET") {
            $this->inputData = $this->input->get (NULL, TRUE);
        }

        $this->target_id = $this->inputData['target_id'] ?? '';
        $this->type = $this->inputData['type'] ?? '';

        $method = $this->router->fetch_method();
        if(in_array($method, $this->api_method) || empty($this->target_id) || empty($this->type)) {
            $this->load->library('output/json_output');
        }

        if(empty($this->target_id) || empty($this->type)){
            $this->json_output->setStatusCode(400)->setResponse(['parameter can not empty'])->send();
        }
        $this->creditSheet = CreditSheetFactory::getInstance($this->target_id);
	}

    public function index()
    {
        $this->load->view('admin/_header');
        $this->load->view('admin/_title',$this->menu);
        $this->load->view('admin/ocr/list');
        $this->load->view('admin/_footer');
    }

    public function report() {
        $this->load->view($this->creditSheet->getViewPath());
    }

    /**
     * 授審表取得結構資料
     * @apiParam {int} target_id
     * @apiParam {int} type
     * @apiSuccess {Object} result
     */
    public function get_structural_data() {
        $response = $this->creditSheet->getStructuralData();

        $this->json_output->setStatusCode(200)->setResponse($response)->send();
    }

    /**
     * 授審表取得資料
     * @apiParam {int} target_id
     * @apiParam {int} type
     * @apiSuccess {Object} result
     */
    public function get_data() {
        $response = $this->creditSheet->getData();

        $this->json_output->setStatusCode(200)->setResponse($response)->send();
    }

    /**
     * 授審表審核成功
     * @apiParam {int} target_id
     * @apiParam {int} type
     * @apiParam {int} group
     * @apiParam {int} score
     * @apiParam {String} opinion
     * @apiSuccess {Object} result
     */
    public function approve() {
        if(!isset($this->inputData['group']) || !isset($this->inputData['score']) || !isset($this->inputData['opinion'])) {
            $this->json_output->setStatusCode(400)->setResponse(['lack of parameter'])->send();
        }
        $adminId 		= $this->login_info->id;
        $rs = $this->creditSheet->approve(intval($this->inputData['group']), $this->inputData['opinion'],
            intval($this->inputData['score']), $adminId);

        $this->json_output->setStatusCode(200)->setResponse(['responseCode' => intval($rs),
            'msg' => $this->creditSheet::RESPONSE_CODE_LIST[$rs]])->send();
    }
}

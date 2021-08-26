<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');
use CreditSheet\CreditSheetFactory;

class Creditmanagement extends MY_Admin_Controller
{

	protected $edit_method = [];
	protected $api_method = ['get_structural_data', 'get_data'];

    private $type;
    private $target_id;
    private $creditSheet;

    public function __construct()
    {
		parent::__construct();
		$this->load->model('loan/target_model');
        $this->load->model('user/user_certification_model');
        // $this->load->library('report/loan/Credit_management');

        $input = $this->input->get(NULL, TRUE);
        $this->target_id = $input['target_id'] ?? '';
        $this->type = $input['type'] ?? '';

        $method = $this->router->fetch_method();
        if(in_array($method, $this->api_method) || empty($this->target_id) || empty($this->type)) {
            $this->load->library('output/json_output');
        }

        if(empty($this->target_id) || empty($this->type)){
            $this->json_output->setStatusCode(200)->setResponse(['parameter can not empty'])->send();
        }else if(in_array($method, $this->api_method)) {
            $this->creditSheet = CreditSheetFactory::getInstance($this->target_id);
        }
	}

    public function index()
    {
        $this->load->view('admin/_header');
        $this->load->view('admin/_title',$this->menu);
        $this->load->view('admin/ocr/list');
        $this->load->view('admin/_footer');
    }

    public function report(){
        if($this->type == 'person'){
            $this->load->view('admin/target/credit_management/person_report');
        }
        if($this->type == 'judicial'){
            $this->load->view('admin/target/judicial_reort');
        }
    }

    public function get_structural_data(){
        $response = [];
        $response['basicInfo']['reviewLevelList'] = $this->creditSheet->basicInfo->getReviewLevelList();
        $response['basicInfo']['creditCategoryList'] = $this->creditSheet->basicInfo->getCreditCategoryList();

        $response['creditLineInfo']['drawdownTypeList'] = $this->creditSheet->creditLineInfo->getDrawdownTypeList();
        $response['creditLineInfo']['interestTypeList'] = $this->creditSheet->creditLineInfo->getInterestTypeList();
        $response['creditLineInfo']['applyLineTypeList'] = $this->creditSheet->creditLineInfo->getApplyLineTypeList();
        $response['creditLineInfo']['reviewerList'] = $this->creditSheet->creditLineInfo->getReviewerList();


        $this->json_output->setStatusCode(200)->setResponse($response)->send();
    }

    public function get_data(){
        $response['basicInfo'] = $this->creditSheet->basicInfo->getBasicInfo();
        $response['creditLineInfo'] = $this->creditSheet->creditLineInfo->getCreditLineInfo();

        $this->json_output->setStatusCode(200)->setResponse($response)->send();
    }


}

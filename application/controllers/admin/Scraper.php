<?php

defined('BASEPATH') or exit('No direct script access allowed');
require(APPPATH . '/libraries/MY_Admin_Controller.php');

class Scraper extends MY_Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('scraper/judicial_yuan_lib');
        $this->load->library('scraper/sip_lib');
        $this->load->library('scraper/findbiz_lib');
        $this->load->library('scraper/business_registration_lib');
        $this->load->library('scraper/google_lib');
        $this->load->library('scraper/instagram_lib');
    }

    public function index()
    {
        $input = $this->input->get(NULL, TRUE);
        $page_view = isset($input['view']) ? $input['view'] : '';
        $this->load->view('admin/_header',$data=['use_vuejs'=>true]);
        $this->load->view('admin/_title', $this->menu);
        if ( ! empty($page_view))
        {
            $this->load->view("admin/certification/scraper/{$page_view}");
        }
        else
        {
            $this->load->view('admin/certification/scraper/scraper_status');
        }
        $this->load->view('admin/_footer');
    }

    private function _get_new_domicile($domicile)
    {
        preg_match('/([\x{4e00}-\x{9fa5}]+)(縣|市)/u', str_replace('台', '臺', $domicile), $matches);
        if ( ! empty($matches))
        {
            $domicile = $matches[1];
        }

        return $domicile;
    }

    public function scraper_status()
    {
        $input = $this->input->get(NULL, TRUE);
        $this->load->library('output/json_output');
        $this->load->model('user/user_certification_model');

        if (empty($input) || ! is_array($input))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'data type not correct'])->send();
        }

        if (empty($input['user_id']))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'parameter not correct'])->send();
        }

        $response = [
            'household_registration_status' => '',
            'judicial_yuan_status' => '',
            'google_status' => '',
            'instagram_status' => '',
            'sip_status' => '',
            'biz_status' => '',
            'business_registration_status' => '',
        ];

        // household_registration_status
        $hrCer = $this->user_certification_model->get_household_info($input['user_id']);
        if ( ! empty($hrCer) && isset($hrCer[0]->id_card_api))
        {
            $hr_result = json_decode($hrCer[0]->id_card_api, TRUE);
            if (isset($hr_result['response']['rowData']['responseData']['checkIdCardApply']) && ! empty($hr_result['response']['rowData']['responseData']['checkIdCardApply']))
            {
                $hr_status = $hr_result['response']['rowData']['responseData']['checkIdCardApply'];
                $response['household_registration_status'] = $hr_status == 1 ? 'finished' : 'failure';
            }
        }

        // verdict_status
        $info = $this->user_model->get($input['user_id']);
        if ( ! empty($info) && isset($info->name))
        {
            $name = $info->name;
            $address = $this->_get_new_domicile($info->address);
            $judicial_yuan_status = $this->judicial_yuan_lib->requestJudicialYuanVerdictsStatuses($name, $address);
            if (isset($judicial_yuan_status['response']['status']) && ! empty($judicial_yuan_status['response']['status']))
            {
                $response['judicial_yuan_status'] = $judicial_yuan_status['response']['status'];
            }
        }

        // google_status
        if ( ! empty($name))
        {
            $google_status = $this->google_lib->get_google_status($name);
            if ( ! empty($google_status['response']['status']) && isset($google_status['response']['status']))
            {
                $response['google_status'] = $google_status['response']['status'];
            }
        }

        // instagram_status
        $social_content = $this->user_certification_model->get_content($input['user_id'], CERTIFICATION_SOCIAL);
        if (! empty($social_content) && isset($social_content[0]->content))
        {
            $social_result = json_decode($social_content[0]->content, TRUE);
            if (isset($social_result['instagram']['username']) && ! empty($social_result['instagram']['username']))
            {
                $ig_username = $social_result['instagram']['username'];
                $ig_status = $this->instagram_lib->getLogStatus($input['user_id'], $ig_username);
                if (isset($ig_status['response']['result']['status']) && ! empty($ig_status['response']['result']['status']))
                {
                    $response['instagram_status'] = $ig_status['response']['result']['status'];
                }
            }
        }

        // sip_status
        $sip_content = $this->user_certification_model->get_content($input['user_id'], CERTIFICATION_STUDENT);
        if ( ! empty($sip_content) && isset($sip_content[0]->content))
        {
            $sip_result = json_decode($sip_content[0]->content, TRUE);
            if (isset($sip_result['school']) && isset($sip_result['sip_account']))
            {
                $school = $sip_result['school'];
                $account = $sip_result['sip_account'];
                $sip_status = $this->sip_lib->getDeepLog($school, $account);
                if (isset($sip_status['response']['status']) && ! empty($sip_status['response']['status']))
                {
                    $response['sip_status'] = $sip_status['response']['status'];
                }
            }
        }

        // biz_status
        $job_content = $this->user_certification_model->get_content($input['user_id'], CERTIFICATION_JOB);
        if ( ! empty($job_content) && isset($job_content[0]->content))
        {
            $job_result = json_decode($job_content[0]->content, TRUE);
            $businessid = $job_result['tax_id'];
            if ( ! empty($businessid))
            {
                $biz_status = $this->findbiz_lib->getFindBizStatus($businessid);
                if (isset($biz_status['response']['result']['status']) && ! empty($biz_status['response']['result']['status']))
                {
                    $response['biz_status'] = $biz_status['response']['result']['status'];
                }
            }
        }

        // business_registration_status
        if ( ! empty($businessid) && isset($businessid))
        {
            $business_registration_status = $this->business_registration_lib->getResultByBusinessId($businessid);
            if ( ! empty($business_registration_status))
            {
                $response['business_registration_status'] = 'finished';
            }
        }

        //ptt 未啟用
//        if ( ! empty($social_content))
//        {
//            if (isset($social_result['ptt']['username']) && ! empty($social_result['ptt']['username']))
//            {
//                $ptt_username = $social_result['ptt']['username'];
//                $ptt_status = $this->ptt_lib->get_ptt_status($input['user_id'], $ptt_username);
//                if (isset($ptt_status['response']['status']) && ! empty($ptt_status['response']['status']))
//                {
//                    $response['ptt_status'] = $ptt_status['response']['status'];
//                }
//            }
//        }

        $this->json_output->setStatusCode(200)->setResponse($response)->send();
    }

    public function identity_info()
    {
        $input = $this->input->get(NULL, TRUE);
        $this->load->library('output/json_output');
        $this->load->model('user/user_certification_model');

        if (empty($input) || ! is_array($input))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'data type not correct'])->send();
        }

        if (empty($input['user_id']))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'parameter not correct'])->send();
        }

        $info = $this->user_model->get($input['user_id']);
        $ocr = $this->user_certification_model->get_ocr($input['user_id']);
        if (empty($info))
        {
            $info = isset($info) ? $info : ['message' => 'sql no response'];
            $this->json_output->setStatusCode(401)->setResponse($info)->send();
        }

        # ig 帳號
        $social_content = $this->user_certification_model->get_content($input['user_id'], CERTIFICATION_SOCIAL);
        
        if ( ! isset($social_content[0]->content))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'content not found'])->send();
        }
        $content = json_decode($social_content[0]->content, TRUE);
        if (! isset($content['instagram']['username']))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'name or ig username not found'])->send();
        }

        $response = [
            'name' => '',
            'birthday' => '',
            'id_number' => '',
            'id_card_date' => '',
            'id_card_place' => '',
            'address' => '',
            'father' => '',
            'mother' => '',
            'born' => '',
            'spouse' => '',
            'instagram_username' => ''
        ];

        $response['name'] = isset($info->name) ? $info->name : '';
        $response['birthday'] = isset($info->birthday) ? $info->birthday : '';
        $response['id_number'] = isset($info->id_number) ? $info->id_number : '';
        $response['id_card_date'] = isset($info->id_card_date) ? $info->id_card_date : '';
        $response['id_card_place'] = isset($info->id_card_place) ? $info->id_card_place : '';
        $response['address'] = isset($info->address) ? $info->address : '';

        if ( ! empty($ocr))
        {
            if (isset($ocr[0]->ocr) && ! empty($ocr[0]->ocr))
            {
                $result = json_decode($ocr[0]->ocr, TRUE);
                $response['father'] = isset($result['father']) ? $result['father'] : '';
                $response['mother'] = isset($result['mother']) ? $result['mother'] : '';
                $response['born'] = isset($result['born']) ? $result['born'] : '';
                $response['spouse'] = isset($result['spouse']) ? $result['spouse'] : '';
            }
        }
        
        $response['instagram_username'] = isset($content['instagram']['username']) ? $content['instagram']['username'] : '';

        $this->json_output->setStatusCode(200)->setResponse($response)->send();
    }

    public function judicial_yuan_count()
    {
        $input = $this->input->get(NULL, TRUE);
        $this->load->library('output/json_output');
        $this->load->model('user/user_certification_model');

        if (empty($input['user_id']))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'parameter not correct'])->send();
        }

        $info = $this->user_model->get($input['user_id']);
        if (empty($info) || ! isset($info->name))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'user name not found'])->send();
        }

        $name = isset($input['name']) ? $input['name'] : $info->name;

        $domicile = $this->_get_new_domicile($input['address']);
        $judicial_yuan_response = $this->judicial_yuan_lib->requestJudicialYuanVerdictsCount($name, $domicile);
        if (empty($judicial_yuan_response) || ! isset($judicial_yuan_response['response']))
        {
            $judicial_yuan_response = isset($judicial_yuan_response['response']) ? $judicial_yuan_response['response'] : ['message' => 'judicialyuan not response'];
            $this->json_output->setStatusCode(401)->setResponse($judicial_yuan_response)->send();
        }

        $this->json_output->setStatusCode(200)->setResponse($judicial_yuan_response['response'])->send();
    }

    public function judicial_yuan_case()
    {
        $input = $this->input->get(NULL, TRUE);
        $this->load->library('output/json_output');

        if (empty($input) || ! is_array($input))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'data type not correct'])->send();
        }

		if (empty($input['user_id'])|| empty($input['case_type']) || empty($input['address']))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'parameter not correct'])->send();
        }

        $info = $this->user_model->get($input['user_id']);
        if (empty($info) || ! isset($info->name))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'user name not found'])->send();
        }

        $name = isset($input['name']) ? $input['name'] : $info->name;

        $domicile = $this->_get_new_domicile($input['address']);
        
        $judicial_yuan_response = $this->judicial_yuan_lib->requestJudicialYuanVerdictsCase($name, $input['case_type'], $domicile);

        if (empty($judicial_yuan_response) || ! isset($judicial_yuan_response['response']))
        {
            $judicial_yuan_response = isset($judicial_yuan_response['response']) ? $judicial_yuan_response['response'] : ['message' => 'judicialuan not response'];
            $this->json_output->setStatusCode(401)->setResponse($judicial_yuan_response)->send();
        }
        $judicial_yuan_response['response']['name'] = $info->name;
        $this->json_output->setStatusCode(200)->setResponse($judicial_yuan_response['response'])->send();
    }

    public function judicial_yuan_verdicts(){
        $this->load->library('output/json_output');
        $input = json_decode($this->security->xss_clean($this->input->raw_input_stream), TRUE);
		
		// ig
		if (isset($input['ig']))
		{
			$result = $this->judicial_yuan_lib->requestJudicialYuanAllCityVerdicts($input['ig']);
			echo json_encode($result);
			die();
		}

        $address = $this->_get_new_domicile($input['address']);
        $result = $this->judicial_yuan_lib->requestJudicialYuanVerdicts($input['name'], $address);	
        if( ! $result){
            $error = [
                'response' => [
                    'message' => '無回應，請洽工程師。'
                ]
            ];
            echo json_encode($error);
        }
        echo json_encode($result);
    }

    public function judicial_yuan_status(){
        $input = $this->input->get(NULL, TRUE);
        $this->load->library('output/json_output');
        $response = [];
		if( ! isset($input['name'])){
			echo json_encode(['message' => 'parameter not correct']);
		}
        $name = $input['name'];
        $judicial_yuan_status = $this->judicial_yuan_lib->requestJudicialYuanVerdictsStatuses($name);      
        if (isset($judicial_yuan_status['response']['status']) && ! empty($judicial_yuan_status['response']['status']))
        {
            $response['judicial_yuan_status'] = $judicial_yuan_status['response']['status'];
        }
        echo json_encode($response);
    }

    public function sip_info()
    {
        $input = $this->input->get(NULL, TRUE);
        $this->load->library('output/json_output');
        $this->load->model('user/user_certification_model');

        if (empty($input) || ! is_array($input))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'data type not correct'])->send();
        }

        if (empty($input['user_id']))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'parameter not correct'])->send();
        }

        $info = $this->user_model->get($input['user_id']);
        $sip_content = $this->user_certification_model->get_content($input['user_id'], CERTIFICATION_STUDENT);

        if (empty($info))
        {
            $info = isset($info) ? $info : ['message' => 'sql no response'];
            $this->json_output->setStatusCode(401)->setResponse($info)->send();
        }

        $response = [
            'name' => '',
            'birthday' => '',
            'id_number' => '',
            'url' => '',
            'school' => '',
            'department' => '',
            'student_id' => '',
            'sip_account' => '',
            'sip_password' => '',
            'email' => ''
        ];
        $response['name'] = isset($info->name) ? $info->name : '';
        $response['birthday'] = isset($info->birthday) ? $info->birthday : '';
        $response['id_number'] = isset($info->id_number) ? $info->id_number : '';

        if ( ! empty($sip_content) && isset($sip_content[0]->content))
        {
            $result = json_decode($sip_content[0]->content, TRUE);
            $response['school'] = isset($result['school']) ? $result['school'] : '';
            $response['department'] = isset($result['department']) ? $result['department'] : '';
            $response['student_id'] = isset($result['student_id']) ? $result['student_id'] : '';
            $response['sip_account'] = isset($result['sip_account']) ? $result['sip_account'] : '';
            $response['sip_password'] = isset($result['sip_password']) ? $result['sip_password'] : '';
            $response['email'] = isset($result['email']) ? $result['email'] : '';
        }

        if ( ! empty($response['school'] && ! empty($response['sip_account'])))
        {
            $url = $this->sip_lib->getUniversityModel($response['school'], $response['sip_account']);
            if (isset($url['response']['url']) && ! empty($url['response']['url']))
            {
                $response['url'] = $url['response']['url'];
            }
        }

        $this->json_output->setStatusCode(200)->setResponse($response)->send();
    }

    // todo : 須先判斷爬取狀態
    public function sip()
    {
        $input = $this->input->get(NULL, TRUE);
        $this->load->library('output/json_output');
        if (empty($input) || ! is_array($input))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'data type not correct'])->send();
        }

        if (empty($input['university']) || empty($input['account']))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'parameter not correct'])->send();
        }

        $response = $this->sip_lib->getDeepData($input['university'], $input['account']);
        if (empty($response['response']) || ! isset($response['response']))
        {
            $this->json_output->setStatusCode(401)->setResponse(['message' => 'sip not response'])->send();
        }
        
        $school_status = $this->sip_lib->getUniversityModel($input['university'], $input['account']);
        if (isset($school_status['response']))
        {
            $response['response']['school_status'] = $school_status['response']; 
        }

        $this->json_output->setStatusCode(200)->setResponse($response['response'])->send();
    }

    public function request_deep(){
        $this->load->library('output/json_output');
        $input = json_decode($this->security->xss_clean($this->input->raw_input_stream), TRUE);
        $result = $this->sip_lib->requestDeep($input['university'],$input['account'],$input['password']);
        if( ! $result){
            $error = [
                'response' => [
                    'message' => '無回應，請洽工程師。'
                ]
            ];
            echo json_encode($error);
        }
        echo json_encode($result);
    }

    public function business_info()
    {
        $input = $this->input->get(NULL, TRUE);
        $this->load->library('output/json_output');
        $this->load->model('user/user_certification_model');

        if (empty($input) || ! is_array($input))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'data type not correct'])->send();
        }

        if (empty($input['user_id']))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'parameter not correct'])->send();
        }

        $info = $this->user_model->get($input['user_id']);
        $job_content = $this->user_certification_model->get_content($input['user_id'], CERTIFICATION_JOB);
        if (empty($info || empty($job_content)))
        {
            $info = isset($info) ? $info : ['message' => 'sql no response'];
            $this->json_output->setStatusCode(401)->setResponse($info)->send();
        }

        $response = [
            'name' => '',
            'birthday' => '',
            'id_number' => '',
            'companyName' => '',
            'tax_id' => '',
            'insuranceSalary' => '',
            'total_count' => '',
            'this_company_count' => '',
            'report_date' => ''
        ];

        $response['name'] = isset($info->name) ? $info->name : '';
        $response['birthday'] = isset($info->birthday) ? $info->birthday : '';
        $response['id_number'] = isset($info->id_number) ? $info->id_number : '';

        if (isset($job_content[0]->content) && ! empty($job_content[0]->content))
        {
            $result = json_decode($job_content[0]->content, TRUE);
        }
        $response['tax_id'] = isset($result['tax_id']) ? $result['tax_id'] : '';
        $response['companyName'] = isset($result['company']) ? $result['company'] : '';
        $response['insuranceSalary'] = isset($result['salary']) ? $result['salary'] : '';
        $response['total_count'] = isset($result['total_count']) ? $result['total_count'] : '';
        $response['this_company_count'] = isset($result['this_company_count']) ? $result['this_company_count'] : '';
        $response['report_date'] = isset($result['report_date']) ? $result['report_date'] : '';

        $this->json_output->setStatusCode(200)->setResponse($response)->send();
    }

    public function biz()
    {
        $input = $this->input->get(NULL, TRUE);
        $this->load->library('output/json_output');

        if (empty($input) || ! is_array($input))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'data type not correct'])->send();
        }

        if (empty($input['tax_id']))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'parameter not correct'])->send();
        }

        $response = $this->findbiz_lib->getFindBizData($input['tax_id']);
        if (empty($response) || ! isset($response['response']))
        {
            $response = isset($response['response']) ? $response['response'] : ['message' => 'biz not response'];
            $this->json_output->setStatusCode(401)->setResponse($response)->send();
        }

        $this->json_output->setStatusCode(200)->setResponse($response['response'])->send();
    }

    public function requestFindBizData(){
        $this->load->library('output/json_output');
        $input = json_decode($this->security->xss_clean($this->input->raw_input_stream), TRUE);
        $result = $this->findbiz_lib->requestFindBizData($input['tax_id']);
        if( ! $result){
            $error = [
                'response' => [
                    'message' => '無回應，請洽工程師。'
                ]
            ];
            echo json_encode($error);
        }
        // 
        echo json_encode($result);
    }

    public function business_registration()
    {
        $input = $this->input->get(NULL, TRUE);
        $this->load->library('output/json_output');

        if (empty($input) || ! is_array($input))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'data type not correct'])->send();
        }

        if (empty($input['tax_id']))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'parameter not correct'])->send();
        }

        $response = $this->business_registration_lib->getResultByBusinessId($input['tax_id']);
        $date = $this->business_registration_lib->getDate();
        if (empty($date) || ! isset($response['response']))
        {
            $response = isset($response->response) ? $response->response : ['message' => 'businessRegistration not response'];
            $this->json_output->setStatusCode(401)->setResponse($response)->send();
        }

        $this->json_output->setStatusCode(200)->setResponse($response['response'])->send();
    }

    public function downloadBusinessRegistration(){
        $this->load->library('output/json_output');
        $result = $this->business_registration_lib->downloadBusinessRegistration();
        if( ! $result){
            $error = [
                'response' => [
                    'message' => '無回應，請洽工程師。'
                ]
            ];
            echo json_encode($error);
        }
        // 
        echo json_encode($result);
    }

    public function google_status(){
        $input = $this->input->get(NULL, TRUE);
        $this->load->library('output/json_output');
        $response = [];
        if( ! isset($input['name'])){
            echo json_encode(['message' => 'parameter not correct']);
        }
        $name = $input['name'];
        $google_status = $this->google_lib->get_google_status($name);
        if (isset($google_status['response']['status']) && ! empty($google_status['response']['status']))
        {
            $response['google_status'] = $google_status['response']['status'];
        }
        echo json_encode($response);
    }

    public function google()
    {
        $input = $this->input->get(NULL, TRUE);
        $this->load->library('output/json_output');

        if (empty($input) || ! is_array($input))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'data type not correct'])->send();
        }

        if (empty($input['user_id']))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'parameter not correct'])->send();
        }

        if (empty($input['name']))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'parameter not correct'])->send();
        }

        $response = $this->google_lib->get_google_data($input['name']);
        if (empty($response) || ! isset($response['response']))
        {
            $response = isset($response) ? $response : ['message' => 'google not response'];
            $this->json_output->setStatusCode(401)->setResponse($response)->send();
        }

        $this->json_output->setStatusCode(200)->setResponse($response['response'])->send();
    }

    public function request_google(){
        $this->load->library('output/json_output');
        $input = json_decode($this->security->xss_clean($this->input->raw_input_stream), TRUE);
        $result = $this->google_lib->request_google($input['keyword']);
        if( ! $result){
            $error = [
                'response' => [
                    'message' => '無回應，請洽工程師。'
                ]
            ];
            echo json_encode($error);
        }
        echo json_encode($result);
    }

    public function ptt_info()
    {
        $input = $this->input->get(NULL, TRUE);
        $this->load->library('output/json_output');
        $this->load->model('user/user_certification_model');

        if (empty($input) || ! is_array($input))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'data type not correct'])->send();
        }

        if (empty($input['user_id']))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'parameter not correct'])->send();
        }

        $info = $this->user_model->get($input['user_id']);
        if ( ! isset($info->name))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'name not found'])->send();
        }

        if (empty($response))
        {
            $response = isset($response) ? $response : ['message' => 'ptt not response'];
            $this->json_output->setStatusCode(401)->setResponse($response)->send();
        }

        $name = $info->name;
        $response['name'] = $name;
        // todo: ptt帳號尚未存入db
        $response['ptt_acount'] = '';
        $this->json_output->setStatusCode(200)->setResponse($response)->send();
    }

    public function ptt()
    {
        $input = $this->input->get(NULL, TRUE);
        $this->load->library('output/json_output');

        if (empty($input) || ! is_array($input))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'data type not correct'])->send();
        }

        if (empty($input['user_id'] || empty($input['account'])))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'parameter not correct'])->send();
        }

        $response = $this->ptt_lib->get_ptt_data($input['user_id'], $input['account']);
        if (empty($response))
        {
            $response = isset($response) ? $response : ['message' => 'ptt not response'];
            $this->json_output->setStatusCode(401)->setResponse($response)->send();
        }

        $this->json_output->setStatusCode(200)->setResponse($response['response'])->send();
    }

    public function instagram_info()
    {
        $input = $this->input->get(NULL, TRUE);
        $this->load->library('output/json_output');
        $this->load->model('user/user_certification_model');

        if (empty($input) || ! is_array($input))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'data type not correct'])->send();
        }

        if (empty($input['user_id']))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'parameter not correct'])->send();
        }

        $social_content = $this->user_certification_model->get_content($input['user_id'], CERTIFICATION_SOCIAL);
        if ( ! isset($social_content[0]->content))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'content not found'])->send();
        }
        $content = json_decode($social_content[0]->content, TRUE);
        $info = $this->user_model->get($input['user_id']);
        if ( ! isset($info->name) || ! isset($content['instagram']['username']))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'name or ig username not found'])->send();
        }

        $response = [
            'name' => $info->name,
            'ig_username' => $content['instagram']['username'],
        ];

        $this->json_output->setStatusCode(200)->setResponse($response)->send();
    }

    public function instagram()
    {
        $input = $this->input->get(NULL, TRUE);
        $this->load->library('output/json_output');

        if (empty($input) || ! is_array($input))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'data type not correct'])->send();
        }

        if (empty($input['user_id']) || empty($input['ig_username']))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'parameter not correct'])->send();
        }
        $response = $this->instagram_lib->getRiskControlInfo($input['user_id'], $input['ig_username']);
        if (empty($response))
        {
            $response = isset($response) ? $response : ['message' => 'instagram not response'];
            $this->json_output->setStatusCode(401)->setResponse($response)->send();
        }

        $this->json_output->setStatusCode(200)->setResponse($response['response'])->send();
    }

    public function updateIGUserInfo(){
        $this->load->library('output/json_output');
        $input = json_decode($this->security->xss_clean($this->input->raw_input_stream), TRUE);
        $result = $this->instagram_lib->updateRiskControlInfo($input['userId'], $input['followed_account']);
        if( ! $result){
            $error = [
                'response' => [
                    'message' => '無回應，請洽工程師。'
                ]
            ];
            echo json_encode($error);
        }
        echo json_encode($result);
    }

    public function autoFollowIG(){
        $this->load->library('output/json_output');
        $input = json_decode($this->security->xss_clean($this->input->raw_input_stream), TRUE);
        $result = $this->instagram_lib->autoFollow($input['userId'], $input['followed_account']);
        if( ! $result){
            $error = [
                'response' => [
                    'message' => '無回應，請洽工程師。'
                ]
            ];
            echo json_encode($error);
        }
        echo json_encode($result);
    }
}
?>

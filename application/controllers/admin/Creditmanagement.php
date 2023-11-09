<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');
use CreditSheet\CreditSheetFactory;
use CreditSheet\CreditSheetBase;

class Creditmanagement extends MY_Admin_Controller
{

	protected $edit_method = [];
	protected $api_method = ['get_structural_data', 'get_data', 'approve', 'get_reviewed_list'];

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
     * 取得已審查的資料列表
     * @apiParam {int} target_id
     * @apiParam {int} type
     * @apiSuccess {Object} result
     */
    public function get_reviewed_list() {
        $response = $this->creditSheet->getReviewedInfoList();

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
        if (!isset($this->inputData['target_id']) || !isset($this->inputData['group']) || !isset($this->inputData['score']) || !isset($this->inputData['opinion'])) {
            $this->json_output->setStatusCode(400)->setResponse(['lack of parameter'])->send();
        }

        // 檢查該驗證的驗證項是否都過了
        if (isset($this->inputData['target_id']))
        {
            $response = $this->_chk_certification($this->inputData['target_id']);

            if ($response['result'] !== TRUE)
            {
                $this->json_output->setStatusCode(400)->setResponse(['msg' => $response['msg']])->send();
            }
        }

        // 檢查是否有此案件
        $target = $this->target_model->get($this->inputData['target_id']);
        if(!isset($target)){
                $this->json_output->setStatusCode(400)->setResponse(['msg' => '查無此案件'])->send();
        }

        $this->load->model('loan/credit_model');
        $credit = $this->credit_model->get_by([
            'user_id'=>$target->user_id,
            'product_id'=>$target->product_id,
            'sub_product_id'=>$target->sub_product_id,
            'instalment'=>$target->instalment,
            'status'=>1
        ]);
        if(!isset($credit)){
                $this->json_output->setStatusCode(400)->setResponse(['msg' => '查無此額度'])->send();
        }
        // 如果調整過分數、調整過額度，則檢查是否為新用戶、是否年滿35歲
        if ((isset($this->inputData['score']) && $this->inputData['score'] != 0)
            || (isset($this->inputData['fixed_amount']) && $this->inputData['fixed_amount'] != $credit->amount)
        ) {
            // 檢查使用者是否為新用戶
            $user_id = $this->target_model->get_user_id_by_id($this->inputData['target_id']);
            if (!isset($user_id['user_id'])) {
                $this->json_output->setStatusCode(400)->setResponse(['msg' => '查無此用戶id'])->send();
            }
            $user_info = $this->user_model->get($user_id['user_id']);
            if (!isset($user_info)) {
                $this->json_output->setStatusCode(400)->setResponse(['msg' => '查無此用戶'])->send();
            }
            $past_targets = $this->target_model->get_many_by([
                'user_id' => $user_id,
                'status' => [5, 10],
            ]);
            // 這裡新戶的算法只要看 是否放款過的用戶
            $is_new_user = count($past_targets) == 0;
            if ($is_new_user) {
                $age = get_age($user_info->birthday);
                // 新戶年齡不可超過35歲
                if ($age >= 35) {
                    $this->json_output->setStatusCode(400)->setResponse(['msg' => '借款人年齡超過35歲，不可調整'])->send();
                }
            }
        }

        $this->load->model('loan/target_meta_model');
        if (isset($this->inputData['job_company_taiwan_1000_point']) && is_numeric($this->inputData['job_company_taiwan_1000_point']))
        {
            $rs = $this->target_meta_model->get_by(['target_id' => $this->inputData['target_id'], 'meta_key' => 'job_company_taiwan_1000_point']);
            if (isset($rs))
            {
                $this->target_meta_model->update_by(['target_id' => $this->inputData['target_id'], 'meta_key' => 'job_company_taiwan_1000_point'], ['meta_value' => $this->inputData['job_company_taiwan_1000_point']]);
            }
            else
            {
                $this->target_meta_model->insert(['target_id' => $this->inputData['target_id'], 'meta_key' => 'job_company_taiwan_1000_point', 'meta_value' => $this->inputData['job_company_taiwan_1000_point']]);
            }
        }
        else
        {
            $this->target_meta_model->update_by(['target_id' => $this->inputData['target_id'], 'meta_key' => 'job_company_taiwan_1000_point'], ['meta_value' => '']);
        }
        if (isset($this->inputData['job_company_world_500_point']) && is_numeric($this->inputData['job_company_world_500_point']))
        {
            $rs = $this->target_meta_model->get_by(['target_id' => $this->inputData['target_id'], 'meta_key' => 'job_company_world_500_point']);
            if (isset($rs))
            {
                $this->target_meta_model->update_by(['target_id' => $this->inputData['target_id'], 'meta_key' => 'job_company_world_500_point'], ['meta_value' => $this->inputData['job_company_world_500_point']]);
            }
            else
            {
                $this->target_meta_model->insert(['target_id' => $this->inputData['target_id'], 'meta_key' => 'job_company_world_500_point', 'meta_value' => $this->inputData['job_company_world_500_point']]);
            }
        }
        else
        {
            $this->target_meta_model->update_by(['target_id' => $this->inputData['target_id'], 'meta_key' => 'job_company_world_500_point'], ['meta_value' => '']);
        }
        if (isset($this->inputData['job_company_medical_institute_point']) && is_numeric($this->inputData['job_company_medical_institute_point']))
        {
            $rs = $this->target_meta_model->get_by(['target_id' => $this->inputData['target_id'], 'meta_key' => 'job_company_medical_institute_point']);
            if (isset($rs))
            {
                $this->target_meta_model->update_by(['target_id' => $this->inputData['target_id'], 'meta_key' => 'job_company_medical_institute_point'], ['meta_value' => $this->inputData['job_company_medical_institute_point']]);
            }
            else
            {
                $this->target_meta_model->insert(['target_id' => $this->inputData['target_id'], 'meta_key' => 'job_company_medical_institute_point', 'meta_value' => $this->inputData['job_company_medical_institute_point']]);
            }
        }
        else
        {
            $this->target_meta_model->update_by(['target_id' => $this->inputData['target_id'], 'meta_key' => 'job_company_medical_institute_point'], ['meta_value' => '']);
        }
        if (isset($this->inputData['job_company_public_agency_point']) && is_numeric($this->inputData['job_company_public_agency_point']))
        {
            $rs = $this->target_meta_model->get_by(['target_id' => $this->inputData['target_id'], 'meta_key' => 'job_company_public_agency_point']);
            if (isset($rs))
            {
                $this->target_meta_model->update_by(['target_id' => $this->inputData['target_id'], 'meta_key' => 'job_company_public_agency_point'], ['meta_value' => $this->inputData['job_company_public_agency_point']]);
            }
            else
            {
                $this->target_meta_model->insert(['target_id' => $this->inputData['target_id'], 'meta_key' => 'job_company_public_agency_point', 'meta_value' => $this->inputData['job_company_public_agency_point']]);
            }
        }
        else
        {
            $this->target_meta_model->update_by(['target_id' => $this->inputData['target_id'], 'meta_key' => 'job_company_public_agency_point'], ['meta_value' => '']);
        }

        $adminId 		= $this->login_info->id;
        $rs = $this->creditSheet->approve(intval($this->inputData['group']), $this->inputData['opinion'],
            intval($this->inputData['score']), $adminId, (int) $this->inputData['fixed_amount']);

        $this->json_output->setStatusCode(200)->setResponse(['responseCode' => intval($rs),
            'msg' => $this->creditSheet::RESPONSE_CODE_LIST[$rs]])->send();
    }

    /**
     * 比對user申請的產品所對應的驗證項，是否已全數通過
     * @param $target_id
     * @return array|bool[]
     */
    private function _chk_certification($target_id)
    {
        $response = $this->target_model->get($target_id);
        $this->load->library('loanmanager/product_lib');
        $product_certs = $this->product_lib->get_product_certs_by_product_id($response->product_id, $response->sub_product_id, []);

        if ( ! isset($response->product_id) || ! isset($response->sub_product_id))
        {
            return ['result' => FALSE, 'msg' => '查無申貸產品紀錄'];
        }

        $product_detail = $this->_get_product_detail($response->product_id, $response->sub_product_id);
        if ( ! $product_detail)
        {
            return ['result' => FALSE, 'msg' => '查無產品設定資料'];
        }

        if ( ! isset($product_detail['certifications']))
        {
            return ['result' => FALSE, 'msg' => '查無產品設定認證項'];
        }

        $user_certification = $this->user_certification_model->get_certification_data_by_user_id($response->user_id, BORROWER);
        $user_certification = array_column($user_certification, 'status', 'certification_id');

        // 必填的驗證項
        if (isset($product_detail['backend_option_certifications']))
        { // 過濾掉[後台]上選填的徵信項
            $certification_need_chk = array_diff($product_certs, $product_detail['backend_option_certifications']);
        }
        else
        {
            $certification_need_chk = $product_certs;
        }
        $this->load->library('certification_lib');
        if($this->certification_lib->associate_certs_are_succeed($response) == FALSE)
        {
            return ['result' => FALSE, 'msg' => '尚有自然關係人未完成'];
        }

        foreach ($certification_need_chk as $certification_id)
        {
            // DB查無認證項資料
            if ( ! isset($user_certification[$certification_id]))
            {
                return ['result' => FALSE, 'msg' => '尚有認證項未完成(' . $certification_id . ')'];
            }

            // 認證項不成功
            if ($user_certification[$certification_id] != CERTIFICATION_STATUS_SUCCEED)
            {
                return ['result' => FALSE, 'msg' => '尚有認證項未通過(' . $certification_id . ')'];
            }
        }

        return ['result' => TRUE];
    }

    /**
     * 依「主/副產品ID」取得對應的產品設定
     * @param $product_id : 主產品ID
     * @param $sub_product_id : 副產品ID
     * @return false|mixed
     */
    private function _get_product_detail($product_id, $sub_product_id)
    {
        $product_list = $this->config->item('product_list');
        $sub_product_list = $this->config->item('sub_product_list');

        if ( ! isset($product_list[$product_id]))
        {
            return FALSE;
        }

        $product = $product_list[$product_id];

        if (isset($sub_product_list[$sub_product_id]['identity'][$product['identity']]) && in_array($sub_product_id, $product['sub_product']))
        {
            return $sub_product_list[$sub_product_id]['identity'][$product['identity']];
        }
        else
        {
            return $product;
        }
    }
}

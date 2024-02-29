<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'/libraries/REST_Controller.php');

use Certification\Certification_factory;
use Certification\Cert_identity;
use CertificationResult\IdentityCertificationResult;
use CertificationResult\MessageDisplay;
use GuzzleHttp\Client;

class Product extends REST_Controller {

    public $user_info;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Certification_lib');
        $this->load->library('Target_lib');
        $method = $this->router->fetch_method();
        $nonAuthMethods = ['chk_famous_school'];
        if (!in_array($method, $nonAuthMethods)) {
            $token 				= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:'';
            $tokenData 			= AUTHORIZATION::getUserInfoByToken($token);
            $nonCheckMethods 	= ['list','info'];
            if(in_array($method, $nonCheckMethods) && empty($token)){
                $this->user_info = [];
            }else{
                if (empty($tokenData->id) || empty($tokenData->phone) || $tokenData->expiry_time<time()) {
                    $this->response(array('result' => 'ERROR','error' => TOKEN_NOT_CORRECT ));
                }

                $this->user_info = $this->user_model->get($tokenData->id);
                if($tokenData->auth_otp != $this->user_info->auth_otp){
                    $this->response(array('result' => 'ERROR','error' => TOKEN_NOT_CORRECT ));
                }

                if($this->user_info->block_status != 0){
                    $this->response(array('result' => 'ERROR','error' => BLOCK_USER ));
                }

                //只限借款人
                if($tokenData->investor != 0 && !in_array($method,['list'])){
                    $this->response(array('result' => 'ERROR','error' => IS_INVERTOR ));
                }

                //暫不開放法人
                //if(isset($tokenData->company) && $tokenData->company != 0  && !in_array($method,['list'])){
                    //$this->response(array('result' => 'ERROR','error' => IS_COMPANY ));
                //}

                if(isset($tokenData->company) && $tokenData->company != 0) {
                    $this->load->library('Judicialperson_lib');
                    $this->user_info->naturalPerson = $this->judicialperson_lib->getNaturalPerson($tokenData->id);
                }

                if($this->request->method != 'get'){
                    $this->load->model('log/log_request_model');
                    $this->log_request_model->insert(array(
                        'method' 	=> $this->request->method,
                        'url'	 	=> $this->uri->uri_string(),
                        'investor'	=> $tokenData->investor,
                        'user_id'	=> $tokenData->id,
                        'agent'		=> $tokenData->agent,
                    ));
                }

                $this->user_info->investor 		= $tokenData->investor;
                $this->user_info->company 		= $tokenData->company;
                $this->user_info->incharge 		= $tokenData->incharge;
                $this->user_info->agent 		= $tokenData->agent;
                $this->user_info->expiry_time 	= $tokenData->expiry_time;
            }
        }
    }


    /**
     * @api {get} /v2/product/list 借款方 取得產品列表
     * @apiVersion 0.2.0
     * @apiName GetProductList
     * @apiGroup Product
     * @apiHeader {String} [request_token] 登入後取得的 Request Token
     * @apiDescription 登入狀態下，若已申請產品且申請狀態為未簽約，則提供申請資訊欄位及認證完成資訊。
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccess {Number} id Product ID
     * @apiSuccess {Number} type 類型 1:信用貸款 2:分期付款
     * @apiSuccess {Number} identity 身份 1:學生 2:社會新鮮人
     * @apiSuccess {String} name 名稱
     * @apiSuccess {String} description 簡介
     * @apiSuccess {Object} instalment 可選期數 0:其他
     * @apiSuccess {Object} repayment 可選計息方式 1:等額本息
     * @apiSuccess {Number} loan_range_s 最低借款額度(元)
     * @apiSuccess {Number} loan_range_e 最高借款額度(元)
     * @apiSuccess {Number} interest_rate_s 年利率下限(%)
     * @apiSuccess {Number} interest_rate_e 年利率上限(%)
     * @apiSuccess {Number} charge_platform 平台服務費(%)
     * @apiSuccess {Number} charge_platform_min 平台最低服務費(元)
     * @apiSuccess {Object} target 申請資訊
     * @apiSuccess {Object} certification 認證完成資訊
     * @apiSuccessExample {Object} SUCCESS
     * {
     * 		'result':'SUCCESS',
     * 		'data':{
     * 			'list':[
     * 			{
     * 				'id':1,
     * 				'type':1,
     * 				'identity':1,
     * 				'name':'學生區',
     * 				'description':'學生區',
     * 				'loan_range_s':12222,
     * 				'loan_range_e':14333333,
     * 				'interest_rate_s':12,
     * 				'interest_rate_e':14,
     * 				'charge_platform':0,
     * 				'charge_platform_min':0,
     * 				'instalment': [
     *					3,
     * 				    6,
     * 				    12,
     * 				    18,
     * 				    24
     * 				  ]
     * 				'repayment': [
     *					1
     * 				  ],
     * 				'target':{
     * 					'id':1,
     * 					'target_no': '1803269743',
     * 					'amount':5000,
     * 					'loan_amount':0,
     * 					'status':0,
     * 					'instalment':3,
     * 					'created_at':'1520421572'
     * 				},
     * 				'certification':[
     * 					{
     * 						'id':1,
     * 						'name': '實名認證',
     * 						'description':'實名認證',
     * 						'alias':'id_card',
     * 						'user_status':1
     * 					},
     * 					{
     * 						'id':2,
     * 						'name': '學生身份認證',
     * 						'description':'學生身份認證',
     * 						'alias':'student',
     * 						'user_status':1
     * 					}
     * 				]
     * 			}
     * 			]
     * 		}
     * }
     */

    public function list_get()
    {
        $list2			= [];
        $cproduct_list 	= $this->config->item('product_list');
        $app_product_totallist = $this->config->item('app_product_totallist');
        $visul_id_des          = $this->config->item('visul_id_des');
        $sub_product_list      = $this->config->item('sub_product_list');
        $certification = $this->config->item('certifications');

        if ($this->get('type') == "company") {
            $company = true;
        } else {
            $company = isset($this->user_info->company) ? $this->user_info->company : false;
        }

        // if($company){
        //     $this->load->model('user/judicial_person_model');
        //     $selling_type = $this->judicial_person_model->get_by(array('company_user_id'=>$this->user_info->id))->selling_type;
        // }
        $login = false;
        if(isset($this->user_info->id) && $this->user_info->id && $this->user_info->investor==0){
            $certification_list	= $this->certification_lib->get_status($this->user_info->id,$this->user_info->investor,$this->user_info->company, TRUE, FALSE, FALSE, TRUE);
            $login = true;
        }else{
            $certification_list = [];

            foreach($certification as $key => $value){
                $value['user_status'] 		= null;
                $value['certification_id'] 	= null;
                $certification_list[$key] = $value;
            }
        }

        if(!empty($cproduct_list)){
            $this->load->helper('target');
            $this->load->helper('product');
            if (isset($this->user_info->id))
            {
                $exist_target_submitted = $this->target_lib->exist_approving_target_submitted($this->user_info->id);
            }
            else
            {
                $exist_target_submitted = FALSE;
            }

            foreach($cproduct_list as $key => $value) {
                if (isset($value['status']) && $value['status'] == 0)
                { // 產品關閉
                    continue;
                }
                $certification = [];
                if (isset($this->user_info->id) && $this->user_info->id && $this->user_info->investor == 0) {
                    $targets = $this->target_model->get_many_by(array(
                        'status' => [
                            TARGET_WAITING_APPROVE,
                            TARGET_WAITING_SIGNING,
                            TARGET_WAITING_VERIFY,
                            TARGET_ORDER_WAITING_QUOTE,
                            TARGET_ORDER_WAITING_SIGNING,
                            30,
                            TARGET_BANK_VERIFY,
                            TARGET_BANK_GUARANTEE,
                            TARGET_BANK_LOAN
                        ],
                        'sub_status' => [
                            TARGET_SUBSTATUS_NORNAL,
                            TARGET_SUBSTATUS_SECOND_INSTANCE,
                            TARGET_SUBSTATUS_SECOND_INSTANCE_TARGET,
                            TARGET_SUBSTATUS_WAITING_ASSOCIATES
                        ],
                        'user_id' => $this->user_info->id,
                        'product_id' => $value['id']
                    ));
                    $associatess = $this->target_lib->get_associates_target_list($this->user_info->id);
                    $associatess ? $targets = array_merge($targets, $associatess) : '';
                    if ($targets) {
                        foreach($targets as $tar_list => $tar) {
                            $add_target = [
                                'id' => intval($tar->id),
                                'product_id' => intval($tar->product_id),
                                'sub_product_id' => intval($tar->sub_product_id),
                                'target_no' => $tar->target_no,
                                'status' => intval($tar->status),
                                'sub_status' => intval($tar->sub_status),
                                'amount' => intval($tar->amount),
                                'loan_amount' => intval($tar->loan_amount),
                                'created_at' => intval($tar->created_at),
                                'instalment' => intval($tar->instalment),
                            ];
                            isset($tar->associate) ? $add_target['associate'] = $tar->associate : '';
                            $target[$tar->product_id][$tar->sub_product_id] = $add_target;
                        }
                    }
                }

                $certification = [];
                if (!empty($certification_list)) {
                    $this->load->library('loanmanager/product_lib');
                    foreach ($certification_list as $k => $v) {
                        $truly_failed = $this->certification_lib->certification_truly_failed($exist_target_submitted, $v['certification_id'] ?? 0,
                            USER_BORROWER,
                            is_judicial_product($value['id'])
                        );
                        if ($truly_failed)
                        {
                            $v['user_status'] = NULL;
                            $v['certification_id'] = NULL;
                            $v['content'] = NULL;
                            $v['remark'] = NULL;
                        }

                        $product_certs = $this->product_lib->get_product_certs_by_product($value, []);
                        if (in_array($k, $product_certs)) {
                            !is_bool($v['optional']) ? $v['optional'] = false : '';
                            $certification[] = $v;
                        }
                    }
                }

                $parm = array(
                    'id'        			=> $value['id'],
                    'product_id'        	=> $value['id'],
                    'type' 					=> $value['type'],
                    'identity' 				=> $value['identity'],
                    'name' 					=> $value['name'],
                    'description' 			=> $value['description'],
                    'loan_range_s'			=> $value['apply_range_s'] ?? $value['loan_range_s'],
                    'loan_range_e'			=> $value['apply_range_e'] ?? $value['loan_range_e'],
                    'interest_rate_s'		=> $value['interest_rate_s'],
                    'interest_rate_e'		=> $value['interest_rate_e'],
                    'charge_platform'		=> $value['charge_platform'],
                    'charge_platform_min'	=> $value['charge_platform_min'],
                    'instalment'			=> $value['instalment'],
                    'repayment'				=> $value['repayment'],
                    'sealler'				=> $value['dealer'],
                    'sub_product'		    => $value['sub_product'],
                    'hiddenMainProduct'		=> isset($value['hiddenMainProduct']) ? $value['hiddenMainProduct'] : false,
                    'hiddenSubProduct'		=> isset($value['hiddenSubProduct']) ? $value['hiddenSubProduct'] : false,
                    'checkOwner' => isset($value['checkOwner']) ? $value['checkOwner']: false,
                    'target'                => isset($target[$value['id']][0])?$target[$value['id']][0]:[],
                    'certification'         => $certification,
                );

                if (isset($value['available_company_categories']) && is_array($value['available_company_categories']))
                {
                    $parm['available_company_categories'] = $value['available_company_categories'];
                }

                if($login){
                    if($this->user_info->investor == 0 && $this->user_info->designate==$value['dealer']){
                        $designate = [];
                        $designate?$parm['designate']=$designate:'';
                    }
                }


                //reformat Product for layer2
                $temp[$value['type']][$value['visul_id']][$value['identity']] = $parm;

                unset($parm['sealler'], $parm['sub_product'], $parm['hiddenMainProduct'], $parm['checkOwner']);
                $list[] = $parm;
            }

            //list2
            //layer1
            $hiddenMainProduct = [];
            $type_list = [];
            $designate = [];
            $allow_visul_list = [];
            $showed_list = [];
            $listData = [];
            $targetStatus = [];
            $hiddenList = [STAGE_CER_TARGET];
            foreach ($temp as $key => $t){
                foreach ($t as $key2 => $t2) {
                    if ($company == 1 && isset($t2[3]) || $company == 0) {
                        $sub_product_info = [];
                        $identityList = [];
                        foreach ($t2 as $key3 => $t3) {
                            $t3['hiddenMainProduct'] == true ? $hiddenMainProduct[] = $key2 : '';
                            $data = $t3;
                            unset($data['sealler'], $data['sub_product'], $data['hiddenMainProduct'], $data['checkOwner'],
                                $data['apply_range_s'], $data['apply_range_e'], $data['available_company_categories']);
                            $identityList[$key3] = $data;

                            //sub_products
                            if (count($t3['sub_product']) > 0) {
                                foreach ($t3['sub_product'] as $key4 => $t4) {
                                    if(isset($sub_product_list[$t4]) && !in_array($t4, $hiddenList) && !in_array($sub_product_list[$t4]['visul_id'], $showed_list)){
                                        if (isset($sub_product_list[$t4]['status']) && $sub_product_list[$t4]['status'] == 0)
                                        { // 產品關閉
                                            continue;
                                        }
                                        $allow_visul_list[] = $showed_list[] = $sub_product_list[$t4]['visul_id'];
                                        $sub_product_list[$t4]['name'] = $visul_id_des[$sub_product_list[$t4]['visul_id']]['name'];
                                        $sub_product_list[$t4]['description'] = $visul_id_des[$sub_product_list[$t4]['visul_id']]['description'];
                                        $sub_product_list[$t4]['status'] = $visul_id_des[$sub_product_list[$t4]['visul_id']]['status'];
                                        $sub_product_list[$t4]['banner'] = $visul_id_des[$sub_product_list[$t4]['visul_id']]['banner'];
                                        foreach ($sub_product_list[$t4]['identity'] as $idekey => $ideval) {
                                            $sub_product_list[$t4]['identity'][$idekey]['loan_range_s']	= $sub_product_list[$t4]['identity'][$idekey]['apply_range_s'] ?? $sub_product_list[$t4]['identity'][$idekey]['loan_range_s'];
                                            $sub_product_list[$t4]['identity'][$idekey]['loan_range_e']	= $sub_product_list[$t4]['identity'][$idekey]['apply_range_e'] ?? $sub_product_list[$t4]['identity'][$idekey]['loan_range_e'];
                                            unset($sub_product_list[$t4]['identity'][$idekey]['targetData'],
                                                $sub_product_list[$t4]['identity'][$idekey]['dealer'],
                                                $sub_product_list[$t4]['identity'][$idekey]['secondInstance'],
                                                $sub_product_list[$t4]['identity'][$idekey]['multi_target'],
                                                $sub_product_list[$t4]['identity'][$idekey]['condition_rate'],
                                                $sub_product_list[$t4]['identity'][$idekey]['option_certifications'],
                                                $sub_product_list[$t4]['identity'][$idekey]['backend_option_certifications'],
                                                $sub_product_list[$t4]['identity'][$idekey]['certifications_stage'],
                                                $sub_product_list[$t4]['identity'][$idekey]['targetData'],
                                                $sub_product_list[$t4]['identity'][$idekey]['apply_range_s'],
                                                $sub_product_list[$t4]['identity'][$idekey]['apply_range_e']
                                            );
                                            if(isset($sub_product_list[$t4]['identity'][$idekey]['need_upload_images']))
                                            {
                                                array_walk($sub_product_list[$t4]['identity'][$idekey]['need_upload_images'], function (&$val) {
                                                    unset($val['meta_name']);
                                                });
                                            }
                                            $exp_product = explode(':', $ideval['product_id']);
                                            if (!empty($certification_list)) {
                                                $certification = [];
                                                foreach ($certification_list as $k => $v) {
                                                    $truly_failed = $this->certification_lib->certification_truly_failed($exist_target_submitted, $v['certification_id'] ?? 0,
                                                        USER_BORROWER,
                                                        is_judicial_product($t3['id'])
                                                    );
                                                    if ($truly_failed)
                                                    {
                                                        $v['user_status'] = NULL;
                                                        $v['certification_id'] = NULL;
                                                        $v['content'] = NULL;
                                                        $v['remark'] = NULL;
                                                    }
                                                    if (in_array($k, $sub_product_list[$t4]['identity'][$idekey]['certifications'])) {
                                                        $certification[] = $v;
                                                    }
                                                }
                                                $sub_product_list[$t4]['identity'][$idekey]['certifications'] = $certification;
                                            }
                                            $targetInfo = isset($target[$exp_product[0]][$exp_product[1]]) ? $target[$exp_product[0]][$exp_product[1]] : [];
                                            $sub_product_list[$t4]['identity'][$idekey]['target'] = $targetInfo;
                                            $listData[$sub_product_list[$t4]['visul_id']][$idekey] = $sub_product_list[$t4]['identity'][$idekey];
                                            $listData[$sub_product_list[$t4]['visul_id']][$idekey]['status'] = count($listData[$sub_product_list[$t4]['visul_id']][$idekey]['target']) > 0 ? $listData[$sub_product_list[$t4]['visul_id']][$idekey]['target']['status'] : -1;
                                            $targetStatus[$sub_product_list[$t4]['visul_id']] = count($targetInfo) > 0 ? $targetInfo['status'] : -1;
                                        }

                                        isset($sub_product_info[0]['visul_id'])
                                        && $sub_product_info[0]['visul_id'] == $sub_product_list[$t4]['visul_id']
                                            ? '' : !$t3['hiddenSubProduct'] ? $sub_product_info[] = $sub_product_list[$t4] : '';
                                    }
                                }
                            }
                        }

                        $type_list['type' . $key][] = [
                            'visul_id' => $key2,
                            'name' => $visul_id_des[$key2]['name'],
                            'identity' => !in_array($key2,$hiddenMainProduct)?$identityList:[],
                            'description' => $visul_id_des[$key2]['description'],
                            'status' => $visul_id_des[$key2]['status'],
                            'banner' => $visul_id_des[$key2]['banner'],
                            'sub_products' => $sub_product_info,
                        ];
                    }
                }
            }
            $total_list = [];
            $identity = $company?'company':'nature';
            foreach ($app_product_totallist[$identity] as $id) {
                if (isset($visul_id_des['TO'.$id])) {
                    $total_list[] = [
                        'visul' => $id,
                        'name' => $visul_id_des['TO'.$id]['name'],
                        'icon' => $visul_id_des['TO'.$id]['icon'],
                        'bannerThumbnail' => $visul_id_des['TO' . $id]['bannerThumbnail'] ?? '',
                        'bannerFull' => $visul_id_des['TO' . $id]['bannerFull'] ?? '',
                        'identity' => $listData[$id],
                        'description' => $visul_id_des['TO'.$id]['description'],
                        'url' => $visul_id_des['TO'.$id]['url'],
                        'status' => $targetStatus[$id],
                    ];
                }
            }
            $parm2 = array(
                'total_list' 					=> $total_list,
                'product_list' 					=> $type_list,
            );
            $list2 = $parm2;
        }

        $this->response(array('result' => 'SUCCESS','data' => [
            'list'  => $list,
            'list2' => $list2,
        ]));
    }

    /**
     * @api {get} /v2/product/info/:id 借款方 取得產品資訊
     * @apiVersion 0.2.0
     * @apiName GetProductInfo
     * @apiGroup Product
     * @apiHeader {String} [request_token] 登入後取得的 Request Token
     * @apiParam {Number} id 產品ID
     * @apiParam {Number} target_id Targets ID
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccess {String} id Product ID
     * @apiSuccess {Number} sub_product_id 子產品 ID
     * @apiSuccess {String} type 類型 1:信用貸款 2:分期付款
     * @apiSuccess {Number} identity 身份 1:學生 2:社會新鮮人
     * @apiSuccess {String} name 名稱
     * @apiSuccess {String} description 簡介
     * @apiSuccess {String} loan_range_s 最低借款額度(元)
     * @apiSuccess {String} loan_range_e 最高借款額度(元)
     * @apiSuccess {String} interest_rate_s 年利率下限(%)
     * @apiSuccess {String} interest_rate_e 年利率上限(%)
     * @apiSuccess {String} charge_platform 平台服務費(%)
     * @apiSuccess {String} charge_platform_min 平台最低服務費(元)
     * @apiSuccess {Object} instalment 可選期數 0:其他
     * @apiSuccess {Object} repayment 可選計息方式 1:等額本息
     * @apiSuccess {NULL/Number} remain_amount 可用額度
     * @apiSuccess {NULL/Number} target_id 有額度的Targets ID
     * @apiSuccessExample {Object} SUCCESS
     * {
     * 	'result': 'SUCCESS',
     * 		'data': {
     * 			'id': 1,
     * 			'type': 1,
     * 			'identity': 1,
     * 			'name': '學生貸',
     * 			'description': '\r\n普匯學生貸\r\n計畫留學、創業或者實現更多理想嗎？\r\n需要資金卻無法向銀行聲請借款嗎？\r\n普匯陪你一起實現夢想',
     * 			'loan_range_s': 5000,
     * 			'loan_range_e': 120000,
     * 			'interest_rate_s': 5,
     * 			'interest_rate_e': 20,
     * 			'charge_platform': 3,
     * 			'charge_platform_min': 500,
     * 			'instalment': [
     * 				3,
     * 				6,
     * 				12,
     * 				18,
     * 				24
     * 			],
     * 			'repayment': [
     * 				1
     * 			],
     *          'remain_amount': 10000,
     *          'target_id': 100574
     * 		}
     * }
     *
     * @apiUse TokenError
     * @apiUse BlockUser
     * @apiUse IsInvestor
     * @apiUse IsCompany
     *
     * @apiError 401 產品不存在
     * @apiErrorExample {Object} 401
     *     {
     *       'result': 'ERROR',
     *       'error': '401'
     *     }
     */
    public function info_get($id, $target_id)
    {
        if($id){
            $exp_product  = explode('%3A',$id);
            $id = $exp_product[0];
            $product_list = $this->config->item('product_list');
            $sub_product_list = $this->config->item('sub_product_list');
            $sub_product_id = (int) ($exp_product[1] ?? 0);

            if(isset($product_list[$id])){
                $product = $product_list[$id];
                if(count($product['sub_product']) > 0 && isset($sub_product_list[$sub_product_id])
                    || $product['hiddenMainProduct']
                    || $sub_product_id && !in_array($sub_product_id,$product['sub_product'])){
                    if($this->is_sub_product($product,$sub_product_id)){
                        $product = $this->trans_sub_product($product,$sub_product_id);
                    }
                    else{
                        $this->response(array('result' => 'ERROR','error' => PRODUCT_NOT_EXIST ));
                    }
                }

                $target = $this->target_model->get_by(['id' => $target_id]);
                if (empty($target))
                {
                    $this->response(['result' => 'ERROR', 'error' => TARGET_APPLY_NOT_EXIST]);
                }
                $this->load->library('credit_lib');
                $chk_credit = $this->credit_lib->get_remain_amount($this->user_info->id, $product['id'], $sub_product_id, $target_id);
                $chk_data = ['remain_amount' => NULL, 'target_id' => NULL];
                if ($chk_credit['credit_amount'] > 0 && $chk_credit['instalment'] == $target->instalment)
                {
                    $chk_data['remain_amount'] = $chk_credit['user_available_amount'];
                    $chk_target = $this->target_model->order_by('created_at', 'DESC')->get_by([
                        'user_id' => $this->user_info->id,
                        'product_id' => $product['id'],
                        'sub_product_id' => $sub_product_id,
                        'loan_amount>' => 0,
                        'id !=' => $target_id,
                        'status' => TARGET_WAITING_SIGNING
                    ]);
                    if (isset($chk_target->id))
                    {
                        $chk_data['target_id'] = (int) $chk_target->id;
                    }
                }

                $data = array(
                    'id' 					=> $product['id'],
                    'sub_product_id'        => $sub_product_id,
                    'type' 					=> $product['type'],
                    'identity' 				=> $product['identity'],
                    'name' 					=> $product['name'],
                    'description' 			=> $product['description'],
                    'loan_range_s'			=> $product['loan_range_s'],
                    'loan_range_e'			=> $product['loan_range_e'],
                    'interest_rate_s'		=> $product['interest_rate_s'],
                    'interest_rate_e'		=> $product['interest_rate_e'],
                    'charge_platform'		=> $product['charge_platform'],
                    'charge_platform_min'	=> $product['charge_platform_min'],
                    'instalment'			=> $product['instalment'],
                    'repayment'				=> $product['repayment'],
                );
                $data = array_merge($data, $chk_data);
                $this->response(array('result' => 'SUCCESS','data' => $data ));
            }
        }
        $this->response(array('result' => 'ERROR','error' => PRODUCT_NOT_EXIST ));
    }

    public function blackList_get()
    {
        $input 		= $this->input->get(NULL, TRUE);
        $user_id = $this->user_info->id;

        $fields = ['product_id', 'sub_product_id'];
        foreach ($fields as $field) {
            if (!isset($input[$field])) {
                $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
            }
        }

        // 確認黑名單結果是否禁止申貸
        $this->load->library('brookesia/black_list_lib');
        $is_user_blocked = $this->black_list_lib->check_user($user_id, CHECK_APPLY_PRODUCT);

        // 子系統若無回應不處理
        if (isset($is_user_blocked['isUserBlocked']) && $is_user_blocked['isUserBlocked'])
        {
            $this->black_list_lib->add_block_log($is_user_blocked);
            $this->response(
                [
                    'result'   => 'SUCCESS',
                    'data'     => [
                        'valid'  => FALSE,
                        'text'   => $this->black_list_lib->get_black_list_text($user_id, $input['product_id'], $input['sub_product_id'])
                    ]
                ]
            );
        }

        $this->response(
            [
                'result'   => 'SUCCESS',
                'data'     => [
                    'valid'  => TRUE
                ]
            ]
        );

    }

    /**
     * @api {post} /v2/product/apply 借款方 申請借款
     * @apiVersion 0.2.0
     * @apiName PostProductApply
     * @apiGroup Product
     * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiDescription 此API只支援信用貸款類型產品，產品簽約前一次只能一案。
     *
     * @apiParam {Number} product_id 產品ID
     * @apiParam {Number} amount 借款金額
     * @apiParam {Number} instalment 申請期數
     * @apiParam {String{0..128}} [reason] 借款原因
     * @apiParam {String{0..16}} [promote_code] 邀請碼
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccess {Number} target_id Targets ID
     * @apiSuccessExample {Object} SUCCESS
     * {
     * 		'result':'SUCCESS',
     * 		'data':{
     * 			'target_id': 1
     * 		}
     * }
     *
     * @apiUse InputError
     * @apiUse InsertError
     * @apiUse TokenError
     * @apiUse BlockUser
     * @apiUse IsInvestor
     * @apiUse IsCompany
     *
     * @apiError 401 產品不存在
     * @apiErrorExample {Object} 401
     *     {
     *       'result': 'ERROR',
     *       'error': '401'
     *     }
     *
     * @apiError 402 超過此產品可申請額度
     * @apiErrorExample {Object} 402
     *     {
     *       'result': 'ERROR',
     *       'error': '402'
     *     }
     *
     * @apiError 403 不支援此期數
     * @apiErrorExample {Object} 403
     *     {
     *       'result': 'ERROR',
     *       'error': '403'
     *     }
     *
     * @apiError 408 同產品重複申請
     * @apiErrorExample {Object} 408
     *     {
     *       'result': 'ERROR',
     *       'error': '408'
     *     }
     *
     * @apiError 410 產品類型錯誤
     * @apiErrorExample {Object} 410
     *     {
     *       'result': 'ERROR',
     *       'error': '410'
     *     }
     *
     * @apiError 424 產品已無額度，不起新案
     * @apiErrorExample {Object} 424
     *     {
     *       'result': 'ERROR',
     *       'error': '424'
     *
     * @apiError 426 黑名單禁止申貸錯誤
     * @apiErrorExample {Object} 426
     *     {
     *       'result': 'ERROR',
     *       'error': '426',
     *       'data': {
     *              'text': 'App端禁止申貸呈現文字'
     *          }
     *     }
     *
     */
    public function apply_post()
    {
        $input 		= $this->input->post(NULL, TRUE);
        $amount = isset($input['amount']) ? $input['amount'] : 0;
        $user_id = $this->user_info->id;

        $exp_product = explode(':', $input['product_id']);
        if ( ! empty($exp_product[0]))
        {
            $product_id = $exp_product[0];
            $sub_product_id = $exp_product[1] ?? 0;
            $this->load->library('loanmanager/product_lib');
            $product = $this->product_lib->get_product_info($product_id);
            $sub_product_list = $this->product_lib->get_sub_product_list();
        }
        else
        {
            $product = [];
        }

        if ($product) {

            // 申請名校貸者，先檢查是否已提交學生驗證、且符合名校資格
            if (isset($product['sub_product']) && SUBPRODUCT_INTELLIGENT_STUDENT == $sub_product_id)
            {
                $this->load->library('certification_lib');
                $certification_info = $this->certification_lib->get_certification_info($user_id, CERTIFICATION_STUDENT);
                if (isset($certification_info->content['school']))
                {
                    $famous_school_list = $this->config->item('famous_school_list');
                    $school_short_name = array_search($certification_info->content['school'], $famous_school_list);

                    if ( ! isset($famous_school_list[strtoupper($school_short_name)]))
                    {
                        $this->response([
                            'result' => 'ERROR',
                            'error' => PRODUCT_STUDENT_NOT_INTELLIGENT
                        ]);
                    }
                }
            }

            //檢核身分
            if($product['identity'] == 3 && $this->user_info->company != 1 && (!isset($product['checkOwner']) || !$product['checkOwner'])){
                $this->response(array('result' => 'ERROR', 'error' => NOT_COMPANY));
            }else if($product['identity'] != 3 && $this->user_info->company == 1){
                $this->response(array('result' => 'ERROR', 'error' => IS_COMPANY));
            }

            //檢核經銷商狀態
            if($this->user_info->company == 1 && $product['type'] == 2){
                if (!in_array(0, $product['dealer'])) {
                    $this->load->model('user/judicial_person_model');
                    $judicial_person = $this->judicial_person_model->get_by(array(
                        'selling_type' => count($product['dealer']) != 0 ? $product['dealer'] : '',
                        'company_user_id' => $user_id,
                        'cooperation' => 1,
                        'status' => 1,
                    ));
                    if(!$judicial_person){
                        $this->response(array('result' => 'ERROR', 'error' => NOT_DEALER));
                    }
                }
            }

            //使用副產品資料
            if (count($product['sub_product']) > 0 && isset($sub_product_list[$sub_product_id])
                || $product['hiddenMainProduct']
                || $sub_product_id && !in_array($sub_product_id, $product['sub_product'])) {
                if($this->is_sub_product($product,$sub_product_id)){
                    $product = $this->trans_sub_product($product,$sub_product_id);
                }else {
                    $this->response(array('result' => 'ERROR', 'error' => PRODUCT_NOT_EXIST));
                }
            }

            //若產品關閉不給申請
            if($product['status'] == 0){
                $this->response(array('result' => 'ERROR', 'error' => PRODUCT_CLOSE));
            }

            // 由於 input 需帶入到成立案件的 function，故需把 target meta 寫入 input 內
            // 但不能由使用者自行輸入，若有自己輸入則回傳錯誤
            if (isset($input['target_meta']))
            {
                $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
            }
            else
            {
                $input['target_meta'] = [];
            }

            // 申貸產品需上傳照片
            if ( ! empty($product['need_upload_images']))
            {
                $this->load->model('log/log_image_model');

                foreach ($product['need_upload_images'] as $validation_image)
                {
                    $arg_name = $validation_image['argument_name'];
                    if($validation_image['optional'] === FALSE && !isset($input[$arg_name]))
                    {
                        $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
                    }

                    $list = [];
                    $image_ids = explode(',', $input[$arg_name]);
                    if ( ! empty($image_ids))
                    {
                        $image_ids = array_filter($image_ids);
                        $image_ids = array_map('intval', $image_ids);

                        $list = $this->log_image_model->get_many_by([
                            'id' => $image_ids,
                            'user_id' => $user_id,
                        ]);
                    }

                    if ( empty($image_ids) || count($list) != count($image_ids))
                    {
                        $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
                    }
                    else
                    {
                        $input['target_meta'][] = [
                            'meta_key' => $validation_image['meta_name'],
                            'meta_value' => json_encode($image_ids)
                        ];
                    }
                }
            }

            // 申貸產品需選擇職業(公司類型)
            if ( ! empty($product['available_company_categories']))
            {
                if (isset($input['company_category']))
                {
                    if ( ! array_key_exists($input['company_category'], $product['available_company_categories']))
                    {
                        $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => 'The parameter company_category is not correct.'));
                    }
                    $input['target_meta'][] = [
                        'meta_key' => TARGET_META_COMPANY_CATEGORY_NUMBER,
                        'meta_value' => (int)$input['company_category']
                    ];
                }
            }

            // 外匯車判斷
            if($product['id'] == PRODUCT_FOREX_CAR_VEHICLE){
                $input['instalment'] = 90;
                if($sub_product_id == 2){
                    $amount = $input['purchase_cost'] + $input['fee_cost'];
                    $input['instalment'] = 180;
                }
            }


            if (!isset($input['target_id'])) {
                // 分期期數判斷
                if (!in_array($input['instalment'], $product['instalment'])) {
                    $this->response(array('result' => 'ERROR', 'error' => PRODUCT_INSTALMENT_ERROR));
                }
                // 貸款金額判斷
                if (($amount < ($product['apply_range_s'] ?? $product['loan_range_s']) || $amount > ($product['apply_range_e'] ?? $product['loan_range_e'])) && $product['type'] != 2) {
                    $this->response(array('result' => 'ERROR', 'error' => PRODUCT_AMOUNT_RANGE));
                }
            }

            if (isset($product['repayment']))
            {
                if (isset($input['repayment']) && in_array($input['repayment'], $product['repayment']))
                {
                    $repayment = $input['repayment'];
                }
                else
                {
                    $repayment = $product['repayment'][0];
                }
            }
            else
            {
                $repayment = 1;
            }

            // 社交跑分可能異常 重新驗證社交 並退掉分數
            $this->load->model('user/user_certification_model');
            $cer = $this->user_certification_model->get_by(
                [
                    'user_id'          => $user_id,
                    'certification_id' => CERTIFICATION_SOCIAL,
                    'status'           => CERTIFICATION_STATUS_SUCCEED,
                    'updated_at < '    => 1643299200
                ]
            );
            if (isset($cer))
            {
                $this->user_certification_model->update_by(
                    [
                        'id' => $cer->id
                    ],
                    [
                        'status' => CERTIFICATION_STATUS_PENDING_TO_VALIDATE
                    ]
                );
                $this->load->model('loan/credit_model');
                $this->credit_model->update_by(
                    [
                        'product_id'       => $product['id'],
                        'user_id'          => $user_id,
                        'status'           => 1,
                        'points > '        => 0
                    ],
                    [
                        'status'           => 0
                    ]
                );
            }


            $param		= [
                'product_id' => $product['id'],
                'sub_product_id' => $sub_product_id,
                'user_id' => $user_id,
                'repayment' => $repayment,
                'damage_rate' => LIQUIDATED_DAMAGES,
            ];

            $method = 'type'.$product['type'].'_apply';

            $this->load->library('credit_lib');
            $chk_credit = $this->credit_lib->get_remain_amount($user_id, $product['id'], $sub_product_id);

            // todo: 原本只要還有核可額度，就可以直接過件，但現在要暫時將入口關掉
            goto LOCK_END;

            if ($chk_credit['credit_amount'] > 0 && $chk_credit['instalment'] == $input['instalment'] && $chk_credit['user_available_amount'] >= $product['loan_range_s'])
            {
                // 有效期內的核可額度(條件：同產品、同期間)
                if ($chk_credit['remain_amount'] >= $input['amount'])
                { // 該產品有未使用額度
                    $param['status'] = TARGET_WAITING_SIGNING;
                    $param['loan_amount'] = (int) (floor($input['amount'] / 1000) * 1000);
                }
                else
                { // 該產品已無使用額度
                    $param['status'] = TARGET_WAITING_SIGNING;
                    $param['loan_amount'] = (int) (floor($chk_credit['remain_amount'] / 1000) * 1000);
                }
                $param['credit_level'] = $chk_credit['credit_level'];
                if ( ! empty($param['loan_amount']) && $param['loan_amount'] > $product['loan_range_e'])
                {
                    $param['loan_amount'] = $product['loan_range_e'];
                }
            }

            LOCK_END:

            // 舊版一鍵送出流程殘留的狀態，在起案的時候應改回待驗證
            $this->load->model('user/user_certification_model');
            $cert_ids = array_column($this->user_certification_model->as_array()->get_many_by(['user_id' => $user_id,
                'status' => [CERTIFICATION_STATUS_PENDING_TO_AUTHENTICATION, CERTIFICATION_STATUS_AUTHENTICATED]]), 'id');
            if ( ! empty($cert_ids))
            {
                $this->user_certification_model->update_by(['id' => $cert_ids],
                    ['status' => CERTIFICATION_STATUS_PENDING_TO_VALIDATE, 'sys_check' => 0]);

                $insert_params = [];
                foreach ($cert_ids as $cert_id)
                {
                    $insert_params[] = [
                        'user_certification_id' => $cert_id,
                        'status' => CERTIFICATION_STATUS_PENDING_TO_VALIDATE,
                        'change_admin' => 0
                    ];
                }
                $this->load->model('log/log_usercertification_model');
                $this->log_usercertification_model->insert_many($insert_params);
            }
            // 正式環境下檢查實名認證，資訊不對就退認證項
            if (ENVIRONMENT === 'production')
            {
                $check_id_card = FALSE;
                $identity_cert = $this->user_certification_model->get_by([
                    'investor' => BORROWER,
                    'status' => CERTIFICATION_STATUS_SUCCEED,
                    'user_id' => $user_id,
                    'certification_id' => CERTIFICATION_IDENTITY
                ]);
                if ($identity_cert)
                {
                    // Avoid checking for the same target too many times.
                    $this->load->model('log/log_integration_model');
                    $api_verify_log = $this->log_integration_model->order_by('created_at', 'DESC')->get_by([
                        'user_certification_id' => $identity_cert->id
                    ]);
                    if ( ! empty($api_verify_log))
                    {
                        $current_time = date('Y-m-d H:i:s');
                        $one_day_ago = date("Y-m-d H:i:s", strtotime($current_time.' -1 day'));
                        if ($api_verify_log->created_at < $one_day_ago)
                        {
                            $check_id_card = TRUE;
                        }
                    }
                    if ($check_id_card)
                    {
                        $identity_content = json_decode($identity_cert->content, TRUE);
                        $remark = json_decode($identity_cert->remark, TRUE);
                        $err_msg = $remark['error'] ?? '';
                        $result = $this->certification_lib->verify_id_card_info(
                            $identity_cert->id, $identity_content, $err_msg, $remark['OCR'] ?? []
                        );

                        // Update user_certification.
                        $to_update = ['content' => json_encode($identity_content, JSON_INVALID_UTF8_IGNORE)];
                        if ($err_msg)
                        {
                            $remark['error'] = $err_msg;
                            $to_update['remark'] = json_encode($remark, JSON_INVALID_UTF8_IGNORE);
                        }
                        $this->user_certification_model->update($identity_cert->id, $to_update);

                        if ($result[0] && $result[1])  // Existed id card info is wrong.
                        {
                            $this->load->model('log/log_usercertification_model');
                            $this->log_usercertification_model->insert([
                                'user_certification_id'	=> $identity_cert->id,
                                'status'				=> CERTIFICATION_STATUS_FAILED,
                                'change_admin'			=> SYSTEM_ADMIN_ID,
                            ]);
                            $rs = \Certification\Cert_identity::set_failed_for_apply($identity_cert, $result[2]);
                            if ($rs === TRUE)
                            {
                                $this->user_certification_model->update($identity_cert->id, [
                                    'certificate_status' => CERTIFICATION_CERTIFICATE_STATUS_SENT
                                ]);
                            }
                            else
                            {
                                log_message('error', "實名認證 user_certification {$identity_cert->id} 退件失敗");
                            }
                        }
                        elseif ($result[0] === FALSE && $result[1] === TRUE)
                        {
                            $this->load->model('log/log_usercertification_model');
                            $this->log_usercertification_model->insert([
                                'user_certification_id' => $identity_cert->id,
                                'status' => CERTIFICATION_STATUS_PENDING_TO_REVIEW,
                                'change_admin' => SYSTEM_ADMIN_ID,
                            ]);
                            $cert_helper = \Certification\Certification_factory::get_instance_by_model_resource($identity_cert);

                            $cert_helper->result->addMessage(IdentityCertificationResult::$RIS_NO_RESPONSE_MESSAGE . '，需人工驗證', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                            $remark = $cert_helper->remark;
                            $remark['verify_result'] = $cert_helper->result->getAllMessage(MessageDisplay::Backend);
                            $remark['verify_result_json'] = $cert_helper->result->jsonDump();

                            $rs = $cert_helper->set_review(TRUE, IdentityCertificationResult::$RIS_NO_RESPONSE_MESSAGE);
                            if ($rs === TRUE)
                            {
                                $this->user_certification_model->update($identity_cert->id, [
                                    'certificate_status' => CERTIFICATION_CERTIFICATE_STATUS_SENT,
                                    'remark' => json_encode($remark, JSON_INVALID_UTF8_IGNORE | JSON_UNESCAPED_UNICODE),
                                ]);
                            }
                            else
                            {
                                log_message('error', "實名認證 user_certification {$identity_cert->id} 轉人工失敗");
                            }
                        }
                    }
                }
            }
            if(method_exists($this, $method)){
                $this->$method($param,$product,$input);
            }

        }
        $this->response(['result' => 'ERROR', 'error' => PRODUCT_NOT_EXIST]);
    }

    /**
     * @api {post} /v2/product/signing 借款方 申請簽約
     * @apiVersion 0.2.0
     * @apiName PostProductSigning
     * @apiGroup Product
     * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiDescription 此API只支援信用貸款類型產品。
     *
     * @apiParam {Number} target_id Targets ID
     * @apiParam {file} person_image 本人照
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      'result': 'SUCCESS'
     *    }
     *
     * @apiUse InputError
     * @apiUse InsertError
     * @apiUse TokenError
     * @apiUse BlockUser
     * @apiUse IsInvestor
     * @apiUse IsCompany
     *
     * @apiError 401 產品不存在
     * @apiErrorExample {Object} 401
     *     {
     *       'result': 'ERROR',
     *       'error': '401'
     *     }
     *
     * @apiError 404 此申請不存在
     * @apiErrorExample {Object} 404
     *     {
     *       'result': 'ERROR',
     *       'error': '404'
     *     }
     *
     * @apiError 405 對此申請無權限
     * @apiErrorExample {Object} 405
     *     {
     *       'result': 'ERROR',
     *       'error': '405'
     *     }
     *
     * @apiError 407 目前狀態無法完成此動作
     * @apiErrorExample {Object} 407
     *     {
     *       'result': 'ERROR',
     *       'error': '407'
     *     }
     *
     * @apiError 410 產品類型錯誤
     * @apiErrorExample {Object} 410
     *     {
     *       'result': 'ERROR',
     *       'error': '410'
     *     }
     *
     * @apiError 202 未通過所需的驗證
     * @apiErrorExample {Object} 202
     *     {
     *       'result': 'ERROR',
     *       'error': '202'
     *     }
     *
     * @apiError 203 未綁定金融帳號
     * @apiErrorExample {Object} 203
     *     {
     *       'result': 'ERROR',
     *       'error': '203'
     *     }
     *
     * @apiError 206 人臉辨識不通過
     * @apiErrorExample {Object} 206
     *     {
     *       'result': 'ERROR',
     *       'error': '206'
     *     }
     *
     * @apiError 208 未滿18歲或大於35歲
     * @apiErrorExample {Object} 208
     *     {
     *       'result': 'ERROR',
     *       'error': '208'
     *     }
     *
     */
    public function signing_post()
    {
        $this->load->library('S3_upload');
        $this->load->model('user/user_bankaccount_model');
        if (!empty($this->load)) {
            $this->load->library('Certification_lib');
        }
        $input 		= $this->input->post(NULL, TRUE);
        $user_id 	= $this->user_info->id;
        $param		= ['status'=>2];

        //必填欄位
        if (empty($input['target_id'])) {
            $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
        }

        $target 	= $this->target_model->get($input['target_id']);
        if(!empty($target)){

            if($target->user_id != $user_id){
                $this->response(array('result' => 'ERROR','error' => APPLY_NO_PERMISSION ));
            }

            if($target->status != 1 || !in_array($target->sub_status, [0, 10])){
                $this->response(array('result' => 'ERROR','error' => APPLY_STATUS_ERROR ));
            }

            $product_list = $this->config->item('product_list');
            $product = $product_list[$target->product_id];
            $sub_product_id = $target->sub_product_id;
            if($this->is_sub_product($product,$sub_product_id)){
                $product = $this->trans_sub_product($product,$sub_product_id);
            }

            if($product){
                $method = 'type'.$product['type'].'_signing';
                log_message('debug', "-- Before $method: " . time() . ' --');
                if(method_exists($this, $method)){
                    $this->$method($param,$product,$input,$target);
                }
            }
            $this->response(array('result' => 'ERROR','error' => PRODUCT_NOT_EXIST ));
        }
        $this->response(array('result' => 'ERROR','error' => APPLY_NOT_EXIST ));
    }

    /**
     * @api {get} /v2/product/applylist 借款方 申請紀錄列表
     * @apiVersion 0.2.0
     * @apiName GetProductApplylist
     * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiGroup Product
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccess {Number} id Targets ID
     * @apiSuccess {String} target_no 案號
     * @apiSuccess {Number} product_id Product ID
     * @apiSuccess {Number} user_id User ID
     * @apiSuccess {Number} amount 申請金額
     * @apiSuccess {Number} loan_amount 核准金額
     * @apiSuccess {Number} platform_fee 平台服務費
     * @apiSuccess {Number} interest_rate 年化利率
     * @apiSuccess {Number} instalment 期數 0:其他
     * @apiSuccess {Number} repayment 計息方式 1:等額本息
     * @apiSuccess {String} reason 借款原因
     * @apiSuccess {String} remark 備註
     * @apiSuccess {Number} delay 是否逾期 0:無 1:逾期中
     * @apiSuccess {Number} status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
     * @apiSuccess {Number} sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還
     * @apiSuccess {Number} created_at 申請日期
     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		'result':'SUCCESS',
     * 		'data':{
     * 			'list':[
     * 			{
     * 				'id': 5,
     * 				'target_no': 'STN2019010484186',
     * 				'product_id': 1,
     * 				'user_id': 1,
     * 				'amount': 5000,
     * 				'loan_amount': 0,
     * 				'platform_fee': 500,
     * 				'interest_rate': 0,
     * 				'instalment': 3,
     * 				'repayment': 1,
     * 				'reason': '',
     * 				'remark': '系統自動取消',
     * 				'delay': 0,
     * 				'status': 9,
     * 				'sub_status': 0,
     * 				'created_at': 1546591486
     * 			}
     * 			]
     * 		}
     *    }
     *
     * @apiUse TokenError
     * @apiUse BlockUser
     * @apiUse IsInvestor
     * @apiUse IsCompany
     *
     */
    public function applylist_get()
    {
        $this->load->library('Subloan_lib');
        $input 				       = $this->input->get(NULL, TRUE);
        $product_list = $this->config->item('product_list');
        $user_id 			       = $this->user_info->id;
        $investor 			       = $this->user_info->investor;
        $param				       = ['user_id'=> $user_id,'status !='=> 8];
        $targets 			       = $this->target_model->get_many_by($param);
        $list				       = [];

        $associatess = $this->target_lib->get_associates_target_list($this->user_info->id, false, true);
        $associatess ? $targets = array_merge($associatess, $targets) : '';
        if(!empty($targets)){
            foreach($targets as $key => $value){
                $product = isset($product_list[$value->product_id]) ? $product_list[$value->product_id] : $product_list[1];
                $sub_product_id = $value->sub_product_id;
                $product_name = $product['name'];
                if($this->is_sub_product($product,$sub_product_id)){
                    $product = $this->trans_sub_product($product,$sub_product_id);
                    $product_name = $product['name'];
                }
                $subloan_target_status     = '';
                $subloan_target_sub_status = '';
                if($value->sub_status == 1){
                    $subloan     = $this->subloan_lib->get_subloan($value);
                    if($subloan){
                        $new_target  = $this->target_model->get($subloan->new_target_id);
                        $subloan_target_status     = $new_target->status;
                        $subloan_target_sub_status = $new_target->sub_status;
                    }
                }

                $reason = $value->reason;
                $json_reason = json_decode($reason);
                if(isset($json_reason->reason)){
                    $reason = $json_reason->reason.' - '.$json_reason->reason_description;
                }

                $list[] = [
                    'id' 				         => intval($value->id),
                    'target_no' 		         => $value->target_no,
                    'product_name' => $product_name,
                    'product_id' 		         => intval($value->product_id),
                    'sub_product_id' 		     => intval($value->sub_product_id),
                    'user_id' 			         => intval($value->user_id),
                    'amount' 			         => intval($value->amount),
                    'loan_amount' 		         => intval($value->loan_amount),
                    'platform_fee' 		         => intval($value->platform_fee),
                    'interest_rate' 	         => floatval($value->interest_rate),
                    'instalment' 		         => intval($value->instalment),
                    'repayment' 		         => intval($value->repayment),
                    'reason' 			         => $reason,
                    'remark' 			         => $value->remark,
                    'delay' 			         => intval($value->delay),
                    'status' 			         => intval($value->status),
                    'sub_status' 		         => intval($value->sub_status),
                    'certificate_status' 		 => intval($value->certificate_status),
                    'associate' 		         => (isset($value->associate) ? $value->associate : false),
                    'subloan_target_status'      => intval($subloan_target_status),
                    'subloan_target_sub_status'  => intval($subloan_target_sub_status),
                    'created_at' 		         => intval($value->created_at),
                    'verify_status' => $this->chk_target_verifying($value->target_data ?? '') ? 1 : 0,
                ];

            }
        }
        $this->response(['result' => 'SUCCESS','data' => ['list' => $list] ]);
    }

    /**
     * @api {get} /v2/product/applyinfo/:id 借款方 申請紀錄資訊
     * @apiVersion 0.2.0
     * @apiName GetProductApplyinfo
     * @apiGroup Product
     * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiDescription 預計還款計畫欄位只在待簽約時出現。
     *
     * @apiParam {Number} id Targets ID
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccess {Number} id Target ID
     * @apiSuccess {String} target_no 案號
     * @apiSuccess {Number} product_id Product ID
     * @apiSuccess {Number} sub_product_id Sub Product ID
     * @apiSuccess {Number} user_id User ID
     * @apiSuccess {Number} amount 申請金額
     * @apiSuccess {Number} loan_amount 核准金額
     * @apiSuccess {Number} platform_fee 平台服務費
     * @apiSuccess {Number} interest_rate 核可利率
     * @apiSuccess {Number} instalment 期數 0:其他
     * @apiSuccess {Number} repayment 計息方式 1:等額本息
     * @apiSuccess {String} reason 借款原因
     * @apiSuccess {String} remark 備註
     * @apiSuccess {Number} delay 是否逾期 0:無 1:逾期中
     * @apiSuccess {Number} delay_days 逾期天數
     * @apiSuccess {Number} status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
     * @apiSuccess {Number} sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還
     * @apiSuccess {Number} created_at 申請日期
     * @apiSuccess {String} contract 合約內容
     * @apiSuccess {Object} certification 認證完成資訊
     * @apiSuccess {Object} credit 信用資訊
     * @apiSuccess {String} credit.level 信用指數
     * @apiSuccess {String} credit.points 信用分數
     * @apiSuccess {String} credit.amount 總信用額度
     * @apiSuccess {String} credit.created_at 核准日期
     * @apiSuccess {Object} amortization_schedule 預計還款計畫
     * @apiSuccess {String} amortization_schedule.amount 借款金額
     * @apiSuccess {String} amortization_schedule.instalment 借款期數
     * @apiSuccess {String} amortization_schedule.rate 年利率
     * @apiSuccess {String} amortization_schedule.date 起始時間
     * @apiSuccess {String} amortization_schedule.total_payment 每月還款金額
     * @apiSuccess {String} amortization_schedule.leap_year 是否為閏年
     * @apiSuccess {String} amortization_schedule.year_days 本年日數
     * @apiSuccess {String} amortization_schedule.XIRR XIRR
     * @apiSuccess {String} amortization_schedule.schedule 還款計畫
     * @apiSuccess {String} amortization_schedule.schedule.instalment 第幾期
     * @apiSuccess {String} amortization_schedule.schedule.repayment_date 還款日
     * @apiSuccess {String} amortization_schedule.schedule.days 本期日數
     * @apiSuccess {String} amortization_schedule.schedule.remaining_principal 剩餘本金
     * @apiSuccess {String} amortization_schedule.schedule.principal 還款本金
     * @apiSuccess {String} amortization_schedule.schedule.interest 還款利息
     * @apiSuccess {String} amortization_schedule.schedule.total_payment 本期還款金額
     * @apiSuccess {String} amortization_schedule.total 還款總計
     * @apiSuccess {String} amortization_schedule.total.principal 本金
     * @apiSuccess {String} amortization_schedule.total.interest 利息
     * @apiSuccess {String} amortization_schedule.total.total_payment 加總
     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		'result':'SUCCESS',
     * 		'data':{
     * 			'id':'1',
     * 			'target_no': '1803269743',
     * 			'product_id':'1',
     *          'sub_product_id': 2,
     * 			'user_id':'1',
     * 			'amount':'5000',
     * 			'loan_amount':'12000',
     * 			'platform_fee':'1500',
     * 			'interest_rate':'9',
     * 			'instalment':'3',
     * 			'repayment':'1',
     * 			'reason':'',
     * 			'remark':'',
     * 			'delay':'0',
     * 			'status':'0',
     * 			'sub_status':'0',
     * 			'created_at':'1520421572',
     * 			'contract':'我是合約',
     * 			'credit':{
     * 				'level':'1',
     * 				'points':'1985',
     * 				'amount':'45000',
     * 				'created_at':'1520421572'
     * 			},
     *	         'certification': [
     *           	{
     *           	     'id': '1',
     *           	     'name': '身分證認證',
     *           	     'description': '身分證認證',
     *           	     'alias': 'id_card',
     *            	    'user_status': '1'
     *           	},
     *           	{
     *           	    'id': '2',
     *            	    'name': '學生證認證',
     *           	    'description': '學生證認證',
     *            	   'alias': 'student',
     *            	   'user_status': '1'
     *           	}
     *           ],
     *       'amortization_schedule': {
     *           'amount': '12000',
     *           'instalment': '6',
     *           'rate': '9',
     *           'date': '2018-04-17',
     *           'total_payment': 2053,
     *           'leap_year': false,
     *           'year_days': 365,
     *           'XIRR': 0.0939,
     *           'schedule': {
     *                '1': {
     *                  'instalment': 1,
     *                  'repayment_date': '2018-06-10',
     *                  'days': 54,
     *                  'remaining_principal': '12000',
     *                  'principal': 1893,
     *                  'interest': 160,
     *                  'total_payment': 2053
     *              },
     *              '2': {
     *                   'instalment': 2,
     *                  'repayment_date': '2018-07-10',
     *                  'days': 30,
     *                   'remaining_principal': 10107,
     *                   'principal': 1978,
     *                   'interest': 75,
     *                    'total_payment': 2053
     *               },
     *              '3': {
     *                    'instalment': 3,
     *                    'repayment_date': '2018-08-10',
     *                    'days': 31,
     *                    'remaining_principal': 8129,
     *                   'principal': 1991,
     *                   'interest': 62,
     *                    'total_payment': 2053
     *                }
     *            },
     *           'total': {
     *                'principal': 12000,
     *                'interest': 391,
     *                'total_payment': 12391
     *            }
     *        }
     * 		}
     *    }
     *
     * @apiUse TokenError
     * @apiUse BlockUser
     * @apiUse IsInvestor
     * @apiUse IsCompany
     *
     * @apiError 404 此申請不存在
     * @apiErrorExample {Object} 404
     *     {
     *       'result': 'ERROR',
     *       'error': '404'
     *     }
     *
     * @apiError 405 對此申請無權限
     * @apiErrorExample {Object} 405
     *     {
     *       'result': 'ERROR',
     *       'error': '405'
     *     }
     */
    public function applyinfo_get($target_id)
    {
        $this->load->library('credit_lib');
        $input 				= $this->input->get(NULL, TRUE);
        $user_id 			= $this->user_info->id;
        $investor 			= $this->user_info->investor;
        $company_status	= $this->user_info->company;
        $target 			= $this->target_model->get($target_id);
        if(!empty($target)){
            $this->load->library('loanmanager/product_lib');
            $product = $this->product_lib->get_exact_product($target->product_id, $target->sub_product_id);

            $product_name = $product['name'];

            $targetDatas = [];
            $cer_group = [];
            if($product['visul_id'] == 'DS2P1'){
                $targetData = json_decode($target->target_data);
                $cer_group['car_file'] = [1,'車籍文件', 2000 ,'提供車籍文件'];
                $cer_group['car_pic'] = [1,'車輛外觀照片', 2000 ,'提供車輛外觀照片'];
                $targetDatas = [
                    'brand' => $targetData->brand,
                    'name' => $targetData->name,
                    'selected_image' => $targetData->selected_image,
                    'vin' => $targetData->vin,
                    'purchase_time' => $targetData->purchase_time,
                    'factory_time' => $targetData->factory_time,
                    'product_description' => $targetData->product_description,
                ];
                $cer_file = ['car_history_image','car_title_image','car_import_proof_image','car_artc_image'];
                $car_pic = ['car_photo_front_image','car_photo_back_image','car_photo_all_image','car_photo_date_image','car_photo_mileage_image'];
                foreach ($product['targetData'] as $key => $value) {
                    if(in_array($key,array_merge($cer_file,$car_pic))){
                        if(isset($targetData->$key) && !empty($targetData->$key)){
                            $pic_array = [];
                            foreach ($targetData->$key as $svalue){
                                preg_match('/\/image.+/', $svalue,$matches);
                                $pic_array[] = FRONT_CDN_URL.'stmps/tarda'.$matches[0];
                            }
                            $targetDatas[$key] = $pic_array;
                        }
                        else{
                            $targetDatas[$key] = '';
                        }
                        if(in_array($key,$cer_file)){
                            empty($targetData->$key)?$cer_group['car_file'][0] = null:'';
                        }elseif(in_array($key,$car_pic)){
                            empty($targetData->$key)?$cer_group['car_pic'][0] = null:'';
                        }
                    }
                }
            } elseif (in_array($product['visul_id'], ['NS2P1', 'NS2P2'])) {
                $certification = $this->config->item('certifications')[CERTIFICATION_BUSINESS_PLAN];
                $targetData = json_decode($target->target_data);
                $cer_group[$certification['alias']] = [1, $product['targetData']['business_plan'][1], $certification['id'], $certification['description']];
                $targetDatas = [];
                foreach ($product['targetData'] as $key => $value) {
                    if(isset($targetData->$key) && !empty($targetData->$key)){
                        $pic_array = [];
                        foreach ($targetData->$key as $skey => $svalue){
                            preg_match('/image.+/', $svalue, $matches);
                            $pic_array[] = FRONT_CDN_URL.'stmps/tarda/'.$matches[0];
                        }
                        $targetDatas[$key] = $pic_array;
                    }
                    else{
                        $targetDatas[$key] = '';
                        $cer_group['business_plan'][0] = null;
                    }

                }
            }

            $certification		= [];
            $certification_list = $this->certification_lib->get_status($user_id, $investor, $company_status, TRUE, $target, $product, TRUE);
            $completeness_level = 100 / count($certification_list);
            if(count($cer_group) > 0){
                $completeness_level = 100 / (count($certification_list) + count($cer_group));
                foreach ($cer_group as $cer_key => $cervalue){
                    $certification[] = [
                        "id"=> $cervalue[2],
                        "alias"=> $cer_key,
                        "name"=> $cervalue[1],
                        "status"=> 1,
                        "description"=>  $cervalue[3],
                        "user_status"=> $cervalue[0],
                        "certification_id"=> null,
                        "updated_at"=> null,
                        "type"=> 'targetData',
                        "certification_completeness"=> 'targetData',
                        "completeness" => ceil($cervalue[0] == 1?$completeness_level:0)
                    ];
                }
            }
            if ($company_status)
            {
                $this->load->library('Judicialperson_lib');
                $naturalPerson = $this->judicialperson_lib->getNaturalPerson($user_id);

                $skip_certification_ids_judicial = $this->certification_lib->get_skip_certification_ids($target, $user_id);
                $skip_certification_ids_natural = $this->certification_lib->get_skip_certification_ids($target, $naturalPerson->id);
            }
            else
            {
                $skip_certification_ids = $this->certification_lib->get_skip_certification_ids($target);
            }

            if(!empty($certification_list)){
                $this->load->helper('target');
                $this->load->helper('product');
                $this->load->helper('user_certification');
                $exist_target_submitted = chk_target_submitted($target->status, $target->certificate_status ?? 0);

                foreach($certification_list as $key => $value){

                    // $config['certifications'] 設定 show=FALSE 則不顯示
                    if (isset($value['show']) && $value['show'] === FALSE)
                    {
                        continue;
                    }

					// 返回認證資料
					if(isset($value['content'])){
						unset($value['content']);
					}
					$user_certification = $this->user_certification_model->get_by(['id'=>$value['certification_id']]);
					$content_array_data = [];
                    // email欄位待new新版app上線後刪除
					$content_key = ['labor_type','return_type','mail_file_status', 'email'];
					if(isset($user_certification->content) && $user_certification->content != '' ){
						$user_certification = json_decode($user_certification->content,true);
						foreach($content_key as $key_name){
                            if(is_array($user_certification)){
                                if(array_key_exists($key_name,$user_certification)){
    								$content_array_data[$key_name] = isset($user_certification[$key_name]) ? $user_certification[$key_name] : '';
    							}
                            }
						}
					}
					if(!$content_array_data){
						$content_array_data = new StdClass();
					}
                    $diploma = $key==8?$value:null;

                    // 找當前登入的 user 所需的徵信項
                    $this->load->library('loanmanager/product_lib');
                    $this->load->model('loan/target_associate_model');
                    $get_associate_info = $this->target_associate_model->as_array()->get_by(['target_id' => $target->id, 'user_id' => $user_id,]);
                    $associate_character = ! empty($get_associate_info['character']) ? $get_associate_info['character'] : ASSOCIATES_CHARACTER_REGISTER_OWNER;
                    $product_certs = $this->product_lib->get_product_certs_by_product_id($target->product_id, $target->sub_product_id, [$associate_character]);

                    if (in_array($key, $product_certs) && $value['id'] != CERTIFICATION_CERCREDITJUDICIAL)
                    {
                        $truly_failed = $this->certification_lib->certification_truly_failed($exist_target_submitted, $value['certification_id'] ?? 0,
                            USER_BORROWER,
                            is_judicial_product($target->product_id)
                        );

                        if ($company_status)
                        {
                            if (is_judicial_certification($value['id']))
                            {
                                $skip_certification_ids = $skip_certification_ids_judicial;
                            }
                            else
                            {
                                $skip_certification_ids = $skip_certification_ids_natural;
                            }
                        }

                        if (in_array($key, $skip_certification_ids))
                        {
                            $value['user_status'] = CERTIFICATION_STATUS_SUCCEED;
                        }
                        else if ($truly_failed)
                        {
                            if (in_array($target->status, [TARGET_WAITING_SIGNING, TARGET_WAITING_VERIFY, TARGET_WAITING_BIDDING, TARGET_WAITING_LOAN]))
                            {
                                $value['user_status'] = CERTIFICATION_STATUS_SUCCEED;
                            }
                            else
                            {
                                $value['user_status'] = NULL;
                                $value['certification_id'] = NULL;
                                $value['remark'] = NULL;
                                $content_array_data = [];
                            }
                        }
                        $option_cert_ids = $product['option_certifications'] ?? [];
                        $value['optional'] = in_array($value['id'], $option_cert_ids); // 是否選填 (true/false)
                        $value['type'] = 'certification';
                        $value['completeness'] = ceil($value['user_status'] == 1?$completeness_level:0);
						$value['certification_content'] = $content_array_data;
                        $certification[] = $value;
                    }
            }

            if(isset($product['checkOwner']) && $product['checkOwner'] == true){
                $associatess = $this->target_lib->get_associates_list($target_id, false, $product, $user_id, $certification);
                if($target->user_id != $user_id && !$associatess){
                    $this->response(array('result' => 'ERROR','error' => APPLY_NO_PERMISSION ));
                }
                $associatess ? $target = $associatess : '';
            }

            $amortization_schedule = [];
            if(in_array($target->status,[1,2,3,4])){
                $amortization_schedule = $this->financial_lib->get_amortization_schedule($target->loan_amount,$target);
            }

//                $credit = $this->credit_lib->get_credit($user_id, $target->product_id, $target->sub_product_id, $target);
                $credit = $this->credit_lib->get_target_credit($user_id, $target->product_id, $target->sub_product_id, $target_id, $target);
            if (isset($credit['amount']))
            {
                $this->load->library('credit_lib');
                $remain_amount = $this->credit_lib->get_remain_amount($target->user_id, $target->product_id, $target->sub_product_id, $target->id);
                $credit['amount'] = $remain_amount['instalment'] == $target->instalment ? $remain_amount['user_available_amount'] : 0;
            }

            $contract = '';
            if($target->contract_id){
                $this->load->library('Contract_lib');
                $contract_data 	= $this->contract_lib->get_contract($target->contract_id);
                $contract 		= $contract_data['content'];
            }

            $order_info = [];
            if($target->order_id != 0){
                $shipped_image = '';
                $this->load->model('transaction/order_model');
                $orders 	= $this->order_model->get_by([
                    'id'		=> $target->order_id,
                ]);

                if(!empty($orders)){
                    $this->load->library('contract_lib');
                    $date        = get_entering_date();
                    $company     = $this->get_dealer_info($orders->company_user_id,substr($orders->merchant_order_no,0,1));
                    $item_info   = [];
                    $items 		 = [];
                    $item_name	 = explode(',',$orders->item_name);
                    $item_count	 = explode(',',$orders->item_count);
                    foreach($item_count as $k => $v){
                        $items[] = $item_name[$k].' x '.$v;
                        $item_count[$k] = intval($v);
                     }

                    if (in_array($target->status, array(TARGET_ORDER_WAITING_SIGNING, TARGET_ORDER_WAITING_VERIFY, TARGET_ORDER_WAITING_SHIP, TARGET_ORDER_WAITING_VERIFY_TRANSFER))) {
                        $amortization_schedule = $this->financial_lib->get_amortization_schedule(intval($orders->total),$target,$date,$product['type']);
                        $contract = $this->contract_lib->pretransfer_contract('order',[
                            $orders->company_user_id,
                            $user_id,
                            implode(' , ',$items),
                            $orders->total,
                            $amortization_schedule['total']['total_payment'].'、'.$orders->instalment.'、'.$amortization_schedule['total_payment'].'、',
                        ]);
                    }

                    $this->load->library('coop_lib');
                    $result = $this->coop_lib->coop_request('order/sread',[
                        'merchant_order_no' => $orders->merchant_order_no,
                        'phone'             => $orders->phone,
                        'type'              => 'product',
                    ],0);
                    if(isset($result->result) && $result->result == 'SUCCESS') {
                        $item_info[] = $result->data->list;
                    }

                    if($orders->shipped_image!=''){
                        $this->load->library('S3_upload');
                        $shipped_image = $this->s3_upload->public_image_by_data(file_get_contents($orders->shipped_image));
                    }

                    $order_info['order_no'] 		 = $orders->order_no;
                    $order_info['company'] 			 = $company;
                    $order_info['merchant_order_no'] = $orders->merchant_order_no;
                    $order_info['item_info']   	     = $item_info;
                    $order_info['item_name'] 		 = $item_name;
                    $order_info['item_count'] 		 = $item_count;
                    $order_info['shipped_number'] 	 = $orders->shipped_number;
                    $order_info['shipped_image'] 	 = $shipped_image;
                    $order_info['delivery'] 		 = intval($orders->delivery);
                    $order_info['status'] 		     = intval($orders->status);
                    $order_info['created_at'] 		 = intval($orders->created_at);
                }
            }

            $biddingHistory = [];
            $this->load->library('target_lib');
            if ($target->status == 3
                && ! $this->target_lib->is_sub_loan($target->target_no)
            ){
            $this->load->model('loan/investment_model');
            $this->load->model('log/log_targetschange_model');
            $this->load->model('log/log_investmentschange_model');
            $history = [];
            $biddingAmount = 0;
            $cancel_inv = [];
            $cancel_inv_amount = [];
            $targets_end = $target->expire_time;
            $targetLog	= $this->log_targetschange_model->order_by('created_at', 'desc')->get_by(array(
                "target_id"		=> $target->id,
                "status"		=> 3,
            ));
            if(empty($targetLog)){
                throw new Exception('no target log was found');
            }

            $targets_start = $targetLog->created_at;
            $currentIndex = strval(floor((time() - $targets_start) / 60 / 60)) + 1;
//                if(!$currentIndex > 90){
                $x = strval(round(($targets_end - $targets_start) / 60 / 60));
                $x_unit = '時';
                $x_limit = 1;
                $y = '100';
                $y_unit = '%';
                $y_limit = 10;
                for ($i = 0; $i < $x; $i++) {
                    $history[$i] = 0;
                }

                //取得投標、棄標投資id
                $investments = $this->investment_model->get_many_by([
                    'created_at >=' => $targets_start,
                    'tx_datetime !=' => null,
                    'target_id' => $target->id,
                    'status' => [1 ,8]
                ]);
                $bidInvest = [];
                $thisHourBidding = 0;
                if($investments){
                    foreach ($investments as $inv_key => $inv_val) {
                        $biddingAmount = 0;
                        if ($inv_val->status == 8) {
                            $cancel_inv[] = $inv_val->id;
                            $cancel_inv_amount[$inv_val->id] = $inv_val->amount;
                        }
                        $at = floor((strtotime($inv_val->tx_datetime) - $targets_start) / 60 / 60) + 1;
                        $bidInvest[] = $inv_val->id;
                        $biddingAmount += $inv_val->amount;
                        if($at == $currentIndex && $inv_val->status != 1){
                            $at++;
                            $thisHourBidding++;
                        }
                        !isset($history[$at]) ? $history[$at] = 0 : '';
                        $history[$at] += $biddingAmount;
                    }
                }else{
                    $thisHourBidding--;
                }

                //取得棄標時間
                $dhistory = [];
                if(count($cancel_inv) > 0){
                    $cancel_inv_time = $this->log_investmentschange_model->order_by('created_at', 'desc')->get_many_by([
                        'investment_id' => $cancel_inv,
                        'status' => 8
                    ]);
                    if($cancel_inv_time){
                        foreach ($cancel_inv_time as $cancel_inv_time_Key => $cancel_inv_time_val) {
                            //僅對應有圈存的investment
                            if(in_array($cancel_inv_time_val->investment_id,$bidInvest)){
                                $at = floor(($cancel_inv_time_val->created_at - $targets_start) / 60 / 60) + 1;
                                !isset($dhistory[$at]) ? $dhistory[$at] = 0 : '';
                                $dhistory[$at] += $cancel_inv_amount[$cancel_inv_time_val->investment_id];
                            }
                        }
                    }
                }

                //取得各期金額
                $lastBidding = 0;
                foreach ($history as $history_key => $history_val) {
                    $history_val != 0 ? $lastBidding += $history_val : '';
                    isset($dhistory[$history_key]) ? $lastBidding -= $dhistory[$history_key] : '';
                    $history[$history_key] = $lastBidding;
                }

                //轉換單位、排除未來期數
                foreach ($history as $history_key => $history_val) {
                    $history[$history_key] = 100 - round(($target->loan_amount - $history_val) / $target->loan_amount * 100);
                    if($history_key > $currentIndex + $thisHourBidding){
                        unset($history[$history_key]);
                    }
                }

                $biddingHistory = [
                        'startBidding' => date("Y/m/d H:i:s",$targets_start),
                        'endBidding' =>  date("Y/m/d H:i:s",$targets_end),
                        'currenIndex' => $currentIndex,
                        'history' => $history,
                        'x' => $x,
                        'x_unit' => $x_unit,
                        'x_limit' => $x_limit,
                        'y' => $y,
                        'y_unit' => $y_unit,
                        'y_limit' => $y_limit,
                    ];
                }
           }


            $reason = $target->reason;
            $json_reason = json_decode($reason);
            if (isset($json_reason->reason)) {
                $reason = $json_reason->reason . ' - ' . $json_reason->reason_description;
            }

            $data = [
                'id' 				    => intval($target->id),
                'target_no' 		    => $target->target_no,
                'product_name' => $product_name,
                'product_id' 		    => intval($target->product_id),
                'sub_product_id' 		    => intval($target->sub_product_id),
                'user_id' 			    => intval($target->user_id),
                'order_id'              => intval($target->order_id),
                'order_info'            => $order_info,
                'targetDatas' => $targetDatas,
                'amount' 			    => intval($target->amount),
                'loan_amount'             =>  intval($target->status == 0 ? $target->amount : $target->loan_amount),
                'platform_fee' 		    => intval($target->platform_fee),
                'interest_rate' 	    => floatval($target->interest_rate),
                'instalment' 		    => intval($target->instalment),
                'repayment' 		    => intval($target->repayment),
                'reason' 			    => $reason,
                'remark' 			    => $target->remark,
                'delay' 			    => intval($target->delay),
                'delay_days' 		    => intval($target->delay_days),
                'status' 			    => intval($target->status),
                'sub_status' 		    => intval($target->sub_status),
                'associate' 		         => (isset($target->associate) ? $target->associate : false),
                'created_at' 		    => intval($target->created_at),
                'contract'			    => $contract,
                'credit'			    => $credit,
                'certification'		    => $certification,
                'amortization_schedule'	=> $amortization_schedule,
                'biddingHistory' => $biddingHistory,
                'certificate_status' => intval($target->certificate_status),
                'verify_status' => $this->chk_target_verifying($target->target_data ?? '') ? 1 : 0,
            ];

            in_array($target->product_id, $this->config->item('allow_changeRate_product')) && $target->status == 3 ? $data['isSupportRateAdjust'] = true : '';

            $this->response(array('result' => 'SUCCESS','data' => $data ));
        }
        $this->response(array('result' => 'ERROR','error' => APPLY_NOT_EXIST ));
    }

    /**
     * @api {get} /v2/product/cancel/:id 借款方 取消申請
     * @apiVersion 0.2.0
     * @apiName GetProductCancel
     * @apiGroup Product
     * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiParam {Number} id Targets ID
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      'result': 'SUCCESS'
     *    }
     *
     * @apiUse InputError
     * @apiUse TokenError
     * @apiUse BlockUser
     * @apiUse IsInvestor
     * @apiUse IsCompany
     *
     *
     * @apiError 404 此申請不存在
     * @apiErrorExample {Object} 404
     *     {
     *       'result': 'ERROR',
     *       'error': '404'
     *     }
     *
     * @apiError 405 對此申請無權限
     * @apiErrorExample {Object} 405
     *     {
     *       'result': 'ERROR',
     *       'error': '405'
     *     }
     *
     * @apiError 407 目前狀態無法完成此動作
     * @apiErrorExample {Object} 407
     *     {
     *       'result': 'ERROR',
     *       'error': '407'
     *     }
     *
     */
    public function cancel_get($target_id)
    {
        $input 		= $this->input->get(NULL, TRUE);
        $user_id 	= $this->user_info->id;
        $targets 	= $this->target_model->get($target_id);
        if(!empty($targets)){
            if($targets->user_id != $user_id){
                $associates_target = $this->target_lib->get_associates_target_list($this->user_info->id, $target_id);
                if ($associates_target) {
                    # 案件關係人取消成為關係人
                    if(in_array($associates_target->associate['status'], array(0, 1)) && !$associates_target->associate['owner']){
                        $this->load->model('loan/target_associate_model');
                        $this->target_associate_model->update_by([
                            'target_id' => $target_id,
                            'user_id' => $user_id,
                        ], [
                            'status' => 9,
                        ]);
                        $this->response(array('result' => 'SUCCESS'));
                    }
                }
                # 負責人個人帳號可以取消企金案件，不應回傳 APPLY_NO_PERMISSION
                if ($associates_target && !$associates_target->associate['owner']) {
                    $this->response(array('result' => 'ERROR','error' => APPLY_NO_PERMISSION ));
                }
            }

            if(in_array($targets->status,[
                TARGET_WAITING_APPROVE,
                TARGET_WAITING_SIGNING,
                TARGET_WAITING_VERIFY,
                TARGET_ORDER_WAITING_QUOTE,
                TARGET_ORDER_WAITING_SIGNING
                ])
                && in_array($targets->sub_status,[
                    TARGET_SUBSTATUS_NORNAL,
                    TARGET_SUBSTATUS_SECOND_INSTANCE,
                    TARGET_SUBSTATUS_SECOND_INSTANCE_TARGET,
                    11
                    ])){
                $rs = $this->target_lib->cancel_target($targets, $user_id, $this->user_info->phone);
                if ($rs) {
                    if ($targets->product_id == 5 &&
                        in_array($targets->sub_product_id, [SUB_PRODUCT_ID_HOME_LOAN_SHORT, SUB_PRODUCT_ID_HOME_LOAN_RENOVATION, SUB_PRODUCT_ID_HOME_LOAN_APPLIANCES])
                    ) {
                        $cancel_booking_result = $this->target_lib->cancel_booking_and_certification($targets->user_id);
                        if (!$cancel_booking_result) {
                            $this->response(array('result' => 'ERROR', 'error' => '取消預約時間失敗'));
                        }
                    }
                    $this->response(array('result' => 'SUCCESS'));
                }
            }
            $this->response(array('result' => 'ERROR','error' => APPLY_STATUS_ERROR ));
        }
        $this->response(array('result' => 'ERROR','error' => APPLY_NOT_EXIST ));
    }


    public function changerate_post()
    {
        $input = $this->input->post(NULL, TRUE);
        $user_id = $this->user_info->id;

        $fields = ['id', 'rate'];
        foreach ($fields as $field) {
            if (empty($input[$field])) {
                $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
            } else {
                $param[$field] = $input[$field];
            }
        }

        $target = $this->target_model->get($param['id']);
        if (!empty($target)) {
            $target->user_id != $user_id
                ? $this->response(array('result' => 'ERROR', 'error' => APPLY_NO_PERMISSION))
                : false;

            $product_list = $this->config->item('product_list');
            $product = $product_list[$target->product_id];
            $sub_product_id = $target->sub_product_id;
            $this->is_sub_product($product, $sub_product_id)
                ? $product = $this->trans_sub_product($product, $sub_product_id)
                : false;

            $new_rate = floatval($param['rate']);
            $new_rate <= $target->interest_rate || $new_rate > $product['interest_rate_e']
                ? $this->response(array('result' => 'ERROR', 'error' => PRODUCT_RATE_ERROR))
                : false;


            $allow_changeRate_product = $this->config->item('allow_changeRate_product');
            if (in_array($target->product_id, $allow_changeRate_product)
                && $target->status == 3
                && in_array($target->sub_status, [TARGET_SUBSTATUS_NORNAL, TARGET_SUBSTATUS_SECOND_INSTANCE_TARGET])
                && $target->script_status == 0
                && $target->expire_time >= time()
            ) {
                $update_data = [
                    'status' => 2,
                    'script_status' => 99
                ];
                $this->target_model->update($target->id, $update_data);
                $this->load->library('target_lib');
                $this->target_lib->insert_change_log($target->id, $update_data, $user_id);
                $this->load->model('loan/investment_model');
                $investments = $this->investment_model->get_many_by([
                    'target_id' => $target->id,
                    'status' => [0, 1]
                ]);
				$this->load->library('Sendemail');
                $notification_subject = "【投資標的】您的投資利率已提高";
                foreach ($investments as $inv_key => $inv_val) {
                    $this->target_lib->cancel_investment($target, $inv_val, $user_id);
                    $rate_increased_notification = $this->rate_increased_notification_content($inv_val->created_at, $target->interest_rate, $new_rate);
					$this->sendemail->change_interest_rate($inv_val, $target->interest_rate, $new_rate, $notification_subject, $rate_increased_notification);
                }
                $target->status = 2;
                $this->load->library('Contract_lib');
                $contract_id = $this->contract_lib->sign_contract('lend', ['', $user_id, $target->loan_amount, $new_rate, '']);
                $launch_times = intval($target->launch_times) + 1;
                $params = [
                    'interest_rate' => $new_rate,
                    'contract_id' => $contract_id,
                    'script_status' => 0,
                    'launch_times' => $launch_times,
                ];
                $this->target_lib->target_verify_success($target, 0, $params, $user_id);

                // Notify rate increased by app.
                $user_ids_to_exclude = [];
                $this->load->library('notification_lib');
                foreach ($investments as $investment)
                {
                    $user_ids_to_exclude[] = $investment->user_id;
                    $rate_increased_notification = $this->rate_increased_notification_content(
                        $investment->created_at, $target->interest_rate, $new_rate, TRUE
                    );
                    $this->notification_lib->notify_rate_increased(
                        [$investment->user_id], $target->interest_rate, $new_rate, $notification_subject, $rate_increased_notification
                    );
                }
                $this->notification_lib->notify_rate_increased(
                    $this->user_model->get_ids($user_ids_to_exclude), $target->interest_rate, $new_rate
                );

                $this->response(array('result' => 'SUCCESS'));
            }
            $this->target_model->update($target->id, ['script_status' => 0]);
            $this->response(array('result' => 'ERROR', 'error' => APPLY_STATUS_ERROR));
        }
        $this->response(array('result' => 'ERROR', 'error' => APPLY_NOT_EXIST));
    }


    /**
     * @api {get} /v2/product/order 借款方 分期訂單列表
     * @apiVersion 0.2.0
     * @apiName GetProductOrder
     * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiGroup Product
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccess {String} order_no 訂單編號
     * @apiSuccess {String} company 經銷商名稱
     * @apiSuccess {String} merchant_order_no 經銷商訂單編號
     * @apiSuccess {Number} product_id Product ID
     * @apiSuccess {Number} amount 金額
     * @apiSuccess {Number} instalment 期數 0:其他
     * @apiSuccess {Object} item_name 商品名稱
     * @apiSuccess {Object} item_count 商品數量
     * @apiSuccess {Number} created_at 申請日期
     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		'result':'SUCCESS',
     * 		'data':{
     * 			'list':[
     * 			{
     * 				'order_no': '29-2019013116565856678',
     * 				'company': '普匯金融科技股份有限公司',
     * 				'merchant_order_no': 'toytoytoy123',
     * 				'product_id': 2,
     * 				'total': 20619,
     * 				'instalment': 3,
     * 				'item_name': [
     * 					'小雞',
     * 					''丫丫''
     * 				],
     * 				'item_count': [
     * 					1,
     * 					2
     * 				],
     * 				'created_at': 1548925018
     * 			}
     * 			]
     * 		}
     *    }
     *
     * @apiUse TokenError
     * @apiUse BlockUser
     * @apiUse IsInvestor
     * @apiUse IsCompany
     *
     */
    public function order_get()
    {
        $input 		= $this->input->get(NULL, TRUE);
        $user_id 	= $this->user_info->id;
        $investor 	= $this->user_info->investor;

        $this->load->model('transaction/order_model');
        $orders 	= $this->order_model->get_many_by([
            'phone'		=> $this->user_info->phone,
            'status'	=> 0,
        ]);

        $list	= [];
        if(!empty($orders)){
            $this->load->library('contract_lib');
            foreach($orders as $key => $value){
                $date = get_entering_date();
                $amortization_schedule = $this->financial_lib->get_amortization_schedule(intval($value->total),$orders,$date);
                $company = $this->user_model->get(intval($value->company_user_id));
                $items 		= [];
                $item_name	= explode(',',$value->item_name);
                $item_count	= explode(',',$value->item_count);
                foreach($item_count as $k => $v){
                    $items[] = $item_name[$k].' x '.$v;
                    $item_count[$k] = intval($v);
                }

                $contract = $this->contract_lib->pretransfer_contract('order',[
                    $value->company_user_id,
                    $user_id,
                    implode(' , ',$items),
                    $value->total,
                    $amortization_schedule['total']['total_payment'].'、'.$value->instalment.'、'.$amortization_schedule['total_payment'].'、',
                ]);

                $list[] = [
                    'order_no' 			=> $value->order_no,
                    'company' 			=> $company->name,
                    'merchant_order_no' => $value->merchant_order_no,
                    'product_id' 		=> intval($value->product_id),
                    'amount' 			=> intval($value->total),
                    'instalment' 		=> intval($value->instalment),
                    'pmt' 				=> intval($amortization_schedule['total_payment']),
                    'item_name' 		=> $item_name,
                    'item_count' 		=> $item_count,
                    'contract' 			=> $contract,
                    'created_at' 		=> intval($value->created_at),
                ];
            }
        }
        $this->response(['result' => 'SUCCESS','data' => ['list' => $list] ]);
    }

    /**
     * @api {post} /v2/product/order 借款方 申請分期訂單
     * @apiVersion 0.2.0
     * @apiName PostProductOrder
     * @apiGroup Product
     * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiDescription 此API只支援申請分期訂單，產品簽約前一次只能一案。
     *
     * @apiParam {String} order_no 訂單編號
     * @apiParam {file} person_image 本人照
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccess {Number} target_id Targets ID
     * @apiSuccessExample {Object} SUCCESS
     * {
     * 		'result':'SUCCESS',
     * 		'data':{
     * 			'target_id': 1
     * 		}
     * }
     *
     * @apiUse InputError
     * @apiUse InsertError
     * @apiUse TokenError
     * @apiUse BlockUser
     * @apiUse IsInvestor
     * @apiUse IsCompany
     *
     * @apiError 408 同產品重複申請
     * @apiErrorExample {Object} 408
     *     {
     *       'result': 'ERROR',
     *       'error': '408'
     *     }
     *
     * @apiError 411 訂單不存在
     * @apiErrorExample {Object} 411
     *     {
     *       'result': 'ERROR',
     *       'error': '411'
     *     }
     *
     * @apiError 412 已申請過或已失效
     * @apiErrorExample {Object} 412
     *     {
     *       'result': 'ERROR',
     *       'error': '412'
     *     }
     *
     *
     */
    public function order_post()
    {
        $this->load->library('S3_upload');
        $this->load->model('user/user_bankaccount_model');
        $this->load->library('Certification_lib');
        $input 		= $this->input->post(NULL, TRUE);
        $user_id 	= $this->user_info->id;
        $investor 	= $this->user_info->investor;
        $param 		= [];
        $date 		= get_entering_date();
        //必填欄位
        if (empty($input['order_no'])) {
            $this->response(['result' => 'ERROR','error' => INPUT_NOT_CORRECT]);
        }
        $this->load->model('transaction/order_model');
        $order = $this->order_model->get_by([
            'phone'		=> $this->user_info->phone,
            'order_no'	=> $input['order_no'],
        ]);

        if($order){
            if($order->status != 0 ){
                $this->response(['result' => 'ERROR','error' => ORDER_STATUS_ERROR]);
            }

            //上傳檔案欄位
            if (isset($_FILES['person_image']) && !empty($_FILES['person_image'])) {
                $image 	= $this->s3_upload->image($_FILES,'person_image',$user_id,'order');
                if(!$image){
                    $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                }
            }else{
                $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }

            $exist = $this->target_model->get_by([
                'status <='		=> 1,
                'user_id'		=> $user_id,
                'product_id'	=> $order->product_id
            ]);
            if($exist){
                $this->response(['result' => 'ERROR','error' => APPLY_EXIST]);
            }

            $items 		= [];
            $item_name	= explode(',',$order->item_name);
            $item_count	= explode(',',$order->item_count);
            foreach($item_count as $k => $v){
                $items[] = $item_name[$k].' x '.$v;
                $item_count[$k] = intval($v);
            }

            $amortization_schedule = $this->financial_lib->get_amortization_schedule(intval($order->total),$orders,$date);

            $this->load->library('contract_lib');
            $contract = $this->contract_lib->sign_contract('order',[
                $order->company_user_id,
                $user_id,
                implode(' , ',$items),
                $order->total,
                $amortization_schedule['total']['total_payment'].'、'.$order->instalment.'、'.$amortization_schedule['total_payment'].'、',
            ]);

            $param = [
                'product_id'	=> $order->product_id,
                'user_id'		=> $user_id,
                'amount'		=> $order->total,
                'damage_rate' 	=> LIQUIDATED_DAMAGES,
                'instalment'	=> $order->instalment,
                'order_id'		=> $order->id,
                'reason'		=> '分期:'.implode(' , ',$items),
                'contract_id'	=> $contract,
                'person_image'	=> $image,
                'loan_date'		=> $date,
            ];

            $insert = $this->target_lib->add_target($param);
            if($insert){
                $this->order_model->update($order->id,['status'=>2]);
                $this->response(['result' => 'SUCCESS','data'=>['target_id'=> $insert ]]);
            }else{
                $this->response(['result' => 'ERROR','error' => INSERT_ERROR]);
            }
        }
        $this->response(array('result' => 'ERROR','error' => ORDER_NOT_EXIST ));
    }

    public function orderApply_post($designate=false)
    {
        $input = $designate?$designate:$this->input->post(NULL, TRUE);
        $user_id = $this->user_info->id;
        $name = '';
        $phone = '';
        $id_number = '';

        $fields 	= ['product_id','instalment','store_id','item_id'];
        foreach ($fields as $field) {
            if (!isset($input[$field])) {
                $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }else{
                $content[$field] = $input[$field];
            }
        }
        $product_id		= $content['product_id'];//產品ID
        $instalment     = $content['instalment'];//分期數
        $store_id       = $content['store_id'];  //店家ID
        $item_id		= $content['item_id'];   //商品ID
        $nickname =  isset($input['nickname'])?$input['nickname']:'';  //暱稱

        $item_count		= isset($input['item_count'])&&$input['item_count']<2?$input['item_count']:1;//商品數量
        $delivery       = isset($input['delivery'])&&$input['delivery']<2?$input['delivery']:0;  //0:線下 1:線上

        //檢驗產品規格
        $product_list 	= $this->config->item('product_list');
        $product 		= $product = isset($product_list[$product_id])?$product_list[$product_id]:[];
        if($product){
            if(!$designate){
                $name = $this->user_info->name;
                $phone = $this->user_info->phone;
                $id_number = $this->user_info->id_number;

                if(!in_array($input['instalment'],$product['instalment'])){
                    $this->response(array('result' => 'ERROR','error' => PRODUCT_INSTALMENT_ERROR ));
                }

                 //檢驗消費貸重複申請
                $exist = $this->target_model->get_by([
                    'status'		=> [20,21],
                    'user_id'		=> $user_id,
                    'product_id'	=> $product_id
                ]);
                if($exist){
                    $this->response(['result' => 'ERROR','error' => APPLY_EXIST]);
                }

                //交易方式
                $address    = '';
                if($delivery == 1){
                    if (!isset($input['address'])) {
                        $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                    }else{
                        $address = $input['address'];
                    }
                }
            }

            $interest_rate = $product['interest_rate_s'];

            $cooperation = $this->get_cooperation_info($store_id);
            $cooperation_id  = $cooperation -> cooperation_id;
            $company_user_id = $cooperation -> company_user_id;

            //對經銷商系統建立訂單
            $user_name    = mb_substr($name,0,1,"utf-8").(substr($id_number,1,1)==1?'先生':(substr($id_number,1,1)==2?'小姐':'先生/小姐'));

            $result = $this->coop_lib->coop_request('order/screate',[
                'cooperation_id' => $cooperation_id,
                'item_id'        => $item_id,
                'item_count'     => $item_count,
                'instalment'     => $instalment,
                'delivery'       => $delivery,
                'name'           => $user_name,
                'phone'          => $phone,
                'address'        => $address,
                'nickname'       => $nickname,
                'selling_type' => $cooperation -> type,
            ],$user_id);
            if(isset($result->result) && $result->result == 'SUCCESS'){
                $item_name = $result->data->product_name.
                    ($result->data->product_spec!='-'
                        ?$result->data->product_spec
                        :''
                    );
                $merchant_order_no = $result->data->merchant_order_no;
                $product_price     = $result->data->product_price;
                $amount            = $product_price;
                $platform_fee      = 0;
                $transfer_fee      = 0;
                $total             = 0;
                //建立主系統訂單
                if($product_price > 0){
                    $platform_fee = $this->financial_lib->get_platform_fee2($product_price, $product['charge_platform']);
                    $transfer_fee = $this->financial_lib->get_transfer_fee( $product_price + $platform_fee);
                    $total        = $amount + $platform_fee + $transfer_fee;
                }

                $designate?$amount=$designate->amount:'';
                $order_parm = [
                    'company_user_id'   => $company_user_id,
                    'order_no'          => $store_id.'-'.date('YmdHis').rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9),
                    'merchant_order_no' => $merchant_order_no,
                    'phone'             => $phone,
                    'product_id'	    => $product_id,
                    'instalment'	    => $instalment,
                    'item_price'        => $product_price,
                    'item_name'         => $item_name,
                    'item_count'        => $item_count,
                    'amount'            => $amount,
                    'platform_fee'	    => $platform_fee,
                    'transfer_fee'      => $transfer_fee,
                    'total'             => $total,
                    'delivery'          => $delivery,
                    'nickname'          => $nickname,
                    'status'            => 0
                ];
                $this->load->model('transaction/order_model');
                $order_insert = $this->order_model->insert($order_parm);
                if($order_insert){
                    if($designate){
                        return true;
                    }

                    $param = [
                        'product_id'	=> $product_id,
                        'user_id'		=> $user_id,
                        'amount'		=> $total,
                        'instalment'	=> $instalment,
                        'platform_fee'  => $platform_fee,
                        'order_id'		=> $order_insert,
                        'reason'		=> '分期:'.$item_name,
                        'status'        => 20,
                    ];
                    //建立產品單號
                    $insert = $this->target_lib->add_target($param);
                }
                if($order_insert && $insert){
                    $this->notification_lib->notice_order_apply($company_user_id,$item_name,$instalment,$delivery);
                    $this->load->library('Certification_lib');
                    $this->certification_lib->expire_certification($user_id);
                    $this->response(['result' => 'SUCCESS','data'=>['target_id'=> $insert ]]);
                }
                $this->target_lib->cancel_order($order_insert,$merchant_order_no,$user_id,$phone);
            }
            $this->response(['result' => 'ERROR','error' => $result->error ]);
        }
        $this->response(['result' => 'ERROR','error' => PRODUCT_NOT_EXIST]);
    }

    public function orderSigning_post()
    {
        $input 		= $this->input->post(NULL, TRUE);
        $user_id 	= $this->user_info->id;
        $date 		= get_entering_date();

        //必填欄位
        if (empty($input['id'])) {
            $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
        }

        $target 	= $this->target_model->get($input['id']);
        if(!empty($target)){

            if($target->user_id != $user_id){
                $this->response(array('result' => 'ERROR','error' => APPLY_NO_PERMISSION ));
            }

            if($target->status != 21 || $target->sub_status != 0){
                $this->response(array('result' => 'ERROR','error' => APPLY_STATUS_ERROR ));
            }

            $product_list 	= $this->config->item('product_list');
            $product 		= isset($product_list[$target->product_id])?$product_list[$target->product_id]:false;
            if($product){
                if($product['type'] != 2){
                    $this->response(array('result' => 'ERROR','error' => PRODUCT_TYPE_ERROR ));
                }

                $this->load->model('transaction/order_model');
                $order 	= $this->order_model->get($target->order_id);
                if($order){
                    if($order->status != 1 ){
                        $this->response(['result' => 'ERROR','error' => ORDER_STATUS_ERROR]);
                    }
                    $items 		= [];
                    $item_name	= explode(',',$order->item_name);
                    $item_count	= explode(',',$order->item_count);
                    foreach($item_count as $k => $v){
                        $items[] = $item_name[$k].' x '.$v;
                        $item_count[$k] = intval($v);
                    }

                    $amortization_schedule = $this->financial_lib->get_amortization_schedule(intval($order->total),$target, get_entering_date(),$product['type']);

                    $this->load->library('contract_lib');
                    $contract = $this->contract_lib->sign_contract('order_200325',[
                        $order->company_user_id,
                        $user_id,
                        implode(' , ',$items),
                        $order->total,
                        $amortization_schedule['total']['total_payment'].'、'.$order->instalment.'、'.$amortization_schedule['total_payment'].'、',
                    ]);

                    $rs = $this->target_lib->ordersigning_target($target->id,$user_id,[
                        'damage_rate' 	=> LIQUIDATED_DAMAGES,
                        'contract_id'	=> $contract,
                        'status'        => 22,
                    ]);
                    if($rs){
                        $this->response(array('result' => 'SUCCESS'));
                    }
                }
                $this->response(array('result' => 'ERROR','error' => ORDER_NOT_EXIST ));
            }
            $this->response(array('result' => 'ERROR','error' => PRODUCT_NOT_EXIST ));
        }
        $this->response(array('result' => 'ERROR','error' => APPLY_NOT_EXIST ));
    }

    public function dealerList_get()
    {
        $list	     = [];
        $cooperation = [];
        $input       = $this->input->get(NULL, TRUE);
        $this->load->model('user/cooperation_model');
        if(isset($input['type'])&&$input['type']!=null){
            $cooperation = $this->cooperation_model->get_many_by([
                'server_ip !=' => '',
                'type'	    => $input['type'],
                'status'    => 1,
            ]);
        }
        if($cooperation){
            foreach($cooperation as $key => $value){
                $list[$key] = $this->get_dealer_info($value->company_user_id,$value->id);
            }
        }

        $this->response(array('result' => 'SUCCESS','data' => ['list' => $list] ));
    }

    private function get_dealer_info($company_user_id,$id){
        $this->load->model('user/judicial_person_model');
        $judicial_person = $this->judicial_person_model->get_by(array('company_user_id'=>$company_user_id));
        $info = '';
        if($judicial_person){
            $info  = [
                'company_id'        => intval($id),
                'company'           => $judicial_person->company,
                'tax_id'            => intval($judicial_person->tax_id),
                //'company_contact' => $judicial_person->cooperation_contact,
                'company_phone'     => $judicial_person->cooperation_phone,
                'company_address'   => $judicial_person->cooperation_address,
            ];
        }
        return $info;
    }

    public function associates_post()
    {
        $input = $this->input->post(NULL, TRUE);

        $fields = ['target_id', 'name', 'phone', 'id_number', 'character', 'relationship'];
        foreach ($fields as $field) {
            if (!isset($input[$field])) {
                $this->response(['result' => 'ERROR','error' => INPUT_NOT_CORRECT]);
            }else{
                $content[$field] = $input[$field];
            }
        }

        $this->load->library('Judicialperson_lib');
        $naturalPerson = $this->judicialperson_lib->getNaturalPerson($this->user_info->id);
        $this->load->library('certification_lib');
        $cerIDENTITY = $this->certification_lib->get_certification_info($naturalPerson->id, CERTIFICATION_IDENTITY, 0);
        if(!$cerIDENTITY){
            $this->response(array('result' => 'ERROR','error' => NO_CER_IDENTITY ));
        }

        $character = $content['character'];
        $characters = $this->config->item('character');

        if ($character == ASSOCIATES_CHARACTER_SPOUSE)
        {
            if(!isset($input['guarantor'])){
                $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
            }
        }

        //同登入的電話號碼(不可加入自己)
        if($content["phone"] == $this->user_info->phone){
            $this->response(['result' => 'ERROR', 'error' => NO_ALLOW_CHARGE, 'msg' => '不可與登入的電話號碼相同']);
        }

        if(
            !preg_match('/^09[0-9]{2}[0-9]{6}$/', $content["phone"])
            || !preg_match('/^[\x{4e00}-\x{9fa5}]{2,15}$/u',$content['name'])
            || mb_strlen($content['name']) < 2
            || mb_strlen($content['name']) > 15
            || !isset($characters[$character])
        ){
            $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
        }

        if (!check_cardid($content['id_number'])) {
            $this->response(['result' => 'ERROR', 'error' => CERTIFICATION_IDNUMBER_ERROR]);
        }

        $targetId = intval($content['target_id']);

        $this->load->model('loan/target_model');
        $target = $this->target_model->get($targetId);

        if (!$target) {
            $this->response(['result' => 'ERROR','error' => TARGET_NOT_EXIST]);
        }

        if ($target->user_id != $this->user_info->id) {
            $this->response(['result' => 'ERROR','error' => INPUT_NOT_CORRECT]);
        }

        //取得歸戶資料
        $user = $this->user_model->get_by(['id_number' => strval($content['id_number'])]);

        $userId = $user->id ?? NULL;
        if ($this->user_info->id == $userId ) {
            $this->response(['result' => 'ERROR','error' => TARGET_SAME_USER]);
        }

        $associate = [
            'target_id' => $targetId,
            'product_id' => $target->product_id,
            'sub_product_id' => $target->sub_product_id,
            'user_id' => $userId,
            'identity' => 2,
            'id_number' => strval($content['id_number']),
            'is_applicant' => 0,
            'character' => intval($content["character"]),
            'guarantor' => isset($input['guarantor']) ? ($input['guarantor'] == 0 ? 0 : 1) : 1,
            'relationship' => intval($content["relationship"]),
            'status' => 0,
            'content' => json_encode($input),
        ];

        $this->load->model('loan/target_associate_model');

        $associates = $this->target_lib->get_associates_user_data($targetId, 'all', 0, false);
        foreach ($associates as $value)
        {
            if ($character == ASSOCIATES_CHARACTER_OWNER && $value->character == ASSOCIATES_CHARACTER_OWNER)
            {
                $this->response(['result' => 'ERROR', 'error' => TARGET_OWNER_EXIST]);
            }
            elseif ($content['id_number'] == $value->id_number)
            {
                $this->response(['result' => 'ERROR', 'error' => TARGET_AGITATE_EXIST]);
            }
        }

        $result = $this->target_associate_model->insert($associate);

        if (!$result) {
            $this->response(['result' => 'EXIT_ERROR']);
        }

        if ($userId)
        {
            $relationship_mapping = [
                'A', // 配偶
                'B', // 血親
                'C', // 姻親
                'D', // 股東
                'E', // 朋友
                'F', // 本人
                'G', // 其他
                'H', // 經營有關之借戶職員
            ];

            $year_fields = ['StartYear', 'EndYear'];
            foreach ($year_fields as $year)
            {
                if (empty($input[$year]) || ! strtotime($input[$year]))
                {
                    $input[$year] = '';
                    continue;
                }
                $input[$year] = (int) $input[$year] - 1911;
            }

            $this->load->model('user/user_certification_model');
            switch ($character)
            {
                case ASSOCIATES_CHARACTER_REAL_OWNER: // 實際負責人
                    $uc_id = $this->user_certification_model->insert([
                        'user_id' => $userId,
                        'certification_id' => CERTIFICATION_PROFILE,
                        'investor' => BORROWER,
                        'content' => json_encode([
                            'prRelationship' => $relationship_mapping[$content['relationship']] ?? '',
                            'othRealPrStartYear' => $input['StartYear'],
                            'othRealPrEndYear' => $input['EndYear'],
                            'othRealPrSHRatio' => (int) ($input['SHRatio'] ?? 0),
                            'othRealPrTitle' => $input['PrTitle'] ?? '',
                        ]),
                        'status' => CERTIFICATION_STATUS_PENDING_TO_REVIEW,
                    ]);
                    if ( ! $uc_id)
                    {
                        break;
                    }
                    $this->user_certification_model->update_by([
                        'user_id' => $userId,
                        'investor' => BORROWER,
                        'certification_id' => CERTIFICATION_PROFILE,
                        'id !=' => $uc_id
                    ], ['status' => CERTIFICATION_STATUS_FAILED]);
                    break;
                case ASSOCIATES_CHARACTER_SPOUSE: // 配偶

                    // [前提] 提交個人及公司「聯徵」時，會幫公司、負責人、負責人配偶(若有)新增一條 user_certification
                    // 若新增當下配偶未歸戶者，會將配偶的 user_certification 掛在負責人名下
                    // [現] 要將暫時掛在負責人名下的「聯徵A11」，轉到配偶名下，條件：
                    // 1. user_id = 負責人user_id
                    // 2. status = CERTIFICATION_STATUS_PENDING_SPOUSE_ASSOCIATE

                    $cert_a11_info = $this->user_certification_model->get_by([
                        'user_id' => $this->user_info->naturalPerson->id, // 負責人user_id
                        'certification_id' => CERTIFICATION_INVESTIGATIONA11,
                        'investor' => $this->user_info->investor,
                        'status' => CERTIFICATION_STATUS_PENDING_SPOUSE_ASSOCIATE
                    ]);
                    if ( ! empty($cert_a11_info))
                    {
                        $this->user_certification_model->update($cert_a11_info->id, [
                            'user_id' => $userId, // 配偶user_id
                            'status' => CERTIFICATION_STATUS_PENDING_TO_REVIEW // 待驗證
                        ]);
                    }
                    else
                    {
                        $this->user_certification_model->insert([
                            'user_id' => $userId, // 配偶user_id
                            'certification_id' => CERTIFICATION_INVESTIGATIONA11,
                            'investor' => BORROWER,
                            'content' => json_encode([]),
                            'status' => CERTIFICATION_STATUS_PENDING_TO_REVIEW // 待驗證
                        ]);
                    }
                    break;
                case ASSOCIATES_CHARACTER_GUARANTOR_A: // 保證人
                    $uc_id = $this->user_certification_model->insert([
                        'user_id' => $userId,
                        'certification_id' => CERTIFICATION_PROFILE,
                        'investor' => BORROWER,
                        'content' => json_encode([
                            'guOneRelWithPr' => $relationship_mapping[$content['relationship']] ?? '',
                            'guOneStartYear' => $input['StartYear'],
                            'guOneEndYear' => $input['EndYear'],
                            'guOneSHRatio' => (int) ($input['SHRatio'] ?? 0),
                            'guOneTitle' => $input['PrTitle'] ?? '',
                        ]),
                        'status' => CERTIFICATION_STATUS_PENDING_TO_REVIEW,
                    ]);
                    if ( ! $uc_id)
                    {
                        break;
                    }
                    $this->user_certification_model->update_by([
                        'user_id' => $userId,
                        'investor' => BORROWER,
                        'certification_id' => CERTIFICATION_PROFILE,
                        'id !=' => $uc_id
                    ], ['status' => CERTIFICATION_STATUS_FAILED]);
                    break;
            }
        }

        $productName = '';
        if ($target->sub_product_id == 4) {
            $productName = "孵化基金";
        } elseif ($target->sub_product_id == 5) {
            $productName = "驗資基金";
        }
        elseif ($target->product_id == PRODUCT_SK_MILLION_SMEG)
        {
            switch ($target->sub_product_id)
            {
                case SUB_PRODUCT_ID_SK_MILLION:
                    $productName = '普匯微企e秒貸';
                    break;
                default:
                    $productName = '普匯信保專案融資';
                    break;
            }
        }

        $is = $character == 1 ? '借款立約人' : '保證人';
        $this->load->library('sms_lib');
        if ($target->product_id == 1002) {
            // 普匯微企e秒貸
            $this->sms_lib->notify_target_product_1002_associates(
                $this->user_info->id,
                $this->user_info->phone,
                $naturalPerson->name,
                $productName,
                $character
            );
        } else {
            $this->sms_lib->notify_target_associates(
                $this->user_info->id,
                $this->user_info->phone,
                $this->user_info->name,
                $productName,
                $is
            );
        }

        if(isset($input['mail'])){
            $this->load->library('notification_lib');
            if ($target->product_id == 1002) {
                // 普匯微企e秒貸
                $this->notification_lib->notify_target_product_1002_associates(
                    $input['mail'],
                    $naturalPerson->name,
                    $productName,
                    $character
                );
            }
            else{
                $this->notification_lib->notify_target_associates(
                    $input['mail'],
                    $this->user_info->name,
                    $productName,
                    $is
                );
            }
        }

        $this->response(['result' => 'SUCCESS']);
    }

    public function delassociates_post()
    {
        $input = $this->input->post(NULL, TRUE);
        $user_id 	= $this->user_info->id;

        $fields = ['target_id', 'index'];
        foreach ($fields as $field) {
            if (!isset($input[$field]) && $input[$field] == '') {
                $this->response(['result' => 'ERROR','error' => INPUT_NOT_CORRECT]);
            }else{
                $content[$field] = $input[$field];
            }
        }

        $targetInfo = $this->target_model->get($content['target_id']);
        if($targetInfo){
            if ($targetInfo->user_id != $user_id) {
                $this->response(array('result' => 'ERROR', 'error' => TARGET_APPLY_NO_PERMISSION));
            }
            $associates = $this->target_lib->get_associates_user_data($content['target_id'], 'all', [0, 1], false);
            $this->load->model('loan/target_associate_model');
            if(isset($associates[$content['index']])){
                $this->target_associate_model->update_by([
                    'id' => $associates[$content['index'] + 1]->id,
                    'target_id' => $content['target_id'],
                ], [
                    'status' => 9,
                ]);
                $this->response(array('result' => 'SUCCESS'));
            }
            $this->response(['result' => 'ERROR','error' => INPUT_NOT_CORRECT]);
        }
        $this->response(array('result' => 'ERROR', 'error' => TARGET_NOT_EXIST));
    }

    public function targetdata_get($id){
        if($id){
            $user_id = $this->user_info->id;
            $target = $this->target_model->get_by([
                'id' => $id,
                'user_id' => $user_id,
                'status' => [0,1,30],
            ]);
            $list = [];
            if($target){
                $product_list = $this->config->item('product_list');
                $product = $product_list[$target->product_id];
                $sub_product_id = $target->sub_product_id;
                if($this->is_sub_product($product,$sub_product_id)){
                    $product = $this->trans_sub_product($product,$sub_product_id);
                }
                $targetData = json_decode($target->target_data);
                foreach ($product['targetData'] as $key => $value) {
                    $res = !empty($targetData->$key);
                    isset($value[3]) && $value[3] ? $res = true : '';
                    $list = array_merge($list,[$key => $res]);
                }
                $this->response(['result' => 'SUCCESS','data' => ['list' => $list] ]);
            }
            $this->response(array('result' => 'ERROR','error' => APPLY_STATUS_ERROR ));
        }
        $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
    }

    public function targetdata_post(){
        $input = $this->input->post(NULL, TRUE);
        if(isset($input['id'])){
            $user_id = $this->user_info->id;
            $target = $this->target_model->get_by([
                'id' => $input['id'],
                'user_id' => $user_id,
                'status' => [0,1,30],
            ]);
            if($target){
                $product_list = $this->config->item('product_list');
                $product = $product_list[$target->product_id];
                $sub_product_id = $target->sub_product_id;
                if($this->is_sub_product($product,$sub_product_id)){
                    $product = $this->trans_sub_product($product,$sub_product_id);
                }

                $targetData = json_decode($target->target_data);
                !$targetData ? $targetData = new stdClass() : '';
                foreach ($targetData as $key => $value) {
                    if(array_key_exists($key,$product['targetData'])  && !empty($input[$key])){
                        if($product['targetData'][$key][0] == 'Picture'){
                            $targetData->$key = $this->get_pic_url($user_id,$key,$input[$key],$product['targetData'][$key][2]);
                        }
                        else{
                            $targetData->$key = $input[$key];
                        }
                    }
                }
                $targetData = json_encode($targetData);
                $rs = $this->target_model->update($target->id,[
                    'target_data' => $targetData,
                ]);
                if($rs){
                    $this->response(['result' => 'SUCCESS']);
                }
                $this->response(array('result' => 'ERROR','error' => APPLY_ACTION_ERROR ));
            }
            $this->response(array('result' => 'ERROR','error' => APPLY_STATUS_ERROR ));
        }
        $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
    }

    private function sub_product_profile($product,$sub_product){
        return array(
            'id' => $product['id'],
            'visul_id' => $sub_product['visul_id'],
            'type' => $product['type'],
            'identity' => $product['identity'],
            'name' => $sub_product['name'],
            'description' => $sub_product['description'],
            'loan_range_s' => $sub_product['loan_range_s'],
            'loan_range_e' => $sub_product['loan_range_e'],
            'interest_rate_s' => $sub_product['interest_rate_s'],
            'interest_rate_e' => $sub_product['interest_rate_e'],
            'charge_platform' => $sub_product['charge_platform'],
            'charge_platform_min' => $sub_product['charge_platform_min'],
            'certifications' => $sub_product['certifications'],
            'instalment' => $sub_product['instalment'],
            'repayment' => $sub_product['repayment'],
            'targetData' => $sub_product['targetData'],
            'dealer' => $sub_product['dealer'],
            'multi_target' => $sub_product['multi_target'],
            'checkOwner' => $sub_product['checkOwner'] ?? FALSE,
            'status' => $sub_product['status'],
            'allow_age_range' => $sub_product['allow_age_range'] ?? [18, 55],
            'apply_range_s' => $sub_product['apply_range_s'] ?? null,
            'apply_range_e' => $sub_product['apply_range_e'] ?? null,
            'need_upload_images' => $sub_product['need_upload_images'] ?? null,
            'available_company_categories' => $sub_product['available_company_categories'] ?? null,
            'default_reason' => $sub_product['default_reason'] ?? '',
            'check_associates_certs' => isset($sub_product['check_associates_certs']) && $sub_product['check_associates_certs'] === TRUE ? TRUE : FALSE,
        );
    }

    private function get_cooperation_info($store_id){
        //檢核經銷商是否存在
        $this->load->library('coop_lib');
        $cooperation = $this->coop_lib->get_cooperation_info($store_id);
        if(!$cooperation){
            $this->response(['result' => 'ERROR','error' => COMPANY_NOT_EXIST]);
        }
        return $cooperation;
    }

    private function get_store_id($company_user_id){
        //檢核經銷商是否存在
        $this->load->library('coop_lib');
        $store_id = $this->coop_lib->get_store_id($company_user_id);
        if(!$store_id){
            $this->response(['result' => 'ERROR','error' => COMPANY_NOT_EXIST]);
        }
        return $store_id;
    }

    // 信用貸款申請
    private function type1_apply($param,$product,$input){

        $sub_product_rule = false;

        // 多案申請判斷
        if($product['multi_target'] == 0){
            $condition = [
                'status <=' => 1,
                'user_id' => $param['user_id'],
                'product_id' => $param['product_id'],
                'sub_product_id' => $param['sub_product_id']
            ];

            // 學生貸跟名校貸不可同時存在
            if ($param['product_id'] == PRODUCT_ID_STUDENT &&
                ($param['sub_product_id'] == SUBPRODUCT_INTELLIGENT_STUDENT || $param['sub_product_id'] == 0))
            {
                $condition['sub_product_id'] = [0, SUBPRODUCT_INTELLIGENT_STUDENT];
            }

            $exist = $this->target_model->get_by(
                $condition
            );

            if ($exist) {
                $this->response(['result' => 'ERROR', 'error' => APPLY_EXIST]);
            }
        }

        $param['reason'] = $input['reason'] ?? ($product['default_reason'] ?? '');
        if(isset($input['reason_description'])&&!empty($input['reason_description'])){
            $build = [
                'reason' => $param['reason'],
                'reason_description' => $input['reason_description']
            ];
            $param['reason'] = json_encode($build) ;
        }

        isset($input['promote_code'])?$param['promote_code'] = $input['promote_code']:'';

        //邀請碼保留月
        if(!isset($param['promote_code']) && strtotime(' -'.TARGET_PROMOTE_LIMIT.' month',time()) <= $this->user_info->created_at && $this->user_info->promote_code != ''){
            $param['promote_code'] = $this->user_info->promote_code;
        }

        $method = $product['visul_id'];
        if(method_exists($this, $method)){
            $sub_product_rule = $this->$method($param,$product,$input);
            $insert = $sub_product_rule;
        }

        if(!$sub_product_rule){
            //必填欄位
            $fields 	= ['amount','instalment'];
            foreach ($fields as $field) {
                if (empty($input[$field])) {
                    $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                }else{
                    $param[$field] = intval($input[$field]);
                }
            }
            $insert = $this->target_lib->add_target($param);
        }

        if ($insert) {

            if ( ! empty($input['target_meta']) && is_array($input['target_meta']))
            {
                $target_id_list = array_fill(0, count($input['target_meta']), ['target_id' => $insert]);
                $target_meta_list = array_replace_recursive($input['target_meta'], $target_id_list);
                $this->load->model('loan/target_meta_model');
                $this->target_meta_model->insert_many($target_meta_list);
            }

            $target = $this->target_model->get_by(['id' => $insert]);
            $this->load->helper('product');
            $this->load->model('log/log_targetschange_model');
            if (is_judicial_product($target->product_id) === FALSE && $target->status == TARGET_WAITING_SIGNING)
            {
                // 該產品有未使用額度
                $this->load->library('loanmanager/product_lib');
                $product_info = $this->product_lib->getProductInfo($target->product_id, $target->sub_product_id);

                $credit = $this->credit_lib->get_credit($target->user_id, $target->product_id, $target->sub_product_id, $target);
                $interest_rate = $credit['rate'] ?? 0;
                $contract_type = 'lend';
                $contract_data = ['', $target->user_id, $target->loan_amount, $interest_rate, ''];

                $this->target_model->update($insert, [
                    'platform_fee' => $this->financial_lib->get_platform_fee($target->loan_amount, $product_info['charge_platform']),
                    'interest_rate' => $interest_rate,
                    'contract_id' => $this->contract_lib->sign_contract($contract_type, $contract_data)
                ]);
                $this->log_targetschange_model->insert(
                    $this->target_lib->get_target_log_param($insert, FALSE, [
                        'interest_rate' => $interest_rate
                    ])
                );

                $this->load->model('loan/credit_sheet_model');
                $credit_sheet_info = $this->credit_sheet_model->order_by('created_at', 'desc')->get_by([
                    'credit_id' => $credit['id'],
                    'status' => 1
                ]);

                // 寫授審表
                $creditSheet = \CreditSheet\CreditSheetFactory::getInstance($target->id);
                $creditSheet->approve($creditSheet::CREDIT_REVIEW_LEVEL_SYSTEM, '一審通過');
                $creditSheet->setFinalReviewerLevel($creditSheet::CREDIT_REVIEW_LEVEL_SYSTEM);
                $creditSheet->archive($credit);

                // 回寫當初給額度時，授審表封存的徵信項（更新 targets.target_data、credit_sheet.certification_list）
                if ( ! empty($credit_sheet_info->certification_list))
                {
                    $certification_id = json_decode($credit_sheet_info->certification_list, TRUE);
                    $this->credit_sheet_model->update_by(['target_id' => $target->id], ['certification_list' => json_encode($certification_id)]);

                    // Set up verify_cetification_list

                    // Get product certifications.
                    $product_cert_ids = [];
                    $product_info2 = $this->product_lib->get_exact_product($target->product_id, $target->sub_product_id);
                    if (array_key_exists('certifications_stage', $product_info2)) {

                        // Merge certifications_stage.
                        foreach ($product_info2['certifications_stage'] as $stage) {
                            $product_cert_ids = array_merge($product_cert_ids, $stage);
                        }

                        // Substract option_certifications.
                        $option_certifications = [];
                        if (array_key_exists('option_certifications', $product_info2)) {
                            $option_certifications = $product_info2['option_certifications'];
                        }
                        $product_cert_ids = array_values(array_diff($product_cert_ids, $option_certifications));
                    }

                    $this->load->model('user/user_certification_model');
                    $cert_ids = $this->user_certification_model->get_ids($certification_id, $target->user_id, $product_cert_ids);

                    // Cast elements to string.
                    $user_certification_ids = [];
                    foreach ($cert_ids as $cert) {
                        $user_certification_ids[] = (string) $cert;
                    }

                    $target_data = json_decode($target->target_data ?? '', TRUE);
                    $target_data['verify_cetification_list'] = json_encode($user_certification_ids);


                    $target_data['certification_id'] = $certification_id;
                    $this->target_model->update($insert, ['target_data' => json_encode($target_data)]);
                }
            }

            $this->load->library('Certification_lib');
            if ($param['sub_product_id'] != 0) {
                $certification = $this->user_certification_model->order_by('created_at', 'desc')->get_by([
                    'user_id' => $param['user_id'],
                    'certification_id' => ($product['identity'] == 1 ? 2 : 10),
                    'investor' => 0,
                    'status' => [0,1,3],
                ]);
                if ($certification && $param['sub_product_id'] == 1) {
                    $this->certification_lib->set_failed($certification->id, '申請新產品。', true);
                }
            }
            if ($param['product_id'] == 5 &&
                in_array($param['sub_product_id'], [SUB_PRODUCT_ID_HOME_LOAN_SHORT, SUB_PRODUCT_ID_HOME_LOAN_RENOVATION, SUB_PRODUCT_ID_HOME_LOAN_APPLIANCES])
            ) {
                $cancel_booking_result = $this->target_lib->cancel_booking_and_certification($param['user_id']);
                if (!$cancel_booking_result) {
                    $this->response(array('result' => 'ERROR', 'error' => '取消預約時間失敗'));
                }
            }
            // 認證逾期判斷處理
            $this->certification_lib->expire_certification($param['user_id']);
            $this->response(['result' => 'SUCCESS', 'data' => ['target_id' => $insert]]);
        } else {
            $this->response(['result' => 'ERROR', 'error' => INSERT_ERROR]);
        }
    }

    private function rate_increased_notification_content($invest_time, $old_rate, $new_rate, $for_app_notification=FALSE): string
    {
        $old_rate = rtrim(strval($old_rate), '0');
        $old_rate = rtrim($old_rate, '.');
        $message = "親愛的投資人請注意：
			您的投資標的有一項重大變更！
			您於" .  date("m月d日", $invest_time) . "投資的債權，剛剛自主提升利率由
			".$old_rate."% → ".$new_rate."%。
			同樣的項目，更高的潛在收益！
			請登錄普匯APP 下標搶佔先手";
        if ($for_app_notification)
        {
            $message = preg_replace('/\s/', '', $message);
            $message = preg_replace('/親愛的投資人請注意：/', '', $message);
        }
        return $message;
    }

    // 消費貸款申請
    private function type2_apply($param,$product,$input,$designate=false)
    {
        $designate?$input = $designate:'';
        $user_id = $param['user_id'];
        $name = '';
        $phone = '';
        $id_number = '';
        $address = '';
        $amount = 0;
        $item_price = 0;

        $fields 	= ['instalment','store_id','item_id'];
        foreach ($fields as $field) {
            if (!isset($input[$field])) {
                $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }else{
                $content[$field] = $input[$field];
            }
        }
        $product_id		= $param['product_id'];
        $sub_product_id		= $param['sub_product_id'];
        $instalment     = $content['instalment'];
        $store_id       = $content['store_id'];
        $item_id		= $content['item_id'];
        $nickname =  isset($content['nickname'])?$content['nickname']:'';

        $item_count		= isset($content['item_count'])&&$content['item_count']<2?$content['item_count']:1;//商品數量
        $delivery       = isset($content['delivery'])&&$content['delivery']<2?$content['delivery']:0;  //0:線下 1:線上

        if(!$designate){
            $name = $this->user_info->name;
            $phone = $this->user_info->phone;
            $id_number = $this->user_info->id_number;

            if(!in_array($input['instalment'],$product['instalment'])){
                $this->response(array('result' => 'ERROR','error' => PRODUCT_INSTALMENT_ERROR ));
            }

            //檢驗消費貸重複申請
            $exist = $this->target_model->get_by([
                'status'		=> [20,21],
                'user_id'		=> $user_id,
                'product_id'	=> $product_id
            ]);
            if($exist){
                $this->response(['result' => 'ERROR','error' => APPLY_EXIST]);
            }

            //交易方式
            $address    = '';
            if($delivery == 1){
                if (!isset($input['address'])) {
                    $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                }else{
                    $address = $input['address'];
                }
            }
        }

        $interest_rate = $product['interest_rate_s'];

        $cooperation = $this->get_cooperation_info($store_id);
        if(!in_array($cooperation->selling_type, $product['dealer'])){
            $this->response(array('result' => 'ERROR','error' => COOPERATION_TYPE_ERROR ));
        }
        $cooperation_id  = $cooperation -> cooperation_id;
        $company_user_id = $cooperation -> company_user_id;

        //對經銷商系統建立訂單
        $user_name    = mb_substr($name,0,1,"utf-8").(substr($id_number,1,1)==1?'先生':(substr($id_number,1,1)==2?'小姐':'先生/小姐'));
        if($designate){
            $amount = $designate['amount'];
            $item_price = $designate['item_price'];
        }
        $result = $this->coop_lib->coop_request('order/screate',[
            'cooperation_id' => $cooperation_id,
            'selling_type' => 0,
            'item_id'        => $item_id,
            'item_count'     => $item_count,
            'item_price'     => $item_price,
            'instalment'     => $instalment,
            'interest_rate'  => $interest_rate,
            'delivery'       => $delivery,
            'name'           => $user_name,
            'nickname'       => $nickname,
            'phone'          => $phone,
            'address'        => $address,
        ],$user_id);
        if(isset($result->result) && $result->result == 'SUCCESS'){
            $item_name = $result->data->product_name.
                ($result->data->product_spec!='-'
                    ?$result->data->product_spec
                    :''
                );
            $merchant_order_no = $result->data->merchant_order_no;
            $product_price     = $item_price>0?$item_price:$result->data->product_price;
            $platform_fee      = 0;
            $transfer_fee      = 0;
            $total             = 0;
            //建立主系統訂單
            if($product_price > 0 && $designate){
                $platform_fee = $this->financial_lib->get_platform_fee2($product_price, $product['charge_platform']);
                $transfer_fee = $this->financial_lib->get_transfer_fee( $product_price + $platform_fee);
                $total        = $amount + $platform_fee + $transfer_fee;
            }

            $order_parm = [
                'company_user_id'   => $company_user_id,
                'order_no'          => $store_id.'-'.date('YmdHis').rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9),
                'merchant_order_no' => $merchant_order_no,
                'phone'             => $phone,
                'product_id'	    => $product_id,
                'sub_product_id'	=> $sub_product_id,
                'instalment'	    => $instalment,
                'item_price'        => $product_price,
                'item_name'         => $item_name,
                'item_count'        => $item_count,
                'amount'            => $amount,
                'platform_fee'	    => $platform_fee,
                'transfer_fee'      => $transfer_fee,
                'total'             => $total,
                'delivery'          => $delivery,
                'nickname'          => $nickname,
                'status'            => 0
            ];
            $this->load->model('transaction/order_model');
            $order_insert = $this->order_model->insert($order_parm);
            if($order_insert){
                if($designate){
                    return $order_insert;
                }

                $param = array_merge($param,[
                    'amount'		=> $total,
                    'instalment'	=> $instalment,
                    'platform_fee'  => $platform_fee,
                    'order_id'		=> $order_insert,
                    'reason'		=> '分期:'.$item_name,
                    'status'        => 20,
                ]);
                //建立產品單號
                $insert = $this->target_lib->add_target($param);
            }
            if($order_insert && $insert){
                $this->notification_lib->notice_order_apply($company_user_id,$item_name,$instalment,$delivery);
                $this->load->library('Certification_lib');
                $this->certification_lib->expire_certification($user_id);

                if ( ! empty($input['target_meta']) && is_array($input['target_meta']))
                {
                    $target_id_list = array_fill(0, count($input['target_meta']), ['target_id' => $insert]);
                    $target_meta_list = array_replace_recursive($input['target_meta'], $target_id_list);
                    $this->load->model('loan/target_meta_model');
                    $this->target_meta_model->insert_many($target_meta_list);
                }

                $this->response(['result' => 'SUCCESS','data'=>['target_id'=> $insert ]]);
            }
            $this->target_lib->cancel_order($order_insert,$merchant_order_no,$user_id,$phone);
        }
        $this->response(['result' => 'ERROR','error' => $result->error ]);
    }

    private function DS1P1($param,$product,$input)
    {
        $fields 	= ['item_id','purchase_time','vin','factory_time','product_description','purchase_cost','fee_cost','sell_price'];
        foreach ($fields as $field) {
            if (empty($input[$field])) {
                $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }else{
                $content[$field] = $input[$field];
            }
        }
        $instalment = $input['instalment'];

        $store_id = $this->get_store_id($this->user_info->id);
        $order_insert = $this->type2_apply($param,$product,$input,[
            'product_id' => $param['product_id'],
            'instalment' => $instalment,
            'store_id' => $store_id,
            'item_id' => $content['item_id'],
            'item_price' => ($content['purchase_cost'] + $content['fee_cost']),
            'amount' => $content['sell_price'],
            'interest_rate' => FEV_INTEREST_RATE,
        ]);
        if($order_insert){
            $target_data = [
                'purchase_time' => $content['purchase_time'],
                'vin' => $content['vin'],
                'factory_time' => $content['factory_time'],
                'product_description' => $content['product_description'],
            ];
            $param = array_merge($param,[
                'amount'		=> $content['purchase_cost'],
                'instalment'	=> $instalment,
                'order_id'		=> $order_insert,
                'target_data' => json_encode($target_data),
                'reason'		=> '購車',
                'status'        => 30,
            ]);
            $param2 = $param;
            $param2['amount'] = $content['fee_cost'];
            $param2['reason'] = '規費';
            $insert = $this->target_lib->add_target_group(
                [$param,$param2],
                ['A','B']
            );
            return $insert;
        }
    }

    private function DS2P1($param,$product,$input)
    {
        $fields 	= ['item_id','purchase_time','vin','factory_time','product_description','amount'];
        foreach ($fields as $field) {
            if (empty($input[$field])) {
                $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }else{
                $content[$field] = $input[$field];
            }
        }
        $instalment = $input['instalment'];

        $this->load->library('coop_lib');
        $result = $this->coop_lib->coop_request('product/list?type=2&item_id='.$content['item_id'],'',0,true);
        if(isset($result->result) && $result->result == 'SUCCESS'){
            $target_data = [
                'item_id' => $content['item_id'],
                'brand' => $result->data->list[0]->brand,
                'name' => $result->data->list[0]->name,
                'selected_image' => $result->data->list[0]->image,
                'purchase_time' => $content['purchase_time'],
                'vin' => $content['vin'],
                'factory_time' => $content['factory_time'],
                'product_description' => $content['product_description'],
            ];

            $target_data = $this-> build_targetData($product,$target_data);

            $param = array_merge($param,[
                'amount'		=> $content['amount'],
                'instalment'	=> $instalment,
                'target_data' => json_encode($target_data),
                'reason'		=> '在庫車',
                'status'        => 0,
            ]);
            $insert = $this->target_lib->add_target($param);
            return $insert;
        }
        $this->response(['result' => 'ERROR','error' => $result->error ]);
    }

    private function type1_signing($param,$product,$input,$target){
        $user_id 	= $target->user_id;

        //檢查金融卡綁定 NO_BANK_ACCOUNT
        $bank_account = $this->user_bankaccount_model->get_by([
            'status'	=> 1,
            'investor'	=> 0,
            //'verify'	=> 0,
            'user_id'	=> $user_id
        ]);
        if (empty($bank_account))
        {
            $this->response(array('result' => 'ERROR', 'error' => NO_BANK_ACCOUNT));
        }

        //上傳檔案欄位
        if (isset($_FILES['person_image']) && !empty($_FILES['person_image'])) {
            log_message('debug', '-- Before upload person image: '. time() . ' --');
            $image 	= $this->s3_upload->image($_FILES,'person_image',$user_id,'signing_target');
            log_message('debug', '-- After upload person image: '. time() . ' --');
            if($image){
                $param['person_image'] = $image;
            }else{
                $this->response(array('result' => 'ERROR, upload person_image failed','error' => INPUT_NOT_CORRECT ));
            }
        }else{
            $this->response(array('result' => 'ERROR, no person_image','error' => INPUT_NOT_CORRECT ));
        }

        if ($target->product_id == PRODUCT_ID_HOME_LOAN && $target->sub_product_id == SUB_PRODUCT_ID_HOME_LOAN_SHORT) {
            if (!isset($_FILES['deed_image']) || empty($_FILES['deed_image'])) {
                $this->response(array('result' => 'ERROR, no deed_image', 'error' => INPUT_NOT_CORRECT));
            }
            log_message('debug', '-- Before upload deed image: ' . time() . ' --');
            $image = $this->s3_upload->image($_FILES, 'deed_image', $user_id, 'signing_target');
            log_message('debug', '-- After upload deed image: ' . time() . ' --');
            if (!$image) {
                $this->response(array('result' => 'ERROR, upload deed_image failed', 'error' => INPUT_NOT_CORRECT));
            }
            $target_data = json_decode($target->target_data, true);
            $target_data['deed_image'] = $image;
            $param['target_data'] = json_encode($target_data);
        }

        $this->load->library('loanmanager/product_lib');
        if ($this->product_lib->need_chk_allow_age($target->product_id, $target->sub_prduct_id ?? 0) === TRUE)
        {
            $age = get_age($this->user_info->birthday);
            if ($this->product_lib->is_age_available($age, $target->product_id, $target->sub_product_id ?? 0) === FALSE)
            {
                $this->load->library('target_lib');
                $this->target_lib->target_verify_failed($target, 0, '身份非平台服務範圍');
                $this->response(array('result' => 'ERROR','error' => UNDER_AGE ));
            }
        }

        $signing_res = $this->target_lib->signing_target($target->id, $param, $user_id);
        if ($signing_res !== FALSE)
        {
            if ($bank_account->verify == 0)
            {
                $this->user_bankaccount_model->update($bank_account->id, ['verify' => 2]);
            }
        }
        log_message('debug', "[Product/type1_signing][user_bankaccount][verify] {$bank_account->verify}");

        $allow_fast_verify_product = $this->config->item('allow_fast_verify_product');
        if (in_array($product['id'], $allow_fast_verify_product)
            && $target->sub_product_id != STAGE_CER_TARGET
            && $target->sub_status != 8
            && $bank_account->verify == 1) {
            $targetData = json_decode($target->target_data);
            $faceDetect = isset($targetData->autoVerifyLog)
            ? count($targetData->autoVerifyLog) < 2
            : true;
            if ($faceDetect) {
                $this->load->library('certification_lib');
                log_message('debug', '-- Before verify signing face: ' . time() . ' --');
                $faceDetect_res = $this->certification_lib->veify_signing_face($target->user_id, $param['person_image']);
                log_message('debug', '-- After verify signing face: ' . time() . ' --');
                if (isset($faceDetect_res['error']) && $faceDetect_res['error'] == '') {
                    $target->status = TARGET_WAITING_VERIFY;
                    $targetData->autoVerifyLog[] = [
                        'faceDetect' => $faceDetect_res,
                        'res' => TARGET_WAITING_BIDDING,
                        'verify_at' => time()
                    ];
                    $param['target_data'] = json_encode($targetData);
                    $this->target_lib->target_verify_success($target, 0, $param);
                } else {
                    $targetData->autoVerifyLog[] = [
                        'faceDetect' => $faceDetect_res,
                        'res' => TARGET_WAITING_SIGNING,
                        'verify_at' => time()
                    ];
                    $param['target_data'] = json_encode($targetData);
                    $this->target_lib->target_sign_failed($target, 0, $product['name'], $param);
                }
            }
        }

        $this->response(array('result' => 'SUCCESS'));
    }

    private function is_sub_product($product,$sub_product_id){
        $sub_product_list = $this->config->item('sub_product_list');
        return isset($sub_product_list[$sub_product_id]['identity'][$product['identity']]) && in_array($sub_product_id,$product['sub_product']);
    }

    private function trans_sub_product($product,$sub_product_id){
        $sub_product_list = $this->config->item('sub_product_list');
        $sub_product_data = $sub_product_list[$sub_product_id]['identity'][$product['identity']];
        $product = $this->sub_product_profile($product,$sub_product_data);
        return $product;
    }

    private function build_targetData($product,$target_data){
        if(count($product['targetData']) > 0){
            foreach ($product['targetData'] as $key => $value) {
                if(empty($target_data[$key])){
                    $target_data = array_merge($target_data, [$key => '']);
                }
            }
            return $target_data;
        }
    }

    private function get_pic_url($user_id, $key, $image_ids, $limit)
    {
        $this->load->library('S3_upload');
        $image_ids = explode(',', $image_ids);
        if (count($image_ids) > $limit) {
            $image_ids = array_slice($image_ids, 0, $limit);
        }
        $this->load->model('log/log_image_model');
        $list = $this->log_image_model->get_many_by([
            'id' => $image_ids,
            'user_id' => $user_id,
        ]);

        if ($list && count($list) == count($image_ids)) {
            $content = [];
            foreach ($list as $k => $v) {
                $content[] = $v->url;
                preg_match('/image.+/', $v->url, $matches);
                $this->s3_upload->public_image_by_data(file_get_contents($v->url), FRONT_S3_BUCKET, $user_id, [
                    'type' => 'stmps/tarda',
                    'name' => $matches[0],
                ]);

            }
            return $content;
        } else {
            $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
        }
    }

    /**
     * @api {get} /v2/product/target_verify_status 借款方 取得案件審核狀態
     * @apiVersion 0.2.0
     * @apiName GetTargetVerifyStatus
     * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiGroup Product
     *
     * @apiParam {Number} target_id 案件流水號
     *
     * @apiSuccess {String} result 響應結果
     * @apiSuccess {Number} status 案件審核狀態 (1:已送出審核, 0:待送出審核)
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {"status": 1}
     *    }
     *
     */
    public function target_verify_status_get()
    {
        $input 		= $this->input->get(NULL, TRUE);
        $user_id 	= $this->user_info->id;
        $investor 	= $this->user_info->investor;
        $targetId   = $input['target_id'];

        if(!isset($targetId)) {
            $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT));
        }

        $target = $this->target_model->get($targetId);
        if(!isset($target)) {
            $this->response(array('result' => 'ERROR','error' => TARGET_NOT_EXIST));
        }else if($user_id != $target->user_id) {
            $this->response(array('result' => 'ERROR','error' => PERMISSION_DENY));
        }

        if($investor != 0) {
            $this->response(array('result' => 'ERROR','error' => IS_INVERTOR));
        }

        $this->load->library('Certification_lib');

        $targetVerifying = $this->chk_target_verifying($target->target_data);

        $this->response(['result' => 'SUCCESS', 'data' => ['status' => $targetVerifying ? 1 : 0]]);

    }

    private function chk_target_verifying($target_data): bool
    {
        $data = json_decode($target_data, TRUE);
        if (isset($data['verify_cetification_list']))
        {
            $data['verify_cetification_list'] = json_decode($data['verify_cetification_list'], TRUE);
            $this->load->model('user/user_certification_model');
            if ( ! empty($this->user_certification_model->chk_verifying_by_ids($data['verify_cetification_list'])))
            {
                return FALSE;
            }
        }
        else
        {
            return FALSE;
        }

        return TRUE;
    }

    private function NS2P1($param, $product, $input)
    {
        $characters = $this->config->item('character');
        $character = isset($input['character']) ? $input['character'] : false;
        if($character){
            if($character == 1){
                $fields = ['amount', 'instalment'];
                foreach ($fields as $field) {
                    if (empty($input[$field])) {
                        $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
                    }else{
                        $param[$field] = intval($input[$field]);
                    }
                }
            }else{
                $param['amount'] = 0;
                $param['instalment'] = 0;
            }
        }
        if (isset($input['target_id']) && $input['target_id'] != '') {
            $this->associatesapply($param, $product, $input);
        }elseif ($character && !isset($characters[$character])) {
            $this->response(['result' => 'ERROR','error' => INPUT_NOT_CORRECT]);
        }

        $param['target_data'] = json_encode($this-> build_targetData($product,[]));
        $param['sub_status'] = 11;
        $insert = $this->target_lib->add_target($param);

        //$user = $this->user_model->get_by(['user_id' => $param['user_id']]);
        $associate = [
            'target_id' => intval($insert),
            'product_id' => $param['product_id'],
            'sub_product_id' => $param['sub_product_id'],
            'user_id' => $param['user_id'],
            'identity' => $product['identity'],
            'is_applicant' => 1,
            'character' => intval($input["character"]),
            'status' => 0,
        ];

        $this->load->model('loan/target_associate_model');
        $this->target_associate_model->insert($associate);

        return $insert;
    }
    private function NS2P2($param, $product, $input){
        return $this->NS2P1($param, $product, $input);
    }

    private function NS3P1($param, $product, $input){
        return $this->NS2P1($param, $product, $input);
    }

    private function NS3P2($param, $product, $input){
        return $this->NS2P1($param, $product, $input);
    }

    private function J1($param, $product, $input){
        return $this->NS2P1($param, $product, $input);
    }

    private function J2($param, $product, $input){
        if (isset($input['target_id']) && $input['target_id'] != '') {
            $this->associatesAgree($param, $product, $input);
        }

        if($this->user_info->company != 1){
            $this->response(array('result' => 'ERROR', 'error' => NOT_COMPANY));
        }

        if (!isset($input['character']) && empty($input['character'])) {
            $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
        }

        $fields 	= ['amount','instalment'];
        foreach ($fields as $field) {
            if (empty($input[$field])) {
                $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }else{
                $param[$field] = intval($input[$field]);
            }
        }

//        $targetData = $this-> build_targetData($product,[]);

        $param['damage_rate'] = 0;
        $param['sub_status'] = 11;
        $insert = $this->target_lib->add_target($param);

        $associate = [
            'target_id' => intval($insert),
            'product_id' => $param['product_id'],
            'sub_product_id' => $param['sub_product_id'],
            'user_id' => $this->user_info->naturalPerson->id,
            'identity' => 2,
            'is_applicant' => 1,
            'character' => intval($input["character"] == 2 ? 0 : 1),
            'status' => 1,
        ];

        $this->load->model('loan/target_associate_model');
        $this->target_associate_model->insert($associate);

        return $insert;
    }

    private function LJ2P1($param, $product, $input)
    {
        return $this->J2($param, $product, $input);
    }

    private function LJ3P1($param, $product, $input)
    {
        return $this->J2($param, $product, $input);
    }

    private function associatesapply($param, $product, $input)
    {
        $product_id = $param["product_id"];
        $sub_product_id = $param['sub_product_id'];
        $target_id = $input['target_id'];
        $user_id = $this->user_info->id;
        $target = $this->target_model->get_by([
            'id' => $target_id,
            'status' => 0,
        ]);

        if ($target) {
            if ($sub_product_id != $target->sub_product_id) {
                $this->response(array('result' => 'ERROR', 'error' => PRODUCT_TYPE_ERROR));
            }

            $associates_target = $this->target_lib->get_associates_target_list($this->user_info->id, $target->id);
            if ($associates_target) {
                if($associates_target->associate['status'] == 0){
                    if ($associates_target->associate['owner']) {
                        $instalment = isset($input['instalment']) ? $input['instalment'] : 0;
                        if (!in_array($input['instalment'], $product['instalment'])) {
                            $this->response(array('result' => 'ERROR', 'error' => PRODUCT_INSTALMENT_ERROR));
                        }
                        $param['instalment'] = $instalment;
                        $amount = isset($input['amount']) ? $input['amount'] : 0;
                        if (($amount < $product['loan_range_s'] || $amount > $product['loan_range_e']) && $product['type'] != 2) {
                            $this->response(array('result' => 'ERROR', 'error' => PRODUCT_AMOUNT_RANGE));
                        }
                        $param['amount'] = $amount;
                        $this->target_model->update($target->id, $param);
                    }
                    $this->load->model('loan/target_associate_model');
                    $this->target_associate_model->update_by([
                        'target_id' => $target_id,
                        'user_id' => $user_id,
                    ], [
                        'product_id' => $product_id,
                        'sub_product_id' => $sub_product_id,
                        'identity' => $product['identity'],
                    ]);
                    $this->response(array('result' => 'SUCCESS'));
                }
                $this->response(array('result' => 'ERROR', 'error' => APPLY_STATUS_ERROR));
            }
            $this->response(array('result' => 'ERROR', 'error' => APPLY_EXIST));
        }
    }

    private function associatesAgree($param, $product, $input)
    {
        $target_id = $input['target_id'];
        $user_id = $this->user_info->id;
        $target = $this->target_model->get_by([
            'id' => $target_id,
            'status' => 0,
        ]);
        if ($target) {
            $associates_target = $this->target_lib->get_associates_target_list($this->user_info->id, $target->id);
            if ($associates_target) {
                if($associates_target->associate['status'] == 0){
                    $this->load->model('loan/target_associate_model');
                    $this->target_associate_model->update_by([
                        'target_id' => $target_id,
                        'user_id' => $user_id,
                    ], [
                        'status' => 1,
                        'identity' => 2,
                    ]);
                    $this->response(array('result' => 'SUCCESS'));
                }
                $this->response(array('result' => 'ERROR', 'error' => APPLY_STATUS_ERROR));
            }
            $this->response(array('result' => 'ERROR', 'error' => APPLY_EXIST));
        }
    }

    /**
     * @api {get} /v2/product/chk_famous_school 借款方 檢查學校是否符合名校貸資格
     * @apiVersion 0.2.0
     * @apiName GetChkFamousSchool
     * @apiGroup Product
     *
     * @apiParam {String} school_short_name 學校英文名縮寫
     *
     * @apiSuccess {Boolean} result 檢查結果
     * @apiSuccessExample {Object} SUCCESS
     * {
     *     "result":"SUCCESS",
     *     "data":{
     *         "result":true
     *     }
     * }
     *
     */
    public function chk_famous_school_get($school_short_name)
    {
        $result = FALSE;

        // 名校清單
        $famous_school_list = $this->config->item('famous_school_list');

        if (isset($famous_school_list[strtoupper($school_short_name)]))
        {
            $result = TRUE;
        }

        $this->response(['result' => 'SUCCESS', 'data' => ['chk_result' => $result]]);
    }

    /**
     * @api {post} /v2/product/targetfaillist 借款方 取得申請失敗列表
     * @apiVersion 0.2.0
     * @apiName GetProductTargetfaillist
     * @apiGroup Product
     * @apiHeader {String} request_token 登入後取得的 Request Token
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
     * {
     *     'result':'SUCCESS',
     *     'data':{
     *         'list': [
     *             {
     *                 "product_name": "3S名校貸",
     *                 "remark": "",
     *                 "certifications": {
     *                     "1":{},
     *                     "2":{
     *                         "name":"學生身份認證"
     *                         "description":[
     *                             "error msg"
     *                         ]
     *                 }
     *             },
     *         ]
     *     }
     * }
     */
    public function targetfaillist_get()
    {
        $product_list = $this->config->item('product_list');
        $this->load->model('loan/target_model');
        $this->load->library('loanmanager/product_lib');
        $targets = $this->target_model->get_failed_target($this->user_info->id);

        $list = [];

        foreach ($targets as $value)
        {
            if ( ! isset($product_list[$value['product_id']]))
            {
                continue;
            }

            $product = $this->product_lib->getProductInfo($value['product_id'], $value['sub_product_id']);
            if ($value['status'] == TARGET_FAIL)
            {
                $list[] = [
                    'id' => (int) $value['id'],
                    'product_name' => $product['name'],
                    'remark' => $value['remark']
                ];
                continue;
            }
            $product_certs = $this->product_lib->get_product_certs_by_product_id($value['product_id'], $value['sub_product_id'], []);
            $need_chk_cert = array_diff($product_certs, $product['option_certifications']);
            $this->load->model('user_certification_model');
            $cert_list = $this->config->item('certifications');
            $failed_cert_reason = [];
            if ($value['status'] == TARGET_WAITING_APPROVE && $value['certificate_status'] == TARGET_CERTIFICATE_SUBMITTED)
            {
                foreach ($need_chk_cert as $certification_id)
                {
                    $info = $this->user_certification_model->order_by('created_at', 'DESC')->get_by([
                        'user_id' => $this->user_info->id,
                        'certification_id' => $certification_id,
                        'investor' => $this->user_info->investor
                    ]);

                    $cert_factory = Certification_factory::get_instance_by_model_resource($info);
                    if ( ! isset($cert_factory))
                    {
                        continue;
                    }
                    if ( ! $cert_factory->is_failed())
                    {
                        if (in_array($certification_id, [CERTIFICATION_INVESTIGATION, CERTIFICATION_JOB]))
                        {
                            if ($cert_factory->can_re_submit())
                            {
                                continue;
                            }
                        }
                        else
                        {
                            continue;
                        }
                    }

                    $remark = json_decode($info->remark, TRUE);
                    if ( ! empty($remark['fail']))
                    {
                        $failed_cert_reason[$certification_id] = [
                            'name' => $cert_list[$certification_id]['name'],
                            'description' => explode('、', $remark['fail'])
                        ];
                    }
                    else
                    {
                        $failed_cert_reason[$certification_id] = [
                            'name' => $cert_list[$certification_id]['name'],
                            'description' => []
                        ];
                    }
                }

                if ( ! empty($failed_cert_reason))
                {
                    $list[] = [
                        'id' => (int) $value['id'],
                        'product_name' => $product['name'],
                        'remark' => $value['remark'],
                        'certifications' => $failed_cert_reason
                    ];
                }
            }
        }

        $this->response(['result' => 'SUCCESS', 'data' => ['list' => $list]]);
    }

    /**
     * @api {post} /v2/product/update 借款方 調整額度
     * @apiVersion 0.2.0
     * @apiName PostProductUpdate
     * @apiGroup Product
     * @apiHeader {String} request_token 登入後取得的 Request Token
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
     * {
     *     'result':'SUCCESS',
     *     'data':{
     *         'target_id':'1000576'
     *     }
     * }
     */
    public function update_post()
    {
        $input = $this->input->post(NULL, TRUE);

        if (empty($input['target_id']))
        {
            $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
        }

        if (empty($input['amount']))
        {
            $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
        }

        $target = $this->target_model->get_by([
            'id' => $input['target_id'],
            'user_id' => $this->user_info->id
        ]);
        if (empty($target))
        {
            $this->response(['result' => 'ERROR', 'error' => TARGET_NOT_EXIST]);
        }

        if($target->status != TARGET_WAITING_SIGNING){
            $this->response(['result' => 'ERROR','error' => TARGET_APPLY_STATUS_ERROR]);
        }

        $this->load->library('credit_lib');
        $chk_credit = $this->credit_lib->get_remain_amount($this->user_info->id, $target->product_id, $target->sub_product_id, $input['target_id']);
        $this->load->model('log/log_targetschange_model');
        if ($input['amount'] > $chk_credit['user_available_amount'] || $chk_credit['instalment'] != $target->instalment)
        {
            $this->target_model->update($target->id, [
                'status' => TARGET_FAIL,
                'sub_status' => TARGET_SUBSTATUS_NORNAL,
                'loan_amount' => 0,
                'remark' => '經AI系統綜合評估後，暫時無法核准您的申請，感謝您的支持與愛護，希望下次還有機會為您服務。'
            ]);
            $this->log_targetschange_model->insert([
                'target_id' => $target->id,
                'status' => TARGET_FAIL,
                'sub_status' => TARGET_SUBSTATUS_NORNAL,
            ]);
            $this->response(['result' => 'ERROR', 'error' => PRODUCT_HAS_NO_CREDIT,
                'data' => ['text' => '經AI系統綜合評估後，暫時無法核准您的申請，感謝您的支持與愛護，希望下次還有機會為您服務。']
            ]);
        }

        $this->load->library('loanmanager/product_lib');
        $product_info = $this->product_lib->getProductInfo($target->product_id, $target->sub_product_id);

        $credit = $this->credit_lib->get_credit($target->user_id, $target->product_id, $target->sub_product_id, $target);
        $interest_rate = $credit['rate'] ?? 0;
        $contract_type = 'lend';
        $contract_data = ['', $target->user_id, $input['amount'], $interest_rate, ''];

        // 調整後金額需以千為單位
        if ($input['amount'] % 1000 != 0)
        {
            $this->response(['result' => 'ERROR', 'error' => PRODUCT_AMOUNT_RANGE]);
        }
        // 調整後金額需符合產品上下限
        if ($input['amount'] > $product_info['loan_range_e'] || $input['amount'] < $product_info['loan_range_s'])
        {
            $this->response(['result' => 'ERROR', 'error' => PRODUCT_AMOUNT_RANGE]);
        }

        $this->target_model->update(
            $input['target_id'], [
                'amount' => $input['amount'],
                'loan_amount' => $input['amount'],
                'platform_fee' => $this->financial_lib->get_platform_fee($input['amount'], $product_info['charge_platform']),
                'contract_id' => $this->contract_lib->sign_contract($contract_type, $contract_data)
            ]
        );
        $this->load->library('target_lib');
        $this->log_targetschange_model->insert(
            $this->target_lib->get_target_log_param($input['target_id'], TRUE, [
                'amount' => $input['amount'],
                'change_user' => $target->user_id
            ])
        );

        $this->response(['result' => 'SUCCESS', 'data' => ['target_id' => (int) $input['target_id']]]);
    }

    public function re_submit_post()
    {
        $input = $this->input->post(NULL, TRUE);
        if (empty($input['target_id']))
        {
            $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
        }

        $target = $this->target_model->get_by([
            'id' => $input['target_id'],
            'user_id' => $this->user_info->id
        ]);
        if (empty($target))
        {
            $this->response(['result' => 'ERROR', 'error' => TARGET_NOT_EXIST]);
        }

        $this->target_model->update(
            $input['target_id'], [
                'certificate_status' => TARGET_CERTIFICATE_RE_SUBMITTING
            ]
        );
        $this->response(['result' => 'SUCCESS']);
    }

    /**
     * @api {post} /v2/product/skip_certification_post 借款方 略過徵信項資料上傳
     * @apiVersion 0.2.0
     * @apiName PostProductSkipCertification
     * @apiGroup Product
     * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiParam {Number} target_id 案件編號
     * @apiParam {String} certification_ids 欲略過的徵信項號碼 (以,分隔)
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
     * {
     *     'result':'SUCCESS',
     * }
     *
     * @apiError 8 資料庫發生錯誤
     * @apiErrorExample {Object} 8
     *     {
     *       "result": "ERROR",
     *       "error": "8"
     *     }
     *
     * @apiError 200 參數錯誤
     * @apiErrorExample {Object} 200
     *     {
     *       "result": "ERROR",
     *       "error": "200"
     *     }
     *
     * @apiError 801 標的不存在
     * @apiErrorExample {Object} 801
     *     {
     *       'result': 'ERROR',
     *       'error': '801'
     *     }
     *
     * @apiError 807 此申請狀態不符
     * @apiErrorExample {Object} 807
     *     {
     *       'result': 'ERROR',
     *       'error': '807'
     *     }
     */
    public function skip_certification_post()
    {
        $input = $this->input->post(NULL, TRUE);
        if (empty($input['target_id']) || empty($input['certification_ids']))
        {
            $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
        }

        $target = $this->target_model->get_by([
            'id' => $input['target_id'],
        ]);
        if (empty($target))
        {
            $this->response(['result' => 'ERROR', 'error' => TARGET_NOT_EXIST]);
        }
        if ($target->status != TARGET_WAITING_APPROVE)
        {
            $this->response(['result' => 'ERROR', 'error' => TARGET_APPLY_STATUS_ERROR]);
        }

        $product = $this->target_lib->get_product_info($target->product_id, $target->sub_product_id);
        $option_cert_ids = $product['backend_option_certifications'] ?? [];
        $skip_cert_ids = explode(',', $input['certification_ids']);
        if (empty($skip_cert_ids) || count(array_intersect($option_cert_ids, $skip_cert_ids)) != count($skip_cert_ids))
        {
            $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => '欲略過的徵信資料編號有誤']);
        }

        if ($product['check_associates_certs'] ?? FALSE)
        {
            $this->load->helper('user_certification');
            $skip_cert_ids_judicial = [];
            $skip_cert_ids_natural = [];
            foreach ($skip_cert_ids as $value)
            {
                if (is_judicial_certification($value))
                {
                    $skip_cert_ids_judicial[] = $value;
                }
                else
                {
                    $skip_cert_ids_natural[] = $value;
                }
            }

            if ($this->user_info->id == $target->user_id)
            {
                $is_succeed_j = $this->update_skip_cert($target->id, $this->user_info->id, $skip_cert_ids_judicial);
                $is_succeed_n = $this->update_skip_cert($target->id, $this->user_info->naturalPerson->id, $skip_cert_ids_natural);
                $is_succeed = $is_succeed_j && $is_succeed_n;
            }
            else
            {
                $is_succeed = $this->update_skip_cert($target->id, $this->user_info->id, $skip_cert_ids_natural);
            }
        }
        else
        { // default
            $is_succeed = $this->update_skip_cert($target->id, 0, $skip_cert_ids);
        }

        if ($is_succeed)
        {
            $this->response(['result' => 'SUCCESS']);
        }
        else
        {
            $this->response(['result' => 'ERROR', 'error' => EXIT_DATABASE]);
        }
    }

    private function update_skip_cert($target_id, $user_id, $skip_cert_ids)
    {
        if (empty($skip_cert_ids))
        {
            return TRUE;
        }

        $param = [
            'target_id' => $target_id,
            'meta_key' => 'skip_certification_ids',
            'user_id' => $user_id
        ];

        $this->load->model('loan/target_meta_model');
        $rs = $this->target_meta_model->get_by($param);
        if (isset($rs))
        {
            $tmp_meta_value = $rs->meta_value ?? '';
            $tmp_meta_value = json_decode($tmp_meta_value, TRUE);
            $skip_cert_ids = array_unique(array_merge($tmp_meta_value, $skip_cert_ids));
            $is_succeed = $this->target_meta_model->update($rs->id, [
                'meta_value' => json_encode($skip_cert_ids),
            ]);
        }
        else
        {
            $is_succeed = $this->target_meta_model->insert([
                'target_id' => $target_id,
                'meta_key' => 'skip_certification_ids',
                'meta_value' => json_encode($skip_cert_ids),
                'user_id' => $user_id
            ]);
        }

        return $is_succeed;
    }

    // 取得可預約時段
    public function booking_timetable_get()
    {
        try
        {
            $start_date = $this->input->get('start_date');
            $end_date = $this->input->get('end_date');
            $this->load->library('booking_lib');
            $response = $this->booking_lib->get_whole_booking_timetable($start_date, $end_date);

            $this->response($response);
        }
        catch (Exception $e)
        {
            $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
        }
    }

    // 取得使用者的預約列表
    public function user_booking_list_get()
    {
        try
        {
            $target_id = $this->input->get('target_id');
            $user_id = $this->user_info->id;
            $this->load->library('booking_lib');
            $response = $this->booking_lib->get_booked_list_by_user($target_id, $user_id);

            $this->response($response);
        }
        catch (Exception $e)
        {
            $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
        }
    }

    // 使用者預約時段
    public function booking_create_post()
    {
        try
        {
            $target_id = $this->input->post('target_id');
            $user_id = $this->user_info->id;
            $date = $this->input->post('date');
            $time = $this->input->post('time');

            if (empty($target_id))
            {
                $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
            }

            $this->load->library('booking_lib');
            $response = $this->booking_lib->create_booking($target_id, $user_id, $date, $time);

            $this->response($response);
        }
        catch (Exception $e)
        {
            $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
        }
    }

    // 使用者取消預約時段
    public function booking_cancel_post($booking_id = '')
    {
        try
        {
            $this->load->library('booking_lib');
            $response = $this->booking_lib->cancel_booking($booking_id);

            $this->response($response);
        }
        catch (Exception $e)
        {
            $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
        }
    }
}

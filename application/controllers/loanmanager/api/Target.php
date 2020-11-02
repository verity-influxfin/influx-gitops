<?php
defined('BASEPATH') or exit('No direct script access allowed');
require(APPPATH . 'libraries/REST_Controller.php');

class Target extends REST_Controller
{

    public $user_info;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('loanmanager/loan_manager_target_model');

        $this->load->library('loanmanager/auth_lib');
        $this->auth_lib->authToken();
    }

    public function list_get()
    {
        $input = $this->input->get(NULL, TRUE);
        $or_where = [];
        $where = [];

        $serarch = isset($input['search']) && !empty($input['search']) ? $input['search'] : false;
        if ($serarch) {
            if (preg_match("/^[\x{4e00}-\x{9fa5}]+$/u", $serarch)) {
                $name = $this->user_model->get_many_by(array(
                    'name like ' => '%' . $serarch . '%',
                ));
                if ($name) {
                    foreach ($name as $k => $v) {
                        $k == 0 ? $where['target.user_id'] = $v->id : $or_where['target.user_id'][] = $v->id;
                    }
                }
            } else {
                if (preg_match('/[A-Za-z]/', substr($serarch, 0, 1)) == 1
                    && is_numeric(substr($serarch, 1, 9))
                ) {
                    $id_number = $this->user_model->get_many_by(array(
                        'id_number like' => '%' . $serarch . '%',
                        'status' => 1
                    ));
                    if ($id_number) {
                        foreach ($id_number as $k => $v) {
                            $k == 0 ? $where['target.user_id'] = $v->id : $or_where['target.user_id'][] = $v->id;
                        }
                    }
                } elseif (preg_match_all('/\D/', $serarch) == 0) {
                    $where['user.id'] = $serarch;
                } else {
                    $where['target.target_no like'] = '%' . $serarch . '%';
                }
            }
        }

        $where['target.loan_status'] = 1;
        $stauts = isset($input['status']) && !empty($input['status']) ? $input['status'] : false;
        if ($stauts) {
            unset($where['push.status =']);
            $where['push.status'] = $stauts;
        }

        $delay = isset($input['delay']) && !empty($input['delay']) ? $input['delay'] : false;
        if ($delay == 1) {
            $where['target.delay_days >'] = 0;
            $where['target.delay_days <='] = 7;
        } elseif ($delay == 2) {
            $where['target.delay_days >'] = 7;
        }

        $datefrom = isset($input['datefrom']) && !empty($input['datefrom']) ? $input['datefrom'] : false;
        $dateto = isset($input['dateto']) && !empty($input['dateto']) ? $input['dateto'] : false;
        if ($datefrom && $dateto) {
            $where['target.created_at >='] = strtotime($datefrom . '00:00:00');
            $where['target.created_at <='] = strtotime($dateto . '23:59:59');
        }

        $order_by = isset($input['order_by']) && !empty($input['order_by']) ? $input['order_by'] : false;


//        $res = $this->loan_manager_target_model->get_delayUser_list(['user.id']);
//        foreach($res as $key => $value){
//            $delayUserList[] = $value->id;
//        }
//        $delayUserList = implode(',',$delayUserList);

//        $res = $this->loan_manager_target_model->get_target_list();

        $select = [
            'target.id',
            'target.product_id',
            'target.sub_product_id',
            'target.target_no',
            'target.user_id',
            'user.name',
            'target.loan_amount',
            'target.instalment',
            'target.interest_rate',
            'target.repayment',
            'target.loan_date',
            'target.status as targetStatus',
            'target.sub_status',
            'target.delay_days',
            'processing.admin_id',
            'processing.push_by',
            'processing.push_type',
            'processing.result',
            'processing.remark',
            'processing.updated_at',
            'admin.name as adminName',
            'push.push_identity as pushIdentity',
            'push.user_status as pushUserStatus',
            'push.status as pushStatus',
        ];

        $param = [
//                'target.status' => 5,
//            'push.status' => 2,
//            'delay_days >' => 7,
//                'user_id' => 1364,
//            'user_id' => 44302,
//            'user.id <=' => 700,
//            'user.id' => 294,
        ];

        $res = $this->loan_manager_target_model->get_target_list($select, $where, $or_where, 0, false, $order_by);

        $data = [];
        if ($res) {
            $this->load->library('Target_lib');
            $this->load->library('loanmanager/Auth_lib');
            $this->load->library('loanmanager/Product_lib');
            $this->load->library('loanmanager/Loantarget_lib');
            $loanmanagerConfig = $this->config->item('loanmanager');
            $repayment_type = $this->config->item('repayment_type');
            $userTargets = [];
            foreach ($res as $key => $value) {
                $productName = $this->product_lib->getProductInfo($value->product_id, $value->sub_product_id)['name'];
                $amortization_schedule = $value->targetStatus == TARGET_REPAYMENTING || $value->targetStatus == TARGET_REPAYMENTED ? $this->target_lib->get_amortization_table($value) : [
                    'total_payment' => 0,
                    'remaining_principal' => 0,
                    'remaining_interest' => 0,
                    'delay_interest' => 0,
                    'liquidated_damages' => 0,
                    'repaid' => 0,
                ];
                $dailyDelayInterest = intval(round($amortization_schedule['remaining_principal']*DELAY_INTEREST*1/100,0));

                $datas[$key] = new stdClass();
                $datas[$key]->target_id = $value->id;
                $datas[$key]->user_id = $value->user_id;
                $datas[$key]->target_no = $value->target_no;
                $datas[$key]->name = $value->name;
                $datas[$key]->productName = $productName;
                $datas[$key]->delayStatus = ($value->targetStatus != TARGET_REPAYMENTED ? $this->loantarget_lib->targetStatus($value->delay_days) : '已結案') . ($value->delay_days > 0 ? '(' . $value->delay_days . '天)' : '');
                $datas[$key]->repaymentType = isset($repayment_type[$value->repayment]) ? $repayment_type[$value->repayment] : 1;
                $datas[$key]->instalment = $value->instalment;
                $datas[$key]->interest_rate = $value->interest_rate;
                $datas[$key]->loanAmount = $value->loan_amount;
                $datas[$key]->total_payment = $amortization_schedule['total_payment'];
                $datas[$key]->repaid = isset($amortization_schedule['repaid']) ? $amortization_schedule['repaid'] : 0;
                $datas[$key]->remaining_principal = $amortization_schedule['remaining_principal'];
                $datas[$key]->lastInterest = $amortization_schedule['last_interest'];
                $datas[$key]->delayInterest = $amortization_schedule['delay_interest'];
                $datas[$key]->liquidatedDamages = $amortization_schedule['liquidated_damages'];
                $datas[$key]->dailyDelayInterest = $dailyDelayInterest;
                $datas[$key]->status = $value->targetStatus;
                $datas[$key]->sub_status = $value->sub_status;

                if (isset($userTargets[$value->user_id]['debtProcess'])) {
                    $userTargets[$value->user_id]['debtProcess']['userPayment'] += $datas[$key]->total_payment;
                    $userTargets[$value->user_id]['debtProcess']['userRepaid'] += $datas[$key]->repaid;
                    $userTargets[$value->user_id]['debtProcess']['userRemainingPrincipal'] += $datas[$key]->remaining_principal;
                } else {
                    if (isset($value->pushStatus)) {
                        if (isset($value->result)) {
                            $userTargets[$value->user_id]['debtProcess'] = [
                                'lastContactStatus' => $loanmanagerConfig['pushTool'][$value->push_by] . $loanmanagerConfig['pushType'][$value->push_type] . '/' . $loanmanagerConfig['pushResultStatus'][$value->result],
                                'lastContactRemark' => $value->remark,
                            ];
                            $userTargets[$value->user_id]['debtProcess']['lastContact'] = date('Y/m/d H:i:s', $value->updated_at);
                            $userTargets[$value->user_id]['debtProcess']['lastContactAdmin'] = $value->adminName;
                            $userTargets[$value->user_id]['debtProcess']['pushIdentity'] = $value->pushIdentity;
                            $userTargets[$value->user_id]['debtProcess']['pushUserStatus'] = $value->pushUserStatus;
                            $userTargets[$value->user_id]['debtProcess']['pushStatus'] = $value->pushStatus;
                        }
                    } else {
                        $userTargets[$value->user_id]['debtProcess'] = [
                            'lastContactStatus' => '無',
                            'lastContactRemark' => '無',
                            'lastContact' => '無',
                            'lastContactAdmin' => '無',
                            'pushUserStatus' => $loanmanagerConfig['pushDataUserStatus'][0],
                        ];
                    }
                    $userTargets[$value->user_id]['debtProcess']['userPayment'] = $datas[$key]->total_payment;
                    $userTargets[$value->user_id]['debtProcess']['userRepaid'] = $datas[$key]->repaid;
                    $userTargets[$value->user_id]['debtProcess']['userRemainingPrincipal'] = $datas[$key]->remaining_principal;
                }
                $userTargets[$value->user_id]['targetList'][] = $datas[$key];
            }
            $data = [
                'list' => $userTargets,
            ];
        }
        $this->response([
            'result' => 'SUCCESS',
            'data' => $data
        ]);
    }

    function userinfo_get()
    {
        $input = $this->input->get(NULL, TRUE);
        $data = [];

        $res = $this->loan_manager_target_model->get_userinfo($input['user_id']);
        if ($res) {
            $loanmanagerConfig = $this->config->item('loanmanager');
            $data['userInfo'] = $res[0];
            $getVirtualAccountInfo = $this->loan_manager_target_model->getPassbookBalance($input['user_id']);

            if($res[0]->processingId == null){
                $data['userInfo']->processingId = 0;
                $data['userInfo']->admin_id = '尚未執行';
                $data['userInfo']->push_by = 0;
                $data['userInfo']->push_type = 0;
                $data['userInfo']->result = 0;
                $data['userInfo']->remark = '';
                $data['userInfo']->updated_at = 0;
                $data['userInfo']->adminName = '無';
                $data['userInfo']->pushIdentity = 0;
                $data['userInfo']->pushUserStatus = 0;
                $data['userInfo']->pushStatus = 0;
            }

            $wasSubLoan = $this->target_model->get_many_by([
                'user_id' => $data['userInfo']->userId,
                'sub_status' => 2,
            ]);
            $this->load->model('loanmanager/loan_manager_debtprocessing_model');
            $wasNegotiate = $this->loan_manager_debtprocessing_model->get_many_by([
                'user_id' => $data['userInfo']->userId,
                'push_type' => 1,
            ]);

            $this->load->model('user/user_meta_model');
            $getGraduate_date = $this->user_meta_model->get_by([
                'user_id' => $input['user_id'],
                'meta_key' => 'graduate_date',
            ]);

            $graduate_date = $getGraduate_date ? $getGraduate_date->meta_value : null;
            $data['userInfo']->graduate_date = $graduate_date;
            $data['userInfo']->subLoanTimes = count($wasSubLoan);
            $data['userInfo']->negotiateTimes = count($wasNegotiate);
            $data['userInfo']->lastContact = !empty($data['userInfo']->updated_at) ? date('Y/m/d H:i:s', $data['userInfo']->updated_at) : '無紀錄';
            $data['userInfo']->lastContactStatus = $loanmanagerConfig['pushTool'][$data['userInfo']->push_by] . $loanmanagerConfig['pushType'][$data['userInfo']->push_type] . '/' . $loanmanagerConfig['pushResultStatus'][$data['userInfo']->result];
            $data['userInfo']->virtualAccounts = $getVirtualAccountInfo[0]->virtualAccounts;
            $data['userInfo']->virtualPassbooks = $getVirtualAccountInfo[0]->virtualPassbooks;
        }
        $this->response([
            'result' => 'SUCCESS',
            'data' => $data
        ]);
    }

    function userinfo_post()
    {
        $input = $this->input->post(NULL, TRUE);
        if(isset($input['user_id'])){
            $userInfo = $this->userInfo($input['user_id']);
            if($userInfo){
                $this->load->model('loanmanager/loan_manager_pushdata_model');
                $waspush = $this->loan_manager_pushdata_model->get_many_by([
                    'user_id' => $input['user_id'],
                ]);
                if($waspush){
                    $param = [];
                    isset($input['push_identity']) && is_numeric($input['push_identity']) ? $param['push_identity'] = $input['push_identity'] : '';
                    isset($input['user_status']) && is_numeric($input['user_status']) ? $param['user_status'] = $input['user_status'] : '';
                    isset($input['status']) && is_numeric($input['status']) ? $param['status'] = $input['status'] : '';
                    count($param) > 0 ? $this->loan_manager_pushdata_model->update_by(['user_id' => $input['user_id']],$param) : '';
                }else{
                    $this->loan_manager_pushdata_model->insert([
                        'admin_id' => $this->user_info->id,
                        'user_id' => $input['user_id'],
                        'assign_id' => $this->user_info->id,
                    ]);
                }
            }
        }
        $this->response([
            'result' => 'SUCCESS',
        ]);
    }

    function addPushContact_post()
    {
        $input = $this->input->post(NULL, TRUE);
        $data = [];
        $fields = ['user_id', 'push_by', 'relationship', 'info', 'remark',];
        foreach ($fields as $field) {
            if (!is_numeric($input[$field]) && !in_array($input['push_by'], [PUSH_BY_EMERGENCY_PHONE, PUSH_BY_USER_PHONE, PUSH_BY_SMS])) {
                $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
            }
        }
        $userInfo = $this->userInfo($input['user_id']);
        if ($userInfo) {
            $this->load->model('loanmanager/loan_manager_contact_model');
            $this->loan_manager_contact_model->insert([
                'user_id' => $input['user_id'],
                'push_by' => $input['push_by'],
                'relationship' => $input['relationship'],
                'info' => $input['info'],
                'remark' => $input['remark'],
                'creator_id' => $this->user_info->id,
            ]);
        }
        $this->response([
            'result' => 'SUCCESS',
        ]);
    }

    function pushContactList_post()
    {
        $input = $this->input->post(NULL, TRUE);
        $data = [];
        $fields 	= ['user_id','push_by',];
        foreach ($fields as $field) {
            if (!is_numeric($input[$field])) {
                $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }
        }
        $userInfo = $this->userInfo($input['user_id']);
        if ($userInfo) {
            $loanmanagerConfig = $this->config->item('loanmanager');
            $defalt = [
                'contact_id' => 0,
                'user_id' => $input['user_id'],
                'push_by' => 0,
                'relationship' => $loanmanagerConfig['contactRelationship'][0],
                'info' => 0,
                'remark' => '認證資料',
            ];
            $this->load->model('loanmanager/loan_manager_contact_model');
            if($input['push_by'] == PUSH_BY_USER_PHONE || $input['push_by'] == PUSH_BY_SMS){
                $temp = $defalt;
                $temp['push_by'] = $loanmanagerConfig['pushTool'][PUSH_BY_USER_PHONE];
                $temp['info'] = $userInfo->phone;
                $data['contactList'][] = $temp;
            }else{
                $metaParam = ['user_id' => $input['user_id']];
                $metaTypeList = [
                    PUSH_BY_EMERGENCY_PHONE => 'emergency_phone',
                    PUSH_BY_INSTGRAM => 'emergency_phone',
                ];
                if(isset($metaTypeList[$input['push_by']])){
                    $metaParam['meta_key'] = $metaTypeList[$input['push_by']];
                    $this->load->model('user/user_meta_model');
                    $contactList = $this->user_meta_model->get_by($metaParam);
                    if($contactList){
                        $relationship = $loanmanagerConfig['contactRelationship'][0];
                        if($input['push_by'] == PUSH_BY_EMERGENCY_PHONE){
                            $relationshipRes = $this->user_meta_model->get_by([
                                'user_id' => $input['user_id'],
                                'meta_key' => 'emergency_relationship',
                            ]);
                            if($relationshipRes){
                                $relationship = $relationshipRes->meta_value;
                            }
                        }
                        $temp = $defalt;
                        $temp['push_by'] = $loanmanagerConfig['pushTool'][$input['push_by']];
                        $temp['relationship'] = $relationship;
                        $temp['info'] = $userInfo->phone;
                        $data['contactList'][] = $temp;
                    }
                }
            }

            $this->load->model('loanmanager/loan_manager_contact_model');
            $contactList = $this->loan_manager_contact_model->get_many_by([
                'user_id' => $input['user_id'],
                'push_by' => $input['push_by'],
            ]);
            if($contactList){
                foreach ($contactList as $listKey => $listValue) {
                    $temp = [];
                    $temp['contact_id'] = $listValue->push_by;
                    $temp['user_id'] = $listValue->user_id;
                    $temp['push_by'] = $loanmanagerConfig['pushTool'][$listValue->push_by];
                    $temp['relationship'] = $loanmanagerConfig['contactRelationship'][$listValue->relationship];
                    $temp['info'] = $listValue->info;
                    $temp['remark'] = $listValue->remark;
                    $data['contactList'][] = $temp;
                }
            }
        }
        $this->response([
            'result' => 'SUCCESS',
            'data' => $data
        ]);
    }

    function pushUser_post()
    {
        $input = $this->input->post(NULL, TRUE);
        $fields 	= ['user_id','push_by','push_type','message','start_time','end_time'];
        foreach ($fields as $field) {
            if ($input[$field] != '') {
                $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }
        }
        $userInfo = $this->userInfo($input['user_id']);
        if($userInfo){
            isset($input['contact_id']) && is_numeric($input['contact_id']) ? $param['contact_id'] = $input['contact_id'] : '';
            $this->load->model('loanmanager/loan_manager_debtprocessing_model');
            $this->loan_manager_debtprocessing_model->insert([
                'admin_id' => $this->user_info->id,
                'user_id' => $input['user_id'],
                'push_by' => $input['push_by'],
                'push_type' => $input['push_type'],
                'message' => $input['message'],
                'start_time' => strtotime($input['start_time']),
                'end_time' => strtotime($input['end_time']),
            ]);
        }
        $this->response([
            'result' => 'SUCCESS',
        ]);
    }

    function userPassbook_get($internal = false, $account = false)
    {
        $input = $this->input->get(NULL, TRUE);
        $fields 	= ['account'];
        $account ? $input['account'] = $account : '';
        foreach ($fields as $field) {
            if (empty($input[$field])) {
                $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }
        }
        $getVirtualPassbook = $this->loan_manager_target_model->getPassbookList($input['account']);
        if($getVirtualPassbook){
            $transaction_source = $this->config->item('transaction_source');
            foreach($getVirtualPassbook as $key => $value){
                $value['remark'] = json_decode($value['remark'],TRUE);
                if(isset($value['remark']['source']) && $value['remark']['source']){
                    $temp[] = [
                        'amount' => $value['amount'],
                        'bank_amount' => $value['bank_amount'],
                        'remark' => $transaction_source[$value['remark']['source']],
                        'targetId' => $value['remark']['target_id'],
                        'tx_datetime' => $value['tx_datetime'],
                        'created_at' => $value['created_at'],
                    ];
                }
            }
        }
        if($internal){
            return $temp;
        }else{
            $this->response([
                'result' => 'SUCCESS',
                'data' => [
                    'virtualAccount' => $input['account'],
                    'list' => $temp,
                ]
            ]);
        }

    }


    function serviceLog_get()
    {
        $input = $this->input->get(NULL, TRUE);
        $list = [];
        if (!is_numeric($input['type'])) {
            $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
        }

        $userInfo = $this->userInfo($input['user_id']);
        if($userInfo){
            $type = $input['type'];
            $logs = [];
            $structure = [
                'title' => '',
                'content' => '',
                'time' => '',
                'remark' => '',
            ];
            if(in_array($type, [0, 1])){
                $this->load->model('loanmanager/loan_manager_pushdata_model');
                //匯款紀錄
                $getVirtualAccountInfo = $this->loan_manager_target_model->getPassbookBalance($input['user_id']);
                if($getVirtualAccountInfo[0]->virtualAccounts != null){
                    $getAccountingRecord = $this->userPassbook_get(1, $getVirtualAccountInfo[0]->virtualAccounts);
                    foreach($getAccountingRecord as $key => $value){
                        $structure['title'] = $value['remark'];
                        $structure['content'] = $value['amount'];
                        $structure['time'] = $value['tx_datetime'];
                        $structure['remark'] = $value['bank_amount'];
                        $structure['type'] = 1;
                        $logs[$value['created_at']][] = $structure;
                    }
                }
            }

            if(in_array($type, [0, 2])){
                //認證紀錄
                $getUserCerList = $this->loan_manager_target_model->getUserCerList($input['user_id']);
                $loanmanagerConfig = $this->config->item('loanmanager');
                foreach($getUserCerList as $key => $value){
                    $structure['title'] = '更新認證';
                    $structure['content'] = $loanmanagerConfig['certifications'][$value->certification_id]['name'];
                    // . '(' . $loanmanagerConfig['cer_status'][ $value->expire_time > time() && !in_array($value->certification_id,[CERTIFICATION_IDCARD,CERTIFICATION_DEBITCARD,CERTIFICATION_EMERGENCY,CERTIFICATION_EMAIL]) ? $value->status : 2] . ')'
                    $structure['time'] = date('Y-m-d H:i:s', $value->created_at);
                    $structure['remark'] = "送出認證";
                    $structure['type'] = 2;
                    $logs[$value->created_at][] = $structure;
                }
            }

            if(in_array($type, [0, 3])){
                //系統通知
                $this->load->model('user/user_notification_model');
                $getNotification = $this->user_notification_model->order_by('created_at','desc')->get_many_by([
                    'user_id'		=> $input['user_id'],
                    'status <>'		=> 0,
                    'investor'		=> [0]
                ]);
                foreach($getNotification as $key => $value){
                    $structure['title'] = $value->title;
                    $structure['content'] = preg_replace('/\\t/','',$value->content);
                    $structure['time'] = date('Y-m-d H:i:s', $value->created_at);
                    $structure['type'] = 3;
                    $logs[$value->created_at][] = $structure;
                }
            }

            if(in_array($type, [0, 4])){
                //登入紀錄
                $getUserLoginLog = $this->loan_manager_target_model->getUserLoginLog($input['user_id']);
                foreach($getUserLoginLog as $key => $value){
                    $structure['title'] = '用戶登入';
                    $structure['content'] = '登入' . $value->status == 1 ? '成功' : '失敗';
                    $structure['time'] = date('Y-m-d H:i:s', $value->created_at);
                    $structure['type'] = 3;
                    $logs[$value->created_at][] = $structure;
                }
            }

            if(in_array($type, [0, 5])){
                //客服紀錄
                $getUserLoginLog = $this->loan_manager_target_model->getUserServiceLog($input['user_id']);
                foreach($getUserLoginLog as $key => $value){
                    $temp = (array)$value;
                    $temp['type'] = 5;
                    $logs[$temp['created_at']][] = $temp;
                }
            }

            if(in_array($type, [0, 6])){
                //面談紀錄
                $getUserLoginLog = $this->loan_manager_target_model->getUserServiceLog($input['user_id'], true);
                foreach($getUserLoginLog as $key => $value){
                    $temp = (array)$value;
                    $temp['type'] = 6;
                    $logs[$temp['created_at']][] = $temp;
                }
            }
        }
        $list = [
            'userId' => $input['user_id'],
            'logs' => $logs,
        ];
        $this->response([
            'result' => 'SUCCESS',
            'data' => $list,
        ]);
    }

    private function userInfo($userId){
        $userInfo = $this->user_model->get_by([
            'id' => $userId,
        ]);
        return $userInfo;
    }
}
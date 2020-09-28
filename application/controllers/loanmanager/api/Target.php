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

        $serarch = isset($input['search']) ? $input['search'] : false;
        if($serarch){
            if(preg_match("/^[\x{4e00}-\x{9fa5}]+$/u", $serarch))
            {
                $name = $this->user_model->get_many_by(array(
                    'name like '    => '%'.$serarch.'%',
                    'status'	    => 1
                ));
                if($name){
                    foreach($name as $k => $v){
                        $where['user_id'][] = $v->id;
                    }
                }
            }else{
                if(preg_match('/[A-Za-z]/', substr($serarch, 0, 1))==1){
                    $id_number	= $this->user_model->get_many_by(array(
                        'id_number  like'	=> '%'.$serarch.'%',
                        'status'	        => 1
                    ));
                    if($id_number){
                        foreach($id_number as $k => $v){
                            $where['user_id'][] = $v->id;
                        }
                    }
                }
                elseif(preg_match_all('/\D/', $serarch)==0){
                    $where['user_id'] = $serarch;
                }
                else{
                    $where['target_no like'] = '%'.$serarch.'%';
                }
            }
        }

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
            'push.status as pushStatus',
            'push.user_status as pushUserStatus',
        ];

        $param = [
//                'target.status' => 5,
            'push.status' => 2,
            'delay_days >' => 7,
//                'user_id' => 1364,
//            'user_id' => 44302,
//            'user.id <=' => 700,
//            'user.id' => 294,
        ];

        $res = $this->loan_manager_target_model->get_target_list($select, $param, 0, 1000);

        $data = [];
        if($res){
            $this->load->library('Target_lib');
            $this->load->library('loanmanager/Auth_lib');
            $this->load->library('loanmanager/Product_lib');
            $this->load->library('loanmanager/Loantarget_lib');
            $loanmanagerConfig = $this->config->item('loanmanager');
            $repayment_type = $this->config->item('repayment_type');
            $userTargets = [];
            foreach($res as $key => $value){
                $productName = $this->product_lib->getProductInfo($value->product_id, $value->sub_product_id)['name'];
                $amortization_schedule = $value->targetStatus == TARGET_REPAYMENTING || $value->targetStatus == TARGET_REPAYMENTED ? $this->target_lib->get_amortization_table($value) : [
                    'total_payment' => 0,
                    'remaining_principal' => 0,
                ];

                $datas[$key] = new stdClass();
                $datas[$key]->target_id = $value->id;
                $datas[$key]->user_id = $value->user_id;
                $datas[$key]->target_no = $value->target_no;
                $datas[$key]->name = $value->name;
                $datas[$key]->productName = $productName;
                $datas[$key]->delayStatus = $this->loantarget_lib->targetStatus($value->delay_days) . ($value->delay_days > 0 ? '('.$value->delay_days.'天)' : '');
                $datas[$key]->repaymentType = $repayment_type[$value->repayment];
                $datas[$key]->instalment = $value->instalment;
                $datas[$key]->interest_rate = $value->interest_rate;
                $datas[$key]->total_payment = $amortization_schedule['total_payment'];
                $datas[$key]->remaining_principal = $amortization_schedule['remaining_principal'];
                $datas[$key]->status = $value->targetStatus;
                $datas[$key]->sub_status = $value->sub_status;

                if(isset($userTargets[$value->user_id]['debtProcess'])){
                    $userTargets[$value->user_id]['debtProcess']['userTotalPayment'] += $datas[$key]->total_payment;
                    $userTargets[$value->user_id]['debtProcess']['userRemainingPrincipal'] += $datas[$key]->remaining_principal;
                }else{
                    if(isset($value->pushStatus)){
                        if(isset($value->result)){
                            $userTargets[$value->user_id]['debtProcess'] = [
                                'lastContactStatus' => $loanmanagerConfig['pushTool'][$value->push_by] . $loanmanagerConfig['pushType'][$value->push_type] . '/' . $loanmanagerConfig['pushResultStatus'][$value->result],
                                'lastContactRemark' => $value->remark,
                            ];
                            $userTargets[$value->user_id]['debtProcess']['lastContact'] = date('Y/m/d H:i:s', $value->updated_at);
                            $userTargets[$value->user_id]['debtProcess']['lastContactAdmin'] = $value->adminName;
                            $userTargets[$value->user_id]['debtProcess']['pushStatus'] = $value->pushStatus;
                            $userTargets[$value->user_id]['debtProcess']['pushUserStatus'] = $loanmanagerConfig['pushDataUserStatus'][$value->pushUserStatus];
                        }
                    }
                    $userTargets[$value->user_id]['debtProcess']['userTotalPayment'] = 0;
                    $userTargets[$value->user_id]['debtProcess']['userRemainingPrincipal'] = 0;
                }
                $userTargets[$value->user_id]['targetList'][] = $datas[$key];
            }
            $data =  [
                'list' => $userTargets,
            ];
        }
        $this->response([
            'result' => 'SUCCESS',
            'data' => $data
        ]);
    }
}

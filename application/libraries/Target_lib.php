<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Approve_target\Approve_factory;
use Certification\Cert_identity;
use Certification\Certification_factory;
use CertificationResult\IdentityCertificationResult;
use CertificationResult\MessageDisplay;
use CreditSheet\CreditSheetFactory;

/**
 * @property CI_Controller $CI
 */
class Target_lib
{


    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('transaction/transaction_model');
        $this->CI->load->library('Notification_lib');
        $this->CI->load->library('utility/payment_time_utility');
    }

    //新增target
    public function add_target($param)
    {
        if (!empty($param)) {
            $param['target_no'] = $this->get_target_no($param['product_id']);
            $insert = $this->CI->target_model->insert($param);
            $this->CI->load->model('log/log_targetschange_model');
            if (!$insert) {
                $param['target_no'] = $this->get_target_no($param['product_id']);
                $insert             = $this->CI->target_model->insert($param);
            }
            $this->CI->log_targetschange_model->insert(
                $this->get_target_log_param($insert, TRUE, $param)
            );
            return $insert;
        }
        return false;
    }

    /**
     * 取得異動 Target 的 Log 參數
     * @param int $target_id
     * @param bool $user_change : 是否為 end-user 的自主行為
     * @param array $param : insert/update Target 的參數
     * @param int $change_admin_id : 若由後台管理者修改，傳入管理者 ID，反之預設 0
     * @return array
     */
    public function get_target_log_param(int $target_id, bool $user_change, array $param, int $change_admin_id = 0)
    {
        $result = [
            'target_id' => $target_id,
            'amount' => $param['amount'] ?? NULL,
            'interest_rate' => $param['interest_rate'] ?? NULL,
            'delay' => $param['delay'] ?? NULL,
            'status' => $param['status'] ?? NULL,
            'loan_status' => $param['loan_status'] ?? NULL,
            'sub_status' => $param['sub_status'] ?? NULL,
            'sys_check' => $param['sys_check'] ?? NULL,
            'change_user' => $param['change_user'] ?? ($user_change ? $param['user_id'] ?? 0 : 0),
            'change_admin' => $change_admin_id
        ];
        ! isset($param['certificate_status']) ?: $result['certificate_status'] = $param['certificate_status'];
        return $result;
    }

    //新增target
    public function add_target_group($param, $code)
    {
        if (!empty($param)) {
            foreach ($param as $key => $val) {
                $val['target_no'] = $this->get_target_no($val['product_id']) . $code[$key];
                $targets[] = $this->CI->target_model->insert($val);
            }
            return $targets;
        }
        return false;
    }

    //簽約
    public function signing_target($target_id, $data, $user_id = 0)
    {
        if ($target_id) {
            $this->CI->load->model('log/Log_targetschange_model');
            $rs = $this->CI->target_model->update($target_id, $data);
            $this->insert_change_log($target_id, $data, $user_id);

            $target_change	= $this->CI->Log_targetschange_model->order_by('created_at','desc')->get_by(array(
                'target_id'		=> $target_id,
                'status'		=> TARGET_WAITING_SIGNING,
            ));
            if(isset($target_change)) {
                $this->CI->load->library('Notification_lib');
                $eventDate 	= date("Y-m-d H:i:s", strtotime("+3 day", $target_change->created_at));
                if($eventDate >= date("Y-m-d H:i:s")) {
                    $target = $this->CI->target_model->get_by(['id' => $target_id]);
                }
            }
            return $rs;
        }
        return false;
    }

    //分期貸簽約
    public function ordersigning_target($target_id, $user_id = 0, $param)
    {
        if ($target_id) {
            $rs = $this->CI->target_model->update($target_id, $param);
            $this->insert_change_log($target_id, $param, $user_id);
            return $rs;
        }
        return false;
    }

    //分期貸
    public function order_target_change($order_id, $status, $param, $user_id, $admin_id = 0)
    {
        if ($order_id) {
            $rs = $this->CI->target_model->update_by(
                [
                    'order_id' => $order_id,
                    'status' => $status,
                ], $param
            );
            if ($rs) {
                $target = $this->CI->target_model->get_by('order_id', $order_id);
                $this->insert_change_log($target->id, [
                    'status' => $param['status'],
                    'sub_status' => isset($param['sub_status']) ? $param['sub_status'] : null,
                ], $user_id);
            }
            return $rs;
        }
        return false;
    }

    public function order_verify_success($target = [], $admin_id = 0)
    {
        $result = $this->coop_status_change_no($target->order_id, 'shipment');
        if (isset($result->result) && $result->result == 'SUCCESS') {
            $this->CI->load->model('transaction/order_model');
            $order = $this->CI->order_model->get_by([
                'id' => $target->order_id
            ]);
            $this->order_target_change($order->id, 23, [
                'status' => 23,
                'sub_status' => 5,
            ], 0, $admin_id);
            $this->CI->load->library('Order_lib');
            $this->CI->order_lib->order_change($target->order_id, 1, [
                'status' => 2,
            ], 0, $admin_id);
            $this->aiBiddingTarget($target);
            return true;
        }
        return false;
    }

    //全案退回
    public function cancel_success_target($target, $admin_id = 0)
    {
        if ($target && in_array($target->status, [3, 4]) && $target->script_status == 0) {
            $param = [
                'status' => 9,
                'remark' => $target->remark . ',整案退回',
            ];
            $rs = $this->CI->target_model->update($target->id, $param);
            $this->insert_change_log($target->id, $param, 0, $admin_id);

            $this->CI->load->model('loan/investment_model');
            $this->CI->load->model('transaction/frozen_amount_model');
            $investments = $this->CI->investment_model->get_many_by([
                'target_id' => $target->id,
                'status' => [0, 1, 2]
            ]);
            if ($investments) {
                foreach ($investments as $key => $value) {
                    $this->insert_investment_change_log($value->id, ['status' => 9], 0, $admin_id);
                    $this->CI->investment_model->update($value->id, ['status' => 9]);
                    if ($value->frozen_status == 1 && $value->frozen_id) {
                        $this->CI->frozen_amount_model->update($value->frozen_id, ['status' => 0]);
                    }
                }
            }

            if ($this->is_sub_loan($target->target_no) === TRUE)
            {
                $this->CI->load->library('Subloan_lib');
                $this->CI->subloan_lib->subloan_success_return($target, $admin_id);
            }
            if ($target->order_id != 0) {
                $this->CI->load->model('transaction/order_model');
                $order = $this->CI->order_model->update($target->order_id, ['status' => 0]);
            }
            return $rs;
        }
        return false;
    }

    //取消
    public function cancel_target($target = [], $user_id = 0, $phone = 0)
    {
        if ($target) {
            //判斷是否為消費貸
            if ($target->order_id != 0) {
                $this->CI->load->model('transaction/order_model');
                $rs = $this->cancel_order($target->order_id, $user_id, $phone);
                if (!$rs) {
                    return false;
                }
            }

            $guarantors = $this->get_associates_user_data($target->id, 'all', [0 ,1], false);
            if($guarantors){
                $this->CI->target_associate_model->update_by([
                    'target_id' => $target->id,
                ],["status" => 8]);
            }

            $param = [
                'status' => 8,
            ];

            $target_detail = $this->CI->target_model->get($target->id);
            $subloan_status = $this->CI->target_lib->is_sub_loan($target_detail->target_no);
            if ($subloan_status === TRUE && $target_detail->sub_status == TARGET_SUBSTATUS_WAITING_SUBLOAN)
            {
                $param['sub_status'] = TARGET_SUBSTATUS_NORNAL;
            }

            $this->CI->target_model->update($target->id, $param);
            $this->insert_change_log($target->id, $param, $user_id);
            return true;
        } else {
            return false;
        }
    }

    //取消訂單
    public function cancel_order($order_id, $user_id, $phone = 0)
    {
        $this->CI->load->model('transaction/order_model');
        $order = $this->CI->order_model->get($order_id);
        $this->CI->load->library('coop_lib');
        $result = $this->CI->coop_lib->coop_request('order/scancel', [
            'merchant_order_no' => $order->merchant_order_no,
            'phone' => $phone,
        ], $user_id);
        if (isset($result->result) && $result->result == 'SUCCESS') {
            if ($order_id != false) {
                $this->CI->order_model->update($order_id, ['status' => 8]);
                return true;
            }
        }
        return false;
    }

    //取消
    public function cancel_investment($target = [], $investment = [], $user_id = 0)
    {
        if ($target && $target->status == 3 && $target->script_status == 0) {
            if ($investment && in_array($investment->status, [0, 1])) {
                $param = [
                    'status' => 8,
                ];
                $rs = $this->CI->investment_model->update($investment->id, $param);
                $this->insert_investment_change_log($investment->id, $param, $user_id, 0);
                //取消凍結
                if ($investment->status == 1 && $investment->frozen_status == 1 && $investment->frozen_id) {
                    $this->CI->load->model('transaction/frozen_amount_model');
                    $this->CI->frozen_amount_model->update($investment->frozen_id, ['status' => 0]);

                    //重新計算
                    $investments = $this->CI->investment_model->get_many_by([
                        'target_id' => $target->id,
                        'status' => 1
                    ]);
                    $amount = 0;
                    if ($investments) {
                        foreach ($investments as $key => $value) {
                            if ($value->status == 1 && $value->frozen_status == 1 && $value->frozen_id) {
                                $amount += $value->amount;
                            }
                        }
                    }
                    $this->CI->target_model->update($target->id, ['invested' => $amount]);
                }
                return $rs;
            }
        }
        return false;
    }

    private function coop_status_change_no($order_id, $type)
    {
        $this->CI->load->model('transaction/order_model');
        $order = $this->CI->order_model->get($order_id);
        $this->CI->load->library('coop_lib');
        $rs = $this->CI->coop_lib->coop_request('order/supdate', [
            'merchant_order_no' => $order->merchant_order_no,
            'phone' => $order->phone,
            'type' => $type,
        ], 0);
        return $rs;
    }

    //核可額度利率
    public function approve_target($target = [], $remark = false, $renew = false, $targetData = false, $stage_cer = false, $subloan_status = false, $matchBrookesia = false, $second_instance_check = false)
    {
        $this->CI->load->library('credit_lib');
        $this->CI->load->library('contract_lib');
        $this->CI->load->library('Anti_fraud_lib');
        $msg = false;
        $target = $this->CI->target_model->get($target->id ?? 0);
        if (!empty($target) && ($target->status == TARGET_WAITING_APPROVE
                || $renew
                || $target->status == TARGET_ORDER_WAITING_VERIFY && $target->sub_status == TARGET_SUBSTATUS_NORNAL
                || $target->status == CERTIFICATION_CERCREDITJUDICIAL
                || $target->status == TARGET_WAITING_SIGNING && $target->sub_product_id == STAGE_CER_TARGET)) {
            $product_list = $this->CI->config->item('product_list');
            $user_id = $target->user_id;
            $product_id = $target->product_id;
            $sub_product_id = $target->sub_product_id;

            if ($product_id == PRODUCT_ID_HOME_LOAN) {
                $certifications_to_check = [
                    CERTIFICATION_LAND_AND_BUILDING_TRANSACTIONS,
                    CERTIFICATION_SITE_SURVEY_VIDEO,
                ];
                foreach ($certifications_to_check as $certification_id) {
                    $certification = $this->CI->user_certification_model->get_by([
                        'user_id' => $user_id,
                        'certification_id' => $certification_id,
                        'status' => 1
                    ]);

                    if (empty($certification)) {
                        return false;
                    }
                }
            }

            $product_info = $product_list[$product_id];
            if ($this->is_sub_product($product_info, $sub_product_id)) {
                $product_info = $this->trans_sub_product($product_info, $sub_product_id);
            }

            $credit = $this->CI->credit_lib->get_credit($user_id, $product_id, $sub_product_id, $target);
            if (!$credit || $stage_cer != 0) {
                if (isset($product_info['checkOwner']) && $product_info['checkOwner']) {
                    $mix_credit = $this->get_associates_user_data($target->id, 'all', [0 ,1], true);
                    $credit_score = [];
                    foreach ($mix_credit as $value) {
                        $credit_score[] = $this->CI->credit_lib->approve_credit($value, $product_id, $sub_product_id, null, false, false, true, $target->instalment, $target);
                    }
                    $total_point = array_sum($credit_score);
                    $rs = $this->CI->credit_lib->approve_associates_credit($target, $total_point);
                }else{
                    $rs = $this->CI->credit_lib->approve_credit($user_id, $product_id, $sub_product_id, null, $stage_cer, $credit, false, $target->instalment, $target);
                }
                if ($rs) {
                    $credit = $this->CI->credit_lib->get_credit($user_id, $product_id, $sub_product_id, $target);
                }
            }
            if ($credit) {
                $creditSheet = CreditSheetFactory::getInstance($target->id);
                $interest_rate = $credit['rate'];
                if ($renew && $target->product_id == PRODUCT_ID_STUDENT)
                {
                    // 學生貸：二審人員調整欄位
                    // 調盩分數後，依據調整後的信評分數給予新的信評等級，並算出新的額度 （上限為15萬）；但利率不變，為調整信評分數前之利率
                    $interest_rate = $target->interest_rate;
                }
                if ($interest_rate) {
                    $used_amount = 0;
                    $other_used_amount = 0;
                    $user_max_credit_amount = $this->CI->credit_lib->get_user_max_credit_amount($user_id);
                    //取得所有產品申請或進行中的案件
                    $target_list = $this->CI->target_model->get_many_by([
                        'id !=' => $target->id,
                        'user_id' => $user_id,
                        'status NOT' => [TARGET_CANCEL, TARGET_FAIL, TARGET_REPAYMENTED]
                    ]);
                    if ($target_list) {
                        foreach ($target_list as $key => $value) {
                            if ($product_id == $value->product_id) {
                                $used_amount = $used_amount + intval($value->loan_amount);
                            } else {
                                $other_used_amount = $other_used_amount + intval($value->loan_amount);
                            }
                            //取得案件已還款金額
                            $pay_back_transactions = $this->CI->transaction_model->get_many_by(array(
                                "source" => SOURCE_PRINCIPAL,
                                "user_from" => $user_id,
                                "target_id" => $value->id,
                                "status" => TRANSACTION_STATUS_PAID_OFF
                            ));
                            //扣除已還款金額
                            foreach ($pay_back_transactions as $key2 => $value2) {
                                if ($product_id == $value->product_id) {
                                    $used_amount = $used_amount - intval($value2->amount);
                                } else {
                                    $other_used_amount = $other_used_amount - intval($value2->amount);
                                }
                            }
                        }
                        //無條件進位使用額度(千元) ex: 1001 ->1100
                        $used_amount = $used_amount % 1000 != 0 ? ceil($used_amount * 0.001) * 1000 : $used_amount;
                        $other_used_amount = $other_used_amount % 1000 != 0 ? ceil($other_used_amount * 0.001) * 1000 : $other_used_amount;
                    }
                    //個人最高歸戶剩餘額度
                    $user_current_credit_amount = $user_max_credit_amount - ($used_amount + $other_used_amount);
                    if ($user_current_credit_amount >= 1000 || $subloan_status) {
                        $this->CI->config->load('credit',TRUE);
                        $instalment_modifier_list = $this->CI->config->item('credit')['credit_instalment_modifier_'.$target->product_id];

                        //該產品額度
                        $used_amount = $credit['amount'] - $used_amount;
                        //檢核產品額度，不得高於個人最高歸戶剩餘額度
                        $credit['amount'] = $used_amount > $user_current_credit_amount ? $user_current_credit_amount : $used_amount;

                        $loan_amount = $target->amount > $credit['amount'] && $subloan_status == false ? $credit['amount'] : $target->amount;
                        // 金額取整程式，2020/10/30排除產轉
                        $loan_amount = ($loan_amount % 1000 != 0 && $subloan_status == false) ? floor($loan_amount * 0.001) * 1000 : $loan_amount;
                        if ($loan_amount >= $product_info['loan_range_s'] || $subloan_status) {
                            if ($product_info['type'] == 1 || $subloan_status) {
                                $platform_fee = $this->CI->financial_lib->get_platform_fee($loan_amount, $product_info['charge_platform']);
                                $param = [
                                    'sub_product_id' => $sub_product_id,
                                    'loan_amount' => $loan_amount,
                                    'credit_level' => $credit['level'],
                                    'platform_fee' => $platform_fee,
                                    'interest_rate' => $interest_rate,
                                    'status' => 0,
                                ];
                                $approve_target_result = new \Approve_target\Approve_target_result($target->status, $target->sub_status);
                                $evaluation_status = $target->sub_status == TARGET_SUBSTATUS_SECOND_INSTANCE_TARGET;


                                // 「上班族貸」新戶額度調整
                                if (in_array($product_id, [3, 4]) && !$renew) {
                                    // Todo: “新戶” (無申貸成功紀錄者) 且薪水四萬以下
                                    $past_targets = $this->CI->target_model->get_many_by([
                                        'user_id' => $user_id,
                                        'status' => [5, 10],
                                    ]);
                                    $is_new_user = count($past_targets) == 0;
                                    if ($is_new_user) {
                                        $certification = $this->CI->user_certification_model->get_by([
                                            'user_id' => $user_id,
                                            'certification_id' => CERTIFICATION_REPAYMENT_CAPACITY,
                                            'status' => 1]);
                                        if (!isset($certification)) {
                                            $this->memo_target($target->id, '沒有有效的還款力計算結果');
                                            goto FORCE_SECOND_INSTANCE;
                                        }
                                        if ($certification->status != 1) {
                                            $this->memo_target($target->id, '沒有驗證成功的還款力計算結果');
                                            goto FORCE_SECOND_INSTANCE;
                                        }
                                        $content = json_decode($certification->content);
                                        if (!isset($content->monthly_repayment) || !isset($content->total_repayment)) {
                                            $this->memo_target($target->id, '沒有有效的還款力計算結果，缺少monthly_repayment 或 total_repayment');
                                            goto FORCE_SECOND_INSTANCE;
                                        }
                                        if (
                                            !is_numeric($content->monthly_repayment)
                                            || !is_numeric($content->total_repayment)
                                        ) {
                                            $message = '還款力計算結果資料類型不正確' .
                                                ', monthly_repayment: ' . $content->monthly_repayment .
                                                ', total_repayment: ' . $content->total_repayment;

                                            log_message('info',
                                                $message .
                                                ', target_id: ' . $target->id .
                                                ', user_id: ' . $user_id .
                                                ', certification: ' . $certification->id);

                                            $this->memo_target($target->id, $message);
                                            goto FORCE_SECOND_INSTANCE;
                                        }
                                        $liabilitiesWithoutAssureTotalAmount = $content->liabilitiesWithoutAssureTotalAmount ?? 0;

                                        $product_id = $target->product_id;
                                        $product = $this->CI->config->item('product_list')[$product_id];
                                        if ($product['condition_rate']['salary_below'] > $content->monthly_repayment * 1000) {
                                            //已進入二審不處理
                                            if ($target->status == TARGET_WAITING_APPROVE && $target->sub_status == TARGET_SUBSTATUS_SECOND_INSTANCE) {
                                                $param['loan_amount'] = $target->loan_amount;
                                                $param['platform_fee'] = $target->platform_fee;
                                                goto FORCE_SECOND_INSTANCE;
                                            }
                                            if ($liabilitiesWithoutAssureTotalAmount > $content->total_repayment * 1000) {
                                                // 高於22倍，0~3000之間
                                                $range_min = 0;
                                                $range_max = 3000;
                                            }
                                            else {
                                                // 低於22倍，額度在3000~10000之間
                                                $range_min = 3000;
                                                $range_max = 10000;
                                            }

                                            $loan_amount = max($range_min, min($range_max, $loan_amount));
                                            $param['loan_amount'] = $loan_amount;

                                            $platform_fee = $this->CI->financial_lib->get_platform_fee($loan_amount, $product_info['charge_platform']);
                                            $param['platform_fee'] = $platform_fee;
                                            goto FORCE_SECOND_INSTANCE;
                                        }
                                    }
                                }

                                // todo: 暫時將「學生貸」、「上班族貸」、「房貸」轉二審
                                if ( ! $subloan_status &&
                                    ! $renew &&
                                    in_array($target->product_id, [PRODUCT_ID_STUDENT, PRODUCT_ID_SALARY_MAN, PRODUCT_ID_HOME_LOAN]) &&
                                    $target->status == TARGET_WAITING_APPROVE &&
                                    $target->sub_status == TARGET_SUBSTATUS_NORNAL)
                                {
                                    goto FORCE_SECOND_INSTANCE;
                                }

                                // #2779: 若信評分數0-450，進二審審核
                                if (isset($credit['points']) && $credit['points'] <= 450 && ! $renew &&  ! $subloan_status)
                                {
                                    goto FORCE_SECOND_INSTANCE;
                                }

                                // #2779: 命中黑名單學校進二審審核
                                $school_config = $this->CI->config->item('school_points');
                                $info = $this->CI->user_meta_model->get_by(['user_id' => $user_id, 'meta_key' => 'school_name']);
                                if ( isset($info) && !$subloan_status && !$renew && in_array($info->meta_value, $school_config['lock_school']) && in_array($target->product_id, [PRODUCT_ID_STUDENT, PRODUCT_ID_STUDENT_ORDER]))
                                {
                                    $approve_target_result->add_memo(TARGET_WAITING_APPROVE, "{$info->meta_value}為黑名單學校", $approve_target_result::DISPLAY_BACKEND);
                                    goto FORCE_SECOND_INSTANCE;
                                }

                                if (
                                    // 命中反詐欺或黑名單，一定要進待二審
                                    ! $matchBrookesia && (
                                        ! $product_info['secondInstance']
                                        && ! $second_instance_check
                                        && $target->product_id < 1000 && $target->sub_status != TARGET_SUBSTATUS_SECOND_INSTANCE
                                        // 依照產品部門需求，「上班族貸」、「房貸」暫時全部強制進待二審
                                        && ! in_array($target->product_id, [PRODUCT_ID_SALARY_MAN, PRODUCT_ID_SALARY_MAN_ORDER, PRODUCT_ID_HOME_LOAN])
                                        || $renew
                                        || $evaluation_status
                                        // ----(S)
                                        // todo: 將「學生貸」、「上班族貸」、「房貸」轉二審，不應有額度就 approve
                                        // || $creditSheet->hasCreditLine()
                                        || ($creditSheet->hasCreditLine() && ! in_array($target->product_id, [PRODUCT_ID_STUDENT, PRODUCT_ID_SALARY_MAN, PRODUCT_ID_HOME_LOAN]))
                                        // ----(E)
                                    )
                                    || $subloan_status
                                ) {
                                    $param['status'] = TARGET_WAITING_SIGNING;

                                    $remark
                                        ? $param['remark'] = (empty($target->remark)
                                            ? $remark
                                            : $target->remark . ', ' . $remark)
                                        : '';
                                    $msg = $target->status == TARGET_WAITING_APPROVE ? true : false;
                                    $target->sub_product_id == STAGE_CER_TARGET && $target->status == TARGET_WAITING_SIGNING && $stage_cer == 0 ? $param['sub_product_id'] = 0 : '';
                                    $renew ? $param['sub_status'] = TARGET_SUBSTATUS_SECOND_INSTANCE_TARGET : '';
                                    if ($target->contract_id == null || $target->loan_amount != $loan_amount) {
                                        $contract_type = 'lend';
                                        $contract_data =  ['', $user_id, $loan_amount, $interest_rate, ''];
                                        if($target->product_id == PRODUCT_FOREX_CAR_VEHICLE && $target->sub_product_id == 3){
                                            $this->CI->load->model('user/judicial_person_model');
                                            $companyData = $this->CI->judicial_person_model->get_by(['company_user_id' => $user_id]);
                                            $userData = $this->CI->user_model->get($companyData->user_id);
                                            $contract_year = date('Y') - 1911;
                                            $contract_month = date('m');
                                            $contract_day = date('d');
                                            $contract_type = 'lend_FEV';
                                            $contract_data = [ '', $user_id, $loan_amount, $target->interest_rate, $contract_year, $contract_month, $contract_day, $targetData->vin, '', '', '', $companyData->company, $userData->name, $companyData->tax_id, $companyData->cooperation_address];
                                        }
                                        $param['contract_id'] = $this->CI->contract_lib->sign_contract($contract_type, $contract_data);
                                    }

                                    $opinion = '一審通過';
                                } else {
                                    FORCE_SECOND_INSTANCE:
                                    $param['sub_status'] = TARGET_SUBSTATUS_SECOND_INSTANCE;
                                    $msg = false;
                                    $opinion = '需二審查核';
                                }
                                $tempData = json_decode($target->target_data,true);
                                if(isset($tempData) && !empty($tempData)) {
                                    $targetData = json_decode(json_encode($targetData), true);
                                    $tempData = array_replace_recursive($tempData, is_array($targetData) ? $targetData : []);
                                } else {
                                    $tempData = $targetData;
                                }
                                $param['target_data'] = json_encode($tempData);

                                if ($param['status'] == TARGET_WAITING_APPROVE)
                                {
                                    $tmp_target_info = $this->CI->target_model->as_array()->get($target->id);
                                    if ($tmp_target_info['status'] == TARGET_WAITING_SIGNING)
                                    {
                                        return FALSE;
                                    }
                                }

                                $temp_new_memo = $approve_target_result->get_all_memo($param['status']);
                                $param['memo'] = json_encode($temp_new_memo, JSON_PRETTY_PRINT);

                                $rs = $this->CI->target_model->update($target->id, $param);

                                if(!$renew) {
                                    $creditSheet->approve($creditSheet::CREDIT_REVIEW_LEVEL_SYSTEM, $opinion);
                                    if($msg)
                                        $creditSheet->setFinalReviewerLevel($creditSheet::CREDIT_REVIEW_LEVEL_SYSTEM);
                                }

                                if ($rs ) {
                                    $creditSheet->archive($credit);
                                    if($opinion == '一審通過' && $msg){
                                        $this->CI->notification_lib->approve_target($user_id, '1', $target, $loan_amount, $subloan_status);
                                    }
                                }
                                $this->insert_change_log($target->id, $param);
                                return true;
                            } else if ($product_info['type'] == 2) {
                                $allow = true;
                                $sub_status = TARGET_SUBSTATUS_NORNAL;
                                if ($loan_amount < $target->amount) {
                                    $target->amount < 50000 ? $sub_status = TARGET_SUBSTATUS_SECOND_INSTANCE : $allow = false;
                                }
                                if (!$matchBrookesia
                                    && $allow) {
                                    $param = [
                                        'loan_amount' => $loan_amount,
                                        'credit_level' => $credit['level'],
                                        'interest_rate' => $interest_rate,
                                        'status' => TARGET_ORDER_WAITING_SHIP,
                                        'sub_status' => $sub_status,
                                    ];
                                    if($sub_status == TARGET_SUBSTATUS_SECOND_INSTANCE)
                                        $creditSheet->approve($creditSheet::CREDIT_REVIEW_LEVEL_SYSTEM, '需二審查核');
                                    else
                                        $creditSheet->approve($creditSheet::CREDIT_REVIEW_LEVEL_SYSTEM, '一審通過');

                                    $rs = $this->CI->target_model->update($target->id, $param);
                                    $creditSheet->archive($credit);
                                    $this->insert_change_log($target->id, $param);
                                    if ($rs) {
                                        $this->CI->load->model('user/user_bankaccount_model');
                                        $bank_account = $this->CI->user_bankaccount_model->get_by([
                                            'status' => 1,
                                            'investor' => 0,
                                            'verify' => 0,
                                            'user_id' => $user_id
                                        ]);
                                        if ($bank_account) {
                                            $bankaccount_info = ['verify' => 2];
                                            $this->CI->user_bankaccount_model->update($bank_account->id, $bankaccount_info);

                                            // 寫 Log
                                            $this->CI->load->library('user_bankaccount_lib');
                                            $this->CI->user_bankaccount_lib->insert_change_log($bank_account->id, $bankaccount_info);
                                        }
                                    }
                                } else {
                                    $failed_condition = ['matchBrookesia' => $matchBrookesia, 'allow' => $allow];
                                    $this->approve_target_fail($user_id, $target, false, '',
                                        $failed_condition);
                                }
                            }
                        } else {
                            $failed_condition = ['loan_amount' => $loan_amount,
                                'loan_range_s' => $product_info['loan_range_s'], 'subloan_status' => $subloan_status];
                            $this->approve_target_fail($user_id, $target, false, '',
                                $failed_condition);
                        }
                    } else {
                        $failed_condition = ['user_current_credit_amount' => $user_current_credit_amount,
                            'subloan_status' => $subloan_status];
                        $this->approve_target_fail($user_id, $target, ($user_current_credit_amount != 0 ? true : false),
                            '', $failed_condition);
                    }
                } else {
                    $failed_condition = ['interest_rate' => $interest_rate];
                    $this->approve_target_fail($user_id, $target, false, '', $failed_condition);
                }
                //return $rs;
            }
        }
        return false;
    }

    private function approve_target_fail($user_id, $target, $maxAmountAlarm = false , $remark = '', $failed_condition = [])
    {
        if ($remark == '') {
            $remark = '經AI系統綜合評估後，暫時無法核准您的申請，感謝您的支持與愛護，希望下次還有機會為您服務' . ($maxAmountAlarm ? '.' : '。');
        }
        $memo = json_decode($target->memo, true) ?? [];
        $memo['failed_condition'] = $failed_condition;
        $param = [
            'loan_amount' => 0,
            'status' => '9',
            'remark' => $remark,
            'memo' => json_encode($memo, JSON_UNESCAPED_UNICODE),
        ];
        $this->CI->target_model->update($target->id, $param);
        $this->insert_change_log($target->id, $param);
        $this->CI->notification_lib->approve_target($user_id, '9', $target, 0, false, $remark);

        $guarantors = $this->get_associates_user_data($target->id, 'all', 1, false);
        if($guarantors){
            $this->CI->target_associate_model->update_by([
                'target_id' => $target->id,
            ],["status" => 9]);
        }
        if ($target->order_id != 0) {
            $this->CI->load->model('transaction/order_model');
            $this->CI->order_model->update($target->order_id, ['status' => 9]);
            $this->coop_status_change_no($target->order_id, 'approve_fail');
        }
        return false;
    }

    private function memo_target($target_id, $message)
    {
        $target = $this->CI->target_model->get_by(['id' => $target_id]);
        $memo = is_null($target->memo) ? [] : json_decode($target->memo, true);
        $memo['repayment_msg'] = $message;
        $this->CI->target_model->update($target_id, ['memo' => json_encode($memo)]);
//        $this->insert_change_log($target_id, ['memo' => $memo]);
    }

    public function target_verify_success($target = [], $admin_id = 0, $param = [], $user_id = 0)
    {
        if (!empty($target) && $target->status == 2) {
            $param['status'] = 3;
            $param['launch_times'] = isset($param['launch_times']) ? $param['launch_times'] : 1;

            if ($target->sub_status != 8) {
                if ($target->sub_product_id == STAGE_CER_TARGET) {
                    // 階段性上架
                    $param['expire_time'] = strtotime('+2 days', time());
                } else {
                    // 一般
                    $param['expire_time'] = strtotime('+2 month', time());
                }
            }

            $this->CI->target_model->update($target->id, $param);
            $this->insert_change_log($target->id, $param, $user_id, $admin_id);
            $this->CI->notification_lib->target_verify_success($target);
            $this->aiBiddingTarget($target);
            return true;
        }
        return false;
    }

    public function target_verify_failed($target = [], $admin_id = 0, $remark = '審批不通過')
    {
        if (!empty($target)) {
            if ($target->order_id != 0) {
                $this->order_fail($target ,$admin_id ,$remark);
            }else{
                $remark = !empty($target->remark) ? $target->remark . ', ' . $remark : $remark;
                $param = [
                    'loan_amount' => 0,
                    'status' => 9,
                    'sub_status' => 0,
                    'remark' => $remark,
                ];
                $target->status == TARGET_BANK_FAIL ? $param['status'] = TARGET_BANK_FAIL : '';
                $this->CI->target_model->update($target->id, $param);
                $this->insert_change_log($target->id, $param, 0, $admin_id);
                $this->CI->notification_lib->target_verify_failed($target->user_id, 0, $remark);
            }
        }
        return false;
    }

    public function target_sign_failed($target = [], $admin_id = 0, $product_name, $param = [])
    {
        if (!empty($target)) {
            $param['status'] = 1;
            $this->CI->target_model->update($target->id, $param);
            $this->insert_change_log($target->id, $param, 0, $admin_id);
            $this->CI->notification_lib->target_sign_failed($target->user_id, 0, $product_name);
        }
        return false;
    }

    public function order_fail($target = [], $admin_id = 0, $remark = '審批不通過')
    {
        if (!empty($target)) {
            $remark = !empty($target->remark) ? $target->remark . ', ' . $remark : $remark;
            $param = [
                'loan_amount' => 0,
                'status' => 9,
                'sub_status' => 0,
                'remark' => $remark,
            ];
            $this->CI->target_model->update($target->id, $param);
            $this->insert_change_log($target->id, $param, 0, $admin_id);
            $this->CI->notification_lib->target_verify_failed($target->user_id, 0, $remark);
            $this->CI->load->model('transaction/order_model');
            $this->CI->order_model->update($target->order_id, ['status' => 9]);
            $user_info = $this->CI->user_model->get($target->user_id);
            $this->cancel_target($target, $target->user_id, $user_info->phone);
        }
        return false;
    }

    //判斷流標或結標或凍結投資款項
    function check_bidding($target)
    {
        if ($target && $target->status == 3) {
            $this->CI->load->model('loan/investment_model');
            $this->CI->load->model('transaction/frozen_amount_model');
            $this->CI->load->model('user/virtual_account_model');
            $this->CI->load->library('Subloan_lib');
            $this->CI->load->library('Contract_lib');
            $this->CI->load->library('Transaction_lib');

            $investments = $this->CI->investment_model->order_by('tx_datetime', 'asc')->get_many_by([
                'target_id' => $target->id,
                'status' => [0, 1]
            ]);
            if ($investments) {

                $amount = 0;
                foreach ($investments as $key => $value) {
                    if ($value->status == 1 && $value->frozen_status == 1 && $value->frozen_id) {
                        $amount += $value->amount;
                    }
                }
                //更新invested
                $this->CI->target_model->update($target->id, ['invested' => $amount]);
                if ($amount >= $target->loan_amount) {

                    //結標
                    $target_update_param = [
                        'status' => 4,
                        'loan_status' => 2
                    ];
                    $rs = $this->CI->target_model->update($target->id, $target_update_param);
                    $this->insert_change_log($target->id, $target_update_param);
                    if ($rs) {
                        $this->CI->notification_lib->auction_closed($target->user_id, 0, $target->target_no, $target->loan_amount);
                        $total = 0;
                        $ended = true;
                        foreach ($investments as $key => $value) {
                            $param = ['status' => 9];
                            if ($value->status == 1 && $value->frozen_status == 1 && $value->frozen_id) {
                                $total += $value->amount;
                                if ($total < $target->loan_amount && $ended) {
                                    $loan_amount = $value->amount;
                                    $schedule = $this->CI->financial_lib->get_amortization_schedule($loan_amount, $target);
                                    $contract_id = $this->signContract($target ,$value->user_id ,$schedule['total_payment'],$loan_amount);
                                    $param = [
                                        'loan_amount' => $loan_amount,
                                        'contract_id' => $contract_id,
                                        'status' => 2
                                    ];
                                    $this->CI->notification_lib->auction_closed($value->user_id, 1, $target->target_no, $loan_amount);
                                } else if ($total >= $target->loan_amount && $ended) {
                                    $loan_amount = $value->amount + $target->loan_amount - $total;
                                    $schedule = $this->CI->financial_lib->get_amortization_schedule($loan_amount, $target);
                                    $contract_id = $this->signContract($target ,$value->user_id ,$schedule['total_payment'],$loan_amount);
                                    $param = [
                                        'loan_amount' => $loan_amount,
                                        'contract_id' => $contract_id,
                                        'status' => 2
                                    ];
                                    $this->CI->notification_lib->auction_closed($value->user_id, 1, $target->target_no, $loan_amount);
                                    $ended = false;
                                } else {
                                    $this->CI->frozen_amount_model->update($value->frozen_id, array('status' => 0));
                                }
                            }
                            $this->insert_investment_change_log($value->id, $param);
                            $this->CI->investment_model->update($value->id, $param);
                        }
                        $this->CI->load->library('Sendemail');
                        $this->CI->sendemail->admin_notification('案件待放款 會員ID：' . $target->user_id, '案件待放款 會員ID：' . $target->user_id . ' 案號：' . $target->target_no);
                        return true;
                    }
                } else {
                    if ($target->expire_time < time()) {
                        //流標
                        if ($this->is_sub_loan($target->target_no) === TRUE)
                        {
                            $this->CI->subloan_lib->renew_subloan($target);
                        } elseif ($target->sub_product_id == STAGE_CER_TARGET) {
                            $param = [
                                'loan_amount' => 0,
                                'status' => 9,
                                'sub_status' => 0,
                            ];
                            $this->CI->target_model->update($target->id, $param);
                            $this->insert_change_log($target->id, $param);
                            $this->CI->notification_lib->stageCer_Target_remind($target->user_id);
                        } else {
                            // 直接整案退回，故不需要再執行到下面退債權的邏輯，直接返回
                            $this->cancel_success_target($target);
                            $this->CI->notification_lib->target_credit_expired($target->user_id);
                            return TRUE;
                        }
                        foreach ($investments as $key => $value) {
                            $this->insert_investment_change_log($value->id, ['status' => 9]);
                            $this->CI->investment_model->update($value->id, ['status' => 9]);
                            if ($value->status == 1 && $value->frozen_status == 1 && $value->frozen_id) {
                                $this->CI->frozen_amount_model->update($value->frozen_id, ['status' => 0]);
                            }
                        }
                    } else {
                        //凍結款項
                        foreach ($investments as $key => $value) {
                            if ($value->status == 0 && $value->frozen_status == 0) {
                                $virtual_account = $this->CI->virtual_account_model->get_by([
                                    'status' => 1,
                                    'investor' => 1,
                                    'user_id' => $value->user_id
                                ]);
                                if ($virtual_account) {
                                    $this->CI->virtual_account_model->update($virtual_account->id, ['status' => 2]);
                                    $funds = $this->CI->transaction_lib->get_virtual_funds($virtual_account->virtual_account);
                                    $total = $funds['total'] - $funds['frozen'];
                                    if (intval($total) - intval($value->amount) >= 0) {
                                        $last_recharge_date = strtotime($funds['last_recharge_date']);
                                        $tx_datetime = $last_recharge_date < $value->created_at ? $value->created_at : $last_recharge_date;
                                        $tx_datetime = date('Y-m-d H:i:s', $tx_datetime);
                                        $param = [
                                            'virtual_account' => $virtual_account->virtual_account,
                                            'amount' => intval($value->amount),
                                            'tx_datetime' => $tx_datetime,
                                        ];
                                        $rs = $this->CI->frozen_amount_model->insert($param);
                                        if ($rs) {
                                            $this->insert_investment_change_log($value->id, ['frozen_status' => 1, 'frozen_id' => $rs, 'status' => 1, 'tx_datetime' => $tx_datetime]);
                                            $this->CI->investment_model->update($value->id, ['frozen_status' => 1, 'frozen_id' => $rs, 'status' => 1, 'tx_datetime' => $tx_datetime]);
                                        }
                                    }
                                    $this->CI->virtual_account_model->update($virtual_account->id, ['status' => 1]);
                                }
                            }
                        }

                        $investments = $this->CI->investment_model->get_many_by([
                            'target_id' => $target->id,
                            'status' => 1
                        ]);
                        if ($investments) {
                            $amount = 0;
                            foreach ($investments as $key => $value) {
                                if ($value->status == 1 && $value->frozen_status == 1 && $value->frozen_id) {
                                    $amount += $value->amount;
                                }
                            }
                            //更新invested
                            $this->CI->target_model->update($target->id, ['invested' => $amount]);
                        }
                    }
                    return true;
                }
            } else {
                if ($target->expire_time < time()) {
                    if ($this->is_sub_loan($target->target_no) === TRUE)
                    {
                        $this->CI->subloan_lib->renew_subloan($target);
                    } elseif ($target->sub_product_id == STAGE_CER_TARGET) {
                        $param = [
                            'loan_amount' => 0,
                            'status' => 9,
                            'sub_status' => 0,
                        ];
                        $this->CI->target_model->update($target->id, $param);
                        $this->insert_change_log($target->id, $param, 0, 0);
                        $this->CI->notification_lib->stageCer_Target_remind($target->user_id);
                    } else {
                        $this->cancel_success_target($target);
                        $this->CI->notification_lib->target_credit_expired($target->user_id);
                    }
                }
                return true;
            }
        }
        return false;
    }


    public function target_cancel_bidding($target = array(), $admin_id = 0, $remark = "請連繫客服協助處理")
    {
        if (!empty($target)) {
            $param = array(
                "status" => TARGET_WAITING_VERIFY,
                "launch_times" => 0,
                "remark" => $remark,
            );
            $this->CI->target_model->update($target->id, $param);
            $this->insert_change_log($target->id, $param, 0, $admin_id);
            $this->CI->notification_lib->target_cancel_bidding($target->user_id, 0, $remark);

            $this->CI->load->model('loan/investment_model');
            $investments = $this->CI->investment_model->get_many_by([
                'target_id' => $target->id,
                'status' => [0, 1]
            ]);
            foreach ($investments as $inv_key => $inv_val) {
                $this->cancel_investment($target, $inv_val, 0, $admin_id);
            }
        }
        return false;
    }

    //借款端還款計畫
    public function get_amortization_table($target = [])
    {
        $target = is_array($target) && ! empty($target) ? json_decode(json_encode($target)) : $target;
        $schedule = [
            'amount' => intval($target->loan_amount),
            'remaining_principal' => intval($target->loan_amount),
            'last_interest' => intval(0),
            'instalment' => intval($target->instalment),
            'rate' => floatval($target->interest_rate),
            'total_payment' => 0,
            'date' => $target->loan_date,
            'end_date' => '',
            'sub_loan_fees' => 0,
            'platform_fees' => 0,
            'legal_affairs_fee' => 0,
            'delay_interest' => intval(0),
            'liquidated_damages' => intval(0),
            'list' => [],

        ];
        $transactions = $this->CI->transaction_model->get_many_by([
            'user_from' => $target->user_id,
            'target_id' => $target->id,
            'status !=' => 0
        ]);

        $list = [];
        if ($transactions) {
            foreach ($transactions as $key => $value) {
                if (intval($value->instalment_no) && !isset($list[$value->instalment_no])) {
                    $list[$value->instalment_no] = [
                        'instalment' => intval($value->instalment_no),//期數
                        'total_payment' => 0,//本期應還款金額
                        'repayment' => 0,//本期已還款金額
                        'interest' => 0,//利息
                        'principal' => 0,//本金
                        'delay_interest' => 0,//延滯息
                        'liquidated_damages' => 0,//違約金
                        'days' => 0,//本期天數
                        'remaining_principal' => 0,//期初本金
                        'repayment_date' => $value->limit_date,//還款日
                        'repaid' => 0,
                        'r_principal' => 0,
                    ];
                }
                switch ($value->source) {
                    case SOURCE_AR_PRINCIPAL:
                        $list[$value->instalment_no]['principal'] += $value->amount;
                        break;
                    case SOURCE_AR_INTEREST:
                        $list[$value->instalment_no]['interest'] += $value->amount;
                        if ($value->status == 1) {
                            $schedule['last_interest'] = $value->amount;
                        }
                        break;
                    case SOURCE_AR_DAMAGE:
                        $list[$value->instalment_no]['liquidated_damages'] += $value->amount;
                        $schedule['liquidated_damages'] += $value->amount;
                        break;
                    case SOURCE_AR_DELAYINTEREST:
                        $list[$value->instalment_no]['delay_interest'] += $value->amount;
                        $schedule['delay_interest'] += $value->amount;
                        break;
                    case SOURCE_SUBLOAN_FEE:
                        $schedule['sub_loan_fees'] += $value->amount;
                        break;
                    case SOURCE_FEES:
                        //判斷法催
                        $value->user_from == $target->user_id && $value->instalment_no > 0 && $target->sub_status == 13
                            ? $schedule['legal_affairs_fee'] += $value->amount : $schedule['platform_fees'] += $value->amount;
                        break;
                    case SOURCE_PRINCIPAL:
                        $list[$value->instalment_no]['r_principal'] += $value->amount;
                    case SOURCE_INTEREST:
                    case SOURCE_DELAYINTEREST:
                    case SOURCE_DAMAGE:
                    case SOURCE_PREPAYMENT_DAMAGE:
                        $list[$value->instalment_no]['repayment'] += $value->amount;
                        !isset($schedule['repaid']) ? $schedule['repaid'] = 0 : '';
                        $schedule['repaid'] += $value->amount;
                        if ($value->source == SOURCE_PRINCIPAL) {
                            $schedule['remaining_principal'] -= $value->amount;
                        } else if ($value->source == SOURCE_PREPAYMENT_DAMAGE) {
                            $list[$value->instalment_no]['liquidated_damages'] += $value->amount;
                        }
                        break;
                    default:
                        break;
                }
            }

            $old_date = $target->loan_date;
            $total = intval($target->loan_amount);
            ksort($list);
            $end = end($list);
            $schedule['end_date'] = $end['repayment_date'];
            foreach ($list as $key => $value) {
                $total_payment = $value['interest'] + $value['principal'] + $value['delay_interest'] + $value['liquidated_damages'];
                $list[$key]['total_payment'] = $total_payment;
                $schedule['total_payment'] += $total_payment;
                $list[$key]['days'] = get_range_days($old_date, $value['repayment_date']);
                $list[$key]['remaining_principal'] = $total;
                $old_date = $value['repayment_date'];
                $total = $total - $value['principal'];
            }
        }
        $schedule['list'] = $list;
        return $schedule;
    }

    private function init_amortization_row($numInstalment, $limitDate)
    {
        return [
            'instalment' => intval($numInstalment),//期數
            'total_payment' => 0,//本期應收款金額
            'repayment' => 0,//本期已收款金額
            'interest' => 0,//利息
            'principal' => 0,//本金
            'delay_interest' => 0,//應收延滯息
            'delay_occurred_at' => 0,
            'delay_principal_return_at' => 0,//逾期清償發生時間
            'delay_interest_return_at' => 0,
            'days' => 0,//本期天數
            'damage' => 0,//違約金,
            'prepayment_allowance' => 0,//提前補償
            'prepayment_damage' => 0,
            'remaining_principal' => 0,//期初本金
            'repayment_principal' => 0,
            'repayment_date' => $limitDate,//還款日
            'ar_fees' => 0,//應收回款手續費
            'r_principal' => 0,
            'r_interest' => 0,
            'r_fees' => 0,
            'r_delayinterest' => 0,
            'r_prepayment_allowance' => 0,
            'r_damages' => 0,
            'r_preapymentDamages' => 0,
            'r_subloan_fees' => 0,
        ];
    }

    public function processOverdueDetails(
        $target,
        $overdueAmortizationRows,
        $overdueStartedAt,
        $lastRepaymentAt
    ) {
        //principal and overdue interest should be kept in following interval unless the target ends
        $remainingPrincipal = 0;
        $remainingInterest = 0;
        $remainingDamage = 0;
        $numInstalment = $target->instalment;
        $startedAt = $overdueAmortizationRows[0]['repayment_date'];
        $overdueOccurredAt = '';
        $mostRecentPaymentAt = $this->CI->payment_time_utility->goToMostRecent(date('Y-m-d', time()));

        if (!$startedAt) {
            return $overdueAmortizationRows;
        }

        $mongthDiff = $this->CI->payment_time_utility->measureMonthGaps($startedAt, $lastRepaymentAt);
        $smallestInstalment = 0;
        $largestInstalment = 200;
        for ($i = 1; $i <= $largestInstalment; $i++) {
            if (isset($overdueAmortizationRows[$i])) {
                continue;
            }
            $smallestInstalment = $i;
            if (!isset($overdueAmortizationRows[$i-1])) {
                continue;
            }
            $overdueAmortizationRows[$i] = $this->init_amortization_row($i, '');
            $overdueAmortizationRows[$i]['repayment_date'] = $this->CI->payment_time_utility->goToNext($overdueAmortizationRows[$i-1]['repayment_date'], true);
        }

        $mongthDiff += $i;
        for ($i = 1; $i <= $mongthDiff; $i++) {
            if (isset($overdueAmortizationRows[$i])) {
                $row = $overdueAmortizationRows[$i];
            } else {
                //amortization schedule not always started with one for some users
                if (!isset($overdueAmortizationRows[$i-1])) {
                    continue;
                }
            }

            if ($overdueStartedAt == $i) {
                $remainingPrincipal = $row['principal'] - $row['r_principal'];
                $remainingInterest = $row['interest'];
                $remainingDamage = $row['damage'];
            }

            if ($i > $overdueStartedAt) {
                $row['principal'] = $remainingPrincipal;
                $row['interest'] = $remainingInterest;
                $row['damage'] = $remainingDamage;
//                $delayDays = get_range_days($overdueAmortizationRows[$i-1]["repayment_date"], $row['repayment_date']);
//                $delayInterest = $this->CI->financial_lib->get_delay_interest($remainingPrincipal, $delayDays);
//                $row['delay_interest'] = $delayInterest + $overdueAmortizationRows[$i-1]['delay_interest'] - $overdueAmortizationRows[$i-1]['r_delayinterest'];
                $delayDays = get_range_days($startedAt, $row['repayment_date']);
                $delayInterest = $this->CI->financial_lib->get_delay_interest($remainingPrincipal, $delayDays);
                $row['delay_interest'] = $delayInterest - $overdueAmortizationRows[$i-1]['r_delayinterest'];

//                if ($row['r_interest'] > 0) {
//                    $remainingInterest = $row['interest'] - $row['r_interest'];
//                }
                $overdueAmortizationRows[$i] = $row;
                if ($row['r_principal'] > 0) {
                    if ($row['delay_principal_return_at']) {
                        $repaymentDiffFromNow = $this->CI->payment_time_utility->measureMonthGaps($overdueAmortizationRows[$i]["repayment_date"], $row['delay_principal_return_at']);
                        if ($repaymentDiffFromNow > 1) {
                            $futureInstalment = $repaymentDiffFromNow + $i;
                            $row['instalment'] = $futureInstalment;
                            $originalRepaymentDate = $overdueAmortizationRows[$futureInstalment]['repayment_date'];
                            $overdueAmortizationRows[$futureInstalment] = $row;
                            $overdueAmortizationRows[$futureInstalment]['repayment_date'] = $originalRepaymentDate;
                            $overdueAmortizationRows[$i]['delay_interest'] = 0;
                            $overdueAmortizationRows[$i]['damage'] = $remainingDamage;
                            $overdueAmortizationRows[$i]['r_fees'] = 0;
                            $overdueAmortizationRows[$i]['r_interest'] = 0;
                            $overdueAmortizationRows[$i]['r_damage'] = 0;
                            $overdueAmortizationRows[$i]['r_principal'] = 0;
                            $overdueAmortizationRows[$i]['r_delayinterest'] = 0;
                            $overdueAmortizationRows[$i]['repayment'] = 0;
                            $delayDaysBeforeReturn = get_range_days($overdueAmortizationRows[$i-1]["repayment_date"], $overdueAmortizationRows[$i]['repayment_date']);
                            $delayInterestAfterReturn = $this->CI->financial_lib->get_delay_interest($remainingPrincipal, $delayDaysBeforeReturn);
                            $overdueAmortizationRows[$i]['delay_interest'] = $delayInterestAfterReturn;
                            continue;
                        }
                    }

                    $overduePrincipalReturnAt = $row['delay_principal_return_at'];
                    $delayDaysBeforeReturn = get_range_days($overdueAmortizationRows[$i-1]["repayment_date"], $overduePrincipalReturnAt);
                    $delayInterestBeforeReturn = $this->CI->financial_lib->get_delay_interest($remainingPrincipal, $delayDaysBeforeReturn);

                    $remainingPrincipal = $row['principal'] - $row['r_principal'];

                    $delayDaysAfterReturn = get_range_days($overduePrincipalReturnAt, $row['repayment_date']);
                    $delayInterestAfterReturn = $this->CI->financial_lib->get_delay_interest($remainingPrincipal, $delayDaysAfterReturn);

                    if ($overdueAmortizationRows[$i]['principal'] - $overdueAmortizationRows[$i]['r_principal'] == 0) {
                        $overdueAmortizationRows[$i]['delay_interest'] = $overdueAmortizationRows[$i]['r_delayinterest'];
                    } else {
                        $overdueAmortizationRows[$i]['delay_interest'] = $delayInterestBeforeReturn + $delayInterestAfterReturn + $overdueAmortizationRows[$i-1]['delay_interest'] - $overdueAmortizationRows[$i-1]['r_delayinterest'];
                    }
                }
                if ($row['r_damages'] > 0) {
                    $remainingPrincipal = $row['damage'] - $row['r_damages'];
                }

                if ($remainingPrincipal == 0) {
                    break;
                }
            }
        }

        return $overdueAmortizationRows;
    }

    public function get_investment_amortization_table_v2($target = [], $investment = [], $lastRepaymentAt = '')
    {
        $xirrDates = [$target->loan_date];
        $xirrValue = [$investment->loan_amount * (-1)];

        $normalSchedule = [
            'amount' => intval($investment->loan_amount),
            'instalment' => intval($target->instalment),
            'rate' => intval($target->interest_rate),
            'total_payment' => 0,
            'XIRR' => 0,
            'date' => $target->loan_date,
            'remaining_principal' => 0,
            'transferDate' => null,
        ];

        $overdueSchedule = $normalSchedule;

        $investmentId = $investment->id;
        $transactions = $this->CI->transaction_model->get_many_by([
            'investment_id' => [0, $investmentId],
            'target_id' => $target->id,
            'status' => [1, 2]
        ]);

        if (!$transactions) {
            return $schedule;
        }

        $limitDate = '';
        $overdueAt = null;
        $overdueStartedAt = -1;
        $normalRepaymentPrincipal = 0;
        $overdueRepaymentPrincipal = 0;
        $normalAmortizationRows = [];
        $overdueAmortizationRows = [];
        $sourcesForSystem = [
            SOURCE_PREPAYMENT_DAMAGE,
            SOURCE_AR_DAMAGE,
            SOURCE_DAMAGE,
            SOURCE_FEES,
            SOURCE_SUBLOAN_FEE
        ];
        foreach ($transactions as $key => $value) {
            if ($value->source == SOURCE_AR_DELAYINTEREST) {
                $overdueAt = $value->limit_date;
                $overdueStartedAt = $value->instalment_no;
            }

            if (
                $value->investment_id == 0
                && !in_array($value->source, $sourcesForSystem)
            ) {
                continue;
            }

            if (
                isset($normalAmortizationRows[$value->instalment_no])
                || isset($overdueAmortizationRows[$value->instalment_no])
            ) {
                continue;
            }

            if ($value->instalment_no && $value->source == SOURCE_AR_PRINCIPAL) {
                $limitDate = $value->limit_date ? $value->limit_date : $limitDate;
                $normalAmortizationRows[$value->instalment_no] = $this->init_amortization_row($value->instalment_no, $limitDate);
                $overdueAmortizationRows[$value->instalment_no] = $this->init_amortization_row($value->instalment_no, $limitDate);
            }
        }

        if (!isset($normalAmortizationRows[0])) {
            $normalAmortizationRows[0] = $this->init_amortization_row(0, $limitDate);
        }
        if (!isset($overdueAmortizationRows[0])) {
            $overdueAmortizationRows[0] = $this->init_amortization_row(0, $limitDate);
        }

        $this->CI->load->model('loan/transfer_model');
        $transferOut = $this->CI->transfer_model->get_by([
            'status' => 10,
            'investment_id' => $investment->id
        ]);
		$normalSchedule['transferOut'] = isset($transferOut->transfer_date) ? $transferOut->transfer_date : '';

        //correct some prepayment instalment numbers as those are incorrect
        if ($target->sub_status == 4 && !$transferOut) {
            //prepayment is alwasy created in the end of the transaction
            usort($transactions, function($a, $b) {
                return strcmp($a->created_at, $b->created_at);
            });

            $toBeModified = [];
            $lastOperation = end($transactions);
            $lastModifiedAt = $lastOperation->created_at;
            $numTransactions = count($transactions);
            for ($i = $numTransactions - 1; $i >= 0; $i--) {
                //random number that prepayment transaction should be created in very short amount of time
                //as the creation not used the same varaible
                if (abs($transactions[$i]->created_at - $lastModifiedAt) > 2) {
                    break;
                }
                $toBeModified[$transactions[$i]->id] = $transactions[$i];
            }

            //take the latest instalment and associated transaction
            $largestInstalmentNo = 0;
            $trancWithlargetest = null;
            for ($i = 0; $i < $numTransactions; $i++) {
                if ($transactions[$i]->instalment_no <= $largestInstalmentNo) {
                    continue;
                }
                $largestInstalmentNo = $transactions[$i]->instalment_no;
                if ($transactions[$i]->limit_date) {
                    $trancWithlargetest = $transactions[$i];
                }
            }

            //modify the prepayment transaction instalment if it is incorrect
            $lastLimitDate = null;
            $correctInstalment = $largestInstalmentNo;
            if ($lastOperation->instalment_no <= $largestInstalmentNo) {
                for ($i = 0; $i < $numTransactions; $i++) {
                    $transactionId = $transactions[$i]->id;
                    if (!isset($toBeModified[$transactionId])) {
                        continue;
                    }
                    if ($correctInstalment == $largestInstalmentNo && $trancWithlargetest->limit_date < $transactions[$i]->entering_date) {
                        $correctInstalment = $largestInstalmentNo + 1;
                    }
                    $transactions[$i]->instalment_no = $correctInstalment;
                    if ($transactions[$i]->limit_date) $lastLimitDate = $transactions[$i]->limit_date;
                }
            }
            if ($correctInstalment > $largestInstalmentNo) {
                $normalAmortizationRows[$largestInstalmentNo + 1] = $this->init_amortization_row($largestInstalmentNo+1, $lastLimitDate);
            }
        }

        $amount = 0;
        foreach ($transactions as $key => $value) {
            if ($value->investment_id == 0 && !in_array($value->source, $sourcesForSystem)) {
                continue;
            }
            $source = $value->source;
            $currentInstalment = $value->instalment_no;
            $repaymentPrincipal = &$normalRepaymentPrincipal;
            $rows = &$normalAmortizationRows;
            $isOverdue = 0;

            if (
                $overdueAt
                && (
                    $value->limit_date && $value->limit_date >= $overdueAt
                    || !$value->limit_date && $value->entering_date >= $overdueAt
                )
            ) {
                $repaymentPrincipal = &$overdueRepaymentPrincipal;
                $rows = &$overdueAmortizationRows;
                $isOverdue =1;
            }
            if (!isset($rows[$currentInstalment])) continue;
            if ($source == SOURCE_PRINCIPAL) {
                if ($overdueAt && $overdueStartedAt <= $currentInstalment) {
                    $nextInstalment = $currentInstalment+1;
                    if (!isset($rows[$nextInstalment])) {
                        $rows[$nextInstalment] = $this->init_amortization_row($nextInstalment, $this->CI->payment_time_utility->goToNext($value->entering_date));
                    }
                    $rows[$nextInstalment]['r_principal'] += $value->amount;
                    $rows[$nextInstalment]['delay_principal_return_at'] = $value->entering_date;
                    $rows[$nextInstalment]['repayment'] += $value->amount;
                } else {
                    $rows[$currentInstalment]['r_principal'] += $value->amount;
                    $rows[$currentInstalment]['repayment'] += $value->amount;
                }
            }elseif ($source == SOURCE_INTEREST) {
                if ($overdueAt) {
                    $nextInstalment = $currentInstalment+1;
                    if (!isset($rows[$nextInstalment])) {
                        $rows[$nextInstalment] = $this->init_amortization_row($nextInstalment, $this->CI->payment_time_utility->goToNext($value->entering_date));
                    }
                    $rows[$nextInstalment]['r_interest'] += $value->amount;
                    $rows[$nextInstalment]['repayment'] += $value->amount;
                } else {
                    $rows[$currentInstalment]['r_interest'] += $value->amount;
                    $rows[$currentInstalment]['repayment'] += $value->amount;
                }
            }elseif ($source == SOURCE_FEES && $value->user_from != $target->user_id) {
                if ($overdueAt && substr($value->entering_date, -2) != '10') {
                    $nextInstalment = $currentInstalment+1;
                    if (!isset($rows[$nextInstalment])) {
                        $rows[$nextInstalment] = $this->init_amortization_row($nextInstalment, $this->CI->payment_time_utility->goToNext($value->entering_date));
                    }
                    $rows[$nextInstalment]['r_fees'] += $value->amount;
                } else {
                    $rows[$currentInstalment]['r_fees'] += $value->amount;
                }
            }elseif ($source == SOURCE_DELAYINTEREST){
                if ($overdueAt) {
                    $nextInstalment = $currentInstalment+1;
                    if (!isset($rows[$nextInstalment])) {
                        $rows[$nextInstalment] = $this->init_amortization_row($nextInstalment, $this->CI->payment_time_utility->goToNext($value->entering_date));
                    }
                    $rows[$nextInstalment]['delay_interest_return_at'] = $value->entering_date;
                    $rows[$nextInstalment]['r_delayinterest'] += $value->amount;
                    $rows[$nextInstalment]['repayment'] += $value->amount;
                } else {
                    $rows[$currentInstalment]['r_delayinterest'] += $value->amount;
                    $rows[$currentInstalment]['repayment'] += $value->amount;
                }
            }elseif ($source == SOURCE_PREPAYMENT_ALLOWANCE){
                $rows[$currentInstalment]['r_prepayment_allowance'] += $value->amount;
            }elseif ($source == SOURCE_DAMAGE){
                $rows[$currentInstalment]['r_damages'] += $value->amount;
            }elseif ($source == SOURCE_PREPAYMENT_DAMAGE){
                $rows[$currentInstalment]['r_preapymentDamages'] += $value->amount;
            }elseif ($source == SOURCE_SUBLOAN_FEE) {
                $rows[$currentInstalment]['r_subloan_fees'] += $value->amount;
            }

            switch ($source) {
                case SOURCE_AR_PRINCIPAL:
                    $rows[$currentInstalment]['principal'] += $value->amount;
                    break;
                case SOURCE_AR_INTEREST:
                    $rows[$currentInstalment]['interest'] += $value->amount;
                    break;
                case SOURCE_AR_DAMAGE:
                    $rows[$currentInstalment]['damage'] += $value->amount;
                    break;
                case SOURCE_PREPAYMENT_ALLOWANCE:
                    $rows[$currentInstalment]['prepayment_allowance'] += $value->amount;
                    break;
                case SOURCE_PREPAYMENT_DAMAGE:
                    $rows[$currentInstalment]['prepayment_damage'] += $value->amount;
                    break;
                case SOURCE_AR_DELAYINTEREST:
                    if ($overdueAt && substr($value->entering_date, -2) != '10') {
                        $nextInstalment = $currentInstalment+1;
                        if (!isset($rows[$nextInstalment])) {
                            $rows[$nextInstalment] = $this->init_amortization_row($nextInstalment, $this->CI->payment_time_utility->goToNext($value->entering_date));
                        }
                        $rows[$nextInstalment]['delay_interest'] += $value->amount;
                        $rows[$nextInstalment]['delay_occurred_at'] = $value->entering_date;
                    } elseif ($overdueAt) {
                        $rows[$currentInstalment]['delay_interest'] += $value->amount;
                        $rows[$currentInstalment]['delay_occurred_at'] = $value->entering_date;
                    }
                    break;
                case SOURCE_AR_FEES:
                    $rows[$currentInstalment]['ar_fees'] += $value->amount;
                    break;
                default:
                    break;
            }
        }

        ksort($normalAmortizationRows);
        ksort($overdueAmortizationRows);
        $overdueAmortizationRows = $this->processOverdueDetails($target, $overdueAmortizationRows, $overdueStartedAt, $lastRepaymentAt);

        $oldDate = $target->loan_date;
        $total = intval($investment->loan_amount);

        $this->CI->load->model('loan/transfer_model');
        $transfer = $this->CI->transfer_model->get_by([
            'status' => 10,
            'new_investment' => $investment->id
        ]);

        if ($transfer) {
            $total = intval($transfer->principal);
            $normalSchedule['transferDate'] = $transfer->transfer_date;
        }

        if ($overdueStartedAt > 0) {
            $normalAmortizationRows[$overdueStartedAt] = $this->init_amortization_row(0, $normalAmortizationRows[$overdueStartedAt]['repayment_date']);
        }

        $tempValue = 0;
        foreach ($normalAmortizationRows as $key => $value) {
            $normalAmortizationRows[$key]['days'] = isset($value['repayment_date'])?get_range_days($oldDate, $value['repayment_date']):null;
            $oldDate = isset($value['repayment_date'])?$value['repayment_date']:null;

            //判斷當期實際還款，如當期未還將暫存$tempValue供後期補上
            $pay_date = date('Y-m-', time()) . REPAYMENT_DAY;
            if($value['r_principal'] != $value['principal'] && $normalAmortizationRows[$key]['repayment_date'] == $pay_date){
                $tempValue = $normalAmortizationRows[$key]['principal'];
            }else{
                $total -= $normalAmortizationRows[$key]['principal'] + $tempValue;
                $tempValue = 0;
            }

            $normalAmortizationRows[$key]['remaining_principal'] = $total;
            $normalSchedule['total_payment'] += $value['total_payment'];
            $xirrDates[] = isset($value['repayment_date'])?$value['repayment_date']:null;
            $xirrValue[] = $value['total_payment'];
        }

        foreach ($overdueAmortizationRows as $key => $value) {
            $overdueAmortizationRows[$key]['days'] = isset($value['repayment_date'])?get_range_days($oldDate, $value['repayment_date']):null;
            $overdueAmortizationRows[$key]['remaining_principal'] += $value['principal'] - $value['r_principal'];

            $oldDate = isset($value['repayment_date'])?$value['repayment_date']:null;

            $overdueSchedule['total_payment'] += $value['total_payment'];
            $xirrDates[] = isset($value['repayment_date'])?$value['repayment_date']:null;
            $xirrValue[] = $value['total_payment'];
        }

        $normalAmortizationRows['XIRR'] = 0;//200306 close
        $overdueAmortizationRows['XIRR'] = 0;

        $normalSchedule['rows'] = $normalAmortizationRows;
        $overdueSchedule['rows'] = $overdueAmortizationRows;

        return ['normal' => $normalSchedule, 'overdue' => $overdueSchedule];
    }

    //出借端回款計畫
    public function get_investment_amortization_table($target = [], $investment = [], $full = false)
    {

        $xirr_dates = [$target->loan_date];
        $xirr_value = [$investment->loan_amount * (-1)];
        $list = [];
        $schedule = [
            'amount' => intval($investment->loan_amount),
            'instalment' => intval($target->instalment),
            'rate' => intval($target->interest_rate),
            'total_payment' => 0,
            'XIRR' => 0,
            'date' => $target->loan_date,
            'remaining_principal' => 0,
        ];
        $repayment_principal = 0;
        $investment_id = $full ? [$investment->id, 0] : $investment->id;
        $transactions = $this->CI->transaction_model->get_many_by([
            'investment_id' => $investment_id,
            'target_id' => $target->id,
            'status' => [1, 2]
        ]);

        if ($transactions) {
            $limit_date = '';
            foreach ($transactions as $key => $value) {
                if ($value->instalment_no && $value->source == SOURCE_AR_PRINCIPAL) {
                    $limit_date = $value->limit_date ? $value->limit_date : $limit_date;
                    $list[$value->instalment_no] = [
                        'instalment' => intval($value->instalment_no),//期數
                        'total_payment' => 0,//本期應收款金額
                        'repayment' => 0,//本期已收款金額
                        'interest' => 0,//利息
                        'principal' => 0,//本金
                        'delay_interest' => 0,//應收延滯息
                        'days' => 0,//本期天數
                        'remaining_principal' => 0,//期初本金
                        'repayment_date' => $limit_date,//還款日
                        'ar_fees' => 0,//應收回款手續費
                        'r_principal' => 0,
                        'r_interest' => 0,
                        'r_fees' => 0,
                        'r_delayinterest' => 0,
                        'r_prepayment_allowance' => 0,
                        'r_damages' => 0,
                        'r_preapymentDamages' => 0,
                        'r_subloan_fees' => 0,
                    ];
                }
            }
            foreach ($transactions as $key => $value) {
                if ($value->source == SOURCE_PRINCIPAL) {
                    !isset($list[$value->instalment_no]['r_principal'])?$list[$value->instalment_no]['r_principal'] = 0:'';
                    $list[$value->instalment_no]['r_principal'] += $value->amount;
                }elseif ($value->source == SOURCE_INTEREST) {
                    !isset($list[$value->instalment_no]['r_interest'])?$list[$value->instalment_no]['r_interest'] = 0:'';
                    $list[$value->instalment_no]['r_interest'] += $value->amount;
                }elseif ($value->source == SOURCE_FEES) {
                    !isset($list[$value->instalment_no]['r_fees'])?$list[$value->instalment_no]['r_fees'] = 0:'';
                    $list[$value->instalment_no]['r_fees'] += $value->amount;
                }elseif ($value->source == SOURCE_DELAYINTEREST){
                    !isset($list[$value->instalment_no]['r_delayinterest'])?$list[$value->instalment_no]['r_delayinterest'] = 0:'';
                    $list[$value->instalment_no]['r_delayinterest'] += $value->amount;
                }elseif ($value->source == SOURCE_PREPAYMENT_ALLOWANCE){
                    !isset($list[$value->instalment_no]['r_prepayment_allowance'])?$list[$value->instalment_no]['r_prepayment_allowance'] = 0:'';
                    $list[$value->instalment_no]['r_prepayment_allowance'] += $value->amount;
                }elseif ($value->source == SOURCE_DAMAGE){
                    !isset($list[$value->instalment_no]['r_damages'])?$list[$value->instalment_no]['r_damages'] = 0:'';
                    $list[$value->instalment_no]['r_damages'] += $value->amount;
                }elseif ($value->source == SOURCE_PREPAYMENT_DAMAGE){
                    !isset($list[$value->instalment_no]['r_preapymentDamages'])?$list[$value->instalment_no]['r_preapymentDamages'] = 0:'';
                    $list[$value->instalment_no]['r_preapymentDamages'] += $value->amount;
                }elseif ($value->source == SOURCE_SUBLOAN_FEE) {
                    !isset($list[$value->instalment_no]['r_subloan_fees'])?$list[$value->instalment_no]['r_subloan_fees'] = 0:'';
                    $list[$value->instalment_no]['r_subloan_fees'] += $value->amount;
                }
                switch ($value->source) {
                    case SOURCE_AR_PRINCIPAL:
                        !isset($list[$value->instalment_no]['principal'])?$list[$value->instalment_no]['principal'] = 0:'';
                        $list[$value->instalment_no]['principal'] += $value->amount;
                        break;
                    case SOURCE_AR_INTEREST:
                        !isset($list[$value->instalment_no]['interest'])?$list[$value->instalment_no]['interest'] = 0:'';
                        $list[$value->instalment_no]['interest'] += $value->amount;
                        break;
                    case SOURCE_AR_DELAYINTEREST:
                        !isset($list[$value->instalment_no]['delay_interest'])?$list[$value->instalment_no]['delay_interest'] = 0:'';
                        $list[$value->instalment_no]['delay_interest'] += $value->amount;
                        break;
                    case SOURCE_PRINCIPAL:
                    case SOURCE_DELAYINTEREST:
                    case SOURCE_INTEREST:
                        !isset($list[$value->instalment_no]['repayment'])?$list[$value->instalment_no]['repayment'] = 0:'';
                        $list[$value->instalment_no]['repayment'] += $value->amount;
                        if ($value->source == SOURCE_PRINCIPAL) {
                            $repayment_principal += $value->amount;
                        }
                        break;
                    case SOURCE_AR_FEES:
                        !isset($list[$value->instalment_no]['ar_fees'])?$list[$value->instalment_no]['ar_fees'] = 0:'';
                        $list[$value->instalment_no]['ar_fees'] += $value->amount;
                        break;
                    default:
                        break;
                }
                if ($value->instalment_no) {
                    !isset($list[$value->instalment_no]['interest'])?$list[$value->instalment_no]['interest'] = 0 : '';
                    !isset($list[$value->instalment_no]['principal'])?$list[$value->instalment_no]['principal'] = 0 : '';
                    !isset($list[$value->instalment_no]['delay_interest'])?$list[$value->instalment_no]['delay_interest'] = 0 : '';
                    $list[$value->instalment_no]['total_payment'] =
                        $list[$value->instalment_no]['interest'] +
                        $list[$value->instalment_no]['principal'] +
                        $list[$value->instalment_no]['delay_interest'];
                }
            }

            $old_date = $target->loan_date;
            $total = intval($investment->loan_amount);
            $this->CI->load->model('loan/transfer_model');
            $transfer = $this->CI->transfer_model->get_by([
                'status' => 10,
                'new_investment' => $investment->id
            ]);
            if ($transfer) {
                $total = intval($transfer->principal);
            }
            $schedule['target_status'] = $target->status;
            $schedule['target_sub_status'] = $target->sub_status;
            $schedule['target_delay_days'] = $target->delay_days;
            $schedule['remaining_principal'] = $total - $repayment_principal;
            ksort($list);
            foreach ($list as $key => $value) {
                $list[$key]['days'] = isset($value['repayment_date'])?get_range_days($old_date, $value['repayment_date']):null;
                $list[$key]['remaining_principal'] = $total;
                $old_date = isset($value['repayment_date'])?$value['repayment_date']:null;
                $total = $total - (isset($value['principal'])?$value['principal']:0);

                $schedule['total_payment'] += $value['total_payment'];
                $xirr_dates[] = isset($value['repayment_date'])?$value['repayment_date']:null;
                $xirr_value[] = $value['total_payment'];
            }

            $schedule['XIRR'] = 0;//200306 close
        }
        $schedule['list'] = $list;
        return $schedule;
    }

    public function get_full_amortization_table($target = []){
        $list = [];
        $transactions = $this->CI->transaction_model->get_many_by([
            'target_id' => $target->id,
            'status' => [1, 2]
        ]);
        if ($transactions) {
            foreach ($transactions as $key => $value) {
                $date = $value->limit_date;
                if($value->limit_date == null){
                    $ym 		= date('Y-m',strtotime($date));
                    $date 		= date('Y-m-',strtotime($ym.' + 1 month')).REPAYMENT_DAY;
                    if($i==1 && $odate > date('Y-m-',strtotime($odate)).REPAYMENT_DAY){
                        $date 		= date('Y-m-',strtotime($date.' + 1 month')).REPAYMENT_DAY;
                    }

                    if(!isset($list[$fdate])){
                        $list[$fdate] = [];
                    }
                }
                $list[$value->instalment_no] = [
                    'ar_principal' => 0,//11
                    'ar_interest' => 0,//13
                    'ar_fees' => 0,//9
                    'ar_delay_interest' => 0,//93
                    'ar_damage' => 0,//91
                    'r_principal' => 0,//12
                    'r_interest' => 0,//14
                    'r_fees' => 0,//4
                    'r_delayinterest' => 0,//94
                    'r_damage' => 0,//92
                    'subloan_fee' => 0,//5
                    'prepayment_allowance' => 0,//7
                    'prepayment_damage' => 0,//8
                ];
                switch ($value->source) {
                    case SOURCE_AR_INTEREST:
                        echo SOURCE_AR_INTEREST;
                        break;
                    case SOURCE_AR_FEES:
                        echo SOURCE_AR_FEES;
                        break;
                    case SOURCE_AR_PRINCIPAL:
                        echo SOURCE_AR_PRINCIPAL;
                        break;
                    case SOURCE_AR_DELAYINTEREST:
                        echo SOURCE_AR_DELAYINTEREST;
                        break;
                    case SOURCE_AR_DAMAGE:
                        echo SOURCE_AR_DAMAGE;
                        break;
                    case SOURCE_SUBLOAN_FEE://AR
                        echo SOURCE_SUBLOAN_FEE;
                        break;
                    case SOURCE_INTEREST:
                        echo SOURCE_INTEREST;
                        break;
                    case SOURCE_FEES:
                        echo SOURCE_FEES;
                        break;
                    case SOURCE_PRINCIPAL:
                        echo SOURCE_PRINCIPAL;
                        break;
                    case SOURCE_DELAYINTEREST:
                        echo SOURCE_DELAYINTEREST;
                        break;
                    case SOURCE_DAMAGE:
                        echo SOURCE_DAMAGE;
                        break;
                    case SOURCE_PREPAYMENT_ALLOWANCE://AR
                        echo SOURCE_PREPAYMENT_ALLOWANCE;
                        break;
                    case SOURCE_PREPAYMENT_DAMAGE://AR
                        echo SOURCE_PREPAYMENT_DAMAGE;
                        break;
                    default:
                        break;
                }
            }
        }
    }

    public function script_check_bidding()
    {
        $script = 3;
        $count = 0;
        $ids = [];
        $targets = $this->CI->target_model->get_many_by([
            'status' => 3,
            'script_status' => 0
        ]);
        if ($targets && !empty($targets)) {
            foreach ($targets as $key => $value) {
                $ids[] = $value->id;
            }
            $update_rs = $this->CI->target_model->update_many($ids, ['script_status' => $script]);
            if ($update_rs) {
                foreach ($targets as $key => $value) {
                    $check = $this->check_bidding($value);
                    if ($check) {
                        $count++;
                    }
                    $this->CI->target_model->update($value->id, ['script_status' => 0]);
                }
            }
        }
        return $count;
    }

    //審核額度
    public function script_approve_target()
    {
        $this->CI->load->library('loanmanager/product_lib');
        $this->CI->load->library('Certification_lib');
        $targets = $this->CI->target_model->get_many_by([
            'status' => [TARGET_WAITING_APPROVE, TARGET_ORDER_WAITING_VERIFY],
            'script_status' => 0,
        ]);
        $list = [];
        $ids = [];
        $script = 4;
        $count = 0;
        $wait_associates = false;
        $allow_stage_cer = [1, 3];
        if ($targets && !empty($targets)) {
            foreach ($targets as $key => $value) {
                // todo: 只放學生貸進新架構，剩餘的等之後開發好再說
                if (($value->product_id == PRODUCT_ID_STUDENT && $value->sub_product_id == 0) )
                {
                    // 迴圈當下重新確認是否狀態一樣
                    $this_target = $this->CI->target_model->get_by([
                        'id' => $value->id,
                        'status' => [TARGET_WAITING_APPROVE, TARGET_ORDER_WAITING_VERIFY],
                    ]);

                    // 如果沒有這筆資料，就跳過
                    if (!$this_target) {
                        continue;
                    }
                    // $value 取代成重新取得的資料
                    $value = $this_target;

                    $approve_factory = new Approve_factory();
                    $approve_instance = $approve_factory->get_instance_by_model_data($value);
                    if ($approve_instance->approve(FALSE) === TRUE)
                    {
                        $count++;
                    }
                    continue;
                }
                $list[$value->product_id][$value->id] = $value;
                $ids[] = $value->id;
            }

            if (empty($ids))
            {
                return TRUE;
            }
            $rs = $this->CI->target_model->update_many($ids, ['script_status' => $script]);
            if ($rs) {
                $product_list = $this->CI->config->item('product_list');
                $subloan_list = $this->CI->config->item('subloan_list');
                foreach ($list as $product_id => $targets) {
                    foreach ($targets as $target_id => $value) {
                        // 迴圈當下重新確認是否狀態一樣
                        $this_target = $this->CI->target_model->get_by([
                            'id' => $value->id,
                            'status' => [TARGET_WAITING_APPROVE, TARGET_ORDER_WAITING_VERIFY],
                        ]);

                        // 如果沒有這筆資料，就跳過
                        if (!$this_target) {
                            // 因為前面有把這筆資料的 script_status 改成 4，所以這裡要改回 0
                            $this->CI->target_model->update($value->id, ['script_status' => TARGET_SCRIPT_STATUS_NOT_IN_USE]);
                            continue;
                        }

                        // $value 取代成重新取得的資料
                        $value = $this_target;

                    	if(!array_key_exists($value->product_id, $product_list))
                        {
                            $this->CI->target_model->update($value->id, ['script_status' => TARGET_SCRIPT_STATUS_NOT_IN_USE]);
                            continue;
                        }

                        $failedCertificationList = [];
                        $pendingCertificationCount = 0;
                        $stage_cer = 0;
                        $product = $product_list[$value->product_id];
                        $sub_product_id = $value->sub_product_id;
                        if ($this->is_sub_product($product, $sub_product_id)) {
                            $product = $this->trans_sub_product($product, $sub_product_id);
                        }
                        $product_certification = $this->CI->product_lib->get_product_certs_by_product_id($value->product_id, $value->sub_product_id, []);
                        $finish = true;

                        if (($product['check_associates_certs'] ?? FALSE))
                        {
                            // 普匯微企e秒貸歸戶
							// to do : 任務控制程式過件須確認不會有其他非法人產品進來
                            if(isset($product['checkOwner']) && $product['checkOwner'] === true){
                                $this->CI->load->model('loan/target_associate_model');
                                if($value->sub_status == TARGET_SUBSTATUS_WAITING_ASSOCIATES) {
                                    $this->CI->certification_lib->check_associates($target_id);
                                    $cer_userList = $this->get_associates_user_data($value->id, 'all', [0, 1], false);
                                    $wait_associates = true;
                                    $finish = true;
                                    foreach ($cer_userList as $listKey => $listValue) {
                                        if($listValue->status == 0 || $listValue->user_id == null) {
                                            $finish = false;
                                        }
                                    }

                                    //待認證清待加入法人本身
                                    if($product['identity'] == 3){
                                        $cer_userList[] = (object)[
                                            'target_id' => $value->id,
                                            'product_id' => $value->product_id,
                                            'sub_product_id' => $value->sub_product_id,
                                            'identity' => 3,
                                            'user_id' => $value->user_id,
                                        ];
                                    }
                                }else{
                                    $wait_associates = false;
                                }
                            }
                        }

                        $subloan_status = preg_match('/' . $subloan_list . '/', $value->target_no) ? true : false;

                        $company = $value->product_id >= 1000 ? 1 : 0;
                        $certifications = $this->CI->certification_lib->get_status($value->user_id, BORROWER, $company, false, $value, FALSE, TRUE);

                        $finish_stage_cer = [];
                        $cer = [];
                        $cer_success_id = []; // 存已成功的徵信項 certification_id
                        $matchBrookesia = false;        // 反詐欺狀態
                        $second_instance_check = false; // 進待二審

                        foreach ($certifications as $key => $certification) {
                            if ($finish && in_array($certification['id'], $product_certification)) {
                                $cert_helper = Certification_factory::get_instance_by_id($certification['certification_id']);
                                if ($certification['user_status'] != '1' ||
                                    (isset($cert_helper) && ($cert_helper->is_succeed() === FALSE || $cert_helper->is_expired() === TRUE))
                                )
                                {

                                    // 還款力計算若驗證不通過，會進入待二審
                                    if ($certification['id'] == CERTIFICATION_REPAYMENT_CAPACITY)
                                    {
                                        $second_instance_check = TRUE;
                                    }

                                    if (in_array($value->product_id, $allow_stage_cer) && in_array($certification['id'], [CERTIFICATION_DIPLOMA]) && ($sub_product_id == 0 || $sub_product_id == STAGE_CER_TARGET) && !$subloan_status) {
                                        $finish_stage_cer[] = $certification['id'];
                                    } else {
                                        // 普匯微企e秒貸對保不驗證
                                        // 加入產品非必要項目不驗證結構
                                        if(!isset($product_list[$value->product_id]['option_certifications']) || !in_array($certification['id'],$product_list[$value->product_id]['option_certifications'])){

                                            $param = ['status' => TARGET_WAITING_APPROVE, 'sub_status' => TARGET_SUBSTATUS_NORNAL];
                                            $res = $this->CI->target_model->update_by([
                                                'id' => $value->id,
                                                'status' => TARGET_WAITING_APPROVE
                                            ], $param);
                                            if ($res)
                                            {
                                                $this->insert_change_log($value->id, $param);
                                            }

                                            $finish = false;
                                        }
                                    }
                                }

                                // 社交認證過期，卡案件狀態為待驗證
                                if ($certification['id'] == CERTIFICATION_SOCIAL && $certification['expire_time'] < time())
                                {
                                    $finish = FALSE;
                                    $param = ['status' => TARGET_WAITING_APPROVE, 'sub_status' => TARGET_SUBSTATUS_NORNAL];
                                    $this->CI->target_model->update($value->id, $param);
                                    $this->insert_change_log($target_id, $param);
                                    $cert_helper = Certification_factory::get_instance_by_id($certification['certification_id']);
                                    if ( ! isset($cert_helper))
                                    {
                                        break;
                                    }
                                    $cert_helper->set_failure(TRUE, \CertificationResult\SocialCertificationResult::$EXPIRED_MESSAGE);
                                    break;
                                }

                                // 工作認證有專業技能證書進待二審
                                if($certification['id'] == 10 && isset($certification['content'])){
                                    if(isset($certification['content']['license_image']) || isset($certification['content']['pro_certificate_image']) || isset($certification['content']['game_work_image'])){
                                        $second_instance_check = true;
                                    }
                                }
                                if ($certification['user_status'] == CERTIFICATION_STATUS_SUCCEED)
                                {
                                    $cer[] = $certification['certification_id'];
                                    $cer_success_id[] = $certification['id'];
                                }
                            }
                        }

                        // 檢查系統自動過件，必要的徵信項
                        $required_certification = array_diff($product_certification, $product['option_certifications']);
                        if ( ! empty(array_diff($required_certification, $cer_success_id)))
                        {
                            $finish = FALSE;
                        }

                        // 法人產品需確認自然關係人認證徵信是否完成
                        if ($finish && ($product['check_associates_certs'] ?? FALSE))
                        {
                            $finish = $this->CI->certification_lib->associate_certs_are_succeed($value);
                        }

                        if ($finish && $wait_associates) {
                            if ($value->status == TARGET_WAITING_APPROVE && $value->sub_status == TARGET_SUBSTATUS_WAITING_ASSOCIATES
                                && !$this->get_associates_user_data($value->id, 'all', [0], false)) {
                                $this->CI->target_model->update($value->id, ['sub_status' => 0]);
                            }
                        }

                        $targetData = json_decode($value->target_data);
                        foreach ($product['targetData'] as $targetDataKey => $targetDataValue) {
                            if (empty($targetData->$targetDataKey) && isset($targetDataValue[3]) && !$targetDataValue[3]) {
                                $finish = false;
                                break;
                            }
                        }

                        if (count($finish_stage_cer) != 0) {
                            $stage_cer = $this->stageCerLevel($finish_stage_cer);
                            !$stage_cer ? $finish = false : '';
                        }

                        $certifications_status_by_id = array_column($certifications, 'user_status', 'id');
                        if ($value->product_id == PRODUCT_ID_STUDENT &&
                            isset($certifications_status_by_id[CERTIFICATION_IDENTITY]) &&
                            $certifications_status_by_id[CERTIFICATION_IDENTITY] == CERTIFICATION_STATUS_SUCCEED &&
                            isset($certifications_status_by_id[CERTIFICATION_STUDENT]) &&
                            $certifications_status_by_id[CERTIFICATION_STUDENT] == CERTIFICATION_STATUS_SUCCEED)
                        {
                            // 1. 申請學生貸，且實名認證、學生認證已審核通過
                            // 2. 通過反詐欺爬蟲（未命中、未被封鎖）
                            // 符合者，將金融驗證轉為待驗證
                            $this->CI->load->library('anti_fraud_lib');
                            $anti_fraud_response = $this->CI->anti_fraud_lib->get_by_user_id($value->user_id);
                            if ($anti_fraud_response['status'] == 200 && empty($anti_fraud_response['response']['results']))
                            {
                                $this->CI->load->model('user/user_bankaccount_model');
                                $bank_account = $this->CI->user_bankaccount_model->get_by([
                                    'status' => VIRTUAL_ACCOUNT_STATUS_AVAILABLE,
                                    'investor' => USER_BORROWER,
                                    'user_id' => $value->user_id
                                ]);
                                if (isset($bank_account->verify) && $bank_account->verify == 0)
                                {
                                    $cert_debit_card = $this->CI->user_certification_model->get($bank_account->user_certification_id);
                                    if ( ! empty($cert_debit_card))
                                    {
                                        $new_content = json_encode(array_merge(
                                            json_decode($cert_debit_card->content, TRUE),
                                            ['in_advance' => TRUE]
                                        ));
                                        $this->CI->user_certification_model->update($cert_debit_card->id, ['content' => $new_content]);
                                        $this->CI->user_bankaccount_model->update($bank_account->id, ['verify' => 2]);
                                    }
                                }
                            }
                        }

                        if ($finish) {
                            // Re-check ID card info in case user's ID card changed.
                            $check_id_card = FALSE;
                            if ($value->certificate_status == TARGET_CERTIFICATE_SUBMITTED)
                            {
                                $this->CI->load->model('user/user_certification_model');
                                $identity_cert = $this->CI->user_certification_model->get_by([
                                    'investor' => BORROWER,
                                    'status' => CERTIFICATION_STATUS_SUCCEED,
                                    'user_id' => $value->user_id,
                                    'certification_id' => CERTIFICATION_IDENTITY
                                ]);
                                if ($identity_cert)
                                {
                                    // Avoid checking for the same target too many times.
                                    $this->CI->load->model('log/log_integration_model');
                                    $api_verify_log = $this->CI->log_integration_model->order_by('created_at', 'DESC')->get_by([
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
                                    else
                                    {
                                        $check_id_card = TRUE;
                                    }

                                    if ($check_id_card)
                                    {
                                        $identity_content = json_decode($identity_cert->content, TRUE);
                                        $remark = json_decode($identity_cert->remark, TRUE);
                                        $err_msg = $remark['error'] ?? '';
                                        $result = $this->CI->certification_lib->verify_id_card_info(
                                            $identity_cert->id, $identity_content, $err_msg, $remark['OCR'] ?? []
                                        );

                                        // Update user_certification.
                                        $to_update = ['content' => json_encode($identity_content, JSON_INVALID_UTF8_IGNORE)];
                                        if ($err_msg)
                                        {
                                            $remark['error'] = $err_msg;
                                            $to_update['remark'] = json_encode($remark, JSON_INVALID_UTF8_IGNORE);
                                        }
                                        $this->CI->user_certification_model->update($identity_cert->id, $to_update);

                                        if ($result[0] && $result[1])  // Existed id card info is wrong.
                                        {
                                            $this->CI->load->model('log/log_usercertification_model');
                                            $this->CI->log_usercertification_model->insert([
                                                'user_certification_id'	=> $identity_cert->id,
                                                'status'				=> CERTIFICATION_STATUS_FAILED,
                                                'change_admin'			=> SYSTEM_ADMIN_ID,
                                            ]);
                                            $cert_helper = \Certification\Certification_factory::get_instance_by_model_resource($identity_cert);
                                            if (isset($cert_helper))
                                            {
                                                $rs = $cert_helper->set_failure(TRUE, IdentityCertificationResult::$RIS_CHECK_FAILED_MESSAGE);
                                            }
                                            else
                                            {
                                                $rs = $this->CI->certification_lib->set_failed($identity_cert->id, IdentityCertificationResult::$RIS_CHECK_FAILED_MESSAGE);
                                            }
                                            if ($rs === TRUE)
                                            {
                                                $this->CI->user_certification_model->update($identity_cert->id, [
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
                                            $this->CI->load->model('log/log_usercertification_model');
                                            $this->CI->log_usercertification_model->insert([
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
                                                $this->CI->user_certification_model->update($identity_cert->id, [
                                                    'certificate_status' => CERTIFICATION_CERTIFICATE_STATUS_SENT,
                                                    'remark' => json_encode($remark, JSON_INVALID_UTF8_IGNORE | JSON_UNESCAPED_UNICODE),
                                                ]);
                                            }
                                            else
                                            {
                                                log_message('error', "實名認證 user_certification {$identity_cert->id} 轉人工失敗");
                                            }

                                            goto UNFINISHED;
                                        }
                                    }
                                }
                            }

                            // 判斷是否符合產品申貸年齡限制
                            $this->CI->load->library('loanmanager/product_lib');
                            if ($this->CI->product_lib->need_chk_allow_age($value->product_id) === TRUE)
                            {
                                if ($company)
                                { // 若為法人戶，改取負責人的生日來計算
                                    $this->CI->load->library('judicialperson_lib');
                                    $user_info = $this->CI->judicialperson_lib->getNaturalPerson($value->user_id);
                                }
                                else
                                {
                                    $user_info = $this->CI->user_model->get($value->user_id);
                                }
                                $age = get_age($user_info->birthday);
                                if ($this->CI->product_lib->is_age_available($age, $value->product_id, $value->sub_product_id) === FALSE)
                                {
                                    $this->target_verify_failed($value, 0, '身份非平台服務範圍');
                                    $this->CI->target_model->update($value->id, ['script_status' => 0]);
                                    return false;
                                }
                            }

                            // 檢查黑名單結果，是否需要處置
                            $this->CI->load->library('brookesia/black_list_lib');
                            $is_user_blocked = $this->CI->black_list_lib->check_user($value->user_id, CHECK_APPLY_PRODUCT);
                            $is_user_second_instance = $this->CI->black_list_lib->check_user($value->user_id, CHECK_SECOND_INSTANCE);

                            // 確認黑名單結果是否需轉二審(子系統回應異常、禁止申貸、轉二審)
                            if (empty($is_user_blocked) || empty($is_user_second_instance))
                            {
                                $this->CI->black_list_lib->add_block_log(['userId' => $value->user_id]);
                                $matchBrookesia = TRUE;
                            }
                            else if($is_user_blocked['isUserBlocked'])
                            {
                                $this->CI->black_list_lib->add_block_log($is_user_blocked);
                                $matchBrookesia = TRUE;
                            }
                            else if($is_user_second_instance['isUserSecondInstance'])
                            {
                                $this->CI->black_list_lib->add_block_log($is_user_second_instance);
                                $matchBrookesia = TRUE;
                            }

                            !is_object($targetData) ? $targetData = (object)($targetData) : $targetData;
                            $targetData->certification_id = $cer;
                            $count++;

                            // 所有徵信項完成後，確認是否需要觸發反詐欺
                            $this->CI->load->library('brookesia/brookesia_lib');
                            $user_checked = $this->CI->brookesia_lib->is_user_checked($value->user_id, $target_id);

                            if (!$user_checked)
                            {
                                $this->CI->brookesia_lib->userCheckAllRules($value->user_id, $target_id);
                            }
                            else
                            {
                                // 判斷是否為普匯微企e秒貸
                                if(in_array($value->product_id, $this->CI->config->item('externalCooperation'))){
                                    $param = [
                                        'target_data' => json_encode($targetData),
                                        'status' => TARGET_WAITING_VERIFY,
                                    ];

                                    //其一userId match反詐欺>轉待二審
                                    $matchBrookesia ? $param['sub_status'] = TARGET_SUBSTATUS_SECOND_INSTANCE : '';

                                    //信保額度判斷
                                    //to do : 過件邏輯待串
                                    $this->CI->load->library('verify/data_verify_lib');
                                    $allowProductStatus = $this->CI->data_verify_lib->productVerify($product, $value);
                                    if($allowProductStatus['status_code']){
                                        //todo信保未過邏輯
                                        if($allowProductStatus['status_code'] == 2){
                                            $param['status'] = TARGET_FAIL;
                                            $param['sub_status'] = TARGET_SUBSTATUS_NORNAL;
                                            $this->approve_target_fail($value->user_id, $value);
                                        }elseif($allowProductStatus['status_code'] == 3){
                                            $param['sub_status'] = TARGET_SUBSTATUS_SECOND_INSTANCE;
                                        }
                                    }

                                    $this->CI->target_model->update($value->id, $param);
                                    $creditSheet = CreditSheetFactory::getInstance($value->id);
                                    $creditSheet->approve($creditSheet::CREDIT_REVIEW_LEVEL_SYSTEM, '需二審查核');
                                }else{
                                    if ( ! $company && $value->certificate_status != TARGET_CERTIFICATE_SUBMITTED)
                                    {
                                        $this->CI->target_model->update($value->id, ['script_status' => TARGET_SCRIPT_STATUS_NOT_IN_USE]);
                                        continue;
                                    }
                                    $this->approve_target($value, false, false, $targetData, $stage_cer, $subloan_status, $matchBrookesia, $second_instance_check);
                                }
                            }

                        } else {
                            UNFINISHED:
                            //自動取消
                            $limit_date = date('Y-m-d', strtotime('-' . TARGET_APPROVE_LIMIT . ' days'));
                            $create_date = date('Y-m-d', $value->created_at);

                            if ($limit_date > $create_date) {
                                $count++;
                                $param = [
                                    'status' => TARGET_FAIL,
                                    'sub_status' => TARGET_SUBSTATUS_NORNAL,
                                    'remark' => $value->remark . '系統自動取消'
                                ];
                                $this->CI->target_model->update($value->id, $param);
                                $this->insert_change_log($target_id, $param);
                                $this->CI->notification_lib->approve_cancel($value->user_id);
                                if ($value->order_id != 0) {
                                    $this->CI->load->model('transaction/order_model');
                                    $this->CI->order_model->update($value->order_id, ['status' => 0]);
                                }
                            }
                        }

                        $this->CI->target_model->update($value->id, ['script_status' => 0]);
                    }
                }
                return $count;
            }
        }
        return false;
    }

    public function script_chk_signing()
    {
        $this->CI->load->model('loan/credit_model');
        $target = $this->CI->credit_model->get_expired_signing_list();
        $target_id = array_column($target, 'target_id');
        if (empty($target_id))
        {
            return 0;
        }

        $this->CI->target_model->update_by(['id' => $target_id], ['script_status' => 17]);
        $count = 0;
        foreach ($target as $value)
        {
            $param = [
                'status' => TARGET_FAIL,
                'sub_status' => TARGET_SUBSTATUS_NORNAL,
                'remark' => $value['remark'] . '系統自動取消'
            ];
            $this->CI->target_model->update($value['id'], $param);
            $this->insert_change_log($value['id'], $param);
            $this->CI->notification_lib->expired_cancel($value['user_id']);

            $count++;
        }

        $this->CI->target_model->update_by(['id' => $target_id], ['script_status' => 0]);

        return $count;
    }

    //使用者觸發架上案件智能投資
    public function aiBiddingAllTarget($userId){
        $allow_aiBidding_product = $this->CI->config->item('allow_aiBidding_product');
        $targets = $this->CI->target_model->order_by('expire_time','asc')->get_many_by([
            'product_id' => $allow_aiBidding_product,
            'status' => 3,
            'script_status' => 0
        ]);
        foreach($targets as $key => $value){
            $this->aiBiddingTarget($value, $userId);
        }
    }

    //案件觸發智能投資用戶
    public function aiBiddingTarget($target, $userId = false){
        //取得智能投資設定有效期用戶清單
        $this->CI->load->model('loan/batch_model');
        $param = [
            'type' => 0,
            'status' => 1,
            'expire_time >=' => time(),
        ];
        $userId ? $param['user_id'] = $userId : '';
        $aiBiddingList = $this->CI->batch_model->order_by('expire_time','asc')->get_many_by($param);
        if($aiBiddingList){
            $this->CI->config->load('school_points',TRUE);
            $school_list = $this->CI->config->item('school_points');
            $target->system = null;
            $target->national = null;
            //取得meta資訊
            $user_meta = $this->CI->user_meta_model->get_many_by([
                'user_id'	=> $target->user_id,
                'meta_key'	=> ['school_system','school_name']
            ]);
            if($user_meta){
                foreach ($user_meta as $skey => $svalue) {
                    //取得學制
                    $svalue->meta_key == 'school_system' ? $target->system = $svalue->meta_value : '';

                    //取得案件學歷是否國立
                    if($svalue->meta_key == 'school_name') {
                        foreach ($school_list['school_points'] as $k => $v) {
                            if (trim($svalue->meta_value) == $v['name']) {
                                $target->national= $v['national'];
                                break;
                            }
                        }
                    }
                }
            }

            //取得性別
            $target_user_info = $this->CI->user_model->get($target->user_id);
            $target->sex = $target_user_info->sex;

            $this->aiBidding($target, $aiBiddingList);
        }
    }

    public function aiBidding($target = [], $aiBiddingList = []){
        $allow_aiBidding_product = $this->CI->config->item('allow_aiBidding_product');
        if(!in_array($target->product_id, $allow_aiBidding_product)){
            return false;
        }

        //案件剩餘可投標餘額
        $targetAllowAmount = $target->loan_amount - $target->invested;
        if($targetAllowAmount < 1000){
            return false;
        }

        //取得該案投資清單
        $investmentList = [];
        $this->CI->load->model('loan/investment_model');
        $investments = $this->CI->investment_model->get_many_by([
            'target_id' => $target->id,
        ]);
        foreach($investments as $key => $value){
            //曾下標的投資人
            $investmentList[] = $value->user_id;
        }

        if(count($aiBiddingList) == 1){
            if(in_array($aiBiddingList[0]->user_id, $investmentList)){
                return false;
            }
        }

        //智能投資名單
        foreach($aiBiddingList as $key => $value){
            $aiBiddingUserList[] = $value->user_id;
        }

        //取得各智能投資的用戶今日投資數字
        $today = strtotime(date("Y-m-d", time()));
        $todayInvestments = [];
        $getTodayInvestments = $this->CI->investment_model->get_many_by([
            'user_id' => $aiBiddingUserList,
            'status NOT' => [8, 9],
            'created_at >=' => $today,
        ]);
        foreach($getTodayInvestments as $key => $value){
            //如已結標則以結標金額
            $amount = $value->status >= 2 ? $value->loan_amount : $value->amount;

            //統計投資人今日投資額
            !isset($todayInvestments[$value->user_id]) ? $todayInvestments[$value->user_id] = 0 : '';
            $todayInvestments[$value->user_id] += $amount;
        }

        foreach($aiBiddingList as $key => $value){
            if($targetAllowAmount >= 1000){
                $content = json_decode($value->filter);
                $biddingAmount = 0;
                $targetAmount = $content->target_amount * 1000;
                $dailyAmount = $content->daily_amount * 1000;
                $cancel = false;

                //判斷案件是否符合條件
                $filter = ['product_id', 'credit_level', 'sex', 'system', 'national'];
                foreach($filter as $fkey => $fvalue){
                    if($content->$fvalue != 'all') {
                        $ids = explode(",", $content->$fvalue);
                        //不符篩選條件
                        if (!in_array($target->$fvalue, $ids)) {
                            $cancel = true;
                            continue;
                        }
                    }
                }
                if($content->interest_rate_s > intval($target->interest_rate)
                    || $content->interest_rate_e < intval($target->interest_rate)
                    || $content->instalment_s > intval($target->instalment)
                    || $content->instalment_e < intval($target->instalment)
                ){
                    continue;
                }

                //排除曾下標的投資人與排除不符篩選標準
                if(!in_array($value->user_id, $investmentList) && !$cancel){
                    !isset($todayInvestments[$value->user_id]) ? $todayInvestments[$value->user_id] = 0 : '';
                    //有設定每日投資額度
                    if($dailyAmount != 0){
                        //計算今日可投標餘額 = 投資人設定投標金額 - 今日已投標金額
                        $todayAllowAmounts = $dailyAmount - $todayInvestments[$value->user_id];
                        if($todayAllowAmounts >= 1000){//餘額滿足一千底標
                            //有設定每案投資額度
                            if($targetAmount != 0){
                                //允許投標金額 = 每案投資額度 >= 今日投標餘額 則 以今日投標餘額
                                $allowBiddingAmount = $targetAmount >= $todayAllowAmounts ? $todayAllowAmounts : $targetAmount;
                                //投標金額 = 允許投標金額 >= 案件可投標金額 則 以案件可投標金額
                                $biddingAmount = $allowBiddingAmount >= $targetAllowAmount ? $targetAllowAmount : $allowBiddingAmount;
                                //->以每案投資額度投標
                            }else{
                                //->今日可投額度來投標至滿標
                                $biddingAmount = $targetAllowAmount >= $todayAllowAmounts ? $todayAllowAmounts : $targetAllowAmount;
                            }
                        }
                    }else{
                        //有設定每案投資額度
                        if($targetAmount != 0){
                            //投標金額 = 每案投資額度 >= 案件可投標金額 則 以案件可投標金額
                            $biddingAmount = $targetAmount >= $targetAllowAmount ? $targetAllowAmount : $targetAmount;
                            //->以每案投資額度投標
                        }else{
                            //全額投至滿標
                            $biddingAmount = $targetAllowAmount;
                            //->有多少投多少
                        }
                    }
                    if($biddingAmount >= 1000){
                        //投標
                        $this->CI->load->model('loan/investment_model');
                        $this->CI->investment_model->insert([
                            'target_id' => $target->id,
                            'user_id' => $value->user_id,
                            'amount' => $biddingAmount,
                            'aiBidding' => 1,
                        ]);
                    }
                }
            }
        }
    }

    private function get_target_no($product_id = 0)
    {
        $product_list = $this->CI->config->item('product_list');
        $alias = $product_list[$product_id]['alias'];
        $code = $alias . date('Ymd') . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(1, 9);
        $result = $this->CI->target_model->get_by('target_no', $code);
        if ($result) {
            return $this->get_target_no($product_id);
        } else {
            return $code;
        }
    }

    public function insert_change_log($target_id, $update_param, $user_id = 0, $admin_id = SYSTEM_ADMIN_ID)
    {
        if ($target_id) {
            $this->CI->load->model('log/Log_targetschange_model');
            $param = [
                'target_id' => $target_id,
                'change_user' => $user_id,
                'change_admin' => $admin_id
            ];
            $fields = ['interest_rate', 'delay', 'status', 'loan_status', 'sub_status', 'script_status'];
            foreach ($fields as $field) {
                if (isset($update_param[$field])) {
                    $param[$field] = $update_param[$field];
                }
            }
            $rs = $this->CI->Log_targetschange_model->insert($param);
            return $rs;
        }
        return false;
    }

    public function insert_investment_change_log($investment_id, $update_param, $user_id = 0, $admin_id = 0)
    {
        if ($investment_id) {
            $this->CI->load->model('log/Log_investmentschange_model');
            $param = [
                'investment_id' => $investment_id,
                'change_user' => $user_id,
                'change_admin' => $admin_id
            ];
            $fields = ['status', 'transfer_status'];
            foreach ($fields as $field) {
                if (isset($update_param[$field])) {
                    $param[$field] = $update_param[$field];
                }
            }
            $rs = $this->CI->Log_investmentschange_model->insert($param);
            return $rs;
        }
        return false;
    }

    public function is_sub_product($product, $sub_product_id)
    {
        $sub_product_list = $this->CI->config->item('sub_product_list');
        return isset($sub_product_list[$sub_product_id]['identity'][$product['identity']]) && in_array($sub_product_id, $product['sub_product']);
    }

    /**
     * @param $product
     * @param $sub_product_id
     * @return array
     */
    private function trans_sub_product($product, $sub_product_id): array
    {
        $sub_product_list = $this->CI->config->item('sub_product_list');
        $sub_product_data = $sub_product_list[$sub_product_id]['identity'][$product['identity']];
        return $this->sub_product_profile($product, $sub_product_data);
    }

    /**
     * @param $product
     * @param $sub_product
     * @return array
     */
    private function sub_product_profile($product, $sub_product): array
    {
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
            'option_certifications' => $sub_product['option_certifications'] ?? [],
            'backend_option_certifications' => $sub_product['backend_option_certifications'] ?? [],
            'instalment' => $sub_product['instalment'],
            'repayment' => $sub_product['repayment'],
            'targetData' => $sub_product['targetData'],
            'secondInstance' => $sub_product['secondInstance'] ?? FALSE,
            'dealer' => $sub_product['dealer'],
            'multi_target' => $sub_product['multi_target'],
            'checkOwner' => $product['checkOwner'] ?? FALSE,
            'status' => $sub_product['status'],
            'need_upload_images' => $sub_product['need_upload_images'] ?? null,
            'available_company_categories' => $sub_product['available_company_categories'] ?? null,
            'default_reason' => $sub_product['default_reason'] ?? '',
            'check_associates_certs' => $sub_product['check_associates_certs'] ?? FALSE
        );
    }

    public function stageCerLevel($cer){
        asort($cer);
        $implode = implode('', $cer);
        if ($implode == '89') {
            $stage_cer = 2;
        } elseif ($implode == '9') {
            $stage_cer = 3;
        } elseif ($implode == '8') {
            $stage_cer = 4;
        }else{
            $stage_cer = false;
        }
        return $stage_cer;
    }

    /**
     * @param $user_id : 使用者id
     * @param array $status : 案件保證人狀態
     * @return mixed
     */
    public function get_associates($user_id, array $status = [ASSOCIATES_STATUS_WAITTING_APPROVE, ASSOCIATES_STATUS_APPROVED])
    {
        $this->CI->load->model('loan/target_associate_model');
        $params = [
            "user_id" => $user_id,
            "status" => $status,
        ];
        return $this->CI->target_associate_model->get_many_by($params);
    }

    /**
     * 檢查是否為企金案件的保證人
     * @param $user_id : 使用者id
     * @return bool
     */
    public function is_associate($user_id): bool
    {
        return empty($this->get_associates($user_id, [
            ASSOCIATES_STATUS_WAITTING_APPROVE,
            ASSOCIATES_STATUS_APPROVED,
            ASSOCIATES_STATUS_CERTIFICATION_CHECKED
        ]));
    }

    public function get_associates_target_list($user_id, $target_id = false ,$self = false, $status = [TARGET_WAITING_APPROVE, TARGET_WAITING_SIGNING, TARGET_WAITING_VERIFY, TARGET_BANK_VERIFY, TARGET_BANK_GUARANTEE]){
        $targets = $target_id ? '' : [];
        $get_associates_list = $this->get_associates($user_id);
        if($get_associates_list){
            foreach ($get_associates_list as $key => $value) {
                if(!$target_id || $target_id == $value->target_id){
                    $param = [
                        'id' => $value->target_id,
                    ];
                    $status != false ? $param['status'] = $status : '' ;
                    $temp = $this->CI->target_model->get_by($param);
                    if($temp && ($temp->user_id != $user_id && $self == true || $self == false)){
                        $temp = !isset($temp) ? new stdClass() : $temp;
                        $temp->associate['owner'] = ($value->character == 1 ? true : false);
                        $temp->associate['identity'] = intval($value->identity);
                        $temp->associate['status'] = intval($value->status);
                        $target_id ? $targets = $temp : $targets[] = $temp;
                    }
                }
            }
        }
        return  $targets;
    }

    public function get_associates_user_data($target_id, $owner = false, $status = [0 ,1], $userList = false, $identity = false){
        $this->CI->load->model('loan/target_associate_model');
        $params = [
            "target_id" => $target_id,
            "status" => $status,
//            "character" => ($owner ? 1 : 2),
        ];
//        $identity ? $params['identity'] = [1,2] : '';
//        if(!is_bool($owner) && $owner == 'all'){
//            unset($params['character']);
//        }

        $rs = $this->CI->target_associate_model->get_many_by($params);
        if($userList){
            $user_list = [];
            foreach ($rs as $key => $value){
                $user_list[] = $value->user_id;
                if($value->user_id == null){
                    $user_list = false;
                    break;
                }
            }
            $rs = $user_list;
        }

        return $rs;
    }

    public function get_associates_data($target_id, $character = false, $status = [0, 1]){
        $this->CI->load->model('loan/target_associate_model');

        $params = [
            "target_id" => $target_id,
            "status" => $status
        ];
        if($character != 'all')
        {
            $params['character'] = (int)$character;
        }

        return $this->CI->target_associate_model->as_array()->get_many_by($params);
    }

    public function get_associates_list($target_id, $status = [0, 1], $product, $self_user_id, $self_certification)
    {
        $this->CI->load->model('loan/target_associate_model');
        $this->CI->load->library('loanmanager/product_lib');

        $get_associates_list = $this->CI->target_associate_model->get_many_by([
            'target_id' => $target_id,
            'status <=' => 1,
        ]);
        $target = [];
        $chargeOfRegistration = false;
        if ($get_associates_list) {
            $param = [
                'id' => $target_id,
            ];
            $status != false ? $param['status'] = $status : '';
            $target = $this->CI->target_model->get_by($param);
            if ($target) {
                $temp = [
                    'remaining_guarantor' => 2,
                    'addspouse' => false,
                    'addrealcharacter' => false,
                    'character' => '',
                    'owner' => '',
                    'agitate' => [],
                ];
                foreach ($get_associates_list as $key => $value) {
                    $self = $self_user_id == $value->user_id;
                    $certification = $self ? $self_certification : [];
                    $certification_id_list = array_column($certification, 'certification_id');
                    $user_id = $self ? $self_user_id : '';
                    $temp['character'] == '' && $self ? $temp['character'] = $value->character : '' ;
                    if(is_null($value->user_id)){
                        $content = json_decode($value->content);
                        $name = $content->name;
                        $id_number = $content->id_number;
                        $phone = $content->phone;
                    }else{
                        $user_info = $this->CI->user_model->get($value->user_id);
                        $user_id = $user_info->id;
                        $name = $user_info->name;
                        $id_number = $user_info->id_number;
                        $phone = $user_info->phone;
                        $certification_list = $this->CI->certification_lib->get_status($value->user_id, $this->CI->user_info->investor, $this->CI->user_info->company);
                        foreach ($certification_list as $ckey => $cvalue) {
                            if (in_array($ckey, $product['certifications']) && $ckey <= 1000 &&
                                ! in_array($cvalue['certification_id'], $certification_id_list))
                            {
                                $cvalue['optional'] = false;
                                $certification[] = $cvalue;
                                $certification_id_list[] = $cvalue['certification_id'];
                            }
                        }
                    }
                    if($this->CI->user_info->company == 1){
                        if($value->character == 0 || $value->character == 1){
                            $this->CI->load->library('Certification_lib');
                            $user_certification	= $this->CI->certification_lib->get_certification_info($value->user_id,1,0);
                            if($user_certification){
                                $temp['addspouse'] = isset($user_certification->content['SpouseName']) && $user_certification->content['SpouseName'] != '';
                                $temp['addspouse'] = isset($user_certification->content['hasSpouse']) && $user_certification->content['hasSpouse'] != '' ? $user_certification->content['hasSpouse'] : false;
                            }
                        }

                        if($value->character == 0){
                            $chargeOfRegistration = true;
                            $temp['addrealcharacter'] = $chargeOfRegistration;
                        }elseif($value->character == 2){
                            // 實際負責人為配偶時
                            if($value->relationship == 0){
                                $temp['addspouse'] = false;
                            }
                            $temp['addrealcharacter'] = false;
                        }elseif($value->character == 3){
                            $temp['addspouse'] = false;
                        }
                    }
                    if (isset($user_info))
                    {
                        $this->CI->load->helper('user_meta_helper');
                        $email = get_email_to($user_info, BORROWER);
                    }
                    if (empty($email))
                    {
                        $associate_content = json_decode($value->content, TRUE);
                        if (json_last_error() !== JSON_ERROR_NONE)
                        {
                            $email = NULL;
                        }
                        else
                        {
                            $email = ! empty($associate_content['mail']) ? $associate_content['mail'] : NULL;
                        }
                    }

                    $data = [
                        'user_id' => $user_id,
                        'name' => $name,
                        'id_number' => $id_number,
                        'phone' => $phone,
                        'identity' => intval($value->identity),
                        'status' => intval($value->status),
                        'guarantor' => ($value->guarantor == 1),
                        'self' => $self,
                        'certification' => $certification,
                        'relationship' => $value->relationship ?? NULL,
                        'email' => $email
                    ];
                    $guarantor_type = [
                       2 => 'A',
                       3 => 'B',
                       4 => 'C',
                       5 => 'C'
                    ];
                    $value->is_applicant == 0 ? $data['guarantor_type'] = $guarantor_type[$value->character] : '';
                    if($value->character == 0 || $value->character == 1){
                        $temp['owner'] = $data;
                    }else{
                        $temp['agitate'][] = $data;
                        if($value->guarantor == 1){
                            $temp['remaining_guarantor']--;
                        }
                    }
                }
                // 如果需要添加實際負責人與配偶，但未添加則加保證人人數設為0等待際負責人與配偶更新完成
                if($temp['addspouse'] == true || $temp['addrealcharacter'] == true){
                    $temp['remaining_guarantor'] = 0;
                }
                $target->associate = $temp;
            }
        }
        return $target;
    }

    private function signContract($target, $investmentUserId, $total_payment ,$loan_amount){
        $contract_type = 'lend';
        $contract_data =  [$investmentUserId, $target->user_id, $loan_amount, $target->interest_rate, $total_payment];
        if($target->product_id == PRODUCT_FOREX_CAR_VEHICLE && $target->sub_product_id == 3){
            $targetData = json_decode($target->target_data);
            $this->CI->load->model('user/judicial_person_model');
            $companyData = $this->CI->judicial_person_model->get_by(['company_user_id' => $target->user_id]);
            $userData = $this->CI->user_model->get($companyData->user_id);
            $investmentData = $this->CI->user_model->get($investmentUserId);
            $contract_year = date('Y') - 1911;
            $contract_month = date('m');
            $contract_day = date('d');
            $contract_type = 'lend_FEV';
            $contract_data = [ $investmentUserId, $target->user_id, $loan_amount, $target->interest_rate, $contract_year, $contract_month, $contract_day, $targetData->vin, $investmentData->name, $investmentData->id_number, $investmentData->address, $companyData->company, $userData->name, $companyData->tax_id, $companyData->cooperation_address];
        }
        return $this->CI->contract_lib->sign_contract($contract_type, $contract_data);
    }

    public function isLegalCollection($legal_collection_at) {
        $checkDate = new DateTime("1911-01-01");
        $legal_collection = 0;
        try {
            $legal_collection_date = new DateTime($legal_collection_at);
            $legal_collection = $legal_collection_date > $checkDate ? 1 : 0;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
        return $legal_collection;
    }

    /**
     * 取得第一期還款日
     * @param $loanDate: 借貸日期
     * @param $formatId: 合約格式編號
     * @return string
     */
    public function getFirstPaymentDate($loanDate, $formatId): string {
        $increasedMonth = 0;
        $day = date('d', strtotime($loanDate));
        if($day === false)
            return $loanDate;

        switch ($formatId) {
            // 借貸契約
            case 1:
                if (intval($day) <= 10)
                    $increasedMonth = 1;
                else
                    $increasedMonth = 2;

                break;
        }
        return date('Y-m-10', strtotime("+".$increasedMonth." months",
            strtotime($loanDate)));
    }

    /**
     * 取得最後一期還款日
     * @param $loanDate: 借貸日期
     * @param $formatId: 合約格式編號
     * @param $instalment: 借款期數
     * @return string
     */
    public function getLastPaymentDate($loanDate, $formatId, $instalment): string {
        $date = "";
        $firstPaymentDate = $this->getFirstPaymentDate($loanDate, $formatId);

        switch ($formatId) {
            // 借貸契約
            case 1:
                $date = date('Y-m-d', strtotime("+".($instalment-1)." months",
                    strtotime($firstPaymentDate)));
                break;
        }
        return $date;
    }

    /**
     * 退案件回待核可
     * @param $target
     * @param int $user_id
     * @param int $admin_id
     * @param int $sys_check
     * @return bool
     */
    public function withdraw_target_to_unapproved($target, $user_id = 0, $admin_id = 0, $sys_check = 1): bool
    {
        if (is_object($target))
        {
            $target = json_decode(json_encode($target), TRUE);
        }

        if ( ! in_array($target['status'], [TARGET_WAITING_SIGNING, TARGET_WAITING_VERIFY, TARGET_ORDER_WAITING_SHIP]))
        {
            log_message('error', '[withdraw_target_to_unapproved]案件非處於允許的狀態。');
            return FALSE;
        }

        $creditSheet = CreditSheetFactory::getInstance($target['id']);
        if($creditSheet)
        {
            $canceled = $creditSheet->cancel();
            if ( ! $canceled)
            {
                log_message('error', '[withdraw_target_to_unapproved]發生不明原因導致授審表取消失敗。');
                return FALSE;
            }
        }

        $status = $target['status'];
        switch ($target['status'])
        {
            // 個金案件：待簽約&待驗證皆要退回待核可
            // 企金案件：待驗證要退回待核可（目前無待簽約階段）
            case TARGET_WAITING_SIGNING:
            case TARGET_WAITING_VERIFY:
                $status = TARGET_WAITING_APPROVE;
                break;
            case TARGET_ORDER_WAITING_SHIP:
                $status = TARGET_ORDER_WAITING_VERIFY;
                break;
        }

        $sub_status = $target['sub_status'];
        switch ($target['sub_status'])
        {
            case TARGET_SUBSTATUS_SECOND_INSTANCE:
            case TARGET_SUBSTATUS_SECOND_INSTANCE_TARGET:
                $sub_status = TARGET_SUBSTATUS_NORNAL;
                break;
        }

        $update_param = ['status' => $status, 'sub_status' => $sub_status, 'certificate_status' => TARGET_CERTIFICATE_SUBMITTED];
        $this->CI->target_model->update_by(
            ['id' => $target['id']],
            $update_param
        );
        $this->insert_change_log($target['id'], [
            'status' => $status,
            'sub_status' => $sub_status,
            'change_user' => $user_id,
            'change_admin' => $admin_id,
            'sys_check' => $sys_check
        ]);

        return TRUE;
    }

    /**
     * 取得案件相關的詳細資訊
     * @param array $target_ids
     * @return mixed
     */
    public function get_targets_detail(array $target_ids)
    {
        $this->CI->load->model('loan/target_model');
        $this->CI->load->model('transaction/transaction_model');
        $this->CI->load->model('user/user_certification_model');
        $this->CI->load->library('credit_lib');

        $product_list = $this->CI->config->item('product_list');
        $sub_product_list = $this->CI->config->item('sub_product_list');
        $subloan_list = $this->CI->config->item('subloan_list');
        $temp_remain_amount_list = [];

        $targets = $this->CI->target_model->get_targets_detail(['id' => $target_ids]);

        $where = ['investor' => USER_BORROWER, 'status' => 1];
        $user_cert_list = $this->CI->user_certification_model->getCertificationsByTargetId($target_ids, $where);

        $remain_principle_List = $this->CI->transaction_model->get_targets_account_payable_amount($target_ids, SOURCE_AR_PRINCIPAL, $group_by_target=TRUE);
        $remain_principle_List = array_column($remain_principle_List, 'total_count', 'target_id');

        foreach ($targets as &$target)
        {
            $user_status = $this->CI->target_model->get_old_user([$target['user_id']], $target['created_at']);
            $user_status = array_column($user_status, 'user_from', 'user_from');

            $user_id = $target['user_id'];
            $product_id = $target['product_id'];
            $sub_product_id = $target['sub_product_id'];
            if ( ! array_key_exists($user_id, $temp_remain_amount_list))
            {
                $remain_amount = $this->CI->credit_lib->get_remain_amount($user_id, $product_id, $sub_product_id);
                $temp_remain_amount_list[$user_id] = $remain_amount;
            }

            $target['product_name'] = $product_list[$product_id]['name'] . ($sub_product_id != 0 ? '/' . $sub_product_list[$sub_product_id]['identity'][$product_list[$product_id]['identity']]['name'] : '') . (preg_match('/' . $subloan_list . '/', $target['target_no']) ? '(產品轉換)' : '');
            $target['user_loyalty_status'] = isset($user_status[$user_id]) ? '舊戶':'新戶';
            $target['user_identity'] = (isset($user_cert_list[$user_id]) && isset($user_cert_list[$user_id][CERTIFICATION_IDENTITY]) ? "是" : "否");
            $target['apply_date'] = date('Y-m-d',$target['created_at']);
            $target['apply_time'] = date('H:i:s',$target['created_at']);
            // TODO: 封存當下的可動用額度，還是匯出當下的？
            $target['unused_credit_line'] = floor($target['unused_credit_line'] / 1000) * 1000;
            // $target['unused_credit_line'] = $temp_remain_amount_list[$user_id]['user_available_amount'];
            $target['principle_balance'] = $remain_principle_List[$target['id']] ?? 0;
        }

        return $targets;
    }

    /**
     * 取得產品設定結構
     * @param $product_id
     * @param $sub_product_id
     * @return array|mixed
     */
    public function get_product_info($product_id, $sub_product_id)
    {
        $product_list = $this->CI->config->item('product_list');
        $sub_product_list = $this->CI->config->item('sub_product_list');
        $product = $product_list[$product_id];
        if (isset($sub_product_list[$sub_product_id]['identity'][$product['identity']]) && in_array($sub_product_id, $product['sub_product']))
        {
            $sub_product_data = $sub_product_list[$sub_product_id]['identity'][$product['identity']];
            $product = $this->sub_product_profile($product, $sub_product_data);

        }
        return $product;
    }

    /**
     * 將 Target meta value 轉換成有意義的顯示文字
     * @param $meta_key
     * @param $meta_value
     * @return string
     */
    public function convert_meta_value(string $meta_key, $meta_value)
    {
        switch ($meta_key)
        {
            case TARGET_META_COMPANY_CATEGORY_NUMBER:
                switch ($meta_value)
                {
                    case COMPANY_CATEGORY_NORMAL: return COMPANY_CATEGORY_NAME_NORMAL;
                    case COMPANY_CATEGORY_FINANCIAL: return COMPANY_CATEGORY_NAME_FINANCIAL;
                    case COMPANY_CATEGORY_GOVERNMENT: return COMPANY_CATEGORY_NAME_GOVERNMENT;
                    case COMPANY_CATEGORY_LISTED: return COMPANY_CATEGORY_NAME_LISTED;
                }
                break;
        }
        return $meta_value;
    }

    /**
     * 將 Target meta key 轉換成有意義的顯示文字
     * @param string $meta_key
     * @return string
     */
    public function convert_meta_key(string $meta_key)
    {
        switch ($meta_key)
        {
            case TARGET_META_COMPANY_CATEGORY_NUMBER:
                return '自填職業';
        }
        return $meta_key;
    }

    /**
     * 取得案件 meta 列表
     * @param $target_id
     * @return array
     */
    public function get_meta_list($target_id) : array {
        $target_meta = [];
        $this->CI->load->model('loan/target_meta_model');
        $rs = $this->CI->target_meta_model->as_array()->get_many_by(['target_id' => $target_id]);

        foreach ($rs as &$meta)
        {
            if (isJson($meta['meta_value']))
            {
                $meta['meta_value'] = json_decode($meta['meta_value'], TRUE);
            }

            if (is_array($meta['meta_value']))
            {
                foreach ($meta['meta_value'] as $value)
                {
                    $meta['meta_value_alias'] = $this->convert_meta_value($meta['meta_key'], $value);
                }
            }
            else
            {
                $meta['meta_value_alias'] = $this->convert_meta_value($meta['meta_key'], $meta['meta_value']);
            }

            $target_meta[] = [
                'meta_key' => $meta['meta_key'],
                'meta_key_alias' => $this->convert_meta_key($meta['meta_key']),
                'meta_value' => $meta['meta_value'],
                'meta_value_alias' => $meta['meta_value_alias']
            ];
        }

        return $target_meta;
    }

    /**
     * @param $target
     * @param $admin_id
     * @param $remark
     * @return bool TRUE if successfully rejected, FALSE if doing nothing
     */
    public function reject($target, $admin_id, string $remark = ''): bool
    {
        if ( ! $target || ! in_array($target->status,array(
                TARGET_WAITING_APPROVE,
                TARGET_WAITING_SIGNING,
                TARGET_WAITING_VERIFY,
                TARGET_ORDER_WAITING_VERIFY,
                TARGET_ORDER_WAITING_SHIP,
                TARGET_BANK_FAIL
            )))
        {
            return FALSE;
        }
        if ($this->is_sub_loan($target->target_no))
        {
            $this->CI->load->library('Subloan_lib');
            $this->CI->subloan_lib->subloan_verify_failed($target,$admin_id,$remark);
        }else{
            $this->target_verify_failed($target,$admin_id,$remark);
        }
        return TRUE;
    }

    public function get_enterprise_product_ids(): array
    {
        return [PRODUCT_FOREX_CAR_VEHICLE, PRODUCT_SK_MILLION_SMEG];
    }

    public function get_individual_product_ids(): array
    {
        return [PRODUCT_ID_STUDENT, PRODUCT_ID_STUDENT_ORDER, PRODUCT_ID_SALARY_MAN, PRODUCT_ID_SALARY_MAN_ORDER, PRODUCT_ID_HOME_LOAN];
    }

    public function get_home_loan_product_ids():array
    {
        return [PRODUCT_ID_HOME_LOAN];
    }

    public function get_product_id_by_tab($tabname): array
    {
        switch ($tabname)
        {
            case PRODUCT_TAB_ENTERPRISE:
                return $this->get_enterprise_product_ids();
            case PRODUCT_TAB_HOME_LOAN:
                return $this->get_home_loan_product_ids();
            case PRODUCT_TAB_INDIVIDUAL:
            default:
                return $this->get_individual_product_ids();
        }
    }

    /**
     * 取得下期還款資料
     * @param $target
     * @return array
     */
    public function get_repayment_schedule($target): array
    {
        $repayment_list = [];
        if (isset($target))
        {
            switch ($target->product_id)
            {
                case PRODUCT_SK_MILLION_SMEG:
                    $today = get_entering_date();
                    for ($i = 1; $i <= $target->instalment; $i++)
                    {
                        $repayment_date = date('Y-m-d', strtotime($target->loan_date . '+' . $i . 'month'));
                        if ($repayment_date >= $today)
                        {
                            // TODO: amount 待定
                            $tmp = ['instalment' => $i, 'date' => $repayment_date, 'amount' => 0];
                            $repayment_list[] = $tmp;
                        }
                    }
                    break;
                default:
                    $this->CI->load->model('transaction/transaction_model');
                    $repayment_schedule = $this->CI->transaction_model->get_repayment_schedule($target->id);
                    if ( ! empty($repayment_schedule))
                    {
                        foreach ($repayment_schedule as $repayment)
                        {
                            $tmp =  ['instalment' => (int) $repayment['instalment_no'],
                                'date' => $repayment['limit_date'], 'amount' => (int) $repayment['amount']];
                            $repayment_list[] = $tmp;
                        }
                    }
                    break;
            }
        }
        return $repayment_list;
    }

    /**
     * 取得案件最後一期還款日期
     * @param $target
     * @return string
     */
    public function get_pay_off_date($target): string
    {
        $pay_off_date = '';
        if (isset($target))
        {
            switch ($target->product_id)
            {
                case PRODUCT_SK_MILLION_SMEG:
                    $pay_off_date = date('Y-m-d', strtotime($target->loan_date . '+' . $target->instalment . 'month'));
                    break;
                default:
                    $this->CI->load->model('transaction/transaction_model');
                    $rs = $this->CI->transaction_model->order_by('limit_date', 'DESC')->get_by(['target_id' => $target->id,
                        'source' => [SOURCE_AR_PRINCIPAL],
                        'status' => [TRANSACTION_STATUS_TO_BE_PAID, TRANSACTION_STATUS_PAID_OFF]]);
                    if (isset($rs))
                    {
                        $pay_off_date = $rs->limit_date;
                    }
                    break;
            }
        }
        return $pay_off_date;
    }

    /**
     * @param int $character : 角色 (對應常數 ASSOCIATES_CHARACTER_*)
     * @return string
     */
    public function get_product_1002_character_meaning(int $character): string
    {
        switch ($character)
        {
            case ASSOCIATES_CHARACTER_REAL_OWNER:
                return '負責人實際負責人';
            case ASSOCIATES_CHARACTER_SPOUSE:
                return '配偶';
            case ASSOCIATES_CHARACTER_GUARANTOR_A:
            case ASSOCIATES_CHARACTER_GUARANTOR_B:
                return '負責人保證人';
            default:
                return '負責人配偶/保證人';
        }
    }

    /**
     * 確認是否有待核可案件已一鍵送出
     * @param $user_id
     * @return bool
     */
    public function exist_approving_target_submitted($user_id): bool
    {
        $this->CI->load->model('loan/target_model');
        return $this->CI->target_model->chk_exist_by_status([
            'user_id' => $user_id,
            'status' => TARGET_WAITING_APPROVE,
            'certificate_status' => [TARGET_CERTIFICATE_SUBMITTED, TARGET_CERTIFICATE_RE_SUBMITTING]
        ]);
    }

    public function get_natural_person_export_list($product_id)
    {
        switch ($product_id)
        {
            case PRODUCT_ID_STUDENT:
            case PRODUCT_ID_SALARY_MAN:
                break;
            default:
                return [];
        }

        $this->CI->load->model('loan/target_model');
        return $this->CI->target_model->get_specific_product_status($product_id, [
            TARGET_WAITING_APPROVE,
            TARGET_WAITING_SIGNING,
            TARGET_WAITING_VERIFY,
            TARGET_ORDER_WAITING_SIGNING,
            TARGET_ORDER_WAITING_VERIFY
        ]);
    }

    /**
     * 檢查是否為產轉案件
     * @param $target_no
     * @return bool
     */
    public function is_sub_loan($target_no): bool
    {
        $subloan_list = $this->CI->config->item('subloan_list');
        return (bool) preg_match('/' . $subloan_list . '/', $target_no);
    }
        /**
     * 退「屋現勘/遠端視訊預約時間認證」 and 子系統取消預約時段
     *
     * @param int $userId 用戶ID
     * @return bool 成功取消預訂並更新認證記錄返回 true，否則返回 false。
     */
    public function cancel_booking_and_certification(int $userId): bool
    {
        $this->CI->load->model('user/user_certification_model');
        $this->CI->load->library('booking_lib');
        $certification = $this->CI->user_certification_model->get_by([
            'user_id' => $userId,
            'certification_id' => CERTIFICATION_SITE_SURVEY_BOOKING,
            'status' => 1
        ]);

        if (!$certification) {
            // 如果找不到相應的認證記錄，視為成功取消
            return true;
        }

        $content = json_decode($certification->content, true);

        if (isset($content['booking_response']['_id'])) {
            $bookingId = $content['booking_response']['_id'];
            $response = $this->CI->booking_lib->cancel_booking($bookingId);
            if (!isset($response['result']) || $response['result'] != 'SUCCESS') {
                // 如果取消預訂失敗，返回失敗
                return false;
            }
        }

        $result = $this->CI->user_certification_model->update($certification->id, ['status' => 2]);
        if (!$result) {
            // 如果更新認證記錄失敗，返回失敗
            return false;
        }

        // 成功取消預訂並更新認證記錄
        return true;
    }
}

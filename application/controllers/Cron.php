<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cron extends CI_Controller
{


    function __construct()
    {
        parent::__construct();
        if (!app_access()) {
            show_404();
        }
        $this->load->model('log/log_script_model');
    }

    public function cathay()
    {	//每五分鐘
        $this->load->library('Payment_lib');
        $script = 1;
        $start_time = time();
        $ids = $this->payment_lib->script_get_cathay_info();
        $num = $ids ? count($ids) : 0;
        $end_time = time();
        $data = [
            'script_name' => 'cathay',
            'num' => $num,
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
        $this->log_script_model->insert($data);
        die('1');
    }

    public function handle_payment()
    {	//每五分鐘
        $this->load->library('Payment_lib');
        $script = 2;
        $start_time = time();
        $count = $this->payment_lib->script_handle_payment();
        $num = $count ? intval($count) : 0;
        $end_time = time();
        $data = [
            'script_name' => 'handle_payment',
            'num' => $num,
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
        $this->log_script_model->insert($data);
        die('1');
    }

    public function check_bidding()
    {	//每五分鐘
        $this->load->library('Target_lib');
        $script = 3;
        $start_time = time();
        $count = $this->target_lib->script_check_bidding();
        $num = $count ? intval($count) : 0;
        $end_time = time();
        $data = [
            'script_name' => 'check_bidding',
            'num' => $num,
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
        $this->log_script_model->insert($data);
        die('1');
    }

    public function temp_handle_invalid_target()
    {
        $this->load->model('loan/target_model');
        $this->load->library('subloan_lib');
        $this->load->library('Notification_lib');
        $this->load->library('target_lib');

        $get = $this->input->get(NULL, TRUE);
        $target_ids = isset($get['ids']) && $get['ids'] ? explode(',', $get['ids']) : null;
        if (isset($target_ids)) {
            $targets = $this->target_model->get_many_by(['id' => $target_ids]);
        } else {
            $targets = $this->target_model->get_many_by(['status' => [0, 1, 2, 3], 'interest_rate > ' => 16]);
        }
        foreach ($targets as $key => $value) {
            if ($value->interest_rate > 16) {
                // 待出借(待上架)
                if ($value->status == 3) {
                    if ($this->target_lib->is_sub_loan($value->target_no) === TRUE) {
                        $this->subloan_lib->subloan_cancel_bidding($value, 0, null);
                    } else {
                        $this->target_lib->target_cancel_bidding($value, 0, null);
                    }
                }

                if (in_array($value->status, [0, 1, 2, 3]) && in_array($value->sub_status, [0, 8, 9, 10])) {
                    // 針對 status in (0,1,2) 的案件做取消，前面 3 的會先下架，但 value 狀態沒有更新，所以要有 3
                    $this->target_lib->cancel_target($value, $value->user_id, 0);
                    $this->notification_lib->withdraw_invalid_target($value->user_id, 0);
                }
            }
        }

    }

    public function approve_target()
    {	//每五分鐘
        $this->load->library('Target_lib');
        $script = 4;
        $start_time = time();
        $count = $this->target_lib->script_approve_target();
        $num = $count ? intval($count) : 0;
        $end_time = time();
        $data = [
            'script_name' => 'approve_target',
            'num' => $num,
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
        $this->log_script_model->insert($data);
        die('1');
    }

    // 檢查待簽約案件的核可額度是否過期
    public function chk_target_signing()
    {
        $this->load->library('target_lib');
        $start_time = time();
        $count = $this->target_lib->script_chk_signing();
        $end_time = time();
        $data = [
            'script_name' => __FUNCTION__,
            'num' => $count,
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
        $this->log_script_model->insert($data);
        die('1');
    }

    public function check_transfer_bidding()
    {	//每五分鐘
        $this->load->library('Transfer_lib');
        $script = 5;
        $start_time = time();
        $count = $this->transfer_lib->script_check_bidding();
        $num = $count ? intval($count) : 0;
        $end_time = time();
        $data = [
            'script_name' => 'check_transfer_bidding',
            'num' => $num,
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
        $this->log_script_model->insert($data);
        die('1');
    }

    public function charge_targets()
    {
        // 進行貸案處理，將還款中的交易帳務過帳核算，更改狀態
        $script = 6;
        $start_time = time();

        // 每五分鐘
        $this->load->library('Charge_lib');

        $count = $this->charge_lib->script_charge_targets();
        $num = $count ? intval($count) : 0;
        $end_time = time();
        $data = [
            'script_name' => 'charge_targets',
            'num' => $num,
            'start_time' => $start_time,
            'end_time' => $end_time
        ];

        $this->log_script_model->insert($data);

        die('1');
    }

    public function charge_delayed_targets_partial_fee()
    {
        // 處理逾期案部分清償
        $script = 16;
        $start_time = time();

        // 每五分鐘
        $this->load->library('Charge_lib');

        $count = $this->charge_lib->script_charge_delayed_targets_partial_fee();
        $num = $count ? intval($count) : 0;
        $end_time = time();
        $data = [
            'script_name' => 'charge_partial_delayed_targets',
            'num' => $num,
            'start_time' => $start_time,
            'end_time' => $end_time
        ];

        $this->log_script_model->insert($data);

        die('1');
    }

    public function prepayment_targets()
    {	//每五分鐘
        $this->load->library('Charge_lib');
        $script = 7;
        $start_time = time();
        $count = $this->charge_lib->script_prepayment_targets();
        $num = $count ? intval($count) : 0;
        $end_time = time();
        $data = [
            'script_name' => 'prepayment_targets',
            'num' => $num,
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
        $this->log_script_model->insert($data);
        die('1');
    }

    public function check_certifications()
    {	//每五分鐘
        $this->load->library('Certification_lib');
        $script = 8;
        $start_time = time();
        $count = $this->certification_lib->script_check_certification();
        $num = $count ? intval($count) : 0;
        $end_time = time();
        $data = [
            'script_name' => 'check_certifications',
            'num' => $num,
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
        $this->log_script_model->insert($data);
        die('1');
    }

    public function handle_promote()
    {
        $start_time = time();
        $this->load->library('user_lib');
        $this->load->model('user/user_qrcode_model');
        $this->load->model('user/qrcode_setting_model');

        $date = $this->input->get('date');
        if (empty($date) || !($timestamp = strtotime($date))) {
            $timestamp = $start_time;
        }
        $year = date('Y', $timestamp);
        $month = date('m', $timestamp);
        $day = date('d', $timestamp);

        // 自動延長一般方案/特約方案的結束時間
        $this->user_qrcode_model->autoRenewTime($this->qrcode_setting_model->generalCaseAliasName);
        $this->user_qrcode_model->autoRenewTime($this->qrcode_setting_model->appointedCaseAliasName);

        if ($day >= 1 && $day <= 9) {
            $data = [
                'script_name' => 'handle_promote_reward',
                'num' => 0,
                'start_time' => $start_time,
                'end_time' => 0
            ];
            $rs = $this->log_script_model->insert($data);
            // 結算獎勵
            $num = $this->user_lib->scriptHandlePromoteReward($year, $month);
        } else {
            $data = [
                'script_name' => 'handle_promote_receipt',
                'num' => 0,
                'start_time' => $start_time,
                'end_time' => 0
            ];
            $rs = $this->log_script_model->insert($data);
            // 計算勞務報酬單或對帳明細
            $num = $this->user_lib->send_promote_receipt();
        }
        $this->log_script_model->update_by(['id' => $rs], [
            'num' => $num,
            'end_time' => time(),
        ]);
        die('1');
    }
    /**
     * 針對實名驗證已成功的所有用戶進行重新認證
     */
    public function recheck_certifications()
    {

        $this->load->model('user/user_certification_model');
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);
        if (empty($request)) {
            $user_certifications = $this->user_certification_model->order_by('user_id', 'ASC')->get_many_by(
                array(
                    'status' => 1,
                    'certification_id =' => 1,
                    // 借款投資都要驗
                    //'investor' => 0
                )
            );
            echo json_encode(
                array_values(
                    array_unique(
                        array_columns(
                            json_decode(json_encode($user_certifications), true),
                            ['user_id', 'investor']
                        ),
                        SORT_REGULAR
                    )
                )
            );
        } else {
            $result = [];
            $pendingUpdateData = [];
            $this->load->library('Certification_lib');
            $this->load->model('user/user_model');
            foreach ($request as $key => $v) {
                $user_certifications = $this->user_certification_model
                    ->order_by('user_id ASC, id DESC', '')
                    ->get_by(
                        array(
                            'status' => 1,
                            'certification_id =' => 1,
                            'user_id' => $v->user_id,
                            'investor' => $v->investor
                        )
                    );

                // 已被封鎖的就不再重驗
                $user = $this->user_model->get_by(["id" => $v->user_id]);
                if (isset($user) && $user->block_status != 0)
                    continue;

                if (isset($user_certifications)) {
                    $tmpRs = $this->certification_lib->realname_verify($user_certifications);
                    $result[] = $tmpRs;

                    $param = [
                        'status' => 3,
                        'remark' => json_encode($tmpRs['remark']),
                        'content' => json_encode($tmpRs['content']),
                        'sys_check' => 1,
                    ];
                    $reviewStatus = 3;
                    if (!$tmpRs['ocrCheckFailed'] && $tmpRs['remark']['error'] == '' && !$tmpRs['ocrCheckFailed']) {
                        unset($param['status']);
                        $reviewStatus = 1;
                    }
                    if ($tmpRs['risVerified'] && $tmpRs['risVerificationFailed']) {
                        $param = [
                            'remark' => json_encode($tmpRs['remark']),
                            'content' => json_encode($tmpRs['content']),
                        ];
                        $reviewStatus = 2;
                    }

                    $pendingUpdateData[] = [
                        'reviewStatus' => $reviewStatus,
                        'cer_id' => $user_certifications->id,
                        'param' => $param
                    ];
                }
            }

            array_map(function ($data) {
                if ($data['reviewStatus'] == 2)
                    $this->certification_lib->set_failed_for_recheck($data['cer_id'], '', true);

                $this->user_certification_model->update($data['cer_id'], $data['param']);

            }, $pendingUpdateData);

            echo json_encode($result);
        }
    }

    // 一次性的針對6/19 10:00以前的人工審核狀態實名進行處理
    public function temp_handle_pending_realname()
    {
        $this->load->model('user/user_certification_model');
        $this->load->model('loan/target_model');
        $this->load->library('Certification_lib');
        $this->load->library('Notification_lib');

        $info_list = $this->user_certification_model->get_many_by([
            'certification_id' => "1",
            'updated_at <' => 1624068044,
            'status' => 3,
        ]);
        $successCnt = $failedCnt = 0;
        foreach ($info_list as $key => $info) {
            if ($info->investor == 1) {
                $this->certification_lib->set_success($info->id, true);
                $successCnt++;
            } else {
                $target = $this->target_model->get_by([
                    'user_id' => $info->user_id,
                    'status' => [0, 1, 2, 3, 4, 20, 21, 22, 23, 24],
                ]);
                // 找不到 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標） 20:待報價 21:待簽約(分期) 22:待驗證(分期) 23:待出貨(分期) 24:待債轉上架 的紀錄
                if (!isset($target)) {
                    // 找不到 還款中(5) && delay_days > 0 的紀錄
                    $delay_target = $this->target_model->get_by([
                        'user_id' => $info->user_id,
                        'status' => 5,
                        'delay_days > ' => 0,
                    ]);
                    if (!isset($delay_target)) {
                        $this->certification_lib->set_failed($info->id, '親愛的會員您好，為確保資料真實性，請您提重新提供實名認證資料，更新您的訊息，謝謝。', true);
                        $this->notification_lib->temp_realname_failed($info->user_id);
                        $failedCnt++;
                    }
                }
            }
        }
        echo "successCnt:" . $successCnt;
        echo "failedCnt:" . $failedCnt;
    }

    public function trigger_edm_event()
    {	//每一小時
        $this->load->library('user_lib');
        $this->load->model('user/user_model');
        $this->load->library('Notification_lib');


        $start_time = time();
        $count = $this->user_lib->script_trigger_edm_event();
        $num = $count ? intval($count) : 0;
        $end_time = time();
        $data = [
            'script_name' => 'trigger_edm_event',
            'num' => $num,
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
        $this->log_script_model->insert($data);
        die('1');
    }

    public function daily_tax()
    {	//每天下午一點
        $this->load->library('Payment_lib');
        $script = 9;
        $input = $this->input->get();
        $start = isset($input['dstart']) ? $input['dstart'] : '';
        $end = isset($input['dend']) ? $input['dend'] : '';
        $start_time = time();
        $count = $this->payment_lib->script_daily_tax($start, $end);
        $num = $count ? intval($count) : 0;
        $end_time = time();
        $data = [
            'script_name' => 'daily_tax',
            'num' => $num,
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
        $this->log_script_model->insert($data);
        die('1');
    }

    // 普匯租賃開立發票
    public function daily_tax_for_leasing()
    {
        $this->load->library('payment_lib');
        $input = $this->input->get(['dstart', 'dend'], TRUE);
        $start_time = time();
        $count = $this->payment_lib->script_daily_tax_for_leasing($input['dstart'], $input['dend']);
        $end_time = time();
        $data = [
            'script_name' => __FUNCTION__,
            'num' => $count,
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
        $this->log_script_model->insert($data);
        die('1');
    }

    public function create_estatement_pdf()
    {	//每五分鐘
        $this->load->library('Estatement_lib');
        $script = 10;
        $start_time = time();
        $count = $this->estatement_lib->script_create_estatement_pdf();
        $num = $count ? intval($count) : 0;
        $end_time = time();
        $data = [
            'script_name' => 'create_estatement_pdf',
            'num' => $num,
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
        $this->log_script_model->insert($data);
        die('1');
    }

    public function alert_account_remaining()
    {	//需要時才手動跑
        $this->load->library('Passbook_lib');
        $script = 11;
        $start_time = time();
        $count = $this->passbook_lib->script_alert_account_remaining();
        $num = $count ? intval($count) : 0;
        $end_time = time();
        $data = [
            'script_name' => 'alert_account_remaining',
            'num' => $num,
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
        $this->log_script_model->insert($data);
        die('1');
    }

    public function create_estatement_html()
    {	//每五分鐘
        $this->load->library('Estatement_lib');
        $script = 12;
        $start_time = time();
        $count = $this->estatement_lib->script_create_estatement_content();
        $num = $count ? intval($count) : 0;
        $end_time = time();
        $data = array(
            "script_name" => "create_estatement_html",
            "num" => $num,
            "start_time" => $start_time,
            "end_time" => $end_time
        );
        $this->log_script_model->insert($data);
        die('1');
    }

    public function create_investor_estatement_html()
    {	//每五分鐘
        $this->load->library('Estatement_lib');
        $script = 16;
        $start_time = time();
        $count = $this->estatement_lib->script_create_investor_estatement_content();
        $num = $count ? intval($count) : 0;
        $end_time = time();
        $data = array(
            "script_name" => "create_investor_estatement_html",
            "num" => $num,
            "start_time" => $start_time,
            "end_time" => $end_time
        );
        $this->log_script_model->insert($data);
        die('1');
    }
    public function create_borrower_estatement_html()
    {	//每五分鐘
        $this->load->library('Estatement_lib');
        $script = 17;
        $start_time = time();
        $count = $this->estatement_lib->script_create_borrower_estatement_content();
        $num = $count ? intval($count) : 0;
        $end_time = time();
        $data = array(
            "script_name" => "create_borrower_estatement_html",
            "num" => $num,
            "start_time" => $start_time,
            "end_time" => $end_time
        );
        $this->log_script_model->insert($data);
        die('1');
    }
    public function re_create_estatement_html()
    {	//重新產生指定使用者對帳單
        $this->load->library('Estatement_lib');
        $start_time = time();
        $input = $this->input->get();
        $user_id = $input['user_id'];
        $start = $input['start'];
        $end = $input['end'];
        $investor = $input['investor'];
        $detail = $input['detail'];
        //user_id,開始時間.結束時間,投資端(option),detail(option)
        $count = $this->estatement_lib->script_re_create_estatement_content($user_id, $start, $end, $investor, $detail);
        $num = $count ? intval($count) : 0;
        $end_time = time();
        $data = array(
            "script_name" => "re_create_estatement_html",
            "num" => $num,
            "start_time" => $start_time,
            "end_time" => $end_time
        );
        $this->log_script_model->insert($data);
        die("1");
    }

    public function send_estatement_pdf()
    {	//每五分鐘
        $this->load->library('Estatement_lib');
        $script = 13;
        $start_time = time();
        $count = $this->estatement_lib->script_send_estatement_pdf();
        $num = $count ? intval($count) : 0;
        $end_time = time();
        $data = array(
            "script_name" => "send_estatement_pdf",
            "num" => $num,
            "start_time" => $start_time,
            "end_time" => $end_time
        );
        $this->log_script_model->insert($data);
        die("1");
    }

    public function check_transfer_success()
    {	//每五分鐘
        $this->load->library('Transfer_lib');
        $script = 14;
        $start_time = time();
        //		$count 		= $this->transfer_lib->script_transfer_success();
        $count = $this->transfer_lib->script_transfer_success_v2();
        $num = $count ? intval($count) : 0;
        $end_time = time();
        $data = [
            'script_name' => 'transfer_success',
            'num' => $num,
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
        $this->log_script_model->insert($data);
        die('1');
    }
    //hsiang  串國泰回應API
    public function check_batchno_to_cathay()
    {
        //每六分鐘
        $this->load->library('Payment_lib');
        $script = 15;
        $start_time = time();
        $id_list = $this->payment_lib->check_batchno_to_cathay();
        $num = count($id_list);
        $end_time = time();
        $data = [
            'script_name' => 'check_batchno_to_cathay',
            'num' => $num,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'parameter' => json_encode($id_list)
        ];
        $this->log_script_model->insert($data);
        die('1');
    }

    /**
     * 寄發 EDM 通知
     *
     * 需使用 POST 方法，並帶入 JSON payload
     * 若有帶入 mail_list 時，無視其他篩選條件，只寄送給 mail_list 的信箱
     * app 若設為 1 是用來通知 app client
     * mail 若設為 1 是用來寄送信件
     */
    public function EDM()
    {
        $input = json_decode($this->input->raw_input_stream);
        $user_id = isset($input->user_id) ? $input->user_id : 0;
        $title = $input->title;
        $content = $input->content;
        $EDM = $input->EDM;
        $EDM_href = $input->EDM_href;
        $investor = isset($input->investor) ? $input->investor : 0;
        $school = isset($input->school) && $input->school != '' ? $input->school : false;
        $years = isset($input->years) && $input->years != '' ? $input->years : false;
        $sex = isset($input->sex) && $input->sex != '' ? $input->sex : false;
        $app = isset($input->app) && $input->app != '' ? $input->app : false;
        $mail = isset($input->mail) && $input->mail != '' ? $input->mail : false;
        $mail_list = isset($input->mail_list) && count($input->mail_list) ? $input->mail_list : array();
        $this->load->library('Notification_lib');

        $start_time = time();
        $count = $this->notification_lib->EDM($user_id, $title, $content, $EDM, $EDM_href, $investor, $school, $years, $sex, $app, $mail, $mail_list);
        $num = $count ? intval($count) : 0;
        $end_time = time();
        $data = [
            'script_name' => 'EDM',
            'num' => $num,
            'parameter' => json_encode([$user_id, $title, $content, $EDM, $EDM_href, $investor, $school, $years, $sex, $app, $mail, $mail_list]),
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
        $this->log_script_model->insert($data);
    }

    public function cancel_target_after_7days_if_name_is_not_verified(){
        $this->load->model('loan/target_model');
        $this->load->library('target_lib');

        $target_list = $this->target_model->get_un_verified_name_7days_target_list();

        $cancelTargetIdArray = array();
        foreach ($target_list as $target) {
            $userId = $target->user_id;
            $targetId = $target->id;
            if(!in_array($targetId, $cancelTargetIdArray)){
                $this->target_lib->cancel_target($target, $target->user_id, 0);
                $this->notification_lib->withdraw_invalid_target($target->user_id, 0);
                $cancelTargetIdArray[] = $targetId;
            }
        }

    }

    public function cancel_target_after_14days_if_name_is_verified(){
        $this->load->model('loan/target_model');
        $this->load->library('target_lib');

        $target_list = $this->target_model->get_verified_name_but_others_not_fullfiled_14days_target_list();

        $product_list = $this->config->item('product_list');
        $cancelTargetIdArray = array();
        foreach ($target_list as $target) {
            $certification_id  = $target->certification_id;
            $product_of_this_target = $product_list[$target->product_id];
            $user_certifications_of_this_product = $product_of_this_target['certifications'];

            if( in_array($certification_id, $user_certifications_of_this_product) ){
                $userId = $target->user_id;
                $targetId = $target->id;
                if(!in_array($targetId, $cancelTargetIdArray)){
                    $this->target_lib->cancel_target($target, $userId, 0);
                    $this->notification_lib->withdraw_invalid_target($userId, 0);
                    $cancelTargetIdArray[] = $targetId;
                }

            }
        }

    }

    public function notice_msg()
    {
        $input = $this->input->get();
        $user_id = isset($input['user_id']) ? $input['user_id'] : 0;
        $title = $input['title'];
        $content = $input['content'];
        $investor = isset($input['investor']) ? $input['investor'] : 0;
        $type = isset($input['type']) ? $input['type'] : 'b03';
        $this->load->library('Notification_lib');
        $this->notification_lib->notice_msg($user_id, $title, $content, $investor, $type);
    }
    public function rescraper_file()
    {

        $input = $this->input->get();
        $ip = isset($input['ip']) ? $input['ip'] : '';

        if (!$ip) {
            echo 'paarams not ip value';
            exit;
        }
        if (!file_exists('reScraper.txt')) {
            fopen("reScraper.txt", "w");
        }
        $current = json_decode(file_get_contents('reScraper.txt'));

        if (!$current || !isset($current->status) || !isset($current->updated_at)) {
            $current = new stdClass();
            $current->status = 'no_data';
            $current->offset = '0';
            $current->updated_at = date('Y-m-d H:i:s');

            file_put_contents('reScraper.txt', json_encode($current));
        }

        if (isset($current->status) && $current->status == '全部完成') {
            exit;
        } elseif (isset($current->status) && $current->status == 'no_data') {
            $current->offset++;
        }

        $this->load->model('user/user_model');
        $meta = $this->user_model->getUsersBy(['certification_id' => CERTIFICATION_IDENTITY], ['name', 'id_card_place'], $current->offset, 1);

        $this->load->library('scraper/judicial_yuan_lib.php', ['ip' => $ip]);
        $current->userId = $meta['0']->user_id;
        $scraper_response = $this->judicial_yuan_lib->requestJudicialYuanVerdictsStatuses($meta['0']->user_id);
        $current->status = isset($scraper_response['response']['status']) ? $scraper_response['response']['status'] : 'no_data';
        $worker_time = isset($scraper_response['response']['updatedAt']) ? date('Y-m-d H:i:s', strtotime('+2 hours', $scraper_response['response']['updatedAt'])) : '';

        if ($current->status == '爬蟲執行完成' || (isset($worker_time) && $current->updated_at < $worker_time) || (isset($scraper_response['status']) && $scraper_response['status'] == '204')) {
            if ($current->status == '爬蟲執行完成' || (isset($worker_time) && $current->updated_at > $worker_time)) {
                $current->offset++;
            }

            $meta = $this->user_model->getUsersBy(['certification_id' => CERTIFICATION_IDENTITY], ['name', 'id_card_place'], $current->offset, 1);

            if (!empty($meta)) {
                $scraper_response = $this->judicial_yuan_lib->requestJudicialYuanVerdicts($meta['0']->name, $meta['0']->id_card_place, $meta['0']->user_id);
                $current->status = 'no_data';
            } else {
                $current->status = '全部完成';
            }

        }

        $current->updated_at = date('Y-m-d H:i:s');
        file_put_contents('reScraper.txt', json_encode($current));
        echo 'ok';
    }

    // pdf解析測試用
    public function pdf_test()
    {

        $input = $this->input->get();
        $url = isset($input['url']) ? $input['url'] : '';
        $type = isset($input['type']) ? $input['type'] : '';

        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($url);
        $text = $pdf->getText();
        $res = '';

        // 掃完結果
        if ($type == 'test') {
            print_r($text);
            exit;
        }

        // 聯徵
        if ($type == 'j') {
            $this->load->library('Joint_credit_lib');
            $res = $this->joint_credit_lib->transfrom_pdf_data($text);
        }

        if ($type == 'i') {
            $this->load->library('Labor_insurance_lib');
            $res = $this->labor_insurance_lib->transfrom_pdf_data($text);
        }
        print_r(json_encode($res));
    }

    public function send_ID_card_request()
    {
        $input = $this->input->get(NULL, TRUE);
        $personId = isset($input['personId']) ? $input['personId'] : '';
        $applyCode = isset($input['applyCode']) ? $input['applyCode'] : '';
        $applyYyymmdd = isset($input['applyYyymmdd']) ? $input['applyYyymmdd'] : '';
        $issueSiteId = isset($input['issueSiteId']) ? $input['issueSiteId'] : '';
        $this->load->library('output/json_output');
        $this->load->library('id_card_lib');

        if (!$personId || !$applyCode || !$applyYyymmdd || !$issueSiteId) {
            $response = array("Wrong Parameters");
            $this->json_output->setStatusCode(400)->setResponse($response)->send();
        }

        $result = $this->id_card_lib->send_request($personId, $applyCode, $applyYyymmdd, $issueSiteId);

        $response = json_decode(json_encode($result), true);
        if (!$response) {
            $this->json_output->setStatusCode(204)->send();
        }

        $this->json_output->setStatusCode(200)->setResponse([$response])->send();
    }

    public function idleFundNotification()
    {
        $this->load->model('log/Log_userlogin_model');
        $this->load->library('Notification_lib');

        $scriptName = 'idleFundNotification';
        $datetime = new DateTime();
        $dayOfMonth = $datetime->format('d');
        $hour = $datetime->format('H');

        $title = "【溫馨提示】";
        $content = "親愛的會員您好，建議您充分利用虛擬帳戶閒置資金，以提高投資效益；若暫無偏好標的，可使用「提領」功能，將您的資金轉出到其他投資項目，預祝您投資順利，穩穩獲利！";

        $result = ['code' => 0, 'msg' => 'Nothing happened'];
        // 每月18號的下午3點觸發
        if ($dayOfMonth == 18 && $hour == 15) {
            $record = $this->log_script_model->order_by("created_at", "desc")->limit(1)->get_by([
                'TIMESTAMPDIFF(SECOND, FROM_UNIXTIME(created_at), CURRENT_TIMESTAMP()) <' => ' 3600',
                'script_name' => $scriptName,
            ]);

            // 近一小時內沒有執行的紀錄
            if (!isset($record)) {
                $data = [
                    'script_name' => $scriptName,
                    'num' => 0,
                    'start_time' => 0,
                    'end_time' => 0
                ];
                $inserted_id = $this->log_script_model->insert($data);
                if ($inserted_id) {
                    $start_time = time();
                    $devices = $this->Log_userlogin_model->get_all_devices();
                    $sentCount = (count($devices, COUNT_RECURSIVE) - count($devices) - array_sum(array_map('count', $devices)));

                    $sendPayload = array(
                        'user_id' => 0,
                        'sender_name' => "閒置資金系統提醒",
                        'target_category' => $this->config->item('notification')['target_category_name'][NotificationTargetCategory::Investment + NotificationTargetCategory::Loan],
                        'target_platform' => 'android/ios',
                        'tokens' => $devices,
                        "notification" => array(
                            "title" => $title,
                            "body" => $content,
                        ),
                        "data" => array(),
                        "send_at" => (new DateTime())->format('Y-m-d H:i'),
                        "apns" => array(
                            "payload" => array(
                                "category" => "NEW_MESSAGE_CATEGORY"
                            )
                        ),
                        "status" => NotificationStatus::Accepted,
                        "type" => NotificationType::RoutineReminder,
                        "dry_run" => 0
                    );
                    $result = $this->notification_lib->send_notification($sendPayload);
                    $end_time = time();

                    $updateData = [
                        'num' => $sentCount,
                        'start_time' => $start_time,
                        'end_time' => $end_time
                    ];
                    $this->log_script_model->update_by(
                        ['id' => $inserted_id],
                        $updateData
                    );

                }
            }
        }
        echo json_encode($result);
    }

    //將黑名單學校的學生認證退回重審
    public function send_certification_return_msg()
    {
        $this->load->model('user/user_certification_model');
        $this->load->library('certification_lib');

        $data_rows = $this->user_certification_model->get_certifications_return();

        if ($data_rows) {
            foreach ($data_rows as $value) {
                $this->certification_lib->set_failed($value['id'], '學生評分系統更新，邀請您重新認證，體驗最新AI風控模組！');
            }
        }
    }

    /**
     * 由於推薦碼的設定可能會更新
     * 執行該方法可以補上新的設定至每個使用者的推薦碼
     */
    public function update_user_qrcode()
    {
        $this->load->model('user/user_qrcode_model');
        $this->load->model('user/qrcode_setting_model');

        $user_qrcode = $this->user_qrcode_model->get_all();
        $qrcode_setting_list = $this->qrcode_setting_model->get_all();
        $qrcode_setting_list = json_decode(json_encode($qrcode_setting_list), TRUE);
        $qrcode_setting_list = array_column($qrcode_setting_list, NULL, 'alias');
        foreach ($qrcode_setting_list as $alias => $qrcode_setting) {
            $qrcode_setting_list[$alias]['settings'] = json_decode($qrcode_setting['settings'], TRUE) ?? [];
        }

        $count = 0;
        $start_time = time();

        $data = [
            'script_name' => 'update_user_qrcode',
            'num' => 0,
            'start_time' => $start_time,
            'end_time' => 0
        ];
        $inserted_id = $this->log_script_model->insert($data);

        foreach ($user_qrcode as $i => $qrcode) {
            $settings = json_decode($qrcode->settings, TRUE);
            if (isset($settings) && array_key_exists($qrcode->alias, $qrcode_setting_list)) {
                $settings['reward'] = array_replace_recursive($qrcode_setting_list[$qrcode->alias]['settings']['reward'], $settings['reward']);
                $qrcode->settings = json_encode($settings) ?? [];
                $user_qrcode_update_param = ['settings' => $qrcode->settings];
                $rs = $this->user_qrcode_model->update_by(['id' => $qrcode->id], $user_qrcode_update_param);
                // 寫 log
                $this->load->model('log/log_user_qrcode_model');
                $user_qrcode_update_param['user_qrcode_id'] = $qrcode->id;
                $this->log_user_qrcode_model->insert_log($user_qrcode_update_param);

                if ($rs) {
                    $count++;
                }
            }
        }

        $end_time = time();
        $updateData = [
            'num' => $count,
            'end_time' => $end_time
        ];
        $this->log_script_model->update_by(
            ['id' => $inserted_id],
            $updateData
        );
        die('1');
    }

    /**
     * 慈善機構批次提領
     * @return void
     */
    public function charity_daily_withdraw()
    {
        $start_time = time();
        $num = 0;

        $this->load->model('user/charity_institution_model');
        $this->load->model('transaction/virtual_passbook_model');
        $this->load->model('user/user_bankaccount_model');
        $this->load->library('transaction_lib');

        $charity_institution_data_list = $this->charity_institution_model->get_withdraw_list();

        foreach ($charity_institution_data_list as $value) {
            if (empty($value['virtual_account']) || empty($value['company_user_id'])) {
                continue;
            }

            // 確認可提領金額
            $virtual_funds = $this->transaction_lib->get_virtual_funds([$value['virtual_account']]);
            if (empty($virtual_funds['total'])) {
                continue;
            }
            $amount = $virtual_funds['total'] - $virtual_funds['frozen'] ?? 0;

            // 檢查是否有銀行帳戶
            $bank_account = $this->user_bankaccount_model->get_by(
                array(
                    'investor' => INVESTOR,
                    'status' => 1,
                    'user_id' => $value['company_user_id'],
                    'verify' => 1
                )
            );
            if (empty($bank_account)) {
                continue;
            }

            // 提領
            $response = $this->transaction_lib->withdraw($value['company_user_id'], (int) $amount);
            if ($response) {
                $num++;
            }
        }

        $this->log_script_model->insert([
            'script_name' => __FUNCTION__,
            'num' => $num,
            'start_time' => $start_time,
            'end_time' => time()
        ]);
        die('1');
    }

    // 提領放款，自動轉出放款匯款單
    public function auto_withdraw()
    {
        $start_time = time();

        // 提領待放款清單
        $this->load->model('transaction/withdraw_model');
        $withdraw_list = $this->withdraw_model->get_all_withdraw_list();
        $valid_withdraw_list = $this->withdraw_model->get_auto_withdraw_list();

        // 轉出放款匯款單
        $count = 0;
        if (!empty($valid_withdraw_list)) {
            $valid_withdraw_ids = array_column($valid_withdraw_list, 'id');
            $this->load->library('payment_lib');
            $response = $this->payment_lib->withdraw_txt($valid_withdraw_ids, SYSTEM_ADMIN_ID);
            if ($response) {
                $count = count($valid_withdraw_ids);
            }
        }
        if (!empty($withdraw_list)) {
            $remain_withdraw_ids = array_diff(array_column($withdraw_list, 'id'), ($valid_withdraw_ids ?? []));
            $this->load->library('withdraw_lib');
            $this->withdraw_lib->withdraw_deny($remain_withdraw_ids);
        }

        $end_time = time();
        $data = [
            'script_name' => 'auto_withdraw',
            'num' => $count,
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
        $this->log_script_model->insert($data);
        die('1');
    }

    // 借款放款，轉出放款匯款單
    public function auto_loan()
    {
        $start_time = time();

        // 借款待放款清單
        $this->load->model('loan/target_model');
        $loan_list = $this->target_model->get_auto_loan_list();

        // 轉出放款匯款單
        $count = 0;
        if (!empty($loan_list)) {
            $loan_ids = array_column($loan_list, 'id');
            $this->load->library('payment_lib');
            $response = $this->payment_lib->loan_txt($loan_ids, SYSTEM_ADMIN_ID);
            if ($response) {
                $count = count($loan_ids);
            }
        }

        $end_time = time();
        $data = [
            'script_name' => 'auto_loan',
            'num' => $count,
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
        $this->log_script_model->insert($data);
        die('1');
    }
    public function get_investor_estatement_count_status()
    {
        $year = $this->input->get('year');
        $month = $this->input->get('month');
        $this->load->library('Estatement_lib');
        $this->estatement_lib->script_create_investor_estatement_content_count_status($year, $month);
        die();
    }

    public function get_borrower_estatement_count_status()
    {
        $year = $this->input->get('year');
        $month = $this->input->get('month');
        $this->load->library('Estatement_lib');
        $this->estatement_lib->script_create_borrower_estatement_content_count_status($year, $month);
        die();
    }

    public function set_target_contract() //臨時需求，補合約書
    {
        $target_id = $this->input->get('target_id');

        $target = $this->target_model->get($target_id);

        if (!$target) {
            echo json_encode(['result' => 'FAIL', 'error' => '找不到此筆資料'], JSON_UNESCAPED_UNICODE);
            die;
        }
        if ($target->status != 1) {
            echo json_encode(['result' => 'FAIL', 'error' => '非待簽約狀態'], JSON_UNESCAPED_UNICODE);
            die;
        }
        if ($target->contract_id) {
            echo json_encode(['result' => 'FAIL', 'error' => '已經有合約'], JSON_UNESCAPED_UNICODE);
            die;
        }
        if (!isset($target->loan_amount)) {
            echo json_encode(['result' => 'FAIL', 'error' => '未設定借款金額'], JSON_UNESCAPED_UNICODE);
            die;
        }
        if (!isset($target->interest_rate)) {
            echo json_encode(['result' => 'FAIL', 'error' => '未設定利率'], JSON_UNESCAPED_UNICODE);
            die;
        }


        $this->load->library('Contract_lib');

        $contract_type = 'lend';

        $user_id = $target->user_id;
        $loan_amount = $target->loan_amount;
        $interest_rate = $target->interest_rate;
        $contract_data = ['', $user_id, $loan_amount, $interest_rate, ''];
        $param['contract_id'] = $this->contract_lib->sign_contract($contract_type, $contract_data);
        $param['remark'] = ''; //清空remark
        $rs = $this->target_model->update($target_id, $param);

        if (!$rs) {
            $this->CI->json_output->setStatusCode(400)->send(['error' => '更新失敗'], JSON_UNESCAPED_UNICODE);
            die;
        }
        echo json_encode(['result' => 'SUCCESS', 'target' => $rs], JSON_UNESCAPED_UNICODE);
        die;
    }


    public function send_estatement_pdf_to_target_user()
    {
        $this->load->library('Estatement_lib');
        $this->load->model('user/user_estatement_model');
        $input = $this->input->get();
        $user_id = $input['user_id'] ?? 0;
        $sdate = $input['sdate'] ?? '';
        if (!$user_id || !$sdate) {
            die('params error');
        }
        $estatement = $this->user_estatement_model->get_by(
            array(
                "url !=" => "",
                //            "status" => 0, //不管有沒有處理過
                "type" => "estatement",
                'user_id' => $user_id,
                'sdate' => $sdate
            )
        );
        if (!$estatement) {
            die('no estatement');
        }
        $rs = $this->estatement_lib->send_estatement($estatement->id);
        if (!$rs) {
            die('send_estatement fail');
        }
        die('send_estatement success');
    }

    public function re_verify_signing_face()
    {
        // 找到待上架的target
        $this->load->model('loan/target_model');

        $batch = 1;
        $targets = $this->db->from('p2p_loan.targets')
            ->where('status', 2)
            ->where_in('product_id', [1, 3])
            ->where('target_no not like', '%NS%')
            ->where('target_no not like', 'SSM%')
            ->where('target_no not like', 'STS%')
            ->where('target_no not like', 'STIS%')
            ->where('target_no not like', 'FGNS%')
            ->where('target_no not like', 'FGIS%')
            ->where('target_no not like', 'HLNS%')
            ->where('DATE(FROM_UNIXTIME(created_at)) >=', '2023-09-01')
            ->order_by('updated_at', 'asc')
            ->limit($batch)
            ->get()
            ->result();

        if (empty($targets)) {
            die('no target');
        }

        $this->load->library('certification_lib');
        $this->load->library('target_lib');
        $this->load->model('user/user_bankaccount_model');

        // 重新驗證簽署人臉
        foreach ($targets as $target) {

            //檢查金融卡綁定 NO_BANK_ACCOUNT
            $bank_account = $this->user_bankaccount_model->get_by([
                'status' => 1,
                'investor' => 0,
                'verify' => 1,
                'user_id' => $target->user_id
            ]);
            if (empty($bank_account)) {
                // 案件狀態更新，特殊需求，不要自動退件
                $param['status'] = 2;
                // update後會更新updated_at，下一次就不會再抓到這筆了
                $this->target_model->update($target->id, $param);
                continue;
            }


            $faceDetect_res = $this->certification_lib->veify_signing_face($target->user_id, $target->person_image);
            if (!isset($faceDetect_res['error'])) {
                // veify_signing_face 發生錯誤
                $param['status'] = $target->status;
                // update後會更新updated_at，下一次就不會再抓到這筆了
                $this->target_model->update($target->id, $param);
                continue;
            }
            if ($faceDetect_res['error'] == '') {
                // 驗證成功，案件狀態更新
                $target->status = TARGET_WAITING_VERIFY;

                // 更新驗證log
                $targetData = json_decode($target->target_data);
                $targetData->autoVerifyLog[] = [
                    'faceDetect' => $faceDetect_res,
                    'res' => TARGET_WAITING_BIDDING,
                    'verify_at' => time()
                ];
                $param['target_data'] = json_encode($targetData);

                // update後會更新updated_at，下一次就不會再抓到這筆了
                $this->target_lib->target_verify_success($target, 0, $param);
            } else {
                //驗證失敗，案件狀態更新，特殊需求，不要自動退件
                $param['status'] = 2;

                // 更新驗證log
                $targetData = json_decode($target->target_data);
                $targetData->autoVerifyLog[] = [
                    'faceDetect' => $faceDetect_res,
                    'res' => TARGET_WAITING_SIGNING,
                    'verify_at' => time()
                ];
                $param['target_data'] = json_encode($targetData);

                // update後會更新updated_at，下一次就不會再抓到這筆了
                $this->target_model->update($target->id, $param);
            }
        }

        $target = $this->target_model->get($targets[0]->id);

        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($target, JSON_UNESCAPED_SLASHES))
            ->_display();

        die();
    }
}

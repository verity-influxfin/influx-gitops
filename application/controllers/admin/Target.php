<?php

defined('BASEPATH') or exit('No direct script access allowed');
require(APPPATH . '/libraries/MY_Admin_Controller.php');

class Target extends MY_Admin_Controller
{

    protected $edit_method = array('edit', 'verify_success', 'verify_failed', 'order_fail', 'waiting_verify', 'final_validations', 'waiting_evaluation', 'waiting_loan', 'target_loan', 'subloan_success', 're_subloan', 'loan_return', 'loan_success', 'loan_failed', 'target_export', 'amortization_export', 'prepayment', 'cancel_bidding', 'approve_order_transfer', 'legalAffairs', 'waiting_reinspection');

    public function __construct()
    {
        parent::__construct();
        $this->load->model('loan/investment_model');
        $this->load->model('loan/transfer_model');
        $this->load->model('user/user_meta_model');
        $this->load->model('user/user_bankaccount_model');
        $this->load->model('user/virtual_account_model');
        $this->load->model('loan/credit_model');
        $this->load->library('target_lib');
        $this->load->library('financial_lib');
        $this->load->library('Spreadsheet_lib');
    }

    public function isJson($inputString)
    {
        json_decode($inputString);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public function index()
    {

        $page_data = ['type' => 'list'];
        $input = $this->input->get(NULL, TRUE);
        $where = [];
        $list = [];
        $fields = ['status', 'delay'];

        if (isset($input['export'])) {
            switch ($input['export']) {
                case 2: // Excel輸出-逾期債權 by 債權
                    $title_rows = [
                        'user_id' => ['name' => '借款人ID'],
                        'product_name' => ['name' => '產品名稱'],
                        'target_no' => ['name' => '案號', 'width' => 20],
                        'credit_level' => ['name' => '核准信評'],
                        'company' => ['name' => '公司', 'width' => 25],
                        'school_name' => ['name' => '學校', 'width' => 25],
                        'user_meta_1' => ['name' => '科系/職位', 'width' => 25],
                        'invest_amount' => ['name' => '債權總額'],
                        'lender' => ['name' => '投資人ID'],
                        'unpaid_principal_by_investor' => ['name' => '逾期本金'],
                        'loan_date' => ['name' => '放款日期', 'width' => 12],
                        'limit_date' => ['name' => '首逾日期', 'width' => 12],
                        'delayed_days' => ['name' => '逾期天數'],
                        'unpaid_interest_by_investor' => ['name' => '尚欠利息'],
                        'delay_interest_by_investor' => ['name' => '尚欠利延滯息', 'width' => 14],
                    ];
                    $data_rows = $this->target_model->get_delayed_report_by_investor($input);
                    $spreadsheet = $this->spreadsheet_lib->load($title_rows, $data_rows);
                    $this->spreadsheet_lib->download('export2.xlsx', $spreadsheet);
                    return;
                case 3: // Excel輸出-逾期債權 by 案件
                    $title_rows = [
                        'target_no' => ['name' => '案號', 'width' => 20],
                        'product_name' => ['name' => '產品', 'width' => 12],
                        'user_id' => ['name' => '會員ID'],
                        'new_or_old' => ['name' => '新舊戶'],
                        'credit_level' => ['name' => '信評'],
                        'company' => ['name' => '公司', 'width' => 25],
                        'school_name' => ['name' => '學校', 'width' => 25],
                        'school_department' => ['name' => '科系', 'width' => 20],
                        'finish_cert_identity' => ['name' => '是否完成實名驗證', 'width' => 20],
                        'amount' => ['name' => '申請金額'],
                        'credit_amount' => ['name' => '核准金額'],
                        'loan_amount' => ['name' => '動用金額'],
                        'user_available_amount' => ['name' => '可動用額度', 'width' => 14],
                        'remaining_principal' => ['name' => '本金餘額'],
                        'interest_rate' => ['name' => '年化利率'],
                        'instalment' => ['name' => '期數'],
                        'repayment' => ['name' => '還款方式'],
                        'loan_date' => ['name' => '放款日期', 'width' => 12],
                        'delay' => ['name' => '逾期狀況'],
                        'delay_days' => ['name' => '逾期天數'],
                        'unpaid_damage' => ['name' => '尚欠違約金', 'width' => 10],
                        'unpaid_interest' => ['name' => '尚欠利息'],
                        'unpaid_delayinterest' => ['name' => '尚欠延滯息', 'width' => 10],
                        'status' => ['name' => '狀態'],
                        'reason' => ['name' => '借款原因', 'width' => 25],
                        'target_created_date' => ['name' => '申請日期', 'width' => 12],
                        'target_created_time' => ['name' => '申請時間'],
                        'credit_created_date' => ['name' => '核准日期', 'width' => 12],
                        'credit_created_time' => ['name' => '核准時間'],
                        'promote_code' => ['name' => '邀請碼'],
                        'remark' => ['name' => '備註'],
                    ];
                    $data_rows = $this->_get_delayed_report_by_target($input);
                    $spreadsheet = $this->spreadsheet_lib->load($title_rows, $data_rows);
                    $this->spreadsheet_lib->download('All_targets_' . date('Ymd') . '.xls', $spreadsheet);
                    return;
            }
        }

        foreach ($fields as $field) {
            if (isset($input[$field]) && $input[$field] != '') {
                $where[$field] = $input[$field];
            }
        }

        if (isset($input[$field]) && !empty($input['tsearch'])) {
            $tsearch = $input['tsearch'];
            if (preg_match("/^[\x{4e00}-\x{9fa5}]+$/u", $tsearch)) {
                $users = $this->user_model->get_many_by(
                    array(
                        'name like ' => '%' . $tsearch . '%',
                        'status' => 1
                    )
                );
                if ($users) {
                    foreach ($users as $user) {
                        $where['user_id'][] = $user->id;
                    }
                } else {
                    $where['user_id'][] = 0;
                }
            } else {
                if (preg_match_all('/[A-Za-z]/', $tsearch) == 1) {
                    $id_number = $this->user_model->get_many_by(
                        array(
                            'id_number  like' => '%' . $tsearch . '%',
                            'status' => 1
                        )
                    );
                    if ($id_number) {
                        foreach ($id_number as $k => $v) {
                            $where['user_id'][] = $v->id;
                        }
                    } else {
                        $where['user_id'][] = 0;
                    }
                } elseif (preg_match_all('/\D/', $tsearch) == 0) {
                    $where['user_id'] = $tsearch;
                } else {
                    $where['target_no like'] = '%' . $tsearch . '%';
                }
            }
        }

        !isset($where['status']) && count($where) != 0 || isset($where['status']) && $where['status'] == '5,10' ? $where['status'] = [TARGET_REPAYMENTING, TARGET_REPAYMENTED] : '';
        if (isset($where['status']) && $where['status'] == 99) {
            unset($where['status']);
        }

        if (!empty($where) || isset($input['status']) && $input['status'] == 99) {
            isset($input['sdate']) && $input['sdate'] != '' ? $where['created_at >='] = strtotime($input['sdate']) : '';
            isset($input['edate']) && $input['edate'] != '' ? $where['created_at <='] = strtotime($input['edate']) : '';
            $list = $this->target_model->get_list($where);
            $tmp = [];
            if ($list) {
                $this->load->model('user/user_meta_model');
                $this->load->model('user/user_certification_model');
                foreach ($list as $key => $value) {
                    if ($this->isJson($value->reason)) {
                        $reasonJson = json_decode($value->reason, true);
                        $value->reason = sprintf("原因: %s, 敘述: %s", $reasonJson["reason"], $reasonJson["reason_description"]);
                    }

                    if ($value->status == 2 || $value->status == 23 && $value->sub_status == 0) {
                        if (!isset($tmp[$value->user_id]['bank_account_verify'])) {
                            $bank_account = $this->user_bankaccount_model->get_by(
                                array(
                                    'user_id' => $value->user_id,
                                    'investor' => 0,
                                    'status' => 1,
                                    'verify' => 1,
                                )
                            );
                            $tmp[$value->user_id]['bank_account_verify'] = $bank_account ? 1 : 0;
                        }
                        $list[$key]->bank_account_verify = $tmp[$value->user_id]['bank_account_verify'];
                    }

                    if ($value->status == TARGET_FAIL && $value->remark != '系統自動取消') {
                        $value->credit_sheet_reviewer = $value->fail_target_reviewer;
                    }

                    if (!isset($tmp[$value->user_id]['school']) || !isset($tmp[$value->user_id]['company'])) {
                        $get_meta = $this->user_meta_model->get_many_by([
                            'meta_key' => ['school_name', 'school_department', 'job_company'],
                            'user_id' => $value->user_id,
                        ]);
                        if ($get_meta) {
                            foreach ($get_meta as $svalue) {
                                $svalue->meta_key == 'school_name' ? $tmp[$svalue->user_id]['school']['school_name'] = $svalue->meta_value : '';
                                $svalue->meta_key == 'school_department' ? $tmp[$svalue->user_id]['school']['school_department'] = $svalue->meta_value : '';
                                $svalue->meta_key == 'job_company' ? $tmp[$svalue->user_id]['company'] = $svalue->meta_value : '';
                            }
                        }
                    }
                    if (isset($tmp[$value->user_id]['school']['school_name'])) {
                        $list[$key]->school_name = $tmp[$value->user_id]['school']['school_name'];
                        $list[$key]->school_department = $tmp[$value->user_id]['school']['school_department'];
                    }

                    isset($tmp[$value->user_id]['company']) ? $list[$key]->company = $tmp[$value->user_id]['company'] : '';

                    // add diploma
                    $diploma_cert = $this->user_certification_model->get_by([
                        'user_id' => $value->user_id,
                        'certification_id' => CERTIFICATION_DIPLOMA,
                        'status' => CERTIFICATION_STATUS_SUCCEED,
                    ]);

                    if (!empty($diploma_cert)) {
                        $system = json_decode($diploma_cert->content)->system;
                        $value->diploma = $this->config->item('school_system')[$system];
                    } else {
                        $value->diploma = '';
                    }

                    // only show for corresponding product
                    if ($value->product_id == PRODUCT_ID_STUDENT) {
                        $list[$key]->company = '';
                        $list[$key]->diploma = '';
                    }

                    if ($value->product_id == PRODUCT_ID_SALARY_MAN) {
                        $list[$key]->school_name = '';
                    }

                    $amortization_table = $this->target_lib->get_amortization_table($value);
                    $list[$key]->remaining_principal = $amortization_table['remaining_principal'];

                    $limit_date = $value->created_at + (TARGET_APPROVE_LIMIT * 86400);
                    $credit = $this->credit_model->order_by('created_at', 'desc')->get_by([
                        'product_id' => $value->product_id,
                        'user_id' => $value->user_id,
                        'created_at <=' => $limit_date,
                    ]);
                    if ($credit) {
                        $list[$key]->credit = $credit;
                    }

                    // 可動用額度，需要同產品(product_id)、同期間(instalment)
                    $this->load->library('credit_lib');
                    $remain_amount = $this->credit_lib->get_remain_amount($value->user_id, $value->product_id, $value->sub_product_id);
                    $list[$key]->remain_amount = $remain_amount['instalment'] == $value->instalment ? $remain_amount['user_available_amount'] : '-';
                    $list[$key]->review_by = isset($value->credit_sheet_reviewer) ? '人工' : '系統';
                }
            }
        }

        // response
        $product_list = $this->config->item('product_list');
        $sub_product_list = $this->config->item('sub_product_list');
        $instalment_list = $this->config->item('instalment');
        $repayment_type = $this->config->item('repayment_type');
        $status_list = $this->target_model->status_list;
        $delay_list = $this->target_model->delay_list;
        if (isset($input['export']) && $input['export'] == 1) {
            header('Content-type:application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename=All_targets_' . date('Ymd') . '.xls');
            $html = '<table><thead>
                    <tr><th>案號</th><th>產品</th><th>會員ID</th><th>新舊戶</th><th>信評</th><th>學校</th><th>公司</th><th>最高學歷</th>
                    <th>科系</th><th>是否完成實名驗證</th><th>申請金額</th><th>核准金額</th><th>動用金額</th><th>可動用額度</th><th>本金餘額</th>
                    <th>年化利率</th><th>期數</th><th>還款方式</th><th>放款日期</th><th>逾期狀況</th><th>逾期天數</th>
                    <th>狀態</th><th>借款原因</th><th>申請日期</th><th>申請時間</th><th>核准日期</th><th>核准時間</th>
                    <th>邀請碼</th><th>備註</th></tr>
                    </thead><tbody>';

            if (isset($list) && !empty($list)) {
                $this->load->model('user/user_certification_model');
                $targetIds = array_column($list, 'id');

                $where = ['investor' => USER_BORROWER, 'status' => CERTIFICATION_STATUS_SUCCEED];
                if (isset($input['edate']) && !empty($input['edate']) && strtotime($input['edate']))
                    $where['updated_at <= '] = strtotime($input['edate']);
                $userCertList = $this->user_certification_model->getCertificationsByTargetId($targetIds, $where);

                $subloan_list = $this->config->item('subloan_list');
                foreach ($list as $key => $value) {
                    $user_status = $this->target_model->get_old_user([$value->user_id], $value->created_at);
                    $user_status = array_column($user_status, 'user_from', 'user_from');

                    // 撈取可動用額度
                    $this->load->library('credit_lib');
                    $remain_amount = $this->credit_lib->get_remain_amount($value->user_id, $value->product_id, $value->sub_product_id);

                    $html .= '<tr>';
                    $html .= '<td>' . $value->target_no . '</td>';
                    $html .= '<td>' . $product_list[$value->product_id]['name'] . ($value->sub_product_id != 0 ? '/' . $sub_product_list[$value->sub_product_id]['identity'][$product_list[$value->product_id]['identity']]['name'] : '') . (preg_match('/' . $subloan_list . '/', $value->target_no) ? '(產品轉換)' : '') . '</td>';
                    $html .= '<td>' . $value->user_id . '</td>';
                    $html .= '<td>' . (isset($user_status[$value->user_id]) ? '舊戶' : '新戶') . '</td>';
                    $html .= '<td>' . $value->credit_level . '</td>';
                    $html .= '<td>' . ($value->school_name ?? '') . '</td>';
                    $html .= '<td>' . ($value->company ?? '') . '</td>';
                    $html .= '<td>' . ($value->diploma ?? '') . '</td>';
                    $html .= '<td>' . (isset($value->school_department) ? $value->school_department : '') . '</td>';
                    $html .= '<td>' . (isset($userCertList[$value->user_id]) && isset($userCertList[$value->user_id][CERTIFICATION_IDENTITY]) ? "是" : "否") . '</td>';
                    $html .= '<td>' . $value->amount . '</td>';
                    $html .= '<td>' . (isset($value->credit->amount) ? $value->credit->amount : '') . '</td>';
                    $html .= '<td>' . $value->loan_amount . '</td>';
                    $html .= '<td>' . ($remain_amount['instalment'] == $value->instalment ? $remain_amount['user_available_amount'] : '-') . '</td>'; // 可動用額度
                    $html .= '<td>' . $value->remaining_principal . '</td>';
                    $html .= '<td>' . floatval($value->interest_rate) . '</td>';
                    $html .= '<td>' . $instalment_list[$value->instalment] . '</td>';
                    $html .= '<td>' . $repayment_type[$value->repayment] . '</td>';
                    $html .= '<td>' . $value->loan_date . '</td>';
                    $html .= '<td>' . $delay_list[$value->delay] . '</td>';
                    $html .= '<td>' . intval($value->delay_days) . '</td>';
                    $html .= '<td>' . $status_list[$value->status] . '</td>';
                    $html .= '<td>' . $value->reason . '</td>';
                    $html .= '<td>' . date("Y-m-d", $value->created_at) . '</td>';
                    $html .= '<td>' . date("H:i:s", $value->created_at) . '</td>';
                    $html .= '<td>' . (isset($value->credit->created_at) ? date("Y-m-d", $value->credit->created_at) : '') . '</td>';
                    $html .= '<td>' . (isset($value->credit->created_at) ? date("H:i:s", $value->credit->created_at) : '') . '</td>';
                    $html .= '<td>' . $value->promote_code . '</td>';
                    $html .= '<td>' . $value->remark . '</td>';
                    $html .= '</tr>';
                }
            }
            $html .= '</tbody></table>';
            echo $html;
        } else {
            $page_data['product_list'] = $product_list;
            $page_data['sub_product_list'] = $sub_product_list;
            $page_data['instalment_list'] = $instalment_list;
            $page_data['repayment_type'] = $repayment_type;
            $page_data['list'] = $list;
            $page_data['status_list'] = $status_list;
            $page_data['delay_list'] = $delay_list;
            $page_data['subloan_list'] = $this->config->item('subloan_list');
            $page_data['name_list'] = $this->admin_model->get_name_list();

            $this->load->view('admin/_header');
            $this->load->view('admin/_title', $this->menu);
            $this->load->view('admin/target/targets_list', $page_data);
            $this->load->view('admin/_footer');
        }
    }

    private function _get_delayed_report_by_target($input)
    {
        $this->load->library('credit_lib');
        $this->load->library('target_lib');
        $this->load->model('user/user_certification_model');

        $instalment_list = $this->config->item('instalment');
        $repayment_type = $this->config->item('repayment_type');
        $status_list = $this->target_model->status_list;
        $delay_list = $this->target_model->delay_list;
        $product_list = $this->config->item('product_list');

        $list = $this->target_model->get_delayed_report_by_target($input);

        $target_ids = array_column($list, 'id');

        $where = ['investor' => USER_BORROWER, 'status' => 1];
        if (!empty($input['edate']) && strtotime($input['edate'])) {
            $where['updated_at <= '] = strtotime($input['edate']);
        }
        $user_cert_list = $this->user_certification_model->getCertificationsByTargetId($target_ids, $where);

        return array_map(function ($element) use ($user_cert_list, $instalment_list, $repayment_type, $status_list, $delay_list, $product_list) {
            $amortization_table = $this->target_lib->get_amortization_table($element);
            $element['remaining_principal'] = $amortization_table['remaining_principal'];

            $remain_amount = $this->credit_lib->get_remain_amount($element['user_id'], $element['product_id'], $element['sub_product_id']);
            $element['user_available_amount'] = $remain_amount['instalment'] == $element['instalment'] ? $remain_amount['user_available_amount'] : '-';

            $element['instalment'] = $instalment_list[$element['instalment']] ?? '';
            $element['repayment'] = $repayment_type[$element['repayment']] ?? '';
            $element['delay'] = $delay_list[$element['delay']] ?? '';
            $element['status'] = $status_list[$element['status']] ?? '';
            $element['product_name'] = $product_list[$element['product_id']]['name'] ?? '';

            $user_status = array_column(
                $this->target_model->get_old_user([$element['user_id']], $element['created_at']),
                'user_from',
                'user_from'
            );
            $element['new_or_old'] = isset($user_status[$element['user_id']]) ? '舊戶' : '新戶';
            $element['finish_cert_identity'] = !empty($user_cert_list[$element['user_id']][CERTIFICATION_IDENTITY]) ? '是' : '否';
            if (!empty($element['created_at'])) {
                $element['target_created_date'] = date('Y-m-d', $element['created_at']);
                $element['target_created_time'] = date('H:i:s', $element['created_at']);
            } else {
                $element['target_created_date'] = '';
                $element['target_created_time'] = '';
            }
            if (!empty($element['credit_created_at'])) {
                $element['credit_created_date'] = date('Y-m-d', $element['credit_created_at']);
                $element['credit_created_time'] = date('H:i:s', $element['credit_created_at']);
            } else {
                $element['credit_created_date'] = '';
                $element['credit_created_time'] = '';
            }
            if ($this->isJson($element['reason'])) {
                $reason_json = json_decode($element['reason'], TRUE);
                $element['reason'] = sprintf("原因: %s, 敘述: %s", $reason_json["reason"], $reason_json["reason_description"]);
            }
            return $element;
        }, $list);
    }

    public function edit()
    {
        $page_data = array('type' => 'edit');
        $get = $this->input->get(NULL, TRUE);
        $post = $this->input->post(NULL, TRUE);
        $sub_product_list = $this->config->item('sub_product_list');


        $id = isset($get['id']) ? intval($get['id']) : 0;
        $display = isset($get['display']) ? intval($get['display']) : 0;
        if (empty($post)) {
            if ($id) {
                $delay_list = $this->target_model->delay_list;
                $info = $this->target_model->get($id);
                if ($info) {
                    $this->load->library('Contract_lib');
                    $amortization_table = [];
                    $investments = [];
                    $investments_amortization_table = [];
                    $investments_amortization_schedule = [];
                    $order = [];
                    if ($info->status == TARGET_REPAYMENTING || $info->status == TARGET_REPAYMENTED) {
                        $amortization_table = $this->target_lib->get_amortization_table($info);
                        $investments = $this->investment_model->get_many_by(array('target_id' => $info->id, 'status' => array(3, 10)));
                        if ($investments) {
                            foreach ($investments as $key => $value) {
                                $investments[$key]->contract = $this->contract_lib->get_contract($value->contract_id)['content'];
                                $investments[$key]->user_info = $this->user_model->get($value->user_id);
                                $investments[$key]->virtual_account = $this->virtual_account_model->get_by(
                                    array(
                                        'user_id' => $value->user_id,
                                        'investor' => 1,
                                        'status' => 1,
                                    )
                                );
                                $investments_amortization_table[$value->id] = $this->target_lib->get_investment_amortization_table($info, $value);
                            }
                        }
                    } else if ($info->status == TARGET_WAITING_LOAN) {
                        $investments = $this->investment_model->get_many_by(array('target_id' => $info->id, 'status' => 2));
                        if ($investments) {
                            foreach ($investments as $key => $value) {
                                $investments[$key]->contract = $this->contract_lib->get_contract($value->contract_id)['content'];
                                $investments[$key]->user_info = $this->user_model->get($value->user_id);
                                $investments[$key]->virtual_account = $this->virtual_account_model->get_by(
                                    array(
                                        'user_id' => $value->user_id,
                                        'investor' => 1,
                                        'status' => 1,
                                    )
                                );
                                $investments_amortization_schedule[$value->id] = $this->financial_lib->get_amortization_schedule(
                                    $value->loan_amount,
                                    $info,
                                    date('Y-m-d')
                                );
                            }
                        }
                    }
                    $judicial_person = '';
                    if ($info->order_id != 0) {
                        $this->load->model('transaction/order_model');
                        $order = $this->order_model->get($info->order_id);
                        $store_id = explode('-', $order->order_no)[0];
                        $this->load->model('user/cooperation_model');
                        $cooperation = $this->cooperation_model->get($store_id);
                        $this->load->model('user/judicial_person_model');
                        $judicial_person = $this->judicial_person_model->get_by([
                            'company_user_id' => $cooperation->company_user_id
                        ]);
                    }

                    $user_id = $info->user_id;
                    $bank_account = $this->user_bankaccount_model->get_many_by(
                        array(
                            'user_id' => $user_id,
                            'investor' => 0,
                            'status' => 1,
                            'verify' => 1,
                        )
                    );

                    $reason = $info->reason;
                    $json_reason = json_decode($reason);
                    if (isset($json_reason->reason)) {
                        $reason = $json_reason->reason . ' - ' . $json_reason->reason_description;
                    }

                    $target_data = $info->target_data;
                    $json_target_data = json_decode($target_data);
                    if (isset($json_target_data->autoVerifyLog)) {
                        $page_data['autoVerifyLog'] = $json_target_data->autoVerifyLog;
                    }

                    $bank_account_verify = $bank_account ? 1 : 0;
                    $credit_list = $this->credit_model->get_many_by(array('user_id' => $user_id));
                    $user_info = $this->user_model->get($user_id);

                    $virtual_accounts = $this->virtual_account_model->get_many_by([
                        'user_id' => $user_id,
                        'investor' => 0,
                    ]);

                    if ($info->sub_status == TARGET_SUBSTATUS_LAW_DEBT_COLLECTION) {
                        $lawAccount = CATHAY_VIRTUAL_CODE . LAW_VIRTUAL_CODE . substr($user_info->id_number, 1, 9);
                        $page_data['lawAccount'] = $lawAccount;
                        $targetData = json_decode($info->target_data);
                        if (isset($targetData->legalAffairs)) {
                            $page_data['legalAffairs'] = $targetData->legalAffairs;
                        }
                    }

                    $page_data['sub_product_list'] = $sub_product_list;
                    $page_data['data'] = $info;
                    $page_data['reason'] = $reason;
                    $page_data['order'] = $order;
                    $page_data['user_info'] = $user_info;
                    $page_data['judicial_person'] = $judicial_person;
                    $page_data['amortization_table'] = $amortization_table;
                    $page_data['investments'] = $investments;
                    $page_data['delivery_list'] = $this->target_model->delivery_list;
                    $page_data['investments_amortization_table'] = $investments_amortization_table;
                    $page_data['investments_amortization_schedule'] = $investments_amortization_schedule;
                    $page_data['credit_list'] = $credit_list;
                    $page_data['product_list'] = $this->config->item('product_list');
                    $page_data['sub_product_list'] = $this->config->item('sub_product_list');
                    $page_data['bank_account_verify'] = $bank_account_verify;
                    $page_data['virtual_accounts'] = $virtual_accounts;
                    $page_data['instalment_list'] = $this->config->item('instalment');
                    $page_data['repayment_type'] = $this->config->item('repayment_type');
                    $page_data['delay_list'] = $delay_list;
                    $page_data['status_list'] = $this->target_model->status_list;
                    $page_data['loan_list'] = $this->target_model->loan_list;

                    if (isset($get['risk']) && $get['risk'] != null) {
                        $this->load->library('certification_lib');
                        if (isset($get['slist']) && $get['slist'] != null) {
                            $page_data['slist'] = $get['slist'];
                        }

                        $user_list = [];
                        $user_investor_list = [];
                        $certification_investor_list = [];

                        $targets = $this->target_model->get_many_by(
                            array(
                                'user_id' => $user_id,
                                'id' => $info->id
                            )
                        );
                        if ($targets) {
                            foreach ($targets as $key => $value) {
                                $list[$value->id] = $value;
                            }
                        }

                        if ($list) {
                            ksort($list);
                            foreach ($list as $key => $value) {
                                $list[$key]->certification = $this->certification_lib->get_last_status($value->user_id, BORROWER);
                                if (isset($list[$key]->certification[3]['certification_id'])) {
                                    $bank_account = $this->user_bankaccount_model->get_by(
                                        array(
                                            'user_certification_id' => $list[$key]->certification[3]['certification_id'],
                                        )
                                    );
                                    $list[$key]->bank_account = $bank_account;
                                    $list[$key]->bank_account_verify = $bank_account->verify == 1 ? 1 : 0;
                                }
                            }
                        }

                        $page_data['list'] = $list;
                        $page_data['certification_investor_list'] = $certification_investor_list;
                        $page_data['certification'] = $this->config->item('certifications');
                        $this->load->view('admin/risk/risk_targets_edit', $page_data);
                    } else {
                        if (!$display) {
                            $this->load->view('admin/_title', $this->menu);
                        }
                        $this->load->view('admin/target/targets_edit', $page_data);
                    }

                    $this->load->view('admin/_header');
                    $this->load->view('admin/_footer');

                } else {
                    alert('ERROR , id is not exist', admin_url('target/index'));
                }
            } else {
                alert('ERROR , id is not exist', admin_url('target/index'));
            }
        } else {
            if (!empty($post['id'])) {
                $id = $post['id'];
                $targets = $this->target_model->get($id);
                $param = [
                    'status' => $targets->status,
                    'loan_amount' => $targets->amount,
                    'sub_status' => 0,
                    'remark' => '核可消費額度',
                ];
                $this->target_model->update($id, $param);
                $this->load->library('Target_lib');
                $this->target_lib->insert_change_log($id, $param);
                alert('額度已提升', admin_url('Target/waiting_verify'));
            }
        }
    }

    function verify_success()
    {
        $get = $this->input->get(NULL, TRUE);
        $id = isset($get['id']) ? intval($get['id']) : 0;
        if ($id) {
            $info = $this->target_model->get($id);
            if ($info && in_array($info->status, array(TARGET_WAITING_VERIFY, TARGET_ORDER_WAITING_SHIP))) {
                if ($this->target_lib->is_sub_loan($info->target_no)) {
                    $this->load->library('subloan_lib');
                    $this->subloan_lib->subloan_verify_success($info, $this->login_info->id);
                }
                if ($info->status == TARGET_ORDER_WAITING_SHIP && $info->sub_status == TARGET_SUBSTATUS_NORNAL) {
                    $this->target_lib->order_verify_success($info, $this->login_info->id);
                } else {
                    $this->target_lib->target_verify_success($info, $this->login_info->id);
                }
                echo '更新成功';
                die();
            } else {
                echo '查無此ID';
                die();
            }
        } else {
            echo '查無此ID';
            die();
        }
    }

    function verify_failed()
    {
        $get = $this->input->get(NULL, TRUE);
        $id = isset($get['id']) ? intval($get['id']) : 0;
        $remark = $get['remark'] ?? '';
        if ($id) {
            $target = $this->target_model->get($id);
            if ($this->target_lib->reject($target, $this->login_info->id, $remark)) {
                if (
                    $target->product_id == 5 &&
                    in_array($target->sub_product_id, [SUB_PRODUCT_ID_HOME_LOAN_SHORT, SUB_PRODUCT_ID_HOME_LOAN_RENOVATION, SUB_PRODUCT_ID_HOME_LOAN_APPLIANCES])
                ) {
                    $cancel_booking_result = $this->target_lib->cancel_booking_and_certification($target->user_id);
                    if (!$cancel_booking_result) {
                        echo '更新失敗，取消預約時間失敗';
                        die();
                    }
                }
                echo '更新成功';
            } else {
                echo '更新失敗';
            }
        } else {
            echo '查無此ID';
        }
        die();
    }

    function order_fail()
    {
        $get = $this->input->get(NULL, TRUE);
        $id = isset($get['id']) ? intval($get['id']) : 0;
        $remark = isset($get['remark']) ? $get['remark'] : '';
        if ($id) {
            $info = $this->target_model->get($id);
            if ($info && $info->status == TARGET_ORDER_WAITING_SIGNING) {
                $this->load->library('subloan_lib');
                $this->target_lib->order_fail($info, $this->login_info->id, $remark);
                echo '更新成功';
                die();
            } else {
                echo '更新失敗';
                die();
            }
        } else {
            echo '查無此ID';
            die();
        }
    }

    public function waiting_verify()
    {
        $page_data = array('type' => 'list');
        $input = $this->input->get(NULL, TRUE);
        $where = array('status' => [TARGET_WAITING_VERIFY, TARGET_ORDER_WAITING_SHIP]);
        $fields = ['target_no', 'user_id', 'delay'];
        $subloan_keyword = $this->config->item('action_Keyword')[0];

        foreach ($fields as $field) {
            if (isset($input[$field]) && $input[$field] != '') {
                $where[$field] = $input[$field];
            }
        }
        $this->load->library('target_lib');
        $tab = $input['tab'] ?? '';
        $filter_product_ids = $this->target_lib->get_product_id_by_tab($tab);
        if (!empty($filter_product_ids)) {
            $where['product_id'] = $filter_product_ids;
            if ($tab == 'enterprise') {
                $where['status'] = [TARGET_WAITING_VERIFY, TARGET_BANK_VERIFY];
            }
        }

        $waiting_list = array();
        $list = $this->target_model->get_many_by($where);
        if ($list) {
            foreach ($list as $key => $value) {
                if (
                    $value->status == TARGET_WAITING_VERIFY && $value->sub_status != TARGET_SUBSTATUS_WAITING_TRANSFER_INTERNAL
                    || $value->status == TARGET_WAITING_VERIFY && $value->sub_status == TARGET_SUBSTATUS_SECOND_INSTANCE
                    || $value->status == TARGET_ORDER_WAITING_SHIP && ($value->sub_status == TARGET_SUBSTATUS_NORNAL || $value->sub_status == TARGET_SUBSTATUS_SECOND_INSTANCE)
                ) {
                    $bank_account = $this->user_bankaccount_model->get_by(
                        array(
                            'user_id' => $value->user_id,
                            'investor' => 0,
                            'status' => 1,
                            'verify' => 1,
                        )
                    );


                    $value->subloan_count = count(
                        $this->target_model->get_many_by(
                            array(
                                'user_id' => $value->user_id,
                                'status !=' => TARGET_FAIL,
                                'remark like' => '%' . $subloan_keyword . '%'
                            )
                        )
                    );

                    $value->bankaccount_verify = $bank_account ? 1 : 0;

                    $waiting_list[] = $value;
                }
            }
        }
        $page_data['instalment_list'] = $this->config->item('instalment');
        $page_data['repayment_type'] = $this->config->item('repayment_type');
        $page_data['list'] = $waiting_list;
        $page_data['product_list'] = $this->config->item('product_list');
        $page_data['sub_product_list'] = $this->config->item('sub_product_list');
        $page_data['status_list'] = $this->target_model->status_list;
        $page_data['sub_list'] = $this->target_model->sub_list;
        $page_data['delay_list'] = $this->target_model->delay_list;
        $page_data['name_list'] = $this->admin_model->get_name_list();
        $page_data['externalCooperation'] = $this->config->item('externalCooperation');


        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/target/waiting_verify_target', $page_data);
        $this->load->view('admin/_footer');
    }

    public function credits()
    {
        $get = $this->input->get(NULL, TRUE);
        if (!$this->input->is_ajax_request()) {
            alert('ERROR, 只接受Ajax', admin_url('user/blocked_users'));
        }

        $targetId = isset($get["id"]) ? intval($get["id"]) : 0;
        $points = isset($get["points"]) ? intval($get["points"]) : 0;
        $fixed_amount = isset($get['fixed_amount']) ? (int) $get['fixed_amount'] : 0;

        $this->load->library('output/json_output');
        $target = $this->target_model->get($targetId);

        if (!$target) {
            $this->json_output->setStatusCode(404)->send();
        }

        $this->load->library('credit_lib');

        $userId = $target->user_id;
        $credit = $this->credit_lib->get_credit($target->user_id, $target->product_id, $target->sub_product_id);
        $credit["product_id"] = $target->product_id;

        $product_list = $this->config->item('product_list');
        if ($fixed_amount > 0 && ($fixed_amount < $product_list[$target->product_id]['loan_range_s'] || $fixed_amount > $product_list[$target->product_id]['loan_range_e'])) {
            $this->json_output->setStatusMessage('額度調整不符合產品設定');
            $this->json_output->setStatusCode(400)->send();
        }

        $this->load->library('utility/admin/creditapprovalextra', [], 'approvalextra');
        $this->approvalextra->setSkipInsertion(true);
        $this->approvalextra->setExtraPoints($points);
        $this->approvalextra->set_fixed_amount($fixed_amount);
        $special_info_ary = [
            'job_company_taiwan_1000_point' => '',
            'job_company_world_500_point' => '',
            'job_company_medical_institute_point' => '',
            'job_company_public_agency_point' => '',
        ];
        foreach ($special_info_ary as $key => $value) {
            if (isset($get[$key]) && is_numeric($get[$key])) {
                $special_info_ary[$key] = $get[$key];
            } else {
                unset($special_info_ary[$key]);
            }
        }
        $this->approvalextra->setSpecialInfo($special_info_ary);

        $level = false;
        if ($target->product_id == 3 && $target->sub_product_id == STAGE_CER_TARGET) {
            $this->load->library('Certification_lib');
            $certification = $this->certification_lib->get_certification_info($userId, 8, 0);
            $certificationStatus = isset($certification) && $certification
                ? ($certification->status == 1 ? true : false)
                : false;
            $level = $certificationStatus ? 3 : 4;
        }
        $newCredits = $this->credit_lib->approve_credit($userId, $target->product_id, $target->sub_product_id, $this->approvalextra, $level, false, false, $target->instalment, $target);
        $this->load->model('user/user_meta_model');
        $info = $this->user_meta_model->get_by(['user_id' => $target->user_id, 'meta_key' => 'school_name']);
        if (isset($info->meta_value) && in_array($target->product_id, [PRODUCT_ID_STUDENT, PRODUCT_ID_STUDENT_ORDER])) {
            $school_config = $this->config->item('school_points');
            if (in_array($info->meta_value, $school_config['lock_school'])) {
                $this->credit_lib->get_lock_school_amount($target->product_id, $target->sub_product_id, $newCredits);
            }
        }

        $credit["amount"] = (int) $newCredits["amount"];
        $message = "";
        if ($fixed_amount == 0) {
            $past_targets = $this->target_model->get_many_by([
                'user_id' => $userId,
                'status' => [5, 10],
            ]);
            $is_new_user = count($past_targets) == 0;
            // Todo: “新戶” (無申貸成功紀錄者) 且薪水四萬以下,
            if ($is_new_user) {
                $certification = $this->user_certification_model->get_by(['user_id' => $userId, 'certification_id' => 15]);
                if (isset($certification) && $certification->status == 1) {
                    $content = json_decode($certification->content);
                    if (
                        isset($content->monthly_repayment) && isset($content->total_repayment)
                        && is_numeric($content->monthly_repayment) && is_numeric($content->total_repayment)
                    ) {
                        $liabilitiesWithoutAssureTotalAmount = $content->liabilitiesWithoutAssureTotalAmount ?? 0;
                        $product_id = $target->product_id;
                        // 上班族貸款
                        if (in_array($product_id, [3, 4])) {
                            $product = $this->config->item('product_list')[$product_id];
                            if ($product['condition_rate']['salary_below'] > $content->monthly_repayment * 1000) {
                                $credit["amount"] = $target->loan_amount;
                                if ($liabilitiesWithoutAssureTotalAmount > $content->total_repayment * 1000) {
                                    $message = "該會員薪資低於4萬，負債大於22倍，系統給定信用額度為0~3000元；若需調整請至「額度調整 1000~20000」之欄位填寫額度";
                                } else {
                                    $message = "該會員薪資低於4萬，負債小於22倍，系統給定信用額度為3000~10000元；若需調整請至「額度調整 1000~20000」之欄位填寫額度";
                                }
                            }
                        }
                    } else {
                        if (!is_numeric($content->monthly_repayment) || !is_numeric($content->total_repayment)) {
                            $message = '還款力計算結果資料類型不正確' .
                                ', monthly_repayment: ' . $content->monthly_repayment .
                                ', total_repayment: ' . $content->total_repayment;

                            log_message(
                                'info',
                                $message .
                                ', target_id: ' . $target->id .
                                ', certification: ' . $certification->id
                            );
                        }
                    }
                }
            }
        }

        $credit["points"] = $newCredits["points"];
        $credit["level"] = $newCredits["level"];
        $credit["expire_time"] = $newCredits["expire_time"];

        $product = $product_list[$target->product_id];
        if ($this->is_sub_product($product, $target->sub_product_id)) {
            $credit['sub_product_id'] = $target->sub_product_id;
            $credit['sub_product_name'] = $this->trans_sub_product($product, $target->sub_product_id)['name'];
        }
        $this->load->library('output/loan/credit_output', ["data" => $credit]);

        $response = [
            "credits" => $this->credit_output->toOne(),
            "message" => $message,
        ];
        $this->json_output->setStatusCode(200)->setResponse($response)->send();
    }

    public function evaluation_approval()
    {
        $post = $this->input->post(NULL, TRUE);
        $newCredits = false;

        $targetId = isset($post["id"]) ? intval($post["id"]) : 0;
        $points = isset($post["points"]) ? intval($post["points"]) : 0;
        $remark = isset($post["reason"]) ? strval($post["reason"]) : false;

        if ($points > 400)
            $points = 400;
        if ($points < -400)
            $points = -400;

        $this->load->library('output/json_output');

        $target = $this->target_model->get($targetId);
        if (!$target) {
            $this->json_output->setStatusCode(404)->send();
        }

        if ($target->status != 0 && $target->sub_status != 9) {
            $this->json_output->setStatusCode(404)->send();
        }

        $userId = $target->user_id;
        $credit = $this->credit_model->get_by([
            'user_id' => $userId,
            'product_id' => $target->product_id,
            'sub_product_id' => $target->sub_product_id,
            'status' => 1
        ]);

        if ($target->sub_product_id != STAGE_CER_TARGET || $target->product_id == 3) {
            $this->load->library('utility/admin/creditapprovalextra', [], 'approvalextra');
            $this->approvalextra->setSkipInsertion(true);
            $this->approvalextra->setExtraPoints($points);

            $level = false;
            if ($target->product_id == 3 && $target->sub_product_id == STAGE_CER_TARGET) {
                $this->load->library('Certification_lib');
                $certification = $this->certification_lib->get_certification_info($userId, 8, 0);
                $certificationStatus = isset($certification) && $certification
                    ? ($certification->status == 1 ? true : false)
                    : false;
                $level = $certificationStatus ? 3 : 4;
            }
            $this->load->library('credit_lib');
            $newCredits = $this->credit_lib->approve_credit($userId, $target->product_id, $target->sub_product_id, $this->approvalextra, $level, false, false, $target->instalment, $target);
        }

        $remark = (empty($target->remark) ? $remark : $target->remark . ', ' . $remark);

        if (
            $newCredits &&
            ($newCredits["amount"] != $credit->amount
                || $newCredits["points"] != $credit->points
                || $newCredits["level"] != $credit->level)
        ) {
            $this->credit_model->update_by(
                [
                    'user_id' => $userId,
                    'product_id' => $target->product_id,
                    'sub_product_id' => $target->sub_product_id,
                    'status' => 1,
                ],
                ['status' => 0]
            );
            $this->credit_model->insert($newCredits);
        }

        if ($target->sub_product_id == STAGE_CER_TARGET && $target->product_id == 1) {
            $param['status'] = 1;
            $param['sub_status'] = 10;
            $param['remark'] = $remark;
            $this->target_model->update($target->id, $param);
        } else {
            $this->target_lib->approve_target($target, $remark, true);
        }
        $this->json_output->setStatusCode(200)->send();
    }

    public function final_validations()
    {
        $get = $this->input->get(NULL, TRUE);

        $targetId = isset($get["id"]) ? intval($get["id"]) : 0;

        if ($this->input->is_ajax_request()) {
            $this->load->library('output/json_output');

            $target = $this->target_model->get($targetId);
            if (!$target || $target->id <= 0) {
                $this->json_output->setStatusCode(404)->send();
            }
            if ($target->status != 0 && $target->sub_status != 9) {
                $this->json_output->setStatusCode(404)->send();
            }

            $product_list = $this->config->item('product_list');
            $product = $product_list[$target->product_id];
            $sub_product_id = $target->sub_product_id;
            $target->productTargetData = $product;

            $this->config->load('credit', TRUE);
            $get_creditTargetData = $this->config->item('credit')['creditTargetData'];
            $target->creditTargetData = isset($get_creditTargetData[$target->product_id][$sub_product_id]) ? $get_creditTargetData[$target->product_id][$sub_product_id] : false;

            $this->load->library('output/loan/target_output', ['data' => $target], 'current_target_output');

            $userId = $target->user_id;
            $user = $this->user_model->get($userId);

            $userMeta = $this->user_meta_model->get_many_by(['user_id' => $userId]);
            $this->load->library('credit_lib');
            $credits = $this->credit_lib->get_credit($userId, $target->product_id, $target->sub_product_id);
            $credits["product_id"] = $target->product_id;

            $this->load->model('user/user_certification_model');
            $schoolCertificationDetail = $this->user_certification_model->get_by([
                'user_id' => $userId,
                'certification_id' => 2,
                'status' => 1,
            ]);

            if ($this->is_sub_product($product, $sub_product_id)) {
                $getSubProduct = $this->trans_sub_product($product, $sub_product_id);
                $target->productTargetData = $getSubProduct;
                $credits['sub_product_id'] = $sub_product_id;
                $credits['sub_product_name'] = $getSubProduct['name'];
            }

            if ($user->company_status == 0) {
                $schoolCertificationDetailArray = $schoolCertificationDetail ? json_decode($schoolCertificationDetail->content, true) : false;
                if (isset($schoolCertificationDetailArray["graduate_date"])) {
                    $graduateDate = new stdClass();
                    $graduateDate->meta_key = "school_graduate_date";
                    $graduateDate->meta_value = $schoolCertificationDetailArray["graduate_date"];
                    $userMeta[] = $graduateDate;
                }

                // Spouse info
                $identity_cert = $this->user_certification_model->get_by([
                    'user_id' => $userId,
                    'certification_id' => CERTIFICATION_IDENTITY,
                    'status' => CERTIFICATION_STATUS_SUCCEED,
                    'investor' => USER_BORROWER
                ]);
                if ($identity_cert) {
                    $identity_content = json_decode($identity_cert->content, TRUE);
                    if (isset($identity_content['hasSpouse']) && $identity_content['hasSpouse']) {
                        $has_spouse = TRUE;
                    } elseif (isset($identity_content['SpouseName']) && $identity_content['SpouseName']) {
                        $has_spouse = TRUE;
                    }
                }

                $this->load->library('mapping/user/usermeta', ["data" => $userMeta]);

                $instagramCertificationDetail = $this->user_certification_model->get_by([
                    'user_id' => $userId,
                    'certification_id' => 4,
                    'status' => 1,
                ]);

                $instagramCertificationDetailArray = isset($instagramCertificationDetail->content) &&
                    is_array(json_decode($instagramCertificationDetail->content, true))
                    ? json_decode($instagramCertificationDetail->content, true) : [];
                if (isset($instagramCertificationDetailArray["type"]) && $instagramCertificationDetailArray["type"] == "instagram") {
                    $picture = $instagramCertificationDetailArray["info"]["picture"];
                    $this->usermeta->setInstagramPicture($picture);
                } else if (isset($instagramCertificationDetailArray['instagram']['picture'])) {
                    $picture = $instagramCertificationDetailArray['instagram']['picture'];
                    $this->usermeta->setInstagramPicture($picture);
                }

                $user->profile = $this->usermeta->values();
                $user->school = $this->usermeta->values();
                $user->instagram = $this->usermeta->values();
                $user->facebook = $this->usermeta->values();
            } elseif ($user->company_status == 1) {
                $this->load->model('user/judicial_person_model');
                $judicial_person = $this->judicial_person_model->get_by([
                    'company_user_id' => $user->id,
                ]);
                $user->judicial_id = $judicial_person ? $judicial_person->id : false;
            }

            $this->load->library('output/user/user_output', ["data" => $user, 'has_spouse' => $has_spouse ?? FALSE]);
            $this->load->library('output/loan/credit_output', ["data" => $credits]);
            $this->load->library('certification_lib');

            $borrowerVerifications = $this->certification_lib->get_last_status($userId, BORROWER, $user->company_status, $target, $target->productTargetData, TRUE, TRUE);
            $investorVerifications = $this->certification_lib->get_last_status($userId, INVESTOR, $user->company_status);
            $verificationInput = ["borrower" => $borrowerVerifications, "investor" => $investorVerifications];
            $this->load->library('output/user/verifications_output', $verificationInput);

            $bankAccount = $this->user_bankaccount_model->get_many_by([
                'user_id' => $userId,
                'status' => 1,
            ]);

            $this->load->library('output/user/Bank_account_output', ['data' => $bankAccount]);

            $virtualAccounts = $this->virtual_account_model->get_many_by([
                'user_id' => $userId,
                'status' => 1,
            ]);

            $this->load->library('Transaction_lib');
            foreach ($virtualAccounts as $virtualAccount) {
                $virtualAccount->funds = $this->transaction_lib->get_virtual_funds($virtualAccount->virtual_account);
            }

            $this->load->library('output/user/Virtual_account_output', ['data' => $virtualAccounts]);

            $targets = $this->target_model->get_targets_with_normal_transactions_count($userId);

            foreach ($targets as $otherTarget) {
                $amortization = $this->target_lib->get_amortization_table($otherTarget);
                $otherTarget->amortization = $amortization;

                $validBefore = $otherTarget->created_at + (TARGET_APPROVE_LIMIT * 86400);
                $credit = $this->credit_model->order_by('created_at', 'desc')->get_by([
                    'product_id' => $otherTarget->product_id,
                    'user_id' => $userId,
                    'created_at <=' => $validBefore,
                ]);
                $otherTarget->credit = $credit;
            }

            $allowed_display_meta = [
                TARGET_META_COMPANY_CATEGORY_NUMBER
            ];

            $target_meta_list = [];
            $target_meta = $this->target_lib->get_meta_list($target->id);
            foreach ($target_meta as $meta) {
                if (in_array($meta['meta_key'], $allowed_display_meta)) {
                    $target_meta_list[] = $meta;
                }
            }

            $product = $this->target_lib->get_product_info($target->product_id, $target->sub_product_id);
            $display_contract_cols = ['contract_name', 'meta_name'];
            $contract_list = array_columns($product['need_upload_images'] ?? [], $display_contract_cols);

            $meta_list_by_key = array_column($userMeta, NULL, 'meta_key');
            $meta_info = $meta_list_by_key['job_company'] ?? new stdclass();
            $job_company = $meta_info->meta_value ?? '';

            $this->load->model('user/user_meta_model');
            $user_meta_list = $this->user_meta_model->as_array()->get_many_by([
                'user_id' => $userId,
                'meta_key' => [
                    'job_company_taiwan_1000_point',
                    'job_company_world_500_point',
                    'job_company_medical_institute_point',
                    'job_company_public_agency_point'
                ],
            ]);
            $user_meta_list = array_column($user_meta_list, 'meta_value', 'meta_key');

            $this->load->config('taiwan_1000');
            $this->load->config('world_500');
            $this->load->config('medical_institute');
            $this->load->config('public_agency');
            $special_list = [
                'job_company' => $job_company,
                'taiwan_1000' => [
                    'list' => array_combine($this->config->item('taiwan_1000'), $this->config->item('taiwan_1000')),
                    'point' => $user_meta_list['job_company_taiwan_1000_point'] ?? '',
                ],
                'world_500' => [
                    'list' => array_combine($this->config->item('world_500'), $this->config->item('world_500')),
                    'point' => $user_meta_list['job_company_world_500_point'] ?? '',
                ],
                'medical_institute' => [
                    'list' => array_combine($this->config->item('medical_institute'), $this->config->item('medical_institute')),
                    'point' => $user_meta_list['job_company_medical_institute_point'] ?? ''
                ],
                'public_agency' => [
                    'list' => array_combine($this->config->item('public_agency'), $this->config->item('public_agency')),
                    'point' => $user_meta_list['job_company_public_agency_point'] ?? '',
                ],
            ];

            $past_targets = $this->target_model->get_many_by([
                'user_id' => $user->id,
                'status' => [5, 10],
            ]);
            // 這裡新戶的算法只要看 是否放款過的用戶
            $is_new_user = count($past_targets) == 0;
            $this->load->library('output/loan/target_output', ['data' => $targets]);
            $response = [
                "target" => $this->current_target_output->toOne(),
                "user" => $this->user_output->toOne(true),
                'new_or_old' => $is_new_user ? '新戶' : '舊戶',
                "credits" => $this->credit_output->toOneWithRemark(),
                "verifications" => $this->verifications_output->toMany(),
                "bank_accounts" => $this->bank_account_output->toMany(),
                "virtual_accounts" => $this->virtual_account_output->toMany(),
                "targets" => $this->target_output->toMany(),
                'target_meta' => $target_meta_list,
                'special_list' => $special_list,
                'contract_list' => $contract_list
            ];

            $this->json_output->setStatusCode(200)->setResponse($response)->send();
        }
        $use_vuejs = true;

        $this->load->view('admin/_header', $data = ['use_vuejs' => true]);
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/target/final_validations');
        $this->load->view('admin/_footer');
    }

    public function waiting_evaluation()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->library('output/json_output');

            $where = ["status" => TARGET_WAITING_APPROVE, "sub_status" => TARGET_SUBSTATUS_SECOND_INSTANCE];

            $this->load->library('target_lib');
            $tab = $this->input->get('tab', TRUE) ?? '';
            $filter_product_ids = $this->target_lib->get_product_id_by_tab($tab);
            if (!empty($filter_product_ids)) {
                $where['product_id'] = $filter_product_ids;
            }

            if ($tab == PRODUCT_TAB_ENTERPRISE) {
                $where['status'] = TARGET_WAITING_VERIFY;
            }

            $targets = $this->target_model->get_many_by($where);
            if (!$targets) {
                $this->json_output->setStatusCode(204)->send();
            }

            $userIds = [];
            $userIndexes = [];
            $index = 0;
            foreach ($targets as $target) {
                $userIds[] = $target->user_id;
                if (!isset($userIndexes[$target->user_id])) {
                    $userIndexes[$target->user_id] = [];
                }
                $userIndexes[$target->user_id][] = $index++;
            }

            $users = $this->user_model->get_many_by(['id' => $userIds]);

            $numTargets = count($targets);
            $userList = array_fill(0, $numTargets, null);
            foreach ($users as $user) {
                $indexes = $userIndexes[$user->id];
                foreach ($indexes as $index) {
                    $userList[$index] = $user;
                }
            }

            $this->load->library('output/loan/target_output', ['data' => $targets]);
            $this->load->library('output/user/user_output', ["data" => $userList]);

            $response = [
                "users" => $this->user_output->toMany(),
                "targets" => $this->target_output->toMany(),
            ];
            $this->json_output->setStatusCode(200)->setResponse($response)->send();
        }

        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/target/waiting_evaluation');
        $this->load->view('admin/_footer');
    }

    public function waiting_loan()
    {
        $page_data = array('type' => 'list');
        $input = $this->input->get(NULL, TRUE);
        $where = array('status' => [TARGET_WAITING_LOAN]);
        $fields = ['target_no', 'user_id', 'delay'];

        foreach ($fields as $field) {
            if (isset($input[$field]) && $input[$field] != '') {
                $where[$field] = $input[$field];
            }
        }
        $waiting_list = array();
        $list = $this->target_model->get_many_by($where);
        if ($list) {
            foreach ($list as $key => $value) {
                if (in_array($value->status, array(4))) {
                    $bank_account = $this->user_bankaccount_model->get_by(
                        array(
                            'user_id' => $value->user_id,
                            'investor' => 0,
                            'status' => 1,
                            'verify' => 1,
                        )
                    );
                    if ($bank_account) {
                        $value->sub_loan_status = $this->target_lib->is_sub_loan($value->target_no);
                        $waiting_list[] = $value;
                    }
                }
            }
        }
        $page_data['instalment_list'] = $this->config->item('instalment');
        $page_data['repayment_type'] = $this->config->item('repayment_type');
        $page_data['list'] = $waiting_list;
        $page_data['product_list'] = $this->config->item('product_list');
        $page_data['sub_product_list'] = $this->config->item('sub_product_list');
        $page_data['status_list'] = $this->target_model->status_list;
        $page_data['loan_list'] = $this->target_model->loan_list;
        $page_data['name_list'] = $this->admin_model->get_name_list();
        $page_data['sub_status_list'] = $this->target_model->sub_list;

        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/target/waiting_loan_target', $page_data);
        $this->load->view('admin/_footer');
    }

    function target_loan()
    {
        $get = $this->input->get(NULL, TRUE);
        $ids = isset($get['ids']) && $get['ids'] ? explode(',', $get['ids']) : '';
        if ($ids && is_array($ids)) {
            $this->load->library('payment_lib');
            $rs = $this->payment_lib->loan_txt($ids, $this->login_info->id);
            if ($rs && $rs != '') {
                $rs = iconv('UTF-8', 'BIG-5//IGNORE', $rs);
                header('Content-type: application/text');
                header('Content-Disposition: attachment; filename=loan_' . date('YmdHis') . '.txt');
                echo $rs;
            } else {
                alert('無可放款之案件', admin_url('Target/waiting_loan'));
            }
        } else {
            alert('請選擇待放款的案件', admin_url('Target/waiting_loan'));
        }
    }

    function subloan_success()
    {
        $get = $this->input->get(NULL, TRUE);
        $id = isset($get['id']) ? intval($get['id']) : 0;
        if ($id) {
            // 啟用SQL事務
            $this->db->trans_start();
            $this->db->query('SET SESSION TRANSACTION ISOLATION LEVEL SERIALIZABLE;');

            // $info = $this->target_model->get($id);

            # issue_898
            $targetSql = sprintf("SELECT * FROM `%s`.`%s` WHERE `id` = '%s' FOR UPDATE", P2P_LOAN_DB, P2P_LOAN_TARGET_TABLE, $id);
            $sqlResult = $this->db->query($targetSql);
            $info = $sqlResult->row();

            $this->load->library('target_lib');
            if ($info && $info->status == 4 && $info->loan_status == 2 && $this->target_lib->is_sub_loan($info->target_no) === TRUE) {
                $this->load->library('Transaction_lib');
                $rs = $this->transaction_lib->subloan_success($id, $this->login_info->id);
                if ($rs) {
                    echo '產轉放款成功';
                } else {
                    echo '產轉放款失敗';
                }
            } else {
                echo '案件不存在或已處理';
            }

            // 事務交易完成，提交結果
            $this->db->trans_complete();
        } else {
            echo '請輸入ID';
        }
    }

    function re_subloan()
    {
        $get = $this->input->get(NULL, TRUE);
        $id = isset($get['id']) ? intval($get['id']) : 0;
        if ($id) {
            $info = $this->target_model->get($id);
            $this->load->library('target_lib');
            if ($info && $info->status == TARGET_WAITING_LOAN && $info->loan_status == 2 && $this->target_lib->is_sub_loan($info->target_no)) {
                $this->load->library('subloan_lib');
                $rs = $this->subloan_lib->rollback_success_target($info, $this->login_info->id);
                if ($rs) {
                    echo '重新上架成功';
                    die();
                } else {
                    echo '操作失敗';
                    die();
                }
            } else {
                echo '查無此ID';
                die();
            }
        } else {
            echo '查無此ID';
            die();
        }
    }

    function loan_return()
    {
        $get = $this->input->get(NULL, TRUE);
        $id = isset($get['id']) ? intval($get['id']) : 0;
        if ($id) {
            $info = $this->target_model->get($id);
            if ($info && $info->status == 4) {
                $rs = $this->target_lib->cancel_success_target($info, $this->login_info->id);
                if ($rs) {
                    echo '更新成功';
                    die();
                } else {
                    echo '更新失敗';
                    die();
                }
            } else {
                echo '查無此ID';
                die();
            }
        } else {
            echo '查無此ID';
            die();
        }
    }

    function loan_success()
    {
        $get = $this->input->get(NULL, TRUE);
        $id = isset($get['id']) ? intval($get['id']) : 0;
        if ($id) {

            // 啟用SQL事務
            $this->db->trans_start();
            $this->db->query('SET SESSION TRANSACTION ISOLATION LEVEL SERIALIZABLE;');

            // $info = $this->target_model->get($id);

            # issue_898
            $targetSql = sprintf("SELECT * FROM `%s`.`%s` WHERE `id` = '%s' FOR UPDATE", P2P_LOAN_DB, P2P_LOAN_TARGET_TABLE, $id);
            $sqlResult = $this->db->query($targetSql);
            $info = $sqlResult->row();

            if ($info && $info->status == 4 && $info->loan_status == 3 && in_array($info->sys_check, [20, 21])) {
                $this->load->library('Transaction_lib');
                $rs = $this->transaction_lib->lending_success($id, $this->login_info->id);
                if ($rs) {
                    echo '更新成功';
                } else {
                    echo '更新失敗';
                }
            } else {
                echo '案件不存在或已處理';
            }

            // 事務交易完成，提交結果
            $this->db->trans_complete();
        } else {
            echo '請輸入ID';
        }
    }

    function loan_failed()
    {
        $get = $this->input->get(NULL, TRUE);
        $id = isset($get['id']) ? intval($get['id']) : 0;
        if ($id) {
            $info = $this->target_model->get($id);
            if ($info && $info->status == TARGET_WAITING_LOAN && $info->loan_status == 3) {
                $this->load->library('Transaction_lib');
                $rs = $this->transaction_lib->lending_failed($id, $this->login_info->id);
                echo '更新成功';
                die();
            } else {
                echo '查無此ID';
                die();
            }
        } else {
            echo '查無此ID';
            die();
        }
    }

    public function transaction_display()
    {
        $page_data = array('type' => 'edit');
        $get = $this->input->get(NULL, TRUE);
        $id = isset($get['id']) ? intval($get['id']) : 0;
        if ($id) {
            $info = $this->target_model->get($id);
            if ($info && in_array($info->status, array(TARGET_REPAYMENTING, TARGET_REPAYMENTED))) {
                $list = array();
                $this->load->model('transaction/transaction_model');
                $transaction_list = $this->transaction_model->order_by('id', 'asc')->get_many_by(array('target_id' => $info->id));
                if ($transaction_list) {
                    foreach ($transaction_list as $key => $value) {
                        $list[$value->investment_id][$value->instalment_no][] = $value;
                    }
                }

                $page_data['info'] = $info;
                $page_data['list'] = $list;
                $page_data['transaction_source'] = $this->config->item('transaction_source');
                $page_data['status_list'] = $this->transaction_model->status_list;
                $page_data['passbook_status_list'] = $this->transaction_model->passbook_status_list;
                $this->load->view('admin/_header');
                $this->load->view('admin/transaction_edit', $page_data);
                $this->load->view('admin/_footer');

            } else {
                die('ERROR , id is not exist');
            }
        } else {
            die('ERROR , id is not exist');
        }

    }

    public function repayment()
    {
        $page_data = ['type' => 'list'];
        $input = $this->input->get(NULL, TRUE);
        $where = ['status' => TARGET_REPAYMENTING];

        $this->load->library('target_lib');
        $category = $input['tab'] ?? '';
        $filter_product_ids = $this->target_lib->get_product_id_by_tab($category);
        if (!empty($filter_product_ids)) {
            $where['product_id'] = $filter_product_ids;
            if ($category == 'enterprise') {
                $where['status'] = [TARGET_BANK_REPAYMENTING];
            }
        }
        $list = $this->target_model->get_many_by($where);
        $school_list = [];
        $user_list = [];
        $amortization_table = [];
        if ($list) {
            // dev use
            // foreach ($list as $key => $value) {
            //     $user_list[] = $value->user_id;
            //     $limit_date = $value->created_at + (TARGET_APPROVE_LIMIT * 86400);
            //     $credit = $this->credit_model->order_by('created_at', 'desc')->get_by([
            //         'product_id' => $value->product_id,
            //         'user_id' => $value->user_id,
            //         'created_at <=' => $limit_date,
            //     ]);
            //     if ($credit) {
            //         $list[$key]->credit = $credit;
            //     }
            //     $amortization_table = $this->target_lib->get_amortization_table($value);
            //     $list[$key]->amortization_table = [
            //         'total_payment_m' => isset($amortization_table['list'][1]['total_payment']),
            //         'total_payment' => $amortization_table['total_payment'],
            //         'remaining_principal' => $amortization_table['remaining_principal'],
            //     ];
            // }
            foreach ($list as $key => $value) {
                $user_list[] = $value->user_id;
                $limit_date = $value->created_at + (TARGET_APPROVE_LIMIT * 86400);
                $credit = $this->credit_model->order_by('created_at', 'desc')->get_by([
                    'product_id' => $value->product_id,
                    'user_id' => $value->user_id,
                    'created_at <=' => $limit_date,
                ]);
                if ($credit) {
                    $list[$key]->credit = $credit;
                }
                $amortization_table = $this->target_lib->get_amortization_table($value);
                $list[$key]->amortization_table = [
                    'total_payment_m' => isset($amortization_table['list'][1]['total_payment']),
                    'total_payment' => $amortization_table['total_payment'],
                    'remaining_principal' => $amortization_table['remaining_principal'],
                ];

                $user_data = $this->user_model->get_by(['id' => $value->user_id]);
                $list[$key]->company = $user_data->name ?? '';
                $repayment_schedule = $this->target_lib->get_repayment_schedule($value);
                if (!empty($repayment_schedule)) {
                    $list[$key]->paid_instalment = $repayment_schedule[array_key_first($repayment_schedule)]['instalment'] - 1;
                } else {
                    $list[$key]->paid_instalment = $value->instalment;
                }
            }

            $this->load->model('user/user_meta_model');
            $users_school = $this->user_meta_model->get_many_by([
                'meta_key' => ['school_name', 'school_department'],
                'user_id' => $user_list,
            ]);
            if ($users_school) {
                foreach ($users_school as $key => $value) {
                    $school_list[$value->user_id][$value->meta_key] = $value->meta_value;
                }
            }
        }
        $page_data['instalment_list'] = $this->config->item('instalment');
        $page_data['repayment_type'] = $this->config->item('repayment_type');
        $page_data['list'] = $list;
        $page_data['delay_list'] = $this->target_model->delay_list;
        $page_data['status_list'] = $this->target_model->status_list;
        $page_data['school_list'] = $school_list;
        $page_data['product_list'] = $this->config->item('product_list');
        $page_data['sub_product_list'] = $this->config->item('sub_product_list');
        $page_data['category'] = $category;

        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/target/targets_repayment', $page_data);
        $this->load->view('admin/_footer');
    }

    public function target_export()
    {
        $post = $this->input->post(NULL, TRUE);
        $ids = isset($post['ids']) && $post['ids'] ? explode(',', $post['ids']) : '';
        $status = isset($post['status']) && $post['status'] ? $post['status'] : [TARGET_REPAYMENTING, TARGET_BANK_REPAYMENTING];
        if ($ids && is_array($ids)) {
            $where = ['id' => $ids];
        } else {
            $where = ['status' => $status];
        }

        $product_list = $this->config->item('product_list');
        $list = $this->target_model->get_many_by($where);
        $school_list = [];
        $user_list = [];
        $amortization_table = [];
        if ($list) {
            foreach ($list as $key => $value) {
                $user_list[] = $value->user_id;
                $limit_date = $value->created_at + (TARGET_APPROVE_LIMIT * 86400);
                $credit = $this->credit_model->order_by('created_at', 'desc')->get_by([
                    'product_id' => $value->product_id,
                    'user_id' => $value->user_id,
                    'created_at <=' => $limit_date,
                ]);
                if ($credit) {
                    $list[$key]->credit = $credit;
                }

                if (in_array($value->status, [TARGET_REPAYMENTING, TARGET_REPAYMENTED])) {
                    $amortization_table = $this->target_lib->get_amortization_table($value);
                    $list[$key]->amortization_table = [
                        'total_payment_m' => $amortization_table['list'][1]['total_payment'],
                        'total_payment' => $amortization_table['total_payment'],
                        'remaining_principal' => $amortization_table['remaining_principal'],
                    ];
                } else {
                    $amortization_table = $this->financial_lib->get_amortization_schedule($value->loan_amount, $value, $value->loan_date);
                    $list[$key]->amortization_table = [
                        'total_payment_m' => $amortization_table['total_payment'],
                        'total_payment' => $amortization_table['total']['total_payment'],
                        'remaining_principal' => $value->loan_amount,
                    ];
                }

            }

            $this->load->model('user/user_meta_model');
            $users_school = $this->user_meta_model->get_many_by(
                array(
                    'meta_key' => ['school_name', 'school_department'],
                    'user_id' => $user_list,
                )
            );
            if ($users_school) {
                foreach ($users_school as $key => $value) {
                    $school_list[$value->user_id][$value->meta_key] = $value->meta_value;
                }
            }
        }
        $instalment_list = $this->config->item('instalment');
        $repayment_type = $this->config->item('repayment_type');
        $delay_list = $this->target_model->delay_list;
        $status_list = $this->target_model->status_list;
        $sub_list = $this->target_model->sub_list;

        header('Content-type:application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=targets_' . date('Ymd') . '.xls');
        $html = '<table><thead><tr><th>案號</th><th>產品</th><th>會員 ID</th><th>信用等級</th><th>學校名稱</th><th>學校科系</th>
                <th>申請金額</th><th>核准金額</th><th>剩餘本金</th><th>年化利率</th><th>貸放期間</th>
                <th>計息方式</th><th>每月回款</th><th>回款本息總額</th><th>放款日期</th>
                <th>逾期狀況</th><th>逾期天數</th><th>狀態</th><th>申請日期</th><th>信評核准日期</th></tr></thead><tbody>';

        if (isset($list) && !empty($list)) {

            foreach ($list as $key => $value) {
                $sub_status = $value->status == TARGET_REPAYMENTED && $value->sub_status != TARGET_SUBSTATUS_NORNAL ? '(' . $sub_list[$value->sub_status] . ')' : '';
                $credit = isset($value->credit) ? date("Y-m-d H:i:s", $value->credit->created_at) : '';
                $html .= '<tr>';
                $html .= '<td>' . $value->target_no . '</td>';
                $html .= '<td>' . $product_list[$value->product_id]['name'] . '</td>';
                $html .= '<td>' . $value->user_id . '</td>';
                $html .= '<td>' . $value->credit_level . '</td>';
                $html .= '<td>' . (isset($school_list[$value->user_id]['school_name']) ? $school_list[$value->user_id]['school_name'] : '') . '</td>';
                $html .= '<td>' . (isset($school_list[$value->user_id]['school_department']) ? $school_list[$value->user_id]['school_department'] : '') . '</td>';
                $html .= '<td>' . $value->amount . '</td>';
                $html .= '<td>' . $value->loan_amount . '</td>';
                $html .= '<td>' . $value->amortization_table['remaining_principal'] . '</td>';
                $html .= '<td>' . $value->interest_rate . '</td>';
                $html .= '<td>' . $value->instalment . '</td>';
                $html .= '<td>' . $repayment_type[$value->repayment] . '</td>';
                $html .= '<td>' . $value->amortization_table['total_payment_m'] . '</td>';
                $html .= '<td>' . $value->amortization_table['total_payment'] . '</td>';
                $html .= '<td>' . $value->loan_date . '</td>';
                $html .= '<td>' . $delay_list[$value->delay] . '</td>';
                $html .= '<td>' . $value->delay_days . '</td>';
                $html .= '<td>' . $status_list[$value->status] . $sub_status . '</td>';
                $html .= '<td>' . date('Y-m-d H:i:s', $value->created_at) . '</td>';
                $html .= '<td>' . $credit . '</td>';
                $html .= '</tr>';
            }
        }
        $html .= '</tbody></table>';
        echo $html;
    }

    public function amortization_export()
    {
        $post = $this->input->post(NULL, TRUE);
        $ids = isset($post['ids']) && $post['ids'] ? explode(',', $post['ids']) : '';
        if ($ids && is_array($ids)) {
            $where = [
                'id' => $ids,
                'status' => TARGET_REPAYMENTING
            ];
        } else {
            $where = [
                'status' => TARGET_REPAYMENTING,
            ];
        }

        $data = [];
        $first_data = [];
        $list = $this->target_model->get_many_by($where);
        if ($list) {
            $total_payment = 0;
            $principal = 0;
            $interest = 0;
            $repayment = 0;
            $r_principal = 0;
            $r_interest = 0;

            foreach ($list as $key => $value) {
                $amortization_table = $this->target_lib->get_amortization_table($value);
                if ($amortization_table) {
                    @$first_data[$amortization_table['date']] -= $amortization_table['amount'];
                    foreach ($amortization_table['list'] as $instalment => $value) {
                        @$data[$value['repayment_date']]['total_payment'] += $value['total_payment'];
                        @$data[$value['repayment_date']]['repayment'] += $value['repayment'];
                        @$data[$value['repayment_date']]['interest'] += $value['interest'];
                        @$data[$value['repayment_date']]['principal'] += $value['principal'];
                        @$data[$value['repayment_date']]['r_principal'] += $value['r_principal'];
                        @$data[$value['repayment_date']]['r_interest'] += $value['repayment'] - $value['r_principal'];
                        @$total_payment += $value['total_payment'];
                        @$principal += $value['principal'];
                        @$interest += $value['interest'];
                        @$repayment += $value['repayment'];
                        @$r_principal += $value['r_principal'];
                        @$r_interest += $value['repayment'] - $value['r_principal'];
                    }
                }
            }
        }

        header('Content-type:application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=amortization_' . date('Ymd') . '.xls');
        $html = '<table>';
        if (isset($first_data) && !empty($first_data)) {
            $sumvalue = 0;
            foreach ($first_data as $key => $value) {
                $html .= '<tr>';
                $html .= '<td>' . $key . '</td>';
                $html .= '<td>' . $value . '</td>';
                $html .= '<td></td><td></td><td></td><td></td>';
                $html .= '</tr>';
                $sumvalue -= $value;
            }
        }
        $html .= '<tr><td></td><td>' . $sumvalue . '</td><td></td><td></td><td></td></tr>';
        $html .= '<tr><td></td><td></td><td></td><td></td><td></td></tr>';
        $html .= '<tr><th>日期</th><th>應收本金</th><th>應收利息</th><th>合計</th><th>當期本金餘額</th><th>已實現本金</th><th>已實現利息</th><th>已回款</th></tr>';
        if (isset($data) && !empty($data)) {
            $total_unrepayment = 0;
            foreach ($data as $key => $value) {
                $unrepayment = $value['principal'] - $value['r_principal'];
                $html .= '<tr>';
                $html .= '<td>' . $key . '</td>';
                $html .= '<td>' . $value['principal'] . '</td>';
                $html .= '<td>' . $value['interest'] . '</td>';
                $html .= '<td>' . $value['total_payment'] . '</td>';
                $html .= '<td>' . $unrepayment . '</td>';
                $html .= '<td>' . $value['r_principal'] . '</td>';
                $html .= '<td>' . $value['r_interest'] . '</td>';
                $html .= '<td>' . $value['repayment'] . '</td>';
                $html .= '</tr>';
                $total_unrepayment += $unrepayment;
            }
        }
        $html .= '<tr><td></td><td>' . $principal . '</td><td>' . $interest . '</td><td>' . $total_payment . '</td><td>' . $total_unrepayment . '</td><td>' . $r_principal . '</td><td>' . $r_interest . '</td><td>' . $repayment . '</td></tr>';
        $html .= '</tbody></table>';
        echo $html;
    }

    public function prepayment()
    {
        $page_data = array('type' => 'list');
        $input = $this->input->get(NULL, TRUE);
        $where = array(
            'status' => array(TARGET_REPAYMENTING, TARGET_REPAYMENTED),
            'sub_status' => array(TARGET_SUBSTATUS_PREPAYMENT, TARGET_SUBSTATUS_PREPAYMENTED),
        );
        $list = array();
        $fields = ['target_no', 'user_id'];

        foreach ($fields as $field) {
            if (isset($input[$field]) && $input[$field] != '') {
                if ($field == 'target_no') {
                    $where[$field . ' like'] = '%' . $input[$field] . '%';
                } else {
                    $where[$field] = $input[$field];
                }
            }
        }
        if (!empty($where)) {
            $this->load->model('loan/prepayment_model');
            $list = $this->target_model->order_by('sub_status', 'ASC')->get_many_by($where);
            if ($list) {
                foreach ($list as $key => $value) {
                    $list[$key]->prepayment = $this->prepayment_model->order_by('settlement_date', 'DESC')->get_by(array('target_id' => $value->id));
                }
            }
        }
        $page_data['instalment_list'] = $this->config->item('instalment');
        $page_data['repayment_type'] = $this->config->item('repayment_type');
        $page_data['list'] = $list;
        $page_data['status_list'] = $this->target_model->status_list;
        $page_data['sub_list'] = $this->target_model->sub_list;
        $page_data['name_list'] = $this->admin_model->get_name_list();


        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/target/prepayment_list', $page_data);
        $this->load->view('admin/_footer');
    }

    public function waiting_bidding()
    {
        $page_data = array('type' => 'list');
        $list = $this->target_model->get_many_by(['status' => [TARGET_WAITING_BIDDING, TARGET_BANK_GUARANTEE, TARGET_BANK_LOAN]]);
        $tmp = [];
        $personal = [];
        $judicialPerson = [];
        $judicialPersonFormBank = [];
        $externalCooperation = $this->config->item('externalCooperation');
        if ($list) {
            $this->load->model('log/Log_targetschange_model');
            $this->load->model('user/user_meta_model');
            foreach ($list as $key => $value) {
                $target_change = $this->Log_targetschange_model->get_by(
                    array(
                        'target_id' => $value->id,
                        'status' => [TARGET_WAITING_BIDDING, TARGET_BANK_VERIFY],
                    )
                );
                if ($target_change) {
                    $list[$key]->bidding_date = $target_change->created_at;
                }
                if (!isset($tmp[$value->user_id]['school']) || !isset($tmp[$value->user_id]['company'])) {
                    if ($value->product_id >= PRODUCT_FOR_JUDICIAL) {
                        $this->load->model("user/user_model");
                        $userData = $this->user_model->get_by(["id" => $value->user_id]);
                        $tmp[$value->user_id]['company'] = $userData->name;
                    } else {
                        $get_meta = $this->user_meta_model->get_many_by([
                            'meta_key' => ['school_name', 'school_department', 'job_company'],
                            'user_id' => $value->user_id,
                        ]);
                        if ($get_meta) {
                            foreach ($get_meta as $skey => $svalue) {
                                $svalue->meta_key == 'school_name' ? $tmp[$svalue->user_id]['school']['school_name'] = $svalue->meta_value : '';
                                $svalue->meta_key == 'school_department' ? $tmp[$svalue->user_id]['school']['school_department'] = $svalue->meta_value : '';
                                $svalue->meta_key == 'job_company' ? $tmp[$svalue->user_id]['company'] = $svalue->meta_value : '';
                            }
                        }
                    }
                }
                if (isset($tmp[$value->user_id]['school']['school_name'])) {
                    $list[$key]->school_name = $tmp[$value->user_id]['school']['school_name'];
                    $list[$key]->school_department = $tmp[$value->user_id]['school']['school_department'];
                }

                isset($tmp[$value->user_id]['company']) ? $list[$key]->company = $tmp[$value->user_id]['company'] : '';

                if ($value->product_id >= PRODUCT_FOR_JUDICIAL) {
                    !in_array($value->product_id, $externalCooperation) ? $judicialPerson[] = $list[$key] : $judicialPersonFormBank[] = $list[$key];
                } else {
                    $personal[] = $list[$key];
                }
            }
        }
        $list = [
            'personal' => $personal,
            'judicialPerson' => $judicialPerson,
            'judicialPersonFormBank' => $judicialPersonFormBank,
        ];

        $page_data['instalment_list'] = $this->config->item('instalment');
        $page_data['repayment_type'] = $this->config->item('repayment_type');
        $page_data['product_list'] = $this->config->item('product_list');
        $page_data['sub_product_list'] = $this->config->item('sub_product_list');
        $page_data['list'] = $list;
        $page_data['status_list'] = $this->target_model->status_list;
        $page_data['sub_list'] = $this->target_model->sub_list;
        $page_data['externalCooperation'] = $externalCooperation;

        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/target/waiting_bidding_target', $page_data);
        $this->load->view('admin/_footer');
    }

    function cancel_bidding()
    {
        $get = $this->input->get(NULL, TRUE);
        $id = isset($get['id']) ? intval($get['id']) : 0;
        $remark = isset($get['remark']) ? $get['remark'] : '';
        if ($id) {
            $info = $this->target_model->get($id);
            if ($info && in_array($info->status, array(TARGET_WAITING_BIDDING))) {
                if ($this->target_lib->is_sub_loan($info->target_no)) {
                    $this->load->library('subloan_lib');
                    $this->subloan_lib->subloan_cancel_bidding($info, $this->login_info->id, $remark);
                } else {
                    $this->target_lib->target_cancel_bidding($info, $this->login_info->id, $remark);
                }
                echo '更新成功';
                die();
            } else {
                echo '查無此ID';
                die();
            }
        } else {
            echo '查無此ID';
            die();
        }
    }

    public function finished()
    {
        $page_data = ['type' => 'list'];
        $input = $this->input->get(NULL, TRUE);
        $list = $this->target_model->get_many_by(['status' => TARGET_REPAYMENTED]);
        $school_list = [];
        $user_list = [];
        $amortization_table = [];
        $where = ['status' => TARGET_REPAYMENTED];

        $this->load->library('target_lib');
        $category = $input['tab'] ?? '';
        $filter_product_ids = $this->target_lib->get_product_id_by_tab($category);
        if (!empty($filter_product_ids)) {
            $where['product_id'] = $filter_product_ids;
            if ($category == 'enterprise') {
                $where['status'] = TARGET_BANK_REPAYMENTED;
            }
        }

        $list = $this->target_model->get_many_by($where);
        if ($list) {
            foreach ($list as $key => $value) {
                $user_list[] = $value->user_id;
                $limit_date = $value->created_at + (TARGET_APPROVE_LIMIT * 86400);
                $credit = $this->credit_model->order_by('created_at', 'desc')->get_by([
                    'product_id' => $value->product_id,
                    'user_id' => $value->user_id,
                    'created_at <=' => $limit_date,
                ]);
                if ($credit) {
                    $list[$key]->credit = $credit;
                }
                $amortization_table = $this->target_lib->get_amortization_table($value);
                $list[$key]->amortization_table = [
                    'total_payment_m' => isset($amortization_table['list'][1]['total_payment']),
                    'total_payment' => $amortization_table['total_payment'],
                    'remaining_principal' => $amortization_table['remaining_principal'],
                ];
            }

            $this->load->model('user/user_meta_model');
            $users_school = $this->user_meta_model->get_many_by([
                'meta_key' => ['school_name', 'school_department'],
                'user_id' => $user_list,
            ]);
            if ($users_school) {
                foreach ($users_school as $key => $value) {
                    $school_list[$value->user_id][$value->meta_key] = $value->meta_value;
                }
            }
        }
        $page_data['instalment_list'] = $this->config->item('instalment');
        $page_data['repayment_type'] = $this->config->item('repayment_type');
        $page_data['product_list'] = $this->config->item('product_list');
        $page_data['sub_product_list'] = $this->config->item('sub_product_list');
        $page_data['list'] = $list;
        $page_data['delay_list'] = $this->target_model->delay_list;
        $page_data['status_list'] = $this->target_model->status_list;
        $page_data['sub_list'] = $this->target_model->sub_list;
        $page_data['school_list'] = $school_list;
        $page_data['category'] = $category;

        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/target/targets_finished', $page_data);
        $this->load->view('admin/_footer');
    }

    public function waiting_signing()
    {
        $page_data = ['type' => 'list'];
        $input = $this->input->get(NULL, TRUE);
        $list = $this->target_model->get_many_by(['status' => [TARGET_WAITING_SIGNING, TARGET_ORDER_WAITING_SIGNING]]);
        $product_list = $this->config->item('product_list');
        $sub_product_list = $this->config->item('sub_product_list');
        $school_list = [];
        $user_list = [];
        $amortization_table = [];
        if ($list) {
            foreach ($list as $key => $value) {
                $user_list[] = $value->user_id;
                $limit_date = $value->created_at + (TARGET_APPROVE_LIMIT * 86400);
                $credit = $this->credit_model->order_by('created_at', 'desc')->get_by([
                    'product_id' => $value->product_id,
                    'user_id' => $value->user_id,
                    'created_at <=' => $limit_date,
                ]);
                if ($credit) {
                    $list[$key]->credit = $credit;
                }
                $amortization_table = $this->financial_lib->get_amortization_schedule($value->loan_amount, $value, $value->loan_date);
                $list[$key]->amortization_table = [
                    'total_payment_m' => $amortization_table['total_payment'],
                    'total_payment' => $amortization_table['total']['total_payment'],
                    'remaining_principal' => $value->loan_amount,
                ];
                $list[$key]->sub_loan_status = $this->target_lib->is_sub_loan($value->target_no);
            }

            $this->load->model('user/user_meta_model');
            $users_school = $this->user_meta_model->get_many_by([
                'meta_key' => ['school_name', 'school_department'],
                'user_id' => $user_list,
            ]);
            if ($users_school) {
                foreach ($users_school as $key => $value) {
                    $school_list[$value->user_id][$value->meta_key] = $value->meta_value;
                }
            }
        }
        $page_data['instalment_list'] = $this->config->item('instalment');
        $page_data['repayment_type'] = $this->config->item('repayment_type');
        $page_data['product_list'] = $product_list;
        $page_data['sub_product_list'] = $sub_product_list;
        $page_data['list'] = $list;
        $page_data['delay_list'] = $this->target_model->delay_list;
        $page_data['status_list'] = $this->target_model->status_list;
        $page_data['sub_list'] = $this->target_model->sub_list;
        $page_data['school_list'] = $school_list;

        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/target/waiting_signing', $page_data);
        $this->load->view('admin/_footer');
    }

    public function waiting_approve_order_transfer()
    {
        $page_data = array('type' => 'list');
        $waiting_list = array();
        $list = $this->target_model->get_many_by(['status' => [TARGET_ORDER_WAITING_VERIFY_TRANSFER]]);
        if ($list) {
            foreach ($list as $key => $value) {
                $bank_account = $this->user_bankaccount_model->get_by(
                    array(
                        'user_id' => $value->user_id,
                        'investor' => 0,
                        'status' => 1,
                        'verify' => 1,
                    )
                );
                if ($bank_account) {
                    $waiting_list[] = $value;
                }
            }
        }
        $page_data['instalment_list'] = $this->config->item('instalment');
        $page_data['repayment_type'] = $this->config->item('repayment_type');
        $page_data['list'] = $waiting_list;
        $page_data['product_list'] = $this->config->item('product_list');
        $page_data['status_list'] = $this->target_model->status_list;
        $page_data['name_list'] = $this->admin_model->get_name_list();


        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/target/approve_order_transfer', $page_data);
        $this->load->view('admin/_footer');
    }

    public function order_target()
    {
        $page_data = array('type' => 'list');
        $list = $this->target_model->get_many_by(['status' => [TARGET_ORDER_WAITING_QUOTE, TARGET_ORDER_WAITING_SIGNING, TARGET_ORDER_WAITING_VERIFY, TARGET_ORDER_WAITING_SHIP, TARGET_ORDER_WAITING_VERIFY_TRANSFER]]);

        $page_data['instalment_list'] = $this->config->item('instalment');
        $page_data['repayment_type'] = $this->config->item('repayment_type');
        $page_data['list'] = $list;
        $page_data['product_list'] = $this->config->item('product_list');
        $page_data['status_list'] = $this->target_model->status_list;
        $page_data['name_list'] = $this->admin_model->get_name_list();


        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/target/order_target', $page_data);
        $this->load->view('admin/_footer');
    }

    function approve_order_transfer()
    {
        $get = $this->input->get(NULL, TRUE);
        $id = isset($get['id']) ? intval($get['id']) : 0;
        if ($id) {
            $info = $this->target_model->get($id);
            if ($info && in_array($info->status, array(TARGET_ORDER_WAITING_VERIFY_TRANSFER))) {
                $this->load->library('Transaction_lib');
                $rs = $this->transaction_lib->order_success($id);
                if ($rs) {
                    echo '更新成功';
                    die();
                } else {
                    echo '更新失敗';
                    die();
                }
            } else {
                echo '查無此ID';
                die();
            }
        } else {
            echo '查無此ID';
            die();
        }
    }

    private function is_sub_product($product, $sub_product_id)
    {
        $sub_product_list = $this->config->item('sub_product_list');
        return isset($sub_product_list[$sub_product_id]['identity'][$product['identity']]) && in_array($sub_product_id, $product['sub_product']);
    }

    private function trans_sub_product($product, $sub_product_id)
    {
        $sub_product_list = $this->config->item('sub_product_list');
        $sub_product_data = $sub_product_list[$sub_product_id]['identity'][$product['identity']];
        $product = $this->sub_product_profile($product, $sub_product_data);
        return $product;
    }

    private function sub_product_profile($product, $sub_product)
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
            'instalment' => $sub_product['instalment'],
            'repayment' => $sub_product['repayment'],
            'targetData' => $sub_product['targetData'],
            'dealer' => $sub_product['dealer'],
            'multi_target' => $sub_product['multi_target'],
            'checkOwner' => $product['checkOwner'],
            'status' => $sub_product['status'],
        );
    }

    public function get_test_data()
    {
        $input = $this->input->get();
        $productId = isset($input['product_id']) ? $input['product_id'] : 0;
        $order = isset($input['order']) ? $input['order'] : 'asc';
        $skip = isset($input['skip']) ? $input['skip'] : 0;
        $limit = isset($input['limit']) ? $input['limit'] : 500;
        $this->load->model("loan/target_model");
        $targets = $this->target_model->limit($limit, $skip)->order_by('id', $order)->get_many_by([
            "product_id" => $productId,
            "status" => [TARGET_WAITING_LOAN, TARGET_REPAYMENTING, TARGET_REPAYMENTED]
        ]);

        $users = [];
        $userIds = [];
        foreach ($targets as $target) {
            $userIds[] = $target->user_id;
        }

        $batch = 1000;
        $iters = intval(count($userIds) / $batch);
        if ($userIds && $iters == 0)
            $iters = 1;
        $this->load->model("user/user_model");
        $this->load->model("user/user_meta_model");
        $this->load->model("user/user_certification_model");
        for ($i = 0; $i < $iters; $i++) {
            $start = $i * $batch;
            $currentUserIds = array_slice($userIds, $start, $batch);
            $metaKeys = ["school_name", "school_major", "school_system", "school_grade", "financial_income", "job_seniority", "investigation_times", "investigation_credit_rate"];
            $metas = $this->user_meta_model->get_many_by(["user_id" => $currentUserIds, "meta_key" => $metaKeys]);
            foreach ($metas as $meta) {
                if (!isset($users[$meta->user_id])) {
                    $users[$meta->user_id] = [];
                }
                $users[$meta->user_id][$meta->meta_key] = $meta->meta_value;
            }

            $certificationDetails = $this->user_certification_model->get_many_by([
                'user_id' => $currentUserIds,
                'certification_id' => 4,
                'status' => 1,
            ]);

            $certificationKeys = ["media", "follows", "followed_by"];
            foreach ($certificationDetails as $certificationDetail) {
                if (!isset($users[$certificationDetail->user_id])) {
                    $users[$certificationDetail->user_id] = [];
                }
                $certificationContent = json_decode($certificationDetail->content);

                foreach ($certificationKeys as $key) {
                    if (isset($certificationContent->info->counts->$key))
                        $users[$certificationDetail->user_id][$key] = $certificationContent->info->counts->$key;
                }
            }

            $incomeExpenditureKeys = ['parttime', 'allowance', 'scholarship', 'other_income', 'restaurant', 'transportation', 'entertainment', 'other_expense'];
            $ieDetails = $this->user_certification_model->get_many_by([
                'user_id' => $currentUserIds,
                'certification_id' => 7,
            ]);
            foreach ($ieDetails as $ie) {
                if (!isset($users[$ie->user_id])) {
                    $users[$ie->user_id] = [];
                }
                $certificationContent = json_decode($ie->content);

                foreach ($incomeExpenditureKeys as $key) {
                    $users[$ie->user_id][$key] = $certificationContent->$key;
                }
            }

            $userKeys = ["id_number", "sex", "birthday", "id_card_place", "created_at"];
            $userInfoArray = $this->user_model->get_many_by(["id" => $currentUserIds]);
            foreach ($userInfoArray as $userInfo) {
                if (!isset($users[$userInfo->id])) {
                    $users[$userInfo->id] = [];
                }

                foreach ($userKeys as $key) {
                    $users[$userInfo->id][$key] = $userInfo->$key;
                }
            }
        }

        $result = [];
        $mapping = [];
        $metaMapping = [
            'parttime' => 'part_time',
            'allowance' => 'allowance',
            'scholarship' => 'scholarship',
            'other_income' => 'other_income',
            'restaurant' => 'food_expenditure',
            'transportation' => 'transportation_expenses',
            'entertainment' => 'entertainment_expenses',
            'other_expense' => 'other_expenses',

            "sex" => "gender",
            "id_card_place" => "location",
            "created_at" => "register_at",
            "birthday" => "dob",
            "school_name" => "uni",
            "school_major" => "department",
            "school_system" => "degree",
            "school_grade" => "grade",
            "media" => "posts",
            "follows" => "num_follow",
            "followed_by" => "num_followed_by",
            "financial_income" => "annual_inc",
            "job_seniority" => "emp_length",
            "investigation_times" => "inq_last_6mths",
            "investigation_credit_rate" => "bc_util",
        ];
        foreach ($targets as $target) {
            $output = [
                "id" => (int) $target->id,
                "product_id" => (int) $target->product_id,
                "target_no" => $target->target_no,
                "user_id" => $target->user_id,
                "loan_amnt" => (int) $target->amount,
                "funded_amnt" => (int) $target->loan_amount,
                "term" => (int) $target->instalment,
                "overdue" => $target->delay_days > 7,
                "purpose" => "",
                "verification_status" => false,
                "pymnt_plan" => true,
                "initial_list_status" => true,
                "home_ownership" => null,
            ];
            if ($target->reason) {
                $output["purpose"] = $target->reason;
                $reason = json_decode($target->reason);
                if (isset($reason->reason)) {
                    $output["purpose"] = $reason->reason;
                }
                if (isset($reason->reason_description)) {
                    $output["purpose"] .= "-" . $reason->reason_description;
                }
            }
            if (!isset($users[$target->user_id])) {
                foreach ($metaMapping as $key => $value) {
                    $output[$value] = null;
                }
                $result[] = $output;
                continue;
            }
            $user = $users[$target->user_id];
            foreach ($metaMapping as $key => $value) {
                if (!isset($user[$key])) {
                    $output[$value] = null;
                } else {
                    $output[$value] = $user[$key];
                    if ($key == "investigation_credit_rate") {
                        $output[$value] .= "%";
                    }
                    if ($key == "job_seniority") {
                        $output["verification_status"] = true;
                    }
                    if ($key == "id_card_place") {
                        $output["location"] = substr($user["id_number"], 0, 1);
                    }
                    if ($key == "birthday") {
                        $output["dob"] = strtotime($user["birthday"]);
                    }
                }
            }
            $result[] = $output;
        }
        if (!$result) {
            return;
        }
        $i = 0;
        $numCols = 33;
        foreach ($result[0] as $key => $value) {
            echo $key;
            $i++;
            if ($i <= $numCols) {
                echo ",";
            }
        }
        echo "<br>";
        foreach ($result as $each) {
            $i = 0;
            foreach ($each as $key => $value) {
                echo $value;
                $i++;
                if ($i <= $numCols) {
                    echo ",";
                }
            }
            echo "<br>";
        }
    }

    public function legalAffairs()
    {
        $get = $this->input->get(NULL, TRUE);
        $post = $this->input->post(NULL, TRUE);
        if (!empty($get['id'])) {
            $id = $get['id'];
            $targets = $this->target_model->get($id);
            $userInfo = $this->user_model->get($targets->user_id);
            if (!$userInfo) {
                return false;
            }

            $rs = $this->virtual_account_model->insert([
                'investor' => 0,
                'user_id' => $userInfo->id,
                'virtual_account' => CATHAY_VIRTUAL_CODE . LAW_VIRTUAL_CODE . substr($userInfo->id_number, 1, 9),
            ]);
            if ($rs) {
                $param = [
                    'sub_status' => TARGET_SUBSTATUS_LAW_DEBT_COLLECTION,
                ];
                $this->target_model->update($id, $param);
                $this->load->library('Target_lib');
                $this->target_lib->insert_change_log($id, $param);
                alert('已建立法催帳戶', admin_url('target/edit?id=' . $id));
            } else {
                alert('法催帳戶建立失敗', admin_url('target/edit?id=' . $id));
            }
        } elseif (!empty($post['id'])) {
            if (!empty($post['type'])) {
                if ($post['type'] == 'set') {
                    $id = $post['id'];
                    $targets = $this->target_model->get($id);
                    $targetData = json_decode($targets->target_data);
                    if (!isset($targetData->legalAffairs)) {
                        $list = [];
                        $fields = ['platformfee', 'fee', 'liquidateddamages', 'liquidateddamagesinterest', 'delayinterest'];
                        foreach ($fields as $field) {
                            if (isset($post[$field]) && is_numeric($post[$field])) {
                                $list[$field] = $post[$field];
                            } else {
                                alert('輸入不完整', admin_url('target/edit?id=' . $id));
                            }
                        }
                        !isset($targetData->legalAffairs) ? $targetData = new stdClass() : '';
                        $targetData->legalAffairs = [
                            'platformfee' => $list['platformfee'],
                            'fee' => $list['fee'],
                            'liquidateddamages' => $list['liquidateddamages'],
                            'liquidateddamagesinterest' => $list['liquidateddamagesinterest'],
                            'delayinterest' => $list['delayinterest'],
                        ];
                        $param = [
                            'target_data' => json_encode($targetData),
                        ];
                        $this->target_model->update($id, $param);

                        $this->transaction_model->update_by([
                            'target_id' => $post['id'],
                            'source' => SOURCE_AR_DELAYINTEREST,
                            'status' => 1
                        ], [
                            'amount' => $list['delayinterest']
                        ]);

                        $this->transaction_model->update_by([
                            'target_id' => $post['id'],
                            'source' => SOURCE_AR_DAMAGE,
                            'status' => 1
                        ], [
                            'amount' => ($list['liquidateddamages'] + $list['liquidateddamagesinterest'])
                        ]);

                        $transaction = $this->transaction_model->get_by([
                            'target_id' => $post['id'],
                            'source' => SOURCE_AR_DAMAGE,
                            'status' => 1
                        ]);
                        $this->transaction_model->insert([
                            'source' => SOURCE_AR_FEES,
                            'entering_date' => get_entering_date(),
                            'user_from' => $transaction->user_from,
                            'bank_account_from' => $transaction->bank_account_from,
                            'amount' => ($list['platformfee'] + $list['fee']),
                            'target_id' => $post['id'],
                            'instalment_no' => $transaction->instalment_no,
                            'user_to' => $transaction->user_to,
                            'bank_account_to' => $transaction->bank_account_to,
                            'limit_date' => $transaction->limit_date,
                            'status' => 1,
                        ]);

                        alert('金額已寫入', admin_url('target/edit?id=' . $id));
                    }
                }
            }
            admin_url('target/edit?id=' . $id);
        }
        return false;
    }

    public function waiting_reinspection()
    {
        $get = $this->input->get(NULL, TRUE);
        $post = $this->input->post(NULL, TRUE);
        $target_id = isset($get['target_id']) ? $get['target_id'] : (isset($post['target_id']) ? $post['target_id'] : '');
        $target_info = $this->target_model->get_by(['id' => $target_id]);
        if ($target_info && in_array($target_info->product_id, $this->config->item('externalCooperation'))) {
            if (count($post) > 0) {
                if (isset($post['send_bank'])) {
                    if (
                        $target_info->status == TARGET_WAITING_VERIFY
                        && $target_info->sub_status == TARGET_SUBSTATUS_SECOND_INSTANCE
                    ) {
                        $param = [
                            'status' => TARGET_BANK_VERIFY,
                            'sub_status' => TARGET_SUBSTATUS_NORNAL,
                        ];
                        $this->target_model->update($target_info->id, $param);
                        $this->load->library('Target_lib');
                        $this->target_lib->insert_change_log($target_info->id, $param);
                    }
                } elseif (isset($post['manual_handling'])) {
                    // dev 用
                    // if($target_info->status == TARGET_WAITING_VERIFY
                    //     && $target_info->sub_status ==TARGET_SUBSTATUS_SECOND_INSTANCE
                    //     || $target_info->status == TARGET_BANK_FAIL
                    // )
                    if (!in_array($target_info->status, [TARGET_BANK_LOAN, TARGET_BANK_REPAYMENTING, TARGET_BANK_REPAYMENTED])) {
                        $target_data = json_decode($target_info->target_data, TRUE);
                        $target_data['manual_reason'] = TARGET_MSG_NOT_CREDIT_STANDARD;
                        $param = [
                            'status' => TARGET_WAITING_VERIFY,
                            'sub_status' => TARGET_SUBSTATUS_WAITING_TRANSFER_INTERNAL,
                            'target_data' => json_encode($target_data)
                        ];
                        $this->target_model->update($target_info->id, $param);
                        $this->load->library('Target_lib');
                        $this->target_lib->insert_change_log($target_info->id, $param);
                    }
                } elseif (isset($post['type']) && isset($post['for']) && isset($post['val'])) {
                    $target_data = json_decode($target_info->target_data);
                    $typeList = ['reinspection_opinion', 'CRO_opinion', 'general_manager_opinion'];
                    $forList = ['comment', 'score'];
                    if (in_array($post['type'], $typeList) && in_array($post['for'], $forList)) {
                        //if(isset($target_data['$field']['comment'])){}
                        $type = $post['type'];
                        $for = $post['for'];
                        $now = time();
                        $newVal = new stdClass();
                        foreach ($target_data->reinspection->$type->$for as $key => $value) {
                            $newVal->$key = $value;
                        }
                        $newVal->$now = $post['val'];
                        (object) $target_data->reinspection->$type->$for = $newVal;
                        $param = [
                            'target_data' => json_encode($target_data),
                        ];
                        $this->target_model->update($target_info->id, $param);
                    }
                }
            } else {
                $page_data['get'] = $get;
                $page_data['targetInfo'] = $target_info;
                $page_data['productList'] = $this->config->item('product_list');

                $this->load->view('admin/_header');
                $this->load->view('admin/_title', $this->menu);
                $this->load->view('admin/target/waiting_reinspection.php', $page_data);
                $this->load->view('admin/_footer');
            }
            $return['result'] = 'fail';
        } else {
            alert('不支援此產品', admin_url('target/waiting_verify'));
        }
        return true;
    }

    // 新光收件檢核表送件紀錄 api
    public function skbank_text_get()
    {
        $get = $this->input->get(NULL, TRUE);
        $bank = $get['bank'] ?? MAPPING_MSG_NO_BANK_NUM_SKBANK;
        $this->load->library('output/json_output');

        if (!$this->input->is_ajax_request() || !isset($get['target_id']) || empty($get) || !is_numeric($get['target_id'])) {
            $this->json_output->setStatusCode(400)->setErrorCode(RequiredArguments)->send();
        }
        $response = [];
        $this->load->model('skbank/LoanTargetMappingMsgNo_model');
        $this->LoanTargetMappingMsgNo_model->limit(1)->order_by("id", "desc");
        $mapping_info = $this->LoanTargetMappingMsgNo_model->get_by(['target_id' => $get['target_id'], 'type' => 'text', 'content !=' => '', 'bank' => $bank]);

        if (empty($mapping_info)) {
            $this->json_output->setStatusCode(200)->setResponse($response)->send();
        }

        $this->load->model('skbank/LoanSendRequestLog_model');
        $msg_no_info = $this->LoanSendRequestLog_model->get_by(['msg_no' => $mapping_info->msg_no, 'send_success =' => 1, 'case_no !=' => 0]);
        if (empty($msg_no_info)) {
            $this->json_output->setStatusCode(200)->setResponse($response)->send();
        }

        $prefix = get_bank_prefix($bank);
        $response[$prefix . 'MsgNo'] = $msg_no_info->msg_no ?? '';
        $response[$prefix . 'CaseNo'] = $msg_no_info->case_no ?? '';

        if (!empty($msg_no_info->request_content)) {
            $request_content = json_decode($msg_no_info->request_content, true);
            $return_msg = json_decode($msg_no_info->response_content, true);
            $response[$prefix . 'CompId'] = $request_content['unencrypted']['CompId'] ?? '';
            $response[$prefix . 'MetaInfo'] = $return_msg['ReturnMsg'] ?? '';
            $response[$prefix . 'Return'] = '成功';
        }
        $this->json_output->setStatusCode(200)->setResponse($response)->send();
    }

    // 新光收件檢核表送件 API
    public function skbank_text_send()
    {
        $get = $this->input->get(NULL, TRUE);
        $this->load->library('output/json_output');

        if (!$this->input->is_ajax_request() || !isset($get['target_id']) || empty($get) || !is_numeric($get['target_id'])) {
            $this->json_output->setStatusCode(400)->setErrorCode(RequiredArguments)->send();
        }

        // TODO:取得總集合
        $this->load->model('skbank/LoanTargetMappingMsgNo_model');
        $this->LoanTargetMappingMsgNo_model->limit(1)->order_by("id", "desc");
        $skbank_save_info = $this->LoanTargetMappingMsgNo_model->get_by(['target_id' => $get['target_id'], 'type' => 'text', 'content !=' => '', 'bank' => $get['bank'] ?? MAPPING_MSG_NO_BANK_NUM_SKBANK]);

        if (!$skbank_save_info || !isset($skbank_save_info->content) || empty($skbank_save_info->content)) {
            $this->json_output->setStatusCode(400)->setErrorCode(ItemNotFound)->send();
        }

        $this->json_output->setStatusCode(200)->setResponse(json_decode($skbank_save_info->content, true))->send();

    }

    // 新光取得圖片
    public function skbank_file_get()
    {
        $get = $this->input->get(NULL, TRUE);
        $this->load->library('output/json_output');
        $response = [];

        $target_info = $this->target_model->get_by(['id' => $get['target_id']]);
        if (!$target_info) {
            $this->json_output->setStatusCode(400)->setErrorCode(RequiredArguments)->send();
        }
        $this->load->library('mapping/sk_bank/check_list');
        $raw_data = $this->check_list->get_raw_data($target_info, $get['bank'] ?? MAPPING_MSG_NO_BANK_NUM_SKBANK, $get_api_attach_no = TRUE);

        $this->load->library('S3_lib');
        foreach ($raw_data as $location => $docs) {
            $response[$location] = [];
            if (!empty($docs['image'])) {
                $response[$location] = $this->s3_lib->imagesToPdf($docs['image'], $target_info->user_id, $location, 'skbank_raw_data');
            }
            if (!empty($docs['pdf'])) {
                $response[$location] = array_merge($response[$location], $docs['pdf']);
            }
        }

        $this->json_output->setStatusCode(200)->setResponse($response)->send();
    }

    public function uploaded_contract()
    {
        $get = $this->input->get(NULL, TRUE);

        $target_id = isset($get['id']) ? intval($get['id']) : 0;
        $meta_name = $get['meta_name'] ?? '';
        $contract_name = $meta_name;

        if (empty($meta_name) || empty($target_id)) {
            alert('網頁帶入資訊有缺少，請確認是否有誤。', admin_url('AdminDashboard'));
        }
        $target = $this->target_model->get($target_id);
        if (!isset($target)) {
            alert('查無案件', admin_url('AdminDashboard'));
        }

        $target_meta = $this->target_lib->get_meta_list($target->id);
        $target_meta = array_column($target_meta, NULL, 'meta_key');
        if (empty($target_meta) || !array_key_exists($meta_name, $target_meta)) {
            alert('查無合約', admin_url('AdminDashboard'));
        }

        $product = $this->target_lib->get_product_info($target->product_id, $target->sub_product_id);
        foreach ($product['need_upload_images'] as $contract) {
            if ($meta_name == $contract['meta_name']) {
                $contract_name = $contract['contract_name'];
            }
        }

        $this->load->model('log/log_image_model');
        $image_url_list = [];
        $image_ids = $target_meta[$meta_name]['meta_value'];
        if (!empty($image_ids) && is_array($image_ids)) {
            $list = $this->log_image_model->as_array()->get_many_by([
                'id' => $image_ids,
                'user_id' => $target->user_id,
            ]);
            $image_url_list = array_column($list, 'url');
        }

        $page_data = [
            'image_url_list' => $image_url_list,
            'contract_name' => $contract_name,
            'target_no' => $target->target_no,
            'target_id' => $target->id,
            'user_id' => $target->user_id,
        ];

        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/target/uploaded_contract', $page_data);
        $this->load->view('admin/_footer');
    }

    // 渲染「DD查核」頁面
    public function meta()
    {
        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/target/dd_edit');
        $this->load->view('admin/_footer');
    }

    // 取得「DD查核」資料
    public function get_meta_info()
    {
        $get = $this->input->get(NULL, TRUE);
        $this->load->library('output/json_output');
        $response = [];

        if (empty($get['id'])) {
            $this->json_output->setStatusCode(204)->setErrorCode(INPUT_NOT_CORRECT)->setErrorMessage('缺少參數，查無資料，請洽工程師')->send();
        }

        $user_info = $this->target_model->get($get['id']);
        if (empty($user_info->user_id)) {
            $this->json_output->setStatusCode(204)->setErrorCode(INPUT_NOT_CORRECT)->setErrorMessage('查無使用者資料')->send();
        }
        $response['data'] = [
            'user_id' => $user_info->user_id,
            'meta_info' => []
        ];
        $this->load->model('loan/target_meta_model');
        $meta_info = $this->target_meta_model->get_many_by([
            'target_id' => $get['id']
        ]);
        array_walk($meta_info, function ($element) use (&$response) {
            switch ($element->meta_key) {
                case 'changes': // 人力變動狀況
                    $index = $pow = 0;
                    while ($element->meta_value >= $pow) {
                        $pow = pow(2, ++$index);
                        if ($element->meta_value & $pow) {
                            $element->meta_value -= $pow;
                            $response['data']['meta_info'][$element->meta_key][] = $index;
                        }
                    }
                    break;
                default:
                    $response['data']['meta_info'][$element->meta_key] = $element->meta_value;
            }
        });

        $this->json_output->setStatusCode(200)->setResponse($response)->send();
    }

    // 儲存「DD查核」資料
    public function save_meta_info()
    {
        $post = json_decode($this->security->xss_clean($this->input->raw_input_stream), TRUE);
        $this->load->library('output/json_output');
        $response = [];

        if (empty($post['meta']) || empty($post['id'])) {
            $this->json_output->setStatusCode(204)->setErrorCode(INPUT_NOT_CORRECT)->setErrorMessage('缺少參數，資料更新失敗，請洽工程師')->send();
        }

        $this->load->model('loan/target_meta_model');
        foreach ($post['meta'] as $key => $value) {
            if (is_array($value)) {
                $value_tmp = 0;
                array_walk($value, function ($element) use (&$value_tmp) {
                    $value_tmp += pow(2, $element);
                });
                $value = $value_tmp;
            }

            $exist = $this->target_meta_model->get_by(array('target_id' => $post['id'], 'meta_key' => $key));
            if ($exist) {
                $this->target_meta_model->update_by(
                    [
                        'target_id' => $post['id'],
                        'meta_key' => $key
                    ],
                    array('meta_value' => $value)
                );
            } else {
                $this->target_meta_model->insert([
                    'target_id' => $post['id'],
                    'meta_key' => $key,
                    'meta_value' => $value
                ]);
            }
        }

        $this->json_output->setStatusCode(200)->setResponse($response)->send();
    }

    public function sme_loan()
    {
        $page_data = [];
        $target_list = $this->target_model->get_many_by([
            'status' => TARGET_WAITING_VERIFY,
            'sub_status' => TARGET_SUBSTATUS_WAITING_TRANSFER_INTERNAL
        ]);
        foreach ($target_list as $target) {
            $target->target_data = json_decode($target->target_data, TRUE);
        }

        $page_data['list'] = $target_list;
        $page_data['product_list'] = $this->config->item('product_list');
        $page_data['subloan_list'] = $this->config->item('subloan_list');

        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/target/sme_loan', $page_data);
        $this->load->view('admin/_footer');
    }

    public function sme_failed()
    {
        $post = $this->input->post(NULL, TRUE);
        $this->load->library('output/json_output');

        if (!$this->input->is_ajax_request() || !isset($post['target_id']) || empty($post) || !is_numeric($post['target_id'])) {
            $this->json_output->setStatusCode(400)->setErrorCode(RequiredArguments)->setResponse(['success' => FALSE, "msg" => '錯誤的案件編號'])->send();
        }

        $target = $this->target_model->get_by([
            'id' => $post['target_id'],
            'status' => TARGET_WAITING_VERIFY,
            'sub_status' => TARGET_SUBSTATUS_WAITING_TRANSFER_INTERNAL
        ]);
        if (!isset($target)) {
            $this->json_output->setStatusCode(400)->setErrorCode(APPLY_NOT_EXIST)->setResponse(['success' => FALSE, "msg" => '找不到案件'])->send();
        }
        $param = [
            'status' => TARGET_FAIL,
        ];
        $this->target_model->update($target->id, $param);
        $this->load->library('Target_lib');
        $this->target_lib->insert_change_log($target->id, $param, 0, $this->login_info->id);
        $this->json_output->setStatusCode(200)->setResponse(['success' => TRUE, 'msg' => '退件成功'])->send();
    }
    public function get_credit_message()
    {
        $input = $this->input->get(NULL, TRUE);
        $this->load->library('output/json_output');
        $this->load->model('loan/target_model');
        $this->load->model('user/user_certification_model');

        $target_id = $input['target_id'];
        $target = $this->target_model->get_by(['id' => $target_id]);
        if (!isset($target)) {
            $this->json_output->setStatusCode(400)->setResponse(['error' => 'target not found'])->send();
        }
        if ($target->loan_amount == 0) {
            $this->json_output->setStatusCode(200)->setResponse(["message" => ""])->send();
        }
        $userId = $target->user_id;
        $past_targets = $this->target_model->get_many_by([
            'user_id' => $userId,
            'status' => [5, 10],
        ]);
        $is_new_user = count($past_targets) == 0;
        $message = "";
        // Todo: “新戶” (無申貸成功紀錄者) 且薪水四萬以下,
        if ($is_new_user) {
            $certification = $this->user_certification_model->get_by(['user_id' => $userId, 'certification_id' => 15]);
            if (isset($certification) && $certification->status == 1) {
                $content = json_decode($certification->content);
                if (
                    isset($content->monthly_repayment) && isset($content->total_repayment)
                    && is_numeric($content->monthly_repayment) && is_numeric($content->total_repayment)
                ) {
                    $liabilitiesWithoutAssureTotalAmount = $content->liabilitiesWithoutAssureTotalAmount ?? 0;
                    $product_id = $target->product_id;
                    // 上班族貸款
                    if (in_array($product_id, [3, 4])) {
                        $product = $this->config->item('product_list')[$product_id];
                        if ($product['condition_rate']['salary_below'] > $content->monthly_repayment * 1000) {
                            $credit["amount"] = $target->loan_amount;
                            if ($liabilitiesWithoutAssureTotalAmount > $content->total_repayment * 1000) {
                                $message = "該會員薪資低於4萬，負債大於22倍，系統給定信用額度為0~3000元；若需調整請至「額度調整 1000~20000」之欄位填寫額度";
                            } else {
                                $message = "該會員薪資低於4萬，負債小於22倍，系統給定信用額度為3000~10000元；若需調整請至「額度調整 1000~20000」之欄位填寫額度";
                            }
                        }
                    }
                } else {
                    if (!is_numeric($content->monthly_repayment) || !is_numeric($content->total_repayment)) {
                        $message = '還款力計算結果資料類型不正確' .
                            ', monthly_repayment: ' . $content->monthly_repayment .
                            ', total_repayment: ' . $content->total_repayment;

                        log_message(
                            'info',
                            $message .
                            ', target_id: ' . $target->id .
                            ', certification: ' . $certification->id
                        );
                    }
                }
            }
        }

        $this->json_output->setStatusCode(200)->setResponse(["message" => $message])->send();
    }
}
?>
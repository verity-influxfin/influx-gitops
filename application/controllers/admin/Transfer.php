<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH . '/libraries/MY_Admin_Controller.php');
use GuzzleHttp\Client;

class Transfer extends MY_Admin_Controller
{
    private $p2p_orm_client;

    protected $edit_method = array('assets_export', 'amortization_export', 'transfer_success', 'transfer_cancel', 'combination_transfer_cancel', 'assets_list');

    public function __construct()
    {
        parent::__construct();
        $this->p2p_orm_client = new Client([
            'base_uri' => getenv('ENV_ERP_HOST'),
            'timeout' => 300,
        ]);
        $this->load->model('loan/investment_model');
        $this->load->model('loan/transfer_model');
        $this->load->model('loan/transfer_investment_model');
        $this->load->library('target_lib');
        $this->load->library('transfer_lib');
        $this->load->library('financial_lib');
    }

    public function index()
    {
        $page_data = array('type' => 'list');
        $list = array();
        $transfers = array();
        $targets = array();
        $school_list = array();
        $input = $this->input->get(NULL, TRUE);
        $show_status = array(3, 10);
        $where = array();
        $target_no = '';
        $fields = ['status', 'target_no', 'user_id'];

        foreach ($fields as $field) {
            if (isset($input[$field]) && $input[$field] != '') {
                if ($field == 'target_no') {
                    $target_no = '%' . $input[$field] . '%';
                } else {
                    $where[$field] = $input[$field];
                }
            }
        }

        isset($input['all']) && $input['all'] == 'all' ? $where = ['status' => [3, 10]] : '';
        isset($input['sdate']) && $input['sdate'] != '' ? $where['created_at >='] = strtotime($input['sdate']) : '';
        isset($input['edate']) && $input['edate'] != '' ? $where['created_at <='] = strtotime($input['edate']) : '';
        if ($target_no != '' || !empty($where)) {
            $where['status'] = isset($where['status']) ? $where['status'] : $show_status;
            $query = $target_no != ''
                ? ['target_no like' => $target_no]
                : ($where['status'] == 3
                    ? ['status' => [5]]
                    : ['status' => [5, 10]]
                );
            isset($input['delay']) && $input['delay'] != '' ? $query['delay'] = ($input['delay'] == 0 ? 0 : 1) : '';
            if (!empty($target_no) || $query) {
                $target_ids = array();
                $target_list = $this->target_model->get_many_by(
                    $query
                );
                if ($target_list) {
                    foreach ($target_list as $key => $value) {
                        $target_ids[] = $value->id;
                    }
                    $where['target_id'] = $target_ids;
                }
            }

            $chunkSize = 500;
            if (isset($where['target_id']) || isset($where['user_id'])) {
                $targetIds = $where["target_id"];
                $targetCount = count($where['target_id']);
                $list = [];
                $rounds = intval($targetCount / $chunkSize) + 1;
                for ($i = 1; $i <= $rounds; $i++) {
                    $currentChunks = array_slice($targetIds, ($i - 1) * $chunkSize, $chunkSize);
                    if (!$currentChunks) continue;
                    $where['target_id'] = $currentChunks;
                    $currentResult = $this->investment_model->order_by('target_id', 'ASC')->get_many_by($where);
                    $list = array_merge($list, $currentResult);
                }
            }

            if ($list) {
                $target_ids = array();
                $ids = array();
                $user_list = array();

                foreach ($list as $key => $value) {
                    $target_ids[] = $value->target_id;
                    $ids[] = $value->id;
                }

                //$target_list 	= $this->target_model->get_many($target_ids);
                if ($target_list) {
                    foreach ($target_list as $key => $value) {
                        $user_list[] = $value->user_id;
                        $targets[$value->id] = $value;
                    }
                }

                foreach ($list as $key => $value) {
                    $list[$key]->amortization_table = $this->target_lib->get_investment_amortization_table($targets[$value->target_id], $value);
                }

                $this->load->model('user/user_meta_model');

                $userIds = $user_list;
                $userCount = count($userIds);
                $school_list = [];
                $rounds = intval($userCount / $chunkSize) + 1;
                for ($i = 1; $i <= $rounds; $i++) {
                    $currentChunks = array_slice($userIds, ($i - 1) * $chunkSize, $chunkSize);
                    if (!$currentChunks) continue;
                    $users_school = $this->user_meta_model->get_many_by(array(
                        'meta_key' => array('school_name', 'school_department'),
                        'user_id' => $currentChunks,
                    ));
                    if ($users_school) {
                        foreach ($users_school as $key => $value) {
                            $school_list[$value->user_id][$value->meta_key] = $value->meta_value;
                        }
                    }
                }


                $totalIds = $ids;
                $investmentCount = count($totalIds);
                $transfer_list = [];
                $rounds = intval($investmentCount / $chunkSize) + 1;
                for ($i = 1; $i <= $rounds; $i++) {
                    $currentChunks = array_slice($totalIds, ($i - 1) * $chunkSize, $chunkSize);
                    if (!$currentChunks) continue;
                    $where = ['investment_id' => $currentChunks];
                    $currentTransfers = $this->transfer_model->get_many_by($where);
                    $transfer_list = array_merge($transfer_list, $currentTransfers);
                }

                if ($transfer_list) {
                    foreach ($transfer_list as $key => $value) {
                        $transfers[$value->investment_id] = $value;
                    }
                }
            }
        }

        $page_data['repayment_type'] = $this->config->item('repayment_type');
        $page_data['list'] = $list;
        $page_data['delay_list'] = $this->target_model->delay_list;
        $page_data['status_list'] = $this->target_model->status_list;
        $page_data['show_status'] = $show_status;
        $page_data['investment_status_list'] = $this->investment_model->status_list;
        $page_data['transfer_status_list'] = $this->investment_model->transfer_status_list;
        $page_data['transfers'] = $transfers;
        $page_data['targets'] = $targets;
        $page_data['school_list'] = $school_list;

        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/target/targets_assets', $page_data);
        $this->load->view('admin/_footer');
    }

	/**
	 * 顯示已下標頁面，並可顯示全部或已付款之對應投資紀錄
	 */
	public function bidding()
	{
		$page_data = array('type' => 'list');
		$input = $this->input->get(NULL, TRUE);
		$show_status = array(2);
		$where = array();
		$target_no = '';
		$fields = ['status', 'target_no', 'user_id'];

		foreach ($fields as $field) {
			if (isset($input[$field]) && $input[$field] != '') {
				if ($field == 'target_no') {
					$target_no = '%' . $input[$field] . '%';
				} else {
					$where[$field] = $input[$field];
				}
			}
		}

		// 0: 待付款 1: 待結標(款項已移至待交易) 2: 待放款(已結標)
		$where['status'] = isset($input['status']) ? ($input['status'] == 'all' || $input['status'] == '') ? [0,1,2] : [intval($input['status'])] : [0,1,2];
		isset($input['sdate']) && $input['sdate'] != '' ? $where['created_at >='] = strtotime($input['sdate']) : '';
		isset($input['edate']) && $input['edate'] != '' ? $where['created_at <='] = strtotime($input['edate']) : '';

		$result = $this->investment_model->get_bidding_investment($where, $target_no);

		$page_data['show_status'] = $show_status;
		$page_data['investment_status_list'] = $this->investment_model->bidding_status_list;
		$page_data['list'] = $result;

		$this->load->view('admin/_header');
		$this->load->view('admin/_title', $this->menu);
		$this->load->view('admin/target/targets_bidding_assets', $page_data);
		$this->load->view('admin/_footer');
	}

  public function assets_export_new()
  {
      $this->load->library('sisyphus/assets_report_lib');
      $assets_report = $this->assets_report_lib->getAssetsReport();

      if ($assets_report){
          $file_name = 'assets_'.date('Ymd-His').'.xlsx';
          force_download($file_name, $assets_report);

      }else{
          echo '<script type="text/javascript"> alert("撈取失敗，請洽工程師\nError: get assets report failed (sisyphus)");
          window.location.href="obligations";
          </script>';
      }

  }

    public function assets_export()
    {
        $post = $this->input->post(NULL, TRUE);
        $html = '';
        $ids = isset($post['ids']) && $post['ids'] ? explode(',', $post['ids']) : '';
        $list = array();
        $targets = array();
        $school_list = array();
        if ($ids && is_array($ids)) {
            $product_list = $this->config->item('product_list');
            $list = $this->investment_model->order_by('target_id', 'ASC')->get_many($ids);
            $user_list = [];
            $amortization_table = [];
            if ($list) {
                $target_ids = array();
                $user_list = array();

                foreach ($list as $key => $value) {
                    $target_ids[] = $value->target_id;
                }

                $target_list = $this->target_model->get_many($target_ids);
                if ($target_list) {
                    foreach ($target_list as $key => $value) {
                        $user_list[] = $value->user_id;
                        $targets[$value->id] = $value;
                    }
                }

                foreach ($list as $key => $value) {
                    $amortization_table = $this->target_lib->get_investment_amortization_table($targets[$value->target_id], $value);
                    $amortization_list = array_values($amortization_table['list']);
                    $list[$key]->amortization_table = array(
                        'total_payment_m' => isset($amortization_list[0]) ? $amortization_list[0]['total_payment'] : 0,
                        'total_payment' => $amortization_table['total_payment'],
                        'remaining_principal' => $amortization_table['remaining_principal'],
                    );
                }

                $this->load->model('user/user_meta_model');
                $users_school = $this->user_meta_model->get_many_by(array(
                    'meta_key' => array('school_name', 'school_department'),
                    'user_id' => $user_list,
                ));
                if ($users_school) {
                    foreach ($users_school as $key => $value) {
                        $school_list[$value->user_id][$value->meta_key] = $value->meta_value;
                    }
                }
            }

            $repayment_type = $this->config->item('repayment_type');
            $delay_list = $this->target_model->delay_list;
            $status_list = $this->target_model->status_list;
            $transfer_list = $this->transfer_model->get_many_by(array('investment_id' => $ids));
            if ($transfer_list) {
                foreach ($transfer_list as $key => $value) {
                    $transfers[$value->investment_id] = $value;
                }
            }

            header('Content-type:application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename=assets_' . date('Ymd') . '.xls');
            $html = '<table>
                        <thead>
                            <tr>
                                <th>產品名稱</th>
                                <th>案號</th>
                                <th>借款人 ID</th>
                                <th>投資人 ID</th>
                                <th>債權總額</th>
                                <th>投資金額</th>
                                <th>剩餘本金</th>
                                <th>核准信評</th>
                                <th>學校/公司</th>
                                <th>科系</th>
                                <th>利率</th>
                                <th>放款期間</th>
                                <th>計息方式</th>
                                <th>放款日期</th>
                                <th>債權狀態</th>
                                <th>案件狀態</th>
                                <th>逾期天數</th>
                                <th>逾期資產</th>
                                <th>調降信評</th>
                            </tr>
                        </thead>
                    <tbody>';

            if (isset($list) && !empty($list)) {
                $products = $this->config->item('product_list');
                $this->load->library('utility/payment_time_utility');
                foreach ($list as $key => $value) {
                    $target = $targets[$value->target_id];
                    $schoolName = '';
                    $schoolDepartment = '';
                    if (isset($school_list[$target->user_id])) {
                        $schoolName = $school_list[$target->user_id]["school_name"];
                        $schoolDepartment = $school_list[$target->user_id]["school_department"];
                    }

                    $delayDays = 0;
                    $targetCurrentStatus = '正常還款';
                    if ($target->status == 10) {
                        $targetCurrentStatus = '到期結案';
                    } elseif ($target->sub_status == 4) {
                        $targetCurrentStatus = '提早清償';
                    } elseif ($target->delay_days > 7) {
                        $delayDays = $target->delay_days;
                        $targetCurrentStatus = '逾期中';
                    }

                    $targetOverdueCategory = '';
                    $currentTime = time();
                    $currentDate = date('Y-m-d', $currentTime);
                    $thisMonthPaymentDate = $this->payment_time_utility->goToPrevious($currentDate);
                    $diffBetweenNowAndPaymentDate = get_range_days(strtotime($thisMonthPaymentDate), $currentTime);
                    if ($delayDays > 7) {
                        $delayDaysToLastPaymentDate = $delayDays - $diffBetweenNowAndPaymentDate;
                        $overdueMonths = $delayDaysToLastPaymentDate /= 30;
                        if ($overdueMonths < 2) {
                            $targetOverdueCategory = '關注資產(M1)';
                        } elseif ($overdueMonths < 3) {
                            $targetOverdueCategory = '次級資產(M2)';
                        } elseif ($overdueMonths < 4) {
                            $targetOverdueCategory = '可疑資產(M3)';
                        } else {
                            $targetOverdueCategory = '不良資產(>M3)';
                        }
                    } elseif ($delayDays > 0) {
                        $targetOverdueCategory = '觀察資產（D7）';
                    }

                    $html .= '<tr>';
                    $html .= '<td>' . $products[$target->product_id]['name'] . '</td>';
                    $html .= '<td>' . $target->target_no . '</td>';
                    $html .= '<td>' . $target->user_id . '</td>';
                    $html .= '<td>' . $value->user_id . '</td>';
                    $html .= '<td>' . $target->loan_amount . '</td>';
                    $html .= '<td>' . $value->loan_amount . '</td>';
                    $html .= '<td>' . $value->amortization_table["remaining_principal"] . '</td>';
                    $html .= '<td>' . $target->credit_level . '</td>';
                    $html .= '<td>' . $schoolName . '</td>';
                    $html .= '<td>' . $schoolDepartment . '</td>';
                    $html .= '<td>' . $target->interest_rate . '</td>';
                    $html .= '<td>' . $target->instalment . '</td>';
                    $html .= '<td>' . $repayment_type[$target->repayment] . '</td>';
                    $html .= '<td>' . $target->loan_date . '</td>';
                    $html .= '<td>' . ($value->transfer_status == 2 ? $this->investment_model->transfer_status_list[$value->transfer_status] : $this->investment_model->status_list[$value->status]) . '</td>';
                    $html .= '<td>' . $targetCurrentStatus . '</td>';
                    $html .= '<td>' . $delayDays . '</td>';
                    $html .= '<td>' . $targetOverdueCategory . '</td>';
                    $html .= '<td>' . 'n/a' . '<td>';
                    $html .= '</tr>';
                }
            }
            $html .= '</tbody></table>';
        }
        echo $html;
    }

    /**
     * New version of the index controller
     */
    public function obligations()
    {
        $page_data = array('type' => 'list');
        $list = array();
        $transfers = array();
        $targets = array();
        $school_list = array();
        $input = $this->input->get(NULL, TRUE);
        $show_status = array(3, 10);
        $where = array();
        $target_no = '';
        $fields = ['status', 'target_no', 'user_id'];

        foreach ($fields as $field) {
            if (isset($input[$field]) && $input[$field] != '') {
                if ($field == 'target_no') {
                    $target_no = '%' . $input[$field] . '%';
                } else {
                    $where[$field] = $input[$field];
                }
            }
        }

        isset($input['all']) && $input['all'] == 'all' ? $where = ['status' => [3, 10]] : '';
        isset($input['sdate']) && $input['sdate'] != '' ? $where['created_at >='] = strtotime($input['sdate']) : '';
        isset($input['edate']) && $input['edate'] != '' ? $where['created_at <='] = strtotime($input['edate']) : '';
        if ($target_no != '' || !empty($where)) {
            $where['status'] = isset($where['status']) ? $where['status'] : $show_status;
            $query = $target_no != ''
                ? ['target_no like' => $target_no]
                : ($where['status'] == 3
                    ? ['status' => [5]]
                    : ['status' => [5, 10]]
                );
            isset($input['delay']) && $input['delay'] != '' ? $query['delay'] = ($input['delay'] == 0 ? 0 : 1) : '';
            if (!empty($target_no) || $query) {
                $target_ids = array();
                $target_list = $this->target_model->get_many_by(
                    $query
                );
                if ($target_list) {
                    foreach ($target_list as $key => $value) {
                        $target_ids[] = $value->id;
                    }
                    $where['target_id'] = $target_ids;
                }
            }

            $chunkSize = 500;
            if (isset($where['target_id']) || isset($where['user_id'])) {
                $targetIds = $where["target_id"];
                $targetCount = count($where['target_id']);
                $list = [];
                $rounds = intval($targetCount / $chunkSize) + 1;
                for ($i = 1; $i <= $rounds; $i++) {
                    $currentChunks = array_slice($targetIds, ($i - 1) * $chunkSize, $chunkSize);
                    if (!$currentChunks) continue;
                    $where['target_id'] = $currentChunks;
                    $currentResult = $this->investment_model->order_by('target_id', 'ASC')->get_many_by($where);
                    $list = array_merge($list, $currentResult);
                }
            }

            if ($list) {
                $target_ids = array();
                $ids = array();
                $user_list = array();

                foreach ($list as $key => $value) {
                    $target_ids[] = $value->target_id;
                    $ids[] = $value->id;
                }

                //$target_list 	= $this->target_model->get_many($target_ids);
                if ($target_list) {
                    foreach ($target_list as $key => $value) {
                        $user_list[] = $value->user_id;
                        $targets[$value->id] = $value;
                    }
                }

                foreach ($list as $key => $value) {
                    $list[$key]->amortization_table = $this->target_lib->get_investment_amortization_table($targets[$value->target_id], $value);
                }

                $this->load->model('user/user_meta_model');

                $userIds = $user_list;
                $userCount = count($userIds);
                $school_list = [];
                $rounds = intval($userCount / $chunkSize) + 1;
                for ($i = 1; $i <= $rounds; $i++) {
                    $currentChunks = array_slice($userIds, ($i - 1) * $chunkSize, $chunkSize);
                    if (!$currentChunks) continue;
                    $users_school = $this->user_meta_model->get_many_by(array(
                        'meta_key' => array('school_name', 'school_department'),
                        'user_id' => $currentChunks,
                    ));
                    if ($users_school) {
                        foreach ($users_school as $key => $value) {
                            $school_list[$value->user_id][$value->meta_key] = $value->meta_value;
                        }
                    }
                }


                $totalIds = $ids;
                $investmentCount = count($totalIds);
                $transfer_list = [];
                $rounds = intval($investmentCount / $chunkSize) + 1;
                for ($i = 1; $i <= $rounds; $i++) {
                    $currentChunks = array_slice($totalIds, ($i - 1) * $chunkSize, $chunkSize);
                    if (!$currentChunks) continue;
                    $where = ['investment_id' => $currentChunks];
                    $currentTransfers = $this->transfer_model->get_many_by($where);
                    $transfer_list = array_merge($transfer_list, $currentTransfers);
                }

                if ($transfer_list) {
                    foreach ($transfer_list as $key => $value) {
                        $transfers[$value->investment_id] = $value;
                    }
                }
            }
        }

        $page_data['repayment_type'] = $this->config->item('repayment_type');
        $page_data['list'] = $list;
        $page_data['delay_list'] = $this->target_model->delay_list;
        $page_data['status_list'] = $this->target_model->status_list;
        $page_data['show_status'] = $show_status;
        $page_data['investment_status_list'] = $this->investment_model->status_list;
        $page_data['transfer_status_list'] = $this->investment_model->transfer_status_list;
        $page_data['transfers'] = $transfers;
        $page_data['targets'] = $targets;
        $page_data['school_list'] = $school_list;

        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/target/obligations', $page_data);
        $this->load->view('admin/_footer');
    }

    /**
     * New version of amortization_export
     * Mostly follows the same rule but modifies a bit to fit requirements
     */
    public function amortization_schedule()
    {
        $post = $this->input->post(NULL, TRUE);
        $ids = isset($post['ids']) && $post['ids'] ? explode(',', $post['ids']) : '';

        $html = '';
        if (!$ids || !is_array($ids)) {
            return;
        }

        $this->load->library('report/accounts/profit_and_loss_account');
        $profitAndLossAccount = $this->profit_and_loss_account->generateTotalReport($ids);

        $this->profit_and_loss_account->toExcel($profitAndLossAccount);
    }

    public function amortization_export()
    {
        $post = $this->input->post(NULL, TRUE);
        $html = '';
        $ids = isset($post['ids']) && $post['ids'] ? explode(',', $post['ids']) : '';
        $list = array();
        if ($ids && is_array($ids)) {
            $investments = $this->investment_model->order_by('target_id', 'ASC')->get_many($ids);
            $amortization_table = array();
            if ($investments) {
                foreach ($investments as $key => $value) {
                    $target = $this->target_model->get($value->target_id);
                    $amortization_table = $this->target_lib->get_investment_amortization_table($target, $value);
                    if ($amortization_table && !empty($amortization_table['list'])) {
                        foreach ($amortization_table['list'] as $k => $v) {
                            if (!isset($list[$v['repayment_date']])) {
                                $list[$v['repayment_date']] = array(
                                    'principal' => 0,
                                    'interest' => 0,
                                    'delay_interest' => 0,
                                    'ar_fees' => 0,
                                    'r_fees' => 0,
                                    'r_principal' => 0,
                                    'r_interest' => 0,
                                    'r_delayinterest' => 0,
                                    'repayment' => 0,
                                );
                            }
                            $list[$v['repayment_date']]['principal'] += $v['principal'];
                            $list[$v['repayment_date']]['interest'] += $v['interest'];
                            $list[$v['repayment_date']]['delay_interest'] += $v['delay_interest'];
                            $list[$v['repayment_date']]['ar_fees'] += $v['ar_fees'];
                            $list[$v['repayment_date']]['r_fees'] += $v['r_fees'];
                            $list[$v['repayment_date']]['r_principal'] += $v['r_principal'];
                            $list[$v['repayment_date']]['r_interest'] += $v['r_interest'];
                            $list[$v['repayment_date']]['r_delayinterest'] += $v['r_delayinterest'];
                            $list[$v['repayment_date']]['repayment'] += $v['repayment'];
                        }
                    }
                }

            }

            header('Content-type:application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename=repayment_schedule_' . date('Ymd') . '.xls');
            $html = '<table><thead><tr><th>還款日</th><th>代收-應付借款本金</th><th>代收-應付借款利息</th><th>代收-應付延滯息</th><th>代收-應付總額</th><th>代收-當期償還本息</th><th>手續費收入-期付金回款</th><th>投資回款淨額</th></tr></thead><tbody>';

            if (isset($list) && !empty($list)) {
                ksort($list);
                foreach ($list as $key => $value) {
                    if (substr($key, -2) == '10') {
                        $total = $value['principal'] + $value['interest'] + $value['r_delayinterest'];
                        $r_fee = $value['r_fees'];
                        $profit = $value['repayment'] - $r_fee;
                        $html .= '<tr>';
                        $html .= '<td>' . $key . '</td>';
                        $html .= '<td>' . $value['principal'] . '</td>';
                        $html .= '<td>' . $value['interest'] . '</td>';
                        $html .= '<td>' . $value['r_delayinterest'] . '</td>';
                        $html .= '<td>' . $total . '</td>';
                        $html .= '<td>' . $value['repayment'] . '</td>';
                        $html .= '<td>' . $r_fee . '</td>';
                        $html .= '<td>' . $profit . '</td>';
                        $html .= '</tr>';
                    }
                }
            }
            $html .= '</tbody></table>';
        }
        echo $html;
    }

    public function waiting_transfer()
    {
        $page_data = array('type' => 'list');
        $this->load->model('loan/transfer_combination_model');
        $transfers = [];
        $combinations = [];
        $where = array(
            'status' => 0
        );
        $list = $this->transfer_model->get_many_by($where);
        if ($list) {
            $combination_ids = [];
            foreach ($list as $key => $value) {
                if (!in_array($value->combination, $combination_ids)) {
                    if ($value->combination != 0) {
                        array_push($combination_ids, $value->combination);
                        $investment = $this->investment_model->get($value->investment_id);
                        $combination_info = $this->transfer_combination_model->get($value->combination);
                        $combination_info->user_id = $investment->user_id;
                        $combination_info->expire_time = $value->expire_time;
                        $combinations[] = $combination_info;
                        array_splice($list, $key, 1);
                    } else {
                        $transfer_info = $value;
                        $transfer_info->target = $this->target_model->get($value->target_id);
                        $transfer_info->investment = $this->investment_model->get($value->investment_id);
                        $transfers[] = $transfer_info;
                    }
                }
            }
        }

        $page_data['instalment_list'] = $this->config->item('instalment');
        $page_data['repayment_type'] = $this->config->item('repayment_type');
        $page_data['list'] = $transfers;
        $page_data['combinations'] = $combinations;
        $page_data['transfer_status_list'] = $this->investment_model->transfer_status_list;

        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/transfer/waiting_transfer', $page_data);
        $this->load->view('admin/_footer');
    }

    public function transfer_combination()
    {
        $page_data = array('type' => 'list');
        $input = $this->input->get(NULL, TRUE);
        $where = array(
            'combination' => $input['id']
        );
        $list = $this->transfer_model->get_many_by($where);
        if ($list) {
            foreach ($list as $key => $value) {
                $list[$key]->target = $this->target_model->get($value->target_id);
                $list[$key]->investment = $this->investment_model->get($value->investment_id);
            }
        }

        $page_data['instalment_list'] = $this->config->item('instalment');
        $page_data['repayment_type'] = $this->config->item('repayment_type');
        $page_data['list'] = $list;
        $page_data['no'] = $input['no'];
        $page_data['transfer_status_list'] = $this->investment_model->transfer_status_list;

        $this->load->view('admin/_header');
        $this->load->view('admin/transfer/transfer_combination', $page_data);
        $this->load->view('admin/_footer');
    }

    public function transfer_combination_success()
    {
        $page_data = array('type' => 'list');
        $input = $this->input->get(NULL, TRUE);
        $where = array(
            'combination' => $input['id']
        );
        $list = $this->transfer_model->get_many_by($where);
        if ($list) {
            foreach ($list as $key => $value) {
                $list[$key]->target = $this->target_model->get($value->target_id);
                $list[$key]->investment = $this->investment_model->get($value->investment_id);
                $list[$key]->transfer_investments = $transfer_investments = $this->transfer_investment_model->get_by(array('transfer_id' => $value->id,));
            }
        }

        $page_data['instalment_list'] = $this->config->item('instalment');
        $page_data['repayment_type'] = $this->config->item('repayment_type');
        $page_data['list'] = $list;
        $page_data['no'] = $input['no'];
        $page_data['transfer_status_list'] = $this->investment_model->transfer_status_list;

        $this->load->view('admin/_header');
        $this->load->view('admin/transfer/transfer_combination_success', $page_data);
        $this->load->view('admin/_footer');
    }

    public function waiting_transfer_success()
    {
        $page_data = array('type' => 'list');
        $this->load->model('loan/transfer_combination_model');
        $this->load->model('loan/transfer_investment_model');
        $transfers = [];
        $combinations = [];
        $where = array(
            'status' => 1
        );
        $list = $this->transfer_model->get_many_by($where);
        if ($list) {
            $combination_ids = [];
            foreach ($list as $key => $value) {
                if (!in_array($value->combination, $combination_ids)) {
                    if ($value->combination != 0) {
                        array_push($combination_ids, $value->combination);
                        $investment = $this->investment_model->get($value->investment_id);
                        $combination_info = $this->transfer_combination_model->get($value->combination);
                        $combination_info->transfer = $value->id;
                        $combination_info->user_id = $investment->user_id;
                        $combination_info->expire_time = $value->expire_time;
                        $combinations[] = $combination_info;
                        array_splice($list, $key, 1);
                    } else {
                        $transfer_info = $value;
                        $transfer_info->target = $this->target_model->get($value->target_id);
                        $transfer_info->investment = $this->investment_model->get($value->investment_id);
                        $transfer_info->transfer_investments = $transfer_investments = $this->transfer_investment_model->get_by(array('transfer_id' => $value->id,));
                        $transfers[] = $transfer_info;
                    }
                }
            }
        }

        $page_data['instalment_list'] = $this->config->item('instalment');
        $page_data['repayment_type'] = $this->config->item('repayment_type');
        $page_data['list'] = $transfers;
        $page_data['combinations'] = $combinations;
        $page_data['transfer_status_list'] = $this->investment_model->transfer_status_list;

        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/transfer/waiting_transfer_success', $page_data);
        $this->load->view('admin/_footer');
    }

    function transfer_success()
    {
        $get = $this->input->get(NULL, TRUE);
        $ids = isset($get['ids']) && $get['ids'] ? explode(',', $get['ids']) : '';
        if ($ids && is_array($ids)) {
            $this->load->library('Transaction_lib');
            foreach ($ids as $key => $id) {
                $rs = $this->transaction_lib->transfer_success($id, $this->login_info->id);
            }
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
    }

    function transfer_cancel()
    {
        $get = $this->input->get(NULL, TRUE);
        $id = isset($get['id']) ? intval($get['id']) : 0;
        if ($id) {
            $info = $this->transfer_model->get($id);
            if ($info && $info->status == 1) {
                $this->load->library('transfer_lib');
                $rs = $this->transfer_lib->cancel_transfer($info, $this->login_info->id);
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

    function c_transfer_cancel()
    {
        $get = $this->input->get(NULL, TRUE);
        $id = isset($get['id']) ? intval($get['id']) : 0;
        if ($id) {
            $this->load->model('loan/transfer_combination_model');
            $combination = $this->transfer_combination_model->get(['combination' => $id]);
            $transfer = $this->transfer_model->get_many_by(['combination' => $id]);
            if ($combination->count && count($transfer)) {
                $this->load->library('transfer_lib');
                $rs = $this->transfer_lib->cancel_combination_transfer($transfer);
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

    /**
     * 取得債權明細 -- 移轉成功
     * 
     * @created_at      2023-08-24
     * @created_by      Howard
     */
    public function transfer_sheet_success()
    {
        $this->load->view(
            'admin/transfer/transfer_sheet_success',
            $data = [
                'menu'      => $this->menu,
                'use_vuejs' => TRUE,
                'scripts'   => [
                    '/assets/admin/js/transfer/transfer_sheet.js',
                    'https://cdn.jsdelivr.net/npm/vue@2.6.14',
                    'https://unpkg.com/axios/dist/axios.min.js'
                ]
            ]
        );
    }

    /**
     * 債權明細 -- 移轉成功 資料
     * 
     * @created_at      2023-08-24
     * @created_by      Howard
     */
    public function get_transfer_success()
    {
        $data = $this->p2p_orm_client->request('GET', 'transfer_sheet', [
            'query' => $this->input->get()
        ])->getBody()->getContents();
        echo $data;
        die();
    }

    /**
     * 債權明細 -- 移轉成功 excel
     * 
     * @created_at                   2023-08-25
     * @created_by                   Howard
     */
    public function transfer_sheet_spreadsheet()
    {

        $res = $this->p2p_orm_client->request('GET', 'transfer_sheet/excel', [
            'query' => $this->input->get()
        ]);
        $des = $res->getHeader('content-disposition')[0];
        $data = $res->getBody()->getContents();
        // create download file by data
        header('content-type: application/octet-stream');
        header('content-disposition:' . $des);
        header('content-length: ' . strlen($data));
        echo $data;
        die();
    }

    public function assets_list()
    {
        ini_set('display_errors','off');
        $page_data = array('type' => 'list');
        $list = array();
        $amortization = array();
        $targets = array();
        $input = $this->input->get(NULL, TRUE);
        $post = $this->input->post(NULL, TRUE);
        $target_no = '';
        $fields = ['status', 'target_no'];
        $target_status = $this->config->item('target_status');
        $type = isset($post['type']) && $post['type'] != '' ? $post['type'] : false;
        $export = isset($post['ids']) && $post['ids'] ? explode(',', $post['ids']) : false;
        $josn = isset($post['data']) && $post['data'] ? explode(',', $post['data']) : false;
        $amortization_format = array(
            'principal' => 0,
            'interest' => 0,
            'delay_interest' => 0,
            'ar_fees' => 0,
            'repayment' => 0,
            'liquidated_damages' => 0,
            'r_principal' => 0,
            'r_interest' => 0,
            'r_fees' => 0,
            'r_delayinterest' => 0,
            'r_prepayment_allowance' => 0,
            'r_damages' => 0,
            'r_preapymentDamages' => 0,
            'r_subloan_fees' => 0,
        );

        foreach ($fields as $field) {
            if (isset($post[$field]) && trim($post[$field]) != '') {
                if ($field == 'target_no') {
                    $target_no = '%' . trim($post[$field]) . '%';
                } else {
                    $where[$field] = $post[$field];
                }
            }
        }


        if ($target_no != '' || $export || $josn) {
            isset($where['status'])
                ? $where = $this->target_query_status($where)
                : $where['status'] = [5 ,10];
            $query = $target_no != ''
                ? ['target_no like' => $target_no]
                : $where;
            isset($post['delay']) && $post['delay'] != '' ? $query['delay'] = ($post['delay'] == 0 ? 0 : 1) : '';
            isset($post['user_id']) && $post['user_id'] != '' ? $wheres['user_id'] = $post['user_id'] : '';
            isset($post['trans_status']) && $post['trans_status'] != '' ? $wheres['transfer_status'] = $post['trans_status'] : '';
            isset($post['sdate']) && $post['sdate'] != '' ? $wheres['created_at >='] = strtotime($post['sdate']) : '';
            isset($post['edate']) && $post['edate'] != '' ? $wheres['created_at <='] = strtotime($post['edate']) : '';
            $target_ids = array();
            $target_list = $this->target_model->get_many_by(
                $query
            );
            if($type == 'platform_assets'){
                $list = $target_list;
            }
            elseif ($target_list) {
                foreach ($target_list as $key => $value) {
                    $target_ids[] = $value->id;
                }
                $wheres['target_id'] = $target_ids;
            }

            if (isset($wheres['target_id']) || isset($wheres['user_id']) || isset($wheres['transfer_status']) || $export) {
                $wheres['status'] = [3 ,10];
                $export ? $wheres = [ 'id' => $export] : '';
                $list = $this->investment_model->order_by('target_id', 'ASC')->get_many_by($wheres);
                $target_ids = array();
                $ids = array();
                $user_list = array();

                foreach ($list as $key => $value) {
                    $target_ids[] = $value->target_id;
                    $ids[] = $value->id;
                }

                if ($target_list) {
                    foreach ($target_list as $key => $value) {
                        $user_list[] = $value->user_id;
                        $targets[$value->id] = $value;
                    }
                }
            }

            if ($list) {
                $this->load->model('user/user_meta_model');
                $target_delay_range = $this->config->item('target_delay_range');
                foreach ($list as $key => $value) {
                    $list[$key]->amortization_table = $this->target_lib->get_investment_amortization_table($targets[$value->target_id], $value, true);
                    if (in_array($type,['amortization','platform_assets']) && $list[$key]->amortization_table && !empty($list[$key]->amortization_table['list'])) {
                        foreach ($list[$key]->amortization_table['list'] as $k => $v) {
                            if ($v['repayment_date'] == null) {
                                continue;
                            }
                            $odate = $ndate = $date = $v['repayment_date'];
                            if (!isset($amortization[$ndate])) {
                                if(date('d', strtotime($date)) != 10){
                                    $ym = date('Y-m', strtotime($odate));
                                    $pay_date = date('Y-m-', strtotime($ym )) . REPAYMENT_DAY;
                                    $ndate = $odate > $pay_date ? date('Y-m-', strtotime($ym . ' + 1 month')) . REPAYMENT_DAY : $pay_date;
                                }
                                !isset($amortization[$ndate]) ? $amortization[$ndate] = $amortization_format : '';
                            }
                            $amortization[$ndate]['principal'] += $v['principal'];
                            $amortization[$ndate]['interest'] += $v['interest'];
                            $amortization[$ndate]['delay_interest'] += $v['delay_interest'];
                            $amortization[$ndate]['ar_fees'] += $v['ar_fees'];
                            $amortization[$ndate]['repayment'] += $v['repayment'];
                            $amortization[$ndate]['liquidated_damages'] += $v['liquidated_damages'];
                            $amortization[$ndate]['r_principal'] += $v['r_principal'];
                            $amortization[$ndate]['r_interest'] += $v['r_interest'];
                            $amortization[$ndate]['r_fees'] += $v['r_fees'];
                            $amortization[$ndate]['r_delayinterest'] += $v['r_delayinterest'];
                            $amortization[$ndate]['r_prepayment_allowance'] += $v['r_prepayment_allowance'];
                            $amortization[$ndate]['r_damages'] += $v['r_damages'];
                            $amortization[$ndate]['r_preapymentDamages'] += $v['r_preapymentDamages'];
                            $amortization[$ndate]['r_subloan_fees'] += $v['r_subloan_fees'];
                        }
                        ksort($amortization);

                        $month = strtotime(array_keys($amortization)[0]);
                        $lastDate = strtotime(end(array_keys($amortization)));
                        while ($month < $lastDate) {
                            $month = strtotime("+1 month", $month);
                            $nymd = date('Y-m-d', $month);
                            !isset($amortization[$nymd]) ? $amortization[$nymd] = $amortization_format: '';
                        }
                    }
                    else{
                        if(!isset($targets[$value->target_id]->school)||!isset($targets[$value->target_id]->company)) {
                            $get_meta = $this->user_meta_model->get_many_by([
                                'meta_key' => ['school_name', 'school_department','job_company'],
                                'user_id' => $targets[$value->target_id]->user_id,
                            ]);
                            if ($get_meta) {
                                foreach ($get_meta as $skey => $svalue) {
                                    $svalue->meta_key == 'school_name' ? $targets[$value->target_id]->school['school_name'] = $svalue->meta_value : '';
                                    $svalue->meta_key == 'school_department' ? $targets[$value->target_id]->school['school_department'] = $svalue->meta_value : '';
                                    $svalue->meta_key == 'job_company' ? $targets[$value->target_id]->company = $svalue->meta_value : '';
                                }
                            }
                        }
                        if(isset($targets[$value->target_id]->school['school_name'])){
                            $list[$key]->school_name       = $targets[$value->target_id]->school['school_name'];
                            $list[$key]->school_department = $targets[$value->target_id]->school['school_department'];
                        }

                        isset($targets[$value->target_id]->company)?$list[$key]->company=$targets[$value->target_id]->company:'';

                        $list[$key]->target_status = $target_status[$this->target_status($targets[$value->target_id])];
                        $list[$key]->delay_type = $target_delay_range[$this->delay_type($targets[$value->target_id])];
                        isset(json_decode($targets[$value->target_id]->target_data)->credit_level) ? $targets[$value->target_id]->credit_level = json_decode($targets[$value->target_id]->target_data)->credit_level : null;
                    }
                }
            }
        }

        if($export || $josn){
            if (isset($list) && !empty($list)) {
                $cell  = [];
                if($type == 'platform_assets'){
                    $this->load->library('Phpspreadsheet_lib');
                    $mergeTItle = [
                        '0:3' => '本金攤還表-計劃',
                        '4:10' => '提早清償(正常)',
                        '11:15' => '到期已結案(正常)',
                        '16:20' => '正常還款',
                        '21:25' => '逾期中',
                    ];
                    $sheetTItle = ['還款日', '代收-期付本金', '代收-期付利息', '本息合計', '代收-期付本金', '代收-期付利息', '本息合計', '手續費收入-期付金回款', '手續費收入-違約金(提還）', '代收-提前還款補償金', '投資回款淨額', '代收-期付本金', '代收-期付利息', '本息合計', '手續費收入-期付金回款', '投資回款淨額', '代收-期付本金', '代收-期付利息', '本息合計', '手續費收入-期付金回款', '投資回款淨額', '代收-應付借款本金', '代收-應付借款利息', '代收-應付延滯息', '應收款項-違約金(逾期)', '應收款項-期付金回款手續費'];
                    foreach ($amortization as $amortizationKey => $amortizationValue) {
                        $cell[] = [
                            $amortizationKey,
                            $amortizationValue['principal'],
                            $amortizationValue['interest'],
                            $amortizationValue['principal'] + $amortizationValue['interest'],
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                            $amortizationValue['r_principal'],
                            $amortizationValue['r_interest'],
                            $amortizationValue['r_principal'] + $amortizationValue['r_interest'],
                            $amortizationValue['r_fees'],
                            $amortizationValue['r_principal'] + $amortizationValue['r_interest'] - $amortizationValue['r_fees'],
                            $amortizationValue['principal'],
                            $amortizationValue['interest'],
                            $amortizationValue['delay_interest'],
                            $amortizationValue['liquidated_damages'],
                            $amortizationValue['ar_fees'],
                        ];
                    }
                    $contents[] = [
                        'sheet' => '資產管理工作底稿(普匯)',
                        'title' => $sheetTItle,
                        'content' => $cell,
                    ];
                    $file_name = date("YmdHis",time()).'_amortization';
                    $descri = '普匯inFlux 後台管理者 '.$this->login_info->id.' [ 債權管理查詢 ]';
                    $this->phpspreadsheet_lib->excel($file_name,$contents,'本金餘額攤還表','各期金額',$descri,$this->login_info->id,true,[1,2,3],false,$mergeTItle);
                }elseif($type == 'amortization'){
                    $this->load->library('Phpspreadsheet_lib');
                    $sheetTItle = ['還款日', '本金餘額', '當期利息', '本息合計', '違約金', '延滯息', '當期償還本息', '回款手續費', '補貼', '投資回款淨額'];
                    foreach ($amortization as $amortizationKey => $amortizationValue) {
                        $cell[] = [
                            $amortizationKey,
                            $amortizationValue['principal'],
                            $amortizationValue['interest'],
                            $amortizationValue['principal'] + $amortizationValue['interest'],
                            $amortizationValue['r_damages'] + $amortizationValue['r_preapymentDamages'],
                            $amortizationValue['delay_interest'],
                            $amortizationValue['r_principal'] + $amortizationValue['r_interest'],
                            $amortizationValue['r_fees'],
                            $amortizationValue['r_prepayment_allowance'],
                            $amortizationValue['r_principal'] + $amortizationValue['r_interest'] + $amortizationValue['delay_interest'] + $amortizationValue['r_prepayment_allowance'] - $amortizationValue['r_fees'],
                        ];
                    }
                    $contents[] = [
                        'sheet' => '本金攤還表',
                        'title' => $sheetTItle,
                        'content' => $cell,
                    ];
                    $file_name = date("YmdHis",time()).'_amortization';
                    $descri = '普匯inFlux 後台管理者 '.$this->login_info->id.' [ 債權管理查詢 ]';
                    $this->phpspreadsheet_lib->excel($file_name,$contents,'本金餘額攤還表','各期金額',$descri,$this->login_info->id,true,[1,2,3,4,5,6,7,8,9]);
                }else{
                    $product_list = $this->config->item('product_list');
                    $sub_product_list = $this->config->item('sub_product_list');
                    $repayment_type = $this->config->item('repayment_type');
                    $subloan_list = $this->config->item('subloan_list');
                    $transfer_status_list = $this->investment_model->transfer_status_list;
                    $sheetTItle = ['產品名稱','案號','借款人ID','投資人ID','債權總額','投資金額','剩餘本金','核准信評','學校/公司','科系','利率','放款期間','計息方式','放款日期','案件狀態','逾期天數','逾期資產','調降信評','債轉狀態'];
                    $cell  = [];
                    foreach ($list as $key => $value) {
                        $target = $targets[$value->target_id];
                        $cell[$value->id.'|'.$target->id] = [
                            (isset($product_list[$target->product_id])?$product_list[$target->product_id]['name']:'').($target->sub_product_id!=0?'/'.$sub_product_list[$target->sub_product_id]['identity'][$product_list[$target->product_id]['identity']]['name']:'').(isset($target->target_no)?(preg_match('/'.$subloan_list.'/',$target->target_no)?'(產品轉換)':''):''),
                            $target->target_no ,
                            $target->user_id ,
                            $value->user_id ,
                            $target->loan_amount ,
                            $value->loan_amount ,
                            $value->amortization_table["remaining_principal"] ,
                            $target->credit_level ,
                            (isset($value->company)?$value->company:'') . (isset($value->company)&&isset($value->school_name)?'/':'') . (isset($value->school_name)?$value->school_name:'') ,
                            (isset($value->school_department)?$value->school_department:'') ,
                            $target->interest_rate ,
                            $target->instalment ,
                            $repayment_type[$target->repayment] ,
                            $target->loan_date ,
                            $value->target_status ,
                            $target->delay_days ,
                            ($target->delay_days == 0? "N/A" : $value->delay_type) ,
                            ($target->delay_days == 0? "N/A" : $target->credit_level) ,
                            $value->transfer_status == 0 || $value->transfer_status == 1? "N/A" : $transfer_status_list[$value->transfer_status] ,
                        ];
                    }
                    if($export){
                        $this->load->library('Phpspreadsheet_lib');
                        $descri = '普匯inFlux 後台管理者 '.$this->login_info->id.' [ 債權管理查詢 ]';
                        if($type == 'assets'){
                            $contents[] = [
                                'sheet' => '債權明細表',
                                'title' => $sheetTItle,
                                'content' => $cell,
                            ];

                            $file_name = date("YmdHis",time()).'_assets';
                            $this->phpspreadsheet_lib->excel($file_name,$contents,'債權明細表','分割債權細項',$descri,$this->login_info->id,true,[4,5,6],[]);
                        }
                    }else{
                        echo json_encode([
                            'result' => 'SUCCESS',
                            'data' => [
                                'sheet' => '債權明細表',
                                'title' => $sheetTItle,
                                'content' => $cell,
                            ]
                        ]);
                    }
                }
            }
            else{
                echo json_encode([
                    'result' => 'SUCCESS',
                    'data' => []
                ]);
            }
        }else{
            $page_data['type_status'] = $this->config->item('target_status');
            $this->load->view('admin/_header');
            $this->load->view('admin/_title', $this->menu);
            $this->load->view('admin/target/targets_assets2', $page_data);
            $this->load->view('admin/_footer');
        }
    }

    public function target_status($target = false)
    {
        if ($target->status == 5 && $target->delay == 0) {
            $current_status = 0;
        } elseif ($target->status == 10 && in_array($target->sub_status, [0, 8, 10])) {
            $current_status = 1;
        } elseif ($target->status == 10 && in_array($target->sub_status, [4])) {
            $current_status = 2;
        } elseif ($target->status == 5 && $target->delay == 1 && $target->delay_days > 7) {
            $current_status = 3;
        } elseif ($target->status == 10 && $target->sub_status == 2) {
            $current_status = 4;
        }
        return $current_status;
    }

    private function target_query_status($query)
    {
        if ($query['status'] == 0) {
            $query['status'] = [5];
            $query['delay'] = 0;
        } elseif ($query['status'] == 1) {
            $query['status'] = 10;
            $query['sub_status'] = [0, 8, 10];
        } elseif ($query['status'] == 2) {
            $query['status'] = 10;
            $query['sub_status'] = [4];
        } elseif ($query['status'] == 3) {
            $query['status'] = 5;
            $query['delay'] = 1;
            $query['delay_days >'] = 7;
        } elseif ($query['status'] == 4) {
            $query['status'] = 10;
            $query['sub_status'] = [2];
        }
        return $query;
    }

    private function target_delay_type($query)
    {
        if ($query['status'] == 0) {
            $query['delay'] = 0;
        } elseif ($query['status'] == 1) {
            $query['delay'] = 1;
        }
        $query['status'] = [5, 10];
        return  $query;
    }

    public function delay_type($target = false)
    {
        $delay_days = $target->delay_days;
        $delay_type = 0;
        if($delay_days > 30 && $delay_days <= 59){
            $delay_type = 1;
        }elseif($delay_days > 60 && $delay_days <= 89){
            $delay_type = 2;
        }elseif($delay_days > 90 && $delay_days <= 119){
            $delay_type = 3;
        }elseif($delay_days > 120){
            $delay_type = 4;
        }
        return $delay_type;
    }
}

?>

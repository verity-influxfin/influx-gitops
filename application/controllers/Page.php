<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Google\Cloud\Storage\StorageClient;
use GuzzleHttp\Client;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;
use Lcobucci\JWT\Signer\Key;

class Page extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ( ! app_access())
        {
            show_404();
        }
        $this->load->model('user/user_model');
        $this->load->model('user/user_meta_model');
        $this->load->model('user/sale_dashboard_model');
        $this->load->model('loan/target_model');
    }

    public function update_target_info(String $from)
    {
        $limit = $this->input->get('limit') ?? 1;

        try
        {
            $day_from = new DateTimeImmutable(date($from));
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
            exit;
        }

        for ($i = 0; $i < $limit; $i++)
        {
            $this->_update_target_info_at_day($day_from->modify("+$i day"));
        }
        echo 'done';
    }

    // 電子看板 排程功能 預定每天早上 9 點執行
    public function update_eboard_info()
    {
        $date = $this->input->get('date');
        if ($timestamp = strtotime($date))
        {
            $today = (new DateTimeImmutable(date('Y-m-d', $timestamp)));
        }
        else
        {
            $today = (new DateTimeImmutable(date('Y-m-d')));
        }

        // 從 ga 抓官網流量 - 昨天的
        $analytics = $this->_initialize_analytics();
        $ga_amounts = $this->_get_report($analytics, $today->modify('-1 day')->format('Y-m-d'));

        // 更新官網流量到 db
        $this->sale_dashboard_model->set_amounts_at($today->modify('-1 day'), Sale_dashboard_model::TARGET_WEB_TRAFFIC, $ga_amounts);

        // 更新會員與案件資訊
        $this->_update_target_info_at_day($today->modify('-1 day'));

        try {
            // 更新 Android 下載量 - 四天前的才有數據(google 報表更新怎麼比 apple 還慢?)
            for ($i = 0; $i < 3; $i++) {
                $modifier = 4 + $i;
                $android_amounts = $this->_get_android_install_report($today->modify("-{$modifier} day"));
                $this->sale_dashboard_model->set_amounts_at($today->modify("-{$modifier} day"), Sale_dashboard_model::TARGET_DOWNLOAD_ANDROID, $android_amounts);
            }
        } catch (Exception $e) {
            log_message('error', "android amounts: " . json_encode($e->getMessage(), JSON_UNESCAPED_UNICODE));
        }


        try {
            // 更新 iOS 下載量 - 前天的
            for ($i = 0; $i < 3; $i++) {
                $modifier = 2 + $i;
                $ios_amounts = $this->_get_ios_sales_summary_data($today->modify("-{$modifier} day")->format('Y-m-d'));
                $this->sale_dashboard_model->set_amounts_at($today->modify("-{$modifier} day"), Sale_dashboard_model::TARGET_DOWNLOAD_IOS, $ios_amounts);
            }
        } catch (Exception $e) {
            log_message('error', "ios amounts: " . json_encode($e->getMessage(), JSON_UNESCAPED_UNICODE));
        }

        echo 'ok';
    }

    private function _update_target_info_at_day(DateTimeInterface $date)
    {
        // 新會員數量
        $new_member = $this->user_model->get_new_members_at_day($date);
        $this->sale_dashboard_model->set_amounts_at(
            $date,
            Sale_dashboard_model::TARGET_USER_REGISTER,
            $new_member
        );

        // 各產品申貸案
        $loan_targets = $this->target_model->get_loan_targets_at_day($date);
        foreach ($loan_targets as $key => $value)
        {
            $this->sale_dashboard_model->set_amounts_at(
                $date,
                $this->sale_dashboard_model->get_loan_mapping_eboard_key_to_type()[$key],
                $value
            );
        }

        // 各產品成交案件
        $deal_targets = $this->target_model->get_deal_targets_at_day($date);
        foreach ($deal_targets as $key => $value)
        {
            $this->sale_dashboard_model->set_amounts_at(
                $date,
                $this->sale_dashboard_model->get_deal_mapping_eboard_key_to_type()[$key],
                $value
            );
        }
    }

    public function eboard()
    {
        $this->load->view('eboard_page');
    }

    public function good_case()
    {
        $this->load->view('good_case_page');
    }

    public function get_eboard_data()
    {
        $retval = [];
        $download = [];
        $first_day = (new DateTimeImmutable(date('Y-m-d')))->modify('- 7 day');
        $weather = $this->_get_today_weather();

        for ($i = 0; $i < 7; $i++)
        {
            $date = $i > 0 ? $first_day->modify("+ {$i} day") : $first_day;
            $amounts_ga = $this->sale_dashboard_model->get_amounts_at($date, [Sale_dashboard_model::TARGET_WEB_TRAFFIC]);

            $retval[] = [

                // 日期
                'date' => $tx_date = $date->format('Y/m/d'),

                // 官網流量
                'official_site_trends' => $amounts_ga[Sale_dashboard_model::TARGET_WEB_TRAFFIC] ?? 0,

                // 新增會員
                'new_member' => $this->user_model->get_new_members_at_day($date),

                // 會員總數
                'total_member' => $this->_get_total_member($date),

                // 各產品每月申貸數
                'product_bids' => $this->target_model->get_loan_targets_at_day($date),

                // 成交
                'deals' => $this->_get_deals($date),
            ];

            // APP下載的時間區間要提前，所以api分開放
            $download_date = $date->modify("-3 day");
            $amounts_app = $this->sale_dashboard_model->get_amounts_at(
                $download_date,
                [
                    Sale_dashboard_model::TARGET_DOWNLOAD_ANDROID,
                    Sale_dashboard_model::TARGET_DOWNLOAD_IOS,
                ]);
            $download[] = [
                'date' => $download_date->format('Y/m/d'),
                'android_downloads' => $amounts_app[Sale_dashboard_model::TARGET_DOWNLOAD_ANDROID] ?? 0,
                'ios_downloads' => $amounts_app[Sale_dashboard_model::TARGET_DOWNLOAD_IOS] ?? 0,
            ];
        }
        $qr = $this->_get_total_qrcode_apply();

        usort($retval, function ($a, $b)
        {
            return $b['date'] <=> $a['date'];
        });

        $this->output->set_content_type('application/json')
            ->set_output(json_encode([
                'result' => 'success',
                'data' => [
                    'history' => $retval,
                    'app_download' => $download,
                    'qrcode' => $qr,
                    'weather' => $weather,
                    'loan_distribution' => $this->_get_loan_distribution(),
                    'loan_statistic' => $this->_get_loan_statistic(strtotime('-7 days'), time()),
                    'platform_statistic' => $this->_get_platform_statistic(),
                ],
            ]));
    }

    public function get_good_case()
    {
        // from application/controllers/api/v2/Website.php list_get()
        $list = [];
        $where = [
            'status' => [3, 4, 5, 10],
            'created_at >=' => strtotime('-30 days'),
        ];
        $orderby = 'created_at';
        $sort = 'desc';
        $filters = $this->input->get('filters');
        if($filters['start_date'] && $filters['end_date']){
            $where['created_at >='] = strtotime($filters['start_date']);
            $where['created_at <='] = strtotime($filters['end_date']);
        }
        if($filters['target_no']){
            $where['target_no like'] = '%'.$filters['target_no'].'%';
        }
        if($filters['order_by']){
            $orderby = $filters['order_by'];
        }
        $this->target_model->order_by($orderby,$sort);
        
        $this->target_model->limit(1000);
        $target_list  = $this->target_model->get_many_by($where);
        $product_list = $this->config->item('product_list');
        $user_meta = new stdClass();

        if(!empty($target_list)){
            foreach($target_list as $key => $value){
                $user_info 	= $this->user_model->get($value->user_id);
                $user		= [];
                if($user_info){
                    $product = $product_list[$value->product_id];
                    $sub_product_id = $value->sub_product_id;
                    $product_name = $product['name'];
                    if($this->is_sub_product($product,$sub_product_id)){
                        $product = $this->trans_sub_product($product,$sub_product_id);
                        $product_name = $product['name'];
                    }

                    $age = get_age($user_info->birthday);
                    if ($product_list[$value->product_id]['identity'] == 1) {
                        $user_meta = $this->user_meta_model->get_by(['user_id' => $value->user_id, 'meta_key' => 'school_name']);
                        if (is_object($user_meta)) {
                            $user_meta->meta_value = preg_replace('/\(自填\)/', '', $user_meta->meta_value);
                        } else {
                            $user_meta = new stdClass();
                            $user_meta->meta_value = '未提供學校資訊';
                        }
                    } elseif ($product_list[$value->product_id]['identity'] == 2) {
                        $meta_info = $this->user_meta_model->get_many_by([
                            'user_id' => $value->user_id,
                            'meta_key' => ['job_company', 'diploma_name']
                        ]);
                        if ($meta_info) {
                            $job_company = ($meta_info[0]->meta_key == 'job_company'
                                ? $meta_info[0]->meta_value
                                : (isset($meta_info[1]) >= 2
                                    ? $meta_info[1]->meta_value
                                    : false));
                            $diploma_name = $meta_info[0]->meta_key == 'diploma_name'
                                ? $meta_info[0]->meta_value
                                : (isset($meta_info[1]) >= 2
                                    ? $meta_info[1]->meta_value
                                    : false);
                            $user_meta->meta_value = $job_company ? $job_company : $diploma_name;
                        } else {
                            $user_meta = new stdClass();
                            $user_meta->meta_value = '未提供相關資訊';
                        }
                    }

                    $user = array(
                        'sex' 			=> $user_info->sex,
                        'age'			=> $age,
                        'company_name'	=> $user_meta?$user_meta->meta_value:'',
                    );

                    $targetDatas = [];
                    $targetData = json_decode($value->target_data);
                    if($product['visul_id'] == 'DS2P1'){
                        $targetDatas = [
                            'brand' => $targetData->brand,
                            'name' => $targetData->name,
                            'selected_image' => $targetData->selected_image,
                            'purchase_time' => $targetData->purchase_time,
                            'factory_time' => $targetData->factory_time,
                            'product_description' => $targetData->product_description,
                        ];
                        foreach ($product['targetData'] as $skey => $svalue) {
                            if(in_array($skey,['car_photo_front_image','car_photo_back_image','car_photo_all_image','car_photo_date_image','car_photo_mileage_image'])){
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
                            }
                        }
                        $user = array(
                            'sex' 			=> '',
                            'age'			=> '',
                            'company_name'	=> '',
                        );
                    }
                }

                $reason = $value->reason;
                $json_reason = json_decode($reason);
                if(isset($json_reason->reason)){
                    $reason = $json_reason->reason.' - '.$json_reason->reason_description;
                }

                $param = [
                    'id' 				=> intval($value->id),
                    'target_no' 		=> $value->target_no,
                    'product_name' => $product_name,
                    'product_id' 		=> intval($value->product_id),
                    'sub_product_id' => intval($value->sub_product_id),
                    'credit_level' 		=> intval($value->credit_level),
                    'user_id' 			=> intval($value->user_id),
                    'user' 				=> $user,
                    'loan_amount' 		=> intval($value->loan_amount),
                    'interest_rate' 	=> floatval($value->interest_rate),
                    'instalment' 		=> intval($value->instalment),
                    'repayment' 		=> intval($value->repayment),
                    'expire_time' 		=> intval($value->expire_time),
                    'invested' 			=> intval($value->invested),
                    'reason' 			=> $reason,
                    'targetDatas' => $targetDatas,
                    'isTargetOpaque' => $sub_product_id==9999?true:false,
                    'status' 			=> intval($value->status),
                    'sub_status' 		=> intval($value->sub_status),
                    'created_at' 		=> intval($value->created_at),
                ];

                isset($targetData->original_interest_rate) && $targetData->original_interest_rate != $value->interest_rate ? $param['is_rate_increase'] = true : '';

                $list[] = $param;
            }
        }
        echo json_encode($list);
        die();
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
            'status' => $sub_product['status'],
        );
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

    private function _get_deals(DateTimeInterface $date)
    {
        $total_deals = 0;
        $deals = $this->target_model->get_deal_targets_at_day($date);
        foreach ($deals as $value)
        {
            $total_deals += $value;
        }

        return $total_deals;
    }

    private function _get_total_member(DateTimeInterface $date)
    {
        $unixtime_query = sprintf('FROM_UNIXTIME(created_at, \'%s\')', $date->format('Y m d'));

        $query = $this->user_model->db->select('COUNT(id) AS amount')
            ->select($unixtime_query . ' AS date')
            ->from('p2p_user.users')
            ->where([
                'created_at <' => $date->modify('+1 day')->getTimestamp(),
            ])
            ->group_by($unixtime_query)
            ->get()
            ->first_row('array');
        return $query['amount'] ?? 0;
    }

    /**
     * 取得公司同事QRCode推廣績效
     * @return array
     * [
     *     [
     *         'name' => 'XXX',
     *         'user_id' => 82,
     *         'full_member_count' => 100, // 註冊+下載核貸數量
     *         'student_count' => 10, // 學生貸核貸數量
     *         'salary_man_count' => 20, // 上班族貸核貸數量
     *     ],
     * ]
     */
    private function _get_total_qrcode_apply()
    {
        $this->load->config('influx_users');
        $user_list = $this->config->item('influx_user_list');
        $user_ids = array_column($user_list, 'user_id');

        // 公司【內】部人員
        $this->load->library('user_lib');
        $data_list = $this->user_lib->getPromotedRewardInfo(['user_id' => $user_ids]);
        $insider = [];
        foreach ($data_list as $data)
        {
            $insider[] = [
                'user_id' => $data['info']['user_id'] ?? '',
                'name' => $data['info']['name'] ?? '',
                'full_member_count' => $data['fullMemberCount'] ?? 0,
                'student_count' => $value['loanedCount']['student'] ?? 0,
                'salary_man_count' => $value['loanedCount']['salary_man'] ?? 0,
            ];
        }

        // 公司【外】部人員
        $data_list = $this->user_lib->getPromotedRewardInfo(['user_id NOT' => $user_ids]);
        $outsider = [];
        foreach ($data_list as $data)
        {
            $outsider[] = [
                'user_id' => $data['info']['user_id'] ?? '',
                'name' => $data['info']['name'] ?? '',
                'full_member_count' => $data['fullMemberCount'] ?? 0,
                'student_count' => $value['loanedCount']['student'] ?? 0,
                'salary_man_count' => $value['loanedCount']['salary_man'] ?? 0,
            ];
        }
        usort($outsider, function ($a, $b)
        {
            if ($a['full_member_count'] == $b['full_member_count'])
            {
                return 0;
            }

            return ($a['full_member_count'] > $b['full_member_count']) ? -1 : 1;
        });

        return [
            'insider' => $insider,
            'outsider' => array_slice($outsider, 0, 20),
        ];
    }

    // get ios downloads at daily report infos
    private function _get_ios_sales_summary_data(String $date_string)
    {
        $client = new GuzzleHttp\Client();
        $res = $client->request('GET', 'https://api.appstoreconnect.apple.com/v1/salesReports', [
            'headers' => [
                'Accept' => 'application/a-gzip',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Authorization' => 'Bearer ' . $this->_get_app_store_connect_api_token(),
            ],
            'query' => [
                'filter[frequency]' => 'DAILY',
                'filter[reportSubType]' => 'SUMMARY',
                'filter[reportDate]' => $date_string, // YYYY-MM-DD
                'filter[reportType]' => 'SALES',
                'filter[vendorNumber]' => '88313024',
                'filter[version]' => '1_0',
            ],
            'decode_content' => FALSE,
        ]);

        $text = gzdecode($res->getBody());
        $matrix = $this->_parse_file_to_array($text);

        $amounts = 0;
        foreach ($matrix as $key => $list)
        {
            if ($list[2] == 'com.influxfin.borrow' && $list[6] == 1)
            {
                $amounts += $list[7];
            }
        }

        return $amounts;
    }

    private function _get_app_store_connect_api_token()
    {
        $this->load->driver('cache', [
            'adapter' => 'apc',
            'backup' => 'file',
        ]);

        $key = 'app_store_connect_api_token';

        if ( ! $token = $this->cache->get($key))
        {
            $token = $this->_generate_app_store_connect_api_token();

            // 存放 5 分鐘
            $this->cache->save($key, $token, 1200);
        }
        return $token;
    }

    private function _generate_app_store_connect_api_token()
    {
        $time = new DateTimeImmutable();
        $signer = new Sha256();
        $token = new Builder();

        $token->setHeader('alg', 'ES256');
        $token->setHeader('typ', 'JWT');
        $token->setHeader('kid', '7RU6XMTDHZ');

        $token->set('iss', 'd1131f2b-f0a2-4e6a-b7c8-3ec40137057a');
        $token->set('iat', $time);
        $token->set('exp', $time->modify('+20 min'));
        $token->set('aud', 'appstoreconnect-v1');
        $ios_key = getenv('APP_STORE_KEY_DIR') . '/ios_key.txt';
        return $token->getToken($signer, new Key(file_get_contents($ios_key)));
    }

    // init google client for ga service
    private function _initialize_analytics()
    {
        $KEY_FILE_LOCATION = getenv('GCP_JSON_KEY_DIR') . '/influx-e-board-f5ba47ed5c0d.json';

        // Create and configure a new client object.
        $client = new Google\Client();
        $client->setApplicationName('Hello Analytics Reporting');
        $client->setAuthConfig($KEY_FILE_LOCATION);
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        $analytics = new Google_Service_AnalyticsReporting($client);
        return $analytics;
    }

    // get user amounts at ga report
    private function _get_report($analytics, $date_string)
    {
        // Replace with your view ID, for example XXXX.
        $VIEW_ID = '217790473';

        // Create the DateRange object.
        $dateRange = new Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate($date_string);
        $dateRange->setEndDate($date_string);

        $users = new Google_Service_AnalyticsReporting_Metric();
        $users->setExpression('ga:users');
        $users->setAlias('users');

        // Create the ReportRequest object.
        $request = new Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId($VIEW_ID);
        $request->setDateRanges($dateRange);
        $request->setMetrics([$users]);

        $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests([$request]);
        $reports = $analytics->reports->batchGet($body);

        if (empty($reports)) {
            return 0;
        }
        
        $data = $reports[0]->getData();
        if (!$data) {
            return 0;
        }

        $totals = $data->getTotals();
        if (empty($totals) || $totals[0]->getValues()[0] < 1) {
            return 0;
        }
    
        $rows = $data->getRows();
        if (empty($rows)) {
            return 0;
        }
    
        $metrics = $rows[0]->getMetrics();
        if (empty($metrics) || empty($metrics[0]->getValues())) {
            return 0;
        }

        return $metrics[0]->getValues()[0];
    }

    // 取得 google play 的報表資料
    private function _get_android_install_report(DateTimeInterface $date)
    {
        $report_month = $date->format('Ym');
        $date_string = $date->format('Y-m-d');

        $KEY_FILE_LOCATION = getenv('GCP_JSON_KEY_DIR') . '/influx-e-board-f5ba47ed5c0d.json';
        $report_bucket = 'pubsite_prod_rev_17015371377322917626';
        $report_path = 'stats/installs/installs_com.influxfin.borrow_' . $report_month . '_overview.csv';

        $storage = new StorageClient([
            'keyFile' => json_decode(file_get_contents($KEY_FILE_LOCATION), TRUE),
        ]);

        $storage->registerStreamWrapper();
        $contents = file_get_contents("gs://{$report_bucket}/{$report_path}");
        $matrix = $this->_parse_file_to_array($contents);

        $amounts = 0;
        foreach ($matrix as $key => $list)
        {
            $filter = preg_replace('/[^a-zA-Z0-9.,-]/u', '', (string) $list[0]);
            $row_data = explode(',', $filter);
            if ($row_data[0] == $date_string)
            {
                $amounts = $row_data[2];
            }
        }

        return $amounts;
    }

    private function _get_today_weather()
    {
        $url = 'https://www.metaweather.com/api/location/2306179/';
        $res = curl_get($url);
        $json = json_decode($res, true);
        return $json['consolidated_weather'][0]['weather_state_abbr'] ?? ['weather_state_abbr' => ''];
    }

    private function _get_loan_distribution(): array
    {
        $mapping_city_name = [
            'A' => '臺北市',
            'B' => '臺中市',
            'C' => '基隆市',
            'D' => '臺南市',
            'E' => '高雄市',
            'F' => '新北市',
            'G' => '宜蘭縣',
            'H' => '桃園市',
            'J' => '新竹縣',
            'K' => '苗栗縣',
            'L' => '臺中市',
            'M' => '南投縣',
            'N' => '彰化縣',
            'P' => '雲林縣',
            'Q' => '嘉義縣',
            'R' => '臺南縣',
            'S' => '高雄市',
            'T' => '屏東縣',
            'U' => '花蓮縣',
            'V' => '臺東縣',
            'X' => '澎湖縣',
            'Y' => '臺北市',
            'W' => '金門縣',
            'Z' => '連江縣',
            'I' => '嘉義市',
            'O' => '新竹市',
        ];

        $list = $this->user_model->db
            ->select('LEFT(u.id_number,1) as city')
            ->from('p2p_loan.targets t')
            ->join('p2p_user.users u', 'u.id = t.user_id ')
            ->where_in('t.status', [TARGET_REPAYMENTING, TARGET_REPAYMENTED])
            ->get()
            ->result_array();

        $rs = array_count_values(array_map('strtoupper', array_column($list, 'city')));
        $result = [];
        foreach ($rs as $code => $amount)
        {
            $result[] = [
                'name' => $mapping_city_name[$code],
                'value' => $amount,
            ];
        }

        return $result;

    }

    private function _get_loan_statistic($start_timestamp = 0, $end_timestamp = 0)
    {
        $this->load->model('transaction/transaction_model');

        $this->transaction_model->db
            ->select('DATE_FORMAT(FROM_UNIXTIME(created_at), \'%Y-%m-%d %H:00\') AS tx_datetime,')
            ->from('p2p_transaction.transactions')
            ->where('source =', SOURCE_LENDING)
            ->group_by('target_id');
        if ($start_timestamp)
        {
            $this->transaction_model->db->where('created_at >= ', $start_timestamp);
        }
        if ($end_timestamp)
        {
            $this->transaction_model->db->where('created_at <= ', $end_timestamp);
        }
        $sub_query = $this->transaction_model->db->get_compiled_select('', TRUE);

        $list = $this->transaction_model->db
            ->select('r.tx_datetime, COUNT(1) AS cnt')
            ->from("({$sub_query}) AS r")
            ->group_by('r.tx_datetime')
            ->get()->result_array();

        $result = [];
        foreach ($list as $info)
        {
            $result[] = [
                'date' => date('Y/m/d', strtotime($info['tx_datetime'] . ' UTC+8')),
                'time' => date('H:00', strtotime($info['tx_datetime'] . ' UTC+8')),
                'value' => (int) $info['cnt'],
            ];
        }

        return $result;
    }

    private function _get_platform_statistic()
    {
        $daily_list = $this->target_model->db
            ->select('loan_date, count(1) AS cnt')
            ->from('p2p_loan.targets')
            ->group_by('loan_date')
            ->where('loan_date IS NOT NULL', NULL, TRUE)
            ->get()->result_array();
        $monthly_list = $this->target_model->db
            ->select('loan_date, count(1) AS cnt')
            ->from('p2p_loan.targets')
            ->group_by('DATE_FORMAT(loan_date, \'%Y-%m\')')
            ->where('loan_date IS NOT NULL', NULL, TRUE)
            ->get()->result_array();

        return [
            'daily_highest_count' => (int) max(array_column($daily_list, 'cnt')),
            'monthly_highest_count' => (int) max(array_column($monthly_list, 'cnt')),
            'total_investment_count' => $this->target_model->get_transaction_count(),
        ];
    }

    private function _parse_file_to_array($contents)
    {
        return array_map(function ($row)
        {
            return explode("\t", $row);
        }, array_filter(
            explode(PHP_EOL, $contents),
            function ($row)
            {
                return  ! empty($row);
            }
        ));
    }
}

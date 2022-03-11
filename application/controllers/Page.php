<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Google\Cloud\Storage\StorageClient;
use GuzzleHttp\Client;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;
use Lcobucci\JWT\Signer\Key;

class Page extends CI_Controller
{

    // 電子看板 排程功能 預定每天早上 9 點執行
    public function update_eboard_info()
    {
        $today = (new DateTimeImmutable(date('Y-m-d')));
        $this->load->model('user/sale_dashboard_model');

        // 從 ga 抓官網流量 - 昨天的
        $analytics = $this->_initialize_analytics();
        $ga_amounts = $this->_get_report($analytics, $today->modify('-1 day')->format('Y-m-d'));

        // 更新官網流量到 db
        $this->sale_dashboard_model->set_amounts_at($today->modify('-1 day'), sale_dashboard_model::PLATFORM_TYPE_GOOGLE_ANALYTICS, $ga_amounts);

        // 更新 iOS 下載量 - 前天的
        $ios_amounts = $this->_get_ios_sales_summary_data($today->modify('-2 day')->format('Y-m-d'));
        $this->sale_dashboard_model->set_amounts_at($today->modify('-2 day'), sale_dashboard_model::PLATFORM_TYPE_IOS, $ios_amounts);

        // 更新 Android 下載量 - TODO
        echo 'ok';
    }

    // 首次上線後，跑這隻更新最近七天的數據資料
    public function init_eboard_info()
    {
        $today = (new DateTimeImmutable(date('Y-m-d')));
        $this->load->model('user/sale_dashboard_model');

        // ga init
        $analytics = $this->_initialize_analytics();

        for ($i = 1; $i <= 7; $i++)
        {
            // update ga infos
            $ga_amounts = $this->_get_report($analytics, $today->modify("- {$i} day")->format('Y-m-d'));
            $this->sale_dashboard_model->set_amounts_at($today->modify("- {$i} day"), sale_dashboard_model::PLATFORM_TYPE_GOOGLE_ANALYTICS, $ga_amounts);

            // update ios downloads
            $j = $i + 1;
            $ios_amounts = $this->_get_ios_sales_summary_data($today->modify("- {$j} day")->format('Y-m-d'));
            $this->sale_dashboard_model->set_amounts_at($today->modify("- {$j} day"), sale_dashboard_model::PLATFORM_TYPE_IOS, $ios_amounts);
        }

        echo 'ok';
    }

    public function eboard()
    {
        $this->load->view('eboard_page');
    }

    public function get_eboard_data()
    {
        $retval = [];
        $first_day = (new DateTimeImmutable(date('Y-m-d')))->modify('- 6 day');
		$weather = $this->_get_today_weather();

        $this->load->model('user/sale_dashboard_model');

        for ($i = 0; $i < 7; $i++) 
        {
            $date = $i > 0 ? $first_day->modify("+ {$i} day") : $first_day;
            $amounts = $this->sale_dashboard_model->get_amounts_at($date);

            $retval[] = [

                // 日期
                'date' => $tx_date = $date->format('Y/m/d'),

                // 官網流量
                'official_site_trends' => $amounts[sale_dashboard_model::PLATFORM_TYPE_GOOGLE_ANALYTICS] ?? 0,

                // 新增會員
                'new_member' => $this->_get_new_member($date),

                // 會員總數
                'total_member' => $this->_get_total_member($date),

                // APP下載
                'android_downloads' => $amounts[sale_dashboard_model::PLATFORM_TYPE_ANDROID] ?? 0,
                'ios_downloads' => $amounts[sale_dashboard_model::PLATFORM_TYPE_IOS] ?? 0,

                // 各產品每月申貸數
                'product_bids' => $this->_get_product_bids($date),

                // 成交
                'deals' => $this->_get_deals($date),
            ];
        }
		$qr = $this->_get_total_qrcode_apply();

        usort($retval, function ($a, $b) {
            return $b['date'] <=> $a['date'];
        });

        $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'result' => 'success',
                        'data'   => [
							'history' => $retval,
							'qrcode' => $qr,
							'weather' => $weather,
                            'loan_distribution' => $this->_get_loan_distribution(),
                            'loan_statistic' => $this->_get_loan_statistic(strtotime('-7 days'), time()),
                            'platform_statistic' => $this->_get_platform_statistic()
						]
                    ]));
    }

	private function _get_product_bids(DateTimeInterface $date)
	{
		$month_ini = $date->modify("first day of this month");
		$month_end = $date->modify("first day of next month");
		$month_ini = $month_ini->setTime(0, 0, 0);
		$month_end = $month_end->setTime(0, 0, 0);

		$this->target_model->db->select([
				'user_id',
				'product_id',
				'sub_product_id',
				'min(created_at) as first_target_at'
			])->from('p2p_loan.targets')
			->where([
				'created_at >=' => $month_ini->getTimestamp(),
				'created_at <'  => $month_end->getTimestamp(),
			])
			->group_by('user_id');

		$sub_query = $this->target_model->db->get_compiled_select('', TRUE);

		$this->load->model('loan/target_model');
		$query = $this->target_model->db->select([
											'user_id',
											'product_id',
											'sub_product_id',
											'first_target_at'
										])->from("($sub_query) as r")
										->where([
											'first_target_at >=' => $date->getTimestamp(),
											'first_target_at <'  => $date->modify('+1 day')->getTimestamp(),
										])
										->get()
										->result_array();

		$result = [
			'SMART_STUDENT' => 0,
			'STUDENT'       => 0,
			'SALARY_MAN'    => 0,
			'SK_MILLION'    => 0
		];

		foreach ($query as $data)
		{
			switch (TRUE)
			{
				case $data["product_id"] == PRODUCT_ID_STUDENT AND $data["sub_product_id"] == SUBPRODUCT_INTELLIGENT_STUDENT:
					$result['SMART_STUDENT'] += 1;
					break;

				case $data["product_id"] == PRODUCT_ID_STUDENT:
					$result['STUDENT'] += 1;
					break;

				case $data["product_id"] == PRODUCT_ID_SALARY_MAN:
					$result['SALARY_MAN'] += 1;
					break;

				case $data["product_id"] == PRODUCT_SK_MILLION_SMEG:
					$result['SK_MILLION'] += 1;
					break;
			}
		}

		return $result;
	}

    private function _get_deals(DateTimeInterface $date)
    {
        $this->load->model('loan/target_model');
        $query = $this->target_model->db->select([
                                            'COUNT(*) AS amount',
                                            'loan_date'
                                        ])->from('p2p_loan.targets')
                                        ->where_in('status', [5, 10])
                                        ->where([
                                            'loan_status' => 1,
                                            'loan_date'   => $date->format('Y-m-d')
                                        ])
                                        ->group_by('loan_date')
                                        ->get()
                                        ->first_row('array');
        return $query['amount'] ?? 0;
    }

    private function _get_total_member(DateTimeInterface $date)
    {

        $this->load->model('user/user_model');

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

    private function _get_new_member(DateTimeInterface $date)
    {

        $this->load->model('user/user_model');

        $unixtime_query = sprintf('FROM_UNIXTIME(created_at, \'%s\')', $date->format('Y m d'));
        
        $query = $this->user_model->db->select('COUNT(id) AS amount')
                                ->select($unixtime_query . ' AS date')
                                ->from('p2p_user.users')
                                ->where([
                                    'created_at >=' => $date->getTimestamp(),
                                    'created_at <'  => $date->modify('+1 day')->getTimestamp(),
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
    public function _get_total_qrcode_apply()
    {
        $this->load->config('influx_users');
        $user_list = $this->config->item('influx_user_list');
        $user_ids = array_column($user_list, 'user_id');

        $this->load->library('user_lib');
        $data_list = $this->user_lib->getPromotedRewardInfo(['user_id' => $user_ids]);

        $result = [];
        foreach ($data_list as $data)
        {
            $result[] = [
                'user_id' => $data['info']['user_id'] ?? '',
                'name' => $data['info']['name'] ?? '',
                'full_member_count' => $data['fullMemberCount'] ?? 0,
                'student_count' => $value['loanedCount']['student'] ?? 0,
                'salary_man_count' => $value['loanedCount']['salary_man'] ?? 0,
            ];
        }

        return $result;
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

        $matrix = array_map(function ($row) {
            return explode("\t", $row);
        }, array_filter(
            explode(PHP_EOL, $text),
            function ($row) { return ! empty($row); }
        ));

        $amounts = 0;
        // 計算借貸app的下載次數
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
            'backup'  => 'file'
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

        return $token->getToken($signer, new Key(file_get_contents('./ios_key.txt')));
    }

    // init google client for ga service
    private function _initialize_analytics()
    {
        $KEY_FILE_LOCATION = './influx-e-board-f5ba47ed5c0d.json';

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
        $request->setMetrics(array($users));

        $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests(array($request));
        $reports = $analytics->reports->batchGet($body);
        return $reports[0]->getData()->getRows()[0]->getMetrics()[0]->getValues()[0];
    }

	private function _get_today_weather(){
		$url = 'https://www.metaweather.com/api/location/2306179/';
		$res = curl_get($url);
		$json = json_decode($res,true);
		return $json['consolidated_weather'][0]['weather_state_abbr'] ?? ['weather_state_abbr'=>''];
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
            'O' => '新竹市'
        ];

        $this->load->model('user/user_model');

        $list = $this->user_model->db
            ->select('LEFT(u.id_number,1) as city')
            ->from('p2p_loan.targets t')
            ->join('p2p_user.users u', 'u.id = t.user_id ')
            ->where_in('t.status', [TARGET_REPAYMENTING, TARGET_REPAYMENTED])
            ->get()
            ->result_array();

        $rs = array_count_values(array_map('strtoupper',array_column($list, 'city')));
        $result = [];
        foreach ($rs as $code => $amount)
        {
            $result[] = [
                'name' => $mapping_city_name[$code],
                'value' => $amount
            ];
        }

        return $result;

    }

    private function _get_loan_statistic($start_timestamp=0, $end_timestamp=0)
    {
        $this->load->model('transaction/transaction_model');

        $this->transaction_model->db
            ->select('DATE_FORMAT(FROM_UNIXTIME(created_at), \'%Y-%m-%d %H:00\') AS tx_datetime,')
            ->from('p2p_transaction.transactions')
            ->where('source =', SOURCE_LENDING)
            ->group_by('target_id');
        if($start_timestamp)
        {
            $this->transaction_model->db->where('created_at >= ', $start_timestamp);
        }
        if($end_timestamp)
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
                'date' => date('Y/m/d', strtotime($info['tx_datetime'].' UTC+8')),
                'time' => date('H:00', strtotime($info['tx_datetime'].' UTC+8')),
                'value' => (int)$info['cnt']
            ];
        }

        return $result;
    }

    private function _get_platform_statistic()
    {
        $this->load->model('loan/target_model');
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
            'daily_highest_count' => (int)max(array_column($daily_list, 'cnt')),
            'monthly_highest_count' => (int)max(array_column($monthly_list, 'cnt')),
            'total_investment_count' => $this->target_model->get_transaction_count(),
        ];
    }
}

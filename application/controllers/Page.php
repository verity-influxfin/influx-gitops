<?php defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;

class Page extends CI_Controller
{
    public function eboard()
    {
        $this->load->view('eboard_page');
    }

    public function get_eboard_data()
    {
        $retval = [];
        $first_day = (new DateTimeImmutable(date('Y-m-d')))->modify('- 6 day');

        $this->load->model('user/sale_dashboard_model');

        for ($i = 0; $i < 7; $i++) 
        {
            $date = $i > 0 ? $first_day->modify("+ {$i} day") : $first_day;
            $amounts = $this->sale_dashboard_model->get_amounts_at($date);

            $retval[] = [

                // 日期
                'date' => $tx_date = $date->format('Y-m-d'),

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

        usort($retval, function ($a, $b) {
            return $b['date'] <=> $a['date'];
        });

        $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'result' => 'success',
                        'data'   => $retval
                    ]));
    }

	private function _get_product_bids(DateTimeInterface $date)
	{
		$month_ini = new DateTime("first day of this month");
		$month_end = new DateTime("first day of next month");
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

		return implode(' / ', array_values($result));

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
     * @param DateTimeInterface $date
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
    private function _get_total_qrcode_apply(DateTimeInterface $date)
    {
        $this->load->config('influx_users');
        $user_list = $this->config->item('influx_user_list');
        $user_ids = array_column($user_list, 'user_id');

        $this->load->library('user_lib');
        $data_list = $this->user_lib->getPromotedRewardInfo(
            ['user_id' => $user_ids],
            $date->getTimestamp(),
            $date->modify('+1 day')->getTimestamp()
        );

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

    private function _get_ios_sales_summary_data()
    {
        $ch = curl_init();

        $url = 'https://api.appstoreconnect.apple.com/v1/salesReports?filter[frequency]=DAILY&filter[reportSubType]=SUMMARY&filter[reportType]=SALES&filter[vendorNumber]=88313024&filter[version]=1_0';

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept-Encoding: gzip, deflate, br',
            'Authorization: Bearer ' . $this->_get_app_store_connect_api_token()
        ]);
        $result = curl_exec($ch);
        curl_close($ch);

        $text = gzdecode($result);

        $matrix = array_map(function ($row) {
            return explode("\t", $row);
        }, array_filter(
            explode(PHP_EOL, $text),
            function ($row) { return ! empty($row); }
        ));
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
        $time   = new DateTimeImmutable();
        $signer = new Sha256();
        $token  = new Builder();

        $token->setHeader('alg','ES256');
        $token->setHeader('typ','JWT');
        $token->setHeader('kid','7RU6XMTDHZ');

        $token->set('iss','d1131f2b-f0a2-4e6a-b7c8-3ec40137057a');
        $token->set('iat', $time);
        $token->set('exp', $time->modify( '+20 min' ));
        $token->set('aud','appstoreconnect-v1');

        return $token->getToken($signer, new Key('-----BEGIN PRIVATE KEY-----
MIGTAgEAMBMGByqGSM49AgEGCCqGSM49AwEHBHkwdwIBAQQgST75E7HzthaY4Xs2
Y6JOXMA7m9EdhbfPZEsCpskCOPugCgYIKoZIzj0DAQehRANCAAR/W+y0k9sqr4kH
7FiAXKYlA0K/5f/fYj2f2KEme+eIfq7RCqdqTtV7V2oMg93EDYXCgBoPDCRCxzP8
VhvEDNkC
-----END PRIVATE KEY-----'));
    }
}

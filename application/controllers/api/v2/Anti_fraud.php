<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Anti_fraud extends Admin_rest_api_controller
{

    public $user_info;
    public $brookesia_url;

    public function __construct()
    {
        parent::__construct();

        $brookesiaPort = '9453';
        $this->brookesia_url = "http://" . getenv('GRACULA_IP') . ":{$brookesiaPort}/brookesia/api/v1.0/";
    }

    /**
     * 新增風險等級
     * 
     * @created_at          2021-12-09
     * @created_by          Jack
     */
    public function create_risk_level_post()
    {
        $this->payload_validation([

            // 用戶 ID
            'userId'      => 'required|int',

            // 項目
            'item'        => 'required|string',

            // 資料來源
            'dataSource' => 'required|string',

            // 歸類
            'category'    => 'required|string',

            // 內容
            'content'     => 'required|string',

            // 風險
            'risk'        => 'required|string',

            // 解決方式
            'resolution'  => 'required|string',
        ]);

        $this->load->model('user/blockesia_model');

        $result = $this->blockesia_model->create_data([
            'user_id'     => $this->payload['userId'],
            'item'        => $this->payload['item'],
            'data_source' => $this->payload['dataSource'],
            'category'    => $this->payload['category'],
            'content'     => $this->payload['content'],
            'risk'        => $this->payload['risk'],
            'resolution'  => $this->payload['resolution'],
        ]);

        if ($result === FALSE)
        {
            $this->response([
                'result' => 'ERROR',
                'data'   => 'ERROR While Data Insert.'
            ]);
        }

        $this->success();
    }

    /**
     * 取得風險等級資料
     * 
     * @created_at          2021-12-10
     * @created_by          Jack
     */
    public function risk_level_list_get()
    {
        $this->payload_validation([

            // 目前頁數
            'currentPage' => 'int',

            // 每頁筆數
            'perPage'     => 'int',
        ]);

        $this->load->model('user/blockesia_model');

        $result = $this->blockesia_model->get_data(
            $this->payload['currentPage'] ?? 1,
            $this->payload['perPage'] ?? 20
        );

        if (($data = $result->data_result) !== FALSE)
        {
            $this->success([
                'list' => $data,
                'pagination' => [
                    'currentPage' => $result->current_page,
                    'perPage'     => $result->per_page,
                    'lastPage'    => $result->last_page,
                    'totalRows'   => $result->total_rows,
                ]
            ]);
            return;
        }
        $this->response([
            'result' => 'ERROR',
            'data'   => 'DB ERROR.'
        ]);
    }

    /**
     * product_config_get 取得產品名稱列表
     * @return array
     * (
     *  [result] => SUCCESS
     *  [data] => array(
     *      [產品ID] => array(
     *          [name] => 產品名稱,
     *          [identity] => 身份,
     *          [sub_product] => array(
     *              [子產品ID] => 產品名稱,
     *              ...
     *          )
     *      ),
     *      ...
     *  )
     * )
     */
    public function product_config_get()
    {
        $input = $this->input->get();
        $response = [];
        $product_list = $this->config->item('product_list');

        $product_list_data = array_map(
            function($key, $values) {
                return [
                        'id'          => $values['id'] ?? null,
                        'name'        => $values['name'] ?? null,
                        'identity'    => $values['identity'] ?? null,
                        'sub_product' => $values['sub_product'] ?? null
                    ];
            },
            array_keys($product_list),
            $product_list
        );

        foreach ($product_list_data as $product_data)
        {
            $response[$product_data['id']] = $product_data;
            if ( ! empty($response[$product_data['id']]))
            {
                unset($response[$product_data['id']]['id']);
            }
        }

        $sub_product_list = $this->config->item('sub_product_list');
        foreach ($response as $product_key => $product)
        {
            if ( ! empty($product['sub_product'] ?? null))
            {
                foreach($product['sub_product'] as $sub_product_key => $sub_product)
                {
                    if (! empty($product_identity = $sub_product_list[$sub_product]['identity'][$product['identity']] ?? null))
                    {
                        unset($response[$product_key]['sub_product'][$sub_product_key]);
                        $response[$product_key]['sub_product'][$sub_product] = $product_identity['name'];
                    }
                }
            }
        }
        $this->response([
            'result' => 'SUCCESS',
            'data'   => $response
        ]);
    }

    /**
     * 取得所有規則
     * 
     * @created_at      2021-12-03
     * @created_by      Joanne
     */
    public function rule_all_get()
    {
        $url = $this->brookesia_url . 'rule/all';
        $result = curl_get($url);
        $response = json_decode($result, TRUE);

        $this->response($response);
    }

    public function rule_info_get()
    {
        $url = $this->brookesia_url . 'rule/info';
        $result = curl_get($url);
        $response = json_decode($result, TRUE);
        $this->response($response);
    }

    public function rule_statistics_get()
    {
        $input = $this->input->get();
        $url = $this->brookesia_url . 'result/ruleStatistics' .
            '?typeId=' . ($input['typeId'] ?? '') ;
        $result = curl_get($url);
        $response = json_decode($result, TRUE);

        $this->response($response);
    }

    public function rule_results_get()
    {
        $input = $this->input->get();
        $url = $this->brookesia_url . 'result/ruleResults' .
            '?ruleId=' . ($input['ruleId'] ?? '') .
            '&startTime=' . ($input['startTime'] ?? '') .
            '&endTime=' . ($input['endTime'] ?? '');
        $result = curl_get($url);
        $response = json_decode($result, TRUE);

        $this->response($response);
    }

    public function column_map_get()
    {
        $url = $this->brookesia_url . 'result/columnMap';
        $result = curl_get($url);
        $response = json_decode($result, TRUE);

        $this->response($response);
    }

    public function user_id_get()
    {
        $input = $this->input->get();
        $url = $this->brookesia_url . 'result/userId' .
            '?userId=' . ($input['userId'] ?? '');
        $result = curl_get($url);
        $response = json_decode($result, TRUE);

        $this->response($response);
    }

    public function risk_map_get()
    {
        $input = $this->input->get();
        $url = $this->brookesia_url . 'result/riskMap' .
            '?risk=' . ($input['risk'] ?? '');
        $result = curl_get($url);
        $response = json_decode($result, TRUE);

        $this->response($response);
    }

    public function ruleId_get()
    {
        $input = $this->input->get();
        $url = $this->brookesia_url . 'result/ruleId' .
            '?typeId=' . ($input['typeId'] ?? '') . 
            '&ruleId='.($input['ruleId'] ?? '');
        $result = curl_get($url);
        $response = json_decode($result, TRUE);

        $this->response($response);
    }

	public function typeId_get()
    {
        $input = $this->input->get();
        $url = $this->brookesia_url . 'result/typeId' .
            '?typeId=' . ($input['typeId'] ?? '');
        $result = curl_get($url);
        $response = json_decode($result, TRUE);

        $this->response($response);
    }

	public function get_new_tree_get(){
		$url = $this->brookesia_url . 'rule/indexMap';
		$result = curl_get($url);
		$json = json_decode($result, TRUE);
		$this->response($json['response']);
	}

	public function get_anti_list_get(){
		$input = $this->input->get(NULL, TRUE);
		$page = isset($input['page']) ? $input['page'] : 1;
		$count = isset($input['count']) ? $input['count'] : 10;
		$userId = isset($input['userId']) ? $input['userId'] : '';
		$index = isset($input['index']) ? $input['index'] : '';
		$risk = isset($input['risk']) ? $input['risk'] : '';
		$url = $this->brookesia_url . 'result/search'
		.'?page='.$page
		.'&count='.$count
		.'&userId='.$userId
		.'&index='.$index
		.'&risk='.$risk
		;
		$result = curl_get($url);
		if (!$result)
        {
            $error = [
                'message' => '黑名單系統無回應，請洽工程師。'
            ];
            $this->response($error);
        }
		$json = json_decode($result, TRUE);
		if ($json['status'] == 204)
        {
            $this->response(['results' => [] ]);
        }
		$this->response($json['response']);
	}

	public function new_risk_post(){
        $input = json_decode($this->security->xss_clean($this->input->raw_input_stream), TRUE);
		$userId = isset($input['userId']) ? $input['userId'] : '';
		$typeId = isset($input['ruleType']) ? $input['ruleType'] : '';
		$ruleId = isset($input['id']) ? $input['id'] : '';
		$description = isset($input['description']) ? $input['description'] : '';
		$columnMap = isset($input['columnMap']) ? json_encode($input['columnMap']) : '';
		$risk = isset($input['risk']) ? $input['risk'] : '';
		$payload = [
            'userId'        => $userId,
            'typeId'        => $typeId,
            'ruleId'        => $ruleId,
            'description'   => $description,
            'columnMap'     => $columnMap,
            'risk'          => $risk,
        ];
		$url = $this->brookesia_url .'result/add';
        $result = curl_get($url, $payload);
		$json = json_decode($result, TRUE);
		if ($json['status'] == 420)
        {
            $this->response($json['response']);
        }
		$this->response($json);
	}

	public function manual_add_post(){
        $input = json_decode($this->security->xss_clean($this->input->raw_input_stream), TRUE);
		$block_text = isset($input['block_text']) ? $input['block_text'] : '';
		$description = isset($input['description']) ? $input['description'] : '';
		$risk = isset($input['risk']) ? $input['risk'] : '';
		$manually_add_type_id = isset($input['manually_add_type_id']) ? $input['manually_add_type_id'] : '';
		$url = $this->brookesia_url .'rule/'.$manually_add_type_id;
		$payload = [
            'description'   => $description,
            'risk'          => $risk,
			'block'	=> $block_text
        ];
		$result = curl_get($url, $payload);
		$json = json_decode($result, TRUE);
		$this->response($json);
	}
}

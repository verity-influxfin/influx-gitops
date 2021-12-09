<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class AntiFraud extends REST_Controller {

	public $user_info;
	public $brookesia_url;

    public function __construct()
    {
        parent::__construct();

        if(!app_access()){
            $this->response(array('result' => 'ERROR','data' => [ ] ), 401);
        }

        $this->brookesia_url = 'http://52.68.199.159:9453/brookesia/api/v1.0/';
    }

    /**
     * product_config_get 產品名稱列表
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
        $product_list_data = array_map(function($key,$values) {
            return [
                    'id' => $values['id'],
                    'name' => $values['name'],
                    'identity' => $values['identity'],
                    'sub_product' => $values['sub_product']
                ];
        }, array_keys($product_list), $product_list);

        foreach($product_list_data as $product_data){
            $response[$product_data['id']] = $product_data;
            unset($response[$product_data['id']]['id']);
        }

        $sub_product_list = $this->config->item('sub_product_list');
        foreach($response as $product_key => $product){
            if(isset($product['sub_product']) && !empty($product['sub_product'])){
                foreach($product['sub_product'] as $sub_product_key => $sub_product){
                    if(isset($sub_product_list[$sub_product]['identity'][$product['identity']])){
                        unset($response[$product_key]['sub_product'][$sub_product_key]);
                        $response[$product_key]['sub_product'][$sub_product] = $sub_product_list[$sub_product]['identity'][$product['identity']]['name'];
                    }
                }
            }
        }
        $this->response(array('result' => 'SUCCESS','data' => $response));
    }

	public function rule_all_get()
	{
		$url = $this->brookesia_url . 'rule/all';
		$result = curl_get($url);;
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

	public function user_id_get(){
		$input = $this->input->get();
		$url = $this->brookesia_url . 'result/userId' .
			'?userId=' . ($input['userId'] ?? '');
		$result = curl_get($url);
		$response = json_decode($result, TRUE);

		$this->response($response);
	}
	public function risk_map_get(){
		$input = $this->input->get();
		$url = $this->brookesia_url . 'result/riskMap' .
			'?risk=' . ($input['risk'] ?? '');
		$result = curl_get($url);
		$response = json_decode($result, TRUE);

		$this->response($response);
	}
}

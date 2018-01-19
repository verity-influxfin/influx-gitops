<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class User extends REST_Controller {

    public function __construct()
    {
        parent::__construct();

        $method = $this->router->fetch_method();
		
       $nonAuthMethods = ['register'];
        if (!in_array($method, $nonAuthMethods)) {
            $token 	= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:"";
            $tokenData 	= AUTHORIZATION::generateUserToken($token);
            if (empty($tokenData->id)) {
				$this->response(['error' => TOKEN_NOT_CORRECT], 401);
            }
        }
    }
	
	/*public function index_get()
	{
		echo get_ip();
		$this->load->model('user/user_model');
		$rs = $this->user_model->get_all();
		dump($rs);		
		$this->load->model('admin/admin_model');
		$rs = $this->admin_model->get_all();
		dump($rs);
		$this->load->model('product/product_category_model');
		$rs = $this->product_category_model->get_all();
		dump($rs);
		echo AUTHORIZATION::generateUserToken(array("toy"=>"test","yayaya"=>"fffffff"));
		
	}*/
	
	public function register_post()
    {

        $input 		= $this->input->post();
		$data		= array();
        $fields 	= ['name', 'phone', 'email', 'password', 'birthday', 'phone'];
        foreach ($fields as $field) {
            if (empty($input[$field])) {
                $this->response(['error' => INPUT_NOT_CORRECT], 400);
            }else{
				$data[$field] = $input[$field];
			}
        }

        $result = $this->user_model->insert($data);
        if ($result) {
            $this->response(['result' => '']);
        } else {
            $this->response(['error' => EXIT_DATABASE], 400);
        }
    }
}

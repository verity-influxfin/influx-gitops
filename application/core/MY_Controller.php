<?php defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class MY_Controller extends CI_Controller
{
    /**
     * 輸出 Json 資料
     * 
     * @param    array    $data      輸出資料
     * 
     * @created_at                   2021-07-30
     * @created_by                   Jack
     */
    protected function _output_json($data)
    {
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data, JSON_UNESCAPED_SLASHES))
            ->_display();
        exit;
    }
}

/**
 * 管理後臺 Controller
 */
class Admin_controller extends MY_Controller
{

    protected $role_info;
    protected $login_info;
    protected $menu = array();
    protected $edit_method = array();

    /**
     * 建構子
     * 
     * @updated_at    2021-07-29
     * @updated_by    Jack
     */
    public function __construct()
    {
        parent::__construct();
        if ( ! app_access())
        {
            show_404();
        }
        $this->load->model([
            'admin/admin_model',
            'admin/role_model',
            'log/log_admin_model'
        ]);
        $this->load->helper('admin');
        $this->load->helper('cookie');
        $this->load->library('form_validation');
        $this->login_info = check_admin();
        $roles = $this->role_model->get_list();

        $method = $this->router->fetch_method();
        $class  = ucfirst($this->router->fetch_class());
        $nonAuthMethods = ['login'];
        if ($class != 'Admin' || ($method != 'login' && $method != 'logout')) {
            if(empty($this->login_info)){
                redirect(admin_url('Admin/login'), 'refresh');
                die();
            }else{
                if($class != 'TestScript'){
                    $role = $roles[$this->login_info->role_id];
                    $role->permission   = json_decode($role->permission,TRUE);
                    if($class == 'AdminDashboard'){
                        $this->role_info    = array('r'=>1,'u'=>1);
                    }else{
                        $this->role_info    = $role->permission[$class];
                    }
                    
                    if($this->role_info['r']==0){
                        alert('權限不足。',admin_url('AdminDashboard/'));
                        die();
                    }
                    
                    if($this->role_info['u']==0 && in_array($method,$this->edit_method)){
                        alert('權限不足。',admin_url($class.'/'));
                        die();
                    }
                    
                    $admin_menu = $this->config->item('admin_menu');
                    foreach($admin_menu as $key => $value){
                        if($key != 'AdminDashboard'){
                            if(!isset($role->permission[$key]) || $role->permission[$key]['r']==0){
                                unset($admin_menu[$key]);
                            }
                        }
                    }
                    
                    if(in_array($method,$this->edit_method) || $method == "block_users"){
                        $this->log_admin_model->insert(array(
                            'admin_id'   => $this->login_info->id,
                            'url'        => $this->uri->uri_string(),
                            'get_param'  => json_encode($this->input->get(NULL, TRUE)),
                            'post_param' => json_encode($this->input->post(NULL, TRUE)),
                        ));
                    }
                    
                    $this->menu = array(
                        'role_info'  => $this->role_info,
                        'role_name'  => $role->name,
                        'login_info' => $this->login_info,
                        'active'     => $this->router->fetch_class(),
                        'menu'       => $admin_menu,
                    );
                }
            }
        }

    }
}

/**
 * 管理後臺 API Controller
 */
class Admin_api_controller extends MY_Controller
{

    /**
     * @var  Payload Service
     */
    public $payload = null;

    /**
     * 建構子
     * 
     * @created_at                2021-08-16
     * @created_by                Jack
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('utils');

        $this->payload = Service('payload', $this->input);

        // 拋出例外輸出 API JSON 格式
        set_exception_handler([$this, 'error']);
    }

    /**
     * 成功輸出 API 格式
     *
     * @param      array  $data   輸出資料
     * 
     * @created_at                2021-08-16
     * @created_by                Jack
     */
    public function output($data=[])
    {
        if ($data instanceof DEUS_Entity)
        {
            $data = $data->__serialize();
        }
        $this->_output_json([
            'success' => TRUE,
            'data'    => $data
        ]);
    }


    /**
     * 錯誤輸出 API 格式
     *
     * @param      Throwable  $ex     例外物件
     * 
     * @created_at                    2021-08-16
     * @created_by                    Jack
     */
    public function error(Throwable $ex)
    {
        $this->_output_json([
            'success' => FALSE,
            'message' => $ex->getMessage()
        ]);
    }
}

class Admin_rest_api_controller extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

        if( ! app_access())
        {
            $this->response([
                'result' => 'ERROR',
                'data'   => []
            ], 401);
        }
    }
}
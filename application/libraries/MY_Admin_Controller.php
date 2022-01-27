<?

class MY_Admin_Controller extends CI_Controller{
	
	protected $role_info;
	protected $login_info;
	protected $menu	= array();
	protected $edit_method = array();
	
	public function __construct(){
        parent::__construct();
		if(!app_access()){
			show_404();
		}
		$this->load->model('admin/admin_model');
		$this->load->model('admin/role_model');
		$this->load->model('log/log_admin_model');
		$this->load->helper('admin');
		$this->load->helper('cookie');
		$this->load->library('form_validation');
		$this->login_info = check_admin();
		$roles = $this->role_model->get_list();
		
		$method = $this->router->fetch_method();
		$class 	= ucfirst($this->router->fetch_class());
		$nonAuthMethods = ['login'];
        if ($class != 'Admin' || ($method != 'login' && $method != 'logout')) {
			if(empty($this->login_info)){
				redirect(admin_url('Admin/login'), 'refresh');
				die();
			}else{
				if($class != 'TestScript'){
                    $allowRolePermission = [];
					$role = $roles[$this->login_info->role_id];
					$role->permission 	= json_decode($role->permission,TRUE);

					if($class == 'AdminDashboard'){
						$this->role_info 	= array('r'=>1,'u'=>1);
					}else{
						$this->role_info 	= $role->permission[$class];
					}

                    $role_permission_list = $this->config->item('role_permission');
                    foreach ($role_permission_list as $key => $role_permission) {
                        if (array_key_exists($key, $role->permission)) {
                            $allowRolePermission = array_merge_recursive($allowRolePermission, $role_permission['permission']);
                        }
                    }

					// 判斷角色權限
                    $pass = True;
                    if($this->role_info['r'] == 0 ||
                        ($this->role_info['u'] == 0 && in_array($method,$this->edit_method))) {
                        if (array_key_exists($class, $allowRolePermission)) {
                            $allowedMethods = array_filter($allowRolePermission[$class], function ($key) use ($method) {
                                return strpos($key, $method) === 0;
                            }, ARRAY_FILTER_USE_KEY);

                            if (!empty($allowedMethods)) {
                                $input = [];
                                if ($this->input->method(TRUE) == "POST") {
                                    $input = $this->input->post(NULL, TRUE);
                                } else if ($this->input->method(TRUE) == "GET") {
                                    $input = $this->input->get(NULL, TRUE);
                                }

                                $allowedMethod = reset($allowedMethods);
                                if (isset($allowedMethod['validator']) && isset($allowedMethod['validator']['className'])) {
                                    $validator = $allowedMethod['validator']['className']::getInstance();
                                    $pass = $validator->checkPermission($input, $allowedMethod['validator']['parameters'] ?? []);
                                    if ($pass && isset($allowedMethod['role_parameters']) && is_array($allowedMethod['role_parameters'])) {
                                        $this->role_info = array_merge_recursive($this->role_info, $allowedMethod['role_parameters']);
                                    }
                                }
                            } else {
                                $pass = False;
                            }
                        }else
                            $pass = False;
                    }

                    if(!$pass) {
                        alert('權限不足。', admin_url('AdminDashboard/'));
                        die();
                    }
					
					$admin_menu = $this->config->item('admin_menu');
					foreach($admin_menu as $key => $value){
						if($key != 'AdminDashboard'){
                            if(array_key_exists($key, $allowRolePermission) && $role->permission[$key]['r'] == 0) {
                                // 角色權限也需要增加菜單選項
                                $admin_menu[$key] = array_filter(
                                    $value,
                                    function ($methodName) use ($allowRolePermission, $key) {
                                        return $methodName == 'parent_name' || (array_key_exists($methodName, $allowRolePermission[$key]) &&
                                            (!isset($allowRolePermission[$key]['menu_display']) || $allowRolePermission[$key]['menu_display'] != false));
                                    },
                                    ARRAY_FILTER_USE_KEY
                                );

                                // 只有最上層菜單(parent_name)時就直接不顯示
                                if(count($admin_menu[$key]) == 1)
                                    unset($admin_menu[$key]);
                            }else {
                                if ((!isset($role->permission[$key]) || $role->permission[$key]['r'] == 0)) {
                                    unset($admin_menu[$key]);
                                }
                            }
						}
					}
					
					if(in_array($method,$this->edit_method) || $method == "block_users"){
						$this->log_admin_model->insert(array(
							'admin_id' 		=> $this->login_info->id,
							'url'	 		=> $this->uri->uri_string(),
							'get_param'		=> json_encode($this->input->get(NULL, TRUE)),
							'post_param'	=> json_encode($this->input->post(NULL, TRUE)),
						));
					}
					
					$this->menu = array(
						'role_info'		=> $this->role_info,
						'role_name'		=> $role->name,
						'login_info'	=> $this->login_info,
						'active'		=> $this->router->fetch_class(),
						'menu'			=> $admin_menu,
					);
				}
			}
        }

    }
}

?>
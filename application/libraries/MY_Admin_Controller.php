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
		$this->load->helper('admin');
		$this->load->helper('cookie');
		$this->load->library('encrypt');
		$this->load->library('form_validation');
		$this->login_info = check_admin();
		$roles = $this->role_model->get_list();
		
		$method = $this->router->fetch_method();
		$class 	= ucfirst($this->router->fetch_class());
		$nonAuthMethods = ['login'];
        if ($class != "Admin" || ($method != "login" && $method != "logout")) {
			if(empty($this->login_info)){
				redirect(admin_url('Admin/login'), 'refresh');
				die();
			}else{
				if($class != "TestScript"){
					$role = $roles[$this->login_info->role_id];
					$role->permission 	= json_decode($role->permission,TRUE);
					if($class == "AdminDashboard"){
						$this->role_info 	= array("r"=>1,"u"=>1);
					}else{
						$this->role_info 	= $role->permission[$class];
					}
					
					if($this->role_info["r"]==0){
						alert("權限不足。",admin_url('AdminDashboard/'));
						die();
					}
					
					if($this->role_info["u"]==0 && in_array($method,$this->edit_method)){
						alert("權限不足。",admin_url($class.'/'));
						die();
					}
					
					$admin_menu = $this->config->item('admin_menu');
					foreach($admin_menu as $key => $value){
						if($key != "AdminDashboard"){
							if(!isset($role->permission[$key]) || $role->permission[$key]["r"]==0){
								unset($admin_menu[$key]);
							}
						}
					}

					$this->menu = array(
						"role_info"		=> $this->role_info,
						"login_info"	=> $this->login_info,
						"active"		=> $this->router->fetch_class(),
						"menu"			=> $admin_menu,
					);
				}
			}
        }

    }
}

?>
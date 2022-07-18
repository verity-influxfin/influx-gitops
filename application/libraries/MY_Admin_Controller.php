<?php

class MY_Admin_Controller extends CI_Controller{
	
	protected $role_info;
	protected $login_info;
	protected $menu	= array();
	protected $edit_method = array();
    // 動作對照表
    protected $action_type_list = [
        'read' => ['key' => 0, 'value' => '查詢'],
        'update' => ['key' => 1, 'value' => '編輯'],
        'create' => ['key' => 2, 'value' => '新增'],
        'delete' => ['key' => 3, 'value' => '刪除'],
    ];
    //角色名稱
    protected $position_list = [1 => '執行長', 2 => '主管', 3 => '經辦'];
    protected $permission_list;
    protected $permission_granted;
	
	public function __construct(){
        parent::__construct();
		if(!app_access()){
			show_404();
		}
		$this->load->model('admin/admin_model');
		$this->load->model('admin/role_model');
		$this->load->model('admin/group_model');
		$this->load->model('log/log_admin_model');
		$this->load->helper('admin');
		$this->load->helper('cookie');
		$this->load->library('form_validation');
        $this->load->config('permission');

        $this->action_type_list = array_map(function ($element) {
            return ['key' => pow(2, $element['key']), 'value' => $element['value']];
        }, $this->action_type_list);

		$this->login_info = check_admin();
        $this->permission_list = $this->config->item('permission');
        $this->_check_permission();

		$nonAuthMethods = ['login'];
        $this->menu = array(
            'role_info'		=> $this->role_info,
            'login_info'	=> $this->login_info,
            'active'		=> $this->router->fetch_class(),
            'menu'			=> $this->_get_menu(),
        );
    }

    // 取得有權限的左側 Menu 資料
    private function _get_menu(): array
    {
        $admin_menu = [];
        foreach ($this->permission_list as $controller => $controller_value)
        {
            if (empty($controller_value['name']))
            {
                continue;
            }
            $controller = strtolower($controller);
            $admin_menu[$controller] = [];
            if (count($controller_value['menu']) == 1)
            { // 無 child menu
                $item = array_keys($controller_value['menu']);
                $item = reset($item);
                if ( ! ($this->permission_granted[$controller][$item]['action']['granted'] & $this->action_type_list['read']['key'])) continue;
                $admin_menu[$controller]['parent_url'] = admin_url($this->permission_granted[$controller][$item]['url']);
            }
            else
            { // 有 child menu
                foreach ($controller_value['menu'] as $item_key => $item_value)
                {
                    if ( ! ($this->permission_granted[$controller][$item_key]['action']['granted'] & $this->action_type_list['read']['key'])) continue;
                    $admin_menu[$controller]['sub'][$item_key] = ['name' => $item_value['name']];
                    if ( ! empty($item_value['param']) && is_array($item_value['param']))
                    {
                        $query_str = '?' . http_build_query($item_value['param']);
                    }
                    else
                    {
                        $query_str = '';
                    }
                    $admin_menu[$controller]['sub'][$item_key]['url'] = admin_url(
                        $this->permission_granted[$controller][$item_key]['url'] . $query_str
                    );
                }
                if (empty($admin_menu[$controller]['sub'])) continue;
                $admin_menu[$controller]['parent_url'] = '#';
            }
            $admin_menu[$controller]['parent_name'] = $controller_value['name'];
        }
        return $admin_menu;
    }

    // 解析 URL
    private function _parse_controller(): array
    {
        $this->load->helper('url');
        return array_pad(
            explode('/', preg_replace('/^admin\//', '', strtolower(uri_string()))),
            2,
            'index'
        );
    }

    // 檢查權限
    private function _check_permission()
    {
        $controller = '';
        $method = '';

        try
        {
            list($controller, $method) = $this->_parse_controller();

            if ($controller == 'admin' && ($method == 'login' || $method == 'logout'))
            {
                return;
            }

            if (empty($this->login_info))
            {
                redirect(admin_url('admin/login'), 'refresh');
                die();
            }

            $this->_get_granted_permission();

            if ($controller == 'admindashboard' || $controller == 'testscript')
            {
                return;
            }

            if ( ! isset($this->permission_granted[$controller][$method]))
            {
                throw new Exception();
            }

            $action = $this->permission_granted[$controller][$method]['action'];
            if (($action['granted'] & $action['valid']) === 0)
            {
                throw new Exception();
            }
        }
        catch (Exception $e)
        {
            alert('權限不足。(' . $this->permission_granted[$controller][$method]['url'] . ')', admin_url('AdminDashboard/'));
        }
    }

    private function _get_granted_permission()
    {
        // 個人例外權限
        $admin_permission = $this->admin_model->get_data_by_id($this->login_info->id);
        if (empty($admin_permission['group_id']) || empty($admin_permission['permission_status']) || $admin_permission['permission_status'] != 1)
        {
            return TRUE;
        }
        // 部門權限
        $group_permission = $this->group_model->get_permission_by_group_id($admin_permission['group_id']);
        $this->role_info = [
            'division' => $admin_permission['division'] ?? '',
            'department' => $admin_permission['department'] ?? '',
            'position' => $this->position_list[$admin_permission['position'] ?? 0] ?? '',
        ];

        $this->load->library('permission_lib');
        $admin_permission = $this->permission_lib->mapping_permission_data($admin_permission['permission'] ?? []);
        $group_permission = $this->permission_lib->mapping_permission_data($group_permission ?? []);

        foreach ($this->permission_list as $controller => $controller_value)
        {
            if (empty($controller_value['permission'])) continue;
            foreach ($controller_value['permission'] as $method => $method_value)
            {
                if (empty($method_value['model']) || empty($method_value['submodel'])) continue;
                $model = $method_value['model'];
                $submodel = $method_value['submodel'];
                $action_type_a = $admin_permission[$model][$submodel] ?? 0;
                $action_type_g = $group_permission[$model][$submodel] ?? 0;
                $this->permission_granted[strtolower($controller)][$method] = [
                    'action' => [
                        'granted' => $action_type_a | $action_type_g,
                        'valid' => $this->action_type_list[$method_value['action']]['key']
                    ],
                    'url' => "{$controller}/{$method}",
                ];
            }
        }
        return TRUE;
    }
}


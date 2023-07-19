<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Admin extends MY_Admin_Controller {

	protected $edit_method = array('add','edit','role_add','role_edit');

	public function __construct() {
		parent::__construct();
		$this->load->model('log/log_adminlogin_model');
 	}

	public function index(){
		$page_data 	= array();
		$where		= array('status'=>1);
		$list 		= $this->admin_model->get_many_by($where);
		$name_list	= array();
		if(!empty($list)){
			foreach($list as $key => $value){
                $url 			= 'https://event.influxfin.com/r/url?p='.$value->my_promote_code;
				$qrcode			= get_qrcode($url);
				$value->qrcode	= $qrcode;
				$list[$key] 	= $value;
			}
			$page_data['list'] 			= $list;
			$page_data['name_list'] 	= $this->admin_model->get_name_list();
			$page_data['status_list'] 	= $this->admin_model->status_list;
		}


		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/admins_list',$page_data);
		$this->load->view('admin/_footer');
	}

	public function login(){
		$post = $this->input->post(NULL, TRUE);
		$already_login_admin_info = check_admin();
		if ($already_login_admin_info && $already_login_admin_info->status == 1) {
			redirect(admin_url('AdminDashboard/'), 'refresh');
			die();
		}else if(empty($post)){
			$cookie = get_cookie(COOKIES_LOGIN_ADMIN);
			$cookie = AUTHORIZATION::getAdminCookieInfoByToken($cookie);
			$cookie	= $cookie?$cookie:'';
			$this->load->view('admin/login',array('cookie'=>$cookie));
		}else{
			if(isset($post['remember'])){
				$cookie = array(
                        'name'   => COOKIES_LOGIN_ADMIN,
                        'value'  => AUTHORIZATION::generateAdminCookieToken($post),
                        'expire' => COOKIE_EXPIRE,
                );

				set_cookie($cookie);
			}else{
				delete_cookie(COOKIES_LOGIN_ADMIN);
			}

			$admin_info = $this->admin_model->get_by('email', $post['email']);

			if(!$admin_info){
				$admin_info = $this->admin_model->get_by('account', $post['email']);
			}

			if($admin_info && $admin_info->status==1){
				if(sha1($post['password'])==$admin_info->password){
					$admin_token = AUTHORIZATION::generateAdminToken($admin_info);
					admin_login($admin_token);
					$this->log_adminlogin_model->insert(array('email'=>$post['email'],'status'=>1));
					redirect(admin_url('AdminDashboard/'), 'refresh');
					die();
				}else{
					$this->log_adminlogin_model->insert(array('email'=>$post['email'],'status'=>0));
					alert('密碼錯誤',admin_url('admin/login'));
					die();
				}
			}else{
				$this->log_adminlogin_model->insert(array('email'=>$post['email'],'status'=>0));
				alert('無此email',admin_url('admin/login'));
				die();
			}
		}
	}

	public function logout(){
		admin_logout();
		redirect(admin_url('admin/login'), 'refresh');
	}

	public function add(){
		$page_data 	= array('type'=>'add');
		$data		= array();
		$post 		= $this->input->post(NULL, TRUE);
		if(empty($post)){
			$this->load->view('admin/_header');
			$this->load->view('admin/_title',$this->menu);
			$this->load->view('admin/admins_edit',$page_data);
			$this->load->view('admin/_footer');
		}else{
			$required_fields 	= [ 'account', 'name', 'phone', 'birthday', 'email', 'password'];
			foreach ($required_fields as $field) {
				if (empty($post[$field])) {
					alert($field.' is empty',admin_url('admin/index'));
				}else{
					$data[$field] = $post[$field];
				}
			}
			$fields = ['phone', 'birthday'];
			foreach ($fields as $field) {
				if (isset($post[$field])) {
					$data[$field] = $post[$field];
				}
			}

			$data['creator_id'] 	= $this->login_info->id;
			$data['my_promote_code']= $this->get_promote_code();
			$rs = $this->admin_model->insert($data);
			if($rs){
				alert('新增成功',admin_url('admin/index'));
			}else{
				alert('新增失敗，請洽工程師',admin_url('admin/index'));
			}
		}
	}

	public function edit(){
		$page_data 	= array('type'=>'edit');
		$post 		= $this->input->post(NULL, TRUE);
		$get 		= $this->input->get(NULL, TRUE);

		if(empty($post)){
			$id = isset($get['id'])?intval($get['id']):0;
			if($id){
				$admin_info = $this->admin_model->get_by('id', $id);
				if($admin_info){
					$url 						= 'https://event.influxfin.com/r/url?p='.$admin_info->my_promote_code;
					$admin_info->qrcode			= get_qrcode($url);
					$page_data['data'] 			= $admin_info;
					$page_data['status_list'] 	= $this->admin_model->status_list;
					$this->load->view('admin/_header');
					$this->load->view('admin/_title',$this->menu);
					$this->load->view('admin/admins_edit',$page_data);
					$this->load->view('admin/_footer');
				}else{
					alert('ERROR , id is not exist',admin_url('admin/index'));
				}
			}else{
				alert('ERROR , id is not exist',admin_url('admin/index'));
			}
		}else{
			if(!empty($post['id'])){
				$fields = ['name', 'phone', 'birthday', 'password','status'];
				foreach ($fields as $field) {
					if (isset($post[$field])) {
						$data[$field] = $post[$field];
					}
				}
				if(isset($data['password']) && empty($data['password'])){
					unset($data['password']);
				}

				$rs = $this->admin_model->update($post['id'],$data);
				if($rs===true){
					alert('更新成功',admin_url('admin/index'));
				}else{
					alert('更新失敗，請洽工程師',admin_url('admin/index'));
				}
			}else{
				alert('ERROR , id is not exist',admin_url('admin/index'));
			}
		}

	}

	public function role_list(){
		$page_data 	= array();
		$list 		= $this->role_model->get_all();
		if(!empty($list)){
			$page_data['list'] 			= $list;
			$page_data['status_list'] 	= $this->role_model->status_list;
			$page_data['name_list'] 	= $this->admin_model->get_name_list();
		}

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/roles_list',$page_data);
		$this->load->view('admin/_footer');
	}

	public function role_add(){
		$status_list 	= $this->role_model->status_list;
		$admin_menu = $this->config->item('admin_menu');
		if(!empty($admin_menu)){
			unset($admin_menu['AdminDashboard']);
		}
		$page_data 	= array(
			'type'			=> 'add',
			'status_list'	=> $status_list,
			'admin_menu'	=> $admin_menu,
		);
		$data		= array();
		$post 		= $this->input->post(NULL, TRUE);
		if(empty($post)){
			$this->load->view('admin/_header');
			$this->load->view('admin/_title',$this->menu);
			$this->load->view('admin/roles_edit',$page_data);
			$this->load->view('admin/_footer');
		}else{
			$required_fields 	= [ 'alias', 'name' ,'status'];
			foreach ($required_fields as $field) {
				if (empty($post[$field])) {
					alert($field.' is empty',admin_url('admin/index'));
				}else{
					$data[$field] = $post[$field];
				}
			}
			$permission = array();
			foreach($admin_menu as $key => $value){
				$r = isset($post['permission'][$key]['r'])&&$post['permission'][$key]['r']?1:0;
				$u = isset($post['permission'][$key]['u'])&&$post['permission'][$key]['u']?1:0;
				$permission[$key] = array(
					'r'	=> $r,
					'u'	=> $u
				);
			}

			$data['permission'] 	= json_encode($permission);
			$data['creator_id'] 	= $this->login_info->id;
			$rs = $this->role_model->insert($data);
			if($rs){
				alert('新增成功',admin_url('admin/role_list'));
			}else{
				alert('新增失敗，請洽工程師',admin_url('admin/role_list'));
			}
		}
	}

	public function role_edit(){
		$status_list 	= $this->role_model->status_list;
		$admin_menu = $this->config->item('admin_menu');
		if(!empty($admin_menu)){
			unset($admin_menu['AdminDashboard']);
		}
        $admin_menu = array_merge($admin_menu, $this->config->item('role_permission'));
		$page_data 	= array(
			'type'			=> 'edit',
			'status_list'	=> $status_list,
			'admin_menu'	=> $admin_menu,
		);
		$post 		= $this->input->post(NULL, TRUE);
		$get 		= $this->input->get(NULL, TRUE);

		if(empty($post)){
			$id = isset($get['id'])?intval($get['id']):0;
			if($id){
				$role_info = $this->role_model->get_by('id', $id);
				if($role_info){
					$role_info->permission = json_decode($role_info->permission,true);
					$page_data['data'] = $role_info;
					$this->load->view('admin/_header');
					$this->load->view('admin/_title',$this->menu);
					$this->load->view('admin/roles_edit',$page_data);
					$this->load->view('admin/_footer');
				}else{
					alert('ERROR , id is not exist',admin_url('admin/role_list'));
				}
			}else{
				alert('ERROR , id is not exist',admin_url('admin/role_list'));
			}
		}else{
			if(!empty($post['id'])){
				$fields = ['name','status'];
				foreach ($fields as $field) {
					if (isset($post[$field])) {
						$data[$field] = $post[$field];
					}
				}

				$permission = array();
				foreach($admin_menu as $key => $value){
					$r = isset($post['permission'][$key]['r'])&&$post['permission'][$key]['r']?1:0;
					$u = isset($post['permission'][$key]['u'])&&$post['permission'][$key]['u']?1:0;
					$permission[$key] = array(
						'r'	=> $r,
						'u'	=> $u
					);
				}

				$data['permission'] 	= json_encode($permission);
				$rs = $this->role_model->update($post['id'],$data);
				if($rs===true){
					alert('更新成功',admin_url('admin/role_list'));
				}else{
					alert('更新失敗，請洽工程師',admin_url('admin/role_list'));
				}
			}else{
				alert('ERROR , id is not exist',admin_url('admin/role_list'));
			}
		}

	}

	private function get_promote_code(){
		$code 	= 'SALES'.make_promote_code();
		$result = $this->admin_model->get_by('my_promote_code',$code);
		if ($result) {
			return $this->get_promote_code();
		}else{
			return $code;
		}
	}

    // 部門權限設定
    public function group_permission_list()
    {
        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/group_permission_list');
        $this->load->view('admin/_footer');
    }

    // 部門權限設定-取列表資料
    public function get_group_permission_list()
    {
        $get = $this->input->get(NULL, TRUE);
        $where = [];
        if ( ! empty($get['division']))
        {
            $where['division LIKE'] = "%{$get['division']}%";
        }

        echo json_encode([
            'list' => $this->group_model->get_many_by($where),
            'position_list' => $this->position_list,
        ]);
    }

    // 部門權限設定-編輯
    public function group_permission_edit()
    {
        $post = $this->input->post(NULL, TRUE);
        $get = $this->input->get(NULL, TRUE);

        if (empty($post)) // 瀏覽表單
        {
            $id = (int) ($get['id'] ?? 0);
            if ( ! $id)
            { // query string沒有帶id
                alert('查無此部門權限', admin_url('admin/group_permission_list'));
            }

            $group_info = $this->group_model->get_data_by_id($id);
            if ( ! $group_info)
            { // id撈不到東西
                alert('查無此部門權限', admin_url('admin/group_permission_list'));
            }
            $this->load->library('permission_lib');
            $this->load->view('admin/_header');
            $this->load->view('admin/_title', $this->menu);
            $this->load->view('admin/group_permission_edit', [
                'data' => $group_info,
                'position_list' => $this->position_list,
                'permission_list' => $this->permission_list,
                'action_type_list' => $this->action_type_list,
                'permission_data' => $this->permission_lib->mapping_permission_data($group_info['permission']),
            ]);
            $this->load->view('admin/_footer');
        }
        else // 儲存表單
        {
            $id = (int) ($post['id'] ?? 0);
            if ( ! $id)
            { // 表單沒有帶id
                alert('查無此部門權限', admin_url('admin/group_permission_list'));
            }

            $data = $this->_check_form_data(
                $post,
                ['division' => '部門', 'department' => '組別', 'position' => '角色名稱', 'permission' => '權限設定']
            );
            $data['updated_admin_id'] = $this->login_info->id;
            unset($data['permission']);
            $permission_data = $post['permission'];

            if ($this->group_model->update_form_data($id, $data, $permission_data) === TRUE)
            {
                if ($id != 1) {
                    $this->admin_model->update_by(['group_id' => $id], ['permission_status' => 0]);
                }
                alert('更新成功', admin_url('admin/group_permission_list'));
            }
            else
            {
                alert('更新失敗，請洽工程師', admin_url('admin/group_permission_list'));
            }
        }
    }

    // 部門權限設定-新增
    public function group_permission_add()
    {
        $post = $this->input->post(NULL, TRUE);

        if (empty($post)) // 瀏覽表單
        {
            $this->load->view('admin/_header');
            $this->load->view('admin/_title', $this->menu);
            $this->load->view('admin/group_permission_edit', [
                'position_list' => $this->position_list,
                'permission_list' => $this->permission_list,
                'action_type_list' => $this->action_type_list,
            ]);
            $this->load->view('admin/_footer');
        }
        else // 儲存表單
        {
            $data = $this->_check_form_data(
                $post,
                ['division' => '部門', 'department' => '組別', 'position' => '角色名稱', 'permission' => '權限設定']
            );
            $data['created_admin_id'] = $data['updated_admin_id'] = $this->login_info->id;
            unset($data['permission']);
            $permission_data = $post['permission'];

            if ($this->group_model->insert_form_data($data, $permission_data) === TRUE)
            {
                alert('新增成功', admin_url('admin/group_permission_list'));
            }
            else
            {
                alert('新增失敗，請洽工程師', admin_url('admin/group_permission_list'));
            }
        }
    }

    // 人員權限設定
    public function admin_permission_list()
    {
        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/admin_permission_list');
        $this->load->view('admin/_footer');
    }

    // 人員權限設定-取列表資料
    public function get_admin_permission_list()
    {
        $get = $this->input->get(NULL, TRUE);
        $where = [];
        if (isset($get['name']) && ! empty($get['name']))
        {
            $where['name LIKE'] = "%{$get['name']}%";
        }

        echo json_encode([
            'list' => $this->admin_model->get_admin_group_data($where),
            'position_list' => $this->position_list,
        ]);
    }

    // 人員權限設定-編輯
    public function admin_permission_edit()
    {
        $post = $this->input->post(NULL, TRUE);
        $get = $this->input->get(NULL, TRUE);

        if (empty($post)) // 瀏覽表單
        {
            $id = (int) ($get['id'] ?? 0);
            if ( ! $id)
            { // query string沒有帶id
                alert('查無此人員權限', admin_url('admin/admin_permission_list'));
            }

            $admin_info = $this->admin_model->get_data_by_id($id);
            if ( ! $admin_info)
            { // id撈不到東西
                alert('查無此人員權限', admin_url('admin/admin_permission_list'));
            }
            $this->load->library('permission_lib');
            $this->load->view('admin/_header');
            $this->load->view('admin/_title', $this->menu);
            $this->load->view('admin/admin_permission_edit', [
                'data' => $admin_info,
                'permission_list' => $this->permission_list,
                'action_type_list' => $this->action_type_list,
                'admin_list' => [[
                    'id' => $admin_info['id'] ?? 0,
                    'email' => $admin_info['email'] ?? '',
                    'name' => $admin_info['name'],
                ]],
                'position_list' => $this->position_list,
                'permission_data' => $this->permission_lib->mapping_permission_data($admin_info['permission']),
            ]);
            $this->load->view('admin/_footer');
        }
        else // 儲存表單
        {
            $id = (int) ($post['id'] ?? 0);
            if ( ! $id)
            { // 表單沒有帶id
                alert('查無此人員權限', admin_url('admin/admin_permission_list'));
            }

            $data = $this->_check_form_data(
                $post,
                ['group_id' => '部門']
            );
            $data['permission_status'] = 0;
            $permission_data = $post['permission'] ?? [];

            if ($this->admin_model->update_permission_data($id, $data, $permission_data) === TRUE)
            {
                alert('更新成功', admin_url('admin/admin_permission_list'));
            }
            else
            {
                alert('更新失敗，請洽工程師', admin_url('admin/admin_permission_list'));
            }
        }
    }

    // 人員權限設定-新增
    public function admin_permission_add()
    {
        $post = $this->input->post(NULL, TRUE);

        if (empty($post)) // 瀏覽表單
        {
            $admin_list = $this->admin_model->get_no_group_list();
            array_unshift($admin_list, ['id' => 0, 'email' => '請選擇']);

            $this->load->view('admin/_header');
            $this->load->view('admin/_title', $this->menu);
            $this->load->view('admin/admin_permission_edit', [
                'permission_list' => $this->permission_list,
                'action_type_list' => $this->action_type_list,
                'admin_list' => $admin_list,
                'position_list' => $this->position_list,
            ]);
            $this->load->view('admin/_footer');
        }
        else // 儲存表單
        {
            $data = $this->_check_form_data(
                $post,
                ['id' => '帳號', 'group_id' => '部門']
            );
            $permission_data = $post['permission'] ?? [];

            if ($this->admin_model->update_permission_data($post['id'], $data, $permission_data) === TRUE)
            {
                alert('新增成功', admin_url('admin/admin_permission_list'));
            }
            else
            {
                alert('新增失敗，請洽工程師', admin_url('admin/admin_permission_list'));
            }
        }
    }

    // 權限審核
    public function permission_grant_list()
    {
        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/permission_grant_list');
        $this->load->view('admin/_footer');
    }

    // 權限審核-取列表資料
    public function get_permission_list()
    {
        $get = $this->input->get(NULL, TRUE);
        $where = [];
        if ( ! empty($get['name']))
        {
            $where['name LIKE'] = "%{$get['name']}%";
        }
        if ( ! empty($get['division']))
        {
            $where['division LIKE'] = "%{$get['division']}%";
        }

        echo json_encode([
            'list' => $this->admin_model->get_admin_group_data($where),
            'position_list' => $this->position_list,
        ]);
    }

    // 權限查詢
    public function permission_search()
    {
        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/permission_search');
        $this->load->view('admin/_footer');
    }

    // 權限查詢-細節
    public function permission_detail()
    {
        $get = $this->input->get(NULL, TRUE);

        $id = (int) ($get['id'] ?? 0);
        if ( ! $id)
        { // query string沒有帶id
            alert('ERROR , id is not exist', admin_url('admin/role_permission_list'));
        }

        $admin_info = $this->admin_model->get_data_by_id($id);
        if ( ! $admin_info || ! isset($admin_info['group_id']))
        { // id撈不到東西
            alert('ERROR , id is not exist', admin_url('admin/role_permission_list'));
        }

        $admin_info['position'] = $this->position_list[$admin_info['position'] ?? ''] ?? '';
        $admin_info['group_permission'] = $this->group_model->get_permission_by_group_id($admin_info['group_id']);
        $this->load->library('permission_lib');
        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/permission_detail', [
            'data' => $admin_info,
            'permission_list' => $this->permission_list,
            'action_type_list' => $this->action_type_list,
            'group_permission_data' => $this->permission_lib->mapping_permission_data($admin_info['group_permission']),
            'admin_permission_data' => $this->permission_lib->mapping_permission_data($admin_info['permission'])
        ]);
        $this->load->view('admin/_footer');
    }

    /**
     * 檢查表單是否有欄位未填寫
     * @param array $post : 表單傳來的資料
     * @param array $fields : 欲檢查的欄位名稱
     * @return array
     */
    private function _check_form_data(array $post, array $fields)
    {
        if (empty($post))
        {
            alert('查無欄位');
        }
        $data = [];
        foreach ($fields as $field_key => $field_name)
        {
            if (empty($post[$field_key]))
            {
                alert($field_name . '必填');
            }
            $data[$field_key] = $post[$field_key];
        }
        return $data;
    }

    // 更新權限審核啟用狀態(admins.permission_status)
    public function update_permission_status()
    {
        $post = $this->input->post(NULL, TRUE);

        if ( ! isset($post['admin_id']) || ! $post['admin_id'])
        {
            $result = FALSE;
        }
        elseif ( ! isset($post['permission_status']))
        {
            $result = FALSE;
        }
        else
        {
            $result = $this->admin_model->update($post['admin_id'], ['permission_status' => $post['permission_status'] ? 1 : 0]);
        }
        echo json_encode(['result' => $result]);
    }

    public function get_group_list()
    {
        $data = [];

        foreach (($this->group_model->get_group_list()) as $value)
        {
            if ( ! isset($value['division']) || ! isset($value['department']))
            {
                continue;
            }

            $department = json_decode(("[{$value['department']}]"), TRUE);

            $data[] = [
                'name' => $value['division'],
                'department' => $department,
            ];
        }

        echo json_encode(['data' => $data]);
    }
}

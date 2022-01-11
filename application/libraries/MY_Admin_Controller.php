<?php

class MY_Admin_Controller extends CI_Controller{
	
	protected $role_info;
	protected $login_info;
	protected $menu	= array();
	protected $edit_method = array();
    // 動作對照表
    protected $action_type_list = [
        'create' => ['key' => 0, 'value' => '增'],
        'delete' => ['key' => 1, 'value' => '刪'],
        'update' => ['key' => 2, 'value' => '改'],
        'read' => ['key' => 3, 'value' => '查']];
    protected $permission_list;
    protected $permission_edit_list;
	
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
		$this->login_info = check_admin();
        $this->permission_list = $this->config->item('permission');
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
                        $this->_get_permission_edit_list();
                        $this->_check_permission();

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

    private function _parse_controller()
    {
        $this->load->helper('url');
        return array_pad(
            explode('/', preg_replace('/^admin\//', '', strtolower(uri_string()))),
            2,
            'index'
        );
    }

    private function _check_permission()
    {
        $controller = '';
        $method = '';

        try
        {
            list($controller, $method) = $this->_parse_controller();

            while (TRUE)
            {
                // Read權限
                if (isset($this->permission_list[$controller]) && isset($this->permission_list[$controller]['list'][$method]))
                {
                    $action = pow(2, $this->action_type_list['read']['key']);
                    $permission_model = $controller;
                    $permission_submodel = $method;
                    break;
                }

                // Create, update, delete權限
                if ( ! isset($this->permission_edit_list[$controller]) || ! isset($this->permission_edit_list[$controller][$method]))
                {
                    throw new Exception();
                }

                $action = $this->permission_edit_list[$controller][$method]['action'];
                if ( ! isset($this->action_type_list[$action]['key']))
                {
                    throw new Exception();
                }
                $action = pow(2, $this->action_type_list[$action]['key']);

                $permission_model = $this->permission_edit_list[$controller][$method]['model_key'];
                $permission_submodel = $this->permission_edit_list[$controller][$method]['submodel_key'];

                break;
            }

            $this->db
                ->select('(IFNULL(ap.action_type,0) | IFNULL(gp.action_type,0)) AS action_type')
                ->from('p2p_admin.admins a')
                ->join('p2p_admin.groups g', 'g.id=a.group_id')
                ->join('p2p_admin.admin_permission ap', "ap.admin_id=a.id AND ap.model_key='{$permission_model}' AND ap.submodel_key='{$permission_submodel}'", 'left')
                ->join('p2p_admin.group_permission gp', "gp.group_id=g.id AND gp.model_key='{$permission_model}' AND gp.submodel_key='{$permission_submodel}'", 'left')
                ->where('a.id', $this->login_info->id)
                ->where('a.permission_status', 1);

            if ((($this->db->get()->first_row('object')->action_type ?? 0) & $action) === 0)
            {
                throw new Exception();
            }
        }
        catch (Exception $e)
        {
            alert('權限不足。('.$controller.'/'.$method.')', admin_url('AdminDashboard/'));
        }
    }

    /**
     * 新增、編輯、刪除的權限對應表
     * 例1：目前「URL」為 /admin/Target/edit，則「target」為class name、「edit」為method name
     * 例2：目前「URL」為 /admin/Passbook/user_bankaccount_edit，則「passbook」為class name、「user_bankaccount_edit」為method name
     * [
     *     'target' => [                      // class名，取strtolower()
     *         'edit' => [                    // method名，取strtolower()
     *             'action' => 'update',      // 權限動作，對應$this->action_type_list
     *             'model_key' => 'target',   // 主模組key，對應$this->config->item('permission')
     *             'submodel_key' => 'index', // 子模組key，對應$this->config->item('permission')
     *         ]
     *     ]
     * ]
     */
    private function _get_permission_edit_list()
    {
        $this->permission_edit_list = [
            'target' => [
                'edit' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'index'
                ],
                'verify_success' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_verify'
                ],
                'verify_failed' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_verify'
                ],
                'order_fail' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_signing'
                ],
                'credits' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_evaluation'
                ],
                'evaluation_approval' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_evaluation'
                ],
                'final_validations' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_evaluation'
                ],
                'target_loan' => [
                    'action' => 'read',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_loan'
                ],
                'subloan_success' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_loan'
                ],
                're_subloan' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_loan'
                ],
                'loan_return' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_loan'
                ],
                'loan_success' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_loan'
                ],
                'loan_failed' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_loan'
                ],
                'transaction_display' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'index'
                ],
                'target_repayment_export' => [
                    'action' => 'read',
                    'model_key' => 'target',
                    'submodel_key' => 'repayment'
                ],
                'target_finished_export' => [
                    'action' => 'read',
                    'model_key' => 'target',
                    'submodel_key' => 'finished'
                ],
                'target_waiting_signing_export' => [
                    'action' => 'read',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_signing'
                ],
                'amortization_export' => [
                    'action' => 'read',
                    'model_key' => 'target',
                    'submodel_key' => 'repayment'
                ],
                'cancel_bidding' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_bidding'
                ],
                'approve_order_transfer' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_approve_order_transfer'
                ],
                'legalaffairs' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'index'
                ],
                'waiting_reinspection' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_verify'
                ],
                'skbank_text_get' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_verify'
                ],
                'skbank_text_send' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_verify'
                ],
                'skbank_image_get' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_verify'
                ],
            ],
            'transfer' => [
                'assets_export_new' => [
                    'action' => 'read',
                    'model_key' => 'transfer',
                    'submodel_key' => 'obligations'
                ],
                'transfer_assets_export' => [
                    'action' => 'read',
                    'model_key' => 'transfer',
                    'submodel_key' => 'index'
                ],
                'obligation_assets_export' => [
                    'action' => 'read',
                    'model_key' => 'transfer',
                    'submodel_key' => 'obligations'
                ],
                'amortization_schedule' => [
                    'action' => 'read',
                    'model_key' => 'transfer',
                    'submodel_key' => 'obligations'
                ],
                'amortization_export' => [
                    'action' => 'read',
                    'model_key' => 'transfer',
                    'submodel_key' => 'index'
                ],
                'transfer_combination' => [
                    'action' => 'read',
                    'model_key' => 'transfer',
                    'submodel_key' => 'waiting_transfer'
                ],
                'transfer_combination_success' => [
                    'action' => 'read',
                    'model_key' => 'transfer',
                    'submodel_key' => 'waiting_transfer_success'
                ],
                'transfer_success' => [
                    'action' => 'update',
                    'model_key' => 'transfer',
                    'submodel_key' => 'waiting_transfer_success'
                ],
                'transfer_cancel' => [
                    'action' => 'update',
                    'model_key' => 'transfer',
                    'submodel_key' => 'waiting_transfer_success'
                ],
                'c_transfer_cancel' => [
                    'action' => 'update',
                    'model_key' => 'transfer',
                    'submodel_key' => 'waiting_transfer_success'
                ],
            ],
            'risk' => [
                'push_info' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'index'
                ],
                'push_info_add' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'index'
                ],
                'push_info_remove' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'index'
                ],
                'push_info_update' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'index'
                ],
                'push_audit' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'index'
                ],
                'push_audit_add' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'index'
                ],
                'judicial_associates' => [
                    'action' => 'read',
                    'model_key' => 'risk',
                    'submodel_key' => 'juridical_person'
                ],
            ],
            'passbook' => [
                'edit' => [
                    'action' => 'update',
                    'model_key' => 'passbook',
                    'submodel_key' => 'index'
                ],
                'display' => [ // 虛擬帳號
                    'action' => 'read',
                    'model_key' => 'passbook',
                    'submodel_key' => 'index'
                ],
                'withdraw_loan' => [ // 轉出放款匯款單
                    'action' => 'read',
                    'model_key' => 'passbook',
                    'submodel_key' => 'withdraw_waiting'
                ],
                'unknown_refund' => [
                    'action' => 'update',
                    'model_key' => 'passbook',
                    'submodel_key' => 'unknown_funds'
                ],
                'loan_success' => [
                    'action' => 'update',
                    'model_key' => 'passbook',
                    'submodel_key' => 'withdraw_waiting'
                ],
                'loan_failed' => [
                    'action' => 'update',
                    'model_key' => 'passbook',
                    'submodel_key' => 'withdraw_waiting'
                ],
                'withdraw_by_admin' => [
                    'action' => 'update',
                    'model_key' => 'passbook',
                    'submodel_key' => 'index'
                ],
                'withdraw_deny' => [
                    'action' => 'update',
                    'model_key' => 'passbook',
                    'submodel_key' => 'withdraw_waiting'
                ],
                'user_bankaccount_edit' => [
                    'action' => 'update',
                    'model_key' => 'passbook',
                    'submodel_key' => 'user_bankaccount_list'
                ],
                'user_bankaccount_success' => [
                    'action' => 'update',
                    'model_key' => 'passbook',
                    'submodel_key' => 'user_bankaccount_list'
                ],
                'user_bankaccount_failed' => [
                    'action' => 'update',
                    'model_key' => 'passbook',
                    'submodel_key' => 'user_bankaccount_list'
                ],
                'user_bankaccount_resend' => [
                    'action' => 'update',
                    'model_key' => 'passbook',
                    'submodel_key' => 'user_bankaccount_list'
                ],
                'user_bankaccount_verify' => [
                    'action' => 'read',
                    'model_key' => 'passbook',
                    'submodel_key' => 'user_bankaccount_list'
                ]
            ],
            'judicialperson' => [
                'juridical_apply_edit' => [
                    'action' => 'update',
                    'model_key' => 'judicialperson',
                    'submodel_key' => 'juridical_apply',
                ],
                'juridical_management_edit' => [
                    'action' => 'update',
                    'model_key' => 'judicialperson',
                    'submodel_key' => 'juridical_management',
                ],
                'cooperation_apply_edit' => [
                    'action' => 'update',
                    'model_key' => 'judicialperson',
                    'submodel_key' => 'cooperation_apply',
                ],
                'cooperation_management_edit' => [
                    'action' => 'update',
                    'model_key' => 'judicialperson',
                    'submodel_key' => 'cooperation_management',
                ],
                'cooperation_apply_success' => [
                    'action' => 'update',
                    'model_key' => 'Judicialperson',
                    'submodel_key' => 'cooperation_apply'
                ],
                'cooperation_management_success' => [
                    'action' => 'update',
                    'model_key' => 'Judicialperson',
                    'submodel_key' => 'cooperation_management'
                ],
                'cooperation_apply_failed' => [
                    'action' => 'update',
                    'model_key' => 'Judicialperson',
                    'submodel_key' => 'cooperation_apply'
                ],
                'cooperation_management_failed' => [
                    'action' => 'update',
                    'model_key' => 'Judicialperson',
                    'submodel_key' => 'cooperation_management'
                ],
            ],
            'certification' => [
                'user_certification_edit' => [
                    'action' => 'update',
                    'model_key' => 'certification',
                    'submodel_key' => 'user_certification_list'
                ],
                'user_bankaccount_edit' => [
                    'action' => 'update',
                    'model_key' => 'passbook',
                    'submodel_key' => 'user_bankaccount_list'
                ],
                'user_bankaccount_success' => [
                    'action' => 'update',
                    'model_key' => 'passbook',
                    'submodel_key' => 'user_bankaccount_list'
                ],
                'user_bankaccount_failed' => [
                    'action' => 'update',
                    'model_key' => 'passbook',
                    'submodel_key' => 'user_bankaccount_list'
                ],
                'user_bankaccount_resend' => [
                    'action' => 'update',
                    'model_key' => 'passbook',
                    'submodel_key' => 'user_bankaccount_list'
                ],
                'user_bankaccount_verify' => [
                    'action' => 'read',
                    'model_key' => 'passbook',
                    'submodel_key' => 'user_bankaccount_list'
                ],
                'difficult_word_add' => [
                    'action' => 'create',
                    'model_key' => 'certification',
                    'submodel_key' => 'difficult_word_list'
                ],
                'difficult_word_edit' => [
                    'action' => 'update',
                    'model_key' => 'certification',
                    'submodel_key' => 'difficult_word_list'
                ],
                'verdict_statuses' => [
                    'action' => 'read',
                    'model_key' => 'certification',
                    'submodel_key' => 'user_certification_list'
                ],
                'verdict_count' => [
                    'action' => 'read',
                    'model_key' => 'certification',
                    'submodel_key' => 'user_certification_list'
                ],
                'verdict' => [
                    'action' => 'read',
                    'model_key' => 'certification',
                    'submodel_key' => 'user_certification_list'
                ],
                'judicial_yuan_case' => [
                    'action' => 'read',
                    'model_key' => 'certification',
                    'submodel_key' => 'user_certification_list'
                ],
                'media_upload' => [
                    'action' => 'update',
                    'model_key' => 'certification',
                    'submodel_key' => 'index'
                ],
                'hasspouse' => [ // 加入是否有配偶
                    'action' => 'update',
                    'model_key' => 'certification',
                    'submodel_key' => 'user_certification_list'
                ],
                'sendskbank' => [ // 新光送件檢核表送出資料
                    'action' => 'update',
                    'model_key' => 'certification',
                    'submodel_key' => 'user_certification_list'
                ],
                'getskbank' => [ // 新光送件檢核表回填資料
                    'action' => 'update',
                    'model_key' => 'certification',
                    'submodel_key' => 'user_certification_list'
                ],
                'save_meta' => [ // 人工填寫風控因子
                    'action' => 'update',
                    'model_key' => 'certification',
                    'submodel_key' => 'user_certification_list'
                ],
                'getmeta' => [ // 帶入風控因子
                    'action' => 'update',
                    'model_key' => 'certification',
                    'submodel_key' => 'user_certification_list'
                ],
                'job_credits' => [
                    'action' => 'update',
                    'model_key' => 'certification',
                    'submodel_key' => 'user_certification_list'
                ],
                'joint_credits' => [
                    'action' => 'update',
                    'model_key' => 'certification',
                    'submodel_key' => 'user_certification_list'
                ],
                'sip' => [ // 學生身份認證
                    'action' => 'update',
                    'model_key' => 'certification',
                    'submodel_key' => 'user_certification_list'
                ],
                'sip_login' => [
                    'action' => 'update',
                    'model_key' => 'certification',
                    'submodel_key' => 'user_certification_list'
                ],
            ],
            'partner' => [
                'add' => [
                    'action' => 'create',
                    'model_key' => 'partner',
                    'submodel_key' => 'index',
                ],
                'edit' => [
                    'action' => 'update',
                    'model_key' => 'partner',
                    'submodel_key' => 'index',
                ],
                'partner_type_add' => [
                    'action' => 'create',
                    'model_key' => 'partner',
                    'submodel_key' => 'partner_type',
                ],
            ],
            'contact' => [
                'edit' => [
                    'action' => 'update',
                    'model_key' => 'contact',
                    'submodel_key' => 'index'
                ],
                'update_notificaion' => [
                    'action' => 'update',
                    'model_key' => 'contact',
                    'submodel_key' => 'send_email'
                ],
            ],
            'user' => [
                'edit' => [
                    'action' => 'update',
                    'model_key' => 'user',
                    'submodel_key' => 'index',
                ],
                'display' => [
                    'action' => 'read',
                    'model_key' => 'user',
                    'submodel_key' => 'index',
                ],
                'block_users' => [
                    'action' => 'update',
                    'model_key' => 'user',
                    'submodel_key' => 'blocked_users',
                ],
                'get_user_notification' => [
                    'action' => 'update',
                    'model_key' => 'user',
                    'submodel_key' => 'index',
                ],
                'judicialyuan' => [
                    'action' => 'update',
                    'model_key' => 'user',
                    'submodel_key' => 'index',
                ],
            ],
            'admin' => [
                'add' => [
                    'action' => 'create',
                    'model_key' => 'admin',
                    'submodel_key' => 'index',
                ],
                'edit' => [
                    'action' => 'update',
                    'model_key' => 'admin',
                    'submodel_key' => 'index',
                ],
                'get_promote_code' => [
                    'action' => 'update',
                    'model_key' => 'admin',
                    'submodel_key' => 'index',
                ],
                'role_add' => [
                    'action' => 'create',
                    'model_key' => 'admin',
                    'submodel_key' => 'role_list',
                ],
                'role_edit' => [
                    'action' => 'update',
                    'model_key' => 'admin',
                    'submodel_key' => 'role_list',
                ],
                'role_list_setting_get' => [
                    'action' => 'read',
                    'model_key' => 'admin',
                    'submodel_key' => 'role_list_setting',
                ],
                'role_list_edit' => [
                    'action' => 'update',
                    'model_key' => 'admin',
                    'submodel_key' => 'role_list_setting',
                ],
                'role_list_add' => [
                    'action' => 'create',
                    'model_key' => 'admin',
                    'submodel_key' => 'role_list_setting',
                ],
                'role_management_get' => [
                    'action' => 'read',
                    'model_key' => 'admin',
                    'submodel_key' => 'role_management',
                ],
                'role_management_edit' => [
                    'action' => 'update',
                    'model_key' => 'admin',
                    'submodel_key' => 'role_management',
                ],
                'role_management_add' => [
                    'action' => 'create',
                    'model_key' => 'admin',
                    'submodel_key' => 'role_management',
                ],
                'role_list_review_get' => [
                    'action' => 'read',
                    'model_key' => 'admin',
                    'submodel_key' => 'role_list_review',
                ],
                'role_review_edit' => [
                    'action' => 'update',
                    'model_key' => 'admin',
                    'submodel_key' => 'role_list_review',
                ],
                'role_permission_detail' => [
                    'action' => 'read',
                    'model_key' => 'admin',
                    'submodel_key' => 'role_permission_list',
                ],
                'update_permission_status' => [
                    'action' => 'update',
                    'model_key' => 'admin',
                    'submodel_key' => 'role_list_review',
                ],
                'get_group_list' => [
                    'action' => 'update',
                    'model_key' => 'admin',
                    'submodel_key' => 'role_management',
                ],
            ],
            'sales' => [
                'bonus_report_detail' => [
                    'action' => 'read',
                    'model_key' => 'sales',
                    'submodel_key' => 'bonus_report',
                ],
                'promote_edit' => [
                    'action' => 'update',
                    'model_key' => 'sales',
                    'submodel_key' => 'promote_list'
                ],
                'promote_reward_loan' => [
                    'action' => 'update',
                    'model_key' => 'sales',
                    'submodel_key' => 'promote_reward_list'
                ],
            ],
            'account' => [
                'estatement_excel' => [
                    'action' => 'read',
                    'model_key' => 'account',
                    'submodel_key' => 'estatement',
                ],
                'estatement_s_excel' => [
                    'action' => 'read',
                    'model_key' => 'account',
                    'submodel_key' => 'estatement',
                ],
            ],
            'ocr' => [
                'reports' => [
                    'action' => 'read',
                    'model_key' => 'ocr',
                    'submodel_key' => 'index',
                ],
                'report' => [
                    'action' => 'read',
                    'model_key' => 'ocr',
                    'submodel_key' => 'index',
                ],
                'save' => [
                    'action' => 'update',
                    'model_key' => 'ocr',
                    'submodel_key' => 'index',
                ],
                'send' => [
                    'action' => 'update',
                    'model_key' => 'ocr',
                    'submodel_key' => 'index',
                ],
            ],
            'postloan' => [
                'save_status' => [
                    'action' => 'update',
                    'model_key' => 'postloan',
                    'submodel_key' => 'legal_doc',
                ],
                'legal_doc_status' => [
                    'action' => 'update',
                    'model_key' => 'postloan',
                    'submodel_key' => 'legal_doc',
                ],
            ],
            'article' => [
                'article_add' => [
                    'action' => 'create',
                    'model_key' => 'article',
                    'submodel_key' => 'index',
                ],
                'article_edit' => [
                    'action' => 'update',
                    'model_key' => 'article',
                    'submodel_key' => 'index',
                ],
                'article_success' => [
                    'action' => 'update',
                    'model_key' => 'article',
                    'submodel_key' => 'index',
                ],
                'article_failed' => [
                    'action' => 'update',
                    'model_key' => 'article',
                    'submodel_key' => 'index',
                ],
                'article_del' => [
                    'action' => 'delete',
                    'model_key' => 'article',
                    'submodel_key' => 'index',
                ],
                'news_add' => [
                    'action' => 'create',
                    'model_key' => 'article',
                    'submodel_key' => 'news',
                ],
                'news_edit' => [
                    'action' => 'update',
                    'model_key' => 'article',
                    'submodel_key' => 'news',
                ],
                'news_success' => [
                    'action' => 'update',
                    'model_key' => 'article',
                    'submodel_key' => 'news',
                ],
                'news_failed' => [
                    'action' => 'update',
                    'model_key' => 'article',
                    'submodel_key' => 'news',
                ],
                'news_del' => [
                    'action' => 'delete',
                    'model_key' => 'article',
                    'submodel_key' => 'news',
                ],
            ],
            'agreement' => [
                'editagreement' => [
                    'action' => 'update',
                    'model_key' => 'agreement',
                    'submodel_key' => 'index'
                ],
                'insertagreement' => [
                    'action' => 'create',
                    'model_key' => 'agreement',
                    'submodel_key' => 'index'
                ],
                'updateagreement' => [
                    'action' => 'update',
                    'model_key' => 'agreement',
                    'submodel_key' => 'index'
                ],
                'deleteagreement' => [
                    'action' => 'delete',
                    'model_key' => 'agreement',
                    'submodel_key' => 'index'
                ],
                'aliasunique' => [
                    'action' => 'update',
                    'model_key' => 'agreement',
                    'submodel_key' => 'index'
                ],
            ],
            'contract' => [
                'editcontract' => [
                    'action' => 'update',
                    'model_key' => 'contract',
                    'submodel_key' => 'index',
                ],
                'updatecontract' => [
                    'action' => 'update',
                    'model_key' => 'contract',
                    'submodel_key' => 'index',
                ],
                'validateinput' => [
                    'action' => 'update',
                    'model_key' => 'contract',
                    'submodel_key' => 'index',
                ],
                'typeunique' => [
                    'action' => 'update',
                    'model_key' => 'contract',
                    'submodel_key' => 'index',
                ],
            ],
            'creditmanagement' => [
                'natural_person_report' => [
                    'action' => 'read',
                    'model_key' => 'risk',
                    'submodel_key' => 'natural_person',
                ],
                'final_validations_report' => [
                    'action' => 'read',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_evaluation',
                ],
                'final_validations_get_structural_data' => [
                    'action' => 'read',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_evaluation',
                ],
                'natural_person_get_structural_data' => [
                    'action' => 'read',
                    'model_key' => 'risk',
                    'submodel_key' => 'natural_person',
                ],
                'get_reviewed_list' => [
                    'action' => 'read',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_evaluation',
                ],
                'get_data' => [
                    'action' => 'read',
                    'model_key' => 'risk',
                    'submodel_key' => 'natural_person',
                ],
                'approve' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_evaluation',
                ],
            ],
            'creditmanagementtable' => [
                'index' => [
                    'action' => 'read',
                    'model_key' => 'ocr',
                    'submodel_key' => 'index',
                ],
                'waiting_reinspection_report' => [
                    'action' => 'read',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_verify',
                ],
                'juridical_person_report' => [
                    'action' => 'read',
                    'model_key' => 'risk',
                    'submodel_key' => 'juridical_person',
                ],
                'waiting_bidding_report' => [
                    'action' => 'read',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_bidding',
                ],
                'report' => [
                    'action' => 'read',
                    'model_key' => 'risk',
                    'submodel_key' => 'juridical_person',
                ],
            ],
            'bankdata' => [
                'index' => [
                    'action' => 'read',
                    'model_key' => 'ocr',
                    'submodel_key' => 'index',
                ],
                'juridical_person_report' => [
                    'action' => 'read',
                    'model_key' => 'risk',
                    'submodel_key' => 'juridical_person'
                ],
                'getmappingmsgno' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_evaluation',
                ],
                'savechecklistdata' => [
                    'action' => 'update',
                    'model_key' => 'risk',
                    'submodel_key' => 'juridical_person',
                ],
                'report' => [ // 百萬信保檢核表
                    'action' => 'read',
                    'model_key' => 'risk',
                    'submodel_key' => 'juridical_person',
                ],
                'brookesia' => [
                    'user_rule_hit' => [
                        'action' => 'update',
                        'model_key' => 'target',
                        'submodel_key' => 'waiting_verify'
                    ],
                    'final_valid_user_rule_hit' => [
                        'action' => 'update',
                        'model_key' => 'target',
                        'submodel_key' => 'waiting_evaluation'
                    ],
                    'user_related_user' => [
                        'action' => 'update',
                        'model_key' => 'target',
                        'submodel_key' => 'waiting_verify'
                    ],
                    'final_valid_user_related_user' => [
                        'action' => 'update',
                        'model_key' => 'target',
                        'submodel_key' => 'waiting_evaluation'
                    ],
                ],
            ],
            'scraper' => [
                'scraper_status' => [
                    'action' => 'read',
                    'model_key' => 'scraper',
                    'submodel_key' => 'index',
                ],
                'judicial_yuan_info' => [
                    'action' => 'read',
                    'model_key' => 'scraper',
                    'submodel_key' => 'index',
                ],
                'judicial_yuan_verdict' => [
                    'action' => 'read',
                    'model_key' => 'scraper',
                    'submodel_key' => 'index',
                ],
                'judicial_yuan_case' => [
                    'action' => 'read',
                    'model_key' => 'scraper',
                    'submodel_key' => 'index',
                ],
                'sip_info' => [
                    'action' => 'read',
                    'model_key' => 'scraper',
                    'submodel_key' => 'index',
                ],
                'sip' => [
                    'action' => 'read',
                    'model_key' => 'scraper',
                    'submodel_key' => 'index',
                ],
                'bizandbr_info' => [
                    'action' => 'read',
                    'model_key' => 'scraper',
                    'submodel_key' => 'index',
                ],
                'biz' => [
                    'action' => 'read',
                    'model_key' => 'scraper',
                    'submodel_key' => 'index',
                ],
                'businessregistration' => [
                    'action' => 'read',
                    'model_key' => 'scraper',
                    'submodel_key' => 'index',
                ],
                'google_info' => [
                    'action' => 'read',
                    'model_key' => 'scraper',
                    'submodel_key' => 'index',
                ],
                'google' => [
                    'action' => 'read',
                    'model_key' => 'scraper',
                    'submodel_key' => 'index',
                ],
                'ptt_info' => [
                    'action' => 'read',
                    'model_key' => 'scraper',
                    'submodel_key' => 'index',
                ],
                'ptt' => [
                    'action' => 'read',
                    'model_key' => 'scraper',
                    'submodel_key' => 'index',
                ],
                'instagram_info' => [
                    'action' => 'read',
                    'model_key' => 'scraper',
                    'submodel_key' => 'index',
                ],
                'instagram' => [
                    'action' => 'read',
                    'model_key' => 'scraper',
                    'submodel_key' => 'index',
                ],
            ],
            'certificationreport' => [
                'index' => [
                    'action' => 'read',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_evaluation'
                ],
                'report' => [
                    'action' => 'read',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_evaluation'
                ],
                'get_data' => [
                    'action' => 'read',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_evaluation'
                ],
                'send_data' => [
                    'action' => 'update',
                    'model_key' => 'target',
                    'submodel_key' => 'waiting_evaluation'
                ],
            ]
        ];
    }
}

?>

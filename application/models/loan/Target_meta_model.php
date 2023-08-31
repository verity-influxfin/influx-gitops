<?php

class Target_meta_model extends MY_Model
{
    public $_table = 'target_meta';
    public $before_create = array('before_data_c');
    public $before_update = array('before_data_u');
    public const REQUIRED_KEY = ['background', 'capacity', 'capitalization', 'changes', 'group_seniority', 'guarantor', 'seniority'];
    public const BK_SELF = 1;
    public const BK_SUCCESSOR = 2;
    public const BK_FAMILY_SUPPORT = 3;
    public const BK_PARTNERSHIP = 4;
    public const BK_OTHER = 5;
    public const JOB_SENIORITY_TEN_YEARS_ABOVE = 1;
    public const JOB_SENIORITY_FIVE_YEARS_ABOVE = 2;
    public const JOB_SENIORITY_THREE_YEARS_ABOVE = 3;
    public const JOB_SENIORITY_OTHER = 4;
    public const HUMAN_RESOURCE_KEYMAN_GE_THREE = 1;
    public const HUMAN_RESOURCE_DIMISSION_RATE_GE_HALF = 2;
    public const HUMAN_RESOURCE_AVERAGE_SALARY_GE_50000 = 4;
    public const HUMAN_RESOURCE_INVALID = 8;
    public const TEAM_SENIORITY_THREE_YEARS_ABOVE = 1;
    public const TEAM_SENIORITY_KEY_PROJECT_EXP = 2;
    public const TEAM_SENIORITY_INVALID = 3;
    public const ACTIVATION_GE_100 = 1;
    public const ACTIVATION_BT_70_100 = 2;
    public const ACTIVATION_BT_50_70 = 3;
    public const ACTIVATION_BT_50_BELOW = 4;
    public const ASSET_GE_30_MILLION =  1;
    public const ASSET_BT_10_30_MILLION = 2;
    public const ASSET_BT_5_10_MILLION = 3;
    public const ASSET_BT_0_5_MILLION = 4;
    public const ASSET_INVALID = 5;
    public const GUARANTOR_JOB_LECTURER = 1;
    public const GUARANTOR_JOB_PROFESSIONAL = 2;
    public const GUARANTOR_JOB_HUGE_COMPANY_EMPLOYEE = 3;
    public const GUARANTOR_JOB_GENERAL = 4;
    public const GUARANTOR_JOB_INVALID = 5;


    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->load->database('loan', TRUE);
    }

    protected function before_data_c($data)
    {
        $data['created_at'] = $data['updated_at'] = date('Y-m-d H:i:s');
        $data['created_ip'] = $data['updated_ip'] = get_ip();
        return $data;
    }

    protected function before_data_u($data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_ip'] = get_ip();
        return $data;
    }

    public function get_required_key_value($target_id)
    {
        return $this->db
            ->select('meta_key,meta_value')
            ->from('p2p_loan.target_meta')
            ->where_in('meta_key', self::REQUIRED_KEY)
            ->where('meta_key IS NOT NULL', NULL, FALSE)
            ->where('target_id', $target_id)
            ->get()
            ->result_array();
    }
}
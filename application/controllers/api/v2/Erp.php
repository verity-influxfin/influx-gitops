<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Erp extends DEUS_Controller
{
    /**
     * 請求資料
     *
     * @var        array
     */
    protected $payload = NULL;

    /**
     * 建構子
     * 
     * @created_at                 2021-08-04
     * @created_by                 Jack
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->library('sisyphus/erp_lib');

        if (! empty($data = $this->input->get_post('data')))
        {
            $this->payload = $this->erp_lib->decrypt($data);
        }
    }

    /**
     * 取得用戶銀行帳戶資料
     * 
     * @created_at                 2021-08-04
     * @created_by                 Jack
     * @updated_at                 2021-08-27
     * @updated_by                 Jack
     */
    public function user_account()
    {
        try
        {
            if (empty($user_id = $this->payload['user_id']))
            {
                throw new Exception('user_id is NOT Provided.');
            }
            $this->load->model('user/user_bankaccount_model');
            if (empty($result = $this->user_bankaccount_model->get_accounts_by_id($user_id)))
            {
                if (ENVIRONMENT != 'production')
                {
                    $user_id = (string) $user_id;
                    switch($user_id)
                    {
                        case '1864':
                        case '3812':
                        case '16872':
                        case '25585':
                        case '34144':
                        case '36905':
                        case '43948':
                        case '47206':
                        case '55437':
                        case '63362':
                        case '64594':
                        case '64736':
                            $result = [
                                'user_id'                => $user_id,
                                'title'                  => '乙方',
                                'bank_account'           => '812-2222-88776655443322',
                                'virtual_borrow_account' => '556-0000-55667788990011',
                            ];
                            break;
                        default:
                            $result = [
                                'user_id'                => $user_id,
                                'title'                  => '甲方',
                                'bank_account'           => '700-1111-22334455667788',
                                'virtual_invest_account' => '556-0000-00998877665544',
                            ];
                            break;
                    }
                }
                else
                {
                    throw new Exception('Invalid user_id.');
                }
            }
        }
        catch(Throwable $e)
        {
            $this->_output_json([
                'success' => FALSE,
                'message' => $e->getMessage()
            ]);
        }
        $this->_output_json([
            'success' => true,
            'data'    => $result
        ]);
    }

}
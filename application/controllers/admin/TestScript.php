<?php require(APPPATH . '/libraries/MY_Admin_Controller.php');

class TestScript extends MY_Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ( ! is_development())
        {
            show_404();
        }
    }

    public function index()
    {
        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/test_payment_script');
        $this->load->view('admin/_footer');
    }

    /**
     * 測試工具 - 新增匯款資料
     *
     * @created_at          2022-03-04
     * @created_by          Jack
     * @updated_at          2022-03-16
     * @updated_by          Jack
     */
    public function mockingTransfer()
    {
        switch (TRUE)
        {
            case ! is_numeric($user_id = trim($this->input->post('user_id'))):
                exit('無效的使用者');
                break;

            case ! in_array($investor = trim($this->input->post('investor')), [USER_BORROWER, USER_INVESTOR]):
                exit('無效的身分');
                break;

            case ! is_numeric($amount = trim($this->input->post('amount'))):
            case $amount <= 0:
                exit('無效的金額');
                break;
        }

        $this->load->model([
            'transaction/payment_model',
            'user/virtual_account_model',
            'user/user_bankaccount_model'
        ]);

        if (empty($virtual_account = $this->virtual_account_model->get_virtual_account_by_user($user_id, $investor)))
        {
            exit('找不到該用戶的虛擬帳戶');
        }

        $bank = $this->user_bankaccount_model->get_bank_account_by_user($user_id, $investor);
        if (empty($bank['bank_code']) || empty($bank['bank_account']))
        {
            exit('找不到該用戶的實體銀行帳戶');
        }

        $this->payment_model->insert([
            'tx_datetime'     => date('Y-m-d H:i:s'),
            'bankaccount_no'  => '015035006475',
            'amount'          => $amount,
            'virtual_account' => $virtual_account,
            'tx_seq_no'       => rand(10000, 99999),
            'tx_id_no'        => rand(1000, 9999),
            'tx_mach'         => '0999',
            'tx_spec'         => '網銀轉帳',
            'bank_amount'     => 0,
            'bank_id'         => $bank['bank_code'],
            'acc_name'        => '金普匯',
            'bank_acc'        => $bank['bank_code'] . $bank['bank_account'],
        ]);
        redirect(admin_url('TestScript'));
    }
}
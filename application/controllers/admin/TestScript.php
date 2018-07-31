<?php

require(APPPATH . "/libraries/MY_Admin_Controller.php");

class TestScript extends MY_Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("loan/investment_model");
        $this->load->model("transaction/payment_model");
        $this->load->model("user/virtual_account_model");
        $this->load->model("user/user_bankaccount_model");
        $this->load->library("Transaction_lib");
    }

    public function index()
    {
        $targetList = $this->target_model->get_all();
        $investmentList = $this->investment_model->get_all();

        $viewData = [
            "targetList" 		=> $targetList,
            "investmentList" 	=> $investmentList,
			"target_status"		=> $this->target_model->status_list,
			"investment_status"		=> $this->investment_model->status_list,
        ];

        $this->load->view("admin/_header");
        $this->load->view("admin/_title",$this->menu);
        $this->load->view("admin/test_script", $viewData);
        $this->load->view("admin/_footer");
    }

    public function payment()
    {
        $virtualAccountList = $this->virtual_account_model->get_all();
        $userBankAccountList = $this->user_bankaccount_model->get_all();
        $paymentList = $this->payment_model->get_all();

        $viewData = [
            "virtualAccountList" 	=> $virtualAccountList,
            "userBankAccountList" 	=> $userBankAccountList,
            "paymentList" 			=> $paymentList,
			"payment_status"		=> $this->payment_model->status_list,
			"bankaccount_verify"	=> array("0"=>"未驗證","1"=>"驗證成功","2"=>"待驗證","3"=>"已發送"),
        ];

        $this->load->view("admin/_header");
        $this->load->view("admin/_title",$this->menu);
        $this->load->view("admin/test_payment_script", $viewData);
        $this->load->view("admin/_footer");
    }

    private function redirectToIndex()
    {
        redirect("admin/testScript");
    }

    private function redirectToTestPaymentPage()
    {
        redirect("admin/TestScript/payment");
    }


    // status 2 to 3
   /* public function admin_verify_target_2_to_3()
    {
        $targetId = $this->input->post("target_id");

        $where = ["id" => $targetId, "status" => 2];
        $param = [
			"status" 		=> 3 , 
			"expire_time"	=> strtotime("+2 days", time()),
			"launch_times"	=> 1
		];
        $this->target_model->update_by($where, $param);

        $this->redirectToIndex();
    } */

 /*   public function VerifyBankAccount()
    {
        $bankaccount_id = $this->input->get("id");
        $param = ["verify"=> 1];
        $this->user_bankaccount_model->update($bankaccount_id, $param);
        $this->redirectToTestPaymentPage();
    }
*/

    // status 4 to 5
    public function lending_success()
    {
        $targetId = $this->input->post("target_id");

		$where = ["id" => $targetId, "status" => 4];
        $param = ["loan_status"	=> 1 ];
		 $this->target_model->update_by($where, $param);
        // TODO: 攤還資料未新增，未切換至status 5
        $rs = $this->transaction_lib->lending_success($targetId);

        $this->redirectToIndex();
    }

    public function insertPayment()
    {
        // payment表
        // Cron.handle_payment() > Payment_lib.script_handle_payment()

        // condition:
        // status=0
        // amount正數為入帳receipt()，負數為出帳expense()
        // virtual_account, bank_id ,bank_code, bank_account為有效值才可入帳
        $bankAccountNo 	= "015035006475";
        $amount 		= $this->input->post("amount");
        $virtualAccount = $this->input->post("virtual_account");
        $bankCode 		= $this->input->post("bank_code");
        $bankAccount 	= $this->input->post("bank_account");

        $txDatetime = date("Y-m-d H:i:s", strtotime("now"));
        $txSeqNo	= rand(10000, 99999);
        $txIdNo 	= rand(1000, 9999);
        $txMach 	= "0999";
        $txSpec 	= "網銀轉帳";
        $bankAmount = 0;
        $bankId 	= $bankCode;
        $accName 	= "金普匯";
        $bankAcc 	= $bankCode . $bankAccount;

        $param = [
            "tx_datetime" 		=> $txDatetime,
            "bankaccount_no" 	=> $bankAccountNo,
            "amount" 			=> $amount,
            "virtual_account" 	=> $virtualAccount,
            "tx_seq_no" 		=> $txSeqNo,
            "tx_id_no" 			=> $txIdNo,
            "tx_mach" 			=> $txMach,
            "tx_spec" 			=> $txSpec,
            "bank_amount" 		=> $bankAmount,
            "bank_id" 			=> $bankId,
            "acc_name" 			=> $accName,
            "bank_acc" 			=> $bankAcc,
        ];
        $this->payment_model->insert($param);

        return $this->redirectToTestPaymentPage();
    }
}
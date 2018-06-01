<div id="page-wrapper">
    <div class="row">
        <div class="col-md-2">
            <h3>匯款</h3>
            <form action="insertPayment" method="post">
                <div class="form-group">
                    <label>金額</label>
                    <input type="number" class="form-control" name="amount">
                </div>
                <div class="form-group">
                    <label>虛擬帳戶</label>
                    <input type="text" class="form-control" name="virtual_account">
                </div>
                <div class="form-group">
                    <label>銀行代碼</label>
                    <input type="text" class="form-control" name="bank_code">
                </div>
                <div class="form-group">
                    <label>銀行帳號</label>
                    <input type="text" class="form-control" name="bank_account">
                </div>
                <button type="submit" class="btn btn-default">匯入</button>
            </form>
        </div>
        <div class="col-md-5">
            <h3>虛擬帳號列表</h3>
            <table class="table table-striped">
                <thead>
                <tr>
                    <td>id</td>
                    <td>投資端</td>
                    <td>user_id</td>
                    <td>virtual_account</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($virtualAccountList as $virtualAccount): ?>
                    <tr>
                        <td><?= $virtualAccount->id ?></td>
                        <td><?= $virtualAccount->investor ?></td>
                        <td><?= $virtualAccount->user_id ?></td>
                        <td><?= $virtualAccount->virtual_account ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-5">
            <h3>銀行認證列表</h3>
            <table class="table table-striped">
                <thead>
                <tr>
                    <td>id</td>
                    <td>user_id</td>
                    <td>investor</td>
                    <td>bank_code</td>
                    <td>bank_account</td>
                    <td>verify</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($userBankAccountList as $userBankAccount): ?>
                    <tr>
                        <td><?= $userBankAccount->id ?></td>
                        <td><?= $userBankAccount->user_id ?></td>
                        <td><?= $userBankAccount->investor ?></td>
                        <td><?= $userBankAccount->bank_code ?></td>
                        <td><?= $userBankAccount->bank_account ?></td>
                        <td>
							<?= $bankaccount_verify[$userBankAccount->verify] ?>
							<?=$userBankAccount->verify==2?' <a href="'.admin_url('TestScript/VerifyBankAccount?id='.$userBankAccount->id).'">驗證</a>':"" ?>
						</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-2">
            <h3>銀行明細</h3>
            <table class="table table-striped">
                <thead>
                <tr>
                    <td>id</td>
                    <td>專戶號碼</td>
                    <td>金額</td>
                    <td>來源帳號</td>
                    <td>虛擬帳號</td>
                    <td>處理狀態</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($paymentList as $payment): ?>
                    <tr>
                        <td><?= $payment->id ?></td>
                        <td><?= $payment->bankaccount_no ?></td>
                        <td><?= $payment->amount ?></td>
                        <td><?= $payment->bank_acc ?></td>
                        <td><?= $payment->virtual_account ?></td>
                        <td><?= $payment_status[$payment->status] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
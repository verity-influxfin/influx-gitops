<div id="page-wrapper">

    <div class="col-md-6">
        <p>待驗證 > 待出借 status 2 to 3</p>
        <form action="<?=admin_url('TestScript/admin_verify_target_2_to_3') ?>" method="post">
            <div class="form-group">
                <label>target_id</label>
                <input type="number" class="form-control" name="target_id">
            </div>
            <button type="submit" class="btn btn-default">update</button>
        </form>
    </div>

    <div class="col-md-6">
        <p>放款 status 4 to 5</p>
        <form action="<?=admin_url('TestScript/lending_success') ?>" method="post">
            <div class="form-group">
                <label>target_id</label>
                <input type="number" class="form-control" name="target_id">
            </div>
            <button type="submit" class="btn btn-default">update</button>
        </form>
    </div>

    <div class="col-md-6">
        <table class="table">
            <thead>
            <tr>
                <th>target_id</th>
                <th>user_id</th>
                <th>amount</th>
                <th>loan_amount</th>
                <th>status</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($targetList as $target): ?>
                <tr>
                    <td><?= $target->id ?></td>
                    <td><?= $target->user_id ?></td>
                    <td><?= $target->amount ?></td>
                    <td><?= $target->loan_amount ?></td>
                    <td><?=$target_status[$target->status] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="col-md-6">
        <table class="table">
            <thead>
            <tr>
                <th>investment_id</th>
                <th>target_id</th>
                <th>狀態</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($investmentList as $investment): ?>
                <tr>
                    <td><?= $investment->id ?></td>
                    <td><?= $investment->target_id ?></td>
                    <td><?= $investment_status[$investment->status] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>


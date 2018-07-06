<div id="page-wrapper">
    <div class="row">
        <h1 class="page-header">協議書</h1>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="<?=admin_url('contract/editContract') ?>">
                <button class="btn btn-primary">新增</button>
            </a>
        </div>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>代號</th>
                <th>名稱</th>
                <th>最後更新時間</th>
                <th>修改</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($contracts as $contract): ?>
                <tr>
                    <td><?= $contract->id ?></td>
                    <td><?= $contract->alias ?></td>
                    <td><?= $contract->name ?></td>
                    <td>
                        <?= date("Y-m-d H:i:s", $contract->updated_at) ?>
                    </td>
                    <td>
                        <a href="<?=admin_url('contract/editContract?id='.$contract->id) ?>">
                            <button class="btn btn-default">修改</button>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
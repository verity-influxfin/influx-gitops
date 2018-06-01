<div id="page-wrapper">
    <div class="row">
        <h1 class="page-header">條款</h1>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="agreement/editAgreement">
                <button class="btn btn-primary">新增</button>
            </a>
        </div>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>id</th>
                <th>alias</th>
                <th>name</th>
                <th>updated_at</th>
                <th>修改</th>
                <th>刪除</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($agreements as $agreement): ?>
                <tr>
                    <td><?= $agreement->id ?></td>
                    <td><?= $agreement->alias ?></td>
                    <td><?= $agreement->name ?></td>
                    <td>
                        <?= date("Y-m-d H:i:s", $agreement->updated_at) ?>
                    </td>
                    <td>
                        <a href="<?=admin_url('agreement/editAgreement?id='.$agreement->id) ?>">
                            <button class="btn btn-default">修改</button>
                        </a>
                    </td>
                    <td>
                        <a href="<?=admin_url('agreement/deleteAgreement?id='.$agreement->id) ?>">
                            <button class="btn btn-danger">刪除</button>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
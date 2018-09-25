<div id="page-wrapper">
    <div class="row">
        <h1 class="page-header">協議書</h1>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="<?=admin_url('agreement/editAgreement') ?>">
                <button class="btn btn-primary">新增</button>
            </a>
        </div>
        <table  class="display responsive nowrap" width="100%" id="dataTables-paging">
            <thead>
            <tr>
                <th>ID</th>
                <th>代號</th>
                <th>名稱</th>
                <th>最後更新時間</th>
                <th>修改</th>
                <!--th>刪除</th-->
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
                    <!--td>
                        <a href="<?=admin_url('agreement/deleteAgreement?id='.$agreement->id) ?>">
                            <button class="btn btn-danger">刪除</button>
                        </a>
                    </td-->
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
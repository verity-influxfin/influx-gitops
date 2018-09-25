<div id="page-wrapper">
    <div class="row">
        <h1 class="page-header">合作商類別</h1>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="<?=admin_url('Partner/partner_type_add') ?>">
                <button class="btn btn-primary">新增</button>
            </a>
        </div>
        <table class="display responsive nowrap" width="100%" id="dataTables-paging">
            <thead>
            <tr>
                <th>NO.</th>
                <th>名稱</th>
                <th>創建者</th>
                <th>創建時間</th>
                <!--th>刪除</th-->
            </tr>
            </thead>
            <tbody>
            <?php foreach ($list as $key => $value){ ?>
                <tr>
                    <td><?= $value->id ?></td>
                    <td><?= $value->title ?></td>
					<td><?=isset($name_list[$value->creator_id])?$name_list[$value->creator_id]:"" ?></td>
                    <td>
                        <?= date("Y-m-d H:i:s", $value->created_at) ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
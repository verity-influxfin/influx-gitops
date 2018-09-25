<div id="page-wrapper">
    <div class="row">
        <h1 class="page-header">後台權限管理</h1>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="<?=admin_url('Admin/role_add') ?>">
                <button class="btn btn-primary">新增角色</button>
            </a>
        </div>
        <table  class="display responsive nowrap" width="100%" id="dataTables-paging">
            <thead>
            <tr>
                <th>NO.</th>
                <th>代號</th>
                <th>角色名稱</th>
                <th>創建者</th>
                <th>創建時間</th>
                <th>修改</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($list as $key => $value){ ?>
                <tr>
                    <td><?= $value->id ?></td>
                    <td><?= $value->alias ?></td>
                    <td><?= $value->name ?></td>
					<td><?=isset($name_list[$value->creator_id])?$name_list[$value->creator_id]:"" ?></td>
                    <td>
                        <?= date("Y-m-d H:i:s", $value->created_at) ?>
                    </td>
					<td><a href="<?=admin_url('Admin/role_edit?id='.$value->id) ?>" class="btn btn-default">Edit</a></td> 
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
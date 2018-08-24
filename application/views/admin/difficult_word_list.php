<div id="page-wrapper">
    <div class="row">
        <h1 class="page-header">銀行困難字列表</h1>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="<?=admin_url('certification/difficult_word_add') ?>">
                <button class="btn btn-primary">新增</button>
            </a>
        </div>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>NO.</th>
                <th>文字</th>
                <th>拆分文字</th>
                <th>創建日期</th>
				<th>創建者</th>
                <th>修改</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($list as $key => $value): ?>
                <tr>
                    <td><?= $value->id ?></td>
                    <td><?= $value->word ?></td>
                    <td><?= $value->spelling ?></td>
                    <td>
                        <?= date("Y-m-d H:i:s", $value->created_at) ?>
                    </td>
					<td><?=isset($name_list[$value->creator_id])?$name_list[$value->creator_id]:"" ?></td>
                    <td>
                        <a href="<?=admin_url('certification/difficult_word_edit?id='.$value->id) ?>">
                            <button class="btn btn-default">修改</button>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
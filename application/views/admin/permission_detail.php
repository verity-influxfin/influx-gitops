<style>
    #page-wrapper {
        font-size: 16px;
    }

    .basic-info {
        margin-top: 25px;
        border-spacing: 12px;
        border-collapse: separate;
        width: 100%;
    }

    .settings-item {
        padding: 25px 0 0 25px;
    }

    .block-title {
        line-height: 34px;
        border-radius: 6px;
        background-color: #c4c4c4;
        width: 130px;
        text-align: center;
    }

    .item {
        padding: 0 12px;
        text-align: center;
    }

    .bgc-2 {
        background: #C4C4C455;
    }

    .settings {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .input-custom {
        width: 200px;
    }

    .row {
        margin: 25px 0;
        display: flex;
    }

    .panel-title {
        flex: 1 0 auto;
        width: 81px;
    }

    .panel {
        flex: 1 1 100%;
        max-width: 100%;
        max-height: 280px;
        overflow: auto;
    }

    .panel-body {
        max-height: 500px;
    }

    .panel-body::before {
        display: none;
    }

    .checkbox-group {
        display: flex;
        gap: 12px;
        padding: 4px;
        margin-bottom: 10px;
    }

    .check-title {
        width: 70px;
    }

    .button-row {
        display: flex;
        justify-content: center;
    }

    .button-row .btn {
        margin: 0 10px 10px 10px;
    }
</style>

<div id="page-wrapper">
    <table class="basic-info">
        <thead>
        <th class="block-title">姓名</th>
        <th class="block-title">帳號</th>
        <th class="block-title">部門</th>
        <th class="block-title">組別</th>
        <th class="block-title">角色</th>
        </thead>
        <tbody>
        <tr>
            <td class="item"><?= $data['name'] ?? '' ?></td>
            <td class="item"><?= $data['email'] ?? '' ?></td>
            <td class="item"><?= $data['division'] ?? '' ?></td>
            <td class="item"><?= $data['department'] ?? '' ?></td>
            <td class="item"><?= $data['position'] ?? '' ?></td>
        </tr>
        </tbody>
    </table>

    <div class="settings">
        <div class="settings-item">
            <div class="block-title bgc-2">權限設定</div>
            <?php
            foreach ($permission_list as $model_key => $value)
            {
                if (empty($value['menu'])) continue;
                if ( ! isset($group_permission_data[$model_key])) continue;
                ?>
                <div class="row">
                    <div class="panel-title"><?= $value['name'] ?? '' ?> :</div>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <?php foreach ($value['menu'] as $submodel_key => $submodel_key_value)
                            {
                                if ( ! isset($group_permission_data[$model_key][$submodel_key])) continue;
                                ?>
                                <div class="checkbox-group">
                                    <div class="check-title"><?= $submodel_key_value['name'] ?? '' ?></div>

                                    <?php foreach ($action_type_list as $action_type_value)
                                    {
                                        $checked = ($group_permission_data[$model_key][$submodel_key] & $action_type_value['key']) ? 'checked' : '';
                                        ?>
                                        <div class="check-item">
                                            <input type="checkbox"
                                                   name="permission[<?= $model_key ?>][<?= $submodel_key ?>][]"
                                                   value="<?= $action_type_value['key'] ?>" <?= $checked ?>>
                                            <label><?= $action_type_value['value'] ?></label>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="settings-item">
            <div class="block-title bgc-2">新增例外權限</div>
            <?php
            foreach ($permission_list as $model_key => $value)
            {
                if (empty($value['menu'])) continue;
                if ( ! isset($admin_permission_data[$model_key])) continue;
                ?>
                <div class="row">
                    <div class="panel-title"><?= $value['name'] ?? '' ?> :</div>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <?php foreach ($value['menu'] as $submodel_key => $submodel_key_value)
                            {
                                if ( ! isset($admin_permission_data[$model_key][$submodel_key])) continue;
                                ?>
                                <div class="checkbox-group">
                                    <div class="check-title"><?= $submodel_key_value['name'] ?? '' ?></div>

                                    <?php foreach ($action_type_list as $action_type_value)
                                    {
                                        $checked = ($admin_permission_data[$model_key][$submodel_key] & $action_type_value['key']) ? 'checked' : '';
                                        ?>
                                        <div class="check-item">
                                            <input type="checkbox"
                                                   name="permission[<?= $model_key ?>][<?= $submodel_key ?>][]"
                                                   value="<?= $action_type_value['key'] ?>" <?= $checked ?>>
                                            <label><?= $action_type_value['value'] ?></label>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="button-row">
        <button class="btn" onclick="history.go(-1)">返回</button>
    </div>
</div>

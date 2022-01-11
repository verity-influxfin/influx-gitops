<style>
    #page-wrapper {
        font-size: 16px;
    }

    .settings-item {
        padding: 25px 0 0 25px;
        display: flex;
        gap: 15px;
    }

    .block-title {
        border-radius: 6px;
        line-height: 34px;
        background-color: #c4c4c4;
        width: 110px;
        text-align: center;
    }

    .input-custom {
        width: 140px;
    }

    .row {
        margin: 25px 0;
        display: flex;
        gap: 15px;
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
        display: grid;
        grid-template-columns: 1fr 1fr;
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
</style>

<form role="form" method="post">
    <div id="page-wrapper">
        <input type="hidden" name="id" value="<?= $data['id'] ?? 0; ?>">
        <div class="settings-item">
            <div class="block-title">部門</div>
            <div class="input-group input-custom">
                <input class="form-control" name="division" type="text" value="<?= $data['division'] ?? '' ?>">
            </div>
        </div>
        <div class="settings-item">
            <div class="block-title">組別</div>
            <div class="input-group input-custom">
                <input class="form-control" name="department" type="text" value="<?= $data['department'] ?? '' ?>">
            </div>
        </div>
        <div class="settings-item">
            <div class="block-title">角色名稱</div>
            <div class="input-group input-custom">
                <select class="form-control" name="position">
                    <option value="">請選擇</option>
                    <?php foreach ($position_list as $key => $value) { ?>
                        <option value="<?= $key ?>" <?= $key == ($data['position'] ?? 0) ? 'selected' : '' ?>><?= $value ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="settings-item">
            <div class="block-title">權限設定</div>
        </div>
        <?php
        $data['permission'] = $data['permission'] ?? [];
        array_walk($data['permission'], function (&$value) use (&$permission_data) {
            $permission_data[$value['model_key']][$value['submodel_key']] = $value['action_type'];
        }, $permission_data);

        $action_type_list = array_map(function ($element) {
            return ['key' => pow(2, $element['key']), 'value' => $element['value']];
        }, $action_type_list);

        foreach ($permission_list as $model_key => $value)
        {
            ?>
            <div class="row">
                <div class="panel-title"><?= $value['name'] ?? '' ?> :</div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <?php foreach ($value['list'] as $submodel_key => $submodel_key_value) { ?>
                            <div class="checkbox-group">
                                <div class="check-title"><?= $submodel_key_value['name'] ?></div>

                                <?php foreach ($action_type_list as $action_type_value)
                                {
                                    $checked = '';
                                    if (isset($permission_data[$model_key][$submodel_key]) && ($permission_data[$model_key][$submodel_key] & $action_type_value['key']))
                                    {
                                        $checked = 'checked';
                                    }
                                    ?>
                                    <div class="check-item">
                                        <input type="checkbox"
                                               name="permission[<?= $model_key ?>][<?= $submodel_key ?>][action][]"
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
        <div class="button-row">
            <button class="btn btn-primary">儲存</button>
        </div>
    </div>
</form>
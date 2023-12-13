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
        width: 130px;
        text-align: center;
    }

    .bgc-2 {
        background: #C4C4C455;
    }

    .authoritys {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .input-custom {
        width: 200px;
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

    .button-row .btn {
        margin: 0 10px 10px 10px;
    }
</style>

<div id="page-wrapper">
    <form role="form" method="post">
        <div class="settings-item">
            <div class="block-title">帳號</div>
            <div class="input-group input-custom">
                <select class="form-control" name="id" id="admin_id">
                    <?php
                    foreach ($admin_list as $value)
                    {
                        if ( ! isset($value['id']) || (empty($value['id']) && $value['id'] !== 0)) continue;
                        ?>
                        <option value="<?= $value['id'] ?>"
                                data-name="<?= $value['name'] ?? '' ?>"
                                data-email="<?= $value['email'] ?? '' ?>"
                        ><?= $value['email'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="settings-item">
            <div class="block-title">姓名</div>
            <div class="input-group input-custom">
                <input class="form-control" type="text" disabled id="admin_name">
            </div>
        </div>
        <div class="authoritys mb-3">
            <div class="authority-item">
                <input name="group_id" id="group_id"
                       type="hidden"
                       value="<?= $data['group_id'] ?? 0 ?>"
                       data-division="<?= $data['division'] ?? '' ?>"
                       data-department="<?= $data['department'] ?? '' ?>"
                       data-position="<?= $data['position'] ?? '' ?>"
                >
                <div class="settings-item">
                    <div class="block-title bgc-2 my-3">權限1
                    </div>
                </div>
                <div class=" settings-item">
                    <div class="block-title">部門</div>
                    <div class="input-group input-custom">
                        <select class="form-control" id="group_division">
                            <option value="">請選擇</option>
                        </select>
                    </div>
                </div>
                <div class="settings-item">
                    <div class="block-title">組別</div>
                    <div class="input-group input-custom">
                        <select class="form-control" id="group_department">
                            <option value="">請選擇</option>
                        </select>
                    </div>
                </div>
                <div class="settings-item">
                    <div class="block-title">角色名稱</div>
                    <div class="input-group input-custom">
                        <select class="form-control" id="group_position">
                            <option value="">請選擇</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="settings-item">
            <div class="block-title bgc-2">新增例外權限</div>
        </div>
        <?php
        foreach ($permission_list as $model_key => $value)
        {
            if (empty($value['menu'])) continue; ?>
            <div class="row">
                <div class="panel-title"><?= $value['name'] ?? '' ?> :</div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <?php foreach ($value['menu'] as $submodel_key => $submodel_key_value) { ?>
                            <div class="checkbox-group">
                                <div class="check-title"><?= $submodel_key_value['name'] ?? '' ?></div>
                                <?php foreach ($action_type_list as $action_type_value)
                                {
                                    $id = "{$model_key}_{$submodel_key}_{$action_type_value['key']}";
                                    $checked = isset($permission_data[$model_key][$submodel_key]) && ($permission_data[$model_key][$submodel_key] & $action_type_value['key'])
                                        ? 'checked'
                                        : '';
                                    ?>
                                    <div class="check-item">
                                        <input type="checkbox"
                                               name="permission[<?= $model_key ?>][<?= $submodel_key ?>][action][]"
                                               value="<?= $action_type_value['key'] ?>" <?= $checked ?>
                                               id="<?= $id ?>">
                                        <label for="<?= $id ?>"><?= $action_type_value['value'] ?></label>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="button-row">
            <button type="button" class="btn" onclick="history.go(-1)">取消</button>
            <button type="submit" class="btn btn-primary">送出申請</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(() => {
        const group_division = $('#group_division');
        const group_department = $('#group_department');
        const group_position = $('#group_position');
        const group_id = $('#group_id');
        let group_list = get_group_list();
        draw_division({
            options: group_list,
            selector: group_division
        });

        group_division.on('change', function () {
            group_position.find('option').not(':first').remove();
            group_id.val('');
            if (!$(this).val()) return true;
            draw_department({
                options: group_list[$(this).val()]['department'],
                selector: group_department
            });
        });
        group_department.on('change', function () {
            group_id.val('');
            if (!group_division.val()) return true;
            draw_position({
                options: group_list[group_division.val()]['department'][$(this).val()]['position'],
                selector: group_position
            });
        });
        group_position.on('change', function () {
            group_id.val($(this).val());
        });

        $('#admin_id').on('change', function () {
            $('#admin_name').val($(this).find(':selected').data('name'));
        }).trigger('change');

        let group_id_value = group_id.val();
        group_division.val(group_division.find('option[data-name="' + group_id.data('division') + '"]').val()).trigger('change');
        group_department.val(group_department.find('option[data-name="' + group_id.data('department') + '"]').val()).trigger('change');
        group_position.val(group_id_value).trigger('change');
    });

    function get_group_list() {
        let group_list;
        $.ajax({
            url: 'get_group_list',
            type: 'get',
            dataType: 'json',
            async: false,
            success: function (response) {
                if (!response['data']) {
                    return;
                }

                group_list = response['data'];
            }
        });
        return group_list;
    }

    function draw_division({options, selector}) {
        selector.find('option').not(':first').remove();

        $.each(options, function (index, value) {
            selector.append($('<option></option>').text(value['name']).attr({
                'value': index,
                'data-name': value['name']
            }));
        });
    }

    function draw_department({options, selector}) {
        selector.find('option').not(':first').remove();

        $.each(options, function (index, value) {
            selector.append($('<option></option>').text(value['name']).attr({
                'value': index,
                'data-name': value['name']
            }));
        });
    }

    function draw_position({options, selector}) {
        selector.find('option').not(':first').remove();
        let position_list = JSON.parse('<?= json_encode($position_list) ?>');

        $.each(options, function (index, value) {
            selector.append($('<option></option>').text(position_list[value]).attr({value: index}));
        });
    }
</script>
<script type="text/javascript">
    function check_fail() {
        var status = $('#status :selected').val();
        if (status == 2) {
            $('#fail_div').show();
        } else {
            $('#fail_div').hide();
        }
    }

    $(document).off("change", "select#fail").on("change", "select#fail", function () {
        let selector = $('input#fail2');
        if ($(this).find(':selected').attr('value') === 'other') {
            selector.css('display', 'block').attr('disabled', false);
        } else {
            selector.css('display', 'none').attr('disabled', true);
        }
    });
</script>
<style>
    table.admin-edit td {
        padding: 1px 3px 0 3px !important;
    }
</style>
<?php
$certification_name = $certification_list[$data->certification_id] ?? '';
$user_id = $data->user_id ?? '';
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= $certification_name; ?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= $certification_name; ?>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>會員 ID</label>
                                <a class="fancyframe" href="<?= admin_url('User/display?id=' . $user_id) ?>">
                                    <p><?= $user_id ?></p>
                                </a>
                            </div>
                            <div class="form-group">
                                <label>添購傢俱家電金額</label>
                                <p class="form-control-static"><?= $content['contract_amount'] ?? '' ?></p>
                            </div>
                            <div class="form-group">
                                <label>發票號碼</label>
                                <p class="form-control-static"><?= $content['receipt_number'] ?? '' ?></p>
                            </div>
                            <div class="form-group">
                                <label>發票金額</label>
                                <p class="form-control-static"><?= $content['receipt_amount'] ?? '' ?></p>
                            </div>
                            <div class="form-group">
                                <label>備註</label>
                                <?php
                                $fail_msg = '';
                                if ( ! empty($remark['fail']))
                                {
                                    $fail_msg = $remark['fail'];
                                    echo '<p style="color:red;" class="form-control-static">失敗原因：' . $fail_msg . '</p>';
                                }
                                ?>
                            </div>
                            <div class="form-group">
                                <label>系統審核</label>
                                <?php
                                if (isset($sys_check))
                                {
                                    echo '<p class="form-control-static">' . ($sys_check == 1 ? '是' : '否') . '</p>';
                                }
                                ?>
                            </div>
                            <h4>審核</h4>
                            <form role="form" method="post" action="/admin/certification/user_certification_edit">
                                <input type="hidden" name="id" value="<?= $data->id ?? '' ?>">
                                <input type="hidden" name="from" value="<?= $from ?? '' ?>">
                                <input type="hidden" name="certification_id"
                                       value="<?= $data->certification_id ?? '' ?>">
                                <h4>審核人員確認</h4>
                                <?php
                                $admin_edit = $content['admin_edit'] ?? [];
                                $input_disabled = $data->status != CERTIFICATION_STATUS_PENDING_TO_REVIEW ? 'disabled' : '';
                                ?>
                                <fieldset>
                                    <div class="form-group">
                                        <table class="admin-edit">
                                            <tr>
                                                <td><label>姓名</label></td>
                                                <td>
                                                    <input type="text" class="form-control" name=""
                                                           value="<?= $user_name ?? '' ?>" disabled>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label>房屋門牌地址</label></td>
                                                <td>
                                                    <input type="text" class="form-control" name=""
                                                           value="<?= $house_deed_address ?? '' ?>" disabled>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label>金額</label></td>
                                                <td>
                                                    <?php
                                                    if ( ! empty($admin_edit['amount']))
                                                    {
                                                        $amount = $admin_edit['amount'];
                                                    }
                                                    elseif ( ! empty($admin_edit['contract_amount']) && ! empty($admin_edit['receipt_amount']))
                                                    {
                                                        $amount = $admin_edit['receipt_amount'];
                                                    }
                                                    elseif ( ! empty($admin_edit['contract_amount']))
                                                    {
                                                        $amount = $admin_edit['contract_amount'];
                                                    }
                                                    elseif ( ! empty($admin_edit['receipt_amount']))
                                                    {
                                                        $amount = $admin_edit['receipt_amount'];
                                                    }
                                                    else
                                                    {
                                                        $amount = '';
                                                    }
                                                    ?>
                                                    <input type="number" class="form-control"
                                                           name="admin_edit[amount]"
                                                           value="<?= $amount ?>" <?= $input_disabled ?>>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label>發票號碼</label></td>
                                                <td>
                                                    <input type="text" class="form-control"
                                                           name="admin_edit[receipt_number]"
                                                           value="<?= $admin_edit['receipt_number'] ?? '' ?>" <?= $input_disabled ?>>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label>合約/發票簽訂日</label></td>
                                                <td>
                                                    <input type="date" class="form-control"
                                                           name="admin_edit[contract_date]"
                                                           value="<?= $admin_edit['contract_date'] ?? '' ?>" <?= $input_disabled ?>>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </fieldset>
                                <h4>審核</h4>
                                <fieldset>
                                    <div class="form-group">
                                        <select id="status" name="status" class="form-control" onchange="check_fail();">
                                            <?php foreach ($status_list as $key => $value) { ?>
                                                <option value="<?= $key ?>" <?= $data->status == $key ? 'selected' : '' ?>><?= $value ?></option>
                                            <?php } ?>
                                        </select>
                                        <input type="hidden" name="id"
                                               value="<?= $data->id ?? '' ?>">
                                        <input type="hidden" name="from" value="<?= $from ?? '' ?>">
                                    </div>
                                    <div class="form-group" id="fail_div" style="display:none">
                                        <label>失敗原因</label>
                                        <select id="fail" name="fail" class="form-control">
                                            <option value="" disabled selected>選擇回覆內容</option>
                                            <?php foreach ($certifications_msg[$data->certification_id] as $key => $value) { ?>
                                                <option <?= $data->status == $value ? 'selected' : '' ?>><?= $value ?></option>
                                            <?php } ?>
                                            <option value="other">其它</option>
                                        </select>
                                        <input type="text" class="form-control" id="fail2" name="fail2"
                                               value="<?= $fail_msg; ?>"
                                               style="background-color:white!important;display:none">
                                    </div>
                                    <button type="submit" class="btn btn-primary">送出</button>
                                </fieldset>
                            </form>
                        </div>
                        <div class="col-lg-6">
                            <h1>圖片</h1>
                            <fieldset disabled>
                                <div class="form-group">
                                    <label for="disabledSelect">合約照片</label><br>
                                    <?php if ( ! empty($content['contract_images']))
                                    {
                                        foreach ($content['contract_images'] as $image)
                                        { ?>
                                            <a href="<?= $image ?>" data-fancybox="images">
                                                <img src="<?= $image ?>"
                                                     style='width:30%;max-width:400px'>
                                            </a>
                                        <?php }
                                    } ?>
                                </div>
                                <div class="form-group">
                                    <label for="disabledSelect">發票照片</label><br>
                                    <?php if ( ! empty($content['receipt_images']))
                                    {
                                        foreach ($content['receipt_images'] as $image)
                                        { ?>
                                            <a href="<?= $image ?>" data-fancybox="images">
                                                <img src="<?= $image ?>"
                                                     style='width:30%;max-width:400px'>
                                            </a>
                                        <?php }
                                    } ?>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <!-- /.row (nested) -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->

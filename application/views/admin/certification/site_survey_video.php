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
                            <form role="form" method="post" action="/admin/certification/user_certification_edit">
                                <input type="hidden" name="id" value="<?= $data->id ?? '' ?>">
                                <input type="hidden" name="from" value="<?= $from ?? '' ?>">
                                <input type="hidden" name="certification_id"
                                       value="<?= $data->certification_id ?? '' ?>">
                                <?php
                                $admin_edit = $content['admin_edit'] ?? [];
                                $input_disabled = $data->status != CERTIFICATION_STATUS_PENDING_TO_REVIEW ? 'disabled' : '';

                                $fail_msg = '';
                                if ( ! empty($remark['fail']))
                                {
                                    $fail_msg = $remark['fail'];
                                    echo '<p style="color:red;" class="form-control-static">失敗原因：' . $fail_msg . '</p>';
                                }

                                ?>
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
                            <h1>影片</h1>
                            <fieldset>
                                <div class="form-group">
                                    <label for="disabledSelect"></label><br>
                                    <?php if ( ! empty($content['video']))
                                    {
                                        $index = 0;
                                        foreach ($content['video'] as $video)
                                        { ?>
                                            <a href="<?= $video ?>" class="btn btn-info">
                                                影片<?= ++$index; ?>
                                            </a>
                                        <?php }
                                    } ?>
                                </div>
                            </fieldset>
                            <?php if ( ! empty($ocr['upload_page']))
                            {
                                ?>
                                <div class="form-group" style="background:#f5f5f5;border-style:double;">
                                    <?= $ocr['upload_page']; ?>
                                </div>
                            <?php } ?>
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

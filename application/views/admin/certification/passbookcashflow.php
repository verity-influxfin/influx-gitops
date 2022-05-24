<script type="text/javascript">
    function check_fail() {
        if ($('#status :selected').val() === '2') {
            $('#fail_div').show();
        } else {
            $('#fail_div').hide();
        }
    }

    $(document).off("change", "select#fail").on("change", "select#fail", function () {
        if ($(this).find(':selected') === 'other') {
            $('input#fail').css('display', 'block').attr('disabled', false);
        } else {
            $('input#fail').css('display', 'none').attr('disabled', true);
        }
    });
</script>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= isset($data->certification_id) ? $certification_list[$data->certification_id] : ""; ?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= isset($data->certification_id) ? $certification_list[$data->certification_id] : ""; ?>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>會員 ID</label>
                                <a class="fancyframe" href="<?= admin_url('User/display?id=' . $data->user_id) ?>">
                                    <p><?= isset($data->user_id) ? $data->user_id : "" ?></p>
                                </a>
                            </div>
                            <div class="form-group">
                                <label>備註</label>
                                <?php
                                if ( ! empty($remark['fail'])) {
                                    echo '<p style="color:red;" class="form-control-static">失敗原因：' . $remark['fail'] . '</p>';
                                }
                                ?>
                            </div>
                            <div class="form-group">
                                <label>系統審核</label>
                                <?
                                if (isset($sys_check)) {
                                    echo '<p class="form-control-static">' . ($sys_check==1?'是':'否') . '</p>';
                                }
                                ?>
                            </div>
                            <h4>審核</h4>
                            <form role="form" method="post">
                                <fieldset>
                                    <div class="form-group">
                                        <select id="status" name="status" class="form-control" onchange="check_fail();">
                                            <? foreach ($status_list as $key => $value) { ?>
                                                <option value="<?= $key ?>"
                                                        <?= $data->status == $key ? "selected" : "" ?>><?= $value ?></option>
                                            <? } ?>
                                        </select>
                                        <input type="hidden" name="id"
                                               value="<?= isset($data->id) ? $data->id : ""; ?>">
                                        <input type="hidden" name="from" value="<?= isset($from) ? $from : ""; ?>">
                                    </div>
                                    <div class="form-group" id="fail_div" style="display:none">
                                        <label>失敗原因</label>
                                        <select id="fail" name="fail" class="form-control">
                                            <option value="" disabled selected>選擇回覆內容</option>
                                            <? foreach ($certifications_msg[$data->certification_id] as $key => $value) { ?>
                                                <option
                                                    <?= $data->status == $value ? "selected" : "" ?>><?= $value ?></option>
                                            <? } ?>
                                            <option value="other">其它</option>
                                        </select>
                                        <input type="text" class="form-control" id="fail" name="fail"
                                               value="<?= $remark && isset($remark["fail"]) ? $remark["fail"] : ""; ?>"
                                               style="background-color:white!important;display:none" disabled="false">
                                    </div>
                                    <button type="submit" class="btn btn-primary">送出</button>
                                </fieldset>
                            </form>
                        </div>
                        <div class="col-lg-6">
                            <h1>圖片/文件</h1>
                            <fieldset disabled>
                                <div class="form-group">
                                    <label>金流證明</label><br>
                                    <?php
                                    if ( ! empty($content['passbook_image']))
                                    {
                                        $content['passbook_image'] = ! is_array($content['passbook_image']) ? array($content['passbook_image']) : $content['passbook_image'];
                                        foreach ($content['passbook_image'] as $key => $value)
                                        {
                                            if (empty($value)) continue; ?>
                                            <a href="<?= $value ?>" data-fancybox="images">
                                                <img src="<?= $value ?>" style='width:30%;max-width:400px'>
                                            </a>
                                        <?php }
                                    } ?>
                                </div>
                                <div class='form-group'>
                                    <label>其他</label><br>
                                    <?php
                                    if ( ! empty($content['file_list']['image']))
                                    { // 擴大信保【後】的Web上傳圖片
                                        foreach ($content['file_list']['image'] as $key => $value)
                                        {
                                            if (empty($value['url'])) continue; ?>
                                            <a href="<?= $value['url'] ?>" data-fancybox="images">
                                                <img src="<?= $value['url'] ?>"
                                                     style='width:30%;max-width:400px'>
                                            </a>
                                        <?php }
                                        echo '<hr/>';
                                    }
                                    if ( ! empty($content['file_list']['file']))
                                    { // 擴大信保【後】的Web上傳PDF
                                        foreach ($content['file_list']['file'] as $key => $value)
                                        {
                                            if (empty($value['url'])) continue; ?>
                                            <a href="<?= $value['url'] ?>">
                                                <i class="fa fa-file"> <?= $value['file_name'] ?? '檔案' ?></i>
                                            </a>
                                        <?php }
                                        echo '<hr/>';
                                    } ?>
                                </div>
                            </fieldset>
                            <? if($data->certification_id == 1004 && isset($ocr['upload_page']) ){ ?>
							<div class="form-group" style="background:#f5f5f5;border-style:double;">
							  <?= isset($ocr['upload_page']) ? $ocr['upload_page'] : ""?>
							</div>
							<? } ?>
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

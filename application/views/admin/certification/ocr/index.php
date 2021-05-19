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
        var sel = $(this).find(':selected');
        $('input#fail').css('display', sel.attr('value') == 'other' ? 'block' : 'none');
        $('input#fail').attr('disabled', sel.attr('value') == 'other' ? false : true);
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
                      <!-- ocr head start -->
                      <div class="col-lg-12">
                        <div class="form-group">
                            <label>會員 ID</label>
                            <a class="fancyframe" href="<?= admin_url('User/display?id=' . $data->user_id) ?>">
                                <p><?= isset($data->user_id) ? $data->user_id : "" ?></p>
                            </a>
                        </div>
                      </div>
                      <!-- ocr head end -->
                      <!-- ocr body start -->
                      <div class="col-lg-12">
                          <div class="form-group">
                              <label>總表</label>
                              <?= isset($ocr['total_table']) ? $ocr['total_table'] : ""?>
                          </div>
                      </div>
                      <div class="col-lg-12">
                        <? isset($ocr['url']) && !is_array($ocr['url']) ? $ocr['url'] = array($ocr['url']) : '';
                        foreach ($ocr['url'] as $key => $value) { ?>
                            <label><a href="<?= isset($value) ? $value : ''; ?>" target="_blank">前往編輯頁面</a></label>
                        <? } ?>
                      </div>
                      <!-- ocr body end -->
                      <!-- admin form start -->
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>圖片</label>
                                <fieldset disabled>
                                    <div class="form-group">
                                        <? isset($ocr['img']) && !is_array($ocr['img']) ? $ocr['img'] = array($ocr['img']) : '';
                                        foreach ($ocr['img'] as $key => $value) { ?>
                                            <a href="<?= isset($value) ? $value : "" ?>" data-fancybox="images">
                                                <img src="<?= $value ? $value : "" ?>" style='width:30%;max-width:400px'>
                                            </a>
                                        <? } ?>
                                    </div>
                                </fieldset>
                            </div>
							<? if( ($data->certification_id == 9 || $data->certification_id == 1003) && isset($ocr['upload_page']) ){ ?>
							<div class="form-group">
							  <?= isset($ocr['upload_page']) ? $ocr['upload_page'] : ""?>
							</div>
							<? } ?>
                            <div class="form-group">
                                <label>備註</label>
                                <?
                                if (!empty($data->remark) && is_array(json_decode($data->remark,true))) {
                                  $error_message = json_decode($data->remark,true);
                                  foreach($error_message as $v){
                                    echo '<p style="color:red;" class="form-control-static">失敗原因：' . $v . '</p>';
                                  }
                                }
                                ?>
                            </div>
                            <label>審核</label>
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
                        <!-- admin form end -->
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

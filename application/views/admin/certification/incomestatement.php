<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
<style>
    .sk-input {
        width : 100%;
    }
</style>
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
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>會員 ID</label>
                                <a class="fancyframe" href="<?= admin_url('User/display?id=' . $data->user_id) ?>">
                                    <p><?= isset($data->user_id) ? $data->user_id : "" ?></p>
                                </a>
                            </div>
                            <div class="form-group">
                                <form role="form" action="/admin/certification/sendSkbank" method="post">
                                    <table class="table table-striped table-bordered table-hover dataTable">
                                        <tbody>
                                            <tr style="text-align: center;"><td colspan="2"><span>新光百萬信保微企貸資料確認</span></td></tr>
                                            <tr hidden><td><span>徵提資料ID</span></td><td><input class="sk-input" type="text" name="id" value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td></tr>
                                            <tr><td><span>公司行業別(主計處)</span></td><td><input class="sk-input" type="text" name="CompIdustry"></td></tr>
                                            <tr><td><span>近一年結算申報書營業收入-年度</span></td><td><input class="sk-input" type="text" name="AnnualIncomeYear1"></td></tr>
                                            <tr><td><span>近一年結算申報書營業收入-營收</span></td><td><input class="sk-input" type="text" name="AnnualIncome1"></td></tr>
                                            <tr><td><span>近二年結算申報書營業收入-年度</span></td><td><input class="sk-input" type="text" name="AnnualIncomeYear2"></td></tr>
                                            <tr><td><span>近二年結算申報書營業收入-營收</span></td><td><input class="sk-input" type="text" name="AnnualIncome2"></td></tr>
                                            <tr><td><span>近三年結算申報書營業收入-年度</span></td><td><input class="sk-input" type="text" name="AnnualIncomeYear3"></td></tr>
                                            <tr><td><span>近三年結算申報書營業收入-營收</span></td><td><input class="sk-input" type="text" name="AnnualIncome3"></td></tr>
                                            <tr><td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button></td></tr>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                            <div class="form-group">
                              <? isset($ocr['url']) && !is_array($ocr['url']) ? $ocr['url'] = array($ocr['url']) : '';
                              foreach ($ocr['url'] as $key => $value) { ?>
                                  <label><a href="<?= isset($value) ? $value : ''; ?>" target="_blank">前往編輯頁面</a></label>
                              <? } ?>
                            </div>
                            <div class="form-group">
                                <label>備註</label>
                                <?
                                if ($remark) {
                                    if (isset($remark["fail"]) && $remark["fail"]) {
                                        echo '<p style="color:red;" class="form-control-static">失敗原因：' . $remark["fail"] . '</p>';
                                    }
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
                            <h1>圖片</h1>
                            <fieldset disabled>
                                <div class="form-group">
                                    <label>損益表</label><br>
                                    <? isset($content['income_statement_image']) && !is_array($content['income_statement_image']) ? $content['income_statement_image'] = array($content['income_statement_image']) : '';
                                    if(!empty($content['income_statement_image'])){
                                        foreach ($content['income_statement_image'] as $key => $value) { ?>
                                            <a href="<?= isset($value) ? $value : "" ?>" data-fancybox="images">
                                                <img src="<?= $value ? $value : "" ?>" style='width:30%;max-width:400px'>
                                            </a>
                                        <? }
                                    }?>
                                </div>
                            </fieldset>
                        </div>
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
<script>
$('select').selectize({
    sortField: 'text',
});
$(document).ready(function() {
    $.ajax({
        type: "GET",
        url: `/admin/certification/getSkbank?id=<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>`,
        dataType: "json",
        success: function (response) {
            if(response.status.code == 200 && response.response != ''){
                Object.keys(response.response).forEach(function(key) {
                    console.log(key);
                    console.log(response.response[key]);
                    if($(`[name='${key}']`).length){
                        if($(`[name='${key}']`).is("input")){
                            $(`[name='${key}']`).val(response.response[key]);
                        }else{
                            let $select = $(`[name='${key}']`).selectize();
                            let selectize = $select[0].selectize;
                            selectize.setValue(selectize.search(response.response[key]).items[0].id);
                        }
                    }
                })
            }else{
                console.log(response);
            }
        },
        error: function(error) {
          alert(error);
        }
    });
});
</script>

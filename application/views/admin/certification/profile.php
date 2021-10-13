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
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>會員 ID</label>
                                <a class="fancyframe" href="<?= admin_url('User/display?id=' . $data->user_id) ?>">
                                    <p><?= isset($data->user_id) ? $data->user_id : "" ?></p>
                                </a>
                            </div>
                            <div class="form-group">
                                <div>
                                    <ul class="nav nav-tabs" id="skbank_form_tab" role="tablist">
                                        <li role="presentation" class="active">
                                            <a href="#Pr" role="tab" data-toggle="tab" aria-expanded="true">負責人</a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#Spouse" role="tab" data-toggle="tab" aria-expanded="false">配偶</a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#GuOne" role="tab" data-toggle="tab" aria-expanded="false">保證人甲</a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#GuTwo" role="tab" data-toggle="tab" aria-expanded="false">保證人乙</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="table-responsive" id="Pr">
                                    <form role="form" action="/admin/certification/sendSkbank" method="post">
                                        <table class="table table-striped table-bordered table-hover dataTable">
                                            <tbody>
                                                <tr style="text-align: center;"><td colspan="2"><span>新光百萬信保微企貸資料確認</span></td></tr>
                                                <tr hidden><td><span>徵提資料ID</span></td><td><input class="sk-input" type="text" name="id" value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td></tr>
                                                <tr><td><span>負責人現居地址-郵遞區號</span></td><td><input class="sk-input" type="text" name="PrCurAddrZip"></td></tr>
                                                <tr><td><span>負責人現居地址-郵遞區號名稱</span></td><td><input class="sk-input" type="text" name="PrCurAddrZipName"></td></tr>
                                                <tr><td><span>負責人現居地址-非郵遞地址資料</span></td><td><input class="sk-input" type="text" name="PrCurlAddress"></td></tr>
                                                <tr><td><span>負責人連絡電話-區碼</span></td><td><input class="sk-input" type="text" name="PrTelAreaCode"></td></tr>
                                                <tr><td><span>負責人連絡電話-電話號碼</span></td><td><input class="sk-input" type="text" name="PrTelNo"></td></tr>
                                                <tr><td><span>負責人連絡電話-分機碼</span></td><td><input class="sk-input" type="text" name="PrTelExt"></td></tr>
                                                <tr><td><span>負責人連絡行動電話</span></td><td><input class="sk-input" type="text" name="PrMobileNo"></td></tr>
                                                <tr><td><span>負責人從事本行業年度</span></td><td><input class="sk-input" type="text" name="PrStartYear" placeholder="格式:YYYY"></td></tr>
                                                <tr><td><span>負責人學歷</span></td><td>
                                                    <select name="PrEduLevel" class="table-input sk-input">
                                                        <option value="A">A:國小</option>
                                                        <option value="B">B:國中</option>
                                                        <option value="C">C:高中職</option>
                                                        <option value="D">D:專科</option>
                                                        <option value="E">E:大學</option>
                                                        <option value="F">F:碩士</option>
                                                        <option value="G">G:博士</option>
                                                        <option value="H">H:無</option>
                                                    </select>
                                                </td></tr>
                                                <tr><td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button></td></tr>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                                <div class="table-responsive" id="Spouse">
                                    <form role="form" action="/admin/certification/sendSkbank" method="post">
                                        <table class="table table-striped table-bordered table-hover dataTable">
                                            <tbody>
                                                <tr style="text-align: center;"><td colspan="2"><span>新光百萬信保微企貸資料確認</span></td></tr>
                                                <tr hidden><td><span>徵提資料ID</span></td><td><input class="sk-input" type="text" name="id" value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td></tr>
                                                <tr><td><span>配偶現居地址-郵遞區號</span></td><td><input class="sk-input" type="text" name="SpouseCurAddrZip"></td></tr>
                                                <tr><td><span>配偶現居地址-郵遞區號名稱</span></td><td><input class="sk-input" type="text" name="SpouseCurAddrZipName"></td></tr>
                                                <tr><td><span>配偶現居地址-非郵遞地址資料</span></td><td><input class="sk-input" type="text" name="SpouseCurlAddress"></td></tr>
                                                <tr><td><span>配偶連絡電話-區碼</span></td><td><input class="sk-input" type="text" name="SpouseTelAreaCode"></td></tr>
                                                <tr><td><span>配偶連絡電話-電話號碼</span></td><td><input class="sk-input" type="text" name="SpouseTelNo"></td></tr>
                                                <tr><td><span>配偶連絡電話-分機碼</span></td><td><input class="sk-input" type="text" name="SpouseTelExt"></td></tr>
                                                <tr><td><span>配偶連絡行動電話</span></td><td><input class="sk-input" type="text" name="SpouseMobileNo"></td></tr>
                                                <tr><td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button></td></tr>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                                <div class="table-responsive" id="GuOne">
                                    <form role="form" action="/admin/certification/sendSkbank" method="post">
                                        <table class="table table-striped table-bordered table-hover dataTable">
                                            <tbody>
                                                <tr style="text-align: center;"><td colspan="2"><span>新光百萬信保微企貸資料確認</span></td></tr>
                                                <tr hidden><td><span>徵提資料ID</span></td><td><input class="sk-input" type="text" name="id" value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td></tr>
                                                <tr><td><span>保證人甲現居地址-郵遞區號</span></td><td><input class="sk-input" type="text" name="GuOneCurAddrZip"></td></tr>
                                                <tr><td><span>保證人甲現居地址-郵遞區號名稱</span></td><td><input class="sk-input" type="text" name="GuOneCurAddrZipName"></td></tr>
                                                <tr><td><span>保證人甲現居地址-非郵遞地址資料</span></td><td><input class="sk-input" type="text" name="GuOneCurlAddress"></td></tr>
                                                <tr><td><span>保證人甲連絡電話-區碼</span></td><td><input class="sk-input" type="text" name="GuOneTelAreaCode"></td></tr>
                                                <tr><td><span>保證人甲連絡電話-電話號碼</span></td><td><input class="sk-input" type="text" name="GuOneTelNo"></td></tr>
                                                <tr><td><span>保證人甲連絡電話-分機碼</span></td><td><input class="sk-input" type="text" name="GuOneTelExt"></td></tr>
                                                <tr><td><span>保證人甲連絡行動電話</span></td><td><input class="sk-input" type="text" name="GuOneMobileNo"></td></tr>
                                                <tr><td><span>保證人甲_任職公司</span></td><td>
                                                    <select name="GuOneCompany" class="table-input sk-input">
                                                        <option value="A">A:公家機關</option>
                                                        <option value="B">B:上市櫃公司</option>
                                                        <option value="C">C:專業人士</option>
                                                        <option value="D">D:借戶</option>
                                                        <option value="E">E:其他民營企業</option>
                                                        <option value="F">F:無</option>
                                                    </select>
                                                </td></tr>
                                                <tr><td><span>實際負責(經營)人_其他實際負責經營人_與借戶負責人之關係</span></td><td>
                                                    <select name="OthRealPrRelWithPr" class="table-input sk-input">
                                                        <option value="A">A:配偶</option>
                                                        <option value="B">B:血親</option>
                                                        <option value="C">C:姻親</option>
                                                        <option value="D">D:股東</option>
                                                        <option value="E">E:朋友</option>
                                                        <option value="F">F:本人</option>
                                                        <option value="G">G:其他</option>
                                                        <option value="H">H:與經營有關之借戶職員</option>
                                                    </select>
                                                </td></tr>
                                                <tr><td><span>實際負責(經營)人_其他實際負責經營人_從事本行業年度</span></td><td><input class="sk-input" type="text" name="OthRealPrStartYear" placeholder="格式:YYYY"></td></tr>
                                                <tr><td><span>實際負責(經營)人_其他實際負責經營人_擔任本公司職務</span></td><td><input class="sk-input" type="text" name="OthRealPrTitle"></td></tr>
                                                <tr><td><span>實際負責(經營)人_其他實際負責經營人_持股比率%</span></td><td><input class="sk-input" type="text" name="OthRealPrSHRatio"></td></tr>
                                                <tr><td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button></td></tr>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                                <div class="table-responsive" id="GuTwo">
                                    <form role="form" action="/admin/certification/sendSkbank" method="post">
                                        <table class="table table-striped table-bordered table-hover dataTable">
                                            <tbody>
                                                <tr style="text-align: center;"><td colspan="2"><span>新光百萬信保微企貸資料確認</span></td></tr>
                                                <tr hidden><td><span>徵提資料ID</span></td><td><input class="sk-input" type="text" name="id" value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td></tr>
                                                <tr><td><span>保證人乙現居地址-郵遞區號</span></td><td><input class="sk-input" type="text" name="GuTwoCurAddrZip"></td></tr>
                                                <tr><td><span>保證人乙現居地址-郵遞區號名稱</span></td><td><input class="sk-input" type="text" name="GuTwoCurAddrZipName"></td></tr>
                                                <tr><td><span>保證人乙現居地址-非郵遞地址資料</span></td><td><input class="sk-input" type="text" name="GuTwoCurlAddress"></td></tr>
                                                <tr><td><span>保證人乙連絡電話-區碼</span></td><td><input class="sk-input" type="text" name="GuTwoTelAreaCode"></td></tr>
                                                <tr><td><span>保證人乙連絡電話-電話號碼</span></td><td><input class="sk-input" type="text" name="GuTwoTelNo"></td></tr>
                                                <tr><td><span>保證人乙連絡電話-分機碼</span></td><td><input class="sk-input" type="text" name="GuTwoTelExt"></td></tr>
                                                <tr><td><span>保證人乙連絡行動電話</span></td><td><input class="sk-input" type="text" name="GuTwoMobileNo"></td></tr>
                                                <tr><td><span>保證人乙_任職公司</span></td><td>
                                                    <select name="GuTwoCompany" class="table-input sk-input">
                                                        <option value="A">A:公家機關</option>
                                                        <option value="B">B:上市櫃公司</option>
                                                        <option value="C">C:專業人士</option>
                                                        <option value="D">D:借戶</option>
                                                        <option value="E">E:其他民營企業</option>
                                                        <option value="F">F:無</option>
                                                    </select>
                                                </td></tr>
                                                <tr><td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button></td></tr>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
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
    $('#skbank_form_tab a').click(function (e) {
        let show_id = $(this).attr("href");
        $(".table-responsive").hide()
        $(show_id).show()
    })
    $( "#skbank_form_tab :first-child :first-child" ).trigger( "click" );
});
</script>

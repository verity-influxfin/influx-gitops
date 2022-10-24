<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
<style>
    .sk-input {
        width : 100%;
    }
</style>
<script type="text/javascript">
    function check_fail() {
        if ($('#status :selected').val() === '2') {
            $('#fail_div').show();
        } else {
            $('#fail_div').hide();
        }
    }

    $(document).off("change", "select#fail").on("change", "select#fail", function () {
        if ($(this).find(':selected').val() === 'other') {
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
                                <form role="form" action="/admin/certification/sendSkbank" method="post">
                                    <table class="table table-striped table-bordered table-hover dataTable">
                                        <tbody>
                                            <tr style="text-align: center;"><td colspan="2"><span>普匯微企e秒貸資料確認</span></td></tr>
                                            <tr hidden><td><span>徵提資料ID</span></td><td><input class="sk-input" type="text" name="id" value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td></tr>
                                            <tr>
                                                <td><span>企業聯絡人姓名</span></td>
                                                <td><input class="sk-input form-control" type="text" name="compContactName"></td>
                                            </tr>
                                            <tr>
                                                <td><span>企業聯絡人電話</span></td>
                                                <td><input class="sk-input form-control" type="text" name="compContactTel"></td>
                                            </tr>
                                            <tr>
                                                <td><span>企業聯絡人分機</span></td>
                                                <td><input class="sk-input form-control" type="text" name="compContactExt"></td>
                                            </tr>
                                            <tr>
                                                <td><span>企業Email</span></td>
                                                <td><input class="sk-input form-control" type="text" name="compEmail"></td>
                                            </tr>
                                            <tr>
                                                <td><span>企業財務主管姓名</span></td>
                                                <td><input class="sk-input form-control" type="text" name="financialOfficerName"></td>
                                            </tr>
                                            <tr>
                                                <td><span>企業財務主管分機</span></td>
                                                <td><input class="sk-input form-control" type="text" name="financialOfficerExt"></td>
                                            </tr>
                                            <tr>
                                                <td><span>目前員工人數</span></td>
                                                <td><input class="sk-input form-control" type="number" name="employeeNum"></td>
                                            </tr>
                                            <tr>
                                                <td><span>是否有海外投資</span></td>
                                                <td><select name="hasForeignInvestment" class="table-input sk-input form-control">
                                                        <option value="''"></option>
                                                        <option value="'1'">1:是</option>
                                                        <option value="'0'">0:否</option>
                                                    </select></td>
                                            </tr>
                                            <tr>
                                                <td><span>受嚴重特殊傳染性肺炎影響之企業</span></td>
                                                <td><select name="isCovidAffected" class="table-input sk-input form-control">
                                                        <option value="''"></option>
                                                        <option value="'1'">1:是</option>
                                                        <option value="'0'">0:否</option>
                                                    </select></td>
                                            </tr>
                                            <tr>
                                                <td><span>是否曾有信用瑕疵紀錄</span></td>
                                                <td><select name="hasCreditFlaws" class="table-input sk-input form-control">
                                                        <option value="''"></option>
                                                        <option value="'1'">1:是</option>
                                                        <option value="'0'">0:否</option>
                                                    </select></td>
                                            </tr>
                                            <tr>
                                                <td><span>近一年平均員工人數是否高過200人</span></td>
                                                <td><select name="lastOneYearOver200employees" class="table-input sk-input form-control">
                                                        <option value="''"></option>
                                                        <option value="'1'">1:是</option>
                                                        <option value="'0'">0:否</option>
                                                    </select></td>
                                            </tr>
                                            <tr>
                                                <td><span>實際營業地址是否等於營業登記地址</span></td>
                                                <td><select name="isBizAddrEqToBizRegAddr" class="table-input sk-input form-control">
                                                        <option value="''"></option>
                                                        <option value="'1'">1:是</option>
                                                        <option value="'0'">0:否</option>
                                                    </select></td>
                                            </tr>
                                            <tr>
                                                <td><span>實際營業地址</span></td>
                                                <td><input class="sk-input form-control" type="text" name="realBizAddress"></td>
                                            </tr>
                                            <tr>
                                                <td><span>營業登記地址是否為「自有」</span></td>
                                                <td><select name="realBizRegAddressOwner" class="table-input sk-input form-control">
                                                        <option value="''"></option>
                                                        <option value="'1'">1:是</option>
                                                        <option value="'0'">0:否</option>
                                                    </select></td>
                                            </tr>
                                            <tr>
                                                <td><span>營業登記地址所有權</span></td>
                                                <td><select name="bizRegAddrOwner" class="table-input sk-input form-control">
                                                        <option value="''"></option>
                                                        <option value="'A'">A:負責人</option>
                                                        <option value="'B'">B:負責人配偶</option>
                                                        <option value="'C'">C:企業</option>
                                                    </select></td>
                                            </tr>
                                            <tr>
                                                <td><span>實際登記地址是否「自有」</span></td>
                                                <td><select name="realBizAddressOwner" class="table-input sk-input form-control">
                                                        <option value="''"></option>
                                                        <option value="'1'">1:是</option>
                                                        <option value="'0'">0:否</option>
                                                    </select></td>
                                            </tr>
                                            <tr>
                                                <td><span>實際登記地址所有權</span></td>
                                                <td><select name="realBizAddrOwner" class="table-input sk-input form-control">
                                                        <option value="''"></option>
                                                        <option value="'A'">A:負責人</option>
                                                        <option value="'B'">B:負責人配偶</option>
                                                        <option value="'C'">C:企業</option>
                                                    </select></td>
                                            </tr>
                                            <tr>
                                                <td><span>是否有關係企業</span></td>
                                                <td><select name="hasRelatedCompany" class="table-input sk-input form-control">
                                                        <option value="''"></option>
                                                        <option value="'1'">1:是</option>
                                                        <option value="'0'">0:否</option>
                                                    </select></td>
                                            </tr>
                                            <tr>
                                                <td><span>關係企業(A)名稱</span></td>
                                                <td><input class="sk-input form-control" type="text" name="relatedCompAName"></td>
                                            </tr>
                                            <tr>
                                                <td><span>關係企業(A)統一編號</span></td>
                                                <td><input class="sk-input form-control" type="text" name="relatedCompAGuiNumber"></td>
                                            </tr>
                                            <tr>
                                                <td><span>關係企業(A)組織型態</span></td>
                                                <td><select name="relatedCompAType" class="table-input sk-input form-control">
                                                        <option value="''"></option>
                                                        <option value="'A'">A:獨資</option>
                                                        <option value="'B'">B:合夥</option>
                                                        <option value="'C'">C:有限公司</option>
                                                        <option value="'D'">D:股份有限公司</option>
                                                    </select></td>
                                            </tr>
                                            <tr>
                                                <td><span>關係企業(A)與申請人之關係</span></td>
                                                <td><select name="relatedCompARelationship" class="table-input sk-input form-control">
                                                        <option value="''"></option>
                                                        <option value="'A'">A:有下列關係之一(相同負責人、負責人互為配偶、負責人互為二親等內血親)</option>
                                                        <option value="'B'">B:相同股東出資額均>=40%</option>
                                                        <option value="'C'">C:轉投資之投資額>=40%</option>
                                                        <option value="'D'">D:營業場所相同</option>
                                                        <option value="'E'">E:營業場所有租賃關係</option>
                                                        <option value="'F'">F:相同總經理</option>
                                                        <option value="'G'">G:相同財務主管</option>
                                                        <option value="'H'">H:其他</option>
                                                    </select></td>
                                            </tr>
                                            <tr>
                                                <td><span>關係企業(B)名稱</span></td>
                                                <td><input class="sk-input form-control" type="text" name="relatedCompBGuiNumber"></td>
                                            </tr>
                                            <tr>
                                                <td><span>關係企業(B)統一編號</span></td>
                                                <td><input class="sk-input form-control" type="text" name="relatedCompBName"></td>
                                            </tr>
                                            <tr>
                                                <td><span>關係企業(B)組織型態</span></td>
                                                <td><select name="relatedCompBType" class="table-input sk-input form-control">
                                                        <option value="''"></option>
                                                        <option value="'A'">A:獨資</option>
                                                        <option value="'B'">B:合夥</option>
                                                        <option value="'C'">C:有限公司</option>
                                                        <option value="'D'">D:股份有限公司</option>
                                                    </select></td>
                                            </tr>
                                            <tr>
                                                <td><span>關係企業(B)與申請人之關係</span></td>
                                                <td><select name="relatedCompBRelationship" class="table-input sk-input form-control">
                                                        <option value="''"></option>
                                                        <option value="'A'">A:有下列關係之一(相同負責人、負責人互為配偶、負責人互為二親等內血親)</option>
                                                        <option value="'B'">B:相同股東出資額均>=40%</option>
                                                        <option value="'C'">C:轉投資之投資額>=40%</option>
                                                        <option value="'D'">D:營業場所相同</option>
                                                        <option value="'E'">E:營業場所有租賃關係</option>
                                                        <option value="'F'">F:相同總經理</option>
                                                        <option value="'G'">G:相同財務主管</option>
                                                        <option value="'H'">H:其他</option>
                                                    </select></td>
                                            </tr>
                                            <tr>
                                                <td><span>關係企業(C)名稱</span></td>
                                                <td><input class="sk-input form-control" type="text" name="relatedCompCName"></td>
                                            </tr>
                                            <tr>
                                                <td><span>關係企業(C)統一編號</span></td>
                                                <td><input class="sk-input form-control" type="text" name="relatedCompCGuiNumber"></td>
                                            </tr>
                                            <tr>
                                                <td><span>關係企業(C)組織型態</span></td>
                                                <td><select name="relatedCompCType" class="table-input sk-input form-control">
                                                        <option value="''"></option>
                                                        <option value="'A'">A:獨資</option>
                                                        <option value="'B'">B:合夥</option>
                                                        <option value="'C'">C:有限公司</option>
                                                        <option value="'D'">D:股份有限公司</option>
                                                    </select></td>
                                            </tr>
                                            <tr>
                                                <td><span>關係企業(C)與申請人之關係</span></td>
                                                <td><select name="relatedCompCRelationship" class="table-input sk-input form-control">
                                                        <option value="''"></option>
                                                        <option value="'A'">A:有下列關係之一(相同負責人、負責人互為配偶、負責人互為二親等內血親)</option>
                                                        <option value="'B'">B:相同股東出資額均>=40%</option>
                                                        <option value="'C'">C:轉投資之投資額>=40%</option>
                                                        <option value="'D'">D:營業場所相同</option>
                                                        <option value="'E'">E:營業場所有租賃關係</option>
                                                        <option value="'F'">F:相同總經理</option>
                                                        <option value="'G'">G:相同財務主管</option>
                                                        <option value="'H'">H:其他</option>
                                                    </select></td>
                                            </tr>
                                            <tr><td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button></td></tr>
                                        </tbody>
                                    </table>
                                </form>
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
                            <form role="form" method="post" action="/admin/certification/user_certification_edit">
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
                            <fieldset>
                                <div class="form-group">
                                    <label>土地所有權狀</label><br>
                                    <? isset($content['BizLandOwnership']) && !is_array($content['BizLandOwnership']) ? $content['BizLandOwnership'] = array($content['BizLandOwnership']) : '';
                                        if(!empty($content['BizLandOwnership'])){
                                            foreach ($content['BizLandOwnership'] as $key => $value) { ?>
                                                <a href="<?= isset($value) ? $value : "" ?>" data-fancybox="images">
                                                    <img src="<?= $value ? $value : "" ?>" style='width:30%;max-width:400px'>
                                                </a><br>
                                            <? }
                                        }?>
                                    <hr/>
                                    <label>建物所有權狀</label><br>
                                    <? isset($content['BizHouseOwnership']) && !is_array($content['BizHouseOwnership']) ? $content['BizHouseOwnership'] = array($content['BizHouseOwnership']) : '';
                                    if(!empty($content['BizHouseOwnership'])){
                                        foreach ($content['BizHouseOwnership'] as $key => $value) { ?>
                                            <a href="<?= isset($value) ? $value : "" ?>" data-fancybox="images">
                                                <img src="<?= $value ? $value : "" ?>" style='width:30%;max-width:400px'>
                                            </a><br>
                                        <?}
                                    }?>
                                    <hr/>
                                    <label>實際土地所有權狀</label><br>
                                    <? isset($content['RealLandOwnership']) && !is_array($content['RealLandOwnership']) ? $content['RealLandOwnership'] = array($content['RealLandOwnership']) : '';
                                    if(!empty($content['RealLandOwnership'])){
                                        foreach ($content['RealLandOwnership'] as $key => $value) { ?>
                                            <a href="<?= isset($value) ? $value : "" ?>" data-fancybox="images">
                                                <img src="<?= $value ? $value : "" ?>" style='width:30%;max-width:400px'>
                                            </a><br>
                                        <? }
                                    }?>
                                    <hr/>
                                    <label>實際建物所有權狀</label><br>
                                    <? isset($content['RealHouseOwnership']) && !is_array($content['RealHouseOwnership']) ? $content['RealHouseOwnership'] = array($content['RealHouseOwnership']) : '';
                                    if(!empty($content['RealHouseOwnership'])){
                                        foreach ($content['RealHouseOwnership'] as $key => $value) { ?>
                                            <a href="<?= isset($value) ? $value : "" ?>" data-fancybox="images">
                                                <img src="<?= $value ? $value : "" ?>" style='width:30%;max-width:400px'>
                                            </a><br>
                                        <? }
                                    }?>
                                    <hr/>
                                    <label>其它</label><br>
                                    <?php
                                    if ( ! empty($content['other_image']) && is_array($content['other_image']))
                                    {
                                        foreach ($content['other_image'] as $value)
                                        { ?>
                                            <a href="<?= $value ?>" data-fancybox="images">
                                                <img src="<?= $value ?>"
                                                     style='width:30%;max-width:400px'>
                                            </a>
                                        <?php }
                                    } ?>
                                    <hr/>
                                    <label></label><br>
                                    <?php
                                    if ( ! empty($content['pdf']) && is_array($content['pdf']))
                                    {
                                        $index = 0;
                                        foreach ($content['pdf'] as $value)
                                        { ?>
                                            <a href="<?= $value ?>" class="btn btn-info">
                                                檔案<?= ++$index; ?>
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
<script>
$(document).ready(function() {
    $('select').selectize({
        sortField: 'text',
    });

    $.ajax({
        type: "GET",
        url: `/admin/certification/getSkbank?id=<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>`,
        dataType: "json",
        success: function (response) {
            if(response.status.code == 200 && response.response != ''){
                Object.keys(response.response).forEach(function(key) {
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
            }
        },
        error: function(error) {
          alert(error);
        }
    });
});
</script>

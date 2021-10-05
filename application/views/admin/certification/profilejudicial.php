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
                                            <tr><td><span>公司產業別</span></td><td><select name="CompDuType" class="table-input sk-input">
                                                <option value="01">01:水泥</option>
                                                <option value="02">02:食品</option>
                                                <option value="03">03:塑膠</option>
                                                <option value="06">06:電器</option>
                                                <option value="07">07:化學</option>
                                                <option value="08">08:玻璃(陶瓷)</option>
                                                <option value="09">09:通訊</option>
                                                <option value="10">10:鋼鐵</option>
                                                <option value="11">11:橡膠</option>
                                                <option value="12">12:汽車</option>
                                                <option value="13">13:服務業</option>
                                                <option value="14">14:飯店百貨</option>
                                                <option value="17">17:DRAM 製造</option>
                                                <option value="18">18:DRAM 模組</option>
                                                <option value="19">19:IC 設計</option>
                                                <option value="20">20:晶圓代工</option>
                                                <option value="21">21:IC 封測</option>
                                                <option value="22">22:TFT-LCD</option>
                                                <option value="23">23:主機板</option>
                                                <option value="24">24:光碟片</option>
                                                <option value="25">25:光碟機</option>
                                                <option value="26">26:網路通訊</option>
                                                <option value="27">27:連接器</option>
                                                <option value="28">28:伺服器</option>
                                                <option value="29">29:軟體業</option>
                                                <option value="30">30:掃描器</option>
                                                <option value="31">31:印表機</option>
                                                <option value="32">32:機殼業</option>
                                                <option value="33">33:手機業</option>
                                                <option value="34">34:電腦組裝</option>
                                                <option value="35">35:電腦週邊</option>
                                                <option value="36">36:筆記型電腦</option>
                                                <option value="37">37:顯示(監視)器</option>
                                                <option value="38">38:印刷電路板</option>
                                                <option value="39">39:被動元件</option>
                                                <option value="40">40:數位相機</option>
                                                <option value="41">41:電源供應器</option>
                                                <option value="42">42:LED</option>
                                                <option value="43">43:工業電腦</option>
                                                <option value="44">44:IC 通路</option>
                                                <option value="45">45:資訊(3C)通路</option>
                                                <option value="46">46:安全監控</option>
                                                <option value="47">47:FLASH(模組)</option>
                                                <option value="48">48:觸控面板</option>
                                                <option value="49">49:散熱模組</option>
                                                <option value="50">50:背光模組</option>
                                                <option value="51">51:電池模組</option>
                                                <option value="52">52:農業</option>
                                                <option value="53">53:林業</option>
                                                <option value="54">54:漁業</option>
                                                <option value="55">55:畜牧業</option>
                                                <option value="56">56:大宗物資業</option>
                                                <option value="57">57:人造纖維業</option>
                                                <option value="58">58:紡紗業</option>
                                                <option value="59">59:織布業</option>
                                                <option value="60">60:成衣業</option>
                                                <option value="61">61:皮革皮毛業</option>
                                                <option value="62">62:染整業</option>
                                                <option value="63">63:電線電纜業</option>
                                                <option value="64">64:機電機械業</option>
                                                <option value="65">65:工具機</option>
                                                <option value="66">66:非鐵金屬業</option>
                                                <option value="67">67:海運</option>
                                                <option value="68">68:空運</option>
                                                <option value="69">69:陸運</option>
                                                <option value="70">70:倉儲物流業</option>
                                                <option value="71">71:建設(開發)</option>
                                                <option value="72">72:營造(工程)</option>
                                                <option value="73">73:建材業</option>
                                                <option value="74">74:金控(銀行)</option>
                                                <option value="75">75:保險</option>
                                                <option value="76">76:證券</option>
                                                <option value="77">77:投資公司</option>
                                                <option value="78">78:其他金融業</option>
                                                <option value="79">79:太陽能</option>
                                                <option value="80">80:自行車</option>
                                                <option value="81">81:生技醫療</option>
                                                <option value="82">82:鐘錶眼鏡業</option>
                                                <option value="83">83:影音通路</option>
                                                <option value="84">84:電信業</option>
                                                <option value="85">85:印刷業</option>
                                                <option value="86">86:出版業</option>
                                                <option value="87">87:製鞋業</option>
                                                <option value="88">88:油電燃氣業</option>
                                                <option value="89">89:有線電視</option>
                                                <option value="90">90:機車業</option>
                                                <option value="91">91:運動用品業</option>
                                                <option value="92">92:餐飲業</option>
                                                <option value="93">93:觀光旅遊業</option>
                                                <option value="94">94:資源回收業</option>
                                                <option value="95">95:量販、超市、便利商店</option>
                                                <option value="96">96:輪胎業</option>
                                                <option value="97">97:休閒娛樂</option>
                                                <option value="98">98:家具業</option>
                                                <option value="99">99:其他</option>
                                            </select></td></tr>
                                            <tr><td><span>營業種類</span></td><td><select name="BusinessType" class="table-input sk-input">
                                                <option value="A">A:製造</option>
                                                <option value="B">B:買賣</option>
                                                <option value="C">C:其他</option>
                                            </select></td></tr>
                                            <tr><td><span>公司主要營業場所-郵遞區號</span></td><td><input class="sk-input" type="text" name="CompMajorAddrZip"></td></tr>
                                            <tr><td><span>公司主要營業場所-郵遞區號名稱</span></td><td><input class="sk-input" type="text" name="CompMajorAddrZipName"></td></tr>
                                            <tr><td><span>公司主要營業場所-非郵遞地址資料</span></td><td><input class="sk-input" type="text" name="CompMajorAddress"></td></tr>
                                            <tr><td><span>公司連絡電話-區碼</span></td><td><input class="sk-input" type="text" name="CompTelAreaCode"></td></tr>
                                            <tr><td><span>公司連絡電話-電話號碼</span></td><td><input class="sk-input" type="text" name="CompTelNo"></td></tr>
                                            <tr><td><span>公司連絡電話-分機碼</span></td><td><input class="sk-input" type="text" name="CompTelExt"></td></tr>
                                            <tr><td><span>營業登記地址</span></td><td><select name="IsBizRegAddrSelfOwn" class="table-input sk-input">
                                                <option value="1">1:自有</option>
                                                <option value="0">0:非自有</option>
                                            </select></td></tr>
                                            <tr><td><span>營業登記地址_自有</span></td><td><select name="BizRegAddrOwner" class="table-input sk-input">
                                                <option value="A">A:企業</option>
                                                <option value="B">B:負責人</option>
                                                <option value="C">C:負責人配偶</option>
                                            </select></td></tr>
                                            <tr><td><span>實際營業地址_是OR否 同營業登記地址</span></td><td><select name="IsBizAddrEqToBizRegAddr" class="table-input sk-input">
                                                <option value="1">1:同營業登記地址</option>
                                                <option value="0">0:不同於營業登記地址</option>
                                            </select></td></tr>
                                            <tr><td><span>實際營業地址_選擇縣市</span></td><td><input class="sk-input" type="text" name="RealBizAddrCityName"></td></tr>
                                            <tr><td><span>實際營業地址_選擇鄉鎮市區</span></td><td><input class="sk-input" type="text" name="RealBizAddrAreaName"></td></tr>
                                            <tr><td><span>實際營業地址_路街名稱(不含路、街)</span></td><td><input class="sk-input" type="text" name="RealBizAddrRoadName"></td></tr>
                                            <tr><td><span>實際營業地址_路 OR 街</span></td><td><input class="sk-input" type="text" name="RealBizAddrRoadType"></td></tr>
                                            <tr><td><span>實際營業地址_段</span></td><td><input class="sk-input" type="text" name="RealBizAddrSec"></td></tr>
                                            <tr><td><span>實際營業地址_巷</span></td><td><input class="sk-input" type="text" name="RealBizAddrLn"></td></tr>
                                            <tr><td><span>實際營業地址_弄</span></td><td><input class="sk-input" type="text" name="RealBizAddrAly"></td></tr>
                                            <tr><td><span>實際營業地址_號(不含之號)</span></td><td><input class="sk-input" type="text" name="RealBizAddrNo"></td></tr>
                                            <tr><td><span>實際營業地址_之號</span></td><td><input class="sk-input" type="text" name="RealBizAddrNoExt"></td></tr>
                                            <tr><td><span>實際營業地址_樓(不含之樓、室)</span></td><td><input class="sk-input" type="text" name="RealBizAddrFloor"></td></tr>
                                            <tr><td><span>實際營業地址_之樓</span></td><td><input class="sk-input" type="text" name="RealBizAddrFloorExt"></td></tr>
                                            <tr><td><span>實際營業地址_室</span></td><td><input class="sk-input" type="text" name="RealBizAddrRoom"></td></tr>
                                            <tr><td><span>實際營業地址_其他備註</span></td><td><input class="sk-input" type="text" name="RealBizAddrOtherMemo"></td></tr>
                                            <tr><td><span>實際營業地址</span></td><td><select name="IsRealBizAddrSelfOwn" class="table-input sk-input">
                                                <option value="1">1:自有</option>
                                                <option value="0">0:非自有</option>
                                            </select></td></tr>
                                            <tr><td><span>實際營業地址_自有</span></td><td><select name="RealBizAddrOwner" class="table-input sk-input">
                                                <option value="A">A:企業</option>
                                                <option value="B">B:負責人</option>
                                                <option value="C">C:負責人配偶</option>
                                            </select></td></tr>
                                            <tr><td><span>主要營業場所建號-縣市名</span></td><td><input class="sk-input" type="text" name="CompMajorCityName"></td></tr>
                                            <tr><td><span>主要營業場所建號-地區</span></td><td><input class="sk-input" type="text" name="CompMajorAreaName"></td></tr>
                                            <tr><td><span>主要營業場所建號-段名</span></td><td><input class="sk-input" type="text" name="CompMajorSecName"></td></tr>
                                            <tr><td><span>主要營業場所建號-段號</span></td><td><input class="sk-input" type="text" name="CompMajorSecNo"></td></tr>
                                            <tr><td><span>主要營業場所所有權</span></td><td><select name="CompMajorOwnership" class="table-input sk-input">
                                                <option value="A">A:公司</option>
                                                <option value="B">B:負責人</option>
                                                <option value="C">C:配偶</option>
                              				    <option value="D">D:甲保證人</option>
                              				    <option value="E">E:乙保證人</option>
                                            </select></td></tr>
                                            <tr><td><span>營業場所設定</span></td><td><select name="CompMajorSetting" class="table-input sk-input">
                                                <option value="A">A:無設定</option>
                                                <option value="B">B:第一順位新光</option>
                                                <option value="C">C:第一順位 非新光</option>
                                            </select></td></tr>
                                            <tr><td><span>營業稅申報方式</span></td><td><select name="BizTaxFileWay" class="table-input sk-input">
                                                <option value="A">A:使用統一發票</option>
                                                <option value="B">B:免用統一發票核定繳納營業稅</option>
                                                <option value="C">C:未達課稅起徵點</option>
                                                <option value="D">D:免徵營業稅或執行業務</option>
                                            </select></td></tr>
                                            <tr><td><span>員工人數</span></td><td><input class="sk-input" type="text" name="EmployeeNum"></td></tr>
                                            <tr><td><span>股東人數</span></td><td><input class="sk-input" type="text" name="ShareholderNum"></td></tr>
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
                                    <label>土地所有權狀</label><br>
                                    <? isset($content['BizLandOwnership']) && !is_array($content['BizLandOwnership']) ? $content['BizLandOwnership'] = array($content['BizLandOwnership']) : '';
                                        if(!empty($content['BizLandOwnership'])){
                                            foreach ($content['BizLandOwnership'] as $key => $value) { ?>
                                                <a href="<?= isset($value) ? $value : "" ?>" data-fancybox="images">
                                                    <img src="<?= $value ? $value : "" ?>" style='width:30%;max-width:400px'>
                                                </a>
                                            <? }
                                        }?>
                                    <label>建物所有權狀</label><br>
                                    <? isset($content['BizHouseOwnership']) && !is_array($content['BizHouseOwnership']) ? $content['BizHouseOwnership'] = array($content['BizHouseOwnership']) : '';
                                    if(!empty($content['BizHouseOwnership'])){
                                        foreach ($content['BizHouseOwnership'] as $key => $value) { ?>
                                            <a href="<?= isset($value) ? $value : "" ?>" data-fancybox="images">
                                                <img src="<?= $value ? $value : "" ?>" style='width:30%;max-width:400px'>
                                            </a>
                                        <?}
                                    }?>
                                    <label>實際土地所有權狀</label><br>
                                    <? isset($content['RealLandOwnership']) && !is_array($content['RealLandOwnership']) ? $content['RealLandOwnership'] = array($content['RealLandOwnership']) : '';
                                    if(!empty($content['RealLandOwnership'])){
                                        foreach ($content['RealLandOwnership'] as $key => $value) { ?>
                                            <a href="<?= isset($value) ? $value : "" ?>" data-fancybox="images">
                                                <img src="<?= $value ? $value : "" ?>" style='width:30%;max-width:400px'>
                                            </a>
                                        <? }
                                    }?>
                                    <label>實際建物所有權狀</label><br>
                                    <? isset($content['RealHouseOwnership']) && !is_array($content['RealHouseOwnership']) ? $content['RealHouseOwnership'] = array($content['RealHouseOwnership']) : '';
                                    if(!empty($content['RealHouseOwnership'])){
                                        foreach ($content['RealHouseOwnership'] as $key => $value) { ?>
                                            <a href="<?= isset($value) ? $value : "" ?>" data-fancybox="images">
                                                <img src="<?= $value ? $value : "" ?>" style='width:30%;max-width:400px'>
                                            </a>
                                        <? }
                                    }?>
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

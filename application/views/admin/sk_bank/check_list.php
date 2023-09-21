<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/admin/css/credit_table/A4_check.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.10.3/sweetalert2.js" type="text/javascript"></script>
    <!-- special select js, css-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
    <script data-pace-options='{ "ajax": true }' src="/assets/admin/scripts/pace.js"></script>
    <title>檢核表</title>
    <style>
        .pointer {
            cursor: pointer;
        }
    </style>
</head>

<body style="background: rgb(204,204,204); font-size: 14px;">
<div class="container mt-2">
    <div>
        <table>
            <tbody>
            <tr>
                <td>交易序號</td>
                <td>案件編號</td>
                <td>送出結果</td>
                <td>回應內容</td>
                <td>操作人員</td>
            </tr>
            <tr>
                <td><input id="msg_no" class="form-control" disabled></td>
                <td><input id="case_no" class="form-control" disabled></td>
                <td><input id="send_success" class="form-control" disabled></td>
                <td><input id="return_msg" class="form-control" style="min-width: 300px;" disabled></td>
                <td><input id="action_user" class="form-control" disabled></td>
            </tr>
            </tbody>
        </table>
    </div>
    <ul class="nav nav-tabs mt-2">
        <li class="nav-item pointer" onclick="changeTab('tab-skbank')">
            <a class="nav-link" id="nav-tab-skbank" aria-current="page">新光</a>
        </li>
    </ul>
    <div id="page-tab-skbank" class="nav-page" data-bankid="1">
        <div class="page">
            <div class="subpage api_data_page">
                <h3 style="text-align: center;">檢核表</h3>
                <div>
                    <table class="table table-bordered border-dark">
                        <tbody>
                        <tr>
                            <th class="source th bold-bottom-border bold-right-border">資料來源</th>
                            <th class="field_name th bold-bottom-border bold-right-border">欄位名稱<span style="color: red; font-size: 5px;">( * 為必填欄位 )</span></th>
                            <th colspan="3" class="content th bold-bottom-border">內容</th>
                            <th class="edit th bold-bottom-border">人工檢驗</th>
                        </tr>
                        <tr>
                            <td class="title input-center bold-bottom-border bold-right-border" rowspan="31">
                                A-工商登記<br>(經濟部API)<br>(主計處)
                            </td>
                            <td class="bold-right-border">公司統一編號<span style="color: red;">*</span></td>
                            <td colspan="3">
                                <input id="CompId_content" name="CompId_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('CompId_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司戶名<span style="color: red;">*</span></td>
                            <td colspan="3">
                                <input id="CompName_content" name="CompName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('CompName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>公司核准設立日期<span style="color: red;">*</span></div>
                                <div style="color:red;">格式:YYYYMMDD</div>
                            </td>
                            <td colspan="3">
                                <input id="CompSetDate_content" name="CompSetDate_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('CompSetDate_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司實收資本額<span style="color: red;">*</span></td>
                            <td colspan="3">
                                <input id="CompCapital_content" name="CompCapital_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('CompCapital_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司行業別(主計處)<span style="color: red;">*</span></td>
                            <td colspan="3">
                                <input id="CompIdustry_content" name="CompIdustry_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('CompIdustry_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司型態<span style="color: red;">*</span></td>
                            <td colspan="3">
                                <select name="CompType_content" class="table-input" id="CompType_content" disabled>
                                    <option value="" selected></option>
                                    <option value="41">41:獨資</option>
                                    <option value="21">21:中小企業</option>
                                </select>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('CompType_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司產業別<span style="color: red;">*</span></td>
                            <td colspan="3">
                                <select name="compDuType_content" class="table-input" id="compDuType_content" disabled>
                                    <option value="" selected></option>
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
                                </select>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('compDuType_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">營業種類</td>
                            <td colspan="3">
                                <select name="BusinessType_content" class="table-input" id="BusinessType_content" disabled>
                                    <option value=""></option>
                                    <option value="A">A:製造</option>
                                    <option value="B">B:買賣</option>
                                    <option value="C">C:其他</option>
                                </select>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('BusinessType_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司登記地址-郵遞區號<span style="color: red;">*</span></td>
                            <td colspan="3">
                                <input id="CompRegAddrZip_content" name="CompRegAddrZip_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('CompRegAddrZip_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司登記地址-郵遞區號名稱<span style="color: red;">*</span></td>
                            <td colspan="3">
                                <input id="CompRegAddrZipName_content" name="CompRegAddrZipName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('CompRegAddrZipName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司登記地址-非郵遞地址資料<span style="color: red;">*</span></td>
                            <td colspan="3">
                                <input id="CompRegAddress_content" name="CompRegAddress_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('CompRegAddress_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>現任負責人擔任公司起日-日期</div>
                                <div style="color:red;">格式:YYYYMMDD</div>
                            </td>
                            <td colspan="3">
                                <input id="PrOnboardDay_content" name="PrOnboardDay_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrOnboardDay_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">現任負責人擔任公司起日-姓名</td>
                            <td colspan="3">
                                <input id="PrOnboardName_content" name="PrOnboardName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrOnboardName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>前任負責人擔任公司起日-日期</div>
                                <div style="color:red;">格式:YYYYMMDD</div>
                            </td>
                            <td colspan="3">
                                <input id="ExPrOnboardDay_content" name="ExPrOnboardDay_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('ExPrOnboardDay_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">前任負責人擔任公司起日-姓名</td>
                            <td colspan="3">
                                <input id="ExPrOnboardName_content" name="ExPrOnboardName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('ExPrOnboardName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>前二任負責人擔任公司起日-日期</div>
                                <div style="color:red;">格式:YYYYMMDD</div>
                            </td>
                            <td colspan="3">
                                <input id="ExPrOnboardDay2_content" name="ExPrOnboardDay2_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('ExPrOnboardDay2_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">前二任負責人擔任公司起日-姓名</td>
                            <td colspan="3">
                                <input id="ExPrOnboardName2_content" name="ExPrOnboardName2_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('ExPrOnboardName2_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">營業登記地址_選擇縣市</td>
                            <td colspan="3">
                                <input id="BizRegAddrCityName_content" name="BizRegAddrCityName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('BizRegAddrCityName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">營業登記地址_選擇鄉鎮市區</td>
                            <td colspan="3">
                                <input id="BizRegAddrAreaName_content" name="BizRegAddrAreaName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('BizRegAddrAreaName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">營業登記地址_路街名稱(不含路、街)</td>
                            <td colspan="3">
                                <input id="BizRegAddrRoadName_content" name="BizRegAddrRoadName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('BizRegAddrRoadName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">營業登記地址_路 OR 街</td>
                            <td colspan="3">
                                <input id="BizRegAddrRoadType_content" name="BizRegAddrRoadType_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('BizRegAddrRoadType_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">營業登記地址_段</td>
                            <td colspan="3">
                                <input id="BizRegAddrSec_content" name="BizRegAddrSec_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('BizRegAddrSec_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">營業登記地址_巷</td>
                            <td colspan="3">
                                <input id="BizRegAddrLn_content" name="BizRegAddrLn_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('BizRegAddrLn_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">營業登記地址_弄</td>
                            <td colspan="3">
                                <input id="BizRegAddrAly_content" name="BizRegAddrAly_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('BizRegAddrAly_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">營業登記地址_號(不含之號)</td>
                            <td colspan="3">
                                <input id="BizRegAddrNo_content" name="BizRegAddrNo_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('BizRegAddrNo_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">營業登記地址_之號</td>
                            <td colspan="3">
                                <input id="BizRegAddrNoExt_content" name="BizRegAddrNoExt_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('BizRegAddrNoExt_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">營業登記地址_樓(不含之樓、室)</td>
                            <td colspan="3">
                                <input id="BizRegAddrFloor_content" name="BizRegAddrFloor_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('BizRegAddrFloor_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">營業登記地址_之樓</td>
                            <td colspan="3">
                                <input id="BizRegAddrFloorExt_content" name="BizRegAddrFloorExt_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('BizRegAddrFloorExt_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">營業登記地址_室</td>
                            <td colspan="3">
                                <input id="BizRegAddrRoom_content" name="BizRegAddrRoom_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('BizRegAddrRoom_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">營業登記地址_其他備註</td>
                            <td colspan="3">
                                <input id="BizRegAddrOtherMemo_content" name="BizRegAddrOtherMemo_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('BizRegAddrOtherMemo_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>公司最後核准變更實收資本額日期</div>
                                <div style="color:red;">格式:YYYYMMDD</div>
                            </td>
                            <td colspan="3">
                                <input id="LastPaidInCapitalDate_content" name="LastPaidInCapitalDate_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('LastPaidInCapitalDate_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="title input-center bold-bottom-border bold-right-border" rowspan="47">
                                B-企業資料表
                            </td>
                            <td class="bold-right-border">公司主要營業場所-郵遞區號<span style="color: red;">*</span></td>
                            <td colspan="3">
                                <input id="CompMajorAddrZip_content" name="CompMajorAddrZip_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('CompMajorAddrZip_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司主要營業場所-郵遞區號名稱<span style="color: red;">*</span></td>
                            <td colspan="3">
                                <input id="CompMajorAddrZipName_content" name="CompMajorAddrZipName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('CompMajorAddrZipName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司主要營業場所-非郵遞地址資料<span style="color: red;">*</span></td>
                            <td colspan="3">
                                <input id="CompMajorAddress_content" name="CompMajorAddress_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('CompMajorAddress_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司連絡電話-區碼<span style="color: red;">*</span></td>
                            <td colspan="3">
                                <input id="CompTelAreaCode_content" name="CompTelAreaCode_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('CompTelAreaCode_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司連絡電話-電話號碼<span style="color: red;">*</span></td>
                            <td colspan="3">
                                <input id="compContactTel_content" name="compContactTel_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('compContactTel_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司連絡電話-分機碼</td>
                            <td colspan="3">
                                <input id="compContactExt_content" name="compContactExt_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('compContactExt_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">營業登記地址</td>
                            <td colspan="3">
                                <select name="realBizRegAddressOwner_content" class="table-input" id="realBizRegAddressOwner_content" disabled>
                                    <option value="" selected></option>
                                    <option value="1">1:自有</option>
                                    <option value="0">0:非自有</option>
                                </select>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('realBizRegAddressOwner_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">營業登記地址_自有</td>
                            <td colspan="3">
                                <select name="bizRegAddrOwner_content" class="table-input" id="bizRegAddrOwner_content" disabled>
                                    <option value="" selected></option>
                                    <option value="A">A:負責人</option>
                                    <option value="B">B:負責人配偶</option>
                                    <option value="C">C:企業</option>
                                </select>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('bizRegAddrOwner_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">實際營業地址_是OR否 同營業登記地址</td>
                            <td colspan="3">
                                <select name="isBizAddrEqToBizRegAddr_content" class="table-input" id="isBizAddrEqToBizRegAddr_content" disabled>
                                    <option value="" selected></option>
                                    <option value="1">1:同營業登記地址</option>
                                    <option value="0">0:不同於營業登記地址</option>
                                </select>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('isBizAddrEqToBizRegAddr_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">實際營業地址_選擇縣市</td>
                            <td colspan="3">
                                <input id="RealBizAddrCityName_content" name="RealBizAddrCityName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('RealBizAddrCityName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">實際營業地址_選擇鄉鎮市區</td>
                            <td colspan="3">
                                <input id="RealBizAddrAreaName_content" name="RealBizAddrAreaName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('RealBizAddrAreaName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">實際營業地址_路街名稱(不含路、街)</td>
                            <td colspan="3">
                                <input id="RealBizAddrRoadName_content" name="RealBizAddrRoadName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('RealBizAddrRoadName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">實際營業地址_路 OR 街</td>
                            <td colspan="3">
                                <input id="RealBizAddrRoadType_content" name="RealBizAddrRoadType_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('RealBizAddrRoadType_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">實際營業地址_段</td>
                            <td colspan="3">
                                <input id="RealBizAddrSec_content" name="RealBizAddrSec_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('RealBizAddrSec_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">實際營業地址_巷</td>
                            <td colspan="3">
                                <input id="RealBizAddrLn_content" name="RealBizAddrLn_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('RealBizAddrLn_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">實際營業地址_弄</td>
                            <td colspan="3">
                                <input id="RealBizAddrAly_content" name="RealBizAddrAly_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('RealBizAddrAly_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">實際營業地址_號(不含之號)</td>
                            <td colspan="3">
                                <input id="RealBizAddrNo_content" name="RealBizAddrNo_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('RealBizAddrNo_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">實際營業地址_之號</td>
                            <td colspan="3">
                                <input id="RealBizAddrNoExt_content" name="RealBizAddrNoExt_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('RealBizAddrNoExt_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">實際營業地址_樓(不含之樓、室)</td>
                            <td colspan="3">
                                <input id="RealBizAddrFloor_content" name="RealBizAddrFloor_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('RealBizAddrFloor_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">實際營業地址_之樓</td>
                            <td colspan="3">
                                <input id="RealBizAddrFloorExt_content" name="RealBizAddrFloorExt_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('RealBizAddrFloorExt_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">實際營業地址_室</td>
                            <td colspan="3">
                                <input id="RealBizAddrRoom_content" name="RealBizAddrRoom_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('RealBizAddrRoom_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">實際營業地址_其他備註</td>
                            <td colspan="3">
                                <input id="RealBizAddrOtherMemo_content" name="RealBizAddrOtherMemo_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('RealBizAddrOtherMemo_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">實際營業地址</td>
                            <td colspan="3">
                                <select name="realBizAddressOwner_content" class="table-input" id="realBizAddressOwner_content" disabled>
                                    <option value="" selected></option>
                                    <option value="1">1:自有</option>
                                    <option value="0">0:非自有</option>
                                </select>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('realBizAddressOwner_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">實際營業地址_自有</td>
                            <td colspan="3">
                                <select name="realBizAddrOwner_content" class="table-input" id="realBizAddrOwner_content" disabled>
                                    <option value="" selected></option>
                                    <option value="A">A:負責人</option>
                                    <option value="B">B:負責人配偶</option>
                                    <option value="C">C:企業</option>
                                </select>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('realBizAddrOwner_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">主要營業場所建號-縣市名</td>
                            <td colspan="3">
                                <input id="CompMajorCityName_content" name="CompMajorCityName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('CompMajorCityName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">主要營業場所建號-地區</td>
                            <td colspan="3">
                                <input id="CompMajorAreaName_content" name="CompMajorAreaName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('CompMajorAreaName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">主要營業場所建號-段名</td>
                            <td colspan="3">
                                <input id="CompMajorSecName_content" name="CompMajorSecName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('CompMajorSecName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">主要營業場所建號-段號</td>
                            <td colspan="3">
                                <input id="CompMajorSecNo_content" name="CompMajorSecNo_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('CompMajorSecNo_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">主要營業場所所有權</td>
                            <td colspan="3">
                                <select name="CompMajorOwnership_content" class="table-input" id="CompMajorOwnership_content" disabled>
                                    <option value="" selected></option>
                                    <option value="A">A:公司</option>
                                    <option value="B">B:負責人</option>
                                    <option value="C">C:配偶</option>
                                    <option value="D">D:甲保證人</option>
                                    <option value="E">E:乙保證人</option>
                                </select>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('CompMajorOwnership_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">營業場所設定</td>
                            <td colspan="3">
                                <select name="CompMajorSetting_content" class="table-input" id="CompMajorSetting_content" disabled>
                                    <option value="" selected></option>
                                    <option value="A">A:無設定</option>
                                    <option value="B">B:第一順位新光</option>
                                    <option value="C">C:第一順位非新光</option>
                                </select>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('CompMajorSetting_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">營業稅申報方式</td>
                            <td colspan="3">
                                <select name="BizTaxFileWay_content" class="table-input" id="BizTaxFileWay_content" disabled>
                                    <option value="" selected></option>
                                    <option value="A">A:使用統一發票</option>
                                    <option value="B">B:免用統一發票核定繳納營業稅</option>
                                    <option value="C">C:未達課稅起徵點</option>
                                    <option value="D">D:免徵營業稅或執行業務</option>
                                </select>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('BizTaxFileWay_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司董監事 A 姓名</td>
                            <td colspan="3">
                                <input id="DirectorAName_content" name="DirectorAName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('DirectorAName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司董監事 A 統編</td>
                            <td colspan="3">
                                <input id="DirectorAId_content" name="DirectorAId_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('DirectorAId_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司董監事 B 姓名</td>
                            <td colspan="3">
                                <input id="DirectorBName_content" name="DirectorBName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('DirectorBName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司董監事 B 統編</td>
                            <td colspan="3">
                                <input id="DirectorBId_content" name="DirectorBId_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('DirectorBId_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司董監事 C 姓名</td>
                            <td colspan="3">
                                <input id="DirectorCName_content" name="DirectorCName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('DirectorCName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司董監事 C 統編</td>
                            <td colspan="3">
                                <input id="DirectorCId_content" name="DirectorCId_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('DirectorCId_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司董監事 D 姓名</td>
                            <td colspan="3">
                                <input id="DirectorDName_content" name="DirectorDName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('DirectorDName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司董監事 D 統編</td>
                            <td colspan="3">
                                <input id="DirectorDId_content" name="DirectorDId_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('DirectorDId_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司董監事 E 姓名</td>
                            <td colspan="3">
                                <input id="DirectorEName_content" name="DirectorEName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('DirectorEName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司董監事 E 統編</td>
                            <td colspan="3">
                                <input id="DirectorEId_content" name="DirectorEId_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('DirectorEId_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司董監事 F 姓名</td>
                            <td colspan="3">
                                <input id="DirectorFName_content" name="DirectorFName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('DirectorFName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司董監事 F 統編</td>
                            <td colspan="3">
                                <input id="DirectorFId_content" name="DirectorFId_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('DirectorFId_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司董監事 G 姓名</td>
                            <td colspan="3">
                                <input id="DirectorGName_content" name="DirectorGName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('DirectorGName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司董監事 G 統編</td>
                            <td colspan="3">
                                <input id="DirectorGId_content" name="DirectorGId_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('DirectorGId_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">員工人數</td>
                            <td colspan="3">
                                <input id="employeeNum_content" name="employeeNum_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('employeeNum_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">股東人數</td>
                            <td colspan="3">
                                <input id="stockholderNum_content" name="stockholderNum_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('stockholderNum_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="title input-center bold-bottom-border bold-right-border" rowspan="24">
                                D-身份證
                            </td>
                            <td class="bold-right-border">負責人姓名<span style="color: red;">*</span></td>
                            <td colspan="3">
                                <input id="PrName_content" name="PrName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">負責人統一編號<span style="color: red;">*</span></td>
                            <td colspan="3">
                                <input id="PrincipalId_content" name="PrincipalId_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrincipalId_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>負責人出生日期<span style="color: red;">*</span></div>
                                <div style="color:red;">格式:YYYYMMDD</div>
                            </td>
                            <td colspan="3">
                                <input id="PrBirth_content" name="PrBirth_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrBirth_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">負責人戶籍地址-郵遞區號</td>
                            <td colspan="3">
                                <input id="PrResAddrZip_content" name="PrResAddrZip_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrResAddrZip_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">負責人戶籍地址-郵遞區號名稱</td>
                            <td colspan="3">
                                <input id="PrResAddrZipName_content" name="PrResAddrZipName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrResAddrZipName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">負責人戶籍地址-非郵遞地址資料</td>
                            <td colspan="3">
                                <input id="PrReslAddress_content" name="PrReslAddress_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrReslAddress_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">甲保證人姓名</td>
                            <td colspan="3">
                                <input id="GuOneName_content" name="GuOneName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">甲保證人統一編號</td>
                            <td colspan="3">
                                <input id="GuOneId_content" name="GuOneId_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneId_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>甲保證人出生日期</div>
                                <div style="color:red;">格式:YYYYMMDD</div>
                            </td>
                            <td colspan="3">
                                <input id="GuOneBirth_content" name="GuOneBirth_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneBirth_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">甲保證人戶籍地址-郵遞區號</td>
                            <td colspan="3">
                                <input id="GuOneResAddrZip_content" name="GuOneResAddrZip_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneResAddrZip_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">甲保證人戶籍地址-郵遞區號名稱</td>
                            <td colspan="3">
                                <input id="GuOneResAddrZipName_content" name="GuOneResAddrZipName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneResAddrZipName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">甲保證人戶籍地址-非郵遞地址資料</td>
                            <td colspan="3">
                                <input id="GuOneReslAddress_content" name="GuOneReslAddress_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneReslAddress_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">乙保證人姓名</td>
                            <td colspan="3">
                                <input id="GuTwoName_content" name="GuTwoName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">乙保證人統一編號</td>
                            <td colspan="3">
                                <input id="GuTwoId_content" name="GuTwoId_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoId_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>乙保證人出生日期</div>
                                <div style="color:red;">格式:YYYYMMDD</div>
                            </td>
                            <td colspan="3">
                                <input id="GuTwoBirth_content" name="GuTwoBirth_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoBirth_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">乙保證人戶籍地址-郵遞區號</td>
                            <td colspan="3">
                                <input id="GuTwoResAddrZip_content" name="GuTwoResAddrZip_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoResAddrZip_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">乙保證人戶籍地址-郵遞區號名稱</td>
                            <td colspan="3">
                                <input id="GuTwoResAddrZipName_content" name="GuTwoResAddrZipName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoResAddrZipName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">乙保證人戶籍地址-非郵遞地址資料</td>
                            <td colspan="3">
                                <input id="GuTwoReslAddress_content" name="GuTwoReslAddress_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoReslAddress_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">配偶姓名</td>
                            <td colspan="3">
                                <input id="SpouseName_content" name="SpouseName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('SpouseName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">配偶統一編號</td>
                            <td colspan="3">
                                <input id="SpouseId_content" name="SpouseId_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('SpouseId_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>配偶出生日期</div>
                                <div style="color:red;">格式:YYYYMMDD</div>
                            </td>
                            <td colspan="3">
                                <input id="SpouseBirth_content" name="SpouseBirth_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('SpouseBirth_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">配偶戶籍地址-郵遞區號</td>
                            <td colspan="3">
                                <input id="SpouseResAddrZip_content" name="SpouseResAddrZip_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('SpouseResAddrZip_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">配偶戶籍地址-郵遞區號名稱</td>
                            <td colspan="3">
                                <input id="SpouseResAddrZipName_content" name="SpouseResAddrZipName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('SpouseResAddrZipName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">配偶戶籍地址-非郵遞地址資料</td>
                            <td colspan="3">
                                <input id="SpouseReslAddress_content" name="SpouseReslAddress_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('SpouseReslAddress_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="title input-center bold-bottom-border bold-right-border" rowspan="44">
                                E-個人資料表
                            </td>
                            <td class="bold-right-border">負責人現居地址-郵遞區號</td>
                            <td colspan="3">
                                <input id="PrCurAddrZip_content" name="PrCurAddrZip_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrCurAddrZip_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">負責人現居地址-郵遞區號名稱</td>
                            <td colspan="3">
                                <input id="PrCurAddrZipName_content" name="PrCurAddrZipName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrCurAddrZipName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">負責人現居地址-非郵遞地址資料</td>
                            <td colspan="3">
                                <input id="PrCurlAddress_content" name="PrCurlAddress_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrCurlAddress_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">負責人連絡電話-區碼</td>
                            <td colspan="3">
                                <input id="PrTelAreaCode_content" name="PrTelAreaCode_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrTelAreaCode_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">負責人連絡電話-電話號碼</td>
                            <td colspan="3">
                                <input id="PrTelNo_content" name="PrTelNo_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrTelNo_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">負責人連絡電話-分機碼</td>
                            <td colspan="3">
                                <input id="PrTelExt_content" name="PrTelExt_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrTelExt_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">負責人連絡行動電話</td>
                            <td colspan="3">
                                <input id="PrMobileNo_content" name="PrMobileNo_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrMobileNo_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">負責人配偶_有 OR 無</td>
                            <td colspan="3">
                                <select name="IsPrMarried_content" class="table-input" id="IsPrMarried_content" disabled>
                                    <option value="" selected></option>
                                    <option value="1">1:有</option>
                                    <option value="0">0:無</option>
                                </select>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('IsPrMarried_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">配偶是否擔任本案保證人</td>
                            <td colspan="3">
                                <select name="IsPrSpouseGu_content" class="table-input" id="IsPrSpouseGu_content" disabled>
                                    <option value="" selected></option>
                                    <option value="1">1:是</option>
                                    <option value="0">0:否</option>
                                </select>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('IsPrSpouseGu_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">本公司實際負責人</td>
                            <td colspan="3">
                                <select name="RealPr_content" class="table-input" id="RealPr_content" disabled>
                                    <option value="" selected></option>
                                    <option value="01">01:登記負責人</option>
                                    <option value="02">02:配偶</option>
                                    <option value="03">03:甲保證人</option>
                                    <option value="04">04:乙保證人</option>
                                </select>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('RealPr_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>負責人從事本行業年度</div>
                                <div style="color:red;">格式:YYYY</div>
                            </td>
                            <td colspan="3">
                                <input id="PrStartYear_content" name="PrStartYear_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrStartYear_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">負責人學歷</td>
                            <td colspan="3">
                                <select name="PrEduLevel_content" class="table-input" id="PrEduLevel_content" disabled>
                                    <option value="" selected></option>
                                    <option value="A">A:國小</option>
                                    <option value="B">B:國中</option>
                                    <option value="C">C:高中職</option>
                                    <option value="D">D:專科</option>
                                    <option value="E">E:大學</option>
                                    <option value="F">F:碩士</option>
                                    <option value="G">G:博士</option>
                                    <option value="H">H:無</option>
                                </select>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrEduLevel_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">實際負責(經營)人_其他實際負責經營人_與借戶負責人之關係</td>
                            <td colspan="3">
                                <select name="OthRealPrRelWithPr_content" class="table-input" id="OthRealPrRelWithPr_content" disabled>
                                    <option value="" selected></option>
                                    <option value="A">A:配偶</option>
                                    <option value="B">B:血親</option>
                                    <option value="C">C:姻親</option>
                                    <option value="D">D:股東</option>
                                    <option value="E">E:朋友</option>
                                    <option value="F">F:本人</option>
                                    <option value="G">G:其他</option>
                                    <option value="H">H:與經營有關之借戶職員</option>
                                </select>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('OthRealPrRelWithPr_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">實際負責(經營)人_其他實際負責經營人_姓名</td>
                            <td colspan="3">
                                <input id="OthRealPrName_content" name="OthRealPrName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('OthRealPrName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">實際負責(經營)人_其他實際負責經營人_身分證字號</td>
                            <td colspan="3">
                                <input id="OthRealPrId_content" name="OthRealPrId_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('OthRealPrId_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>實際負責(經營)人_其他實際負責經營人_出生日期</div>
                                <div style="color:red;">格式:YYYYMMDD</div>
                            </td>
                            <td colspan="3">
                                <input id="OthRealPrBirth_content" name="OthRealPrBirth_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('OthRealPrBirth_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <di>實際負責(經營)人_其他實際負責經營人_從事本行業年度</di>
                                <div style="color:red;">格式:YYYY</div>
                            </td>
                            <td colspan="3">
                                <input id="OthRealPrStartYear_content" name="OthRealPrStartYear_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('OthRealPrStartYear_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">實際負責(經營)人_其他實際負責經營人_擔任本公司職務</td>
                            <td colspan="3">
                                <input id="OthRealPrTitle_content" name="OthRealPrTitle_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('OthRealPrTitle_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">實際負責(經營)人_其他實際負責經營人_持股比率%</td>
                            <td colspan="3">
                                <input id="OthRealPrSHRatio_content" name="OthRealPrSHRatio_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('OthRealPrSHRatio_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">保證人甲_與借戶負責人之關係</td>
                            <td colspan="3">
                                <select name="GuOneRelWithPr_content" class="table-input" id="GuOneRelWithPr_content" disabled>
                                    <option value="" selected></option>
                                    <option value="A">A:配偶</option>
                                    <option value="B">B:血親</option>
                                    <option value="C">C:姻親</option>
                                    <option value="D">D:股東</option>
                                    <option value="E">E:朋友</option>
                                    <option value="G">G:其他</option>
                                    <option value="H">H:與經營有關之借戶職員</option>
                                </select>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneRelWithPr_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">保證人甲_任職公司</td>
                            <td colspan="3">
                                <select name="GuOneCompany_content" class="table-input" id="GuOneCompany_content" disabled>
                                    <option value="" selected></option>
                                    <option value="A">A:公家機關</option>
                                    <option value="B">B:上市櫃公司</option>
                                    <option value="C">C:專業人士</option>
                                    <option value="D">D:借戶</option>
                                    <option value="E">E:其他民營企業</option>
                                    <option value="F">F:無</option>
                                </select>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneCompany_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">保證人乙_與借戶負責人之關係</td>
                            <td colspan="3">
                                <select name="GuTwoRelWithPr_content" class="table-input" id="GuTwoRelWithPr_content" disabled>
                                    <option value="" selected></option>
                                    <option value="A">A:配偶</option>
                                    <option value="B">B:血親</option>
                                    <option value="C">C:姻親</option>
                                    <option value="D">D:股東</option>
                                    <option value="E">E:朋友</option>
                                    <option value="G">G:其他</option>
                                    <option value="H">H:與經營有關之借戶職員</option>
                                </select>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoRelWithPr_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">保證人乙_任職公司</td>
                            <td colspan="3">
                                <select name="GuTwoCompany_content" class="table-input" id="GuTwoCompany_content" disabled>
                                    <option value="" selected></option>
                                    <option value="A">A:公家機關</option>
                                    <option value="B">B:上市櫃公司</option>
                                    <option value="C">C:專業人士</option>
                                    <option value="D">D:借戶</option>
                                    <option value="E">E:其他民營企業</option>
                                    <option value="F">F:無</option>
                                </select>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoCompany_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">配偶現居地址-郵遞區號</td>
                            <td colspan="3">
                                <input id="SpouseCurAddrZip_content" name="SpouseCurAddrZip_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('SpouseCurAddrZip_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">配偶現居地址-郵遞區號名稱</td>
                            <td colspan="3">
                                <input id="SpouseCurAddrZipName_content" name="SpouseCurAddrZipName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('SpouseCurAddrZipName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">配偶現居地址-非郵遞地址資料</td>
                            <td colspan="3">
                                <input id="SpouseCurlAddress_content" name="SpouseCurlAddress_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('SpouseCurlAddress_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">配偶連絡電話-區碼</td>
                            <td colspan="3">
                                <input id="SpouseTelAreaCode_content" name="SpouseTelAreaCode_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('SpouseTelAreaCode_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">配偶連絡電話-電話號碼</td>
                            <td colspan="3">
                                <input id="SpouseTelNo_content" name="SpouseTelNo_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('SpouseTelNo_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">配偶連絡電話-分機碼</td>
                            <td colspan="3">
                                <input id="SpouseTelExt_content" name="SpouseTelExt_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('SpouseTelExt_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">配偶連絡行動電話</td>
                            <td colspan="3">
                                <input id="SpouseMobileNo_content" name="SpouseMobileNo_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('SpouseMobileNo_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">甲保證人現居地址-郵遞區號</td>
                            <td colspan="3">
                                <input id="GuOneCurAddrZip_content" name="GuOneCurAddrZip_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneCurAddrZip_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">甲保證人現居地址-郵遞區號名稱</td>
                            <td colspan="3">
                                <input id="GuOneCurAddrZipName_content" name="GuOneCurAddrZipName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneCurAddrZipName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">甲保證人現居地址-非郵遞地址資料</td>
                            <td colspan="3">
                                <input id="GuOneCurlAddress_content" name="GuOneCurlAddress_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneCurlAddress_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">甲保證人連絡電話-區碼</td>
                            <td colspan="3">
                                <input id="GuOneTelAreaCode_content" name="GuOneTelAreaCode_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneTelAreaCode_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">甲保證人連絡電話-電話號碼</td>
                            <td colspan="3">
                                <input id="GuOneTelNo_content" name="GuOneTelNo_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneTelNo_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">甲保證人連絡電話-分機碼</td>
                            <td colspan="3">
                                <input id="GuOneTelExt_content" name="GuOneTelExt_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneTelExt_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">甲保證人連絡行動電話</td>
                            <td colspan="3">
                                <input id="GuOneMobileNo_content" name="GuOneMobileNo_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneMobileNo_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">乙保證人現居地址-郵遞區號</td>
                            <td colspan="3">
                                <input id="GuTwoCurAddrZip_content" name="GuTwoCurAddrZip_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoCurAddrZip_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">乙保證人現居地址-郵遞區號名稱</td>
                            <td colspan="3">
                                <input id="GuTwoCurAddrZipName_content" name="GuTwoCurAddrZipName_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoCurAddrZipName_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">乙保證人現居地址-非郵遞地址資料</td>
                            <td colspan="3">
                                <input id="GuTwoCurlAddress_content" name="GuTwoCurlAddress_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoCurlAddress_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">乙保證人連絡電話-區碼</td>
                            <td colspan="3">
                                <input id="GuTwoTelAreaCode_content" name="GuTwoTelAreaCode_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoTelAreaCode_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">乙保證人連絡電話-電話號碼</td>
                            <td colspan="3">
                                <input id="GuTwoTelNo_content" name="GuTwoTelNo_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoTelNo_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">乙保證人連絡電話-分機碼</td>
                            <td colspan="3">
                                <input id="GuTwoTelExt_content" name="GuTwoTelExt_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoTelExt_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">乙保證人連絡行動電話</td>
                            <td colspan="3">
                                <input id="GuTwoMobileNo_content" name="GuTwoMobileNo_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoMobileNo_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="title input-center bold-bottom-border bold-right-border" rowspan="6">
                                F-所得稅申報書<br>（損益表）
                            </td>
                            <td class="bold-right-border">
                                <div>近一年結算申報書營業收入-年度</div>
                                <div style="color:red;">格式:YYYYMMDD</div>
                            </td>
                            <td colspan="3">
                                <input id="AnnualIncomeYear1_content" name="AnnualIncomeYear1_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('AnnualIncomeYear1_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">近一年結算申報書營業收入-營收</td>
                            <td colspan="3">
                                <input id="AnnualIncome1_content" name="AnnualIncome1_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('AnnualIncome1_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>近二年結算申報書營業收入-年度</div>
                                <div style="color:red;">格式:YYYYMMDD</div>
                            </td>
                            <td colspan="3">
                                <input id="AnnualIncomeYear2_content" name="AnnualIncomeYear2_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('AnnualIncomeYear2_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">近二年結算申報書營業收入-營收</td>
                            <td colspan="3">
                                <input id="AnnualIncome2_content" name="AnnualIncome2_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('AnnualIncome2_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>近三年結算申報書營業收入-年度</div>
                                <div style="color:red;">格式:YYYYMMDD</div>
                            </td>
                            <td colspan="3">
                                <input id="AnnualIncomeYear3_content" name="AnnualIncomeYear3_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('AnnualIncomeYear3_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-bottom-border bold-right-border">近三年結算申報書營業收入-營收</td>
                            <td colspan="3" class="bold-bottom-border">
                                <input id="AnnualIncome3_content" type="text" class="table-input" disabled>
                            </td>
                            <td class="bold-bottom-border">
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('AnnualIncome3_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="title input-center bold-bottom-border bold-right-border" rowspan="24">
                                G-勞保局投保人數
                            </td>
                            <td class="bold-right-border">
                                <div>公司近01個月投保人數-年月</div>
                                <div style="color:red;">格式:YYYYMM</div>
                            </td>
                            <td colspan="3">
                                <input id="NumOfInsuredYM1_content" name="NumOfInsuredYM1_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('NumOfInsuredYM1_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司近01個月投保人數-人數</td>
                            <td colspan="3">
                                <input id="NumOfInsured1_content" name="NumOfInsured1_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('NumOfInsured1_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>公司近02個月投保人數-年月</div>
                                <div style="color:red;">格式:YYYYMM</div>
                            </td>
                            <td colspan="3">
                                <input id="NumOfInsuredYM2_content" name="NumOfInsuredYM2_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('NumOfInsuredYM2_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司近02個月投保人數-人數</td>
                            <td colspan="3">
                                <input id="NumOfInsured2_content" name="NumOfInsured2_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('NumOfInsured2_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>公司近03個月投保人數-年月</div>
                                <div style="color:red;">格式:YYYYMM</div>
                            </td>
                            <td colspan="3">
                                <input id="NumOfInsuredYM3_content" name="NumOfInsuredYM3_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('NumOfInsuredYM3_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>公司近03個月投保人數-人數</div>
                                <div style="color:red;">格式:YYYYMM</div>
                            </td>
                            <td colspan="3">
                                <input id="NumOfInsured3_content" name="NumOfInsured3_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('NumOfInsured3_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>公司近04個月投保人數-年月</div>
                                <div style="color:red;">格式:YYYYMM</div>
                            </td>
                            <td colspan="3">
                                <input id="NumOfInsuredYM4_content" name="NumOfInsuredYM4_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('NumOfInsuredYM4_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>公司近04個月投保人數-人數</div>
                                <div style="color:red;">格式:YYYYMM</div>
                            </td>
                            <td colspan="3">
                                <input id="NumOfInsured4_content" name="NumOfInsured4_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('NumOfInsured4_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>公司近05個月投保人數-年月</div>
                                <div style="color:red;">格式:YYYYMM</div>
                            </td>
                            <td colspan="3">
                                <input id="NumOfInsuredYM5_content" name="NumOfInsuredYM5_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('NumOfInsuredYM5_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司近05個月投保人數-人數</td>
                            <td colspan="3">
                                <input id="NumOfInsured5_content" name="NumOfInsured5_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('NumOfInsured5_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>公司近06個月投保人數-年月</div>
                                <div style="color:red;">格式:YYYYMM</div>
                            </td>
                            <td colspan="3">
                                <input id="NumOfInsuredYM6_content" name="NumOfInsuredYM6_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('NumOfInsuredYM6_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司近06個月投保人數-人數</td>
                            <td colspan="3">
                                <input id="NumOfInsured6_content" name="NumOfInsured6_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('NumOfInsured6_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>公司近07個月投保人數-年月</div>
                                <div style="color:red;">格式:YYYYMM</div>
                            </td>
                            <td colspan="3">
                                <input id="NumOfInsuredYM7_content" name="NumOfInsuredYM7_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('NumOfInsuredYM7_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司近07個月投保人數-人數</td>
                            <td colspan="3">
                                <input id="NumOfInsured7_content" name="NumOfInsured7_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('NumOfInsured7_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>公司近08個月投保人數-年月</div>
                                <div style="color:red;">格式:YYYYMM</div>
                            </td>
                            <td colspan="3">
                                <input id="NumOfInsuredYM8_content" name="NumOfInsuredYM8_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('NumOfInsuredYM8_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司近08個月投保人數-人數</td>
                            <td colspan="3">
                                <input id="NumOfInsured8_content" name="NumOfInsured8_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('NumOfInsured8_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>公司近09個月投保人數-年月</div>
                                <div style="color:red;">格式:YYYYMM</div>
                            </td>
                            <td colspan="3">
                                <input id="NumOfInsuredYM9_content" name="NumOfInsuredYM9_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('NumOfInsuredYM9_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司近09個月投保人數-人數</td>
                            <td colspan="3">
                                <input id="NumOfInsured9_content" name="NumOfInsured9_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('NumOfInsured9_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>公司近10個月投保人數-年月</div>
                                <div style="color:red;">格式:YYYYMM</div>
                            </td>
                            <td colspan="3">
                                <input id="NumOfInsuredYM10_content" name="NumOfInsuredYM10_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('NumOfInsuredYM10_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司近10個月投保人數-人數</td>
                            <td colspan="3">
                                <input id="NumOfInsured10_content" name="NumOfInsured10_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('NumOfInsured10_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>公司近11個月投保人數-年月</div>
                                <div style="color:red;">格式:YYYYMM</div>
                            </td>
                            <td colspan="3">
                                <input id="NumOfInsuredYM11_content" name="NumOfInsuredYM11_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('NumOfInsuredYM11_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司近11個月投保人數-人數</td>
                            <td colspan="3">
                                <input id="NumOfInsured11_content" name="NumOfInsured11_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('NumOfInsured11_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>公司近12個月投保人數-年月</div>
                                <div style="color:red;">格式:YYYYMM</div>
                            </td>
                            <td colspan="3">
                                <input id="NumOfInsuredYM12_content" name="NumOfInsuredYM12_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('NumOfInsuredYM12_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司近12個月投保人數-人數</td>
                            <td colspan="3">
                                <input id="NumOfInsured12_content" name="NumOfInsured12_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('NumOfInsured12_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="title input-center bold-bottom-border bold-right-border" rowspan="53">
                                H-聯徵資料
                            </td>
                            <td class="bold-right-border">
                                <div>企業聯徵查詢日期</div>
                                <div style="color:red;">格式:YYYYMMDD</div>
                            </td>
                            <td colspan="3">
                                <input id="jcCompDataDate_content" name="jcCompDataDate_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('jcCompDataDate_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>公司中期放款餘額-年月</div>
                                <div style="color:red;">格式:YYYYMM</div>
                            </td>
                            <td colspan="3">
                                <input id="MidTermLnYM_content" name="MidTermLnYM_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('MidTermLnYM_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司中期放款餘額</td>
                            <td colspan="3">
                                <input id="MidTermLnBal_content" name="MidTermLnBal_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('MidTermLnBal_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>公司短期放款餘額-年月</div>
                                <div style="color:red;">格式:YYYYMM</div>
                            </td>
                            <td colspan="3">
                                <input id="ShortTermLnYM_content" name="ShortTermLnYM_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('ShortTermLnYM_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">公司短期放款餘額</td>
                            <td colspan="3">
                                <input id="ShortTermLnBal_content" name="ShortTermLnBal_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('ShortTermLnBal_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>企業聯徵J02資料年月</div>
                                <div style="color:red;">格式:YYYYMM</div>
                            </td>
                            <td colspan="3">
                                <input id="jcCompJ02YM_content" name="jcCompJ02YM_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('jcCompJ02YM_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">企業信用評分</td>
                            <td colspan="3">
                                <input id="CompCreditScore_content" name="CompCreditScore_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('CompCreditScore_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>負責人聯徵查詢日期</div>
                                <div style="color:red;">格式:YYYYMMDD</div>
                            </td>
                            <td colspan="3">
                                <input id="PrJCICQueryDate_content" name="PrJCICQueryDate_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrJCICQueryDate_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">負責人聯徵信用評分</td>
                            <td colspan="3">
                                <input id="PrCreditScore_content" name="PrCreditScore_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrCreditScore_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>負責人聯徵J01資料年月</div>
                                <div style="color:red;">格式:YYYYMM</div>
                            </td>
                            <td colspan="3">
                                <input id="PrJCICDataDate_content" name="PrJCICDataDate_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrJCICDataDate_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">負責人銀行借款餘額(現金卡)</td>
                            <td colspan="3">
                                <input id="PrBal_CashCard_content" name="PrBal_CashCard_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrBal_CashCard_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">負責人銀行借款餘額(信用卡)</td>
                            <td colspan="3">
                                <input id="PrBal_CreditCard_content" name="PrBal_CreditCard_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrBal_CreditCard_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">負責人銀行借款餘額(短放)</td>
                            <td colspan="3">
                                <input id="PrBal_ShortTermLn_content" name="PrBal_ShortTermLn_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrBal_ShortTermLn_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">負責人銀行借款餘額(中放)</td>
                            <td colspan="3">
                                <input id="PrBal_MidTermLn_content" name="PrBal_MidTermLn_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrBal_MidTermLn_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">負責人銀行借款餘額(長放)</td>
                            <td colspan="3">
                                <input id="PrBal_LongTermLn_content" name="PrBal_LongTermLn_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrBal_LongTermLn_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">負責人銀行借款餘額(短擔)</td>
                            <td colspan="3">
                                <input id="PrBal_ShortTermGuar_content" name="PrBal_ShortTermGuar_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrBal_ShortTermGuar_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">負責人銀行借款餘額(中擔)</td>
                            <td colspan="3">
                                <input id="PrBal_MidTermLnGuar_content" name="PrBal_MidTermLnGuar_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrBal_MidTermLnGuar_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">負責人銀行借款餘額(長擔)</td>
                            <td colspan="3">
                                <input id="PrBal_LongTermLnGuar_content" name="PrBal_LongTermLnGuar_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrBal_LongTermLnGuar_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>配偶聯徵查詢日期</div>
                                <div style="color:red;">格式:YYYYMMDD</div>
                            </td>
                            <td colspan="3">
                                <input id="SpouseJCICQueryDate_content" name="SpouseJCICQueryDate_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('SpouseJCICQueryDate_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">配偶聯徵信用評分</td>
                            <td colspan="3">
                                <input id="SpouseCreditScore_content" name="SpouseCreditScore_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('SpouseCreditScore_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>配偶聯徵J01資料年月</div>
                                <div style="color:red;">格式:YYYYMM</div>
                            </td>
                            <td colspan="3">
                                <input id="SpouseJCICDataDate_content" name="SpouseJCICDataDate_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('SpouseJCICDataDate_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">配偶銀行借款餘額(現金卡)</td>
                            <td colspan="3">
                                <input id="SpouseBal_CashCard_content" name="SpouseBal_CashCard_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('SpouseBal_CashCard_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">配偶銀行借款餘額(信用卡)</td>
                            <td colspan="3">
                                <input id="SpouseBal_CreditCard_content" name="SpouseBal_CreditCard_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('SpouseBal_CreditCard_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">配偶銀行借款餘額(短放)</td>
                            <td colspan="3">
                                <input id="SpouseBal_ShortTermLn_content" name="SpouseBal_ShortTermLn_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('SpouseBal_ShortTermLn_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">配偶銀行借款餘額(中放)</td>
                            <td colspan="3">
                                <input id="SpouseBal_MidTermLn_content" name="SpouseBal_MidTermLn_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('SpouseBal_MidTermLn_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">配偶銀行借款餘額(長放)</td>
                            <td colspan="3">
                                <input id="SpouseBal_LongTermLn_content" name="SpouseBal_LongTermLn_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('SpouseBal_LongTermLn_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">配偶銀行借款餘額(短擔)</td>
                            <td colspan="3">
                                <input id="SpouseBal_ShortTermGuar_content" name="SpouseBal_ShortTermGuar_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('SpouseBal_ShortTermGuar_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">配偶銀行借款餘額(中擔)</td>
                            <td colspan="3">
                                <input id="SpouseBal_MidTermLnGuar_content" name="SpouseBal_MidTermLnGuar_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('SpouseBal_MidTermLnGuar_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">配偶銀行借款餘額(長擔)</td>
                            <td colspan="3">
                                <input id="SpouseBal_LongTermLnGuar_content" name="SpouseBal_LongTermLnGuar_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('SpouseBal_LongTermLnGuar_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>甲保證人聯徵查詢日期</div>
                                <div style="color:red;">格式:YYYYMMDD</div>
                            </td>
                            <td colspan="3">
                                <input id="GuOneJCICQueryDate_content" name="GuOneJCICQueryDate_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneJCICQueryDate_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">甲保證人聯徵信用評分</td>
                            <td colspan="3">
                                <input id="GuOneCreditScore_content" name="GuOneCreditScore_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneCreditScore_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>甲保證人聯徵J01資料年月</div>
                                <div style="color:red;">格式:YYYYMM</div>
                            </td>
                            <td colspan="3">
                                <input id="GuOneJCICDataDate_content" name="GuOneJCICDataDate_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneJCICDataDate_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">甲保證人銀行借款餘額(現金卡)</td>
                            <td colspan="3">
                                <input id="GuOneBal_CashCard_content" name="GuOneBal_CashCard_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneBal_CashCard_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">甲保證人銀行借款餘額(信用卡)</td>
                            <td colspan="3">
                                <input id="GuOneBal_CreditCard_content" name="GuOneBal_CreditCard_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneBal_CreditCard_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">甲保證人銀行借款餘額(短放)</td>
                            <td colspan="3">
                                <input id="GuOneBal_ShortTermLn_content" name="GuOneBal_ShortTermLn_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneBal_ShortTermLn_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">甲保證人銀行借款餘額(中放)</td>
                            <td colspan="3">
                                <input id="GuOneBal_MidTermLn_content" name="GuOneBal_MidTermLn_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneBal_MidTermLn_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">甲保證人銀行借款餘額(長放)</td>
                            <td colspan="3">
                                <input id="GuOneBal_LongTermLn_content" name="GuOneBal_LongTermLn_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneBal_LongTermLn_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">甲保證人銀行借款餘額(短擔)</td>
                            <td colspan="3">
                                <input id="GuOneBal_ShortTermGuar_content" name="GuOneBal_ShortTermGuar_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneBal_ShortTermGuar_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">甲保證人銀行借款餘額(中擔)</td>
                            <td colspan="3">
                                <input id="GuOneBal_MidTermLnGuar_content" name="GuOneBal_MidTermLnGuar_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneBal_MidTermLnGuar_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">甲保證人銀行借款餘額(長擔)</td>
                            <td colspan="3">
                                <input id="GuOneBal_LongTermLnGuar_content" name="GuOneBal_LongTermLnGuar_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneBal_LongTermLnGuar_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>乙保證人聯徵查詢日期</div>
                                <div style="color:red;">格式:YYYYMMDD</div>
                            </td>
                            <td colspan="3">
                                <input id="GuTwoJCICQueryDate_content" name="GuTwoJCICQueryDate_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoJCICQueryDate_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">乙保證人聯徵信用評分</td>
                            <td colspan="3">
                                <input id="GuTwoCreditScore_content" name="GuTwoCreditScore_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoCreditScore_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>乙保證人聯徵J01資料年月</div>
                                <div style="color:red;">格式:YYYYMM</div>
                            </td>
                            <td colspan="3">
                                <input id="GuTwoJCICDataDate_content" name="GuTwoJCICDataDate_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoJCICDataDate_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">乙保證人銀行借款餘額(現金卡)</td>
                            <td colspan="3">
                                <input id="GuTwoBal_CashCard_content" name="GuTwoBal_CashCard_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoBal_CashCard_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">乙保證人銀行借款餘額(信用卡)</td>
                            <td colspan="3">
                                <input id="GuTwoBal_CreditCard_content" name="GuTwoBal_CreditCard_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoBal_CreditCard_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">乙保證人銀行借款餘額(短放)</td>
                            <td colspan="3">
                                <input id="GuTwoBal_ShortTermLn_content" name="GuTwoBal_ShortTermLn_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoBal_ShortTermLn_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">乙保證人銀行借款餘額(中放)</td>
                            <td colspan="3">
                                <input id="GuTwoBal_MidTermLn_content" name="GuTwoBal_MidTermLn_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoBal_MidTermLn_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">乙保證人銀行借款餘額(長放)</td>
                            <td colspan="3">
                                <input id="GuTwoBal_LongTermLn_content" name="GuTwoBal_LongTermLn_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoBal_LongTermLn_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">乙保證人銀行借款餘額(短擔)</td>
                            <td colspan="3">
                                <input id="GuTwoBal_ShortTermGuar_content" name="GuTwoBal_ShortTermGuar_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoBal_ShortTermGuar_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">乙保證人銀行借款餘額(中擔)</td>
                            <td colspan="3">
                                <input id="GuTwoBal_MidTermLnGuar_content" name="GuTwoBal_MidTermLnGuar_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoBal_MidTermLnGuar_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">乙保證人銀行借款餘額(長擔)</td>
                            <td colspan="3">
                                <input id="GuTwoBal_LongTermLnGuar_content" name="GuTwoBal_LongTermLnGuar_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoBal_LongTermLnGuar_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">負責人擔任其他企業負責人之企業統編</td>
                            <td colspan="3">
                                <input id="PrBeingOthCompPrId_content" name="PrBeingOthCompPrId_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrBeingOthCompPrId_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">配偶擔任其他企業負責人之企業統編</td>
                            <td colspan="3">
                                <input id="SpouseBeingOthCompPrId_content" name="SpouseBeingOthCompPrId_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('SpouseBeingOthCompPrId_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="title input-center bold-bottom-border bold-right-border" rowspan="8">
                                I-勞保異動明細
                            </td>
                            <td class="bold-right-border">
                                <div>負責人-被保險人勞保異動查詢日期</div>
                                <div style="color:red;">格式:YYYYMMDD</div>
                            </td>
                            <td colspan="3">
                                <input id="PrLaborQryDate_content" name="PrLaborQryDate_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrLaborQryDate_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">負責人-被保險人勞保異動查詢-最近期投保薪資</td>
                            <td colspan="3">
                                <input id="PrLaborInsSalary_content" name="PrLaborInsSalary_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('PrLaborInsSalary_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>配偶-被保險人勞保異動查詢日期</div>
                                <div style="color:red;">格式:YYYYMMDD</div>
                            </td>
                            <td colspan="3">
                                <input id="SpouseLaborQryDate_content" name="SpouseLaborQryDate_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('SpouseLaborQryDate_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">配偶-被保險人勞保異動查詢-最近期投保薪資</td>
                            <td colspan="3">
                                <input id="SpouseLaborInsSalary_content" name="SpouseLaborInsSalary_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('SpouseLaborInsSalary_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>甲保證人-被保險人勞保異動查詢日期</div>
                                <div style="color:red;">格式:YYYYMMDD</div>
                            </td>
                            <td colspan="3">
                                <input id="GuOneLaborQryDate_content" name="GuOneLaborQryDate_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneLaborQryDate_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">甲保證人-被保險人勞保異動查詢-最近期投保薪資</td>
                            <td colspan="3">
                                <input id="GuOneLaborInsSalary_content" name="GuOneLaborInsSalary_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuOneLaborInsSalary_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">
                                <div>乙保證人-被保險人勞保異動查詢日期</div>
                                <div style="color:red;">格式:YYYYMMDD</div>
                            </td>
                            <td colspan="3">
                                <input id="GuTwoLaborQryDate_content" name="GuTwoLaborQryDate_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoLaborQryDate_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold-right-border">乙保證人-被保險人勞保異動查詢-最近期投保薪資</td>
                            <td colspan="3">
                                <input id="GuTwoLaborInsSalary_content" name="GuTwoLaborInsSalary_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('GuTwoLaborInsSalary_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td class="title input-center bold-bottom-border bold-right-border">J-其他</td>
                            <td class="bold-right-border">其他備註</td>
                            <td colspan="3">
                                <input id="OtherMemo_content" name="OtherMemo_content" type="text" class="table-input" disabled>
                            </td>
                            <td>
                                <center>
                                    <input class="input-width" type="button"
                                           onclick="edit_click('OtherMemo_content')" value="Edit">
                                </center>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="input-width">
                        <tr>
                            <td class="white-border">
                                <center>
                                    <input id="text_list" type="button" class="sendBtn" value="儲存資料">
                                </center>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="page">
            <div class="subpage api_file_page">
                <h1 style="text-align: center;">附件檢核表</h1>
                <table class="table" border="2">
                    <tbody>
                    <tr>
                        <th class="th bold-bottom-border bold-right-border" style="width: 7%;">附件類別<br/>
                            <span style="color: red; font-size: 5px;">( * 為必填欄位 )</span>
                        </th>
                        <th class="field_name th bold-bottom-border bold-right-border">圖片/文件</th>
                    </tr>
                    <tr>
                        <td class="title input-center bold-bottom-border bold-right-border">
                            A01:公司變更事項登記卡及工商登記查詢<span style="color: red;">*</span>
                        </td>
                        <td class="bold-right-border ">
                            <fieldset disabled id="A01">
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <td class="title input-center bold-bottom-border bold-right-border">
                            A02:負責人及保證人身分證影本及第二證件、戶役政查詢<span style="color: red;">*</span>
                        </td>
                        <td class="bold-right-border ">
                            <fieldset disabled id="A02">
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <td class="title input-center bold-bottom-border bold-right-border">
                            A03:營業據點建物登記謄本(公司或負責人或保證人自有才須提供)
                        </td>
                        <td class="bold-right-border ">
                            <fieldset disabled id="A03">
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <td class="title input-center bold-bottom-border bold-right-border">A04:近三年公司所得稅申報書</td>
                        <td class="bold-right-border ">
                            <fieldset disabled id="A04">
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <td class="title input-center bold-bottom-border bold-right-border">
                            A05:近12月勞保局投保資料<span style="color: red;">*</span>
                        </td>
                        <td class="bold-right-border ">
                            <fieldset disabled id="A05">
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <td class="title input-center bold-bottom-border bold-right-border">
                            A06:公司、負責人、配偶及保證人的聯徵資料 J01、J02、J10、J20、A13、A11<span style="color: red;">*</span>
                        </td>
                        <td class="bold-right-border ">
                            <fieldset disabled id="A06">
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <td class="title input-center bold-bottom-border bold-right-border">
                            A07:負責人及保證人之被保險人勞保異動查詢
                        </td>
                        <td class="bold-right-border ">
                            <fieldset disabled id="A07">
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <td class="title input-center bold-bottom-border bold-right-border">
                            A08:公司、負責人及保證人近六個月存摺餘額明細及存摺封面<span style="color: red;">*</span>
                        </td>
                        <td class="bold-right-border ">
                            <fieldset disabled id="A08">
                            </fieldset>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    const SKBANK_ID = 1; // 新光銀行
    const BANK_LIST = [SKBANK_ID];

    let urlString;
    let url;
    let target_id;
    let table_type;
    let data_type;
    // 下拉選單列表
    let select_array = ['CompType_content', 'BusinessType_content', 'compDuType_content', 'bizRegAddrOwner_content', 'isBizAddrEqToBizRegAddr_content', 'BizTaxFileWay_content',
        'IsPrMarried_content', 'IsPrSpouseGu_content', 'RealPr_content', 'realBizRegAddressOwner_content', 'realBizAddressOwner_content', 'realBizAddrOwner_content', 'OthRealPrRelWithPr_content',
        'GuOneRelWithPr_content', 'GuOneCompany_content', 'GuTwoRelWithPr_content', 'GuTwoCompany_content', 'PrEduLevel_content', 'CompMajorOwnership_content', 'CompMajorSetting_content'];
    // 原始檔案圖片-附件資料表
    let rawData_array = [
        'A01', 'A02', 'A03', 'A04', 'A05', 'A06', 'A07', 'A08', 'B02', 'B03', 'B08', 'B09', 'B10', 'B11', 'B13', 'B14', 'B15', 'B16',
    ];
    // 送出時為數字欄位
    let is_int_array = {
        [SKBANK_ID]: ['CompCapital', 'AnnualIncome1', 'AnnualIncome2', 'AnnualIncome3', 'NumOfInsured1', 'NumOfInsured2', 'NumOfInsured3', 'NumOfInsured4', 'NumOfInsured5', 'NumOfInsured6', 'NumOfInsured7', 'NumOfInsured8', 'NumOfInsured9', 'NumOfInsured10', 'NumOfInsured11', 'NumOfInsured12', 'MidTermLnBal', 'ShortTermLnBal', 'CompCreditScore', 'PrLaborInsSalary', 'SpouseLaborInsSalary', 'GuOneLaborInsSalary', 'GuTwoLaborInsSalary', 'PrCreditScore', 'PrBal_CashCard', 'PrBal_CreditCard', 'PrBal_ShortTermLn', 'PrBal_MidTermLn', 'PrBal_LongTermLn', 'PrBal_ShortTermGuar', 'PrBal_MidTermLnGuar', 'PrBal_LongTermLnGuar', 'SpouseCreditScore', 'SpouseBal_CashCard', 'SpouseBal_CreditCard', 'SpouseBal_ShortTermLn', 'SpouseBal_MidTermLn', 'SpouseBal_LongTermLn', 'SpouseBal_ShortTermGuar', 'SpouseBal_MidTermLnGuar', 'SpouseBal_LongTermLnGuar', 'GuOneCreditScore', 'GuOneBal_CashCard', 'GuOneBal_CreditCard', 'GuOneBal_ShortTermLn', 'GuOneBal_MidTermLn', 'GuOneBal_LongTermLn', 'GuOneBal_ShortTermGuar', 'GuOneBal_MidTermLnGuar', 'GuOneBal_LongTermLnGuar', 'GuTwoCreditScore', 'GuTwoBal_CashCard', 'GuTwoBal_CreditCard', 'GuTwoBal_ShortTermLn', 'GuTwoBal_MidTermLn', 'GuTwoBal_LongTermLn', 'GuTwoBal_ShortTermGuar', 'GuTwoBal_MidTermLnGuar', 'GuTwoBal_LongTermLnGuar', 'OthRealPrSHRatio', 'employeeNum', 'stockholderNum']
    };

    function edit_click(id_content) {
        $(`[name=${id_content}]`).each((i, e) => {
            $(e).prop('disabled', function (i, v) {
                return !v;
            })
        })
    }

    function fillReport(data) {
        Object.keys(data).forEach(function (key) {
            let $attachment_block = $(`#${key}`);
            if ($attachment_block.length) {
                if (rawData_array.includes($attachment_block.attr('id'))) {
                    $.each(data[key], function (doc_type, doc_list) {
                        let a_tag = '';
                        if (doc_type === 'image') {
                            $.each(doc_list, function (index, doc) {
                                a_tag += `<a href="${doc}" data-fancybox="images"><img id="${key}_${index}"  src="${doc}" style='width:30%;max-width:400px'></a>`;
                            })
                        } else if (doc_type === 'pdf') {
                            $.each(doc_list, function (index, doc) {
                                a_tag += `<span style="margin: 0 2px 0 2px"><a href="${doc}">檔案${index + 1}</a></span>`;
                            })
                        }
                        $attachment_block.append(a_tag + '<br/>');
                    });
                } else {
                    $attachment_block.val(data[key]);
                }
            }
        })
    }

    // 取得送件檢核表資料
    function fetchReport(target_id, table_type, bank, result) {
        $.ajax({
            type: "GET",
            url: `/admin/bankdata/report?target_id=${target_id}&table_type=${table_type}&bank=${bank}`,
            success: function (response) {
                result(response);
            },
            error: function (error) {
                alert(error);
            }
        });
    }

    function getMappingMsgNo(target_id, action, data_type, bank, result) {
        $.ajax({
            type: "GET",
            url: `/admin/bankdata/getMappingMsgNo?target_id=${target_id}&action=${action}&data_type=${data_type}&bank=${bank}`,
            success: function (response) {
                response = response.response;
                result(response);
            },
            error: function (error) {
                alert(error);
            }
        });
    }

    // 取得收件檢核表文字資料
    function getCheckLisTextData(bank_num) {
        let selectIds = []
        let all_data = {}
        $(`[data-bankid=${bank_num}] input, [data-bankid=${bank_num}] select`).each(function () {
            if (this.name) selectIds.push(this.name)
        })

        let bank_int_array = is_int_array[bank_num];
        selectIds.forEach((item) => {
            let key = '';
            let value = '';
            if (item) {
                if (item.match(/.*-selectized/g)) {
                    key = item.replace(/-selectized/g, '');
                } else {
                    key = item;
                }
                value = $(`[name="${key}"]`).eq(0).val();
                key = key.replace(/_content/g, '');
                if (bank_int_array.includes(key)) {
                    if (value) {
                        value = parseInt(value);
                    } else {
                        value = 0;
                    }
                }
                all_data[key] = value;
            }
        });
        return all_data;
    }

    function getCheckListImagesData() {
        let IDs = [];
        let all_data = {}
        $(".api_file_page").find("img").each(function () {
            IDs.push(this.id);
        });
        IDs.forEach((item) => {
            let key = '';
            let value = '';
            if (item) {
                key = item;
                value = $(`#${key}`).attr('src');
                all_data[key] = value;
            }
        });
        return all_data;
    }

    //儲存送出資料
    function saveCheckListData(msg_no, data_type, data, bank = 1) {
        $.ajax({
            type: "POST",
            url: `/admin/bankdata/saveCheckListData?msg_no=${msg_no}&data_type=${data_type}&bank=${bank}`,
            data: data,
            dataType: "json",
            success: function (response) {
                alert('儲存成功');

                $("#text_list").val("儲存資料");
                $(".sendBtn").prop("disabled", false);

                return response;
            },
            error: function (error) {
                alert(error);
            }
        });
    }

    async function save(send_type) {
        // 收件檢核表資料傳送
        const sendData = (bank_num) => {
            return new Promise((resolve) => {
                $("#text_list").val("資料處理中");
                let all_data = getCheckLisTextData(bank_num);

                getMappingMsgNo(target_id, 'send', 'text', bank_num, function (data) {
                    let msg_data = data;
                    if (!msg_data) {
                        return 'no response';
                    }

                    let msg_no = '';
                    if (msg_data.status.code == 201 || msg_data.status.code == 202) {
                        msg_no = msg_data.data.msg_no;
                    } else {
                        alert(`status code = ${msg_data.status.code}, error message = ${msg_data.status.message}`);
                        return;
                    }
                    if (msg_no == '') {
                        alert('交易序號為空');
                        return;
                    }
                    let request_data = {
                        'MsgNo': msg_no,
                        'CompId': `${all_data.CompId}`,
                        'PrincipalId': `${all_data.PrincipalId}`,
                    }
                    delete all_data.CompId;
                    delete all_data.PrincipalId;
                    request_data.Data = all_data;
                    request_data = JSON.stringify(request_data);

                    saveCheckListData(msg_no, 'text', request_data, bank_num);

                    resolve()
                });
            })
        }
        const sendImageList = (bank_num) => {
            return new Promise((resolve) => {
                $("#image_list").val("資料處理中");
                let all_data = getCheckListImagesData();
                let request_data = [];
                let image_list_data = [];
                let case_no = $('#case_no').val();
                let compId_input = $('#CompId_content').val();

                if (case_no) {
                    Object.keys(all_data).forEach((key) => {
                        const new_string = key.split('_');
                        data_type = new_string[0];
                        getMappingMsgNo(target_id, 'send', key, bank_num, (data) => {
                            let msg_no = data.data.msg_no;
                            request_data.push({
                                'MsgNo': msg_no,
                                'CompId': compId_input,
                                'CaseNo': case_no,
                                'DocType': new_string[0],
                                'DocSeq': parseInt(new_string[1]) + 1,
                                'DocFileType': 4,
                                'DocUrl': all_data[key]
                            });

                            resolve()
                        });
                    })

                }
            })
        }
        if (send_type == 'text_list') {
            for (const bank of BANK_LIST) {
                await sendData(bank)
            }
        }
        if (send_type == 'image_list') {
            for (const bank of BANK_LIST) {
                await sendImageList(bank)
            }
        }
    }

    function changeTab(tab) {
        $('.nav-link').removeClass('active')
        $(`#nav-${tab}`).toggleClass('active')
        $('.nav-page').each((i, e) => {
            $(e).hide()
        })
        $(`#page-${tab}`).show()
    }

    $(document).ready(function () {
        urlString = window.location.href;
        url = new URL(urlString);
        target_id = url.searchParams.get("target_id");
        table_type = url.searchParams.get("table_type");
        let IDs = [];
        $("body").find("input").each(function () {
            if (this.name) IDs.push(this.name);
        });
        $("body").find("select").each(function () {
            if (this.name) IDs.push(this.name);
        });

        $(`#nav-tab-skbank`).click()
        for (const bank of BANK_LIST) {
            fetchReport(target_id, table_type, bank, function (data) {
                if (!data) {
                    alert('can\'t not get response');
                    return;
                }

                if (data.status.code != '200') {
                    alert(data.response);
                    return;
                }
                if (data.status.code == '200' && data.response) {
                    fillReport(data.response);
                    $(`div[data-bankid=${bank}] .sendBtn`).prop('disabled', false);
                }
            });
        }


        $('input').on('input', (e) => {
            const name = e.target.name
            const val = e.target.value
            $(`[name="${name}"]`).each(function () {
                if (this.value != val) {
                    $(this).val(val)
                }
            })
        })

        $('select').on('change', (e) => {
            const name = e.target.name
            const val = e.target.value
            $(`[name="${name}"]`).each(function () {
                if (this.value != val) {
                    $(this).val(val)
                }
            })
        })

        $('.sendBtn').click(function () {
            $(".sendBtn").prop("disabled", true);
            let bank = $(this).parents('.nav-page').data('bankid');

            save(this.id);
        });

    });
</script>

</html>

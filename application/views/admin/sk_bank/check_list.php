<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin/css/credit_table/A4_check.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.10.3/sweetalert2.js"
        type="text/javascript"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
    <script data-pace-options='{ "ajax": true }' src="/assets/admin/scripts/pace.js"></script>
    <title>百萬信保檢核表</title>
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
            <li class="nav-item pointer" onclick="changeTab('tab-kgibank')">
                <a class="nav-link" id="nav-tab-kgibank">凱基</a>
            </li>
        </ul>
        <div id="page-tab-skbank" class="nav-page" data-bankid="1">
            <div class="page">
                <div class="subpage api_data_page">
                    <h3 style="text-align: center;">百萬信保檢核表</h3>
                    <div>
                        <table class="table table-bordered border-dark">
                            <tbody>
                                <tr>
                                    <th class="source th bold-bottom-border bold-right-border">資料來源</th>
                                    <th class="field_name th bold-bottom-border bold-right-border">欄位名稱</th>
                                    <th colspan="3" class="content th bold-bottom-border">內容</th>
                                    <th class="edit th bold-bottom-border">人工檢驗</th>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="32">
                                        A-工商登記<br>
                                        (經濟部API)<br>
                                        (主計處)
                                    </td>
                                    <td class="bold-right-border">公司統一編號</td>
                                    <td colspan="3"><input name="CompId_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司戶名</td>
                                    <td colspan="3"><input name="CompName_content" type="text" class="table-input"
                                            disabled>
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
                                        <div>公司核准設立日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3"><input name="CompSetDate_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompSetDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司實收資本額</td>
                                    <td colspan="3"><input name="CompCapital_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompCapital_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司行業別(主計處)</td>
                                    <td colspan="3"><input name="CompIdustry_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompIdustry_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司型態</td>

                                    <td colspan="3">
                                        <select name="CompType_content" class="table-input" disabled>
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
                                    <td class="bold-right-border">公司產業別</td>
                                    <!-- <input id="CompDuType_content" type="text" class="table-input" disabled> -->
                                    <td colspan="3">
                                        <select name="CompDuType_content" class="table-input" disabled>
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
                                                onclick="edit_click('CompDuType_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業種類</td>
                                    <!-- <input id="BusinessType_content" type="text" class="table-input" disabled>  -->
                                    <td colspan="3">
                                        <select name="BusinessType_content" class="table-input" disabled>
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
                                    <td class="bold-right-border">公司登記地址-郵遞區號</td>
                                    <td colspan="3"><input name="CompRegAddrZip_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompRegAddrZip_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">tab-skbank-custom</td>
                                    <td colspan="3"><input name="customTab1_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button" onclick="edit_click('customTab1')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司登記地址-郵遞區號名稱</td>
                                    <td colspan="3"><input name="CompRegAddrZipName_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompRegAddrZipName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司登記地址-非郵遞地址資料</td>
                                    <td colspan="3"><input name="CompRegAddress_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="PrOnboardDay_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="PrOnboardName_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="ExPrOnboardDay_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="ExPrOnboardName_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="ExPrOnboardDay2_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="ExPrOnboardName2_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="BizRegAddrCityName_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="BizRegAddrAreaName_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="BizRegAddrAreaName_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="BizRegAddrAreaName_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="BizRegAddrSec_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="BizRegAddrLn_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="BizRegAddrAly_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="BizRegAddrNo_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="BizRegAddrNoExt_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="BizRegAddrFloor_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="BizRegAddrFloorExt_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="BizRegAddrRoom_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="BizRegAddrOtherMemo_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('BizRegAddrOtherMemo_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司最後核准變更實收資本額日期</td>
                                    <td colspan="3"><input name="LastPaidInCapitalDate_content" type="text"
                                            class="table-input" disabled>
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
                                    <td class="bold-right-border">公司主要營業場所-郵遞區號</td>
                                    <td colspan="3"><input name="CompMajorAddrZip_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompMajorAddrZip_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司主要營業場所-郵遞區號名稱</td>
                                    <td colspan="3"><input name="CompMajorAddrZipName_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompMajorAddrZipName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司主要營業場所-非郵遞地址資料</td>
                                    <td colspan="3"><input name="CompMajorAddress_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompMajorAddress_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司連絡電話-區碼</td>
                                    <td colspan="3"><input name="CompTelAreaCode_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompTelAreaCode_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司連絡電話-電話號碼</td>
                                    <td colspan="3"><input name="CompTelNo_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompTelNo_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司連絡電話-分機碼</td>
                                    <td colspan="3"><input name="CompTelExt_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompTelExt_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業登記地址</td>
                                    <td colspan="3"><select name="IsBizRegAddrSelfOwn_content" class="table-input"
                                            disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:自有</option>
                                            <option value="0">0:非自有</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('IsBizRegAddrSelfOwn_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業登記地址_自有</td>
                                    <td colspan="3"><select name="BizRegAddrOwner_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="A">A:企業</option>
                                            <option value="B">B:負責人</option>
                                            <option value="C">C:負責人配偶</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('BizRegAddrOwner_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際營業地址_是OR否 同營業登記地址</td>
                                    <td colspan="3"><select name="IsBizAddrEqToBizRegAddr_content" class="table-input"
                                            id="" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:同營業登記地址</option>
                                            <option value="0">0:不同於營業登記地址</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('IsBizAddrEqToBizRegAddr_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際營業地址_選擇縣市</td>
                                    <td colspan="3"><input id="RealBizAddrCityName_content"
                                            name="RealBizAddrCityName_content" type="text" class="table-input" disabled>
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
                                    <td colspan="3"><input id="RealBizAddrAreaName_content"
                                            name="RealBizAddrAreaName_content" type="text" class="table-input" disabled>
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
                                    <td colspan="3"><input id="RealBizAddrRoadName_content"
                                            name="RealBizAddrRoadName_content" type="text" class="table-input" disabled>
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
                                    <td colspan="3"><input id="RealBizAddrRoadType_content"
                                            name="RealBizAddrRoadType_content" type="text" class="table-input" disabled>
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
                                    <td colspan="3"><input id="RealBizAddrSec_content" name="RealBizAddrSec_content"
                                            type="text" class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('RealBizAddrSec_content')" value="Edit">
                                        </center>
                                    </td>
                                <tr>
                                    <td class="bold-right-border">實際營業地址_巷</td>
                                    <td colspan="3"><input name="RealBizAddrLn_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="RealBizAddrAly_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="RealBizAddrNo_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="RealBizAddrNoExt_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="RealBizAddrFloor_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="RealBizAddrFloorExt_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="RealBizAddrRoom_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="RealBizAddrOtherMemo_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><select name="IsRealBizAddrSelfOwn_content" class="table-input"
                                            id="" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:自有</option>
                                            <option value="0">0:非自有</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('IsRealBizAddrSelfOwn_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際營業地址_自有</td>
                                    <td colspan="3"><select name="RealBizAddrOwner_content" class="table-input" id=""
                                            disabled>
                                            <option value="" selected></option>
                                            <option value="A">A:企業</option>
                                            <option value="B">B:負責人</option>
                                            <option value="C">C:負責人配偶</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('RealBizAddrOwner_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">主要營業場所建號-縣市名</td>
                                    <td colspan="3"><input name="CompMajorCityName_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="CompMajorAreaName_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="CompMajorSecName_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="CompMajorSecNo_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><select name="CompMajorOwnership_content" class="table-input" id=""
                                            disabled>
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
                                    <td colspan="3"><select name="CompMajorSetting_content" class="table-input" id=""
                                            disabled>
                                            <option value="" selected></option>
                                            <option value="A">A:無設定</option>
                                            <option value="B">B:第一順位新光</option>
                                            <option value="C">C:第一順位 非新光</option>
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
                                    <td colspan="3"><select name="BizTaxFileWay_content" class="table-input" id=""
                                            disabled>
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
                                    <td colspan="3"><input name="DirectorAName_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="DirectorAId_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="DirectorBName_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="DirectorBId_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="DirectorCName_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="DirectorCId_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="DirectorDName_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="DirectorDId_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="DirectorEName_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="DirectorEId_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="DirectorFName_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="DirectorFId_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="DirectorGName_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="DirectorGId_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="EmployeeNum_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('EmployeeNum_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">股東人數</td>
                                    <td colspan="3"><input name="ShareholderNum_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('ShareholderNum_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="24">
                                        D-身份證
                                    </td>
                                    <td class="bold-right-border">負責人姓名</td>
                                    <td colspan="3"><input name="PrName_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('PrName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人統一編號</td>
                                    <td colspan="3"><input name="PrincipalId_content" type="text" class="table-input"
                                            disabled>
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
                                        <div>負責人出生日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3"><input name="PrBirth_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="PrResAddrZip_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="PrResAddrZipName_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="PrReslAddress_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="GuOneName_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="GuOneId_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="GuOneBirth_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="GuOneResAddrZip_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="GuOneResAddrZipName_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="GuOneReslAddress_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="GuTwoName_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="GuTwoId_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="GuTwoBirth_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="GuTwoResAddrZip_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="GuTwoResAddrZipName_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="GuTwoReslAddress_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="SpouseName_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="SpouseId_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="SpouseBirth_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="SpouseResAddrZip_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="SpouseResAddrZipName_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="SpouseReslAddress_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="PrCurAddrZip_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="PrCurAddrZipName_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="PrCurlAddress_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="PrTelAreaCode_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="PrTelNo_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="PrTelExt_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="PrMobileNo_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><select name="IsPrMarried_content" class="table-input" id=""
                                            disabled>
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
                                    <td colspan="3"><select name="IsPrSpouseGu_content" class="table-input" id=""
                                            disabled>
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
                                    <td colspan="3"><select name="RealPr_content" class="table-input" id="" disabled>
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
                                    <td class="bold-right-border">負責人從事本行業年度</td>
                                    <td colspan="3"><input name="PrStartYear_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><select name="PrEduLevel_content" class="table-input" id=""
                                            disabled>
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
                                    <td colspan="3"><select name="OthRealPrRelWithPr_content" class="table-input" id=""
                                            disabled>
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
                                    <td colspan="3"><input name="OthRealPrName_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="OthRealPrId_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('OthRealPrId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際負責(經營)人_其他實際負責經營人_出生日期</td>
                                    <td colspan="3"><input name="OthRealPrBirth_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('OthRealPrBirth_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際負責(經營)人_其他實際負責經營人_從事本行業年度</td>
                                    <td colspan="3"><input name="OthRealPrStartYear_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="OthRealPrTitle_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="OthRealPrSHRatio_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><select name="GuOneRelWithPr_content" class="table-input" id=""
                                            disabled>
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
                                    <td colspan="3"><select name="GuOneCompany_content" class="table-input" id=""
                                            disabled>
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
                                    <td colspan="3"><select name="GuTwoRelWithPr_content" class="table-input" id=""
                                            disabled>
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
                                    <td colspan="3"><select name="GuTwoCompany_content" class="table-input" id=""
                                            disabled>
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
                                    <td colspan="3"><input name="SpouseCurAddrZip_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="SpouseCurAddrZipName_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="SpouseCurlAddress_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="SpouseTelAreaCode_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="SpouseTelNo_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="SpouseTelExt_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="SpouseMobileNo_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="GuOneCurAddrZip_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="GuOneCurAddrZipName_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="GuOneCurlAddress_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="GuOneTelAreaCode_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="GuOneTelNo_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="GuOneTelExt_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="GuOneMobileNo_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="GuTwoCurAddrZip_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="GuTwoCurAddrZipName_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="GuTwoCurlAddress_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="GuTwoTelAreaCode_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="GuTwoTelNo_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="GuTwoTelExt_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="GuTwoMobileNo_content" type="text" class="table-input"
                                            disabled>
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
                                        F-所得稅申報書<br>
                                        （損益表）
                                    </td>
                                    <td class="bold-right-border">
                                        <div>近一年結算申報書營業收入-年度</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3"><input name="AnnualIncomeYear1_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="AnnualIncome1_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="AnnualIncomeYear2_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="AnnualIncome2_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="AnnualIncomeYear3_content" type="text"
                                            class="table-input" disabled>
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
                                        <input name="AnnualIncome3_content" type="text" class="table-input" disabled>
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
                                    <td colspan="3"><input name="NumOfInsuredYM1_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="NumOfInsured1_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="NumOfInsuredYM2_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="NumOfInsured2_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="NumOfInsuredYM3_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="NumOfInsured3_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="NumOfInsuredYM4_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="NumOfInsured4_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="NumOfInsuredYM5_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="NumOfInsured5_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="NumOfInsuredYM6_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="NumOfInsured6_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="NumOfInsuredYM7_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="NumOfInsured7_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="NumOfInsuredYM8_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="NumOfInsured8_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="NumOfInsuredYM9_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="NumOfInsured9_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="NumOfInsuredYM10_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="NumOfInsured10_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="NumOfInsuredYM11_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="NumOfInsured11_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="NumOfInsuredYM12_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="NumOfInsured12_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="CompJCICQueryDate_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompJCICQueryDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司中期放款餘額-年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="MidTermLnYM_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="MidTermLnBal_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="ShortTermLnYM_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="ShortTermLnBal_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="CompJCICDataDate_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompJCICDataDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">企業信用評分</td>
                                    <td colspan="3"><input name="CompCreditScore_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="PrJCICQueryDate_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="PrCreditScore_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="PrJCICDataDate_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="PrBal_CashCard_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="PrBal_CreditCard_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="PrBal_ShortTermLn_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="PrBal_MidTermLn_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="PrBal_LongTermLn_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="PrBal_ShortTermGuar_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="PrBal_MidTermLnGuar_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="PrBal_LongTermLnGuar_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="SpouseJCICQueryDate_content" type="text"
                                            class="table-input" disabled>
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
                                        <input name="SpouseCreditScore_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="SpouseJCICDataDate_content" type="text"
                                            class="table-input" disabled>
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
                                        <input name="SpouseBal_CashCard_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="SpouseBal_CreditCard_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="SpouseBal_ShortTermLn_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="SpouseBal_MidTermLn_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="SpouseBal_LongTermLn_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="SpouseBal_ShortTermGuar_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="SpouseBal_MidTermLnGuar_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="SpouseBal_LongTermLnGuar_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="GuOneJCICQueryDate_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="GuOneCreditScore_content" type="text" class="table-input" disabled>
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
                                        <input name="GuOneJCICDataDate_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="GuOneBal_CashCard_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="GuOneBal_CreditCard_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="GuOneBal_ShortTermLn_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="GuOneBal_MidTermLn_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="GuOneBal_LongTermLn_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="GuOneBal_ShortTermGuar_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="GuOneBal_MidTermLnGuar_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="GuOneBal_LongTermLnGuar_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="GuTwoJCICQueryDate_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="GuTwoCreditScore_content" type="text" class="table-input" disabled>
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
                                        <input name="GuTwoJCICDataDate_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="GuTwoBal_CashCard_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="GuTwoBal_CreditCard_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="GuTwoBal_ShortTermLn_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="GuTwoBal_MidTermLn_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="GuTwoBal_LongTermLn_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="GuTwoBal_ShortTermGuar_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="GuTwoBal_MidTermLnGuar_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="GuTwoBal_LongTermLnGuar_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="PrBeingOthCompPrId_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="SpouseBeingOthCompPrId_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="PrLaborQryDate_content" type="text" class="table-input" disabled>
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
                                        <input name="PrLaborInsSalary_content" type="text" class="table-input" disabled>
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
                                        <input name="SpouseLaborQryDate_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="SpouseLaborInsSalary_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="GuOneLaborQryDate_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="GuOneLaborInsSalary_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="GuTwoLaborQryDate_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="GuTwoLaborInsSalary_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="OtherMemo_content" type="text" class="table-input" disabled>
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
                    <table class="table table-bordered border-dark">
                        <tbody>
                            <tr>
                                <th class="th bold-bottom-border bold-right-border" style="width: 8%;">附件類別</th>
                                <th class="field_name th bold-bottom-border bold-right-border">圖片</th>
                            </tr>
                            <tr>
                                <td class="title input-center bold-bottom-border bold-right-border">A01:公司變更事項登記卡及工商登記查詢
                                </td>
                                <td class="bold-right-border ">
                                    <fieldset disabled name="A01">
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td class="title input-center bold-bottom-border bold-right-border">
                                    A02:負責人及保證人身分證影本及第二證件、戶役政查詢
                                </td>
                                <td class="bold-right-border ">
                                    <fieldset disabled name="A02">
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td class="title input-center bold-bottom-border bold-right-border">
                                    A03:營業據點建物登記謄本(公司或負責人或保證人自有才須提供)</td>
                                <td class="bold-right-border ">
                                    <fieldset disabled name="A03">
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td class="title input-center bold-bottom-border bold-right-border">A04:近三年公司所得稅申報書</td>
                                <td class="bold-right-border ">
                                    <fieldset disabled name="A04">
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td class="title input-center bold-bottom-border bold-right-border">A05:近12月勞保局投保資料</td>
                                <td class="bold-right-border ">
                                    <fieldset disabled name="A05">
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td class="title input-center bold-bottom-border bold-right-border">
                                    A06:公司、負責人、配偶及保證人的聯徵資料
                                    J01、J02、J10、J20、A13、A11</td>
                                <td class="bold-right-border ">
                                    <fieldset disabled name="A06">
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td class="title input-center bold-bottom-border bold-right-border">
                                    A07:負責人及保證人之被保險人勞保異動查詢
                                </td>
                                <td class="bold-right-border ">
                                    <fieldset disabled name="A07">
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td class="title input-center bold-bottom-border bold-right-border">
                                    A08:公司、負責人及保證人近六個月存摺餘額明細及存摺封面</td>
                                <td class="bold-right-border ">
                                    <fieldset disabled name="A08">
                                    </fieldset>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="page-tab-kgibank" class="nav-page" data-bankid="2">
            <div class="page">
                <div class="subpage api_data_page">
                    <h3 style="text-align: center;">百萬信保檢核表</h3>
                    <div>
                        <table class="table table-bordered border-dark">
                            <tbody>
                                <tr>
                                    <th class="source th bold-bottom-border bold-right-border">資料來源</th>
                                    <th class="field_name th bold-bottom-border bold-right-border">欄位名稱</th>
                                    <th colspan="3" class="content th bold-bottom-border">內容</th>
                                    <th class="edit th bold-bottom-border">人工檢驗</th>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="14">
                                        工商登記<br>
                                        (經濟部API)<br>
                                        (主計處)
                                    </td>
                                    <td class="bold-right-border">公司統一編號</td>
                                    <td colspan="3"><input name="CompId_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司戶名</td>
                                    <td colspan="3"><input name="CompName_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">組織類型</td>

                                    <td colspan="3">
                                        <select name="CompType2_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="A">A:獨資</option>
                                            <option value="B">B:合夥</option>
                                            <option value="C">C:有限公司</option>
                                            <option value="D">D:股份有限公司</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompType2_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司核准設立日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3"><input name="CompSetDate_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompSetDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">依法核准情形</td>

                                    <td colspan="3">
                                        <select name="RegisterType_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="A">A:有公司登記與商業登記</option>
                                            <option value="B">B:取得主管機關核發之營業證照</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('RegisterType_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">是否公開發行</td>

                                    <td colspan="3">
                                        <select name="IsPublic_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('IsPublic_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司實收資本額</td>
                                    <td colspan="3"><input name="CompCapital_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompCapital_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司最後核准變更實收資本額日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3"><input name="LastPaidInCapitalDate_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('LastPaidInCapitalDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業登記地址</td>
                                    <td colspan="3"><input name="BizRegAddress_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('BizRegAddress_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">是否有法人投資</td>

                                    <td colspan="3">
                                        <select name="HasJuridicalInvest_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('HasJuridicalInvest_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">法人投資佔總股份(%)</td>
                                    <td colspan="3"><input name="JuridicalInvestRate_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('JuridicalInvestRate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業稅申報方式</td>
                                    <td colspan="3"><select name="BizTaxFileWay_content" class="table-input" id=""
                                            disabled>
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
                                    <td class="bold-right-border">營業種類標準代碼</td>
                                    <td colspan="3"><input name="BusinessType2_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('BusinessType2_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">是否屬於製造業、營造業或礦業或土石採集業</td>

                                    <td colspan="3">
                                        <select name="IsManufacturing_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('IsManufacturing_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="5">
                                        近三年稅簽
                                    </td>

                                    <td class="bold-right-border">前一年度營業額</td>
                                    <td colspan="3"><input name="LastYearRevenue_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('LastYearRevenue_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">每日營運資金需求量</td>
                                    <td colspan="3"><input name="DailyWorkingCapital_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('DailyWorkingCapital_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">淨營業週期</td>
                                    <td colspan="3"><input name="OperatingCycle_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('OperatingCycle_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負債總額</td>
                                    <td colspan="3"><input name="LiabilitiesAmount_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('LiabilitiesAmount_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">權益總額</td>
                                    <td colspan="3"><input name="EquityAmount_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('EquityAmount_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="31">
                                        企業資料表
                                    </td>

                                    <td class="bold-right-border">公司產業別</td>
                                    <!-- <input id="CompDuType_content" type="text" class="table-input" disabled> -->
                                    <td colspan="3">
                                        <select name="CompDuType_content" class="table-input" disabled>
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
                                                onclick="edit_click('CompDuType_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">受嚴重特殊傳染性肺炎影響之企業</td>
                                    <td colspan="3">
                                        <select name="IsCovidAffected_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('IsCovidAffected_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業登記地址是否自有</td>
                                    <td colspan="3"><select name="IsBizRegAddrSelfOwn_content" class="table-input"
                                            disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:自有</option>
                                            <option value="0">0:非自有</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('IsBizRegAddrSelfOwn_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業登記地址自有-所有權</td>
                                    <td colspan="3"><select name="BizRegAddrOwner_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="A">A:企業</option>
                                            <option value="B">B:負責人</option>
                                            <option value="C">C:負責人配偶</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('BizRegAddrOwner_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際營業地址_是OR否 同營業登記地址</td>
                                    <td colspan="3"><select name="IsBizAddrEqToBizRegAddr_content" class="table-input"
                                            id="" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:同營業登記地址</option>
                                            <option value="0">0:不同於營業登記地址</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('IsBizAddrEqToBizRegAddr_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際營業地址</td>
                                    <td colspan="3"><input id="RealBizAddress_content"
                                            name="RealBizAddrCityName_content" type="text" class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('RealBizAddrCityName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際營業地址是否自有</td>
                                    <td colspan="3"><select name="IsRealBizAddrSelfOwn_content" class="table-input"
                                            id="" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:自有</option>
                                            <option value="0">0:非自有</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('IsRealBizAddrSelfOwn_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際營業地址自有-所有權</td>
                                    <td colspan="3"><select name="RealBizAddrOwner_content" class="table-input" id=""
                                            disabled>
                                            <option value="" selected></option>
                                            <option value="A">A:企業</option>
                                            <option value="B">B:負責人</option>
                                            <option value="C">C:負責人配偶</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('RealBizAddrOwner_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">企業聯絡人電話-區碼</td>
                                    <td colspan="3"><input name="CompTelAreaCode_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompTelAreaCode_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">企業聯絡人電話-電話號碼</td>
                                    <td colspan="3"><input name="CompTelNo_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompTelNo_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">企業聯絡人電話-分機碼</td>
                                    <td colspan="3"><input name="CompTelExt_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompTelExt_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">企業聯絡人姓名</td>
                                    <td colspan="3"><input name="CompContactName_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompContactName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">企業聯絡人職稱</td>
                                    <td colspan="3"><input name="CompContact_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompContact_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">企業財務主管姓名</td>
                                    <td colspan="3"><input name="FinancialOfficerName_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('FinancialOfficerName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">企業財務主管分機</td>
                                    <td colspan="3"><input name="FinancialOfficerExt_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('FinancialOfficerExt_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">企業Email</td>
                                    <td colspan="3"><input name="CompEmail_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompEmail_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">目前員工人數</td>
                                    <td colspan="3"><input name="EmployeeNum_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('EmployeeNum_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">是否有海外投資</td>
                                    <td colspan="3"><select name="HasForeignInvestment_content" class="table-input"
                                            id="" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('HasForeignInvestment_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">是否有關係企業</td>
                                    <td colspan="3"><select name="HasRelatedCompany_content" class="table-input" id=""
                                            disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('HasRelatedCompany_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">關係企業(A)統一編號</td>
                                    <td colspan="3"><input name="RelatedCompAGuiNumber_content" type="text"
                                            class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('RelatedCompAGuiNumber_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">關係企業(A)名稱</td>
                                    <td colspan="3"><input name="RelatedCompAName_content" type="text"
                                            class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('RelatedCompAName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">關係企業(A)組織型態</td>

                                    <td colspan="3">
                                        <select name="RelatedCompAType_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="A">A:獨資</option>
                                            <option value="B">B:合夥</option>
                                            <option value="C">C:有限公司</option>
                                            <option value="D">D:股份有限公司</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('RelatedCompAType_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">關係企業(A)與借戶之關係</td>
                                    <td colspan="3"><input name="RelatedCompARelationship_content" type="text"
                                            class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('RelatedCompARelationship_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">關係企業(B)統一編號</td>
                                    <td colspan="3"><input name="RelatedCompBGuiNumber_content" type="text"
                                            class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('RelatedCompBGuiNumber_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">關係企業(B)名稱</td>
                                    <td colspan="3"><input name="RelatedCompBName_content" type="text"
                                            class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('RelatedCompBName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">關係企業(B)組織型態</td>

                                    <td colspan="3">
                                        <select name="RelatedCompBType_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="A">A:獨資</option>
                                            <option value="B">B:合夥</option>
                                            <option value="C">C:有限公司</option>
                                            <option value="D">D:股份有限公司</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('RelatedCompBType_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">關係企業(B)與借戶之關係</td>
                                    <td colspan="3"><input name="RelatedCompBRelationship_content" type="text"
                                            class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('RelatedCompBRelationship_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">關係企業(C)統一編號</td>
                                    <td colspan="3"><input name="RelatedCompCGuiNumber_content" type="text"
                                            class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('RelatedCompCGuiNumber_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">關係企業(C)名稱</td>
                                    <td colspan="3"><input name="RelatedCompCName_content" type="text"
                                            class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('RelatedCompCName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">關係企業(C)組織型態</td>

                                    <td colspan="3">
                                        <select name="RelatedCompCType_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="A">A:獨資</option>
                                            <option value="B">B:合夥</option>
                                            <option value="C">C:有限公司</option>
                                            <option value="D">D:股份有限公司</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('RelatedCompCType_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">關係企業(C)與借戶之關係</td>
                                    <td colspan="3"><input name="RelatedCompCRelationship_content" type="text"
                                            class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('RelatedCompCRelationship_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="21">
                                        近三年401/403/405
                                    </td>

                                    <td class="bold-right-border">
                                        <div>近一年申報營業稅年份</div>
                                        <div style="color:red;">格式:YYYY</div>
                                    </td>
                                    <td colspan="3">
                                        <input name="BusinessTaxLastOneYear_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('BusinessTaxLastOneYear_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>近二年申報營業稅年份</div>
                                        <div style="color:red;">格式:YYYY</div>
                                    </td>
                                    <td colspan="3">
                                        <input name="BusinessTaxLastTwoYear_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('BusinessTaxLastTwoYear_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>近三年申報營業稅年份</div>
                                        <div style="color:red;">格式:YYYY</div>
                                    </td>
                                    <td colspan="3">
                                        <input name="BusinessTaxLastThreeYear_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('BusinessTaxLastThreeYear_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近一年申報營業稅01~02月開立發票金額</td>
                                    <td colspan="3"><input name="LastOneYearInvoiceAmountM1M2_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('LastOneYearInvoiceAmountM1M2_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近一年申報營業稅03~04月開立發票金額</td>
                                    <td colspan="3"><input name="LastOneYearInvoiceAmountM3M4_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('LastOneYearInvoiceAmountM3M4_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近一年申報營業稅05~06月開立發票金額</td>
                                    <td colspan="3"><input name="LastOneYearInvoiceAmountM5M6_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('LastOneYearInvoiceAmountM5M6_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近一年申報營業稅07~08月開立發票金額</td>
                                    <td colspan="3"><input name="LastOneYearInvoiceAmountM7M8_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('LastOneYearInvoiceAmountM7M8_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近一年申報營業稅09~10月開立發票金額</td>
                                    <td colspan="3"><input name="LastOneYearInvoiceAmountM9M10_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('LastOneYearInvoiceAmountM9M10_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近一年申報營業稅11~12月開立發票金額</td>
                                    <td colspan="3"><input name="LastOneYearInvoiceAmountM11M12_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('LastOneYearInvoiceAmountM11M12_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近二年申報營業稅01~02月開立發票金額</td>
                                    <td colspan="3"><input name="LastTwoYearInvoiceAmountM1M2_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('LastTwoYearInvoiceAmountM1M2_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近二年申報營業稅03~04月開立發票金額</td>
                                    <td colspan="3"><input name="LastTwoYearInvoiceAmountM3M4_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('LastTwoYearInvoiceAmountM3M4_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近二年申報營業稅05~06月開立發票金額</td>
                                    <td colspan="3"><input name="LastTwoYearInvoiceAmountM5M6_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('LastTwoYearInvoiceAmountM5M6_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近二年申報營業稅07~08月開立發票金額</td>
                                    <td colspan="3"><input name="LastTwoYearInvoiceAmountM7M8_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('LastTwoYearInvoiceAmountM7M8_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近二年申報營業稅09~10月開立發票金額</td>
                                    <td colspan="3"><input name="LastTwoYearInvoiceAmountM9M10_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('LastTwoYearInvoiceAmountM9M10_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近二年申報營業稅11~12月開立發票金額</td>
                                    <td colspan="3"><input name="LastTwoYearInvoiceAmountM11M12_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('LastTwoYearInvoiceAmountM11M12_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近三年申報營業稅01~02月開立發票金額</td>
                                    <td colspan="3"><input name="LastThreeYearInvoiceAmountM1M2_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('LastThreeYearInvoiceAmountM1M2_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近三年申報營業稅03~04月開立發票金額</td>
                                    <td colspan="3"><input name="LastThreeYearInvoiceAmountM3M4_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('LastThreeYearInvoiceAmountM3M4_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近三年申報營業稅05~06月開立發票金額</td>
                                    <td colspan="3"><input name="LastThreeYearInvoiceAmountM5M6_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('LastThreeYearInvoiceAmountM5M6_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近三年申報營業稅07~08月開立發票金額</td>
                                    <td colspan="3"><input name="LastThreeYearInvoiceAmountM7M8_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('LastThreeYearInvoiceAmountM7M8_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近三年申報營業稅09~10月開立發票金額</td>
                                    <td colspan="3"><input name="LastThreeYearInvoiceAmountM9M10_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('LastThreeYearInvoiceAmountM9M10_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近三年申報營業稅11~12月開立發票金額</td>
                                    <td colspan="3"><input name="LastThreeYearInvoiceAmountM11M12_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('LastThreeYearInvoiceAmountM11M12_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="4">
                                        負責人身分證
                                    </td>
                                    <td class="bold-right-border">負責人姓名</td>
                                    <td colspan="3"><input name="PrName_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('PrName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人身分證字號</td>
                                    <td colspan="3"><input name="PrincipalId_content" type="text" class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('PrincipalId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人配偶_有 OR 無</td>
                                    <td colspan="3"><select name="IsPrMarried_content" class="table-input" id=""
                                            disabled>
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
                                    <td class="bold-right-border">
                                        <div>負責人出生日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3"><input name="PrBirth_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('PrBirth_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="5">
                                        負責人基本資料
                                    </td>

                                    <td class="bold-right-border">
                                        <div>負責人擔任本企業負責人年度</div>
                                        <div style="color:red;">格式:YYYY</div>
                                    </td>
                                    <td colspan="3"><input name="PrInChargeYear_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('PrInChargeYear_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>負責人從事本行業年度</div>
                                        <div style="color:red;">格式:YYYY</div>
                                    </td>
                                    <td colspan="3"><input name="PrStartYear_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><select name="PrEduLevel_content" class="table-input" id=""
                                            disabled>
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
                                    <td class="bold-right-border">負責人行動電話</td>
                                    <td colspan="3"><input name="PrMobileNo_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('PrMobileNo_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人Email</td>
                                    <td colspan="3"><input name="PrEmail_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('PrEmail_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="2">
                                        負責人配偶身分證
                                    </td>

                                    <td class="bold-right-border">負責人配偶姓名</td>
                                    <td colspan="3"><input name="SpouseName_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('SpouseName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人配偶身分證字號</td>
                                    <td colspan="3"><input name="SpouseId_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('SpouseId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="8">
                                        <div>其他實際負責人</div>
                                        <div>身分證、基本資料</div>
                                    </td>

                                    <td class="bold-right-border">實際負責人是否等於登記負責人</td>

                                    <td colspan="3">
                                        <select name="IsPrRegister_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('IsPrRegister_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">其他實際負責人_與借戶負責人之關係</td>
                                    <td colspan="3"><select name="OthRealPrRelWithPr_content" class="table-input" id=""
                                            disabled>
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
                                    <td class="bold-right-border">其他實際負責人_姓名</td>
                                    <td colspan="3"><input name="OthRealPrName_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('OthRealPrName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">其他實際負責人_身分證字號</td>
                                    <td colspan="3"><input name="OthRealPrId_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('OthRealPrId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">其他實際負責人_出生日期</td>
                                    <td colspan="3"><input name="OthRealPrBirth_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('OthRealPrBirth_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">其他實際負責人_從事本行業年度</td>
                                    <td colspan="3"><input name="OthRealPrStartYear_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('OthRealPrStartYear_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">其他實際負責人_擔任本公司職務</td>
                                    <td colspan="3"><input name="OthRealPrTitle_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('OthRealPrTitle_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">其他實際負責人_持股比率%</td>
                                    <td colspan="3"><input name="OthRealPrSHRatio_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('OthRealPrSHRatio_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="6">
                                        <div>保證人</div>
                                        <div>身分證、基本資料</div>
                                    </td>

                                    <td class="bold-right-border">是否有徵提保證人</td>

                                    <td colspan="3">
                                        <select name="HasGuarantor_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('HasGuarantor_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">配偶是否擔任本案保證人</td>
                                    <td colspan="3"><select name="IsPrSpouseGu_content" class="table-input" id=""
                                            disabled>
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
                                    <td class="bold-right-border">保證人_姓名</td>
                                    <td colspan="3"><input name="GuOneName_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('GuOneName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">保證人_身分證字號</td>
                                    <td colspan="3"><input name="GuOneId_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('GuOneId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">保證人_與借戶負責人之關係</td>
                                    <td colspan="3"><select name="GuOneRelWithPr_content" class="table-input" id=""
                                            disabled>
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
                                    <td class="bold-right-border">保證人_任職公司</td>
                                    <td colspan="3"><select name="GuOneCompany_content" class="table-input" id=""
                                            disabled>
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
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="6">
                                        公司聯徵報告</td>

                                    <td class="bold-right-border">票債信情形是否異常</td>

                                    <td colspan="3">
                                        <select name="JcCompDebtLog_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('JcCompDebtLog_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>聯徵中心資料日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3"><input name="CompJCICQueryDate_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompJCICQueryDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>聯徵J02資料年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="CompJCICDataDate_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompJCICDataDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">企業信用評分</td>
                                    <td colspan="3"><input name="CompCreditScore_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('CompCreditScore_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">授信總餘額</td>
                                    <td colspan="3"><input name="JcCompCreditAmount_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('JcCompCreditAmount_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">往來銀行家數</td>
                                    <td colspan="3"><input name="JcCompBankDealingNum_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('JcCompBankDealingNum_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="13">
                                        負責人聯徵報告</td>

                                    <td class="bold-right-border">票債信情形是否異常</td>

                                    <td colspan="3">
                                        <select name="PrDebtLog_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('PrDebtLog_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>聯徵J02資料年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="PrJ02YM_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('PrJ02YM_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">聯徵信用評分</td>
                                    <td colspan="3"><input name="PrCreditScore_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('PrCreditScore_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">授信總餘額</td>
                                    <td colspan="3"><input name="PrCreditTotalAmount_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('PrCreditTotalAmount_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">現金卡張數</td>
                                    <td colspan="3"><input name="PrCashCardQty_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('PrCashCardQty_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">現金卡可動用額度</td>
                                    <td colspan="3"><input name="PrCashCardAvailLimit_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('PrCashCardAvailLimit_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">現金卡餘額合計</td>
                                    <td colspan="3"><input name="PrBal_CashCard_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('PrBal_CashCard_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">最近一週或一個月還款有無延遲紀錄</td>
                                    <td colspan="3">
                                        <select name="PrHasWeekMonthDelay_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('PrHasWeekMonthDelay_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">信用卡張數</td>
                                    <td colspan="3"><input name="PrCreditCardQty_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('PrCreditCardQty_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">信用卡可動用額度</td>
                                    <td colspan="3"><input name="PrCreditCardAvailAmount_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('PrCreditCardAvailAmount_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">信用卡餘額合計</td>
                                    <td colspan="3"><input name="PrBal_CreditCard_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('PrBal_CreditCard_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">最近三個月內還款有無延遲紀錄</td>
                                    <td colspan="3">
                                        <select name="PrHasLastThreeMonthDelay_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('PrHasLastThreeMonthDelay_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人擔任其他企業負責人之企業統編</td>
                                    <td colspan="3">
                                        <input name="PrBeingOthCompPrId_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('PrBeingOthCompPrId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="4">
                                        負責人配偶聯徵報告</td>

                                    <td class="bold-right-border">票債信情形是否異常</td>

                                    <td colspan="3">
                                        <select name="SpouseDebtLog_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('SpouseDebtLog_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>聯徵J02資料年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="SpouseJ02YM_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('SpouseJ02YM_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">聯徵信用評分</td>
                                    <td colspan="3">
                                        <input name="SpouseCreditScore_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('SpouseCreditScore_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人配偶擔任其他企業負責人之企業統編</td>
                                    <td colspan="3">
                                        <input name="SpouseBeingOthCompPrId_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('SpouseBeingOthCompPrId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="3">
                                        保證人聯徵報告</td>

                                    <td class="bold-right-border">票債信情形是否異常</td>

                                    <td colspan="3">
                                        <select name="GuarantorDebtLog_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('GuarantorDebtLog_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>聯徵J02資料年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3">
                                        <input name="GuarantorJ02YM_content" type="text" class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('GuarantorJ02YM_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">聯徵信用評分</td>
                                    <td colspan="3">
                                        <input name="GuOneCreditScore_content" type="text" class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('GuOneCreditScore_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="24">
                                        勞保局投保人數
                                    </td>
                                    <td class="bold-right-border">
                                        <div>公司近01個月投保人數-年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="NumOfInsuredYM1_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="NumOfInsured1_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="NumOfInsuredYM2_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="NumOfInsured2_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="NumOfInsuredYM3_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('NumOfInsuredYM3_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司近03個月投保人數-人數</td>
                                    <td colspan="3"><input name="NumOfInsured3_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="NumOfInsuredYM4_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('NumOfInsuredYM4_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司近04個月投保人數-人數
                                    </td>
                                    <td colspan="3"><input name="NumOfInsured4_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="NumOfInsuredYM5_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="NumOfInsured5_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="NumOfInsuredYM6_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="NumOfInsured6_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="NumOfInsuredYM7_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="NumOfInsured7_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="NumOfInsuredYM8_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="NumOfInsured8_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="NumOfInsuredYM9_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="NumOfInsured9_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="NumOfInsuredYM10_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="NumOfInsured10_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="NumOfInsuredYM11_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="NumOfInsured11_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="NumOfInsuredYM12_content" type="text"
                                            class="table-input" disabled>
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
                                    <td colspan="3"><input name="NumOfInsured12_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('NumOfInsured12_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>


                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="6">
                                        勞保異動明細
                                    </td>
                                    <td class="bold-right-border">
                                        <div>負責人-被保險人勞保異動查詢日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3">
                                        <input name="PrLaborQryDate_content" type="text" class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('PrLaborQryDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人-被保險人勞保異動查詢-最近期投保薪資-人數</td>
                                    <td colspan="3"><input name="PrLaborInsSalary_content" type="text"
                                            class="table-input" disabled>
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
                                        <input name="SpouseLaborQryDate_content" type="text" class="table-input"
                                            disabled>
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
                                        <input name="SpouseLaborInsSalary_content" type="text" class="table-input"
                                            disabled>
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
                                        <div>保證人-被保險人勞保異動查詢日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3">
                                        <input name="GuOneLaborQryDate_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('GuOneLaborQryDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">保證人-被保險人勞保異動查詢-最近期投保薪資</td>
                                    <td colspan="3">
                                        <input name="GuOneLaborInsSalary_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('GuOneLaborInsSalary_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border">J-其他</td>
                                    <td class="bold-right-border">其他備註</td>
                                    <td colspan="3">
                                        <input name="OtherMemo_content" type="text" class="table-input" disabled>
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
                    <table class="table table-bordered border-dark">
                        <tbody>
                            <tr>
                                <th class="th bold-bottom-border bold-right-border" style="width: 8%;">附件類別</th>
                                <th class="field_name th bold-bottom-border bold-right-border">圖片</th>
                            </tr>
                            <tr>
                                <td class="title input-center bold-bottom-border bold-right-border">A01:公司變更事項登記卡及工商登記查詢
                                </td>
                                <td class="bold-right-border ">
                                    <fieldset disabled name="A01">
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td class="title input-center bold-bottom-border bold-right-border">
                                    A02:負責人身分證 + 健保卡
                                </td>
                                <td class="bold-right-border ">
                                    <fieldset disabled name="B02">
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td class="title input-center bold-bottom-border bold-right-border">
                                    A03:負責人配偶身分證 + 健保卡</td>
                                <td class="bold-right-border ">
                                    <fieldset disabled name="B03">
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td class="title input-center bold-bottom-border bold-right-border">A04:保證人身分證 + 健保卡
                                </td>
                                <td class="bold-right-border ">
                                    <fieldset disabled name="B04">
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td class="title input-center bold-bottom-border bold-right-border">A05:營業據點建物登記謄本
                                    (公司或負責人或保證人自有)</td>
                                <td class="bold-right-border ">
                                    <fieldset disabled name="A03">
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td class="title input-center bold-bottom-border bold-right-border">A06:近三年稅捐機關納稅證明(税簽)
                                </td>
                                <td class="bold-right-border ">
                                    <fieldset disabled name="A04">
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td class="title input-center bold-bottom-border bold-right-border">A07:近12個月員工投保單位人數資料
                                </td>
                                <td class="bold-right-border ">
                                    <fieldset disabled name="A05">
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td class="title input-center bold-bottom-border bold-right-border">A08:公司聯徵資料</td>
                                <td class="bold-right-border ">
                                    <fieldset disabled name="B08">
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td class="title input-center bold-bottom-border bold-right-border">A09:負責人聯徵資料</td>
                                <td class="bold-right-border ">
                                    <fieldset disabled name="B09">
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td class="title input-center bold-bottom-border bold-right-border">A10:負責人配偶聯徵資料</td>
                                <td class="bold-right-border ">
                                    <fieldset disabled name="B10">
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td class="title input-center bold-bottom-border bold-right-border">A11:保證人聯徵資料</td>
                                <td class="bold-right-border ">
                                    <fieldset disabled name="B11">
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td class="title input-center bold-bottom-border bold-right-border">
                                    A12:負責人及保證人之被保險人勞保異動查詢</td>
                                <td class="bold-right-border ">
                                    <fieldset disabled name="A07">
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td class="title input-center bold-bottom-border bold-right-border">A13:公司近六個月往來存摺影本+內頁
                                </td>
                                <td class="bold-right-border ">
                                    <fieldset disabled name="B13">
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td class="title input-center bold-bottom-border bold-right-border">A14:負責人近六個月往來存摺影本+內頁
                                </td>
                                <td class="bold-right-border ">
                                    <fieldset disabled name="B14">
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td class="title input-center bold-bottom-border bold-right-border">A15:保證人近六個月往來存摺影本+內頁
                                </td>
                                <td class="bold-right-border ">
                                    <fieldset disabled name="B15">
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td class="title input-center bold-bottom-border bold-right-border">A16:近三年 401/403/405
                                    表</td>
                                <td class="bold-right-border ">
                                    <fieldset disabled name="B16">
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
    // let hasClick=false;
    let urlString;
    let url;
    let target_id;
    let table_type;
    let data_type;
    let msg_no;
    let msg_data;
    let all_data = {};
    let IDs = [];
    let key = '';
    let value = '';
    let image_list_data = [];
    // 下拉選單列表
    let select_array = ['CompType_content', 'BusinessType_content', 'CompDuType_content', 'BizRegAddrOwner_content', 'IsBizAddrEqToBizRegAddr_content', 'BizTaxFileWay_content',
        'IsPrMarried_content', 'IsPrSpouseGu_content', 'RealPr_content', 'IsBizRegAddrSelfOwn_content', 'IsRealBizAddrSelfOwn_content', 'RealBizAddrOwner_content', 'OthRealPrRelWithPr_content',
        'GuOneRelWithPr_content', 'GuOneCompany_content', 'GuTwoRelWithPr_content', 'GuTwoCompany_content', 'PrEduLevel_content', 'CompMajorOwnership_content', 'CompMajorSetting_content',
        'CompType2_content', 'RegisterType_content', 'IsPublic_content', 'HasJuridicalInvest_content', 'IsManufacturing_content', 'IsCovidAffected_content', 'HasForeignInvestment_content', 'HasRelatedCompany_content',
        'RelatedCompAType_content', 'RelatedCompBType_content', 'RelatedCompCType_content', 'IsPrRegister_content', 'OthRealPrRelWithPr_content', 'HasGuarantor_content', 'IsPrSpouseGu_content',
        'JcCompDebtLog_content', 'PrDebtLog_content', 'PrHasWeekMonthDelay_content', 'PrHasLastThreeMonthDelay_content', 'SpouseDebtLog_content', 'GuarantorDebtLog_content'];
    // 原始檔案圖片-附件資料表
    let rawData_array = [
        'A01', 'A02', 'A03', 'A04', 'A05', 'A06', 'A07', 'A08', 'B02', 'B03', 'B08', 'B09', 'B10', 'B11', 'B13', 'B14', 'B15', 'B16',
    ];
    // 送出時為數字欄位
    let is_int_array = ['CompCapital', 'AnnualIncome1', 'AnnualIncome2', 'AnnualIncome3', 'NumOfInsured1', 'NumOfInsured2', 'NumOfInsured3', 'NumOfInsured4', 'NumOfInsured5', 'NumOfInsured6', 'NumOfInsured7', 'NumOfInsured8', 'NumOfInsured9', 'NumOfInsured10', 'NumOfInsured11', 'NumOfInsured12', 'MidTermLnBal', 'ShortTermLnBal', 'CompCreditScore', 'PrLaborInsSalary', 'SpouseLaborInsSalary', 'GuOneLaborInsSalary', 'GuTwoLaborInsSalary', 'PrCreditScore', 'PrBal_CashCard', 'PrBal_CreditCard', 'PrBal_ShortTermLn', 'PrBal_MidTermLn', 'PrBal_LongTermLn', 'PrBal_ShortTermGuar', 'PrBal_MidTermLnGuar', 'PrBal_LongTermLnGuar', 'SpouseCreditScore', 'SpouseBal_CashCard', 'SpouseBal_CreditCard', 'SpouseBal_ShortTermLn', 'SpouseBal_MidTermLn', 'SpouseBal_LongTermLn', 'SpouseBal_ShortTermGuar', 'SpouseBal_MidTermLnGuar', 'SpouseBal_LongTermLnGuar', 'GuOneCreditScore', 'GuOneBal_CashCard', 'GuOneBal_CreditCard', 'GuOneBal_ShortTermLn', 'GuOneBal_MidTermLn', 'GuOneBal_LongTermLn', 'GuOneBal_ShortTermGuar', 'GuOneBal_MidTermLnGuar', 'GuOneBal_LongTermLnGuar', 'GuTwoCreditScore', 'GuTwoBal_CashCard', 'GuTwoBal_CreditCard', 'GuTwoBal_ShortTermLn', 'GuTwoBal_MidTermLn', 'GuTwoBal_LongTermLn', 'GuTwoBal_ShortTermGuar', 'GuTwoBal_MidTermLnGuar', 'GuTwoBal_LongTermLnGuar', 'IsPrMarried', 'IsBizRegAddrSelfOwn', 'IsBizAddrEqToBizRegAddr', 'IsRealBizAddrSelfOwn', 'OthRealPrSHRatio',
        'HasJuridicalInvest', 'IsManufacturing', 'LastYearRevenue', 'DailyWorkingCapital', 'OperatingCycle', 'LiabilitiesAmount', 'EquityAmount', 'IsCovidAffected', 'EmployeeNum', 'HasForeignInvestment', 'HasRelatedCompany', 'BusinessTaxLastOneYear', 'BusinessTaxLastTwoYear', 'BusinessTaxLastThreeYear',
        'LastOneYearInvoiceAmountM1M2', 'LastOneYearInvoiceAmountM3M4'];
    function edit_click(id_content) {
        $(`[name=${id_content}]`).each((i, e) => { $(e).prop('disabled', function (i, v) { return !v; }) })
    }

    function changeTab(tab) {
        $(`#nav-tab-skbank`).removeClass('active')
        $(`#nav-tab-kgibank`).removeClass('active')
        $(`#nav-${tab}`).toggleClass('active')
        $('.nav-page').each((i, e) => {
            $(e).hide()
        })
        $(`#page-${tab}`).show()
    }

    function fillReport(data) {
        Object.keys(data).forEach(function (key) {
            if ($(`[name=${key}]`).length) {
                if (rawData_array.includes($(`[name=${key}]`).attr('name'))) {
                    Object.keys(data[key]).forEach(function (key1) {
                        var a_tag = `<a href="${data[key][key1]}" data-fancybox="images">
                                <img id="${key}_${key1}"  src="${data[key][key1]}" style='width:30%;max-width:400px'>
                            </a>`;
                        console.log($("#"+key+"_"+key1).length );
                        if($("#"+key+"_"+key1).length === 0) {
                            $(`[name=${key}]`).each((i, e) => $(e).append(a_tag))
                        }
                    })
                }
                if (!rawData_array.includes($(`[name=${key}]`).attr('name'))) {
                    $(`[name=${key}]`).val(data[key]);
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
    function getCheckLisTexttData(bank_num) {
        let selectIds = []
        let all_data = {}
        $(`[data-bankid=${bank_num}] input, [data-bankid=${bank_num}] select`).each(function () { if (this.name) selectIds.push(this.name) })
        selectIds.forEach((item, index) => {
            if (item) {
                if (item.match(/.*-selectized/g)) {
                    key = item.replace(/-selectized/g, '');
                } else {
                    key = item;
                }
                value = $(`[name="${key}"]`).eq(0).val();
                key = key.replace(/_content/g, '');
                if (is_int_array.includes(key)) {
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
        $(".api_file_page").find("img").each(function () { IDs.push(this.id); });
        IDs.forEach((item, index) => {
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
            data: request_data,
            dataType: "json",
            success: function (response) {
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
                all_data = getCheckLisTexttData(bank_num);
                data_type = 'text';
                getMappingMsgNo(target_id, 'send', data_type, bank_num, function (data) {
                    msg_data = data;

                    if (!msg_data) {
                        return 'no response';
                    }

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
                    request_data = {
                        'MsgNo': msg_no,
                        'CompId': `${all_data.CompId}`,
                        'PrincipalId': `${all_data.PrincipalId}`,
                    }
                    delete all_data.CompId;
                    delete all_data.PrincipalId;
                    request_data.Data = all_data;
                    request_data = JSON.stringify(request_data);

                    save_response = saveCheckListData(msg_no, 'text', request_data, bank_num);

                    console.log(save_response);
                    // $.ajax({
                    //     type: "POST",
                    //     data: request_data,
                    //     url: '/api/skbank/v1/LoanRequest/apply_text',
                    //     dataType: "json",
                    //     success: function (response) {
                    //         $('#msg_no').val(response.msg_no);
                    //         $('#case_no').val(response.case_no);
                    //         alert(`新光送出結果 ： ${response.success}\n回應內容 ： ${response.error}\n新光案件編號 ： ${response.case_no}\n新光交易序號 ： ${response.msg_no}\n新光送出資料資訊 ： ${response.meta_info}\n`);
                    //     },
                    //     error: function(error) {
                    //       alert(error);
                    //     }
                    // });

                    $("#text_list").val("儲存資料");
                    $(".sendBtn").prop("disabled", false);
                    resolve()
                });
            })
        }
        if (send_type == 'text_list') {
			// 1=sk 2=kgi
			for (const bank of [1,2]) {
				await sendData(bank)
			}
        }
        if (send_type == 'image_list') {
            $("#image_list").val("資料處理中");
            all_data = getCheckListImagesData();
            request_data = [];
            image_list_data = [];
            case_no = $('#case_no').val();
            data_count = Object.keys(all_data).length;
            let compId_imput = $('#CompId_content').val();

            if (case_no) {
                Object.keys(all_data).forEach((key) => {
                    new_string = key.split('_');
                    data_type = new_string[0];
                    getMappingMsgNo(target_id, 'send', key, bank_num, (data) => {
                        msg_data = data;
                        msg_no = msg_data.data.msg_no;
                        request_data.push({
                            'MsgNo': msg_no,
                            'CompId': compId_imput,
                            'CaseNo': case_no,
                            'DocType': new_string[0],
                            'DocSeq': parseInt(new_string[1]) + 1,
                            'DocFileType': 4,
                            'DocUrl': all_data[key]
                        });
                        // if(Object.keys(request_data).length == data_count){
                        //     image_list_data = JSON.stringify({"request_image_list":request_data});
                        //     $.ajax({
                        //         type: "POST",
                        //         data: image_list_data,
                        //         url: '/api/skbank/v1/LoanRequest/apply_image_list',
                        //         dataType: "json",
                        //         success: function (response) {
                        //           alert(response);
                        //         },
                        //         error: function(error) {
                        //           alert(error);
                        //         }
                        //     });
                        //     $("#image_list").val("儲存資料");
                        //     $(".sendBtn").prop("disabled", false);
                        // }
                    });
                })

            }
        }
    }

    $(document).ready(function () {
        urlString = window.location.href;
        url = new URL(urlString);
        target_id = url.searchParams.get("target_id");
        table_type = url.searchParams.get("table_type");
        IDs = []
        $("body").find("input").each(function () { if (this.name) IDs.push(this.name); });
        $("body").find("select").each(function () { if (this.name) IDs.push(this.name); });
        $(`#nav-tab-skbank`).click()
        for (const bank of [1,2]) {
            fetchReport(target_id, table_type, bank, function (data) {
                if (!data) {
                    alert('can\'t not get response');
                    return;
                }
                console.log(data);
                if (data.status.code != '200') {
                    alert(data.response); return;
                }
                if (data.status.code == '200' && data.response) {
                    fillReport(data.response);
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
            //console.log(bank);
            save(this.id);
        });

    });
</script>

</html>

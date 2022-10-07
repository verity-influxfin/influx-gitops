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
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="31">
                                        A-工商登記<br>
                                        (經濟部API)<br>
                                        (主計處)
                                    </td>
                                    <td class="bold-right-border">公司統一編號</td>
                                    <td colspan="3"><input name="compId_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司戶名</td>
                                    <td colspan="3"><input name="compName_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司核准設立日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3"><input name="compSetDate_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compSetDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司實收資本額</td>
                                    <td colspan="3"><input name="compCapital_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compCapital_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司行業別(主計處)</td>
                                    <td colspan="3"><input name="compIdustry_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compIdustry_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司型態</td>

                                    <td colspan="3">
                                        <select name="compType_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="41">41:獨資</option>
                                            <option value="21">21:中小企業</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compType_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司產業別</td>
                                    <!-- <input id="compDuType_content" type="text" class="table-input" disabled> -->
                                    <td colspan="3">
                                        <select name="compDuType_content" class="table-input" disabled>
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
                                    <!-- <input id="businessType_content" type="text" class="table-input" disabled>  -->
                                    <td colspan="3">
                                        <select name="businessType_content" class="table-input" disabled>
                                            <option value="A">A:製造</option>
                                            <option value="B">B:買賣</option>
                                            <option value="C">C:其他</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('businessType_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司登記地址-郵遞區號</td>
                                    <td colspan="3"><input name="compRegAddrZip_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compRegAddrZip_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司登記地址-郵遞區號名稱</td>
                                    <td colspan="3"><input name="compRegAddrZipName_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compRegAddrZipName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司登記地址-非郵遞地址資料</td>
                                    <td colspan="3"><input name="compRegAddress_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compRegAddress_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>現任負責人擔任公司起日-日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3"><input name="prOnboardDay_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prOnboardDay_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">現任負責人擔任公司起日-姓名</td>
                                    <td colspan="3"><input name="prOnboardName_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prOnboardName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>前任負責人擔任公司起日-日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3"><input name="exPrOnboardDay_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('exPrOnboardDay_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">前任負責人擔任公司起日-姓名</td>
                                    <td colspan="3"><input name="exPrOnboardName_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('exPrOnboardName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>前二任負責人擔任公司起日-日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3"><input name="exPrOnboardDay2_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('exPrOnboardDay2_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">前二任負責人擔任公司起日-姓名</td>
                                    <td colspan="3"><input name="exPrOnboardName2_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('exPrOnboardName2_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業登記地址_選擇縣市</td>
                                    <td colspan="3"><input name="bizRegAddrCityName_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('bizRegAddrCityName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業登記地址_選擇鄉鎮市區</td>
                                    <td colspan="3"><input name="bizRegAddrAreaName_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('bizRegAddrAreaName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業登記地址_路街名稱(不含路、街)</td>
                                    <td colspan="3"><input name="bizRegAddrAreaName_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('bizRegAddrRoadName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業登記地址_路 OR 街</td>
                                    <td colspan="3"><input name="bizRegAddrAreaName_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('bizRegAddrRoadType_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業登記地址_段</td>
                                    <td colspan="3"><input name="bizRegAddrSec_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('bizRegAddrSec_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業登記地址_巷</td>
                                    <td colspan="3"><input name="bizRegAddrLn_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('bizRegAddrLn_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業登記地址_弄</td>
                                    <td colspan="3"><input name="bizRegAddrAly_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('bizRegAddrAly_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業登記地址_號(不含之號)</td>
                                    <td colspan="3"><input name="bizRegAddrNo_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('bizRegAddrNo_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業登記地址_之號</td>
                                    <td colspan="3"><input name="bizRegAddrNoExt_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('bizRegAddrNoExt_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業登記地址_樓(不含之樓、室)</td>
                                    <td colspan="3"><input name="bizRegAddrFloor_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('bizRegAddrFloor_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業登記地址_之樓</td>
                                    <td colspan="3"><input name="bizRegAddrFloorExt_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('bizRegAddrFloorExt_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業登記地址_室</td>
                                    <td colspan="3"><input name="bizRegAddrRoom_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('bizRegAddrRoom_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業登記地址_其他備註</td>
                                    <td colspan="3"><input name="bizRegAddrOtherMemo_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('bizRegAddrOtherMemo_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司最後核准變更實收資本額日期</td>
                                    <td colspan="3"><input name="lastPaidInCapitalDate_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('lastPaidInCapitalDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="47">
                                        B-企業資料表
                                    </td>
                                    <td class="bold-right-border">公司主要營業場所-郵遞區號</td>
                                    <td colspan="3"><input name="compMajorAddrZip_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compMajorAddrZip_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司主要營業場所-郵遞區號名稱</td>
                                    <td colspan="3"><input name="compMajorAddrZipName_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compMajorAddrZipName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司主要營業場所-非郵遞地址資料</td>
                                    <td colspan="3"><input name="compMajorAddress_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compMajorAddress_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司連絡電話-區碼</td>
                                    <td colspan="3"><input name="compTelAreaCode_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compTelAreaCode_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司連絡電話-電話號碼</td>
                                    <td colspan="3"><input name="compTelNo_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compTelNo_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司連絡電話-分機碼</td>
                                    <td colspan="3"><input name="compTelExt_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compTelExt_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業登記地址</td>
                                    <td colspan="3"><select name="isBizRegAddrSelfOwn_content" class="table-input"
                                            disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:自有</option>
                                            <option value="0">0:非自有</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('isBizRegAddrSelfOwn_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業登記地址_自有</td>
                                    <td colspan="3"><select name="bizRegAddrOwner_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="A">A:企業</option>
                                            <option value="B">B:負責人</option>
                                            <option value="C">C:負責人配偶</option>
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
                                    <td colspan="3"><select name="isBizAddrEqToBizRegAddr_content" class="table-input"
                                            id="" disabled>
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
                                    <td colspan="3"><input id="realBizAddrCityName_content"
                                            name="realBizAddrCityName_content" type="text" class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('realBizAddrCityName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際營業地址_選擇鄉鎮市區</td>
                                    <td colspan="3"><input id="realBizAddrAreaName_content"
                                            name="realBizAddrAreaName_content" type="text" class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('realBizAddrAreaName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際營業地址_路街名稱(不含路、街)</td>
                                    <td colspan="3"><input id="realBizAddrRoadName_content"
                                            name="realBizAddrRoadName_content" type="text" class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('realBizAddrRoadName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際營業地址_路 OR 街</td>
                                    <td colspan="3"><input id="realBizAddrRoadType_content"
                                            name="realBizAddrRoadType_content" type="text" class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('realBizAddrRoadType_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際營業地址_段</td>
                                    <td colspan="3"><input id="realBizAddrSec_content" name="realBizAddrSec_content"
                                            type="text" class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('realBizAddrSec_content')" value="Edit">
                                        </center>
                                    </td>
                                <tr>
                                    <td class="bold-right-border">實際營業地址_巷</td>
                                    <td colspan="3"><input name="realBizAddrLn_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('realBizAddrLn_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際營業地址_弄</td>
                                    <td colspan="3"><input name="realBizAddrAly_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('realBizAddrAly_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際營業地址_號(不含之號)</td>
                                    <td colspan="3"><input name="realBizAddrNo_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('realBizAddrNo_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際營業地址_之號</td>
                                    <td colspan="3"><input name="realBizAddrNoExt_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('realBizAddrNoExt_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際營業地址_樓(不含之樓、室)</td>
                                    <td colspan="3"><input name="realBizAddrFloor_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('realBizAddrFloor_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際營業地址_之樓</td>
                                    <td colspan="3"><input name="realBizAddrFloorExt_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('realBizAddrFloorExt_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際營業地址_室</td>
                                    <td colspan="3"><input name="realBizAddrRoom_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('realBizAddrRoom_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際營業地址_其他備註</td>
                                    <td colspan="3"><input name="realBizAddrOtherMemo_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('realBizAddrOtherMemo_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際營業地址</td>
                                    <td colspan="3"><select name="isRealBizAddrSelfOwn_content" class="table-input"
                                            id="" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:自有</option>
                                            <option value="0">0:非自有</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('isRealBizAddrSelfOwn_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際營業地址_自有</td>
                                    <td colspan="3"><select name="realBizAddrOwner_content" class="table-input" id=""
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
                                                onclick="edit_click('realBizAddrOwner_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">主要營業場所建號-縣市名</td>
                                    <td colspan="3"><input name="compMajorCityName_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compMajorCityName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">主要營業場所建號-地區</td>
                                    <td colspan="3"><input name="compMajorAreaName_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compMajorAreaName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">主要營業場所建號-段名</td>
                                    <td colspan="3"><input name="compMajorSecName_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compMajorSecName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">主要營業場所建號-段號</td>
                                    <td colspan="3"><input name="compMajorSecNo_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compMajorSecNo_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">主要營業場所所有權</td>
                                    <td colspan="3"><select name="compMajorOwnership_content" class="table-input" id=""
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
                                                onclick="edit_click('compMajorOwnership_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業場所設定</td>
                                    <td colspan="3"><select name="compMajorSetting_content" class="table-input" id=""
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
                                                onclick="edit_click('compMajorSetting_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業稅申報方式</td>
                                    <td colspan="3"><select name="bizTaxFileWay_content" class="table-input" id=""
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
                                                onclick="edit_click('bizTaxFileWay_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司董監事 A 姓名</td>
                                    <td colspan="3"><input name="directorAName_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('directorAName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司董監事 A 統編</td>
                                    <td colspan="3"><input name="directorAId_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('directorAId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司董監事 B 姓名</td>
                                    <td colspan="3"><input name="directorBName_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('directorBName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司董監事 B 統編</td>
                                    <td colspan="3"><input name="directorBId_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('directorBId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司董監事 C 姓名</td>
                                    <td colspan="3"><input name="directorCName_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('directorCName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司董監事 C 統編</td>
                                    <td colspan="3"><input name="directorCId_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('directorCId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司董監事 D 姓名</td>
                                    <td colspan="3"><input name="directorDName_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('directorDName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司董監事 D 統編</td>
                                    <td colspan="3"><input name="directorDId_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('directorDId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司董監事 E 姓名</td>
                                    <td colspan="3"><input name="directorEName_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('directorEName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司董監事 E 統編</td>
                                    <td colspan="3"><input name="directorEId_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('directorEId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司董監事 F 姓名</td>
                                    <td colspan="3"><input name="directorFName_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('directorFName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司董監事 F 統編</td>
                                    <td colspan="3"><input name="directorFId_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('directorFId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司董監事 G 姓名</td>
                                    <td colspan="3"><input name="directorGName_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('directorGName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司董監事 G 統編</td>
                                    <td colspan="3"><input name="directorGId_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('directorGId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">員工人數</td>
                                    <td colspan="3"><input name="employeeNum_content" type="text" class="table-input"
                                            disabled>
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
                                    <td colspan="3"><input name="shareholderNum_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('shareholderNum_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="24">
                                        D-身份證
                                    </td>
                                    <td class="bold-right-border">負責人姓名</td>
                                    <td colspan="3"><input name="prName_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人統一編號</td>
                                    <td colspan="3"><input name="principalId_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('principalId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>負責人出生日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3"><input name="prBirth_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prBirth_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人戶籍地址-郵遞區號</td>
                                    <td colspan="3"><input name="prResAddrZip_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prResAddrZip_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人戶籍地址-郵遞區號名稱</td>
                                    <td colspan="3"><input name="prResAddrZipName_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prResAddrZipName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人戶籍地址-非郵遞地址資料</td>
                                    <td colspan="3"><input name="prReslAddress_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prReslAddress_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">甲保證人姓名</td>
                                    <td colspan="3"><input name="guOneName_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">甲保證人統一編號</td>
                                    <td colspan="3"><input name="guOneId_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>甲保證人出生日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3"><input name="guOneBirth_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneBirth_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">甲保證人戶籍地址-郵遞區號</td>
                                    <td colspan="3"><input name="guOneResAddrZip_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneResAddrZip_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">甲保證人戶籍地址-郵遞區號名稱</td>
                                    <td colspan="3"><input name="guOneResAddrZipName_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneResAddrZipName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">甲保證人戶籍地址-非郵遞地址資料</td>
                                    <td colspan="3"><input name="guOneReslAddress_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneReslAddress_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">乙保證人姓名</td>
                                    <td colspan="3"><input name="guTwoName_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guTwoName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">乙保證人統一編號</td>
                                    <td colspan="3"><input name="guTwoId_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guTwoId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>乙保證人出生日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3"><input name="guTwoBirth_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guTwoBirth_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">乙保證人戶籍地址-郵遞區號</td>
                                    <td colspan="3"><input name="guTwoResAddrZip_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guTwoResAddrZip_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">乙保證人戶籍地址-郵遞區號名稱</td>
                                    <td colspan="3"><input name="guTwoResAddrZipName_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guTwoResAddrZipName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">乙保證人戶籍地址-非郵遞地址資料</td>
                                    <td colspan="3"><input name="guTwoReslAddress_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guTwoReslAddress_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">配偶姓名</td>
                                    <td colspan="3"><input name="spouseName_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">配偶統一編號</td>
                                    <td colspan="3"><input name="spouseId_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>配偶出生日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3"><input name="spouseBirth_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseBirth_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">配偶戶籍地址-郵遞區號</td>
                                    <td colspan="3"><input name="spouseResAddrZip_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseResAddrZip_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">配偶戶籍地址-郵遞區號名稱</td>
                                    <td colspan="3"><input name="spouseResAddrZipName_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseResAddrZipName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">配偶戶籍地址-非郵遞地址資料</td>
                                    <td colspan="3"><input name="spouseReslAddress_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseReslAddress_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="44">
                                        E-個人資料表
                                    </td>
                                    <td class="bold-right-border">負責人現居地址-郵遞區號</td>
                                    <td colspan="3"><input name="prCurAddrZip_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prCurAddrZip_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人現居地址-郵遞區號名稱</td>
                                    <td colspan="3"><input name="prCurAddrZipName_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prCurAddrZipName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人現居地址-非郵遞地址資料</td>
                                    <td colspan="3"><input name="prCurlAddress_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prCurlAddress_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人連絡電話-區碼</td>
                                    <td colspan="3"><input name="prTelAreaCode_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prTelAreaCode_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人連絡電話-電話號碼</td>
                                    <td colspan="3"><input name="prTelNo_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prTelNo_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人連絡電話-分機碼</td>
                                    <td colspan="3"><input name="prTelExt_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prTelExt_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人連絡行動電話</td>
                                    <td colspan="3"><input name="prMobileNo_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prMobileNo_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人配偶_有 OR 無</td>
                                    <td colspan="3"><select name="isPrMarried_content" class="table-input" id=""
                                            disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:有</option>
                                            <option value="0">0:無</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('isPrMarried_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">配偶是否擔任本案保證人</td>
                                    <td colspan="3"><select name="isPrSpouseGu_content" class="table-input" id=""
                                            disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('isPrSpouseGu_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">本公司實際負責人</td>
                                    <td colspan="3"><select name="realPr_content" class="table-input" id="" disabled>
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
                                                onclick="edit_click('realPr_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人從事本行業年度</td>
                                    <td colspan="3"><input name="prStartYear_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prStartYear_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人學歷</td>
                                    <td colspan="3"><select name="prEduLevel_content" class="table-input" id=""
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
                                                onclick="edit_click('prEduLevel_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際負責(經營)人_其他實際負責經營人_與借戶負責人之關係</td>
                                    <td colspan="3"><select name="othRealPrRelWithPr_content" class="table-input" id=""
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
                                                onclick="edit_click('othRealPrRelWithPr_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際負責(經營)人_其他實際負責經營人_姓名</td>
                                    <td colspan="3"><input name="othRealPrName_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('othRealPrName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際負責(經營)人_其他實際負責經營人_身分證字號</td>
                                    <td colspan="3"><input name="othRealPrId_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('othRealPrId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際負責(經營)人_其他實際負責經營人_出生日期</td>
                                    <td colspan="3"><input name="othRealPrBirth_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('othRealPrBirth_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際負責(經營)人_其他實際負責經營人_從事本行業年度</td>
                                    <td colspan="3"><input name="othRealPrStartYear_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('othRealPrStartYear_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際負責(經營)人_其他實際負責經營人_擔任本公司職務</td>
                                    <td colspan="3"><input name="othRealPrTitle_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('othRealPrTitle_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際負責(經營)人_其他實際負責經營人_持股比率%</td>
                                    <td colspan="3"><input name="othRealPrSHRatio_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('othRealPrSHRatio_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">保證人甲_與借戶負責人之關係</td>
                                    <td colspan="3"><select name="guOneRelWithPr_content" class="table-input" id=""
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
                                                onclick="edit_click('guOneRelWithPr_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">保證人甲_任職公司</td>
                                    <td colspan="3"><select name="guOneCompany_content" class="table-input" id=""
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
                                                onclick="edit_click('guOneCompany_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">保證人乙_與借戶負責人之關係</td>
                                    <td colspan="3"><select name="guTwoRelWithPr_content" class="table-input" id=""
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
                                                onclick="edit_click('guTwoRelWithPr_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">保證人乙_任職公司</td>
                                    <td colspan="3"><select name="guTwoCompany_content" class="table-input" id=""
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
                                                onclick="edit_click('guTwoCompany_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">配偶現居地址-郵遞區號</td>
                                    <td colspan="3"><input name="spouseCurAddrZip_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseCurAddrZip_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">配偶現居地址-郵遞區號名稱</td>
                                    <td colspan="3"><input name="spouseCurAddrZipName_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseCurAddrZipName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">配偶現居地址-非郵遞地址資料</td>
                                    <td colspan="3"><input name="spouseCurlAddress_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseCurlAddress_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">配偶連絡電話-區碼</td>
                                    <td colspan="3"><input name="spouseTelAreaCode_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseTelAreaCode_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">配偶連絡電話-電話號碼</td>
                                    <td colspan="3"><input name="spouseTelNo_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseTelNo_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">配偶連絡電話-分機碼</td>
                                    <td colspan="3"><input name="spouseTelExt_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseTelExt_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">配偶連絡行動電話</td>
                                    <td colspan="3"><input name="spouseMobileNo_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseMobileNo_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">甲保證人現居地址-郵遞區號</td>
                                    <td colspan="3"><input name="guOneCurAddrZip_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneCurAddrZip_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">甲保證人現居地址-郵遞區號名稱</td>
                                    <td colspan="3"><input name="guOneCurAddrZipName_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneCurAddrZipName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">甲保證人現居地址-非郵遞地址資料</td>
                                    <td colspan="3"><input name="guOneCurlAddress_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneCurlAddress_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">甲保證人連絡電話-區碼</td>
                                    <td colspan="3"><input name="guOneTelAreaCode_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneTelAreaCode_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">甲保證人連絡電話-電話號碼</td>
                                    <td colspan="3"><input name="guOneTelNo_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneTelNo_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">甲保證人連絡電話-分機碼</td>
                                    <td colspan="3"><input name="guOneTelExt_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneTelExt_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">甲保證人連絡行動電話</td>
                                    <td colspan="3"><input name="guOneMobileNo_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneMobileNo_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">乙保證人現居地址-郵遞區號</td>
                                    <td colspan="3"><input name="guTwoCurAddrZip_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guTwoCurAddrZip_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">乙保證人現居地址-郵遞區號名稱</td>
                                    <td colspan="3"><input name="guTwoCurAddrZipName_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guTwoCurAddrZipName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">乙保證人現居地址-非郵遞地址資料</td>
                                    <td colspan="3"><input name="guTwoCurlAddress_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guTwoCurlAddress_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">乙保證人連絡電話-區碼</td>
                                    <td colspan="3"><input name="guTwoTelAreaCode_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guTwoTelAreaCode_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">乙保證人連絡電話-電話號碼</td>
                                    <td colspan="3"><input name="guTwoTelNo_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guTwoTelNo_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">乙保證人連絡電話-分機碼</td>
                                    <td colspan="3"><input name="guTwoTelExt_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guTwoTelExt_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">乙保證人連絡行動電話</td>
                                    <td colspan="3"><input name="guTwoMobileNo_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guTwoMobileNo_content')" value="Edit">
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
                                    <td colspan="3"><input name="annualIncomeYear1_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('annualIncomeYear1_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近一年結算申報書營業收入-營收</td>
                                    <td colspan="3"><input name="annualIncome1_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('annualIncome1_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>近二年結算申報書營業收入-年度</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3"><input name="annualIncomeYear2_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('annualIncomeYear2_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近二年結算申報書營業收入-營收</td>
                                    <td colspan="3"><input name="annualIncome2_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('annualIncome2_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>近三年結算申報書營業收入-年度</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3"><input name="annualIncomeYear3_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('annualIncomeYear3_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-bottom-border bold-right-border">近三年結算申報書營業收入-營收</td>
                                    <td colspan="3" class="bold-bottom-border">
                                        <input name="annualIncome3_content" type="text" class="table-input" disabled>
                                    </td>
                                    <td class="bold-bottom-border">
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('annualIncome3_content')" value="Edit">
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
                                    <td colspan="3"><input name="numOfInsuredYM1_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsuredYM1_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司近01個月投保人數-人數</td>
                                    <td colspan="3"><input name="numOfInsured1_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsured1_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司近02個月投保人數-年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="numOfInsuredYM2_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsuredYM2_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司近02個月投保人數-人數</td>
                                    <td colspan="3"><input name="numOfInsured2_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsured2_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司近03個月投保人數-年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="numOfInsuredYM3_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsuredYM3_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司近03個月投保人數-人數</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="numOfInsured3_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsured3_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司近04個月投保人數-年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="numOfInsuredYM4_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsuredYM4_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司近04個月投保人數-人數</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="numOfInsured4_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsured4_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司近05個月投保人數-年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="numOfInsuredYM5_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsuredYM5_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司近05個月投保人數-人數</td>
                                    <td colspan="3"><input name="numOfInsured5_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsured5_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司近06個月投保人數-年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="numOfInsuredYM6_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsuredYM6_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司近06個月投保人數-人數</td>
                                    <td colspan="3"><input name="numOfInsured6_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsured6_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司近07個月投保人數-年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="numOfInsuredYM7_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsuredYM7_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司近07個月投保人數-人數</td>
                                    <td colspan="3"><input name="numOfInsured7_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsured7_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司近08個月投保人數-年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="numOfInsuredYM8_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsuredYM8_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司近08個月投保人數-人數</td>
                                    <td colspan="3"><input name="numOfInsured8_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsured8_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司近09個月投保人數-年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="numOfInsuredYM9_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsuredYM9_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司近09個月投保人數-人數</td>
                                    <td colspan="3"><input name="numOfInsured9_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsured9_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司近10個月投保人數-年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="numOfInsuredYM10_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsuredYM10_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司近10個月投保人數-人數</td>
                                    <td colspan="3"><input name="numOfInsured10_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsured10_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司近11個月投保人數-年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="numOfInsuredYM11_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsuredYM11_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司近11個月投保人數-人數</td>
                                    <td colspan="3"><input name="numOfInsured11_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsured11_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司近12個月投保人數-年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="numOfInsuredYM12_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsuredYM12_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司近12個月投保人數-人數</td>
                                    <td colspan="3"><input name="numOfInsured12_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsured12_content')" value="Edit">
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
                                    <td colspan="3"><input name="compJCICQueryDate_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compJCICQueryDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司中期放款餘額-年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="midTermLnYM_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('midTermLnYM_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司中期放款餘額</td>
                                    <td colspan="3"><input name="midTermLnBal_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('midTermLnBal_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司短期放款餘額-年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="shortTermLnYM_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('shortTermLnYM_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司短期放款餘額</td>
                                    <td colspan="3"><input name="shortTermLnBal_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('shortTermLnBal_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>企業聯徵J02資料年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="compJCICDataDate_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compJCICDataDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">企業信用評分</td>
                                    <td colspan="3"><input name="compCreditScore_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compCreditScore_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>負責人聯徵查詢日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3"><input name="prJCICQueryDate_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prJCICQueryDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人聯徵信用評分</td>
                                    <td colspan="3"><input name="prCreditScore_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prCreditScore_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>負責人聯徵J01資料年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="prJCICDataDate_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prJCICDataDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人銀行借款餘額(現金卡)</td>
                                    <td colspan="3"><input name="prBal_CashCard_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prBal_CashCard_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人銀行借款餘額(信用卡)</td>
                                    <td colspan="3"><input name="prBal_CreditCard_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prBal_CreditCard_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人銀行借款餘額(短放)</td>
                                    <td colspan="3"><input name="prBal_ShortTermLn_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prBal_ShortTermLn_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人銀行借款餘額(中放)</td>
                                    <td colspan="3"><input name="prBal_MidTermLn_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prBal_MidTermLn_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人銀行借款餘額(長放)</td>
                                    <td colspan="3"><input name="prBal_LongTermLn_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prBal_LongTermLn_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人銀行借款餘額(短擔)</td>
                                    <td colspan="3"><input name="prBal_ShortTermGuar_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prBal_ShortTermGuar_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人銀行借款餘額(中擔)</td>
                                    <td colspan="3"><input name="prBal_MidTermLnGuar_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prBal_MidTermLnGuar_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人銀行借款餘額(長擔)</td>
                                    <td colspan="3"><input name="prBal_LongTermLnGuar_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prBal_LongTermLnGuar_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>配偶聯徵查詢日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3"><input name="spouseJCICQueryDate_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseJCICQueryDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">配偶聯徵信用評分</td>
                                    <td colspan="3">
                                        <input name="spouseCreditScore_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseCreditScore_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>配偶聯徵J01資料年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="spouseJCICDataDate_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseJCICDataDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">配偶銀行借款餘額(現金卡)</td>
                                    <td colspan="3">
                                        <input name="spouseBal_CashCard_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseBal_CashCard_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">配偶銀行借款餘額(信用卡)</td>
                                    <td colspan="3">
                                        <input name="spouseBal_CreditCard_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseBal_CreditCard_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">配偶銀行借款餘額(短放)</td>
                                    <td colspan="3">
                                        <input name="spouseBal_ShortTermLn_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseBal_ShortTermLn_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">配偶銀行借款餘額(中放)</td>
                                    <td colspan="3">
                                        <input name="spouseBal_MidTermLn_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseBal_MidTermLn_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">配偶銀行借款餘額(長放)</td>
                                    <td colspan="3">
                                        <input name="spouseBal_LongTermLn_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseBal_LongTermLn_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">配偶銀行借款餘額(短擔)</td>
                                    <td colspan="3">
                                        <input name="spouseBal_ShortTermGuar_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseBal_ShortTermGuar_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">配偶銀行借款餘額(中擔)</td>
                                    <td colspan="3">
                                        <input name="spouseBal_MidTermLnGuar_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseBal_MidTermLnGuar_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">配偶銀行借款餘額(長擔)</td>
                                    <td colspan="3">
                                        <input name="spouseBal_LongTermLnGuar_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseBal_LongTermLnGuar_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>甲保證人聯徵查詢日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3">
                                        <input name="guOneJCICQueryDate_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneJCICQueryDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">甲保證人聯徵信用評分</td>
                                    <td colspan="3">
                                        <input name="guOneCreditScore_content" type="text" class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneCreditScore_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>甲保證人聯徵J01資料年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3">
                                        <input name="guOneJCICDataDate_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneJCICDataDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">甲保證人銀行借款餘額(現金卡)</td>
                                    <td colspan="3">
                                        <input name="guOneBal_CashCard_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneBal_CashCard_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">甲保證人銀行借款餘額(信用卡)</td>
                                    <td colspan="3">
                                        <input name="guOneBal_CreditCard_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneBal_CreditCard_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">甲保證人銀行借款餘額(短放)</td>
                                    <td colspan="3">
                                        <input name="guOneBal_ShortTermLn_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneBal_ShortTermLn_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">甲保證人銀行借款餘額(中放)</td>
                                    <td colspan="3">
                                        <input name="guOneBal_MidTermLn_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneBal_MidTermLn_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">甲保證人銀行借款餘額(長放)</td>
                                    <td colspan="3">
                                        <input name="guOneBal_LongTermLn_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneBal_LongTermLn_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">甲保證人銀行借款餘額(短擔)</td>
                                    <td colspan="3">
                                        <input name="guOneBal_ShortTermGuar_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneBal_ShortTermGuar_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">甲保證人銀行借款餘額(中擔)</td>
                                    <td colspan="3">
                                        <input name="guOneBal_MidTermLnGuar_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneBal_MidTermLnGuar_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">甲保證人銀行借款餘額(長擔)</td>
                                    <td colspan="3">
                                        <input name="guOneBal_LongTermLnGuar_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneBal_LongTermLnGuar_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>乙保證人聯徵查詢日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3">
                                        <input name="guTwoJCICQueryDate_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guTwoJCICQueryDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">乙保證人聯徵信用評分</td>
                                    <td colspan="3">
                                        <input name="guTwoCreditScore_content" type="text" class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guTwoCreditScore_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>乙保證人聯徵J01資料年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3">
                                        <input name="guTwoJCICDataDate_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guTwoJCICDataDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">乙保證人銀行借款餘額(現金卡)</td>
                                    <td colspan="3">
                                        <input name="guTwoBal_CashCard_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guTwoBal_CashCard_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">乙保證人銀行借款餘額(信用卡)</td>
                                    <td colspan="3">
                                        <input name="guTwoBal_CreditCard_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guTwoBal_CreditCard_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">乙保證人銀行借款餘額(短放)</td>
                                    <td colspan="3">
                                        <input name="guTwoBal_ShortTermLn_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guTwoBal_ShortTermLn_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">乙保證人銀行借款餘額(中放)</td>
                                    <td colspan="3">
                                        <input name="guTwoBal_MidTermLn_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guTwoBal_MidTermLn_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">乙保證人銀行借款餘額(長放)</td>
                                    <td colspan="3">
                                        <input name="guTwoBal_LongTermLn_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guTwoBal_LongTermLn_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">乙保證人銀行借款餘額(短擔)</td>
                                    <td colspan="3">
                                        <input name="guTwoBal_ShortTermGuar_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guTwoBal_ShortTermGuar_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">乙保證人銀行借款餘額(中擔)</td>
                                    <td colspan="3">
                                        <input name="guTwoBal_MidTermLnGuar_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guTwoBal_MidTermLnGuar_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">乙保證人銀行借款餘額(長擔)</td>
                                    <td colspan="3">
                                        <input name="guTwoBal_LongTermLnGuar_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guTwoBal_LongTermLnGuar_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人擔任其他企業負責人之企業統編</td>
                                    <td colspan="3">
                                        <input name="prBeingOthCompPrId_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prBeingOthCompPrId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">配偶擔任其他企業負責人之企業統編</td>
                                    <td colspan="3">
                                        <input name="spouseBeingOthCompPrId_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseBeingOthCompPrId_content')" value="Edit">
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
                                        <input name="prLaborQryDate_content" type="text" class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prLaborQryDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人-被保險人勞保異動查詢-最近期投保薪資</td>
                                    <td colspan="3">
                                        <input name="prLaborInsSalary_content" type="text" class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prLaborInsSalary_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>配偶-被保險人勞保異動查詢日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3">
                                        <input name="spouseLaborQryDate_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseLaborQryDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">配偶-被保險人勞保異動查詢-最近期投保薪資</td>
                                    <td colspan="3">
                                        <input name="spouseLaborInsSalary_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseLaborInsSalary_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>甲保證人-被保險人勞保異動查詢日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3">
                                        <input name="guOneLaborQryDate_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneLaborQryDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">甲保證人-被保險人勞保異動查詢-最近期投保薪資</td>
                                    <td colspan="3">
                                        <input name="guOneLaborInsSalary_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneLaborInsSalary_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>乙保證人-被保險人勞保異動查詢日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3">
                                        <input name="guTwoLaborQryDate_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guTwoLaborQryDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">乙保證人-被保險人勞保異動查詢-最近期投保薪資</td>
                                    <td colspan="3">
                                        <input name="guTwoLaborInsSalary_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guTwoLaborInsSalary_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border">J-其他</td>
                                    <td class="bold-right-border">其他備註</td>
                                    <td colspan="3">
                                        <input name="otherMemo_content" type="text" class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('otherMemo_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="input-width">
                            <tr>
                                <td class="white-border">
                                    <center>
                                        <input id="text_list" type="button" class="sendBtn" value="儲存資料" disabled>
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
                                    <td colspan="3"><input name="compId_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司戶名</td>
                                    <td colspan="3"><input name="compName_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">組織類型</td>

                                    <td colspan="3">
                                        <select name="compType2_content" class="table-input" disabled>
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
                                                onclick="edit_click('compType2_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司核准設立日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3"><input name="compSetDate_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compSetDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">依法核准情形</td>

                                    <td colspan="3">
                                        <select name="registerType_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="A">A:有公司登記與商業登記</option>
                                            <option value="B">B:取得主管機關核發之營業證照</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('registerType_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">是否公開發行</td>

                                    <td colspan="3">
                                        <select name="isPublic_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('isPublic_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司實收資本額</td>
                                    <td colspan="3"><input name="compCapital_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compCapital_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司最後核准變更實收資本額日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3"><input name="lastPaidInCapitalDate_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('lastPaidInCapitalDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業登記地址</td>
                                    <td colspan="3"><input name="bizRegAddress_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('bizRegAddress_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">是否有法人投資</td>

                                    <td colspan="3">
                                        <select name="hasJuridicalInvest_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('hasJuridicalInvest_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">法人投資佔總股份(%)</td>
                                    <td colspan="3"><input name="juridicalInvestRate_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('juridicalInvestRate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業稅申報方式</td>
                                    <td colspan="3"><select name="bizTaxFileWay_content" class="table-input" id=""
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
                                                onclick="edit_click('bizTaxFileWay_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業種類標準代碼</td>
                                    <td colspan="3"><input name="businessType2_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('businessType2_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">是否屬於製造業、營造業或礦業或土石採集業</td>

                                    <td colspan="3">
                                        <select name="isManufacturing_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('isManufacturing_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="5">
                                        近三年稅簽
                                    </td>

                                    <td class="bold-right-border">前一年度營業額</td>
                                    <td colspan="3"><input name="lastOneYearRevenue_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('lastOneYearRevenue_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">每日營運資金需求量</td>
                                    <td colspan="3"><input name="dailyWorkingCapital_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('dailyWorkingCapital_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">淨營業週期</td>
                                    <td colspan="3"><input name="operatingCycle_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('operatingCycle_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負債總額</td>
                                    <td colspan="3"><input name="liabilitiesAmount_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('liabilitiesAmount_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">權益總額</td>
                                    <td colspan="3"><input name="equityAmount_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('equityAmount_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="33">
                                        企業資料表
                                    </td>

                                    <td class="bold-right-border">公司產業別</td>
                                    <!-- <input id="compDuType_content" type="text" class="table-input" disabled> -->
                                    <td colspan="3">
                                        <select name="compDuType_content" class="table-input" disabled>
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
                                    <td class="bold-right-border">受嚴重特殊傳染性肺炎影響之企業</td>
                                    <td colspan="3">
                                        <select name="isCovidAffected_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('isCovidAffected_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業登記地址是否自有</td>
                                    <td colspan="3"><select name="isBizRegAddrSelfOwn_content" class="table-input"
                                            disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:自有</option>
                                            <option value="0">0:非自有</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('isBizRegAddrSelfOwn_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">營業登記地址自有-所有權</td>
                                    <td colspan="3"><select name="bizRegAddrOwner_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="A">A:企業</option>
                                            <option value="B">B:負責人</option>
                                            <option value="C">C:負責人配偶</option>
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
                                    <td colspan="3"><select name="isBizAddrEqToBizRegAddr_content" class="table-input"
                                            id="" disabled>
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
                                    <td class="bold-right-border">實際營業地址</td>
                                    <td colspan="3"><input id="realBizAddress_content"
                                            name="realBizAddrCityName_content" type="text" class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('realBizAddrCityName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際營業地址是否自有</td>
                                    <td colspan="3"><select name="isRealBizAddrSelfOwn_content" class="table-input"
                                            id="" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:自有</option>
                                            <option value="0">0:非自有</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('isRealBizAddrSelfOwn_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">實際營業地址自有-所有權</td>
                                    <td colspan="3"><select name="realBizAddrOwner_content" class="table-input" id=""
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
                                                onclick="edit_click('realBizAddrOwner_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">企業聯絡人電話-區碼</td>
                                    <td colspan="3"><input name="compTelAreaCode_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compTelAreaCode_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">企業聯絡人電話-電話號碼</td>
                                    <td colspan="3"><input name="compTelNo_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compTelNo_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">企業聯絡人電話-分機碼</td>
                                    <td colspan="3"><input name="compTelExt_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compTelExt_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">企業聯絡人姓名</td>
                                    <td colspan="3"><input name="compContactName_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compContactName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">企業聯絡人職稱</td>
                                    <td colspan="3"><input name="compContact_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compContact_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">企業財務主管姓名</td>
                                    <td colspan="3"><input name="financialOfficerName_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('financialOfficerName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">企業財務主管分機</td>
                                    <td colspan="3"><input name="financialOfficerExt_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('financialOfficerExt_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">企業Email</td>
                                    <td colspan="3"><input name="compEmail_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compEmail_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">目前員工人數</td>
                                    <td colspan="3"><input name="employeeNum_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('employeeNum_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">是否有海外投資</td>
                                    <td colspan="3"><select name="hasForeignInvestment_content" class="table-input"
                                            id="" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('hasForeignInvestment_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">是否有關係企業</td>
                                    <td colspan="3"><select name="hasRelatedCompany_content" class="table-input" id=""
                                            disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('hasRelatedCompany_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">是否擁有信用瑕疵</td>
                                    <td colspan="3"><select name="hasCreditFlaws_content" class="table-input" id=""
                                                            disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                   onclick="edit_click('hasCreditFlaws_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近一年平均員工人數是否超過200人</td>
                                    <td colspan="3"><select name="lastOneYearOver200employees_content" class="table-input" id=""
                                                            disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                   onclick="edit_click('lastOneYearOver200employees_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">關係企業(A)統一編號</td>
                                    <td colspan="3"><input name="relatedCompAGuiNumber_content" type="text"
                                            class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('relatedCompAGuiNumber_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">關係企業(A)名稱</td>
                                    <td colspan="3"><input name="relatedCompAName_content" type="text"
                                            class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('relatedCompAName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">關係企業(A)組織型態</td>

                                    <td colspan="3">
                                        <select name="relatedCompAType_content" class="table-input" disabled>
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
                                                onclick="edit_click('relatedCompAType_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">關係企業(A)與借戶之關係</td>
                                    <td colspan="3"><input name="relatedCompARelationship_content" type="text"
                                            class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('relatedCompARelationship_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">關係企業(B)統一編號</td>
                                    <td colspan="3"><input name="relatedCompBGuiNumber_content" type="text"
                                            class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('relatedCompBGuiNumber_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">關係企業(B)名稱</td>
                                    <td colspan="3"><input name="relatedCompBName_content" type="text"
                                            class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('relatedCompBName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">關係企業(B)組織型態</td>

                                    <td colspan="3">
                                        <select name="relatedCompBType_content" class="table-input" disabled>
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
                                                onclick="edit_click('relatedCompBType_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">關係企業(B)與借戶之關係</td>
                                    <td colspan="3"><input name="relatedCompBRelationship_content" type="text"
                                            class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('relatedCompBRelationship_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">關係企業(C)統一編號</td>
                                    <td colspan="3"><input name="relatedCompCGuiNumber_content" type="text"
                                            class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('relatedCompCGuiNumber_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">關係企業(C)名稱</td>
                                    <td colspan="3"><input name="relatedCompCName_content" type="text"
                                            class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('relatedCompCName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">關係企業(C)組織型態</td>

                                    <td colspan="3">
                                        <select name="relatedCompCType_content" class="table-input" disabled>
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
                                                onclick="edit_click('relatedCompCType_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">關係企業(C)與借戶之關係</td>
                                    <td colspan="3"><input name="relatedCompCRelationship_content" type="text"
                                            class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('relatedCompCRelationship_content')" value="Edit">
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
                                        <input name="businessTaxLastOneYear_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('businessTaxLastOneYear_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>近二年申報營業稅年份</div>
                                        <div style="color:red;">格式:YYYY</div>
                                    </td>
                                    <td colspan="3">
                                        <input name="businessTaxLastTwoYear_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('businessTaxLastTwoYear_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>近三年申報營業稅年份</div>
                                        <div style="color:red;">格式:YYYY</div>
                                    </td>
                                    <td colspan="3">
                                        <input name="businessTaxLastThreeYear_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('businessTaxLastThreeYear_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近一年申報營業稅01~02月開立發票金額</td>
                                    <td colspan="3"><input name="lastOneYearInvoiceAmountM1M2_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('lastOneYearInvoiceAmountM1M2_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近一年申報營業稅03~04月開立發票金額</td>
                                    <td colspan="3"><input name="lastOneYearInvoiceAmountM3M4_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('lastOneYearInvoiceAmountM3M4_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近一年申報營業稅05~06月開立發票金額</td>
                                    <td colspan="3"><input name="lastOneYearInvoiceAmountM5M6_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('lastOneYearInvoiceAmountM5M6_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近一年申報營業稅07~08月開立發票金額</td>
                                    <td colspan="3"><input name="lastOneYearInvoiceAmountM7M8_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('lastOneYearInvoiceAmountM7M8_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近一年申報營業稅09~10月開立發票金額</td>
                                    <td colspan="3"><input name="lastOneYearInvoiceAmountM9M10_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('lastOneYearInvoiceAmountM9M10_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近一年申報營業稅11~12月開立發票金額</td>
                                    <td colspan="3"><input name="lastOneYearInvoiceAmountM11M12_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('lastOneYearInvoiceAmountM11M12_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近二年申報營業稅01~02月開立發票金額</td>
                                    <td colspan="3"><input name="lastTwoYearInvoiceAmountM1M2_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('lastTwoYearInvoiceAmountM1M2_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近二年申報營業稅03~04月開立發票金額</td>
                                    <td colspan="3"><input name="lastTwoYearInvoiceAmountM3M4_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('lastTwoYearInvoiceAmountM3M4_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近二年申報營業稅05~06月開立發票金額</td>
                                    <td colspan="3"><input name="lastTwoYearInvoiceAmountM5M6_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('lastTwoYearInvoiceAmountM5M6_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近二年申報營業稅07~08月開立發票金額</td>
                                    <td colspan="3"><input name="lastTwoYearInvoiceAmountM7M8_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('lastTwoYearInvoiceAmountM7M8_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近二年申報營業稅09~10月開立發票金額</td>
                                    <td colspan="3"><input name="lastTwoYearInvoiceAmountM9M10_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('lastTwoYearInvoiceAmountM9M10_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近二年申報營業稅11~12月開立發票金額</td>
                                    <td colspan="3"><input name="lastTwoYearInvoiceAmountM11M12_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('lastTwoYearInvoiceAmountM11M12_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近三年申報營業稅01~02月開立發票金額</td>
                                    <td colspan="3"><input name="lastThreeYearInvoiceAmountM1M2_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('lastThreeYearInvoiceAmountM1M2_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近三年申報營業稅03~04月開立發票金額</td>
                                    <td colspan="3"><input name="lastThreeYearInvoiceAmountM3M4_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('lastThreeYearInvoiceAmountM3M4_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近三年申報營業稅05~06月開立發票金額</td>
                                    <td colspan="3"><input name="lastThreeYearInvoiceAmountM5M6_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('lastThreeYearInvoiceAmountM5M6_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近三年申報營業稅07~08月開立發票金額</td>
                                    <td colspan="3"><input name="lastThreeYearInvoiceAmountM7M8_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('lastThreeYearInvoiceAmountM7M8_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近三年申報營業稅09~10月開立發票金額</td>
                                    <td colspan="3"><input name="lastThreeYearInvoiceAmountM9M10_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('lastThreeYearInvoiceAmountM9M10_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">近三年申報營業稅11~12月開立發票金額</td>
                                    <td colspan="3"><input name="lastThreeYearInvoiceAmountM11M12_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('lastThreeYearInvoiceAmountM11M12_content')"
                                                value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="4">
                                        負責人身分證
                                    </td>
                                    <td class="bold-right-border">負責人姓名</td>
                                    <td colspan="3"><input name="prName_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人身分證字號</td>
                                    <td colspan="3"><input name="principalId_content" type="text" class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('principalId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人配偶_有 OR 無</td>
                                    <td colspan="3"><select name="isPrMarried_content" class="table-input" id=""
                                            disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:有</option>
                                            <option value="0">0:無</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('isPrMarried_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>負責人出生日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3"><input name="prBirth_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prBirth_content')" value="Edit">
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
                                    <td colspan="3"><input name="prInChargeYear_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prInChargeYear_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>負責人從事本行業年度</div>
                                        <div style="color:red;">格式:YYYY</div>
                                    </td>
                                    <td colspan="3"><input name="prStartYear_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prStartYear_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人學歷</td>
                                    <td colspan="3"><select name="prEduLevel_content" class="table-input" id=""
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
                                                onclick="edit_click('prEduLevel_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人行動電話</td>
                                    <td colspan="3"><input name="prMobileNo_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prMobileNo_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人Email</td>
                                    <td colspan="3"><input name="prEmail_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prEmail_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="2">
                                        負責人配偶身分證
                                    </td>

                                    <td class="bold-right-border">負責人配偶姓名</td>
                                    <td colspan="3"><input name="spouseName_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人配偶身分證字號</td>
                                    <td colspan="3"><input name="spouseId_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseId_content')" value="Edit">
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
                                        <select name="isPrRegister_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('isPrRegister_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">其他實際負責人_與借戶負責人之關係</td>
                                    <td colspan="3"><select name="othRealPrRelWithPr_content" class="table-input" id=""
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
                                                onclick="edit_click('othRealPrRelWithPr_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">其他實際負責人_姓名</td>
                                    <td colspan="3"><input name="othRealPrName_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('othRealPrName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">其他實際負責人_身分證字號</td>
                                    <td colspan="3"><input name="othRealPrId_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('othRealPrId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">其他實際負責人_出生日期</td>
                                    <td colspan="3"><input name="othRealPrBirth_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('othRealPrBirth_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">其他實際負責人_從事本行業年度</td>
                                    <td colspan="3"><input name="othRealPrStartYear_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('othRealPrStartYear_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">其他實際負責人_擔任本公司職務</td>
                                    <td colspan="3"><input name="othRealPrTitle_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('othRealPrTitle_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">其他實際負責人_持股比率%</td>
                                    <td colspan="3"><input name="othRealPrSHRatio_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('othRealPrSHRatio_content')" value="Edit">
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
                                        <select name="hasGuarantor_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('hasGuarantor_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">配偶是否擔任本案保證人</td>
                                    <td colspan="3"><select name="isPrSpouseGu_content" class="table-input" id=""
                                            disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('isPrSpouseGu_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">保證人_姓名</td>
                                    <td colspan="3"><input name="guOneName_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneName_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">保證人_身分證字號</td>
                                    <td colspan="3"><input name="guOneId_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">保證人_與借戶負責人之關係</td>
                                    <td colspan="3"><select name="guOneRelWithPr_content" class="table-input" id=""
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
                                                onclick="edit_click('guOneRelWithPr_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">保證人_任職公司</td>
                                    <td colspan="3"><select name="guOneCompany_content" class="table-input" id=""
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
                                                onclick="edit_click('guOneCompany_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="6">
                                        公司聯徵報告</td>

                                    <td class="bold-right-border">票債信情形是否異常</td>

                                    <td colspan="3">
                                        <select name="jcCompDebtLog_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('jcCompDebtLog_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>聯徵中心資料日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3"><input name="compJCICQueryDate_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compJCICQueryDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>聯徵J02資料年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="compJCICDataDate_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compJCICDataDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">企業信用評分</td>
                                    <td colspan="3"><input name="compCreditScore_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('compCreditScore_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">授信總餘額</td>
                                    <td colspan="3"><input name="jcCompCreditAmount_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('jcCompCreditAmount_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">往來銀行家數</td>
                                    <td colspan="3"><input name="jcCompBankDealingNum_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('jcCompBankDealingNum_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="13">
                                        負責人聯徵報告</td>

                                    <td class="bold-right-border">票債信情形是否異常</td>

                                    <td colspan="3">
                                        <select name="prDebtLog_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prDebtLog_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>聯徵J02資料年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="prJ02YM_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prJ02YM_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">聯徵信用評分</td>
                                    <td colspan="3"><input name="prCreditScore_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prCreditScore_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">授信總餘額</td>
                                    <td colspan="3"><input name="prCreditTotalAmount_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prCreditTotalAmount_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">現金卡張數</td>
                                    <td colspan="3"><input name="prCashCardQty_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prCashCardQty_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">現金卡可動用額度</td>
                                    <td colspan="3"><input name="prCashCardAvailLimit_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prCashCardAvailLimit_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">現金卡餘額合計</td>
                                    <td colspan="3"><input name="prBal_CashCard_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prBal_CashCard_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">最近一週或一個月還款有無延遲紀錄</td>
                                    <td colspan="3">
                                        <select name="prHasWeekMonthDelay_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prHasWeekMonthDelay_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">信用卡張數</td>
                                    <td colspan="3"><input name="prCreditCardQty_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prCreditCardQty_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">信用卡可動用額度</td>
                                    <td colspan="3"><input name="prCreditCardAvailAmount_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prCreditCardAvailAmount_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">信用卡餘額合計</td>
                                    <td colspan="3"><input name="prBal_CreditCard_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prBal_CreditCard_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">最近三個月內還款有無延遲紀錄</td>
                                    <td colspan="3">
                                        <select name="prHasLastThreeMonthDelay_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prHasLastThreeMonthDelay_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人擔任其他企業負責人之企業統編</td>
                                    <td colspan="3">
                                        <input name="prBeingOthCompPrId_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prBeingOthCompPrId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="4">
                                        負責人配偶聯徵報告</td>

                                    <td class="bold-right-border">票債信情形是否異常</td>

                                    <td colspan="3">
                                        <select name="spouseDebtLog_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseDebtLog_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>聯徵J02資料年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="spouseJ02YM_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseJ02YM_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">聯徵信用評分</td>
                                    <td colspan="3">
                                        <input name="spouseCreditScore_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseCreditScore_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人配偶擔任其他企業負責人之企業統編</td>
                                    <td colspan="3">
                                        <input name="spouseBeingOthCompPrId_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseBeingOthCompPrId_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border" rowspan="3">
                                        保證人聯徵報告</td>

                                    <td class="bold-right-border">票債信情形是否異常</td>

                                    <td colspan="3">
                                        <select name="guarantorDebtLog_content" class="table-input" disabled>
                                            <option value="" selected></option>
                                            <option value="1">1:是</option>
                                            <option value="0">0:否</option>
                                        </select>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guarantorDebtLog_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>聯徵J02資料年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3">
                                        <input name="guarantorJ02YM_content" type="text" class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guarantorJ02YM_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">聯徵信用評分</td>
                                    <td colspan="3">
                                        <input name="guOneCreditScore_content" type="text" class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneCreditScore_content')" value="Edit">
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
                                    <td colspan="3"><input name="numOfInsuredYM1_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsuredYM1_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司近01個月投保人數-人數</td>
                                    <td colspan="3"><input name="numOfInsured1_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsured1_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司近02個月投保人數-年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="numOfInsuredYM2_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsuredYM2_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司近02個月投保人數-人數</td>
                                    <td colspan="3"><input name="numOfInsured2_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsured2_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司近03個月投保人數-年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="numOfInsuredYM3_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsuredYM3_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司近03個月投保人數-人數</td>
                                    <td colspan="3"><input name="numOfInsured3_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsured3_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司近04個月投保人數-年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="numOfInsuredYM4_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsuredYM4_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司近04個月投保人數-人數
                                    </td>
                                    <td colspan="3"><input name="numOfInsured4_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsured4_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司近05個月投保人數-年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="numOfInsuredYM5_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsuredYM5_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司近05個月投保人數-人數</td>
                                    <td colspan="3"><input name="numOfInsured5_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsured5_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司近06個月投保人數-年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="numOfInsuredYM6_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsuredYM6_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司近06個月投保人數-人數</td>
                                    <td colspan="3"><input name="numOfInsured6_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsured6_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司近07個月投保人數-年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="numOfInsuredYM7_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsuredYM7_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司近07個月投保人數-人數</td>
                                    <td colspan="3"><input name="numOfInsured7_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsured7_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司近08個月投保人數-年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="numOfInsuredYM8_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsuredYM8_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司近08個月投保人數-人數</td>
                                    <td colspan="3"><input name="numOfInsured8_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsured8_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司近09個月投保人數-年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="numOfInsuredYM9_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsuredYM9_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司近09個月投保人數-人數</td>
                                    <td colspan="3"><input name="numOfInsured9_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsured9_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司近10個月投保人數-年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="numOfInsuredYM10_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsuredYM10_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司近10個月投保人數-人數</td>
                                    <td colspan="3"><input name="numOfInsured10_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsured10_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司近11個月投保人數-年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="numOfInsuredYM11_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsuredYM11_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司近11個月投保人數-人數</td>
                                    <td colspan="3"><input name="numOfInsured11_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsured11_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>公司近12個月投保人數-年月</div>
                                        <div style="color:red;">格式:YYYYMM</div>
                                    </td>
                                    <td colspan="3"><input name="numOfInsuredYM12_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsuredYM12_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">公司近12個月投保人數-人數</td>
                                    <td colspan="3"><input name="numOfInsured12_content" type="text" class="table-input"
                                            disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('numOfInsured12_content')" value="Edit">
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
                                        <input name="prLaborQryDate_content" type="text" class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prLaborQryDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">負責人-被保險人勞保異動查詢-最近期投保薪資-人數</td>
                                    <td colspan="3"><input name="prLaborInsSalary_content" type="text"
                                            class="table-input" disabled>
                                    </td>

                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('prLaborInsSalary_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>配偶-被保險人勞保異動查詢日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3">
                                        <input name="spouseLaborQryDate_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseLaborQryDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">配偶-被保險人勞保異動查詢-最近期投保薪資</td>
                                    <td colspan="3">
                                        <input name="spouseLaborInsSalary_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('spouseLaborInsSalary_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">
                                        <div>保證人-被保險人勞保異動查詢日期</div>
                                        <div style="color:red;">格式:YYYYMMDD</div>
                                    </td>
                                    <td colspan="3">
                                        <input name="guOneLaborQryDate_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneLaborQryDate_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-right-border">保證人-被保險人勞保異動查詢-最近期投保薪資</td>
                                    <td colspan="3">
                                        <input name="guOneLaborInsSalary_content" type="text" class="table-input"
                                            disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('guOneLaborInsSalary_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title input-center bold-bottom-border bold-right-border">J-其他</td>
                                    <td class="bold-right-border">其他備註</td>
                                    <td colspan="3">
                                        <input name="otherMemo_content" type="text" class="table-input" disabled>
                                    </td>
                                    <td>
                                        <center>
                                            <input class="input-width" type="button"
                                                onclick="edit_click('otherMemo_content')" value="Edit">
                                        </center>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="input-width">
                            <tr>
                                <td class="white-border">
                                    <center>
                                        <input id="text_list" type="button" class="sendBtn" value="儲存資料" disabled>
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
    let select_array = ['compType_content', 'businessType_content', 'compDuType_content', 'bizRegAddrOwner_content', 'isBizAddrEqToBizRegAddr_content', 'bizTaxFileWay_content',
        'isPrMarried_content', 'isPrSpouseGu_content', 'realPr_content', 'isBizRegAddrSelfOwn_content', 'isRealBizAddrSelfOwn_content', 'realBizAddrOwner_content', 'othRealPrRelWithPr_content',
        'guOneRelWithPr_content', 'guOneCompany_content', 'guTwoRelWithPr_content', 'guTwoCompany_content', 'prEduLevel_content', 'compMajorOwnership_content', 'compMajorSetting_content',
        'compType2_content', 'registerType_content', 'isPublic_content', 'hasJuridicalInvest_content', 'isManufacturing_content', 'isCovidAffected_content', 'hasForeignInvestment_content', 'hasRelatedCompany_content',
        'relatedCompAType_content', 'relatedCompBType_content', 'relatedCompCType_content', 'isPrRegister_content', 'othRealPrRelWithPr_content', 'hasGuarantor_content', 'isPrSpouseGu_content',
        'jcCompDebtLog_content', 'prDebtLog_content', 'prHasWeekMonthDelay_content', 'prHasLastThreeMonthDelay_content', 'spouseDebtLog_content', 'guarantorDebtLog_content'];
    // 原始檔案圖片-附件資料表
    let rawData_array = [
        'A01', 'A02', 'A03', 'A04', 'A05', 'A06', 'A07', 'A08', 'B02', 'B03', 'B08', 'B09', 'B10', 'B11', 'B13', 'B14', 'B15', 'B16',
    ];
    // 送出時為數字欄位
    let is_int_array = ['compCapital', 'annualIncome1', 'annualIncome2', 'annualIncome3', 'numOfInsured1', 'numOfInsured2', 'numOfInsured3', 'numOfInsured4', 'numOfInsured5', 'numOfInsured6', 'numOfInsured7', 'numOfInsured8', 'numOfInsured9', 'numOfInsured10', 'numOfInsured11', 'numOfInsured12', 'MidTermLnBal', 'shortTermLnBal', 'compCreditScore', 'prLaborInsSalary', 'spouseLaborInsSalary', 'guOneLaborInsSalary', 'guTwoLaborInsSalary', 'prCreditScore', 'prBal_CashCard', 'prBal_CreditCard', 'prBal_ShortTermLn', 'prBal_MidTermLn', 'prBal_LongTermLn', 'prBal_ShortTermGuar', 'prBal_MidTermLnGuar', 'prBal_LongTermLnGuar', 'spouseCreditScore', 'spouseBal_CashCard', 'spouseBal_CreditCard', 'spouseBal_ShortTermLn', 'spouseBal_MidTermLn', 'spouseBal_LongTermLn', 'spouseBal_ShortTermGuar', 'spouseBal_MidTermLnGuar', 'spouseBal_LongTermLnGuar', 'guOneCreditScore', 'guOneBal_CashCard', 'guOneBal_CreditCard', 'guOneBal_ShortTermLn', 'guOneBal_MidTermLn', 'guOneBal_LongTermLn', 'guOneBal_ShortTermGuar', 'guOneBal_MidTermLnGuar', 'guOneBal_LongTermLnGuar', 'guTwoCreditScore', 'guTwoBal_CashCard', 'guTwoBal_CreditCard', 'guTwoBal_ShortTermLn', 'guTwoBal_MidTermLn', 'guTwoBal_LongTermLn', 'guTwoBal_ShortTermGuar', 'guTwoBal_MidTermLnGuar', 'guTwoBal_LongTermLnGuar', 'isPrMarried', 'isBizRegAddrSelfOwn', 'isBizAddrEqToBizRegAddr', 'isRealBizAddrSelfOwn', 'othRealPrSHRatio',
        'hasJuridicalInvest', 'isManufacturing', 'lastOneYearRevenue', 'dailyWorkingCapital', 'operatingCycle', 'liabilitiesAmount', 'equityAmount', 'isCovidAffected', 'employeeNum', 'hasForeignInvestment', 'hasRelatedCompany', 'businessTaxLastOneYear', 'businessTaxLastTwoYear', 'businessTaxLastThreeYear',
        'lastOneYearInvoiceAmountM1M2', 'lastOneYearInvoiceAmountM3M4'];
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
            data: data,
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
                        'CompId': `${all_data.compId}`,
                        'PrincipalId': `${all_data.principalId}`,
                    }
                    delete all_data.CompId;
                    delete all_data.PrincipalId;
                    request_data.Data = all_data;
                    request_data = JSON.stringify(request_data);

                    save_response = saveCheckListData(msg_no, 'text', request_data, bank_num);
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
        const sendImageList = (bank_num) => {
            return new Promise((resolve) => {
                $("#image_list").val("資料處理中");
                all_data = getCheckListImagesData();
                request_data = [];
                image_list_data = [];
                case_no = $('#case_no').val();
                data_count = Object.keys(all_data).length;
                let compId_imput = $('#compId_content').val();

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
                            resolve()
                        });
                    })

                }
            })
        }
        if (send_type == 'text_list') {
			// 1=sk 2=kgi
			for (const bank of [1,2]) {
				await sendData(bank)
			}
        }
        if (send_type == 'image_list') {
            for (const bank of [1,2]) {
                await sendImageList(bank)
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

                if (data.status.code != '200') {
                    alert(data.response); return;
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
            //console.log(bank);
            save(this.id);
        });

    });
</script>

</html>

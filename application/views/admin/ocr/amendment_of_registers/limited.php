<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin/css/ocr/A3.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="<?=base_url()?>assets/admin/js/ocr/fetch.js"></script>
    <title>有限公司設立登記表</title>
    <style>
    .btn {
      width: 30px;
      height: 30px;
      padding: 6px 0;
      border-radius: 15px;
      text-align: center;
      font-size: 12px;
      line-height: 1.5;
      margin: 5px;
    }
    </style>
</head>

<body style="text-align:center;">
  <div style="position:fixed; right:0;z-index:5">
    <button style="margin:0px 5px 0px 0px ;" onclick="edit_click()">編輯</button>
    <button style="margin:0px 5px 0px 0px ;" id="save_click">儲存</button>
    <button style="margin:0px 5px 0px 0px ;" id="send_click">送出</button>
  </div>
  <div class="page">
    <p class="page-header">共3頁&nbsp;第1頁</p>
    <div class="page-title">
      <div class="seal">
        <div class="block">
          <p class="seal-title">
            （&emsp;公&emsp;司&emsp;印&emsp;章&emsp;）
          </p>
          <div class="seal-chunk">
          </div>
        </div>
        <div class="block">
          <p class="seal-title">（&nbsp;代&nbsp;表&nbsp;公&nbsp;司&nbsp;負&nbsp;責&nbsp;人&nbsp;印&nbsp;章&nbsp;）</p>
          <div class="seal-chunk">
          </div>
        </div>
      </div>
      <div>
        <h2>有限公司設立登記表</h2>
        <table class="company-form">
          <tbody>
            <tr class="height">
              <td>公司預查編號</td>
              <td colspan="3" class="input-border"><input id="cpid" class="table-input save-this" type="text"></td>
            </tr>
            <tr class="height">
              <td>※公司統一編號</td>
              <td colspan="3" class="input-border"><input id="taxId" class="result-this table-input save-this" type="text"></td>
            </tr>
            <tr class="height">
              <td>公司連絡電話</td>
              <td colspan="3" class="input-border"><input id="phone" class="table-input save-this" type="text"></td>
            </tr>
            <tr class="height">
              <td>僑外投資事業</td>
              <td style="width: 100px;">
                <input id="is_ic" type="checkbox">是
                <input id="not_ic" type="checkbox">否
              </td>
              <td>一人公司</td>
              <td><input id="is_oc" type="checkbox">是
                <input id="not_oc" type="checkbox">否
              </td>
            </tr>
            <tr class="height">
              <td>陸資</td>
              <td style="width: 100px;">
                <input id="is_ch" type="checkbox">是
                <input id="not_ch" type="checkbox">否
              </td>
              <td>預定開業日期</td>
              <td><input id="sodate" class="input-underline" type="text" style="width: 100%;"></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="page-content">
      <span class="pull-left">印章請用油性印泥蓋章，並勿超出框格</span>
      <table class="table" border="1">
        <tbody>
          <tr>
            <td rowspan="2">一、公司名稱</td>
            <td>中文</td>
            <td colspan="4"><input class="save-this result-this" id="name" type="text" style="width: 87%;">有限公司</td>
          </tr>
          <tr>
            <td><span class="memo">（章程所訂）</span>外文</td>
            <td colspan="6"><input id="c_nameen" type="text" class="table-input"></td>
          </tr>
          <tr>
            <td colspan="2">二、（郵遞區號）公司所在地<span class="memo">（含鄉鎮市區村里）<span></td>
            <td colspan="4"><input id="address" type="text" class="table-input save-this result-this"></td>
          </tr>
          <tr>
            <td colspan="2">三、資本總額</td>
            <td colspan="4">新台幣<input id="amountOfCapital" type="text" class="input-right save-this result-this" style="width: 74%;">元<span
                class="memo">（阿拉伯數字）</span></td>
          </tr>
          <tr>
            <td colspan="2">四、董事人數</td>
            <td><input id="director" type="text" class="input-right save-this">人</td>
            <td>五、代表人姓名</td>
            <td><input id="owner" type="text" class="table-input save-this result-this"></td>
          </tr>
          <tr>
            <td colspan="2">六、公司章程訂定日期</td>
            <td colspan="4"><input id="mtime" type="text" class="table-input save-this"></td>
          </tr>
        </tbody>
        <tbody>
          <tr>
            <td rowspan="5" colspan="2">七、資本明細</td>
            <td rowspan="3">資產增加</td>
            <td colspan="4">
              <div class="d-f">
                <span>1.現金</span><input id="cash" type="text" style="width: 85%;" class="input-right save-this"><span>元</span>
              </div>
            </td>
          </tr>
          <tr>
            <td colspan="4">
              <div class="d-f">
                <span>2.財產</span><input id="property" type="text" style="width: 85%;" class="input-right save-this"><span>元</span>
              </div>
            </td>
          </tr>
          <tr>
            <td colspan="4">
              <div class="d-f">
                <span>3.技術</span><input id="technology" type="text" style="width: 85%;"
                  class="input-right save-this"><span>元</span>
              </div>
            </td>
          </tr>
          <tr>
            <td>併購</td>
            <td colspan="4">
              <div class="d-f">
                <span>4.合併新設</span><input id="establishment" type="text" style="width: 77%;"
                  class="input-right save-this"><span>元</span>
              </div>
            </td>
          </tr>
          <tr>
            <td style="color:#ffffff">Oops!!</td>
            <td colspan="4">
              <div class="d-f">
                <input id="other" type="text" style="width: 95%;" class="input-right save-this"><span>元</span>
              </div>
            </td>
          </tr>
        </tbody>
        <tbody>
          <tr>
            <td rowspan="4" style="width: 190px;">八、被合併公司資料明細</td>
            <td style="width: 150px;">合併基準日</td>
            <td>統一編號</td>
            <td colspan="2">公司名稱</td>
          </tr>
          <tr>
            <td>
              <div class="d-f">
                <input id="year" type="text" class="input-right quarter" ><span>年</span>
                <input id="month" type="text" class="input-right quarter" ><span>月</span>
                <input id="day" type="text" class="input-right quarter" ><span>日</span>
              </div>
            </td>
            <td><input id="uniformNumbers" type="text" class="input-right" style="width: 100%;"></td>
            <td colspan="2"><input id="companyName" type="text" class="input-right" style="width: 100%;"></td>
          </tr>
          <tr>
            <td>
              <div class="d-f">
                <input id="year" type="text" class="input-right quarter" ><span>年</span>
                <input id="month" type="text" class="input-right quarter" ><span>月</span>
                <input id="day" type="text" class="input-right quarter" ><span>日</span>
              </div>
            </td>
            <td><input id="uniformNumbers" type="text" class="input-right" style="width: 100%;"></td>
            <td colspan="2"><input id="companyName" type="text" class="input-right" style="width: 100%;"></td>
          </tr>
          <tr>
            <td>
              <div class="d-f">
                <input id="year" type="text" class="input-right quarter" ><span>年</span>
                <input id="month" type="text" class="input-right quarter" ><span>月</span>
                <input id="day" type="text" class="input-right quarter" ><span>日</span>
              </div>
            </td>
            <td><input id="uniformNumbers" type="text" class="input-right" style="width: 100%;"></td>
            <td colspan="2"><input id="companyName" type="text" class="input-right" style="width: 100%;"></td>
          </tr>
        </tbody>
      </table>
      <div class="flex">
        <p>※核准登記日期文號</p>
        <input id="applydateid" type="text" class="input-center" style="width: 69%;">
        <p>※檔號</p>
        <input id="fileid" type="text" class="input-center">
      </div>
    </div>
    <div class="bottom">
      <div class="mark-row">
        <span>公務記載蓋章欄</span>
        <div></div>
      </div>
      <div>
        <ul class="statement-list">
          <li>申請表一式二份，於合辦後一份存核辦單位，一份送還申請公司收執。</li>
          <li>為配合電腦作業，請打字或電腦以黑色列印填寫清楚，數字部分請採用阿拉伯數字，並請勿折疊、挖補、服貼或塗改。</li>
          <li>※各欄如變更登記日期文號、檔號等，申請人請勿填寫。</li>
          <li>違反公司法代作資金導致公司資本不實，公司負責人最高可處五年以下有期徒刑。</li>
          <li>為配合郵政作業，請於所在地加填郵遞區號。</li>
        </ul>
      </div>
    </div>
  </div>
  <div class="page">
    <p class="page-header">共3頁&nbsp;
      第2頁</p>
    <div class="page-title">
      <input class="input-underline company-name" type="text" style="width: 83%;">
      <h2>有限公司設立登記表</h2>
    </div>
    <div class="page-content">
      <p style="text-align: start;">註:欄為不足請自行複製，未使用之欄位可自行刪除，若本頁不足使用，請複製全頁後自行增減欄位。</p>
      <table class="table" border="1">
        <thead style="text-align: center;">
          <tr>
            <th colspan="3">所營事業</th>
          </tr>
          <tr>
            <td style="width: 60px;">編號</td>
            <td style="width: 170px;">代碼</td>
            <td>營業項目說明</td>
          </tr>
        </thead>
        <tbody id="business-content" class="save-table">
        </tbody>
      </table>
      <button class="btn" onclick="addBusiness('#business-content')">
        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-plus" fill="currentColor"
          xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd"
            d="M8 3.5a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5H4a.5.5 0 0 1 0-1h3.5V4a.5.5 0 0 1 .5-.5z" />
          <path fill-rule="evenodd" d="M7.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0V8z" />
        </svg>
      </button>
      <button class="btn" onclick="deleteBusiness('.business-table')">
        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor"
          xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd"
            d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z" />
          <path fill-rule="evenodd"
            d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z" />
        </svg>
      </button>
    </div>
    <br>
    <div class="bottom">
      <div class="mark-row">
        <span>公務記載蓋章欄</span>
        <div></div>
      </div>
    </div>
  </div>
  <div class="page">
    <p class="page-header">共3頁&nbsp;
      第3頁</p>
    <div class="page-title">
      <input class="input-underline company-name" type="text" class="table-input" style="width: 83%;">
      <h2>有限公司設立登記表</h2>
    </div>
    <div class="page-content">
      <p style="text-align: start;">註:欄為不足請自行複製，未使用之欄位可自行刪除，若本頁不足使用，請複製全頁後自行增減欄位。</p>
      <table class="table" border="1">
        <thead style="text-align: center;">
          <tr>
            <th colspan="5">董事、股東名單</th>
          </tr>
          <tr>
            <td rowspan="2">編號</td>
            <td>職稱</td>
            <td>姓名(或法人名稱)</td>
            <td>身分證號(或法人統一編號)</td>
            <td>出資額(元)</td>
          </tr>
          <tr>
            <td colspan="4">(郵遞區號)住所或居所(或法人所在地)</td>
          </tr>
        </thead>
        <tbody id="directorList" class="save-table"></tbody>
      </table>
      <button class="btn" onclick="addDirector('#directorList')">
        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-plus" fill="currentColor"
          xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd"
            d="M8 3.5a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5H4a.5.5 0 0 1 0-1h3.5V4a.5.5 0 0 1 .5-.5z" />
          <path fill-rule="evenodd" d="M7.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0V8z" />
        </svg>
      </button>
      <button class="btn" onclick="deleteDirector('.director-table-1')">
        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor"
          xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd"
            d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z" />
          <path fill-rule="evenodd"
            d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z" />
        </svg>
      </button><br>
      <table class="table" border="1">
        <thead style="text-align: center;">
          <tr>
            <th colspan="4">經理人名單</th>
          </tr>
          <tr>
            <td rowspan="2">編號</td>
            <td>姓名</td>
            <td>身分證統一編號</td>
            <td>到職日期(年月日)</td>
          </tr>
          <tr>
            <td colspan="3">(郵遞區號)住所或居所</td>
          </tr>
        </thead>
        <tbody id="managerList" class="save-table"></tbody>
      </table>
      <button class="btn" onclick="addManager('#managerList')">
        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-plus" fill="currentColor"
          xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd"
            d="M8 3.5a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5H4a.5.5 0 0 1 0-1h3.5V4a.5.5 0 0 1 .5-.5z" />
          <path fill-rule="evenodd" d="M7.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0V8z" />
        </svg>
      </button>
      <button class="btn" onclick="deleteManager('.manager-table-1')">
        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor"
          xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd"
            d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z" />
          <path fill-rule="evenodd"
            d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z" />
        </svg>
      </button>
      <table class="table" border="1">
        <thead style="text-align: center;">
          <tr>
            <th colspan="4">所代表法人</th>
          </tr>
          <tr>
            <td rowspan="2">編號</td>
            <td>董事編號</td>
            <td>所代表法人名稱</td>
            <td>法人統一編號</td>
          </tr>
          <tr>
            <td colspan="4">(郵遞區號)法人所在地</td>
          </tr>
        </thead>
        <tbody id="legalpersonLsit" class="save-table"></tbody>
      </table>
      <button class="btn" onclick="addLegalperson('#legalpersonLsit')">
        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-plus" fill="currentColor"
          xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd"
            d="M8 3.5a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5H4a.5.5 0 0 1 0-1h3.5V4a.5.5 0 0 1 .5-.5z" />
          <path fill-rule="evenodd" d="M7.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0V8z" />
        </svg>
      </button>
      <button class="btn" onclick="deleteLegalperson('.legalperson-table-1')">
        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor"
          xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd"
            d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z" />
          <path fill-rule="evenodd"
            d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z" />
        </svg>
      </button>
    </div>
    <div class="bottom">
      <div class="mark-row">
        <span>公務記載蓋章欄</span>
        <div></div>
      </div>
    </div>
  </div>
</body>

<script>

  var business_num = 1;
  var director_num = 1;
  var manager_num = 1;
  var legalperson_num = 1;

  // 所營事業 html
  function createBusinessHtml (business_num,value = ''){
    return`
    <tr class="business-table save-this-two-level-t" >
      <td style="width: 60px;text-align: center;">${business_num}</td>
      <td style="width: 170px;">
        <input id="business_num_${business_num}" style="width:100%;text-align: center;" value="${value}">
      </td>
      <td style="width: 170px;"><input style="width:100%;text-align: center;"></td>
    </tr>`;
  }

  // 所營事業
  function addBusiness(element='#business-content',data='',isMongo=0) {
    if(data ==''){
      html = createBusinessHtml(business_num);
      addTableList(element, html)
      business_num += 1;
    }else{
    Object.keys(data).forEach(function (key) {
      if(isMongo){
        value = data[key];
      }else{
        value = data[key]['business_num_'+ business_num];
      }
      html = createBusinessHtml(business_num,value);
      addTableList(element, html)
      business_num+=1;
    })
    }
  }

  function deleteBusiness(element='.business-table') {
    if (business_num > 1) {
      deleteTableList(element);
      business_num -= 1;
    }
  }

  // 董事名單 html
  function createDirectorHtml (director_num, title = '', name = '', id = '', dollars = '', address = ''){
    return`
    <tr class="director-table-1 save-this-two-level-t">
      <td colspan="5">
        <div class="dynamic-row">
          <div class="d-td-1" style="width: 77px;">
            ${director_num}
          </div>
          <div class="d-td-2" style="width: calc(100% - 77px);">
              <div class="up">
                <div style="width:77px"><input class="${director_num == 1? 'result-this' :''}" style="width:99%;" id="director_title_${director_num}" value="${title}"></div>
                <div style="width:280px"><input class="${director_num == 1? 'result-this' :''}" style="width:99%;" id="director_name_${director_num}" value="${name}"></div>
                <div style="width:424px"><input class="${director_num == 1? 'result-this' :''}" style="width:99%;" id="director_id_${director_num}" value="${id}"></div>
                <div style="width:175px"><input style="width:99%;" id="director_dollars_${director_num}" value="${dollars}"></div>
              </div>
              <div class="down"><input style="width:100%;" id="director_address_${director_num}" value="${address}"></div>
          </div>
        </div>
      </td>
    </tr`;
  }

  // 董事名單
  function addDirector(element='#directorList',data='',isMongo=0) {
    if(data ==''){
      html = createDirectorHtml(director_num);
      addTableList(element, html)
      director_num += 1;
    }else{
      Object.keys(data).forEach(function (key) {
        if(isMongo){
          title = data[key]['jobTitle'];
          name = data[key]['name'];
          id = data[key]['personId'];
          dollars = data[key]['amountOfCapitalContributed'];
          address = data[key]['address'];
        }else{
          title = data[key]['director_title_'+ director_num];
          name = data[key]['director_name_'+ director_num];
          id = data[key]['director_id_'+ director_num];
          dollars = data[key]['director_dollars_'+ director_num];
          address = data[key]['director_address_'+ director_num];
        }
        html = createDirectorHtml(director_num,title,name,id,dollars,address);
        addTableList(element, html)
        director_num+=1;
      })
    }
  }

  function deleteDirector(element='.director-table-1') {
    if (director_num > 1) {
      deleteTableList(element);
      director_num -= 1;
    }
  }

  // 經理人 html
  function createManagerHtml (manager_num, name = '', id = '', work_date = '', address = ''){
    return`
    <tr class="manager-table-1 save-this-two-level-t">
      <td colspan="5">
        <div class="dynamic-row">
          <div class="d-td-1" style="width: 119px;">
            ${manager_num}
          </div>
          <div class="d-td-2" style="width: calc(100% - 119px);">
              <div class="up">
                <div style="width:118px"><input style="width:99%;" id="manager_name_${manager_num}" value="${name}"></div>
                <div style="width:383px"><input style="width:99%;" id="manager_id_${manager_num}" value="${id}"></div>
                <div style="width:418px"><input style="width:99%;" id="manager_work_date_${manager_num}" value="${work_date}"></div>
              </div>
              <div class="down"><input style="width:100%;" id="manager_address_${manager_num}" value="${address}"></div>
          </div>
        </div>
      </td>
    </tr>`;
  }

  // 經理人
  function addManager(element='#managerList',data='',isMongo=0) {
    if(data==''){
      html = createManagerHtml(manager_num)
      addTableList(element, html)
      manager_num += 1;
    }else{
      Object.keys(data).forEach(function (key) {
        if(isMongo){
          name = data[key]['name'];
          id = data[key]['personId'];
          work_date = data[key]['amountOfCapitalContributed'];
          address = data[key]['address'];
        }else{
          name = data[key]['manager_name_'+ manager_num];
          id = data[key]['manager_id_'+ manager_num];
          work_date = data[key]['manager_work_date_'+ manager_num];
          address = data[key]['manager_address_'+ manager_num];
        }
        html = createManagerHtml(manager_num,name,id,work_date,address);
        addTableList(element, html)
        manager_num+=1;
      })
    }
  }

  function deleteManager(element='.manager-table-1') {
    if (manager_num > 1) {
      deleteTableList(element);
      manager_num -= 1;
    }
  }

  // 所代表法人 html
  function createLegalpersonHtml (legalperson_num, num='', name = '', taxid = '', address = ''){
    return `
    <tr class="legalperson-table-1 save-this-two-level-t">
      <td colspan="5">
        <div class="dynamic-row">
          <div class="d-td-1" style="width: 116px;">
            ${legalperson_num}
          </div>
          <div class="d-td-2" style="width: calc(100% - 116px);">
              <div class="up">
                <div style="width:221px"><input style="width:99%;" id="legalperson_num_${legalperson_num}" value="${num}"></div>
                <div style="width:376px"><input style="width:99%;" id="legalperson_name_${legalperson_num}" value="${name}"></div>
                <div style="width:326px"><input style="width:99%;" id="legalperson_taxid_${legalperson_num}" value="${taxid}"></div>
              </div>
              <div class="down"><input style="width:100%;" id="legalperson_address_${legalperson_num}" value="${address}"></div>
          </div>
        </div>
      </td>
      </tr>`;
  }

  // 所代表法人
  function addLegalperson(element='#legalpersonLsit',data='',isMongo=0) {
    if(data==''){
      html = createLegalpersonHtml(legalperson_num)
      addTableList(element, html)
      legalperson_num += 1;
    }else{
      Object.keys(data).forEach(function (key) {
        if(isMongo){
          num = data[key]['jobTitle'];
          name = data[key]['name'];
          taxid = data[key]['personId'];
          address = data[key]['address'];
        }else{
          num = data[key]['legalperson_num_'+ legalperson_num];
          name = data[key]['legalperson_name_'+ legalperson_num];
          taxid = data[key]['legalperson_taxid_'+ legalperson_num];
          address = data[key]['legalperson_address_'+ legalperson_num];
        }
        html = createLegalpersonHtml(legalperson_num,num,name,taxid,address);
        addTableList(element, html)
        legalperson_num+=1;
      })
    }
  }


  function deleteLegalperson(element='.legalperson-table-1') {
    if (legalperson_num > 1) {
      deleteTableList(element);
      legalperson_num -= 1;
    }
  }
  $(document).ready(function () {
    var urlString = window.location.href;
    var url = new URL(urlString);
    var certification_id = url.searchParams.get("certification");
    var imageId = url.searchParams.get("id");
    var textId = url.searchParams.get("name");
    var check = url.searchParams.get("check");

    // 儲存按鈕
    $( "#save_click" ).on('click',function() {
      save_click('amendment_of_register', imageId, certification_id);
    });

    // 送出按鈕
    $( "#send_click" ).on('click',function() {
      send_click('amendment_of_register', imageId, certification_id);
    });

    fetchReport('amendment_of_register', imageId, check, certification_id, function (data) {
      if (!data) {
        return;
      }
      if(data.data_type){
        fillReport(data,'amendment_of_register','report');
        if(data.table_list["business-content"]){
            addBusiness('#business-content',data.table_list["business-content"]);
        }
        if(data.table_list["directorList"]){
          addDirector('#directorList',data.table_list["directorList"]);
        }
        if(data.table_list["managerList"]){
          addManager('#managerList',data.table_list["managerList"]);
        }
        if(data.table_list["legalpersonLsit"]){
          addLegalperson('#legalpersonLsit',data.table_list["legalpersonLsit"]);
        }
      }else{
        fillReport(data.companyInfo,'amendment_of_register','report');
        // fillReport(data.companyInfo,'amendment_of_register','report');
        if(data.scopeOfBusiness.length!=0){
          addBusiness('#business-content',data.scopeOfBusiness,1);
        }
        if(data.registerOfShareholders.length!=0){
          addDirector('#directorList',data.registerOfShareholders,1);
        }
        if(data.managers.length!=0){
          addManager('#managerList',data.managers,1);
        }
        if(data.representativeOfLegalPerson.length!=0){
          addLegalperson('#legalpersonLsit',data.representativeOfLegalPerson,1);
        }
      }
      if (data.message) {
        alert(data.message);
      }
    });

  });
</script>

</html>

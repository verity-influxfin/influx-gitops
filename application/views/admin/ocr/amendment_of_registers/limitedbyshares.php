<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin/css/ocr/A3.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="<?=base_url()?>assets/admin/js/ocr/fetch.js"></script>
  <title>股份有限公司設立登記表</title>
    <style>
      .auto-skip {
        width: 50%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }

      .detail div {
        display: flex;
      }

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
          <h2>股份有限公司設立登記表</h2>
          <table class="company-form">
            <tbody>
              <tr>
                <td>公司預查編號</td>
                <td colspan="3" class="input-border"><input id="cpid" class="table-input save-this" type="text"></td>
              </tr>
              <tr>
                <td>公司統一編號</td>
                <td colspan="3" class="input-border"><input id="taxId" class="table-input save-this result-this" type="text"></td>
              </tr>
              <tr>
                <td>公司連絡電話</td>
                <td colspan="3" class="input-border"><input id="phone" class="table-input save-this" type="text"></td>
              </tr>
              <tr>
                <td>僑外投資事業</td>
                <td>
                  <input id="is_ic" type="checkbox" disabled>是
                  <input id="not_ic" type="checkbox" disabled>否
                </td>
                <td>公開發行</td>
                <td><input id="is_ipo" type="checkbox" disabled>是
                  <input id="not_ipo" type="checkbox" disabled>否
                </td>
              </tr>
              <tr>
                <td>陸資</td>
                <td colspan="3">
                  <input id="is_ch" type="checkbox" disabled>是
                  <input id="not_ch" type="checkbox" disabled>否
                </td>
              </tr>
              <tr>
                <td colspan="2">閉鎖性股份有限公司股東人數</td>
                <td colspan="2"><input id="blockingshareholder" class="input-underline" type="text" style="width: 35px;">人
                </td>
              </tr>
              <tr>
                <td colspan="2">複數表決權特別股</td>
                <td colspan="2" style="width: 100px;">
                  <input id="is_multiplevote" type="checkbox" disabled>有
                  <input id="not_multiplevote" type="checkbox" disabled>無
                </td>
              </tr>
              </tr>
              <tr>
                <td colspan="2">對於特定事項具否決權特別股</td>
                <td colspan="2" style="width: 100px;">
                  <input id="is_againstvote" type="checkbox" disabled>有
                  <input id="not_againstvote" type="checkbox" disabled>無
                </td>
              </tr>
              <tr>
                <td colspan="3">特別股股東被選為董事、監察人之禁止或限制或當選一定名額之權利</td>
                <td style="width: 100px;">
                  <input id="is_special" type="checkbox" disabled>有
                  <input id="not_special" type="checkbox" disabled>無
                </td>
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
              <td colspan="4"><input class="save-this result-this" id="name" type="text" style="width: 83%;">股份有限公司</td>
            </tr>
            <tr>
              <td><span class="memo">（章程所訂）</span>外文</td>
              <td colspan="4"><input id="cnameen" type="text" style="width: 100%;" class="table-input"></td>
            </tr>
            <tr>
              <td colspan="2">二、（郵遞區號）公司所在地<span class="memo">（含鄉鎮市區村里）</span></td>
              <td colspan="4"><input id="address" type="text" style="width: 100%;" class="table-input save-this result-this"></td>
            </tr>
            <tr>
              <td colspan="2">三、代表公司負責人</td>
              <td><input type="text" class="save-this result-this" id="owner"></td>
              <td>四、每股金額<span class="memo">（阿拉伯數字）</span></td>
              <td><input type="text" id="price_per_stock" class="input-right table-input save-this" style="width: 93%;">元</td>
            </tr>
            <tr>
              <td colspan="2">五、資本總額<span class="memo">（阿拉伯數字）</span></td>
              <td colspan="4"><input id="capital" type="text" class="input-right" style="width: 97%;">元</td>
            </tr>
            <tr>
              <td colspan="2">六、實收資本總額<span class="memo">（阿拉伯數字）</span></td>
              <td colspan="4"><input id="paidInCapital" type="text" class="input-right" style="width: 97%;">元</td>
            </tr>
            <tr>
              <td rowspan="2" colspan="2">七、股份總數</td>
              <td rowspan="2"><input id="stocktotal" type="text" class="input-right">股</td>
              <td rowspan="2">八、已發行股份總數</td>
              <td>1.普通股<input id="stock" type="text" class="input-right">股</td>
            </tr>
            <tr>
              <td>2.特別股<input id="special_stock" type="text" class="input-right">股</td>
            </tr>
            <tr>
              <td colspan="2">九、董事人數任期</td>
              <td colspan="4"><input id="director_num" type="text" class="input-center"
                  style="width: 50px;">人&emsp;&emsp;自<input id="f_director_y" type="text" class="input-center"
                  style="width: 50px;">年<input id="f_director_m" type="text" class="input-center"
                  style="width: 50px;">月<input id="f_director_d" type="text" class="input-center"
                  style="width: 50px;">日&emsp;&emsp;至&emsp;&emsp;<input id="t_director_y" type="text" class="input-center"
                  style="width: 50px;">年<input id="t_director_m" type="text" class="input-center"
                  style="width: 50px;">月<input id="t_director_d" type="text" class="input-center"
                  style="width: 50px;">日<br>（含獨資董事<input id="independent_director_num" type="text" class="input-center"
                  style="width: 50px;">人）</td>
            </tr>
            <tr>
              <td rowspan="2" colspan="2">
                十、<input id="supervisors" name="option" type="radio" class="input-center">
                監察人人數或<input id="auditCommitteeMember" name="option" type="radio" class="input-center">審計會委員</td>
              <td colspan="4"><input id="supervise_people" type="text" class="input-center"
                  style="width: 50px;">人&emsp;&emsp;自<input id="f_supervise_y" type="text" class="input-center"
                  style="width: 50px;">年<input id="f_supervise_m" type="text" class="input-center"
                  style="width: 50px;">月<input id="f_supervise_d" type="text" class="input-center"
                  style="width: 50px;">日&emsp;&emsp;至&emsp;&emsp;<input id="t_supervise_y" type="text"
                  class="input-center" style="width: 50px;">年<input id="t_supervise_m" type="text" class="input-center"
                  style="width: 50px;">月<input id="t_supervise_d" type="text" class="input-center" style="width: 50px;">日
              </td>
            </tr>
            <tr>
              <td colspan="4">本公司設置審計委員會由全體獨立董事組成替代監察人</td>
            </tr>
            <tr>
              <td colspan="2">十一、公司章程修正（訂定）日期</td>
              <td colspan="4"><input id="apply_y" type="text" class="input-center" style="width: 75px;">年<input
                  id="apply_m" type="text" class="input-center" style="width: 75px;">月<input id="apply_d" type="text"
                  class="input-center" style="width: 75px;">日</td>
            </tr>
          </tbody>
          <tbody>
            <tr>
              <td rowspan="9" colspan="2">十二、股本明細</td>
              <td rowspan="3">資產增加</td>
              <td colspan="4">
                <div class="d-f">
                  <span>1.現金</span>
                  <input id="cash" type="text" style="width: 43%;" class="input-right"><span>股</span>
                  <input id="cash" type="text" style="width: 37%;" class="input-right"><span>元</span>
                </div>
              </td>
            </tr>
            <tr>
              <td colspan="4">
                <div class="d-f">
                  <span>2.財產</span>
                  <input id="cash" type="text" style="width: 43%;" class="input-right"><span>股</span>
                  <input id="cash" type="text" style="width: 37%;" class="input-right"><span>元</span>
                </div>
              </td>
            </tr>
            <tr>
              <td colspan="4">
                <div class="d-f">
                  <span>3.技術</span>
                  <input id="cash" type="text" style="width: 43%;" class="input-right"><span>股</span>
                  <input id="cash" type="text" style="width: 37%;" class="input-right"><span>元</span>
                </div>
              </td>
            </tr>
            <tr>
              <td rowspan="3">併購</td>
              <td colspan="4">
                <div class="d-f">
                  <span>4.合併新設</span>
                  <input id="cash" type="text" style="width: 37%;" class="input-right"><span>股</span>
                  <input id="cash" type="text" style="width: 37%;" class="input-right"><span>元</span>
                </div>
              </td>
            </tr>
            <tr>
              <td colspan="4">
                <div class="d-f">
                  <span>5.分割新設</span>
                  <input id="cash" type="text" style="width: 37%;" class="input-right"><span>股</span>
                  <input id="cash" type="text" style="width: 37%;" class="input-right"><span>元</span>
                </div>
              </td>
            </tr>
            <tr>
              <td colspan="4">
                <div class="d-f">
                  <span>6.股份轉換</span>
                  <input id="cash" type="text" style="width: 37%;" class="input-right"><span>股</span>
                  <input id="cash" type="text" style="width: 37%;" class="input-right"><span>元</span>
                </div>
              </td>
            </tr>
            <tr>
              <td rowspan="3">其他</td>
              <td colspan="4">
                <div class="d-f">
                  <span style="color:#ffffff">Oops!!</span>
                  <input id="cash" type="text" style="width: 43%;" class="input-right"><span>股</span>
                  <input id="cash" type="text" style="width: 37%;" class="input-right"><span>元</span>
                </div>
              </td>
            </tr>
            <tr>
              <td colspan="4">
                <div class="d-f">
                  <span style="color:#ffffff">Oops!!</span>
                  <input id="cash" type="text" style="width: 43%;" class="input-right"><span>股</span>
                  <input id="cash" type="text" style="width: 37%;" class="input-right"><span>元</span>
                </div>
              </td>
            </tr>
            <tr>
              <td colspan="4">
                <div class="d-f">
                  <span style="color:#ffffff">Oops!!</span>
                  <input id="cash" type="text" style="width: 43%;" class="input-right"><span>股</span>
                  <input id="cash" type="text" style="width: 37%;" class="input-right"><span>元</span>
                </div>
              </td>
            </tr>
          </tbody>
          <tbody>
            <tr>
              <td colspan="7">十三、被併購公司資料明細</td>
            </tr>
            <tr>
              <td rowspan="2" colspan="2">併購種類</td>
              <td rowspan="2">併購基準日</td>
              <td colspan="2">被併購公司</td>
            </tr>
            <tr>
              <td>統一編號</td>
              <td>公司名稱</td>
            </tr>

            <tr>
              <td colspan="2"><input id="cash" type="text" style="width: 100%;" class="input-right"></td>
              <td><input id="apply_y" type="text" class="input-center" style="width: 75px;">年<input id="apply_m"
                  type="text" class="input-center" style="width: 75px;">月<input id="apply_d" type="text"
                  class="input-center" style="width: 75px;">日</td>
              <td><input id="apply_y" type="text" class="input-center" style="width: 100%;"></td>
              <td><input id="apply_y" type="text" class="input-center" style="width: 100%;"></td>
            </tr>

            <tr>
              <td rowspan="2" colspan="2"><input id="cash" type="text" style="width: 100%;" class="input-right"></td>
              <td rowspan="2"><input id="apply_y" type="text" class="input-center" style="width: 75px;">年<input
                  id="apply_m" type="text" class="input-center" style="width: 75px;">月<input id="apply_d" type="text"
                  class="input-center" style="width: 75px;">日</td>
              <td><input id="apply_y" type="text" class="input-center" style="width: 100%;"></td>
              <td><input id="apply_y" type="text" class="input-center" style="width: 100%;"></td>
            </tr>
          </tbody>
        </table>
        <div class="flex">
          <p>※核准登記日期文號</p>
          <input id="applydateid" type="text" style="width: 69%;">
          <p>※檔號</p>
          <input id="fileid" type="text">
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
            <li>第十欄位請依公司章程內容，於「監察人人數任期」前註記<div class="square-fill" style="width: 10px;height: 10px;"></div>
              ，並填寫人數任期；或於「審計委員會」前註記<div class="square-fill" style="width: 10px;height: 10px;"></div>
              ，監察人之人數任期免填。</li>
            <li>閉鎖性股份有限公司應填列股東人數、以技術或勞務出資者應填列章程載明之核給股數與抵充金額（勞務出資僅適用閉鎖性股份有限公司）。</li>
          </ul>
        </div>
      </div>
    </div>
    <div class="page">
      <p class="page-header">共3頁&nbsp;
        第2頁</p>
      <div class="page-title">
        <input class="input-underline company-name" type="text" style="width: 79%;">
        <h2>股份有限公司設立登記表</h2>
      </div>
      <div class="page-content">
        <p style="text-align: start;">註:欄為不足請自行複製，未使用之欄位可自行刪除，若本頁不足使用，請複製全頁後自行增減欄位。</p>
        <table class="table" border="1">
          <thead style="text-align: center;">
            <tr>
              <th colspan="3">
                <h2>所&emsp;營&emsp;事&emsp;業</h2>
              </th>
            </tr>
            <tr>
              <td style="width: 60px;">編號</td>
              <td style="width: 170px;">代碼</td>
              <td>營業項目說明</td>
            </tr>
          </thead>
          <tbody id="business-content" class="save-table"></tbody>
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
        <input class="input-underline company-name" type="text" style="width: 79%;">
        <h2>股份有限公司設立登記表</h2>
      </div>
      <div class="page-content">
        <p style="text-align: start;">註:欄為不足請自行複製，未使用之欄位可自行刪除，若本頁不足使用，請複製全頁後自行增減欄位。</p>
        <table class="table" border="1">
          <thead style="text-align: center;">
            <tr>
              <th colspan="5">董事、監察人名單</th>
            </tr>
            <tr>
              <td rowspan="2">編號</td>
              <td>職稱</td>
              <td>姓名(或法人名稱)</td>
              <td>身分證號(或法人統一編號)</td>
              <td>持有股份(股)</td>
            </tr>
            <tr>
              <td colspan="4">(郵遞區號)住所或居所(或法人所在地)</td>
            </tr>
          </thead>
          <tbody id="directorList"></tbody>
        </table>
        <button class="btn" onclick="addDirector()">
          <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-plus" fill="currentColor"
            xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M8 3.5a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5H4a.5.5 0 0 1 0-1h3.5V4a.5.5 0 0 1 .5-.5z" />
            <path fill-rule="evenodd" d="M7.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0V8z" />
          </svg>
        </button>
        <button class="btn" onclick="deleteDirector()">
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
              <td>身分證號</td>
              <td>到職日期(年月日)</td>
            </tr>
            <tr>
              <td colspan="3">(郵遞區號)住所或居所</td>
            </tr>
          </thead>
          <tbody id="managerList"></tbody>
        </table>
        <button class="btn" onclick="addManager()">
          <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-plus" fill="currentColor"
            xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M8 3.5a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5H4a.5.5 0 0 1 0-1h3.5V4a.5.5 0 0 1 .5-.5z" />
            <path fill-rule="evenodd" d="M7.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0V8z" />
          </svg>
        </button>
        <button class="btn" onclick="deleteManager()">
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
              <th colspan="4">所代表法人</th>
            </tr>
            <tr>
              <td rowspan="2">董監事編號</td>
              <td>董事編號</td>
              <td>所代表法人名稱</td>
              <td>法人統一編號</td>
            </tr>
            <tr>
              <td colspan="4">(郵遞區號)法人所在地</td>
            </tr>
          </thead>
          <tbody id="legalpersonLsit"></tbody>
        </table>
        <button class="btn" onclick="addLegalperson()">
          <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-plus" fill="currentColor"
            xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M8 3.5a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5H4a.5.5 0 0 1 0-1h3.5V4a.5.5 0 0 1 .5-.5z" />
            <path fill-rule="evenodd" d="M7.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0V8z" />
          </svg>
        </button>
        <button class="btn" onclick="deleteLegalperson()">
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
          <div class="d-td-1" style="width: 140px;">
            ${manager_num}
          </div>
          <div class="d-td-2" style="width: calc(100% - 140px);">
              <div class="up">
                <div style="width:140px"><input style="width:99%;" id="manager_name_${manager_num}" value="${name}"></div>
                <div style="width:264px"><input style="width:99%;" id="manager_id_${manager_num}" value="${id}"></div>
                <div style="width:493px"><input style="width:99%;" id="manager_work_date_${manager_num}" value="${work_date}"></div>
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
          <div class="d-td-1" style="width: 236px;">
            ${legalperson_num}
          </div>
          <div class="d-td-2" style="width: calc(100% - 236px);">
              <div class="up">
                <div style="width:191px"><input style="width:99%;" id="legalperson_num_${legalperson_num}" value="${num}"></div>
                <div style="width:327px"><input style="width:99%;" id="legalperson_name_${legalperson_num}" value="${name}"></div>
                <div style="width:238px"><input style="width:99%;" id="legalperson_taxid_${legalperson_num}" value="${taxid}"></div>
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
        fillReport(data,'balance_sheet','report');
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
        fillReport(data.companyInfo,'balance_sheet','report');
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

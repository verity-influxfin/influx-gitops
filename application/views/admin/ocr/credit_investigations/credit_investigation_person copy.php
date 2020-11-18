<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin/css/ocr/A3.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="<?=base_url()?>assets/admin/js/ocr/fetch.js"></script>
    <title>聯徵資料表</title>
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
    .cr-text {
      margin-bottom: 10px;
    }
    .credit-table {
      border-bottom:2px black dashed;
      width: 100%;
      font-size: 16px;
      border-spacing: 0px;
      text-align: left;
      margin: 20px 0px;
    }
    .credit-table-input {
      width: 100%;
      border: 0px;
    }
    .credit-table input {
      border: 0px;
      text-align: center;
      width: 100%;
    }
    .credit-table td {
      text-align: center;
    }
    .credit-table th {
      width: 50%;
    }
    .credit-btn {
      border-top: 0px solid #ffffff;
    }
    .credit-btn th {
      border-bottom:2px black dashed;
    }
    .credit-data td,tr {
      border-style: none;
    }
    .icon-div {
      position: absolute;
      left: -27px;
    }
    .list-li {
      list-style:none;
      position: relative;
    }
    </style>
</head>

<body style="text-align:center;">
  <div style="position:fixed; right:0;z-index:5">
    <button style="margin:0px 5px 0px 0px ;" id="edit_click">編輯</button>
    <button style="margin:0px 5px 0px 0px ;" id="save_click">儲存</button>
    <button style="margin:0px 5px 0px 0px ;" id="send_click">送出</button>
  </div>
  <div class="page">
    <p class="page-header"><input class="input-underline save-this result-this" style="width:10%;" id="date" > <input class="input-underline save-this result-this" style="width:10%;" id="time" ></p>
    <p class="page-header">Page. &emsp;<input class="input-underline save-this" style="width:10%;" id="start_page" value="1"> /&emsp;<input class="input-underline save-this" style="width:10%;" id="end_page" ></p>
    <h2>
      <div>財團法人金融聯合徵信中心</div>
      <div>當事人綜合性運報告</div>
    </h2>
    <div>謹慎使用信用報告&emsp;&emsp;&emsp;&emsp;保障良好信用</div>
    <p style="text-align: start;">本報告僅供&emsp;台端參考，其所載信用資訊並非金融機構准駁金融交易之唯一依據。</p>
    <div class="page-title">身分證號：
      <input class="input-underline company-name save-this result-this" id="id-card" type="text" style="width: 20%;">
    </div>
    <div class="page-title">中文姓名：
      <input class="input-underline company-name save-this result-this" id="name" type="text" style="width: 20%;">
    </div>
    <div class="page-content">
      <table class="credit-table" rules="rows">
        <tbody class="credit-table"><th colspan="30"></th></tbody>
        <tbody class="credit-table"><th colspan="30"></th></tbody>
        <tbody class="credit-table">
          <th colspan="15">【銀行借款資訊】</th>
          <th colspan="15" id="bank-loan-title">查資料庫中無</th>
        </tbody>
        <tbody class="credit-data save-table" id="bank-loan">
          <tr class="bank-loan-list" hidden>
            <td colspan="6">金融機構</td>
            <td colspan="6">訂約金額(千元)</td>
            <td colspan="6">借款餘額(千元)</td>
            <td colspan="6">科目</td>
            <td colspan="6">最近十二個月有無還款延遲</td>
          </tr>
        </tbody>
        <tbody class="credit-btn action-btn">
          <th colspan="30" style="text-align: center;">
            <button class="btn" onclick="addBankLoan('#bank-loan')">
              <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-plus" fill="currentColor"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                  d="M8 3.5a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5H4a.5.5 0 0 1 0-1h3.5V4a.5.5 0 0 1 .5-.5z" />
                <path fill-rule="evenodd" d="M7.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0V8z" />
              </svg>
            </button>
            <button class="btn" onclick="deleteBankLoan('.bank-loan-list')">
              <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                  d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z" />
                <path fill-rule="evenodd"
                  d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z" />
              </svg>
            </button>
          </th>
        </tbody>
        <tbody class="credit-table">
          <th colspan="15">【逾期、催收或呆帳資訊】</th>
          <th colspan="15">查資料庫中無</th>
        </tbody>
        <tbody class="credit-table">
          <th colspan="15">【主債務債權再轉讓及清償資訊】</th>
          <th colspan="15">查資料庫中無</th>
        </tbody>
        <tbody class="credit-table"><th colspan="30"></th></tbody>
        <tbody class="credit-table">
          <th colspan="15">【共同債務/從債務/其他債務資訊】</th>
          <th colspan="15" id="debt-info-title">查資料庫中無</th>
        </tbody>
        <tbody class="credit-data save-table" id="debt-info">
          <tr class="debt-info-list" hidden>
            <td colspan="5">債務類型</td>
            <td colspan="5">台端擔任之保證人</td>
            <td colspan="5">承貸行</td>
            <td colspan="5">科目</td>
            <td colspan="5">未逾期金額(千元)</td>
            <td colspan="5">逾期未還金額(千元)</td>
          </tr>
        </tbody>
        <tbody class="credit-btn action-btn">
          <th colspan="30" style="text-align: center;">
            <button class="btn" onclick="addDebtInfo('#debt-info')">
              <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-plus" fill="currentColor"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                  d="M8 3.5a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5H4a.5.5 0 0 1 0-1h3.5V4a.5.5 0 0 1 .5-.5z" />
                <path fill-rule="evenodd" d="M7.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0V8z" />
              </svg>
            </button>
            <button class="btn" onclick="deleteDebtInfo('.debt-info-list')">
              <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                  d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z" />
                <path fill-rule="evenodd"
                  d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z" />
              </svg>
            </button>
          </th>
        </tbody>
        <tbody class="credit-table">
          <th colspan="15">【共同債務/從債務/其他債務轉讓資訊】</th>
          <th colspan="15">查資料庫中無</th>
        </tbody>
        <tbody class="credit-table"><th colspan="30"></th></tbody>
        <tbody class="credit-table">
          <th colspan="15">【退票資訊】</th>
          <th colspan="15">查資料庫中無</th>
        </tbody>
        <tbody class="credit-table">
          <th colspan="15">【拒絕往來資訊】</th>
          <th colspan="15">查資料庫中無</th>
        </tbody>
        <tbody class="credit-table"><th colspan="30"></th></tbody>
        <tbody class="credit-table">
          <th colspan="15">【信用卡資訊】</th>
          <th colspan="15" id="credit-card-info-title">查資料庫中無</th>
        </tbody>
        <tbody class="credit-data save-table" id="credit-card-info">
          <tr class="credit-card-info-list" hidden>
            <td colspan="5">發卡機構</td>
            <td colspan="5">卡名</td>
            <td colspan="5">額度</td>
            <td colspan="5">發卡日期</td>
            <td colspan="5">停用日期</td>
            <td colspan="5">停用狀態</td>
          </tr>
          <!-- <tr>
            <td colspan="5">玉山銀行</td>
            <td colspan="5">VISA 卓越 (正)</td>
            <td colspan="5">50</td>
            <td colspan="5">104/09/17</td>
            <td colspan="5"></td>
            <td colspan="5">使用中</td>
          </tr>
          <tr>
            <td colspan="5">永豐銀行</td>
            <td colspan="5">MASTER 卓越 (正)</td>
            <td colspan="5">50</td>
            <td colspan="5">103/04/21</td>
            <td colspan="5">108/05/02</td>
            <td colspan="5">一般停用：申請停用</td>
          </tr> -->
        </tbody>
        <tbody class="credit-btn action-btn">
          <th colspan="30" style="text-align: center;">
            <button class="btn" onclick="addCreditCard('#credit-card-info')">
              <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-plus" fill="currentColor"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                  d="M8 3.5a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5H4a.5.5 0 0 1 0-1h3.5V4a.5.5 0 0 1 .5-.5z" />
                <path fill-rule="evenodd" d="M7.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0V8z" />
              </svg>
            </button>
            <button class="btn" onclick="deleteCreditCard('.credit-card-info-list')">
              <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                  d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z" />
                <path fill-rule="evenodd"
                  d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z" />
              </svg>
            </button>
          </th>
        </tbody>
        <tbody class="credit-table">
          <th colspan="15">【信用卡戶帳款資訊】</th>
          <th colspan="15" id="credit-card-account-title">查資料庫中無</th>
        </tbody>
        <tbody class="credit-data save-table" id="credit-card-account">
          <tr class="credit-card-account-list" hidden>
            <td colspan="3">結帳日</td>
            <td colspan="3">發卡機構</td>
            <td colspan="3">卡名</td>
            <td colspan="3">額度(千元)</td>
            <td colspan="3">預借現金</td>
            <td colspan="3">結案</td>
            <td colspan="3">上期繳款狀況</td>
            <td colspan="3">本期應付帳款(元)</td>
            <td colspan="3">未到期待付款(元)</td>
            <td colspan="3">債權狀態</td>
          </tr>
          <!-- <tr>
            <td colspan="3">109/07/05</td>
            <td colspan="3">台中商銀</td>
            <td colspan="3">MASTER</td>
            <td colspan="3">80</td>
            <td colspan="3">無</td>
            <td colspan="3"></td>
            <td colspan="3">不需繳款</td>
            <td colspan="3">80000</td>
            <td colspan="3">0</td>
            <td colspan="3"></td>
          </tr>
          <tr style="border-bottom:2px black dashed;"><td colspan="30"></td></tr>
          <tr>
            <td colspan="3">109/06/17</td>
            <td colspan="3">華南銀行</td>
            <td colspan="3">VISA</td>
            <td colspan="3">200</td>
            <td colspan="3">無</td>
            <td colspan="3"></td>
            <td colspan="3">全額繳清 無延遲</td>
            <td colspan="3">31586</td>
            <td colspan="3">165036</td>
            <td colspan="3"></td>
          </tr>
          <tr>
            <td colspan="3">109/06/22</td>
            <td colspan="3">國泰世華銀行</td>
            <td colspan="3">VISA</td>
            <td colspan="3">150</td>
            <td colspan="3">無</td>
            <td colspan="3"></td>
            <td colspan="3">全額繳清 無延遲</td>
            <td colspan="3">74751</td>
            <td colspan="3">85361</td>
            <td colspan="3"></td>
          </tr>
          <tr style="border-bottom:2px black dashed;"><td colspan="30"></td></tr>
          <tr>
            <td colspan="3">109/05/22</td>
            <td colspan="3">國泰世華銀行</td>
            <td colspan="3">VISA</td>
            <td colspan="3">150</td>
            <td colspan="3">無</td>
            <td colspan="3"></td>
            <td colspan="3">全額繳清 無延遲</td>
            <td colspan="3">74751</td>
            <td colspan="3">85361</td>
            <td colspan="3"></td>
          </tr> -->
        </tbody>
        <tbody class="credit-btn action-btn">
          <th colspan="30" style="text-align: center;">
            <button class="btn" onclick="addCreditCardAccount('#credit-card-account')">
              <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-plus" fill="currentColor"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                  d="M8 3.5a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5H4a.5.5 0 0 1 0-1h3.5V4a.5.5 0 0 1 .5-.5z" />
                <path fill-rule="evenodd" d="M7.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0V8z" />
              </svg>
            </button>
            <button class="btn" onclick="deleteCreditCardAccount('.credit-card-account-list')">
              <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                  d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z" />
                <path fill-rule="evenodd"
                  d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z" />
              </svg>
            </button>
          </th>
        </tbody>
        <tbody class="credit-table">
          <th colspan="15">【信用卡債權再轉讓及清償資訊】</th>
          <th colspan="15">查資料庫中無</th>
        </tbody>
        <tbody class="credit-table"><th colspan="30"></th></tbody>
        <tbody class="credit-table">
          <th colspan="15">【被查詢記錄】</th>
          <th colspan="15" id="inquired-info-title">查資料庫中無</th>
        </tbody>
        <tbody class="credit-data save-table" id="inquired-info">
          <tr class="inquired-info-list" hidden>
            <td colspan="10">查詢日期</td>
            <td colspan="10">查詢機構</td>
            <td colspan="10">查詢理由</td>
          </tr>
          <!-- <tr>
            <td colspan="10">109/04/21</td>
            <td colspan="10">台北富邦銀行個金授館部</td>
            <td colspan="10">帳戶管理</td>
          </tr>
          <tr>
            <td colspan="10">109/04/22</td>
            <td colspan="10">玉山銀行信用卡暨支付金融事</td>
            <td colspan="10">帳戶管理</td>
          </tr>
          <tr>
            <td colspan="10">109/05/18</td>
            <td colspan="10">台新國際商業銀行個金授信管</td>
            <td colspan="10">新業務</td>
          </tr> -->
        </tbody>
        <tbody class="credit-btn action-btn">
          <th colspan="30" style="text-align: center;">
            <button class="btn" onclick="addInquiredInfo('#inquired-info')">
              <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-plus" fill="currentColor"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                  d="M8 3.5a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5H4a.5.5 0 0 1 0-1h3.5V4a.5.5 0 0 1 .5-.5z" />
                <path fill-rule="evenodd" d="M7.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0V8z" />
              </svg>
            </button>
            <button class="btn" onclick="deleteInquiredInfo('.inquired-info-list')">
              <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                  d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z" />
                <path fill-rule="evenodd"
                  d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z" />
              </svg>
            </button>
          </th>
        </tbody>
        <tbody class="credit-table"><th colspan="30"></th></tbody>
        <tbody class="credit-table">
          <th colspan="15">【被電子支付或電子票證發行機構查詢紀錄】</th>
          <th colspan="15" id="inquired-info-other-title">查資料庫中無</th>
        </tbody>
        <tbody class="credit-data save-table" id="inquired-info-other">
          <tr class="inquired-info-other-list" hidden>
            <td colspan="10">查詢日期</td>
            <td colspan="10">查詢機構</td>
            <td colspan="10">查詢理由</td>
          </tr>
          <!-- <tr>
            <td colspan="10">109/03/26</td>
            <td colspan="10">街口電子支付股份有限公司</td>
            <td colspan="10">身份確認</td>
          </tr> -->
        </tbody>
        <tbody class="credit-btn action-btn">
          <th colspan="30" style="text-align: center;">
            <button class="btn" onclick="addInquiredInfoOther('#inquired-info-other')">
              <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-plus" fill="currentColor"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                  d="M8 3.5a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5H4a.5.5 0 0 1 0-1h3.5V4a.5.5 0 0 1 .5-.5z" />
                <path fill-rule="evenodd" d="M7.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0V8z" />
              </svg>
            </button>
            <button class="btn" onclick="deleteInquiredInfoOther('.inquired-info-other-list')">
              <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                  d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z" />
                <path fill-rule="evenodd"
                  d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z" />
              </svg>
            </button>
          </th>
        </tbody>
        <tbody class="credit-table"><th colspan="30"></th></tbody>
        <tbody class="credit-table">
          <th colspan="15">【當事人查詢記錄】</th>
          <th colspan="15" id="inquired-info-myself-title">查資料庫中無</th>
        </tbody>
        <tbody class="credit-data save-table" id="inquired-info-myself">
          <tr class="inquired-info-myself-list" hidden>
            <td colspan="10">查詢日期</td>
            <td colspan="10">申請方式</td>
            <td colspan="10">申請原因</td>
          </tr>
          <!-- <tr>
            <td colspan="10">108/11/22</td>
            <td colspan="10">本人親臨</td>
            <td colspan="10">暸解信用紀錄</td>
          </tr> -->
        </tbody>
        <tbody class="credit-btn action-btn">
          <th colspan="30" style="text-align: center;">
            <button class="btn" onclick="addInquiredInfoMyself('#inquired-info-myself')">
              <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-plus" fill="currentColor"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                  d="M8 3.5a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5H4a.5.5 0 0 1 0-1h3.5V4a.5.5 0 0 1 .5-.5z" />
                <path fill-rule="evenodd" d="M7.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0V8z" />
              </svg>
            </button>
            <button class="btn" onclick="deleteInquiredInfoMyself('.inquired-info-myself-list')">
              <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                  d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z" />
                <path fill-rule="evenodd"
                  d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z" />
              </svg>
            </button>
          </th>
        </tbody>
        <tbody class="credit-table"><th colspan="30"></th></tbody>
        <tbody class="credit-table">
          <th colspan="15">【附加訊息】</th>
          <th colspan="15" id="extra-info-title">查資料庫中無</th>
        </tbody>
        <tbody class="credit-data save-table" id="extra-info">
          <tr class="extra-info-list" hidden>
            <td colspan="10">登錄日期</td>
            <td colspan="20" style="text-align: start;">註記內容</td>
          </tr>
          <!-- <tr>
            <td colspan="10">108/02/26</td>
            <td colspan="20" style="text-align: start;">花旗(台灣)銀行108.02.26告知：當事人已依消債條例前置協商機制與債權金融機構達成還款協議，當事人依約履行中。</td>
          </tr> -->
        </tbody>
        <tbody class="credit-btn action-btn">
          <th colspan="30" style="text-align: center;">
            <button class="btn" onclick="addExtraInfo('#extra-info')">
              <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-plus" fill="currentColor"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                  d="M8 3.5a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5H4a.5.5 0 0 1 0-1h3.5V4a.5.5 0 0 1 .5-.5z" />
                <path fill-rule="evenodd" d="M7.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0V8z" />
              </svg>
            </button>
            <button class="btn" onclick="deleteExtraInfo('.extra-info-list')">
              <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                  d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z" />
                <path fill-rule="evenodd"
                  d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z" />
              </svg>
            </button>
          </th>
        </tbody>
        <tbody class="credit-table"><th colspan="30"></th></tbody>
        <tbody class="credit-table">
          <th colspan="15">【信用評分】</th>
          <th colspan="15" hidden>查資料庫中無</th>
        </tbody>
        <tbody class="credit-data save-table" id="credit-score-info">
          <tr>
            <td colspan="30" style="text-align: start;">信用評分：<input style="width:50px;" id="score"> 分</td>
          </tr>
          <tr>
            <td colspan="30" style="text-align: start;">此次所有受評者中，有 <input style="width:50px;" id="per-cent-start"> %~ <input style="width:50px;" id="per-cent-end"> %的人其評分低於&emsp;&emsp;台端信用評分</td>
          </tr>
          <tr style="border-bottom:2px black dashed;"><td colspan="30"></td></tr>
          <tr>
            <td colspan="30" style="text-align: start;">台端之信用評分位於上述百分位區間之主要原因依序說明如下：</td>
          </tr>
          <!-- <tr>
            <td colspan="30" style="text-align: start;">※台端近一年內有較高的信用卡循環信用金額</td>
          </tr>
          <tr>
            <td colspan="30" style="text-align: start;">※台端近一年內曾有較高的信用卡額度使用率</td>
          </tr>
          <tr>
            <td colspan="30" style="text-align: start;">※台端近12期內授信金額未能有效降低</td>
          </tr>
          <tr>
            <td colspan="30" style="text-align: start;">※台端目前有效信用卡持卡期間較短</td>
          </tr> -->
        </tbody>
        <tbody class="credit-btn action-btn">
          <th colspan="30" style="text-align: center;">
            <button class="btn" onclick="addCreditScoreInfo('#credit-score-info')">
              <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-plus" fill="currentColor"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                  d="M8 3.5a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5H4a.5.5 0 0 1 0-1h3.5V4a.5.5 0 0 1 .5-.5z" />
                <path fill-rule="evenodd" d="M7.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0V8z" />
              </svg>
            </button>
            <button class="btn" onclick="deleteCreditScoreInfo('.credit-score-info-list')">
              <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                  d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z" />
                <path fill-rule="evenodd"
                  d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z" />
              </svg>
            </button>
          </th>
        </tbody>
        <tbody class="credit-table"><th colspan="30"></th></tbody>
      </table>
    </div>
    <br>
    <div class="bottom">
      <ul style="text-align: start;">
        <li class="list-li">
          <div class="icon-div">※</div>
          上述信用資料若有發現金融機構報送的資料有誤，您可以向原來提報的金融機構反應，請其向本中心通知改正；
          有可以用書面的方式檢具身份證明文件(如有證據請一併檢附)，直接向本中心反映，本中心會主動協助將您的資料與報送的金融機構
          聯繫，如果查明屬實，便會把您的資料更改正確。
        </li>
        <li class="list-li">
          <div class="icon-div">※</div>
          信用卡資訊與信用卡繳款資訊之更新，乃收件隨即處理上線，各發卡機構報送頻率原則上每週一次惟各發卡機構報送週期不一，
          故信用卡類資訊無單一資料截止日期。
        </li>
        <li class="list-li">
          <div class="icon-div">※</div>
          本中心信用報告所示信用資料僅供申請人參考，不能等同或證明資料當事人於全體金融機構實際存在之所有金融負債(含保證)情形。
          申請人為任何決定時，請勿以本信用報告作為唯一依據。
        </li>
        <li class="list-li">
          <div class="icon-div">※</div>
          本中心所提供信用報告之各項資料，為報經金融監督管理委員會核備之資料揭露期限內之新用資訊。
        </li>
        <li class="list-li">
          <div class="icon-div">※</div>
          信用報告為個人隱私之重要文件，請妥善保管，切勿輕信或輕易交給他人，以避免為不法用途。
        </li>
      </ul>
  </div>
  <div style="margin-bottom:3px;"><<電子密章：<input style="text-align: center;border: none;width:35%;"> >></div>
  <div>***報表結束***</div>
</body>

<script>
  var bank_loan_num = 1;
  var debt_info_num = 1;
  var credit_card_info_num = 1;
  var inquired_info_num =1;
  var inquired_info_num_other =1;
  var inquired_info_num_myself =1;
  var extra_info_num =1;
  var credit_score_info_num =1;
  var credit_card_account_num =1;

  function switchCase(title_element,list_title){
    $(title_element).first().prop('hidden', function(i, v) { return !v; });
    $(list_title).prop('hidden', function(i, v) { return !v; });
  }

  // 銀行借款資訊
  function createBankLoanHtml (num,bank = '',contract_amount='',loan_balance='',title='',delay=''){
    return`
    <tr class="bank-loan-list" id="bank_loan_${num}">
      <td colspan="6"><input id="bank_loan_name_${num}" value="${bank}"></td>
      <td colspan="6"><input id="bank_loan_contract_amount_${num}" value="${contract_amount}"></td>
      <td colspan="6"><input id="bank_loan_balance_${num}" value="${loan_balance}"></td>
      <td colspan="6"><input id="bank_loan_title_${num}" value="${title}"></td>
      <td colspan="6"><input id="bank_loan_delay_${num}" value="${delay}"></td>
    </tr>`;
  }

  function addBankLoan(element='',data='',isMongo=0) {
    if(element){
      if(bank_loan_num==1){
        // $('#bank-loan-title').prop('hidden', true);
        // $(".bank-loan-list").first().prop('hidden', false);
        switchCase('#bank-loan-title','.bank-loan-list');
      }
      if(data ==''){
        html = createBankLoanHtml(bank_loan_num);
        addTableList(element, html)
        bank_loan_num += 1;
      }else{
      // Object.keys(data).forEach(function (key) {
      //   if(isMongo){
      //     value = data[key];
      //   }else{
      //     value = data[key]['business_num_'+ business_num];
      //   }
      //   html = createBusinessHtml(business_num,value);
      //   addTableList(element, html)
      //   business_num+=1;
      // })
      }
    }
  }

  function deleteBankLoan(element='.bank-loan-list') {
    if (bank_loan_num > 1) {
      deleteTableList(element);
      bank_loan_num -= 1;
    }
    if (bank_loan_num == 1 && ! $('.bank-loan-list').first().is(":hidden")) {
      // $(".bank-loan-list").first().prop('hidden', true);
      // $("#bank-loan-title").prop('hidden', false);
      switchCase('#bank-loan-title','.bank-loan-list');
    }
  }

  // 逾期催收或呆帳資訊
  function createDebtInfoHtml(num,type='',company='',bank='',loan_type='',not_delay='',delay=''){
    return`
    <tr class="debt-info-list" id="debt_info_${num}">
      <td colspan="5"><input id="debt_info_type_${num}" value="${type}"></td>
      <td colspan="5"><input id="debt_info_company_${num}" value="${company}"></td>
      <td colspan="5"><input id="debt_info_bank_${num}" value="${bank}"></td>
      <td colspan="5"><input id="debt_info_loan_type_${num}" value="${loan_type}"></td>
      <td colspan="5"><input id="debt_info_not_delay_${num}" value="${not_delay}"></td>
      <td colspan="5"><input id="debt_info_delay_${num}" value="${delay}"></td>
    </tr>`;
  }

  function addDebtInfo(element='',data='',isMongo=0){
    if(element){
      if(debt_info_num==1){
        // $('#bank-loan-title').prop('hidden', true);
        // $(".bank-loan-list").first().prop('hidden', false);
        switchCase('#debt-info-title','.debt-info-list');
      }
      if(data ==''){
        html = createDebtInfoHtml(debt_info_num);
        addTableList(element, html)
        debt_info_num += 1;
      }else{
      // Object.keys(data).forEach(function (key) {
      //   if(isMongo){
      //     value = data[key];
      //   }else{
      //     value = data[key]['business_num_'+ business_num];
      //   }
      //   html = createBusinessHtml(business_num,value);
      //   addTableList(element, html)
      //   business_num+=1;
      // })
      }
    }
  }

  function deleteDebtInfo(element='.debt-info-list') {
    if (debt_info_num > 1) {
      deleteTableList(element);
      debt_info_num -= 1;
    }
    if (debt_info_num == 1 && ! $('.debt-info-list').first().is(":hidden")) {
      // $(".bank-loan-list").first().prop('hidden', true);
      // $("#bank-loan-title").prop('hidden', false);
      switchCase('#debt-info-title','.debt-info-list');
    }
  }

  // 信用卡資訊
  function createCreditCardInfoHtml (num,bank = '',name='',quota='',start_at='',end_at='',status=''){
    return`
    <tr class="credit-card-info-list" id="credit_card_info_${num}">
      <td colspan="5"><input id="credit_card_info_bank_${num}" value="${bank}"></td>
      <td colspan="5"><input id="credit_card_info_name_${num}" value="${name}"></td>
      <td colspan="5"><input id="credit_card_info_quota_${num}" value="${quota}"></td>
      <td colspan="5"><input id="credit_card_info_start_at_${num}" value="${start_at}"></td>
      <td colspan="5"><input id="credit_card_info_end_at_${num}" value="${end_at}"></td>
      <td colspan="5"><input id="credit_card_info_status_${num}" value="${status}"></td>
    </tr>`;
  }

  function addCreditCard(element='',data='',isMongo=0){
    if(element){
      if(credit_card_info_num==1){
        // $('#bank-loan-title').prop('hidden', true);
        // $(".bank-loan-list").first().prop('hidden', false);
        switchCase('#credit-card-info-title','.credit-card-info-list');
      }
      if(data ==''){
        html = createCreditCardInfoHtml(credit_card_info_num);
        addTableList(element, html)
        credit_card_info_num += 1;
      }else{
      // Object.keys(data).forEach(function (key) {
      //   if(isMongo){
      //     value = data[key];
      //   }else{
      //     value = data[key]['business_num_'+ business_num];
      //   }
      //   html = createBusinessHtml(business_num,value);
      //   addTableList(element, html)
      //   business_num+=1;
      // })
      }
    }
  }

  function deleteCreditCard(element='.credit-card-info-list') {
    if (credit_card_info_num > 1) {
      deleteTableList(element);
      credit_card_info_num -= 1;
    }
    if (credit_card_info_num == 1 && ! $('.credit-card-info-list').first().is(":hidden")) {
      // $(".bank-loan-list").first().prop('hidden', true);
      // $("#bank-loan-title").prop('hidden', false);
      switchCase('#credit-card-info-title','.credit-card-info-list');
    }
  }

  // 信用卡戶帳款資訊
  function createCreditCardAccountHtml (num,date='',bank = '',name='',quota='',advance_loan='',case_type='',last_month='',now='',waite='',status=''){
    return`
    <tr class="credit-card-account-list" id="credit_card_account_${num}">
      <td colspan="3"><input class="credit-card-account-date" id="credit_card_account_date_${num}" value="${date}"></td>
      <td colspan="3"><input id="credit_card_account_bank_${num}" value="${bank}"></td>
      <td colspan="3"><input id="credit_card_account_name_${num}" value="${name}"></td>
      <td colspan="3"><input id="credit_card_account_quota_${num}" value="${quota}"></td>
      <td colspan="3"><input id="credit_card_account_advance_loan_${num}" value="${advance_loan}"></td>
      <td colspan="3"><input id="credit_card_account_case_type_${num}" value="${case_type}"></td>
      <td colspan="3"><input id="credit_card_account_last_month_${num}" value="${last_month}"></td>
      <td colspan="3"><input id="credit_card_account_now_${num}" value="${now}"></td>
      <td colspan="3"><input id="credit_card_account_waite_${num}" value="${waite}"></td>
      <td colspan="3"><input id="credit_card_account_status_${num}" value="${status}"></td>
    </tr>
    <tr class="credit-card-account-list" style="border-bottom:2px black dashed;" hidden><td colspan="30"></td></tr>`;
  }

  function addCreditCardAccount(element='',data='',isMongo=0){
    if(element){
      if(credit_card_account_num==1){
        // $('#bank-loan-title').prop('hidden', true);
        // $(".bank-loan-list").first().prop('hidden', false);
        switchCase('#credit-card-account-title','.credit-card-account-list');
      }
      if(data ==''){
        html = createCreditCardAccountHtml(credit_card_account_num);
        addTableList(element, html)
        credit_card_account_num += 1;
      }else{
        // console.log(data);
        Object.keys(data).forEach(function (key) {
          if(isMongo){
            date = '';
            bank = data[key]['bank'];
            name = data[key]['cardType'];
            quota = data[key]['creditLimit'];
            advance_loan = data[key]['cashAdvance'];
            case_type = '';
            last_month = data[key]['paymentStatus'] + ' ' + data[key]['delayStatus'];
            now = data[key]['accountsPayable'];
            waite = data[key]['deadlineAccountsPayable'];
            status = data[key]['lawOfObligationsStatus'];

            html = createCreditCardAccountHtml(credit_card_account_num,date,bank,name,quota,advance_loan,case_type,last_month,now,waite,status);
            addTableList(element, html)
            // credit_card_account_num += 1;
          }else{
            date = data[key]['credit_card_account_date_'+ credit_card_account_num];
            bank = data[key]['credit_card_account_bank_'+ credit_card_account_num];
            name = data[key]['credit_card_account_name_'+ credit_card_account_num];
            quota = data[key]['credit_card_account_quota_'+ credit_card_account_num];
            advance_loan = data[key]['credit_card_account_advance_loan_'+ credit_card_account_num];
            case_type = data[key]['credit_card_account_case_type_'+ credit_card_account_num];
            last_month = data[key]['credit_card_account_last_month_'+ credit_card_account_num];
            now = data[key]['credit_card_account_now_'+ credit_card_account_num];
            waite = data[key]['credit_card_account_waite_'+ credit_card_account_num];
            status = data[key]['credit_card_account_status_'+ credit_card_account_num];

            html = createCreditCardAccountHtml(credit_card_account_num,date,bank,name,quota,advance_loan,case_type,last_month,now,waite,status);
            addTableList(element, html)
          }
          // html = createBusinessHtml(business_num,value);
          // addTableList(element, html)
          credit_card_account_num += 1;
        })
      }
    }
  }

  function deleteCreditCardAccount(element='.credit-card-account-list') {
    if (credit_card_account_num > 1) {
      deleteTableList(element);
      credit_card_account_num -= 1;
    }
    if (credit_card_account_num == 1 && ! $('.credit-card-account-list').first().is(":hidden")) {
      // $(".bank-loan-list").first().prop('hidden', true);
      // $("#bank-loan-title").prop('hidden', false);
      switchCase('#credit-card-account-title','.credit-card-account-list');
    }
  }
  $(document).ready(function(){
    var checkout_date;
    var list_len;
    var last_checkout_date;
    var rex = new RegExp("^([0-9]{3})[./]{1}([0-9]{1,2})[./]{1}([0-9]{1,2})$");
    $(document).on('change','.credit-card-account-date:last',function(){
      checkout_date = $(this).val();
      list_len = $('.credit-card-account-date').length;
      list_len -= 2;
      last_checkout_date = $(`.credit-card-account-date:eq(${list_len})`).val();
      // console.log(last_checkout_date);
      // console.log(checkout_date);
      if (rex.exec(checkout_date) != null && rex.exec(last_checkout_date) != null){
        checkout_date = checkout_date.split('/');
        last_checkout_date = last_checkout_date.split('/');
        if(last_checkout_date[1] != checkout_date[1]){
          list_len = $('.credit-card-account-list').length;
          list_len -= 3;
          $(`.credit-card-account-list:eq(${list_len})`).prop('hidden', function(i, v) { return !v; });
        }
      }
    });
  });

  // 被查詢紀錄
  function createInquiredInfoHtml (num,date = '',mechanism='',reason=''){
    return`
    <tr class="inquired-info-list" id="inquired_info_${num}">
      <td colspan="10"><input id="inquired_info_date_${num}" value="${date}"></td>
      <td colspan="10"><input id="inquired_info_mechanism_${num}" value="${mechanism}"></td>
      <td colspan="10"><input id="inquired_info_reason_${num}" value="${reason}"></td>
    </tr>`;
  }

  function addInquiredInfo(element='',data='',isMongo=0){
    if(element){
      if(inquired_info_num==1){
        // $('#bank-loan-title').prop('hidden', true);
        // $(".bank-loan-list").first().prop('hidden', false);
        switchCase('#inquired-info-title','.inquired-info-list');
      }
      if(data ==''){
        html = createInquiredInfoHtml(inquired_info_num);
        addTableList(element, html)
        inquired_info_num += 1;
      }else{
      // Object.keys(data).forEach(function (key) {
      //   if(isMongo){
      //     value = data[key];
      //   }else{
      //     value = data[key]['business_num_'+ business_num];
      //   }
      //   html = createBusinessHtml(business_num,value);
      //   addTableList(element, html)
      //   business_num+=1;
      // })
      }
    }
  }

  function deleteInquiredInfo(element='.inquired-info-list') {
    if (inquired_info_num > 1) {
      deleteTableList(element);
      inquired_info_num -= 1;
    }
    if (inquired_info_num == 1 && ! $('.inquired-info-list').first().is(":hidden")) {
      // $(".bank-loan-list").first().prop('hidden', true);
      // $("#bank-loan-title").prop('hidden', false);
      switchCase('#inquired-info-title','.inquired-info-list');
    }
  }

  // 被電子支付或電子票證發行機構查詢紀錄
  function createInquiredInfoOtherHtml (num,date = '',mechanism='',reason=''){
    return`
    <tr class="inquired-info-other-list" id="inquired_info_other_${num}">
      <td colspan="10"><input id="inquired_info_other_date_${num}" value="${date}"></td>
      <td colspan="10"><input id="inquired_info_other_mechanism_${num}" value="${mechanism}"></td>
      <td colspan="10"><input id="inquired_info_other_reason_${num}" value="${reason}"></td>
    </tr>`;
  }

  function addInquiredInfoOther(element='',data='',isMongo=0){
    if(element){
      if(inquired_info_num_other==1){
        // $('#bank-loan-title').prop('hidden', true);
        // $(".bank-loan-list").first().prop('hidden', false);
        switchCase('#inquired-info-other-title','.inquired-info-other-list');
      }
      if(data ==''){
        html = createInquiredInfoOtherHtml(inquired_info_num_other);
        addTableList(element, html)
        inquired_info_num_other += 1;
      }else{
      // Object.keys(data).forEach(function (key) {
      //   if(isMongo){
      //     value = data[key];
      //   }else{
      //     value = data[key]['business_num_'+ business_num];
      //   }
      //   html = createBusinessHtml(business_num,value);
      //   addTableList(element, html)
      //   business_num+=1;
      // })
      }
    }
  }

  function deleteInquiredInfoOther(element='.inquired-info-other-list') {
    if (inquired_info_num_other > 1) {
      deleteTableList(element);
      inquired_info_num_other -= 1;
    }
    if (inquired_info_num_other == 1 && ! $('.inquired-info-other-list').first().is(":hidden")) {
      // $(".bank-loan-list").first().prop('hidden', true);
      // $("#bank-loan-title").prop('hidden', false);
      switchCase('#inquired-info-other-title','.inquired-info-other-list');
    }
  }

  // 當事人查詢記錄

  function createInquiredInfoMyselfHtml (num,date = '',mechanism='',reason=''){
    return`
    <tr class="inquired-info-myself-list" id="inquired_info_myself_${num}">
      <td colspan="10"><input id="inquired_info_myself_date_${num}" value="${date}"></td>
      <td colspan="10"><input id="inquired_info_myself_mechanism_${num}" value="${mechanism}"></td>
      <td colspan="10"><input id="inquired_info_myself_reason_${num}" value="${reason}"></td>
    </tr>`;
  }

  function addInquiredInfoMyself(element='',data='',isMongo=0){
    if(element){
      if(inquired_info_num_myself==1){
        // $('#bank-loan-title').prop('hidden', true);
        // $(".bank-loan-list").first().prop('hidden', false);
        switchCase('#inquired-info-myself-title','.inquired-info-myself-list');
      }
      if(data ==''){
        html = createInquiredInfoMyselfHtml(inquired_info_num_myself);
        addTableList(element, html)
        inquired_info_num_myself += 1;
      }else{
      // Object.keys(data).forEach(function (key) {
      //   if(isMongo){
      //     value = data[key];
      //   }else{
      //     value = data[key]['business_num_'+ business_num];
      //   }
      //   html = createBusinessHtml(business_num,value);
      //   addTableList(element, html)
      //   business_num+=1;
      // })
      }
    }
  }

  function deleteInquiredInfoMyself(element='.inquired-info-myself-list') {
    if (inquired_info_num_myself > 1) {
      deleteTableList(element);
      inquired_info_num_myself -= 1;
    }
    if (inquired_info_num_myself == 1 && ! $('.inquired-info-myself-list').first().is(":hidden")) {
      // $(".bank-loan-list").first().prop('hidden', true);
      // $("#bank-loan-title").prop('hidden', false);
      switchCase('#inquired-info-myself-title','.inquired-info-myself-list');
    }
  }

  // 附加訊息
  function createExtraInfoHtml (num,date = '',reason=''){
    return`
    <tr class="extra-info-list" id="extra_info_${num}">
      <td colspan="10"><input id="extra_info_date_${num}" value="${date}"></td>
      <td colspan="20" style="text-align: start;"><input id="extra_info_reason_${num}" value="${reason}"></td>
    </tr>`;
  }

  function addExtraInfo(element='',data='',isMongo=0){
    if(element){
      if(extra_info_num==1){
        // $('#bank-loan-title').prop('hidden', true);
        // $(".bank-loan-list").first().prop('hidden', false);
        switchCase('#extra-info-title','.extra-info-list');
      }
      if(data ==''){
        html = createExtraInfoHtml(extra_info_num);
        addTableList(element, html)
        extra_info_num += 1;
      }else{
      // Object.keys(data).forEach(function (key) {
      //   if(isMongo){
      //     value = data[key];
      //   }else{
      //     value = data[key]['business_num_'+ business_num];
      //   }
      //   html = createBusinessHtml(business_num,value);
      //   addTableList(element, html)
      //   business_num+=1;
      // })
      }
    }
  }

  function deleteExtraInfo(element='.extra-info-list') {
    if (extra_info_num > 1) {
      deleteTableList(element);
      extra_info_num -= 1;
    }
    if (extra_info_num == 1 && ! $('.extra-info-list').first().is(":hidden")) {
      // $(".bank-loan-list").first().prop('hidden', true);
      // $("#bank-loan-title").prop('hidden', false);
      switchCase('#extra-info-title','.extra-info-list');
    }
  }

  // 信用評分
  function createCreditScoreInfoHtml (num,reason=''){
    return`
    <tr class="credit-score-info-list" id="credit_score_info_${num}">
      <td colspan="30">※<input style="text-align: start;width:95%;" id="credit_score_info_reason_${num}" value="${reason}"></td>
    </tr>`;
  }

  function addCreditScoreInfo(element='',data='',isMongo=0){
    if(element){
      if(data ==''){
        html = createCreditScoreInfoHtml(credit_score_info_num);
        addTableList(element, html)
        credit_score_info_num += 1;
      }else{
      // Object.keys(data).forEach(function (key) {
      //   if(isMongo){
      //     value = data[key];
      //   }else{
      //     value = data[key]['business_num_'+ business_num];
      //   }
      //   html = createBusinessHtml(business_num,value);
      //   addTableList(element, html)
      //   business_num+=1;
      // })
      }
    }
  }

  function deleteCreditScoreInfo(element='.credit-score-info-list') {
    if (credit_score_info_num > 1) {
      deleteTableList(element);
      credit_score_info_num -= 1;
    }
  }

  $(document).ready(function () {
    var urlString = window.location.href;
    var url = new URL(urlString);
    var certification_id = url.searchParams.get("certification");
    var imageId = url.searchParams.get("id");
    var textId = url.searchParams.get("name");
    var check = url.searchParams.get("check");

    // 編輯按鈕
    $( "#edit_click" ).on('click',function() {
      switchCase('#test','.action-btn');
      edit_click();
    });

    // 儲存按鈕
    $( "#save_click" ).on('click',function() {
      save_click('credit_investigation', imageId, certification_id);
    });

    // 送出按鈕
    $( "#send_click" ).on('click',function() {
      send_click('credit_investigation', imageId, certification_id);
    });

    fetchReport('credit_investigation', imageId, check, certification_id, function (data) {
      if (!data) {
        return;
      }
      // console.log(data);
      if(data.data_type){
        fillReport(data,'credit_investigation','report');
        if(data.table_list["credit-card-account"]){
            addCreditCardAccount('#credit-card-account',data.table_list["credit-card-account"]);
        }
        fillReport(data.table_list["credit-score-info"][0],'credit_investigation','report');
        // if(data.table_list["directorList"]){
        //   addDirector('#directorList',data.table_list["directorList"]);
        // }
        // if(data.table_list["managerList"]){
        //   addManager('#managerList',data.table_list["managerList"]);
        // }
        // if(data.table_list["legalpersonLsit"]){
        //   addLegalperson('#legalpersonLsit',data.table_list["legalpersonLsit"]);
        // }
      }else{
        // fillReport(data.investigationInfo,'credit_investigation','report');
        // fillReport(data.companyInfo,'amendment_of_register','report');

        Object.keys(data.investigationInfo).forEach(function (key) {
          // console.log(data.investigationInfo[key]);
          if(data.investigationInfo[key].creditCardAccountInfo){
            var creditCardAccountInfo = data.investigationInfo[key].creditCardAccountInfo.detailList;
            creditCardAccountInfo.forEach(function(value, index, array){
              Object.keys(array[index]).forEach(function (key) {
                var data_info = array[index][key];
                  addCreditCardAccount('#credit-card-account',data_info,1);
              })
              html = `<tr class="credit-card-account-list" style="border-bottom:2px black dashed;" ><td colspan="30"></td></tr>`;
              addTableList('#credit-card-account', html)
            })
          }

        })

        // if(data.investigationInfo.detailList.length!=0){
        //   addCreditCardAccount('#credit-card-account',data.investigationInfo.detailList,1);
        // }
        // if(data.registerOfShareholders.length!=0){
        //   addDirector('#directorList',data.registerOfShareholders,1);
        // }
        // if(data.managers.length!=0){
        //   addManager('#managerList',data.managers,1);
        // }
        // if(data.representativeOfLegalPerson.length!=0){
        //   addLegalperson('#legalpersonLsit',data.representativeOfLegalPerson,1);
        // }
      }
      if (data.message) {
        alert(data.message);
      }
    });

  });
</script>

</html>

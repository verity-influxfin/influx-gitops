<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/admin/css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/admin/font-awesome-4.1.0/css/font-awesome.min.css" />
  <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/admin/css/ocr/A3.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="<?= base_url() ?>assets/admin/js/ocr/fetch.js"></script>
  <title>聯徵資料表</title>
  <style>
    .icon-div {
      position: absolute;
      left: -20px;
    }

    .list-li {
      list-style: none;
      position: relative;
    }

    .credit-info .ctr {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: fit-content;
    }

    .credit-info .credit-tr {
      display: flex;
    }

    .credit-info .credit-th {
      padding: 5px;
    }

    .credit-info .credit-th,
    .credit-info .credit-td {
      width: 100%;
      position: relative;
      border: 1px solid #000000;
    }

    .credit-info .td-row {
      display: flex;
    }

    .credit-info .td-row:not(:last-of-type) {
      border-bottom: 1px solid #000000;
    }

    .credit-info .td-row div {
      width: 100%;
      position: relative;
    }

    .credit-info .td-row div:not(:last-of-type) {
      border-right: 1px solid #000000;
    }

    .credit-flex {
      display: flex;
    }

    .credit-flex div {
      width: 100%;
    }

    .form-control {
      padding: 0px;
      border-radius: 0px;
      border: 0px;
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
    <h3 style="position: relative;">
      <div>當事人綜合信用報告</div>
    </h3>
    <div>謹慎使用信用報告&emsp;&emsp;&emsp;&emsp;保障良好信用</div>
    <p>本報告僅供您參考,其所載信用資訊並非金融機構准駁金融交易之唯一依據。</p>
    <div style="text-align: start;">
      <div><span>身分證號：</span><input class="input-underline company-name save-this result-this" id="personId" type="text"></div>
      <div><span>姓名：</span><input class="input-underline company-name save-this result-this" id="personName" type="text"></div>
    </div>
    <div>截至印表時間（<input class="input-underline save-this result-this" style="width:10%;" id="printDatetime"> ）止，<br>金融機構報送您尚在揭露期限（經金融監督管理委員會核備）之信用資訊如下：<br>（信用資訊項目有紀錄時，敬請您參閱如后信用明細資訊表）</div>
    <div class="credit-info">
      <div class="credit-thead">
        <div class="credit-tr">
          <div class="credit-th">類別</div>
          <div class="credit-th">信用資訊項目</div>
          <div class="credit-th">有/無信用資訊</div>
          <div class="credit-th">參閱信用明細</div>
        </div>
      </div>
      <div class="credit-tbody">
        <div class="credit-tr">
          <div class="credit-td" style="width:25%"><span class="ctr">一、借款資訊</span></div>
          <div class="credit-td" style="width:75%;padding: 0px;">
            <div class="td-row">
              <div><span class="ctr">1.借款總餘額資訊</span></div>
              <div><input class="form-control save-this" id="liabilities_totalAmount_existCreditInfo"></div>
              <div><input class="form-control save-this" id="liabilities_totalAmount_creditDetail"></div>
            </div>
            <div class="td-row">
              <div><span class="ctr">2.共同債務/從債務/其他債務資訊</span></div>
              <div><input class="form-control save-this" id="liabilities_metaInfo_existCreditInfo"></div>
              <div><input class="form-control save-this" id="liabilities_metaInfo_creditDetail"></div>
            </div>
            <div class="td-row">
              <div><span class="ctr">3.借款逾期、催收或呆帳紀錄</span></div>
              <div><input class="form-control save-this" id="liabilities_badDebtInfo_existCreditInfo"></div>
              <div><input class="form-control save-this" id="liabilities_badDebtInfo_creditDetail"></div>
            </div>
          </div>
        </div>

        <div class="credit-tr">
          <div class="credit-td" style="width:25%"><span class="ctr">二、信用卡資訊</span></div>
          <div class="credit-td" style="width:75%;padding: 0px;">
            <div class="td-row">
              <div><span class="ctr">1.信用卡持卡紀錄</span></div>
              <div><input class="form-control save-this" id="creditCard_cardInfo_existCreditInfo"></div>
              <div><input class="form-control save-this" id="creditCard_cardInfo_creditDetail"></div>
            </div>
            <div class="td-row">
              <div><span class="ctr">2.信用卡帳款總餘額資訊</span></div>
              <div><input class="form-control save-this" id="creditCard_totalAmount_existCreditInfo"></div>
              <div><input class="form-control save-this" id="creditCard_totalAmount_creditDetail"></div>
            </div>
          </div>
        </div>

        <div class="credit-tr">
          <div class="credit-td" style="width:25%"><span class="ctr">三、票信資訊</span></div>
          <div class="credit-td" style="width:75%;padding: 0px;">
            <div class="td-row">
              <div><span class="ctr">1.大額存款不足退票資訊</span></div>
              <div><input class="form-control save-this" id="checkingAccount_largeAmount_existCreditInfo"></div>
              <div><input class="form-control save-this" id="checkingAccount_largeAmount_creditDetail"></div>
            </div>
            <div class="td-row">
              <div><span class="ctr">2.票據拒絕往來資訊</span></div>
              <div><input class="form-control save-this" id="checkingAccount_rejectInfo_existCreditInfo"></div>
              <div><input class="form-control save-this" id="checkingAccount_rejectInfo_creditDetail"></div>
            </div>
          </div>
        </div>

        <div class="credit-tr">
          <div class="credit-td" style="width:25%"><span class="ctr">四、查詢紀錄</span></div>
          <div class="credit-td" style="width:75%;padding: 0px;">
            <div class="td-row">
              <div><span class="ctr">1.被查詢紀錄</span></div>
              <div><input class="form-control save-this" id="queryLog_queriedLog_existCreditInfo"></div>
              <div><input class="form-control save-this" id="queryLog_queriedLog_creditDetail"></div>
            </div>
            <div class="td-row">
              <div><span class="ctr">2.當事人查詢信用報告紀錄</span></div>
              <div><input class="form-control save-this" id="queryLog_applierSelfQueriedLog_existCreditInfo"></div>
              <div><input class="form-control save-this" id="queryLog_applierSelfQueriedLog_creditDetail"></div>
            </div>
          </div>
        </div>

        <div class="credit-tr">
          <div class="credit-td" style="width:25%"><span class="ctr">五、其他</span></div>
          <div class="credit-td" style="width:75%;padding: 0px;">
            <div class="td-row">
              <div><span class="ctr">1.附加訊息資訊</span></div>
              <div><input class="form-control save-this" id="other_extraInfo_existCreditInfo"></div>
              <div><input class="form-control save-this" id="other_extraInfo_creditDetail"></div>
            </div>
            <div class="td-row">
              <div><span class="ctr">2.主債務債權轉讓及清償資訊</span></div>
              <div><input class="form-control save-this" id="other_mainInfo_existCreditInfo"></div>
              <div><input class="form-control save-this" id="other_mainInfo_creditDetail"></div>
            </div>
            <div class="td-row">
              <div><span class="ctr">3.共同債務/從債務/其他債務轉讓資訊</span></div>
              <div><input class="form-control save-this" id="other_metaInfo_existCreditInfo"></div>
              <div><input class="form-control save-this" id="other_metaInfo_creditDetail"></div>
            </div>
            <div class="td-row">
              <div><span class="ctr">4.信用卡債權轉讓及清償資訊</span></div>
              <div><input class="form-control save-this" id="other_creditCardTransferInfo_existCreditInfo"></div>
              <div><input class="form-control save-this" id="other_creditCardTransferInfo_creditDetail"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <br>
    <br>
    <br>
    <p>各項揭露期限內之信用紀錄明細資訊如下表說明：</p>
	<h2>借款資訊</h2>
    <hr>
    <div>
      <div>表B1</div>
      <div>一、借款餘額明細資訊</div>
    </div>
    <div class="credit-info">
      <div class="credit-thead">
        <div class="credit-tr">
          <div class="credit-th">金融機構名稱</div>
          <div class="credit-th">資料年月</div>
          <div class="credit-th">訂約金額</div>
          <div class="credit-th">未逾期金額</div>
          <div class="credit-th">逾期金額</div>
          <div class="credit-th">科目</div>
          <div class="credit-th">用途</div>
          <div class="credit-th">最近十二個月遲延還款紀錄</div>
        </div>
      </div>
      <div class="credit-tbody save-table" id="B1_table"></div>

      <div style="padding: 10px;">
        <button type="button" class="btn btn-success add" style="margin-right:20px"><i class="fa fa-plus"></i></button>
        <button type="button" class="btn btn-danger remove"><i class="fa fa-minus"></i></button>
      </div>
    </div>
    <br>
    <br>
    <div class="credit-info">
      <div class="credit-thead">
        <div class="credit-tr">
          <div class="credit-th">金融機構名稱</div>
          <div class="credit-th">資料日期</div>
          <div class="credit-th">撥款金額</div>
          <div class="credit-th">還款金額</div>
          <div class="credit-th">科目</div>
          <div class="credit-th">用途</div>
          <div class="credit-th">延遲還款紀錄</div>
        </div>
      </div>
      <div class="credit-tbody save-table" id="B1-extra_table"></div>

      <div style="padding: 10px;">
        <button type="button" class="btn btn-success add" style="margin-right:20px"><i class="fa fa-plus"></i></button>
        <button type="button" class="btn btn-danger remove"><i class="fa fa-minus"></i></button>
      </div>
    </div>
    <p style="text-align: start;">說明：<br>
      <ul style="text-align: start;list-style: none;padding: 0px;">
        <li>未結案之借款總餘額資訊，係指截至印表時間止，金融機構報送之最新借款總餘額，其計算方式為：底止之借款總餘額+ 止撥款總金額- 止還款總金額。</li>
        <li>科目為呆帳的金額，通常是不包含轉銷呆帳後所衍生其他如利息等金額部分，如您對借款金額有疑慮，敬請您洽詢原債權銀行。</li>
      </ul>
    </p>
    <br>
    <br>
    <br>
    <div>
      <div>表B2</div>
      <div>共同債務/從債務/其他債務資訊</div>
    </div>
    <div class="credit-info">
      <div class="credit-thead">
        <div class="credit-tr">
          <div class="credit-th" style="overflow:auto;padding: 5px;">
            <div>共同債務</div>
          </div>
        </div>
        <div class="credit-tr">
          <div class="credit-th">主借款戶</div>
          <div class="credit-th">承貸金融機構</div>
          <div class="credit-th">未逾期金額</div>
          <div class="credit-th">逾期金額</div>
          <div class="credit-th">科目</div>
        </div>
      </div>
      <div class="credit-tbody save-table" id="B2-part1_table"></div>

      <div style="padding: 10px;">
        <button type="button" class="btn btn-success add" style="margin-right:20px"><i class="fa fa-plus"></i></button>
        <button type="button" class="btn btn-danger remove"><i class="fa fa-minus"></i></button>
      </div>
    </div>
    <br>
    <br>
    <div class="credit-info">
      <div class="credit-thead">
        <div class="credit-tr">
          <div class="credit-th" style="overflow:auto;padding: 5px;">
            <div>從債務</div>
          </div>
        </div>
        <div class="credit-tr">
          <div class="credit-th">主借款戶</div>
          <div class="credit-th">承貸金融機構</div>
          <div class="credit-th">未逾期金額</div>
          <div class="credit-th">逾期金額</div>
          <div class="credit-th">科目</div>
	  </div>
      </div>
      <div class="credit-tbody save-table" id="B2-part2_table"></div>

      <div style="padding: 10px;">
        <button type="button" class="btn btn-success add" style="margin-right:20px"><i class="fa fa-plus"></i></button>
        <button type="button" class="btn btn-danger remove"><i class="fa fa-minus"></i></button>
      </div>
    </div>
    <br>
    <br>
    <div class="credit-info">
      <div class="credit-thead">
        <div class="credit-tr">
          <div class="credit-th" style="overflow:auto;padding: 5px;">
            <div>其他債務資訊</div>
          </div>
        </div>
        <div class="credit-tr">
          <div class="credit-th">主借款戶</div>
          <div class="credit-th">承貸金融機構</div>
          <div class="credit-th">未逾期金額</div>
          <div class="credit-th">逾期金額</div>
          <div class="credit-th">科目</div>
        </div>
      </div>
      <div class="credit-tbody save-table" id="B2-part3_table"></div>
      <div style="padding: 10px;">
        <button type="button" class="btn btn-success add" style="margin-right:20px"><i class="fa fa-plus"></i></button>
        <button type="button" class="btn btn-danger remove"><i class="fa fa-minus"></i></button>
      </div>
    </div>
    <div>
      <div>表B3</div>
      <div>借款逾期、催收或呆帳紀錄<br>
        (若結案日期欄位有結案日期係指您與金融機構間已無債權債務關係)</div>
    </div>
    <div class="credit-info">
      <div class="credit-thead">
        <div class="credit-tr">
          <div class="credit-th">金融機構名稱</div>
          <div class="credit-th">資料日期</div>
          <div class="credit-th">金額</div>
          <div class="credit-th">科目</div>
          <div class="credit-th">結案日期</div>
        </div>
      </div>
      <div class="credit-tbody save-table" id="B3_table"></div>

      <div style="padding: 10px;">
        <button type="button" class="btn btn-success add" style="margin-right:20px"><i class="fa fa-plus"></i></button>
        <button type="button" class="btn btn-danger remove"><i class="fa fa-minus"></i></button>
      </div>
    </div>
    <p style="text-align: start;">說明：借款逾期、催收及呆帳紀錄揭露期限，自清償之日起揭露3年，但呆帳紀錄最長不超過自轉銷之日起揭露5年。</p>
    <br>
    <br>
    <br>
    <h2>二、信用卡帳務資訊</h2>
    <hr>
    <div>
      <div>表K1</div>
      <div>信用卡持卡紀錄</div>
    </div>
    <div class="credit-info">
      <div class="credit-thead">
        <div class="credit-tr">
          <div class="credit-th">發卡機構</div>
          <div class="credit-th">卡名</div>
          <div class="credit-th">發卡日期</div>
          <div class="credit-th">停卡日期</div>
          <div class="credit-th">使用狀態</div>
        </div>
      </div>
      <div class="credit-tbody save-table" id="K1_table"></div>

      <div style="padding: 10px;">
        <button type="button" class="btn btn-success add" style="margin-right:20px"><i class="fa fa-plus"></i></button>
        <button type="button" class="btn btn-danger remove"><i class="fa fa-minus"></i></button>
      </div>
    </div>
    <p style="text-align: start;">說明：信用卡資料揭露期限，自停卡日期起揭露5年。但款項未繳之強制停卡資料，未清償者，自停卡日期起揭露7年；已清償者，自清償日期起揭露6個月，但最長不超過自停卡日期起7年。</p>
    <br>
    <br>
    <br>
    <div>
      <div>表K2</div>
      <div>信用卡帳款資訊</div>
    </div>
    <div class="credit-info">
      <div class="credit-thead">
        <div class="credit-tr">
          <div class="credit-th" style="overflow:auto;padding: 5px;">
            <div class="pull-left">信用卡帳款總餘額</div>
            <div class="pull-right"><input style="border: 0px;text-align: end;" id="K2_totalAmount">元</div>
          </div>
        </div>
        <div class="credit-tr">
          <div class="credit-th">結帳日</div>
          <div class="credit-th">發卡機構</div>
          <div class="credit-th">卡名</div>
          <div class="credit-th">本期額度</div>
          <div class="credit-th">應付帳款</div>
          <div class="credit-th">未到期待付款</div>
          <div class="credit-th">上期繳款狀況</div>
          <div class="credit-th">是否預借現金</div>
          <div class="credit-th">債權</div>
          <div class="credit-th">狀態債權結案</div>
        </div>
      </div>
      <div class="credit-tbody save-table" id="K2_table"></div>
      <div style="padding: 10px;">
        <button type="button" class="btn btn-success add" style="margin-right:20px"><i class="fa fa-plus"></i></button>
        <button type="button" class="btn btn-danger remove"><i class="fa fa-minus"></i></button>
      </div>
    </div>
    <p style="text-align: start;">
      說明：
      <ul style="text-align: start;padding-left: 20px;">
        <li>信用卡戶帳款資料揭露期限，繳款資料自繳款截止日起揭露1年，催收及呆帳紀錄自清償之日起揭露６個月，但呆帳紀錄未清償者，自轉銷之日起揭露５年。</li>
        <li>因每人每卡之結帳時間可能不同，爰信用卡戶帳款資訊係各發卡機構依各信用卡帳款作業週期報送後，進行資料處理與更新。</li>
        <li>「信用卡帳款總餘額」資訊係擷取各發卡機構於最近兩個月個別帳單之最新一筆帳款資料（排除「債權結案」乙欄已有結案資訊者），並加總「本期應付帳款」及「未到期待付款」兩欄資訊後之金額。</li>
        <li>債權狀態為呆帳的金額，通常是不包含轉銷呆帳後所衍生其他如利息等金額部分，如您對借款金額有疑慮，敬請您洽詢原債權銀行。</li>
      </ul>
    </p>
    <br>
    <br>
    <h2>四、查詢紀錄</h2>
    <hr>
    <div>
      <div>表S1</div>
      <div>被查詢紀錄<br>(查詢機構包含：金融機構、電子支付機構及電子票證機構)</div>
    </div>
    <div class="credit-info">
      <div class="credit-thead">
        <div class="credit-tr">
          <div class="credit-th">查詢日期</div>
          <div class="credit-th">查詢機構</div>
          <div class="credit-th">查詢理由</div>
        </div>
      </div>
      <div class="credit-tbody save-table" id="S1_table"></div>

      <div style="padding: 10px;">
        <button type="button" class="btn btn-success add" style="margin-right:20px"><i class="fa fa-plus"></i></button>
        <button type="button" class="btn btn-danger remove"><i class="fa fa-minus"></i></button>
      </div>
    </div>
    <p style="text-align: start;">說明:被金融機構或電子支付機構或電子票證發行機構查詢紀錄，為自查詢日期(不含查詢當日)起1年內之紀錄。</p>
    <div>
      <div>表S2</div>
      <div>當事人查詢信用報告紀錄</div>
    </div>
    <div class="credit-info">
      <div class="credit-thead">
        <div class="credit-tr">
          <div class="credit-th">查詢日期</div>
          <div class="credit-th">申請方式</div>
          <div class="credit-th">信用報告種類</div>
          <div class="credit-th">是否揭予金融機構參考</div>
        </div>
      </div>
      <div class="credit-tbody save-table" id="S2_table"></div>
      <div style="padding: 10px;">
        <button type="button" class="btn btn-success add" style="margin-right:20px"><i class="fa fa-plus"></i></button>
        <button type="button" class="btn btn-danger remove"><i class="fa fa-minus"></i></button>
      </div>
    </div>
    <p style="text-align: start;">說明:被金融機構或電子支付機構或電子票證發行機構查詢紀錄，為自查詢日期(不含查詢當日)起1年內之紀錄。</p>
    <br>
    <br>
    <br>
    <br>
    <div class="bottom">
      <ul style="text-align: start;padding-left: 20px;">
        <li class="list-li">
          <div class="icon-div">※</div>
          上述信用資料若有發現金融機構報送的資料有誤，您除可以向原來提報的金融機構反映， 請其向本中心通知改正外；也可以用書面的方式檢具身分證明文件 (如有相關佐證資料請一併檢附)，直接向本中心反映，本中心會主動協助將您的資料與報送的金融機構聯繫，如果查 明屬實，便會把您的資料更改正確。
        </li>
        <li class="list-li">
          <div class="icon-div">※</div>
          本中心信用報告所示信用資料僅供當事人參考，不能等同或證明資料當事人於全體金融機構實際存在之所有金融負債（含保證）情形。當事人為任何決定時，請勿以本信用報告作為唯一依據。
        </li>
        <li class="list-li">
          <div class="icon-div">※</div>
          信用報告為個人隱私之重要文件，請妥善保管，切勿輕信或輕易交給他人，以避免為不法用途。
        </li>
      </ul>
    </div>
    <div>***報表結束***</div>
</div>
<div class="page">
	<div>＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝</div>
	<div style="width:100%; display:flex; justify-content: space-between;"><span>｜｜</span><span style="font-size:15px;">財團法人金融聯合徵信中心</span><span>｜｜</span></div>
	<div>＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝</div>
	<div style="text-align:end;"><span>查詢日期：</span><input type="text" id="queryDatetime"></div>
	<div style="text-align:start;">
		<div><span>所屬單位：9980259</span><span style="margin-left: 10px;">財團法人金融聯合徵信中心</span></div>
		<div><span>身分證號：</span><input type="text" class="save-this" id="personId1"><input type="text" class="save-this" id="personName1"></div>
		<div><span>消債/其他註記：</span><input type="text" class="save-this" id ="debtDischargeOtherComment"></div>
		<div><span>通報案件紀錄：</span><input type="text" class="save-this" id ="reportedRecord"></div>
	</div>
	<div>＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝</div>
	<div style="text-align:start;">
		<div><span>查詢項目：A11</span><span style="margin-left: 100px;">個人任職董監事/有限合夥合夥人、 商業登記事業負責人及經理人企業名錄</span></div>
		<div><span>身分證號：</span><input type="text" class="save-this" id ="personId2"><input type="text" class="save-this" id ="personName2"></div>
	</div>
	<!-- part 1 -->
	<div style="text-align:end;"><span>資料日期：</span><input type="text"></div>
	<div class="credit-info" style="margin-top:20px;">
      <div class="credit-thead">
        <div class="credit-tr">
          <div class="credit-th" style="overflow:auto;padding: 5px;">
            <div style="text-align:start;">一、個人任職董監事/經理人企業名錄</div>
          </div>
        </div>
        <div class="credit-tr">
          <div class="credit-th">統一編號</div>
          <div class="credit-th">公司中文戶名</div>
          <div class="credit-th">職稱</div>
          <div class="credit-th">持有股數/出資額</div>
          <div class="credit-th">中文姓名</div>
		  <div class="credit-th">設立狀況</div>
	  </div>
      </div>
      <div class="credit-tbody save-table" id="A11-part1_table"></div>

      <div style="padding: 10px;">
        <button type="button" class="btn btn-success add" style="margin-right:20px"><i class="fa fa-plus"></i></button>
        <button type="button" class="btn btn-danger remove"><i class="fa fa-minus"></i></button>
      </div>
    </div>
	<!-- part 2 -->
	<div style="text-align:end;"><span>資料日期：</span><input type="text"></div>
	<div class="credit-info" style="margin-top:20px;">
      <div class="credit-thead">
        <div class="credit-tr">
          <div class="credit-th" style="overflow:auto;padding: 5px;">
            <div style="text-align:start;">二、個人任職獨資/合夥事業負責人及經理人企業名錄</div>
          </div>
        </div>
        <div class="credit-tr">
          <div class="credit-th">統一編號</div>
          <div class="credit-th">公司中文戶名</div>
          <div class="credit-th">職稱</div>
          <div class="credit-th">資本額</div>
          <div class="credit-th">中文姓名</div>
		  <div class="credit-th">設立狀況</div>
	  </div>
      </div>
      <div class="credit-tbody save-table" id="A11-part2_table"></div>

      <div style="padding: 10px;">
        <button type="button" class="btn btn-success add" style="margin-right:20px"><i class="fa fa-plus"></i></button>
        <button type="button" class="btn btn-danger remove"><i class="fa fa-minus"></i></button>
      </div>
    </div>
	<!-- part 3 -->
	<div style="text-align:end;"><span>資料日期：</span><input type="text"></div>
	<div class="credit-info" style="margin-top:20px;">
      <div class="credit-thead">
        <div class="credit-tr">
          <div class="credit-th" style="overflow:auto;padding: 5px;">
            <div style="text-align:start;">三、個人任職有限合夥事業合夥人及經理人企業名錄</div>
          </div>
        </div>
        <div class="credit-tr">
          <div class="credit-th">統一編號</div>
          <div class="credit-th">公司中文戶名</div>
          <div class="credit-th">職稱</div>
          <div class="credit-th">出資額</div>
          <div class="credit-th">中文姓名</div>
		  <div class="credit-th">設立狀況</div>
	  </div>
      </div>
      <div class="credit-tbody save-table" id="A11-part3_table"></div>

      <div style="padding: 10px;">
        <button type="button" class="btn btn-success add" style="margin-right:20px"><i class="fa fa-plus"></i></button>
        <button type="button" class="btn btn-danger remove"><i class="fa fa-minus"></i></button>
      </div>
    </div>
	<br>
	<br>
	<br>
	<br>
	<div class="bottom">
      <ul style="text-align: start;padding-left: 20px;">資料來源：
        <li class="list-li">
		  1.公司、有限合夥及商業登記資料：經濟部商業司
        </li>
        <li class="list-li">
          2.其他組織：本中心蒐集相關資料彙整建置
        </li>
      </ul>
	  <div style="text-align: start;padding-left: 20px;">以上資料若有疑問，請逕洽本中心。</div>
	  <br>
	  <ul style="text-align: start;padding-left: 20px;">
		  <li class="list-li">
  		  ****獨資/合夥事業曾核准設立，但目前查無設立狀況資料者，於中文戶名後註明"非營業中"****
          </li>
		  <li class="list-li">
  		  ****公司曾核准設立，但目前非屬核准設立狀態者．於中文戶名後註明核准日期****
          </li>
		  <li class="list-li">
  		  ****依據商業司「商工登記資料公示查詢系統」之釋義，公司已解散，董事會已不存在，公司負責人為清算人，依公司法第83條規定，清算人之就、任解任等均應向法院聲報；依民法第42條第1項規定，清算中之公司，係屬法院監督之範疇。****
          </li>
	  <ul>
    </div>
	<div>*****上述資訊僅供會員參考，不宜作為交易准駁之唯一依據，並請勿對外公開*****</div>
</div>
</body>

<script>
  function switchCase(title_element, list_title) {
    $(title_element).first().prop('hidden', function(i, v) {
      return !v;
    });
    $(list_title).prop('hidden', function(i, v) {
      return !v;
    });

    $('.remove').attr('disabled',!$('.remove').attr('disabled'));
    $('.add').attr('disabled',!$('.add').attr('disabled'));
  }

  $(document).ready(function() {
    var urlString = window.location.href;
    var url = new URL(urlString);
    var certification_id = url.searchParams.get("certification");
    var imageId = url.searchParams.get("id");
    var textId = url.searchParams.get("name");
    var check = url.searchParams.get("check");

    var checkout_date;
    var list_len;
    var last_checkout_date;
    var rex = new RegExp("^([0-9]{3})[./]{1}([0-9]{1,2})[./]{1}([0-9]{1,2})$");

    let template = {
      "B1": {
        accountDescription: "",
        bankName: "",
        delayAmount: "",
        noDelayAmount: "",
        pastOneYearDelayRepayment: "",
        purpose: "",
        totalAmount: "",
        yearMonth: ""
      },
      "B1-extra": {
        accountDescription: "",
        bankName: "",
        pastOneYearDelayRepayment: "",
        purpose: "",
        yearMonth: "",
        appropriationAmount: "",
        repaymentAmount: ""
      },
      "B2-part1": {
        mainBorrower: "",
        loanedBank: "",
        noDelayAmount: "",
        delayAmount: "",
        accountDescription: ""
      },
      "B2-part2": {
        mainBorrower: "",
        loanedBank: "",
        noDelayAmount: "",
        delayAmount: "",
        accountDescription: ""
      },
      "B2-part3": {
        mainBorrower: "",
        loanedBank: "",
        noDelayAmount: "",
        delayAmount: "",
        accountDescription: ""
      },
      "B3": {
        accountDescription: "",
        closedDate: "",
        yearMonth: "",
        bankName: "",
        amount: ""
      },
      "K1": {
        authority: "",
        cardName: {
          type: "",
          level: "",
          primaryType: ""
        },
        authorizedDate: "",
        deauthorizedDate: "",
        status: ""
      },
      "K2": {
        date: "",
        bank: "",
        cardType: "",
        quotaAmount: "",
        currentAmount: "",
        nonExpiredAmount: "",
        previousPaymentStatus: "",
        cashAdvanced: "",
        claimsStatus: "",
        claimsClosed: ""
      },
      "S1": {
        date: "",
        institution: "",
        reason: "",
      },
      "S2": {
        date: "",
        applyType: "",
        creditReportType: "",
        revealToBank: ""
      },
	  "A11-part1":{
		taxId: "",
		companyName: "",
		jobTitle: "",
		amountOfCapitalContributed: "",
		personName: "",
		registerStatus: "",
	  },
	  "A11-part2":{
		taxId: "",
		companyName: "",
		jobTitle: "",
		amountOfCapitalContributed: "",
		personName: "",
		registerStatus: "",
	  },
	  "A11-part3":{
		taxId: "",
		companyName: "",
		jobTitle: "",
		amountOfCapitalContributed: "",
		personName: "",
		registerStatus: "",
	  },
    };

    let counts = {
      K1: 0,
      K2: 0,
      S1: 0,
      S2: 0,
	  'A11-part1': 0,
	  'A11-part2': 0,
	  'A11-part3': 0,
    }

    $(document).on('change', '.credit-card-account-date:last', function() {
      checkout_date = $(this).val();
      list_len = $('.credit-card-account-date').length;
      list_len -= 2;
      last_checkout_date = $(`.credit-card-account-date:eq(${list_len})`).val();
      // console.log(last_checkout_date);
      // console.log(checkout_date);
      if (rex.exec(checkout_date) != null && rex.exec(last_checkout_date) != null) {
        checkout_date = checkout_date.split('/');
        last_checkout_date = last_checkout_date.split('/');
        if (last_checkout_date[1] != checkout_date[1]) {
          list_len = $('.credit-card-account-list').length;
          list_len -= 3;
          $(`.credit-card-account-list:eq(${list_len})`).prop('hidden', function(i, v) {
            return !v;
          });
        }
      }
    });

    // 編輯按鈕
    $("#edit_click").on('click', function() {
      switchCase('#test', '.action-btn');
      edit_click();
    });

    // 儲存按鈕
    $("#save_click").on('click', function() {
      save_click('credit_investigation', imageId, certification_id);
    });

    // 送出按鈕
    $("#send_click").on('click', function() {
      send_click('credit_investigation', imageId, certification_id);
    });

	//
    $('.add').on('click', function() {
  		let ID = $(this).parents('.credit-info').find('.credit-tbody').attr('id').split('_')[0];
		if(!counts[ID]){
			counts[ID] = 0;
		}
      counts[ID] += 1;

      let tr = '<div class="credit-tr">';
	  Object.keys(template[ID]).forEach((key) => {
        if (typeof template[ID][key] !== 'object') {
          tr += `<div class="credit-td"><input class="form-control" id="${ID}_${key}_${counts[ID]}" value="${template[ID][key]}"></div>`;
        } else {
          tr += `<div class="credit-td  credit-flex">`;
          Object.keys(template[ID][key]).forEach((item) => {
            tr += `<input class="form-control" id="${ID}_${key}_${counts[ID]}_${item}" value="${template[ID][key][item]}">`;
          });
          tr += `</div>`;
        }
      })
      tr += '</div>'

      addTableList($(this).parents('.credit-info').find('.credit-tbody'), tr);
    });

    $('.remove').on('click', function() {
      deleteTableList($(this).parents('.credit-info').find('.credit-tbody').children());

      let ID = $(this).parents('.credit-info').find('.credit-tbody').attr('id').split('_')[0];
      counts[ID] -= 1;
    });


    fetchReport('credit_investigation', imageId, check, certification_id, function(data) {
      if (!data) {
        return;
      }

	  Object.keys(data.applierInfo.basicInfo).forEach((key) => {
		$(`#${key}`).val(data.applierInfo.basicInfo[key]);
	  });

	  Object.keys(data.applierInfo.creditInfo).forEach((key) => {
		if (typeof data.applierInfo.creditInfo[key] === 'string') {
		  $(`#${key}`).val(data.applierInfo.creditInfo[key]);
		} else {
		  Object.keys(data.applierInfo.creditInfo[key]).forEach((subKey) => {
			if (typeof data.applierInfo.creditInfo[key][subKey] !== 'string') {
			  $(`#${key}_${subKey}_existCreditInfo`).val(data.applierInfo.creditInfo[key][subKey].existCreditInfo);
			  $(`#${key}_${subKey}_creditDetail`).val(data.applierInfo.creditInfo[key][subKey].creditDetail);
			}
		  });
		}
	  });

	  let B1_tr = '';
	  counts['B1'] = typeof(data.B1.dataList)=='undefined' ? 0 : data.B1.dataList.length;
	  if(counts['B1']){
		data.B1.dataList.forEach((row, index) => {
		  B1_tr += '<div class="credit-tr">';
		  Object.keys(row).forEach((key) => {
			B1_tr += `<div class="credit-td"><input class="form-control" id="B1_${key}_${index+1}" value="${row[key]}"></div>`;
		  })
		  B1_tr += '</div>'
		});
		$('#B1_table').html(B1_tr);
	  }

	  let B1_extra_tr = '';
	  counts['B1-extra'] = typeof(data['B1-extra'].dataList)=='undefined' ? 0 : data['B1-extra'].dataList.length;
	  if(counts['B1-extra']){
		data['B1-extra'].dataList.forEach((row, index) => {
		  B1_extra_tr += '<div class="credit-tr">';
		  Object.keys(row).forEach((key) => {
			B1_extra_tr += `<div class="credit-td"><input class="form-control" id="B1-extra_tr_${key}_${index+1}" value="${row[key]}"></div>`;
		  })
		  B1_extra_tr += '</div>'
		});
		$('#B1-extra_table').html(B1_extra_tr);
	  }
	  //B2

	  //part1
	  if (data['B2'].part1) {
		let B2_part1_tr = '';
		counts['B2-part1'] = data['B2'].part1.dataList.length;
		data['B2']['part1'].dataList.forEach((row, index) => {
		  B2_part1_tr += '<div class="credit-tr">';
		  Object.keys(row).forEach((key) => {
			B2_part1_tr += `<div class="credit-td"><input class="form-control" id="B2-part1_tr_${key}_${index+1}" value="${row[key]}"></div>`;
		  })
		  B2_part1_tr += '</div>'
		});
		$('#B2-part1_table').html(B2_part1_tr);
	  }
	  //part2
	  if (data['B2'].part2) {
		let B2_part2_tr = '';
		counts['B2-part2'] = data['B2'].part2 ? data['B2'].part2.dataList.length : 0;
		data['B2']['part2'].dataList.forEach((row, index) => {
		  B2_part2_tr += '<div class="credit-tr">';
		  Object.keys(row).forEach((key) => {
			B2_part2_tr += `<div class="credit-td"><input class="form-control" id="B2-part2_tr_${key}_${index+1}" value="${row[key]}"></div>`;
		  })
		  B2_part2_tr += '</div>'
		});
		$('#B2-part2_table').html(B2_part2_tr);
	  }
	  //part3
	  if (data['B2'].part3) {
		let B2_part3_tr = '';
		counts['B2-part3'] = data['B2'].part3 ? data['B2']['part3'].dataList.length : 0;
		data['B2']['part3'].dataList.forEach((row, index) => {
		  B2_part3_tr += '<div class="credit-tr">';
		  Object.keys(row).forEach((key) => {
			B2_part3_tr += `<div class="credit-td"><input class="form-control" id="B2-part3_tr_${key}_${index+1}" value="${row[key]}"></div>`;
		  })
		  B2_part3_tr += '</div>'
		});
		$('#B2-part3_table').html(B2_part3_tr);

	  }
	  let B3_tr = '';
	  counts['B3'] = typeof(data.B3.dataList)=='undefined' ? 0 : data.B3.dataList.length;
	  if(counts['B3']){
		data.B3.dataList.forEach((row, index) => {
		  B3_tr += '<div class="credit-tr">';
		  Object.keys(row).forEach((key) => {
			B3_tr += `<div class="credit-td"><input class="form-control" id="B3_${key}_${index+1}" value="${row[key]}"></div>`;
		  })
		  B3_tr += '</div>'
		});
		$('#B3_table').html(B3_tr);
	  }

	  let K1_tr = '';
	  counts['K1'] = typeof(data.K1.dataList)=='undefined' ? 0 : data.K1.dataList.length;
	  if(counts['K1']){
		data.K1.dataList.forEach((row, index) => {
		  K1_tr += '<div class="credit-tr">';
		  Object.keys(template['K1']).forEach((key) => {
			if (typeof row[key] !== 'object') {
			  K1_tr += `<div class="credit-td"><input class="form-control" id="K1_${key}_${index+1}" value="${row[key] ? row[key] : ''}"></div>`;
			} else {
			  K1_tr += `<div class="credit-td  credit-flex">`;
			  Object.keys(row[key]).forEach((item) => {
				K1_tr += `<input class="form-control" id="K1_${key}_${index}_${item}" value="${row[key][item]}">`;
			  });
			  K1_tr += `</div>`;
			}
		  })
		  K1_tr += '</div>'
		});
		$('#K1_table').html(K1_tr);
	  }

	  $(`#K2_totalAmount`).val(data.K2.totalAmount);
	  let K2_tr = '';
	  counts['K2'] = typeof(data.K2.dataList)=='undefined' ? 0 : data.K2.dataList.length;
	  if(counts['K2']){
		data.K2.dataList.forEach((row, index) => {
		  K2_tr += '<div class="credit-tr">';
		  Object.keys(row).forEach((key) => {
			K2_tr += `<div class="credit-td"><input class="form-control" id="K2_${key}_${index+1}" value="${row[key]}"></div>`;
		  })
		  K2_tr += '</div>'
		});
		$('#K2_table').html(K2_tr);
	  }

	  let S1_tr = '';
	  counts['S1'] = typeof(data.S1.dataList)=='undefined' ? 0 : data.S1.dataList.length;
	  if(counts['S1']){
		  data.S1.dataList.forEach((row, index) => {
			S1_tr += '<div class="credit-tr">';
			Object.keys(row).forEach((key) => {
			  S1_tr += `<div class="credit-td"><input class="form-control" id="S1_${key}_${index+1}" value="${row[key]}"></div>`;
			})
			S1_tr += '</div>'
		  });
		  $('#S1_table').html(S1_tr);
	  }

	  let S2_tr = '';
	  counts['S2'] = typeof(data.S2.dataList)=='undefined' ? 0 : data.S2.dataList.length;
	  if(counts['S2']){
		data.S2.dataList.forEach((row, index) => {
		  S2_tr += '<div class="credit-tr">';
		  Object.keys(row).forEach((key) => {
			S2_tr += `<div class="credit-td"><input class="form-control" id="S2_${key}_${index+1}" value="${row[key]}"></div>`;
		  })
		  S2_tr += '</div>'
		});
		$('#S2_table').html(S2_tr);
	  }

	  // A11 基本資訊
		Object.keys(data.extraA11).forEach((row, index) => {
			if($(`#${row}`).length){
				$(`#${row}`).val(data.extraA11[row]);
			}
		});

		let A11_part1_tr = '';
        counts['extraA11_part1'] = data.extraA11.part1.dataList.length ? data.extraA11.part1.dataList.length : 0;
		if(counts['extraA11_part1']){
			data.extraA11.part1.dataList.forEach((row, index) => {
	          A11_part1_tr += '<div class="credit-tr">';
	          Object.keys(row).forEach((key) => {
	            A11_part1_tr += `<div class="credit-td"><input class="form-control" id="A11_part1_${key}_${index+1}" value="${row[key]}"></div>`;
	          })
	          A11_part1_tr += '</div>';
	        });
	        $('#A11-part1_table').html(A11_part1_tr);
		}

		let A11_part2_tr = '';
        counts['extraA11_part2'] = data.extraA11.part2.dataList.length ? data.extraA11.part2.dataList.length : 0;
		if(counts['extraA11_part2']){
			data.extraA11.part2.dataList.forEach((row, index) => {
	          A11_part2_tr += '<div class="credit-tr">';
	          Object.keys(row).forEach((key) => {
	            A11_part2_tr += `<div class="credit-td"><input class="form-control" id="A11_part2_${key}_${index+1}" value="${row[key]}"></div>`;
	          })
	          A11_part1_tr += '</div>';
	        });
	        $('#A11-part2_table').html(A11_part2_tr);
		}

		let A11_part3_tr = '';
        counts['extraA11_part3'] = data.extraA11.part3.dataList.length ? data.extraA11.part3.dataList.length : 0;
		if(counts['extraA11_part3']){
			data.extraA11.part3.dataList.forEach((row, index) => {
	          A11_part1_tr += '<div class="credit-tr">';
	          Object.keys(row).forEach((key) => {
	            A11_part3_tr += `<div class="credit-td"><input class="form-control" id="A11_part1_${key}_${index+1}" value="${row[key]}"></div>`;
	          })
	          A11_part3_tr += '</div>';
	        });
	        $('#A11-part3_table').html(A11_part3_tr);
		}

      if (data.message) {
        alert(data.message);
      }
    });

  });
</script>

</html>
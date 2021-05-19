<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin/css/ocr/balance_sheet.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="<?=base_url()?>assets/admin/js/ocr/fetch.js"></script>
    <title>勞動部勞工保險局投保單位人數資料表</title>
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
    input {
      width: 50%;
    }
    </style>
  </head>
  <body style="text-align: center;">
    <div class="book">
      <div class="page">
        <div style="position:fixed; right:0;">
          <button style="margin:0px 5px 0px 0px ;" onclick="edit_click()">編輯</button>
          <button style="margin:0px 5px 0px 0px ;" id="save_click">儲存</button>
          <button style="margin:0px 5px 0px 0px ;" id="send_click">送出</button>
        </div>
        <div class="subpage">
          <h2>勞動部勞工保險局投保單位人數資料表</h2>
          <div style="display:flex;">
            <div style="width: 50%;text-align: left;">
              <div style="">保險證號:<input class="save-this" id="insuranceId"></div>
              <div style="">單位名稱:<input class="save-this result-this" id="companyInfo"></div>
              <div style="">計費年月:<input class="save-this result-this" id="insurancePeriod"></div>
            </div>
            <div style="width: 50%;text-align: right;">
              <div style="">印表日期:<input class="save-this result-this" id="reportTime"></div>
            </div>
          </div>
          <h2>月底生效人數(不含月底當日退保者)</h2>
          <table>
            <tr>
              <td style="text-align: center;">序號</td>
              <td style="text-align: center;">計費年月</td>
              <td style="text-align: center;">月底生效人數</td>
            </tr>
            <tbody id="table-values" class="save-table">

            </tbody>
          </table><br>
          <button id="add-list" class="btn" onclick="addLast()">
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-plus" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M8 3.5a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5H4a.5.5 0 0 1 0-1h3.5V4a.5.5 0 0 1 .5-.5z"/>
              <path fill-rule="evenodd" d="M7.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0V8z"/>
            </svg>
          </button>
          <button id="delete-list" class="btn" onclick="deleteLast()">
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z"/>
              <path fill-rule="evenodd" d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z"/>
            </svg>
          </button><br>
          <b style="float: center;">資料結束</b><br>
          <b style="float: left;">備註：</b><br>
          <div style="float: left;">一、本表僅供投保單位辦理保險業務參考，不作其他證明使用。</div><br>
          <div style="float: left;">二、依據個人資料保護法及政府資訊公開法等相關規定，本表僅限投保單位查詢。</div>
        </div>
      </div>
    </div>
    <script>

        var num = 1;

        function createInsuranceHtml (num, insurance_month = '', insurance_count=''){
          return`
          <tr class="insurance-tr save-this-two-level-t">
            <td style="text-align: center;">${num}</td>
            <td style="text-align: center; ">
              <input id="NumOfInsuredYM${num}" value="${insurance_month}">
            </td>
            <td style="text-align: center; ">
              <input id="NumOfInsured${num}" value="${insurance_count}">
            </td>
          </tr>`;
        }

        // add table list
        function addLast(element='#table-values',data='',isMongo=0){
          if(data ==''){
            html = createInsuranceHtml(num);
            addTableList(element, html);
            num+=1;
          }else{
            Object.keys(data).forEach(function (key) {
              if(isMongo){
				  insurance_month = data[key]['yearMonth'];
				  insurance_count = data[key]['insuredCount'];
				  html = createInsuranceHtml(num,insurance_month,insurance_count);
				  addTableList(element, html)
				  num+=1;
              }else{
                insurance_month = data[key]['NumOfInsuredYM'+ num];
                insurance_count = data[key]['NumOfInsured'+ num];
                html = createInsuranceHtml(num,insurance_month,insurance_count);
                addTableList(element, html)
                num+=1;
              }
            })
          }
        }

        // delete table list
        function deleteLast(){
          if(num > 1){
            var element = '.insurance-tr';
            deleteTableList(element);
            num-=1;
          }
        }

        $(document).ready(function() {
            var urlString = window.location.href;
            var url = new URL(urlString);
            var certification_id = url.searchParams.get("certification");
            var imageId = url.searchParams.get("id");
            var check = url.searchParams.get("check");

            // 儲存按鈕
            $( "#save_click" ).on('click',function() {
              save_click('insurance_table', imageId, certification_id);
            });

            // 送出按鈕
            $( "#send_click" ).on('click',function() {
              // 抓最近一年
              for(var i=num-12;i<=num;i++){
                $(`#NumOfInsuredYM${i}`).addClass( "result-this" );
				$(`#NumOfInsured${i}`).addClass( "result-this" );
              }
              send_click('insurance_table', imageId, certification_id);
            });

            fetchReport('insurance_table', imageId, check, certification_id, function (data) {
                if (!data) {
                    return;
                }
                if(data.data_type){
                  fillReport(data,'insurance_table','report');
                  addLast('#table-values',data.table_list["table-values"]);
                }else{
                  fillReport(data,'insurance_table','report');
                  addLast('#table-values',data.insuredList,1);
                }

            });
        });
    </script>
  </body>
</html>

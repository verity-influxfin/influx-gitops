<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin/css/ocr/A3_check.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="<?=base_url()?>assets/admin/js/ocr/fetch.js"></script>
    <title>勞工保險被保險人投資資料表</title>
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
    .div-list {
      width: 22%;
    }
    .sd {
      width: 60%;
    }
    .bl-flex input {
      width: 100%;
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
        <div class="page-title">
            <p class="bl-t">勞工保險被保險人投保資料表</p>
            <div class="bl-id"><span>報表代號：</span><input id="(id)"></div>
        </div>
        <div class="page-content bl-pc">
            <div class="bl-c">
                <div style="overflow:hidden;">
                    <div class="d-f bl-df bl-mb"><span>日期：</span>
                      <input class="save-this" id="summary_reportDate" style="width: 100px;">
                    </div>
                </div>
                <div class="d-f bl-mb">
                    <div class="bl-cs"><span>姓名：</span><input class="save-this" id="summary_name"></div>
                    <div class="bl-cs"><span>身分證號：</span><input class="save-this" id="summary_personId"></div>
                    <div class="bl-cs"><span>出生日期：</span><input class="save-this" id="summary_birthday"></div>
                </div>
                <div>
                    <div class="d-f bl-mb" style="text-align: start;width: 100%;">
                        <div>首筆資料：</div>
                        <div style="width: 30%;">
                            <input class="save-this" id="first_insuranceId" style="width: 50%;"><input class="save-this" id="first_companyName" style="width: 50%;"><br>
                            <input class="save-this" id="first_date" style="width: 100px;">
                            <input class="save-this" id="first_extra" style="width: 100px;">
                        </div>
                    </div>
                    <div class="d-f bl-mb" style="text-align: start;width: 100%;">
                        <div>最新資料：</div>
                        <div style="width: 30%;">
                            <input class="save-this" id="newest_insuranceId" style="width: 50%;"><input class="save-this" id="newest_companyName" style="width: 50%;"><br>
                            <input class="save-this" id="newest_date" style="width: 100px;">
                            <input class="save-this" id="newest_extra" style="width: 100px;">
                        </div>
                    </div>
                    <div class="d-f bl-mb" style="text-align: start;width: 100%;">
                        <div>年資計算：<br>(起)</div>
                        <div style="width: 30%;">
                            <input class="save-this" id="start_insuranceId" style="width: 50%;"><input class="save-this" id="start_companyName" style="width: 50%;"><br>
                            <input class="save-this" id="start_date" style="width: 100px;">
                            <input class="save-this" id="start_extra" style="width: 100px;">
                        </div>
                    </div>
                    <div class="d-f bl-mb" style="text-align: start;width: 100%;">
                        <div>年資計算：<br>(迄)</div>
                        <div style="width: 30%;">
                            <input class="save-this" id="last_insuranceId" style="width: 50%;"><input class="save-this" id="last_companyName" style="width: 50%;"><br>
                            <input class="save-this" id="last_date" style="width: 100px;">
                            <input class="save-this" id="last_extra" style="width: 100px;">
                        </div>
                    </div>
                    <div class="d-f bl-mb" style="text-align: start;width: 100%;">
                        <div>投保年資：</div>
                        <div style="width: 30%;">
                            <input class="save-this" id="summary_yearsOfInsurance" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="bl-desc">
                <label>說明：</label>
                <ul>
                    <li>本表提供截至列印日止電腦登載之被保險人投保紀錄，實際年資以投保單位所申報資料為準，本表僅提供投保單位及被保險人辦理保險業務參考，不做其他證明使用。</li>
                    <li>僅參加職災保險、僅參加就業保險期間不計勞工保險普通事故保險年資。</li>
                    <li>依據個人資料保護法及政府資訊公開法等相關規定，本表僅限被保險人本人或其現投保單位使得查詢索取。</li>
                    <li>老年一次給付之年資，六十歲以前最多以三十年計算，六十歲以後最多以五年計。年金給付之年資，以被保險人所投保年資計算，無年資上限。</li>
                </ul>
            </div>
        </div>
        <div class="bottom">
            <div class="d-f ds">
                <div style="margin: 2px 20px;"><img src="<?=base_url()?>assets/admin/fonts/bli.png" style="width: 150px;"></div>
                <div class="we" style="font-size: 35px;">勞&emsp;動&emsp;部<br>勞工保險局</div>
                <div class="we" style="font-size: 65px;">製發</div>
            </div>
        </div>
    </div>
    <div class="page">
        <div class="page-title">
            <p class="bl-t">勞工保險被保險人投保資料表(明細)</p>
            <div class="bl-id"><span>報表代號：</span><input id="(id)"></div>
        </div>
        <div class="page-content bl-pc bl-c">
            <div style="overflow:hidden;">
                <div class="d-f bl-df" style="margin-bottom: 10px;"><span>日期：</span><input id="(id)"
                        style="width: 33px;"><span>年</span><input id="(id)" style="width: 33px;"><span>月</span><input
                        id="(id)" style="width: 33px;"><span>日</span></div>
            </div>
            <div style="overflow:hidden;">
                <div class="bl-df" style="margin-bottom: 10px;"><span>頁次：1</span><span>／1</span></div>
            </div>
            <div class="d-f bl-mb">
                <div class="bl-cs"><span>姓名：</span><input id="(id)"></div>
                <div class="bl-cs"><span>身分證號：</span><input id="(id)"></div>
                <div class="bl-cs"><span>出生日期：</span><input id="(id)"></div>
            </div>
            <div class="bl-table">
                <div class="bl-th">
                    <div class="i-n">保險證號</div>
                    <div class="i-u">投保單位</div>
                    <div class="i-s">投保薪資</div>
                    <div class="e-d">生效日期</div>
                    <div class="s-d">退保日期</div>
                    <div class="rm">備註</div>
                </div>
                <div id="nl-table" class="bl-tb save-table">
                </div>
            </div><br>
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
        </div>
        <div class="bottom">
            <div class="d-f ds">
                <div style="margin: 2px 20px;"><img src="<?=base_url()?>assets/admin/fonts/bli.png" style="width: 150px;"></div>
                <div class="we" style="font-size: 35px;">勞&emsp;動&emsp;部<br>勞工保險局</div>
                <div class="we" style="font-size: 65px;">製發</div>
            </div>
        </div>
    </div>
    <script>

        var num = 1;

        // add table list
        function addLast(element='#nl-table',data='',isMongo=0){
          if(data ==''){
            html = `
            <div class="bl-flex insurance-tr">
                <div class="i-n"><input id="id_${num}" ></div>
                <div class="i-u"><input id="company_name_${num}"></div>
                <div class="i-s"><input id="salary_${num}"></div>
                <div class="e-d"><input id="start_${num}"></div>
                <div class="s-d"><input id="end_${num}"></div>
                <div class="rm"><input id="comment_${num}"></div>
            </div>
            `;
            addTableList(element, html);
            num+=1;
          }else{
            if(isMongo){
              Object.keys(data['insuranceList']).forEach(function (key) {
                // insurance_id = data['insuranceList'][key]['insuranceId'];

                company = data['insuranceList'][key]['companyName'];
                // html = `
                //   <div class="bl-flex insurance-tr">
                //       <div class="i-n"><input value="${data['insuranceList'][key]['insuranceId']}"></div>
                //       <div class="i-u"><input value="${data['insuranceList'][key]['companyName']}"></div>
                //   `;
                // ser = '';
                Object.keys(data['insuranceList'][key]['detailList']).forEach(function (key1) {
                  html = `
                  <div class="bl-flex insurance-tr">
                      <div class="i-n"><input id="id_${num}" value="${data['insuranceList'][key]['insuranceId']}"></div>
                      <div class="i-u"><input id="company_name_${num}" value="${data['insuranceList'][key]['companyName']}"></div>
                      <div class="i-s"><input id="salary_${num}" value="${data['insuranceList'][key]['detailList'][key1]['insuranceSalary']}"></div>
                      <div class="e-d"><input id="start_${num}" value="${data['insuranceList'][key]['detailList'][key1]['startDate']}"></div>
                      <div class="s-d"><input id="end_${num}" value="${data['insuranceList'][key]['detailList'][key1]['endDate']}"></div>
                      <div class="rm"><input id="comment_${num}" value="${data['insuranceList'][key]['detailList'][key1]['comment']}"></div>
                  </div>
                  `;
                  // salary = data['insuranceList'][key]['detailList'][key1]['insuranceSalary'];
                  // start = data['insuranceList'][key]['detailList'][key1]['startDate'];
                  // end = data['insuranceList'][key]['detailList'][key1]['endDate'];
                  // info = data['insuranceList'][key]['detailList'][key1]['comment'];
                  addTableList(element, html);
                  num+=1;
                })

                // insurance_month = data[key]['insurance_time_'+ num];
                // html = createInsuranceHtml(num,data['insuranceList'][key]['insuranceId'],data['insuranceList'][key]['companyName'],salary,start,end,info);
              })
            }else{
              // console.log(data['nl-table']);
              Object.keys(data['nl-table']).forEach(function (key) {
                html = `
                <div class="bl-flex insurance-tr">
                    <div class="i-n"><input id="id_${num}" value="${data['nl-table'][key]['id']}"></div>
                    <div class="i-u"><input id="company_name_${num}" value="${data['nl-table'][key]['company_name']}"></div>
                    <div class="i-s"><input id="salary_${num}" value="${data['nl-table'][key]['salary']}"></div>
                    <div class="e-d"><input id="start_${num}" value="${data['nl-table'][key]['start']}"></div>
                    <div class="s-d"><input id="end_${num}" value="${data['nl-table'][key]['end']}"></div>
                    <div class="rm"><input id="comment_${num}" value="${data['nl-table'][key]['comment']}"></div>
                </div>
                `;
                addTableList(element, html);
                num+=1;
              })
            }
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
              save_click('insurance_table_person', imageId, certification_id);
            });

            // 送出按鈕
            $( "#send_click" ).on('click',function() {
              // 抓最近一年
              // for(var i=num-11;i<=num;i++){
              //   $(`#insurance_count_${i}`).addClass( "result-this" );
              // }
              send_click('insurance_table', imageId, certification_id);
            });

            fetchReport('insurance_table', imageId, check, certification_id, function (data) {
                if (!data) {
                    return;
                }
                // console.log(data);
                if(data.data_type){
                  fillReport(data,'insurance_table','report');
                  addLast('#nl-table',data.table_list);
                }else{
                  fillReport(data.summaryPage,'insurance_person','report','summary_');
                  fillReport(data.summaryPage.firstInsurance,'insurance_person','report','first_');
                  fillReport(data.summaryPage.newestInsurance,'insurance_person','report','newest_');
                  fillReport(data.summaryPage.startInsurance,'insurance_person','report','start_');
                  fillReport(data.summaryPage.lastInsurance,'insurance_person','report','last_');
// console.log(data.pageList);
                  // Object.keys(data.pageList).forEach(function (key) {
                  //   console.log(data[key]);
                  //   // addLast('#nl-table', data['insuranceList']);
                  // })
                  data.pageList.forEach(function(element, index, array){
                    // console.log(array[index]);
                    addLast('#nl-table', array[index],1);
                  });

                  // fillReport(data.pageList,'insurance_person','report','summary_');
                  // fillReport(data,'insurance_table','report');
                  // time_range = data.insuranceRange.split(":");
                  // fillTableData(data.table,time_range);
                }

            });

            // function fillData(data) {
            //     Object.keys(data).forEach(function(key) {
            //         if(key != 'table'){
            //           htmlKey = "#" + key;
            //           if(data[key].indexOf(':')!=-1){
            //             text = data[key].split(":");
            //             $(htmlKey).text(text[1]);
            //           }else{
            //             $(htmlKey).text(data[key]);
            //           }
            //
            //           $(htmlKey).css('color', 'blue');
            //         }
            //     })
            // }

            // function fillTableData(data,time_range) {
            //     if(time_range[1].indexOf('~')!=-1){
            //       range = time_range[1].split("~");
            //       month = parseInt(range[0].substr(3));
            //       year = parseInt(range[0].substr(0,3));
            //     }
            //
            //     Object.keys(data).forEach(function(key) {
            //       // console.log(data[key])
            //       if(month<10){
            //         month = '0' + month;
            //       }
            //           if(data[key] !=''){
            //             html = '<tr class="insurance-tr save-this-two-level-t"><td style="text-align: center;">'+ num +'</td><td style="text-align: center; "><input id="insurance_time_'+ num +'" value="'+ year + month +'"></td><td style="text-align: center; "><input id="insurance_count_'+ num +'" value="'+ data[key]+'"></td></tr>';
            //             $('#table-values').append(html)
            //             num+=1;
            //             if(parseInt(month)+1>=13){
            //               year = parseInt(year)+1;
            //               month = 1;
            //             }else{
            //               month = parseInt(month)+1;
            //             }
            //
            //           }
            //     })
            // }
        });
    </script>
</body>

</html>

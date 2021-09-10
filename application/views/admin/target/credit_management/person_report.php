    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin/css/A4.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <title>授信審核表</title>
        <style>
            span {
                white-space: nowrap;
            }
            .is-line-up {
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

            .input {
                text-align: center;
                width: 100%;
                border: 0px;
                height: 100%;
            }

            .table {
                width: 100%;
                font-size: 14px;
                border-spacing: 0px;
                text-align: left;
            }

            .table input[type="text"] {
                width: 100%;
                height: 100%;
                border: 0px;
            }

            .table input {
                border: 0px;
            }

			textarea {
				word-break:keep-all;
				padding: 0;
				width: 670px;
				max-width: 670px;
				box-sizing: border-box;
				height: 300px;
				overflow: auto;
			}
        </style>
    </head>

    </html>

    <body>
        <div class="book">
            <div class="page">
                <div class="subpage">
                    <h1 style="text-align:center;">授信審核表</h1>
                    <div style="float:right;">
                        <p>
                            <input id="reportDate" >
                        </p>
                    </div>
                    <div>
                        <!-- 案件類型 -->
                        <form id="creditCategoryList" class="is-line-up">
                        </form>
                    </div>
                    <div>
                        <!-- 審核人員 -->
                        <form id="reviewLevelList" class="is-line-up">
                            <span>本件核貸層次：</span>
                        </form>
                    </div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td rowspan="6"><span>借款人</span></td>
                                <td colspan="2"><span>姓名：</span>
                                    <input type="text" id="name">
                                </td>
                                <td colspan="2" rowspan="2">
									<form name="user_type">
										<div><input type="radio" value="1" name="user_type_radio"><span>個人</span></div>
	                                    <div><input type="radio" value="2" name="user_type_radio"><span>法人</span></div>
									</form>
                                </td>
                                <td rowspan="2"><span>戶籍地址</span></td>
                                <td colspan="5" rowspan="2">
                                    <input type="text" id="address">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><span>身分證字號：</span>
                                    <input type="text" id="id_card_number">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><span>任職公司：</span></td>
                                <td colspan="2">
                                    <input type="text" id="company">
                                </td>
                                <td rowspan="2"><span>常用信箱</span></td>
                                <td colspan="3" rowspan="2">
                                    <input type="text" id="email">
                                </td>
                                <td rowspan="2"></td>
                                <td rowspan="2"></td>
                            </tr>
                            <tr>
                                <td colspan="2"><span>就讀(畢業)學校/科系：</span></td>
                                <td colspan="2">
                                    <input type="text" id="school">
                                </td>
                            </tr>
                            <tr>
                                <td><span>出生日期</span></td>
                                <td>
                                    <input type="text" id="birthday">
                                </td>
                                <td><span>初貸日期</span></td>
                                <td>
                                    <input type="text" id="firstApplyDate">
                                </td>
                                <td colspan="4" rowspan="2" style="text-align: left;"><span>關係戶：</span>
                                    <input type="text">
                                </td>
                                <td><span>信評時間</span></td>
                                <td>
                                    <input id="creditDate"  type="text">
                                </td>
                            </tr>
                            <tr>
                                <!-- 產品類別： -->
                                <td colspan="4">
                                    <span>產品類別：</span>
                                    <form id="productCategoryList">
                                    </form>
                                </td>
                                <td><span>信評等級</span></td>
                                <td>
                                    <input id="creditRank" type="text">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div id="white"></div>
                    <div style="float:right;">
                        <p>單位：新台幣千元</p>
                    </div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td colspan="2"><span>核貸授信總額度(含本案)</span></td>
                                <td colspan="3"><input type="text"id="unusedCreditLine"></td>
                                <td rowspan="2"><span>額度到期日</span></td>
                                <td rowspan="2"><input id="creditLineExpiredDate" type="text"></td>
                            </tr>
                            <tr>
                                <td><span>申貸額度及條件</span></td>
                                <td>
                                    <form id="applyLineTypeList">
                                    </form>
                                </td>
                                <td>額度合計</td>
                                <td colspan="2"><input type="text" id="todayApplyLine"></td>
                            </tr>
                            <tr>
                                <td>授信種類</td>
                                <td>授信額度(單位:仟元)</td>
                                <td>申請額度(單位:仟元)</td>
                                <td>每筆貸放期間</td>
                                <td>利(費)率</td>
                                <td>計息方式</td>
                                <td>動撥方式</td>
                            </tr>
                        </tbody>
                        <tbody id="loan_list">
                            <!-- <tr>
                                <td><input type="text"></td>
                                <td><input type="text"></td>
                                <td><input type="text"></td>
                                <td><input type="text"></td>
                                <td><input type="text"></td>
                                <td><input type="text"></td>
                                <td><input type="text"></td>
                            </tr> -->
                        </tbody>
                        <tbody>
                            <tr>
                                <th>授信總額度</th>
                                <td><input id="totalUnusedCreditLine"></td>
                                <td><input id="totalApplyLine"></td>
                                <td colspan="4"><canvas class="slash" style="width: 100%;height: 18px;margin: -1px;"></canvas></td>
                            </tr>
                        </tbody>
                    </table>
                    <form>
                         <p>借款原因：<input class="input" type="text" id="reason" name="txt_CreditUse" size="115"
                                style="text-align: left;border:1px; border-bottom-style: solid;"></p>
                        <p>還款方式：<input class="input" type="text" id="paymentType" name="txt_RepaymentSources" size="115"
                                style="text-align: left;border:1px; border-bottom-style: solid;"></p>
                        <p>其他條件：<input class="input" type="text" id="otherCondition" name="txt_OtherConditions" size="115"
                                style="text-align: left;border:1px; border-bottom-style: solid;"></p>
                    </form>
                    <div id="white"></div>
                    <table class="table">
                        <tr>
                            <td style="width: 30px;">審核<br>主管</td>
                            <td><input type="text"></td>
                            <td style="width: 30px;">審核<br>人員</td>
                            <td><input type="text"></td>
                        </tr>
                    </table>
                    <div id="white"></div>
                    <table class="table">
                        <tr>
                            <td rowspan="9" style="width: 80px;">核貸層級批示</td>
                            <td>本次核准額度(仟元)：</td>
                            <td><input id="unusedCreditLine" type="text"></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;">一審</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div>ㄧ審意見：</div>
                                <div><textarea type="text" style="height: 100px;"></textarea></div>
                                <div><span>姓名：</span><input type="text" style="width: 90%;height: 20px;"></div>
                            </td>
                        </tr>
						<tr>
                            <td colspan="2" style="text-align: center;">二審</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div>二審意見：</div>
                                <div><textarea type="text" style="height: 100px;"></textarea></div>
                                <div><span>姓名：</span><input type="text" style="width: 90%;height: 20px;"></div>
                            </td>
                        </tr>
						<tr>
                            <td colspan="2" style="text-align: center;">風控長</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div>風控長意見：</div>
                                <div><textarea type="text" style="height: 100px;"></textarea></div>
                                <div><span>專家分數調整（±400）：</span><input type="text" style="width: 75%;height: 20px;"></div>
                                <div><span>姓名：</span><input type="text" style="width: 90%;height: 20px;"></div>
                            </td>
                        </tr>
						<tr>
                            <td colspan="2" style="text-align: center;">總經理</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div>總經理意見：</div>
                                <div><textarea type="text" style="height: 100px;"></textarea></div>
								<div><span>專家分數調整（±1500）：</span><input type="text" style="width: 75%;height: 20px;"></div>
                                <div><span>姓名：</span><input type="text" style="width: 90%;height: 20px;"></div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="page">
            <div class="subpage">
                <h3>連保人明細</h3>
                <table class="table">
                    <tbody>
                        <tr>
                            <td>姓名</td>
                            <td>出生日</td>
							<td>身分證統一編號</td>
							<td>與借款人關係</td>
							<td>資產淨值</td>
                            <td>他項權利</td>
                        </tr>
                    </tbody>
                    <tbody id="relationship"></tbody>
                    <!-- <tr>
                        <td><input type="text"></td>
                        <td><input type="text"></td>
                        <td><input type="text"></td>
                        <td><input type="text"></td>
                        <td><input type="text"></td>
                        <td><input type="text"></td>
                    </tr> -->
                </table>
                <div id="white"></div>
                <div style="float:right;">
                    <p>單位：新台幣千元</p>
                </div>
                <h3>擔保品明細</h3>
                <table class="table">
                    <tbody>
                        <tr>
                            <td colspan="2">擔保品名稱</td>
                            <td>所有權人</td>
                            <td colspan="3">座落或存放處所(地號、建號)</td>
                            <td>持分</td>
                            <td>持分數量(坪或仟股)</td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr>
                            <td colspan="2"><input type="text"></td>
                            <td><input type="text"></td>
                            <td colspan="3"><input type="text"></td>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr>
                            <td rowspan="2">鑑價日期</td>
                            <td colspan="2">鑑價總值(仟元)</td>
                            <td colspan="2">鑑價淨值(仟元)</td>
                            <td rowspan="2">放款率(%)</td>
                            <td rowspan="2">放款值(仟元)</td>
                            <td rowspan="2">設定情形</td>
                        </tr>
                        <tr>
                            <td>單價</td>
                            <td>總價</td>
                            <td>單價</td>
                            <td>總價</td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr>
                            <td>合計</td>
                            <td><canvas class="slash" style="width: 100%;height: 18px;margin: -1px;"></canvas></td>
                            <td><input type="text"></td>
                            <td><canvas class="slash" style="width: 100%;height: 18px;margin: -1px;"></canvas></td>
                            <td><input type="text"></td>
                            <td><canvas class="slash" style="width: 100%;height: 18px;margin: -1px;"></canvas></td>
                            <td><input type="text"></td>
                            <td><canvas class="slash" style="width: 100%;height: 18px;margin: -1px;"></canvas></td>
                        </tr>
                    </tbody>
                </table>
                <div id="white"></div>
                <h3>核決權限</h3>
                <table class="table">
                    <tr>
                        <td rowspan="2">擔保情形(含本件)</td>
                        <td>無擔保</td>
                        <td>擔保</td>
                        <td>合計</td>
                    </tr>
                    <tr>
                        <td><input type="text"></td>
                        <td><input type="text"></td>
                        <td><input type="text"></td>
                    </tr>
                </table>
                <div id="white"></div>
                <div style="float:right;">
                    <p>單位：新台幣千元</p>
                </div>
                <h3>現放明細</h3>
                <table class="table">
                    <tbody>
                        <tr>
                            <td rowspan="2">授信種類(會計科目)</td>
                            <td>現有核准額度</td>
                            <td>餘額</td>
                            <td rowspan="2">利(費)率</td>
                            <td rowspan="2">額度到期日</td>
                            <td rowspan="2">餘額最後到期日</td>
                            <td colspan="2">
                                <input id="approvedDate">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <inpu id="lastYearDate">
                            </td>
                            <td>最高額度</td>
                            <td>最高餘額</td>
                        </tr>
                    </tbody>
                    <tbody id="loan_total0">
                        <!-- <tr>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                        </tr> -->
                    </tbody>
                    <tbody id="loan_total1">

                        <tr>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                            <td colspan="2" rowspan="$param" style="vertical-align:text-top;">
                                <div>其他說明:</div>
                                <div><input type="text"></div>
                            </td>
                        </tr>
                    </tbody>
					<tbody id="loan_total">
					</tbody>
                    <tbody>
                        <tr>
                            <td>合計</td>
                            <td><canvas class="slash" style="width: 100%;height: 18px;margin: -1px;"></canvas></td>
                            <td><input type="text"></td>
                            <td colspan="3"><canvas class="slash" style="width: 100%;height: 18px;margin: -1px;"></canvas>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </body>

    <script>
        let slash = document.querySelectorAll(".slash");
        slash.forEach((item) => {
            let ctx = item.getContext('2d');
            ctx.lineWidth = 15;
            ctx.beginPath();
            ctx.moveTo(item.width, 0);
            ctx.lineTo(0, item.height);
            ctx.stroke();
        });

        // 授審表選單項目html
        function create_item_option_html(type,name,option_name){
            return `<div><input type="${type}" name="${name}"><span>${option_name}</span></div>`;
        }

        // 取得授審表選單項目
        function get_default_item(target_id,type){
            let report_item = {};
            $.ajax({
                type: "GET",
                url: `/admin/creditmanagement/get_structural_data?target_id=${target_id}&type=${type}`,
                async: false,
                success: function (response) {
                    report_item = response.response;
                },
                error: function(error) {
                    alert(error);
                }
            });
            return report_item;
        }

        // 取得案件資料
        function get_report_data(target_id,type){
            let report_data = {};
            $.ajax({
                type: "GET",
                url: `/admin/creditmanagement/get_data?target_id=${target_id}&type=${type}`,
                async: false,
                success: function (response) {
                    report_data = response.response;
                },
                error: function(error) {
                    alert(error);
                }
            });
            return report_data;
        }

        $(document).ready(function () {
          let urlString = window.location.href;
          let url = new URL(urlString);
          let target_id = url.searchParams.get("target_id");
          let type = url.searchParams.get("type");
		  let td_array = ['relationship','loan_list','loan_total'];

          report_item = get_default_item(target_id,type);

          if(jQuery.isEmptyObject(report_item)){
              alert('無法取得項目選單，請聯絡開發人員');
              return;
          }

          Object.keys(report_item).forEach(function (area_name) {
              Object.keys(report_item[area_name]).forEach(function (form_title) {
                  Object.keys(report_item[area_name][form_title]).forEach(function (form_option) {
                      if(form_title == 'productCategoryList'){
                          type = 'checkbox';
                      }else{
                          type = 'radio';
                      }
                      option_html = create_item_option_html(type,form_title + type,report_item[area_name][form_title][form_option]);
                      $(`#${form_title}`).append(option_html);
                  })
              })
          })

          report_data = get_report_data(target_id,type);

          if(jQuery.isEmptyObject(report_data)){
              alert('無法取得受審表資料，請聯絡開發人員');
              return;
          }

          console.log(report_data);

          Object.keys(report_data).forEach(function (area_name) {
              Object.keys(report_data[area_name]).forEach(function (input_title) {
                  if(typeof(report_data[area_name][input_title]) !== 'object'){
                      $(`#${input_title}`).val(report_data[area_name][input_title]);
                  }
                  // Object.keys(report_item[area_name][form_title]).forEach(function (form_option) {
                  //     if(form_title == 'productCategoryList'){
                  //         type = 'checkbox';
                  //     }else{
                  //         type = 'radio';
                  //     }
                  //     option_html = create_item_option_html(type,form_title + type,report_item[area_name][form_title][form_option]);
                  //     $(`#${form_title}`).append(option_html);
                  // })
              })
          })

          return;
          // $.ajax({
          //     type: "GET",
          //     url: `/admin/creditmanagement/get_data?target_id=${target_id}&type=${type}`,
          //     success: function (response) {
          //         if (!response) {
          //             alert(response);
          //             return;
          //         }
          //         if(response.response){
          //           Object.keys(response.response).forEach(function (key) {
			// 			if( td_array.includes(key)){
			// 				if(Array.isArray(response.response[key])){
			// 					Object.keys(response.response[key]).forEach(function (key1) {
			// 						html_id = key;
			// 						td_string = '<tr>';
			// 						Object.keys(response.response[key][key1]).forEach(function (key2) {
			// 							td_string += `<td><input type="text" value="${response.response[key][key1][key2]}"></td>`;
			// 						})
			// 						if(key == 'loan_total' && key1 == 0){
			// 							html_id = key + '0';
			// 							td_string += `<td><input type="text"></td><td><input type="text"></td>`;
			// 						}
			// 						if(key == 'loan_total' && key1 == 1){
			// 							html_id = key + '1';
			// 							td_string += `<td colspan="2" rowspan="$param" style="vertical-align:text-top;">
			// 								<div>其他說明:</div>
			// 								<div><input type="text"></div>
			// 							</td>`;
			// 						}
			// 						td_string += '</tr>';
			// 						$(`#${html_id}`).append(td_string);
			// 					})
			// 				}
			// 			}else{
			// 				if(key == 'company_type'){
			// 					document.company_type.company_type_radio.value=response.response[key];
			// 				}else{
			// 					$(`#${key}`).val(response.response[key]);
			// 				}
			// 			}
          //           })
          //         }
          //     },
          //     error: function(error) {
          //         alert(response);
          //     }
          // });
        });
    </script>

    </html>

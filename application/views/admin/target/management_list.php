    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin/css/credit_table/A4.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <title>授信審核表</title>
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

            .input {
                text-align: center;
                width: 100%;
                border: 0px;
                height: 100%;
            }

            .table {
                width: 100%;
                font-size: 12px;
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
    			/* max-width: 670px; */
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
                            <script language="javascript">
                                var Today = new Date();
                                document.write(Today.getFullYear() + " 年 " + (Today.getMonth() + 1) + " 月 " + Today.getDate() + " 日");
                            </script>
                        </p>
                        <p>單位：新台幣千元</p>
                    </div>
                    <div>
                        <form>
                            <input type="radio" name="case-type" checked>新案
                            <input type="radio" name="case-type">增貸
                            <input type="radio" name="case-type">續約
                            <input type="radio" name="case-type">改貸
                            <input type="radio" name="case-type">產品轉換
                        </form>
                    </div>
                    <div>
                        <p>
                            本件核貸層次：<input type="radio" name="action-user">二審人員
                            <input type="radio" name="action-user">風控長
                            <input type="radio" name="action-user">總經理
                        </p>
                    </div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td rowspan="6">借款人</td>
                                <td colspan="2">公司名稱：
                                    <input type="text" style="width:50%;" id="company_name">
                                </td>
                                <td colspan="2" rowspan="2">
									<form name="company_type">
										<div><input type="radio" value="1" name="company_type_radio"><span>股份有限公司</span></div>
	                                    <div><input type="radio" value="2" name="company_type_radio"><span>獨資</span></div>
	                                    <div><input type="radio" value="3" name="company_type_radio"><span>有限公司</span></div>
	                                    <div><input type="radio" value="4" name="company_type_radio"><span>合夥</span></div>
									</form>
                                </td>
                                <td rowspan="2">公司地址</td>
                                <td colspan="5" rowspan="2">
                                    <input type="text" id="address">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">統一編號：
                                    <input type="text" style="width:50%;" id="tax_id">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">代表人(職稱)董事長：</td>
                                <td colspan="2">
                                    <input type="text" id="owner">
                                </td>
                                <td rowspan="2">經營業務</td>
                                <td colspan="3" rowspan="2">
                                    <input type="text">
                                </td>
                                <td rowspan="2">行業代碼</td>
                                <td rowspan="2">
                                    <input type="text" id="industryName">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">身分證統一編號：</td>
                                <td colspan="2">
                                    <input type="text" id="director_id">
                                </td>
                            </tr>
                            <tr>
                                <td>設立日期</td>
                                <td>
                                    <input type="text" id="create_date">
                                </td>
                                <td>初貸日期</td>
                                <td>
                                    <input type="text" id="firstApplyDate">
                                </td>
                                <td colspan="4" rowspan="2" style="text-align: left;">集團歸戶/中文名稱(代號)：
                                    <input type="text" style="height:50%;">
                                </td>
                                <td>信評時間</td>
                                <td>
                                    <input type="text">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">產品類別：
                                    <input type="radio" name="is-credit-guarantee" checked>信保
                                    <input type="radio" name="is-credit-guarantee">非信保
                                </td>
                                <td>信評等級</td>
                                <td>
                                    <input type="text">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div id="white"></div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td colspan="2">核貸授信總額度(含本案)</td>
                                <td colspan="3"><input type="text"id="loan_list_total"></td>
                                <td rowspan="2">額度到期日</td>
                                <td rowspan="2"><input type="text"></td>
                            </tr>
                            <tr>
                                <td>申貸額度及條件</td>
                                <td>
                                    <input type="radio" name="quota-type" checked>單項額度<br>
                                    <input type="radio" name="quota-type">綜合額度
                                </td>
                                <td>額度合計</td>
                                <td colspan="2"><input type="text" id="total_amount"></td>
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
                                <td id="loan_list_amount">0</td>
                                <td id="loan_list_applied_amount">0</td>
                                <td colspan="4"><canvas class="slash" style="width: 100%;height: 18px;margin: -1px;"></canvas></td>
                            </tr>
                        </tbody>
                    </table>
                    <form>
                         <p>授信用途：<input class="input" type="text" name="txt_CreditUse" size="115"
                                style="text-align: left;border:1px; border-bottom-style: solid;" value="充實短期營運周轉"></p>
                        <p>還款財源：<input class="input" type="text" name="txt_RepaymentSources" size="115"
                                style="text-align: left;border:1px; border-bottom-style: solid;" value="營收"></p>
                        <p>還款辦法：<input class="input" type="text" name="txt_RepaymentMethod" size="115"
                                style="text-align: left;border:1px; border-bottom-style: solid;" value="按月本息均攤"></p>
                        <p>其他條件：<input class="input" type="text" name="txt_OtherConditions" size="115"
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
                            <td rowspan="7" style="width: 80px;">核貸層級批示</td>
                            <td>本次核准額度(仟元)：</td>
                            <td><input type="text"></td>
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
                                <div style="display: flex;">最近一年內（<input type="text"
                                        style="height:initial;width: 20px;">年<input type="text"
                                        style="height:initial;width: 20px;">月）</div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div style="display: flex;">（<input type="text" style="height:initial;width: 20px;">年<input
                                        type="text" style="height:initial;width: 20px;">月<input type="text"
                                        style="height:initial;width: 20px;">日）</div>
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
        $(document).ready(function () {
          let urlString = window.location.href;
          let url = new URL(urlString);
          let target_id = url.searchParams.get("target_id");
          let table_type = url.searchParams.get("table_type");
		  let td_array = ['relationship','loan_list','loan_total'];

          $.ajax({
              type: "GET",
              url: `/admin/creditmanagementtable/report?target_id=${target_id}&table_type=${table_type}`,
              success: function (response) {
                  if (!response) {
                      alert(response);
                      return;
                  }
                  if(response.response){
                    Object.keys(response.response).forEach(function (key) {
						// console.log(key);
						if( td_array.includes(key)){
							if(Array.isArray(response.response[key])){
								// console.log(response.response[key]);
								Object.keys(response.response[key]).forEach(function (key1) {
									// console.log(response.response[key][key1]);
									html_id = key;
									td_string = '<tr>';
									Object.keys(response.response[key][key1]).forEach(function (key2) {
										td_string += `<td><input type="text" value="${response.response[key][key1][key2]}"></td>`;
									})
									if(key == 'loan_total' && key1 == 0){
										html_id = key + '0';
										td_string += `<td><input type="text"></td><td><input type="text"></td>`;
									}
									if(key == 'loan_total' && key1 == 1){
										html_id = key + '1';
										td_string += `<td colspan="2" rowspan="$param" style="vertical-align:text-top;">
											<div>其他說明:</div>
											<div><input type="text"></div>
										</td>`;
									}
									// console.log(response.response[key][key1][key2]);
									td_string += '</tr>';
									$(`#${html_id}`).append(td_string);
								})
							}
						}else{
							if(key == 'company_type'){
								document.company_type.company_type_radio.value=response.response[key];
							}else{
								$(`#${key}`).val(response.response[key]);
							}
						}
                    })
                  }
              },
              error: function(error) {
                  alert(response);
              }
          });
        });
    </script>

    </html>

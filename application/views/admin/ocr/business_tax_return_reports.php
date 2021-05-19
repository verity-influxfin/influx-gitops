<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin/css/ocr/A4_Horizontal.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="<?=base_url()?>assets/admin/js/ocr/fetch.js"></script>
  <title>營業人銷售額與稅額申報書（401）</title>
</head>

<body style="text-align:center;">
  <div class="book">
    <div class="page">
      <div style="position:fixed; right:0;"hidden>
        <button style="margin:0px 5px 0px 0px ;" onclick="edit_click()">編輯</button>
        <button style="margin:0px 5px 0px 0px ;" id="save_click">儲存</button>
        <button style="margin:0px 5px 0px 0px ;" id="send_click">送出</button>
      </div>
      <div class="subpage">
        <table>
          <tr>
            <td colspan="2" style="width: 65px;text-align:center;">統一編號</td>
            <td colspan="2"><input type="text" class="save-this" id="tax_id"></td>
            <td colspan="2" rowspan="3" style="width:550px;text-align: center;border-top-color: white;">
              <h2>營業人銷售額與稅額申報書(401)</h2><br>
              (一般稅額計算-專營應稅營業人使用)<br>
              <div class="year_card"><span>所屬年月份:</span><input type="text" class="save-this" id="report_time_range"><span>年</span><input type="text"
                class="save-this"  id="401_M"><span>月</div>
            </td>
            <td rowspan="3" style="width: 20px;text-align:center;">註記欄</td>
            <td colspan="2" style="text-align:center;">核准按月申報</td>
            <td style="width: 25px;text-align:center;"><input type="checkbox" class="save-this" id="cb_MonthlyDeclaration"></td>
          </tr>
          <td colspan="2" style="text-align:center;">營業人名稱</td>
          <td colspan="2"><input type="text" class="save-this" id="name"></td>
          <td rowspan="2" style="text-align:center;">核准合併總繳單位</td>
          <td style="text-align:center;">總機構彙總報繳</td>
          <td style="text-align:center;"><input type="checkbox" class="save-this" id="cb_HeadOffice"></td>
          </tr>
          <tr>
            <td colspan="2" style="text-align:center;">稅籍編號</td>
            <td colspan="2"><input type="text" class="save-this" id="tax_audit"></td>
            <td style="text-align:center;">各單位分別申報</td>
            <td style="text-align:center;"><input type="checkbox" class="save-this" id="cb_BrunchOffice"></td>
          </tr>
          <tr>
            <td colspan="2" style="text-align:center;">負責人姓名</td>
            <td colspan="2"><input type="text" class="save-this" id="responser"></td>
            <td style="text-align:center;width: 80px;">營業地址</td>
            <td><input type="text" class="save-this" id="address"></td>
            <td colspan="2" style="text-align:center;">使用發票份數</td>
            <td colspan="2" style="text-align: right;">
              <div class="flex"><input type="text" class="save-this" id="numberOfUsedInvoices"><span>份</span></div>
            </td>
          </tr>
        </table>
        <table>
          <tr>
            <td rowspan="10" style="width: 20px;text-align:center;border-top-color: white;">銷項</td>
            <td rowspan="2" style="width: 200px;text-align:center;border-top-color: white;">項目\區分</td>
            <td colspan="4" style="text-align: center; border-top-color: white;width: 300px;">應稅</td>
            <td colspan="2" rowspan="2" style="border-top-color: white;text-align: center;width: 150px;">零稅率銷售額</td>
            <td rowspan="10" style="width: 20px;text-align:center;border-top-color: white;">稅額計算</td>
            <td style="border-top-color: white;width: 20px;text-align: center;">代號</td>
            <td style="border-top-color: white;border-right-color:white;text-align: center;">項目</td>
            <td style="border-top-color: white;width: 50px;"></td>
            <td style="border-top-color: white;width: 158px;text-align: center;">稅額</td>
          </tr>
          <tr>
            <td colspan="2" style="text-align: center;">銷售額</td>
            <td colspan="2" style="text-align: center;">稅額</td>
            <td>1.</td>
            <td>本期(月)銷項稅額合計</td>
            <td>(2)101</td>
            <td><input type="text" class="save-this" id="101"></td>
          </tr>
          <tr>
            <td>三聯式發票、電子計算機發票</td>
            <td colspan="2">
              <div class="flex"><span>1</span><input type="text" class="save-this" id="1"></div>
            </td>
            <td colspan="2">
              <div class="flex"><span>2</span><input type="text" class="save-this" id="2"></div>
            </td>
            <td colspan="2" style="border-bottom-color:white ;">3(非經海關出口應附證明文件者)</td>
            <td>7.</td>
            <td>得扣抵進項合計</td>
            <td>(9)+(10)107</td>
            <td><input type="text" class="save-this" id="107"></td>
          </tr>
          <tr>
            <td>收銀機發票(三聯式)及電子發票</td>
            <td colspan="2">
              <div class="flex"><span>5</span><input type="text" class="save-this" id="5"></div>
            </td>
            <td colspan="2">
              <div class="flex"><span>6</span><input type="text" class="save-this" id="6"></div>
            </td>
            <td colspan="2">
              <div class="flex"><span>7</span><input type="text" class="save-this" id="7"></div>
            </td>
            <td>8.</td>
            <td>上期(月)累積留抵稅額</td>
            <td>108</td>
            <td><input type="text" class="save-this" id="108"></td>
          </tr>
          <tr>
            <td>二聯式發票、收銀機發票(二聯式)</td>
            <td colspan="2">
              <div class="flex"><span>9</span><input type="text" class="save-this" id="9"></div>
            </td>
            <td colspan="2">
              <div class="flex"><span>10</span><input type="text" class="save-this" id="10"></div>
            </td>
            <td colspan="2" style="font-size: 10px;border-bottom-color:white ;">11(經海關出口免付證明文件者)</td>
            <td>10.</td>
            <td>小計(7+8)</td>
            <td>110</td>
            <td><input type="text" class="save-this" id="110"></td>
          </tr>
          <tr>
            <td>免用發票</td>
            <td colspan="2">
              <div class="flex"><span>13</span><input type="text" class="save-this" id="13"></div>
            </td>
            <td colspan="2">
              <div class="flex"><span>14</span><input type="text" class="save-this" id="14"></div>
            </td>
            <td colspan="2">
              <div class="flex"><span>15</span><input type="text" class="save-this" id="15"></div>
            </td>
            <td>11.</td>
            <td>本期(月)應實繳稅額(1-10)</td>
            <td>111</td>
            <td><input type="text" id="111"></td>
          </tr>
          <tr>
            <td>減：退回及折讓</td>
            <td colspan="2">
              <div class="flex"><span>17</span><input type="text" class="save-this" id="17"></div>
            </td>
            <td colspan="2">
              <div class="flex"><span>18</span><input type="text" class="save-this" id="18"></div>
            </td>
            <td colspan="2">
              <div class="flex"><span>19</span><input type="text" class="save-this" id="19"></div>
            </td>
            <td>12.</td>
            <td>本期(月)申報留抵稅額(10-1)</td>
            <td>112</td>
            <td><input type="text" class="save-this" id="112"></td>
          </tr>
          <tr>
            <td>合計</td>
            <td colspan="2">
              <div class="flex"><span>21(1)</span><input type="text" class="save-this" id="21_1"></div>
            </td>
            <td colspan="2">
              <div class="flex"><span>22(2</span>)<input type="text" class="save-this" id="22_2"></div>
            </td>
            <td colspan="2">
              <div class="flex"><span>23(3)</span><input type="text" class="save-this" id="23_3"></div>
            </td>
            <td>13.</td>
            <td>得退稅限額合計</td>
            <td>(3)×5%+(10)<br>113</td>
            <td><input type="text" class="save-this" id="113"></td>
          </tr>
          <tr>
            <td rowspan="2">
              銷售額總計<br>
              (1)+(3)
            </td>
            <td rowspan="2" colspan="6">
              <div class="flex">
                <span style="line-height: 26px;">25(7)</span>
                <input type="text" class="save-this" id="25_7" style="width: 35%;">
                <span style="line-height: 26px;">元(</span>
                <div>內含銷售<br>固定資產</div>
                <span style="line-height: 26px;">27</span>
                <input type="text" class="save-this" id="27" style="width: 35%;">&nbsp;元)
              </div>
            </td>
            <td>14.</td>
            <td style="font:9px;">本期(月)應退稅額合計(如12>13則13,13>12則12)</td>
            <td>114</td>
            <td><input type="text" class="save-this" id="114"></td>
          </tr>
          <tr>
            <td>15.</td>
            <td>本期(月)累積留抵稅額(12-14)</td>
            <td>115</td>
            <td><input type="text" class="save-this" id="115"></td>
          </tr>
        </table>
        <table>
          <tr>
            <td rowspan="17" style="width: 20px;border-top-color: white;text-align: center;">進項</td>
            <td rowspan="2" colspan="3" style="border-top-color: white;text-align: center;width: 300px;">項目\區分</td>
            <td colspan="4" style="border-top-color: white;text-align: center;">得扣抵進項稅額</td>
            <td colspan="5" rowspan="2" style="width: 420px;border-top-color:white ;text-align: center;"></td>
          </tr>
          <tr>
            <td colspan="2" style="text-align: center;">金額</td>
            <td colspan="2" style="text-align: center;">稅額</td>
          </tr>
          <tr>
            <td rowspan="2" colspan="2">
              統一發票扣抵聯<br>
              (包括一般稅額計算之電子計算機發票扣抵聯)
            </td>
            <td style="text-align: center;">進貨及費用</td>
            <td colspan="2">
              <div class="flex"><span>28</span><input type="text" class="save-this" id="28"></div>
            </td>
            <td colspan="2">
              <div class="flex"><span>29</span><input type="text" class="save-this" id="29"></div>
            </td>
            <td colspan="3" rowspan="3">保稅區營業人按進口報關程序銷售貨物至我國境內課稅區之免開立統一發票銷售額</td>
            <td colspan="2" rowspan="3" style="text-align: right;">
              <div class="flex"><input type="text" class="save-this" id="82"><span>元</span></div>
            </td>
          </tr>
          <tr>
            <td style="text-align: center;">固定資產</td>
            <td colspan="2">
              <div class="flex"><span>30</span><input type="text" class="save-this" id="30"></div>
            </td>
            <td colspan="2">
              <div class="flex"><span>31</span><input type="text" class="save-this" id="31"></div>
            </td>
          </tr>
          <tr>
            <td rowspan="2" colspan="2">三聯式收銀機發票扣抵聯及一般稅額計算之電子發票</td>
            <td style="text-align: center;">進貨及費用</td>
            <td colspan="2">
              <div class="flex"><span>32</span><input type="text" class="save-this" id="32"></div>
            </td>
            <td colspan="2">
              <div class="flex"><span>33</span><input type="text" class="save-this" id="33"></div>
            </td>
          </tr>
          <tr>
            <td style="text-align: center;">固定資產</td>
            <td colspan="2">
              <div class="flex"><span>34</span><input type="text" class="save-this" id="34"></div>
            </td>
            <td colspan="2">
              <div class="flex"><span>35</span><input type="text" class="save-this" id="35"></div>
            </td>
            <td colspan="3" style="border-bottom-color:white ;">
              <div class="flex"><span>收件編號:</span><input type="text" class="save-this" id="收件編號"></div>
            </td>
            <td colspan="2" rowspan="11"></td>
          </tr>
          <tr>
            <td rowspan="2" colspan="2">
              載有稅額之其他憑證<br>
              (包括二聯式收銀機票)
            </td>
            <td style="text-align: center;">進貨及費用</td>
            <td colspan="2">
              <div class="flex"><span>36</span><input type="text" class="save-this" id="36"></div>
            </td>
            <td colspan="2">
              <div class="flex"><span>37</span><input type="text" class="save-this" id="37"></div>
            </td>
            <td style="border-right-color:white ;border-bottom-color:white ;">申報日期:</td>
            <td colspan="2" style="border-bottom-color:white ;text-align: right;">
              <div class="flex">
                <input type="text" id="">年<input type="text" id="">月<input type="text" id="">日</div>
            </td>
          </tr>
          <tr>
            <td style="text-align: center;">固定資產</td>
            <td colspan="2">
              <div class="flex"><span>38</span><input type="text" class="save-this" id="38"></div>
            </td>
            <td colspan="2">
              <div class="flex"><span>39</span><input type="text" class="save-this" id="39"></div>
            </td>
            <td style="border-right-color:white ;border-bottom-color:white ;">申報次數:</td>
            <td colspan="2" style="border-bottom-color:white ;text-align: right;">
              <div class="flex"><input type="text" id=""><span>次</span></div>
            </td>
          </tr>
          <tr>
            <td rowspan="2" colspan="2">海關代徵營業稅繳納證扣抵聯</td>
            <td style="text-align: center;">進貨及費用</td>
            <td colspan="2">
              <div class="flex"><span>78</span><input type="text" class="save-this" id="78"></div>
            </td>
            <td colspan="2">
              <div class="flex"><span>79</span><input type="text" class="save-this" id="79"></div>
            </td>
            <td style="border-right-color:white ;border-bottom-color:white ;">進銷項筆數:</td>
            <td colspan="2" style="border-bottom-color:white ;text-align: right;">
              <div class="flex"><input type="text" id=""><span>筆</span></div>
            </td>
          </tr>
          <tr>
            <td style="text-align: center;">固定資產</td>
            <td colspan="2">
              <div class="flex"><span>80</span><input type="text" class="save-this" id="80"></div>
            </td>
            <td colspan="2">
              <div class="flex"><span>81</span><input type="text" class="save-this" id="81"></div>
            </td>
            <td style="border-right-color:white ;border-bottom-color:white ;">法院拍賣進銷項筆數:</td>
            <td colspan="2" style="border-bottom-color:white ;text-align: right;">
              <div class="flex"><input type="text" id=""><span>筆</span></div>
            </td>
          </tr>
          <tr>
            <td rowspan="2" colspan="2">減：退出、折讓及海關退還溢繳稅款</td>
            <td style="text-align: center;">進貨及費用</td>
            <td colspan="2">
              <div class="flex"><span>40</span><input type="text" class="save-this" id="40"></div>
            </td>
            <td colspan="2">
              <div class="flex"><span>41</span><input type="text" class="save-this" id="41"></div>
            </td>
            <td style="border-right-color:white ;border-bottom-color:white ;">零稅率銷售項筆數:</td>
            <td colspan="2" style="border-bottom-color:white ;text-align: right;">
              <div class="flex"><input type="text" id=""><span>筆</span></div>
            </td>
          </tr>
          <tr>
            <td style="text-align: center;">固定資產</td>
            <td colspan="2">
              <div class="flex"><span>42</span><input type="text" class="save-this" id="42"></div>
            </td>
            <td colspan="2">
              <div class="flex"><span>43</span><input type="text" class="save-this" id="43"></div>
            </td>
            <td colspan="2" style="border-right-color:white ;border-bottom-color:white ;">營業人申報固定資產退稅清單筆數:</td>
            <td style="border-bottom-color:white ;text-align: right;">
              <div class="flex"><input type="text" id=""><span>筆</span></div>
            </td>
          </tr>
          <tr>
            <td rowspan="2" colspan="2">合計</td>
            <td style="text-align: center;">進貨及費用</td>
            <td colspan="2">
              <div class="flex"><span>44</span><input type="text" class="save-this" id="44"></div>
            </td>
            <td colspan="2">
              <div class="flex"><span>45(9)</span><input type="text" class="save-this" id="45_9"></div>
            </td>
            <td colspan="2" style="border-right-color:white ;border-bottom-color:white ;">營業人購買舊乘人小汽車及機車進項憑證明細筆數:</td>
            <td style="border-bottom-color:white ;text-align: right;">
              <div class="flex"><input type="text" id=""><span>筆</span></div>
            </td>
          </tr>
          <tr>
            <td style="text-align: center;">固定資產</td>
            <td colspan="2">
              <div class="flex"><span>46</span><input type="text" class="save-this" id="46"></div>
            </td>
            <td colspan="2">
              <div class="flex"><span>47(10)</span><input type="text" class="save-this" id="47_10"></div>
            </td>
            <td style="border-right-color:white ;border-bottom-color:white ;">已納稅額:</td>
            <td colspan="2" style="border-bottom-color:white ;text-align: right;">
              <div class="flex"><input type="text" class="save-this" id="44_1"><span>元</span></div>
            </td>
          </tr>
          <tr>
            <td rowspan="3" colspan="2">
              進項總金額<br>
              (包括不得扣抵憑證及普通收據)
            </td>
            <td rowspan="2" style="text-align: center;">進貨及費用</td>
            <td rowspan="2" colspan="4">
              <div class="flex"><span>48</span><input type="text" class="save-this" id="48"><span>元</span></div>
            </td>
            <td style="border-right-color:white ;border-bottom-color:white ;">最後異動日期:</td>
            <td colspan="2" style="border-bottom-color:white ;">
              <div class="flex">
                <input type="text" class="save-this" id="LastChange_Y"><span>年</span>
                <input type="text" class="save-this" id="LastChange_M"><span>月</span>
                <input type="text" class="save-this" id="LastChange_D"><span>日</span>
                <input type="text" class="save-this" id="LastChange_H"><span>:</span>
                <input type="text" class="save-this" id="LastChange_Min"><span>:</span>
                <input type="text" class="save-this" id="LastChange_S">
              </div>
            </td>
          </tr>
          <tr>
            <td style="border-right-color:white ;">製表日期:</td>
            <td colspan="2">
              <div class="flex"><input type="text" class="save-this" id="Tabulation_Y"><span>年</span><input type="text"
                 class="save-this"  id="Tabulation_M"><span>月</span><input type="text" class="save-this" id="Tabulation_D"><span>日</span></div>
            </td>
          </tr>
          <tr>
            <td style="text-align: center;">固定資產</td>
            <td colspan="4">
              <div class="flex"><span>49</span><input type="text" class="save-this" id="49"><span>元</span></div>
            </td>
            <td style="text-align: center;">申辦情形</td>
            <td style="text-align: center;">姓名</td>
            <td style="text-align: center;">身分證統一編號</td>
            <td style="text-align: center;">電話</td>
            <td style="text-align: center;">登錄文(字)號</td>
          </tr>
          <tr>
            <td colspan="3" style="text-align: center;">進口免稅貨物</td>
            <td colspan="5">
              <div class="flex"><span>73</span><input type="text" class="save-this" id="ImportDutyFree"><span>元</span></div>
            </td>
            <td style="text-align: center;">自行申報</td>
            <td><input type="text" class="save-this" id="Name_Self"></td>
            <td><input type="text" class="save-this" id="IDNumber_Self"></td>
            <td><input type="text" class="save-this" id="PhoneNumber_Self"></td>
            <td><input type="text" class="save-this" id="RegisterNumber_Self"></td>
          </tr>
          <tr>
            <td colspan="3" style="text-align: center;">購買國外勞務</td>
            <td colspan="5">
              <div class="flex"><span>74</span><input type="text" id="BuyForeignLabor"><span>元</span></div>
            </td>
            <td style="text-align: center;">委任申報</td>
            <td><input type="text" class="save-this" id="Name_Appoint"></td>
            <td><input type="text" class="save-this" id="IDNumber_Appoint"></td>
            <td><input type="text" class="save-this" id="PhoneNumber_Appoint"></td>
            <td><input type="text" class="save-this" id="RegisterNumber_Appoint"></td>
          </tr>
          <tr>
            <td style="text-align: center;" rowspan="3">說明</td>
            <td colspan="12" rowspan="3">
              一、本申報書適用專營應稅及零稅率之營業人填報。<br>
              二、如營業人申報當期(月)之銷售額包括有免稅、特種稅額計算銷售額者，請改用(403)申報書申報。<br>
              三、納稅者如有依納稅者權利保護法第7條第8項但書規定，為重要事項陳述者，請另填報「營業稅聲明事項表」並檢附相關證明文件。
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
    <script>
        $(document).ready(function() {
            var urlString = window.location.href;
            var url = new URL(urlString);
            var certification_id = url.searchParams.get("certification");
            var imageId = url.searchParams.get("id");
            var check = url.searchParams.get("check");

            // 儲存按鈕
            $( "#save_click" ).on('click',function() {
              save_click('business_tax_return_report', imageId, certification_id);
            });

            // 送出按鈕
            $( "#send_click" ).on('click',function() {
              send_click('business_tax_return_report', imageId, certification_id);
            });

            fetchReport('business_tax_return_report', imageId, check, certification_id, function (data) {
                if (!data) {
                    return;
                }
                if(data.data_type){
                  fillReport(data,'business_tax_return','report')
                }else{
                  // var businessTax = data.report.business_tax_return_logs.items[0].business_tax_return;
                  fillReport(data.company_info,'business_tax_return','report')

                  $('#report_time_range').val(data.report_time_range);
                  if(check == '1'){
                    fillTableData(businessTax.table,1)
                  }else{
                    fillReport(data.output_info,'business_tax_return','report')
                    fillReport(data.input_info,'business_tax_return','report')
                    fillReport(data.tax_calculation,'business_tax_return','report')
                  }
                }

            });

        });
    </script>
</body>
</html>

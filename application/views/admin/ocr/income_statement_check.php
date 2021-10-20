<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin/css/ocr/income_statement.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="<?=base_url()?>assets/admin/js/ocr/fetch.js"></script>
    <title>損益表</title>
  </head>

  <body style="text-align:center;">
    <div class="book">
      <div class="page" style="zoom:0;overflow:auto;">
        <div class="subpage">
          <h3>
            <input  class="check-this" id='year'>
            年度損益及稅額計算表
          </h3>
          <div>
            <button class="load-value-btn" type="submit" style="display:inline;"value="get">載入模版資料</button>
            <button class="input-value-btn" type="submit" style="display:inline;"value="post">提交輸入值</button>
            <button class="search-value-btn" type="submit" style="display:inline;"value="search">查詢所有模版</button>
          </div>
          <p>
            所得期間：自民國&nbsp;<input class="check-this" id='start_at'>起至&nbsp<input class="check-this" id='end_at'>
          </p>
          <table>
            <tr>
              <td rowspan="3" style="text-align: center;">營利事業名稱</td>
              <td rowspan="3" style="width: 270px;"><input style="height:100%;width:100%;" class="check-this" id="companyName"></td>
              <td rowspan="3" style="text-align: center;width: 30px;">營利事業統一編號</td>
              <td rowspan="3" colspan="8" style="width: 208px;" ><input style="height:100%;width:100%;" class="check-this" id="taxId"></td>
              <td rowspan="3" style="text-align: center;">組織種類</td>
              <td>1股份</td>
              <td><input type="checkbox"  class="check-this" id="cb_1"></td>
              <td>4兩合</td>
              <td><input type="checkbox"  class="check-this" id="cb_4"></td>
              <td>7外國分公司</td>
              <td><input type="checkbox"  class="check-this" id="cb_7"></td>
            </tr>
            <tr>
              <td>2有限</td>
              <td><input type="checkbox"  class="check-this" id="cb_2"></td>
              <td>5合夥</td>
              <td><input type="checkbox"  class="check-this" id="cb_5"></td>
              <td>8外國辦事處</td>
              <td><input type="checkbox"  class="check-this" id="cb_8"></td>
            </tr>
            <tr>
              <td>3無限</td>
              <td><input type="checkbox"  class="check-this" id="cb_3"></td>
              <td>6獨資</td>
              <td><input type="checkbox"  class="check-this" id="cb_6"></td>
              <td>0其他</td>
              <td><input type="checkbox"  class="check-this" id="cb_0"></td>
            </tr>
            <tr>
              <td colspan="2" style="text-align: center;">損益項目</td>
              <td colspan="5" style="text-align: center;">帳載結算金額</td>
              <td colspan="4" style="text-align: center;">自行依法調整後金額</td>
              <td colspan="7" style="text-align: center;">營業收入調節說明</td>
            </tr>
            <tr>
              <td rowspan="34" style="text-align: center;">營業淨利</td>
              <td>
                01&nbsp;營業收入總額(包括外匯收入&nbsp;<input style="width:20px;" class="check-this" id="1_AddExchange">&nbsp;元)
              </td>
              <td>01</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="1"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="1_AddAdjust"></td>
              <td colspan="7" rowspan="17">
                本年度結算申報營業收入總額01
                <input type="text" size="10px" style=" border:1px; border-bottom-style:solid; text-align:right;" class="check-this" id="01_Total">元<br>
                與總分支機構申報營業稅銷售額68
                <input type="text" size="10px" style=" border:1px; border-bottom-style:solid; text-align:right;" class="check-this" id="68">元</br>
                相差69
                <input type="text" size="10px" style=" border:1px; border-bottom-style:solid; text-align:right;" class="check-this" id="69">元，說明如下:</br>
                加:70上期結轉本期預收款
                <input type="text" size="10px" style=" border:1px; border-bottom-style:solid; text-align:right;" class="check-this" id="70">元</br>
                &nbsp;&nbsp;&nbsp;&nbsp;71本期應收未開立發票金額
                <input type="text" size="10px" style=" border:1px; border-bottom-style:solid; text-align:right;" class="check-this" id="71">元</br>
                &nbsp;&nbsp;&nbsp;&nbsp;75其他(請附明細表或說明)
                <input type="text" size="10px" style=" border:1px; border-bottom-style:solid; text-align:right;" class="check-this" id="75">元</br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;說明:
                <input type="text" size="10px" style=" border:1px; border-bottom-style:solid; text-align:right;" class="check-this" id="Explanation_Add"></br>
                減:76本期預收款
                <input type="text" size="10px" style=" border:1px; border-bottom-style:solid; text-align:right;" class="check-this" id="76">元</br>
                &nbsp;&nbsp;&nbsp;&nbsp;77上期應收本期開立發票金額
                <input type="text" size="10px" style=" border:1px; border-bottom-style:solid; text-align:right;" class="check-this" id="77">元</br>
                &nbsp;&nbsp;&nbsp;&nbsp;78視為銷貨開立發票金額(請附明細表)
                <input type="text" size="10px" style=" border:1px; border-bottom-style:solid; text-align:right;" class="check-this" id="78">元</br>
                &nbsp;&nbsp;&nbsp;&nbsp;79本期溢開發票金額
                <input type="text" size="10px" style=" border:1px; border-bottom-style:solid; text-align:right;" class="check-this" id="79">元</br>
                &nbsp;&nbsp;&nbsp;&nbsp;80佣金收入
                <input type="text" size="10px" style=" border:1px; border-bottom-style:solid; text-align:right;" class="check-this" id="80">元</br>
                &nbsp;&nbsp;&nbsp;&nbsp;81租賃收入
                <input type="text" size="10px" style=" border:1px; border-bottom-style:solid; text-align:right;" class="check-this" id="81">元</br>
                &nbsp;&nbsp;&nbsp;&nbsp;82出售下腳廢料
                <input type="text" size="10px" style=" border:1px; border-bottom-style:solid; text-align:right;" class="check-this" id="82">元</br>
                &nbsp;&nbsp;&nbsp;&nbsp;83出售資產
                <input type="text" size="10px" style=" border:1px; border-bottom-style:solid; text-align:right;" class="check-this" id="83">元</br>
                &nbsp;&nbsp;&nbsp;&nbsp;84代收款(請附明細表)
                <input type="text" size="10px" style=" border:1px; border-bottom-style:solid; text-align:right;" class="check-this" id="84">元</br>
                &nbsp;&nbsp;&nbsp;&nbsp;85因信託行為開立發票金額(請附明細表)
                <input type="text" size="10px" style=" border:1px; border-bottom-style:solid; text-align:right;" class="check-this" id="85">元</br>
                &nbsp;&nbsp;&nbsp;&nbsp;87其他(請附明細表或說明)
                <input type="text" size="10px" style=" border:1px; border-bottom-style:solid; text-align:right;" class="check-this" id="87">元</br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;說明:
                <input type="text" size="54px" style=" border:1px; border-bottom-style:solid; text-align:right;" class="check-this" id="Explanation_minus">
              </td>
            </tr>
            <tr>
              <td>02&nbsp;減:銷貨退回</td>
              <td style="text-align: center;">02</td>
              <td colspan="4" style="text-align:right"><input style="width:100%;" class="check-this" id="2"></td>
              <td colspan="4" style="text-align:right"><input style="width:100%;" class="check-this" id="2_AddAdjust"></td>
            </tr>
            <tr>
              <td>03&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;銷貨折讓</td>
              <td style="text-align: center;">03</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="3"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="3_AddAdjust"></td>
            </tr>
            <tr>
              <td>04&nbsp;營業收入淨額(01-02-03)</td>
              <td style="text-align: center;">04</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="4"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="4_AddAdjust"></td>
            </tr>
            <tr>
              <td>05&nbsp;營業成本(請填第4頁明細表)</td>
              <td style="text-align: center;">05</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="5"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="5_AddAdjust"></td>
            </tr>
            <tr>
              <td>06&nbsp;營業毛利(04-05)</td>
              <td style="text-align: center;">06</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="6"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="6_AddAdjust"></td>
            </tr>
            <tr>
              <td>07&nbsp;毛利率(06÷04×100)</td>
              <td style="text-align: center;">07</td>
              <td colspan="4" style="text-align:right"><input style="width:80%;" class="check-this" id="7">&nbsp;%</td>
              <td colspan="4" style="text-align:right"><input style="width:80%;" class="check-this" id="7_AddAdjust">&nbsp;%</td>
            </tr>
            <tr>
              <td>08&nbsp;營業費用及損失總額(10至32合計)</td>
              <td style="text-align: center;">08</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="8"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="8_AddAdjust"></td>
            </tr>
            <tr>
              <td>09&nbsp;費用率(08÷04×100)</td>
              <td style="text-align: center;">09</td>
              <td colspan="4" style="text-align:right"><input style="width:80%;" class="check-this" id="9">&nbsp;%</td>
              <td colspan="4" style="text-align:right"><input style="width:80%;" class="check-this" id="9_AddAdjust">&nbsp;%</td>
            </tr>
            <tr>
              <td>10&nbsp;薪資支出</td>
              <td style="text-align: center;">10</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="10"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="10_AddAdjust"></td>
            </tr>
            <tr>
              <td>11&nbsp;租金支出</td>
              <td style="text-align: center;">11</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="11"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="11_AddAdjust"></td>
            </tr>
            <tr>
              <td>12&nbsp;文具用品</td>
              <td style="text-align: center;">12</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="12"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="12_AddAdjust"></td>
            </tr>
            <tr>
              <td>13&nbsp;旅費</td>
              <td style="text-align: center;">13</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="13"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="13_AddAdjust"></td>
            </tr>
            <tr>
              <td>14&nbsp;運費</td>
              <td style="text-align: center;">14</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="14"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="14_AddAdjust"></td>
            </tr>
            <tr>
              <td>15&nbsp;郵電費</td>
              <td style="text-align: center;">15</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="15"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="15_AddAdjust"></td>
            </tr>
            <tr>
              <td>16&nbsp;修繕費</td>
              <td style="text-align: center;">16</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="16"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="16_AddAdjust"></td>
            </tr>
            <tr>
              <td>17&nbsp;廣告費</td>
              <td style="text-align: center;">17</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="17"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="17_AddAdjust"></td>
            </tr>
            <tr>
              <td>18&nbsp;水電瓦斯費</td>
              <td style="text-align: center;">18</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="18"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="18_AddAdjust"></td>
              <td colspan="7" style="text-align: center;">
                總分支機構申報營業稅額銷售之銷貨退回及折讓差異情形
              </td>
            </tr>
            <tr>
              <td>19&nbsp;保險費</td>
              <td style="text-align: center;">19</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="19"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="19_AddAdjust"></td>
              <td colspan="7" rowspan="4">128營業稅申報銷貨退回及折讓金額:
                <input type="text" size="10px" style=" border:1px; border-bottom-style:solid; text-align:right;" class="check-this" id="128">
                元，與本頁02銷貨退回及03銷貨折讓之差異說明:
                <input type="text" size="54px" style=" border:1px; border-bottom-style:solid; text-align:right;" class="check-this" id="Explanation_128">
              </td>
            </tr>
            <tr>
              <td>20&nbsp;交際費</td>
              <td style="text-align: center;">20</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="20"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="20_AddAdjust"></td>
            </tr>
            <tr>
              <td>21&nbsp;捐贈</td>
              <td style="text-align: center;">21</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="21"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="21_AddAdjust"></td>
            </tr>
            <tr>
              <td>22&nbsp;稅捐</td>
              <td style="text-align: center;">22</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="22"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="22_AddAdjust"></td>
            </tr>
            <tr>
              <td>23&nbsp;呆帳損失</td>
              <td style="text-align: center;">23</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="23"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="23_AddAdjust"></td>
              <td colspan="7" style="text-align: center;">揭露事實</td>
            </tr>
            <tr>
              <td>24&nbsp;折舊</td>
              <td style="text-align: center;">24</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="24"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="24_AddAdjust"></td>
              <td colspan="7" rowspan="3">本營利事業本期進貨、進料、費用及損失，因交易相對人應給與而未給與統一發票，致無法取得合法憑證，惟已誠實入帳，能提示送貨單、交易相關文件及支付款項資料者，屬進貨成本&nbsp;<input style="width:20%;" class="check-this" id="PurchaseCost">&nbsp;元、營業費用及損失&nbsp;<input style="width:20%;" class="check-this" id="OperatingExpensesAndLosses">&nbsp;元</td>
            </tr>
            <tr>
              <td>25&nbsp;各項耗竭及攤提(請另填附明細表)</td>
              <td style="text-align: center;">25</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="25"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="25_AddAdjust"></td>
            </tr>
            <tr>
              <td>26&nbsp;外銷損失</td>
              <td style="text-align: center;">26</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="26"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="26_AddAdjust"></td>
            </tr>
            <tr>
              <td>27&nbsp;伙食費</td>
              <td style="text-align: center;">27</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="27"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="27_AddAdjust"></td>
              <td colspan="7" style="text-align: center;">依所得基本稅額條例實行細則第5條第2項規定自行擇定申報情形</td>
            </tr>
            <tr>
              <td>28&nbsp;職工福利</td>
              <td style="text-align: center;">28</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="28"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="28_AddAdjust"></td>
              <td colspan="7" rowspan="7">
                501&nbsp;本年度擇定申報合於獎勵規定之免稅所得
                <input type="text" size="10px" style=" border:1px; border-bottom-style:solid; text-align:right;" class="check-this" id="57_AddAdjust">
                元(請將本欄金額填入第57欄)</br>
                502&nbsp;本年度擇定申報前十年核定虧損本年度扣除額
                <input type="text" size="10px" style=" border:1px; border-bottom-style:solid; text-align:right;" class="check-this" id="55_AddAdjust">
                元(請將本欄金額填入第55欄)</br>
                (本年度有合於獎勵規定之免稅所得及前十年虧損扣除之適用者，請依規定於填寫本申報書時自行擇定減除順序及金額。)
              </td>
            </tr>
            <tr>
              <td>29&nbsp;研究費</td>
              <td style="text-align: center;">29</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="29"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="29_AddAdjust"></td>
            </tr>
            <tr>
              <td>30&nbsp;佣金支出</td>
              <td style="text-align: center;">30</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="30"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="30_AddAdjust"></td>
            </tr>
            <tr>
              <td>31&nbsp;訓練費</td>
              <td style="text-align: center;">31</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="31"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="31_AddAdjust"></td>
            </tr>
            <tr>
              <td>32&nbsp;其他費用(請填第5頁明細表)</td>
              <td style="text-align: center;">32</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="32"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="32_AddAdjust"></td>
            </tr>
            <tr>
              <td>33&nbsp;營業淨利(06-08)</td>
              <td style="text-align: center;">33</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="33"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="33_AddAdjust"></td>
            </tr>
            <tr>
              <td>104&nbsp;營業淨利率(33÷04×100)</td>
              <td style="text-align: center;">104</td>
              <td colspan="4" style="text-align:right"><input style="width:80%;" class="check-this" id="104">&nbsp;%</td>
              <td colspan="4" style="text-align:right"><input style="width:80%;" class="check-this" id="104_AddAdjust">&nbsp;%</td>
            </tr>
            <tr>
              <td rowspan="9" style="text-align: center;">非營業收益</td>
              <td>34&nbsp;非營業收入總額(35至44合計)</td>
              <td style="text-align: center;">34</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="34"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="34_AddAdjust"></td>
              <td colspan="7" style="text-align: center;">營業收入分類表</td>
            </tr>
            <tr>
              <td>35&nbsp;投資收益及一般股息及紅利(含國外)</td>
              <td style="text-align: center;">35</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="35"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="35_AddAdjust"></td>
              <td rowspan="2" style="text-align: center;">標準代號</td>
              <td rowspan="2" style="text-align: center;">小業別</td>
              <td rowspan="2" style="text-align: center;">擴大書審純益率</td>
              <td rowspan="2" style="text-align: center;">所得額標準</td>
              <td colspan="3" rowspan="2" style="text-align: center;">營業收入淨額</td>
            </tr>
            <tr>
              <td>
                36&nbsp;依所得稅法第42條規定取得之股利淨額或盈餘淨額(不含權益法之投資收益)
              </td>
              <td style="text-align: center;">36</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="36"></td>
              <td colspan="4" style="text-align:right"></td>
            </tr>
            <tr>
              <td>38&nbsp;利息收入</td>
              <td style="text-align: center;">38</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="38"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="38_AddAdjust"></td>
              <td>89&nbsp;<input style="width:50%;text-align:right;" class="check-this" id="89"></td>
              <td ><input style="width:100%;" class="check-this" id="SmallBusiness_1"></td>
              <td ><input style="width:100%;" class="check-this" id="ExpandedReviewIncome_1"></td>
              <td ><input style="width:100%;" class="check-this" id="IncomeStandard_1"></td>
              <td colspan="3">90&nbsp;<input style="width:50%;text-align:right;" class="check-this" id="90"></td>
            </tr>
            <tr>
              <td>39&nbsp;投資損失</td>
              <td style="text-align: center;">39</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="39"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="39_AddAdjust"></td>
              <td>91&nbsp;<input style="width:50%;text-align:right;" class="check-this" id="91_StandardCode"></td>
              <td ><input style="width:100%;" class="check-this" id="SmallBusiness_2"></td>
              <td ><input style="width:100%;" class="check-this" id="ExpandedReviewIncome_2"></td>
              <td ><input style="width:100%;" class="check-this" id="IncomeStandard_2"></td>
              <td colspan="3">92&nbsp;<input style="width:50%;text-align:right;" class="check-this" id="92_NetIncome"></td>
            </tr>
            <tr>
              <td>40&nbsp;出售資產損失(包括證券、期貨、土地交易損失)</td>
              <td style="text-align: center;">40</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="40"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="40_AddAdjust"></td>
              <td>94&nbsp;<input style="width:50%;text-align:right;" class="check-this" id="94_StandardCode"></td>
              <td ><input style="width:100%;" class="check-this" id="SmallBusiness_3"></td>
              <td ><input style="width:100%;" class="check-this" id="ExpandedReviewIncome_3"></td>
              <td ><input style="width:100%;" class="check-this" id="IncomeStandard_3"></td>
              <td colspan="3">96&nbsp;<input style="width:50%;text-align:right;" class="check-this" id="96_NetIncome"></td>
            </tr>
            <tr>
              <td>41&nbsp;佣金收入</td>
              <td style="text-align: center;">41</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="41"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="41_AddAdjust"></td>
              <td colspan="7" rowspan="9" style="border-bottom-color:white ;">
                102&nbsp;本營利事業自&nbsp;<input style="width:20%;" class="check-this" id="Invoice_Y">&nbsp;年&nbsp;<input style="width:20%;" class="check-this" id="Invoice_M">&nbsp;月起經核准使用收銀機開立統一發票，並自&nbsp;<input style="width:20%;" class="check-this" id="EInvoice_Y">&nbsp;年&nbsp;<input  class="check-this" id="EInvoice_M">&nbsp;月起經核准且全部改依電子發票實施作業要點規定開立電子發票。</br>
                122&nbsp;所得稅費用(利益)<input type="text" size="10px" style=" border:1px; border-bottom-style:solid; text-align:right;" class="check-this" id="IncomeTax">元(請附明細表)。</br>
                123&nbsp;依臺灣地區與大陸地區人民關係條例規定之大陸地區來源所得<input type="text" size="10px" style=" border:1px; border-bottom-style:solid; text-align:right;" class="check-this" id="TaiwanAndChinaIncomeInLaw">元。</br>
                124&nbsp;本年度存貨計價係
                  a<input type="checkbox" class="check-this" id="cb_a">沿用
                  b<input type="checkbox" class="check-this" id="cb_b">變更                 
                原方法，採用<input type="text" size="10px" style=" border:1px; border-bottom-style:solid; text-align:right;" class="check-this" id="Method">法，盤存制度採用       
                  c<input type="checkbox" class="check-this" id="cb_c">永續
                  d<input type="checkbox" class="check-this" id="cb_d">實地       
                存貨評價基礎係     
                  e<input type="checkbox" class="check-this" id="cb_e">成本
                  f<input type="checkbox" class="check-this" id="cb_f">成本與淨變現價值孰低
                基礎。</br>
                127&nbsp;依「營利事業所得稅結算申報案件擴大書面審核實施要點」辦理結算申報者，其土地及其定著物(如房屋)之交易增益暨依法不計入所得課稅之所得額為<input type="text" size="10px" style=" border:1px; border-bottom-style:solid; text-align:right;" class="check-this" id="IncomeTaxCredit">元。</br>
              </td>
            </tr>
            <tr>
              <td>43&nbsp;兌換盈益</td>
              <td style="text-align: center;">43</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="43"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="43_AddAdjust"></td>
            </tr>
            <tr>
              <td>
                44&nbsp;其他收入(包括97退稅收入&nbsp;<input style="width:20%;" class="check-this" id="OrtherIncome">&nbsp;元)
              </td>
              <td style="text-align: center;">44</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="44"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="44_AddAdjust"></td>
            </tr>
            <tr>
              <td rowspan="7" style="text-align: center;">非營業損失</td>
              <td>45&nbsp;非營業損失及費用總額(46至52合計)</td>
              <td style="text-align: center;">45</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="45"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="45_AddAdjust"></td>
            </tr>
            <tr>
              <td>46&nbsp;利息支出</td>
              <td style="text-align: center;">46</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="46"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="46_AddAdjust"></td>
            </tr>
            <tr>
              <td>47&nbsp;投資損失</td>
              <td style="text-align: center;">47</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="47"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="47_AddAdjust"></td>
            </tr>
            <tr>
              <td>48&nbsp;出售資產損失(包括證券、期貨、土地交易損失)</td>
              <td style="text-align: center;">48</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="48"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="48_AddAdjust"></td>
            </tr>
            <tr>
              <td>49&nbsp;災害損失</td>
              <td style="text-align: center;">49</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="49"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="49_AddAdjust"></td>
            </tr>
            <tr>
              <td>51&nbsp;兌換虧損</td>
              <td style="text-align: center;">51</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="51"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="51_AddAdjust"></td>             
            </tr>
            <tr>
              <td>52&nbsp;其他損失</td>
              <td style="text-align: center;">52</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="52"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="52_AddAdjust"></td>
              <td colspan="7" rowspan="14" style="font-size: x-small;">
                附註:</br>
                一、申報時，請依本申報書所列之損益項目順序填寫，無法歸類之營業費用及損失，請一律填</br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;於第32欄「其他費用」項目。</br>
                二、標準代號如「棉梭織布製造」1121-11「其他汽車維修」9511-99......等，請參考本申報書最</br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;後附表「稅務行業標準代號及擴大書面審核適用純益率標準彚編表」之代號。</br>         
                三、營業收入之分類表按小業別的銷貨收入之大小順序填列，並以營業收入最多之小業別代號</br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;為業別標準代號。</br>
                四、全年所得額為虧損時，請在金額前冠上負號。稅率表、稅額計算及應填送之附表均詳見本</br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;頁第2聯背面「申報須知」;證券、期貨、土地交易所得等免稅所得項目請另附損益表並黏</br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;貼於第10頁之附件黏貼欄。</br>
                五、獨資、合夥組織之營利事業應依規定辦理結(決)算申報，無須計算及繳納其應納稅額，免填</br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;寫本頁稅額計算相關欄位;當年度各類所得之已扣繳稅額，請填報於第13頁第 (16)欄「扣繳</br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;稅額」，由獨資資本主或合夥組織合夥人辦理同年度綜合所得稅結算申報 時，自應納稅額</br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;中減除。</br>
                六、營利事業依所得稅法第24條之4規定計算營利事業所得額者，應檢附營利事業及其子公司</br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;營運船舶明細表及經營利事業所得稅查核簽證申報會計師複核之海運業務及非海運業務收</br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;入、成本、費用、損失明細表，上開收入、成本、費用、損失明細表【2H】金額應與本頁</br>
                &nbsp;&nbsp;&nbsp;&nbsp;【125】欄金額相符。
              </td>
            </tr>
            <tr>
              <td rowspan="13" style="text-align: center;">損益及課稅所得</td>
              <td>53&nbsp;全年所得額(33+34-45)</td>
              <td style="text-align: center;">53</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="53"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="53_AddAdjust"></td>
            </tr>
            <tr>
              <td>54&nbsp;純益率〔53÷(04+34)×100〕</td>
              <td style="text-align: center;">54</td>
              <td colspan="4" style="text-align:right"><input style="width:80%;" class="check-this" id="54">&nbsp;%</td>
              <td colspan="4" style="text-align:right"><input style="width:80%;" class="check-this" id="54_AddAdjust">&nbsp;%</td>
            </tr>
            <tr>
              <td>93&nbsp;國際金融(證券)業務分行(分公司)免稅所得</td>
              <td style="text-align: center;">93</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="93"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="93_AddAdjust"></td>
            </tr>
            <tr>
              <td>99&nbsp;停徵之證券、期貨交易所得(損失)</td>
              <td style="text-align: center;">99</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="99"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="99_AddAdjust"></td>
            </tr>
            <tr>
              <td>101&nbsp;免徵所得稅之出售土地增益</td>
              <td style="text-align: center;">101</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="101"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="101_AddAdjust"></td>
            </tr>
            <tr>
              <td>57&nbsp;合於獎勵規定之免稅所得</td>
              <td style="text-align: center;">57</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="57"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="57_AddAdjust"></td>
            </tr>
            <tr>
              <td>125&nbsp;適用噸位稅制收入所得</td>
              <td style="text-align: center;">125</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="125"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="125_AddAdjust"></td>
            </tr>
            <tr>
              <td>126&nbsp;依船舶淨噸位計算之所得(請附計算表)</td>
              <td style="text-align: center;">126</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="126"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="126_AddAdjust"></td>
            </tr>
            <tr>
              <td>129&nbsp;中小企業增僱員工薪資費用加成減除金【詳申報須知八、(八)】</td>
              <td style="text-align: center;">129</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="129"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="129_AddAdjust"></td>
            </tr>
            <tr>
              <td>132&nbsp;智慧財產權讓與或授權收益範圍內之研發支出加倍</td>
              <td style="text-align: center;">132</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="132"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="132_AddAdjust"></td>
            </tr>
            <tr>
              <td>58&nbsp;</td>
              <td style="text-align: center;">58</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="58"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="58_AddAdjust"></td>
            </tr>
            <tr>
              <td>55&nbsp;前十年核定虧損本年度扣除額【詳申報須知五、(八)及(九)】</td>
              <td style="text-align: center;">55</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="55"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="55_AddAdjust"></td>
            </tr> 
            <tr>
              <td>59&nbsp;課稅所得額(53-93-99-101-57-129-58-55-125+126)</td>
              <td style="text-align: center;">59</td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="59"></td>
              <td colspan="4" style="text-align:right" ><input style="width:100%;" class="check-this" id="59_AddAdjust"></td>
            </tr>
            <tr>
              <td rowspan="14" style="text-align: center;">稅額計算</td>
              <td colspan="10" rowspan="3" style="border-right-color: white;">
                60課稅所得額×稅率=本年度應納稅額(計算至元為止，角以下無條件捨去)</br>
                (1)&nbsp;<input style="width:10px;" class="check-this" id="TaxableIncome">&nbsp;元×&nbsp;<input style="width:10px;" class="check-this" id="Tax">&nbsp;%=&nbsp;<input style="width:10px;" class="check-this" id="60_1">&nbsp;</br>
                (2)營業期間不滿1年者，換算全年所得核課:〔(&nbsp;<input style="width:10px;" class="check-this" id="TaxableIncome">&nbsp;元× 12/&nbsp;<input style="width:10px;" class="check-this" id="WorkMonthOfYear">&nbsp;)×&nbsp;<input class="check-this" id="Tax_1">&nbsp;%〕×&nbsp;<input  class="check-this" id="WorkMonthOfYear">&nbsp;/12=&nbsp;<input class="check-this" id="60_2" >&nbsp;
              </td>
              <td style="border-color: white;"></td>
              <td colspan="3" style="border-color: white gray;"></td>
              <td colspan="3" style="border-color: white gray;"></td>
            </tr>
            <tr>
              <td style="border-color: white;"></td>
              <td colspan="3" style="border-color: white gray;"></td>
              <td colspan="3" style="border-color: white gray;"></td>
            </tr>
            <tr>
              <td style="border-color: white white gray white;"></td>
              <td style="border-color: white gray gray white;" colspan="3"></td>
              <td colspan="3" style="text-align: right; border-color: white gray gray white;"><input class="check-this" id="60_2_3">&nbsp;元</td>
            </tr>
            <tr>
              <td colspan="14">112&nbsp;依境外所得來源國稅法規定繳納之所得稅可扣抵之稅額(附所得稅法第3條第2項規定之納稅憑證及簽證文件)</td>
            <td colspan="3" style="text-align: right;">
              <div style="float: left;">112</div>
              <input class="check-this" id="112">&nbsp;元
            </td>
            </tr>
            <tr>
              <td colspan="14">119&nbsp;大陸地區來源所得在大陸地區及第三地區已繳納之所得稅可扣抵之稅額(附台灣地區與大陸地區人民關係條例施行細則第21條規定之納稅憑證及文件)</td>
              <td colspan="3" style="text-align: right;">
                <div style="float: left;">119</div>
                <input class="check-this" id="119">&nbsp;元
              </td>
            </tr>
            <tr>
              <td colspan="14">95&nbsp;依法律規定之投資抵減稅額，於本年度抵減之稅額(應檢附證件詳須知八)</td>
              <td colspan="3" style="text-align: right;">
                <div style="float: left;">95</div>
                <input class="check-this" id="95">&nbsp;元
              </td>
            </tr>
            <tr>
              <td colspan="14">118&nbsp;已扣抵國外所得稅額之基本稅額與一般所得稅額之差額(詳須知九、十，應填報本申報書第2頁之各欄項，並將該頁(15)欄計算結果填入本欄)</td>
              <td colspan="3" style="text-align: right;">
                <div style="float: left;">118</div>
                <input class="check-this" id="118">&nbsp;元
              </td>
            </tr>
            <tr>
              <td colspan="14">113&nbsp;行政救濟留抵稅額於本年度抵減額(應提示營利事業所得稅留抵稅額證明書正本經核驗後發還)</td>
              <td colspan="3" style="text-align: right;">
                <div style="float: left;">113</div>
                <input class="check-this" id="113">&nbsp;元
              </td>
            </tr>
            <tr>
              <td colspan="14">62&nbsp;本年度暫繳自繳稅額(含已繳納及核定未繳納稅額)</td>
              <td colspan="3" style="text-align: right;">
                <div style="float: left;">62</div>
                <input class="check-this" id="62">&nbsp;元
              </td>
            <tr>
              <td colspan="14">63&nbsp;本年度扣抵之扣繳稅額(註:分離課稅所得之扣繳稅額不得抵繳)</td>
              <td colspan="3" style="text-align: right;">
                <div style="float: left;">63</div>
                <input class="check-this" id="63">&nbsp;元
              </td>
            </tr>
            <tr>
              <td colspan="14">64&nbsp;本年度應自行向公庫補繳之營利事業所得稅額(附自繳稅額繳款書收據，60-112-119-95+118-113-62-63)</td>
              <td colspan="3" style="text-align: right;">
                <div style="float: left;">64</div>
                <input class="check-this" id="64">&nbsp;元
              </td>
            </tr>
            <tr>
              <td colspan="14">65&nbsp;半年度申請因特還之營利事業所得稅的</td>
              <td colspan="3" style="text-align: right;">
                <div style="float: left;">65</div>
                <input class="check-this" id="65">&nbsp;元
              </td>
            </tr>
            <tr>
              <td colspan="14">116&nbsp;以本年度應退稅額抵繳上年度未分配盈餘申報自繳稅額之金額(即第16頁第26項金額)</td>
              <td colspan="3" style="text-align: right;">
                <div style="float: left;">116</div>
                <input class="check-this" id="116">&nbsp;元
              </td>
            </tr>
            <tr>
              <td colspan="14">117&nbsp;以本年度應退稅額抵繳上年度未分配盈餘申報自繳稅額後應退還之稅額(65-116)</td>
              <td colspan="3" style="text-align: right;">
                <div style="float: left;">117</div>
                <input class="check-this" id="117">&nbsp;元
              </td>
            </tr>
            <tr>
              <td colspan="13" style="border-color: white;"></td>
              <td colspan="5" style="border-right-color: white;"></td>
            </tr>
            <tr>
              <td colspan="12" style="border-color:white ;"></td>
              <td style="border-bottom-color: white;"></td>
              <td rowspan="3" style="text-align: center;">
                分局</br>
                稽徵所</br>
                收件編號
              </td>
              <td colspan="4" style="text-align:right" rowspan="3" style="text-align: center;" ><input style="width:100%;height:100%;" class="check-this" id="ReceiptNumber"></td>
            </tr>
            <tr>
              <td style="border-color: white;">簽證會計師:</td>
              <td style="border-color: white;" style="text-align:center;" ><input style="width:100%;" class="check-this" id="AccountantSign"></td>
              <td colspan="11" style="border-bottom-color: white;">(蓋章)</td>       
            </tr>
            <tr>
              <td colspan="13" style="border-color: white;"></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <script>
    $(document).ready(function() {
      var urlString = window.location.href;
      var url = new URL(urlString);
      var textId = url.searchParams.get("name");
      function fetchCheck(action,textId,input_data=[]) {
          if(textId==''){
            alert('請在網址填入name');
          }else{
            $.ajax({
              type: "POST",
              url: "/admin/ocr/check",
              data: {
                'action' : action,
                'id' : textId,
                'type' : 'income_statement',
                'data' : input_data,
              },
              beforeSend: function () {
              },
              complete: function () {
              },
              success: function (response) {
                  if(action=='post'){
                    alert(response.response.msg);
                  }
                  if(action=='get'){
                    // console.log(response.response.data);
                    if(response.status.code=='200'){
                      fillData(response.response.data);
                    }else{
                      alert(response.response.msg);
                    }
                  }
                  if(action=='search'){
                    // console.log(response.response.data);
                    if(response.status.code=='200'){
                      alert('id列表：'+response.response.data);
                    }else{
                      alert(response.response.msg);
                    }
                  }
                          return;
              },
              error: function (response) {
                  alert(response.msg);
              }
            });
          }
      }

      function fillData(data) {
          Object.keys(data).forEach(function(key) {
                htmlKey = "#" + data[key]['title'];
                $(htmlKey).val(data[key]['value']);
          })
      }

      $(".input-value-btn").click(function() {
        var IDs = [];
        var data = [];
        action = $(".input-value-btn").val();

        $('.check-this').each(function () {
          data_id = this.id;
          data_input = $("#" + data_id).val();
          data.push({
              title: data_id, 
              value:  data_input
          });
        });
        fetchCheck(action,textId,data);

      });
      $(".load-value-btn").click(function() {
        action = $(".load-value-btn").val();
        fetchCheck(action,textId,data=[]);
      });
      $(".search-value-btn").click(function() {
        action = $(".search-value-btn").val();
        fetchCheck(action,textId,data=[]);
      });
    });
    </script>
  </body>
</html>

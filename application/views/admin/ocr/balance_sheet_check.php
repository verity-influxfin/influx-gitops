<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin/css/ocr/balance_sheet.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="<?=base_url()?>assets/admin/js/ocr/fetch.js"></script>
    <title>資產負債表</title>
  </head>
  <body style="text-align: center;" >
    <div class="book">
      <div class="page"style="zoom:0;overflow:auto;">
        <div class="subpage">
          <h2>資產負債表檢測模版</h2>
          <div>
            <button class="load-value-btn" type="submit" style="display:inline;"value="get">載入模版資料</button>
            <button class="input-value-btn" type="submit" style="display:inline;"value="post">提交輸入值</button>
            <button class="search-value-btn" type="submit" style="display:inline;"value="search">查詢所有模版</button>
          </div>
          <div><br>
            <input class="check-this" id="report_time" >
          </div>
          <p style="float: left;">營利事業名稱:<input class="check-this" id="name"></p>
          <table>
            <tr>
              <td rowspan="2" style="text-align: center; width: 32px;">編號</td>
              <td rowspan="2" style="text-align: center; width: 300px;">
                項目
              </td>
              <td colspan="2" style="text-align: center;">金額</td>
              <td rowspan="2" style="text-align: center; width: 32px;">編號</td>
              <td rowspan="2" style="text-align: center; width: 300px;">
                項目
              </td>
              <td colspan="2" style="text-align: center;">金額</td>
            </tr>
            <tr>
              <td style="text-align: center;">小計</td>
              <td style="text-align: center;">合計</td>
              <td style="text-align: center;">小計</td>
              <td style="text-align: center;">合計</td>
            </tr>
            <tr>
              <td style="text-align: center;">1100</td>
              <td>流動資產</td>
              <td style="text-align: right;"><input style="width:100%;" style="width:100%;" class="check-this" id="1100_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1100_T"></td>
              <td style="text-align: center;">2100</td>
              <td>流動負債</td>
              <td style="text-align: right;"><input style="width:100%;"  class="check-this" id="2100_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2100_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1111</td>
              <td>&nbsp;&nbsp;&nbsp;現金</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1111_St"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1111_T"></td>
              <td style="text-align: center;">2110</td>
              <td>&nbsp;&nbsp;&nbsp;短期借款 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2110_St"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2110_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1112</td>
              <td>&nbsp;&nbsp;&nbsp;銀行存款</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1112_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1112_T"></td>
              <td style="text-align: center;">2111</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;銀行透支 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2111_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2111_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1113</td>
              <td>&nbsp;&nbsp;&nbsp;約當現金</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1113_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1113_T"></td>
              <td style="text-align: center;">2112</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;銀行借款</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2112_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2112_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1114</td>
              <td>&nbsp;&nbsp;&nbsp;短期投資（附註二）</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1114_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1114_T"></td>
              <td style="text-align: center;">2113</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;應付短期票券</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2113_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2113_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1151</td>
              <td>&nbsp;&nbsp;&nbsp;透過損益按公允價值衡量之金融資產－流動(附註三)</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1151_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1151_T"></td>
              <td style="text-align: center;">2113</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;其他短期借款</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2119_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2119_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1154</td>
              <td>&nbsp;&nbsp;&nbsp;避險之金融資產－流動（附註三）</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1154_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1154_T"></td>
              <td style="text-align: center;">2140</td>
              <td>&nbsp;&nbsp;&nbsp;透過損益按公允價值衡量之金融負債－流動</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2140_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2140_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1158</td>
              <td>&nbsp;&nbsp;&nbsp;透過其他綜合損益按公允價值衡量之金融資產－流動 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1158_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1158_T"></td>
              <td style="text-align: center;">2150</td>
              <td>&nbsp;&nbsp;&nbsp;避險之衍生金融負債－流動</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2150_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2150_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1161</td>
              <td>&nbsp;&nbsp;&nbsp;按攤銷後成本衡量之金融資產－流動（附註三） </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1161_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1161_T"></td>
              <td style="text-align: center;">2170</td>
              <td>&nbsp;&nbsp;&nbsp;特別股負債－流動</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2170_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2170_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1157</td>
              <td>&nbsp;&nbsp;&nbsp;其他金融資產－流動（附註三）</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1157_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1157_T"></td>
              <td style="text-align: center;">2180</td>
              <td>&nbsp;&nbsp;&nbsp;其他金融負債－流動</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2180_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2180_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1159</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;減：累計減損</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1159_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1159_T"></td>
              <td style="text-align: center;">2126</td>
              <td>&nbsp;&nbsp;&nbsp;合約負債－流動</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2126_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2126_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1125</td>
              <td>&nbsp;&nbsp;&nbsp;合約資產－流動（附註三）</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1125_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1125_T"></td>
              <td style="text-align: center;">2120</td>
              <td>&nbsp;&nbsp;&nbsp;應付票據</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2120_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2120_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1126</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;減：累計減損 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1159_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1159_T"></td>
              <td style="text-align: center;">2121</td>
              <td>&nbsp;&nbsp;&nbsp;應付帳款</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2121_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2121_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1121</td>
              <td>&nbsp;&nbsp;&nbsp;應收票據 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1121_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1121_T"></td>
              <td style="text-align: center;">2130 </td>
              <td>nbsp;&nbsp;&nbsp;其他應付款</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2130_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2130_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1122</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;減：備抵呆帳 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1122_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1122_T"></td>
              <td style="text-align: center;">2131</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;應付費用 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2131_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2131_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1123</td>
              <td>&nbsp;&nbsp;&nbsp;應收帳款</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1123_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1123_T"></td>
              <td style="text-align: center;">2132</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;應付稅捐 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2132_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2132_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1124</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;減：備抵呆帳 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1124_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1124_T"></td>
              <td style="text-align: center;">2133</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;應付股利</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2133_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2133_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1129</td>
              <td>&nbsp;&nbsp;&nbsp;其他應收款 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1129_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1129_T"></td>
              <td style="text-align: center;">2129</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;銷項稅額</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2134_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2134_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1130</td>
              <td>&nbsp;&nbsp;&nbsp;存 貨 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1130_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1130_T"></td>
              <td style="text-align: center;">2135</td>
              <td>&nbsp;&nbsp;&nbsp;其他應付款－其他</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2135_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2135_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1131</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;商 品 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1131_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1131_T"></td>
              <td style="text-align: center;">2136</td>
              <td>&nbsp;&nbsp;&nbsp;預收款項 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2136_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2136_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1132</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;製成品</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1132_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1132_T"></td>
              <td style="text-align: center;">2137</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;預收貨款</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2137_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2137_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1133</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;在製品(或在建工程) </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1133_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1133_T"></td>
              <td style="text-align: center;">2138</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;其他預收款</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2138_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2138_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1134</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;原 料 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1134_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1134_T"></td>
              <td style="text-align: center;">2190</td>
              <td>&nbsp;&nbsp;&nbsp;其他流動負債 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2190_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2190_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1135</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;物 料 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1135_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1135_T"></td>
              <td style="text-align: center;">2191</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;暫收款</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2191_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2191_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1136</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;寄 銷 品 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1136_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1136_T"></td>
              <td style="text-align: center;">2192</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;業主(股東)往來</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2192_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2192_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1138</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;其 他 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1138_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1138_T"></td>
              <td style="text-align: center;">2193</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;同業往來</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2193_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2193_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1137</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;減：備抵存貨跌價損失 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1137_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1137_T"></td>
              <td style="text-align: center;">2195</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;代收款</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2195_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2195_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1140</td>
              <td>&nbsp;&nbsp;&nbsp;預付款項 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1140_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1140_T"></td>
              <td style="text-align: center;">2196</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;其他流動負債-其他 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2196_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2196_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1141</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;預付費用 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1141_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1141_T"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td style="text-align: center;">1142</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;用品盤存 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1142_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1142_T"></td>
              <td style="text-align: center;">2200</td>
              <td>非流動負債 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2200_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2200_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1143</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;預付貨款 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1143_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1143_T"></td>
              <td style="text-align: center;">2210</td>
              <td>&nbsp;&nbsp;&nbsp;應付公司債 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2210_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2210_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1144</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;進項稅額 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1144_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1144_T"></td>
              <td style="text-align: center;">2220</td>
              <td>&nbsp;&nbsp;&nbsp;長期借款</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2220_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2220_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1145</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;留抵稅額</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1145_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1145_T"></td>
              <td style="text-align: center;">2230</td>
              <td>&nbsp;&nbsp;&nbsp;透過損益按公允價值衡量之金融負債－非流動 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2230_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2230_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1149</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;其他預付款</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1149_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1149_T"></td>
              <td style="text-align: center;">2240</td>
              <td>&nbsp;&nbsp;&nbsp;避險之衍生金融負債－非流動 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2240_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2240_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1190</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;其他流動資產</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1190_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1190_T"></td>
              <td style="text-align: center;">2260</td>
              <td>&nbsp;&nbsp;&nbsp;特別股負債-非流動</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2260_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2260_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1191</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;暫 付 款 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1191_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1191_T"></td>
              <td style="text-align: center;">2270</td>
              <td>&nbsp;&nbsp;&nbsp;其他金融負債－非流動</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2270_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2270_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1192</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;業主（股東）往來 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1192_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1192_T"></td>
              <td style="text-align: center;">2280</td>
              <td>&nbsp;&nbsp;&nbsp;長期應付票據及款項</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2280_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2280_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1193</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;同業往來</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1193_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1193_T"></td>
              <td style="text-align: center;">2281</td>
              <td>&nbsp;&nbsp;&nbsp;合約負債－非流動</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2281_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2281_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1199</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;其 他</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1199_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1199_T"></td>
              <td style="text-align: center;">2900</td>
              <td>&nbsp;&nbsp;&nbsp;其他非流動負債</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2900_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2900_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1200</td>
              <td>非流動資產</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1200_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1200_T"></td>
              <td style="text-align: center;">2900</td>
              <td>&nbsp;&nbsp;&nbsp;其他非流動負債</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2900_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2900_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1300</td>
              <td>&nbsp;&nbsp;&nbsp;長期投資（附註二）</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1300_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1300_T"></td>
              <td style="text-align: center;">2910</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;存入保證金 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2910_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2910_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1302</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;減：累計減損（附註二）</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1302_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1302_T"></td>
              <td style="text-align: center;">2940</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;退休金準備</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2940_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2940_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1612</td>
              <td>&nbsp;&nbsp;&nbsp;透過損益按公允價值衡量之金融資產－非流動(附註三)</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1612_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1612_T"></td>
              <td style="text-align: center;">2951</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;國外投資損失準備 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2951_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2951_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1615</td>
              <td>&nbsp;&nbsp;&nbsp;避險之衍生金融資產－非流動（附註三）</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1615_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1615_T"></td>
              <td style="text-align: center;">2970</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;受託承銷品</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2970_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2970_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1621</td>
              <td>&nbsp;&nbsp;透過其他綜合損益按公允價值衡量之金融資產－非流動</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1621_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1621_T"></td>
              <td style="text-align: center;">2999</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;其他非流動負債-其他</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2999_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2999_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1622</td>
              <td>&nbsp;&nbsp;&nbsp;按攤銷後成本衡量之金融資產－非流動(附註三)</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1622_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1622_T"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td style="text-align: center;">1618</td>
              <td>&nbsp;&nbsp;&nbsp;其他金融資產－非流動（附註三） </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1618_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1618_T"></td>
              <td style="text-align: center;">2000</td>
              <td>負債總額 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2000_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="2000_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1630</td>
              <td>&nbsp;&nbsp;&nbsp;採用權益法之投資（附註三）</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1630_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1630_T"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td style="text-align: center;">1631</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;減：累計減損 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1631_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1631_T"></td>
              <td style="text-align: center;">3100</td>
              <td>資本或股本（實收）</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3100_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3100_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1640</td>
              <td>&nbsp;&nbsp;&nbsp;合約資產－非流動（附註三） </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1640_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1640_T"></td>
              <td style="text-align: center;">3110</td>
              <td>&nbsp;&nbsp;&nbsp;股本（登記）</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3110_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3110_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1641</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;減：累計減損（附註三）</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1641_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1641_T"></td>
              <td style="text-align: center;">3130</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;加：預收股款</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3130_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3130_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1400</td>
              <td>&nbsp;&nbsp;&nbsp;不動產、廠房、及設備(固定資產)</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1400_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1400_T"></td>
              <td style="text-align: center;">3120</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;減：未發行股本</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3120_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3120_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1410</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;土 地</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1410_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1410_T"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td style="text-align: center;">1411</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;減：累計減損 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1411_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1411_T"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td style="text-align: center;">1431</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;房屋及建築 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1431_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1431_T"></td>
              <td style="text-align: center;">3300</td>
              <td>資本公積</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3300_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3300_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1432</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;減：累計折舊 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1432_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1432_T"></td>
              <td style="text-align: center;">3400</td>
              <td>保留盈餘</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3400_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3400_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1433</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;減：累計減損</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1433_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1433_T"></td>
              <td style="text-align: center;">3410</td>
              <td>&nbsp;&nbsp;&nbsp;法定盈餘公積</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3410_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3410_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1441</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;機器設備 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1441_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1441_T"></td>
              <td style="text-align: center;">3411</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;法定盈餘公積(86年度以前餘額)</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3411_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3411_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1442</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;減：累計折舊</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1442_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1442_T"></td>
              <td style="text-align: center;">3412</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;法定盈餘公積(87年度以後餘額)</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3412_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3412_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1443</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;減：累計減損 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1443_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1443_T"></td>
              <td style="text-align: center;">3420</td>
              <td>&nbsp;&nbsp;&nbsp;特別盈餘公積</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3420_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3420_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1451</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;運輸設備 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1451_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1451_T"></td>
              <td style="text-align: center;">3421</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;特別盈餘公積(86年度以前餘額)</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3421_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3421_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1452</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;減：累計折舊</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1452_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1452_T"></td>
              <td style="text-align: center;">3422</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;特別盈餘公積(87年度以後餘額)</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3422_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3422_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1453</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;減：累計減損</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1453_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1453_T"></td>
              <td style="text-align: center;">3430</td>
              <td>&nbsp;&nbsp;&nbsp;累積盈虧</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3430_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3430_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1461</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;辦公設備</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1461_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1461_T"></td>
              <td style="text-align: center;">3431</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;累積盈虧(86年度以前餘額)</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3431_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3431_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1462</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;減：累計折舊 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1462_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1462_T"></td>
              <td style="text-align: center;">3432</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;累積盈虧(87年度以後餘額)</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3432_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3432_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1463</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;減：累計減損</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1463_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1463_T"></td>
              <td style="text-align: center;">3434</td>
              <td>&nbsp;&nbsp;&nbsp;追溯適用及追溯重編之影響數(附註六)</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3434_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3434_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1470</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;未完工程及待驗設備</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1470_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1470_T"></td>
              <td style="text-align: center;">3435</td>
              <td>&nbsp;&nbsp;&nbsp;本期自其他綜合損益或其他權益項目轉入之稅後淨額 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3435_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3435_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1491</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;其他固定資產 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1491_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1491_T"></td>
              <td style="text-align: center;">3440</td>
              <td>&nbsp;&nbsp;&nbsp;本期損益(稅後)</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3440_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3440_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1492</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;減：累計折舊 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1492_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1492_T"></td>
              <td style="text-align: center;">3450</td>
              <td>&nbsp;&nbsp;&nbsp;減:自本期盈餘分配或撥補虧損之金錢</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3450_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3450_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1493</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;減：累計減損 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1493_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1493_T"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td style="text-align: center;">1541</td>
              <td>&nbsp;&nbsp;&nbsp;投資性不動產</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1541_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1541_T"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td style="text-align: center;">1542</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;減：累計折舊</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1542_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1542_T"></td>
              <td style="text-align: center;">3500</td>
              <td>其他權益</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3500_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3500_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1421</td>
              <td>&nbsp;&nbsp;&nbsp;礦產資源</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1421_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1421_T"></td>
              <td style="text-align: center;">3502</td>
              <td>&nbsp;&nbsp;&nbsp;避險中屬有效避險部分之避險工具損益</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3502_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3502_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1422</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;減：累計折耗 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1422_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1422_T"></td>
              <td style="text-align: center;">3503</td>
              <td>&nbsp;&nbsp;&nbsp;國外營運機構財務報表換算之兌換差額</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3503_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3503_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1551</td>
              <td>&nbsp;&nbsp;&nbsp;生物資產</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1551_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1551_T"></td>
              <td style="text-align: center;">3504</td>
              <td>&nbsp;&nbsp;&nbsp;未實現重估增值</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3504_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3504_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1552</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;減：累計折舊</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1552_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1552_T"></td>
              <td style="text-align: center;">3505</td>
              <td>&nbsp;&nbsp;&nbsp;確定福利計畫再衡量數</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3505_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3505_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1553</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;減：累計減損（附註五）</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1553_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1553_T"></td>
              <td style="text-align: center;">3507</td>
              <td>&nbsp;&nbsp;&nbsp;透過其他綜合損益按公允價值衡量之未實現損益</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3507_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3507_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1510</td>
              <td>&nbsp;&nbsp;&nbsp;無形資產</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1510_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1510_T"></td>
              <td style="text-align: center;">3506</td>
              <td>&nbsp;&nbsp;&nbsp;其他權益-其他</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3506_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3506_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1511</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;減：累計攤折</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1511_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1511_T"></td>
              <td style="text-align: center;">3600</td>
              <td>減:庫藏股票</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3600_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3600_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1512</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;減：累計減損 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1512_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1512_T"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td style="text-align: center;">1900</td>
              <td>&nbsp;&nbsp;&nbsp;其他非流動資產</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1900_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1900_T"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td style="text-align: center;">1901</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;存出保證金 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1901_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1901_T"></td>
              <td style="text-align: center;">3000</td>
              <td>權益總額 </td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3000_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="3000_T"></td>
            </tr>
            <tr>
              <td style="text-align: center;">1902</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;未攤銷費用</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1902_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1902_T"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td style="text-align: center;">1903</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;預付設備款</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1903_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1903_T"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td style="text-align: center;">1904</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;其他非流動資產－其他</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1904_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1904_T"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td style="text-align: center;">1000</td>
              <td>資產總額</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1000_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="1000_T"></td>
              <td style="text-align: center;">9000</td>
              <td>負債及權益總額</td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="9000_ST"></td>
              <td style="text-align: right;"><input style="width:100%;" class="check-this" id="9000_T"></td>
            </tr>
          </table>
          <div style="height: 5px;"></div>
          <!--b style="float: left;">備註:請參閱背面附註說明</b-->
          <table>
            <tr>
              <td rowspan="3" style="text-align: center; width: 90px;">
                營利事業<br>
                統一編號
              </td>
              <td rowspan="3" style="text-align: center;"><input style="height:100%;width:100%;" class="check-this" id="taxId"></td>
              <td style="border-color: white;"></td>
              <td rowspan="3" style="width: 1px;border-top-color: white; border-bottom-color:white ;"></td>
              <td rowspan="3" style="text-align: center; width: 90px;">
                分局<br>
                稽徵所<br>
                收件編號
              </td>
              <td rowspan="3" style="text-align: center;"><input style="height:100%;width:100%;" class="check-this" id="ReceiptNumber"></td>
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
                    'type' : 'balance_sheet',
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

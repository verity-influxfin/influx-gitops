<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
	<table style="width:100%;height:100%">
		<tr>
			<td colspan="4" style="text-align:center;background-color:#00468C;color:#eee;">
				<h2>普匯金融科技 - 投資人交易對帳單</h2>
			</td>
		</tr>
		<tr>
			<td colspan="4">
			</td>
		</tr>	
		<tr>
			<td colspan="4">
				交易明細<br>
				<table style="width:100%;">
					<tr>
					  <th  style="text-align:center;border: 1px solid #000000;" rowspan="2">交易日期</th>
					  <th  style="text-align:center;border: 1px solid #000000;" rowspan="2" colspan="2">案件號碼</th>
					  <th  style="text-align:center;border: 1px solid #000000;" rowspan="2">交易項目</th>
					  <th  style="text-align:center;border: 1px solid #000000;" colspan="4">收入</th>
					  <th  style="text-align:center;border: 1px solid #000000;" colspan="2">支出</th>
					  <th  style="text-align:center;border: 1px solid #000000;" rowspan="2">虛擬帳戶餘額</th>
					  <th  style="text-align:center;border: 1px solid #000000;" rowspan="2">備註</th>
					</tr>
					<tr>
					  <th style="text-align:center;border: 1px solid #000000;" >本金</th>
					  <th style="text-align:center;border: 1px solid #000000;" >利息</th>
					  <th style="text-align:center;border: 1px solid #000000;" >延滯利息</th>
					  <th style="text-align:center;border: 1px solid #000000;" >違約補貼金</th>
					  <th style="text-align:center;border: 1px solid #000000;" >本金</th>
					  <th style="text-align:center;border: 1px solid #000000;" >服務費</th>
					</tr>
					{list}
					<tr>
						<td style="border: 1px solid #000000;" >{date}</td>
						<td style="border: 1px solid #000000;" colspan="2" >{target_no}</td>
						<td style="border: 1px solid #000000;" >{title}</td>
						<td style="text-align:right;border: 1px solid #000000;">{income_principal}</td>
						<td style="text-align:right;border: 1px solid #000000;">{income_interest}</td>
						<td style="text-align:right;border: 1px solid #000000;">{income_delay_interest}</td>
						<td style="text-align:right;border: 1px solid #000000;">{income_allowance}</td>
						<td style="text-align:right;border: 1px solid #000000;">{cost_principal}</td>
						<td style="text-align:right;border: 1px solid #000000;">{cost_fee}</td>
						<td style="text-align:right;border: 1px solid #000000;">{amount}</td>
						<td style="text-align:center;border: 1px solid #000000;">{remark}</td>
					</tr>
					{/list}
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="4">
			</td>
		</tr>	
		<tr>
			<td colspan="4" style="text-align:center;background-color:#00468C;color:#eee;">
				<h3>普匯金融科技 / 溫馨提示</h3>
			</td>
		</tr>
		<tr>
			<td colspan="4">
			</td>
		</tr>		
		<tr>
			<td colspan="2" style="text-align:center;">
				<img src="https://s3-ap-northeast-1.amazonaws.com/influxp2p/estatement/logo.png" style="width:300px">
			</td>
			<td colspan="2" style="text-align:left;">
				<p>請妥善保管密碼等個人訊息，並確保在官方途徑使用。
			  普匯無論何時都不會向您以任何形式詢問或確認您的個人資料。
			  包括金融卡帳戶號碼，提領密碼，帳戶餘額，身分證字號等。
			  感謝您對普匯的支持，我們將竭誠為您服務。
			  更多訊息請登入我的手機ATM進行查詢</p>
			  <p>Line@客服：Line@puhey 歡迎登錄</p>
			  <p>普匯網站了解更多最新消息：<a href="https://www.influxfin.com/">https://www.influxfin.com/</a></p>
			  
			</td>
		</tr>
	</table>
</body>
</html>
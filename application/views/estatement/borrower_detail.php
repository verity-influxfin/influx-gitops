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
			<td colspan="4" style="text-align:center;background-color:#2b3881;color:#eee;">
				<h2>普匯金融科技 - 借款人交易對帳單</h2>
			</td>
		</tr>
		<tr>
			<td colspan="4">
			</td>
		</tr>	
		<tr>
			<td colspan="4">
				交易明細
				<table  style="width:100%;border:2px #7fb4d5 solid;">
					<tr>
					  <th rowspan="2">交易日期</th>
					  <th rowspan="2" colspan="2">案件號碼</th>
					  <th rowspan="2">交易項目</th>
					  <th colspan="4">收入</th>
					  <th colspan="2">支出</th>
					  <th rowspan="2">虛擬帳戶餘額</th>
					  <th rowspan="2">備註</th>
					</tr>
					<tr>
					  <th>本金</th>
					  <th>利息</th>
					  <th>延滯利息</th>
					  <th>違約補貼金</th>
					  <th>違約補貼金</th>
					  <th>服務費</th>
					</tr>
					{list}
					<tr>
						<td>{date}</td>
						<td>{target_no}</td>
						<td>{title}</td>
						<td>{income_principal}</td>
						<td>{income_interest}</td>
						<td>{income_delay_interest}</td>
						<td>{income_allowance}</td>
						<td>{cost_principal}</td>
						<td>{cost_fee}</td>
						<td>{amount}</td>
						<td>{remark}</td>
					</tr>
					{/list}
				</table>
			</td>
		<tr>
		<tr>
			<td colspan="4">
			</td>
		</tr>	
		<tr>
			<td colspan="4" style="text-align:center;background-color:#2b3881;color:#eee;">
				<h3>普匯金融科技 / 溫馨提示</h3>
			</td>
		</tr>
		<tr>
			<td colspan="4">
			</td>
		</tr>		
		<tr>
			<td colspan="2" style="text-align:center;">
				<img src="https://s3-ap-northeast-1.amazonaws.com/influxp2p/estatement/06.png" style="width:300px">
			</td>
			<td colspan="2" style="text-align:left;">
				<p>請妥善保管密碼等個人訊息，並確保在官方途徑使用。
			  普匯無論何時都不會向您以任何形式詢問或確認您的個人資料。
			  包括金融卡帳戶號碼，提領密碼，帳戶餘額，身分證字號等。
			  感謝您對普匯的支持，我們將竭誠為您服務。
			  更多訊息請登入我的手機ATM進行查詢</p>
			  <p>Line@客服：Line@inFluxTW 歡迎登錄</p>
			  <p>普匯網站了解更多最新消息：<a href="https://www.influxfin.com/">https://www.influxfin.com/</a></p>
			</td>
		</tr>
	</table>
</body>
</html>
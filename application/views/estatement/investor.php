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
				<h3></h3>
			</td>
		</tr>	
		<tr>
			<td colspan="4">
				<table>
					<tr><td>日期： {edate}</td></tr>
					<tr><td>親愛的 {user_name} 您好:<br></td></tr>
				</table>
			</td>
		</tr>
		<tr style="height:100%;">
			<td>
				<table  style="border:2px #7fb4d5 solid;">
					<tr>
					  <td></td>
					</tr>
					<tr><td>·會員編號：{user_id}</td></tr>
					<tr><td>·戶名：{user_name}</td></tr>
					<tr><td>·銀行代號：(013)國泰世華</td></tr>
					<tr>
					  <td></td>
					</tr>
				</table>
			</td>
			<td>
				<table  style="border:2px #7fb4d5 solid;">
					<tr>
					  <td></td>
					</tr>
					<tr><td>·虛擬帳戶：{virtual_account}</td></tr>
					<tr><td>·虛擬帳戶可用餘額：{total}元</td></tr>
					<tr><td>·待交易帳戶餘額：{frozen}元</td></tr>
					<tr>
					  <td></td>
					</tr>
				</table>
			</td>
			<td colspan="2" rowspan="4">
				<table style="border:2px #7fb4d5 solid;height:300px;text-align:center;" >
					<tr>
					  <td></td>
					  <td></td>
					  <td></td>
					</tr>
					<tr>
					  <td style="text-align:left;">·應收本金</td>
					  <td>{ar_principal_count}筆</td>
					  <td style="text-align:right;">{ar_principal}元</td>
					</tr>
					<tr>
					  <td style="text-align:left;">·應收利息</td>
					  <td>{ar_interest_count}筆</td>
					  <td style="text-align:right;">{ar_interest}元</td>
					</tr>
					<tr>
					  <td style="text-align:left;">·應收本金(逾期)</td>
					  <td>{delay_ar_principal_count}筆</td>
					  <td style="text-align:right;">{delay_ar_principal}元</td>
					</tr>
					<tr>
					  <td style="text-align:left;">·應收利息(逾期)</td>
					  <td>{delay_ar_interest_count}筆</td>
					  <td style="text-align:right;">{delay_ar_interest}元</td>
					</tr>
					<tr>
					  <td style="text-align:left;">·應收帳款合計</td>
					  <td>{ar_total_count}筆</td>
					  <td style="text-align:right;">{ar_total}元</td>
					</tr>  
					<tr>
					  <td></td>
					  <td></td>
					  <td></td>
					</tr> 
					<tr>
					  <td></td>
					  <td></td>
					  <td></td>
					</tr>	
					<tr>
					  <td></td>
					  <td></td>
					  <td></td>
					</tr>
					<tr>
					  <td></td>
					  <td></td>
					  <td></td>
					</tr>
					<tr>
					  <td></td>
					  <td></td>
					  <td></td>
					</tr>					
				</table>
			</td>
		</tr>
		<tr><td colspan="2"></td></tr>
		<tr>
			<td colspan="2">
				<span>本期收益： {sdate} - {edate}</span>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<table style="border:2px #7fb4d5 solid; border-collapse:collapse;;text-align:center;">
					<tr>
					  <td></td><td></td><td></td>
					</tr>
				  <tr>
					<td style="text-align:left;">·利息收入</td>
					<td>{interest_count}筆</td>
					<td style="text-align:right;">{interest}</td>
				  </tr>
				  <tr>
					<td style="text-align:left;">·違約補貼金</td>
					<td>{allowance_count}筆</td>
					<td style="text-align:right;">{allowance}</td>
				  </tr> 
					<tr>
					  <td></td><td></td><td></td>
					</tr>				  
				</table>
				<br>
			</td>
		</tr>
		<tr>
			<td colspan="4">
				<h1> </h1>
			</td>
		</tr>	
		<tr>
			<td colspan="4" style="text-align:center;background-color:#00468C;color:#eee;">
				<h3>普匯金融科技 / 溫馨提示</h3>
			</td>
		</tr>
		<tr>
			<td colspan="4">
				<h3></h3>
			</td>
		</tr>		
		<tr>
			<td colspan="2" style="text-align:center;">
				<img src="<?=FRONT_CDN_URL ?>public/logo.png" style="width:300px">
			</td>
			<td colspan="2" style="text-align:left;">
				<p>請妥善保管密碼等個人訊息，並確保在官方途徑使用。
			  普匯無論何時都不會向您以任何形式詢問或確認您的個人資料。
			  包括金融卡帳戶號碼，提領密碼，帳戶餘額，身分證字號等。
			  感謝您對普匯的支持，我們將竭誠為您服務。
			  更多訊息請登入我的 普匯inFlux 進行查詢</p>
			  <p>Line@客服：Line@influxfin 歡迎登錄</p>
			  <p>普匯網站了解更多最新消息：<a href="https://www.influxfin.com/">https://www.influxfin.com/</a></p>
			  
			</td>
		</tr>
	</table>
</body>
</html>
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
				<h2>普匯金融科技 - 借款人交易對帳單</h2>
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
					<tr><td>親愛的 {user_name} 您好:<br>感謝使用普匯借款服務，以下是您的借款對帳單</td></tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="4">
			</td>
		</tr>
		<tr>
			<td>
				<p>個人資訊</p>
			</td>
			<td>
				<p>帳戶資訊</p>
			</td>
			<td colspan="2">
				<p>本期還款總覽</p>
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
					<tr><td></td></tr>
					<tr><td></td></tr>
				</table>
			</td>
			<td>
				<table  style="border:2px #7fb4d5 solid;">
					<tr>
					  <td></td>
					</tr>
					<tr><td>·帳戶餘額：{total}元</td></tr>
					<tr><td>·還款銀行：(013)國泰世華</td></tr>
					<tr><td>·還款帳戶：{virtual_account}</td></tr>
					<tr><td></td></tr>
				</table>
			</td>
			<td colspan="2">
				<table style="border:2px #7fb4d5 solid;height:300px;text-align:center;" >
					<tr>
					  <td>正常案件</td>
					  <td>逾期案件</td>
					</tr>
					<tr>
					  <td style="text-align:left;">·本期已還金額：{normal_rapay}元</td>
					  <td style="text-align:left;">·本期已還金額：{delay_rapay}元</td>
					</tr> 
					<tr>
					  <td style="text-align:left;">·本期應還金額：{normal_amount}元</td>
					  <td style="text-align:left;">·本期未還金額：{delay_amount}元</td>
					</tr> 
					<tr>
					  <td style="text-align:left;">·筆數：{normal_count}筆</td>
					  <td style="text-align:left;">·筆數：{delay_count}筆</td>
					</tr> 
					<tr>
					  <td></td>
					  <td></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="4">
			</td>
		</tr>
		<tr>
			<td>
				<p>信用總覽</p>
			</td>
			<td>
				<p>提前還款總覽</p>
			</td>
			<td colspan="2">
				<p>剩餘借款總額</p>
			</td>
		</tr>
		<tr>
			<td>
				<table  style="border:2px #7fb4d5 solid;">
					<tr>
					  <td></td>
					</tr>
					<tr><td>·信用額度：{credit_amount}元</td></tr>
					<tr><td>·已用額度：{used_credit}元</td></tr>
					<tr><td></td></tr>
				</table>
			</td>
			<td>
				<table  style="border:2px #7fb4d5 solid;">
					<tr>
					  <td></td>
					</tr>
					<tr><td>·提還總金額：{prepayment_amount} 元</td></tr>
					<tr><td>·筆數：{prepayment_count} 筆</td></tr>
					<tr><td></td></tr>
				</table>
			</td>
			<td colspan="2">
				<table style="border:2px #7fb4d5 solid;height:300px;text-align:center;" >
					<tr>
					  <td>正常案件</td>
					  <td>逾期案件</td>
					</tr>
					<tr>
					  <td style="text-align:left;">·剩餘本金總額：{ar_principal}元</td>
					  <td style="text-align:left;">·剩餘借款總額：{delay_amount} 元</td>
					</tr>
					<tr>
					  <td></td>
					  <td></td>
					</tr> 
					<tr>
					  <td></td>
					  <td></td>
					</tr>					
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
<table class="table table-striped table-bordered table-hover" border="1">
  <tbody style="text-align:start;">
	<tr>
	  <td rowspan="4">基本資訊</td>
	  <td>統一編號</td>
	  <td>
		<?php
			if($type == 'person'){
				echo isset($data['personId']) ? $data['personId'] : '-';
			}
			if($type == 'company'){
				echo isset($data['taxId']) ? $data['taxId'] : '-';
			}
	  	?>
      </td>
	</tr>
	<tr>
	  <td>截至印表時間</td>
	  <td><?= isset($data['printDatetime']) ? $data['printDatetime'] : '-'; ?></td>
	</tr>
	<tr>
	  <td>信用評分</td>
	  <td><?= isset($data['scoreComment']) ? $data['scoreComment'] : '-'; ?></td>
	</tr>
	<tr>
	  <td>信用評分原因</td>
	  <td><p><?= isset($data['commentReason']) ? $data['commentReason'] : '-'; ?></p></td>
	</tr>
	<tr>
	  <td>還款力計算</td>
	  <td colspan="2">
		<?php
		 if($type == 'person'){
		?>
			<p>長期擔保放款：<?= isset($data['longAssureMonthlyPayment']) ? (strpos($data['longAssureMonthlyPayment'], ',') === false ? number_format($data['longAssureMonthlyPayment']*1000) : $data['longAssureMonthlyPayment'] . '千元') : '-'; ?></p>
			<p>中期擔保放款：<?= isset($data['midAssureMonthlyPayment']) ? (strpos($data['midAssureMonthlyPayment'], ',') === false ? number_format($data['midAssureMonthlyPayment']*1000) : $data['midAssureMonthlyPayment'] . '千元') : '-'; ?></p>
			<p>長期放款：<?= isset($data['longMonthlyPayment']) ? (strpos($data['longMonthlyPayment'], ',') === false ? number_format($data['longMonthlyPayment']*1000) : $data['longMonthlyPayment'] . '千元') : '-'; ?></p>
			<p>中期放款：<?= isset($data['midMonthlyPayment']) ? (strpos($data['midMonthlyPayment'], ',') === false ? number_format($data['midMonthlyPayment']*1000) : $data['midMonthlyPayment'] . '千元') : '-'; ?></p>
			<p>短期放款：<?= isset($data['shortMonthlyPayment']) ? (strpos($data['shortMonthlyPayment'], ',') === false ? number_format($data['shortMonthlyPayment']*1000) : $data['shortMonthlyPayment'] . '千元') : '-'; ?></p>
			<p>助學貸款月繳：<?= isset($data['studentLoansMonthlyPayment']) ? (strpos($data['studentLoansMonthlyPayment'], ',') === false ? number_format($data['studentLoansMonthlyPayment']*1000) : $data['studentLoansMonthlyPayment'] . '千元') : '-'; ?></p>
			<p>信用卡月繳：<?= isset($data['creditCardMonthlyPayment']) ? (strpos($data['creditCardMonthlyPayment'], ',') === false ? number_format($data['creditCardMonthlyPayment']*1000) : $data['creditCardMonthlyPayment'] . '千元') : '-'; ?></p>
			<p>總共月繳：<?= isset($data['totalMonthlyPayment']) ? (strpos($data['totalMonthlyPayment'], ',') === false ? number_format($data['totalMonthlyPayment']*1000) : $data['totalMonthlyPayment'] . '千元') : '-'; ?></p>
			<p>是否小於投保薪資：<?= isset($data['monthly_repayment_enough']) ? $data['monthly_repayment_enough'] : '-'; ?></p>
			<p>
				<span>投保薪資：<?= isset($data['monthly_repayment']) ? (strpos($data['monthly_repayment'], ',') === false ? number_format($data['monthly_repayment']*1000) : $data['monthly_repayment']. '千元') : '-'; ?></span>；
				<span>總共月繳：<?= isset($data['totalMonthlyPayment']) ? (strpos($data['totalMonthlyPayment'], ',') === false ? number_format($data['totalMonthlyPayment']*1000) : $data['totalMonthlyPayment'] . '千元') : '-'; ?></span>
			</p>
			<p>是否小於薪資22倍：<?= isset($data['total_repayment_enough']) ? $data['total_repayment_enough'] : '-'; ?></p>
			<p>
				<span>薪資22倍：<?= isset($data['total_repayment']) ? (strpos($data['total_repayment'], ',') === false ? number_format($data['total_repayment']*1000) : $data['total_repayment'] . '千元') : '-'; ?></span>；
				<span>借款總餘額：<?= isset($data['liabilitiesWithoutAssureTotalAmount']) && is_numeric($data['liabilitiesWithoutAssureTotalAmount']) ? (strpos($data['liabilitiesWithoutAssureTotalAmount'], ',') === false ? number_format($data['liabilitiesWithoutAssureTotalAmount']) : $data['liabilitiesWithoutAssureTotalAmount']) : '-'; ?></span>
			</p>
			<p>
				<span>
					負債比計算：<?= isset($data['debt_to_equity_ratio']) ? $data['debt_to_equity_ratio'] : '-'; ?>%</span>
				</span>
			</p>
		<?php
		 }
		?>
	  </td>
	</tr>
  </tbody>
</table>

<table class="table table-striped table-bordered table-hover" border="1">
  <tbody style="text-align:start;">
	<tr>
	  <td>類別</td>
	  <td>信用資訊項目</td>
	  <td>有/無信用資訊</td>
	  <td>內容</td>
	  <td>訊息</td>
	</tr>
	<tr>
	  <td rowspan="6">一、借款資訊</td>
	  <td rowspan="4">1.借款總餘額資訊</td>
	  <td rowspan="4"><?= isset($data['liabilities_totalAmount']) && is_numeric($data['liabilities_totalAmount']) ? (strpos($data['liabilities_totalAmount'], ',') === false ? number_format($data['liabilities_totalAmount']) : $data['liabilities_totalAmount']) : '-'; ?></td>
	  <td>總覽</td>
	  <td>
		<p>有無遲延還款：<?= isset($data['repaymentDelay']) ? $data['repaymentDelay']: '-'; ?></p>
		<p>借款家數：<?= isset($data['bankCount']) ? $data['bankCount']: '-'; ?>家</p>
		<p>助學貸款筆數：<?= isset($data['totalAmountStudentLoansCount']) ? $data['totalAmountStudentLoansCount']: '-'; ?>筆</p>
		<p>短期筆數：<?= isset($data['totalAmountShortCount']) ? $data['totalAmountShortCount']: '-'; ?>筆</p>
		<p>中期筆數：<?= isset($data['totalAmountMidCount']) ? $data['totalAmountMidCount']: '-'; ?>筆</p>
		<p>長期筆數：<?= isset($data['totalAmountLongCount']) ? $data['totalAmountLongCount']: '-'; ?>筆</p>
		<p>短期擔保筆數：<?= isset($data['totalAmountShortAssureCount']) ? $data['totalAmountShortAssureCount']: '-'; ?>筆</p>
		<p>中期擔保筆數：<?= isset($data['totalAmountMidAssureCount']) ? $data['totalAmountMidAssureCount']: '-'; ?>筆</p>
		<p>長期擔保筆數：<?= isset($data['totalAmountLongAssureCount']) ? $data['totalAmountLongAssureCount']: '-'; ?>筆</p>
		<p>信用卡筆數：<?= isset($data['totalAmountCreditCardCount']) ? $data['totalAmountCreditCardCount']: '-'; ?>筆</p>
		<p>現金卡筆數：<?= isset($data['totalAmountCashCount']) ? $data['totalAmountCashCount']: '-'; ?>筆</p>
	  </td>
	</tr>
	<tr>
	  <td>訂約金額</td>
	  <td>
		<p>訂約金額額度總額：<?= isset($data['totalAmountQuota']) ? (strpos($data['totalAmountQuota'], ',') === false ? number_format($data['totalAmountQuota']*1000) : $data['totalAmountQuota'] . '') : '-'; ?></p>
		<p>助學貸款訂約金額：<?= isset($data['totalAmountStudentLoans']) ? (strpos($data['totalAmountStudentLoans'], ',') === false ? number_format($data['totalAmountStudentLoans']*1000) : $data['totalAmountStudentLoans'] . '(千元)') : '-'; ?></p>
		<p>短期訂約金額：<?= isset($data['totalAmountShort']) ? (strpos($data['totalAmountShort'], ',') === false ? number_format($data['totalAmountShort']*1000) : $data['totalAmountShort'] . '(千元)') : '-'; ?></p>
		<p>中期訂約金額：<?= isset($data['totalAmountMid']) ? (strpos($data['totalAmountMid'], ',') === false ? number_format($data['totalAmountMid']*1000) : $data['totalAmountMid'] . '(千元)') : '-'; ?></p>
		<p>長期訂約金額：<?= isset($data['totalAmountLong']) ? (strpos($data['totalAmountLong'], ',') === false ? number_format($data['totalAmountLong']*1000) : $data['totalAmountLong'] . '(千元)') : '-'; ?></p>
		<p>短期擔保訂約金額：<?= isset($data['totalAmountShortAssure']) ? (strpos($data['totalAmountShortAssure'], ',') === false ? number_format($data['totalAmountShortAssure']*1000) : $data['totalAmountShortAssure'] . '(千元)') : '-'; ?></p>
		<p>中期擔保訂約金額：<?= isset($data['totalAmountMidAssure']) ? (strpos($data['totalAmountMidAssure'], ',') === false ? number_format($data['totalAmountMidAssure']*1000) : $data['totalAmountMidAssure'] . '(千元)') : '-'; ?></p>
		<p>長期擔保訂約金額：<?= isset($data['totalAmountLongAssure']) ? (strpos($data['totalAmountLongAssure'], ',') === false ? number_format($data['totalAmountLongAssure']*1000) : $data['totalAmountLongAssure'] . '(千元)') : '-'; ?></p>
		<p>信用卡訂約金額：<?= isset($data['totalAmountCreditCard']) ? (strpos($data['totalAmountCreditCard'], ',') === false ? number_format($data['totalAmountCreditCard']*1000) : $data['totalAmountCreditCard'] . '(千元)') : '-'; ?></p>
		<p>現金卡訂約金額：<?= isset($data['totalAmountCash']) ? (strpos($data['totalAmountCash'], ',') === false ? number_format($data['totalAmountCash']*1000) : $data['totalAmountCash'] . '(千元)') : '-'; ?></p>
	  </td>
	</tr>
	<tr>
	  <td>借款餘額</td>
	  <td>
		<p>借款餘額額度總額：<?= isset($data['balanceQuota']) ? (strpos($data['balanceQuota'], ',') === false ? number_format($data['balanceQuota']*1000) : $data['balanceQuota'] . '(千元)') : '-'; ?></p>
		<p>助學貸款借款金額：<?= isset($data['balanceStudentLoans']) ? (strpos($data['balanceStudentLoans'], ',') === false ? number_format($data['balanceStudentLoans']*1000) : $data['balanceStudentLoans'] . '(千元)') : '-'; ?></p>
		<p>短期借款金額：<?= isset($data['balanceShort']) ? (strpos($data['balanceShort'], ',') === false ? number_format($data['balanceShort']*1000) : $data['balanceShort'] . '(千元)') : '-'; ?></p>
		<p>中期借款金額：<?= isset($data['balanceMid']) ? (strpos($data['balanceMid'], ',') === false ? number_format($data['balanceMid']*1000) : $data['balanceMid'] . '(千元)') : '-'; ?></p>
		<p>長期借款金額：<?= isset($data['balanceLong']) ? (strpos($data['balanceLong'], ',') === false ? number_format($data['balanceLong']*1000) : $data['balanceLong'] . '(千元)') : '-'; ?></p>
		<p>短期擔保借款金額：<?= isset($data['balanceShortAssure']) ? (strpos($data['balanceShortAssure'], ',') === false ? number_format($data['balanceShortAssure']*1000) : $data['balanceShortAssure'] . '(千元)') : '-'; ?></p>
		<p>中期擔保借款金額：<?= isset($data['balanceMidAssure']) ? (strpos($data['balanceMidAssure'], ',') === false ? number_format($data['balanceMidAssure']*1000) : $data['balanceMidAssure'] . '(千元)') : '-'; ?></p>
		<p>長期擔保借款金額：<?= isset($data['balanceLongAssure']) ? (strpos($data['balanceLongAssure'], ',') === false ? number_format($data['balanceLongAssure']*1000) : $data['balanceLongAssure'] . '(千元)') : '-'; ?></p>
		<p>信用卡借款餘額：<?= isset($data['balanceCreditCard']) ? (strpos($data['balanceCreditCard'], ',') === false ? number_format($data['balanceCreditCard']*1000) : $data['balanceCreditCard'] . '(千元)') : '-'; ?></p>
		<p>現金卡借款餘額：<?= isset($data['balanceCash']) ? (strpos($data['balanceCash'], ',') === false ? number_format($data['balanceCash']*1000) : $data['balanceCash'] . '(千元)') : '-'; ?></p>
	  </td>
	</tr>
	<tr>
	  <td>信用貸款(信貸)額度動用率</td>
	  <td>
		<p>(短放+中放+長放)總餘額/(短放+中放+長放)總額度：<?= isset($data['creditUtilizationRate']) ? round($data['creditUtilizationRate'], 2) : '-'; ?>%</p>
	  </td>
	</tr>
	<tr>
	  <td>2.共同債務/從債務/其他債務資訊</td>
	  <td colspan="3"><?= isset($data['liabilities_metaInfo']) ? $data['liabilities_metaInfo'] : '-'; ?></td>
	</tr>
	<tr>
	  <td>3.借款逾期、催收或呆帳紀錄</td>
	  <td colspan="3"><?= isset($data['liabilities_badDebtInfo']) ? $data['liabilities_badDebtInfo'] : '-'; ?></td>
	</tr>
	<tr>
	  <td rowspan="2">二、信用卡資訊</td>
	  <td>1.信用卡持卡紀錄</td>
	  <td><?= isset($data['creditCard_cardInfo']) ? $data['creditCard_cardInfo'] : '-'; ?></td>
	  <td>總覽</td>
	  <td>
		<p>信用卡使用中張數：<?= isset($data['creditCardCount']) ? $data['creditCardCount'] : '-'; ?>張</p>
		<p>信用卡是否有額度：
			<?php
			 	if(isset($data['creditCardCount']) && is_numeric($data['creditCardCount'])){
					if($data['creditCardCount'] == 0){
						echo '無';
					}else{
						echo '有';
					}
				}else{
					echo '-';
				}
			?>
		</p>
	  </td>
	</tr>
	<tr>
	  <td>2.信用卡帳款總餘額資訊</td>
	  <td><?= isset($data['creditCard_totalAmount']) ? $data['creditCard_totalAmount'] : '-'; ?></td>
	  <td>總覽</td>
	  <td>
		<p>信用紀錄幾個月：<?= isset($data['creditLogCount']) ? $data['creditLogCount'] : '-'; ?></p>
		<p>近一個月信用卡使用率：<?= isset($data['creditCardUseRate']) ? $data['creditCardUseRate'] : '-'; ?>%</p>
		<p>是否有預借現金：<?= isset($data['cashAdvanced']) ? $data['cashAdvanced'] : '-'; ?></p>
		<p>延遲未滿一個月次數：<?= isset($data['delayLessMonth']) ? $data['delayLessMonth'] : '-'; ?></p>
		<p>延遲超過一個月次數：<?= isset($data['delayMoreMonth']) ? $data['delayMoreMonth'] : '-'; ?></p>
		<p>催收、呆帳紀錄：<?= isset($data['creditCardHasBadDebt']) ? $data['creditCardHasBadDebt'] : '-'; ?></p>
	  </td>
	</tr>
	<tr>
	  <td rowspan="2">三、票信資訊</td>
	  <td>1.大額存款不足退票資訊</td>
	  <td colspan="3"><?= isset($data['checkingAccount_largeAmount']) ? $data['checkingAccount_largeAmount'] : '-'; ?></td>
	</tr>
	<tr>
	  <td>2.票據拒絕往來資訊</td>
	  <td colspan="3"><?= isset($data['checkingAccount_rejectInfo']) ? $data['checkingAccount_rejectInfo'] : '-'; ?></td>
	</tr>
	<tr>
	  <td rowspan="2">四、查詢資訊</td>
	  <td>1.被查詢記錄</td>
	  <td><?= isset($data['queryLog_queriedLog']) ? $data['queryLog_queriedLog'] : '-'; ?></td>
	  <td>總覽</td>
	  <td>
		<p>被電子支付或電子票證發行機構查詢紀錄：<?= isset($data['S1Count']) ? $data['S1Count'] : '-'; ?></p>
	  </td>
	</tr>
	<tr>
	  <td>2.當事人查詢查詢信用報告紀錄</td>
	  <td><?= isset($data['queryLog_applierSelfQueriedLog']) ? $data['queryLog_applierSelfQueriedLog'] : '-'; ?></td>
	  <td>總覽</td>
	  <td>
		<p>當事人查詢紀錄：<?= isset($data['S2Count']) ? $data['S2Count'] : '-'; ?></p>
	  </td>
	</tr>
	<tr>
	  <td rowspan="4">五、其他</td>
	  <td>1.附加訊息資訊</td>
	  <td colspan="3"><?= isset($data['other_extraInfo']) ? $data['other_extraInfo'] : '-'; ?></td>
	</tr>
	<tr>
	  <td>2.主債務債權轉讓及清償資訊</td>
	  <td colspan="3"><?= isset($data['other_mainInfo']) ? $data['other_mainInfo'] : '-'; ?></td>
	</tr>
	<tr>
	  <td>3.共同債務/從債務/其他債務轉讓資訊</td>
	  <td colspan="3"><?= isset($data['other_metaInfo']) ? $data['other_metaInfo'] : '-'; ?></td>
	</tr>
	<tr>
	  <td>4.信用卡債權轉讓及清償資訊</td>
	  <td colspan="3"><?= isset($data['other_creditCardTransferInfo']) ? $data['other_creditCardTransferInfo'] : '-'; ?></td>
	</tr>
  </tbody>
</table>

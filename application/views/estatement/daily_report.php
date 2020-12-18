<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
	<style>
	tr{
		line-height: 20px;
	}
	.color{
		background-color: #DCDCDC;
	}
	</style>
	<h1>虛擬帳戶交易明細表</h1>
	<h5>選擇範圍：<?=$sdate.' - '.$edate;?> 列印日期：<?=date("Y-m-d H:i:s");?></h5>
	<table border="1" style="text-align:center;width:100%">
		<tbody>
			<tr style="text-align:center;width:100%;height:100%;line-height: 22px;">
				<th width="60" rowspan="2">交易日期</th>
				<th width="100" rowspan="2">案件號碼</th>
				<th rowspan="2">交易種類</th>
				<th width="380" colspan="5">提出</th>
				<th width="380" colspan="5">存入</th>
				<th width="60" rowspan="2">本金金額</th>
				<th width="60" rowspan="2">利息金額</th>
				<th width="60" rowspan="2">平台服務費</th>
				<th width="60" rowspan="2">違約金</th>
				<th width="70" rowspan="2">提還補貼金</th>
				<th width="60" rowspan="2">延滯息</th>
				<th width="60" rowspan="2">債權差額</th>
			</tr>
			<tr>
				<th width="60">戶名</th>
				<th width="100">虛擬帳戶</th>
				<th width="60">金額</th>
				<th width="100">銀行帳戶</th>
				<th width="60">金額</th>
				<th width="60">戶名</th>
				<th width="100">虛擬帳戶</th>
				<th width="60">金額</th>
				<th width="100">銀行帳戶</th>
				<th width="60">金額</th>
			</tr>

		<?php
			$sum = array(
				"v_amount_from"		=> 0,
				"amount_from"		=> 0,
				"v_amount_to"		=> 0,
				"amount_to"			=> 0,
				"principal"			=> 0,
				"interest"			=> 0,
				"platform_fee"		=> 0,
				"damages"			=> 0,
				"allowance"			=> 0,
				"delay_interest"	=> 0,
				"else"				=> 0,
			);
			if(isset($list) && !empty($list)){
				$count = 0;
				foreach($list as $key => $value){
					$sum["v_amount_from"]	+= isset($value["v_amount_from"])&&$value["v_amount_from"]?$value["v_amount_from"]:0;
					$sum["amount_from"]	+= isset($value["amount_from"])&&$value["amount_from"]?$value["amount_from"]:0;
					$sum["v_amount_to"]	+= isset($value["v_amount_to"])&&$value["v_amount_to"]?$value["v_amount_to"]:0;
					$sum["amount_to"]	+= isset($value["amount_to"])&&$value["amount_to"]?$value["amount_to"]:0;
					$sum["principal"]	+= isset($value["principal"])&&$value["principal"]?$value["principal"]:0;
					$sum["interest"]	+= isset($value["interest"])&&$value["interest"]?$value["interest"]:0;
					$sum["platform_fee"]+= isset($value["platform_fee"])&&$value["platform_fee"]?$value["platform_fee"]:0;
					$sum["damages"]		+= isset($value["damages"])&&$value["damages"]?$value["damages"]:0;
					$sum["allowance"]	+= isset($value["allowance"])&&$value["allowance"]?$value["allowance"]:0;
					$sum["delay_interest"]	+= isset($value["delay_interest"])&&$value["delay_interest"]?$value["delay_interest"]:0;
					$sum["else"]		+= isset($value["else"])&&$value["else"]?$value["else"]:0;
					$count++;
		?>
			<tr <?=$count%2==1?'class="color"':''; ?>>
				<td align='center' valign="middle" rowspan="<?=isset($value["sub_list"])?count($value["sub_list"])+1:""?>"><?=isset($value["entering_date"])?$value["entering_date"]:"" ?></td>
				<td align='center' valign="middle" rowspan="<?=isset($value["sub_list"])?count($value["sub_list"])+1:""?>"><?=isset($value["target_no"])?$value["target_no"]:"" ?></td>
				<td align='center' valign="middle" rowspan="<?=isset($value["sub_list"])?count($value["sub_list"])+1:""?>"><?=isset($transaction_type_name[$value["source_type"]])?$transaction_type_name[$value["source_type"]]:"" ?></td>
				<td><?=isset($value["user_from"])?$value["user_from"]:"" ?></td>
				<td><?=isset($value["v_bank_account_from"])?$value["v_bank_account_from"]:"" ?></td>
				<td class="num"><?=isset($value["v_amount_from"])&&$value["v_amount_from"]?number_format($value["v_amount_from"]):"" ?></td>
				<td><?=isset($value["bank_account_from"])?$value["bank_account_from"]:"" ?></td>
				<td class="num"><?=isset($value["amount_from"])&&$value["amount_from"]?number_format($value["amount_from"]):"" ?></td>
				<td><?=isset($value["user_to"])?$value["user_to"]:"" ?></td>
				<td><?=isset($value["v_bank_account_to"])?$value["v_bank_account_to"]:"" ?></td>
				<td class="num"><?=isset($value["v_amount_to"])&&$value["v_amount_to"]?number_format($value["v_amount_to"]):"" ?></td>
				<td><?=isset($value["bank_account_to"])?$value["bank_account_to"]:"" ?></td>
				<td class="num"><?=isset($value["amount_to"])&&$value["amount_to"]?number_format($value["amount_to"]):"" ?></td>
				<td class="num"><?=isset($value["principal"])&&$value["principal"]?number_format($value["principal"]):"" ?></td>
				<td class="num"><?=isset($value["interest"])&&$value["interest"]?number_format($value["interest"]):"" ?></td>
				<td class="num"><?=isset($value["platform_fee"])&&$value["platform_fee"]?number_format($value["platform_fee"]):"" ?></td>
				<td class="num"><?=isset($value["damages"])&&$value["damages"]?number_format($value["damages"]):"" ?></td>
				<td class="num"><?=isset($value["allowance"])&&$value["allowance"]?number_format($value["allowance"]):"" ?></td>
				<td class="num"><?=isset($value["delay_interest"])&&$value["delay_interest"]?number_format($value["delay_interest"]):"" ?></td>
				<td class="num"><?=isset($value["else"])&&$value["else"]?number_format($value["else"]):"" ?></td>
			</tr>
				<?php
					if(isset($value["sub_list"]) && !empty($value["sub_list"])){
						foreach($value["sub_list"] as $k => $v){
							$sum["v_amount_from"]	+= isset($v["v_amount_from"])&&$v["v_amount_from"]?$v["v_amount_from"]:0;
							$sum["amount_from"]	+= isset($v["amount_from"])&&$v["amount_from"]?$v["amount_from"]:0;
							$sum["v_amount_to"]	+= isset($v["v_amount_to"])&&$v["v_amount_to"]?$v["v_amount_to"]:0;
							$sum["amount_to"]	+= isset($v["amount_to"])&&$v["amount_to"]?$v["amount_to"]:0;
							$sum["principal"]	+= isset($v["principal"])&&$v["principal"]?$v["principal"]:0;
							$sum["interest"]	+= isset($v["interest"])&&$v["interest"]?$v["interest"]:0;
							$sum["platform_fee"]+= isset($v["platform_fee"])&&$v["platform_fee"]?$v["platform_fee"]:0;
							$sum["damages"]		+= isset($v["damages"])&&$v["damages"]?$v["damages"]:0;
							$sum["allowance"]	+= isset($v["allowance"])&&$v["allowance"]?$v["allowance"]:0;
							$sum["delay_interest"]	+= isset($v["delay_interest"])&&$v["delay_interest"]?$v["delay_interest"]:0;
							$sum["else"]		+= isset($v["else"])&&$v["else"]?$v["else"]:0;
				?>
				<tr <?=$count%2==1?'class="color"':''; ?> >
					<td><?=isset($v["user_from"])?$v["user_from"]:"" ?></td>
					<td><?=isset($v["v_bank_account_from"])?$v["v_bank_account_from"]:"" ?></td>
					<td class="num"><?=isset($v["v_amount_from"])&&$v["v_amount_from"]?number_format($v["v_amount_from"]):"" ?></td>
					<td><?=isset($v["bank_account_from"])?$v["bank_account_from"]:"" ?></td>
					<td class="num"><?=isset($v["amount_from"])&&$v["amount_from"]?number_format($v["amount_from"]):"" ?></td>
					<td><?=isset($v["user_to"])?$v["user_to"]:"" ?></td>
					<td><?=isset($v["v_bank_account_to"])?$v["v_bank_account_to"]:"" ?></td>
					<td class="num"><?=isset($v["v_amount_to"])&&$v["v_amount_to"]?number_format($v["v_amount_to"]):"" ?></td>
					<td><?=isset($v["bank_account_to"])?$v["bank_account_to"]:"" ?></td>
					<td class="num"><?=isset($v["amount_to"])&&$v["amount_to"]?number_format($v["amount_to"]):"" ?></td>
					<td class="num"><?=isset($v["principal"])&&$v["principal"]?number_format($v["principal"]):"" ?></td>
					<td class="num"><?=isset($v["interest"])&&$v["interest"]?number_format($v["interest"]):"" ?></td>
					<td class="num"><?=isset($v["platform_fee"])&&$v["platform_fee"]?number_format($v["platform_fee"]):"" ?></td>
					<td class="num"><?=isset($v["damages"])&&$v["damages"]?number_format($v["damages"]):"" ?></td>
					<td class="num"><?=isset($v["allowance"])&&$v["allowance"]?number_format($v["allowance"]):"" ?></td>
					<td class="num"><?=isset($v["delay_interest"])&&$v["delay_interest"]?number_format($v["delay_interest"]):"" ?></td>
					<td class="num"><?=isset($v["else"])&&$v["else"]?number_format($v["else"]):"" ?></td>
				</tr>
				<?php
					}}
				?>
		<?php
			}}
		?>
			<tr class="color">
				<td>合計</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td class="num"><?=isset($sum["v_amount_from"])?number_format($sum["v_amount_from"]):"" ?></td>
				<td></td>
				<td class="num"><?=isset($sum["amount_from"])?number_format($sum["amount_from"]):"" ?></td>
				<td></td>
				<td></td>
				<td class="num"><?=isset($sum["v_amount_to"])?number_format($sum["v_amount_to"]):"" ?></td>
				<td></td>
				<td class="num"><?=isset($sum["amount_to"])?number_format($sum["amount_to"]):"" ?></td>
				<td class="num"><?=isset($sum["principal"])?number_format($sum["principal"]):"" ?></td>
				<td class="num"><?=isset($sum["interest"])?number_format($sum["interest"]):"" ?></td>
				<td class="num"><?=isset($sum["platform_fee"])?number_format($sum["platform_fee"]):"" ?></td>
				<td class="num"><?=isset($sum["damages"])?number_format($sum["damages"]):"" ?></td>
				<td class="num"><?=isset($sum["allowance"])?number_format($sum["allowance"]):"" ?></td>
				<td class="num"><?=isset($sum["delay_interest"])?number_format($sum["delay_interest"]):"" ?></td>
				<td class="num"><?=isset($sum["else"])?number_format($sum["else"]):"" ?></td>
			</tr>
		</tbody>
	</table>
</body>
</html>

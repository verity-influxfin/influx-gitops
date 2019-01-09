
	<script>
	
		function form_onsubmit(){
			return true;
		}
	</script>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">虛擬帳戶明細</h1>
					
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							<table>
								<? if ($virtual_account->virtual_account == PLATFORM_VIRTUAL_ACCOUNT){?>
								<tr>
									<td>虛擬帳戶：：</td>
									<td><?=PLATFORM_VIRTUAL_ACCOUNT ?></td>	
								</tr>
								<tr>
									<td>戶名：</td>
									<td>平台虛擬帳號</td>
									<td><?=isset($virtual_account->investor)?$investor_list[$virtual_account->investor]:'' ?></td>
								</tr>
								<? }else{ ?>
								<tr>
									<td>虛擬帳戶：：</td>
									<td><?=isset($virtual_account->virtual_account)?$virtual_account->virtual_account:'' ?></td>
								</tr>
								<tr>	
									<td>戶名：</td>
									<td><?=isset($user_info->name)?$user_info->name:'' ?></td>
									<td><?=isset($virtual_account->investor)?$investor_list[$virtual_account->investor]:'' ?></td>
								</tr>
								<? } ?>
							</table>
                        </div>
                        <div class="panel-body">
                            <div class="row">
								<div class="col-lg-6 meta">
									<div class="table-responsive">
										<table class="table table-bordered table-hover" style="text-align:center;">
											<tbody>
											<tr style="background-color:#f5f5f5;">
												<td colspan="6">交易明細</td>
											</tr>
											<tr>
												<td>交易時間</td>
												<td>收入</td>
												<td>支出</td>
												<td>餘額</td>
												<td>備註</td>
												<td>案件ID</td>
											</tr>
											<? if(!empty($list)){
												foreach($list as $key => $value){
													$value["remark"] = json_decode($value["remark"],TRUE);
											?>

												<tr>
													<td><?=$value["tx_datetime"] ?></td>
													<td><?=$value["action"]=="debit"?$value["amount"]:""; ?></td>
													<td><?=$value["action"]=="credit"?$value["amount"]:""; ?></td>
													<td><?=$value["bank_amount"] ?></td>
													<td><?=isset($value["remark"]["source"])?$transaction_source[$value["remark"]["source"]]:"" ?></td>
													<td><?=isset($value["remark"]["target_id"])&&$value["remark"]["target_id"]?$value["remark"]["target_id"]:"" ?></td>
												</tr>
											<? }} ?>
											</tbody>
										</table>
									</div>
								</div>
								<div class="col-lg-6 meta">
									<div class="table-responsive">
										<table class="table table-bordered table-hover" style="text-align:center;">
											<tbody>
											<tr style="background-color:#f5f5f5;">
												<td colspan="5">待交易紀錄</td>
											</tr>
											<tr>
												<td>交易時間</td>
												<td>流水號</td>
												<td>金額</td>
												<td>狀態</td>
												<td>備註</td>
											</tr>
											<? if(!empty($frozen_list)){
												foreach($frozen_list as $key => $value){
											?>
												<tr>
													<td><?=$value->tx_datetime ?></td>
													<td><?=$value->id ?></td>
													<td><?=$value->amount ?></td>
													<td style="color:<?=$value->status?"red":"green";?>"><?=$frozen_status[$value->status] ?></td>
													<td><?=$frozen_type[$value->type] ?></td>
												</tr>
											<? }} ?>
											</tbody>
										</table>
									</div>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

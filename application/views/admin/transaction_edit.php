
	<script>
	
		function form_onsubmit(){
			return true;
		}
	</script>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">科目明細 - <?=$info->target_no ?></h1>
					
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							科目明細 - <?=$info->target_no ?>
                        </div>
                        <div class="panel-body">
                            <div class="row">
								<? if(!empty($list)){
									foreach($list as $investment_id => $instalment_list){
								?>
								<div class="col-lg-12">
									<div class="table-responsive">
										<table class="table table-bordered table-hover" style="text-align:center;">
											<tbody>
											<tr style="background-color:#f5f5f5;">
												<td colspan="12">交易明細 - <?=$investment_id?"出借號 ： ".$investment_id:"其他交易"; ?></td>
											</tr>
											<tr style="background-color:#f5f5f5;">
												<td>流水號</td>
												<td>交易日</td>
												<th>貸放期間</th>
												<td>科目</td>
												<td>金額</td>
												<td>User From</td>
												<td>Account From</td>
												<td>User To</td>
												<td>Account To</td>
												<td>付款期限</td>
												<td>狀態</td>
												<td>入帳狀態</td>
											</tr>
											<? 
												foreach($instalment_list as $instalment_no => $value){
													foreach($value as $k => $v){
											?>
												<tr style="<?=$instalment_no%2==0?'background-color:#f5f5f5;':"" ?><?=$v->status==0?"color:gray;":""?>">
													<td><?=$v->id ?></td>
													<td><?=$v->entering_date ?></td>
													<td><?=$v->instalment_no?$v->instalment_no:"期初" ?></td>
													<td><?=isset($v->source)?$transaction_source[$v->source]:"" ?></td>
													<td><?=$v->amount ?></td>
													<td>
													<?=$v->user_from==$info->user_id?"借款人 : ":"" ?>
													<?=$v->user_from!=$info->user_id&&$v->user_from?"出借端 : ":"" ?>
													<?=$v->user_from?$v->user_from:"平台" ?>
													</td>
													<td><?=$v->bank_account_from ?></td>
													<td>
														<?=$v->user_to==$info->user_id?"借款人 : ":"" ?>
														<?=$v->user_to!=$info->user_id&&$v->user_to?"出借端 : ":"" ?>
														<?=$v->user_to?$v->user_to:"平台" ?>
													</td>
													<td><?=$v->bank_account_to ?></td>
													<td><?=$v->limit_date ?></td>
													<td style="<?=$v->status==2?"color:green":""?>">
													<?=isset($v->status)?$status_list[$v->status]:"" ?>
													</td>
													<td><?=isset($v->passbook_status)?$passbook_status_list[$v->passbook_status]:"" ?></td>
												</tr>
												<? }} ?>
											</tbody>
										</table>
									</div>
								</div>
								<? }} ?>
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

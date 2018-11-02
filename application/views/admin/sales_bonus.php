
		<script type="text/javascript">
			function showChang(){
				var sdate 				= $('#sdate').val();
				var edate 				= $('#edate').val();
				top.location = './bonus_report?sdate='+sdate+'&edate='+edate;
			}
		</script>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">業務獎金報表</h1>
					
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							<table>
								<tr>
									<td>放款日期區間：</td>
									<td><input type="text" value="<?=$sdate ?>" id="sdate" data-toggle="datepicker"  /></td>
									<td>-</td>
									<td><input type="text" value="<?=$edate ?>" id="edate" data-toggle="datepicker" /></td>
									<td><a href="javascript:void(0)" onclick="showChang();" class="btn btn-default float-right btn-md" >查詢</a></td>
								</tr>
								<tr>
									<td colspan="4">查詢範圍區間：<br><?=$sdate.' - '.$edate ?></td>
								</tr>
							</table>
                        </div>
                        <div class="panel-body">
                            <div class="row">
								<div class="col-lg-6 meta">
									<div class="table-responsive">
										<table class="table table-bordered table-hover" style="text-align:center;">
											<tbody>
											<tr style="background-color:#f5f5f5;">
												<td colspan="6">業務</td>
											</tr>
											<tr style="background-color:#f5f5f5;">
												<td rowspan="2">業務姓名</td>
												<td colspan="3">放款成功</td>
											</tr>
											<tr style="background-color:#f5f5f5;">
												<td>數量</td>
												<td>放款金額</td>
												<td>手續費</td>
											</tr>
											<? 
											$sum 		= array("success_fee"=>0,"success_amount"=>0,"success"=>0);
											$platform 	= array("success_fee"=>0,"success_amount"=>0,"success"=>0);
											if(!empty($list["platform"])){
												foreach($list["platform"] as $key => $target){
													$platform["success_fee"] 	+= $target['platform_fee'];
													$platform["success_amount"] += $target['loan_amount'];
												}
												$platform["success"] 	= count($list["platform"]);
											}

											if(!empty($list["sales"])){
												foreach($list["sales"] as $key => $value){
													$success_fee 	= 0;
													$success_amount = 0;
													if($value){
														foreach($value as $k => $target){
															$success_fee 	+= $target['platform_fee'];
															$success_amount += $target['loan_amount'];
														}
													}
													$success 	= count($value);
													$sum["success"] 		+= $success;
													$sum["success_fee"] 	+= $success_fee;
													$sum["success_amount"] 	+= $success_amount;
											?>
												<tr>
													<td><p class="form-control-static"><?=$admins_name[$key]; ?></p></td>
													<td>
													<a class="fancyframe" href="<?=admin_url('Sales/bonus_report_detail?type=sales&sdate='.$sdate.'&edate='.$edate.'&id='.$key) ?>" >
														<p class="form-control-static"><?=number_format($success); ?></p>
													</a>
													</td>
													<td><p class="form-control-static"><?=number_format($success_amount); ?></p></td>
													<td><p class="form-control-static"><?=number_format($success_fee); ?></p></td>
												</tr>
											<? }} ?>
											<tr style="background-color:#f5f5f5;">
												<td>合計</td>
												<td><?=number_format($sum["success"]); ?></td>
												<td><?=number_format($sum["success_amount"]); ?></td>
												<td><?=number_format($sum["success_fee"]); ?></td>
											</tr>
											<tr style="background-color:#f5f5f5;">
												<td>無邀請碼</td>
												<td>
												<a class="fancyframe" href="<?=admin_url('Sales/bonus_report_detail?type=platform&sdate='.$sdate.'&edate='.$edate) ?>" >
													<p class="form-control-static"><?=number_format($platform["success"]); ?></p>
												</a>
												</td>
												<td><?=number_format($platform["success_amount"]); ?></td>
												<td><?=number_format($platform["success_fee"]); ?></td>
											</tr>
											</tbody>
										</table>
									</div>
                                </div>
								<div class="col-lg-6 meta">
									<div class="table-responsive">
										<table class="table table-bordered table-hover" style="text-align:center;">
											<tbody>
											<tr style="background-color:#f5f5f5;">
												<td colspan="8">合作商</td>
											</tr>
											<tr style="background-color:#f5f5f5;">
												<td rowspan="2">負責業務</td>
												<td rowspan="2">上層公司</td>
												<td rowspan="2">類別</td>
												<td rowspan="2">單位</td>
												<td colspan="3">放款成功</td>
											</tr>
											<tr style="background-color:#f5f5f5;">
												<td>數量</td>
												<td>放款金額</td>
												<td>手續費</td>
											</tr>
											<? 
											$sum = array("success_fee"=>0,"success_amount"=>0,"success"=>0);
											if(!empty($list["partner"])){
												foreach($list["partner"] as $key => $value){
													$success_fee 	= 0;
													$success_amount = 0;
													if($value){
														foreach($value as $k => $target){
															$success_fee 	+= $target['platform_fee'];
															$success_amount += $target['loan_amount'];
														}
													}
													$success 	= count($value);
													$sum["success"] 		+= $success;
													$sum["success_fee"] 	+= $success_fee;
													$sum["success_amount"] 	+= $success_amount;
											?>
												<tr>
													<td><p class="form-control-static"><?=$admins_name[$partner_list[$key]->admin_id]; ?></p></td>
													<td><p class="form-control-static"><?=$partner_list[$key]->parent_id?$partner_list[$partner_list[$key]->parent_id]->company:""; ?></p></td>
													<td><p class="form-control-static"><?=$partner_type[$partner_list[$key]->type]; ?></p></td>
													<td><p class="form-control-static"><?=$partner_list[$key]->company; ?></p></td>
													<td>
													<a class="fancyframe" href="<?=admin_url('Sales/bonus_report_detail?type=partner&sdate='.$sdate.'&edate='.$edate.'&id='.$key) ?>" >
														<p class="form-control-static"><?=number_format($success); ?></p>
													</a>
													</td>
													<td><p class="form-control-static"><?=number_format($success_amount); ?></p></td>
													<td><p class="form-control-static"><?=number_format($success_fee); ?></p></td>
												</tr>
											<? }} ?>
											<tr style="background-color:#f5f5f5;">
												<td></td>
												<td></td>
												<td></td>
												<td>合計</td>
												<td><?=number_format($sum["success"]); ?></td>
												<td><?=number_format($sum["success_amount"]); ?></td>
												<td><?=number_format($sum["success_fee"]); ?></td>
											</tr>
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
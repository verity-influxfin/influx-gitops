
		<script type="text/javascript">
			function showChang(){
				var sdate 				= $('#sdate').val();
				var edate 				= $('#edate').val();
				top.location = './index?sdate='+sdate+'&edate='+edate;
			}
		</script>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">業務借款報表</h1>
					
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
									<td>申請日期區間：</td>
									<td><a href="<?=admin_url('Sales/index') ?>" target="_self" class="btn btn-default float-right btn-md" >本日</a></td>
									<td><a href="<?=admin_url('Sales/index?sdate=all&edate=all') ?>" target="_self" class="btn btn-default float-right btn-md" >全部</a></td>
									<td style="text-align: center;width:30px">|</td>
									<td><input type="text" value="<?=$sdate=='all'?"":$sdate ?>" id="sdate" data-toggle="datepicker"  /></td>
									<td>-</td>
									<td><input type="text" value="<?=$edate=='all'?"":$edate ?>" id="edate" data-toggle="datepicker" /></td>
									<td><a href="javascript:void(0)" onclick="showChang();" class="btn btn-default float-right btn-md" >查詢</a></td>
								</tr>
								<tr>
									<td colspan="4">查詢範圍區間：<br><?=$sdate=='all'?"全部":$sdate.' - '.$edate ?></td>
									<td colspan="4">查詢結果：<?=$count; ?>筆</td>
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
												<td rowspan="2">借款中</td>
												<td colspan="3">放款成功</td>
												<td rowspan="2">已取消</td>
											</tr>
											<tr style="background-color:#f5f5f5;">
												<td>數量</td>
												<td>放款金額</td>
												<td>手續費</td>
											</tr>
											<? 
											$sum 		= array("apply"=>0,"success_fee"=>0,"success_amount"=>0,"success"=>0,"faile"=>0);
											$platform 	= array("apply"=>0,"success_fee"=>0,"success_amount"=>0,"success"=>0,"faile"=>0);
											if(!empty($list["platform"])){
												$platform_num 			= array();
												foreach($target_status as $k => $v){
													$platform_num[$k] = 0;
													if(isset($list["platform"][$k])&&$list["platform"][$k]){
														$platform_num[$k] = count($list["platform"][$k]);
														if($k==5 || $k==10){
															foreach($list["platform"][$k] as $t => $target){
																$platform["success_fee"] 	+= $target['platform_fee'];
																$platform["success_amount"] += $target['loan_amount'];
															}
														}
													}
												}
												$platform["apply"] 		= $platform_num[0]+$platform_num[1]+$platform_num[2]+$platform_num[3]+$platform_num[4];
												$platform["success"] 	= $platform_num[5]+$platform_num[10];
												$platform["faile"] 		= $platform_num[8]+$platform_num[9];
											}

											if(!empty($list["sales"])){
												foreach($list["sales"] as $key => $value){
													$num = array();
													$success_fee 	= 0;
													$success_amount = 0;
													foreach($target_status as $k => $v){
														$num[$k] = 0;
														if(isset($value[$k])&&$value[$k]){
															$num[$k] = count($value[$k]);
															if($k==5 || $k==10){
																foreach($value[$k] as $t => $target){
																	$success_fee 	+= $target['platform_fee'];
																	$success_amount += $target['loan_amount'];
																}
															}
														}
													}
													
													$apply 		= $num[0]+$num[1]+$num[2]+$num[3]+$num[4];
													$success 	= $num[5]+$num[10];
													$faile 		= $num[8]+$num[9];
													$sum["apply"] 			+= $apply;
													$sum["success"] 		+= $success;
													$sum["success_fee"] 	+= $success_fee;
													$sum["success_amount"] 	+= $success_amount;
													$sum["faile"] 			+= $faile;
											?>
												<tr>
													<td><p class="form-control-static"><?=$admins_name[$key]; ?></p></td>
													<td>
														<p class="form-control-static"><?=$apply; ?></p>
													</td>
													<td><p class="form-control-static"><?=$success; ?></p></td>
													<td><p class="form-control-static"><?=$success_amount; ?></p></td>
													<td><p class="form-control-static"><?=$success_fee; ?></p></td>
													<td>
														<p class="form-control-static"><?=$faile; ?></p>
													</td>
												</tr>
											<? }} ?>
											<tr style="background-color:#f5f5f5;">
												<td>合計</td>
												<td><?=$sum["apply"]; ?></td>
												<td><?=$sum["success"]; ?></td>
												<td><?=$sum["success_amount"]; ?></td>
												<td><?=$sum["success_fee"]; ?></td>
												<td><?=$sum["faile"]; ?></td>
											</tr>
											<tr style="background-color:#f5f5f5;">
												<td>無邀請碼</td>
												<td><?=$platform["apply"]; ?></td>
												<td><?=$platform["success"]; ?></td>
												<td><?=$platform["success_amount"]; ?></td>
												<td><?=$platform["success_fee"]; ?></td>
												<td><?=$platform["faile"]; ?></td>
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
												<td rowspan="2">類別</td>
												<td rowspan="2">單位</td>
												<td rowspan="2">借款中</td>
												<td colspan="3">放款成功</td>
												<td rowspan="2">已取消</td>
											</tr>
											<tr style="background-color:#f5f5f5;">
												<td>數量</td>
												<td>放款金額</td>
												<td>手續費</td>
											</tr>
											<? 
											$sum = array("apply"=>0,"success_fee"=>0,"success_amount"=>0,"success"=>0,"faile"=>0);
											if(!empty($list["partner"])){
												foreach($list["partner"] as $key => $value){
													$num = array();
													$success_fee 	= 0;
													$success_amount = 0;
													foreach($target_status as $k => $v){
														$num[$k] = 0;
														if(isset($value[$k])&&$value[$k]){
															$num[$k] = count($value[$k]);
															if($k==5 || $k==10){
																foreach($value[$k] as $t => $target){
																	$success_fee 	+= $target['platform_fee'];
																	$success_amount += $target['loan_amount'];
																}
															}
														}
													}
													$apply 		= $num[0]+$num[1]+$num[2]+$num[3]+$num[4];
													$success 	= $num[5]+$num[10];;
													$faile 		= $num[8]+$num[9];
													$sum["apply"] 			+= $apply;
													$sum["success"] 		+= $success;
													$sum["success_fee"] 	+= $success_fee;
													$sum["success_amount"] 	+= $success_amount;
													$sum["faile"] 			+= $faile;
											?>
												<tr>
													<td><p class="form-control-static"><?=$admins_name[$partner_list[$key]->admin_id]; ?></p></td>
													<td><p class="form-control-static"><?=$partner_type[$partner_list[$key]->type]; ?></p></td>
													<td><p class="form-control-static"><?=$partner_list[$key]->company; ?></p></td>
													<td>
														<p class="form-control-static"><?=$apply; ?></p>
													</td>
													<td><p class="form-control-static"><?=$success; ?></p></td>
													<td><p class="form-control-static"><?=$success_amount; ?></p></td>
													<td><p class="form-control-static"><?=$success_fee; ?></p></td>
													<td>
														<p class="form-control-static"><?=$faile; ?></p>
													</td>
												</tr>
											<? }} ?>
											<tr style="background-color:#f5f5f5;">
												<td></td>
												<td></td>
												<td>合計</td>
												<td><?=$sum["apply"]; ?></td>
												<td><?=$sum["success"]; ?></td>
												<td><?=$sum["success_amount"]; ?></td>
												<td><?=$sum["success_fee"]; ?></td>
												<td><?=$sum["faile"]; ?></td>
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

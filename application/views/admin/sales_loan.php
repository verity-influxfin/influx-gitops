
	<script>
	
		function form_onsubmit(){
			return true;
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
							<?=$min_date." - ".$max_date?>
                        </div>
                        <div class="panel-body">
                            <div class="row">
								<div class="col-lg-6 meta">
									<div class="table-responsive">
										<table class="table table-bordered table-hover" style="text-align:center;">
											<tbody>
											<tr style="background-color:#f5f5f5;">
												<td colspan="4">業務</td>
											</tr>
											<tr style="background-color:#f5f5f5;">
												<td>業務姓名</td>
												<td>申請中</td>
												<td>申請成功</td>
												<td>已取消</td>
											</tr>
											<? 
											$sum 		= array("apply"=>0,"success"=>0,"faile"=>0);
											$platform 	= array("apply"=>0,"success"=>0,"faile"=>0);
											if(!empty($list["platform"])){
												$platform_num = array();
												foreach($target_status as $k => $v){
													$platform_num[$k] = isset($list["platform"][$k])&&$list["platform"][$k]?count($list["platform"][$k]):0;
												}
												$platform["apply"] 		= $platform_num[0]+$platform_num[1]+$platform_num[2];
												$platform["success"] 	= $platform_num[3]+$platform_num[4]+$platform_num[5]+$platform_num[10];
												$platform["faile"] 		= $platform_num[8]+$platform_num[9];
											}
											
											if(!empty($list["sales"])){
												foreach($list["sales"] as $key => $value){
													$num = array();
													foreach($target_status as $k => $v){
														$num[$k] = isset($value[$k])&&$value[$k]?count($value[$k]):0;
													}
													
													$apply 		= $num[0]+$num[1]+$num[2];
													$success 	= $num[3]+$num[4]+$num[5]+$num[10];
													$faile 		= $num[8]+$num[9];
													$sum["apply"] 	+= $apply;
													$sum["success"] += $success;
													$sum["faile"] 	+= $faile;
											?>
												<tr>
													<td><p class="form-control-static"><?=$admins_name[$key]; ?></p></td>
													<td>
														<p class="form-control-static"><?=$apply; ?></p>
													</td>
													<td><p class="form-control-static"><?=$success; ?></p></td>
													<td>
														<p class="form-control-static"><?=$faile; ?></p>
													</td>
												</tr>
											<? }} ?>
											<tr style="background-color:#f5f5f5;">
												<td>合計</td>
												<td><?=$sum["apply"]; ?></td>
												<td><?=$sum["success"]; ?></td>
												<td><?=$sum["faile"]; ?></td>
											</tr>
											<tr style="background-color:#f5f5f5;">
												<td>無邀請碼</td>
												<td><?=$platform["apply"]; ?></td>
												<td><?=$platform["success"]; ?></td>
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
												<td colspan="5">合作商</td>
											</tr>
											<tr style="background-color:#f5f5f5;">
												<td>負責業務</td>
												<td>單位</td>
												<td>申請中</td>
												<td>申請成功</td>
												<td>已取消</td>
											</tr>
											<? 
											$sum = array("apply"=>0,"success"=>0,"faile"=>0);
											if(!empty($list["partner"])){
												foreach($list["partner"] as $key => $value){
													$num = array();
													foreach($target_status as $k => $v){
														$num[$k] = isset($value[$k])&&$value[$k]?count($value[$k]):0;
													}
													$apply 		= $num[0]+$num[1]+$num[2];
													$success 	= $num[3]+$num[4]+$num[5]+$num[10];;
													$faile 		= $num[8]+$num[9];
													$sum["apply"] 	+= $apply;
													$sum["success"] += $success;
													$sum["faile"] 	+= $faile;
											?>
												<tr>
													<td><p class="form-control-static"><?=$admins_name[$partner_list[$key]->admin_id]; ?></p></td>
													<td><p class="form-control-static"><?=$partner_list[$key]->company; ?></p></td>
													<td>
														<p class="form-control-static"><?=$apply; ?></p>
													</td>
													<td><p class="form-control-static"><?=$success; ?></p></td>
													<td>
														<p class="form-control-static"><?=$faile; ?></p>
													</td>
												</tr>
											<? }} ?>
											<tr style="background-color:#f5f5f5;">
												<td></td>
												<td>合計</td>
												<td><?=$sum["apply"]; ?></td>
												<td><?=$sum["success"]; ?></td>
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

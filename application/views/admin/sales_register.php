
	<script>
	
		function form_onsubmit(){
			return true;
		}
	</script>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">業務註冊報表</h1>
					
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
												<td>註冊人數</td>
												<td>FB登入</td>
												<td>學生認證</td>
											</tr>
											<? 
											$sum = array("count"=>0,"school"=>0,"fb"=>0);
											if(!empty($list["sales"])){
												foreach($list["sales"] as $key => $value){
													$sum["count"] 	+= isset($value["count"])&&$value["count"]?$value["count"]:0;
													$sum["school"] += isset($value["school"])&&$value["school"]?$value["school"]:0;
													$sum["fb"] 	+= isset($value["fb"])&&$value["fb"]?$value["fb"]:0;
											?>
												<tr>
													<td><p class="form-control-static"><?=$admins_name[$key]; ?></p></td>
													<td>
														<p class="form-control-static"><?=isset($value["count"])&&$value["count"]?$value["count"]:0; ?></p>
													</td>
													<td><p class="form-control-static"><?=isset($value["fb"])&&$value["fb"]?$value["fb"]:0; ?></p></td>
													<td>
														<p class="form-control-static"><?=isset($value["school"])&&$value["school"]?$value["school"]:0; ?></p>
													</td>
												</tr>
											<? }} ?>
											<tr style="background-color:#f5f5f5;">
												<td>合計</td>
												<td><?=$sum["count"]; ?></td>
												<td><?=$sum["fb"]; ?></td>
												<td><?=$sum["school"]; ?></td>
											</tr>
											<tr style="background-color:#f5f5f5;">
												<td>無邀請碼</td>
												<td>
													<?=$list["platform"]["count"]?$list["platform"]["count"]:0; ?>
												</td>
												<td><?=$list["platform"]["fb"]?$list["platform"]["fb"]:0; ?></td>
												<td>
													<?=$list["platform"]["school"]?$list["platform"]["school"]:0; ?>
												</td>
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
												<td colspan="6">合作商</td>
											</tr>
											<tr style="background-color:#f5f5f5;">
												<td>負責業務</td>
												<td>類別</td>
												<td>單位</td>
												<td>註冊人數</td>
												<td>FB登入</td>
												<td>學生認證</td>
											</tr>
											<? 
											$sum = array("count"=>0,"school"=>0,"fb"=>0);
											if(!empty($list["partner"])){
												foreach($list["partner"] as $key => $value){
													$sum["count"] 	+= $value["count"];
													$sum["school"] 	+= $value["school"];
													$sum["fb"] 		+= $value["fb"];
											?>
												<tr>
													<td><p class="form-control-static"><?=$admins_name[$partner_list[$key]->admin_id]; ?></p></td>
													<td><p class="form-control-static"><?=$partner_type[$key]; ?></p></td>
													<td><p class="form-control-static"><?=$partner_list[$key]->company; ?></p></td>
													<td>
														<p class="form-control-static"><?=$value["count"]?$value["count"]:0; ?></p>
													</td>
													<td><p class="form-control-static"><?=$value["fb"]?$value["fb"]:0; ?></p></td>
													<td>
														<p class="form-control-static"><?=$value["school"]?$value["school"]:0; ?></p>
													</td>
												</tr>
											<? }} ?>
											<tr style="background-color:#f5f5f5;">
												<td></td>
												<td></td>
												<td>合計</td>
												<td><?=$sum["count"]; ?></td>
												<td><?=$sum["fb"]; ?></td>
												<td><?=$sum["school"]; ?></td>
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

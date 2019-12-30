

		<script type="text/javascript">
			function showChang(){
				var sdate 				= $('#sdate').val();
				var edate 				= $('#edate').val();
				top.location = './register_report?sdate='+sdate+'&edate='+edate;
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
							<table>
								<tr>
									<td><a href="<?=admin_url('Sales/register_report') ?>" target="_self" class="btn btn-default float-right btn-md" >本日</a></td>
									<td style="text-align: center;width:30px">|</td>
									<td><input type="text" value="<?=$sdate ?>" id="sdate" data-toggle="datepicker"  /></td>
									<td>-</td>
									<td><input type="text" value="<?=$edate ?>" id="edate" data-toggle="datepicker" /></td>
									<td><a href="javascript:void(0)" onclick="showChang();" class="btn btn-default float-right btn-md" >查詢</a></td>
								</tr>
								<tr>
									<td colspan="4"><?=$sdate.' - '.$edate ?></td>
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
												<td colspan="5">業務</td>
											</tr>
											<tr style="background-color:#f5f5f5;">
												<td>業務姓名</td>
												<td>註冊人數</td>
												<td>FB登入</td>
												<td>學生認證</td>
												<td>實名認證</td>
											</tr>
											<? 
											$sum = ['count'=>0,'school'=>0,'fb'=>0,'name'=>0];
											if(!empty($list['sales'])){
												foreach($list['sales'] as $key => $value){
													$sum['count'] 	+= intval($value['count']);
													$sum['school'] 	+= intval($value['school']);
													$sum['fb'] 		+= intval($value['fb']);
													$sum['name'] 	+= intval($value['name']);
											?>
												<tr>
													<td><p class="form-control-static"><?=$admins_name[$key]; ?></p></td>
													<td><p class="form-control-static"><?=intval($value["count"]) ?></p></td>
													<td><p class="form-control-static"><?=intval($value["fb"]) ?></p></td>
													<td><p class="form-control-static"><?=intval($value["school"]) ?></p></td>
													<td><p class="form-control-static"><?=intval($value["name"]) ?></p></td>
												</tr>
											<? }} ?>
											<?
											if(!empty($list['marketing'])){
												foreach($list['marketing'] as $key => $value){
													$sum['count'] 	+= intval($value['count']);
													$sum['school'] 	+= intval($value['school']);
													$sum['fb'] 		+= intval($value['fb']);
													$sum['name'] 	+= intval($value['name']);
											?>
												<tr>
													<td><p class="form-control-static"><?=$key; ?></p></td>
													<td><p class="form-control-static"><?=intval($value["count"]) ?></p></td>
													<td><p class="form-control-static"><?=intval($value["fb"]) ?></p></td>
													<td><p class="form-control-static"><?=intval($value["school"]) ?></p></td>
													<td><p class="form-control-static"><?=intval($value["name"]) ?></p></td>
												</tr>
											<? }} ?>
											<tr style="background-color:#f5f5f5;">
												<td>合計</td>
												<td><?=$sum['count']; ?></td>
												<td><?=$sum['fb']; ?></td>
												<td><?=$sum['school']; ?></td>
												<td><?=$sum['name']; ?></td>
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
												<td colspan="5">其他 ( 無邀請碼 )</td>
											</tr>
											<tr style="background-color:#f5f5f5;">
												<td>註冊人數</td>
												<td>FB登入</td>
												<td>學生認證</td>
												<td>實名認證</td>
											</tr>
											<tr style="background-color:#f5f5f5;">
												<td><a href="accounts?category=others&sdate=<?= $sdate ?>&edate=<?= $edate ?>" target="_blank"><?=$list['platform']['count']?$list['platform']['count']:0; ?></a></td>
												<td><a href="accounts?category=others&type=fb&sdate=<?= $sdate ?>&edate=<?= $edate ?>" target="_blank"><?=$list['platform']['fb']?$list['platform']['fb']:0; ?></a></td>
												<td><a href="accounts?category=others&type=student&sdate=<?= $sdate ?>&edate=<?= $edate ?>" target="_blank"><?=$list['platform']['school']?$list['platform']['school']:0; ?></a></td>
												<td><a href="accounts?category=others&type=name&sdate=<?= $sdate ?>&edate=<?= $edate ?>" target="_blank"><?=$list['platform']['name']?$list['platform']['name']:0; ?></a></td>
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
												<td colspan="7">合作商</td>
											</tr>
											<tr style="background-color:#f5f5f5;">
												<td>負責業務</td>
												<td>類別</td>
												<td>單位</td>
												<td>註冊人數</td>
												<td>FB登入</td>
												<td>學生認證</td>
												<td>實名認證</td>
											</tr>
											<? 
											$sum = ['count'=>0,'school'=>0,'fb'=>0,'name'=>0];
											if(!empty($list["partner"])){
												foreach($list["partner"] as $key => $value){
													$sum['count'] 	+= intval($value['count']);
													$sum['school'] 	+= intval($value['school']);
													$sum['fb'] 		+= intval($value['fb']);
													$sum['name'] 	+= intval($value['name']);
											?>
												<tr>
													<td><p class="form-control-static"><?=$admins_name[$partner_list[$key]->admin_id]; ?></p></td>
													<td><p class="form-control-static"><?=$partner_type[$partner_list[$key]->type]; ?></p></td>
													<td><p class="form-control-static"><?=$partner_list[$key]->company; ?></p></td>
													<td><a href="accounts?category=partner&partner_id=<?= $key ?>&sdate=<?= $sdate ?>&edate=<?= $edate ?>" target="_blank"><p class="form-control-static"><?=$value['count']?$value['count']:0; ?></p></a></td>
													<td><a href="accounts?category=partner&partner_id=<?= $key ?>&type=fb&sdate=<?= $sdate ?>&edate=<?= $edate ?>" target="_blank"><p class="form-control-static"><?=$value['fb']?$value['fb']:0; ?></p></a></td>
													<td><a href="accounts?category=partner&partner_id=<?= $key ?>&type=student&sdate=<?= $sdate ?>&edate=<?= $edate ?>" target="_blank"><p class="form-control-static"><?=$value['school']?$value['school']:0; ?></p></a></td>
													<td><a href="accounts?category=partner&partner_id=<?= $key ?>&type=name&sdate=<?= $sdate ?>&edate=<?= $edate ?>" target="_blank"><p class="form-control-static"><?=$value['name']?$value['name']:0; ?></p></a></td>
												</tr>
											<? }} ?>
											<tr style="background-color:#f5f5f5;">
												<td></td>
												<td></td>
												<td>合計</td>
												<td><a href="accounts?category=partner&sdate=<?= $sdate ?>&edate=<?= $edate ?>" target="_blank"><?=$sum["count"]; ?></a></td>
												<td><a href="accounts?category=partner&type=fb&sdate=<?= $sdate ?>&edate=<?= $edate ?>" target="_blank"><?=$sum["fb"]; ?></a></td>
												<td><a href="accounts?category=partner&type=student&sdate=<?= $sdate ?>&edate=<?= $edate ?>" target="_blank"><?=$sum["school"]; ?></a></td>
												<td><a href="accounts?category=partner&type=student&sdate=<?= $sdate ?>&edate=<?= $edate ?>" target="_blank"><?=$sum["name"]; ?></a></td>
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

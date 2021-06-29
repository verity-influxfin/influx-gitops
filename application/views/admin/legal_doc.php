        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">逾期 - 全部列表</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <style>
                .panel-heading td {
                    height: 30px;
                    vertical-align: middle;
                    padding-left: 5px;
                }
                .tsearch input {
                    width: 640px;
                }
            </style>
			<script type="text/javascript">
                function showChang(){
                    var tsearch 			= $('#tsearch').val();
                    var dateRange           = 'sdate='+$('#sdate').val()+'&edate='+$('#edate').val();
                    if(tsearch==''){
                        if(confirm("即將撈取各狀態案件，過程可能需點時間，請勿直接關閉， 確認是否執行？")) {
                            top.location = './legal_doc?inquiry=1&'+dateRange;
                        }
                    }
                    else{
                        top.location = './legal_doc?inquiry=1&tsearch='+tsearch+'&'+dateRange;
                    }
				}
                $(document).off("keypress","input[type=text]").on("keypress","input[type=text]" ,  function(e){
                    code = (e.keyCode ? e.keyCode : e.which);
                    if (code == 13){
                        showChang();
                    }
                });
			</script>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
						<div class="panel-heading" style=" display: flex;">
							<div class="col-lg-8">
								<table>
									<tr>
										<td>搜尋：</td>
										<td class="tsearch" colspan="7"><input type="text" value="<?=isset($_GET['tsearch'])&&$_GET['tsearch']!=''?$_GET['tsearch']:''?>" id="tsearch" placeholder="使用者代號(UserID) / 姓名 / 身份證字號 / 案號" /></td>
									</tr>
									<tr style="vertical-align: baseline;">
										<td>從：</td>
										<td><input type="text" value="<?=isset($_GET['sdate'])&&$_GET['sdate']!=''?$_GET['sdate']:''?>" id="sdate" data-toggle="datepicker" placeholder="不指定區間" /></td>
										<td style="">到：</td>
										<td><input type="text" value="<?=isset($_GET['edate'])&&$_GET['edate']!=''?$_GET['edate']:''?>" id="edate" data-toggle="datepicker" style="width: 182px;"  placeholder="不指定區間" /></td>
										<td colspan="2" style="text-align: right"><a href="javascript:showChang();" class="btn btn-default">查詢</a></td>
									</tr>
								</table>
							</div>
							<div class="col-lg-4" style="
								display: flex;
								justify-content: space-between;
								align-self: flex-end;">
								<a href="javascript:showChang();" class="btn btn-primary">全選</a>
								<a href="javascript:showChang();" class="btn btn-primary">匯出文件</a>
							</div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" width="100%" id="dataTables-tables">
                                    <thead>
                                        <tr>
                                            <th>案號</th>
                                            <th>產品</th>
                                            <th>會員 ID</th>
											<th>放款日</th>
											<th>逾期日</th>
											<th>逾期天數</th>
											<th>處理進度</th>
											<th style="width: 12%">債權人</th>
											<th>操作</th>
                                            <th>存證信函</th>
                                            <th>法催執行日期</th>
                                            <th style="width: 280px;">備註</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
										if(isset($list) && !empty($list)){
											$subloan_list = $this->config->item('subloan_list');
											$count = 0;
											foreach($list as $key => $value){
												$count++;
									?>
										<tr class="<?=$count%2==0?"odd":"even"; ?> list <?=isset($value->target_id)?$value->target_id:'' ?>">
											<td><?=isset($value->target_no)?$value->target_no:'' ?></td>
											<td><?=isset($product_list[$value->product_id])?$product_list[$value->product_id]['name']:'' ?><?=$value->sub_product_id!=0?' / '.$sub_product_list[$value->sub_product_id]['identity'][$product_list[$value->product_id]['identity']]['name']:'' ?><?=isset($value->target_no)?(preg_match('/'.$subloan_list.'/',$value->target_no)?'(產品轉換)':''):'' ?></td>
											<td><?=isset($value->user_id)?$value->user_id:'' ?></td>
											<td><?=isset($value->loan_date)?$value->loan_date:'' ?></td>
											<td><?=isset($value->min_limit_date)?$value->min_limit_date:'' ?></td>
											<td><?=isset($value->delay_days)?intval($value->delay_days):"" ?></td>
											<td>法催執行中
												<a href="javascript:showChang();" class="btn btn-danger mt-4">取消執行</a>
											</td>
											<td>
												<?php
													foreach($value->investor_list as $investor) {
												?>
													<input class="form-check-input" type="checkbox" value="">
													<label class="form-check-label"><?= $investor ?></label>
												<?php
													}
												?>
											</td>
											<td>
												<a href="javascript:showChang();" class="btn btn-danger ">取消</a>
												<a href="javascript:showChang();" class="btn btn-primary mt-2">全選</a>
											</td>
											<td>
												<input class="form-check-input" type="checkbox" value="" id="letterCheck">
												<label class="form-check-label" for="letterCheck">
													已寄送
												</label>
											</td>
											<td><input type="text" value="<?=isset($_GET['action_date'])&&$_GET['action_date']!=''?$_GET['action_date']:''?>" id="edate" data-toggle="datepicker" style="width: 100px;"  placeholder="執行日期" /></td>
											<td>2021/06/24進行法催</br>執行id:82,87(包含存證信函)，由[News]匯出</td>
											<td><a href="<?=admin_url('target/edit')."?id=47174" ?>" class="btn btn-default">Detail</a></td>
										</tr>
									<?php
										}}
									?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
        </div>
        <!-- /#page-wrapper -->

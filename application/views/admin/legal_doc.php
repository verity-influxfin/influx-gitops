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

				function download(filename, dataUrl) {
					// Construct the 'a' element
					var link = document.createElement("a");
					link.download = filename;
					link.target = "_blank";

					// Construct the URI
					link.href = dataUrl;
					document.body.appendChild(link);
					link.click();

					// Cleanup the DOM
					document.body.removeChild(link);
					delete link;
				}

				let allInvestorsMode = false;
				$( document ).ready(function() {
					$('.investor-all').click(function () {
						console.log($(this));
						$(this).parent().prev().find('input').prop("checked", true);
					});
					$('.investor-cancel').click(function () {
						$(this).parent().prev().find('input').prop("checked", false);
					});
					$('#all-target-investors').click(function () {
						allInvestorsMode = !allInvestorsMode;
						$('.panel-body').find('.investors > div > input').each(function() {
							$(this).prop("checked", allInvestorsMode);
						});
					});
					$('.save-process').click(function (e) {
						e.preventDefault();
						let status = $(this).prev('select').val();
						let log_id = $(this).parent().closest('tr').data('logid');
						let target_id = $(this).parent().closest('tr').data('id');
						let adminName = $('h5.navbar-brand').text();
						adminName = adminName.substr(0, adminName.indexOf('[')-1);

						Pace.track(() => {
							$.ajax({
								type: 'POST',
								url: "<?=admin_url('PostLoan/save_status')?>",
								data: {log_id: log_id, target_id: target_id, status: status},
								success: (json) => {
									console.log(json);
									let rsp = JSON.parse(json);
									if(rsp['success']) {
										$(this).parent().closest('tr').find('td.memo').append(moment().format('YYYY-MM-DD') + " 更換處理進度為" + $(this).prev('select').find('option:selected').text() + "</br> - [" + adminName + "] </br>");
										$(this).addClass('saved-animation'); // add the animation class
										setTimeout(() => {
											$(this).removeClass('saved-animation');
										}, 1500);
									}
								},
								error: function (xhr, textStatus, thrownError) {
									alert(textStatus);
								}
							});
						});
					});
					$('#exportBtn').click(function () {
						if(form_onsubmit('即將匯出文件，過程可能需點時間，請勿直接關閉，確認是否執行？')) {
							let result = [];

							let isSuccess = true;
							$('tr.list').each(function () {
								let target_id = $(this).data('id');
								let size = result.length;
								result[size] = {'doneTask': {}, 'status': 10};
								result[size]['investorUserId'] = [];
								result[size]['targetId'] = target_id;

								$(this).find('td').each(function () {
									let keyname = $(this).data('key');
									if(keyname !== undefined) {
										result[size][keyname] = $(this).text();
									}
								});

								$(this).find('.investors > div > input:checked').each(function () {
									result[size]['investorUserId'].push($(this).next('label').text());
								});

								$(this).find('input.done-task').each(function () {
									result[size]['doneTask'][$(this).val()] = $(this).prop("checked");
								});

								result[size]['sendDate'] = $(this).find('.send_datetime').val();

								if(result[size]['investorUserId'].length) {
									if (result[size]['sendDate'] === "") {
										$(this).find('.send_datetime').prop('required', true);
										$("form").find("#submit-hidden").click();
										isSuccess = false;
										return false;
									}
								}else{
									result.splice(size, 1);
								}
							});

							console.log('result',result);
							if(isSuccess) {
								Pace.track(() => {
									$.ajax({
										type: 'POST',
										url: "<?=admin_url('PostLoan/legal_doc')?>",
										data: {data: result},
										success: (rsp) => {
											let sentResult = JSON.parse(rsp);

											if(sentResult['status'] !== 200) {
												return alert(rs['description']);
											}

											$(this).addClass('saved-animation'); // add the animation class
											setTimeout(() => {
												$(this).removeClass('saved-animation');
											}, 1500);

											var timeoutID = setInterval(() => {
												$.ajax({
													type: 'POST',
													url: "<?=admin_url('PostLoan/legal_doc_status')?>",
													data: {tasksLogId: sentResult['response']['tasksLogId']},
													success: (json) => {
														let legalDocResult = JSON.parse(json);

														if(legalDocResult['status'] === 200 && legalDocResult['response']['result']['status'] === 'finished') {
															clearInterval(timeoutID);
															download('支付命令資料.zip', legalDocResult['response']['result']['url']);
														}
													},
													error: function (xhr, textStatus, thrownError) {
														alert(textStatus);
													}
												});
											}, 5000);
										},
										error: function (xhr, textStatus, thrownError) {
											alert(textStatus);
										}
									});
								});
							}
						}
					});

				});

                function showChang(){
                    var tsearch 			= $('#tsearch').val();
                    var dateRange           = 'sdate='+$('#sdate').val()+'&edate='+$('#edate').val();
                    if(tsearch==''){
                        if(confirm("即將撈取各狀態案件，過程可能需點時間，請勿直接關閉，確認是否執行？")) {
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

				function form_onsubmit(msg){
					if(confirm(msg)){
						return true;
					}
					return false;
				}
			</script>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
						<div class="panel-heading" style=" display: flex;">
							<div class="col-lg-9">
								<table>
									<tr>
										<td>搜尋：</td>
										<td class="tsearch" colspan="7"><input type="text" value="<?=isset($_GET['tsearch'])&&$_GET['tsearch']!=''?$_GET['tsearch']:''?>" id="tsearch" placeholder="使用者代號(UserID) / 姓名 / 身份證字號 / 案號" /></td>
									</tr>
									<tr style="vertical-align: baseline;">
										<td>從：</td>
										<td>
											<select class="form-control" id="sdate">
												<option value="0" <?=isset($_GET['sdate'])&&$_GET['sdate']==0?'selected="selected"':''?>>M1</option>
												<option value="30" <?=isset($_GET['sdate'])&&$_GET['sdate']==30?'selected="selected"':''?>>M2</option>
												<option value="60" <?=isset($_GET['sdate'])&&$_GET['sdate']==60?'selected="selected"':''?>>M3</option>
												<option value="90" <?=isset($_GET['sdate'])&&$_GET['sdate']==90?'selected="selected"':''?>>M4</option>
											</select>
										</td>
										<td style="">到：</td>
										<td>
											<select class="form-control" id="edate">
												<option value="0" <?=isset($_GET['edate'])&&$_GET['edate']==0?'selected="selected"':''?>>M1</option>
												<option value="30" <?=isset($_GET['edate'])&&$_GET['edate']==30?'selected="selected"':''?>>M2</option>
												<option value="60" <?=isset($_GET['edate'])&&$_GET['edate']==60?'selected="selected"':''?>>M3</option>
												<option value="90" <?=isset($_GET['edate'])&&$_GET['edate']==90?'selected="selected"':''?>>M4</option>
												<option value="9999" <?=isset($_GET['edate'])&&$_GET['edate']==9999?'selected="selected"':''?>>-</option>
											</select>
										</td>
										<td colspan="2" style="text-align: right"><a href="javascript:showChang();" class="btn btn-default">查詢</a></td>
									</tr>
								</table>
							</div>
							<div class="col-lg-3" style="
								display: flex;
								justify-content: space-between;
								align-self: flex-end;">
								<div class="btn-group-toggle" data-toggle="buttons" id="all-target-investors">
									<label class="btn btn-primary">
										<input type="checkbox"> 全選
									</label>
								</div>
								<button class="btn btn-primary button-saved draw" id="exportBtn">匯出文件</button>
							</div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
								<form action="">
								<input id="submit-hidden" type="submit" style="display: none" />
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
											<th>債權人</th>
											<th>操作</th>
                                            <th>已完成作業</th>
                                            <th>法催執行日期</th>
                                            <th>備註</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
										if(isset($list) && !empty($list)){
											$subloan_list = $this->config->item('subloan_list');
											$count = 0;
											foreach($list as $key => $value){
												$lastLog = null;
												if(count($value->logs))
													$lastLog = $value->logs[count($value->logs)-1];
												$count++;
									?>
										<tr class="<?=$count%2==0?"odd":"even"; ?> list" data-logid="<?=isset($lastLog->id)?$lastLog->id:'' ?>" data-id="<?=isset($value->target_id)?$value->target_id:'' ?>">
											<td style="word-break: break-all; min-width: 50px;" data-key="target_no"><?=isset($value->target_no)?$value->target_no:'' ?></td>
											<td><?=isset($product_list[$value->product_id])?$product_list[$value->product_id]['name']:'' ?><?=$value->sub_product_id!=0?' / '.$sub_product_list[$value->sub_product_id]['identity'][$product_list[$value->product_id]['identity']]['name']:'' ?><?=isset($value->target_no)?(preg_match('/'.$subloan_list.'/',$value->target_no)?'(產品轉換)':''):'' ?></td>
											<td><?=isset($value->user_id)?$value->user_id:'' ?></td>
											<td><?=isset($value->loan_date)?$value->loan_date:'' ?></td>
											<td data-key="limitDate"><?=isset($value->min_limit_date)?$value->min_limit_date:'' ?></td>
											<td data-key="delayDays"><?=isset($value->delay_days)?intval($value->delay_days):"" ?></td>
											<td>
												<select class="form-control" style="min-width: 150px;" >
													<?php
														foreach($process_status as $status => $description) {
													?>
													<option value="<?=$status?>" <?=isset($lastLog)&&$lastLog->status==$status?"selected":""?> ><?= $description ?></option>
													<?php
														}
													?>
												</select>
												<button class="btn btn-primary mt-2 save-process button-saved draw">儲存處理進度</button>
											</td>
											<td class="investors" style="min-width: 58px;">
												<?php
													foreach($value->investor_list as $investor) {
												?>
												<div>
													<input class="form-check-input" type="checkbox" value="" <?= isset($lastLog->investors) && in_array($investor, $lastLog->investors) ? "checked" : "" ?> >
													<label class="form-check-label"><?= $investor ?></label>
												</div>
												<?php
													}
												?>
											</td>
											<td>
												<a class="btn btn-danger investor-cancel">取消</a>
												<a class="btn btn-primary mt-2 investor-all">全選</a>
											</td>
											<td>
												<?php
													foreach($task_list as $task => $description) {
												?>
												<div>
													<input class="form-check-input done-task" type="checkbox" value="<?=$task?>" <?= isset($lastLog->done_task->$task) && $lastLog->done_task->$task == "true" ? "checked" : "" ?>>
													<label class="form-check-label"><?=$description?></label>
												</div>
												<?php
													}
												?>
											</td>
											<td><input type="text" value="<?=isset($_GET['send_datetime'])&&$_GET['send_datetime']!=''?$_GET['send_datetime']:''?>" class="send_datetime" data-toggle="datepicker" style="width: 100px;"  placeholder="執行日期" /></td>
											<td class="memo">
												<?php
													foreach($value->logs as $log) {
														if(isset($log->delay_days)) {
															if($log->delay_days > 0)
																echo (new DateTime($log->created_at))->format('Y-m-d') . " 進行法催</br>執行id: " . implode(",", $log->investors) . (isset($log->done_task->legalConfirmLetter) && $log->done_task->legalConfirmLetter == "true" ? "(包含存證信函)" : "") . "，由[" . $log->admin . "]匯出 </br>";
														}else{
															echo (new DateTime($log->created_at))->format('Y-m-d') . " 更換處理進度為".$process_status[$log->status]."</br> - [" . $log->admin . "] </br>";
														}
													}
												?>
											</td>
											<td><a href="<?=admin_url('target/edit')."?id=".$value->target_id ?>" class="btn btn-default" target="_blank" rel="noopener noreferrer">Detail</a></td>
										</tr>
									<?php
										}}
									?>
                                    </tbody>

                                </table>
								</form>
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

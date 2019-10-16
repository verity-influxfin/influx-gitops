<script>
	function form_onsubmit() {
		return true;
	}
</script>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">會員資訊</h1>

		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?= $type == "edit" ? "會員資訊" : "新增會員" ?>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-6">
							<h2>會員資訊</h2>
							<div class="table-responsive">
								<table class="table table-bordered table-hover table-striped">
									<tbody>
										<tr>
											<td>
												<p class="form-control-static">FB 照片</p>
											</td>
											<td colspan="3">
												<p class="form-control-static">
													<?= isset($meta["fb_id"]) ? "<a href='https://graph.facebook.com/" . $meta["fb_id"] . "/picture?width=500&heigth=500' data-fancybox='images'><img src='https://graph.facebook.com/" . $meta["fb_id"] . "/picture?type=large' style='width:30%;'></a>" : ""; ?>
												</p>
											</td>
											<td>
												<p class="form-control-static">FB 暱稱</p>
											</td>
											<td colspan="3">
												<p class="form-control-static"><?= isset($data->nickname) ? $data->nickname : ""; ?></p>
											</td>

										</tr>
										<tr>
											<td>
												<p class="form-control-static">會員 ID</p>
											</td>
											<td>
												<p class="form-control-static"><?= isset($data->id) ? $data->id : ""; ?></p>
											</td>
											<td>
												<p class="form-control-static">姓名</p>
											</td>
											<td>
												<p class="form-control-static"><?= isset($data->name) ? $data->name : ""; ?></p>
											</td>

											<td>
												<p class="form-control-static">Email</p>
											</td>
											<td colspan="3">
												<p class="form-control-static"><?= isset($data->email) ? $data->email : ""; ?></p>
											</td>

										</tr>
										<tr>
											<td>
												<p class="form-control-static">發證</p>
											</td>
											<td>
												<p class="form-control-static"><?= isset($data->id_card_date) ? $data->id_card_date : ""; ?> （<?= isset($data->id_card_place) ? $data->id_card_place : ""; ?>）</p>
											</td>
											<td>
												<p class="form-control-static">身分證字號</p>
											</td>
											<td>
												<p class="form-control-static"><?= isset($data->id_number) ? $data->id_number : ""; ?></p>
											</td>
											<td>
												<p class="form-control-static">性別</p>
											</td>
											<td>
												<p class="form-control-static"><?= isset($data->sex) ? $data->sex : ""; ?></p>
											</td>
										</tr>
										<tr>
											<td>
												<p class="form-control-static">生日</p>
											</td>
											<td>
												<p class="form-control-static"><?= isset($data->birthday) ? $data->birthday : ""; ?></p>
											</td>
											<td>
												<p class="form-control-static">電話</p>
											</td>
											<td>
												<p class="form-control-static"><?= isset($data->phone) ? $data->phone : ""; ?></p>
											</td>
											<td>
												<p class="form-control-static">註冊日期</p>
											</td>
											<td>
												<p class="form-control-static"><?= isset($data->created_at) && !empty($data->created_at) ? date("Y-m-d H:i:s", $data->created_at) : ""; ?></p>
											</td>
										</tr>
										<tr>
											<td>
												<p class="form-control-static">狀態</p>
											</td>
											<td>
												<p class="form-control-static">借款端：<?= isset($data->status) && $data->status ? "正常" : "未申請"; ?></p>
												<p class="form-control-static">投資端：<?= isset($data->investor_status) && $data->investor_status ? "正常" : "未申請"; ?></p>
											</td>
											<td>
												<p class="form-control-static">地址</p>
											</td>
											<td colspan="3">
												<p class="form-control-static"><?= isset($data->address) ? $data->address : ""; ?></p>
											</td>
										</tr>
										<tr>
											<td>
												<p class="form-control-static">設備號碼</p>
											</td>
											<td colspan="5">
												<p class="form-control-static">借款端</p>
												<input type="text" class="form-control" value="<?= isset($device_id_borrow) ? $device_id_borrow : "未取得"; ?>">
												<p class="form-control-static">投資端</p>
												<input type="text" class="form-control" value="<?= isset($device_id_invest) ? $device_id_invest : "未取得"; ?>">
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="col-lg-6">
							<h2>金融卡資訊</h2>
							<div class="table-responsive">
								<table class="table table-bordered table-hover" style="text-align:center;">
									<tbody>
										<? $bank_acc_cer_id = 0;
										$inv_bank_acc_cer_id = 0;
										if (!empty($bank_account)) {
											foreach ($bank_account as $key => $value) {
												if ($value->investor == 1) {
													$inv_bank_acc_cer_id = $value->id;
												} else {
													$bank_acc_cer_id = $value->id;
												}
												?>
												<tr style="background-color:#f5f5f5;">
													<td rowspan="3">
														<p class="form-control-static"><?= isset($value->investor) ? $bank_account_investor[$value->investor] : ""; ?></p>
													</td>
													<td>
														<p class="form-control-static">
															銀行：<?= isset($value->bank_code) ? $value->bank_code . ' 分行：' . $value->branch_code : ""; ?><br>
														</p>

													</td>
													<td>
														<p class="form-control-static">
															帳號：<?= isset($value->bank_account) ? $value->bank_account : ""; ?>
														</p>
													</td>
												</tr>
												<tr>
													<td><?= isset($value->front_image) ? "<a href='" . $value->front_image . "' data-fancybox='images'><img src='" . $value->front_image . "' style='width:30%;'></a>" : ""; ?></td>
													<td><?= isset($value->back_image) ? "<a href='" . $value->back_image . "' data-fancybox='images'><img src='" . $value->back_image . "' style='width:30%;'></a>" : ""; ?></td>
												</tr>
												<? if ($value->verify == 0) { ?>
													<tr>
														<td colspan="3"><? echo $bank_account_verify[$value->verify] ?></td>
													</tr>
										<? }
											}
										} ?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="col-lg-6">
							<h2>信用評分</h2>
							<div class="table-responsive">
								<table class="table table-bordered table-hover" style="text-align:center;">
									<tbody>
										<? if (!empty($credit_list)) {
											foreach ($credit_list as $key => $value) {
												?>
												<tr style="border-top:2px solid black">
													<td style="background-color: #f5f5f5">
														<p class="form-control-static">產品</p>
													</td>
													<td>
														<p class="form-control-static"><?= isset($value->product_id) ? $product_list[$value->product_id]['name'] : ""; ?></p>
													</td>
													<td style="background-color: #f5f5f5">
														<p class="form-control-static">信用等級</p>
													</td>
													<td<?= isset($value->status) && $value->status == 0 ? " style='text-decoration:line-through'" : ""; ?>>
														<p class="form-control-static"><?= isset($value->level) ? $value->level : ""; ?></p>
														</td>
												</tr>
												<tr>
													<td style="background-color: #f5f5f5">
														<p class="form-control-static">信用評分</p>
													</td>
													<td<?= isset($value->status) && $value->status == 0 ? " style='text-decoration:line-through'" : ""; ?>>
														<p class="form-control-static"><?= isset($value->points) ? $value->points : ""; ?></p>
														</td>
														<td style="background-color: #f5f5f5">
															<p class="form-control-static">信用額度</p>
														</td>
														<td<?= isset($value->status) && $value->status == 0 ? " style='text-decoration:line-through'" : ""; ?>>
															<p class="form-control-static"><?= isset($value->amount) ? $value->amount : ""; ?></p>
															</td>
												</tr>
												<tr>
													<td style="background-color: #f5f5f5">
														<p class="form-control-static">有效時間</p>
													</td>
													<td<?= isset($value->status) && $value->status == 0 ? " style='text-decoration:line-through'" : ""; ?>>
														<p class="form-control-static"><?= isset($value->expire_time) && !empty($value->expire_time) ? date("Y-m-d H:i:s", $value->expire_time) : ""; ?></p>
														</td>
														<td style="background-color: #f5f5f5">
															<p class="form-control-static">核准時間</p>
														</td>
														<td>
															<p class="form-control-static"><?= isset($value->created_at) && !empty($value->created_at) ? date("Y-m-d H:i:s", $value->created_at) : ""; ?></p>
														</td>
												</tr>
										<? }
										} ?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="col-lg-3">
							<h2>借款端認證</h2>
							<div class="table-responsive">
								<table class="table table-bordered table-hover" style="text-align:center;">
									<tbody>
										<? if (!empty($certification)) {
											foreach ($certification as $key => $value) {
												?>
												<tr>
													<td>
														<p class="form-control-static"><?= $value['name'] ?></p>
													</td>
													<td>
														<?
																$status        = ($value['expire_time'] <= time()&&!in_array($key,[IDCARD,DEBITCARD,EMERGENCY,EMAIL])? 'danger' : 'success');
																$expire_time   = date("Y/m/d", $value['expire_time']);
																$expire_status = $value['expire_time'] <= time()&&!in_array($key,[IDCARD,DEBITCARD,EMERGENCY,EMAIL]) ? (' (' . $expire_time . ')') : '';

																if ($value['id'] == 3) {
																	switch ($value['user_status']) {
																		case '3':
																		case '0':
																			echo '<a href="' . admin_url('certification/user_bankaccount_edit?id=' . $bank_acc_cer_id) . '" ><button type="button" class="btn btn-warning btn-circle"><i class="fa fa-refresh"></i> </button></a>';
																			break;
																		case '1':
																			echo '<a href="' . admin_url('certification/user_bankaccount_edit?id=' . $bank_acc_cer_id) . '" ><button type="button" class="btn btn-' . $status . ' btn-circle"><i class="fa fa-check"></i> </button></a>' . $expire_status . '';
																			break;
																		case '2':
																			echo '<a href="' . admin_url('certification/user_bankaccount_edit?id=' . $bank_acc_cer_id) . '" ><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i> </button></a>' . $expire_status . '';
																			break;
																		default:
																			echo '<p class="form-control-static">無</p>';
																			break;
																	}
																} else {
																	$certification_id = $value['certification_id'];
																	switch ($value['user_status']) {
																		case '3':
																		case '0':
																			echo '<a href="' . admin_url('certification/user_certification_edit?from=risk&id=' . $certification_id) . '" ><button type="button" class="btn btn-warning btn-circle"><i class="fa fa-refresh"></i> </button></a>';
																			break;
																		case '1':
																			echo '<a href="' . admin_url('certification/user_certification_edit?from=risk&id=' . $certification_id) . '" ><button type="button" class="btn btn-' . $status . ' btn-circle"><i class="fa fa-check"></i> </button></a>' . $expire_status . '';;
																			break;
																		case '2':
																			echo '<a href="' . admin_url('certification/user_certification_edit?from=risk&id=' . $certification_id) . '" ><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i> </button></a>' . $expire_status . '';
																			break;
																		default:
																			echo '<p class="form-control-static">無</p>';
																			break;
																	}
																}
																?>
													</td>
												</tr>
										<? }
										} ?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="col-lg-3">
							<h2>投資端認證</h2>
							<div class="table-responsive">
								<table class="table table-bordered table-hover" style="text-align:center;">
									<tbody>
										<? if (!empty($certification_investor)) {
											foreach ($certification_investor as $key => $value) {
												?>
												<tr>
													<td>
														<p class="form-control-static"><?= $value['name'] ?></p>
													</td>
													<td>
														<?
																if ($value['id'] == 3) {
																	switch ($value['user_status']) {
																		case '3':
																		case '0':
																			echo '<a href="' . admin_url('certification/user_bankaccount_edit?id=' . $inv_bank_acc_cer_id) . '" ><button type="button" class="btn btn-warning btn-circle"><i class="fa fa-refresh"></i> </button></a>';
																			break;
																		case '1':
																			echo '<a href="' . admin_url('certification/user_bankaccount_edit?id=' . $inv_bank_acc_cer_id) . '" ><button type="button" class="btn btn-success btn-circle"><i class="fa fa-check"></i> </button></a>';
																			break;
																		case '2':
																			echo '<a href="' . admin_url('certification/user_bankaccount_edit?id=' . $inv_bank_acc_cer_id) . '" ><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i> </button></a>';
																			break;
																		default:
																			echo '<p class="form-control-static">無</p>';
																			break;
																	}
																} else {
																	$certification_id = $value['certification_id'];
																	switch ($value['user_status']) {
																		case '3':
																		case '0':
																			echo '<a href="' . admin_url('certification/user_certification_edit?from=risk&id=' . $certification_id) . '" ><button type="button" class="btn btn-warning btn-circle"><i class="fa fa-refresh"></i> </button></a>';
																			break;
																		case '1':
																			echo '<a href="' . admin_url('certification/user_certification_edit?from=risk&id=' . $certification_id) . '" ><button type="button" class="btn btn-success btn-circle"><i class="fa fa-check"></i> </button></a>';
																			break;
																		case '2':
																			echo '<a href="' . admin_url('certification/user_certification_edit?from=risk&id=' . $certification_id) . '" ><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i> </button></a>';
																			break;
																		default:
																			echo '<p class="form-control-static">無</p>';
																			break;
																	}
																}
																?>
													</td>
												</tr>
										<? }
										} ?>
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

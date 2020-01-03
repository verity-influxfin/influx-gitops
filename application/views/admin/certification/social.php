		<script type="text/javascript">
			function check_fail() {
				var status = $('#status :selected').val();
				if (status == 2) {
					$('#fail_div').show();
				} else {
					$('#fail_div').hide();
				}
			}
			$(document).off("change", "select#fail").on("change", "select#fail", function() {
				var sel = $(this).find(':selected');
				$('input#fail').css('display', sel.attr('value') == 'other' ? 'block' : 'none');
				$('input#fail').attr('disabled', sel.attr('value') == 'other' ? false : true);
			});
		</script>
		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header"><?= isset($data->certification_id) ? $certification_list[$data->certification_id] : ""; ?></h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
			<div class="row">
				<? if (isset($content['type'])) { ?>
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<?= isset($data->certification_id) ? $certification_list[$data->certification_id] : ""; ?>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<label>會員 ID</label>
											<a class="fancyframe" href="<?= admin_url('User/display?id=' . $data->user_id) ?>">
												<p><?= isset($data->user_id) ? $data->user_id : "" ?></p>
											</a>
										</div>
										<div class="form-group">
											<label>認證類型</label>
											<p class="form-control-static"><?= isset($content['type']) ? $content['type'] : "" ?></p>
										</div>
										<div class="form-group">
											<label>access_token</label>
											<p class="form-control-static"><?= isset($content['access_token']) ? $content['access_token'] : "" ?></p>
										</div>
										<div class="form-group">
											<label>審核狀態</label>
											<p class="form-control-static"><?= isset($data->sys_check) && $data->sys_check == 0 ? "人工" : "系統" ?></p>
										</div>
										<div class="form-group">
											<label>備註</label>
											<?
											if ($remark) {
												if (isset($remark["fail"]) && $remark["fail"]) {
													echo '<p style="color:red;" class="form-control-static">失敗原因：' . $remark["fail"] . '</p>';
												}
											}
											?>
										</div>
										<h4>審核</h4>
										<form role="form" method="post">
											<fieldset>
												<div class="form-group">
													<select id="status" name="status" class="form-control" onchange="check_fail();">
														<? foreach ($status_list as $key => $value) { ?>
															<option value="<?= $key ?>" <?= $data->status == $key ? "selected" : "" ?>><?= $value ?></option>
														<? } ?>
													</select>
													<input type="hidden" name="id" value="<?= isset($data->id) ? $data->id : ""; ?>">
													<input type="hidden" name="from" value="<?= isset($from) ? $from : ""; ?>">
												</div>
												<div class="form-group" id="fail_div" style="display:none">
													<label>失敗原因</label>
													<select id="fail" name="fail" class="form-control">
														<option value="" disabled selected>選擇回覆內容</option>
														<? foreach ($certifications_msg[4] as $key => $value) { ?>
															<option <?= $data->status == $value ? "selected" : "" ?>><?= $value ?></option>
														<? } ?>
														<option value="other">其它</option>
													</select>
													<input type="text" class="form-control" id="fail" name="fail" value="<?= $remark && isset($remark["fail"]) ? $remark["fail"] : ""; ?>" style="background-color:white!important;display:none" disabled="false">
												</div>
												<button type="submit" class="btn btn-primary">送出</button>
											</fieldset>
										</form>

									</div>
									<div class="col-lg-6">
										<? if ($content['type'] == 'instagram') {
											$info = isset($content['info']) ? $content['info'] : array();
										?>
											<table style="text-align: center;width:100%">
												<tr>
													<td rowspan="2">
														<a href="<?= isset($info['picture']) ? $info['picture'] : "" ?>" data-fancybox="images">
															<img src="<?= isset($info['picture']) ? $info['picture'] : "" ?>" alt="<?= isset($info['username']) ? $info['username'] : "" ?> 的大頭貼照">
														</a>
													</td>
													<td><a href="<?= isset($info['link']) ? $info['link'] : "" ?>" target="_blank">
															<h1><?= isset($info['username']) ? $info['username'] : "" ?></h1>
														</a></td>
												</tr>
												<tr>
													<td>
														<h4>
															<?= isset($info['counts']['media']) ? $info['counts']['media'] : "0" ?> 貼文 、
															<?= isset($info['counts']['followed_by']) ? $info['counts']['followed_by'] : "0" ?> 位追蹤者 、
															<?= isset($info['counts']['follows']) ? $info['counts']['follows'] : "0" ?> 追蹤中 、</h4>
													</td>
												</tr>
											</table>

										<? } ?>
									</div>
									<? if (isset($info['meta']) && count($info['meta']) > 0) {
										foreach ($info['meta'] as $key => $value) {
									?>
											<div class="col-lg-3">
												<p>讚數：<?= isset($value['likes']) ? $value['likes'] : "" ?>、發布日期：<?= isset($value['created_time']) ? date("Y-m-d H:i:s", $value['created_time']) : "" ?></p>
												<a href="<?= isset($value['picture']) ? $value['picture'] : "" ?>" data-fancybox="images">
													<img style="width:100%" src="<?= isset($value['picture']) ? $value['picture'] : "" ?>">
												</a>
												<p><?= isset($value['text']) ? $value['text'] : "" ?></p>
											</div>
									<? }
									} else {
										echo '<h4>無貼文</h4>';
									} ?>
								</div>
								<!-- /.row (nested) -->
							</div>
							<!-- /.panel-body -->
						</div>
						<!-- /.panel -->
					</div>
				<? } else { ?>
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<?= isset($data->certification_id) ? $certification_list[$data->certification_id] : ""; ?>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<label>會員 ID</label>
											<a class="fancyframe" href="<?= admin_url('User/display?id=' . $data->user_id) ?>">
												<p><?= isset($data->user_id) ? $data->user_id : "" ?></p>
											</a>
										</div>
										<div class="form-group">
											<div class="form-group">
												<h3>
													<p><label>FB 認證 </label></p>
												</h3>
												<label>FB token</label>
												<p class="form-control-static"><?= isset($content['facebook']['access_token']) ? $content['facebook']['access_token'] : "" ?></p>
												<label>FB 登入信箱</label>
												<p class="form-control-static"><?= isset($content['facebook']['email']) ? $content['facebook']['email'] : "" ?></p>
												<label>FB 暱稱</label>
												<p class="form-control-static"><?= isset($content['facebook']['name']) ? $content['facebook']['name'] : "" ?></p>
												<label>FB 大頭照</label>
												<a href="<?= isset($content['facebook']['picture']) ? $content['facebook']['picture'] : "" ?>" data-fancybox="images">
													<img src="<?= isset($content['facebook']['picture']) ? $content['facebook']['picture'] : "" ?>">
												</a>
											</div>
											<div class="form-group">
												<h3>
													<p><label>IG 認證 </label></p>
												</h3>
												<label>IG 帳號</label>
												<a href="<?= isset($content['instagram']['link']) ? $content['instagram']['link'] : "" ?>" target="_blank">
													<h4><?= isset($content['instagram']['username']) ? $content['instagram']['username'] : "" ?></h4>
												</a>
												<td>
													<p>
														<?= isset($content['instagram']['counts']['media']) ? $content['instagram']['counts']['media'] : "0" ?> 貼文 、
														<?= isset($content['instagram']['counts']['followed_by']) ? $content['instagram']['counts']['followed_by'] : "0" ?> 位追蹤者 、
														<?= isset($content['instagram']['counts']['follows']) ? $content['instagram']['counts']['follows'] : "0" ?> 追蹤中 </p>
												</td>
												<label>IG 暱稱</label>
												<p class="form-control-static"><?= isset($content['instagram']['name']) ? $content['instagram']['name'] : "" ?></p>
												<label>IG token</label>
												<p class="form-control-static"><?= isset($content['instagram']['access_token']) ? $content['instagram']['access_token'] : "" ?></p>
												<label>IG 大頭照</label>
												<p><a href="<?= isset($content['instagram']['picture']) ? $content['instagram']['picture'] : "" ?>" data-fancybox="images">
														<img src="<?= isset($content['instagram']['picture']) ? $content['instagram']['picture'] : "" ?>">
													</a></p>
												<label>IG 貼文</label>
												<? foreach ($content['instagram']['meta'] as $key => $value) {
												?>
													<div>
														<p>讚數：<?= isset($value['likes']) ? $value['likes'] : "" ?>、發布日期：<?= isset($value['created_time']) ? date("Y-m-d H:i:s", $value['created_time']) : "" ?></p>
														<a href="<?= isset($value['picture']) ? $value['picture'] : "" ?>" data-fancybox="images">
															<img style="width:50%" src="<?= isset($value['picture']) ? $value['picture'] : "" ?>">
														</a>
														<p><?= isset($value['text']) ? $value['text'] : "" ?></p>
													</div>
												<? } ?>
											</div>
											<div class="form-group">
												<label>審核狀態</label>
												<p class="form-control-static"><?= isset($data->sys_check) && $data->sys_check == 0 ? "人工" : "系統" ?></p>
											</div>
											<div class="form-group">
												<label>備註</label>
												<?
												if ($remark) {
													if (isset($remark["fail"]) && $remark["fail"]) {
														echo '<p style="color:red;" class="form-control-static">失敗原因：' . $remark["fail"] . '</p>';
													}
												}
												?>
											</div>
											<h4>審核</h4>
											<form role="form" method="post">
												<fieldset>
													<div class="form-group">
														<select id="status" name="status" class="form-control" onchange="check_fail();">
															<? foreach ($status_list as $key => $value) { ?>
																<option value="<?= $key ?>" <?= $data->status == $key ? "selected" : "" ?>><?= $value ?></option>
															<? } ?>
														</select>
														<input type="hidden" name="id" value="<?= isset($data->id) ? $data->id : ""; ?>">
														<input type="hidden" name="from" value="<?= isset($from) ? $from : ""; ?>">
													</div>
													<div class="form-group" id="fail_div" style="display:none">
														<label>失敗原因</label>
														<select id="fail" name="fail" class="form-control">
															<option value="" disabled selected>選擇回覆內容</option>
															<? foreach ($certifications_msg[4] as $key => $value) { ?>
																<option <?= $data->status == $value ? "selected" : "" ?>><?= $value ?></option>
															<? } ?>
															<option value="other">其它</option>
														</select>
														<input type="text" class="form-control" id="fail" name="fail" value="<?= $remark && isset($remark["fail"]) ? $remark["fail"] : ""; ?>" style="background-color:white!important;display:none" disabled="false">
													</div>
													<button type="submit" class="btn btn-primary">送出</button>
												</fieldset>
											</form>

										</div>

										<!-- /.row (nested) -->
									</div>
									<!-- /.panel-body -->
								</div>
								<!-- /.panel -->
							</div>
							<!-- /.col-lg-12 -->
						<? } ?>
						<!-- /.col-lg-12 -->
						</div>
						<!-- /.row -->
					</div>
					<!-- /#page-wrapper -->

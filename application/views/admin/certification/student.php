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
										<label>學校名稱</label>
										<p class="form-control-static"><?= isset($content['school']) ? $content['school'] : "" ?></p>
									</div>
									<div class="form-group">
										<label>學制</label>
										<p class="form-control-static"><?= isset($content['system']) ? $school_system[$content['system']] : "" ?></p>
									</div>
									<div class="form-group">
										<label>學門</label>
										<p class="form-control-static"><?= isset($content['major']) ? $content['major'] : "" ?></p>
									</div>
									<div class="form-group">
										<label>系所</label>
										<p class="form-control-static"><?= isset($content['department']) ? $content['department'] : "" ?></p>
									</div>
									<div class="form-group">
										<label>年級</label>
										<p class="form-control-static"><?= isset($content['grade']) ? $content['grade'] : "" ?></p>
									</div>
									<div class="form-group">
										<label>學號</label>
										<p class="form-control-static"><?= isset($content['student_id']) ? $content['student_id'] : "" ?></p>
									</div>
									<div class="form-group">
										<label>校內電子信箱</label>
										<p class="form-control-static"><?= isset($content['email']) ? $content['email'] : "" ?></p>
									</div>
									<div class="form-group">
										<label>SIP帳號</label>
										<p class="form-control-static"><?= isset($content['sip_account']) ? $content['sip_account'] : "" ?></p>
									</div>
									<div class="form-group">
										<label>SIP密碼</label>
										<p class="form-control-static"><?= isset($content['sip_password']) ? $content['sip_password'] : "" ?></p>
									</div>
									<div class="form-group">
										<label>SIP 網址</label><br>
										<? if (!empty($content['sipURL'])) { ?>
											<? foreach ($content['sipURL'] as $key => $value) { ?>
												<a href="<?= isset($value) ? $value : "" ?>" target="_blank">SIP連結</a>
											<? } ?>
										<? }else{echo "無";} ?>
									</div>
									<div class="form-group">
										<label>預計畢業時間</label>
										<p class="form-control-static"><?= isset($content['graduate_date']) ? $content['graduate_date'] : "未填寫" ?></p>
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
													<? foreach ($certifications_msg[2] as $key => $value) { ?>
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
									<h1>圖片</h1>
									<fieldset disabled>
										<div class="form-group">
											<label>學生證正面照</label><br>
											<a href="<?= isset($content['front_image']) ? $content['front_image'] : "" ?>" data-fancybox="images">
												<img src="<?= isset($content['front_image']) ? $content['front_image'] : "" ?>" style='width:30%;max-width:400px'>
											</a>
										</div>
										<div class="form-group">
											<label>學生證背面照</label><br>
											<a href="<?= isset($content['back_image']) ? $content['back_image'] : "" ?>" data-fancybox="images">
												<img src="<?= isset($content['back_image']) ? $content['back_image'] : "" ?>" style='width:30%;max-width:400px'>
											</a>
										</div>
                                        <? if (!empty($content['transcript_image'])) { ?>
										<div class="form-group">
											<label>成績單</label><br>
											<a href="<?= isset($content['transcript_image']) ? $content['transcript_image'] : "" ?>" data-fancybox="images">
												<img src="<?= isset($content['transcript_image']) ? $content['transcript_image'] : "" ?>" style='width:30%;max-width:400px'>
											</a>
										</div>
                                        <? } ?>
									</fieldset>
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

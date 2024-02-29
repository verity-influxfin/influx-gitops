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
									<!-- <div class="form-group">
										<label>學門</label>
										<p class="form-control-static"><?= isset($content['major']) ? $content['major'] : "" ?></p>
									</div> -->
									<div class="form-group">
										<label>系所</label>
										<p class="form-control-static"><?= isset($content['department']) ? $content['department'] : "" ?></p>
									</div>
									<div class="form-group">
										<label>畢業日期</label>
										<p class="form-control-static"><?= isset($content['diploma_date']) ? $content['diploma_date'] : "" ?></p>
									</div>
									<!-- <div class="form-group">
										<label>SIP 帳號</label>
										<p class="form-control-static"><?= isset($content['sip_account']) ? $content['sip_account'] : "" ?></p>
									</div>
									<div class="form-group">
										<label>SIP 密碼</label>
										<p class="form-control-static"><?= isset($content['sip_password']) ? $content['sip_password'] : "" ?></p>
									</div> -->
									<!-- <div class="form-group">
										<label>SIP 網址</label><br>
										<? if (!empty($content['sipURL'])) { ?>
											<? foreach ($content['sipURL'] as $key => $value) { ?>
												<a href="<?= isset($value) ? $value : "" ?>" target="_blank">SIP連結
												</a>
											<? } ?>
										<? } ?>
									</div> -->
									<div class="form-group">
										<label>驗證結果</label>
										<?
											if($remark && isset($remark['verify_result']) && is_array($remark['verify_result'])){
												foreach($remark['verify_result'] as $verify_result){
													echo'<p style="color:red;">'.$verify_result.'</p>';
												}
											}
										?>
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
                                    <div class="form-group">
                                        <label>系統審核</label>
                                        <?
                                        if (isset($sys_check)) {
                                            echo '<p class="form-control-static">' . ($sys_check==1?'是':'否') . '</p>';
                                        }
                                        ?>
                                    </div>
									<h4>審核</h4>
									<form role="form" method="post" action="/admin/certification/user_certification_edit">
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
													<? foreach ($certifications_msg[8] as $key => $value) { ?>
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
											<label>畢業證書</label><br>
                                            <?  isset($content['diploma_image'])&&!is_array($content['diploma_image'])?$content['diploma_image']=array($content['diploma_image']):'';
                                                foreach ($content['diploma_image'] as $key => $value) { ?>
                                                <a href="<?= isset($value) ? $value : "" ?>" data-fancybox="images">
                                                    <img src="<?= $value ? $value : "" ?>" style='width:30%;max-width:400px'>
                                                </a>
                                            <? } ?>
										</div>
										<!-- <div class="form-group">
											<label>成績單</label><br>
											<? if (!empty($content['transcript_image'])) { ?>
												<? foreach ($content['transcript_image'] as $key => $value) { ?>
													<a href="<?= isset($value) ? $value : "" ?>" data-fancybox="images">
														<img src="<?= $value ? $value : "" ?>" style='width:30%;max-width:400px'>
													</a>
												<? } ?>
											<? } ?>
										</div> -->
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

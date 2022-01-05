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
        <style>
            .meta-input {
                width : 100%;
            }
        </style>
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
                                        <div class="form-group">
                                            <form role="form" action="/admin/certification/save_meta" method="post">
                                                <table class="table table-striped table-bordered table-hover dataTable">
                                                    <tbody>
                                                        <tr style="text-align: center;"><td colspan="2"><span>風控因子確認</span></td></tr>
                                                        <tr hidden><td><span>徵提資料ID</span></td><td><input class="meta-input" type="text" name="id" value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td></tr>
                                                        <tr><td><span>好友數</span></td><td><input class="meta-input" type="text" name="follow_count" placeholder=""></td></tr>
                                                        <tr><td><span>近3個月內每發文數</span></td><td><input class="meta-input" type="text" name="posts_in_3months" placeholder=""></td></tr>
                                                        <tr><td><span>發文關鍵字</span></td><td><input class="meta-input" type="text" name="key_word" placeholder=""></td></tr>
                                                        <tr><td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button></td></tr>
                                                    </tbody>
                                                </table>
                                            </form>
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
                                                <table border="1" style="text-align: center;width:100%">
                                                    <tr>
                                                        <td>IG 帳號</td>
                                                        <td><a href="<?= isset($content['instagram']['link']) ? $content['instagram']['link'] : "" ?>" target="_blank">
        													<h4><?= isset($content['instagram']['username']) ? $content['instagram']['username'] : "" ?></h4>
        												</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>帳號是否存在</td>
                                                        <td><?= isset($content['instagram']['usernameExist']) ? $content['instagram']['usernameExist'] : "" ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>貼文數</td>
                                                        <td><?= isset($content['instagram']['info']['allPostCount']) ? $content['instagram']['info']['allPostCount'] : "" ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>被追蹤數</td>
                                                        <td><?= isset($content['instagram']['info']['allFollowerCount']) ? $content['instagram']['info']['allFollowerCount'] : "" ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>追蹤數</td>
                                                        <td><?= isset($content['instagram']['info']['allFollowingCount']) ? $content['instagram']['info']['allFollowingCount'] : "" ?></td>
                                                    </tr>
                                                </table>

                                                <? if(isset($content['instagram']['access_token'])){   ?>
												<label>IG token(IG ID)</label>
												<p class="form-control-static"><? echo $content['instagram']['access_token']; ?></p>
                                                <? } ?>
												<label>IG 大頭照</label>
												<p><a href="<?= isset($content['instagram']['picture']) ? $content['instagram']['picture'] : "" ?>" data-fancybox="images">
														<img src="<?= isset($content['instagram']['picture']) ? $content['instagram']['picture'] : "" ?>">
													</a></p>
												<label>IG 貼文</label>
												<? if(isset($content['instagram']['meta'])) {
													foreach ($content['instagram']['meta'] as $key => $value) {
												?>
													<div>
														<p>讚數：<?= isset($value['likes']) ? $value['likes'] : "" ?>、發布日期：<?= isset($value['created_time']) ? date("Y-m-d H:i:s", $value['created_time']) : "" ?></p>
														<a href="<?= isset($value['picture']) ? $value['picture'] : "" ?>" data-fancybox="images">
															<img style="width:50%" src="<?= isset($value['picture']) ? $value['picture'] : "" ?>">
														</a>
														<p><?= isset($value['text']) ? $value['text'] : "" ?></p>
													</div>
												<? } }?>
											</div>
                                            <div class="form-group">
                                                <form role="form" action="/admin/certification/save_meta" method="post">
                                                    <table class="table table-striped table-bordered table-hover dataTable">
                                                        <tbody>
                                                            <tr style="text-align: center;"><td colspan="2"><span>風控因子確認</span></td></tr>
                                                            <tr hidden><td><span>徵提資料ID</span></td><td><input class="meta-input" type="text" name="id" value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td></tr>
                                                            <tr><td><span>好友數</span></td><td><input class="meta-input" type="text" name="follow_count" placeholder=""></td></tr>
                                                            <tr><td><span>近3個月內每發文數</span></td><td><input class="meta-input" type="text" name="posts_in_3months" placeholder=""></td></tr>
                                                            <tr><td><span>發文關鍵字</span></td><td><input class="meta-input" type="text" name="key_word" placeholder=""></td></tr>
                                                            <tr><td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button></td></tr>
                                                        </tbody>
                                                    </table>
                                                </form>
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
                                            <div class="form-group">
                                                <label>系統審核</label>
                                                <?
                                                if (isset($sys_check)) {
                                                    echo '<p class="form-control-static">' . ($sys_check==1?'是':'否') . '</p>';
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
<script>
$(document).ready(function() {
    $.ajax({
        type: "GET",
        url: `/admin/certification/getMeta?id=<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>`,
        dataType: "json",
        success: function (response) {
            if(response.status.code == 200 && response.response != ''){
                Object.keys(response.response).forEach(function(key) {
                    if($(`[name='${key}']`).length){
                        if($(`[name='${key}']`).is("input")){
                            $(`[name='${key}']`).val(response.response[key]);
                        }else{
                            let $select = $(`[name='${key}']`).selectize();
                            let selectize = $select[0].selectize;
                            selectize.setValue(selectize.search(response.response[key]).items[0].id);
                        }
                    }
                })
            }else{
                console.log(response);
            }
        },
        error: function(error) {
          alert(error);
        }
    });
});
</script>

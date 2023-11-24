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
            function confirmAndSubmit() {
                var result = confirm("確定要執行嗎？");
                if (result) {
                    document.getElementById("confirmForm").submit();
                } else {
                    return false;
                }
            };
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
										<label>交件方式</label>
										<p class="form-control-static"><?php
                                            if (isset($content['return_type']))
                                            {
                                            switch($content['return_type']){
                            					case 0:
                            						$type = '郵局申請(紙本)';
                            					    break;
                                                case 1:
                            						$type = '自然人憑證';
                            					    break;
                                                case 2:
                            						$type = '投資人行動網';
                            					    break;
                                                case 3:
                            						$type = '臨櫃申請(紙本)';
                            					    break;
                                                default:
                                                    break;
                                            }
                                        }else{
                                            $type = '';
                                        }
                                        echo $type; ?></p>
									</div>
                                    <div class="form-group">
                                        <?php
                                        if ( ! empty($content['pdf_file']))
                                        {
                                            // user 回信或是 admin 上傳 pdf 檔案
                                            ?>
                                            <label>聯徵資料</label><br>
                                            <a href="<?= $content['pdf_file']; ?>" target="_blank">下載</a>
                                            <form id="confirmForm" role="form" method="post" action="/admin/certification/user_certification_edit">
                                            <div class="form-group">
                                                <input type="hidden" name="delete_pdf_file" value="<?=isset($content['pdf_file']) ? $content['pdf_file'] : '0' ?>" />
                                                <input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"";?>" >
                                                <input type="hidden" name="from" value="<?=isset($content['pdf_file'])?$content['pdf_file']:"";?>" >
                                            </div>
                                            <button type="submit" onclick="return confirmAndSubmit()" class="btn btn-primary" >刪除檔案</button>
                                        </form><br />
                                        <?php }
                                        elseif ( ! empty($content['images']))
                                        {
                                            // admin 上傳圖片
                                            ?>
                                            <label>聯徵資料</label><br>
                                            <?php
                                            $index = 0;
                                            foreach ($content['images'] as $value)
                                            { ?>
                                                <a href="<?= $value; ?>" target="_blank" data-fancybox="images" class="btn btn-sm btn-default" style="margin-bottom: 2px">圖片<?= ++$index; ?></a>
                                                <?php
                                            }
                                        }
                                        elseif (in_array($content['return_type'], [1, 2]))
                                        {
                                            // 原始邏輯
                                            ?>
                                            <label>聯徵資料</label><br>
                                            <p>尚未收到回信PDF</p>
                                        <?php }
                                        ?>
                                    </div>
								</div>
                                <div class="col-lg-6">
                                    <?php if ($content['return_type'] == 0){ ?>
									<h1>圖片</h1>
									<fieldset disabled>
										<div class="form-group">
											<label>郵遞回單</label><br>
											<a href="<?= isset($content['postal_image']) ? $content['postal_image'] : "" ?>" data-fancybox="images">
												<img src="<?= isset($content['postal_image']) ? $content['postal_image'] : "" ?>" style='width:30%;max-width:400px'>
											</a>
										</div>
									</fieldset>
                                    <?php } ?>
                                    <?php
                                    // 待人工審核時才可以上傳
                                    if ($data->status == CERTIFICATION_STATUS_PENDING_TO_REVIEW)
                                    {
                                        if ( ! empty($ocr['upload_page']['pdf_file']))
                                        {
                                            ?>
                                            <label>上傳pdf檔案</label>
                                            <div class="form-group" style="background:#f5f5f5;border-style:double;">
                                                <?= $ocr['upload_page']['pdf_file']; ?>
                                            </div>
                                        <?php }
                                        if ( ! empty($ocr['upload_page']['images']))
                                        {
                                            ?>
                                            <label>上傳圖片</label>
                                            <div class="form-group" style="background:#f5f5f5;border-style:double;">
                                                <?= $ocr['upload_page']['images']; ?>
                                            </div>
                                        <?php }
                                    } ?>
                                </div>
								<!-- 聯徵報告 -->
								<div class="col-lg-12">
								<?
									if($report_page){
										echo $report_page;
									}
								?>
								</div>
								<div class="col-lg-12">
									<form role="form" method="post" action="/admin/certification/user_certification_edit">
                                    <? if ($data->status == 1) { ?>
                                        <div class="form-group">
                                            <label>查詢次數</label>
                                            <p><?= isset($content['times']) ? $content['times'] : 0 ?></p>
                                        </div>
                                        <div class="form-group">
                                            <label>信用卡使用率%</label>
                                            <p><?= isset($content['credit_rate']) ? $content['credit_rate'] : 0 ?></p>
                                        </div>
                                        <div class="form-group">
                                            <label>信用記錄幾個月</label>
                                            <p><?= isset($content['months']) ? $content['months'] : 0 ?></p>
                                        </div>
                                        <div class="form-group">
                                            <label>聯徵調閱日期</label>
                                            <p><?= isset($content['printDate']) ? $content['printDate'] : 0 ?></p>
                                        </div>
                                    <? } else { ?>
                                        <div class="form-group">
                                            <label>查詢次數</label>
                                            <input type="number" class="form-control" name="times" value="<?= isset($content['times']) ? $content['times'] : 0 ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>信用卡使用率%</label>
                                            <input type="number" step="0.01" class="form-control" name="credit_rate" value="<?= isset($content['credit_rate']) ? $content['credit_rate'] : 0 ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>信用記錄幾個月</label>
                                            <input type="number" class="form-control" name="months" value="<?= isset($content['months']) ? $content['months'] : 0 ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>聯徵調閱日期</label>
                                            <input type="text" class="form-control" name="printDate" data-toggle="datepicker" style="width: 182px;" value="<?= isset($content['printDate']) ? $content['printDate'] : 0 ?>">
                                        </div>
                                    <? } ?>
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
													<? foreach ($certifications_msg[9] as $key => $value) { ?>
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

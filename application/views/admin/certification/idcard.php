		<script type="text/javascript">
			function check_fail(){
				var status = $('#status :selected').val();
				if(status==2){
					$('#fail_div').show();
				}else{
					$('#fail_div').hide();
				}
			}
		</script>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?=isset($data->certification_id)?$certification_list[$data->certification_id]:"";?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							<?=isset($data->certification_id)?$certification_list[$data->certification_id]:"";?>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
									<div class="form-group">
										<label>會員 ID</label>
										<a class="fancyframe" href="<?=admin_url('User/display?id='.$data->user_id) ?>" >
											<p><?=isset($data->user_id)?$data->user_id:"" ?></p>
										</a>
									</div>
									<div class="form-group">
										<label>姓名</label>
										<p class="form-control-static"><?=isset($content['name'])?$content['name']:""?></p>
									</div>
									<div class="form-group">
										<label>身分證字號</label>
										<p class="form-control-static"><?=isset($content['id_number'])?$content['id_number']:""?></p>
									</div>
									<div class="form-group">
										<label>發證日期</label>
										<p class="form-control-static"><?=isset($content['id_card_date'])?$content['id_card_date']:""?></p>
									</div>
									<div class="form-group">
										<label>發證地點</label>
										<p class="form-control-static"><?=isset($content['id_card_place'])?$content['id_card_place']:""?></p>
									</div>
									<div class="form-group">
										<label>生日</label>
										<p class="form-control-static"><?=isset($content['birthday'])?$content['birthday']:""?></p>
									</div>
									<div class="form-group">
										<label>地址</label>
										<p class="form-control-static"><?=isset($content['address'])?$content['address']:""?></p>
									</div>									
									<div class="form-group">
										<label>備註</label>
										<? 
											if($remark){
												if(isset($remark["fail"]) && $remark["fail"]){
													echo '<p style="color:red;" class="form-control-static">失敗原因：'.$remark["fail"].'</p>';
												}
												if(isset($remark["error"]) && $remark["error"]){
													echo '<p style="color:red;" class="form-control-static">錯誤：<br />'.$remark["error"].'</p>';
												}
												if($remark["face"] && is_array($remark["face"])){
													echo '<p class="form-control-static">照片比對結果：';
													foreach($remark["face"] as $key => $value){
														echo $value."% ";
													}
													echo '</p>';
												}
											}
										?>
									</div>
									<h1>審核</h1>
                                    <form role="form" method="post">
                                        <fieldset>
       										<div class="form-group">
												<select id="status" name="status" class="form-control" onchange="check_fail();" >
													<? foreach($status_list as $key => $value){ ?>
													<option value="<?=$key?>" <?=$data->status==$key?"selected":""?>><?=$value?></option>
													<? } ?>
												</select>
												<input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"";?>" >
												<input type="hidden" name="from" value="<?=isset($from)?$from:"";?>" >
											</div>
											<div class="form-group" id="fail_div" style="display:none">
												<label>失敗原因</label>
												<input type="text" class="form-control" id="fail" name="fail" value="<?=$remark && isset($remark["fail"])?$remark["fail"]:"";?>" >
											</div>
											<button type="submit" class="btn btn-primary">送出</button>
                                        </fieldset>
                                    </form>
                                </div>
								<div class="col-lg-6">
                                    <h1>圖片</h1>
									<fieldset disabled>
										<div class="form-group">
											<label for="disabledSelect">身分證正面照</label><br>
											<table>
												<tr>
													<td rowspan="6">
														<a href="<?=isset($content['front_image'])?$content['front_image']:""?>" data-fancybox="images">
															<img src="<?=isset($content['front_image'])?$content['front_image']:""?>" style='width:100%;max-width:300px'>
														</a>
													</td>
												</tr>
												<tr><td>
													<label>人臉數量：</label><?=isset($remark["face_count"]["front_count"])?$remark["face_count"]["front_count"]:0;?><br>
													<label>姓名：</label><?=isset($remark["OCR"]["name"])?$remark["OCR"]["name"]:"";?><br>
													<label>生日：</label><?=isset($remark["OCR"]["birthday"])?$remark["OCR"]["birthday"]:"";?><br>
													<label>換發日期：</label><?=isset($remark["OCR"]["id_card_date"])?$remark["OCR"]["id_card_date"]:"";?><br>
													<label>身分證字號：</label><?=isset($remark["OCR"]["id_number"])?$remark["OCR"]["id_number"]:"";?>
												</td></tr>
											</table>
										</div>
										<div class="form-group">
											<label for="disabledSelect">身分證背面照</label><br>
											<table>
												<tr>
													<td rowspan="5">
														<a href="<?=isset($content['back_image'])?$content['back_image']:""?>" data-fancybox="images">
															<img src="<?=isset($content['back_image'])?$content['back_image']:""?>" style='width:100%;max-width:300px;'>
														</a>
													</td>
												</tr>
												<tr><td>
													<label>父：</label><?=isset($remark["OCR"]["father"])?$remark["OCR"]["father"]:"";?><br>
													<label>母：</label><?=isset($remark["OCR"]["mother"])?$remark["OCR"]["mother"]:"";?><br>
												</td></tr>
											</table>
										</div>
										<div class="form-group">
											<label for="disabledSelect">本人照</label><br>
											<table>
												<tr>
													<td rowspan="2">
														<a href="<?=isset($content['person_image'])?$content['person_image']:""?>" data-fancybox="images">
															<img src="<?=isset($content['person_image'])?$content['person_image']:""?>" style='width:100%;max-width:300px;'>
														</a>
													</td>
												</tr>
												<tr><td><label>人臉數量：</label><?=isset($remark["face_count"]["person_count"])?$remark["face_count"]["person_count"]:0;?></td></tr>
											</table>
										</div>
										<div class="form-group">
											<label for="disabledSelect">健保卡照</label><br>
											<table>
												<tr>
													<td rowspan="6">
														<a href="<?=isset($content['healthcard_image'])?$content['healthcard_image']:""?>" data-fancybox="images">
															<img src="<?=isset($content['healthcard_image'])?$content['healthcard_image']:""?>" style='width:100%;max-width:300px;'>
														</a>
													</td>
												</tr>
												<tr><td>
													<label>姓名：</label><?=isset($remark["OCR"]["name"])?$remark["OCR"]["name"]:"";?><br>
													<label>生日：</label><?=isset($remark["OCR"]["birthday"])?$remark["OCR"]["birthday"]:"";?><br>
													<label>身分證字號：</label><?=isset($remark["OCR"]["id_number"])?$remark["OCR"]["id_number"]:"";?>
												</td></tr>
											</table>
										</div>
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

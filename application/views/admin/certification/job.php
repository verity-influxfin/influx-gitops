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
										<label>公司</label>
										<p class="form-control-static"><?=isset($content['tax_id'])?$content['tax_id']:""?></p>
										<p class="form-control-static"><?=isset($content['company'])?$content['company']:""?></p>
									</div>
									<div class="form-group">
										<label>公司類型</label>
										<p class="form-control-static"><?=isset($content['industry'])?$industry_name[$content['industry']]:""?></p>
									</div>
									<div class="form-group">
										<label>企業規模</label>
										<p class="form-control-static"><?=isset($content['employee'])?$employee_range[$content['employee']]:""?></p>
									</div>
									<div class="form-group">
										<label>職位</label>
										<p class="form-control-static"><?=isset($content['position'])?$position_name[$content['position']]:""?></p>
									</div>
									<div class="form-group">
										<label>職務性質</label>
										<p class="form-control-static"><?=isset($content['type'])?$job_type_name[$content['type']]:""?></p>
									</div>
									<div class="form-group">
										<label>畢業以來的工作期間</label>
										<p class="form-control-static"><?=isset($content['seniority'])?$seniority_range[$content['seniority']]:""?></p>
									</div>
									<div class="form-group">
										<label>此公司工作期間</label>
										<p class="form-control-static"><?=isset($content['job_seniority'])?$seniority_range[$content['job_seniority']]:""?></p>
									</div>
									<div class="form-group">
										<label>月薪</label>
										<p class="form-control-static"><?=isset($content['salary'])?$content['salary']:""?></p>
									</div>
									<div class="form-group">
										<label>備註</label>
										<? 
											if($remark){
												if(isset($remark["fail"]) && $remark["fail"]){
													echo '<p style="color:red;" class="form-control-static">失敗原因：'.$remark["fail"].'</p>';
												}
											}
										?>
									</div>
									<h1>審核</h1>
                                    <form role="form" method="post">
                                        <fieldset>
       										<div class="form-group">
												<? if($data->status==1){?>
												<div class="form-group">
												<label>專業加分</label>
													<p><?=isset($content['license_status'])&&$content['license_status']==1?"專業證書加分":"專業證書不加分"?></p>
												</div>
												<?}else{?>
												<div class="form-group">
												<label>專業加分</label>
													<select id="license_status" name="license_status" class="form-control">
														<option value="0" <?=isset($content['license_status'])&&$content['license_status']==0?"selected":""?>>專業證書不加分</option>
														<option value="1" <?=isset($content['license_status'])&&$content['license_status']==1?"selected":""?>>專業證書加分</option>
													</select>
												</div>
												<?}?>
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
											<label for="disabledSelect">名片/工作證明</label><br>
											<a href="<?=isset($content['business_image'])?$content['business_image']:""?>" data-fancybox="images">
												<img src="<?=isset($content['business_image'])?$content['business_image']:""?>" style='width:30%;max-width:400px'>
											</a>
										</div>
										<div class="form-group">
											<label for="disabledSelect">勞健保卡</label><br>
											<? if(!empty($content['labor_image'])){
												foreach($content['labor_image'] as $key => $value){
											?>
											<a href="<?=$value ?>" data-fancybox="images">
												<img src="<?=$value ?>" style='width:30%;max-width:400px'>
											</a>
											<?}}?>
										</div>
										<div class="form-group">
											<label for="disabledSelect">存摺內頁照</label><br>
											<? if(!empty($content['passbook_image'])){
												foreach($content['passbook_image'] as $key => $value){
											?>
											<a href="<?=$value ?>" data-fancybox="images">
												<img src="<?=$value ?>" style='width:30%;max-width:400px'>
											</a>
											<?}}?>
										</div>
										<div class="form-group">
											<label for="disabledSelect">收入輔助證明</label><br>
											<? if(!empty($content['auxiliary_image'])){
												foreach($content['auxiliary_image'] as $key => $value){
											?>
											<a href="<?=$value ?>" data-fancybox="images">
												<img src="<?=$value ?>" style='width:30%;max-width:400px'>
											</a>
											<?}}?>
										</div>
										<? if(!empty($content['license_image'])){ ?>
										<div class="form-group">
											<label for="disabledSelect">專業證書</label><br>
											<a href="<?=isset($content['license_image'])?$content['license_image']:""?>" data-fancybox="images">
												<img src="<?=isset($content['license_image'])?$content['license_image']:""?>" style='width:30%;max-width:400px'>
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


	<script>
	
		function form_onsubmit(){
			return true;
		}
	</script>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?=$type=="edit"?"會員資訊":"新增會員" ?></h1>
					
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							<?=$type=="edit"?"會員資訊":"新增會員" ?>
                        </div>
                        <div class="panel-body">
                            <div class="row">
								<div class="col-lg-6 meta">
									<form role="form" method="post" onsubmit="return form_onsubmit();" >
										<? if($type=="edit"){ ?>
										<input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"";?>" >
										<? } ?>
										
										<div class="table-responsive">
											<table class="table table-bordered table-hover table-striped">
												<tbody>
													<tr>
														<td><p class="form-control-static">ID</p></td>
														<td>
															<p class="form-control-static"><?=isset($data->id)?$data->id:"";?></p>
														</td>
														<td><p class="form-control-static">姓名</p></td>
														<td>
															<p class="form-control-static"><?=isset($data->name)?$data->name:"";?></p>
														</td>

														<td><p class="form-control-static">Email</p></td>
														<td colspan="3">
															<p class="form-control-static"><?=isset($data->email)?$data->email:"";?></p>
														</td>
	
													</tr>
													<tr>
														<td><p class="form-control-static">發證地點</p></td>
														<td>
															<p class="form-control-static"><?=isset($data->id_card_place)?$data->id_card_place:"";?></p>
														</td>
														<td><p class="form-control-static">發證日期</p></td>
														<td>
															<p class="form-control-static"><?=isset($data->id_card_date)?$data->id_card_date:"";?></p>
														</td>
														<td><p class="form-control-static">身分證字號</p></td>
														<td>
															<p class="form-control-static"><?=isset($data->id_number)?$data->id_number:"";?></p>
														</td>
														<td><p class="form-control-static">性別</p></td>
														<td>
															<p class="form-control-static"><?=isset($data->sex)?$data->sex:"";?></p>
														</td>
													</tr>
													<tr>
														<td><p class="form-control-static">生日</p></td>
														<td>
															<p class="form-control-static"><?=isset($data->birthday)?$data->birthday:"";?></p>
														</td>
														<td><p class="form-control-static">電話</p></td>
														<td>
															<p class="form-control-static"><?=isset($data->phone)?$data->phone:"";?></p>
														</td>
														<td><p class="form-control-static">地址</p></td>
														<td colspan="3">
															<p class="form-control-static"><?=isset($data->address)?$data->address:"";?></p>
														</td>
													</tr>
													<tr>
														<td><p class="form-control-static">借款端帳號</p></td>
														<td>
															<p class="form-control-static"><?=isset($data->status)&&$data->status?"正常":"未申請";?></p>
														</td>
														<td><p class="form-control-static">出借端帳號</p></td>
														<td>
															<p class="form-control-static"><?=isset($data->status)&&$data->status?"正常":"未申請";?></p>
														</td>
														<td><p class="form-control-static">註冊日期</p></td>
														<td colspan="3">
															<p class="form-control-static"><?=isset($data->created_at)&&!empty($data->created_at)?date("Y-m-d H:i:s",$data->created_at):"";?></p>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
										<!--button type="submit" class="btn btn-default">Submit Button</button-->
									</form>
									<div class="table-responsive">
										<table class="table table-bordered table-hover" style="text-align:center;">
											<tbody>
											<? if(!empty($meta_fields)){
												foreach($meta_fields as $alias => $fields){
											?>
												<tr style="background-color:#f5f5f5;">
													<td colspan="2"><?=isset($certification_list[$alias])?$certification_list[$alias]:$alias?></td>
												</tr>
											<?		foreach($fields as $key => $field){
											?>
												<tr>
													<td style="width:40%;"><?=$field ?></td>
													<td style="word-break: break-all;width:60%;"><?
													if(isset($meta[$key])&&!empty($meta[$key])){
														if(in_array($key,$meta_images)){
															echo "<img src='".$meta[$key]."' style='width:30%;'>";
														}else if( $key == $alias.'_status' && $meta[$key]==1){
															echo "已認證"; 
														}else{
															echo $meta[$key];
														}
													}else{
														echo "無";
													}
													?></td>
												</tr>
											<? }}} ?>
											</tbody>
										</table>
									</div>
								</div>
								<div class="col-lg-6 meta">
									<div class="table-responsive">
										<table class="table table-bordered table-hover" style="text-align:center;">
											<tbody>
											<tr style="background-color:#f5f5f5;">
												<td colspan="4">金融卡資訊</td>
											</tr>
											<? if(!empty($bank_account)){
												foreach($bank_account as $key => $value){
											?>
												<tr style="background-color:#f5f5f5;">
													<td><p class="form-control-static">ID</p></td>
													<td>
														<p class="form-control-static"><?=isset($value->id)?$value->id:"";?></p>
													</td>
													<td><p class="form-control-static">借款端/出借端</p></td>
													<td>
														<p class="form-control-static"><?=isset($value->investor)?$bank_account_investor[$value->investor]:"";?></p>
													</td>
												</tr>
												<tr>
													<td><p class="form-control-static">銀行號 分行號</p></td>
													<td>
														<p class="form-control-static"><?=isset($value->bank_code)?$value->bank_code.' '.$value->branch_code:"";?></p>
													</td>
													<td><p class="form-control-static">銀行帳號</p></td>
													<td>
														<p class="form-control-static"><?=isset($value->bank_account)?$value->bank_account:"";?></p>
													</td>
												</tr>
												<tr>
													<td><p class="form-control-static">狀態</p></td>
													<td>
														<p class="form-control-static">正常</p>
													</td>
													<td><p class="form-control-static">驗證狀態</p></td>
													<td>
														<p class="form-control-static"><?=isset($value->verify)?$bank_account_verify[$value->verify]:"";?></p>
													</td>
												</tr>
												<tr>
													<td colspan="2"><?=isset($value->front_image)?"<img src='".$value->front_image."' style='width:30%;'>":"";?></td>
													<td colspan="2"><?=isset($value->back_image)?"<img src='".$value->back_image."' style='width:30%;'>":"";?></td>
												</tr>
											<? }} ?>
											</tbody>
										</table>
									</div>
                                </div>


                                </div>
                                <!-- /.col-lg-6 (nested) -->
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

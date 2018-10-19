
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
									<div class="table-responsive">
										<table class="table table-bordered table-hover table-striped">
											<tbody>
												<tr>
													<td><p class="form-control-static">FB 照片</p></td>
													<td colspan="3">
														<p class="form-control-static">
														<?=isset($meta["fb_id"])?"<a href='https://graph.facebook.com/".$meta["fb_id"]."/picture?width=500&heigth=500' data-fancybox='images'><img src='https://graph.facebook.com/".$meta["fb_id"]."/picture?type=large' style='width:30%;'></a>":"";?>
														</p>
													</td>
													<td><p class="form-control-static">FB 暱稱</p></td>
													<td colspan="3">
														<p class="form-control-static"><?=isset($data->nickname)?$data->nickname:"";?></p>
													</td>

												</tr>
												<tr>
													<td><p class="form-control-static">會員 ID</p></td>
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
													<td>
														<p class="form-control-static"><?=isset($value->investor)?$bank_account_investor[$value->investor]:"";?></p>
													</td>
													<td>
														<p class="form-control-static">
														銀行：<?=isset($value->bank_code)?$value->bank_code.'<br>分行：'.$value->branch_code:"";?><br>
														<?=isset($value->bank_account)?$value->bank_account:"";?>
														</p>
													</td>
													<td>
														<p class="form-control-static">正常</p>
													</td>
													<td>
														<p class="form-control-static"><?=isset($value->verify)?$bank_account_verify[$value->verify]:"";?></p>
													</td>
												</tr>
												<tr>
													<td colspan="2"><?=isset($value->front_image)?"<a href='".$value->front_image."' data-fancybox='images'><img src='".$value->front_image."' style='width:30%;'></a>":"";?></td>
													<td colspan="2"><?=isset($value->back_image)?"<a href='".$value->back_image."' data-fancybox='images'><img src='".$value->back_image."' style='width:30%;'></a>":"";?></td>
												</tr>
											<? }} ?>
											</tbody>
										</table>
									</div>
                                </div>
								<div class="col-lg-3 meta">
									<div class="table-responsive">
										<table class="table table-bordered table-hover" style="text-align:center;">
											<tbody>
											<tr style="background-color:#f5f5f5;">
												<td colspan="4">信用指數</td>
											</tr>
											<? if(!empty($credit_list)){
												foreach($credit_list as $key => $value){
											?>
												<tr>
													<td><p class="form-control-static">產品</p></td>
													<td>
														<p class="form-control-static"><?=isset($value->product_id)?$product_list[$value->product_id]:"";?></p>
													</td>
													<td><p class="form-control-static">信用等級</p></td>
													<td>
														<p class="form-control-static"><?=isset($value->level)?$value->level:"";?></p>
													</td>
												</tr>
												<tr>
													<td><p class="form-control-static">信用評分</p></td>
													<td>
														<p class="form-control-static"><?=isset($value->points)?$value->points:"";?></p>
													</td>
													<td><p class="form-control-static">信用額度</p></td>
													<td>
														<p class="form-control-static"><?=isset($value->amount)?$value->amount:"";?></p>
													</td>
												</tr>
												<tr>
													<td><p class="form-control-static">有效時間</p></td>
													<td>
														<p class="form-control-static"><?=isset($value->expire_time)&&!empty($value->expire_time)?date("Y-m-d H:i:s",$value->expire_time):"";?></p>
													</td>
													<td><p class="form-control-static">核准時間</p></td>
													<td>
														<p class="form-control-static"><?=isset($value->created_at)&&!empty($value->created_at)?date("Y-m-d H:i:s",$value->created_at):"";?></p>
													</td>
												</tr>
											<? }} ?>
											</tbody>
										</table>
									</div>
                                </div>
								<? if(!empty($meta_fields)){
									foreach($meta_fields as $alias => $fields){
								?>
								<div class="col-lg-3 meta">
									<div class="table-responsive">
										<table class="table table-bordered table-hover" style="text-align:center;">
											<tbody>
												<tr style="background-color:#f5f5f5;">
													<td colspan="2"><?=isset($certification_list[$alias])?$certification_list[$alias]:$alias?></td>
												</tr>
											<?		foreach($fields as $key => $field){
											?>
												<tr>
													<td style="width:40%;"><?=$field ?></td>
													<td style="word-break: break-all;width:60%;"><?
													if(isset($meta[$key])){
														if(in_array($key,$meta_images) && !empty($meta[$key])){
															echo "<a href='".$meta[$key]."' data-fancybox='images'><img src='".$meta[$key]."' style='width:30%;'></a>";
														}else if( $key == $alias.'_status' && $meta[$key]==1){
															echo "已認證"; 
														}else if( $key == 'school_system'){
															echo $school_system[$meta[$key]]; 
														}else{
															if(!empty($meta[$key]))
																echo $meta[$key];
															else
																echo "無";
														}
													}else{
														echo "無";
													}
													?></td>
												</tr>
												<? } ?>
											</tbody>
										</table>
									</div>
								</div>
								<? }} ?>
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

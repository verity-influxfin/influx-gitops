
	<script>
	
		function form_onsubmit(){
			return true;
		}
	</script>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?=$type=="edit"?"修改會員資訊":"新增會員" ?></h1>
					
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							<?=$type=="edit"?"修改會員資訊":"新增會員" ?>
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
								</div>
								<div class="col-lg-6 meta">
									<div class="table-responsive">
										<table class="table table-bordered table-hover table-striped">
											<tbody>
											<?php if(!empty($meta)){
												$image = array("health_card_status","health_card_front","id_card_front","id_card_back","id_card_person","student_card_front","student_card_back","financial_creditcard","financial_passbook");
												foreach($meta as $key => $value){
													if(in_array($key,$image)){
														$value = "<img src='".$value."' style='width:50%'>";
													}
											?>
												<tr>
													<td><?=isset($meta_fields[$key])?$meta_fields[$key]:$key?></td>
													<td style="word-break: break-all;"><?=$value?></td>
												</tr>
											<?php }} ?>
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

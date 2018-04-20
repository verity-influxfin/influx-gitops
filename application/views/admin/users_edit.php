
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
                                <div class="col-lg-6 user">
                                    <form role="form" method="post" onsubmit="return form_onsubmit();" >
									    <div class="form-group">
                                            <label>ID</label>
                                            <p class="form-control-static"><?=isset($data->id)?$data->id:"";?></p>
                                        </div>
                                        <div class="form-group">
                                            <label>姓名</label>
                                            <!--input id="name" name="name" class="form-control" placeholder="Enter Name" value="<?=isset($data->name)?$data->name:"";?>"-->
											<p class="form-control-static"><?=isset($data->name)?$data->name:"";?></p>
											<?
												if($type=="edit"){
											?>
											<input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"";?>" >
											<? } ?>
                                        </div>
										<div class="form-group">
                                            <label>性別</label>
                                            <p class="form-control-static"><?=isset($data->sex)?$data->sex:"";?></p>
                                        </div>
										<div class="form-group">
                                            <label>電話</label>
                                            <p class="form-control-static"><?=isset($data->phone)?$data->phone:"";?></p>
                                        </div>
										<div class="form-group">
                                            <label>身分證字號</label>
                                            <p class="form-control-static"><?=isset($data->id_number)?$data->id_number:"";?></p>
                                        </div>
										<div class="form-group">
                                            <label>發證地點</label>
                                            <p class="form-control-static"><?=isset($data->id_card_date)?$data->id_card_date:"";?></p>
                                        </div>
										<div class="form-group">
                                            <label>發證日期</label>
                                            <p class="form-control-static"><?=isset($data->id_card_place)?$data->id_card_place:"";?></p>
                                        </div>
										<div class="form-group">
                                            <label>地址</label>
                                            <p class="form-control-static"><?=isset($data->address)?$data->address:"";?></p>
                                        </div>
										<div class="form-group">
                                            <label>Email</label>
                                            <!--input id="email" name="email" class="form-control" placeholder="Enter Email" value="<?=isset($data->email)?$data->email:"";?>"-->
											<p class="form-control-static"><?=isset($data->address)?$data->address:"";?></p>
                                        </div>
										<div class="form-group">
                                            <label>生日</label>
                                            <p class="form-control-static"><?=isset($data->birthday)?$data->birthday:"";?></p>
                                        </div>
										<div class="form-group">
                                            <label>借款端帳號</label>
                                            <p class="form-control-static"><?=isset($data->status)&&$data->status?"正常":"未申請";?></p>
                                        </div>
										<div class="form-group">
                                            <label>出借端帳號</label>
                                            <p class="form-control-static"><?=isset($data->status)&&$data->status?"正常":"未申請";?></p>
                                        </div>
										<div class="form-group">
                                            <label>註冊日期</label>
                                            <p class="form-control-static"><?=isset($data->created_at)&&!empty($data->created_at)?date("Y-m-d H:i:s",$data->created_at):"";?></p>
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
														$value = "<img src='".display_image($value)."' style='width:50%'>";
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

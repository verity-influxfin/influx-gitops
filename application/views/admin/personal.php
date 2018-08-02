
	<script>
	
		function form_onsubmit(){
			return true;
		}
	</script>
	
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">修改個人資料</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
						修改個人資料
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form role="form" method="post" onsubmit="return form_onsubmit();" > 
                                        <div class="form-group">
                                            <label>帳號</label>
												<p class="form-control-static"><?=isset($data->account)?$data->account:"";?></p>
												<a href="<?=isset($data->my_promote_code)?$data->qrcode:"" ?>" data-fancybox="images" ><img src="<?=isset($data->my_promote_code)?$data->qrcode:"" ?>" /></a>
                                        </div>
                                        <div class="form-group">
                                            <label>姓名</label>
                                            <input id="name" name="name" class="form-control" placeholder="Enter Name" value="<?=isset($data->name)?$data->name:"";?>">
                                        </div>
                                        <div class="form-group">
                                            <label>電話</label>
                                            <input id="phone" name="phone" class="form-control" placeholder="Enter Phone" value="<?=isset($data->phone)?$data->phone:"";?>" >
                                        </div>
										<div class="form-group">
                                            <label>生日</label> 
                                            <p class="form-control-static"><?=isset($data->birthday)?date("m/d",strtotime($data->birthday)):"";?></p>
                                        </div>
										<div class="form-group">
                                            <label>Email</label> 
											<p class="form-control-static"><?=isset($data->email)?$data->email:"";?></p>
                                        </div>
										<div class="form-group">
                                            <label>角色</label>
											<p class="form-control-static"><?=isset($data->role_id)?$role_name[$data->role_id]:"";?></p>
                                        </div>
										<div class="form-group">
											<label>新密碼</label> 
											<input type="password" id="password" name="password" class="form-control" placeholder="Enter Password">
										</div>
										<div class="form-group">
											<label>確認新密碼</label> 
											<input type="password" id="confirm_password" class="form-control" placeholder="Confirm Password">
										</div>
                                        <button type="submit" class="btn btn-default">Submit Button</button>
                                    </form>
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

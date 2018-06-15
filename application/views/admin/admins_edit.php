
	<script>
	
		function form_onsubmit(){
			return true;
		}
	</script>
	
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?=$type=="edit"?"修改管理員資訊":"新增管理員" ?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
						<?=$type=="edit"?"修改管理員資訊":"新增管理員" ?>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form role="form" method="post" onsubmit="return form_onsubmit();" > 
                                        <div class="form-group">
                                            <label>帳號</label>
											<?
												if($type=="edit"){
											?>
												<p class="form-control-static"><?=isset($data->account)?$data->account:"";?></p>
												<input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"";?>" >
											<? }else{ ?>
												<input id="account" name="account" class="form-control" placeholder="Enter Account">
											<? } ?>
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
                                            <input id="birthday" name="birthday" class="form-control" placeholder="Enter Birthday" value="<?=isset($data->birthday)?$data->birthday:"";?>" >
                                        </div>
										<div class="form-group">
                                            <label>Email</label> 
											<?
												if($type=="edit"){
											?>
												<p class="form-control-static"><?=isset($data->email)?$data->email:"";?></p>
											<? }else{ ?>
												<input id="email" name="email" class="form-control" placeholder="Enter Email">
											<? } ?>
                                        </div>
										<div class="form-group">
                                            <label>角色</label>
                                            <select class="form-control" id="role_id" name="role_id">
												<? 
												if(isset($role_name) && !empty($role_name)){
													foreach($role_name as $key => $value){
												?>
                                                <option value="<?=$key; ?>" <?=isset($data->role_id)&&$data->role_id==$key?"selected":"";?>><?=$value; ?></option>
												<? }} ?>
                                            </select>
                                        </div>
										<div class="form-group">
                                            <label>Password</label> 
                                            <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password">
                                        </div>
										<div class="form-group">
                                            <label>Confirm Password</label> 
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

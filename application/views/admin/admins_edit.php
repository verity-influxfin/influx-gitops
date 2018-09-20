
	<script>
	
		function form_onsubmit(){
			return true;
		}
	</script>
	
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?=$type=="edit"?"修改後台人員資訊":"新增後台人員" ?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
						<?=$type=="edit"?"修改後台人員資訊":"新增後台人員" ?>
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
												<a href="<?=isset($data->my_promote_code)?$data->qrcode:"" ?>" data-fancybox="images" ><img src="<?=isset($data->my_promote_code)?$data->qrcode:"" ?>" /></a>
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
                                            <input data-toggle="datepicker" id="birthday" name="birthday" class="form-control" placeholder="Enter Birthday" value="<?=isset($data->birthday)?$data->birthday:"";?>" >
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
										<?
											if($type!="edit"){
										?>
											<div class="form-group">
												<label>Password</label> 
												<input type="password" id="password" name="password" class="form-control" placeholder="Enter Password">
											</div>
											<div class="form-group">
												<label>Confirm Password</label> 
												<input type="password" id="confirm_password" class="form-control" placeholder="Confirm Password">
											</div>
										<? }else{ ?>
											<div class="form-group">
                                            <label>狀態</label>
												<select class="form-control" id="status" name="status">
													<? 
													if(isset($status_list) && !empty($status_list)){
														foreach($status_list as $key => $value){
													?>
													<option value="<?=$key; ?>" <?=isset($data->status)&&$data->status==$key?"selected":"";?>><?=$value; ?></option>
													<? }} ?>
												</select>
											</div>
										<? } ?>
                                        <button type="submit" class="btn btn-default">送出</button>
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


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
                                            <label>地址</label> 
                                            <input id="address" name="address" class="form-control" placeholder="Enter Address" value="<?=isset($data->address)?$data->address:"";?>" >
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
                                            <label>Password</label> 
                                            <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password">
                                        </div>
										<div class="form-group">
                                            <label>Confirm Password</label> 
                                            <input type="password" id="confirm_password" class="form-control" placeholder="Confirm Password">
                                        </div>
								
                                        <!--div class="form-group">
                                            <label>狀態</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="0">0</option>
                                                <option value="1">1</option>
                                            </select>
                                        </div-->

                                        <button type="submit" class="btn btn-default">Submit Button</button>
                                    </form>
                                </div>

                                    <!--h1>Form Validation States</h1>
                                    <form role="form">
                                        <div class="form-group has-success">
                                            <label class="control-label" for="inputSuccess">Input with success</label>
                                            <input type="text" class="form-control" id="inputSuccess">
                                        </div>
                                        <div class="form-group has-warning">
                                            <label class="control-label" for="inputWarning">Input with warning</label>
                                            <input type="text" class="form-control" id="inputWarning">
                                        </div>
                                        <div class="form-group has-error">
                                            <label class="control-label" for="inputError">Input with error</label>
                                            <input type="text" class="form-control" id="inputError">
                                        </div>
                                    </form-->

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

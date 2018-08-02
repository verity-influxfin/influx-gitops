
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
										<?
											if($type=="edit"){
										?>
										<div class="form-group">
                                            <label>QR Code</label> 
											<img class="form-control-static" src="<?=isset($data->my_promote_code)?$data->qrcode:"" ?>" />
	                                    </div>
										<? } ?>
                                        <div class="form-group">
                                            <label>公司統編</label>
											<?
												if($type=="edit"){
											?>
												<p class="form-control-static"><?=isset($data->tax_id)?$data->tax_id:"";?></p>
												<input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"";?>" >
											<? }else{ ?>
												<input id="tax_id" name="tax_id" class="form-control" placeholder="Enter Tax ID">
											<? } ?>
                                        </div>
										<div class="form-group">
                                            <label>公司名稱</label>
                                            <input id="company" name="company" class="form-control" placeholder="Enter Company" value="<?=isset($data->company)?$data->company:"";?>">
                                        </div>
                                        <div class="form-group">
                                            <label>負責人姓名</label>
                                            <input id="name" name="name" class="form-control" placeholder="Enter Name" value="<?=isset($data->name)?$data->name:"";?>">
                                        </div>
                                        <div class="form-group">
                                            <label>負責人電話</label>
                                            <input id="phone" name="phone" class="form-control" placeholder="Enter Phone" value="<?=isset($data->phone)?$data->phone:"";?>" >
                                        </div>
										<div class="form-group">
                                            <label>負責人職稱</label> 
                                            <input id="title" name="title" class="form-control" placeholder="Enter Title" value="<?=isset($data->title)?$data->title:"";?>" >
                                        </div>
										<div class="form-group">
                                            <label>負責人Email</label> 
											<?
												if($type=="edit"){
											?>
												<p class="form-control-static"><?=isset($data->email)?$data->email:"";?></p>
											<? }else{ ?>
												<input id="email" name="email" class="form-control" placeholder="Enter Email">
											<? } ?>
                                        </div>
										<div class="form-group">
                                            <label>負責業務</label>
                                            <select class="form-control" id="admin_id" name="admin_id">
												<option value="0" >無</option>
												<? 
												if(isset($admins_name) && !empty($admins_name)){
													foreach($admins_name as $key => $value){
												?>
                                                <option value="<?=$key; ?>" <?=isset($data->admin_id)&&$data->admin_id==$key?"selected":"";?>><?=$value; ?></option>
												<? }} ?>
                                            </select>
                                        </div>
										<div class="form-group">
                                            <label>上層公司</label>
                                            <select class="form-control" id="parent_id" name="parent_id">
												<option value="0" >無</option>
												<? 
												if(isset($partner_name) && !empty($partner_name)){
													foreach($partner_name as $key => $value){
												?>
                                                <option value="<?=$key; ?>" <?=isset($data->parent_id)&&$data->parent_id==$key?"selected":"";?>><?=$value; ?></option>
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

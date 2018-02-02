
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
                                <div class="col-lg-12">
                                    <form role="form" method="post" onsubmit="return form_onsubmit();" >
									    <div class="form-group">
                                            <label>ID</label>
                                            <p class="form-control-static"><?=isset($data->id)?$data->id:"";?></p>
                                        </div>
                                        <div class="form-group">
                                            <label>名稱</label>
                                            <input id="name" name="name" class="form-control" placeholder="Enter Name" value="<?=isset($data->name)?$data->name:"";?>">
											<?
												if($type=="edit"){
											?>
											<input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"";?>" >
											<? } ?>
                                        </div>
										<div class="form-group">
                                            <label>電話</label>
                                            <p class="form-control-static"><?=isset($data->phone)?$data->phone:"";?></p>
                                        </div>
                                        <div class="form-group">
                                            <label>別名</label>
                                            <input id="nickname" name="nickname" class="form-control" placeholder="Enter Nick Name" value="<?=isset($data->nickname)?$data->nickname:"";?>">
                                        </div>
										<div class="form-group">
                                            <label>地址</label>
                                            <input id="address" name="address" class="form-control" placeholder="Enter Address" value="<?=isset($data->address)?$data->address:"";?>">
                                        </div>
										<div class="form-group">
                                            <label>Email</label>
                                            <input id="email" name="email" class="form-control" placeholder="Enter Email" value="<?=isset($data->email)?$data->email:"";?>">
                                        </div>
										<div class="form-group">
                                            <label>Status</label>
                                            <input id="status" name="status" class="form-control" placeholder="Enter Status" value="<?=isset($data->status)?$data->status:"";?>">
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

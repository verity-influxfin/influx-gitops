
	<script>
	
		function form_onsubmit(){
			return true;
		}
	</script>
	
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?=$type=="edit"?"修改認證方式":"新增認證方式" ?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
						<?=$type=="edit"?"修改認證方式":"新增認證方式" ?>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form role="form" method="post" onsubmit="return form_onsubmit();" > 
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
                                            <label>代號</label>
											<input id="alias" name="alias" class="form-control" placeholder="Enter Alias" value="<?=isset($data->alias)?$data->alias:"";?>">
                                        </div>
                                        <div class="form-group">
                                            <label>簡介</label>
											<textarea id="description" name="description" class="form-control" rows="3"><?=isset($data->description)?$data->description:"";?></textarea>
                                        </div>

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
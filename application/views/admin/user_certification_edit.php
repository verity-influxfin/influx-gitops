
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">認證訊息</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
						認證訊息
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form role="form" method="post"> 
                                        <div class="form-group">
                                            <label>User</label>
											<p><?=isset($data->user_id)?$data->user_id:"";?></p>
											<input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"";?>" >
                                        </div>
										<div class="form-group">
                                            <label>認證方式</label>
											<p><?=isset($data->certification_id)?$certification_list[$data->certification_id]:"";?></p>
                                        </div>
										<?
											if(!empty($content)){
												foreach($content as $key => $value){
										?>
											<div class="form-group">
												<label><?=$key; ?></label>
												<? if(substr($key,-5,5)=="image"){?>
												<img src="<?=display_image($value)?>" height="300" width="auto">
												<? }else{?>
												<p><?=$value?></p>
												<? }?>
											</div>
										<?
											}}
										?>
										
										<? if($data->status!=1){ ?>
											<div class="form-group">
												<label>審核</label>
												<select name="status">
													<? foreach($status_list as $key => $value){ ?>
													<option value="<?=$key?>" <?=$data->status==$key?"selected":""?>><?=$value?></option>
													<? } ?>
												</select>
											</div>
											<button type="submit" class="btn btn-default">Submit Button</button>
										<? } ?>
                                        
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

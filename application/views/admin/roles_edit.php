
	<script>
	
		function form_onsubmit(){
			return true;
		}
	</script>
	
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?=$type=="edit"?"修改權限":"新增角色" ?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
						<?=$type=="edit"?"修改權限":"新增角色" ?>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" method="post" onsubmit="return form_onsubmit();" > 
                                        <div class="form-group">
                                            <label>代號</label>
											<?
												if($type=="edit"){
											?>
												<p class="form-control-static"><?=isset($data->alias)?$data->alias:"";?></p>
												<input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"";?>" >
												<? }else{ ?>
												<input id="alias" name="alias" class="form-control" >
											<? } ?>
                                        </div>
                                        <div class="form-group">
                                            <label>角色名稱</label>
                                            <input id="name" name="name" class="form-control" value="<?=isset($data->name)?$data->name:"";?>">
                                        </div>
                                        <div class="form-group">
                                            <label>權限設定</label>

											<table class="table table-bordered table-hover" style="text-align:center;">
												<thead>
													<tr>
													  <th>功能</th>
													  <th>閱讀</th>
													  <th>修改</th>
													</tr>
												</thead>
												<tbody>
												<? if(!empty($admin_menu)){
													foreach($admin_menu as $key => $value){
												?>
													<tr>
														<td><?=isset($value["name"])?$value["name"]:$value["parent_name"] ?></td>
														<td>
															<input class="ed" type="checkbox" name="permission[<?=$key;?>][r]" value="1" <?=isset($data->permission[$key]["r"])&&$data->permission[$key]["r"]?"checked":""?>/>
														</td>
														<td>
															<input class="ed" type="checkbox" name="permission[<?=$key;?>][u]" value="1" <?=isset($data->permission[$key]["u"])&&$data->permission[$key]["u"]?"checked":""?>/>
														</td>
													</tr>
												<? }} ?>
												</tbody>
											</table>
                                        </div>

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

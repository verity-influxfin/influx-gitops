
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?=isset($data->certification_id)?$certification_list[$data->certification_id]:"";?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							<?=isset($data->certification_id)?$certification_list[$data->certification_id]:"";?>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
									<div class="form-group">
										<label>會員 ID</label>
										<a class="fancyframe" href="<?=admin_url('User/display?id='.$data->user_id) ?>" >
											<p><?=isset($data->user_id)?$data->user_id:"" ?></p>
										</a>
										<input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"";?>" >
									</div>
									<div class="form-group">
										<label>緊急聯絡人姓名</label>
										<p class="form-control-static"><?=isset($content['name'])?$content['name']:""?></p>
									</div>
									<div class="form-group">
										<label>緊急聯絡人電話</label>
										<p class="form-control-static"><?=isset($content['phone'])?$content['phone']:""?></p>
									</div>
									<div class="form-group">
										<label>緊急聯絡人關係</label>
										<p class="form-control-static"><?=isset($content['relationship'])?$content['relationship']:""?></p>
									</div>						
									<h1>審核</h1>
                                    <form role="form">
                                        <fieldset>
       										<div class="form-group">
												<select name="status" class="form-control">
													<? foreach($status_list as $key => $value){ ?>
													<option value="<?=$key?>" <?=$data->status==$key?"selected":""?>><?=$value?></option>
													<? } ?>
												</select>
											</div>
											<button type="submit" class="btn btn-primary">送出</button>
                                        </fieldset>
                                    </form>
                                </div>
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

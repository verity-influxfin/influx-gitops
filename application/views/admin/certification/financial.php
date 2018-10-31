
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
									</div>
									<div class="form-group">
										<label>打工收入</label>
										<p class="form-control-static"><?=isset($content['parttime'])?$content['parttime']:""?></p>
									</div>
									<div class="form-group">
										<label>零用錢收入</label>
										<p class="form-control-static"><?=isset($content['allowance'])?$content['allowance']:""?></p>
									</div>
									<div class="form-group">
										<label>獎學金收入</label>
										<p class="form-control-static"><?=isset($content['scholarship'])?$content['scholarship']:""?></p>
									</div>
									<div class="form-group">
										<label>其他收入</label>
										<p class="form-control-static"><?=isset($content['other_income'])?$content['other_income']:""?></p>
									</div>
									<div class="form-group">
										<label>餐飲支出</label>
										<p class="form-control-static"><?=isset($content['restaurant'])?$content['restaurant']:""?></p>
									</div>
									<div class="form-group">
										<label>交通支出</label>
										<p class="form-control-static"><?=isset($content['transportation'])?$content['transportation']:""?></p>
									</div>	
									<div class="form-group">
										<label>娛樂支出</label>
										<p class="form-control-static"><?=isset($content['entertainment'])?$content['entertainment']:""?></p>
									</div>	
									<div class="form-group">
										<label>其他支出</label>
										<p class="form-control-static"><?=isset($content['other_expense'])?$content['other_expense']:""?></p>
									</div>									
									<h1>審核</h1>
                                    <form role="form" method="post">
                                        <fieldset>
       										<div class="form-group">
												<select name="status" class="form-control">
													<? foreach($status_list as $key => $value){ ?>
													<option value="<?=$key?>" <?=$data->status==$key?"selected":""?>><?=$value?></option>
													<? } ?>
												</select>
												<input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"";?>" >
												<input type="hidden" name="from" value="<?=isset($from)?$from:"";?>" >
											</div>
											<button type="submit" class="btn btn-primary">送出</button>
                                        </fieldset>
                                    </form>
                                </div>
								<div class="col-lg-6">
                                    <h1>圖片</h1>
									<fieldset disabled>
										<div class="form-group">
											<label for="disabledSelect">信用卡帳單照</label><br>
											<a href="<?=isset($content['creditcard_image'])?$content['creditcard_image']:""?>" data-fancybox="images">
												<img src="<?=isset($content['creditcard_image'])?$content['creditcard_image']:""?>" style='width:30%;max-width:400px'>
											</a>
										</div>
										<div class="form-group">
											<label for="disabledSelect">存摺內頁照</label><br>
											<a href="<?=isset($content['creditcard_image'])?$content['creditcard_image']:""?>" data-fancybox="images">
												<img src="<?=isset($content['creditcard_image'])?$content['creditcard_image']:""?>" style='width:30%;max-width:400px'>
											</a>
										</div>
									</fieldset>
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

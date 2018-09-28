
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
										<label>銀行代碼</label>
										<p class="form-control-static"><?=isset($content['bank_code'])?$content['bank_code']:""?></p>
									</div>
									<div class="form-group">
										<label>分支機構代號</label>
										<p class="form-control-static"><?=isset($content['branch_code'])?$content['branch_code']:""?></p>
									</div>
									<div class="form-group">
										<label>銀行帳號</label>
										<p class="form-control-static"><?=isset($content['bank_account'])?$content['bank_account']:""?></p>
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
											</div>
											<button type="submit" class="btn btn-primary">送出</button>
                                        </fieldset>
                                    </form>
                                </div>
								<div class="col-lg-6">
                                    <h1>圖片</h1>
									<fieldset disabled>
										<div class="form-group">
											<label for="disabledSelect">金融卡正面照</label><br>
											<a href="<?=isset($content['front_image'])?$content['front_image']:""?>" data-fancybox="images">
												<img src="<?=isset($content['front_image'])?$content['front_image']:""?>" style='width:30%;max-width:400px'>
											</a>
										</div>
										<div class="form-group">
											<label for="disabledSelect">金融卡背面照</label><br>
											<a href="<?=isset($content['back_image'])?$content['back_image']:""?>" data-fancybox="images">
												<img src="<?=isset($content['back_image'])?$content['back_image']:""?>" style='width:30%;max-width:400px'>
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

        <script type="text/javascript">
            function check_fail(){
                var status = $('#status :selected').val();
                if(status==2){
                    $('#fail_div').show();
                }else{
                    $('#fail_div').hide();
                }
            }
        </script>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">金融帳號</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							金融帳號
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
									<div class="form-group">
										<label>會員 ID</label>
										<p class="form-control-static"><?=isset($data->user_id)?$data->user_id:"";?></p>
										<input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"";?>" >
									</div>
									<div class="form-group">
										<label>出借/借款</label>
										<p class="form-control-static"><?=isset($data->investor)?$investor_list[$data->investor]:"";?></p>
									</div>
									<div class="form-group">
										<label>銀行代碼</label>
										<p class="form-control-static"><?=isset($data->bank_code)?$data->bank_code:"";?></p>
									</div>	
									<div class="form-group">
										<label>分行代碼</label>
										<p class="form-control-static"><?=isset($data->branch_code)?$data->branch_code:"";?></p>
									</div>
									<div class="form-group">
										<label>銀行帳號</label>
										<p class="form-control-static"><?=isset($data->bank_account)?$data->bank_account:"";?></p>
									</div>
									<div class="form-group">
										<label>驗證狀況</label>
										<p class="form-control-static"><?=isset($data->verify)?$verify_list[$data->verify]:"" ?></p>
									</div>
                                    <h4>審核</h4>
                                    <form role="form" method="post">
                                        <fieldset>
                                            <div class="form-group hide">
                                                <select id="status" name="status" class="form-control" onchange="check_fail();" >
                                                    <option value="2"></option>
                                                </select>
                                                <input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"";?>" >
                                                <input type="hidden" name="from" value="<?=isset($from)?$from:"";?>" >
                                            </div>
                                            <button type="submit" class="btn btn-primary">作廢</button>
                                        </fieldset>
                                    </form>
                                </div>
								<div class="col-lg-6">
                                    <h1>圖片</h1>
									<fieldset disabled>
                                    <? if($bankbook==0){ ?>
										<div class="form-group">
											<label for="disabledSelect">金融卡正面照</label><br>
											<a href="<?=isset($data->front_image)?$data->front_image:""?>" data-fancybox="images">
												<img src="<?=isset($data->front_image)?$data->front_image:""?>" style='width:30%;max-width:400px'>
											</a>
										</div>
										<div class="form-group">
											<label for="disabledSelect">金融卡背面照</label><br>
											<a href="<?=isset($data->back_image)?$data->back_image:""?>" data-fancybox="images">
												<img src="<?=isset($data->back_image)?$data->back_image:""?>" style='width:30%;max-width:400px'>
											</a>
										</div>
                                    <? }else{  ?>
                                        <div class="form-group">
											<label for="disabledSelect">金融卡正/反面照</label><br>
                                            <? foreach($bankbook_image as $key => $value){ ?>
                                                <a href="<?=isset($value)?$value:""?>" data-fancybox="images">
                                                    <img src="<?=$value?$value:""?>" style='width:100%;max-width:300px'>
                                                </a>
                                            <? } ?>

										</div>                                       
                                    <? } ?>
									</fieldset>
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

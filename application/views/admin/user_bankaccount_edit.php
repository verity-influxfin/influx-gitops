
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
                                <div class="col-lg-12">
                                    <form role="form" method="post"> 
                                        <div class="form-group">
                                            <label>會員 ID</label>
											<p><?=isset($data->user_id)?$data->user_id:"";?></p>
											<input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"";?>" >
                                        </div>
										<div class="form-group">
                                            <label>出借/借款</label>
											<p><?=isset($data->investor)?$data->investor:"";?></p>
                                        </div>
										<div class="form-group">
                                            <label>銀行代碼</label>
											<p><?=isset($data->bank_code)?$data->bank_code:"";?></p>
                                        </div>	
										<div class="form-group">
                                            <label>分行代碼</label>
											<p><?=isset($data->branch_code)?$data->branch_code:"";?></p>
                                        </div>
										<div class="form-group">
                                            <label>銀行帳號</label>
											<p><?=isset($data->bank_account)?$data->bank_account:"";?></p>
                                        </div>
										<div class="form-group">
                                            <label>驗證狀況</label>
											<p> <?=isset($data->verify)?$verify_list[$data->verify]:"" ?></p>
                                        </div>
                                       <div class="form-group">
                                            <label>正面</label>
											<p> <img src="<?=$data->front_image ?>" height="300" width="auto"></p>
                                        </div>
										<div class="form-group">
                                            <label>反面</label>
											<p> <img src="<?=$data->back_image ?>" height="300" width="auto"></p>
                                        </div>
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

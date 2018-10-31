
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
										<label>姓名</label>
										<p class="form-control-static"><?=isset($content['name'])?$content['name']:""?></p>
									</div>
									<div class="form-group">
										<label>身分證字號</label>
										<p class="form-control-static"><?=isset($content['id_number'])?$content['id_number']:""?></p>
									</div>
									<div class="form-group">
										<label>發證日期</label>
										<p class="form-control-static"><?=isset($content['id_card_date'])?$content['id_card_date']:""?></p>
									</div>
									<div class="form-group">
										<label>發證地點</label>
										<p class="form-control-static"><?=isset($content['id_card_place'])?$content['id_card_place']:""?></p>
									</div>
									<div class="form-group">
										<label>生日</label>
										<p class="form-control-static"><?=isset($content['birthday'])?$content['birthday']:""?></p>
									</div>
									<div class="form-group">
										<label>地址</label>
										<p class="form-control-static"><?=isset($content['address'])?$content['address']:""?></p>
									</div>									
									<div class="form-group">
										<label>備註</label>
										<p class="form-control-static">
										<? 
											if($remark){
												if($remark["error"]){
													echo "錯誤：".$remark["error"]."<br>";
												}
												if($remark["OCR"]){
													echo "OCR：<br>";
												}
												if($remark["face_count"]){
													echo "個人照數量：";
													echo isset($remark["face_count"]["person_count"])?$remark["face_count"]["person_count"]:0;
													echo "<br>";
													echo "證件照數量：";
													echo isset($remark["face_count"]["front_count"])?$remark["face_count"]["front_count"]:0;
													echo "<br>";
												}
												
												if($remark["face"] && is_array($remark["face"])){
													echo "照片比對結果：";
													foreach($remark["face"] as $key => $value){
														echo $value."% ";
													}
												}
											}
										?>
										</p>
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
											<label for="disabledSelect">身分證正面照</label><br>
											<a href="<?=isset($content['front_image'])?$content['front_image']:""?>" data-fancybox="images">
												<img src="<?=isset($content['front_image'])?$content['front_image']:""?>" style='width:30%;max-width:400px'>
											</a>
										</div>
										<div class="form-group">
											<label for="disabledSelect">身分證背面照</label><br>
											<a href="<?=isset($content['back_image'])?$content['back_image']:""?>" data-fancybox="images">
												<img src="<?=isset($content['back_image'])?$content['back_image']:""?>" style='width:30%;max-width:400px'>
											</a>
										</div>
										<div class="form-group">
											<label for="disabledSelect">本人照</label><br>
											<a href="<?=isset($content['person_image'])?$content['person_image']:""?>" data-fancybox="images">
												<img src="<?=isset($content['person_image'])?$content['person_image']:""?>" style='width:30%;max-width:400px'>
											</a>
										</div>
										<div class="form-group">
											<label for="disabledSelect">健保卡照</label><br>
											<a href="<?=isset($content['healthcard_image'])?$content['healthcard_image']:""?>" data-fancybox="images">
												<img src="<?=isset($content['healthcard_image'])?$content['healthcard_image']:""?>" style='width:30%;max-width:400px'>
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

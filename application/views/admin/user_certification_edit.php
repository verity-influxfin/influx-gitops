
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
                                            <label>會員 ID</label>
											<a class="fancyframe" href="<?=admin_url('User/display?id='.$data->user_id) ?>" >
												<p><?=isset($data->user_id)?$data->user_id:"" ?></p>
											</a>
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
												<label><?=isset($user_meta_fields[$key])?$user_meta_fields[$key]:$key; ?></label>
												<? if(substr($key,-5,5)=="image"){?>
													<a href="<?=$value?>" data-fancybox="images"><img src="<?=$value?>" style='width:30%;max-width:400px'></a>
												<? }else if($key=='system'){?>
													<p><?=$school_system[$value]?></p>
												<? }else{?>
													<p><?=$value?></p>
												<? }?>
											</div>
										<?
											}}
										?>
										<div class="form-group">
                                            <label>備註</label>
											<p>
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
										<?// if($data->status!=1){ ?>
											<div class="form-group">
												<label>審核</label>
												<select name="status">
													<? foreach($status_list as $key => $value){ ?>
													<option value="<?=$key?>" <?=$data->status==$key?"selected":""?>><?=$value?></option>
													<? } ?>
												</select>
											</div>
											<button type="submit" class="btn btn-default">Submit Button</button>
										<?// } ?>
                                        
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

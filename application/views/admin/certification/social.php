
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
										<label>認證類型</label>
										<p class="form-control-static"><?=isset($content['type'])?$content['type']:""?></p>
									</div>
									<div class="form-group">
										<label>access_token</label>
										<p class="form-control-static"><?=isset($content['access_token'])?$content['access_token']:""?></p>
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
								<? if($content['type']=='instagram'){
										$info = isset($content['info'])?$content['info']:array();
									?>
										<table style="text-align: center;width:100%" >
											<tr>
												<td rowspan="2">
												<a href="<?=isset($info['picture'])?$info['picture']:""?>" data-fancybox="images">
													<img src="<?=isset($info['picture'])?$info['picture']:""?>" alt="<?=isset($info['username'])?$info['username']:""?> 的大頭貼照">
												</a>
												</td>
												<td><a href="<?=isset($info['link'])?$info['link']:""?>" target="_blank"><h1><?=isset($info['username'])?$info['username']:""?></h1></a></td>
											</tr>
											<tr>
												<td><h4>
												<?=isset($info['counts']['media'])?$info['counts']['media']:"0"?> 貼文 、
												<?=isset($info['counts']['followed_by'])?$info['counts']['followed_by']:"0"?> 位追蹤者 、
												<?=isset($info['counts']['follows'])?$info['counts']['follows']:"0"?> 追蹤中 、</h4></td>
											</tr>
										</table>

									<? } ?>
								</div>
								<? if(isset($info['meta']) && count($info['meta'])>0){
									foreach($info['meta'] as $key => $value){
								?>
									<div class="col-lg-3">
										<p>讚數：<?=isset($value['likes'])?$value['likes']:""?>、發布日期：<?=isset($value['created_time'])?date("Y-m-d H:i:s",$value['created_time']):""?></p>
										<a href="<?=isset($value['picture'])?$value['picture']:""?>" data-fancybox="images">
											<img style="width:100%" src="<?=isset($value['picture'])?$value['picture']:""?>" >
										</a>
										<p><?=isset($value['text'])?$value['text']:""?></p>
									</div>
								<?}}else{ echo '<h4>無貼文</h4>';}?>
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

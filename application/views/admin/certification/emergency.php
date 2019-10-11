		<script type="text/javascript">
			function check_fail(){
				var status = $('#status :selected').val();
				if(status==2){
					$('#fail_div').show();
				}else{
					$('#fail_div').hide();
				}
			}
            $(document).off("change","select#fail").on("change","select#fail" ,  function(){
                var sel=$(this).find(':selected');
                $('input#fail').css('display',sel.attr('value')=='other'?'block':'none');
                $('input#fail').attr('disabled',sel.attr('value')=='other'?false:true);
            });
		</script>
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
										<label>緊急聯絡人姓名</label><br />
                                        <?
                                        if($content['name']==''&&$data->status==3){
                                            echo '<form role="form" method="post">
                                <div class="form-group">
                                    <input type="text" name="name" value="' . $content['name'] . '" />
                                    <input type="hidden" name="id" value="' . $data->id . '" >
                                    <input type="hidden" name="from" value="' . $from . '" >
                                </div>
                                <button type="submit" class="btn btn-primary">確認姓名</button>
                                </form><br />';
                                        }
                                        else {
                                            echo '<p class="form-control-static">'.isset($content['name'])?$content['name']:"".'</p>';
                                        }
                                        ?>
									</div>
									<div class="form-group">
										<label>緊急聯絡人電話</label>
										<p class="form-control-static"><?=isset($content['phone'])?$content['phone']:""?></p>
									</div>
									<div class="form-group">
										<label>緊急聯絡人關係</label>
										<p class="form-control-static"><?=isset($content['relationship'])?$content['relationship']:""?></p>
										<? if($content['relationship']=='監護人'){ ?>
										<label>戶口名簿照片</label>
												<? if(array_key_exists('household_image',$content)){?>
												<p href="<?=isset($content['household_image'])?$content['household_image']:""?>" data-fancybox="images">
		                                        <img src="<?=isset($content['household_image'])?$content['household_image']:""?>" style='width:30%;max-width:400px'></p>
												<? }else{  
													echo '<p style="color:red;" class="form-control-static">尚未上傳戶口名簿照片</p>';
											  }  ?>
										<? }  ?>
									</div>
                                    <div class="form-group">
                                        <label>審核狀態</label>
                                        <p class="form-control-static"><?=isset($data->sys_check)&&$data->sys_check==0?"人工":"系統"?></p>
                                    </div>
									<div class="form-group">
										<label>備註</label>
										<? 
											if($remark){
												if(isset($remark["fail"]) && $remark["fail"]){
													echo '<p style="color:red;" class="form-control-static">失敗原因：'.$remark["fail"].'</p>';
												}
											}
										?>
									</div>	
									<h4>審核</h4>
                                    <form role="form" method="post">
                                        <fieldset>
       										<div class="form-group">
												<select id="status" name="status" class="form-control" onchange="check_fail();" >
													<? foreach($status_list as $key => $value){ ?>
													<option value="<?=$key?>" <?=$data->status==$key?"selected":""?>><?=$value?></option>
													<? } ?>
												</select>
												<input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"";?>" >
												<input type="hidden" name="from" value="<?=isset($from)?$from:"";?>" >
											</div>
											<div class="form-group" id="fail_div" style="display:none">
												<label>失敗原因</label>
                                                <select id="fail" name="fail" class="form-control">
                                                    <option value="" disabled selected>選擇回覆內容</option>
                                                    <? foreach($certifications_msg[5] as $key => $value){ ?>
                                                        <option <?=$data->status==$value?"selected":""?>><?=$value?></option>
                                                    <? } ?>
                                                    <option value="other">其它</option>
                                                </select>
                                                <input type="text" class="form-control" id="fail" name="fail" value="<?=$remark && isset($remark["fail"])?$remark["fail"]:"";?>" style="background-color:white!important;display:none" disabled="false">
											</div>
											<button type="submit" class="btn btn-primary" <?=$content['name']==''&&$data->status==3?'disabled':''; ?>>送出</button>
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

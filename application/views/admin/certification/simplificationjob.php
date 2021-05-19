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
                                        <label>被保險人勞保異動查詢日期</label>
                                        <p class="form-control-static"><?=isset($content['LaborQryDate'])?$content['LaborQryDate']:""?></p>
                                    </div>
                                    <div class="form-group">
                                        <label>最近期投保薪資</label>
                                        <p class="form-control-static"><?=isset($content['LaborInsSalary'])?$content['LaborInsSalary']:""?></p>
                                    </div>
                                    <? if(isset($content['labor_type'])){?>
                                        <div class="form-group">
                                            <label>勞保卡</label><br>
                                            <p><?= isset($content['labor_type']) && $content['labor_type'] ? '電子郵件交件' : '紙本交件' ?></p>
                                            <? if($content['labor_type']==1){?>
                                                    <? if (!empty($content['pdf_file'])) { ?>
                                                        <a href="<?= isset($content['pdf_file']) ? $content['pdf_file'] : ""?>" target="_blank">下載</a>
                                                    <? }else{?>
                                                        <p>尚未收到回信PDF</p>
                                            <?}?>
                                        </div>
                                        <?}} ?>
                                    <br />
                                    <div class="form-group">
										<label>備註</label>

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
                                                    <? foreach($certifications_msg[501] as $key => $value){ ?>
                                                        <option <?=$data->status==$value?"selected":""?>><?=$value?></option>
                                                    <? } ?>
                                                    <option value="other">其它</option>
                                                </select>
                                                <input type="text" class="form-control" id="fail" name="fail" value="<?=$remark && isset($remark["fail"])?$remark["fail"]:"";?>" style="background-color:white!important;display:none" disabled="false">
											</div>
											<button type="submit" class="btn btn-primary">送出</button>
                                        </fieldset>
                                    </form>
                                </div>
								<div class="col-lg-6">
                                    <h1>圖片</h1>
									<fieldset disabled>
                                        <? if (isset($content['labor_image'])) {
                                            echo '<h4>【勞保異動明細】</h4><div class="form-group"><label for="disabledSelect">勞保異動明細</label><br>';
                                            foreach($content['labor_image'] as $key => $value){
                                                echo'<a href="'.$value.'" data-fancybox="images"><img src="'.$value.'" style="width:30%;max-width:400px"></a>';
                                            }
                                            echo '</div><br /><br /><br />';
                                        }?>
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

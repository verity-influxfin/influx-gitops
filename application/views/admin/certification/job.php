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
                                        <label>公司</label>
                                        <p class="form-control-static"><?=isset($content['tax_id'])?$content['tax_id']:""?></p>
                                    <?
                                    if($content['company']==''&&$data->status==3){
                                        echo '<form role="form" method="post">
                                        <div class="form-group">
                                            <input type="text" name="company" value="' . $content['company'] . '" />
                                            <input type="hidden" name="id" value="' . $data->id . '" >
                                            <input type="hidden" name="from" value="' . $from . '" >
                                        </div>
                                        <button type="submit" class="btn btn-primary">確認公司名</button>
                                        </form><br />';
                                    }
                                    else {
                                        echo '<p class="form-control-static">'.isset($content['company'])?$content['company']:"".'</p>';
                                    }
                                     ?>
									</div>
									<div class="form-group">
										<label>公司類型</label>
										<p class="form-control-static"><?=isset($content['industry'])?$industry_name[$content['industry']]:""?></p>
									</div>
									<div class="form-group">
										<label>企業規模</label>
										<p class="form-control-static"><?=isset($content['employee'])?$employee_range[$content['employee']]:""?></p>
									</div>
									<div class="form-group">
										<label>職位</label>
										<p class="form-control-static"><?=isset($content['position'])?$position_name[$content['position']]:""?></p>
									</div>
									<div class="form-group">
										<label>職務性質</label>
										<p class="form-control-static"><?=isset($content['type'])?$job_type_name[$content['type']]:""?></p>
									</div>
									<div class="form-group">
										<label>畢業以來的工作期間</label>
										<p class="form-control-static"><?=isset($content['seniority'])?$seniority_range[$content['seniority']]:""?></p>
									</div>
									<div class="form-group">
										<label>此公司工作期間</label>
										<p class="form-control-static"><?=isset($content['job_seniority'])?$seniority_range[$content['job_seniority']]:""?></p>
									</div>
                                    <form role="form" method="post">
                                        <div class="form-group">
                                            <label>月薪</label><br />
                                            <input type="text" name="salary" value="<?=isset($content['salary'])?$content['salary']:""?>" />
                                            <input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"";?>" >
                                            <input type="hidden" name="from" value="<?=isset($content['salary'])?$content['salary']:"";?>" >
                                        </div>
                                        <button type="submit" class="btn btn-primary">修改月薪</button>
                                    </form><br /><br />
									<?
										if (isset($content['incomeDate'])) {
											echo '
												<div class="form-group">
													<label>發薪日</label>
													<p class="form-control-static">' . $content['incomeDate'] . '號</p>
												</div>
											';
										}
									?>
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
									<div class="form-group">
										<label>工作認證PDF連結</label><br>
										<? if (!empty($content['pdf_file'])) { ?>
											<a href="<?= isset($content['pdf_file']) ? $content['pdf_file'] : ""?>" target="_blank">下載
											</a>
										<? }else{?>
											<p>無上傳檔案</p>
										<?} ?>
									</div>
									<h4>審核</h4>
                                        <form role="form" method="post">
                                        <fieldset>
       										<div class="form-group">
												<? if($data->status==1){?>
												<div class="form-group">
												<label>專業加分</label>
													<p><?=isset($content['license_status'])&&$content['license_status']==1?"專業證書加分":"專業證書不加分"?></p>
												</div>
												<?}else{?>
												<div class="form-group">
												<label>專業加分</label>
													<select id="license_status" name="license_status" class="form-control">
														<option value="0" <?=isset($content['license_status'])&&$content['license_status']==0?"selected":""?>>專業證書不加分</option>
														<option value="1" <?=isset($content['license_status'])&&$content['license_status']==1?"selected":""?>>專業證書加分</option>
													</select>
												</div>
												<?}?>
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
                                                    <? foreach($certifications_msg[10] as $key => $value){ ?>
                                                        <option <?=$data->status==$value?"selected":""?>><?=$value?></option>
                                                    <? } ?>
                                                    <option value="other">其它</option>
                                                </select>
                                                <input type="text" class="form-control" id="fail" name="fail" value="<?=$remark && isset($remark["fail"])?$remark["fail"]:"";?>" style="background-color:white!important;display:none" disabled="false">
											</div>
											<button type="submit" class="btn btn-primary" <?=$content['company']==''&&$data->status==3?'disabled':''; ?>>送出</button>
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
                                            echo '<br /><br /><br />';
                                        }?>

                                        <? if (isset($content['income_prove_image'])||isset($content['passbook_image'])||isset($content['passbook_cover_image'])) {
                                            echo '<h4>【薪資證明】</h4>';
                                            if (isset($content['income_prove_image'])) {
                                                echo '<div class="form-group"><label for="disabledSelect">名片/工作證明</label><br>';
                                                foreach($content['income_prove_image'] as $key => $value){
                                                    echo'<a href="'.$value.'" data-fancybox="images"><img src="'.$value.'" style="width:30%;max-width:400px"></a>';
                                                }
                                            }
                                            if (isset($content['passbook_image'])) {
                                                echo '<div class="form-group"><label for="disabledSelect">存摺內頁照</label><br>';
                                                foreach($content['passbook_image'] as $key => $value){
                                                    echo'<a href="'.$value.'" data-fancybox="images"><img src="'.$value.'" style="width:30%;max-width:400px"></a>';
                                                }
                                            }
                                            if (isset($content['passbook_cover_image'])) {
                                                echo '<div class="form-group"><label for="disabledSelect">專業證照</label><br>';
                                                foreach($content['passbook_cover_image'] as $key => $value){
                                                    echo'<a href="'.$value.'" data-fancybox="images"><img src="'.$value.'" style="width:30%;max-width:400px"></a>';
                                                }
                                            }
                                            echo '<br /><br /><br />';
                                        }?>

                                        <? if (isset($content['business_image'])||isset($content['auxiliary_image'])||isset($content['license_image'])) {
                                            echo '<h4>【其他輔助證明】</h4>';
                                            if (isset($content['business_image'])) {
                                                !is_array($content['business_image'])?$content['business_image']=[$content['business_image']]:'';
                                                echo '<div class="form-group"><label for="disabledSelect">名片正反面</label><br>';
                                                foreach($content['business_image'] as $key => $value){
                                                    echo'<a href="'.$value.'" data-fancybox="images"><img src="'.$value.'" style="width:30%;max-width:400px"></a>';
                                                }
                                            }
                                            if (isset($content['auxiliary_image'])) {
                                                echo '<div class="form-group"><label for="disabledSelect">最近年度報稅扣繳憑證</label><br>';
                                                foreach($content['auxiliary_image'] as $key => $value){
                                                    echo'<a href="'.$value.'" data-fancybox="images"><img src="'.$value.'" style="width:30%;max-width:400px"></a>';
                                                }
                                            }
                                            if (isset($content['license_image'])) {
                                                !is_array($content['license_image'])?$content['license_image']=[$content['license_image']]:'';
                                                echo '<div class="form-group"><label for="disabledSelect">其他專業證明證照</label><br>';
                                                foreach($content['license_image'] as $key => $value){
                                                    echo'<a href="'.$value.'" data-fancybox="images"><img src="'.$value.'" style="width:30%;max-width:400px"></a>';
                                                }
                                            }
                                            echo '<br /><br /><br />';
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

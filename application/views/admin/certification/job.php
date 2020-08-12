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
                                        </form>';
                                    }
                                    else {
                                        echo '<p class="form-control-static">'.(isset($content['company'])?$content['company']:"").'</p>';
                                    } ?>
                                    </div>
                                    <?
                                    if(isset($content['company_address'])) {
                                        echo '<div class="form-group"><label>公司名稱</label><p class="form-control-static"> '.(isset($content['company_address'])?$content['company_address']:"").'</p></div>';
                                    }
                                    if(isset($content['company_phone_number'])) {
                                        echo '<div class="form-group"><label>公司電話</label><p class="form-control-static"> '.(isset($content['company_phone_number'])?$content['company_phone_number']:"").'</p></div>';
                                    }
                                     ?>


									<div class="form-group">
										<label>公司類型</label>
										<p class="form-control-static"><?=isset($content['industry'])?$industry_name[$content['industry']]:""?></p>
									</div>
									<div class="form-group">
										<label>工作職稱</label>
										<p class="form-control-static"><? echo isset($job_title)?$job_title:"無資料"; ?></p>
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
                                    <? if($data->status==1){?>
                                    <div class="form-group">
                                        <label>月薪</label><br />
                                        <p class="form-control-static"><?=isset($content['salary'])?$content['salary']:""?></p>
                                    </div>
                                    <?}else{?>
                                        <form role="form" method="post">
                                            <div class="form-group">
                                                <label>月薪</label><br />
                                                <input type="text" name="salary" value="<?=isset($content['salary'])?$content['salary']:""?>" />
                                                <input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"";?>" >
                                                <input type="hidden" name="from" value="<?=isset($content['salary'])?$content['salary']:"";?>" >
                                            </div>
                                            <button type="submit" class="btn btn-primary">修改月薪</button>
                                        </form><br />
                                    <? } ?>
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
                                    <form role="form" method="post">
                                        <div class="form-group">
                                            <label>專業證書加分 (最高6級)</label>
                                            <? if($data->status==1){?>
                                                <p><?=isset($content['license_status'])&&$content['license_status']>0?$content['license_status']."級":"專業證書不加分"?></p>
                                            <?}else{?>
                                                <select name="license_status" class="form-control">
                                                    <option value="0" <?=isset($content['license_status'])&&$content['license_status']==0?"selected":""?>>不加分</option>
                                                    <option value="1" <?=isset($content['license_status'])&&$content['license_status']==1?"selected":""?>>1級</option>
                                                    <option value="2" <?=isset($content['license_status'])&&$content['license_status']==2?"selected":""?>>2級</option>
                                                    <option value="3" <?=isset($content['license_status'])&&$content['license_status']==3?"selected":""?>>3級</option>
                                                    <option value="4" <?=isset($content['license_status'])&&$content['license_status']==4?"selected":""?>>4級</option>
                                                    <option value="5" <?=isset($content['license_status'])&&$content['license_status']==5?"selected":""?>>5級</option>
                                                    <option value="6" <?=isset($content['license_status'])&&$content['license_status']==6?"selected":""?>>6級</option>
                                                </select>
                                            <?}?>
                                        </div>
                                        <div class="form-group">
                                            <label>競賽作品加分 (最高4級)</label>
                                            <? if($data->status==1){?>
                                                <p><?=isset($content['game_work_level'])&&$content['game_work_level']>0?$content['game_work_level']."級":"競賽作品不加分"?></p>
                                            <?}else{?>
                                                <select name="game_work_level" class="form-control">
                                                    <option value="0" <?=isset($content['game_work_level'])&&$content['game_work_level']==0?"selected":""?>>不加分</option>
                                                    <option value="1" <?=isset($content['game_work_level'])&&$content['game_work_level']==1?"selected":""?>>1級</option>
                                                    <option value="2" <?=isset($content['game_work_level'])&&$content['game_work_level']==2?"selected":""?>>2級</option>
                                                    <option value="3" <?=isset($content['game_work_level'])&&$content['game_work_level']==3?"selected":""?>>3級</option>
                                                    <option value="4" <?=isset($content['game_work_level'])&&$content['game_work_level']==4?"selected":""?>>4級</option>
                                                </select>
                                            <?}?>
                                        </div>
                                        <div class="form-group">
                                            <label>專家調整 (最高5級)</label>
                                            <? if($data->status==1){?>
                                                <p><?=isset($content['pro_level'])&&$content['pro_level']>0?$content['pro_level']."級":"專家調整不加分"?></p>
                                            <?}else{?>
                                                <select name="pro_level" class="form-control">
                                                    <option value="0" <?=isset($content['pro_level'])&&$content['pro_level']==0?"selected":""?>>不加分</option>
                                                    <option value="1" <?=isset($content['pro_level'])&&$content['pro_level']==1?"selected":""?>>1級</option>
                                                    <option value="2" <?=isset($content['pro_level'])&&$content['pro_level']==2?"selected":""?>>2級</option>
                                                    <option value="3" <?=isset($content['pro_level'])&&$content['pro_level']==3?"selected":""?>>3級</option>
                                                    <option value="4" <?=isset($content['pro_level'])&&$content['pro_level']==4?"selected":""?>>4級</option>
                                                    <option value="5" <?=isset($content['pro_level'])&&$content['pro_level']==5?"selected":""?>>5級</option>
                                                </select>
                                            <?}?>
                                        </div><br />
                                    <div class="form-group">
										<label>備註</label>

									</div>
									<h4>審核</h4>
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
                                        <? if ($data->status!=4) { ?>
                                            <? if (isset($content['labor_image'])) {
                                                echo '<h4>【勞保異動明細】</h4><div class="form-group"><label for="disabledSelect">勞保異動明細</label><br>';
                                                foreach($content['labor_image'] as $key => $value){
                                                    echo'<a href="'.$value.'" data-fancybox="images"><img src="'.$value.'" style="width:30%;max-width:400px"></a>';
                                                }
                                                echo '</div><br /><br /><br />';
                                            }?>

                                            <? if (isset($content['income_prove_image'])||isset($content['passbook_image'])||isset($content['passbook_cover_image'])) {
                                                echo '<h4>【薪資證明】</h4>';
                                                if (isset($content['income_prove_image'])) {
                                                    echo '<div class="form-group"><label for="disabledSelect">名片/工作證明</label><br>';
                                                    foreach($content['income_prove_image'] as $key => $value){
                                                        echo'<a href="'.$value.'" data-fancybox="images"><img src="'.$value.'" style="width:30%;max-width:400px"></a>';
                                                    }
                                                    echo '</div>';
                                                }
                                                if (isset($content['passbook_image'])) {
                                                    echo '<div class="form-group"><label for="disabledSelect">存摺內頁照</label><br>';
                                                    foreach($content['passbook_image'] as $key => $value){
                                                        echo'<a href="'.$value.'" data-fancybox="images"><img src="'.$value.'" style="width:30%;max-width:400px"></a>';
                                                    }
                                                    echo '</div>';
                                                }
                                                if (isset($content['passbook_cover_image'])) {
                                                    echo '<div class="form-group"><label for="disabledSelect">存摺封面照</label><br>';
                                                    foreach($content['passbook_cover_image'] as $key => $value){
                                                        echo'<a href="'.$value.'" data-fancybox="images"><img src="'.$value.'" style="width:30%;max-width:400px"></a>';
                                                    }
                                                    echo '</div>';
                                                }
                                                echo '<br /><br /><br />';
                                            }?>

                                            <? if (isset($content['business_image'])||isset($content['auxiliary_image'])||isset($content['license_image'])||isset($content['programming_language'])||isset($content['pro_certificate'])||isset($content['game_work'])) {
                                                echo '<h4>【其他輔助證明】</h4>';
                                                if (isset($content['programming_language'])) {
                                                    echo '<div class="form-group"><label for="disabledSelect">專業語言</label><br>';
                                                    if ($techie_lang) {
                                                        echo '程式語言：'.implode('、',$techie_lang).'<br/>';
                                                    }
                                                    if ($other_lang) {
                                                        echo '程式語言(自填)：'.implode('、',$other_lang);
                                                    }
                                                    echo '</div>';
                                                }
                                                if (isset($content['business_image'])) {
                                                    !is_array($content['business_image'])?$content['business_image']=[$content['business_image']]:'';
                                                    echo '<div class="form-group"><label for="disabledSelect">名片正反面</label><br>';
                                                    foreach($content['business_image'] as $key => $value){
                                                        echo'<a href="'.$value.'" data-fancybox="images"><img src="'.$value.'" style="width:30%;max-width:400px"></a>';
                                                    }
                                                    echo '</div>';
                                                }
                                                if (isset($content['auxiliary_image'])) {
                                                    echo '<div class="form-group"><label for="disabledSelect">最近年度報稅扣繳憑證</label><br>';
                                                    foreach($content['auxiliary_image'] as $key => $value){
                                                        echo'<a href="'.$value.'" data-fancybox="images"><img src="'.$value.'" style="width:30%;max-width:400px"></a>';
                                                    }
                                                    echo '</div>';
                                                }
                                                if (isset($content['license_image'])) {
                                                    !is_array($content['license_image'])?$content['license_image']=[$content['license_image']]:'';
                                                    echo '<div class="form-group"><label for="disabledSelect">其他專業證明證照</label><br>';
                                                    $arr_license_desc = explode(',',$content['license_des']);
                                                    foreach($content['license_image'] as $key => $value){
                                                        echo'<a href="'.$value.'" data-fancybox="images"><img src="'.$value.'" style="width:30%;max-width:400px"></a><br>';
                                                         echo '圖片說明：'.(isset($arr_license_desc[$key])&&!empty($arr_license_desc[$key])?$arr_license_desc[$key]:'未填寫說明')."<br><br>";
                                                    }
                                                    echo '</div>';
                                                }
                                                if (isset($content['pro_certificate_image'])) {
                                                    echo '<div class="form-group"><label for="disabledSelect"><h4>專業證書</h4></label><br>';
                                                    $arr_pro_certificate = explode(',',$content['pro_certificate']);
                                                    foreach($content['pro_certificate_image'] as $key => $value){
                                                        echo'<a href="'.$value.'" data-fancybox="images"><img src="'.$value.'" style="width:30%;max-width:400px"></a><br>';
                                                        echo '圖片說明：'.(isset($arr_pro_certificate[$key])&&!empty($arr_pro_certificate[$key])?$arr_pro_certificate[$key]:'未填寫說明')."<br><br>";
                                                    }
                                                    echo '</div><br />';
                                                }
                                                if (isset($content['game_work_image'])) {
                                                    echo '<div class="form-group"><label for="disabledSelect"><h4>競賽作品</h4></label><br>';
                                                    $arr_game_work = explode(',',$content['game_work']);
                                                    foreach($content['game_work_image'] as $key => $value){
                                                        echo'<a href="'.$value.'" data-fancybox="images"><img src="'.$value.'" style="width:30%;max-width:400px"></a><br>';
                                                        echo '圖片說明：'.(isset($arr_game_work[$key])&&!empty($arr_game_work[$key])?$arr_game_work[$key]:'未填寫說明')."<br><br>";
                                                    }
                                                    echo '</div>';
                                                }
                                                echo '<br /><br /><br />';
                                            }?>
                                        <? } ?>

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

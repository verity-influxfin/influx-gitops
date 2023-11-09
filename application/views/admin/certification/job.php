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
                                        <label>公司統編</label>
                                        <p class="form-control-static"><?=isset($content['tax_id'])?$content['tax_id']:""?></p>
                                    </div>
                                    <div class="form-group">
                                        <label>公司名稱</label>
                                        <p class="form-control-static"><?=(isset($content['company'])?$content['company']:"")?></p>
                                    </div>
                                    <?
                                    if(isset($content['company_address'])) {
                                        echo '<div class="form-group"><label>公司地址</label><p class="form-control-static"> '.(isset($content['company_address'])?$content['company_address']:"").'</p></div>';
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
									<div class="form-group">
										<label>商業司查詢資料</label>
										<table>
											<tbody>
												<tr>
													<td>商業司查詢統一編號</td>
													<td><?= isset($content['gcis_info']['Business_Accounting_NO']) ? $content['gcis_info']['Business_Accounting_NO'] : '' ?></td>
												</tr>
												<tr>
													<td>設立狀況</td>
													<td><?= isset($content['gcis_info']['Company_Status_Desc']) ? $content['gcis_info']['Company_Status_Desc'] : '' ?></td>
												</tr>
												<tr>
													<td>公司名稱</td>
													<td><?= isset($content['gcis_info']['Company_Name']) ? $content['gcis_info']['Company_Name'] : '' ?></td>
												</tr>
												<tr>
													<td>資本總額</td>
													<td><?= isset($content['gcis_info']['Capital_Stock_Amount']) ? $content['gcis_info']['Capital_Stock_Amount'] : '' ?></td>
												</tr>
												<tr>
													<td>實收資本額</td>
													<td><?= isset($content['gcis_info']['Paid_In_Capital_Amount']) ? $content['gcis_info']['Paid_In_Capital_Amount'] : '' ?></td>
												</tr>
												<tr>
													<td>負責人</td>
													<td><?= isset($content['gcis_info']['Responsible_Name']) ? $content['gcis_info']['Responsible_Name'] : '' ?></td>
												</tr>
												<tr>
													<td>地址</td>
													<td><?= isset($content['gcis_info']['Company_Location']) ? $content['gcis_info']['Company_Location'] : '' ?></td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="form-group">
										<label>勞保異動明細資料</label>
										<? if(isset($content['pdf_info'])){ ?>
											<table>
												<tbody>
													<tr>
														<td>姓名</td>
														<td><?= isset($content['pdf_info']['name']) ? $content['pdf_info']['name'] : '' ?></td>
													</tr>
													<tr>
														<td>身分證字號</td>
														<td><?= isset($content['pdf_info']['person_id']) ? $content['pdf_info']['person_id'] : '' ?></td>
													</tr>
													<tr>
														<td>下載日期</td>
														<td><?= isset($content['pdf_info']['report_date']) ? $content['pdf_info']['report_date'] : '' ?></td>
													</tr>
													<tr>
														<td>總工作年資</td>
														<td><?= isset($content['pdf_info']['total_count']) ? floor($content['pdf_info']['total_count']/12).'年'.($content['pdf_info']['total_count']%12).'月' : '0年0月' ?></td>
													</tr>
													<tr>
														<td>目前任職公司年資</td>
														<td><?= isset($content['pdf_info']['this_company_count']) ? floor($content['pdf_info']['this_company_count']/12).'年'.($content['pdf_info']['this_company_count']%12).'月' : '0年0月' ?></td>
													</tr>
												</tbody>
											</table>
											<table>
												<tbody>
													<tr>
														<td>保險證號</td><td>投保公司名稱</td><td>投保薪資</td><td>投保日期</td><td>退保日期</td><td>註記</td>
													</tr>
													<tr>
														<td><?=isset($content['pdf_info']['last_insurance_info']['insuranceId']) ? $content['pdf_info']['last_insurance_info']['insuranceId'] : '' ?></td>
														<td><?=isset($content['pdf_info']['last_insurance_info']['companyName']) ? $content['pdf_info']['last_insurance_info']['companyName'] : '' ?></td>
														<td><?=isset($content['pdf_info']['last_insurance_info']['insuranceSalary']) ? $content['pdf_info']['last_insurance_info']['insuranceSalary'] : '' ?></td>
														<td><?=isset($content['pdf_info']['last_insurance_info']['startDate']) ? $content['pdf_info']['last_insurance_info']['startDate'] : '' ?></td>
														<td><?=isset($content['pdf_info']['last_insurance_info']['endDate']) ? $content['pdf_info']['last_insurance_info']['endDate'] : '' ?></td>
														<td><?=isset($content['pdf_info']['last_insurance_info']['comment']) ? $content['pdf_info']['last_insurance_info']['comment'] : '' ?></td>
													</tr>
												</tbody>
											</table>
										<? } ?>
									</div>
                                    <? if($data->status==1){?>
                                    <div class="form-group">
                                        <label>用戶自填月薪</label><br />
                                        <p class="form-control-static"><?=isset($content['salary'])?$content['salary']:""?></p><br />
                                        <label>最終確認月薪</label><br />
                                        <p class="form-control-static"><?=isset($content['admin_salary'])?$content['admin_salary']:""?></p>
                                    </div>
                                    <div class="form-group">
                                        <label>專業證書是否有效</label><br />
                                        <p class="form-control-static"><?= ($content['job_has_license'] ?? '0') === '1' ? '是' : '否' ?></p>
                                    </div>
                                    <?}else{?>
                                        <form role="form" method="post" action="/admin/certification/user_certification_edit">
                                            <div class="form-group">
                                                <label>用戶自填月薪</label><br />
                                                <p class="form-control-static"><?=isset($content['salary'])?$content['salary']:""?></p><br />
                                                <label>最終確認月薪</label><br />
                                                <input type="text" name="salary" value="<?=isset($content['admin_salary']) ? $content['admin_salary'] : '0' ?>" />
                                                <input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"";?>" >
                                                <input type="hidden" name="from" value="<?=isset($content['admin_salary'])?$content['admin_salary']:"";?>" >
                                            </div>
                                            <button type="submit" class="btn btn-primary">修改月薪</button>
                                        </form><br />
                                        <?php
                                        if (in_array($data->status, [CERTIFICATION_STATUS_PENDING_TO_VALIDATE, CERTIFICATION_STATUS_PENDING_TO_REVIEW]))
                                        { ?>
                                            <form role="form" method="post"
                                                  action="/admin/certification/save_job_meta">
                                                <div class="form-group">
                                                    <label>專業證書是否有效</label><br/>
                                                    <select name="job_has_license">
                                                        <option value="0">否</option>
                                                        <option value="1">是</option>
                                                    </select>
                                                    <input type="hidden" name="id" value="<?= $data->id ?? ''; ?>">
                                                </div>
                                                <button type="submit" class="btn btn-primary">修改專業證書是否有效</button>
                                            </form><br/>
                                        <?php }
                                    } ?>
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
                                        <?php
                                        }
                                    }
                                    elseif ( ! empty($content['pdf_file']))
                                    {
                                        ?>
                                        <div class="form-group">
                                            <label>勞保卡</label><br>
                                            <a href="<?= $content['pdf_file']; ?>" target="_blank">下載</a>
                                        </div>
                                    <?php } ?>

									<div class="form-group">
										<label>驗證結果</label>
										<?
											if($remark && isset($remark['verify_result']) && is_array($remark['verify_result'])){
												foreach($remark['verify_result'] as $verify_result){
													echo'<p style="color:red;">'.$verify_result.'</p>';
												}
											}
										?>
									</div>
                                    <form role="form" method="post" action="/admin/certification/user_certification_edit">
                                    <div class="form-group">
										<label>備註</label>
										<?
										if ($remark) {
											if (isset($remark["fail"]) && $remark["fail"]) {
												echo '<p style="color:red;" class="form-control-static">失敗原因：' . $remark["fail"] . '</p>';
											}
										}
										?>
									</div>
                                    <div class="form-group">
                                        <label>系統審核</label>
                                        <?
                                        if (isset($sys_check)) {
                                            echo '<p class="form-control-static">' . ($sys_check==1?'是':'否') . '</p>';
                                        }
                                        ?>
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
											<button type="submit" class="btn btn-primary" <?=(!isset($content['company'])||$content['company']=='')&&$data->status==3?'disabled':''; ?>>送出</button>
                                        </fieldset>
                                    </form>
                                </div>
								<div class="col-lg-6">
                                    <h1>圖片</h1>
                                    <fieldset disabled>
                                        <div class="form-group">
                                            <?php if ( ! empty($content['ocr_marker']['res']) && $content['ocr_marker']['res'] === TRUE)
                                            { ?>
                                                <label for="disabledSelect">OCR 關鍵字標記</label><br>
                                                <?php
                                                foreach ($content['ocr_marker']['content'] as $value)
                                                {
                                                    if (empty($value['input_kw_mat']) && empty($value['salary_kw_mat']))
                                                    {
                                                        continue;
                                                    } ?>
                                                    <div class="row" style="width: 100%">
                                                        <div class="col-lg-3">
                                                            <a href="<?= $value['url']; ?>" data-fancybox="images">
                                                                <img alt="" src="<?= $value['url']; ?>"
                                                                     style="width:100%;max-width:300px">
                                                            </a>
                                                        </div>
                                                        <div class="col-lg-9">
                                                            <label>身份關鍵字：</label>
                                                            <br/>
                                                            <?php foreach ($value['input_kw_mat'] as $kw_value) {
                                                                echo implode(' ', $kw_value) . '<br/>';
                                                            } ?>
                                                            <label>收入關鍵字：</label>
                                                            <br/>
                                                            <?php foreach ($value['salary_kw_mat'] as $kw_value) {
                                                                echo implode(' ', $kw_value) . '<br/>';
                                                            } ?>
                                                        </div>
                                                    </div>
                                                    <hr/>
                                                <?php }
                                            } ?>
                                        </div>
                                        <? if ($data->status!=4) { ?>
                                            <? if (!isset($content['financial_image'])) { ?>
                                            <? if (isset($content['labor_image'])) { ?>
                                            <?
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

                                            <? if (isset($content['business_image'])||isset($content['auxiliary_image'])||isset($content['programming_language'])||isset($content['pro_certificate'])||isset($content['game_work'])) {
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
                                        <?}else{
                                            echo '<div class="form-group"><label for="disabledSelect">財務收入證明</label><br>';
                                            foreach($content['financial_image'] as $key => $value){
                                                echo'<a href="'.$value.'" data-fancybox="images"><img src="'.$value.'" style="width:30%;max-width:400px"></a>';
                                            }
                                            echo '</div>';
                                          }

                                            if (isset($content['license_image']))
                                            {
                                                $content['license_image'] = ! is_array($content['license_image']) ? [$content['license_image']] : $content['license_image'];
                                                echo '<div class="form-group"><label for="disabledSelect">其他專業證明證照</label><br>';
                                                if (isset($content['license_des']))
                                                {
                                                    $arr_license_desc = explode(',', $content['license_des']);
                                                    foreach ($content['license_image'] as $key => $value)
                                                    {
                                                        echo '<a href="' . $value . '" data-fancybox="images"><img src="' . $value . '" style="width:30%;max-width:400px"></a><br>';
                                                        echo '圖片說明：' . (isset($arr_license_desc[$key]) && ! empty($arr_license_desc[$key]) ? $arr_license_desc[$key] : '未填寫說明') . "<br><br>";
                                                    }
                                                }
                                                echo '</div>';
                                            }

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

        <style>
            table {
                margin-top: 5px;
            }

            table td {
                border: 1px solid;
                padding: 2px 2px 2px 2px;
            }
        </style>
        <script>
            $(document).ready(function () {
                let job_has_license = '<?php echo $job_has_license = $content['job_has_license'] ? 1 : 0; ?>';
                $('select[name="job_has_license"]').val(job_has_license);
            });
        </script>
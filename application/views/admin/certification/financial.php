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
										<label>打工收入</label>
										<p class="form-control-static"><?=isset($content['parttime'])?$content['parttime']:""?></p>
									</div>
									<div class="form-group">
										<label>零用錢收入</label>
										<p class="form-control-static"><?=isset($content['allowance'])?$content['allowance']:""?></p>
									</div>
									<div class="form-group">
										<label>獎學金收入</label>
										<p class="form-control-static"><?=isset($content['scholarship'])?$content['scholarship']:""?></p>
									</div>
									<div class="form-group">
										<label>其他收入</label>
										<p class="form-control-static"><?=isset($content['other_income'])?$content['other_income']:""?></p>
									</div>
									<div class="form-group">
										<label>餐飲支出</label>
										<p class="form-control-static"><?=isset($content['restaurant'])?$content['restaurant']:""?></p>
									</div>
									<div class="form-group">
										<label>交通支出</label>
										<p class="form-control-static"><?=isset($content['transportation'])?$content['transportation']:""?></p>
									</div>
									<div class="form-group">
										<label>網路電信支出</label>
										<p class="form-control-static"><?=isset($content['telegraph_expense'])?$content['telegraph_expense']:""?></p>
									</div>
									<div class="form-group">
										<label>娛樂支出</label>
										<p class="form-control-static"><?=isset($content['entertainment'])?$content['entertainment']:""?></p>
									</div>
									<div class="form-group">
										<label>房租家庭支出</label>
										<p class="form-control-static"><?=isset($content['rent_expenses'])?$content['rent_expenses']:""?></p>
									</div>
									<div class="form-group">
										<label>教育支出</label>
										<p class="form-control-static"><?=isset($content['educational_expenses'])?$content['educational_expenses']:""?></p>
									</div>
									<div class="form-group">
										<label>保險支出</label>
										<p class="form-control-static"><?=isset($content['insurance_expenses'])?$content['insurance_expenses']:""?></p>
									</div>
									<div class="form-group">
										<label>社交支出</label>
										<p class="form-control-static"><?=isset($content['social_expenses'])?$content['social_expenses']:""?></p>
									</div>
									<div class="form-group">
										<label>房貸月繳</label>
										<p class="form-control-static"><?=isset($content['long_assure_monthly_payment'])?$content['long_assure_monthly_payment']:""?></p>
									</div>
									<div class="form-group">
										<label>車貸月繳</label>
										<p class="form-control-static"><?=isset($content['mid_assure_monthly_payment'])?$content['mid_assure_monthly_payment']:""?></p>
									</div>
									<div class="form-group">
										<label>信貸月繳</label>
										<p class="form-control-static"><?=isset($content['credit_monthly_payment'])?$content['credit_monthly_payment']:""?></p>
									</div>
									<div class="form-group">
										<label>助學貸月繳</label>
										<p class="form-control-static"><?=isset($content['student_loans_monthly_payment'])?$content['student_loans_monthly_payment']:""?></p>
									</div>
									<div class="form-group">
										<label>信用卡月繳</label>
										<p class="form-control-static"><?=isset($content['student_loans_monthly_payment'])?$content['student_loans_monthly_payment']:""?></p>
									</div>
									<div class="form-group">
										<label>民間借貸月付</label>
										<p class="form-control-static"><?=isset($content['other_private_borrowing'])?$content['other_private_borrowing']:""?></p>
									</div>
									<div class="form-group">
										<label>其他支出</label>
										<p class="form-control-static"><?=isset($content['other_expense'])?$content['other_expense']:""?></p>
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
                                    <div class="form-group">
                                        <label>系統審核</label>
                                        <?
                                        if (isset($sys_check)) {
                                            echo '<p class="form-control-static">' . ($sys_check==1?'是':'否') . '</p>';
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
												<input type="text" class="form-control" id="fail" name="fail" value="<?=$remark && isset($remark["fail"])?$remark["fail"]:"";?>" >
											</div>
											<button type="submit" class="btn btn-primary">送出</button>
                                        </fieldset>
                                    </form>
                                </div>
								<div class="col-lg-6">
                                    <h1>圖片</h1>
									<fieldset disabled>
										<div class="form-group">
											<label for="disabledSelect">信用卡帳單照</label><br>
											<a href="<?=isset($content['creditcard_image'])?$content['creditcard_image']:""?>" data-fancybox="images">
												<img src="<?=isset($content['creditcard_image'])?$content['creditcard_image']:""?>" style='width:30%;max-width:400px'>
											</a>
										</div>
										<div class="form-group">
											<label for="disabledSelect">存摺內頁照</label><br>
                                            <?
                                            !is_array($content['passbook_image']) ? $content['passbook_image'] = [$content['passbook_image']] : '';
                                            foreach ($content['passbook_image'] as $key => $value) { ?>
                                                <a href="<?=isset($value)?$value:""?>" data-fancybox="images">
                                                    <img src="<?=isset($value)?$value:""?>" style='width:30%;max-width:400px'>
                                                </a>
                                            <? } ?>
										</div>

                                        <? if(isset($content['bill_phone_image'])){ ?>
                                            <div class="form-group">
                                                <label for="disabledSelect">電話帳單</label><br>
                                                <?
                                                !is_array($content['bill_phone_image']) ? $content['bill_phone_image'] = [$content['bill_phone_image']] : '';
                                                foreach ($content['bill_phone_image'] as $key => $value) { ?>
                                                    <a href="<?=isset($value)?$value:""?>" data-fancybox="images">
                                                        <img src="<?=isset($value)?$value:""?>" style='width:30%;max-width:400px'>
                                                    </a>
                                                <? } ?>
                                            </div>
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

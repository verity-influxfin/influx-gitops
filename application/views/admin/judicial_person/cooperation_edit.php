
	<script>
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
				<h1 class="page-header">經銷商帳號申請</h1>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<!-- /.row -->
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-6">
								<div class="form-group">
									<label>申請人 ID</label>
									<a class="fancyframe" href="<?=admin_url('User/display?id='.$data->user_id) ?>" >
										<p><?=isset($data->user_id)?$data->user_id:"" ?></p>
									</a>
								</div>
								<div class="form-group">
									<label>申請人</label>
									<p class="form-control-static"><?=isset($user_info->name)?$user_info->name:"" ?></p>
								</div>
                                <div class="form-group">
                                    <label>法人 User ID</label>
                                    <a class="fancyframe" href="<?= admin_url('User/display?id=' . $data->company_user_id) ?>">
                                        <p><?= isset($data->company_user_id) ? $data->company_user_id : "" ?></p>
                                    </a>
                                </div>
								<div class="form-group">
									<label>統一編號</label>
									<p class="form-control-static"><?=isset($user_info->id_number)?$user_info->id_number:"" ?></p>
								</div>
								<!--<div class="form-group">
									<label>IP列表</label><br>
									<textarea style="width:100%" class="form-control-static"><?=isset($data->cooperation_server_ip)?$data->cooperation_server_ip:"" ?></textarea>
								</div>-->
								<div class="form-group">
									<label>備註</label>
									<p class="form-control-static"><?=isset($data->remark)?$data->remark:"" ?></p>
								</div>
								<h4>審核</h4>
								<form role="form" method="post">
									<fieldset>
                                        <div class="form-group">
                                            <select id="cooperation" name="cooperation" class="form-control" onchange="check_fail();" >
                                                <? foreach($cooperation_list as $key => $value){ ?>
                                                    <option value="<?=$key?>" <?=$data->cooperation==$key?"selected":""?>><?=$value?></option>
                                                <? } ?>
                                            </select>
                                            <input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"";?>" >
                                        </div>
										<button type="submit" class="btn btn-primary">送出</button>
									</fieldset>
								</form>
							</div>
							<div class="col-lg-6">
								<h1>商業司資料</h1>
								<div class="form-group">
									<?if($company_data){?>
										<table class="table table-bordered table-hover table-striped">
											<tbody>
												<tr><td><p class="form-control-static">公司統一編號</p></td><td><p class="form-control-static"><?=isset($company_data['Business_Accounting_NO'])?$company_data['Business_Accounting_NO']:'' ?></p></td></tr>
												<tr><td><p class="form-control-static">公司狀況描述</p></td><td><p class="form-control-static"><?=isset($company_data['Company_Status_Desc'])?$company_data['Company_Status_Desc']:'' ?></p></td></tr>
												<tr><td><p class="form-control-static">公司名稱</p></td><td><p class="form-control-static"><?=isset($company_data['Company_Name'])?$company_data['Company_Name']:'' ?></p></td></tr>
												<tr><td><p class="form-control-static">資本總額(元)</p></td><td><p class="form-control-static"><?=isset($company_data['Capital_Stock_Amount'])?number_format($company_data['Capital_Stock_Amount']):'' ?></p></td></tr>
												<tr><td><p class="form-control-static">實收資本額(元)</p></td><td><p class="form-control-static"><?=isset($company_data['Paid_In_Capital_Amount'])?number_format($company_data['Paid_In_Capital_Amount']):'' ?></p></td></tr>
												<tr><td><p class="form-control-static">代表人姓名</p></td><td><p class="form-control-static"><?=isset($company_data['Responsible_Name'])?$company_data['Responsible_Name']:'' ?></p></td></tr>
												<tr><td><p class="form-control-static">公司登記地址</p></td><td><p class="form-control-static"><?=isset($company_data['Company_Location'])?$company_data['Company_Location']:'' ?></p></td></tr>
												<tr><td><p class="form-control-static">登記機關名稱</p></td><td><p class="form-control-static"><?=isset($company_data['Register_Organization_Desc'])?$company_data['Register_Organization_Desc']:'' ?></p></td></tr>
												<tr><td><p class="form-control-static">核准設立日期</p></td><td><p class="form-control-static"><?=isset($company_data['Company_Setup_Date'])?$company_data['Company_Setup_Date']:'' ?></p></td></tr>
												<tr><td><p class="form-control-static">最後核准變更日期</p></td><td><p class="form-control-static"><?=isset($company_data['Change_Of_Approval_Data'])?$company_data['Change_Of_Approval_Data']:'' ?></p></td></tr>
												<tr><td><p class="form-control-static">撤銷日期</p></td><td><p class="form-control-static"><?=isset($company_data['Revoke_App_Date'])?$company_data['Revoke_App_Date']:'' ?></p></td></tr>
												<tr><td><p class="form-control-static">停復業狀況</p></td><td><p class="form-control-static"><?=isset($company_data['Case_Status'])?$company_data['Case_Status']:'' ?></p></td></tr>
												<tr><td><p class="form-control-static">停復業狀況描述</p></td><td><p class="form-control-static"><?=isset($company_data['Case_Status_Desc'])?$company_data['Case_Status_Desc']:'' ?></p></td></tr>
												<tr><td><p class="form-control-static">停業核准日期</p></td><td><p class="form-control-static"><?=isset($company_data['Sus_App_Date'])?$company_data['Sus_App_Date']:'' ?></p></td></tr>
												<tr><td><p class="form-control-static">停業/延展期間(起)</p></td><td><p class="form-control-static"><?=isset($company_data['Sus_Beg_Date'])?$company_data['Sus_Beg_Date']:'' ?></p></td></tr>
												<tr><td><p class="form-control-static">停業/延展期間(迄)</p></td><td><p class="form-control-static"><?=isset($company_data['Sus_End_Date'])?$company_data['Sus_End_Date']:'' ?></p></td></tr>
											</tbody>
										</table>
									<?}?>
								</div>
								<div class="form-group">
									<?if($shareholders){
									?>
										<table class="table table-bordered table-hover table-striped">
											<tbody>
												<? foreach($shareholders as $key => $value){ ?>
												<tr>
													<td><p class="form-control-static">職稱名稱</p></td>
													<td>
														<p class="form-control-static"><?=isset($value['Person_Position_Name'])?$value['Person_Position_Name']:'' ?></p>
													</td>
													<td><p class="form-control-static">董監事股東姓名</p></td>
													<td>
														<p class="form-control-static"><?=isset($value['Person_Name'])?$value['Person_Name']:'' ?></p>
													</td>
													<td><p class="form-control-static">所代表法人</p></td>
													<td>
														<p class="form-control-static"><?=isset($value['Juristic_Person_Name'])?$value['Juristic_Person_Name']:'' ?></p>
													</td>
													<td><p class="form-control-static">持有股份數</p></td>
													<td>
														<p class="form-control-static"><?=isset($value['Person_Shareholding'])?number_format($value['Person_Shareholding']):'' ?></p>
													</td>
												</tr>
												<?}?>
											</tbody>
										</table>
									<?}?>
								</div>
                                <div class="col-lg-6">
                                    <h1>圖片</h1>
                                    <div class="form-group">
									<? if($content['facade_image']){ ?>
											<label for="disabledSelect">店門正面照</label>
                                                <a href="<?=$content['facade_image']?>" data-fancybox="images">
                                                    <img src="<?=isset($content['facade_image'])?$content['facade_image']:""?>" style='width:100%;max-width:300px'>
                                                </a>
										<? } ?>
                                    </div>
                                    <div class="form-group">
									<? if($content['store_image']){ ?>
											<label for="disabledSelect">店內正面照</label>
											<? foreach($content['store_image'] as $key => $value){ ?>
												<a href="<?=$value ?>" data-fancybox="images">
													<img src="<?=$value ?>" style='width:100%;max-width:300px'>
												</a>
											<? } ?>
									<? } ?>
                                    </div>
                                    <? if(isset($content['front_image'])){ ?>
                                        <div class="form-group">
                                            <label for="disabledSelect">銀行流水帳正面</label>
                                            <a href="<?=$content['front_image']?>" data-fancybox="images">
                                                <img src="<?=$content['front_image']?>" style='width:100%;max-width:300px'>
                                            </a>
                                        </div>
                                    <? } ?>

                                    <? if(isset($content['front_image'])){ ?>
                                        <div class="form-group">
                                            <label for="disabledSelect">銀行流水帳內頁</label>
                                            <? foreach($content['passbook_image'] as $key => $value){ ?>
                                                <a href="<?=$value ?>" data-fancybox="images">
                                                    <img src="<?=$value ?>" style='width:100%;max-width:300px'>
                                                </a>
                                            <? } ?>
                                        </div>
                                    <? } ?>
                                   <? if(isset($content['bankbook_image'])){ ?>
                                    <div class="form-group">
                                    <label for="disabledSelect">存摺封面</label>
                                            <? foreach($content['bankbook_image'] as $key => $value){ ?>
                                                <a href="<?=$value ?>" data-fancybox="images">
                                                    <img src="<?=$value ?>" style='width:100%;max-width:300px'>
                                                </a>
                                            <? } ?>
                                    </div>
                                   <? } ?>
                                    <? if(isset($content['form_401_image'])){ ?>
                                        <div class="form-group">
                                            <label for="disabledSelect">401表格</label>
                                            <? foreach($content['form_401_image'] as $key => $value){ ?>
                                                <a href="<?=$value ?>" data-fancybox="images">
                                                    <img src="<?=$value ?>" style='width:100%;max-width:300px'>
                                                </a>
                                            <? } ?>
                                        </div>
                                    <? } ?>
                                </div>
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

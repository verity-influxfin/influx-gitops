
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
				<h1 class="page-header">法人帳號申請</h1>
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
									<p class="form-control-static"><?=isset($data->user_name)?$data->user_name:"" ?></p>
								</div>
								<div class="form-group">
									<label>統一編號</label>
									<p class="form-control-static"><?=isset($data->tax_id)?$data->tax_id:"" ?></p>
								</div>
								<div class="form-group">
									<label>公司類型</label>
									<p class="form-control-static"><?=isset($company_type[$data->company_type])?$company_type[$data->company_type]:"" ?></p>
								</div>
								<div class="form-group">
									<label>公司名稱</label>
									<p class="form-control-static"><?=isset($data->company)?$data->company:"" ?></p>
								</div>
								<div class="form-group">
									<label>備註</label>
									<p class="form-control-static"><?=isset($data->remark)?$data->remark:"" ?></p>
								</div>	
								<h1>審核</h1>
								<form role="form" method="post">
									<fieldset>
                                        <div class="form-group">
                                            <select id="status" name="status" class="form-control" onchange="check_fail();" >
                                                <? foreach($status_list as $key => $value){ ?>
                                                    <option value="<?=$key?>" <?=$data->status==$key?"selected":""?>><?=$value?></option>
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

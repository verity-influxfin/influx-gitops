<style type="text/css">
body{
	font-family: 微軟正黑體;
}
.barLink a{
    display: inline-block;
    margin: 19px 9px;
    font-size: 20px;
    font-weight: bold;
    border-bottom: 3px #e2e2e2 solid;
    padding: 2px 5px;
    cursor:pointer;
    text-decoration: none;
    color:black;
}
.barLink a:hover{
    border-bottom: 3px #ccc solid;
}
.barLink a.active{
    border-bottom-color: #988977;
    cursor:auto;
}
.panel-heading{
	font-size: 16px;
    letter-spacing: 0.5px;
    font-weight: bold;
}
#page-wrapper{
  margin-left:0px;
}
</style>
	<script>
	
		function form_onsubmit(){
			return true;
		}

	</script>
	
	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<div class="barLink">
					<a class="active">標的資訊</a>
					<a href="<? echo admin_url('risk/push_info')."?id=".$data->id.(isset($slist)?"&slist=1":"") ?>">催收資訊</a>
					<a href="<? echo admin_url('risk/push_audit')."?id=".$data->id.(isset($slist)?"&slist=1":"") ?>">催收審批</a>
				</div>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<!-- /.row -->
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
					標的資訊
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-6">
								<div class="table-responsive">
									<table class="table table-bordered table-hover table-striped">
										<tbody>
											<tr>
												<td style="width: 20%;"><p class="form-control-static">案件 ID</p></td>
												<td>
													<p class="form-control-static"><?=isset($data->id)?$data->id:"";?></p>
												</td>
												<td><p class="form-control-static">案號</p></td>
												<td>
													<p class="form-control-static"><?=isset($data->target_no)?$data->target_no:"";?></p>
												</td>
											</tr>
											<tr>
												<td><p class="form-control-static">產品</p></td>
												<td>
													<p class="form-control-static"><?=isset($data->product_id)?$product_list[$data->product_id]:"";?></p>
												</td>
												<td><p class="form-control-static">申請金額</p></td>
												<td>
													<p class="form-control-static">$<?=isset($data->amount)?$data->amount:"";?></p>
												</td>
											</tr>
											<tr>
												<td><p class="form-control-static">核准金額</p></td>
												<td>
													<p class="form-control-static">$<?=isset($data->loan_amount)?$data->loan_amount:"";?></p>
												</td>
												<td><p class="form-control-static">核可利率</p></td>
												<td>
													<p class="form-control-static"><?=isset($data->interest_rate)?$data->interest_rate:"";?>%</p>
												</td>
											</tr>
											<tr>
												<td><p class="form-control-static">期數</p></td>
												<td>
													<p class="form-control-static"><?=isset($data->instalment)?$instalment_list[$data->instalment]:"";?></p>
												</td>
												<td><p class="form-control-static">還款方式</p></td>
												<td>
													<p class="form-control-static"><?=isset($data->repayment)?$repayment_type[$data->repayment]:"";?></p>
												</td>
											</tr>
											<tr>
												<td><p class="form-control-static">還款虛擬帳號</p></td>
												<td>
													<p class="form-control-static">
													
													<a class="fancyframe" href="<?=admin_url('Passbook/display?virtual_account='.$virtual_account->virtual_account) ?>" ><?=$virtual_account->virtual_account ?></a>
													</p>
												</td>
												<td><p class="form-control-static">備註</p></td>
												<td>
													<p class="form-control-static"><?=isset($data->remark)?$data->remark:"";?></p>
												</td>
											</tr>
											<tr>
												<td><p class="form-control-static">狀態</p></td>
												<td>
													<p class="form-control-static"><?=isset($data->status)?$status_list[$data->status]:"";?></p>
												</td>
												<td><p class="form-control-static">放款狀態</p></td>
												<td>
													<p class="form-control-static"><?=isset($data->loan_status)?$loan_list[$data->loan_status]:"";?> - <?=$bank_account_verify?"金融帳號已驗證":"金融帳號未驗證";?></p>
												</td>
											</tr>
											<tr>
												<td><p class="form-control-static">平台服務費</p></td>
												<td>
													<p class="form-control-static"><?=isset($data->platform_fee)?$data->platform_fee:"";?></p>
												</td>
												<td><p class="form-control-static">放款日期</p></td>
												<td>
													<p class="form-control-static"><?=isset($data->loan_date)?$data->loan_date:"";?></p>
												</td>
											</tr>
											<tr>
												<td><p class="form-control-static">逾期狀態 - 天數</p></td>
												<td>
													<p class="form-control-static"><?=isset($data->delay)?$data->delay.'-'.$data->delay_days:"";?></p>
												</td>
												<td><p class="form-control-static">申請日期</p></td>
												<td>
													<p class="form-control-static"><?=isset($data->created_at)&&!empty($data->created_at)?date("Y-m-d H:i:s",$data->created_at):"";?></p>
												</td>
											</tr>
											<tr>
												<td><p class="form-control-static">簽約照片</p></td>
												<td colspan="3">
													<?=isset($data->person_image)?"<a href='".$data->person_image."' data-fancybox='images'><img src='".$data->person_image."' style='width:30%;'></a>":"";?>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="table-responsive">
									<table class="table table-bordered table-hover" style="text-align:center;">
										<tbody>
											<tr style="background-color:#f5f5f5;">
												<td colspan="8">
													<a class="fancyframe" href="<?=admin_url('User/display?id='.$user_info->id) ?>" >借款人資訊</a>
												</td>
											</tr>
											<tr>
												<td><p class="form-control-static">借款人ID</p></td>
												<td>
													<p class="form-control-static"><?=isset($user_info->id)?$user_info->id:"";?></p>
												</td>
												<td><p class="form-control-static">姓名</p></td>
												<td>
													<p class="form-control-static"><?=isset($user_info->name)?$user_info->name:"";?></p>
												</td>

												<td><p class="form-control-static">Email</p></td>
												<td colspan="3">
													<p class="form-control-static"><?=isset($user_info->email)?$user_info->email:"";?></p>
												</td>

											</tr>
											<tr>
												<td><p class="form-control-static">發證地點</p></td>
												<td>
													<p class="form-control-static"><?=isset($user_info->id_card_place)?$user_info->id_card_place:"";?></p>
												</td>
												<td><p class="form-control-static">發證日期</p></td>
												<td>
													<p class="form-control-static"><?=isset($user_info->id_card_date)?$user_info->id_card_date:"";?></p>
												</td>
												<td><p class="form-control-static">身分證字號</p></td>
												<td>
													<p class="form-control-static"><?=isset($user_info->id_number)?$user_info->id_number:"";?></p>
												</td>
												<td><p class="form-control-static">性別</p></td>
												<td>
													<p class="form-control-static"><?=isset($user_info->sex)?$user_info->sex:"";?></p>
												</td>
											</tr>
											<tr>
												<td><p class="form-control-static">生日</p></td>
												<td>
													<p class="form-control-static"><?=isset($user_info->birthday)?$user_info->birthday:"";?></p>
												</td>
												<td><p class="form-control-static">電話</p></td>
												<td>
													<p class="form-control-static"><?=isset($user_info->phone)?$user_info->phone:"";?></p>
												</td>
												<td><p class="form-control-static">地址</p></td>
												<td colspan="3">
													<p class="form-control-static"><?=isset($user_info->address)?$user_info->address:"";?></p>
												</td>
											</tr>
											<tr>
												<td><p class="form-control-static">借款端帳號</p></td>
												<td>
													<p class="form-control-static"><?=isset($user_info->status)&&$user_info->status?"正常":"未申請";?></p>
												</td>
												<td><p class="form-control-static">出借端帳號</p></td>
												<td>
													<p class="form-control-static"><?=isset($user_info->status)&&$user_info->status?"正常":"未申請";?></p>
												</td>
												<td><p class="form-control-static">註冊日期</p></td>
												<td colspan="3">
													<p class="form-control-static"><?=isset($user_info->created_at)&&!empty($user_info->created_at)?date("Y-m-d H:i:s",$user_info->created_at):"";?></p>
												</td>
											</tr>
											<? if($data->status==5 || $data->status==10){?>
												<tr style="background-color:#f5f5f5;">
													<td colspan="8">
														<a class="fancyframe" href="<?=admin_url('Target/transaction_display?id='.$data->id) ?>" >攤還表</a>
													</td>
												</tr>
												<tr style="background-color:#f5f5f5;">
													<td>期數</td>
													<td>期初本金</td>
													<td>還款日</td>
													<td>日數</td>
													<td>還款本息</td>
													<td>違約延滯</td>
													<td>還款合計</td>
													<td>已還款金額</td>
												</tr>
												<? if($amortization_table){
													foreach($amortization_table["list"] as $key => $value){
												?>
												<tr>
													<td><?=$value['instalment'] ?></td>
													<td><?=$value['remaining_principal'] ?></td>
													<td><?=$value['repayment_date'] ?></td>
													<td><?=$value['days'] ?></td>
													<td><?=$value['principal'] ?><br><?=$value['interest'] ?></td>
													<td style="color:red;"><?=$value['liquidated_damages'] ?><br><?=$value['delay_interest'] ?></td>
													<td><?=$value['total_payment'] ?></td>
													<td><?=$value['repayment'] ?></td>
												</tr>
											<? }}} ?>
										</tbody>
									</table>
								</div>
								<div class="table-responsive">
									<div class="panel panel-default">
										<div class="panel-heading">
											<table width="100%">
												<tr>
													<td colspan="9">圖示說明：</td>
												</tr>
												<tr>
													<td>留空  尚未認證</td>
													<td>
													<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-refresh"></i></button>
													資料更新中
													</td>
													<td>
													<button type="button" class="btn btn-success btn-circle"><i class="fa fa-check"></i> </button>
													認證完成
													</td>
													<td>
													<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i></button>
													已退回
													</td>
													<td>
													<button type="button" class="btn btn-info btn-circle"><i class="fa fa-info"></i></button>
													查看備註
													</td>
												</tr>
											</table>
										</div>
										<!-- /.panel-heading -->
										<div class="panel-body">
											<div class="table-responsive">
												<table class="table table-striped table-bordered table-hover" width="100%" style="text-align: center;">
													<tr role="row"><th class="sorting" tabindex="0" aria-controls="dataTables-tables" rowspan="1" colspan="1" aria-label="實名認證: activate to sort column ascending" style="width: 53px;">實名認證</th><th class="sorting" tabindex="0" aria-controls="dataTables-tables" rowspan="1" colspan="1" aria-label="學生身份認證: activate to sort column ascending" style="width: 79px;">學生身份認證</th><th class="sorting" tabindex="0" aria-controls="dataTables-tables" rowspan="1" colspan="1" aria-label="金融帳號認證: activate to sort column ascending" style="width: 79px;">金融帳號認證</th><th class="sorting" tabindex="0" aria-controls="dataTables-tables" rowspan="1" colspan="1" aria-label="社交認證: activate to sort column ascending" style="width: 53px;">社交認證</th><th class="sorting" tabindex="0" aria-controls="dataTables-tables" rowspan="1" colspan="1" aria-label="緊急聯絡人: activate to sort column ascending" style="width: 66px;">緊急聯絡人</th><th class="sorting" tabindex="0" aria-controls="dataTables-tables" rowspan="1" colspan="1" aria-label="常用電子信箱: activate to sort column ascending" style="width: 80px;">常用電子信箱</th><th class="sorting" tabindex="0" aria-controls="dataTables-tables" rowspan="1" colspan="1" aria-label="財務訊息認證: activate to sort column ascending" style="width: 81px;">財務訊息認證</th></tr>
													<tbody>
													<?php 
														$count = 0;
														if(isset($certification_investor_list) && !empty($certification_investor_list)){
															foreach($certification_investor_list as $key => $value){
																$count++;
													?>
														<tr class="<?=$count%2==0?"odd":"even"; ?>">
															<td>投資端</td>
															<td>
																<a class="fancyframe" href="<?=admin_url('User/display?id='.$key) ?>" >
																	<?=isset($key)?$key:"" ?>
																</a>
															</td><td></td><td></td><td></td>
															<? 
															if($certification){
																foreach($certification as $k => $v){
																	echo '<td>';
																	if(isset($value[$k]["user_status"]) && $value[$k]["user_status"]!=null){
																		$user_status 		= $value[$k]["user_status"];
																		$certification_id 	= $value[$k]["certification_id"];
																		if($k==3){
																			switch($user_status){
																				case '0': 
																					if($value[1]["user_status"]==1){
																						echo '<a class="fancyframe" target="_blank" href="'.admin_url('certification/user_bankaccount_list?verify=2').'" class="btn btn-default btn-md" >金融驗證</a>';
																					}else{
																						echo '<a class="fancyframe" href="'.admin_url('certification/user_bankaccount_edit?from=risk&id='.$value["bank_account"]->id).'" ><button type="button" class="btn btn-warning btn-circle"><i class="fa fa-refresh"></i> </button></a>';
																					}
																					break;
																				case '1':
																					echo '<a class="fancyframe" href="'.admin_url('certification/user_bankaccount_edit?from=risk&id='.$value["bank_account"]->id).'" ><button type="button" class="btn btn-success btn-circle"><i class="fa fa-check"></i> </button></a>';
																					break;
																				case '2': 
																					echo '<a class="fancyframe" href="'.admin_url('certification/user_bankaccount_edit?from=risk&id='.$value["bank_account"]->id).'" ><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i> </button></a>';
																					break;
																				case '3': 
																					echo '<a class="fancyframe" target="_blank" href="'.admin_url('certification/user_bankaccount_list?verify=2').'" class="btn btn-default btn-md" >金融驗證</a>';
																					break;
																				default:
																					break;
																			}
																		}else{
																			switch($user_status){
																				case '0': 
																					echo '<a class="fancyframe" href="'.admin_url('certification/user_certification_edit?from=risk&id='.$certification_id).'" ><button type="button" class="btn btn-warning btn-circle"><i class="fa fa-refresh"></i> </button></a>';
																					break;
																				case '1':
																					echo '<a class="fancyframe" href="'.admin_url('certification/user_certification_edit?from=risk&id='.$certification_id).'" ><button type="button" class="btn btn-success btn-circle"><i class="fa fa-check"></i> </button></a>';
																					break;
																				case '2': 
																					echo '<a class="fancyframe" href="'.admin_url('certification/user_certification_edit?from=risk&id='.$certification_id).'" ><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i> </button></a>';
																					break;
																				case '3': 
																					echo '<a class="fancyframe" href="'.admin_url('certification/user_certification_edit?from=risk&id='.$certification_id).'" class="btn btn-default btn-md" >驗證</a>';
																					break;
																				default:
																					break;
																			}
																		}
																	}
																	echo '</td>';
																}
															}
															?>
															<td></td>                                          
														</tr>                                    
													<?php 
														}}
													?>
													<?php 
														if(isset($list) && !empty($list)){
															foreach($list as $key => $value){
																$count++;
													?>
														<tr class="<?=$count%2==0?"odd":"even"; ?>">														
															<? if($certification){
																foreach($certification as $k => $v){
																	echo '<td>';
																	if(isset($value->certification) && $value->certification[$k]["user_status"]!=null){
																		$certification_id 	= $value->certification[$k]["certification_id"];
																		if($k==3){
																			switch($value->certification[$k]["user_status"]){
																				case '0': 
																					echo '<a class="fancyframe" href="'.admin_url('certification/user_bankaccount_edit?from=risk&id='.$value->bank_account->id).'" class="btn btn-default btn-md" >驗證</a>';
																					break;
																				case '1':
																					echo '<a class="fancyframe" href="'.admin_url('certification/user_bankaccount_edit?from=risk&id='.$value->bank_account->id).'" ><button type="button" class="btn btn-success btn-circle"><i class="fa fa-check"></i> </button></a>';
																					break;
																				case '2': 
																					echo '<a class="fancyframe" href="'.admin_url('certification/user_bankaccount_edit?from=risk&id='.$value->bank_account->id).'" ><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i> </button></a>';
																					break;
																				case '3': 
																					echo '<a class="fancyframe" href="'.admin_url('certification/user_bankaccount_edit?from=risk&id='.$value->bank_account->id).'" class="btn btn-default btn-md" >驗證</a>';
																					break;
																				default:
																					break;
																			}
																		}else{
																			switch($value->certification[$k]["user_status"]){
																				case '0': 
																					echo '<a class="fancyframe" href="'.admin_url('certification/user_certification_edit?from=risk&id='.$certification_id).'" ><button type="button" class="btn btn-warning btn-circle"><i class="fa fa-refresh"></i> </button></a>';
																					break;
																				case '1':
																					echo '<a class="fancyframe" href="'.admin_url('certification/user_certification_edit?from=risk&id='.$certification_id).'" ><button type="button" class="btn btn-success btn-circle"><i class="fa fa-check"></i> </button></a>';
																					break;
																				case '2': 
																					echo '<a class="fancyframe" href="'.admin_url('certification/user_certification_edit?from=risk&id='.$certification_id).'" ><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i> </button></a>';
																					break;
																				case '3': 
																					echo '<a class="fancyframe" href="'.admin_url('certification/user_certification_edit?from=risk&id='.$certification_id).'" class="btn btn-default btn-md" >驗證</a>';
																					break;
																				default:
																					break;
																			}
																		}
																	}
																	echo '</td>';
																}
																}
															?>                                         
														</tr>                                    
													<?php 
														}}
													?>
													</tbody>
												</table>
											</div>
										</div>
										<!-- /.panel-body -->
									</div>	
								</div>
							</div>
							<? if(in_array($data->status,array(4,5,10))){?>
							<div class="col-lg-6">
								<div class="table-responsive">
								<? if($investments){
									foreach($investments as $key => $value){
								?>
								<table class="table table-bordered table-hover" style="text-align:center;">
										<tbody>
											<tr style="background-color:#f5f5f5;">
												<td colspan="8">
													<a class="fancyframe" href="<?=admin_url('User/display?id='.$value->user_info->id) ?>" >出借人資訊：<?=isset($value->user_info->id)?$value->user_info->id:"";?></a>
												</td>
											</tr>
											<tr>
												<td><p class="form-control-static">出借人ID</p></td>
												<td>
													<p class="form-control-static"><?=isset($value->user_info->id)?$value->user_info->id:"";?></p>
												</td>
												<td><p class="form-control-static">姓名</p></td>
												<td>
													<p class="form-control-static"><?=isset($value->user_info->name)?$value->user_info->name:"";?></p>
												</td>

												<td><p class="form-control-static">Email</p></td>
												<td colspan="3">
													<p class="form-control-static"><?=isset($value->user_info->email)?$value->user_info->email:"";?></p>
												</td>
											</tr>
											<tr>
												<td><p class="form-control-static">發證地點</p></td>
												<td>
													<p class="form-control-static"><?=isset($value->user_info->id_card_place)?$value->user_info->id_card_place:"";?></p>
												</td>
												<td><p class="form-control-static">發證日期</p></td>
												<td>
													<p class="form-control-static"><?=isset($value->user_info->id_card_date)?$value->user_info->id_card_date:"";?></p>
												</td>
												<td><p class="form-control-static">身分證字號</p></td>
												<td>
													<p class="form-control-static"><?=isset($value->user_info->id_number)?$value->user_info->id_number:"";?></p>
												</td>
												<td><p class="form-control-static">性別</p></td>
												<td>
													<p class="form-control-static"><?=isset($value->user_info->sex)?$value->user_info->sex:"";?></p>
												</td>
											</tr>
											<tr>
												<td><p class="form-control-static">投標</p></td>
												<td>
													<p class="form-control-static"><?=isset($value->amount)?$value->amount:"";?></p>
												</td>
												<td><p class="form-control-static">得標</p></td>
												<td>
													<p class="form-control-static"><?=isset($value->loan_amount)?$value->loan_amount:"";?></p>
												</td>

												<td><p class="form-control-static">匯款時間</p></td>
												<td colspan="3">
													<p class="form-control-static"><?=isset($value->tx_datetime)?$value->tx_datetime:"";?></p>
												</td>
											</tr>
											<tr>
												<td><p class="form-control-static">出借虛擬帳號</p></td>
												<td colspan="3">
													<p class="form-control-static">
													<a class="fancyframe" href="<?=admin_url('Passbook/display?virtual_account='.$value->virtual_account->virtual_account) ?>" ><?=$value->virtual_account->virtual_account ?></a>
													</p>
												</td>
												<td><p class="form-control-static">待交易區流水號</p></td>
												<td colspan="3">
													<p class="form-control-static"><?=isset($value->frozen_id)?$value->frozen_id:"";?></p>
												</td>
											</tr>
											<? if(isset($investments_amortization_schedule[$value->id]) && $investments_amortization_schedule[$value->id]){?>
												<tr style="background-color:#f5f5f5;">
													<td colspan="7">預計攤還表</td>
												</tr>
												<tr style="background-color:#f5f5f5;">
													<td>本金合計</td>
													<td><?=$investments_amortization_schedule[$value->id]["amount"]?></td>
													<td>本息合計</td>
													<td><?=$investments_amortization_schedule[$value->id]["total"]["total_payment"]?></td>
													<td>XIRR</td>
													<td><?=$investments_amortization_schedule[$value->id]["XIRR"]?>%</td>
													<td><?=$investments_amortization_schedule[$value->id]["date"]?></td>
												</tr>
												<tr style="background-color:#f5f5f5;">
													<td>期數</td>
													<td>期初本金</td>
													<td>還款日</td>
													<td>日數</td>
													<td>還款本金</td>
													<td>還款利息</td>
													<td>還款合計</td>
												</tr>
												<?	foreach($investments_amortization_schedule[$value->id]["schedule"] as $k => $v){ ?>
												
												
												<tr>
													<td><?=$v['instalment'] ?></td>
													<td><?=$v['remaining_principal'] ?></td>
													<td><?=$v['repayment_date'] ?></td>
													<td><?=$v['days'] ?></td>
													<td><?=$v['principal'] ?></td>
													<td><?=$v['interest'] ?></td>
													<td><?=$v['total_payment'] ?></td>
												</tr>
											<? }} ?>
											
											<? if(isset($investments_amortization_table[$value->id]) && $investments_amortization_table[$value->id]){ ?>
												<tr style="background-color:#f5f5f5;">
													<td colspan="8"><a class="fancyframe" href="<?=admin_url('Target/transaction_display?id='.$data->id) ?>" >攤還表</a></td>
												</tr>
												<tr style="background-color:#f5f5f5;">
													<td>本金合計</td>
													<td><?=$investments_amortization_table[$value->id]["amount"]?></td>
													<td>本息合計</td>
													<td><?=$investments_amortization_table[$value->id]["total_payment"]?></td>
													<td>XIRR</td>
													<td><?=$investments_amortization_table[$value->id]["XIRR"]?>%</td>
													<td colspan="2"><?=$investments_amortization_table[$value->id]["date"]?></td>
												</tr>
												<tr style="background-color:#f5f5f5;">
													<td>期數</td>
													<td>期初本金</td>
													<td>還款日</td>
													<td>日數</td>
													<td>還款本金</td>
													<td>還款利息</td>
													<td>還款合計</td>
													<td>已還款金額</td>
												</tr>
												<?	foreach($investments_amortization_table[$value->id]["list"] as $k => $v){ ?>
												<tr>
													<td><?=$v['instalment'] ?></td>
													<td><?=$v['remaining_principal'] ?></td>
													<td><?=$v['repayment_date'] ?></td>
													<td><?=$v['days'] ?></td>
													<td><?=$v['principal'] ?></td>
													<td><?=$v['interest'] ?></td>
													<td><?=$v['total_payment'] ?></td>
													<td><?=$v['repayment'] ?></td>
												</tr>
											<? }} ?>
										</tbody>
									</table>
								<?}}?>
								</div>
							</div>
							<? }?>
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

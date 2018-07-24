
	<script>
	
		function form_onsubmit(){
			return true;
		}

	</script>
	
	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">標的資訊</h1>
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
												<td><p class="form-control-static">ID</p></td>
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
													<p class="form-control-static"><?=isset($data->virtual_account)?$data->virtual_account:"";?></p>
												</td>
												<td><p class="form-control-static">備註</p></td>
												<td>
													<p class="form-control-static"><?=isset($data->remark)?$data->remark:"";?></p>
												</td>
											</tr>
											<tr>
												<td><p class="form-control-static">狀態</p></td>
												<td>
													<p class="form-control-static"><?=isset($data->status)?$data->status:"";?></p>
												</td>
												<td><p class="form-control-static">放款狀態</p></td>
												<td>
													<p class="form-control-static"><?=isset($data->loan_status)?$data->loan_status:"";?></p>
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
												<td><p class="form-control-static">ID</p></td>
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
													<td>期數</td>
													<td>期初本金</td>
													<td>還款日</td>
													<td>日數</td>
													<td>還款本金</td>
													<td>還款利息</td>
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
													<td><?=$value['principal'] ?></td>
													<td><?=$value['interest'] ?></td>
													<td><?=$value['total_payment'] ?></td>
													<td><?=$value['repayment'] ?></td>
												</tr>
											<? }}} ?>
										</tbody>
									</table>
								</div>
							</div>
							<? if($data->status==5 || $data->status==10){?>
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
												<td><p class="form-control-static">ID</p></td>
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
												<? if($investments_amortization_table[$value->id]){
													foreach($investments_amortization_table[$value->id]["list"] as $k => $v){
												?>
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

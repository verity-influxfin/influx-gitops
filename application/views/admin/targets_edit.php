
	<script>
	
		function form_onsubmit(){
			return true;
		}
		
		$(document).ready(function () {

		});	
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
												<td colspan="8">會員資訊</td>
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
										</tbody>
									</table>
								</div>
								<div class="table-responsive">
									<table class="table table-bordered table-hover" style="text-align:center;">
										<tbody>
										<tr style="background-color:#f5f5f5;">
											<td colspan="4">金融卡資訊</td>
										</tr>
										<? if(!empty($bank_account)){
											foreach($bank_account as $key => $value){
										?>
											<tr style="background-color:#f5f5f5;">
												<td><p class="form-control-static">ID</p></td>
												<td>
													<p class="form-control-static"><?=isset($value->id)?$value->id:"";?></p>
												</td>
												<td><p class="form-control-static">借款端/出借端</p></td>
												<td>
													<p class="form-control-static"><?=isset($value->investor)?$bank_account_investor[$value->investor]:"";?></p>
												</td>
											</tr>
											<tr>
												<td><p class="form-control-static">銀行號 分行號</p></td>
												<td>
													<p class="form-control-static"><?=isset($value->bank_code)?$value->bank_code.' '.$value->branch_code:"";?></p>
												</td>
												<td><p class="form-control-static">銀行帳號</p></td>
												<td>
													<p class="form-control-static"><?=isset($value->bank_account)?$value->bank_account:"";?></p>
												</td>
											</tr>
											<tr>
												<td><p class="form-control-static">狀態</p></td>
												<td>
													<p class="form-control-static">正常</p>
												</td>
												<td><p class="form-control-static">驗證狀態</p></td>
												<td>
													<p class="form-control-static"><?=isset($value->verify)?$bank_account_verify[$value->verify]:"";?></p>
												</td>
											</tr>
											<tr>
												<td colspan="2"><?=isset($value->front_image)?"<a href='".$value->front_image."' data-fancybox='images'><img src='".$value->front_image."' style='width:30%;'></a>":"";?></td>
												<td colspan="2"><?=isset($value->back_image)?"<a href='".$value->back_image."' data-fancybox='images'><img src='".$value->back_image."' style='width:30%;'></a>":"";?></td>
											</tr>
										<? }} ?>
										</tbody>
									</table>
								</div>

								<div class="table-responsive">
									<table class="table table-bordered table-hover" style="text-align:center;">
										<tbody>
										<tr style="background-color:#f5f5f5;">
											<td colspan="4">信用指數</td>
										</tr>
										<? if(!empty($credit_list)){
											foreach($credit_list as $key => $value){
										?>
											<tr style="background-color:#f5f5f5;">
												<td><p class="form-control-static">ID</p></td>
												<td>
													<p class="form-control-static"><?=isset($value->id)?$value->id:"";?></p>
												</td>
												<td><p class="form-control-static">借款端</p></td>
												<td>
													<p class="form-control-static">借款端</p>
												</td>
											</tr>
											<tr>
												<td><p class="form-control-static">產品</p></td>
												<td>
													<p class="form-control-static"><?=isset($value->product_id)?$product_list[$value->product_id]:"";?></p>
												</td>
												<td><p class="form-control-static">信用等級</p></td>
												<td>
													<p class="form-control-static"><?=isset($value->level)?$value->level:"";?></p>
												</td>
											</tr>
											<tr>
												<td><p class="form-control-static">信用評分</p></td>
												<td>
													<p class="form-control-static"><?=isset($value->points)?$value->points:"";?></p>
												</td>
												<td><p class="form-control-static">信用額度</p></td>
												<td>
													<p class="form-control-static"><?=isset($value->amount)?$value->amount:"";?></p>
												</td>
											</tr>
											<tr>
												<td><p class="form-control-static">有效時間</p></td>
												<td>
													<p class="form-control-static"><?=isset($value->expire_time)&&!empty($value->expire_time)?date("Y-m-d H:i:s",$value->expire_time):"";?></p>
												</td>
												<td><p class="form-control-static">核准時間</p></td>
												<td>
													<p class="form-control-static"><?=isset($value->created_at)&&!empty($value->created_at)?date("Y-m-d H:i:s",$value->created_at):"";?></p>
												</td>
											</tr>
										<? }} ?>
										</tbody>
									</table>
								</div>
							</div>
							<div class="col-lg-6 meta">
								<div class="table-responsive">
									<table class="table table-bordered table-hover" style="text-align:center;">
										<tbody>
										<? if(!empty($meta_fields)){
											foreach($meta_fields as $alias => $fields){
										?>
											<tr style="background-color:#f5f5f5;">
												<td colspan="2"><?=isset($certification_list[$alias])?$certification_list[$alias]:$alias?></td>
											</tr>
										<?		foreach($fields as $key => $field){
										?>
											<tr>
												<td style="width:40%;"><?=$field ?></td>
												<td style="word-break: break-all;width:60%;"><?
												if(isset($meta[$key])&&!empty($meta[$key])){
													if(in_array($key,$meta_images)){
														echo "<a href='".$meta[$key]."' data-fancybox='images'><img src='".$meta[$key]."' style='width:30%;'></a>";
													}else if( $key == $alias.'_status' && $meta[$key]==1){
														echo "已認證"; 
													}else{
														echo $meta[$key];
													}
												}else{
													echo "無";
												}
												?></td>
											</tr>
										<? }}} ?>
										</tbody>
									</table>
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

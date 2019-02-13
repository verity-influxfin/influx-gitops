        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">借款 - 還款中</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<script type="text/javascript">
				function checked_all(){
					$('.targets').prop("checked", true);
					check_checked();
				}
				
				function check_checked(){
					var ids					= '';
					var repayment_export	= '<?=admin_url('target/repayment_export') ?>';
					var amortization_export = '<?=admin_url('target/amortization_export') ?>';
					
					$('.targets:checked').each(function() {
						if(ids==''){
							ids += this.value;
						}else{
							ids += ',' + this.value;
						}		
					});
					$('#repayment_export').attr('href',repayment_export + '?ids=' + ids);
					$('#amortization_export').attr('href',amortization_export + '?ids=' + ids);
				}
			</script>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							<a id="repayment_export" href="<?=admin_url('target/repayment_export') ?>" target="_blank"  class="btn btn-primary float-right" >匯出Excel</a>
							<a id="amortization_export" href="<?=admin_url('target/amortization_export') ?>" target="_blank"  class="btn btn-primary float-right" >匯出攤還表</a>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th>案號 <a href="javascript:void(0)" onclick="checked_all();" class="btn" >全選</a></th>
                                            <th>產品</th>
                                            <th>會員 ID</th>
                                            <th>信用等級</th>
                                            <th>學校名稱</th>
                                            <th>學校科系</th>
                                            <th>借款金額</th>
											<th>剩餘本金</th>
											<th>年化利率</th>
                                            <th>期數</th>
                                            <th>還款方式</th>
                                            <th>每月回款</th>
                                            <th>本息總額</th>
                                            <th>放款日期</th>
                                            <th>逾期狀況</th>
                                            <th>狀態</th>
                                            <th>申請日期</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php 
										if(isset($list) && !empty($list)){
											$count = 0;
											foreach($list as $key => $value){
												$count++;
									?>
                                        <tr class="<?=$count%2==0?"odd":"even"; ?> list <?=isset($value->user_id)?$value->user_id:'' ?>">
											 <td>
												<input class="targets" type="checkbox" onclick="check_checked();" value="<?=isset($value->id)?$value->id:'' ?>" />
												<?=isset($value->target_no)?$value->target_no:'' ?>
											 </td>
                                            <td><?=isset($product_name[$value->product_id])?$product_name[$value->product_id]:"" ?></td>
                                            <td><?=isset($value->user_id)?$value->user_id:"" ?></td>
                                            <td><?=isset($value->credit_level)?$value->credit_level:"" ?></td>
											<td><?=isset($school_list[$value->user_id]["school_name"])?$school_list[$value->user_id]["school_name"]:"" ?></td>
                                            <td><?=isset($school_list[$value->user_id]["school_department"])?$school_list[$value->user_id]["school_department"]:"" ?></td>
                                            <td><?=isset($value->loan_amount)&&$value->loan_amount?$value->loan_amount:"" ?></td>
											<td><?=isset($value->amortization_table["remaining_principal"])?$value->amortization_table["remaining_principal"]:"" ?></td>
                                            <td><?=isset($value->interest_rate)&&$value->interest_rate?floatval($value->interest_rate).'%':"" ?></td>
                                            <td><?=isset($value->instalment)?$value->instalment:"" ?></td>
                                            <td><?=isset($value->repayment)?$repayment_type[$value->repayment]:"" ?></td>
                                            <td><?=isset($value->amortization_table["total_payment_m"])?$value->amortization_table["total_payment_m"]:"" ?></td>
                                            <td><?=isset($value->amortization_table["total_payment"])?$value->amortization_table["total_payment"]:"" ?></td>
                                            <td><?=isset($value->loan_date)?$value->loan_date:"" ?></td>
                                            <td><?=isset($value->delay)?$delay_list[$value->delay]:"" ?> <?=$value->delay?$value->delay_days.'天':"" ?></td>
                                            <td>
											<?=isset($status_list[$value->status])?$status_list[$value->status]:'' ?>
											</td>
                                            <td><?=isset($value->created_at)?date("Y-m-d H:i:s",$value->created_at):"" ?></td>
											<td><a href="<?=admin_url('target/edit')."?id=".$value->id ?>" class="btn btn-default">Detail</a></td> 
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
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
        </div>
        <!-- /#page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">債權列表</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<script type="text/javascript">
				function checked_all(){
					$('.targets').prop("checked", true);
					check_checked();
				}
				
				function check_checked(){
					var ids					= "";
					var repayment_export	= '<?=admin_url('target/repayment_export') ?>';
					var amortization_export = '<?=admin_url('target/amortization_export') ?>';
					
					$('.targets:checked').each(function() {
						if(ids==""){
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
						</div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="display responsive nowrap" width="100%" id="dataTables-tables">
                                    <thead>
                                        <tr>
                                            <th>案號 <a href="javascript:void(0)" onclick="checked_all();" class="btn" >全選</a></th>
                                            <th>投資人會員 ID</th>
                                            <th>債權金額</th>
                                            <th>案件總額</th>
											<th>年化利率</th>
                                            <th>期數</th>
                                            <th>還款方式</th>
                                            <th>放款日期</th>
                                            <th>逾期狀況</th>
                                            <th>債權狀態</th>
                                            <th>債轉時間</th>
                                            <th>案件狀態</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php 
										if(isset($list) && !empty($list)){
											$count = 0;
											foreach($list as $key => $value){
												$count++;
												$target = $value->target;
									?>
                                        <tr class="<?=$count%2==0?"odd":"even"; ?> list <?=isset($target->user_id)?$target->user_id:"" ?>">
											 <td>
												<?=isset($target->target_no)?$target->target_no:"" ?>
											 </td>
                                            <td><?=isset($value->user_id)?$value->user_id:"" ?></td>
                                            <td><?=isset($value->loan_amount)&&$value->loan_amount?$value->loan_amount:"" ?></td>
                                            <td><?=isset($target->loan_amount)&&$target->loan_amount?$target->loan_amount:"" ?></td>
                                            <td><?=isset($target->interest_rate)&&$target->interest_rate?$target->interest_rate.'%':"" ?></td>
                                            <td><?=isset($target->instalment)?$target->instalment:"" ?></td>
                                            <td><?=isset($target->repayment)?$repayment_type[$target->repayment]:"" ?></td>
                                            <td><?=isset($target->loan_date)?$target->loan_date:"" ?></td>
                                            <td><?=isset($target->delay)?$delay_list[$target->delay]:"" ?></td>
                                            <td><?=$value->transfer_status==2?$transfer_status_list[$value->transfer_status]:$investment_status_list[$value->status] ?></td>
                                            <td><?=$value->transfer_status==2&&isset($transfers[$value->id]->transfer_date)?$transfers[$value->id]->transfer_date:"" ?></td>
                                            <td><?=isset($status_list[$target->status])?$status_list[$target->status]:"" ?></td>
											<td><a href="<?=admin_url('target/edit')."?id=".$target->id ?>" class="btn btn-default">Detail</a></td> 
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
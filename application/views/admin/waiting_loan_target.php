        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">借款 - 待放款</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<script type="text/javascript">
				function toloan(){
					var ids = "";
					$('.targets:checked').each(function() {
						if(ids==""){
							ids += this.value;
						}else{
							ids += ',' + this.value;
						}		
					});
					if(ids==""){
						alert("請先選擇欲放款案件");
						return false;
					}
					if(confirm("確認匯出下列案件ID:"+ids)){
						window.open('./target_loan?ids=' + ids,'_blank');
						setTimeout(location.reload(),500);
					}
				}
				
				function success(id){
					if(confirm("確認放款成功？")){
						if(id){
							$.ajax({
								url: './loan_success?id='+id,
								type: 'GET',
								success: function(response) {
									alert(response);
									location.reload();
								}
							});
						}
					}
				}
				
				function failed(id){
					if(confirm("確認放款失敗？改回待出款")){
						if(id){
							$.ajax({
								url: './loan_failed?id='+id,
								type: 'GET',
								success: function(response) {
									alert(response);
									location.reload();
								}
							});
						}
					}
				}

				function rollback(id){
					if(confirm("確認整案退回？得標者全數流標")){
						if(id){
							alert("功能實作中，請找IT");
						}
					}
				}
			</script>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
						<a href="javascript:void(0)" target="_blank" onclick="toloan();" class="btn btn-primary float-right" >轉出放款匯款單</a>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>NO.</th>
                                            <th>案號</th>
                                            <th>產品</th>
                                            <th>借款人ID</th>
                                            <th>放款金額</th>
											<th>核准利率</th>
                                            <th>期數</th>
                                            <th>還款方式</th>
                                            <th>放款狀態</th>
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
                                        <tr class="<?=$count%2==0?"odd":"even"; ?>">
                                            <td>
												<? if($value->loan_status==2){ ?>
												<input class="targets" type="checkbox" value="<?=isset($value->id)?$value->id:"" ?>" />
												<? } ?>
												&nbsp;<?=isset($value->id)?$value->id:"" ?>
											</td>
                                            <td><?=isset($value->target_no)?$value->target_no:"" ?></td>
                                            <td><?=isset($product_name[$value->product_id])?$product_name[$value->product_id]:"" ?></td>
                                            <td>
												<a class="fancyframe" href="<?=admin_url('User/display?id='.$value->user_id) ?>" >
													<?=isset($value->user_id)?$value->user_id:"" ?>
												</a>
											</td>
                                            <td><?=isset($value->loan_amount)&&$value->loan_amount?$value->loan_amount:"" ?></td>
                                            <td><?=isset($value->interest_rate)&&$value->interest_rate?$value->interest_rate:"" ?>%</td>
                                            <td><?=isset($value->instalment)?$instalment_list[$value->instalment]:"" ?></td>
                                            <td><?=isset($value->repayment)?$repayment_type[$value->repayment]:"" ?></td>
                                            <td>
												<?=isset($loan_list[$value->loan_status])?$loan_list[$value->loan_status]:"" ?>
												<? if($value->loan_status==3){
													echo '<button class="btn btn-success" onclick="success('.$value->id.')">成功</button>&nbsp;';
													echo '<button class="btn btn-danger" onclick="failed('.$value->id.')">不成功</button>&nbsp;';
													echo '<button class="btn btn-danger" onclick="rollback('.$value->id.')">整案退回</button>';
												} ?>
											</td>
                                            <td><?=isset($status_list[$value->status])?$status_list[$value->status]:"" ?></td>
                                            <td><?=isset($value->created_at)?date("Y-m-d H:i:s",$value->created_at):"" ?></td>
											<td><a href="<?=admin_url('target/edit')."?id=".$value->id ?>" class="btn btn-default">Detail</a></td> 
                                        </tr>                                        
									<?php 
										}}else{
									?>
									<tr class="odd">
										<th class="text-center" colspan="12">目前尚無資料</th>
									</tr>
									<?php 
										}
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
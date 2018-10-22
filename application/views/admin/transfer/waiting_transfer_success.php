        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">債權 - 待放款</h1>
                </div>
                <!-- /.col-lg-12 -->
			<script type="text/javascript">
				function toloan(){
					var ids = "";
					var target_no = "";
					$('.targets:checked').each(function() {
						if(ids==""){
							ids += this.value;
						}else{
							ids += ',' + this.value;
						}	
						if(target_no==""){
							target_no += $(this).attr("data-targetno");
						}else{
							target_no += ',' + $(this).attr("data-targetno");
						}							
					});
					if(ids==""){
						alert("請先選擇欲放行案件");
						return false;
					}
					if(confirm("確認放行下列案件："+target_no)){
						if(ids){
							$.ajax({
								url: './transfer_success?ids='+ids,
								type: 'GET',
								success: function(response) {
									alert(response);
									location.reload();
								}
							});
						}
					}
				}
				
				function cancel(id){
					if(confirm("確認取消債轉？")){
						if(id){
							$.ajax({
								url: './transfer_cancel?id='+id,
								type: 'GET',
								success: function(response) {
									alert(response);
									location.reload();
								}
							});
						}
					}
				}
			</script>
			</div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							<a href="javascript:void(0)" onclick="toloan();" class="btn btn-primary float-right" >放行</a>
						</div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="display responsive nowrap" width="100%" id="dataTables-tables">
                                    <thead>
                                        <tr>
                                            <th>案號</th>
                                            <th>出讓人會員 ID</th>
                                            <th>受讓人會員 ID</th>
                                            <th>扣款時間</th>
                                            <th>債權金額</th>
                                            <th>案件總額</th>
											<th>年化利率</th>
                                            <th>價金</th>
                                            <th>剩餘本金</th>
                                            <th>本期利息</th>
                                            <th>本期回款手續費</th>
                                            <th>剩餘期數</th>
                                            <th>有效時間</th>
											<th>取消</th>
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
                                        <tr class="<?=$count%2==0?"odd":"even"; ?> list <?=isset($value->user_id)?$value->user_id:"" ?>">
											 <td>
												<?=isset($value->target->target_no)?$value->target->target_no:"" ?>
												<input class="targets" type="checkbox" data-targetno="<?=isset($value->target->target_no)?$value->target->target_no:"" ?>" value="<?=isset($value->id)?$value->id:"" ?>" />
											 </td>
                                            <td><?=isset($value->investment->user_id)&&$value->investment->user_id?$value->investment->user_id:"" ?></td>
                                            <td><?=isset($value->transfer_investment->user_id)&&$value->transfer_investment->user_id?$value->transfer_investment->user_id:"" ?></td>
                                            <td><?=isset($value->transfer_investment->tx_datetime)&&$value->transfer_investment->tx_datetime?$value->transfer_investment->tx_datetime:"" ?></td>
                                            <td><?=isset($value->investment->loan_amount)&&$value->investment->loan_amount?$value->investment->loan_amount:"" ?></td>
                                            <td><?=isset($value->target->loan_amount)&&$value->target->loan_amount?$value->target->loan_amount:"" ?></td>
                                            <td><?=isset($value->target->interest_rate)&&$value->target->interest_rate?$value->target->interest_rate.'%':"" ?></td>
                                            <td><?=isset($value->amount)?$value->amount:"" ?></td>
                                            <td><?=isset($value->principal)?$value->principal:"" ?></td>
                                            <td><?=isset($value->interest)?$value->interest:"" ?></td>
                                            <td><?=isset($value->platform_fee)?$value->platform_fee:"" ?></td>
                                            <td><?=isset($value->instalment)?$value->instalment:"" ?></td>
                                            <td><?=isset($value->expire_time)?date("Y-m-d H:i:s",$value->expire_time):"" ?></td>
                                            <td><button class="btn btn-danger" onclick="cancel(<?=isset($value->id)?$value->id:"" ?>)">取消</button></td>
											<td><a href="<?=admin_url('target/edit')."?id=".$value->target->id ?>" class="btn btn-default">Detail</a></td> 
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
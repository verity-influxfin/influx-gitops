        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">提領 - 待放款</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<script type="text/javascript">
				function toloan(){
					var ids = "";
					$('.withdraws:checked').each(function() {
						if(ids==""){
							ids += this.value;
						}else{
							ids += ',' + this.value;
						}		
					});
					if(ids==""){
						alert("請先選擇欲出款申請");
						return false;
					}
					if(confirm("確認匯出下列案件ID:"+ids)){
						window.open('./withdraw_loan?ids=' + ids,'_blank');
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
                                <table class="table table-striped table-bordered table-hover" id="dataTables-tables">
                                    <thead>
                                        <tr>
                                            <th>NO.</th>
                                            <th>虛擬帳號</th>
                                            <th>User ID</th>
											<th>借款端/出借端</th>
                                            <th>提領金額</th>
                                            <th>待交易流水號</th>
                                            <th>狀態</th>
                                            <th>創建日期</th>
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
												<? if($value->status==0){ ?>
												<input class="withdraws" type="checkbox" value="<?=isset($value->id)?$value->id:"" ?>" />
												<? } ?>
												&nbsp;<?=isset($value->id)?$value->id:"" ?>
											</td>
                                            <td>
											<a class="fancyframe" href="<?=admin_url('Passbook/display?virtual_account='.$value->virtual_account) ?>" >
												<?=isset($value->virtual_account)?$value->virtual_account:"" ?>
											</a>
											</td>
                                            <td><a class="fancyframe" href="<?=admin_url('user/display?id='.$value->user_id) ?>" ><?=isset($value->user_id)?$value->user_id:"" ?></a></td>
											<td><?=isset($value->investor)?$investor_list[$value->investor]:"" ?></td>
											<td><?=isset($value->amount)?intval($value->amount):"" ?></td>
											<td><?=isset($value->frozen_id)?$value->frozen_id:"" ?></td>
                                            <td><?=isset($value->status)?$status_list[$value->status]:"" ?>
												<? if($value->status==2){
													echo '<button class="btn btn-success" onclick="success('.$value->id.')">成功</button>&nbsp;';
													echo '<button class="btn btn-danger" onclick="failed('.$value->id.')">不成功</button>';
												} ?></td>
											<td><?=isset($value->created_at)?date("Y-m-d H:i:s",$value->created_at):"" ?></td>
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
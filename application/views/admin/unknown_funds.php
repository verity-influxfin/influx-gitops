        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">不明來源退款</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<script type="text/javascript">
				function toloan(){
					var ids = "";
					$('.refunds:checked').each(function() {
						if(ids==""){
							ids += this.value;
						}else{
							ids += ',' + this.value;
						}		
					});
					if(ids==""){
						alert("請先選擇欲退款");
						return false;
					}
					if(confirm("確認退款下列ID (限使用ATM轉帳):"+ids)){
						window.open('./unknown_refund?ids=' + ids,'_blank');
						setTimeout(location.reload(),500);
					}
				}
			</script>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							<a href="javascript:void(0)" target="_blank" onclick="toloan();" class="btn btn-primary float-right" >轉出匯款單</a>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>交易日期</th>
                                            <th>交易序號</th>
                                            <th>金額</th>
                                            <th>交易說明</th>
                                            <th>對方銀行</th>
											<th>對方帳號</th>
											<th>對方戶名</th>
											<th>虛擬帳號</th>
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
                                            <td><? if($value->status==5){ ?>
												<input class="refunds" type="checkbox" value="<?=isset($value->id)?$value->id:"" ?>" />
												<? } ?>
												&nbsp;<?=isset($value->id)?$value->id:"" ?>
											</td>
                                            <td><?=isset($value->tx_datetime)?$value->tx_datetime:"" ?></td>
                                            <td><?=isset($value->tx_seq_no)?$value->tx_seq_no:"" ?></td>
                                            <td><?=isset($value->amount)?$value->amount:"" ?></td>
                                            <td><?=isset($value->tx_spec)&&$value->tx_spec?$value->tx_spec:"" ?></td>
                                            <td><?=isset($value->bank_id)&&$value->bank_id?$value->bank_id:"" ?></td>
                                            <td><?=isset($value->bank_acc)?$value->bank_acc:"" ?></td>
											<td><?=isset($value->acc_name)?$value->acc_name:"" ?></td>
											<td><?=isset($value->virtual_account)?$value->virtual_account:"" ?></td>
                                            <td><?=isset($value->status)?$status_list[$value->status]:"" ?></td>
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
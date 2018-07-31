        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">標的列表</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<script type="text/javascript">
				function showChang(){
					var delay 				= $('#delay :selected').val();
					var status 				= $('#status :selected').val();
					top.location = './index?delay='+delay+'&status='+status;
				}
				
				function success(id){
					if(confirm("確認審批上架？")){
						if(id){
							$.ajax({
								url: './verify_success?id='+id,
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
					if(confirm("確認驗證失敗？案件將自動取消")){
						if(id){
							$.ajax({
								url: './verify_failed?id='+id,
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
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>案號</th>
                                            <th>產品</th>
                                            <th>用戶ID</th>
                                            <th>申請金額</th>
                                            <th>核准金額</th>
											<th>年化利率</th>
                                            <th>期數</th>
                                            <th>還款方式</th>
                                            <th>逾期狀況
												<select id="delay" onchange="showChang();">
													<option value="" >請選擇</option>
													<? foreach($delay_list as $key => $value){ ?>
														<option value="<?=$key?>" <?=isset($_GET['delay'])&&$_GET['delay']!=""&&intval($_GET['delay'])==intval($key)?"selected":""?>><?=$value?></option>
													<? } ?>
												</select>
											</th>
                                            <th>狀態
												<select id="status" onchange="showChang();">
													<option value="" >請選擇</option>
													<? foreach($status_list as $key => $value){ ?>
														<option value="<?=$key?>" <?=isset($_GET['status'])&&$_GET['status']!=""&&intval($_GET['status'])==intval($key)?"selected":""?>><?=$value?></option>
													<? } ?>
												</select>
											</th>
                                            <th>申請日期</th>
                                            <th>邀請碼</th>
                                            <th>查看</th>
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
                                            <td><?=isset($value->id)?$value->id:"" ?></td>
                                            <td><?=isset($value->target_no)?$value->target_no:"" ?></td>
                                            <td><?=isset($product_name[$value->product_id])?$product_name[$value->product_id]:"" ?></td>
                                            <td><?=isset($value->user_id)?$value->user_id:"" ?></td>
                                            <td><?=isset($value->amount)?$value->amount:"" ?></td>
                                            <td><?=isset($value->loan_amount)&&$value->loan_amount?$value->loan_amount:"" ?></td>
                                            <td><?=isset($value->interest_rate)&&$value->interest_rate?$value->interest_rate:"" ?></td>
                                            <td><?=isset($value->instalment)?$instalment_list[$value->instalment]:"" ?></td>
                                            <td><?=isset($value->repayment)?$repayment_type[$value->repayment]:"" ?></td>
                                            <td><?=isset($value->delay)?$delay_list[$value->delay]:"" ?></td>
                                            <td>
											<?=isset($status_list[$value->status])?$status_list[$value->status]:"" ?>
											<? 	if($value->status==2){
													if($value->bank_account_verify){
														echo '<button class="btn btn-default" onclick="success('.$value->id.')">審批上架</button>';
														echo '<button class="btn btn-danger" onclick="failed('.$value->id.')">不通過</button>';
													}else{
														echo '<p style="color:red;">金融帳號未驗證</p>';
													}
												}
											?>
											</td>
                                            <td><?=isset($value->created_at)?date("Y-m-d H:i:s",$value->created_at):"" ?></td>
											<td><?=isset($value->promote_code)?$value->promote_code:"" ?></td>
											<td><a href="<?=admin_url('target/edit')."?id=".$value->id ?>" class="btn btn-default">查看</a></td> 
                                        </tr>                                        
									<?php 
										}}else{
									?>
									<tr class="odd">
										<th class="text-center" colspan="14">目前尚無資料</th>
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
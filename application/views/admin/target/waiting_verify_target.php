        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">借款 - 待審批</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<script type="text/javascript">
				function success(id){
					if(confirm("確認審批過件？")){
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
							var p 		= prompt("請輸入退案原因，將自動通知使用者，不通知請按取消",'');
							var remark 	= '';
							if(p){
								remark = encodeURIComponent(p);
							}
							$.ajax({
								url: './verify_failed?id='+id+'&remark='+remark,
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
                                <table class="table table-striped table-bordered table-hover" width="100%" id="dataTables-tables">
                                    <thead>
                                        <tr>
                                            <th>案號</th>
                                            <th>產品</th>
                                            <th>借款人ID</th>
                                            <th>申請金額</th>
                                            <th>核准金額</th>
											<th>核准利率</th>
                                            <th>期數</th>
                                            <th>還款方式</th>
                                            <th>狀態
											</th>
                                            <th>申請日期</th>
                                            <th>備註</th>
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
                                            <td><?=isset($value->target_no)?$value->target_no:'' ?></td>
                                            <td><?=isset($product_list[$value->product_id])?$product_list[$value->product_id]['name']:'' ?><?=$value->sub_product_id!=0?' / '.$sub_product_list[$value->sub_product_id]['identity'][$product_list[$value->product_id]['identity']]['name']:'' ?></td>
                                            <td>
												<a class="fancyframe" href="<?=admin_url('User/display?id='.$value->user_id) ?>" >
													<?=isset($value->user_id)?$value->user_id:'' ?>
												</a>
											</td>
                                            <td><?=isset($value->amount)?$value->amount:'' ?></td>
                                            <td><?=isset($value->loan_amount)&&$value->loan_amount?$value->loan_amount:'' ?></td>
                                            <td><?=isset($value->interest_rate)&&$value->interest_rate?floatval($value->interest_rate):'' ?>%</td>
                                            <td><?=isset($value->instalment)?$instalment_list[$value->instalment]:'' ?></td>
                                            <td><?=isset($value->repayment)?$repayment_type[$value->repayment]:'' ?></td>
                                            <td>
												<button <?=isset($value->subloan_count) && $value->subloan_count>2?" ":"" ?>class="btn
												<? if($value->bankaccount_verify==0){ ?>
                                                        btn-info"  onclick="window.location.href='<?=admin_url('certification/user_bankaccount_list?verify=2')."?id=".$value->id ?>'">待金融驗證</button>
                                                <? }else{ ?>
                                                    <?=$value->order_id==0?"btn-success":"btn-info" ?>" onclick="success(<?=isset($value->id)?$value->id:"" ?>)">審批<?=isset($value->order_id)&&$value->order_id!=0?'出貨':'上架' ?></button>
                                                    <button class="btn btn-danger" onclick="failed(<?=isset($value->id)?$value->id:'' ?>)">不通過</button>
                                                <? } ?>
                                                <?=isset($sub_list[$value->sub_status])?($value->sub_status==9?'('.$sub_list[$value->sub_status].')':''):'' ?>
											</td>
                                            <td><?=isset($value->created_at)?date("Y-m-d H:i:s",$value->created_at):'' ?></td>
                                            <td><?=isset($value->remark)?$value->remark:'' ?></td>
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
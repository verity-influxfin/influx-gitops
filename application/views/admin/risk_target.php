        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">風控專區</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<script type="text/javascript">
				function success(id,target_no){
					if(confirm(target_no+" 確認審批上架？")){
						if(id){
							$.ajax({
								url: '<?=admin_url('target/verify_success?id=')?>'+id,
								type: 'GET',
								success: function(response) {
									alert(response);
									location.reload();
								}
							});
						}
					}
				}

				function failed(id,target_no){
					if(confirm(target_no+" 確認退件？案件將自動取消")){
						if(id){
							var p 		= prompt("請輸入退案原因，將自動通知使用者，不通知請按取消","");
							var remark 	= "";
							if(p){
								remark = encodeURIComponent(p);
							}
							$.ajax({
								url: '<?=admin_url('target/verify_failed?id=')?>'+id+'&remark='+remark,
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
							<table style="width:50%">
								<tr>
									<td colspan="9">圖示說明：</td>
								</tr>
								<tr>
									<td>留空  尚未認證</td>
									<td>
									<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-refresh"></i></button>
									資料更新中
									</td>
									<td>
									<button type="button" class="btn btn-success btn-circle"><i class="fa fa-check"></i> </button>
									認證完成
									</td>
									<td>
									<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i></button>
									已退回
									</td>
									<td>
									<button type="button" class="btn btn-info btn-circle"><i class="fa fa-info"></i></button>
									查看備註
									</td>
								</tr>
							</table>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" width="100%" style="text-align: center;" id="dataTables-tables">
                                    <thead>
                                        <tr>
                                            <th>案號</th>
                                            <th>會員編號</th>
											<th>產品名稱</th>
                                            <th>狀態</th>
                                            <th>最後更新時間</th>
											<? if($certification){
												foreach($certification as $key => $value){
													echo '<th>'.$value['name'].'</th>';
												}
											}
											?>
                                            <th>退件</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php 
										$count = 0;
										if(isset($certification_investor_list) && !empty($certification_investor_list)){
											foreach($certification_investor_list as $key => $value){
												$count++;
									?>
                                        <tr class="<?=$count%2==0?"odd":"even"; ?>">
                                            <td>投資端</td>
											<td>
												<a class="fancyframe" href="<?=admin_url('User/display?id='.$key) ?>" >
													<?=isset($key)?$key:"" ?>
												</a>
											</td><td></td><td></td><td></td>
											<? 
											if($certification){
												foreach($certification as $k => $v){
													echo '<td>';
													if(isset($value[$k]["user_status"]) && $value[$k]["user_status"]!==null){
														$user_status 		= $value[$k]["user_status"];
														$certification_id 	= $value[$k]["certification_id"];
														if($k==3){
															switch($user_status){
																case '0': 
																	if($value[1]["user_status"]==1){
																		echo '<a target="_blank" href="'.admin_url('certification/user_bankaccount_list?verify=2').'" class="btn btn-default btn-md" >金融驗證</a>';
																	}else{
																		echo '<a href="'.admin_url('certification/user_bankaccount_edit?from=risk&id='.$value["bank_account"]->id).'" ><button type="button" class="btn btn-warning btn-circle"><i class="fa fa-refresh"></i> </button></a>';
																	}
																	break;
																case '1':
																	echo '<a href="'.admin_url('certification/user_bankaccount_edit?from=risk&id='.$value["bank_account"]->id).'" ><button type="button" class="btn btn-success btn-circle"><i class="fa fa-check"></i> </button></a>';
																	break;
																case '2': 
																	echo '<a href="'.admin_url('certification/user_bankaccount_edit?from=risk&id='.$value["bank_account"]->id).'" ><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i> </button></a>';
																	break;
																case '3': 
																	echo '<a target="_blank" href="'.admin_url('certification/user_bankaccount_list?verify=2').'" class="btn btn-default btn-md" >金融驗證</a>';
																	break;
																default:
																	break;
															}
														}else{
															switch($user_status){
																case '0': 
																	echo '<a href="'.admin_url('certification/user_certification_edit?from=risk&id='.$certification_id).'" ><button type="button" class="btn btn-warning btn-circle"><i class="fa fa-refresh"></i> </button></a>';
																	break;
																case '1':
																	echo '<a href="'.admin_url('certification/user_certification_edit?from=risk&id='.$certification_id).'" ><button type="button" class="btn btn-success btn-circle"><i class="fa fa-check"></i> </button></a>';
																	break;
																case '2': 
																	echo '<a href="'.admin_url('certification/user_certification_edit?from=risk&id='.$certification_id).'" ><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i> </button></a>';
																	break;
																case '3': 
																	echo '<a href="'.admin_url('certification/user_certification_edit?from=risk&id='.$certification_id).'" class="btn btn-default btn-md" >驗證</a>';
																	break;
																default:
																	break;
															}
														}
													}
													echo '</td>';
												}
											}
											?>
                                            <td></td>                                          
                                        </tr>                                    
									<?php 
										}}
									?>
									<?php 
										if(isset($list) && !empty($list)){
											foreach($list as $key => $value){
												$count++;
									?>
                                        <tr class="<?=$count%2==0?"odd":"even"; ?>">
                                            <td>
												<a class="fancyframe" href="<?=admin_url('target/edit?display=1&id='.$value->id) ?>" >
													<?=isset($value->target_no)?$value->target_no:"" ?>
												</a>
											</td>
											<td>
												<a class="fancyframe" href="<?=admin_url('User/display?id='.$value->user_id) ?>" >
													<?=isset($value->user_id)?$value->user_id:"" ?>
												</a>
											</td>
                                            <td><?=isset($product_list[$value->product_id])?$product_list[$value->product_id]['name']:'' ?></td>
											<td>
												<? 
													if($value->status==2){
														if($value->bank_account_verify){
															$all_pass = true;
															
															foreach($value->certification as $k => $v){
																if(in_array($v['id'],$product_list[$value->product_id]['certifications']) && $v['user_status']!=1){
																	$all_pass = false;
																}
															}
															
															if($all_pass){
																if($value->sub_status==8){
																	echo '<button class="btn btn-success" onclick="success('.$value->id.','."'".$value->target_no."'".')">轉換產品上架</button>';
																}else{
																	echo '<button class="btn btn-success" onclick="success('.$value->id.','."'".$value->target_no."'".')">審批上架</button>';
																}
															}else{
																echo isset($status_list[$value->status])?$status_list[$value->status]:"";
															}
														}else{
															echo '<a target="_blank" href="'.admin_url('certification/user_bankaccount_list?verify=2').'" class="btn btn-default btn-md" >待金融驗證</a>';
														}
													}else{
														echo isset($status_list[$value->status])?$status_list[$value->status]:"";
													}
												?>
											</td>
											<td><?=isset($value->updated_at)?date("Y-m-d H:i:s",$value->updated_at):"" ?></td>
											<? if($certification){
												foreach($certification as $k => $v){
													echo '<td>';
													if(isset($value->certification) && $value->certification[$k]["user_status"]!==null){
														$certification_id 	= $value->certification[$k]["certification_id"];
														if($k==3){
															switch($value->certification[$k]["user_status"]){
																case '0': 
																	echo '<a href="'.admin_url('certification/user_bankaccount_edit?from=risk&id='.$value->bank_account->id).'" class="btn btn-default btn-md" >驗證</a>';
																	break;
																case '1':
																	echo '<a href="'.admin_url('certification/user_bankaccount_edit?from=risk&id='.$value->bank_account->id).'" ><button type="button" class="btn btn-success btn-circle"><i class="fa fa-check"></i> </button></a>';
																	break;
																case '2': 
																	echo '<a href="'.admin_url('certification/user_bankaccount_edit?from=risk&id='.$value->bank_account->id).'" ><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i> </button></a>';
																	break;
																case '3': 
																	echo '<a href="'.admin_url('certification/user_bankaccount_edit?from=risk&id='.$value->bank_account->id).'" class="btn btn-default btn-md" >驗證</a>';
																	break;
																default:
																	break;
															}
														}else{
															switch($value->certification[$k]["user_status"]){
																case '0': 
																	echo '<a href="'.admin_url('certification/user_certification_edit?from=risk&id='.$certification_id).'" ><button type="button" class="btn btn-warning btn-circle"><i class="fa fa-refresh"></i> </button></a>';
																	break;
																case '1':
																	echo '<a href="'.admin_url('certification/user_certification_edit?from=risk&id='.$certification_id).'" ><button type="button" class="btn btn-success btn-circle"><i class="fa fa-check"></i> </button></a>';
																	break;
																case '2': 
																	echo '<a href="'.admin_url('certification/user_certification_edit?from=risk&id='.$certification_id).'" ><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i> </button></a>';
																	break;
																case '3': 
																	echo '<a href="'.admin_url('certification/user_certification_edit?from=risk&id='.$certification_id).'" class="btn btn-default btn-md" >驗證</a>';
																	break;
																default:
																	break;
															}
														}
													}
													echo '</td>';
												}
												}
											?>
                                            <td><button class="btn btn-outline btn-danger" onclick="failed(<?=isset($value->id)?$value->id:"" ?>,'<?=isset($value->target_no)?$value->target_no:"" ?>');" >退件</button></td>                                          
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
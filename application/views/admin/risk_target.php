        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">風控專區</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
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
                                <table  class="display responsive nowrap" width="100%" style="text-align: center;" id="dataTables-tables">
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
										if(isset($list) && !empty($list)){
											$count = 0;
											foreach($list as $key => $value){
												$count++;
									?>
                                        <tr class="<?=$count%2==0?"odd":"even"; ?>">
                                            <td>
												<?=isset($value->target_no)?$value->target_no:"" ?>
											</td>
											<td>
												<a class="fancyframe" href="<?=admin_url('User/display?id='.$value->user_id) ?>" >
													<?=isset($value->user_id)?$value->user_id:"" ?>
												</a>
											</td>
                                            <td><?=isset($product_name[$value->product_id])?$product_name[$value->product_id]:"" ?></td>
											<td><?=isset($status_list[$value->status])?$status_list[$value->status]:"" ?></td>
											<td><?=isset($value->updated_at)?date("Y-m-d H:i:s",$value->updated_at):"" ?></td>
											<? if($certification){
												foreach($certification as $k => $v){
													echo '<td>';
													if(isset($certification_list[$value->user_id]) && $certification_list[$value->user_id][$k]["user_status"]!=null){
														$user_status 		= $certification_list[$value->user_id][$k]["user_status"];
														$certification_id 	= $certification_list[$value->user_id][$k]["certification_id"];
														
														switch($user_status){
															case '0': 
																echo '<button type="button" class="btn btn-success btn-circle"><i class="fa fa-check"></i> </button>';
																break;
															case '1': 
																echo '<button type="button" class="btn btn-success btn-circle"><i class="fa fa-check"></i> </button>';
																break;
															case '2': 
																echo '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i></button>';
																break;
															default:
																break;
														}
													}
													echo '</td>';
												}
												}
											?>
                                            <td><button class="btn btn-outline btn-danger" onclick="rollback('.$value->id.')">退件</button></td>                                          
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
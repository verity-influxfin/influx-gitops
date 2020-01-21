        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">個人信用列表</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<script type="text/javascript">
				function showChang(){
					var user_id 			= $('#user_id').val();
					top.location = './credit?user_id='+user_id;
				}
			</script>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							<table>
								<tr>
									<td>會員ID：</td>
									<td><input type="text" value="<?=isset($_GET['user_id'])&&$_GET['user_id']!=""?$_GET['user_id']:""?>" id="user_id" /></td>	
									<td>
										<a href="javascript:showChang();" class="btn btn-default">查詢</a>
									</td>
								</tr>
							</table>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
											<th>會員 ID</th>
											<th>會員姓名</th>
                                            <th>產品</th>
                                            <th>信用等級</th>
                                            <th>信用分數</th>
											<th>信用額度</th>
                                            <th>狀態</th>
                                            <th>有效日期</th>
                                            <th>產生日期</th>
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
											<td><?=isset($value->user_id)?$value->user_id:'' ?></td>
											<td><?=isset($value->user_name)?$value->user_name:'' ?></td>
                                            <td><?=isset($product_list[$value->product_id])?$product_list[$value->product_id]['name']:'' ?><?=$value->sub_product_id!=0?' / '.$sub_product_list[$value->sub_product_id]['identity'][$product_list[$value->product_id]['identity']]['name']:'' ?></td>
                                            <td><?=isset($value->level)?$value->level:'' ?></td>
                                            <td><?=isset($value->points)?$value->points:'' ?></td>
                                            <td><?=isset($value->amount)?$value->amount:'' ?></td>
                                            <td><?=isset($status_list[$value->status])?$status_list[$value->status]:'' ?></td>
                                            <td><?=isset($value->expire_time)?date("Y-m-d H:i:s",$value->expire_time):'' ?></td>
                                            <td><?=isset($value->created_at)?date("Y-m-d H:i:s",$value->created_at):'' ?></td>
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
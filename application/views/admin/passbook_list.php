        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">虛擬帳號管理</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<script type="text/javascript">
				function showChang(){
					var user_id 			= $('#user_id').val();
					var virtual_account 	= $('#virtual_account').val();
					top.location = './index?user_id='+user_id+'&virtual_account='+virtual_account;
				}
			</script>
            <!-- /.row -->
            <div class="row">
			     <div class="col-lg-12">
                    <div class="panel panel-default">
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="display responsive nowrap" width="100%">
                                    <tbody>
									<tr class="list 0">
										<td>平台虛擬帳號</td>
										<td>
											<?=PLATFORM_VIRTUAL_ACCOUNT ?>
										</td>
										<td><a href="<?=admin_url('passbook/edit')."?id=".PLATFORM_VIRTUAL_ACCOUNT ?>" class="btn btn-default">查看明細</a></td> 
									</tr> 
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							<table>
								<tr>
									<td>會員 ID：</td>
									<td><input type="text" value="<?=isset($_GET['user_id'])&&$_GET['user_id']!=""?$_GET['user_id']:""?>" id="user_id" /></td>	
									<td>虛擬帳號：</td>
									<td><input type="text" value="<?=isset($_GET['virtual_account'])&&$_GET['virtual_account']!=""?$_GET['virtual_account']:""?>" id="virtual_account" /></td>
									<td><a href="javascript:showChang();" class="btn btn-default">查詢</a></td>
								</tr>
							</table>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="display responsive nowrap" width="100%">
                                    <thead>
                                        <tr>
											<th>會員 ID：</th>
                                            <th>虛擬帳號</th>
                                            <th>借款端/出借端</th>
                                            <th>狀態</th>
                                            <th>創建日期</th>
                                            <th>查看明細</th>
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
                                            <td><?=isset($value->user_id)?$value->user_id:"" ?></td>
											<td>
											<a class="fancyframe" href="<?=admin_url('Passbook/display?virtual_account='.$value->virtual_account) ?>" >
												<?=isset($value->virtual_account)?$value->virtual_account:"" ?>
											</a>
											</td>
											<td><?=isset($value->investor)?$investor_list[$value->investor]:"" ?></td>
                                            <td><?=isset($value->status)?$status_list[$value->status]:"" ?></td>
											<td><?=isset($value->created_at)?date("Y-m-d H:i:s",$value->created_at):"" ?></td>
											<td><a href="<?=admin_url('passbook/edit')."?id=".$value->id ?>" class="btn btn-default">Detail</a></td> 
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
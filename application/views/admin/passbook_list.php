        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">虛擬帳號管理</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
						會員 ID：<input type="text" value="" id="user_search" onkeypress="return number_only(event);" onkeyup="user_search()"/>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>虛擬帳號</th>
                                            <th>User ID</th>
                                            <th>借款端/出借端</th>
                                            <th>狀態</th>
                                            <th>創建日期</th>
                                            <th>查看明細</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<tr class="<?=$count%2==0?"odd":"even"; ?> list 0">
										<td>
										<a class="fancyframe" href="<?=admin_url('Passbook/display?virtual_account='.PLATFORM_VIRTUAL_ACCOUNT) ?>" >
												<?=PLATFORM_VIRTUAL_ACCOUNT ?>
											</a>
										</td>
										<td>平台虛擬帳號</td>
										<td></td>
										<td>正常</td>
										<td></td>
										<td><a href="<?=admin_url('passbook/edit')."?id=".PLATFORM_VIRTUAL_ACCOUNT ?>" class="btn btn-default">Detail</a></td> 
									</tr> 
									<?php 
										if(isset($list) && !empty($list)){
											$count = 0;
											foreach($list as $key => $value){
												$count++;
									?>
                                        <tr class="<?=$count%2==0?"odd":"even"; ?> list <?=isset($value->user_id)?$value->user_id:"" ?>">
                                            <td>
											<a class="fancyframe" href="<?=admin_url('Passbook/display?virtual_account='.$value->virtual_account) ?>" >
												<?=isset($value->virtual_account)?$value->virtual_account:"" ?>
											</a>
											</td>
                                            <td><a class="fancyframe" href="<?=admin_url('user/display?id='.$value->user_id) ?>" ><?=isset($value->user_id)?$value->user_id:"" ?></a></td>
											<td><?=isset($value->investor)?$investor_list[$value->investor]:"" ?></td>
                                            <td><?=isset($value->status)?$status_list[$value->status]:"" ?></td>
											<td><?=isset($value->created_at)?date("Y-m-d H:i:s",$value->created_at):"" ?></td>
											<td><a href="<?=admin_url('passbook/edit')."?id=".$value->id ?>" class="btn btn-default">Detail</a></td> 
                                        </tr>                                        
									<?php 
										}}else{
									?>
									<tr class="odd">
										<th class="text-center" colspan="10">目前尚無資料</th>
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
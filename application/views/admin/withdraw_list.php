        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">提領紀錄列表</h1>
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
                                        <tr class="<?=$count%2==0?"odd":"even"; ?> list <?=isset($value->user_id)?$value->user_id:"" ?>">
											<td><?=isset($value->id)?$value->id:"" ?></td>
                                            <td>
											<a class="fancyframe" href="<?=admin_url('Passbook/display?virtual_account='.$value->virtual_account) ?>" >
												<?=isset($value->virtual_account)?$value->virtual_account:"" ?>
											</a>
											</td>
                                            <td><a class="fancyframe" href="<?=admin_url('user/display?id='.$value->user_id) ?>" ><?=isset($value->user_id)?$value->user_id:"" ?></a></td>
											<td><?=isset($value->investor)?$investor_list[$value->investor]:"" ?></td>
											<td><?=isset($value->amount)?intval($value->amount):"" ?></td>
											<td><?=isset($value->frozen_id)?$value->frozen_id:"" ?></td>
                                            <td><?=isset($value->status)?$status_list[$value->status]:"" ?></td>
											<td><?=isset($value->created_at)?date("Y-m-d H:i:s",$value->created_at):"" ?></td>
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
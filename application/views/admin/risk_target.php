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
						
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="display responsive nowrap" width="100%" >
                                    <thead>
                                        <tr>
                                            <th>案號</th>
                                            <th>會員編號</th>
											<th>產品名稱</th>
                                            <th>狀態</th>
                                            <th>最後更新時間</th>
                                            <th>實名認證</th>
                                            <th>緊急聯絡人認證</th>
                                            <th>學生認證</th>
                                            <th>金融認證</th>
                                            <th>常用信箱認證</th>
                                            <th>社交認證</th>
                                            <th>財務認證</th>
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
                                        <tr class="<?=$count%2==0?"odd":"even"; ?> list <?=isset($value->user_id)?$value->user_id:"" ?>">
                                            <td>
											<a class="fancyframe" href="<?=admin_url('Passbook/display?virtual_account='.$value->virtual_account) ?>" >
												<?=isset($value->virtual_account)?$value->virtual_account:"" ?>
											</a>
											</td>
                                            <td><?=isset($value->user_id)?$value->user_id:"" ?></td>
											<td><?=isset($value->investor)?$investor_list[$value->investor]:"" ?></td>
											<td><?=isset($value->amount)?intval($value->amount):"" ?></td>
											<td><?=isset($value->frozen_id)?$value->frozen_id:"" ?></td>
                                            <td><?=isset($value->status)?$status_list[$value->status]:"" ?></td>
											<td><?=isset($value->created_at)?date("Y-m-d H:i:s",$value->created_at):"" ?></td>
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
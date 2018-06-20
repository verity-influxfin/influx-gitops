        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">投訴與建議</h1>
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
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User ID</th>
                                            <th>借款/出借</th>
                                            <th>內容</th>
                                            <th>處理人</th>
                                            <th>新增時間</th>
                                            <th>修改</th>
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
                                            <td><a href="<?=admin_url('user/edit?id='.$value->user_id) ?>"><?=isset($value->user_id)?$value->user_id:"" ?></a></td>
                                            <td><?=$value->investor?"出借端":"借款端" ?></td>
                                            <td><?=isset($value->content)?$value->content:"" ?></td>
                                            <td><?=$value->admin_id&&isset($name_list[$value->admin_id])?$name_list[$value->admin_id]:"" ?></td>
											<td><?=isset($value->created_at)?date("Y-m-d H:i:s",$value->created_at):"" ?></td>
											<td><a href="<?=admin_url('partner/edit')."?id=".$value->id ?>" class="btn btn-default">Edit</a></td> 
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
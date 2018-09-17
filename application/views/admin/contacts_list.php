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
                                <table class="table table-striped table-bordered table-hover" id="dataTables-tables">
                                    <thead>
                                        <tr>
                                            <th>NO.</th>
                                            <th>會員 ID</th>
                                            <th>借款端/出借端</th>
                                            <th>內容</th>
                                            <th>處理人</th>
                                            <th>處理狀態</th>
                                            <th>回報時間</th>
                                            <th>Edit</th>
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
                                            <td><a href="<?=admin_url('user/edit?id='.$value->user_id) ?>" target="_blank"><?=isset($value->user_id)?$value->user_id:"" ?></a></td>
                                            <td><?=isset($value->investor)?$investor_list[$value->investor]:"" ?></td>
                                            <td><?=isset($value->content)?$value->content:"" ?></td>
                                            <td><?=$value->admin_id&&isset($name_list[$value->admin_id])?$name_list[$value->admin_id]:"未處理" ?></td>
                                            <td><?=isset($value->status)?$status_list[$value->status]:"" ?></td>
											<td><?=isset($value->created_at)?date("Y-m-d H:i:s",$value->created_at):"" ?></td>
											<td><a href="<?=admin_url('contact/edit')."?id=".$value->id ?>" class="btn btn-default">Edit</a></td> 
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
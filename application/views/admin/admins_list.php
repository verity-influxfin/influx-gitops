        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">後台管理員</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							<a href="<?=admin_url('admin/add') ?>" class="btn btn-default float-right ">新增管理員</a>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>帳號</th>
                                            <th>角色</th>
                                            <th>姓名</th>
                                            <th>電話</th>
                                            <th>地址</th>
                                            <th>Email</th>
                                            <th>狀態</th>
                                            <th>創建者</th>
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
                                            <td><?=isset($value->account)?$value->account:"" ?></td>
                                            <td><?=isset($value->role_id)?$value->role_id:"" ?></td>
                                            <td><?=isset($value->name)?$value->name:"" ?></td>
                                            <td><?=isset($value->phone)?$value->phone:"" ?></td>
                                            <td><?=isset($value->address)?$value->address:"" ?></td>
                                            <td><?=isset($value->email)?$value->email:"" ?></td>
                                            <td><?=isset($value->status)?$value->status:"" ?></td>
                                            <td><?=isset($name_list[$value->creator_id])?$name_list[$value->creator_id]:"" ?></td>
											<td><a href="<?=admin_url('admin/edit')."?id=".$value->id ?>" class="btn btn-default">Edit</a></td> 
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
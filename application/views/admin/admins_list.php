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
							<a href="<?=admin_url('admin/add') ?>" class="btn btn-default btn-primary float-right ">新增管理員</a>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="display responsive nowrap" width="100%" id="dataTables-tables">
                                    <thead>
                                        <tr>
                                            <th>NO.</th>
                                            <th>帳號</th>
                                            <th>角色</th>
                                            <th>姓名</th>
                                            <th>電話</th>
                                            <th>地址</th>
                                            <th>Email</th>
                                            <th>QR code</th>
                                            <th>創建者</th>
                                            <th>狀態</th>
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
                                            <td><?=isset($value->account)?$value->account:"" ?></td>
                                            <td><?=isset($role_name[$value->role_id])?$role_name[$value->role_id]:"" ?></td>
                                            <td><?=isset($value->name)?$value->name:"" ?></td>
                                            <td><?=isset($value->phone)?$value->phone:"" ?></td>
                                            <td><?=isset($value->birthday)?date("m/d",strtotime($value->birthday)):"" ?></td>
                                            <td><?=isset($value->email)?$value->email:"" ?></td>
											<td><a href="<?=isset($value->my_promote_code)?$value->qrcode:"" ?>" data-fancybox="images" >https://event.influxfin.com/r/url?p=<?=$value->my_promote_code ?></a></td>
                                            <td><?=isset($name_list[$value->creator_id])?$name_list[$value->creator_id]:"" ?></td>
                                            <td <?=$value->status==0?'style="color:red;"':"" ?>><?=isset($status_list[$value->status])?$status_list[$value->status]:"" ?></td>
											<td><a href="<?=admin_url('admin/edit')."?id=".$value->id ?>" class="btn btn-default">Edit</a></td> 
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
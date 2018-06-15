        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">合作商管理</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							<a href="<?=admin_url('partner/add') ?>" class="btn btn-default float-right ">新增合作商</a>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>負責業務</th>
                                            <th>上層公司名稱</th>
                                            <th>公司名稱</th>
                                            <th>負責人姓名</th>
                                            <th>QR code</th>
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
                                            <td><?=$value->admin_id&&isset($name_list[$value->admin_id])?$name_list[$value->admin_id]:"" ?></td>
											<td><?=$value->parent_id&&isset($partner_name[$value->parent_id])?$partner_name[$value->parent_id]:"" ?></td>
                                            <td><?=isset($value->company)?$value->company:"" ?></td>
                                            <td><?=isset($value->name)?$value->name:"" ?></td>
                                            <td><img src="<?=isset($value->my_promote_code)?$value->qrcode:"" ?>" /></td>
                                            <td><?=isset($name_list[$value->creator_id])?$name_list[$value->creator_id]:"" ?></td>
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
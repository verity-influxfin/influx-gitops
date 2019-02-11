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
							<a href="<?=admin_url('partner/add') ?>" class="btn btn-primary float-right" >新增合作商</a>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="display responsive nowrap" width="100%" id="dataTables-paging">
                                    <thead>
                                        <tr>
											<th>公司名稱</th>
											<th>負責人姓名</th>
											<th>聯絡電話</th>
                                            <th>類別</th>
                                            <th>上層公司名稱</th>
                                            <th>學校</th>
                                            <th>QR code</th>
											<th>負責業務</th>
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
											<td><?=isset($value->company)?$value->company:"" ?></td>
                                            <td><?=isset($value->name)?$value->name:"" ?></td>
                                            <td><?=isset($value->phone)?$value->phone:"" ?></td>
											<td><?=$value->type&&isset($partner_type[$value->type])?$partner_type[$value->type]:"" ?></td> 
											<td><?=$value->parent_id&&isset($partner_name[$value->parent_id])?$partner_name[$value->parent_id]:"" ?></td>
                                            <td><?=isset($value->school)?$value->school:"" ?></td>
                                            <td><a href="<?=isset($value->my_promote_code)?$value->qrcode:"" ?>" data-fancybox="images" ><?=$value->my_promote_code ?></a></td>
                                            <td><?=$value->admin_id&&isset($name_list[$value->admin_id])?$name_list[$value->admin_id]:"" ?></td> 
											<td><?=isset($name_list[$value->creator_id])?$name_list[$value->creator_id]:"" ?></td>
											<td><a href="<?=admin_url('partner/edit')."?id=".$value->id ?>" class="btn btn-default">Edit</a></td> 
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
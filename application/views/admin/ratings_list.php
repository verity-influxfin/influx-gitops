        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">評級方式列表</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							<a href="<?=admin_url('rating/add') ?>" class="btn btn-default float-right ">新增評級方式</a>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-tables">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>代號</th>
                                            <th>名稱</th>
                                            <th>簡介</th>
                                            <th>狀態</th>
                                            <th>創建日期</th>
                                            <th>創建者</th>
											<th>是否已建置判斷</th>
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
                                            <td><?=isset($value->alias)?$value->alias:"" ?></td>
                                            <td><?=isset($value->name)?$value->name:"" ?></td>
                                            <td><?=isset($value->description)?$value->description:"" ?></td>
											<td><?=isset($value->status)?$value->status:"" ?></td>
											<td><?=isset($value->created_at)&&!empty($value->created_at)?date("Y-m-d H:i:s",$value->created_at):"" ?></td>
											<td><?=isset($name_list[$value->creator_id])?$name_list[$value->creator_id]:"" ?></td>
											<td><?=isset($value->set)&&$value->set?"可使用":"未建置" ?></td>
											<td><a href="<?=admin_url('rating/edit')."?id=".$value->id ?>" class="btn btn-default">Edit</a></td> 
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
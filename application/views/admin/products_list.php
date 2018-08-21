        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">產品列表</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							<a href="<?=admin_url('product/add') ?>" class="btn btn-primary float-right" >新增產品</a>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>NO.</th>
                                            <th>名稱</th>
                                            <th>借款額度</th>
                                            <th>年利率下限（%）</th>
											<th>年利率上限（%）</th>
                                            <th>狀態</th>
                                            <th>創建日期</th>
                                            <th>創建者</th>
                                            <!--th>設定評級加權</th-->
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
                                            <td><?=isset($value->name)?$value->name:"" ?></td>
											<td><?=isset($value->loan_range_s)?$value->loan_range_s:"" ?> - <?=isset($value->loan_range_e)?$value->loan_range_e:"" ?></td>
											<td><?=isset($value->interest_rate_s)?$value->interest_rate_s:"" ?></td>
											<td><?=isset($value->interest_rate_e)?$value->interest_rate_e:"" ?></td>
											<td><?=isset($value->status)?$status_list[$value->status]:"" ?></td>
											<td><?=isset($value->created_at)&&!empty($value->created_at)?date("Y-m-d H:i:s",$value->created_at):"" ?></td>
											<td><?=isset($name_list[$value->creator_id])?$name_list[$value->creator_id]:"" ?></td>
											<!--td><a href="<?=admin_url('product/rating_edit')."?id=".$value->id ?>" class="btn btn-default">設定</a></td--> 
											<td><a href="<?=admin_url('product/edit')."?id=".$value->id ?>" class="btn btn-default">Edit</a></td> 
                                        </tr>                                        
									<?php 
										}}else{
									?>
									<tr class="odd">
										<th class="text-center" colspan="11">目前尚無資料</th>
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
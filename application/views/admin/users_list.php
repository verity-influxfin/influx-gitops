        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">會員列表</h1>
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
                                <table class="table table-striped table-bordered table-hover" id="dataTables-paging">
                                    <thead>
                                        <tr>
                                            <th>會員 ID</th>
                                            <th>姓名</th>
                                            <th>電話</th>
                                            <th>性別</th>
                                            <th>Email</th>
                                            <th>借款端帳號</th>
                                            <th>出借帳號</th>
                                            <th>是否封鎖</th>
                                            <th>註冊邀請碼</th>
                                            <th>創建日期</th>
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
                                        <tr class="<?=$count%2==0?"odd":"even"; ?> list <?=isset($value->id)?$value->id:"" ?>">
                                            <td><?=isset($value->id)?$value->id:"" ?></td>
                                            <td><?=isset($value->name)?$value->name:"" ?></td>
                                            <td><?=isset($value->phone)?$value->phone:"" ?></td>
                                            <td><?=isset($value->sex)?$value->sex:"" ?></td>
                                            <td><?=isset($value->email)?$value->email:"" ?></td>
											<td><?=isset($value->status)&&$value->status?"正常":"未申請" ?></td>
											<td><?=isset($value->investor_status)&&$value->investor_status?"正常":"未申請" ?></td>
											<td><?=isset($value->block_status)&&$value->block_status?"封鎖":"否" ?></td>
											<td><?=isset($value->promote_code)?$value->promote_code:"" ?></td>
											<td><?=isset($value->created_at)&&!empty($value->created_at)?date("Y-m-d H:i:s",$value->created_at):"" ?></td>
											<td><a href="<?=admin_url('user/edit')."?id=".$value->id ?>" class="btn btn-default">Detail</a></td> 
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
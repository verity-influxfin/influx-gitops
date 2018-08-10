        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">會員認證申請</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<script type="text/javascript">
				function showChang(){
					var investor 			= $('#investor :selected').val();
					var certification_id 	= $('#certification_id :selected').val();
					var status 				= $('#status :selected').val();
					top.location = './user_certification_list?investor='+investor+'&certification_id='+certification_id+'&status='+status;
				}
			</script>
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
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>會員 ID</th>
                                            <th>出借/借款
												<select id="investor" onchange="showChang();">
													<option value="" >請選擇</option>
													<? foreach($investor_list as $key => $value){ ?>
														<option value="<?=$key?>" <?=isset($_GET['investor'])&&$_GET['investor']!=""&&intval($_GET['investor'])==intval($key)?"selected":""?>><?=$value?></option>
													<? } ?>
												</select>
											</th>
                                            <th>認證方式
												<select id="certification_id" onchange="showChang();">
													<option value="" >請選擇</option>
													<? foreach($certification_list as $key => $value){ ?>
														<option value="<?=$key?>" <?=isset($_GET['certification_id'])&&$_GET['certification_id']==$key?"selected":""?>><?=$value?></option>
													<? } ?>
												</select>
											</th>
                                            <th>狀態
												<select id="status" onchange="showChang();">
													<option value="" >請選擇</option>
													<? foreach($status_list as $key => $value){ ?>
														<option value="<?=$key?>" <?=isset($_GET['status'])&&$_GET['status']!=""&&intval($_GET['status'])==intval($key)?"selected":""?>><?=$value?></option>
													<? } ?>
												</select>
											</th>
                                            <th>申請日期</th>
                                            <th>Detail</th>
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
                                            <td><?=isset($value->id)?$value->id:"" ?></td>
                                            <td><?=isset($value->user_id)?$value->user_id:"" ?></td>
                                            <td><?=isset($value->investor)?$investor_list[$value->investor]:"" ?></td>
                                            <td><?=isset($value->certification_id)?$certification_list[$value->certification_id]:"" ?></td>
											<td><?=isset($value->status)?$status_list[$value->status]:"" ?></td>
											<td><?=isset($value->created_at)&&!empty($value->created_at)?date("Y-m-d H:i:s",$value->created_at):"" ?></td>
											<td><a href="<?=admin_url('certification/user_certification_edit')."?id=".$value->id ?>" class="btn btn-default">Detail</a></td> 
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
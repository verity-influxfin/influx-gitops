        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">會員認證申請</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<script type="text/javascript">
				function showChang(){
					var user_id 			= $('#user_id').val();
					var certification_id 	= $('#certification_id :selected').val();
					var status 				= $('#status :selected').val();
					top.location = './user_certification_list?&certification_id='+certification_id+'&status='+status+'&user_id='+user_id;
				}
			</script>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							會員 ID：<input type="text" value="<?=isset($_GET['user_id'])&&$_GET['user_id']!=""?$_GET['user_id']:""?>" id="user_id" onkeypress="return number_only(event);" />
							認證方式：
							<select id="certification_id">
								<option value="" >請選擇</option>
								<? foreach($certification_list as $key => $value){ ?>
									<option value="<?=$key?>" <?=isset($_GET['certification_id'])&&$_GET['certification_id']==$key?"selected":""?>><?=$value?></option>
								<? } ?>
							</select>
							狀態：
							<select id="status">
								<option value="" >請選擇</option>
								<? foreach($status_list as $key => $value){ ?>
									<option value="<?=$key?>" <?=isset($_GET['status'])&&$_GET['status']!=""&&intval($_GET['status'])==intval($key)?"selected":""?>><?=$value?></option>
								<? } ?>
							</select>
							<a href="javascript:showChang();" class="btn btn-default">查詢</a>
						</div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="display responsive nowrap" width="100%" id="dataTables-tables">
                                    <thead>
                                        <tr>
                                            <th>會員 ID</th>
                                            <th>出借/借款</th>
                                            <th>認證方式</th>
                                            <th>狀態</th>
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
                                            <td><?=isset($value->user_id)?$value->user_id:"" ?></td>
                                            <td><?=isset($value->investor)?$investor_list[$value->investor]:"" ?></td>
                                            <td><?=isset($value->certification_id)?$certification_list[$value->certification_id]:"" ?></td>
											<td><?=isset($value->status)?$status_list[$value->status]:"" ?></td>
											<td><?=isset($value->created_at)&&!empty($value->created_at)?date("Y-m-d H:i:s",$value->created_at):"" ?></td>
											<td><a target="_blank" href="<?=admin_url('certification/user_certification_edit')."?id=".$value->id ?>" class="btn btn-default">Detail</a></td>
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
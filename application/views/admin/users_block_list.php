        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">鎖定帳號管理</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<script type="text/javascript">
				function showChang(){
					var user_id = $('#user_id').val();
					var phone 	= $('#phone').val();
					var name 	= $('#name').val();
					top.location = './index?id='+user_id+'&phone='+phone+'&name='+name;
				}
			</script>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="display responsive nowrap" width="100%">
                                    <thead>
                                        <tr>
                                            <th>會員 ID</th>
                                            <th>姓名</th>
                                            <th>電話</th>
                                            <th>性別</th>
                                            <th>Email</th>
                                            <th>借款端帳號</th>
                                            <th>出借帳號</th>
                                            <th>封鎖狀態</th>
                                            <th>解除</th>
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
											<td><?=isset($value->block_status)&&$value->block_status?$block_status_list[$value->block_status]:null ?></td>
											<td><a href="<?=admin_url('user/block_user')."?id=".$value->id ?>" class="btn btn-default">解除鎖定</a></td>
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
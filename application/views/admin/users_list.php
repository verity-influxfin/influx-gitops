        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">會員列表</h1>
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
                        <div class="panel-heading">
							<table>
								<tr>
									<td>會員ID：</td>
									<td><input type="text" value="<?=isset($_GET['id'])&&$_GET['id']!=""?$_GET['id']:""?>" id="user_id" /></td>	
									<td>電話：</td>
									<td><input type="text" value="<?=isset($_GET['phone'])&&$_GET['phone']!=""?$_GET['phone']:""?>" id="phone" /></td>
									<td>姓名：</td>
									<td><input type="text" value="<?=isset($_GET['name'])&&$_GET['name']!=""?$_GET['name']:""?>" id="name" /></td>
									<td><a href="javascript:showChang();" class="btn btn-default">查詢</a></td>
								</tr>
							</table>
                        </div>
                        <!-- /.panel-heading -->
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
											<td><a target="_blank" href="<?=admin_url('user/edit')."?id=".$value->id ?>" class="btn btn-default">Detail</a></td>
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
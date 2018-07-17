        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">會員金融帳號綁定</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<script type="text/javascript">
				function showChang(){
					var investor 			= $('#investor :selected').val();
					var verify 				= $('#verify :selected').val();
					top.location = './user_bankaccount_list?investor='+investor+'&verify='+verify;
				}
			</script>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							<a href="<?=admin_url('certification/user_bankaccount_verify') ?>" target="_blank" class="btn btn-primary float-right" >轉出驗證匯款列表</a>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User</th>
                                            <th>出借/借款
												<select id="investor" onchange="showChang();">
													<option value="" >請選擇</option>
													<? foreach($investor_list as $key => $value){ ?>
														<option value="<?=$key?>" <?=isset($_GET['investor'])&&$_GET['investor']!=""&&intval($_GET['investor'])==intval($key)?"selected":""?>><?=$value?></option>
													<? } ?>
												</select>
											</th>
                                            <th>銀行代碼</th>
                                            <th>分行代碼</th>
                                            <th>銀行帳號</th>
                                            <th>驗證狀況
												<select id="verify" onchange="showChang();">
													<option value="" >請選擇</option>
													<? foreach($verify_list as $key => $value){ ?>
														<option value="<?=$key?>" <?=isset($_GET['verify'])&&$_GET['verify']!=""&&intval($_GET['verify'])==intval($key)?"selected":""?>><?=$value?></option>
													<? } ?>
												</select>
											</th>
                                            <th>申請日期</th>
                                            <th>查看訊息</th>
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
                                            <td><?=isset($value->user_id)?$value->user_id:"" ?></td>
                                            <td><?=isset($value->investor)?$investor_list[$value->investor]:"" ?></td>
                                            <td><?=isset($value->bank_code)?$value->bank_code:"" ?></td>
                                            <td><?=isset($value->branch_code)?$value->branch_code:"" ?></td>
                                            <td><?=isset($value->bank_account)?$value->bank_account:"" ?></td>
											<td><?=isset($value->verify)?$verify_list[$value->verify]:"" ?></td>
											<td><?=isset($value->created_at)&&!empty($value->created_at)?date("Y-m-d H:i:s",$value->created_at):"" ?></td>
											<td><a href="<?=admin_url('certification/user_bankaccount_edit')."?id=".$value->id ?>" class="btn btn-default">查看訊息</a></td> 
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
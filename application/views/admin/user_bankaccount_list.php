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
				
				function success(id){
					if(confirm("確認驗證成功？")){
						if(id){
							$.ajax({
								url: './user_bankaccount_success?id='+id,
								type: 'GET',
								success: function(response) {
									alert(response);
									location.reload();
								}
							});
						}
					}
				}
				
				function failed(id){
					//if(confirm("確認驗證失敗？申請中的案件將自動取消")){
					if(confirm("確認驗證失敗？")){
						if(id){
							$.ajax({
								url: './user_bankaccount_failed?id='+id,
								type: 'GET',
								success: function(response) {
									alert(response);
									location.reload();
								}
							});
						}
					}
				}
				
				function resend(id){
					if(confirm("改回待驗證？")){
						if(id){
							$.ajax({
								url: './user_bankaccount_resend?id='+id,
								type: 'GET',
								success: function(response) {
									alert(response);
									location.reload();
								}
							});
						}
					}
				}
			</script>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							<a href="<?=admin_url('certification/user_bankaccount_verify') ?>" target="_self" class="btn btn-primary float-right" >轉出驗證匯款列表</a>
							出借/借款：
							<select id="investor" onchange="showChang();">
								<option value="" >請選擇</option>
								<? foreach($investor_list as $key => $value){ ?>
									<option value="<?=$key?>" <?=isset($_GET['investor'])&&$_GET['investor']!=""&&intval($_GET['investor'])==intval($key)?"selected":""?>><?=$value?></option>
								<? } ?>
							</select>
							驗證狀況：
							<select id="verify" onchange="showChang();">
								<option value="" >請選擇</option>
								<? foreach($verify_list as $key => $value){ ?>
									<option value="<?=$key?>" <?=isset($_GET['verify'])&&$_GET['verify']!=""&&intval($_GET['verify'])==intval($key)?"selected":""?>><?=$value?></option>
								<? } ?>
							</select>
						</div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="display responsive nowrap" width="100%" id="dataTables-tables">
                                    <thead>
                                        <tr>
                                            <th>會員 ID</th>
                                            <th>會員姓名</th>
                                            <th>出借/借款</th>
                                            <th>銀行代碼</th>
                                            <th>分行代碼</th>
                                            <th>銀行帳號</th>
											<th>驗證狀況</th>
											<th>系統驗證狀態</th>
											<th>失敗</th>
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
                                        <tr class="<?=$count%2==0?"odd":"even"; ?>  list <?=isset($value->user_id)?$value->user_id:"" ?>">
                                            <td><?=isset($value->user_id)?$value->user_id:"" ?></td>
                                            <td>
											<?=isset($value->user_name)?$value->user_name:"" ?>
											<? 
												if(isset($value->user_name_list) && !empty($value->user_name_list)){
													foreach($value->user_name_list as $k => $word){
														if(!iconv('UTF-8', 'BIG-5//IGNORE', $word)){
															if(isset($word_list[$word]) && $word_list[$word]){
																echo '<br><p">已處理特殊字：'.$word.'</p>';
															}else{
																echo '<br><a href="'.admin_url('certification/difficult_word_list').'" target="_blank"><p style="color:red;">請處理特殊字：'.$word.'</p></a>';
															}
														}
													}
												}
											?>
											</td>
                                            <td><?=isset($value->investor)?(!$value->back_image==''?$investor_list[$value->investor]:$investor_list[2]):"" ?></td>
                                            <td><?=isset($value->bank_code)?$value->bank_code:"" ?></td>
                                            <td><?=isset($value->branch_code)?$value->branch_code:"" ?></td>
                                            <td><?=isset($value->bank_account)?$value->bank_account:"" ?></td>
											<td>
											<?=isset($value->verify)?$verify_list[$value->verify]:"" ?>
											<? 	if($value->verify==2 && empty($value->user_name)){
													echo '<p style="color:red;">未實名認證</p>';
												}
												
												if($value->verify==3){
													echo '<br><button class="btn btn-success" title="驗證成功" onclick="success('.$value->id.')">通過</button>&nbsp;';
													echo '<button class="btn btn-danger" title="退回待驗證，可重新匯出" onclick="resend('.$value->id.')">重發</button>';
												}
											?>
											</td>
											<td><?=isset($value->sys_check)?$sys_check_list[$value->sys_check]:"" ?></td>											
											<td>
											<? 	
												if($value->verify!=1 && $value->verify!=4){
													echo '<button class="btn btn-danger" title="驗證不通過，會通知使用者，使用者可重新驗證" onclick="failed('.$value->id.')">不通過</button>';
												}
											?>
											</td>
											<td><?=isset($value->created_at)&&!empty($value->created_at)?date("Y-m-d H:i:s",$value->created_at):"" ?></td>
											<td><a href="<?=admin_url('certification/user_bankaccount_edit')."?id=".$value->id ?>" class="btn btn-default">Detail</a></td> 
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
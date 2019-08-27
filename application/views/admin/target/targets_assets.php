        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">債權 - 全部列表</h1>
                </div>
                <!-- /.col-lg-12 -->

			</div>
			<script type="text/javascript">
				function showChang(){
					var user_id 			= $('#user_id').val();
					var target_no 			= $('#target_no').val();
					var status 				= $('#status :selected').val();
                    if(user_id==''&&target_no==''&&status==''){
                        top.location = './index?all=all';
                    }
                    else {
                        top.location = './index?status=' + status + '&user_id=' + user_id + '&target_no=' + target_no;
                    }
				}

				function checked_all(){
					$('.investment').prop("checked", true);
					check_checked();
				}
				
				function check_checked(){
					var ids					= "";
					var assets_export 		= '<?=admin_url('transfer/assets_export') ?>';
					var amortization_export = '<?=admin_url('transfer/amortization_export') ?>';
					
					$('.investment:checked').each(function() {
						if(ids==""){
							ids += this.value;
						}else{
							ids += ',' + this.value;
						}		
					});
					
					if(ids!=""){
						$('#assets_export').attr('href',assets_export + '?ids=' + ids);
						$('#amortization_export').attr('href',amortization_export + '?ids=' + ids);
					}else{
						$('#assets_export').attr('href',"javascript:alert('請先選擇債權');");
						$('#amortization_export').attr('href',"javascript:alert('請先選擇債權');");
					}
					
				}
			</script>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							<table>
								<tr>
									<td>投資人ID：</td>
									<td><input type="text" value="<?=isset($_GET['user_id'])&&$_GET['user_id']!=""?$_GET['user_id']:""?>" id="user_id" /></td>	
									<td>案號：</td>
									<td><input type="text" value="<?=isset($_GET['target_no'])&&$_GET['target_no']!=""?$_GET['target_no']:""?>" id="target_no" /></td>
									<td></td>
								</tr>
								<tr>
									<td>狀態：</td>
									<td>
										<select id="status">
											<option value="" >請選擇</option>
											<? foreach($investment_status_list as $key => $value){ 
												if(in_array($key,$show_status)){
											?>
												<option value="<?=$key?>" <?=isset($_GET['status'])&&$_GET['status']!=""&&intval($_GET['status'])==intval($key)?"selected":""?>><?=$value?></option>
											<? }} ?>
										</select>
									</td>
									<td></td>
									<td>
										<a href="javascript:showChang();" class="btn btn-default">查詢</a>
									</td>
									<td>
									</td>
								</tr>
							</table>
							<a id="assets_export" href="javascript:alert('請先選擇債權');" target="_blank"  class="btn btn-primary float-right" >債權明細表</a>
							<a id="amortization_export" href="javascript:alert('請先選擇債權');" target="_blank"  class="btn btn-primary float-right" >預期本息回款明細表</a>
							
						</div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th>案號 <a href="javascript:void(0)" onclick="checked_all();" class="btn" >全選</a></th>
                                            <th>投資人 ID</th>
                                            <th>借款人 ID</th>
                                            <th>債權金額</th>
                                            <th>案件總額</th>
                                            <th>剩餘本金</th>
											<th>信用等級</th>
											<th>學校名稱</th>
                                            <th>學校科系</th>
											<th>年化利率</th>
                                            <th>期數</th>
                                            <th>還款方式</th>
                                            <th>放款日期</th>
                                            <th>逾期狀況</th>
                                            <th>債權狀態</th>
                                            <th>債轉時間</th>
                                            <th>案件狀態</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php 
										if(isset($list) && !empty($list)){
											$count = 0;
											foreach($list as $key => $value){
												$count++;
												$target = $targets[$value->target_id];
									?>
                                        <tr class="<?=$count%2==0?"odd":"even"; ?> list <?=isset($target->user_id)?$target->user_id:"" ?>">
											<td>
												<?=isset($target->target_no)?$target->target_no:"" ?>
												<input class="investment" type="checkbox" onclick="check_checked();" value="<?=isset($value->id)?$value->id:"" ?>" />
											</td>
                                            <td><?=isset($value->user_id)?$value->user_id:"" ?></td>
                                            <td><?=isset($target->user_id)?$target->user_id:"" ?></td>
                                            <td><?=isset($value->loan_amount)&&$value->loan_amount?$value->loan_amount:"" ?></td>
                                            <td><?=isset($target->loan_amount)&&$target->loan_amount?$target->loan_amount:"" ?></td>
											<td><?=isset($value->amortization_table)&&$value->amortization_table?$value->amortization_table["remaining_principal"]:"" ?></td>
										    <td><?=isset($target->credit_level)&&$target->credit_level?$target->credit_level:"" ?></td>
											<td><?=isset($school_list[$target->user_id]["school_name"])?$school_list[$target->user_id]["school_name"]:"" ?></td>
                                            <td><?=isset($school_list[$target->user_id]["school_department"])?$school_list[$target->user_id]["school_department"]:"" ?></td>
                                            <td><?=isset($target->interest_rate)&&$target->interest_rate?$target->interest_rate.'%':"" ?></td>
                                            <td><?=isset($target->instalment)?$target->instalment:"" ?></td>
                                            <td><?=isset($target->repayment)?$repayment_type[$target->repayment]:"" ?></td>
                                            <td><?=isset($target->loan_date)?$target->loan_date:"" ?></td>
                                            <td><?=isset($target->delay)?$delay_list[$target->delay]:"" ?></td>
                                            <td><?=$value->transfer_status==2?$transfer_status_list[$value->transfer_status]:$investment_status_list[$value->status] ?></td>
                                            <td><?=$value->transfer_status==2&&isset($transfers[$value->id]->transfer_date)?$transfers[$value->id]->transfer_date:"" ?></td>
                                            <td><?=isset($status_list[$target->status])?$status_list[$target->status]:"" ?></td>
											<td><a href="<?=admin_url('target/edit')."?id=".$target->id ?>" class="btn btn-default">Detail</a></td> 
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
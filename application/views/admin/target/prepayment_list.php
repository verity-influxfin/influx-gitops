        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">借款 - 提前還款</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<script type="text/javascript">
				function showChang(){
					var user_id 			= $('#user_id').val();
					var target_no 			= $('#target_no').val();
					top.location = './prepayment?&user_id='+user_id+'&target_no='+target_no;
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
									<td><input type="text" value="<?=isset($_GET['user_id'])&&$_GET['user_id']!=""?$_GET['user_id']:""?>" id="user_id" /></td>	
									<td>案號：</td>
									<td><input type="text" value="<?=isset($_GET['target_no'])&&$_GET['target_no']!=""?$_GET['target_no']:""?>" id="target_no" /></td>
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
                                            <th>案號</th>
                                            <th>會員 ID</th>
                                            <th>借款金額</th>
											<th>年化利率</th>
                                            <th>貸放期間</th>
                                            <th>放款日期</th>
                                            <th>狀態</th>
                                            <th>提前還款狀態</th>
                                            <th>提還金額</th>
                                            <th>提還本金</th>
                                            <th>提還利息</th>
                                            <th>提還違約金</th>
                                            <th>結息日期</th>
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
                                            <td><?=isset($value->target_no)?$value->target_no:"" ?></td>
                                            <td><?=isset($value->user_id)?$value->user_id:"" ?></td>
                                            <td><?=isset($value->loan_amount)&&$value->loan_amount?$value->loan_amount:"" ?></td>
                                            <td><?=isset($value->interest_rate)&&$value->interest_rate?$value->interest_rate:"" ?></td>
                                            <td><?=isset($value->instalment)?$instalment_list[$value->instalment]:"" ?></td>
                                            <td><?=isset($value->loan_date)?$value->loan_date:"" ?></td>
                                            <td><?=isset($status_list[$value->status])?$status_list[$value->status]:"" ?></td>
                                            <td><?=isset($sub_list[$value->sub_status])?$sub_list[$value->sub_status]:"" ?></td>
											<td><?=isset($value->prepayment->amount)?$value->prepayment->amount:"" ?></td>
											<td><?=isset($value->prepayment->principal)?$value->prepayment->principal:"" ?></td>
											<td><?=isset($value->prepayment->interest)?$value->prepayment->interest:"" ?></td>
											<td><?=isset($value->prepayment->damage)?$value->prepayment->damage:"" ?></td>
											<td><?=isset($value->prepayment->settlement_date)?$value->prepayment->settlement_date:"" ?></td>
                                            <td><?=isset($value->prepayment->created_at)?date("Y-m-d H:i:s",$value->prepayment->created_at):"" ?></td>
											<td><a href="<?=admin_url('target/edit')."?id=".$value->id ?>" class="btn btn-default">Detail</a></td> 
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
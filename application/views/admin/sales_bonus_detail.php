        <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							<?=$sdate.' - '.$edate.'   '.$name?>
						</div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>案號</th>
                                            <th>產品</th>
                                            <th>會員 ID</th>
                                            <th>核准金額</th>
                                            <th>放款日期</th>
                                            <th>申請日期</th>
                                            <th>邀請碼</th>
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
											<td><?=$count ?></td>
											<td><?=isset($value->target_no)?$value->target_no:"" ?></td>
                                            <td><?=isset($product_name[$value->product_id])?$product_name[$value->product_id]:"" ?></td>
                                            <td><?=isset($value->user_id)?$value->user_id:"" ?></td>
                                            <td><?=isset($value->loan_amount)&&$value->loan_amount?$value->loan_amount:"" ?></td>
                                            <td><?=isset($value->loan_date)?$value->loan_date:"" ?></td>
                                            <td><?=isset($value->created_at)?date("Y-m-d H:i:s",$value->created_at):"" ?></td>
                                            <td><?=isset($value->promote_code)?$value->promote_code:"" ?></td>
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
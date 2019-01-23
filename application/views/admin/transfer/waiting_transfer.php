        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">債權轉讓 - 待收購</h1>
                </div>
                <!-- /.col-lg-12 -->

			</div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
						</div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" width="100%" id="dataTables-tables">
                                    <thead>
                                        <tr>
                                            <th>案號</th>
                                            <th>出讓人會員 ID</th>
                                            <th>債權金額</th>
                                            <th>案件總額</th>
											<th>年化利率</th>
                                            <th>價金</th>
                                            <th>剩餘本金</th>
                                            <th>本期利息</th>
                                            <th>本期回款手續費</th>
                                            <th>剩餘期數</th>
                                            <th>有效時間</th>
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
											 <td>
												<?=isset($value->target->target_no)?$value->target->target_no:"" ?>
											 </td>
                                            <td><?=isset($value->investment->user_id)&&$value->investment->user_id?$value->investment->user_id:"" ?></td>
                                            <td><?=isset($value->investment->loan_amount)&&$value->investment->loan_amount?$value->investment->loan_amount:"" ?></td>
                                            <td><?=isset($value->target->loan_amount)&&$value->target->loan_amount?$value->target->loan_amount:"" ?></td>
                                            <td><?=isset($value->target->interest_rate)&&$value->target->interest_rate?floatval($value->target->interest_rate).'%':"" ?></td>
                                            <td><?=isset($value->amount)?$value->amount:"" ?></td>
                                            <td><?=isset($value->principal)?$value->principal:"" ?></td>
                                            <td><?=isset($value->interest)?$value->interest:"" ?></td>
                                            <td><?=isset($value->platform_fee)?$value->platform_fee:"" ?></td>
                                            <td><?=isset($value->instalment)?$value->instalment:"" ?></td>
                                            <td><?=isset($value->expire_time)?date("Y-m-d",$value->expire_time):"" ?></td>
											<td><a href="<?=admin_url('target/edit')."?id=".$value->target->id ?>" class="btn btn-default">Detail</a></td> 
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
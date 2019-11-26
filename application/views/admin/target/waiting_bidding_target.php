        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">借款 - 已上架</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <script type="text/javascript">
                function cancel(id){
                    if(confirm("確認強制下架？案件將退回前一狀態")){
                        if(id){
                            var p 		= prompt("請輸入退案原因，將自動通知使用者，不通知請按取消","");
                            var remark 	= "";
                            if(p){
                                remark = encodeURIComponent(p);
                            }
                            $.ajax({
                                url: './cancel_bidding?id='+id+'&remark='+remark,
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
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" width="100%" >
                                    <thead>
                                        <tr>
                                            <th>案號</th>
                                            <th>產品</th>
                                            <th>會員 ID</th>
                                            <th>信用等級</th>
                                            <th>學校名稱</th>
                                            <th>學校科系</th>
                                            <th>申請金額</th>
                                            <th>核准金額</th>
											<th>年化利率</th>
                                            <th>期數</th>
                                            <th>還款方式</th>
                                            <th>目前投標金額</th>
											<th>上架次數</th>
                                            <th>流標期限</th>
                                            <th>上架日期</th>
                                            <th>邀請碼</th>
                                            <th>二審</th>
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
                                        <tr class="<?=$count%2==0?"odd":"even"; ?>">
                                            <td><?=isset($value->target_no)?$value->target_no:'' ?></td>
                                            <td><?=isset($product_list[$value->product_id])?$product_list[$value->product_id]['name']:'' ?><?=$value->sub_product_id!=0?' / '.$sub_product_list[$value->sub_product_id]['identity'][$product_list[$value->product_id]['identity']]['name']:'' ?></td>
                                            <td>
												<a class="fancyframe" href="<?=admin_url('User/display?id='.$value->user_id) ?>" >
													<?=isset($value->user_id)?$value->user_id:'' ?>
												</a>
											</td>
											<td><?=isset($value->credit_level)?$value->credit_level:'' ?></td>
											<td><?=isset($school_list[$value->user_id]["school_name"])?$school_list[$value->user_id]["school_name"]:'' ?></td>
                                            <td><?=isset($school_list[$value->user_id]["school_department"])?$school_list[$value->user_id]["school_department"]:'' ?></td>
                                            <td><?=isset($value->amount)?$value->amount:'' ?></td>
                                            <td><?=isset($value->loan_amount)&&$value->loan_amount?$value->loan_amount:'' ?></td>
                                            <td><?=isset($value->interest_rate)&&$value->interest_rate?floatval($value->interest_rate):'' ?></td>
                                            <td><?=isset($value->instalment)?$instalment_list[$value->instalment]:'' ?></td>
                                            <td><?=isset($value->repayment)?$repayment_type[$value->repayment]:'' ?></td>
                                            <td><?=isset($value->invested)?$value->invested:'' ?></td>
											<td><?=isset($value->launch_times)?$value->launch_times:'' ?></td>
                                            <td><?=isset($value->expire_time)?date("Y-m-d H:i:s",$value->expire_time):'' ?></td>
                                            <td><?=isset($value->bidding_date)?date("Y-m-d H:i:s",$value->bidding_date):'' ?></td>
											<td><?=isset($value->promote_code)?$value->promote_code:'' ?></td>
                                            <td><button class="btn btn-danger" onclick="cancel(<?=isset($value->id)?$value->id:"" ?>)">下架</button></td>
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
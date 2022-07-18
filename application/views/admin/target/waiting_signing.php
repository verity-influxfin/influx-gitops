        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">借款 - 待簽約</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<script type="text/javascript">
				function checked_all(){
					$('.targets').prop("checked", true);
					check_checked();
				}
				
				function check_checked(){
                    var ids = "",ctr = $('#target_export').parent().find('.btn');
					$('.targets:checked').each(function() {
						if(ids==""){
							ids += this.value;
						}else{
							ids += ',' + this.value;
						}		
					});
                    if(ids!=""){
                        $('#target_export').val(ids);
                        ctr.prop('disabled',false);
                    }
                    else{
                        ctr.prop('disabled',true);
                    }
				}
                function order_fail(id){
                    if(confirm("確認自待簽約退件？案件將自動取消")){
                        if(id){
                            var p 		= prompt("請輸入退案原因，將自動通知使用者，不通知請按取消",'');
                            var remark 	= '';
                            if(p){
                                remark = encodeURIComponent(p);
                            }
                            $.ajax({
                                url: './order_fail?id='+id+'&remark='+remark,
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
                            <form action="<?=admin_url('target/target_waiting_signing_export') ?>" method="post" style="display: inline-block">
                                <input type="submit" class="btn btn-primary float-right" value="匯出Excel" disabled />
                                <input id="target_export" type="hidden" name="ids" />
                                <input type="hidden" name="status" value="1" />
                            </form>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th>案號 <a href="javascript:void(0)" onclick="checked_all();" class="btn" >全選</a></th>
                                            <th>產品</th>
                                            <th>會員 ID</th>
                                            <th>信用等級</th>
                                            <th>學校名稱</th>
                                            <th>學校科系</th>
                                            <th>借款金額</th>
											<th>剩餘本金</th>
											<th>年化利率</th>
                                            <th>貸放期間</th>
                                            <th>計息方式</th>
                                            <th>每月回款</th>
                                            <th>本息總額</th>
                                            <th>放款日期</th>
                                            <th>逾期狀況</th>
                                            <th>狀態</th>
                                            <th>申請日期</th>
											<th>核准日期</th>
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
												<input class="targets" type="checkbox" onclick="check_checked();" value="<?=isset($value->id)?$value->id:"" ?>" />
												<?=isset($value->target_no)?$value->target_no:"" ?>
											 </td>
                                            <td><?=isset($product_list[$value->product_id])?$product_list[$value->product_id]['name']:'' ?><?=$value->sub_product_id!=0?' / '.$sub_product_list[$value->sub_product_id]['identity'][$product_list[$value->product_id]['identity']]['name']:'' ?></td>
                                            <td><?=isset($value->user_id)?$value->user_id:"" ?></td>
                                            <td><?=isset($value->credit_level)?$value->credit_level:"" ?></td>
											<td><?=isset($school_list[$value->user_id]["school_name"])?$school_list[$value->user_id]["school_name"]:"" ?></td>
                                            <td><?=isset($school_list[$value->user_id]["school_department"])?$school_list[$value->user_id]["school_department"]:"" ?></td>
                                            <td><?=isset($value->loan_amount)&&$value->loan_amount?$value->loan_amount:"" ?></td>
											<td><?=isset($value->amortization_table["remaining_principal"])?$value->amortization_table["remaining_principal"]:"" ?></td>
                                            <td><?=isset($value->interest_rate)&&$value->interest_rate?floatval($value->interest_rate).'%':"" ?></td>
                                            <td><?=isset($value->instalment)?$value->instalment:"" ?></td>
                                            <td><?=isset($value->repayment)?$repayment_type[$value->repayment]:"" ?></td>
                                            <td><?=isset($value->amortization_table["total_payment_m"])?$value->amortization_table["total_payment_m"]:"" ?></td>
                                            <td><?=isset($value->amortization_table["total_payment"])?$value->amortization_table["total_payment"]:"" ?></td>
                                            <td><?=isset($value->loan_date)?$value->loan_date:"" ?></td>
                                            <td><?=isset($value->delay)?$delay_list[$value->delay]:"" ?> <?=$value->delay?$value->delay_days.'天':"" ?></td>
                                            <td>
                                                <?
                                                    if($value->status == 21){
                                                        echo '<button class="btn btn-danger" onclick="order_fail('.$value->id.')">消費貸退件</button>';
                                                    }
                                                ?>
											<?=isset($status_list[$value->status])?$status_list[$value->status]:"" ?>
											<?=$value->sub_status!= 0 ?'('.$sub_list[$value->sub_status].')':"" ?>
											</td>
                                            <td><?=isset($value->created_at)?date("Y-m-d H:i:s",$value->created_at):"" ?></td>
											<td><?=isset($value->credit)?date("Y-m-d H:i:s",$value->credit->created_at):"" ?></td>
											<td><a href="<?=admin_url('target/target_waiting_signing_detail')."?id=".$value->id ?>" class="btn btn-default">Detail</a></td>
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
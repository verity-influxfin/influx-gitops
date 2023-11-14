        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">借款 - 還款中</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<script type="text/javascript">
				function checked_all(){
					$('.targets').prop("checked", true);
					check_checked();
				}
				function check_checked(){
                    var ids = "",ctr = $('#amortization_export,#target_export').parent().find('.btn');
                    $('.targets:checked').each(function() {
                        if(ids==''){
                            ids += this.value;
                        }else{
                            ids += ',' + this.value;
                        }
                    });
                    if(ids!=""){
                        $('#target_export').val(ids);
                        $('#amortization_export').val(ids);
                        ctr.prop('disabled',false);
                    }
                    else{
                        ctr.prop('disabled',true);
                    }
				}
                $(document).off("click","#selAll").on("click","#selAll" ,  function(){
                    var ctr = $('#amortization_export,#target_export').parent().find('.btn');
                    $(this).prop('checked')==true?($('.targets,#chl').hide(),ctr.prop('disabled',false)):($('.targets,#chl').show(),ctr.prop('disabled',true));
                });

			</script>
            <div class="category-tab">
                <button class="category-tab-item active" id="tab1" onclick="location.search = 'tab=personal'">個金</button>
                <button class="category-tab-item" id="tab2" onclick="location.search = 'tab=enterprise'">企金</button>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <form action="<?=admin_url('target/target_repayment_export') ?>" method="post" style="display: inline-block">
                                <input type="submit" class="btn btn-primary float-right" value="匯出Excel" disabled />
                                <input id="target_export" type="hidden" name="ids" />
                            </form>
                            <form action="<?=admin_url('target/amortization_export') ?>" method="post" style="display: inline-block">
                                <input type="submit" class="btn btn-primary float-right" value="本金餘額攤還表" disabled />
                                <input id="amortization_export" type="hidden" name="ids" />
                            </form>
                            <input type="checkbox" id="selAll" style="margin: 0 2px 0 10px;">所有還款中資料
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
                                        <?php if(($category ?? '') != 'enterprise') { ?>
                                            <th>案號 <a href="javascript:void(0)" onclick="checked_all();" id="chl" class="btn" >全選</a></th>
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
                                        <?php } else { ?>
                                            <th>案號 <a href="javascript:void(0)" onclick="checked_all();" id="chl" class="btn" >全選</a></th>
                                            <th>產品</th>
                                            <th>會員 ID</th>
                                            <th>信用等級</th>
                                            <th>公司名稱</th>
                                            <th>放款金額</th>
                                            <th>年化利率</th>
                                            <th>貸放期間</th>
                                            <th>計息方式</th>
                                            <th>已還款期數</th>
                                            <th>放款日期</th>
                                            <th>逾期狀況</th>
                                            <th>狀態</th>
                                            <th>申請日期</th>
                                            <th>核准日期</th>
                                            <th>Detail</th>
                                        <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php 
										if(isset($list) && !empty($list)){
											$count = 0;
											foreach($list as $key => $value){
												$count++;
									?>
                                        <?php if(($category ?? '') != 'enterprise') { ?>
                                        <tr class="<?=$count%2==0?"odd":"even"; ?> list <?=isset($value->user_id)?$value->user_id:'' ?>">
											 <td>
												<input class="targets" type="checkbox" onclick="check_checked();" value="<?=isset($value->id)?$value->id:'' ?>" />
												<?=isset($value->target_no)?$value->target_no:'' ?>
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
											<?=isset($status_list[$value->status])?$status_list[$value->status]:'' ?>
											</td>
                                            <td><?=isset($value->created_at)?date("Y-m-d H:i:s",$value->created_at):"" ?></td>
											<td><?=isset($value->credit)?date("Y-m-d H:i:s",$value->credit->created_at):"" ?></td>
											<td><a href="<?=admin_url('target/target_repayment_detail')."?id=".$value->id ?>" class="btn btn-default">Detail</a></td>
                                        </tr>
                                    <?php } else { ?>
                                        <tr class="<?=$count%2==0?"odd":"even"; ?>">
                                            <td>
                                                <input class="targets" type="checkbox" onclick="check_checked();" value="<?=isset($value->id)?$value->id:'' ?>" />
                                                <?=isset($value->target_no)?$value->target_no:'' ?>
                                            </td>
                                            <td><?=isset($product_list[$value->product_id])?$product_list[$value->product_id]['name']:'' ?><?=$value->sub_product_id!=0?' / '.$sub_product_list[$value->sub_product_id]['identity'][$product_list[$value->product_id]['identity']]['name']:'' ?></td>
                                            <td>
                                                <a class="fancyframe" href="<?=admin_url('User/display?id='.$value->user_id) ?>" >
                                                    <?=isset($value->user_id)?$value->user_id:'' ?>
                                                </a>
                                            </td>
                                            <td><?=isset($value->credit_level)?$value->credit_level:'' ?></td>
                                            <td><?= $value->company??'' ?></td>
                                            <td><?= $value->loan_amount ?? '' ?></td>
                                            <td><? echo isset($value->interest_rate)&&$value->interest_rate != ''?floatval($value->interest_rate).'%':'-' ?></td>
                                            <td><?=isset($value->instalment)?$instalment_list[$value->instalment]:'' ?></td>
                                            <td><?=isset($value->repayment)?$repayment_type[$value->repayment]:'' ?></td>
                                            <td><?=isset($value->paid_instalment)?$value->paid_instalment.'期':'' ?></td>
                                            <td><?= $value->loan_date ?? '' ?></td>
                                            <td><?= (($value->delay ?? 0) == 1 ? '已逾期' : '未逾期') ?></td>
                                            <td><?=isset($status_list[$value->status])?$status_list[$value->status]:'' ?></td>
                                            <td><?= isset($value->created_at) ? date("Y-m-d",$value->created_at) : '-' ?></td>
                                            <td><?= $value->loan_date ?? '' ?></td>
                                            <td><a href="<?=admin_url('target/target_repayment_detail')."?id=".$value->id ?>" class="btn btn-default" target="_blank" rel="noopener noreferrer">Detail</a></td>
                                        </tr>
									<?php 
                                        }}}
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
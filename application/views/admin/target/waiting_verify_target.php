        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">借款 - 待上架</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<script type="text/javascript">
				function success(id){
					if(confirm("確認審批過件？")){
						if(id){
							$.ajax({
								url: './verify_success?id='+id,
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
					if(confirm("確認驗證失敗？案件將自動取消")){
						if(id){
							var p 		= prompt("請輸入退案原因，將自動通知使用者，不通知請按取消",'');
							var remark 	= '';
							if(p){
								remark = encodeURIComponent(p);
							}
							$.ajax({
								url: './verify_failed?id='+id+'&remark='+remark,
								type: 'GET',
								success: function(response) {
									alert(response);
									location.reload();
								}
							});
						}
					}
				}
                $(document).off("click","#send_bank").on("click","#send_bank" ,  function(){
                    $("#send_bank").attr("disabled",true);
                    ajpost(location.origin+"/admin/target/waiting_reinspection",
                        "target_id="+$("#send_bank").data("id")+
                        "&send_bank=1",true);
                    location.reload();
                });

			</script>
            <!-- /.row -->
            <div class="category-tab">
                <button class="category-tab-item active" id="tab1" onclick="location.search = 'tab=individual'">個金</button>
                <button class="category-tab-item" id="tab2" onclick="location.search = 'tab=enterprise'">企金</button>
            </div>
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
                                            <th>產品</th>
                                            <th>借款人ID</th>
                                            <th>申請金額</th>
                                            <th>核准金額</th>
											<th>核准利率</th>
                                            <th>貸放期間</th>
                                            <th>計息方式</th>
                                            <th>狀態</th>
                                            <th>申請日期</th>
                                            <th>備註</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php 
										if(isset($list) && !empty($list)){
											$count = 0;
											foreach($list as $key => $value){
                                                $isExternalCoop = in_array($value->product_id, $externalCooperation);
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
                                            <td><?=isset($value->amount)?$value->amount:'' ?></td>
                                            <td><? echo isset($value->loan_amount)&&$value->loan_amount?$value->loan_amount:'-' ?></td>
                                            <td><? echo isset($value->interest_rate)&&$value->interest_rate&&!$isExternalCoop?floatval($value->interest_rate).'%':'-' ?></td>
                                            <td><?=isset($value->instalment)?$instalment_list[$value->instalment]:'' ?></td>
                                            <td><?=isset($value->repayment)?$repayment_type[$value->repayment]:'' ?></td>
                                            <td>
												<?
                                                $unFinish = true;
                                                if($isExternalCoop){ ?>
												   <?= '<button class="category-tab-item active">待銀行審核中</button>' ?>
                                                <?}else{
                                                    if($value->bankaccount_verify==0){ ?>
                                                        <button class="btn btn-info"  onclick="window.location.href='<?=admin_url('certification/user_bankaccount_list?verify=2')."?id=".$value->id ?>'">待金融驗證</button>
                                                    <? }else{ ?>
                                                        <button <?=isset($value->subloan_count) && $value->subloan_count>2?" ":"" ?>class="btn  <?=$value->order_id==0?"btn-success":"btn-info" ?>" onclick="success(<?=isset($value->id)?$value->id:"" ?>)" <?=$value->sub_status==TARGET_SUBSTATUS_SECOND_INSTANCE?'disabled':'' ?>>審批上架</button>
                                                    <? } ?>
                                                    <button class="btn btn-danger" onclick="failed(<?=isset($value->id)?$value->id:'' ?>)">不通過</button>
                                                    <?=isset($sub_list[$value->sub_status])?($value->sub_status==9?'('.$sub_list[$value->sub_status].')':''):'' ?>
                                                <? } ?>
											</td>
                                            <td><?=isset($value->created_at)?date("Y-m-d H:i:s",$value->created_at):'' ?></td>
                                            <td><?=isset($value->remark)?$value->remark:'' ?></td>
											<td><a target="_blank" href="<? echo !$isExternalCoop ? admin_url('target/target_waiting_verify_detail')."?id=".$value->id : admin_url('target/waiting_reinspection')."?target_id=".$value->id ?>" class="btn btn-<? echo !$unFinish || !$isExternalCoop ? 'default' : 'danger' ?>"><? echo !$isExternalCoop ? '詳情' : '二審' ?></a></td>
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

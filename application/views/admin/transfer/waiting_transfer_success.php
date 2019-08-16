<style type="text/css">
    h4{display: inline-block;}
</style>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">債權轉讓 - 待放款</h1>
                </div>
                <!-- /.col-lg-12 -->
			<script type="text/javascript">
				function toloan(){
					var ids = "";
					var target_no = "";
					$('.transfer:checked').each(function() {
						if(ids==""){
							ids += this.value;
						}else{
							ids += ',' + this.value;
						}	
						if(target_no==""){
							target_no += $(this).attr("data-targetno");
						}else{
							target_no += ',' + $(this).attr("data-targetno");
						}							
					});
					if(ids==""){
						alert("請先選擇欲放行案件");
						return false;
					}
					if(confirm("確認放行下列案件："+target_no)){
						if(ids){
							$.ajax({
								url: './transfer_success?ids='+ids,
								type: 'GET',
								success: function(response) {
									alert(response);
									location.reload();
								}
							});
						}
					}
				}

                function toloanpkg(){
                    var ids = "";
                    var target_no = "";
                    $('.transferpkg:checked').each(function() {
                        if(ids==""){
                            ids += this.value;
                        }else{
                            ids += ',' + this.value;
                        }
                        if(target_no==""){
                            target_no += $(this).attr("data-targetpkgno");
                        }else{
                            target_no += ',' + $(this).attr("data-targetpkgno");
                        }
                    });
                    if(ids==""){
                        alert("請先選擇欲放行案件");
                        return false;
                    }
                    if(confirm("確認放行下列案件："+target_no)){
                        if(ids){
                            $.ajax({
                                url: './transfer_success?ids='+ids,
                                type: 'GET',
                                success: function(response) {
                                    alert(response);
                                    location.reload();
                                }
                            });
                        }
                    }
                }
				
				function cancel(id){
					if(confirm("確認取消債轉？")){
						if(id){
							$.ajax({
								url: './transfer_cancel?id='+id,
								type: 'GET',
								success: function(response) {
									alert(response);
									location.reload();
								}
							});
						}
					}
				}
				function cancelpkg(id){
					if(confirm("確認取消打包債轉？")){
						if(id){
							$.ajax({
								url: './c_transfer_cancel?id='+id,
								type: 'GET',
								success: function(response) {
									alert(response);
									location.reload();
								}
							});
						}
					}
				}

				function checked_all(){
					$('.transfer').prop("checked", true);
				}
				function checked_all_pkg(){
					$('.transferpkg').prop("checked", true);
				}
			</script>
			</div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><h4>單筆債權</h4>
							<a href="javascript:void(0)" onclick="toloan();" class="btn btn-primary float-right" >放行</a>
						</div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th>案號 <a href="javascript:void(0)" onclick="checked_all();" class="btn" >全選</a></th>
                                            <th>出讓人會員 ID</th>
                                            <th>受讓人會員 ID</th>
                                            <th>扣款時間</th>
                                            <th>債權金額</th>
											<th>年化利率</th>
                                            <th>價金</th>
                                            <th>剩餘本金</th>
                                            <th>剩餘利息</th>
                                            <th>剩餘延滯息</th>
                                            <th>剩餘期數</th>
                                            <th>有效時間</th>
											<th>取消</th>
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
												<input class="transfer" type="checkbox" data-targetno="<?=isset($value->target->target_no)?$value->target->target_no:"" ?>" value="<?=isset($value->id)?$value->id:"" ?>" />
												<?=isset($value->target->target_no)?$value->target->target_no:"" ?>
											 </td>
                                            <td><?=isset($value->investment->user_id)&&$value->investment->user_id?$value->investment->user_id:"" ?></td>
                                            <td><?=isset($value->transfer_investment->user_id)&&$value->transfer_investment->user_id?$value->transfer_investment->user_id:"" ?></td>
                                            <td><?=isset($value->transfer_investment->tx_datetime)&&$value->transfer_investment->tx_datetime?$value->transfer_investment->tx_datetime:"" ?></td>
                                            <td><?=isset($value->investment->loan_amount)&&$value->investment->loan_amount?$value->investment->loan_amount:"" ?></td>
                                            <td><?=isset($value->target->interest_rate)&&$value->target->interest_rate?floatval($value->target->interest_rate).'%':"" ?></td>
                                            <td><?=isset($value->amount)?$value->amount:"" ?></td>
                                            <td><?=isset($value->principal)?$value->principal:"" ?></td>
                                            <td><?=isset($value->interest)?$value->interest:"" ?></td>
                                            <td><?=isset($value->delay_interest)?$value->delay_interest:"" ?></td>
                                            <td><?=isset($value->instalment)?$value->instalment:"" ?></td>
                                            <td><?=isset($value->expire_time)?date("Y-m-d H:i:s",$value->expire_time):"" ?></td>
                                            <td><button class="btn btn-danger" onclick="cancel(<?=isset($value->id)?$value->id:"" ?>)">退回債轉</button></td>
											<td><a href="<?=admin_url('target/edit')."?id=".$value->target->id ?>" class="btn btn-default" target="_blank">Detail</a></td>
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
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><h4>打包債權</h4>
                            <a href="javascript:void(0)" onclick="toloanpkg();" class="btn btn-primary float-right" >放行</a>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" width="100%" id="dataTables-tables2">
                                    <thead>
                                    <tr>
                                        <th>案號 <a href="javascript:void(0)" onclick="checked_all_pkg();" class="btn" >全選</a></th>
                                        <th>出讓人會員ID</th>
                                        <th>債權筆數</th>
                                        <th>平均年化利率</th>
                                        <th>價金</th>
                                        <th>剩餘本金</th>
                                        <th>已發生利息</th>
                                        <th>已發生延滯息</th>
                                        <th>最小剩餘期數</th>
                                        <th>最大剩餘期數</th>
                                        <th>有效時間</th>
                                        <th>取消</th>
                                        <th>打包內容</th>
                                    </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
                                    <?php
                                    if(isset($combinations) && !empty($combinations)){
                                        $count = 0;
                                        foreach($combinations as $key => $value){
                                            $count++;
                                            ?>
                                            <tr class="<?=$count%2==0?"odd":"even"; ?> list <?=isset($value->user_id)?$value->user_id:"" ?>">
                                                <td>
                                                    <input class="transferpkg" type="checkbox" data-targetpkgno="<?=isset($value->combination_no)?$value->combination_no:"" ?>" value="<?=isset($value->transfer)?$value->transfer:"" ?>" />
                                                    <?=isset($value->combination_no)?$value->combination_no:"" ?>
                                                </td>
                                                <td><?=isset($value->user_id)?$value->user_id:"" ?></td>
                                                <td><?=isset($value->count)?$value->count:"" ?></td>
                                                <td><?=isset($value->interest_rate)?$value->interest_rate:"" ?></td>
                                                <td><?=isset($value->amount)?$value->amount:"" ?></td>
                                                <td><?=isset($value->principal)?$value->principal:"" ?></td>
                                                <td><?=isset($value->interest)?$value->interest:"" ?></td>
                                                <td><?=isset($value->delay_interest)?$value->delay_interest:"" ?></td>
                                                <td><?=isset($value->min_instalment)?$value->min_instalment:"" ?></td>
                                                <td><?=isset($value->max_instalment)?$value->max_instalment:"" ?></td>
                                                <td><?=isset($value->expire_time)?date("Y-m-d",$value->expire_time):"" ?></td>
                                                <td><button class="btn btn-danger" onclick="cancelpkg(<?=isset($value->id)?$value->id:"" ?>)">退回整包債轉</button></td>
                                                <td><a href="<?=admin_url('transfer/transfer_combination')."?id=".(isset($value->id)?$value->id:"")."&no=",(isset($value->combination_no)?$value->combination_no:"") ?>" class="btn btn-default fancyframe">詳情</a></td>
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
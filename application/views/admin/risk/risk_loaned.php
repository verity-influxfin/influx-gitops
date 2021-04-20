<style type="text/css">
body{
	font-family: 微軟正黑體;
}
.panel-heading{
	font-size: 16px;
    letter-spacing: 0.5px;
    font-weight: bold;
}
.panel-heading td{
  padding:0 5px;
}
</style>        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?=isset($slist)?"貸後已催收列表":"貸後催收列表" ?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<script type="text/javascript">
				function showChang(){
					var user_id 			= $('#user_id').val();
					var target_no 			= $('#target_no').val();
					var delay 				= $('#delay :selected').val();
					var status 				= $('#status :selected').val();
					top.location = './loaned_wait_push?delay='+delay+'&status='+status+'&user_id='+user_id+'&target_no='+target_no;
				}
				//$(document).on("click",".panel-body .btn-default" , function(){window.open($(this).attr('href'),'貸後催收系統',config='left=100,scrollbars=yes');});		
			</script>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"<?=isset($slist)?" style='display:none'":"" ?>>
							<table>
								<tr>
									<td>會員ID：</td>
									<td><input type="text" value="<?=isset($_GET['user_id'])&&$_GET['user_id']!=""?$_GET['user_id']:""?>" id="user_id" /></td>	
									<td>案號：</td>
									<td><input type="text" value="<?=isset($_GET['target_no'])&&$_GET['target_no']!=""?$_GET['target_no']:""?>" id="target_no" /></td>
									<td>催收狀態：</td>
									<td>
										<select id="status">
											<option value="" >請選擇</option>
											<? foreach($push_status_list as $key => $value){ ?>
												<option value="<?=$key?>" <?=isset($_GET['status'])&&$_GET['status']!=""&&intval($_GET['status'])==intval($key)?"selected":""?>><?=$value?></option>
											<? } ?>
										</select></td>
									<td><a href="javascript:showChang();" class="btn btn-default">查詢</a></td>
								</tr>
							</table>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th>案號</th>
                                            <th>產品</th>
                                            <th>會員 ID</th>
                                            <th>申請金額</th>
                                            <th>核准金額</th>
											<th>年化利率</th>
                                            <th>期數</th>
                                            <th>還款方式</th>
                                            <th>逾期狀況</th>
                                            <th>還款狀態</th>
                                            <th>申請日期</th>
                                            <th>催收狀態</th>
                                            <th>備註</th>
                                            <th>邀請碼</th>
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
                                            <td><?=isset($product_list[$value->product_id])?$product_list[$value->product_id]['name']:"" ?></td>
                                            <td><?=isset($value->user_id)?$value->user_id:"" ?></td>
                                            <td><?=isset($value->amount)?$value->amount:"" ?></td>
                                            <td><?=isset($value->loan_amount)&&$value->loan_amount?$value->loan_amount:"" ?></td>
                                            <td><?=isset($value->interest_rate)&&$value->interest_rate?$value->interest_rate:"" ?></td>
                                            <td><?=isset($value->instalment)?$instalment_list[$value->instalment]:"" ?></td>
                                            <td><?=isset($value->repayment)?$repayment_type[$value->repayment]:"" ?></td>
                                            <td><?=isset($value->delay)?$delay_list[$value->delay]:"" ?></td>
                                            <td>
											<?=isset($status_list[$value->status])?$status_list[$value->status]:"" ?>
											<? 	if($value->status==2 && !$value->bank_account_verify){
													echo '<p style="color:red;">金融帳號未驗證</p>';
												}
											?>
											</td>
                                            <td><?=isset($value->created_at)?date("Y-m-d H:i:s",$value->created_at):"" ?></td>
											<td><?=isset($value->push_status)?$push_status_list[$value->push_status]:"" ?></td>
											<td><?=isset($value->remark)?$value->remark:"" ?></td>
											<td><?=isset($value->promote_code)?$value->promote_code:"" ?></td>
											<td><div href="<? echo admin_url('target/edit')."?risk=1&id=".$value->id.(isset($slist)?"&slist=1":"") ?>" class="btn btn-default fancyframe">Detail</div></td> 
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
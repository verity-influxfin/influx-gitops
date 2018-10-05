        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">交易收支統計表 
						<a href="javascript:void(0)" target="_blank" onclick="toloan();" class="btn btn-primary float-right" >匯出</a>
					</h1>
					
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<script type="text/javascript">
				function showChang(){
					var sdate 				= $('#sdate').val();
					var edate 				= $('#edate').val();
					top.location = './index?sdate='+sdate+'&edate='+edate;
				}
			</script>
			<style>
				td{
					padding:5px;
				}
			</style>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							<table>
								<tr>
									<td>指定區間：</td>
									<td><a href="<?=admin_url('Account/index') ?>" target="_self" class="btn btn-default float-right btn-md" >本月</a></td>
									<td><a href="<?=admin_url('Account/index?sdate=all&edate=all') ?>" target="_self" class="btn btn-default float-right btn-md" >全部</a></td>
									<td style="text-align: center;">|</td>
									<td><input type="text" value="<?=$sdate=='all'?"":$sdate ?>" id="sdate" data-toggle="datepicker"  /></td>
									<td>-</td>
									<td><input type="text" value="<?=$edate=='all'?"":$edate ?>" id="edate" data-toggle="datepicker" /></td>
									<td><a href="javascript:void(0)" onclick="showChang();" class="btn btn-default float-right btn-md" >查詢</a></td>
								</tr>
								<tr>
									<td colspan="4">查詢範圍區間：<?=$sdate=='all'?"全部":$sdate.' - '.$edate ?></td>
									<td colspan="4">查詢結果：<?=count($list) ?>筆</td>
								</tr>
							</table>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th>科目</th>
                                            <th>收入金額</th>
                                            <th>支出金額</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php 
										if(isset($list) && !empty($list)){
											$count = 0;
											foreach($list as $key => $value){
												$sum['debit'] 	+= $value['debit'];
												$sum['credit'] 	+= $value['credit'];
												$count++;
									?>
                                        <tr class="<?=$count%2==0?"odd":"even"; ?>">
                                            <td><?=isset($key)?$transaction_source[$key]:"" ?></td>
                                            <td style="text-align:right;"><?=isset($value['debit'])&&$value['debit']?number_format($value['debit']):"" ?></td>
                                            <td style="text-align:right;"><?=isset($value['credit'])&&$value['credit']?number_format($value['credit']):"" ?></td>
                                        </tr>                                        
									<?php 
										}}
									?>
										<tr>
                                            <td>合計</td>
                                            <td style="text-align:right;"><?=isset($sum['debit'])&&$sum['debit']?number_format($sum['debit']):"" ?></td>
                                            <td style="text-align:right;"><?=isset($sum['credit'])&&$sum['credit']?number_format($sum['credit']):"" ?></td>
                                        </tr>   
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
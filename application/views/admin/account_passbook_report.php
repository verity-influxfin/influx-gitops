        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">虛擬帳戶餘額明細表 
						<a href="javascript:void(0)" target="_blank" onclick="" class="btn btn-primary float-right" >匯出</a>
					</h1>
					
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<script type="text/javascript">
				function showChang(){
					var date 	 = $('#date').val();
					top.location = './passbook_report?date='+date;
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
									<td>指定日期：</td>
									<td><a href="<?=admin_url('Account/passbook_report') ?>" target="_self" class="btn btn-default float-right btn-md" >本日</a></td>
									<td style="text-align: center;">|</td>
									<td><input type="text" id="date" value="<?=$date ?>" data-toggle="datepicker"  /></td>
									<td><a href="javascript:void(0)" onclick="showChang();" class="btn btn-default float-right btn-md" >查詢</a></td>
								</tr>
								<tr>
									<td colspan="3">查詢日期：<?=$date ?></td>
									<td colspan="2">查詢結果：<?=count($list) ?>筆</td>
								</tr>
							</table>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th>虛擬帳號</th>
                                            <th>戶名</th>
                                            <th>借款端/出借端</th>
                                            <th>餘額</th>
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
                                            <td><?=isset($key)?$key:"" ?></td>
                                            <td><?=isset($info[$key])?$info[$key]->user_info->name:"" ?></td>
                                            <td><?=isset($info[$key])?$investor_list[$info[$key]->investor]:"" ?></td>
                                            <td><?=isset($value)?$value:"" ?></td>
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
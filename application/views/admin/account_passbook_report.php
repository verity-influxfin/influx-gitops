<script   src="<?=base_url()?>assets/admin/js/jquery.table2excel.js"></script>
       <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">虛擬帳戶餘額明細表 
                    <button type="button" id="btnExport" onclick="ExportToExcel();">匯出Excel</button> 
                </h1>
					
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<script type="text/javascript">
				function showChang(){
					var date 	 = $('#date').val();
					top.location = './passbook_report?date='+date;
                }
                function ExportToExcel() {      
                    $("#tableExcel").table2excel({
                    exclude : ".noExl", //過濾位置的css類名
                    filename : "虛擬帳號餘明細表.xls", // 
                    name: "Excel Document Name.xlsx",
                    exclude_img: false,//是否導出圖片false導出
                    exclude_links: true,//是否導出link false導出
                    exclude_inputs: true//是否導出inputs false導出
                    });            
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
                            <div id="myDiv" class="table-responsive">
                            <table id="tableExcel" width="100%" border="1" cellspacing="0" cellpadding="0" class="table table-striped table-bordered table-hover" >                                    <thead>
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
											foreach($list as $row){
												$count++;
									?>
                                        <tr class="<?=$count%2==0?"odd":"even"; ?>">
                                            <td><?= $row->virtual_account ?></td>
                                            <td><?= $row->name ?></td>
                                            <td><?= $investor_list[$row->investor_status] ?></td>
                                            <td style="text-align:right;"><?=isset($row->total_amount)?number_format($row->total_amount):"" ?></td>
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
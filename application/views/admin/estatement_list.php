        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">個人對帳單</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<script type="text/javascript">
				function showChang(){
					var investor 			= $('#investor :selected').val();
					top.location = './estatement?investor='+investor;
				}
			</script>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							出借/借款：
							<select id="investor" onchange="showChang();">
								<option value="" >請選擇</option>
								<? foreach($investor_list as $key => $value){ ?>
									<option value="<?=$key?>" <?=isset($_GET['investor'])&&$_GET['investor']!=""&&intval($_GET['investor'])==intval($key)?"selected":""?>><?=$value?></option>
								<? } ?>
							</select>
						</div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="display responsive nowrap" width="100%" id="dataTables-tables">
                                    <thead>
                                        <tr>
                                            <th>類型</th>
											<th>出借/借款</th>
                                            <th>日期</th>
                                            <th>會員 ID</th>
                                            <th>會員姓名</th>
                                            <th>對帳單</th>
                                            <th>產生日期</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php 
										if(isset($list) && !empty($list)){
											$count = 0;
											foreach($list as $key => $value){
												$count++;
									?>
                                        <tr class="<?=$count%2==0?"odd":"even"; ?>  list <?=isset($value->user_id)?$value->user_id:"" ?>">
                                            <td><?=isset($value->type)?$value->type:"" ?></td>
											<td><?=isset($value->investor)?$investor_list[$value->investor]:"" ?></td>
                                            <td><?=isset($value->sdate)?$value->sdate.' - '.$value->edate:"" ?></td>
                                            <td><?=isset($value->user_id)?$value->user_id:"" ?></td>
                                            <td><?=isset($value->user_name)?$value->user_name:"" ?></td>
                                            <td><a type="button" href="<?=isset($value->url)?$value->url:"" ?>" class="btn btn-info"><i class="fa fa-download"></i></a></td>
											<td><?=isset($value->created_at)&&!empty($value->created_at)?date("Y-m-d H:i:s",$value->created_at):"" ?></td>
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
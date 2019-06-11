        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">個人對帳單</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<script type="text/javascript">
				function showChang(){
					var user_id 			= $('#user_id').val();
					var sdate 				= $('#sdate').val();
					var edate 				= $('#edate').val();
					var investor 			= $('#investor :selected').val();
                    var name 				= $('#name').val();
					top.location = './estatement?investor='+investor+'&sdate='+sdate+'&edate='+edate+'&user_id='+user_id+'&name='+name;
				}
			</script>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							<table>
								<tr>
									<td>出借/借款：</td>
									<td>
									<select id="investor" >
										<option value="" >請選擇</option>
										<? foreach($investor_list as $key => $value){ ?>
											<option value="<?=$key?>" <?=isset($_GET['investor'])&&$_GET['investor']!=""&&intval($_GET['investor'])==intval($key)?"selected":""?>><?=$value?></option>
										<? } ?>
									</select>
									</td>
									<td>會員ID：</td>
									<td><input type="text" value="<?=isset($_GET['user_id'])&&$_GET['user_id']!=""?$_GET['user_id']:""?>" id="user_id" /></td>	
								</tr>
								<tr>
									<td>指定區間：</td>
									<td><input type="text" value="<?=isset($sdate)&&$sdate?$sdate:'' ?>" id="sdate" data-toggle="datepicker"  /></td>
									<td style="text-align: center;">-</td>
									<td><input type="text" value="<?=isset($edate)&&$edate?$edate:'' ?>" id="edate" data-toggle="datepicker" /></td>
								</tr>
                                <tr>
                                    <td style="text-align: center;">會員姓名：</td>
                                    <td><input type="text" value="<?=isset($name)&&$name?$name:'' ?>" id="name" /></td>
                                </tr>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td style="text-align:right;"><a href="javascript:void(0)" onclick="showChang();" class="btn btn-default float-right btn-md" >查詢</a></td>
								</tr>
							</table>
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
                                            <td>
                                                <a type="button" href="<?=isset($value->url)?$value->url:"" ?>" class="btn btn-danger"><i class="fa fa-download"></i></a>
                                                <? if($value->investor==1&&$value->type=="estatementdetail"){ ?>
                                                    <a type="button" href="./estatement_excel?sdate=<?=isset($value->sdate)?$value->sdate:'' ?>&edate=<?=isset($value->edate)?$value->edate:'' ?>&user_id=<?=isset($value->user_id)?$value->user_id:"" ?>" class="btn btn-success"><i class="fa fa-download"></i></a>
                                                <? } ?>
                                            </td>
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
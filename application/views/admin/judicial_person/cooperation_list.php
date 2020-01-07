        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">法人 - 經銷商<? echo $status_list==1?'管理':'申請';?>列表</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<script type="text/javascript">
				function success(id){
					if(confirm("確認審核通過？")){
						if(id){
							$.ajax({
								url: './cooperation_success?id='+id,
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
					if(confirm("確認審核失敗？")){
						if(id){
							$.ajax({
								url: './cooperation_failed?id='+id,
								type: 'GET',
								success: function(response) {
									alert(response);
									location.reload();
								}
							});
						}
					}
				}
                function build(id){
					if(id){
					    cut = id.split('|');
                        top.location = '../certification/user_certification_edit?cid=1006&user_id='+cut[0]+'&selltype='+cut[1];
                    }
				}

				function showChang(){
					var user_id = $('#user_id').val();
					var tax_id 			= $('#tax_id').val();
					var cooperation 	= $('#cooperation :selected').val();
					top.location = './cooperation?cooperation='+cooperation+'&user_id='+user_id+'&tax_id='+tax_id;
				}
			</script>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							<table>
								<tr>
									<td>法人會員ID：</td>
									<td><input type="text" value="<?=isset($_GET['user_id'])&&$_GET['user_id']!=""?$_GET['user_id']:""?>" id="user_id" /></td>
									<td style="padding-left: 15px;">統一編號：</td>
									<td><input type="text" value="<?=isset($_GET['tax_id'])&&$_GET['tax_id']!=""?$_GET['tax_id']:""?>" id="tax_id" /></td>
									<td></td>
								</tr>
								<tr>
									<td>狀態：</td>
									<td>
										<select id="cooperation">
											<option value="" >請選擇</option>
											<? foreach($cooperation_list as $key => $value){ ?>
												<option value="<?=$key?>" <?=isset($_GET['cooperation'])&&$_GET['cooperation']!=""&&intval($_GET['cooperation'])==intval($key)?"selected":""?>><?=$value?></option>
											<? } ?>
										</select>
									</td>
									<td></td>
									<td>
										<a href="javascript:showChang();" class="btn btn-default">查詢</a>
									</td>
								</tr>
							</table>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th>法人 User ID</th>
                                            <th>申請人 ID</th>
                                            <th>銷售類別</th>
                                            <th>統一編號</th>
                                            <th>公司名稱</th>
                                            <th>負責人</th>
                                            <th style="width: 93px;">信用評估表</th>
                                            <th>備註</th>
                                            <th>狀態</th>
                                            <th>申請日期</th>
                                            <th>管理</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
										if(isset($list) && !empty($list)){
											$count = 0;
											foreach($list as $key => $value){
												$count++;
                                                $sellingType = isset($value->selling_type)?'|'.$value->selling_type:"";
									?>
                                        <tr class="<?=$count%2==0?"odd":"even"; ?> list">
                                            <td><?=isset($value->company_user_id)&&$value->company_user_id!=0?$value->company_user_id:"" ?></td>
                                            <td><?=isset($value->user_id)?$value->user_id:"" ?></td>
                                            <td><?=isset($value->selling_type)?$selling_type[$value->selling_type]:"" ?></td>
                                            <td><?=isset($value->tax_id)?$value->tax_id:"" ?></td>
                                            <td><?=isset($value->company)?$value->company:"" ?></td>
                                            <td><?=isset($value->user_name)?$value->user_name:"" ?></td>
                                            <td><? $company_user_id = isset($value->company_user_id)?$value->company_user_id:"";
                                                if(!$value->cerCreditJudicial || $value->cerCreditJudicial->status == 0){
                                                    echo '<button class="btn btn-danger" style="width: 80px;" onclick="build(\''.$company_user_id.$sellingType.'\')">填寫</button>';
                                                }
                                                else{
                                                    echo '<a target="_blank" class="btn btn-info" style="width: 80px;" href="'.admin_url('certification/user_certification_edit?id=').$value->cerCreditJudicial->id.'">檢閱</a>';
                                                }
                                                ?></td>
                                            <td><?=isset($value->remark)?$value->remark:"" ?>
                                                <?=isset($value->selling_type)
                                                    ?($value->selling_type==2 && $value->no_taishin?'未建立台新帳號':'')
                                                    :"" ?>
                                            </td>
                                            <td><?=isset($cooperation_list[$value->cooperation])?$cooperation_list[$value->cooperation]:"" ?>
                                                <? if($value->cooperation==2){ ?>
                                                    <button class="btn btn-success" onclick="success(<?=isset($value->id)?$value->id:"" ?>)">通過</button>
                                                    <button class="btn btn-danger" onclick="failed(<?=isset($value->id)?$value->id:"" ?>)">不通過</button>
                                                <? } ?>
                                            </td>
                                            <td><?=isset($value->created_at)?date("Y-m-d H:i:s",$value->created_at):"" ?></td>
                                            <td><a target="_blank" href="<?=admin_url('judicialperson/cooperation_edit')."?id=".$value->id ?>" class="btn btn-default">管理</a></td>
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
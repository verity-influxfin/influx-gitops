<style>
    table.dataTable tbody td {
        vertical-align: middle;
    }
    <?=isset($input['target_id']) ? '
    .riskTitle {
        display: none;
    }
    .riskContent{
        padding:0;
    }
    .riskWrapper {
        margin: 0!important;
        background: white;
        border-left: 0!important;
    }
    ':'';
    ?>
</style>
<!-- DataTables JavaScript -->
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js"></script>
<script>
    var dataTableSet= {
        "bPaginate": false, // 顯示換頁
        "searching": true, // 顯示搜尋
        "info":	false, // 顯示資訊
        "fixedHeader": true, // 標題置頂
        "dom": '<"pull-left"f><"pull-right"l>tip',
        "oLanguage":{
            "sProcessing":"處理中...",
            "sLengthMenu":"顯示 _MENU_ 項結果",
            "sZeroRecords":"目前無資料",
            "sInfo":"顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
            "sInfoEmpty":"顯示第 0 至 0 項結果，共 0 項",
            "sInfoFiltered":"(從 _MAX_ 項結果過濾)",
            "sSearch":"模糊搜尋:",
            "oPaginate":{"sFirst":"首頁",
                "sPrevious":"上頁",
                "sNext":"下頁",
                "sLast":"尾頁"}
        }
    };
</script>
        <div id="page-wrapper" class="riskWrapper">
            <div class="row">
                <div class="col-lg-12 riskTitle">
                    <h1 class="page-header">
                        <?=isset($input['investor']) && $input['investor'] == 1?'投資端審核':'' ?>
                        <?=isset($input['investor']) && $input['investor'] == 0 && isset($input['company']) && $input['company'] == 1?'法人借款端審核':'' ?>
                        <?=isset($input['investor']) && $input['investor'] == 0 && isset($input['company']) && $input['company'] == 0?'自然人借款端審核':'' ?>
						<?=isset($input['associates']) && $input['associates'] == 1?'負責人/保證人 個人認證徵信狀態':'' ?>
                    </h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<script type="text/javascript">
				function success(id,target_no){
					if(confirm(target_no+" 確認審批上架？")){
						if(id){
							$.ajax({
								url: '<?=admin_url('target/verify_success?id=')?>'+id,
								type: 'GET',
								success: function(response) {
									alert(response);
									location.reload();
								}
							});
						}
					}
				}

				function failed(id,target_no){
					if(confirm(target_no+" 確認退件？案件將自動取消")){
						if(id){
							var p 		= prompt("請輸入退案原因，將自動通知使用者，不通知請按取消","");
							var remark 	= "";
							if(p){
								remark = encodeURIComponent(p);
							}
							$.ajax({
								url: '<?=admin_url('target/verify_failed?id=')?>'+id+'&remark='+remark,
								type: 'GET',
								success: function(response) {
									alert(response);
									location.reload();
								}
							});
						}
					}
				}
                $(document).off("click","a#fontchange").on("click","a#fontchange" ,  function(){
                    if($(this).hasClass('active')){
                        $(this).removeClass('active');
                        $('.nhide').css('display','inherit');
                        $('.sword').css('display','none');
                    }else{
                        $(this).addClass('active');
                        $('.nhide').css('display','none');
                        $('.sword').css('display','block');
                    }
                });

                function setDisabled(e){
                    $(e).attr("disabled",true);
                    $(e).text("已送出");
                }

                $(document).off("click",".manual_handling").on("click",".manual_handling",  function(){
                    setDisabled(this);
                    let target_id = $(this).data('target_id');

                    Pace.track(() => {
                        $.ajax({
                            type: 'POST',
                            url: "<?=admin_url('target/waiting_reinspection')?>",
                            data: {target_id: target_id, manual_handling: 1},
                            async: true,
                            success: (rsp) => {
                                console.log(rsp['response']);
                                alert(rsp['response']['msg']);
                            },
                            error: function (xhr, textStatus, thrownError) {
                                alert(textStatus);
                            }
                        });
                    });
                });
			</script>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12 riskContent">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							<table style="width:50%">
								<tr>
									<td colspan="9">圖示說明：</td>
								</tr>
								<tr>
									<td>留空  尚未認證</td>
									<td>
									<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-refresh"></i></button>
									資料更新中
									</td>
									<td>
									<button type="button" class="btn btn-success btn-circle"><i class="fa fa-check"></i> </button>
									認證完成
									</td>
                                    <td>
									<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-check"></i> </button>
									認證過期
									</td>
									<td>
									<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i></button>
									已退回
									</td>
                                    <? if(!isset($input['target_id'])){ ?>
                                    <td>
                                        <a id="fontchange" class="btn btn-default" style="margin-top: 6px;">Font mode</a>
									</td>
                                    <? } ?>
								</tr>
							</table>
                        </div>
                        <!-- /.panel-heading -->
                        <?php
                            if(isset($input['investor']) && $input['investor'] == 1){
                        ?>
                        <div class="panel-body">
                            <div class="table-responsive">
								<table class="table table-striped table-bordered table-hover" width="100%" style="text-align: center;" id="dataTables-tables">
                                    <thead>
                                        <tr>
                                            <th>案號</th>
                                            <th>會員編號</th>
											<?
                                            if($certification){
                                                foreach($certification as $key => $value) echo '<th>'.$value['name'].'</th>';
                                            }
											?>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
										$count = 0;
										if(isset($certification_investor_list) && !empty($certification_investor_list)){
											foreach($certification_investor_list as $key => $value){
												$count++;
									?>
                                        <tr class="<?=$count%2==0?"odd":"even"; ?>">
                                            <td>投資端</td>
											<td>
												<a class="fancyframe" href="<?=admin_url('User/display?id='.$key) ?>" >
													<?=isset($key)?$key:"" ?>
												</a>
											</td>
											<?
											if($certification){
												foreach($certification as $k => $v){
													echo '<td>';
													if(isset($value[$k]["user_status"]) && $value[$k]["user_status"]!==null){
														$user_status 		= $value[$k]["user_status"];
														$certification_id 	= $value[$k]["certification_id"];
                                                        $sys_check = $value[$k]["sys_check"]===0?" btn-circle":" ";
														if($k==3 && isset($value["bank_account"])){
															switch($user_status){
																case '0':
																	if($value[1]["user_status"]==1){
																		echo '<a target="_blank" href="'.admin_url('certification/user_bankaccount_list?verify=2').'" class="btn btn-default btn-md nhide" >金融驗證</a><span class="sword" style="display:none">可金融驗證</span>';
																	}else{
																		echo '<a target="_blank" href="'.admin_url('certification/user_bankaccount_edit?id='.$value["bank_account"]->id).'" ><button type="button" class="btn btn-warning btn-circle nhide"><i class="fa fa-refresh"></i></button></a><span class="sword" style="display:none">資料更新中</span>';
																	}
																	break;
																case '1':
																	echo '<a target="_blank" href="'.admin_url('certification/user_bankaccount_edit?id='.$value["bank_account"]->id).'" ><button type="button" class="btn btn-success'.$sys_check.' nhide"><i class="fa fa-check"></i></button></a><span class="sword" style="display:none">完成</span>';
																	break;
																case '2':
																	echo '<a target="_blank" href="'.admin_url('certification/user_bankaccount_edit?id='.$value["bank_account"]->id).'" ><button type="button" class="btn btn-danger'.$sys_check.' nhide"><i class="fa fa-times"></i></button></a><span class="sword" style="display:none">已退回</span>';
																	break;
																case '3':
																	echo '<a target="_blank" href="'.admin_url('certification/user_bankaccount_list?verify=2').'" class="btn btn-default btn-md nhide" >金融驗證</a><span class="sword" style="display:none">可金融驗證</span>';
																	break;
																default:
																	break;
															}
														}else{
															switch($user_status){
																case '0':
																	echo '<a target="_blank" href="'.admin_url('certification/user_certification_edit?id='.$certification_id).'" ><button type="button" class="btn btn-warning btn-circle nhide"><i class="fa fa-refresh"></i></button></a><span class="sword" style="display:none">資料更新中</span>';
																	break;
																case '1':
																	echo '<a target="_blank" href="'.admin_url('certification/user_certification_edit?id='.$certification_id).'" ><button type="button" class="btn btn-success'.$sys_check.' nhide"><i class="fa fa-check"></i></button></a><span class="sword" style="display:none">完成</span>';
																	break;
																case '2':
																	echo '<a target="_blank" href="'.admin_url('certification/user_certification_edit?id='.$certification_id).'" ><button type="button" class="btn btn-danger'.$sys_check.' nhide"><i class="fa fa-times"></i></button></a><span class="sword" style="display:none">已退回</span>';
																	break;
																case '3':
																	echo '<a target="_blank" href="'.admin_url('certification/user_certification_edit?id='.$certification_id).'" class="btn btn-default btn-md nhide" >驗證</a><span class="sword" style="display:none">可驗證</span>';
																	break;
																default:
																	break;
															}
														}
													}
													echo '</td>';
												}
											}
											?>
                                        </tr>
									<?php
										}}
									?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php
                            }
                            else{
                        foreach($list as $productKey => $productValue){
                            $checkOwner = isset($product_list[$productKey]['checkOwner']) ? $product_list[$productKey]['checkOwner'] : false;
                            $isExternalCoop = in_array($productKey, $externalCooperation);
                        ?>
                        <div class="panel-body">
                            <?php if (!isset($input['target_id'])){ echo '<h3>'.$product_list[$productKey]['name'].'</h3>'; } ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" width="100%" style="text-align: center;" <? if(!isset($input['target_id'])){ ?>id="dataTables-tables<? echo $productKey; }?>">
                                    <thead>
                                    <tr>
                                        <? if(!isset($input['target_id'])){ ?>
                                        <th>案號</th>
                                        <th>會員編號</th>
                                        <th>產品名稱</th>
                                        <th>狀態</th>
                                        <th>最後更新時間</th>
                                        <? }
                                        $printHtml = '';
                                        foreach($certification[$productKey] as $key => $value){
                                            if(isset($input['company']) && $input['company'] == 1 && $value['id'] >= 1000
                                                || isset($input['company']) && $input['company'] == 0 && $value['id'] < 1000) {
                                                if ($checkOwner) {
                                                    $value['id'] == CERTIFICATION_GOVERNMENTAUTHORITIES ? $printHtml .= '<th>負責人</th><th>負責人配偶</th>' : '';
                                                    $value['id'] == CERTIFICATION_PROFILEJUDICIAL ? $printHtml .= '<th>保證人</th>' : '';
                                                }
                                                $printHtml .= '<th>' . ($value['id'] == 9 && isset($input['company']) && $input['company'] == 1 ? '自然人' : '') . $value['name'] . '</th>';
                                            }
                                        }
                                        echo $printHtml;
                                        ?>
                                        <?if(isset($input['investor']) && $input['investor'] == 0){
                                            ?>
                                            <th>DD查核</th><?php
                                            if($isExternalCoop){
                                                if(!isset($input['target_id'])){
                                                    echo '<th>授信審核表</th>';
                                                    echo '<th>收件檢核表</th>';
                                                    echo '<th></th>';
                                                }else{
                                                    echo '<th>收件檢核表</th>';
                                                    echo '<th>徵信報告</th>';
                                                    echo '<th>圖片上傳資料</th>';
                                                }
                                            }else{
                                                echo '<th>退件</th>';
                                            }
                                        }?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $count = 0;
                                    if(isset($productValue) && !empty($productValue)){
                                        foreach($productValue as $key => $value){
                                            $count++;
                                            $associates = $checkOwner ? true : false;
                                            ?>
                                        <tr class="<?=$count%2==0?"odd":"even"; ?>">
                                            <? if(!isset($input['target_id'])){ ?>
                                            <td>
                                                <a class="fancyframe" href="<?=admin_url('target/edit?display=1&id='.$value->id) ?>" >
                                                    <?=isset($value->target_no)?$value->target_no:"" ?>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="fancyframe" href="<?=admin_url('User/display?id='.$value->user_id) ?>" >
                                                    <?=isset($value->user_id)?$value->user_id:"" ?>
                                                </a>
                                            </td>
                                            <td><?=isset($product_list[$value->product_id])?$product_list[$value->product_id]['name']:'' ?><?=$value->sub_product_id!=0?' / '.$sub_product_list[$value->sub_product_id]['identity'][$product_list[$value->product_id]['identity']]['name']:'' ?></td>
                                            <td>
                                                <?
                                                if($value->status==2){
                                                    if(isset($value->bank_account_verify) && $value->bank_account_verify){
                                                        $all_pass = true;

                                                        foreach($value->certification as $k => $v){
                                                            if(in_array($v['id'],$product_list[$value->product_id]['certifications']) && $v['user_status']!=1){
                                                                $all_pass = false;
                                                            }
                                                        }

                                                        if($all_pass && !$isExternalCoop){
                                                            if($value->sub_status==8){
                                                                echo '<button class="btn btn-success nhide" onclick="success('.$value->id.','."'".$value->target_no."'".')">轉換產品上架</button><span class="sword" style="display:none">可轉換產品上架</span>';
                                                            }else{
                                                                echo '<button class="btn btn-success nhide" onclick="success('.$value->id.','."'".$value->target_no."'".')">審批上架</button><span class="sword" style="display:none">可審批上架</span>';
                                                            }
                                                        }else{
                                                            echo isset($status_list[$value->status])?$status_list[$value->status]:"";
                                                        }
                                                    }else{
                                                        if($value->certification[3]['user_status'] == 0){
                                                            echo '<a target="_blank" href="'.admin_url('certification/user_bankaccount_list?verify=2').'" class="btn btn-default btn-md nhide" >待金融驗證</a><span class="sword" style="display:none">待金融驗證</span>';
                                                        }else{
                                                            echo isset($status_list[$value->status])?$status_list[$value->status]:"";
                                                        }
                                                    }
                                                }else{
                                                    echo isset($status_list[$value->status])?$status_list[$value->status]:"";
                                                }
                                                ?>
                                            </td>
                                            <td><?=isset($value->lastUpdate)?date("Y-m-d H:i:s",$value->lastUpdate):"" ?></td>
                                            <? }
                                            if($certification){
                                                if(isset($input['investor']) && $input['investor'] == 0){
                                                    foreach( $certification[$productKey] as $k => $svalue){
                                                        if ( ! isset($value->certification[$svalue['id']]))
                                                        {
                                                            continue;
                                                        }
                                                        if(isset($input['company']) && $input['company'] == 1 && isset($value->certification) && $svalue['id'] >= 1000
                                                            || isset($input['company']) && $input['company'] == 0 && isset($value->certification) && $svalue['id'] < 1000){
                                                            if ($value->certification[$svalue['id']]["user_status"]!==null) {
                                                                $certification_id = $value->certification[$svalue['id']]["certification_id"];
                                                                $sys_check = $value->certification[$svalue['id']]["sys_check"] === 0 ? " btn-circle" : " ";
                                                                $status = ($value->certification[$svalue['id']]['expire_time'] <= time() && !in_array($svalue['id'], [CERTIFICATION_IDENTITY, CERTIFICATION_DEBITCARD, CERTIFICATION_EMERGENCY, CERTIFICATION_EMAIL]) ? 'danger' : 'success');
                                                            }
                                                            if($checkOwner){
                                                                if($svalue['id'] == CERTIFICATION_GOVERNMENTAUTHORITIES){
                                                                    echo isset($value->associate['owner']) ? '<td><a target="_blank" href="'.admin_url('risk/judicial_associates?target_id='.$value->id).'" ><button type="button" class="btn btn-'.($value->associate['owner'] == 1 ? 'info' : 'warning').' btn-circle"><i class="fa '.($value->associate['owner'] == 1 ? 'fa-check' : 'fa-refresh').'"></i></button></a><span class="sword" style="display:none">個人認證徵信狀態</span></td>' : '<td></td>';
                                                                    echo isset($value->associate['spouse']) ? '<td><a target="_blank" href="'.admin_url('risk/judicial_associates?target_id='.$value->id).'" ><button type="button" class="btn btn-'.($value->associate['spouse'] == 1 ? 'info' : 'warning').' btn-circle"><i class="fa '.($value->associate['spouse'] == 1 ? 'fa-check' : 'fa-refresh').'"></i></button></a><span class="sword" style="display:none">個人認證徵信狀態</span></td>' : '<td></td>';
                                                                }elseif ($svalue['id'] == CERTIFICATION_PROFILEJUDICIAL){
                                                                    echo isset($value->associate['guarantor']) ? '<td><a target="_blank" href="'.admin_url('risk/judicial_associates?target_id='.$value->id).'" ><button type="button" class="btn btn-'.($value->associate['guarantor'] == 1 ? 'info' : 'warning').' btn-circle"><i class="fa '.($value->associate['guarantor'] == 1 ? 'fa-check' : 'fa-refresh').'"></i></button></a><span class="sword" style="display:none">個人認證徵信狀態</span></td>' : '<td></td>';
                                                                }
                                                            }
                                                            echo '<td>';
                                                            if($svalue['id']==3){
                                                                switch($value->certification[$svalue['id']]["user_status"]){
                                                                    case '0':
                                                                        echo '<a target="_blank" href="'.admin_url('certification/user_bankaccount_edit?id='.$value->bank_account->id).'" class="btn btn-default btn-md nhide" >驗證</a><span class="sword" style="display:none">可驗證</span>';
                                                                        break;
                                                                    case '1':
                                                                        echo '<a target="_blank" href="'.admin_url('certification/user_bankaccount_edit?id='.$value->bank_account->id).'" ><button type="button" class="btn btn-'.$status.$sys_check.' nhide"><i class="fa fa-check"></i></button></a><span class="sword" style="display:none">完成</span>';
                                                                        break;
                                                                    case '2':
                                                                        echo '<a target="_blank" href="'.admin_url('certification/user_bankaccount_edit?id='.$value->bank_account->id).'" ><button type="button" class="btn btn-danger'.$sys_check.' nhide"><i class="fa fa-times"></i></button></a><span class="sword" style="display:none">已退回</span>';
                                                                        break;
                                                                    case '3':
                                                                        echo '<a target="_blank" href="'.admin_url('certification/user_bankaccount_edit?id='.$value->bank_account->id).'" class="btn btn-default btn-md nhide" >驗證</a><span class="sword" style="display:none">可驗證</span>';
                                                                        break;
                                                                    default:
                                                                        break;
                                                                }
                                                            }else{
                                                                switch($value->certification[$svalue['id']]["user_status"]){
                                                                    case '0':
                                                                        echo '<a target="_blank" href="'.admin_url('certification/user_certification_edit?id='.$certification_id.'&product_id='.$value->product_id).'" ><button type="button" class="btn btn-warning'.$sys_check.' nhide"><i class="fa fa-refresh"></i></button></a><span class="sword" style="display:none">資料更新中</span>';
                                                                        break;
                                                                    case '1':
                                                                        echo '<a target="_blank" href="'.admin_url('certification/user_certification_edit?id='.$certification_id.'&product_id='.$value->product_id).'" ><button type="button" class="btn btn-'.$status.''.$sys_check.' nhide"><i class="fa fa-check"></i></button></a><span class="sword" style="display:none">完成</span>';
                                                                        break;
                                                                    case '2':
                                                                        echo '<a target="_blank" href="'.admin_url('certification/user_certification_edit?id='.$certification_id.'&product_id='.$value->product_id).'" ><button type="button" class="btn btn-danger'.$sys_check.' nhide"><i class="fa fa-times"></i></button></a><span class="sword" style="display:none">已退回</span>';
                                                                        break;
                                                                    case '3':
                                                                        echo '<a target="_blank" href="'.admin_url('certification/user_certification_edit?id='.$certification_id.'&product_id='.$value->product_id).'" class="btn btn-default btn-md nhide" >驗證</a><span class="sword" style="display:none">可驗證</span>';
                                                                        break;
                                                                    default:
                                                                        break;
                                                                }
                                                            }
                                                            echo '</td>';
                                                        }
                                                    }
                                                    ?>
                                                    <td>
                                                    <?php if ($value->dd_edit_done === TRUE) { ?>
                                                        <a target="_blank" href="/admin/target/meta?id=<?= $value->id ?>">
                                                            <button type="button" class="btn btn-success btn-circle nhide"><i class="fa fa-check"></i></button>
                                                        </a>
                                                        <span class="sword" style="display:none">完成</span>
                                                    <?php } else { ?>
                                                        <a target="_blank"
                                                           href="/admin/target/meta?id=<?= $value->id ?>"
                                                           class="btn btn-default btn-md nhide">驗證<?= $value->dd_edit_done; ?></a>
                                                    <?php } ?>
                                                    </td><?php
                                                    if(isset($input['company']) && $input['company'] == 1){ ?>
                                                    <? if($isExternalCoop){ ?>
                                                            <? if(!isset($input['target_id'])){ ?>
                                                                <td><a class="btn btn-primary btn-info" href="/admin/creditmanagementtable/juridical_person_report?target_id=<?=isset($value->id)?$value->id:"" ?>&table_type=management" target="_blank" >查看<br />授信審核表</a></td>
                                                            <? } ?>
                                                            <td><a class="btn btn-primary" href="/admin/bankdata/juridical_person_report?target_id=<?=isset($value->id)?$value->id:"" ?>&table_type=check" target="_blank" >查看<br />送件檢核表</a></td>
                                                    <? }
                                                    }
                                                }
                                                if(isset($input['investor']) && $input['investor'] == 0){
                                                    if($isExternalCoop) {
                                                        if (!isset($input['target_id'])) {
                                                            echo '<td><button class="btn btn-primary btn-warning manual_handling" onclick=""' .(in_array($value->status, [TARGET_BANK_LOAN, TARGET_BANK_REPAYMENTING, TARGET_BANK_REPAYMENTED]) ?'disabled':' '). ' data-target_id='.$value->id.'>轉人工</button></td>';
                                                        }else{
                                                            echo '<td><a class="btn btn-primary" href="/admin/CertificationReport/report?target_id='.($value->id??"").'" target="_blank" >查看<br />徵信報告</a></td>';
                                                            echo '<td><button class="btn btn-primary btn-info" onclick="" >圖片資料</button></td>';
                                                        }
                                                    }else{
                                                        echo '<td><button class="btn btn-outline btn-danger" onclick="failed('.(isset($value->id)?$value->id:"").','.(isset($value->target_no)?$value->target_no:'').')" >退件</button></td>';
                                                    }
                                                }?>
                                                </tr>
                                                <?php
                                            }}}
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <script>$('#dataTables-tables<?=$productKey?>').dataTable(dataTableSet);</script>
                        <?php
                                }
                            }
                        ?>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
        </div>
        <!-- /#page-wrapper -->

<style>
    table.dataTable tbody td {
        vertical-align: middle;
    }
</style>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        <?=isset($input['investor']) && $input['investor'] == 1?'投資端審核':'' ?>
                        <?=isset($input['investor']) && $input['investor'] == 0 && isset($input['company']) && $input['company'] == 1?'法人借款端審核':'' ?>
                        <?=isset($input['investor']) && $input['investor'] == 0 && isset($input['company']) && $input['company'] == 0?'自然人借款端審核':'' ?>
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
                $(document).ready(function(){
                    $('#myTabs a').click(function (e) {
                        let show_id = $(this).attr("href");
                        $(".table-responsive").hide()
                        $(show_id).show()
                    })
                    $( "#myTabs :first-child :first-child" ).trigger( "click" );
                });
			</script>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
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
                                    <td>
                                        <a id="fontchange" class="btn btn-default" style="margin-top: 6px;">Font mode</a>
									</td>
								</tr>
							</table>
                        </div>
                        <div>
                            <ul class="nav nav-tabs" id="myTabs" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#risk_index_tabke_1" role="tab" data-toggle="tab" aria-controls="test1" aria-expanded="true">身份驗證</a>
                                </li>
                                <li role="presentation">
                                    <a href="#risk_index_tabke_2" role="tab" data-toggle="tab" aria-controls="test2" aria-expanded="false">收件檢核</a>
                                </li>
                                <li role="presentation">
                                    <a href="#risk_index_tabke_3" role="tab" data-toggle="tab" aria-controls="test3" aria-expanded="false">審核中</a>
                                </li>
                            </ul>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive" id="risk_index_tabke_1">
                                <div class="row">
                                    <h3 class="page-header m-3">身份驗證</h3>
                                </div>
                                <table class="table table-striped table-bordered table-hover dataTables-tables" width="100%" style="text-align: center;" >
                                    <thead>
                                        <tr>
                                            <th>案號</th>
                                            <th>會員編號</th>
											<th>產品名稱</th>
                                            <th>狀態</th>
                                            <th>最後更新時間</th>
											<?
                                            $investor_cer = [1,3,5,6];
                                            if($certification){
												foreach($certification as $key => $value){
												    if(isset($input['investor']) && $input['investor'] == 1 && !in_array($key, $investor_cer)
                                                        || isset($input['investor']) && $input['investor'] == 0 && isset($input['company']) && $input['company'] == 1 && $key <= 10
                                                        || isset($input['investor']) && $input['investor'] == 0 && isset($input['company']) && $input['company'] == 0 && $key > 10
                                                    ){
												        unset($certification[$key]);
												        continue;
                                                    }
                                                    echo '<th>'.($key==9 && isset($input['company']) && $input['company']==1?'自然人':'').$value['name'].'</th>';
                                                }
											}
											?>
                                            <?if(isset($input['investor']) && $input['investor'] != 1){?>
                                            <th>退件</th>
                                            <?}?>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
                                    $count = 0;
                                    if (isset($certification_investor_list) && !empty($certification_investor_list)) {
                                        foreach ($certification_investor_list as $key => $value) {
                                            $count++;
                                            ?>
                                            <tr class="<?= $count % 2 == 0 ? "odd" : "even"; ?>">
                                                <td>投資端</td>
                                                <td>
                                                    <a class="fancyframe"
                                                       href="<?= admin_url('User/display?id=' . $key) ?>">
                                                        <?= isset($key) ? $key : "" ?>
                                                    </a>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <?
                                                if ($certification) {
                                                    foreach ($certification as $k => $v) {
                                                        echo '<td>';
                                                        if (isset($value[$k]["user_status"]) && $value[$k]["user_status"] !== null) {
                                                            $user_status = $value[$k]["user_status"];
                                                            $certification_id = $value[$k]["certification_id"];
                                                            $sys_check = $value[$k]["sys_check"] === 0 ? " btn-circle" : " ";
                                                            if ($k == 3) {
                                                                switch ($user_status) {
                                                                    case '0':
                                                                        if ($value[1]["user_status"] == 1) {
                                                                            echo '<a target="_blank" href="' . admin_url('certification/user_bankaccount_list?verify=2') . '" class="btn btn-default btn-md nhide" >金融驗證</a><span class="sword" style="display:none">可金融驗證</span>';
                                                                        } else {
                                                                            echo '<a target="_blank" href="' . admin_url('certification/user_bankaccount_edit?id=' . $value["bank_account"]->id) . '" ><button type="button" class="btn btn-warning btn-circle nhide"><i class="fa fa-refresh"></i></button></a><span class="sword" style="display:none">資料更新中</span>';
                                                                        }
                                                                        break;
                                                                    case '1':
                                                                        echo '<a target="_blank" href="' . admin_url('certification/user_bankaccount_edit?id=' . $value["bank_account"]->id) . '" ><button type="button" class="btn btn-success' . $sys_check . ' nhide"><i class="fa fa-check"></i></button></a><span class="sword" style="display:none">完成</span>';
                                                                        break;
                                                                    case '2':
                                                                        echo '<a target="_blank" href="' . admin_url('certification/user_bankaccount_edit?id=' . $value["bank_account"]->id) . '" ><button type="button" class="btn btn-danger' . $sys_check . ' nhide"><i class="fa fa-times"></i></button></a><span class="sword" style="display:none">已退回</span>';
                                                                        break;
                                                                    case '3':
                                                                        echo '<a target="_blank" href="' . admin_url('certification/user_bankaccount_list?verify=2') . '" class="btn btn-default btn-md nhide" >金融驗證</a><span class="sword" style="display:none">可金融驗證</span>';
                                                                        break;
                                                                    default:
                                                                        break;
                                                                }
                                                            } else {
                                                                switch ($user_status) {
                                                                    case '0':
                                                                        echo '<a target="_blank" href="' . admin_url('certification/user_certification_edit?id=' . $certification_id) . '" ><button type="button" class="btn btn-warning btn-circle nhide"><i class="fa fa-refresh"></i></button></a><span class="sword" style="display:none">資料更新中</span>';
                                                                        break;
                                                                    case '1':
                                                                        echo '<a target="_blank" href="' . admin_url('certification/user_certification_edit?id=' . $certification_id) . '" ><button type="button" class="btn btn-success' . $sys_check . ' nhide"><i class="fa fa-check"></i></button></a><span class="sword" style="display:none">完成</span>';
                                                                        break;
                                                                    case '2':
                                                                        echo '<a target="_blank" href="' . admin_url('certification/user_certification_edit?id=' . $certification_id) . '" ><button type="button" class="btn btn-danger' . $sys_check . ' nhide"><i class="fa fa-times"></i></button></a><span class="sword" style="display:none">已退回</span>';
                                                                        break;
                                                                    case '3':
                                                                        echo '<a target="_blank" href="' . admin_url('certification/user_certification_edit?id=' . $certification_id) . '" class="btn btn-default btn-md nhide" >驗證</a><span class="sword" style="display:none">可驗證</span>';
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
                                                <td></td>
                                            </tr>
                                            <?php
										}}
									?>
									<?php
										if(isset($list[0]) && !empty($list[0])){
											foreach($list[0] as $key => $value){
												$count++;
									?>
                                        <tr class="<?=$count%2==0?"odd":"even"; ?>">
                                            <td>
												<a class="fancyframe" href="<?=admin_url('target/edit?display=1&id='.$value->id) ?>" >
													<?=isset($value->target_no)?$value->target_no:"" ?>
												</a>
											</td>
											<td>
												<a class="fancyframe" href="<?=admin_url('User/display?id='.$value->user_id) ?>" >
													<?=isset($value->user_id)?$value->user_id:"" ?> <?=$value->user_status==1?"舊戶":"新戶" ?>
												</a>
											</td>
                                            <td><?
                                                $productId = isset($value->product_id) ? $value->product_id : 1;
                                                $subproductId = isset($sub_product_list[$value->sub_product_id]) ? $value->sub_product_id : 0;
                                                $subloan_list = $this->config->item('subloan_list');
                                                echo isset($product_list[$productId])?$product_list[$productId]['name']:''; ?><? echo $subproductId!=0?' / '.$sub_product_list[$subproductId]['identity'][$product_list[$productId]['identity']]['name']:''; ?><?=isset($value->target_no)?(preg_match('/'.$subloan_list.'/',$value->target_no)?'(產品轉換)':''):'' ?></td>
											<td>
												<?
													if($value->status==2){
														if($value->bank_account_verify){
															$all_pass = true;

															foreach($value->certification as $k => $v){
																if(in_array($v['id'],$product_list[$productId]['certifications']) && $v['user_status']!=1){
																	$all_pass = false;
																}
															}

															if($all_pass){
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
											<td><?=isset($value->updated_at)?date("Y-m-d H:i:s",$value->updated_at):"" ?></td>
											<? if($certification){
												foreach($certification as $k => $v){
													echo '<td>';
													if(in_array($k, $value->certification_stage_list[0] ?? []) && isset($value->certification) && $value->certification[$k]["user_status"]!==null){
														$certification_id 	= $value->certification[$k]["certification_id"];
                                                        $sys_check = $value->certification[$k]["sys_check"]===0?" btn-circle":" ";
                                                        $status      = ($value->certification[$k]['expire_time']<=time()&&!in_array($v['id'],[CERTIFICATION_IDCARD,CERTIFICATION_DEBITCARD,CERTIFICATION_EMERGENCY,CERTIFICATION_EMAIL])?'danger':'success');
                                                        $expire_time = date( "Y/m/d", $value->certification[$k]['expire_time']);
														if($k==3){
															switch($value->certification[$k]["user_status"]){
																case '0':
																	echo '<a target="_blank" href="'.admin_url('certification/user_bankaccount_edit?id='.$value->bank_account->id).'" class="btn btn-default btn-md nhide" >驗證</a><span class="sword" style="display:none">可驗證</span>';
																	break;
																case '1':
																	echo '<a target="_blank" href="'.admin_url('certification/user_bankaccount_edit?id='.$value->bank_account->id).'" ><button type="button" class="btn btn-'.$status.''.$sys_check.' nhide"><i class="fa fa-check"></i></button></a><span class="sword" style="display:none">完成</span>';
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
															switch($value->certification[$k]["user_status"]){
																case '0':
																	echo '<a target="_blank" href="'.admin_url('certification/user_certification_edit?id='.$certification_id).'" ><button type="button" class="btn btn-warning'.$sys_check.' nhide"><i class="fa fa-refresh"></i></button></a><span class="sword" style="display:none">資料更新中</span>';
																	break;
																case '1':
																	echo '<a target="_blank" href="'.admin_url('certification/user_certification_edit?id='.$certification_id).'" ><button type="button" class="btn btn-'.$status.''.$sys_check.' nhide"><i class="fa fa-check"></i></button></a><span class="sword" style="display:none">完成</span>';
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
                                            <? if(isset($input['investor']) && $input['investor'] != 1){?>
                                                <td></td>
                                            <?}?>
                                        </tr>
									<?php
										}}
									?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="table-responsive mt-5" id="risk_index_tabke_2">
                                <div class="row">
                                    <h3 class="page-header m-3">收件檢核</h3>
                                </div>
                                <table class="table table-striped table-bordered table-hover dataTables-tables" width="100%" style="text-align: center;">
                                    <thead>
                                    <tr>
                                        <th>案號</th>
                                        <th>會員編號</th>
                                        <th>產品名稱</th>
                                        <th>狀態</th>
                                        <th>最後更新時間</th>
                                        <?
                                        $investor_cer = [1,3,5,6];
                                        if($certification){
                                            foreach($certification as $key => $value){
                                                if(isset($input['investor']) && $input['investor'] == 1 && !in_array($key, $investor_cer)
                                                    || isset($input['investor']) && $input['investor'] == 0 && isset($input['company']) && $input['company'] == 1 && $key <= 10
                                                    || isset($input['investor']) && $input['investor'] == 0 && isset($input['company']) && $input['company'] == 0 && $key > 10
                                                ){
                                                    unset($certification[$key]);
                                                    continue;
                                                }
                                                echo '<th>'.($key==9 && isset($input['company']) && $input['company']==1?'自然人':'').$value['name'].'</th>';
                                            }
                                        }
                                        ?>
                                        <?if(isset($input['investor']) && $input['investor'] != 1){?>
                                            <th>退件</th>
                                        <?}?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $count = 0;
                                    if (isset($certification_investor_list) && !empty($certification_investor_list)) {
                                        foreach ($certification_investor_list as $key => $value) {
                                            $count++;
                                            ?>
                                            <tr class="<?= $count % 2 == 0 ? "odd" : "even"; ?>">
                                                <td>投資端</td>
                                                <td>
                                                    <a class="fancyframe"
                                                       href="<?= admin_url('User/display?id=' . $key) ?>">
                                                        <?= isset($key) ? $key : "" ?>
                                                    </a>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <?
                                                if ($certification) {
                                                    foreach ($certification as $k => $v) {
                                                        echo '<td>';
                                                        if (isset($value[$k]["user_status"]) && $value[$k]["user_status"] !== null) {
                                                            $user_status = $value[$k]["user_status"];
                                                            $certification_id = $value[$k]["certification_id"];
                                                            $sys_check = $value[$k]["sys_check"] === 0 ? " btn-circle" : " ";
                                                            if ($k == 3) {
                                                                switch ($user_status) {
                                                                    case '0':
                                                                        if ($value[1]["user_status"] == 1) {
                                                                            echo '<a target="_blank" href="' . admin_url('certification/user_bankaccount_list?verify=2') . '" class="btn btn-default btn-md nhide" >金融驗證</a><span class="sword" style="display:none">可金融驗證</span>';
                                                                        } else {
                                                                            echo '<a target="_blank" href="' . admin_url('certification/user_bankaccount_edit?id=' . $value["bank_account"]->id) . '" ><button type="button" class="btn btn-warning btn-circle nhide"><i class="fa fa-refresh"></i></button></a><span class="sword" style="display:none">資料更新中</span>';
                                                                        }
                                                                        break;
                                                                    case '1':
                                                                        echo '<a target="_blank" href="' . admin_url('certification/user_bankaccount_edit?id=' . $value["bank_account"]->id) . '" ><button type="button" class="btn btn-success' . $sys_check . ' nhide"><i class="fa fa-check"></i></button></a><span class="sword" style="display:none">完成</span>';
                                                                        break;
                                                                    case '2':
                                                                        echo '<a target="_blank" href="' . admin_url('certification/user_bankaccount_edit?id=' . $value["bank_account"]->id) . '" ><button type="button" class="btn btn-danger' . $sys_check . ' nhide"><i class="fa fa-times"></i></button></a><span class="sword" style="display:none">已退回</span>';
                                                                        break;
                                                                    case '3':
                                                                        echo '<a target="_blank" href="' . admin_url('certification/user_bankaccount_list?verify=2') . '" class="btn btn-default btn-md nhide" >金融驗證</a><span class="sword" style="display:none">可金融驗證</span>';
                                                                        break;
                                                                    default:
                                                                        break;
                                                                }
                                                            } else {
                                                                switch ($user_status) {
                                                                    case '0':
                                                                        echo '<a target="_blank" href="' . admin_url('certification/user_certification_edit?id=' . $certification_id) . '" ><button type="button" class="btn btn-warning btn-circle nhide"><i class="fa fa-refresh"></i></button></a><span class="sword" style="display:none">資料更新中</span>';
                                                                        break;
                                                                    case '1':
                                                                        echo '<a target="_blank" href="' . admin_url('certification/user_certification_edit?id=' . $certification_id) . '" ><button type="button" class="btn btn-success' . $sys_check . ' nhide"><i class="fa fa-check"></i></button></a><span class="sword" style="display:none">完成</span>';
                                                                        break;
                                                                    case '2':
                                                                        echo '<a target="_blank" href="' . admin_url('certification/user_certification_edit?id=' . $certification_id) . '" ><button type="button" class="btn btn-danger' . $sys_check . ' nhide"><i class="fa fa-times"></i></button></a><span class="sword" style="display:none">已退回</span>';
                                                                        break;
                                                                    case '3':
                                                                        echo '<a target="_blank" href="' . admin_url('certification/user_certification_edit?id=' . $certification_id) . '" class="btn btn-default btn-md nhide" >驗證</a><span class="sword" style="display:none">可驗證</span>';
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
                                                <td></td>
                                            </tr>
                                            <?php
                                        }}
                                    ?>
                                    <?php
                                    if(isset($list[1]) && !empty($list[1])){
                                        foreach($list[1] as $key => $value){
                                            $count++;
                                            ?>
                                            <tr class="<?=$count%2==0?"odd":"even"; ?>">
                                                <td>
                                                    <a class="fancyframe" href="<?=admin_url('target/edit?display=1&id='.$value->id) ?>" >
                                                        <?=isset($value->target_no)?$value->target_no:"" ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a class="fancyframe" href="<?=admin_url('User/display?id='.$value->user_id) ?>" >
                                                        <?=isset($value->user_id)?$value->user_id:"" ?> <?=$value->user_status==1?"舊戶":"新戶" ?>
                                                    </a>
                                                </td>
                                                <td><?
                                                    $productId = isset($value->product_id) ? $value->product_id : 1;
                                                    $subproductId = isset($sub_product_list[$value->sub_product_id]) ? $value->sub_product_id : 0;
                                                    $subloan_list = $this->config->item('subloan_list');
                                                    echo isset($product_list[$productId])?$product_list[$productId]['name']:''; ?><? echo $subproductId!=0?' / '.$sub_product_list[$subproductId]['identity'][$product_list[$productId]['identity']]['name']:''; ?><?=isset($value->target_no)?(preg_match('/'.$subloan_list.'/',$value->target_no)?'(產品轉換)':''):'' ?></td>
                                                <td>
                                                    <?
                                                    if($value->status==2){
                                                        if($value->bank_account_verify){
                                                            $all_pass = true;

                                                            foreach($value->certification as $k => $v){
                                                                if(in_array($v['id'],$product_list[$productId]['certifications']) && $v['user_status']!=1){
                                                                    $all_pass = false;
                                                                }
                                                            }

                                                            if($all_pass){
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
                                                <td><?=isset($value->updated_at)?date("Y-m-d H:i:s",$value->updated_at):"" ?></td>
                                                <? if($certification){
                                                    foreach($certification as $k => $v){
                                                        echo '<td>';
                                                        if(isset($value->certification) && $value->certification[$k]["user_status"]!==null){
                                                            $certification_id 	= $value->certification[$k]["certification_id"];
                                                            $sys_check = $value->certification[$k]["sys_check"]===0?" btn-circle":" ";
                                                            $status      = ($value->certification[$k]['expire_time']<=time()&&!in_array($v['id'],[CERTIFICATION_IDCARD,CERTIFICATION_DEBITCARD,CERTIFICATION_EMERGENCY,CERTIFICATION_EMAIL])?'danger':'success');
                                                            $expire_time = date( "Y/m/d", $value->certification[$k]['expire_time']);
                                                            if($k==3){
                                                                switch($value->certification[$k]["user_status"]){
                                                                    case '0':
                                                                        echo '<a target="_blank" href="'.admin_url('certification/user_bankaccount_edit?id='.$value->bank_account->id).'" class="btn btn-default btn-md nhide" >驗證</a><span class="sword" style="display:none">可驗證</span>';
                                                                        break;
                                                                    case '1':
                                                                        echo '<a target="_blank" href="'.admin_url('certification/user_bankaccount_edit?id='.$value->bank_account->id).'" ><button type="button" class="btn btn-'.$status.''.$sys_check.' nhide"><i class="fa fa-check"></i></button></a><span class="sword" style="display:none">完成</span>';
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
                                                                switch($value->certification[$k]["user_status"]){
                                                                    case '0':
                                                                        echo '<a target="_blank" href="'.admin_url('certification/user_certification_edit?id='.$certification_id).'" ><button type="button" class="btn btn-warning'.$sys_check.' nhide"><i class="fa fa-refresh"></i></button></a><span class="sword" style="display:none">資料更新中</span>';
                                                                        break;
                                                                    case '1':
                                                                        echo '<a target="_blank" href="'.admin_url('certification/user_certification_edit?id='.$certification_id).'" ><button type="button" class="btn btn-'.$status.''.$sys_check.' nhide"><i class="fa fa-check"></i></button></a><span class="sword" style="display:none">完成</span>';
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
                                                <? if(isset($input['investor']) && $input['investor'] != 1){?>
                                                    <td><button class="btn btn-outline btn-danger" onclick="failed(<?=isset($value->id)?$value->id:"" ?>,'<?=isset($value->target_no)?$value->target_no:"" ?>');" >退件</button></td>
                                                <?}?>
                                            </tr>
                                            <?php
                                        }}
                                    ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="table-responsive mt-5 " id="risk_index_tabke_3">
                                <div class="row">
                                    <h3 class="page-header m-3">審核中</h3>
                                </div>
                                <table class="table table-striped table-bordered table-hover dataTables-tables" width="100%" style="text-align: center;" >
                                    <thead>
                                    <tr>
                                        <th>案號</th>
                                        <th>會員編號</th>
                                        <th>產品名稱</th>
                                        <th>狀態</th>
                                        <th>最後更新時間</th>
                                        <?
                                        $investor_cer = [1,3,5,6];
                                        if($certification){
                                            foreach($certification as $key => $value){
                                                if(isset($input['investor']) && $input['investor'] == 1 && !in_array($key, $investor_cer)
                                                    || isset($input['investor']) && $input['investor'] == 0 && isset($input['company']) && $input['company'] == 1 && $key <= 10
                                                    || isset($input['investor']) && $input['investor'] == 0 && isset($input['company']) && $input['company'] == 0 && $key > 10
                                                ){
                                                    unset($certification[$key]);
                                                    continue;
                                                }
                                                echo '<th>'.($key==9 && isset($input['company']) && $input['company']==1?'自然人':'').$value['name'].'</th>';
                                            }
                                        }
                                        ?>
                                        <?if(isset($input['investor']) && $input['investor'] != 1){?>
                                            <th>退件</th>
                                        <?}?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $count = 0;
                                    if (isset($certification_investor_list) && !empty($certification_investor_list)) {
                                        foreach ($certification_investor_list as $key => $value) {
                                            $count++;
                                            ?>
                                            <tr class="<?= $count % 2 == 0 ? "odd" : "even"; ?>">
                                                <td>投資端</td>
                                                <td>
                                                    <a class="fancyframe"
                                                       href="<?= admin_url('User/display?id=' . $key) ?>">
                                                        <?= isset($key) ? $key : "" ?>
                                                    </a>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <?
                                                if ($certification) {
                                                    foreach ($certification as $k => $v) {
                                                        echo '<td>';
                                                        if (isset($value[$k]["user_status"]) && $value[$k]["user_status"] !== null) {
                                                            $user_status = $value[$k]["user_status"];
                                                            $certification_id = $value[$k]["certification_id"];
                                                            $sys_check = $value[$k]["sys_check"] === 0 ? " btn-circle" : " ";
                                                            if ($k == 3) {
                                                                switch ($user_status) {
                                                                    case '0':
                                                                        if ($value[1]["user_status"] == 1) {
                                                                            echo '<a target="_blank" href="' . admin_url('certification/user_bankaccount_list?verify=2') . '" class="btn btn-default btn-md nhide" >金融驗證</a><span class="sword" style="display:none">可金融驗證</span>';
                                                                        } else {
                                                                            echo '<a target="_blank" href="' . admin_url('certification/user_bankaccount_edit?id=' . $value["bank_account"]->id) . '" ><button type="button" class="btn btn-warning btn-circle nhide"><i class="fa fa-refresh"></i></button></a><span class="sword" style="display:none">資料更新中</span>';
                                                                        }
                                                                        break;
                                                                    case '1':
                                                                        echo '<a target="_blank" href="' . admin_url('certification/user_bankaccount_edit?id=' . $value["bank_account"]->id) . '" ><button type="button" class="btn btn-success' . $sys_check . ' nhide"><i class="fa fa-check"></i></button></a><span class="sword" style="display:none">完成</span>';
                                                                        break;
                                                                    case '2':
                                                                        echo '<a target="_blank" href="' . admin_url('certification/user_bankaccount_edit?id=' . $value["bank_account"]->id) . '" ><button type="button" class="btn btn-danger' . $sys_check . ' nhide"><i class="fa fa-times"></i></button></a><span class="sword" style="display:none">已退回</span>';
                                                                        break;
                                                                    case '3':
                                                                        echo '<a target="_blank" href="' . admin_url('certification/user_bankaccount_list?verify=2') . '" class="btn btn-default btn-md nhide" >金融驗證</a><span class="sword" style="display:none">可金融驗證</span>';
                                                                        break;
                                                                    default:
                                                                        break;
                                                                }
                                                            } else {
                                                                switch ($user_status) {
                                                                    case '0':
                                                                        echo '<a target="_blank" href="' . admin_url('certification/user_certification_edit?id=' . $certification_id) . '" ><button type="button" class="btn btn-warning btn-circle nhide"><i class="fa fa-refresh"></i></button></a><span class="sword" style="display:none">資料更新中</span>';
                                                                        break;
                                                                    case '1':
                                                                        echo '<a target="_blank" href="' . admin_url('certification/user_certification_edit?id=' . $certification_id) . '" ><button type="button" class="btn btn-success' . $sys_check . ' nhide"><i class="fa fa-check"></i></button></a><span class="sword" style="display:none">完成</span>';
                                                                        break;
                                                                    case '2':
                                                                        echo '<a target="_blank" href="' . admin_url('certification/user_certification_edit?id=' . $certification_id) . '" ><button type="button" class="btn btn-danger' . $sys_check . ' nhide"><i class="fa fa-times"></i></button></a><span class="sword" style="display:none">已退回</span>';
                                                                        break;
                                                                    case '3':
                                                                        echo '<a target="_blank" href="' . admin_url('certification/user_certification_edit?id=' . $certification_id) . '" class="btn btn-default btn-md nhide" >驗證</a><span class="sword" style="display:none">可驗證</span>';
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
                                                <td></td>
                                            </tr>
                                            <?php
                                        }}
                                    ?>
                                    <?php
                                    if(isset($list[2]) && !empty($list[2])){
                                        foreach($list[2] as $key => $value){
                                            $count++;
                                            ?>
                                            <tr class="<?=$count%2==0?"odd":"even"; ?>">
                                                <td>
                                                    <a class="fancyframe" href="<?=admin_url('target/edit?display=1&id='.$value->id) ?>" >
                                                        <?=isset($value->target_no)?$value->target_no:"" ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a class="fancyframe" href="<?=admin_url('User/display?id='.$value->user_id) ?>" >
                                                        <?=isset($value->user_id)?$value->user_id:"" ?> <?=$value->user_status==1?"舊戶":"新戶" ?>
                                                    </a>
                                                </td>
                                                <td><?
                                                    $productId = isset($value->product_id) ? $value->product_id : 1;
                                                    $subproductId = isset($sub_product_list[$value->sub_product_id]) ? $value->sub_product_id : 0;
                                                    $subloan_list = $this->config->item('subloan_list');
                                                    echo isset($product_list[$productId])?$product_list[$productId]['name']:''; ?><? echo $subproductId!=0?' / '.$sub_product_list[$subproductId]['identity'][$product_list[$productId]['identity']]['name']:''; ?><?=isset($value->target_no)?(preg_match('/'.$subloan_list.'/',$value->target_no)?'(產品轉換)':''):'' ?></td>
                                                <td>
                                                    <?
                                                    if($value->status==2){
                                                        if($value->bank_account_verify){
                                                            $all_pass = true;

                                                            foreach($value->certification as $k => $v){
                                                                if(in_array($v['id'],$product_list[$productId]['certifications']) && $v['user_status']!=1){
                                                                    $all_pass = false;
                                                                }
                                                            }

                                                            if($all_pass){
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
                                                <td><?=isset($value->updated_at)?date("Y-m-d H:i:s",$value->updated_at):"" ?></td>
                                                <? if($certification){
                                                    foreach($certification as $k => $v){
                                                        echo '<td>';
                                                        if(isset($value->certification) && $value->certification[$k]["user_status"]!==null){
                                                            $certification_id 	= $value->certification[$k]["certification_id"];
                                                            $sys_check = $value->certification[$k]["sys_check"]===0?" btn-circle":" ";
                                                            $status      = ($value->certification[$k]['expire_time']<=time()&&!in_array($v['id'],[CERTIFICATION_IDCARD,CERTIFICATION_DEBITCARD,CERTIFICATION_EMERGENCY,CERTIFICATION_EMAIL])?'danger':'success');
                                                            $expire_time = date( "Y/m/d", $value->certification[$k]['expire_time']);
                                                            if($k==3){
                                                                switch($value->certification[$k]["user_status"]){
                                                                    case '0':
                                                                        echo '<a target="_blank" href="'.admin_url('certification/user_bankaccount_edit?id='.$value->bank_account->id).'" class="btn btn-default btn-md nhide" >驗證</a><span class="sword" style="display:none">可驗證</span>';
                                                                        break;
                                                                    case '1':
                                                                        echo '<a target="_blank" href="'.admin_url('certification/user_bankaccount_edit?id='.$value->bank_account->id).'" ><button type="button" class="btn btn-'.$status.''.$sys_check.' nhide"><i class="fa fa-check"></i></button></a><span class="sword" style="display:none">完成</span>';
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
                                                                switch($value->certification[$k]["user_status"]){
                                                                    case '0':
                                                                        echo '<a target="_blank" href="'.admin_url('certification/user_certification_edit?id='.$certification_id).'" ><button type="button" class="btn btn-warning'.$sys_check.' nhide"><i class="fa fa-refresh"></i></button></a><span class="sword" style="display:none">資料更新中</span>';
                                                                        break;
                                                                    case '1':
                                                                        echo '<a target="_blank" href="'.admin_url('certification/user_certification_edit?id='.$certification_id).'" ><button type="button" class="btn btn-'.$status.''.$sys_check.' nhide"><i class="fa fa-check"></i></button></a><span class="sword" style="display:none">完成</span>';
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
                                                <? if(isset($input['investor']) && $input['investor'] != 1){?>
                                                    <td><button class="btn btn-outline btn-danger" onclick="failed(<?=isset($value->id)?$value->id:"" ?>,'<?=isset($value->target_no)?$value->target_no:"" ?>');" >退件</button></td>
                                                <?}?>
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

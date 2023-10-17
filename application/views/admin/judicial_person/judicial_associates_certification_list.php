<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">負責人/保證人 個人認證徵信狀態</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<script type="text/javascript">

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

	</script>

	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<!-- /.panel-heading -->
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

				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" width="100%">
							<thead>
							<tr>
								<th>案號</th>
								<th>會員編號</th>
								<th>名稱</th>
								<th>狀態</th>
								<th>最後更新時間</th>

								<?php
                                    foreach ($certification_list as $cid =>$cValue){
                                        $th = isset($cValue['name'])?$cValue['name']:'';
                                        echo '<th>'.$th.'</th>';
                                    }
								?>
								<th>刪除</th>
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
										<td>
											<a class="fancyframe" href="<?=admin_url('target/edit?display=1&id='.$input['target_id']) ?>" >
												<?=isset($target_no)? $target_no : '' ?>
											</a>
										</td>

										<td>
                                            <?=$value->user_id!=null? '<a class="fancyframe" href="'.admin_url('User/display?id='.$value->user_id).'">'.$value->user_id.'</a>':'未歸戶' ?>
										</td>

										<td><?=isset($value->character) && isset($character_list)?$character_list[$value->character]:'' ?></td>

										<td>
											<?
                                            if($value->cerStatus == 2){
                                                echo '<a target="_blank" href="'.admin_url('certification/user_bankaccount_list?verify=2').'" class="btn btn-default btn-md nhide" >待金融驗證</a><span class="sword" style="display:none">待金融驗證</span>';

                                            }else{
                                                echo $value->cerStatus == 0 ? '未完成' : '完成';
                                            }
                                            ?>
										</td>

										<td><? echo $value->lastUpdate > 0? date("Y-m-d H:i:s", $value->lastUpdate):"N/A" ?></td>

										<?php
											if($certification_list){
												foreach ($certification_list as $cid =>$cValue){
													echo '<td>';
													if(isset($value->certification[$cid])){
                                                        $sys_check = $value->certification[$cid]["sys_check"] === 0 ? " btn-circle" : " ";
                                                        $status = ($value->certification[$cid]['expire_time'] <= time() && !in_array($cid, [CERTIFICATION_IDENTITY, CERTIFICATION_DEBITCARD, CERTIFICATION_EMERGENCY, CERTIFICATION_EMAIL]) ? 'danger' : 'success');
                                                        if($cid==3){
                                                            switch($value->certification[$cid]['user_status']){
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
                                                            switch($value->certification[$cid]['user_status']){
                                                                case '0':
                                                                    echo '<a target="_blank" href="'.admin_url('certification/user_certification_edit?id='.$value->certification[$cid]['certification_id']).'" ><button type="button" class="btn btn-warning'.$sys_check.' nhide"><i class="fa fa-refresh"></i></button></a><span class="sword" style="display:none">資料更新中</span>';
                                                                    break;
                                                                case '1':
                                                                    echo '<a target="_blank" href="'.admin_url('certification/user_certification_edit?id='.$value->certification[$cid]['certification_id']).'" ><button type="button" class="btn btn-'.$status.''.$sys_check.' nhide"><i class="fa fa-check"></i></button></a><span class="sword" style="display:none">完成</span>';
                                                                    break;
                                                                case '2':
                                                                    echo '<a target="_blank" href="'.admin_url('certification/user_certification_edit?id='.$value->certification[$cid]['certification_id']).'" ><button type="button" class="btn btn-danger'.$sys_check.' nhide"><i class="fa fa-times"></i></button></a><span class="sword" style="display:none">已退回</span>';
                                                                    break;
                                                                case '3':
                                                                case CERTIFICATION_STATUS_PENDING_SPOUSE_ASSOCIATE:
                                                                    echo '<a target="_blank" href="'.admin_url('certification/user_certification_edit?id='.$value->certification[$cid]['certification_id']).'" class="btn btn-default btn-md nhide" >驗證</a><span class="sword" style="display:none">可驗證</span>';
                                                                    break;
                                                                default:
                                                                    break;
                                                            }
                                                        }
													}
												}

												echo '<td><button class="btn btn-outline btn-danger" >刪除</button></td>';
												}
											}
										?>

								</tr>
									<?php
								}
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

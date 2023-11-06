<script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript">
    function check_fail() {
        var status = $('#status :selected').val();
        if (status == 1) {
            $('#fail_div').show();
        } else {
            $('#fail_div').hide();
        }
    }
    $( window ).on( "load", function() {
        $(document).on("click",".contract",function() {
            $(window.open().document.body).html('<div style="margin: 0px;padding: 15px;height: 978px;line-height: 24px;letter-spacing: 1px;font-size: 12px;border: 0;width: 650px;white-space: pre-wrap;">'+$(this).data('value')+'</div>');
        });
    });
</script>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">標的資訊</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    標的資訊
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <tbody>
                                    <tr>
                                        <td style="width: 20%;"><p class="form-control-static">案件 ID</p></td>
                                        <td>
                                            <p class="form-control-static"><?= isset($data->id) ? $data->id : ""; ?></p>
                                        </td>
                                        <td><p class="form-control-static">案號</p></td>
                                        <td>
                                            <p class="form-control-static"><?= isset($data->target_no) ? $data->target_no : ""; ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><p class="form-control-static">產品</p></td>
                                        <td>
                                            <p class="form-control-static"><?= isset($data->product_id) ? $product_list[$data->product_id]['name'] : ""; ?><?= $data->sub_product_id != 0 ? ' / ' . $sub_product_list[$data->sub_product_id]['identity'][$product_list[$data->product_id]['identity']]['name'] : '' ?></p>
                                        </td>
                                        <td><p class="form-control-static">申請金額</p></td>
                                        <td>
                                            <p class="form-control-static">
                                                $<?= isset($data->amount) ? $data->amount : ""; ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><p class="form-control-static">核准金額</p></td>
                                        <td>
                                            <p class="form-control-static">
                                                $<?= isset($data->loan_amount) ? $data->loan_amount : ""; ?></p>
                                        </td>
                                        <td><p class="form-control-static">核可利率</p></td>
                                        <td>
                                            <p class="form-control-static"><?= isset($data->interest_rate) ? floatval($data->interest_rate) : ""; ?>
                                                %</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><p class="form-control-static">貸放期間</p></td>
                                        <td>
                                            <p class="form-control-static"><?= isset($data->instalment) ? $instalment_list[$data->instalment] : ""; ?></p>
                                        </td>
                                        <td><p class="form-control-static">計息方式</p></td>
                                        <td>
                                            <p class="form-control-static"><?= isset($data->repayment) ? $repayment_type[$data->repayment] : ""; ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><p class="form-control-static">還款虛擬帳號</p></td>
                                        <td>
                                            <p class="form-control-static">

N                                                <?
                                                    foreach($virtual_accounts as $k => $virtual_account){
                                                        echo '<p><a class="fancyframe" href="'.admin_url('Passbook/display?virtual_account=' . $virtual_account->virtual_account).'">'. $virtual_account->virtual_account.'</a></p>';
                                                    }
                                                ?>
                                            </p>
                                        </td>
                                        <td><p class="form-control-static">備註</p></td>
                                        <td>
                                            <p class="form-control-static"><? echo $data->remark != '' ? nl2br($data->remark) : "無"; ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><p class="form-control-static">狀態</p></td>
                                        <td>
                                            <p class="form-control-static"><?= isset($data->status) ? $status_list[$data->status] : ""; ?></p>
                                        </td>
                                        <td><p class="form-control-static">放款狀態</p></td>
                                        <td>
                                            <p class="form-control-static"><?= isset($data->loan_status) ? $loan_list[$data->loan_status] : ""; ?>
                                                - <?= $bank_account_verify ? "金融帳號已驗證" : "金融帳號未驗證"; ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><p class="form-control-static">平台服務費</p></td>
                                        <td>
                                            <p class="form-control-static"><?= isset($data->platform_fee) ? $data->platform_fee : ""; ?></p>
                                        </td>
                                        <td><p class="form-control-static">放款日期</p></td>
                                        <td>
                                            <p class="form-control-static"><?= isset($data->loan_date) ? $data->loan_date : ""; ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><p class="form-control-static">逾期狀態 / 天數</p></td>
                                        <td>
                                            <p class="form-control-static"><?= isset($data->delay) ? $delay_list[$data->delay] . ' / ' . $data->delay_days : ""; ?>
                                                <? if($data->delay == 1 && $data->delay_days >= 90 && $data->sub_status !=13){ ?>
                                                    <a href="/admin/target/legalAffairs?id=<? echo $data->id ?>" style="margin-left: 10px"><button class="btn btn-danger">轉為法催案件</button></a>
                                                <? }elseif($data->sub_status ==13){ ?>
                                                    ( 法催案件 )
                                                    <? if($data->sub_status ==13){ ?>
                                                        <form action="/admin/target/legalAffairs" method="post">
                                                            <fieldset>
                                                                <div class="form-group">
                                                                    執行費：<input type="text" name="platformfee" value="<?=isset($legalAffairs->platformfee)?$legalAffairs->platformfee:''?>" placeholder="（法院申請費）" <?=isset($legalAffairs->platformfee)?' disabled':''?> /><br />
                                                                    催收手續費：<input type="text" name="fee" value="<?=isset($legalAffairs->fee)?$legalAffairs->fee:''?>" placeholder="（存證信函費用等）" <?=isset($legalAffairs->fee)?' disabled':''?> /><br />
                                                                    違約金：<input type="text" name="liquidateddamages" value="<?=isset($legalAffairs->liquidateddamages)?$legalAffairs->liquidateddamages:''?>" <?=isset($legalAffairs->liquidateddamages)?' disabled':''?> /><br />
                                                                    違約金延滯息：<input type="text" name="liquidateddamagesinterest" value="<?=isset($legalAffairs->liquidateddamagesinterest)?$legalAffairs->liquidateddamagesinterest:''?>" <?=isset($legalAffairs->liquidateddamagesinterest)?' disabled':''?> /><br />
                                                                    延滯息：<input type="text" name="delayinterest" value="<?=isset($legalAffairs->delayinterest)?$legalAffairs->delayinterest:''?>" <?=isset($legalAffairs->delayinterest)?' disabled':''?> />
                                                                    <input type="hidden" name="id" value="<? echo $data->id ?>" />
                                                                    <input type="hidden" name="type" value="set" />
                                                                </div>
                                                                <button type="submit" class="btn btn-primary  <?=isset($legalAffairs->delayinterest)?' hide':''?>">確認執行金額</button>
                                                            </fieldset>
                                                        </form>
                                                    <? } ?>
                                                <? } ?>
                                            </p>
                                        </td>
                                        <td><p class="form-control-static">申請日期</p></td>
                                        <td>
                                            <p class="form-control-static"><?= isset($data->created_at) && !empty($data->created_at) ? date("Y-m-d H:i:s", $data->created_at) : ""; ?></p>
                                        </td>
                                    </tr>
                                    <? if (isset($order->transfer_fee)) { ?>
                                        <tr>
                                            <td><p class="form-control-static">債轉服務費</p></td>
                                            <td colspan="3">
                                                <p class="form-control-static"><?= isset($order->transfer_fee) ? $order->transfer_fee : ""; ?></p>
                                            </td>
                                        </tr>
                                    <? } ?>
                                    <tr>
                                        <td><p class="form-control-static">借款原因</p></td>
                                        <td colspan="3">
                                            <p class="form-control-static"><? echo $reason != '' ? $reason : "未填寫"; ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><p class="form-control-static">授信審核表</p></td>
                                        <td><a target="_blank" href="<?=admin_url('creditmanagement/report_targets_edit')."?type=person&target_id=" . $data->id ?? "";  ?>" class="btn btn-default">查看</a></td>
                                    </tr>
                                    <? if ($data->order_id == 0) { ?>
                                        <tr>
                                            <td><p class="form-control-static">簽約照片</p></td>
                                            <td colspan="3">
                                                <?= !empty($data->person_image) ? "<a href='" . $data->person_image . "' data-fancybox='images'><img src='" . $data->person_image . "' style='width:30%;'></a>" : ""; ?>
                                            </td>
                                        </tr>
                                    <?php if ($data->product_id == 5 && $data->sub_product_id == 10) { ?>
                                        <tr>
                                            <td><p class="form-control-static">房屋所有權狀照片</p></td>
                                            <td colspan="3">
                                                <?php if (!empty(json_decode($data->target_data, true)) && isset(json_decode($data->target_data, true)['deed_image'])) {
                                                    $deed_image_url = json_decode($data->target_data, true)['deed_image'];
                                                    echo "<a href='$deed_image_url' data-fancybox='images'><img alt='' src='$deed_image_url' style='width:30%;'></a>";
                                                } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                        <? if(isset($autoVerifyLog)){ ?>
                                        <tr><td rowspan="3"><p class="form-control-static">簽約照臉部辨識</p></td></tr>
                                            <?
                                            foreach ($autoVerifyLog as $fkey => $fvalue) {
                                                    echo '<tr><td><p class="form-control-static">系統 ' . date("【Y/m/d H:i:s】",$fvalue->verify_at) . ($fvalue->res == TARGET_WAITING_BIDDING ? '案件上架' : '案件退至〈待簽約〉'), '</p>';
                                                ?>
                                                </td><td colspan="2">
                                                <?
                                                if (isset($fvalue->faceDetect->error) && $fvalue->faceDetect->error) {
                                                    echo '<p style="color:red;" class="form-control-static">辨識錯誤：<br />' . $fvalue->faceDetect->error . '</p>';
                                                }
                                                if (isset($fvalue->faceDetect->face) && $fvalue->faceDetect->face && is_array($fvalue->faceDetect->face)) {
                                                    echo '<p class="form-control-static">辨識結果(Sys1)：';
                                                    foreach ($fvalue->faceDetect->face as $key => $value) {
                                                        echo $value . "% ";
                                                    }
                                                    echo '</p>';
                                                    if (isset($fvalue->faceDetect->faceplus) && count($fvalue->faceDetect->faceplus) > 0) {
                                                        echo '<p class="form-control-static">辨識結果(Sys2)：';
                                                        foreach ($fvalue->faceDetect->faceplus as $key => $value) {
                                                            echo $value . "% ";
                                                        }
                                                        echo '</p>';
                                                    }
                                                }
                                                echo '</td></tr>';
                                            }
                                                ?>
                                        <? } ?>
                                    <? } ?>
                                    </tbody>
                                </table>
                            </div>
                            <? if ($data->order_id != 0) { ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" style="text-align:center;">
                                        <tbody>
                                        <tr style="background-color:#f5f5f5;">
                                            <td colspan="8">消費資訊</td>
                                        </tr>
                                        <tr>
                                            <td width="110px">訂單編號</td>
                                            <td width="150px"><?= $order->merchant_order_no; ?></td>
                                            <td width="110px">法人商號(賣家)</td>
                                            <td colspan="3"><a class="fancyframe"
                                                               href="<?= admin_url('judicialperson/cooperation_management_edit?id=' . $judicial_person->id) ?>"><?= isset($judicial_person->user_id) ? $judicial_person->company : ""; ?></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>商品名稱</td>
                                            <td><?= $order->item_name; ?></td>
                                            <td>數量</td>
                                            <td><?= $order->item_count; ?></td>
                                            <td width="110px">報價金額</td>
                                            <td width="100px"><?= $order->amount; ?></td>
                                        </tr>
                                        <tr>
                                            <td>買家暱稱</td>
                                            <td><?= $order->nickname; ?></td>
                                            <td>交易方式</td>
                                            <td colspan="3"><?= $delivery_list[$order->delivery]; ?></td>
                                        </tr>
                                        <tr>
                                            <td>出貨照片</td>
                                            <td colspan="5"><?= $order->shipped_image != '' ? "<a href='" . $order->shipped_image . "' data-fancybox='images'><img src='" . $order->shipped_image . "' style='width:30%;'></a>" : '尚未出貨'; ?></td>
                                        </tr>
                                        <? if ($order->delivery == 1) { ?>
                                            <tr>
                                                <td>貨運單號</td>
                                                <td colspan="5"><?= $order->merchant_orshipped_numberder_no != '' ? $order->merchant_orshipped_numberder_no : '尚未出貨'; ?></td>
                                            </tr>
                                        <? } ?>
                                        <? if ($data->sub_status == 9) { ?>
                                            <tr>
                                                <td colspan="6">
                                                    <form role="form" method="post">
                                                        <fieldset>
                                                            <div class="form-group hide">
                                                                <select id="status" name="status" class="form-control"
                                                                        onchange="check_fail();">
                                                                    <option value="2"></option>
                                                                </select>
                                                                <input type="hidden" name="id"
                                                                       value="<?= isset($data->id) ? $data->id : ""; ?>">
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">核可申請金額
                                                            </button>
                                                        </fieldset>
                                                    </form>
                                                </td>
                                            </tr>
                                        <? } ?>
                                        </tbody>
                                    </table>
                                </div>
                            <? } ?>
                            <div class="table-responsive">
									<table class="table table-bordered table-hover" style="text-align:center;">
										<tbody>
											<tr style="background-color:#f5f5f5;">
												<td colspan="8">
													<a class="fancyframe" href="<?=admin_url('User/display?id='.$user_info->id) ?>" >借款人資訊</a>
												</td>
											</tr>
											<tr>
												<td><p class="form-control-static">借款人ID</p></td>
												<td>
													<p class="form-control-static"><?=isset($user_info->id)?$user_info->id:"";?></p>
												</td>
												<td><p class="form-control-static">姓名</p></td>
												<td>
													<p class="form-control-static"><?=isset($user_info->name)?$user_info->name:"";?></p>
												</td>

												<td><p class="form-control-static">Email</p></td>
												<td colspan="3">
													<p class="form-control-static"><?=isset($user_info->email)?$user_info->email:"";?></p>
												</td>

											</tr>
											<tr>
												<td><p class="form-control-static">發證地點</p></td>
												<td>
													<p class="form-control-static"><?=isset($user_info->id_card_place)?$user_info->id_card_place:"";?></p>
												</td>
												<td><p class="form-control-static">發證日期</p></td>
												<td>
													<p class="form-control-static"><?=isset($user_info->id_card_date)?$user_info->id_card_date:"";?></p>
												</td>
												<td><p class="form-control-static">身分證字號</p></td>
												<td>
													<p class="form-control-static"><?=isset($user_info->id_number)?$user_info->id_number:"";?></p>
												</td>
												<td><p class="form-control-static">性別</p></td>
												<td>
													<p class="form-control-static"><?=isset($user_info->sex)?$user_info->sex:"";?></p>
												</td>
											</tr>
											<tr>
												<td><p class="form-control-static">生日</p></td>
												<td>
													<p class="form-control-static"><?=isset($user_info->birthday)?$user_info->birthday:"";?></p>
												</td>
												<td><p class="form-control-static">電話</p></td>
												<td>
													<p class="form-control-static"><?=isset($user_info->phone)?$user_info->phone:"";?></p>
												</td>
												<td><p class="form-control-static">地址</p></td>
												<td colspan="3">
													<p class="form-control-static"><?=isset($user_info->address)?$user_info->address:"";?></p>
												</td>
											</tr>
											<tr>
												<td><p class="form-control-static">借款端帳號</p></td>
												<td>
													<p class="form-control-static"><?=isset($user_info->status)&&$user_info->status?"正常":"未申請";?></p>
												</td>
												<td><p class="form-control-static">出借端帳號</p></td>
												<td>
													<p class="form-control-static"><?=isset($user_info->status)&&$user_info->status?"正常":"未申請";?></p>
												</td>
												<td><p class="form-control-static">註冊日期</p></td>
												<td colspan="3">
													<p class="form-control-static"><?=isset($user_info->created_at)&&!empty($user_info->created_at)?date("Y-m-d H:i:s",$user_info->created_at):"";?></p>
												</td>
											</tr>
											<? if($data->status==5 || $data->status==10){?>
												<tr style="background-color:#f5f5f5;">
													<td colspan="8">
														<a class="fancyframe" href="<?=admin_url('Target/transaction_display?id='.$data->id) ?>" >攤還表</a>
													</td>
												</tr>
												<tr style="background-color:#f5f5f5;">
													<th>貸放期間</th>
													<td>期初本金</td>
													<td>還款日</td>
													<td>日數</td>
													<td>還款本息</td>
													<td>違約延滯</td>
													<td>還款合計</td>
													<td>已還款金額</td>
												</tr>
												<? if($amortization_table){
													foreach($amortization_table["list"] as $key => $value){
												?>
												<tr>
													<td><?=$value['instalment'] ?></td>
													<td><?=$value['remaining_principal'] ?></td>
													<td><?=$value['repayment_date'] ?></td>
													<td><?=$value['days'] ?></td>
                                                    <td><?= $value['principal'] ?>
                                                        <br><?= $value['interest'] ?></td>
                                                    <td style="color:red;"><?= $value['liquidated_damages'] ?>
                                                        <br><?= $value['delay_interest'] ?>
                                                        <? if($amortization_table['legal_affairs_fee'] > 0){
                                                            $value['total_payment'] += $amortization_table['legal_affairs_fee'];
                                                            $value['repayment'] += $amortization_table['legal_affairs_fee'];
                                                            ?>
                                                            <br><?= $amortization_table['legal_affairs_fee'] ?> (法催費用)
                                                        <? } ?>
                                                    </td>
													<td><?=$value['total_payment'] ?></td>
													<td><?=$value['repayment'] ?></td>
												</tr>
											<? }}} ?>
										</tbody>
									</table>
								</div>
							</div>
							<? if(in_array($data->status,array(4,5,10))){?>
							<div class="col-lg-6">
								<div class="table-responsive">
								<? if($investments){
									foreach($investments as $key => $value){
								?>
								<table class="table table-bordered table-hover" style="text-align:center;">
										<tbody>
											<tr style="background-color:#f5f5f5;">
												<td colspan="8">
													<a class="fancyframe" href="<?=admin_url('User/display?id='.$value->user_info->id) ?>" >出借人資訊：<?=isset($value->user_info->id)?$value->user_info->id:"";?></a>
												</td>
											</tr>
											<tr>
												<td><p class="form-control-static">出借人ID</p></td>
												<td>
													<p class="form-control-static"><?=isset($value->user_info->id)?$value->user_info->id:"";?></p>
												</td>
												<td><p class="form-control-static">姓名</p></td>
												<td>
													<p class="form-control-static"><?=isset($value->user_info->name)?$value->user_info->name:"";?></p>
												</td>
                                             <? if(strlen($value->user_info->id_number) == 10){ ?>
												<td><p class="form-control-static">Email</p></td>
												<td colspan="3">
													<p class="form-control-static"><?=isset($value->user_info->email)?$value->user_info->email:"";?></p>
												</td>
                                            </tr>
                                            <tr>
                                                <td><p class="form-control-static">發證地點</p></td>
                                                <td>
                                                    <p class="form-control-static"><?=isset($value->user_info->id_card_place)?$value->user_info->id_card_place:"";?></p>
                                                </td>
                                                <td><p class="form-control-static">發證日期</p></td>
                                                <td>
                                                    <p class="form-control-static"><?=isset($value->user_info->id_card_date)?$value->user_info->id_card_date:"";?></p>
                                                </td>
                                                <td><p class="form-control-static">身分證字號</p></td>
                                                <td>
                                                    <p class="form-control-static"><?=isset($value->user_info->id_number)?$value->user_info->id_number:"";?></p>
                                                </td>
                                                <td><p class="form-control-static">性別</p></td>
                                                <td>
                                                    <p class="form-control-static"><?=isset($value->user_info->sex)?$value->user_info->sex:"";?></p>
                                                </td>
                                            </tr>
                                             <? }else{ ?>
                                                    <td><p class="form-control-static">統編</p></td>
                                                    <td colspan="3">
                                                        <p class="form-control-static"><?=isset($value->user_info->id_number)?$value->user_info->id_number:"";?></p>
                                                    </td>
                                             <? } ?>

											<tr>
												<td><p class="form-control-static">投標</p></td>
												<td>
													<p class="form-control-static"><?=isset($value->amount)?$value->amount:"";?></p>
												</td>
												<td><p class="form-control-static">得標</p></td>
												<td>
													<p class="form-control-static"><?=isset($value->loan_amount)?$value->loan_amount:"";?></p>
												</td>

												<td><p class="form-control-static">匯款時間</p></td>
												<td colspan="3">
													<p class="form-control-static"><?=isset($value->tx_datetime)?$value->tx_datetime:"";?></p>
												</td>
											</tr>
											<tr>
												<td><p class="form-control-static">出借虛擬帳號</p></td>
												<td colspan="3">
													<p class="form-control-static">
													<a class="fancyframe" href="<?=admin_url('Passbook/display?virtual_account='.$value->virtual_account->virtual_account) ?>" ><?=$value->virtual_account->virtual_account ?></a>
													</p>
												</td>
												<td><p class="form-control-static">待交易區流水號</p></td>
                                                <td>
                                                    <p class="form-control-static"><?= isset($value->frozen_id) ? $value->frozen_id : ""; ?></p>
                                                </td>
                                                <td colspan="2"><p class="form-control-static contract" data-id="<?=$value->id ?>" data-value="<?=$value->contract ?>"style="color: #428bca;text-decoration: none;cursor: pointer;">借貸契約</p></td>
											</tr>
											<? if(isset($investments_amortization_schedule[$value->id]) && $investments_amortization_schedule[$value->id]){?>
												<tr style="background-color:#f5f5f5;">
													<td colspan="7">預計攤還表</td>
												</tr>
												<tr style="background-color:#f5f5f5;">
													<td>本金合計</td>
													<td colspan="2"><?=$investments_amortization_schedule[$value->id]["amount"]?></td>
													<td>本息合計</td>
													<td colspan="3"><?=$investments_amortization_schedule[$value->id]["total"]["total_payment"]?></td>
                                                    <td>放款日</td>
                                                    <td colspan="3"><?= $investments_amortization_schedule[$value->id]["date"] ?></td>
                                                </tr>
												<tr style="background-color:#f5f5f5;">
													<th>貸放期間</th>
													<td>期初本金</td>
													<td>還款日</td>
													<td>日數</td>
													<td>還款本金</td>
													<td>還款利息</td>
													<td>還款合計</td>
												</tr>
												<?	foreach($investments_amortization_schedule[$value->id]["schedule"] as $k => $v){ ?>


												<tr>
													<td><?=$v['instalment'] ?></td>
													<td><?=$v['remaining_principal'] ?></td>
													<td><?=$v['repayment_date'] ?></td>
													<td><?=$v['days'] ?></td>
													<td><?=$v['principal'] ?></td>
													<td><?=$v['interest'] ?></td>
													<td><?=$v['total_payment'] ?></td>
												</tr>
											<? }} ?>

											<? if(isset($investments_amortization_table[$value->id]) && $investments_amortization_table[$value->id]){ ?>
												<tr style="background-color:#f5f5f5;">
													<td colspan="8"><a class="fancyframe" href="<?=admin_url('Target/transaction_display?id='.$data->id) ?>" >攤還表</a></td>
												</tr>
												<tr style="background-color:#f5f5f5;">
													<td>本金合計</td>
													<td><?=$investments_amortization_table[$value->id]["amount"]?></td>
													<td>本息合計</td>
													<td><?=$investments_amortization_table[$value->id]["total_payment"]?></td>
                                                    <td>放款日</td>
                                                    <td colspan="3"><?= $investments_amortization_table[$value->id]["date"] ?></td>
                                                </tr>
												<tr style="background-color:#f5f5f5;">
													<th>貸放期間</th>
													<td>期初本金</td>
													<td>還款日</td>
													<td>日數</td>
													<td>還款本金</td>
													<td>還款利息</td>
													<td>還款合計</td>
													<td>已還款金額</td>
												</tr>
												<?	foreach($investments_amortization_table[$value->id]["list"] as $k => $v){ ?>
												<tr>
													<td><?=$v['instalment'] ?></td>
													<td><?=$v['remaining_principal'] ?></td>
													<td><?=$v['repayment_date'] ?></td>
													<td><?=$v['days'] ?></td>
													<td><?=$v['principal'] ?></td>
													<td><?=$v['interest'] ?></td>
													<td><?=$v['total_payment'] ?></td>
													<td><?=$v['repayment'] ?></td>
												</tr>
											<? }} ?>
										</tbody>
									</table>
								<?}}?>
								</div>
							</div>
							<? }?>
						</div>
						<!-- /.row (nested) -->
					</div>
					<!-- /.panel-body -->
				</div>
				<!-- /.panel -->
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<!-- /.row -->
    </div>
    <!-- /#page-wrapper -->

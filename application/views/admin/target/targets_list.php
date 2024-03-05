        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">借款 - 全部列表</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <style>
                .panel-heading td {
                    height: 30px;
                    vertical-align: middle;
                    padding-left: 5px;
                }
                .tsearch input {
                    width: 640px;
                }
            </style>
			<script type="text/javascript">
                function showChang(){
                    var tsearch 			= $('#tsearch').val();
                    var delay 				= $('#delay :selected').val();
                    var status 				= $('#status :selected').val();
                    var exports 			= $('#export :selected').val();
                    var dateRange           = '&sdate='+$('#sdate').val()+'&edate='+$('#edate').val();
                    const url = new URL(location.href);
                    if(tsearch==''&&delay==''&&status==''){
                        if(confirm("即將撈取各狀態案件，過程可能需點時間，請勿直接關閉， 確認是否執行？")) {
                            top.location = url.pathname + '?status=99'+(exports==1?'&export=1':'')+dateRange;
                        }
                    }
                    else{
                        let searchParam = [
                            ['delay', delay],
                            ['status', status],
                            ['tsearch', tsearch],
                            ['sdate', $('#sdate').val()],
                            ['edate', $('#edate').val()],
                        ];
						switch (exports) {
							case '1':
                                searchParam.push(['export', 1]);
								break;
							case '2': // Excel輸出-逾期債權 by 債權
                                searchParam.push(['export', 2]);
								break;
							case '3': // Excel輸出-逾期債權 by 案件
                                searchParam.push(['export', 3]);
								break;
						}
                        url.search = new URLSearchParams(searchParam);
						top.location = url.href;
                    }
				}
                $(document).off("keypress","input[type=text]").on("keypress","input[type=text]" ,  function(e){
                    code = (e.keyCode ? e.keyCode : e.which);
                    if (code == 13){
                        showChang();
                    }
                });
			</script>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							<table>
								<tr>
									<td>搜尋：</td>
									<td class="tsearch" colspan="7"><input type="text" value="<?=isset($_GET['tsearch'])&&$_GET['tsearch']!=''?$_GET['tsearch']:''?>" id="tsearch" placeholder="使用者代號(UserID) / 姓名 / 身份證字號 / 案號" /></td>
								</tr>
								<tr>
                                    <td>逾期：</td>
                                    <td>
                                        <select id="delay">
                                            <option value='' >請選擇</option>
                                            <? foreach($delay_list as $key => $value){ ?>
                                                <option value="<?=$key?>" <?=isset($_GET['delay'])&&$_GET['delay']!=''&&intval($_GET['delay'])==intval($key)?"selected":''?>><?=$value?></option>
                                            <? } ?>
                                        </select>
                                    </td>
                                    <td>狀態：</td>
                                    <td colspan="5">
                                        <select id="status">
                                            <option value='99' <?=isset($_GET['status'])&&$_GET['status']!=''&& intval($_GET['status']) == '99'?"selected":''?>>全部狀態</option>
                                            <option value='5,10' <?=isset($_GET['status']) && $_GET['status'] != '' && $_GET['status'] == '5,10' ? "selected" : ''?>>交易案件(還款中+已結案)</option>
                                            <? foreach($status_list as $key => $value){ ?>
                                            <option value="<?=$key?>" <?=isset($_GET['status']) && $_GET['status']!='' && strpos($_GET['status'], ',') == false && intval($_GET['status']) == intval($key) ? "selected" : '' ?>><?=$value?></option>
                                            <? } ?>
                                        </select>
                                    </td>
								</tr>
                                <tr style="vertical-align: baseline;">
                                    <td>從：</td>
                                    <td><input type="text" value="<?=isset($_GET['sdate'])&&$_GET['sdate']!=''?$_GET['sdate']:''?>" id="sdate" data-toggle="datepicker" placeholder="不指定區間" /></td>
                                    <td style="">到：</td>
                                    <td><input type="text" value="<?=isset($_GET['edate'])&&$_GET['edate']!=''?$_GET['edate']:''?>" id="edate" data-toggle="datepicker" style="width: 182px;"  placeholder="不指定區間" /></td>
                                    <td style="">輸出：</td>
                                    <td>
                                        <select id="export">
                                            <option value='0' >頁面顯示</option>
											<?php if (isset($_GET['delay']) && $_GET['delay'] === '1' && isset($_GET['status']) && $_GET['status'] == '5') {
                                                echo "<option value='3' >Excel輸出-逾期債權 by 案件</option>";
												echo "<option value='2' >Excel輸出-逾期債權 by 債權</option>";
											}
                                            else
                                            {
                                                echo "<option value='1' >Excel輸出</option>";
                                            }?>
                                        </select>
                                    </td>
                                    <td colspan="2" style="text-align: right"><a href="javascript:showChang();" class="btn btn-default">查詢</a></td>
                                </tr>
                            </table>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-tables">
                                    <thead>
                                        <tr>
                                            <th>案號</th>
                                            <th>產品</th>
                                            <th>會員 ID</th>
                                            <th>信評</th>
                                            <th>學校</th>
                                            <th>公司</th>
                                            <th>最高學歷</th>
                                            <th>科系</th>
                                            <th>申請金額</th>
                                            <th>核准金額</th>
                                            <th>動用金額</th>
                                            <th>可動用額度</th>
											<th>本金餘額</th>
											<th>年化利率</th>
                                            <th>貸放期間</th>
                                            <th>計息方式</th>
                                            <th>放款日期</th>
                                            <th>逾期狀況</th>
                                            <th>逾期天數</th>
                                            <th>狀態</th>
                                            <th>借款原因</th>
                                            <th>申請日期</th>
                                            <th>核准日期</th>
                                            <th>邀請碼</th>
                                            <th class="for_all_target">審核人員</th>
                                            <th class="for_all_target">姓名</th>
                                            <th style="width: 280px;">備註</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
										if(isset($list) && !empty($list)){
											$count = 0;
                                            foreach($list as $key => $value){
                                                $count++;
									?>
                                        <tr class="<?=$count % 2 == 0 ? "odd" : "even"; ?> list <?=$value->user_id ?? '' ?>">
                                            <td><?=$value->target_no ?? '' ?></td>
                                            <td><?=isset($product_list[$value->product_id]) ? $product_list[$value->product_id]['name'] : '' ?><?=$value->sub_product_id!=0?' / '.$sub_product_list[$value->sub_product_id]['identity'][$product_list[$value->product_id]['identity']]['name']:'' ?><?=isset($value->target_no)?(preg_match('/'.$subloan_list.'/',$value->target_no)?'(產品轉換)':''):'' ?></td>
                                            <td><?=$value->user_id ?? '' ?></td>
                                            <td><?=$value->credit_level ?? '' ?></td>
                                            <td><?=$value->school_name ?? '' ?></td>
                                            <td><?=$value->company ?? '' ?></td>
                                            <td><?=$value->diploma ?? '' ?></td>
                                            <td><?=$value->school_department ?? '' ?></td>
                                            <td><?=$value->amount ?? '' ?></td>
                                            <td><?=$value->credit->amount ?? '' ?></td>
                                            <td><?=$value->loan_amount ?? '' ?></td>
                                            <td><?= // 可動用額度
                                                $value->remain_amount ?? '' ?>
                                            </td>
                                            <td><?=$value->remaining_principal ?? '' ?></td>
                                            <td><?=isset($value->interest_rate) && $value->interest_rate ? floatval($value->interest_rate) : '' ?></td>
                                            <td><?=isset($value->instalment) ? $instalment_list[$value->instalment] : '' ?></td>
                                            <td><?=isset($value->repayment) ? $repayment_type[$value->repayment] : '' ?></td>
                                            <td><?=$value->loan_date ?? '' ?></td>
                                            <td><?=isset($value->delay) ? $delay_list[$value->delay] : '' ?></td>
											<td><?=isset($value->delay_days) ? intval($value->delay_days) : "" ?></td>
                                            <td>
											<?=isset($value->status)?($value->sub_status==5?'待廠商出貨 (分期)':$status_list[$value->status]):'' ?>
											<? 	if($value->status==2 && !$value->bank_account_verify){
													echo '<p style="color:red;">金融帳號未驗證</p>';
												}
											?>
											</td>
                                            <td><?=isset($value->reason) ? $value->reason: '' ?></td>
                                            <td><?=isset($value->created_at)?date("Y-m-d H:i:s",$value->created_at):'' ?></td>
                                            <td><?=isset($value->credit->created_at)?date("Y-m-d H:i:s",$value->credit->created_at):'' ?></td>
                                            <td><?=isset($value->promote_code)?$value->promote_code:'' ?></td>
                                            <td class="for_all_target"><?= $value->review_by ?? '' ?></td>
                                            <td class="for_all_target"><?= $value->credit_sheet_reviewer ?? '' ?></td>
                                            <td><?=isset($value->remark)?nl2br($value->remark):'' ?></td>
											<td><a href="<?=admin_url('target/detail')."?id=".$value->id ?>" class="btn btn-default">Detail</a></td>
                                        </tr>
									<?php
									        }
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

<script type="text/javascript">
    $(document).ready(function () {
        const url = new URL(location.href);
        const match = url.pathname.split("/");
        if (match[3] !== 'index') {
            $('.for_all_target').css('display', 'none');
        }
    });
</script>
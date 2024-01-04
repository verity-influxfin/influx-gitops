        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">推薦有賞 - 全部列表</h1>
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
                const url2 = new URL(location.href);

                function showChang(){
                    var tsearch 			= $('#tsearch').val();
                    var alias 				= $('#alias :selected').val();
                    var dateRange           = '&sdate='+$('#sdate').val()+'&edate='+$('#edate').val();
                    if(tsearch===''&&alias===''){
                        top.location = './promote_list?'+dateRange;
                    }
                    else{
                        top.location = './promote_list?tsearch='+tsearch+(alias!==''?'&alias='+alias:'')+dateRange;
                    }
				}
                $(document).off("keypress","input[type=text]").on("keypress","input[type=text]" ,  function(e){
                    code = (e.keyCode ? e.keyCode : e.which);
                    if (code == 13){
                        showChang();
                    }
                });

                function promote_export_list() {
                    let searchParams = {
                        tsearch: $('#tsearch').val(),
                        alias: $('#alias :selected').val(),
                        sdate: $('#sdate').val(),
                        edate: $('#edate').val(),
                        export: 1
                    };
                    url2.search = new URLSearchParams(searchParams);
                    top.location = url2.href;
                }
			</script>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading" style=" display: flex;">
                            <div class="col-lg-9">
                                <table>
                                    <tr>
                                        <td>搜尋：</td>
                                        <td class="tsearch" colspan="7"><input type="text" value="<?=isset($_GET['tsearch'])&&$_GET['tsearch']!=''?$_GET['tsearch']:''?>" id="tsearch" placeholder="使用者代號(UserID) / 姓名 / 身份證字號 / 推薦碼" /></td>
                                    </tr>
                                    <tr>
                                        <td>類型：</td>
                                        <td colspan="5">
                                            <select id="alias">
                                                <? foreach($alias_list as $key => $value){ ?>
                                                <option value="<?=$key?>" <?=isset($_GET['alias'])&&$_GET['alias']!=''&&$_GET['alias']==$key?"selected":''?>><?=$value?></option>
                                                <? } ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr style="vertical-align: baseline;">
                                        <td>從：</td>
                                        <td><input type="text" value="<?=isset($_GET['sdate'])&&$_GET['sdate']!=''?$_GET['sdate']:''?>" id="sdate" data-toggle="datepicker" placeholder="不指定區間" /></td>
                                        <td style="">到：</td>
                                        <td><input type="text" value="<?=isset($_GET['edate'])&&$_GET['edate']!=''?$_GET['edate']:''?>" id="edate" data-toggle="datepicker" style="width: 182px;"  placeholder="不指定區間" /></td>
                                        <td colspan="2" style="text-align: right"><a href="javascript:showChang();" class="btn btn-default">查詢</a></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-lg-3">
                                <div style="
                                    display: flex;
                                    justify-content: flex-end;
                                    align-self: flex-start;
                                     ">
                                    <a type="button" href="javascript:void(0)" class="btn btn-default" onclick="promote_export_list()">報表匯出</a>&nbsp;
                                    <a type="button" href="<?=admin_url('sales/promote_import_list') ?>" class="btn btn-primary">前往報表匯入</a>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" width="100%" id="dataTables-tables">
                                    <thead>
                                        <tr>
                                            <th>合約截止日</th>
                                            <th>會員 ID</th>
                                            <th>類型</th>
                                            <th>名稱</th>
                                            <th>邀請碼</th>
                                            <th>註冊+下載數量</th>
                                            <th>學生貸核貸數量</th>
                                            <th>上班族貸核貸數量</th>
                                            <th>合作產品核貸數量</th>
											<th>累計獎金</th>
											<th>狀態</th>
                                            <th>詳細資訊</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
                                        $count = 0;
										if(isset($list) && !empty($list)) {
                                            foreach($list as $key => $value) {
                                                $count++;

                                                $student_count = $value['api']['award_info']["student_count"] ?? 0;
                                                $salary_man_count = $value['api']['award_info']["salary_man_count"] ?? 0;
                                                $small_enterprise_count = $value['api']['award_info']["small_enterprise_count"] ?? 0;
                                                $promote_count = $value['api']['award_info']['promote_count'] ?? 0;
                                                $studentRewardAmount = $value['api']['award_info']['student_amount'] ?? 0;
                                                $salaryManRewardAmount = $value['api']['award_info']['salary_man_amount'] ?? 0;
                                                $smallEnterpriseAmount = $value['api']['award_info']['small_enterprise_amount'] ?? 0;
//                                                $totalRewardAmount = $value['totalRewardAmount'] ?? 0;
                                                $totalRewardAmount = $studentRewardAmount + $salaryManRewardAmount + $smallEnterpriseAmount;

									?>
                                        <tr class="<?= $count%2==0?"odd":"even"; ?> list <?= $value['info']['id'] ?? '' ?>">
                                            <td><?= $value['info']['contract_end_time']??'' ?></td>
                                            <td><?= $value['info']['user_id']??'' ?></td>
                                            <td><?= $value['info']['settings']['description']??'' ?></td>
                                            <td><?= $value['info']['name']??'' ?></td>
                                            <td><?= $value['info']['promote_code']??'' ?></td>
                                            <td><?= $promote_count??'' ?></td>
                                            <td><?= $student_count??'' ?></td>
                                            <td><?= $salary_man_count??'' ?></td>
                                            <td><?= $small_enterprise_count??'' ?></td>
                                            <td><?= $totalRewardAmount ?></td>
                                            <td><?= ($value['info']['status']??'')==1?"啟用":"停用" ?></td>
											<td><a href="<?=admin_url('sales/promote_detail')."?id=".$value['info']['id'] ?><?=isset($_GET['sdate'])&&$_GET['sdate']!=''?"&sdate=".$_GET['sdate']:''?><?=isset($_GET['edate'])&&$_GET['edate']!=''?"&edate=".$_GET['edate']:''?>" target="_blank" class="btn btn-default">詳細資訊</a></td>
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
                <div class="col-lg-12" style="
                    display: flex;
                    align-items: center;
                    justify-content: end;
                ">
                <?= $links ?>
                </div>
                <!-- /.col-lg-12 -->
            </div>
        </div>
        <!-- /#page-wrapper -->

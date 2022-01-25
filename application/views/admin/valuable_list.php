        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">高價值用戶 - 查詢列表</h1>
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

            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading" style="display: flex; justify-content: space-between; align-items: flex-end;">
							<table>
								<tr>
                                    <td>產品：</td>
                                    <td colspan="5">
                                        <select id="alias">
                                            <? foreach($product_list as $key => $value){ ?>
                                            <option value="<?=$key?>" <?=isset($_GET['product_id'])&&$_GET['product_id']!=''&&$_GET['product_id']==$key?"selected":''?>><?=$value['name']?></option>
                                            <? } ?>
                                        </select>
                                    </td>
								</tr>
                                <tr style="vertical-align: baseline;">
                                    <td>從：</td>
                                    <td><input type="text" value="<?=isset($_GET['start_date'])&&$_GET['start_date']!=''?$_GET['start_date']:''?>" id="sdate" data-toggle="datepicker" placeholder="不指定區間" /></td>
                                    <td style="">到：</td>
                                    <td><input type="text" value="<?=isset($_GET['end_date'])&&$_GET['end_date']!=''?$_GET['end_date']:''?>" id="edate" data-toggle="datepicker" style="width: 182px;"  placeholder="不指定區間" /></td>
                                    <td colspan="2" style="text-align: right"><a href="javascript:showChang();" class="btn btn-default">查詢</a></td>
                                </tr>
                            </table>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" width="100%" id="dataTables-tables">
                                    <thead>
                                        <tr>
                                            <th>會員 ID</th>
                                            <th>半年內申貸案數</th>
                                            <th>申貸頻率(天)</th>
                                            <th>系統拒絕紀錄</th>
                                            <th>申貸紀錄</th>
                                            <th>信用額度</th>
                                            <th>通過實名</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
                                        $count = 0;
										if(isset($list) && !empty($list)) {
                                            foreach($list as $user_id => $value) {
                                                $count++;
									?>
                                        <tr class="<?= $count%2==0?"odd":"even"; ?> list <?= $value['id'] ?? '' ?>">
                                            <td><?= $user_id??'' ?></td>
                                            <td><?= $value['count']??'' ?></td>
                                            <td><?= $value['frequent']??'' ?></td>
                                            <td><?= $value['banned_flag']??'' ?></td>
                                            <td><?= $value['apply_status']??'' ?></td>
                                            <td><?= $value['credit_status']??'' ?></td>
                                            <td><?= $value['identity']??'' ?></td>
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

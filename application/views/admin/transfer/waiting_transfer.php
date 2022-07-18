        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">債權轉讓 - 待收購</h1>
                </div>
                <!-- /.col-lg-12 -->

			</div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><h4>單筆債權</h4></div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" width="100%" id="dataTables-tables">
                                    <thead>
                                    <tr>
                                        <th>案號</th>
                                        <th>出讓人會員 ID</th>
                                        <th>債權金額</th>
                                        <th>案件總額</th>
                                        <th>年化利率</th>
                                        <th>價金</th>
                                        <th>剩餘本金</th>
                                        <th>本期利息</th>
                                        <th>本期回款手續費</th>
                                        <th>剩餘期數</th>
                                        <th>有效時間</th>
                                        <th>Detail</th>
                                    </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
                                    <?php
                                    if(isset($list) && !empty($list)){
                                        $count = 0;
                                        foreach($list as $key => $value){
                                            $count++;
                                            ?>
                                            <tr class="<?=$count%2==0?"odd":"even"; ?> list <?=isset($value->user_id)?$value->user_id:"" ?>">
                                                <td>
                                                    <?=isset($value->target->target_no)?$value->target->target_no:"" ?>
                                                </td>
                                                <td><?=isset($value->investment->user_id)&&$value->investment->user_id?$value->investment->user_id:"" ?></td>
                                                <td><?=isset($value->investment->loan_amount)&&$value->investment->loan_amount?$value->investment->loan_amount:"" ?></td>
                                                <td><?=isset($value->target->loan_amount)&&$value->target->loan_amount?($value->target->order_id!=0?$value->amount:$value->target->loan_amount):"" ?></td>
                                                <td><?=isset($value->target->interest_rate)&&$value->target->interest_rate?floatval($value->target->interest_rate).'%':"" ?></td>
                                                <td><?=isset($value->amount)?$value->amount:"" ?></td>
                                                <td><?=isset($value->principal)?$value->principal:"" ?></td>
                                                <td><?=isset($value->interest)?$value->interest:"" ?></td>
                                                <td><?=isset($value->platform_fee)?$value->platform_fee:"" ?></td>
                                                <td><?=isset($value->instalment)?$value->instalment:"" ?></td>
                                                <td><?=isset($value->expire_time)?date("Y-m-d",$value->expire_time):"" ?></td>
                                                <td><a href="<?=admin_url('target/waiting_transfer_detail')."?id=".$value->target->id ?>" class="btn btn-default" target="_blank">Detail</a></td>
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
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><h4>打包債權</h4></div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" width="100%" id="dataTables-tables2">
                                    <thead>
                                    <tr>
                                        <th>案號</th>
                                        <th>出讓人會員ID</th>
                                        <th>債權筆數</th>
                                        <th>平均年化利率</th>
                                        <th>價金</th>
                                        <th>剩餘本金</th>
                                        <th>已發生利息</th>
                                        <th>已發生延滯息</th>
                                        <th>最小剩餘期數</th>
                                        <th>最大剩餘期數</th>
                                        <th>有效時間</th>
                                        <th>打包內容</th>
                                    </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
                                    <?php
                                    if(isset($combinations) && !empty($combinations)){
                                        $count = 0;
                                        foreach($combinations as $key => $value){
                                            $count++;
                                            ?>
                                            <tr class="<?=$count%2==0?"odd":"even"; ?> list <?=isset($value->user_id)?$value->user_id:"" ?>">
                                                <td><?=isset($value->combination_no)?$value->combination_no:"" ?></td>
                                                <td><?=isset($value->user_id)?$value->user_id:"" ?></td>
                                                <td><?=isset($value->count)?$value->count:"" ?></td>
                                                <td><?=isset($value->interest_rate)?$value->interest_rate:"" ?></td>
                                                <td><?=isset($value->amount)?$value->amount:"" ?></td>
                                                <td><?=isset($value->principal)?$value->principal:"" ?></td>
                                                <td><?=isset($value->interest)?$value->interest:"" ?></td>
                                                <td><?=isset($value->delay_interest)?$value->delay_interest:"" ?></td>
                                                <td><?=isset($value->min_instalment)?$value->min_instalment:"" ?></td>
                                                <td><?=isset($value->max_instalment)?$value->max_instalment:"" ?></td>
                                                <td><?=isset($value->expire_time)?date("Y-m-d",$value->expire_time):"" ?></td>
                                                <td><a href="<?=admin_url('transfer/transfer_combination')."?id=".(isset($value->id)?$value->id:"")."&no=",(isset($value->combination_no)?$value->combination_no:"") ?>" class="btn btn-default fancyframe">詳情</a></td>
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
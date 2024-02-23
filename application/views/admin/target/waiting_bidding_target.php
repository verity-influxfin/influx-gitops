<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">借款 - 已上架</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- DataTables JavaScript -->
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript">
        function cancel(id) {
            if (confirm("確認強制下架？案件將退回前一狀態")) {
                if (id) {
                    var p = prompt("請輸入退案原因，將自動通知使用者，不通知請按取消", "");
                    var remark = "";
                    if (p) {
                        remark = encodeURIComponent(p);
                    }
                    $.ajax({
                        url: './cancel_bidding?id=' + id + '&remark=' + remark,
                        type: 'GET',
                        success: function (response) {
                            alert(response);
                            location.reload();
                        }
                    });
                }
            }
        }
        var dataTableSet = {
            "bPaginate": false, // 顯示換頁
            "searching": true, // 顯示搜尋
            "info": false, // 顯示資訊
            "fixedHeader": true, // 標題置頂
            "dom": '<"pull-left"f><"pull-right"l>tip',
            "oLanguage": {
                "sProcessing": "處理中...",
                "sLengthMenu": "顯示 _MENU_ 項結果",
                "sZeroRecords": "目前無資料",
                "sInfo": "顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
                "sInfoEmpty": "顯示第 0 至 0 項結果，共 0 項",
                "sInfoFiltered": "(從 _MAX_ 項結果過濾)",
                "sSearch": "模糊搜尋:",
                "oPaginate": {
                    "sFirst": "首頁",
                    "sPrevious": "上頁",
                    "sNext": "下頁",
                    "sLast": "尾頁"
                }
            }
        };
    </script>
    <!-- /.row -->
    <?
    $typeList = [
        'personal' => '個金',
        'judicialPerson' => '企金',
        'judicialPersonFormBank' => '企金(信保)'
    ];
    if (isset($list) && !empty($list)) {
        foreach ($list as $typeKey => $typeValue) {
            ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <? echo '<h3>' . $typeList[$typeKey] . '</h3>'; ?>
                        </div>
                        <!-- /.panel-heading -->

                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" width="100%" <? if (!isset($input['target_id'])) { ?>id="dataTables-tables<? echo $typeKey;
                                } ?>">
                                    <thead>
                                        <tr>
                                            <th>案號</th>
                                            <th>產品</th>
                                            <th>會員 ID</th>
                                            <th>信用等級</th>
                                            <?=
                                                $typeKey == 'personal' ? '<th>學校</th><th>公司</th><th>最高學歷</th><th>科系</th>' : '<th>公司名稱</th>'
                                            ?>
                                            <?= $typeKey != 'judicialPersonFormBank' ? '<th>申請金額</th>' : '' ?>
                                            <th>核准額度</th>
                                            <th>利率</th>
                                            <th>貸放期間</th>
                                            <th>計息方式</th>
                                            <?= $typeKey != 'judicialPersonFormBank' ? '<th>目前投標金額</th><th>上架次數</th><th>流標期限</th>' : '<th>申貸狀態</th><th>狀態更新日期</th>' ?>
                                            <th>上架日期</th>
                                            <th>邀請碼</th>
                                            <th>授信審核表</th>
                                            <?= $typeKey != 'judicialPersonFormBank' ? '<th></th>' : '' ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $count = 0;
                                        foreach ($typeValue as $key => $value) {
                                            $count++;
                                            ?>
                                            <tr class="<?= $count % 2 == 0 ? "odd" : "even"; ?>">
                                                <td><?= isset($value->target_no) ? $value->target_no : '' ?></td>
                                                <td><?= isset($product_list[$value->product_id]) ? $product_list[$value->product_id]['name'] : '' ?><?= $value->sub_product_id != 0 ? ' / ' . $sub_product_list[$value->sub_product_id]['identity'][$product_list[$value->product_id]['identity']]['name'] : '' ?>
                                                </td>
                                                <td>
                                                    <a class="fancyframe"
                                                        href="<?= admin_url('User/display?id=' . $value->user_id) ?>">
                                                        <?= isset($value->user_id) ? $value->user_id : '' ?>
                                                    </a>
                                                </td>
                                                <td><?= $value->credit_level ?? '' ?></td>
                                                <?= $typeKey == 'personal' ? '<td>' . $value->school_name ?? '' . '</td>' : '' ?>
                                                <?= $typeKey == 'personal' ? '<td>' . $value->company ?? '' . '</td>' : '' ?>
                                                <?= $typeKey == 'personal' ? '<td>' . $value->diploma ?? '' . '</td>' : '' ?>
                                                <?= $typeKey == 'personal' ? 
                                                    '<td>' . (isset($value->school_department) ? ($value->school_department ?? '') : '') . '</td>' :
                                                    '' ?>
                                                <?= isset($value->amount) && $typeKey != 'judicialPersonFormBank' ? '<td>' . $value->amount . '</td>' : '' ?>
                                                <td><?= isset($value->loan_amount) && $value->loan_amount ? $value->loan_amount : '' ?>
                                                </td>
                                                <td>
                                                    <? echo isset($value->interest_rate) && $value->interest_rate != '' ? floatval($value->interest_rate) . '%' : '-' ?>
                                                </td>
                                                <td><?= isset($value->instalment) ? $instalment_list[$value->instalment] : '' ?></td>
                                                <td><?= isset($value->repayment) ? $repayment_type[$value->repayment] : '' ?></td>
                                                <td><?= isset($value->invested) && $typeKey != 'judicialPersonFormBank' ? $value->invested : ($status_list[$value->status]) ?>
                                                </td>
                                                <?= isset($value->launch_times) && $typeKey != 'judicialPersonFormBank' ? '<td>' . $value->launch_times . '</td>' : '' ?>
                                                <td>
                                                    <? echo date("Y-m-d H:i:s", isset($value->expire_time) && $typeKey != 'judicialPersonFormBank' ? $value->expire_time : $value->updated_at) ?>
                                                </td>
                                                <td>
                                                    <?= isset($value->bidding_date) ? date("Y-m-d H:i:s", $value->bidding_date) : '' ?>
                                                </td>
                                                <td><?= isset($value->promote_code) ? $value->promote_code : '' ?></td>
                                                <td><a class="btn btn-primary btn-info"
                                                        href="<? echo $typeKey != 'judicialPersonFormBank' ? admin_url('creditmanagement/waiting_bidding_report?type=person&target_id=' . $value->id . '') : admin_url('target/waiting_reinspection' . "?target_id=" . $value->id) ?>"
                                                        target="_blank">查看</a></td>
                                                <? if ($value->status == TARGET_WAITING_BIDDING) { ?>
                                                    <td><button class="btn btn-danger"
                                                            onclick="cancel(<?= isset($value->id) ? $value->id : "" ?>)">下架</button></td>
                                                <? } ?>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <script>$('#dataTables-tables<?= $typeKey ?>').dataTable(dataTableSet);</script>
            <!-- /.panel-body -->
        <? }
    } ?>
</div>
<!-- /#page-wrapper -->
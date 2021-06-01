<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">債權 - 已投標</h1>
        </div>
        <!-- /.col-lg-12 -->

    </div>
    <script type="text/javascript">
        function showChang() {
            var user_id = $('#user_id').val();
            var target_no = $('#target_no').val();
            var status = $('#status :selected').val();
            var dateRange = '&sdate=' + $('#sdate').val() + '&edate=' + $('#edate').val();
            top.location = './bidding?status=' + status + '&user_id=' + user_id + '&target_no=' + target_no + dateRange;

        }

        function checked_all() {
            $('.investment').prop("checked", true);
            check_checked();
        }

        function check_checked() {
            var ids = "", ctr = $('#amortization_export,#assets_export').parent().find('.btn');
            $('.investment:checked').each(function () {

                if (ids == "") {
                    ids += this.value;
                } else {
                    ids += ',' + this.value;
                }
            });
            if (ids != "") {
                $('#assets_export').val(ids);
                $('#amortization_export').val(ids);
                ctr.prop('disabled', false);
            } else {
                ctr.prop('disabled', true);
            }
        }
    </script>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <table>
                        <tr>
                            <td>投資人ID：</td>
                            <td><input type="text"
                                       value="<?= isset($_GET['user_id']) && $_GET['user_id'] != "" ? $_GET['user_id'] : "" ?>"
                                       id="user_id"/></td>
                            <td>案號：</td>
                            <td><input type="text"
                                       value="<?= isset($_GET['target_no']) && $_GET['target_no'] != "" ? $_GET['target_no'] : "" ?>"
                                       id="target_no"/></td>
                            <td></td>
                        </tr>
                        <tr style="vertical-align: baseline;">
                            <td style="padding: 14px 0;">從：</td>
                            <td><input type="text"
                                       value="<?= isset($_GET['sdate']) && $_GET['sdate'] != '' ? $_GET['sdate'] : '' ?>"
                                       id="sdate" data-toggle="datepicker" placeholder="不指定區間"/></td>
                            <td style="">到：</td>
                            <td><input type="text"
                                       value="<?= isset($_GET['edate']) && $_GET['edate'] != '' ? $_GET['edate'] : '' ?>"
                                       id="edate" data-toggle="datepicker" style="width: 182px;" placeholder="不指定區間"/>
                            </td>
                        </tr>
                        <tr>
                            <td>狀態：</td>
                            <td>
                                <select id="status">
                                    <option value="all">全部</option>
                                    <? foreach ($investment_status_list as $key => $value) {
                                        if (in_array($key, $show_status)) {
                                            ?>
                                            <option value="<?= $key ?>" <?= isset($_GET['status']) && $_GET['status'] != "" && intval($_GET['status']) == intval($key) ? "selected" : "" ?>><?= $value ?></option>
                                        <? }
                                    } ?>
                                </select>
                            </td>
                            <td></td>
                            <td>
                                <a href="javascript:showChang();" class="btn btn-default">查詢</a>
                            </td>
                            <td>
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
                                <th class="text-center">下標時間</th>
                                <th class="text-center">付款時間</th>
                                <th class="text-center">案號</th>
                                <th class="text-center">投資人ID</th>
                                <th class="text-center">投標金額</th>
                                <th class="text-center">得標金額</th>
                                <th class="text-center">案件總額</th>
                                <th class="text-center">智能投資</th>
                                <th class="text-center">凍結款項流水號</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (isset($list) && !empty($list)) {
                                $count = 0;
                                foreach ($list as $key => $value) {
                                    $count++;
                                    ?>
                                    <tr class="<?= $count % 2 == 0 ? "odd" : "even"; ?> text-center <?= isset($value->user_id) ? $value->user_id : "" ?>">
                                        <td><?= isset($value->created_at) ? $value->created_at : "" ?></td>
                                        <td><?= isset($value->updated_at) ? $value->updated_at : "" ?></td>
                                        <td><?= isset($value->target_no) ? $value->target_no : "" ?></td>
                                        <td><?= isset($value->user_id) ? $value->user_id: "" ?></td>
                                        <td><?= isset($value->amount) ? number_format(intval($value->amount)): "" ?></td>
                                        <td><?= isset($value->loan_amount) ? number_format(intval($value->loan_amount)): "" ?></td>
                                        <td><?= isset($value->total_amount) ? number_format(intval($value->total_amount)): "" ?></td>
                                        <td><?= isset($value->aiBidding) ? $value->aiBidding: "" ?></td>
                                        <td><?= isset($value->frozen_id) ? $value->frozen_id: "" ?></td>
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

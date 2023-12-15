<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">會員驗證碼查詢</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            function getUrlParam(param) {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get(param);
            }
            ["user_id", "phone"].forEach(function(item) {
                $("#" + item).val(getUrlParam(item));
            });

            $("#user_id").on("input", function() {
                var user_id = $(this).val();
                if (user_id != "") {
                    $("#phone").val("");
                }
            });
            $("#phone").on("input", function() {
                var phone = $(this).val();
                if (phone != "") {
                    $("#user_id").val("");
                }
            });

            ["#user_id", "#phone"].forEach(function(item) {
                $(item).keyup(function(event) {
                    if (event.keyCode == 13) {
                        $("#search").click();
                    }
                });
            });

            $("#search").click(function() {
                var user_id = $("#user_id").val();
                var phone = $("#phone").val();
                var url = "/admin/User/sms_verify";
                if (user_id != "") {
                    url += "?user_id=" + user_id;
                }
                if (phone != "") {
                    if (url.indexOf("?") > -1) {
                        url += "&phone=" + phone;
                    } else {
                        url += "?phone=" + phone;
                    }
                }
                location.href = url;
            });
        });
    </script>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <table>
                        <tr>
                            <td>會員ID：</td>
                            <td><input type="text" value="<?= isset($_GET['id']) && $_GET['id'] != "" ? $_GET['id'] : "" ?>" id="user_id" /></td>
                            <td>電話：</td>
                            <td><input type="text" value="<?= isset($_GET['phone']) && $_GET['phone'] != "" ? $_GET['phone'] : "" ?>" id="phone" /></td>
                            <td><button id="search">查詢</button></td>
                        </tr>
                    </table>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="display responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                    <th>類別</th>
                                    <th>驗證碼</th>
                                    <th>過期時間</th>
                                    <th>狀態</th>
                                    <th>發送時間</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($verification_code) && !empty($verification_code)) {
                                    $count = 0;
                                    $types = ['verify' => '驗證', 'register' => '註冊'];
                                    foreach ($verification_code as $key => $value) {
                                        $count++;
                                ?>
                                        <tr class="<?= $count % 2 == 0 ? "odd" : "even"; ?> list <?= isset($value->id) ? $value->id : "" ?>">
                                            <td><?= isset($types[$value->type]) ? $types[$value->type] : "" ?></td>
                                            <td><?= isset($value->code) ? $value->code : "" ?></td>
                                            <td><?= isset($value->expire_time) ? date('Y/m/d H:i:s', $value->expire_time) : "" ?></td>
                                            <td><?= isset($value->status) ? $value->status ? "已使用" : "未使用" : "" ?></td>
                                            <td><?= isset($value->created_at) ? date('Y/m/d H:i:s', $value->created_at) : "" ?></td>
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

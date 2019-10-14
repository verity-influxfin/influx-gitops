        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">鎖定帳號管理</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<script type="text/javascript">
                var block_status_list = <?php echo json_encode($block_status_list); ?>;

                $(document).ready(function() {
                    var $loadingImage = $('#loadingImage').hide();
                    $(document).ajaxStart(function() {
                        $loadingImage.show();
                    }).ajaxStop(function() {
                        $loadingImage.hide();
                    });

                    $("#searchBtn").click(function(){
                        $("#searchBtn").attr("disabled", true);
                        var user_id = $('#user_id').val();
                        var phone 	= $('#phone').val();
                        var name 	= $('#name').val();

                        $("#users").find("tr:gt(0)").remove();

                        $.ajax({
                            type: "GET",
                            url: "/admin/User?id=" + user_id + "&phone=" + phone + "&name=" + name,
                            success: function(response) {
                                $("#searchBtn").removeAttr("disabled");
                                if (response.status.code != 200) {
                                    return;
                                }

                                $.each(response.response.users, function(i, user) {
                                    var block_status_text = typeof block_status_list[user.block_status] === 'undefined' ? "未定義" : block_status_list[user.block_status];
                                    var borrowing_account_status_text = user.status ? "正常":"未申請";
                                    var lending_account_status_text = user.investor_status ? "正常":"未申請";
                                    var block_status_text = user.block_status == 0 ? "封鎖" : ""

                                    var trTag = i % 2 == 0 ? '<tr class="even list">' : '<tr class="odd list">';
                                    $(trTag).append(
                                        $('<td>').text(user.id),
                                        $('<td>').text(user.name),
                                        $('<td>').text(user.phone),
                                        $('<td>').text(user.sex),
                                        $('<td>').text(user.email),
                                        $('<td>').text(borrowing_account_status_text),
                                        $('<td>').text(lending_account_status_text),
                                        $('<td>').text(block_status_text),
                                        $('<button class="btn btn-default">').text(block_status_text)
                                    ).appendTo('#users');
                                });
                            }
                        });
                    });
                })
			</script>
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header">搜尋</h3>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <table>
                                <tr>
                                    <td>會員ID：</td>
                                    <td><input type="text" value="<?=isset($_GET['id'])&&$_GET['id']!=""?$_GET['id']:""?>" id="user_id" /></td>
                                    <td>電話：</td>
                                    <td><input type="text" value="<?=isset($_GET['phone'])&&$_GET['phone']!=""?$_GET['phone']:""?>" id="phone" /></td>
                                    <td>姓名：</td>
                                    <td><input type="text" value="<?=isset($_GET['name'])&&$_GET['name']!=""?$_GET['name']:""?>" id="name" /></td>
                                    <td><button id="searchBtn" class="btn btn-default">查詢</div></td>
                                </tr>
                            </table>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="users" class="display responsive nowrap" width="100%">
                                    <thead>
                                        <tr class="odd list">
                                            <th width="10%">會員 ID</th>
                                            <th width="10%">姓名</th>
                                            <th width="15%">電話</th>
                                            <th width="10%">性別</th>
                                            <th width="20%">Email</th>
                                            <th>借款端帳號</th>
                                            <th>出借帳號</th>
                                            <th>封鎖狀態</th>
                                            <th width="10%">修改</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                                <div width="100%" style="text-align: center;">
                                    <div id='loadingImage'>
                                        <img src="http://i.stack.imgur.com/FhHRx.gif" />
                                    </div>
                                </div>
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
                    <h3 class="page-header">停權列表</h3>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="display responsive nowrap" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="10%">會員 ID</th>
                                            <th width="10%">姓名</th>
                                            <th width="15%">電話</th>
                                            <th width="10%">性別</th>
                                            <th width="20%">Email</th>
                                            <th>借款端帳號</th>
                                            <th>出借帳號</th>
                                            <th>封鎖狀態</th>
                                            <th width="10%">解除</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
										if(isset($list) && !empty($list)){
											$count = 0;
											foreach($list as $key => $value){
												$count++;
									?>
                                        <tr class="<?=$count%2==0?"odd":"even"; ?> list <?=isset($value->id)?$value->id:"" ?>">
                                            <td><?=isset($value->id)?$value->id:"" ?></td>
                                            <td><?=isset($value->name)?$value->name:"" ?></td>
                                            <td><?=isset($value->phone)?$value->phone:"" ?></td>
                                            <td><?=isset($value->sex)?$value->sex:"" ?></td>
                                            <td><?=isset($value->email)?$value->email:"" ?></td>
											<td><?=isset($value->status)&&$value->status?"正常":"未申請" ?></td>
											<td><?=isset($value->investor_status)&&$value->investor_status?"正常":"未申請" ?></td>
											<td><?=isset($value->block_status)&&$value->block_status?$block_status_list[$value->block_status]:null ?></td>
											<td><a href="<?=admin_url('user/block_users')."?id=".$value->id."&status=unblocked" ?>" class="btn btn-default">解除鎖定</a></td>
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

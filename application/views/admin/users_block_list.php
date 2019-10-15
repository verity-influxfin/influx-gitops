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
                    var $searchLoadingImage = $('#searchLoadingImage').hide();
                    var $blockLoadingImage = $('#blockLoadingImage').hide();

                    function appendUserDetail(tableId, i, user) {
                        var block_status_text = typeof block_status_list[user.block_status] === 'undefined' ? "未定義" : block_status_list[user.block_status];
                        var borrowing_account_status_text = user.status ? "正常":"未申請";
                        var lending_account_status_text = user.investor_status ? "正常":"未申請";
                        var should_block_disabled = user.block_status != 0 ? "disabled" : "";
                        var button = tableId == "#users"
                            ? $('<button class="btn btn-default blockBtn" ' + should_block_disabled + ' />').text("封鎖")
                            : $('<button class="btn btn-default unblockBtn" />').text("解除鎖定")

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
                            button
                        ).appendTo(tableId);
                    }

                    function hideBlockForm() {
                        $('#blockFormDiv').hide();
                    }

                    function showBlockForm() {
                        $('#blockFormDiv').show();
                        $('#blockForm').show();
                    }

                    function changeBlockStatus(user_id, status) {
                        var tableRow = $("#users").find("td").filter(function() {
                            return $(this).text() == user_id;
                        }).closest("tr");

                        if (tableRow) {
                            blockStatusColIndex = tableRow.find('td').length-1;
                            if (status == 'unblocked') {
                                blockStatusText = "正常";
                                blockBtnDisabled = false;
                            } else {
                                blockStatusText = "人工停權";
                                blockBtnDisabled = true;
                            }
                            tableRow.find('td').eq(blockStatusColIndex).html(blockStatusText);
                            tableRow.find('button').attr("disabled", blockBtnDisabled);
                        }
                    }

                    $("#searchBtn").click(function(){
                        hideBlockForm();
                        $("#searchBtn").attr("disabled", true);
                        var user_id = $('#user_id').val();
                        var phone 	= $('#phone').val();
                        var name 	= $('#name').val();

                        $("#users").find("tr:gt(0)").remove();

                        $.ajax({
                            type: "GET",
                            url: "/admin/User?id=" + user_id + "&phone=" + phone + "&name=" + name,
                            beforeSend: function () {
                                $('#searchLoadingImage').show();
                            },
                            complete: function () {
                                $('#searchLoadingImage').hide();
                            },
                            success: function(response) {
                                $("#searchBtn").removeAttr("disabled");
                                if (response.status.code != 200) {
                                    return;
                                }

                                $.each(response.response.users, function(i, user) {
                                    appendUserDetail('#users', i, user)
                                });
                            }
                        });
                    });

                    var clickedBlockButton;
                    $('#blockFormDiv').hide();
                    $('#users').on('click', '.blockBtn', function() {
                        showBlockForm();

                        var row = $(this).closest("tr").find('td');
                        var user_id = row.eq(0).html();
                        var user_name = row.eq(1).html();

                        clickedBlockButton = $(this).closest("tr").find('button');

                        $('input[name="id"]').val(user_id);
                        $('input[name="name"]').val(user_name);

                        $('html,body').animate(
                            { scrollTop: $("#blockFormDiv").offset().top },
                            'slow'
                        );
                    });

                    $('#blockedUsers').on('click', '.unblockBtn', function() {
                        var rowTd = $(this).closest("tr").find('td');
                        var rowButton = $(this).closest("tr").find("button");
                        var user_id = rowTd.eq(0).html();
                        var data = {
                            'id' : user_id,
                            'status' : 'unblocked'
                        };

                        $.ajax({
                           type: "POST",
                           url: 'block_users',
                           data: data, // serializes the form's elements.
                           success: function(response) {
                               if (response.status.code != 200) {
                                   alert('解鎖失敗，請再試一次');
                                   return;
                               }
                               alert('已經成功解除該用戶的封鎖。');
                               rowTd.remove();
                               rowButton.remove();

                               changeBlockStatus(user_id, "unblocked");
                           },
                           error: function() {
                               $('#blockForm').show();
                           }
                         });
                    });

                    // this is the id of the form
                    $("#blockForm").submit(function(e) {
                        e.preventDefault();

                        var form = $(this);
                        var url = form.attr('action');

                        $.ajax({
                           type: "POST",
                           url: url,
                           data: form.serialize(), // serializes the form's elements.
                           beforeSend: function () {
                               $blockLoadingImage.show();
                               $('#blockForm').hide();
                           },
                           complete: function () {
                               $blockLoadingImage.hide();
                           },
                           success: function(response) {
                               clickedBlockButton.attr("disabled", true);
                               if (response.status.code != 200) {
                                   alert('封鎖失敗，請再試一次');
                                   return;
                               }
                               alert('已經成功封鎖該用戶。');

                               appendUserDetail('#blockedUsers', 10, response.response.user);
                               changeBlockStatus($('#blockForm').find('input[name="id"]').val(), "blocked");
                           },
                           error: function() {
                               $('#blockForm').show();
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
                                    <div id='searchLoadingImage'>
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

            <div id="blockFormDiv" align="center">
                <div style="width: 500px; padding: 15; border: 1px; text-align: left">
                    <form id="blockForm" action="block_users">
                        <a>封鎖表單：</a><br>
                        會員Id:
                        <input type="text" style="border: 0;" name="id" readonly><br>
                        會員名稱:
                        <input type="text" style="border: 0;" name="name" readonly><br>
                        封鎖狀態:
                        <select name="status">
                          <option value="blocked">封鎖</option>
                          <option value="temp_blocked">暫時封鎖</option>
                          <option value="system_blocked">系統封鎖</option>
                        </select><br>
                        封鎖原因:
                        <input type="text" name="reason" value=""><br><br>
                        <input class="btn btn-default" type="submit" value="Submit">
                    </form>
                    <div width="100%" style="text-align: center;">
                        <div id='blockLoadingImage'>
                            <img src="http://i.stack.imgur.com/FhHRx.gif" />
                        </div>
                    </div>
                </div>
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
                                <table id="blockedUsers" class="display responsive nowrap" width="100%">
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
											<td><button class="btn btn-default unblockBtn">解除鎖定</button></td>
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

<script type="text/javascript" src="<?php echo base_url();?>assets/admin/js/common/datetime.js" ></script>
<script src="<?=base_url()?>assets/admin/js/mapping/user/user.js"></script>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">用戶</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <h3 class="page-header">搜尋</h3>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive center-text">
                        <table id="users" class=" table table-bordered display responsive nowrap" width="100%">
                            <thead>
                                <tr class="odd list">
                                    <th class="center-text" width="30%">會員 ID</th>
                                    <th class="center-text" width="20%">註冊日期</th>
                                    <th class="center-text" width="50%">邀請碼</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <button id="load-more" class="btn btn-default">載入更多</button>
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

<script>

    $(document).ready(function() {
        var urlString = window.location.href;
        var url = new URL(urlString);
        var type = url.searchParams.get("type");
        var category = url.searchParams.get("category");
        var partnerId = url.searchParams.get("partner_id");
        var startAt = url.searchParams.get("sdate");
        var endAt = url.searchParams.get("edate");
        
        $("#load-more").hide();

        var users = [];
        var usersIndex = 1;
        var page = 1;
        var limit = 20;
        var fetchUserAjaxLock = false;
        
        fillFakeUsers();
        fetchUsers();

        function fillUsers(currentUsers) {    
            for (var i = 0; i < currentUsers.length; i++) {
                $("<tr>").append(
                    $('<td class="center-text">').append(users.length),
                    $('<td class="center-text">').append(currentUsers[i].getRegisteredAtAsDate()),
                    $('<td class="center-text">').append(currentUsers[i].promoteCode),
                ).appendTo("#users");
            }
		}
        
        function fillFakeUsers(show = true) {
            if (!show) {
                var lastIndex = users.length;
                $("#users tr:gt(" + lastIndex + ")").remove();
                return;
            }

            idPTag = '<p class="form-control-static" style="width: 60px;"></p>';
            createdAtPTag = '<p class="form-control-static" style="width: 150px;"></p>';
            pTag = '<p class="form-control-static"></p>';
            for (var i = 0; i < 20; i++) {
                $("<tr>").append(
                    $('<td class="fake-fields short center-text">').append(idPTag),
                    $('<td class="fake-fields short center-text">').append(createdAtPTag),
                    $('<td class="fake-fields center-text">').append(pTag)
                ).appendTo("#users");
            }
        }

        $('#load-more').on('click', function() {
            if (fetchUserAjaxLock) {
                return;
            }
            fillFakeUsers();
            fetchUsers();
        });
        
        function fetchUsers() {
            var query = {
                'sdate' : startAt,
                'edate' : endAt,
                'type' : type,
                'category' : category,
                'offset' : page
            };
            
            if (category == 'partner' && partnerId) {
                query['partner_id'] = partnerId;
            }

            var queryString = $.param(query);

            $.ajax({
                type: "GET",
                url: "/admin/sales/accounts?" + queryString,
                beforeSend: function () {
                    fetchUserAjaxLock = true;
                },
                complete: function () {
                    fetchUserAjaxLock = false;
                },
                success: function (response) {
                    fillFakeUsers(false);
                    if (response.status.code != 200 && response.status.code != 204) {
                        return;
                    } else if (response.status.code == 204) {
                        if (page == 1) {
                            alert('資料不存在');
                            window.close();
                        }
                        $("#load-more").hide();
                        return;
    				}
                    
                    var currentUsers = response.response.users
                    var userObjects = [];
                    for (var i = 0; i < currentUsers.length; i++) {
                        var user = new User(currentUsers[i]);
                        users.push(user)
                        userObjects.push(user)
                    }

                    fillUsers(userObjects);
                    if (currentUsers.length < 20) {
                        $("#load-more").hide();
                    } else {
                        $("#load-more").show();
                    }

                    page++;
                },
    			error: function(error) {
                    alert('資料載入失敗。請重新整理。');
    			}
            });
        }
    });
</script>

<style>
    .center-text {
        text-align: center;
    }

    @keyframes placeHolderShimmer{
        0% {
            background: #ececec;
        }

        30% {
            background: #F7F7F7;
        }

        50% {
            background: #ececec;
        }

        80% {
            background: #F7F7F7;
        }

        100% {
            background: #ececec;
        }
    }

    .fake-fields p {
        animation-duration: 3.25s;
        animation-fill-mode: forwards;
        animation-iteration-count: infinite;
        animation-name: placeHolderShimmer;
        animation-timing-function: linear;
        background: darkgray;
        background: linear-gradient(to right, #eeeeee 10%, #dddddd 18%, #eeeeee 33%);
        background-size: 800px 104px;
        height: 30px;
        position: relative;
        border-radius: 25px;
    }

    .short p{
        margin:0 auto;
        width:50%;
    }

</style>

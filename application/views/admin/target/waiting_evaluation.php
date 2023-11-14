<script type="text/javascript" src="<?php echo base_url();?>assets/admin/js/common/datetime.js" ></script>
<script src="<?=base_url()?>assets/admin/js/mapping/user/user.js"></script>
<script src="<?=base_url()?>assets/admin/js/mapping/loan/target.js"></script>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">借款 - 二審</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
    <div class="category-tab">
        <button class="category-tab-item active" id="tab1" onclick="location.search = 'tab=individual'">個金</button>
        <button class="category-tab-item" id="tab2" onclick="location.search = 'tab=enterprise'">企金</button>
<!--        <button class="category-tab-item" id="tab3" onclick="location.search = 'tab=home_loan'">房產消費貸</button>-->
    </div>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table id="dataTables-tables" class="table table-bordered width="100%">
							<thead>
							<tr>
								<th width="30%">案號</th>
								<th width="20%">產品</th>
								<th width="20%">會員 ID</th>
								<th width="30%">二審</th>
							</tr>
							</thead>
							<tbody>
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
    $(document).ready(function() {
        fillFakeTargets();
        $.ajax({
            type: "GET",
            url: "/admin/Target/waiting_evaluation",
            data: {
                tab: url.searchParams.get('tab')
            },
            beforeSend: function () {
            },
            complete: function () {
            },
            success: function (response) {
                fillFakeTargets(false);
                if (response.status.code != 200 && response.status.code != 204) {
                    return;
                }
                if (response.status.code == 204) {
                    alert("無資料");
                    return;
				}

                var targets = [];
                var users = [];
                var targetsJson = response.response.targets;
                var usersJson = response.response.users;
                for (var i = 0; i < targetsJson.length; i++) {
                    targets.push(new Target(targetsJson[i]));
                    users.push(new User(usersJson[i]));
				}

                fillTargetsInfo(targets, users);
            }
        });

        function fillFakeTargets(show = true) {
            if (!show) {
                $("#dataTables-tables tr:gt(0)").remove();
                return;
            }

            pTag = '<p class="form-control-static"></p>';
            for (var i = 0; i < 3; i++) {
                $("<tr>").append(
                    $('<td class="fake-fields center-text">').append(pTag),
                    $('<td class="fake-fields center-text">').append(pTag),
                    $('<td class="fake-fields center-text">').append(pTag),
                    $('<td class="fake-fields center-text">').append(pTag)
                ).appendTo("#dataTables-tables");
			}
		}

        function fillTargetsInfo(targets, users) {
            let validation_url = url.searchParams.get('tab') === 'enterprise' ? 'waiting_reinspection?target_id=' : 'final_validations_detail?id=';
			for (var i = 0; i < targets.length; i++) {
				let target = targets[i];
				let user = users[i];

				let detailButton = '<a href="/admin/target/'+validation_url + target.id + '&user_id=' + user.id +'" target="_blank"><button class="btn btn-danger">審核</button></a>';
				$("<tr>").append(
                    getCenterTextCell(target.number),
					getCenterTextCell(target.product.name),
					getCenterTextCell(user.id),
					getCenterTextCell(detailButton)
				).appendTo("#dataTables-tables");
			}
		}

        function getCenterTextCell(value) {
            //console.log('<td class="center-text">' + value + '</td>');
            return '<td class="center-text">' + value + '</td>';
        }
    });
</script>

<style>
	@keyframes placeHolderShimmer{
		0%{
			background-position: -468px 0
		}
		100%{
			background-position: 468px 0
		}
	}

	.fake-fields p {
		animation-duration: 1.25s;
		animation-fill-mode: forwards;
		animation-iteration-count: infinite;
		animation-name: placeHolderShimmer;
		animation-timing-function: linear;
		background: darkgray;
		background: linear-gradient(to right, #eeeeee 10%, #dddddd 18%, #eeeeee 33%);
		background-size: 800px 104px;
		height: 30px;
		position: relative;
	}
</style>

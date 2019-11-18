<script src="<?=base_url()?>assets/admin/js/mapping/user/jointcredit.js"></script>
<script src="<?=base_url()?>assets/admin/js/mapping/user/user.js"></script>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">聯徵</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">

				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
							<div id="container">
								<div class="col-lg-6">
									<p>會員:</p>
									<a id="user-id-link"><p id="user-id"></p></a>
								</div>
								<div>
									<p>主要狀態:</p>
									<p id="overal-status"></p>
								</div>
							</div>
							</br>
							<table id="joint-credits" class="table table-bordered">
								<tr class="odd list">
									<th class="center-text table-field" width="30%">內容</th>
									<th class="center-text table-field" width="10%">狀態</th>
									<th class="center-text table-field" width="30%">訊息</th>
									<th class="center-text table-field" width="30%">退件訊息</th>
								</tr>
							</table>
						</div>
					</div>
					<!-- /.row (nested) -->
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->

<script>
    $(document).ready(function() {
        var urlString = window.location.href;
        var url = new URL(urlString);
        var certificationId = url.searchParams.get("id");

		$.ajax({
            type: "GET",
            url: "/admin/Certification/joint_credits?id=" + certificationId,
            beforeSend: function () {

            },
            complete: function () {

            },
            success: function (response) {
                if (response.status.code != 200 && response.status.code != 404) {
                    return;
                } else if (response.status.code == 404) {
                    alert('資料不存在');
                    window.close();
                    return;
				}

				var jointCredits = new JointCredit(response.response.joint_credits);
				fillJointCredits(jointCredits);

				var user = new User(response.response.user);
				fillUser(user);
            },
			error: function(error) {
                alert('資料載入失敗。請重新整理。');
			}
        });

		function fillUser(user) {
			$("#user-id").text(user.id);
			$("#user-id-link").attr("href", "/admin/user/edit?id=" + user.id);
			$("#user-id-link").attr("target", "_blank");
		}

		function fillJointCredits(jointCredits) {
			$("#overal-status").text(jointCredits.status);
			for (var i = 0; i < jointCredits.messages.length; i++) {
				var message = jointCredits.messages[i];

				var splitedMessage = "";
				for (var j = 0; j < message.message.length; j++) {
					splitedMessage += message.message[j] + "<br>";
				}

				$("<tr>").append(
					$('<td class="center-text table-field">').append(message.stage),
                    $('<td class="center-text">').append(message.status),
                    $('<td class="center-text">').append(splitedMessage),
					$('<td class="center-text">').append(message.rejected_message),
				).appendTo("#joint-credits");
			}
		}
    });

</script>
<style>
	#container p { display: inline }

	.table-field {
		background-color: #f5f5f5;
		table-layout: fixed;
	}

	.center-text {
		text-align: center;
	}
</style>

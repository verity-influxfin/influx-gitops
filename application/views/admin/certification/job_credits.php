<script src="<?= base_url() ?>assets/admin/js/mapping/user/jobcredit.js"></script>
<script src="<?= base_url() ?>assets/admin/js/mapping/user/user.js"></script>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">工作認證</h1>
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
								<div class="col-lg-4">
									<p>會員:</p>
									<a id="user-id-link">
										<p id="user-id"></p>
									</a>
								</div>
								<div class="col-lg-4">
									<p>主要狀態:</p>
									<p id="overal-status" style="color:red;"></p>
								</div>
								<div class="col-lg-4">
									<a id="job-credit-file">勞保檔案</a>
								</div>
							</div>
							</br>
							<table id="job-credits" class="table table-bordered">
								<tr class="odd list">
									<th class="center-text table-field" width="30%">內容</th>
									<th class="center-text table-field" width="10%">狀態</th>
									<th class="center-text table-field" width="30%">訊息</th>
									<th class="center-text table-field" width="30%">退件訊息</th>
								</tr>
							</table>
							<div class="center-text">
								<form id="verification-change" class="align-form container" action="/admin/Certification/user_certification_edit">
									<span>審核: </span>
									<select id="verification"></select>
									<span>專業證書加分 (最高6級)</span>
									<select id="license_status"></select>
									<span>專家調整 (最高5級)</span>
									<select id="pro_level"></select>
									<button type="submit" class="btn btn-primary">送出</button>
							</div>
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
			url: "/admin/Certification/job_credits?id=" + certificationId,
			beforeSend: function() {

			},
			complete: function() {

			},
			success: function(response) {
				if (response.status.code != 200 && response.status.code != 204) {
					return;
				} else if (response.status.code == 204) {
					alert('資料不存在');
					window.close();
					return;
				}
				fillDropDownMenu(response.response.statuses);
				fillLicenseStatusMenu();
				fillProLevelMenu();
				var jobCredits = new JobCredit(response.response.job_credits);
				fillJobCredits(jobCredits);
				fillJobCreditFile(jobCredits);
				var user = new User(response.response.user);
				fillUser(user);
			},
			error: function(error) {
				alert('資料載入失敗。請重新整理。');
			}
		});
	
		$("#verification-change").submit(function(e) {
			e.preventDefault();

			var isConfirmed = confirm("確認是否要通過審核？");
			if (!isConfirmed) {
				return false;
			}
			
			var form = $(this);
			var url = form.attr('action');
			var status = $("#verification").val();
			var license_status = $("#license_status").val();
			var pro_level = $("#pro_level").val();
			var data = {
				'id': certificationId,
				'status': status,
				'license_status':license_status,
				'pro_level':pro_level
			}
			

			$.ajax({
				type: "POST",
				url: url,
				data: data,
				success: function(response) {
					window.close();
				},
				error: function() {
					alert('審核失敗，請重整頁面後，再試一次。');
				}
			});
		});

		function fillJobCreditFile(jobCredit) {
			if (!jobCredit.file) return;
			$("#job-credit-file").attr("href", jobCredit.file);
			$("#job-credit-file").attr("target", "_blank");
		}

		function fillUser(user) {
			$("#user-id").text(user.id);
			$("#user-id-link").attr("href", "/admin/user/edit?id=" + user.id);
			$("#user-id-link").attr("target", "_blank");
		}

		function fillDropDownMenu(statuses) {
			for (var i = 0; i < statuses.length; i++) {
				var option = new Option(statuses[i], i);
				$("#verification").append(option);
			}
		}

		function fillLicenseStatusMenu() {
			for (var i = 0; i < 7; i++) {
				var option = new Option(i, i);
				$("#license_status").append(option);
			}
		}

		function fillProLevelMenu(){
			for (var i = 0; i < 6; i++) {
				var option = new Option(i, i);
				$("#pro_level").append(option);
			}
		}

		function fillJobCredits(jobCredits) {
			$("#overal-status").text(jobCredits.status);
			if (jobCredits.status == "驗證成功") {
				$("#verification").val(1).change();
			} else if (jobCredits.status == "退件") {
				$("#verification").val(2).change();
			} else if (jobCredits.status == "待人工驗證") {
				$("#verification").val(3).change();
			}

			$("#license_status").val(jobCredits.licenseStatus).change();
			$("#pro_level").val(jobCredits.proLevel).change();
			for (var i = 0; i < jobCredits.messages.length; i++) {
				var message = jobCredits.messages[i];

				var splitedMessage = "";
				for (var j = 0; j < message.message.length; j++) {
					splitedMessage += message.message[j] + "<br>";
				}

				$("<tr>").append(
					$('<td class="center-text table-field">').append(message.stage),
					$('<td class="center-text">').append(message.status),
					$('<td class="center-text">').append(splitedMessage),
					$('<td class="center-text">').append(message.rejected_message),
				).appendTo("#job-credits");
			}
		}
		


	});
</script>
<style>
	#container p {
		display: inline
	}

	.table-field {
		background-color: #f5f5f5;
		table-layout: fixed;
	}

	.align-form {
		display: inline;
	}

	.center-text {
		text-align: center;
	}
</style>

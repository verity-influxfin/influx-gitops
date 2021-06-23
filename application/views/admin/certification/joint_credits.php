<script src="<?= base_url() ?>assets/admin/js/mapping/user/jointcredit.js"></script>
<script src="<?= base_url() ?>assets/admin/js/mapping/user/user.js"></script>

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
									<a id="joint-credit-file">聯徵檔案</a>
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
							<div class="center-text">
								<form id="verification-change" class="align-form container" action="/admin/Certification/user_certification_edit">
									<span>審核: </span>
									<select id="verification"></select>
									<?
									if ($status == 1) {
										?>
                                        <?= isset($printDate) ? '<td>聯徵調閱日期：' . $printDate . '</td>' : '' ?>
										<td>被查詢次數：<?= isset($times) ? $times : ""; ?></td>
										<td>信用卡使用率%：<?= isset($credit_rate) ? $credit_rate : ""; ?></td>
										<td>信用記錄幾個月：<?= isset($months) ? $months : ""; ?></td>
                                        <input type="hidden" name="fail" placeholder="退件原因" />
                                        <button type="submit" class="btn btn-primary">送出</button>
									<?
									} else {
										?>
                                        <input type="text"
                                               value=""
                                               name="printDate" data-toggle="datepicker" style="width: 182px;" placeholder="<?= isset($printDate) ? $printDate : '聯徵調閱日期' ?>"/>
                                        <input type="number" name="times" placeholder="被查詢次數" value=<?= isset($times) ? $times : ""; ?> />
                                        <input type="number" step="0.01" name="credit_rate" placeholder="信用卡使用率%" value=<?= isset($credit_rate) ? $credit_rate : ""; ?> />
                                        <input type="number" name="months" placeholder="信用記錄幾個月" value=<?= isset($months) ? $months : ""; ?> />
                                        <input type="text" name="fail" placeholder="備註" />
                                        <button type="submit" class="btn btn-primary">送出</button>
									<?
									}
									?>
								</form>
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
			url: "/admin/Certification/joint_credits?id=" + certificationId,
			beforeSend: function() {

			},
			complete: function() {

			},
			success: function(response) {
				if (response.status.code != 200 && response.status.code != 404) {
					return;
				} else if (response.status.code == 404) {
					alert('資料不存在');
					window.close();
					return;
				}

				fillDropDownMenu(response.response.statuses);

				var jointCredits = new JointCredit(response.response.joint_credits);
				fillJointCredits(jointCredits);
				fillJointCreditFile(jointCredits)

				var user = new User(response.response.user);
				fillUser(user);
			},
			error: function(error) {
				alert('資料載入失敗。請重新整理。');
			}
		});

		$("#verification-change").submit(function(e) {
			e.preventDefault();

			var isConfirmed = confirm("確認是否要送出審核？");
			if (!isConfirmed) {
				return false;
			}

			var form = $(this);
			var url = form.attr('action');
			var status = $("#verification").val();
			var failureReason = form.find('input[name="fail"]').val();
			var times = form.find('input[name="times"]').val();
			var rate = form.find('input[name="credit_rate"]').val();
			var months = form.find('input[name="months"]').val();
			var printDate = form.find('input[name="printDate"]').val();


			var data = {
				'id': certificationId,
				'status': status
			}
			if (failureReason) {
				data.fail = failureReason;
			}
			if (times) {
				data.times = times;
			}
			if (months) {
				data.months = months;
			}
			if (rate) {
				data.credit_rate = rate;
			}
			if (printDate) {
				data.printDate = printDate;
			}

			$.ajax({
				type: "POST",
				url: url,
				data: data,
				success: function(response) {
                    location.reload();
				},
				error: function() {
					alert('審核失敗，請重整頁面後，再試一次。');
				}
			});
		});

		function fillJointCreditFile(jointCredit) {
			if (!jointCredit.file) return;
			$("#joint-credit-file").attr("href", jointCredit.file);
			$("#joint-credit-file").attr("target", "_blank");
		}

		function fillUser(user) {
			$("#user-id").text(user.id);
			$("#user-id-link").attr("href", "/admin/user/display?id=" + user.id);
			$("#user-id-link").attr("target", "_blank");
		}

		function fillDropDownMenu(statuses) {
			for (var i = 0; i < statuses.length; i++) {
				var option = new Option(statuses[i], i);
				$("#verification").append(option);
			}
		}

		function fillJointCredits(jointCredits) {
			$("#overal-status").text(jointCredits.status);
			if (jointCredits.status == "驗證成功") {
				$("#verification").val(1).change();
			} else if (jointCredits.status == "退件") {
				$("#verification").val(2).change();
			} else if (jointCredits.status == "待人工驗證") {
				$("#verification").val(3).change();
			}
			for (var i = 0; i < jointCredits.messages.length; i++) {
				var message = jointCredits.messages[i];
				var splitedMessage = "";

                if(jointCredits.messages[i].status == '驗證成功' && jointCredits.messages[i].stage == '「調閱日期」應為最近申貸日起算一個月內'){
                    $('input[name="printDate"]').attr('disabled',true);
                    $('input[name="printDate"]').hide();
                }

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

		$("#verification").change(function() {
			var status = $(this).val();
			if (status == 2) {
				$('input[name="fail"]').prop('type', 'text');
				$('input[name="times"]').prop('type', 'hidden');
				$('input[name="times"]').val('');
				$('input[name="credit_rate"]').prop('type', 'hidden');
				$('input[name="credit_rate"]').val('');
				$('input[name="months"]').prop('type', 'hidden');
				$('input[name="months"]').val('');
			} else if (status == 1) {
				$('input[name="times"]').prop('type', 'text');
				$('input[name="credit_rate"]').prop('type', 'text');
				$('input[name="months"]').prop('type', 'text');
			} else {

			}
		});
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

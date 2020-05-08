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
							</div><br/>
                            <div id="container">
								<table  id="images" class="table table-bordered">
                                    <th class="center-text table-field" width="30%">項目</th>
									<th class="center-text table-field" width="70%">照片</th>
                                </table>
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
							<div>
                                <div>統一編號: <span id="tax_id"></span></div>
                                <div>公司: <span id="company"></span></div>
                                <div>公司類型: <span id="industry"></span></div>
                                <div>工作職稱: <span id="job_title"></span></div>
                                <div>企業規模: <span id="employee"></span></div>
                                <div>職位: <span id="position"></span></div>
                                <div>職務性質: <span id="type"></span></div>
                                <div>畢業以來的工作期間: <span id="seniority"></span></div>
                                <div>此公司工作期間: <span id="job_seniority"></span></div>
                                <form id="salary-change" class="align-form container" action="/admin/Certification/user_certification_edit">
									<span>修改自填月薪: </span>
									<input id="salary"></input>
									<button type="submit" class="btn btn-primary">修改自填月薪</button>
								</form>
							</div>
							<div>
								<form id="verification-change" class="align-form container" action="/admin/Certification/user_certification_edit">
                                    <span>專業證書加分 (最高6級)</span>
                                    <select id="license_status"></select>

                                    <span>專家調整 (最高5級)</span>
                                    <select id="pro_level"></select>
                                    <br/>
                                    <br/>
                                    <span>審核: </span>
                                    <select id="verification"></select>
                                    <select id="failMsg" style="display: none;"></select>
                                    <input id="fail" type="text" value="" style="background-color: white !important;width: 100px;display: none;height: 20px;" placeholder="輸入其它說明">
                                    <br/>
                                    <br/>
                                    <button type="submit" class="btn btn-primary">送出</button>
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
                fillFailMsg(response.response.fail_msg);
				fillLicenseStatusMenu();
                fillProLevelMenu();
                var jobCredits = new JobCredit(response.response.job_credits);
                fillJobCredits(jobCredits);
                fillJobCreditFile(jobCredits);
                var user = new User(response.response.user);
                fillUser(user);
                fillExtraImages(jobCredits);
                response.response.job_credits.status == 'failure' ? $('#failMsg').hide() : '';
            },
			error: function(error) {
				alert('資料載入失敗。請重新整理。');
			}
		});

		$("#salary-change").submit(function(e) {
			e.preventDefault();
			var isConfirmed = confirm("確認是否要更新月薪資料？");
			if (!isConfirmed) {
				return false;
			}

			var form = $(this);
			var url = form.attr('action');
			var status = $("#verification").val();
			var salary=$("#salary").val();
			var data = {
				'id': certificationId,
				'salary':salary
			}

			$.ajax({
				type: "POST",
				url: url,
				data: data,
				success: function(response) {
					window.close();
				},
				error: function() {
					alert('送出失敗，請重整頁面後，再試一次。');
				}
			});
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
			var fail = $("#fail").val();
			var data = {
				'id': certificationId,
				'status': status,
				'license_status':license_status,
				'pro_level':pro_level,
				'fail':fail,
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

        $(document).off("change", "select#verification").on("change", "select#verification", function () {
            if ($(this).find(':selected').val() == 2) {
                $('#failMsg').show();
            } else {
                $('#failMsg').hide();
            }
        });

        $(document).off("change", "select#failMsg").on("change", "select#failMsg", function () {
            $(this).find(':selected').attr('value') == 'other' ? $('#fail').show() : $('#fail').hide();
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

        function fillFailMsg(failMsg) {
            for (var i = 0; i < failMsg.length; i++) {
                var option = new Option(failMsg[i], i);
                $("#failMsg").append(option);
            }
            $("#failMsg").append(new Option('其它', 'other'));
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
            $("#tax_id").text(jobCredits.tax_id);
            $("#company").text(jobCredits.company);
            $("#industry").text(jobCredits.industry);
            $("#job_title").text(jobCredits.job_title);
            $("#employee").text(jobCredits.employee);
            $("#position").text(jobCredits.position);
            $("#type").text(jobCredits.type);
            $("#seniority").text(jobCredits.seniority);
            $("#job_seniority").text(jobCredits.job_seniority);
            $("#salary").val(jobCredits.salary).change();
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

        function fillExtraImages(jobCredit) {
            if (jobCredit.incomeProveImages) {
                for (var i = 0; i < jobCredit.incomeProveImages.length; i++) {
                    appendImage('薪資證明', jobCredit.incomeProveImages[i]);
                }
            }

            if (jobCredit.businessImages) {
                for (var i = 0; i < jobCredit.businessImages.length; i++) {
                    appendImage('名片正反面', jobCredit.businessImages[i]);
                }
            }

            if (jobCredit.auxiliaryImages) {
                for (var i = 0; i < jobCredit.auxiliaryImages.length; i++) {
                    appendImage('最近年度報稅扣繳憑證', jobCredit.auxiliaryImages[i]);
                }
            }

            if (jobCredit.licenseImages) {
                for (var i = 0; i < jobCredit.licenseImages.length; i++) {
                    appendImage('其他專業證明證照', jobCredit.licenseImages[i]);
                }
            }
        }

        function appendImage(name, link) {
            var image = '<a href="' + link +'" data-fancybox="images"><img src="' + link + '" style="width:30%;max-width:400px"></a>';
            $("<tr>").append(
                $('<td class="center-text">').append(name),
                $('<td class="center-text">').append(image),
            ).appendTo("#images");
        }
    });

    var RotateImage = function (instance) {
        this.instance = instance;

        this.init();
    };

    $.extend(RotateImage.prototype, {
        $button_left: null,
        $button_right: null,
        transitionanimation: true,

        init: function () {
            var self = this;

            self.$button_right = $('<button data-rotate-right class="fancybox-button fancybox-button--rotate" title="Rotate to right"><span class="oi oi-action-redo"></span></button>')
                .prependTo(this.instance.$refs.toolbar)
                .on('click', function (e) {
                    self.rotate('right');
                });

            self.$button_left = $('<button data-rotate-left class="fancybox-button fancybox-button--rotate" title="Rotate to left"><span class="oi oi-action-undo"></span></button>')
                .prependTo(this.instance.$refs.toolbar)
                .on('click', function (e) {
                    self.rotate('left');
                });
        },

        rotate: function (direction) {
            var self = this;
            var image = self.instance.current.$image[0];
            var angle = parseInt(self.instance.current.$image.attr('data-angle')) || 0;

            if (direction == 'right') {
                angle += 90;
            } else {
                angle -= 90;
            }

            if (!self.transitionanimation) {
                angle = angle % 360;
            } else {
                $(image).css('transition', 'transform .3s ease-in-out');
            }

            self.instance.current.$image.attr('data-angle', angle);

            $(image).css('webkitTransform', 'rotate(' + angle + 'deg)');
            $(image).css('mozTransform', 'rotate(' + angle + 'deg)');
        }
    });

    $(document).on('onInit.fb', function (e, instance) {
        if (!instance.opts.rotate) {
            instance.Rotate = new RotateImage(instance);
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

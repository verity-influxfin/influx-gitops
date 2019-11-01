<script type="text/javascript" src="<?php echo base_url();?>assets/admin/js/common/datetime.js" ></script>
<script src="<?=base_url()?>assets/admin/js/mapping/user/user.js"></script>
<script src="<?=base_url()?>assets/admin/js/mapping/user/verification.js"></script>
<script src="<?=base_url()?>assets/admin/js/mapping/user/bankaccount.js"></script>
<script src="<?=base_url()?>assets/admin/js/mapping/user/bankaccounts.js"></script>
<script src="<?=base_url()?>assets/admin/js/mapping/user/virtualaccount.js"></script>
<script src="<?=base_url()?>assets/admin/js/mapping/user/virtualaccounts.js"></script>
<script src="<?=base_url()?>assets/admin/js/mapping/user/relateduser.js"></script>
<script src="<?=base_url()?>assets/admin/js/mapping/loan/credit.js"></script>
<script src="<?=base_url()?>assets/admin/js/mapping/loan/target.js"></script>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">二審</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					申戶資訊
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
							<div class="table-responsive">
								<table class="table table-bordered">
									<tr>
										<td class="table-field"><p class="form-control-static">姓名</p></td>
										<td class="table-ten">
											<p id="name" class="form-control-static"></p>
										</td>
										<td class="table-field"><p class="form-control-static">性別</p></td>
										<td class="table-ten">
											<p id="gender" class="form-control-static"></p>
										</td>
										<td class="table-field"><p class="form-control-static">學校名稱</p></td>
										<td class="table-twenty">
											<p id="school" class="form-control-static"></p>
										</td>
										<td class="table-field"><p class="form-control-static">借款端銀行/分行</p></td>
										<td class="table-twenty">
											<p id="borrower-bank" class="form-control-static"></p>
										</td>
									</tr>
									<tr>
										<td class="table-field"><p class="form-control-static">身分證字號</p></td>
										<td class="table-ten">
											<p id="id-card" class="form-control-static"></p>
										</td>
										<td class="table-field"><p class="form-control-static">婚姻</p></td>
										<td class="table-ten">
											<p id="marriage" class="form-control-static"></p>
										</td>
										<td class="table-field"><p class="form-control-static">學制/學門</p></td>
										<td class="table-twenty">
											<p id="school-system" class="form-control-static"></p>
										</td>
										<td class="table-field"><p class="form-control-static">借款端帳號</p></td>
										<td class="table-twenty">
											<p id="borrower-account" class="form-control-static"></p>
										</td>
									</tr>
									<tr>
										<td class="table-field"><p class="form-control-static">生日</p></td>
										<td class="table-ten">
											<p id="birthday" class="form-control-static"></p>
										</td>
										<td class="table-field"><p class="form-control-static">地址</p></td>
										<td class="table-ten">
											<p id="address" class="form-control-static"></p>
										</td>
										<td class="table-field"><p class="form-control-static">系所</p></td>
										<td class="table-twenty">
											<p id="school-department" class="form-control-static"></p>
										</td>
										<td class="table-field"><p class="form-control-static">投資端銀行/分行</p></td>
										<td class="table-twenty">
											<p id="investor-bank" class="form-control-static"></p>
										</td>
									</tr>
									<tr>
										<td class="table-field"><p class="form-control-static">發證日期</p></td>
										<td class="table-ten">
											<p id="id-card-issued-at" class="form-control-static"></p>
										</td>
										<td class="table-field"><p class="form-control-static">E-mail</p></td>
										<td class="table-ten">
											<p id="email" class="form-control-static"></p>
										</td>
										<td class="table-field"><p class="form-control-static">(預計)畢業日期</p></td>
										<td class="table-twenty">
											<p id="graduated-at" class="form-control-static"></p>
										</td>
										<td class="table-field"><p class="form-control-static">投資端帳號</p></td>
										<td class="table-twenty">
											<p id="investor-account" class="form-control-static"></p>
										</td>
									</tr>
								</table>
								<table class="table table-bordered">
									<tr>
										<td class="table-field"><p class="form-control-static">使用者編號</p></td>
										<td class="table-twenty">
											<p id="id" class="form-control-static"></p>
										</td>
										<td class="table-field"><p class="form-control-static">手機號碼</p></td>
										<td class="table-twenty">
											<p id="phone" class="form-control-static"></p>
										</td>
										<td class="table-field"><p class="form-control-static">註冊日期</p></td>
										<td class="table-ten">
											<p id="registered-at" class="form-control-static"></p>
										</td>
									</tr>
									<tr>
										<td class="table-field"><p class="form-control-static">持證自拍照</p></td>
										<td class="table-twenty table-picture">
											<p id="profile-image" class="form-control-static"></p>
										</td>
										<td class="table-field"><p class="form-control-static">簽約照</p></td>
										<td class="table-twenty table-picture">
											<p id="applicant-signing-target-image" class="form-control-static"></p>
										</td>
										<td class="table-field"><p class="form-control-static">借款端虛擬帳戶餘額</p></td>
										<td class="table-ten">
											<p id="borrower-virtual-account-total" class="form-control-static"></p>
										</td>
									</tr>
									<tr>
										<td class="table-field"><p class="form-control-static">FB照片</p></td>
										<td class="table-twenty table-picture">
											<p id="facebook-profile-picture" class="form-control-static"></p>
										</td>
										<td class="table-field"><p class="form-control-static">FB暱稱</p></td>
										<td class="table-ten">
											<p id="facebook-username" class="form-control-static"></p>
										</td>
										<td class="table-field"><p class="form-control-static">投資端虛擬帳戶餘額</p></td>
										<td class="table-ten">
											<p id="investor-virtual-account-total" class="form-control-static"></p>
										</td>
									</tr>
									<tr>
										<td class="table-field"><p class="form-control-static">IG照片</p></td>
										<td class="table-twenty table-picture">
											<p id="instagram-profile-picture" class="form-control-static"></p>
										</td>
										<td class="table-field"><p class="form-control-static">IG帳號名稱</p></td>
										<td class="table-ten">
											<p id="instagram-username" class="form-control-static"></p>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
		<div class="col-sm-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					借款端認證
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
							<div class="table-responsive">
								<table id="borrowing-verifications" class="table table-bordered table-hover">
									<thead>
										<tr class="odd list">
											<th class="center-text" width="40%">認證名稱</th>
											<th class="center-text" width="60%">狀態</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					投資端認證
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
							<div class="table-responsive">
								<table id="investing-verifications" class="table table-bordered table-hover">
									<thead>
									<tr class="odd list">
										<th class="center-text" width="40%">認證名稱</th>
										<th class="center-text" width="60%">狀態</th>
									</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					信用評分
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
							<div class="table-responsive">
								<table class="table table-bordered table-hover">
									<tr>
										<td class="table-field center-text"><p class="form-control-static">產品</p></td>
										<td class="center-text table-twenty">
											<p id="product-name" class="form-control-static"></p>
										</td>
									</tr>
									<tr>
										<td class="table-field center-text"><p class="form-control-static">信用等級</p></td>
										<td class="center-text table-twenty">
											<p id="credit-level" class="form-control-static"></p>
										</td>
									</tr>
									<tr>
										<td class="table-field center-text"><p class="form-control-static">信用評分</p></td>
										<td class="center-text table-twenty">
											<p id="credit-points" class="form-control-static"></p>
										</td>
									</tr>
									<tr>
										<td class="table-field center-text"><p class="form-control-static">信用額度</p></td>
										<td class="center-text table-twenty">
											<p id="credit-amount" class="form-control-static"></p>
										</td>
									</tr>
									<tr>
										<td class="table-field center-text"><p class="form-control-static">有效時間</p></td>
										<td class="center-text table-twenty">
											<p id="credit-expired-at" class="form-control-static"></p>
										</td>
									</tr>
									<tr>
										<td class="table-field center-text"><p class="form-control-static">核准時間</p></td>
										<td class="center-text table-twenty">
											<p id="credit-created-at" class="form-control-static"></p>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					反詐欺管理指標-狀態
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
							<div class="table-responsive">
								<table class="table table-bordered table-hover table-striped">
									<thead>
									<tr class="odd list">
										<th width="25%">風險等級</th>
										<th width="30%">事件時間</th>
										<th width="45%">指標內容</th>
									</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					關聯戶
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
							<div class="table-responsive center-text">
								<table id="related-users" class="table table-bordered table-hover table-striped">
									<thead>
									<tr class="odd list">
										<th width="40%">關聯原因</th>
										<th width="30%">借款/投資端</th>
										<th width="30%">使用者編號</th>
									</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
								<button id="load-more">載入更多</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					歸戶案件總攬（僅顯示申請中/還款中/逾期中）
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
							<div class="table-responsive">
								<table id="targets" class="table table-bordered table-hover table-striped">
									<thead>
									<tr class="odd list">
										<th width="10%">案號</th>
										<th width="10%">產品</th>
										<th width="12%">核准金額</th>
										<th width="12%">本金餘額</th>
										<th width="12%">可動用餘額</th>
										<th width="10%">狀態</th>
										<th width="10%">有效時間</th>
										<th width="10%">Detail</th>
										<th width="14%">借款原因</th>
									</tr>
									</thead>
									<tbody>

									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					二審分數調整
				</div>
				<div class="panel-body">
					<div class="col-sm-4">
						<h5>分數調整部分</h5>
						<form>
							<p>分數調整：(-400 ~ 400)</p>
							<input type="text" name="score"/>
							<p>審批內容：</p>
							<input type="text" name="description"/></br></br>
							<button>額度試算</button>
						</form>
					</div>
					<div class="col-sm-8">
						<h5>調整後額度試算部分</h5>
						<div class="table-responsive">
							<table class="table table-bordered table-hover table-striped">
								<tr>
									<td><p class="form-control-static">產品</p></td>
									<td>
										<p class="form-control-static"></p>
									</td>
									<td><p class="form-control-static">信用等級</p></td>
									<td>
										<p class="form-control-static"></p>
									</td>
								</tr>
								<tr>
									<td><p class="form-control-static">信用評分</p></td>
									<td>
										<p class="form-control-static"></p>
									</td>
									<td><p class="form-control-static">信用額度</p></td>
									<td>
										<p class="form-control-static"></p>
									</td>
								</tr>
								<tr>
									<td><p class="form-control-static">有效時間</p></td>
									<td>
										<p class="form-control-static"></p>
									</td>
									<td><p class="form-control-static">核准時間</p></td>
									<td>
										<p class="form-control-static"></p>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<br>
					<div class="col-lg-12 text-center">
						<button>送出</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->

<script>

    $(document).ready(function() {
        var urlString = window.location.href;
        var url = new URL(urlString);
        var caseId = url.searchParams.get("id");
        var userId = url.searchParams.get("user_id");
        // var caseId = 6543;
        // var userId = 3475;

        $("#load-more").hide();
        $.ajax({
            type: "GET",
            url: "/admin/Target/final_validations?id=" + caseId,
            beforeSend: function () {
            },
            complete: function () {
            },
            success: function (response) {
                hideLoadingAnimation();

                if (response.status.code != 200) {
                    return;
                }

                let currentTargetJson = response.response.target;
                target = new Target(currentTargetJson);
                fillCurrentTargetInfo(target)

				let userJson = response.response.user;
                console.log(userJson);
                user = new User(userJson);
                fillUserInfo(user)

                let creditJson = response.response.credits;
                credit = new Credit(creditJson);
				fillCreditInfo(credit);

				let bankAccountJson = response.response.bank_accounts;
                bankAccounts = new BankAccounts(bankAccountJson);
				fillBankAccounts(bankAccounts)

                let virtualAccountJson = response.response.virtual_accounts;
				virtualAccounts = new VirtualAccounts(virtualAccountJson);
				fillVirtualAccounts(virtualAccounts);

				var borrowerVerifications = [];
				let verificationsJson = response.response.verifications;
				for (var i = 0; i < verificationsJson.borrower.length; i++) {
                    borrowerVerifications.push(new Verification(verificationsJson.borrower[i]));
				}
				fillBorrowingVerifications(bankAccounts, borrowerVerifications);

				var investorVerifications = [];
				for (var i = 0; i < verificationsJson.investor.length; i++) {
                    investorVerifications.push(new Verification(verificationsJson.investor[i]));
				}
				fillInvestingVerifications(bankAccounts, investorVerifications);

				var targets = [];
				let targetsJson = response.response.targets;
				for (var i = 0; i < targetsJson.length; i++) {
					targets.push(new Target(targetsJson[i]));
				}
                fillTargets(targets);
            },
			error: function(error) {
                alert('資料載入失敗。請重新整理。');
			}
        });

        var relatedUsersIndex = 1;
        var relatedUsers = [];
        $.ajax({
            type: "GET",
            url: "/admin/User/related_users" + "?id=" + userId,
            beforeSend: function () {
            },
            complete: function () {
            },
            success: function (response) {
                if (response.status.code != 200) {
                    return;
                }

                let relatedUsersJson = response.response.related_users;
                for (var i = 0; i < relatedUsersJson.length; i++) {
                    var relatedUser = new RelatedUser(relatedUsersJson[i]);
                    relatedUsers.push(relatedUser);
				}
                fillRelatedUsers();
            }
        });

        $('#load-more').on('click', function() {
            fillRelatedUsers();
        });

        function hideLoadingAnimation() {
            $(".table-ten p").css('background', 'white');
            $(".table-twenty p").css('background', 'white');
		}

		function fillRelatedUsers() {
            var maxNumInPage = 20;
            var start = (relatedUsersIndex-1) * maxNumInPage;
            var end = relatedUsersIndex * maxNumInPage;
            if (end > relatedUsers.length) end = relatedUsers.length;
            if (start > end || (end - start < maxNumInPage)) {
                $("#load-more").hide();
			} else {
                $("#load-more").show();
			}

            for (var i = start; i < end; i++) {
                var reasonText = mapRelatedUsersReasons(relatedUsers[i].reason);
                var reason = '<p class="form-control-static">' + reasonText + '</p>';
                var statuses = '<p>' + relatedUsers[i].user.borrower_status + "/" + relatedUsers[i].user.investor_status + '</p>';
				var userLink = '<a href="' + '/admin/user/edit?id=' + relatedUsers[i].user.id + '" target="_blank"><p>' + relatedUsers[i].user.id + '</p></a>'
                $("<tr>").append(
                    $('<td class="center-text">').append(reason),
                    $('<td class="center-text">').append(statuses),
                    $('<td class="center-text">').append(userLink),
                ).appendTo("#related-users");
            }
            relatedUsersIndex+=1;
		}

		function mapRelatedUsersReasons(reason) {
            var mapping = {
                "same_device_id" : "相同裝置",
                "same_ip": "相同ip",
                "same_contact": "相同緊急聯絡人",
                "emergency_contact": "為此人的緊急聯絡人",
                "same_bank_account": "嘗試輸入相同銀行帳號",
                "same_id_number": "嘗試輸入相同身分證字號",
                "same_phone_number": "嘗試輸入相同電話號碼",
                "same_address": "相同住址",
                "introducer": "推薦人",
            };
            if (reason in mapping) {
                return mapping[reason];
			}
            return "未定義";
		}

		function fillCurrentTargetInfo(target) {
            $("#applicant-signing-target-image").prepend('<img src="' + target.image + '" style="width: 30%;"></img>');
		}

        function fillUserInfo(user) {
            $("#id").text(user.id);
            $("#name").text(user.name);
            $("#gender").text(user.gender);
            $("#birthday").text(user.birthday);
            $("#email").text(user.contact.email);
            $("#phone").text(user.contact.phone);
            $("#address").text(user.contact.address);
            $("#registered-at").text(user.getRegisteredAtAsDate());
            $("#profile-image").prepend('<img id="profile-image-content" src="' + user.profileImage + '" style="width:30%;" />');
            $("#id-card").text(user.idCard.id);
			$("#id-card-issued-at").text(user.idCard.issuedAt);

			$("#school").text(user.school.name);
			$("#school-system").text(user.school.system + " / " + user.school.department);
			$("#school-department").text(user.school.department);
			$("#graduated-at").text(user.school.graduateAt ? user.school.graduateAt : '未提供');

			$("#instagram-username").text(user.instagram.username);
			$("#instagram-profile-picture").prepend('<img id="instagram-profile-picture-content" src="' + user.instagram.profileImage + '" style="width:30%;" />');
			$("#facebook-profile-picture").prepend('<img id="facebook-profile-picture-content" src="' + user.getFbProfilePicture() + '" style="width:30%;" />');
			$("#facebook-username").text(user.facebook.username);
		}

		function fillCreditInfo(credit) {
            $("#product-name").text(credit.product.name);
			$("#credit-level").text(credit.level);
			$("#credit-amount").text(credit.amount);
			$("#credit-points").text(credit.points);
			$("#credit-created-at").text(credit.getCreatedAtAsDate());
			$("#credit-expired-at").text(credit.getExpiredAtAsDate());
		}

		function fillInvestingVerifications(bankAccounts, verifications) {
            for (var i = 0; i < verifications.length; i++) {
                pTag = '<p class="form-control-static">' + verifications[i].name + '</p>';
                $("<tr>").append(
                    $('<td class="table-field center-text">').append(pTag),
                    '<td class="center-text">' + getVerificationButton(bankAccounts, verifications[i]) + '</td>'
                ).appendTo("#investing-verifications");
            }
		}

		function fillBorrowingVerifications(bankAccounts, verifications) {
            for (var i = 0; i < verifications.length; i++) {
                pTag = '<p class="form-control-static">' + verifications[i].name + '</p>';
                $("<tr>").append(
                    $('<td  class="table-field center-text">').append(pTag),
                    '<td class="center-text">' + getVerificationButton(bankAccounts, verifications[i]) + '</td>'
                ).appendTo("#borrowing-verifications");
			}
		}

		function fillBankAccounts(bankAccounts) {
            if (bankAccounts.borrower) {
                var text = bankAccounts.borrower.bankCode + " / " + bankAccounts.borrower.branchCode;
                $("#borrower-bank").text(text);
				$("#borrower-account").text(bankAccounts.borrower.account);
			}

            if (bankAccounts.investor) {
                var text = bankAccounts.investor.bankCode + " / " + bankAccounts.investor.branchCode;
                $("#investor-bank").text(text);
                $("#investor-account").text(bankAccounts.investor.account);
			}
		}

		function fillVirtualAccounts(virtualAccounts) {
            if (virtualAccounts.borrower) {
                var total = virtualAccounts.borrower.funds.total - virtualAccounts.borrower.funds.frozen;
                $("#borrower-virtual-account-total").text(total + "元");
			}

            if (virtualAccounts.investor) {
                var total = virtualAccounts.investor.funds.total - virtualAccounts.investor.funds.frozen;
                $("#investor-virtual-account-total").text(total + "元");
			}
		}

		function getVerificationButton(bankAccounts, verification) {
            var button;
            var url;
            if (verification.id == 3) {
                if (verification.isPending() || verification.requireHumanReview()) {
                    button = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-refresh"></i></button>';
				} else if (verification.success()) {
					button = '<button type="button" class="btn btn-success btn-circle"><i class="fa fa-check"></i></button>';
				} else if (verification.failure()) {
					button = '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i></button>';
				} else {
                    return '<p class="form-control-static">無</p>';
				}
                url = '/admin/certification/user_bankaccount_edit?id=' + bankAccounts.borrower.id;
            } else {
                if (verification.isPending() || verification.requireHumanReview()) {
                    button = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-refresh"></i> </button>';
				} else if (verification.success()) {
                    button = '<button type="button" class="btn btn-success btn-circle"><i class="fa fa-check"></i> </button>';
				} else if (verification.failure()) {
                    button = '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i> </button>';
				} else {
                    return '<p class="form-control-static">無</p>';
				}
                url = '/admin/certification/user_certification_edit?from=risk&id=' + verification.id;
            }

            return '<a href="' + url + '">' + button + '</a>';
		}

		function fillTargets(targets) {
            for (var i = 0; i < targets.length; i++) {
                let target = targets[i];
                $("<tr>").append(
                    getCenterTextCell(target.number),
                    getCenterTextCell(target.product.name),
                    getCenterTextCell(target.amount.approved),
                    getCenterTextCell(target.amount.remaining),
                    getCenterTextCell(target.amount.principal),
                    getCenterTextCell(target.status),
                    getCenterTextCell(target.expireAt),
                    getCenterTextCell('<a href="/admin/target/edit?id=' + target.id + '" target="_blank"><button>Detail</button></a>'),
                    getCenterTextCell(target.reason)
                ).appendTo("#targets");
            }
		}

		function getCenterTextCell(value) {
            return '<td class="center-text">' + value + '</td>';
		}
    });
</script>
<style>
	.table-field {
		background-color: #f5f5f5;
		table-layout: fixed;
		width: 10%;
	}

	.table-ten {
		width: 10%;
	}

	.table-twenty {
		width: 20%;
	}

	.table-picture {
		text-align: center;
		height: 200px;
	}

	.center-text {
		text-align: center;
	}

	@keyframes placeHolderShimmer{
		0%{
			background-position: -468px 0
		}
		100%{
			background-position: 468px 0
		}
	}

	.table-ten p, .table-twenty p {
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

	.table-picture p {
		animation-duration: 1.25s;
		animation-fill-mode: forwards;
		animation-iteration-count: infinite;
		animation-name: placeHolderShimmer;
		animation-timing-function: linear;
		background: darkgray;
		background: linear-gradient(to right, #eeeeee 10%, #dddddd 18%, #eeeeee 33%);
		background-size: 800px 104px;
		height: 200px;
		position: relative;
	}
</style>

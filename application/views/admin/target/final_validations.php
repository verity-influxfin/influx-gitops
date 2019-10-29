<script type="text/javascript" src="<?php echo base_url();?>assets/admin/js/common/datetime.js" ></script>
<script src="<?=base_url()?>assets/admin/js/mapping/user/user.js"></script>
<script src="<?=base_url()?>assets/admin/js/mapping/user/verification.js"></script>
<script src="<?=base_url()?>assets/admin/js/mapping/user/bankaccount.js"></script>
<script src="<?=base_url()?>assets/admin/js/mapping/user/bankaccounts.js"></script>
<script src="<?=base_url()?>assets/admin/js/mapping/loan/credit.js"></script>

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
						<div class="col-lg-6">
							<div class="table-responsive">
								<table class="table table-bordered table-hover table-striped">
									<tr>
										<td><p class="form-control-static">姓名</p></td>
										<td>
											<p id="name" class="form-control-static"></p>
										</td>
										<td><p class="form-control-static">性別</p></td>
										<td>
											<p id="gender" class="form-control-static"></p>
										</td>
										<td><p class="form-control-static">學校名稱</p></td>
										<td>
											<p id="school" class="form-control-static"></p>
										</td>
										<td><p class="form-control-static">借款端銀行/分行</p></td>
										<td>
											<p id="borrower-bank" class="form-control-static"></p>
										</td>
									</tr>
									<tr>
										<td><p class="form-control-static">身分證字號</p></td>
										<td>
											<p id="id-card" class="form-control-static"></p>
										</td>
										<td><p class="form-control-static">婚姻</p></td>
										<td>
											<p id="marriage" class="form-control-static"></p>
										</td>
										<td><p class="form-control-static">學制/學門</p></td>
										<td>
											<p id="school-system" class="form-control-static"></p>
										</td>
										<td><p class="form-control-static">借款端帳號</p></td>
										<td>
											<p id="borrower-account" class="form-control-static"></p>
										</td>
									</tr>
									<tr>
										<td><p class="form-control-static">生日</p></td>
										<td>
											<p id="birthday" class="form-control-static"></p>
										</td>
										<td><p class="form-control-static">地址</p></td>
										<td>
											<p id="address" class="form-control-static"></p>
										</td>
										<td><p class="form-control-static">系所</p></td>
										<td>
											<p id="school-department" class="form-control-static"></p>
										</td>
										<td><p class="form-control-static">投資端銀行/分行</p></td>
										<td>
											<p id="investor-bank" class="form-control-static"></p>
										</td>
									</tr>
									<tr>
										<td><p class="form-control-static">發證日期</p></td>
										<td>
											<p id="id-card-issued-at" class="form-control-static"></p>
										</td>
										<td><p class="form-control-static">E-mail</p></td>
										<td>
											<p id="email" class="form-control-static"></p>
										</td>
										<td><p class="form-control-static">(預計)畢業日期</p></td>
										<td>
											<p id="graduated-at" class="form-control-static"></p>
										</td>
										<td><p class="form-control-static">投資端帳號</p></td>
										<td>
											<p id="investor-account" class="form-control-static"></p>
										</td>
									</tr>
								</table>
								<table class="table table-bordered table-hover table-striped">
									<tr>
										<td><p class="form-control-static">使用者編號</p></td>
										<td>
											<p id="id" class="form-control-static"></p>
										</td>
										<td><p class="form-control-static">手機號碼</p></td>
										<td>
											<p id="phone" class="form-control-static"></p>
										</td>
										<td><p class="form-control-static">註冊日期</p></td>
										<td>
											<p id="registered-at" class="form-control-static"></p>
										</td>
									</tr>
									<tr>
										<td><p class="form-control-static">持證自拍照</p></td>
										<td>
											<p class="form-control-static"></p>
										</td>
										<td><p class="form-control-static">簽約照</p></td>
										<td>
											<p class="form-control-static"></p>
										</td>
										<td><p class="form-control-static">借款端虛擬帳戶餘額</p></td>
										<td>
											<p class="form-control-static"></p>
										</td>
									</tr>
									<tr>
										<td><p class="form-control-static">FB照片</p></td>
										<td>
											<p id="facebook-profile-picture" class="form-control-static"></p>
										</td>
										<td><p class="form-control-static">FB暱稱</p></td>
										<td>
											<p id="facebook-username" class="form-control-static"></p>
										</td>
										<td><p class="form-control-static">投資端虛擬帳戶餘額</p></td>
										<td>
											<p class="form-control-static"></p>
										</td>
									</tr>
									<tr>
										<td><p class="form-control-static">IG照片</p></td>
										<td>
											<p class="form-control-static"></p>
										</td>
										<td><p class="form-control-static">IG帳號名稱</p></td>
										<td>
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
								<table id="borrowing-verifications" class="table table-bordered table-hover table-striped">
									<thead>
										<tr class="odd list">
											<th width="40%">認證名稱</th>
											<th width="60%">狀態</th>
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
								<table class="table table-bordered table-hover table-striped">
									<tr>
										<td><p class="form-control-static">實名認證</p></td>
										<td>
											<p class="form-control-static"></p>
										</td>
									</tr>
									<tr>
										<td><p class="form-control-static">金融帳號認證</p></td>
										<td>
											<p class="form-control-static"></p>
										</td>
									</tr>
									<tr>
										<td><p class="form-control-static">緊急聯絡人</p></td>
										<td>
											<p class="form-control-static"></p>
										</td>
									</tr>
									<tr>
										<td><p class="form-control-static">常用電子信箱</p></td>
										<td>
											<p class="form-control-static"></p>
										</td>
									</tr>
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
								<table class="table table-bordered table-hover table-striped">
									<tr>
										<td><p class="form-control-static">產品</p></td>
										<td>
											<p id="product-name" class="form-control-static"></p>
										</td>
									</tr>
									<tr>
										<td><p class="form-control-static">信用等級</p></td>
										<td>
											<p id="credit-level" class="form-control-static"></p>
										</td>
									</tr>
									<tr>
										<td><p class="form-control-static">信用評分</p></td>
										<td>
											<p id="credit-points" class="form-control-static"></p>
										</td>
									</tr>
									<tr>
										<td><p class="form-control-static">信用額度</p></td>
										<td>
											<p id="credit-amount" class="form-control-static"></p>
										</td>
									</tr>
									<tr>
										<td><p class="form-control-static">有效時間</p></td>
										<td>
											<p id="credit-expired-at" class="form-control-static"></p>
										</td>
									</tr>
									<tr>
										<td><p class="form-control-static">核准時間</p></td>
										<td>
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
							<div class="table-responsive">
								<table class="table table-bordered table-hover table-striped">
									<thead>
									<tr class="odd list">
										<th width="20%">類型</th>
										<th width="25%">關聯原因</th>
										<th width="25%">借款/投資端</th>
										<th width="30%">使用者編號</th>
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
					歸戶案件總攬（僅顯示申請中/還款中/逾期中）
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
							<div class="table-responsive">
								<table class="table table-bordered table-hover table-striped">
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
        var userId = 3475;
        $.ajax({
            type: "GET",
            url: "/admin/Target/final_validations?id=" + userId,
            beforeSend: function () {
            },
            complete: function () {
            },
            success: function (response) {
                if (response.status.code != 200) {
                    return;
                }
				let userJson = response.response.user;
                user = new User(userJson);
                fillUserInfo(user)

                let creditJson = response.response.credits;
                credit = new Credit(creditJson);
				fillCreditInfo(credit);

				let bankAccountJson = response.response.bank_accounts;
                bankAccounts = new BankAccounts(bankAccountJson);
				fillBankAccounts(bankAccounts)

				var verifications = [];
				let verificationsJson = response.response.verifications;
				for (var i = 0; i < verificationsJson.length; i++) {
				    verifications.push(new Verification(verificationsJson[i]));
				}
				fillBorrowingVerifications(bankAccounts, verifications);
            },
			error: function(error) {
                alert('資料載入失敗。請重新整理。');
			}
        });

        function fillUserInfo(user) {
            $("#id").text(user.id);
            $("#name").text(user.name);
            $("#gender").text(user.gender);
            $("#birthday").text(user.birthday);
            $("#email").text(user.contact.email);
            $("#phone").text(user.contact.phone);
            $("#address").text(user.contact.address);
            $("#registered-at").text(user.getRegisteredAtAsDate());
            $("#id-card").text(user.idCard.id);
			$("#id-card-issued-at").text(user.idCard.issuedAt);

			$("#school").text(user.school.name);
			$("#school-system").text(user.school.system + " / " + user.school.department);
			$("#school-department").text(user.school.department);
			$("#graduated-at").text('');

			$("#instagram-username").text(user.instagram.username);
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

		function fillBorrowingVerifications(bankAccounts, verifications) {

            for (var i = 0; i < verifications.length; i++) {
                pTag = '<p class="form-control-static">' + verifications[i].name + '</p>';
                $("<tr>").append(
                    $('<td>').append(pTag),
                    '<td>' + getVerificationButton(bankAccounts, verifications[i]) + '</td>'
                ).appendTo("#borrowing-verifications");
			}
		}

		function fillBankAccounts(bankAccounts) {
            if (bankAccounts.borrower) {
                var text = bankAccounts.borrower.bankCode + " / " + bankAccounts.borrower.branchCode;
                $("#borrower-bank").text(text);
				$("#borrower-account").text(bankAccounts.borrower.account);
			} else if (bankAccounts.investor) {
                var text = bankAccounts.investor.bankCode + " / " + bankAccounts.investor.branchCode;
                $("#investor-bank").text(text);
                $("#investor-account").text(bankAccounts.investor.account);
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
    });
</script>

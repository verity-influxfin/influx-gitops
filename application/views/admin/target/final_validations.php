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
<style type="text/css">
    .creditArea{
        width: 82px;line-height: 20px;text-align: center;margin-bottom: 6px;
    }
    .creditArea:disabled{
        border:0;line-height: 24px;background-color: #f4f4f4;
    }
    .targetDataInputblock div{
        line-height: 24px;
    }
</style>
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
				<div class="panel-body natual">
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
											<div class="scrollable">
												<p id="address" class="form-control-static"></p>
											</div>
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
                <div class="panel-body company" style="display: none">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td class="table-field"><p class="form-control-static">使用者編號</p></td>
                                        <td class="table-twenty">
                                            <p id="id" class="form-control-static"></p>
                                        </td>
                                        <td class="table-field"><p class="form-control-static">E-mail</p></td>
                                        <td class="table-ten">
                                            <p id="email" class="form-control-static"></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="table-field"><p class="form-control-static">公司登記名稱</p></td>
                                        <td class="table-ten">
                                            <p id="name" class="form-control-static"></p>
                                        </td>
                                        <td class="table-field"><p class="form-control-static">統一編號</p></td>
                                        <td class="table-ten">
                                            <p id="id-card" class="form-control-static"></p>
                                        </td>
                                    </tr>
                                </table>
                                <table class="table table-bordered">
                                    <tr>
                                        <td class="table-field"><p class="form-control-static">借款端虛擬帳戶餘額</p></td>
                                        <td class="table-ten">
                                            <p id="borrower-virtual-account-total" class="form-control-static"></p>
                                        </td>
                                        <td class="table-field"><p class="form-control-static">投資端虛擬帳戶餘額</p></td>
                                        <td class="table-ten">
                                            <p id="investor-virtual-account-total" class="form-control-static"></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="table-field"><p class="form-control-static">借款端銀行/分行</p></td>
                                        <td class="table-twenty">
                                            <p id="borrower-bank" class="form-control-static"></p>
                                        </td>
                                        <td class="table-field"><p class="form-control-static">投資端銀行/分行</p></td>
                                        <td class="table-twenty">
                                            <p id="investor-bank" class="form-control-static"></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="table-field"><p class="form-control-static">借款端帳號</p></td>
                                        <td class="table-twenty">
                                        <p id="borrower-account" class="form-control-static"></p>
                                        </td>
                                        <td class="table-field"><p class="form-control-static">投資端帳號</p></td>
                                        <td class="table-twenty">
                                            <p id="investor-account" class="form-control-static"></p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
        </div>
			<!-- /.panel -->

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
										<th width="20%">關聯原因</th>
										<th width="30%">關聯值</th>
										<th width="20%">借款/投資端</th>
										<th width="30%">使用者編號</th>
									</tr>
									</thead>
									<tbody>
										<tr class="odd list">
											<td class="center-text fake-fields">
												<p class="form-control-static"></p>
											</td>
											<td class="center-text fake-fields">
												<p class="form-control-static"></p>
											</td>
											<td class="center-text fake-fields">
												<p class="form-control-static"></p>
											</td>
											<td class="center-text fake-fields">
												<p class="form-control-static"></p>
											</td>
										</tr>
									</tbody>
								</table>
								<button id="load-more" class="btn btn-default">載入更多</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="targetDatas" class="col-lg-12 hide">
			<div class="panel panel-default">
				<div class="panel-heading">
					案件徵提資料
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
							<div class="table-responsive">
								<table id="targetData" class="table table-bordered">
									<thead>
									<tr class="odd list">
										<th width="10%">資料項目</th>
										<th width="10%">繳交狀態</th>
										<th width="10%">加分項目</th>
                                    </tr>
									</thead>
                                    <tbody>
                                    <tr class="odd list">
                                        <td class="center-text fake-fields">
                                            <p class="form-control-static"></p>
                                        </td>
                                        <td class="center-text fake-fields">
                                            <p class="form-control-static"></p>
                                        </td>
                                        <td class="center-text fake-fields">
                                            <p class="form-control-static"></p>
                                        </td>
                                    </tr>
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
					歸戶案件總覽（僅顯示申請中/還款中/逾期中）
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
							<div class="table-responsive">
								<table id="targets" class="table table-bordered">
									<thead>
									<tr class="odd list">
										<th width="10%">案號</th>
										<th width="10%">產品</th>
										<th width="10%">申請金額</th>
										<th width="10%">核准金額</th>
										<th width="10%">本金餘額</th>
										<th width="10%">可動用餘額</th>
										<th width="6%">狀態</th>
										<th width="10%">有效時間</th>
										<th width="14%">借款原因</th>
                                        <th width="10%">詳情</th>
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
					<div class="col-sm-4 changeCredit">
						<h5>分數調整部分</h5>
						<form id="credit-evaluation" method="GET" action="/admin/Target/credits">
							<p>分數調整：(-400 ~ 400)</p>
							<input type="text" name="score" value="0"/>
							<button class="btn btn-default" type="submit">額度試算</button>
						</form>
					</div>
					<div class="col-sm-8 changeCredit">
						<h5>調整後額度試算部分</h5>
						<div class="table-responsive">
							<table class="table table-bordered">
								<tr>
									<td class="table-field center-text"><p>產品</p></td>
									<td class="center-text table-reevaluation">
										<p id="new-product-name"></p>
									</td>
									<td class="table-field center-text"><p>信用等級</p></td>
									<td class="center-text table-reevaluation">
										<p id="new-credit-level"></p>
									</td>
								</tr>
								<tr>
									<td class="table-field center-text"><p>信用評分</p></td>
									<td class="center-text table-reevaluation">
										<p id="new-credit-points"></p>
									</td>
									<td class="table-field center-text"><p>信用額度</p></td>
									<td class="center-text table-reevaluation">
										<p id="new-credit-amount"></p>
									</td>
								</tr>
								<tr>
                                    <td class="table-field center-text"><p>核准時間</p></td>
                                    <td class="center-text table-reevaluation">
                                        <p id="new-credit-created-at"></p>
                                    </td>
                                    <td class="table-field center-text"><p>有效時間</p></td>
                                    <td class="center-text table-reevaluation">
                                    <p id="new-credit-expired-at"></p>
                                    </td>
                                </tr>
							</table>
						</div>
					</div>
					<br>
					<div class="center-text">
						<form id="evaluation-complete" method="POST" action="/admin/Target/evaluation_approval">
							<div class="col-lg-12 text-center">
								<p style="display:inline">審批內容：</p>
								<input type="text" name="description"/>
								<button class="btn btn-warning" type="submit">額度試算</button>
                                <button class="btn btn-danger" data-url="/admin/Target/verify_failed" id="verify_failed">不通過</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.row -->
</div>
</div>
<!-- /#page-wrapper -->

<script>

    $(document).ready(function() {
        var urlString = window.location.href;
        var url = new URL(urlString);
        var caseId = url.searchParams.get("id");
        var userId = url.searchParams.get("user_id");
        var modifiedPoints = null;
        var targetInfoAjaxLock = false;
        var relatedUserAjaxLock = false;

        changeReevaluationLoading(false);
        fillFakeVerifications("borrowing");
        fillFakeVerifications("investing");
        fillFakeRelatedUsers();
        fillFakeTargets();
        $("#load-more").hide();
        $.ajax({
            type: "GET",
            url: "/admin/Target/final_validations?id=" + caseId,
            beforeSend: function () {
                targetInfoAjaxLock = true;
            },
            complete: function () {
                targetInfoAjaxLock = false;
				fetchRelatedUsers(userId);
            },
            success: function (response) {
                hideLoadingAnimation();
                fillFakeVerifications("borrowing", false);
                fillFakeVerifications("investing", false);
                fillFakeTargets(false);
                if (response.status.code != 200 && response.status.code != 404) {
                    return;
                } else if (response.status.code == 404) {
                    alert('資料不存在');
                    window.close();
                    return;
				}

                let currentTargetJson = response.response.target;
                target = new Target(currentTargetJson);
                fillCurrentTargetInfo(target)
                !$.isEmptyObject(target.targetData)?fillCurrentTargetData(target.targetData,target.productTargetData,target.creditTargetData):'';

                let userJson = response.response.user;
                user = new User(userJson);
                if(response.response.user.company == 1){
                    $('.natual').css('display','none');
                    $('.company').css('display','block');
                    $('#targetDatas').removeClass('hide');
                    fillCompanyUserInfo(user);
                }else{
                    fillUserInfo(user)
                }

                let creditJson = response.response.credits;
                credit = new Credit(creditJson);
				fillCreditInfo(credit);

				let bankAccountJson = response.response.bank_accounts;
                bankAccounts = new BankAccounts(bankAccountJson);
				fillBankAccounts(bankAccounts,response.response.user.company)

                let virtualAccountJson = response.response.virtual_accounts;
				virtualAccounts = new VirtualAccounts(virtualAccountJson);
				fillVirtualAccounts(virtualAccounts,response.response.user.company);

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
		function fetchRelatedUsers(userId) {
			$.ajax({
				type: "GET",
				url: "/admin/User/related_users" + "?id=" + userId,
				beforeSend: function () {
					relatedUserAjaxLock = true;
				},
				complete: function () {
					relatedUserAjaxLock = false;
				},
				success: function (response) {
					fillFakeRelatedUsers(false);
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
		}


        $('#load-more').on('click', function() {
            fillRelatedUsers();
        });

        function hideLoadingAnimation() {
            $(".table-ten p").css('background', 'white');
            $(".table-twenty p").css('background', 'white');
		}

		function changeReevaluationLoading(display = true) {
			if (!display) {
				$(".table-reevaluation p").css('background', 'white');
 				$(".table-reevaluation p").css('animation-play-state', 'paused');
			} else {
				$(".table-reevaluation p").css('animation-play-state', 'running');
				$(".table-reevaluation p").css('background', 'linear-gradient(to right, #eeeeee 10%, #dddddd 18%, #eeeeee 33%)');
				$(".table-reevaluation p").css('animation-duration', '1.25s');
				$(".table-reevaluation p").css('background-size', '800px 104px');
			}
		}

		function fillFakeRelatedUsers(show = true) {
            if (!show) {
                $("#related-users tr:gt(0)").remove();
                return;
			}
            pTag = '<p class="form-control-static"></p>'

            for (var i = 0; i < 3; i++) {
                $("<tr>").append(
                    $('<td class="fake-fields center-text">').append(pTag),
                    $('<td class="fake-fields center-text">').append(pTag),
                    $('<td class="fake-fields center-text">').append(pTag),
                    $('<td class="fake-fields center-text">').append(pTag),
                ).appendTo("#related-users");
			}
		}

		function fillRelatedUsers() {
            var maxNumInPage = 5;
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
                var value = '<p class="form-control-static">' + relatedUsers[i].relatedValue + '</p>';
                var statuses = '<p>' + relatedUsers[i].user.borrower_status + "/" + relatedUsers[i].user.investor_status + '</p>';
				var userLink = '<a href="' + '/admin/user/edit?id=' + relatedUsers[i].user.id + '" target="_blank"><p>' + relatedUsers[i].user.id + '</p></a>'
                $("<tr>").append(
                    $('<td class="center-text">').append(reason),
                    $('<td class="center-text">').append(value),
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

		function fillCurrentTargetData(targetData,productTargetData,creditTargetData) {
            var targetDatas = $.parseJSON(targetData);
            var newPageData = '', content = '' , addbouns = false;
            $('#targetData tbody tr').remove();
            $.each(productTargetData,function (k,v) {
                if(v[0]=='Picture'){
                    content = '';
                    $.each(targetDatas[k],function (sk,sv) {
                        content += '<a href="'+sv+'" data-fancybox="images"><img style="width: 100px" src="'+sv+'" /></a>';
                    });
                }else{
                    content = targetDatas[k];
                }
                var bonus = '無加權分數';
                if(creditTargetData[k]!=undefined){
                    addbouns = true;
                    bonus = '<span id="targetDataAudit" data-id="'+k+'"><select class="form-control">';
                    $.each(creditTargetData[k],function (tk,tv) {
                        bonus += '<option value="' + tv + '">加分 ' + tv + '</option>';
                    });
                    bonus +='</select><br /><a class="btn btn-warning">加至分數調整區</a></span>';
                }
                $('#targetData tbody').append('<tr><td><p class="form-control-static">'+v[1]+'</p></td><td>'+(targetDatas[k]!=''?content:'未送件')+'</td><td><p class="form-control-static">'+bonus+'</p></td></tr>');
                addbouns?$('.targetDataInputblock,[name=score]').removeClass('hide'):'';
            });
            $('.credit-input').on('keyup mouseup', function() {
                $('[name=score]').val(parseInt($(this).val())+parseInt($('.targetDataInput').val()));
            });
            $('#targetDataAudit a').on('click', function() {
                $(this).attr('disabled',true);
                var item = $(this).parent();
                var bonus = '<div data-source="'+item.closest('span').data('id')+'" data-score="'+item.find(':selected').val()+'">'+item.closest('tr').find('td').eq(0).text()+' + '+item.find(':selected').val()+'</div>';
                $('.targetDataInputblock div').append(bonus);
                $('.targetDataInput').val(parseInt($('.targetDataInput').val())+parseInt(item.find(':selected').val()));
                $('.credit-input').keyup();
                $('#credit-evaluation [type=submit]').click();
            });
        }

        function fillUserInfo(user) {
            $(".natual #id").text(user.id);
            $(".natual #name").text(user.name);
            $(".natual #gender").text(user.gender);
            $(".natual #birthday").text(user.birthday);
            $(".natual #email").text(user.contact.email);
            $(".natual #phone").text(user.contact.phone);
            $(".natual #address").text(user.contact.address);
            $(".natual #registered-at").text(user.getRegisteredAtAsDate());
            $(".natual #profile-image").prepend('<img id="profile-image-content" src="' + user.profileImage + '" style="width:30%;" />');
            $(".natual #id-card").text(user.idCard.id);
			$(".natual #id-card-issued-at").text(user.idCard.issuedAt);

			$("#marriage").text(user.isMarried() ? "已婚" : "");

			$("#school").text(user.school.name);
			$("#school-system").text(user.school.system + " / " + user.school.department);
			$("#school-department").text(user.school.department);
			$("#graduated-at").text(user.school.graduateAt ? user.school.graduateAt : '未提供');

			if(user.instagram){
			    $("#instagram-username").text(user.instagram.username);
			    $("#instagram-profile-picture").prepend('<img id="instagram-profile-picture-content" src="' + user.instagram.profileImage + '" style="width:30%;" />');
            }
			if (user.facebook) {
                $("#facebook-profile-picture").prepend('<img id="facebook-profile-picture-content" src="' + user.getFbProfilePicture() + '" style="width:30%;" />');
                $("#facebook-username").text(user.facebook.username);
			}
		}

        function fillCompanyUserInfo(user) {
            $(".company #id").text(user.id);
            $(".company #name").html(user.name+" - <a target='_blank' href='../admin/judicialperson/edit?id="+user.judicial_id+"'>法人申請資料</a> / <a target='_blank' href='../admin/judicialperson/edit?id="+user.judicial_id+"'>經銷商申請資料</a>");
            $(".company #gender").text(user.gender);
            $(".company #birthday").text(user.birthday);
            $(".company #email").text(user.contact.email);
            $(".company #phone").text(user.contact.phone);
            $(".company #address").text(user.contact.address);
            $(".company #registered-at").text(user.getRegisteredAtAsDate());
            $(".company #id-card").text(user.idCard.id);
			$(".company #id-card-issued-at").text(user.idCard.issuedAt);
		}

		function clearCreditInfo(isReEvaluated = false) {
			var prefix = '';
			if (isReEvaluated) prefix = "new-";
				$("#" + prefix + "product-name").text('');
				$("#" + prefix + "credit-level").text('');
				$("#" + prefix + "credit-amount").text('');
				$("#" + prefix + "credit-points").text('');
				$("#" + prefix + "credit-created-at").text('');
				$("#" + prefix + "credit-expired-at").text('');
		}

		function fillCreditInfo(credit, isReEvaluated = false) {
			var prefix = '';
			if(credit.product.sub_product_id == 9999){
                $('#credit-evaluation button').attr('disabled',false);
                $('#evaluation-complete [type=submit]').text('通過');
                $('#evaluation-complete [type=submit]').removeClass('btn-warning').addClass('btn-success');
                $('.changeCredit').hide();
            }
			if (isReEvaluated) prefix = "new-";
			$("#" + prefix + "product-name").text(credit.product.name);
			$("#" + prefix + "credit-level").text(credit.level);
			$("#" + prefix + "credit-amount").text(convertNumberSplitedByThousands(credit.amount));
			$("#" + prefix + "credit-points").text(credit.points);
            $("#" + prefix + "credit-created-at").text(credit.getCreatedAtAsDate());
            $("#" + prefix + "credit-expired-at").text(credit.getExpiredAtAsDate());
        }

        function fillFakeVerifications(type, show = true) {
            var tableId = "#" + type + "-verifications";
            for (var i = 0; i < 3; i++) {
                if (!show) {
                    $(tableId + " tr:gt(0)").remove();
                    return;
                }
                pTag = '<p class="form-control-static"></p>';

                for (var i = 0; i < 3; i++) {
                    $("<tr>").append(
                        $('<td class="table-field center-text">').append(pTag),
                        $('<td class="fake-fields center-text">').append(pTag),
                    ).appendTo(tableId);
                }
			}
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

		function fillBankAccounts(bankAccounts,company) {
            var type = company==1?'.company ':'.natual ';
            if (bankAccounts.borrower) {
                var text = bankAccounts.borrower.bankCode + " / " + bankAccounts.borrower.branchCode;
                $(type+"#borrower-bank").text(text);
				$(type+"#borrower-account").text(bankAccounts.borrower.account);
			}

            if (bankAccounts.investor) {
                var text = bankAccounts.investor.bankCode + " / " + bankAccounts.investor.branchCode;
                $(type+"#investor-bank").text(text);
                $(type+"#investor-account").text(bankAccounts.investor.account);
			}
		}

		function fillVirtualAccounts(virtualAccounts,company) {
            var type = company==1?'.company ':'.natual ';
            if (virtualAccounts.borrower) {
                var total = virtualAccounts.borrower.funds.total - virtualAccounts.borrower.funds.frozen;
                total = convertNumberSplitedByThousands(total);
                $(type+"#borrower-virtual-account-total").text(total + "元");
			}

            if (virtualAccounts.investor) {
                var total = virtualAccounts.investor.funds.total - virtualAccounts.investor.funds.frozen;
                total = convertNumberSplitedByThousands(total);
                $(type+"#investor-virtual-account-total").text(total + "元");
			}
		}

		function getVerificationButton(bankAccounts, verification) {
            var button;
            var url;
            if (verification.id == 3) {
                if (verification.isPending() || verification.requireHumanReview()) {
                    button = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-refresh"></i></button>';
				} else if (verification.success() && !verification.expired) {
					button = '<button type="button" class="btn btn-success btn-circle"><i class="fa fa-check"></i></button>';
				} else if (verification.success() && verification.expired) {
                    button = '<button type="button" class="btn btn-danger circle"><i class="fa fa-check"></i></button>' + getVerificationExpiredTimeText(verification);
                } else if (verification.failure()) {
					button = '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i></button>' + getVerificationExpiredTimeText(verification);
				} else {
                    return '<p class="form-control-static">無</p>';
				}
                url = '/admin/certification/user_bankaccount_edit?id=' + bankAccounts.borrower.id;
            } else {
                if (verification.isPending() || verification.requireHumanReview()) {
                    button = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-refresh"></i> </button>';
				} else if (verification.success() && !verification.expired) {
                    button = '<button type="button" class="btn btn-success btn-circle"><i class="fa fa-check"></i> </button>';
				} else if (verification.success() && verification.expired) {
                    button = '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-check"></i></button>' + getVerificationExpiredTimeText(verification);
                } else if (verification.failure()) {
                    button = '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i> </button>' + getVerificationExpiredTimeText(verification);
				} else {
                    return '<p class="form-control-static">無</p>';
				}
                url = '/admin/certification/user_certification_edit?id=' + verification.id;
            }

            return '<a target="_blank" href="' + url + '">' + button + '</a>';
		}

		function getVerificationExpiredTimeText(verification) {
            if (verification.expiredAt > 0) {
                return '(' + verification.getExpiredAtHumanReadable() + ')';
			}
            return '';
		}

		function fillFakeTargets(show = true) {
            if (!show) {
                $("#targets tr:gt(0)").remove();
                return;
            }

            pTag = '<p class="form-control-static"></p>';
            for (var i = 0; i < 3; i++) {
                $("<tr>").append(
                    $('<td class="fake-fields center-text">').append(pTag),
                    $('<td class="fake-fields center-text">').append(pTag),
                    $('<td class="fake-fields center-text">').append(pTag),
                    $('<td class="fake-fields center-text">').append(pTag),
                    $('<td class="fake-fields center-text">').append(pTag),
                    $('<td class="fake-fields center-text">').append(pTag),
                    $('<td class="fake-fields center-text">').append(pTag),
                    $('<td class="fake-fields center-text">').append(pTag),
                    $('<td class="fake-fields center-text">').append(pTag),
                    $('<td class="fake-fields center-text">').append(pTag)
                ).appendTo("#targets");
			}
		}

		function fillTargets(targets) {
            for (var i = 0; i < targets.length; i++) {
                let target = targets[i];
                var backgroundColor = target.status.text == '待核可' ? 'bg-danger' : '';

                var amountRequested = convertNumberSplitedByThousands(target.amount.requested);
                var amountApproved = convertNumberSplitedByThousands(target.amount.approved);
                var amountRemaining = convertNumberSplitedByThousands(target.amount.remaining);
                var principal = convertNumberSplitedByThousands(target.amount.principal);

                $("<tr>").append(
                    getCenterTextCell(target.number, backgroundColor),
                    getCenterTextCell(target.product.name, backgroundColor),
                    getCenterTextCell(amountRequested, backgroundColor),
                    getCenterTextCell(amountApproved, backgroundColor),
                    getCenterTextCell(amountRemaining, backgroundColor),
                    getCenterTextCell(principal, backgroundColor),
                    getCenterTextCell(target.status.text, backgroundColor),
                    getCenterTextCell(target.getExpireAtHumanReadable(), backgroundColor),
                    getCenterTextCell(target.reason, backgroundColor),
                    getCenterTextCell('<a href="/admin/target/edit?id=' + target.id + '" target="_blank"><button class="btn btn-info">詳情</button></a>', backgroundColor)
                ).appendTo("#targets");
            }
        }

		function getCenterTextCell(value, additionalCssClass = "") {
            return '<td class="center-text ' + additionalCssClass + '">' + value + '</td>';
		}

		function convertNumberSplitedByThousands(value) {
			var convertedNumbers = value;
			try {
				convertedNumbers = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
			} catch(err) {

			}
            return convertedNumbers;
		}

        $("#credit-evaluation").submit(function(e) {
            e.preventDefault();
            $('#credit-evaluation button').attr('disabled',true);
            if (relatedUserAjaxLock || targetInfoAjaxLock) {
                alert("請等待資料載入完成後，再行試算。");
                $('#credit-evaluation button').attr('disabled',false);
                return;
			}

            var form = $(this);
            var url = form.attr('action');
            var points = form.find('input[name="score"]').val();
            var remark = form.find('input[name="description"]').val();
            $.ajax({
                type: "GET",
                url: url + "?id=" + caseId + "&points=" + points,
                beforeSend: function () {
                    changeReevaluationLoading(true);
                    clearCreditInfo(true);
                },
                complete: function () {
                    changeReevaluationLoading(false);
                },
                success: function (response) {
                    if (response.status.code != 200) {
                        return;
                    }

                    let creditJson = response.response.credits;
                    credit = new Credit(creditJson);
                    fillCreditInfo(credit, true);
                    modifiedPoints = points;
                    $('#credit-evaluation button').attr('disabled',false);
                    $('#evaluation-complete [type=submit]').text('通過');
                    $('#evaluation-complete [type=submit]').removeClass('btn-warning').addClass('btn-success');
                }
            });
        });

        $("#evaluation-complete").submit(function(e) {
            e.preventDefault();
            $('#evaluation-complete button').attr('disabled',true);
            if (modifiedPoints === null) {
                $('#credit-evaluation [type=submit]').click();
                $('#evaluation-complete button').attr('disabled',false);
                $('#evaluation-complete [type=submit]').click();
                return;
            }

            var isConfirmed = confirm("確認是否要通過審核？");
            if (!isConfirmed){
                $('#evaluation-complete button').attr('disabled',false);
                return false;
            }

            var form = $(this);
            var url = form.attr('action');
            var description = form.find('input[name="description"]').val();

			var data = {
			    'id' : caseId,
				'points' : modifiedPoints,
				'reason' : description
			}
            $.ajax({
                type: "POST",
                url: url,
                data: data, // serializes the form's elements.
                success: function(response) {
                    if (response.status.code != 200) {
                        alert('審核失敗，請重整頁面後，再試一次。');
                        return;
                    }
					alert("審核成功，點選OK關閉頁面。");
                    window.close();
                },
                error: function() {
                    alert('審核失敗，請重整頁面後，再試一次。');
                }
            });
		});

        $("#verify_failed").click(function(e) {
            e.preventDefault();
            $('#evaluation-complete button').attr('disabled',true);
            var isConfirmed = confirm("確認是否要退件？");
            if (!isConfirmed){
                $('#evaluation-complete button').attr('disabled',false);
                return false;
            }

            var url = $(this).attr('data-url');
            var description = $('input[name="description"]').val();

            var data = {
                'id' : caseId,
                'remark' : description
            }
            $.ajax({
                type: "get",
                url: url,
                data: data, // serializes the form's elements.
                success: function(response) {
                    alert("審核成功，點選OK關閉頁面。");
                    window.close();
                },
                error: function() {
                    alert('審核失敗，請重整頁面後，再試一次。');
                }
            });
        });
        window.onunload = refreshParent;
        function refreshParent() {
            window.opener.location.reload();
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

	.table-reevaluation {
		width: 10%;
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

	.table-ten p, .table-twenty p, .table-reevaluation p {
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

	.scrollable {
		overflow: auto;
	}
</style>
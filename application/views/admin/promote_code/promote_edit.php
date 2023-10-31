<script>
    let modifiedFlag = false;

    $(document).ready(function () {
        $(document).on("click",".contract",function() {
            $(window.open().document.body).html('<div style="margin: 0px;padding: 15px;height: 978px;line-height: 24px;letter-spacing: 1px;font-size: 12px;border: 0;width: 650px;white-space: pre-wrap;">'+$(this).data('value')+'</div>');
        });

        $(".number").bind( "change", function(e) {
            e.target.value = (parseInt(e.target.value) || '');
            $(e.target).next('input').val('%');

        });

        $(".percent").bind( "change", function(e) {
            $(e.target).prev('input').val('');
            let number = e.target.value.slice(0, e.target.value.length - 1);
            if (number.includes('%')) {
                e.target.value = '%';
            } else {
                number = Math.min(Math.max(parseFloat(e.target.value), 0), 100);
                if(Number.isNaN(number))
                    number = 0;

                e.target.value = parseFloat(number) + '%';
                e.target.setSelectionRange(e.target.value.length - 1, e.target.value.length - 1);
            }
        });

        $(document).on("click",".modified",function() {
            $('#status_selector').prop("disabled", false);
            modifiedFlag = true;
        });
    });

    function getInt(val) {
        let v = parseFloat(val);
        if (v % 1 === 0) {
            return v;
        } else {
            let n = v.toString().split('.').join('');
            return parseInt(n);
        }
    }

    function change_submit() {
        if(!modifiedFlag)
            return false;

        let qrcode_id = $('#qrcode_id').val();
        let status = $('#status_selector').val();
        if(!qrcode_id)
            return false;
        Pace.track(() => {
            $.ajax({
                type: "POST",
                url: "<?=admin_url('sales/promote_set_status')?>",
                data: {
                    'user_qrcode_id' : qrcode_id,
                    'status' : status,
                },
                beforeSend: function () {
                },
                complete: function () {
                },
                success: function (response) {
                    alert(response.response.msg);
                    if(response.status.code=='200'){
                        location.reload();
                    }
                },
                error: function (response) {
                    alert(response.msg);
                }
            });
        });
    }

	function form_onsubmit() {
		return true;
	}
</script>
<?php

$studentRewardAmount = $data['api']['award_info']['student_amount'] ?? 0;
$salaryManRewardAmount = $data['api']['award_info']['salary_man_amount'] ?? 0;
$smallEnterpriseAmount = $data['api']['award_info']['small_enterprise_amount'] ?? 0;
$totalRewardAmount = $studentRewardAmount + $salaryManRewardAmount + $smallEnterpriseAmount;


?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">推薦有賞</h1>

		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading" style="display: flex">
                    <div class="col-md-6">
                        <p class="form-control-static">推薦有賞</p>
                    </div>
                    <div class="align-items-end col-md-6">
                        <p class="form-control-static contract" data-id="<?= isset($data['info'])?$data['info']['contract_id']??"":"" ?>" data-value="<?= $contract??"" ?>" style="color: #428bca;text-decoration: none;cursor: pointer;">合約下載</p></td>
                    </div>
				</div>
				<div class="panel-body">
                    <input hidden id="qrcode_id" value="<?= isset($data['info'])?$data['info']['id']??'':'' ?>">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <tbody>
                                         <tr>
                                             <td>
                                                <p class="form-control-static">ID：<?= isset($data['info'])?$data['info']['user_id']??"":"" ?></p>
                                             </td>
                                             <td>
                                                <p class="form-control-static">名稱：<?= isset($data['info'])?$data['info']['name']??"":"" ?></p>
                                             </td>
                                             <td>
                                                 <p class="form-control-static">類型：<?= isset($data['info'])?$data['info']['settings']['description']??"":"" ?></p>
                                             </td>
                                             <td>
                                                 <p class="form-control-static">邀請碼：<?= isset($data['info'])?$data['info']['promote_code']??"":"" ?></p>
                                             </td>
                                             <td>
                                                 <p class="form-control-static">日期：<?=isset($_GET['sdate'])&&$_GET['sdate']!=''?$_GET['sdate']:''?> ~ <?=isset($_GET['edate'])&&$_GET['edate']!=''?$_GET['edate']:date("Y-m-d")?></p>
                                             </td>
                                             <td>
                                                 <p class="form-control-static">狀態：
                                                     <select id="status_selector" class="form-select" aria-label="Default select example" disabled>
                                                         <? foreach ($this->user_qrcode_model->status_list as $status => $name) { ?>
                                                         <option value="<?= $status ?>" <?= ($data['info']['status']??'')==$status?"selected":"" ?>><?=$name?></option>
                                                         <? } ?>
                                                     </select>
                                                 </p>
                                             </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
					<div class="row">
						<div class="col-lg-8">
                            <div style="display: flex; align-items: center;">
                                <h2>統計資訊</h2>
                                <div class="mt-3 ml-3">
                                    <a href="<?= admin_url('sales/promote_report_export?' . http_build_query($_GET)) ?>" target="_blank">
                                        <button class="btn btn-primary">下載統計報表</button></a>
                                </div>
                            </div>
							<div class="table-responsive">
								<table class="table table-bordered table-hover table-striped">
									<tbody>
										<tr>
                                            <td>
                                                <p class="form-control-static">下載數量</p>
                                            </td>
                                            <td>
                                                <p class="form-control-static"><?= $data['downloadedCount']??"" ?></p>
                                            </td>
                                            <td>
                                                <p class="form-control-static">註冊+下載數量</p>
                                            </td>
                                            <td>
                                                <p class="form-control-static"><?= $data['fullMemberCount']??"" ?></p>
                                            </td>
                                            <td>
                                                <p class="form-control-static">註冊+下載獎金</p>
                                            </td>
                                            <td>
                                                <p class="form-control-static"><?= $data['fullMemberRewardAmount']??"" ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p class="form-control-static">註冊數量</p>
                                            </td>
                                            <td>
                                                <p class="form-control-static"><?= $data['registeredCount'] ?? '' ?></p>
                                            </td>
                                            <td colspan="2"></td>
                                            <td>
                                                <p class="form-control-static">註冊獎金</p>
                                            </td>
                                            <td>
                                                <p class="form-control-static"><?= $data['registeredRewardAmount'] ?? '' ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p class="form-control-static">學生貸核准數量</p>
                                            </td>
                                            <td >
                                                <p class="form-control-static"><?= isset($data['loanedCount'])?$data['loanedCount']['student']??"":"" ?></p>
                                            </td>
                                            <td>
                                                <p class="form-control-static">學生貸核准總金額</p>
                                            </td>
                                            <td >
                                                <p class="form-control-static"><?= isset($data['loanedBalance'])?$data['loanedBalance']['student']??"":"" ?></p>
                                            </td>
                                            <td>
                                                <p class="form-control-static">學生貸獎金</p>
                                            </td>
                                            <td>
                                                <p class="form-control-static">
                                                    <?php //= isset($data['rewardAmount'])?$data['rewardAmount']['student']??"":"" ?>
                                                    <?= $studentRewardAmount ?>
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p class="form-control-static">上班族貸核准數量</p>
                                            </td>
                                            <td>
                                                <p class="form-control-static"><?= isset($data['loanedCount'])?$data['loanedCount']['salary_man']??"":"" ?></p>
                                            </td>
                                            <td>
                                                <p class="form-control-static">上班族貸核准總金額</p>
                                            </td>
                                            <td>
                                                <p class="form-control-static"><?= isset($data['loanedBalance'])?$data['loanedBalance']['salary_man']??"":"" ?></p>
                                            </td>
                                            <td>
                                                <p class="form-control-static">上班族貸獎金</p>
                                            </td>
                                            <td>
                                                <p class="form-control-static">
                                                    <?php //= isset($data['rewardAmount'])?$data['rewardAmount']['salary_man']??"":"" ?>
                                                    <?= $salaryManRewardAmount ?>
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p class="form-control-static">微企貸核准數量</p>
                                            </td>
                                            <td>
                                                <p class="form-control-static"><?= isset($data['loanedCount'])?$data['loanedCount']['small_enterprise']??"":"" ?></p>
                                            </td>
                                            <td>
                                                <p class="form-control-static">微企貸核准總金額</p>
                                            </td>
                                            <td>
                                                <p class="form-control-static"><?= isset($data['loanedBalance'])?$data['loanedBalance']['small_enterprise']??"":"" ?></p>
                                            </td>
                                            <td>
                                                <p class="form-control-static">微企貸獎金</p>
                                            </td>
                                            <td>
                                                <p class="form-control-static">
                                                    <?php //= isset($data['rewardAmount'])?$data['rewardAmount']['small_enterprise']??"":"" ?>
                                                    <?= $smallEnterpriseAmount ?>
                                                </p>
                                            </td>
                                        </tr>
                                        <?php
                                            foreach ($collaborator_list as $collaborator) {
                                        ?>
                                        <tr>
                                            <td>
                                                <p class="form-control-static"><?= $collaborator['collaborator']??'' ?>核准數量</p>
                                            </td>
                                            <td>
                                                <p class="form-control-static"><?= $data['collaborationCount'][$collaborator['id']]??0 ?></p>
                                            </td>
                                            <td colspan="2">
                                                <p class="form-control-static"></p>
                                            </td>
                                            <td>
                                                <p class="form-control-static"><?= $collaborator['collaborator'] ?>核准獎金</p>
                                            </td>
                                            <td>
                                                <p class="form-control-static"><?= $data['collaborationRewardAmount'][$collaborator['id']]??0 ?></p>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <tr>
<!--                                            <td colspan="2">-->
<!--                                                <p class="form-control-static"></p>-->
<!--                                            </td>-->
<!--                                            <td>-->
<!--                                                <p class="form-control-static">累積核貸總金額</p>-->
<!--                                            </td>-->
<!--                                            <td>-->
<!--                                                <p class="form-control-static">--><?//= $data['totalLoanedAmount']??"" ?><!--</p>-->
<!--                                            </td>-->
                                            <td colspan="4">
                                                <p class="form-control-static"></p>
                                            </td>
                                            <td>
                                                <p class="form-control-static">累積總獎金</p>
                                            </td>
                                            <td>
                                                <p class="form-control-static">
                                                    <?php //= $data['totalRewardAmount']??"" ?>
                                                    <?= $totalRewardAmount ?>
                                                </p>
                                            </td>
                                        </tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="col-lg-4">
							<h2>獎金公式</h2>
							<div class="table-responsive">
								<table class="table table-bordered table-hover" id="formula-table" style="text-align:center;">
									<tbody>
                                        <? if(isset($data['info']) && $data['info']['alias'] == $this->qrcode_setting_model->appointedCaseAliasName) { ?>
                                        <tr style="background-color:#f5f5f5;">
                                            <td style="vertical-align: middle; text-align: center;">
                                                <p class="form-control-static">
                                                    上班族貸獎金公式<br>
                                                </p>
                                            </td>
                                            <td>
                                                <input type="text" disabled style="width: 120px" class="form-control number" data-category="salary_man" data-type="amount" value="<?= isset($data['info'])&&isset($data['info']['settings']['reward']['product']['salary_man'])?$data['info']['settings']['reward']['product']['salary_man']['amount']??"0":"0" ?>">
                                            </td>
                                        </tr>
                                        <tr style="background-color:#f5f5f5;">
                                            <td style="vertical-align: middle; text-align: center;">
                                                <p class="form-control-static">
                                                    上班族貸服務費獎金公式<br>
                                                </p>
                                            </td>
                                            <td>
                                                <input type="text" disabled style="width: 120px" class="form-control percent" data-category="salary_man" data-type="amount" value="<?= isset($data['info'])&&isset($data['info']['settings']['reward']['product']['salary_man'])?$data['info']['settings']['reward']['product']['salary_man']['borrower_percent']??"0":"0" ?>%" maxlength="5">
                                            </td>
                                        </tr>
                                        <tr style="background-color:#f5f5f5;">
                                            <td style="vertical-align: middle; text-align: center;">
                                                <p class="form-control-static">
                                                    學生貸獎金公式<br>
                                                </p>
                                            </td>
                                            <td>
                                                <input type="text" disabled style="width: 120px" class="form-control number" data-category="student" data-type="amount" value="<?= isset($data['info'])&&isset($data['info']['settings']['reward']['product']['student'])?$data['info']['settings']['reward']['product']['student']['amount']??"0":"0" ?>">
                                            </td>
                                        </tr>
                                        <tr style="background-color:#f5f5f5;">
                                            <td style="vertical-align: middle; text-align: center;">
                                                <p class="form-control-static">
                                                    學生貸服務費獎金公式<br>
                                                </p>
                                            </td>
                                            <td>
                                                <input type="text" disabled style="width: 120px" class="form-control percent" data-category="student" data-type="amount" value="<?= isset($data['info'])&&isset($data['info']['settings']['reward']['product']['student'])?$data['info']['settings']['reward']['product']['student']['borrower_percent']??"0":"0" ?>%" maxlength="5">
                                            </td>
                                        </tr>
                                            <tr style="background-color:#f5f5f5;">
                                                <td style="vertical-align: middle; text-align: center;">
                                                    <p class="form-control-static">
                                                        微企貸獎金公式<br>
                                                    </p>
                                                </td>
                                                <td>
                                                    <input type="text" disabled style="width: 120px" class="form-control number" data-category="small_enterprise" data-type="amount" value="<?= isset($data['info'])&&isset($data['info']['settings']['reward']['product']['small_enterprise'])?$data['info']['settings']['reward']['product']['small_enterprise']['amount']??"0":"0" ?>">
                                                </td>
                                            </tr>
                                            <tr style="background-color:#f5f5f5;">
                                                <td style="vertical-align: middle; text-align: center;">
                                                    <p class="form-control-static">
                                                        微企貸服務費獎金公式<br>
                                                    </p>
                                                </td>
                                                <td>
                                                    <input type="text" disabled style="width: 120px" class="form-control percent" data-category="small_enterprise" data-type="amount" value="<?= isset($data['info'])&&isset($data['info']['settings']['reward']['product']['small_enterprise'])?$data['info']['settings']['reward']['product']['small_enterprise']['borrower_percent']??"0":"0" ?>%" maxlength="5">
                                                </td>
                                            </tr>
                                        <? } else { ?>
                                            <tr style="background-color:#f5f5f5;">
                                                <td style="vertical-align: middle; text-align: center;">
                                                    <p class="form-control-static">
                                                        上班族貸獎金公式<br>
                                                    </p>
                                                </td>
                                                <td>
<!--                                                    <input type="text" disabled style="width: 120px" class="form-control number" data-category="salary_man" data-type="amount" value="--><?php //= isset($data['info'])&&isset($data['info']['settings']['reward']['product']['salary_man'])?$data['info']['settings']['reward']['product']['salary_man']['amount']??"":"" ?><!--">-->
                                                    <input type="text" disabled style="width: 120px"
                                                           class="form-control number" data-category="salary_man"
                                                           data-type="amount" value="<?= $salaryManRewardAmount ?>">
                                                </td>
                                            </tr>
                                            <tr style="background-color:#f5f5f5;">
                                                <td style="vertical-align: middle; text-align: center;">
                                                    <p class="form-control-static">
                                                        學生貸獎金公式<br>
                                                    </p>
                                                </td>
                                                <td>
<!--                                                    <input type="text" disabled style="width: 120px" class="form-control number" data-category="student" data-type="amount" value="--><?php //= isset($data['info'])&&isset($data['info']['settings']['reward']['product']['student'])?$data['info']['settings']['reward']['product']['student']['amount']??"":"" ?><!--">-->
                                                    <input type="text" disabled style="width: 120px"
                                                           class="form-control number" data-category="student"
                                                           data-type="amount"
                                                           value="<?= $studentRewardAmount ?>">
                                                </td>
                                            </tr>
                                            <tr style="background-color:#f5f5f5;">
                                                <td style="vertical-align: middle; text-align: center;">
                                                    <p class="form-control-static">
                                                        微企貸獎金公式<br>
                                                    </p>
                                                </td>
                                                <td>
<!--                                                    <input type="text" disabled style="width: 120px" class="form-control number" data-category="small_enterprise" data-type="amount" value="--><?php //= isset($data['info']) && isset($data['info']['settings']['reward']['product']['small_enterprise']) ? $data['info']['settings']['reward']['product']['small_enterprise']['amount'] ?? "0" : "0" ?><!--">-->
                                                    <input type="text" disabled style="width: 120px"
                                                           class="form-control number" data-category="small_enterprise"
                                                           data-type="amount" value="<?= $smallEnterpriseAmount ?>">
                                                </td>
                                            </tr>
                                        <? } ?>

                                        <tr style="background-color:#f5f5f5;">
                                            <td style="vertical-align: middle; text-align: center;">
                                                <p class="form-control-static">
                                                    註冊+下載獎金公式<br>
                                                </p>
                                            </td>
                                            <td>
                                                <input type="text" disabled style="width: 120px" class="form-control number" data-category="full_member" data-type="amount" value="<?= isset($data['info'])&&isset($data['info']['settings']['reward']['full_member'])?$data['info']['settings']['reward']['full_member']['amount']??"":"" ?>">
                                            </td>
                                        </tr>
                                        <tr style="background-color:#f5f5f5;">
                                            <td style="vertical-align: middle; text-align: center;">
                                                <p class="form-control-static">
                                                    註冊獎金公式<br>
                                                </p>
                                            </td>
                                            <td>
                                                <input type="text" disabled style="width: 120px"
                                                       class="form-control number" data-category="registered"
                                                       data-type="amount"
                                                       value="<?= $data['info']['settings']['reward']['registered']['amount'] ?? '' ?>">
                                            </td>
                                        </tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
                    <div class="form-group">
                        <div class="align-items-end col-md-6">
                            <button class="btn btn-success mr-5 modified">修改</button>
                            <button class="btn btn-primary" onclick="change_submit()">確定</button>
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

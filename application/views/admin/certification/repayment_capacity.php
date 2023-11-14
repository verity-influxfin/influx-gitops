<?php
if ( ! in_array($status, [CERTIFICATION_STATUS_PENDING_TO_REVIEW, CERTIFICATION_STATUS_SUCCEED]))
{
    $disabled = 'disabled';
    $btn_hidden = 'hidden';
}
else
{
    $disabled = '';
    $btn_hidden = '';
}
$new_calculate_algo = isset($content['totalEffectiveDebt']);  // 還款力相關欄位是否使用新算法
?>
<style>
    .form-control-static {
        width: 100%;
    }

    .form-control-static span {
        width: 15%;
        position: relative;
        display: inline-block;
    }

    .form-control-static input {
        text-align: right;
    }
</style>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= $certification_type ?? '' ?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form role="form" method="post" action="/admin/certification/user_certification_edit">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <input type="hidden" name="id" value="<?= $id ?? 0; ?>">
                                    <input type="hidden" name="certification_id"
                                           value="<?= CERTIFICATION_REPAYMENT_CAPACITY; ?>">
                                </div>
                                <div class="form-group">
                                    <ol>
                                        <li>
                                            <div class="form-control-static">
                                                <span>長期擔保放款訂約金額：</span>
                                                <input type="text"
                                                       id="longAssure"
                                                       name="longAssure"
                                                       class="formData"
                                                       value="<?= $content['longAssure'] ?? 0 ?>" <?= $disabled ?>> 千元
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-control-static">
                                                <span>中期擔保放款訂約金額：</span>
                                                <input type="text"
                                                       id="midAssure"
                                                       name="midAssure"
                                                       class="formData"
                                                       value="<?= $content['midAssure'] ?? 0 ?>" <?= $disabled ?>> 千元
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-control-static">
                                                <span>長期放款訂約金額：</span>
                                                <input type="text"
                                                       id="long"
                                                       name="long"
                                                       class="formData"
                                                       value="<?= $content['long'] ?? 0 ?>" <?= $disabled ?>> 千元
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-control-static">
                                                <span>中期放款訂約金額：</span>
                                                <input type="text"
                                                       id="mid"
                                                       name="mid"
                                                       class="formData"
                                                       value="<?= $content['mid'] ?? 0 ?>" <?= $disabled ?>> 千元
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-control-static">
                                                <span>短期放款訂約金額：</span>
                                                <input type="text"
                                                       id="short"
                                                       name="short"
                                                       class="formData"
                                                       value="<?= $content['short'] ?? 0 ?>" <?= $disabled ?>> 千元
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-control-static">
                                                <span>助學貸款總訂約金額：</span>
                                                <input type="text"
                                                       id="studentLoans"
                                                       name="studentLoans"
                                                       class="formData"
                                                       value="<?= $content['studentLoans'] ?? 0; ?>" <?= $disabled ?> /> 千元；總筆數：
                                                <input type="text"
                                                       id="studentLoansCount"
                                                       name="studentLoansCount"
                                                       class="formData"
                                                       value="<?= $content['studentLoansCount'] ?? 0; ?>" <?= $disabled ?> />
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-control-static">
                                                <?php
                                                if ($new_calculate_algo)
                                                {
                                                ?>
                                                <span>至查詢日止借款總餘額：</span>
                                                <?php
                                                }
                                                else
                                                {
                                                ?>
                                                <span>借款總餘額：</span>
                                                <?php
                                                }
                                                ?>
                                                <input type="text"
                                                       id="liabilitiesWithoutAssureTotalAmount"
                                                       name="liabilitiesWithoutAssureTotalAmount"
                                                       class="formData"
                                                       value="<?= $content['liabilitiesWithoutAssureTotalAmount'] ?? 0; ?>" <?= $disabled ?> /> 元
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-control-static">
                                                <span>信用卡帳款總餘額：</span>
                                                <input type="text"
                                                       id="creditCard"
                                                       name="creditCard"
                                                       class="formData"
                                                       value="<?= $content['creditCard'] ?? 0 ?>" <?= $disabled ?> /> 元
                                            </div>
                                        </li>
                                        <?php
                                        if ($new_calculate_algo)
                                        {
                                        ?>
                                        <li>
                                            <div class="form-control-static">
                                                <span>信用借款+信用卡+現金卡總餘額：</span>
                                                <input type="text"
                                                       id="totalEffectiveDebt"
                                                       name="totalEffectiveDebt"
                                                       class="formData"
                                                       value="<?= $content['totalEffectiveDebt'] ?>" <?= $disabled ?>> 元
                                            </div>
                                        </li>
                                        <?php
                                        }
                                        ?>
                                    </ol>
                                </div>
                                <hr/>
                                <div class="form-group">
                                    <table class="table table-striped table-bordered table-hover">
                                        <tbody style="text-align: start;">
                                        <tr>
                                            <td>總共月繳：</td>
                                            <td><input type="text"
                                                       disabled
                                                       id="totalMonthlyPayment"
                                                       value="<?php echo isset($content['totalMonthlyPayment'])
                                                           ? (strpos($content['totalMonthlyPayment'], ',') === FALSE
                                                               ? number_format($content['totalMonthlyPayment'] * 1000)
                                                               : $content['totalMonthlyPayment'] . '千')
                                                           : '-'; ?>"
                                                       style="text-align: right;" /> 元
                                            </td>
                                            <td>薪資22倍：</td>
                                            <td><input type="text"
                                                       disabled
                                                       value="<?php echo ! empty($content['total_repayment']) && is_numeric($content['total_repayment'])
                                                           ? (strpos($content['total_repayment'], ',') === FALSE
                                                               ? number_format($content['total_repayment'] * 1000)
                                                               : $content['total_repayment'] . '千')
                                                           : '-'; ?>"
                                                       style="text-align: right;" /> 元
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>薪資收入：</td>
                                            <td><input type="text"
                                                       disabled
                                                       id="monthly_repayment"
                                                       value="<?php echo ! empty($content['monthly_repayment']) && is_numeric($content['monthly_repayment'])
                                                           ? (strpos($content['monthly_repayment'], ',') === FALSE
                                                               ? number_format($content['monthly_repayment'] * 1000)
                                                               : $content['monthly_repayment'] . '千')
                                                           : '-'; ?>"
                                                       style="text-align: right;"> 元
                                            </td>
                                            <td>負債比：</td>
                                            <td><input type="text"
                                                       disabled
                                                       id="debt_to_equity_ratio"
                                                       value="<?= $content['debt_to_equity_ratio'] ?? '-'; ?>"
                                                       style="text-align: right;"> %
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <hr/>
                                <div class="form-group">
                                    <label>驗證結果</label>
                                    <?php
                                    if ( ! empty($remark['verify_result']))
                                    {
                                        foreach ($remark['verify_result'] as $value)
                                        {
                                            echo '<p style="color:red;" class="form-control-static">' . $value . '</p>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-lg-12" <?= $btn_hidden ?>>
                                <div class="col-lg-6"></div>
                                <div class="col-lg-6">
                                    <button type="submit" class="btn btn-primary" style="float: right;">確認送出</button>
                                </div>
                            </div>
                        </div>
                    </form>
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
    $(document).ready(function () {

        $('.formData').change(function () {
            let num = get_number($(this).val());
            if (num !== 0) {
                $(this).val(num.toLocaleString());
            }

            if ($(this).attr('name') !== 'liabilitiesWithoutAssureTotalAmount') {
                // 總共月繳
                let result = get_totalMonthlyPayment();
                $('#totalMonthlyPayment').val(result.toLocaleString());

                // 負債比
                let monthly_repayment_num = get_number($('#monthly_repayment').val())
                $('#debt_to_equity_ratio').val(Math.round(
                    result / monthly_repayment_num * 100 * 100
                ) / 100);
            }
        }).each(function (index, element) {
            let num = get_number($(element).val());
            $(element).val(num.toLocaleString('en-US'));
        });

        $('form').submit(function () {
            let studentLoans = get_number($('#studentLoans').val());
            let studentLoansCount = get_number($('#studentLoansCount').val());

            if ((studentLoans !== 0 || studentLoansCount !== 0) &&
                (studentLoans * studentLoansCount === 0)
            ) {
                alert('助學貸款總訂約金額與總筆數，不可其中一個為0，另一個不為0');
                return false;
            }
        });
    });

    function get_number(_str) {
        let arr = _str.split('');
        let out = [];
        for (let cnt = 0; cnt < arr.length; cnt++) {
            if (isNaN(arr[cnt]) === false) {
                out.push(arr[cnt]);
            }
        }
        return Number(out.join(''));
    }

    // 計算總共月繳金額
    function get_totalMonthlyPayment() {
        let result = 0;

        $('.formData').each(function (index, element) {
            let input_value = get_number($(element).val());

            switch ($(element).attr('name')) {
                case 'longAssure': // 長期擔保放款月繳
                    result += Math.round(
                        input_value * (Math.pow(1.0020833, 300) * 0.0020833) / (Math.pow(1.0020833, 300) - 1) * 100
                    );
                    break;
                case 'midAssure': // 中期擔保放款月繳
                    result += Math.round(
                        input_value * (Math.pow(1.0041666, 84) * 0.0041666) / (Math.pow(1.0041666, 84) - 1) * 100
                    );
                    break;
                case 'long': // 長期放款月繳
                    result += Math.round(
                        input_value * (Math.pow(1.0020833, 300) * 0.0020833) / (Math.pow(1.0020833, 300) - 1) * 100
                    );
                    break;
                case 'mid': // 中期放款月繳
                    result += Math.round(
                        input_value * (Math.pow(1.0083333, 60) * 0.0083333) / (Math.pow(1.0083333, 60) - 1) * 100
                    );
                    break;
                case 'short': // 短期放款月繳
                    result += Math.round(
                        input_value * 0.0083333 * 100
                    );
                    break;
                case 'creditCard': // 信用卡月繳
                    result += Math.round(
                        input_value * 0.1 / 1000 * 100
                    );
                    break;
                case 'studentLoans': // 助學貸款月繳
                    let student_loans_count = get_number($('#studentLoansCount').val());

                    if (isNaN(student_loans_count) !== false || student_loans_count === 0) {
                        student_loans_count = 1;
                    }

                    result += Math.round(
                        input_value / (student_loans_count * 12) * 100
                    );
                    break;
            }
        });

        return result / 100 * 1000;
    }
</script>

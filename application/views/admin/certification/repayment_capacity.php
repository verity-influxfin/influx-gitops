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
                    <div class="row">
                        <table class="table table-striped table-bordered table-hover" border="1">
                            <tbody style="text-align:start;">
                            <tr>
                                <td>還款力計算</td>
                                <td colspan="2">
                                    <p>
                                        長期擔保放款：<?= isset($data['long_assure_monthly_payment']) ? (strpos($data['long_assure_monthly_payment'], ',') === FALSE ? number_format($data['long_assure_monthly_payment'] * 1000) : $data['long_assure_monthly_payment'].'千元') : '-'; ?></p>
                                    <p>
                                        中期擔保放款：<?= isset($data['mid_assure_monthly_payment']) ? (strpos($data['mid_assure_monthly_payment'], ',') === FALSE ? number_format($data['mid_assure_monthly_payment'] * 1000) : $data['mid_assure_monthly_payment'].'千元') : '-'; ?></p>
                                    <p>
                                        長期放款：<?= isset($data['long_monthly_payment']) ? (strpos($data['long_monthly_payment'], ',') === FALSE ? number_format($data['long_monthly_payment'] * 1000) : $data['long_monthly_payment'].'千元') : '-'; ?></p>
                                    <p>
                                        中期放款：<?= isset($data['mid_monthly_payment']) ? (strpos($data['mid_monthly_payment'], ',') === FALSE ? number_format($data['mid_monthly_payment'] * 1000) : $data['mid_monthly_payment'].'千元') : '-'; ?></p>
                                    <p>
                                        短期放款：<?= isset($data['short_monthly_payment']) ? (strpos($data['short_monthly_payment'], ',') === FALSE ? number_format($data['short_monthly_payment'] * 1000) : $data['short_monthly_payment'].'千元') : '-'; ?></p>
                                    <p>
                                        助學貸款月繳：<?= isset($data['student_loans_monthly_payment']) ? (strpos($data['student_loans_monthly_payment'], ',') === FALSE ? number_format($data['student_loans_monthly_payment'] * 1000) : $data['student_loans_monthly_payment'].'千元') : '-'; ?></p>
                                    <p>
                                        信用卡月繳：<?= isset($data['credit_card_monthly_payment']) ? (strpos($data['credit_card_monthly_payment'], ',') === FALSE ? number_format($data['credit_card_monthly_payment'] * 1000) : $data['credit_card_monthly_payment'].'千元') : '-'; ?></p>
                                    <p>
                                        總共月繳：<?= isset($data['total_monthly_payment']) ? (strpos($data['total_monthly_payment'], ',') === FALSE ? number_format($data['total_monthly_payment'] * 1000) : $data['total_monthly_payment'].'千元') : '-'; ?></p>
                                    <p>
                                        是否小於投保薪資：<?= $data['monthly_repayment_enough'] ?? '-'; ?></p>
                                    <p>
                                        <span>投保薪資：<?= isset($data['monthly_repayment']) ? (strpos($data['monthly_repayment'], ',') === FALSE ? number_format($data['monthly_repayment'] * 1000) : $data['monthly_repayment'].'千元') : '-'; ?></span>；
                                        <span>總共月繳：<?= isset($data['total_monthly_payment']) ? (strpos($data['total_monthly_payment'], ',') === FALSE ? number_format($data['total_monthly_payment'] * 1000) : $data['total_monthly_payment'].'千元') : '-'; ?></span>
                                    </p>
                                    <p>
                                        是否小於薪資22倍：<?= $data['total_repayment_enough'] ?? '-'; ?></p>
                                    <p>
                                        <span>薪資22倍：<?= isset($data['total_repayment']) ? (strpos($data['total_repayment'], ',') === FALSE ? number_format($data['total_repayment'] * 1000) : $data['total_repayment'].'千元') : '-'; ?></span>；
                                        <span>借款總餘額：<?= isset($data['liabilities_without_assure_total_amount']) && is_numeric($data['liabilities_without_assure_total_amount']) ? (strpos($data['liabilities_without_assure_total_amount'], ',') === FALSE ? number_format($data['liabilities_without_assure_total_amount']) : $data['liabilities_without_assure_total_amount']) : '-'; ?></span>
                                    </p>
                                    <p>
                                        <span>負債比計算：<?= $data['debt_to_equity_ratio'] ?? '-'; ?>%</span>
                                    </p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
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

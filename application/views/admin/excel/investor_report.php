<style>
    table {
        border-collapse: collapse;
    }

    table, th, td {
        border: 1px black;
    }
</style>
<table>
    <thead>

    </thead>
    <tbody>
    <tr>
        <th style="color:red">投資人</th><th><?= $data['basic_info']['id'] ?? '' ?></th><th style="color:red">報表日期</th><th><?=$data['basic_info']['export_date']?></th>
    </tr>
    <tr>
        <th style="color:red">首筆投資</th><th><?= $data['basic_info']['first_invest_date'] ?></th><th style="color:red">投資金額</th><th><?= number_format($data['basic_info']['invest_amount']) ?></th>
    </tr>
    <tr>
        <th></th>
    </tr>
    <tr>
        <th>(一)資產概況</th>
    </tr>
    <tr>
        <th>投資產品</th>
        <? foreach (array_column($data['assets_description'], 'name') as $type) { ?>
            <th><?= $type ?>
        <? } ?>
    </tr>
    <tr>
        <th>正常還款中</th>
        <? foreach (array_column($data['assets_description'], 'amount_not_delay') as $amount_not_delay) { ?>
        <th><?= number_format($amount_not_delay) ?>
        <? } ?>
    </tr>
    <tr>
        <th>逾期中</th>
        <? foreach (array_column($data['assets_description'], 'amount_delay') as $amount_delay) { ?>
        <th><?= number_format($amount_delay) ?>
            <? } ?>
    </tr>
    <tr>
        <th>本金餘額</th>
        <? foreach (array_column($data['assets_description'], 'total_amount') as $total_amount) { ?>
        <th><?= number_format($total_amount) ?>
            <? } ?>
    </tr>
    <tr>
        <th></th>
    </tr>
    <tr>
        <th>(二)投資績效</th>
    </tr>
    <tr>
        <th>科目</th><th>績效</th>
    </tr>
    <tr>
        <th>投資年資</th>
        <th><?= $data['invest_performance']['years'] ?></th>
    </tr>
    <tr>
        <th>2021上半年</th>
        <th>?? %</th>
    </tr>
    <tr>
        <th>平均本金餘額</th>
        <th><?= number_format($data['invest_performance']['average_principle']) ?></th>
    </tr>
    <tr>
        <th>扣除逾期之折現收益</th>
        <th><?= number_format($data['invest_performance']['return_discount_without_delay']) ?></th>
    </tr>
    <tr>
        <th>折現年化報酬率</th>
        <th><?= $data['invest_performance']['discount_rate_of_return'] ?>%</th>
    </tr>
    <tr>
        <th></th>
    </tr>
    <tr>
        <th>(三)已實現收益率</th>
    </tr>
    <tr>
        <th rowspan="2">期間</th><th rowspan="2">本金均額</th><th colspan="5">收入</th><th>支出</th><th rowspan="2">總收益</th><th>報酬率</th>
    </tr>
    <tr>
        <th>利息收入</th>
        <th>提還利息</th>
        <th>逾期償還利息</th>
        <th>延滯息</th>
        <th>補貼息</th>
        <th>回款手收</th>
        <th></th>
    </tr>
    <? foreach ($data['realized_rate_of_return'] as $info) { ?>
        <tr>
            <th><?= $info['range_title'] ?></th>
            <th><?= number_format($info['average_principle']) ?></th>
            <th><?= number_format($info['interest']) ?></th>
            <th><?= number_format($info['prepaid_interest']) ?></th>
            <th><?= number_format($info['delayed_paid_interest']) ?></th>
            <th><?= number_format($info['delayed_interest']) ?></th>
            <th><?= number_format($info['allowance']) ?></th>
            <th><?= number_format($info['platform_fee']) ?></th>
            <th><?= number_format($info['total_income']) ?></th>
            <th><?= $info['rate_of_return'] ?>%</th>
        </tr>
    <? } ?>
    <tr>
        <th colspan="7" style="text-align: left">1.總收益=(利息收入+提還利息+逾期償還利息+延滯息+補貼息)-回款手收</th>
    </tr>
    <tr>
        <th colspan="7" style="text-align: left">2.報酬率=當期(總收益/本金均額)</th>
    </tr>
    <tr>
        <th colspan="7" style="text-align: left">3.本金均額=年度每月底本金餘額加總/期數</th>
    </tr>
    <tr>
        <th></th>
    </tr>
    <tr>
        <th>(四)待實現應收利息</th>
    </tr>
    <tr>
        <th>期間</th>
        <th>金額</th>
        <th>折現</th>
    </tr>
    <? foreach ($data['account_payable_interest'] as $info) { ?>
        <tr>
            <th><?= $info['range_title'] ?></th>
            <th><?= number_format($info['amount']) ?></th>
            <th><?= number_format($info['discount_amount']) ?></th>
        </tr>
    <? } ?>
    <tr>
        <th>內部報酬率預估</th><th colspan="2"><?= $data['estimate_IRR'] ?>%</th>
    </tr>
    <tr>
        <th></th>
    </tr>
    <tr>
        <th>(五)逾期未收</th>
    </tr>
    <tr>
        <th>科目</th>
        <th>金額</th>
    </tr>
    <tr>
        <th>逾期-尚欠本息</th>
        <th><?= number_format($data['delay_not_return']['principal_and_interest'] ?? 0) ?></th>
    </tr>
    <tr>
        <th>逾期-尚欠延滯息</th>
        <th><?= number_format($data['delay_not_return']['delay_interest'] ?? 0) ?></th>
    </tr>
    <tr>
        <th>合計</th>
        <th><?= number_format( $data['delay_not_return']['total'] ?? 0) ?></th>
    </tr>
    </tbody>
</table>
<div id="page-wrapper">
    <div class="row">
        <div class="col-xs-12">
            <h1 class="page-header d-flex justify-between">
                <div>績效統計表</div>
            </h1>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="d-flex">
                <div class="search-btn">

                    <div>
                        KPI 指標&nbsp;&nbsp;
                        <input type="month" id="yearmonth" value="<?=$goal_ym?>" />
                        <button class="btn btn-primary" onclick="get_goals()">查詢</button>
                        <button class="btn btn-primary" onclick="report_export()">下載</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-3">
            <table  class="display responsive nowrap" width="100%" id="dataTables-paging">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="font-weight:bold;font-size:16px;" width="100%">
                            <thead>
                                <tr><th colspan="2">業務推廣</th><?=$trtd_date?></tr>
                            </thead>
                            <tbody>
                                <tr><td rowspan="3">官網流量</td><td>目標流量</td><?=$datas[0]['goal']?></tr>
                                <tr><td>實際流量</td><?=$datas[0]['real']?></tr>
                                <tr><td>達成率</td><?=$datas[0]['rate']?></tr>

                                <tr><td rowspan="3">會員註冊</td><td>目標會員數</td><?=$datas[3]['goal']?></tr>
                                <tr><td>實際總會員數</td><?=$datas[3]['real']?></tr>
                                <tr><td>達成率</td><?=$datas[3]['rate']?></tr>

                                <tr><td rowspan="3">APP下載</td><td>目標下載數</td><?=$datas[1]['goal']?></tr>
                                <tr><td>實際下載數</td><?=$datas[1]['real']?></tr>
                                <tr><td>達成率</td><?=$datas[1]['rate']?></tr>

                                <tr><td rowspan="3">申貸總計</td><td>目標申貸戶數</td><?=$datas[2]['goal']?></tr>
                                <tr><td>實際完成申貸戶數</td><?=$datas[2]['real']?></tr>
                                <tr><td>達成率</td><?=$datas[2]['rate']?></tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered" style="font-weight:bold;font-size:16px;" width="100%">
                            <thead>
                                <tr><th colspan="2">申貸指標</th><?=$trtd_date?></tr>
                            </thead>
                            <tbody>
                                <tr><td rowspan="3">上班族貸</td><td>目標申貸戶數</td><?=$datas[4]['goal']?></tr>
                                <tr><td>實際申貸戶數</td><?=$datas[4]['real']?></tr>
                                <tr><td>達成率</td><?=$datas[4]['rate']?></tr>

                                <tr><td rowspan="3">學生貸</td><td>目標申貸戶數</td><?=$datas[5]['goal']?></tr>
                                <tr><td>實際申貸戶數</td><?=$datas[5]['real']?></tr>
                                <tr><td>達成率</td><?=$datas[5]['rate']?></tr>

                                <tr><td rowspan="3">3S名校貸</td><td>目標申貸戶數</td><?=$datas[6]['goal']?></tr>
                                <tr><td>實際申貸戶數</td><?=$datas[6]['real']?></tr>
                                <tr><td>達成率</td><?=$datas[6]['rate']?></tr>

                                <tr><td rowspan="3">信保專案</td><td>目標申貸戶數</td><?=$datas[10]['goal']?></tr>
                                <tr><td>實際申貸戶數</td><?=$datas[10]['real']?></tr>
                                <tr><td>達成率</td><?=$datas[10]['rate']?></tr>

                                <tr><td rowspan="3">中小企業</td><td>目標申貸戶數</td><?=$datas[11]['goal']?></tr>
                                <tr><td>實際申貸戶數</td><?=$datas[11]['real']?></tr>
                                <tr><td>達成率</td><?=$datas[11]['rate']?></tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered" style="font-weight:bold;font-size:16px;" width="100%">
                            <thead>
                                <tr><th colspan="2">成交指標</th><?=$trtd_date?></tr>
                            </thead>
                            <tbody>
                                <tr><td rowspan="3">上班族貸</td><td>目標成交筆數</td><?=$datas[7]['goal']?></tr>
                                <tr><td>實際成交筆數</td><?=$datas[7]['real']?></tr>
                                <tr><td>達成率</td><?=$datas[7]['rate']?></tr>

                                <tr><td rowspan="3">學生貸</td><td>目標成交筆數</td><?=$datas[8]['goal']?></tr>
                                <tr><td>實際成交筆數</td><?=$datas[8]['real']?></tr>
                                <tr><td>達成率</td><?=$datas[8]['rate']?></tr>

                                <tr><td rowspan="3">3S名校貸</td><td>目標成交筆數</td><?=$datas[9]['goal']?></tr>
                                <tr><td>實際成交筆數</td><?=$datas[9]['real']?></tr>
                                <tr><td>達成率</td><?=$datas[9]['rate']?></tr>

                                <tr><td rowspan="3">信保專案</td><td>目標成交筆數</td><?=$datas[12]['goal']?></tr>
                                <tr><td>實際成交筆數</td><?=$datas[12]['real']?></tr>
                                <tr><td>達成率</td><?=$datas[12]['rate']?></tr>

                                <tr><td rowspan="3">中小企業</td><td>目標成交筆數</td><?=$datas[13]['goal']?></tr>
                                <tr><td>實際成交筆數</td><?=$datas[13]['real']?></tr>
                                <tr><td>達成率</td><?=$datas[13]['rate']?></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    function get_goals(){
        top.location = "<?=base_url();?>"+'admin/Sales/sales_report?goal_ym='+$('#yearmonth').val();
    }
    function report_export(){
        top.location = "<?=base_url();?>"+'admin/Sales/goals_export?goal_ym='+$('#yearmonth').val();
    }
</script>
<style>
    table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
    .d-flex {
        display: flex;
        align-items: center;
    }

    .justify-between {
        justify-content: space-between;
    }

    .search-btn {
        display: flex;
        justify-content: flex-start;
        flex: 1 0 auto;
    }
</style>
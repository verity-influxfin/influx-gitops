<script src="<?=base_url()?>assets/admin/js/common/amountconvertor.js"></script>
<script src="<?=base_url()?>assets/admin/js/mapping/report/loan/loantable.js"></script>
<script src="<?=base_url()?>assets/admin/js/mapping/report/loan/loanrow.js"></script>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">申貸總覽</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <form id="search-report" method="GET" action="/admin/sales/report_overview">
                    <table>
                        <tr>
                            <td>申貸區間：</td>
                            <td><a id="loan-range-today" target="_self" class="btn btn-default float-right btn-md" >本日</a></td>
                            <td><a id="loan-range-all" target="_self" class="btn btn-default float-right btn-md" >全部</a></td>
                            <td class="center-text gap">|</td>
                            <td><input id="loan_sdate" name="loan_sdate" type="text" data-toggle="datepicker"/></td>
                            <td>-</td>
                            <td><input id="loan_edate" name="loan_edate" type="text" data-toggle="datepicker"/></td>
                            <td class="center-text gap">|</td>
                        </tr>
                        <tr>
                            <td>轉化區間：</td>
                            <td><a id="conversion-range-today" target="_self" class="btn btn-default float-right btn-md" >本日</a></td>
                            <td><a id="conversion-range-all" target="_self" class="btn btn-default float-right btn-md" >全部</a></td>
                            <td class="center-text gap">|</td>
                            <td><input id="conversion_sdate" name="conversion_sdate" data-toggle="datepicker"/></td>
                            <td>-</td>
                            <td><input id="conversion_edate" name="conversion_edate" data-toggle="datepicker"/></td>
                            <td class="center-text gap">|</td>
                            <td><button class="btn btn-default float-right btn-md" type="submit">查詢</button></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="panel panel-default">
                <table id="total" class="table table-bordered">
                    <tr>
                        <td>總計</td>
                    </tr>
                    <tr>
                        <td class="center-text" width="9%">身份</td>
                        <td class="center-text" width="8%">申貸戶</td>
                        <td class="center-text" width="8%">核可未簽約戶</td>
                        <td class="center-text" width="8%">上架中戶</td>
                        <td class="center-text" width="8%">成交戶數</td>
                        <td class="center-text" width="13%">成交率（成交戶/申貸戶）</td>
                        <td class="center-text" width="8%">申請案</td>
                        <td class="center-text" width="8%">成交筆數</td>
                        <td class="center-text" width="10%">核可未簽約金額</td>
                        <td class="center-text" width="10%">上架中金額</td>
                        <td class="center-text" width="10%">成交金額</td>
                    </tr>
                </table>
                </br></br>
                <table id="credit-loan" class="table table-bordered">
                    <tr>
                        <td>信用貸款</td>
                    </tr>
                    <tr>
                        <td class="center-text" width="9%">身份</td>
                        <td class="center-text" width="8%">申貸戶</td>
                        <td class="center-text" width="8%">核可未簽約戶</td>
                        <td class="center-text" width="8%">上架中戶</td>
                        <td class="center-text" width="8%">成交戶數</td>
                        <td class="center-text" width="13%">成交率（成交戶/申貸戶）</td>
                        <td class="center-text" width="8%">申請案</td>
                        <td class="center-text" width="8%">成交筆數</td>
                        <td class="center-text" width="10%">核可未簽約金額</td>
                        <td class="center-text" width="10%">上架中金額</td>
                        <td class="center-text" width="10%">成交金額</td>
                    </tr>
                </table>
                </br></br>
                <table id="techi-loan" class="table table-bordered">
                    <tr>
                        <td>工程師貸</td>
                    </tr>
                    <tr>
                        <td class="center-text" width="9%">身份</td>
                        <td class="center-text" width="8%">申貸戶</td>
                        <td class="center-text" width="8%">核可未簽約戶</td>
                        <td class="center-text" width="8%">上架中戶</td>
                        <td class="center-text" width="8%">成交戶數</td>
                        <td class="center-text" width="13%">成交率（成交戶/申貸戶）</td>
                        <td class="center-text" width="8%">申請案</td>
                        <td class="center-text" width="8%">成交筆數</td>
                        <td class="center-text" width="10%">核可未簽約金額</td>
                        <td class="center-text" width="10%">上架中金額</td>
                        <td class="center-text" width="10%">成交金額</td>
                    </tr>
                </table>
                </br></br>
                <table id="mobile-phone-loan" class="table table-bordered">
                    <tr>
                        <td>手機貸</td>
                    </tr>
                    <tr>
                        <td class="center-text" width="9%">身份</td>
                        <td class="center-text" width="8%">申貸戶</td>
                        <td class="center-text" width="8%">核可未簽約戶</td>
                        <td class="center-text" width="8%">上架中戶</td>
                        <td class="center-text" width="8%">成交戶數</td>
                        <td class="center-text" width="13%">成交率（成交戶/申貸戶）</td>
                        <td class="center-text" width="8%">申請案</td>
                        <td class="center-text" width="8%">成交筆數</td>
                        <td class="center-text" width="10%">核可未簽約金額</td>
                        <td class="center-text" width="10%">上架中金額</td>
                        <td class="center-text" width="10%">成交金額</td>
                    </tr>
                </table>
                </br></br>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var isFetching = false;
        var today = new Date();
        var todayString = today.getFullYear() + "-" + (today.getMonth()+1) + "-" + today.getDate();

        $('input[name=loan_sdate]').val(todayString);
        $('input[name=loan_edate]').val(todayString);
        $('input[name=conversion_sdate]').val(todayString);
        $('input[name=conversion_edate]').val(todayString);

        function fillFakeReports(show = true) {
            var tables = ["total", "credit-loan", "techi-loan", "mobile-phone-loan"];
            for (var i = 0; i < tables.length; i++) {
                fillFakeReport(tables[i], show);
            }
        }

        function fillFakeReport(table, show = true) {
            var lastIndex = 1
            $("#" + table + " tr:gt(" + lastIndex + ")").remove();
            if (!show) {
                return;
            }

            pTag = '<p class="form-control-static"></p>';
            for (var i = 0; i < 5; i++) {
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
                    $('<td class="fake-fields center-text">').append(pTag),
                    $('<td class="fake-fields center-text">').append(pTag),
                ).appendTo("#" + table);
            }
        }

        function fillReport(table, tableName) {
            for (var i = 0; i < table.rows.length; i++) {
                var row = "";
                if (i % 2 == 0) {
                    row = '<td rowspan=2 class="center-text">'
                }
                $("<tr>").append(
                    $('<td class="center-text">').append(table.rows[i].name),
                    $('<td class="center-text">').append(table.rows[i].applicants),
                    $('<td class="center-text">').append(table.rows[i].pendingSigningApplicants),
                    $('<td class="center-text">').append(table.rows[i].onTheMarket),
                    $('<td class="center-text">').append(table.rows[i].matchedApplicants),
                    $('<td class="center-text">').append(table.rows[i].matchRate),
                    $(row).append(table.rows[i].applications),
                    $(row).append(table.rows[i].matchedApplications),
                    $('<td class="center-text">').append(table.rows[i].approvedPendingSigningAmount),
                    $('<td class="center-text">').append(table.rows[i].onTheMarketAmount),
                    $('<td class="center-text">').append(table.rows[i].matchedAmount),
                ).appendTo("#" + tableName);
            }
        }

        function fetchOverviewReport(createdStart, createdEnd, convertedStart, convertedEnd) {
            var query = {
                'loan_sdate' : createdStart,
                'loan_edate' : createdEnd,
                'conversion_sdate' : convertedStart,
                'conversion_edate' : convertedEnd,
            };

            var queryString = $.param(query);

            $.ajax({
                type: "GET",
                url: "/admin/sales/loan_overview?" + queryString,
                beforeSend: function () {
                    isFetching = true;
                },
                complete: function () {
                    isFetching = false;
                },
                success: function (response) {
                    fillFakeReports(false);
                    if (response.status.code != 200 && response.status.code != 204) {
                        return;
                    } else if (response.status.code == 204) {
                        alert('資料不存在');
                        window.close();
                        return;
                    }
                    var amountConvertor = new AmountConvertor();

                    var totalTable = new LoanTable(response.response.total_table, amountConvertor);
                    fillReport(totalTable, 'total');
                    var totalTable = new LoanTable(response.response.credit_loan_table, amountConvertor);
                    fillReport(totalTable, 'credit-loan');
                    var totalTable = new LoanTable(response.response.techi_loan_table, amountConvertor);
                    fillReport(totalTable, 'techi-loan');
                    var totalTable = new LoanTable(response.response.mobile_phone_loan_table, amountConvertor);
                    fillReport(totalTable, 'mobile-phone-loan');
                },
                error: function(error) {
                    alert('資料載入失敗。請重新整理。');
                }
            });
        }

        $("#search-report").submit(function(e) {
            e.preventDefault();

            if (isFetching) {
                return;
            }

            var form = $(this);
            var url = form.attr('action');
            var loanStartAt = form.find('input[name="loan_sdate"]').val();
            var loanEndAt = form.find('input[name="loan_edate"]').val();
            var convertedStartAt = form.find('input[name="conversion_sdate"]').val();
            var convertedEndAt = form.find('input[name="conversion_edate"]').val();

            fillFakeReports();
            fetchOverviewReport(loanStartAt, loanEndAt, convertedStartAt, convertedEndAt);
        });

        $('#loan-range-today').click(function() {
            var today = new Date();
            var todayString = today.getFullYear() + "-" + (today.getMonth()+1) + "-" + today.getDate();

            $('input[name=loan_sdate]').val(todayString);
            $('input[name=loan_edate]').val(todayString);
        });
        $('#loan-range-all').click(function() {
            var today = new Date();
            var todayString = today.getFullYear() + "-" + (today.getMonth()+1) + "-" + today.getDate();

            $('input[name=loan_sdate]').val('2018-08-03');
            $('input[name=loan_edate]').val(todayString);
        });
        $('#conversion-range-today').click(function() {
            var today = new Date();
            var todayString = today.getFullYear() + "-" + (today.getMonth()+1) + "-" + today.getDate();

            $('input[name=conversion_sdate]').val(todayString);
            $('input[name=conversion_edate]').val(todayString);
        });
        $('#conversion-range-all').click(function() {
            var today = new Date();
            var todayString = today.getFullYear() + "-" + (today.getMonth()+1) + "-" + today.getDate();

            $('input[name=conversion_sdate]').val('2018-08-03');
            $('input[name=conversion_edate]').val(todayString);
        });
    });
</script>

<style>
    .center-text {
        text-align: center;
    }

    .gap {
        width: 30px;
    }

    @keyframes placeHolderShimmer{
        0% {
            background: #ececec;
        }

        30% {
            background: #F7F7F7;
        }

        50% {
            background: #ececec;
        }

        80% {
            background: #F7F7F7;
        }

        100% {
            background: #ececec;
        }
    }

    .fake-fields p {
        animation-duration: 3.25s;
        animation-fill-mode: forwards;
        animation-iteration-count: infinite;
        animation-name: placeHolderShimmer;
        animation-timing-function: linear;
        background: darkgray;
        background: linear-gradient(to right, #eeeeee 10%, #dddddd 18%, #eeeeee 33%);
        background-size: 800px 104px;
        height: 20px;
        position: relative;
        border-radius: 25px;
    }
</style>
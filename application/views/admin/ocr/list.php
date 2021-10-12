<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">OCR 列表</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default center-text">
                <table>
                    <tr>
                        <td>報表：</td>
                        <td colspan="5">
                            <select id="reportType">
                                <option value="">請選擇報表</option>
                                <option value='balance_sheets'>資產負債表</option>
                                <option value='income_statements'>損益表</option>
                                <option value='business_tax_return_reports'>401</option>
																<option value='insurance_tables'>投保單位人數資料表</option>
																<option value='amendment_of_registers'>變卡</option>
																<option value='credit_investigations'>聯徵</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <table id="reports" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="20">序號</th>
                            <th width="30%">id</th>
                            <th width="30%">狀態</th>
                            <th width="20%">細節</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <button id="load-more" class="btn btn-default">載入更多</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var count = 0;
        var offset = 1;
        var limit = 60;
        $("#load-more").hide();

        $("#reportType"). change(function(){
            $("#reports tr:gt(0)").remove();

            var type = $( "#reportType" ).val();
            if (!type) return;
            fetchReports(type, 1, limit, function(data) {
                offset = data['offset'];
                count = data['count'];
                if ((offset-1) * limit > count) {
                    $("#load-more").hide();
                } else {
                    $("#load-more").show();
                }
            });
        });

        $('#load-more').on('click', function() {
            if ((offset-1) * limit > count) {
                return;
            }
            var type = $( "#reportType" ).val();
            if (!type) return;
            fetchReports(type, offset, limit, function(data) {
                offset = data['offset'];
                count = data['count'];
                if ((offset-1) * limit > count) {
                    $("#load-more").hide();
                } else {
                    $("#load-more").show();
                }
            });
        });

        function fetchReports(type, offset, limit, callback) {
            $.ajax({
				type: "GET",
				url: "/admin/ocr/reports" + "?type=" + type + "&offset=" + offset + "&limit=" + limit,
				beforeSend: function () {
				},
				complete: function () {
				},
				success: function (response) {
                    var reports;
                    if ('balance_sheet_logs' in response.response) {
                        reports = response.response.balance_sheet_logs;
                    } else if ('income_statement_logs' in response.response) {
                        reports = response.response.income_statement_logs;
                    } else if ('business_tax_return_logs' in response.response) {
                        reports = response.response.business_tax_return_logs;
                    } else if ('insurance_table_logs' in response.response) {
                        reports = response.response.insurance_table_logs;
                    } else if ('amendment_of_register_logs' in response.response) {
											reports = response.response.amendment_of_register_logs;
										} else if ('credit_investigation_logs' in response.response) {
											reports = response.response.credit_investigation_logs;
										}
                    fillReports(reports.items, offset, limit);
                    callback({'offset' : offset+1, 'count' : reports.count});
                    return;
				},
                error: function () {
                    callback({'offset' : offset, 'count' : 0});
                }
			});
        }

        function fillReports(reports, offset, limit) {
            var start = 0;
            var end = reports.length;
            for (var i = start; i < end; i++) {
                var id = reports[i].id;
                var status = reports[i].status;
								var url = reports[i].url;
								var hasCheck = 0;
                if ('balance_sheet' in reports[i]) {
                    reportType = 'balance_sheet';
										// hasCheck = 1;
                } else if ('business_tax_return' in reports[i]) {
                    reportType = 'business_tax_return';
                } else if ('income_statement' in reports[i]) {
                    reportType = 'income_statement';
										// hasCheck = 1;
                } else if ('insurance_table' in reports[i]) {
                    reportType = 'insurance_table';
                } else if ('amendment_of_register' in reports[i]) {
									reportType = 'amendment_of_register';
								} else if ('credit_investigation' in reports[i]) {
									reportType = 'credit_investigation';
								}

                var detail = '<a target="_blank" href="/admin/ocr/report?id=' + id + '&type=' + reportType + '&check=' + hasCheck +'"><button class="btn btn-info">詳情</button></a>';
								if(reportType=='amendment_of_register'){
									var link = ''
									if(url){
										Object.keys(url).forEach(function(key) {
											link += '<a data-fancybox="images" target="_blank" href="'+ url[key] +'">'+key +'</a>,'
			              })
									}
								}else{
									var link = '<a data-fancybox="images" target="_blank" href="'+ url +'">'+id +'</a>';
								}
                $("<tr>").append(
                    $('<td class="center-text">').append((offset-1) * limit + i + 1),
                    $('<td class="center-text">').append(link),
                    $('<td class="center-text">').append(status),
                    $('<td class="center-text">').append(detail),
                ).appendTo("#reports");
            }
        }
    });
</script>
<style>
    .center-text {
        text-align: center;
    }
</style>

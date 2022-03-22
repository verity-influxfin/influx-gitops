<style lang="scss">
    .table-title {
        min-width: 75px;
        background-color: #f9f9f9;
    }

    .table-content {
        word-break: break-all;
    }

    .table-ellipsis {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
	.d-flex{
		display: flex;
	}
	.jcb{
		justify-content: space-between;
	}
	.aic{
		align-items: center;
	}
</style>
<script type="text/javascript">
    // SQL資料抓取
    function fetchInfoData(user_id) {
        $.ajax({
            type: "GET",
            url: "/admin/scraper/biz_br_info" + "?user_id=" + user_id,
            async: false,
            success: function (response) {
                if (response.status.code != 200) {
                    console.log(response.status.code)
                    return false;
                }
                bizAndBrInfo = response.response;
                fillInfoData(bizAndBrInfo);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest.status);
                console.log(XMLHttpRequest.readyState);
                console.log(textStatus);
            },
        });
    }

    // 爬蟲資料抓取
    function fetchBizData() {
        tax_id = $('#tax-id').text();
        $.ajax({
            type: "GET",
            url: "/admin/scraper/biz" + "?tax_id=" + tax_id,
            success: function (response) {
                if (response.status.code != 200) {
                    console.log(response.status.code)
                    return false;
                }
                bizData = response.response.result;
                fillFirstPageCompanyInfo(bizData);
                fillFirstPageDirectorInfo(bizData);
                fillHistory(bizData);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest.status);
                console.log(XMLHttpRequest.readyState);
                console.log(textStatus);
            },
        });
    }

    function getNewDate(str) {
        if (!str) {
            return false;
        }
        newDateStr = str.toString()
        if (newDateStr.length === 7)
            newDate = newDateStr.substring(0, 3) + "年" + newDateStr.substring(3, 5) + "月" + newDateStr.substring(5, 7) + "日"
        else
            newDate = newDateStr.substring(0, 2) + "年" + newDateStr.substring(2, 4) + "月" + newDateStr.substring(4, 6) + "日"
        return newDate;
    }

    function create_key_value_html(key, value) {
        html = `<tr>
					<th class="table-title">${key}</th>
					<td style=background-color:white;>${value}</td>
				</tr>`
        return html;
    }

    function create_director_info_html(infoKey, key, value) {
        html = `<tr>
					<th style=background-color:white; rowspan="5">${infoKey}</th>
					<th class="table-title">${key}</th>
					<td style=background-color:white;>${value}</td>
				</tr>`
        return html;
    }

    function create_risk_level_html(level, time, content) {
        html = `<div class="form-group">
                <table class="table table-bordered table-hover table-striped">
                    <tr>
                        <th class="table-title">風險等級</td>
                        <th class="table-title">事件時間</td>
                        <th class="table-title">指標內容</td>
                    </tr>
                    <tr>
                        <td>${level}</td>
                        <td>${time}</td>
                        <td>${content}</td>
                    </tr>
                </table>
            </div>`
        return html;
    }

    function fillInfoData(response) {
        if (!response) {
            return false;
        }
        $('#name').text(response.name);
        $('#birthday').text(response.birthday);
        $('#id-number').text(response.id_number);
        $('#companyName').text(response.companyName);
        $('#tax-id').text(response.tax_id);
        $('#insuranceSalary').text(response.insuranceSalary);
        $('#total-count').text(response.total_count);
        $('#this_company-count').text(response.this_company_count);
        $('#report-date').text(getNewDate(response.report_date));
    }

    function fillData(response, id) {
        htmls = '';
        Object.keys(response).forEach(function (infoKey) {
            if (typeof (response[infoKey]) != 'object') {
                key_value_html = create_key_value_html(infoKey, response[infoKey]);
                htmls += key_value_html;
            } else {
                contents_html = '';
                Object.keys(response[infoKey]).forEach(function (key) {
                    content_html = key + ':' + response[infoKey][key] + `<br>`;
                    contents_html += content_html;
                })
                key_value_html = create_key_value_html('所營事業資料', contents_html);
                htmls += key_value_html;
            }
        })
        table = `<div class="form-group">
					<table style="table-layout:fixed;" class="table table-bordered table-hover table-striped" id="${id}">
						<tbody>
							${htmls}
						</tbody>
					</table>
				</div>`
        $('#history').append(table);
    }

    function fillFirstPageCompanyInfo(response) {
        if (!response) {
            return false;
        }
        firstPageCompanyInfo = response.firstPage.firstPageCompanyInfo;
        Object.keys(firstPageCompanyInfo).forEach(function (infoKey) {
            if (typeof (firstPageCompanyInfo[infoKey]) != 'object') {
                key_value_html = create_key_value_html(infoKey, firstPageCompanyInfo[infoKey]);
                $('#first-page-company-info').append(key_value_html);
            } else {
                contents_html = '';
                Object.keys(firstPageCompanyInfo[infoKey]).forEach(function (key) {
                    content_html = key + ':' + firstPageCompanyInfo[infoKey][key] + `<br>`;
                    contents_html += content_html;
                })
                html = create_key_value_html('所營事業資料', contents_html);
                $('#first-page-company-info').append(html);
            }
        })
    }

    function fillFirstPageDirectorInfo(response) {
        if (!response) {
            return false;
        }
        firstPageDirectorInfo = response.firstPage.firstPageDirectorInfo;
        // console.log(firstPageDirectorInfo);
        Object.keys(firstPageDirectorInfo).forEach(function (infoKey) {
            if (typeof (firstPageDirectorInfo[infoKey]) == 'object') {
                htmls = ''
                Object.keys(firstPageDirectorInfo[infoKey]).forEach(function (key) {
                    if (key == '序號') {
                        html = create_director_info_html(infoKey, key, firstPageDirectorInfo[infoKey][key]);
                    } else {
                        html = create_key_value_html(key, firstPageDirectorInfo[infoKey][key]);
                    }
                    htmls += html;
                })
                $('#first-page-director-info').append(htmls);
            }
        })
    }

    function fillHistory(response) {
        if (!response) {
            return false;
        }
        historyList = response.history;
        i = 0
        historyList.forEach(function (item) {
            fillData(item, i);
            i += 1;
        })
    }

    function fillRiskLevelData(response) {
        if (!response) {
            return false;
        }
        riskLevelResponse.forEach((response) => {
            level_html = create_risk_level_html(response.level, response.time, response.content);
            $('#risk-level').append(level_html);
        })
    }

    $(document).ready(function () {
        let urlString = window.location.href;
        let url = new URL(urlString);
        let user_id = url.searchParams.get("user_id");
        setTimeout(fetchInfoData(user_id), 1000);
        setTimeout(fetchBizData(), 1000);

		$('#redo').on('click', () => {
            if (confirm('是否確定重新執行爬蟲？')) {
                axios.post('/admin/scraper/requestFindBizData', {
                    tax_id: $('#tax-id').text(),
                }).then(({ data }) => {
                    if (data.status == 200) {
                        location.reload()
                    }
                    else {
                        alert(data.error.code)
                    }
                })
            }
        })
    });
</script>
<div id="page-wrapper">
    <div class="d-flex jcb aic page-header">
        <div>
            <h1>經濟部商業司資訊</h1>
        </div>
		<div>
			<scraper-status-icon :column="column"></scraper-status-icon>
			<button class="btn btn-danger" id="redo">重新執行爬蟲</button>
		</div>
    </div>
    <table class="table table-bordered table-hover table-striped">
        <tbody>
        <tr>
            <th class="table-title">資料內容（工作收入證明）</th>
        </tr>
        <tr>
            <td>
                <table class="table table-bordered table-hover table-striped">
                    <tr>
                        <th class="table-title">姓名</th>
                        <td style=background-color:white; id="name"></td>
                        <th class="table-title">投保單位名稱</th>
                        <td style=background-color:white; id="companyName"></td>
                        <th class="table-title">總工作年資</th>
                        <td style=background-color:white; id="total-count"></td>
                    </tr>
                    <tr>
                        <th class="table-title">出生年月日</th>
                        <td style=background-color:white; id="birthday"></td>
                        <th class="table-title">統一編號</th>
                        <td style=background-color:white; id="tax-id"></td>
                        <th class="table-title">現任職公司年資</th>
                        <td style=background-color:white; id="this_company-count"></td>
                    </tr>
                    <tr>
                        <th class="table-title">身分證字號</th>
                        <td style=background-color:white; id="id-number"></td>
                        <th class="table-title">投保薪資</th>
                        <td style=background-color:white; id="insuranceSalary"></td>
                        <th class="table-title">查詢日期起迄</th>
                        <td style=background-color:white; id="report-date"></td>
                    </tr>
                </table>
            </td>
        </tr>
        </tbody>
        <table>
            <table class="table table-bordered table-hover table-striped">
                <tr>
                    <th class="table-title">公司基本資料</th>
                </tr>
                <tr>
                    <td>
                        <table style="table-layout:fixed;" class="table table-bordered table-hover table-striped">
                            <tbody id="first-page-company-info"></tbody>
                        </table>
                    </td>
                </tr>
            </table>
            <table class="table table-bordered table-hover table-striped">
                <tr>
                    <th class="table-title">監董事資料</th>
                </tr>
                <tr>
                    <td>
                        <table style="table-layout:fixed;" class="table table-bordered table-hover table-striped">
                            <tbody id="first-page-director-info"></tbody>
                        </table>
                    </td>
                </tr>
            </table>
            <table class="table table-bordered table-hover table-striped">
                <tr>
                    <th class="table-title">歷史資料</th>
                </tr>
                <tr>
                    <td>
                        <table style="table-layout:fixed;" class="table table-bordered table-hover table-striped">
                            <div id="history"></div>
                        </table>
                    </td>
                </tr>
            </table>
        </table>
    </table>
</div>
<script>
	const v = new Vue({
		el:'#page-wrapper',
		computed: {
			column(){
				return 'biz_status'
			}
		},
	})
</script>

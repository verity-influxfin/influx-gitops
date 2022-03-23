<style lang="scss">
    .d-flex {
        display: flex;
    }

    .jcb {
        justify-content: space-between;
    }

    .aic {
        align-items: center;
    }
</style>
<div id="page-wrapper">
    <div class="d-flex jcb aic page-header">
        <h1>經濟部商業司資訊</h1>
        <div>
            <scraper-status-icon :column="column"></scraper-status-icon>
            <button class="btn btn-danger" id="redo">重新執行爬蟲</button>
        </div>
    </div>
    <div class="d-flex jcb aic page-header">
        <h2>工作收入證明</h2>
    </div>
    <table class="table">
        <tr>
            <th>姓名</th>
            <td id="name"></td>
            <th>投保單位名稱</th>
            <td id="companyName"></td>
            <th>總工作年資</th>
            <td id="total-count"></td>
        </tr>
        <tr>
            <th>出生年月日</th>
            <td id="birthday"></td>
            <th>統一編號</th>
            <td id="tax-id"></td>
            <th>現任職公司年資</th>
            <td id="this_company-count"></td>
        </tr>
        <tr>
            <th>身分證字號</th>
            <td id="id-number"></td>
            <th>投保薪資</th>
            <td id="insuranceSalary"></td>
            <th>查詢日期起迄</th>
            <td id="report-date"></td>
        </tr>
    </table>
    <div class="d-flex jcb aic page-header">
        <h2>公司基本資料</h2>
    </div>
    <table class="table">
        <tbody id="first-page-company-info"></tbody>
    </table>
    <div class="d-flex jcb aic page-header">
        <h2>監董事資料</h2>
    </div>
    <table class="table">
        <tbody id="first-page-director-info"></tbody>
    </table>
    <div class="d-flex jcb aic page-header">
        <h2>歷史資料</h2>
    </div>
    <table class="table">
        <div id="history"></div>
    </table>
</div>
<script>
    // SQL資料抓取
    function fetchInfoData(user_id) {
        $.ajax({
            type: "GET",
            url: "/admin/scraper/business_info" + "?user_id=" + user_id,
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
            return '';
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
					<th>${key}</th>
					<td>${value}</td>
				</tr>`
        return html;
    }

    function create_director_info_html(infoKey, key, value) {
        html = `<tr>
					<th rowspan="5">${infoKey}</th>
					<th>${key}</th>
					<td>${value}</td>
				</tr>`
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
					<table class="table" id="${id}">
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
                }).then(({data}) => {
                    if (data.code == 200) {
                        if (data.response.status == 200) {
                            alert('已成功送出追蹤')
                        } else {
                            alert(`子系統回應${data.response}，請洽工程師！`)
                        }
                    } else {
                        alert(`http回應${data.code}，請洽工程師！`)
                    }
                })
            }
        })
    });
    const v = new Vue({
        el: '#page-wrapper',
        computed: {
            column() {
                return 'biz_status'
            }
        },
    })
</script>

<style lang="scss">
    .table-title {
        min-width: 75px;
        background-color: #f9f9f9;
    }

    .table-content {
        word-break: break-all;
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
    let riskLevelResponse = []

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
    function fetchBusinessRegistrationData() {
        tax_id = $('#tax-id').text();
        $.ajax({
            type: "GET",
            url: "/admin/scraper/business_registration" + "?tax_id=" + tax_id,
            success: function (response) {
                if (response.status.code != 200) {
                    console.log(response.status.code)
                    return false;
                }
                console.log(response);
                businessRegistrationData = response.response;
                fillBusinessRegistrationData(businessRegistrationData);
                fillBusinessRegistrationDate(businessRegistrationData.date);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest.status);
                console.log(XMLHttpRequest.readyState);
                console.log(textStatus);
            },
        });
    }

    // 反詐欺資料
    function fetchRiskLevelData(user_id) {
        fillRiskLevelData(riskLevelResponse);
    }

    function approved() {
        alert('功能尚未啟用！');
        return false;
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

    function getNewIndustry(brData) {
        if (!brData) {
            return false;
        }
        newIndustry = brData.industryName + "(" + brData.industryCode + ")";
        if (brData.industryName1 !== "0")
            newIndustry += "<br/>" + brData.industryName1 + "(" + brData.industryCode1 + ")";
        if (brData.industryName2 !== "0")
            newIndustry += "<br/>" + brData.industryName2 + "(" + brData.industryCode2 + ")";
        if (brData.industryName3 !== "0")
            newIndustry += "<br/>" + brData.industryName3 + "(" + brData.industryCode3 + ")";
        return newIndustry;
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

    // 插入資料
    function fillBusinessRegistrationData(brData) {
        if (!brData) {
            return false;
        }
        $('#nameOfBusinessEntity').text(brData.nameOfBusinessEntity);
        $('#organizationType').text(brData.organizationType);
        $('#dateOfIncorporation').text(getNewDate(brData.dateOfIncorporation));
        $('#businessId').text(brData.businessId);
        $('#amountOfCapital').text(brData.amountOfCapital);
        $('#useOfUniformInvoice').text(brData.useOfUniformInvoice);
        $('#address').text(brData.address);
        $('#industryName').text(getNewIndustry(brData));
    }

    function fillBusinessRegistrationDate(brDate) {
        if (!brDate) {
            return false;
        }
        $('#title-date').text('財政部稅籍登記資訊 (更新日期：' + brDate + ')');
    }

    function fillRiskLevelData(response) {
        if (!response) {
            return false;
        }
        riskLevelResponse.forEach((response) => {
            level_html = create_risk_level_html(response.level, response.time, response.content);
            $('#risk-level').append(level_html);
        });
    }

    $(document).ready(function () {
        let urlString = window.location.href;
        let url = new URL(urlString);
        let user_id = url.searchParams.get("user_id");
        setTimeout(fetchInfoData(user_id), 1000);
        setTimeout(fetchBusinessRegistrationData(), 1000);
        setTimeout(fetchRiskLevelData(user_id), 1000);
		$('#redo').on('click', () => {
            if (confirm('是否確定重新執行爬蟲？')) {
                axios.post('/admin/scraper/downloadBusinessRegistration').then(({ data }) => {
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
            <h1 id="title-date">財政部稅籍登記資訊</h1>
        </div>
		<div>
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
                    <th class="table-title">財務部稅籍登記資訊</th>
                </tr>
                <tr>
                    <td>
                        <table style="table-layout:fixed;" class="table table-bordered table-hover table-striped">
                            <tbody>
                            <tr>
                                <th class="table-title">公司名稱</th>
                                <td style=background-color:white; id="nameOfBusinessEntity"></td>
                                <th class="table-title">組織種類</th>
                                <td id="organizationType"></td>
                            </tr>
                            <tr>
                                <th class="table-title">核准設立日期</th>
                                <td style=background-color:white; id="dateOfIncorporation"></td>
                                <th class="table-title">統一編號</th>
                                <td id="businessId"></td>
                            </tr>
                            <tr>
                                <th class="table-title">資本額</th>
                                <td style=background-color:white; id="amountOfCapital"></td>
                                <th class="table-title">是否使用統一發票</th>
                                <td id="useOfUniformInvoice"></td>
                            </tr>
                            <tr>
                                <th class="table-title">稅籍登記地址</th>
                                <td style=background-color:white; id="address"></td>
                                <th class="table-title">登記營業項目</th>
                                <td id="industryName"></td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </table>
    </table>
</div>

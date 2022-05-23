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
        <h1 id="title-date">財政部稅籍登記資訊</h1>
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
        <h2>財務部稅籍登記資訊</h2>
    </div>
    <table class="table">
        <tbody>
        <tr>
            <th>公司名稱</th>
            <td id="nameOfBusinessEntity"></td>
            <th>組織種類</th>
            <td id="organizationType"></td>
        </tr>
        <tr>
            <th>核准設立日期</th>
            <td id="dateOfIncorporation"></td>
            <th>統一編號</th>
            <td id="businessId"></td>
        </tr>
        <tr>
            <th>資本額</th>
            <td id="amountOfCapital"></td>
            <th>是否使用統一發票</th>
            <td id="useOfUniformInvoice"></td>
        </tr>
        <tr>
            <th>稅籍登記地址</th>
            <td id="address"></td>
            <th>登記營業項目</th>
            <td id="industryName"></td>
        </tr>
        </tbody>
    </table>
</div>
<script type="text/javascript">
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

    $(document).ready(function () {
        let urlString = window.location.href;
        let url = new URL(urlString);
        let user_id = url.searchParams.get("user_id");
        setTimeout(fetchInfoData(user_id), 1000);
        setTimeout(fetchBusinessRegistrationData(), 1000);

        $('#redo').on('click', () => {
            if (confirm('是否確定重新執行爬蟲？')) {
                axios.post('/admin/scraper/downloadBusinessRegistration').then(({data}) => {
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
                return 'business_registration_status'
            }
        },
    })
</script>

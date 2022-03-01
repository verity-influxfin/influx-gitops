<style lang="scss">
    .table-title {
        min-width: 75px;
        background-color: #f9f9f9;
    }

    .table-content {
        word-break: break-all;
    }
</style>
<script type="text/javascript">
    let riskLevelResponse = []

    // SQL資料抓取
    function fetchInfoData(user_id) {
        $.ajax({
            type: "GET",
            url: "/admin/scraper/sip_info" + "?user_id=" + user_id,
            async: false,
            success: function (response) {
                if (response.status.code != 200) {
                    console.log(response.status.code)
                    return false;
                }
                sipInfo = response.response;
                fillInfoData(sipInfo);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest.status);
                console.log(XMLHttpRequest.readyState);
                console.log(textStatus);
            },
        });
    }

    // 爬蟲資料抓取
    function fetchSipData() {
        university = $('#school').text();
        account = $('#sip-account').text();
        $.ajax({
            type: "GET",
            url: "/admin/scraper/sip" + "?university=" + university + "&account=" + account,
            success: function (response) {
                if (response.status.code != 200) {
                    console.log(response.status.code)
                    return false;
                }
                sipData = response.response;
                fillSipData(sipData.university, sipData.result);
                fillSipScore(sipData.result.semesterGrades);
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

    function creat_url_html(university, url) {
        if (!university || !url) {
            return false;
        }
        url = `<a href="${url}" target="_blank" id="url">${university}</a>`
        return url;
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

    function fillInfoData(sqlResponse) {
        if (!sqlResponse) {
            return false;
        }
        $('#name').text(sqlResponse.name);
        $('#birthday').text(sqlResponse.birthday);
        $('#id-number').text(sqlResponse.id_number);
        $('#school').append(creat_url_html(sqlResponse.school, sqlResponse.url));
        $('#department').text(sqlResponse.department);
        $('#student-id').text(sqlResponse.student_id);
        $('#sip-account').text(sqlResponse.sip_account);
        $('#sip-password').text(sqlResponse.sip_password);
        $('#email').text(sqlResponse.email);
    }

    function fillSipData(university, dataResponse) {
        if (!university || !dataResponse) {
            return false;
        }
        // console.log(dataResponse);
        $('#name-scraper').text(dataResponse.name);
        $('#id-scraper').text(dataResponse.idNumber);
        $('#university').text(university);
        $('#department-scraper').text(dataResponse.department);
        $('#school-status').text(dataResponse.schoolStatus);
        $('#student-phone').text(dataResponse.studentPhone);
        $('#home-phone').text(dataResponse.homePhone);
        $('#guardian').text(dataResponse.guardian);
        $('#guardian-phone').text(dataResponse.guardianPhone);
        $('#communication-address').text(dataResponse.communicationAddress);
        $('#household-address').text(dataResponse.householdAddress);
        $('#latest-grades').text(dataResponse.latestGrades);
    }

    function fillSipScore(dataResponse) {
        if (!dataResponse) {
            return false;
        }
        i = 0;
        Object.keys(dataResponse).forEach((semester) => {
            htmls = ''
            dataResponse[semester]['class'].forEach((item) => {
                html = `
				<tr>
					<td style=background-color:white;>${item.name}</td>
					<td style=background-color:white;>${item.credit}</td>
					<td style=background-color:white;>${item.score}</td>
				</tr>`
                htmls += html;
            })
            table = `<div class="form-group">
					<table style="table-layout:fixed;" class="table table-bordered table-hover table-striped">
						<tbody>
							<tr>
								<th>學年期/學期總平均</th>
								<th style=background-color:white;>${semester}</th>
								<th style=background-color:white;>${dataResponse[semester].totalAvg}</th>
							</tr>
							<tr>
								<th class="table-title">課程名稱</th>
								<th class="table-title">學分</th>
								<th class="table-title">分數</th>
							</tr>
							${htmls}
						</tbody>
					</table>
				</div>`
            $('#content').append(table);
        })
    }

    function fillRiskLevelData(riskLevelResponse) {
        if (!riskLevelResponse) {
            return false;
        }
        riskLevelResponse.forEach((item) => {
            level_html = create_risk_level_html(item.level, item.time, item.content);
            $('#risk-level').append(level_html);
        })
    }

    $(document).ready(function () {
        let urlString = window.location.href;
        let url = new URL(urlString);
        let user_id = url.searchParams.get("user_id");
        setTimeout(fetchInfoData(user_id), 1000);
        setTimeout(fetchSipData(user_id), 1000);
        setTimeout(fetchRiskLevelData(user_id), 1000);
    });
</script>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">學生SIP資訊</h1>
        </div>
    </div>
    <table class="table table-bordered table-hover table-striped">
        <tbody>
        <tr>
            <th class="table-title">資料內容（學生認證）</th>
        </tr>
        <tr>
            <td>
                <table class="table table-bordered table-hover table-striped">
                    <tr>
                        <th class="table-title">姓名</th>
                        <td style=background-color:white; id="name"></td>
                        <th class="table-title">學校名稱</th>
                        <td style=background-color:white; id="school"></td>
                        <th class="table-title">SIP帳號</th>
                        <td style=background-color:white; id="sip-account"></td>
                    </tr>
                    <tr>
                        <th class="table-title">出生年月日</th>
                        <td style=background-color:white; id="birthday"></td>
                        <th class="table-title">系所</th>
                        <td style=background-color:white; id="department"></td>
                        <th class="table-title">SIP密碼</th>
                        <td style=background-color:white; id="sip-password"></td>
                    </tr>
                    <tr>
                        <th class="table-title">身分證字號</th>
                        <td style=background-color:white; id="id-number"></td>
                        <th class="table-title">學號</th>
                        <td style=background-color:white; id="student-id"></td>
                        <th class="table-title">學校信箱</th>
                        <td style=background-color:white; id="email"></td>
                    </tr>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-hover table-striped">
        <tr>
            <th class="table-title">SIP爬蟲資訊</th>
        </tr>
        <tr>
            <td>
                <table style="table-layout:fixed;" class="table table-bordered table-hover table-striped">
                    <tbody>
                    <tr>
                        <th class="table-title">姓名</th>
                        <td style=background-color:white; id="name-scraper"></td>
                        <th class="table-title">身分證</th>
                        <td style=background-color:white; id="id-scraper"></td>
                    </tr>
                    <tr>
                        <th class="table-title">學校</th>
                        <td style=background-color:white; id="university"></td>
                        <th class="table-title">科系</th>
                        <td style=background-color:white; id="department-scraper"></td>
                    </tr>
                    <tr>
                        <th class="table-title">在學狀態</th>
                        <td style=background-color:white; id="school-status"></td>
                        <th class="table-title">手機</th>
                        <td style=background-color:white; id="student-phone"></td>
                    </tr>
                    <tr>
                        <th class="table-title">家用電話</th>
                        <td style=background-color:white; id="home-phone"></td>
                        <th class="table-title">緊急聯絡人</th>
                        <td style=background-color:white; id="guardian"></td>
                    </tr>
                    <tr>
                        <th class="table-title">緊急聯絡人電話</th>
                        <td style=background-color:white; id="guardian-phone"></td>
                        <th class="table-title">通訊地址</th>
                        <td style=background-color:white; id="communication-address"></td>
                    </tr>
                    <tr>
                        <th class="table-title">戶籍地址</th>
                        <td style=background-color:white; id="household-address"></td>
                        <th class="table-title">近一學期成績</th>
                        <td style=background-color:white; id="latest-grades"></td>
                    </tr>
                    </tbody>
                </table>
                <table style="table-layout:fixed;" class="table table-bordered table-hover table-striped">
                    <div id="content"></div>
                </table>
            </td>
        </tr>
    </table>
</div>

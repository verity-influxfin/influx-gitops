<style lang="scss">
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
<div id="page-wrapper">
    <div class="d-flex jcb aic page-header">
        <h1>學生SIP資訊</h1>
        <div>
            <scraper-status-icon :column="column"></scraper-status-icon>
            <button class="btn btn-danger" id="redo">重新執行爬蟲</button>
        </div>
    </div>
    <div class="d-flex jcb aic page-header">
        <h2>學生認證資訊</h2>
    </div>
    <table class="table">
        <tr>
            <th>姓名</th>
            <td id="name"></td>
            <th>學校名稱</th>
            <td id="school"></td>
            <th>SIP帳號</th>
            <td id="sip-account"></td>
        </tr>
        <tr>
            <th>出生年月日</th>
            <td id="birthday"></td>
            <th>系所</th>
            <td id="department"></td>
            <th>SIP密碼</th>
            <td id="sip-password"></td>
        </tr>
        <tr>
            <th>身分證字號</th>
            <td id="id-number"></td>
            <th>學號</th>
            <td id="student-id"></td>
            <th>學校信箱</th>
            <td id="email"></td>
        </tr>
    </table>
    <div class="d-flex jcb aic page-header">
        <h2>爬蟲資訊</h2>
    </div>
    <table class="table">
        <tbody>
        <tr>
            <th>姓名</th>
            <td id="name-scraper"></td>
            <th>身分證</th>
            <td id="id-scraper"></td>
        </tr>
        <tr>
            <th>學校</th>
            <td id="university"></td>
            <th>科系</th>
            <td id="department-scraper"></td>
        </tr>
        <tr>
            <th>在學狀態</th>
            <td id="school-status"></td>
            <th>手機</th>
            <td id="student-phone"></td>
        </tr>
        <tr>
            <th>家用電話</th>
            <td id="home-phone"></td>
            <th>緊急聯絡人</th>
            <td id="guardian"></td>
        </tr>
        <tr>
            <th>緊急聯絡人電話</th>
            <td id="guardian-phone"></td>
            <th>通訊地址</th>
            <td id="communication-address"></td>
        </tr>
        <tr>
            <th>戶籍地址</th>
            <td id="household-address"></td>
            <th>近一學期成績</th>
            <td id="latest-grades"></td>
        </tr>
        </tbody>
    </table>
    <div class="d-flex jcb aic page-header">
        <h2>成績資訊</h2>
    </div>
    <div id="content"></div>
</div>
<script type="text/javascript">
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
                fillSipData(sipData.university, sipData.result, sipData.school_status);
                console.log(sipData.result.semesterGrades);
                fillSipScore(sipData.result.semesterGrades);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest.status);
                console.log(XMLHttpRequest.readyState);
                console.log(textStatus);
            },
        });
    }

    function creat_url_html(university, url) {
        if (!university || !url) {
            return false;
        }
        url = `<a href="${url}" target="_blank" id="url">${university}</a>`
        return url;
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

    function fillSipData(university, dataResponse, school_status) {
        if (!university || !dataResponse) {
            return false;
        }
        // console.log(dataResponse);
		const status = (st)=>{
			if(st){
				return `
					<button type="button" class="btn btn-success btn-circle">
						<i class="fa fa-check"></i>
					</button>
				`
			}
			return `
				<button type="button" class="btn btn-danger btn-circle">
					<i class="fa fa-times"></i>
				</button>
			`
		}
		const universityStatus = (st) => {
			switch (st) {
				case 0: return '驗證碼問題'
				case 1: return '正常狀態'
				case 2: return '黑名單學校'
				case 3: return 'server問題'
				case 4: return 'VPN相關問題'
				case 5: return '要求改密碼'
				case 6: return '問卷問題'
				case 7: return '不穩定 有時有未知異常'
				default: return 'unKnown Error'
			}
		}
        $('#name-scraper').html(dataResponse.name + status(school_status.deep_scrape_enabled.name));
        $('#id-scraper').html(dataResponse.idNumber + status(school_status.deep_scrape_enabled.idNumber));
        $('#university').text(university + ' - ' + universityStatus(school_status.status));
        $('#department-scraper').html(dataResponse.department + status(school_status.deep_scrape_enabled.department));
        $('#school-status').html(dataResponse.schoolStatus + status(school_status.deep_scrape_enabled.schoolStatus));
        $('#student-phone').html(dataResponse.studentPhone + status(school_status.deep_scrape_enabled.studentPhone));
        $('#home-phone').html(dataResponse.homePhone + status(school_status.deep_scrape_enabled.homePhone));
        $('#guardian').html(dataResponse.guardian + status(school_status.deep_scrape_enabled.guardian));
        $('#guardian-phone').html(dataResponse.guardianPhone + status(school_status.deep_scrape_enabled.guardianPhone));
        $('#communication-address').html(dataResponse.communicationAddress + status(school_status.deep_scrape_enabled.communicationAddress));
        $('#household-address').html(dataResponse.householdAddress + status(school_status.deep_scrape_enabled.householdAddress));
        $('#latest-grades').html(dataResponse.latestGrades + status(school_status.deep_scrape_enabled.latestGrades));
    }

    function fillSipScore(dataResponse) {
        if (!dataResponse) {
            return false;
        }
        i = 0;
        Object.keys(dataResponse).forEach((semester) => {
            let htmls = ''
            const semester_class = dataResponse[semester]['class'] ?? []
            semester_class.forEach((item) => {
                html = `
				<tr>
					<td>${item.name ?? ''}</td>
					<td>${item.credit ?? ''}</td>
					<td>${item.score ?? ''}</td>
				</tr>`
                htmls += html;
            })
            table = `
					<table class="table">
						<tbody>
							<tr>
								<th>學年期/學期總平均</th>
								<td>${semester}</th>
								<td>${dataResponse[semester].totalAvg}</td>
							</tr>
							<tr>
								<th>課程名稱</th>
								<th>學分</th>
								<th>分數</th>
							</tr>
							${htmls}
						</tbody>
					</table>`
            $('#content').append(table);
        })
    }

    $(document).ready(function () {
        let urlString = window.location.href;
        let url = new URL(urlString);
        let user_id = url.searchParams.get("user_id");
        setTimeout(fetchInfoData(user_id), 1000);
        setTimeout(fetchSipData(user_id), 1000);

		$('#redo').on('click', () => {
			if (confirm('是否確定重新執行爬蟲？')) {
				axios.post('/admin/scraper/request_deep', {
					university: $('#school').text(),
					account: $('#sip-account').text(),
					password: $('#sip-password').text()
				}).then(({ data }) => {
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
		el:'#page-wrapper',
		computed: {
			column(){
				return 'sip_status'
			}
		},
	})
</script>

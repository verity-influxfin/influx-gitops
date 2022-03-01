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
    function fetchStatus() {
        user_id = $('#id-textbox').val();
        if (user_id != '') {
            if (user_id.length > 5) {
                $('#id-textbox').val('');
                alert('文字過長,請重新輸入！');
            } else {
                getAjax(user_id);
            }
        }
    }

    function getAjax(user_id) {
        $.ajax({
            type: "GET",
            url: "/admin/scraper/scraper_status" + "?user_id=" + user_id,
            async: false,
            success: function (response) {
                if (response.status.code != 200) {
                    console.log(response.status.code)
                    return false;
                }
                // console.log(response);
                statusResponse = response.response;
                if ($('#' + user_id).text() != user_id)
                    fillData(user_id);
                insertOrUpdateContent(user_id, statusResponse);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest.status);
                console.log(XMLHttpRequest.readyState);
                console.log(textStatus);
            },
        });
    }

    function insertOrUpdateContent(user_id, statusResponse) {
        judicial_yuan = create_status_icon(statusResponse.judicial_yuan_status, '/admin/Scraper?view=judicial_yuan_verdict&user_id=' + user_id);
        household_registration = create_status_icon(statusResponse.household_registration_status, '#');
        sip = create_status_icon(statusResponse.sip_status, '/admin/Scraper?view=sip&user_id=' + user_id);
        biz = create_status_icon(statusResponse.biz_status, '/admin/Scraper?view=biz&user_id=' + user_id);
        business_registration = create_status_icon(statusResponse.business_registration_status, '/admin/Scraper?view=business_registration&user_id=' + user_id);
        google = create_status_icon(statusResponse.google_status, '/admin/Scraper?view=google&user_id=' + user_id);
        ptt = create_status_icon(statusResponse.ptt_status, '/admin/Scraper?view=ptt&user_id=' + user_id);
        instagram = create_status_icon(statusResponse.instagram_status, '/admin/Scraper?view=instagram&user_id=' + user_id);
        // fb = create_status_icon(statusResponse.fb, '#');
        // dcard = create_status_icon(statusResponse.dcard, '#');
        $('#judicial_yuan' + user_id).empty().append(judicial_yuan);
        $('#household_registration' + user_id).empty().append(household_registration);
        $('#sip' + user_id).empty().append(sip);
        $('#biz' + user_id).empty().append(biz);
        $('#business_registration' + user_id).empty().append(business_registration);
        $('#google' + user_id).empty().append(google);
        $('#ptt' + user_id).empty().append(ptt);
        $('#instagram' + user_id).empty().append(instagram);
        // $('#fb'+user_id).empty().append(fb);
        // $('#dcard'+user_id).empty().append(dcard);
    }

    function risk_update() {
        alert('功能尚未啟用！');
        return false;
    }

    function status_update() {
        alert('功能尚未啟用！');
        return false;
    }

    function fillData(user_id) {
        if (!user_id) {
            return false;
        }
        case_html = create_content_html(user_id, "judicial_yuan" + user_id, "household_registration" + user_id, "sip" + user_id, "biz" + user_id, "business_registration" + user_id, "google" + user_id, "ptt" + user_id, "instagram" + user_id, "fb" + user_id, "dcard" + user_id);
        $('#content').append(case_html);
    }

    function create_status_icon(status, url) {
        if (url == '#') {
            target = "_self"
        } else {
            target = "_blank"
        }
        if (status == 'finished') {
            return `<a href="${url}" target="${target}">
						<button class="btn btn-success btn-circle btn-sm">
                       		<i class="fa fa-check"></i>
                    	</button>
					</a>`;
        } else if (status == 'failure') {
            return `<a href="${url}" target="${target}">
						<button class="btn btn-danger btn-circle btn-sm">
                        	<i class="fa fa-times"></i>
                    	</button>
					</a>`;
        } else if (status == 'requested') {
            return `<a href="${url}" target="${target}">
						<button class="btn btn-secondary btn-circle btn-sm">
                        	<i class="fa fa-pause"></i>
                    	</button>
					</a>`;
        } else if (status == 'started') {
            return `<a href="${url}" target="${target}">
						<button class="btn btn-warning btn-circle btn-sm">
                        	<i class="fa fa-refresh"></i>
                    	</button>
					</a>`;
        } else
            return false;
    }

    function create_content_html(user_id, judical_yuan, household_registration, sip, biz, business_registration, google, ptt, instagram, fb, dcard) {
        html = `<tr align="center" valign="center">
                    <td style=background-color:white; id="${user_id}">${user_id}</td>
                    <td style=background-color:white; id="${judical_yuan}"></td>
                    <td style=background-color:white; id="${household_registration}"></td>
                    <td style=background-color:white; id="${sip}"></td>
                    <td style=background-color:white; id="${biz}"></td>
                    <td style=background-color:white; id="${business_registration}"></td>
                    <td style=background-color:white; id="${google}"></td>
                    <td style=background-color:white; id="${instagram}"></td>
                </tr>`
        return html;
    }

    $(document).ready(function () {
        let urlString = window.location.href;
        let url = new URL(urlString);
    });
</script>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">爬蟲系統</h1>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <table>
                <tbody>
                <tr>
                    <td colspan="4">
                        <h4>圖示說明：</h4>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex justify-content-evenly">
                            <button type="button" class="btn btn-success btn-circle"><i class="fa fa-check"></i>
                            </button>
                            <label>執行成功</label>
                            <button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i></button>
                            <label>執行失敗</label>
                            <button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-pause"></i>
                            </button>
                            <label>尚未執行</label>
                            <button type="button" class="btn btn-warning btn-circle"><i class="fa fa-refresh"></i>
                            </button>
                            <label>執行中</label>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="panel-body">
            <div class="pull-left">
                <div id="dataTables-tables_filter" class="dataTables_filter">
                    <label>
                        ID搜尋:
                        <input type="text" id="id-textbox" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');">
                        <input type="button" onclick="fetchStatus()" class="btn btn-primary btn-sm" value="查詢">
                    </label>
                </div>
            </div>
            <table class="table table-bordered table-hover table-striped table-sm" align="center" valign="center">
                <tr>
                    <th class="table-title">會員編號</th>
                    <th class="table-title">司法院判決案例</th>
                    <th class="table-title">內政部戶政司</th>
                    <th class="table-title">SIP學生資訊</th>
                    <th class="table-title">經濟部商業司</th>
                    <th class="table-title">財務部稅籍登記</th>
                    <th class="table-title">Google</th>
                    <th class="table-title">Instagram</th>
                </tr>
                <tbody id="content"></tbody>
            </table>
        </div>
    </div>
</div>

<style lang="scss">
    .table-title {
        min-width: 75px;
        background-color: #f9f9f9;
    }

    .table-content {
        word-break: break-all;
    }

    .d-flex {
        display: flex;
    }

    .aic {
        align-items: center;
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
                create_table(user_id, response.response)
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest.status);
                console.log(XMLHttpRequest.readyState);
                console.log(textStatus);
            },
        });
    }

    function create_table_content(status, url) {
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
            return '';
    }

    function create_table(user_id, statusResponse) {
        const t2 = $('#status').DataTable()
        const judicial_yuan_html = create_table_content(statusResponse.judicial_yuan_status, '/admin/Scraper?view=judicial_yuan_verdict&user_id=' + user_id);
        const household_registration_html = create_table_content(statusResponse.household_registration_status, '#');
        const sip_html = create_table_content(statusResponse.sip_status, '/admin/Scraper?view=sip&user_id=' + user_id);
        const biz_html = create_table_content(statusResponse.biz_status, '/admin/Scraper?view=biz&user_id=' + user_id);
        const business_registration_html = create_table_content(statusResponse.business_registration_status, '/admin/Scraper?view=business_registration&user_id=' + user_id);
        const google_html = create_table_content(statusResponse.google_status, '/admin/Scraper?view=google&user_id=' + user_id);
        const instagram_html = create_table_content(statusResponse.instagram_status, '/admin/Scraper?view=instagram&user_id=' + user_id);
        t2.clear()
        t2.row.add([
            user_id,
            judicial_yuan_html,
            household_registration_html,
            sip_html,
            biz_html,
            business_registration_html,
            google_html,
            instagram_html
        ])
        t2.draw()
    }

    $(document).ready(function () {
        let urlString = window.location.href;
        let url = new URL(urlString);

        const t2 = $('#status').DataTable({
            'ordering': false,
            "paging": false,
            "info": false,
            "searching": true,
            'language': {
                'processing': '處理中...',
                'lengthMenu': '顯示 _MENU_ 項結果',
                'zeroRecords': '目前無資料',
                'info': '顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項',
                'infoEmpty': '顯示第 0 至 0 項結果，共 0 項',
                'infoFiltered': '(從 _MAX_ 項結果過濾)',
                'search': '使用本次搜尋結果快速搜尋',
                'paginate': {
                    'first': '首頁',
                    'previous': '上頁',
                    'next': '下頁',
                    'last': '尾頁'
                }
            },
            "info": false
        })
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
            <h4>圖示說明：</h4>
            <div class="d-flex aic my-2">
                <div>
                    <button type="button" class="btn btn-success btn-circle">
                        <i class="fa fa-check"></i>
                    </button>
                    <label>執行成功</label>
                </div>
                <div class="mx-3">
                    <button type="button" class="btn btn-danger btn-circle">
                        <i class="fa fa-times"></i>
                    </button>
                    <label>執行失敗</label>
                </div>
                <div>
                    <button type="button" class="btn btn-default btn-circle">
                        <i class="fa fa-pause"></i>
                    </button>
                    <label>尚未執行</label>
                </div>
                <div class="mx-3">
                    <button type="button" class="btn btn-warning btn-circle">
                        <i class="fa fa-refresh"></i>
                    </button>
                    <label>執行中</label>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="d-flex aic">
                <div>ID搜尋：</div>
                <div class="input-group input mx-2">
                    <input class="form-control" type="text" id="id-textbox"
                        onkeyup="this.value=this.value.replace(/[^0-9]/g,'');">
                </div>
                <button type="button" onclick="fetchStatus()" class="btn btn-primary btn-sm">查詢</button>
            </div>
            <table id="status">
                <thead>
                    <tr>
                        <th>會員編號</th>
                        <th>司法院判決案例</th>
                        <th>內政部戶政司</th>
                        <th>SIP學生資訊</th>
                        <th>經濟部商業司</th>
                        <th>財務部稅籍登記</th>
                        <th>Google</th>
                        <th>Instagram</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

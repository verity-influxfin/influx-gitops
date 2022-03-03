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
            url: "/admin/scraper/ptt_info" + "?user_id=" + user_id,
            async: false,
            success: function (response) {
                if (response.status.code != 200) {
                    console.log(response.status.code)
                    return false;
                }
                pttInfo = response.response;
                fillInfoData(pttInfo);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest.status);
                console.log(XMLHttpRequest.readyState);
                console.log(textStatus);
            },
        });
    }

    function fetchPttData(user_id) {
        account = $('#ptt-account').text();
        if (account != '') {
            $.ajax({
                type: "GET",
                url: "/admin/scraper/ptt" + "?user_id=" + user_id + "&account=" + account,
                success: function (response) {
                    if (response.status.code != 200) {
                        console.log(response.status.code)
                        return false;
                    }
                    articleNumResponse = response.response.articleNumResult;
                    messageNumResponse = response.response.messageNumResult;
                    articleResult = response.response.articleResult;
                    messageResult = response.response.messageResult;
                    fillPttArticleNumAndMessageNum(articleNumResponse, messageNumResponse);
                    fillPttArticleResult(articleResult);
                    fillPttMessageResult(messageResult);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest.status);
                    console.log(XMLHttpRequest.readyState);
                    console.log(textStatus);
                },
            });
        }
    }

    // 反詐欺資料
    function fetchRiskLevelData(user_id) {
        fillRiskLevelData(riskLevelResponse);
    }

    function approved() {
        alert('功能尚未啟用！');
        return false;
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

    function fillInfoData(pttInfoData) {
        if (!pttInfoData) {
            return false;
        }
        $('#name').text(pttInfoData.name);
        $('#ptt-account').text(pttInfoData.ptt_acount);
        baseUrl = 'https://www.pttweb.cc/user/' + pttInfoData.ptt_acount;
        $('#ptt-account').attr("href", baseUrl);
    }

    function fillPttArticleNumAndMessageNum(articleNumResponse, messageNumResponse) {
        if (!articleNumResponse || !messageNumResponse) {
            return false;
        }
        articleNumResponse = articleNumResponse[articleNumResponse.length - 1]
        messageNumResponse = messageNumResponse[messageNumResponse.length - 1]
        // console.log(articleNumResponse);
        // console.log(messageNumResponse);
        $('#article-total').text(articleNumResponse.total);
        $('#article-bump').text(articleNumResponse.bump);
        $('#article-comment').text(articleNumResponse.comment);
        $('#article-boo').text(articleNumResponse.boo);
        $('#message-total').text(messageNumResponse.total);
        $('#message-bump').text(messageNumResponse.bump);
        $('#message-comment').text(messageNumResponse.comment);
        $('#message-boo').text(messageNumResponse.boo);
    }

    function fillPttArticleResult(articleResult) {
        if (!articleResult || !messageNumResponse) {
            return false;
        }
    }

    function fillPttMessageResult(messageResult) {
        if (!messageResult) {
            return false;
        }
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
        setTimeout(fetchPttData(user_id), 1000);
        setTimeout(fetchRiskLevelData(user_id), 1000);
    });
</script>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">社群-Ptt</h1>
        </div>
    </div>
    <table class="table table-bordered table-hover table-striped">
        <tbody>
        <tr>
            <th class="table-title">資料內容（學生認證）</th>
        </tr>
        <tr>
            <td>
                <table style="table-layout:fixed;" class="table table-bordered table-hover table-striped">
                    <tr>
                        <th class="table-title">姓名</th>
                        <td style=background-color:white; id="name"></td>
                        <th class="table-title">PTT帳號</th>
                        <td style=background-color:white;>
                            <a id="ptt-account" target="_blank" href="https://www.pttweb.cc/user/">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-hover table-striped">
        <tr>
            <th class="table-title">Ptt資訊</th>
        </tr>
        <tr>
            <td>
                <table style="table-layout:fixed;" class="table table-bordered table-hover table-striped">
                    <tr>
                        <th class="table-title">發文</th>
                        <td style=background-color:white; id="article-total"></td>
                        <th class="table-title">留言</th>
                        <td style=background-color:white; id="message-total"></td>
                    </tr>
                    <tr>
                        <th class="table-title">總獲得推文數</th>
                        <td style=background-color:white; id="article-bump"></td>
                        <th class="table-title">總推文數</th>
                        <td style=background-color:white; id="message-bump"></td>
                    </tr>
                    <tr>
                        <th class="table-title">總獲得留言數</th>
                        <td style=background-color:white; id="article-comment"></td>
                        <th class="table-title">總留言數</th>
                        <td style=background-color:white; id="message-comment"></td>
                    <tr>
                        <th class="table-title">總獲得噓文數</th>
                        <td style=background-color:white; id="article-boo"></td>
                        <th class="table-title">總噓文數</th>
                        <td style=background-color:white; id="message-boo"></td>
                    </tr>
                </table>
                <div id="content"></div>
            </td>
        </tr>
    </table>
</div>

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
            url: "/admin/scraper/instagram_info" + "?user_id=" + user_id,
            async: false,
            success: function (response) {
                if (response.status.code != 200) {
                    console.log(response.status.code)
                    return false;
                }
                instagramInfo = response.response;
                fillInfoData(instagramInfo);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest.status);
                console.log(XMLHttpRequest.readyState);
                console.log(textStatus);
            },
        });
    }

    // 爬蟲資料抓取
    function fetchInstagramData(user_id) {
        ig_username = $('#ig-username').text();
        $.ajax({
            type: "GET",
            url: "/admin/scraper/instagram" + "?user_id=" + user_id + "&ig_username=" + ig_username,
            success: function (response) {
                if (response.status.code != 200) {
                    console.log(response.status.code)
                    return false;
                }
                instagramData = response.response.result;
                fillInstagramData(instagramData);
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

    function fillInfoData(instagramInfoData) {
        if (!instagramInfoData) {
            return false;
        }
        $('#name').text(instagramInfoData.name);
        $('#ig-username').text(instagramInfoData.ig_username);
        baseUrl = 'https://www.instagram.com/' + instagramInfoData.ig_username + '/';
        $('#ig-username').attr("href", baseUrl);
    }

    function fillInstagramData(instagramData) {
        if (!instagramData) {
            return false;
        }
        switch (instagramData.followStatus) {
            case 'followed':
                followStatus = '追蹤中'
                break;
            case 'waitingFollowAccept':
                followStatus = '已送出請求'
                break;
            case 'unfollowed':
                followStatus = '未追蹤'
                break;
            default:
                followStatus = ''
        }

        $('#is-exist').text(instagramData.isExist ? '是' : '否');
        $('#is-private').text(instagramData.isPrivate ? '是' : '否');
        $('#is-follower').text(instagramData.isFollower ? '是' : '否');
        $('#posts').text(instagramData.posts);
        $('#following').text(instagramData.following);
        $('#followers').text(instagramData.followers);
        $('#follow-status').text(followStatus);
        $('#posts-in-3-months').text(instagramData.postsIn3Months);
        $('#posts-with-key-words').text(instagramData.postsWithKeyWords);
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
        setTimeout(fetchInstagramData(user_id), 1000);
        setTimeout(fetchRiskLevelData(user_id), 1000);
    });
</script>
<div id="page-wrapper">
    <div class="d-flex jcb aic page-header">
        <div>
            <h1>社群-Instagram</h1>
        </div>
		<div>
			<button class="btn btn-danger" id="redo">重新執行爬蟲</button>
		</div>
    </div>
    <table class="table table-bordered table-hover table-striped">
        <tbody>
        <tr>
            <th class="table-title">資料內容（社交認證）</th>
        </tr>
        <tr>
            <td>
                <table style="table-layout:fixed;" class="table table-bordered table-hover table-striped">
                    <tr>
                        <th class="table-title">姓名</th>
                        <td style=background-color:white; id="name"></td>
                        <th class="table-title">IG帳號</th>
                        <td style=background-color:white;>
                            <a id="ig-username" target="_blank" href="https://www.instagram.com/"></a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-hover table-striped">
        <tr>
            <th class="table-title">Instagram資訊</th>
        </tr>
        <tr>
            <td>
                <table style="table-layout:fixed;" class="table table-bordered table-hover table-striped">
                    <tr>
                        <th class="table-title">帳號是否存在</th>
                        <th class="table-title">是否為私人帳號</th>
                        <th class="table-title">是否追蹤官方帳號</th>
                    </tr>
                    <tr>
                        <td style=background-color:white; id="is-exist"></td>
                        <td style=background-color:white; id="is-private"></td>
                        <td style=background-color:white; id="is-follower"></td>

                    </tr>
                    <tr>
                        <th class="table-title">總貼文數</th>
                        <th class="table-title">追蹤數</th>
                        <th class="table-title">粉絲數</th>

                    </tr>
                    <tr>
                        <td style=background-color:white; id="posts"></td>
                        <td style=background-color:white; id="following"></td>
                        <td style=background-color:white; id="followers"></td>
                    </tr>
                    <tr>
                        <th class="table-title">系統爬蟲帳號是否追蹤此帳號</th>
                        <th class="table-title">3個月內po文</th>
                        <th class="table-title">關鍵字命中(全球、財經、數位、兩岸)</th>
                    </tr>
                    <tr>
                        <td style=background-color:white; id="follow-status"></td>
                        <td style=background-color:white; id="posts-in-3-months"></td>
                        <td style=background-color:white; id="posts-with-key-words"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

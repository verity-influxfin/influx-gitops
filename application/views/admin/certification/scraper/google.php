<style lang="scss">
    .table-title {
        padding: 0;
        min-width: 75px;
        background-color: #f9f9f9;
    }

    .f-red {
        background-color: red;
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
            url: "/admin/scraper/verdict_google_info" + "?user_id=" + user_id,
            async: false,
            success: function (response) {
                if (response.status.code != 200) {
                    console.log(response.status.code)
                    return false;
                }
                googleInfo = response.response;
                fillInfoData(googleInfo);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest.status);
                console.log(XMLHttpRequest.readyState);
                console.log(textStatus);
            },
        });
    }

    // 爬蟲資料抓取
    function fetchGoogleData(user_id) {
        name = $('#name').text();
        $.ajax({
            type: "GET",
            url: "/admin/scraper/google" + "?user_id=" + user_id + "&name=" + name,
            success: function (response) {
                if (response.status.code != 200) {
                    console.log(response.status.code)
                    return false;
                }
                googelData = response.response.results;
                fillGoogleData(googelData);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest.status);
                console.log(XMLHttpRequest.readyState);
                console.log(textStatus);
            },
        });
    }

    // 關鍵字加上標號
    function highlight(content, what, spanClass) {
        pattern = new RegExp('([<.]*)(' + what + ')([<.]*)', 'gi'),
            replaceWith = '$1<span ' + (spanClass ? 'class="' + spanClass + '"' : '') + '">$2</span>$3',
            highlighted = content.replace(pattern, replaceWith);
        return highlighted;
    }

    function watchContent(id) {
        if (isClick == true) {
            $('#' + id).addClass('table-ellipsis');
            isClick = false;
        } else {
            $('#' + id).removeClass('table-ellipsis');
            isClick = true;
        }
    }

    // todo:分頁
    function create_case_html(name, url, title, content, contentAll, i) {
        content = highlight(content, name, 'f-red');
        html = `<div class="form-group">
                    <table style="table-layout:fixed;" class="table table-bordered table-hover table-striped">
                        <tbody>
                            <tr>
                                <td class="table-title">標題</td>
                                <td style=background-color:white;>
                                    <a class="fancyframe" target="_blank" href="${url}">${title}</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="table-title">內容</td>
                                <td>${content}</td>
                            </tr>
                            <tr>
                                <td class="table-title">
                                    <a onclick="watchContent('${i}')">內容(點我展開)</a>
                                </td>
                                <td class="table-content table-ellipsis" id="${i}">
                                    ${contentAll}
                                </td>
                            </tr>
                        </tbody>
                    <table>
                </div>`
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

    function fillInfoData(googleInfo) {
        if (!googleInfo) {
            return false;
        }
        idCardPlace = googleInfo.id_card_place;
        idCardPlace = idCardPlace.split(')');
        $('#name').text(googleInfo.name);
        url = 'https://www.google.com.tw/search?q=' + googleInfo.name + '&num=100';
        $('#name').attr("href", url);
        $('#birthday').text(googleInfo.birthday);
        $('#id-number').text(googleInfo.id_number);
        $('#id-card-date').text(googleInfo.id_card_date);
        $('#id_card_place').text(idCardPlace[0].replace('(', ''));
        $('#replacement').text(idCardPlace[1]);
        father_url = 'https://www.google.com.tw/search?q=' + googleInfo.father + '&num=100';
        $('#father').text(googleInfo.father);
        $('#father').attr("href", father_url);
        mother_url = 'https://www.google.com.tw/search?q=' + googleInfo.mother + '&num=100';
        $('#mother').text(googleInfo.mother);
        $('#mother').attr("href", mother_url);
        $('#born').text(googleInfo.born);
        spouse_url = 'https://www.google.com.tw/search?q=' + googleInfo.spouse + '&num=100';
        $('#spouse').text(googleInfo.spouse);
        $('#spouse').attr("href", spouse_url);
        $('#address').text(googleInfo.address);
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

    function fillGoogleData(googleData) {
        name = $('#name').text();
        console.log(name);
        console.log(googleData);
        if (!name || !googleData) {
            return false;
        }
        i = 0;
        googleData.forEach((item) => {
            console.log(item.url);
            console.log(item.title);
            console.log(item.content);
            case_html = create_case_html(name, item.url, item.title, item.content, item.contentAll, i);
            $('#content').append(case_html);
            i += 1;
        });
    }

    $(document).ready(function () {
        let urlString = window.location.href;
        let url = new URL(urlString);
        let user_id = url.searchParams.get("user_id");
        setTimeout(fetchInfoData(user_id), 1000);
        setTimeout(fetchGoogleData(user_id), 1000);

		$('#redo').on('click', () => {
			if (confirm('是否確定重新執行爬蟲？')) {
				axios.post('/admin/scraper/request_google', {
					keyword: $('#name').text(),
				}).then(({ data }) => {
					if (data.status == 200) {
						location.reload()
					}
					else{
						alert(data.response.message)
					}
				})
			}
		})
    });
</script>
<div id="page-wrapper">
    <div class="d-flex jcb aic page-header">
        <div>
            <h1>Google</h1>
        </div>
		<div>
			<scraper-status-icon :column="column"></scraper-status-icon>
			<button class="btn btn-danger" id="redo">重新執行爬蟲</button>
		</div>
    </div>
    <table style="table-layout:fixed;" class="table table-bordered table-hover table-striped">
        <tr>
            <th class="table-title">資料內容（實名認證）</th>
        </tr>
        <tr>
            <td>
                <table class="table table-bordered table-hover table-striped">
                    <tr>
                        <th class="table-title">姓名</th>
                        <td style=background-color:white;>
                            <a id="name" target="_blank" href="https://www.google.com/"></a>
                        </td>
                        <th class="table-title">發證日期</th>
                        <td style=background-color:white; id="id-card-date"></td>
                        <th class="table-title">父</th>
                        <td style=background-color:white;>
                            <a id="father" target="_blank" href="https://www.google.com/"></a>
                        </td>
                        <th class="table-title">出生地</th>
                        <td style=background-color:white; id="born"></td>
                    </tr>
                    <tr>
                        <th class="table-title">出生年月日</th>
                        <td style=background-color:white; id="birthday"></td>
                        <th class="table-title">發證地點</th>
                        <td style=background-color:white; id="id_card_place"></td>
                        <th class="table-title">母</th>
                        <td style=background-color:white;>
                            <a id="mother" target="_blank" href="https://www.google.com/"></a>
                        </td>
                        <th class="table-title" colspan="2">戶籍地址</th>
                    </tr>
                    <tr>
                        <th class="table-title">身分證字號</th>
                        <td style=background-color:white; id="id-number"></td>
                        <th class="table-title">補換證</th>
                        <td style=background-color:white; id="replacement"></td>
                        <th class="table-title">配偶</th>
                        <td style=background-color:white;>
                            <a id="spouse" target="_blank" href="https://www.google.com/"></a>
                        </td>
                        <td style=background-color:white; id="address" colspan="2"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="table table-bordered table-hover table-striped">
        <tr>
            <th class="table-title">Google資訊</th>
        </tr>
        <tr>
            <td>
                <div id="content"></div>
            </td>
        </tr>
    </table>
</div>
<script>
	const v = new Vue({
		el:'#page-wrapper',
		computed: {
			column(){
				return 'google_status'
			}
		},
	})
</script>

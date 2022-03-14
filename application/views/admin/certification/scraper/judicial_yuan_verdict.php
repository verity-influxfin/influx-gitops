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
<script type="text/javascript">
    function fetchInfoData(user_id) {
        $.ajax({
            type: "GET",
            url: "/admin/scraper/judicial_yuan_info" + "?user_id=" + user_id,
            success: function (response) {
                if (response.status.code != 200) {
                    console.log(response.status.code)
                    return;
                }
                judicialyuanInfo = response.response;
                fillInfoData(judicialyuanInfo);
                // tabs
                const tabs = [judicialyuanInfo.name, judicialyuanInfo.father, judicialyuanInfo.mother, judicialyuanInfo.spouse]
                v.tabs = tabs.filter(x => x.length > 0)
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest.status);
                console.log(XMLHttpRequest.readyState);
                console.log(textStatus);
            },
        });
    }

    // 資料抓取
    function fetchjudicialyuan(user_id = '') {
        $.ajax({
            type: "GET",
            url: "/admin/scraper/judicial_yuan" + "?user_id=" + user_id + "&function=requestJudicialYuanVerdictsCount",
            success: function (response) {
                if (response.status.code != 200) {
                    console.log(response.status.code)
                    return;
                }
                countResponse = response.response;
                filljudicialYuanCount(countResponse);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest.status);
                console.log(XMLHttpRequest.readyState);
                console.log(textStatus);
            },
        });
    }

    // 更新內文
    function ajaxGetCase(user_id, case_type) {
        $('#case-content').empty();
        $.ajax({
            type: "GET",
            url: "/admin/scraper/judicial_yuan_case" + "?user_id=" + user_id + "&case_type=" + case_type +
                "&function=requestJudicialYuanVerdictsCase",
            async: false,
            success: function (response) {
                if (response.status.code != 200) {
                    console.log(response);
                    $('#case-content').append(`<center>沒有資料！<center>`)
                    return;
                }
                name = response.response.name;
                dataResponse = response.response;
                filljudicialyuanData(name, dataResponse);
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

    // 寫入textbox
    function inputCaseToTextBox(caseType) {
        $('#text-case-type').text('司法院資訊(' + caseType + ')');
        getNewCase(caseType);
    }

    // 開關內文
    function watchContent(id) {
        if (isClick == true) {
            $('#' + id).addClass('table-ellipsis');
            isClick = false;
        } else {
            $('#' + id).removeClass('table-ellipsis');
            isClick = true;
        }
    }

    // 獲取新檔案
    function getNewCase(case_type) {
        setTimeout(ajaxGetCase(user_id, case_type), 1000);
    }

    // count
    function create_count_html(type, count) {
        html = `<tr>
                    <th class="table-title"><a href="#" onclick="inputCaseToTextBox('${type}')" >${type}</a></th>
                    <td style=background-color:white;>${count}</td>
                </tr>`
        return html;
    }

    // case sample
    function create_case_html(name, url, title, date, location, content, i) {
        content = highlight(content, name, 'f-red');
        html = `<div class="form-group">
                    <table style="table-layout:fixed;" class="table table-bordered table-hover table-striped">
                        <tbody>
                            <tr>
                                <th class="table-title">裁判字號</th>
                                <td style=background-color:white;>
                                    <a class="fancyframe" target="_blank" href="${url}">${title}</a>
                                </td>
                            </tr>
                            <tr>
                                <th class="table-title">時間</th>
                                <td>${date}</td>
                            </tr>
                            <tr>
                                <th class="table-title">地點</th>
                                <td style=background-color:white;>${location}</td>
                            </tr>
                            <tr>
                                <th class="table-title">
                                    <a onclick="watchContent('${i}')">內容(點我展開)</a>
                                </th>
                                <td class="table-content table-ellipsis" id="${i}">
                                    ${content}
                                </td>
                            </tr>
                        </tbody>
                    <table>
                </div>`
        return html;
    }

    // 插入SQL資料
    function fillInfoData(judicialyuanInfoData) {
        if (!judicialyuanInfoData) {
            return;
        }
        idCardPlace = judicialyuanInfoData.id_card_place;
        idCardPlace = idCardPlace.split(')');
        $('#name').text(judicialyuanInfoData.name);
        $('#birthday').text(judicialyuanInfoData.birthday);
        $('#id-number').text(judicialyuanInfoData.id_number);
        $('#id-card-date').text(judicialyuanInfoData.id_card_date);
        $('#id_card_place').text(idCardPlace[0].replace('(', ''));
        $('#replacement').text(idCardPlace[1]);
        $('#father').text(judicialyuanInfoData.father);
        $('#mother').text(judicialyuanInfoData.mother);
        $('#born').text(judicialyuanInfoData.born);
        $('#spouse').text(judicialyuanInfoData.spouse);
        $('#address').text(judicialyuanInfoData.address);
    }

    // 插入count資料
    function filljudicialYuanCount(judicialYuanCount) {
        if (!judicialYuanCount) {
            return;
        }
        judicialYuanCount.cases.forEach((item) => {
            // console.log(item.case)
            // console.log(item.count)
            count_html = create_count_html(item.case, item.count);
            $('#count').append(count_html);
        });
    }

    // 插入爬蟲資料
    function filljudicialyuanData(name, dataResponse) {
        if (!dataResponse || dataResponse.result.length == 0) {
            $('#case-content').append(`<center>沒有資料！<center>`);
            return;
        }
        i = 0;
        console.log(dataResponse.result);
        dataResponse.result.forEach((item) => {
            // console.log(item.url);
            // console.log(item.title);
            // console.log(item.date);
            // console.log(item.type);
            // console.log(item.location);
            // console.log(item.content);
            case_html = create_case_html(name, item.url, item.title, item.date, item.location, item.content, i);
            $('#case-content').append(case_html);
            i += 1;
        });
    }

    $(document).ready(function () {
        let urlString = window.location.href;
        let url = new URL(urlString);
        user_id = url.searchParams.get("user_id");
        fetchInfoData(user_id);
        fetchjudicialyuan(user_id);

        $('#redo').on('click', () => {
            if (confirm('是否確定重新執行爬蟲？')) {
                axios.post('/admin/scraper/judicial_yuan_verdicts', {
                    name: $('#name').text(),
                    address: $('#address').text()
                }).then(({ data }) => {
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
            <h1>司法院判決案例</h1>
        </div>
        <div>
            <scraper-status-icon :column="column"></scraper-status-icon>
            <button class="btn btn-danger" id="redo">重新執行爬蟲</button>
        </div>
    </div>
    <table class="table table-bordered table-hover table-striped">
        <tbody>
            <tr>
                <th class="table-title">資料內容（實名認證）</th>
            </tr>
            <tr>
                <td>
                    <table class="table table-bordered table-hover table-striped">
                        <tr>
                            <th class="table-title">姓名</th>
                            <td style=background-color:white; id="name"></td>
                            <th class="table-title">發證日期</th>
                            <td style=background-color:white; id="id-card-date"></td>
                            <th class="table-title">父</th>
                            <td style=background-color:white; id="father"></td>
                            <th class="table-title">出生地</th>
                            <td style=background-color:white; id="born"></td>
                        </tr>
                        <tr>
                            <th class="table-title">出生年月日</th>
                            <td style=background-color:white; id="birthday"></td>
                            <th class="table-title">發證地點</th>
                            <td style=background-color:white; id="id_card_place"></td>
                            <th class="table-title">母</th>
                            <td style=background-color:white; id="mother"></td>
                            <th class="table-title" colspan="2">戶籍地址</th>
                        </tr>
                        <tr>
                            <th class="table-title">身分證字號</th>
                            <td style=background-color:white; id="id-number"></td>
                            <th class="table-title">補換證</th>
                            <td style=background-color:white; id="replacement"></td>
                            <th class="table-title">配偶</th>
                            <td style=background-color:white; id="spouse"></td>
                            <td style=background-color:white; id="address" colspan="2"></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <ul class="nav nav-pills mb-3">
        <li role="presentation" v-for="tab in tabs" :class="{active: tab===chooseTab}">
            <a @click="clickTab(tab)" :href="'#'+ tab">{{tab}}</a>
        </li>
    </ul>
    <table style="table-layout:fixed;" class="table table-bordered table-hover table-striped">
        <tr>
            <th class="table-title">司法院筆數(點選項目進行查詢資訊)</th>
        </tr>
        <tr>
            <td>
                <table style="table-layout:fixed;" class="table table-bordered table-hover table-striped">
                    <tr>
                        <th class="table-title">項目</th>
                        <th class="table-title">筆數</th>
                    </tr>
                    <tbody id="count"></tbody>
                </table>
            </td>
        </tr>
    </table>
    <table style="table-layout:fixed;" class="table table-bordered table-hover table-striped">
        <tr>
            <th class="table-title" id="text-case-type">司法院資訊</th>
        </tr>
        <tr>
            <td style=border-bottom-color:white;>
                <div class="col-lg-12" id="case-content"></div>
            </td>
        </tr>
        <tr>
            <td style=background-color:white;>
                <div class="d-flex justify-content-evenly">
                    <center>
                        <div class="btn-group" role="group" aria-label="Basic example"></div>
                    </center>
                </div>
            </td>
        </tr>
    </table>
</div>
<script>
    const v = new Vue({
        el: '#page-wrapper',
        data() {
            return {
                tabs: [],
                chooseTab: ''
            }
        },
        mounted() {
            const hash = decodeURI(location.hash.slice(1))
            this.chooseTab = decodeURI(location.hash.slice(1))
        },
        computed: {
            column() {
                return 'judicial_yuan_status'
            },
        },
        methods: {
            clickTab(tab) {
                this.chooseTab = tab
            }
        },
    })
</script>

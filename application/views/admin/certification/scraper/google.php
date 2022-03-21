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
<div id="page-wrapper">
    <div class="d-flex jcb aic page-header">
        <div>
            <h1>Google</h1>
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
    <ul class="nav nav-pills mb-3">
        <li role="presentation" v-for="tab in tabs" :class="{active: tab===chooseTab}">
            <a @click="clickTab(tab)" :href="'#'+ tab">{{tab}}</a>
        </li>
    </ul>
    <table class="table table-bordered table-hover table-striped">
        <tr>
            <th class="table-title d-flex aic">
                <div class="mr-4">Google資訊</div>
                <div>
                    <scraper-status-icon :show-status="status"></scraper-status-icon>
                    <button class="btn btn-danger" @click="doRedo" :disabled="status == 'loading'">重新執行爬蟲</button>
                </div>
            </th>
        </tr>
        <tr>
            <td>
                <div>
                    <div class="form-group">
                        <table style="table-layout:fixed;" class="table table-bordered table-hover table-striped"
                            v-for="(item,index) in googleInfos">
                            <tbody>
                                <tr>
                                    <td class="table-title">標題</td>
                                    <td style=background-color:white;>
                                        <a class="fancyframe" target="_blank" :href="item.url">{{item.title}}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-title">內容</td>
                                    <td>{{item.content}}</td>
                                </tr>
                                <tr>
                                    <td class="table-title">
                                        <a @click="watchContent(`case_content_${index}`)">內容(點我展開)</a>
                                    </td>
                                    <td class="table-content table-ellipsis" :id="`case_content_${index}`"
                                        v-html="highlight(item.contentAll, chooseTab,'f-red')">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>
<script>
    const v = new Vue({
        el: '#page-wrapper',
        computed: {
            column() {
                return 'google_status'
            }
        },
        mounted() {
            const url = new URL(location.href)
            this.userId = url.searchParams.get('user_id')
            const hash = decodeURI(location.hash.slice(1))
            this.chooseTab = decodeURI(location.hash.slice(1))
            this.getInfoData().then(() => {
                this.getTabData()
            })
        },
        data() {
            return {
                tabs: [],
                status: 'loading',
                googleInfos: '',
                chooseTab: ''

            }
        },
        methods: {
            watchContent(id) {
                $('#' + id).toggleClass('table-ellipsis');
            },
            clickTab(tab) {
                this.chooseTab = tab
                this.googleInfos = []
                this.getTabData()
            },
            highlight(content, what, spanClass) {
                pattern = new RegExp('([<.]*)(' + what + ')([<.]*)', 'gi'),
                    replaceWith = '$1<span ' + (spanClass ? 'class="' + spanClass + '"' : '') + '">$2</span>$3',
                    highlighted = content.replace(pattern, replaceWith);
                return highlighted;
            },
            getInfoData() {
                const fillInfoData = (judicialyuanInfoData) => {
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
                return axios.get(`/admin/scraper/verdict_google_info?user_id=${this.userId}`).then(({ data }) => {
                    if (data.status.code === 200) {
                        fillInfoData(data.response)
                        this.judicialYuanInfo = data.response
                        // tabs
                        const tabs = [data.response.name, data.response.father, data.response.mother, data.response.spouse]
                        this.tabs = tabs.filter(x => x.length > 0)
                        if (!this.chooseTab) {
                            this.chooseTab = data.response.name
                        }
                    }
                })
            },
            getTabData() {
                let name = null
                if (this.chooseTab) {
                    name = this.chooseTab
                }
                this.googleInfos = []
                this.status = 'loading'
                this.getStatus()
                return axios.get('/admin/scraper/google', {
                    params: {
                        user_id: this.userId,
                        name,
                    }
                }).then(({ data }) => {
                    if (data.status.code === 200) {
                        this.googleInfos = data.response.results
                    }
                    else {
                        alert(data.status.code + ' ' + data.status.message)
                    }
                })
            },
            getStatus() {
                return axios.get('/admin/scraper/google_status', {
                    params: {
                        name: this.chooseTab,
                    }
                }).then(({ data }) => {
                    this.status = data.google_status
                })
            },
            doRedo() {
                if (confirm('是否確定重新執行爬蟲？')) {
                    axios.post('/admin/scraper/request_google', {
                        keyword: this.chooseTab,
                    }).then(({ data }) => {
                        if (data.status == 200) {
                            location.reload()
                        }
                        else {
                            alert(data.message)
                        }
                    })
                }
            }
        },
    })
</script>

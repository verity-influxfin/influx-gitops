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

    .pointer {
        cursor: pointer;
    }
</style>
<script>
    $(document).ready(function () {
        let urlString = window.location.href;
        let url = new URL(urlString);
        user_id = url.searchParams.get("user_id");

        $('#redo').on('click', () => {

        })
    });
</script>
<div id="page-wrapper">
    <div class="d-flex jcb aic page-header">
        <div>
            <h1>司法院判決案例</h1>
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
            <th class="table-title d-flex aic">
                <div class="mr-4">
                    司法院筆數(點選項目進行查詢資訊)
                </div>
                <div>
                    <scraper-status-icon :show-status="status"></scraper-status-icon>
                    <button class="btn btn-danger" @click="doRedo" :disabled="status == 'loading'">重新執行爬蟲</button>
                </div>
            </th>
        </tr>
        <tr>
            <td>
                <table style="table-layout:fixed;" class="table table-bordered table-hover table-striped">
                    <tr>
                        <th class="table-title">項目</th>
                        <th class="table-title">筆數</th>
                    </tr>
                    <tbody id="count">
                        <tr v-for="item in cases">
                            <td class="table-title"><a @click="getCase(item.case)" class="pointer">{{ item.case }}</a>
                            </td>
                            <td style=background-color:white;>{{ item.count }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
    <table style="table-layout:fixed;" class="table table-bordered table-hover table-striped">
        <tr>
            <th class="table-title" id="text-case-type">司法院資訊</th>
        </tr>
        <table style="table-layout:fixed;" class="table table-bordered table-hover table-striped">
            <tbody v-for="(item,index) in infos">
                <tr>
                    <th class="table-title">裁判字號</th>
                    <td style=background-color:white;>
                        <a class="fancyframe" target="_blank" :href="item.url">{{item.title}}</a>
                    </td>
                </tr>
                <tr>
                    <th class="table-title">時間</th>
                    <td>{{item.date}}</td>
                </tr>
                <tr>
                    <th class="table-title">地點</th>
                    <td style=background-color:white;>{{item.location}}</td>
                </tr>
                <tr>
                    <th class="table-title">
                        <a @click="watchContent(`case_content_${index}`)" class="pointer">內容（點我展開）</a>
                    </th>
                    <td class="table-content table-ellipsis" :id="`case_content_${index}`">
                        {{item.content}}
                    </td>
                </tr>
            </tbody>
        </table>
</div>
<script>
    const v = new Vue({
        el: '#page-wrapper',
        data() {
            return {
                tabs: [],
                chooseTab: '',
                userId: '',
                judicialYuanInfo: {},
                cases: [],
                infos: [],
                status: 'loading'
            }
        },
        mounted() {
            const url = new URL(location.href);
            this.userId = url.searchParams.get('user_id');
            const hash = decodeURI(location.hash.slice(1))
            this.chooseTab = decodeURI(location.hash.slice(1))
            //apis
            this.getJudicialYuanInfo().then(() => {
                this.getTabData()
            })
        },
        computed: {
            column() {
                return 'judicial_yuan_status'
            },
        },
        methods: {
            clickTab(tab) {
                this.chooseTab = tab
                this.infos = []
                this.getTabData()
            },
            watchContent(id) {
                $('#' + id).toggleClass('table-ellipsis');
            },
            getJudicialYuanInfo() {
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
                        v.tabs = tabs.filter(x => x.length > 0)
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
                this.cases = []
                this.status = 'loading'
                this.getStatus()
                return axios.get('/admin/scraper/judicial_yuan_count', {
                    params: {
                        user_id: this.userId,
                        name,
                        address: this.judicialYuanInfo.address
                    }
                }).then(({ data }) => {
                    if (data.status.code === 200) {
                        this.cases = data.response.cases
                    }
                })
            },
            getCase(case_type) {
                return axios.get('/admin/scraper/judicial_yuan_case', {
                    params: {
                        user_id: this.userId,
                        name: this.chooseTab,
                        case_type,
                        address: this.judicialYuanInfo.address
                    }
                }).then(({ data }) => {
                    if (data.status.code === 200) {
                        this.infos = data.response.result
                    }
                })
            },
            getStatus() {
                return axios.get('/admin/scraper/judicial_yuan_status', {
                    params: {
                        name: this.chooseTab,
                    }
                }).then(({ data }) => {
                    this.status = data.judicial_yuan_status
                })
            },
            doRedo() {
                if (confirm('是否確定重新執行爬蟲？')) {
                    axios.post('/admin/scraper/judicial_yuan_verdicts', {
                        name: this.chooseTab,
                        address: this.judicialYuanInfo.address
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
        }
    })
</script>

<style lang="scss">
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
<div id="page-wrapper">
    <div class="d-flex jcb aic page-header">
        <h1>司法院判決案例</h1>
    </div>
    <div class="d-flex jcb aic page-header">
        <h2>實名資訊</h2>
    </div>
    <table class="table">
        <tbody>
            <tr>
                <th>姓名</th>
                <td>{{info.name}}</td>
                <th>發證日期</th>
                <td>{{info.id_card_date}}</td>
                <th>父</th>
                <td>{{info.father}}</td>
                <th>出生地</th>
                <td>{{info.born}}</td>
            </tr>
            <tr>
                <th>出生年月日</th>
                <td>{{info.birthday}}</td>
                <th>發證地點</th>
                <td>{{id_card_place}}</td>
                <th>母</th>
                <td>{{info.mother}}</td>
                <th>戶籍地址</th>
                <td>{{info.address}}</td>
            </tr>
            <tr>
                <th>身分證字號</th>
                <td>{{info.id_number}}</td>
                <th>補換證</th>
                <td>{{replacement}}</td>
                <th>配偶</th>
                <td>{{info.spouse}}</td>
                <th>ig帳號</th>
                <td>
                    <a target="_blank" :href="ig_url">{{info.instagram_username}}</a>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="d-flex jcb aic page-header">
        <h2>司法院筆數<span>(點選查詢詳細資訊)</span></h2>
        <div>
            <scraper-status-icon :show-status="status"></scraper-status-icon>
            <button class="btn btn-danger" @click="doRedo" :disabled="status == 'loading'">重新執行爬蟲</button>
        </div>
    </div>
    <ul class="nav nav-pills mb-3">
        <li role="presentation" v-for="tab in tabs" :class="{active: tab===chooseTab}">
            <a @click="clickTab(tab)" :href="'#'+ tab">{{tab}}</a>
        </li>
    </ul>
    <table class="table">
        <thead>
            <tr>
                <th>項目</th>
                <th>筆數</th>
            </tr>
        </thead>
        <tbody id="count">
            <tr v-for="item in cases">
                <td><a @click="getCase(item.case)" class="pointer">{{ item.case }}</a>
                </td>
                <td>{{ item.count }}</td>
            </tr>
        </tbody>
    </table>
    <div class="d-flex jcb aic page-header">
        <h2>司法院資訊</h2>
    </div>
    <table style="table-layout:fixed;" class="table">
        <tbody v-for="(item,index) in infos">
            <tr>
                <th>裁判字號</th>
                <td>
                    <a class="fancyframe" target="_blank" :href="item.url">{{item.title}}</a>
                </td>
            </tr>
            <tr>
                <th>時間</th>
                <td>{{item.date}}</td>
            </tr>
            <tr>
                <th>地點</th>
                <td>{{item.location}}</td>
            </tr>
            <tr>
                <th>
                    <a @click="watchContent(`case_content_${index}`)" class="pointer">內容(點我展開)</a>
                </th>
                <td class="table-content table-ellipsis" :id="`case_content_${index}`"
                    v-html="highlight(item.content, chooseTab,'f-red')">
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
                info: {},
                ig_url: '',
                id_card_place: '',
                replacement: '',
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
            highlight(content, what, spanClass) {
                pattern = new RegExp('([<.]*)(' + what + ')([<.]*)', 'gi'),
                    replaceWith = '$1<span ' + (spanClass ? 'class="' + spanClass + '"' : '') + '">$2</span>$3',
                    highlighted = content.replace(pattern, replaceWith);
                return highlighted;
            },
            watchContent(id) {
                $('#' + id).toggleClass('table-ellipsis');
            },
            getJudicialYuanInfo() {
                const fillInfoData = (info) => {
                    if (!info) {
                        return;
                    }
                    let idCardPlace = info.id_card_place.split(')')
                    this.id_card_place = idCardPlace[0].replace('(', '');
                    this.replacement = idCardPlace[1].replace(')', '');
                    this.ig_url = `https://www.instagram.com/${info.instagram_username}`;
                }
                return axios.get(`/admin/scraper/identity_info?user_id=${this.userId}`).then(({ data }) => {
                    if (data.status.code === 200) {
                        fillInfoData(data.response)
                        this.info = data.response
                        // tabs
                        const tabs = [data.response.name, data.response.father, data.response.mother, data.response.spouse, data.response.instagram_username]
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
                        address: this.info.address
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
                        address: this.info.address
                    }
                }).then(({ data }) => {
                    if (data.status.code === 200) {
                        this.infos = data.response.result
                    }
                })
            },
            getStatus() {
                const address = this.info.name == this.chooseTab ? this.info.address : null
                return axios.get('/admin/scraper/judicial_yuan_status', {
                    params: {
                        name: this.chooseTab,
                        address
                    }
                }).then(({ data }) => {
                    this.status = data.judicial_yuan_status
                })
            },
            doRedo() {
                if (confirm('是否確定重新執行爬蟲？')) {
                    // ig
                    if (this.chooseTab === this.info.instagram_username) {
                        return axios.post('/admin/scraper/judicial_yuan_verdicts', {
                            ig: this.chooseTab,
                            userid: this.userId
                        }).then(({ data }) => {
                            if (data.code == 200) {
                                if (data.response.status == 200) {
                                    location.reload()
                                }
                                else {
                                    alert(`子系統回應${data.response}，請洽工程師！`)
                                }
                            }
                            else {
                                alert(`http回應${data.code}，請洽工程師！`)
                            }
                        })
                    }
                    axios.post('/admin/scraper/judicial_yuan_verdicts', {
                        userid: this.userId,
                        name: this.chooseTab,
                        address: this.info.address
                    }).then(({ data }) => {
                        if (data.code == 200) {
                            if (data.response.status == 200) {
                                location.reload()
                            }
                            else {
                                alert(`子系統回應${data.response}，請洽工程師！`)
                            }
                        }
                        else {
                            alert(`http回應${data.code}，請洽工程師！`)
                        }
                    })
                }
            }
        }
    })
</script>

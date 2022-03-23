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
</style>
<div id="page-wrapper">
    <div class="d-flex jcb aic page-header">
        <div>
            <h1>Google</h1>
        </div>
    </div>
    <div class="d-flex jcb aic page-header">
        <h2>實名資訊</h2>
    </div>
    <table class="table">
        <tbody>
        <tr>
            <th>姓名</th>
            <td>
                <a target="_blank" :href="urls.name_url">{{info.name}}</a>
            </td>
            <th>發證日期</th>
            <td>{{info.id_card_date}}</td>
            <th>父</th>
            <td>
                <a target="_blank" :href="urls.father_url">{{info.father}}</a>
            </td>
            <th>出生地</th>
            <td>{{info.born}}</td>
        </tr>
        <tr>
            <th>出生年月日</th>
            <td>{{info.birthday}}</td>
            <th>發證地點</th>
            <td>{{id_card_place}}</td>
            <th>母</th>
            <td>
                <a target="_blank" :href="urls.mother_url">{{info.mother}}</a>
            </td>
            <th>戶籍地址</th>
            <td>{{info.address}}</td>
        </tr>
        <tr>
            <th>身分證字號</th>
            <td>{{info.id_number}}</td>
            <th>補換證</th>
            <td>{{replacement}}</td>
            <th>配偶</th>
            <td colspan="3">
                <a target="_blank" :href="urls.spouse_url">{{info.spouse}}</a>
            </td>
        </tr>
        </tbody>
    </table>
    <div class="d-flex jcb aic page-header">
        <h2>Google資訊</h2>
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
    <table style="table-layout:fixed;" class="table"
           v-for="(item,index) in googleInfos">
        <tbody>
        <tr>
            <th>標題</th>
            <td style=background-color:white;>
                <a class="fancyframe" target="_blank" :href="item.url">{{item.title}}</a>
            </td>
        </tr>
        <tr>
            <th>內容</th>
            <td>{{item.content}}</td>
        </tr>
        <tr>
            <th>
                <a @click="watchContent(`case_content_${index}`)">內容(點我展開)</a>
            </th>
            <td class="table-content table-ellipsis" :id="`case_content_${index}`"
                v-html="highlight(item.contentAll, chooseTab,'f-red')">
            </td>
        </tr>
        </tbody>
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
        data() {
            return {
                tabs: [],
                status: 'loading',
                googleInfos: '',
                chooseTab: '',
                info: {},
                urls: {
                    name_url: '',
                    father_url: '',
                    mother_url: '',
                    spouse_url: '',
                }
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
                const fillInfoData = (info) => {
                    if (!info) {
                        return;
                    }
                    let idCardPlace = info.id_card_place.split(')')
                    this.id_card_place = idCardPlace[0].replace('(', '');
                    this.replacement = idCardPlace[1].replace(')', '');
                    this.urls.name_url = `https://www.google.com/search?q="${info.name}"&num=100`;
                    this.urls.father_url = `https://www.google.com/search?q="${info.father}"&num=100`;
                    this.urls.mother_url = `https://www.google.com/search?q="${info.mother}"&num=100`;
                    this.urls.spouse_url = `https://www.google.com/search?q="${info.spouse}"&num=100`;
                }
                return axios.get(`/admin/scraper/identity_info?user_id=${this.userId}`).then(({data}) => {
                    if (data.status.code === 200) {
                        fillInfoData(data.response)
                        this.info = data.response
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
                }).then(({data}) => {
                    if (data.status.code === 200) {
                        this.googleInfos = data.response.results
                    } else {
                        alert(data.status.code + ' ' + data.status.message)
                    }
                })
            },
            getStatus() {
                return axios.get('/admin/scraper/google_status', {
                    params: {
                        name: this.chooseTab,
                    }
                }).then(({data}) => {
                    this.status = data.google_status
                })
            },
            doRedo() {
                if (confirm('是否確定重新執行爬蟲？')) {
                    axios.post('/admin/scraper/request_google', {
                        keyword: this.chooseTab,
                    }).then(({data}) => {
                        if (data.code == 200) {
                            if (data.response.status == 200) {
                                location.reload()
                            } else {
                                alert(`子系統回應${data.response}，請洽工程師！`)
                            }
                        } else {
                            alert(`http回應${data.code}，請洽工程師！`)
                        }
                    })
                }
            }
        }
    })
</script>

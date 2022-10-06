<style lang="scss">
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
            <h1>內政部戶政司</h1>
        </div>
        <div>
            <scraper-status-icon :column="column"></scraper-status-icon>
            <button class="btn btn-danger" id="redo" @click="doRedo">重新執行爬蟲</button>
        </div>
    </div>
    <div class="d-flex jcb aic page-header">
        <div>
            <h2>實名資訊</h2>
        </div>
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
            <td colspan="3">{{info.spouse}}</td>
        </tr>
        </tbody>
    </table>
    <div class="d-flex jcb aic page-header">
        <div>
            <h2>內政部戶政司資訊</h2>
        </div>
    </div>
    <div></div>
    <table class="table">
        <tbody>
        <tr>
            <th>送出的查詢資料</th>
            <td>上方實名資料之「身分證字號」,「補換證」,「發證日期」,「發證地點」欄位。<br />
                <span style="color: red">注意：同一身分證字號重複執行失敗三次以上將被封鎖。</span></td>
            <th>查驗結果</th><td>{{result}}</td>
        </tr>
        </tbody>
    </table>
</div>
<script>
    const v = new Vue({
        el: '#page-wrapper',
        data() {
            return {
                userId: '',
                info: {},
                id_card_place: '',
                replacement: '',
                status: 'loading',
                result: ''
            }
        },
        computed: {
            column() {
                return 'household_registration_status'
            }
        },
        mounted() {
            const url = new URL(location.href);
            this.userId = url.searchParams.get('user_id');
            //apis
            this.getJudicialYuanInfo().then(() => {
                this.getIdCardApiResult()
            })
        },
        methods: {
            getJudicialYuanInfo() {
                const fillInfoData = (info) => {
                    if (!info) {
                        return;
                    }
                    let idCardPlace = info.id_card_place.split(')')
                    this.id_card_place = idCardPlace[0].replace('(', '');
                    this.replacement = idCardPlace[1].replace(')', '');
                }
                return axios.get(`/admin/scraper/identity_info?user_id=${this.userId}&omit_ig`).then(({data}) => {
                    if (data.status.code === 200) {
                        fillInfoData(data.response)
                        this.info = data.response
                    }
                })
            },
            getIdCardApiResult() {
                return axios.get(`/admin/scraper/id_card_api_result?user_id=${this.userId}`).then(({data}) => {
                    if (data.status.code === 200) {
                        this.result = data.response.result
                    }
                })
            },
            doRedo() {
                if (confirm('是否確定重新執行爬蟲？')) {
                    axios.put('/admin/scraper/id_card_api', {
                        user_id: user_id
                    }).then(({data}) => {
                        if (data.status.code === 200) {
                            location.reload()
                        } else if (data.status.code === 587) {
                            alert(data.response.message)
                        } else {
                            alert(`http回應 ${data.status.code}，請洽工程師！`)
                        }
                    })
                }
            }
        }
    })
</script>

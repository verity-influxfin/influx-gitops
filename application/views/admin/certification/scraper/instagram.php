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
            <h1>Instagram</h1>
        </div>
        <div>
            <scraper-status-icon :column="column"></scraper-status-icon>
            <button class="btn btn-info" id="follow" @click="doRedoFollow">追蹤</button>
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
            <td>{{info.spouse}}</td>
            <th>ig帳號</th>
            <td>
                <a target="_blank" :href="ig_url">{{info.instagram_username}}</a>
            </td>
        </tr>
        </tbody>
    </table>
    <div class="d-flex jcb aic page-header">
        <div>
            <h2>Instagram資訊</h2>
        </div>
    </div>
    <table class="table">
        <tbody>
            <tr>
                <th>帳號是否存在</th>
                <th>是否為私人帳號</th>
                <th>是否追蹤官方帳號</th>
            </tr>
            <tr>
                <td>{{is_exist}}</td>
                <td>{{is_private}}</td>
                <td>{{is_follower}}</td>
            </tr>
            <tr>
                <th>總貼文數</th>
                <th>追蹤數</th>
                <th>粉絲數</th>

            </tr>
            <tr>
                <td>{{igData.posts}}</td>
                <td>{{igData.following}}</td>
                <td>{{igData.followers}}</td>
            </tr>
            <tr>
                <th>系統爬蟲帳號是否追蹤此帳號</th>
                <th>3個月內po文</th>
                <th>關鍵字命中(全球、財經、數位、兩岸)</th>
            </tr>
            <tr>
                <td>{{follow_status}}</td>
                <td>{{igData.postsIn3Months}}</td>
                <td>{{igData.postsWithKeyWords}}</td>
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
                igData: {},
                is_exist: '',
                is_private: '',
                is_follower: '',
                follow_status: '',
                cases: [],
                infos: [],
                status: 'loading'
            }
        },
        computed: {
            column() {
                return 'instagram_status'
            }
        },
        mounted() {
            const url = new URL(location.href);
            this.userId = url.searchParams.get('user_id');
            const hash = decodeURI(location.hash.slice(1))
            this.chooseTab = decodeURI(location.hash.slice(1))
            //apis
            this.getJudicialYuanInfo().then(() => {
                this.fillInstagramData()
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
                    this.ig_url = `https://www.instagram.com/${info.instagram_username}`;
                }
                return axios.get(`/admin/scraper/identity_info?user_id=${this.userId}`).then(({data}) => {
                    if (data.status.code === 200) {
                        fillInfoData(data.response)
                        this.info = data.response
                    }
                })
            },
            fillInstagramData() {
                const fillData = (igData) => {
                    if (!igData) {
                        return false;
                    }
                    switch (igData.followStatus) {
                        case 'followed':
                            this.follow_status = '追蹤中'
                            break;
                        case 'waitingFollowAccept':
                            this.follow_status = '已送出請求'
                            break;
                        case 'unfollowed':
                            this.follow_status = '未追蹤'
                            break;
                        default:
                            this.follow_status = ''
                    }
                    this.is_exist = igData.isExist ? '是' : '否';
                    this.is_private = igData.isPrivate ? '是' : '否';
                    this.is_follower = igData.isFollower ? '是' : '否';
                }
                return axios.get(`/admin/scraper/instagram?user_id=${this.userId}&ig_username=${this.info.instagram_username}`)
                    .then(({data}) => {
                        if (data.status.code === 200) {
                            fillData(data.response.result)
                            this.igData = data.response.result
                        }
                    })
            },
            doRedo() {
                if (confirm('是否確定重新執行爬蟲？')) {
                    axios.post('/admin/scraper/updateIGUserInfo', {
                        userId: user_id,
                        followed_account: $('#ig-username').text(),
                    }).then(({data}) => {
                        if (data.code == 200) {
                            if (data.response.status == 200) {
                                location.reload()
                            } else {
                                alert(`子系統回應${data.response.status}，請洽工程師！`)
                            }
                        } else {
                            alert(`http回應${data.code}，請洽工程師！`)
                        }
                    })
                }
            },
            doRedoFollow() {
                if (!confirm('是否確定重新送出追蹤請求？')) {
                    return
                }
                axios.post('/admin/scraper/autoFollowIG', {
                    userId: user_id,
                    followed_account: $('#ig-username').text(),
                }).then(({data}) => {
                    if (data.code == 200) {
                        if (data.response.status == 200) {
                            alert('已成功送出追蹤')
                        } else {
                            alert(`子系統回應${data.response}，請洽工程師！`)
                        }
                    } else {
                        alert(`http回應${data.code}，請洽工程師！`)
                    }
                })
            }
        }
    })
</script>

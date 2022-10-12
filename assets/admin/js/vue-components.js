Vue.component('v-page', {
    props: ['data', 'maxLength'],
    computed: {
        has_prev: function () {
            return this.$props['data']['current_page'] > 1;
        },
        has_next: function () {
            return this.$props['data']['current_page'] < this.$props['data']['last_page'];
        },
        page_range: function () {
            let current_page = this.$props['data']['current_page'];
            let last_page = this.$props['data']['last_page'];
            const length = last_page
            const maxLength = this.maxLength ?? 7
            if (length < maxLength) {
                return this.range(1, length)
            }
            const even = maxLength % 2 === 0 ? 1 : 0
            const left = Math.floor(maxLength / 2)
            const right = length - left + 1 + even
            if (current_page > left && current_page < right) {
                const firstItem = 1
                const lastItem = length
                const start = current_page - left + 2
                const end = current_page + left - 2 - even
                const secondItem = start - 1 === firstItem + 1 ? 2 : '...'
                const beforeLastItem = end + 1 === lastItem - 1 ? end + 1 : '...'

                return [1, secondItem, ...this.range(start, end), beforeLastItem, length]
            } else if (current_page === left) {
                const end = current_page + left - 1 - even
                return [...this.range(1, end), '...', length]
            } else if (current_page === right) {
                const start = current_page - left + 1
                return [1, '...', ...this.range(start, length)]
            } else {
                return [
                    ...this.range(1, left),
                    '...',
                    ...this.range(right, length),
                ]
            }
        }
    },
    template: `
<nav>
    <ul class="pagination">
        <li :class="{disabled: !has_prev}">
            <a href="#" @click.prevent="prev_page()">
                <span>&laquo;</span>
            </a>
        </li>
        <li v-for="page in page_range" :class="{active: page==data.current_page}">
            <a href="#" v-if="isNumber(page)" @click.prevent="change_page(page)">{{page}}</a>
            <a v-else>{{page}}</a>
            
        </li>
        <li :class="{disabled: !has_next}">
            <a href="#" @click.prevent="next_page()">
                <span>&raquo;</span>
            </a>
        </li>
    </ul>
</nav>
`,
    methods: {
        change_page: function (page) {
            if (this.$props['data']['current_page'] != page) {
                this.$emit('change_page', page);
            }
        },
        next_page: function () {
            if (this.has_next) {
                this.change_page(this.$props['data']['current_page'] + 1);
            }
        },
        prev_page: function () {
            if (this.has_prev) {
                this.change_page(this.$props['data']['current_page'] - 1);
            }
        },
        range(from, to) {
            const range = []
            from = from > 0 ? from : 1
            for (let i = from; i <= to; i++) {
                range.push(i)
            }
            return range
        },
        isNumber(n) {
            return !isNaN(Number(n))
        }
    }
});

Vue.component('blog-post', {
    props: ['title'],
    template: '<h3>{{ title }}</h3>'
});

// scraper

Vue.component('scraper-status', {
    props: ['userId'],
    template: `
        <div class="panel panel-default">
            <div class="panel-heading">
                <div v-if="userId">會員爬蟲列表</div>
                <div class="d-flex aic my-2">
<!--					<div>圖示說明：   </div>-->
                    <div>
                        <button type="button" class="btn btn-success btn-circle">
                            <i class="fa fa-check"></i>
                        </button>
                        <label>執行成功</label>
                    </div>
                    <div class="mx-3">
                        <button type="button" class="btn btn-danger btn-circle">
                            <i class="fa fa-times"></i>
                        </button>
                        <label>執行失敗</label>
                    </div>
                    <div>
                        <button type="button" class="btn btn-default btn-circle">
                            <i class="fa fa-pause"></i>
                        </button>
                        <label>等待執行</label>
                    </div>
                    <div class="mx-3">
                        <button type="button" class="btn btn-warning btn-circle">
                            <i class="fa fa-refresh"></i>
                        </button>
                        <label>執行中</label>
                    </div>
                    <div>
                        <button type="button" class="btn btn-default btn-circle">
                            <i class="fa fa-info"></i>
                        </button>
                        <label>尚未執行</label>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div v-if="!userId" class="d-flex aic">
                    <div>ID搜尋：</div>
                    <div class="input-group input mx-2">
                        <input class="form-control" type="text" id="id-textbox"
                            onkeyup="this.value=this.value.replace(/[^0-9]/g,'');">
                    </div>
                    <button type="button" @click="fetchStatus" class="btn btn-primary btn-sm">查詢</button>
                </div>
                <table id="scraper-status-table">
                    <thead>
                        <tr>
                            <th>會員編號</th>
                            <th>內政部戶政司</th>
                            <th>司法院判決案例</th>
                            <th>Google</th>
                            <th>Instagram</th>
                            <th>SIP學生資訊</th>
                            <th>經濟部商業司</th>
                            <th>財務部稅籍登記</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div v-if="judicial_yuan_fail" class="alert alert-danger mt-2" role="alert">
                    <i class="fa fa-warning"></i>
                    司法院爬蟲尚未執行
                </div>
            </div>
        </div>
    `,
    mounted() {
        $(document).ready(() => {
            let urlString = window.location.href;
            let url = new URL(urlString);
            const t2 = $('#scraper-status-table').DataTable({
                'ordering': false,
                "paging": false,
                "info": false,
                "searching": false,
                'language': {
                    'processing': '處理中...',
                    'lengthMenu': '顯示 _MENU_ 項結果',
                    'zeroRecords': '目前無資料',
                    'info': '顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項',
                    'infoEmpty': '顯示第 0 至 0 項結果，共 0 項',
                    'infoFiltered': '(從 _MAX_ 項結果過濾)',
                    'search': '使用本次搜尋結果快速搜尋',
                    'paginate': {
                        'first': '首頁',
                        'previous': '上頁',
                        'next': '下頁',
                        'last': '尾頁'
                    }
                },
                "info": false
            })
            if (this.userId) {
                this.getAjax(this.userId)
            }
        });
    },
    data() {
        return {
            judicial_yuan_fail: false
        }
    },
    methods: {
        fetchStatus() {
            user_id = $('#id-textbox').val();
            if (user_id != '') {
                this.getAjax(user_id);
            }
        },
        getAjax(user_id) {
            $.ajax({
                type: "GET",
                url: "/admin/scraper/scraper_status" + "?user_id=" + user_id,
                async: false,
                success: (response) => {
                    if (response.status.code != 200) {
                        console.log(response.status.code)
                        return false;
                    }
                    // console.log(response);
                    statusResponse = response.response;
                    this.create_table(user_id, response.response)
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest.status);
                    console.log(XMLHttpRequest.readyState);
                    console.log(textStatus);
                },
            });
        },
        create_table(user_id, statusResponse) {
            const t2 = $('#scraper-status-table').DataTable()
            const judicial_yuan_html = this.create_table_content(statusResponse.judicial_yuan_status, '/admin/Scraper?view=judicial_yuan_verdict&user_id=' + user_id);
            const household_registration_html = this.create_table_content(statusResponse.household_registration_status, '/admin/Scraper?view=household_registration&user_id=' + user_id);
            const sip_html = this.create_table_content(statusResponse.sip_status, '/admin/Scraper?view=sip&user_id=' + user_id);
            const biz_html = this.create_table_content(statusResponse.biz_status, '/admin/Scraper?view=biz&user_id=' + user_id);
            const business_registration_html = this.create_table_content(statusResponse.business_registration_status, '/admin/Scraper?view=business_registration&user_id=' + user_id);
            const google_html = this.create_table_content(statusResponse.google_status, '/admin/Scraper?view=google&user_id=' + user_id);
            const instagram_html = this.create_table_content(statusResponse.instagram_status, '/admin/Scraper?view=instagram&user_id=' + user_id);
            t2.clear()
            t2.row.add([
                user_id,
                household_registration_html,
                judicial_yuan_html,
                google_html,
                instagram_html,
                sip_html,
                biz_html,
                business_registration_html
            ])
            t2.draw()
            console.log(statusResponse.judicial_yuan_status)
            if (
                statusResponse.judicial_yuan_status != 'finished' &&
                statusResponse.judicial_yuan_status != 'failure' &&
                statusResponse.judicial_yuan_status != 'requested' &&
                statusResponse.judicial_yuan_status != 'started'
            ) {
                this.judicial_yuan_fail = true
            } else {
                console.log(statusResponse.judicial_yuan_status)
                this.judicial_yuan_fail = false
            }
        },
        create_table_content(status, url) {
            if (url == '#') {
                target = "_self"
            } else {
                target = "_blank"
            }
            if (status == 'finished') {
                return `<a href="${url}" target="${target}">
                        <button class="btn btn-success btn-circle btn-sm">
                               <i class="fa fa-check"></i>
                        </button>
                    </a>`;
            } else if (status == 'failure') {
                return `<a href="${url}" target="${target}">
                        <button class="btn btn-danger btn-circle btn-sm">
                            <i class="fa fa-times"></i>
                        </button>
                    </a>`;
            } else if (status == 'requested') {
                return `<a href="${url}" target="${target}">
                        <button class="btn btn-secondary btn-circle btn-sm">
                            <i class="fa fa-pause"></i>
                        </button>
                    </a>`;
            } else if (status == 'started') {
                return `<a href="${url}" target="${target}">
                        <button class="btn btn-warning btn-circle btn-sm">
                            <i class="fa fa-refresh"></i>
                        </button>
                    </a>`;
            } else
                return `
                    <a href="${url}" target="${target}">
                        <button class="btn btn-default btn-circle btn-sm">
                            <i class="fa fa-info"></i>
                        </button>
                    </a>
                `;
        }
    },
})

Vue.component('scraper-status-icon', {
    props: ['column', 'showStatus'],
    template: `
    <span>
        <button class="btn btn-default btn-circle btn-sm" v-if="status == 'loading'">
            <i class="fa fa-refresh"></i>
        </button>
        <button class="btn btn-success btn-circle btn-sm" v-else-if="status == 'finished'">
            <i class="fa fa-check"></i>
        </button>
        <button class="btn btn-danger btn-circle btn-sm" v-else-if="status == 'failure'">
            <i class="fa fa-times"></i>
        </button>
        <button class="btn btn-secondary btn-circle btn-sm" v-else-if="status == 'requested'">
            <i class="fa fa-pause"></i>
        </button>

        <button class="btn btn-warning btn-circle btn-sm" v-else-if="status == 'started'">
            <i class="fa fa-refresh"></i>
        </button>
        <button class="btn btn-default btn-circle btn-sm" v-else>
            <i class="fa fa-info"></i>
        </button>
    </span>
    `,
    data() {
        return {
            status: ''
        }
    },
    mounted() {
        if (!this.showStatus) {
            let url = new URL(location.href);
            user_id = url.searchParams.get("user_id");
            this.getStatus(user_id)
        }
    },
    methods: {
        getStatus(user_id) {
            $.ajax({
                type: "GET",
                url: "/admin/scraper/scraper_status" + "?user_id=" + user_id,
                async: false,
                success: (response) => {
                    if (response.status.code != 200) {
                        console.log(response.status.code)
                        return false;
                    }
                    // console.log(response);
                    this.status = response.response[this.column] ?? ''
                },
                error: function (XMLHttpRequest, textStatus) {
                    console.log(XMLHttpRequest.status);
                    console.log(XMLHttpRequest.readyState);
                    console.log(textStatus);
                },
            });
        }
    },
    watch: {
        showStatus: {
            handler(v) {
                this.status = v
            },
            immediate: true,
        }
    },
})

/**
 * Simple object check.
 * @param item
 * @returns {boolean}
 */
function isObject(item) {
	return (item && typeof item === 'object' && !Array.isArray(item));
}

/**
 * Deep merge two objects.
 * @param target
 * @param ...sources
 */
function mergeDeep(target, ...sources) {
	if (!sources.length) return target;
	const source = sources.shift();

	if (isObject(target) && isObject(source)) {
		for (const key in source) {
			if (isObject(source[key])) {
				if (!target[key]) Object.assign(target, { [key]: {} });
				mergeDeep(target[key], source[key]);
			} else {
				Object.assign(target, { [key]: source[key] });
			}
		}
	}

	return mergeDeep(target, ...sources);
}

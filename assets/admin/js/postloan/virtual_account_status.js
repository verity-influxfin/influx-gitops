var app = new Vue({
    el: '#page-wrapper',
    data: {
        searchform: {
            user_id_int: '',
        },
        is_waiting_response: false,
        virtual_account_status: {
            id_int: null,
            uesr_id_int: null,
            status_int: null,
            created_at_ts_sec_int: null,
            sum_virtual_passbook_amount_int: null
        },
        editStatus: null,
    },
    methods: {
        doSearch() {
            const { user_id_int } = this.searchform
            const string = Object.entries({ user_id_int })
                .filter(([key, value]) => value !== '')
                .map(([key, value]) => `${key}=${value}`)
                .join('&')
            // axios get get_virtual_account_status
            this.is_waiting_response = true
            axios.get('/admin/postLoan/get_virtual_account_status?' + string)
                .then(({ data }) => {
                    this.virtual_account_status = data
                    this.editStatus = data.status_int
                }).catch((error) => {
                    alert('子系統錯誤或無回應:' + error)
                }).finally(() => {
                    this.is_waiting_response = false
                })
        },
        doEdit() {
            if (!confirm('確定更改狀態？')) return
            this.is_waiting_response = true
            const { uesr_id_int } = this.virtual_account_status
            axios.post(`/admin/postLoan/post_virtual_account_status`, {
                user_id_int: uesr_id_int,
                status_int: this.editStatus
            })
                .then(() => {
                    this.doSearch()
                }).catch((error) => {
                    this.is_waiting_response = true
                    alert('子系統錯誤或無回應:' + error)
                })
        },
        amount: function (value) {
            if (value) {
                return value.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ',')
            }
            return value
        },
        formatTime(value) {
            return moment(value * 1000).format('YYYY-MM-DD HH:mm:ss')
        },
        statusText(status) {
            switch (status) {
                case 0:
                    return '凍結中';
                case 1:
                    return '正常';
                case 2:
                    return '使用中';
                default:
                    return '未知狀態';
            }
        }
    }
})

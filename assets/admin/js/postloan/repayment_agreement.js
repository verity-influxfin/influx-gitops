var app = new Vue({
    el: '#page-wrapper',
    data: {
        searchform: {
            user_id_int: '',
            total_repayment_amount_int: '',
            sum_agreement_penalty_amount_int: ''
        },
        is_waiting_response: false,
        repayment_agreement: {
            user_id_int: null,
            raTarget_list: [],
            sum_receivable_principal_amount_int: null,
            sum_receivable_penalty_amount_int: null,
            sum_agreement_penalty_amount_int: null,
            total_repayment_amount_int: null,
            agreement_amount_int: null,
        },
        editStatus: null,
    },
    methods: {
        doSearch() {
            let url = '/admin/postLoan/get_repayment_agreement?'
            // build params form searchform
            const { user_id_int, total_repayment_amount_int, sum_agreement_penalty_amount_int } = this.searchform
            url += Object.entries({ user_id_int, total_repayment_amount_int, sum_agreement_penalty_amount_int })
                .filter(([key, value]) => value !== '')
                .map(([key, value]) => `${key}=${value}`)
                .join('&')
            this.is_waiting_response = true
            axios.get(url)
                .then(({ data }) => {
                    this.repayment_agreement = data
                }).catch((error) => {
                    alert('子系統錯誤或無回應:' + error)
                }).finally(() => {
                    this.is_waiting_response = false
                })
        },
        confirmRepayment() {
            if (!confirm('是否要確立 清償協議表？')) return
            this.is_waiting_response = true
            let url = '/admin/postLoan/repayment_agreement_confirm?'
            const { user_id_int, total_repayment_amount_int, sum_agreement_penalty_amount_int } = this.searchform
            url += Object.entries({ user_id_int, total_repayment_amount_int, sum_agreement_penalty_amount_int })
                .filter(([key, value]) => value !== '')
                .map(([key, value]) => `${key}=${value}`)
                .join('&')
            axios.post(url)
                .then(() => {
                    this.doSearch()
                }).catch((error) => {
                    this.is_waiting_response = false
                    alert('子系統錯誤或無回應:' + error)
                })
        },
        downloadExcel() {
            let url = '/admin/postLoan/repayment_agreement_sheet?'
            // build params form searchform
            const { user_id_int, total_repayment_amount_int, sum_agreement_penalty_amount_int } = this.searchform
            url += Object.entries({ user_id_int, total_repayment_amount_int, sum_agreement_penalty_amount_int })
                .filter(([key, value]) => value !== '')
                .map(([key, value]) => `${key}=${value}`)
                .join('&')
            this.is_waiting_response = true
            axios({
                url,
                methods: 'GET',
                responseType: 'blob'
            }).then(res => {
                const url = window.URL.createObjectURL(new Blob([res.data]))
                const link = document.createElement('a')
                link.href = url
                const filename = res.headers["content-disposition"].split("filename=")[1]
                link.download = filename.slice(1, filename.length - 1)
                document.body.appendChild(link);
                link.click();
            }).catch((error) => {
                alert(error)
            }).finally(() => {
                this.is_waiting_response = false
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
        formatRatio(value) {
            if (isNaN(Number(value))) return value
            return `${(value * 100).toFixed(3)}%`
        }
    }
})

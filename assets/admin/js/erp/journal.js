var app = new Vue({
    el: '#page-wrapper',
    data: {
        searchform: {
            start_date: '',
            end_date: moment().format('YYYY-MM-DD'),
            user_id_int: '',
        },
        table_data: [],
        erp_balance_data: [],
        tab: 'tab1',
        is_waiting_response: false,
    },
    watch: {
        tab(n) {
            if (n === 'tab2') {
                this.searchform.start_date = moment().subtract(1, 'days').format('YYYY-MM-DD')
                this.searchform.end_date = moment().subtract(1, 'days').format('YYYY-MM-DD')
            } else {
                this.searchform.start_date = ''
                this.searchform.end_date = moment().format('YYYY-MM-DD')
            }
        }
    },
    mounted() {
        var self = this;
        $('#start_date').datepicker({
            'format': 'yyyy-mm-dd',
        }).on('change', function () { self.searchform.start_date = this.value });

        $('#end_date').datepicker({
            'format': 'yyyy-mm-dd',
        }).on('change', function () { self.searchform.end_date = this.value });
    },
    methods: {
        doSearch() {
            this.is_waiting_response = true
            const { start_date, end_date, user_id_int } = this.searchform
            const string = Object.entries({ start_date, end_date, user_id_int })
                .filter(([key, value]) => value !== '')
                .map(([key, value]) => `${key}=${value}`)
                .join('&')
            // axios get get_journal_data
            axios.get('/admin/erp/get_journal_data?' + string).then(({ data }) => {
                this.table_data = data
            }).catch((error) => {
                alert('子系統錯誤或無回應:' + error)
            }).finally(() => {
                this.is_waiting_response = false
            })
        },
        doErpSearch() {
            this.is_waiting_response = true
            const { start_date, end_date } = this.searchform
            const string = Object.entries({ start_date, end_date })
                .filter(([key, value]) => value !== '')
                .map(([key, value]) => `${key}=${value}`)
                .join('&')
            // axios get erp_balance_sheet
            axios.get('/admin/erp/erp_balance_sheet?' + string).then(({ data }) => {
                this.erp_balance_data = data.row_list
            }).catch((error) => {
                alert('子系統錯誤或無回應:' + error)
            }).finally(() => {
                this.is_waiting_response = false
            })
        },
        formateTime(value) {
            return moment(value).format('YYYY-MM-DD HH:mm:ss')
        },
        amount(value) {
            if (value) {
                return value.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ',')
            }
            return '__'
        },
        dcAmount(value, dc_code_value, dc_code) {
            if (dc_code == dc_code_value) {
                return this.amount(value)
            }
            return ''
        }
    }
})

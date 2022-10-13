var app = new Vue({
    el: '#page-wrapper',
    data: {
        searchform: {
            start_date: moment().subtract(2, 'months').format('YYYY-MM-10'),
            end_date: moment().format('YYYY-MM-10'),
            user_id_int: '',
        },
        table_data: [],
        is_waiting_response: false,
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
            // axios get get_journal_data
            axios.get('/admin/erp/get_journal_data', {
                params: this.searchform
            }).then(({ data }) => {
                this.table_data = data
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

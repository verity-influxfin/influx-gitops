var app = new Vue({
    el: '#page-wrapper',
    data: {
        searchform: {
            start_date: '',
            end_date: moment().format('YYYY-MM-DD'),
            user_id_int: '',
            investment_id_int_list_str: ''
        },
        is_waiting_response: false,
        replayment_list: [],
        replayment_list_latest: [],
        tab:'tab1'
    },
    computed: {
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
            const { start_date, end_date, user_id_int, investment_id_int_list_str } = this.searchform
            const string = Object.entries({ start_date, end_date, user_id_int, investment_id_int_list_str })
                .filter(([key, value]) => value !== '')
                .map(([key, value]) => `${key}=${value}`)
                .join('&')
            // axios get get_replayment_data
            this.is_waiting_response = true
            axios.get('/admin/erp/get_replayment_data?' + string)
                .then(({ data }) => {
                    this.replayment_list = data
                }).catch((error) => {
                    alert('子系統錯誤或無回應:' + error)
                }).finally(() => {
                    this.is_waiting_response = false
                })
        },
        doSearchLatest() {
            const { start_date, end_date, user_id_int, investment_id_int_list_str } = this.searchform
            const string = Object.entries({ start_date, end_date, user_id_int, investment_id_int_list_str })
                .filter(([key, value]) => value !== '')
                .map(([key, value]) => `${key}=${value}`)
                .join('&')
            // axios get get_replayment_data
            this.is_waiting_response = true
            axios.get('/admin/erp/get_stack_replayment_schedule?' + string)
                .then(({ data }) => {
                    this.replayment_list_latest = data
                }).catch((error) => {
                    alert('子系統錯誤或無回應:' + error)
                }).finally(() => {
                    this.is_waiting_response = false
                })
        },
        amount: function (value) {
            return value.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ',')
        },
        formatTime(value) {
            return moment(value).format('YYYY-MM-DD HH:mm:ss')
        },
        eventCode(code) {
            /*
            10: 正常放款 (產生)
            11: 債權受讓 (產生)
            20: 發生逾期 (突發)
            21: 逾期本攤 (突發)
            22: 逾期還款 (突發)
            30: 債權出讓 (突發結束)
            31: 提前清償 (突發結束)
            */
            switch (Number(code)) {
                case 10:
                    return '正常放款'
                case 11:
                    return '債權受讓'
                case 20:
                    return '發生逾期'
                case 21:
                    return '逾期本攤'
                case 22:
                    return '逾期還款'
                case 30:
                    return '債權出讓'
                case 31:
                    return '提前清償'
                default:
                    return '未知'
            }
        }
    }
})

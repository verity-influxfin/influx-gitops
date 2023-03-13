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
        tab: 'tab1',
        hide: false
    },
    computed: {
        eyeIcon() {
            return this.hide ? 'fa-eye-slash' : 'fa-eye'
        },
        filteredReplaymentListLatest() {
            if (this.hide) {
                // use moment get this month last day
                const lastDay = moment().startOf('month').format('YYYY-MM-DD')
                const replayment_list_latest = { ...this.replayment_list_latest }
                replayment_list_latest.stacked_coded1_rsRow_list = this.replayment_list_latest.stacked_coded1_rsRow_list.filter(item => moment(item.date).isAfter(lastDay))
                replayment_list_latest.stacked_coded2_rsRow_list = this.replayment_list_latest.stacked_coded2_rsRow_list.filter(item => moment(item.date).isAfter(lastDay))
                return replayment_list_latest
            }
            return this.replayment_list_latest
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
            axios.get('/admin/erp/get_stack_replayment_schedule_data?' + string)
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
            10: 正常放款
            11: 正常案債權受讓
            12: 發生逾期
            13: 提前清償
            14: 正常案債權出讓
            20: 逾期債權之產生
            21: 逾期案債權受讓
            23: 逾期還款
            24: 逾期案債權出讓
            */
            switch (Number(code)) {
                case 10:
                    return '正常放款'
                case 11:
                    return '正常案債權受讓'
                case 12:
                    return '發生逾期'
                case 13:
                    return '提前清償'
                case 14:
                    return '正常案債權出讓'
                case 20:
                    return '逾期債權之產生'
                case 21:
                    return '逾期案債權受讓'
                case 23:
                    return '逾期還款'
                case 24:
                    return '逾期案債權出讓'
                default:
                    return '未知'
            }
        },
        downloadExcel() {
            $("#fileDownloadIframe").remove();
            let url = '/admin/erp/get_replayment_spreadsheet?'
            // build params form searchform
            const { start_date, end_date, user_id_int, investment_id_int_list_str } = this.searchform
            url += Object.entries({ start_date, end_date, user_id_int, investment_id_int_list_str })
                .filter(([key, value]) => value !== '')
                .map(([key, value]) => `${key}=${value}`)
                .join('&')
            this.is_waiting_response = true
            axios({
                url,
                methods: 'GET',
                responseType: 'blob'
            }).then(res => {
                const url = window.URL.createObjectURL(new Blob([res.data]));
                const link = document.createElement('a');
                link.href = url;
                const filename = res.headers["content-disposition"].split("filename=")[1]
                link.download = filename.slice(1, filename.length - 1)
                document.body.appendChild(link);
                link.click();
            }).finally(() => {
                this.is_waiting_response = false
            })
        },
        downloadExcelLatest() {
            $("#fileDownloadIframe").remove();
            let url = '/admin/erp/get_stack_replayment_schedule_spreadsheet?'
            // build params form searchform
            const { start_date, end_date, user_id_int, investment_id_int_list_str } = this.searchform
            url += Object.entries({ start_date, end_date, user_id_int, investment_id_int_list_str })
                .filter(([key, value]) => value !== '')
                .map(([key, value]) => `${key}=${value}`)
                .join('&')
            this.is_waiting_response = true
            axios({
                url,
                methods: 'GET',
                responseType: 'blob'
            }).then(res => {
                const url = window.URL.createObjectURL(new Blob([res.data]));
                const link = document.createElement('a');
                link.href = url;
                const filename = res.headers["content-disposition"].split("filename=")[1]
                link.download = filename.slice(1, filename.length - 1)
                document.body.appendChild(link);
                link.click();
            }).finally(() => {
                this.is_waiting_response = false
            })
        }
    }
})

var app = new Vue({
    el: '#page-wrapper',
    data: {
        searchform: {
            start_date: '',
            end_date: moment().format('YYYY-MM-DD'),
            investor_id_int: '',
        },
        is_waiting_response: false,
        column: [],
        tableData: []
    },
    mounted: function () {
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
            const { start_date, end_date, investor_id_int } = this.searchform
            // show confirm
            if (investor_id_int == '') {
                if (!confirm('即將輸出全平台資料，等候時間較長，請勿關閉頁面，確認是否執行？')) {
                    return
                }
            }
            const string = Object.entries({ start_date, end_date, investor_id_int })
                .filter(([key, value]) => value !== '')
                .map(([key, value]) => `${key}=${value}`)
                .join('&')
            // axios get replayment_schedule
            this.is_waiting_response = true
            axios.get('/admin/erp/get_etpr_data?' + string).then(({ data }) => {
                this.tableData = data.table_str_mat
                this.column = data.column_name_list
            }).catch((error) => {
                alert('子系統錯誤或無回應: ' + error)
            }).finally(() => {
                this.is_waiting_response = false
            })
        },
        downloadExcel() {
            $("#fileDownloadIframe").remove();
            let url = '/admin/erp/etpr_spreadsheet?'
            // build params form searchform
            const { start_date, end_date, investor_id_int } = this.searchform
            // show confirm
            if (investor_id_int == '') {
                if (!confirm('即將輸出全平台資料，等候時間較長，請勿關閉頁面，確認是否執行？')) {
                    return
                }
            }
            url += Object.entries({ start_date, end_date, investor_id_int })
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
                link.download = res.headers["content-disposition"].split("filename=")[1]
                document.body.appendChild(link);
                link.click();
            }).finally(() => {
                this.is_waiting_response = false
            })
        },
        format(value) {
            if (value.toString().includes('-')) {
                return value
            }
            return value.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ',')
        }
    }
})

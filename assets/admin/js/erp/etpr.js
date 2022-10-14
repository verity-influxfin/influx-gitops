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
            this.is_waiting_response = true
            const { start_date, end_date, investor_id_int } = this.searchform
            const string = Object.entries({ start_date, end_date, investor_id_int })
                .filter(([key, value]) => value !== '')
                .map(([key, value]) => `${key}=${value}`)
                .join('&')
            // axios get replayment_schedule
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
            url += Object.entries({ start_date, end_date, investor_id_int })
                .filter(([key, value]) => value !== '')
                .map(([key, value]) => `${key}=${value}`)
                .join('&')
            $("body").append(
                `<iframe id="fileDownloadIframe" src="${url}" style="display: none"></iframe>`
            );
        },
        format(value) {
            if (value.toString().includes('-')) {
                return value
            }
            return value.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ',')
        }
    }
})

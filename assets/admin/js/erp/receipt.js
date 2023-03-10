var app = new Vue({
    el: '#page-wrapper',
    data: {
        searchform: {
            start_date: moment().subtract('1', 'days').format('YYYY-MM-DD'),
            end_date: moment().subtract('1', 'days').format('YYYY-MM-DD'),
            form_intEnum: 0,
        },
        is_waiting_response: false,
        tab: 'tab1',
        a_sheet: {
            column: [],
            tableData: []
        },
        b_sheet: {
            column: [],
            tableData: []
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
            const { start_date, end_date, form_intEnum } = this.searchform
            const string = Object.entries({ start_date, end_date, form_intEnum })
                .filter(([key, value]) => value !== '')
                .map(([key, value]) => `${key}=${value}`)
                .join('&')
            // axios get_receipt
            this.is_waiting_response = true
            axios.get('/admin/erp/get_receipt?' + string).then(({ data }) => {
                this.a_sheet.column = data.a_sheet.column_name_list
                this.a_sheet.tableData = data.a_sheet.table_str_mat
                this.b_sheet.column = data.b_sheet.column_name_list
                this.b_sheet.tableData = data.b_sheet.table_str_mat
            }).catch((error) => {
                alert('子系統錯誤或無回應: ' + error)
            }).finally(() => {
                this.is_waiting_response = false
            })
        },
        downloadExcel() {
            $("#fileDownloadIframe").remove();
            let url = '/admin/erp/receipt_spreadsheet?'
            // build params form searchform
            const { start_date, end_date, form_intEnum } = this.searchform
            url += Object.entries({ start_date, end_date, form_intEnum })
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
        },
    }
})

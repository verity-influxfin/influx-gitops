var app = new Vue({
    el: '#page-wrapper',
    data: {
        searchform: {
            start_date: moment().format('YYYY-MM-DD'),
            end_date: moment().format('YYYY-MM-DD'),
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
        setInterval(() => {
            if (document.cookie.includes('fileDownload=true')) {
                self.is_waiting_response = false
                document.cookie = 'fileDownload=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
            }
        }, 1000);
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
            $("body").append(
                `<iframe id="fileDownloadIframe" src="${url}" style="display: none"></iframe>`
            );
        },
        format(value) {
            if (value.toString().includes('-')) {
                return value
            }
            return value.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ',')
        },
    }
})

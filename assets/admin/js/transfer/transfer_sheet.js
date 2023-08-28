var app = new Vue({
    el: '#page-wrapper',
    data: {
        searchform: {
            sdate: '',
            edate: moment().format('YYYY-MM-DD'),
        },
        is_waiting_response: false,
        trasfer_sheet: [],
        conbine_sheet: [],
        combinations_detail: []
    },
    computed: {
    },
    mounted() {
        var self = this;
        $('#sdate').datepicker({
            'format': 'yyyy-mm-dd',
        }).on('change', function () {
            self.searchform.sdate = this.value
        });

        $('#edate').datepicker({
            'format': 'yyyy-mm-dd',
        }).on('change', function () {
            self.searchform.edate = this.value
        });
    },
    methods: {
        doSearch() {
            const {
                sdate,
                edate
            } = this.searchform
            // show confirm
            if (sdate == '' || edate == '') {
                alert('請記得帶要查詢的日期區間')
                return
            }

            const string = Object.entries({
                sdate,
                edate
            }).filter(([key, value]) => value !== '')
                .map(([key, value]) => `${key}=${value}`)
                .join('&')
            this.is_waiting_response = true

            axios.get('/admin/transfer/get_transfer_success?' + string).then(({
                data
            }) => {
                this.trasfer_sheet = data.response.transfers
                this.conbine_sheet = data.response.combinations
                this.combinations_detail = data.response.combinations_detail
            }).catch((error) => {
                alert('子系統錯誤或無回應:' + error)
            }).finally(() => {
                this.is_waiting_response = false
            })
        },
        downloadExcel() {
            $("#fileDownloadIframe").remove();
            let url = '/admin/transfer/transfer_sheet_spreadsheet?'
            // build params form searchform
            const {
                sdate,
                edate,
            } = this.searchform

            if (sdate == '' || edate == '') {
                alert('請記得帶要查詢的日期區間')
                return
            }
            url += Object.entries({
                sdate,
                edate
            })
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
    },
})
var app = new Vue({
    el: '#page-wrapper',
    data: {
        searchform: {
            start_date: '',
            end_date: moment().format('YYYY-MM-DD'),
            user_id_int: '',
        },
        is_waiting_response: false,
        assets_sheet: []
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
        setInterval(() => {
            if (document.cookie.includes('fileDownload=true')) {
                self.is_waiting_response = false
                document.cookie = 'fileDownload=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
            }
        }, 1000);
    },
    methods: {
        doSearch() {
            const { start_date, end_date, user_id_int } = this.searchform
            // show confirm
            if (user_id_int == '') {
                if (!confirm('即將輸出全平台資料，等候時間較長，請勿關閉頁面，確認是否執行？')) {
                    return
                }
            }
            const string = Object.entries({ start_date, end_date, user_id_int })
                .filter(([key, value]) => value !== '')
                .map(([key, value]) => `${key}=${value}`)
                .join('&')
            // axios get get_assets_sheet_data
            this.is_waiting_response = true
            axios.get('/admin/erp/get_assets_sheet_data?' + string)
            .then(({ data }) => {
                this.assets_sheet = data
            }).catch((error) => {
                alert('子系統錯誤或無回應:' + error)
            }).finally(() => {
                this.is_waiting_response = false
            })
        },
        downloadExcel() {
            $("#fileDownloadIframe").remove();
            let url = '/admin/erp/assets_sheet_spreadsheet?'
            // build params form searchform
            const { start_date, end_date, user_id_int } = this.searchform
            // show confirm
            if (user_id_int == '') {
                if (!confirm('即將輸出全平台資料，等候時間較長，請勿關閉頁面，確認是否執行？')) {
                    return
                }
            }
            url += Object.entries({ start_date, end_date, user_id_int })
                .filter(([key, value]) => value !== '')
                .map(([key, value]) => `${key}=${value}`)
                .join('&')
            this.is_waiting_response = true
            $("body").append(
                `<iframe id="fileDownloadIframe" src="${url}" style="display: none"></iframe>`
            );
        },
        amount: function (value) {
            return value.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ',')
        },
        formatTime(value) {
            return moment(value).format('YYYY-MM-DD HH:mm:ss')
        }
    }
})

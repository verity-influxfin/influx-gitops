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
    },
    methods: {
        doSearch() {
            this.is_waiting_response = true
            const { start_date, end_date, user_id_int } = this.searchform
            const string = Object.entries({ start_date, end_date, user_id_int })
                .filter(([key, value]) => value !== '')
                .map(([key, value]) => `${key}=${value}`)
                .join('&')
            // axios get get_assets_sheet_data
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
            url += Object.entries({ start_date, end_date, user_id_int })
                .filter(([key, value]) => value !== '')
                .map(([key, value]) => `${key}=${value}`)
                .join('&')
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

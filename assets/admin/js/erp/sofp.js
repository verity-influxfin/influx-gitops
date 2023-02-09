var app = new Vue({
    el: '#page-wrapper',
    data: {
        searchform: {
            start_date: '',
            end_date: moment().format('YYYY-MM-DD'),
            user_id_int: '',
        },
        table_data: {
            start_date: '',
            end_date: '',
            assets: {
                subtotal: 0,
                subjectGroup_list: [],
            },
            equity: {
                subtotal: 0,
                subjectGroup_list: [],
            },
            liabilities: {
                subtotal: 0,
                subjectGroup_list: [],
            },
            user_id_int: 0,
        },
        is_waiting_response: false,
    },
    mounted() {
        var self = this;
        $('#start_date').datepicker({
            'format': 'yyyy-mm-dd',
        }).on('change', function () { self.searchform.date = this.value });
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
    computed: {
        table_has_data: function () {
            return this.has_assets || this.has_equity || this.has_liabilities
        },
        has_assets: function () {
            return this.table_data.assets.subjectGroup_list.length > 0
        },
        has_equity: function () {
            return this.table_data.equity.subjectGroup_list.length > 0
        },
        has_liabilities: function () {
            return this.table_data.liabilities.subjectGroup_list.length > 0
        }
    },
    methods: {
        getListTitle(obj) {
            return obj.subject_list[0].name.split(' - ')[0]
        },
        amount: function (value) {
            return value.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ',')
        },
        doSearch() {
            this.is_waiting_response = true
            const { start_date, end_date, user_id_int } = this.searchform
            const string = Object.entries({ start_date, end_date, user_id_int })
                .filter(([key, value]) => value !== '')
                .map(([key, value]) => `${key}=${value}`)
                .join('&')
            // axios get get_assets_sofp_data
            axios.get('/admin/erp/get_sofp_data?' + string).then(({ data }) => {
                this.table_data = data
            }).catch((error) => {
                alert('子系統錯誤或無回應: ' + error)
            }).finally(() => {
                this.is_waiting_response = false
            })
        },
        downloadExcel() {
            $("#fileDownloadIframe").remove();
            let url = '/admin/erp/sofp_spreadsheet?'
            // build params form searchform
            const { start_date, end_date, user_id_int } = this.searchform
            url += Object.entries({ start_date, end_date, user_id_int })
                .filter(([key, value]) => value !== '')
                .map(([key, value]) => `${key}=${value}`)
                .join('&')
            this.is_waiting_response = true
            $("body").append(
                `<iframe id="fileDownloadIframe" src="${url}" style="display: none"></iframe>`
            );
        },
    }
})

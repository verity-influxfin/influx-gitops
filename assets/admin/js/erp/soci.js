var app = new Vue({
    el: '#page-wrapper',
    data: {
        searchform: {
            start_date: moment().format('YYYY-01-01'),
            end_date: moment().format('YYYY-MM-DD'),
            user_id_int: '',
        },
        table_data: {
            start_date: '',
            end_date: '',
            total: 0,
            revenues: {
                subtotal: 0,
                subjectGroup_list: [],
            },
            expenses: {
                subtotal: 0,
                subjectGroup_list: [],
            },
            user_id_int: 0,
        },
        is_waiting_response: false,
    },
    computed: {
        table_has_data: function () {
            return this.has_revenues || this.has_expense;
        },
        has_revenues: function () {
            return this.table_data.revenues.subjectGroup_list.length > 0
        },
        has_expense: function () {
            return this.table_data.expenses.subjectGroup_list.length > 0
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
        getListTitle(obj) {
          return obj.subject_list[0].name.split(' - ')[0]
        },
        doSearch() {
            this.is_waiting_response = true
            // axios get get_assets_sheet_data
            axios.get('/admin/erp/get_soci_data', {
                params: this.searchform
            }).then(({ data }) => { 
                this.table_data = data
                this.is_waiting_response = false
            })
        },
        downloadExcel() { 
            $("#fileDownloadIframe").remove();
            let url = '/admin/erp/soci_spreadsheet?'
            // build params form searchform
            let params = []
            for (var key in this.searchform) {
                params.push(key + '=' + this.searchform[key])
            }
            url += params.join('&')
            $("body").append(
              `<iframe id="fileDownloadIframe" src="${url}" style="display: none"></iframe>`
            );
        },
        amount: function (value) {
            return value.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ',')
        }
    }
})

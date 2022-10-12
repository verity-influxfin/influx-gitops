var app = new Vue({
    el: '#page-wrapper',
    data: {
        searchform: {
            start_date: moment().subtract(2, 'months').format('YYYY-MM-10'),
            end_date: moment().format('YYYY-MM-10'),
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
            // axios get replayment_schedule
            axios.get('/admin/erp/get_etpr_data', {
                params: this.searchform
            }).then(({ data }) => {
                this.tableData = data.table_str_mat
                this.column = data.column_name_list
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

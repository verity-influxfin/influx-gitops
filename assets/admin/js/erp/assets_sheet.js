var app = new Vue({
    el: '#page-wrapper',
    data: {
        searchform: {
            start_date: moment().subtract(2, 'months').format('YYYY-MM-10'),
            end_date: moment().format('YYYY-MM-10'),
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
            // //do api
            // this.assets_sheet = [
            //     {
            //         "product_name": "學生貸",
            //         "target_no": "STN2022090592436",
            //         "target_user_id": 53400,
            //         "target_loan_amount": 23000,
            //         "approved_credits_level": 7,
            //         "latest_credits_level": 7,
            //         "school_or_company": "元智大學",
            //         "major": "工程及工程業學門",
            //         "instalment": 18,
            //         "interest_rate": 11,
            //         "interest_method": "本息均攤",
            //         "loan_date": "2022-09-08",
            //         "case_status": "正常還款中",
            //         "target_id": 70651,
            //         "investment_user_id": 73628,
            //         "investment_loan_amount": 16000,
            //         "investment_status": "持有中",
            //         "delay_days": 2,
            //         "principal_balance": 16000,
            //         "contract_created_at": "2022-09-19T12:20:03+00:00"
            //     },
            //     {
            //         "product_name": "學生貸",
            //         "target_no": "STN2022090638594",
            //         "target_user_id": 67377,
            //         "target_loan_amount": 12000,
            //         "approved_credits_level": 8,
            //         "latest_credits_level": 8,
            //         "school_or_company": "長榮大學",
            //         "major": "商業及管理學門",
            //         "instalment": 3,
            //         "interest_rate": 16,
            //         "interest_method": "本息均攤",
            //         "loan_date": "2022-09-08",
            //         "case_status": "正常還款中",
            //         "target_id": 70644,
            //         "investment_user_id": 73628,
            //         "investment_loan_amount": 4000,
            //         "investment_status": "持有中",
            //         "delay_days": 0,
            //         "principal_balance": 4000,
            //         "contract_created_at": "2022-09-19T12:20:03+00:00"
            //     }
            // ]
            // axios get get_assets_sheet_data
            axios.get('/admin/erp/get_assets_sheet_data', {
                params: this.searchform
            }).then(({ data }) => { 
                this.assets_sheet = data
                this.is_waiting_response = false
            })
        },
        downloadExcel() { 
            $("#fileDownloadIframe").remove();
            let url = '/admin/erp/assets_sheet_spreadsheet?'
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
        },
        formatTime(value) {
            return moment(value).format('YYYY-MM-DD HH:mm:ss') 
        }
    }
})

$(document).ready(function () {
    const detailTable = $('#history-table').DataTable({
        'ordering': false,
        'language': {
            'processing': '處理中...',
            'lengthMenu': '顯示 _MENU_ 項結果',
            'zeroRecords': '目前無資料',
            'info': '顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項',
            'infoEmpty': '顯示第 0 至 0 項結果，共 0 項',
            'infoFiltered': '(從 _MAX_ 項結果過濾)',
            'search': '使用本次搜尋結果快速搜尋',
            'paginate': {
                'first': '首頁',
                'previous': '上頁',
                'next': '下頁',
                'last': '尾頁'
            }
        },
        "info": false
    })
});

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
        setDetailTableRow(combination_no) {
            $('#historyModal').modal('toggle')
            $('#history-table').DataTable().clear()
            this.combinations_detail.forEach(list => {
                if (list.combination_no === combination_no) {
                    $('#history-table').DataTable().row.add([
                        list.transfer_date,
                        list.user_id,
                        list.new_user_id,
                        list.target_no,
                        list.loan_amount,
                        list.interest_rate,
                        list.principal,
                        list.interest,
                        list.delay_interest,
                        list.instalment
                    ])
                }
            });
            $('#history-table').DataTable().draw()

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
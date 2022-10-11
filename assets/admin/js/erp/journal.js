var app = new Vue({
  el: '#page-wrapper',
  data: {
    set_duration: false,
    searchform: {
        year: moment().format('yyyy'),
        month: moment().format('MM'),
        user_id: '',
        role: 'investor',
        end_year: moment().format('yyyy'),
        end_month: moment().format('MM'),
    },
    table_data: [],
    is_waiting_response: false,
    filter_grade: ''
  },
  filters: {
      amount: function (value) {
        return value.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ',')
      }
  },
  computed: {
    debit_total: function () {
        var retval = 0;
        this.entry_filter.forEach(e => {
            retval += e.is_debit ? e.amount : 0;
        });
        return retval;
    },
    credit_total: function () {
        var retval = 0;
        this.entry_filter.forEach(e => {
            retval += !e.is_debit ? e.amount : 0;
        });
        return retval;
    },
    entry_filter: function () {
        if (this.filter_grade == '') {
            return this.table_data;
        }
        return this.table_data.filter( entry => entry.grade.toString().includes(this.filter_grade))
    }
  },
  methods: {
    spreadsheet_export: function () {
        if (this.table_data.length > 0) {
            var formdata = new FormData();
            formdata.append('data', btoa(encodeURIComponent(JSON.stringify(this.table_data))));
            this.is_waiting_response = true
            axios({
                method: 'post',
                url: '/admin/Erp/journal_spreadsheet',
                data: formdata,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                responseType: 'blob'
            }).then((response) => {
                let blob = new Blob([response.data], { type: 'application/vnd.ms-excel' })
                let link = document.createElement('a')
                link.href = window.URL.createObjectURL(blob)
                link.download = '日記簿_' + this.searchform.year + this.searchform.month + '.xlsx'
                link.download = `${this.searchform.year - 1911}${this.searchform.month}日記簿_${moment().valueOf()}.xlsx`
                link.click()
                this.is_waiting_response = false
            })
        }
    },
    search: function () {
        var self = this;
        this.table_data = [];

        if (this.set_duration) {
            let start = moment(`${this.searchform.year}-${this.searchform.month}-01`);
            let end = moment(`${this.searchform.end_year}-${this.searchform.end_month}-01`);

            let promises = [];

            for (var d = moment(start); d.diff(end, 'months') <= 0; d.add(1, 'months')) {
                let data = encodeURIComponent(btoa(encodeURIComponent(JSON.stringify({
                    year   : d.format('YYYY'),
                    month  : d.format('MM'),
                    user_id: this.searchform.user_id.trim(),
                    role   : this.searchform.role.trim(),
                }))));

                promises.push(axios({
                    method: 'get',
                    url: '/admin/Erp/get_journal_data?data=' + data,
                    headers: { 'Accept': 'application/json' }
                }));
            }

            if (promises.length > 0) {
                axios.all(promises).then(axios.spread((...datas) => {
                    datas.forEach(resp => {
                        self.table_data = self.table_data.concat(resp.data.data.entries);
                    });
                }));
            }

        } else {
            let data = encodeURIComponent(btoa(encodeURIComponent(JSON.stringify({
                year   : this.searchform.year.trim(),
                month  : this.searchform.month.trim(),
                user_id: this.searchform.user_id.trim(),
                role   : this.searchform.role.trim(),
            }))));

            axios({
                method: 'get',
                url: '/admin/Erp/get_journal_data?data=' + data,
                headers: { 'Accept': 'application/json' }
            }).then(resp => {
                this.table_data = resp.data.data.entries;
            })
        }

    }
  }
})
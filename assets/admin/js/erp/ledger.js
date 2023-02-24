var app = new Vue({
  el: '#page-wrapper',
  data: {
    searchform: {
        // year: moment().format('yyyy'),
        // month: moment().format('MM'),
        year: '2020',
        month: '12',
        user_id: '22936',
        role: 'investor',
    },
    table_data: [],
    is_waiting_response: false,
  },
  filters: {
      amount: function (value) {
        return value.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ',')
      }
  },
  computed: {
    debit_total: function () {
        var retval = 0;
        this.table_data.forEach(e => {
            retval += e.is_debit ? e.amount : 0;
        });
        return retval;
    },
    credit_total: function () {
        var retval = 0;
        this.table_data.forEach(e => {
            retval += !e.is_debit ? e.amount : 0;
        });
        return retval;
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
                link.click()
                this.is_waiting_response = false
            })
        }
    },
    search: function () {
        var self = this;

        this.searchform.year    = this.searchform.year.trim();
        this.searchform.month   = this.searchform.month.trim();
        this.searchform.user_id = this.searchform.user_id.trim();
        this.searchform.role    = this.searchform.role.trim();

        var data = encodeURIComponent(btoa(encodeURIComponent(JSON.stringify(this.searchform))));

        axios({
            method: 'get',
            url: '/admin/Erp/get_ledger_data?data=' + data,
            headers: { 'Accept': 'application/json' }
        }).then(resp => {
            // console.log(resp.data)
            this.table_data = resp.data.data.items;
        })
    },
    getsubtotal: function(preliminary_balance, items) {
        subtotal = preliminary_balance;
        return items.map(function(item){
            subtotal = subtotal + item.debit_amount - item.credit_amount;
            return subtotal;
        });
    },
    getdebittotal: function(items) {
        retval = 0;
        items.forEach(function (item) {
            retval += item.debit_amount;
        });
        return retval;
    },
    getcredittotal: function(items) {
        retval = 0;
        items.forEach(function (item) {
            retval += item.credit_amount;
        });
        return retval;
    },
  }
})
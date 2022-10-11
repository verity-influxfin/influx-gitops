var app = new Vue({
  el: '#page-wrapper',
  data: {
    searchform: {
        start_date: moment().format('YYYY-01-01'),
        end_date: moment().format('YYYY-MM-DD'),
        user_id: '',
        role: 'investor',
    },
    table_data: {
        'expense': {
            'amount': 0,
            'items': []
        },
        'revenues': {
            'amount': 0,
            'items': []
        },
        'total_amount': 0
    },
    is_waiting_response: false,
  },
  computed: {
    table_has_data: function () {
        return this.has_revenues || this.has_expense;
    },
    has_revenues: function () {
        return this.table_data.revenues.items.length > 0;
    },
    has_expense: function () {
        return this.table_data.expense.items.length > 0;
    }
  },
  filters: {
      amount: function (value) {
        return value.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ',');
      }
  },
  mounted() {
    var self = this;
    $('#start_date').datepicker({
        'format': 'yyyy-mm-dd',
    }).on('change', function() { self.searchform.start_date = this.value});
    $('#end_date').datepicker({
        'format': 'yyyy-mm-dd',
    }).on('change', function() { self.searchform.end_date = this.value});
    // this.search()
  },
  methods: {
    spreadsheet_export: function () {
        if (this.table_data.length > 0) {
            var formdata = new FormData();
            formdata.append('data', btoa(encodeURIComponent(JSON.stringify(this.table_data))));
            this.is_waiting_response = true
            axios({
                method: 'post',
                url: '/admin/Erp/soci_spreadsheet',
                data: formdata,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                responseType: 'blob'
            }).then((response) => {
                let blob = new Blob([response.data], { type: 'application/vnd.ms-excel' })
                let link = document.createElement('a')
                link.href = window.URL.createObjectURL(blob)
                link.download = `損益表.xlsx`
                link.click()
                this.is_waiting_response = false
            })
        }
    },
    search: function () {
        axios.get(
            '/admin/Erp/get_soci_data?data=' + encodeURIComponent(btoa(encodeURIComponent(JSON.stringify(this.searchform))))
        ).then((resp) => {
            this.table_data = resp.data.data.items
        })
    }
  }
})
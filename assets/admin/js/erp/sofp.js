var app = new Vue({
  el: '#page-wrapper',
  data: {
    searchform: {
        date: moment().format('YYYY-MM-DD'),
        user_id: '',
        role: 'investor',
    },
    table_data: [],
    is_waiting_response: false,
    form_error: {
        search: {
            date    : false,
            user_id : false,
            role    : false,
        }
    }
  },
  watch:{
    'searchform.date': function(value) {
        this.form_error.search.date = value == '';
    },
  },
  filters: {
      sofp_amount: function (value) {
        if (value < 0)
        {
            value = Math.abs(value);
            return '(' + value.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ',') + ')';
        }
        return value.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ',');
      }
  },
  mounted() {
    var self = this;
    $('#date').datepicker({
        'format': 'yyyy-mm-dd',
    }).on('change', function() { self.searchform.date = this.value});
  },
  methods: {
    spreadsheet_export: function () {
        if (this.table_data.length > 0) {
            var formdata = new FormData();
            formdata.append('data', btoa(encodeURIComponent(JSON.stringify(this.table_data))));
            this.is_waiting_response = true
            axios({
                method: 'post',
                url: '/admin/Erp/sofp_spreadsheet',
                data: formdata,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                responseType: 'blob'
            }).then((response) => {
                let blob = new Blob([response.data], { type: 'application/vnd.ms-excel' })
                let link = document.createElement('a')
                link.href = window.URL.createObjectURL(blob)
                link.download = `資產負債表.xlsx`
                link.click()
                this.is_waiting_response = false
            })
        }
    },
    search: function () {
        axios.get(
            '/admin/Erp/get_sofp_data?data=' + encodeURIComponent(btoa(encodeURIComponent(JSON.stringify(this.searchform))))
        ).then((resp) => {
            this.table_data = resp.data.data.pages
        })
    },
    count_blank: function(content, side) {
        asset_count = content.assets.items.length > 0 ? content.assets.items.length + 1 : 0;
        liabilities_count = content.liabilities.items.length > 0 ? content.liabilities.items.length + 1 : 0;
        equity_count = content.equity.items.length > 0 ? content.equity.items.length + 1 : 0;

        max_count = liabilities_count + equity_count;

        if (max_count < asset_count) {
            max_count = asset_count;
        }

        if (max_count > 0)
        {
            max_count += 1;
        }

        if (side == 'left') {
            return max_count - asset_count;
        } else {
            return max_count - (liabilities_count + equity_count);
        }
    }
  }
})
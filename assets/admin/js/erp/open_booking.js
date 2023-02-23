var app = new Vue({
  el: '#page-wrapper',
  data: {
    searchform: {
        investor_id: '',
    },
    data: [],
    is_waiting_response: false,
    form_error: {
        search: {
            investor_id : false,
        }
    },
  },
  watch: {
    'searchform.investor_id': function(value) {
        this.form_error.search.investor_id = value == '';
    },
  },
  filters: {
      asset_amount: function (value) {
        if (value == null) {
            return '-';
        }
        return value.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ',')
      },
      status_decode: function (value) {
        switch (value) {
            case 0: return '待清償';
            case 1: return '已清償';
            case 2: return '提前清償';
            case 3: return '部分清償';
            case 4: return '逾期';
            case 5: return '法催';
        }
        return value;
      }
  },
  mounted: function() {
    let self = this;
  },
  computed: {
    is_search_valid: function () {
        for (let key in this.form_error.search) {
            if (this.form_error.search[key] !== false) {
                return false;
            }
        }
        return true;
    }
  },
  methods: {
    search: function () {

        var self = this;

        this.data = [];

        data = {
            investor_id : this.searchform.investor_id.trim(),
        };
        data = encodeURIComponent(btoa(encodeURIComponent(JSON.stringify(data))));

        axios({
            method: 'get',
            url: '/admin/Erp/get_open_booking_data?data=' + data,
            headers: { 'Accept': 'application/json' }
        }).then(resp => {
            data = resp.data.data;
            self.data = data.targets;
        })
    }
  }
})
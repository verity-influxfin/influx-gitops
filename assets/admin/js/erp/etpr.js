var app = new Vue({
  el: '#page-wrapper',
  data: {
    searchform: {
        start_date: '2019-08-10',
        end_date: moment().add(24, 'months').format('YYYY-MM-10'),
        investor_id: '',
        contracts: [],
    },
    data: [],
    contract_list:[],
    is_waiting_response: false,
    form_error: {
        search: {
            start_date  : false,
            end_date    : false,
            investor_id : false,
        }
    },
    current_tab: 'normal'
  },
  watch: {
    'searchform.start_date': function(value) {
        this.form_error.search.start_date = value == '';
    },
    'searchform.end_date': function(value) {
        this.form_error.search.end_date = value == '';
    },
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
    var self = this;

    $('#start_date').datepicker({
        'format': 'yyyy-mm-dd',
    }).on('change', function() { self.searchform.start_date = this.value});

    $('#end_date').datepicker({
        'format': 'yyyy-mm-dd',
    }).on('change', function() { self.searchform.end_date = this.value});
  },
  computed: {
    is_search_valid: function () {
        for (key in this.form_error.search) {
            if (this.form_error.search[key] !== false) {
                return false;
            }
        }
        return true;
    }
  },
  methods: {
    settab: function(tabname) {
        if (! this.is_waiting_response) {
            this.current_tab = tabname;
            this.data = [];
            this.search();
        }
    },
    tabactive: function(checktab) {
        return {'active': this.istab(checktab) };
    },
    istab: function(checktab) {
        return this.current_tab == checktab;
    },
    spreadsheet_export: function () {
        if (this.data.length > 0) {
            var formdata = new FormData();
            formdata.append('data', btoa(encodeURIComponent(JSON.stringify(this.data))));
            this.is_waiting_response = true
            axios({
                method: 'post',
                url: '/admin/Erp/etpr_spreadsheet',
                data: formdata,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                responseType: 'blob'
            }).then((response) => {
                let blob = new Blob([response.data], { type: 'application/vnd.ms-excel' })
                let link = document.createElement('a')
                link.href = window.URL.createObjectURL(blob)
                link.download = 'excel.xlsx'
                link.click()
                this.is_waiting_response = false
            })
        }
    },
    select_all: function(checkall){
        if (checkall) {
            this.searchform.contracts = this.contract_list;
        }else {
            this.searchform.contracts = [];
        }
    },
    search: function () {

        if (! this.is_search_valid) {
            return;
        }

        var self = this;

        this.data = [];

        data = {
            start_date  : this.searchform.start_date.trim(),
            end_date    : this.searchform.end_date.trim(),
            investor_id : this.searchform.investor_id.trim(),
            contracts   : this.searchform.contracts,
            type        : this.current_tab,
        };
        data = encodeURIComponent(btoa(encodeURIComponent(JSON.stringify(data))));

        self.is_waiting_response = true;
        axios({
            method: 'get',
            url: '/admin/Erp/get_etpr_data?data=' + data,
            headers: { 'Accept': 'application/json' }
        }).then(resp => {
            data = resp.data.data;
            self.contract_list = [];
            if (data.contracts != null) {
                self.contract_list = data.contracts.sort();
            }
            if (self.searchform.contracts != null && self.searchform.contracts.length < 1) {
                self.searchform.contracts = data.contracts;
            }
            self.data = data.etpr;
            self.is_waiting_response = false;
        })
    }
  }
})
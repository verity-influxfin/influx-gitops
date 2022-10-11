function encode_payload (data) {
    return encodeURIComponent(btoa(encodeURIComponent(JSON.stringify(data))));
}

function currencyformatter(amount) {
    return amount.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ',');
}

var app = new Vue({
  el: '#page-wrapper',
  data: {
    target_filter: '',
    target_list: null,
    investment_list: [],
    active_target: null,
    is_waiting_response: false,
    target_type: 'normal',
  },
  filters: {
      amount: function (value) {
        return currencyformatter(value);
      },
      investment_title: function (item) {
        return [
            `投資人: ${item.investor_id}`,
            `金額: ${currencyformatter(item.amount)}`,
            item.transfer_status == 0 ? null : '(出讓)'
        ].filter(x => x).join(' | ');
      }
  },
  computed: {
    total_investments: function() {
        return this.investment_list.length;
    },
    total_amount: function() {
        return this.investment_list.map(x => parseFloat(x.amount)).reduce((a, b) => a + b, 0);
    },
    filtered_target_list: function() {
        if (this.target_filter !== '') {
            let target = this.target_filter.toUpperCase();
            return this.target_list.filter(x => x.target_no.search(new RegExp(target,'g')) >= 0);
        }
        return this.target_list;
    }
  },
  mounted: function() {
    var self = this;

    $('.datepicker').datepicker({
        'format': 'yyyy-mm-dd',
    });
  },
  methods: {
    copy_target_no: function(e, target_no) {

        navigator.clipboard.writeText(target_no)
        .then(() => {
            $(e.target).text('copied');

            setTimeout(function (){
                $(e.target).text('copy');
            },1000);
        })
        .catch(err => {
            console.log('Something went wrong', err);
        })
    },
    check_target: function (target_no) {
        if (this.is_waiting_response === false) {
            this.active_target = target_no;
            this.investment_list = [];

            console.log({
                'target_no': target_no,
                'target_type': this.target_type,
            });

            let data = encode_payload({
                'target_no': target_no,
                'target_type': this.target_type,
            });
            this.is_waiting_response = true;

            axios({
                method: 'get',
                url: '/admin/Erp/get_investments?data=' + data,
                headers: { 'Accept': 'application/json' }
            }).then(resp => {
                this.investment_list = resp.data.data;
                this.is_waiting_response = false;
            })
        }
    },
    call_target_testfy: function () {

        let data = encode_payload({
            'target_no': this.active_target
        });
        this.is_waiting_response = true;

        axios({
            method: 'get',
            url: '/admin/Erp/target_testfy?data=' + data,
            headers: { 'Accept': 'application/json' }
        }).then(resp => {
            this.is_waiting_response = false;
            window.alert(resp.data.success ? '執行成功' : '執行失敗');
        }, error => {
            this.is_waiting_response = false;
            window.alert('執行失敗');
        })
    },
    number_only: function(e) {
        e.target.value = e.target.value.replace(/\D/g, '');
    },
    update_target_list: function(e) {
        this.target_list = [];
        this.investment_list = [];
        this.target_filter = '';
        this.active_target = null;

        let data = Object.fromEntries(new FormData(e.target));
        this.target_type = data.target_type;
        data = encode_payload(data);
        this.is_waiting_response = true;

        axios({
            method: 'get',
            url: '/admin/Erp/get_targets?data=' + data,
            headers: { 'Accept': 'application/json' }
        }).then(resp => {
            this.target_list = resp.data.data;
            this.is_waiting_response = false;
        })
    },
  }
})
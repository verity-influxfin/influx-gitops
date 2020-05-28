<template>
  <div class="invest-detail-wrapper">
    <div class="assets-card">
      <div class="pieChart-container">
        <div class="pie-chart" ref="pie_chart"></div>
      </div>
      <div class="income-table" v-if="Object.keys(invsetmentData).length !==0">
        <div class="table-col">
          累計收益&ensp;
          <i class="far fa-question-circle" ref="income_tip" @click="showTip"></i>
          <span class="float-right">
            <span class="nmber">
              {{format(invsetmentData.income.interest
              +invsetmentData.income.delay_interest
              +invsetmentData.income.other)}}
            </span>$
          </span>
        </div>
        <hr />
        <div class="table-col">
          可用餘額
          <span class="float-right">
            <span class="nmber" style="color:#1E88E5">{{format(tweenedBalance)}}</span>$
          </span>
        </div>
        <div class="table-col">
          待交易賬戶餘額
          <span class="float-right">
            <span class="nmber" style="color:#616161">{{format(tweendeTotalFrozen)}}</span>$
          </span>
        </div>
        <div class="table-col">
          待回收本金
          <span class="float-right">
            <span class="nmber" style="color:#FFC107">{{format(tweenedPrincipal)}}</span>$
          </span>
        </div>
        <div class="table-col">
          待回收利息
          <span class="float-right">
            <span class="nmber" style="color:#F57C00">{{format(tweenedInterest)}}</span>$
          </span>
        </div>
        <div class="table-col">
          待回收延滯息
          <span class="float-right">
            <span class="nmber" style="color:#F44336">{{format(tweenedDelayInterest)}}</span>$
          </span>
        </div>
        <div class="table-col">
          資產總額
          <span class="float-right">
            <span class="nmber" style="color:#558B2F">{{format(tweenedTotal)}}</span>$
          </span>
        </div>
      </div>

      <div class="fade show income_tooltip" ref="income_content">
        <p>累計收益明細：</p>
        <div>
          利息：
          <span class="float-right">{{format(invsetmentData.income.interest)}}元</span>
        </div>
        <div>
          延滯利息：
          <span class="float-right">{{format(invsetmentData.income.delay_interest)}}元</span>
        </div>
        <div>
          其他：
          <span class="float-right">{{format(invsetmentData.income.other)}}元</span>
        </div>
      </div>
    </div>
    <statement :list="list" @searchDteail="search" />
  </div>
</template>

<script>
import statement from "./component/statementComponent";

export default {
  components: {
    statement
  },
  data: () => ({
    totalFrozen: 0,
    tweendeTotalFrozen: 0,
    list: [],
    invsetmentData: {},
    tweenedBalance: 0,
    tweenedPrincipal: 0,
    tweenedInterest: 0,
    tweenedDelayInterest: 0,
    tweenedTotal: 0
  }),
  computed: {
    balance() {
      return Object.keys(this.invsetmentData).length !== 0
        ? this.invsetmentData.funds.total - this.totalFrozen
        : 0;
    },
    principal() {
      return Object.keys(this.invsetmentData).length !== 0
        ? this.invsetmentData.accounts_receivable.principal
        : 0;
    },
    interest() {
      return Object.keys(this.invsetmentData).length !== 0
        ? this.invsetmentData.accounts_receivable.interest
        : 0;
    },
    delayInterest() {
      return Object.keys(this.invsetmentData).length !== 0
        ? this.invsetmentData.accounts_receivable.delay_interest
        : 0;
    },
    total() {
      return Object.keys(this.invsetmentData).length !== 0
        ? this.invsetmentData.funds.total +
            this.invsetmentData.accounts_receivable.principal +
            this.invsetmentData.accounts_receivable.interest +
            this.invsetmentData.accounts_receivable.delay_interest
        : 0;
    }
  },
  created() {
    this.invsetmentData = this.$store.getters.InvestAccountData;
  },
  watch: {
    invsetmentData() {
      this.totalFrozen = 0;
      this.createPieChart();
    },
    balance(newValue) {
      gsap.to(this.$data, { duration: 1, tweenedBalance: newValue });
    },
    totalFrozen(newValue) {
      gsap.to(this.$data, { duration: 1, tweendeTotalFrozen: newValue });
    },
    principal(newValue) {
      gsap.to(this.$data, { duration: 1, tweenedPrincipal: newValue });
    },
    interest(newValue) {
      gsap.to(this.$data, { duration: 1, tweenedInterest: newValue });
    },
    delayInterest(newValue) {
      gsap.to(this.$data, { duration: 1, tweenedDelayInterest: newValue });
    },
    total(newValue) {
      gsap.to(this.$data, { duration: 1, tweenedTotal: newValue });
    }
  },
  methods: {
    format(data) {
      let l10nEN = new Intl.NumberFormat("en-US");
      return l10nEN.format(data.toFixed(0));
    },
    createPieChart() {
      let $this = this;
      let pieData = [];

      $.each($this.invsetmentData.funds.frozenes, (index, value) => {
        $this.totalFrozen += value;
      });

      pieData.push({
        value: $this.invsetmentData.funds.total - $this.totalFrozen,
        name: "可用餘額",
        itemStyle: { color: "#1E88E5" }
      });
      pieData.push({
        value: $this.totalFrozen,
        name: "待交易賬戶餘額",
        itemStyle: { color: "#616161" }
      });
      pieData.push({
        value: $this.invsetmentData.accounts_receivable.principal,
        name: "待回收本金",
        itemStyle: { color: "#FFC107" }
      });
      pieData.push({
        value: $this.invsetmentData.accounts_receivable.interest,
        name: "待回收利息",
        itemStyle: { color: "#F57C00" }
      });
      pieData.push({
        value: $this.invsetmentData.accounts_receivable.delay_interest,
        name: "待回收延滯息",
        itemStyle: { color: "#F44336" }
      });

      let pie_chart = echarts.init($this.$refs.pie_chart);

      let option = {
        title: {
          subtext: `結算至${$this.invsetmentData.funds.last_recharge_date}`,
          left: "center"
        },
        tooltip: {
          trigger: "item",
          confine: true,
          formatter(params) {
            return `<span>${params.name}：$<span style="font-size:30px;color:${
              params.color
            }">${$this.format(params.value)}</span> (${params.percent}%)`;
          }
        },
        series: [
          {
            type: "pie",
            radius: "80%",
            label: {
              show: false
            },
            emphasis: {
              label: {
                show: false
              }
            },
            animationType: "scale",
            animationEasing: "elasticOut",
            animationDelay: function(idx) {
              return 500;
            },
            data: pieData.sort(function(a, b) {
              return a.value > b.value;
            })
          }
        ]
      };

      pie_chart.setOption(option);
    },
    showTip() {
      $(this.$refs.income_content)
        .css("top", $(this.$refs.income_tip).offset().top + 20)
        .css("left", $(this.$refs.income_tip).offset().left - 10)
        .toggle();
    },
    search() {
      let $this = this;
      axios
        .post("getTansactionDetails", { isInvest: true })
        .then(res => {
          $this.list = res.data.data.list;
        })
        .catch(error => {
          console.log("getTansactionDetails 發生錯誤，請稍後在試");
        });
    }
  }
};
</script>

<style lang="scss">
.invest-detail-wrapper {
  display: flex;

  .assets-card {
    padding: 10px;
    box-shadow: 0 0 5px #848484;
    border-radius: 10px;
    background: #efefef;
    margin-right: 20px;
    display: flex;

    %bg {
      box-shadow: 0 0 8px black;
      padding: 10px;
      border-radius: 11px;
      margin: 10px;
      background: #545454;
    }

    .pieChart-container {
      @extend %bg;

      .pie-chart {
        width: 500px;
        height: 500px;
      }
    }

    .income-table {
      width: fit-content;
      height: fit-content;
      @extend %bg;

      .table-col {
        width: 400px;
        margin: 5px;
        color: #ffffff;
        font-weight: 600;
        padding: 5px;
        overflow: auto;
        line-height: 28px;

        .nmber {
          font-size: 30px;
        }
      }
    }
  }

  .income_tooltip {
    width: 180px;
    background: #ffffff;
    padding: 10px;
    border-radius: 9px;
    box-shadow: 2px 2px 0 0px #110057;
    position: absolute;
    display: none;
    top: 0;
    left: 0;
  }

  @media screen and(max-width:767px) {
    .assets-card {
      .pieChart-container {
        .pie-chart {
          width: 310px !important;
          height: 310px;
        }
      }

      .income-table {
        width: 93%;
      }
    }
  }

  @media screen and (max-width: 1023px) {
    display: block;

    .assets-card {
      width: 95%;
      display: block;
      margin: 0px 10px 10px 10px;

      .pieChart-container {
        .pie-chart {
          width: 690px;
        }
      }

      .income-table {
        width: 97%;

        .table-col {
          width: 100%;
        }
      }
    }

    .income-detail-card {
      margin: 0px 10px 10px 10px;
      width: 96%;
    }
  }
}
</style>
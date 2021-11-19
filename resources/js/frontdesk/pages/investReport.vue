<template>
  <div class="main">
    <div class="report-main">
      <div class="row no-gutters report-intro mx-auto">
        <p>親愛的會員您好：</p>
        <p>
          感謝您長期以來對普匯的信賴與支持，在此為您結算至{{ today }}
          投資績效報告，如有任何問題，
          歡迎聯繫普匯客服Line@inFluxFin，我們將竭誠為您提供貼心的服務，
          再次感謝您的愛護與支持！
        </p>
      </div>
      <div class="info-details" v-if="investReport.basicInfo">
        <div class="item">
          <div class="item-title">投資人</div>
          <div class="item-value">{{ investReport.basicInfo.id }}</div>
        </div>
        <div class="item">
          <div class="item-title">首筆投資</div>
          <div class="item-value">
            {{ investReport.basicInfo.firstInvestDate }}
          </div>
        </div>
        <div class="item">
          <div class="item-title">投資金額</div>
          <div class="item-value">
            $
            {{ formate(investReport.basicInfo.investAmount) }}
          </div>
        </div>
      </div>
      <div class="row no-gutters justify-content-between mt-4">
        <div class="invest-overview">
          <div class="overview-title">
            <div class="text-center">(一)資產概況</div>
            <div class="underline"></div>
          </div>
          <div class="overview-table">
            <div class="table-header">
              <div class="item">投資產品</div>
              <div class="item">上班族貸</div>
              <div class="item">學生貸</div>
              <div class="item">本金餘額</div>
            </div>
            <div class="table-row">
              <div class="table-title item">正常還款中</div>
              <div
                class="item"
                v-for="x in localInvestDescription.amountNotDelay"
                :key="x"
              >
                {{ formate(x) }}
              </div>
            </div>
            <div class="table-row gray">
              <div class="table-title item">逾期中</div>
              <div
                class="item"
                v-for="x in localInvestDescription.amountDelay"
                :key="x"
              >
                {{ formate(x) }}
              </div>
            </div>
            <div class="table-row">
              <div class="table-title item">本金餘額</div>
              <div
                class="item"
                v-for="x in localInvestDescription.totalAmount"
                :key="x"
              >
                {{ formate(x) }}
              </div>
            </div>
          </div>
        </div>
        <div class="invest-performance">
          <div class="performance-title">
            <div class="text-center">(二)投資績效</div>
            <div class="underline"></div>
          </div>
          <div class="performance-table">
            <div class="table-header">
              <div class="item">科目</div>
              <div class="item">績效</div>
            </div>
            <div
              class="table-row"
              v-for="(x, i) in investReport.investPerformance"
              :key="x.name"
            >
              <div class="table-title item">{{ x.name }}</div>
              <div class="item">
                {{ formate(x.description) }}
                {{ i == 1 || i == 4 ? "%" : "" }}
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="realized-rate">
        <div class="realized-title">
          <div class="text-center">(三)已實現收益率</div>
          <div class="underline"></div>
        </div>
        <div class="realized-table">
          <div class="header-item dur">期間</div>
          <div class="header-item avg">本金均額(※3)</div>
          <div class="header-item full-income">總收益(※1)</div>
          <div class="header-item item-rate">報酬率(※2)</div>
          <div class="test1">
            <div class="test2 header-item">收入</div>
            <div class="test3 header-item">支出</div>
            <div class="header-sub-item">利息收入</div>
            <div class="header-sub-item">提還利息</div>
            <div class="header-sub-item">逾期償還利息</div>
            <div class="header-sub-item">延滯息</div>
            <div class="header-sub-item">補貼息</div>
            <div class="header-sub-item">回款手收</div>
          </div>
          <div class="rows">
            <div
              class="one-row"
              v-for="x in investReport.realizedRateOfReturn"
              :key="x.rangeOfYear"
            >
              <div class="table-title item">{{ x.rangeOfYear }}</div>
              <div class="item">{{ formate(x.principalBalance) }}</div>
              <div class="item">{{ formate(x.interest) }}</div>
              <div class="item">{{ formate(x.withdrawInterest) }}</div>
              <div class="item">{{ formate(x.repayDelayInterest) }}</div>
              <div class="item">{{ formate(x.delayInterest) }}</div>
              <div class="item">{{ formate(x.subsidyInterest) }}</div>
              <div class="item">{{ formate(x.handlingFee) }}</div>
              <div class="item">{{ formate(x.totalIncome) }}</div>
              <div class="item">{{ x.rateOfReturn }}%</div>
            </div>
            <!-- <div class="one-row">
              <div class="table-title item">累積收益率</div>
              <div class="item">123,456</div>
              <div class="item">123,456</div>
              <div class="item">1,239</div>
              <div class="item">123,456</div>
              <div class="item">123,456</div>
              <div class="item">123,456</div>
              <div class="item">123,456</div>
              <div class="item">123,456</div>
              <div class="item">12%</div>
            </div> -->
          </div>
        </div>
      </div>
      <div class="row flex-column no-gutters hint mt-2">
        <div>
          ※1.總收益=(利息收入+提還利息+逾期償還利息+延滯息+補貼息)-回款手收
        </div>
        <div class="">※2.報酬率=當期(總收益/本金均額)</div>
        <div class="">※3.本金均額=年度每月底本金餘額加總/期數</div>
      </div>
      <div class="row no-gutters justify-content-between mt-2">
        <div class="wait-for-realized">
          <div class="wait-title">
            <div class="text-center">(四)待實現應收利息</div>
            <div class="underline"></div>
          </div>
          <div class="table-header">
            <div class="item">期間</div>
            <div class="item">金額</div>
            <div class="item">折現</div>
          </div>
          <div
            v-if="
              investReport.waitedRateOfReturn &&
              investReport.waitedRateOfReturn.statisticsData
            "
          >
            <div
              class="table-row"
              v-for="x in investReport.waitedRateOfReturn.statisticsData"
              :key="x.rangeOfMonth"
            >
              <div class="table-title item">{{ x.rangeOfMonth }}</div>
              <div class="item">{{ formate(x.amount) }}</div>
              <div class="item">{{ formate(x.discount) }}</div>
            </div>
            <div class="table-row">
              <div class="table-title item">內部報酬率預估</div>
              <div class="item col">
                {{ investReport.waitedRateOfReturn.predictRateOfReturn }}%
              </div>
            </div>
          </div>
        </div>
        <div class="overdue">
          <div class="overdue-title">
            <div class="text-center">(五)逾期未收</div>
            <div class="underline"></div>
          </div>
          <div class="table-header">
            <div class="item">科目</div>
            <div class="item">金額</div>
          </div>
          <div
            class="table-row"
            v-for="x in investReport.delayNotReturn"
            :key="x.name"
          >
            <div class="table-title item">{{ x.name }}</div>
            <div class="item">{{ formate(x.amount) }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      investReport: {
        "basicInfo": {
          "id": "82",
          "firstInvestDate": "2018/11/09",
          "investAmount": "270000"
        },
        "assetsDescription": [
          {
            "name": "上班族貸",
            "amountNotDelay": "126436",
            "amountDelay": "943",
            "totalAmount": "137379"
          },
          {
            "name": "學生貸",
            "amountNotDelay": "126436",
            "amountDelay": "943",
            "totalAmount": "137379"
          },
          {
            "name": "本金餘額",
            "amountNotDelay": "126436",
            "amountDelay": "943",
            "totalAmount": "137379"
          }
        ],
        "investPerformance": [
          {
            "name": "投資年資",
            "description": "2.9",
          },
          {
            "name": "2021上半年",
            "description": "7.1",
          },
          {
            "name": "平均本金餘額",
            "description": "167893934",
          },
          {
            "name": "扣除逾期之折現收益",
            "description": "5694463",
          },
          {
            "name": "折現年化報酬率",
            "description": "12.00",
          },
        ],
        "realizedRateOfReturn": [
          {
            "rangeOfYear": "2018 01-12",
            "principalBalance": "266734",
            "interest": "3271",
            "withdrawInterest": "15",
            "repayDelayInterest": "546",
            "delayInterest": "13424",
            "subsidyInterest": "90",
            "handlingFee": "141241",
            "totalIncome": "99573552",
            "rateOfReturn": "1"
          },
          {
            "rangeOfYear": "2019 01-12",
            "principalBalance": "266734",
            "interest": "3271",
            "withdrawInterest": "15",
            "repayDelayInterest": "546",
            "delayInterest": "13424",
            "subsidyInterest": "90",
            "handlingFee": "141241",
            "totalIncome": "99573552",
            "rateOfReturn": "1"
          },
          {
            "rangeOfYear": "累績收益率",
            "principalBalance": "266734",
            "interest": "3271",
            "withdrawInterest": "15",
            "repayDelayInterest": "546",
            "delayInterest": "13424",
            "subsidyInterest": "90",
            "handlingFee": "141241",
            "totalIncome": "99577552",
            "rateOfReturn": "1",
          }
        ],
        "waitedRateOfReturn": {
          "statisticsData": [
            {
              "rangeOfMonth": "2021 06-12",
              "amount": "62041",
              "discount": "41243"
            },
            {
              "rangeOfMonth": "2021 06-12",
              "amount": "62041",
              "discount": "41243"
            },
            {
              "rangeOfMonth": "合計",
              "amount": "62041",
              "discount": "41243"
            }
          ],
          "predictRateOfReturn": "16.14"
        },
        "delayNotReturn": [
          {
            "name": "逾期-尚欠本息",
            "amount": "58296"
          },
          {
            "name": "逾期-尚欠延滯息",
            "amount": "58296"
          },
          {
            "name": "合計",
            "amount": "58296"
          }
        ]
      },
      test: ''
    }
  },
  computed: {
    today() {
      // 2020/01/01
      const today = new Date()
      return `${today.getFullYear()}/${(today.getMonth() + 1).toString().padStart(2, '0')}/${today.getDate().toString().padStart(2, '0')}`
    },
    localInvestDescription() {
      const { assetsDescription } = this.investReport
      if (assetsDescription && assetsDescription.length > 0) {
        return {
          amountNotDelay: assetsDescription.map(x => x.amountNotDelay),
          amountDelay: assetsDescription.map(x => x.amountDelay),
          totalAmount: assetsDescription.map(x => x.totalAmount)
        }
      }
      return {
        amountNotDelay: [],
        amountDelay: [],
        totalAmount: []
      }
    },
  },
  methods: {
    formate(x) {
      if (isNaN(x)) {
        return x
      }
      return parseInt(x, 10).toLocaleString()
    }
  },
}
</script>

<style lang="scss" scoped>
.report-main {
  width: 940px;
  height: 1200px;
  margin: 30px auto;
  padding: 0 20px;
  background-size: cover;
  background-image: url("../asset/images/invest-report-cover.jpg");
  line-height: 1.5;
  font-size: 14px;
  font-weight: bold;
  .report-intro {
    padding-top: 140px;
    width: 570px;
    line-height: 1.5;
    font-size: 18px;
    font-weight: bold;
  }
  .info-details {
    margin: auto;
    display: flex;
    justify-content: space-between;
    width: 535px;
    padding: 7px 40px;
    .item {
      display: flex;
      flex-direction: column;
      align-items: center;
      .item-value {
        color: #0b5ab2;
      }
    }
  }
  .invest-overview {
    .overview-title {
      margin-bottom: 15px;
    }
  }
  .invest-performance {
    .table-row:nth-child(odd) {
      background: #dbdcdc;
    }
    .performance-title {
      margin-bottom: 15px;
    }
    .table-header .item {
      width: 150px;
    }
    .table-row .item {
      width: 150px;
    }
  }
  .realized-rate {
    .realized-title {
      margin-bottom: 15px;
    }
    .realized-table {
      display: grid;
      grid-template-columns: 100px 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
      grid-template-rows: 42px auto;
      gap: 0px 0px;
      grid-auto-flow: row;
      grid-template-areas:
        "dur avg test1 test1 test1 test1 test1 test1 full-income item-rate"
        "rows rows rows rows rows rows rows rows rows rows"
        "rows2 rows2 rows2 rows2 rows2 rows2 rows2 rows2 rows2 rows2";
      .header-item {
        background-color: #3b6188;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
      }
      .header-sub-item {
        background-color: #fff;
        color: #3b6188;
        display: flex;
        align-items: center;
        justify-content: center;
      }
    }

    .dur {
      grid-area: dur;
    }

    .avg {
      grid-area: avg;
    }

    .full-income {
      grid-area: full-income;
    }

    .item-rate {
      grid-area: item-rate;
    }

    .test1 {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
      grid-template-rows: 1fr 1fr;
      gap: 0px 0px;
      grid-auto-flow: row;
      grid-template-areas:
        "test2 test2 test2 test2 test2 test3"
        ". . . . . .";
      grid-area: test1;
    }

    .test2 {
      grid-area: test2;
    }

    .test3 {
      grid-area: test3;
    }

    .rows {
      grid-area: rows;
      .one-row {
        display: grid;
        grid-template-columns: 100px 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
        grid-template-areas: ". . . . . . . . . .";
        .item {
          padding: 5px 0;
          text-align: center;
        }
      }
    }
    .one-row:nth-child(even) {
      background: #dbdcdc;
    }

    .rows2 {
      grid-area: rows2;
    }

    .table-header .item {
      width: 80px;
      &.item-ful {
        line-height: 52px;
      }
      &.full-income {
        width: 97px;
      }
      &.dur {
        width: 100px;
      }
      &.avg {
        width: 105px;
      }
      &.overdue-rate {
        width: 105px;
      }
      &.item-rate {
        width: 93px;
      }
    }
    .table-row .item {
      width: 86px;
      &.full-income {
        width: 97px;
      }
      &.dur {
        width: 100px;
      }
      &.avg {
        width: 105px;
      }
      &.overdue-rate {
        width: 105px;
      }
    }
    .table-row:nth-child(even) {
      background: #dbdcdc;
    }
    .double-item {
      //   display: flex;
      .item {
        width: 80px;
      }
      .item-down {
        color: #0b5ab2;
        background-color: #fff;
      }
    }
  }
  .wait-for-realized {
    .wait-title {
      margin-bottom: 15px;
    }
    .table-row:nth-child(even) {
      background: #dbdcdc;
    }
    .table-header .item {
      width: 130px;
    }
    .table-row .item {
      width: 130px;
    }
  }
  .overdue {
    .overdue-title {
      margin-bottom: 15px;
    }
    .table-header .item {
      width: 140px;
    }
    .table-row .item {
      width: 140px;
    }
    .table-row:nth-child(even) {
      background: #dbdcdc;
    }
  }
  .underline {
    margin: auto;
    width: 75px;
    border-bottom: 5px solid #3b6188;
  }
  .table-header {
    background-color: #3b6188;
    color: #fff;
    display: flex;
    .item {
      width: 100px;
      padding: 5px 10px;
      text-align: center;
    }
  }
  .table-row {
    display: flex;
    &.gray {
      background-color: #dbdcdc;
    }
    .item {
      width: 100px;
      padding: 5px 10px;
      text-align: center;
    }
  }
  .table-title {
    color: #3b6188;
  }
  .hint {
    color: red;
    padding-left: 15px;
    font-size: 12px;
  }
}

@media screen and (max-width: 940px) {
  .report-main {
    margin: 15px 10px;
  }
}
</style>

<template>
  <div class="row no-gutters investment-table">
    <div class="investment-table-tabs col-md-auto col-12">
      <div
        class="investment-table-tab"
        @click="activeTab = 1"
        :class="{ active: activeTab === 1 }"
      >
        <img
          class="img-icon"
          src="@/asset/images/investment/investment-tab1.svg"
        />
      </div>
      <div
        class="investment-table-tab"
        @click="activeTab = 2"
        :class="{ active: activeTab === 2 }"
      >
        <img
          class="img-icon"
          src="@/asset/images/investment/investment-tab2.svg"
        />
      </div>
      <div
        class="investment-table-tab"
        @click="activeTab = 3"
        :class="{ active: activeTab === 3 }"
      >
        <img
          class="img-icon"
          src="@/asset/images/investment/investment-tab3.svg"
        />
      </div>
      <div
        class="investment-table-tab"
        @click="activeTab = 4"
        :class="{ active: activeTab === 4 }"
      >
        <img
          class="img-icon"
          src="@/asset/images/investment/investment-tab4.svg"
        />
      </div>
    </div>
    <div class="table-panel col-md col-12">
      <!-- table-1 -->
      <table class="table-element" v-show="activeTab === 1">
        <thead class="thead">
          <tr>
            <th class="th th-1">投資</th>
            <th class="th">開戶流程</th>
            <th class="th">可下單時間</th>
            <th class="th">投入資金所需</th>
            <th class="th">知識所需</th>
          </tr>
        </thead>
        <tbody>
          <tr class="tr tr-1">
            <td class="td-1 td">普匯債權投資</td>
            <td class="td">AI自動化審查</td>
            <td class="td">隨時</td>
            <td class="td">少，1000元即可投入</td>
            <td class="td">基礎</td>
          </tr>
          <tr class="tr">
            <td class="td td-1">基金</td>
            <td class="td">須填寫投資人適性評估</td>
            <td class="td">特定交易時間</td>
            <td class="td">少</td>
            <td class="td">高深</td>
          </tr>
          <tr class="tr">
            <td class="td td-1">股票（台股）</td>
            <td class="td">須先開立指定銀行的帳戶</td>
            <td class="td">特定交易時間</td>
            <td class="td">多</td>
            <td class="td">進階</td>
          </tr>
          <tr class="tr">
            <td class="td td-1">股票（美股）</td>
            <td class="td">
              <div>國內：需開立複委託</div>
              <div>海外：步驟繁瑣</div>
            </td>
            <td class="td">特定交易時間</td>
            <td class="td">多</td>
            <td class="td">進階</td>
          </tr>
          <tr class="tr">
            <td class="td td-1">虛擬貨幣</td>
            <td class="td">各交易所情況不一</td>
            <td class="td">隨時</td>
            <td class="td">多</td>
            <td class="td">高深</td>
          </tr>
        </tbody>
      </table>
      <!-- table-2 -->
      <div v-show="activeTab === 2">
        <div class="chart" ref="chart"></div>
      </div>
      <!-- table-3 -->
      <table class="table-element" v-show="activeTab === 3">
        <thead class="thead">
          <tr>
            <th class="th th-1">獲利</th>
            <th class="th">年化報酬率</th>
            <th class="th">配息方式</th>
            <th class="th">資金運用</th>
          </tr>
        </thead>
        <tbody>
          <tr class="tr tr-1">
            <td class="td-1 td">普匯債權投資</td>
            <td class="td">約5~16%</td>
            <td class="td">約定本息，每月回收</td>
            <td class="td">小額分散，自主配置</td>
          </tr>
          <tr class="tr">
            <td class="td td-1">基金</td>
            <td class="td">10年內約-3.37~23.89%</td>
            <td class="td">依照產品性質而定</td>
            <td class="td">依照經理人偏好</td>
          </tr>
          <tr class="tr">
            <td class="td td-1">股票（台股）</td>
            <td class="td">15年內平均約8.6%</td>
            <td class="td">每年股息皆不確定</td>
            <td class="td">受股市波動影響</td>
          </tr>
          <tr class="tr">
            <td class="td td-1">股票（美股）</td>
            <td class="td">25年內平均約7.7%</td>
            <td class="td">每年股息皆不確定</td>
            <td class="td">受股市波動影響</td>
          </tr>
          <tr class="tr">
            <td class="td td-1">虛擬貨幣</td>
            <td class="td">種類繁多難以估計</td>
            <td class="td">依照產品性質而定</td>
            <td class="td">受幣價波動影響</td>
          </tr>
        </tbody>
      </table>
      <!-- table-4 -->
      <table class="table-element" v-show="activeTab === 4">
        <thead class="thead">
          <tr>
            <th class="th th-1">費用</th>
            <th class="th">申購手續費</th>
            <th class="th">賣出手續費</th>
            <th class="th">其他費用</th>
          </tr>
        </thead>
        <tbody>
          <tr class="tr tr-1">
            <td class="td-1 td">普匯債權投資</td>
            <td class="td">
              <div>無</div>
              <div>還款到帳收取1%</div>
            </td>
            <td class="td">無</td>
            <td class="td">無</td>
          </tr>
          <tr class="tr">
            <td class="td td-1">基金</td>
            <td class="td">0%~3%</td>
            <td class="td">10年內約-3.37~23.89%</td>
            <td class="td">
              <div>帳號管理費：每年0.2%</div>
              <div>經理費：每年1%~2.5%</div>
              <div>保管費：每年0.2%</div>
            </td>
          </tr>
          <tr class="tr">
            <td class="td td-1">股票（台股）</td>
            <td class="td">0.1425%</td>
            <td class="td">0.1425%</td>
            <td class="td">無</td>
          </tr>
          <tr class="tr">
            <td class="td td-1">股票（美股）</td>
            <td class="td">
              <div>複委託:0.25%～1%</div>
              <div>海外券商:0%~0.1%</div>
            </td>
            <td class="td">
              <div>複委託:0.25%～1%</div>
              <div>海外券商:每筆10~35美元</div>
            </td>
            <td class="td">每年股息皆不確定</td>
          </tr>
          <tr class="tr">
            <td class="td td-1">虛擬貨幣</td>
            <td class="td">約0.05～0.2%</td>
            <td class="td">約0.05～0.2%</td>
            <td class="td">出金手續費：0~3%</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      activeTab: 1
    }
  },
  mounted() {
    this.createChart()
  },
  methods: {
    createChart() {
      let chart = echarts.init(this.$refs.chart)
      const labelOption = {}
      const option = {
        title: {
          text: '投資風險',
          left: 'center',
          textStyle: {
            fontSize: 32
          }
        },
        grid: {
          top: '15%',
        },
        legend: {
          top: '50px',
          data: ['市場風險', '利率風險', '匯率風險', '營運風險']
        },
        xAxis: [
          {
            axisTick: { show: false },
            splitArea: {
              show: true,
              areaStyle: { color: ['#DFE2F060', '#ffffff10', '#ffffff10', '#ffffff10', '#ffffff10'] }
            },
            axisLabel: {
              formatter: function (value) {
                if (value === 'Puhey') return '{' + value + '| }'
                return value
              },
              rich: {
                Puhey: {
                  height: window.innerWidth < 800 ? 30 : 45,
                  align: 'center',
                  backgroundColor: {
                    image: require('@/asset/images/logo_puhey.png')
                  }
                }
              }
            },
            data: ['Puhey', '基金', '股票（台股）', '股票（美股）', '虛擬貨幣']
          }
        ],
        yAxis: [
          {
            type: 'value',
            name: '風險等級',
            axisLabel: {
              formatter: function (value, index) {
                switch (index) {
                  case 0:
                    return ''
                  case 1:
                    return '低'
                  case 2:
                    return '中'
                  case 3:
                    return '高'
                  case 4:
                    return '極高'
                  default:
                    return value
                }
              }
            },
            axisLine: {
              show: true
            }
          }
        ],
        series: [
          {
            name: '市場風險',
            type: 'bar',
            color: '#5071c2',
            data: [100, 300, 300, 300, 400]
          },
          {
            name: '利率風險',
            type: 'bar',
            color: '#e5813a',
            data: [100, 300, 100, 100, 200]
          },
          {
            name: '匯率風險',
            type: 'bar',
            color: '#a5a5a5',
            data: [100, 300, 100, 300, 400]
          },
          {
            name: '營運風險',
            type: 'bar',
            color: '#f8c226',
            data: [100, 200, 200, 200, 400]
          }
        ]
      }
      chart.setOption(option)
    }
  },
}
</script>

<style lang="scss" scoped>
$color--primary: #036eb7;
$color__text--primary: #707070;
$color__background--primary: #f3f9fc;
.investment-table {
  &-tabs {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    .investment-table-tab {
      &.active {
        background: #fff;
        padding: 22px 37px 22px 70px;
      }
      cursor: pointer;
      padding: 22px 37px;
      background: linear-gradient(
        90deg,
        #f2f3f6 0%,
        rgba(223, 226, 240, 0.8) 100%
      );
      box-shadow: 4px 4px 12px rgba(0, 0, 0, 0.1);
      border-radius: 24px 0px 0px 24px;
      width: max-content;
    }
  }
  .chart {
    margin: 0 auto;
    height: 600px;
    max-width: 100%;
    width: 900px;
  }
  .table-panel {
    background: #ffffff;
    padding: 11px 11px 90px;
    border-radius: 0 24px 24px 24px;
  }
  .table-element {
    width: 100%;
    .thead {
      font-style: normal;
      font-weight: 500;
      font-size: 20px;
      line-height: 1.4;
      color: $color--primary;
      .th {
        text-align: center;
        padding: 15px 0;
      }
      .th-1 {
        font-style: normal;
        font-weight: 700;
        font-size: 56px;
        line-height: 1.4;
        color: #023d64;
      }
    }
    .tr {
      &:nth-child(odd) {
        background: #fafbfe;
      }
      .td {
        padding: 24px 8px;
        text-align: center;
        color: #707070;
      }
      .td-1 {
        color: $color--primary;
      }
    }
    .tr.tr-1 {
      background: $color--primary;
    }
    .tr-1 {
      font-style: normal;
      font-weight: 400;
      font-size: 20px;
      line-height: 1.4;
      .td {
        color: #ffffff;
      }
      .td-1 {
        color: #fff000;
      }
    }
  }
}
@media screen and (max-width: 767px) {
  .investment-table {
    &-tabs {
      flex-direction: row;
      align-items: flex-end;
      .investment-table-tab {
        &.active {
          background: #fff;
          padding: 20px 8px 8px;
        }
        padding: 8px;
        background: linear-gradient(
          90deg,
          #f2f3f6 0%,
          rgba(223, 226, 240, 0.8) 100%
        );
        border-radius: 12px 12px 0px 0px;
      }
      .img-icon {
        width: 28px;
      }
    }
    .chart {
      margin: 0 auto;
      height: 500px;
      max-width: initial;
      width: 600px;
    }
    .table-panel {
      background: #ffffff;
      padding: 11px 11px 90px;
      border-radius: 0 24px 24px 24px;
      overflow: auto;
    }
    .table-element {
      width: 100%;
      .thead {
        font-size: 14px;
        .th {
          padding: 9px 0;
          min-width: 120px;
        }
        .th-1 {
          font-size: 20px;
        }
      }
      .tr {
        font-size: 14px;
        &:nth-child(odd) {
          background: #fafbfe;
        }
        .td {
          padding: 12px 4px;
          width: max-content;
        }
        .td-1 {
          color: $color--primary;
        }
      }
      .tr.tr-1 {
        background: $color--primary;
      }
      .tr-1 {
        font-size: 14px;
        line-height: 1.4;
        .td {
          color: #ffffff;
        }
        .td-1 {
          color: #fff000;
        }
      }
    }
  }
}
</style>

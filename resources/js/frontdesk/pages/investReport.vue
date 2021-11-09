<template>
  <div class="main">
    <div class="report-main">
      <div class="row no-gutters report-intro mx-auto">
        <p>親愛的會員您好：</p>
        <p>
          感謝您長期以來對普匯的信賴與支持，在此為您結算至2021/05/31的投資績效報告，
          如有任何問題，歡迎聯繫普匯客服Line@inFluxFin，我們將竭誠為您提供貼心的服務，
          再次感謝您的愛護與支持！
        </p>
      </div>
      <div class="info-details">
        <div class="item">
          <div class="item-title">投資人</div>
          <div class="item-value">1234</div>
        </div>
        <div class="item">
          <div class="item-title">首筆投資</div>
          <div class="item-value">2018/09/19</div>
        </div>
        <div class="item">
          <div class="item-title">投資金額</div>
          <div class="item-value">$20,000,000</div>
        </div>
      </div>
      <div class="row no-gutters justify-content-between">
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
              <div class="item">123,456</div>
              <div class="item">123,456</div>
              <div class="item">1,123,456</div>
            </div>
            <div class="table-row gray">
              <div class="table-title item">逾期中</div>
              <div class="item">123,456</div>
              <div class="item">123,456</div>
              <div class="item">1,123,456</div>
            </div>
            <div class="table-row">
              <div class="table-title item">本金餘額</div>
              <div class="item">123,456</div>
              <div class="item">123,456</div>
              <div class="item">1,123,456</div>
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
            <div class="table-row">
              <div class="table-title item">投資年資</div>
              <div class="item">1.5</div>
            </div>
            <div class="table-row gray">
              <div class="table-title item">上半年</div>
              <div class="item">22.22%</div>
            </div>
            <div class="table-row">
              <div class="table-title item">平均本金餘額</div>
              <div class="item">1,234,567</div>
            </div>
            <div class="table-row gray">
              <div class="table-title item">扣除逾期之折現收益</div>
              <div class="item">1,234,567</div>
            </div>
            <div class="table-row">
              <div class="table-title item">折現年化報酬率</div>
              <div class="item">12.34%</div>
            </div>
          </div>
        </div>
      </div>

      <!-- <div class="realized-rate">
        <div class="realized-title">
          <div class="text-center">(三)已實現收益率</div>
          <div class="underline"></div>
        </div>
        <div class="realized-table">
          <div class="table-header">
            <div class="item dur item-ful">期間</div>
            <div class="item item-ful avg">本金均額(※3)</div>
            <div class="double-item d-flex flex-column justify-content-end">
              <div class="row no-gutters">
                <div class="item-up item col">收入</div>
                <div class="item-up item">支出</div>
              </div>
              <div class="row no-gutters">
                <div class="item-down item">利息收入</div>
                <div class="item-down item">提還利息</div>
                <div class="item-down item overdue-rate">逾期償還利息</div>
                <div class="item-down item">延滯息</div>
                <div class="item-down item">補貼息</div>
                <div class="item-down item">回款手收</div>
              </div>
            </div>
            <div class="item item-ful full-income">總收益(※1)</div>
            <div class="double-item">
              <div class="row no-gutters">
                <div class="item-up item item-rate">收益率</div>
              </div>
              <div class="row no-gutters">
                <div class="item-down item item-rate">報酬率(※2)</div>
              </div>
            </div>
          </div>
          <div class="table-row" v-for="i in 4" :key="i">
            <div class="table-title item dur">2021 07~12</div>
            <div class="item avg">123,456</div>
            <div class="item">123,456</div>
            <div class="item">1,239</div>
            <div class="item overdue-rate">123,456</div>
            <div class="item">123,456</div>
            <div class="item">123,456</div>
            <div class="item">123,456</div>
            <div class="item full-income">123,456</div>
            <div class="item">12%</div>
          </div>
          <div class="table-row">
            <div class="table-title item dur">累積收益率</div>
            <div class="item avg">123,456</div>
            <div class="item">123,456</div>
            <div class="item">1,239</div>
            <div class="item overdue-rate">123,456</div>
            <div class="item">123,456</div>
            <div class="item">123,456</div>
            <div class="item">123,456</div>
            <div class="item full-income">123,456</div>
            <div class="item">12%</div>
          </div>
        </div>
      </div> -->
      <div class="row no-gutters justify-content-between">
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
          <div class="table-row">
            <div class="table-title item">2021 07~12</div>
            <div class="item">123,456</div>
            <div class="item">123,456</div>
          </div>
          <div class="table-row">
            <div class="table-title item">2021 01~06</div>
            <div class="item">123,456</div>
            <div class="item">123,456</div>
          </div>
          <div class="table-row">
            <div class="table-title item">2022 07~12</div>
            <div class="item">123,456</div>
            <div class="item">123,456</div>
          </div>
          <div class="table-row">
            <div class="table-title item">合計</div>
            <div class="item">123,456</div>
            <div class="item">123,456</div>
          </div>
          <div class="table-row">
            <div class="table-title item">內部報酬率評估</div>
            <div class="item col">12.34%</div>
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
          <div class="table-row">
            <div class="table-title item">逾期-尚欠本息</div>
            <div class="item">123,456</div>
          </div>
          <div class="table-row gray">
            <div class="table-title item">逾期-尚欠延滯息</div>
            <div class="item">123,456</div>
          </div>
          <div class="table-row">
            <div class="table-title item">合計</div>
            <div class="item">1,123,456</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {

}
</script>

<style lang="scss" scoped>
.report-main {
  width: 940px;
  height: 1200px;
  margin: auto;
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
    margin-top: 30px;
    .overview-title {
      margin-bottom: 15px;
    }
  }
  .invest-performance {
    margin-top: 30px;
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
    margin-top: 30px;
    .realized-title {
      margin-bottom: 15px;
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
    margin-top: 30px;
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
    margin-top: 30px;
    .overdue-title {
      margin-bottom: 15px;
    }
    .table-header .item {
      width: 140px;
    }
    .table-row .item {
      width: 140px;
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
}
</style>
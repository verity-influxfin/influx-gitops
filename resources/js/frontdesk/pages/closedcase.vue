<template>
  <div class="closedcase-wrapper">
    <div class="no-data" v-if="groupList.length === 0">
      <img src="../asset/images/empty.svg" class="img-fluid" />
      <a target="_blank" href="https://event.influxfin.com/r/iurl?p=webinvest"
        >目前沒有投資標的，點我立即前往 >></a
      >
    </div>
    <div id="accordion" role="tablist" v-else>
      <div class="c-title">
        <div class="p-type">案件類型</div>
        <div class="p-count">案件件數</div>
        <div class="p-total">已回收本金</div>
        <div class="p-total">已回收利息</div>
      </div>
      <div class="card" v-for="(product, key) in groupList" :key="key">
        <div
          class="header collapsed"
          data-parent="#accordion"
          data-toggle="collapse"
          :data-target="`#collapse${key}`"
          aria-expanded="false"
        >
          <div class="h-t">
            <div class="p-type">{{ productList[product] }}</div>
            <div class="p-count">{{ finishedData[product].length }}件</div>
            <div class="p-total">
              <span>${{ getTotal(finishedData[product]) }}</span>
            </div>
            <div class="p-total">
              <span>${{ getInterest(finishedData[product]) }}</span>
            </div>
          </div>
          <span class="accicon">
            <i class="fas fa-angle-down rotate-icon"></i>
          </span>
        </div>
        <div :id="`collapse${key}`" class="collapse" data-parent="#accordion">
          <template v-for="(item, age) in groupByAge(finishedData[product])">
            <div class="mc-body" v-if="item.length !== 0" :key="age">
              <div
                class="m-header collapsed"
                :data-parent="`#collapse${key}`"
                data-toggle="collapse"
                :data-target="`#collapse${age}`"
                aria-expanded="false"
              >
                <div class="h-t">
                  <div class="p-type">{{ ageTextList[age] }}</div>
                  <div class="p-count">{{ item.length }}件</div>
                  <div class="p-total">
                    <span>${{ getTotal(item) }}</span>
                  </div>
                  <div class="p-total">
                    <span>${{ getInterest(item) }}</span>
                  </div>
                </div>
                <span class="accicon">
                  <i class="fas fa-angle-down rotate-icon"></i>
                </span>
              </div>
              <div
                :id="`collapse${age}`"
                class="collapse"
                :data-parent="`collapse${key}`"
              >
                <div class="c-body">
                  <template v-for="(row, type) in groupByType(item)">
                    <div
                      class="case-row"
                      v-if="item.length !== 0"
                      @click="showCases(row, typeTextList[type])"
                      :key="type"
                    >
                      <div class="d-bg">
                        <div class="p-type">{{ typeTextList[type] }}</div>
                        <div class="p-count">{{ row.length }}件</div>
                        <div class="p-total">
                          <span class="total">${{ getTotal(row) }}</span>
                        </div>
                        <div class="p-total">
                          <span class="total">${{ getInterest(row) }}</span>
                        </div>
                      </div>
                    </div>
                  </template>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// groupby
Array.prototype.groupBy = function (key1, key2 = "") {
  return this.reduce(function (groups, item) {
    const val = key2 ? item[key1][key2] : item[key1];
    groups[val] = groups[val] || [];
    groups[val].push(item);
    return groups;
  }, {});
};

import investDeatil from "../component/investDetailComponent";

export default {
  components: {
    investDeatil,
  },
  data: () => ({
    category: "",
    groupList: [],
    detailData: {},
    finishedData: {},
    productList: ["", "學生貸", "上班族貸", "工程師貸"],
    ageTextList: {
      normal: "正常案",
      observed: "觀察案",
      attention: "關注案",
      secondary: "次級案",
      bad: "不良案",
    },
    typeTextList: {
      normal: "正常",
      advance: "提前清償",
      delay: "逾期清償",
      transfer: "產品轉換",
      sell: "債權出讓",
    },
  }),
  created() {
    this.$parent.pageIcon = "/images/icon_closed_b.svg";
    this.$parent.pageTitle = "結案總覽";
    this.$parent.pagedesc = "您已結案的債權";
    this.getFinishedData();
  },
  methods: {
    format(data) {
      let l10nEN = new Intl.NumberFormat("en-US");
      return l10nEN.format(data.toFixed(0));
    },
    getFinishedData() {
      axios
        .post(`${location.origin}/getRecoveriesFinished`)
        .then((res) => {
          this.finishedData = res.data.data.list.groupBy(
            "target",
            "product_id"
          );

          this.groupList = Object.keys(this.finishedData);
        })
        .catch((error) => {
          console.log("getRecoveriesFinished 發生錯誤，請稍後再試");
        });
    },
    getTotal(data) {
      let total = 0;

      data.forEach((row, index) => {
        total += row.income.other + row.income.principal + row.income.transfer;
      });

      return this.format(total);
    },
    getInterest(data) {
      let total = 0;

      data.forEach((row, index) => {
        total += row.income.delay_interest + row.income.interest;
      });

      return this.format(total);
    },
    groupByAge(data) {
      let normal = [],
        observed = [],
        attention = [],
        secondary = [],
        bad = [];

      data.forEach((row, index) => {
        if (row.target.delay_days <= 0) {
          normal.push(row);
        } else if (0 < row.target.delay_days && row.target.delay_days <= 7) {
          observed.push(row);
        } else if (7 < row.target.delay_days && row.target.delay_days <= 29) {
          attention.push(row);
        } else if (29 < row.target.delay_days && row.target.delay_days <= 59) {
          secondary.push(row);
        } else {
          bad.push(row);
        }
      });

      return { normal, observed, attention, secondary, bad };
    },
    groupByType(data) {
      let normal = [],
        advance = [],
        transfer = [],
        delay = [],
        sell = [];

      data.forEach((row, index) => {
        let _status = row.status;
        let _subStatus = row.target.status;
        let _subTargetSubStatus = row.target.sub_status;
        let _transferStatus = row.transfer_status;
        let _redemptionType = 0;
        let _isDelayTarget = row.income.delay_interest > 0;

        if (_status === 9) {
          //流標
        } else if (_status === 10) {
          if (_transferStatus === 2) {
            sell.push(row);
          } else {
            if (_subStatus === 10) {
              if (_subTargetSubStatus === 2) {
                transfer.push(row);
              } else if (_subTargetSubStatus === 4) {
                advance.push(row);
              } else {
                if (_isDelayTarget) {
                  delay.push(row);
                } else {
                  normal.push(row);
                }
              }
            }
          }
        } else {
          normal.push(row);
        }
      });

      return { normal, advance, transfer, sell, delay };
    },
    showCases(data, category) {
      this.detailData = data;
      this.category = category;
    },
  },
};
</script>

<style lang="scss">
.closedcase-wrapper {
  width: 73%;
  margin: 0px auto;
  padding: 25px 35px;

  #accordion {
    .p-type {
      width: 200px;
    }
    .p-count {
      width: 200px;
    }
    .p-total {
      width: 200px;
      /* display: flex; */
      /* justify-content: space-between; */
      text-align: end;
    }

    .c-title {
      background-color: #37bbc6;
      color: #ffffff;
      display: flex;
      padding: 15px;
    }

    .card {
      margin: 5px 0px;
      border: 0px;
      border-radius: 0px;

      .m-header {
        padding: 15px;
        display: flex;
        justify-content: space-between;
        cursor: pointer;

        .h-t {
          display: flex;
        }
      }

      .header {
        padding: 15px;
        display: flex;
        justify-content: space-between;
        font-weight: bold;
        cursor: pointer;

        .h-t {
          display: flex;
        }
      }

      .case-row {
        padding: 5px;
        margin: 5px;
        border-top: 2px dashed #b3b3b3;

        .d-bg {
          padding: 5px;
          display: flex;
          color: #083a6e;

          &:hover {
            background: #f5f5f5;
          }
        }
      }

      .close-row {
        display: flex;
        padding: 15px;
      }

      .mc-body {
        border-top: 2px solid #d2d2d2;
      }
    }
  }

  .no-data {
    padding: 40px;
    display: grid;
    text-align: center;
    width: 420px;
    margin: 0px auto;

    img {
      margin: 0px auto;
      opacity: 0.5;
    }

    a {
      font-size: 20px;
      font-weight: bolder;
      color: #ff1212;
    }
  }
}

@media screen and (max-width: 767px) {
  .closedcase-wrapper {
    width: 100%;
    padding: 10px;

    .no-data {
      padding: 10px;
      width: 100%;
    }

    #accordion {
      .p-type {
        width: 120px;
      }
      .p-count {
        width: 70px;
      }
      .p-total {
        width: 120px;
        /* display: flex; */
        /* justify-content: space-between; */
        text-align: end;
      }

      .card {
        .header {
          .h-t {
            width: 100%;
          }
        }
      }
    }
  }
}
</style>
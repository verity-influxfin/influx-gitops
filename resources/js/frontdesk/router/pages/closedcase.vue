<template>
  <div class="closedcase-wrapper">
    <div class="no-data" v-if="groupList.length === 0">
      <img :src="'./Images/invest_empty.svg'" class="img-fluid" />
      <a target="_blank" href="https://event.influxfin.com/r/iurl?p=webinvest">目前沒有投資標的，點我立即前往 >></a>
    </div>
    <div id="accordion" role="tablist" v-else>
      <div class="card" v-for="(product,key) in groupList" :key="key">
        <div
          class="card-header product-card-header collapsed"
          role="button"
          data-parent="#accordion"
          data-toggle="collapse"
          :data-target="`#collapse${key}`"
          aria-expanded="true"
        >
          <div class="float-left header-title">
            <div class="gap item1">{{product}}</div>
            <div class="gap count item2">{{finishedData[product].length}}件</div>
            <div class="gap item3">
              應收本金
              <span class="total">${{getTotal(finishedData[product])}}</span>
            </div>
          </div>
        </div>
        <div :id="`collapse${key}`" class="collapse" data-parent="#accordion">
          <div class="card-body">
            <div v-for="(item,age) in groupByAge(finishedData[product])" :key="age">
              <div v-if="item.length !==0">
                <div
                  class="card-header age-card-header collapsed"
                  role="button"
                  :data-parent="`#collapse${key}`"
                  data-toggle="collapse"
                  :data-target="`#collapse${age}`"
                  aria-expanded="true"
                >
                  <div class="float-left header-title">
                    <div class="gap item1">{{ageTextList[age]}}</div>
                    <div class="gap count item2">{{item.length}}件</div>
                    <div class="gap item3">
                      應收本金
                      <span class="total">${{getTotal(item)}}</span>
                    </div>
                  </div>
                </div>
                <div :id="`collapse${age}`" class="collapse" :data-parent="`collapse${key}`">
                  <div class="card-body">
                    <div v-for="(row,type) in groupByType(item)" :key="type">
                      <div
                        class="group-row"
                        v-if="item.length !==0"
                        @click="showCases(row,typeTextList[type])"
                      >
                        <div class="gap item1">{{typeTextList[type]}}</div>
                        <div class="gap count item2">{{row.length}}件</div>
                        <div class="gap item3">
                          回收總額
                          <span class="total">${{getTotal(row)}}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// groupby
Array.prototype.groupBy = function(key1, key2 = "") {
  return this.reduce(function(groups, item) {
    const val = key2 ? item[key1][key2] : item[key1];
    groups[val] = groups[val] || [];
    groups[val].push(item);
    return groups;
  }, {});
};

import investDeatil from "./component/investDetailComponent";

export default {
  components: {
    investDeatil
  },
  data: () => ({
    category: "",
    groupList: [],
    detailData: {},
    finishedData: {},
    ageTextList: {
      normal: "正常案",
      observed: "觀察案",
      attention: "關注案",
      secondary: "次級案",
      bad: "不良案"
    },
    typeTextList: {
      normal: "正常",
      advance: "提前清償",
      transfer: "產品轉換",
      sell: "債權出讓"
    }
  }),
  created() {
    this.getFinishedData();
  },
  methods: {
    format(data) {
      let l10nEN = new Intl.NumberFormat("en-US");
      return l10nEN.format(data.toFixed(0));
    },
    getFinishedData() {
      axios
        .post("getRecoveriesFinished")
        .then(res => {
          this.finishedData = res.data.data.list.groupBy(
            "target",
            "product_id"
          );

          this.groupList = Object.keys(this.finishedData);
        })
        .catch(error => {
          console.log("getRecoveriesFinished 發生錯誤，請稍後再試");
        });
    },
    getTotal(data) {
      let total = 0;

      data.forEach((row, index) => {
        total +=
          row.income.delay_interest +
          row.income.interest +
          row.income.other +
          row.income.principal +
          row.income.transfer;
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
                  advance.push(row);
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

      return { normal, advance, transfer, sell };
    },
    showCases(data, category) {
      this.detailData = data;
      this.category = category;
    }
  }
};
</script>

<style lang="scss">
.closedcase-wrapper {
  margin-right: 10px;
  padding: 10px;
  box-shadow: 0 0 5px #848484;
  border-radius: 10px;
  background: #efefef;

  #accordion {
    .card {
      margin-bottom: 20px;

      .product-card-header {
        background: linear-gradient(45deg, #0014ff, #7cc3ff);
        color: #ffffff;
        font-weight: bolder;
        cursor: pointer;

        .header-title {
          .count {
            color: #ffa500;
            text-decoration: underline;
          }

          .total {
            color: #000000;
            margin-left: 5px;
            float: right;
          }
        }
      }

      .age-card-header {
        background: linear-gradient(45deg, #9b5cff, #fdddff);
        color: #ffffff;
        font-weight: bolder;
        cursor: pointer;
        overflow: auto;

        .header-title {
          .count {
            color: #ffa500;
            text-decoration: underline;
          }

          .total {
            color: #000000;
            margin-left: 5px;
            float: right;
          }
        }
      }

      .group-row {
        border-bottom: 2px solid #12a700;
        padding: 10px 0px;
        cursor: pointer;
        font-weight: bolder;
        overflow: auto;

        &:hover {
          background: #c1c1c1;
        }

        span {
          margin: 0px 10px;
        }

        .count {
          color: #ffa500;
          text-decoration: underline;
        }

        .total {
          color: #000000;
          margin-left: 5px;
          float: right;
        }
      }
    }

    .gap {
      margin: 0px 20px;
      float: left;
    }

    .item1 {
      width: 100px;
    }

    .item2 {
      width: 70px;
    }

    .item3 {
      width: 200px;
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

  @media screen and (max-width: 767px) {
    .no-data {
      width: auto;

      a {
        font-size: 16px;
      }
    }
    #accordion {
      .header-title {
        display: flex;

        .item1 {
          width: 75px;
        }
      }

      .group-row {
        .item1 {
          width: 70px;
        }

        .item2{
          width: 40px;
        }
      }

      .gap {
        margin: 0px;
      }

      .item1 {
        width: 100px;
      }

      .item2 {
        width: 50px;
      }

      .item3 {
        width: 150px;
      }
    }
  }
}
</style>
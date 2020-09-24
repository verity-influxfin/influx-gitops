<template>
  <div class="debt-wrapper">
    <div class="no-data" v-if="groupList.length === 0">
      <img src="../asset/images/empty.svg" class="img-fluid" />
      <a target="_blank" href="https://event.influxfin.com/r/iurl?p=webinvest">目前沒有投資標的，點我立即前往 >></a>
    </div>
    <div v-else id="accordion" role="tablist">
      <div class="c-title">
        <div class="p-type">案件類型</div>
        <div class="p-count">案件件數</div>
        <div class="p-total">應收本息</div>
      </div>
      <div class="card" v-for="(product,key) in groupList" :key="key">
        <div
          class="header collapsed"
          data-parent="#accordion"
          data-toggle="collapse"
          :data-target="`#collapse${key}`"
          aria-expanded="false"
        >
          <div class="h-t">
            <div class="p-type">{{product}}</div>
            <div class="p-count">{{recoveriesData[product].length}}件</div>
            <div class="p-total">
              <!-- <span>應收本金</span> -->
              <span>${{getTotal(recoveriesData[product])}}</span>
            </div>
          </div>
          <span class="accicon">
            <i class="fas fa-angle-down rotate-icon"></i>
          </span>
        </div>
        <div :id="`collapse${key}`" class="collapse" data-parent="#accordionExample">
          <div class="c-body">
            <template v-for="(item,index) in groupBy(recoveriesData[product])">
              <div
                class="case-row"
                v-if="item.length !==0"
                @click="showCases(item,textList[index])"
                :key="index"
              >
                <div class="d-bg">
                  <div class="p-type">{{textList[index]}}</div>
                  <div class="p-count">{{item.length}}件</div>
                  <div class="p-total">
                    <!-- <span>金額</span> -->
                    <span>${{getTotal(item)}}</span>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </div>
      </div>
    </div>
    <investDeatil
      ref="investDeatil"
      :detailData="detailData"
      :category="category"
      :investCaseInfo="investCaseInfo"
      :showDetailBtn="true"
      @sendInfo="getCaseInfo"
    />
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
    recoveriesData: {},
    detailData: {},
    investCaseInfo: {},
    textList: {
      normal: "正常案",
      observed: "觀察案",
      attention: "關注案",
      secondary: "次級案",
      bad: "不良案",
    },
    groupList: [],
  }),
  created() {
    this.getRecoveriesData();
    this.$parent.pageIcon = "/images/icon_moneyback_b.svg";
    this.$parent.pageTitle = "債權總覽";
    this.$parent.pagedesc = "您手上所有的債權";
  },
  methods: {
    format(data) {
      let l10nEN = new Intl.NumberFormat("en-US");
      return l10nEN.format(data.toFixed(0));
    },
    getRecoveriesData() {
      axios
        .post("getRecoveriesList")
        .then((res) => {
          this.recoveriesData = res.data.data.list.groupBy(
            "target",
            "product_name"
          );

          this.groupList = Object.keys(this.recoveriesData);
        })
        .catch((error) => {
          console.log("getRecoveriesList 發生錯誤，請稍後再試");
        });
    },
    getTotal(data) {
      let total = 0;

      data.forEach((row, index) => {
        total +=
          row.accounts_receivable.delay_interest +
          row.accounts_receivable.interest +
          row.accounts_receivable.principal;
      });

      return this.format(total);
    },
    groupBy(data) {
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
    showCases(data, category) {
      this.detailData = data;
      this.category = category;
      $(this.$refs.investDeatil.$refs.investModal).modal("show");
    },
    getCaseInfo(id) {
      axios
        .post("getRecoveriesInfo", { id })
        .then((res) => {
          this.investCaseInfo = res.data.data;
          $(this.$refs.investDeatil.$refs.detailModal).modal("show");
        })
        .catch((error) => {
          console.log("getRecoveriesInfo 發生錯誤，請稍後再試");
        });
    },
  },
};
</script>

<style lang="scss">
.debt-wrapper {
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
        cursor: pointer;
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
  .debt-wrapper {
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
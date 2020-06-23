<template>
  <div class="debt-wrapper">
    <div class="no-data" v-if="groupList.length === 0">
      <img :src="'./Images/invest_empty.svg'" class="img-fluid" />
      <a target="_blank" href="https://event.influxfin.com/r/iurl?p=webinvest">目前沒有投資標的，點我立即前往 >></a>
    </div>
    <div id="accordion" role="tablist" v-else>
      <div class="card" v-for="(product,key) in groupList" :key="key">
        <div
          class="card-header collapsed"
          role="button"
          data-parent="#accordion"
          data-toggle="collapse"
          :data-target="`#collapse${key}`"
          aria-expanded="true"
        >
          <div class="float-left header-title">
            <div class="gap item1">{{product}}</div>
            <div class="gap count item2">{{recoveriesData[product].length}}件</div>
            <div class="gap item3">
              應收本金
              <span class="total">${{getTotal(recoveriesData[product])}}</span>
            </div>
          </div>
        </div>
        <div :id="`collapse${key}`" class="collapse" data-parent="#accordionExample">
          <div class="card-body">
            <div v-for="(item,index) in groupBy(recoveriesData[product])" :key="index">
              <div
                class="group-row"
                v-if="item.length !==0"
                @click="showCases(item,textList[index])"
              >
                <div class="gap item1">{{textList[index]}}</div>
                <div class="gap count item2">{{item.length}}件</div>
                <div class="gap item3">
                  金額
                  <span class="total">${{getTotal(item)}}</span>
                </div>
              </div>
            </div>
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
    recoveriesData: {},
    detailData: {},
    investCaseInfo: {},
    textList: {
      normal: "正常案",
      observed: "觀察案",
      attention: "關注案",
      secondary: "次級案",
      bad: "不良案"
    },
    groupList: []
  }),
  created() {
    this.getRecoveriesData();
  },
  methods: {
    format(data) {
      let l10nEN = new Intl.NumberFormat("en-US");
      return l10nEN.format(data.toFixed(0));
    },
    getRecoveriesData() {
      axios
        .post("getRecoveriesList")
        .then(res => {
          this.recoveriesData = res.data.data.list.groupBy(
            "target",
            "product_name"
          );

          this.groupList = Object.keys(this.recoveriesData);
        })
        .catch(error => {
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
        .then(res => {
          this.investCaseInfo = res.data.data;
          $(this.$refs.investDeatil.$refs.detailModal).modal("show");
        })
        .catch(error => {
          console.log("getRecoveriesInfo 發生錯誤，請稍後再試");
        });
    }
  }
};
</script>

<style lang="scss">
.debt-wrapper {
  margin-right: 10px;
  padding: 10px;
  box-shadow: 0 0 5px #848484;
  border-radius: 10px;
  background: #efefef;

  #accordion {
    .card {
      margin-bottom: 20px;

      .card-header {
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
    }

    .card-body {
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

  @media screen and (max-width: 1023px) {
    .no-data {
      width: auto;

      a {
        font-size: 16px;
      }
    }
    #accordion {
      .header-title {
        display: flex;
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
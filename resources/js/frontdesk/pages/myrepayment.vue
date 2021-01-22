<template>
  <div class="repayment-wrapper">
    <div class="repayment-card">
      <div v-if="installment.length === 0" class="no-data">
        <img :src="'/images/empty.svg'" class="img-fluid" />
        <a target="_blank" href="https://event.influxfin.com/R/url?p=17K5591Q"
          >請往APP了解更多 >></a
        >
      </div>
      <template v-else>
        <div class="info-card" v-for="(item, index) in installment" :key="index">
          <div class="title">
            {{ item.product_name }}
            <br />
            {{ item.target_no }}
          </div>
          <div class="circle">
            <circle-progress
              key="animation-model"
              :width="circleWidth"
              radius="6"
              barColor="#4493C2"
              duration="1000"
              delay="20"
              timeFunction="cubic-bezier(0.99, 0.01, 0.22, 0.94)"
              backgroundColor="#f5f5f5"
              :progress="progress(item)"
              :isAnimation="true"
              :isRound="true"
            ></circle-progress>
            <div class="period">
              {{ item.next_repayment.instalment }}&nbsp;/&nbsp;{{ item.instalment }}
            </div>
          </div>
          <div class="payment">
            <div class="p-d">
              <div class="pd-l">{{ item.next_repayment.date }}</div>
              <div class="pd-m">本期還款日期</div>
            </div>
            <div class="p-d">
              <div class="pd-l" :style="{ color: item.delay_days > 0 ? 'red' : '' }">
                ${{ format(item.next_repayment.amount) }}元
              </div>
              <div class="pd-m">本期還款金額</div>
            </div>
          </div>
          <div class="link">
            <detailBtn
              :data="item"
              :left="true"
              @sendinfo="getInfo(item.id, item.status)"
            ></detailBtn>
            <a
              class="btn btn-secondary btn-sm float-right"
              target="_blank"
              href="https://line.me/R/ti/p/%40kvd1654s"
              >聯繫客服</a
            >
          </div>
          <div class="delay-memo" v-if="item.delay_days > 0">
            您已逾期，請至APP全額清償或申請產品轉換
          </div>
        </div>
      </template>
    </div>
    <div class="statement-card">
      <statement :list="list" @searchDteail="search" @download="downloadCSV" />
    </div>
    <div
      ref="detailModal"
      class="detail-modal modal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalLabel"
      aria-hidden="true"
      data-backdrop="static"
    >
      <div class="modal-dialog" v-if="Object.keys(detailData).length !== 0">
        <div class="modal-content">
          <div class="modal-body">
            <div :class="['detail-banner', { delay: isDelay }]">
              <span>{{ isDelay ? "逾期總額" : "本金餘額" }}</span>
              <span class="amount">{{
                format(
                  isDelay
                    ? detailData.amortization_schedule.total_payment
                    : detailData.amortization_schedule.remaining_principal
                )
              }}</span>
              <span>$</span>
            </div>
            <div class="detail-title">
              <label>{{ detailData.product_name }}</label>
              <span :class="{ delay: isDelay }">{{ statusText }}</span>
            </div>
            <div class="detail-subtitle">
              <label>案件編號</label>
              <span>{{ detailData.target_no }}</span>
            </div>
            <div class="delay-info" v-if="isDelay">
              <div class="delay-row">
                <div class="card-item">
                  <label>當期還款日</label>
                  <br />
                  <span class="delay">{{ detailData.next_repayment.date }}</span>
                </div>
                <div class="card-item">
                  <label>逾期日數</label>
                  <br />
                  <span class="delay">{{ detailData.delay_days }}日</span>
                </div>
              </div>
              <div class="delay-row">
                <div class="card-item">
                  <label>逾期本金</label>
                  <br />
                  <span>{{ format(detailData.loan_amount) }}</span>
                </div>
                <div class="card-item">
                  <label>逾期利息</label>
                  <br />
                  <span>{{ format(detailData.next_repayment.interest) }}</span>
                </div>
              </div>
              <div class="delay-row">
                <div class="card-item">
                  <label>逾期違約金</label>
                  <br />
                  <span class="delay">{{
                    format(detailData.next_repayment.liquidated_damages)
                  }}</span>
                </div>
                <div class="card-item">
                  <label>逾期延滯息</label>
                  <br />
                  <span class="delay">{{
                    format(detailData.next_repayment.delay_interest)
                  }}</span>
                </div>
              </div>
              <div class="delay-row" v-if="detailData.targetDatas.virtual_account">
                <div class="card-item">
                  <label>案件還款行</label>
                  <br />
                  <span
                    >({{ detailData.targetDatas.virtual_account.bank_code }}){{
                      detailData.targetDatas.virtual_account.bank_name
                    }}</span
                  >
                </div>
                <div class="card-item">
                  <label>案件還款分行</label>
                  <br />
                  <span
                    >({{ detailData.targetDatas.virtual_account.branch_code }}){{
                      detailData.targetDatas.virtual_account.branch_name
                    }}</span
                  >
                </div>
              </div>
              <div class="delay-row" v-if="detailData.targetDatas.virtual_account">
                <div class="card-item">
                  <label>案件還款帳號</label>
                  <span>{{
                    detailData.targetDatas.virtual_account.virtual_account
                  }}</span>
                </div>
                <div class="card-item"></div>
              </div>
            </div>
            <div class="detail-info" v-else>
              <div class="card-item">
                <label>本期還款日</label>
                <br />
                <span>{{ detailData.next_repayment.date }}</span>
              </div>
              <div class="card-item">
                <label>本期還款金額</label>
                <br />
                <span>{{ format(detailData.next_repayment.amount) }}$</span>
              </div>
            </div>
            <div class="detail-row">
              <label>還款方式</label>
              <span>{{ repaymentMethod }}</span>
            </div>
            <div class="detail-row">
              <label>借款總額</label>
              <span>{{ format(detailData.loan_amount) }}</span>
            </div>
            <div class="detail-row">
              <label>借款期間</label>
              <span
                >{{ detailData.amortization_schedule.date }} -
                {{ detailData.amortization_schedule.end_date }}</span
              >
            </div>
            <div class="detail-row">
              <label>帳期</label>
              <span
                >{{ detailData.next_repayment.instalment }}/{{
                  detailData.instalment
                }}期</span
              >
            </div>
          </div>
          <div class="modal-footer" style="display: block">
            <button class="btn btn-info float-left" @click="open">查看還款明細</button>
            <button class="btn btn-primary float-right" data-dismiss="modal">確認</button>
          </div>
        </div>
      </div>
    </div>

    <div
      ref="openModal"
      class="charts-modal modal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalLabel"
      aria-hidden="true"
      data-backdrop="static"
    >
      <div class="modal-dialog" v-if="Object.keys(detailData).length !== 0">
        <div class="modal-content">
          <div class="modal-body">
            <div class="charts-container">
              <div class="repayment-charts" ref="repayment_charts"></div>
            </div>
            <div class="detail-row-container">
              <div
                class="repayment-detail-item"
                v-for="(item, index) in repaymentDeatilRow"
                :key="index"
              >
                <div class="row1">
                  <p>{{ item.repayment_date }}</p>
                  <span>第{{ item.instalment }}/{{ detailData.instalment }}期</span>
                </div>
                <div class="row2">
                  <p style="color: orange">${{ format(item.total_payment) }}</p>
                  <span v-if="isDelay">逾期清償</span>
                  <span v-else>
                    含利息
                    <span style="color: lightblue">${{ format(item.interest) }}</span>
                  </span>
                </div>
                <div
                  class="row3"
                  v-html="
                    ckeckStatus(item.repayment, item.delay_interest, item.total_payment)
                  "
                ></div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button
              class="btn btn-success"
              style="width: 100%"
              @click="closeModal($refs.openModal)"
            >
              確認
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import circleProgress from "../component/circleProgressComponent";
import detailBtn from "../component/detailBtnComponent";
import statement from "../component/statementComponent";

export default {
  beforeRouteEnter(to, from, next) {
    if (sessionStorage.length === 0 || sessionStorage.flag === "logout") {
      next("/index");
    } else {
      next();
    }
  },
  components: {
    detailBtn,
    circleProgress,
    statement,
  },
  data: () => ({
    circleWidth: 200,
    detailData: {},
    repaymentDeatilRow: [],
    list: [],
    isDelay: false,
  }),
  computed: {
    repaymentNumber() {
      let l10nEN = new Intl.NumberFormat("en-US");
      return l10nEN.format(this.$parent.myRepayment.next_repayment.amount.toFixed(0));
    },
    installment() {
      return this.$store.getters.ApplyList.installment;
    },
    repaymentMethod() {
      let methodText = {
        1: "等額本息",
        2: "先息後本",
        3: "以日計息",
      };

      return methodText[this.detailData.repayment];
    },
    statusText() {
      let applyingList = ["0", "1", "2", "3", "4"];
      let subStatus = ["1", "2", "3", "4", "8"];
      let subText = {
        1: "產品轉換申請中",
        2: "產品轉換完成",
        3: "提前還款申請中",
        4: "提前還款完成",
        8: "產品轉換案件還款中",
      };

      if (applyingList.indexOf(this.detailData.status) !== -1) {
        return "案件申請中";
      } else if (this.detailData.status === 5) {
        if (this.detailData.delay_days > 7) {
          return "案件逾期";
        } else if (this.detailData.delay_days > 0) {
          return "寬限期內待還款";
        } else if (subStatus.indexOf(this.detailData.sub_status) !== -1) {
          return subText[this.detailData.status];
        } else {
          return "正常還款中";
        }
      } else if (this.detailData.status === 10) {
        if (subStatus.indexOf(this.detailData.sub_status) !== -1) {
          return subText[this.detailData.status];
        } else {
          return "案件已清償";
        }
      } else {
        let textList = {
          8: "申請取消",
          9: "申請失敗",
        };

        return textList[this.detailData.status];
      }
    },
  },
  methods: {
    format(data) {
      let l10nEN = new Intl.NumberFormat("en-US");
      return l10nEN.format(data.toFixed(0));
    },
    progress(item) {
      return item.next_repayment.instalment === 0
        ? 0
        : (item.next_repayment.instalment / item.instalment) * 100;
    },
    getInfo(id, status) {
      let showDetail = [5, 10, 20];
      if (showDetail.indexOf(status) !== -1) {
        axios
          .post("getDetail", { id })
          .then((res) => {
            this.showDetail(res.data.data);
          })
          .catch((error) => {
            if (error.response.data.error === 100) {
              alert("連線逾時，請重新登入");
              this.$root.logout();
            } else {
              console.log("getDetail 發生錯誤，請稍後在試");
            }
          });
      }
    },
    showDetail(data) {
      this.detailData = data;
      this.isDelay = this.detailData.delay_days > 0 ? true : false;

      $(this.$refs.detailModal).modal("show");
    },
    open() {
      let $this = this;
      $this.repaymentDeatilRow = $this.detailData.amortization_schedule.list;

      let axisData = [],
        principalData = [],
        interestData = [],
        totalData = [],
        count = 0;

      $.each($this.detailData.amortization_schedule.list, (key, row) => {
        count++;
        axisData.push(row.repayment_date);
        principalData.push(row.principal);
        interestData.push(row.interest);
        totalData.push(row.total_payment);
      });

      $($this.$refs.repayment_charts).css(
        "width",
        `${count * 50 < 466 ? 466 : count * 50}px`
      );

      let repayment_charts = echarts.init($this.$refs.repayment_charts);

      let option = {
        grid: {
          left: 60,
          top: 60,
          right: 60,
          bottom: 30,
        },
        legend: {
          selectedMode: false,
          textStyle: {
            color: "#ffffff",
          },
          left: 20,
          top: 20,
          data: [
            {
              name: "每月還款本金",
              icon: "path://M15,25A5,5,0,1,1,30,25A5,5,0,1,1,15,25",
            },
            {
              name: "每月還款利息",
              icon: "path://M15,25A5,5,0,1,1,30,25A5,5,0,1,1,15,25",
            },
          ],
        },
        tooltip: {
          trigger: "axis",
          confine: true,
          formatter(params) {
            let res = `<span>日期：${params[0].axisValue}</span><br>`;

            $.each(params.reverse(), (index, row) => {
              res += `<span>${
                row.marker + row.seriesName
              }：<span style="float:right;">$${$this.format(
                row.value
              )}</span></span><br>`;
            });

            return res;
          },
        },
        xAxis: {
          type: "category",
          boundaryGap: false,
          axisLine: {
            lineStyle: {
              color: "#ffffff",
            },
          },
          axisTick: {
            show: false,
          },
          splitLine: {
            show: true,
            lineStyle: {
              color: "#ffffff",
            },
          },
          axisLabel: {
            formatter(value, index) {
              return value;
            },
          },
          data: axisData,
        },
        yAxis: {
          type: "value",
          show: false,
        },
        series: [
          {
            name: "總額",
            type: "line",
            symbolSize: 1,
            hoverAnimation: false,
            legendHoverLink: false,
            legendHoverLink: false,
            lineStyle: {
              width: 0,
            },
            itemStyle: {
              color: "#fff",
            },
            data: totalData,
          },
          {
            name: "每月還款利息",
            stack: "total",
            symbol: "circle",
            symbolSize: 6,
            itemStyle: {
              color: "lightblue",
            },
            lineStyle: {
              color: "lightblue",
            },
            type: "line",
            data: interestData,
          },
          {
            name: "每月還款本金",
            stack: "total",
            symbol: "circle",
            symbolSize: 6,
            itemStyle: {
              color: "orange",
            },
            lineStyle: {
              color: "orange",
            },
            type: "line",
            data: principalData,
          },
        ],
      };

      repayment_charts.setOption(option);
      $($this.$refs.openModal).modal("show");
    },
    ckeckStatus(repayment, delay_interest, total_payment) {
      if (0 == repayment) {
        if (0 == delay_interest) {
          return `<span style="color:#4A90E2;">待還款</span>`;
        } else {
          return `<span style="color:#FF575F;">逾期未還</span>`;
        }
      } else if (repayment === total_payment) {
        if (0 == delay_interest) {
          return `<span style="color:#7ED321;">已還款</span>`;
        } else {
          return `<span style="color:#4A4A4A;">逾期清償</span>`;
        }
      }
    },
    search(type) {
      let $this = this;
      axios
        .post("getTansactionDetails", { isInvest: false })
        .then((res) => {
          $this.list = res.data.data.list;
        })
        .catch((error) => {
          if (error.response.data.error === 100) {
            alert("連線逾時，請重新登入");
            this.$root.logout();
          } else {
            console.log("getTansactionDetails 發生錯誤，請稍後在試");
          }
        });
    },
    downloadCSV(range) {
      let start = range.start.getTime();
      let end = range.end.getTime();
      $("#csvDownloadIframe").remove();
      $("body").append(
        `<iframe id="csvDownloadIframe" src="${location.origin}/downloadStatement?start=${start}&end=${end}&isInvest=0" style="display: none"></iframe>`
      );
    },
    closeModal($el) {
      $($el).modal("hide");

      setTimeout(() => {
        $("body").addClass("modal-open");
      }, 500);
    },
  },
};
</script>

<style lang="scss">
.repayment-wrapper {
  display: flex;
  position: relative;
  width: 85%;
  margin: 0px auto;
  padding: 25px;

  .repayment-card {
    width: 85%;
    margin: 0px auto;
    overflow: auto;
    position: relative;
    .info-card {
      border-radius: 9px;
      box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);
      background-color: #ffffff;
      margin: 10px;
      padding: 10px;
      width: calc(100% / 3 - 20px);
      float: left;
      height: 400px;

      .title {
        text-align: center;
        font-weight: bolder;
      }

      .circle {
        position: relative;
        margin: 0px auto;
        width: fit-content;
        height: 200px;

        .period {
          position: absolute;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          text-align: center;
          font-size: 40px;
          color: #6c8e9a;
          font-weight: bolder;
        }
      }

      .payment {
        display: flex;
        justify-content: center;
        margin: 10px 0px;

        .p-d {
          width: 50%;
          padding: 0px 10px;

          &:nth-of-type(1) {
            border-right: 2px dashed #c8c8c8;
          }

          .pd-l {
            font-size: 20px;
          }

          .pd-m {
            font-size: 14px;
          }
        }
      }

      .link {
        margin: 10px 0px;
        overflow: auto;
      }

      .delay-memo {
        border-left: 2px solid red;
        margin: 10px 0px 0px 0px;
        padding: 0px 5px;
        font-size: 10px;
        color: red;
      }
    }

    .head-title {
      margin: 10px 10px 10px 0px;
      overflow: auto;
      padding: 8px;
      background: #ffffff;
      border-radius: 6px;
      width: 245px;

      .detail {
        color: #000a82;
        font-weight: bolder;
      }
    }

    .no-data {
      display: grid;
      width: 45%;
      margin: 0px auto;

      img {
        opacity: 0.5;
      }

      a {
        text-align: center;
        color: #000000;
        font-weight: bolder;
        font-size: 22px;
        margin-top: 15px;
      }
    }
  }

  .detail-modal {
    .detail-banner {
      background: #4b59ff;
      border-radius: 8px;
      padding: 10px;
      text-align: center;
      box-shadow: 0 0 4px #0008ff;
      color: #ffffff;
      margin-bottom: 10px;

      .amount {
        color: #ffed14;
        font-size: 34px;
        padding: 0px 5px;
      }
    }

    .detail-banner.delay {
      background: #ff4b4b;
      box-shadow: 0 0 4px #ff0000;
    }

    .detail-title {
      label {
        font-size: 20px;
        color: #009cbf;
        font-weight: bolder;
      }

      span {
        float: right;
        color: #9c9c9c;
      }

      .delay {
        color: #ff6b6b;
      }
    }

    .detail-subtitle {
      span {
        float: right;
      }
    }

    .detail-info {
      display: flex;
      background: #d9e4ff;
      border-radius: 5px;
      padding: 5px;
      margin-bottom: 10px;

      .card-item {
        width: 50%;
        text-align: center;

        label {
          color: #656565;
        }

        span {
          color: #1a2cff;
          font-size: 20px;
          font-weight: bolder;
        }
      }
    }

    .detail-row {
      label {
        color: #505050;
        font-weight: bolder;
      }

      span {
        float: right;
      }
    }

    .delay-info {
      background: #ffd9d9;
      border-radius: 5px;
      padding: 5px;
      margin-bottom: 10px;

      .delay-row {
        display: flex;

        .card-item {
          width: 100%;
          text-align: center;

          label {
            color: #656565;
          }

          span {
            color: #1a2cff;
            font-size: 20px;
            font-weight: bolder;

            &.delay {
              color: #ff1a1a !important;
            }
          }
        }
      }
    }
  }

  .charts-modal {
    .charts-container {
      overflow: auto;
    }

    .detail-row-container {
      overflow: auto;
      height: 295px;
      background: #f5f5f5;
    }

    .repayment-charts {
      height: 300px;
      background: linear-gradient(180deg, #38acff, #0067ff);
    }

    .repayment-detail-item {
      display: flex;
      background: #ffffff;
      margin: 10px;
      border-radius: 10px;
      padding: 10px;
      box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);

      .row1 {
        padding-left: 20px;
        width: 35%;
        font-weight: 600;
      }

      .row2 {
        width: 35%;
        text-align: end;
      }

      .row3 {
        width: 30%;
        text-align: center;
        line-height: 63px;
        font-weight: 600;
      }
    }
  }
}

@media screen and (max-width: 767px) {
  .repayment-wrapper {
    flex-direction: column;
    width: 100%;
    padding: 0px 10px;

    %back {
      margin: 15px auto;
      width: 97%;
    }

    .repayment-card {
      width: 100%;
      order: 2;

      .info {
        width: calc(100%);
        margin: 10px 0px;
      }

      .info-card {
        width: 100%;
        margin: 10px 0px;

        .payment {
          .p-d {
            padding: 0px 10px;
          }
        }
      }
    }
  }
}
</style>

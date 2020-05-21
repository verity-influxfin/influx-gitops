<template>
  <div class="myrepayment-wrapper">
    <div class="accound-card">
      <p class="legend">
        親愛的用戶您好：
        <br />還款請以約定金融卡轉帳至以下專屬帳戶內，中午12點後為隔日帳，為避免銀行作業影響普匯入帳時間及計息天數，請儘早匯款。
      </p>
      <h3>專屬還款帳號</h3>
      <div>
        <span class="title">銀行名稱：</span>
        <span
          class="des"
        >({{$parent.myRepayment.virtual_account.bank_code}}){{$parent.myRepayment.virtual_account.bank_name}}</span>
      </div>
      <div>
        <span class="title">分行名稱：</span>
        <span
          class="des"
        >({{$parent.myRepayment.virtual_account.branch_code}}){{$parent.myRepayment.virtual_account.branch_name}}</span>
      </div>
      <div>
        <span class="title">銀行帳號：</span>
        <span class="des">{{$parent.myRepayment.virtual_account.virtual_account}}</span>
      </div>
      <hr />
      <div>
        次還款日&emsp;
        <strong class="des green">{{$parent.myRepayment.next_repayment.date}}</strong>
      </div>
      <p class="sm">
        請於還款日前匯入
        <strong class="green">{{repaymentNumber}}</strong>元
      </p>
    </div>

    <div class="repayment-card">
      <div v-if="installment.length === 0" class="no-data">
        <img :src="'./image/empty.svg'" class="img-fluid" />
        <a target="_blank" href="https://event.influxfin.com/R/url?p=17K5591Q">請往APP了解更多 >></a>
      </div>
      <div v-else>
        <div class="info-card" v-for="(item,index) in installment" :key="index">
          <div style="display: flex;">
            <div class="circle">
              <circle-progress
                key="animation-model"
                :width="circleWidth"
                radius="6"
                barColor="#ffbe5c"
                duration="1000"
                delay="20"
                timeFunction="cubic-bezier(0.99, 0.01, 0.22, 0.94)"
                backgroundColor="#ffdeab"
                :progress="progress(item)"
                :isAnimation="true"
                :isRound="true"
              ></circle-progress>
              <div class="period">
                {{item.next_repayment.instalment}}&nbsp;/&nbsp;{{item.instalment}}
                <br />期
              </div>
            </div>
            <div class="head-title">
              <p class="detail">
                {{item.product_name}}
                <br />
                {{item.target_no}}
              </p>
              <div style="color: #797979;">
                <div>
                  本期還款日期
                  <span class="float-right">{{item.next_repayment.date}}</span>
                </div>
                <div>
                  本期還款金額
                  <span
                    class="float-right"
                    :style="{'color':item.delay_days >0 ? 'red' : ''}"
                  >${{format(item.next_repayment.amount)}}</span>
                </div>
              </div>
              <detailBtn :data="item" :left="true" @sendinfo="getInfo(item.id,item.status)"></detailBtn>
              <a
                class="btn btn-secondary btn-sm float-right"
                target="_blank"
                href="https://line.me/R/ti/p/%40kvd1654s"
              >聯繫克服</a>
            </div>
          </div>
          <div class="delay-memo" v-if="item.delay_days >0">您已逾期，請至APP全額清償或申請產品轉換</div>
        </div>
      </div>
    </div>

    <div class="detail-card">
      <div class="input-group">
        <v-date-picker
          mode="range"
          v-model="range"
          style="width: 65%;"
          :popover="{ visibility: 'click' }"
        />
        <button class="btn btn-custom" type="button" @click="search('range')">
          <i class="fas fa-search"></i>
        </button>

        <button
          class="btn btn-info btn-sm btn-rel"
          type="button"
          style="left: -13px;"
          @click="search('month')"
        >本月</button>
        <button class="btn btn-primary btn-sm btn-rel" type="button" @click="search('all')">全部</button>
      </div>
      <div class="no-passbook-table" v-if="passbook.length ===0">
        <div class="no-passbook">
          <img :src="'./image/no_passbook.svg'" class="img-fluid" />
        </div>
      </div>
      <div class="passbook-table" v-else>
        <scrollingTable :syncHeaderScroll="false" :scrollHorizontal="false">
          <template slot="thead">
            <tr class="header-center">
              <th class="remark">科目</th>
              <th class="amount">現金流量</th>
              <th class="bank_amount">虛擬帳戶餘額</th>
            </tr>
          </template>
          <template slot="tbody">
            <tr v-for="(item,index) in passbook" :key="index">
              <td v-for="(text,colIndex) in item" :class="colIndex" v-html="text" :key="colIndex"></td>
            </tr>
          </template>
        </scrollingTable>
      </div>
    </div>

    <div
      ref="detailModal"
      class="detail-modal modal fade"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <div :class="['detail-banner', {'delay' : isDelay}]">
              <span>{{deatilTitle}}</span>
              <span class="amount">{{format(remainingPrincipal)}}</span>
              <span>$</span>
            </div>
            <div class="detail-title">
              <label>{{productName}}</label>
              <span :class="{'delay' : isDelay}">{{statusText}}</span>
            </div>
            <div class="detail-subtitle">
              <label>案件編號</label>
              <span>{{targetId}}</span>
            </div>
            <div class="delay-info" v-if="isDelay">
              <div class="delay-row">
                <div class="card-item">
                  <label>當期還款日</label>
                  <br />
                  <span class="delay">{{nextRepaymentDate}}</span>
                </div>
                <div class="card-item">
                  <label>逾期日數</label>
                  <br />
                  <span class="delay">{{delayDays}}日</span>
                </div>
              </div>
              <div class="delay-row">
                <div class="card-item">
                  <label>逾期本金</label>
                  <br />
                  <span>{{format(loanAmount)}}</span>
                </div>
                <div class="card-item">
                  <label>逾期利息</label>
                  <br />
                  <span>{{format(interest)}}</span>
                </div>
              </div>
              <div class="delay-row">
                <div class="card-item">
                  <label>逾期違約金</label>
                  <br />
                  <span class="delay">{{format(liquidatedDamages)}}</span>
                </div>
                <div class="card-item">
                  <label>逾期延滯息</label>
                  <br />
                  <span class="delay">{{format(delayInterest)}}</span>
                </div>
              </div>
              <div class="delay-row">
                <div class="card-item">
                  <label>案件還款行</label>
                  <br />
                  <span>({{vBankCode}}){{vBankName}}</span>
                </div>
                <div class="card-item">
                  <label>案件還款分行</label>
                  <br />
                  <span>({{vBranchCode}}){{vBranchName}}</span>
                </div>
              </div>
              <div class="delay-row">
                <div class="card-item">
                  <label>案件還款帳號</label>
                  <span>{{vAccount}}</span>
                </div>
                <div class="card-item"></div>
              </div>
            </div>
            <div class="detail-info" v-else>
              <div class="card-item">
                <label>本期還款日</label>
                <br />
                <span>{{nextRepaymentDate}}</span>
              </div>
              <div class="card-item">
                <label>本期還款金額</label>
                <br />
                <span>{{format(nextRepaymentAmount)}}$</span>
              </div>
            </div>
            <div class="detail-row">
              <label>還款方式</label>
              <span>{{repaymentMethod}}</span>
            </div>
            <div class="detail-row">
              <label>借款總額</label>
              <span>{{format(loanAmount)}}</span>
            </div>
            <div class="detail-row">
              <label>借款期間</label>
              <span>{{startDate}} - {{endDate}}</span>
            </div>
            <div class="detail-row">
              <label>帳期</label>
              <span>{{repayment}}/{{instalment}}期</span>
            </div>
          </div>
          <div class="modal-footer" style="display:block;">
            <button class="btn btn-info float-left" @click="open">查看還款明細</button>
            <button class="btn btn-primary" style="float: right;" data-dismiss="modal">確認</button>
          </div>
        </div>
      </div>
    </div>

    <div
      ref="openModal"
      class="charts-modal modal fade"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <div class="charts-container">
              <div class="repayment-charts" ref="repayment_charts"></div>
            </div>
            <div class="detail-row-container">
              <div
                class="repayment-detail-item"
                v-for="(item,index) in repaymentDeatilRow"
                :key="index"
              >
                <div class="row1">
                  <p>{{item.repayment_date}}</p>
                  <span>第{{item.instalment}}/{{instalment}}期</span>
                </div>
                <div class="row2">
                  <p style="color:orange">${{format(item.total_payment)}}</p>
                  <span v-if="isDelay">逾期清償</span>
                  <span v-else>
                    含利息
                    <span style="color:lightblue">${{format(item.interest)}}</span>
                  </span>
                </div>
                <div
                  class="row3"
                  v-html="ckeckStatus(item.repayment,item.delay_interest,item.total_payment)"
                ></div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-success" data-dismiss="modal">確認</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import circleProgress from "./component/circleProgressComponent";
import detailBtn from "./component/detailBtnComponent";
import scrollingTable from "./component/scrollingTableComponent";

export default {
  beforeRouteEnter(to, from, next) {
    if (sessionStorage.length === 0 || sessionStorage.flag === "logout") {
      next("/index");
    } else {
      next();
    }
  },
  components: {
    circleProgress,
    detailBtn,
    scrollingTable
  },
  data: () => ({
    circleWidth: $(window.document).outerWidth() < 1023 ? 90 : 120,
    detailData: {},
    repaymentDeatilRow: [],
    passbook: [],
    range: {
      start: new Date(),
      end: new Date()
    },
    deatilTitle: "",
    productName: "",
    targetId: "",
    nextRepaymentDate: "",
    startDate: "",
    endDate: "",
    repayment: "",
    instalment: "",
    nextRepaymentAmount: 0,
    loanAmount: 0,
    remainingPrincipal: 0,
    isDelay: false,
    delayDays: "",
    interest: "",
    liquidatedDamages: "",
    delayInterest: "",
    vBankName: "",
    vBankCOde: "",
    vBranchName: "",
    vBranchCode: "",
    vAccount: ""
  }),
  computed: {
    repaymentNumber() {
      let l10nEN = new Intl.NumberFormat("en-US");
      return l10nEN.format(
        this.$parent.myRepayment.next_repayment.amount.toFixed(0)
      );
    },
    installment() {
      return this.$store.getters.ApplyList.installment;
    },
    repaymentMethod() {
      let methodText = {
        "1": "等額本息",
        "2": "先息後本",
        "3": "以日計息"
      };

      return methodText[this.detailData.repayment];
    },
    statusText() {
      let applyingList = ["0", "1", "2", "3", "4"];
      let subStatus = ["1", "2", "3", "4", "8"];
      let subText = {
        "1": "產品轉換申請中",
        "2": "產品轉換完成",
        "3": "提前還款申請中",
        "4": "提前還款完成",
        "8": "產品轉換案件還款中"
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
          "8": "申請取消",
          "9": "申請失敗"
        };

        return textList[this.detailData.status];
      }
    }
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
          .then(res => {
            this.showDetail(res.data.data);
          })
          .catch(error => {
            console.log("getDetail 發生錯誤，請稍後在試");
          });
      }
    },
    showDetail(data) {
      this.detailData = data;
      this.isDelay = data.delay_days > 0 ? true : false;
      this.deatilTitle = this.isDelay ? "逾期總額" : "本金餘額";
      this.remainingPrincipal = this.isDelay
        ? data.amortization_schedule.total_payment
        : data.amortization_schedule.remaining_principal;
      this.nextRepaymentAmount = data.next_repayment.amount;
      this.loanAmount = data.loan_amount;
      this.productName = data.product_name;
      this.targetId = data.target_no;
      this.nextRepaymentDate = data.next_repayment.date;
      this.startDate = data.amortization_schedule.date;
      this.endDate = data.amortization_schedule.end_date;
      this.repayment = data.next_repayment.instalment;
      this.instalment = data.instalment;

      this.delayDays = data.delay_days;
      this.interest = data.next_repayment.interest;
      this.liquidatedDamages = data.next_repayment.liquidated_damages;
      this.delayInterest = data.next_repayment.delay_interest;

      if (data.targetDatas.virtual_account) {
        this.vBankCode = data.targetDatas.virtual_account.bank_code;
        this.vBankName = data.targetDatas.virtual_account.bank_name;
        this.vBranchCode = data.targetDatas.virtual_account.branch_code;
        this.vBranchName = data.targetDatas.virtual_account.branch_name;
        this.vAccount = data.targetDatas.virtual_account.virtual_account;
      }
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
          bottom: 30
        },
        legend: {
          selectedMode: false,
          textStyle: {
            color: "#ffffff"
          },
          left: 20,
          top: 20,
          data: [
            {
              name: "每月還款本金",
              icon: "path://M15,25A5,5,0,1,1,30,25A5,5,0,1,1,15,25"
            },
            {
              name: "每月還款利息",
              icon: "path://M15,25A5,5,0,1,1,30,25A5,5,0,1,1,15,25"
            }
          ]
        },
        tooltip: {
          trigger: "axis",
          confine: true,
          formatter(params) {
            let res = `<span>日期：${params[0].axisValue}</span><br>`;

            $.each(params.reverse(), (index, row) => {
              res += `<span>${row.marker +
                row.seriesName}：<span style="float:right;">$${$this.format(
                row.value
              )}</span></span><br>`;
            });

            return res;
          }
        },
        xAxis: {
          type: "category",
          boundaryGap: false,
          axisLine: {
            lineStyle: {
              color: "#ffffff"
            }
          },
          axisTick: {
            show: false
          },
          splitLine: {
            show: true,
            lineStyle: {
              color: "#ffffff"
            }
          },
          axisLabel: {
            formatter(value, index) {
              return value;
            }
          },
          data: axisData
        },
        yAxis: {
          type: "value",
          show: false
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
              width: 0
            },
            itemStyle: {
              color: "#fff"
            },
            data: totalData
          },
          {
            name: "每月還款利息",
            stack: "total",
            symbol: "circle",
            symbolSize: 6,
            itemStyle: {
              color: "lightblue"
            },
            lineStyle: {
              color: "lightblue"
            },
            type: "line",
            data: interestData
          },
          {
            name: "每月還款本金",
            stack: "total",
            symbol: "circle",
            symbolSize: 6,
            itemStyle: {
              color: "orange"
            },
            lineStyle: {
              color: "orange"
            },
            type: "line",
            data: principalData
          }
        ]
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

      let date = new Date();

      if (type === "month") {
        $this.range.start = new Date(date.getFullYear(), date.getMonth(), 1);
        $this.range.end = new Date(
          date.getFullYear(),
          date.getMonth(),
          date.getDate()
        );
      }

      $this.passbook = [];

      axios
        .post("getTansactionDetails")
        .then(res => {
          res.data.data.list.forEach((row, index) => {
            $this.passbook.push({
              remark: `${row.remark}<br>${row.tx_datetime.substr(2)}`,
              amount: `<span style="color:${
                row.amount > 0 ? "#5192E5" : "#FF4758"
              }">${$this.format(row.amount)}</span>`,
              bank_amount: $this.format(row.bank_amount)
            });
            if (type !== "all") {
              if (
                row.created_at + "000" >= $this.range.end.getTime() ||
                $this.range.start.getTime() >= row.created_at + "000"
              ) {
                $this.passbook.splice(-1, 1);
              }
            }
          });
        })
        .catch(error => {
          console.log("getTansactionDetails 發生錯誤，請稍後在試");
        });
    }
  }
};
</script>

<style lang="scss">
.myrepayment-wrapper {
  display: flex;

  %back {
    padding: 10px;
    box-shadow: 0 0 5px #848484;
    border-radius: 10px;
    background: #efefef;
  }

  .accound-card {
    @extend %back;
    width: 300px;
    height: fit-content;
    margin-right: 10px;

    .legend {
      font-size: 13px;
      font-weight: bolder;
      background: #cfe9ff;
      border-radius: 5px;
      padding: 5px;
      color: #5167ff;
    }

    .title {
      color: #757575;
      font-weight: bolder;
    }

    .des {
      float: right;
      color: #ad5000;
    }

    .green {
      color: #159159;
    }

    .sm {
      font-size: 13px;
      color: #868686;
    }
  }

  .repayment-card {
    @extend %back;
    width: 900px;
    margin-right: 10px;
  }

  .detail-card {
    @extend %back;
    width: 400px;
    height: 455px;

    .input-group {
      margin-bottom: 10px;
    }

    .btn-rel {
      position: relative;
    }

    .btn-custom {
      border: 0;
      background: none;
      padding: 2px 5px;
      margin-top: 2px;
      position: relative;
      left: -28px;
      margin-bottom: 0;
      border-radius: 3px;
    }

    .passbook-table {
      height: 385px;
      overflow: hidden;
      position: relative;
      padding: 5px;
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 0 2px #5b9dff;
    }

    .no-passbook-table {
      height: 385px;
      overflow: hidden;
      position: relative;

      .no-passbook {
        width: 60%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        opacity: 0.3;
      }
    }

    .header-center {
      .remark,
      .amount,
      .bank_amount {
        text-align: center !important;
      }
    }

    .remark {
      width: 160px;
      max-width: 160px;
      min-width: 160px;
    }

    .amount {
      width: 90px;
      max-width: 90px;
      min-width: 90px;
      text-align: end;
    }

    .bank_amount {
      width: 100px;
      min-width: 100px;
      max-width: 100px;
      text-align: end;
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
      background: #dae9ff;
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
      box-shadow: 0 0 5px black;

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

  @media screen and (max-width: 1023px) {
    display: block;

    %back {
      margin: 5px;
    }

    .accound-card,
    .repayment-card,
    .detail-card {
      width: 97%;
    }

    .charts-modal {
      .repayment-detail-item {
        .row1 {
          padding-left: 0px;
        }
      }
    }

    .detail-card {
      .remark {
        width: 135px;
        min-width: 135px;
        max-width: 135px;
      }
    }
  }
}
</style>
<template>
  <div>
    <div
      class="invest-modal modal"
      ref="investModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalLabel"
      aria-hidden="true"
      data-backdrop="static"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <button
              type="button"
              class="btn btn-close"
              style="color: #ffb100;"
              data-dismiss="modal"
            >
              <i class="far fa-times-circle"></i>
            </button>
            <div class="filter-space">
              <select class="form-control float-left" v-model="orderBy">
                <option value="loan_date">投資時間</option>
                <option value="principal">本金餘額</option>
                <option value="remaining_period">剩餘期數</option>
                <option value="interest_rate">年利率</option>
                <option value="credit_level">信評</option>
              </select>
              <button class="btn btn-secondary btn-sm float-left" @click="showMore = !showMore">
                <span v-if="showMore">
                  <i class="fas fa-search-minus"></i>精簡
                </span>
                <span v-else>
                  <i class="fas fa-search-plus"></i>完整
                </span>
              </button>
            </div>
            <div class="invert-deatil-container" v-if="investData.length !==0">
              <div class="invert-deatil-card" v-for="(item,index) in investData" :key="index">
                <div class="card-title">
                  <img :src="'./Image/icon_step_ok_blue.svg'" />
                  <span>{{item.target.product_name}} - {{category}}</span>
                </div>
                <div>
                  <span>{{item.target.target_no}}</span>
                  <span
                    class="float-right"
                    :style="{color:levelColor[item.target.credit_level]}"
                  >{{item.target.credit_level}}級</span>
                </div>
                <div
                  class="normal-color"
                  :style="{color:getColor(item.target.delay_days,item.next_repayment.date)}"
                >{{getText(item)}}</div>
                <transition name="fade">
                  <div class="detail-card" v-if="showMore">
                    <div class="label-color">最近一期應收本息（{{item.next_repayment.date}}）</div>
                    <span
                      :style="{color:getColor(item.target.delay_days,item.next_repayment.date)}"
                    >{{format(item.next_repayment.amount)}}元</span>
                    <div style="display:flex">
                      <div class="card-col">
                        <p class="label-color">預期收益總額</p>
                        <span>{{getTotal(item)}}元</span>
                      </div>
                      <div class="card-col">
                        <p class="label-color">本金餘額</p>
                        <span>{{format(item.accounts_receivable.principal)}}元</span>
                      </div>
                      <div class="card-col">
                        <p class="label-color">帳期</p>
                        <span>{{schedule_(item)}}</span>
                      </div>
                    </div>
                  </div>
                </transition>
                <button
                  class="btn btn-showdetail btn-sm"
                  v-if="$props.showDetailBtn"
                  @click="send(item.id)"
                >查看詳情</button>
                <div style="height:38px">
                  <div class="transfer" v-if="item.transfer_status == 1">
                    <img
                      :src="item.isLocked ? `./Image/icon_lock.svg` : `./Image/icon_unlock.svg`"
                      class="img-fluid"
                      style="height: 36px;"
                    />
                    <span>債權轉換申請中</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div
      class="invert-item-detail-modal modal"
      ref="detailModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalLabel"
      aria-hidden="true"
      data-backdrop="static"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <div class="tabbable-panel" v-if="Object.keys(infoData).length !== 0">
              <div class="tabbable-line">
                <ul class="nav nav-tabs" role="tablist" @click="changeTab($event)">
                  <li class="nav-item active">
                    <a href="#overview" role="tab" data-toggle="tab">投資概況</a>
                  </li>
                  <li class="nav-item">
                    <a href="#info" role="tab" data-toggle="tab">標的訊息</a>
                  </li>
                  <li class="nav-item">
                    <a href="#detail" role="tab" data-toggle="tab">回款明細</a>
                  </li>
                </ul>
                <div class="tab-content">
                  <div role="tabpanel" class="tab-pane fade active show" id="overview">
                    <div class="block">
                      <h5>{{infoData.target.product_name}}</h5>
                      <div>
                        {{infoData.target.target_no}}
                        <span
                          :style="{color:levelColor[infoData.target.credit_level]}"
                        >{{infoData.target.credit_level}}級</span>
                      </div>
                      <hr />
                      <div>
                        <p class="total-re">累積回款總額</p>
                        <p
                          class="total-val"
                          :style="{color:infoData.target.delay_days > 0 ? '#ff5a5a' :'#5844ff'}"
                        >
                          <span>{{totalInstalment(infoData.amortization_schedule.list)}}</span>$
                        </p>
                      </div>
                      <div>
                        <span class="float-left">債權總額</span>
                        <span
                          class="float-right"
                          :style="{color:infoData.target.delay_days > 0 ? '#ff5a5a' :'#5844ff'}"
                        >{{format(infoData.amortization_schedule.total_payment)}}元</span>
                      </div>
                      <div>
                        <span class="float-left">投資本金</span>
                        <span
                          class="float-right"
                          style="color:#5844ff"
                        >{{format(infoData.loan_amount)}}元</span>
                      </div>
                      <div>
                        <span class="float-left">預期收益總額</span>
                        <span
                          class="float-right"
                          :style="{color:infoData.target.delay_days > 0 ? '#ff5a5a' :'#5844ff'}"
                        >{{format(infoData.amortization_schedule.total_payment - infoData.amortization_schedule.amount)}}元</span>
                      </div>
                      <hr />
                      <p
                        :style="{color:infoData.target.delay_days > 0 ? '#ff5a5a' :'#5844ff'}"
                      >{{infoData.target.delay ==0 ? `還款中` : `案件逾期中 - ${infoData.target.delay_days}天`}}</p>
                      <div>
                        <span class="float-left">回款方式</span>
                        <span
                          class="float-right"
                          style="color:#5844ff"
                        >{{returnTypeText(infoData.target.repayment)}}</span>
                      </div>
                      <div>
                        <span class="float-left">帳期</span>
                        <span
                          class="float-right"
                          style="color:#5844ff"
                        >{{schedule(infoData.target,infoData.amortization_schedule)}}</span>
                      </div>
                      <div>
                        <span class="float-left">應收本金</span>
                        <span
                          class="float-right"
                          :style="{color:infoData.target.delay_days > 0 ? '#ff5a5a' :'#5844ff'}"
                        >{{format(infoData.amortization_schedule.remaining_principal)}}元</span>
                      </div>
                      <div>
                        <span class="float-left">應收收益</span>
                        <span
                          class="float-right"
                          :style="{color:infoData.target.delay_days > 0 ? '#ff5a5a' :'#5844ff'}"
                        >{{format(infoData.amortization_schedule.total_payment - infoData.amortization_schedule.amount)}}元</span>
                      </div>
                      <div>
                        <span class="float-left">投資日期</span>
                        <span
                          class="float-right"
                          style="color:#5844ff"
                        >{{dateToStr(parseInt(`${infoData.created_at}000`))}}</span>
                      </div>
                    </div>
                    <div class="block" v-if="infoData.transfer_status == 1">
                      <p class="sub-title">債權轉讓申請中</p>
                      <div>
                        <span class="float-left">申請日期</span>
                        <span class="float-right">{{infoData.transfer.transfer_at}}</span>
                      </div>
                      <div>
                        <span class="float-left">價金</span>
                        <span
                          class="float-right"
                          style="color:#5844ff"
                        >{{format(infoData.transfer.principal)}}元</span>
                      </div>
                    </div>
                    <div
                      class="block contract"
                      v-if="infoData.transfer_status == 1"
                      @click="showContract(infoData.transfer.contract,`債權讓與契約書`)"
                    >債權讓與契約書 ></div>
                  </div>
                  <div role="tabpanel" class="tab-pane fade" id="info">
                    <div class="block">
                      <p class="sub-title">借款人基本資料</p>
                      <div>
                        <span class="float-left">借款人</span>
                        <span
                          class="float-right"
                        >{{infoData.target.user ? infoData.target.user.name : ''}}</span>
                      </div>
                      <div>
                        <span class="float-left">年齡</span>
                        <span
                          class="float-right"
                        >{{infoData.target.user ? infoData.target.user.age : ''}}歲</span>
                      </div>
                      <div>
                        <span class="float-left">性別</span>
                        <span
                          class="float-right"
                        >{{infoData.target.user ? infoData.target.user.sex === "F" ? "女" : "男" : ''}}</span>
                      </div>
                      <div>
                        <span class="float-left">在學學校/畢業學校</span>
                        <span
                          class="float-right"
                        >{{infoData.target.user ? infoData.target.user.company_name : ''}}</span>
                      </div>
                      <div>
                        <span class="float-left">身分證</span>
                        <span
                          class="float-right"
                        >{{infoData.target.user ? infoData.target.user.id_number : ''}}</span>
                      </div>
                      <div>
                        <span class="float-left">信用評等</span>
                        <span
                          class="float-right"
                          :style="{color:levelColor[infoData.target.credit_level]}"
                        >{{infoData.target.credit_level}}級</span>
                      </div>
                      <hr />
                      <p class="sub-title">借款標的</p>
                      <div>
                        <span class="float-left">預計年化利率</span>
                        <span class="float-right" style="#ff5a5a">{{infoData.target.interest_rate}}%</span>
                      </div>
                      <div>
                        <span class="float-left">借款期間</span>
                        <span class="float-right">{{infoData.target.instalment}}期</span>
                      </div>
                      <div>
                        <span class="float-left">借款金額</span>
                        <span class="float-right">{{format(infoData.target.loan_amount)}}元</span>
                      </div>
                      <div>
                        借款原因
                        <br />
                        <p>{{infoData.target.reason}}</p>
                      </div>
                      <div v-if="infoData.target.remark">
                        備註
                        <br />
                        <p>{{infoData.target.remark}}</p>
                      </div>
                    </div>
                    <div
                      class="block contract"
                      @click="showContract(infoData.contract,`借貸合約`)"
                    >借貸合約 ></div>
                    <div
                      class="block contract"
                      v-if="infoData.transfer_status == 1"
                      @click="showContract(infoData.transfer.contract,`債權讓與契約`)"
                    >債權讓與契約 ></div>
                  </div>
                  <div
                    role="tabpanel"
                    class="tab-pane fade"
                    id="detail"
                    style="overflow: scroll;height: 500px;padding: 5px;"
                  >
                    <div
                      class="invest-detail-row"
                      v-for="(row,index) in infoData.amortization_schedule.list"
                      :key="index"
                    >
                      <div class="col1">
                        <p>{{row.repayment_date}}</p>
                        <span>{{getInstalment(row.instalment,infoData.target)}}</span>
                      </div>
                      <div class="col2">
                        <p
                          :style="{color:row.delay_interest > 0 ? '#ff5a5a' :'#5844ff'}"
                        >{{format(row.total_payment)}}元</p>
                        <span v-if="row.delay_interest > 0">逾期清償</span>
                        <span v-else>
                          含利息
                          <span style="color:#004eff">${{format(row.interest)}}</span>
                        </span>
                      </div>
                      <div
                        class="col3"
                        v-html="ckeckStatus(row.repayment,row.delay_interest,row.total_payment)"
                      ></div>
                    </div>
                    {{row}}
                  </div>
                </div>
              </div>
            </div>
            <button
              class="btn btn-dark"
              style="width:100%"
              @click="closeModal($refs.detailModal)"
            >確認</button>
          </div>
        </div>
      </div>
    </div>

    <div
      class="contract-modal modal fade"
      ref="contractModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalLabel"
      aria-hidden="true"
      data-backdrop="static"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <button type="button" class="btn btn-close" @click="closeModal($refs.contractModal)">
              <i class="far fa-times-circle"></i>
            </button>
            <h3>{{contractTitle}}</h3>
            <div v-html="contract.replace(/\r\n|\n/g,`<br>`)"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["detailData", "category", "investCaseInfo", "showDetailBtn"],
  data: () => ({
    orderBy: "loan_date",
    contractTitle: "",
    contract: "",
    showMore: true,
    investData: [],
    infoData: {},
    levelColor: {
      "1": "#a4d040",
      "2": "#83bb39",
      "3": "#6dbea2",
      "4": "#77afde",
      "5": "#688edc",
      "6": "#4569c3",
      "7": "#f0cb43",
      "8": "#e1aa41",
      "9": "#e17528",
      "10": "#e17528",
      "11": "#df6362",
      "12": "#ce2f1c",
      "13": "#b22821"
    }
  }),
  watch: {
    "$props.detailData"(newData) {
      this.investData = newData;
    },
    "$props.investCaseInfo"(newData) {
      this.infoData = newData;
    },
    orderBy(newVal) {
      let target = ["credit_level", "interest_rate", "loan_date"];
      let accounts_receivable = ["principal"];

      this.investData = this.investData.sort(function(a, b) {
        if (target.indexOf(newVal) !== -1) {
          if (newVal === "credit_level") {
            return a.target[newVal] > b.target[newVal] ? 1 : -1;
          } else {
            return a.target[newVal] < b.target[newVal] ? 1 : -1;
          }
        } else if (accounts_receivable.indexOf(newVal) !== -1) {
          return a.accounts_receivable[newVal] < b.accounts_receivable[newVal]
            ? 1
            : -1;
        } else {
          let aLeft = a.target.instalment - a.next_repayment.instalment;
          let bLeft = b.target.instalment - b.next_repayment.instalment;
          return aLeft < bLeft ? 1 : -1;
        }
      });
    }
  },
  methods: {
    format(data) {
      let l10nEN = new Intl.NumberFormat("en-US");
      return l10nEN.format(data.toFixed(0));
    },
    returnTypeText(data) {
      let methodText = {
        "1": "等額本息",
        "2": "先息後本",
        "3": "以日計息"
      };

      return methodText[data];
    },
    dateToStr(date) {
      let dateObj = new Date(date);

      let date_item = {
        year: dateObj.getFullYear(),
        month:
          (dateObj.getMonth() + 1 < 10 ? "0" : "") + (dateObj.getMonth() + 1),
        day: (dateObj.getDate() < 10 ? "0" : "") + dateObj.getDate(),
        hour: (dateObj.getHours() < 10 ? "0" : "") + dateObj.getHours(),
        min: (dateObj.getMinutes() < 10 ? "0" : "") + dateObj.getMinutes(),
        sec: (dateObj.getSeconds() < 10 ? "0" : "") + dateObj.getSeconds()
      };

      return `${date_item.year}/${date_item.month}/${date_item.day} ${date_item.hour}:${date_item.min}`;
    },
    totalInstalment(list) {
      let totalInstalment = 0;
      $.each(list, (index, row) => {
        totalInstalment += row.repayment;
      });

      return this.format(totalInstalment);
    },
    getInstalment(instalment, target) {
      return target.repayment === 3
        ? `第${instalment}/${target.instalment}天`
        : `第${instalment}期`;
    },
    schedule_(item) {
      if (item.target.repayment === 3) {
        let _financingDate =
          Math.ceil(
            (new Date().getTime() - new Date(item.target.loan_date).getTime()) /
              1000 /
              60 /
              60 /
              24
          ) + 1;
        return `${_financingDate}/${item.target.instalment}天`;
      } else
        return `${item.next_repayment.instalment}/${item.target.instalment}期`;
    },
    schedule(target, amortization_schedule) {
      let _currentInstalment = 0;
      for (let key in amortization_schedule.list) {
        if (
          amortization_schedule.list[key].repayment > 0 &&
          _currentInstalment < key
        ) {
          _currentInstalment = key;
        }
      }

      if (target.repayment == 3) {
        let _financingDate =
          Math.ceil(
            (new Date().getTime() - new Date(target.loan_date).getTime()) /
              1000 /
              60 /
              60 /
              24
          ) + 1;
        return `${_financingDate}/${target.instalment}天`;
      } else {
        return _currentInstalment <= 0
          ? "首期"
          : `${_currentInstalment}/${target.instalment}期`;
      }
    },
    ckeckStatus(repayment, delay_interest, total_payment) {
      if (0 == repayment) {
        if (0 == delay_interest) {
          return `<span style="color:#4A90E2;">待還款</span>`;
        } else {
          return `<span style="color:#FF575F;">逾期清償</span>`;
        }
      } else if (repayment === total_payment) {
        if (0 == delay_interest) {
          return `<span style="color:#7ED321;">已還款</span>`;
        } else {
          return `<span style="color:#4A4A4A;">逾期清償</span>`;
        }
      }
      return "";
    },
    getTotal(item) {
      return this.format(
        item.accounts_receivable.interest + item.accounts_receivable.principal
      );
    },
    getColor(delayDays, date) {
      if (delayDays == 0) {
        var _now = new Date();
        if (_now === new Date(date)) {
          return "#E38A2E";
        }
        return "#7ED321";
      } else if (delayDays <= 7) {
        return "#E38A2E";
      } else {
        return "#FF575F";
      }
    },
    getText(item) {
      if (item.status == 3) {
        if (item.target.sub_status == 1) {
          return `債務人產品轉換申請中 - 已逾期${item.target.delay_days}天`;
        } else if (item.target.sub_status == 3) {
          return `提前清償申請中`;
        } else {
          if (item.target.delay == "0") {
            var _now = new Date();
            if (_now === new Date(item.next_repayment.date)) {
              return `本期待還款`;
            }
            return `正常回款中`;
          } else if (item.target.delay_days <= 7) {
            return `寬限期待回款中(已逾${item.target.delay_days}天)`;
          } else {
            return `案件逾期${item.target.delay_days}天${
              item.target.collectionType ? item.target.collectionType : ""
            }`;
          }
        }
      } else if (item.status == 10) {
        if (item.target.sub_status == 2) {
          return `產品轉換完成`;
        } else if (item.target.sub_status == 4) {
          return `提前清償`;
        } else {
          return `正常結案`;
        }
      }
    },
    send(id) {
      this.$emit("sendInfo", id);
    },
    changeTab($event) {
      $("li.nav-item").removeClass("active");
      $($event.target)
        .parent("li")
        .addClass("active");
    },
    showContract(contract, title) {
      this.contractTitle = title;
      this.contract = contract;
      $(this.$refs.contractModal).modal("show");
    },
    closeModal($el) {
      $($el).modal("hide");

      setTimeout(() => {
        $("body").addClass("modal-open");
      }, 500);
    }
  }
};
</script>

<style lang="scss">
.invest-modal {
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.5s;
  }
  .fade-enter,
  .fade-leave-to {
    opacity: 0;
  }

  .modal-content {
    background: #feffd1;
    border: 2px solid rgb(255, 239, 129);

    .filter-space {
      overflow: auto;
      margin-bottom: 10px;

      select {
        width: fit-content !important;
        padding: 0px !important;
        height: 30px !important;
      }

      button {
        margin-left: 15px;
      }
    }

    .invert-deatil-container {
      overflow: auto;

      .invert-deatil-card {
        padding: 10px;
        margin: 10px;
        border-radius: 17px;
        box-shadow: 0 0 7px #315eff;
        background: #ffffff;
        float: left;
        width: 23%;

        .card-title {
          color: #000fb1;
          font-weight: bolder;
          font-size: 18px;
        }

        .detail-card {
          border-radius: 5px;
          background: #f4ffd3;
          padding: 5px;
          box-shadow: 0 0 4px #ada000;
          margin: 10px 5px 0px 5px;

          .label-color {
            color: #757575;
          }

          .card-col {
            margin-right: 8px;

            p {
              margin-bottom: 5px;
            }

            span {
              color: #2151ff;
            }
          }
        }

        .transfer {
          margin-top: 5px;
          border-top: 1px solid #b3b3b3;
          text-align: center;

          span {
            line-height: 36px;
            color: #696969;
          }
        }

        .btn-showdetail {
          $color: #4970ff;

          width: 100%;
          border: 2px solid $color;
          border-radius: 19px;
          color: $color;
          font-weight: bolder;
          margin-top: 10px;

          &:hover {
            background: $color;
            color: #ffffff;
          }
        }
      }

      @media screen and(max-width:1023px) {
        .invert-deatil-card {
          width: 46%;
        }
      }

      @media screen and(max-width:767px) {
        .invert-deatil-card {
          width: 94%;
        }
      }
    }
  }

  @media (min-width: 576px) {
    .modal-dialog {
      max-width: 90%;
      margin: 1.75rem auto;
    }
  }
}

.invert-item-detail-modal {
  .tabbable-panel {
    padding: 10px;
  }

  .tabbable-line {
    .nav-tabs {
      border: none;
      margin: 0px;

      .nav-item {
        margin-right: 2px;
        width: 32%;
        text-align: center;

        a {
          border: 0;
          margin-right: 0;
          color: #737373;

          &:hover {
            text-decoration: none;
          }

          i {
            color: #a6a6a6;
          }
        }

        &.open,
        &:hover {
          border-bottom: 4px solid #fbcdcf;

          a {
            border: 0;
            background: none !important;
            color: #333333;

            i {
              color: #a6a6a6;
            }
          }
        }

        &.active {
          border-bottom: 4px solid #f3565d;
          position: relative;

          a {
            border: 0;
            color: #333333;

            i {
              color: #404040;
            }
          }
        }
      }
    }

    .tab-content {
      margin-top: -3px;
      background-color: #fff;
      border: 0;
      border-top: 1px solid #eee;
      padding: 15px 0;
    }
  }

  .contract {
    cursor: pointer;
  }

  .block {
    padding: 10px;
    border-radius: 14px;
    box-shadow: 0 0 6px #310bff;
    margin-bottom: 9px;
    background: #f3f4ff;

    div {
      overflow: auto;
    }

    .sub-title {
      color: #000e42;
      font-weight: bolder;
    }
  }

  .invest-detail-row {
    display: flex;
    border-radius: 5px;
    box-shadow: 0 0 4px #888888;
    padding: 5px;
    background: #f3f3f3;
    margin-bottom: 10px;

    div {
      padding: 0px 15px;
    }

    .col1,
    .col2 {
      width: 100%;
    }

    .col2 {
      text-align: end;
    }

    .col3 {
      width: 60%;
      text-align: center;
      line-height: 64px;
    }

    @media screen and(max-width:767px) {
      div {
        padding: 0px 5px;
      }

      .col2 {
        width: 70%;
      }
    }
  }

  h5 {
    color: #0e0080;
    font-weight: bolder;
  }

  .total-re {
    text-align: center;
    color: #005094;
  }

  .total-val {
    text-align: center;
    padding: 0px;

    span {
      font-size: 40px;
    }
  }

  .btn-close {
    position: absolute;
    top: -9px;
    right: -7px;
    font-size: 27px;
  }
}
</style>
<template>
  <div class="membercentre-wrapper">
    <div class="loan-header">
      <userInfo :userData="userData"></userInfo>
      <div class="menu-card">
        <div style="width: max-content;overflow: hidden;">
          <router-link class="menu-item" to="loannotification">
            <div class="img">
              <img src="../asset/images/icon_notification.svg" class="img-fluid" />
              <span v-if="unreadCount !== 0">{{unreadCount}}</span>
            </div>
            <p>通知</p>
          </router-link>
          <router-link class="menu-item" to="myrepayment">
            <div class="img">
              <img src="../asset/images/icon_account.svg" class="img-fluid" />
            </div>
            <p>帳戶資訊</p>
          </router-link>
        </div>
      </div>
    </div>
    <div class="member-menu">
      <div class="borrow-card">
        <div class="borrow-box">
          <div class="detial-row">
            <div>
              <span>{{ format(tweenedFrozen) }}$</span>
              <label>提領待放款金額</label>
            </div>
          </div>
          <div class="detial-row">
            <div>
              <span>{{ format(tweenedPrIncipal) }}$</span>
              <label>現欠本金餘額</label>
            </div>
          </div>
          <div class="detial-row">
            <div>
              <span>{{ format(tweenedRepayment) }}$</span>
              <label>本期({{repaymentDate}})待還本息</label>
            </div>
          </div>
        </div>
        <div class="balance-row">
          <label>可用餘額</label>
          <span style="font-size: 20px;">{{ format(tweenedFunds) }}$</span>
        </div>
      </div>
      <div class="financial-card">
        <div class="account-card">
          <h4>專屬還款帳號</h4>
          <div class="repay-account">
            <p
              class="des"
              v-if="Object.keys(myRepayment).length !==0"
            >({{myRepayment.virtual_account.bank_code}}){{myRepayment.virtual_account.bank_name}}</p>
            <p
              class="des"
              v-if="Object.keys(myRepayment).length !==0"
            >({{myRepayment.virtual_account.branch_code}}){{myRepayment.virtual_account.branch_name}}</p>
            <p
              class="des"
              v-if="Object.keys(myRepayment).length !==0"
            >{{myRepayment.virtual_account.virtual_account}}</p>
          </div>
        </div>
        <div class="repay-s">
          <p class="sm">
            <strong>2020-09-11</strong>
            <span class="yellow">次還款日</span>
          </p>
          <p class="sm">
            <b>
              <strong>10000</strong>元
            </b>
            <span class="yellow">請於還款日前匯入</span>
          </p>
        </div>
        <p class="legend">
          親愛的用戶您好：
          <br />還款請以約定金融卡轉帳至以下專屬帳戶內，中午12點後為隔日帳，為避免銀行作業影響普匯入帳時間及計息天數，請儘早匯款。
        </p>
      </div>
    </div>
    <div class="main-content">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
import userInfo from "../component/userInfoComponent";

export default {
  beforeRouteEnter(to, from, next) {
    if (sessionStorage.length === 0 || sessionStorage.flag === "logout") {
      next("/index");
    } else {
      next();
    }
  },
  components: {
    userInfo,
  },
  data: () => ({
    myRepayment: {},
    userData: JSON.parse(sessionStorage.getItem("userData")),
    repaymentDate: "",
    funds: 0,
    frozen: 0,
    principal: 0,
    repaymentAmount: 0,
    tweenedFunds: 0,
    tweenedFrozen: 0,
    tweenedPrIncipal: 0,
    tweenedRepayment: 0,
    unreadCount: 0,
  }),
  computed: {
    regards() {
      let time = parseInt(new Date().getHours());
      let text = "";
      if (0 <= time && time < 5) {
        text = "晚安";
      } else if (5 <= time && time < 11) {
        text = "早安";
      } else if (11 <= time && time < 17) {
        text = "午安";
      } else if (17 <= time && time < 23) {
        text = "晚安";
      }
      return `${text} ${this.userName}`;
    },
  },
  created() {
    this.$store.dispatch("getRepaymentList");
    this.$router.push("/loannotification");

    this.getMyRepayment();

    $("title").text(`借款專區 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {});
  },
  watch: {
    funds(newValue) {
      gsap.to(this.$data, { duration: 1, tweenedFunds: newValue });
    },
    frozen(newValue) {
      gsap.to(this.$data, { duration: 1, tweenedFrozen: newValue });
    },
    principal(newValue) {
      gsap.to(this.$data, { duration: 1, tweenedPrIncipal: newValue });
    },
    repaymentAmount(newValue) {
      gsap.to(this.$data, { duration: 1, tweenedRepayment: newValue });
    },
  },
  methods: {
    format(data) {
      let l10nEN = new Intl.NumberFormat("en-US");
      return l10nEN.format(data.toFixed(0));
    },
    getMyRepayment() {
      axios
        .get("getMyRepayment")
        .then((res) => {
          this.myRepayment = res.data.data;
          this.funds = res.data.data.funds.total;
          this.frozen = res.data.data.funds.frozen;
          this.principal = res.data.data.accounts_payable.principal;
          this.repaymentAmount = res.data.data.next_repayment.amount;
          this.repaymentDate = res.data.data.next_repayment.date;
        })
        .catch((error) => {
          console.error("getMyRepayment 發生錯誤，請稍後再試");
        });
    },
  },
};
</script>

<style lang="scss">
.membercentre-wrapper {
  margin-top: 84px;
  background-color: #f5f5f5;

  .loan-header {
    background-image: url("../asset/images/loan_bg.png");
    background-position: 50% 50%;
    background-repeat: no-repeat;
    background-size: 100% 100%;
    padding: 25px 10%;
    display: flex;
    justify-content: space-between;

    .info-card {
      display: flex;

      .picture {
        border-radius: 50%;
        overflow: hidden;
        height: 100px;
        width: 100px;
      }

      .userInfo {
        display: grid;
        padding-left: 20px;
        color: #ffffff;

        p {
          line-height: 50px;
          margin: 0px;
          font-weight: bolder;
          font-size: 20px;
        }

        span {
          line-height: 50px;
          margin: 0px;
          font-weight: bolder;
        }
      }
    }

    .menu-card {
      border: none;
      overflow: auto;
      max-width: 850px;

      .menu-item {
        cursor: pointer;
        float: left;
        text-align: center;
        margin: 10px 5px;
        color: white;
        width: 70px;

        &:hover {
          text-decoration: none;
        }

        .img {
          width: 30px;
          height: 30px;
          position: relative;
          margin: 5px auto;

          img {
            width: 55px;
          }

          span {
            position: absolute;
            top: -13px;
            right: 5px;
            background: #08deb1;
            border-radius: 50%;
            width: 17px;
            font-size: 10px;
            font-weight: initial;
          }
        }

        p {
          margin-bottom: 0px;
        }
      }
    }
  }

  .member-menu {
    width: 73%;
    margin: 0px auto;
    display: flex;
    padding: 25px;

    .borrow-card {
      width: calc(50% - 20px);
      margin: 20px 10px;
      border-radius: 20px;
      box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);
      background-color: #ffffff;
      position: relative;

      .borrow-box {
        overflow: hidden;
        padding: 3rem 2rem;

        .detial-row {
          width: 50%;
          height: 82px;
          float: left;
          text-align: center;
          padding: 10px;
          font-weight: bold;
          position: relative;

          &:first-of-type {
            border-right: 1px dashed #00000029;
            border-bottom: 1px dashed #00000029;
            border-width: medium;
          }

          &:nth-of-type(2) {
            border-bottom: 1px dashed #00000029;
            border-width: medium;
          }

          &:nth-of-type(3) {
            border-right: 1px dashed #00000029;
            border-width: medium;
          }

          div {
            display: flex;
            flex-direction: column;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: fit-content;
          }
        }
      }

      .balance-row {
        border-radius: 4.5px;
        background-image: linear-gradient(to bottom, #00aeff, #00d9d5);
        color: #ffffff;
        font-weight: bold;
        position: absolute;
        left: 50%;
        bottom: 0%;
        transform: translate(-50%, 50%);
        width: 85%;
        text-align: center;
        padding: 10px;

        label {
          margin-bottom: 0px;
          margin-right: 10px;
        }
      }
    }

    .financial-card {
      width: calc(50% - 20px);
      margin: 20px 10px;
      background-image: url("../asset/images/myloan_bank.png");
      background-position: 50% 50%;
      background-repeat: no-repeat;
      background-size: 100% 100%;
      padding: 20px;
      color: #ffffff;

      .account-card {
        width: 62%;
        margin: 0px auto;
        .repay-account {
          overflow: hidden;
          margin-bottom: 10px;

          .des {
            width: 50%;
            float: left;
            font-size: 14px;
            margin-bottom: 5px;
          }
        }
      }

      .repay-s {
        width: 62%;
        display: flex;
        margin: 0px auto;
        .sm {
          width: 50%;
          display: flex;
          flex-direction: column;
          margin: 0px;

          &:first-of-type {
            border-right: 1px dashed #ffffff;
            padding-right: 15px;
          }

          &:last-of-type {
            padding-left: 15px;
          }

          .yellow {
            color: #fbd900;
          }
        }
      }

      .legend {
        font-size: 7px;
        margin: 10px auto;
      }
    }
  }

  .statement-card {
    border-radius: 20px;
    box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);
    background-color: #ffffff;
    width: 33%;
    margin-left: 20px;
    padding: 10px;

    .s-title {
      background-color: #083a6e;
      color: #ffffff;
    }
  }
}

.loan-notification{
  .pagination {
    width: fit-content;
    margin: 0px auto;

    .paginationjs-pages {
      li {
        border: 0px solid #aaa;

        a {
          color: #083a6e;
          background: #f5f5f5;
        }
      }
      .paginationjs-prev,
      .paginationjs-next,
      .paginationjs-page.active {
        a {
          background: #083a6e;
          color: #ffffff;
        }
      }
    }
  }}

@media screen and (max-width: 767px) {
  .membercentre-wrapper {
    .loan-header {
      background-size: cover;
      flex-direction: column;
      padding: 10px;

      .info-card {
        width: fit-content;
        margin: 0px auto;

        .picture {
          width: 90px;
          height: 90px;
        }
      }

      .menu-card {
        max-width: fit-content;
        margin: 0px auto;
      }
    }

    .member-menu {
      width: 100%;
      flex-direction: column;
      padding: 10px;

      .borrow-card {
        width: calc(100% - 20px);
        margin: 10px;
      }

      .financial-card {
        width: calc(100% - 20px);
        margin: 25px auto 0px auto;
        padding: 10px;
        overflow: hidden;

        .account-card {
          width: 95%;
          margin-left: 4rem;
          h3 {
            font-size: 20px;
            margin-bottom: 5px;
          }

          .repay-account {
            margin-bottom: 5px;
          }
        }

        .repay-s {
          width: 100%;
        }

        .legend {
          margin: 7px 0px 0px 0px;
        }
      }
    }
  }
}
</style>
<template>
  <div class="membercentre-wrapper">
    <div class="member-menu">
      <div class="info-card">
        <div class="picture">
          <img :src="userPic" class="img-fluid" />
        </div>
        <div class="userInfo">
          <p>{{regards}}</p>
          <span>我的使用者編號：{{userId}}</span>
        </div>
      </div>
      <div class="borrow-card">
        <div class="detial-row">
          <label>虛擬帳戶餘額</label>
          <span>{{ format(tweenedFunds) }}$</span>
        </div>
        <div class="detial-row">
          <label>提領待放款金額</label>
          <span>{{ format(tweenedFrozen) }}$</span>
        </div>
        <div class="detial-row">
          <label>現欠本金餘額</label>
          <span>{{ format(tweenedprIncipal) }}$</span>
        </div>
        <div class="detial-row">
          <label>本期({{repaymentDate}})待還本息</label>
          <span>{{ format(tweenedrePayment) }}$</span>
        </div>
      </div>
      <div class="menu-card">
        <router-link class="menu-item" to="/notification">
          <img :src="'./Image/icon_account.svg'" class="img-fluid" />
          <p>通知</p>
        </router-link>
        <router-link class="menu-item" to="/myrepayment">
          <img :src="'./Image/icon_moneyback.svg'" class="img-fluid" />
          <p>帳戶資訊</p>
        </router-link>
        <!-- <router-link class="menu-item" to="/repayment">
          <img :src="'./Image/icon_moneyback.svg'" class="img-fluid" />
          <p>我的還款</p>
        </router-link>
        <router-link class="menu-item" to="/detail">
          <img :src="'./Image/icon_getmoney.svg'" class="img-fluid" />
          <p>帳戶提領</p>
        </router-link>-->
      </div>
    </div>
    <div class="main-content">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
export default {
  beforeRouteEnter(to, from, next) {
    if (sessionStorage.length === 0 || sessionStorage.flag === "logout") {
      next("/index");
    } else {
      next();
    }
  },
  data: () => ({
    myRepayment: {},
    userId: "",
    userName: "",
    userPic: "",
    repaymentDate: "",
    funds: 0,
    frozen: 0,
    principal: 0,
    repaymentAmount: 0,
    tweenedFunds: 0,
    tweenedFrozen: 0,
    tweenedprIncipal: 0,
    tweenedrePayment: 0
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
    }
  },
  created() {
    this.$store.dispatch("getRepaymentList");
    this.$router.push("/notification");
    let userData = JSON.parse(sessionStorage.getItem("userData"));

    this.userId = userData.id;
    this.userName = userData.name;
    this.userPic = userData.picture ? userData.picture : "./Image/mug_shot.svg";

    this.getMyRepayment();

    $("title").text(`會員中心 - inFlux普匯金融科技`);
  },
  watch: {
    funds: function(newValue) {
      gsap.to(this.$data, { duration: 1, tweenedFunds: newValue });
    },
    frozen: function(newValue) {
      gsap.to(this.$data, { duration: 1, tweenedFrozen: newValue });
    },
    principal: function(newValue) {
      gsap.to(this.$data, { duration: 1, tweenedprIncipal: newValue });
    },
    repaymentAmount: function(newValue) {
      gsap.to(this.$data, { duration: 1, tweenedrePayment: newValue });
    }
  },
  methods: {
    format(data) {
      let l10nEN = new Intl.NumberFormat("en-US");
      return l10nEN.format(data.toFixed(0));
    },
    getMyRepayment() {
      axios
        .get("getMyRepayment")
        .then(res => {
          this.myRepayment = res.data.data;
          this.funds = res.data.data.funds.total;
          this.frozen = res.data.data.funds.frozen;
          this.principal = res.data.data.accounts_payable.principal;
          this.repaymentAmount = res.data.data.next_repayment.amount;
          this.repaymentDate = res.data.data.next_repayment.date;
        })
        .catch(error => {
          console.error("getMyRepayment 發生錯誤，請稍後再試");
        });
    }
  }
};
</script>

<style lang="scss">
.membercentre-wrapper {
  .member-menu {
    overflow: auto;
    position: relative;
    display: flex;
    box-shadow: 0px 1px 1px #a6b9ff;
    background: #edf1ff;
    padding: 0px 40px;

    %block {
      margin: 10px 0px;
      padding: 10px;
      width: fit-content;
      border-right: 1px solid #a6b9ff;
    }

    .info-card {
      @extend %block;

      .picture {
        float: left;
        width: 100px;
        border-radius: 50%;
        overflow: auto;
      }

      .userInfo {
        display: grid;
        padding-left: 20px;

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

    .borrow-card {
      $color: blue, green, red, orange;

      @extend %block;
      width: 300px;

      .detial-row {
        display: flex;
        position: relative;

        label {
          margin-bottom: 0px;
        }

        @for $i from 1 through 4 {
          &:nth-child(#{$i}) {
            span {
              position: absolute;
              right: 0;
              color: nth($color, $i);
            }
          }
        }
      }

      .detial-row:not(:last-child) {
        border-bottom: 1px solid #b7b7b7;
      }
    }

    .menu-card {
      @extend %block;
      margin: 10px 20px;
      text-align: center;
      display: flex;
      padding: 0px;
      border: none;

      .menu-item {
        cursor: pointer;
        width: 100px;
        text-align: -webkit-center;
        background: #ffffff;
        box-shadow: 0 0 2px #7d7d7d;
        border-radius: 10px;
        margin: 10px;
        color: black;

        &:hover {
          background: #b3d9ff;
          text-decoration: none;
        }

        img {
          width: 72px;
        }

        p {
          margin-bottom: 0px;
        }
      }
    }
  }

  .main-content {
    padding: 20px;
  }

  @media screen and (max-width: 1023px) {
    .member-menu {
      display: block;
      padding: 0px;

      %block {
        width: 95%;
        margin: 15px auto;
        border: 1px solid #c1c1c1;
        background: #f9f9f9;
        border-radius: 5px;
      }

      .menu-item {
        background: #e0efff !important;
        box-shadow: none !important;
      }
    }

    .main-content {
      padding: 0px;
    }
  }
}
</style>
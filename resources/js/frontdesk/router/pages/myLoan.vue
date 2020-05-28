<template>
  <div class="membercentre-wrapper">
    <div class="member-menu">
      <userInfo :userData="userData"></userInfo>
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
          <span>{{ format(tweenedPrIncipal) }}$</span>
        </div>
        <div class="detial-row">
          <label>本期({{repaymentDate}})待還本息</label>
          <span>{{ format(tweenedRepayment) }}$</span>
        </div>
      </div>
      <div class="menu-card">
        <div style="width:max-content">
          <router-link class="menu-item" to="/loannotification">
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
    </div>
    <div class="main-content">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
import userInfo from "./component/userInfoComponent";

export default {
  beforeRouteEnter(to, from, next) {
    if (sessionStorage.length === 0 || sessionStorage.flag === "logout") {
      next("/index");
    } else {
      next();
    }
  },
  components: {
    userInfo
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
    tweenedRepayment: 0
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
    this.$router.push("/loannotification");

    this.getMyRepayment();

    $("title").text(`借款專區 - inFlux普匯金融科技`);
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
@import "./scss/membercentre";

.membercentre-wrapper {
  .borrow-card {
    $color: blue, green, red, orange;

    @extend %block;
    width: 300px !important;

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

    @media screen and (max-width:1023px){
    .borrow-card{
      width:95% !important;
    }
  }
}
</style>
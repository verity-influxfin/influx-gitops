<template>
  <div class="membercentre-wrapper">
    <div class="member-menu">
      <userInfo :userData="userData"></userInfo>
      <div class="invest-card">
        <div class="detial-row">
          <label>應收帳款</label>
          <span>{{ format(tweenedPrincipal) }}$</span>
        </div>
        <div class="detial-row">
          <label>持有債權總額</label>
          <span>{{ format(tweenedReceivable) }}$</span>
        </div>
        <div class="detial-row">
          <label>可用餘額</label>
          <span>{{ format(tweenedAvailable) }}$</span>
        </div>
        <div class="detial-row">
          <label>得標/處理中</label>
          <span>{{ format(tweenedFrozen) }}$</span>
        </div>
        <div class="detial-row">
          <label>不足額待匯入</label>
          <span>{{ format(tweenedInsufficient) }}$</span>
        </div>
      </div>
      <div class="menu-card">
        <div style="width:max-content">
          <router-link class="menu-item" to="/investnotification">
            <img :src="'./Images/icon_account.svg'" class="img-fluid" />
            <p>通知</p>
          </router-link>
          <router-link class="menu-item" to="/debt">
            <img :src="'./Images/icon_moneyback.svg'" class="img-fluid" />
            <p>債權總覽</p>
          </router-link>
          <router-link class="menu-item" to="/closedcase">
            <img :src="'./Images/icon_closed.svg'" class="img-fluid" />
            <p>結案總覽</p>
          </router-link>
          <router-link class="menu-item" to="/detail">
            <img :src="'./Images/icon_getmoney.svg'" class="img-fluid" />
            <p>明細</p>
          </router-link>
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
    userData: JSON.parse(sessionStorage.getItem("userData")),
    principal: 0,
    tweenedPrincipal: 0,
    receivable: 0,
    tweenedReceivable: 0,
    available: 0,
    tweenedAvailable: 0,
    frozen: 0,
    tweenedFrozen: 0,
    insufficient: 0,
    tweenedInsufficient: 0
  }),
  computed: {
    myInvsetment() {
      return this.$store.getters.InvestAccountData;
    }
  },
  created() {
    this.$store.dispatch("getRecoveriesList");
    this.$store.dispatch("getRecoveriesFinished");
    this.$store.dispatch("getMyInvestment");

    this.$router.push("/investnotification");

    $("title").text(`投資專區 - inFlux普匯金融科技`);
  },
  watch: {
    myInvsetment() {
      this.getInvestmentData();
    },
    principal(newValue) {
      gsap.to(this.$data, { duration: 1, tweenedPrincipal: newValue });
    },
    receivable(newValue) {
      gsap.to(this.$data, { duration: 1, tweenedReceivable: newValue });
    },
    available(newValue) {
      gsap.to(this.$data, { duration: 1, tweenedAvailable: newValue });
    },
    frozen(newValue) {
      gsap.to(this.$data, { duration: 1, tweenedFrozen: newValue });
    },
    insufficient(newValue) {
      gsap.to(this.$data, { duration: 1, tweenedInsufficient: newValue });
    }
  },
  methods: {
    format(data) {
      let l10nEN = new Intl.NumberFormat("en-US");
      return l10nEN.format(data.toFixed(0));
    },
    getInvestmentData() {
      let totalFrozen = 0;
      for (let key in this.myInvsetment.funds.frozenes) {
        totalFrozen += this.myInvsetment.funds.frozenes[key];
      }

      let money = 0;
      if (
        this.myInvsetment.funds.total - totalFrozen <
        this.myInvsetment.payable
      ) {
        money =
          this.myInvsetment.payable -
          (this.myInvsetment.funds.total - totalFrozen);
      }

      this.principal =
        this.myInvsetment.accounts_receivable.principal +
        this.myInvsetment.accounts_receivable.interest +
        this.myInvsetment.accounts_receivable.delay_interest;
      this.receivable = this.myInvsetment.accounts_receivable.principal;
      this.available = this.myInvsetment.funds.total - totalFrozen;
      this.frozen = totalFrozen;
      this.insufficient = money;
    }
  }
};
</script>

<style lang="scss">
@import "./scss/membercentre";

.membercentre-wrapper {
  .invest-card {
    $color: orange, green, blue, darkgray, red;

    @extend %block;
    width: 300px !important;

    .detial-row {
      display: flex;
      position: relative;

      label {
        margin-bottom: 0px;
      }

      @for $i from 1 through 5 {
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
    .invest-card{
      width:95% !important;
    }
  }
}
</style>
<template>
  <div class="investment-wrapper">
    <div class="invest-header">
      <userInfo :userData="userData"></userInfo>
      <div class="menu-card">
        <div style="width: max-content; overflow: hidden">
          <router-link class="menu-item" to="/investnotification">
            <div class="img">
              <img
                src="../asset/images/icon_notification.svg"
                class="img-fluid"
              />
              <span v-if="unreadCount !== 0">{{ unreadCount }}</span>
            </div>
            <p>通知</p>
          </router-link>
          <router-link class="menu-item" to="/debt">
            <div class="img">
              <img src="../asset/images/icon_moneyback.svg" class="img-fluid" />
            </div>
            <p>債權總覽</p>
          </router-link>
          <router-link class="menu-item" to="/closedcase">
            <div class="img">
              <img src="../asset/images/icon_closed.svg" class="img-fluid" />
            </div>
            <p>結案總覽</p>
          </router-link>
          <router-link class="menu-item" to="/detail">
            <div class="img">
              <img src="../asset/images/icon_getmoney.svg" class="img-fluid" />
            </div>
            <p>明細</p>
          </router-link>
        </div>
      </div>
    </div>
    <div class="member-menu">
      <div class="invest-card">
        <div class="invest-box">
          <div class="detial-row">
            <div>
              <span>{{ format(tweenedPrincipal) }}$</span>
              <label>應收款項</label>
            </div>
          </div>
          <div class="detial-row">
            <div>
              <span>{{ format(tweenedReceivable) }}$</span>
              <label>持有債權總額</label>
            </div>
          </div>
          <div class="detial-row">
            <div>
              <span>{{ format(tweenedFrozen) }}$</span>
              <label>得標/處理中</label>
            </div>
          </div>
          <div class="detial-row">
            <div>
              <span>{{ format(tweenedInsufficient) }}$</span>
              <label>不足額待匯入</label>
            </div>
          </div>
        </div>

        <div class="balance-row">
          <label>可用餘額</label>
          <span>{{ format(tweenedAvailable) }}$</span>
        </div>
      </div>
      <div class="info-card" v-if="pageIcon || pageTitle || pagedesc">
        <div class="title">
          <div class="icon">
            <img :src="pageIcon" />
          </div>
          <h3>{{ pageTitle }}</h3>
        </div>
        <p>{{ pagedesc }}</p>
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
    userData: JSON.parse(sessionStorage.getItem("userData")),
    pageTitle: "",
    pageIcon: "",
    pagedesc: "",
    principal: 0,
    tweenedPrincipal: 0,
    receivable: 0,
    tweenedReceivable: 0,
    available: 0,
    tweenedAvailable: 0,
    frozen: 0,
    tweenedFrozen: 0,
    insufficient: 0,
    tweenedInsufficient: 0,
    unreadCount: 0,
  }),
  computed: {
    myInvsetment() {
      return this.$store.getters.InvestAccountData;
    },
  },
  created() {
    this.$store.dispatch("getRecoveriesList");
    this.$store.dispatch("getRecoveriesFinished");
    this.$store.dispatch("getMyInvestment");

    this.$router.push("/investnotification");

    $("title").text(`投資專區 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {});
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
    },
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
    },
  },
};
</script>

<style lang="scss">
.investment-wrapper {
  background-color: #f5f5f5;
  .invest-header {
    background-image: url("../asset/images/header_bg.png");
    background-repeat: no-repeat;
    background-size: contain;
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
        color: #157efb;
        width: 70px;

        &:hover {
          text-decoration: none;
        }

        .img {
          width: 45px;
          height: 45px;
          position: relative;
          margin: 5px auto;

          img {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
          }

          span {
            position: absolute;
            top: -13px;
            right: 15px;
            background: #083a6e;
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
    width: 85%;
    margin: 0px auto;
    display: flex;
    padding: 25px;

    .invest-card {
      width: calc(50% - 20px);
      margin: 20px 10px;
      border-radius: 25px;
      box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);
      background-image: linear-gradient(to right, #e4eeff 0%, #ffffff 100%);
      position: relative;

      .invest-box {
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
            border-right: 0.5px solid #81c3f3;
            border-bottom: 0.5px solid #81c3f3;
            border-width: medium;
          }

          &:nth-of-type(2) {
            border-bottom: 0.5px solid #81c3f3;
            border-width: medium;
          }

          &:nth-of-type(3) {
            border-right: 0.5px solid #81c3f3;
            border-width: medium;
          }

          div {
            display: flex;
            flex-direction: column;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
          }
        }
      }

      .balance-row {
        border: 2px solid #157efb;
        border-radius: 25px;
        background-image: linear-gradient(to top, #ebf5ff, #ffffff),
          linear-gradient(to bottom, #81c3f3, #157efb);
        color: #157efb;
        font-weight: bold;
        position: absolute;
        left: 50%;
        bottom: 0%;
        transform: translate(-50%, 50%);
        width: 60%;
        text-align: center;
        padding: 5px;

        label {
          margin-bottom: 0px;
          margin-right: 10px;
        }
      }
    }

    .info-card {
      width: calc(50% - 20px);
      margin: 20px 10px;
      position: relative;
      padding: 3rem;

      .title {
        display: flex;
        margin-bottom: 20px;
        color: #083a6e;

        .icon {
          margin-right: 5px;

          img {
            width: 45px;
            height: 29px;
          }
        }

        h3 {
          font-weight: bold;
        }
      }

      p {
        font-weight: bold;
      }
    }
  }

  @media screen and (max-width: 767px) {
    .invest-header {
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

      .invest-card {
        width: calc(100% - 20px);
        margin: 10px;
      }

      .info-card {
        width: calc(100% - 20px);
        padding: 25px 0px 0px 0px;
        margin: 10px;
      }
    }
  }
}
</style>
<template>
  <div class="invest-container">
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
          <router-link class="menu-item" to="/invest-report">
            <div class="img">
              <img src="../asset/images/report-icon.svg" class="img-fluid" />
            </div>
            <p>投資人報告書</p>
          </router-link>
        </div>
      </div>
    </div>
    <div class="my-invest main">
      <div class="my-invest-header">
        <div class="invest-title col">投資總覽</div>
        <div class="invest-date col-auto">日期：{{ today }}</div>
      </div>
      <div class="invest-content">
        <div class="invest-item">
          <div class="item-title">應收款項</div>
          <div class="item-info">${{ formate(tweenedAccountReceivable) }}</div>
        </div>
        <div class="invest-item">
          <div class="item-title">持有債權本金餘額</div>
          <div class="item-info">${{ formate(tweenedPrincipal) }}</div>
        </div>
        <div class="invest-item">
          <div class="item-title">得標/處理中</div>
          <div class="item-info">${{ formate(tweenedFrozen) }}</div>
        </div>
        <div class="invest-item">
          <div class="item-title">不足額待匯入</div>
          <div class="item-info">${{ formate(tweenedInsufficient) }}</div>
        </div>
        <div class=""></div>
      </div>
    </div>
    <div class="report-main" v-if="!loading && invest_report.basic_info">
      <div class="report-date">
        <button class="btn btn-excel-download" @click="downloadExcel">
          Excel下載
        </button>
        <div>製表日期 {{ invest_report.basic_info.export_date }}</div>
      </div>
      <div class="row no-gutters report-intro mx-auto">
        <p>親愛的會員您好：</p>
        <p>
          感謝您長期以來對普匯的信賴與支持，在此為您結算至
          {{ invest_report.basic_info.export_date }}
          投資績效報告，如有任何問題，
          歡迎聯繫普匯客服Line@inFluxFin，我們將竭誠為您提供貼心的服務，
          再次感謝您的愛護與支持！
        </p>
      </div>
      <div class="info-details">
        <div class="item">
          <div class="item-title">投資人</div>
          <div class="item-value">{{ invest_report.basic_info.id }}</div>
        </div>
        <div class="item">
          <div class="item-title">首筆投資</div>
          <div class="item-value">
            {{ invest_report.basic_info.first_invest_date }}
          </div>
        </div>
        <div class="item">
          <div class="item-title">投資金額</div>
          <div class="item-value">
            $
            {{ formate(invest_report.basic_info.invest_amount) }}
          </div>
        </div>
      </div>
      <div class="row no-gutters justify-content-center mt-4">
        <div class="invest-overview">
          <div class="overview-title">
            <div class="text-center">(一)資產概況</div>
            <div class="underline"></div>
          </div>
          <div class="overview-table">
            <div class="table-header">
              <div class="item">投資產品</div>
              <div class="item">上班族貸</div>
              <div class="item">學生貸</div>
              <div class="item">本金餘額</div>
            </div>
            <div class="table-row">
              <div class="table-title item">正常還款中</div>
              <div
                class="item"
                v-for="x in localInvestDescription.amount_not_delay"
                :key="x"
              >
                {{ formate(x) }}
              </div>
            </div>
            <div class="table-row gray">
              <div class="table-title item">逾期中</div>
              <div
                class="item"
                v-for="x in localInvestDescription.amount_delay"
                :key="x"
              >
                {{ formate(x) }}
              </div>
            </div>
            <div class="table-row">
              <div class="table-title item">本金餘額</div>
              <div
                class="item"
                v-for="x in localInvestDescription.total_amount"
                :key="x"
              >
                {{ formate(x) }}
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="realized-rate">
        <div class="realized-title">
          <div class="text-center">(二)已實現收益率</div>
          <div class="underline"></div>
        </div>
        <div class="realized-table">
          <div class="header-item full-income">總收益(※1)</div>
          <div class="header-sub-item item-rate">年化報酬率(※2)</div>
          <div class="test1">
            <div class="item-1 header-item b-right">
              投資年資：{{ invest_report.invest_performance.years }} (年)
            </div>
            <div class="item-2 header-item b-right">收入</div>
            <div class="item-3 header-item b-right">支出</div>
            <div class="header-sub-item">期間</div>
            <div class="header-sub-item b-right">本金均額(※3)</div>
            <div class="header-sub-item">利息收入</div>
            <div class="header-sub-item">提還利息</div>
            <div class="header-sub-item">逾期償還利息</div>
            <div class="header-sub-item">延滯息</div>
            <div class="header-sub-item b-right">補貼息</div>
            <div class="header-sub-item b-right">回款手收</div>
          </div>
          <div class="rows">
            <div
              class="one-row"
              v-for="x in local_realized_rate_of_return"
              :key="x.range_title"
            >
              <div class="table-title item">{{ x.range_title }}</div>
              <div class="item">{{ formate(x.average_principle) }}</div>
              <div class="item">{{ formate(x.interest) }}</div>
              <div class="item">{{ formate(x.prepaid_interest) }}</div>
              <div class="item">{{ formate(x.delayed_paid_interest) }}</div>
              <div class="item">{{ formate(x.delayed_interest) }}</div>
              <div class="item">{{ formate(x.allowance) }}</div>
              <div class="item">{{ formate(x.platform_fee) }}</div>
              <div class="item">{{ formate(x.total_income) }}</div>
              <div class="item">{{ x.rate_of_return }}%</div>
            </div>
          </div>
        </div>
      </div>
      <div class="row flex-column no-gutters hint mt-2">
        <div>
          ※1.總收益=(利息收入+提還利息+逾期償還利息+延滯息+補貼息)-回款手收
        </div>
        <div class="">※2.年化報酬率=當期(總收益/本金均額)/期間月數*12</div>
        <div class="">※3.本金均額=年度每日本金餘額加總/天數</div>
      </div>
      <div class="row no-gutters justify-content-center mt-2">
        <div class="wait-for-realized">
          <div class="wait-title">
            <div class="text-center">(三)待實現應收利息</div>
            <div class="underline"></div>
          </div>
          <div class="table-header">
            <div class="item">期間</div>
            <div class="item">金額</div>
          </div>
          <div v-if="local_account_payable_interest">
            <div
              class="table-row"
              v-for="x in local_account_payable_interest"
              :key="x.range_title"
            >
              <div class="table-title item">
                {{ x.range_title }}
              </div>
              <div class="item">{{ formate(x.amount) }}</div>
            </div>
          </div>
        </div>
        <div class="overdue">
          <div class="overdue-title">
            <div class="text-center">(四)逾期未收</div>
            <div class="underline"></div>
          </div>
          <div class="table-header">
            <div class="item">科目</div>
            <div class="item">金額</div>
          </div>
          <div class="table-row">
            <div class="table-title item">逾期-尚欠本息</div>
            <div class="item">
              {{
                formate(invest_report.delay_not_return.principal_and_interest)
              }}
            </div>
          </div>
          <div class="table-row">
            <div class="table-title item">逾期-尚欠延滯息</div>
            <div class="item">
              {{ formate(invest_report.delay_not_return.delay_interest) }}
            </div>
          </div>
          <div class="table-row">
            <div class="table-title item">合計</div>
            <div class="item">
              {{ formate(invest_report.delay_not_return.total) }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import userInfo from "../component/userInfoComponent";
import Axios from 'axios'
import { gsap } from 'gsap/dist/gsap'
export default {
  components: {
    userInfo,
  },
  data() {
    return {
      invest_report: {
        /*
        basic_info: {
          id: '82',
          first_invest_date: '2018/11/09',
          invest_amount: '270000',
          export_date: '2022-03-28'
        },
        assets_description: {
          STN: {
            name: '上班族貸',
            amount_not_delay: '126436',
            amount_delay: '943',
            total_amount: '137379'
          },
          FGN: {
            name: '學生貸',
            amount_not_delay: '126436',
            amount_delay: '943',
            total_amount: '137379'
          },
          total: {
            name: '本金餘額',
            amount_not_delay: '126436',
            amount_delay: '943',
            total_amount: '137379'
          }
        },
        invest_performance: {
          years: 3.4,
          prevHalf: 0,
          average_principle: 1271796,
          return_discount_without_delay: 231641,
          discount_rate_of_return: 5.36
        },
        realized_rate_of_return: [
          {
            principle_list: {},
            interest: 3271,
            prepaid_interest: 0,
            delayed_paid_interest: 0,
            delayed_interest: 0,
            allowance: 0,
            platform_fee: 138,
            total_income: 3133,
            rate_of_return: 0.9,
            average_principle: 353463,
            start_date: '2018-11',
            end_date: '2018-12',
            days: 52,
            diff_month: 2,
            range_title: '201811-201812'
          },
          {
            principle_list: {},
            interest: 34484,
            prepaid_interest: 304,
            delayed_paid_interest: 3361,
            delayed_interest: 3890,
            allowance: 365,
            platform_fee: 1894,
            total_income: 44175,
            rate_of_return: 12,
            average_principle: 368474,
            start_date: '2019-01',
            end_date: '2019-12',
            days: 365,
            diff_month: 12,
            range_title: '201901-201912'
          },
          {
            principle_list: {},
            interest: 76829,
            prepaid_interest: 2513,
            delayed_paid_interest: 1714,
            delayed_interest: 78000,
            allowance: 18429,
            platform_fee: 41956,
            total_income: 139756,
            rate_of_return: 6.5,
            average_principle: 2159440,
            start_date: '2020-01',
            end_date: '2020-12',
            days: 366,
            diff_month: 12,
            range_title: '202001-202012'
          },
          {
            principle_list: {},
            interest: 63078,
            prepaid_interest: 10,
            delayed_paid_interest: 11885,
            delayed_interest: 277000,
            allowance: 147,
            platform_fee: 13912,
            total_income: 350103,
            rate_of_return: 26.3,
            average_principle: 1329234,
            start_date: '2021-01',
            end_date: '2021-12',
            days: 365,
            diff_month: 12,
            range_title: '202101-202112'
          },
          {
            principle_list: {},
            interest: 0,
            prepaid_interest: 0,
            delayed_paid_interest: 0,
            delayed_interest: 0,
            allowance: 380,
            platform_fee: 33,
            total_income: 347,
            rate_of_return: 0.1,
            average_principle: 367632,
            start_date: '2022-01',
            end_date: '2022-02',
            days: 59,
            diff_month: 2,
            range_title: '202201-202202'
          },
          {
            principle_list: {},
            interest: 177662,
            prepaid_interest: 2827,
            delayed_paid_interest: 16960,
            delayed_interest: 358890,
            allowance: 19321,
            platform_fee: 57933,
            total_income: 537514,
            rate_of_return: 56.3,
            average_principle: 954005,
            start_date: '2018-01',
            end_date: '2022-02',
            days: 1520,
            diff_month: 50,
            range_title: '累計收益率'
          },
          {
            principle_list: {}
          }
        ],
        account_payable_interest: [
          {
            amount: 3244,
            discount_amount: 3232,
            start_date: '2019-08',
            end_date: '2019-10',
            discount_exponent: 2.3,
            range_title: '201908-201910'
          },
          {
            amount: 4186,
            discount_amount: 4178,
            start_date: '2020-11',
            end_date: '2020-12',
            discount_exponent: 1.2,
            range_title: '202011-202012'
          },
          {
            amount: 4510,
            discount_amount: 4508,
            start_date: '2021-01',
            end_date: '2021-10',
            discount_exponent: 0.3,
            range_title: '202101-202110'
          }
        ],
        delay_not_return: {
          principal_and_interest: 341897,
          delay_interest: 138462,
          total: 480359
        },
        estimate_IRR: 0.161
        */
      },
      userData: {},
      isLogin: null,
      loading: true,
      accountReceivable: 0,
      principal: 0,
      frozen: 0,
      insufficient: 0,
      tweenedAccountReceivable: 0,
      tweenedPrincipal: 0,
      tweenedFrozen: 0,
      tweenedInsufficient: 0,
    }
  },
  created() {
    this.loading = false
    this.userData = JSON.parse(sessionStorage.getItem("userData"))
    if (sessionStorage.length === 0 || sessionStorage.flag === 'logout') {
      this.$store.commit('mutationLogin')
      this.isLogin = false
    } else {
      this.$store.dispatch("getMyInvestment");
      this.getData().finally(() => {
        this.loading = false
      })
    }
  },
  computed: {
    today() {
      // 2020/01/01
      const today = new Date()
      return `${today.getFullYear()}/${(today.getMonth() + 1)
        .toString()
        .padStart(2, '0')}/${today
          .getDate()
          .toString()
          .padStart(2, '0')}`
    },
    localInvestDescription() {
      const { assets_description } = this.invest_report
      if (Object.keys(assets_description).length > 0) {
        return {
          amount_not_delay: Object.keys(assets_description).map(
            x => assets_description[x].amount_not_delay
          ),
          amount_delay: Object.keys(assets_description).map(
            x => assets_description[x].amount_delay
          ),
          total_amount: Object.keys(assets_description).map(
            x => assets_description[x].total_amount
          )
        }
      }
      return {
        amount_not_delay: [],
        amount_delay: [],
        total_amount: []
      }
    },
    local_realized_rate_of_return() {
      return this.invest_report.realized_rate_of_return.filter(x => x.range_title).map(x => {
        const startArray = x.start_date.split('-')
        const newTitle = x.range_title !== '累計收益率' ? `${startArray[0]}  ${startArray[1]}-${x.end_date.split('-')[1]} (月)` : '累計收益率'
        return {
          ...x,
          range_title: newTitle
        }
      })
    },
    local_account_payable_interest() {
      return this.invest_report.account_payable_interest.filter(x => x.range_title).map(x => {
        if (x.range_title === '合計') {
          return x
        }
        const startArray = x.start_date.split('-')
        const newTitle = `${startArray[0]}  ${startArray[1]}-${x.end_date.split('-')[1]} (月)`
        return {
          ...x,
          range_title: newTitle
        }
      })
    },
    myInvsetment() {
      return this.$store.getters.InvestAccountData;
    },
  },
  methods: {
    formate(x) {
      if (isNaN(x)) {
        return x
      }
      return parseInt(x, 10).toLocaleString()
    },
    getData() {
      return Axios.post('/getInvestReport')
        .then(({ data }) => {
          if(data.error && data.error == 2002){
            // 實名認證未通過
            alert('需通過實名認證才可查看本頁面')
            this.$router.back()
          }
          this.invest_report = data.data
        })
        .catch(err => {
          console.error(err)
        })
    },
    downloadExcel() {
      return Axios.get('/downloadInvestReport', {
        responseType: 'blob'
      }).then(response => {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a')
        link.href = url
        link.setAttribute('download', `普匯投資報告書(${this.invest_report.basic_info.id}).xlsx`)
        document.body.appendChild(link)
        link.click()
      }).catch(err => {
        alert('發生錯誤，請稍後再試')
      })
    },
    getInvestmentData() {
      let totalFrozen = 0;
      for (let key in this.myInvsetment.funds.frozenes) {
        totalFrozen += this.myInvsetment.funds.frozenes[key];
      }

      let money = 0;
      if (this.myInvsetment.funds.total - totalFrozen < this.myInvsetment.payable) {
        money = this.myInvsetment.payable - (this.myInvsetment.funds.total - totalFrozen);
      }

      this.accountReceivable =
        this.myInvsetment.accounts_receivable.principal +
        this.myInvsetment.accounts_receivable.interest +
        this.myInvsetment.accounts_receivable.delay_interest;
      this.principal = this.myInvsetment.accounts_receivable.principal;
      this.available = this.myInvsetment.funds.total - totalFrozen;
      this.frozen = totalFrozen;
      this.insufficient = money;
    }
  },
  watch: {
    myInvsetment() {
      this.getInvestmentData();
    },
    accountReceivable(newValue) {
      gsap.to(this.$data, { duration: 1, tweenedAccountReceivable: newValue });
    },
    principal(newValue) {
      gsap.to(this.$data, { duration: 1, tweenedPrincipal: newValue });
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
}
</script>
<style lang="scss">
.invest-header {
  background-image: url('../asset/images/header_bg.png');
  background-repeat: no-repeat;
  background-size: contain;
  padding: 25px 5%;
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
      min-width: 80px;
      &:hover {
        text-decoration: none;
      }
      .img {
        width: 45px;
        height: 45px;
        position: relative;
        margin: 5px auto;
        img {
          width: 100%;
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
      .header {
        text-align: center;
        font-weight: bold;
        margin-bottom: 10px;
      }
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
@media screen and (max-width: 940px) {
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
</style>
<style lang="scss" scoped>
.invest-container {
  min-height: 100vh;
}
.b-right {
  border-right: 1px solid #fff;
}
.my-invest {
  width: 940px;
  background-color: #fff;
  border-radius: 28px;
  box-shadow: 4px 4px 10px 3px rgba(0, 0, 0, 0.1);
  margin: 20px auto;
  padding: 20px 40px;
  .my-invest-header {
    display: flex;
    align-items: center;
    margin-bottom: 4px;
    padding: 8px 0;
    border-bottom: 2px solid #f3f3f3;
    .invest-title {
      left: 370px;
      font-weight: 500;
      font-size: 28px;
      line-height: 1.5;
      color: #036eb7;
    }
    .invest-date {
      font-weight: 500;
      font-size: 22px;
      line-height: 1.5;
      text-align: center;
      color: #036eb7;
    }
  }
  .invest-content {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    gap: 25px;
    margin-top: 25px;
    .invest-item {
      display: flex;
      flex-direction: column;
      justify-content: center;
      .item-title {
        padding: 4px 0;
        font-size: 20px;
        line-height: 1.5;
        text-align: center;
        color: #707070;
      }
      .item-info {
        font-size: 24px;
        line-height: 1.5;
        text-align: center;
        color: #036eb7;
      }
    }
  }
}
.report-main {
  width: 940px;
  height: 1200px;
  margin: 30px auto;
  padding: 0 20px;
  background-size: cover;
  background-image: url('../asset/images/invest-report-cover.jpg');
  line-height: 1.5;
  font-size: 14px;
  font-weight: bold;
  position: relative;
  .report-date {
    position: absolute;
    display: flex;
    flex-direction: column;
    align-items: center;
    top: 70px;
    right: 30px;
    line-height: 1.5;
    font-size: 16px;
    .btn-excel-download {
      color: #fff;
      background-color: #036eb7;
      border-radius: 6px;
    }
  }
  .report-intro {
    padding-top: 140px;
    width: 570px;
    line-height: 1.5;
    font-size: 18px;
    font-weight: bold;
  }
  .info-details {
    margin: auto;
    display: flex;
    justify-content: space-between;
    width: 535px;
    padding: 7px 40px;
    .item {
      display: flex;
      flex-direction: column;
      align-items: center;
      .item-value {
        color: #0b5ab2;
      }
    }
  }
  .invest-overview {
    .overview-title {
      margin-bottom: 15px;
    }
  }
  .invest-performance {
    .table-row:nth-child(odd) {
      background: #dbdcdc;
    }
    .performance-title {
      margin-bottom: 15px;
    }
    .table-header .item {
      width: 150px;
    }
    .table-row .item {
      width: 150px;
    }
  }
  .realized-rate {
    .realized-title {
      margin-bottom: 15px;
    }
    .realized-table {
      display: grid;
      grid-template-columns: 100px 100px 1fr 1fr 1fr 1fr 1fr 1fr 1fr 105px;
      grid-template-rows: 42px auto;
      gap: 0px 0px;
      grid-auto-flow: row;
      grid-template-areas:
        'test1 test1 test1 test1 test1 test1 test1 test1 full-income item-rate'
        'rows rows rows rows rows rows rows rows rows rows'
        'rows2 rows2 rows2 rows2 rows2 rows2 rows2 rows2 rows2 rows2';
      .header-item {
        background-color: #3b6188;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
      }
      .header-sub-item {
        background-color: #dbdcdc;
        color: #3b6188;
        display: flex;
        align-items: center;
        justify-content: center;
      }
    }

    .full-income {
      grid-area: full-income;
    }

    .item-rate {
      grid-area: item-rate;
    }

    .test1 {
      display: grid;
      grid-template-columns: 100px 100px 1fr 1fr 1fr 1fr 1fr 1fr;
      grid-template-rows: 1fr 1fr;
      gap: 0px 0px;
      grid-auto-flow: row;
      grid-template-areas:
        'item1 item1 item2 item2 item2 item2 item2 item3'
        '. . . . . . . .';
      grid-area: test1;
      .item-1 {
        grid-area: item1;
      }
      .item-2 {
        grid-area: item2;
      }
      .item-3 {
        grid-area: item3;
      }
    }

    .rows {
      grid-area: rows;
      .one-row {
        display: grid;
        grid-template-columns: 100px 100px 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
        grid-template-areas: '. . . . . . . . . .';
        .item {
          padding: 5px 0;
          text-align: center;
        }
      }
    }
    .one-row:nth-child(even) {
      background: #dbdcdc;
    }

    .rows2 {
      grid-area: rows2;
    }

    .table-header .item {
      width: 80px;
      &.item-ful {
        line-height: 52px;
      }
      &.full-income {
        width: 97px;
      }
      &.dur {
        width: 100px;
      }
      &.avg {
        width: 105px;
      }
      &.overdue-rate {
        width: 105px;
      }
      &.item-rate {
        width: 93px;
      }
    }
    .table-row .item {
      width: 86px;
      &.full-income {
        width: 97px;
      }
      &.dur {
        width: 100px;
      }
      &.avg {
        width: 105px;
      }
      &.overdue-rate {
        width: 105px;
      }
    }
    .table-row:nth-child(even) {
      background: #dbdcdc;
    }
    .double-item {
      //   display: flex;
      .item {
        width: 80px;
      }
      .item-down {
        color: #0b5ab2;
        background-color: #fff;
      }
    }
  }
  .wait-for-realized {
    margin-right: 80px;
    .wait-title {
      margin-bottom: 15px;
    }
    .table-row:nth-child(even) {
      background: #dbdcdc;
    }
    .table-header .item {
      width: 130px;
    }
    .table-row .item {
      width: 130px;
    }
  }
  .overdue {
    .overdue-title {
      margin-bottom: 15px;
    }
    .table-header .item {
      width: 140px;
    }
    .table-row .item {
      width: 140px;
    }
    .table-row:nth-child(even) {
      background: #dbdcdc;
    }
  }
  .underline {
    margin: auto;
    width: 75px;
    border-bottom: 5px solid #3b6188;
  }
  .table-header {
    background-color: #3b6188;
    color: #fff;
    display: flex;
    .item {
      width: 115px;
      padding: 5px 10px;
      text-align: center;
    }
  }
  .table-row {
    display: flex;
    &.gray {
      background-color: #dbdcdc;
    }
    .item {
      width: 115px;
      padding: 5px 10px;
      text-align: center;
    }
  }
  .table-title {
    color: #3b6188;
  }
  .hint {
    color: red;
    padding-left: 15px;
    font-size: 12px;
  }
  .clickable {
    cursor: pointer;
  }
}

@media screen and (max-width: 940px) {
  .report-main {
    margin: 15px 10px;
  }
}
</style>

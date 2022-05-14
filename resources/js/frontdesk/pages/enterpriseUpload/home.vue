<template>
  <div class="enterprise-home">
    <div class="nameplate">
      <div class="row nameplate-content">
        <div class="col-auto">
          <img src="@/asset/images/enterpriseUpload/portrait.svg" />
        </div>
        <div class="col content-text">
          <div>{{ regards }}</div>
          <div>我的使用者編號：{{ userData.id }}</div>
        </div>
      </div>
    </div>
    <div class="home-content">
      <div>
        <div class="white-block">
          <div class="block-title">親愛的 {{ userData.name }} 之負責人</div>
          <div class="block-info row no-gutters bb">
            <div class="col-auto">代收代付帳戶餘額</div>
            <div class="col"></div>
            <div class="col-auto num">${{ format(funds) }}</div>
          </div>
          <div class="block-info row no-gutters bb">
            <div class="col-auto">待放款金額</div>
            <div class="col"></div>
            <div class="col-auto num">${{ format(frozen) }}</div>
          </div>
          <div class="block-info row no-gutters bb">
            <div class="col-auto">現欠本金餘額</div>
            <div class="col"></div>
            <div class="col-auto num">${{ format(principal) }}</div>
          </div>
          <div class="block-info row no-gutters bb">
            <div class="col-auto">本期應收本息</div>
            <div class="col"></div>
            <div class="col-auto num">${{ format(repaymentAmount) }}</div>
          </div>
        </div>
        <div class="white-block function-buttons">
          <button class="btn tab-btn active">
            <span class="btn-icon">
              <img
                src="@/asset/images/enterpriseUpload/doc.svg"
                class="icon-active"
              />
            </span>
            <span>我的案件</span>
          </button>
          <router-link to="/myrepayment">
            <button class="btn tab-btn">
              <span class="btn-icon">
                <img src="@/asset/images/enterpriseUpload/dollar-square.svg" />
              </span>
              <span>帳戶餘額</span>
            </button>
          </router-link>
          <router-link to="/userTerms">
            <button class="btn tab-btn">
              <span class="btn-icon">
                <img src="@/asset/images/enterpriseUpload/task-square.svg" />
              </span>
              <span>平台條約</span>
            </button>
          </router-link>
          <router-link to="/promoteCode">
            <button class="btn tab-btn">
              <span class="btn-icon">
                <img src="@/asset/images/enterpriseUpload/like.svg" />
              </span>
              <span>推薦有賞</span>
            </button>
          </router-link>
          <div class="row no-gutters">
            <button
              class="btn btn-notification col mr-3"
              data-toggle="modal"
              data-target="#notificationModal"
            >
              查看通知
            </button>
            <button class="btn btn-logout col-auto" @click="logout">
              登出
            </button>
          </div>
        </div>
      </div>
      <div class="white-block">
        <div class="block-title">我的案件</div>
        <div class="case-tabs row no-gutters">
          <div class="col">
            <button
              class="btn case-tab"
              :class="{ active: caseType === null }"
              @click="showCase(null)"
            >
              申貸中 ({{ caseNums.offer }})
            </button>
          </div>
          <div class="col">
            <button
              class="btn case-tab"
              :class="{ active: caseType === 5 }"
              @click="showCase(5)"
            >
              還款中 ({{ caseNums.repayment }})
            </button>
          </div>
          <div class="col">
            <button
              class="btn case-tab"
              :class="{ active: caseType === 10 }"
              @click="showCase(10)"
            >
              已結案 ({{ caseNums.finished }})
            </button>
          </div>
        </div>
        <div>
          <div class="item-info row no-gutters" v-for="item in renderApplyList">
            <div class="installment col-auto">
              <div class="num" :class="caseStatus(item.status)">
                {{ item.instalment }}
              </div>
              <div class="text">期</div>
            </div>
            <div class="col">
              <div class="item-title">
                {{ item.product_name }} - {{ item.target_no }}
              </div>
              <div class="row no-gutters my-2">
                <div class="col-6">
                  <div class="item-info-title">申貸額度</div>
                  <div class="item-info-text">${{ format(item.amount) }}</div>
                </div>
                <div class="col">
                  <div class="item-info-title">申貸日期</div>
                  <div class="item-info-text">
                    {{ dateTime(item.created_at) }}
                  </div>
                </div>
              </div>
              <div class="row no-gutters justify-content-end">
                <!-- 申貸中 -->

                <!-- 還款中 -->
                <button
                  class="btn col-auto btn-repayment w-100"
                  v-if="item.status === 5"
                >
                  媒合成功
                </button>
                <!-- 已結案 -->
                <button
                  class="btn col-auto btn-finished w-100"
                  v-else-if="item.status === 10"
                >
                  已完成
                </button>
                <div class="d-flex no-gutters" v-else>
                  <button class="btn col-auto btn-status">
                    {{ statusText(item.status) }}
                  </button>
                  <router-link
                    :to="`overview/principal?case-id=${item.id}`"
                  >
                    <button
                      class="btn col-auto btn-offer"
                      :disabled="item.status !== 0"
                    >
                      線上資料提供
                    </button>
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="notificationModal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <div
              class="notification-item"
              v-for="item in notifications"
              @click="read(item.id, item.status)"
            >
              <div class="row no-gutters">
                <div class="unread-dot" v-if="item.status === 1"></div>
                <div class="col notification-item-date">
                  {{ new Date(item.created_at * 1000).toLocaleString() }}
                </div>
              </div>
              <div class="row no-gutters notification-item-title">
                <span>{{ item.title }}</span>
                <span v-html="item.content"></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  beforeRouteEnter(to, from, next) {
    if (sessionStorage.length === 0 || sessionStorage.flag === 'logout') {
      next('/index')
      // next();
    } else {
      next()
    }
  },
  mounted() {
    this.getMyRepayment()
    this.getNotification()
    this.getApplyList()
  },
  data() {
    return {
      applyList: [],
      caseNums: {
        offer: 0,
        repayment: 0,
        finished: 0
      },
      myRepayment: {},
      notifications: [],
      repaymentDate: '',
      repaymentAmount: '',
      funds: 0,
      frozen: 0,
      principal: 0,
      caseType: null
    }
  },
  computed: {
    renderApplyList() {
      return this.applyList.filter(x => {
        if (this.caseType) {
          return x.status === this.caseType
        } else {
          // 申貸中 0~4
            return Number(x.status < 5)
          // return true
        }
      })
    },
    userData() {
      return JSON.parse(sessionStorage.getItem('userData'))
    },
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
      return `${text} ${this.userData.name}`;
    },
  },
  methods: {
    dateTime(d) {
      return new Date(d * 1000).toLocaleString().split(' ')[0]
    },
    format(data) {
      let l10nEN = new Intl.NumberFormat("en-US");
      return l10nEN.format(Number(data).toFixed(0));
    },
    caseStatus(status) {
      switch (Number(status)) {
        case 5:
          return 'case-repayment'
        case 10:
          return 'case-finished'
        default:
          return 'case-offer'
      }
    },
    statusText(status) {
      switch (Number(status)) {
        case 0:
          return '待核可'
        case 1:
          return '待簽約'
        case 2:
          return '待驗證'
        case 3:
          return '待出借'
        case 4:
          return '待放款'
        default:
          return ''
      }
    },
    showCase(type) {
      this.caseType = type
    },
    getMyRepayment() {
      axios
        .get("/getMyRepayment")
        .then((res) => {
          this.myRepayment = res.data.data;
          this.funds = res.data.data.funds.total;
          this.frozen = res.data.data.funds.frozen;
          this.principal = res.data.data.accounts_payable.principal;
          this.repaymentAmount = res.data.data.next_repayment.amount;
          this.repaymentDate = res.data.data.next_repayment.date;
        })
        .catch((error) => {
          if (error.response.data.error === 100) {
            alert("連線逾時，請重新登入");
            this.$root.logout();
          } else {
            console.error("getMyRepayment 發生錯誤，請稍後再試");
          }
        });
    },
    getNotification() {
      axios.post(`/getNotification`).then((res) => {
        this.notifications = res.data.data.list
      })
        .catch((error) => {
          if (error.response.data.error === 100) {
            alert("連線逾時，請重新登入")
            this.logout()
          } else {
            console.log("getNotification 發生錯誤，請稍後再試");
          }
        });
    },
    getApplyList() {
      this.caseNums = {
        offer: 0,
        repayment: 0,
        finished: 0
      }
      axios.get('/api/v1/product/applylist').then(({ data }) => {
        const ans = data.data.list
        this.applyList = ans
        ans.forEach(x => {
          if (Number(x.status) < 5) {
            this.caseNums.offer += 1
          }
          if (Number(x.status) === 5) {
            this.caseNums.repayment += 1
          }
          if (Number(x.status) === 10) {
            this.finished += 1
          }
        })
      })
    },
    read(id, status) {
      if (status == 1) {
        axios.post(`/read`, { id }).then((res) => {
          this.getNotification()
        });
      }
    },
    logout() {
      axios.post(`/logout`).then((res) => {
        this.$store.commit('mutationUserData', {})
        location.reload()
      })
    },
  },

}
</script>

<style lang="scss" scoped>
.modal-content {
  border-radius: 20px;
}
.modal-body {
  padding: 34px 30px;
  max-height: 600px;
  overflow: scroll;
}
.enterprise-home {
  .nameplate {
    background-image: url('~images/enterpriseUpload/home-header.png');
    height: 200px;
    border-radius: 20px;
    margin-bottom: 50px;
    .nameplate-content {
      padding: 40px;
      .content-text {
        padding: 15px;
        font-style: normal;
        font-weight: 500;
        font-size: 24px;
        line-height: 35px;
        color: #ffffff;
      }
    }
  }
  .home-content {
    display: grid;
    grid-template-columns: 3fr 4fr;
    gap: 50px;
    .white-block {
      background: #ffffff;
      border-radius: 20px;
      padding: 30px 30px 15px;
      .block-title {
        font-style: normal;
        font-weight: 500;
        font-size: 24px;
        line-height: 35px;
        color: #393939;
        margin-bottom: 30px;
      }
      .block-info {
        font-style: normal;
        font-weight: 500;
        font-size: 18px;
        line-height: 26px;
        color: #707070;
        padding-bottom: 10px;
        margin-bottom: 15px;
        .num {
          color: #036eb7;
        }
        &.bb {
          border-bottom: 1.5px solid #f3f3f3;
        }
      }
    }
    .function-buttons {
      margin-top: 50px;
      display: flex;
      flex-direction: column;
      gap: 15px;
      .tab-btn {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        padding: 7px auto;
        font-weight: 500;
        font-size: 18px;
        line-height: 20px;
        text-align: center;
        color: #707070;
        border: 1.5px solid #f3f3f3;
        border-radius: 10px;
        &.active {
          color: #036eb7;
          border-color: #036eb7;
        }
      }
      .btn-notification {
        font-style: normal;
        font-weight: 500;
        font-size: 18px;
        line-height: 26px;
        text-align: center;
        color: #ffffff;
        background: #036eb7;
        border-radius: 10px;
      }
      .btn-logout {
        padding: 7px 40px;
        font-style: normal;
        font-weight: 500;
        font-size: 18px;
        line-height: 26px;
        text-align: center;
        color: #ffffff;
        background: #f29600;
        border-radius: 10px;
      }
    }
    .case-tabs {
      gap: 30px;
      padding-bottom: 16px;
      border-bottom: 2px solid #f3f3f3;
      margin-bottom: 16px;
      .case-tab {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        padding: 7px auto;
        font-weight: 500;
        font-size: 18px;
        line-height: 26px;
        text-align: center;
        color: #707070;
        border: 1.5px solid #f3f3f3;
        border-radius: 10px;
        &.active {
          color: #036eb7;
          border-color: #036eb7;
        }
      }
    }
    .item-info {
      display: flex;
      padding: 15px 30px;
      border: 1.5px solid #f3f3f3;
      box-sizing: border-box;
      border-radius: 10px;
      margin-bottom: 25px;
      .installment {
        margin-right: 28px;
        .num {
          padding: 22px;
          font-family: 'Arial';
          font-style: italic;
          font-weight: 700;
          font-size: 48px;
          line-height: 55px;
          text-align: center;
          color: #707070;
          border-radius: 50%;
          border: 4px solid #f3f3f3;
          &.case-offer {
            border-color: #f3f3f3;
          }
          &.case-repayment {
            border-color: #007ddc;
          }
          &.case-finished {
            border-color: #30ba3e;
          }
        }
        .text {
          margin-top: 7px;
          font-style: normal;
          font-weight: 500;
          font-size: 18px;
          line-height: 26px;
          text-align: center;
          color: #393939;
        }
      }
      .item-title {
        padding: 0 0 8px 30px;
        margin-bottom: 8px;
        font-style: normal;
        font-weight: 500;
        font-size: 18px;
        line-height: 26px;
        color: #393939;
        border-bottom: 2px solid #f3f3f3;
      }
      .item-info-title {
        padding-left: 30px;
        font-weight: 500;
        font-size: 14px;
        line-height: 20px;
        color: #797979;
      }
      .item-info-text {
        padding-left: 30px;
        margin-top: 4px;
        font-style: normal;
        font-weight: 500;
        font-size: 14px;
        line-height: 20px;
        color: #036eb7;
      }
      .btn-status {
        padding: 7px 31px;
        font-style: normal;
        font-weight: 500;
        font-size: 18px;
        line-height: 26px;
        text-align: center;
        color: #ffffff;
        background: #c4c4c4;
        border-radius: 10px;
      }
      .btn-offer {
        padding: 7px 31px;
        font-style: normal;
        font-weight: 500;
        font-size: 18px;
        line-height: 26px;
        text-align: center;
        color: #ffffff;
        background: #036eb7;
        border-radius: 10px;
        margin-left: 30px;
      }
      .btn-repayment {
        padding: 7px 0;
        font-style: normal;
        font-weight: 500;
        font-size: 18px;
        line-height: 26px;
        text-align: center;
        color: #ffffff;
        background: #f29600;
        border-radius: 10px;
      }
      .btn-finished {
        padding: 7px 0;
        font-style: normal;
        font-weight: 500;
        font-size: 18px;
        line-height: 26px;
        text-align: center;
        color: #ffffff;
        background: #30ba3e;
        border-radius: 10px;
      }
    }
  }
  .notification-item {
    cursor: pointer;
    padding-bottom: 15px;
    border-bottom: 2px solid #f3f3f3;
    margin-bottom: 15px;
    &:hover {
      background-color: #f3f3f3;
    }
    .unread-dot {
      width: 8px;
      height: 8px;
      background-color: #f29600;
      border-radius: 50%;
      margin-top: 8px;
      margin-right: 4px;
    }
    .notification-item-date {
      font-style: normal;
      font-weight: 500;
      font-size: 16px;
      line-height: 23px;
      color: #393939;
      margin-bottom: 6px;
    }
    .notification-item-title {
      padding-left: 12px;
      font-style: normal;
      font-weight: 500;
      font-size: 16px;
      line-height: 23px;
      color: #707070;
    }
  }
}
</style>

<template>
  <div v-if="!loading">
    <div class="loan-header">
      <userInfo :userData="userData"></userInfo>
      <div class="menu-card">
        <div style="width: max-content; overflow: hidden">
          <router-link class="menu-item" to="promoteCode">
            <div class="img">
              <img
                src="../asset/images/icon-recommendmoney.svg"
                style="width:45px"
              />
            </div>
            <p>推廣有賞</p>
          </router-link>
          <router-link class="menu-item" to="loannotification">
            <div class="img">
              <img
                src="../asset/images/icon_notification.svg"
                class="img-fluid"
              />
              <span v-if="unreadCount !== 0">{{ unreadCount }}</span>
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
    <div class="main-content" v-if="startQr">
      <div class="left block">
        <div class="qr-up">
          <div class="qr-code">
            <div class="block-title">推薦有賞QRcode</div>
            <div class="qr-graph">
              <img style="width:120px;" :src="apiData.promote_qrcode" />
            </div>
          </div>
          <div class="qr-summary">
            <div class="summary-group">
              <span class="summary-item">•統計至:</span>
              <span class="summary-value">{{ now() }}</span>
            </div>
            <div class="summary-group" v-if="apiData.overview">
              <span class="summary-item">•成功推薦下載+註冊人數:</span>
              <span class="summary-value">
                {{ apiData.overview.fullMemberCount }}
              </span>
            </div>
            <div
              class="summary-group"
              v-if="apiData.overview && apiData.overview.loanedCount"
            >
              <span class="summary-item">•成功推薦學生貸人數:</span>
              <span class="summary-value">
                {{ apiData.overview.loanedCount.student }}
              </span>
            </div>
            <div
              class="summary-group"
              v-if="apiData.overview && apiData.overview.loanedCount"
            >
              <span class="summary-item">•成功推薦上班族人數:</span>
              <span class="summary-value">
                {{ apiData.overview.loanedCount.salary_man }}
              </span>
            </div>
            <div class="summary-group">
              <span class="summary-item">•累積獎金:</span>
              <span class="summary-value">
                {{ apiData.total_reward_amount }}
              </span>
            </div>
          </div>
        </div>
        <div class="qr-down">
          <div class="qr-chart">
            <div class="block-title">當月收益</div>
            <div class="chart">
              <div class="pie-chart" ref="pie_chart"></div>
            </div>
          </div>
          <div class="qr-profit">
            <div class="profit-group">
              <span class="profit-item">•成功推薦下載+註冊獎金:</span>
              <span class="profit-value" style="color:#4385f5;">
                {{ formate(newestDetailList.fullMemberRewardAmount) }}
              </span>
            </div>
            <div class="profit-group">
              <span class="profit-item">•成功推薦學生貸獎金:</span>
              <span class="profit-value" style="color:#f57c00;">
                {{ formate(newestDetailList.student.rewardAmount) }}
              </span>
            </div>
            <div class="profit-group">
              <span class="profit-item">•成功推薦上班族獎金:</span>
              <span class="profit-value" style="color:#f44336;">
                {{ formate(newestDetailList.salary_man.rewardAmount) }}
              </span>
            </div>
            <div class="all-profit profit-group">
              <span class="profit-item">•總獎金:</span>
              <span class="profit-value" style="color:#003cb4;">
                {{ formate(newestDetailListTotal) }}
              </span>
            </div>
          </div>
        </div>
      </div>
      <div class="right">
        <div class="qr-thema">
          <div class="thema-title block-title">活動辦法</div>
          <div class="thema-content">
            <div>
              1.透過推廣QRcode掃描下載APP註冊會員並完成申貸即可獲得推薦獎金
            </div>
            <div>2.首次學生貸媒合成功即獲得獎金NT$200</div>
            <div>3.首次上班族貸媒合成功即獲得獎金NT$400</div>
            <div>4.獎金統一於月底結算，次月10日撥付至專屬帳戶</div>
          </div>
        </div>
        <div class="month-detail">
          <div class="month-title block-title">每月明細</div>
          <div class="detail-list" v-if="detailList">
            <detailItem
              v-for="i in Object.keys(detailList)"
              :key="i"
              :detail="detailList[i]"
              :date="i"
              @click="dataModalOpen(detailList[i], i)"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="main-content fail" v-else>
      <div class="row title-1">每月明細</div>
      <div class="row title-2">尚未成功推薦，開始分享以累計獎金</div>
      <div class="row">
        <img src="../asset/images/qr_start.svg" class="img-start" />
      </div>
    </div>
    <div class="modal" id="dataListModal" role="dialog">
      <div class="modal-dialog" v-if="selectedDetail != null">
        <div class="modal-content">
          <div class="modal-data-header">
            <div class="data-list-date">{{ selectedDetail.date }} 明細</div>
          </div>
          <div class="divider"></div>
          <div class="summary-row">
            <span class="summary-item">•成功推薦學生貸人數:</span>
            <span class="summary-value">
              {{ selectedDetail.student.count }}
            </span>
          </div>
          <div class="summary-row">
            <span class="summary-item">•成功推薦上班族人數:</span>
            <span class="summary-value">
              {{ selectedDetail.salary_man.count }}
            </span>
          </div>
          <div class="summary-row">
            <span class="summary-item">•本月獎金統計:</span>
            <span class="summary-value">
              {{
                formate(
                  selectedDetail.fullMemberRewardAmount +
                    selectedDetail.student.rewardAmount +
                    selectedDetail.salary_man.rewardAmount
                )
              }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import userInfo from "../component/userInfoComponent";
import detailItem from "../component/promoteCodeDetailItem";
import Axios from 'axios';

export default {
  beforeRouteEnter (to, from, next) {
    if (sessionStorage.length === 0 || sessionStorage.flag === "logout") {
      next("/index");
      // next();
    } else {
      next();
    }
  },
  components: {
    userInfo,
    detailItem,
  },
  mounted () {
    this.loading = true
    this.getNotification()
    this.getPromoteCodeData()
      .then(ans => {
        this.apiData = ans.data
        this.detailList = ans.data.detail_list
        this.startQr = ans.data.status > 0
      })
      .finally(async () => {
        this.loading = false
        await this.$nextTick()
        if (this.startQr != false) {
          this.createPieChart(this.newestDetailList)
        }
      })
  },
  data: () => ({
    userData: JSON.parse(sessionStorage.getItem("userData")),
    apiData: null,
    detailList: null,
    selectedDetail: null,
    startQr: false,
    loading: false,
    unreadCount: 0,
  }),
  methods: {
    createPieChart (data) {
      let pieData = [];

      pieData.push({
        value: data.fullMemberRewardAmount,
        name: "成功推薦下載+註冊獎金",
        itemStyle: { color: "#4385f5" },
      });
      pieData.push({
        value: data.student.rewardAmount,
        name: "成功推薦學生貸獎金",
        itemStyle: { color: "#f57c00" },
      });
      pieData.push({
        value: data.salary_man.rewardAmount,
        name: "成功推薦上班族獎金",
        itemStyle: { color: "#f44336" },
      });

      let pie_chart = echarts.init(this.$refs.pie_chart);

      let option = {
        tooltip: {
          trigger: "item",
          confine: true,
          formatter (params) {
            return `<span>${params.name}：$<span style="color:${
              params.color
              }">${' ' + params.value.toLocaleString() + ' '}</span> (${params.percent}%)`;
          },
        },
        series: [
          {
            type: "pie",
            radius: "80%",
            label: {
              show: false,
            },
            emphasis: {
              label: {
                show: false,
              },
            },
            animationType: "scale",
            animationEasing: "elasticOut",
            animationDelay: function (idx) {
              return 500;
            },
            data: pieData.sort(function (a, b) {
              return a.value > b.value;
            }),
          },
        ],
      };

      pie_chart.setOption(option);
    },
    async getPromoteCodeData () {
      return Axios.get('/getPromoteCode').then(x => {
        return x.data
      }).catch(err => {
        console.error(err)
      })
    },
    dataModalOpen (data, date) {
      $('#dataListModal').modal('show')
      this.selectedDetail = { ...data, date }
      // setTimeout(() => { $('#dataListModal').modal('hide') }, 3000)
    },
    getNotification () {
      this.unreadCount = 0;
      axios
        .post(`${location.origin}/getNotification`)
        .then((res) => {
          res.data.data.list.forEach((item, index) => {
            if (item.status == 1) {
              this.unreadCount++;
            }
          });
        })
        .catch((error) => {
          if (error.response.data.error === 100) {
            alert("連線逾時，請重新登入");
            this.$root.logout();
          } else {
            console.log("getNotification 發生錯誤，請稍後再試");
          }
        });
    },
    formate (n) {
      return n.toLocaleString()
    },
    formateDate (s) {
      // 2021-09-26 15:43:16
      const ans = s.split(' ')
      return ans[0].split('-').join('/')
    },
    now () {
      const now = new Date()
      return `${now.getFullYear()}/${now.getMonth() + 1}/${now.getDate()}`
    },
    getDetailKey () {
      const now = new Date()
      return `${now.getFullYear()}-${now.getMonth() + 1}`
    },
  },
  computed: {
    newestDetailList () {
      const list = this.detailList
      if (list) {
        const now = new Date()
        const month = now.getMonth() > 8 ? (now.getMonth() + 1).toString() : `0${(now.getMonth() + 1).toString()}`
        const lastMonth = now.getMonth() > 9 ? now.getMonth().toString() : `0${now.getMonth().toString()}`
        const key = `${now.getFullYear()}-${month}`
        const key2 = `${now.getFullYear()}-${lastMonth}`
        return list[key] ?? list[key2]
      }
      return null
    },
    newestDetailListTotal () {
      const list = this.newestDetailList
      if (list && list.student && list.salary_man) {
        return list.fullMemberRewardAmount + list.student.rewardAmount + list.salary_man.rewardAmount
      }
      return 0
    }
  }
}
</script>

<style lang="scss" scoped>
.main-content {
  margin: 42px 100px 45px 145px;
  display: flex;
  .block {
    background-color: #fff;
    border-radius: 20px;
    box-shadow: 0 3px 6px 0 rgba(0, 0, 0, 0.16);
  }
  .left {
    padding: 20px 40px;
    width: 706px;
    margin-right: 73px;
    .qr-up {
      display: flex;
      .qr-code {
        width: 278px;
      }
      .qr-graph {
        margin-top: 13px;
        width: 120px;
        height: 120px;
        // background-color: black;
      }
      .qr-summary {
        width: 260px;
        font-family: NotoSansTC;
        font-size: 14px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: 2;
        letter-spacing: normal;
        text-align: left;
        color: #5d5555;
        .summary-group {
          display: flex;
          justify-content: space-between;
        }
        .summary-item {
          display: inline-block;
          margin-right: 20px;
        }
        .summary-value {
          display: inline-block;
          text-align: right;
        }
      }
    }
    .qr-down {
      margin-top: 65px;
      display: flex;
      .qr-chart {
        width: 278px;
        .pie-chart {
          width: 200px;
          height: 200px;
        }
      }
      .qr-profit {
        width: 260px;
        font-family: NotoSansTC;
        font-size: 14px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: 2;
        letter-spacing: normal;
        text-align: left;
        color: #5d5555;
        .profit-group {
          display: flex;
          justify-content: space-between;
        }
        .profit-item {
          margin-right: 20px;
        }
        .profit-value {
          text-align: right;
        }
        .all-profit {
          margin-top: 28px;
        }
      }
    }
  }
  .right {
    width: 492px;
    display: flex;
    flex-direction: column;
    .qr-thema {
      padding: 20px 34px 28px 38px;
      border-radius: 20px;
      box-shadow: 0 3px 6px 0 rgba(0, 0, 0, 0.16);
    }
    .month-detail {
      max-height: 300px;
      overflow: auto;
      margin-top: 45px;
      padding: 20px 34px 28px 38px;
      border-radius: 20px;
      box-shadow: 0 3px 6px 0 rgba(0, 0, 0, 0.16);
    }
    .detail-list {
      margin-top: 10px;
    }
  }
  .block-title {
    font-family: NotoSansTC;
    font-size: 18px;
    font-weight: 500;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.44;
    letter-spacing: normal;
    text-align: left;
    color: #1c395f;
  }
}
.fail {
  flex-direction: column;
  align-items: center;
  .title-1 {
    font-family: NotoSansTC;
    font-size: 24px;
    font-weight: 500;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.44;
    letter-spacing: normal;
    text-align: left;
    color: #1c395f;
    margin-bottom: 20px;
  }
  .title-2 {
    font-family: NotoSansTC;
    font-size: 20px;
    font-weight: 500;
    font-stretch: normal;
    font-style: normal;
    line-height: 2;
    letter-spacing: normal;
    text-align: left;
    color: #5d5555;
    margin-bottom: 35px;
  }
  .img-start {
    width: 600px;
  }
}
.modal-content {
  padding: 0 20px 0 20px;
  border-radius: 20px;
  background-size: cover;
  background-repeat: no-repeat;
  background-image: url("../asset/images/modal-bg.svg");
  .modal-data-header {
    display: flex;
    justify-content: flex-end;
    .data-list-date {
      color: #fff;
      margin: 28px 0 25px;
    }
  }
  .divider {
    width: 100%;
    margin: 10px 0 10px;
    border-bottom: #5d5555 1px solid;
  }
  .summary-row {
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    font-family: NotoSansTC;
    font-size: 22px;
    font-weight: 500;
    font-stretch: normal;
    font-style: normal;
    line-height: 2;
    letter-spacing: normal;
    text-align: left;
    color: #5d5555;
    .summary-item {
      margin-right: 24px;
    }
    .summary-value {
      text-align: right;
      color: #f29600;
    }
  }
}
@media screen and (max-width: 767px) {
  .main-content {
    margin: 20px 15px;
    flex-direction: column;
    .left {
      padding: 20px 15px;
      max-width: 100%;
      margin-right: 0;
      .qr-up {
        flex-direction: column;
        .qr-code {
          margin: auto;
          max-width: 100%;
        }
        .qr-graph {
          margin: auto;
        }
        .qr-summary {
          margin: 20px auto 0;
          font-size: 16px;
        }
      }
      .qr-down {
        margin-top: 30px;
        flex-direction: column;
        .qr-chart {
          margin: auto;
          width: 200px;
        }
        .qr-profit {
          margin: auto;
          font-size: 16px;
        }
      }
    }
    .right {
      margin-top: 20px;
      max-width: 100%;
      .qr-thema {
        padding: 20px 25px;
      }
      .month-detail {
        margin-top: 25px;
        padding: 10px 25px;
        font-size: 16px;
      }
      .detail-list {
        margin-top: 20px;
      }
    }
    .block-title {
      text-align: left;
    }
  }
  .fail {
    .title-1 {
      margin-bottom: 20px;
    }
    .title-2 {
      margin-bottom: 35px;
    }
    .img-start {
      display: block;
      margin: auto;
      max-width: 90%;
    }
  }
  .modal-content {
    padding: 0 20px 0 20px;
    .modal-data-header {
      .data-list-date {
        color: #fff;
        margin: 28px 0 25px;
      }
    }
    .divider {
      width: 100%;
      margin: 10px 0 10px;
      border-bottom: #5d5555 1px solid;
    }
    .summary-row {
      margin-bottom: 20px;
      .summary-item {
        margin-right: 24px;
      }
    }
  }
}
</style>
<style lang="scss">
.loan-header {
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
    margin: 0 -20px;
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
@media screen and (max-width: 767px) {
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
}
</style>
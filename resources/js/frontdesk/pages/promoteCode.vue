<template>
  <div class="">
    <div class="loan-header">
      <userInfo :userData="userData"></userInfo>
      <div class="menu-card">
        <!-- <div style="width: max-content; overflow: hidden">
          <router-link class="menu-item" to="loannotification">
            <div class="img">
              <img
                src="../asset/images/icon_notification.svg"
                class="img-fluid"
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
        </div> -->
      </div>
    </div>
    <div class="main-content">
      <div class="left block">
        <div class="qr-up">
          <div class="qr-code">
            <div class="block-title">推薦有賞QRcode</div>
            <div class="qr-graph"></div>
          </div>
          <div class="qr-summary">
            <div>•統計至:</div>
            <div>•成功推薦下載+註冊人數:</div>
            <div>•成功推薦學生貸人數:</div>
            <div>•成功推薦上班族人數:</div>
            <div>•累積獎金:</div>
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
            <div>•成功推薦下載+註冊獎金:</div>
            <div>•成功推薦學生貸獎金:</div>
            <div>•成功推薦上班族獎金:</div>
            <div class="all-profit">•總獎金:</div>
          </div>
        </div>
      </div>
      <div class="right">
        <div class="qr-thema">
          <div class="thema-title block-title">活動辦法</div>
          <div class="thema-content">
            some text...
          </div>
        </div>
        <div class="month-detail">
          <div class="month-title block-title">每月明細</div>
          <div class="detail-list">
            <detailItem v-for="i in 3" :key="i" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import userInfo from "../component/userInfoComponent";
import detailItem from "../component/promoteCodeDetailItem";
import axios from "axios"
import Axios from 'axios';

export default {
  beforeRouteEnter (to, from, next) {
    if (sessionStorage.length === 0 || sessionStorage.flag === "logout") {
      next("/index");
    } else {
      next();
    }
  },
  components: {
    userInfo,
    detailItem,
  },
  mounted () {
    this.createPieChart()
    this.getPromoteCodeData()
  },
  data: () => ({
    userData: JSON.parse(sessionStorage.getItem("userData")),
  }),
  methods: {
    createPieChart () {
      let pieData = [];

      pieData.push({
        value: 52220,
        name: "成功推薦下載+註冊獎金",
        itemStyle: { color: "#4385f5" },
      });
      pieData.push({
        value: 40000,
        name: "成功推薦學生貸獎金",
        itemStyle: { color: "#f57c00" },
      });
      pieData.push({
        value: 30000,
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
    getPromoteCodeData () {
      Axios.get('/getPromoteCode').then(x => {
        console.log(x.data)
      }).catch(err => {
        console.error(err)
      })
    },
  }
}
</script>

<style lang="scss" scoped>
.main-content {
  margin: 42px 107px 45px 153px;
  display: flex;
  .block {
    background-color: #fff;
    border-radius: 20px;
    box-shadow: 0 3px 6px 0 rgba(0, 0, 0, 0.16);
  }
  .left {
    padding: 20px 67px 20px 95px;
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
        background-color: black;
      }
      .qr-summary {
        font-family: NotoSansTC;
        font-size: 14px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: 2;
        letter-spacing: normal;
        text-align: left;
        color: #5d5555;
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
        font-family: NotoSansTC;
        font-size: 14px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: 2;
        letter-spacing: normal;
        text-align: left;
        color: #5d5555;
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
</style>
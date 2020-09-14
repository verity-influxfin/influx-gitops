<template>
  <div class="college-wrapper">
    <banner :data="this.bannerData"></banner>
    <div class="text-card">
      <div class="a-hr">
        <div class="a-s">
          <!-- <h3>「夢想與生活的資金需求，普匯投資借給你！」</h3> -->
          <p>集結了各大學校友、老師，專門投資借貸同學在學期間的資金需求。</p>
          <p>不論是夢想實現，還是生活急需，只要下載普匯App，「3分鐘申請，10分鐘核准，1小時到帳」，全程AI線上媒合為你快速找到投資人！</p>
          <p>超過50000人都在使用普匯完成夢想、解決生活資金問題，同學都只找「普匯．你的手機ATM 」，提供簡單快速、隱私又安全的急用資金！</p>
        </div>
      </div>
    </div>
    <credit :creditList="creditList" amount="12" />
    <div class="partner-card">
      <div class="partner-box">
        <p>我們服務了超過140所學校的同學</p>
      </div>
      <div class="partner-row">
        <div class="item hvr-grow-shadow" v-for="(item,index) in partner" :key="index">
          <img class="img-fluid" :src="item.imageSrc" />
        </div>
      </div>
    </div>
    <experience :experiences="experiences" />
    <apply
      title="「不知道該如何申貸嗎？」"
      :requiredDocuments="applyData.requiredDocuments"
      :step="applyData.step"
    />
    <download :isLoan="true" :isInvest="false" />
    <qa :qaData="qaData" />
  </div>
</template>

<script>
import banner from "../component/bannerComponent";
import download from "../component/downloadComponent";
import experience from "../component/experienceComponent";
import qa from "../component/qaComponent";
import apply from "../component/applyComponent";
import credit from "../component/creditComponent";

export default {
  components: {
    banner,
    download,
    experience,
    qa,
    apply,
    credit,
  },
  data: () => ({
    qaData: [],
    partner: [],
    bannerData: {},
    applyData: {},
    creditList: {
      rate1: "5%",
      rate2: "6%",
      rate3: "7%",
      rate4: "8%",
      rate5: "9%",
      rate6: "10%",
      rate7: "11%",
      rate8: "12%",
      rate9: "13%",
    },
  }),
  computed: {
    experiences() {
      let $this = this;
      let data = [];
      $.each($this.$store.getters.ExperiencesData, (index, row) => {
        if (row.category === "college") {
          data.push(row);
        }
      });

      return data;
    },
    video() {
      return this.$store.getters.VideoData.slice(0, 4);
    },
  },
  created() {
    this.$store.dispatch("getExperiencesData", "loan");
    this.getBannerData();
    this.getPartnerData();
    this.getQaData();
    this.getApplydata();
    $("title").text(`學生貸款 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {
      AOS.init();
    });
  },
  methods: {
    getBannerData() {
      axios.post("getBannerData", { filter: "college" }).then((res) => {
        this.bannerData = res.data;
      });
    },
    getPartnerData() {
      axios.post("getPartnerData").then((res) => {
        res.data.forEach((item) => {
          if (item.type === "edu") {
            this.partner.push(item);
          }
        });
      });
    },
    getApplydata() {
      axios.post("getApplydata", { filter: "college" }).then((res) => {
        this.applyData = res.data;
      });
    },
    getQaData() {
      axios.post("getQaData", { filter: "college" }).then((res) => {
        this.qaData = res.data;
      });
    },
  },
};
</script>

<style lang="scss">
.college-wrapper {
  width: 100%;

  h2 {
    font-weight: bolder;
    text-align: center;
    color: #083a6e;
  }

  .hr {
    width: 130px;
    height: 2px;
    background-image: linear-gradient(to right, #000e8b, #ffffff);
    margin: 0px auto;
  }

  .link {
    display: block;
    background: #006bda;
    color: #ffffff;
    width: 20%;
    margin: 0px auto;
    font-weight: bolder;

    i {
      margin-left: 10px;
    }

    &:hover {
      border: 2px solid #006bda;
      background: #ffffff;
      color: #006bda;
    }
  }

  .text-card {
    .a-hr {
      height: 125px;
      background-color: #94d6eb;
      position: relative;

      .a-s {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 80%;
        background-color: #04769f;
        font-weight: bold;
        color: #ffffff;

        h3 {
          color: #ffffff;
          text-align: center;
          font-weight: bold;
          margin: 25px auto;
        }

        p {
          width: 80%;
          margin: 25px auto;
        }
      }
    }
  }

  .partner-card {
    overflow: auto;
    position: relative;
    padding: 4rem 0px;
    display: flex;
    justify-content: flex-end;

    .partner-box {
      background-color: #d9e7f5;
      width: 30%;
      position: relative;

      p {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 24.5px;
        font-weight: bold;
        line-height: 1.73;
        color: #000000;
      }
    }

    .partner-row {
      overflow: hidden;
      width: 55%;
      background-image: url("../asset/images/429.png");
      background-repeat: no-repeat;
      background-size: 100% 100%;
      padding: 20px;

      .item {
        float: left;
        width: calc(100% / 4 - 60px);
        margin: 20px 30px;
        border-radius: 50%;
        overflow: auto;
        padding: 10px;
        background: #ffffff;
      }
    }
  }

  @media screen and (max-width: 767px) {
    h2 {
      font-size: 25px;
      margin-bottom: 20px;
    }

    .link {
      width: 50%;
    }

    .text-card {
      display: none;
    }

    .partner-card {
      flex-direction: column;
      padding: 0px;

      .partner-box {
        width: 100%;

        p {
          position: initial;
          transform: initial;
          margin: 1rem 0px;
          text-align: center;
        }
      }

      .partner-row {
        width: 100%;
        padding: 0px;

        .item {
          margin: 10px;
          width: calc(33% - 20px);
          float: initial;
        }
      }
    }
  }
}
</style>

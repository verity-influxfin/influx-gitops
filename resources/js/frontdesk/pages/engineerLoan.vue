<template>
  <div class="engineer-wrapper">
    <banner :data="this.bannerData"></banner>
    <div class="text-card">
      <div class="a-hr">
        <div class="a-s">
          <!-- <h3>「夢想與生活的資金需求，普匯投資借給你！」</h3> -->
          <p>集結了各大學校友、老師，專門投資借貸同學在學期間的資金需求。</p>
          <p>不論是夢想實現，還是生活急需，只要下載普匯App，「3分鐘申請，10分鐘核准，1小時到帳」，全程AI線上媒合為你快速找到投資人！</p>
          <p>超過50,000人都在使用普匯完成夢想、解決生活資金問題，同學都只找「普匯．你的手機ATM 」，提供簡單快速、隱私又安全的急用資金！</p>
        </div>
      </div>
    </div>
    <credit :creditList="creditList" amount="20" license="最高額度會根據您的申請身分而有所不同" />
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
import qa from "../component/qaComponent";
import apply from "../component/applyComponent";
import credit from "../component/creditComponent";

export default {
  components: {
    banner,
    download,
    qa,
    apply,
    credit,
  },
  data: () => ({
    credit: "--",
    qaData: [],
    creditList: {
      rate1: "5%",
      rate2: "5.5%",
      rate3: "6%",
      rate4: "6.5%",
      rate5: "7%",
      rate6: "7.5%",
      rate7: "8%",
      rate8: "8.5%",
      rate9: "9%",
    },
    bannerData: {},
    applyData: {},
  }),
  created() {
    this.getApplydata();
    this.getBannerData();
    this.getQaData();
    $("title").text(`工程師專案 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {
      AOS.init();
    });
  },
  methods: {
    getBannerData() {
      axios.post("getBannerData", { filter: "engineer" }).then((res) => {
        this.bannerData = res.data;
      });
    },
    getApplydata() {
      axios.post("getApplydata", { filter: "engineer" }).then((res) => {
        this.applyData = res.data;
      });
    },
    getQaData() {
      axios.post("getQaData", { filter: "engineer" }).then((res) => {
        this.qaData = res.data;
      });
    },
    rotate(index, type) {
      let angle = window.outerWidth >= 767 ? 22.5 : 42.5;
      let distense = window.outerWidth >= 767 ? 200 : 100;
      let rotateFrom = (-angle * index) / 18;

      if (type === "circle") {
        return `rotate(${
          rotateFrom + index * angle - 167
        }deg) translate(${distense}px, 0px)`;
      } else {
        return `rotate(${(rotateFrom + index * angle - 167) * -1}deg)`;
      }
    },
    turn(index) {
      let angle = window.outerWidth >= 767 ? 22.5 : 40.5;
      let dir = index * angle - (window.outerWidth >= 767 ? 90 : 96);

      this.credit = this.creditList[index].rate;

      gsap.to(".pointer", 0.5, {
        rotate: `${dir}dge`,
      });
    },
    transform($event) {
      this.timeLineMax = new TimelineMax({ paused: true, reversed: true });
      this.timeLineMax.to($event.target, { scale: 1.2 });
      this.timeLineMax.play();
    },
    recovery($event) {
      this.timeLineMax.reverse();
    },
  },
};
</script>

<style lang="scss">
.engineer-wrapper {
  width: 100%;

  h2 {
    font-weight: bolder;
    text-align: center;
    color: #083a6e;
  }

  .hr {
    width: 130px;
    height: 2px;
    background-image: linear-gradient(to right, #71008b, #ffffff);
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
  }
}
</style>


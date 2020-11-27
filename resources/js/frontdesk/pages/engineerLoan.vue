<template>
  <div class="engineer-wrapper">
    <banner :data="this.bannerData" :isInvest="false"></banner>
    <target
      :items="applyData.item"
      text="資訊/資工/資管相關科系的學生或從事相關職業，並提供專業技術證照，<br><br>馬上享有高額度、低利率的優惠！"
    ></target>
    <apply
      title="申貸簡便五步驟"
      :requiredDocuments="applyData.requiredDocuments"
      :step="applyData.step"
    />
    <div class="avg-card">
      <img src="../asset/images/circle.svg" class="img-fluid" />
    </div>
    <credit
      :creditList="creditList"
      amount="20"
      license="最高額度會根據您的申請身分而有所不同"
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
import target from "../component/targetComponent";

export default {
  components: {
    banner,
    download,
    qa,
    apply,
    target,
    credit,
  },
  data: () => ({
    credit: "--",
    qaData: [],
    creditList: {
      rate1: 5,
      rate2: 5.5,
      rate3: 6,
      rate4: 6.5,
      rate5: 7,
      rate6: 7.5,
      rate7: 8,
      rate8: 8.5,
      rate9: 9,
    },
    bannerData: {},
    applyData: {},
  }),
  created() {
    this.getApplydata();
    this.getBannerData();
    this.getQaData();
    $("title").text(`資訊工程師專案 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {
      AOS.init();
    });
  },
  methods: {
    getBannerData() {
      axios
        .post(`${location.origin}/getBannerData`, { filter: "engineer" })
        .then((res) => {
          this.bannerData = res.data;
        });
    },
    getApplydata() {
      axios
        .post(`${location.origin}/getApplydata`, { filter: "engineer" })
        .then((res) => {
          this.applyData = res.data;
        });
    },
    getQaData() {
      axios
        .post(`${location.origin}/getQaData`, { filter: "engineer" })
        .then((res) => {
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
    background-image: linear-gradient(to right, #0559ac, #ffffff);
    margin: 0px auto;
  }

  .partner-card {
    overflow: auto;
    position: relative;
    background-color: #ecedf1;
  }

  @media screen and (max-width: 767px) {
    h2 {
      font-size: 25px;
      margin-bottom: 20px;
    }
  }
}
</style>


<template>
  <div class="freshGraduate-wrapper">
    <banner :data="this.bannerData" :isShowLoan="true"></banner>
    <div class="img-wrapper" data-aos="flip-up">
      <img :src="'./Image/worker_web.jpg'" class="img-fluid desktop" />
      <img :src="'./Image/worker_mobile.jpg'" class="img-fluid mobile" />
    </div>
    <applyDescribe :data="this.applyData" ref="apply"></applyDescribe>
    <join href="./Image/child_banner.jpg" :isShowLoan="true"></join>
    <qa :data="this.qaData" title="常見問題"></qa>
    <videoShare ref="videoShare" title="借款人怎麼說？" :data="this.shares"></videoShare>
  </div>
</template>

<script>
import videoShareComponent from "./component/videoShareComponent";
import bannerComponent from "./component/bannerComponent";
import joinComponent from "./component/joinComponent";
import applyDescribeComponent from "./component/applyDescribeComponent";
import qaComponent from "./component/qaComponent";

export default {
  components: {
    videoShare: videoShareComponent,
    banner: bannerComponent,
    join: joinComponent,
    applyDescribe: applyDescribeComponent,
    qa: qaComponent
  },
  data: () => ({
    qaData: [],
    bannerData: {},
    applyData: {}
  }),
  computed: {
    shares() {
      return this.$store.getters.SharesData.slice(0,4);
    }
  },
  created() {
    this.$store.dispatch("getSharesData", { category: "loan" });
    this.getApplydata();
    this.getBannerData();
    this.getQaData();
    $("title").text(`上班族貸款 - inFlux普匯金融科技`);
  },
  mounted() {
    $(this.$refs.apply.$refs.apply_slick).attr("data-aos", "fade-up");
    $(this.$refs.videoShare.$refs.share_content).attr("data-aos", "fade-left");
    AOS.init();
  },
  methods: {
    getBannerData() {
      axios.post("getBannerData", { filter: "freshgraduate" }).then(res => {
        this.bannerData = res.data;
      });
    },
    getApplydata() {
      axios.post("getApplydata", { filter: "freshgraduate" }).then(res => {
        this.applyData = res.data;
        this.$nextTick(() => {
          this.$refs.apply.createSlick();
        });
      });
    },
    getQaData() {
      axios.post("getQaData", { filter: "freshgraduate" }).then(res => {
        this.qaData = res.data;
      });
    }
  }
};
</script>

<style lang="scss">
.freshGraduate-wrapper {
  .loan-banner {
    img {
      bottom: -95%;
      width: 110%;
    }

    @media (max-width: 1024px) {
      img {
        bottom: -45%;
      }
    }

    @media (max-width: 1023px) {
      img {
        left: 0%;
        bottom: -10%;
      }
    }

    @media (max-width: 767px) {
      img {
        left: -49%;
        bottom: -10%;
        width: auto;
      }
    }
  }

  .img-wrapper {
    width: 100%;
    padding: 0px 5%;

    @media (max-width: 767px) {
      .desktop {
        display: none;
      }
    }

    @media (min-width: 767px) {
      .mobile {
        display: none;
      }
    }
  }
}
</style>


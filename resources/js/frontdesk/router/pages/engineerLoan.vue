<template>
  <div class="engineer-wrapper">
    <banner :data="this.bannerData" :isShowLoan="true"></banner>
    <div class="engineer-slick" ref="engineer_slick" data-aos="zoom-in">
      <div v-for="(item,index) in dossales" class="slick-item" :key="index">
        <img :src="item.imageSrc" class="img-fluid" />
      </div>
    </div>
    <applyDescribe :data="this.applyData" ref="apply"></applyDescribe>
    <join href="./Images/child_banner.jpg" :isShowLoan="true"></join>
    <qa :data="this.qaData" title="常見問題"></qa>
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
    applyData: {},
    dossales: [
      { imageSrc: "./Images/dossal1.png" },
      { imageSrc: "./Images/engineer_slick2.png" },
      { imageSrc: "./Images/dossal3.png" },
      { imageSrc: "./Images/dossal4.png" }
    ]
  }),
  created() {
    this.getApplydata();
    this.getBannerData();
    this.getQaData();
    $("title").text(`工程師專案 - inFlux普匯金融科技`);
  },
  mounted() {
    this.createSlick();
    $(this.$refs.apply.$refs.apply_slick).attr("data-aos", "fade-up");
    AOS.init();
  },
  methods: {
    getBannerData() {
      axios.post("getBannerData", { filter: "engineer" }).then(res => {
        this.bannerData = res.data;
      });
    },
    getApplydata() {
      axios.post("getApplydata", { filter: "engineer" }).then(res => {
        this.applyData = res.data;
        this.$nextTick(() => {
          this.$refs.apply.createSlick();
        });
      });
    },
    getQaData() {
      axios.post("getQaData", { filter: "engineer" }).then(res => {
        this.qaData = res.data;
      });
    },
    createSlick() {
      $(this.$refs.engineer_slick).slick({
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        dots: true,
        dotsClass: "slick-custom-dots",
        customPaging(slider, i) {
          return '<i class="fas fa-circle"></i>';
        },
        prevArrow: '<i class="fas fa-chevron-left arrow-left"></i>',
        nextArrow: '<i class="fas fa-chevron-right arrow-right"></i>',
        responsive: [
          {
            breakpoint: 767,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1
            }
          }
        ]
      });
    }
  }
};
</script>

<style lang="scss">
@import "./scss/slick";

.engineer-wrapper {
  width: 100%;

  .engineer-slick {
    padding: 50px 0px;

    @extend %slick-style;

    .slick-item {
      margin: 0px 10px;
      cursor: default;
    }

    .slick-list {
      width: 100%;
    }

    @media (min-width: 767px) {
      .slick-custom-dots {
        display: none;
      }
    }

    @media (max-width: 1023px) {
      .slick-item {
        margin: 0px 3px;
      }
    }
  }

  @media (max-width: 1023px) {
    .loan-banner img {
      left: -56%;
    }
  }

  @media (max-width: 767px) {
    .banner-content p {
      text-align: left !important;
    }

    .apply-title p {
      width: 80% !important;
    }
  }
}
</style>


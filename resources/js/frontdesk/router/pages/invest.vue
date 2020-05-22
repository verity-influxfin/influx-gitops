<template>
  <div class="invest-wrapper">
    <banner :data="this.bannerData" :isShowInvest="true"></banner>
    <div class="compare-wrapper" data-aos="zoom-in">
      <img :src="'./Image/invest_web.png'" class="img-fluid desktop" />
      <div class="invest-slick mobile" ref="invest_slick">
        <div v-for="(imgSrc,index) in this.investCategory" class="slick-item" :key="index">
          <img :src="imgSrc" class="img-fluid" />
        </div>
      </div>
    </div>
    <div class="explanation-wrapper">
      <div class="explanation-banner">
        <p>如何開始投資呢??</p>
      </div>
      <div class="explanation-img">
        <img class="hidden-desktop" :src="'./Image/how_to_invest_desktop.png'" />
        <img class="hidden-phone" :src="'./Image/how_to_invest_mobile.png'" />
      </div>
    </div>
    <join href="./Image/child_banner.jpg" :isShowInvest="true" subTitle="加入普匯完成你的財富目標吧！"></join>
    <qa :data="this.qaData" title="常見問題"></qa>
    <videoShare ref="videoShare" title="聽聽投資人怎麼說" :data="this.shares"></videoShare>
  </div>
</template>

<script>
import bannerComponent from "./component/bannerComponent";
import joinComponent from "./component/joinComponent";
import qaComponent from "./component/qaComponent";
import videoShareComponent from "./component/videoShareComponent";

export default {
  components: {
    videoShare: videoShareComponent,
    banner: bannerComponent,
    join: joinComponent,
    qa: qaComponent
  },
  data: () => ({
    qaData: [],
    bannerData: {},
    investCategory: [
      "./Image/invest_puhey.png",
      "./Image/invest_fund.png",
      "./Image/invest_stock.png"
    ]
  }),
  computed: {
    shares() {
      return this.$store.getters.SharesData;
    }
  },
  created() {
    this.$store.dispatch("getSharesData", { category: "invest" });
    this.getQaData();
    this.getBannerData();
    $("title").text(`債權投資 - inFlux普匯金融科技`);
  },
  mounted() {
    this.createSlick();
    $(this.$refs.videoShare.$refs.share_content).attr("data-aos", "fade-left");
    AOS.init();
  },
  methods: {
    createSlick() {
      $(this.$refs.invest_slick).slick({
        infinite: true,
        slidesToShow: 3,
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
    },
    getBannerData() {
      axios
        .post("getBannerData", { filter: "invest" })
        .then(res => {
          this.bannerData = res.data;
        })
        .catch(error => {
          console.error("getBannerData 發生錯誤，請稍後再試");
        });
    },
    getQaData() {
      axios.post("getQaData", { filter: "invest" }).then(res => {
        this.qaData = res.data;
      });
    }
  }
};
</script>

<style lang="scss">
@import "./scss/slick";

.invest-wrapper {
  width: 100%;

  .compare-wrapper {
    width: 100%;

    .desktop {
      padding: 0px 10%;
    }
  }

  .explanation-wrapper {
    .explanation-banner {
      background-color: #f7f7f7;
      height: 200px;
      text-align: center;
      position: relative;
      p {
        top: 50%;
        transform: translateY(-50%);
        font-size: 29px;
        font-weight: bolder;
        position: inherit;
      }
    }

    .explanation-img {
      padding: 10px;
      margin: 0px auto;
      width: fit-content;
    }
  }

  .invest-slick {
    padding: 50px 0px;

    @extend %slick-style;

    .slick-item {
      margin: 0px 10px;
      cursor: default;
    }

    .slick-list {
      width: 100%;
    }
  }

  .loan-banner img {
    width: 100% !important;
  }

  @media (max-width: 1023px) {
    .loan-banner img {
      bottom: 1%;
    }
  }

  @media (max-width: 767px) {
    .banner-content p {
      text-align: left !important;
    }

    .apply-title p {
      width: 80% !important;
    }

    .desktop {
      display: none;
    }

    .loan-banner img {
      width: auto !important;
      height: 100%;
      left: -100%;
      bottom: 0%;
    }
  }

  @media (min-width: 767px) {
    .slick-custom-dots,
    .mobile {
      display: none;
    }
  }
}
</style>


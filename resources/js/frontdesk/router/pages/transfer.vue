<template>
  <div class="transfer-wrapper">
    <div class="transfer-header">
      <div class="header-title">
        <img :src="'./image/child_banner.jpg'" />
        <div class="title">債權轉讓</div>
      </div>
      <div class="header-img">
        <img :src="'./image/transfer_banner_web.jpg'" class="img-fluid desktop" />
        <img :src="'./image/transfer_banner_mobile.jpg'" class="img-fluid mobile" />
      </div>
      <div class="header-footer">
        <p>滾石不生苔 , 隨時靈活轉換您的資金</p>
        <h2>如何使用債權轉讓?</h2>
      </div>
    </div>
    <div class="transfer-content" data-aos="zoom-in">
      <img :src="'./image/transfer_web.png'" class="img-fluid desktop" />
      <div class="transfer-slick mobile" ref="transfer_slick">
        <div v-for="(imgSrc,index) in this.transferFlow" class="slick-item" :key="index">
          <img :src="imgSrc" class="img-fluid" />
        </div>
      </div>
    </div>
    <qa :data="this.qaData" title="常見問題"></qa>
    <div class="transfer-footer">
      <h2>投資理財大補帖</h2>
      <div class="investTonic-slick" ref="investTonic_slick" data-aos="flip-left">
        <div v-for="(item,index) in this.investTonic" class="content-row" :key="index">
          <img :src="item.imgSrc" class="img-fluid" />
          <p>【普匯觀點】</p>
          <p>{{item.title}}</p>
          <br />
          <router-link :to="item.link" class="btn btn-danger">觀看大補帖</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import qaComponent from "./component/qaComponent";

export default {
  components: {
    qa: qaComponent
  },
  data: () => ({
    qaData: [],
    transferFlow: [
      "./image/transfer_flow1.png",
      "./image/transfer_flow2.png",
      "./image/transfer_flow3.png",
      "./image/transfer_flow4.png",
      "./image/transfer_flow5.png",
      "./image/transfer_flow6.png"
    ],
    investTonic: []
  }),
  created() {
    this.getQaData();
    this.getInvestTonicData();
    $("title").text(`債權轉讓 - inFlux普匯金融科技`);
  },
  mounted() {
    this.createTransferSlick();
    AOS.init();
  },
  watch: {
    investTonic() {
      this.$nextTick(() => {
        this.createInvestTonicSlick();
      });
    }
  },
  methods: {
    getInvestTonicData() {
      let $this = this;
      $.ajax({
        url: "getInvestTonicData",
        type: "POST",
        dataType: "json",
        success(data) {
          data.forEach((item, key) => {
            data[key].link = `/articlepage/investtonic-${item.id}`;
          });
          $this.investTonic = data;
        }
      });
    },
    getQaData() {
      let $this = this;
      $.ajax({
        url: "getQaData",
        type: "POST",
        data: {
          filter: "transfer"
        },
        dataType: "json",
        success(data) {
          $this.qaData = data;
        }
      });
    },
    createTransferSlick() {
      $(this.$refs.transfer_slick).slick({
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
    createInvestTonicSlick() {
      $(this.$refs.investTonic_slick).slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        prevArrow: '<i class="fas fa-chevron-left arrow-left"></i>',
        nextArrow: '<i class="fas fa-chevron-right arrow-right"></i>',
        responsive: [
          {
            breakpoint: 1023,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 1
            }
          },
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
@import './scss/slick';

%position {
  width: 100%;
  overflow: hidden;
  position: relative;
}

.transfer-wrapper {
  .transfer-header {
    .header-title {
      @extend %position;
      height: 160px;

      img {
        position: absolute;
        height: 210%;
      }

      .title {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #ffffff;
        font-weight: bolder;
        font-size: 33px;
      }
    }

    .header-img {
      text-align: center;

      @media (min-width: 767px) {
        padding: 10px;
        img {
          width: 80%;
        }
      }
    }

    .header-footer {
      @extend %position;
      background-color: #f7f7f7;
      height: 200px;
      text-align: center;
      padding-top: 40px;
      font-weight: bolder;

      h2 {
        font-weight: bolder;
      }

      @media (max-width: 767px) {
        height: 150px;
      }
    }
  }

  .transfer-slick {
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
  }

  .investTonic-slick {
    @extend %slick-style;
    width: 75%;
    margin: 0px auto;

    .slick-item {
      margin: 0px 10px;
      cursor: default;
    }

    .slick-list {
      width: 100%;
      margin: 0px auto;

      .slick-slide {
        padding: 14px;
      }
    }

    @media (max-width: 1023px) {
      .arrow-left {
        left: -20px;
      }
      .arrow-right {
        right: -20px;
      }
    }

    @media (max-width: 767px) {
      .arrow-left {
        left: 0;
      }
      .arrow-right {
        right: 0;
      }
    }
  }

  .transfer-footer {
    padding: 20px;

    h2 {
      padding-top: 50px;
      text-align: center;
      font-weight: bolder;
    }
  }

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
</style>

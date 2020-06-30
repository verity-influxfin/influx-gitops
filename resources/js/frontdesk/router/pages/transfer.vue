<template>
  <div class="transfer-wrapper">
    <div class="transfer-header">
      <div class="header-title">
        <img :src="'./Images/child_banner.jpg'" />
        <div class="title">債權轉讓</div>
      </div>
      <div class="header-img">
        <img :src="'./Images/transfer_banner_web.jpg'" class="img-fluid desktop" />
        <img :src="'./Images/transfer_banner_mobile.jpg'" class="img-fluid mobile" />
      </div>
      <div class="header-footer">
        <p>滾石不生苔 , 隨時靈活轉換您的資金</p>
        <h2>如何使用債權轉讓?</h2>
      </div>
    </div>
    <div class="transfer-content" data-aos="zoom-in">
      <img :src="'./Images/transfer_web.png'" class="img-fluid desktop" />
      <div class="transfer-slick mobile" ref="transfer_slick">
        <div v-for="(imgSrc,index) in this.transferFlow" class="slick-item" :key="index">
          <img :src="imgSrc" class="img-fluid" />
        </div>
      </div>
    </div>
    <div class="qa-card">
      <h2>還有其他問題嗎?</h2>
      <div class="row">
        <div class="qa-item" v-for="(item,index) in qaData.slice(0, 3)" :key="index">
          <p>{{item.title}}</p>
          <hr />
          <span v-html="item.content"></span>
        </div>
      </div>
      <div class="row">
        <div class="qa-item" v-for="(item,index) in qaData.slice(3)" :key="index">
          <p>Q：{{item.title}}</p>
          <hr />
          <span v-html="item.content"></span>
        </div>
      </div>
      <div class="row">
        <router-link class="btn link" style="margin:0px auto;" to="qa">
          更多問題
          <i class="fas fa-angle-double-right" />
        </router-link>
      </div>
    </div>
    <div class="transfer-footer">
      <h2>投資理財大補帖</h2>
      <div class="investTonic-slick" ref="investTonic_slick" data-aos="flip-left">
        <div v-for="(item,index) in this.investTonic" class="content-row" :key="index">
          <div class="img">
            <img :src="item.media_link" class="img-fluid" />
          </div>
          <p>{{item.post_title}}</p>
          <br />
          <router-link :to="item.link" class="btn btn-danger">觀看大補帖</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
    qaData: [],
    transferFlow: [
      "./Images/transfer_flow1.png",
      "./Images/transfer_flow2.png",
      "./Images/transfer_flow3.png",
      "./Images/transfer_flow4.png",
      "./Images/transfer_flow5.png",
      "./Images/transfer_flow6.png"
    ],
    investTonic: []
  }),
  created() {
    this.getQaData();
    this.getInvestTonicData();
    $("title").text(`債權轉讓 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {
      this.createTransferSlick();
      $(this.$root.$refs.banner).hide();
      this.$root.pageHeaderOffsetTop = 0;
      AOS.init();
    });
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
      axios.post("getInvestTonicData").then(res => {
        let data = res.data;
        $.each(data, (index, row) => {
          data[index].link = `/articlepage/investtonic-${row.ID}`;
        });
        this.investTonic = data;
      });
    },
    getQaData() {
      axios.post("getQaData", { filter: "transfer" }).then(res => {
        this.qaData = res.data;
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
@import "./scss/slick";

%position {
  width: 100%;
  overflow: hidden;
  position: relative;
}

.transfer-wrapper {
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

  .transfer-header {
    .header-title {
      @extend %position;
      height: 160px;

      img {
        min-width: 100%;
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

  .transfer-content {
    img {
      min-width: 100%;
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

    .content-row {
      margin: 0px 10px;
      cursor: default;

      .img {
        width: 100%;
        height: 190px;
        overflow: hidden;
      }
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

  .qa-card {
    padding: 30px;
    background: #ececec;
    overflow: hidden;

    h2 {
      text-align: center;
      color: #006bda;
    }

    .row {
      overflow: hidden;
      display: flex;

      .qa-item {
        background: #ffffff;
        padding: 10px;
        margin: 10px;
        border-radius: 10px;
        box-shadow: 0 0 5px #0069ff;
        width: 31.5%;

        p {
          color: #000000;
        }

        hr {
          color: #000000;
        }

        span {
          color: #000000;
        }
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

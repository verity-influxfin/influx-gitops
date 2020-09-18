<template>
  <div class="transfer-wrapper">
    <banner :data="bannerData"></banner>
    <div class="text-card">
      <div class="a-hr">
        <div class="a-s">
          <p>滾石不生苔 , 隨時靈活轉換您的資金</p>
        </div>
      </div>
    </div>
    <div class="transfer-card">
      <h2>如何使用債權轉讓</h2>
      <div class="hr"></div>
      <div class="transfer-slick mobile" ref="transfer_slick">
        <div v-for="(imgSrc,index) in this.transferFlow" class="slick-item" :key="index">
          <img :src="imgSrc" class="img-fluid" />
        </div>
      </div>
    </div>
    <qa :qaData="qaData" />
    <div class="invest-tonic-card">
      <h2>投資理財大補帖</h2>
      <div class="hr"></div>
      <div class="invest-tonic-slick" ref="investTonic_slick">
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
import qa from "../component/qaComponent";
import banner from "../component/bannerComponent";

export default {
  components: {
    qa,
    banner,
  },
  data: () => ({
    qaData: [],
    transferFlow: [
      "/images/transfer_flow1.png",
      "/images/transfer_flow2.png",
      "/images/transfer_flow3.png",
      "/images/transfer_flow4.png",
      "/images/transfer_flow5.png",
      "/images/transfer_flow6.png",
    ],
    investTonic: [],
    bannerData: {},
  }),
  created() {
    this.getQaData();
    this.getBannerData();
    this.getInvestTonicData();
    $("title").text(`債權轉讓 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {
      this.createTransferSlick();

      AOS.init();
    });
  },
  watch: {
    investTonic() {
      this.$nextTick(() => {
        this.createInvestTonicSlick();
      });
    },
  },
  methods: {
    getInvestTonicData() {
      axios.post(`${location.origin}/getInvestTonicData`).then((res) => {
        let data = res.data;
        $.each(data, (index, row) => {
          data[index].link = `/articlepage/?q=investtonic-${row.ID}`;
        });
        this.investTonic = data;
      });
    },
    getBannerData() {
      axios
        .post(`${location.origin}/getBannerData`, { filter: "transfer" })
        .then((res) => {
          this.bannerData = res.data;
        })
        .catch((error) => {
          console.error("getBannerData 發生錯誤，請稍後再試");
        });
    },
    getQaData() {
      axios.post(`${location.origin}/getQaData`, { filter: "transfer" }).then((res) => {
        this.qaData = res.data;
      });
    },
    createTransferSlick() {
      $(this.$refs.transfer_slick).slick({
        infinite: true,
        slidesToShow: 6,
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
              slidesToScroll: 1,
            },
          },
        ],
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
              slidesToScroll: 1,
            },
          },
          {
            breakpoint: 767,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
            },
          },
        ],
      });
    },
  },
};
</script>

<style lang="scss">
.transfer-wrapper {
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
      background-color: #6591be;
      position: relative;

      .a-s {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 214px;
        width: 80%;
        background-color: #083a6e;
        font-size: 29.5px;
        font-weight: bold;
        color: #ffffff;

        h3 {
          color: #ffffff;
          text-align: center;
          font-weight: bold;
          margin: 25px auto;
        }

        p {
          line-height: 1.7;
          position: absolute;
          top: 50%;
          transform: translate(-50%, -50%);
          left: 50%;
        }
      }
    }
  }

  .transfer-card {
    img {
      min-width: 100%;
    }

    .transfer-slick {
      padding: 50px 0px;

      .slick-item {
        margin: 0px 10px;
        cursor: default;
      }

      .slick-list {
        width: 100%;
      }

      .slick-custom-dots {
        position: absolute;
        padding: 15px 0px;
        text-align: center;
        color: #a0a0a0;
        display: none;
        list-style-type: none;
        left: 50%;
        transform: translateX(-50%);
        font-size: 13px;

        li {
          margin: 0px 3px;
        }

        .slick-active {
          color: #000000;
        }
      }

      %arrow {
        position: absolute;
        top: 50%;
        transform: translatey(-50%);
        font-size: 23px;
        z-index: 1;
        cursor: pointer;
      }

      .arrow-left {
        @extend %arrow;
        left: 0px;
      }

      .arrow-right {
        @extend %arrow;
        right: 0px;
      }
    }
  }

  .invest-tonic-card {
    padding: 20px;

    h2 {
      padding-top: 50px;
      text-align: center;
      font-weight: bolder;
    }

    .invest-tonic-slick {
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

      %arrow {
        position: absolute;
        top: 50%;
        transform: translatey(-50%);
        font-size: 23px;
        z-index: 1;
        cursor: pointer;
      }

      .arrow-left {
        @extend %arrow;
        left: 0px;
      }

      .arrow-right {
        @extend %arrow;
        right: 0px;
      }
    }
  }

  @media (max-width: 767px) {
    .text-card {
      display: none;
    }

    h2 {
      font-size: 25px;
      margin-bottom: 20px;
    }

    .link {
      width: 50%;
    }

    .transfer-card {
      .transfer-slick {
        .slick-custom-dots {
          display: flex;
        }
      }
    }

    .invest-tonic-card {
      padding: 10px;

      .invest-tonic-slick {
        width: 100%;
      }
    }
  }
}
</style>

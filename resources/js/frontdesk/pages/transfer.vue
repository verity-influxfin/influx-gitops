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
    <div class="tra-bg">
      <div class="intro-card">
        <h2>什麼是債權轉讓？</h2>
        <div class="hr"></div>
        <div class="cont">
          <p>
            漢皇重色思傾國，御宇多年求不得。楊家有女初長成，養在深閨人未識。天生麗質難自棄，一朝選在君王側。回眸一笑百媚生，六宮粉黛無顏色。春寒賜浴華清池，溫泉水滑洗凝脂。侍兒扶起嬌無力，始是新承恩澤時。
          </p>
          <p>
            雲鬢花顏金步搖，芙蓉帳暖度春宵。春宵苦短日高起，從此君王不早朝。承歡侍宴無閒暇，春從春遊夜專夜。後宮佳麗三千人，三千寵愛在一身。金屋妝成嬌侍夜，玉樓宴罷醉和春。姊妹弟兄皆列土，可憐光彩生門戶。
          </p>
        </div>
      </div>
      <apply
        title="如何使用債權轉讓"
        :requiredDocuments="applyData.requiredDocuments"
        :step="applyData.step"
      />
    </div>
    <div class="invest-tonic-card">
      <h2>投資理財大補帖</h2>
      <div class="hr"></div>
      <div class="invest-tonic-slick" ref="investTonic_slick">
        <router-link
          v-for="(item, index) in this.investTonic"
          class="content-row hvr-float-shadow"
          :to="item.link"
          :key="index"
        >
          <div class="img">
            <img :src="item.media_link" class="img-fluid" />
          </div>
          <p>{{ item.post_title }}</p>
        </router-link>
      </div>
    </div>
    <download :isLoan="false" :isInvest="true" />
    <qa :qaData="qaData" />
  </div>
</template>

<script>
import qa from "../component/qaComponent";
import banner from "../component/bannerComponent";
import apply from "../component/applyComponent";
import download from "../component/downloadComponent";

export default {
  components: {
    qa,
    apply,
    banner,
    download,
  },
  data: () => ({
    qaData: [],
    applyData: {},
    investTonic: [],
    bannerData: {},
  }),
  created() {
    this.getQaData();
    this.getApplydata();
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
    getApplydata() {
      axios
        .post(`${location.origin}/getApplydata`, { filter: "transfer" })
        .then((res) => {
          this.applyData = res.data;
        });
    },
    getQaData() {
      axios
        .post(`${location.origin}/getQaData`, { filter: "transfer" })
        .then((res) => {
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
        slidesToShow: 4,
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

  .tra-bg {
    background-image: url("../asset/images/transfer_bg.png");
    background-position: 50% 50%;
    background-repeat: no-repeat;
    background-size: cover;
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

  .intro-card {
    padding: 20px;

    .cont {
      width: 80%;
      margin: 30px auto;
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
      width: 90%;
      margin: 0px auto;

      .content-row {
        margin: 0px 10px;
        cursor: pointer;
        box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);

        .img {
          width: 100%;
          height: 190px;
          overflow: hidden;
        }

        p {
          text-align: center;
          color: #083a6e;
          font-size: 15px;
          margin: 0.5rem 0px;
          font-weight: bolder;
          height: 45px;
        }
      }

      .slick-list {
        width: 100%;
        margin: 0px auto;
        padding: 10px 0px;

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
        left: -2%;
      }

      .arrow-right {
        @extend %arrow;
        right: -2%;
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

    .intro-card {
      .cont {
        width: 100%;
        margin: 30px auto;
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

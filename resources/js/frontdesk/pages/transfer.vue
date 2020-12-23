<template>
  <div class="transfer-wrapper">
    <banner :data="bannerData" :isInvest="true"></banner>
    <div class="intro-card">
      <div class="t-c"><h2>什麼是債權轉讓？</h2></div>
      <div class="hr"></div>
      <div class="cont">
        <p>
          您可將持有的債權出售轉讓予其他投資人，隨時將投資標的轉換回現金。
        </p>
      </div>
    </div>
    <apply
      title="如何使用債權轉讓"
      :requiredDocuments="applyData.requiredDocuments"
      :step="applyData.step"
    />
    <experience :experiences="experiences" title="用戶回饋" />
    <div class="invest-tonic-card">
      <div class="t-c"><h2>投資理財大補帖</h2></div>
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
    <float />
  </div>
</template>

<script>
import qa from "../component/qaComponent";
import banner from "../component/bannerComponent";
import apply from "../component/applyComponent";
import experience from "../component/experienceComponent";
import download from "../component/downloadComponent";
import float from "../component/floatComponent";

export default {
  components: {
    qa,
    apply,
    banner,
    experience,
    download,
    float,
  },
  data: () => ({
    qaData: [],
    applyData: {},
    investTonic: [],
    bannerData: {},
  }),
  computed: {
    experiences() {
      return this.$store.getters.ExperiencesData;
    },
  },
  created() {
    this.$store.dispatch("getExperiencesData", "invest");
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
        speed: 1000,
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
        speed: 1000,
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
  .t-c {
    background-image: linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
    background-clip: text;
    width: fit-content;
    color: #ffffff00;
    margin: 0px auto;

    h2 {
      font-weight: bolder;
    }
  }

  .hr {
    width: 260px;
    height: 1px;
    background-image: linear-gradient(to right, #0559ac, #ffffff);
    margin: 0px auto;
  }

  .intro-card {
    padding: 20px;

    .cont {
      width: 80%;
      margin: 30px auto;
      text-align: center;
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

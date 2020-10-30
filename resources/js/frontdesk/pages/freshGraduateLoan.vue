<template>
  <div class="freshGraduate-wrapper">
    <banner :data="bannerData"></banner>
    <div class="text-card">
      <div class="a-hr">
        <div class="a-s">
          <p>「普匯・你的手機ATM」</p>
          <p>沒有煩人的「專員」打擾，只需要一隻可以上網的手機</p>
          <p>AI 24hr online滿足您的資金需求</p>
        </div>
      </div>
    </div>
    <credit :creditList="creditList" amount="20" />
    <apply
      title="申貸簡便四步驟"
      :requiredDocuments="applyData.requiredDocuments"
      :step="applyData.step"
    />
    <experience :experiences="experiences" title="真實回饋" />
    <div class="video-card">
      <h2>聽聽借款人怎麼說</h2>
      <div class="hr" />
      <div class="video-row" ref="video_slick">
        <div class="item" v-for="(item, index) in shares" :key="index">
          <iframe
            :src="item.video_link"
            frameborder="0"
            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
            style="height: 180px"
          ></iframe>
          <hr />
          <p>{{ item.post_title }}</p>
        </div>
      </div>
      <router-link class="btn link" to="vlog?q=loan"
        >影音列表<i class="fas fa-angle-double-right"
      /></router-link>
    </div>
    <download :isLoan="true" :isInvest="false" />
    <qa :qaData="qaData" />
  </div>
</template>

<script>
import banner from "../component/bannerComponent";
import download from "../component/downloadComponent";
import experience from "../component/experienceComponent";
import qa from "../component/qaComponent";
import apply from "../component/applyComponent";
import credit from "../component/creditComponent";

export default {
  components: {
    banner,
    download,
    experience,
    qa,
    apply,
    credit,
  },
  data: () => ({
    qaData: [],
    bannerData: {},
    applyData: {},
    creditList: {
      rate1: 5,
      rate2: 6,
      rate3: 7,
      rate4: 8,
      rate5: 9,
      rate6: 10,
      rate7: 11,
      rate8: 12,
      rate9: 13,
    },
  }),
  computed: {
    experiences() {
      let $this = this;
      let data = [];
      $.each($this.$store.getters.ExperiencesData, (index, row) => {
        if (row.rank === "officeWorker") {
          data.push(row);
        }
      });

      return data;
    },
    shares() {
      return this.$store.getters.VideoData.slice(0, 4);
    },
  },
  created() {
    this.$store.dispatch("getExperiencesData", "loan");
    this.$store.dispatch("getVideoData", { category: "loan" });
    this.getBannerData();
    this.getApplydata();
    this.getQaData();
    $("title").text(`上班族貸款 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {
      AOS.init();
    });
  },
  watch: {
    shares() {
      this.$nextTick(() => {
        this.createSlick();
      });
    },
  },
  methods: {
    getBannerData() {
      axios
        .post(`${location.origin}/getBannerData`, { filter: "freshgraduate" })
        .then((res) => {
          this.bannerData = res.data;
          this.bannerData.downloadhtml = `
          <a class="b-link" target="_blank" href="https://event.influxfin.com/R/url?p=webbanner">
            <img src="/images/apple-logo_invest.svg">
          </a>
          <a class="b-link" target="_blank" href="https://event.influxfin.com/R/url?p=webbanner">
            <img src="/images/android-logo_invest.svg">
          </a>
        `;
        });
    },
    getApplydata() {
      axios
        .post(`${location.origin}/getApplydata`, { filter: "freshgraduate" })
        .then((res) => {
          this.applyData = res.data;
        });
    },
    getQaData() {
      axios
        .post(`${location.origin}/getQaData`, { filter: "freshgraduate" })
        .then((res) => {
          this.qaData = res.data;
        });
    },
    createSlick() {
      $(this.$refs.video_slick).slick({
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        dots: false,
        arrows: false,
        speed: 1000,
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
  },
};
</script>

<style lang="scss">
.freshGraduate-wrapper {
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
      background-color: #083a6e;
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
          word-break: keep-all;
        }
      }
    }
  }

  .video-card {
    padding: 30px;
    overflow: hidden;
    position: relative;
    background: #f9f9f9;

    .video-row {
      display: flex;
      margin: 10px auto;
      width: fit-content;

      .item {
        margin: 0px 10px;

        hr {
          margin: 5px 0px;
          border-top: 2px solid #000000;
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
      .a-hr {
        height: initial;
        .a-s {
          position: relative;
          width: 100%;
          overflow: hidden;
          p {
            word-break: break-word;
          }
        }
      }
    }

    .video-card {
      padding: 10px;

      h2 {
        text-align: center;
      }

      .video-row {
        display: block;
        width: initial;

        .item {
          text-align: center;

          p {
            text-align: initial;
          }
        }
      }
    }
  }
}
</style>


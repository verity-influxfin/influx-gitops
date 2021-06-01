<template>
  <div class="college-wrapper">
    <banner :data="bannerData" :isBorrow="true"></banner>
    <target
      :items="applyData.item"
      text="滿20歲的在校生，準備您的雙證件、學生證、金融卡，在APP上拍照上傳，立即核准！"
    ></target>
    <apply title="申貸簡便四步驟" :step="applyData.step" />
    <div class="partner-card">
      <img src="../asset/images/circle.svg" class="img-fluid ib" />
      <div class="cnt">
        <div class="c-c">
          <div class="t-c">
            <h2>用戶遍佈全台大專院校</h2>
          </div>
          <div class="hr"></div>
          <p>
            超過156所大學生，在普匯找到生活費、補習費、出國遊學、交換學生...完成夢想！
          </p>
        </div>
        <div class="c-i">
          <img class="img-fluid" src="../asset/images/formosa.svg" />
        </div>
      </div>
    </div>
    <credit
      :creditList="creditList"
      amount="120000"
      text="數萬名大專院校、碩博學士會員<br>動動手拍拍照手機上傳，額度立即核准！<br>最快5分鐘申貸 10分鐘核准 1小時放款！"
    />
    <experience :experiences="experiences" title="同學回饋" />
    <download :isLoan="true" :isInvest="false" />
    <qa :qaData="qaData" />
    <float />
  </div>
</template>

<script>
import banner from "../component/bannerComponent";
import download from "../component/downloadComponent";
import experience from "../component/experienceComponent";
import qa from "../component/qaComponent";
import apply from "../component/applyComponent";
import target from "../component/targetComponent";
import credit from "../component/creditComponent";
import float from "../component/floatComponent";

export default {
  components: {
    banner,
    download,
    experience,
    qa,
    apply,
    target,
    credit,
    float,
  },
  data: () => ({
    qaData: [],
    bannerData: {},
    applyData: {},
  }),
  computed: {
    experiences() {
      let $this = this;
      let data = [];
      $.each($this.$store.getters.ExperiencesData, (index, row) => {
        if (row.rank === "student") {
          data.push(row);
        }
      });
      return data;
    },
    video() {
      return this.$store.getters.VideoData.slice(0, 4);
    },
  },
  created() {
    this.$store.dispatch("getExperiencesData", "loan");
    this.getBannerData();
    this.getQaData();
    this.getApplydata();
    $("title").text(`學生貸款 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {
      AOS.init();
    });
  },
  methods: {
    getBannerData() {
      axios
        .post(`${location.origin}/getBannerData`, { filter: "college" })
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
      axios.post(`${location.origin}/getApplydata`, { filter: "college" }).then((res) => {
        this.applyData = res.data;
      });
    },
    getQaData() {
      axios.post(`${location.origin}/getQaData`, { filter: "college" }).then((res) => {
        this.qaData = res.data;
      });
    },
  },
};
</script>

<style lang="scss">
.college-wrapper {
  width: 100%;

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
    width: 130px;
    height: 2px;
    background-image: linear-gradient(to right, #0559ac, #ffffff);
    margin: 0px auto;
  }

  .partner-card {
    overflow: auto;
    position: relative;
    background-color: #ecedf1;

    .ib {
      width: 100%;
    }

    .cnt {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      display: flex;
      width: 80%;

      .hr {
        margin: 1rem 0px;
      }

      .t-c {
        background-image: linear-gradient(to right, #3179d5 0%, #0ad0f4 50%, #3179d5 75%);

        margin: 0px;
      }

      %width {
        width: 50%;
      }

      .c-i {
        @extend %width;
      }

      .c-c {
        @extend %width;
        padding: 5rem 0px;

        p {
          font-size: 17px;
          font-weight: 500;
          line-height: 2.5;
          letter-spacing: 1px;
          color: #ffffff;
        }
      }
    }
  }

  @media screen and (max-width: 767px) {
    h2 {
      font-size: 25px;
    }

    .partner-card {
      background: #153a71;

      .ib {
        display: none;
      }

      .cnt {
        flex-direction: column;
        width: 100%;
        position: initial;
        transform: initial;
        padding: 10px;

        .c-c {
          width: 100%;
          padding: 10px 0px;

          .t-c {
            margin: 0px auto;
          }

          .hr {
            margin: 0px auto;
          }
        }

        .c-i {
          width: 100%;
        }
      }
    }
  }
}
</style>

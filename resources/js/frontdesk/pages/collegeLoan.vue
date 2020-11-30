<template>
  <div class="college-wrapper">
    <banner :data="bannerData" :isInvest="false"></banner>
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
            <h2>服務範圍廣</h2>
          </div>
          <div class="hr"></div>
          <p>超過156所大學生，不論是生活急需、還是補習進修費，都在找普匯！</p>
          <p>成立不到兩年已幫助超過2萬名學生，讓學生一想到借錢，就想到普匯！</p>
        </div>
        <div class="c-i">
          <img class="img-fluid" src="../asset/images/formosa.svg" />
        </div>
      </div>
    </div>
    <credit :creditList="creditList" amount="12" />
    <experience :experiences="experiences" title="同學回饋" />
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
import target from "../component/targetComponent";
import credit from "../component/creditComponent";

export default {
  components: {
    banner,
    download,
    experience,
    qa,
    apply,
    target,
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
      axios
        .post(`${location.origin}/getApplydata`, { filter: "college" })
        .then((res) => {
          this.applyData = res.data;
        });
    },
    getQaData() {
      axios
        .post(`${location.origin}/getQaData`, { filter: "college" })
        .then((res) => {
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
    background-image: linear-gradient(
      to right,
      #1e2973 0%,
      #319acf 50%,
      #1e2973 75%
    );
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
        background-image: linear-gradient(
          to right,
          #3179d5 0%,
          #0ad0f4 50%,
          #3179d5 75%
        );

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

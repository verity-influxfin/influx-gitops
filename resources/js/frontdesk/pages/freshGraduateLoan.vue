<template>
  <div class="freshGraduate-wrapper">
    <banner :data="bannerData" :isInvest="false"></banner>
    <target
      :items="applyData.item"
      text="年滿20歲的上班族，提供工作證明、聯徵報告，馬上給您最滿意的額度！<br><br>體驗金融科技帶來的線上借貸服務，就從『普匯･你的手機ATM』開始！"
    ></target>
    <apply
      title="申貸簡便四步驟"
      :requiredDocuments="applyData.requiredDocuments"
      :step="applyData.step"
    />
    <div class="avg-card">
      <img src="../asset/images/circle.svg" class="img-fluid" />
      <div class="cnt">
        <div class="c-t">
          <div class="item">
            <div class="t-c"><h3>即時審核，快速媒合放款</h3></div>
            <p>
              系統自動化審核加快申貸的速度，平均3分鐘完成申請、10分鐘核准、60分鐘到帳！
            </p>
          </div>
          <div class="item">
            <div class="t-c"><h3>全線上無人化</h3></div>
            <p>
              運用AI整合會員資料進行大數據分析，自動線上完成徵信、授信、甚至續約程序，操作方便簡單。
            </p>
          </div>
          <div class="item">
            <div class="t-c"><h3>隱私無照會</h3></div>
            <p>
              申貸全程無人干擾，更不會接到任何業務員的照會電話，也不會主動聯絡借款人及其緊急聯絡人。
            </p>
          </div>
        </div>
        <div class="c-i">
          <div class="i-line">
            <img class="img-fluid" src="../asset/images/m-line.png" />
          </div>
          <div class="i-m">
            <img class="img-fluid" src="../asset/images/mobile.png" />
          </div>
        </div>
        <div class="c-t">
          <div class="item">
            <div class="t-c"><h3>聯徵不留紀錄</h3></div>
            <p>
              不會留下任何貸款相關紀錄，不影響未來信用狀況，也不佔任何銀行額度。
            </p>
          </div>
          <div class="item">
            <div class="t-c"><h3>費率單純透明</h3></div>
            <p>
              只收取一次手續費，且費率以及每月需還本息金額公開透明，不用擔心被收取其他費用。
            </p>
          </div>
        </div>
      </div>
    </div>
    <credit :creditList="creditList" amount="20" />
    <experience :experiences="experiences" title="用戶回饋" />
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
import target from "../component/targetComponent";

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

  .avg-card {
    overflow: auto;
    position: relative;
    background-color: #ecedf1;

    .cnt {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      display: flex;
      overflow: hidden;
      height: 687px;

      .c-t {
        width: 37.5%;
        overflow: hidden;
        padding: 20px;
        position: relative;
        height: fit-content;
        top: 50%;
        transform: translate(0px, -50%);

        .item {
          margin: 0px 10px 3rem 10px;

          .t-c {
            background-image: linear-gradient(
              to right,
              #306fca 0%,
              #09d4f6 50%,
              #306fca 75%
            );
            margin: 0px;
            h3 {
              font-size: 24px;
            }
          }

          p {
            font-size: 16px;
            font-weight: 500;
            line-height: 1.5;
            letter-spacing: 1px;
            text-align: left;
            color: #ffffff;
            height: 90px;
          }
        }
      }

      .c-i {
        width: 25%;
        margin: 2.5rem 0px;
        position: relative;

        .i-m {
          position: absolute;
          top: 0px;
          left: 0px;
          animation: i-m-float 2.5s ease-in-out infinite;
        }
      }
    }
  }

  @keyframes i-m-float {
    0% {
      transform: translatey(-5px);
    }

    50% {
      transform: translatey(5px);
    }

    100% {
      transform: translatey(-5px);
    }
  }

  @media screen and (max-width: 767px) {
    h2 {
      font-size: 25px;
      margin-bottom: 20px;
    }
  }
}
</style>


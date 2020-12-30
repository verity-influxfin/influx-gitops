<template>
  <div class="freshGraduate-wrapper">
    <banner :data="bannerData" :isBorrow="true"></banner>
    <target
      :items="applyData.item"
      text="年滿20歲的上班族，提供工作證明、聯徵報告，馬上給您最滿意的額度！<br><br>體驗金融科技帶來的線上借貸服務，就從『普匯･你的手機ATM』開始！"
    ></target>
    <apply
      title="申貸簡單四步驟"
      :requiredDocuments="applyData.requiredDocuments"
      :step="applyData.step"
    />
    <div class="avg-card">
      <img src="../asset/images/circle.svg" class="img-fluid ib" />
      <div class="cnt">
        <div class="c-t c-l">
          <div class="item r">
            <div class="i-cnt">
              <div class="t-c"><h3>即時審核，快速媒合放款</h3></div>
              <div class="ccc"></div>
              <p>
                系統自動化審核加快申貸的速度，平均10分鐘完成申請、30分鐘核准、60分鐘到帳！
              </p>
            </div>
            <div class="img">
              <img src="../asset/images/num1.svg" class="img-fluid" />
            </div>
          </div>
          <div class="item r">
            <div class="i-cnt">
              <div class="t-c"><h3>全線上無人化</h3></div>
              <div class="ccc"></div>
              <p>
                運用AI整合會員資料進行大數據分析，自動線上完成徵信、授信、甚至續約程序，操作方便簡單。
              </p>
            </div>
            <div class="img">
              <img src="../asset/images/num2.svg" class="img-fluid" />
            </div>
          </div>
          <div class="item r">
            <div class="i-cnt">
              <div class="t-c"><h3>隱私無照會</h3></div>
              <div class="ccc"></div>
              <p>
                申貸全程無人干擾，更不會接到任何業務員的照會電話，也不會主動聯絡借款人及其緊急聯絡人。
              </p>
            </div>
            <div class="img">
              <img src="../asset/images/num3.svg" class="img-fluid" />
            </div>
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
        <div class="c-t c-r">
          <div class="item">
            <div class="img">
              <img src="../asset/images/num4.svg" class="img-fluid" />
            </div>
            <div class="i-cnt">
              <div class="t-c"><h3>聯徵不留紀錄</h3></div>
              <div class="ccc"></div>
              <p>不會留下任何貸款相關紀錄，不影響未來信用狀況，也不佔任何銀行額度。</p>
            </div>
          </div>
          <div class="item">
            <div class="img">
              <img src="../asset/images/num5.svg" class="img-fluid" />
            </div>
            <div class="i-cnt">
              <div class="t-c"><h3>費率單純透明</h3></div>
              <div class="ccc"></div>
              <p>
                只收取一次手續費，且費率以及每月需還本息金額公開透明，不用擔心被收取其他費用。
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <credit :creditList="creditList" amount="200000" />
    <experience :experiences="experiences" title="用戶回饋" />
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
import credit from "../component/creditComponent";
import target from "../component/targetComponent";
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
    background-image: linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
    background-clip: text;
    color: #ffffff00;
    margin: 0px auto;

    h2 {
      text-align: center;
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
    overflow: hidden;
    position: relative;
    background-color: #ecedf1;

    .ib {
      width: 100%;
    }

    .cnt {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      display: flex;
      overflow: hidden;
      height: 687px;

      .c-l {
        top: 50%;
        transform: translateY(-46%);

        .item {
          &:not(:last-of-type) {
            margin-bottom: 5rem;
          }
        }
      }

      .c-r {
        top: 50%;
        transform: translateY(-30%);

        .item {
          &:not(:last-of-type) {
            margin-bottom: 3rem;
          }
        }
      }

      .c-t {
        width: calc((100% - 383px) / 2);
        overflow: hidden;
        padding: 20px;
        position: relative;

        .item {
          display: flex;

          .i-cnt {
            width: calc(100% - 100px);
          }

          .img {
            margin: 0px 1rem;
            width: 100px;
          }

          .ccc {
            width: 50%;
            height: 2px;
            margin-bottom: 0.5rem;
            background-image: linear-gradient(
              to left,
              #3670d3 100%,
              #09d7f8 50%,
              #2e84da 0%
            );
          }

          .t-c {
            background-image: linear-gradient(
              to right,
              #306fca 0%,
              #09d4f6 50%,
              #306fca 75%
            );
            margin: 0px;
            h3 {
              font-size: 26px;
            }
          }

          p {
            font-size: 18px;
            font-weight: 500;
            line-height: 1.5;
            letter-spacing: 1px;
            text-align: left;
            color: #ffffff;
            height: 72px;
          }
        }

        .r {
          h3,
          p {
            text-align: right;
          }

          .ccc {
            margin: 0px 0px 0.5rem auto;
          }
        }
      }

      .c-i {
        width: 383px;
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

    .avg-card {
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
        height: auto;

        .c-l,
        .c-r {
          .item {
            &:not(:last-of-type) {
              margin-bottom: initial;
            }
          }
        }

        .c-t {
          width: 100%;
          transform: initial;
          top: 0px;
          padding: 0px;

          .r {
            h3 {
              text-align: initial;
              font-size: 24px;
            }
            p {
              text-align: initial;
              font-size: 16px;
            }

            .ccc {
              margin: 0px 0px 0.5rem 0px;
            }
          }

          .item {
            margin: 1rem 10px;

            .i-cnt {
              order: 1;
              width: calc(100% - 60px);
            }

            .img {
              order: 0;
              margin: 0px 0.5rem 0px 0px;
              width: 60px;
            }

            p {
              height: auto;
            }
          }
        }

        .c-i {
          display: none;
        }
      }
    }
  }
}
</style>

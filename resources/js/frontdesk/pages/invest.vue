<template>
  <div class="invest-wrapper">
    <banner :data="bannerData"></banner>
    <div class="text-card">
      <div class="a-hr">
        <div class="a-s">
          <p>小額分散，穩定報酬首選</p>
          <p>運用Fintech投資債權 → 小資族的最佳選擇</p>
          <p>1,000元開始投資創造你的被動收入</p>
        </div>
      </div>
    </div>
    <div class="desc-card">
      <h2>投資介紹</h2>
      <div class="hr"></div>
      <div class="box">
        <p>
          「債權投資」是近年慢慢興起的投資方式，透過購買借款人的債權，獲取每期回款本息的收益，在金融科技產業發展越來越成熟下，已經成為許多人最信賴的投資標的。
        </p>
        <p>
          投資不外乎就是追求創造額外的穩定收入，並且不用承擔高風險與流動性問題，但許多人不敢玩股票、不懂怎麼選擇債券，更沒有時間研究複雜的投資標的，「普匯債權」一定會是你最好的投資選擇。
        </p>
      </div>
      <div class="box">
        <h4>普匯債權特性</h4>
        <div class="gb">
          <div class="item">
            <h5>低風險</h5>
            <ul class="t">
              <li>對借款人嚴格審核</li>
              <li>逾期率不到3%</li>
              <li>小額分散</li>
              <li>完善貸後管理</li>
            </ul>
          </div>

          <div class="item">
            <h5>穩定高報酬</h5>
            <ul class="t">
              <li>平均年化報酬率8%~15%</li>
              <li>利率透明公開</li>
              <li>不受市場波動影響</li>
            </ul>
          </div>

          <div class="item">
            <h5>操作簡單</h5>
            <ul class="t">
              <li>無需盯盤</li>
              <li>公開透明的回款資訊</li>
            </ul>
          </div>

          <div class="item">
            <h5>安全性高</h5>
            <ul class="t">
              <li>法律合約明確</li>
              <li>帳務透明</li>
              <li>專屬帳戶</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="step-card">
      <h2>投資方式</h2>
      <div class="hr"></div>
      <ul class="nav" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" data-toggle="tab" href="#optional">
            <div class="img">
              <img
                src="../asset/images/invest_choose_optional.svg"
                class="img-fluid"
              />
            </div>
            <p>自選標的</p>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#smart"
            ><div class="img">
              <img
                src="../asset/images/invest_choose_smart.svg"
                class="img-fluid"
              />
            </div>
            <p>智能投資</p>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#quick"
            ><div class="img">
              <img
                src="../asset/images/invest_choose_quick.svg"
                class="img-fluid"
              />
            </div>
            <p>檢審速貸</p>
          </a>
        </li>
      </ul>
      <div class="tab-content">
        <div id="optional" class="tab-pane fade active show">
          <div class="pic">
            <img src="../asset/images/optional_invset.png" class="img-fluid" />
          </div>
          <div class="text">
            <h4>自選標的</h4>
            <p>
              依據信用等級、就讀學校、申貸原因、年化報酬率、期數，自由選擇您喜好的投資標的。
            </p>
          </div>
        </div>
        <div id="smart" class="tab-pane fade">
          <div class="pic">
            <img src="../asset/images/smart_invest.png" class="img-fluid" />
          </div>
          <div class="text">
            <h4>智能投資</h4>
            <p>
              設定您的偏好的信用等級、年化報酬率、期數等自動下標，24小時不錯失優質案件！
            </p>
          </div>
        </div>
        <div id="quick" class="tab-pane fade">
          <div class="pic">
            <img src="../asset/images/quick_invest.png" class="img-fluid" />
          </div>
          <div class="text">
            <h4>檢審速貸</h4>
            <p>申請人無提供最高學歷，同時您可以獲得更好的報酬率。</p>
          </div>
        </div>
      </div>
    </div>
    <apply
      title="投資簡單四步驟"
      :requiredDocuments="applyData.requiredDocuments"
      :step="applyData.step"
    />
    <div class="advantage-card">
      <h2>投資工具比一比</h2>
      <div class="hr" />
      <div class="adv-box">
        <div class="puhey">
          <div class="adv-item">
            <div class="i-b">
              <img src="../asset/images/invest_chart_a.svg" class="img-fluid" />
            </div>
            <div class="i-m" data-aos="fade-left" data-aos-duration="1000">
              <img
                src="../asset/images/invest_chart_a1.svg"
                class="img-fluid"
              />
            </div>
          </div>
        </div>
        <div class="other">
          <div class="adv-item">
            <div class="i-b">
              <img src="../asset/images/invest_chart_b.svg" class="img-fluid" />
            </div>
            <div
              class="i-m"
              data-aos="fade-left"
              data-aos-delay="900"
              data-aos-duration="1000"
            >
              <img
                src="../asset/images/invest_chart_b1.svg"
                class="img-fluid"
              />
            </div>
          </div>
          <div class="adv-item">
            <div class="i-b">
              <img src="../asset/images/invest_chart_c.svg" class="img-fluid" />
            </div>
            <div
              class="i-m"
              data-aos="fade-left"
              data-aos-delay="1200"
              data-aos-duration="1000"
            >
              <img
                src="../asset/images/invest_chart_c1.svg"
                class="img-fluid"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
    <experience :experiences="experiences" title="真實回饋" />
    <div class="video-card">
      <h2>聽聽投資人怎麼說</h2>
      <div class="hr" />
      <div class="video-row" ref="video_slick">
        <div class="item" v-for="(item, index) in video" :key="index">
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
      <router-link class="btn link" to="vlog/?q=invest"
        >影音列表 <i class="fas fa-angle-double-right"
      /></router-link>
    </div>
    <download :isLoan="false" :isInvest="true" />
    <qa :qaData="qaData" />
  </div>
</template>

<script>
import banner from "../component/bannerComponent";
import download from "../component/downloadComponent";
import experience from "../component/experienceComponent";
import qa from "../component/qaComponent";
import apply from "../component/applyComponent";

export default {
  components: {
    banner,
    experience,
    download,
    qa,
    apply,
  },
  data: () => ({
    qaData: [],
    bannerData: {},
    applyData: {},
  }),
  computed: {
    experiences() {
      return this.$store.getters.ExperiencesData;
    },
    video() {
      return this.$store.getters.VideoData;
    },
  },
  created() {
    this.$store.dispatch("getExperiencesData", "invest");
    this.$store.dispatch("getVideoData", { category: "invest" });
    this.getQaData();
    this.getBannerData();
    this.getApplydata();
    $("title").text(`債權投資 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {
      AOS.init();
      this.createSlick(this.$refs.type_slick);
      particlesJS.load("step-card", "data/invest.json");
    });
  },
  watch: {
    video() {
      this.$nextTick(() => {
        this.createSlick(this.$refs.video_slick);
      });
    },
  },
  methods: {
    createSlick(target) {
      $(target).slick({
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
    getBannerData() {
      axios
        .post("getBannerData", { filter: "invest" })
        .then((res) => {
          this.bannerData = res.data;
        })
        .catch((error) => {
          console.error("getBannerData 發生錯誤，請稍後再試");
        });
    },
    getApplydata() {
      axios
        .post(`${location.origin}/getApplydata`, { filter: "invest" })
        .then((res) => {
          this.applyData = res.data;
        });
    },
    getQaData() {
      axios
        .post(`${location.origin}/getQaData`, { filter: "invest" })
        .then((res) => {
          this.qaData = res.data;
        });
    },
  },
};
</script>

<style lang="scss">
.invest-wrapper {
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
      background-color: #6591be;
      position: relative;

      .a-s {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 80%;
        background-color: #083a6e;
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

  .desc-card {
    background: #f5f5f5;
    overflow: hidden;
    padding: 30px;

    .box {
      width: 1200px;
      margin: 1rem auto;
      overflow: auto;

      h4 {
        text-align: center;
        font-weight: bold;
      }

      p {
        color: #8e8e8e;
        font-weight: bold;
      }

      .gb {
        display: flex;
      }

      .item {
        background: #ffffff;
        box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);
        width: 100%;
        float: left;
        margin: 10px;
        padding: 10px 20px;
        border-radius: 10px;

        h5 {
          text-align: center;
          font-weight: 700;
          color: #083a6e;
        }

        .t {
          padding-left: 20px;

          li {
            list-style: none;
            background-image: url("../asset/images/invest.svg");
            background-position: 0 0;
            background-repeat: no-repeat;
            background-size: 24px 24px;
            padding-left: 30px;
            margin: 10px 0px;
          }
        }
      }
    }
  }

  .step-card {
    position: relative;
    padding: 30px;
    overflow: hidden;
    background-image: url("../asset/images/368.png");
    background-repeat: no-repeat;
    background-size: cover;

    h2 {
      font-weight: bolder;
      text-align: center;
      color: #ffffff;
    }

    .hr {
      width: 130px;
      height: 3px;
      background-image: linear-gradient(to right, #fbd900, #ffffff);
      margin: 20px auto;
    }

    .nav {
      width: fit-content;
      margin: 0px auto;

      .nav-link {
        display: flex;

        &:hover {
          .img {
            background: #fff29d;
          }
        }

        .img {
          background: #ffffff;
          padding: 10px;
          text-align: center;
          border-radius: 50%;
          border: 5px solid #08deb1;
          transition-duration: 0.5s;
          overflow: hidden;

          img {
            width: 50px;
            height: 50px;
          }
        }

        p {
          color: #ffffff;
          margin: 0px 15px;
          line-height: 80px;
          font-size: 22.5px;
          font-weight: 900;
          transition-duration: 0.5s;
        }
      }

      .active {
        .img {
          border: 5px solid #fbd900;
        }

        p {
          color: #fbd900;
        }
      }
    }

    .tab-pane.active {
      margin: 0px auto;
      width: fit-content;
      position: relative;
      transform: translate(-6rem, 0px);

      .pic {
        width: 450px;
        animation: phone-waving 2s infinite alternate linear;
      }

      .text {
        position: absolute;
        transform: translate(100%, -50%);
        right: 0px;
        top: 70%;

        h4 {
          color: #fbd900;
          font-weight: 900;
        }

        p {
          margin: 0px;
          color: #ffffff;
        }
      }
    }

    .fade {
      transition-duration: 0.5s;
    }

    @keyframes phone-waving {
      0% {
        transform: translateY(-10px);
      }

      50% {
        transform: translateY(0px);
      }

      100% {
        transform: translateY(10px);
      }
    }
  }

  .advantage-card {
    padding: 20px;
    overflow: hidden;

    .adv-box {
      display: flex;
      width: fit-content;
      margin: 0px auto;

      .puhey {
        width: 450px;
        margin: 20px;
        position: relative;

        .adv-item {
          position: absolute;
          top: 40%;
          transform: translateY(-50%);
          width: 450px;
          margin: 30px auto;

          .i-b {
            width: 450px;
          }

          .i-m {
            top: 28%;
            right: 0%;
            position: absolute;
            width: 210px;
            z-index: 1;
          }
        }
      }

      .other {
        width: fit-content;
        margin: 20px;

        .adv-item {
          position: relative;
          width: 280px;
          height: 280px;
          margin: 30px auto;

          .i-b {
            width: 280px;
            height: 280px;
          }

          .i-m {
            top: 28%;
            right: 0%;
            position: absolute;
            width: 130px;
            z-index: 1;
          }
        }
      }
    }
  }

  .video-card {
    padding: 30px;
    overflow: hidden;
    position: relative;
    background: #ffffff;

    .video-row {
      margin: 30px auto;
      width: 80%;

      .item {
        margin: 0px 10px;
        overflow: hidden;

        hr {
          margin: 5px 0px;
          border-top: 2px solid #000000;
        }
      }
    }
  }

  [data-aos="fade-left"] {
    transform: translate3d(40px, 0, 0);
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

    .desc-card {
      padding: 10px;

      .box {
        width: 100%;
        margin: 1rem auto 0px auto;

        .gb {
          flex-direction: column;
        }

        .item {
          width: 100%;
          margin: 0px;
          padding: 10px;
          height: auto;
          border-radius: 0px;
          box-shadow: 0 0 black;
          border: 1px solid #dbdbdb;

          &:first-of-type {
            border-radius: 10px 10px 0px 0px;
          }

          &:last-of-type {
            border-radius: 0px 0px 10px 10px;
          }

          .img {
            width: 20%;
          }

          .i-c {
            width: 80%;

            .t {
              padding-left: 20px;
            }
          }
        }
      }
    }

    .advantage-card {
      padding: 10px;
      .adv-box {
        flex-direction: column;
        width: 100%;

        .puhey {
          width: initial;
          margin: 10px 0px;

          .adv-item {
            width: 100%;
            margin: 10px auto;
            position: initial;
            transform: inherit;

            .i-m {
              width: 170px;
            }

            .i-b {
              width: initial;
              height: initial;
            }
          }
        }

        .other {
          margin: 10px 0px;
          display: flex;
          width: initial;

          .adv-item {
            width: 170px;
            height: 170px;
            margin: 0px auto;

            .i-m {
              width: 80px;
              transition-delay: 0.5s;
            }

            .i-b {
              width: 170px;
              height: 170px;
            }
          }
        }
      }
    }

    .step-card {
      padding: 10px;
      .nav {
        flex-wrap: inherit;

        .nav-link {
          padding: 0px;
          margin: 0px 10px;
          .img {
            display: none;
          }

          p {
            margin: 0px;
          }
        }
      }

      .tab-pane.active {
        transform: translate(0rem, 0px);
        display: flex;
        flex-direction: column;

        .pic {
          order: 2;
          width: 100%;
        }

        .text {
          order: 1;
          position: relative;
          transform: inherit;
          right: 0;
          top: 0%;

          h4 {
            display: none;
          }
        }
      }
    }

    .video-card {
      padding: 10px;

      .video-row {
        width: 100%;

        .item {
          iframe {
            width: 100%;
          }
        }
      }
    }
  }
}
</style>


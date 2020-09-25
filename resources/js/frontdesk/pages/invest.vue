<template>
  <div class="invest-wrapper">
    <banner :data="bannerData"></banner>
    <div class="text-card">
      <div class="a-hr">
        <div class="a-s">
          <p>小額分散，穩定報酬首選</p>
          <p>1,000元開始投資創造你的被動收入</p>
        </div>
      </div>
    </div>
    <div id="step-card" class="step-card">
      <h2>我要如何投資?</h2>
      <div class="hr"></div>
      <p>普匯平台分為三種投資方式</p>
      <div class="cnt">
        <div class="box">
          <div class="i-box">
            <div class="icon">
              <svg viewBox="0 0 60 60" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <defs>
                  <pattern id="optional" patternUnits="userSpaceOnUse" width="60" height="60">
                    <image
                      x="10"
                      y="10"
                      href="../asset/images/invest_choose_optional.svg"
                      width="40"
                      height="40"
                    />
                  </pattern>
                </defs>
                <polygon class="poly-fill" points="15 4 1 30 15 56 45 56 59 30 45 4" />
                <polygon
                  points="15 4 1 30 15 56 45 56 59 30 45 4"
                  fill="url(#optional)"
                  stroke-width="2"
                  stroke="#08deb1"
                />
              </svg>
            </div>
            <div class="text">
              <h4>自選標的</h4>
              <p>進入社會工作了，臨時有急缺？沒有煩人的「專員」打擾， 只有AI 24小時online滿足您的資金需求。</p>
            </div>
          </div>
          <div class="i-m-pic">
            <img src="../asset/images/optional _invset.png" class="img-fluid" />
          </div>
        </div>
        <div class="box">
          <div class="i-m-pic">
            <img src="../asset/images/smart_invest.png" class="img-fluid" />
          </div>
          <div class="i-box">
            <div class="icon">
              <svg viewBox="0 0 60 60" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <defs>
                  <pattern id="smart" patternUnits="userSpaceOnUse" width="60" height="60">
                    <image
                      x="10"
                      y="10"
                      href="../asset/images/invest_choose_smart.svg"
                      width="40"
                      height="40"
                    />
                  </pattern>
                </defs>
                <polygon class="poly-fill" points="15 4 1 30 15 56 45 56 59 30 45 4" />
                <polygon
                  points="15 4 1 30 15 56 45 56 59 30 45 4"
                  fill="url(#smart)"
                  stroke-width="2"
                  stroke="#08deb1"
                />
              </svg>
            </div>
            <div class="text">
              <h4>智能投資</h4>
              <p>進入社會工作了，臨時有急缺？沒有煩人的「專員」打擾， 只有AI 24小時online滿足您的資金需求。</p>
            </div>
          </div>
        </div>
        <div class="box">
          <div class="i-box">
            <div class="icon">
              <svg viewBox="0 0 60 60" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <defs>
                  <pattern id="quick" patternUnits="userSpaceOnUse" width="60" height="60">
                    <image
                      x="10"
                      y="10"
                      href="../asset/images/invest_choose_quick.svg"
                      width="40"
                      height="40"
                    />
                  </pattern>
                </defs>
                <polygon class="poly-fill" points="15 4 1 30 15 56 45 56 59 30 45 4" />
                <polygon
                  points="15 4 1 30 15 56 45 56 59 30 45 4"
                  fill="url(#quick)"
                  stroke-width="2"
                  stroke="#08deb1"
                />
              </svg>
            </div>
            <div class="text">
              <h4>檢審速貸</h4>
              <p>進入社會工作了，臨時有急缺？沒有煩人的「專員」打擾， 只有AI 24小時online滿足您的資金需求。</p>
            </div>
          </div>
          <div class="i-m-pic">
            <img src="../asset/images/quick_invest.png" class="img-fluid" />
          </div>
        </div>
      </div>
    </div>
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
              <img src="../asset/images/invest_chart_a1.svg" class="img-fluid" />
            </div>
          </div>
        </div>
        <div class="other">
          <div class="adv-item">
            <div class="i-b">
              <img src="../asset/images/invest_chart_b.svg" class="img-fluid" />
            </div>
            <div class="i-m" data-aos="fade-left" data-aos-delay="900" data-aos-duration="1000">
              <img src="../asset/images/invest_chart_b1.svg" class="img-fluid" />
            </div>
          </div>
          <div class="adv-item">
            <div class="i-b">
              <img src="../asset/images/invest_chart_c.svg" class="img-fluid" />
            </div>
            <div class="i-m" data-aos="fade-left" data-aos-delay="1200" data-aos-duration="1000">
              <img src="../asset/images/invest_chart_c1.svg" class="img-fluid" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <experience :experiences="experiences" />
    <div class="video-card">
      <h2>聽聽投資人怎麼說</h2>
      <div class="hr" />
      <div class="video-row" ref="video_slick">
        <div class="item" v-for="(item,index) in video" :key="index">
          <iframe
            :src="item.video_link"
            frameborder="0"
            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
            style="height:180px;"
          ></iframe>
          <hr />
          <p>{{item.post_title}}</p>
        </div>
      </div>
      <router-link class="btn link" to="vlog/?q=invest">影音列表</router-link>
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

export default {
  components: {
    banner,
    experience,
    download,
    qa,
  },
  data: () => ({
    qaData: [],
    bannerData: {},
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
        }
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
        width: 530px;
        margin: 20px;
        position: relative;

        .adv-item {
          position: absolute;
          top: 40%;
          transform: translateY(-50%);
          width: 530px;
          margin: 30px auto;

          .i-b {
            width: inherit;
            height: inherit;
          }

          .i-m {
            top: 28%;
            right: 0%;
            position: absolute;
            width: 260px;
            z-index: 1;
          }
        }
      }

      .other {
        width: fit-content;
        margin: 20px;

        .adv-item {
          position: relative;
          width: 330px;
          height: 330px;
          margin: 30px auto;

          .i-b {
            width: inherit;
            height: inherit;
          }

          .i-m {
            top: 28%;
            right: 0%;
            position: absolute;
            width: 155px;
            z-index: 1;
          }
        }
      }
    }
  }

  .step-card {
    background: #4296ba;
    position: relative;
    padding: 20px;
    overflow: hidden;
    z-index: 0;

    .particles-js-canvas-el {
      position: absolute;
      z-index: -1;
      top: 0;
      left: 0;
    }

    h2 {
      font-weight: bolder;
      text-align: center;
      color: #ffffff;
    }

    .hr {
      width: 130px;
      height: 3px;
      background-image: linear-gradient(to right, #fbd900, #ffffff);
      margin: 0px auto;
    }

    p {
      margin: 15px auto;
      font-size: 14px;
      text-align: center;
      color: #ffffff;
    }

    .cnt {
      width: 80%;
      margin: 0px auto;

      .box {
        display: flex;

        .i-box {
          width: 65%;
          display: flex;

          %center {
            position: relative;
            top: 50%;
            height: fit-content;
            transform: translateY(-50%);
            margin: 0px 20px;
          }

          .icon {
            width: 20%;
            @extend %center;

            .poly-fill {
              fill: #ffffff;
            }
          }

          .text {
            width: 80%;
            @extend %center;

            h4 {
              color: #ffffff;
              font-weight: bold;
            }

            p {
              text-align: left;
            }
          }
        }

        .i-m-pic {
          width: 35%;
        }

        &:hover {
          .i-box {
            .icon {
              .poly-fill {
                transition-duration: 0.5s;
                fill: #fffbb4;
              }
            }

            .text {
              h4 {
                transition-duration: 0.5s;
                color: #fbd900;
              }
            }
          }

          .i-m-pic {
            animation: phone-waving 2s infinite alternate linear;

            &:before {
              pointer-events: none;
              position: absolute;
              z-index: -1;
              content: "";
              top: 100%;
              left: 5%;
              height: 10px;
              width: 90%;
              background: radial-gradient(
                ellipse at center,
                rgba(0, 0, 0, 0.35) 0,
                rgba(0, 0, 0, 0) 80%
              );
            }
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
      display: none;
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
          }
        }
      }
    }

    .step-card {
      .cnt {
        width: 100%;

        .box {
          margin: 30px auto;

          .i-box {
            width: 100%;

            %center {
              margin: 0px 10px;
            }

            .icon {
              width: 30%;
            }
            .text {
              width: 70%;
            }
          }

          .i-m-pic {
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


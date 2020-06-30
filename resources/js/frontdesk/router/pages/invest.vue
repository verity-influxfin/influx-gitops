<template>
  <div class="invest-wrapper">
    <banner :data="bannerData" :isShowInvest="true"></banner>
    <div class="advantage-card">
      <h3>年化報酬率5~20% 穩定獲利低風險</h3>
    </div>
    <div class="step-card">
      <h3>如何開始投資呢?</h3>
      <h4>普匯平台分為三種投資方式</h4>
      <div class="step-list">
        <div class="item">
          <h5>自選標的</h5>
          <div class="content">
            <div class="app-pic"></div>
            <div class="directions"></div>
          </div>
        </div>
        <div class="item">
          <h5>智能投資</h5>
          <div class="content">
            <div class="directions"></div>
            <div class="app-pic"></div>
          </div>
        </div>
        <div class="item">
          <h5>檢審速貸</h5>
          <div class="content">
            <div class="app-pic"></div>
            <div class="directions"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="experience-card">
      <h2>聽聽投資人怎麼說</h2>
      <div class="progress">
        <div
          class="progress-bar"
          role="progressbar"
          style="width: 75%"
          aria-valuenow="75"
          aria-valuemin="0"
          aria-valuemax="100"
        ></div>
      </div>
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
      <router-link class="btn link" to="/vlog/invest">
        影音列表
        <i class="fas fa-external-link-alt"></i>
      </router-link>
    </div>
    <div class="download-card" :style="`background-image: url('./Images/19366.jpg')`">
      <div style="width: 64%;margin: 10px auto;">
        <img :src="'./Images/flow.png'" class="img-fluid" />
      </div>
    </div>
    <div class="qa-card">
      <h2>還有其他問題嗎?</h2>
      <div class="qa-row">
        <div class="qa-item" v-for="(item,index) in qaData.slice(0, 3)" :key="index">
          <p>{{item.title}}</p>
          <hr />
          <span v-html="item.content"></span>
        </div>
      </div>
      <div class="qa-row">
        <div class="qa-item" v-for="(item,index) in qaData.slice(3)" :key="index">
          <p>Q：{{item.title}}</p>
          <hr />
          <span v-html="item.content"></span>
        </div>
      </div>
      <div class="row">
        <router-link class="btn link" style="margin:0px auto;" to="qa">
          更多問題
          <i class="fas fa-angle-double-right" />
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import bannerComponent from "./component/bannerComponent";

export default {
  components: {
    banner: bannerComponent
  },
  data: () => ({
    qaData: [],
    bannerData: {}
  }),
  computed: {
    video() {
      return this.$store.getters.VideoData;
    }
  },
  created() {
    this.$store.dispatch("getVideoData", { category: "invest" });
    this.getQaData();
    this.getBannerData();
    $("title").text(`債權投資 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {
      $(this.$root.$refs.banner).hide();
      this.$root.pageHeaderOffsetTop = 0;
      AOS.init();
    });
  },
  watch: {
    video() {
      this.$nextTick(() => {
        this.createSlick();
      });
    }
  },
  methods: {
    createSlick() {
      $(this.$refs.video_slick).slick({
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
              slidesToScroll: 1
            }
          }
        ]
      });
    },
    getBannerData() {
      axios
        .post("getBannerData", { filter: "invest" })
        .then(res => {
          this.bannerData = res.data;
        })
        .catch(error => {
          console.error("getBannerData 發生錯誤，請稍後再試");
        });
    },
    getQaData() {
      axios.post("getQaData", { filter: "invest" }).then(res => {
        this.qaData = res.data;
      });
    }
  }
};
</script>

<style lang="scss">
.invest-wrapper {
  width: 100%;

  .progress {
    height: 4px;
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

  .advantage-card {
    padding: 30px;
    background: #2170ff;
    text-align: center;
    color: #ffffff;
  }

  .step-card {
    padding: 30px;
    background: #f3f3f3;
    text-align: center;
    color: #310071;

    h3 {
      font-weight: bolder;
      margin-bottom: 60px;
    }

    .step-list {
      padding: 10px;
      background: #ffffff;
      color: #000000;
      border-radius: 20px;
      box-shadow: 0px 0px 5px black;
      width: 80%;
      margin: 0px auto;

      .item {
        padding: 10px;
        margin: 30px 10px;
        h5 {
          color: #2170ff;
          font-weight: bolder;
          font-size: 35px;
        }

        .content {
          display: flex;
          padding: 10px 50px;
          .app-pic {
            height: 400px;
            width: 20%;
            background: #bbbbbb;
            box-shadow: inset 0px 0px 0px 20px black;
            border-radius: 20px;

            &:nth-of-type(odd) {
              margin-left: 5%;
            }

            &:nth-of-type(even) {
              margin-right: 5%;
            }
          }

          .directions {
            border-radius: 20px;
            background: #e4e4e4;
            padding: 10px;
            width: 60%;
            height: 400px;
            box-shadow: 0px 0px 20px #909090;

            &:nth-of-type(odd) {
              margin-right: 10%;
            }

            &:nth-of-type(even) {
              margin-left: 10%;
            }
          }
        }
      }
    }
  }

  .experience-card {
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

  .qa-card {
    padding: 30px;
    background: #ececec;
    overflow: hidden;

    h2 {
      text-align: center;
      color: #006bda;
    }

    .qa-row {
      overflow: hidden;
      display: flex;

      .qa-item {
        background: #ffffff;
        padding: 10px;
        margin: 10px;
        border-radius: 10px;
        box-shadow: 0 0 5px #0069ff;
        width: 31.5%;

        p {
          color: #000000;
        }

        hr {
          color: #000000;
        }

        span {
          color: #000000;
        }
      }
    }
  }

  .download-card {
    background-repeat: no-repeat;
    background-size: 100% 100%;
    overflow: hidden;

    .zxc {
      display: flex;
      width: fit-content;
      margin: 20px auto;

      %bg {
        width: 300px;
        height: 300px;
        border-radius: 10px;
        box-shadow: 0 0 8px black;
        background: #ffffff;
      }

      .loan {
        @extend %bg;
      }

      .shakehand {
        width: 100px;
        line-height: 300px;
      }

      .invest {
        @extend %bg;
      }
    }
  }

  @media screen and (max-width: 767px) {
    .link {
      width: 50%;
    }

    .step-card {
      padding: 10px;

      h3 {
        margin-bottom: 20px;
      }

      h4 {
        margin-bottom: 15px;
      }

      .step-list {
        width: 100%;

        .item {
          margin: 0px;
          padding: 0px;

          .content {
            flex-direction: column;
            padding: 10px;

            .app-pic {
              width: 70%;
              margin: 0px auto !important;
              order: 1;
            }

            .directions {
              width: 90%;
              margin: 20px auto !important;
              order: 2;
            }
          }
        }
      }
    }

        .experience-card {
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

    
    .qa-card {
      padding: 10px;

      .qa-row {
        display: block;

        .qa-item {
          width: 98%;
          margin: 2px 2px 12px 2px;
        }
      }
    }
  }
}
</style>


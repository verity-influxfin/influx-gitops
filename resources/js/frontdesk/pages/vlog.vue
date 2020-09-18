<template>
  <div class="vlog-wrapper" id="vlog-wrapper">
    <div class="header">
      <h2 v-if="category == 'share'">小學堂影音</h2>
      <h2 v-if="category == 'invest'">投資人專訪</h2>
      <h2 v-if="category == 'loan'">借款人專訪</h2>
      <div class="hr"></div>
      <div class="input-custom">
        <i class="fas fa-search"></i>
        <input type="text" class="form-control" v-model="filter" />
        <i class="fas fa-times" v-if="filter" @click="filter = ''"></i>
      </div>
    </div>
    <template v-if="filterVideo.length === 0">
      <div class="empty">
        <div class="empty-img">
          <img src="../asset/images/empty.svg" class="img-fluid" />
        </div>
        <h3>沒有結果</h3>
        <p>根據您的搜索，我們似乎找不到結果</p>
      </div>
    </template>
    <template v-else>
      <ul class="video-content" ref="content"></ul>
      <div class="pagination" ref="pagination"></div>
    </template>
  </div>
</template>

<script>
let postRow = Vue.extend({
  props: ["item"],
  template: `
    <li class="video">
      <div class="video-iframe">
        <iframe
          :src="item.video_link"
          frameborder="0"
          allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
          allowfullscreen
        ></iframe>
      </div>
      <div class="video-info">
        <h5>{{item.post_title}}</h5>
        <a
          v-if="item.type === 'video'"
          :href="'/videopage/?q='+item.ID"
          class="read"
        >閱讀內容</a>
        <a
          v-if="item.category === 'loan'"
          href="https://event.influxfin.com/R/url?p=webbanner"
          target="_blank"
          class="loan"
        >立即借款</a>
        <a
          v-if="item.category === 'invest'"
          href="https://event.influxfin.com/r/iurl?p=webinvest"
          target="_blank"
          class="invest"
        >立即投資</a>
        <a
          v-if="item.category === 'sponsor'"
          href="https://docs.google.com/forms/d/1Pp02TNm2wtWZdUwJpuW1J_ZCjx2QR_h8pgU5PNiE6ks/viewform?edit_requested=true"
          target="_blank"
          class="sponsor"
        >贊助申請</a>
      </div>
    </li>
  `,
});

export default {
  data: () => ({
    filter: "",
    pageHtml: "",
    category: "",
    filterVideo: [],
  }),
  computed: {
    video() {
      return this.$store.getters.VideoData;
    },
  },
  watch: {
    $route(to, from) {
      this.refresh();
    },
    video(newVal) {
      this.filterVideo = newVal;
      this.pagination();
    },
    filter(newVal) {
      this.filterVideo = [];
      this.video.forEach((row, index) => {
        if (row.post_title.toLowerCase().indexOf(newVal.toLowerCase()) !== -1) {
          this.filterVideo.push(row);
        }
      });
    },
    filterVideo() {
      window.dispatchEvent(new Event("resize"));
      this.pagination();
    },
  },
  created() {
    let urlParams = new URLSearchParams(window.location.search);
    this.category = urlParams.get("q");
    this.refresh(this.category);

    $("title").text(`影音分享 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {
      AOS.init();
      particlesJS.load("vlog-wrapper", "data/mobile.json");
    });
  },
  methods: {
    refresh(category) {
      this.$store.dispatch("getVideoData", { category });
    },
    pagination() {
      let $this = this;
      $this.$nextTick(() => {
        $($this.$refs.pagination).pagination({
          dataSource: $this.filterVideo,
          pageSize: 8,
          callback(data, pagination) {
            $($this.$refs.content).html("");
            data.forEach((item, index) => {
              let component = new postRow({
                propsData: {
                  item,
                },
              }).$mount();

              $($this.$refs.content).append(component.$el);
            });
          },
        });

        window.dispatchEvent(new Event("resize"));
      });
    },
  },
};
</script>

<style lang="scss">
.vlog-wrapper {
  padding: 30px;
  overflow: hidden;
  width: 100%;
  position: relative;

  .particles-js-canvas-el {
    position: absolute;
    top: 0;
    z-index: -1;
  }

  .header {
    width: 80%;
    margin: 20px auto;
    position: relative;

    h2 {
      font-weight: bolder;
      text-align: center;
      color: #061164;
    }

    .hr {
      width: 130px;
      height: 2px;
      background-image: linear-gradient(to right, #71008b, #ffffff);
      margin: 0px auto;
    }

    .input-custom {
      width: 300px;
      position: absolute;
      top: 0;
      right: 0;

      .form-control {
        padding: 5px 35px;
      }

      %iStyle {
        position: absolute;
        top: 50%;
        transform: translate(0, -50%);
        font-size: 20px;
        color: #083a6e;
      }

      .fa-search {
        @extend %iStyle;
        left: 10px;
      }

      .fa-times {
        @extend %iStyle;
        right: 10px;
        cursor: pointer;
      }
    }
  }

  .pagination {
    margin: 0px auto;
    width: fit-content;
  }

  .empty {
    text-align: center;
    margin: 30px auto;

    .empty-img {
      width: 200px;
      margin: 20px auto;
    }

    h3 {
      font-weight: bold;
    }
  }

  .video-content {
    width: 80%;
    overflow: auto;
    margin: 0px auto;
    padding: 0px;

    .video {
      margin: 10px;
      float: left;
      width: calc(100% / 3 - 20px);
      list-style: none;
      box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);
      background: #ffffff;

      .video-iframe {
        iframe {
          width: 100%;
          height: 210px;
        }
      }

      .video-info {
        margin: 10px;
        text-align: center;
        overflow: hidden;

        h5 {
          text-align: initial;
          font-size: 16px;
          font-weight: bolder;
          height: 38px;
        }

        a {
          display: block;
          width: fit-content;
          font-weight: bolder;
          float: right;
        }

        .read {
          color: #007bff;
        }

        .loan {
          color: #ffc107;
        }

        .invest {
          color: #00207b;
        }

        .sponsor {
          color: #177300;
        }
      }
    }
  }

  @media screen and (max-width: 767px) {
    padding: 10px;

    .header {
      width: 100%;

      .input-custom {
        width: 100%;
        position: relative;
        margin: 10px auto;
      }
    }

    .video-content {
      width: 100%;

      .video {
        width: 100%;
        margin: 10px 0px;

        .video-iframe {
          iframe {
            height: 190px;
          }
        }
      }
    }
  }
}
</style>

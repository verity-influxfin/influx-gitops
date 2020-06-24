<template>
  <div class="video-wrapper">
    <div class="header">
      <h2 v-if="$route.params.category === 'share'">小學堂影音</h2>
      <h2 v-if="$route.params.category === 'invest'">投資人專訪</h2>
      <h2 v-if="$route.params.category === 'loan'">借款人專訪</h2>
      <div class="input-custom">
        <i class="fas fa-search"></i>
        <input type="text" class="form-control" v-model="filter" />
        <i class="fas fa-times" v-if="filter" @click="filter = ''"></i>
      </div>
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
    </div>
    <ul class="video-content" ref="content"></ul>
    <div class="pagination" ref="pagination"></div>
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
          :href="'#/videopage/'+item.ID"
          class="btn btn-link"
        >閱讀內容</a>
        <a
          v-if="item.category === 'loan'"
          href="https://event.influxfin.com/R/url?p=webbanner"
          target="_blank"
          class="btn btn-warning"
        >立即借款</a>
        <a
          v-if="item.category === 'invest'"
          href="https://event.influxfin.com/r/iurl?p=webinvest"
          target="_blank"
          class="btn btn-primary"
        >立即投資</a>
        <a
          v-if="item.category === 'sponsor'"
          href="https://docs.google.com/forms/d/1Pp02TNm2wtWZdUwJpuW1J_ZCjx2QR_h8pgU5PNiE6ks/viewform?edit_requested=true"
          target="_blank"
          class="btn btn-success"
        >贊助申請</a>
      </div>
    </li>
  `
});

export default {
  data: () => ({
    filter: "",
    pageHtml: "",
    filterVideo: []
  }),
  computed: {
    video() {
      return this.$store.getters.VideoData;
    }
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
      this.pagination();
    }
  },
  created() {
    this.refresh();
    $("title").text(`影音分享 - inFlux普匯金融科技`);
  },
  methods: {
    refresh() {
      let category = this.$route.params.category;
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
                  item
                }
              }).$mount();

              $($this.$refs.content).append(component.$el);
            });
          }
        });
      });
    }
  }
};
</script>

<style lang="scss">
.video-wrapper {
  padding: 30px;
  overflow: auto;
  width: 100%;

  .progress {
    height: 4px;
  }

  .header {
    width: 80%;
    margin: 20px auto;
    position: relative;

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
        color: #002bff;
        text-shadow: 0 0 4px #002bff;
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

  .video-content {
    width: 75%;
    overflow: auto;
    margin: 0px auto;
    padding: 0px;

    .video {
      margin: 10px;
      float: left;
      width: 48%;
      list-style: none;

      .video-iframe {
        iframe {
          width: 100%;
          height: 300px;
        }
      }

      .video-info {
        margin: 10px;
        text-align: center;

        h5 {
          text-align: initial;
        }

        a {
          width: 100%;
        }

        .btn-link {
          background: #0072ff;
          transition-duration: 0.5s;
          color: #ffffff;
          font-weight: bolder;

          &:hover {
            background: #ff7818;
            text-decoration: none;
          }
        }
      }

      @media (max-width: 1025px) {
        display: block;

        .video-iframe {
          iframe {
            width: 100%;
            height: 350px;
          }
        }
      }
      @media (max-width: 767px) {
        display: block;

        .video-iframe {
          iframe {
            width: auto;
            height: auto;
          }
        }

        .video-info {
          margin: 0px;
        }
      }
    }
  }
}
</style>

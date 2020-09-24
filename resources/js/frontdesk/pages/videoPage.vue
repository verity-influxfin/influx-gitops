<template>
  <div class="video-wrapper">
    <h3 class="title">{{this.videoTitle}}</h3>
    <div style="display: flex;">
      <div class="main-view">
        <div class="contenier">
          <div class="title-img">
            <img :src="this.videoImg" class="img-fluid" />
          </div>
          <div class="video-container">
            <iframe
              :src="this.videoLink"
              frameborder="0"
              allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
              allowfullscreen
            ></iframe>
          </div>
          <div class="main-content" v-html="this.videoHtml"></div>
        </div>
        <div class="comments">
          <fb:comments
            :href="this.link"
            num_posts="10"
            notify="true"
            :width="(this.width*0.99).toFixed(0)"
          ></fb:comments>

          <div class="share-btn">
            <shareBtn :link="this.link"></shareBtn>
          </div>
        </div>
      </div>

      <div class="sub">
        <div class="box">
          <h4>最新文章</h4>
          <div>
            <div class="latest" v-for="(item,index) in latest" :key="index">
              <a :href="item.link">{{item.post_title}}</a>
              <div class="float-right">－{{item.post_modified.substr(0,10)}}</div>
            </div>
          </div>
        </div>
        <div class="box">
          <h4>分享文章</h4>
          <shareBtn :link="this.link"></shareBtn>
        </div>
        <div class="box">
          <h4>時間排序</h4>
          {{group}}
          <tree :data="list" :key="new Date()">
            <span class="tree-text" slot-scope="{ node }">
              <template v-if="!node.hasChildren()">
                －
                <a
                  :title="node.text.text"
                  :href="node.text.link"
                >{{`${node.text.text.substr(0, 10)}...`}}</a>
              </template>
              <template v-else>
                <i :class="[node.expanded() ? 'fas fa-folder-open' : 'fas fa-folder']"></i>
                {{node.text}}
              </template>
            </span>
          </tree>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import shareBtnComponent from "../component/shareBtnComponent";
import LiquorTree from "liquor-tree";

export default {
  components: {
    shareBtn: shareBtnComponent,
  },
  data: () => ({
    width: window.outerWidth,
    link: window.location.toString(),
    videoTitle: "",
    videoImg: "",
    videoLink: "",
    videoHtml: "",
    category: "",
  }),
  computed: {
    latest() {
      return this.$store.getters.VideoData.splice(0, 3);
    },
    list() {
      let groups = [];
      this.$store.getters.VideoData.forEach((kItem) => {
        let dateItem = kItem.post_modified.substr(0, 7).split("-");
        if (groups.filter((gItem) => gItem.text === dateItem[0]).length === 0) {
          groups.push({ text: dateItem[0], children: [] });
        }
      });

      this.$store.getters.VideoData.forEach((kItem) => {
        let dateItem = kItem.post_modified.substr(0, 7).split("-");
        groups.forEach((gItem) => {
          if (
            gItem.text === dateItem[0] &&
            gItem.children.filter((iItem) => iItem.text === dateItem[1])
              .length === 0
          ) {
            gItem.children.push({ text: dateItem[1], children: [] });
          }
        });
      });

      this.$store.getters.VideoData.forEach((kItem) => {
        groups.forEach((gItem, gindex) => {
          gItem.children.forEach((iItem, rindex) => {
            if (
              kItem.post_modified.substr(0, 7) === `${gItem.text}-${iItem.text}`
            ) {
              groups[gindex].children[rindex].children.push({
                text: {
                  text: kItem.post_title,
                  link: `${kItem.link}&category=${this.category}`,
                },
              });
            }
          });
        });
      });

      return groups;
    },
  },
  created() {
    this.getVideoPage();
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    this.category = urlParams.get("category");
    this.$store.dispatch("getVideoData", { category: this.category });
  },
  mounted() {
    this.$nextTick(() => {});
  },
  methods: {
    getVideoPage() {
      const queryString = window.location.search;
      const urlParams = new URLSearchParams(queryString);
      let type = urlParams.get("q");
      axios
        .post(`${location.origin}/getVideoPage`, { filter: type })
        .then((res) => {
          $("title").text(`${res.data.post_title} - inFlux普匯金融科技`);
          FB.XFBML.parse();
          this.videoTitle = res.data.post_title;
          this.videoImg = res.data.media_link;
          this.videoLink = res.data.video_link;
          this.videoHtml = res.data.post_content;
        });
    },
  },
};
</script>

<style lang="scss">
.video-wrapper {
  overflow: hidden;
  padding: 100px 30px 30px 30px;
  background-image: url("../asset/images/article.png");
  background-position: 50% 50%;
  background-repeat: no-repeat;
  background-size: 100% 100%;

  .title {
    font-size: 40px;
    font-weight: bold;
    line-height: normal;
    text-align: center;
    color: #061164;
    margin-bottom: 4rem;
  }

  .main-view {
    width: 70%;
    margin-left: 7rem;
    margin-right: 2rem;
    .contenier {
      .title,
      .title-img {
        text-align: center;
      }

      .video-container {
        text-align: center;
        margin: 20px auto;

        iframe {
          width: 70%;
          height: 350px;
        }
      }

      .main-content {
        padding: 20px;

        img {
          width: 100% !important;
          height: auto !important;
        }
      }
    }

    .comments {
      margin-top: 50px;
      position: relative;
      border-radius: 10px;
      box-shadow: 0px 2px 5px 0 #6ab0f2;
      background-color: rgba(255, 255, 255, 0.7);
      padding: 15px;
    }
  }

  .sub {
    width: 30%;
    margin-right: 6rem;

    .box {
      margin-bottom: 3rem;

      .latest {
        $gray: #9b9b9b;

        overflow: auto;
        margin-bottom: 5px;
        a {
          color: $gray;
          text-align: justify;
          display: block;
        }

        color: $gray;
      }
    }
  }

  .share-btn{
    display: none;
  }
}

@media screen and (max-width: 767px) {
  .video-wrapper {
    padding: 20px 10px 10px 10px;

    .title {
      font-size: 24px;
    }

    .flex {
      flex-direction: column;
    }

    .main-view {
      width: 100%;
      margin: 0px;

      .contenier {
        .video-container {
          margin: 0px auto;

          iframe {
            width: 100%;
            height: 200px;
          }
        }
      }
    }

    .sub {
      display: none;
    }

    .share-btn{
      display: block;
    }
  }
}
</style>


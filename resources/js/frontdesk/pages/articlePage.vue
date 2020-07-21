<template>
  <div class="article-wrapper">
    <div class="contenier">
      <h3 class="title" v-if="this.articleTitle">{{this.articleTitle}}</h3>
      <div class="title-img" v-if="this.articleImg">
        <img :src="this.articleImg" class="img-fluid" />
      </div>
      <div class="main-content" v-if="this.articleHtml" v-html="this.articleHtml"></div>
    </div>
    <div class="comments" v-if="$route.params.type.indexOf('news') === -1">
      <fb:comments
        :href="this.link"
        num_posts="10"
        notify="true"
        :width="(this.width*0.99).toFixed(0)"
      ></fb:comments>
      <shareBtn :link="this.link"></shareBtn>
    </div>
  </div>
</template>

<script>
import shareBtnComponent from "../component/shareBtnComponent";

export default {
  components: {
    shareBtn: shareBtnComponent
  },
  data: () => ({
    width: window.outerWidth,
    link: window.location.toString().replace("#", "%23"),
    articleTitle: "",
    articleImg: "",
    articleHtml: ""
  }),
  created() {
    this.getArticleData();
  },
  mounted() {
    this.$nextTick(() => {
      $(this.$root.$refs.banner).hide();
      this.$root.pageHeaderOffsetTop = 0;
    });
  },
  methods: {
    async getArticleData() {
      let type = this.$route.params.type.split("-");
      if (type[0] == "news") {
        await this.$store.dispatch("getNewsData");

        let news = this.$store.getters.NewsData[type[1]];
            $("title").text(`${news.title} - inFlux普匯金融科技`);
            FB.XFBML.parse();
            this.articleTitle = news.title;
            this.articleImg = news.image_url ? news.image_url : "";
            this.articleHtml = news.content;
      } else {
        axios
          .post("getArticleData", { filter: this.$route.params.type })
          .then(res => {
            $("title").text(`${res.data.post_title} - inFlux普匯金融科技`);
            FB.XFBML.parse();
            this.articleTitle = res.data.post_title;
            this.articleImg = res.data.media_link ? res.data.media_link : "";
            this.articleHtml = res.data.post_content;
          });
      }
    }
  }
};
</script>

<style lang="scss">
.article-wrapper {
  width: 100%;
  overflow: hidden;
  background: #dbe6ff;
  padding: 100px 30px 30px 30px;

  %bg {
    width: 80%;
    margin: 0px auto;
    background: #ffffff;
    padding: 20px;
    box-shadow: 0px 0px 20px black;
  }

  .contenier {
    @extend %bg;

    .title,
    .title-img {
      text-align: center;
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
    @extend %bg;
    margin-top: 50px;
    position: relative;

    &:before {
      content: "";
      transform: rotate(157deg);
      background-image: url("../asset/images/rings.svg");
      background-repeat: no-repeat;
      background-size: cover;
      position: absolute;
      top: -74px;
      width: 100px;
      height: 100px;
      left: 60px;
    }

    &:after {
      content: "";
      transform: rotate(157deg);
      background-image: url("../asset/images/rings.svg");
      background-repeat: no-repeat;
      background-size: cover;
      position: absolute;
      top: -74px;
      width: 100px;
      height: 100px;
      right: 60px;
    }
  }

  @media screen and (max-width: 767px) {
    padding: 10px;

    %bg {
      width: 100%;
      padding: 10px;
    }

    .contenier {
      .main-content {
        padding: 0px;
        img {
          width: 100% !important;
          height: auto !important;
        }
      }
    }

    .comments {
      margin-top: 30px;

      &:before {
        top: -40px;
        width: 50px;
        height: 50px;
        left: 20px;
      }

      &:after {
        top: -40px;
        width: 50px;
        height: 50px;
        right: 20px;
      }
    }
  }
}
</style>



<template>
  <div class="video-wrapper">
    <div class="contenier">
      <h3 class="title">{{this.videoTitle}}</h3>
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
      <shareBtn :link="this.link"></shareBtn>
    </div>
  </div>
</template>

<script>
import shareBtnComponent from "./component/shareBtnComponent";

export default {
  components: {
    shareBtn: shareBtnComponent
  },
  data: () => ({
    width: window.outerWidth,
    link: window.location.toString().replace("#", "%23"),
    videoTitle: "",
    videoImg: "",
    videoLink: "",
    videoHtml: ""
  }),
  created() {
    this.getVideoPage();
  },
  mounted() {
    this.$nextTick(() => {
      $(this.$root.$refs.banner).hide();
      this.$root.pageHeaderOffsetTop = 0;
    });
  },
  methods: {
    getVideoPage() {
      axios
        .post("getVideoPage", { filter: this.$route.params.id })
        .then(res => {
          $("title").text(`${res.data.post_title} - inFlux普匯金融科技`);
          FB.XFBML.parse();
          this.videoTitle = res.data.post_title;
          this.videoImg = res.data.media_link;
          this.videoLink = res.data.video_link;
          this.videoHtml = res.data.post_content;
        });
    }
  }
};
</script>

<style lang="scss">
.video-wrapper {
  overflow: hidden;
  padding: 100px 30px 30px 30px;
  width: 100%;

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
    }
  }

  .comments {
    @extend %bg;
    margin-top: 50px;
    position: relative;

    &:before {
      content: "";
      transform: rotate(157deg);
      background-image: url("../../asset/rings.svg");
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
      background-image: url("../../asset/rings.svg");
      background-repeat: no-repeat;
      background-size: cover;
      position: absolute;
      top: -74px;
      width: 100px;
      height: 100px;
      right: 60px;
    }
  }
}
</style>


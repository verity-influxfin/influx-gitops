<template>
  <div class="content-wrapper">
    <div class="main">
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
    <fb:comments
      :href="this.link"
      num_posts="10"
      notify="true"
      :width="(this.width*0.99).toFixed(0)"
    ></fb:comments>
    <shareBtn :link="this.link"></shareBtn>
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
    $("title").text(`${this.$route.params.id} - inFlux普匯金融科技`);
    this.getVideoPage();
  },
  methods: {
    getVideoPage() {
      axios
        .post("getVideoPage", { filter: this.$route.params.id })
        .then(res => {
          FB.XFBML.parse();
          this.videoTitle = res.data.title;
          this.videoImg = res.data.imageSrc;
          this.videoLink = res.data.videoLink;
          this.videoHtml = res.data.content;
        });
    }
  }
};
</script>

<style lang="scss">
</style>


<template>
  <div>
    <div class="main">
      <h3 class="title" v-if="this.articleTitle">{{this.articleTitle}}</h3>
      <div class="title-img" v-if="this.articleImg">
        <img :src="this.articleImg" class="img-fluid" />
      </div>
      <div class="main-content" v-if="this.articleHtml" v-html="this.articleHtml"></div>
    </div>
    <fb:comments
      v-if="$route.params.id.indexOf('news') === -1"
      :href="this.link"
      num_posts="10"
      notify="true"
      :width="(this.width*0.99).toFixed(0)"
    ></fb:comments>
    <shareBtn v-if="$route.params.id.indexOf('news') === -1" :link="this.link"></shareBtn>
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
    articleTitle: "",
    articleImg: "",
    articleHtml: ""
  }),
  created() {
    $("title").text(`${this.$route.params.id} - inFlux普匯金融科技`);
    this.getArticleData();
  },
  methods: {
    getArticleData() {
      let $this = this;

      $.ajax({
        url: "getArticleData",
        type: "POST",
        dataType: "json",
        data: {
          filter: $this.$route.params.id
        },
        success(data) {
          FB.XFBML.parse();
          $this.articleTitle = data.title;
          $this.articleImg = data.imageSrc;
          $this.articleHtml = data.content;
        }
      });
    }
  }
};
</script>

<style lang="scss">
</style>



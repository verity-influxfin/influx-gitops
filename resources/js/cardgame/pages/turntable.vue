<template>
  <div class="cardgame-wrapper">
    <nav class="page-header navbar navbar-expand-lg">
      <div class="web-logo">
        <router-link to="index"
        ><img :src="'/images/logo_new.png'" class="img-fluid"
        /></router-link>
      </div>
    </nav>
    <div class="cards">
      <span class="cardA">
        <span class="cardFace" style="background-image: url(../asset/cardGame1.svg)">普匯目前主要業務項目為何?<br /><br />(A)借貸/投資<br />(B)保險</span>
      </span>
      <span class="cardB">
        <span class="cardFace" style="background-image: url(../asset/cardGame2.svg)">普匯目前主要業務項目為何?<br /><br />(A)借貸/投資<br />(B)保險</span>
      </span>
      <span class="cardA">
        <span class="cardFace" style="background-image: url(../asset/cardGame3.svg)">普匯目前主要業務項目為何?<br /><br />(A)借貸/投資<br />(B)保險</span>
      </span>
      <span class="cardB">
        <span class="cardFace" style="background-image: url(../asset/cardGame4.svg)">普匯目前主要業務項目為何?<br /><br />(A)借貸/投資<br />(B)保險</span>
      </span>
      <span class="cardA">
        <span class="cardFace" style="background-image: url(../asset/cardGame5.svg)">普匯目前主要業務項目為何?<br /><br />(A)借貸/投資<br />(B)保險</span>
      </span>
      <span class="cardB">
        <span class="cardFace" style="background-image: url(../asset/cardGame6.svg)">普匯目前主要業務項目為何?<br /><br />(A)借貸/投資<br />(B)保險</span>
      </span>
      <span class="cardA">
        <span class="cardFace" style="background-image: url(../asset/cardGame7.svg)">普匯目前主要業務項目為何?<br /><br />(A)借貸/投資<br />(B)保險</span>
      </span>
      <span class="cardB">
        <span class="cardFace" style="background-image: url(../asset/cardGame8.svg)">普匯目前主要業務項目為何?<br /><br />(A)借貸/投資<br />(B)保險</span>
      </span>
      <span class="cardA">
        <span class="cardFace" style="background-image: url(../asset/cardGame9.svg)">普匯目前主要業務項目為何?<br /><br />(A)借貸/投資<br />(B)保險</span>
      </span>
      <span class="cardB">
        <span class="cardFace" style="background-image: url(../asset/cardGame10.svg)">普匯目前主要業務項目為何?<br /><br />(A)借貸/投資<br />(B)保險</span>
      </span>
      <span class="cardA">
        <span class="cardFace" style="background-image: url(../asset/cardGame11.svg)">普匯目前主要業務項目為何?<br /><br />(A)借貸/投資<br />(B)保險</span>
      </span>
      <span class="cardB">
        <span class="cardFace" style="background-image: url(../asset/cardGame12.svg)">普匯目前主要業務項目為何?<br /><br />(A)借貸/投資<br />(B)保險</span>
      </span>
    </div>
  </div>
</template>

<script>
export default {
  methods: {
    getImg(splide, newIndex) {
      this.selectedImg = `avatar${newIndex + 1}.svg`;
    },
    removeImg(authorImg) {
      axios
          .post("/deleteGreetingAuthorImg", { authorImg })
          .then((res) => {
            this.authorImg = "";
          })
          .catch((error) => {
            let errorsData = error.response.data;
            $(this.$refs.upload).val("");
            alert(errorsData);
          });
    },
    upload(e) {
      let imageData = new FormData();
      imageData.append("file", e.target.files[0]);

      axios.interceptors.request.use(
          (config) => {
            this.isLoading = true;
            return config;
          },
          (error) => {
            this.isLoading = false;
            return Promise.reject(error);
          }
      );

      axios.interceptors.response.use(
          (response) => {
            this.isLoading = false;
            return response;
          },
          (error) => {
            this.isLoading = false;
            return Promise.reject(error);
          }
      );

      axios
          .post("/uploadGreetingAuthorImg", imageData)
          .then((res) => {
            this.authorImg = `${res.data}`;
            alert("上傳成功！");
          })
          .catch((error) => {
            let errorsData = error.response.data;
            $(e.target).val("");
            alert(errorsData);
          });
    },
    share() {
      this.isCopyed = false;

      let data = {
        selectedImg: this.selectedImg,
        greetingWord: this.greetingWord,
        authorName: this.authorName,
        authorImg: this.authorImg,
      };

      let encodeData = encodeURIComponent(JSON.stringify(data));

      axios
          .post("/setGreetingData", data)
          .then((res) => {
            let string = `${location.origin}/greeting/show?token=${res.data.token}ber1b9er1be9&utm_source=greeting&utm_medium=track&utm_campaign=greetingShow`;
            $(".hide").val(string);
            $('.line-it-button').attr('data-url',string);
            $('.fb-share-button').attr('data-href',string);

            (function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = "https://connect.facebook.net/zh_TW/sdk.js#xfbml=1&version=v3.0";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));

            window.setTimeout(function() {
              LineIt.loadButton();
            }, 1500);

            $(this.$refs.messageModal).modal("show");
          })
          .catch((err) => {
            console.error(err);
          });
    },
    copy() {
      document.execCommand("selectAll");
      document.execCommand("Copy");
      this.isCopyed = true;
    },
  },
};
</script>

<style lang="scss">

</style>

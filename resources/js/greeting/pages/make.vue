<template>
  <div class="make-wrapper">
    <nav class="page-header navbar navbar-expand-lg">
      <div class="web-logo">
        <router-link to="index"
          ><img :src="'/images/logo_new.png'" class="img-fluid"
        /></router-link>
      </div>
    </nav>
    <div class="make-card">
      <div class="dialog">左右更換小普頭像</div>
      <Splide
        class="avatar-box"
        :options="avatarOptions"
        @splide:moved="getImg"
        ref="avatarSplide"
      >
        <template v-slot:controls>
          <div class="splide__arrows">
            <button class="splide__arrow splide__arrow--prev">
              <img class="img-fluid" src="../asset/autprev.svg" />
            </button>
            <button class="splide__arrow splide__arrow--next">
              <img class="img-fluid" src="../asset/autnext.svg" />
            </button>
          </div>
        </template>
        <SplideSlide v-for="(num, index) in 9" :key="index">
          <div class="img">
            <img :src="`/images/avatar${num}.svg`" class="img-fluid" />
          </div>
        </SplideSlide>
      </Splide>
      <div class="reel">
        <img src="../asset/reel.svg" class="img-fluid" />
      </div>
      <div class="zone">
        <img src="../asset/greeting_phone.svg" class="img-fluid g_phone" />
        <div class="cnt">
          <textarea
            class="form-control"
            rows="7"
            cols="10"
            maxlength="80"
            placeholder="輸入賀卡內容"
            autocomplete="off"
            v-model="greetingWord"
          ></textarea>
          <input
            type="text"
            class="form-control"
            style="text-align: end"
            placeholder="您是誰"
            name="name"
            autocomplete="off"
            v-model="authorName"
          />
          <div class="img-box">
            <div class="user-img">
              <img
                class="img-fluid"
                v-if="authorImg"
                :src="`/upload/greeting/${authorImg}`"
                @click="removeImg(authorImg)"
              />
              <input v-else type="file" @change="upload" ref="upload" />
              <img class="img-fluid" v-if="isLoading" src="../asset/g_loading.svg" />
            </div>
          </div>
        </div>
      </div>

      <button class="btn btn-greeting" @click="share">分享賀卡</button>
    </div>

    <div
      class="message-modal modal fade"
      ref="messageModal"
      role="dialog"
      aria-labelledby="modalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <div class="msg">
              <transition name="fade">
                <span class="copy" v-if="!isCopyed"><input type="text" class="hide" @click="copy()" /></span>
                <span class="copie" v-else> 賀卡已複製到您的剪貼簿，<br />趕快分享給好友吧！ </span>
              </transition>
              <br />
              <span><div class="line-it-button" data-lang="zh_Hant" data-type="share-a" data-ver="3" data-color="default" data-size="small" data-count="false" style="display: none;"></div></span>
              <span><div class="fb-share-button" data-layout="button"></div></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Splide, SplideSlide } from "@splidejs/vue-splide";
export default {
  components: {
    Splide,
    SplideSlide,
  },
  data: () => ({
    isCopyed: false,
    isLoading: false,
    selectedImg: "avatar1.svg",
    greetingWord: "",
    authorName: "",
    authorImg: "",
    avatarOptions: {
      type: "loop",
      autoplay: false,
      perPage: 1,
      perMove: 1,
      pagination: false,
      arrowPath: "",
    },
  }),
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

          LineIt.loadButton();
          (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "https://connect.facebook.net/zh_TW/sdk.js#xfbml=1&version=v3.0";
            fjs.parentNode.insertBefore(js, fjs);
          }(document, 'script', 'facebook-jssdk'));

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
.make-wrapper {
  .page-header {
    z-index: 11;
    background-image: linear-gradient(to right, #1e2973 0%, #319acf 36%, #1e2973 73%);

    .web-logo {
      width: 140px;
      margin: 0px;
    }
  }

  .make-card {
    background-image: url("../asset/greet_bg.png");
    background-size: cover;
    background-position: center;
    position: relative;
    overflow: auto;

    .dialog {
      background-image: url("../asset/dialog.svg");
      background-size: cover;
      background-position: center;
      padding: 10px 30px 25px 30px;
      width: fit-content;
      font-weight: bold;
      line-height: 1.25;
      color: #2c1f1f;
      margin: 10px auto 0px auto;
    }

    .avatar-box {
      width: 200px;
      margin: -20px auto -10px auto;
      height: 162px;

      .img {
        text-align: center;
        height: 172px;
        position: relative;

        img {
          position: absolute;
          bottom: 0px;
          left: 50%;
          transform: translate(-50%, 0px);
        }
      }

      .splide__arrow {
        background: #ffffff00;
        opacity: 1;
      }

      .splide__arrow--prev {
        left: -1rem;
      }
      .splide__arrow--next {
        right: -1rem;
      }
    }

    .reel {
      text-align: center;
      z-index: 1;
      position: relative;

      img {
        width: 257px;
      }
    }

    .zone {
      background-image: url("../asset/rolling-zone.svg");
      background-size: cover;
      background-position: center;
      width: 233px;
      height: 435px;
      margin: 0px auto;
      margin-top: -1px;
      padding: 7px 35px;
      position: relative;

      .form-control {
        background: #ffffff00;
        border: 3px dashed #af7200;
        margin: 5px auto;
      }

      .g_phone {
        position: absolute;
        top: 15px;
        left: 50%;
        transform: translate(-50%, 0px);
        z-index: 0;
      }

      .cnt {
        position: relative;
        z-index: 1;
        padding: 30px 5px 0px 5px;
      }

      .img-box {
        background-image: url("../asset/back.svg");
        background-size: cover;
        background-position: center;
        width: 153px;
        height: 110px;
        margin: 0px auto;
        position: relative;
        text-align: center;
        overflow: hidden;
        border-radius: 20px;

        .user-img {
          width: 152px;
          height: 108px;
          overflow: hidden;
          margin: 1px;
          position: relative;

          img {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
          }

          input[type="file"] {
            width: 100px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            overflow: hidden;
          }
        }
      }
    }

    .btn-greeting {
      background-image: linear-gradient(to top, #002160, #1f55a0);
      border-radius: 20px;
      padding: 5px 13px;
      margin: 1rem auto;
      display: block;
      color: #ffffff;
      font-weight: bolder;
    }
  }

  .message-modal {
    .modal-dialog {
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 100%;
      margin: 0px;
    }

    .modal-content {
      background: #fff0;
      border: 0px;

      .modal-body {
        padding: 0px;
      }
    }
    .fade-enter-active,
    .fade-leave-active {
      transition: opacity 0.5s;
    }
    .fade-enter,
    .fade-leave-to {
      opacity: 0;
    }

    .hide {
      transform: translate(-50%, -50%);
      width: 160px;
      display: inline-block;
    }

    .msg {
      background-image: url("../asset/msgbg.svg");
      background-size: cover;
      background-position: center;
      width: 360px;
      height: 146px;
      margin: 0px auto;
      text-align: center;
      padding: 3rem 0px;
      font-size: 15px;
      font-weight: bold;
      color: #ffffff;

      .copy {
        margin: 0px 0 0 149px;
      }

      .copie {
        margin: -15px 0 0 0;
      }

      .fb-share-button {
        display: inline-flex;
        margin: 0 15px 0px 3px
      }

      .line-it-button{
        width:52px!important;
        height: 20px!important;
      }
    }
  }
}
</style>

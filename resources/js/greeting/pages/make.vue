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
        <SplideSlide v-for="(num, index) in 8" :key="index">
          <div class="img">
            <img :src="`/images/avatar${num}.svg`" class="img-fluid" />
          </div>
        </SplideSlide>
      </Splide>
      <div class="reel">
        <img src="../asset/reel.svg" class="img-fluid" />
      </div>
      <div class="zone">
        <textarea
          class="form-control"
          rows="8"
          cols="10"
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
            />
            <input v-else type="file" @change="upload" />
          </div>
          <img class="img-fluid img-border" src="../asset/border.svg" />
        </div>
      </div>

      <button class="btn btn-greeting" @click="share">分享賀卡</button>
      <input type="text" class="hide" ref="hide" />
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
            <div class="msg">賀卡已複製到您的剪貼簿，<br />趕快分享給好友吧！</div>
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
    upload(e) {
      let imageData = new FormData();
      imageData.append("file", e.target.files[0]);
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
      let data = {
        selectedImg: this.selectedImg,
        greetingWord: this.greetingWord,
        authorName: this.authorName,
        authorImg: this.authorImg,
      };

      data = window.btoa(JSON.stringify(unescape(encodeURIComponent(JSON.stringify(data)))));

      let string = `${location.origin}/greeting/show?token=${data}&utm_source=greeting&utm_medium=track&utm_campaign=greetingShow`;

      axios
        .post(
          "https://api.reurl.cc/shorten",
          { url: string },
          {
            headers: {
              "reurl-api-key":
                "4070ff49d794e33218573b663c974755ecd3b235959f04df8a38b58d65165567c4f5d6",
            },
          }
        )
        .then((res) => {
          $(this.$refs.hide).val(res.data.short_url).focus().select();

          this.$nextTick(() => {
            document.execCommand("Copy");
            $(this.$refs.messageModal).modal("show");
          });
        })
        .catch((err) => {
          console.error(err);
        });
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
    height: calc(100vh - 64px);
    background-image: url("../asset/greet_bg.svg");
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
      margin: -10px auto 0px auto;
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

      .form-control {
        background: #ffffff00;
        border: 3px dashed #af7200;
        margin: 5px auto;
      }

      .img-box {
        background-image: url("../asset/back.svg");
        background-size: cover;
        background-position: center;
        width: 162px;
        height: 112px;
        margin: 0px auto;
        position: relative;
        text-align: center;
        overflow: hidden;

        .user-img {
          width: 150px;
          height: 100px;
          overflow: hidden;
          margin: 7px;
          position: relative;

          input[type="file"] {
            width: 80px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            overflow: hidden;
          }
        }

        .img-border {
          position: absolute;
          z-index: 1;
          top: 3px;
          left: 3px;
          pointer-events: none;
        }
      }
    }

    .btn-greeting {
      background-image: linear-gradient(to bottom, #e3322a, #a80015);
      border-radius: 20px;
      padding: 5px 13px;
      margin: 1rem auto;
      display: block;
      color: #ffffff;
      font-weight: bolder;
    }

    .hide {
      opacity: 0;
      height: 0px;
      position: absolute;
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

    .msg {
      background-image: url("../asset/msgbg.svg");
      background-size: cover;
      background-position: center;
      width: 360px;
      height: 146px;
      margin: 0px auto;
      text-align: center;
      padding: 2.5rem 0px;
      font-size: 15px;
      font-weight: bold;
      line-height: 1.4;
      color: #ffffff;
    }
  }
}
</style>

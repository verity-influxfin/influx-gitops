<template>
  <div class="show-wrapper">
    <transition name="fade">
      <div class="cover" v-if="!isPlay">
        <img
          src="../asset/play.svg"
          class="img-fluid play"
          v-if="greetingData"
        />
        <img src="../asset/play.svg" class="img-fluid play" v-else />
        <img
            src="../asset/greeting_influx.svg"
            class="img-fluid greeting_influx"
        />
      </div>
      <video v-else
          class="make-video"
          webkit-playsinline="true"
          playsinline="true"
          x-webkit-airplay="true"
          x5-video-player-type="h5"
          x5-video-player-fullscreen="true"
          x5-video-ignore-metadata="true"
          src="/upload/greeting/video.mp4"
      ></video>
    </transition>
    <div class="cnt" v-if="greetingData" @click.once="play($event)">
      <img src="../asset/greeting_logo.svg" class="top-center img-fluid" />
      <div class="greeting-card" ref="greetingcard">
        <div class="avatar-box">
          <img :src="`/images/${greetingData.selectedImg}`" class="img-fluid" />
        </div>
        <div class="reel">
          <img src="../asset/reel.svg" class="img-fluid" />
        </div>
        <div class="zone" ref="zone">
          <img src="../asset/greeting_phone.svg" class="img-fluid g_phone" />
          <div class="g-cnt">
            <div class="word form-control">
              &emsp;&emsp;{{ greetingData.greetingWord }}
            </div>
            <div class="name form-control">{{ greetingData.authorName }} 筆</div>
            <div class="img-box">
              <div class="user-img">
                <img
                  class="img-fluid"
                  :src="`/upload/greeting/${greetingData.authorImg}`"
                  @error="greetingData.authorImg = 'default.svg'"
                />
              </div>
            </div>
          </div>
        </div>
      </div>

      <a class="btn btn-greeting" ref="btn" href="/greeting/make" target="_blank"
        >製作我的賀卡</a
      >
      <img src="../asset/border-top-left.svg" class="top-left img-fluid" />
      <img src="../asset/border-top-right.svg" class="top-right img-fluid" />
      <img src="../asset/border-bottom-left.svg" class="bottom-left img-fluid" />
      <img src="../asset/border-bottom-right.svg" class="bottom-right img-fluid" />
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
    isPlay: false,
    greetingData: "",
  }),
  created() {
    const urlParams = new URLSearchParams(window.location.search);
    let token = urlParams.get("token");
    this.getgreetingData(token.replace("ber1b9er1be9", ""));
  },
  methods: {
    async getgreetingData(token) {
      axios
        .post("/getGreetingData", { token })
        .then((res) => {
          this.greetingData = res.data;
        })
        .catch((err) => {
          alert("參數錯誤");
        });
    },
    play($event) {
      $(".play").css("width", "50px");
      this.isPlay = true;
      this.$nextTick(() => {
        let video = $(".make-video")[0];
        let total = 20;
        let isCardShowed = false;
        let isZoneShowed = false;
        let isBtnShowed = false;
        video.play();

        let t = setInterval(() => {
          if (video.currentTime < total) {
            if (video.currentTime > 13 && !isCardShowed) {
              $(".greeting-card").css("opacity", 1);
              isCardShowed = true;
            }

            if (video.currentTime > 14 && !isZoneShowed) {
              $(".zone").css("height", "435px");
              isZoneShowed = true;
            }

            if (video.currentTime > 18 && !isBtnShowed) {
              $(".btn").css("opacity", 1);
              isBtnShowed = true;
            }
          }
        }, 100);
      });
    },
  },
};
</script>

<style lang="scss">
.show-wrapper {
  position: relative;
  overflow: hidden;

  %position {
    position: absolute;
  }

  .cover {
    @extend %position;
    width: 100%;
    height: 100vh;
    background: #EFFAFF;
    z-index: 1;

    .play {
      width: 100px;
      position: absolute;
      top: 46%;
      left: 50%;
      transform: translate(-50%, -50%);
      transition-duration: 1s;
    }

    .greeting_influx {
      width: 100px;
      position: absolute;
      top: 55.5%;
      left: 50%;
      transform: translate(-50%, -50%);
      transition-duration: 1s;
    }
  }

  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 1s;
  }
  .fade-enter,
  .fade-leave-to {
    opacity: 0;
  }

  .cnt {
    height: 100vh;
    width: 100%;
    position: relative;
    background-image: url("../asset/greetingborder.png");
    background-size: 100% 100%;
    background-position: inherit;
    z-index: 10;

    .greeting-card {
      position: absolute;
      height: 631px;
      top: 46%;
      left: 50%;
      transform: translate(-50%, -50%);
      opacity: 0;
      transition-duration: 2s;

      .avatar-box {
        width: 200px;
        margin: 0px auto -33px auto;
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

      .reel {
        text-align: center;
        z-index: 1;
        position: relative;
        width: 282px;
        margin-bottom: -27px;
      }

      .zone {
        background-image: url("../asset/rolling-zone.svg");
        background-size: cover;
        background-position: center;
        width: 233px;
        height: 0px;
        margin: -3px auto 0px auto;
        padding: 7px 35px;
        overflow: hidden;
        transition-duration: 2s;
        position: relative;

        .g_phone {
          position: absolute;
          top: 15px;
          left: 50%;
          transform: translate(-50%, 0px);
          z-index: 0;
        }

        .g-cnt {
          position: relative;
          z-index: 1;
          padding: 30px 5px 0px 5px;

          .form-control {
            background: #ffffff00;
            border: 0px;
            margin: 5px auto;
            font-weight: bold;
          }

          .word {
            height: 197px;
            padding: 0px;
          }
          .name {
            text-align: end;
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
            }
          }
        }
      }
    }

    .btn-greeting {
      background-image: linear-gradient(to top, #a80015, #e3322a);
      border-radius: 20px;
      padding: 5px 13px;
      margin: 1rem auto;
      color: #ffffff;
      font-weight: bolder;
      position: absolute;
      bottom: 75px;
      left: 50%;
      transform: translate(-50%, 0px);
      transition-duration: 2s;
      opacity: 0;
    }
    .top-center {
      @extend %position;
      left: 50%;
      transform: translate(-50%, 0px);
      top: 5px;
      width: 95px;
      z-index: 10;
    }
    .top-left {
      @extend %position;
      top: 3px;
      left: 3px;
      width: 79px;
      z-index: 10;
    }
    .top-right {
      @extend %position;
      top: 3px;
      right: 3px;
      width: 79px;
      z-index: 10;
    }
    .bottom-left {
      @extend %position;
      bottom: 3px;
      left: 3px;
      width: 79px;
      z-index: 10;
    }
    .bottom-right {
      @extend %position;
      bottom: 3px;
      right: 3px;
      z-index: 10;
      width: 79px;
    }
  }

  .make-video {
    width: 97%;
    top: 50%;
    transform: translate(0px, -50%);
    @extend %position;
  }
}
</style>

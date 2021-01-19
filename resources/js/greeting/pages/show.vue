<template>
  <div class="show-wrapper">
    <div class="cover" v-if="!isPlay" @click="play"></div>
    <template v-else>
      <video class="make-video" src="/upload/greeting/video.mp4"></video>
      <div class="cnt" v-if="greetingData">
        <div class="greeting-card" ref="greetingcard">
          <div class="avatar-box">
            <img :src="`/images/${greetingData.selectedImg}`" class="img-fluid" />
          </div>
          <div class="reel">
            <img src="../asset/reel.svg" class="img-fluid" />
          </div>
          <div class="zone" ref="zone">
            <div class="word form-control">
              &emsp;&emsp;{{ greetingData.greetingWord }}
            </div>
            <div class="name form-control">{{ greetingData.authorName }} 筆</div>
            <div class="img-box">
              <div class="user-img">
                <img
                  class="img-fluid"
                  :src="`/upload/greeting/${greetingData.authorImg}`"
                />
              </div>
              <img class="img-fluid img-border" src="../asset/border.svg" />
            </div>
          </div>
        </div>

        <a class="btn btn-greeting" ref="btn" href="/greeting/make" target="_blank"
          >製做我的賀卡</a
        >
        <img src="../asset/border-top-left.svg" class="top-left img-fluid" />
        <img src="../asset/border-top-right.svg" class="top-right img-fluid" />
        <img src="../asset/border-bottom-left.svg" class="bottom-left img-fluid" />
        <img src="../asset/border-bottom-right.svg" class="bottom-right img-fluid" />
      </div>
    </template>
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
    let search = urlParams.get("token");
    this.greetingData = JSON.parse(search);
  },
  methods: {
    play() {
      this.isPlay = true;
      this.$nextTick(() => {
        $(".make-video").get(0).play();

        setTimeout(() => {
          $(this.$refs.greetingcard).css("opacity", 1);
        }, 13000);

        setTimeout(() => {
          $(this.$refs.zone).css("height", "435px");
        }, 14000);

        setTimeout(() => {
          $(this.$refs.btn).css("opacity", 1);
        }, 19000);
      });
    },
  },
};
</script>

<style lang="scss">
.show-wrapper {
  position: relative;

  %position {
    position: absolute;
  }

  .cover {
    width: 100%;
    height: 100vh;
    background: #ffffff;
  }

  .cnt {
    height: 100vh;
    width: 100%;
    position: relative;
    background-image: url("../asset/greetingborder.png");
    background-size: 100% 100%;
    background-position: inherit;

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
        margin: 0px auto -20px auto;
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
      }

      .zone {
        background-image: url("../asset/rolling-zone.svg");
        background-size: cover;
        background-position: center;
        width: 233px;
        height: 0px;
        margin-top: -3px;
        padding: 7px 35px;
        overflow: hidden;
        transition-duration: 2s;

        .form-control {
          background: #ffffff00;
          border: 0px;
          margin: 5px auto;
          font-weight: bold;
        }

        .word {
          width: 163px;
          height: 210px;
          padding: 0px;
        }
        .name {
          text-align: end;
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
    }

    .btn-greeting {
      background-image: linear-gradient(to bottom, #e3322a, #a80015);
      border-radius: 20px;
      padding: 5px 13px;
      margin: 1rem auto;
      color: #ffffff;
      font-weight: bolder;
      position: absolute;
      bottom: 10px;
      left: 50%;
      transform: translate(-50%, 0px);
      transition-duration: 2s;
      opacity: 0;
    }

    .top-left {
      @extend %position;
      top: 0px;
      left: 0px;
    }
    .top-right {
      @extend %position;
      top: 0px;
      right: 0px;
    }
    .bottom-left {
      @extend %position;
      bottom: 0px;
      left: 0px;
    }
    .bottom-right {
      @extend %position;
      bottom: 0px;
      right: 0px;
    }
  }

  .make-video {
    width: 100%;
    height: 100vh;
    @extend %position;
  }
}
</style>

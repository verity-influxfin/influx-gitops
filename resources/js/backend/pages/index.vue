<template>
  <div class="wrapper" :style="`background-image:url(./images/3661950.jpg)`">
    <div class="header">
      <p class="date">{{ date }}</p>
      <h4 class="title">普匯官網後台系統</h4>
    </div>
    <div class="content">
      <router-link class="center-high left" to="milestone" v-if="userData.identity == 1"
        >里程碑</router-link
      >
      <router-link class="center-mid-high left" to="campus" v-if="userData.identity == 1"
        >普匯大使</router-link
      >
      <router-link class="center-mid left" to="feedback" v-if="userData.identity == 1"
        >心得回饋</router-link
      >
      <router-link
        class="center-lower-mid left"
        to="cooperation"
        v-if="userData.identity == 1"
        >合作訊息</router-link
      >
      <router-link class="center-low left" to="news" v-if="userData.identity == 1"
        >最新消息</router-link
      >
      <router-link class="center" to="knowledge">AI金融科技新知</router-link>
      <router-link class="center-low right" to="video">小學堂影音</router-link>
      <router-link
        class="center-lower-mid right"
        to="banner"
        v-if="userData.identity == 1"
        >頁面Banner</router-link
      >
      <router-link class="center-mid right" to="partner" v-if="userData.identity == 1"
        >合作夥伴</router-link
      >
      <router-link class="center-high right" to="media" v-if="userData.identity == 1"
        >媒體報導</router-link
      >
    </div>
    <div v-if="userData.identity == 1">
      <router-link class="notice-cooper" to="cooperation">
        <div class="img">
          <img class="img-fluid" src="../asset/images/cooperation.svg" />
        </div>
        <span>新合作</span>
      </router-link>
      <router-link class="notice-feedback" to="feedback">
        <div class="img">
          <img class="img-fluid" src="../asset/images/feedback.svg" />
        </div>
        <span>新回饋</span>
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
    date: "",
    userData:
      sessionStorage.length !== 0 ? JSON.parse(sessionStorage.getItem("userData")) : {},
  }),
  created() {
    this.timer = setInterval(() => {
      this.date = this.dateToString(new Date().getTime());
    }, 1000);
    $("title").text(`後臺系統 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {
      this.checkNewMessage();
    });
  },
  methods: {
    dateToString(milliseconds) {
      let dateObj = new Date(milliseconds);

      let date_item = {
        year: dateObj.getFullYear(),
        month: (dateObj.getMonth() + 1 < 10 ? "0" : "") + (dateObj.getMonth() + 1),
        day: (dateObj.getDate() < 10 ? "0" : "") + dateObj.getDate(),
        hour: (dateObj.getHours() < 10 ? "0" : "") + dateObj.getHours(),
        min: (dateObj.getMinutes() < 10 ? "0" : "") + dateObj.getMinutes(),
        sec: (dateObj.getSeconds() < 10 ? "0" : "") + dateObj.getSeconds(),
      };

      return `${date_item.year}/${date_item.month}/${date_item.day} ${date_item.hour}:${date_item.min}:${date_item.sec}`;
    },
    async checkNewMessage() {
      let cooRes = await axios.get("checkCooperation");
      let feedRes = await axios.get("checkFeedback");

      if (cooRes.data > 0) {
        $(".notice-cooper").addClass("notice-show");
      }

      if (feedRes.data > 0) {
        $(".notice-feedback").addClass("notice-show");
      }
    },
  },
  beforeDestroy() {
    if (this.timer) {
      clearInterval(this.timer);
    }
  },
};
</script>

<style lang="scss">
.wrapper {
  width: 100%;
  height: 92.9vh;
  background-repeat: no-repeat;
  background-size: 100% 100vh;
  overflow: hidden;
  position: relative;

  .header {
    overflow: auto;
    text-align: center;
    padding: 10px;
    margin: 20px 0px;
    color: #ffffff;

    .date {
      font-size: 53px;
      font-family: monospace;
    }

    .title {
      font-size: 41px;
    }
  }

  .content {
    %bg {
      border-radius: 50%;
      position: absolute;
      transform: translate(-50%, -50%);
      text-align: center;
      color: #ffffff;
      font-weight: bolder;
      box-shadow: 3px 3px 5px 0px #000000;
      font-size: 24px;
      cursor: pointer;
      text-decoration: none;
      transition-duration: 0.5s;

      &:hover {
        background: #d37e00;
      }
    }

    $bgcolor: #3198f4, #2c8dea, #2783df, #2279d4, #1d6fc9, #1964bf, #145ab4, #0f50a9,
      #0a469e, #053b94;

    @for $i from 1 through 10 {
      a:nth-child(#{$i}) {
        background: nth($bgcolor, $i);
      }
    }

    .disabled {
      cursor: default;
      background: gray !important;
      color: #000000;

      &:hover {
        background: gray;
      }
    }

    .center {
      width: 250px;
      height: 250px;
      line-height: 250px;
      top: 65%;
      left: 50%;
      @extend %bg;
    }

    .center-low {
      width: 200px;
      height: 200px;
      line-height: 200px;
      top: 55%;
      @extend %bg;

      &.right {
        left: 71%;
      }

      &.left {
        left: 29%;
      }
    }

    .center-lower-mid {
      width: 200px;
      height: 200px;
      line-height: 200px;
      top: 85%;
      @extend %bg;

      &.right {
        left: 66%;
      }

      &.left {
        left: 34%;
      }
    }

    .center-mid {
      width: 200px;
      height: 200px;
      line-height: 200px;
      top: 75%;
      @extend %bg;

      &.right {
        left: 85%;
      }

      &.left {
        left: 15%;
      }
    }

    .center-mid-high {
      width: 200px;
      height: 200px;
      line-height: 200px;
      top: 20%;
      @extend %bg;

      &.right {
        left: 80%;
      }

      &.left {
        left: 20%;
      }
    }

    .center-high {
      width: 200px;
      height: 200px;
      line-height: 200px;
      top: 45%;
      @extend %bg;

      &.right {
        left: 90%;
      }

      &.left {
        left: 10%;
      }
    }
  }

  %noticbg {
    display: flex;
    color: red;
    padding: 7px;
    background: #ffffff;
    width: fit-content;
    font-weight: bolder;
    position: absolute;
    right: 0px;
    transform: translateX(105%);
    box-shadow: 0 0 5px black;
    transition-duration: 1s;

    .img {
      width: 30px;
      filter: drop-shadow(0px 0px 2px white);
    }

    span {
      line-height: 30px;
    }

    &:hover {
      text-decoration: none;
    }
  }

  .notice-show {
    transform: translateX(0%) !important;
  }

  .notice-cooper {
    @extend %noticbg;
    top: 65px;
  }
  .notice-feedback {
    @extend %noticbg;
    top: 130px;
  }
}
</style>

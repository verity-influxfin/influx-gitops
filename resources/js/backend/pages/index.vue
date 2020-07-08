<template>
  <div class="wrapper" :style="`background-image:url(./Images/3661950.jpg)`">
    <div class="header">
      <p class="date">{{date}}</p>
      <h4 class="title">普匯官網後台系統</h4>
    </div>
    <div class="content">
      <router-link class="center-high left disabled" to="" v-if="userData.identity == 1">Coming soon</router-link>
      <router-link class="center-low left" to="market" v-if="userData.identity == 1">分期超市</router-link>
      <router-link class="center" to="knowledge">小學堂</router-link>
      <router-link class="center-low right" to="video">小學堂影音</router-link>
      <router-link class="center-high right disabled" to="" v-if="userData.identity == 1">Coming soon</router-link>
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
    date: "",
    userData:
      sessionStorage.length !== 0
        ? JSON.parse(sessionStorage.getItem("userData"))
        : {}
  }),
  created() {
    this.timer = setInterval(() => {
      this.date = this.dateToString(new Date().getTime());
    }, 1000);
    $("title").text(`後臺系統 - inFlux普匯金融科技`);
  },
  methods: {
    dateToString(milliseconds) {
      let dateObj = new Date(milliseconds);

      let date_item = {
        year: dateObj.getFullYear(),
        month:
          (dateObj.getMonth() + 1 < 10 ? "0" : "") + (dateObj.getMonth() + 1),
        day: (dateObj.getDate() < 10 ? "0" : "") + dateObj.getDate(),
        hour: (dateObj.getHours() < 10 ? "0" : "") + dateObj.getHours(),
        min: (dateObj.getMinutes() < 10 ? "0" : "") + dateObj.getMinutes(),
        sec: (dateObj.getSeconds() < 10 ? "0" : "") + dateObj.getSeconds()
      };

      return `${date_item.year}/${date_item.month}/${date_item.day} ${date_item.hour}:${date_item.min}:${date_item.sec}`;
    }
  },
  beforeDestroy() {
    if (this.timer) {
      clearInterval(this.timer);
    }
  }
};
</script>

<style lang="scss">
.wrapper {
  width: 100%;
  height: 92.9vh;
  background-repeat: no-repeat;
  background-size: 100% 100vh;
  overflow: auto;

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

    $bgcolor: #36a2ff, #4688ff, #106ad9, #0042d5, #003189;

    @for $i from 1 through 5 {
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
      top: 70%;
      left: 50%;
      @extend %bg;
    }

    .center-low {
      width: 200px;
      height: 200px;
      line-height: 200px;
      top: 60%;
      @extend %bg;

      &.right {
        left: 71%;
      }

      &.left {
        left: 29%;
      }
    }

    .center-high {
      width: 200px;
      height: 200px;
      line-height: 200px;
      top: 50%;
      @extend %bg;

      &.right {
        left: 90%;
      }

      &.left {
        left: 10%;
      }
    }
  }
}
</style>


<template>
  <div class="loan-wrapper">
    <div class="loan-title">
      <img :src="this.$props.data.bgHref" />
      <div class="title">{{this.$props.data.title}}</div>
    </div>
    <div class="loan-banner">
      <img :src="this.$props.data.bannerHref" />
      <div class="banner-content">
        <p v-for="(text,index) in this.$props.data.info" :key="index">{{text}}</p>
        <a
          v-if="this.$props.isShowLoan"
          class="btn btn-loan"
          href="https://event.influxfin.com/R/url?p=webbanner"
          target="_blank"
        >{{this.$props.data.btnText}}</a>
        <a
          v-if="this.$props.isShowInvest"
          class="btn btn-loan"
          href="https://event.influxfin.com/r/iurl?p=webinvest"
          target="_blank"
        >{{this.$props.data.btnText}}</a>
      </div>
    </div>
    <div class="loan-footer">
      <p v-html="this.$props.data.description"></p>
    </div>
  </div>
</template>

<script>
export default {
  props: ["data", "isShowLoan", "isShowInvest"]
};
</script>

<style lang="scss">
%position {
  width: 100%;
  overflow: hidden;
  position: relative;
}
.loan-title {
  @extend %position;
  height: 160px;

  img {
    min-width: 100%;
    position: absolute;
    height: 210%;
  }

  .title {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #ffffff;
    font-weight: bolder;
    font-size: 33px;
    word-break: keep-all;
  }
}

.loan-banner {
  @extend %position;
  height: 435px;

  img {
    min-width: 100%;
    position: absolute;
    bottom: -50%;
  }

  .banner-content {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    left: 7%;
    text-align: center;
    width: fit-content;

    p {
      color: #ffffff;
      text-shadow: 0 0 8px black;
      font-size: 37px;
      font-weight: bolder;
    }

    .btn-loan {
      border-radius: 30px;
      font-weight: 600;
      padding: 10px 25px;
      color: #ffffff;
      opacity: 0.8;
      width: 200px;
      background-color: #ffeb01;
      font-size: 19px;

      &:hover {
        background-color: #ff6a00;
      }
    }
  }

  @media (max-width: 767px) {
    img {
      bottom: -5%;
      height: 110%;
      left: -57%;
    }

    .banner-content {
      transform: translate(-50%, -50%);
      left: 50%;
    }
  }
}

.loan-footer {
  @extend %position;
  background-color: #f7f7f7;
  height: 200px;
  text-align: center;

  p {
    top: 50%;
    transform: translateY(-50%);
    font-size: 29px;
    font-weight: bolder;
    position: inherit;
  }

  @media (max-width: 767px) {
    height: 150px;

    p {
      font-size: 24px;
    }
  }
}
</style>
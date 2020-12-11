<template>
  <div class="product-banner">
    <img
      :src="$props.data.bannerDesktopHref"
      style="width: 100%"
      class="hidden-desktop"
    />
    <img
      :src="$props.data.bannerMoblieHref"
      style="width: 100%"
      class="hidden-phone"
    />
    <div class="banner-cnt">
      <h1 class="banner-title">{{ $props.data.productName }}</h1>
      <div class="banner-desc" v-html="$props.data.desc"></div>
      <a
        v-if="!isInvest"
        class="banner-download"
        href="https://event.influxfin.com/R/url?p=webbanner"
        target="_blank"
        ><img src="../asset/images/light-y.svg" class="img-fluid" />
        <div class="text">立即借款</div></a
      >
      <a
        v-else
        class="banner-download"
        href="https://event.influxfin.com/r/iurl?p=webinvest"
        target="_blank"
        ><img src="../asset/images/light-y.svg" class="img-fluid" />
        <div class="text">立即投資</div></a
      >
    </div>
  </div>
</template>

<script>
export default {
  props: ["data", "isInvest"],
};
</script>

<style lang="scss">
.product-banner {
  width: 100%;
  overflow: hidden;
  position: relative;

  .banner-cnt {
    position: absolute;
    top: 54%;
    left: 40px;
    transform: translate(0px, -50%);
    width: 545px;

    .banner-title {
      text-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
      font-size: 48px;
      font-weight: normal;
      line-height: 1.48;
      letter-spacing: 2.4px;
      text-align: left;
      color: #f2e627;
    }

    .banner-desc {
      text-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
      font-family: NotoSansTC;
      font-size: 30px;
      line-height: 1.5;
      letter-spacing: 1.8px;
      color: #ffffff;
    }

    .banner-download {
      width: 40%;
      margin: 1rem auto;
      position: relative;
      display: block;

      :hover {
        color: #ffffff;
        text-decoration: none;
      }

      .text {
        color: #ffffff;
        position: absolute;
        top: 51%;
        left: 47%;
        transform: translate(-50%, -50%);
        font-size: 20px;
      }
    }
  }
}

@media (max-width: 767px) {
  .product-banner {
    .banner-cnt {
      width: 100%;
      top: 25%;
      left: 50%;
      transform: translate(-50%, -50%);

      .banner-title {
        text-align: center;
      }

      .banner-desc {
        text-align: center;
        font-size: 20px;
      }

      .banner-download {
        width: 60%;
      }
    }
  }
}
</style>
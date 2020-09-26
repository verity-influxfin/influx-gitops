<template>
  <div
    class="product-banner"
    :style="`background-image:url(${$props.data.bannerHref})`"
  >
    <div class="phone-img">
      <img :src="$props.data.bannerPhone" class="img-fluid" />
    </div>
    <h1 class="banner-title">{{ $props.data.productName }}</h1>
    <div class="banner-download" v-html="$props.data.downloadhtml"></div>
  </div>
</template>

<script>
export default {
  props: ["data"],
};
</script>

<style lang="scss">
.product-banner {
  width: 100%;
  height: 75vh;
  overflow: hidden;
  position: relative;
  background-repeat: no-repeat;
  background-size: cover;
  padding: 0px 10%;

  .banner-download {
    position: absolute;
    top: 60%;
    left: 42%;
    transform: translate(-50%, -50%);
    display: flex;
    width: 500px;
    justify-content: space-between;

    .b-link {
      width: 200px;
      display: block;
    }
  }

  .banner-title {
    color: #ffffff;
    font-weight: bolder;
    position: absolute;
    top: 50%;
    left: 30%;
    transform: translate(-50%, -50%);
  }

  .phone-img {
    float: right;
    width: fit-content;
    margin-bottom: 5rem;

    img {
      width: 60%;
    }
  }
}

@media (max-width: 767px) {
  .product-banner {
    background-position: 50% 50%;
    height: 65vh;

    .phone-img {
      display: none;
    }

    .banner-title {
      left: 50%;
    }

    .banner-download {
      left: 50%;
      width: 90%;

      .b-link {
        width: 150px;
      }
    }

    .banner-content {
      position: initial;
      transform: initial;
      margin: 20px auto;
    }
  }
}
</style>
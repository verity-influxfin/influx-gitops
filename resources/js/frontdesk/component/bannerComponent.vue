<template>
  <div
    class="product-banner"
    :style="`background-image:url(${$props.data.bannerHref})`"
  >
    <div class="phone-img">
      <img :src="$props.data.bannerPhone" class="img-fluid" />
    </div>
    <h1 class="banner-title">{{ $props.data.productName }}</h1>
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

    .banner-content {
      position: initial;
      transform: initial;
      margin: 20px auto;
    }
  }
}
</style>
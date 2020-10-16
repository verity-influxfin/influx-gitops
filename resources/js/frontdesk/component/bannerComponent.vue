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

    <div class="phone-img">
      <img :src="$props.data.bannerPhone" class="img-fluid" />
    </div>
    <h1 class="banner-title">{{ $props.data.productName }}</h1>
    <h2 class="banner-desc">{{ $props.data.desc }}</h2>
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
  height: 547px;
  overflow: hidden;
  position: relative;

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

  .banner-desc{
    color: #ffffff;
    position: absolute;
    top: 75%;
    left: 50%;
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
    height: 547px;

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
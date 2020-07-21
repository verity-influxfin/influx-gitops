<template>
  <div class="product-wrapper">
    <div class="product-banner">
      <img :src="this.$props.data.bannerHref" />
      <div class="banner-content">
        <p v-for="(text,index) in this.$props.data.info" :key="index">{{text}}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["data"]
};
</script>

<style lang="scss">

.product-banner {
  width: 100%;
  overflow: hidden;
  position: relative;
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
</style>